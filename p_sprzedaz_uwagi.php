<?php include_once('header.php'); ?>
<body>
<?php 
$sql_e = "SELECT sprzedaz_uwagi, sprzedaz_id,sprzedaz_pozycja_nazwa,sprzedaz_pozycja_sn FROM $dbname.serwis_sprzedaz WHERE (sprzedaz_id=$id) LIMIT 1";
$result = mysql_query($sql_e, $conn) or die($k_b);
$newArray = mysql_fetch_array($result);
$temp_id  	= $newArray['sprzedaz_id'];
$temp_nazwa = $newArray['sprzedaz_pozycja_nazwa'];
$temp_sn	= $newArray['sprzedaz_pozycja_sn'];
$temp_uwagi	= $newArray['sprzedaz_uwagi'];

pageheader("Uwagi o towarze");
startbuttonsarea("center");
echo "$temp_nazwa (SN: $temp_sn)";
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