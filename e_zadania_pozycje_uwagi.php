<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) {
	$_POST=sanitize($_POST);
	if ($_POST['duwagi']!='') {
	$sql_e1="UPDATE $dbname.serwis_zadania_pozycje SET pozycja_uwagi = '".nl2br($_POST[duwagi])."' WHERE pozycja_id = $uid LIMIT 1";
	if (mysql_query($sql_e1, $conn)) { 
			?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else {
		?><script>info('Wystąpił błąd'); self.close(); </script><?php
	}
			
	} else {
		$sql_e1="UPDATE $dbname.serwis_zadania_pozycje SET pozycja_uwagi = '' WHERE pozycja_id = $uid LIMIT 1";
		if (mysql_query($sql_e1, $conn)) { 
			?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
		} else {
			?><script>info('Wystąpił błąd'); self.close(); </script><?php
		}
	}
} else {
$sql1 = "SELECT pozycja_id,pozycja_komorka,pozycja_przypisane_osobie,pozycja_uwagi FROM $dbname.serwis_zadania_pozycje WHERE pozycja_id=$id";
$result = mysql_query($sql1, $conn) or die($k_b);
while ($newArray = mysql_fetch_array($result)) {
   $temp_id				= $newArray['pozycja_id'];
   $mopis				= $newArray['pozycja_komorka'];
   $temp_przyp_osobie 	= $newArray['pozycja_przypisane_osobie'];
   $muwagi				= $newArray['pozycja_uwagi'];

}
pageheader("Edycja uwag dla komórki:");
infoheader("<b>$_REQUEST[komorka]</b>");
starttable();
echo "<form name=edu action=$PHP_SELF method=POST>";
tbl_empty_row();
	tr_();
		td("120;rt;Uwagi");
		td_(";;");
			echo "<textarea name=duwagi cols=35 rows=6>".br2nl($muwagi)."</textarea>";
		_td();
	_tr();
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