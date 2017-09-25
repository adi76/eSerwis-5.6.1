<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) { 
	?><script>ShowWaitingMessage('Trwa zapisywanie danych...', 'Saving1');</script>
	<?php
	$_POST=sanitize($_POST);
	$sql_a = "INSERT INTO $dbname.serwis_piony VALUES ('','$_POST[dnp]',1)";
	if (mysql_query($sql_a, $conn)) { 
		?>
		<script>HideWaitingMessage('Saving1');</script>
		<script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else {
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
	}
} else {
pageheader("Dodawanie nowego pionu do bazy");
starttable();
echo "<form name=add action=$PHP_SELF method=POST>";
tbl_empty_row();
tr_();
	td("100;r;Nazwa pionu");
	td_(";;");
		echo "<input id=pion class=wymagane size=27 maxlength=100 type=text name=dnp onKeyUp='slownik_pion()' onBlur='slownik_pion()'>";
		echo "<img name=status src=img//none.gif>";
		echo "<select name=lista style='display:none'>";
		$result=mysql_query("SELECT pion_nazwa FROM $dbname.serwis_piony ORDER BY pion_nazwa",$conn) or die($k_b);
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
  frmvalidator.addValidation("dnp","req","Nie podano nazwy pionu");
</script>
<?php } ?>
</body>
</html>