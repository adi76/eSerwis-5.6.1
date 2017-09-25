<?php include_once('header.php'); ?>
<body>
<?php
$sql_e = "SELECT remont_id, remont_uwagi FROM $dbname.serwis_ewidencja_remonty WHERE (remont_id=$id) LIMIT 1";
$result = mysql_query($sql_e, $conn) or die($k_b);
$newArray = mysql_fetch_array($result);
$temp_id  	= $newArray['remont_id'];
$temp_uwagi	= $newArray['remont_uwagi'];

pageheader("Uwagi do remontu");

startbuttonsarea("left");
echo "$temp_uwagi";
endbuttonsarea();

startbuttonsarea("right");
addbuttons("zamknij");
endbuttonsarea();

?>
</body>
</html>