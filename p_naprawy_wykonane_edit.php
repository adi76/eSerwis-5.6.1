<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) { 
	$_POST=sanitize($_POST);

	$sql_e1="UPDATE $dbname.serwis_naprawa SET naprawa_wykonane_naprawy = '".nl2br($_POST[duwagi])."' WHERE naprawa_id = '$uid' LIMIT 1";
	
	if (mysql_query($sql_e1, $conn)) { 
		?><script>if (opener) opener.location.reload(true); self.close();</script><?php	
	} else {
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php	
	}
} else {
$result = mysql_query("SELECT naprawa_id,naprawa_nazwa,naprawa_model,naprawa_sn,naprawa_wykonane_naprawy, naprawa_ni FROM $dbname.serwis_naprawa WHERE (naprawa_id=$id) LIMIT 1", $conn) or die($k_b);
list($temp_id,$temp_nazwa,$temp_model,$temp_sn,$temp_uwagi,$temp_ni)=mysql_fetch_array($result);
pageheader("Edycja wykonanych napraw");
startbuttonsarea("center");
if ($temp_ni=='') $temp_ni='-';
echo "Typ sprzętu: <b>$temp_nazwa <b>$temp_model</b></b><br />SN: <b>$temp_sn</b><br />NI: <b>$temp_ni</b>";
endbuttonsarea();
starttable();
echo "<form name=edu action=$PHP_SELF method=POST>";
tbl_empty_row();
	tr_();
		td("120;rt;Wykonane naprawy");
		td_(";;;");
			echo "<textarea name=duwagi cols=35 rows=6>".br2nl($temp_uwagi)."</textarea>";
		_td();
	_tr();
tbl_empty_row();
endtable();
startbuttonsarea("right");
addbuttons("zapisz","anuluj");
endbuttonsarea();
echo "<input size=30 type=hidden name=uid value='$temp_id'>";
_form();
}
?>
</body>
</html>