<?php include_once('header.php'); ?>
<body>
<?php 
$result1 = mysql_query("SELECT magazyn_uwagi,magazyn_nazwa,magazyn_model,magazyn_sn FROM $dbname.serwis_magazyn WHERE magazyn_id=$id LIMIT 1", $conn) or die($k_b);
list($muwagi,$temp_nazwa,$temp_model,$temp_sn)=mysql_fetch_array($result1);
pageheader("Uwagi o sprzęcie");
startbuttonsarea("center");
echo "$temp_nazwa $temp_model (SN: $temp_sn)";
hr();

endbuttonsarea();
startbuttonsarea("left");
echo "$muwagi";
endbuttonsarea();

startbuttonsarea("right");
addbuttons("zamknij");
endbuttonsarea();

?>
</body>
</html>