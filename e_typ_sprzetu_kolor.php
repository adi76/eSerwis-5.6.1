<?php include_once('header_begin.php'); ?>
<link rel="stylesheet" href="css/js_color_picker_v2.css" media="screen">
<script src="js/color_functions.js"></script>		
<script type="text/javascript" src="js/js_color_picker_v2.js"></script>
</head>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) { 
	$sql_a = "UPDATE $dbname.serwis_slownik_rola SET rola_kolor='$_POST[tdk]' WHERE rola_id=$_POST[rid] LIMIT 1";
	if (mysql_query($sql_a, $conn)) { 
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else {
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
	}
} else { 
	list($oldvalue,$rolaid)=mysql_fetch_array(mysql_query("SELECT rola_kolor,rola_id FROM $dbname.serwis_slownik_rola WHERE rola_id=$id LIMIT 1",$conn));
	pageheader("Zmiana koloro podświetlenia grupy towarowej");
	starttable();
	echo "<form name=add action=$PHP_SELF method=POST>";	
	tbl_empty_row();
	tr_();
		td("100;r;Kolor grupy");
		td_(";;");
			echo "<input type=text size=10 name=tdk value='$oldvalue' style='background:$oldvalue' onClick=showColorPicker(this,document.forms[0].tdk);>";
			echo "&nbsp;<a class=normalfont href=# onClick=showColorPicker(this,document.forms[0].tdk); title=' Wybierz kolor '><img src='img/color_pick.png' border=0 align=absmiddle>Zmień kolor</a>";
		_td();
	_tr();
	tr_();
		td_(";;");
		?><script>
		//showColorPicker(this,document.forms[0].tdk);
		</script>
		<?php
		_td();
	_tr();
tbl_empty_row();
endtable();
startbuttonsarea("right");
addbuttons("zapisz","anuluj");
endbuttonsarea();
echo "<input type=hidden name=rid value=$rolaid>";
_form();
}
?>
</body>
</html>