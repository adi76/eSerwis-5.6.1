<?php include_once('header.php'); ?>
<body>
<?php
include('inc_encrypt.php');

$sql="SELECT * FROM $dbname.serwis_faktura_szcz WHERE ((belongs_to=$es_filia) and (pozycja_status='0'))";

if ($wybor!='') $sql.=" and (pozycja_typ='$wybor') ";

$result = mysql_query($sql, $conn) or die(mysql_error());
$count_rows = mysql_num_rows($result);

if ($count_rows!=0) {
nowalinia();
echo "<h4>Przegl¹danie towarów dostêpnych na stanie</h4>";

echo "<div class=show>";
echo "<form name=towary>";
echo "Wybierz typ towaru: ";
$sql2="SELECT * FROM $dbname.serwis_slownik_rola ORDER BY rola_nazwa";
$result2 = mysql_query($sql2, $conn) or die(mysql_error());
echo "<select name=trodzaj onChange='document.location.href=document.towary.trodzaj.options[document.towary.trodzaj.selectedIndex].value'>\n";
echo "<option value='main.php?action=zsns&wybor=$temp_nazwa5'>Wszystko";
	
while ($newArray2 = mysql_fetch_array($result2)) {
	$temp_id5  	= $newArray2['rola_id'];
	$temp_nazwa5	= $newArray2['rola_nazwa'];
	echo "<option ";
	if ($wybor==$temp_nazwa5) echo "SELECTED ";
	echo "VALUE='main.php?action=zsns&wybor=$temp_nazwa5'>$temp_nazwa5</option>\n";
}	
echo "</select>\n";
echo "</form>";

echo "</div>";

echo "<table cellspacing=1 align=center>";
echo "<tr>";
echo "<th class=center>LP</th><th>Nazwa towaru</th><th>SN</th>";

// ============================
// access control 
$accessLevels = array("1", "9");
if(array_search($es_prawa, $accessLevels)>-1){
echo "<th class=right>Cena netto<br />z faktury</th>";
}
// access control koniec
// ============================

// ============================
// access control 
$accessLevels = array("9");
if(array_search($es_prawa, $accessLevels)>-1){
	echo "<th class=right>Cena netto<br />+koszty</th>";
}
// access control koniec
// ============================

// ============================
// access control 
$accessLevels = array("1", "9");
if(array_search($es_prawa, $accessLevels)>-1){

	echo "<th width=100>Nr zamówienia</th><th>Dostawca, nr faktury, data wystawienia</th>";
}
// access control koniec
// ============================

echo "<th class=center width=40>Uwagi</th><th class=center>Opcje</th>";

echo "</tr>";
$sql="SELECT * FROM $dbname.serwis_faktura_szcz,serwis_faktury WHERE ((serwis_faktura_szcz.belongs_to=$es_filia) and (serwis_faktura_szcz.pozycja_status='0') and (serwis_faktury.faktura_id=serwis_faktura_szcz.pozycja_nr_faktury))";

if ($wybor!='') $sql.=" and (pozycja_typ='$wybor') ";

$sql.=" ORDER BY pozycja_typ ASC, serwis_faktury.faktura_data ASC";
$result = mysql_query($sql, $conn) or die(mysql_error());

$i = 0;
$j = 1;
	
while ($newArray = mysql_fetch_array($result)) {
	$temp_id  			= $newArray['pozycja_id'];
	$temp_nrfaktury		= $newArray['pozycja_nr_faktury'];
	$temp_numer			= $newArray['pozycja_numer'];
	$temp_nazwa			= $newArray['pozycja_nazwa'];
	$temp_ilosc			= $newArray['pozycja_ilosc'];
	$temp_sn			= $newArray['pozycja_sn'];
	$temp_cenanetto_cr	= $newArray['pozycja_cena_netto'];
	$temp_status		= $newArray['pozycja_status'];
	$temp_cenanettoodsp_cr	= $newArray['pozycja_cena_netto_odsprzedazy'];
	$temp_typ			= $newArray['pozycja_typ'];
	$temp_uwagi			= $newArray['pozycja_uwagi'];
	
	$temp_cenanetto = decrypt_md5($temp_cenanetto_cr,$key);
	$temp_cenanettoodsp = decrypt_md5($temp_cenanettoodsp_cr,$key);
	
	tbl_tr_highlight($i);
	
	$sql1="SELECT * FROM $dbname.serwis_faktury WHERE (faktura_id='$temp_nrfaktury')";

	$result1 = mysql_query($sql1, $conn) or die(mysql_error());
	
	while ($newArray4 = mysql_fetch_array($result1)) {
		$temp_id4  			= $newArray4['faktura_id'];
		$temp_numer4		= $newArray4['faktura_numer'];
		$temp_data4			= $newArray4['faktura_data'];
		$temp_dostawca4		= $newArray4['faktura_dostawca'];	
		$temp_nz			= $newArray4['faktura_nr_zamowienia'];	
		
	}

	echo "<td width=30 class=center>$j</td>";
	echo "<td class=wrap>$temp_nazwa";
	
	if ($pokazpoczatekuwag==1) pokaz_uwagi($temp_uwagi,$iloscznakowuwag,"newWindow(480,265,'p_towar_uwagi.php?id=$temp_id')");
	
	echo "</td>";
	echo "<td>$temp_sn</td>";
// ============================
// access control 


$accessLevels = array("1", "9");
if(array_search($es_prawa, $accessLevels)>-1){
	echo "<td class=right>$temp_cenanetto z³</td>";

}
// access control koniec
// ============================
	
// ============================
// access control 
$accessLevels = array("9");
if(array_search($es_prawa, $accessLevels)>-1){
	echo "<td class=right>$temp_cenanettoodsp z³</td>";
}
// access control koniec
// ============================
	
// ============================
// access control 

$accessLevels = array("1", "9");
if(array_search($es_prawa, $accessLevels)>-1){
	echo "<td class=center>$temp_nz</td>";

	echo "<td>$temp_dostawca4, $temp_numer4, $temp_data4&nbsp;";
	echo "</td>";
}
// access control koniec
// ============================

if ($temp_uwagi!='') {
  echo "<td width=40 class=center><a title=' $temp_uwagi '><input class=imgoption type=image src=img/comment.gif onclick=\"newWindow(480,265,'p_towar_uwagi.php?id=$temp_id')\"></a><a title=' Edytuj uwagi o towarze '><input class=imgoption type=image src=img/edit_comment.gif onclick=\"newWindow(480,245,'e_towar_uwagi.php?id=$temp_id')\"></a></td>";
} else echo "<td class=center width=40><a title=' Dodaj uwagi o towarze'><input class=imgoption type=image src=img/add_comment.gif  onclick=\"newWindow(480,245,'e_towar_uwagi.php?id=$temp_id')\"></a></td>";

	$j+=1;
	
	$d= Date('d');
	$m= Date('m');
	$r= Date('Y');
	
	echo "<td width=70 class=center>";
	
// ============================
// access control 
$accessLevels = array("9");
if(array_search($es_prawa, $accessLevels)>-1){

	$sql_c = "SELECT faktura_id FROM $dbname.serwis_faktury WHERE ((faktura_id=$temp_nrfaktury) and (belongs_to=$es_filia))";
	$result_c = mysql_query($sql_c, $conn);
	$count_rows = mysql_num_rows($result_c);
	
	if ($count_rows>0) {
	echo "<a title=' Poka¿ pozycje na fakturze nr $temp_numer4 '><input class=imgoption type=image src=img/search.gif  onclick=\"newWindow_r(800,600,'p_faktura_pozycje.php?id=$temp_id4')\"></a>";
	} else 
		echo "<a title=' Towar zosta³ przesuniêty z innego magazynu '><input class=imgoption type=image src=img/towar_p.gif></a>";
}
// access control koniec
// ============================
	
	echo "<a title=' Sprzedaj towar : $temp_nazwa o numerze seryjnym : $temp_sn '><input class=imgoption type=image src=img/sell.gif  onclick=\"newWindow(790,400,'z_towary_obrot.php?id=$temp_id&f=$temp_id4')\"></a>";
	$wc = $temp_nazwa.",".$temp_sn;
	echo "<a title=' Generuj protokó³ '><input class=imgoption type=image src=img//print.gif  onclick=\"newWindow_r(700,595,'utworz_protokol.php?dzien=$d&miesiac=$m&rok=$r&wykonane_czynnosci=$wc&choosefromewid=1')\"></a>";
	echo "</td>";

	$i+=1;
	echo "</tr>";
}

echo "</table>";
nowalinia();

} else 
	{
	
		if ($wybor!='wszystko') {
			echo "<br /><h2>Brak tego typu towaru do odsprzeda¿y ($wybor)</h2>";
			?>
			<meta http-equiv="REFRESH" content="2;url=<?php echo "$linkdostrony";?>main.php?action=zsns">
			<?php			
		} else {
			echo "<br /><h2>Brak towarów do odsprzeda¿y na stanie</h2>";
			?>
			<meta http-equiv="REFRESH" content="2;url=<?php echo "$linkdostrony";?>main.php">
			<?php
		}
	}
?>
