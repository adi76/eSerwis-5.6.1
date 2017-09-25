<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) {
	$_POST=sanitize($_POST);
	if (($_POST['dmodel']!='') && ($_POST['dsn']!='')) {
	
	$sql_e1="UPDATE $dbname.serwis_magazyn SET magazyn_model = '$_POST[dmodel]', magazyn_sn = '$_POST[dsn]', magazyn_ni = '$_POST[dni]' WHERE magazyn_id = '$uid' LIMIT 1";
	
	if (mysql_query($sql_e1, $conn)) { 
			?><script>if (opener) opener.location.reload(true); self.close(); </script><?php	
	} else 
		{
		  ?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
		}
			
	} else {
		  ?><script>info('Nie wypełniłeś wymaganych pól'); self.close(); </script><?php
	}
} else { ?>

<?php 
$result = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni,magazyn_uwagi_sa,magazyn_status FROM $dbname.serwis_magazyn WHERE (magazyn_id=$id) LIMIT 1", $conn) or die($k_b);
list($mid,$mnazwa,$mmodel,$msn,$mni,$muwagi,$mstatus) = mysql_fetch_array($result);
pageheader("Edycja danych o sprzęcie");
starttable();
echo "<form name=edu action=$PHP_SELF method=POST>";
tbl_empty_row();
	tr_();
		td("120;r;Typ sprzętu");
		td_(";;;");
			echo "<b>$mnazwa</b>";
		_td();
	_tr();
	tr_();
		td("120;r;Model");
		td_(";;;");
			echo "<input class=wymagane size=33 maxlength=30 type=text name=dmodel value='$mmodel'>";
		_td();
	_tr();
	tr_();
		td("120;r;SN");
		td_(";;;");
			echo "<input class=wymagane size=33 maxlength=30 type=text name=dsn value='$msn'>";
		_td();
	_tr();
	tr_();
		td("120;r;NI");
		td_(";;;");
			echo "<input type=text name=dni size=21 maxlength=20 value='$mni'>";
		_td();
	_tr();
	tbl_empty_row();
endtable();
echo "<input size=30 type=hidden name=uid value='$mid'>";
startbuttonsarea("right");
addbuttons("zapisz","anuluj");
endbuttonsarea();
_form();
?>
<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("edu");
  frmvalidator.addValidation("dmodel","req","Nie podałeś modelu sprzętu");
  frmvalidator.addValidation("dsn","reg","Nie podałeś numeru seryjnego sprzętu");
</script>
<?php } ?>
</body>
</html>