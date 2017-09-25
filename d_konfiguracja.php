<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) { 
	?><script>ShowWaitingMessage('Trwa zapisywanie danych...', 'Saving1');</script>
	<?php
	$_POST=sanitize($_POST);
	$procesor1 = str_replace(',','.',$_POST['procesor']);
	$pamiec1 = str_replace(',','.',$_POST['pamiec']);
	$dysk1 = str_replace(',','.',$_POST['dysk']);
	$tylkodoew='0';
	$konf_opis='Procesor '.$procesor1.'GHz, '.$pamiec1.'MB RAM, '.$dysk1.'GB HDD';
	if ($_POST['tde']=='on' ) $tylkodoew='1';
	$sql_a = "INSERT INTO $dbname.serwis_slownik_konfiguracja VALUES ('','$konf_opis',$es_filia,'$_POST[oks]','$procesor1','$pamiec1','$dysk1')";
	if (mysql_query($sql_a, $conn)) { 
		?>
		<script>HideWaitingMessage('Saving1');</script>
		<script>if (opener) opener.location.reload(true); self.close();</script><?php
	} else {
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
	}
} else { 
pageheader("Dodawanie nowej konfiguracji sprzętu do słownika");
starttable();
echo "<form name=add action=$PHP_SELF method=POST>";	
tbl_empty_row();
tr_();
	td(";r;Procesor");
	td_(";;");
		echo "<input class=wymagane size=10 maxlength=10 type=text name=procesor  onkeypress='return handleEnter(this, event);'>&nbsp;GHz";
	_td();
_tr();
tr_();
	td(";r;Pamięć");
	td_(";;");
		echo "<input class=wymagane size=10 maxlength=10 type=text name=pamiec  onkeypress='return handleEnter(this, event);'>&nbsp;MB";
	_td();
_tr();
tr_();
	td(";r;Dysk");
	td_(";;");
		echo "<input class=wymagane size=10 maxlength=10 type=text name=dysk  onkeypress='return handleEnter(this, event);'>&nbsp;GB";
	_td();
_tr();
tr_();
	td(";r;Opis sprzętu");
	td_(";;");
		echo "<input size=50 maxlength=100 type=text name=oks onkeypress='return handleEnter(this, event);'>";
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
  frmvalidator.addValidation("procesor","req","Nie podano prędkości procesora");
  frmvalidator.addValidation("pamiec","req","Nie podano ilości pamięci");
  frmvalidator.addValidation("dysk","req","Nie podano rozmiaru dysku");
</script>
<?php } ?>
</body>
</html>