<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) { 
	?><script>ShowWaitingMessage('Trwa zapisywanie danych...', 'Saving1');</script>
	<?php
	$_POST=sanitize($_POST);
	$sql_a = "INSERT INTO $dbname.serwis_slownik_monitor VALUES ('','$_POST[mm]','$_POST[om]','$_POST[cm]',$es_filia,'$_POST[tm]')";
	if (mysql_query($sql_a, $conn)) { 
		?>
		<script>HideWaitingMessage('Saving1');</script>
		<script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else {
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
	}
} else { 
pageheader("Dodawanie nowego modelu monitora");
starttable();
echo "<form name=add action=$PHP_SELF method=POST>";
tbl_empty_row();
tr_();
	td(";r;Model monitora");
	td_(";;");
		echo "<input id=monitor class=wymagane size=30 maxlength=100 type=text name=mm onKeyUp='slownik_monitorow()' onBlur='slownik_monitorow()'>";
		echo "<img name=status src=img//none.gif>";
		echo "<select name=lista style='display:none'>";
		$result=mysql_query("SELECT monitor_nazwa FROM $dbname.serwis_slownik_monitor ORDER BY monitor_nazwa",$conn) or die($k_b);
		while (list($temp)=mysql_fetch_array($result)) {
			echo "<option value=$temp>$temp</option>\n";
		}
		echo "</select>";		
	_td();
_tr();
tr_();
	td(";r;Typ");
	td_(";;");
		echo "<select name=tm onkeypress='return handleEnter(this, event);'>";
		echo "<option value='CRT'>CRT</option>\n";
		echo "<option value='LCD' selected>LCD</option>\n";
		echo "</select>";
	_td();
_tr();
tr_();
	td(";r;Ilość cali");
	td_(";;");
		echo "<input size=5 maxlength=11 type=text name=cm onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();
tr_();
	td(";r;Opis monitora");
	td_(";;");
		echo "<input size=50 maxlength=100 type=text name=om onkeypress='return handleEnter(this, event);'>";
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
  frmvalidator.addValidation("mm","req","Nie podano modelu monitora");
</script>
<?php } ?>
</body>
</html>