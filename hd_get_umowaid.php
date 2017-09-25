<?php
//require_once "cfg_eserwis.php";
include_once('header.php');
require_once "cfg_helpdesk.php"; 

if(isset($_POST["wybierzid"])) {
	$oz1 = $_POST[wybierzid];
	
	$sql = "SELECT up_umowa_id FROM $dbname.serwis_komorki WHERE ((up_id='$oz1') and (belongs_to=$es_filia)) LIMIT 1";
	
	$rsd = mysql_query($sql,$conn_hd);
	$ile = mysql_num_rows($rsd);
	if ($ile!=0) {
		$dane = mysql_fetch_array($rsd);
		echo "<input type=text id=idum name=idum value=\"$dane[up_umowa_id]\" \" />";
	} else {
		echo "<input type=text id=idum name=idum \" />";
	}
}

?>