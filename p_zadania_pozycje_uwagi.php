<?php include_once('header.php'); ?>
<body>
<?php
$sql1 = "SELECT pozycja_uwagi,pozycja_komorka FROM $dbname.serwis_zadania_pozycje WHERE pozycja_id='$id'";
$result1 = mysql_query($sql1, $conn) or die($k_b);
$dane = mysql_fetch_array($result1);
$muwagi			= $dane['pozycja_uwagi'];
$temp_komorka 	= $dane['pozycja_komorka'];   

pageheader("Uwagi do wykonania zadania w UP/komórce:");
startbuttonsarea("center");
infoheader("<b>$temp_komorka</b>");
endbuttonsarea();

hr();
startbuttonsarea("left");
echo nl2br($muwagi);
endbuttonsarea();

startbuttonsarea("right");
addbuttons("zamknij");
endbuttonsarea();

?>
</body>
</html>