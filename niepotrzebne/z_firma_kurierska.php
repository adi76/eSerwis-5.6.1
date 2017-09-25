<?php include_once('header.php'); ?>
<body>
<?php include('body_start.php'); ?>
<?php	

$sql="SELECT * FROM $dbname.serwis_firmy_kurierskie";
$result = mysql_query($sql, $conn) or die(mysql_error());
$count_rows = mysql_num_rows($result);

if ($count_rows!=0) {
echo "<h4>Przegl¹danie bazy firm kurierskich</h4>";
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

$sql="SELECT * FROM $dbname.serwis_firmy_kurierskie ";

$sql=$sql."ORDER BY fk_nazwa LIMIT $limitvalue, $rps";

$result = mysql_query($sql, $conn) or die(mysql_error());
// ============================================================================================================
// koniec - paging

echo "<table cellspacing=1 align=center>";
echo "<tr>";
echo "<th class=center>LP</th><th>Nazwa firmy</th><th>Telefon</th><th>email</th><th>WWW</th>";
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
	$temp_id  			= $newArray['fk_id'];
	$temp_nazwa			= $newArray['fk_nazwa'];
	$temp_telefon		= $newArray['fk_telefon'];
	$temp_email			= $newArray['fk_email'];
	$temp_www			= $newArray['fk_www'];
	
	tbl_tr_highlight($i);
	
	echo "<td width=30 class=center>$j</td>";
	$j+=1;
	echo "<td>$temp_nazwa</td>";
	echo "<td>$temp_telefon</td>";
	echo "<td><a href=mailto:$temp_email>$temp_email</a></td>";
	echo "<td><a title=' Otwórz stronê w nowym oknie ' href=$temp_www target=_blank>$temp_www</a></td>";

// ============================
// access control 
$accessLevels = array("9");
if(array_search($es_prawa, $accessLevels)>-1){
	echo "<td class=center width=50>";
	echo "<a title=' Edytuj dane o firmie kurierskiej $temp_nazwa '><input class=imgoption type=image src=img/edit.gif onclick=\"newWindow(430,210,'e_firma_kurierska.php?select_id=$temp_id&all=1')\"></a>";
	echo "<a title=' Usuñ firmê kuriersk¹ $temp_nazwa z bazy '><input class=imgoption type=image src=img/delete.gif  onclick=\"newWindow(400,105,'u_firma_kurierska.php?select_id=$temp_id')\"></a>";
	echo "</td>";
} 
// access control koniec
// ============================

	$i+=1;
}

echo "</table>";

// paging_end
include_once('paging_end.php');
// paging_end

} else echo "<h2>Baza firm kurierskich jest pusta</h2>";
listabaz($es_prawa);
startbuttonsarea("right");
// ============================
// access control 
$accessLevels = array("9");
if(array_search($es_prawa, $accessLevels)>-1){
addownlinkbutton("'Dodaj firmê kuriersk¹'","Button1","button","newWindow(430,210,'d_firma_kurierska.php')");
}
addbuttons("start");
endbuttonsarea();

include('body_stop.php');
include('js/menu.js');
?>
</body>
</html>