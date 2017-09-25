<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) {
	$_POST=sanitize($_POST);
	if ($_POST['duwagi']!='') {
	
	$sql_e1="UPDATE $dbname.serwis_faktury SET faktura_uwagi = '".nl2br($_POST[duwagi])."' WHERE faktura_id = '$uid' LIMIT 1";
	
	if (mysql_query($sql_e1, $conn)) { 
			?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else 
		{
			?><script>info('Wystąpił błąd'); self.close(); </script><?php
		}
			
	} else {
	
	$sql_e1="UPDATE $dbname.serwis_faktury SET faktura_uwagi = '' WHERE faktura_id = '$uid' LIMIT 1";
	
	if (mysql_query($sql_e1, $conn)) { 
			?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else 
		{
			?><script>info('Wystąpił błąd'); self.close(); </script><?php
			}
	
	}

} else {

$sql_e = "SELECT * FROM $dbname.serwis_faktury WHERE (faktura_id=$id) LIMIT 1";

$result = mysql_query($sql_e, $conn) or die($k_b);
while ($newArray = mysql_fetch_array($result)) {
	$temp_id  			= $newArray['faktura_id'];
	$temp_numer  		= $newArray['faktura_numer'];
	$temp_uwagi			= $newArray['faktura_uwagi'];

}
	pageheader("Edycja uwag do faktury");
	startbuttonsarea("center");
	echo "Faktura nr : $temp_numer";
	endbuttonsarea();
	starttable();
	echo "<form name=edu action=$PHP_SELF method=POST>";	
	echo "<input size=30 type=hidden name=uid value='$temp_id'>";
	
	tbl_empty_row();

		echo "<tr>";
		echo "<td width=120 class=righttop>Uwagi</td>";
		echo "<td><textarea name=duwagi cols=35 rows=6>".br2nl($temp_uwagi)."</textarea></td>";
		echo "</tr>";
	tbl_empty_row();	
	endtable();

	startbuttonsarea("right");
	addbuttons("zapisz","anuluj");
	endbuttonsarea();

	_form();
} 
?>

</body>
</html>