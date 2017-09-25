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

pageheader("Raport sumaryczny ze zgłoszeń za okres",0,0);
infoheader("Okres raportowania: <b>".$okres."</b>");

echo "<br /><p align=center style='padding:5px; background-color:white; color:blue;'><span id=step1 ></span><span id=step2></span></p>";
?><script>document.getElementById('step1').innerHTML=' Trwa przygotowywanie tabel tymczasowych...&nbsp;';</script><?php ob_flush(); flush();

$zakres_dat = explode(" - ",$okres);
$data_od = $zakres_dat[0];
$data_do = $zakres_dat[1];

$dokoncz_poprzedni = false;
if ($_REQUEST[dokoncz]=='on') $dokoncz_poprzedni = true;
	
if ($dokoncz_poprzedni==false) {
		// tworzenie tabeli tycznasowej dla raportów
		$sql_f = "SELECT filia_leader FROM $dbname.serwis_filie WHERE filia_leader=$es_nr LIMIT 1";
		$result_f = mysql_query($sql_f, $conn_hd) or die($k_b);
		$countf = mysql_num_rows($result_f);
		$fa = mysql_fetch_array($result_f);
		$filia_leader_nr = $fa['filia_leader'];
		//hd_g_raport_sumaryczny_ze_zgloszen
		$czyistnieje = mysql_query("SELECT * FROM $dbname_hd.hd_temp_raport_sumaryczny_$filia_leader_nr LIMIT 1", $conn_hd);
		$czyistnieje_nr = mysql_query("SELECT * FROM $dbname_hd.hd_temp_raport_sumaryczny_nr_$filia_leader_nr LIMIT 1", $conn_hd);
			
		if ($czyistnieje) {	
			if (($countf>0) || ($is_dyrektor)) { 
				$sql_report = "TRUNCATE TABLE $dbname_hd.hd_temp_raport_sumaryczny_$filia_leader_nr";	$result_report = mysql_query($sql_report, $conn_hd) or die($k_b);
			}
		} else { 
			if (($countf>0) || ($is_dyrektor)) { 
				$sql_report = "CREATE TABLE $dbname_hd.`hd_temp_raport_sumaryczny_$filia_leader_nr` (
				`pole1` varchar(8) collate utf8_polish_ci NOT NULL,
				`pole2` varchar(20) collate utf8_polish_ci NOT NULL,
				`pole3` varchar(100) collate utf8_polish_ci NOT NULL,
				`pole4` text collate utf8_polish_ci NOT NULL,
				`pole5` text collate utf8_polish_ci NOT NULL,
				`pole6` varchar(100) collate utf8_polish_ci NOT NULL,
				`pole7` varchar(100) collate utf8_polish_ci NOT NULL,
				`pole8` varchar(100) collate utf8_polish_ci NOT NULL,
				`pole9` varchar(100) collate utf8_polish_ci NOT NULL,
				`pole10` varchar(20) collate utf8_polish_ci NOT NULL,
				`pole11` int(10) collate utf8_polish_ci NOT NULL DEFAULT '0',
				`pole12` varchar(20) collate utf8_polish_ci NOT NULL,
				`pole13` int(10) collate utf8_polish_ci NOT NULL DEFAULT '0',
				`pole14` int(10) collate utf8_polish_ci NOT NULL DEFAULT '0',
				`pole15` varchar(100) collate utf8_polish_ci NOT NULL,
				`pole16` varchar(100) collate utf8_polish_ci NOT NULL,
				`pole17` varchar(100) collate utf8_polish_ci NOT NULL,
				`pole18` varchar(100) collate utf8_polish_ci NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;";	
				
				$result_report = mysql_query($sql_report, $conn_hd) or die($k_b);	
			}
		}

		if ($czyistnieje_nr) {	
			if (($countf>0) || ($is_dyrektor)) { 
				$sql_report = "TRUNCATE TABLE $dbname_hd.hd_temp_raport_sumaryczny_nr_$filia_leader_nr";	
				$result_report = mysql_query($sql_report, $conn_hd) or die($k_b);		
			}
		} else { 
			if (($countf>0) || ($is_dyrektor)) { 
				$sql_report = "CREATE TABLE $dbname_hd.`hd_temp_raport_sumaryczny_nr_$filia_leader_nr` (`pole1` varchar(8) collate utf8_polish_ci NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;";	
				$result_report = mysql_query($sql_report, $conn_hd) or die($k_b);	
			}
		}

	$i = 0;

	?><script>document.getElementById('step1').innerHTML=' Trwa przygotowywanie danych do raportu...<b>0%</b>&nbsp;';</script><?php ob_flush(); flush();

	$kopiuj_1 = mysql_query("INSERT INTO $dbname_hd.hd_temp_raport_sumaryczny_nr_$filia_leader_nr (pole1) SELECT zgl_nr FROM $dbname_hd.hd_zgloszenie WHERE (zgl_data BETWEEN '$data_od' and '$data_do') and (zgl_widoczne=1) and (zgl_kategoria<>5) and ((zgl_poledodatkowe2='') or ((zgl_poledodatkowe2<>'') and (zgl_status<>1)))",$conn_hd);
	?><script>document.getElementById('step1').innerHTML=' Trwa przygotowywanie danych do raportu...<b>20%</b>&nbsp;';</script><?php ob_flush(); flush();

	$kopiuj_2 = mysql_query("INSERT INTO $dbname_hd.hd_temp_raport_sumaryczny_nr_$filia_leader_nr (pole1) SELECT zgl_nr FROM $dbname_hd.hd_zgloszenie WHERE  (zgl_czy_rozwiazany_problem=1) and (zgl_czy_rozwiazany_problem_data BETWEEN '$data_od' and '$data_do') and (zgl_widoczne=1) and (zgl_kategoria<>5) and ((zgl_poledodatkowe2='') or ((zgl_poledodatkowe2<>'') and (zgl_status<>1)))",$conn_hd);
	?><script>document.getElementById('step1').innerHTML=' Trwa przygotowywanie danych do raportu...<b>40%</b>&nbsp;';</script><?php ob_flush(); flush();

	$kopiuj_3 = mysql_query("INSERT INTO $dbname_hd.hd_temp_raport_sumaryczny_nr_$filia_leader_nr (pole1) SELECT zgl_nr FROM $dbname_hd.hd_zgloszenie WHERE  (zgl_czy_rozwiazany_problem=0) and (zgl_data_zmiany_statusu <='$data_do') and (zgl_widoczne=1) and (zgl_kategoria<>5) and (zgl_status<>'9') and ((zgl_poledodatkowe2='') or ((zgl_poledodatkowe2<>'') and (zgl_status<>1)))",$conn_hd);
	?><script>document.getElementById('step1').innerHTML=' Trwa przygotowywanie danych do raportu...<b>60%</b>&nbsp;';</script><?php ob_flush(); flush();

	$kopiuj_4 = mysql_query("INSERT INTO $dbname_hd.hd_temp_raport_sumaryczny_nr_$filia_leader_nr (pole1) SELECT zgl_nr FROM $dbname_hd.hd_zgloszenie WHERE (zgl_data_zmiany_statusu >= '$data_od') and (zgl_data_zmiany_statusu <= '$data_do') and (zgl_widoczne=1) and (zgl_kategoria<>5) and (zgl_status='9') and ((zgl_poledodatkowe2='') or ((zgl_poledodatkowe2<>'') and (zgl_status<>1)))",$conn_hd);
	?><script>document.getElementById('step1').innerHTML=' Trwa przygotowywanie danych do raportu...<b>80%</b>&nbsp;';</script><?php ob_flush(); flush();

	$kopiuj_do_glownego = mysql_query("INSERT INTO $dbname_hd.hd_temp_raport_sumaryczny_$filia_leader_nr (pole1) SELECT pole1 FROM $dbname_hd.hd_temp_raport_sumaryczny_nr_$filia_leader_nr WHERE 1 GROUP BY pole1 ASC",$conn_hd);
	?><script>document.getElementById('step1').innerHTML=' Trwa przygotowywanie danych do raportu...<b>100%</b>&nbsp;';</script><?php ob_flush(); flush();

	// wypełnienie pozostałych pól w tabeli tymczasowej
	// pole44 : zgl_naprawa_id
	$licznik = 0;

	$wybierz_zgl = "SELECT pole1 FROM $dbname_hd.hd_temp_raport_sumaryczny_$filia_leader_nr";
	$result_zgl = mysql_query($wybierz_zgl, $conn_hd) or die($k_b);
	$count_zgl = mysql_num_rows($result_zgl);

	
} else {
	$wybierz_zgl = "SELECT COUNT(pole1) FROM $dbname_hd.hd_temp_raport_sumaryczny_$filia_leader_nr";
	$result_zgl = mysql_query($wybierz_zgl, $conn_hd) or die($k_b);
	$count_zgl = mysql_num_rows($result_zgl);
	
	$wybierz_zgl = "SELECT COUNT(pole1) FROM $dbname_hd.hd_temp_raport_sumaryczny_$filia_leader_nr WHERE (pole1!='')";
	$result_zgl = mysql_query($wybierz_zgl, $conn_hd) or die($k_b);
	$licznik = mysql_num_rows($result_zgl);
	$percent_value = ceil($licznik * 100 / $count_zgl);
	
	$wybierz_zgl = "SELECT pole1 FROM $dbname_hd.hd_temp_raport_sumaryczny_$filia_leader_nr WHERE pole1>=$_REQUEST[lasterror]";
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

	while ($newArray = mysql_fetch_array($result_zgl)) {
	
		$zgl_nr 		= $newArray['pole1'];
		
		$k_b = "<h2 style='text-align:center; color:black; font-weight:normal; background-color:red; padding:10px; '>&nbsp;Wystąpił błąd podczas analizy zgłoszenia nr <b>".$zgl_nr."</b></h2><p align=center><input type=button class=buttons value='Wstecz' onclick=\"self.location.href='hd_g_raport_sumaryczny_ze_zgloszen.php?okres=$_REQUEST[rr]&rok=$_REQUEST[r_rok]&q=3e4212e3e9976r9283juyh9jff90je90ijf0ij3f0-k2doijrefoik0329ok0reij0ok3er34kr3riu934r34r23efi0jcnsajsbdiweuhiweufh4234&lasterror=".$zgl_nr."';\"><input type=button class=buttons value='Pokaż zgłoszenie' onclick=\"newWindow_r(800,600,'hd_o_zgloszenia.php?action=podglad&id=".$zgl_nr."&nr=".$zgl_nr."'); return false;\"><input type=button class=buttons value='Zamknij' onclick=\"self.close();\"></p>";

		// wyzerowanie zmiennych
		$_pole1 = '';	// nr zgloszenia
		$_pole2 = '';	// data zgłoszenia
		$_pole3 = '';	// komórka
		$_pole4 = '';	// temat
		$_pole5 = '';	// treść
		$_pole6 = '';	// kategoria
		$_pole7 = '';	// podkategoria
		$_pole8 = '';	// status 
		$_pole9 = '';	// zasadne
		$_pole10 = '';	// filia
		$_pole11 = '';	// łączny czas kroków
		$_pole12 = '';	// sposób realizacji
		$_pole13 = '';	// czas dojazdu
		$_pole14 = '';	// łączna ilość km
		$_pole15 = '';	// typ komórki
		$_pole16 = '';	// kategoria komórki
		$_pole17 = '';	// data ostatniej zmiany statusu
		$_pole18 = '';

		$res1 = mysql_query("SELECT zgl_nr, zgl_data, zgl_godzina, zgl_komorka, zgl_temat, zgl_tresc, zgl_kategoria, zgl_podkategoria, zgl_status, zgl_zasadne, belongs_to, zgl_razem_czas, zgl_razem_km, zgl_data_zmiany_statusu FROM $dbname_hd.hd_zgloszenie WHERE zgl_nr=$zgl_nr LIMIT 1") or die($k_b);
		//echo "1|";
		list($_pole1,$_pole2a,$_pole2b,$_pole3,$_pole4,$_pole5,$_pole6a,$_pole7a,$_pole8a,$_pole9a,$_pole10a,$_pole11,$_pole14,$_pole17a) = mysql_fetch_array($res1);	
		//echo "2|";

		$result_upid = mysql_query("SELECT up_typ, up_kategoria FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) and (serwis_komorki.belongs_to=$_pole10a) and ((UPPER(CONCAT(serwis_piony.pion_nazwa,' ',serwis_komorki.up_nazwa)))='$_pole3') LIMIT 1", $conn) or die($k_b);
		list($_typ_k, $_pole16)=mysql_fetch_array($result_upid);		
		
		if ($_pole16==9) $_pole16 = 'Administracja';
		switch ($_typ_k) {
			case "1" : $_pole15 = 'UP'; break;
			case "2" : $_pole15 = 'FI'; break;
			case "3" : $_pole15 = 'AP'; break;
			case "4" : $_pole15 = 'Administracja'; break;
		}
		
		$_pole17 = substr($_pole17a,0,16);
		
		$_pole2 = substr($_pole2a,0,10)." ".substr($_pole2b,0,5);
		//$_pole2 = $_pole2a." ".$_pole2b;
		
		$_pole9 = 'NIE';		if ($_pole9a=='1') $_pole9 = 'TAK';
		
		
		$_fname = $filie[$_pole10];
		$_pole10 = $_fname;
		
		$res12 = mysql_query("SELECT filia_nazwa FROM $dbname.serwis_filie WHERE (filia_id='$_pole10a') LIMIT 1") or die($k_b);
		list($_pole10)=mysql_fetch_array($res12);		

		$_pole4 = str_replace('\'','`',$_pole4);		$_pole4 = str_replace('\"','',$_pole4);
		$_pole5 = str_replace('\'','`',$_pole5);		$_pole5 = str_replace('\"','',$_pole5);
		
		
		// ################################
		// ##### POLE 21 , 22,23      #####
		// ################################		
				
		if ($_pole7a=='1') $_pole7 = 'brak';
		if ($_pole7a=='2') $_pole7 = 'Oprogramowanie - Placówka pocztowa';
		if ($_pole7a=='3') $_pole7 = 'Stacja robocza';
		if ($_pole7a=='4') $_pole7 = 'Serwer';
		if ($_pole7a=='5') $_pole7 = 'Oprogramowanie - Administracja';
		if ($_pole7a=='6') $_pole7 = 'Aktualizacja SP2000';
		if ($_pole7a=='7') $_pole7 = 'Oprogramowanie techniczne';
		if ($_pole7a=='8') $_pole7 = 'Konserwacja sprzętu';
		if ($_pole7a=='9') $_pole7 = 'Urządzenia peryferyjne';
		if ($_pole7a=='0') $_pole7 = 'WAN/LAN';
		if ($_pole7a=='A') $_pole7 = 'Otwarcie placówki pocztowej';
		if ($_pole7a=='B') $_pole7 = 'Przeniesienie placówki pocztowej';
		if ($_pole7a=='C') $_pole7 = 'Zamknięcie placówki pocztowej';
		if ($_pole7a=='D') $_pole7 = 'Inne';
		if ($_pole7a=='E') $_pole7 = 'Alarmy';
		if ($_pole7a=='F') $_pole7 = 'Prace administracyjno-sprawozdawcze';
		
		$zgl_kat = $_pole6a;
		
		if ($_pole6a=='1') $_pole6 = 'Konsultacje';
		if ($_pole6a=='2') $_pole6 = 'Awarie';
		if ($_pole6a=='3') $_pole6 = 'Prace zlecone w ramach umowy';
		if ($_pole6a=='4') $_pole6 = 'Prace zlecone poza umową';
		if ($_pole6a=='5') $_pole6 = 'Prace na potrzeby Postdata';
		if ($_pole6a=='6') $_pole6 = 'Awarie krytyczne';
		
		if ($_pole8a=='1') $_pole8 = 'nowe';
		if ($_pole8a=='2') $_pole8 = 'przypisane';
		if ($_pole8a=='3') $_pole8 = 'rozpoczęte';
		if ($_pole8a=='3B') $_pole8 = 'w firmie';
		if ($_pole8a=='3A') $_pole8 = 'w serwisie zewnętrznym';
		if ($_pole8a=='4') $_pole8 = 'oczekiwanie na odpowiedź klienta';
		if ($_pole8a=='5') $_pole8 = 'oczekiwanie na sprzęt';
		if ($_pole8a=='6') $_pole8 = 'do oddania';
		if ($_pole8a=='7') $_pole8 = 'rozpoczęte - nie zakończone';
		if ($_pole8a=='9') $_pole8 = 'zamknięte';	
			
		$_pole12 = 'pierwszy kontakt';	
	
		if (($zgl_kat=='2') || ($zgl_kat=='6') || ($zgl_kat=='3') || ($zgl_kat=='4')) {
		
			$_pole12 = 'zdalnie';
		
			// zebranie wszystkich kroków w jedną zmienną
			$res13 = mysql_query("SELECT SUM(zgl_szcz_czas_trwania_wyjadu) FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$zgl_nr') and (zgl_szcz_widoczne=1) and (zgl_szcz_blokada_edycji_kroku=0) ORDER BY zgl_szcz_nr_kroku ASC") or die($k_b);
		
			list($_pole13)=mysql_fetch_array($res13);
		
			if ($_pole14>0) $_pole12 = 'wyjazd';
		}
	
		$res11 = mysql_query("SELECT zgl_szcz_id FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$zgl_nr') and (zgl_szcz_widoczne=1) and (zgl_szcz_byl_wyjazd=1)") or die($k_b);
		list($_bw)=mysql_fetch_array($res11);
		if ($_bw>1) $_pole12 = 'wyjazd';
	
	// uaktualnij tabelę tymczasową
		$sql_update = "UPDATE $dbname_hd.hd_temp_raport_sumaryczny_$filia_leader_nr SET pole2='".$_pole2."',pole3='".$_pole3."', pole4='".$_pole4."',pole5='".$_pole5."',pole6='".$_pole6."',pole7='".$_pole7."',pole8='".$_pole8."',pole9='".$_pole9."',pole10='".$_pole10."',pole11='".$_pole11."',pole12='".$_pole12."', pole13='".$_pole13."', pole14='".$_pole14."', pole15='".$_pole15."', pole16='".$_pole16."', pole17='".$_pole17."', pole18='".$_pole18."' WHERE (pole1='$zgl_nr') LIMIT 1";
		
		//echo "<br />".$sql_update."<br />";
		
		$update_zgl = mysql_query($sql_update, $conn_hd) or die($k_b);
		//echo "12<br />";
		$percent_value = ceil($licznik++ * 100 / $count_zgl);
		?><script>document.getElementById('step1').innerHTML=' Trwa uzupełnianie raportu danymi szczegółowymi...<b><?php echo $percent_value; ?>%</b>&nbsp;';</script><?php
		ob_flush();
		flush();

	}

	?><script>document.getElementById('step1').innerHTML=' Raport został wygenerowany | ilość pozycji: <b><?php echo $count_zgl; ?></b>&nbsp;'; //document.getElementById('ex3').style.display='';</script><?php
	ob_flush();
	flush();

}	

	echo "<form action=do_xls_htmlexcel_hd_g_raport_sumaryczny_ze_zgloszen.php METHOD=POST target=_blank>";		
		echo "<input type=hidden name=okres value='$data_od-$data_do'>";
		echo "<input type=hidden name=obszar value='$obszar'>";	
		echo "<input type=hidden name=rok value='$_REQUEST[r_rok]'>";
		echo "<input type=hidden name=opis_raportu value='raport_sumaryczny'>";
		echo "<input type=hidden name=filia_leader_nr value='$filia_leader_nr'>";
		echo "<input type=hidden name=addfilia value='$_REQUEST[addfilia]'>";
		echo "<input type=hidden name=add_time_and_kategoria value='$_REQUEST[add_time_and_kategoria]'>";
	echo "<br />";
	
	startbuttonsarea("right");
	echo "<span style='float:left'>";
	echo "<input type=button class=buttons value='Zmień zakres danych do raportu' onClick=\"self.location='hd_g_raport_sumaryczny_ze_zgloszen.php?okres=".$_REQUEST[rr]."&rok=".$_REQUEST[r_rok]."&q=3e4212e3e9976r9283juyh9jff90je90ijf0ij3f0-k2doijrefoik0329ok0reij0ok3er34kr3riu934r34r23efi0jcnsajsbdiweuhiweufh4234&d1=".$_REQUEST[d_od]."&d2=".$_REQUEST[d_do]."&d3=".$_REQUEST[r_T_zakres]."&d4=".$_REQUEST[r_M_zakres]."&d5=".$_REQUEST[r_K_zakres]."'; \" />";
	
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

pageheader("Generowanie raportu sumarycznego za okres");
?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();

echo "<form name=ruch action=hd_g_raport_sumaryczny_ze_zgloszen.php method=POST>";

	echo "<table cellspacing=1 align=center style=width:400px>";
	tbl_empty_row(1);

	$Rok_Min = '2011';

	echo "<tr>";
		echo "<td class=center colspan=2>";		
			echo "Raport dla roku:&nbsp;";

			if ($_REQUEST[rok]!='') { $Rok_Sel = $_REQUEST[rok]; } else { $Rok_Sel = date('Y'); }
			$Rok_Curr = date('Y');			
			$Lat_Wstecz = $Rok_Curr - $Rok_Min;
			
			echo "<select name=r_rok onChange=\"self.location='hd_g_raport_sumaryczny_ze_zgloszen.php?okres=".$_REQUEST[okres]."&rok='+this.value+'';\">";
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
			echo "<input type=radio name=rr id=rr_D value='D'"; if ($_REQUEST[okres]=='D') echo " checked "; echo " onClick=\"self.location='hd_g_raport_sumaryczny_ze_zgloszen.php?okres=D&rok=".$_REQUEST[rok]."&q=".$_REQUEST[q]."&d1=".$_REQUEST[d1]."&d2=".$_REQUEST[d2]."&lasterror=".$_REQUEST[lasterror]."'; \"><a class=normalfont href=# onClick=\"document.getElementById('rr_D').checked=true; self.location='hd_g_raport_sumaryczny_ze_zgloszen.php?okres=D&rok=".$_REQUEST[rok]."&q=".$_REQUEST[q]."&d1=".$_REQUEST[d1]."&d2=".$_REQUEST[d2]."&lasterror=".$_REQUEST[lasterror]."'; \">Dzienny (od..do)</a>";
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
			echo "<input type=radio name=rr id=rr_T value='T'"; if ($_REQUEST[okres]=='T') echo " checked "; echo " onClick=\"self.location='hd_g_raport_sumaryczny_ze_zgloszen.php?okres=T&rok=".$_REQUEST[rok]."&q=".$_REQUEST[q]."&d1=".$_REQUEST[d1]."&d2=".$_REQUEST[d2]."&lasterror=".$_REQUEST[lasterror]."'; \"><a class=normalfont href=# onClick=\"document.getElementById('rr_T').checked=true; self.location='hd_g_raport_sumaryczny_ze_zgloszen.php?okres=T&rok=".$_REQUEST[rok]."&q=".$_REQUEST[q]."&d1=".$_REQUEST[d1]."&d2=".$_REQUEST[d2]."&lasterror=".$_REQUEST[lasterror]."'; \">Tygodniowy</a>";
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
			echo "<input type=radio name=rr id=rr_M value='M'"; if ($_REQUEST[okres]=='M') echo " checked "; echo " onClick=\"self.location='hd_g_raport_sumaryczny_ze_zgloszen.php?okres=M&rok=".$_REQUEST[rok]."&q=".$_REQUEST[q]."&d1=".$_REQUEST[d1]."&d2=".$_REQUEST[d2]."&lasterror=".$_REQUEST[lasterror]."'; \"><a class=normalfont href=# onClick=\"document.getElementById('rr_M').checked=true; self.location='hd_g_raport_sumaryczny_ze_zgloszen.php?okres=M&rok=".$_REQUEST[rok]."&q=".$_REQUEST[q]."&d1=".$_REQUEST[d1]."&d2=".$_REQUEST[d2]."&lasterror=".$_REQUEST[lasterror]."'; \">Miesięczny</a>";
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
			echo "<input type=radio name=rr id=rr_K value='K'"; if ($_REQUEST[okres]=='K') echo " checked "; echo " onClick=\"self.location='hd_g_raport_sumaryczny_ze_zgloszen.php?okres=K&rok=".$_REQUEST[rok]."&q=".$_REQUEST[q]."&d1=".$_REQUEST[d1]."&d2=".$_REQUEST[d2]."&lasterror=".$_REQUEST[lasterror]."'; \"><a class=normalfont href=# onClick=\"document.getElementById('rr_K').checked=true; self.location='hd_g_raport_sumaryczny_ze_zgloszen.php?okres=K&rok=".$_REQUEST[rok]."&q=".$_REQUEST[q]."&d1=".$_REQUEST[d1]."&d2=".$_REQUEST[d2]."&lasterror=".$_REQUEST[lasterror]."'; \">Kwartalny</a>";
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
/*	
	echo "<p align=center>";
		echo "<input class=border0 type=checkbox name=addfilia id=addfilia />";
		echo "<a href=# class=normalfont onClick=\"if (document.getElementById('addfilia').checked) { document.getElementById('addfilia').checked=false; } else { document.getElementById('addfilia').checked=true; }\"> wersja do kontroli (dodatkowa kolumna z nazwą filii)&nbsp;</a>";
	echo "</p>";
	echo "<br />";

	echo "<p align=center>";
		echo "<input class=border0 type=checkbox name=add_time_and_kategoria id=add_time_and_kategoria />";
		echo "<a href=# class=normalfont onClick=\"if (document.getElementById('add_time_and_kategoria').checked) { document.getElementById('add_time_and_kategoria').checked=false; } else { document.getElementById('add_time_and_kategoria').checked=true; }\"> <font color=blue>dodaj kolumnę z kategorią komórki i łącznym czasem poświęconym na zgłoszenie</font>&nbsp;</a>";
	echo "</p>";
	echo "<br />";
*/	
	if ($_REQUEST[lasterror]!='') {
		infoheader("Poprzednio generowany raport nie został zakończony. <input class=border0 type=checkbox name=dokoncz id=dokoncz checked=checked /><a href=# class=normalfont onClick=\"if (document.getElementById('dokoncz').checked) { document.getElementById('dokoncz').checked=false; } else { document.getElementById('dokoncz').checked=true; }\"> Dokończ poprzedni raport</a>");
		echo "<input type=hidden name=lasterror value=$_REQUEST[lasterror] />";
		
		//echo "<p align=center>";
		//echo "<br />";
		addownsubmitbutton("'Generuj raport'","submit");
		
		//echo "</p>";
		
		
	} else {
		echo "<br />";
		addownsubmitbutton("'Generuj raport'","submit");
		
		echo "<input type=hidden name=lasterror value='' />";
	}

	echo "<span style='float:right'>";
	addbuttons('zamknij');
	echo "</span>";
	
	endbuttonsarea();

	_form();	
	/*
	if ($currentuser==$adminname) {
	echo "<hr />";
	echo "&nbsp;Tylko dla administratora: <form action=do_xls_htmlexcel_hd_g_statystyka_rozbudowany.php METHOD=POST target=_blank style='display:inline;'>";
		echo "<input type=hidden name=okres value='XXXX-XX-XX'>";
		echo "<input type=hidden name=obszar value='$obszar'>";	
		echo "<input type=hidden name=rok value='XXXX'>";
		echo "<input type=hidden name=opis_raportu value='raport_szczegolowy'>";
		
		echo "<input type=hidden name=addfilia value='on'>";
		echo "<input type=hidden name=add_time_and_kategoria value='$_REQUEST[add_time_and_kategoria]'>";
		
		addownsubmitbutton("'Generuj plik XLS z danych zapisanych w bazie'","refresh_");
		
		endbuttonsarea();
	echo "</form>";
	
	}
	*/
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

