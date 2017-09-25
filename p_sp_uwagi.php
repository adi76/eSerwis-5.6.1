<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php'); 
?>
<body>
<?php 
$sql_e123 = "SELECT wp_typ_podzespolu, wp_sprzet_pocztowy_uwagi FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_id=$wpid) LIMIT 1";
$result123 = mysql_query($sql_e123, $conn) or die($k_b);
$newArray123 = mysql_fetch_array($result123);
$temp_uwagi123	= $newArray123['wp_sprzet_pocztowy_uwagi'];
$temp_typ123	= $newArray123['wp_typ_podzespolu'];

pageheader("Uwagi o podzespole <font color=white>".$temp_typ123."</font>");

startbuttonsarea("left");
echo "$temp_uwagi123";
endbuttonsarea();

startbuttonsarea("right");
addbuttons("zamknij");
endbuttonsarea();

?>
</body>
</html>