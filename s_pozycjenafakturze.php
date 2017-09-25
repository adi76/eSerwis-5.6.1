<?php

?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
	
starttable();
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

$sql="SELECT * FROM $dbname.serwis_faktura_szcz WHERE (belongs_to=$es_filia) AND ((pozycja_numer LIKE '%$search%') OR (pozycja_sn LIKE '%$search%') OR (pozycja_nazwa LIKE '%$search%') or (pozycja_nr_faktury LIKE '%$search'))";
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

echo "<td width=30 class=center>$j</td>";
echo "<td>".highlight($temp_nazwa, $search)."</td>";
echo "<td>".highlight($temp_sn, $search)."</td>";
// -
// access control 


$accessLevels = array("1", "9");
if(array_search($es_prawa, $accessLevels)>-1){
echo "<td class=right>$temp_cenanetto zł</td>";

}
// access control koniec
// -

// -
// access control 
$accessLevels = array("9");
if(array_search($es_prawa, $accessLevels)>-1){
echo "<td class=right>$temp_cenanettoodsp zł</td>";
}
// access control koniec
// -

// -
// access control 

$accessLevels = array("1", "9");
if(array_search($es_prawa, $accessLevels)>-1){
echo "<td class=center>$temp_nz</td>";
echo "<td><a onclick=\"newWindow_r(800,600,'p_faktura_pozycje.php?id=$temp_id4')\">$temp_dostawca4, $temp_numer4, $temp_data4</a></td>";
}
echo "</a>";	
// access control koniec
// -
$j+=1;

$d= Date('d');
$m= Date('m');
$r= Date('Y');

if ($temp_status==0) {
	echo "<a title='Pokaż towar: $temp_nazwa o numerze seryjnym : $temp_sn w magazynie'><input class=imgoption type=image src=img/show_towar.gif  onclick=\"self.location.href='p_towary_dostepne.php?id=$temp_id'; \"></a>";
//echo "<td class=center><a title=' Sprzedaj towar : $temp_nazwa o numerze seryjnym : $temp_sn '><input class=imgoption type=image src=img/sell.gif onclick=\"newWindow(790,410,'z_towary_obrot.php?id=$temp_id&f=$temp_id4')\"></a>";

$accessLevels = array("9");
if(array_search($es_prawa, $accessLevels)>-1){
//	echo "<a title=' Generuj protokół '><input class=imgoption type=image src=img//print.gif  onclick=\"newWindow_r(700,595,'utworz_protokol.php?dzien=$d&miesiac=$m&rok=$r&nazwa_urzadzenia=".urlencode($temp_nazwa)."&sn_urzadzenia=".urlencode($temp_sn)."')\"></a>";
}

echo "</td>";
} else {
	echo "<td class=center></td>";
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