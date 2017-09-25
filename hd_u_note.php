<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body>
<?php 
	$sql_d1="UPDATE $dbname_hd.hd_notes SET note_status=0 WHERE (note_id = '$_GET[noteid]') LIMIT 1";
	if (mysql_query($sql_d1, $conn_hd)) { 
		?><script>
		self.close(); 
		if (opener) opener.document.getElementById('notes_refresh').click();
		</script><?php
	} else { 
		?><script>info('Wystąpił błąd podczas operacji usuwania'); self.close(); </script><?php
	}
?>
</body>
</html>