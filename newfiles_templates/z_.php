<?php include_once('header.php'); ?>
<body>
<?php include('body_start.php'); ?>
<?php
$sql="SELECT * FROM $dbname.serwis_piony ORDER BY pion_nazwa"; // WHERE belongs_to='$es_filia'";
$result = mysql_query($sql, $conn) or die(mysql_error());
$count_rows = mysql_num_rows($result);

if ($count_rows!=0) {
echo "<h4>Przegl¹danie bazy pionów</h4>";

echo "<div class=show>";

	if ($showall==0) {
		echo "<a class=paging href=$phpfile?showall=1&paget=$page>Poka¿ wszystko na jednej stronie</a>";
	} else {
		echo "<a class=paging href=$phpfile?showall=0&page=$paget>Dziel na strony</a>";	
	}

echo "</div>";

// paging
// ============================================================================================================
$totalrows = mysql_num_rows($result);

if ($showall==0) {
  $rps=$rowpersite;
} else $rps=10000;

if(empty($page)){ $page = 1; }
$limitvalue = $page * $rps - ($rps);

$sql="SELECT * FROM $dbname.serwis_piony ORDER BY pion_nazwa LIMIT $limitvalue, $rps";

$result = mysql_query($sql, $conn) or die(mysql_error());
// ============================================================================================================
// koniec - paging

echo "<table cellspacing=1 align=center>";
echo "<tr>";
echo "<th class=center>LP</th><th>Nazwa pionu</th>";

// ============================
// access control 
$accessLevels = array("9");
if(array_search($es_prawa, $accessLevels)>-1){
	echo "<th class=center>Opcje</th>"; 
} 
// access control koniec
// ============================

echo "</tr>";

$i = 0;
$j = $page*$rowpersite-$rowpersite+1;
while ($newArray = mysql_fetch_array($result)) {
	$temp_id  			= $newArray['pion_id'];
	$temp_nazwa			= $newArray['pion_nazwa'];
	
	tbl_tr_highlight($i);
	
	echo "<td width=30 class=center>$j</td>";
	$j+=1;
	echo "<td>$temp_nazwa</td>";

// ============================
// access control 
$accessLevels = array("9");
if(array_search($es_prawa, $accessLevels)>-1){
	echo "<td width=40 class=center><a title=' Usuñ pion $temp_nazwa z bazy '><input class=imgoption type=image src=img/delete.gif onclick=\"newWindow(400,105,'u_pion.php?id=$temp_id')\"></a></td>";
} 
// access control koniec
// ============================

	$i+=1;
}

echo "</table>";

// paging_end
include_once('paging_end.php');
// paging_end

} else echo "<h4>Baza pionów jest pusta</h4>";
listabaz($es_prawa);
startbuttonsarea("right");
// ============================
// access control 
$accessLevels = array("9");
if(array_search($es_prawa, $accessLevels)>-1){
addownlinkbutton("'Dodaj pion'","Button1","button","newWindow(360,130,'d_pion.php')");
}
addbuttons("start");

endbuttonsarea();

include('body_stop.php');
include('js/menu.js');

?>

</body>
</html>