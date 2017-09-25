<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) {
	$_POST=sanitize($_POST);
	if ($_POST['duwagi']!='') {
		$sql_e1="UPDATE $dbname.serwis_zadania SET zadanie_uwagi = '$_POST[duwagi]' WHERE zadanie_id = $uid LIMIT 1";
		if (mysql_query($sql_e1, $conn)) { 
				?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
		} else {
			?><script>info('Wystąpił błąd'); self.close(); </script><?php
		}
	} else {
		$sql_e1="UPDATE $dbname.serwis_zadania SET zadanie_uwagi = '' WHERE zadanie_id = $uid LIMIT 1";
		if (mysql_query($sql_e1, $conn)) { 
			?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
		} else {
			?><script>info('Wystąpił błąd'); self.close(); </script><?php
		}
	}
} else { ?>
<?php 
$result = mysql_query("SELECT zadanie_id,zadanie_opis,zadanie_uwagi FROM $dbname.serwis_zadania WHERE zadanie_id=$id LIMIT 1", $conn) or die($k_b);
list($temp_id,$mopis,$muwagi)=mysql_fetch_array($result);
pageheader("Edycja uwag do zadania");
starttable();
echo "<form name=edu action=$PHP_SELF method=POST>";
$opis=$mopis;
if (strlen($mopis)>60) $opis = substr($mopis,0,60)."...";
startbuttonsarea("center");
echo "$opis";
endbuttonsarea();
tbl_empty_row();
tr_();
	td("60;rt;Uwagi");
	td_(";;");
		echo "<textarea name=duwagi cols=42 rows=6>$muwagi</textarea>";
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