<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 
	$sql_d1 = "DELETE FROM $dbname.serwis_czarna_lista WHERE (bl_id = '$_POST[umid]') LIMIT 1";
	if (mysql_query($sql_d1, $conn)) {
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else { 
		?><script>info('Wystąpił błąd podczas operacji usuwania'); self.close(); </script><?php
	}
} else { ?>
<?php
$result = mysql_query("SELECT bl_id, bl_ip FROM $dbname.serwis_czarna_lista WHERE (bl_id=$id) LIMIT 1", $conn) or die($k_b);
list($temp_id,$temp_ip)=mysql_fetch_array($result);
errorheader("Czy napewno chcesz usunąć poniższy adres z czarnej listy ?");
infoheader("<b>".$temp_ip."</b>");
startbuttonsarea("center");
echo "<form action=$PHP_SELF method=POST>";
echo "<input type=hidden name=umid value=$temp_id>";
addbuttons("tak","nie");
endbuttonsarea();
_form();
}
?>
</body>
</html>