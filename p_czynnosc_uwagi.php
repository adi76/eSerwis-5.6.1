<?php include_once('header.php'); ?>
<body>
<?php
$result1 = mysql_query("SELECT todo_uwagi,todo_opis FROM $dbname.serwis_komorka_todo WHERE todo_id=$id", $conn) or die($k_b);
list($muwagi,$mopis)=mysql_fetch_array($result1);
pageheader("Uwagi do wykonanej czynności");
startbuttonsarea("left");
echo "<b>Czynność:</b><br />$mopis";
hr();
endbuttonsarea();
startbuttonsarea("left");
echo "<b>Uwagi do wykonanej czynności:</b><br />$muwagi";
endbuttonsarea();
br();
startbuttonsarea("right");
addbuttons("zamknij");
endbuttonsarea();
?>
</body>
</html>