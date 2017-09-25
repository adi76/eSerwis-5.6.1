<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) { 
	$_POST=sanitize($_POST);
	
	$dddd = Date("Y-m-d H:i:s");
	$dw = Date("Y-m-d");
	if ($_POST[data_wycofania]!='') $dw=$_POST[data_wycofania];
	
	$sql_e1="UPDATE $dbname.serwis_naprawa SET naprawa_powod_wycofania_z_serwisu='".nl2br($_POST[duwagi])."', naprawa_data_wycofania='$dw', naprawa_osoba_wycofujaca_sprzet_z_serwisu='$currentuser', naprawa_wycofanie_timestamp='$dddd' WHERE naprawa_id = '$uid' LIMIT 1";
	if (mysql_query($sql_e1, $conn)) { 
		?><script>if (opener) opener.location.reload(true); self.close();</script><?php	
	} else {
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php	
	}
} else {
$result = mysql_query("SELECT naprawa_id,naprawa_nazwa,naprawa_model,naprawa_sn,naprawa_powod_wycofania_z_serwisu, naprawa_ni, naprawa_data_wycofania FROM $dbname.serwis_naprawa WHERE (naprawa_id=$id) LIMIT 1", $conn) or die($k_b);
list($temp_id,$temp_nazwa,$temp_model,$temp_sn,$temp_uwagi,$temp_ni,$temp_data_wycofania)=mysql_fetch_array($result);
pageheader("Edycja powodu wycofania sprzętu z serwisu");
startbuttonsarea("center");
if ($temp_ni=='') $temp_ni='-';
echo "Typ sprzętu: <b>$temp_nazwa <b>$temp_model</b></b><br />SN: <b>$temp_sn</b><br />NI: <b>$temp_ni</b>";
endbuttonsarea();
starttable();
echo "<form name=edu action=$PHP_SELF method=POST>";
tbl_empty_row();
	tr_();
		td("120;rt;Data wycofania");
		td_(";;;");
		if ($temp_data_wycofania=='0000-00-00') $temp_data_wycofania=Date('Y-m-d');
			echo "<input type=text id=data_wycofania name=data_wycofania size=10 maxlength=10 value='".$temp_data_wycofania."' onkeypress=\"return handleEnter(this, event);\" onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\">";
			echo "<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę wycofania sprzętu z serwisu '><img class=imgoption src=img/cal.gif width=16 height=16 border=0></a>";
			if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('data_wycofania').value='".Date('Y-m-d')."'; return false;\">";
		_td();
	_tr();
	tr_();
		td("120;rt;Powód wycofania");
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
<script language="JavaScript">
var cal1 = new calendar1(document.forms['edu'].elements['data_wycofania']);
	cal1.year_scroll = true;
	cal1.time_comp = false;
</script>
</body>
</html>