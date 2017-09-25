<?php include_once('header.php'); ?>
<body>
<?php
$sql_e = "SELECT usuniecie_id, usuniecie_uwagi FROM $dbname.serwis_ewidencja_usuniecia WHERE (usuniecie_id=$id) LIMIT 1";
$result = mysql_query($sql_e, $conn) or die($k_b);
$newArray = mysql_fetch_array($result);
$temp_id  	= $newArray['usuniecie_id'];
$temp_uwagi	= $newArray['usuniecie_uwagi'];

pageheader("Uwagi do usunięcia sprzętu");

startbuttonsarea("left");
echo "$temp_uwagi";
endbuttonsarea();

startbuttonsarea("right");
addbuttons("zamknij");
endbuttonsarea();

?>
</body>
</html>