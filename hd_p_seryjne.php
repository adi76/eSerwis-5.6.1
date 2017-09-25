<?php 
include_once('cfg_helpdesk.php');
include_once('header.php');

echo "<body OnLoad=\"document.forms[0].elements[0].focus();\" />";
//echo "SELECT * FROM $dbname_hd.helpdesk.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_poledodatkowe2='$_GET[unique]')";

pageheader("Informacje szczegółowe o trasie wyjazdowej",0);
$result = mysql_query("SELECT zgl_szcz_id FROM $dbname_hd.hd_zgloszenie_szcz WHERE (belongs_to=$es_filia) and (zgl_szcz_unikalny_numer='$_GET[unique]')", $conn_hd) or die($k_b);
$ile = mysql_num_rows($result);

list($trasa_wyjazdowa,$w_data,$w_km,$w_osoba)=mysql_fetch_array(mysql_query("SELECT wyjazd_trasa,wyjazd_data,wyjazd_km,wyjazd_osoba FROM $dbname_hd.hd_zgloszenie_wyjazd WHERE wyjazd_zgl_szcz_id='$_GET[unique]'", $conn_hd));

$result_k = mysql_query("SELECT filia_lokalizacja FROM $dbname.serwis_filie WHERE filia_id=$es_filia LIMIT 1", $conn) or die($k_b);
list($temp_lok)=mysql_fetch_array($result_k);

//nowalinia();
//echo "Ilość punktów trasy : <b>$ile</b><br />";
//echo "Lokalizacja : <b>".strtoupper_utf8($temp_lok)."</b><br />";
//echo "Trasa : <b>$trasa_wyjazdowa</b><br />";

$pkt_trasy = explode(' - ',$trasa_wyjazdowa);
$ile_pkt = sizeof($pkt_trasy);

//echo "Ile pkt. trasy (wg wyjazdu) : <b></b>$ile_pkt<br />";

//print_r($pkt_trasy);

echo "&nbsp;Data wyjazdu: <b>$w_data</b><br />";
echo "&nbsp;Osoba wykonująca: <b>$w_osoba</b><br /><br />";

starttable();
//th("250;c;Punkt trasy",$es_prawa);

for ($v=0; $v<$ile_pkt; $v++) {
	tbl_tr_highlight($v);
	//echo "|".$pkt_trasy[$v]."|<br />";
		td_(";;".$pkt_trasy[$v].""); _td();
	_tr();
}

/*
$i = 1;
	while ($newArray = mysql_fetch_array($result)) {
	
		$temp_komorka		= $newArray['zgl_komorka'];	
		//$temp_km			= $newArray['zgl_
		tbl_tr_highlight($i);
			
			td_(";;".$temp_komorka."");
			_td();
		
		_tr();
			$i++;
	}
*/

endtable();
echo "&nbsp;Łącznie przejechano : <b>$w_km km</b><br />";
startbuttonsarea("right");
//echo "<input class=buttons type=button onClick=\"self.location.reload(); \" value='Odśwież widok' />";
echo "<input class=buttons id=anuluj type=button onClick=\"self.close();\" value=Zamknij>";
endbuttonsarea();


?>
</body>
</html>