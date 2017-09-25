<?php 
include_once('cfg_helpdesk.php');
include_once('header.php');

	$wybierz_zgl = "SELECT pole2 FROM $dbname_hd.hd_temp_raport_rozbudowany";
	$result_zgl = mysql_query($wybierz_zgl, $conn_hd) or die($k_b);

	while ($newArray = mysql_fetch_array($result_zgl)) {
	
		$zgl_nr 		= $newArray['pole2'];

		$result_upid = mysql_query("SELECT belongs_to FROM $dbname_hd.hd_zgloszenie WHERE (zgl_nr=$zgl_nr) LIMIT 1", $conn) or die($k_b);
		list($bt)=mysql_fetch_array($result_upid);
		
		$result_upid = mysql_query("SELECT filia_nazwa FROM $dbname.serwis_filie WHERE (filia_id=$bt) LIMIT 1", $conn) or die($k_b);
		list($fnazwa)=mysql_fetch_array($result_upid);		
		
		// uaktualnij tabelê tymczasow¹
		$sql_update = "UPDATE $dbname_hd.hd_temp_raport_rozbudowany SET pole47='".$fnazwa."' WHERE (pole2='$zgl_nr') LIMIT 1";
		$update_zgl = mysql_query($sql_update, $conn_hd) or die($k_b);

	}
	
	echo "Uzupe³niono raport o nazwy filii";
	
?>