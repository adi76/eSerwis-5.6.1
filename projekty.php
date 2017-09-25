<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php'); 
$result_aaaa = mysql_query("SELECT * FROM $dbname_hd.hd_projekty WHERE (projekt_dla_filii LIKE '%|$_REQUEST[id999]|%') and (projekt_active=1) and (projekt_status=1)", $conn) or die($k_b);
$count_rows111 = mysql_num_rows($result_aaaa);
if ($count_rows111>0) {
	echo "<option value=''>Wybierz projekt z listy</option>";	
	while ($newArray9999 = mysql_fetch_array($result_aaaa)) {
		$temp_opis  	= $newArray9999[projekt_opis];
		$temp_kto  		= $newArray9999[projekt_autor];
		$temp_kiedy  	= $newArray9999[projekt_data_utworzenia];
		echo "<option value='$temp_opis'>$temp_opis</option>\n";
	}
}

?>