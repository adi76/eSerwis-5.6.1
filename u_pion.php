<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 
	$sql_d1 = "DELETE FROM $dbname.serwis_piony WHERE (pion_id = '$_POST[pid]') LIMIT 1";
	if (mysql_query($sql_d1, $conn)) {
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else { 
		?><script>info('Wystąpił błąd podczas operacji usuwania'); self.close(); </script><?php
	}
} else {
$result_e1 = mysql_query("SELECT up_pion_id FROM $dbname.serwis_komorki WHERE (up_pion_id=$id)", $conn) or die($k_b);
$count_rows = mysql_num_rows($result_e1);
if ($count_rows!=0) { 
	?>
	<script>
	info('Nie można usunąć tego pionu ze słownika gdyż istnieją z nim powiązania w komórkach / UP');self.close();
	</script>
	<?php
echo "</body></html>";
exit;
}
$result = mysql_query("SELECT pion_id,pion_nazwa FROM $dbname.serwis_piony WHERE (pion_id=$id) LIMIT 1", $conn) or die($k_b);
list($temp_id,$temp_nazwa)=mysql_fetch_array($result);
errorheader("Czy napewno chcesz usunąć wybrany pion ?");
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