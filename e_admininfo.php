<?php include_once('header.php'); ?>
<body onLoad="document.forms[0].elements[0].focus();">
<?php 
if ($clear) {
if (mysql_query("UPDATE $dbname.serwis_admin SET admin_opis='', admin_value=0 WHERE (admin_id = 1) LIMIT 1", $conn)) 
	{ 
		$es_admin_info='';
		?><script>opener.location.reload(true); self.close(); </script><?php
	}
	exit;
}

if ($submit) { 
	$_POST=sanitize($_POST);
	$setactive = $_POST[active];
	if ($_POST[komunikat]=='') { $setactive = 0; }
	if (mysql_query("UPDATE $dbname.serwis_admin SET admin_opis='$_POST[komunikat]', admin_value=$setactive WHERE (admin_id = 1) LIMIT 1", $conn)) 
		{ 
			$es_admin_info=$_POST[komunikat];
			$es_admin_info_a=$setactive;
			?><script>opener.location.reload(true); self.close(); </script><?php
		} 
	else 
		{
			?><script>info('Wystąpił błąd podczas zapisu do bazy'); self.close(); </script><?php
		}
} 
else 
{
$result = mysql_query("SELECT admin_id,admin_opis,admin_value FROM $dbname.serwis_admin WHERE (admin_id=1) LIMIT 1", $conn) or die($k_b);
list($temp_id,$temp_nazwa,$temp_value) = mysql_fetch_array($result);
pageheader("Zmiana treści komunikatu od admina");
echo "<form name=info action=$PHP_SELF method=POST>";	
starttable();
tbl_empty_row();
	tr_();
		td("120;r;Treść komunikatu");
		td_(";;;");
			echo "<input size=50 maxlength=150 type=text name=komunikat value='$temp_nazwa' onkeypress='return handleEnter(this, event);'>";
		_td();
	_tr();
	tr_();
		td("120;r;Aktywny");
		td_(";;;");
			echo "<select name=active onkeypress='return handleEnter(this, event);'>";
			echo "<option value=0"; if ($temp_value==0) { echo " SELECTED"; } echo ">NIE</option>\n";
			echo "<option value=1"; if ($temp_value==1) { echo " SELECTED"; } echo ">TAK</option>\n";
			echo "</select>";			
		_td();
	_tr();
tbl_empty_row();
endtable();
	
startbuttonsarea("right");
addownsubmitbutton("'Wyczyść i wyłącz komunikat'","clear");
addbuttons("zapisz","anuluj");
endbuttonsarea();
_form();
}
?>
</body>
</html>