<?php include_once('header.php'); ?>
<body>
<?php 
$result1 = mysql_query("SELECT naprawa_uwagi, naprawa_nazwa, naprawa_model, naprawa_sn, naprawa_ni FROM $dbname.serwis_naprawa WHERE naprawa_id=$id LIMIT 1", $conn) or die($k_b);
list($muwagi,$temp_nazwa,$temp_model,$temp_sn,$temp_ni)=mysql_fetch_array($result1);
pageheader("Uwagi do naprawy");
startbuttonsarea("center");
if ($temp_ni=='') $temp_ni='-';
echo "Typ sprzętu: <b>$temp_nazwa <b>$temp_model</b></b><br />SN: <b>$temp_sn</b><br />NI: <b>$temp_ni</b>";
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