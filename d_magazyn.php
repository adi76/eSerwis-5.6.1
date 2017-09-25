<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) {
?>
<script>ShowWaitingMessage('Trwa zapisywanie danych...', 'Saving1');</script>
<?php 
$_POST=sanitize($_POST);
if ($_POST[tuwagi]!='') { $tuwagisa='1'; } else $tuwagisa='0';
$dddd = Date('Y-m-d H:i:s');
$sql_t = "INSERT INTO $dbname.serwis_magazyn values ('', '$_POST[tnazwa]','$_POST[tmodel]','$_POST[tsn]','$_POST[tni]',$tuwagisa,'".nl2br($_POST[tuwagi])."',$_POST[tstatus],$es_filia,'$currentuser','$dddd','','','0')";
if (mysql_query($sql_t, $conn)) 
	{ 
	?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
} else 
	{
	?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
	}
} else { 
	pageheader("Dodawanie nowego sprzętu serwisowego");
	starttable();
	echo "<form name=addt action=$PHP_SELF method=POST>";	
	tbl_empty_row();

	tr_();
		td("120;r;Typ sprzętu");	
		td_(";;;");
			$result = mysql_query("SELECT rola_id, rola_nazwa FROM $dbname.serwis_slownik_rola ORDER BY rola_nazwa", $conn) or die($k_b);	
			echo "<select class=wymagane name=tnazwa onkeypress='return handleEnter(this, event);'>\n"; 					 				
			echo "<option value=''>Wybierz z listy...";
				while (list($rola_id, $rola_nazwa) = mysql_fetch_array($result)) { echo "<option value='$rola_nazwa'>$rola_nazwa</option>\n"; }
			echo "</select>\n"; 
		_td();
	_tr();
	
	tr_();
		td("120;r;Model");
		td_(";;;");
			echo "<input class=wymagane size=31 maxlength=30 type=text name=tmodel onkeypress='return handleEnter(this, event);'>";
		_td();
	_tr();

	tr_();
		td("120;r;Numer seryjny");
		td_(";;;");
			echo "<input class=wymagane size=31 maxlength=30 type=text name=tsn onkeypress='return handleEnter(this, event);'>";
		_td();
	_tr();
	
	tr_();
		td("120;r;Numer inwentarzowy");
		td_(";;;");
			echo "<input size=21 maxlength=20 type=text name=tni onkeypress='return handleEnter(this, event);'>";
		_td();
	_tr();

	tr_();
		td("120;rt;Uwagi");
		td_(";;;");
			echo "<textarea name=tuwagi cols=39 rows=6></textarea>";
		_td();
	_tr();

	echo "<input type=hidden name=tstatus value='0'>";
	tbl_empty_row();
	endtable();
	
	startbuttonsarea("right");
	addbuttons("zapisz","anuluj");
	endbuttonsarea();

	_form();
?>	
<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("addt");
  frmvalidator.addValidation("tnazwa","dontselect=0","Nie wybrałeś typu sprzętu");
  frmvalidator.addValidation("tmodel","req","Nie podałeś modelu sprzętu");
  frmvalidator.addValidation("tsn","req","Nie podałeś numeru seryjnego sprzętu");
  frmvalidator.addValidation("tsn","alnumhyphen","Użyłeś niedozwolonych znaków w polu \"Numer seryjny\"");
</script>
<?php } ?>
</body>
</html>