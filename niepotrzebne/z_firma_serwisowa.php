<?php include_once('header.php'); ?>
<body>
<?php include('body_start.php'); ?>
<?php
$sql="SELECT * FROM $dbname.serwis_firmy_serwisowe ORDER BY fs_nazwa";

$result = mysql_query($sql, $conn) or die(mysql_error());
$count_rows = mysql_num_rows($result);
if ($count_rows!=0) {
echo "<h4>Przegl¹danie bazy firm serwisowych</h4>";
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
$sql="SELECT * FROM $dbname.serwis_firmy_serwisowe ";
$sql=$sql."ORDER BY fs_nazwa LIMIT $limitvalue, $rps";
$result = mysql_query($sql, $conn) or die(mysql_error());
// ============================================================================================================
// koniec - paging

echo "<table cellspacing=1 align=center>";
echo "<tr>";
echo "<th class=center>LP</th><th>Nazwa firmy</th><th>Adres</th><th>Telefon<br />Fax</th><th>email<br />WWW</th><th>Serwisowany sprzêt</th>";
echo "<th class=center>Opcje</th>"; 

echo "</tr>";

$i = 0;
$j = $page*$rowpersite-$rowpersite+1;

while ($newArray = mysql_fetch_array($result)) {
	$temp_id  			= $newArray['fs_id'];
	$temp_nazwa			= $newArray['fs_nazwa'];
	$temp_adres			= $newArray['fs_adres'];
	$temp_telefon		= $newArray['fs_telefon'];
	$temp_fax			= $newArray['fs_fax'];
	$temp_email			= $newArray['fs_email'];
	$temp_www			= $newArray['fs_www'];
	$temp_belongs_to	= $newArray['belongs_to'];
	$temp_sp			= $newArray['fs_sprzet'];

	tbl_tr_highlight($i);
	
	echo "<td class=center width=30>$j</td>";
	$j+=1;
	echo "<td>$temp_nazwa</td>";
	echo "<td>$temp_adres</td>";
	echo "<td>$temp_telefon<br />$temp_fax</td>";
	echo "<td>$temp_email<br />$temp_www</td>";
	echo "<td class=wrap>$temp_sp</td>";
	echo "<td class=center width=65>";
// ============================
// access control 
$accessLevels = array("9");
if(array_search($es_prawa, $accessLevels)>-1){
	echo "<a title=' Edytuj dane o firmie serwisowej $temp_nazwa '><input class=imgoption type=image src=img/edit.gif  onclick=\"newWindow(460,290,'e_firma_serwisowa.php?select_id=$temp_id&all=1')\"></a>";	
	echo "<a title=' Usuñ firmê serwisow¹ $temp_nazwa z bazy '><input class=imgoption type=image src=img/delete.gif  
onclick=\"newWindow(400,105,'u_firma_serwisowa.php?select_id=$temp_id')\"></a>";
} 
echo "<a title=' Drukuj informacje o firmie $temp_nazwa ' href=#><input class=imgoption type=image src=img/print.gif  
onclick=\"newWindow_r(800,600,'p_firma_serwisowa.php?fsid=$temp_id')\"></a>";
// access control koniec
// ============================
	echo "</td>";
	$i+=1;
}

echo "</table>";

// paging_end
include_once('paging_end.php');
// paging_end

} else echo "<h2>Baza firm serwisowych jest pusta</h2>";
listabaz($es_prawa);
startbuttonsarea("right");
// ============================
// access control 
$accessLevels = array("9");
if(array_search($es_prawa, $accessLevels)>-1){
addownlinkbutton("'Dodaj firmê serwisow¹'","Button1","button","newWindow(460,290,'d_firma_serwisowa.php')");
}
addbuttons("start");
endbuttonsarea();

include('body_stop.php');
include('js/menu.js');
?>
</body>
</html>