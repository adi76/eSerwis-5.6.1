<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 
	$sql_e1b="DELETE FROM $dbname.serwis_faktury WHERE faktura_id = '$_POST[uid]' LIMIT 1";
	if (mysql_query($sql_e1b, $conn)) { 
		$sql_e1b1="DELETE FROM $dbname.serwis_faktura_szcz WHERE pozycja_nr_faktury = '$_POST[uid]'";
		if (mysql_query($sql_e1b1, $conn)) { 
			?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
		}
	} else { 
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php	
	}
} else { 

$sql_e444 = "SELECT * FROM $dbname.serwis_faktury WHERE (faktura_id=$id) LIMIT 1";
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


errorheader("Czy napewno chcesz usunąć wybraną fakturę ?");
infoheader("<b>".$temp_numer.", ".$temp_dostawca." z ".$temp_data."</b>");

startbuttonsarea("center");
echo "<form action=$PHP_SELF method=POST>";
echo "<input type=hidden name=uid value=$temp_id>";
addbuttons("tak","nie");
endbuttonsarea();

_form();
}
?>
</body>
</html>