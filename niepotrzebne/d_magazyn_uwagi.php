<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) { 
	
	if ($_POST['duwagi']!='') {
	
	$sql_e1="UPDATE $dbname.serwis_magazyn SET magazyn_uwagi_sa = '1' , magazyn_uwagi = '".nl2br($_POST[duwagi])."' WHERE magazyn_id = '$uid' LIMIT 1";
	
	if (mysql_query($sql_e1, $conn)) { 
			?><script> opener.location.reload(true); self.close(); </script><?php	
	} else 
		{
		  ?><script>info('Wyst¹pi³ b³¹d podczas zapisywania zmian do bazy'); self.close(); </script><?php
			}
			
	} else {
	
		  ?><script>info('Nie wype³ni³eœ wymaganych pól'); self.close(); </script><?php
	
	}
} else { 
$sql_e = "SELECT * FROM $dbname.serwis_magazyn WHERE (magazyn_id=$id) LIMIT 1";

$result = mysql_query($sql_e, $conn) or die(mysql_error());
while ($newArray = mysql_fetch_array($result)) {
	$temp_id  			= $newArray['magazyn_id'];
	$temp_nazwa  		= $newArray['magazyn_nazwa'];
	$temp_model  		= $newArray['magazyn_model'];
	$temp_sn  			= $newArray['magazyn_sn'];

	$temp_uwagi			= $newArray['magazyn_uwagi'];

}

	echo "<h4>Dodawanie uwag o sprzêcie</h4>";
	echo "<div class=show>$temp_nazwa $temp_model ($temp_sn)</div>";

	echo "<table cellspacing=1 align=center>";
	echo "<form name=add action=$PHP_SELF method=POST>";
	tbl_empty_row();

		echo "<tr>";
		echo "<td width=120 class=righttop>Uwagi</td>";
		echo "<td><textarea name=duwagi cols=37 rows=6>".br2nl($temp_uwagi)."</textarea></td>";
		echo "</tr>";
		
	tbl_empty_row();
	echo "</table>";

	startbuttonsarea("right");
	addbuttons("zapisz","anuluj");
	endbuttonsarea();
	echo "<input size=30 type=hidden name=uid value='$temp_id'>";

	echo "</form>";
}
?>
</body>
</html>