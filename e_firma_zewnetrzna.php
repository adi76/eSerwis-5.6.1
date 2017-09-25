<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) { 
	$_POST=sanitize($_POST);
	if ($_POST['dtf_ds']!='on') $rz = ''; else $rz=$_POST['dfzrz'];
	if (($_POST['dnf']!='')) {
	$sql_e1="UPDATE $dbname.serwis_fz SET fz_nazwa = '$_POST[dnf]' , fz_adres = '$_POST[dadres]' , fz_telefon = '$_POST[dtelefon]' , fz_fax = '$_POST[dfax]' , fz_email = '$_POST[demail]' , fz_www = '$_POST[dwww]', belongs_to = $es_filia, fz_opis = '$_POST[dfo]', fz_is_fs='$_POST[dtf_fs]', fz_is_fk='$_POST[dtf_fk]', fz_is_ds='$_POST[dtf_ds]', fz_realizacja_zakupu='$rz' WHERE fz_id = '$fzid' LIMIT 1";
	if (mysql_query($sql_e1, $conn)) { 
			?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else {
		  ?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
		}
	} else {	
		?><script>info('Nie wypełniłeś wymaganych pól'); window.history.go(-1); </script><?php
	}
} else {
$sql_e = "SELECT * FROM $dbname.serwis_fz WHERE (fz_id=$select_id) LIMIT 1";
$result = mysql_query($sql_e, $conn) or die($k_b);
while ($newArray = mysql_fetch_array($result)) {
	$temp_id  			= $newArray['fz_id'];
	$temp_nazwa			= $newArray['fz_nazwa'];
	$temp_adres			= $newArray['fz_adres'];
	$temp_telefon		= $newArray['fz_telefon'];
	$temp_fax			= $newArray['fz_fax'];
	$temp_email			= $newArray['fz_email'];
	$temp_www			= $newArray['fz_www'];
	$temp_belongs_to	= $newArray['belongs_to'];
	$temp_opis			= $newArray['fz_opis'];
	$temp_is_fs			= $newArray['fz_is_fs'];
	$temp_is_fk			= $newArray['fz_is_fk'];
	$temp_is_ds			= $newArray['fz_is_ds'];
	$temp_rz			= $newArray['fz_realizacja_zakupu'];
}
pageheader("Edycja danych firmie zewnętrznej");
starttable();
echo "<form onSubmit=\"return checkCheckBoxes();\" name=fz action=$PHP_SELF method=POST >";
echo "<input size=30 type=hidden name=fzid value='$temp_id'>";
tbl_empty_row();
tr_();
	td("100;r;Nazwa firmy");
	td_(";;");
		echo "<input id=es class=wymagane size=45 maxlength=100 type=text name=dnf onKeyUp='slownik_es()' onBlur='slownik_es()' value='$temp_nazwa'>";
		echo "<img name=status src=img//none.gif>";
		echo "<select name=lista style='display:none'>";
		$sql="SELECT fz_nazwa FROM $dbname.serwis_fz ORDER BY fz_nazwa";
		$result=mysql_query($sql,$conn) or die($k_b);
		while ($dane=mysql_fetch_array($result)) {
			$temp = $dane['fz_nazwa'];
			if ($temp!=$temp_nazwa) echo "<option value=$temp>$temp</option>\n";
		}
		echo "</select>";
	_td();
_tr();
tr_();
	td("100;r;Funkcja:");
	tD_(";;");
		echo "<input class=border0 type=checkbox "; if ($temp_is_fs=='on') echo "checked"; echo " name=dtf_fs>";
		echo "<span style='cursor:hand' onclick=labelClick(document.fz.dtf_fs)> Firma serwisowa<br /></span>";
		echo "<input class=border0 type=checkbox "; if ($temp_is_fk=='on') echo "checked"; echo " name=dtf_fk>";
		echo "<span style='cursor:hand' onclick=labelClick(document.fz.dtf_fk)> Firma kurierska<br /></span>";
		echo "<input class=border0 id=wlacz onClick='pokazrealizacja();' type=checkbox "; if ($temp_is_ds=='on') echo "checked";echo " name=dtf_ds>";
		echo "<span style='cursor:hand' onclick='labelClick(document.fz.dtf_ds); pokazrealizacja();'> Dostawca sprzętu</span>";
	_td();
_tr();
echo "<tr id=realizacja "; if ($temp_is_ds=='on') echo "style=display:"; else echo "style=display:none"; echo ">";
	td("100;r;Realizacja zakupu");
	td_(";;");
		echo "<select name=dfzrz onkeypress='return handleEnter(this, event);'>";
		echo "<option value=''>Wybierz z listy</option>\n";
		echo "<option value='Dział Handlowy'"; if ($temp_rz=='Dział Handlowy') echo " SELECTED"; echo ">Dział Handlowy</option>\n";
		echo "<option value='Oddział ".$obszar."'"; if ($temp_rz=='Oddział '.$obszar.'') echo " SELECTED"; echo ">Oddział ".$obszar."</option>\n";
		echo "</select>\n";		
	_td();
_tr();
tr_();
	td("100;r;Opis firmy");
	td_(";;");
		echo "<input size=45 maxlength=100 type=text name=dfo value='$temp_opis' onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();
tr_();
	td("100;r;Adres");
	td_(";;");
		echo "<input size=45 maxlength=100 type=text name=dadres value='$temp_adres' onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();
tr_();
	td("100;r;Telefon");
	td_(";;");
		echo "<input size=45 maxlength=100 type=text name=dtelefon value='$temp_telefon ' onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();
tr_();
	td("100;r;Fax");
	td_(";;");
		echo "<input size=20 maxlength=100 type=text name=dfax value='$temp_fax' onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();
tr_();
	td("100;r;e-mail");
	td_(";;");
		echo "<input size=45 maxlength=100 type=text name=demail value='$temp_email' onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();
tr_();
	td("100;r;Strona WWW");
	td_(";;");
		echo "<input size=45 maxlength=100 type=text name=dwww value='$temp_www' onkeypress='return handleEnter(this, event);'>";
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
function checkCheckBoxes() {
	if (document.fz.dnf.value=='') { alert('Nie podano nazwy firmy'); return false; } else {
		if (document.fz.dtf_fs.checked == false && document.fz.dtf_fk.checked == false &&	    
			document.fz.dtf_ds.checked == false) {	
				alert ('Musisz wybrać przynajmniej jedną funkcję firmy!');
				return false;
			} else {
				return true;
				}
	}
}


<!---  var frmvalidator  = new Validator("fz");
  
<!--  frmvalidator.addValidation("dnf","req","Nie podano nazwy firmy serwisowej");
<!--  frmvalidator.addValidation("demail","email","Błędnie podany adres email");


</script>
<?php } ?>

</body>
</html>