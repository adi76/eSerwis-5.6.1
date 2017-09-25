<?php
require_once "cfg_eserwis.php";
$q=$_GET["q"];
if (!$q) return;
$sql = "SELECT serwis_komorki.up_id, serwis_komorki.up_nazwa, serwis_piony.pion_nazwa, serwis_komorki.up_umowa_id, serwis_komorki.up_adres, serwis_komorki.up_telefon, serwis_komorki.up_nrwanportu, serwis_komorki.up_ip, serwis_komorki.up_working_time, serwis_komorki.up_working_time_alternative, serwis_komorki.up_working_time_alternative_start_date, serwis_komorki.up_working_time_alternative_stop_date, serwis_komorki.up_typ_uslugi, serwis_komorki.up_kategoria, serwis_komorki.up_przypisanie_jednostki FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((belongs_to=$_GET[filia]) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) and ((serwis_komorki.up_nazwa LIKE '%$q%') or (serwis_piony.pion_nazwa LIKE '%$q%'))) ORDER BY serwis_piony.pion_nazwa ASC,serwis_komorki.up_nazwa ASC";
$rsd = mysql_query($sql,$conn);
while($rs = mysql_fetch_array($rsd)) {
	$cid = $rs['up_id'];
	$cnazwa = $rs['up_nazwa'];
	$cpion = $rs['pion_nazwa'];
	$cumid = $rs['up_umowa_id'];
	
	$clok = $rs['up_adres'];
	$ctel = $rs['up_telefon'];
	$cnrwanportu = $rs['up_nrwanportu'];
	$cip = $rs['up_ip'];
	
	$cwt = $rs['up_working_time'];
	$cwta = $rs['up_working_time_alternative'];
	$cwta1 = $rs['up_working_time_alternative_start_date'];
	$cwta2 = $rs['up_working_time_alternative_stop_date'];
	
	$ctu = $rs['up_typ_uslugi'];
	$cku = $rs['up_kategoria'];
	$cpj = $rs['up_przypisanie_jednostki'];
	
	echo "$cpion $cnazwa|$cid|$cumid|$cnrwanportu|$clok|$ctel|$cip|$cnazwa|$cwt|$cwta|$cwta1|$cwta2|$ctu|$cku|$cpj\n";
}
return false;
?>