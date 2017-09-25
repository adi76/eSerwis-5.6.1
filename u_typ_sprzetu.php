<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 
	$sql_d1="DELETE FROM $dbname.serwis_slownik_rola WHERE (rola_id = '$_POST[id]') LIMIT 1";
	if (mysql_query($sql_d1, $conn)) { 
		?><script>if (opener) opener.location.reload(true); self.close();  </script><?php
	} else {
		?><script>info('Wystąpił błąd podczas operacji usuwania'); self.close(); </script><?php
	}
} else {
$sql_e1 = "SELECT ewidencja_id FROM $dbname.serwis_ewidencja WHERE (ewidencja_typ=$id)";
$result_e1 = mysql_query($sql_e1, $conn) or die($k_b);
$count_rows = mysql_num_rows($result_e1);

list($typnazwa)=mysql_fetch_array(mysql_query("SELECT rola_nazwa FROM $dbname.serwis_slownik_rola WHERE rola_id=$id LIMIT 1",$conn));
$sql_e2 = "SELECT pozycja_id FROM $dbname.serwis_faktura_szcz WHERE (pozycja_typ='$typnazwa')";
$result_e2 = mysql_query($sql_e2, $conn) or die($k_b);
$count_rows2 = mysql_num_rows($result_e2);

if ($count_rows!=0) { 
	?>
		<script>
			info('Nie można usunąć tego typu sprzętu, gdyż istnieją z nim powiązania w ewidencji sprzętu');self.close();
		</script>
	<?php
echo "</body></html>";
exit;
}
if ($count_rows2!=0) { 
	?>
		<script>
			info('Nie można usunąć tego typu sprzętu, gdyż istnieją z nim powiązania w towarach do odsprzedaży');self.close();
		</script>
	<?php
echo "</body></html>";
exit;
}


$result = mysql_query("SELECT rola_id,rola_nazwa,rola_do_ewidencji FROM $dbname.serwis_slownik_rola WHERE (rola_id=$id) LIMIT 1", $conn) or die($k_b);
list($temp_id,$temp_nazwa,$temp_ewid)=mysql_fetch_array($result);
errorheader("Czy napewno chcesz usunąć wybrany typ sprzętu ?");
infoheader("<b>".$temp_nazwa."</b>");
startbuttonsarea("center");
echo "<form action=$PHP_SELF method=POST>";
echo "<input type=hidden name=eid value=$temp_id>";		
echo "<input type=hidden name=id value=$id>";
addbuttons("tak","nie");
endbuttonsarea();
_form();
}
?>
</body>
</html>