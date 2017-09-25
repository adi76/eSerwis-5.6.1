<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body>
<?php 
	$sql_d1="DELETE FROM $dbname_hd.hd_komorka_pracownicy WHERE (hd_komorka_pracownicy_telefon='') and (belongs_to=$es_filia)";
	if (mysql_query($sql_d1, $conn_hd)) { 
		?><script>
		alert('Liczba usuniętych osób z bazy osób zgłaszających: <?php echo $_REQUEST[countpuste]; ?>');
		self.close(); 
		if (opener) opener.location.reload(true);
		</script><?php
	} else { 
		?><script>info('Wystąpił błąd podczas operacji usuwania'); self.close(); </script><?php
	}
?>
</body>
</html>