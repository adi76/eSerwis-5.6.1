<?php include_once('header.php'); ?>
<body onLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) { 
	?><script>ShowWaitingMessage('Trwa zapisywanie danych...', 'Saving1');</script><?php 
	$_POST=sanitize($_POST);
	$sql_a = "INSERT INTO $dbname.serwis_filie VALUES ('','$_POST[dnf]','$_POST[dna]','$_POST[dfk]',$_POST[dfkier],'$_POST[dfs]','$_POST[dfl]')";
	if (mysql_query($sql_a, $conn)) { 
			?>
			<script>HideWaitingMessage('Saving1');</script>
			<script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else {
		  ?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
		}
} else { 
pageheader("Dodawanie nowej filii do bazy");
starttable();
echo "<form name=add action=$PHP_SELF method=POST>";
tbl_empty_row();
tr_();
	td("100;r;Nazwa filii");
	td_(";;");
		echo "<input id=umowa class=wymagane size=40 maxlength=30 type=text name=dnf onKeyUp='slownik_filie()' onBlur='slownik_filie()'>";
		echo "<img name=status src=img//none.gif>";
		echo "<select name=lista style='display:none'>";
		$result=mysql_query("SELECT filia_nazwa FROM $dbname.serwis_filie",$conn) or die($k_b);
		while (list($tem)=mysql_fetch_array($result)) {
			echo "<option value=$temp>$temp</option>\n";
		}
		echo "</select>";	
	_td();
_tr();
tr_();
	td("100;r;Adres");
	td_(";;");
		echo "<input size=40 maxlength=200 type=text name=dna onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();
tr_();
	td("100;r;Telefon / Fax");
	td_(";;");
		echo "<input size=25 maxlength=50 type=text name=dfk onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();
tr_();
	td("100;r;Kierownik");
	td_(";;");
		echo "<select class=wymagane name=dfkier onkeypress='return handleEnter(this, event);'>";
		echo "<option value=''>Wybierz z listy</option>\n";
		$result=mysql_query("SELECT user_id, user_first_name, user_last_name FROM $dbname.serwis_uzytkownicy ORDER BY user_login ASC",$conn) or die($k_b);
		while (list($temp_id,$temp_i,$temp_n)=mysql_fetch_array($result)) {
			echo "<option value=$temp_id>$temp_i $temp_n</option>\n"; }
		echo "</select>";
	_td();
_tr();
tr_();
	td("100;r;Skrót nazwy");
	td_(";;");
		echo "<input size=10 maxlength=10 type=text name=dfs onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();
tr_();
	td("100;r;Lokalizacja");
	td_(";;");
		echo "<input maxlength=50 size=45 type=text name=dfl onkeypress='return handleEnter(this, event);'>";
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
  frmvalidator.addValidation("dnf","req","Nie podano nazwy filii");
  frmvalidator.addValidation("dfkier","dontselect=0","Nie wybrałeś kierownika");
</script>	
<?php } ?>
</body>
</html>