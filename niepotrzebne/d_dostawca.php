<?php include_once('header.php'); ?>
<body OnLoad="document.add.dnd.focus();">
<?php
if ($submit) {
	if (($_POST['dnd']!='')) {
		$sql_a = "INSERT INTO $dbname.serwis_dostawcy VALUES ('','$_POST[dnd]')";	
		if (mysql_query($sql_a, $conn)) { 
			?><script> opener.location.reload(true); self.close(); </script><?php } else {
			?><script>info('Wyst¹pi³ b³¹d podczas zapisywania zmian do bazy'); self.close(); </script><?php }
		} else { 
			?><script>info('Nie wype³ni³eœ wymaganych pól'); self.close(); </script><?php 
		}
} else { ?>
<?php
echo "<h4>Dodawanie nowego dostawcy towaru do bazy</h4>";

echo "<table cellspacing=1 align=center>";
echo "<form name=add action=$PHP_SELF method=POST>";
tbl_empty_row();

echo "<tr>";
echo "<td width=100 class=right>Nazwa dostawcy</td>";
echo "<td class=left><input id=dostawca class=wymagane size=35 maxlength=100 type=text name=dnd onKeyUp='slownik_dostawcow()'>";
echo "<img class=imgoption name=status src=img//none.gif>";
echo "<select name=lista style='display:none'>";
$sql="SELECT dostawca_nazwa FROM $dbname.serwis_dostawcy ORDER BY dostawca_nazwa";
$result=mysql_query($sql,$conn);
while ($dane=mysql_fetch_array($result)) {
	$temp = $dane['dostawca_nazwa'];
	echo "<option value=$temp>$temp</option>\n";
}
echo "</select>";		
echo "</td>";

tbl_empty_row();		
echo "</table>";

startbuttonsarea("right");
addbuttons("zapisz","anuluj");
endbuttonsarea();

echo "</form>";
?>
<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("add");
  frmvalidator.addValidation("dnd","req","Nie podano nazwy dostawcy");
</script>
<?php } ?>
</body>
</html>