<?php include_once('header.php'); ?>
<body>
<?php 
if ($_REQUEST[action]=='wlacz') {
	$sql_d1 = "UPDATE $dbname.serwis_piony SET pion_active=1 WHERE (pion_id = '$_REQUEST[id]') LIMIT 1";
	if (mysql_query($sql_d1, $conn)) {
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else { 
		?><script>info('Wystąpił błąd podczas aktualizacji bazy'); self.close(); </script><?php
	}
	echo "</body></html>";
} else {
	$sql_d1 = "UPDATE $dbname.serwis_piony SET pion_active=0 WHERE (pion_id = '$_REQUEST[id]') LIMIT 1";
	if (mysql_query($sql_d1, $conn)) {
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else { 
		?><script>info('Wystąpił błąd podczas aktualizacji bazy'); self.close(); </script><?php
	}
	echo "</body></html>";
}

?>