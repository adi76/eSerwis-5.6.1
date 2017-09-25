<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 
	if (mysql_query("UPDATE $dbname.serwis_awarie SET awaria_status=2, awaria_datazamkniecia='".date("Y-m-d H:i:s")."', awaria_osobazamykajaca='$currentuser' WHERE awaria_id=$_POST[id] LIMIT 1", $conn)) { 
		$es_block=$_POST[a];
		?><script>opener.location.reload(true); self.close(); </script><?php
	} else { 
		?><script>info('Wystąpił błąd podczas operacji zapisu do bazy'); self.close(); </script><?php
	}
} else { 
$sql="SELECT awaria_id,awaria_gdzie,awaria_nrzgloszenia FROM $dbname.serwis_awarie WHERE awaria_id=$id LIMIT 1";
$wynik=mysql_query($sql,$conn) or die($k_b);
list($temp_id,$temp_gdzie,$temp_nr)=mysql_fetch_array($wynik);
errorheader("Czy napewno chcesz anulować zgłoszenie awarii w<br />");
infoheader("<b>".$temp_gdzie."</b> | Nr zgłoszenia: <b>".$temp_nr."</b>");
startbuttonsarea("center");
echo "<form action=$PHP_SELF method=POST>";
echo "<input type=hidden name=id value=$id>";
addbuttons("tak","nie");
endbuttonsarea();
_form();
} 
?>
</body>
</html>