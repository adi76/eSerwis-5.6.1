<?php include_once('header.php');

if ($submit) {
$nowydostep=$value;
$sql_a = "UPDATE $dbname.serwis_slownik_rola SET rola_do_ewidencji=$nowydostep WHERE rola_id=$id LIMIT 1";
	if (mysql_query($sql_a, $conn)) { 
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else {
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
	}
exit;
}

?>
</body>
</html>