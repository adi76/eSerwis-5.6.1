<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 
	$sql_e1="UPDATE $dbname.serwis_uzytkownicy SET belongs_to='99' WHERE user_id = '$_POST[userid]' LIMIT 1";
	//echo $sql_e1;
	
	if (mysql_query($sql_e1, $conn)) { 
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else {
		?><script>info('Wystąpił błąd podczas operacji usuwania'); self.close(); </script><?php
	}
} else {

$result = mysql_query("SELECT user_first_name, user_last_name FROM $dbname.serwis_uzytkownicy WHERE (user_id=$select_id) LIMIT 1", $conn) or die($k_b);
list($temp_fn, $temp_ln)=mysql_fetch_array($result);
errorheader("Czy napewno chcesz usunąć wybranego pracownika z bazy ?");
infoheader("<b>".$temp_fn." ".$temp_ln."</b>");
startbuttonsarea("center");
echo "<form action=$PHP_SELF method=POST>";
echo "<input type=hidden name=userid value=$_REQUEST[select_id]>";
addbuttons("tak","nie");
endbuttonsarea();
_form();
}
?>
</body>
</html>