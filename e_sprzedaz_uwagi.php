<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) { 
	$_POST=sanitize($_POST);
	$sql_e1="UPDATE $dbname.serwis_sprzedaz SET sprzedaz_uwagi = '".nl2br($_POST[duwagi])."' WHERE sprzedaz_id = '$uid' LIMIT 1";
	if (mysql_query($sql_e1, $conn)) { 
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else {
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
	}
} else { ?>
<?php
$sql_e="SELECT sprzedaz_id,sprzedaz_pozycja_nazwa,sprzedaz_pozycja_sn,sprzedaz_uwagi FROM $dbname.serwis_sprzedaz WHERE (sprzedaz_id=$id) LIMIT 1";
$result = mysql_query($sql_e, $conn) or die($k_b);
$newArray = mysql_fetch_array($result);
$temp_id  		= $newArray['sprzedaz_id'];
$temp_nazwa  	= $newArray['sprzedaz_pozycja_nazwa'];
$temp_sn  		= $newArray['sprzedaz_pozycja_sn'];
$temp_uwagi		= $newArray['sprzedaz_uwagi'];

pageheader("Edycja uwag do sprzedaży towaru / usługi");
infoheader("".$temp_nazwa." (SN: ".$temp_sn.")");

starttable();
echo "<form name=edu action=$PHP_SELF method=POST>";

tbl_empty_row();

echo "<tr>";
	echo "<td width=120 class=righttop>Uwagi</td>";
	echo "<td><textarea name=duwagi cols=35 rows=6>".br2nl($temp_uwagi)."</textarea></td>";
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