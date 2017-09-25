<?php 
include_once('header.php');
include_once('cfg_helpdesk.php');
?>
<body>
<?php
$result1 = mysql_query("SELECT projekt_opis,projekt_uwagi FROM $dbname_hd.hd_projekty WHERE projekt_id='$id'", $conn_hd) or die($k_b);
list($mopis,$muwagi)=mysql_fetch_array($result1);
pageheader("Uwagi do projektu");
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