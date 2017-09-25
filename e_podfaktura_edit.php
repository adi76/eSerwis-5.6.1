<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) {	
	$_POST=sanitize($_POST);
	if ($_POST['duwagi']!='') {
		$sql_e1="UPDATE $dbname.serwis_podfaktury SET pf_uwagi = '".nl2br($_POST[duwagi])."' WHERE pf_id = '$uid' LIMIT 1";
		if (mysql_query($sql_e1, $conn)) { 
			?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
		} else {
			?><script>info('Wystąpił błąd'); self.close(); </script><?php
		}		
	} else {
		$sql_e1="UPDATE $dbname.serwis_podfaktury SET pf_uwagi = '' WHERE pf_id = '$uid' LIMIT 1";
		if (mysql_query($sql_e1, $conn)) { 
			?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
		} else {
			?><script>info('Wystąpił błąd'); self.close(); </script><?php
		}
	}
} else { ?>
<?php
$sql_e = "SELECT pf_id,pf_numer,pf_uwagi FROM $dbname.serwis_podfaktury WHERE (pf_id=$id) LIMIT 1";
$result = mysql_query($sql_e, $conn) or die($k_b);
$newArray = mysql_fetch_array($result);
$temp_id  		= $newArray['pf_id'];
$temp_numer 	= $newArray['pf_numer'];
$temp_uwagi		= $newArray['pf_uwagi'];

pageheader("Edycja uwag do podfaktury");
startbuttonsarea("center");
echo "Podfaktura nr : $temp_numer";
endbuttonsarea();

starttable();
echo "<form name=edu action=$PHP_SELF method=POST>";

tbl_empty_row();

echo "<tr>";
	echo "<td width=120 class=righttop>Uwagi</td>";
	echo "<td><textarea name=duwagi cols=35 rows=7>".br2nl($temp_uwagi)."</textarea></td>";
echo "</tr>";

tbl_empty_row();	
endtable();
echo "<input size=30 type=hidden name=uid value='$temp_id'>";	
startbuttonsarea("right");
addbuttons("zapisz","anuluj");
endbuttonsarea();

_form();
}
?>
</body>
</html>