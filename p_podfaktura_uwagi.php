<?php include_once('header.php'); ?>
<body>
<?php
$sql1 = "SELECT pf_uwagi FROM $dbname.serwis_podfaktury WHERE pf_id='$id'";
$result1 = mysql_query($sql1, $conn) or die($k_b);
$dane 	= mysql_fetch_array($result1);
$muwagi	= $dane['pf_uwagi'];

pageheader("Uwagi do podfaktury");
startbuttonsarea("center");
echo "Podfaktura nr : $nr";
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