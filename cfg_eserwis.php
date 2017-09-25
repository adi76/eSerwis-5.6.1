<?php
include('cfg_wersja.php');
include('cfg_adres.php');

// ***************************** USTAWIENIA GŁÓWNE - START *****************************
$debug 												= false;

global $linkdostronyglownej;
$idw_dla_zbh_testowa								= FALSE;	// Czy dana wersja jest TESTOWA
$idw_dla_zbh										= 30;	// ilosc dni roboczych wstecz dla ktorych mozna rejestrowac zgloszenia w bazie Helpdesk
if (!$idw_dla_zbh_testowa) $idw_dla_zbh				= 30;
$zerowe_czasy_wykonania								= FALSE;  // czy ignorować zerowe czasy wykonywania czynności dla rejestrowanych zgłoszeń
$wersja_programu 									= $wersja;
$nazwa_aplikacji 									= "eSerwis ".$wersja_programu;
$linkdostronyglownej 								= $linkdostrony."main.php";
$sciezka_do_logow_z_kopiami							= "KopieZUP";
$splt												= 0;	// pokazuj ile czasu zajęło wygenerowanie strony
$numeruj_protokoly									= 1; 	// czy mają być numerowane protokły  (numeracja jest miesięczna)
$http_refresh										= 10;	// ilosc sekund oczekiwania zanim przeladowane bedzie okno z brakiem rezultatow zapytania
$upload_plikow										= 1;	// czy ma być możliwy upload plików (baza wiedzy, kroki zgłoszenia, 
$_today												= 1; 	// czy pokazywać ikonkę umożliwiającą wpisanie daty bieżącej (obok pól z datami)
$potwierdzaj_submit									= 1; 	// czy pokazywać okienko z pytaniem o poprawność wprowadzonych danych
$potwierdzaj_close									= 1;	// czy pokazywać okienko z pytaniem przed zamknięciem lub anulowaniem
$nr_telefonu_do_tpsa 								= "0 800 120 810";
$nr_klienta 										= "93358";
$wskaznik_marzy										= 1.08; // 8%
$useDateMask										= 0;	// czy uĹĽywać maski ####-##-## do wprowadzania dat w systemie
$dialog_window_x									= 600;
$dialog_window_y									= 135;
$ClueTipOn											= 1;	// czy mają się generować zmodyfikowane dymki
$wylacz_przyjmowanie_uszk_sprzetu_z_menu_glownego	= 1;
$wylacz_rejestracje_awarii_wan_z_menu_glownego 		= 1;
$wylacz_tworzenie_protokolow_z_menu_glownego 		= 1;
$powiaz_wymiane_podzespolow_ze_sprzedaza 			= 0;
$PTPNPZPM											= 1; // pokaz_typy_podzespolow_nie_powiazane_z_pozycja_magazynowa
$GetLicznikFrom										= 'zgloszenia'; // 'statystyka'
$k_b												= "<h2 style='text-align:left; color:white; font-weight:normal; background-color:red; padding:10px; '>&nbsp;Wystąpił błąd komunikacji lub wynik zapytania do bazy zwrócił błąd.<span style='float:right;margin-top:-3px'><input type=button class=buttons value='Wstecz' onclick=\"history.go(-1)\"><input type=button class=buttons value='Zamknij' onclick=\"self.close();\"></span></h2>";
// USTAWIENIA GŁÓWNE - STOP

// ***************************** USTAWIENIA WYGLĄDU - START *****************************
$rowpersite 										= 15;	// ilość wierszy w tabelach
$page_max 											= 50; 	// max ilość odnośników na stronie
$pokazraport										= 1;	// czy ma pokazywać raport na stronie startowej z awarii itp.
$pokazgoup			 								= 0;	// pokazanie strzałki na dole strony
$tooltips											= 0;	// graficzne dymki podpowiedzi
$pokazpoczatekuwag									= 1;	// czy ma pokazywać początek treści uwag w tabelach
$iloscznakowuwag									= 30;	// ilość pierwszych znaków pola uwagi pokazywanych w tabelach
$default_kolorowanie								= 1; 	// w module magazyn - czy ma kolorować domyślnie wg typu sprzętu
$icon_size											= 3;	// 1 - 48x48	, 2 - 72x72		, 3 - 128x128     ==> dla widoku prostego bazy eSerwis
$pokaz_filtrowanie_w_naprawach						= 1; 	// czy mają być dostępne filtrowania w naprawach (wg. komórki i wg typu)
$box_z_licznikiem_height 							= 55;	// wysokość kwadratu z licznikiem na stronie startowej
$box_z_licznikiem_fontsize_napis					= 12; 	// w px
$box_z_licznikiem_fontsize 							= 4;	// w em
$box_z_licznikiem_odstep 							= 5;	// pkt odstępu między napisem a wartością licznika
$wciecie_dla_podleglych_komorek 					= 1;	// w przeglądaniu komórek: czy wyróżniać wcięciem typ komórki Agencja i filia
$liczba_wierszy_w_licznikach_w						= 16;
$liczba_wierszy_w_licznikach_m						= 15;
$templates_funny									= true;
$path_to_kopie										= "KopieZUP";
$weryfikacja_dat_protokolow							= true;	// czy podczas generowania protokołu ma być sprawdzane zamknięcie danego miesiąca. Jeżeli dany miesiąc jest zamknięty system nie pozwoli wygenerować protokołu.
// USTAWIENIA WYGLĄDU - STOP

// ***************************** USTAWIENIA DLA BAZY HELPDESK - START *****************************
$zastap_obsluge_zmiana_statusu						= 1;
$max_ilosc_minut_dla_konsultacji					= 15;
$enableHDszczPreviewDIV								= 1;	// czy ma byc dostepny podglad krokow wykonanych przy danym zgłoszeniu na liscie zlecen(wersja oparta na DIV'ach)
$PokazHDnaStronieGlownej 							= 1; 	// czy ma pokazywac informacje o zgloszeniach z Helpdesk'a na stronie startowej
$KolorujWgStatusow									= 1; 	// kolorowanie wierszy w tabeli ze zgloszeniami
$KolorujWgStatusowKroki								= 0; 	// kolorowanie wierszy z krokami w tabeli ze zgloszeniami
$PokazIkonyWHPrzegladanie 							= 1;	// ikony w tabeli hd_p_zgloszenia.php (rozwijanie tresci, osoby, telefony itp.
$WlaczMaile											= 0;		// wlacza lub wylacza maile w systemie
$TrybDebugowania									= 0; 		// wlacz / wylacz tryb debugowania
$wersja_graficzna									= 0;		// 18082010 - poczaek implementacji. W wiezych tabelach, z wylazonym trybem graficznym dane bedzie szybciej wyswietlane
$AK_margines_tolerancji								= 3; 	// ilość godzin tolerancji, w ramach których będzie sprawdzane przy przesunięciach czasu rozpoczęcia działań, czy ma ma być dostępna modyfikacja czy nie. Np. UP pracuje w godzinach 10:00-17:00. Przy wartości ustawionej na 1: wpisanie zgłoszenia o godzinie 16:00 i późniejszej będzie umożliwiało już modyfikację czasu rozpoczęcia.
$HD_prog_ostrzezenia_o_nierozpoczeciu_zgloszenia 	= 14 * 60 * 60;		// ilosc godzin * ... * ...
$HD_prog_ostrzezenia_o_niezakonczeniu_zgloszenia 	= 14 * 60 * 60;		// ilosc godzin * ... * ...
$wlacz_miganie_ostrzegawcze 						= true;			 // czy ostrzeżenia o kończącym się czasie na rozpoczęcie lub zakończenie oraz o przekroczeniach czasu mają migać.
$turnon__hd_o_zgloszenia							= true;		// okienko ostrzegawcze o kończącym się czasie w Obsłudze zgłoszenia
$turnon__hd_o_zgloszenia_zs							= true;		// okienko ostrzegawcze o kończącym się czasie w oknie Zmień status
$turnon__hd_p_zgloszenia							= true;		// okienko ostrzegawcze o kończącym się czasie w Przeglądaniu zgłoszeń
$turnon__hd_p_zgloszenia_kroki						= true;		// okienko ostrzegawcze o kończącym się czasie w Przeglądaniu kroków zgłoszenia
$blinking_R_from									= 6 * 60 * 60;						// od ilu godzin informacja o kończącym się czasie na rozpoczęcie ma migać
$blinking_Z_from									= 8 * 60 * 60;						// od ilu godzin informacja o kończącym się czasie na rozpoczęcie ma migać
$max_ilosc_dni_w_przegladaniu_zgloszen 				= 30;
$czy_wyroznic_zgloszenia_seryjne					= 1;	// czy w przeglądaniu zgłoszeń wyróżnić zgłoszenia seryjne 
$funkcja_kontroli_pracownikow						= 1;	// czy mają być dostępne funkcje kontrolne dla kierowników
$wymagane_protokoly_z_wyjazdow						= 1;
$raport_zbiorczy_koloruj							= 1; 	// kolory dla IZZ, IWK, CZAS
$szczegoly_naprawy_w_przegladaniu_zgloszen 			= true;	// czy dać możliwość załadowania szczegółów naprawy w przeglądaniu zgłoszeń
$czy_wymagany_nr_telefonu							= 0; 	// czy w rejestracji zgłoszeń pole: numer telefonu ma być wymagane 
$gp_start_pn_pt										= '07:00';
$gp_stop_pn_pt										= '21:00';
$gp_start_so										= '07:00';
$gp_stop_so											= '15:00';
$gp_start_ni										= '';
$gp_stop_ni											= '';
$default_working_time 								= 'PN@07:00-21:00;WT@07:00-21:00;SR@07:00-21:00;CZ@07:00-21:00;PT@07:00-21:00;SO@07:00-15:00;NI@-;';
$serwis_working_time 								= 'PN@07:00-21:00;WT@07:00-21:00;SR@07:00-21:00;CZ@07:00-21:00;PT@07:00-21:00;SO@07:00-15:00;NI@-;';
$datasystemowa_kolor 								= '#FF0000';
$datasystemowa_tlo	 								= '#FFFFFF';

$wiecej_informacji_w_Helpdesk						= FALSE;	// czy ma pokazywać więcej informaji w dymkach na liście zgłoszeń (np. opis statusu, inf. o stanach pośrednich itp.)

// jakie wartości zbierać do liczników (wszyscy)
/*	1 - pr. rozpoczecia	2 - pr. zakończenia	3 - nowe	4 - rozp. nie zak.	5 - przypisane	6 - rozpoczęte	7 - w firmie	8 - w serwisie zewn.	9 - nie zamkniete	10 - dooddania	11 - zamknięte	12 - wszystkie	13 - awarie krytyczne	14 - awarie zwykłe	15 - oczek. na odp. klienta	16 - oczek. na odp. klienta */
$l_w_1	= true;		$l_w_2	= true;		$l_w_3	= true;		$l_w_4	= true;		$l_w_5	= true;		$l_w_6	= true;		$l_w_7	= true;		$l_w_8	= true;		$l_w_9	= true;		
$l_w_10	= true;		$l_w_11	= true;		$l_w_12	= false;	$l_w_13	= true;		$l_w_14	= true;		$l_w_15 = true; 	$l_w_16 = true;	
// jakie wartości zbierać do liczników (moje) 
/*	1 - pr. rozpoczecia	2 - pr. zakończenia	3 - rozp. nie zak.	4 - przypisane	5 - rozpoczęte	6 - w firmie	7 - w serwisie zewn.	8 - nie zamkniete	9 - do oddania	10 - zamknięte	11 - wszystkie		*12 - awarie krytyczne	*13 - awarie zwykłe	15 - oczek. na odp. klienta	16 - oczek. na odp. klienta	*/
$l_m_1	= true;		$l_m_2	= true;		$l_m_3	= false;		$l_m_4	= true;		$l_m_5	= true;		$l_m_6	= true;		$l_m_7	= true;		$l_m_8	= true;		
$l_m_9	= true;		$l_m_10	= true;		$l_m_11	= false;		$l_m_12	= true;		$l_m_13	= true;		$l_m_15 = true; 	$l_m_16 = true;		

$noofminutes_w										= 10;	// ilość minut między automatycznymi odświeżeniami liczników dla wszystkich
$noofminutes_m 										= 10;	// ilość minut między automatycznymi odświeżeniami liczników dla danego użytkownika
$noofminutes 										= 10;
$nofmiliseconds 									= $noofminutes*60*1000;
$HOUSTON_nr_tel_1 									= '22 5053612';
$HOUSTON_nr_tel_2 									= '502019660';
$HOUSTON_mail 										= 'HOUSTON@warszawa.poczta-polska.pl';
$HADIM_max 											= 10; 	// max. ilość znaków do wpisania numeru HADIM 
															// po zmianie cyfry należy wykonać zapytanie na bazie helpdesk:
// ALTER TABLE `hd_zgloszenie` CHANGE `zgl_poczta_nr` `zgl_poczta_nr` VARCHAR ( XX ) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL;
//																				/*\
//																				 |
// 																				 --- $HADIM_max
$HADIM_width										= 8;	// szerokość pola INPUT (w hd_d_zgloszenie.php, hd_d_zgloszenie_s.php, hd_e_zgloszenie_new.php) dla pola z numerem HADIM
$Podst_Inf_o_zgl									= true; 	// czy ma pokazywać podstawowe informacje o zgłszeniu przy operacjach: pobrania uszk. sprzętu z up, wymianie podzespołów, itp.
$ZamianaZnakowSpecjalnych							= true; // czy ma zamieniać znaki specjalne ' -> `   " -> `    \ -> / 	w hd_d_zgloszenie.php, hd_d_zgloszenie_s.php, hd_o_zgloszenia_zs.php, hd_o_zgloszenia_s.php, hd_e_zgloszenie_krok.php, hd_e_zgloszenie_new.php
$DK_SS												= true; // czy ma pokazywać drugą kolumnę w krokach zgłoszenia z Czasem START / STOP

$allow_hide_for_all									= false; // czy ma dawać możliwość ukrywania wszystkich zgłoszeń (nawet tych powiązanych z wymianą podzespołów i naprawą) - może to spowodować problemy ze spójnością bazy
// USTAWIENIA DLA BAZY HELPDESK - STOP
$funkcja_weryfikacji_zgloszen_przez_kierownikow		= true;  // czy ma być włączona opcja potwierdzania sprawdzenia zgłoszenia przez przełożonego.
$edycja_dla_wszystkich_dla_okresu					= '2012-07'; // maska RRRR-MM <- dla zgłoszeń z tego miesiąca będzie możliwość edycji zgłoszenia przez wszystkich użytkowników
$weryfikacja_more									= true; // pokaż informacje o czasach poszczególnych etapów w formatce weryfikacji zgłoszeń

// -----------------------------------//
/**/  include('cfg_nadpisz.php');	 /**/ 
// ---------------------------------- //

include('cfg_db.php');

if ($splt==1) require "classes/timer.class.php";
define('eSerwis_OK',1);

function ShowSQL($zapytanie) {
	echo "<br /><i>$zapytanie</i>";
}

function ConnectToDatabase() {
	global $link, $dbtype, $dbhost, $dbusername, $dbpassword, $dbname;
	$link = mysql_connect( "$dbhost", "$dbusername" , "$dbpassword" ) or die("<h2 style='font-size:13px;font-weight:normal;padding-top:10px;padding-bottom:10px;margin-top:0px;margin-bottom:5px;text-align:center;background: #FF9999;color:#313131;display: block; border: 1px solid #FF6C6C;'>Wystąpił błąd podczas łączenia się z bazą danych</h2><div align=right><input type=button class=buttons value='Spróbuj ponownie' onclick=\"window.location.reload(true);\"></div>");
	mysql_select_db("$dbname", $link) or die($k_b);
	return $link;
}

// sprawdzenie poprawności hasla podanego podczas logowania
// poziom uzytkowników : 
//   0 - tylko przeglądanie
//   1 - zaawansowany
//   9 - administrator
function sprawdz($login,$haslo) {
  global $dbhost, $dbusername, $dbpassword, $dbname;
  $wynik=false;
  global $imie;
  global $nazwisko;
  global $loginname;
  global $mail;
  global $prawa;
  global $filia;
  global $nr;
  global $um;
  global $styl;
  global $fskrot;
  global $info;
  global $info_a;
  global $block;
  global $pikony;
  global $allowsell;
  global $menutype;
  global $utelefon;
  global $spage;
  global $mminhd;
    
$conn = mysql_connect("$dbhost","$dbusername","$dbpassword") or exit;
mysql_select_db("$dbname",$conn) or die($k_b);
  
$sql = "SELECT user_first_name,user_id,user_last_name,user_ranking,belongs_to,user_master,user_style,user_login,user_showicons,user_allow_sell,user_menu_type,user_phone,user_startpage,user_mainmenu_in_helpdesk FROM $dbname.serwis_uzytkownicy WHERE user_login = '$login' AND user_pass = '$haslo';";
$odpowiedz = mysql_query($sql,$conn) or die($k_b);
  
if (mysql_num_rows($odpowiedz)!=0) {
	$wynik=true;
	list($imie,$nr,$nazwisko,$prawa,$filia,$um,$styl,$loginname,$pikony,$allowsell,$menutype,$utelefon,$spage,$mminhd)=mysql_fetch_array($odpowiedz);
} 

// wczytanie komunikatu od admina (jeżeli takowy istnieje)
$odpowiedz = mysql_query("SELECT admin_opis,admin_value FROM $dbname.serwis_admin WHERE ((admin_id=1) and (admin_value=1)) LIMIT 1",$conn) or die($k_b);
if (mysql_num_rows($odpowiedz)!=0) { list($info,$info_a)=mysql_fetch_array($odpowiedz); } else { $info=''; $info_a=0; }

// sprawdzenie statusu blokady bazy
$odpowiedz = mysql_query("SELECT admin_value FROM $dbname.serwis_admin WHERE ((admin_id=2) and (admin_value=1)) LIMIT 1",$conn) or die($k_b);
if (mysql_num_rows($odpowiedz)!=0) { list($dane)=mysql_fetch_array($odpowiedz); } else $block=0;

if ($filia) { 
	$odpowiedz = mysql_query("SELECT filia_skrot FROM $dbname.serwis_filie WHERE filia_id = $filia LIMIT 1",$conn) or die($k_b);
	if (mysql_num_rows($odpowiedz)!=0) { list($fskrot)=mysql_fetch_array($odpowiedz); } else $fskrot='';
}

mysql_close() or exit;
return $wynik;
}

function getdays($day1,$day2)  { return round((strtotime($day2)-strtotime($day1))/(24*60*60),0); } 
function gethours($day1,$day2) { return round((strtotime($day2)-strtotime($day1))/(60*60),0); } 
function getminutes($day1,$day2) { return round((strtotime($day2)-strtotime($day1))/60,0); } 

function round_up ($value, $places=0) {
  if ($places < 0) { $places = 0; }
  $mult = pow(10, $places);
  return ceil($value * $mult) / $mult;
}

function round_to_penny($amount){
// print( round_to_penny(79.99999) ); //result: 79.99  
    $string = (string)($amount * 10000);
    $string_array = split("\.", $string);
    $int = (int)$string_array[0];
    $return = $int / 10000;
    return $return;
}

function ceiling($number, $significance = 1) {
	return ( is_numeric($number) && is_numeric($significance) ) ? (ceil($number/$significance)*$significance) : false;
}
	
function escape($url) { return str_replace("%2F", "/", urlencode($url)); }
function escape1($url){ return str_replace("+", "&nbsp;", $url); }
function unescape($url){ return str_replace(" ", "+", $url); }
function tbl_empty_row($colspanilosc = 1) { echo "<tr class=hideme><td colspan=$colspanilosc>&nbsp;</td></tr>"; }
function tbl_hr_row($colspanilosc=1) { echo "<tr><td colspan=$colspanilosc><hr /></td></tr>"; }


function tbl_tr_highlight($i) {
	if ($i % 2 != 0 ) echo "<tr id=$i class=nieparzyste onmouseover=\"rowOver('$i',1);\" onmouseout=\"rowOver('$i',0);\" onclick=\"selectRow('$i');\" ondblclick=\"deSelectRow('$i');\">";
	if ($i % 2 == 0 ) echo "<tr id=$i class=parzyste onmouseover=\"rowOver('$i',1);\" onmouseout=\"rowOver('$i',0);\" onclick=\"selectRow('$i');\" ondblclick=\"deSelectRow('$i');\">";
}

function tbl_tr_highlight_1row_kolor($i,$kolor) {
	echo "<tr id=$i style='background:$kolor' onmouseover=\"rowOver_color('$i',1,'$kolor');\" onmouseout=\"rowOver_color('$i',0,'$kolor');\" />";
}

// ==========================

function tbl_tr_highlight_dblClick($i) {
	if ($i % 2 != 0 ) echo "<tr id=$i class=nieparzyste onmouseover=\"rowOver('$i',1);; return false;\" onmouseout=\"rowOver('$i',0); return false;\" onclick=\"selectRow('$i');\" >"; 
	//onDblClick=\"SelectTRById($i); _PokazNrZgloszen(); return false;\">";
	
	if ($i % 2 == 0 ) echo "<tr id=$i class=parzyste onmouseover=\"rowOver('$i',1);\" onmouseout=\"rowOver('$i',0);\" onclick=\"selectRow('$i');\" >"; 
	//onDblClick=\"SelectTRById($i); _PokazNrZgloszen();\">";
}

function tbl_tr_color_dblClick($i,$kolor) {
	echo "<tr id=$i style='background:$kolor' onmouseover=\"rowOver_color('$i',1,'#FFFFFF');; return false;\" onmouseout=\"rowOver_color('$i',0,'$kolor'); return false;\">";
	//	onclick=selectRow_color('$i','$kolor') ondblclick=deSelectRow_color('$i','$kolor')>";
}

// ==========================

function tbl_tr_highlight_dblClick_z_kb_kategorie($i) {
	if ($i % 2 != 0 ) echo "<tr id=$i class=nieparzyste onmouseover=\"rowOver('$i',1); \" onmouseout=\"rowOver('$i',0);\" onclick=\"selectRow('$i');\" onDblClick=\"PrepareKategoria($i); \">";
	if ($i % 2 == 0 ) echo "<tr id=$i class=parzyste onmouseover=\"rowOver('$i',1);\" onmouseout=\"rowOver('$i',0);\" onclick=\"selectRow('$i');\" onDblClick=\"PrepareKategoria($i); \">";
}

function tbl_tr_highlight_dblClick_z_kb_kategorie2($i) {
	if ($i % 2 != 0 ) echo "<tr id=$i class=nieparzyste onmouseover=\"rowOver('$i',1);\" onmouseout=\"rowOver('$i',0);\" onclick=\"selectRow('$i');\" onDblClick=\"PrepareKategoria2($i); \">";
	if ($i % 2 == 0 ) echo "<tr id=$i class=parzyste onmouseover=\"rowOver('$i',1);\" onmouseout=\"rowOver('$i',0);\" onclick=\"selectRow('$i');\" onDblClick=\"PrepareKategoria2($i); \">";
}

function tbl_tr_highlight_dblClick_e_magazyn_pobierz($i) {
	if ($i % 2 != 0 ) echo "<tr id=$i class=nieparzyste onmouseover=\"rowOver('$i',1);\" onmouseout=\"rowOver('$i',0);\" onclick=\"selectRow('$i');\" onDblClick=\"PreparePobierz($i); \">";
	if ($i % 2 == 0 ) echo "<tr id=$i class=parzyste onmouseover=\"rowOver('$i',1);\" onmouseout=\"rowOver('$i',0);\" onclick=\"selectRow('$i');\" onDblClick=\"PreparePobierz($i); \">";
}
function tbl_tr_highlight_dblClick_e_magazyn_pobierz_kolor($i,$kolor) {
	echo "<tr id=$i style='background:$kolor' onmouseover=\"rowOver_color('$i',1,'#FFFFFF');;\" onmouseout=\"rowOver_color('$i',0,'$kolor');\" onDblClick=\"PreparePobierz($i);\">";
}

function tbl_tr_highlight_dblClick_p_magazyn_do_rezerwacji($i) {
	if ($i % 2 != 0 ) echo "<tr id=$i class=nieparzyste onmouseover=\"rowOver('$i',1);\" onmouseout=\"rowOver('$i',0);\" onclick=\"selectRow('$i');\" onDblClick=\"PreparePMagazynDoRezerwacji($i); \">";
	if ($i % 2 == 0 ) echo "<tr id=$i class=parzyste onmouseover=\"rowOver('$i',1);\" onmouseout=\"rowOver('$i',0);\" onclick=\"selectRow('$i');\"  onDblClick=\"PreparePMagazynDoRezerwacji($i); \">";
}

function tbl_tr_highlight_dblClick_p_magazyn_do_rezerwacji_kolor($i,$kolor) {
	echo "<tr id=$i style='background:$kolor' onmouseover=\"rowOver_color('$i',1,'#FFFFFF');;\" onmouseout=\"rowOver_color('$i',0,'$kolor');\" onDblClick=\"PreparePMagazynDoRezerwacji($i);\">";
}

function tbl_tr_highlight_dblClick_z_magazyn_ukryj($i) {
	if ($i % 2 != 0 ) echo "<tr id=$i class=nieparzyste onmouseover=\"rowOver('$i',1);\" onmouseout=\"rowOver('$i',0);\" onclick=\"selectRow('$i');\"  onDblClick=\"PrepareZMagazynUkryj($i); \">";
	if ($i % 2 == 0 ) echo "<tr id=$i class=parzyste onmouseover=\"rowOver('$i',1);\" onmouseout=\"rowOver('$i',0);\" onclick=\"selectRow('$i');\" onDblClick=\"PrepareZMagazynUkryj($i); \">";
}

function tbl_tr_highlight_dblClick_z_magazyn_ukryj_kolor($i,$kolor) {
	echo "<tr id=$i style='background:$kolor' onmouseover=\"rowOver_color('$i',1,'#FFFFFF');;\" onmouseout=\"rowOver_color('$i',0,'$kolor');\" onDblClick=\"PrepareZMagazynUkryj($i);\">";
}

function tbl_tr_highlight_dblClick_p_magazyn_aktualne($i) {
	if ($i % 2 != 0 ) echo "<tr id=$i class=nieparzyste onmouseover=\"rowOver('$i',1);\" onmouseout=\"rowOver('$i',0);\" onclick=\"selectRow('$i');\"  onDblClick=\"PreparePMagazynAktualne($i); \">";
	if ($i % 2 == 0 ) echo "<tr id=$i class=parzyste onmouseover=\"rowOver('$i',1);\" onmouseout=\"rowOver('$i',0);\" onclick=\"selectRow('$i');\"  onDblClick=\"PreparePMagazynAktualne($i); \">";
}

function tbl_tr_highlight_dblClick_p_magazyn_aktualne_kolor($i,$kolor) {
	echo "<tr id=$i style='background:$kolor' onmouseover=\"rowOver_color('$i',1,'#FFFFFF');;\" onmouseout=\"rowOver_color('$i',0,'$kolor');\" onDblClick=\"PreparePMagazynAktualne($i);\">";
}

function tbl_tr_highlight_dblClick_p_magazyn_pobrany($i) {
	if ($i % 2 != 0 ) echo "<tr id=$i class=nieparzyste onmouseover=\"rowOver('$i',1);\" onmouseout=\"rowOver('$i',0);\" onclick=\"selectRow('$i');\"  onDblClick=\"PreparePMagazynPobrany($i); \">";
	if ($i % 2 == 0 ) echo "<tr id=$i class=parzyste onmouseover=\"rowOver('$i',1);\" onmouseout=\"rowOver('$i',0);\" onclick=\"selectRow('$i');\"  onDblClick=\"PreparePMagazynPobrany($i); \">";
}

function tbl_tr_highlight_dblClick_p_magazyn_pobrany_kolor($i,$kolor) {
	echo "<tr id=$i style='background:$kolor' onmouseover=\"rowOver_color('$i',1,'#FFFFFF');;\" onmouseout=\"rowOver_color('$i',0,'$kolor');\" onDblClick=\"PreparePMagazynPobrany($i);\">";	
}

function tbl_tr_highlight_dblClick_p_magazyn_rezerwacje($i) {
	if ($i % 2 != 0 ) echo "<tr id=$i class=nieparzyste onmouseover=\"rowOver('$i',1);\" onmouseout=\"rowOver('$i',0);\" onclick=\"selectRow('$i');\"  onDblClick=\"PreparePMagazynRezerwacje($i); \">";
	if ($i % 2 == 0 ) echo "<tr id=$i class=parzyste onmouseover=\"rowOver('$i',1);\" onmouseout=\"rowOver('$i',0);\" onclick=\"selectRow('$i');\"  onDblClick=\"PreparePMagazynRezerwacje($i); \">";
}

function tbl_tr_highlight_dblClick_p_magazyn_rezerwacje_kolor($i,$kolor) {
	echo "<tr id=$i style='background:$kolor' onmouseover=\"rowOver_color('$i',1,'#FFFFFF');;\" onmouseout=\"rowOver_color('$i',0,'$kolor');\" onDblClick=\"PreparePMagazynRezerwacje($i);\">";
}

function tbl_tr_highlight_dblClick_z_magazyn_ukryty($i) {
	if ($i % 2 != 0 ) echo "<tr id=$i class=nieparzyste onmouseover=\"rowOver('$i',1);\" onmouseout=\"rowOver('$i',0);\" onclick=\"selectRow('$i');\"  onDblClick=\"PrepareZMagazynUkryty($i); \">";
	if ($i % 2 == 0 ) echo "<tr id=$i class=parzyste onmouseover=\"rowOver('$i',1);\" onmouseout=\"rowOver('$i',0);\" onclick=\"selectRow('$i');\" onDblClick=\"PrepareZMagazynUkryty($i); \">";
}

function tbl_tr_highlight_dblClick_z_magazyn_ukryty_kolor($i,$kolor) {
	echo "<tr id=$i style='background:$kolor' onmouseover=\"rowOver_color('$i',1,'#FFFFFF');;\" onmouseout=\"rowOver_color('$i',0,'$kolor');\" onDblClick=\"PrepareZMagazynUkryty($i);\">";
}

function tbl_tr_highlight_dblClick_z_magazyn_zwroty($i) {
	if ($i % 2 != 0 ) echo "<tr id=$i class=nieparzyste onmouseover=\"rowOver('$i',1);\" onmouseout=\"rowOver('$i',0);\" onclick=\"selectRow('$i');\"  onDblClick=\"PrepareZMagazynZwroty($i); \">";
	if ($i % 2 == 0 ) echo "<tr id=$i class=parzyste onmouseover=\"rowOver('$i',1);\" onmouseout=\"rowOver('$i',0);\" onclick=\"selectRow('$i');\"  onDblClick=\"PrepareZMagazynZwroty($i); \">";
}

function tbl_tr_highlight_dblClick_z_magazyn_zwroty_kolor($i,$kolor) {
	echo "<tr id=$i style='background:$kolor' onmouseover=\"rowOver_color('$i',1,'#FFFFFF');;\" onmouseout=\"rowOver_color('$i',0,'$kolor');\" onDblClick=\"PrepareZMagazynZwroty($i);\">";
}

function tbl_tr_color($i,$kolor) {
	echo "<tr id=$i style='background:$kolor' onmouseover=\"rowOver_color('$i',1,'#FFFFFF');\" onmouseout=\"rowOver_color('$i',0,'$kolor');\">";
//	onclick=selectRow_color('$i','$kolor') ondblclick=deSelectRow_color('$i','$kolor')>";
}

function tbl_tr_color_with_border($i,$kolor) {
	echo "<tr id=$i style=\"border:2px solid red; background:$kolor; font-weight:bold;\" onmouseover=\"rowOver_color('$i',1,'#FFFFFF');\" onmouseout=\"rowOver_color('$i',0,'$kolor');\">";
//	onclick=selectRow_color('$i','$kolor') ondblclick=deSelectRow_color('$i','$kolor')>";
}


function tbl_tr_color_dblClick_towary_dostepne($i,$kolor) {
	//echo "<tr id=$i style='background:$kolor' onmouseover=\"rowOver_color('$i',1,'#FFFFFF');;\" onmouseout=\"rowOver_color('$i',0,'$kolor');\" onDblClick=\"PrepareTowar($i);\">";
	echo "<tr id=$i style='background:$kolor' onmouseover=\"rowOver_color('$i',1,'#FFFFFF');;\" onmouseout=\"rowOver_color('$i',0,'$kolor');\" >";
}

function tbl_tr_highlight_kolor($i,$kolor) {
	echo "<tr id=$i style='background:$kolor' onmouseover=\"rowOver_color('$i',1,'#FFFFFF');;\" onmouseout=\"rowOver_color('$i',0,'$kolor');\" onClick=\"selectRow('$i')\" onDblClick=\"SelectTRById($i); PokazNrZgloszen();\">";

//	if ($i % 2 != 0 ) echo "<tr id=$i class=nieparzyste onmouseover=rowOver('$i',1); onmouseout=rowOver('$i',0) onclick=selectRow('$i') ondblclick=deSelectRow('$i')>";
//	if ($i % 2 == 0 ) echo "<tr id=$i class=parzyste onmouseover=rowOver('$i',1); onmouseout=rowOver('$i',0) onclick=selectRow('$i') ondblclick=deSelectRow('$i')>";
}

function startbuttonsarea($wyrownajdo="right") {
	switch ($wyrownajdo) {
		case "right"	: echo "<div class='showr hideme'>"; break;
		case "left"		: echo "<div class='showl hideme'>"; break;
		case "center"	: echo "<div class='show hideme'>"; break;
		default			: echo "<div class='showr hideme'>"; break;
	}
}

function startbuttonsarea_new($wyrownajdo="right") {
	switch ($wyrownajdo) {
		case "right"	: echo "<div class='showr hideme'>"; break;
		case "left"		: echo "<div class='showl hideme'>"; break;
		case "center"	: echo "<div class='show hideme'>"; break;
		default			: echo "<div class='showr hideme'>"; break;
	}
}

function addbuttons($buttonarray=null) {
	$arg = func_get_args();
	$ile = count($arg);
	if (count($arg)>0) {
		for ($t=0; $t<count($arg); $t++) {
			switch ($arg[$t]) {
				case "anuluj"		: 	if ($ile==1) $potwierdzaj_close = 1;
										if ($potwierdzaj_close!=1) { 
											echo "<input id=anuluj class=buttons type=button onClick=\"if (confirm('Czy napewno chcesz anulować wprowadzone dane ?')) self.close();\" value=Anuluj>"; break;
										} else {
											echo "<input id=anuluj class=buttons type=button onClick=self.close(); value=Anuluj>"; break;
										}
				case "zamknij"		: 	if ($ile==1) $potwierdzaj_close = 1;
										if ($potwierdzaj_close!=1) { 
											echo "<input id=anuluj class=buttons type=button onClick=\"if (confirm('Czy napewno chcesz zamknąć bieżące okono ?')) self.close();\" value=Zamknij>"; break;
										} else {
											echo "<input id=anuluj class=buttons type=button onClick=self.close(); value=Zamknij>"; break;
										}
				case "tak"			: $brouwser = $_SERVER['HTTP_USER_AGENT'];
										if ((strstr($brouwser,"MSIE 5.0")) || (strstr($brouwser,"MSIE 5.5")) || (strstr($brouwser,"MSIE 6.0")) || (strstr($brouwser,"MSIE 7.0"))) echo "<div style=height:0px></div>";
										echo "<input class=buttons type=submit name=submit value=TAK>"; break;
				case "nie"			: echo "<input class=buttons type=button onClick=self.close(); value=NIE>"; break;
				case "dalej"		: echo "<input class=buttons type=submit name=submit id=submit value=Dalej onClick=\"return _onSubmitDefault('Potwierdzasz poprawność wprowadzonych danych ?');\">"; break;
				case "dalej1"		: echo "<input class=buttons type=submit name=submit1 id=submit value=Dalej onClick=\"return _onSubmitDefault('Potwierdzasz poprawność wprowadzonych danych ?');\">"; break;
				case "zapisz"		: echo "<input class=buttons type=submit name=submit id=submit value=Zapisz onClick=\"return _onSubmitDefault('Potwierdzasz poprawność wprowadzonych danych ?');\">"; break;
				case "zapisz&zamknij"		: echo "<input class=buttons type=submit name=submit id=submit value='Zapisz & Zamknij' onClick=\"return _onSubmitDefault('Potwierdzasz poprawność wprowadzonych danych ?');\">"; break;
				case "wstecz"		: echo "<input id=back class=buttons type=button onClick=history.go(-1); value='Poprzedni widok'>"; break;
				case "start"		: echo "<input class=buttons type=button onClick=\"if (parent.window.name=='eSerwis') { window.location.href='main.php'; } else { if (confirm('Zamknąć okno i przejść do strony głównej ?')) { self.close(); } else { window.location.href='main.php'; } } \" value='Strona główna'>"; break;
				case "przypiszdozadania": echo "<input class=buttons style='font-weight:bold' type=submit id=PrzypiszDoZadania name=submit1 value='Przypisz do zadania'>"; break;				
				case "dodajsprzet"	: echo "<input class=buttons type=button onclick=\"newWindow_r(820,600,'d_ewidencja.php')\" value='Dodaj sprzęt'>"; break;
				case "goup"			: echo "<a href=#goup>top</a>"; break;
				case "reloadpage"		: echo "<input class=buttons type=button name=reloadpage value='Odśwież stronę' onClick=\"window.location.reload(true);\">"; break;
				default				: echo ""; break;
			} // switch

		} // for
		//echo "<span id=Saving style='display:none;background-color:black;'><b><font color=white>&nbsp;Trwa zapisywanie... proszę czekać&nbsp;</font></b><input class=imgoption type=image src=img/loader.gif>&nbsp;</span>";
	} // if
}

function addlinkbutton($nazwa=null, $link) { echo "<input class=buttons type=button onClick='window.location.href=\"$link\"' value=$nazwa>";}
function addownlinkbutton($nazwa="button", $buttonname = "button", $buttontype="button", $link) { echo "<input class=buttons id=$buttonname name=$buttonname type=$buttontype onClick=\"$link\" value=$nazwa>"; }
function addownlinkbutton2($nazwa="button", $buttonname = "button", $buttontype="button", $link) { echo "<input class=buttons id=$buttonname name=$buttonname type=$buttontype onClick='window.location=\"$link\"' value=$nazwa>";}
function addownsubmitwithname($nazwa="przycisk", $buttonname = "button", $buttontype="submit") { echo "<input id=$buttonname class=buttons name=$buttonname type=$buttontype value=$nazwa>";}
function addsubmitbutton($nazwa=null, $link) { echo "<input class=buttons type=submit id=submit onClick=\"$link\" value=$nazwa>"; }
function addclosewithreloadbutton($nazwa=null) { echo "<input class=buttons type=button onClick=\"if (opener) opener.location.reload(true); self.close();\" value=$nazwa>";}
function addbackbutton($nazwa=null) { echo "<input class=buttons type=button onClick=onClick=history.go(-1); value='$nazwa'>";}
function addselectallbutton($nazwa="'Zaznacz wszystkie'") {	echo "<input class=buttons type=button onClick=selectAll1(this); value=$nazwa>";}
function addinvertbutton($nazwa="'Odwróć zaznaczenie'") { echo "<input class=buttons type=button onClick=selectAll(this); value=$nazwa>";}
function addclearselectionbutton($nazwa="Wyczyść") { echo "<input type=reset class=buttons name=sAll value=$nazwa>"; }
function addownsubmitbutton($nazwa="wpisz nazwę przycisku", $inputname="submit") { echo "<input class=buttons type=submit id=submit name=$inputname value=$nazwa>";}
function addownbutton($nazwa="wpisz nazwę przycisku", $inputname="button") { echo "<input class=buttons type=submit name=$inputname value=$nazwa>";}

function pokaz_uwagi($zmienna, $max_znakow=30, $link){
	if (strlen($zmienna)>0) {
		echo "<br />";
		$uwagi123=br2nl($zmienna);
		if (strlen($zmienna)>$max_znakow) $uwagi123 = (substr($zmienna,0,$max_znakow).'...');	
		echo "<a title=' Pokaż uwagi w nowym oknie ' href=# style='color:#3C3C3C; text-decoration:none;' onClick=\"$link\">";
		echo "<sub style='font-size:11px;'><u>UWAGI:</u> $uwagi123</sub>";
		echo "</a>";
	}
}

function SkrocTekst($zmienna, $max_znakow=30){
	if (strlen($zmienna)>0) {
		$tekst=br2nl($zmienna);
		if (strlen($zmienna)>$max_znakow) $tekst = substr($zmienna,0,$max_znakow).'...';	
		echo "<a title='$zmienna' class=normal>$tekst</a>";
	}
}

function listabaz($poziomuprawnien="9", $pokazikony) {
	echo "<div class=hideme style=margin-left:10px; id=floatmenu>";
	echo "<ul class=menulist id=listMenuRoot1>";
	echo "<li><a class=menu_font href=#>&nbsp;Przejdź do bazy&nbsp;</a>";
	echo "<ul>";
	if($poziomuprawnien=="9") {
		echo "<li><p align=left><a href=z_uzytkownicy.php?showall=0>";
		if ($pokazikony==1) echo "<img src=img/database_users.png border=0 align=absmiddle>";
		echo "&nbsp;Pracowników</a></p></li>";
		echo "<li><p align=left><a href=z_pion.php>";
		if ($pokazikony==1) echo "<img src=img/database_piony.png border=0 align=absmiddle>";
		echo "&nbsp;Pionów</a></p></li>";
		echo "<li><p align=left><a href=z_umowa.php>";
		if ($pokazikony==1) echo "<img src=img/database_umowy.png border=0 align=absmiddle>";
		echo "&nbsp;Umów</a></p></li>";
		echo "<li><p align=left><a href=z_filie.php>";
		if ($pokazikony==1) echo "<img src=img/database_filie.png border=0 align=absmiddle>";
		echo "&nbsp;Filii</a></p></li>";
	}
	echo "			<li><p align=left><a href=z_komorka.php?showall=0&aktywne=1>";
	if ($pokazikony==1) echo "<img src=img/database_up.png border=0 align=absmiddle>";
	echo "&nbsp;Urzędów/komórek</a></p></li>";
	echo "			<li><p align=left><a href=z_firmy_zewnetrzne.php>";
	if ($pokazikony==1) echo "<img src=img/database_fz.png border=0 align=absmiddle>";
	echo "&nbsp;Firm zewnętrznych</a></p></li>";
	echo "			<li><p align=left><a href=z_oprogramowanie.php>";
	if ($pokazikony==1) echo "<img src=img/database_opr.png border=0 align=absmiddle>";
	echo "&nbsp;Oprogramowania</a></p></li>";
	echo "			<li><p align=left><a href=z_typ_sprzetu.php>";
	if ($pokazikony==1) echo "<img src=img/database_typy.png border=0 align=absmiddle>";
	echo "&nbsp;Typów sprzętu komputerowego</a></p></li>";
	echo "			<li><p align=left><a href=z_konfiguracja.php>";
	if ($pokazikony==1) echo "<img src=img/database_konf.png border=0 align=absmiddle>";
	echo "&nbsp;Typów komputerów z konfiguracją</a></p></li>";
	echo "			<li><p align=left><a href=z_monitorami.php>";
	if ($pokazikony==1) echo "<img src=img/database_monitor.png border=0 align=absmiddle>";
	echo "&nbsp;Modeli monitorów</a></p></li>";
	echo "			<li><p align=left><a href=z_drukarka.php>";
	if ($pokazikony==1) echo "<img src=img/database_print.png border=0 align=absmiddle>";
	echo "&nbsp;Modeli drukarek</a></p></li>		";
	echo "			<li><p align=left><a href=z_czytnik.php>";
	if ($pokazikony==1) echo "<img src=img/barcode.gif border=0 align=absmiddle>";
	echo "&nbsp;Modeli czytników</a></p></li>		";
	echo "		</ul>";
	echo "	</li>";
	echo "</ul>";
	echo "</div>";
}

function endbuttonsarea() { echo "</div>"; }
function endbuttonsarea_new() { echo "</span>"; }
/*  to replace all linebreaks to <br /> the best solution (IMO) is: because each OS have different ASCII chars for linebreak: windows = \r\n unix = \n mac = \r */
function nl2br2($string) { $string = str_replace(array("\r\n", "\r", "\n"), "<br />", $string); return $string; }
function br2nl($text) { return  preg_replace('/<br\\s*?\/??>/i', '', $text); }
function br2nl3($text) { return  preg_replace('/<br\\s*?\/??>/i', '\n', $text); }
function br2nl2($text) { return  str_replace("<br />", "\n\r", $text); }
function br2point($text) { return  preg_replace('/<br\\s*?\/??>/i', '.', $text); }
function space2nbsp($text) { return  str_replace(" ", "&nbsp;", $text); }

function highlight($x,$var) {//$x is the string, $var is the text to be highlighted
	if ($var != "") { $xtemp = ""; $i=0; while($i<strlen($x)){
		if((($i + strlen($var)) <= strlen($x)) && (strcasecmp($var, substr($x, $i, strlen($var))) == 0)) {
			$xtemp .= "<font style=background:yellow><b>" . substr($x, $i , strlen($var)) . "</b></font>";
			$i += strlen($var);
		} else { $xtemp .= $x{$i}; $i++; }
	} $x = $xtemp; }
    return $x;
}

function errorheader($napis) {echo "<h2 style='padding:10px; font-weight:normal;'>$napis</h2>";}
function infoheader($napis,$drukarka=0) {
	echo "<h5 style='padding:10px; font-weight:normal;'>";
	if ($drukarka!=0) { 
		echo "<div class=hideme>";
		echo "<a href=# class=normalfont title=' Wydrukuj zawartość strony ' onClick='window.print();'>";
		if ($pokaz_ikony==1) { echo "<img src=img/print_preview.gif class=imgoption style='position:relative;float:right;margin-right:6px;' border=0>";
		} else echo "<sub style='position:relative;float:right; margin-right:6px;text-decoration:none;'><b>Drukuj</b></sub>";
		echo "</a>";
		echo "</div>";
	}
	echo "$napis";
	echo "</h5>";
}
function okheader($napis) {echo "<h3 style='padding:10px; font-weight:normal;'>$napis</h3>";}
function naprawaheader($napis) {echo "<h3 class=h3naprawa style='padding:10px; font-weight:normal;'>$napis</h3>";}
function naprawaheader2($napis) {echo "<h3 class=h3naprawa style='padding:10px; font-weight:normal;background-color:#9BBAED; border: 1px solid #6A99E4;'>$napis</h3>";}
function ssheader($napis) {echo "<h3 class=h3ss style='padding:10px; font-weight:normal;'>$napis</h3>";}
function pageheader($napis,$drukarka=0,$wstecz=0,$showmainmenu=false) {
	echo "<h4 style='padding:10px; font-weight:normal;'>";
	if ($wstecz!=0) { 
		echo "<div class=hideme>";
		echo "<a href=# class=normalfont onClick=\"if (parent.window.name=='eSerwis') { window.location.href='main.php'; } else { if (confirm('Zamknąć okno i przejść do strony głównej ?')) { self.close(); } else { window.location.href='main.php'; } }\" title='Przejdź do strony głównej'>";
		if ($pokaz_ikony==1) { 
			echo "<sub style='position:relative;float:left;margin-left:3px;text-decoration:none;' class=normaltext>";
			echo "<img src=img/house.gif class=imgoption style='position:relative;float:left;margin-left:6px;' border=0>";
			echo "</sub>";
		} else {
			echo "<sub style='position:relative;float:left;margin-left:3px;text-decoration:none;' class=normaltext><b>Start</b>";
			echo "</sub>";			
		}
		echo "</a>";
		
		if ($showmainmenu==true) {
			echo "<sub id=show_mm style='position:relative;float:left;margin-left:3px;text-decoration:none;' class=normaltext>";
			echo "&nbsp;|&nbsp;<a title='Pokaż menu główne bazy eSerwis' class=normalfont style='color:blue; font-weight:bold;' href=# value='Pokaż menu główne' onClick=\"$('#mainmenu').show(); $('#show_mm').hide(); $('#hide_mm').show();\" >Pokaż menu główne</a>";
			echo "</sub>";

			echo "<sub id=hide_mm style='display:none; position:relative;float:left;margin-left:3px;text-decoration:none;' class=normaltext>";
			echo "&nbsp;|&nbsp;<a title='Ukryj menu główne bazy eSerwis' class=normalfont style='color:blue; font-weight:bold;' href=# value='Ukryj menu główne' onClick=\"$('#mainmenu').hide(); $('#show_mm').show(); $('#hide_mm').hide();\" >Ukryj menu główne</a>";
			echo "</sub>";
			
		}
		
		echo "</div>";
	}
	if ($drukarka!=0) { 
		echo "<div class=hideme>";
		echo "<a href=# class=normalfont title=' Wydrukuj zawartość strony ' onClick='window.print();'>";
		if ($pokaz_ikony==1) { 
			echo "<img src=img/print_preview.gif class=imgoption style='position:relative;float:right;margin-right:6px;' border=0>";
		} else echo "<sub style='position:relative;float:right;margin-right:3px;text-decoration:none;'><b>Drukuj</b></sub>";
		echo "</a>";
		echo "</div>";
	}
	echo "$napis";
	echo "</h4><hr style='margin:0px;' />";
}
function starttable($szerokosc=null) {echo "<table style='margin-bottom:2px' cellspacing=1 class=maxwidth ";if ($szerokosc) echo " style=width:$szerokosc";	echo ">";}
function endtable() {echo "</table>";}
function oddziel() { 
	//echo "<hr />"; 
}
function nowalinia() { echo "<br />"; }

function td_colspan($span=1, $align='c', $tresc=null) {
	if (($span) && ($align=="c")) echo "<td class=center colspan=$span>";
	if (($span) && ($align=="l")) echo "<td class=left colspan=$span>";
	if (($span) && ($align=="r")) echo "<td class=right colspan=$span>";
	if ($tresc) echo "$tresc";
}

function th_colspan($span=1, $align='c', $tresc=null) {
	if (($span) && ($align=="c")) echo "<th class=center colspan=$span>";
	if (($span) && ($align=="l")) echo "<th class=left colspan=$span>";
	if (($span) && ($align=="r")) echo "<th class=right colspan=$span>";
	if ($tresc) echo "$tresc";
}
/*
 th('0:c:LP|0::Nazwa|0::Model|0::Numer seryjny|0::Numer inwentarzowy|0:c:Uwagi|0:c:Czynnosci',$es_prawa);
 np.
 0:c:LP|0		=> |parametry[0]	| 0 - bez określonej szerokości kolumny, 100 - szerokość 100px
 			=> |parametry[1]	| c - nagłówek wycentrowany, l - do lewej, r - do prawej. Brak tego parametru - domyślnie = l
			=> |parametry[2]	| Nazwa nagłówka
			=> |parametry[3]	| widoczność zależna od aktualnego poziomu uprawnieĹ„ (parametr niewymagany)
*/				

function th($dane,$prawa) {
	echo "<tr>";
	if ($dane) {
		$th_all = explode("|",$dane);
		foreach ($th_all as $th_one) {
			$parametry = explode(";",$th_one);
			if ($parametry[3]) {
				if (strpos($parametry[3],$prawa)>-1) {
					echo "<th";
					if (($parametry[0]) && ($parametry[0]!=0)) echo " width=$parametry[0]";
					if ($parametry[1]) { 
						if ($parametry[1]=='c') echo " class=center"; elseif ($parametry[1]=='r') echo " class=right"; elseif ($parametry[1]=='nw') echo " class=nowrap"; elseif ($parametry[1]=='w') echo " class=wrap"; else echo " class=left";
					} else echo " class=left";
					echo ">$parametry[2]</th>";
				}
			} else {
					echo "<th";
					if (($parametry[0]) && ($parametry[0]!=0)) echo " width=$parametry[0]";
					if ($parametry[1]) { 
						if ($parametry[1]=='c') echo " class=center"; elseif ($parametry[1]=='r') echo " class=right"; elseif ($parametry[1]=='nw') echo " class=nowrap"; elseif ($parametry[1]=='w') echo " class=wrap"; else echo " class=left";
					} else echo " class=left";
					echo ">$parametry[2]</th>";
				}
		}
	}
	echo "</tr>";
}

function th_($dane,$prawa) {
	if ($dane) {
		$th_all = explode("|",$dane);
		foreach ($th_all as $th_one) {
			$parametry = explode(";",$th_one);
			if ($parametry[3]) {
				if (strpos($parametry[3],$prawa)>-1) {
					echo "<th";
					if (($parametry[0]) && ($parametry[0]!=0)) echo " width=$parametry[0]";
					if ($parametry[1]) { 
						if ($parametry[1]=='c') echo " class=center"; elseif ($parametry[1]=='r') echo " class=right"; elseif ($parametry[1]=='nw') echo " class=nowrap"; elseif ($parametry[1]=='w') echo " class=wrap"; else echo " class=left";
					} else echo " class=left";
					echo ">$parametry[2]</th>";
				}
			} else {
					echo "<th";
					if (($parametry[0]) && ($parametry[0]!=0)) echo " width=$parametry[0]";
					if ($parametry[1]) { 
						if ($parametry[1]=='c') echo " class=center"; elseif ($parametry[1]=='r') echo " class=right"; elseif ($parametry[1]=='nw') echo " class=nowrap"; elseif ($parametry[1]=='w') echo " class=wrap"; else echo " class=left";
					} else echo " class=left";
					echo ">$parametry[2]</th>";
				}
		}
	}
}

function td($dane) {
	if ($dane) {
		$td_all = explode("|",$dane);
		foreach ($td_all as $td_one) {
			$parametry = explode(";",$td_one);
			echo "<td";
			if (($parametry[0]) && ($parametry[0]!=0)) echo " width=$parametry[0]";
			if ($parametry[1]) { 
			if ($parametry[1]=='c') echo " class=center"; elseif ($parametry[1]=='r') echo " class=right"; elseif ($parametry[1]=='rt') echo " class=righttop"; elseif ($parametry[1]=='nw') echo " class=nowrap"; elseif ($parametry[1]=='w') echo " class=wrap"; elseif ($parametry[1]=='ct') echo " style='text-align:center;vertical-align:top;'"; else echo " class=left";
			} else echo " class=left";
			echo ">$parametry[2]</td>";
		}
	}
}

function td_($dane) {
	if ($dane) {
		$td_all = explode("|",$dane);
		foreach ($td_all as $td_one) {
			$parametry = explode(";",$td_one);
//			if ($parametry[2]!='') {
				echo "<td";
				if (($parametry[0]) && ($parametry[0]!=0)) echo " width=$parametry[0]";
				if ($parametry[1]) { 
					if ($parametry[1]=='c') echo " class=center"; elseif ($parametry[1]=='r') echo " class=right"; elseif ($parametry[1]=='rt') echo " class=righttop"; elseif ($parametry[1]=='nw') echo " class=nowrap"; elseif ($parametry[1]=='w') echo " class=wrap"; else echo " class=left";
				} else echo " class=left";
				echo ">$parametry[2]";
//			}
		}
	}
}

function td_img($dane) {
	if ($dane) {
		$td_all = explode("|",$dane);
		foreach ($td_all as $td_one) {
			$parametry = explode(";",$td_one);
			echo "<td";
			if (($parametry[0]) && ($parametry[0]!=0)) echo " width=$parametry[0]";
			if ($parametry[1]) { 
			if ($parametry[1]=='c') echo " class=center"; elseif ($parametry[1]=='r') echo " class=right"; elseif ($parametry[1]=='rt') echo " class=righttop"; else echo " class=left";
			} else echo " class=left";
			echo ">";
		}
	}
}

function _th() { echo "</th>"; }
function _td() { echo "</td>"; }
function tr_() { echo "<tr>"; }
function _tr() { echo "</tr>"; }
function br()  { echo "<br />"; }
function hr()  { echo "<hr />"; }

function start_stoper() { $time = microtime(); $time = explode(" ", $time); $time = $time[1] + $time[0]; $start = $time; }
function stop_stoper() { $time = microtime(); $time = explode(" ", $time);$time = $time[1] + $time[0]; $finish = $time; $totaltime = ($finish - $start);	
//printf ("Czas ładowania strony %f sekund.", $totaltime);
}

function stripslashes_deep($value) {
    if(isset($value)) {
        $value = is_array($value) ?
            array_map('stripslashes_deep', $value) :
            stripslashes($value);
    }
    return $value;
} 

function cleanup($data, $write=false) {
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = cleanup_lvl2($value, $write);
        }
    } else {
        $data = cleanup_lvl2($data, $write);
    }
    return $data;
}

function cleanup_lvl2($data, $write=false) {
    if (isset($data)) { // preserve NULL
        if (get_magic_quotes_gpc()) {
            $data = stripslashes($data);
        }
        if ($write) {
            $data = mysql_real_escape_string($data);
        }
    }
    return $data;
}

function skroc_tekst($tekst,$ile=30) { if (strlen($tekst)>$ile) return substr($tekst,0,$ile).'...'; else return $tekst; }

function correct_currency($kwota_wejciowa=null) {
	if (($kwota_wejciowa) || ($kwota_wejciowa!="0.00")) {
		$cena9 = str_replace(',','.',$kwota_wejciowa);
		$kropka = strpos($cena9,'.');
		if (($kropka==0) && ($cena9[0]=='.')) { 
			$cena9='0'.$cena9;
			$kropka = strpos($cena9,'.');
		}
		if ($kropka>0) {
			$pelnakwota = intval(substr($cena9,0,$kropka));
			$pokropce = substr($cena9,$kropka+1,2);
			if (strlen($pokropce)==1) $pokropce.='0';
		} else {
				$pelnakwota=intval($cena9);
				if ($kropka!=0) $pokropce = intval(substr($cena9,$kropka+1,2)); else $pokropce.='00';
				}
		$kwotakoncowa = $pelnakwota.'.'.$pokropce;
		return $kwotakoncowa;
	} else return "0.00";
}

function form_() { }
function _form() { echo "</form>"; }

function calculate_datediff($startdate,$stopdate, $params="dgms") {
	$data = strtotime($startdate);	
	if ($stopdate=='0000-00-00 00:00:00') $teraz = time(); else { $teraz=strtotime($stopdate); }
	$dni_r = ($teraz - $data) / (60 * 60 * 24);
	$dni_c = floor($dni_r);
	$godzin_r = ($dni_r - $dni_c) * 24;
	$godzin_c = floor($godzin_r);
	$minut_r = ($godzin_r - $godzin_c) * 60;
	$minut_c = floor($minut_r);
	$sekund_c = floor(($minut_r - $minut_c) * 60);

	if ($params) {
		$parametry = str_split($params);
		foreach ($parametry as $param) { 
			if ($param=='d') { if ($dni_c!=0) $wynik.=$dni_c." dni<br />"; }
			if ($param=='g') { if ($godzin_c!=0) $wynik.=$godzin_c." godzin"; }
			if ($param=='m') { if ($minut_c!=0) $wynik.=" ".$minut_c." minut"; }
			if ($param=='s') { if ($sekund_c!=0) $wynik.=" ".$sekund_c." sekund"; }
		}
	}
	return $wynik;
}

function sql_quote($value) {
	$value = htmlentities($value);
	if( get_magic_quotes_gpc() ) { $value = stripslashes($value); }
	//check if this function exists
	if( function_exists("mysql_real_escape_string") ) { $value = mysql_real_escape_string($value); }
	//for PHP version < 4.3.0 use addslashes
		else { $value = addslashes($value); }
	return $value;
}

function sanitize($input){
    $output=str_replace("'","`",$input);
	//$output=str_replace(array("\r\n", "\r", "\n"), "<br />", $string);
	return $output;
}

function AddWorkingDays($nofdays, $startdate) {
// AddWorkingDays("14","2010-05-10")

// definicja dni wolnych od pracy
$swieta = array("2013-01-01","2013-04-01","2013-05-01","2013-05-03","2013-05-30","2013-08-15","2013-11-01","2013-11-11","2013-12-25","2013-12-26");

$xxxx = 1;
$temp=strtotime($startdate);
   Do {
      $temp=$temp+86400;
      if (date("w",$temp)==0) $xxxx=$xxxx-1;
      $xxxx=$xxxx+1;
	  if (in_array(date("Y-m-d",$temp),$swieta)) $xxxx=$xxxx-1;
   } While ($xxxx<=$nofdays);

   return date("Y-m-d",$temp);
}

function SubstractWorkingDays($nofdays, $startdate) {
// AddWorkingDays("14","2010-05-10")

// definicja dni wolnych od pracy
$swieta = array("2013-01-01","2013-04-01","2013-05-01","2013-05-03","2013-05-30","2013-08-15","2013-11-01","2013-11-11","2013-12-25","2013-12-26");

$xxxx = 0;
$temp=strtotime($startdate);
   Do {
      $temp=$temp-86400;
      if (date("w",$temp)==0) {
		$xxxx=$xxxx-1;
		} else {
			$s = date("Y-m-d",$temp);
			if (in_array($s,$swieta)) $xxxx=$xxxx-1;
	  }
      $xxxx=$xxxx+1;
   } While ($xxxx<$nofdays);

   return date("Y-m-d",$temp);
}

function SubstractDays($nofdays, $startdate) {
// AddWorkingDays("14","2010-05-10")

// definicja dni wolnych od pracy
$swieta = array("2013-01-01","2013-04-01","2013-05-01","2013-05-03","2013-05-30","2013-08-15","2013-11-01","2013-11-11","2013-12-25","2013-12-26");

$xxxx = 0;
$temp=strtotime($startdate);
   Do {
		$temp=$temp-86400;
		$s = date("Y-m-d",$temp);
		if (in_array($s,$swieta)) $xxxx=$xxxx-1;
		$xxxx=$xxxx+1;
   } While ($xxxx<$nofdays);

   return date("Y-m-d",$temp);
}

function AddHoursToDate($nofhours, $startdate) {
// usage: AddHoursToDate("6","2010-06-10 22:50")
   return date("Y-m-d H:i",(strtotime($startdate)+$nofhours*3600));
}

function AddMinutesToDate($noofminutes, $startdate) {
	return date("Y-m-d H:i:s",strtotime($startdate)+$noofminutes*60);
}

function SubMinutesFromDate($noofminutes, $startdate) {
	return date("Y-m-d H:i:s",strtotime($startdate)-$noofminutes*60);
}

function SubMinutesFromDate2($noofminutes, $startdate) {
	return date("Y-m-d H:i",strtotime($startdate)-$noofminutes*60);
}

function rand_str($length = 16, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890') {
    // Length of character list
    $chars_length = (strlen($chars) - 1);

    // Start our string
    $string = $chars{rand(0, $chars_length)};
   
    // Generate random string
    for ($i = 1; $i < $length; $i = strlen($string))
    {
        // Grab a random character from our list
        $r = $chars{rand(0, $chars_length)};
       
        // Make sure the same two characters don't appear next to each other
        if ($r != $string{$i - 1}) $string .=  $r;
    }
   
    // Return the string
    return $string;
}

function HD_RoznicaDat($startdate,$stopdate, $params="dgms") {
	$data = strtotime($startdate);	
	if ($stopdate=='0000-00-00 00:00:00') $teraz = time(); else { $teraz=strtotime($stopdate); }
	$dni_r = ($teraz - $data) / (60 * 60 * 24);
	$dni_c = floor($dni_r);
	$godzin_r = ($dni_r - $dni_c) * 24;
	$godzin_c = floor($godzin_r);
	$minut_r = ($godzin_r - $godzin_c) * 60;
	$minut_c = floor($minut_r);
	$sekund_c = floor(($minut_r - $minut_c) * 60);

	if ($params) {
		$parametry = str_split($params);
		foreach ($parametry as $param) { 
			if ($param=='d') { if ($dni_c!=0) $wynik.=$dni_c." dni "; }
			if ($param=='g') { if ($godzin_c!=0) $wynik.=$godzin_c." h"; }
			if ($param=='m') { if ($minut_c!=0) $wynik.=" ".$minut_c." m"; }
			if ($param=='s') { if ($sekund_c!=0) $wynik.=" ".$sekund_c." s"; }
		}
	}
	return $wynik;
}

function HD_RoznicaDat_c($startdate,$stopdate, $params="dgms") {
	$data = strtotime($startdate);	
	if ($stopdate=='0000-00-00 00:00:00') $teraz = time(); else { $teraz=strtotime($stopdate); }
	$dni_r = ($teraz - $data) / (60 * 60 * 24);
	$dni_c = floor($dni_r);
	$godzin_r = ($dni_r - $dni_c) * 24;
	$godzin_c = floor($godzin_r);
	$minut_r = ($godzin_r - $godzin_c) * 60;
	$minut_c = floor($minut_r);
	$sekund_c = floor(($minut_r - $minut_c) * 60);

	if ($params) {
		$parametry = str_split($params);
		foreach ($parametry as $param) { 
			if ($param=='d') { if ($dni_c!=0) $wynik.=$dni_c; }
		}
	}
	return $wynik;
}

function HD_RoznicaDat_w_m($startdate,$stopdate) {
	$data = strtotime($startdate);	
	if ($stopdate=='0000-00-00 00:00:00') $teraz = time(); else { $teraz=strtotime($stopdate); }
	$dni_r = ($teraz - $data) / (60 * 60 * 24);
	$dni_c = floor($dni_r);
	$godzin_r = ($dni_r - $dni_c) * 24*60;
	$godzin_c = floor($godzin_r);
	$minut_r = ($godzin_r - $godzin_c) * 60;
	$minut_c = floor($minut_r);
	$sekund_c = floor(($minut_r - $minut_c));

	return ($godzin_c + $minut_c + $sekund_c);
}

function HD_RoznicaDat_w_s($startdate,$stopdate, $params="dgms") {
	$data = strtotime($startdate);	
	if ($stopdate=='0000-00-00 00:00:00') $teraz = time(); else { $teraz=strtotime($stopdate); }
	$dni_r = ($teraz - $data) / (60 * 60 * 24);
	$dni_c = floor($dni_r);
	$godzin_r = ($dni_r - $dni_c) * 24;
	$godzin_c = floor($godzin_r);
	$minut_r = ($godzin_r - $godzin_c) * 60;
	$minut_c = floor($minut_r);
	$sekund_c = floor(($minut_r - $minut_c) * 60);

	if ($params) {
		$parametry = str_split($params);
		foreach ($parametry as $param) { 
			if ($param=='d') { if ($dni_c!=0) $wynik.=$dni_c." dni "; }
			if ($param=='g') { if ($godzin_c!=0) $wynik.=$godzin_c." h"; }
			if ($param=='m') { if ($minut_c!=0) $wynik.=" ".$minut_c." m"; }
			if ($param=='s') { if ($sekund_c!=0) $wynik.=" ".$sekund_c." s"; }
		}
	}
	return ($teraz - $data);
}

function sek2days($value) { return floor($value/(60*60*24)); }

function days2sek($value) {	return $value*60*60*24; }

function sek2hours($value) { 
	$dni_r = ($value) / (60 * 60 * 24);	$dni_c = floor($dni_r);	$godzin_r = ($dni_r - $dni_c) * 24;	$godzin_c = floor($godzin_r);	
	return $godzin_c;
}

function hours2sek($value) { return $value*60*60; }

function sek2minutes($value) {
	$dni_r = ($value) / (60 * 60 * 24);	$dni_c = floor($dni_r);	$godzin_r = ($dni_r - $dni_c) * 24;	$godzin_c = floor($godzin_r);	$minut_r = ($godzin_r - $godzin_c) * 60;	$minut_c = floor($minut_r);	
	return $minut_c;
}

function minutes2sek($value) { return $value*60; }

function sek2seconds($value) {
	$dni_r = ($value) / (60 * 60 * 24);	$dni_c = floor($dni_r);	$godzin_r = ($dni_r - $dni_c) * 24;	$godzin_c = floor($godzin_r);	$minut_r = ($godzin_r - $godzin_c) * 60;	$minut_c = floor($minut_r);	
	$sekund_c = floor(($minut_r - $minut_c) * 60);
	return $sekund_c;
}

function seconds2sek($value) { return $value; }

function FormatFloat($wartosc, $ile_miejsc_po_przecinku) {
	printf("%.0".$ile_miejsc_po_przecinku."f", $wartosc);
}

function minutes2hours($mins,$format) { 
	if ($mins < 0) { 
		$min = Abs($mins); 
	} else { 
		$min = ceil($mins); 
	} 
	//if ($min==0) return 0;
	$H = Floor($min / 60); 
	$M = ($min - ($H * 60)) / 100; 
	$hours = $H +  $M; 
	if ($mins < 0) { 
		$hours = $hours * (-1); 
	} 
	$expl = explode(".", $hours); 
	$H = $expl[0]; 
	if (empty($expl[1])) { 
		$expl[1] = 00; 
	} 
	$M = $expl[1]; 
	if (strlen($M) < 2) { 
		$M = $M . 0; 
	} 
	if ($format=='short') {
		$hours = $H . "h " . $M."m"; 
	} else $hours = $H . " godzin " . $M." minut(y)"; 

	return $hours; 
}

/*
** check a date
** dd.mm.yyyy || mm/dd/yyyy || dd-mm-yyyy || yyyy-mm-dd
*/

function check_date($date) {
    if(strlen($date) == 10) {
        $pattern = '/\.|\/|-/i';    // . or / or -
        preg_match($pattern, $date, $char);
       
        $array = preg_split($pattern, $date, -1, PREG_SPLIT_NO_EMPTY);
       
        if(strlen($array[2]) == 4) {
            // dd.mm.yyyy || dd-mm-yyyy
            if($char[0] == "."|| $char[0] == "-") {
                $month = $array[1];
                $day = $array[0];
                $year = $array[2];
            }
            // mm/dd/yyyy    # Common U.S. writing
            if($char[0] == "/") {
                $month = $array[0];
                $day = $array[1];
                $year = $array[2];
            }
        }
        // yyyy-mm-dd    # iso 8601
        if(strlen($array[0]) == 4 && $char[0] == "-") {
            $month = $array[1];
            $day = $array[2];
            $year = $array[0];
        }
        if(checkdate($month, $day, $year)) {    //Validate Gregorian date
            return TRUE;
       
        } else {
            return FALSE;
        }
    }else {
        return FALSE;    // more or less 10 chars
    }
}

function jaki_dzien_full($data) {
	$dzien_from_date = substr($data,8,2);
	$miesiac_from_date = substr($data,5,2);
	$rok_from_date = substr($data,0,4);

	$dd=date('l',mktime(0,0,0,$miesiac_from_date,$dzien_from_date,$rok_from_date));
	$komorka_pracuje = 0;
	$addday = 0;
	switch ($dd) {
		case "Monday"		: $day = '[poniedziałek]'; break;
		case "Tuesday"		: $day = '[wtorek]'; break;
		case "Wednesday"	: $day = '[środa]'; break;
		case "Thursday"		: $day = '[czwartek]'; break;
		case "Friday"		: $day = '[piątek]'; break;
		case "Saturday"		: $day = '[sobota]'; break;
		case "Sunday"		: $day = '[niedziela]'; break;
	}
	return $day;
}

function jaki_dzien($data) {
	$dzien_from_date = substr($data,8,2);
	$miesiac_from_date = substr($data,5,2);
	$rok_from_date = substr($data,0,4);

	$dd=date('l',mktime(0,0,0,$miesiac_from_date,$dzien_from_date,$rok_from_date));
	$komorka_pracuje = 0;
	$addday = 0;
	switch ($dd) {
		case "Monday"		: $day = 'pn'; break;
		case "Tuesday"		: $day = 'wt'; break;
		case "Wednesday"	: $day = 'sr'; break;
		case "Thursday"		: $day = 'cz'; break;
		case "Friday"		: $day = 'pt'; break;
		case "Saturday"		: $day = 'so'; break;
		case "Sunday"		: $day = 'ni'; break;
	}
	return $day;
}

function jaki_dzien_upper($data) {
	$dzien_from_date = substr($data,8,2);
	$miesiac_from_date = substr($data,5,2);
	$rok_from_date = substr($data,0,4);

	$dd=date('l',mktime(0,0,0,$miesiac_from_date,$dzien_from_date,$rok_from_date));
	$komorka_pracuje = 0;
	$addday = 0;
	switch ($dd) {
		case "Monday"		: $day = 'PN'; break;
		case "Tuesday"		: $day = 'WT'; break;
		case "Wednesday"	: $day = 'SR'; break;
		case "Thursday"		: $day = 'CZ'; break;
		case "Friday"		: $day = 'PT'; break;
		case "Saturday"		: $day = 'SO'; break;
		case "Sunday"		: $day = 'NI'; break;
	}
	return $day;
}

function czy_pracuje($data, $wt) {

	$dni = explode(";",$wt);
	$pn = explode("@",$dni[0]);
	$wt = explode("@",$dni[1]);
	$sr = explode("@",$dni[2]);
	$cz = explode("@",$dni[3]);
	$pt = explode("@",$dni[4]);
	$so = explode("@",$dni[5]);
	$ni = explode("@",$dni[6]);
	$ok = 0;
	
	if ((jaki_dzien($data))=='pn') if ($pn[1]!='-') $ok = 1; 
	if ((jaki_dzien($data))=='wt') if ($wt[1]!='-') $ok = 1; 
	if ((jaki_dzien($data))=='sr') if ($sr[1]!='-') $ok = 1; 
	if ((jaki_dzien($data))=='cz') if ($cz[1]!='-') $ok = 1; 
	if ((jaki_dzien($data))=='pt') if ($pt[1]!='-') $ok = 1; 
	if ((jaki_dzien($data))=='so') if ($so[1]!='-') $ok = 1; 
	if ((jaki_dzien($data))=='ni') if ($ni[1]!='-') $ok = 1; 
	
	if ($ok==1) { return true; } else { return false; }
}

function godzina_stop($data, $wt) {

	$dni = explode(";",$wt);
	$pn = explode("@",$dni[0]);
	$wt = explode("@",$dni[1]);
	$sr = explode("@",$dni[2]);
	$cz = explode("@",$dni[3]);
	$pt = explode("@",$dni[4]);
	$so = explode("@",$dni[5]);
	$ni = explode("@",$dni[6]);
	$ok = 0;
	
	if ((jaki_dzien($data))=='pn') if ($pn[1]!='-') { $t = explode("-",$pn[1]); $ok = $data." ".$t[1]; }
	if ((jaki_dzien($data))=='wt') if ($wt[1]!='-') { $t = explode("-",$wt[1]); $ok = $data." ".$t[1]; }
	if ((jaki_dzien($data))=='sr') if ($sr[1]!='-') { $t = explode("-",$sr[1]); $ok = $data." ".$t[1]; }
	if ((jaki_dzien($data))=='cz') if ($cz[1]!='-') { $t = explode("-",$cz[1]); $ok = $data." ".$t[1]; }
	if ((jaki_dzien($data))=='pt') if ($pt[1]!='-') { $t = explode("-",$pt[1]); $ok = $data." ".$t[1]; }
	if ((jaki_dzien($data))=='so') if ($so[1]!='-') { $t = explode("-",$so[1]); $ok = $data." ".$t[1]; }
	if ((jaki_dzien($data))=='ni') if ($ni[1]!='-') { $t = explode("-",$ni[1]); $ok = $data." ".$t[1]; }
	
	return $ok;
}

function godzina_start($data, $wt) {

	$dni = explode(";",$wt);
	$pn = explode("@",$dni[0]);
	$wt = explode("@",$dni[1]);
	$sr = explode("@",$dni[2]);
	$cz = explode("@",$dni[3]);
	$pt = explode("@",$dni[4]);
	$so = explode("@",$dni[5]);
	$ni = explode("@",$dni[6]);
	$ok = 0;
	
	if ((jaki_dzien($data))=='pn') if ($pn[1]!='-') { $t = explode("-",$pn[1]); $ok = $data." ".$t[0]; }
	if ((jaki_dzien($data))=='wt') if ($wt[1]!='-') { $t = explode("-",$wt[1]); $ok = $data." ".$t[0]; }
	if ((jaki_dzien($data))=='sr') if ($sr[1]!='-') { $t = explode("-",$sr[1]); $ok = $data." ".$t[0]; }
	if ((jaki_dzien($data))=='cz') if ($cz[1]!='-') { $t = explode("-",$cz[1]); $ok = $data." ".$t[0]; }
	if ((jaki_dzien($data))=='pt') if ($pt[1]!='-') { $t = explode("-",$pt[1]); $ok = $data." ".$t[0]; }
	if ((jaki_dzien($data))=='so') if ($so[1]!='-') { $t = explode("-",$so[1]); $ok = $data." ".$t[0]; }
	if ((jaki_dzien($data))=='ni') if ($ni[1]!='-') { $t = explode("-",$ni[1]); $ok = $data." ".$t[0]; }
	
	return $ok;
}

function ilosc_godzin_w_dniu($data,$wt,$zglwpis) {
	$dni = explode(";",$wt);
	if ((jaki_dzien($data))=='pn') $temp = explode("@",$dni[0]);
	if ((jaki_dzien($data))=='wt') $temp = explode("@",$dni[1]);
	if ((jaki_dzien($data))=='sr') $temp = explode("@",$dni[2]);
	if ((jaki_dzien($data))=='cz') $temp = explode("@",$dni[3]);
	if ((jaki_dzien($data))=='pt') $temp = explode("@",$dni[4]);
	if ((jaki_dzien($data))=='so') $temp = explode("@",$dni[5]);
	if ((jaki_dzien($data))=='ni') $temp = explode("@",$dni[6]);
	
	$temp1 = explode("-",$temp[1]);
	$hmstart = explode(":",$temp1[0]);
	$hmstop = explode(":",$temp1[1]);
	
	$hmwpis = explode(":",$zglwpis);
	
	if ($temp=='-') return 0;	
	$w = ((($hmstop[0] *60)+ $hmstop[1])-(($hmwpis[0] *60)+ $hmwpis[1]));
	//echo $w;
	if ($w<0) $w = 0;
	//echo ">>>".$w."<<<";
	return $w;
}

function ilosc_godzin_w_dniu1s($data,$wt,$zglwpis,$_serwis_wt_) {
//echo "<br/ >$data, ,$zglwpis<br />";

	if ($_serwis_wt_==null) $_serwis_wt_ = $serwis_working_time;

	$dni_serwis = explode(";",$_serwis_wt_);

	if ((jaki_dzien($data))=='pn') { $temp_s = explode("@",$dni_serwis[0]); }
	if ((jaki_dzien($data))=='wt') { $temp_s = explode("@",$dni_serwis[1]); }
	if ((jaki_dzien($data))=='sr') { $temp_s = explode("@",$dni_serwis[2]); }
	if ((jaki_dzien($data))=='cz') { $temp_s = explode("@",$dni_serwis[3]); }
	if ((jaki_dzien($data))=='pt') { $temp_s = explode("@",$dni_serwis[4]); }
	if ((jaki_dzien($data))=='so') { $temp_s = explode("@",$dni_serwis[5]); }
	if ((jaki_dzien($data))=='ni') { $temp_s = explode("@",$dni_serwis[6]); }

	// godziny pracy serwisu
	$temp1_serwis = explode("-",$temp_s[1]);
	$hmstart_s = explode(":",$temp1_serwis[0]);
	$hmstop_s = explode(":",$temp1_serwis[1]);

	$dni = explode(";",$wt);
	if ((jaki_dzien($data))=='pn') $temp = explode("@",$dni[0]);
	if ((jaki_dzien($data))=='wt') $temp = explode("@",$dni[1]);
	if ((jaki_dzien($data))=='sr') $temp = explode("@",$dni[2]);
	if ((jaki_dzien($data))=='cz') $temp = explode("@",$dni[3]);
	if ((jaki_dzien($data))=='pt') $temp = explode("@",$dni[4]);
	if ((jaki_dzien($data))=='so') $temp = explode("@",$dni[5]);
	if ((jaki_dzien($data))=='ni') $temp = explode("@",$dni[6]);
	
	$temp1 = explode("-",$temp[1]);
	$hmstart = explode(":",$temp1[0]);
	$hmstop = explode(":",$temp1[1]);

	if ($hmstop>=$hmstop_s) $hmstop = $hmstop_s;
	if ($hmstart<=$hmstart_s) $hmstart = $hmstart_s;
	
	$hmwpis = explode(":",$zglwpis);
	
	if ($temp=='-') return 0;	
	$w = ((($hmstop[0] *60)+ $hmstop[1])-(($hmwpis[0] *60)+ $hmwpis[1]));
	//echo $w;
	if ($w<0) $w = 0;
	return $w;
}

function ilosc_godzin_w_dniu1($data,$wt,$zglwpis) {

	$dni = explode(";",$wt);
	if ((jaki_dzien($data))=='pn') $temp = explode("@",$dni[0]);
	if ((jaki_dzien($data))=='wt') $temp = explode("@",$dni[1]);
	if ((jaki_dzien($data))=='sr') $temp = explode("@",$dni[2]);
	if ((jaki_dzien($data))=='cz') $temp = explode("@",$dni[3]);
	if ((jaki_dzien($data))=='pt') $temp = explode("@",$dni[4]);
	if ((jaki_dzien($data))=='so') $temp = explode("@",$dni[5]);
	if ((jaki_dzien($data))=='ni') $temp = explode("@",$dni[6]);
	
	$temp1 = explode("-",$temp[1]);
	$hmstart = explode(":",$temp1[0]);
	$hmstop = explode(":",$temp1[1]);
	
	$hmwpis = explode(":",$zglwpis);
	
	if ($temp=='-') return 0;	
	$w = ((($hmstop[0] *60)+ $hmstop[1])-(($hmstart[0] *60)+ $hmstart[1]));
	//echo $w;
	if ($w<0) $w = 0;

	return $w;
}

function AddHoursToDateSimple($nofhours, $startdate) {
// usage: AddHoursToDate("6","2010-06-10 22:50")
   return date("Y-m-d",(strtotime($startdate)+$nofhours*3600));
}

function godzina_stop1($data, $wt) {

	$dni = explode(";",$wt);
	$pn = explode("@",$dni[0]);
	$wt = explode("@",$dni[1]);
	$sr = explode("@",$dni[2]);
	$cz = explode("@",$dni[3]);
	$pt = explode("@",$dni[4]);
	$so = explode("@",$dni[5]);
	$ni = explode("@",$dni[6]);
	$ok = 0;
	
	if ((jaki_dzien($data))=='pn') if ($pn[1]!='-') { $t = explode("-",$pn[1]); $ok = $t[1]; }
	if ((jaki_dzien($data))=='wt') if ($wt[1]!='-') { $t = explode("-",$wt[1]); $ok = $t[1]; }
	if ((jaki_dzien($data))=='sr') if ($sr[1]!='-') { $t = explode("-",$sr[1]); $ok = $t[1]; }
	if ((jaki_dzien($data))=='cz') if ($cz[1]!='-') { $t = explode("-",$cz[1]); $ok = $t[1]; }
	if ((jaki_dzien($data))=='pt') if ($pt[1]!='-') { $t = explode("-",$pt[1]); $ok = $t[1]; }
	if ((jaki_dzien($data))=='so') if ($so[1]!='-') { $t = explode("-",$so[1]); $ok = $t[1]; }
	if ((jaki_dzien($data))=='ni') if ($ni[1]!='-') { $t = explode("-",$ni[1]); $ok = $t[1]; }
	
	return $ok;
}

function godzina_start1($data, $wt) {

	$dni = explode(";",$wt);
	$pn = explode("@",$dni[0]);
	$wt = explode("@",$dni[1]);
	$sr = explode("@",$dni[2]);
	$cz = explode("@",$dni[3]);
	$pt = explode("@",$dni[4]);
	$so = explode("@",$dni[5]);
	$ni = explode("@",$dni[6]);
	$ok = 0;
	
	if ((jaki_dzien($data))=='pn') if ($pn[1]!='-') { $t = explode("-",$pn[1]); $ok = $t[0]; }
	if ((jaki_dzien($data))=='wt') if ($wt[1]!='-') { $t = explode("-",$wt[1]); $ok = $t[0]; }
	if ((jaki_dzien($data))=='sr') if ($sr[1]!='-') { $t = explode("-",$sr[1]); $ok = $t[0]; }
	if ((jaki_dzien($data))=='cz') if ($cz[1]!='-') { $t = explode("-",$cz[1]); $ok = $t[0]; }
	if ((jaki_dzien($data))=='pt') if ($pt[1]!='-') { $t = explode("-",$pt[1]); $ok = $t[0]; }
	if ((jaki_dzien($data))=='so') if ($so[1]!='-') { $t = explode("-",$so[1]); $ok = $t[0]; }
	if ((jaki_dzien($data))=='ni') if ($ni[1]!='-') { $t = explode("-",$ni[1]); $ok = $t[0]; }
	
	return $ok;
}

function AddWorkingDays_UP($nofdays, $startdate,$week1) {
// AddWorkingDays("14","2010-05-10")

// definicja dni wolnych od pracy
$swieta = array("2013-01-01","2013-04-01","2013-05-01","2013-05-03","2013-05-30","2013-08-15","2013-11-01","2013-11-11","2013-12-25","2013-12-26");

$xxxx = 1;
$temp=strtotime($startdate);
   Do {
      $temp=$temp+86400;
      if (date("w",$temp)==0) $xxxx=$xxxx-1;
      $xxxx=$xxxx+1;
	  if (in_array(date("Y-m-d",$temp),$swieta)) $xxxx=$xxxx-1;
//	  if (czy_pracuje(date("Y-m-d",$temp),$week1)==false) $xxxx=$xxxx-1;
   } While ($xxxx<=$nofdays);

   return date("Y-m-d",$temp);
}

function AddMinutesToHour($noofminutes, $startdate) {
	return date("H:i",strtotime($startdate)+$noofminutes*60);
}

function ile_minut_pomiedzy_godzinami($startdate,$stopdate,$serwis_wt,$kkk) {
$stopdate1 = $stopdate;
$startdate1 = $startdate;

	$dni_serwis = explode(";",$serwis_wt);

	if ((jaki_dzien($data))=='pn') { $temp_s = explode("@",$dni_serwis[0]); }
	if ((jaki_dzien($data))=='wt') { $temp_s = explode("@",$dni_serwis[1]); }
	if ((jaki_dzien($data))=='sr') { $temp_s = explode("@",$dni_serwis[2]); }
	if ((jaki_dzien($data))=='cz') { $temp_s = explode("@",$dni_serwis[3]); }
	if ((jaki_dzien($data))=='pt') { $temp_s = explode("@",$dni_serwis[4]); }
	if ((jaki_dzien($data))=='so') { $temp_s = explode("@",$dni_serwis[5]); }
	if ((jaki_dzien($data))=='ni') { $temp_s = explode("@",$dni_serwis[6]); }
	
	// godziny pracy komorki
	$hmstart = explode(":",$startdate1);
	$hmstop = explode(":",$stopdate1);
	
	// godziny pracy serwisu
	$temp1_serwis = explode("-",$temp_s[1]);
	$hmstart_s = explode(":",$temp1_serwis[0]);
	$hmstop_s = explode(":",$temp1_serwis[1]);
		
	//echo "$stopdate | $temp1_serwis[1]";
	
	// jeżeli godzina rozpoczecia pracy komorki jest wczesniejsza niz godzina rozpoczecia pracy serwisu => godzina rozpoczecia pracy komorki otrzymuje wartosc z serwisu
	if ((strtotime($startdate1))<(strtotime($temp1_serwis[0].":00"))) { $startdate1 = $temp1_serwis[0]; }
	
	// jeżeli godzina zakończenia pracy komorki jest późniejsza niz godzina zakończenia pracy serwisu => godzina zakończenia  pracy komorki otrzymuje wartosc z serwisu
	//echo "<font color=red>".strtotime($stopdate1)." - ".strtotime($temp1_serwis[1].":00")."</font>";
	if ($kkk>0)
		if ((strtotime($stopdate1))>(strtotime($temp1_serwis[1]))) { $stopdate1 = $temp1_serwis[1];}

	$wynik = (strtotime($stopdate1)-strtotime($startdate1)) / 60;
	
	return $wynik;
}

function ilosc_minut_pracujacych_w_dniu($data,$wt,$serwis_wt) {
	$dni = explode(";",$wt);
	$dni_serwis = explode(";",$serwis_wt);
	
	if ((jaki_dzien($data))=='pn') { $temp = explode("@",$dni[0]); $dzien = 'pn'; $temp_s = explode("@",$dni_serwis[0]); }
	if ((jaki_dzien($data))=='wt') { $temp = explode("@",$dni[1]); $dzien = 'wt'; $temp_s = explode("@",$dni_serwis[1]); }
	if ((jaki_dzien($data))=='sr') { $temp = explode("@",$dni[2]); $dzien = 'sr'; $temp_s = explode("@",$dni_serwis[2]); }
	if ((jaki_dzien($data))=='cz') { $temp = explode("@",$dni[3]); $dzien = 'cz'; $temp_s = explode("@",$dni_serwis[3]); }
	if ((jaki_dzien($data))=='pt') { $temp = explode("@",$dni[4]); $dzien = 'pt'; $temp_s = explode("@",$dni_serwis[4]); }
	if ((jaki_dzien($data))=='so') { $temp = explode("@",$dni[5]); $dzien = 'so'; $temp_s = explode("@",$dni_serwis[5]); }
	if ((jaki_dzien($data))=='ni') { $temp = explode("@",$dni[6]); $dzien = 'ni'; $temp_s = explode("@",$dni_serwis[6]); }

	// godziny pracy komorki
	$temp1 = explode("-",$temp[1]);
	$hmstart = explode(":",$temp1[0]);
	$hmstop = explode(":",$temp1[1]);
		
	// godziny pracy serwisu
	$temp1_serwis = explode("-",$temp_s[1]);
	$hmstart_s = explode(":",$temp1_serwis[0]);
	$hmstop_s = explode(":",$temp1_serwis[1]);

//	echo "<font color=pink>K: ".$temp1[0]." ".$temp1[1]." | S:".$temp1_serwis[0]." ".$temp1_serwis[1]."</font>";
	
	// jeżeli godzina rozpoczecia pracy komorki jest wczesniejsza niz godzina rozpoczecia pracy serwisu => godzina rozpoczecia pracy komorki otrzymuje wartosc z serwisu
	if ((strtotime($temp1[0]))<(strtotime($temp1_serwis[0]))) { $temp1[0]=$temp1_serwis[0]; $hmstart = explode(":",$temp1[0]); }
	
	// jeżeli godzina zakończenia pracy komorki jest późniejsza niz godzina zakończenia pracy serwisu => godzina zakończenia  pracy komorki otrzymuje wartosc z serwisu
	if ((strtotime($temp1[1]))>(strtotime($temp1_serwis[1]))) { $temp1[1]=$temp1_serwis[1]; $hmstop = explode(":",$temp1[1]); }

//	echo " | WYBRANE: <font color=pink>".$temp1[0]." ".$temp1[1]."</font> | ";
	
	//echo " | <b>$hmstop[0]:$hmstop[1] $hmstart[0]:$hmstart[1]</b>";
	
	if ($temp=='-') return 0;	
	
	$w = ((($hmstop[0] *60)+ $hmstop[1])-(($hmstart[0] *60)+ $hmstart[1]));
	
	//echo "****".$w."****";
	
	if ($w<0) $w = 0;
	return $w;
}

function godzina_start1s($data, $wt,$serwis_wt) {

	$dni_serwis = explode(";",$serwis_wt);

	if ((jaki_dzien($data))=='pn') { $temp_s = explode("@",$dni_serwis[0]); }
	if ((jaki_dzien($data))=='wt') { $temp_s = explode("@",$dni_serwis[1]); }
	if ((jaki_dzien($data))=='sr') { $temp_s = explode("@",$dni_serwis[2]); }
	if ((jaki_dzien($data))=='cz') { $temp_s = explode("@",$dni_serwis[3]); }
	if ((jaki_dzien($data))=='pt') { $temp_s = explode("@",$dni_serwis[4]); }
	if ((jaki_dzien($data))=='so') { $temp_s = explode("@",$dni_serwis[5]); }
	if ((jaki_dzien($data))=='ni') { $temp_s = explode("@",$dni_serwis[6]); }
				
	// godziny pracy serwisu
	$temp1_serwis = explode("-",$temp_s[1]);
	$hmstart_s = explode(":",$temp1_serwis[0]);
	//$hmstop_s = explode(":",$temp1_serwis[1]);
	
	$dni = explode(";",$wt);
	$pn = explode("@",$dni[0]);
	$wt = explode("@",$dni[1]);
	$sr = explode("@",$dni[2]);
	$cz = explode("@",$dni[3]);
	$pt = explode("@",$dni[4]);
	$so = explode("@",$dni[5]);
	$ni = explode("@",$dni[6]);
	$ok = 0;
	
	if ((jaki_dzien($data))=='pn') if ($pn[1]!='-') { $t = explode("-",$pn[1]); $ok = $t[0]; }
	if ((jaki_dzien($data))=='wt') if ($wt[1]!='-') { $t = explode("-",$wt[1]); $ok = $t[0]; }
	if ((jaki_dzien($data))=='sr') if ($sr[1]!='-') { $t = explode("-",$sr[1]); $ok = $t[0]; }
	if ((jaki_dzien($data))=='cz') if ($cz[1]!='-') { $t = explode("-",$cz[1]); $ok = $t[0]; }
	if ((jaki_dzien($data))=='pt') if ($pt[1]!='-') { $t = explode("-",$pt[1]); $ok = $t[0]; }
	if ((jaki_dzien($data))=='so') if ($so[1]!='-') { $t = explode("-",$so[1]); $ok = $t[0]; }
	if ((jaki_dzien($data))=='ni') if ($ni[1]!='-') { $t = explode("-",$ni[1]); $ok = $t[0]; }
	
	//echo $temp1_serwis[0];
	
	if ((strtotime($temp1_serwis[0]))>(strtotime($ok))) { $ok = $temp1_serwis[0]; }
	
	return $ok;
}

function godzina_stop1s($data, $wt,$serwis_wt) {
	
	$dni_serwis = explode(";",$serwis_wt);

	if ((jaki_dzien($data))=='pn') { $temp_s = explode("@",$dni_serwis[0]); }
	if ((jaki_dzien($data))=='wt') { $temp_s = explode("@",$dni_serwis[1]); }
	if ((jaki_dzien($data))=='sr') { $temp_s = explode("@",$dni_serwis[2]); }
	if ((jaki_dzien($data))=='cz') { $temp_s = explode("@",$dni_serwis[3]); }
	if ((jaki_dzien($data))=='pt') { $temp_s = explode("@",$dni_serwis[4]); }
	if ((jaki_dzien($data))=='so') { $temp_s = explode("@",$dni_serwis[5]); }
	if ((jaki_dzien($data))=='ni') { $temp_s = explode("@",$dni_serwis[6]); }
		
	// godziny pracy serwisu
	$temp1_serwis = explode("-",$temp_s[1]);
	$hmstart_s = explode(":",$temp1_serwis[0]);
	//$hmstop_s = explode(":",$temp1_serwis[1]);
	
	$dni = explode(";",$wt);
	$pn = explode("@",$dni[0]);
	$wt = explode("@",$dni[1]);
	$sr = explode("@",$dni[2]);
	$cz = explode("@",$dni[3]);
	$pt = explode("@",$dni[4]);
	$so = explode("@",$dni[5]);
	$ni = explode("@",$dni[6]);
	$ok = 0;
	
	if ((jaki_dzien($data))=='pn') if ($pn[1]!='-') { $t = explode("-",$pn[1]); $ok = $t[1]; }
	if ((jaki_dzien($data))=='wt') if ($wt[1]!='-') { $t = explode("-",$wt[1]); $ok = $t[1]; }
	if ((jaki_dzien($data))=='sr') if ($sr[1]!='-') { $t = explode("-",$sr[1]); $ok = $t[1]; }
	if ((jaki_dzien($data))=='cz') if ($cz[1]!='-') { $t = explode("-",$cz[1]); $ok = $t[1]; }
	if ((jaki_dzien($data))=='pt') if ($pt[1]!='-') { $t = explode("-",$pt[1]); $ok = $t[1]; }
	if ((jaki_dzien($data))=='so') if ($so[1]!='-') { $t = explode("-",$so[1]); $ok = $t[1]; }
	if ((jaki_dzien($data))=='ni') if ($ni[1]!='-') { $t = explode("-",$ni[1]); $ok = $t[1]; }
	
	if ((strtotime($temp1_serwis[1]))<(strtotime($ok))) { $ok = $temp1_serwis[1]; }
		
	return $ok;
}

function MinutesBetween($data1_start, $data1_stop, $_wt_, $_serwis_wt_) {
//	echo "<br />$data1_start | $data1_stop | $_wt_ | $_serwis_wt_";
	
	if ($data1_start==$data1_stop) return 0;
	
	$sama_data1 = substr($data1_start,0,10);				//echo "<br />Sama data 1: <b>[$sama_data1]</b><br />";
	$sama_data2 = substr($data1_stop,0,10);				//echo "Sama data 2: <b>[$sama_data2]</b><br />";

	$sama_godzina1 = substr($data1_start,11,8);			//echo "<br />Sama godzina 1: <b>[$sama_godzina1]</b><br />";
	$sama_godzina2 = substr($data1_stop,11,8);			//echo "Sama godzina 2: <b>[$sama_godzina2]</b><br />";

	$sama_godzina1short = substr($data1_start,11,5);		//echo "<br />Sama godzina 1 short: <b>[$sama_godzina1short]</b><br />";
	$sama_godzina2short = substr($data1_stop,11,5);		//echo "Sama godzina 2 short: <b>[$sama_godzina2short]</b><br />";

	$temp_data1_str1 = strtotime($sama_data1);		// data początkowa
	$temp_data2_str = strtotime($sama_data2);		// data końcowa

	$sama_godzina2 = substr($data1_stop,11,8);			//echo "Sama godzina 2: <b>[$sama_godzina2]</b><br />";

	$kkkk = 0;
	$ile_dni_pracujacych = 0;
	$minut_w_pelnych_dniach_pracujacych = 0;

	Do {
		$ilosc_minut_pracujacych_w_dniu_do_dodania = 0;				// wyzeruj licznik minut do dodania danego dnia
		$temp_data1_str = strtotime($sama_data1)+($kkkk*86400);		// $temp_data1_str-> data w milisekundach
		$temp_data1_str_normal = date("Y-m-d",$temp_data1_str);		// $temp_data1_str_normal 	-> data w formacie YYYY-MM-DD
		
		// sprawdź czy aktualny dzień jest pracujący
		if (czy_pracuje($temp_data1_str_normal,$_wt_)) {
			$ile_dni_pracujacych++;	
			//echo "<b><font color=green>PRACUJACY</font></b> | <b><font color=green>".$temp_data1_str_normal."</font></b> | ";
			
			// jeżeli badany dzień nie jest dniem początkowym i nie jest dniem końcowym => dodaj ilość minut roboczych w danym dniu
			if (($temp_data1_str != $temp_data2_str) &&	($temp_data1_str != $temp_data1_str1)) {
				$ilosc_minut_pracujacych_w_dniu_do_dodania = ilosc_minut_pracujacych_w_dniu($temp_data1_str_normal,$_wt_,$_serwis_wt_);	
				$minut_w_pelnych_dniach_pracujacych += $ilosc_minut_pracujacych_w_dniu_do_dodania;
			}
			
			//echo "(k=$kkkk) | ";		
			// jeżeli badany dzień jest równy dniowi początkowemu wtedy wylicz ile pozostało minut do końca pracy dnia
			if ($kkkk == 0) {
				$sama_godzina_graniczna_start = godzina_start1s($temp_data1_str_normal,$_wt_,$_serwis_wt_);
				
				// jeżeli godzina początkowa jest po rozpoczęciu dnia
				if ((strtotime($sama_godzina1)) < strtotime($sama_godzina_graniczna_start)) {
					$sama_godzina1 = godzina_start1s($temp_data1_str_normal,$_wt_,$_serwis_wt_);	
				}

			// jeżeli daty dą te same
			if (($temp_data1_str == $temp_data1_str1) && ($temp_data1_str == $temp_data2_str)) {

				// ustaw graniczną godzinę pracy w danym dniu 
				$sama_godzina_graniczna_stop = godzina_stop1s($temp_data1_str_normal,$_wt_,$_serwis_wt_);
				
				// jeżeli ten sam dzień i godzina zakończenia jest < od granicznej godziny pracy
				if ($sama_godzina_graniczna_stop>=$sama_godzina2short) {				
					$minut_pracujacych_w_dniu_startowym = ile_minut_pomiedzy_godzinami($sama_godzina1, $sama_godzina2,$_serwis_wt_,$kkkk);

			// #### ujemne czasy		
					if ($minut_pracujacych_w_dniu_startowym<0) $minut_pracujacych_w_dniu_startowym=0;
					
					//echo "<font color=blue>Ilosc min. w dniu startowym: ".$minut_pracujacych_w_dniu_startowym."</font> | ";	
					$ilosc_minut_pracujacych_w_dniu_do_dodania = $minut_pracujacych_w_dniu_startowym;
					
				} else {
					//echo "Dostepnosc do komorki do: <font color=purple><b>".$sama_godzina_graniczna_stop."</b> | </font>";
					//echo "><b>$sama_godzina1, $sama_godzina_graniczna_stop<";
					$minut_pracujacych_w_dniu_startowym = ile_minut_pomiedzy_godzinami($sama_godzina1, $sama_godzina_graniczna_stop,$_serwis_wt_,$kkkk);
					
			// #### ujemne czasy					
					if ($minut_pracujacych_w_dniu_startowym<0) $minut_pracujacych_w_dniu_startowym=0;
					//echo "<font color=blue>Ilosc min. w dniu startowym: ".$minut_pracujacych_w_dniu_startowym."</font> | ";	
					$ilosc_minut_pracujacych_w_dniu_do_dodania = $minut_pracujacych_w_dniu_startowym;
				}
				
			} else {
				
				$sama_godzina_graniczna_stop = godzina_stop1s($temp_data1_str_normal,$_wt_,$_serwis_wt_);
				if ($sama_godzina1short>$sama_godzina_graniczna_stop) {
					//echo "Krok rozpoczęty po godzinach pracy UP: <font color=purple><b>".$sama_godzina_graniczna_stop."</b> | </font>";
					$minut_pracujacych_w_dniu_startowym = 0;
					$ilosc_minut_pracujacych_w_dniu_do_dodania = $minut_pracujacych_w_dniu_startowym;
				} else {
					//echo "Dostepnosc do komorki do: <font color=purple><b>".$sama_godzina_graniczna_stop."</b> | </font>";
					$minut_pracujacych_w_dniu_startowym = ile_minut_pomiedzy_godzinami($sama_godzina1, $sama_godzina_graniczna_stop,$_serwis_wt_,$kkkk);

				// #### ujemne czasy				
					if ($minut_pracujacych_w_dniu_startowym<0) $minut_pracujacych_w_dniu_startowym=0;
					//echo "<font color=blue>Ilosc min. w dniu startowym: ".$minut_pracujacych_w_dniu_startowym."</font> | ";
					$ilosc_minut_pracujacych_w_dniu_do_dodania = $minut_pracujacych_w_dniu_startowym;
				}
			}

			}
			
			// jeżeli badany dzień jest równy dniowi końcowemu wtedy wylicz ile upłyneło minut od rozpoczęcia pracy tego dnia do godziny podanej
			if ($kkkk != 0) {		
				if ($temp_data1_str >= $temp_data2_str) {
					$sama_godzina_graniczna_stop = godzina_start1s($sama_data2,$_wt_,$_serwis_wt_);
					
					//echo "-$sama_godzina_graniczna_stop,$sama_godzina2-";
					// jeżeli godzina końcowa jest późniejsza niż godzina zakończenia pracy komórki
					if ((strtotime($sama_godzina2short)) > strtotime($sama_godzina_graniczna_stop)) {
						//$sama_godzina2 = godzina_stop1($temp_data1_str_normal,$_wt_);
					}
					//$sama_godzina_graniczna_start = godzina_start1s($sama_data2,$_wt_);
					//echo "$sama_godzina_graniczna_start";
					if ((strtotime($sama_godzina2short)) > strtotime($sama_godzina_graniczna_stop)) {
					
					}
					
					//echo "+$sama_godzina_graniczna_stop+ $sama_godzina2";
					
					if ((strtotime($sama_godzina_graniczna_stop))>(strtotime($sama_godzina2short))) { 
						$minut_pracujacych_w_dniu_koncowym = 0;
						//	echo "<font color=blue>Ilosc min. w dniu końcowym: ".$minut_pracujacych_w_dniu_koncowym."</font> | ";
						$minut_w_pelnych_dniach_pracujacych -= $ilosc_minut_pracujacych_w_dniu_do_dodania;
						$ilosc_minut_pracujacych_w_dniu_do_dodania = $minut_pracujacych_w_dniu_koncowym;
					} 
					
					if ((strtotime($sama_godzina_graniczna_stop))<(strtotime($sama_godzina2short))) { 
						//else {
						//	echo "Dostepnosc do komorki od: <font color=purple><b>".$sama_godzina_graniczna_stop."</b> | </font>";
						$minut_pracujacych_w_dniu_koncowym = ile_minut_pomiedzy_godzinami($sama_godzina_graniczna_stop,$sama_godzina2,$_serwis_wt_,$kkkk);
						//	echo "<font color=blue>Ilosc min. w dniu końcowym: ".$minut_pracujacych_w_dniu_koncowym."</font> | ";
						$minut_w_pelnych_dniach_pracujacych -= $ilosc_minut_pracujacych_w_dniu_do_dodania;
						$ilosc_minut_pracujacych_w_dniu_do_dodania = $minut_pracujacych_w_dniu_koncowym;
					}
				}
			}

			//	echo "ilosc minut w danym dniu: <b>".$ilosc_minut_pracujacych_w_dniu_do_dodania."</b>";	
			//	echo " | suma z pełnych dni: ".$minut_w_pelnych_dniach_pracujacych."";
		
		} else {
			// echo "<b><font color=red>NIEPRACUJACY</font></b> | <b><font color=red>".$temp_data1_str_normal."</font></b>";
		}
		
		if ($temp_data1_str >= $temp_data2_str) {
			//if (($czas_nowy!=$czas) && ($czas_nowy==1)) { $czas = $czas_nowy; $temp_data1_str-=3600; $ilosc_minut_pracujacych_w_dniu_do_dodania-=60; }
			//if (($czas_nowy!=$czas) && ($czas_nowy==0)) { $czas = $czas_nowy; $temp_data1_str+=3600; $ilosc_minut_pracujacych_w_dniu_do_dodania+=60; }
			break;
		}	
		
		//	if (czy_pracuje($temp_data1_str_normal,$_wt_)) { echo "PRACUJACY | "; } else { echo "NIEPRACUJACY | "; }	
			
		$kkkk++;
	} While ($temp_data1_str != $temp_data2_str);

	// faktyczna ilość minut pracujacych między datami = suma minut pozostałych w dniu startowym + ilość minut w pełnych dniach pracujących + ilość minut jakie upłynęło w dniu końcowym
	$minut_razem = $minut_pracujacych_w_dniu_startowym + $minut_w_pelnych_dniach_pracujacych + $minut_pracujacych_w_dniu_koncowym;
	//if ($minut_razem<0) $minut_razem=0;
	
	return ceil($minut_razem);
}

function ModifyWorkingTime($_inWeek, $_inNGP, $_inNDP, $_inDN, $_in_gSTART, $_in_gSTOP, $_in_przesun) {

//echo "<hr /><hr />$_inDN<hr /><hr />";
//echo "<hr /><hr />$_in_przesun<hr /><hr />";
//echo "<hr /><hr />$_inNDP ".jaki_dzien_upper($_inNDP)."<hr /><hr />";

$dni_temp = explode(";",$_inWeek);

$_pn = explode("@",$dni_temp[0]);
$_wt = explode("@",$dni_temp[1]);
$_sr = explode("@",$dni_temp[2]);
$_cz = explode("@",$dni_temp[3]);
$_pt = explode("@",$dni_temp[4]);
$_so = explode("@",$dni_temp[5]);
$_ni = explode("@",$dni_temp[6]);

$dzien_tempPN = explode("-",$_pn[1]);
$dzien_tempWT = explode("-",$_wt[1]);
$dzien_tempSR = explode("-",$_sr[1]);
$dzien_tempCZ = explode("-",$_cz[1]);
$dzien_tempPT = explode("-",$_pt[1]);
$dzien_tempSO = explode("-",$_so[1]);
$dzien_tempNI = explode("-",$_ni[1]);

$godz_zmien = $_inNGP;

/*
if ($_in_przesun == "NEXT_DAY") {
	switch ($_inDN) {
		case 'PN' : $_inDN = 'WT'; break;
		case 'WT' : $_inDN = 'SR'; break;
		case 'SR' : $_inDN = 'CZ'; break;
		case 'CZ' : $_inDN = 'PT'; break;
		case 'PT' : $_inDN = 'SO'; break;
		case 'SO' : $_inDN = 'NI'; break;
		case 'NI' : $_inDN = 'PN'; break;
	}
}
*/
	
if (!czy_pracuje($_inNDP, $_inWeek)) { $W = $_inWeek; return $W; }

if ($_in_przesun == "") { $W = $_inWeek; return $W; }

$W = '';
//echo "<hr /><hr /><b>***************</b><hr /><hr />";
if ($_in_przesun == "STOP") {

	if ($_inDN == 'PN') { 
		$dzien_nowy[0] = $dzien_tempPN[0]; $dzien_nowy[1] = $godz_zmien;
		
		//$W .= $_pn[0]."@".$dzien_tempPN[0]."-".$dzien_tempPN[1].";";	// PN
		$W .= $_pn[0]."@".$dzien_nowy[0]."-".$dzien_nowy[1].";";		// PN START NOWA
		$W .= $_wt[0]."@".$dzien_tempWT[0]."-".$dzien_tempWT[1].";";	// WT
		$W .= $_sr[0]."@".$dzien_tempSR[0]."-".$dzien_tempSR[1].";";	// SR
		$W .= $_cz[0]."@".$dzien_tempCZ[0]."-".$dzien_tempCZ[1].";";	// CZ
		$W .= $_pt[0]."@".$dzien_tempPT[0]."-".$dzien_tempPT[1].";";	// PT
		$W .= $_so[0]."@".$dzien_tempSO[0]."-".$dzien_tempSO[1].";";	// SO
		$W .= $_ni[0]."@".$dzien_tempNI[0]."-".$dzien_tempNI[1].";";	// NI
	}

	if ($_inDN == 'WT') { 
		$dzien_nowy[0] = $dzien_tempWT[0]; $dzien_nowy[1] = $godz_zmien;
		
		$W .= $_pn[0]."@".$dzien_tempPN[0]."-".$dzien_tempPN[1].";";	// PN
		// $W .= $_wt[0]."@".$dzien_tempWT[0]."-".$dzien_tempWT[1].";";	// WT
		$W .= $_wt[0]."@".$dzien_nowy[0]."-".$dzien_nowy[1].";";		// WT START NOWA
		$W .= $_sr[0]."@".$dzien_tempSR[0]."-".$dzien_tempSR[1].";";	// SR
		$W .= $_cz[0]."@".$dzien_tempCZ[0]."-".$dzien_tempCZ[1].";";	// CZ
		$W .= $_pt[0]."@".$dzien_tempPT[0]."-".$dzien_tempPT[1].";";	// PT
		$W .= $_so[0]."@".$dzien_tempSO[0]."-".$dzien_tempSO[1].";";	// SO
		$W .= $_ni[0]."@".$dzien_tempNI[0]."-".$dzien_tempNI[1].";";	// NI
	}
	
	if ($_inDN == 'SR') { 
		$dzien_nowy[0] = $dzien_tempSR[0]; $dzien_nowy[1] = $godz_zmien;
		
		$W .= $_pn[0]."@".$dzien_tempPN[0]."-".$dzien_tempPN[1].";";	// PN
		$W .= $_wt[0]."@".$dzien_tempWT[0]."-".$dzien_tempWT[1].";";	// WT		
		//$W .= $_sr[0]."@".$dzien_tempSR[0]."-".$dzien_tempSR[1].";";	// SR
		$W .= $_sr[0]."@".$dzien_nowy[0]."-".$dzien_nowy[1].";";		// SR START NOWA	
		$W .= $_cz[0]."@".$dzien_tempCZ[0]."-".$dzien_tempCZ[1].";";	// CZ
		$W .= $_pt[0]."@".$dzien_tempPT[0]."-".$dzien_tempPT[1].";";	// PT
		$W .= $_so[0]."@".$dzien_tempSO[0]."-".$dzien_tempSO[1].";";	// SO
		$W .= $_ni[0]."@".$dzien_tempNI[0]."-".$dzien_tempNI[1].";";	// NI
	}
	
	if ($_inDN == 'CZ') { 
		$dzien_nowy[0] = $dzien_tempCZ[0]; $dzien_nowy[1] = $godz_zmien;
		
		$W .= $_pn[0]."@".$dzien_tempPN[0]."-".$dzien_tempPN[1].";";	// PN
		$W .= $_wt[0]."@".$dzien_tempWT[0]."-".$dzien_tempWT[1].";";	// WT
		$W .= $_sr[0]."@".$dzien_tempSR[0]."-".$dzien_tempSR[1].";";	// SR
		//$W .= $_cz[0]."@".$dzien_tempCZ[0]."-".$dzien_tempCZ[1].";";	// CZ
		$W .= $_cz[0]."@".$dzien_nowy[0]."-".$dzien_nowy[1].";";		// CZ START NOWA
		$W .= $_pt[0]."@".$dzien_tempPT[0]."-".$dzien_tempPT[1].";";	// PT
		$W .= $_so[0]."@".$dzien_tempSO[0]."-".$dzien_tempSO[1].";";	// SO
		$W .= $_ni[0]."@".$dzien_tempNI[0]."-".$dzien_tempNI[1].";";	// NI
	}
	
	if ($_inDN == 'PT') { 
		$dzien_nowy[0] = $dzien_tempPT[0]; $dzien_nowy[1] = $godz_zmien;
		
		$W .= $_pn[0]."@".$dzien_tempPN[0]."-".$dzien_tempPN[1].";";	// PN
		$W .= $_wt[0]."@".$dzien_tempWT[0]."-".$dzien_tempWT[1].";";	// WT
		$W .= $_sr[0]."@".$dzien_tempSR[0]."-".$dzien_tempSR[1].";";	// SR
		$W .= $_cz[0]."@".$dzien_tempCZ[0]."-".$dzien_tempCZ[1].";";	// CZ
		//$W .= $_pt[0]."@".$dzien_tempPT[0]."-".$dzien_tempPT[1].";";	// PT
		$W .= $_pt[0]."@".$dzien_nowy[0]."-".$dzien_nowy[1].";";		// PT START NOWA
		$W .= $_so[0]."@".$dzien_tempSO[0]."-".$dzien_tempSO[1].";";	// SO
		$W .= $_ni[0]."@".$dzien_tempNI[0]."-".$dzien_tempNI[1].";";	// NI

	}
	
	if ($_inDN == 'SO') { 
		$dzien_nowy[0] = $dzien_tempSO[0]; $dzien_nowy[1] = $godz_zmien;
		
		$W .= $_pn[0]."@".$dzien_tempPN[0]."-".$dzien_tempPN[1].";";	// PN
		$W .= $_wt[0]."@".$dzien_tempWT[0]."-".$dzien_tempWT[1].";";	// WT
		$W .= $_sr[0]."@".$dzien_tempSR[0]."-".$dzien_tempSR[1].";";	// SR
		$W .= $_cz[0]."@".$dzien_tempCZ[0]."-".$dzien_tempCZ[1].";";	// CZ
		$W .= $_pt[0]."@".$dzien_tempPT[0]."-".$dzien_tempPT[1].";";	// PT
		//$W .= $_so[0]."@".$dzien_tempSO[0]."-".$dzien_tempSO[1].";";	// SO
		$W .= $_so[0]."@".$dzien_nowy[0]."-".$dzien_nowy[1].";";		// SO START NOWA
		$W .= $_ni[0]."@".$dzien_tempNI[0]."-".$dzien_tempNI[1].";";	// NI	
	}
	
	if ($_inDN == 'NI') { 
		$dzien_nowy[0] = $dzien_tempNI[0]; $dzien_nowy[1] = $godz_zmien;
		
		$W .= $_pn[0]."@".$dzien_tempPN[0]."-".$dzien_tempPN[1].";";	// PN
		$W .= $_wt[0]."@".$dzien_tempWT[0]."-".$dzien_tempWT[1].";";	// WT
		$W .= $_sr[0]."@".$dzien_tempSR[0]."-".$dzien_tempSR[1].";";	// SR
		$W .= $_cz[0]."@".$dzien_tempCZ[0]."-".$dzien_tempCZ[1].";";	// CZ
		$W .= $_pt[0]."@".$dzien_tempPT[0]."-".$dzien_tempPT[1].";";	// PT
		$W .= $_so[0]."@".$dzien_tempSO[0]."-".$dzien_tempSO[1].";";	// SO
		//$W .= $_ni[0]."@".$dzien_tempNI[0]."-".$dzien_tempNI[1].";";	// NI
		$W .= $_ni[0]."@".$dzien_nowy[0]."-".$dzien_nowy[1].";";		// NI START NOWA
	}
}

if (($_in_przesun == "START") || ($_in_przesun == "NEXT_DAY")) {

	if ($_inDN == 'PN') { 
		$dzien_nowy[0] = $godz_zmien; $dzien_nowy[1] = $dzien_tempPN[1];	
		
		//$W .= $_pn[0]."@".$dzien_tempPN[0]."-".$dzien_tempPN[1].";";	// PN
		$W .= $_pn[0]."@".$dzien_nowy[0]."-".$dzien_nowy[1].";";		// PN START NOWA
		$W .= $_wt[0]."@".$dzien_tempWT[0]."-".$dzien_tempWT[1].";";	// WT
		$W .= $_sr[0]."@".$dzien_tempSR[0]."-".$dzien_tempSR[1].";";	// SR
		$W .= $_cz[0]."@".$dzien_tempCZ[0]."-".$dzien_tempCZ[1].";";	// CZ
		$W .= $_pt[0]."@".$dzien_tempPT[0]."-".$dzien_tempPT[1].";";	// PT
		$W .= $_so[0]."@".$dzien_tempSO[0]."-".$dzien_tempSO[1].";";	// SO
		$W .= $_ni[0]."@".$dzien_tempNI[0]."-".$dzien_tempNI[1].";";	// NI
	}

	if ($_inDN == 'WT') { 
		$dzien_nowy[0] = $godz_zmien; $dzien_nowy[1] = $dzien_tempWT[1];	
		
		$W .= $_pn[0]."@".$dzien_tempPN[0]."-".$dzien_tempPN[1].";";	// PN
		// $W .= $_wt[0]."@".$dzien_tempWT[0]."-".$dzien_tempWT[1].";";	// WT
		$W .= $_wt[0]."@".$dzien_nowy[0]."-".$dzien_nowy[1].";";		// WT START NOWA
		$W .= $_sr[0]."@".$dzien_tempSR[0]."-".$dzien_tempSR[1].";";	// SR
		$W .= $_cz[0]."@".$dzien_tempCZ[0]."-".$dzien_tempCZ[1].";";	// CZ
		$W .= $_pt[0]."@".$dzien_tempPT[0]."-".$dzien_tempPT[1].";";	// PT
		$W .= $_so[0]."@".$dzien_tempSO[0]."-".$dzien_tempSO[1].";";	// SO
		$W .= $_ni[0]."@".$dzien_tempNI[0]."-".$dzien_tempNI[1].";";	// NI
	}
	
	if ($_inDN == 'SR') { 
		$dzien_nowy[0] = $godz_zmien; $dzien_nowy[1] = $dzien_tempSR[1];	
		
		$W .= $_pn[0]."@".$dzien_tempPN[0]."-".$dzien_tempPN[1].";";	// PN
		$W .= $_wt[0]."@".$dzien_tempWT[0]."-".$dzien_tempWT[1].";";	// WT	
		//$W .= $_sr[0]."@".$dzien_tempSR[0]."-".$dzien_tempSR[1].";";	// SR
		$W .= $_sr[0]."@".$dzien_nowy[0]."-".$dzien_nowy[1].";";		// SR START NOWA
		$W .= $_cz[0]."@".$dzien_tempCZ[0]."-".$dzien_tempCZ[1].";";	// CZ
		$W .= $_pt[0]."@".$dzien_tempPT[0]."-".$dzien_tempPT[1].";";	// PT
		$W .= $_so[0]."@".$dzien_tempSO[0]."-".$dzien_tempSO[1].";";	// SO
		$W .= $_ni[0]."@".$dzien_tempNI[0]."-".$dzien_tempNI[1].";";	// NI
	}
	
	if ($_inDN == 'CZ') { 
		$dzien_nowy[0] = $godz_zmien; $dzien_nowy[1] = $dzien_tempCZ[1];	
		
		$W .= $_pn[0]."@".$dzien_tempPN[0]."-".$dzien_tempPN[1].";";	// PN
		$W .= $_wt[0]."@".$dzien_tempWT[0]."-".$dzien_tempWT[1].";";	// WT
		$W .= $_sr[0]."@".$dzien_tempSR[0]."-".$dzien_tempSR[1].";";	// SR
		//$W .= $_cz[0]."@".$dzien_tempCZ[0]."-".$dzien_tempCZ[1].";";	// CZ
		$W .= $_cz[0]."@".$dzien_nowy[0]."-".$dzien_nowy[1].";";		// CZ START NOWA
		$W .= $_pt[0]."@".$dzien_tempPT[0]."-".$dzien_tempPT[1].";";	// PT
		$W .= $_so[0]."@".$dzien_tempSO[0]."-".$dzien_tempSO[1].";";	// SO
		$W .= $_ni[0]."@".$dzien_tempNI[0]."-".$dzien_tempNI[1].";";	// NI
	}
	
	if ($_inDN == 'PT') { 
		$dzien_nowy[0] = $godz_zmien; $dzien_nowy[1] = $dzien_tempPT[1];	
		
		$W .= $_pn[0]."@".$dzien_tempPN[0]."-".$dzien_tempPN[1].";";	// PN
		$W .= $_wt[0]."@".$dzien_tempWT[0]."-".$dzien_tempWT[1].";";	// WT
		$W .= $_sr[0]."@".$dzien_tempSR[0]."-".$dzien_tempSR[1].";";	// SR
		$W .= $_cz[0]."@".$dzien_tempCZ[0]."-".$dzien_tempCZ[1].";";	// CZ
		//$W .= $_pt[0]."@".$dzien_tempPT[0]."-".$dzien_tempPT[1].";";	// PT
		$W .= $_pt[0]."@".$dzien_nowy[0]."-".$dzien_nowy[1].";";		// PT START NOWA
		$W .= $_so[0]."@".$dzien_tempSO[0]."-".$dzien_tempSO[1].";";	// SO
		$W .= $_ni[0]."@".$dzien_tempNI[0]."-".$dzien_tempNI[1].";";	// NI

	}
	
	if ($_inDN == 'SO') { 
		$dzien_nowy[0] = $godz_zmien; $dzien_nowy[1] = $dzien_tempSO[1];	
		
		$W .= $_pn[0]."@".$dzien_tempPN[0]."-".$dzien_tempPN[1].";";	// PN
		$W .= $_wt[0]."@".$dzien_tempWT[0]."-".$dzien_tempWT[1].";";	// WT
		$W .= $_sr[0]."@".$dzien_tempSR[0]."-".$dzien_tempSR[1].";";	// SR
		$W .= $_cz[0]."@".$dzien_tempCZ[0]."-".$dzien_tempCZ[1].";";	// CZ
		$W .= $_pt[0]."@".$dzien_tempPT[0]."-".$dzien_tempPT[1].";";	// PT
		//$W .= $_so[0]."@".$dzien_tempSO[0]."-".$dzien_tempSO[1].";";	// SO
		$W .= $_so[0]."@".$dzien_nowy[0]."-".$dzien_nowy[1].";";		// SO START NOWA
		$W .= $_ni[0]."@".$dzien_tempNI[0]."-".$dzien_tempNI[1].";";	// NI	
	}
	
	if ($_inDN == 'NI') { 
		$dzien_nowy[0] = $godz_zmien; $dzien_nowy[1] = $dzien_tempNI[1];	
		
		$W .= $_pn[0]."@".$dzien_tempPN[0]."-".$dzien_tempPN[1].";";	// PN
		$W .= $_wt[0]."@".$dzien_tempWT[0]."-".$dzien_tempWT[1].";";	// WT
		$W .= $_sr[0]."@".$dzien_tempSR[0]."-".$dzien_tempSR[1].";";	// SR
		$W .= $_cz[0]."@".$dzien_tempCZ[0]."-".$dzien_tempCZ[1].";";	// CZ
		$W .= $_pt[0]."@".$dzien_tempPT[0]."-".$dzien_tempPT[1].";";	// PT
		$W .= $_so[0]."@".$dzien_tempSO[0]."-".$dzien_tempSO[1].";";	// SO
		//$W .= $ni[0]."@".$dzien_tempNI[0]."-".$dzien_tempNI[1].";";	// NI
		$W .= $_ni[0]."@".$dzien_nowy[0]."-".$dzien_nowy[1].";";		// NI START NOWA
	}
}
//echo "<hr /><hr /><b>".$W."</b><hr /><hr />";
return $W;
}

//function to validate ip address format in php by Roshan Bhattarai(http://roshanbh.com.np)
function validateIpAddress($ip_addr)
{
  //first of all the format of the ip address is matched
  if(preg_match("/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/",$ip_addr))
  {
    //now all the intger values are separated
    $parts=explode(".",$ip_addr);
    //now we need to check each part can range from 0-255
    foreach($parts as $ip_parts)
    {
      if(intval($ip_parts)>255 || intval($ip_parts)<0)
      return false; //if number is not within range of 0-255
    }
    return true;
  }
  else
    return false; //if format of ip address doesn't matches
}

function z_jakiego_dnia($data, $wt) {
	$xxx=0;
	for ($u=1; $u<60; $u++) {
		if (SubstractWorkingDays($u,date("Y-m-d"))==$data) {
			if (czy_pracuje($data,$wt)) {
				$xxx++;
				return $xxx;
			 	break;
			} 
		} else {
			if (czy_pracuje(SubstractWorkingDays($u,date("Y-m-d")),$wt)) {
				$xxx++;
			} 
		}
	}
	return HD_RoznicaDat_c(date("Y-m-d"),$data);
	
	/*
	if (czy_pracuje($data,$wt)) {
		$c = HD_RoznicaDat_c(date("Y-m-d"),$data);
		
		echo "$c";
	} else {
		echo "NIE";
	}
	*/
}

function CalcDiff($currTime, $origTime)
{
    $showDiff = '';

    // Set times
    $currTime = intval($currTime);
    $origTime = intval($origTime);
    if ($currTime<$origTime) { $diff = $origTime-$currTime; }
    else { $diff = $currTime-$origTime; }

    // Start Years
    $yrs = floor($diff/31556926); // 31556926 secs/yr
    if ($yrs > 0)
    {
        $diff = $diff - ($yrs*31556926);
        $showDiff .= "{$yrs}";
        $showDiff .= $yrs>1 ? ' Lat ' : '';
        $showDiff .= $yrs<2 ? ' Rok ' : '';
    }
    unset($yrs); // End Years

    // Start Months
    $mos = floor($difference/2629743.83); // 2629743.83 secs/mo
    if ($mos > 0)
    {
        $diff = $diff - ($mos*2629743.83);
        $showDiff .= empty($showDiff) ? '' : ', ';
        $showDiff .= "{$mos}";
        $showDiff .= 1<$mos && $mos<5 ? ' Miesiące ' : '';
        $showDiff .= $mos>4 ? ' Miesięcy ' : '';
        $showDiff .= $mos<1 ? ' Miesiąc ' : '';
    }
    unset($mos); // End Months

    // Start Weeks
    $wks = floor($diff/604800); // 604800 secs/wk
    if ($wks > 0)
    {
        $diff = $diff - ($wks*604800);
        $showDiff .= empty($showDiff) ? '' : ', ';
        $showDiff .= "{$wks}";
        $showDiff .= 1<$wks && $wks<5 ? ' Tygodnie ' : '';
        $showDiff .= $wks>4 ? ' Tygodni ' : '';
        $showDiff .= $wks<2 ? ' Tydzien ' : '';
    }
    unset($wks); // End Weeks

    // Start Days
    $days = floor($diff/86400); // 86400 secs/day
    if ($days > 0)
    {
        $diff = $diff - ($days*86400);
        $showDiff .= empty($showDiff) ? '' : ', ';
        $showDiff .= "{$days}";
        $showDiff .= $days>1 ? ' Dni ' : '';
        $showDiff .= $days<2 ? ' Dzien ' : '';
    }
    unset($days); // End Days

    // Start Hours
    $hrs = floor($diff/3600); // 3600 secs/hr
    if ($hrs > 0) {
        $diff = $diff - ($hrs*3600);
        $showDiff .= empty($showDiff) ? '' : ', ';
        $showDiff .= "{$hrs}";
        $showDiff .= 1<$hrs && $hrs<5 ? ' Godziny ' : '';
        $showDiff .= $hrs<2 ? ' Godzina ' : '';
        $showDiff .= $hrs>4 ? ' Godzin ' : '';
    }
    unset($hrs); // End Hours

    // Start Minutes
    $mins = floor($diff/60); // 60 secs/min
    if ($mins > 0) {
        $diff = $diff - ($mins*60);
        $showDiff .= empty($showDiff) ? '' : ', ';
        $showDiff .= "{$mins}";
        $showDiff .= 1<$mins && $mins<5 ? ' Minuty ' : '';
        $showDiff .= $mins<2 ? ' Minuta' : '';
        $showDiff .= $mins>4 ? ' Minut' : '';
    }
    unset($mins); // End Minutes

    // Start Seconds
    if ($diff > 0)
    {
        $showDiff .= empty($showDiff) ? '' : ', ';
        $showDiff .= "{$diff}";
        $showDiff .= 1<$diff && $diff<5 ? ' Sekundy ' : '';
        $showDiff .= $diff>4 ? ' Sekund ' : '';
        $showDiff .= $diff<2 ? ' Sekunda ' : '';
               
    } // End Seconds

    unset($diff); // Free unused memory

    // Zwraca różnice
    if ($currTime<$origTime) { $showDiff = "- {$showDiff}"; }
    return $showDiff;
}

$conn = ConnectToDatabase();

?>