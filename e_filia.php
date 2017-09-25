<?php include_once('header.php'); ?>
<body onLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) { 
	$_POST=sanitize($_POST);
	$sql_a = "UPDATE $dbname.serwis_filie SET filia_nazwa='$_POST[dnf]', filia_adres='$_POST[dna]', filia_kontakt='$_POST[dfk]', filia_leader=$_POST[dfkier], filia_skrot='$_POST[dfs]', filia_lokalizacja='$_POST[dfl]' WHERE filia_id=$_POST[id] LIMIT 1";
	$es_skrot='$_POST[dfs]';
	if (mysql_query($sql_a, $conn)) { 
			?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else {
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
	}
} else {
pageheader("Edycja danych o filii");
$result = mysql_query("SELECT filia_id,filia_nazwa,filia_adres,filia_kontakt,filia_leader,filia_skrot,filia_lokalizacja FROM $dbname.serwis_filie WHERE (filia_id=$id) LIMIT 1", $conn) or die($k_b);
list($temp_id,$temp_nazwa,$temp_adres,$temp_kontakt,$temp_kierownik_id,$temp_skrot,$temp_lok)=mysql_fetch_array($result);
starttable();
echo "<form name=add action=$PHP_SELF method=POST>";
tbl_empty_row();
tr_();
	td("100;r;Nazwa filii");
	td_(";;");
		echo "<input id=umowa class=wymagane size=40 maxlength=30 type=text name=dnf value='$temp_nazwa' onKeyUp='slownik_filie()' onBlur='slownik_filie()'>";
		echo "<img name=status src=img//none.gif>";
		echo "<select name=lista style='display:none'>";
		$result=mysql_query("SELECT filia_nazwa FROM $dbname.serwis_filie",$conn) or die($k_b);
		while (list($temp)=mysql_fetch_array($result)) {
			if ($temp!=$temp_nazwa) echo "<option value='$temp'>$temp</option>\n";			
		}
		echo "</select>";
	_td();
_tr();
tr_();
	td("100;r;Adres");
	td_(";;");
		echo "<input size=40 maxlength=200 type=text name=dna value='$temp_adres' onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();
tr_();
	td("100;r;Telefon / Fax");
	td_(";;");
		echo "<input size=25 maxlength=50 type=text name=dfk value='$temp_kontakt' onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();
tr_();
	td("100;r;Kierownik");
	td_(";;");
		echo "<select class=wymagane name=dfkier onkeypress='return handleEnter(this, event);'>";
		echo "<option value=''>Wybierz z listy</option>\n";
		$result=mysql_query("SELECT user_id, user_first_name, user_last_name FROM $dbname.serwis_uzytkownicy ORDER BY user_login ASC",$conn) or die($k_b);
		while (list($temp_fid,$temp_i,$temp_n)=mysql_fetch_array($result)) {
			echo "<option ";
			if ($temp_fid==$temp_kierownik_id) { echo "SELECTED "; }
			echo "value=$temp_fid>$temp_i $temp_n</option>\n";
		}
		echo "</select>";
	_td();
_tr();
tr_();
	td("100;r;Skrót nazwy");
	td_(";;");
		echo "<input size=10 maxlength=10 type=text name=dfs value='$temp_skrot' onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();
tr_();
	td("100;r;Lokalizacja");
	td_(";;");
		echo "<input maxlength=50 size=45 type=text name=dfl value='$temp_lok' onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();
echo "<input type=hidden name=id value=$temp_id>";
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