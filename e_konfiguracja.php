<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) { 
	$_POST=sanitize($_POST);
	$tylkodoew='0';
	$pr = str_replace(',','.',$_POST[procesor]);
	$pa = str_replace(',','.',$_POST[pamiec]);
	$dy = str_replace(',','.',$_POST[dysk]);
	$konf_opis='Procesor '.$pr.'GHz, '.$pa.'MB RAM, '.$dy.'GB HDD';
	if ($_POST['tde']=='on' ) $tylkodoew='1'; 
	$sql_a = "UPDATE $dbname.serwis_slownik_konfiguracja SET konfiguracja_nazwa='$konf_opis', konfiguracja_opis='$_POST[oks]', procesor='$pr', pamiec='$pa', dysk='$dy' WHERE konfiguracja_id='$_POST[id]' LIMIT 1";
	if (mysql_query($sql_a, $conn)) { 
		?><script>
		if (opener) opener.location.reload(true);
		if (confirm("Czy chcesz zaktulizować całą bazę ewidencji sprzętu uwzględniając zmiany w konfiguracji sprzętu ?")) {
				window.location.href='e_konfiguracja_update.php?id=<?php echo "$_POST[id]&HDD=$HDD&RAM=$RAM&PROC=$PROC"; ?>';
			} else self.close();		
		</script>
		<script>if (opener) opener.location.reload(true); self.close(); </script>
	<?php } else 
	{
	  ?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
	}
} else {
$result = mysql_query("SELECT konfiguracja_id,konfiguracja_nazwa,konfiguracja_opis,pamiec,dysk,procesor FROM $dbname.serwis_slownik_konfiguracja WHERE (konfiguracja_id=$id) LIMIT 1", $conn) or die($k_b);
list($temp_id,$temp_nazwa,$temp_opis,$temp_RAM,$temp_HDD,$temp_PROCESOR)=mysql_fetch_array($result);
pageheader("Edycja konfiguracji sprzętu");
starttable();
echo "<form name=add action=$PHP_SELF method=POST>";
tbl_empty_row();
tr_();
	td(";r;Procesor");
	td_(";;");
		echo "<input class=wymagane size=10 maxlength=10 type=text name=procesor value='$temp_PROCESOR' onkeypress='return handleEnter(this, event);'>&nbsp;GHz";
	_td();
_tr();
tr_();
	td(";r;Pamięć");
	td_(";;");
		echo "<input class=wymagane size=10 maxlength=10 type=text name=pamiec value='$temp_RAM' onkeypress='return handleEnter(this, event);'>&nbsp;MB";
	_td();
_tr();
tr_();
	td(";r;Dysk");
	td_(";;");
		echo "<input class=wymagane size=10 maxlength=10 type=text name=dysk value='$temp_HDD' onkeypress='return handleEnter(this, event);'>&nbsp;GB";
	_td();
_tr();
tr_();
	td(";r;Opis sprzętu");
	td_(";;");
		echo "<input size=50 maxlength=100 type=text name=oks value='$temp_opis' onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();
tbl_empty_row();	
endtable();
echo "<input type=hidden name=RAM value='$RAM'>";	
echo "<input type=hidden name=HDD value='$HDD'>";	
echo "<input type=hidden name=PROC value='$PROC'>";	
echo "<input type=hidden name=id value='$id'>";	
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