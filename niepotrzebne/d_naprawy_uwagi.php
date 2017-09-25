<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) { 
	if ($_POST['duwagi']!='') {
		$sql_e1="UPDATE $dbname.serwis_naprawa SET naprawa_uwagi_sa = '1' , naprawa_uwagi = '".nl2br($_POST[duwagi])."' WHERE naprawa_id = '$uid' LIMIT 1";
		if (mysql_query($sql_e1, $conn)) { 
				?><script> opener.location.reload(true); self.close(); </script><?php	
		} else {
			  ?><script>info('Wyst¹pi³ b³¹d podczas zapisywania zmian do bazy'); self.close(); </script><?php
		}
	} else {
		?><script>info('Nie wype³ni³eœ wymaganych pól'); self.close(); </script><?php
	}
} else { ?>
<?php 
$sql_e = "SELECT * FROM $dbname.serwis_naprawa WHERE (naprawa_id=$id) LIMIT 1";
$result = mysql_query($sql_e, $conn) or die(mysql_error());
$newArray = mysql_fetch_array($result);
$temp_id  		= $newArray['naprawa_id'];
$temp_nazwa 	= $newArray['naprawa_nazwa'];
$temp_model 	= $newArray['naprawa_model'];
$temp_sn  		= $newArray['naprawa_sn'];
$temp_uwagi		= $newArray['naprawa_uwagi'];

echo "<h4>Dodawanie uwag o naprawie</h4><div class=show><b>$temp_nazwa $temp_model ($temp_sn)</b></div>";

echo "<table cellspacing=1 align=center style=width:450px>";
echo "<form name=add action=$PHP_SELF method=POST>";
echo "<input size=30 type=hidden name=uid value='$temp_id'>";
tbl_empty_row();

echo "<tr>";
	echo "<td width=120 class=righttop>Uwagi</td>";
	echo "<td><textarea name=duwagi cols=25 rows=6>".br2nl($temp_uwagi)."</textarea></td>";
echo "</tr>";

tbl_empty_row();
echo "</table>";

startbuttonsarea("right");
addbuttons("zapisz","anuluj");
endbuttonsarea();
	
echo "</form>";
}
?>
</body>
</html>