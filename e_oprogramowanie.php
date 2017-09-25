<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php
if ($submit) { 
	$_POST=sanitize($_POST);
	$sql_a = "UPDATE $dbname.serwis_slownik_oprogramowania SET oprogramowanie_slownik_nazwa='$_POST[dnp]' WHERE oprogramowanie_slownik_id='$_POST[id]'";
	if (mysql_query($sql_a, $conn)) { 
		?><script>
		if (opener) opener.location.reload(true);
//		if (confirm("Czy chcesz zaktulizować całą bazę ewidencji oprogramowania uwzględniając zmianę nazwy oprogramowania ?")) {
				window.location.href='aktopr.php?id=<?php echo "$_POST[id]"; ?>';
//			} else self.close();		
		</script>
		<script>if (opener) opener.location.reload(true); self.close(); </script>
<?php
	} else {
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
	}
} else {
$result = mysql_query("SELECT oprogramowanie_slownik_nazwa FROM $dbname.serwis_slownik_oprogramowania WHERE (oprogramowanie_slownik_id=$id) LIMIT 1", $conn) or die($k_b);
list($temp_nazwa)=mysql_fetch_array($result);
pageheader("Edycja nazwy oprogramowania");
starttable();
echo "<form name=add action=$PHP_SELF method=POST>";
tbl_empty_row();
tr_();
	td(";r;Nazwa oprogramowania");
	td_(";;");
		echo "<input id=opr class=wymagane size=40 maxlength=40 type=text name=dnp onKeyUp='slownik_opr()' onBlur='slownik_opr()' value='$temp_nazwa'>";
		echo "<img name=status src=img//none.gif>";
		echo "<select name=lista style='display:none'>";
		$result=mysql_query("SELECT oprogramowanie_slownik_nazwa FROM $dbname.serwis_slownik_oprogramowania ORDER BY oprogramowanie_slownik_nazwa",$conn) or die($k_b);
		while (list($temp)=mysql_fetch_array($result)) {
			if ($temp!=$temp_nazwa) echo "<option value=$temp>$temp</option>\n";
		}
		echo "</select>";
	_td();
_tr();
tbl_empty_row();
endtable();
echo "<input type=hidden name=id value='$id'>";
startbuttonsarea("right");
addbuttons("zapisz","anuluj");
endbuttonsarea();
_form();
?>
<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("add");
  frmvalidator.addValidation("dnp","req","Nie podano nazwy oprogramowania");
</script>
<?php } ?>
</body>
</html>