<?php 
include_once('cfg_helpdesk.php');
include_once('header.php');

echo "<body>";

if ($obszar=='Łódź') $obszar='Lodz';

$mozna_eksportowac = 1;
$wlacz_zakres_dzienny = 1; 

if ($submit) {

//print_r($_REQUEST);

function SanitizeInput($string) { return (strtr($string, '\\/.,!@#$%^&*()_+1234567890<>?','                                                ')); }; 

if ($_REQUEST[rr]=='T') $okres = $_REQUEST[r_T_zakres];
if ($_REQUEST[rr]=='M') $okres = $_REQUEST[r_M_zakres];
if ($_REQUEST[rr]=='K') $okres = $_REQUEST[r_K_zakres];

$okres1 = str_replace('@','-',$okres);
$okres = str_replace('@',' - ',$okres);
	
if ($_REQUEST[rr]=='D') $okres = $_REQUEST[d_od]." - ".$_REQUEST[d_do];

pageheader("Raport szczegółowy za okres ze zgłoszeń (dla Poczty)",0,0);
infoheader("Okres raportowania: <b>".$okres."</b>");

echo "<br /><p align=center style='padding:5px; background-color:white; color:blue;'><span id=step1 ></span><span id=step2></span></p>";
?><script>document.getElementById('step1').innerHTML=' Trwa przygotowywanie tabel tymczasowych...&nbsp;';</script><?php ob_flush(); flush();

$zakres_dat = explode(" - ",$okres);
$data_od = $zakres_dat[0];
$data_do = $zakres_dat[1];

$dokoncz_poprzedni = false;
if ($_REQUEST[dokoncz]=='on') $dokoncz_poprzedni = true;

$filia_leader_nr = $_REQUEST[filia_leader_nr];

if ($dokoncz_poprzedni==false) {
		// tworzenie tabeli tycznasowej dla raportów
		$sql_f = "SELECT filia_leader FROM $dbname.serwis_filie WHERE filia_id=$es_filia LIMIT 1";
		$result_f = mysql_query($sql_f, $conn_hd) or die($k_b);
		$countf = mysql_num_rows($result_f);
		$fa = mysql_fetch_array($result_f);
		$filia_leader_nr = $_REQUEST[filia_leader_nr];
		
		$czyistnieje = mysql_query("SELECT * FROM $dbname_hd.hd_temp_raport_rozbudowany_$filia_leader_nr LIMIT 1", $conn_hd);
		$czyistnieje_nr = mysql_query("SELECT * FROM $dbname_hd.hd_temp_raport_rozbudowany_nr_$filia_leader_nr LIMIT 1", $conn_hd);

		if ($czyistnieje) {	
			if (($countf>0) || ($is_dyrektor)) { 
				$sql_report = "TRUNCATE TABLE $dbname_hd.hd_temp_raport_rozbudowany_$filia_leader_nr";	$result_report = mysql_query($sql_report, $conn_hd) or die($k_b);
			}
		} else { 
			if (($countf>0) || ($is_dyrektor)) { 
				$sql_report = "CREATE TABLE $dbname_hd.`hd_temp_raport_rozbudowany_$filia_leader_nr` (
				`pole1` varchar(5) collate utf8_polish_ci NOT NULL,
				`pole2` varchar(8) collate utf8_polish_ci NOT NULL,
				`pole3` varchar(8) collate utf8_polish_ci NOT NULL,
				`pole4` varchar(20) collate utf8_polish_ci NOT NULL,
				`pole5` varchar(100) collate utf8_polish_ci NOT NULL,
				`pole6` varchar(20) collate utf8_polish_ci NOT NULL,
				`pole7` varchar(1) collate utf8_polish_ci NOT NULL,
				`pole8` varchar(50) collate utf8_polish_ci NOT NULL,
				`pole9` varchar(50) collate utf8_polish_ci NOT NULL,
				`pole10` text collate utf8_polish_ci NOT NULL,
				`pole11` varchar(100) collate utf8_polish_ci NOT NULL,
				`pole12` varchar(100) collate utf8_polish_ci NOT NULL,
				`pole13` varchar(100) collate utf8_polish_ci NOT NULL,
				`pole14` varchar(100) collate utf8_polish_ci NOT NULL,
				`pole15` varchar(20) collate utf8_polish_ci NOT NULL,
				`pole16` int(10) collate utf8_polish_ci NOT NULL DEFAULT '0',
				`pole17` int(10) collate utf8_polish_ci NOT NULL DEFAULT '0',
				`pole18` varchar(3) collate utf8_polish_ci NOT NULL,
				`pole19` int(10) collate utf8_polish_ci NOT NULL DEFAULT '0',
				`pole20` text collate utf8_polish_ci NOT NULL,
				`pole21` varchar(20) collate utf8_polish_ci NOT NULL,
				`pole22` varchar(20) collate utf8_polish_ci NOT NULL,
				`pole23` varchar(20) collate utf8_polish_ci NOT NULL,
				`pole24` int(10) collate utf8_polish_ci NOT NULL DEFAULT '0',
				`pole25` int(10) collate utf8_polish_ci NOT NULL DEFAULT '0',
				`pole26` varchar(3) collate utf8_polish_ci NOT NULL,
				`pole27` int(10) collate utf8_polish_ci NOT NULL DEFAULT '0',
				`pole28` text collate utf8_polish_ci NOT NULL,
				`pole29` varchar(20) collate utf8_polish_ci NOT NULL,
				`pole30` int(10) collate utf8_polish_ci NOT NULL DEFAULT '0',
				`pole31` int(10) collate utf8_polish_ci NOT NULL DEFAULT '0',
				`pole32` int(10) collate utf8_polish_ci NOT NULL DEFAULT '0',
				`pole33` int(10) collate utf8_polish_ci NOT NULL DEFAULT '0',
				`pole34` int(10) collate utf8_polish_ci NOT NULL DEFAULT '0',
				`pole35` int(1) collate utf8_polish_ci NOT NULL DEFAULT '0',
				`pole36` varchar(30) collate utf8_polish_ci NOT NULL,
				`pole37` varchar(20) collate utf8_polish_ci NOT NULL,
				`pole38` int(10) collate utf8_polish_ci NOT NULL DEFAULT '0',
				`pole39` int(10) collate utf8_polish_ci NOT NULL DEFAULT '0',
				`pole40` int(10) collate utf8_polish_ci NOT NULL DEFAULT '0',
				`pole41` int(10) collate utf8_polish_ci NOT NULL DEFAULT '0',
				`pole42` int(10) collate utf8_polish_ci NOT NULL DEFAULT '0',
				`pole43` int(10) collate utf8_polish_ci NOT NULL DEFAULT '0',
				`pole44` int(10) collate utf8_polish_ci NOT NULL DEFAULT '0',
				`pole45` int(10) collate utf8_polish_ci NOT NULL DEFAULT '0',
				`pole46` int(10) collate utf8_polish_ci NOT NULL DEFAULT '0',
				`pole47` varchar(20) collate utf8_polish_ci NOT NULL,
				`pole50` varchar(30) collate utf8_polish_ci NOT NULL,
				`pole51` varchar(30) collate utf8_polish_ci NOT NULL,
				`pole52` varchar(30) collate utf8_polish_ci NOT NULL,
				`pole53` varchar(30) collate utf8_polish_ci NOT NULL,
				`pole54` varchar(30) collate utf8_polish_ci NOT NULL,
				`pole55` varchar(30) collate utf8_polish_ci NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci; ALTER TABLE $dbname_hd.`hd_temp_raport_rozbudowany_$filia_leader_nr` ADD INDEX ( `pole2` );";	
				
				$result_report = mysql_query($sql_report, $conn_hd) or die($k_b);	
			}
		}
		
		if ($czyistnieje_nr) {	
			if (($countf>0) || ($is_dyrektor)) { 
				$sql_report = "TRUNCATE TABLE $dbname_hd.hd_temp_raport_rozbudowany_nr_$filia_leader_nr";	
				$result_report = mysql_query($sql_report, $conn_hd) or die($k_b);		
			}
		} else { 
			if (($countf>0) || ($is_dyrektor)) { 
				$sql_report = "CREATE TABLE $dbname_hd.`hd_temp_raport_rozbudowany_nr_$filia_leader_nr` (`pole2` varchar(8) collate utf8_polish_ci NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;";	
				$result_report = mysql_query($sql_report, $conn_hd) or die($k_b);	
			}
		}

	$i = 0;

	?><script>document.getElementById('step1').innerHTML=' Trwa przygotowywanie danych do raportu...<b>0%</b>&nbsp;';</script><?php ob_flush(); flush();

	$kopiuj_1 = mysql_query("INSERT INTO $dbname_hd.hd_temp_raport_rozbudowany_nr_$filia_leader_nr (pole2) SELECT zgl_nr FROM $dbname_hd.hd_zgloszenie WHERE (zgl_data BETWEEN '$data_od' and '$data_do') and (zgl_widoczne=1) and (zgl_kategoria<>5) and ((zgl_poledodatkowe2='') or ((zgl_poledodatkowe2<>'') and (zgl_status<>1)))",$conn_hd);
	?><script>document.getElementById('step1').innerHTML=' Trwa przygotowywanie danych do raportu...<b>20%</b>&nbsp;';</script><?php ob_flush(); flush();

	$kopiuj_2 = mysql_query("INSERT INTO $dbname_hd.hd_temp_raport_rozbudowany_nr_$filia_leader_nr (pole2) SELECT zgl_nr FROM $dbname_hd.hd_zgloszenie WHERE  (zgl_czy_rozwiazany_problem=1) and (zgl_czy_rozwiazany_problem_data BETWEEN '$data_od' and '$data_do') and (zgl_widoczne=1) and (zgl_kategoria<>5) and ((zgl_poledodatkowe2='') or ((zgl_poledodatkowe2<>'') and (zgl_status<>1)))",$conn_hd);
	?><script>document.getElementById('step1').innerHTML=' Trwa przygotowywanie danych do raportu...<b>40%</b>&nbsp;';</script><?php ob_flush(); flush();

	$kopiuj_3 = mysql_query("INSERT INTO $dbname_hd.hd_temp_raport_rozbudowany_nr_$filia_leader_nr (pole2) SELECT zgl_nr FROM $dbname_hd.hd_zgloszenie WHERE  (zgl_czy_rozwiazany_problem=0) and (zgl_data_zmiany_statusu <='$data_do') and (zgl_widoczne=1) and (zgl_kategoria<>5) and (zgl_status<>'9') and ((zgl_poledodatkowe2='') or ((zgl_poledodatkowe2<>'') and (zgl_status<>1)))",$conn_hd);
	?><script>document.getElementById('step1').innerHTML=' Trwa przygotowywanie danych do raportu...<b>60%</b>&nbsp;';</script><?php ob_flush(); flush();

	$kopiuj_4 = mysql_query("INSERT INTO $dbname_hd.hd_temp_raport_rozbudowany_nr_$filia_leader_nr (pole2) SELECT zgl_nr FROM $dbname_hd.hd_zgloszenie WHERE (zgl_data_zmiany_statusu >= '$data_od') and (zgl_data_zmiany_statusu <= '$data_do') and (zgl_widoczne=1) and (zgl_kategoria<>5) and (zgl_status='9') and ((zgl_poledodatkowe2='') or ((zgl_poledodatkowe2<>'') and (zgl_status<>1)))",$conn_hd);
	?><script>document.getElementById('step1').innerHTML=' Trwa przygotowywanie danych do raportu...<b>80%</b>&nbsp;';</script><?php ob_flush(); flush();

	$kopiuj_do_glownego = mysql_query("INSERT INTO $dbname_hd.hd_temp_raport_rozbudowany_$filia_leader_nr (pole2) SELECT pole2 FROM $dbname_hd.hd_temp_raport_rozbudowany_nr_$filia_leader_nr WHERE 1 GROUP BY pole2 ASC",$conn_hd);
	?><script>document.getElementById('step1').innerHTML=' Trwa przygotowywanie danych do raportu...<b>100%</b>&nbsp;';</script><?php ob_flush(); flush();

	// wypełnienie pozostałych pól w tabeli tymczasowej
	// pole44 : zgl_naprawa_id
	$licznik = 0;

	$wybierz_zgl = "SELECT pole2 FROM $dbname_hd.hd_temp_raport_rozbudowany_$filia_leader_nr";
	$result_zgl = mysql_query($wybierz_zgl, $conn_hd) or die($k_b);
	$count_zgl = mysql_num_rows($result_zgl);

	
} else {
	
	$wybierz_zgl = "SELECT COUNT(pole1) FROM $dbname_hd.hd_temp_raport_rozbudowany_$filia_leader_nr";
	$result_zgl = mysql_query($wybierz_zgl, $conn_hd) or die($k_b);
	$count_zgl = mysql_num_rows($result_zgl);
	
	$wybierz_zgl = "SELECT COUNT(pole1) FROM $dbname_hd.hd_temp_raport_rozbudowany_$filia_leader_nr WHERE (pole1!='')";
	$result_zgl = mysql_query($wybierz_zgl, $conn_hd) or die($k_b);
	$licznik = mysql_num_rows($result_zgl);
	$percent_value = ceil($licznik * 100 / $count_zgl);
	
	$wybierz_zgl = "SELECT pole2 FROM $dbname_hd.hd_temp_raport_rozbudowany_$filia_leader_nr WHERE pole2>=$_REQUEST[lasterror]";
	$result_zgl = mysql_query($wybierz_zgl, $conn_hd) or die($k_b);
	$count_zgl = mysql_num_rows($result_zgl);

}

?><script>document.getElementById('step1').innerHTML=' Trwa uzupełnianie raportu danymi szczegółowymi...<b><?php echo $licznik; ?>%</b>&nbsp;';</script><?php ob_flush(); flush();


$filie = array(""); for ($i=0; $i<=20;$i++) $filie[$i]='';

$result6 = mysql_query("SELECT filia_id, filia_nazwa FROM $dbname.serwis_filie ORDER BY filia_nazwa", $conn) or die($k_b);				
while (list($temp_id_filii,$temp_nazwa_filii) = mysql_fetch_array($result6)) {
	//echo "$temp_id_filii,$temp_nazwa_filii<br />";
	$filie[$temp_id_filii]=$temp_nazwa_filii;
}

if ($count_zgl>0) {
	$time_all = 0;
	
	while ($newArray = mysql_fetch_array($result_zgl)) {

		$time = microtime(); $time = explode(" ", $time); $time = $time[1] + $time[0]; $start = $time;
		
		$zgl_nr 		= $newArray['pole2'];
		
		$k_b = "<h2 style='text-align:center; color:black; font-weight:normal; background-color:red; padding:10px; '>&nbsp;Wystąpił błąd podczas analizy zgłoszenia nr <b>".$zgl_nr."</b></h2><p align=center><input type=button class=buttons value='Wstecz' onclick=\"self.location.href='hd_g_statystyka_zgloszen_rozbudowany.php?okres=$_REQUEST[rr]&rok=$_REQUEST[r_rok]&q=3e4212e3e9976r9283juyh9jff90je90ijf0ij3f0-k2doijrefoik0329ok0reij0ok3er34kr3riu934r34r23efi0jcnsajsbdiweuhiweufh4234&lasterror=".$zgl_nr."';\"><input type=button class=buttons value='Pokaż zgłoszenie' onclick=\"newWindow_r(800,600,'hd_o_zgloszenia.php?action=podglad&id=".$zgl_nr."&nr=".$zgl_nr."'); return false;\"><input type=button class=buttons value='Zamknij' onclick=\"self.close();\"></p>";

		// wyzerowanie zmiennych
		$_pole12 = '';
		$_pole13 = '';
		$_pole14 = '';
		$_pole15 = '';
		$_pole16 = '';
		$_pole17 = '';
		$_pole18 = '';
		$_pole19 = '';
		$_pole20 = '';
		$_pole21 = '';
		$_pole22 = '';
		$_pole23 = '';
		$_pole24 = '';
		$_pole25 = '';
		$_pole26 = '';
		$_pole27 = '';
		$_pole28 = '';
		$_pole29 = '';
		$_pole30 = '';
		$_pole31 = '';
		$_pole32 = '';
		$_pole33 = '';
		$_pole34 = '';
		$_pole35 = '';
		
		$_pole50 = '';	// kategoria komórki
		$_pole51 = '';	// zgl_razem_czas
		$_pole52 = '';
		$_pole53 = '';
		$_pole54 = ''; // łączny czas przejazdów dla zgłoszenia
		$_pole55 = '';

		$res1 = mysql_query("SELECT zgl_typ_uslugi, zgl_nr, zgl_poczta_nr, zgl_data,zgl_godzina, zgl_komorka,zgl_przypisanie_jednostki, zgl_kategoria_komorki, zgl_osoba, zgl_podkategoria, zgl_tresc, zgl_kategoria, zgl_status, zgl_E1C,zgl_E1P,zgl_E2C,zgl_E2P,zgl_E3C,zgl_E3P,zgl_naprawa_id,zgl_rozpoczecie_min, zgl_zakonczenie_min,zgl_czy_rozwiazany_problem,zgl_razem_km,belongs_to,zgl_rozpoczecie_min, zgl_zakonczenie_min,zgl_E1C,zgl_E2C,zgl_razem_km,zgl_razem_czas,zgl_podkategoria_poziom_2,zgl_sprawdzone_osoba FROM $dbname_hd.hd_zgloszenie WHERE zgl_nr=$zgl_nr LIMIT 1") or die($k_b);
		
		//echo "1|";
		
		list($_pole1,$_pole2,$_pole3,$_pole4,$_godzina,$_pole5,$_pole6,$_pole7,$_pole8,$_pole9,$_pole10,$_pole11,$_pole21,$_pole38,$_pole39,$_pole40,$_pole41,$_pole42,$_pole43,$_pole44,$_pole45,$_pole46,$_czy_rozw,$km,$_pole47,$_pole16,$_pole24,$_pole17,$_etap2C,$razemKM,$_pole51,$_pole52,$_pole53) = mysql_fetch_array($res1);	
		//echo "2|";
	
		$_pole8 = SanitizeInput($_pole8);
		$_pole10 = str_replace('\'','`',$_pole10);
		$_pole10 = str_replace('\"','',$_pole10);
			
		if ($_pole53!='') $_pole53 = 'TAK';
		
		// jeżeli typ usługi, przypisanie lub kategoria jest pusta
		$result_upid = mysql_query("SELECT up_typ_uslugi, up_przypisanie_jednostki, up_kategoria FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (up_active=1) and (belongs_to=$_pole47) and (UPPER(CONCAT(serwis_piony.pion_nazwa,' ',serwis_komorki.up_nazwa))='$_pole5') LIMIT 1", $conn) or die($k_b);
		list($_pole1a, $_pole6a, $_pole7a)=mysql_fetch_array($result_upid);		
		//echo "3|";
		if ($_pole1=='') $_pole1 = $_pole1a;
		if ($_pole6=='') $_pole6 = $_pole6a;
		if ($_pole7=='') $_pole7 = $_pole7a;
		
		//if ($_pole5=='CP UP ŁÓDŹ 47') echo "|".$_pole1." | ".$_pole6." | ".$_pole7." | <br />";
		
		if ($_pole1=='') $_pole1 = 'KOI';
		if ($_pole6=='') $_pole6 = '2'.$obszar;
		if (($_pole7=='') || ($_pole7=='0')) $_pole7 = '9';	// żeby pokazało pozostałe
		
		// ################################
		// ##### POLE 21 , 22,23      #####
		// ################################		
		
		$_pole4 = substr($_pole4,0,16)." ".substr($_godzina,0,5);
		
		if ($_pole9=='1') $_pole9 = 'brak';
		if ($_pole9=='2') $_pole9 = 'Oprogramowanie - wsparcie użytkownika';
		if ($_pole9=='3') $_pole9 = 'Stacja robocza';
		if ($_pole9=='4') $_pole9 = 'Serwer';
		if ($_pole9=='5') $_pole9 = 'Oprogramowanie - wsparcie użytkownika';
		if ($_pole9=='6') $_pole9 = 'Aktualizacje oprogramowania';
		if ($_pole9=='7') $_pole9 = 'Oprogramowanie - problemy techniczne';
		if ($_pole9=='8') $_pole9 = 'Konserwacja sprzętu';
		if ($_pole9=='9') $_pole9 = 'Urządzenia peryferyjne';
		if ($_pole9=='0') $_pole9 = 'WAN/LAN';
		if ($_pole9=='A') $_pole9 = 'Otwarcie placówki pocztowej';
		if ($_pole9=='B') $_pole9 = 'Przeniesienie placówki pocztowej';
		if ($_pole9=='C') $_pole9 = 'Zamknięcie placówki pocztowej';
		if ($_pole9=='D') $_pole9 = 'Inne';
		if ($_pole9=='E') $_pole9 = 'Alarmy';
		if ($_pole9=='F') $_pole9 = 'Prace administracyjno-sprawozdawcze';
		if ($_pole9=='G') $_pole9 = 'Projekty';
		if ($_pole9=='H') $_pole9 = 'Kopie bezpieczeństwa';
		if ($_pole9=='I') $_pole9 = 'Domena';
		
		$zgl_kat = $_pole11;
		
		if ($_pole11=='1') $_pole11 = 'Konsultacje';
		if ($_pole11=='2') $_pole11 = 'Awarie';
		if ($_pole11=='3') $_pole11 = 'Prace zlecone w ramach umowy';
		if ($_pole11=='4') $_pole11 = 'Prace zlecone poza umową';
		if ($_pole11=='5') $_pole11 = 'Prace na potrzeby Postdata';
		if ($_pole11=='6') $_pole11 = 'Awarie krytyczne';
		if ($_pole11=='7') $_pole11 = 'Konserwacja';
			
		// sprawdzenie czy nie jest "rozwiązany"
		$_old_status = $_pole21;
		
		if ($_czy_rozw=='1') {
			$_pole21 = 'rozwiązany';
			$res1 = mysql_query("SELECT zgl_szcz_data_systemowa_rejestracji_kroku, zgl_szcz_czas_rozpoczecia_kroku,zgl_szcz_czas_wykonywania FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$zgl_nr') and (zgl_szcz_widoczne=1) and (zgl_szcz_czy_rozwiazany_problem=1) LIMIT 1") or die($k_b);			
			list($_pole22,$_pole23,$_zglszcz_czw)=mysql_fetch_array($res1);
			//echo "4|";
			$_pole22 = substr($_pole22,0,16);
			
			$_pole23 = AddMinutesToDate($_zglszcz_czw, $_pole23);
			
			$_pole23 = substr($_pole23,0,16);
			if ($_old_status=='9') $_pole21 = 'zamknięte';			
		} else {
			// statusy dla Poczty
			if ($_pole21=='1') $_pole21 = 'nowe';
			if ($_pole21=='2') $_pole21 = 'nowe';
			if ($_pole21=='3') $_pole21 = 'w trakcie realizacji';
			if ($_pole21=='3B') $_pole21 = 'w trakcie realizacji';
			if ($_pole21=='3A') $_pole21 = 'w serwisie zewnętrznym';
			if ($_pole21=='4') $_pole21 = 'oczekiwanie na odpowiedź klienta';
			if ($_pole21=='5') $_pole21 = 'w trakcie realizacji';
			if ($_pole21=='6') $_pole21 = 'w trakcie realizacji';
			if ($_pole21=='7') $_pole21 = 'w trakcie realizacji';
			if ($_pole21=='9') $_pole21 = 'zamknięte';		
		}
	
	// sprawdzenie czy jest przesunięcie terminu rozpoczęcia
	if (($zgl_kat=='2') || ($zgl_kat=='6')) {
		
		$res1 = mysql_query("SELECT zgl_szcz_przesuniety_termin_rozpoczecia,zgl_szcz_przesuniecie_data,zgl_szcz_przesuniecie_osoba FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$zgl_nr') and (zgl_szcz_widoczne=1) LIMIT 1") or die($k_b);
		
		list($_temp_czy_przesuniety, $_temp_przes_data, $_temp_przes_osoba)=mysql_fetch_array($res1);
		
		if ($_temp_czy_przesuniety == '1') {
			$_pole20 = 'Ustalona data rozpoczęcia: '.substr($_temp_przes_data,0,16).'. Osoba potwierdzająca: '.$_temp_przes_osoba.'';
		}
	
	}
	
	if (($zgl_kat=='2') || ($zgl_kat=='6') || ($zgl_kat=='3') || ($zgl_kat=='4')) {
		
		// #########################
		// ##### POLE 12 i 13 ######
		// #########################
		
		
		// jeżeli powiązana naprawa
		if ($_pole44>0) {
			$res1 = mysql_query("SELECT naprawa_nazwa, naprawa_model, naprawa_sn, naprawa_ni FROM $dbname.serwis_naprawa WHERE naprawa_id='$_pole44' LIMIT 1") or die($k_b);
			list($mnazwa,$mmodel,$msn,$mni)=mysql_fetch_array($res1);
			//echo "5|";
			$_pole12 = $msn." / ".$mni;
			$_pole13 = $mnazwa." ".$mmodel;		
			//echo "<br />|".$_pole13;
		} 
		
		if ($_pole13=='') {
			// sprawdź czy dla zgłoszenia nie ma powiązanej wymiany podzespołów
			$res1 = mysql_query("SELECT wp_sprzet_opis, pozycja_status,wp_sprzet_sn,wp_sprzet_ni FROM $dbname_hd.hd_zgl_wymiany_podzespolow, $dbname.serwis_faktura_szcz WHERE (wp_sprzedaz_fakt_szcz_id=pozycja_id) and (wp_zgl_id=$zgl_nr) LIMIT 1") or die($k_b);
			//echo "6|";
			$count_wp = mysql_num_rows($res1);
			if ($count_wp>0) {
				list($mnazwa,$poz_status,$msn,$mni)=mysql_fetch_array($res1);
			
				if ($poz_status>0) {
					$_pole12 = $msn." / ".$mni;
					$_pole13 = $mnazwa;
					$_pole35 = 'w raporcie dodatkowym';
				}
			}
		}
	}
	
		// #########################
		// ##### POLE 15      ######
		// #########################
		
		// ustalenie daty rozpoczęcia kroku o statusie >= 3
		$res1 = mysql_query("SELECT zgl_szcz_czas_rozpoczecia_kroku FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$zgl_nr') and (zgl_szcz_status<>'1') and (zgl_szcz_status<>'2') and (zgl_szcz_widoczne=1) ORDER BY zgl_szcz_nr_kroku ASC LIMIT 1") or die($k_b);
		//echo "7|";
		list($_pole15)=mysql_fetch_array($res1);
		$_pole15 = substr($_pole15,0,16);	
		
		// ######################################
		// ##### POLE 16,17,18,19,24,26,27  #####
		// ######################################
		
		// przewidywany czas reakcji (wg umowy) i przewidywany czas zakończenia | dla Awaii i Awarii krytycznych
		
//		$res1 = mysql_query("SELECT zgl_rozpoczecie_min, zgl_zakonczenie_min,zgl_E1C,zgl_E2C,zgl_razem_km FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id='$zgl_nr') and ((zgl_kategoria='2') or (zgl_kategoria='6')) and (zgl_widoczne=1) LIMIT 1") or die($k_b);
//		list($_pole16,$_pole24,$_pole17,$_etap2C,$razemKM)=mysql_fetch_array($res1);

		if ($_pole17<=$_pole16) { 
			$_pole18 = 'TAK';
			$_pole19 = '0';
		} else { 
			$_pole18 = 'NIE';
			$_pole19 = ($_pole17-$_pole16);
		}
		
		$_czas_rozwiazania = $_pole17+$etap2C;
		if ($_pole24<=$_czas_rozwiazania) {
			$_pole26 = 'TAK';
			$_pole27 = ($_czas_rozwiazania-$_pole24);
		} else {
			$_pole26 = 'NIE';
			$_pole27 = '';
		}
		
		if (($zgl_kat=='1') || ($zgl_kat=='3') || ($zgl_kat=='4') || ($zgl_kat=='7')) {
			$_pole26 = 'NIE';
			$_pole27 = '';
		}
	
		// ################################
		// ##### POLE 25              #####
		// ################################	
		
		$_etap1c = $_pole38;
		$_etap1p = $_pole39;
		$_etap2c = $_pole40;
		$_etap2p = $_pole41;
		$_etap3c = $_pole42;
		$_etap3p = $_pole43;
		
		// czas rozwiązania incydentu
//		$res1 = mysql_query("SELECT zgl_E1C,zgl_E2C,zgl_E3C,zgl_E1P,zgl_E2P,zgl_E3P FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id='$zgl_nr') and (zgl_widoczne=1) LIMIT 1") or die($k_b);
//		list($_etap1c,$_etap2c,$_etap3c,$_etap1p,$_etap2p,$_etap3p)=mysql_fetch_array($res1);
		
		$_pole25 = $_etap1c + $_etap2c;

		// ################################
		// ##### POLE 28              #####
		// ################################	
		
	$_pole36 = 'pierwszy kontakt';	
	
	if (($zgl_kat=='2') || ($zgl_kat=='6') || ($zgl_kat=='3') || ($zgl_kat=='4')) {
		
		$_pole36 = 'zdalnie';
		
		// zebranie wszystkich kroków w jedną zmienną
		$res1 = mysql_query("SELECT zgl_szcz_czas_rozpoczecia_kroku,zgl_szcz_status, zgl_szcz_wykonane_czynnosci FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$zgl_nr') and (zgl_szcz_widoczne=1) and (zgl_szcz_blokada_edycji_kroku=0) ORDER BY zgl_szcz_nr_kroku ASC") or die($k_b);
		
		while (list($_czas,$_status,$_wc)=mysql_fetch_array($res1)) {
		
			// statusy dla Poczty
			if ($_status=='1') $_status1 = 'nowe';
			if ($_status=='2') $_status1 = 'nowe';
			if ($_status=='3') $_status1 = 'w trakcie realizacji';
			if ($_status=='3B') $_status1 = 'w trakcie realizacji';
			if ($_status=='3A') $_status1 = 'w serwisie zewnętrznym';
			if ($_status=='4') $_status1 = 'oczekiwanie na odpowiedź klienta';
			if ($_status=='5') $_status1 = 'w trakcie realizacji';
			if ($_status=='6') $_status1 = 'w trakcie realizacji';
			if ($_status=='7') $_status1 = 'w trakcie realizacji';
			if ($_status=='9') $_status1 = 'zamknięte';
			
			//$_wc = str_replace('\'','`',$_wc);
			//$_wc = str_replace('\"','',$_wc);
			if (substr($_wc,0,20)!='przypisanie do osoby') 
				$_pole28 .= "".substr($_czas,0,16).",".$_status1.",".br2point($_wc)."\n\r\n\r";		
			
		}
		// ################################
		// ##### POLE 36              #####
		// ################################	
		if ($razemKM>0) $_pole36 = 'wyjazd';
		//if ($_bw==1) $_pole36 = 'wyjazd';

	} else {
		$_pole28 = $_pole4.",".$_pole21.",".$_pole10."";
	}
	
	$res1 = mysql_query("SELECT zgl_szcz_id FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$zgl_nr') and (zgl_szcz_widoczne=1) and (zgl_szcz_byl_wyjazd=1)") or die($k_b);
	list($_bw)=mysql_fetch_array($res1);
	if ($_bw>1) $_pole36 = 'wyjazd';
		
		// ################################
		// ##### POLE 29              #####
		// ################################			
		$res1 = mysql_query("SELECT zgl_szcz_czas_rozpoczecia_kroku, zgl_szcz_czas_wykonywania FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$zgl_nr') and (zgl_szcz_widoczne=1) and (zgl_szcz_status=9) LIMIT 1") or die($k_b);
		list($_pole29,$_pole29_cw)=mysql_fetch_array($res1);
		//echo "10|";
		if ($_pole29!='') {
			$_pole29 = AddMinutesToDate($_pole29_cw, $_pole29); // <-- new (02.07.2012)		
			$_pole29 = substr($_pole29,0,16);
		} else $_pole29 = '';
		
		// ################################
		// ##### POLE 30              #####
		// ################################		
		$_pole30 = $_etap3c;
		
		//echo "$_etap1c + $_pole40 + $_etap3c (old)   | $_pole17 + $_pole25 + $_pole30 (new) <br />";
		$_pole31 = $_etap1c + $_etap2c + $_etap3c; // <--- old
		//$_pole31 = $_pole17 + $_pole25 + $_pole30;	// <--- new
		
		//$_pole32 = $_pole17 + $_pole25 + $_pole30;
		//echo $_pole17." ".$_pole25." ".$_pole30." = ".$_pole32."<br />";
		
		$_pole32 = $_etap1p;
		$_pole33 = $_etap2p;
		$_pole34 = $_etap3p;
		
		
		//$res1 = mysql_query("SELECT filia_nazwa FROM $dbname.serwis_filie WHERE (filia_id='$_pole47') LIMIT 1") or die($k_b);
		//list($_fname)=mysql_fetch_array($res1);		

		$_fname = $filie[$_pole47];
		$_pole47 = $_fname;
		
		// łączny czas poświęcony na przejazdy
		$_pole54 = 0;
		$res1 = mysql_query("SELECT SUM(zgl_szcz_czas_trwania_wyjadu) FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$zgl_nr') and (zgl_szcz_widoczne=1)") or die($k_b);
		list($_pole54)=mysql_fetch_array($res1);		
		
		$_pole28 = str_replace('\'','`',$_pole28);
		$_pole28 = str_replace('\"','',$_pole28);
		$_pole28 = str_replace('\\','/',$_pole28);
		$_pole10 = str_replace('\\','/',$_pole10);
		
		// uaktualnij tabelę tymczasową
		$sql_update = "UPDATE $dbname_hd.hd_temp_raport_rozbudowany_$filia_leader_nr SET pole1='".$_pole1."',pole3='".$_pole3."',pole4='".$_pole4."',pole5='".$_pole5."',pole6='".$_pole6."',pole7='".$_pole7."',pole8='".$_pole8."',pole9='".$_pole9."',pole10='".$_pole10."',pole11='".$_pole11."',pole21='".$_pole21."',pole38='".$_pole38."',pole39='".$_pole39."',pole40='".$_pole40."',pole41='".$_pole41."',pole42='".$_pole42."',pole43='".$_pole43."',pole44='".$_pole44."',pole45='".$_pole45."',pole46='".$_pole46."',pole12='".$_pole12."', pole13='".$_pole13."', pole14='".$_pole14."', pole15='".$_pole15."', pole16='".$_pole16."', pole17='".$_pole17."', pole18='".$_pole18."', pole19='".$_pole19."', pole20='".$_pole20."', pole21='".$_pole21."', pole22='".$_pole22."', pole23='".$_pole23."', pole24='".$_pole24."', pole25='".$_pole25."', pole26='".$_pole26."', pole27='".$_pole27."', pole28='".$_pole28."', pole29='".$_pole29."', pole30='".$_pole30."', pole31='".$_pole31."', pole32='".$_pole32."', pole33='".$_pole33."', pole34='".$_pole34."', pole35='".$_pole35."', pole36='".$_pole36."', pole47='".$_pole47."', pole50='".$_pole7."', pole51='".$_pole51."', pole52='".$_pole52."', pole53='".$_pole53."', pole54='".$_pole54."', pole55='".$_pole55."' WHERE (pole2='$zgl_nr') LIMIT 1";
		
		$update_zgl = mysql_query($sql_update, $conn_hd) or die($k_b);
		$percent_value = ceil($licznik++ * 100 / $count_zgl);
		
		$time = microtime(); $time = explode(" ", $time);$time = $time[1] + $time[0]; $finish = $time; $totaltime = ($finish - $start);
		
		$time_all+=$totaltime;
		$time_avg = $time_all / $licznik;
		
		$sec_to_end = ceil($time_avg * ($count_zgl-$licznik));
		
		$__time = $sec_to_end;
		$__sec = ($__time % 60);
		$__time = ($__time - $__sec) / 60;
		$__min = $__time % 60;
		$__hour = ($__time - $__min) / 60; 
		
		if ($__min<10) $__min = "0".$__min;
		if ($__hour<10) $__hour = "0".$__hour;
		
		?><script>document.getElementById('step1').innerHTML=' Trwa uzupełnianie raportu danymi szczegółowymi...<b><?php echo $percent_value; ?>%</b> | Pozostało do uzupełnienia <?php echo ($count_zgl-$licznik);?> zgłoszeń<br /><br /><font color=grey>Przewidywany czas zakończenia generowania raportu: <b><?php echo date("Y-m-d H:i",strtotime(date("Y-m-d H:i"))+$sec_to_end); ?></b> (<?php echo $__hour."h ".$__min."m"; ?>)</font>';</script><?php
		ob_flush();
		flush();

	}

	?><script>document.getElementById('step1').innerHTML=' Raport został wygenerowany | ilość pozycji: <b><?php echo $count_zgl; ?></b>&nbsp;'; //document.getElementById('ex3').style.display='';</script><?php
	ob_flush();
	flush();

}

	echo "<form action=do_xls_htmlexcel_hd_g_statystyka_rozbudowany.php METHOD=POST target=_blank>";		
		echo "<input type=hidden name=okres value='$data_od-$data_do'>";
		echo "<input type=hidden name=obszar value='$obszar'>";	
		echo "<input type=hidden name=rok value='$_REQUEST[r_rok]'>";
		echo "<input type=hidden name=opis_raportu value='raport_szczegolowy'>";
		echo "<input type=hidden name=filia_leader_nr value='$filia_leader_nr'>";
		echo "<input type=hidden name=addfilia value='$_REQUEST[addfilia]'>";
		echo "<input type=hidden name=add_time_and_kategoria value='$_REQUEST[add_time_and_kategoria]'>";
		
	echo "<br />";
	
	startbuttonsarea("right");
	echo "<span style='float:left'>";
	echo "<input type=button class=buttons value='Zmień zakres danych do raportu'  onClick=\"self.location='hd_g_statystyka_zgloszen_rozbudowany.php?okres=".$_REQUEST[rr]."&rok=".$_REQUEST[r_rok]."&q=3e4212e3e9976r9283juyh9jff90je90ijf0ij3f0-k2doijrefoik0329ok0reij0ok3er34kr3riu934r34r23efi0jcnsajsbdiweuhiweufh4234&d1=".$_REQUEST[d_od]."&d2=".$_REQUEST[d_do]."&d3=".$_REQUEST[r_T_zakres]."&d4=".$_REQUEST[r_M_zakres]."&d5=".$_REQUEST[r_K_zakres]."'; \" />";
	
	echo "<input type=button class=buttons value='Wygeneruj raport ponownie' onClick=\"self.location.reload(true); \" />";
	echo "</span>";
	
	addownsubmitbutton("'Generuj plik XLS'","refresh_");
	addbuttons("zamknij");

	endbuttonsarea();
	echo "</form>";

} else { ?>

<?php
$ok = 0;
$sql 	= "SELECT filia_leader FROM $dbname.serwis_filie WHERE filia_id=$es_filia";
$result = mysql_query($sql, $conn) or die($k_b);
while ($newArray = mysql_fetch_array($result)) {
	$temp_leader	= $newArray['filia_leader'];
	if ($temp_leader==$es_nr) $ok = 1;
}

function getFirstDayOfWeek($year, $weeknr) {
    $offset = date('w', mktime(0,0,0,1,1,$year));
    $offset = ($offset < 5) ?1-$offset : 8-$offset;
    $monday = mktime(0,0,0,1,1+$offset,$year);
    $date = strtotime('+' . ($weeknr- 1) . ' weeks', $monday);
    return date('Y-m-d',$date);
}

function getLastDayOfWeek($year, $weeknr) {
    $offset = date('w', mktime(0,0,0,1,1,$year));
    $offset = ($offset < 5) ?1-$offset : 8-$offset;
    $monday = mktime(0,0,0,1,1+$offset,$year);
    $date = strtotime('+' . ($weeknr) . ' weeks -1 day', $monday);
    return date('Y-m-d',$date);

}

pageheader("Generowanie raportu szczegółowego za okres (dla Poczty) - wersja rozbudowana");

?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();

echo "<form name=ruch action=hd_g_statystyka_zgloszen_rozbudowany.php method=POST>";

	echo "<table cellspacing=1 align=center style=width:400px>";
	tbl_empty_row(1);

	$Rok_Min = '2011';

	echo "<tr>";
		echo "<td class=center colspan=2>";		
			echo "Raport dla roku:&nbsp;";

			if ($_REQUEST[rok]!='') { $Rok_Sel = $_REQUEST[rok]; } else { $Rok_Sel = date('Y'); }
			$Rok_Curr = date('Y');			
			$Lat_Wstecz = $Rok_Curr - $Rok_Min;
			
			echo "<select name=r_rok onChange=\"self.location='hd_g_statystyka_zgloszen_rozbudowany.php?okres=".$_REQUEST[okres]."&rok='+this.value+'';\">";
				for ($r=$Rok_Curr-$Lat_Wstecz; $r<=$Rok_Curr; $r++) { 
					echo "<option value=$r "; if ($Rok_Sel==$r) echo "SELECTED"; echo ">$r</option>\n";
				}
			echo "</select>";
		echo "</td>";
	echo "</tr>";
	
	tbl_empty_row(1);
		
	if ($wlacz_zakres_dzienny==1) {
	echo "<tr height=30>";

		echo "<td>";
			echo "<input type=radio name=rr id=rr_D value='D'"; if ($_REQUEST[okres]=='D') echo " checked "; echo " onClick=\"self.location='hd_g_statystyka_zgloszen_rozbudowany.php?okres=D&rok=".$_REQUEST[rok]."&q=".$_REQUEST[q]."&d1=".$_REQUEST[d1]."&d2=".$_REQUEST[d2]."&lasterror=".$_REQUEST[lasterror]."'; \"><a class=normalfont href=# onClick=\"document.getElementById('rr_D').checked=true; self.location='hd_g_statystyka_zgloszen_rozbudowany.php?okres=D&rok=".$_REQUEST[rok]."&q=".$_REQUEST[q]."&d1=".$_REQUEST[d1]."&d2=".$_REQUEST[d2]."&lasterror=".$_REQUEST[lasterror]."'; \">Dzienny (od..do)</a>";
		echo "</td>";

		echo "<td class=left "; 
		if ($_REQUEST[okres]=='D') { echo " style='display:' "; } else { echo " style='display:none' "; }
		echo ">";
		
		if ($_REQUEST[d1]!='') { $d1_value = $_REQUEST[d1]; } else { $d1_value = date('Y-m-d'); }
		if ($_REQUEST[d2]!='') { $d2_value = $_REQUEST[d2]; } else { $d2_value = date('Y-m-d'); }
		
			echo "<input type=text size=8 maxlength=10 name=d_od id=d_od value='".$d1_value."'>";
			echo " ... ";
			echo "<input type=text size=8 maxlength=10 name=d_do id=d_do value='".$d2_value."'>";
		echo "</td>";		
	echo "</tr>";	
	
	}
	
	echo "<tr height=30>";
		echo "<td>";
			echo "<input type=radio name=rr id=rr_T value='T'"; if ($_REQUEST[okres]=='T') echo " checked "; echo " onClick=\"self.location='hd_g_statystyka_zgloszen_rozbudowany.php?okres=T&rok=".$_REQUEST[rok]."&q=".$_REQUEST[q]."&d1=".$_REQUEST[d1]."&d2=".$_REQUEST[d2]."&lasterror=".$_REQUEST[lasterror]."'; \"><a class=normalfont href=# onClick=\"document.getElementById('rr_T').checked=true; self.location='hd_g_statystyka_zgloszen_rozbudowany.php?okres=T&rok=".$_REQUEST[rok]."&q=".$_REQUEST[q]."&d1=".$_REQUEST[d1]."&d2=".$_REQUEST[d2]."&lasterror=".$_REQUEST[lasterror]."'; \">Tygodniowy</a>";
		echo "</td>";

		if ($Rok_Curr == $Rok_Sel) { $Max_Week_Nr = date('W'); } else { 
			$Max_Week_Nr = date("W", mktime(0,0,0,12,28,$Rok_Sel));
		}
		
		$Week_nr = date('W');
		
		echo "<td>"; 
		echo "<span ";
		if ($_REQUEST[okres]=='T') { echo " style='display:' "; } else { echo " style='display:none' "; }
		echo ">";

			echo "<select name=r_T_zakres>";
				for ($r=1; $r<=$Max_Week_Nr; $r++) { 
					echo "<option value='".getFirstDayOfWeek($_REQUEST[rok],$r)."@".getLastDayOfWeek($_REQUEST[rok],$r)."'";
					if (($Week_nr==$r) && ($Rok_Curr == $Rok_Sel)) echo " SELECTED ";
					echo ">".getFirstDayOfWeek($_REQUEST[rok],$r)." - ".getLastDayOfWeek($_REQUEST[rok],$r)."</option>\n";
				}
			echo "</select>";			
		
		echo "</span>";
		echo "</td>";
	echo "</tr>";
	
	echo "<tr height=30>";
		echo "<td>";
			echo "<input type=radio name=rr id=rr_M value='M'"; if ($_REQUEST[okres]=='M') echo " checked "; echo " onClick=\"self.location='hd_g_statystyka_zgloszen_rozbudowany.php?okres=M&rok=".$_REQUEST[rok]."&q=".$_REQUEST[q]."&d1=".$_REQUEST[d1]."&d2=".$_REQUEST[d2]."&lasterror=".$_REQUEST[lasterror]."'; \"><a class=normalfont href=# onClick=\"document.getElementById('rr_M').checked=true; self.location='hd_g_statystyka_zgloszen_rozbudowany.php?okres=M&rok=".$_REQUEST[rok]."&q=".$_REQUEST[q]."&d1=".$_REQUEST[d1]."&d2=".$_REQUEST[d2]."&lasterror=".$_REQUEST[lasterror]."'; \">Miesięczny</a>";
		echo "</td>";

		echo "<td>"; 
		echo "<span ";
		if ($_REQUEST[okres]=='M') { echo " style='display:' "; } else { echo " style='display:none' "; }
		echo ">";

		$Miesiac_Curr = date('m');
		
			echo "<select name=r_M_zakres>";
				echo "<option value='".$Rok_Sel."-01-01@".$Rok_Sel."-01-31' "; 
				if (($Miesiac_Curr=='01') && ($Rok_Curr == $Rok_Sel)) echo "SELECTED"; 
				echo ">Styczeń</option>\n";
				
				echo "<option value='".$Rok_Sel."-02-01@".$Rok_Sel."-02-29' "; 
				if (($Miesiac_Curr=='02') && ($Rok_Curr == $Rok_Sel)) echo "SELECTED"; 
				echo ">Luty</option>\n";
				
				echo "<option value='".$Rok_Sel."-03-01@".$Rok_Sel."-03-31' "; 
				if (($Miesiac_Curr=='03') && ($Rok_Curr == $Rok_Sel)) echo "SELECTED"; 
				echo ">Marzec</option>\n";
				
				echo "<option value='".$Rok_Sel."-04-01@".$Rok_Sel."-04-30' "; 
				if (($Miesiac_Curr=='04') && ($Rok_Curr == $Rok_Sel)) echo "SELECTED"; 
				echo ">Kwiecień</option>\n";
				
				echo "<option value='".$Rok_Sel."-05-01@".$Rok_Sel."-05-31' "; 
				if (($Miesiac_Curr=='05') && ($Rok_Curr == $Rok_Sel)) echo "SELECTED"; 
				echo ">Maj</option>\n";
				
				echo "<option value='".$Rok_Sel."-06-01@".$Rok_Sel."-06-30' "; 
				if (($Miesiac_Curr=='06') && ($Rok_Curr == $Rok_Sel)) echo "SELECTED"; 
				echo ">Czerwiec</option>\n";
				
				echo "<option value='".$Rok_Sel."-07-01@".$Rok_Sel."-07-31' "; 
				if (($Miesiac_Curr=='07') && ($Rok_Curr == $Rok_Sel)) echo "SELECTED"; 
				echo ">Lipiec</option>\n";
				
				echo "<option value='".$Rok_Sel."-08-01@".$Rok_Sel."-08-31' "; 
				if (($Miesiac_Curr=='08') && ($Rok_Curr == $Rok_Sel)) echo "SELECTED"; 
				echo ">Sierpień</option>\n";
				
				echo "<option value='".$Rok_Sel."-09-01@".$Rok_Sel."-09-30' "; 
				if (($Miesiac_Curr=='09') && ($Rok_Curr == $Rok_Sel)) echo "SELECTED"; 
				echo ">Wrzesień</option>\n";
				
				echo "<option value='".$Rok_Sel."-10-01@".$Rok_Sel."-10-31' "; 
				if (($Miesiac_Curr=='10') && ($Rok_Curr == $Rok_Sel)) echo "SELECTED"; 
				echo ">Październik</option>\n";
				
				echo "<option value='".$Rok_Sel."-11-01@".$Rok_Sel."-11-30' "; 
				if (($Miesiac_Curr=='11') && ($Rok_Curr == $Rok_Sel)) echo "SELECTED"; 
				echo ">Listopad</option>\n";
				
				echo "<option value='".$Rok_Sel."-12-01@".$Rok_Sel."-12-31' "; 
				if (($Miesiac_Curr=='12') && ($Rok_Curr == $Rok_Sel)) echo "SELECTED"; 
				echo ">Grudzień</option>\n";
				
			echo "</select>";
		echo "</span>";
		echo "&nbsp;";
		echo "</td>";		
	echo "</tr>";

	echo "<tr height=30>";

		echo "<td>";
			echo "<input type=radio name=rr id=rr_K value='K'"; if ($_REQUEST[okres]=='K') echo " checked "; echo " onClick=\"self.location='hd_g_statystyka_zgloszen_rozbudowany.php?okres=K&rok=".$_REQUEST[rok]."&q=".$_REQUEST[q]."&d1=".$_REQUEST[d1]."&d2=".$_REQUEST[d2]."&lasterror=".$_REQUEST[lasterror]."'; \"><a class=normalfont href=# onClick=\"document.getElementById('rr_K').checked=true; self.location='hd_g_statystyka_zgloszen_rozbudowany.php?okres=K&rok=".$_REQUEST[rok]."&q=".$_REQUEST[q]."&d1=".$_REQUEST[d1]."&d2=".$_REQUEST[d2]."&lasterror=".$_REQUEST[lasterror]."'; \">Kwartalny</a>";
		echo "</td>";

		echo "<td>"; 
		echo "<span ";
		if ($_REQUEST[okres]=='K') { echo " style='display:' "; } else { echo " style='display:none' "; }
		echo ">";
		
			$Miesiac_Curr = date('m');
			
			if ((($Miesiac_Curr=='01') || ($Miesiac_Curr=='02') || ($Miesiac_Curr=='03')) && ($Rok_Curr == $Rok_Sel)) $kw_nr = 1;
			if ((($Miesiac_Curr=='04') || ($Miesiac_Curr=='05') || ($Miesiac_Curr=='06')) && ($Rok_Curr == $Rok_Sel)) $kw_nr = 2;
			if ((($Miesiac_Curr=='07') || ($Miesiac_Curr=='08') || ($Miesiac_Curr=='09')) && ($Rok_Curr == $Rok_Sel)) $kw_nr = 3;
			if ((($Miesiac_Curr=='10') || ($Miesiac_Curr=='11') || ($Miesiac_Curr=='12')) && ($Rok_Curr == $Rok_Sel)) $kw_nr = 4;
			
			echo "<select name=r_K_zakres>";
				echo "<option value='".$Rok_Sel."-01-01@".$Rok_Sel."-03-31' "; if ($kw_nr==1) echo "SELECTED"; echo ">I kwartał</option>\n";
				echo "<option value='".$Rok_Sel."-04-01@".$Rok_Sel."-06-30' "; if ($kw_nr==2) echo "SELECTED"; echo ">II kwartał</option>\n";
				echo "<option value='".$Rok_Sel."-07-01@".$Rok_Sel."-09-30' "; if ($kw_nr==3) echo "SELECTED"; echo ">III kwartał</option>\n";
				echo "<option value='".$Rok_Sel."-10-01@".$Rok_Sel."-12-31' "; if ($kw_nr==4) echo "SELECTED"; echo ">IV kwartał</option>\n";
				
			echo "</select>";
			echo "</span>";
			echo "&nbsp;";
		echo "</td>";		
	echo "</tr>";
	
	tbl_empty_row(2);
	endtable();

	startbuttonsarea("center");
	
	echo "<p align=center>";
		echo "<input class=border0 type=checkbox name=addfilia id=addfilia checked=checked />";
		echo "<a href=# class=normalfont onClick=\"if (document.getElementById('addfilia').checked) { document.getElementById('addfilia').checked=false; } else { document.getElementById('addfilia').checked=true; }\"> wersja do kontroli (dodatkowa kolumna z nazwą filii oraz informacją czy zgłoszenie było sprawdzone przez przełożonego)&nbsp;</a>";
	echo "</p>";
	echo "<br />";

	echo "<p align=center>";
		echo "<input class=border0 type=checkbox name=add_time_and_kategoria id=add_time_and_kategoria checked=checked />";
		echo "<a href=# class=normalfont onClick=\"if (document.getElementById('add_time_and_kategoria').checked) { document.getElementById('add_time_and_kategoria').checked=false; } else { document.getElementById('add_time_and_kategoria').checked=true; }\"> <font color=blue>dodaj kolumnę z kategorią komórki i łącznym czasem poświęconym na zgłoszenie</font>&nbsp;</a>";
	echo "</p>";
	echo "<br />";
	
	if ($_REQUEST[lasterror]!='') {
		infoheader("Poprzednio generowany raport nie został zakończony. <input class=border0 type=checkbox name=dokoncz id=dokoncz checked=checked /><a href=# class=normalfont onClick=\"if (document.getElementById('dokoncz').checked) { document.getElementById('dokoncz').checked=false; } else { document.getElementById('dokoncz').checked=true; }\"> Dokończ poprzedni raport</a>");
		echo "<input type=hidden name=lasterror value=$_REQUEST[lasterror] />";
		
		echo "<p align=center>";
		echo "<br />";
		addownsubmitbutton("'Generuj raport'","submit");
		
		echo "</p>";
		
		
	} else {
		echo "<br />";
		addownsubmitbutton("'Generuj raport'","submit");
		
		echo "<input type=hidden name=lasterror value='' />";
	}

	echo "<span style='float:right'>";
	addbuttons('zamknij');
	echo "</span>";
	
	echo "<input type=hidden name=filia_leader_nr value='$es_filia' />";
	
	endbuttonsarea();

	_form();	
	
if ($currentuser==$adminname) {
	echo "<div id=adm>";
	echo "<hr />";
	echo "&nbsp;<b>Tylko dla administratora: </b><hr /><br /><form action=do_xls_htmlexcel_hd_g_statystyka_rozbudowany.php METHOD=POST target=_blank style='display:inline;'>";
		echo "<input type=hidden name=okres value='XXXX-XX-XX'>";
		echo "<input type=hidden name=obszar value='$obszar'>";	
		echo "<input type=hidden name=rok value='XXXX'>";
		echo "<input type=hidden name=opis_raportu value='raport_szczegolowy'>";
		
		echo "<input type=hidden name=addfilia value='on'>";
		echo "<input type=hidden name=add_time_and_kategoria value='$_REQUEST[add_time_and_kategoria]'>";
			
		echo "&nbsp;Raport wygenerowany przez: ";
		$result6 = mysql_query("SELECT user_first_name, user_last_name, belongs_to FROM $dbname.serwis_uzytkownicy WHERE (user_locked=0) and (user_dyrektor=1) ORDER BY user_last_name", $conn) or die($k_b);
		list($temp_imie,$temp_nazwisko,$temp_bt) = mysql_fetch_array($result6);
		
		$cnt = 0;
		echo "<select name=filia_leader_nr onkeypress='return handleEnter(this, event);'>\n"; 					 				
		
		$czyistnieje = mysql_query("SELECT * FROM $dbname_hd.hd_temp_raport_rozbudowany_$temp_bt LIMIT 1", $conn_hd);
		
		if ($czyistnieje) {
			$res3 = mysql_query("SELECT pole1 FROM $dbname_hd.hd_temp_raport_rozbudowany_$temp_bt",$conn_hd);
			$ile_rec = mysql_num_rows($res3);
			$cnt++;	
			echo "<option value='$temp_bt' SELECTED>$temp_imie $temp_nazwisko | $ile_rec pozycji</option>";
			echo "<option value='$temp_bt'></option>";
		}
		
		$result6 = mysql_query("SELECT user_first_name, user_last_name, belongs_to FROM $dbname.serwis_uzytkownicy, $dbname.serwis_filie WHERE (user_locked=0) and (user_id=filia_leader) ORDER BY user_last_name", $conn) or die($k_b);		
		while (list($temp_imie,$temp_nazwisko,$temp_bt) = mysql_fetch_array($result6)) {
		
			$czyistnieje = mysql_query("SELECT * FROM $dbname_hd.hd_temp_raport_rozbudowany_$temp_bt LIMIT 1", $conn_hd);
			if ($czyistnieje) { 
				
				$res3 = mysql_query("SELECT pole1 FROM $dbname_hd.hd_temp_raport_rozbudowany_$temp_bt",$conn_hd);
				$ile_rec = mysql_num_rows($res3);
				
				echo "<option value='$temp_bt'"; 
				$iin = $temp_imie.' '.$temp_nazwisko;
				$all_from_filia .= "'".$iin ."',";
			
				if ($_GET[tuser]==$iin) echo " SELECTED "; echo ">$temp_imie $temp_nazwisko | $ile_rec pozycji</option>\n"; 
				$cnt++;
			}
			
		}
		echo "</select>\n";

		if ($cnt>0) addownsubmitbutton("'Generuj plik XLS z raportu wygenerowanego przez wybranego użytkownika'","refresh_");
		
		endbuttonsarea();
	echo "</form>";
	
	echo "</div>";
?>
<script>
<?php if ($cnt==0) { ?>document.getElementById('adm').style.display='none';<? } ?>
</script>
<?php	
	}
	
}
// else errorheader("Funkcja dostępna tylko dla kierowników zespołów");

/*
pkt.1 :

SELECT * FROM hd_zgloszenie, hd_zgloszenie_szcz WHERE (SUBSTRING(zgl_szcz_czas_rozpoczecia_kroku,1,10) BETWEEN '2011-02-07' and '2011-02-13') and (zgl_szcz_nr_kroku=1) and (zgl_szcz_zgl_id=zgl_id) and (zgl_widoczne=1) and (zgl_poledodatkowe2='') and (zgl_kategoria<>5)


pkt.2 : 
SELECT * FROM hd_zgloszenie, hd_zgloszenie_szcz WHERE (SUBSTRING(zgl_szcz_czas_rozpoczecia_kroku,1,10) BETWEEN '2011-02-07' and '2011-02-13') and  (zgl_szcz_zgl_id=zgl_id) and (zgl_widoczne=1) and (zgl_poledodatkowe2='') and (zgl_kategoria<>5) and (zgl_szcz_status=9) and (zgl_data BETWEEN '2011-02-07' and '2011-02-13')

pkt.3 :
???
SELECT DISTINCT(zgl_szcz_zgl_id) FROM hd_zgloszenie, hd_zgloszenie_szcz WHERE (SUBSTRING(zgl_szcz_czas_rozpoczecia_kroku,1,10) BETWEEN '2011-02-07' and '2011-02-13') and  (zgl_szcz_zgl_id=zgl_id) and (zgl_widoczne=1) and (zgl_poledodatkowe2='') and (zgl_kategoria<>5) and (zgl_szcz_status<>9) and (zgl_data BETWEEN '2011-02-07' and '2011-02-13')


pkt.4 :
SELECT * FROM hd_zgloszenie, hd_zgloszenie_szcz WHERE (SUBSTRING(zgl_szcz_czas_rozpoczecia_kroku,1,10) BETWEEN '2011-02-07' and '2011-02-13') and  (zgl_szcz_zgl_id=zgl_id) and (zgl_widoczne=1) and (zgl_poledodatkowe2='') and (zgl_kategoria<>5) and (zgl_szcz_status<>9) and (zgl_data<'2011-02-07')



*/

?>

<script>HideWaitingMessage();</script>

</body>
</html>

