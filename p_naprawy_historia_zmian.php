<?php include_once('header.php');
?>
<?php
	if ($_REQUEST[id]=='0') exit;

	$sql="SELECT * FROM $dbname.serwis_naprawa_historia_zmian WHERE (naprawa_hz_naprawa_id=$_GET[id]) and (naprawa_hz_widoczne=1) and (belongs_to=$es_filia)";	
	$result = mysql_query($sql, $conn) or die($k_b);
	$count_rows = mysql_num_rows($result);

			$sql_up = "SELECT up_id, up_pion_id FROM $dbname.serwis_komorki WHERE (up_nazwa='$_GET[up]') and (belongs_to=$es_filia) LIMIT 1";
			$wynik = mysql_query($sql_up, $conn) or die($k_b);
			$dane_up = mysql_fetch_array($wynik);
			$temp_up_id = $dane_up['up_id'];
			$temp_pion_id = $dane_up['up_pion_id'];
			
			// nazwa pionu z id pionu
			$sql_get_pion = "SELECT pion_nazwa FROM $dbname.serwis_piony WHERE (pion_id='$temp_pion_id') LIMIT 1";
			$wynik_get_pion = mysql_query($sql_get_pion, $conn) or die($k_b);
			$dane_get_pion = mysql_fetch_array($wynik_get_pion);
			$temp_pion_nazwa = $dane_get_pion['pion_nazwa'];
			// koniec ustalania nazwy pionu
			
if ($count_rows>0) {
	pageheader("Historia zmian informacji o naprawianym sprzęcie",1,0);
	infoheader("<b>$_GET[typ] $_GET[model]</b> (SN: $_GET[sn])<br />z<br /><b>$temp_pion_nazwa $_GET[up]</b>");

	starttable("");
	th(";c;LP|120;c;Data zmiany|100;c;Osoba zmieniająca|;c;Opis zmian",$es_prawa);
	
	$j = 1;
	$i=0;

	While ($newArray = mysql_fetch_array($result)) {

		$temp_id  		= $newArray['naprawa_hz_id'];
		$temp_data  	= $newArray['naprawa_hz_data'];
		$temp_osoba		= $newArray['naprawa_hz_osoba'];
		$temp_zmiany	= $newArray['naprawa_hz_zmiany'];

		tbl_tr_highlight($i);		
		
		echo "<td style='text-align:center;'>";
			echo $j;
		echo "</td>";

		echo "<td style='text-align:center;'>";
			echo $temp_data;
		echo "</td>";
				
		echo "<td style='text-align:center;'>";
			echo $temp_osoba;
		echo "</td>";
		
		echo "<td style='text-align:left;'>";
			//echo $temp_zmiany;
			echo nl2br(wordwrap($temp_zmiany, 80, "<br />"));
		echo "</td>";
			
		$j++;
		$i++;
		_tr();
	}	
	endtable();
} else {
	errorheader("Wybrana naprawa nie była modyfikowana");
}
	startbuttonsarea("right");
	addbuttons("zamknij");
	endbuttonsarea();

?>