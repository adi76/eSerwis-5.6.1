<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body>
<?php 

	$r3 = mysql_query("SELECT zgl_szcz_czas_rozpoczecia_kroku FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$_REQUEST[nr]') and (zgl_szcz_nr_kroku=1) LIMIT 1", $conn_hd) or die($k_b);
	list($data_1_kroku)=mysql_fetch_array($r3);

	$data_1_kroku_data = substr($data_1_kroku,0,10);
	$data_1_kroku_godzina = substr($data_1_kroku,11,8);
		
	$sql_a = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_data='$data_1_kroku_data', zgl_godzina='$data_1_kroku_godzina' WHERE (zgl_nr='$_REQUEST[nr]')  LIMIT 1";
		
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