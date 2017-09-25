<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 
	$sql_d1="DELETE FROM $dbname.serwis_slownik_monitor WHERE (monitor_id = '$_POST[id]') LIMIT 1";
	if (mysql_query($sql_d1, $conn)) {
		?><script>opener.location.reload(true); self.close(); </script><?php
	} else {
		?><script>info('Wyst¹pi³ b³¹d podczas operacji usuwania'); self.close(); </script><?php
	}
} else { ?>
<?php
$sql_e1 = "SELECT ewidencja_monitor_opis FROM $dbname.serwis_ewidencja WHERE (ewidencja_monitor_opis='$model')";
$result_e1 = mysql_query($sql_e1, $conn) or die(mysql_error());
$count_rows = mysql_num_rows($result_e1);

if ($count_rows!=0) { 
	?>
		<script>
		info('Nie mo¿na usun¹æ tego monitora z bazy, gdy¿ istniej¹ z nim powi¹zania w innych tabelach');self.close();
		</script>
		<?php
echo "</body></html>";
exit;
}

$sql_e = "SELECT monitor_id,monitor_nazwa FROM $dbname.serwis_slownik_monitor WHERE (monitor_id=$id) LIMIT 1";
$result = mysql_query($sql_e, $conn) or die(mysql_error());
$newArray = mysql_fetch_array($result);
$temp_id  		= $newArray['monitor_id'];
$temp_nazwa		= $newArray['monitor_nazwa'];
	
echo "<h2>Czy napewno chcesz usun¹æ model monitora<br />$temp_nazwa<br />ze s³ownika ?<br /></h2>";

startbuttonsarea("center");
echo "<form action=$PHP_SELF method=POST>";
echo "<input type=hidden name=id value=$temp_id>";
addbuttons("tak","nie");
endbuttonsarea();

echo "</form>";
}
?>
</body>
</html>