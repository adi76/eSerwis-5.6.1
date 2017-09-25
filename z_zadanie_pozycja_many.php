<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 
	$dddd = date('Y-m-d H:i:s');
	$sql_d1="UPDATE $dbname.serwis_zadania_pozycje SET pozycja_status=9, pozycja_data_modyfikacji='$dddd', pozycja_modyfikowane_przez = '$currentuser' WHERE (pozycja_id  IN (".$_REQUEST[nr]."))";
	if (mysql_query($sql_d1, $conn)) { 
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else { 
		?><script>info('Wystąpił błąd podczas operacji usuwania'); self.close(); </script>
	<?php } 
} else {
errorheader("Czy napewno chcesz potwierdzić wykonanie zadania w poniższych komórkach ?");
$sql = "SELECT * FROM $dbname.serwis_zadania_pozycje WHERE (pozycja_id  IN (".$_REQUEST[nr]."))";
$result5 = mysql_query($sql, $conn) or die($k_b);
$k = '';
while ($newArray = mysql_fetch_array($result5)) {
	$temp_komorka  			= $newArray['pozycja_komorka'];
	
	$k .= $temp_komorka."<br />";
}
	
infoheader("<b>".$k."</b>");
echo "<br />";
startbuttonsarea("center");
echo "<form action=$PHP_SELF method=POST>";
echo "<input type=hidden name=id value=$id>";
echo "<input type=hidden name=nr value=$_REQUEST[nr]>";
addbuttons("tak","nie");
endbuttonsarea();
_form();
} 
?>
</body>
</html>