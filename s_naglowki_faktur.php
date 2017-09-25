<?php

?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
	
starttable();
echo "<tr>";
echo "<th class=center>LP</th><th>Numer faktury<br />Nr zamówienia</th><th>Data<br />wystawienia</th><th>Dostawca<br />Realizacja zakupu</th><th>Poz. na fakt.<br />Ilość podfaktur</th>";

// -
// access control 
$accessLevels = array("9");
if(array_search($es_prawa, $accessLevels)>-1){
echo "<th class=right>Kwota na fakturze<br />Koszty podfaktur</th>";
}
// access control koniec

echo "<th class=center>Uwagi</th>";
echo "<th class=center>Opcje</th>";
echo "</tr>";

$result = mysql_query($sql, $conn) or die($k_b);	

$i=0;
$j = 1;

while ($newArray = mysql_fetch_array($result)) {		
	
	$temp_id  			= $newArray['faktura_id'];
	$temp_numer			= $newArray['faktura_numer'];
	$temp_data			= $newArray['faktura_data'];
	$temp_dostawca		= $newArray['faktura_dostawca'];
	$temp_koszty		= $newArray['faktura_koszty_dodatkowe'];
	$temp_osoba			= $newArray['faktura_osoba'];
	$temp_datawpisu		= $newArray['faktura_datawpisu'];
	$temp_status		= $newArray['faktura_status'];
	$temp_nr_zamowienia = $newArray['faktura_nr_zamowienia'];
	$temp_realizacja	= $newArray['faktura_realizacjazakupu'];
	$temp_uwagi			= $newArray['faktura_uwagi'];

tbl_tr_highlight($i);

$i+=1;

//echo "<td width=30 class=center>$j</td>";
td("30;c;<a class=normalfont href=# title=' $temp_id '>".$j."</a>");
echo "<td>".highlight($temp_numer, $search)."<br />".highlight($temp_nr_zamowienia, $search)."";
if ($pokazpoczatekuwag==1) pokaz_uwagi($temp_uwagi,$iloscznakowuwag,"newWindow(480,265,'p_faktura_uwagi.php?id=$temp_id&nr=$temp_numer')");
echo "</td>";
echo "<td>".highlight($temp_data, $search)."</td>";
echo "<td>".highlight($temp_dostawca, $search)."<br />$temp_realizacja</td>";
$count=0;

$sql1="SELECT * FROM $dbname.serwis_faktura_szcz WHERE ((belongs_to=$es_filia) and (pozycja_nr_faktury='$temp_id'))";
$result1 = mysql_query($sql1, $conn) or die($k_b);
$count_rows = mysql_num_rows($result1);

$count=$count_rows;

$countpf=0;
$kwota_na_fakturze = (float) "0.0";

while ($newArray = mysql_fetch_array($result1)) {
	$temp_pozycja_cena_netto_cr 	= $newArray['pozycja_cena_netto'];
	$temp_ilosc_pozycji			= $newArray['pozycja_ilosc'];
	
	$temp_pozycja_cena_netto = decrypt_md5($temp_pozycja_cena_netto_cr,$key);
	$kwota_na_fakturze+=($temp_pozycja_cena_netto*$temp_ilosc_pozycji);
}

// ilość podfaktur	
	
	$countpf=0;

	$sql1="SELECT * FROM $dbname.serwis_podfaktury WHERE ((belongs_to=$es_filia) and (pf_nr_faktury_id='$temp_id'))";
	$result1 = mysql_query($sql1, $conn) or die($k_b);
	$count_rows = mysql_num_rows($result1);
	$countpf=$count_rows;
	
	$kwota_na_podfakturze = (float) "0.0";
	
	while ($newArray = mysql_fetch_array($result1)) {
		$temp_podfaktura_kwota_netto_crypted 	= $newArray['pf_kwota_netto'];
		$temp_podfaktura_kwota_netto = decrypt_md5($temp_podfaktura_kwota_netto_crypted,$key);
		$kwota_na_podfakturze+=$temp_podfaktura_kwota_netto;
	}
// --------------------
	
echo "<td class=center width=90><b>$count<br />$countpf</b></td>";

// -
// access control 
$accessLevels = array("1","9");
if(array_search($es_prawa, $accessLevels)>-1){
	echo "<td class=right width=120>".correct_currency($kwota_na_fakturze)." zł<br />".correct_currency($kwota_na_podfakturze)." zł</td>";
}
// access control koniec


$j+=1;

$uwagisa = ($temp_uwagi!='');
echo "<td width=40 class=center>";
if ($uwagisa=='1') {
	echo "<a title=' Czytaj uwagi '><input class=imgoption type=image src=img/comment.gif onclick=\"newWindow(480,265,'p_faktura_uwagi.php?id=$temp_id&nr=$temp_numer')\"></a>";
}
echo "<a title=' Edytuj uwagi '><input class=imgoption type=image src=img/edit_comment.gif onclick=\"newWindow(480,245,'e_faktura_uwagi.php?id=$temp_id&nr=$temp_numer')\"></a>";
echo "</td>";
 
echo "<td width=100 class=center>";			 
// -
// access control 
$accessLevels = array("9");
if(array_search($es_prawa, $accessLevels)>-1){	
		echo "<a title=' Popraw fakturę nr $temp_numer '><input class=imgoption type=image src=img/edit.gif  
onclick=\"newWindow(800,600,'e_faktura.php?id=$temp_id&fz=1')\"></a>";

if ($temp_status==0) {
	echo "<a title=' Usuń fakturę '><input class=imgoption type=image src=img/delete.gif  
onclick=\"newWindow(400,140,'u_faktura.php?id=$temp_id')\"></a>";
}
}
// access control koniec

	
	echo "<a title=' Pokaż pozycje na fakturze nr $temp_numer '><input class=imgoption type=image src=img/search.gif  onclick=\"newWindow_r(800,600,'p_faktura_pozycje.php?id=$temp_id')\"></a>";

	//echo "<a title=' Pokaż podfaktury do faktury nr $temp_numer '><input class=imgoption type=image src=img/search_pf.gif  onclick=\"newWindow_r(800,385,'p_podfaktura_pozycje.php?id=$temp_id')\"></a>";

if ($temp_status!=9) {
	echo "<a title=' Dodaj podfakturę do faktury nr $temp_numer '><input class=imgoption type=image src=img/podfaktura.gif onclick=\"newWindow(800,600,'d_podfaktura.php?id=$temp_id')\"></a>";
	}

br();	
if ($temp_status==0) { 
echo "niezatwierdzona";
} else {
	echo "<b>zatwierdzona</b>";
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