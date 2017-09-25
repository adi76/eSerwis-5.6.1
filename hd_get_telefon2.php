<?php
include_once('header.php');
require_once "cfg_eserwis.php";
require_once "cfg_helpdesk.php"; 

if(isset($_POST["wybierzid"])){
	$oz1 = $_POST[wybierzid];
	
	$sql = "SELECT hd_komorka_pracownicy_telefon FROM $dbname_hd.hd_komorka_pracownicy WHERE ((hd_komorka_pracownicy_nazwa='$oz1') and (hd_zgl_seryjne=1)) LIMIT 1";

	$rsd = mysql_query($sql,$conn_hd);
	$ile = mysql_num_rows($rsd);
	if ($ile!=0) {
		$dane = mysql_fetch_array($rsd);
		//echo "<input type=hidden name=hdoztelefon1 size=15 maxlength=15 value=\"$dane[hd_komorka_pracownicy_telefon]\" onKeyPress=\"return filterInput(1, event, false,' '); \" />";
		//echo "<input type=text name=hdoztelefon1 value=\"$dane[hd_komorka_pracownicy_telefon]\" />";
		//echo $dane[hd_komorka_pracownicy_telefon];

	} else {
	//echo "<input type=text name=hdoztelefon1 value='' />";
// "<input type=hidden name=hdoztelefon1 size=15 maxlength=15 onKeyPress=\"return filterInput(1, event, false,' '); \" />";
	}
}

?>