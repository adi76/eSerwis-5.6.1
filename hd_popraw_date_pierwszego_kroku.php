<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body>
<?php 

	$data_1_kroku = $_REQUEST[nowadata]." ".$_REQUEST[nowagodzina];
	
	$sql_a = "UPDATE $dbname_hd.hd_zgloszenie_szcz SET zgl_szcz_czas_rozpoczecia_kroku='$data_1_kroku' WHERE (zgl_szcz_id='$_REQUEST[id]')  LIMIT 1";

	if (mysql_query($sql_a, $conn_hd)) { 
			?>
			<script> 
				if (opener) opener.location.reload(true); 
				self.close();
			</script>
			<?php
	} else {
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
	}

?>
</body>
</html>