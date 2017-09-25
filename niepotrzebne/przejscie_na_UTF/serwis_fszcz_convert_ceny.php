<?php include_once('header.php'); ?>
<body>
<?php
include('inc_encrypt.php');
$i=0;
$sql55="SELECT pozycja_id, pozycja_cena_netto, pozycja_cena_netto_odsprzedazy FROM $dbname.serwis_faktura_szcz ";
$result55 = mysql_query($sql55, $conn) or die(mysql_error());
while ($newArray55 = mysql_fetch_array($result55)) {
	$poz_id					= $newArray55['pozycja_id'];
	$poz_cenanetto_cr		= $newArray55['pozycja_cena_netto'];		
	$poz_cenaodsprz_cr		= $newArray55['pozycja_cena_netto_odsprzedazy'];			
	
if ($kierunek=='cr_to_normal') {
	$poz_cenanetto 		= decrypt_md5_($poz_cenanetto_cr,$key);
	$poz_cenaodsprz	 	= decrypt_md5_($poz_cenaodsprz_cr,$key);
} else {
	$poz_cenanetto 		= crypt_md5($poz_cenanetto_cr,$key);
	$poz_cenaodsprz	 	= crypt_md5($poz_cenaodsprz_cr,$key);
}


if ($kierunek=='cr_to_normal') {
	$poz_cenanetto = correct_currency($poz_cenanetto);
	$poz_cenaodsprz = correct_currency($poz_cenaodsprz);
}

	$sql555="UPDATE $dbname.serwis_faktura_szcz SET pozycja_cena_netto='$poz_cenanetto', pozycja_cena_netto_odsprzedazy='$poz_cenaodsprz' WHERE (pozycja_id=$poz_id)";
	//echo "$sql555<br>";
	$result555 = mysql_query($sql555, $conn) or die(mysql_error());			
	$i++;
	echo "$i) zmieniono : |$poz_cenanetto_cr|->|$poz_cenanetto|  |$poz_cenaodsprz_cr|->|$poz_cenaodsprz|<br />";
}
?>
<script>
alert('Tabela faktury szczegółowe została zaktualizowana');
//self.close();	
</script>
<?php
//	echo "<h3><br><center>Zmiany zostały wprowadzone</center><br></h3>";
//	echo "<br><p align=center><input class=buttons type=button onClick=window.close() value='&nbsp;&nbsp;&nbsp;Zamknij&nbsp;&nbsp;&nbsp;'></p>";
?>
</body>
</html>