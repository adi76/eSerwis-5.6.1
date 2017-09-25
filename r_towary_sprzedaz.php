<?php include_once('header.php'); ?>
<body>
<?php include('body_start.php'); ?>

<?php
include('inc_encrypt.php');

$sql="SELECT * FROM $dbname.serwis_sprzedaz WHERE (belongs_to=$es_filia)"; // WHERE ((belongs_to=$es_filia) and (pozycja_status='1'))";
$result = mysql_query($sql, $conn) or die($k_b);
$count_rows = mysql_num_rows($result);

if ($count_rows!=0) {
pageheader("Przeglądanie historii sprzedaży");

startbuttonsarea("center");
if ($showall==0) {
	echo "<a class=nav_normal href=r_towary_sprzedaz.php?showall=1&paget=$page>Pokaż wszystko na jednej stronie</a>";
	} else {
	echo "<a class=nav_normal href=r_towary_sprzedaz.php?showall=0&page=$paget>Dziel na strony</a>";
	}
endbuttonsarea();

starttable();
echo "<tr>";
echo "<th width=30>LP</th><th>Nazwa towaru, SN<br />Dostawca, nr faktury, data wystawienia</th><th width=60>Cena<br />netto</th><th>Data sprzedaży<br />Sprzedano dla</th><th>Numer umowy</th><th>Opcje</th>";
echo "</tr>";
$sql="SELECT * FROM $dbname.serwis_sprzedaz WHERE (belongs_to=$es_filia)";

$result = mysql_query($sql, $conn) or die($k_b);

// paging
// -
$totalrows = mysql_num_rows($result);

if(empty($page)){ $page = 1; }

if ($showall==0) {
  $rps=$rowpersite;
} else $rps=10000;

$limitvalue = $page * $rps - ($rps);

if ($printpreview==0) {
	$sql=$sql." LIMIT $limitvalue, $rps";
}

$result = mysql_query($sql, $conn) or die($k_b);
// -
// koniec - paging

$i = 0;
$j = 1;
	
while ($newArray = mysql_fetch_array($result)) {
	$temp_id  			= $newArray['sprzedaz_id'];
	$temp_poz_nazwa		= $newArray['sprzedaz_pozycja_nazwa'];
	$temp_poz_sn		= $newArray['sprzedaz_pozycja_sn'];
	$temp_poz_cena_cr	= $newArray['sprzedaz_pozycja_cenanetto'];
	$temp_data			= $newArray['sprzedaz_data'];
	$temp_umowa			= $newArray['sprzedaz_umowa_nazwa'];
	$temp_pion			= $newArray['sprzedaz_pion_nazwa'];
	$temp_up			= $newArray['sprzedaz_up_nazwa'];
	$temp_uwagi			= $newArray['sprzedaz_uwagi'];
	$temp_fakt_id		= $newArray['sprzedaz_faktura_id'];

	$temp_poz_cena		= decrypt_md5($temp_poz_cena_cr,$key);
	
	tbl_tr_highlight($i);
	
	echo "<td width=30 class=center>$j</td>";
	$sql1="SELECT * FROM $dbname.serwis_faktury WHERE ((belongs_to=$es_filia) and (faktura_id='$temp_fakt_id'))";

	$result1 = mysql_query($sql1, $conn) or die($k_b);
	
	while ($newArray4 = mysql_fetch_array($result1)) {
		$temp_id4  			= $newArray4['faktura_id'];
		$temp_numer4		= $newArray4['faktura_numer'];
		$temp_data4			= $newArray4['faktura_data'];
		$temp_dostawca4		= $newArray4['faktura_dostawca'];	
	}
	
	echo "<td>$temp_poz_nazwa, $temp_poz_sn<br /><a class=opt3 onclick=\"newWindow_r(800,600,'p_faktura_pozycje.php?id=$temp_id4')\">&nbsp;$temp_dostawca4, $temp_numer4, $temp_data4&nbsp;</a></td>";
	echo "<td class=right>"; 
	
	if ($temp_poz_cena!=0) { echo "$temp_poz_cena zł"; }
	
	echo "</td>";

	echo "<td>$temp_data<br />";
	
	if ($temp_pion!="") { echo "$temp_pion&nbsp;"; } 
	
	echo "$temp_up</td>";

	echo "<td>$temp_umowa</td>";
	$j+=1;
	
	$r=substr($temp_data,0,4);
	$m=substr($temp_data,5,2);
	$d=substr($temp_data,8,2);

	echo "<td width=40 class=center><a title=' Generuj protokół dla : $temp_poz_nazwa '><input type=image class=imgoption src=img//print.gif  onclick=\"newWindow_r(700,595,'utworz_protokol.php?c_8=on&dzien=$d&miesiac=$m&rok=$r&up=".urlencode($temp_up)."&nazwa_urzadzenia=".urlencode($temp_poz_nazwa)."&sn_urzadzenia=".urlencode($temp_poz_sn)."&readonly=1')\"></a>";
		
	echo "</td>";

	$i+=1;
	echo "</tr>";
}

endtable();

// paging_end
include_once('paging_end.php');
// paging_end				

} else errorheader("Historia sprzedaży jest pusta");

startbuttonsarea("right");
addbuttons("start");
endbuttonsarea();				

include('body_stop.php');
?>
</body>
</html>