<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) {
	$_POST=sanitize($_POST);
	$dddd = Date('Y-m-d H:i:s');
	$sql_a = "INSERT INTO $dbname.serwis_czarna_lista VALUES ('','$_POST[blip]',$_POST[blactive],'$_POST[blenddate]','$currentuser','$dddd')";
	if (mysql_query($sql_a, $conn)) { 
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else {
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
	}
} else {
pageheader("Dodawanie nowego IP do czarnej listy");
starttable();
echo "<form name=add action=$PHP_SELF method=POST>";
tbl_empty_row();
	tr_();
		td("170;r;Adres IP");
		td_(";;;");
			echo "<input class=wymagane size=15 maxlength=15 type=text name=blip onkeypress='return handleEnter(this, event);'>";
			echo "<a href=#><img class=imgoption src=img/help.gif align=absmiddle border=0 onclick=\"newWindow_r(400,200,'help.php?id=1')\" width=16 width=16></a>";
		_td();
	_tr();
	tr_();
		td("170;r;Blokada aktywna");
		td_(";;;");
			echo "<select name=blactive onkeypress='return handleEnter(this, event);'>";		
			echo "<option value=1 SELECTED>Tak</option>\n";
			echo "<option value=0>Nie</option>\n";	
			echo "</select>";	
		_td();
	_tr();
	tr_();
		td("170;r;Data wygaśnięcia blokady");
		td_(";;;");
			echo "<input size=10 maxlength=10 type=text id=blenddate name=blenddate onkeypress='return handleEnter(this, event);' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\">";
			echo "<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif align=abstop width=16 height=16 border=0></a>";
			if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('blenddate').value='".Date('Y-m-d')."'; return false;\">";
		_td();
	_tr();
tbl_empty_row();
endtable();
startbuttonsarea("right");
addbuttons("zapisz","anuluj");
endbuttonsarea();
_form();
?>
<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("add");
  frmvalidator.addValidation("blip","req","Nie podano adresu IP");
</script>	

<script language="JavaScript">
var cal1 = new calendar1(document.forms['add'].elements['blenddate']);
	cal1.year_scroll = true;
	cal1.time_comp = false;
</script>
<?php } ?>
</body>
</html>