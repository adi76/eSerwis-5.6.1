<?php include_once('header.php'); ?>
<body>
<?php 
$result1 = mysql_query("SELECT zestaw_opis,zestaw_uwagi FROM $dbname.serwis_zestawy WHERE zestaw_id=$id LIMIT 1", $conn) or die($k_b);
list($temp_opis,$temp_uwagi)=mysql_fetch_array($result1);
pageheader("Uwagi do zestawu");
startbuttonsarea("center");
echo "$temp_opis";
endbuttonsarea();
hr();
startbuttonsarea("left");	
echo "$temp_uwagi";
endbuttonsarea();
startbuttonsarea("right");
addbuttons("zamknij");
endbuttonsarea();
?>
</body>
</html>