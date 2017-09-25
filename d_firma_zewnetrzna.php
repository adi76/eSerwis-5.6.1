<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) { 
	?><script>ShowWaitingMessage('Trwa zapisywanie danych...', 'Saving1');</script>
	<?php
	$_POST=sanitize($_POST);
	if (($_POST['dnf']!='')) {
	$sql_a = "INSERT INTO $dbname.serwis_fz VALUES ('','$_POST[dnf]','$_POST[dadres]','$_POST[dtelefon]','$_POST[dfax]','$_POST[demail]','$_POST[dwww]',$es_filia,'$_POST[dfo]','$_POST[dtf_fs]','$_POST[dtf_fk]','$_POST[dtf_ds]','$_POST[dfzrz]')";
	
	if (mysql_query($sql_a, $conn)) 
		{ 
			?>
			<script>HideWaitingMessage('Saving1');</script>
			<script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else 
		{
		  ?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
		}
	} else {	
			?><script>info('Nie wypełniłeś wymaganych pól'); self.close(); </script><?php
	}
} else { ?>
<?php
pageheader("Dodawanie nowej firmy zewnętrznej");
starttable();
echo "<form name=fz action=$PHP_SELF method=POST onSubmit=\"return checkCheckBoxes();\">";
tbl_empty_row();
tr_();
	td("100;r;Nazwa firmy");
	td_(";;");
	echo "<input id=serwisowa class=wymagane size=30 maxlength=100 type=text name=dnf onKeyUp='slownik_serwisow()' onBlur='slownik_serwisow()'>";
	echo "<img name=status src=img//none.gif>";
		echo "<select name=lista style='display:none' onkeypress='return handleEnter(this, event);'>";
		$sql="SELECT fz_nazwa FROM $dbname.serwis_fz ORDER BY fz_nazwa";
		$result=mysql_query($sql,$conn) or die($k_b);
		while ($dane=mysql_fetch_array($result)) {
			$temp = $dane['fz_nazwa'];
			echo "<option value=$temp>$temp</option>\n";
		}
		echo "</select>";		
	_td();
_tr();
tr_();
	td("100;r;Funkcja:");
	td_(";;");
		echo "<input class=border0 type=checkbox name=dtf_fs>";
		echo "<span style='cursor:hand' onclick=labelClick(document.fz.dtf_fs)> Firma serwisowa<br /></span>";
		echo "<input class=border0 type=checkbox name=dtf_fk>";
		echo "<span style='cursor:hand' onclick=labelClick(document.fz.dtf_fk)> Firma kurierska<br /></span>";
		echo "<input class=border0 id=wlacz type=checkbox name=dtf_ds onClick='pokazrealizacja();'>";
		echo "<span style='cursor:hand' onclick='labelClick(document.fz.dtf_ds); pokazrealizacja(); '> Dostawca sprzętu</span>";		
	_td();
_tr();
echo "<tr id=realizacja style=display:none>";
	td("100;r;Realizacja zakupu");
	td_(";;");
		echo "<select name=dfzrz onkeypress='return handleEnter(this, event);'>";
		echo "<option value=''>Wybierz z listy</option>\n";
		echo "<option value='Dział Handlowy'>Dział Handlowy</option>\n";
		echo "<option value='Oddział $obszar'>Oddział $obszar</option>\n";
		echo "</select>\n";		
	_td();
_tr();
tr_();
	td("100;r;Opis firmy");
	td_(";;");
		echo "<input size=45 maxlength=100 type=text name=dfo onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();
tr_();
	td("100;r;Adres");
	td_(";;");
		echo "<input size=45 maxlength=100 type=text name=dadres onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();
tr_();
	td("100;r;Telefon");
	td_(";;");
		echo "<input size=45 maxlength=100 type=text name=dtelefon onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();
tr_();
	td("100;r;Fax");
	td_(";;");
		echo "<input size=30 maxlength=100 type=text name=dfax onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();
tr_();
	td("100;r;e-mail");
	td_(";;");
		echo "<input size=40 maxlength=100 type=text name=demail onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();
tr_();
	td("100;r;Strona WWW");
	td_(";;");
		echo "<input size=45 maxlength=100 type=text name=dwww onkeypress='return handleEnter(this, event);'>";
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
		if (document.fz.dtf_fs.checked == false &&
			document.fz.dtf_fk.checked == false &&	    
			document.fz.dtf_ds.checked == false) {		
				alert ('Musisz wybrać przynajmniej jedną funkcję firmy!');
				return false;
			} else {
				return true;
				}
	}
}
</script>	
<?php } ?>
</body>
</html>