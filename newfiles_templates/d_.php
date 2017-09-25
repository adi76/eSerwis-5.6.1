<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) {
	$sql_a = "INSERT INTO $dbname.serwis_****** VALUES ('','$_POST[md]','$_POST[od]','$es_filia')";
	if (mysql_query($sql_a, $conn)) { 
		?><script>opener.location.reload(true); self.close(); </script><?php } 
		else {
			?><script>info('Wyst¹pi³ b³¹d podczas zapisywania zmian do bazy'); self.close(); </script><?php
		}
} else { ?>
<?php
echo "<h4>***********</h4>";

echo "<table cellspacing=1 align=center>";
echo "<form name=add action=$PHP_SELF method=POST>";
tbl_empty_row();

echo "<tr>";
echo "<td class=right>*****</td>";
echo "<td class=left>";
	echo "<input id=druk class=wymagane size=30 maxlength=100 type=text name=md onKeyUp='slownik_drukarek()'>";
	echo "<img class=imgoption name=status src=img//none.gif>";
	echo "<select name=lista style='display:none'>";
	$sql="SELECT drukarka_nazwa FROM $dbname.serwis_slownik_drukarka ORDER BY drukarka_nazwa";
	$result=mysql_query($sql,$conn);

	while ($dane=mysql_fetch_array($result)) {
		$temp = $dane['drukarka_nazwa'];
		echo "<option value=$temp>$temp</option>\n";
	}
	echo "</select>";
echo "</td>";
echo "</tr>";

echo "<tr>";
echo "<td class=right>***************</td>";
echo "<td class=left><input size=50 maxlength=100 type=text name=od onkeypress='return handleEnter(this, event)'></td>";
echo "</tr>";

tbl_empty_row();
echo "</table>";

startbuttonsarea("right");
addbuttons("zapisz","anuluj");
endbuttonsarea();

echo "</form>";
?>
<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("add");
  frmvalidator.addValidation("md","req","Nie podano ***********");
</script>
<?php } ?>
</body>
</html>