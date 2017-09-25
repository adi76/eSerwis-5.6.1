<?php include_once('header.php'); ?>
<body>
<?php include('body_start.php'); 
$sql="SELECT * FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ";
if ($es_m==1) { } else $sql=$sql."(serwis_komorki.belongs_to=$es_filia) and  ";
$sql.=" (serwis_komorki.up_pion_id=serwis_piony.pion_id) ";
if ($wybierz!='') $sql.="AND (serwis_komorki.up_id=$wybierz) ";
if ($wybierz_p!='') $sql.="AND (serwis_komorki.up_pion_id=$wybierz_p) ";

if ($wybierz_typ!='') $sql.="AND (serwis_komorki.up_typ=$wybierz_typ) ";
if ($wybierz_kat!='') $sql.="AND (serwis_komorki.up_kategoria=$wybierz_kat) ";
if ($aktywne!='') $sql.="AND (serwis_komorki.up_active=$aktywne) ";
if ($ko!='') $sql.="AND (serwis_komorki.up_kompleksowa_obsluga=$ko) ";

$sql=$sql."ORDER BY serwis_piony.pion_nazwa,serwis_komorki.up_nazwa ASC";

//echo "<br />".$aktywne;
//echo "<br />".$sql;

$result = mysql_query($sql, $conn) or die($k_b);
$count_rows = mysql_num_rows($result);
// paging
$totalrows = mysql_num_rows($result);
if ($showall==0) { $rps=$rowpersite; } else $rps=10000;
if(empty($page)){ $page = 1; }
$limitvalue = $page * $rps - ($rps);
$sql="SELECT * FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ";
if ($es_m==1) { } else $sql=$sql."(serwis_komorki.belongs_to=$es_filia) and ";
$sql.=" (serwis_komorki.up_pion_id=serwis_piony.pion_id) ";
if ($wybierz!='') $sql.="AND (serwis_komorki.up_id=$wybierz) ";
if ($wybierz_p!='') $sql.="AND (serwis_komorki.up_pion_id=$wybierz_p) ";

if ($wybierz_typ!='') $sql.="AND (serwis_komorki.up_typ=$wybierz_typ) ";
if ($wybierz_kat!='') $sql.="AND (serwis_komorki.up_kategoria=$wybierz_kat) ";
if ($aktywne!='') $sql.="AND (serwis_komorki.up_active=$aktywne) ";
if ($ko!='') $sql.="AND (serwis_komorki.up_kompleksowa_obsluga=$ko) ";

$sql_to_temp = $sql."ORDER BY serwis_piony.pion_nazwa,serwis_komorki.up_nazwa ASC";

$sql=$sql."ORDER BY serwis_piony.pion_nazwa,serwis_komorki.up_nazwa ASC LIMIT $limitvalue, $rps";

$result = mysql_query($sql, $conn) or die($k_b);
//$count_rows = mysql_num_rows($result);

$czyistnieje_temp = mysql_query("SELECT * FROM $dbname.temp_serwis_komorki_$es_nr LIMIT 1", $conn);
if ($czyistnieje_temp) {	
	$sql_report = "TRUNCATE TABLE $dbname.temp_serwis_komorki_$es_nr";	$result_report = mysql_query($sql_report, $conn) or die($k_b);
} else { 
	$sql_report = "CREATE TABLE $dbname.`temp_serwis_komorki_$es_nr` (
		`pole_pelna_nazwa` varchar(150) collate utf8_polish_ci NOT NULL,
		`pole_typ` varchar(50) collate utf8_polish_ci NOT NULL,
		`pole_kategoria` varchar(40) collate utf8_polish_ci NOT NULL,
		`pole_typ_uslugi` varchar(20) collate utf8_polish_ci NOT NULL,
		`pole_koi` varchar(3) collate utf8_polish_ci NOT NULL,
		`pole_stempel` varchar(10) collate utf8_polish_ci NOT NULL,
		`pole_podsiec` varchar(20) collate utf8_polish_ci NOT NULL,
		`pole_wanport` varchar(20) collate utf8_polish_ci NOT NULL,
		`pole_telefon` varchar(100) collate utf8_polish_ci NOT NULL,
		`pole_umowa` varchar(50) collate utf8_polish_ci NOT NULL,
		`pole_zalacznik` varchar(50) collate utf8_polish_ci NOT NULL,
		`pole_open` varchar(20) collate utf8_polish_ci NOT NULL,
		`pole_close` varchar(20) collate utf8_polish_ci NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;";	
			
		$result_report = mysql_query($sql_report, $conn) or die($k_b);	
}
	$zap_4 = "INSERT INTO $dbname.temp_serwis_komorki_$es_nr (pole_pelna_nazwa,pole_typ,pole_kategoria,pole_typ_uslugi,pole_koi,pole_stempel,pole_podsiec,pole_wanport,pole_telefon,pole_umowa,pole_zalacznik,pole_open,pole_close) SELECT CONCAT($dbname.serwis_piony.pion_nazwa, ' ',$dbname.serwis_komorki.up_nazwa) as A, $dbname.serwis_slownik_typ_komorki.slownik_typ_komorki_opis, $dbname.serwis_komorki.up_kategoria,$dbname.serwis_komorki.up_typ_uslugi,$dbname.serwis_komorki.up_kompleksowa_obsluga,$dbname.serwis_komorki.up_stempel,$dbname.serwis_komorki.up_ip,$dbname.serwis_komorki.up_nrwanportu,$dbname.serwis_komorki.up_telefon,$dbname.serwis_umowy.umowa_nr,$dbname.serwis_komorki.up_przypisanie_jednostki,$dbname.serwis_komorki.up_open_date,$dbname.serwis_komorki.up_close_date FROM $dbname.serwis_komorki, $dbname.serwis_piony, $dbname.serwis_slownik_typ_komorki, $dbname.serwis_umowy WHERE ($dbname.serwis_komorki.up_pion_id=$dbname.serwis_piony.pion_id) and ($dbname.serwis_komorki.up_typ=$dbname.serwis_slownik_typ_komorki.slownik_typ_komorki_id) and ($dbname.serwis_umowy.umowa_id=$dbname.serwis_komorki.up_umowa_id) and ";
	
	if ($es_m==1) { } else $zap_4=$zap_4."(serwis_komorki.belongs_to=$es_filia) and ";
	$zap_4.= " (serwis_komorki.up_pion_id=serwis_piony.pion_id) ";
	if ($wybierz!='') $zap_4.="AND (serwis_komorki.up_id=$wybierz) ";
	if ($wybierz_p!='') $zap_4.="AND (serwis_komorki.up_pion_id=$wybierz_p) ";

	if ($wybierz_typ!='') $zap_4.="AND (serwis_komorki.up_typ=$wybierz_typ) ";
	if ($wybierz_kat!='') $zap_4.="AND (serwis_komorki.up_kategoria=$wybierz_kat) ";
	if ($aktywne!='') $zap_4.="AND (serwis_komorki.up_active=$aktywne) ";
	if ($ko!='') $zap_4.="AND (serwis_komorki.up_kompleksowa_obsluga=$ko) ";

	$zap_4 = $zap_4."ORDER BY serwis_piony.pion_nazwa,serwis_komorki.up_nazwa ASC";	
	
	//echo $zap_4;
	
	$result_report5 = mysql_query($zap_4, $conn) or die($k_b);	
		
		
		//INSERT INTO $dbname.temp_serwis_komorki_$es_nr (pole_pelna_nazwa,pole_typ,pole_kategoria,pole_typ_uslugi,pole_koi,pole_stempel,pole_podsiec,pole_wanport,pole_telefon,pole_umowa,pole_zalacznik) SELECT CONCAT(serwis_piony.pion_nazwa, serwis_komorki.up_nazwa) as A, serwis_slownik_typ_komorki.slownik_typ_komorki_opis WHERE serwis_komorki.up_pion_id=serwis_piony.pion_id and serwis_komorki.up_typ=serwis_slownik_typ_komorki.slownik_typ_komorki_id
	

// koniec - paging
if ($count_rows!=0) {
	pageheader("Przeglądanie bazy podległych UP",1,1);
	
	?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
		
	startbuttonsarea("center");
	if ($showall==0) {
		echo "<a class=paging href=$PHP_SELF?showall=1&paget=$page&wybierz_p=$wybierz_p&wybierz_typ=$wybierz_typ&wybierz_kat=$wybierz_kat&aktywne=$aktywne&ko=$ko>Pokaż wszystko na jednej stronie</a>";
	} else {
		echo "<a class=paging href=$PHP_SELF?showall=0&page=$paget&wybierz_p=$wybierz_p&wybierz_typ=$wybierz_typ&wybierz_kat=$wybierz_kat&aktywne=$aktywne&ko=$ko>Dziel na strony</a>";	
	}
	echo "| Łącznie: <b>$count_rows pozycji</b>";
	endbuttonsarea();
	
	startbuttonsarea("center");
	echo "<form name=komorki action=$PHP_SELF method=GET>";
	hr();
	echo "Pokaż: ";
		echo "<select class=select_hd_p_zgloszenia name=wybierz ";
		if ($_REQUEST[wybierz]!='') echo " style='background-color:yellow; '"; 
		echo " onChange='document.location.href=document.komorki.wybierz.options[document.komorki.wybierz.selectedIndex].value'>";
		$sql_lista_up = "SELECT up_id, up_nazwa, pion_nazwa, up_active FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((serwis_komorki.belongs_to=$es_filia) and (serwis_piony.pion_id=serwis_komorki.up_pion_id)";
		if ($_REQUEST[wybierz_p]!='') $sql_lista_up .= " and (serwis_komorki.up_pion_id='$_REQUEST[wybierz_p]') ";
		if ($_REQUEST[wybierz_typ]!='') $sql_lista_up .= " and (serwis_komorki.up_typ='$_REQUEST[wybierz_typ]') ";
		if ($_REQUEST[wybierz_kat]!='') $sql_lista_up .= " and (serwis_komorki.up_kategoria='$_REQUEST[wybierz_kat]') ";
		if ($_REQUEST[aktywne]!='') $sql_lista_up .= " and (serwis_komorki.up_active='$_REQUEST[aktywne]') ";
		$sql_lista_up .= ") ORDER BY serwis_piony.pion_nazwa, serwis_komorki.up_nazwa";
		$wynik_lista_up = mysql_query($sql_lista_up,$conn) or die($k_b);
		echo "<option ";
		if ($wybierz=='') echo "SELECTED ";
		echo "value='$PHP_SELF?showall=$showall&okresm=01&okresr=$okresr&paget=$page&page=$paget&wybierz=&wybierz_p=$wybierz_p&wybierz_typ=$wybierz_typ&wybierz_kat=$wybierz_kat&aktywne=$aktywne&ko=$ko'>Wszystkie UP / komórki</option>\n";	
		while (list($temp_upid, $temp_upnazwa, $temp_pionnazwa, $temp_up_state)=mysql_fetch_array($wynik_lista_up)) {
			echo "<option "; 
			if ($wybierz==$temp_upid) echo "SELECTED ";
			
			$_up_state = '';
			if ($temp_up_state==0) $_up_state = '[zawieszona]';
			if ($temp_up_state==2) $_up_state = '[zamknięta]';
			
			if ($temp_up_state==1) {
				echo "value='$PHP_SELF?showall=$showall&okresm=01&okresr=$okresr&paget=$page&page=$paget&wybierz=$temp_upid&wybierz_p=$wybierz_p&wybierz_typ=$wybierz_typ&wybierz_kat=$wybierz_kat&aktywne=$aktywne&ko=$ko'>$temp_pionnazwa $temp_upnazwa ".$_up_state."</option>\n";	
			} else {
				echo "value='$PHP_SELF?showall=$showall&okresm=01&okresr=$okresr&paget=$page&page=$paget&wybierz=$temp_upid&wybierz_p=$wybierz_p&wybierz_typ=$wybierz_typ&wybierz_kat=$wybierz_kat&aktywne=$temp_up_state&ko=$ko'>$temp_pionnazwa $temp_upnazwa ".$_up_state."</option>\n";	
			}
		
		}
		echo "</select>";
		echo "&nbsp;";
		echo "<select class=select_hd_p_zgloszenia name=wybierz_p ";
		if ($_REQUEST[wybierz_p]!='') echo " style='background-color:yellow; '"; 
		echo " onChange='document.location.href=document.komorki.wybierz_p.options[document.komorki.wybierz_p.selectedIndex].value'>";
		$sql_lista_p = "SELECT DISTINCT pion_nazwa, pion_id FROM $dbname.serwis_piony ORDER BY pion_nazwa";
		$wynik_lista_p = mysql_query($sql_lista_p,$conn) or die($k_b);
		echo "<option value='$PHP_SELF?showall=$showall&okresm=01&okresr=$okresr&paget=$page&page=$paget&wybierz_p=&wybierz=$wybierz&wybierz_typ=$wybierz_typ&wybierz_kat=$wybierz_kat&aktywne=$aktywne&ko=$ko' ";
		if ($wybierz_p=='') echo "SELECTED ";
		echo ">Wg pionu</option>";
		echo "<option ";
		
		echo "value='$PHP_SELF?showall=$showall&okresm=01&okresr=$okresr&paget=$page&page=$paget&wybierz_p=&wybierz=$wybierz&wybierz_typ=$wybierz_typ&wybierz_kat=$wybierz_kat&aktywne=$aktywne&ko=$ko'>Wszystkie</option>\n";	
		while (list($temp_pionnazwa, $temp_p_id)=mysql_fetch_array($wynik_lista_p)) {
			echo "<option "; 
			if ($wybierz_p==$temp_p_id) echo "SELECTED ";
			echo "value='$PHP_SELF?showall=$showall&okresm=01&okresr=$okresr&paget=1&page=1&wybierz_p=$temp_p_id&wybierz=$wybierz&wybierz_typ=$wybierz_typ&wybierz_kat=$wybierz_kat&aktywne=$aktywne&ko=$ko'>$temp_pionnazwa</option>\n";	
		
		}
		echo "</select>";

		echo "&nbsp;";
		echo "<select class=select_hd_p_zgloszenia name=wybierz_typ ";
		if ($_REQUEST[wybierz_typ]!='') echo " style='background-color:yellow; '"; 
		echo " onChange='document.location.href=document.komorki.wybierz_typ.options[document.komorki.wybierz_typ.selectedIndex].value'>";
		$sql_lista_p = "SELECT DISTINCT slownik_typ_komorki_opis, slownik_typ_komorki_id FROM $dbname.serwis_slownik_typ_komorki ORDER BY slownik_typ_komorki_opis";
		$wynik_lista_p = mysql_query($sql_lista_p,$conn) or die($k_b);
		echo "<option value='$PHP_SELF?showall=$showall&okresm=01&okresr=$okresr&paget=$page&page=$paget&wybierz_typ=&wybierz_p=$wybierz_p&wybierz=$wybierz&wybierz_kat=$wybierz_kat&aktywne=$aktywne&ko=$ko' ";
		if ($wybierz_typ=='') echo "SELECTED ";
		echo ">Wg typu</option>";
		echo "<option ";
		
		echo "value='$PHP_SELF?showall=$showall&okresm=01&okresr=$okresr&paget=$page&page=$paget&wybierz_typ=&wybierz_p=$wybierz_p&wybierz=$wybierz&wybierz_kat=$wybierz_kat&aktywne=$aktywne&ko=$ko'>Wszystkie</option>\n";	
		while (list($temp_typ_nazwa, $temp_typ_id)=mysql_fetch_array($wynik_lista_p)) {
			echo "<option "; 
			if ($wybierz_typ==$temp_typ_id) echo "SELECTED ";
			echo "value='$PHP_SELF?showall=$showall&okresm=01&okresr=$okresr&paget=1&page=1&wybierz_typ=$temp_typ_id&wybierz_p=$wybierz_p&wybierz=$wybierz&wybierz_kat=$wybierz_kat&aktywne=$aktywne&ko=$ko'>$temp_typ_nazwa</option>\n";	
		
		}
		echo "</select>";

		echo "&nbsp;";
		echo "<select class=select_hd_p_zgloszenia name=wybierz_kat ";
		if ($_REQUEST[wybierz_kat]!='') echo " style='background-color:yellow; '"; 
		echo " onChange='document.location.href=document.komorki.wybierz_kat.options[document.komorki.wybierz_kat.selectedIndex].value'>";
		$sql_lista_p = "SELECT DISTINCT slownik_kategoria_komorki_opis, slownik_kategoria_komorki_id FROM $dbname.serwis_slownik_kategoria_komorki ORDER BY slownik_kategoria_komorki_opis";
		$wynik_lista_p = mysql_query($sql_lista_p,$conn) or die($k_b);
		echo "<option value='$PHP_SELF?showall=$showall&okresm=01&okresr=$okresr&paget=$page&page=$paget&wybierz_kat=&wybierz_p=$wybierz_p&wybierz_typ=$wybierz_typ&wybierz=$wybierz&aktywne=$aktywne&ko=$ko' ";
		if ($wybierz_typ=='') echo "SELECTED ";
		echo ">Wg kategorii</option>";
		
		echo "<option ";
		//if ($wybierz_kat=='') echo "SELECTED ";
		echo "value='$PHP_SELF?showall=$showall&okresm=01&okresr=$okresr&paget=$page&page=$paget&wybierz_kat=&wybierz_p=$wybierz_p&wybierz_typ=$wybierz_typ&wybierz=$wybierz&aktywne=$aktywne&ko=$ko'>Wszystkie</option>\n";	
		while (list($temp_kat_nazwa, $temp_kat_id)=mysql_fetch_array($wynik_lista_p)) {
			echo "<option "; 
			if ($wybierz_kat==$temp_kat_id) echo "SELECTED ";
			echo "value='$PHP_SELF?showall=$showall&okresm=01&okresr=$okresr&paget=1&page=1&wybierz_kat=$temp_kat_id&wybierz_p=$wybierz_p&wybierz_typ=$wybierz_typ&wybierz=$wybierz&aktywne=$aktywne&ko=$ko'>$temp_kat_nazwa</option>\n";
		}
		echo "</select>";
		
		echo "&nbsp;";
		echo "<select class=select_hd_p_zgloszenia name=aktywne ";
		if ($_REQUEST[aktywne]!='') echo " style='background-color:yellow; '"; 
		echo " onChange='document.location.href=document.komorki.aktywne.options[document.komorki.aktywne.selectedIndex].value'>";
			echo "<option value='$PHP_SELF?showall=$showall&okresm=01&okresr=$okresr&paget=1&page=1&wybierz_kat=$temp_kat_id&wybierz_p=$wybierz_p&wybierz_typ=$wybierz_typ&wybierz=$wybierz&aktywne=&ko=$ko' ";
			if ($aktywne=='') echo "SELECTED ";
			echo ">Wg aktywności</option>";
			echo "<option ";
			echo "value='$PHP_SELF?showall=$showall&okresm=01&okresr=$okresr&paget=1&page=1&wybierz_kat=$temp_kat_id&wybierz_p=$wybierz_p&wybierz_typ=$wybierz_typ&wybierz=$wybierz&aktywne=&ko=$ko' ";
			echo ">Wszystkie</option>\n";
			//echo "<option ";
			//echo " value='$PHP_SELF?showall=$showall&okresm=01&okresr=$okresr&paget=1&page=1&wybierz_kat=$temp_kat_id&wybierz_p=$wybierz_p&wybierz_typ=$wybierz_typ&wybierz=$wybierz&aktywne=&ko=$ko'>Wszystkie</option>";
			echo "<option value='$PHP_SELF?showall=$showall&okresm=01&okresr=$okresr&paget=1&page=1&wybierz_kat=$temp_kat_id&wybierz_p=$wybierz_p&wybierz_typ=$wybierz_typ&wybierz=$wybierz&aktywne=1&ko=$ko' "; 
			if ($aktywne=='1') echo "SELECTED ";
			echo ">Aktywne</option>";
			echo "<option value='$PHP_SELF?showall=$showall&okresm=01&okresr=$okresr&paget=1&page=1&wybierz_kat=$temp_kat_id&wybierz_p=$wybierz_p&wybierz_typ=$wybierz_typ&wybierz=$wybierz&aktywne=0&ko=$ko' ";
			if ($aktywne=='0') echo "SELECTED ";
			echo ">Zawieszone</option>";
			echo "<option value='$PHP_SELF?showall=$showall&okresm=01&okresr=$okresr&paget=1&page=1&wybierz_kat=$temp_kat_id&wybierz_p=$wybierz_p&wybierz_typ=$wybierz_typ&wybierz=$wybierz&aktywne=2&ko=$ko' ";
			if ($aktywne=='2') echo "SELECTED ";
			echo ">Zamknięte</option>";
		echo "</select>";

		echo "&nbsp;";
		echo "<select class=select_hd_p_zgloszenia name=ko ";
		if ($_REQUEST[ko]!='') echo " style='background-color:yellow; '"; 
		echo " onChange='document.location.href=document.komorki.ko.options[document.komorki.ko.selectedIndex].value'>";
			echo "<option value='$PHP_SELF?showall=$showall&okresm=01&okresr=$okresr&paget=1&page=1&wybierz_kat=$temp_kat_id&wybierz_p=$wybierz_p&wybierz_typ=$wybierz_typ&wybierz=$wybierz&aktywne=$aktywne&ko=' ";
			if ($ko=='') echo "SELECTED ";
			echo ">Wg obsługi</option>";
			echo "<option ";
			echo " value='$PHP_SELF?showall=$showall&okresm=01&okresr=$okresr&paget=1&page=1&wybierz_kat=$temp_kat_id&wybierz_p=$wybierz_p&wybierz_typ=$wybierz_typ&wybierz=$wybierz&aktywne=$aktywne'>Wszystkie</option>";
			echo "<option value='$PHP_SELF?showall=$showall&okresm=01&okresr=$okresr&paget=1&page=1&wybierz_kat=$temp_kat_id&wybierz_p=$wybierz_p&wybierz_typ=$wybierz_typ&wybierz=$wybierz&aktywne=$aktywne&ko=1' "; 
			if ($ko=='1') echo "SELECTED ";
			echo ">kompleksowa</option>";
			echo "<option value='$PHP_SELF?showall=$showall&okresm=01&okresr=$okresr&paget=1&page=1&wybierz_kat=$temp_kat_id&wybierz_p=$wybierz_p&wybierz_typ=$wybierz_typ&wybierz=$wybierz&aktywne=$aktywne&ko=0' ";
			if ($ko=='0') echo "SELECTED ";
			echo ">pozostałe</option>";
		echo "</select>";

		echo "&nbsp;<input type=button class=buttons value='Domyślny widok' onClick=\"document.location.href='$PHP_SELF?showall=$showall&okresm=01&okresr=$okresr&paget=$page&page=$paget&wybierz_kat=&wybierz_p=&wybierz_typ=&wybierz=&aktywne=1'\" />";
		
		echo "</form>";
	endbuttonsarea();
	
	starttable();
	th("30;c;LP|;;Nazwa<br /><sub>Adres<br />Opis</sub><br />Nazwa pliku z backup'em|;c;Typ<br/><sub>Kategoria</sub>|;c;Typ usługi|20;c;<sub>Kompleksowa<br />obsługa</sub>|;c;Stempel|;;Podsieć<br />IP serwera<br /><sub>Nr WAN-portu</sub>|;;Telefon|;;Przypisanie jednostki do umowy(ów)<br />Przypisanie jednostki do załącznika|;c;Opcje",$es_prawa);
	$i = 0;
	$j = $page*$rowpersite-$rowpersite+1;
	
	$count_bez_godzin_pracy = 0;
	
	while ($newArray = mysql_fetch_array($result)) {
		$temp_id  				= $newArray['up_id'];
		$temp_nazwa				= $newArray['up_nazwa'];
		$temp_opis				= $newArray['up_opis'];
		$temp_adres				= $newArray['up_adres'];
		$temp_telefon			= $newArray['up_telefon'];
		$temp_ip				= $newArray['up_ip'];
		$temp_nrwanportu		= $newArray['up_nrwanportu'];
		$temp_stempel			= $newArray['up_stempel'];	
		$temp_belongs_to		= $newArray['belongs_to'];
		$temp_pion_id			= $newArray['up_pion_id'];
		$temp_umowa_id			= $newArray['up_umowa_id'];
		$temp_active_status 	= $newArray['up_active'];
		$temp_up_typ			= $newArray['up_typ'];
		$temp_up_kategoria		= $newArray['up_kategoria'];
		$temp_up_ko				= $newArray['up_kompleksowa_obsluga'];
		$temp_working_time		= $newArray['up_working_time'];	
		$temp_ipserwera			= $newArray['up_ipserwera'];
		
		$temp_working_time_alt	= $newArray['up_working_time_alternative'];	
		$temp_working_time_start_date	= $newArray['up_working_time_alternative_start_date'];		
		$temp_working_time_stop_date	= $newArray['up_working_time_alternative_stop_date'];		
		$temp_typ_uslugi	= $newArray['up_typ_uslugi'];
		$temp_przypisanie_jedn	= $newArray['up_przypisanie_jednostki'];
		$temp_komorka_macierzysta = $newArray['up_komorka_macierzysta_id'];
		$temp_backupname			= $newArray['up_backupname'];
		
		tbl_tr_highlight($i);
		$result1 = mysql_query("SELECT filia_nazwa FROM $dbname.serwis_filie WHERE filia_id=$temp_belongs_to", $conn) or die($k_b);
		list($temp_filia_nazwa)=mysql_fetch_array($result1);
			echo "<td class=center>";
			if ($temp_active_status=='2') echo "<font color=red>";
			if ($temp_active_status=='0') echo "<strike>";
			echo $j;
			if ($temp_active_status=='0') echo "</strike>";
			if ($temp_active_status=='2') echo "</font>";
			echo "</td>";
			$j++;
			
			list($typkomorki)=mysql_fetch_array(mysql_query("SELECT slownik_typ_komorki_opis FROM $dbname.serwis_slownik_typ_komorki WHERE slownik_typ_komorki_id=$temp_up_typ LIMIT 1", $conn));
			list($kategoriakomorki)=mysql_fetch_array(mysql_query("SELECT slownik_kategoria_komorki_opis FROM $dbname.serwis_slownik_kategoria_komorki WHERE slownik_kategoria_komorki_id=$temp_up_kategoria LIMIT 1", $conn));
			
			if ($temp_up_ko==0) $kompleksowa_obsluga = 'NIE';
			if ($temp_up_ko==1) $kompleksowa_obsluga = '<b>TAK</b>';
			
			list($pionnazwa)=mysql_fetch_array(mysql_query("SELECT pion_nazwa FROM $dbname.serwis_piony WHERE pion_id=$temp_pion_id LIMIT 1", $conn));

		td_(";w");
			echo "<a href=# class=normalfont title=' Umowa nr $umowanr | $umowaopis ' onclick=\"newWindow_r(700,600,'p_komorka_szczegoly.php?id=$temp_id')\"><b>";
			if ($temp_active_status=='0') echo "<strike>";
			if ($temp_active_status=='2') echo "<font color=red>";
			
			if ($temp_komorka_macierzysta>0) echo "<font color=#4F4F4F>";			
			if (($temp_komorka_macierzysta>0) && ($wciecie_dla_podleglych_komorek==1)) echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			
			if ($temp_up_typ==2) echo "<font color=#2D2D2D>";
			echo "".$pionnazwa." ".$temp_nazwa."";
			if ($temp_up_typ==2) echo "</font>";
			
			if ($temp_active_status=='2') echo " - komórka zamknięta";
			
			if ($temp_active_status=='2') echo "</font>";
			if ($temp_active_status=='0') echo "</strike>";
			if ($temp_komorka_macierzysta>0) echo "</font>";
			
			echo "</b></a>";
			echo "<sub>";
			if ($temp_adres!='') echo "<br />";
			if ($temp_active_status=='0') echo "<strike>";
			if ($temp_active_status=='2') echo "<font color=red>";
			if (($temp_komorka_macierzysta>0) && ($wciecie_dla_podleglych_komorek==1)) echo "&nbsp;&nbsp;&nbsp;&nbsp;";echo "".$temp_adres."";
			if ($temp_active_status=='2') echo "</font>";			
			if ($temp_active_status=='0') echo "</strike>";
			echo "</sub>";
			
			echo "<sub>";
			if ($temp_opis!='') echo "<br />";
			if ($temp_active_status=='0') echo "<strike>";
			if ($temp_active_status=='2') echo "<font color=red>";			
			if (($temp_komorka_macierzysta>0) && ($wciecie_dla_podleglych_komorek==1)) echo "&nbsp;&nbsp;&nbsp;&nbsp;";echo "".$temp_opis."";
			if ($temp_active_status=='2') echo "</font>";			
			if ($temp_active_status=='0') echo "</strike>";
			echo "</sub>";
			
			$days = explode(";",$temp_working_time);
			
			$oneday1 = explode("@",$days[0]); 
			$oneday2 = explode("@",$days[1]); 
			$oneday3 = explode("@",$days[2]); 
			$oneday4 = explode("@",$days[3]); 
			$oneday5 = explode("@",$days[4]); 
			$oneday6 = explode("@",$days[5]); 
			$oneday7 = explode("@",$days[6]); 

			$gp_sa = 1;
			if (($oneday1[1]=='') && ($oneday2[1]=='') && ($oneday3[1]=='') && ($oneday4[1]=='') && ($oneday5[1]=='') && ($oneday6[1]=='') && ($oneday7[1]=='')) $gp_sa = 0;
			
			if ($oneday1[1]=='') $oneday1[1] = '-';
			if ($oneday2[1]=='') $oneday2[1] = '-';
			if ($oneday3[1]=='') $oneday3[1] = '-';
			if ($oneday4[1]=='') $oneday4[1] = '-';
			if ($oneday5[1]=='') $oneday5[1] = '-';
			if ($oneday6[1]=='') $oneday6[1] = '-';
			if ($oneday7[1]=='') $oneday7[1] = '-';
			
			// menu z godzinami pracy
			$opis_stanow = '<table class=stany>';
			$opis_stanow.= '<tr height=24><td colspan=2 class=center style=background-color:#FFFF7F><font color=black>Godziny pracy</b></font></td></tr>';
			
			$opis_stanow .= '<tr><td class=right width=100>Poniedziałek:</td><td><b>'.$oneday1[1].'</b></td></tr>';
			$opis_stanow .= '<tr><td class=right>Wtorek</td><td><b>'.$oneday2[1].'</b></td></tr>';
			$opis_stanow .= '<tr><td class=right>Środek:</td><td><b>'.$oneday3[1].'</b></td></tr>';
			$opis_stanow .= '<tr><td class=right>Czwartek:</td><td><b>'.$oneday4[1].'</b></td></tr>';
			$opis_stanow .= '<tr><td class=right>Piątek:</td><td><b>'.$oneday5[1].'</b></td></tr>';
			$opis_stanow .= '<tr><td class=right>Sobota:</td><td><b>'.$oneday6[1].'</b></td></tr>';
			$opis_stanow .= '<tr><td class=right>Niedziela:</td><td><b>'.$oneday7[1].'</b></td></tr>';
			$opis_stanow.= '</table>';
			
			
			if (($temp_working_time_start_date!='0000-00-00') && ($temp_working_time_stop_date!='0000-00-00')) {

				$days = explode(";",$temp_working_time_alt);
				
				$oneday1a = explode("@",$days[0]); 
				$oneday2a = explode("@",$days[1]); 
				$oneday3a = explode("@",$days[2]); 
				$oneday4a = explode("@",$days[3]); 
				$oneday5a = explode("@",$days[4]); 
				$oneday6a = explode("@",$days[5]); 
				$oneday7a = explode("@",$days[6]); 

				$gpa_sa = 1;
				if (($oneday1a[1]=='') && ($oneday2a[1]=='') && ($oneday3a[1]=='') && ($oneday4a[1]=='') && ($oneday5a[1]=='') && ($oneday6a[1]=='') && ($oneday7a[1]=='')) $gpa_sa = 0;
				
				
				if ($oneday1a[1]=='') $oneday1a[1] = '-';
				if ($oneday2a[1]=='') $oneday2a[1] = '-';
				if ($oneday3a[1]=='') $oneday3a[1] = '-';
				if ($oneday4a[1]=='') $oneday4a[1] = '-';
				if ($oneday5a[1]=='') $oneday5a[1] = '-';
				if ($oneday6a[1]=='') $oneday6a[1] = '-';
				if ($oneday7a[1]=='') $oneday7a[1] = '-';
				
				
				$alt_od = date('Y')."-".substr($temp_working_time_start_date,5,5);
				$alt_do = date('Y')."-".substr($temp_working_time_stop_date,5,5);
				
				// menu z godzinami pracy
				$opis_stanow .= '<table class=stany>';
				$opis_stanow .= '<tr height=24><td colspan=2 class=center style=background-color:#FFAA7F><font color=black>Godziny pracy (alternatywne)</b><br />obowiązują od: <b>'.$alt_od.' - '.$alt_do.'</b></font></td></tr>';
				
				$opis_stanow .= '<tr><td class=right width=100>Poniedziałek:</td><td><b>'.$oneday1a[1].'</b></td></tr>';
				$opis_stanow .= '<tr><td class=right>Wtorek</td><td><b>'.$oneday2a[1].'</b></td></tr>';
				$opis_stanow .= '<tr><td class=right>Środek:</td><td><b>'.$oneday3a[1].'</b></td></tr>';
				$opis_stanow .= '<tr><td class=right>Czwartek:</td><td><b>'.$oneday4a[1].'</b></td></tr>';
				$opis_stanow .= '<tr><td class=right>Piątek:</td><td><b>'.$oneday5a[1].'</b></td></tr>';
				$opis_stanow .= '<tr><td class=right>Sobota:</td><td><b>'.$oneday6a[1].'</b></td></tr>';
				$opis_stanow .= '<tr><td class=right>Niedziela:</td><td><b>'.$oneday7a[1].'</b></td></tr>';
				$opis_stanow.= '</table>';
			
			}

			if ($temp_up_typ!=3) { 
				$tbn = $temp_backupname;
				if ($tbn=='') $temp_backupname='</b><i>';
				
				if ($temp_komorka_macierzysta>0) $temp_backupname.='<font color=#4F4F4F>';	
				if ($temp_up_typ==2) $temp_backupname.='<font color=#2D2D2D>';
				
				if ($tbn=='') $temp_backupname.= 'nie zdefiniowano';
				$temp_backupname.= '</font></i>';
				if ($temp_backupname!='') echo "<br />";
				if (($temp_komorka_macierzysta>0) && ($wciecie_dla_podleglych_komorek==1)) echo "&nbsp;&nbsp;&nbsp;&nbsp;";
				echo "<b>$temp_backupname</b>";
			}
		_td();

		echo "<td class=center>";
			if ($temp_active_status=='2') echo "<font color=red>";			
			if ($temp_active_status=='0') echo "<strike>";
			echo $typkomorki."<br /><sub>".$kategoriakomorki."</sub>";
			if ($temp_active_status=='0') echo "</strike>";
			if ($temp_active_status=='2') echo "</font>";		
		echo "</td>";
		
		$typuslugi = explode(',',$temp_typ_uslugi);
		
		$tu2a = '';
		$tu2b = '';
		if ($temp_active_status=='2') { $tu2a = "<font color=red>"; $tu2b = "</font>"; }
		if ($temp_active_status=='0') { $tu2a = "<strike>"; $tu2b = "</strike>"; }
		
		$tu = '';
		foreach ($typuslugi as $thisone) { 
			if ($thisone=='KOI') $tu.="<a title='Kompleksowa Obsługa Informatyczna' href=# class=normalfont>".$tu2a."".$thisone."".$tu2b."</a><br />"; 
			if ($thisone=='OK') $tu.="<a title='Okresowa konserwacja' href=# class=normalfont>".$tu2a."".$thisone."".$tu2b."</a><br />"; 
			if ($thisone=='UZ') $tu.="<a title='Usługi na żądanie' href=# class=normalfont>".$tu2a."".$thisone."".$tu2b."</a><br />"; 			
			if ($thisone=='UUAK') $tu.="<a title='Usługa Usuwania Awarii Krytycznych' href=# class=normalfont>".$tu2a."".$thisone."".$tu2b."</a><br />"; 
		}
		
		echo "<td class=center>";
		echo $tu;
		echo "</td>";
				
		echo "<td class=center>";
		if ($temp_active_status=='2') echo "<font color=red>";
		if ($temp_active_status=='0') echo "<strike>";
		echo $kompleksowa_obsluga;
		if ($temp_active_status=='0') echo "</strike>";
		if ($temp_active_status=='2') echo "</font>";
		echo "</td>";
		
		echo "<td class=center>";
		if ($temp_active_status=='2') echo "<font color=red>";
		if ($temp_active_status=='0') echo "<strike>";
		echo $temp_stempel;
		if ($temp_active_status=='0') echo "</strike>";
		if ($temp_active_status=='2') echo "</font>";
		echo "</td>";

		echo "<td class=left>";
		if ($temp_active_status=='2') echo "<font color=red>";
		if ($temp_active_status=='0') echo "<strike>";
		echo $temp_ip."<br />".$temp_ip.".<b>".$temp_ipserwera."</b><br /><sub>".$temp_nrwanportu."</sub>";
		if ($temp_active_status=='0') echo "</strike>";
		if ($temp_active_status=='2') echo "</font>";
		echo "</td>";

		echo "<td class=left>";
		if ($temp_active_status=='2') echo "<font color=red>";
		if ($temp_active_status=='0') echo "<strike>";
		echo $temp_telefon;
		if ($temp_active_status=='0') echo "</strike>";
		if ($temp_active_status=='2') echo "</font>";
		echo "</td>";

		td_(";;");
		$ile = count(explode(',',$temp_umowa_id));
		$umowy = explode(',',$temp_umowa_id);
		//echo $ile;
			//$umowy = explode(',',$temp_umowa_id);
			for ($v=0;$v<$ile;$v++) { 
				list($umowanr,$umowaopis)=mysql_fetch_array(mysql_query("SELECT umowa_nr,umowa_opis FROM $dbname.serwis_umowy WHERE  (umowa_id='$umowy[$v]') LIMIT 1",$conn));
				if ($temp_active_status=='2') echo "<font color=red>";
				if ($temp_active_status=='0') echo "<strike>";
				echo $umowanr." - <b>".$umowaopis."</b><br />";
				if ($temp_active_status=='0') echo "</strike>";
				if ($temp_active_status=='2') echo "</font>";
				//echo $thisone."<br />";
			}
			//*/
			
			if ($temp_przypisanie_jedn!='') {
				if ($temp_active_status=='2') echo "<font color=red>";
				if ($temp_active_status=='0') echo "<strike>";
				echo "<hr /><b>".$temp_przypisanie_jedn."</b>";
				if ($temp_active_status=='0') echo "</strike>";
				if ($temp_active_status=='2') echo "</font>";
			} else { 
				if ($temp_active_status=='2') echo "<font color=red>";
				if ($temp_active_status=='0') echo "<strike>";
				echo "<hr />-"; 
				if ($temp_active_status=='0') echo "</strike>";
				if ($temp_active_status=='2') echo "</font>";
			}
			
		_td();
		
		td_(";c");
		
			if ($temp_active_status=='2') 		
				if ($gp_sa==1) echo "<a class='normalfont title' href=# title='$opis_stanow'><input class=imgoption type=image src=img/godziny_pracy.gif></a>";

			if ($temp_active_status!='2') 		
				if ($gp_sa==1) echo "<a class='normalfont title' href=# title='$opis_stanow' onclick=\"newWindow_r(800,600,'e_komorka.php?select_id=$temp_id'); return false;\"><input class=imgoption type=image src=img/godziny_pracy.gif></a>";
	
			$result1 = mysql_query("SELECT todo_id FROM $dbname.serwis_komorka_todo WHERE (todo_up_id=$temp_id) and (belongs_to=$es_filia) and (todo_status=1)", $conn) or die($k_b);
			$lista_ilosc = mysql_num_rows($result1);
			if ($lista_ilosc>0) {
				echo "<a title=' Ilość czynności wykonanych w $temp_nazwa = $lista_ilosc '><input class=imgoption type=image src=img/czynnosc_lista.gif onclick=\"newWindow(800,400,'p_komorka_czynnosc.php?id=$temp_id')\"></a>";
			}
			
			if ($temp_active_status!='2')
				echo "<a title=' Edycja danych o $temp_nazwa '><input class=imgoption type=image src=img/edit.gif onclick=\"newWindow_r(800,600,'e_komorka.php?select_id=$temp_id'); return false;\"></a>";
				
			$accessLevels = array("9");
			if (array_search($es_prawa, $accessLevels)>-1) {
				echo "<a title=' Usuń $temp_nazwa z bazy '><input class=imgoption type=image src=img/delete.gif  onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_komorka.php?select_id=$temp_id')\"></a>";
			}
			echo "<a title=' Edytuj listę czynności do wykonania w $temp_nazwa '><input class=imgoption type=image src=img/clock_add.png onclick=\"newWindow(800,370,'p_komorka_czynnosc.php?id=$temp_id')\"></a>";
			echo "<a title=' Szczegółowe informacje o $temp_nazwa '><input class=imgoption type=image src=img/detail.gif onclick=\"newWindow_r(700,600,'p_komorka_szczegoly.php?id=$temp_id')\"></a>";
			echo "<a title=' Pokaż sprzęt będący na stanie $temp_nazwa ' href=p_ewidencja.php?action=ewid_all&view=all&sel_up=$temp_id&printpreview=0&allowback=2 title=' Pokaż sprzęt będący na stanie $temp_nazwa '><img class=imgoption src=img/software.gif border=0 width=16 width=16></a>";
		_td();
		$i++;
	_tr();
}
endtable();
$sql = "SELECT up_id FROM $dbname.serwis_komorki WHERE (up_working_time='') AND (belongs_to=$es_filia) AND (up_active=1)";
$result = mysql_query($sql, $conn) or die($k_b);
$count_bez_godzin_pracy = mysql_num_rows($result);
if ($count_bez_godzin_pracy>0) errorheader("<font style='font-weight:normal'>Ilość komórek bez zdefiniowanych godzin pracy: <b>".$count_bez_godzin_pracy."</b></font>");

include_once('paging_end.php');
} else { 
	errorheader("Baza komórek jest pusta lub brak pozycji spełniających wybrane kryteria");
}

listabaz($es_prawa,"".$pokaz_ikony."");
echo "<span style='float:left; margin-left:5px;'>";addbuttons("wstecz");echo "</span>";

if ($count_rows>0) {
	startbuttonsarea("right");
	echo "<form action=do_xls_htmlexcel_slownik_komorki.php METHOD=POST target=_blank>";		
		echo "<input type=hidden name=nr value='$es_nr'>";
		addownsubmitbutton("'Export do XLS'","refresh_");
	echo "</form>";
	endbuttonsarea();
}

startbuttonsarea("right");
$accessLevels = array("1","9");
//if(array_search($es_prawa, $accessLevels)>-1){
	
	if (($es_nr==$kierownik_nr) || ($is_dyrektor==1)) {
	
		echo "<input type=button class=buttons style='color:red' value='Komórki z błędnie wprowadzonymi godzinami pracy' onClick=\"newWindow_r(800,600,'z_komorka_bledne_godziny.php'); \" />";
		
		echo "<input type=button class=buttons style='color:blue' value='Aktualna ilość komórek w podziale na typ i kategorie' onClick=\"newWindow_r(800,600,'z_komorka_zestawienie.php'); \" /><br />";
	}
	addownlinkbutton("'Dodaj komórkę/UP'","Button1","button","newWindow_r(800,600,'d_komorka.php')");
//}
addbuttons("start");
endbuttonsarea();
include('body_stop.php');
include('js/menu.js');
?>
<script>
$(document).ready(function() { $('a.title').cluetip({splitTitle: '|'}); });
</script>
<script>HideWaitingMessage();</script>
</body>
</html>