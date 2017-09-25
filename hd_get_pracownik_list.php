<?php
require_once "cfg_eserwis.php";
require_once "cfg_helpdesk.php";

$q=$_GET["q"];
if (!$q) return;

if ($_GET[komorka]!='') {
	$sql = "SELECT hd_komorka_pracownicy_nazwa,hd_komorka_pracownicy_telefon FROM $dbname_hd.hd_komorka_pracownicy WHERE ((hd_serwis_komorka_id=$_GET[komorka]) and (hd_komorka_pracownicy_nazwa LIKE '%$q%')) ORDER BY hd_komorka_pracownicy_nazwa ASC";
	$rsd = mysql_query($sql,$conn);

		while($rs = mysql_fetch_array($rsd)) {
			$cid = $rs['hd_komorka_pracownicy_nazwa'];
			$cnazwa = $rs['hd_komorka_pracownicy_telefon'];
			echo "$cid|$cnazwa\n";
		}
}

return false;
?>