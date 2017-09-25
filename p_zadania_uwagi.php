<?php include_once('header.php'); ?>
<body>
<?php
$result1 = mysql_query("SELECT zadanie_opis,zadanie_uwagi FROM $dbname.serwis_zadania WHERE zadanie_id='$id'", $conn) or die($k_b);
list($mopis,$muwagi)=mysql_fetch_array($result1);
pageheader("Uwagi do zadania");
startbuttonsarea("center");
echo "$mopis";
endbuttonsarea();
hr();
startbuttonsarea("left");
echo "$muwagi";
endbuttonsarea();
br();
startbuttonsarea("right");
addbuttons("zamknij");
endbuttonsarea();
?>
</body>
</html>