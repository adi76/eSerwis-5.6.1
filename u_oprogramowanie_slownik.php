<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 
	$sql = "DELETE FROM $dbname.serwis_slownik_oprogramowania WHERE (oprogramowanie_slownik_id = '$_POST[pid]') LIMIT 1";
	if (mysql_query($sql, $conn)) { 
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else {
		?><script>info('Wystąpił błąd podczas operacji usuwania'); self.close(); </script><?php
	}	
} else {
$result_e1 = mysql_query("SELECT oprogramowanie_slownik_id FROM $dbname.serwis_oprogramowanie WHERE (oprogramowanie_slownik_id=$id)", $conn) or die($k_b);
$count_rows = mysql_num_rows($result_e1);
if ($count_rows!=0) { 
	?>
	<script>
		info('Nie można usunąć tego oprogramowania, gdyż istnieją z nim powiązania w innych tabelach');self.close();
	</script>
	<?php
echo "</body></html>";
exit;
}
$result = mysql_query("SELECT oprogramowanie_slownik_id,oprogramowanie_slownik_nazwa FROM $dbname.serwis_slownik_oprogramowania WHERE (oprogramowanie_slownik_id=$id) LIMIT 1", $conn) or die($k_b);
list($temp_id,$temp_nazwa)=mysql_fetch_array($result);
errorheader("Czy napewno chcesz usunąć wybrane oprogramowanie ze słownika ?");
infoheader("<b>".$temp_nazwa."");
startbuttonsarea("center");
echo "<form action=$PHP_SELF method=POST>";
echo "<input type=hidden name=pid value=$temp_id>";
addbuttons("tak","nie");
endbuttonsarea();
_form();
}
?>
</body>
</html>