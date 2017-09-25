<?php include_once('header.php'); ?>
<body>
<?php include('body_start.php'); ?>
<?php
$sql="SELECT * FROM $dbname.serwis_dostawcy ORDER BY dostawca_nazwa"; //WHERE belongs_to='$es_filia'";
$result = mysql_query($sql, $conn) or die(mysql_error());
$count_rows = mysql_num_rows($result);
if ($count_rows!=0) {
echo "<h4>Przegl¹danie bazy dostawców</h4>";
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

$sql="SELECT * FROM $dbname.serwis_dostawcy ORDER BY dostawca_nazwa LIMIT $limitvalue, $rps";

$result = mysql_query($sql, $conn) or die(mysql_error());
// ============================================================================================================
// koniec - paging

echo "<table cellspacing=1 align=center>";
echo "<tr>";
echo "<th>LP</th><th>Nazwa dostawcy</th>";

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
	$temp_id  			= $newArray['dostawca_id'];
	$temp_nazwa			= $newArray['dostawca_nazwa'];
	
	tbl_tr_highlight($i);
	
	echo "<td class=center width=30>$j</td>";
	$j+=1;
	echo "<td>$temp_nazwa</td>";

// ============================
// access control 
$accessLevels = array("9");
if(array_search($es_prawa, $accessLevels)>-1){
 
echo "<td class=center width=40><a title=' Usuñ dostawcê $temp_nazwa z bazy '><input class=imgoption type=image src=img/delete.gif onclick=\"newWindow(400,105,'u_dostawca.php?id=$temp_id')\"></a></td>";
} 
// access control koniec
// ============================

	$i+=1;
}

echo "</table>";

// paging_end
include_once('paging_end.php');
// paging_end

} else echo "<h4>Baza dostawców jest pusta</h4>";
listabaz($es_prawa);
startbuttonsarea("right");
// ============================
// access control 
$accessLevels = array("9");
if(array_search($es_prawa, $accessLevels)>-1){
addownlinkbutton("'Dodaj dostawcê'","Button1","button","newWindow(460,130,'d_dostawca.php')");
}
addbuttons("start");
endbuttonsarea();

include('body_stop.php');
include('js/menu.js');
?>
</body>
</html>