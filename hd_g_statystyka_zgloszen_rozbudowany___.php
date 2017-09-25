<?php 
include_once('cfg_helpdesk.php');
include_once('header.php');

echo "<body>";

if ($obszar=='Łódź') $obszar='Lodz';

$mozna_eksportowac = 1;
$wlacz_zakres_dzienny = 1; 

if ($submit) {

echo "START: [".date("H:i:s")."]";

	//echo $_REQUEST[r_rok]."<br />";
	//echo "Okres: ".$_REQUEST[rr]."<br />";
	if ($_REQUEST[rr]=='T') $okres = $_REQUEST[r_T_zakres];
	if ($_REQUEST[rr]=='M') $okres = $_REQUEST[r_M_zakres];
	if ($_REQUEST[rr]=='K') $okres = $_REQUEST[r_K_zakres];
	//echo "		".$okres;

	$okres1 = str_replace('@','-',$okres);
	$okres = str_replace('@',' - ',$okres);
	
	if ($_REQUEST[rr]=='D') $okres = $_REQUEST[d_od]." - ".$_REQUEST[d_do];
	
pageheader("Raport okresowy ze zgłoszeń (dla Poczty)",1,0);
infoheader("Okres raportowania: <b>".$okres."</b>");

echo "<table cellspacing=1 align=center>";
echo "<tr>";
	echo "<th colspan=2 style='background-color:#000080; color:white; font-style:Verdana; font-size:9pt;'>";
	echo "Zarządzanie Jakością Usług IT - ".$obszar;
	echo "</th>";
	echo "<th class=center style='background-color:#000080; color:white; font-style:Verdana; font-size:9pt;'>% wykonania</th>";
	echo "<th width=200 class=center style='background-color:#000080; color:white; font-style:Verdana; font-size:9pt;'>";
	echo "Okres pomiaru<br />$okres";
	echo "</th>";

	if ($mozna_eksportowac==1) {	
		echo "<th width=30 class=center style='background-color:#000080; color:white; font-style:Verdana; font-size:9pt;'>";
		echo "XLS";
		echo "</th>";
	}
	
echo "</tr>";

$pkt_1_value = 0;
$pkt_2_value = 0;
$pkt_3_value = 0;
$pkt_4_value = 0;
$pkt_5_value = 0;
$pkt_6_value = 0;
$pkt_7_value = 0;
$pkt_8_value = 0;


?>
<script>
var xx = (250);
var yy = (250);
document.write('<div id=TrwaLadowanie style="position:absolute; left:');
document.write(xx);
document.write('px; top:');
document.write(yy);
document.write('px; color:grey; width:350px; font-weight:bold; text-align:center; font-size:13px; border: 1px solid #FC9898; background-color:white;padding:15px">Trwa pobieranie danych z serwera...<input type=image class=border0 src=img/loader.gif></div>');
</script>
<?php 
ob_flush();	
flush();

$zakres_dat = explode(" - ",$okres);

$data_od = $zakres_dat[0];
$data_do = $zakres_dat[1];

	// tworzenie tabeli tycznasowej dla raportów
	$sql_f = "SELECT filia_leader FROM $dbname.serwis_filie WHERE filia_leader=$es_nr LIMIT 1";
	$result_f = mysql_query($sql_f, $conn_hd) or die($k_b);
	$countf = mysql_num_rows($result_f);
	$fa = mysql_fetch_array($result_f);
	$filia_leader_nr = $fa['filia_leader'];
	
	$czyistnieje1 = mysql_query("SELECT * FROM $dbname_hd.hd_temp_raport_rozbudowany_1 LIMIT 1", $conn_hd);
	$czyistnieje2 = mysql_query("SELECT * FROM $dbname_hd.hd_temp_raport_rozbudowany_2 LIMIT 1", $conn_hd);
	$czyistnieje3 = mysql_query("SELECT * FROM $dbname_hd.hd_temp_raport_rozbudowany_3 LIMIT 1", $conn_hd);
	$czyistnieje4 = mysql_query("SELECT * FROM $dbname_hd.hd_temp_raport_rozbudowany_4 LIMIT 1", $conn_hd);
	
	if ($czyistnieje1) {	
		if (($countf>0) || ($is_dyrektor)) { 
			$sql_report = "TRUNCATE TABLE $dbname_hd.hd_temp_raport_rozbudowany_1";	$result_report = mysql_query($sql_report, $conn_hd) or die($k_b);
		}
	} else { 
		if (($countf>0) || ($is_dyrektor)) { 
			$sql_report = "CREATE TABLE $dbname_hd.`hd_temp_raport_rozbudowany_1` (
			`pole1` varchar(5) collate utf8_polish_ci NOT NULL,
			`pole2` varchar(8) collate utf8_polish_ci NOT NULL,
			`pole3` varchar(8) collate utf8_polish_ci NOT NULL,
			`pole4` varchar(20) collate utf8_polish_ci NOT NULL,
			`pole5` varchar(100) collate utf8_polish_ci NOT NULL,
			`pole6` varchar(20) collate utf8_polish_ci NOT NULL,
			`pole7` varchar(1) collate utf8_polish_ci NOT NULL,
			`pole8` varchar(50) collate utf8_polish_ci NOT NULL,
			`pole9` varchar(30) collate utf8_polish_ci NOT NULL,
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
			`pole46` int(10) collate utf8_polish_ci NOT NULL DEFAULT '0') ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;";	
			
			$result_report = mysql_query($sql_report, $conn_hd) or die($k_b);	
		}
	}

	if ($czyistnieje2) {	
		if (($countf>0) || ($is_dyrektor)) { 
			$sql_report = "TRUNCATE TABLE $dbname_hd.hd_temp_raport_rozbudowany_2";	$result_report = mysql_query($sql_report, $conn_hd) or die($k_b);		
		}
	} else { 
		if (($countf>0) || ($is_dyrektor)) { 
			$sql_report = "CREATE TABLE $dbname_hd.`hd_temp_raport_rozbudowany_2` (
			`pole1` varchar(5) collate utf8_polish_ci NOT NULL,
			`pole2` varchar(8) collate utf8_polish_ci NOT NULL,
			`pole3` varchar(8) collate utf8_polish_ci NOT NULL,
			`pole4` varchar(20) collate utf8_polish_ci NOT NULL,
			`pole5` varchar(100) collate utf8_polish_ci NOT NULL,
			`pole6` varchar(20) collate utf8_polish_ci NOT NULL,
			`pole7` varchar(1) collate utf8_polish_ci NOT NULL,
			`pole8` varchar(50) collate utf8_polish_ci NOT NULL,
			`pole9` varchar(30) collate utf8_polish_ci NOT NULL,
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
			`pole46` int(10) collate utf8_polish_ci NOT NULL DEFAULT '0') ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;";	
			$result_report = mysql_query($sql_report, $conn_hd) or die($k_b);	
		}
	}

	if ($czyistnieje3) {	
		if (($countf>0) || ($is_dyrektor)) { 
			$sql_report = "TRUNCATE TABLE $dbname_hd.hd_temp_raport_rozbudowany_3";	$result_report = mysql_query($sql_report, $conn_hd) or die($k_b);		
		}
	} else { 
		if (($countf>0) || ($is_dyrektor)) { 
			$sql_report = "CREATE TABLE $dbname_hd.`hd_temp_raport_rozbudowany_3` (
			`pole1` varchar(5) collate utf8_polish_ci NOT NULL,
			`pole2` varchar(8) collate utf8_polish_ci NOT NULL,
			`pole3` varchar(8) collate utf8_polish_ci NOT NULL,
			`pole4` varchar(20) collate utf8_polish_ci NOT NULL,
			`pole5` varchar(100) collate utf8_polish_ci NOT NULL,
			`pole6` varchar(20) collate utf8_polish_ci NOT NULL,
			`pole7` varchar(1) collate utf8_polish_ci NOT NULL,
			`pole8` varchar(50) collate utf8_polish_ci NOT NULL,
			`pole9` varchar(30) collate utf8_polish_ci NOT NULL,
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
			`pole46` int(10) collate utf8_polish_ci NOT NULL DEFAULT '0') ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;";	
			$result_report = mysql_query($sql_report, $conn_hd) or die($k_b);	
		}
	}
	
	if ($czyistnieje4) {	
		if (($countf>0) || ($is_dyrektor)) { 

			$sql_report = "TRUNCATE TABLE $dbname_hd.hd_temp_raport_rozbudowany_4";	$result_report = mysql_query($sql_report, $conn_hd) or die($k_b);		
		}
	} else { 
		if (($countf>0) || ($is_dyrektor)) { 
			$sql_report = "CREATE TABLE $dbname_hd.`hd_temp_raport_rozbudowany_4` (
			`pole1` varchar(5) collate utf8_polish_ci NOT NULL,
			`pole2` varchar(8) collate utf8_polish_ci NOT NULL,
			`pole3` varchar(8) collate utf8_polish_ci NOT NULL,
			`pole4` varchar(20) collate utf8_polish_ci NOT NULL,
			`pole5` varchar(100) collate utf8_polish_ci NOT NULL,
			`pole6` varchar(20) collate utf8_polish_ci NOT NULL,
			`pole7` varchar(1) collate utf8_polish_ci NOT NULL,
			`pole8` varchar(50) collate utf8_polish_ci NOT NULL,
			`pole9` varchar(30) collate utf8_polish_ci NOT NULL,
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
			`pole46` int(10) collate utf8_polish_ci NOT NULL DEFAULT '0') ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;";	
			$result_report = mysql_query($sql_report, $conn_hd) or die($k_b);	
		}
	}

$i = 0;

list($pkt_1_value)=mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_data BETWEEN '$data_od' and '$data_do') and (zgl_widoczne=1) and (zgl_kategoria<>5) and ((zgl_poledodatkowe2='') or ((zgl_poledodatkowe2<>'') and (zgl_status<>1)))", $conn_hd));

$kopiuj_1 = mysql_query("INSERT INTO $dbname_hd.hd_temp_raport_rozbudowany_1 (pole1,pole2,pole3,pole4,pole5,pole6,pole7,pole8,pole9,pole10,pole11,pole21,pole38,pole39,pole40,pole41,pole42,pole43,pole44,pole45,pole46) SELECT zgl_typ_uslugi, zgl_nr,zgl_poczta_nr, CONCAT(zgl_data,' ',SUBSTRING_INDEX(zgl_godzina,':',2)) as MomentZgloszenia, zgl_komorka,zgl_przypisanie_jednostki, zgl_kategoria_komorki, zgl_osoba, hd_podkategoria_opis,zgl_tresc,hd_kategoria_opis,hd_status_opis,zgl_E1C,zgl_E1P,zgl_E2C,zgl_E2P,zgl_E3C,zgl_E3P,zgl_naprawa_id,zgl_rozpoczecie_min, zgl_zakonczenie_min FROM $dbname_hd.hd_zgloszenie, $dbname_hd.hd_kategoria, $dbname_hd.hd_podkategoria,$dbname_hd.hd_status WHERE (zgl_kategoria=hd_kategoria_nr) and (zgl_podkategoria=hd_podkategoria_nr) and (zgl_status=hd_status_nr) and (zgl_data BETWEEN '$data_od' and '$data_do') and (zgl_widoczne=1) and (zgl_kategoria<>5) and ((zgl_poledodatkowe2='') or ((zgl_poledodatkowe2<>'') and (zgl_status<>1)))",$conn_hd);

ob_flush();
flush();

tbl_tr_highlight($i++);
	echo "<td class=center width=30 height=30>$i</td>";
	echo "<td>Liczba incydentów zarejestrowanych w okresie rozliczeniowym [szt.]";	
	echo "</td>";
	
	echo "<td class=center>";
	echo "&nbsp;<span id=percent1 style='background-color:black; color:yellow'></span>";
	echo "</td>";
	
	echo "<td class=center>".$pkt_1_value."</td>";
	
	if ($mozna_eksportowac==1) {
		echo "<td class=center>";
		echo "<form name=pkt1 style='display:inline' action=do_xls_htmlexcel_hd_g_raport_szcz_nowy.php METHOD=POST target=_blank>";
		echo "<input type=hidden name=do_punktu value='1'>";
		echo "<input type=hidden name=okres value='$data_od-$data_do'>";
		echo "<input type=hidden name=obszar value='$obszar'>";		
		echo "<input type=hidden name=opis_raportu value='liczba_zgl_ktore_wplynely_w_biezacym_okresie'>";
		echo "<a id=ex1 style='display:none' title=' Wyeksportuj zgłoszenia do pliku XLS '><input class=imgoption type=image src=img/xls_icon.gif onclick=\"document.pkt1.submit();\"></a>";
		echo "</form>";
		echo "</td>";
	}

echo "</tr>";
ob_flush();
flush();

// wypełnienie pozostałych pól w tabeli tymczasowej
// pole44 : zgl_naprawa_id

$wybierz_z_1 = "SELECT pole2,pole44,pole21 FROM $dbname_hd.hd_temp_raport_rozbudowany_1";
$result_z_1 = mysql_query($wybierz_z_1, $conn_hd) or die($k_b);
$count_rows_z_1 = mysql_num_rows($result_z_1);
$licznik = 0;
if ($count_rows_z_1>0) {

	while ($newArray = mysql_fetch_array($result_z_1)) {
	
		$zgl_nr 		= $newArray['pole2'];
		$zgl_status 	= $newArray['pole21'];
		$naprawa_id 	= $newArray['pole44'];

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
		
		// ################################
		// ##### POLE 21 , 22,23      #####
		// ################################		
		
		// sprawdzenie czy nie jest "rozwiązany"
		$res1 = mysql_query("SELECT zgl_czy_rozwiazany_problem,zgl_razem_km,zgl_kategoria,zgl_status,zgl_E1C,zgl_E2C,zgl_E3C,zgl_E1P,zgl_E2P,zgl_E3P FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id='$zgl_nr') and (zgl_widoczne=1) LIMIT 1") or die($k_b);
		list($_czy_rozw,$km,$zgl_kat,$zs,$_etap1c,$_etap2c,$_etap3c,$_etap1p,$_etap2p,$_etap3p)=mysql_fetch_array($res1);
		
		if ($_czy_rozw=='1') {
			$_pole21 = 'rozwiązany';
			$res1 = mysql_query("SELECT zgl_szcz_data_systemowa_rejestracji_kroku, zgl_szcz_czas_rozpoczecia_kroku FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$zgl_nr') and (zgl_szcz_widoczne=1) and (zgl_szcz_czy_rozwiazany_problem=1) LIMIT 1") or die($k_b);
			list($_pole22,$_pole23)=mysql_fetch_array($res1);
			$_pole22 = substr($_pole22,0,16);
			$_pole23 = substr($_pole23,0,16);
			if ($zs=='9') $_pole21 = 'zamknięte';			
		} else {
			// statusy dla Poczty
			if ($zs=='1') $_pole21 = 'nowe';
			if ($zs=='2') $_pole21 = 'nowe';
			if ($zs=='3') $_pole21 = 'w trakcie realizacji';
			if ($zs=='3B') $_pole21 = 'w trakcie realizacji';
			if ($zs=='3A') $_pole21 = 'w serwisie zewnętrznym';
			if ($zs=='4') $_pole21 = 'oczekiwanie na odpowiedź klienta';
			if ($zs=='5') $_pole21 = 'w trakcie realizacji';
			if ($zs=='6') $_pole21 = 'w trakcie realizacji';
			if ($zs=='7') $_pole21 = 'w trakcie realizacji';
			if ($zs=='9') $_pole21 = 'zamknięte';		
		}
		

	if (($zgl_kat=='2') || ($zgl_kat=='6') || ($zgl_kat=='3') || ($zgl_kat=='4')) {
		
		// #########################
		// ##### POLE 12 i 13 ######
		// #########################
		
		
		// jeżeli powiązana naprawa
		if ($naprawa_id>0) {
			$res1 = mysql_query("SELECT naprawa_nazwa, naprawa_model, naprawa_sn, naprawa_ni FROM $dbname.serwis_naprawa WHERE naprawa_id='$naprawa_id' LIMIT 1") or die($k_b);
			list($mnazwa,$mmodel,$msn,$mni)=mysql_fetch_array($res1);
			
			$_pole12 = $msn." / ".$mni;
			$_pole13 = $mnazwa." ".$mmodel;		
			//echo "<br />|".$_pole13;
		} 
		
		if ($_pole13=='') {
			// sprawdź czy dla zgłoszenia nie ma powiązanej wymiany podzespołów
			$res1 = mysql_query("SELECT wp_sprzet_opis, pozycja_status,wp_sprzet_sn,wp_sprzet_ni FROM $dbname_hd.hd_zgl_wymiany_podzespolow, $dbname.serwis_faktura_szcz WHERE (wp_sprzedaz_fakt_szcz_id=pozycja_id) and (wp_zgl_id=$zgl_nr) LIMIT 1") or die($k_b);
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
		
		// #########################
		// ##### POLE 15      ######
		// #########################
		
		// ustalenie daty rozpoczęcia kroku o statusie >= 3
		$res1 = mysql_query("SELECT zgl_szcz_czas_rozpoczecia_kroku FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$zgl_nr') and (zgl_szcz_status<>'1') and (zgl_szcz_status<>'2') and (zgl_szcz_widoczne=1) ORDER BY zgl_szcz_nr_kroku ASC LIMIT 1") or die($k_b);
		list($_pole15)=mysql_fetch_array($res1);
		$_pole15 = substr($_pole15,0,16);	
		
		// ######################################
		// ##### POLE 16,17,18,19,24,26,27  #####
		// ######################################
		
		// przewidywany czas reakcji (wg umowy) i przewidywany czas zakończenia | dla Awaii i Awarii krytycznych
		$res1 = mysql_query("SELECT zgl_rozpoczecie_min, zgl_zakonczenie_min,zgl_E1C,zgl_E2C FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id='$zgl_nr') and ((zgl_kategoria='2') or (zgl_kategoria='6')) and (zgl_widoczne=1) LIMIT 1") or die($k_b);
		list($_pole16,$_pole24,$_pole17,$_etap2C)=mysql_fetch_array($res1);
		
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
	}
	
		// ################################
		// ##### POLE 25              #####
		// ################################	
		
		// czas rozwiązania incydentu
		//$res1 = mysql_query("SELECT zgl_E1C,zgl_E2C,zgl_E3C,zgl_E1P,zgl_E2P,zgl_E3P FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id='$zgl_nr') and (zgl_widoczne=1) LIMIT 1") or die($k_b);
		//list($_etap1c,$_etap2c,$_etap3c,$_etap1p,$_etap2p,$_etap3p)=mysql_fetch_array($res1);
		
		$_pole25 = $_etap1c + $_etap2c;

		// ################################
		// ##### POLE 28              #####
		// ################################	
		
	if (($zgl_kat=='2') || ($zgl_kat=='6') || ($zgl_kat=='3') || ($zgl_kat=='4')) {
		
		$_pole36 = 'zdalnie';
		
		// zebranie wszystkich kroków w jedną zmienną
		$res1 = mysql_query("SELECT zgl_szcz_czas_rozpoczecia_kroku,zgl_szcz_status, zgl_szcz_wykonane_czynnosci,zgl_szcz_byl_wyjazd FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$zgl_nr') and (zgl_szcz_widoczne=1) ORDER BY zgl_szcz_nr_kroku ASC") or die($k_b);
		while (list($_czas,$_status,$_wc,$_byl_wyjazd)=mysql_fetch_array($res1)) {
		
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
		
			$_pole28 .= "".substr($_czas,0,16).",".$_status1.",".br2point($_wc)."\n\r";
			
			if ($_byl_wyjazd=='1') $_pole36 = 'wyjazd';
			
		}	
		
		// ################################
		// ##### POLE 36              #####
		// ################################	
		
		if (($zgl_kat=='4')) $_pole36 = 'zdalnie';
		if ($zgl_kat=='1') $_pole36 = 'pierwszy kontakt';

	} else $_pole36 = 'pierwszy kontakt';
	
	
		// ################################
		// ##### POLE 29              #####
		// ################################			
		$res1 = mysql_query("SELECT zgl_szcz_czas_rozpoczecia_kroku FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$zgl_nr') and (zgl_szcz_widoczne=1) and (zgl_szcz_status=9) LIMIT 1") or die($k_b);
		list($_pole29)=mysql_fetch_array($res1);
		$_pole29 = substr($_pole29,0,16);
		
		// ################################
		// ##### POLE 30              #####
		// ################################		
		$_pole30 = $_etap3c;
		
		$_pole31 = $_etap1c + $_etap2c + $_etap3c;
		$_pole32 = $_etap1p;
		$_pole33 = $_etap2p;
		$_pole34 = $_etap3p;
		
		// uaktualnij tabelę tymczasową
		$sql_update_1 = "UPDATE $dbname_hd.hd_temp_raport_rozbudowany_1 SET pole12='".$_pole12."', pole13='".$_pole13."', pole14='".$_pole14."', pole15='".$_pole15."', pole16='".$_pole16."', pole17='".$_pole17."', pole18='".$_pole18."', pole19='".$_pole19."', pole20='".$_pole20."', pole21='".$_pole21."', pole22='".$_pole22."', pole23='".$_pole23."', pole24='".$_pole24."', pole25='".$_pole25."', pole26='".$_pole26."', pole27='".$_pole27."', pole28='".$_pole28."', pole29='".$_pole29."', pole30='".$_pole30."', pole31='".$_pole31."', pole32='".$_pole32."', pole33='".$_pole33."', pole34='".$_pole34."', pole35='".$_pole35."', pole36='".$_pole36."' WHERE pole2=$zgl_nr LIMIT 1";
		
//		echo "$sql_update_1<br />";
		$update_1 = mysql_query($sql_update_1, $conn_hd) or die($k_b);

		$percent_value_1 = ceil($licznik++ * 100 / $count_rows_z_1);
		
		?><script>document.getElementById('percent1').innerHTML=' <?php echo $percent_value_1; ?>%&nbsp;';</script><?php
		ob_flush();
		flush();

	}
	?><script>document.getElementById('percent1').innerHTML=' 100%&nbsp;'; document.getElementById('ex1').style.display='';</script><?php
	ob_flush();
	flush();

}

list($pkt_2_value)=mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_czy_rozwiazany_problem=1) and (zgl_czy_rozwiazany_problem_data BETWEEN '$data_od' and '$data_do') and (zgl_widoczne=1) and (zgl_kategoria<>5) and ((zgl_poledodatkowe2='') or ((zgl_poledodatkowe2<>'') and (zgl_status<>1)))", $conn_hd));

$kopiuj_2 = mysql_query("INSERT INTO $dbname_hd.hd_temp_raport_rozbudowany_2 (pole1,pole2,pole3,pole4,pole5,pole6,pole7,pole8,pole9,pole10,pole11,pole21,pole38,pole39,pole40,pole41,pole42,pole43,pole44,pole45,pole46) SELECT zgl_typ_uslugi, zgl_nr,zgl_poczta_nr, CONCAT(zgl_data,' ',SUBSTRING_INDEX(zgl_godzina,':',2)) as MomentZgloszenia, zgl_komorka,zgl_przypisanie_jednostki, zgl_kategoria_komorki, zgl_osoba, hd_podkategoria_opis,zgl_tresc,hd_kategoria_opis,hd_status_opis,zgl_E1C,zgl_E1P,zgl_E2C,zgl_E2P,zgl_E3C,zgl_E3P,zgl_naprawa_id,zgl_rozpoczecie_min, zgl_zakonczenie_min FROM $dbname_hd.hd_zgloszenie, $dbname_hd.hd_kategoria, $dbname_hd.hd_podkategoria,$dbname_hd.hd_status WHERE (zgl_kategoria=hd_kategoria_nr) and (zgl_podkategoria=hd_podkategoria_nr) and (zgl_status=hd_status_nr) and (zgl_czy_rozwiazany_problem=1) and (zgl_czy_rozwiazany_problem_data BETWEEN '$data_od' and '$data_do') and (zgl_widoczne=1) and (zgl_kategoria<>5) and ((zgl_poledodatkowe2='') or ((zgl_poledodatkowe2<>'') and (zgl_status<>1)))",$conn_hd);

ob_flush();
flush();
	
tbl_tr_highlight($i++);
	echo "<td class=center width=30 height=30>$i</td>";
	echo "<td>Liczba incydentów rozwiązanych w okresie rozliczeniowym [szt.]</td>";
	
	echo "<td class=center>";
	echo "&nbsp;<span id=percent2 style='background-color:black; color:yellow'></span>";
	echo "</td>";
	
	echo "<td class=center>".$pkt_2_value."</td>";

	if ($mozna_eksportowac==1) {
		echo "<td class=center>";
		echo "<form name=pkt1 style='display:inline' action=do_xls_htmlexcel_hd_g_raport_szcz_nowy.php METHOD=POST target=_blank>";
		echo "<input type=hidden name=do_punktu value='2'>";
		echo "<input type=hidden name=okres value='$data_od-$data_do'>";
		echo "<input type=hidden name=obszar value='$obszar'>";		
		echo "<input type=hidden name=opis_raportu value='liczba_zgl_rozwiazanych_sposrod_tych_ktore_wplynely_w_biezacym_okresie'>";
		echo "<a id=ex2 style='display:none' title=' Wyeksportuj zgłoszenia do pliku XLS '><input class=imgoption type=image src=img/xls_icon.gif onclick=\"document.pkt1.submit();\"></a>";
		echo "</form>";
		echo "</td>";
	}

echo "</tr>";
ob_flush();
flush();

// wypełnienie pozostałych pól w tabeli tymczasowej
// pole44 : zgl_naprawa_id

$wybierz_z_2 = "SELECT pole2,pole44,pole21 FROM $dbname_hd.hd_temp_raport_rozbudowany_2";
$result_z_2 = mysql_query($wybierz_z_2, $conn_hd) or die($k_b);
$count_rows_z_2 = mysql_num_rows($result_z_2);
$licznik = 0;

if ($count_rows_z_2>0) {

	while ($newArray = mysql_fetch_array($result_z_2)) {
	
		$zgl_nr 		= $newArray['pole2'];
		$zgl_status 	= $newArray['pole21'];
		$naprawa_id 	= $newArray['pole44'];

		echo "$zgl_nr: $naprawa_id<br />";
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
		
		// ################################
		// ##### POLE 21 , 22,23      #####
		// ################################		
		
		// sprawdzenie czy nie jest "rozwiązany"
		$res1 = mysql_query("SELECT zgl_czy_rozwiazany_problem,zgl_razem_km,zgl_kategoria,zgl_status FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id='$zgl_nr') and (zgl_widoczne=1) LIMIT 1") or die($k_b);
		list($_czy_rozw,$km,$zgl_kat,$zs)=mysql_fetch_array($res1);
		
		if ($_czy_rozw=='1') {
			$_pole21 = 'rozwiązany';
			$res1 = mysql_query("SELECT zgl_szcz_data_systemowa_rejestracji_kroku, zgl_szcz_czas_rozpoczecia_kroku FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$zgl_nr') and (zgl_szcz_widoczne=1) and (zgl_szcz_czy_rozwiazany_problem=1) LIMIT 1") or die($k_b);
			list($_pole22,$_pole23)=mysql_fetch_array($res1);
			$_pole22 = substr($_pole22,0,16);
			$_pole23 = substr($_pole23,0,16);
			if ($zs=='9') $_pole21 = 'zamknięte';			
		} else {
			// statusy dla Poczty
			if ($zs=='1') $_pole21 = 'nowe';
			if ($zs=='2') $_pole21 = 'nowe';
			if ($zs=='3') $_pole21 = 'w trakcie realizacji';
			if ($zs=='3B') $_pole21 = 'w trakcie realizacji';
			if ($zs=='3A') $_pole21 = 'w serwisie zewnętrznym';
			if ($zs=='4') $_pole21 = 'oczekiwanie na odpowiedź klienta';
			if ($zs=='5') $_pole21 = 'w trakcie realizacji';
			if ($zs=='6') $_pole21 = 'w trakcie realizacji';
			if ($zs=='7') $_pole21 = 'w trakcie realizacji';
			if ($zs=='9') $_pole21 = 'zamknięte';		
		}
		

	if (($zgl_kat=='2') || ($zgl_kat=='6') || ($zgl_kat=='3') || ($zgl_kat=='4')) {
		
		// #########################
		// ##### POLE 12 i 13 ######
		// #########################
		
		
		// jeżeli powiązana naprawa
		if ($naprawa_id>0) {
			$res1 = mysql_query("SELECT naprawa_nazwa, naprawa_model, naprawa_sn, naprawa_ni FROM $dbname.serwis_naprawa WHERE naprawa_id='$naprawa_id' LIMIT 1") or die($k_b);
			list($mnazwa,$mmodel,$msn,$mni)=mysql_fetch_array($res1);
			
			$_pole12 = $msn." / ".$mni;
			$_pole13 = $mnazwa." ".$mmodel;		
			//echo "<br />|".$_pole13;
		} 
		
		if ($_pole13=='') {
			// sprawdź czy dla zgłoszenia nie ma powiązanej wymiany podzespołów
			$res1 = mysql_query("SELECT wp_sprzet_opis, pozycja_status,wp_sprzet_sn,wp_sprzet_ni FROM $dbname_hd.hd_zgl_wymiany_podzespolow, $dbname.serwis_faktura_szcz WHERE (wp_sprzedaz_fakt_szcz_id=pozycja_id) and (wp_zgl_id=$zgl_nr) LIMIT 1") or die($k_b);
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
		
		// #########################
		// ##### POLE 15      ######
		// #########################
		
		// ustalenie daty rozpoczęcia kroku o statusie >= 3
		$res1 = mysql_query("SELECT zgl_szcz_czas_rozpoczecia_kroku FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$zgl_nr') and (zgl_szcz_status<>'1') and (zgl_szcz_status<>'2') and (zgl_szcz_widoczne=1) ORDER BY zgl_szcz_nr_kroku ASC LIMIT 1") or die($k_b);
		list($_pole15)=mysql_fetch_array($res1);
		$_pole15 = substr($_pole15,0,16);	
		
		// ######################################
		// ##### POLE 16,17,18,19,24,26,27  #####
		// ######################################
		
		// przewidywany czas reakcji (wg umowy) i przewidywany czas zakończenia | dla Awaii i Awarii krytycznych
		$res1 = mysql_query("SELECT zgl_rozpoczecie_min, zgl_zakonczenie_min,zgl_E1C,zgl_E2C FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id='$zgl_nr') and ((zgl_kategoria='2') or (zgl_kategoria='6')) and (zgl_widoczne=1) LIMIT 1") or die($k_b);
		list($_pole16,$_pole24,$_pole17,$_etap2C)=mysql_fetch_array($res1);
		
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
	}
	
		// ################################
		// ##### POLE 25              #####
		// ################################	
		
		// czas rozwiązania incydentu
		$res1 = mysql_query("SELECT zgl_E1C,zgl_E2C,zgl_E3C,zgl_E1P,zgl_E2P,zgl_E3P FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id='$zgl_nr') and (zgl_widoczne=1) LIMIT 1") or die($k_b);
		list($_etap1c,$_etap2c,$_etap3c,$_etap1p,$_etap2p,$_etap3p)=mysql_fetch_array($res1);
		
		$_pole25 = $_etap1c + $_etap2c;

		// ################################
		// ##### POLE 28              #####
		// ################################	
		
	if (($zgl_kat=='2') || ($zgl_kat=='6') || ($zgl_kat=='3') || ($zgl_kat=='4')) {
		
		$_pole36 = 'zdalnie';
		
		// zebranie wszystkich kroków w jedną zmienną
		$res1 = mysql_query("SELECT zgl_szcz_czas_rozpoczecia_kroku,zgl_szcz_status, zgl_szcz_wykonane_czynnosci,zgl_szcz_byl_wyjazd FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$zgl_nr') and (zgl_szcz_widoczne=1) ORDER BY zgl_szcz_nr_kroku ASC") or die($k_b);
		while (list($_czas,$_status,$_wc,$_byl_wyjazd)=mysql_fetch_array($res1)) {
		
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
		
			$_pole28 .= "".substr($_czas,0,16).",".$_status1.",".br2point($_wc)."\n\r";
			
			if ($_byl_wyjazd=='1') $_pole36 = 'wyjazd';
			
		}	
		
		// ################################
		// ##### POLE 36              #####
		// ################################	
		
		if (($zgl_kat=='4')) $_pole36 = 'zdalnie';
		if ($zgl_kat=='1') $_pole36 = 'pierwszy kontakt';

	} else $_pole36 = 'pierwszy kontakt';
	
	
		// ################################
		// ##### POLE 29              #####
		// ################################			
		$res1 = mysql_query("SELECT zgl_szcz_czas_rozpoczecia_kroku FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$zgl_nr') and (zgl_szcz_widoczne=1) and (zgl_szcz_status=9) LIMIT 1") or die($k_b);
		list($_pole29)=mysql_fetch_array($res1);
		$_pole29 = substr($_pole29,0,16);
		
		// ################################
		// ##### POLE 30              #####
		// ################################		
		$_pole30 = $_etap3c;
		
		$_pole31 = $_etap1c + $_etap2c + $_etap3c;
		$_pole32 = $_etap1p;
		$_pole33 = $_etap2p;
		$_pole34 = $_etap3p;
		
		// uaktualnij tabelę tymczasową
		$sql_update_2 = "UPDATE $dbname_hd.hd_temp_raport_rozbudowany_2 SET pole12='".$_pole12."', pole13='".$_pole13."', pole14='".$_pole14."', pole15='".$_pole15."', pole16='".$_pole16."', pole17='".$_pole17."', pole18='".$_pole18."', pole19='".$_pole19."', pole20='".$_pole20."', pole21='".$_pole21."', pole22='".$_pole22."', pole23='".$_pole23."', pole24='".$_pole24."', pole25='".$_pole25."', pole26='".$_pole26."', pole27='".$_pole27."', pole28='".$_pole28."', pole29='".$_pole29."', pole30='".$_pole30."', pole31='".$_pole31."', pole32='".$_pole32."', pole33='".$_pole33."', pole34='".$_pole34."', pole35='".$_pole35."', pole36='".$_pole36."' WHERE pole2=$zgl_nr LIMIT 1";
		
//		echo "$sql_update_1<br />";
		$update_2 = mysql_query($sql_update_2, $conn_hd) or die($k_b);

		$percent_value_2 = ceil($licznik++ * 100 / $count_rows_z_2);
		?><script>document.getElementById('percent2').innerHTML=' <?php echo $percent_value_2; ?>%&nbsp;';</script><?php
		ob_flush();
		flush();

	}
	?><script>document.getElementById('percent2').innerHTML=' 100%&nbsp;'; document.getElementById('ex2').style.display='';</script><?php
	ob_flush();
	flush();

}


tbl_tr_highlight($i++);

//list($pkt_3_value)=mysql_fetch_array(mysql_query("SELECT COUNT(DISTINCT(zgl_nr)) FROM $dbname_hd.hd_zgloszenie,$dbname_hd.hd_zgloszenie_szcz WHERE (hd_zgloszenie.zgl_id = hd_zgloszenie_szcz.zgl_szcz_zgl_id) and (SUBSTRING_INDEX(zgl_szcz_czas_rozpoczecia_kroku, ' ', 1 ) <= '$data_do') and (zgl_widoczne=1) and (zgl_kategoria<>5) and (zgl_status<>'9') and (zgl_szcz_status<>'9') and ((zgl_poledodatkowe2='') or ((zgl_poledodatkowe2<>'') and (zgl_status<>1)))", $conn_hd));

list($pkt_3_value)=mysql_fetch_array(mysql_query("SELECT COUNT(DISTINCT(zgl_nr)) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_czy_rozwiazany_problem=0) and (zgl_data_zmiany_statusu <='$data_do') and (zgl_widoczne=1) and (zgl_status<>'9') and (zgl_kategoria<>5) and ((zgl_poledodatkowe2='') or ((zgl_poledodatkowe2<>'') and (zgl_status<>1)))", $conn_hd));

$kopiuj_3 = mysql_query("INSERT INTO $dbname_hd.hd_temp_raport_rozbudowany_3 (pole2) SELECT DISTINCT(zgl_nr) FROM $dbname_hd.hd_zgloszenie, $dbname_hd.hd_zgloszenie_szcz WHERE (hd_zgloszenie.zgl_id = hd_zgloszenie_szcz.zgl_szcz_zgl_id) and (zgl_czy_rozwiazany_problem=0) and (zgl_data_zmiany_statusu <='$data_do') and (zgl_widoczne=1) and (zgl_kategoria<>5) and (zgl_status<>'9') and ((zgl_poledodatkowe2='') or ((zgl_poledodatkowe2<>'') and (zgl_status<>1)))",$conn_hd);

ob_flush();
flush();

	echo "<td class=center width=30 height=30>$i</td>";
	echo "<td>Liczba  incydentów w trakcie rozwiązywania [szt.]</td>";

	echo "<td class=center>";
	echo "&nbsp;<span id=percent3 style='background-color:black; color:yellow'></span>";
	echo "</td>";
	
	echo "<td class=center>".$pkt_3_value."</td>";

	if ($mozna_eksportowac==1) {
		echo "<td class=center>";
		echo "<form name=pkt1 style='display:inline' action=do_xls_htmlexcel_hd_g_raport_szcz_nowy.php METHOD=POST target=_blank>";
		echo "<input type=hidden name=do_punktu value='3'>";
		echo "<input type=hidden name=okres value='$data_od-$data_do'>";
		echo "<input type=hidden name=obszar value='$obszar'>";		
		echo "<input type=hidden name=opis_raportu value='liczba_zgloszen_w_trakcie_realizacji_z_biezacego_okresu'>";
		echo "<a id=ex3 style='display:none' title=' Wyeksportuj zgłoszenia do pliku XLS '><input class=imgoption type=image src=img/xls_icon.gif onclick=\"document.pkt1.submit();\"></a>";
		echo "</form>";
		echo "</td>";
	}

echo "</tr>";
ob_flush();
flush();

// wypełnienie pozostałych pól w tabeli tymczasowej
// pole44 : zgl_naprawa_id

$wybierz_z_3 = "SELECT pole2 FROM $dbname_hd.hd_temp_raport_rozbudowany_3";
$result_z_3 = mysql_query($wybierz_z_3, $conn_hd) or die($k_b);
$count_rows_z_3 = mysql_num_rows($result_z_3);
$licznik = 0;

if ($count_rows_z_3>0) {

	while ($newArray = mysql_fetch_array($result_z_3)) {
	
		$zgl_nr 		= $newArray['pole2'];
		//echo "SELECT zgl_typ_uslugi,zgl_nr, zgl_poczta_nr, CONCAT(zgl_data,' ',SUBSTRING_INDEX(zgl_godzina,':',2)) as MomentZgloszenia, zgl_komorka,zgl_przypisanie_jednostki, zgl_kategoria_komorki, zgl_osoba, hd_podkategoria_opis,zgl_tresc,hd_kategoria_opis,hd_status_opis,zgl_E1C,zgl_E1P,zgl_E2C,zgl_E2P,zgl_E3C,zgl_E3P,zgl_naprawa_id,zgl_rozpoczecie_min, zgl_zakonczenie_min FROM $dbname_hd.hd_zgloszenie,$dbname_hd.hd_podkategoria,$dbname_hd.hd_status,$dbname_hd.hd_kategoria WHERE (zgl_kategoria=hd_kategoria_nr) and (zgl_podkategoria=hd_podkategoria_nr) and (zgl_status=hd_status_nr) and (zgl_nr='$zgl_nr') LIMIT 1";
		
		$res1 = mysql_query("SELECT zgl_typ_uslugi,zgl_nr, zgl_poczta_nr, CONCAT(zgl_data,' ',SUBSTRING_INDEX(zgl_godzina,':',2)) as MomentZgloszenia, zgl_komorka,zgl_przypisanie_jednostki, zgl_kategoria_komorki, zgl_osoba, hd_podkategoria_opis,zgl_tresc,hd_kategoria_opis,hd_status_opis,zgl_E1C,zgl_E1P,zgl_E2C,zgl_E2P,zgl_E3C,zgl_E3P,zgl_naprawa_id,zgl_rozpoczecie_min, zgl_zakonczenie_min FROM $dbname_hd.hd_zgloszenie,$dbname_hd.hd_podkategoria,$dbname_hd.hd_status,$dbname_hd.hd_kategoria WHERE (zgl_kategoria=hd_kategoria_nr) and (zgl_podkategoria=hd_podkategoria_nr) and (zgl_status=hd_status_nr) and (zgl_nr='$zgl_nr') LIMIT 1") or die($k_b);
		
		list($_pole1,$_pole2,$_pole3,$_pole4,$_pole5,$_pole6,$_pole7,$_pole8,$_pole9,$_pole10,$_pole11,$_pole21,$_pole38,$_pole39,$_pole40,$_pole41,$_pole42,$_pole43,$_pole44,$_pole45,$_pole46)=mysql_fetch_array($res1);	
		
//echo "$_pole1,$_pole2,$_pole3,$_pole4,$_pole5,$_pole6,$_pole7,$_pole8,$_pole9,$_pole10,$_pole11,$_pole21,$_pole38,$_pole39,$_pole40,$_pole41,$_pole42,$_pole43,$_pole44,$_pole45,$_pole46";
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
		
		// ################################
		// ##### POLE 21 , 22,23      #####
		// ################################		
		
		// sprawdzenie czy nie jest "rozwiązany"
		$res1 = mysql_query("SELECT zgl_czy_rozwiazany_problem,zgl_razem_km,zgl_kategoria,zgl_status FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id='$zgl_nr') and (zgl_widoczne=1) LIMIT 1") or die($k_b);
		list($_czy_rozw,$km,$zgl_kat,$zs)=mysql_fetch_array($res1);
		
		if ($_czy_rozw=='1') {
			$_pole21 = 'rozwiązany';
			$res1 = mysql_query("SELECT zgl_szcz_data_systemowa_rejestracji_kroku, zgl_szcz_czas_rozpoczecia_kroku FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$zgl_nr') and (zgl_szcz_widoczne=1) and (zgl_szcz_czy_rozwiazany_problem=1) LIMIT 1") or die($k_b);
			list($_pole22,$_pole23)=mysql_fetch_array($res1);
			$_pole22 = substr($_pole22,0,16);
			$_pole23 = substr($_pole23,0,16);
			if ($zs=='9') $_pole21 = 'zamknięte';			
		} else {
			// statusy dla Poczty
			if ($zs=='1') $_pole21 = 'nowe';
			if ($zs=='2') $_pole21 = 'nowe';
			if ($zs=='3') $_pole21 = 'w trakcie realizacji';
			if ($zs=='3B') $_pole21 = 'w trakcie realizacji';
			if ($zs=='3A') $_pole21 = 'w serwisie zewnętrznym';
			if ($zs=='4') $_pole21 = 'oczekiwanie na odpowiedź klienta';
			if ($zs=='5') $_pole21 = 'w trakcie realizacji';
			if ($zs=='6') $_pole21 = 'w trakcie realizacji';
			if ($zs=='7') $_pole21 = 'w trakcie realizacji';
			if ($zs=='9') $_pole21 = 'zamknięte';		
		}
		

	if (($zgl_kat=='2') || ($zgl_kat=='6') || ($zgl_kat=='3') || ($zgl_kat=='4')) {
		
		// #########################
		// ##### POLE 12 i 13 ######
		// #########################
		
		
		// jeżeli powiązana naprawa
		if ($naprawa_id>0) {
			$res1 = mysql_query("SELECT naprawa_nazwa, naprawa_model, naprawa_sn, naprawa_ni FROM $dbname.serwis_naprawa WHERE naprawa_id='$naprawa_id' LIMIT 1") or die($k_b);
			list($mnazwa,$mmodel,$msn,$mni)=mysql_fetch_array($res1);
			
			$_pole12 = $msn." / ".$mni;
			$_pole13 = $mnazwa." ".$mmodel;		
			//echo "<br />|".$_pole13;
		} 
		
		if ($_pole13=='') {
			// sprawdź czy dla zgłoszenia nie ma powiązanej wymiany podzespołów
			$res1 = mysql_query("SELECT wp_sprzet_opis, pozycja_status,wp_sprzet_sn,wp_sprzet_ni FROM $dbname_hd.hd_zgl_wymiany_podzespolow, $dbname.serwis_faktura_szcz WHERE (wp_sprzedaz_fakt_szcz_id=pozycja_id) and (wp_zgl_id=$zgl_nr) LIMIT 1") or die($k_b);
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
		
		// #########################
		// ##### POLE 15      ######
		// #########################
		
		// ustalenie daty rozpoczęcia kroku o statusie >= 3
		$res1 = mysql_query("SELECT zgl_szcz_czas_rozpoczecia_kroku FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$zgl_nr') and (zgl_szcz_status<>'1') and (zgl_szcz_status<>'2') and (zgl_szcz_widoczne=1) ORDER BY zgl_szcz_nr_kroku ASC LIMIT 1") or die($k_b);
		list($_pole15)=mysql_fetch_array($res1);
		$_pole15 = substr($_pole15,0,16);	
		
		// ######################################
		// ##### POLE 16,17,18,19,24,26,27  #####
		// ######################################
		
		// przewidywany czas reakcji (wg umowy) i przewidywany czas zakończenia | dla Awaii i Awarii krytycznych
		$res1 = mysql_query("SELECT zgl_rozpoczecie_min, zgl_zakonczenie_min,zgl_E1C,zgl_E2C FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id='$zgl_nr') and ((zgl_kategoria='2') or (zgl_kategoria='6')) and (zgl_widoczne=1) LIMIT 1") or die($k_b);
		list($_pole16,$_pole24,$_pole17,$_etap2C)=mysql_fetch_array($res1);
		
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
		
	}
	
		// ################################
		// ##### POLE 25              #####
		// ################################	
		
		// czas rozwiązania incydentu
		$res1 = mysql_query("SELECT zgl_E1C,zgl_E2C,zgl_E3C,zgl_E1P,zgl_E2P,zgl_E3P FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id='$zgl_nr') and (zgl_widoczne=1) LIMIT 1") or die($k_b);
		list($_etap1c,$_etap2c,$_etap3c,$_etap1p,$_etap2p,$_etap3p)=mysql_fetch_array($res1);
		
		$_pole25 = $_etap1c + $_etap2c;

		// ################################
		// ##### POLE 28              #####
		// ################################	
		
	if (($zgl_kat=='2') || ($zgl_kat=='6') || ($zgl_kat=='3') || ($zgl_kat=='4')) {
		
		$_pole36 = 'zdalnie';
		
		// zebranie wszystkich kroków w jedną zmienną
		$res1 = mysql_query("SELECT zgl_szcz_czas_rozpoczecia_kroku,zgl_szcz_status, zgl_szcz_wykonane_czynnosci,zgl_szcz_byl_wyjazd FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$zgl_nr') and (zgl_szcz_widoczne=1) ORDER BY zgl_szcz_nr_kroku ASC") or die($k_b);
		while (list($_czas,$_status,$_wc,$_byl_wyjazd)=mysql_fetch_array($res1)) {
		
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
		
			$_pole28 .= "".substr($_czas,0,16).",".$_status1.",".br2point($_wc)."\n\r";
			
			if ($_byl_wyjazd=='1') $_pole36 = 'wyjazd';
			
		}	
		
		// ################################
		// ##### POLE 36              #####
		// ################################	
		
		if (($zgl_kat=='4')) $_pole36 = 'zdalnie';
		if ($zgl_kat=='1') $_pole36 = 'pierwszy kontakt';

	} else $_pole36 = 'pierwszy kontakt';
	
	
		// ################################
		// ##### POLE 29              #####
		// ################################			
		$res1 = mysql_query("SELECT zgl_szcz_czas_rozpoczecia_kroku FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$zgl_nr') and (zgl_szcz_widoczne=1) and (zgl_szcz_status=9) LIMIT 1") or die($k_b);
		list($_pole29)=mysql_fetch_array($res1);
		$_pole29 = substr($_pole29,0,16);
		
		// ################################
		// ##### POLE 30              #####
		// ################################		
		$_pole30 = $_etap3c;
		
		$_pole31 = $_etap1c + $_etap2c + $_etap3c;
		$_pole32 = $_etap1p;
		$_pole33 = $_etap2p;
		$_pole34 = $_etap3p;
		
		// uaktualnij tabelę tymczasową
		
		$sql_update_3 = "UPDATE $dbname_hd.hd_temp_raport_rozbudowany_3 SET pole1='".$_pole1."',pole3='".$_pole3."',pole4='".$_pole4."',pole5='".$_pole5."',pole6='".$_pole6."',pole7='".$_pole7."',pole8='".$_pole8."',pole9='".$_pole9."',pole10='".$_pole10."',pole11='".$_pole11."',pole21='".$_pole21."',pole38='".$_pole38."',pole39='".$_pole39."',pole40='".$_pole40."',pole41='".$_pole41."',pole42='".$_pole42."',pole43='".$_pole43."',pole44='".$_pole44."',pole45='".$_pole45."',pole46='".$_pole46."',pole12='".$_pole12."', pole13='".$_pole13."', pole14='".$_pole14."', pole15='".$_pole15."', pole16='".$_pole16."', pole17='".$_pole17."', pole18='".$_pole18."', pole19='".$_pole19."', pole20='".$_pole20."', pole21='".$_pole21."', pole22='".$_pole22."', pole23='".$_pole23."', pole24='".$_pole24."', pole25='".$_pole25."', pole26='".$_pole26."', pole27='".$_pole27."', pole28='".$_pole28."', pole29='".$_pole29."', pole30='".$_pole30."', pole31='".$_pole31."', pole32='".$_pole32."', pole33='".$_pole33."', pole34='".$_pole34."', pole35='".$_pole35."', pole36='".$_pole36."' WHERE (pole2='$zgl_nr') LIMIT 1";
		
		//echo "$licznik: $sql_update_3<hr />";
		$update_3 = mysql_query($sql_update_3, $conn_hd) or die($k_b);

		$percent_value_3 = ceil($licznik++ * 100 / $count_rows_z_3);
		?><script>document.getElementById('percent3').innerHTML=' <?php echo $percent_value_3; ?>%&nbsp;';</script><?php
		ob_flush();
		flush();

	}
	?><script>document.getElementById('percent3').innerHTML=' 100%&nbsp;'; document.getElementById('ex3').style.display='';</script><?php
	ob_flush();
	flush();

}

list($pkt_4_value)=mysql_fetch_array(mysql_query("SELECT COUNT(DISTINCT(zgl_nr)) FROM $dbname_hd.hd_zgloszenie,$dbname_hd.hd_zgloszenie_szcz WHERE (hd_zgloszenie.zgl_id = hd_zgloszenie_szcz.zgl_szcz_zgl_id) and (zgl_data_zmiany_statusu >= '$data_od') and (zgl_data_zmiany_statusu <= '$data_do') and (zgl_widoczne=1) and (zgl_kategoria<>5) and (zgl_status='9') and ((zgl_poledodatkowe2='') or ((zgl_poledodatkowe2<>'') and (zgl_status<>1)))", $conn_hd));

//echo "SELECT COUNT(DISTINCT(zgl_nr)) FROM $dbname_hd.hd_zgloszenie,$dbname_hd.hd_zgloszenie_szcz WHERE (hd_zgloszenie.zgl_id = hd_zgloszenie_szcz.zgl_szcz_zgl_id) and (zgl_data_zmiany_statusu >= '$data_od') and (zgl_data_zmiany_statusu <= '$data_do') and (zgl_widoczne=1) and (zgl_kategoria<>5) and (zgl_status='9') and ((zgl_poledodatkowe2='') or ((zgl_poledodatkowe2<>'') and (zgl_status<>1)))";

$kopiuj_4 = mysql_query("INSERT INTO $dbname_hd.hd_temp_raport_rozbudowany_4 (pole2) SELECT DISTINCT(zgl_nr) FROM $dbname_hd.hd_zgloszenie, $dbname_hd.hd_zgloszenie_szcz WHERE (hd_zgloszenie.zgl_id = hd_zgloszenie_szcz.zgl_szcz_zgl_id) and (zgl_data_zmiany_statusu >= '$data_od') and (zgl_data_zmiany_statusu <= '$data_do') and (zgl_widoczne=1) and (zgl_kategoria<>5) and (zgl_status='9') and ((zgl_poledodatkowe2='') or ((zgl_poledodatkowe2<>'') and (zgl_status<>1)))",$conn_hd);

ob_flush();
flush();
tbl_tr_highlight($i++);
	echo "<td class=center width=30 height=30>$i</td>";
	echo "<td>Liczba incydentów zamkniętych w okresie rozliczeniowym [szt.]</td>";
	
	echo "<td class=center>";
	echo "&nbsp;<span id=percent4 style='background-color:black; color:yellow'></span>";
	echo "</td>";
	
	echo "<td class=center>".$pkt_4_value."</td>";

	if ($mozna_eksportowac==1) {
		echo "<td class=center>";
		echo "<form name=pkt1 style='display:inline' action=do_xls_htmlexcel_hd_g_raport_szcz_nowy.php METHOD=POST target=_blank>";
		echo "<input type=hidden name=do_punktu value='4'>";
		echo "<input type=hidden name=okres value='$data_od-$data_do'>";
		echo "<input type=hidden name=obszar value='$obszar'>";		
		echo "<input type=hidden name=opis_raportu value='liczba_zgloszen_w_trakcie_realizacji_z_biezacego_okresu'>";
		echo "<a id=ex4 style='display:none' title=' Wyeksportuj zgłoszenia do pliku XLS '><input class=imgoption type=image src=img/xls_icon.gif onclick=\"document.pkt1.submit();\"></a>";
		echo "</form>";
		echo "</td>";
	}

echo "</tr>";
ob_flush();
flush();

// wypełnienie pozostałych pól w tabeli tymczasowej
// pole44 : zgl_naprawa_id

$wybierz_z_4 = "SELECT pole2 FROM $dbname_hd.hd_temp_raport_rozbudowany_4";
$result_z_4 = mysql_query($wybierz_z_4, $conn_hd) or die($k_b);
$count_rows_z_4 = mysql_num_rows($result_z_4);
$licznik = 0;

if ($count_rows_z_4>0) {

	while ($newArray = mysql_fetch_array($result_z_4)) {
	
		$zgl_nr 		= $newArray['pole2'];
		
		//echo "SELECT zgl_typ_uslugi,zgl_nr, zgl_poczta_nr, CONCAT(zgl_data,' ',SUBSTRING_INDEX(zgl_godzina,':',2)) as MomentZgloszenia, zgl_komorka,zgl_przypisanie_jednostki, zgl_kategoria_komorki, zgl_osoba, hd_podkategoria_opis,zgl_tresc,hd_kategoria_opis,hd_status_opis,zgl_E1C,zgl_E1P,zgl_E2C,zgl_E2P,zgl_E3C,zgl_E3P,zgl_naprawa_id,zgl_rozpoczecie_min, zgl_zakonczenie_min FROM $dbname_hd.hd_zgloszenie WHERE (zgl_nr='$zgl_nr') LIMIT 1";
		
		$res1 = mysql_query("SELECT zgl_typ_uslugi,zgl_nr, zgl_poczta_nr, CONCAT(zgl_data,' ',SUBSTRING_INDEX(zgl_godzina,':',2)) as MomentZgloszenia, zgl_komorka,zgl_przypisanie_jednostki, zgl_kategoria_komorki, zgl_osoba, hd_podkategoria_opis,zgl_tresc,hd_kategoria_opis,hd_status_opis,zgl_E1C,zgl_E1P,zgl_E2C,zgl_E2P,zgl_E3C,zgl_E3P,zgl_naprawa_id,zgl_rozpoczecie_min, zgl_zakonczenie_min FROM $dbname_hd.hd_zgloszenie,$dbname_hd.hd_podkategoria,$dbname_hd.hd_status,$dbname_hd.hd_kategoria WHERE (zgl_kategoria=hd_kategoria_nr) and (zgl_podkategoria=hd_podkategoria_nr) and (zgl_status=hd_status_nr) and (zgl_nr='$zgl_nr') LIMIT 1") or die($k_b);
		
		list($_pole1,$_pole2,$_pole3,$_pole4,$_pole5,$_pole6,$_pole7,$_pole8,$_pole9,$_pole10,$_pole11,$_pole21,$_pole38,$_pole39,$_pole40,$_pole41,$_pole42,$_pole43,$_pole44,$_pole45,$_pole46)=mysql_fetch_array($res1);	
		
//echo "$_pole1,$_pole2,$_pole3,$_pole4,$_pole5,$_pole6,$_pole7,$_pole8,$_pole9,$_pole10,$_pole11,$_pole21,$_pole38,$_pole39,$_pole40,$_pole41,$_pole42,$_pole43,$_pole44,$_pole45,$_pole46";
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
		
		
		// ################################
		// ##### POLE 21 , 22,23      #####
		// ################################		
		
		// sprawdzenie czy nie jest "rozwiązany"
		$res1 = mysql_query("SELECT zgl_czy_rozwiazany_problem,zgl_razem_km,zgl_kategoria,zgl_status FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id='$zgl_nr') and (zgl_widoczne=1) LIMIT 1") or die($k_b);
		list($_czy_rozw,$km,$zgl_kat,$zs)=mysql_fetch_array($res1);
						
		if ($_czy_rozw=='1') {
			$_pole21 = 'rozwiązany';
			$res1 = mysql_query("SELECT zgl_szcz_data_systemowa_rejestracji_kroku, zgl_szcz_czas_rozpoczecia_kroku FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$zgl_nr') and (zgl_szcz_widoczne=1) and (zgl_szcz_czy_rozwiazany_problem=1) LIMIT 1") or die($k_b);
			list($_pole22,$_pole23)=mysql_fetch_array($res1);
			$_pole22 = substr($_pole22,0,16);
			$_pole23 = substr($_pole23,0,16);
			if ($zs=='9') $_pole21 = 'zamknięte';			
		} else {
			// statusy dla Poczty
			if ($zs=='1') $_pole21 = 'nowe';
			if ($zs=='2') $_pole21 = 'nowe';
			if ($zs=='3') $_pole21 = 'w trakcie realizacji';
			if ($zs=='3B') $_pole21 = 'w trakcie realizacji';
			if ($zs=='3A') $_pole21 = 'w serwisie zewnętrznym';
			if ($zs=='4') $_pole21 = 'oczekiwanie na odpowiedź klienta';
			if ($zs=='5') $_pole21 = 'w trakcie realizacji';
			if ($zs=='6') $_pole21 = 'w trakcie realizacji';
			if ($zs=='7') $_pole21 = 'w trakcie realizacji';
			if ($zs=='9') $_pole21 = 'zamknięte';		
		}
		

	if (($zgl_kat=='2') || ($zgl_kat=='6') || ($zgl_kat=='3') || ($zgl_kat=='4')) {
		
		// #########################
		// ##### POLE 12 i 13 ######
		// #########################
		
		
		// jeżeli powiązana naprawa
		if ($naprawa_id>0) {
			$res1 = mysql_query("SELECT naprawa_nazwa, naprawa_model, naprawa_sn, naprawa_ni FROM $dbname.serwis_naprawa WHERE naprawa_id='$naprawa_id' LIMIT 1") or die($k_b);
			list($mnazwa,$mmodel,$msn,$mni)=mysql_fetch_array($res1);
			
			$_pole12 = $msn." / ".$mni;
			$_pole13 = $mnazwa." ".$mmodel;		
			//echo "<br />|".$_pole13;
		} 
		
		if ($_pole13=='') {
			// sprawdź czy dla zgłoszenia nie ma powiązanej wymiany podzespołów
			$res1 = mysql_query("SELECT wp_sprzet_opis, pozycja_status,wp_sprzet_sn,wp_sprzet_ni FROM $dbname_hd.hd_zgl_wymiany_podzespolow, $dbname.serwis_faktura_szcz WHERE (wp_sprzedaz_fakt_szcz_id=pozycja_id) and (wp_zgl_id=$zgl_nr) LIMIT 1") or die($k_b);
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
		
		// #########################
		// ##### POLE 15      ######
		// #########################
		
		// ustalenie daty rozpoczęcia kroku o statusie >= 3
		$res1 = mysql_query("SELECT zgl_szcz_czas_rozpoczecia_kroku FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$zgl_nr') and (zgl_szcz_status<>'1') and (zgl_szcz_status<>'2') and (zgl_szcz_widoczne=1) ORDER BY zgl_szcz_nr_kroku ASC LIMIT 1") or die($k_b);
		list($_pole15)=mysql_fetch_array($res1);
		$_pole15 = substr($_pole15,0,16);	
		
		// ######################################
		// ##### POLE 16,17,18,19,24,26,27  #####
		// ######################################
		
		// przewidywany czas reakcji (wg umowy) i przewidywany czas zakończenia | dla Awaii i Awarii krytycznych
		$res1 = mysql_query("SELECT zgl_rozpoczecie_min, zgl_zakonczenie_min,zgl_E1C,zgl_E2C FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id='$zgl_nr') and ((zgl_kategoria='2') or (zgl_kategoria='6')) and (zgl_widoczne=1) LIMIT 1") or die($k_b);
		list($_pole16,$_pole24,$_pole17,$_etap2C)=mysql_fetch_array($res1);
		
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
	}
	
		// ################################
		// ##### POLE 25              #####
		// ################################	
		
		// czas rozwiązania incydentu
		$res1 = mysql_query("SELECT zgl_E1C,zgl_E2C,zgl_E3C,zgl_E1P,zgl_E2P,zgl_E3P FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id='$zgl_nr') and (zgl_widoczne=1) LIMIT 1") or die($k_b);
		list($_etap1c,$_etap2c,$_etap3c,$_etap1p,$_etap2p,$_etap3p)=mysql_fetch_array($res1);
		
		$_pole25 = $_etap1c + $_etap2c;

		// ################################
		// ##### POLE 28              #####
		// ################################	
		
	if (($zgl_kat=='2') || ($zgl_kat=='6') || ($zgl_kat=='3') || ($zgl_kat=='4')) {
		
		$_pole36 = 'zdalnie';
		
		// zebranie wszystkich kroków w jedną zmienną
		$res1 = mysql_query("SELECT zgl_szcz_czas_rozpoczecia_kroku,zgl_szcz_status, zgl_szcz_wykonane_czynnosci,zgl_szcz_byl_wyjazd FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$zgl_nr') and (zgl_szcz_widoczne=1) ORDER BY zgl_szcz_nr_kroku ASC") or die($k_b);
		while (list($_czas,$_status,$_wc,$_byl_wyjazd)=mysql_fetch_array($res1)) {
		
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
		
			$_pole28 .= "".substr($_czas,0,16).",".$_status1.",".br2point($_wc)."\n\r";
			
			if ($_byl_wyjazd=='1') $_pole36 = 'wyjazd';
			
		}	
		
		// ################################
		// ##### POLE 36              #####
		// ################################	
		
		if (($zgl_kat=='4')) $_pole36 = 'zdalnie';
		if ($zgl_kat=='1') $_pole36 = 'pierwszy kontakt';

	} else $_pole36 = 'pierwszy kontakt';
	
	
		// ################################
		// ##### POLE 29              #####
		// ################################			
		$res1 = mysql_query("SELECT zgl_szcz_czas_rozpoczecia_kroku FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$zgl_nr') and (zgl_szcz_widoczne=1) and (zgl_szcz_status=9) LIMIT 1") or die($k_b);
		list($_pole29)=mysql_fetch_array($res1);
		$_pole29 = substr($_pole29,0,16);
		
		// ################################
		// ##### POLE 30              #####
		// ################################		
		$_pole30 = $_etap3c;
		
		$_pole31 = $_etap1c + $_etap2c + $_etap3c;
		$_pole32 = $_etap1p;
		$_pole33 = $_etap2p;
		$_pole34 = $_etap3p;
		
		// uaktualnij tabelę tymczasową
			
		$sql_update_4 = "UPDATE $dbname_hd.hd_temp_raport_rozbudowany_4 SET pole1='".$_pole1."',pole3='".$_pole3."',pole4='".$_pole4."',pole5='".$_pole5."',pole6='".$_pole6."',pole7='".$_pole7."',pole8='".$_pole8."',pole9='".$_pole9."',pole10='".$_pole10."',pole11='".$_pole11."',pole21='".$_pole21."',pole38='".$_pole38."',pole39='".$_pole39."',pole40='".$_pole40."',pole41='".$_pole41."',pole42='".$_pole42."',pole43='".$_pole43."',pole44='".$_pole44."',pole45='".$_pole45."',pole46='".$_pole46."',pole12='".$_pole12."', pole13='".$_pole13."', pole14='".$_pole14."', pole15='".$_pole15."', pole16='".$_pole16."', pole17='".$_pole17."', pole18='".$_pole18."', pole19='".$_pole19."', pole20='".$_pole20."', pole21='".$_pole21."', pole22='".$_pole22."', pole23='".$_pole23."', pole24='".$_pole24."', pole25='".$_pole25."', pole26='".$_pole26."', pole27='".$_pole27."', pole28='".$_pole28."', pole29='".$_pole29."', pole30='".$_pole30."', pole31='".$_pole31."', pole32='".$_pole32."', pole33='".$_pole33."', pole34='".$_pole34."', pole35='".$_pole35."', pole36='".$_pole36."' WHERE (pole2='$zgl_nr') LIMIT 1";
		
		//echo "$licznik: $sql_update_4<hr />";
		$update_4 = mysql_query($sql_update_4, $conn_hd) or die($k_b);

		$percent_value_4 = ceil($licznik++ * 100 / $count_rows_z_4);
		?><script>document.getElementById('percent4').innerHTML=' <?php echo $percent_value_4; ?>%&nbsp;';</script><?php
		ob_flush();
		flush();

	}
	?><script>document.getElementById('percent4').innerHTML=' 100%&nbsp;'; document.getElementById('ex4').style.display='';</script><?php
	ob_flush();
	flush();

}

	
tbl_tr_highlight($i++);

/*
tbl_tr_highlight($i++);
	echo "<td class=center width=30 height=30>$i</td>";
	echo "<td>Procent zgłoszeń rozwiązanych w bieżącym okresie [%]</td>";
	echo "<td class=center></td>";
	echo "<td class=center>".$pkt_5_value."</td>";
	echo "<td></td>";
echo "</tr>";

tbl_tr_highlight($i++);
	echo "<td class=center width=30 height=30>$i</td>";
	echo "<td>Procent zgłoszeń w trakcie realizacji w bieżącym okresie [%]</td>";
	echo "<td class=center></td>";
	echo "<td class=center>".$pkt_6_value."</td>";
	echo "<td></td>";
echo "</tr>";

tbl_tr_highlight($i++);
	echo "<td class=center width=30 height=30>$i</td>";
	echo "<td>Procent zgłoszeń reklamacyjnych [%]</td>";
	echo "<td class=center></td>";
	echo "<td class=center>".$pkt_7_value."</td>";
	echo "<td></td>";
echo "</tr>";

tbl_tr_highlight($i++);
	echo "<td class=center width=30 height=30>$i</td>";
	echo "<td>Średni czas rozwiązania zgłoszenia [godz.]</td>";
	echo "<td class=center></td>";
	echo "<td class=center>".minutes2hours($pkt_8_value,'short')."</td>";
	echo "<td></td>";
echo "</tr>";
*/

echo "</table>";

	echo "<form action=do_xls_htmlexcel_hd_g_statystyka_zgloszen.php METHOD=POST target=_blank>";
		
	echo "<input type=hidden name=okres value='$data_od-$data_do'>";
	echo "<input type=hidden name=obszar value='$obszar'>";

	echo "<input type=hidden name=pkt_1 value='$pkt_1_value'>";
	echo "<input type=hidden name=pkt_2 value='$pkt_2_value'>";
	echo "<input type=hidden name=pkt_3 value='$pkt_3_value'>";
	echo "<input type=hidden name=pkt_4 value='$pkt_4_value'>";
	echo "<input type=hidden name=pkt_5 value='$pkt_5_value'>";
	echo "<input type=hidden name=pkt_6 value='$pkt_6_value'>";
	echo "<input type=hidden name=pkt_7 value='$pkt_7_value'>";
	echo "<input type=hidden name=pkt_8 value='".minutes2hours($pkt_8_value,'short')."'>";
	
	echo "<input type=hidden name=rok value='$_REQUEST[r_rok]'>";
	startbuttonsarea("right");

	echo "<span style='float:left'>";
	
	echo "<input type=button class=buttons value='Zmień zakres danych do raportu'  onClick=\"self.location='hd_g_statystyka_zgloszen.php?okres=".$_REQUEST[rr]."&rok=".$_REQUEST[r_rok]."&q=3e4212e3e9976r9283juyh9jff90je90ijf0ij3f0-k2doijrefoik0329ok0reij0ok3er34kr3riu934r34r23efi0jcnsajsbdiweuhiweufh4234&d1=".$_REQUEST[d_od]."&d2=".$_REQUEST[d_do]."&d3=".$_REQUEST[r_T_zakres]."&d4=".$_REQUEST[r_M_zakres]."&d5=".$_REQUEST[r_K_zakres]."'; \" />";
	echo "</span>";
	
	addownsubmitbutton("'Generuj plik XLS'","refresh_");
	addbuttons("zamknij");

	endbuttonsarea();

_form();

?>
<script>
document.getElementById('TrwaLadowanie').style.display='none';
</script>

<?php 

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

pageheader("Generowanie raportu okresowego ze zgłoszeń (dla Poczty) - wersja rozbudowana");
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
			echo "<input type=radio name=rr id=rr_D value='D'"; if ($_REQUEST[okres]=='D') echo " checked "; echo " onClick=\"self.location='hd_g_statystyka_zgloszen_rozbudowany.php?okres=D&rok=".$_REQUEST[rok]."&q=".$_REQUEST[q]."&d1=".$_REQUEST[d1]."&d2=".$_REQUEST[d2]."'; \"><a class=normalfont href=# onClick=\"document.getElementById('rr_D').checked=true; self.location='hd_g_statystyka_zgloszen_rozbudowany.php?okres=D&rok=".$_REQUEST[rok]."&q=".$_REQUEST[q]."&d1=".$_REQUEST[d1]."&d2=".$_REQUEST[d2]."'; \">Dzienny (od..do)</a>";
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
			echo "<input type=radio name=rr id=rr_T value='T'"; if ($_REQUEST[okres]=='T') echo " checked "; echo " onClick=\"self.location='hd_g_statystyka_zgloszen_rozbudowany.php?okres=T&rok=".$_REQUEST[rok]."&q=".$_REQUEST[q]."&d1=".$_REQUEST[d1]."&d2=".$_REQUEST[d2]."'; \"><a class=normalfont href=# onClick=\"document.getElementById('rr_T').checked=true; self.location='hd_g_statystyka_zgloszen_rozbudowany.php?okres=T&rok=".$_REQUEST[rok]."&q=".$_REQUEST[q]."&d1=".$_REQUEST[d1]."&d2=".$_REQUEST[d2]."'; \">Tygodniowy</a>";
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
			echo "<input type=radio name=rr id=rr_M value='M'"; if ($_REQUEST[okres]=='M') echo " checked "; echo " onClick=\"self.location='hd_g_statystyka_zgloszen_rozbudowany.php?okres=M&rok=".$_REQUEST[rok]."&q=".$_REQUEST[q]."&d1=".$_REQUEST[d1]."&d2=".$_REQUEST[d2]."'; \"><a class=normalfont href=# onClick=\"document.getElementById('rr_M').checked=true; self.location='hd_g_statystyka_zgloszen_rozbudowany.php?okres=M&rok=".$_REQUEST[rok]."&q=".$_REQUEST[q]."&d1=".$_REQUEST[d1]."&d2=".$_REQUEST[d2]."'; \">Miesięczny</a>";
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
			echo "<input type=radio name=rr id=rr_K value='K'"; if ($_REQUEST[okres]=='K') echo " checked "; echo " onClick=\"self.location='hd_g_statystyka_zgloszen_rozbudowany.php?okres=K&rok=".$_REQUEST[rok]."&q=".$_REQUEST[q]."&d1=".$_REQUEST[d1]."&d2=".$_REQUEST[d2]."'; \"><a class=normalfont href=# onClick=\"document.getElementById('rr_K').checked=true; self.location='hd_g_statystyka_zgloszen_rozbudowany.php?okres=K&rok=".$_REQUEST[rok]."&q=".$_REQUEST[q]."&d1=".$_REQUEST[d1]."&d2=".$_REQUEST[d2]."'; \">Kwartalny</a>";
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
	addownsubmitbutton("'Generuj raport'","submit");

	echo "<span style='float:right'>";
	addbuttons('zamknij');
	echo "</span>";
	
	endbuttonsarea();
	
	_form();	
	
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

echo "STOP: [".date("H:i:s")."]";

?>
</body>
</html>

