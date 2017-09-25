<?php include_once('header.php'); ?>
<body>
<?php 
$sql1 = "SELECT historia_magid, historia_komentarz FROM $dbname.serwis_historia WHERE historia_id=$id LIMIT 1";
$result1 = mysql_query($sql1, $conn) or die($k_b);
$dane = mysql_fetch_array($result1);
$hkom	= $dane['historia_komentarz']; 
$hmagid = $dane['historia_magid'];

list($mnazwa, $mmodel, $msn)=mysql_fetch_array(mysql_query("SELECT magazyn_model, magazyn_nazwa, magazyn_sn FROM $dbname.serwis_magazyn WHERE magazyn_id=$hmagid LIMIT 1",$conn));
pageheader("Uwagi o sprzęcie");
startbuttonsarea("center");
if ($msn!='') echo "$mmodel $mnazwa (SN: $msn)"; else echo "$mmodel $mnazwa";
hr();	
startbuttonsarea("left");
echo urldecode($hkom);
endbuttonsarea();
startbuttonsarea("right");
addbuttons("zamknij");
endbuttonsarea();
?>
</body>
</html>