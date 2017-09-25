<?php
//require_once "cfg_eserwis.php";
include_once('header.php');
require_once "cfg_helpdesk.php"; 

if (isset($_POST["wybierzid"])) {
	$komorka = $_POST[wybierzid];
	
	$sql5 = "SELECT up_id FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((belongs_to=$es_filia) and 
(CONCAT(serwis_piony.pion_nazwa,' ',serwis_komorki.up_nazwa)='$komorka') and (serwis_komorki.up_pion_id=serwis_piony.pion_id))  LIMIT 1";

	$result2 = mysql_query($sql5,$conn_hd) or die($k_b);
	$dane = mysql_fetch_array($result2);
	$ReturnValue = $dane['up_id'];
	
	echo ">>>>>$ReturnValue<<<<<";

} else {
	echo ">>>>><<<<<";
}
return false;
?>
