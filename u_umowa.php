<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 
	$sql_d1 = "DELETE FROM $dbname.serwis_umowy WHERE (umowa_id = '$_POST[umid]') LIMIT 1";
	if (mysql_query($sql_d1, $conn)) { 
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else { 
		?><script>info('Wystąpił błąd podczas operacji usuwania'); self.close(); </script><?php
	}
} else { ?>
<?php
$result_e1 = mysql_query("SELECT belongs_to FROM $dbname.serwis_umowy WHERE (umowa_id=$id)", $conn) or die($k_b);
list($umowa)=mysql_fetch_array($result_e1);
if ($umowa!=$es_filia) { 
	?>
		<script>
		info('Nie można usunąć umowy, która nie jest przypisana do Twojego oddziału');self.close();
		</script>
	<?php
echo "</body></html>";
exit;
}
$result_e1 = mysql_query("SELECT up_umowa_id FROM $dbname.serwis_komorki WHERE (up_umowa_id=$id)", $conn) or die($k_b);
$count_rows = mysql_num_rows($result_e1);
if ($count_rows!=0) { 
	?>
	<script>
	info('Nie można usunąć tej umowy ze słownika gdyż istnieją z nią powiązania w komórkach / UP');self.close();
	</script>
	<?php
echo "</body></html>";
exit;
}
$result = mysql_query("SELECT umowa_id, umowa_opis, umowa_nr FROM $dbname.serwis_umowy WHERE (umowa_id=$id) LIMIT 1", $conn) or die($k_b);
list($temp_id,$temp_opis,$temp_nr)=mysql_fetch_array($result);
errorheader("Czy napewno chcesz usunąć wybraną umowę ?");
infoheader("<b>".$temp_nr." ".$temp_opis."</b>");
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