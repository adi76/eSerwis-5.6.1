<?php include_once('header.php'); ?>
<body>
<?php
include('inc_encrypt.php');
include('body_start.php');
$sql="SELECT * FROM $dbname.serwis_faktura_szcz WHERE ((belongs_to=$es_filia) and (pozycja_status='0'))";
$result = mysql_query($sql, $conn) or die($k_b);
$count_rows = mysql_num_rows($result);

if ($count_rows!=0) {
pageheader("Towary możliwe do przesunięcia",1,1);
?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();

starttable();
echo "<form name=koszty action=p_towary_przesuniecia_zapisz.php method=POST>";
echo "<tr>";
echo "<th class=center>LP</th>";
echo "<th class=center>Wybierz</th>";
echo "<th>Nazwa towaru</th><th>SN</th>";

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
	echo "<th class=right>Cena netto<br />+koszty</th>";
}
// access control koniec
// -

// -
// access control 
$accessLevels = array("1", "9");
if(array_search($es_prawa, $accessLevels)>-1){

	echo "<th class=center>Nr zamówienia</th><th>Dostawca, nr faktury, data wystawienia</th>";
}
// access control koniec
// -

echo "<th class=center>Uwagi</th>";
echo "</tr>";
$sql="SELECT * FROM $dbname.serwis_faktura_szcz WHERE ((belongs_to=$es_filia) and (pozycja_status='0'))";
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
	$temp_uwagi			= $newArray['pozycja_uwagi'];
	
	$temp_cenanetto = decrypt_md5($temp_cenanetto_cr,$key);
	$temp_cenanettoodsp = decrypt_md5($temp_cenanettoodsp_cr,$key);
	
	tbl_tr_highlight($i);
	
	$sql1="SELECT * FROM $dbname.serwis_faktury WHERE (faktura_id='$temp_nrfaktury')";
	$result1 = mysql_query($sql1, $conn) or die($k_b);
	while ($newArray4 = mysql_fetch_array($result1)) {
		$temp_id4  			= $newArray4['faktura_id'];
		$temp_numer4		= $newArray4['faktura_numer'];
		$temp_data4			= $newArray4['faktura_data'];
		$temp_dostawca4		= $newArray4['faktura_dostawca'];	
		$temp_nz			= $newArray4['faktura_nr_zamowienia'];	
		
	}
	//echo "<td width=30 class=center>$j</td>";
	td("30;c;<a class=normalfont href=# title=' $temp_id '>".$j."</a>");	
	echo "<td class=center><input class=border0 type=checkbox name=pozycja$j></td>";
	echo "<input type=hidden name=pozid$j value=$temp_id>";

// -
// access control 
$accessLevels = array("1", "9");
if(array_search($es_prawa, $accessLevels)>-1){

}
// access control koniec
// -

	echo "<td>$temp_nazwa";
	if ($pokazpoczatekuwag==1) pokaz_uwagi($temp_uwagi,$iloscznakowuwag,"newWindow(480,265,'p_towar_uwagi.php?id=$temp_id'); return false;");	
	echo "</td>";
	echo "<td>$temp_sn</td>";
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

	echo "<td><a title=' Pokaż szczegółowe informacje o fakturze nr $temp_numer4 ' class=opt3 onclick=\"newWindow_r(800,600,'p_faktura_pozycje.php?id=$temp_id4')\">$temp_dostawca4, $temp_numer4, $temp_data4</a>";
	
	echo "</td>";
}
// access control koniec
// -

echo "<td class=center>";
if ($temp_uwagi!='') {
  echo "<a title=' $temp_uwagi '><input class=imgoption type=image src=img/comment.gif onclick=\"newWindow(480,265,'p_towar_uwagi.php?id=$temp_id'); return false;\"></a>";
}
echo "<a title=' Edytuj uwagi o towarze '><input class=imgoption type=image src=img/edit_comment.gif onclick=\"newWindow(480,245,'e_towar_uwagi.php?id=$temp_id'); return false;\"></a>";
echo "</td>";

$j+=1;
	
$d= Date('d');
$m= Date('m');
$r= Date('Y');

$i+=1;
echo "</tr>";
}
echo "<input type=hidden name=ilosc value=$i>";

endtable();
oddziel();
startbuttonsarea("right");
echo "<span style='float:left'>";
addselectallbutton("'Zaznacz wszystkie'");
addinvertbutton("'Odwróć zaznaczenie'");
addclearselectionbutton("'Odczytaj ponownie'");
echo "</span>";

echo "<input class=buttons type=submit id=submit name=submit88 style='font-weight:bold' value='Przesuń zaznaczone towary / usługi'>";
echo "<br />";
addlinkbutton("'Faktury niezatwierdzone'","z_faktury.php?showall=0");
addlinkbutton("'Faktury zatwierdzone'","z_faktury_zatwierdzone.php?showall=0");
addownlinkbutton("'Towary dostępne'","Button1","button","self.location.href='p_towary_dostepne.php?view=normal';");
addlinkbutton("'Przeglądaj zgłoszenia'","hd_p_zgloszenia.php?showall=0&p5=BZ&sort=AD&page=1");
//addownsubmitbutton("'Przesuń zaznaczone towary/usługi'","submit88");
echo "<br />";
addbuttons("start");
endbuttonsarea();

_form();
	
} else {
	errorheader("Brak towarów na stanie");
	startbuttonsarea("right");
	addbuttons("start");
	endbuttonsarea();	
		?>
		<meta http-equiv="REFRESH" content="<?php echo $http_refresh; ?>;url=<?php echo "$linkdostrony";?>main.php">
		<?php
	}

include('body_stop.php');
?>
<script>HideWaitingMessage();</script>

</body>
</html>
