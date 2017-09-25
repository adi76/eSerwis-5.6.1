<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body>
<?php 
if ($submit) {	
	$_POST=sanitize($_POST);
	$sql = "UPDATE $dbname.serwis_naprawa SET naprawa_status='-1' WHERE naprawa_id=$_POST[id] LIMIT 1";
	if (mysql_query($sql, $conn_hd)) { 		
		?>
		<script>
			if (opener) opener.location.reload(true);
			self.close();
		</script>
		<?php
	} else { 
		?><script>info('Wystąpił błąd podczas wykonywania operacji'); self.close(); </script><?php
	}
} else {
errorheader("Czy napewno chcesz zmienić status naprawy na \"<b>uszkodzony na stanie</b>\" ?");
startbuttonsarea("center");
nowalinia();
echo "<form action=$PHP_SELF method=POST>";
echo "<input type=hidden name=id value=$_GET[id]>";
echo "<input type=hidden name=nr value=$_GET[nr]>";
echo "<input type=hidden name=unique value=$_GET[unique]>";
echo "<input type=hidden name=numer_zgl value=$_GET[numer_zgl]>";
addbuttons("tak","nie");
endbuttonsarea();
_form();
}
?>
</body>
</html>