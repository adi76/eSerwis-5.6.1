<?php 
include_once('cfg_helpdesk.php');
include_once('header.php');

echo "<body>";

if ($obszar=='Łódź') $obszar='Lodz';

$mozna_eksportowac = 1;
$wlacz_zakres_dzienny = 1; 

if ($submit) {

	//echo $_REQUEST[r_rok]."<br />";
	//echo "Okres: ".$_REQUEST[rr]."<br />";
	if ($_REQUEST[rr]=='T') $okres = $_REQUEST[r_T_zakres];
	if ($_REQUEST[rr]=='M') $okres = $_REQUEST[r_M_zakres];
	if ($_REQUEST[rr]=='K') $okres = $_REQUEST[r_K_zakres];

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

?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();

$zakres_dat = explode(" - ",$okres);

$data_od = $zakres_dat[0];
$data_do = $zakres_dat[1];

// Liczba zgłoszeń przekazanych

// Liczba zgłoszeń rozwiązanych spośród tych które wpłynęły w bieżacym okresie [szt.]
//echo "SELECT zgl_id FROM $dbname_hd.hd_zgloszenie WHERE (zgl_data BETWEEN '$data_od' and '$data_do') and (zgl_widoczne=1) and (zgl_kategoria<>5)";

//list($pkt_3_value)=mysql_fetch_array(mysql_query("SELECT COUNT(DISTINCT(zgl_id)) FROM $dbname_hd.hd_zgloszenie,$dbname_hd.hd_zgloszenie_szcz WHERE (zgl_data BETWEEN '$data_od' and '$data_do') and (zgl_widoczne=1) and (zgl_kategoria<>5) and (SUBSTRING(zgl_szcz_czas_rozpoczecia_kroku,1,10) BETWEEN '$data_od' and '$data_do') and (zgl_szcz_status<>1) and (zgl_szcz_status<>2) and (zgl_szcz_status<>9) and (zgl_szcz_widoczne=1) and (zgl_szcz_zgl_id=zgl_id)", $conn_hd));
//echo "SELECT COUNT(DISTINCT(zgl_id)) FROM $dbname_hd.hd_zgloszenie,$dbname_hd.hd_zgloszenie_szcz WHERE (zgl_data BETWEEN '$data_od' and '$data_do') and (zgl_widoczne=1) and (zgl_kategoria<>5) and (SUBSTRING(zgl_szcz_czas_rozpoczecia_kroku,1,10) BETWEEN '$data_od' and '$data_do') and (zgl_szcz_status<>1) and (zgl_szcz_status<>2) and (zgl_szcz_status<>9) and (zgl_szcz_widoczne=1) and (zgl_szcz_zgl_id=zgl_id)";


//list($pkt_4_value)=mysql_fetch_array(mysql_query("SELECT COUNT(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_data BETWEEN '2010-10-01' and '$data_od') and (zgl_widoczne=1) and (zgl_kategoria<>5) and (zgl_status<>1) and (zgl_status<>2) and (zgl_status<>9) ", $conn_hd));

//echo "SELECT COUNT(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_data BETWEEN '2010-10-01' and '$data_od') and (zgl_widoczne=1) and (zgl_kategoria<>5) and (zgl_status<>1) and (zgl_status<>2) and (zgl_status<>9) ";

//echo $sql4."<br />";
//echo $count_rows1;
// jeżeli są zgłoszenia z wyznaczonymi czasami rozpoczęcia / zakończenia


/*
if ($count_rows1>0) {
	
	while (list($w_zgl_id,$w_zgl_dr,$w_zgl_dz, $w_zgl_s, $w_zgl_dzs)=mysql_fetch_array($result4)) {

		// pobierz czas rozpoczęcia wykonywania kroku i czas wykonywania kroku
		$r3 = mysql_query("SELECT zgl_szcz_czas_rozpoczecia_kroku, zgl_szcz_czas_wykonywania FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$w_zgl_id') ORDER BY zgl_szcz_nr_kroku DESC LIMIT 1", $conn_hd) or die($k_b);
		list($w_zgl_szcz_czas_rozp, $w_zgl_szcz_czas_wyk)=mysql_fetch_array($r3);
		
		// wyznacz faktyczny czas zakończenia wykonywania kroku
		$newTime = AddMinutesToDate($w_zgl_szcz_czas_wyk,$w_zgl_szcz_czas_rozp);
		//echo $w_zgl_dz." | ".$w_zgl_szcz_czas_rozp." | ".$newTime."<br />";
		
		if ($newTime>$w_zgl_dz) $pkt_4_value++;
		
	}
}

*/


//echo "SELECT DISTINCT(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_data <= '$data_od') and (zgl_widoczne=1) and ((zgl_poledodatkowe2='') or ((zgl_poledodatkowe2<>'') and (zgl_status<>1))) and (zgl_kategoria<>5) and ((zgl_data_zmiany_statusu > '$data_do') or ((zgl_data_zmiany_statusu < '$data_od') and (zgl_status<>9)))";
// przygotowanie tabeli tymczasowej
	
	// tworzenie tabeli tycznasowej dla raportów
	$sql_f = "SELECT filia_leader FROM $dbname.serwis_filie WHERE filia_leader=$es_nr LIMIT 1";
	$result_f = mysql_query($sql_f, $conn_hd) or die($k_b);
	$countf = mysql_num_rows($result_f);
	
	$czyistnieje1 = mysql_query("SELECT * FROM $dbname_hd.hd_temp_statystyka_1 LIMIT 1", $conn_hd);
	$czyistnieje2 = mysql_query("SELECT * FROM $dbname_hd.hd_temp_statystyka_2 LIMIT 1", $conn_hd);
	$czyistnieje3 = mysql_query("SELECT * FROM $dbname_hd.hd_temp_statystyka_3 LIMIT 1", $conn_hd);
	$czyistnieje4 = mysql_query("SELECT * FROM $dbname_hd.hd_temp_statystyka_4 LIMIT 1", $conn_hd);
	
	if ($czyistnieje1) {	
		if (($countf>0) || ($is_dyrektor)) { 
			$sql_report = "TRUNCATE TABLE $dbname_hd.hd_temp_statystyka_1";	$result_report = mysql_query($sql_report, $conn_hd) or die($k_b);		
		}
	} else { 
		if (($countf>0) || ($is_dyrektor)) { 
			$sql_report = "CREATE TABLE $dbname_hd.`hd_temp_statystyka_1` (`pole1` int(10) NOT NULL,`pole2` varchar(100) collate utf8_polish_ci NOT NULL,`pole3` varchar(100) collate utf8_polish_ci NOT NULL,`pole4` varchar(100) collate utf8_polish_ci NOT NULL,`pole5` varchar(100) collate utf8_polish_ci NOT NULL,`pole6` varchar(100) collate utf8_polish_ci NOT NULL,`pole7` text collate utf8_polish_ci NOT NULL,`pole8` text collate utf8_polish_ci NOT NULL,`pole9` varchar(100) collate utf8_polish_ci NOT NULL,`pole10` varchar(100) collate utf8_polish_ci NOT NULL,`pole11` varchar(100) collate utf8_polish_ci NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci";			
			$result_report = mysql_query($sql_report, $conn_hd) or die($k_b);	
		}
	}

	if ($czyistnieje2) {	
		if (($countf>0) || ($is_dyrektor)) { 
			$sql_report = "TRUNCATE TABLE $dbname_hd.hd_temp_statystyka_2";	$result_report = mysql_query($sql_report, $conn_hd) or die($k_b);		
		}
	} else { 
		if (($countf>0) || ($is_dyrektor)) { 
			$sql_report = "CREATE TABLE $dbname_hd.`hd_temp_statystyka_2` (`pole1` int(10) NOT NULL,`pole2` varchar(100) collate utf8_polish_ci NOT NULL,`pole3` varchar(100) collate utf8_polish_ci NOT NULL,`pole4` varchar(100) collate utf8_polish_ci NOT NULL,`pole5` varchar(100) collate utf8_polish_ci NOT NULL,`pole6` varchar(100) collate utf8_polish_ci NOT NULL,`pole7` text collate utf8_polish_ci NOT NULL,`pole8` text collate utf8_polish_ci NOT NULL,`pole9` varchar(100) collate utf8_polish_ci NOT NULL,`pole10` varchar(100) collate utf8_polish_ci NOT NULL,`pole11` varchar(100) collate utf8_polish_ci NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci";			
			$result_report = mysql_query($sql_report, $conn_hd) or die($k_b);	
		}
	}

	if ($czyistnieje3) {	
		if (($countf>0) || ($is_dyrektor)) { 
			$sql_report = "TRUNCATE TABLE $dbname_hd.hd_temp_statystyka_3";	$result_report = mysql_query($sql_report, $conn_hd) or die($k_b);		
		}
	} else { 
		if (($countf>0) || ($is_dyrektor)) { 
			$sql_report = "CREATE TABLE $dbname_hd.`hd_temp_statystyka_3` (`pole1` int(10) NOT NULL,`pole2` varchar(100) collate utf8_polish_ci NOT NULL,`pole3` varchar(100) collate utf8_polish_ci NOT NULL,`pole4` varchar(100) collate utf8_polish_ci NOT NULL,`pole5` varchar(100) collate utf8_polish_ci NOT NULL,`pole6` varchar(100) collate utf8_polish_ci NOT NULL,`pole7` text collate utf8_polish_ci NOT NULL,`pole8` text collate utf8_polish_ci NOT NULL,`pole9` varchar(100) collate utf8_polish_ci NOT NULL,`pole10` varchar(100) collate utf8_polish_ci NOT NULL,`pole11` varchar(100) collate utf8_polish_ci NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci";			
			$result_report = mysql_query($sql_report, $conn_hd) or die($k_b);	
		}
	}

	if ($czyistnieje4) {	
		if (($countf>0) || ($is_dyrektor)) { 
			$sql_report = "TRUNCATE TABLE $dbname_hd.hd_temp_statystyka_4";	$result_report = mysql_query($sql_report, $conn_hd) or die($k_b);		
		}
	} else { 
		if (($countf>0) || ($is_dyrektor)) { 
			$sql_report = "CREATE TABLE $dbname_hd.`hd_temp_statystyka_4` (`pole1` int(10) NOT NULL,`pole2` varchar(100) collate utf8_polish_ci NOT NULL,`pole3` varchar(100) collate utf8_polish_ci NOT NULL,`pole4` varchar(100) collate utf8_polish_ci NOT NULL,`pole5` varchar(100) collate utf8_polish_ci NOT NULL,`pole6` varchar(100) collate utf8_polish_ci NOT NULL,`pole7` text collate utf8_polish_ci NOT NULL,`pole8` text collate utf8_polish_ci NOT NULL,`pole9` varchar(100) collate utf8_polish_ci NOT NULL,`pole10` varchar(100) collate utf8_polish_ci NOT NULL,`pole11` varchar(100) collate utf8_polish_ci NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci";			
			$result_report = mysql_query($sql_report, $conn_hd) or die($k_b);	
		}
	}

	
$i = 0;
		
		$kopiuj_1 = mysql_query("INSERT INTO $dbname_hd.hd_temp_statystyka_1 (pole1,pole2,pole3,pole4,pole5,pole6,pole7,pole8,pole9,pole10,pole11) SELECT zgl_nr, zgl_poczta_nr,zgl_data,zgl_godzina, zgl_komorka,zgl_osoba, zgl_temat, zgl_tresc, hd_kategoria_opis, zgl_nr, zgl_razem_czas FROM $dbname_hd.hd_zgloszenie, $dbname_hd.hd_kategoria WHERE (zgl_kategoria=hd_kategoria_nr) and (zgl_data BETWEEN '$data_od' and '$data_do') and (zgl_widoczne=1) and (zgl_kategoria<>5) and ((zgl_poledodatkowe2='') or ((zgl_poledodatkowe2<>'') and (zgl_status<>1)))",$conn_hd);
		
		list($pkt_1_value)=mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM $dbname_hd.hd_temp_statystyka_1",$conn_hd));
		
		ob_flush();
		flush();
		
tbl_tr_highlight($i++);
	echo "<td class=center width=30 height=30>$i</td>";
	echo "<td>Liczba zgłoszeń które wpłynęły w bieżącym okresie [szt.]</td>";
	
	echo "<td class=center>".$pkt_1_value."</td>";
	
	if ($mozna_eksportowac==1) {
		echo "<td class=center>";
		echo "<form name=pkt1 style='display:inline' action=do_xls_htmlexcel_hd_g_raport_szcz_nowy.php METHOD=POST target=_blank>";
		echo "<input type=hidden name=do_punktu value='1'>";
		echo "<input type=hidden name=okres value='$data_od-$data_do'>";
		echo "<input type=hidden name=obszar value='$obszar'>";		
		echo "<input type=hidden name=opis_raportu value='liczba_zgl_ktore_wplynely_w_biezacym_okresie'>";
		echo "<a title=' Wyeksportuj zgłoszenia do pliku XLS '><input class=imgoption type=image src=img/xls_icon.gif onclick=\"document.pkt1.submit();\"></a>";
		echo "</form>";
		echo "</td>";
	}

echo "</tr>";

		$_p2 = mysql_query("SELECT zgl_id, zgl_nr, zgl_poczta_nr,zgl_data,zgl_godzina, zgl_komorka,zgl_osoba, zgl_temat, zgl_tresc, hd_kategoria_opis, zgl_razem_czas FROM $dbname_hd.hd_zgloszenie, $dbname_hd.hd_kategoria WHERE (zgl_kategoria=hd_kategoria_nr) and (zgl_data BETWEEN '$data_od' and '$data_do') and (zgl_widoczne=1) and (zgl_kategoria<>5) and ((zgl_poledodatkowe2='') or ((zgl_poledodatkowe2<>'') and (zgl_status<>1)))", $conn_hd) or die($k_b);
		$count_p2 = mysql_num_rows($_p2);

		$pkt_2_value = 0;
		$pkt_3_value = 0;
		$pkt_4_value = 0;

		$licznik =0;

		while ($newArray = mysql_fetch_array($_p2)) {
			$temp_id 	= $newArray['zgl_id'];
			
			$temp_data1 	= $newArray['zgl_nr'];
			$temp_data2 	= $newArray['zgl_poczta_nr'];
			$temp_data3 	= $newArray['zgl_data'];
			$temp_data4 	= $newArray['zgl_godzina'];
			$temp_data5 	= $newArray['zgl_komorka'];
			$temp_data6 	= $newArray['zgl_osoba'];
			$temp_data7 	= $newArray['zgl_temat'];
			$temp_data8 	= $newArray['zgl_tresc'];
			$temp_data9 	= $newArray['hd_kategoria_opis'];
			$temp_data10 	= $newArray['hd_priorytet_opis'];
			$temp_data11 	= $newArray['zgl_razem_czas'];

			$temp_data6 = str_replace("\\", "", $temp_data6);

			$temp_data7 = str_replace("\\", "/", $temp_data7);
			$temp_data8 = str_replace("\\", "/", $temp_data8);
			$temp_data7 = str_replace('"', '`', $temp_data7);
			$temp_data8 = str_replace('"', '`', $temp_data8);
			$temp_data7 = str_replace("'", "`", $temp_data7);
			$temp_data8 = str_replace("'", "`", $temp_data8);

			
			list($status,$sz_id,$sz_nr)=mysql_fetch_array(mysql_query("SELECT zgl_szcz_status,zgl_szcz_id,zgl_szcz_nr_kroku FROM $dbname_hd.hd_zgloszenie_szcz WHERE (SUBSTRING(zgl_szcz_czas_rozpoczecia_kroku,1,10) BETWEEN '$data_od' and '$data_do') and (zgl_szcz_zgl_id='$temp_id') ORDER BY zgl_szcz_nr_kroku DESC LIMIT 1", $conn_hd));
			
			//echo $temp_id.": szcz_id:".$szcz_id."|krok:".$sz_nr."|status:".$status."<br />";
			
			if ($status==9) {
				$pkt_2_value++;
				$dodaj_rekord = mysql_query("INSERT INTO $dbname_hd.hd_temp_statystyka_2 VALUES('$temp_data1','$temp_data2','$temp_data3','$temp_data4','$temp_data5','$temp_data6','$temp_data7','$temp_data8','$temp_data9','$temp_data10','$temp_data11')");
				
//				if ($currentuser=='Maciej Adrjanowicz') 
//					echo "INSERT INTO $dbname_hd.hd_temp_statystyka_2 VALUES('$temp_data1','$temp_data2','$temp_data3','$temp_data4','$temp_data5','$temp_data6','$temp_data7','$temp_data8','$temp_data9','$temp_data10','$temp_data11')<br />";
				
				//echo "INSERT INTO $dbname_hd.hd_temp_statystyka_2 VALUES('$temp_data1','$temp_data2','$temp_data3','$temp_data4','$temp_data5','$temp_data6','$temp_data7','$temp_data8','$temp_data9','$temp_data10','$temp_data11')<br />";
			}
			
			if ($status!=9) {
				$pkt_3_value++;
				$dodaj_rekord = mysql_query("INSERT INTO $dbname_hd.hd_temp_statystyka_3 VALUES('$temp_data1','$temp_data2','$temp_data3','$temp_data4','$temp_data5','$temp_data6','$temp_data7','$temp_data8','$temp_data9','$temp_data10','$temp_data11')");
				
				//echo "INSERT INTO $dbname_hd.hd_temp_statystyka_3 VALUES('$temp_data1','$temp_data2','$temp_data3','$temp_data4','$temp_data5','$temp_data6','$temp_data7','$temp_data8','$temp_data9','$temp_data10','$temp_data11')<br />";
			
			}
			
		//	if (($status!=9) && ($temp_data<$data_od)) $pkt_4_value++;
			
			if (($licznik % 100)==0) { ob_flush();flush(); }
			$licznik++;
		}
		ob_flush();
		flush();
		
tbl_tr_highlight($i++);
	echo "<td class=center width=30 height=30>$i</td>";
	echo "<td>Liczba zgłoszeń rozwiązanych spośród tych które wpłynęły w bieżacym okresie [szt.]</td>";
	
	echo "<td class=center>".$pkt_2_value."</td>";

	if ($mozna_eksportowac==1) {
		echo "<td class=center>";
		echo "<form name=pkt1 style='display:inline' action=do_xls_htmlexcel_hd_g_raport_szcz_nowy.php METHOD=POST target=_blank>";
		echo "<input type=hidden name=do_punktu value='2'>";
		echo "<input type=hidden name=okres value='$data_od-$data_do'>";
		echo "<input type=hidden name=obszar value='$obszar'>";		
		echo "<input type=hidden name=opis_raportu value='liczba_zgl_rozwiazanych_sposrod_tych_ktore_wplynely_w_biezacym_okresie'>";
		echo "<a title=' Wyeksportuj zgłoszenia do pliku XLS '><input class=imgoption type=image src=img/xls_icon.gif onclick=\"document.pkt1.submit();\"></a>";
		echo "</form>";
		echo "</td>";
	}

echo "</tr>";

tbl_tr_highlight($i++);
	echo "<td class=center width=30 height=30>$i</td>";
	echo "<td>Liczba zgłoszeń w trakcie realizacji z bieżacego okresu [szt.]</td>";
	echo "<td class=center>".$pkt_3_value."</td>";

	if ($mozna_eksportowac==1) {
		echo "<td class=center>";
		echo "<form name=pkt1 style='display:inline' action=do_xls_htmlexcel_hd_g_raport_szcz_nowy.php METHOD=POST target=_blank>";
		echo "<input type=hidden name=do_punktu value='3'>";
		echo "<input type=hidden name=okres value='$data_od-$data_do'>";
		echo "<input type=hidden name=obszar value='$obszar'>";		
		echo "<input type=hidden name=opis_raportu value='liczba_zgloszen_w_trakcie_realizacji_z_biezacego_okresu'>";
		echo "<a title=' Wyeksportuj zgłoszenia do pliku XLS '><input class=imgoption type=image src=img/xls_icon.gif onclick=\"document.pkt1.submit();\"></a>";
		echo "</form>";
		echo "</td>";
	}

echo "</tr>";

		$_p2 = mysql_query("SELECT DISTINCT(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE 
		(zgl_data < '$data_od') and (zgl_widoczne=1) and ((zgl_poledodatkowe2='') or ((zgl_poledodatkowe2<>'') and (zgl_status<>1))) and (zgl_kategoria<>5) and ((zgl_data_zmiany_statusu BETWEEN '$data_od' and '$data_do') and (zgl_status<>9))",$conn_hd) or die($k_b);		
		$count_p2 = mysql_num_rows($_p2);

		$pkt_4_value = 0;
		$licznik =0;

		while ($newArray = mysql_fetch_array($_p2)) {
			$temp_id 	= $newArray['zgl_id'];
			
			$_p3 = mysql_query("SELECT * FROM $dbname_hd.hd_zgloszenie WHERE zgl_id=$temp_id",$conn_hd) or die($k_b);			
			while ($newArray1 = mysql_fetch_array($_p3)) {

				$temp_data1 	= $newArray1['zgl_nr'];
				$temp_data2 	= $newArray1['zgl_poczta_nr'];
				$temp_data3 	= $newArray1['zgl_data'];
				$temp_data4 	= $newArray1['zgl_godzina'];
				$temp_data5 	= $newArray1['zgl_komorka'];
				$temp_data6 	= $newArray1['zgl_osoba'];
				$temp_data7 	= $newArray1['zgl_temat'];
				$temp_data8 	= $newArray1['zgl_tresc'];
				$temp_data9 	= $newArray1['hd_kategoria_opis'];
				$temp_data10 	= $newArray1['hd_priorytet_opis'];
				$temp_data11 	= $newArray1['zgl_razem_czas'];
			
				$temp_data6 = str_replace("\\", "", $temp_data6);

				$temp_data7 = str_replace("\\", "/", $temp_data7);
				$temp_data8 = str_replace("\\", "/", $temp_data8);
				$temp_data7 = str_replace('"', '`', $temp_data7);
				$temp_data8 = str_replace('"', '`', $temp_data8);
				$temp_data7 = str_replace("'", "`", $temp_data7);
				$temp_data8 = str_replace("'", "`", $temp_data8);

			//if (($status!=9)) { // && ($temp_data3<$data_od)) {
				$pkt_4_value++;
				$dodaj_rekord = mysql_query("INSERT INTO $dbname_hd.hd_temp_statystyka_4 VALUES('$temp_data1','$temp_data2','$temp_data3','$temp_data4','$temp_data5','$temp_data6','$temp_data7','$temp_data8','$temp_data9','$temp_data10','$temp_data11')");
				//echo "INSERT INTO $dbname_hd.hd_temp_statystyka_4 VALUES('$temp_data1','$temp_data2','$temp_data3','$temp_data4','$temp_data5','$temp_data6','$temp_data7','$temp_data8','$temp_data9','$temp_data10','$temp_data11')<br />";
				
			}
			
			if (($licznik % 100)==0) { ob_flush();flush(); }
			$licznik++;
		}
		ob_flush();
		flush();
		
		$_p2 = mysql_query("SELECT DISTINCT(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE 
		(zgl_data <= '$data_od') and (zgl_widoczne=1) and ((zgl_poledodatkowe2='') or ((zgl_poledodatkowe2<>'') and (zgl_status<>1))) and (zgl_kategoria<>5) and ((zgl_data_zmiany_statusu > '$data_do') or ((zgl_data_zmiany_statusu < '$data_od') and (zgl_status<>9)))",$conn_hd) or die($k_b);
		$count_p2 = mysql_num_rows($_p2);

		$licznik = 0;
		while ($newArray = mysql_fetch_array($_p2)) {
			$temp_id 	= $newArray['zgl_id'];
			
			$_p3 = mysql_query("SELECT * FROM $dbname_hd.hd_zgloszenie WHERE zgl_id=$temp_id",$conn_hd) or die($k_b);			
			while ($newArray1 = mysql_fetch_array($_p3)) {

				$temp_data1 	= $newArray1['zgl_nr'];
				$temp_data2 	= $newArray1['zgl_poczta_nr'];
				$temp_data3 	= $newArray1['zgl_data'];
				$temp_data4 	= $newArray1['zgl_godzina'];
				$temp_data5 	= $newArray1['zgl_komorka'];
				$temp_data6 	= $newArray1['zgl_osoba'];
				$temp_data7 	= $newArray1['zgl_temat'];
				$temp_data8 	= $newArray1['zgl_tresc'];
				$temp_data9 	= $newArray1['hd_kategoria_opis'];
				$temp_data10 	= $newArray1['hd_priorytet_opis'];
				$temp_data11 	= $newArray1['zgl_razem_czas'];
			
				$temp_data6 = str_replace("\\", "", $temp_data6);

				$temp_data7 = str_replace("\\", "/", $temp_data7);
				$temp_data8 = str_replace("\\", "/", $temp_data8);
				$temp_data7 = str_replace('"', '`', $temp_data7);
				$temp_data8 = str_replace('"', '`', $temp_data8);
				$temp_data7 = str_replace("'", "`", $temp_data7);
				$temp_data8 = str_replace("'", "`", $temp_data8);

				//if (($status!=9)) { // && ($temp_data3<$data_od)) {
				$pkt_4_value++;
				$dodaj_rekord = mysql_query("INSERT INTO $dbname_hd.hd_temp_statystyka_4 VALUES('$temp_data1','$temp_data2','$temp_data3','$temp_data4','$temp_data5','$temp_data6','$temp_data7','$temp_data8','$temp_data9','$temp_data10','$temp_data11')");
			
			}
			
			if (($licznik % 100)==0) { ob_flush();flush(); }
			$licznik++;
		}
		ob_flush();
		flush();


	
tbl_tr_highlight($i++);
	echo "<td class=center width=30 height=30>$i</td>";
	echo "<td>Liczba zgłoszeń w trakcie realizacji z poprzednich okresów [szt.]</td>";	
	echo "<td class=center>".$pkt_4_value."</td>";

	if ($mozna_eksportowac==1) {
		echo "<td class=center>";
		echo "<form name=pkt1 style='display:inline' action=do_xls_htmlexcel_hd_g_raport_szcz_nowy.php METHOD=POST target=_blank>";
		echo "<input type=hidden name=do_punktu value='4'>";
		echo "<input type=hidden name=okres value='$data_od-$data_do'>";
		echo "<input type=hidden name=obszar value='$obszar'>";		
		echo "<input type=hidden name=opis_raportu value='liczba_zgloszen_w_trakcie_realizacji_z_poprzednich_okresow'>";
		echo "<a title=' Wyeksportuj zgłoszenia do pliku XLS '><input class=imgoption type=image src=img/xls_icon.gif onclick=\"document.pkt1.submit();\"></a>";
		echo "</form>";
		echo "</td>";
	}

echo "</tr>";


// procent zgłoszeń rozwiązanych
if ($pkt_1_value!=0) { $pkt_5_value = ceiling(($pkt_2_value / $pkt_1_value)*100); } else $pkt_5_value = 0;

// procent zgłoszeń w trakcie realizacji
//$pkt_6_value = ceiling(($pkt_3_value / $pkt_1_value)*100);
if ($pkt_1_value!=0) { //$pkt_6_value = ceiling(($pkt_3_value / $pkt_1_value)*100); 
	$pkt_6_value = 100 - $pkt_5_value;
	} else $pkt_6_value = 0;

// procent zgłoszeń reklamacyjnych
//$pkt_7_value = ceiling(($pkt_4_value / $pkt_1_value)*100);
if ($pkt_1_value!=0) { 
	//$pkt_7_value = ceiling(($pkt_4_value / $pkt_1_value)*100); 
	$pkt_7_value = 0;
} else $pkt_7_value = 0;

// średni czas rozwiązania zgłoszenia
list($suma_czasow)=mysql_fetch_array(mysql_query("SELECT SUM(zgl_razem_czas) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_data BETWEEN '$data_od' and '$data_do') and (zgl_status=9) and (zgl_widoczne=1) and (zgl_kategoria<>5) and (zgl_poledodatkowe2='')", $conn_hd));

//echo $suma_czasow;

//$pkt_8_value = ceiling(($suma_czasow / $pkt_2_value));
if (($suma_czasow!=0) && ($pkt_2_value!=0)) { $pkt_8_value = ceiling(($suma_czasow / $pkt_2_value)); } else $pkt_8_value = 0;


tbl_tr_highlight($i++);
	echo "<td class=center width=30 height=30>$i</td>";
	echo "<td>Procent zgłoszeń rozwiązanych w bieżącym okresie [%]</td>";
	echo "<td class=center>".$pkt_5_value."</td>";
	echo "<td></td>";
echo "</tr>";

tbl_tr_highlight($i++);
	echo "<td class=center width=30 height=30>$i</td>";
	echo "<td>Procent zgłoszeń w trakcie realizacji w bieżącym okresie [%]</td>";
	echo "<td class=center>".$pkt_6_value."</td>";
	echo "<td></td>";
echo "</tr>";

tbl_tr_highlight($i++);
	echo "<td class=center width=30 height=30>$i</td>";
	echo "<td>Procent zgłoszeń reklamacyjnych [%]</td>";
	echo "<td class=center>".$pkt_7_value."</td>";
	echo "<td></td>";
echo "</tr>";

tbl_tr_highlight($i++);
	echo "<td class=center width=30 height=30>$i</td>";
	echo "<td>Średni czas rozwiązania zgłoszenia [godz.]</td>";
	echo "<td class=center>".minutes2hours($pkt_8_value,'short')."</td>";
	echo "<td></td>";
echo "</tr>";

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
	echo "&nbsp;";
	echo "<input type=button class=buttons value='Zmień zakres danych do raportu'  onClick=\"self.location='hd_g_statystyka_zgloszen.php?okres=".$_REQUEST[rr]."&rok=".$_REQUEST[r_rok]."&q=3e4212e3e9976r9283juyh9jff90je90ijf0ij3f0-k2doijrefoik0329ok0reij0ok3er34kr3riu934r34r23efi0jcnsajsbdiweuhiweufh4234&d1=".$_REQUEST[d_od]."&d2=".$_REQUEST[d_do]."&d3=".$_REQUEST[r_T_zakres]."&d4=".$_REQUEST[r_M_zakres]."&d5=".$_REQUEST[r_K_zakres]."'; \" />";
	echo "</span>";
	
	addownsubmitbutton("'Generuj plik XLS'","refresh_");
	addbuttons("zamknij");

	endbuttonsarea();

_form();

?>

<script>HideWaitingMessage();</script>

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

pageheader("Generowanie raportu okresowego ze zgłoszeń (dla Poczty)");
echo "<form name=ruch action=hd_g_statystyka_zgloszen.php method=POST>";

	echo "<table cellspacing=1 align=center style=width:400px>";
	tbl_empty_row(1);

	$Rok_Min = '2011';

	echo "<tr>";
		echo "<td class=center colspan=2>";		
			echo "Raport dla roku:&nbsp;";

			if ($_REQUEST[rok]!='') { $Rok_Sel = $_REQUEST[rok]; } else { $Rok_Sel = date('Y'); }
			$Rok_Curr = date('Y');			
			$Lat_Wstecz = $Rok_Curr - $Rok_Min;
			
			echo "<select name=r_rok onChange=\"self.location='hd_g_statystyka_zgloszen.php?okres=".$_REQUEST[okres]."&rok='+this.value+'';\">";
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
			echo "<input type=radio name=rr id=rr_D value='D'"; if ($_REQUEST[okres]=='D') echo " checked "; echo " onClick=\"self.location='hd_g_statystyka_zgloszen.php?okres=D&rok=".$_REQUEST[rok]."&q=".$_REQUEST[q]."&d1=".$_REQUEST[d1]."&d2=".$_REQUEST[d2]."'; \"><a class=normalfont href=# onClick=\"document.getElementById('rr_D').checked=true; self.location='hd_g_statystyka_zgloszen.php?okres=D&rok=".$_REQUEST[rok]."&q=".$_REQUEST[q]."&d1=".$_REQUEST[d1]."&d2=".$_REQUEST[d2]."'; \">Dzienny (od..do)</a>";
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
			echo "<input type=radio name=rr id=rr_T value='T'"; if ($_REQUEST[okres]=='T') echo " checked "; echo " onClick=\"self.location='hd_g_statystyka_zgloszen.php?okres=T&rok=".$_REQUEST[rok]."&q=".$_REQUEST[q]."&d1=".$_REQUEST[d1]."&d2=".$_REQUEST[d2]."'; \"><a class=normalfont href=# onClick=\"document.getElementById('rr_T').checked=true; self.location='hd_g_statystyka_zgloszen.php?okres=T&rok=".$_REQUEST[rok]."&q=".$_REQUEST[q]."&d1=".$_REQUEST[d1]."&d2=".$_REQUEST[d2]."'; \">Tygodniowy</a>";
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
			echo "<input type=radio name=rr id=rr_M value='M'"; if ($_REQUEST[okres]=='M') echo " checked "; echo " onClick=\"self.location='hd_g_statystyka_zgloszen.php?okres=M&rok=".$_REQUEST[rok]."&q=".$_REQUEST[q]."&d1=".$_REQUEST[d1]."&d2=".$_REQUEST[d2]."'; \"><a class=normalfont href=# onClick=\"document.getElementById('rr_M').checked=true; self.location='hd_g_statystyka_zgloszen.php?okres=M&rok=".$_REQUEST[rok]."&q=".$_REQUEST[q]."&d1=".$_REQUEST[d1]."&d2=".$_REQUEST[d2]."'; \">Miesięczny</a>";
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
			echo "<input type=radio name=rr id=rr_K value='K'"; if ($_REQUEST[okres]=='K') echo " checked "; echo " onClick=\"self.location='hd_g_statystyka_zgloszen.php?okres=K&rok=".$_REQUEST[rok]."&q=".$_REQUEST[q]."&d1=".$_REQUEST[d1]."&d2=".$_REQUEST[d2]."'; \"><a class=normalfont href=# onClick=\"document.getElementById('rr_K').checked=true; self.location='hd_g_statystyka_zgloszen.php?okres=K&rok=".$_REQUEST[rok]."&q=".$_REQUEST[q]."&d1=".$_REQUEST[d1]."&d2=".$_REQUEST[d2]."'; \">Kwartalny</a>";
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

?>
</body>
</html>

