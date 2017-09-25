<?php include_once('header.php'); ?>
<body>
<?php 
include('body_start.php');
include('cfg_helpdesk.php');

pageheader("Szczegółowe informacje o przekazaniu sprzętu serwisowego",1,0);

$result99 = mysql_query("SELECT zgl_komorka FROM $dbname_hd.hd_zgloszenie WHERE zgl_id='$_REQUEST[id]' LIMIT 1", $conn) or die($k_b);
list($komorka) = mysql_fetch_array($result99);
	
$result99 = mysql_query("SELECT historia_magid FROM $dbname.serwis_historia WHERE historia_hd_zgl_nr='$id' LIMIT 1", $conn) or die($k_b);
list($hid) = mysql_fetch_array($result99);
if ($hid>0) {

} else {
	if ($_REQUEST[parentid]>0) {
		$result99 = mysql_query("SELECT historia_magid FROM $dbname.serwis_historia WHERE historia_hd_zgl_nr='$_REQUEST[parentid]' LIMIT 1", $conn) or die($k_b);
		list($hid) = mysql_fetch_array($result99);
	}
}

$result99 = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni,magazyn_uwagi,magazyn_uwagi_sa,magazyn_status,belongs_to FROM $dbname.serwis_magazyn WHERE magazyn_id='$hid' LIMIT 1", $conn) or die($k_b);

list($mid, $mnazwa, $mmodel, $msn, $mni, $muwagi, $muwagisa,$mstatus,$bt) = mysql_fetch_array($result99);

infoheader("<center>Informacje o sprzęcie serwisowym: <br /><br />".$mnazwa." ".$mmodel." | SN: ".$msn." | NI: $mni</center>");

$result99 = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni,magazyn_uwagi,magazyn_uwagi_sa,magazyn_status,belongs_to FROM $dbname.serwis_magazyn WHERE magazyn_id='$hid' LIMIT 1", $conn) or die($k_b);
list($mid, $mnazwa, $mmodel, $msn, $mni, $muwagi, $muwagisa,$mstatus,$bt) = mysql_fetch_array($result99);

starttable();

$result1 = mysql_query("SELECT * FROM $dbname.serwis_historia WHERE historia_hd_zgl_nr=$_REQUEST[id]", $conn) or die($k_b);	
//echo "SELECT * FROM $dbname.serwis_historia WHERE historia_hd_zgl_nr=$_REQUEST[hd_zgl_nr]";
$count_rows = mysql_num_rows($result1);

if ($count_rows==0) {
	$result1 = mysql_query("SELECT * FROM $dbname.serwis_historia WHERE historia_hd_zgl_nr=$_REQUEST[parentid]", $conn) or die($k_b);	
}

if (mysql_num_rows($result1)!=0) {

	while ($dane = mysql_fetch_array($result1)) {
		$hid 		= $dane['historia_magid'];
		$huser 		= $dane['historia_user'];
		$hdata 		= $dane['historia_data'];
		$hruch 		= $dane['historia_ruchsprzetu'];
		$hkomentarz	= $dane['historia_komentarz'];
		
	$r = 1;
	
		tbl_empty_row(2);
		tr_();
			td("220;r;Ruch sprzętu|;l;<b>".$hruch.": ".$komorka."</b>");
		_tr();
	tbl_empty_row(2);
		tr_();
			td("220;r;Osoba wykonująca|;l;<b>".$huser."</b>");
		_tr();
		tr_();
			td("220;r;Data wykonania|;l;<b>".substr($hdata,0,16)."</b>");
		_tr();
		if ($hkomentarz!='') {
			tr_();
				td("220;rt;Uwagi|;w;<b>".urldecode($hkomentarz)."</b>");
			_tr();
		}
		
	tbl_empty_row(2);
	$r++;
	
	}
	
	endtable();
} else {
	

	errorheader("Brak informacji o wybranym sprzęcie serwisowym");
	
}
startbuttonsarea("right");
addbuttons("zamknij");
endbuttonsarea();
include('body_stop.php'); 
?>
</body>
</html>