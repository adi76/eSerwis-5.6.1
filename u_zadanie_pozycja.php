<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 
	$sql_d1="DELETE FROM $dbname.serwis_zadania_pozycje WHERE (pozycja_id = '$_POST[id]') LIMIT 1";
	if (mysql_query($sql_d1, $conn)) { 
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else { 
		?><script>info('Wystąpił błąd podczas operacji usuwania'); self.close(); </script>
	<?php } 
} else {
errorheader("Czy napewno chcesz usunąć wykonanie zadania w");
infoheader("<b>".$k."</b>");
startbuttonsarea("center");
echo "<form action=$PHP_SELF method=POST>";
echo "<input type=hidden name=id value=$id>";
addbuttons("tak","nie");
endbuttonsarea();
_form();
} 
?>
</body>
</html>