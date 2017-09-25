<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 

	$sql_u = "UPDATE $dbname.serwis_sprzedaz SET sprzedaz_status='9' WHERE sprzedaz_id=$sprzedazid LIMIT 1";
	$result_u = mysql_query($sql_u, $conn) or die($k_b);

	$sql_u = "UPDATE $dbname.serwis_faktura_szcz SET pozycja_status=9 WHERE pozycja_id=$pozid LIMIT 1";
	$result_u = mysql_query($sql_u, $conn) or die($k_b);

	$sql_u = "UPDATE $dbname.serwis_faktury SET faktura_status=9 WHERE faktura_id=$fakid LIMIT 1";
	$result_u = mysql_query($sql_u,$conn) or die($k_b);

	$sql_d1="DELETE FROM $dbname.serwis_sprzedaz_raport WHERE (sr_id = '$_POST[id]') LIMIT 1";
	if (mysql_query($sql_d1, $conn)) { 
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else { 
		?><script>info('Wystąpił błąd podczas operacji usuwania'); self.close(); </script><?php
	}
} else {
$result = mysql_query("SELECT sr_id, sr_rok, sr_miesiac FROM $dbname.serwis_sprzedaz_raport WHERE (belongs_to=$es_filia) ORDER BY sr_id DESC LIMIT 1", $conn) or die($k_b);
list($temp_id,$temp_rok, $temp_miesiac)=mysql_fetch_array($result);
errorheader("Czy napewno chcesz usunąć zatwierdzenie raportu ze sprzedaży za okres <b>$temp_miesiac-$temp_rok</b> ?");
//infoheader("<b>".$temp_nazwa."</b> ze słownika ?");
startbuttonsarea("center");
echo "<form action=$PHP_SELF method=POST>";
echo "<input type=hidden name=id value=$temp_id>";
addbuttons("tak","nie");
endbuttonsarea();
_form();
}
?>
</body>
</html>