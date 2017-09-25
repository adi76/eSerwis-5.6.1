<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 
	$sql_d1="DELETE FROM $dbname.serwis_magazyn WHERE magazyn_id = '$_POST[mid]' LIMIT 1";
	if (mysql_query($sql_d1, $conn)) { 
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else { 
		?><script>info('Wystąpił błąd podczas operacji usuwania'); self.close(); </script><?php
	}
} else {
$result = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn FROM $dbname.serwis_magazyn WHERE (magazyn_id=$id) LIMIT 1", $conn) or die($k_b);
list($temp_id,$temp_nazwa,$temp_model,$temp_sn)=mysql_fetch_array($result);
errorheader("Czy napewno chcesz usunąć wybrany sprzęt z magazynu ?");
infoheader("<b>".$temp_nazwa." ".skroc_tekst($temp_model,30)." (SN: ".$temp_sn.")");
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