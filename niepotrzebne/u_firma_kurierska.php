<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 
	$sql_d1="DELETE FROM $dbname.serwis_firmy_kurierskie WHERE fk_id = '$_POST[fkid]' LIMIT 1";
	if (mysql_query($sql_d1, $conn)) { 
		?><script>opener.location.reload(true); self.close(); </script><?php
	} else {
		?><script>info('Wyst¹pi³ b³¹d podczas operacji usuwania'); self.close(); </script><?php
	}
} else { ?>
<?php
$sql_e = "SELECT * FROM $dbname.serwis_firmy_kurierskie WHERE (fk_id=$select_id) LIMIT 1";
$result = mysql_query($sql_e, $conn) or die(mysql_error());
$newArray = mysql_fetch_array($result);
$temp_id  			= $newArray['fk_id'];
$temp_nazwa			= $newArray['fk_nazwa'];
$temp_telefon		= $newArray['fk_telefon'];
$temp_email			= $newArray['fk_email'];
$temp_www			= $newArray['fk_www'];
$temp_belongs_to	= $newArray['belongs_to'];

startbuttonsarea("center");
echo "<h2>Czy napewno chcesz usun¹æ t¹ firmê kuriersk¹ ?<br /><br />$temp_nazwa</h2>";
echo "<form action=$PHP_SELF method=POST>";
echo "<input type=hidden name=fkid value=$temp_id>";
addbuttons("tak","nie");
endbuttonsarea();

echo "</form>";
}
?>
</body>
</html>