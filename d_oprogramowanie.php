<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php
if ($submit) { 
	?><script>ShowWaitingMessage('Trwa zapisywanie danych...', 'Saving1');</script>
	<?php
	$_POST=sanitize($_POST);
	$sql_a = "INSERT INTO $dbname.serwis_slownik_oprogramowania VALUES ('','$_POST[dnp]')";
	if (mysql_query($sql_a, $conn)) { 
		?>
		<script>HideWaitingMessage('Saving1');</script>
		<script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else {
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
	}
} else {
pageheader("Dodawanie nowego oprogramowania do słownika");
starttable();
echo "<form name=add action=$PHP_SELF method=POST>";
tbl_empty_row();
tr_();
	td(";r;Nazwa oprogramowania");
	td_(";;");
		echo "<input id=opr class=wymagane size=40 maxlenght=40 type=text name=dnp onKeyUp='slownik_opr()' onBlur='slownik_opr()'>";
		echo "<img name=status src=img//none.gif>";
		echo "<select name=lista style='display:none'>";
		$result=mysql_query("SELECT oprogramowanie_slownik_nazwa FROM $dbname.serwis_slownik_oprogramowania ORDER BY oprogramowanie_slownik_nazwa",$conn) or die($k_b);
		while (list($temp)=mysql_fetch_array($result)) { echo "<option value=$temp>$temp</option>\n"; }
		echo "</select>";
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
	frmvalidator.addValidation("dnp","req","Nie podano nazwy oprogramowania");
</script>
<?php } ?>
</body>
</html>