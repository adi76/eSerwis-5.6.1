<?php include_once('header.php'); ?>
<body>
<?php 
$sql_e = "SELECT pozycja_id, pozycja_nazwa, pozycja_sn, pozycja_uwagi FROM $dbname.serwis_faktura_szcz WHERE (pozycja_id=$id) LIMIT 1";
$result = mysql_query($sql_e, $conn) or die($k_b);
$newArray = mysql_fetch_array($result);
$temp_id  	= $newArray['pozycja_id'];
$temp_nazwa = $newArray['pozycja_nazwa'];
$temp_sn  	= $newArray['pozycja_sn'];
$temp_uwagi	= $newArray['pozycja_uwagi'];

pageheader("Uwagi o towarze");
startbuttonsarea("center");
echo "$temp_nazwa";
if ($temp_sn!='') echo "<br />(SN: $temp_sn)";
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