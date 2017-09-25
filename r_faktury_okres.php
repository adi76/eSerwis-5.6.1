<?php include_once('header.php'); ?>
<body>
<?php 
include('inc_encrypt.php');
if ($submit) { 
include('body_start.php');
$okres_od=$_GET[okres_od];
$okres_do=$_GET[okres_do];

$sql_a = "SELECT * FROM $dbname.serwis_faktury WHERE ((belongs_to=$es_filia) and (faktura_data BETWEEN '$okres_od 00:00:00' AND '$okres_do 23:59:59'))"; //and (faktura_status>0))";

$result_a = mysql_query($sql_a, $conn) or die($k_b);
$count_rows = mysql_num_rows($result_a);
$totalrows = $count_rows;

if ($count_rows!=0) {	
pageheader("Wyniki wyszukiwania faktur z okresu",1,1);
infoheader("<b>".$okres_od." - ".$okres_do."</b>");
	
?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
	
startbuttonsarea("center");
if ($showall==0) {
	echo "<a class=normalfont href=r_faktury_okres.php?submit=$submit&showall=1&paget=$page&okres_od=$okres_od&okres_do=$okres_do>Pokaż wszystko na jednej stronie</a>";
	} else {
	echo "<a class=normalfont href=r_faktury_okres.php?submit=$submit&showall=0&page=$paget&okres_od=$okres_od&okres_do=$okres_do>Dziel na strony</a>";
	}
	
	echo "<a href=# class=paging1>";
	echo "| Łącznie: <b>$totalrows pozycji</b>";
	echo "</a>";
	
endbuttonsarea();
	
	starttable();
	echo "<tr>";
	echo "<th class=center>LP</th><th>Numer faktury<br />Numer zamówienia</th><th>Data<br />wystawienia</th><th>Dostawca<br />Realizacja zakupu</th><th>Poz. na fakt.<br />Ilość podfaktur</th>";

	// -
	// access control 
	$accessLevels = array("1","9");
	if(array_search($es_prawa, $accessLevels)>-1){
	echo "<th class=right>Kwota na fakturze<br />Koszty podfaktur</th>";
	}
	// access control koniec
	
	echo "<th class=center>Status</th><th class=center width=50>Uwagi</th>";

	echo "<th class=center>Opcje</th>";

	echo "</tr>";

	$sql_a = "SELECT * FROM $dbname.serwis_faktury WHERE ((belongs_to=$es_filia) and (faktura_data BETWEEN '$okres_od 00:00:00' AND '$okres_do 23:59:59'))"; // and (faktura_status>0))";

$result_a = mysql_query($sql_a, $conn) or die($k_b);

// paging
// -
$totalrows = mysql_num_rows($result_a);
if(empty($page)){ $page = 1; }

if ($showall==0) {
  $rps=$rowpersite;
} else $rps=10000;

$limitvalue = $page * $rps - ($rps);
$sql_a=$sql_a." LIMIT $limitvalue, $rps";
$result_a = mysql_query($sql_a, $conn) or die($k_b);
// -
// koniec - paging

$i=0;
$j = $page*$rowpersite-$rowpersite+1;

while ($newArray = mysql_fetch_array($result_a)) {		
	
	$temp_id  			= $newArray['faktura_id'];
	$temp_numer			= $newArray['faktura_numer'];
	$temp_data			= $newArray['faktura_data'];
	$temp_dostawca		= $newArray['faktura_dostawca'];
	$temp_koszty		= $newArray['faktura_koszty_dodatkowe'];
	$temp_osoba			= $newArray['faktura_osoba'];
	$temp_datawpisu		= $newArray['faktura_datawpisu'];
	$temp_status		= $newArray['faktura_status'];
	$temp_nr_zamowienia = $newArray['faktura_nr_zamowienia'];
	$temp_uwagi			= $newArray['faktura_uwagi'];
  
tbl_tr_highlight($i);

$i+=1;
	
//echo "<td width=30 class=center>$j</td>";
td("30;c;<a class=normalfont href=# title=' $temp_id '>".$j."</a>");
echo "<td>$temp_numer<br />$temp_nr_zamowienia";
if ($pokazpoczatekuwag==1) pokaz_uwagi($temp_uwagi,$iloscznakowuwag,"newWindow(480,265,'fuwagi_e.php?id=$temp_id&nr=$temp_numer')");
echo "<td>$temp_data</td>";
echo "<td>$temp_dostawca</td>";

$count=0;
$sql1="SELECT * FROM $dbname.serwis_faktura_szcz WHERE (pozycja_nr_faktury='$temp_id')";

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
	$sql1="SELECT * FROM $dbname.serwis_podfaktury WHERE (pf_nr_faktury_id='$temp_id')";

	$result1 = mysql_query($sql1, $conn) or die($k_b);
	$count_rows = mysql_num_rows($result1);
	$countpf=$count_rows;
	
	$kwota_na_podfakturze = (float) "0.0";
	
	while ($newArray = mysql_fetch_array($result1)) {
		$temp_podfaktura_kwota_netto_crypted 	= $newArray['pf_kwota_netto'];
		$temp_podfaktura_kwota_netto = decrypt_md5($temp_podfaktura_kwota_netto_crypted,$key);
		$kwota_na_podfakturze+=$temp_podfaktura_kwota_netto;
	}
	echo "<td width=90 class=center><b>$count<br />$countpf</b></td>";

// -
// access control 
$accessLevels = array("1","9");
if(array_search($es_prawa, $accessLevels)>-1){
	echo "<td class=right width=120>".correct_currency($kwota_na_fakturze)." zł<br />".correct_currency($kwota_na_podfakturze)." zł</td>";}
// access control koniec
	
if ($temp_status==0) { 
echo "<td class=center><a class=normalfont title=' Pokaż niezatwierdzone faktury ' href=z_faktury.php?showall=0>niezatwierdzona</td>";
} else {
	echo "<td class=center><a class=normalfont title=' Pokaż zatwierdzone faktury ' href=z_faktury_zatwierdzone.php?showall=0>zatwierdzona</a></td>";
} 

$j+=1;

$uwagisa = ($temp_uwagi!='');

echo "<td class=center>";
if ($uwagisa=='1') {
	echo "<input class=imgoption type=image src=img/comment.gif title=' Czytaj uwagi ' onclick=\"newWindow(480,265,'fuwagi_e.php?id=$temp_id&nr=$temp_numer')\">";
}
echo "<input class=imgoption type=image src=img/edit_comment.gif title=' Edytuj uwagi ' onclick=\"newWindow(480,245,'e_faktura_uwagi.php?id=$temp_id&nr=$temp_numer')\">";
echo "</td>";

echo "<td class=center>";

// -
// access control 
$accessLevels = array("9");
if(array_search($es_prawa, $accessLevels)>-1){	
	if ($temp_status!=9) {
		echo "<input class=imgoption type=image src=img/edit.gif title=' Popraw fakturę ' onclick=\"newWindow(800,600,'e_faktura.php?id=$temp_id&fz=1')\">";
}
}
// access control koniec


echo "<a href=# onclick=\"newWindow_r(800,600,'p_faktura_pozycje.php?id=$temp_id')\"><img class=imgoption src=img/search.gif border=0 width=16 width=16></a></a>";							   
echo "</tr>";
}

endtable();

// paging_end
include_once('paging_end.php');
// paging_end
} else errorheader("Nie znaleziono faktur w podanym przez Ciebie okresie");

startbuttonsarea("right");
oddziel();
addlinkbutton("'Zmień kryteria wyszukiwania'","main.php?action=fsfo");
addbuttons("start");
endbuttonsarea();
include('body_stop.php');

} else { ?>

<?php

// -
// access control 
$accessLevels = array("0","1","9");
if(array_search($es_prawa, $accessLevels)>-1){
}
// access control koniec
// -
	br();
	pageheader("Pokaż faktury z okresu");
	
	echo "<table cellspacing=1 align=center style=width:300px>";
	echo "<form name=ruch action=r_faktury_okres.php method=GET>";
	
	tbl_empty_row();

	echo "<tr>";
	echo "<td class=center colspan=2>";
	echo "<b>Podaj zakres dat<br /><br /></b>";
	echo "</td>";
	echo "</tr>";
	
		echo "<tr>";
		echo "<td width=150 class=center>od dnia</td>";
		echo "<td width=150 class=center>do dnia</td>";
		echo "</tr>";

	$r1 = Date('Y');
	$m1 = Date('m');
	$d1 = '01';
	
	$r2 = Date('Y');
	$m2 = Date('m');
	
	if ($m2==1) $d2=31;
	if ($m2==2) $d2=29;
	if ($m2==3) $d2=31;
	if ($m2==4) $d2=30;
	if ($m2==5) $d2=31;
	if ($m2==6) $d2=30;
	if ($m2==7) $d2=31;
	if ($m2==8) $d2=31;
	if ($m2==9) $d2=30;
	if ($m2==10) $d2=31;
	if ($m2==11) $d2=30;
	if ($m2==12) $d2=31;
				
	$data1=$r1.'-'.$m1.'-'.$d1;
	$data2=$r1.'-'.$m1.'-'.$d2;
	
		echo "<tr>";
			echo "<td class=center><input class=wymagane size=10 maxlength=10 type=text id=okres_od name=okres_od value=$data1 onkeypress='return handleEnter(this, event);' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\">"; 
			echo "<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif width=16 height=16 border=0></a>";
			if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('okres_od').value='".Date('Y-m-d')."'; return false;\">";
			echo "</td>";
			echo "<td class=center><input class=wymagane size=10 maxlength=10 type=text id=okres_do name=okres_do value=$data2 onkeypress='return handleEnter(this, event);' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\">"; 
			echo "<a tabindex=-1 href=javascript:cal11.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif width=16 height=16 border=0></a>";
			if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('okres_do').value='".Date('Y-m-d')."'; return false;\">";
			echo "</td>";
		echo "</tr>";		

	tbl_empty_row();	
	
	endtable();
	
	startbuttonsarea("center");
	addownsubmitbutton("'Pokaż'","submit");
	endbuttonsarea();
	
	_form();	
	
?>

<script language="JavaScript">
var cal1 = new calendar1(document.forms['ruch'].elements['okres_od']);
	cal1.year_scroll = true;
	cal1.time_comp = false;
var cal11 = new calendar1(document.forms['ruch'].elements['okres_do']);
	cal11.year_scroll = true;
	cal11.time_comp = false;
</script>

<script language="JavaScript" type="text/javascript">
	var frmvalidator  = new Validator("ruch");
	 
	frmvalidator.addValidation("okres_od","req","Nie podałeś daty początkowej");  
	frmvalidator.addValidation("okres_do","req","Nie podałeś daty końcowej");  

	frmvalidator.addValidation("okres_od","numerichyphen","Użyłeś niedozwolonych znaków w polu \"od dnia\"");
	frmvalidator.addValidation("okres_do","numerichyphen","Użyłeś niedozwolonych znaków w polu \"do dnia\"");
	
</script>
<?php } ?>

<script>HideWaitingMessage();</script>

</body>
</html>