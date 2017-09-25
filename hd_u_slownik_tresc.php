<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body>
<?php 
	$sql_d1="DELETE FROM $dbname_hd.hd_slownik_tresci WHERE (tresc_id = '$_GET[select_id]') LIMIT 1";
	//echo $sql_d1;
	if (mysql_query($sql_d1, $conn_hd)) { 
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else { 
		?><script>info('Wystąpił błąd podczas operacji usuwania'); self.close(); </script><?php
	}
?>
</body>
</html>