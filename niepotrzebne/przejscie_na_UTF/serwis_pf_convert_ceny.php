<?php include_once('header.php'); ?>
<body>
<?php
include('inc_encrypt.php');
$i=0;
$sql55="SELECT pf_id, pf_kwota_netto FROM $dbname.serwis_podfaktury ";
$result55 = mysql_query($sql55, $conn) or die(mysql_error());
while ($newArray55 = mysql_fetch_array($result55)) {
	$poz_id					= $newArray55['pf_id'];
	$poz_cenanetto_cr		= $newArray55['pf_kwota_netto'];
	
if ($kierunek=='cr_to_normal') {
	$poz_cenanetto 		= decrypt_md5_($poz_cenanetto_cr,$key);
} else {
	$poz_cenanetto 		= crypt_md5($poz_cenanetto_cr,$key);
}

if ($kierunek=='cr_to_normal') {
	$poz_cenanetto = correct_currency($poz_cenanetto);
}

	$sql555="UPDATE $dbname.serwis_podfaktury SET pf_kwota_netto='$poz_cenanetto' WHERE (pf_id=$poz_id)";
	//echo "$sql555<br>";
	$result555 = mysql_query($sql555, $conn) or die(mysql_error());			
	$i++;
	echo "$i) zmieniono : |$poz_cenanetto_cr|->|$poz_cenanetto|<br />";
}
?>
<script>
alert('Tabela podfaktury została zaktualizowana');
//self.close();	
</script>
<?php
//	echo "<h3><br><center>Zmiany zostały wprowadzone</center><br></h3>";
//	echo "<br><p align=center><input class=buttons type=button onClick=window.close() value='&nbsp;&nbsp;&nbsp;Zamknij&nbsp;&nbsp;&nbsp;'></p>";
?>
</body>
</html>