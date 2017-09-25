<?php include_once('header.php'); ?>
<body>
<?php
include('inc_encrypt.php');
$i=0;
$sql55="SELECT sprzedaz_id, sprzedaz_pozycja_cenanetto,sprzedaz_pozycja_cenaodsp,sprzedaz_pozycja_cenatwoja FROM $dbname.serwis_sprzedaz ";
$result55 = mysql_query($sql55, $conn) or die(mysql_error());
while ($newArray55 = mysql_fetch_array($result55)) {
	$sprz_id				= $newArray55['sprzedaz_id'];
	$sprz_cenanetto_cr		= $newArray55['sprzedaz_pozycja_cenanetto'];		
	$sprz_cenaodsprz_cr		= $newArray55['sprzedaz_pozycja_cenaodsp'];		
	$sprz_cenatwoja_cr		= $newArray55['sprzedaz_pozycja_cenatwoja'];		
	
if ($kierunek=='cr_to_normal') {
	$sprz_cenanetto 	= decrypt_md5_($sprz_cenanetto_cr,$key);
	$sprz_cenaodsprz 	= decrypt_md5_($sprz_cenaodsprz_cr,$key);
	$sprz_cenatwoja 	= decrypt_md5_($sprz_cenatwoja_cr,$key);
} else {
	$sprz_cenanetto 	= crypt_md5($sprz_cenanetto_cr,$key);
	$sprz_cenaodsprz 	= crypt_md5($sprz_cenaodsprz_cr,$key);
	$sprz_cenatwoja 	= crypt_md5($sprz_cenatwoja_cr,$key);
}

if ($kierunek=='cr_to_normal') {
	$sprz_cenanetto = correct_currency($sprz_cenanetto);
	$sprz_cenaodsprz = correct_currency($sprz_cenaodsprz);
	$sprz_cenatwoja = correct_currency($sprz_cenatwoja);	
}

	$sql555="UPDATE $dbname.serwis_sprzedaz SET sprzedaz_pozycja_cenanetto='$sprz_cenanetto', sprzedaz_pozycja_cenaodsp='$sprz_cenaodsprz',sprzedaz_pozycja_cenatwoja='$sprz_cenatwoja' WHERE (sprzedaz_id=$sprz_id)";
	//echo "$sql555<br>";
	
	$result555 = mysql_query($sql555, $conn) or die(mysql_error());			
	$i++;
	echo "$i) zmieniono : |$sprz_cenanetto_cr|->|$sprz_cenanetto|  |$sprz_cenaodsprz_cr|->|$sprz_cenaodsprz|   |$sprz_cenatwoja_cr|->|$sprz_cenatwoja|<br />";
}
?>
<script>
alert('Tabela sprzedaży została zaktualizowana');
//self.close();	
</script>
<?php
//	echo "<h3><br><center>Zmiany zostały wprowadzone</center><br></h3>";
//	echo "<br><p align=center><input class=buttons type=button onClick=window.close() value='&nbsp;&nbsp;&nbsp;Zamknij&nbsp;&nbsp;&nbsp;'></p>";
?>
</body>
</html>