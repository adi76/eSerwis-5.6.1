<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) { 
	$_POST=sanitize($_POST);
	$sql_a = "UPDATE $dbname.serwis_umowy SET umowa_opis='$_POST[dopis]', umowa_koordynator='$_POST[dkoordynator]', umowa_koordynator_email='$_POST[dkoordynatoremail]' WHERE umowa_id=$_POST[umowaid] LIMIT 1";
	if (mysql_query($sql_a, $conn)) { 
			?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else 	{
		  ?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
		}
} else {
pageheader("Edycja umowy");

$result = mysql_query("SELECT * FROM $dbname.serwis_umowy WHERE (umowa_id=$_REQUEST[id]) LIMIT 1", $conn) or die($k_b);
$newArray = mysql_fetch_array($result);
$temp_id  			= $newArray['umowa_id'];		
$temp_numer  		= $newArray['umowa_nr'];		
$temp_nr_zl			= $newArray['umowa_nr_zlecenia'];
$temp_opis			= $newArray['umowa_opis'];				
$temp_koord			= $newArray['umowa_koordynator'];
$temp_koord_email	= $newArray['umowa_koordynator_email'];

starttable();
echo "<form name=add action=$PHP_SELF method=POST>";	
tbl_empty_row();
tr_();
	td("100;r;Numer umowy");
	td_(";;");
		echo "<b>$temp_numer</b>";
	_td();
_tr();
tr_();
	td("100;r;Numer zlecenia");
	td_(";;");
		echo "<b>$temp_nr_zl</b>";
	_td();
_tr();
tr_();
	td("100;r;Opis");
	td_(";;");
		echo "<input size=45 maxlength=100 type=text name=dopis value='$temp_opis' onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();
tr_();
	td("100;r;Koordynator");
	td_(";;");
		echo "<input size=45 maxlength=100 type=text name=dkoordynator value='$temp_koord' onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();
tr_();
	td("100;r;Email koordynatora");
	td_(";;");
		echo "<input size=45 maxlength=100 type=text name=dkoordynatoremail value='$temp_koord_email' onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();
tbl_empty_row();
endtable();
echo "<input type=hidden name=umowaid value='$temp_id'>";	
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