<?php include_once('header.php'); ?>
<body>
<?php
$sql1 = "SELECT faktura_uwagi FROM $dbname.serwis_faktury WHERE faktura_id='$id'";
$result1 = mysql_query($sql1, $conn) or die($k_b);
$dane 	= mysql_fetch_array($result1);
$muwagi	= $dane['faktura_uwagi'];
pageheader("Uwagi do faktury");

startbuttonsarea("center");
echo "Faktura nr $nr";
endbuttonsarea();
hr();
startbuttonsarea("left");
echo "$muwagi";
endbuttonsarea();

startbuttonsarea("right");
addbuttons("zamknij");
endbuttonsarea();

?>
</body>
</html>