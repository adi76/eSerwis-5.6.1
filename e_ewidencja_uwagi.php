<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) { 
$_POST=sanitize($_POST);
	if ($_POST['duwagi']!='') {
		$sql_e1="UPDATE $dbname.serwis_ewidencja SET ewidencja_uwagi = '".nl2br($_POST[duwagi])."' WHERE ewidencja_id = '$uid' LIMIT 1";
		if (mysql_query($sql_e1, $conn)) { 
				?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
		} else {
			?><script>info('Wystąpił błąd podczas operacji usuwania'); self.close(); </script><?php
		}
	} else {
		$sql_e1="UPDATE $dbname.serwis_ewidencja SET ewidencja_uwagi = '' WHERE ewidencja_id = '$uid' LIMIT 1";
		if (mysql_query($sql_e1, $conn)) { 
			?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
		} else {
			?><script>info('Wystąpił błąd podczas operacji usuwania'); self.close(); </script><?php
			}
	}
} else { 
$sql_e = "SELECT ewidencja_typ_nazwa, ewidencja_up_nazwa, ewidencja_id, ewidencja_komputer_opis, ewidencja_komputer_sn, ewidencja_drukarka_opis, ewidencja_drukarka_sn, ewidencja_uwagi FROM $dbname.serwis_ewidencja WHERE (ewidencja_id=$id) LIMIT 1";
$result = mysql_query($sql_e, $conn) or die($k_b);
while ($newArray = mysql_fetch_array($result)) {
	$temp_id  			= $newArray['ewidencja_id'];
	$temp_typn			= $newArray['ewidencja_typ_nazwa'];
	$temp_upnazwa		= $newArray['ewidencja_up_nazwa'];
	$temp_uwagi			= $newArray['ewidencja_uwagi'];
	
if (($temp_typn=='Komputer') || ($temp_typn=='Serwer') || ($temp_typn=='Notebook')) {	
	$temp_nazwa  		= $newArray['ewidencja_komputer_opis'];
	$temp_sn  			= $newArray['ewidencja_komputer_sn'];
	pageheader("Edycja uwag o sprzęcie");
	startbuttonsarea("center");
		echo $temp_typn." ".$temp_nazwa." (SN: ".$temp_sn.")<br />".$temp_upnazwa;
	endbuttonsarea();
} elseif ($temp_typn=='Drukarka') {
	$temp_dnazwa  		= $newArray['ewidencja_drukarka_opis'];
	$temp_dsn  			= $newArray['ewidencja_drukarka_sn'];
	pageheader("Edycja uwag o drukarce");
	startbuttonsarea("center");
		echo $temp_dnazwa." (SN: ".$temp_dsn.")<br />".$temp_upnazwa;
	endbuttonsarea();
} else {
	pageheader("Edycja uwag o sprzęcie");
	startbuttonsarea("center");
		echo $temp_typn."<br />".$temp_upnazwa;
	endbuttonsarea();
	}
}
starttable();
echo "<form name=edu action=$PHP_SELF method=POST>";
tbl_empty_row();
tr_();
	td("120;rt;Uwagi");
	td_(";;");
		echo "<textarea name=duwagi cols=35 rows=6>".br2nl($temp_uwagi)."</textarea>";
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