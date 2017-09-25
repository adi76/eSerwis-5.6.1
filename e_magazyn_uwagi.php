<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) { 
	$_POST=sanitize($_POST);
	if ($_POST['duwagi']!='') {	
	$sql_e1="UPDATE $dbname.serwis_magazyn SET magazyn_uwagi_sa = '1' , magazyn_uwagi = '".nl2br($_POST[duwagi])."' WHERE magazyn_id = '$uid' LIMIT 1";
	if (mysql_query($sql_e1, $conn)) { 
			?><script>opener.location.reload(true); self.close(); </script><?php
	} else 
		{
			  ?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
		}
	} else {
	$sql_e1="UPDATE $dbname.serwis_magazyn SET magazyn_uwagi_sa = '0' , magazyn_uwagi = '' WHERE magazyn_id = '$uid' LIMIT 1";
	if (mysql_query($sql_e1, $conn)) { 
			?><script>opener.location.reload(true); self.close(); </script><?php
	} else 
		{
		  ?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
		}
	}
} else { ?>

<?php
$result = mysql_query("SELECT magazyn_id, magazyn_nazwa, magazyn_model, magazyn_sn, magazyn_uwagi FROM $dbname.serwis_magazyn WHERE (magazyn_id=$id) LIMIT 1", $conn) or die($k_b);
list($temp_id, $temp_nazwa, $temp_model, $temp_sn, $temp_uwagi) = mysql_fetch_array($result);
pageheader("Edycja uwag o sprzęcie");
startbuttonsarea("center");
echo "$temp_nazwa $temp_model (SN: $temp_sn)";
endbuttonsarea();

starttable();
echo "<form name=edu action=$PHP_SELF method=POST>";
tbl_empty_row();
tr_();
	td("120;rt;Uwagi");
	td_(";;;");
		echo "<textarea name=duwagi cols=36 rows=6>".br2nl($temp_uwagi)."</textarea>";
	_td();
_tr();
tbl_empty_row();	
endtable();

echo "<input size=30 type=hidden name=uid value='$temp_id'>";
startbuttonsarea("right");
addbuttons("zapisz","anuluj");
endbuttonsarea();

_form();
}
?>
</body>
</html>