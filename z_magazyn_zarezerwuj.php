<?php include_once('header.php'); ?>
<body>
<?php
if ($submit) {
	$sql_e1b="UPDATE $dbname.serwis_magazyn SET magazyn_status = '2', magazyn_osoba_rezerwujaca = '$_POST[osobarez]', magazyn_data_rezerwacji = '$_POST[datarez]' WHERE magazyn_id = '$_POST[uid]' LIMIT 1";
	if (mysql_query($sql_e1b, $conn)) { 
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else { 
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php	
	}
} else { ?>
<?php
$result444 = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni,magazyn_uwagi_sa,magazyn_status FROM $dbname.serwis_magazyn WHERE (magazyn_id=$select_id) LIMIT 1", $conn) or die($k_b);
list($mid,$mnazwa,$mmodel,$msn,$mni,$muwagi,$mstatus)=mysql_fetch_array($result444);
$dddd = Date('Y-m-d H:i:s');
errorheader("Czy napewno chcesz zarezerwować wybrany sprzęt ?");
infoheader("<b>".$mnazwa." - ".skroc_tekst($mmodel,50)." (SN: ".$msn.")");
startbuttonsarea("center");
echo "<form action=$PHP_SELF method=POST>";
echo "<input type=hidden name=uid value=$mid>";
echo "<input type=hidden name=osobarez value='$currentuser'>";
echo "<input type=hidden name=datarez value='$dddd'>";
addbuttons("tak","nie");
endbuttonsarea();
_form();
}
?>
</body>
</html>