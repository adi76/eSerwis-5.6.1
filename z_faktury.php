<?php include_once('header.php'); ?>
<body>
<?php 
include('body_start.php'); 
include('cfg_vat.php');
include('inc_encrypt.php');
// -
// access control 
$accessLevels = array("0","1","9");
if(array_search($es_prawa, $accessLevels)>-1){
} else  { echo "</body>"; ?> <script>alert("Brak uprawnień do tej funkcji"); self.close(); </script><?php exit; }

// access control koniec
// -

$sql="SELECT * FROM $dbname.serwis_faktury WHERE ((belongs_to=$es_filia) and (faktura_status='0')) "; 
if ($_GET[osoba]!='') $sql.=" and (faktura_dostawca='$_GET[osoba]') ";
$sql.= " ORDER BY faktura_data DESC";

$result = mysql_query($sql, $conn) or die($k_b);
$count_rows = mysql_num_rows($result);

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

if ($count_rows!=0) {
pageheader("Przeglądanie faktur niezatwierdzonych",1,1);

?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();

startbuttonsarea("center");
echo "<form name=faktury action=$PHP_SELF method=GET>";
hr();
echo "Pokaż faktury od dostawcy: ";
	echo "<select class=select_hd_p_zgloszenia name=wybierz  onChange='document.location.href=document.faktury.wybierz.options[document.faktury.wybierz.selectedIndex].value'>";
	$sql_lista_dostawcow = "SELECT DISTINCT faktura_dostawca FROM $dbname.serwis_faktury WHERE (belongs_to=$es_filia) and (faktura_status='0') ORDER BY faktura_dostawca ASC";
	$wynik_lista_dost = mysql_query($sql_lista_dostawcow,$conn) or die($k_b);
	echo "<option ";
	if ($wybierz=='') echo "SELECTED ";
	echo "value='$PHP_SELF?showall=$showall&osoba='>Wszyscy dostawcy</option>\n";	
	while (list($temp_dost)=mysql_fetch_array($wynik_lista_dost)) {
		echo "<option "; 
		if ($osoba==$temp_dost) echo "SELECTED ";
		echo "value='$PHP_SELF?showall=$showall&osoba=".urlencode($temp_dost)."'>$temp_dost</option>\n";	
	}
	echo "</select>&nbsp;|";

if ($showall==0) {
	echo "<a class=paging href=z_faktury.php?showall=1&paget=$page>Pokaż wszystko na jednej stronie</a>";
	} else {
	echo "<a class=paging href=z_faktury.php?showall=0&page=$paget>Dziel na strony</a>";
	}
echo "| Łącznie: <b>$count_rows pozycji</b>";
endbuttonsarea();

starttable();
echo "<tr>";
echo "<th class=center>LP</th><th>Numer faktury<br />Nr zamówienia</th><th class=center>Data<br />wystawienia</th><th>Dostawca<br />Realizacja zakupu</th><th class=center>Poz. na fakt.<br />Ilość podfaktur</th>";

// -
// access control 
$accessLevels = array("9");
if(array_search($es_prawa, $accessLevels)>-1){
	echo "<th class=right>Wpisana wartość faktury netto / brutto</th>";
	echo "<th class=right>Wartość faktury netto<br />Wartość podfaktur</th>";
}
// access control koniec
// -
echo "<th class=center>Uwagi</th>";
echo "<th class=center>Opcje</th>";

echo "</tr>";

$i = 0;
$j = $page*$rowpersite-$rowpersite+1;
	
while ($newArray = mysql_fetch_array($result)) {
	$temp_id  			= $newArray['faktura_id'];
	$temp_numer			= $newArray['faktura_numer'];
	$temp_data			= $newArray['faktura_data'];
	$temp_dostawca		= $newArray['faktura_dostawca'];
	$temp_koszty		= $newArray['faktura_koszty_dodatkowe'];
	$temp_osoba			= $newArray['faktura_osoba'];
	$temp_datawpisu		= $newArray['faktura_datawpisu'];
	$temp_status		= $newArray['faktura_status'];
	$temp_realizacja	= $newArray['faktura_realizacjazakupu'];
	$temp_fnrz			= $newArray['faktura_nr_zamowienia'];
	$temp_uwagi			= $newArray['faktura_uwagi'];
	
	$temp_knf_cr		= $newArray['faktura_kwota_netto'];	
	$temp_kbf_cr		= $newArray['faktura_kwota_brutto'];
	
	tbl_tr_highlight($i);

	//echo "<td width=30 class=center>$j</td>";
	td("30;c;<a class=normalfont href=# title=' $temp_id '>".$j."</a>");
	if ($temp_numer=='') $temp_numer='-';
	if ($temp_fnrz=='0') $temp_fnrz='-';
	echo "<td>$temp_numer<br />$temp_fnrz";
	if ($pokazpoczatekuwag==1) pokaz_uwagi($temp_uwagi,$iloscznakowuwag,"newWindow(480,265,'p_faktura_uwagi.php?id=$temp_id&nr=$temp_numer')");	
	echo "</td>";
	
// zlicz ilość pozycji na fakturze
	
	$count=0;

	$sql1="SELECT * FROM $dbname.serwis_faktura_szcz WHERE ((pozycja_nr_faktury='$temp_id') and (pozycja_status>-2))";
	$result1 = mysql_query($sql1, $conn) or die($k_b);
	$count_rows = mysql_num_rows($result1);
	$count=$count_rows;
	
	$kwota_na_fakturze = (float) "0.0";
	
	while ($newArray = mysql_fetch_array($result1)) {
		$temp_pozycja_cena_netto_cr 	= $newArray['pozycja_cena_netto'];
		$temp_ilosc_pozycji				= $newArray['pozycja_ilosc'];
		
		$temp_pozycja_cena_netto = decrypt_md5($temp_pozycja_cena_netto_cr,$key);
		$kwota_na_fakturze+=($temp_pozycja_cena_netto*$temp_ilosc_pozycji);
	}

	
	echo "<td class=center>$temp_data</td>";
	echo "<td>$temp_dostawca<br />$temp_realizacja</td>";
	
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
	
	echo "<td class=center><b>$count<br />$countpf</b></td>";
	
// kwota na fakturze 
		
	$koszty_dodatkowe_lacznie = (float) "0.0";
	$sql1="SELECT count(pf_kwota_netto) FROM $dbname.serwis_podfaktury WHERE (pf_nr_faktury_id='$temp_id')";
	//echo "$sql1";
	$result1 = mysql_query($sql1, $conn) or die($k_b);	

	$kwota =  mysql_fetch_array($result1);
	$koszty_dodatkowe_lacznie = $kwota[0];
	
// -
// access control 
$accessLevels = array("9");
if(array_search($es_prawa, $accessLevels)>-1){
	$temp_knf = decrypt_md5($temp_knf_cr,$key);
	$temp_kbf = decrypt_md5($temp_kbf_cr,$key);
	
	if (($temp_knf=='') && ($temp_kbf=='')) { 
		$kn = '';
		$kb = '';
		$od = '<font color=grey>nie wpisana</font>';
	} else {
		$poprawna_kbf = $temp_knf * $VAT_value;
		$roznica = (int) abs(($poprawna_kbf-$temp_kbf)*100);
		$roznica1 = (int) abs(($poprawna_kbf-$temp_kbf));
		$roznica1 = correct_currency($roznica1);
		
		$o_grosz = 0;
		if ((strlen($roznica)==1) && (substr($roznica,0,1)=='1')){
			$o_grosz = 1;
			$o_wiecej = 0;
		} else { 
			if (substr($roznica,0,1)!='0') {
				$o_grosz = 0; 
				$o_wiecej = 1; 
			} else {
				$o_grosz = 0; 
				if ($roznica1==0) {
					$o_wiecej = 0; 
				} else {
					$o_wiecej = 1; 
				}
				}
		}
	
		$kn = correct_currency($temp_knf)." zł";
		$kb = '';
		if ($o_grosz==1) $kb .= "<a title='Kwota brutto faktury niezgodna z poprawną o jeden grosz' href=# class=normalfont><font color=#FF5500>";
		if ($o_wiecej==1) $kb .= "<a title='Kwota brutto faktury niezgodna z poprawną o ".correct_currency($roznica1)."zł' href=# class=normalfont><font color=red>";
		$kb .= correct_currency($temp_kbf)." zł";
		if ($o_grosz==1) $kb .= "</font></a>";
		if ($o_wiecej==1) $kb .= "</font></a>";
		$od = ' / ';
	}
	echo "<td class=right>".$kn."".$od."".$kb."</td>";
	
	$kn1 = correct_currency($kwota_na_fakturze);
	
	if (($kn!='') && ($kb!='')) {
		//$roznica = correct_currency($temp_knf) - correct_currency($kwota_na_fakturze);
		
		$roznica = (int) abs(($temp_knf-$kwota_na_fakturze)*100);
		$warunkowo = 0;
		if ($roznica>0) $info = "Wartość netto wpisanych pozycji na fakturze jest mniejsza niż wpisanej kwoty faktury netto o: ".correct_currency(($temp_knf-$kwota_na_fakturze))." zł. ";
		if ($roznica==0) $info = ""; //"Wpisana kwota netto faktury jest zgodna z wartością wpisanych pozycji";
		if ($roznica<0) $info = "Wartość netto wpisanych pozycji przekracza wpisaną kwotę faktury netto o: ".correct_currency(abs($roznica))." zł. ";	
		
		$rrr= correct_currency(abs($roznica*1));		
		if (($rrr=='1.00') || ($rrr=='-1.00')) {
			$info = "Wartość netto wpisanych pozycji na fakturze jest niezgodna z wpisaną kwotą faktury netto o 1 grosz. ";
			$warunkowo = 1;
		}
		
		$r1 = abs($roznica*100);
		if (($r1>0) && ($r1<=10)) $warunkowo=1;
		
	} else $info = "";
	
	echo "<td class=right>";
	echo "<a title='$info'>";
	//if ($info!='') echo "<font color=red>";
	if ($warunkowo==0) if ($info!='') echo "<font color=red>";
	if ($warunkowo==1) if ($info!='') echo "<font color=#FF5500>";
	
	echo "".$kn1." zł";
	echo "</font>";
	echo "</a>";
	if ($warunkowo==0) if ($info!='') echo "&nbsp;<a title='$info' href=# class=normalfont style='border: 1px solid red'><font color=red>&nbsp;!&nbsp;</font></b></a>";
	if ($warunkowo==1) if ($info!='') echo "&nbsp;<a title='$info' href=# class=normalfont style='border: 1px solid #FF5500'><font color=#FF5500>&nbsp;!&nbsp;</font></b></a>";
	
	echo "<br />".correct_currency($kwota_na_podfakturze)." zł</td>";
}
// access control koniec

$j+=1;
$uwagisa = ($temp_uwagi!='');
echo "<td class=center>";
if ($uwagisa=='1') {
	echo "<a title=' Czytaj uwagi '><input class=imgoption type=image src=img/comment.gif onclick=\"newWindow(480,265,'p_faktura_uwagi.php?id=$temp_id&nr=$temp_numer')\"></a>";
}
echo "<a title=' Edytuj uwagi '><input class=imgoption type=image src=img/edit_comment.gif onclick=\"newWindow(480,245,'e_faktura_uwagi.php?id=$temp_id&nr=$temp_numer')\"></a>";
echo "</td>";

echo "<td class=center>";	

// -
// access control 
$accessLevels = array("9");
if(array_search($es_prawa, $accessLevels)>-1){

if ($count==0) {
	
} else {	
		if (($info=='') || ($warunkowo==1)) {
			echo "<a title=' Zatwierdź fakturę $temp_numer '><input class=imgoption type=image src=img/accept.gif 
	onclick=\"";
			if ($warunkowo==1) $info.="Czy akceptujesz tą różnicę i chcesz przejść do zatwierdzenia faktury ?";
			if ($warunkowo==1) echo "if (confirm('".$info."')) {";
			echo "newWindow($dialog_window_x,$dialog_window_y,'e_faktura_zatwierdz.php?id=$temp_id&poz=$count'); return false;";
			if ($warunkowo==1) echo "} else { return false; } ";
			echo "\"></a>";	
		} else {
			$info .= '\n\r\n\rZatwierdzenie faktury będzie możliwe po poprawieniu niezgodności.';
			echo "<a title=' Zatwierdź fakturę $temp_numer '><input class=imgoption type=image src=img/accept.gif 
	onclick=\"alert('".$info."'); return false;\"></a>";	
		}
	}
}
// access control koniec


	echo "<a title=' Popraw fakturę nr $temp_numer '><input class=imgoption type=image src=img/edit.gif onclick=\"newWindow(800,600,'e_faktura.php?id=$temp_id'); return false;\"></a>";	

	// sprawdzenie czy na fakturze są pozycje sprzedane lub rozliczone
	$sql1a="SELECT * FROM $dbname.serwis_faktura_szcz WHERE (pozycja_nr_faktury='$temp_id') and (pozycja_status>0)";
	$result1a = mysql_query($sql1a, $conn) or die($k_b);
	$count_rows1a = mysql_num_rows($result1a);
	
	if ($count_rows1a==0) echo "<a title=' Usuń fakturę nr $temp_numer '><input class=imgoption type=image src=img/delete.gif 
onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_faktura.php?id=$temp_id'); return false;\"></a>";

	echo "<a title=' Pokaż pozycje na fakturze nr $temp_numer '><input class=imgoption type=image src=img/search.gif  onclick=\"newWindow_r(800,600,'p_faktura_pozycje.php?id=$temp_id'); return false;\"></a>";

	//echo "<a title=' Pokaż podfaktury dla faktury nr $temp_numer '><input class=imgoption type=image src=img/search_pf.gif  onclick=\"newWindow_r(800,385,'p_podfaktura_pozycje.php?id=$temp_id')\"></a>";
	echo "<a title=' Dodaj pozycje do faktury nr $temp_numer '><input class=imgoption type=image src=img/add_pos.gif onclick=\"newWindow_r(800,600,'d_faktura_pozycja.php?id=$temp_id&curr=$count'); return false;\"></a>";
	
	echo "<a title=' Dodaj podfakturę do faktury nr $temp_numer '><input class=imgoption type=image src=img/podfaktura.gif  
onclick=\"newWindow(600,355,'d_podfaktura.php?id=$temp_id'); return false;\"></a>";

	echo "</td>";

	$i+=1;
	echo "</tr>";
}

endtable();

// paging_end
include_once('paging_end.php');
// paging_end

} else {
	errorheader("Brak niezatwierdzonych faktur w bazie");
}

startbuttonsarea("right");
oddziel();
// -
// access control 
$accessLevels = array("1","9");
if(array_search($es_prawa, $accessLevels)>-1) {
addownlinkbutton("'Dodaj nową fakturę'","Button1","button","newWindow_r(800,600,'d_faktura.php')");
addlinkbutton("'Faktury zatwierdzone'","z_faktury_zatwierdzone.php?showall=0");
}
addlinkbutton("'Przeglądaj zgłoszenia'","hd_p_zgloszenia.php?showall=0&p5=BZ&sort=AD&page=1");
addlinkbutton("'Towary na stanie'","p_towary_dostepne.php?view=normal");
addbuttons("start");
endbuttonsarea();

include('body_stop.php');
?>
<script>HideWaitingMessage();</script>
</html>
