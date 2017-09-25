<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php'); 
?>
<body>
<?php
$result_aaaa = mysql_query("SELECT * FROM $dbname_hd.hd_projekty WHERE (projekt_opis='$_REQUEST[popis]') and (projekt_active=1) LIMIT 1", $conn) or die($k_b);

while ($newArray9999 = mysql_fetch_array($result_aaaa)) {
	$temp_opis  = $newArray9999[projekt_opis];
	$temp_kto  = $newArray9999[projekt_autor];
	$temp_kiedy  = $newArray9999[projekt_data_utworzenia];
	echo "<font color=blue>Projekt utworzony przez <b>$temp_kto</b>, <b>".substr($temp_kiedy,0,16)."</b></font>";
}

?>
</body>
</html>