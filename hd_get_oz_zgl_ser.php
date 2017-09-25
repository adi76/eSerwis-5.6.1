<?php
require_once "cfg_eserwis.php";
require_once "cfg_helpdesk.php"; 

$q=$_GET["q"];
if (!$q) return;
$sql = "SELECT hd_komorka_pracownicy_nazwa,hd_komorka_pracownicy_telefon FROM $dbname_hd.hd_komorka_pracownicy WHERE ((belongs_to=$_GET[filia]) and (hd_zgl_seryjne=1) and (hd_komorka_pracownicy_nazwa LIKE '%$q%')) ORDER BY hd_komorka_pracownicy_nazwa ASC";
$rsd = mysql_query($sql,$conn);
while($rs = mysql_fetch_array($rsd)) {
	$cid = $rs['hd_komorka_pracownicy_nazwa'];
	$cnazwa = $rs['hd_komorka_pracownicy_telefon'];
//	$cpion = $rs['pion_nazwa'];
	echo "$cid|$cnazwa\n";
}
return false;
?>