<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 
	$sql_e1a="UPDATE $dbname.serwis_magazyn SET magazyn_status = '0' WHERE magazyn_id = '$_POST[uid]' LIMIT 1";
	
	if (mysql_query($sql_e1a, $conn)) { 
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else {
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php	
	}
} else { ?>
<?php
$result555 = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn FROM $dbname.serwis_magazyn WHERE (magazyn_id=$select_id) LIMIT 1", $conn) or die($k_b);
list($mid,$mnazwa,$mmodel,$msn) = mysql_fetch_array($result555);
errorheader("Czy napewno chcesz odkryć wybrany sprzęt ?");
infoheader("<b>".$mnazwa." - ".skroc_tekst($mmodel,50)." (SN: ".$msn.")");
startbuttonsarea("center");
echo "<form action=$PHP_SELF method=POST>";
echo "<input type=hidden name=uid value=$mid>";
addbuttons("tak","nie");
endbuttonsarea();
_form();
}
?>
</body>
</html>