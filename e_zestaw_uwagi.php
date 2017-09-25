<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) { 
	$_POST=sanitize($_POST);
	if ($_POST[duwagi]!='') {
		$sql_e1="UPDATE $dbname.serwis_zestawy SET zestaw_uwagi = '".nl2br($_POST[duwagi])."' WHERE zestaw_id = '$uid' LIMIT 1";
	} else {
		$sql_e1="UPDATE $dbname.serwis_zestawy SET zestaw_uwagi = '' WHERE zestaw_id = '$uid' LIMIT 1";
	}
	
	if (mysql_query($sql_e1, $conn)) { 
		?><script>if (opener) opener.location.reload(true); self.close();</script><?php	
	} else {
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php	
	}
} else {
$result = mysql_query("SELECT zestaw_id, zestaw_opis,zestaw_uwagi FROM $dbname.serwis_zestawy WHERE (zestaw_id=$id) LIMIT 1", $conn) or die($k_b);
list($temp_id,$temp_opis,$temp_uwagi)=mysql_fetch_array($result);
pageheader("Edycja uwag o zestawie");
startbuttonsarea("center");
echo "$temp_opis";
endbuttonsarea();
starttable();
echo "<form name=edu action=$PHP_SELF method=POST>";
tbl_empty_row();
	tr_();
		td("120;rt;Uwagi");
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