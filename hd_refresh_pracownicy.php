<?php 

include_once('header_simple.php');
include_once('cfg_eserwis.php');
include_once('cfg_helpdesk.php');

$databiezaca = Date('Y-m-d');

$result44 = mysql_query("SELECT CONCAT(user_first_name,' ',user_last_name) as X FROM $dbname.serwis_uzytkownicy WHERE (belongs_to=$es_filia) and (user_locked=0) ORDER BY user_last_name ASC", $conn_hd) or die($k_b);

$uu = 1;
$t = 0;

$span_pracownicy = '';

$span_pracownicy .= "<div id=treecontrol>";
//if (strlen($_REQUEST[p1])==10) 
if (strlen($_COOKIE[selected_date])>1) $databiezaca = $_COOKIE[selected_date];
$span_pracownicy .= "&nbsp;Kontrola dla: <b>$databiezaca</b><hr />";

$span_pracownicy .= "&nbsp;Wszyscy:&nbsp;<a class=normalfont title='Zwiń wszystkie' href=#><img src='js/jquery/images/minus.gif' border=0 /> Zwiń</a>&nbsp;&nbsp;<a class=normalfont title='Rozwiń wszystkie' href=#><img src='js/jquery/images/plus.gif' border=0 /> Rozwiń</a><hr />";
//$span_pracownicy .= "&nbsp;<a class=normalfont title='Zwiń / Rozwiń wszystkie' href=#>Zwiń / Rozwiń wszystkie</a>";

$span_pracownicy .= "</div>";
	
$span_pracownicy .= "<ul id=browser>";

while (list($imieinazwisko) = mysql_fetch_array($result44)) {

	list($param1,$param2)=mysql_fetch_array(mysql_query("SELECT COUNT(zgl_szcz_id),SUM(zgl_szcz_czas_wykonywania) FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_osoba_wykonujaca_krok='$imieinazwisko') and (zgl_szcz_widoczne=1) and (SUBSTRING(zgl_szcz_czas_rozpoczecia_kroku,1,10)='".$databiezaca."')", $conn_hd));

	//list($param1,$param2)=mysql_fetch_array(mysql_query("SELECT COUNT(zgl_szcz_id),SUM(zgl_szcz_czas_wykonywania) FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_osoba_wykonujaca_krok='$imieinazwisko') and (zgl_szcz_widoczne=1) and (SUBSTRING(zgl_szcz_czas_rozpoczecia_kroku,1,10)='".$databiezaca."')", $conn_hd));

	list($param3)=mysql_fetch_array(mysql_query("SELECT COUNT(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (hd_zgloszenie.belongs_to=$es_filia) and (hd_zgloszenie.zgl_widoczne=1) AND ((zgl_kategoria='2') OR (zgl_kategoria='6')) AND (zgl_status<>'9') AND (zgl_data_rozpoczecia!='0000-00-00 00:00:00') and ((zgl_status=1) or (zgl_status=2)) and (zgl_osoba_przypisana='$imieinazwisko')", $conn_hd));	
	
//"SELECT *, TIMEDIFF('".Date('Y-m-d H:i:s')."',zgl_data_rozpoczecia) as CzasDoRozpoczecia FROM helpdesk.hd_zgloszenie WHERE (hd_zgloszenie.belongs_to=$_REQUEST[f]) and (hd_zgloszenie.zgl_widoczne=1) AND ((zgl_kategoria='2') OR (zgl_kategoria='6')) AND (zgl_status<>'9') AND (zgl_data_rozpoczecia!='0000-00-00 00:00:00') and ((zgl_status=1) or (zgl_status=2)) and (zgl_osoba_przypisana='$_REQUEST[cu]')"
//$MZPriorytetRozpoczecia = mysql_num_rows($resultX);	
	

	list($param4)=mysql_fetch_array(mysql_query("SELECT COUNT(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (hd_zgloszenie.belongs_to=$es_filia) and (hd_zgloszenie.zgl_widoczne=1) AND ((zgl_kategoria='2') OR (zgl_kategoria='6'))	 AND (zgl_status<>'9') AND (zgl_data_zakonczenia!='0000-00-00 00:00:00') and ((zgl_status<>1) and (zgl_status<>2)) and (zgl_osoba_przypisana='$imieinazwisko')", $conn_hd));
	
	list($param5)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status='2') and (zgl_widoczne=1) and (zgl_osoba_przypisana='$imieinazwisko')", $conn_hd));
	
	//	list($param5)=mysql_fetch_array(mysql_query("SELECT COUNT(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status='2') and (zgl_osoba_przypisana='$imieinazwisko') ", $conn_hd));

	list($param6)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status='3') and (zgl_widoczne=1) and (zgl_osoba_przypisana='$imieinazwisko')", $conn_hd));
	
	//list($param6)=mysql_fetch_array(mysql_query("SELECT COUNT(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status='3') and (zgl_osoba_przypisana='$imieinazwisko') ", $conn_hd));

	list($param7)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status='3B') and (zgl_widoczne=1) and (zgl_osoba_przypisana='$imieinazwisko')", $conn_hd));
	
	//list($param7)=mysql_fetch_array(mysql_query("SELECT COUNT(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status='3B') and (zgl_osoba_przypisana='$imieinazwisko') ", $conn_hd));

	list($param8)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status='3A') and (zgl_widoczne=1) and (zgl_osoba_przypisana='$imieinazwisko')", $conn_hd));
	
//	list($param8)=mysql_fetch_array(mysql_query("SELECT COUNT(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status='3A') and (zgl_osoba_przypisana='$imieinazwisko') ", $conn_hd));

	list($param9)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status<>'9') and (zgl_widoczne=1) and (zgl_osoba_przypisana='$imieinazwisko')", $conn_hd));
	
//	list($param9)=mysql_fetch_array(mysql_query("SELECT COUNT(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status<>'9') and (zgl_osoba_przypisana='$imieinazwisko') ", $conn_hd));

	list($param10)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status='6') and (zgl_widoczne=1) and (zgl_osoba_przypisana='$imieinazwisko')", $conn_hd));
	
	//list($param10)=mysql_fetch_array(mysql_query("SELECT COUNT(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status='6') and (zgl_osoba_przypisana='$imieinazwisko') ", $conn_hd));
	
	$span_pracownicy .= "<li><span class=folder>".skroc_tekst($imieinazwisko,24)."</span>";
		$span_pracownicy .= "<ul>";	
		
			$span_pracownicy .= "<li><a class=normalfont href=# onClick=\"newWindow_r(800,600,'hd_g_raport_dzienny_dla_pracownika.php?okres_od=$databiezaca&okres_do=$databiezaca&tuser=".urlencode($imieinazwisko)."&submit=Generuj+raport&special=1&norefresh=1'); return false;\"><span class=folder>Ilość kroków </span><span style='float:right; margin-right:4px'><b>".$param1."</b></span></a></li>";
			
			$span_pracownicy .= "<li><a class=normalfont href=# onClick=\"newWindow_r(800,600,'hd_g_raport_dzienny_dla_pracownika.php?okres_od=$databiezaca&okres_do=$databiezaca&tuser=".urlencode($imieinazwisko)."&submit=Generuj+raport&special=1&norefresh=1'); return false;\"><span class=folder>Łączny czas </span><span style='float:right; margin-right:4px'><b>".minutes2hours($param2,'short')."</b></span></a></li>";
			
			if ($param3>0) $span_pracownicy .= "<li><a class=normalfont href=hd_p_zgloszenia.php?sa=0&p2=&p5=BZ&p6=".urlencode($imieinazwisko)."&hd_rps=$_REQUEST[hd_rps]&page=1&p0=R><span class=folder style='color:red'>Pr. rozpoczęcia </span><span style='float:right; margin-right:4px'><b>".$param3."</b></span></a></li>";
			if ($param4>0) $span_pracownicy .= "<li><a class=normalfont href=hd_p_zgloszenia.php?sa=0&p2=&p5=BZ&p6=".urlencode($imieinazwisko)."&hd_rps=$_REQUEST[hd_rps]&page=1&p0=Z><span class=folder style='color:red'>Pr. zakończenia </span><span style='float:right; margin-right:4px'><b>".$param4."</b></span></a></li>";
			if ($param5>0) $span_pracownicy .= "<li><a class=normalfont href=hd_p_zgloszenia.php?sa=0&p5=2&p6=".urlencode($imieinazwisko)."&sort=$_REQUEST[sort]&hd_rps=$_REQUEST[hd_rps]&page=1><span class=folder>Przypisane </span><span style='float:right; margin-right:4px'><b>".$param5."</b></span></a></li>";
			if ($param6>0) $span_pracownicy .= "<li><a class=normalfont href=hd_p_zgloszenia.php?sa=0&p5=3&p6=".urlencode($imieinazwisko)."&sort=$_REQUEST[sort]&hd_rps=$_REQUEST[hd_rps]&page=1><span class=folder>Rozpoczęte </span><span style='float:right; margin-right:4px'><b>".$param6."</b></span></a></li>";
			if ($param7>0) $span_pracownicy .= "<li><a class=normalfont href=hd_p_zgloszenia.php?sa=0&p5=3B&p6=".urlencode($imieinazwisko)."&sort=$_REQUEST[sort]&hd_rps=$_REQUEST[hd_rps]&page=1><span class=folder>W firmie </span><span style='float:right; margin-right:4px'><b>".$param7."</b></span></a></li>";
			if ($param8>0) $span_pracownicy .= "<li><a class=normalfont href=hd_p_zgloszenia.php?sa=0&p5=3A&p6=".urlencode($imieinazwisko)."&sort=$_REQUEST[sort]&hd_rps=$_REQUEST[hd_rps]&page=1><span class=folder>W serwisie zewn. </span><span style='float:right; margin-right:4px'><b>".$param8."</b></span></a></li>";
			if ($param9>0) $span_pracownicy .= "<li><a class=normalfont href=hd_p_zgloszenia.php?sa=0&p5=BZ&p6=".urlencode($imieinazwisko)."&sort=$_REQUEST[sort]&hd_rps=$_REQUEST[hd_rps]&page=1><span class=folder>Nie zamknięte </span><span style='float:right; margin-right:4px'><b>".$param9."</b></span></a></li>";
			if ($param10>0) $span_pracownicy .= "<li><a class=normalfont href=hd_p_zgloszenia.php?sa=0&p5=6&p6=".urlencode($imieinazwisko)."&sort=$_REQUEST[sort]&hd_rps=$_REQUEST[hd_rps]&page=1><span class=folder>Do oddania </span><span style='float:right; margin-right:4px'><b>".$param10."</b></span></a></li>";
			
			$span_pracownicy .= "<li><a class=normalfont href=hd_p_zgloszenia.php?sa=0&p5=9&p6=".urlencode($imieinazwisko)."&sort=$_REQUEST[sort]&hd_rps=$_REQUEST[hd_rps]&page=1><span class=folder>Zamknięte </span><span style='float:right; margin-right:4px'><b>*</b></span></a></li>";
		
		$span_pracownicy .= "</ul>";
	$span_pracownicy .= "</li>";
	
/*	
	$span_pracownicy.= "<tr "; 
	if ($uu % 2 != 0 ) { $span_pracownicy.=" class=nieparzyste "; } else { $span_pracownicy.=" class=parzyste "; }
	$span_pracownicy.=" id=tr_pracownik_$t style='margin=0px; padding:0px;'><td style='width:100%;margin=0px; padding:0px; '>";
	$span_pracownicy.=$imieinazwisko;
	$span_pracownicy.="</td>";
	$span_pracownicy.="</tr>";
*/
	$t++;
	$uu++;
	
}

$span_pracownicy .= "</ul>";
echo $span_pracownicy;

?>

<?php if (($es_prawa==9) && ($funkcja_kontroli_pracownikow==1)) { ?>
<script type="text/javascript" src="js/jquery/jquery.treeview.js"></script>
<script type="text/javascript" src="js/jquery/jquery.cookie_treeview.js"></script>
<link rel="stylesheet" type="text/css" href="js/jquery/jquery.treeview.css" />
<?php } ?>

<script>
//if (readCookie('hd_pokaz_pracownikow')=='TAK') {
	$(document).ready(function(){ $("#browser").treeview({
		control: "#treecontrol",
		persist: "cookie",
		collapsed: true
	}) });
//}
</script>
