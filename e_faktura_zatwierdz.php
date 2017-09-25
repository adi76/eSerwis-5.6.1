<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 
$sql="UPDATE $dbname.serwis_faktury SET faktura_status = 1, faktura_ilosc_pozycji = '$_POST[pozycji]' WHERE (faktura_id = '$_POST[fid]')";
//echo $sql."<br />";
if (mysql_query($sql, $conn)) { 
	$sql1="UPDATE $dbname.serwis_faktura_szcz SET pozycja_status = 0 WHERE ((pozycja_nr_faktury = '$_POST[fid]') and ((pozycja_status=0) or (pozycja_status=-1)))";
	//echo $sql1."<br />";
	if (mysql_query($sql1, $conn)) { 
		?><script>if (opener) opener.location.reload(true); self.close();  </script><?php
	} 
} else {
	 ?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php	
	}
} else { ?>
<?php
$sql_e444 = "SELECT * FROM $dbname.serwis_faktury WHERE (faktura_id='$id') LIMIT 1";
$result444 = mysql_query($sql_e444, $conn) or die($k_b);
$newArray = mysql_fetch_array($result444);
$temp_id  			= $newArray['faktura_id'];
$temp_numer			= $newArray['faktura_numer'];
$temp_data			= $newArray['faktura_data'];
$temp_dostawca		= $newArray['faktura_dostawca'];
$temp_koszty		= $newArray['faktura_koszty_dodatkowe'];
$temp_osoba			= $newArray['faktura_osoba'];
$temp_datawpisu		= $newArray['faktura_datawpisu'];
$temp_status		= $newArray['faktura_status'];

okheader("Czy napewno chcesz zatwierdzić wybraną fakturę ?");
infoheader("<b>Nr: ".$temp_numer.", ".$temp_dostawca." z ".$temp_data."</b>");
startbuttonsarea("center");
echo "<form action=$PHP_SELF method=POST>";
echo "<input type=hidden name=fid value=$temp_id>";	
echo "<input type=hidden name=pozycji value=$poz>";
addbuttons("tak","nie");
endbuttonsarea();

_form();
}
?>
</body>
</html>