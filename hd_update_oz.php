<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body>
<?php 
$_GET=sanitize($_GET);
$sql_a = "UPDATE $dbname_hd.hd_komorka_pracownicy SET hd_komorka_pracownicy_telefon='$_GET[nnt]', hd_aktualizowane_przez = '$currentuser' WHERE hd_komorka_pracownicy_id=$_GET[ozid] LIMIT 1";
if (mysql_query($sql_a, $conn_hd)) {
	?><script>document.getElementById('warning1').style.display='none'; document.getElementById('NrZaktualizowany').style.display=''; </script><?php
		echo "$_GET[nnt]";
		} else {
	?><?php
	}		

?>