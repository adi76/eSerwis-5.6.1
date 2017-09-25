<?php
include_once('header.php');
include_once('cfg_helpdesk.php');

if ($_REQUEST[id]=='0') exit;

$sql="SELECT * FROM $dbname_hd.hd_zgloszenie_kroki_historia_zmian WHERE (zmiana_krok_id=$_GET[id]) and (zmiana_widoczne=1) and (belongs_to=$es_filia)";	
$result = mysql_query($sql, $conn_hd) or die($k_b);
$count_rows = mysql_num_rows($result);

if ($count_rows>0) {
	pageheader("Historia zmian w kroku <b>$_GET[nr_kroku]</b> zgłoszenia nr <b>$_GET[zgl_nr]</b>",1,0);

	starttable("100%");
	th(";c;LP|;c;Data zmiany|;c;Osoba zmieniająca|;c;Opis zmian",$es_prawa);
	
	$j = 1;
	$i=0;

	While ($newArray = mysql_fetch_array($result)) {

		$temp_id  		= $newArray['zmiana_id'];
		$temp_data  	= $newArray['zmiana_krok_data'];
		$temp_osoba		= $newArray['zmiana_krok_osoba'];
		$temp_zmiany	= $newArray['zmiana_zmiany'];

		tbl_tr_highlight($i);		
		
		echo "<td style='text-align:center;vertical-align:top;'>";
			echo $j;
		echo "</td>";
				
		echo "<td style='text-align:center;vertical-align:top;'>";
			echo substr($temp_data,0,16);
		echo "</td>";
				
		echo "<td style='text-align:center;vertical-align:top;'>";
			echo $temp_osoba;
		echo "</td>";
		
		echo "<td style='text-align:left;vertical-align:top;'>";
			//echo $temp_zmiany;
			echo nl2br(wordwrap($temp_zmiany, 80, "<br />"));
		echo "</td>";
			
		$j++;
		$i++;
		_tr();
	}	
	endtable();
} else {
	errorheader("Zgłoszenie nie było modyfikowane");
}
	startbuttonsarea("right");
	addbuttons("zamknij");
	endbuttonsarea();

?>