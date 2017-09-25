<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) { 
	?><script>ShowWaitingMessage('Trwa zapisywanie danych...', 'Saving1');</script><?php 
	$_POST=sanitize($_POST);
	$sql_a = "INSERT INTO $dbname.serwis_umowy VALUES ('','$_POST[dnu]','$_POST[dnz]','$_POST[dopis]','$_POST[dkoordynator]','$_POST[dkoordynatoremail]',$es_filia)";
	if (mysql_query($sql_a, $conn)) { 
			?>
			<script>HideWaitingMessage('Saving1');</script>
			<script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else 	{
		  ?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
		}
} else {
pageheader("Dodawanie nowej umowy");
starttable();
echo "<form name=add action=$PHP_SELF method=POST>";	
tbl_empty_row();
tr_();
	td("100;r;Numer umowy");
	td_(";;");
		echo "<input id=umowa class=wymagane size=30 maxlength=50 type=text name=dnu onKeyUp='slownik_umow()' onBlur='slownik_umow()'>";
		echo "<img name=status src=img//none.gif>";
		echo "<select name=lista style='display:none'>";
		$result=mysql_query("SELECT umowa_nr FROM $dbname.serwis_umowy",$conn) or die($k_b);
		while (list($temp)=mysql_fetch_array($result)) { echo "<option value=$temp>$temp</option>\n"; }
		echo "</select>";		
	_td();
_tr();
tr_();
	td("100;r;Numer zlecenia");
	td_(";;");
	echo "<input class=wymagane size=30 maxlength=50 type=text name=dnz onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();
tr_();
	td("100;r;Opis");
	td_(";;");
		echo "<input size=45 maxlength=100 type=text name=dopis onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();
tr_();
	td("100;r;Koordynator");
	td_(";;");
		echo "<input size=45 maxlength=100 type=text name=dkoordynator onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();
tr_();
	td("100;r;Email koordynatora");
	td_(";;");
		echo "<input size=45 maxlength=100 type=text name=dkoordynatoremail onkeypress='return handleEnter(this, event);'>";
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
  frmvalidator.addValidation("dnu","req","Nie podano numeru umowy");
  frmvalidator.addValidation("dnz","req","Nie podano numeru zlecenia");
  frmvalidator.addValidation("dnz","numeric","Błędnie podany numer zlecenia (dozwolone są tylko cyfry)");
</script>	
<?php } ?>
</body>
</html>