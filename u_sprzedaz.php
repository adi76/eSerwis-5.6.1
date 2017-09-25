<?php 
include_once('header.php');
?>
<body>
<?php 
if ($submit) {	
	$sql = "UPDATE $dbname.serwis_sprzedaz SET belongs_to=99 WHERE (sprzedaz_id=$_REQUEST[id]) LIMIT 1";
	if (mysql_query($sql, $conn)) { ?>
		<script>
			if (opener) opener.location.reload(true); 
			self.close();
		</script>
		<?php
	} else { 
		?><script>info('Wystąpił błąd podczas operacji ukrywania sprzedaży'); self.close(); </script><?php
	}
} else {
errorheader("Czy napewno chcesz ukryć wybraną sprzedaż ?");
startbuttonsarea("center");
nowalinia();
echo "<form action=$PHP_SELF method=POST>";
echo "<input type=hidden name=id value=$_GET[id]>";
addbuttons("tak","nie");
endbuttonsarea();
_form();
}
?>
</body>
</html>