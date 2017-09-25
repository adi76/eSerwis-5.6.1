<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 	
	$sql_d1 = "DELETE FROM $dbname.serwis_dostawcy WHERE (dostawca_id = '$_POST[did]') LIMIT 1";
	if (mysql_query($sql_d1, $conn)) { 
		?><script> opener.location.reload(true); self.close(); </script><?php
	} else { 
		?><script>info('Wyst¹pi³ b³¹d podczas operacji usuwania'); self.close(); </script><?php
	}
} else { ?>
<?php
$sql_e1 = "SELECT faktura_dostawca_id FROM $dbname.serwis_faktury WHERE (faktura_dostawca_id=$id)";
$result_e1 = mysql_query($sql_e1, $conn) or die(mysql_error());
$count_rows = mysql_num_rows($result_e1);
if ($count_rows!=0) { 
?>
<script>
info('Nie mo¿na usun¹æ tego dostawcy, gdy¿ istniej¹ z nim powi¹zania w innych tabelach');self.close();
</script>
<?php
echo "</body></html>";
exit;
}

$sql_e = "SELECT dostawca_id, dostawca_nazwa FROM $dbname.serwis_dostawcy WHERE (dostawca_id=$id) LIMIT 1";
$result = mysql_query($sql_e, $conn) or die(mysql_error());
$newArray = mysql_fetch_array($result);
$temp_id	= $newArray['dostawca_id'];
$temp_nazwa = $newArray['dostawca_nazwa'];
	
echo "<h2>Czy napewno chcesz usun¹æ ?<br /><br />$temp_nazwa</h2>";

startbuttonsarea("center");
echo "<form action=$PHP_SELF method=POST>";
echo "<input type=hidden name=did value=$temp_id>";
addbuttons("tak","nie");
endbuttonsarea();

echo "</form>";
}
?>
</body>
</html>