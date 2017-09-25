<?php include_once('header.php'); ?>
<body>
<?php
$sql55="SELECT * FROM $dbname.serwis_slownik_konfiguracja WHERE konfiguracja_id=$id";
$result55 = mysql_query($sql55, $conn) or die(mysql_error());
while ($newArray55 = mysql_fetch_array($result55)) {
	$sprz_id				= $newArray55['konfiguracja_id'];		
	$sprz_nazwa				= $newArray55['konfiguracja_nazwa'];		
	$temp_RAM				= $newArray55['pamiec'];
	$temp_HDD				= $newArray55['dysk'];
	$temp_PROCESOR			= $newArray55['procesor'];
	
	$konf_opis='Procesor '.$temp_PROCESOR.'GHz, '.$temp_RAM.'MB RAM, '.$temp_HDD.'GB HDD';
	//echo "$konf_opis<br>";
	$sql555="UPDATE $dbname.serwis_ewidencja SET ewidencja_konfiguracja='$sprz_nazwa', k_procesor='$temp_PROCESOR', k_pamiec='$temp_RAM', k_dysk='$temp_HDD' WHERE ((k_procesor='$PROC') and (k_pamiec='$RAM') and (k_dysk='$HDD'))";
	//echo "$sql555<br>";
	$result555 = mysql_query($sql555, $conn) or die(mysql_error());			
}
?>
<script>
alert('Baza sprzętu została zaktualizowana');
self.close();	
</script>
<?php
//	echo "<h3><br><center>Zmiany zostały wprowadzone</center><br></h3>";
//	echo "<br><p align=center><input class=buttons type=button onClick=window.close() value='&nbsp;&nbsp;&nbsp;Zamknij&nbsp;&nbsp;&nbsp;'></p>";
?>
</body>
</html>