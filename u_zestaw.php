<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 
	$sql_d1="DELETE FROM $dbname.serwis_zestawy WHERE zestaw_id = '$_POST[mid]' LIMIT 1";
	if (mysql_query($sql_d1, $conn)) { 
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else { 
		?><script>info('Wystąpił błąd podczas operacji usuwania'); self.close(); </script><?php
	}
} else {
$result = mysql_query("SELECT zestaw_id, zestaw_opis FROM $dbname.serwis_zestawy WHERE (zestaw_id=$id) LIMIT 1", $conn) or die($k_b);
list($temp_id,$temp_opis)=mysql_fetch_array($result);
errorheader("Czy napewno chcesz usunąć wybrany pusty zestaw z listy ?");
infoheader("<b>".skroc_tekst($temp_opis,40)."</b>");
startbuttonsarea("center");
echo "<form action=$PHP_SELF method=POST>";
echo "<input type=hidden name=mid value=$temp_id>";
addbuttons("tak","nie");
endbuttonsarea();
_form();
}
?>
</body>
</html>