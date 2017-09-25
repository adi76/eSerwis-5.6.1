<?php include_once('header.php'); ?>
<body>
<?php
$sql_e = "SELECT przesuniecie_id, przesuniecie_uwagi FROM $dbname.serwis_ewidencja_przesuniecia WHERE (przesuniecie_id=$id) LIMIT 1";
$result = mysql_query($sql_e, $conn) or die($k_b);
$newArray = mysql_fetch_array($result);
$temp_id  	= $newArray['przesuniecie_id'];
$temp_uwagi	= $newArray['przesuniecie_uwagi'];

pageheader("Uwagi do przesunięcia");

startbuttonsarea("left");
echo "$temp_uwagi";
endbuttonsarea();

startbuttonsarea("right");
addbuttons("zamknij");
endbuttonsarea();

?>
</body>
</html>