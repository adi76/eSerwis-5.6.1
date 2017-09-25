<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) {
	?><script>ShowWaitingMessage('Trwa zapisywanie danych...', 'Saving1');</script>
	<?php
	$_POST=sanitize($_POST);
	$sql_a = "INSERT INTO $dbname.serwis_slownik_drukarka VALUES ('','$_POST[md]','$_POST[od]',$es_filia)";
	if (mysql_query($sql_a, $conn)) { 
		?>
		<script>HideWaitingMessage('Saving1');</script>
		<script>if (opener) opener.location.reload(true); self.close(); </script><?php } 
		else {
			?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
		}
} else { ?>
<?php
pageheader("Dodawanie nowego modelu drukarki");
starttable();
echo "<form name=add action=$PHP_SELF method=POST>";
tbl_empty_row();
tr_();
	td(";r;Model drukarki");
	td_(";;");
		echo "<input id=druk class=wymagane size=30 maxlength=100 type=text name=md onKeyUp='slownik_drukarek()' onBlur='slownik_drukarek()'>";
		echo "<img class=imgoption name=status src=img//none.gif width=16 width=16>";
		echo "<select name=lista style='display:none'>";
		$result=mysql_query("SELECT drukarka_nazwa FROM $dbname.serwis_slownik_drukarka ORDER BY drukarka_nazwa",$conn) or die($k_b);
		while (list($temp)=mysql_fetch_array($result)) { echo "<option value=$temp>$temp</option>\n"; }
		echo "</select>";	
	_td();
_tr();
tr_();
	td(";r;Opis drukarki");
	td_(";;");
		echo "<input size=50 maxlength=100 type=text name=od onkeypress='return handleEnter(this, event);'>";
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
  frmvalidator.addValidation("md","req","Nie podano modelu drukarki");
</script>
<?php } ?>
</body>
</html>