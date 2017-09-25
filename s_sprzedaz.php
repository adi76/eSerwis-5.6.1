<?php

?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
	
echo "<table class=maxwidth cellspacing=1 align=center>";
echo "<tr>";
echo "<th class=center>LP</th><th>Nazwa towaru</th><th>SN</th>";

// -
// access control 
$accessLevels = array("1", "9");
if(array_search($es_prawa, $accessLevels)>-1){
echo "<th class=right>Cena netto<br />z faktury</th>";
}
// access control koniec
// -

// -
// access control 
$accessLevels = array("9");
if(array_search($es_prawa, $accessLevels)>-1){
echo "<th class=right>Cena netto<br />odsprzedaży</th>";
}
// access control koniec
// -

// -
// access control 
$accessLevels = array("1", "9");
if(array_search($es_prawa, $accessLevels)>-1){

echo "<th>Nr zamówienia</th><th>Dostawca, nr faktury, data wystawienia</th>";
}
// access control koniec
// -

echo "<th class=center>Opcje</th>";

echo "</tr>";

$sql="SELECT * FROM $dbname.serwis_faktura_szcz WHERE ((belongs_to=$es_filia) and ((pozycja_status='0') or (pozycja_status='5')) AND ((pozycja_numer LIKE '%$search%') OR (pozycja_sn LIKE '%$search%') OR (pozycja_nazwa LIKE '%$search%') or (pozycja_nr_faktury LIKE '%$search'))) ORDER BY pozycja_typ ASC";

$result = mysql_query($sql, $conn) or die($k_b);

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
	
$temp_cenanetto = decrypt_md5($temp_cenanetto_cr,$key);
$temp_cenanettoodsp = decrypt_md5($temp_cenanettoodsp_cr,$key);
 
tbl_tr_highlight($i);

$sql1="SELECT * FROM $dbname.serwis_faktury WHERE ((belongs_to=$es_filia) and (faktura_id='$temp_nrfaktury'))";
$result1 = mysql_query($sql1, $conn) or die($k_b);
while ($newArray4 = mysql_fetch_array($result1)) {
	$temp_id4  			= $newArray4['faktura_id'];
	$temp_numer4		= $newArray4['faktura_numer'];
	$temp_data4			= $newArray4['faktura_data'];
	$temp_dostawca4		= $newArray4['faktura_dostawca'];	
	$temp_nz			= $newArray4['faktura_nr_zamowienia'];	
	
}

echo "<td width=30 class=center><a href=# class=normalfont title='$temp_id'>$j</a></td>";
echo "<td>".highlight($temp_nazwa, $search)."</td>";
echo "<td>".highlight($temp_sn, $search)."</td>";
// -
// access control 


$accessLevels = array("1", "9");
if(array_search($es_prawa, $accessLevels)>-1){
echo "<td class=right>".correct_currency($temp_cenanetto)." zł</td>";

}
// access control koniec
// -

// -
// access control 
$accessLevels = array("9");
if(array_search($es_prawa, $accessLevels)>-1){
echo "<td class=right>".correct_currency($temp_cenanettoodsp)." zł</td>";
}
// access control koniec
// -

// -
// access control 

$accessLevels = array("1", "9");
if(array_search($es_prawa, $accessLevels)>-1){
echo "<td class=center width=50>$temp_nz</td>";
	$fakt = $temp_dostawca4.' '.$temp_numer4.', '.$temp_data4;
	echo "<td><a title=' Pokaż szczegółowe informacje o fakturze nr $temp_numer4 ' class=opt3 onclick=\"newWindow_r(800,600,'p_faktura_pozycje.php?id=$temp_id4')\">".highlight($fakt, $search)."</a></td>";
}

// access control koniec
// -
$j+=1;

$d= Date('d');
$m= Date('m');
$r= Date('Y');

echo "<td class=center>";

$jest_zestawem = 0;
if ($temp_status==5) {
	$sql_nr_zestawu = "SELECT zestawpozycja_zestaw_id FROM $dbname.serwis_zestaw_szcz WHERE zestawpozycja_fszcz_id=$temp_id LIMIT 1";
	list($nr_zestawu)=mysql_fetch_array(mysql_query($sql_nr_zestawu,$conn));
	$sql_zestaw_name = "SELECT zestaw_opis FROM $dbname.serwis_zestawy WHERE (zestaw_id=$nr_zestawu) LIMIT 1";
	list($zestaw_name)=mysql_fetch_array(mysql_query($sql_zestaw_name, $conn));
}

if ($temp_status==0) {
//	echo "<a title=' Sprzedaj towar : $temp_nazwa o numerze seryjnym : $temp_sn '><input class=imgoption type=image src=img/sell.gif  onclick=\"newWindow(790,340,'z_towary_obrot.php?id=$temp_id&f=$temp_id4')\"></a>";
		if ($allow_sell==1) {
			
			echo "<a title='Pokaż towar: $temp_nazwa o numerze seryjnym : $temp_sn w magazynie'><input class=imgoption type=image src=img/show_towar.gif  onclick=\"self.location.href='p_towary_dostepne.php?id=$temp_id'; \"></a>";
			
			//echo "<a title=' Sprzedaj towar : $temp_nazwa o numerze seryjnym : $temp_sn '><input class=imgoption type=image src=img/sell.gif  onclick=\"newWindow_r(700,595,'z_towary_obrot.php?id=$temp_id&f=$temp_id4&obzp=1')\"></a>";
			//$wc = $temp_nazwa.",".$temp_sn;	
	
			$accessLevels = array("9");
			if(array_search($es_prawa, $accessLevels)>-1){
			//		echo "<a title=' Generuj protokół '><input class=imgoption type=image src=img//print.gif  onclick=\"newWindow_r(700,595,'utworz_protokol.php?dzien=$d&miesiac=$m&rok=$r&wykonane_czynnosci=".urlencode($wc)."&choosefromewid=1')\"></a>";
			}
		}
	
} else {
	//echo "<a title=' Element zestawu: $zestaw_name '><input class=imgoption type=image src=img/basket.gif onclick=\"newWindow(800,600,'p_zestaw_pozycje.php?id=$nr_zestawu&sold=0&showall=1&paget=1')\"></a>";

	echo "<a title='Pokaż towar: $temp_nazwa o numerze seryjnym : $temp_sn w magazynie'><input class=imgoption type=image src=img/show_towar.gif  onclick=\"self.location.href='p_towary_dostepne.php?id=$temp_id'; \"></a>";
				
	if ($allow_sell==1) {
	//	echo "<a title=' Sprzedaj zestaw: $zestaw_name '><input class=imgoption type=image src=img/sell_zestaw.gif  onclick=\"newWindow_r(700,595,'g_zestaw_obrot.php?id=$nr_zestawu')\"></a>";
	}	
	$jest_zestawem = 1;
	//echo "<a title=' Pokaż pozycje zestawu : $temp_opis '><input class=imgoption type=image src=img/search.gif  onclick=\"newWindow_r(800,600,'p_zestaw_pozycje.php?id=$temp_id&sold=$temp_status&showall=1&paget=1')\"></a>";
}

$accessLevels = array("9");
if(array_search($es_prawa, $accessLevels)>-1){

	$sql_c = "SELECT faktura_id FROM $dbname.serwis_faktury WHERE ((faktura_id=$temp_nrfaktury) and (belongs_to=$es_filia))";
	$result_c = mysql_query($sql_c, $conn) or die($k_b);
	$count_rows = mysql_num_rows($result_c);
	
	if ($count_rows>0) {
		echo "<a title=' Pokaż pozycje na fakturze nr $temp_numer4 '><input class=imgoption type=image src=img/search.gif  onclick=\"newWindow_r(800,600,'p_faktura_pozycje.php?id=$temp_id4');\"></a>";
	} else 
		echo "<a title=' Towar został przesunięty z innego magazynu '><input class=imgoption type=image src=img/towar_p.gif></a>";
}

if ($jest_zestawem==1) {
	echo "<br /><a title=' Nazwa zestawu: $zestaw_name '>[$zestaw_name]</a>";	
}
	
echo "</td>";

$i+=1;
echo "</tr>";
}

endtable();		
?>

<script>HideWaitingMessage();</script>

<?php 
?>