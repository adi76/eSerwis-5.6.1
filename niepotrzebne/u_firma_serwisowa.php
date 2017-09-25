<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 
	$sql_d1="DELETE FROM $dbname.serwis_firmy_serwisowe WHERE fs_id = '$_POST[fsid]' LIMIT 1";	
	if (mysql_query($sql_d1, $conn)) { 
		?><script>opener.location.reload(true); self.close(); </script><?php
	} else {
		?><script>info('Wyst¹pi³ b³¹d podczas operacji usuwania'); self.close(); </script><?php
	}
} else { ?>
<?php

// sprawdzenie czy nie ma powi¹zañ do serwisu w bazie ewidencji lub innych tabelach
$sql_e1 = "SELECT ewidencja_gwarancja_kto FROM $dbname.serwis_ewidencja WHERE (ewidencja_gwarancja_kto=$select_id)";
$result_e1 = mysql_query($sql_e1, $conn) or die(mysql_error());
$count_rows = mysql_num_rows($result_e1);
if ($count_rows!=0) { 
	?>
	<script>
	info('Nie mo¿na usun¹æ tej firmy serwisowej gdy¿ istniej¹ z ni¹ powi¹zania w innych tabelach');self.close();
	</script>
	<?php
echo "</body></html>";
exit;
}

$sql_e = "SELECT * FROM $dbname.serwis_firmy_serwisowe WHERE (fs_id=$select_id) LIMIT 1";
$result = mysql_query($sql_e, $conn) or die(mysql_error());
$newArray = mysql_fetch_array($result);
$temp_id  			= $newArray['fs_id'];
$temp_nazwa			= $newArray['fs_nazwa'];
$temp_adres			= $newArray['fs_adres'];
$temp_telefon		= $newArray['fs_telefon'];
$temp_fax			= $newArray['fs_fax'];
$temp_email			= $newArray['fs_email'];
$temp_www			= $newArray['fs_www'];
$temp_belongs_to	= $newArray['belongs_to'];
	
startbuttonsarea("center");
echo "<h2>Czy napewno chcesz usun¹æ t¹ firmê serwisow¹ ?<br /><br />$temp_nazwa</h2>";
echo "<form action=$PHP_SELF method=POST>";
echo "<input type=hidden name=fsid value=$temp_id>";
addbuttons("tak","nie");
endbuttonsarea();

echo "</form>";
}
?>
</body>
</html>