<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 
	$sql_d1="DELETE FROM $dbname.serwis_slownik_monitor WHERE (monitor_id = '$_POST[id]') LIMIT 1";
	if (mysql_query($sql_d1, $conn)) {
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else {
		?><script>info('Wystąpił błąd podczas operacji usuwania'); self.close(); </script><?php
	}
} else {
$result_e1 = mysql_query("SELECT ewidencja_monitor_opis FROM $dbname.serwis_ewidencja WHERE (ewidencja_monitor_opis='$model')", $conn) or die($k_b);
$count_rows = mysql_num_rows($result_e1);
if ($count_rows!=0) { 
	?>
	<script>
	info('Nie można usunąć tego monitora z bazy, gdyż istnieją z nim powiązania w innych tabelach');self.close();
	</script>
	<?php
echo "</body></html>";
exit;
}
$result = mysql_query("SELECT monitor_id,monitor_nazwa FROM $dbname.serwis_slownik_monitor WHERE (monitor_id=$id) LIMIT 1", $conn) or die($k_b);
list($temp_id,$temp_nazwa)=mysql_fetch_array($result);
errorheader("Czy napewno chcesz usunąć wybrany model monitora ze słownika ?");
infoheader("<b>".$temp_nazwa."</b>");
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