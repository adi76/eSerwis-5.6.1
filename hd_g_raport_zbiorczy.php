<?php 
include_once('cfg_helpdesk.php');
include_once('header.php');

echo "<body>";

$mozna_eksportowac = 1;
$wlacz_zakres_dzienny = 1; 

if ($submit) {

	if ($obszar=='Łódź') $obszar='Lodz';

	if ($_REQUEST[rr]=='T') $okres = $_REQUEST[r_T_zakres];
	if ($_REQUEST[rr]=='M') $okres = $_REQUEST[r_M_zakres];
	if ($_REQUEST[rr]=='K') $okres = $_REQUEST[r_K_zakres];

	$okres1 = str_replace('@','-',$okres);
	$okres = str_replace('@',' - ',$okres);
	
	if ($_REQUEST[rr]=='D') $okres = $_REQUEST[d_od]." - ".$_REQUEST[d_do];
	
	pageheader("Raport zbiorczy",1,0);

	if ($_REQUEST[zakres]!='all') {
		$result6 = mysql_query("SELECT filia_nazwa FROM $dbname.serwis_filie WHERE filia_id=$_REQUEST[zakres] LIMIT 1", $conn) or die($k_b);				
		list($temp_nazwa_filii) = mysql_fetch_array($result6);
		$temp_zakres = $temp_nazwa_filii;
	} else {
		$temp_nazwa_filii = 'cały Oddział';
		$temp_zakres = 'caly_oddzial_lodz';
	}
	
	if ($_REQUEST[kat]!='all') {
		$result6 = mysql_query("SELECT hd_kategoria_opis FROM $dbname_hd.hd_kategoria WHERE hd_kategoria_nr=$_REQUEST[kat] LIMIT 1", $conn_hd) or die($k_b);				
		list($temp_kategorie) = mysql_fetch_array($result6);
		$temp_kategorie1 = str_replace(' ','_',$temp_kategorie);		
	} else {
		$temp_kategorie = 'wszystkich';
		$temp_kategorie1 = 'wszystkie';
		
	}
	
	infoheader("Okres raportowania: <b>".$okres."</b><br />Raport dla obszaru: <b>".$temp_nazwa_filii."</b><br />Raport dla kategorii: <b>".$temp_kategorie."</b>");
	
	echo "<table cellspacing=1 align=center>";
	echo "<tr>";
		
	if (($_REQUEST[kat]=='all') && ($_REQUEST[AddCategories]=='on')) {
	
		echo "<th class=center rowspan=2>";
		echo "LP";
		echo "</th>";
		echo "<th rowspan=2>";
		echo "Imię i nazwisko";
		echo "</th>";
		echo "<th class=center rowspan=2>";
		echo "Ilość zgłoszeń zarejestrowanych<br />(<font color=green>IZZ</font>)";
		echo "</th>";
		echo "<th class=center rowspan=2>";
		echo "Ilość wykonanych kroków<br />(<font color=brown>IWK</font>)";
		echo "</th>";
		echo "<th class=center rowspan=2>";
		echo "Łączny czas poświęcony na wykonanie kroków<br />(<font color=red>CZAS</font>)";
		echo "</th>";
		echo "<th class=center rowspan=2>";
		echo "Łączny czas poświęcony na przejazdy<br />(<font color=black>CZAS</font>)";
		echo "</th>";
		
		//$result6 = mysql_query("SELECT count(hd_kategoria_nr) FROM $dbname_hd.hd_kategoria WHERE (hd_kategoria_wlaczona=1) ORDER BY hd_kategoria_opis ASC", $conn) or die($k_b);
		//list($ilosc_kategorii) = mysql_fetch_array($result6);
		
		//echo "<th colspan=".($ilosc_kategorii*3).">
		$result6 = mysql_query("SELECT hd_kategoria_nr, hd_kategoria_opis FROM $dbname_hd.hd_kategoria WHERE (hd_kategoria_wlaczona=1) ORDER BY hd_kategoria_display_order ASC", $conn) or die($k_b);

		while (list($kat_nr,$kat_opis) = mysql_fetch_array($result6)) {
			echo "<th colspan=3 class=center><font color=blue>$kat_opis</font></th>";
			//echo "<th>Ilość wyk. kroków<br /><font color=blue>$kat_opis</font></th>";
		}
		echo "</tr>";
		echo "<tr>";
		//echo "<th colspan=5></th>";
		
		$result6 = mysql_query("SELECT hd_kategoria_nr, hd_kategoria_opis FROM $dbname_hd.hd_kategoria WHERE (hd_kategoria_wlaczona=1) ORDER BY hd_kategoria_display_order ASC", $conn) or die($k_b);
		while (list($kat_nr,$kat_opis) = mysql_fetch_array($result6)) {
			echo "<th class=center><font color=green>IZZ</font></th>";
			echo "<th class=center><font color=brown>IWK</font></th>";
			echo "<th class=center><font color=red>CZAS</font></th>";
			//echo "<th>Ilość wyk. kroków<br /><font color=blue>$kat_opis</font></th>";
		}		
		//echo "</tr>";
	} else {
		echo "<th class=center >";
		echo "LP";
		echo "</th>";
		echo "<th >";
		echo "Imię i nazwisko";
		echo "</th>";
		echo "<th class=center>";
		echo "Ilość zgłoszeń zarejestrowanych";
		echo "</th>";
		echo "<th class=center>";
		echo "Ilość wykonanych kroków";
		echo "</th>";
		echo "<th class=center>";
		echo "Łączny czas poświęcony na wykonanie kroków";
		echo "</th>";
		echo "<th class=center>";
		echo "Łączny czas poświęcony na przejazdy";
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
	
	// tworzenie tabeli tycznasowej dla raportów
	$sql_f = "SELECT filia_leader FROM $dbname.serwis_filie WHERE filia_leader=$es_nr LIMIT 1";
	$result_f = mysql_query($sql_f, $conn_hd) or die($k_b);
	$countf = mysql_num_rows($result_f);
	
	$czyistnieje = mysql_query("SELECT * FROM $dbname_hd.hd_temp_raport_zbiorczy_$es_nr LIMIT 1");
	
	if ($czyistnieje) {	
		if (($countf>0) || ($is_dyrektor)) { 
			$sql_report = "TRUNCATE TABLE $dbname_hd.hd_temp_raport_zbiorczy_$es_nr";	$result_report = mysql_query($sql_report, $conn_hd) or die($k_b);		
			
		}
	} else { 
		if (($countf>0) || ($is_dyrektor)) { 
			$sql_report = "CREATE TABLE $dbname_hd.hd_temp_raport_zbiorczy_$es_nr (`pole1` int(10) NOT NULL,`pole2` varchar(100) collate utf8_polish_ci NOT NULL,`pole3` varchar(100) collate utf8_polish_ci NOT NULL,`pole4` varchar(100) collate utf8_polish_ci NOT NULL,`pole5` varchar(100) collate utf8_polish_ci NOT NULL,`pole6` varchar(100) collate utf8_polish_ci NOT NULL,`pole7` text collate utf8_polish_ci NOT NULL,`pole8` text collate utf8_polish_ci NOT NULL,`pole9` varchar(100) collate utf8_polish_ci NOT NULL,`pole10` varchar(100) collate utf8_polish_ci NOT NULL,`pole11` varchar(100) collate utf8_polish_ci NOT NULL,`podsuma1` varchar(100) collate utf8_polish_ci NOT NULL,`podsuma2` varchar(100) collate utf8_polish_ci NOT NULL,`podsuma3` varchar(100) collate utf8_polish_ci NOT NULL,`podsuma4` varchar(100) collate utf8_polish_ci NOT NULL,`podsuma5` varchar(100) collate utf8_polish_ci NOT NULL,`podsuma6` varchar(100) collate utf8_polish_ci NOT NULL,`podsuma7` varchar(100) collate utf8_polish_ci NOT NULL,`podsuma8` varchar(100) collate utf8_polish_ci NOT NULL,`podsuma9` varchar(100) collate utf8_polish_ci NOT NULL,`podsuma10` varchar(100) collate utf8_polish_ci NOT NULL,`podsuma11` varchar(100) collate utf8_polish_ci NOT NULL,`podsuma12` varchar(100) collate utf8_polish_ci NOT NULL,`podsuma13` varchar(100) collate utf8_polish_ci NOT NULL,`podsuma14` varchar(100) collate utf8_polish_ci NOT NULL,`podsuma15` varchar(100) collate utf8_polish_ci NOT NULL,`podsuma16` varchar(100) collate utf8_polish_ci NOT NULL,`podsuma17` varchar(100) collate utf8_polish_ci NOT NULL,`podsuma18` varchar(100) collate utf8_polish_ci NOT NULL,`podsuma19` varchar(100) collate utf8_polish_ci NOT NULL,`podsuma20` varchar(100) collate utf8_polish_ci NOT NULL,`podsuma21` varchar(100) collate utf8_polish_ci NOT NULL,`filia` varchar(100) collate utf8_polish_ci NOT NULL,`filia_nazwa` varchar(100) collate utf8_polish_ci NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci; ALTER TABLE $dbname_hd.hd_temp_raport_zbiorczy_$es_nr ADD INDEX ( `filia` );";		
			$result_report = mysql_query($sql_report, $conn_hd) or die($k_b);	
		}
	}
	//echo ">>>".$sql_report;
	
	$i = 1;
	$j = 1;
	
	$zapyt_pracownicy = "SELECT user_first_name, user_last_name, belongs_to FROM $dbname.serwis_uzytkownicy WHERE (user_locked=0) ";	
	if ($_REQUEST[zakres]!='all') $zapyt_pracownicy .= " and (belongs_to=$_REQUEST[zakres]) "; 
	$zapyt_pracownicy .= " ORDER BY belongs_to ASC, user_last_name ASC";
	$result6 = mysql_query($zapyt_pracownicy) or die($k_b);
	
	$sum1 = 0;
	$sum2 = 0;
	$sum3 = 0;
	$sum4 = 0;

	$separate = false;
	$old_check = 0;
	$podnr = 1;
	
	while (list($temp_imie,$temp_nazwisko,$temp_bt) = mysql_fetch_array($result6)) {
		
		if ($_REQUEST[zakres]=='all') {
			if ($temp_bt!=$old_check) {
			
			
if ($temp_bt!=$old_check) {	
	
	if (($_REQUEST[kat]=='all') && ($_REQUEST[AddCategories]=='on') && ($old_check!=0)) {		

		tbl_tr_highlight_1row_kolor($j,'#FFFF00');
		$j++;
		
		echo "<td class=right colspan=2><b>Łącznie dla filli/oddziału ".$temp_nazwa_filii.":</b></td>";
		echo "<td class=center><b>$sum1</b></td>";
		echo "<td class=center><b>$sum2</b></td>";
		echo "<td class=center><b>$sum3 minut | ".minutes2hours($sum3,'short')."</b></td>";	
		echo "<td class=center><b>$sum4 minut | ".minutes2hours($sum4,'short')."</b></td>";	

		$result6a = mysql_query("SELECT SUM(podsuma1) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$old_check)", $conn) or die($k_b);
		list($ps1a) = mysql_fetch_array($result6a);
		
		$result6a = mysql_query("SELECT SUM(podsuma2) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$old_check)", $conn) or die($k_b);
		list($ps2a) = mysql_fetch_array($result6a);
		
		$result6a = mysql_query("SELECT SUM(podsuma3) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$old_check)", $conn) or die($k_b);
		list($ps3a) = mysql_fetch_array($result6a);
		
		$result6a = mysql_query("SELECT SUM(podsuma4) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$old_check)", $conn) or die($k_b);
		list($ps4a) = mysql_fetch_array($result6a);
		
		$result6a = mysql_query("SELECT SUM(podsuma5) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$old_check)", $conn) or die($k_b);
		list($ps5a) = mysql_fetch_array($result6a);
		
		$result6a = mysql_query("SELECT SUM(podsuma6) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$old_check)", $conn) or die($k_b);
		list($ps6a) = mysql_fetch_array($result6a);
		
		$result6a = mysql_query("SELECT SUM(podsuma7) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$old_check)", $conn) or die($k_b);
		list($ps7a) = mysql_fetch_array($result6a);
		
		$result6a = mysql_query("SELECT SUM(podsuma8) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$old_check)", $conn) or die($k_b);
		list($ps8a) = mysql_fetch_array($result6a);
		
		$result6a = mysql_query("SELECT SUM(podsuma9) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$old_check)", $conn) or die($k_b);
		list($ps9a) = mysql_fetch_array($result6a);
		
		$result6a = mysql_query("SELECT SUM(podsuma10) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$old_check)", $conn) or die($k_b);
		list($ps10a) = mysql_fetch_array($result6a);
		
		$result6a = mysql_query("SELECT SUM(podsuma11) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$old_check)", $conn) or die($k_b);
		list($ps11a) = mysql_fetch_array($result6a);
		
		$result6a = mysql_query("SELECT SUM(podsuma12) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$old_check)", $conn) or die($k_b);
		list($ps12a) = mysql_fetch_array($result6a);
		
		$result6a = mysql_query("SELECT SUM(podsuma13) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$old_check)", $conn) or die($k_b);
		list($ps13a) = mysql_fetch_array($result6a);
		
		$result6a = mysql_query("SELECT SUM(podsuma14) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$old_check)", $conn) or die($k_b);
		list($ps14a) = mysql_fetch_array($result6a);
		
		$result6a = mysql_query("SELECT SUM(podsuma15) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$old_check)", $conn) or die($k_b);
		list($ps15a) = mysql_fetch_array($result6a);
		
		$result6a = mysql_query("SELECT SUM(podsuma16) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$old_check)", $conn) or die($k_b);
		list($ps16a) = mysql_fetch_array($result6a);
		
		$result6a = mysql_query("SELECT SUM(podsuma17) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$old_check)", $conn) or die($k_b);
		list($ps17a) = mysql_fetch_array($result6a);
		
		$result6a = mysql_query("SELECT SUM(podsuma18) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$old_check)", $conn) or die($k_b);
		list($ps18a) = mysql_fetch_array($result6a);

		$result6a = mysql_query("SELECT SUM(podsuma19) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$old_check)", $conn) or die($k_b);
		list($ps19a) = mysql_fetch_array($result6a);
		$result6a = mysql_query("SELECT SUM(podsuma20) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$old_check)", $conn) or die($k_b);
		list($ps20a) = mysql_fetch_array($result6a);
		$result6a = mysql_query("SELECT SUM(podsuma21) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$old_check)", $conn) or die($k_b);
		list($ps21a) = mysql_fetch_array($result6a);
		
		echo "<td class=center>"; if ($ps1a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps1a>0)) echo "<font color=green>"; echo "<b>$ps1a</b>";  if (($raport_zbiorczy_koloruj==1) && ($ps1a>0)) echo "</font>"; if ($ps1a==0) echo "</font>"; echo "</td>";
		echo "<td class=center>"; if ($ps2a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps2a>0)) echo "<font color=brown>"; echo "<b>$ps2a</b>";  if (($raport_zbiorczy_koloruj==1) && ($ps2a>0)) echo "</font>"; if ($ps2a==0) echo "</font>"; echo "</td>";
		echo "<td class=center>"; if ($ps3a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps3a>0)) echo "<font color=red>"; echo "<b>$ps3a</b>";  if (($raport_zbiorczy_koloruj==1) && ($ps3a>0)) echo "</font>"; if ($ps3a==0) echo "</font>"; echo "</td>";
		
		echo "<td class=center>"; if ($ps4a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps4a>0)) echo "<font color=green>"; echo "<b>$ps4a</b>";  if (($raport_zbiorczy_koloruj==1) && ($ps4a>0)) echo "</font>"; if ($ps4a==0) echo "</font>"; echo "</td>";
		echo "<td class=center>"; if ($ps5a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps5a>0)) echo "<font color=brown>"; echo "<b>$ps5a</b>";  if (($raport_zbiorczy_koloruj==1) && ($ps5a>0)) echo "</font>"; if ($ps5a==0) echo "</font>"; echo "</td>";
		echo "<td class=center>"; if ($ps6a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps6a>0)) echo "<font color=red>"; echo "<b>$ps6a</b>";  if (($raport_zbiorczy_koloruj==1) && ($ps6a>0)) echo "</font>"; if ($ps6a==0) echo "</font>"; echo "</td>";
		
		echo "<td class=center>"; if ($ps7a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps7a>0)) echo "<font color=green>"; echo "<b>$ps7a</b>";  if (($raport_zbiorczy_koloruj==1) && ($ps7a>0)) echo "</font>"; if ($ps7a==0) echo "</font>"; echo "</td>";
		echo "<td class=center>"; if ($ps8a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps8a>0)) echo "<font color=brown>"; echo "<b>$ps8a</b>";  if (($raport_zbiorczy_koloruj==1) && ($ps7a>0)) echo "</font>"; if ($ps8a==0) echo "</font>"; echo "</td>";
		echo "<td class=center>"; if ($ps9a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps9a>0)) echo "<font color=red>"; echo "<b>$ps9a</b>";  if (($raport_zbiorczy_koloruj==1) && ($ps9a>0)) echo "</font>"; if ($ps9a==0) echo "</font>"; echo "</td>";

		echo "<td class=center>"; if ($ps19a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps19a>0)) echo "<font color=green>"; echo "<b>$ps19a</b>";if (($raport_zbiorczy_koloruj==1) && ($ps19a>0)) echo "</font>"; if ($ps19a==0) echo "</font>"; echo "</td>";
		echo "<td class=center>"; if ($ps20a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps20a>0)) echo "<font color=brown>"; echo "<b>$ps20a</b>";if (($raport_zbiorczy_koloruj==1) && ($ps20a>0)) echo "</font>";  if ($ps20a==0) echo "</font>"; echo "</td>";
		echo "<td class=center>"; if ($ps21a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps21a>0)) echo "<font color=red>"; echo "<b>$ps21a</b>"; if (($raport_zbiorczy_koloruj==1) && ($ps21a>0)) echo "</font>"; if ($ps21a==0) echo "</font>"; echo "</td>";
		
		echo "<td class=center>"; if ($ps10a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps10a>0)) echo "<font color=green>"; echo "<b>$ps10a</b>";if (($raport_zbiorczy_koloruj==1) && ($ps10a>0)) echo "</font>"; if ($ps10a==0) echo "</font>"; echo "</td>";
		echo "<td class=center>"; if ($ps11a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps11a>0)) echo "<font color=brown>"; echo "<b>$ps11a</b>";if (($raport_zbiorczy_koloruj==1) && ($ps11a>0)) echo "</font>";  if ($ps11a==0) echo "</font>"; echo "</td>";
		echo "<td class=center>"; if ($ps12a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps12a>0)) echo "<font color=red>"; echo "<b>$ps12a</b>"; if (($raport_zbiorczy_koloruj==1) && ($ps12a>0)) echo "</font>"; if ($ps12a==0) echo "</font>"; echo "</td>";
		
		echo "<td class=center>"; if ($ps13a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps13a>0)) echo "<font color=green>"; echo "<b>$ps13a</b>";if (($raport_zbiorczy_koloruj==1) && ($ps13a>0)) echo "</font>"; if ($ps13a==0) echo "</font>"; echo "</td>";
		echo "<td class=center>"; if ($ps14a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps14a>0)) echo "<font color=brown>"; echo "<b>$ps14a</b>";if (($raport_zbiorczy_koloruj==1) && ($ps14a>0)) echo "</font>";  if ($ps14a==0) echo "</font>"; echo "</td>";
		echo "<td class=center>"; if ($ps15a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps15a>0)) echo "<font color=red>"; echo "<b>$ps15a</b>"; if (($raport_zbiorczy_koloruj==1) && ($ps15a>0)) echo "</font>"; if ($ps15a==0) echo "</font>"; echo "</td>";
		
		echo "<td class=center>"; if ($ps16a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps16a>0)) echo "<font color=green>"; echo "<b>$ps16a</b>";if (($raport_zbiorczy_koloruj==1) && ($ps16a>0)) echo "</font>"; if ($ps16a==0) echo "</font>"; echo "</td>";
		echo "<td class=center>"; if ($ps17a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps17a>0)) echo "<font color=brown>"; echo "<b>$ps17a</b>";if (($raport_zbiorczy_koloruj==1) && ($ps17a>0)) echo "</font>";  if ($ps17a==0) echo "</font>"; echo "</td>";
		echo "<td class=center>"; if ($ps18a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps18a>0)) echo "<font color=red>"; echo "<b>$ps18a</b>"; if (($raport_zbiorczy_koloruj==1) && ($ps18a>0)) echo "</font>"; if ($ps18a==0) echo "</font>"; echo "</td>";
	
	}		
		
}
			
				if (($_REQUEST[kat]=='all') && ($_REQUEST[AddCategories]=='on')) {
					echo "<tr><td colspan=23><hr /></td></tr>";
				} else {
					echo "<tr><td colspan=5><hr /></td></tr>";
				}
				
				$result611 = mysql_query("SELECT filia_nazwa FROM $dbname.serwis_filie WHERE (filia_id=$temp_bt) LIMIT 1", $conn) or die($k_b);				
				list($temp_nazwa_filii) = mysql_fetch_array($result611);
				
				if (($_REQUEST[kat]=='all') && ($_REQUEST[AddCategories]=='on')) {
					echo "<tr><td colspan=23 class=center><b>$temp_nazwa_filii</b></td></tr>";
				} else {
					echo "<tr><td colspan=5 class=center><b>$temp_nazwa_filii</b></td></tr>";
				}
				
				if (($_REQUEST[kat]=='all') && ($_REQUEST[AddCategories]=='on')) {
					echo "<tr><td colspan=23><hr /></td></tr>";
				} else {
					echo "<tr><td colspan=5><hr /></td></tr>";
				}
				
				$old_check = $temp_bt;
				$i = 1;
				
				$sum1 = 0;
				$sum2 = 0;
				$sum3 = 0;
				$sum4 = 0;
				
			}
		}
		
		tbl_tr_highlight($j);
		echo "<td class=center>$i</td>";
		
		echo "<td>$temp_imie $temp_nazwisko</td>";
		$i_i_n = $temp_imie." ".$temp_nazwisko;
		echo "<td class=center>";
			$il_zgl_zarejestrowanych=0;
			## LICZBA ZAREJESTROWANYCH ZGŁOSZEŃ - POCZĄTEK ##
			$zapyt = "SELECT COUNT(zgl_nr) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_data BETWEEN '$data_od' and '$data_do') and (zgl_widoczne=1) and (zgl_osoba_rejestrujaca='$temp_imie $temp_nazwisko') ";	
			if ($_REQUEST[zakres]!='all') $zapyt .= " and (belongs_to=$_REQUEST[zakres]) ";
			if ($_REQUEST[kat]!='all') $zapyt .= " and (zgl_kategoria=$_REQUEST[kat]) ";	
			$result6a = mysql_query($zapyt) or die($k_b);		
			list($il_zgl_zarejestrowanych)=mysql_fetch_array($result6a);		
			## LICZBA ZAREJESTROWANYCH ZGŁOSZEŃ - KONIEC ##
			if ($il_zgl_zarejestrowanych=='0') { echo "<font color=grey>0</font>"; } else { 
				echo "<a class=normalfont href=# title='Pokaż raport szczegółowy' onClick=\"newWindow_r(".$_COOKIE[max_x].",".$_COOKIE[max_y].",'hd_g_raport_okresowy.php?okres_od=$data_od&okres_do=$data_do&tzgldata=data_utworzenia&tuser=".urlencode($i_i_n)."&tstatus=0&submit=Generuj&readonly=1&fromraport=1'); return false; \">";	
				echo "$il_zgl_zarejestrowanych"; 
				echo "</a>";
			}
			$sum1+=$il_zgl_zarejestrowanych;
		echo "</td>";
	//	ob_flush();	flush();
		
		echo "<td class=center>";
			$il_krokow_obsluzonych=0;
			## LICZBA WPISANYCH KROKÓW - POCZĄTEK ##
			$zapyt = "SELECT COUNT(zgl_szcz_id) FROM $dbname_hd.hd_zgloszenie_szcz,$dbname_hd.hd_zgloszenie WHERE (zgl_id=zgl_szcz_zgl_id) and (zgl_szcz_czas_rozpoczecia_kroku BETWEEN '".$data_od." 00:00:00' and '".$data_do." 23:59:59') and (zgl_szcz_widoczne=1) and ((zgl_szcz_osoba_wykonujaca_krok='$temp_imie $temp_nazwisko') or (zgl_szcz_dodatkowe_osoby_wykonujace_krok LIKE '%".$temp_imie." ".$temp_nazwisko."%'))";	
			
			if ($_REQUEST[zakres]!='all') $zapyt .= " and (hd_zgloszenie_szcz.belongs_to=$_REQUEST[zakres]) ";
			if ($_REQUEST[kat]!='all') $zapyt .= " and (zgl_kategoria=$_REQUEST[kat]) ";
			
			$result6a = mysql_query($zapyt) or die($k_b);		
			list($il_krokow_obsluzonych)=mysql_fetch_array($result6a);					
			## LICZBA WPISANYCH KROKÓW - KONIEC ##
		
			if ($il_krokow_obsluzonych=='0') { echo "<font color=grey>0</font>"; } else { 
				echo "<a class=normalfont href=# title='Pokaż raport szczegółowy' onClick=\"newWindow_r(".$_COOKIE[max_x].",".$_COOKIE[max_y].",'hd_g_raport_dzienny_dla_pracownika.php?okres_od=$data_od&okres_do=$data_do&tzgldata=data_utworzenia&tuser=".urlencode($i_i_n)."&tstatus=0&submit=Generuj&readonly=1&kategoria=&fromraport=1'); return false; \">";	
				echo "$il_krokow_obsluzonych"; 
				echo "</a>";
			}
			
			$sum2+=$il_krokow_obsluzonych;			
		echo "</td>";
	//	ob_flush();	flush();
		
		echo "<td class=center>";	
			$laczny_czas_wykonywania = 1;
			## ŁACZNY CZAS POŚWIĘCONY NA OBSŁUGĘ ZGŁOSZEŃ - POCZĄTEK ##
			$zapyt = "SELECT SUM(zgl_szcz_czas_wykonywania) FROM $dbname_hd.hd_zgloszenie_szcz,$dbname_hd.hd_zgloszenie WHERE (zgl_id=zgl_szcz_zgl_id) and (zgl_szcz_czas_rozpoczecia_kroku BETWEEN '".$data_od." 00:00:00' and '".$data_do." 23:59:59') and (zgl_szcz_widoczne=1) and ((zgl_szcz_osoba_wykonujaca_krok='$temp_imie $temp_nazwisko') or (zgl_szcz_dodatkowe_osoby_wykonujace_krok LIKE '%".$temp_imie." ".$temp_nazwisko."%')) ";	
			
			if ($_REQUEST[zakres]!='all') $zapyt .= " and (hd_zgloszenie_szcz.belongs_to=$_REQUEST[zakres]) ";
			if ($_REQUEST[kat]!='all') $zapyt .= " and (zgl_kategoria=$_REQUEST[kat]) ";
			
			$result6a = mysql_query($zapyt) or die($k_b);		
			list($laczny_czas_wykonywania)=mysql_fetch_array($result6a);					
			## ŁACZNY CZAS POŚWIĘCONY NA OBSŁUGĘ ZGŁOSZEŃ - KONIEC ##
			
			if ($laczny_czas_wykonywania=='') { echo "<font color=grey>0</font>"; $laczny_czas_wykonywania = 0; } else { echo "$laczny_czas_wykonywania minut | ".minutes2hours($laczny_czas_wykonywania,'short').""; }
			
			$sum3+=$laczny_czas_wykonywania;			
		echo "</td>";

		echo "<td class=center>";	
			$laczny_czas_przejazdu = 1;
			## ŁACZNY CZAS POŚWIĘCONY NA PRZEJAZDY - POCZĄTEK ##
			$zapyt = "SELECT SUM(zgl_szcz_czas_trwania_wyjadu) FROM $dbname_hd.hd_zgloszenie_szcz,$dbname_hd.hd_zgloszenie WHERE (zgl_id=zgl_szcz_zgl_id) and (zgl_szcz_czas_rozpoczecia_kroku BETWEEN '".$data_od." 00:00:00' and '".$data_do." 23:59:59') and (zgl_szcz_widoczne=1) and ((zgl_szcz_osoba_wykonujaca_krok='$temp_imie $temp_nazwisko') or (zgl_szcz_dodatkowe_osoby_wykonujace_krok LIKE '%".$temp_imie." ".$temp_nazwisko."%')) ";	
			
			if ($_REQUEST[zakres]!='all') $zapyt .= " and (hd_zgloszenie_szcz.belongs_to=$_REQUEST[zakres]) ";
			if ($_REQUEST[kat]!='all') $zapyt .= " and (zgl_kategoria=$_REQUEST[kat]) ";
			
			$result6a = mysql_query($zapyt) or die($k_b);		
			list($laczny_czas_przejazdu)=mysql_fetch_array($result6a);					
			## ŁACZNY CZAS POŚWIĘCONY NA PRZEJAZDY - KONIEC ##
			
			if ($laczny_czas_przejazdu=='') { echo "<font color=grey>0</font>"; $laczny_czas_przejazdu = 0; } else { echo "$laczny_czas_przejazdu minut | ".minutes2hours($laczny_czas_przejazdu,'short').""; }
			
			$sum4+=$laczny_czas_przejazdu;			
		echo "</td>";

		if (($_REQUEST[kat]=='all') && ($_REQUEST[AddCategories]=='on')) {		
			
			$result61 = mysql_query("SELECT hd_kategoria_nr, hd_kategoria_opis FROM $dbname_hd.hd_kategoria WHERE (hd_kategoria_wlaczona=1) ORDER BY hd_kategoria_display_order ASC", $conn) or die($k_b);
			while (list($kat_nr,$kat_opis) = mysql_fetch_array($result61)) {
				
				// ilość zgłoszeń w danej kategorii
				$zapyt = "SELECT COUNT(zgl_nr) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_data BETWEEN '$data_od' and '$data_do') and (zgl_widoczne=1) and (zgl_osoba_rejestrujaca='$temp_imie $temp_nazwisko') ";	
				if ($_REQUEST[zakres]!='all') $zapyt .= " and (belongs_to=$_REQUEST[zakres]) ";
				$zapyt .= " and (zgl_kategoria=$kat_nr) ";	
				$result6a = mysql_query($zapyt) or die($k_b);		
				list($ilosc_temp)=mysql_fetch_array($result6a);
			
				// ilość kroków w danej kategorii
				$zapyt = "SELECT COUNT(zgl_szcz_id) FROM $dbname_hd.hd_zgloszenie_szcz,$dbname_hd.hd_zgloszenie WHERE (zgl_id=zgl_szcz_zgl_id) and (zgl_szcz_czas_rozpoczecia_kroku BETWEEN '".$data_od." 00:00:00' and '".$data_do." 23:59:59') and (zgl_szcz_widoczne=1) and ((zgl_szcz_osoba_wykonujaca_krok='$temp_imie $temp_nazwisko') or (zgl_szcz_dodatkowe_osoby_wykonujace_krok LIKE '%".$temp_imie." ".$temp_nazwisko."%')) ";	
			
				if ($_REQUEST[zakres]!='all') $zapyt .= " and (hd_zgloszenie_szcz.belongs_to=$_REQUEST[zakres]) ";
				$zapyt .= " and (zgl_kategoria=$kat_nr) ";
				
				$result6a = mysql_query($zapyt) or die($k_b);		
				list($ilosc_krokow_temp)=mysql_fetch_array($result6a);		
				
				// czasy dla poszczególnych kategorii
				$zapyt = "SELECT SUM(zgl_szcz_czas_wykonywania) FROM $dbname_hd.hd_zgloszenie_szcz,$dbname_hd.hd_zgloszenie WHERE (zgl_id=zgl_szcz_zgl_id) and (zgl_szcz_czas_rozpoczecia_kroku BETWEEN '".$data_od." 00:00:00' and '".$data_do." 23:59:59') and (zgl_szcz_widoczne=1) and ((zgl_szcz_osoba_wykonujaca_krok='$temp_imie $temp_nazwisko') or (zgl_szcz_dodatkowe_osoby_wykonujace_krok LIKE '%".$temp_imie." ".$temp_nazwisko."%')) ";	
			
				if ($_REQUEST[zakres]!='all') $zapyt .= " and (hd_zgloszenie_szcz.belongs_to=$_REQUEST[zakres]) ";
				$zapyt .= " and (zgl_kategoria=$kat_nr) ";
			
				$result6a = mysql_query($zapyt) or die($k_b);		
				list($czas_temp)=mysql_fetch_array($result6a);
			
				if ($ilosc_temp>0) { } else { $ilosc_temp = 0; }
				if ($ilosc_krokow_temp>0) { } else { $ilosc_krokow_temp = 0; }
				if ($czas_temp>0) { } else { $czas_temp = 0; }
				
				echo "<td class=center>";
				if ($ilosc_temp==0) echo "<font color=grey>";
				
				if ($ilosc_temp>0) {
					echo "<a class=normalfont href=# title='Pokaż raport szczegółowy' onClick=\"newWindow_r(".$_COOKIE[max_x].",".$_COOKIE[max_y].",'hd_g_raport_okresowy.php?okres_od=$data_od&okres_do=$data_do&tzgldata=data_utworzenia&tuser=".urlencode($i_i_n)."&tstatus=0&submit=Generuj&readonly=1&kategoria=$kat_nr&fromraport=1'); return false; \">";	
				}
				if (($raport_zbiorczy_koloruj==1) && ($ilosc_temp>0)) echo "<font color=green>";
				echo "$ilosc_temp"; 
				if (($raport_zbiorczy_koloruj==1) && ($ilosc_temp>0)) echo "</font>";
				
				if ($ilosc_temp>0) {
					echo "</a>";
				}
				
				if ($ilosc_temp==0) echo "</font>";
				echo "</td>";
				
				echo "<td class=center>";
				if ($ilosc_krokow_temp==0) echo "<font color=grey>";
							
				if ($ilosc_krokow_temp>0) {
					echo "<a class=normalfont href=# title='Pokaż raport szczegółowy' onClick=\"newWindow_r(".$_COOKIE[max_x].",".$_COOKIE[max_y].",'hd_g_raport_dzienny_dla_pracownika.php?okres_od=$data_od&okres_do=$data_do&tzgldata=data_utworzenia&tuser=".urlencode($i_i_n)."&tstatus=0&submit=Generuj&readonly=1&kategoria=$kat_nr&fromraport=1'); return false; \">";	
				}
				if (($raport_zbiorczy_koloruj==1) && ($ilosc_krokow_temp>0)) echo "<font color=brown>";
				echo $ilosc_krokow_temp;
				if (($raport_zbiorczy_koloruj==1) && ($ilosc_krokow_temp>0)) echo "</font>";
				
				if ($ilosc_krokow_temp>0) {
					echo "</a>";
				}
				
				
				if ($ilosc_krokow_temp==0) echo "</font>";
				echo "</td>";
				
				echo "<td class=center>";
				if ($czas_temp==0) echo "<font color=grey>";

				if ($czas_temp>0) {
					echo "<a class=normalfont href=# title='Czas w przeliczeniu na godziny: ".minutes2hours($czas_temp,'short').". Pokaż raport szczegółowy' onClick=\"newWindow_r(".$_COOKIE[max_x].",".$_COOKIE[max_y].",'hd_g_raport_dzienny_dla_pracownika.php?okres_od=$data_od&okres_do=$data_do&tzgldata=data_utworzenia&tuser=".urlencode($i_i_n)."&tstatus=0&submit=Generuj&readonly=1&kategoria=$kat_nr&fromraport=1'); return false; \">";	
				}
				
				if (($raport_zbiorczy_koloruj==1) && ($czas_temp>0)) echo "<font color=red>";
				echo $czas_temp;
				if (($raport_zbiorczy_koloruj==1) && ($czas_temp>0)) echo "</font>";
				if ($ilosc_krokow_temp>0) { echo "</a>"; }
				
				if ($czas_temp==0) echo "</font>";
				echo "</td>";

				if ($kat_nr==1) {
					$ps1a = $ilosc_temp;
					$ps1b = $ilosc_krokow_temp;
					$ps1c = $czas_temp;
				}
				if ($kat_nr==2) {
					$ps2a = $ilosc_temp;
					$ps2b = $ilosc_krokow_temp;
					$ps2c = $czas_temp;
				}
				if ($kat_nr==6) {
					$ps3a = $ilosc_temp;
					$ps3b = $ilosc_krokow_temp;
					$ps3c = $czas_temp;
				}					
				if ($kat_nr==3) {
					$ps4a = $ilosc_temp;
					$ps4b = $ilosc_krokow_temp;
					$ps4c = $czas_temp;
				}
				if ($kat_nr==4) {
					$ps5a = $ilosc_temp;
					$ps5b = $ilosc_krokow_temp;
					$ps5c = $czas_temp;
				}
				if ($kat_nr==5) {
					$ps6a = $ilosc_temp;
					$ps6b = $ilosc_krokow_temp;
					$ps6c = $czas_temp;
				}
				if ($kat_nr==7) {
					$ps7a = $ilosc_temp;
					$ps7b = $ilosc_krokow_temp;
					$ps7c = $czas_temp;
				}
			}
			
		} else {
			$ps1a='0';
			$ps1b='0';
			$ps1c='0';
			$ps2a='0';
			$ps2b='0';
			$ps2c='0';
			$ps3a='0';
			$ps3b='0';
			$ps3c='0';
			$ps4a='0';
			$ps4b='0';
			$ps4c='0';
			$ps5a='0';
			$ps5b='0';
			$ps5c='0';
			$ps6a='0';	
			$ps6b='0';
			$ps6c='0';
			$ps7a='0';	
			$ps7b='0';
			$ps7c='0';			
		}
		
		list($filia)=mysql_fetch_array(mysql_query("SELECT filia_nazwa FROM $dbname.serwis_filie WHERE filia_id=$temp_bt LIMIT 1", $conn));
		
		$dodaj_osobe = mysql_query("INSERT INTO $dbname_hd.hd_temp_raport_zbiorczy_$es_nr VALUES ('','$temp_imie $temp_nazwisko','$il_zgl_zarejestrowanych','$il_krokow_obsluzonych','$laczny_czas_wykonywania','".minutes2hours($laczny_czas_wykonywania,'short')."','$laczny_czas_przejazdu','".minutes2hours($laczny_czas_przejazdu,'short')."','-','-','-',$ps1a,$ps1b,$ps1c,$ps2a,$ps2b,$ps2c,$ps3a,$ps3b,$ps3c,$ps4a,$ps4b,$ps4c,$ps5a,$ps5b,$ps5c,$ps6a,$ps6b,$ps6c,$ps7a,$ps7b,$ps7c,$temp_bt,'$filia')");

		//echo "INSERT INTO $dbname_hd.hd_temp_raport_zbiorczy_$es_nr VALUES ('','$temp_imie $temp_nazwisko','$il_zgl_zarejestrowanych','$il_krokow_obsluzonych','$laczny_czas_wykonywania','".minutes2hours($laczny_czas_wykonywania,'short')."','$laczny_czas_przejazdu','".minutes2hours($laczny_czas_przejazdu,'short')."','-','-','-',$ps1a,$ps1b,$ps1c,$ps2a,$ps2b,$ps2c,$ps3a,$ps3b,$ps3c,$ps4a,$ps4b,$ps4c,$ps5a,$ps5b,$ps5c,$ps6a,$ps6b,$ps6c,$ps7a,$ps7b,$ps7c,$temp_bt,'$filia')";
		
		//echo "<hr />";
		//echo "INSERT INTO $dbname_hd.hd_temp_raport_zbiorczy_$es_nr VALUES ('','$temp_imie $temp_nazwisko','$il_zgl_zarejestrowanych','$il_krokow_obsluzonych','$laczny_czas_wykonywania','".minutes2hours($laczny_czas_wykonywania,'short')."','-','-','-','-','-',$ps1a,$ps1b,$ps1c,$ps2a,$ps2b,$ps2c,$ps3a,$ps3b,$ps3c,$ps4a,$ps4b,$ps4c,$ps5a,$ps5b,$ps5c,$ps6a,$ps6b,$ps6c,$temp_bt,'$filia')<br />";
		//echo "<hr />";
		
		ob_flush();	flush();
		echo "</tr>";
		
		$podnr = $podnr + 1;
		$i++;
		$j++;
	}

	if (($_REQUEST[kat]=='all') && ($_REQUEST[AddCategories]=='on') && ($old_check!=0)) {		

		tbl_tr_highlight_1row_kolor($j,'#FFFF00');
		$j++;
		
		echo "<td class=right colspan=2><b>Łącznie dla filli/oddziału ".$temp_nazwa_filii.":</b></td>";
		echo "<td class=center><b>$sum1</b></td>";
		echo "<td class=center><b>$sum2</b></td>";
		echo "<td class=center><b>$sum3 minut | ".minutes2hours($sum3,'short')."</b></td>";	
		echo "<td class=center><b>$sum4 minut | ".minutes2hours($sum4,'short')."</b></td>";	

		$result6a = mysql_query("SELECT SUM(podsuma1) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$old_check)", $conn) or die($k_b);
		list($ps1a) = mysql_fetch_array($result6a);
		
		$result6a = mysql_query("SELECT SUM(podsuma2) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$old_check)", $conn) or die($k_b);
		list($ps2a) = mysql_fetch_array($result6a);
		
		$result6a = mysql_query("SELECT SUM(podsuma3) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$old_check)", $conn) or die($k_b);
		list($ps3a) = mysql_fetch_array($result6a);
		
		$result6a = mysql_query("SELECT SUM(podsuma4) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$old_check)", $conn) or die($k_b);
		list($ps4a) = mysql_fetch_array($result6a);
		
		$result6a = mysql_query("SELECT SUM(podsuma5) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$old_check)", $conn) or die($k_b);
		list($ps5a) = mysql_fetch_array($result6a);
		
		$result6a = mysql_query("SELECT SUM(podsuma6) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$old_check)", $conn) or die($k_b);
		list($ps6a) = mysql_fetch_array($result6a);
		
		$result6a = mysql_query("SELECT SUM(podsuma7) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$old_check)", $conn) or die($k_b);
		list($ps7a) = mysql_fetch_array($result6a);
		
		$result6a = mysql_query("SELECT SUM(podsuma8) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$old_check)", $conn) or die($k_b);
		list($ps8a) = mysql_fetch_array($result6a);
		
		$result6a = mysql_query("SELECT SUM(podsuma9) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$old_check)", $conn) or die($k_b);
		list($ps9a) = mysql_fetch_array($result6a);
		
		$result6a = mysql_query("SELECT SUM(podsuma10) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$old_check)", $conn) or die($k_b);
		list($ps10a) = mysql_fetch_array($result6a);
		
		$result6a = mysql_query("SELECT SUM(podsuma11) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$old_check)", $conn) or die($k_b);
		list($ps11a) = mysql_fetch_array($result6a);
		
		$result6a = mysql_query("SELECT SUM(podsuma12) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$old_check)", $conn) or die($k_b);
		list($ps12a) = mysql_fetch_array($result6a);
		
		$result6a = mysql_query("SELECT SUM(podsuma13) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$old_check)", $conn) or die($k_b);
		list($ps13a) = mysql_fetch_array($result6a);
		
		$result6a = mysql_query("SELECT SUM(podsuma14) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$old_check)", $conn) or die($k_b);
		list($ps14a) = mysql_fetch_array($result6a);
		
		$result6a = mysql_query("SELECT SUM(podsuma15) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$old_check)", $conn) or die($k_b);
		list($ps15a) = mysql_fetch_array($result6a);
		
		$result6a = mysql_query("SELECT SUM(podsuma16) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$old_check)", $conn) or die($k_b);
		list($ps16a) = mysql_fetch_array($result6a);
		
		$result6a = mysql_query("SELECT SUM(podsuma17) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$old_check)", $conn) or die($k_b);
		list($ps17a) = mysql_fetch_array($result6a);
		
		$result6a = mysql_query("SELECT SUM(podsuma18) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$old_check)", $conn) or die($k_b);
		list($ps18a) = mysql_fetch_array($result6a);

		$result6a = mysql_query("SELECT SUM(podsuma19) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$old_check)", $conn) or die($k_b);
		list($ps19a) = mysql_fetch_array($result6a);

		$result6a = mysql_query("SELECT SUM(podsuma20) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$old_check)", $conn) or die($k_b);
		list($ps20a) = mysql_fetch_array($result6a);
		
		$result6a = mysql_query("SELECT SUM(podsuma21) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$old_check)", $conn) or die($k_b);
		list($ps21a) = mysql_fetch_array($result6a);
		
		echo "<td class=center>"; if ($ps1a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps1a>0)) echo "<font color=green>"; echo "<b>$ps1a</b>";  if (($raport_zbiorczy_koloruj==1) && ($ps1a>0)) echo "</font>"; if ($ps1a==0) echo "</font>"; echo "</td>";
		echo "<td class=center>"; if ($ps2a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps2a>0)) echo "<font color=brown>"; echo "<b>$ps2a</b>";  if (($raport_zbiorczy_koloruj==1) && ($ps2a>0)) echo "</font>"; if ($ps2a==0) echo "</font>"; echo "</td>";
		echo "<td class=center>"; if ($ps3a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps3a>0)) echo "<font color=red>"; echo "<b>$ps3a</b>";  if (($raport_zbiorczy_koloruj==1) && ($ps3a>0)) echo "</font>"; if ($ps3a==0) echo "</font>"; echo "</td>";
		
		echo "<td class=center>"; if ($ps4a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps4a>0)) echo "<font color=green>"; echo "<b>$ps4a</b>";  if (($raport_zbiorczy_koloruj==1) && ($ps4a>0)) echo "</font>"; if ($ps4a==0) echo "</font>"; echo "</td>";
		echo "<td class=center>"; if ($ps5a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps5a>0)) echo "<font color=brown>"; echo "<b>$ps5a</b>";  if (($raport_zbiorczy_koloruj==1) && ($ps5a>0)) echo "</font>"; if ($ps5a==0) echo "</font>"; echo "</td>";
		echo "<td class=center>"; if ($ps6a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps6a>0)) echo "<font color=red>"; echo "<b>$ps6a</b>";  if (($raport_zbiorczy_koloruj==1) && ($ps6a>0)) echo "</font>"; if ($ps6a==0) echo "</font>"; echo "</td>";
		
		echo "<td class=center>"; if ($ps7a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps7a>0)) echo "<font color=green>"; echo "<b>$ps7a</b>";  if (($raport_zbiorczy_koloruj==1) && ($ps7a>0)) echo "</font>"; if ($ps7a==0) echo "</font>"; echo "</td>";
		echo "<td class=center>"; if ($ps8a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps8a>0)) echo "<font color=brown>"; echo "<b>$ps8a</b>";  if (($raport_zbiorczy_koloruj==1) && ($ps7a>0)) echo "</font>"; if ($ps8a==0) echo "</font>"; echo "</td>";
		echo "<td class=center>"; if ($ps9a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps9a>0)) echo "<font color=red>"; echo "<b>$ps9a</b>";  if (($raport_zbiorczy_koloruj==1) && ($ps9a>0)) echo "</font>"; if ($ps9a==0) echo "</font>"; echo "</td>";
		
		echo "<td class=center>"; if ($ps19a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps19a>0)) echo "<font color=green>"; echo "<b>$ps19a</b>";if (($raport_zbiorczy_koloruj==1) && ($ps19a>0)) echo "</font>"; if ($ps19a==0) echo "</font>"; echo "</td>";
		echo "<td class=center>"; if ($ps20a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps20a>0)) echo "<font color=brown>"; echo "<b>$ps20a</b>";if (($raport_zbiorczy_koloruj==1) && ($ps20a>0)) echo "</font>";  if ($ps20a==0) echo "</font>"; echo "</td>";
		echo "<td class=center>"; if ($ps21a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps21a>0)) echo "<font color=red>"; echo "<b>$ps21a</b>"; if (($raport_zbiorczy_koloruj==1) && ($ps21a>0)) echo "</font>"; if ($ps21a==0) echo "</font>"; echo "</td>";
	
		echo "<td class=center>"; if ($ps10a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps10a>0)) echo "<font color=green>"; echo "<b>$ps10a</b>";if (($raport_zbiorczy_koloruj==1) && ($ps10a>0)) echo "</font>"; if ($ps10a==0) echo "</font>"; echo "</td>";
		echo "<td class=center>"; if ($ps11a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps11a>0)) echo "<font color=brown>"; echo "<b>$ps11a</b>";if (($raport_zbiorczy_koloruj==1) && ($ps11a>0)) echo "</font>";  if ($ps11a==0) echo "</font>"; echo "</td>";
		echo "<td class=center>"; if ($ps12a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps12a>0)) echo "<font color=red>"; echo "<b>$ps12a</b>"; if (($raport_zbiorczy_koloruj==1) && ($ps12a>0)) echo "</font>"; if ($ps12a==0) echo "</font>"; echo "</td>";
		
		echo "<td class=center>"; if ($ps13a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps13a>0)) echo "<font color=green>"; echo "<b>$ps13a</b>";if (($raport_zbiorczy_koloruj==1) && ($ps13a>0)) echo "</font>"; if ($ps13a==0) echo "</font>"; echo "</td>";
		echo "<td class=center>"; if ($ps14a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps14a>0)) echo "<font color=brown>"; echo "<b>$ps14a</b>";if (($raport_zbiorczy_koloruj==1) && ($ps14a>0)) echo "</font>";  if ($ps14a==0) echo "</font>"; echo "</td>";
		echo "<td class=center>"; if ($ps15a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps15a>0)) echo "<font color=red>"; echo "<b>$ps15a</b>"; if (($raport_zbiorczy_koloruj==1) && ($ps15a>0)) echo "</font>"; if ($ps15a==0) echo "</font>"; echo "</td>";
		
		echo "<td class=center>"; if ($ps16a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps16a>0)) echo "<font color=green>"; echo "<b>$ps16a</b>";if (($raport_zbiorczy_koloruj==1) && ($ps16a>0)) echo "</font>"; if ($ps16a==0) echo "</font>"; echo "</td>";
		echo "<td class=center>"; if ($ps17a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps17a>0)) echo "<font color=brown>"; echo "<b>$ps17a</b>";if (($raport_zbiorczy_koloruj==1) && ($ps17a>0)) echo "</font>";  if ($ps17a==0) echo "</font>"; echo "</td>";
		echo "<td class=center>"; if ($ps18a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps18a>0)) echo "<font color=red>"; echo "<b>$ps18a</b>"; if (($raport_zbiorczy_koloruj==1) && ($ps18a>0)) echo "</font>"; if ($ps18a==0) echo "</font>"; echo "</td>";
	
	}		
		
	tbl_tr_highlight_1row_kolor($i,'#9CBCE2');
	//tbl_tr_highlight($i);
	echo "<td class=right colspan=2><b>Łącznie: </b></td>";
	
		if ($_REQUEST[zakres]=='all') {
			$result6a = mysql_query("SELECT SUM(pole3) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr."", $conn) or die($k_b);
		} else {
			$result6a = mysql_query("SELECT SUM(pole3) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$_REQUEST[zakres])", $conn) or die($k_b);
		}		
		list($sum1) = mysql_fetch_array($result6a);

		if ($_REQUEST[zakres]=='all') {
			$result6a = mysql_query("SELECT SUM(pole4) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr."", $conn) or die($k_b);
		} else {
			$result6a = mysql_query("SELECT SUM(pole4) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$_REQUEST[zakres])", $conn) or die($k_b);
		}
		list($sum2) = mysql_fetch_array($result6a);

		if ($_REQUEST[zakres]=='all') {
			$result6a = mysql_query("SELECT SUM(pole5) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr."", $conn) or die($k_b);
		} else {
			$result6a = mysql_query("SELECT SUM(pole5) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$_REQUEST[zakres])", $conn) or die($k_b);
		}
		list($sum3) = mysql_fetch_array($result6a);

		if ($_REQUEST[zakres]=='all') {
			$result6a = mysql_query("SELECT SUM(pole7) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr."", $conn) or die($k_b);
		} else {
			$result6a = mysql_query("SELECT SUM(pole7) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$_REQUEST[zakres])", $conn) or die($k_b);
		}
		list($sum4) = mysql_fetch_array($result6a);
		
	echo "<td class=center><b>$sum1</b></td>";
	echo "<td class=center><b>$sum2</b></td>";
	echo "<td class=center><b>$sum3 minut | ".minutes2hours($sum3,'short')."</b></td>";	
	echo "<td class=center><b>$sum4 minut | ".minutes2hours($sum4,'short')."</b></td>";
	
	if (($_REQUEST[kat]=='all') && ($_REQUEST[AddCategories]=='on')) {		
		
		if ($_REQUEST[zakres]=='all') {
			$result6a = mysql_query("SELECT SUM(podsuma1) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr."", $conn) or die($k_b);
		} else {
			$result6a = mysql_query("SELECT SUM(podsuma1) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$_REQUEST[zakres])", $conn) or die($k_b);
		}
		list($ps1a) = mysql_fetch_array($result6a);
		
		if ($_REQUEST[zakres]=='all') { 
			$result6a = mysql_query("SELECT SUM(podsuma2) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr."", $conn) or die($k_b);
		} else $result6a = mysql_query("SELECT SUM(podsuma2) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$_REQUEST[zakres])", $conn) or die($k_b);
		list($ps2a) = mysql_fetch_array($result6a);
		
		if ($_REQUEST[zakres]=='all') { 
			$result6a = mysql_query("SELECT SUM(podsuma3) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr."", $conn) or die($k_b);
		} else $result6a = mysql_query("SELECT SUM(podsuma3) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$_REQUEST[zakres])", $conn) or die($k_b);
		list($ps3a) = mysql_fetch_array($result6a);
		
		if ($_REQUEST[zakres]=='all') { 
			$result6a = mysql_query("SELECT SUM(podsuma4) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr."", $conn) or die($k_b);
		} else $result6a = mysql_query("SELECT SUM(podsuma4) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$_REQUEST[zakres])", $conn) or die($k_b);
		list($ps4a) = mysql_fetch_array($result6a);
		
		if ($_REQUEST[zakres]=='all') { 
			$result6a = mysql_query("SELECT SUM(podsuma5) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr."", $conn) or die($k_b);
		} else $result6a = mysql_query("SELECT SUM(podsuma5) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$_REQUEST[zakres])", $conn) or die($k_b);
		list($ps5a) = mysql_fetch_array($result6a);
		
		if ($_REQUEST[zakres]=='all') { 
			$result6a = mysql_query("SELECT SUM(podsuma6) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr."", $conn) or die($k_b);
		} else $result6a = mysql_query("SELECT SUM(podsuma6) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$_REQUEST[zakres])", $conn) or die($k_b);
		list($ps6a) = mysql_fetch_array($result6a);
		
		if ($_REQUEST[zakres]=='all') { 
			$result6a = mysql_query("SELECT SUM(podsuma7) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr."", $conn) or die($k_b);
		} else $result6a = mysql_query("SELECT SUM(podsuma7) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$_REQUEST[zakres])", $conn) or die($k_b);
		list($ps7a) = mysql_fetch_array($result6a);
		
		if ($_REQUEST[zakres]=='all') { 
			$result6a = mysql_query("SELECT SUM(podsuma8) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr."", $conn) or die($k_b);
		} else $result6a = mysql_query("SELECT SUM(podsuma8) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$_REQUEST[zakres])", $conn) or die($k_b);
		list($ps8a) = mysql_fetch_array($result6a);
		
		if ($_REQUEST[zakres]=='all') { 
			$result6a = mysql_query("SELECT SUM(podsuma9) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr."", $conn) or die($k_b);
		} else $result6a = mysql_query("SELECT SUM(podsuma9) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$_REQUEST[zakres])", $conn) or die($k_b);
		list($ps9a) = mysql_fetch_array($result6a);
		
		if ($_REQUEST[zakres]=='all') { 
			$result6a = mysql_query("SELECT SUM(podsuma10) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr."", $conn) or die($k_b);
		} else $result6a = mysql_query("SELECT SUM(podsuma10) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$_REQUEST[zakres])", $conn) or die($k_b);
		list($ps10a) = mysql_fetch_array($result6a);
		
		if ($_REQUEST[zakres]=='all') { 
			$result6a = mysql_query("SELECT SUM(podsuma11) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr."", $conn) or die($k_b);
		} else $result6a = mysql_query("SELECT SUM(podsuma11) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$_REQUEST[zakres])", $conn) or die($k_b);
		list($ps11a) = mysql_fetch_array($result6a);
		
		if ($_REQUEST[zakres]=='all') { 
			$result6a = mysql_query("SELECT SUM(podsuma12) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr."", $conn) or die($k_b);
		} else $result6a = mysql_query("SELECT SUM(podsuma12) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$_REQUEST[zakres])", $conn) or die($k_b);
		list($ps12a) = mysql_fetch_array($result6a);
		
		if ($_REQUEST[zakres]=='all') { 
			$result6a = mysql_query("SELECT SUM(podsuma13) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr."", $conn) or die($k_b);
		} else $result6a = mysql_query("SELECT SUM(podsuma13) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$_REQUEST[zakres])", $conn) or die($k_b);
		list($ps13a) = mysql_fetch_array($result6a);
		
		if ($_REQUEST[zakres]=='all') { 
			$result6a = mysql_query("SELECT SUM(podsuma14) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr."", $conn) or die($k_b);
		} else $result6a = mysql_query("SELECT SUM(podsuma14) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$_REQUEST[zakres])", $conn) or die($k_b);
		list($ps14a) = mysql_fetch_array($result6a);
		
		if ($_REQUEST[zakres]=='all') { 
			$result6a = mysql_query("SELECT SUM(podsuma15) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr."", $conn) or die($k_b);
		} else $result6a = mysql_query("SELECT SUM(podsuma15) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$_REQUEST[zakres])", $conn) or die($k_b);
		list($ps15a) = mysql_fetch_array($result6a);
		
		if ($_REQUEST[zakres]=='all') { 
			$result6a = mysql_query("SELECT SUM(podsuma16) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr."", $conn) or die($k_b);
		} else $result6a = mysql_query("SELECT SUM(podsuma16) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$_REQUEST[zakres])", $conn) or die($k_b);
		list($ps16a) = mysql_fetch_array($result6a);
		
		if ($_REQUEST[zakres]=='all') { 
			$result6a = mysql_query("SELECT SUM(podsuma17) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr."", $conn) or die($k_b);
		} else $result6a = mysql_query("SELECT SUM(podsuma17) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$_REQUEST[zakres])", $conn) or die($k_b);
		list($ps17a) = mysql_fetch_array($result6a);
		
		if ($_REQUEST[zakres]=='all') { 
			$result6a = mysql_query("SELECT SUM(podsuma18) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr."", $conn) or die($k_b);
		} else $result6a = mysql_query("SELECT SUM(podsuma18) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$_REQUEST[zakres])", $conn) or die($k_b);
		list($ps18a) = mysql_fetch_array($result6a);

		if ($_REQUEST[zakres]=='all') { 
			$result6a = mysql_query("SELECT SUM(podsuma19) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr."", $conn) or die($k_b);
		} else $result6a = mysql_query("SELECT SUM(podsuma19) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$_REQUEST[zakres])", $conn) or die($k_b);
		list($ps19a) = mysql_fetch_array($result6a);

		if ($_REQUEST[zakres]=='all') { 
			$result6a = mysql_query("SELECT SUM(podsuma20) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr."", $conn) or die($k_b);
		} else $result6a = mysql_query("SELECT SUM(podsuma20) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$_REQUEST[zakres])", $conn) or die($k_b);
		list($ps20a) = mysql_fetch_array($result6a);
		
		if ($_REQUEST[zakres]=='all') { 
			$result6a = mysql_query("SELECT SUM(podsuma21) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr."", $conn) or die($k_b);
		} else $result6a = mysql_query("SELECT SUM(podsuma21) FROM $dbname_hd.hd_temp_raport_zbiorczy_".$es_nr." WHERE (filia=$_REQUEST[zakres])", $conn) or die($k_b);
		list($ps21a) = mysql_fetch_array($result6a);
		
		// KONSULTACJE
		echo "<td class=center>"; if ($ps1a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps1a>0)) echo "<font color=green>"; echo "<b>$ps1a</b>";  if (($raport_zbiorczy_koloruj==1) && ($ps1a>0)) echo "</font>"; if ($ps1a==0) echo "</font>"; echo "</td>";
		echo "<td class=center>"; if ($ps2a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps2a>0)) echo "<font color=brown>"; echo "<b>$ps2a</b>";  if (($raport_zbiorczy_koloruj==1) && ($ps2a>0)) echo "</font>"; if ($ps2a==0) echo "</font>"; echo "</td>";
		echo "<td class=center>"; if ($ps3a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps3a>0)) echo "<font color=red>"; echo "<b>$ps3a</b>";  if (($raport_zbiorczy_koloruj==1) && ($ps3a>0)) echo "</font>"; if ($ps3a==0) echo "</font>"; echo "</td>";
		
		// awarie 
		echo "<td class=center>"; if ($ps4a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps4a>0)) echo "<font color=green>"; echo "<b>$ps4a</b>";  if (($raport_zbiorczy_koloruj==1) && ($ps4a>0)) echo "</font>"; if ($ps4a==0) echo "</font>"; echo "</td>";
		echo "<td class=center>"; if ($ps5a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps5a>0)) echo "<font color=brown>"; echo "<b>$ps5a</b>";  if (($raport_zbiorczy_koloruj==1) && ($ps5a>0)) echo "</font>"; if ($ps5a==0) echo "</font>"; echo "</td>";
		echo "<td class=center>"; if ($ps6a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps6a>0)) echo "<font color=red>"; echo "<b>$ps6a</b>";  if (($raport_zbiorczy_koloruj==1) && ($ps6a>0)) echo "</font>"; if ($ps6a==0) echo "</font>"; echo "</td>";
		
		// AWARIE KRYTYCZNE
		echo "<td class=center>"; if ($ps7a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps7a>0)) echo "<font color=green>"; echo "<b>$ps7a</b>";  if (($raport_zbiorczy_koloruj==1) && ($ps7a>0)) echo "</font>"; if ($ps7a==0) echo "</font>"; echo "</td>";
		echo "<td class=center>"; if ($ps8a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps8a>0)) echo "<font color=brown>"; echo "<b>$ps8a</b>";  if (($raport_zbiorczy_koloruj==1) && ($ps8a>0)) echo "</font>"; if ($ps8a==0) echo "</font>"; echo "</td>";
		echo "<td class=center>"; if ($ps9a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps9a>0)) echo "<font color=red>"; echo "<b>$ps9a</b>";  if (($raport_zbiorczy_koloruj==1) && ($ps9a>0)) echo "</font>"; if ($ps9a==0) echo "</font>"; echo "</td>";

		// KONSERWACJE
		echo "<td class=center>"; if ($ps19a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps19a>0)) echo "<font color=green>"; echo "<b>$ps19a</b>";  if (($raport_zbiorczy_koloruj==1) && ($ps19a>0)) echo "</font>"; if ($ps19a==0) echo "</font>"; echo "</td>";
		echo "<td class=center>"; if ($ps20a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps20a>0)) echo "<font color=brown>"; echo "<b>$ps20a</b>";  if (($raport_zbiorczy_koloruj==1) && ($ps20a>0)) echo "</font>"; if ($ps20a==0) echo "</font>"; echo "</td>";
		echo "<td class=center>"; if ($ps21a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps21a>0)) echo "<font color=red>"; echo "<b>$ps21a</b>";  if (($raport_zbiorczy_koloruj==1) && ($ps21a>0)) echo "</font>"; if ($ps21a==0) echo "</font>"; echo "</td>";
		
		// prace zlecone w ramach umowy
		echo "<td class=center>"; if ($ps10a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps10a>0)) echo "<font color=green>"; echo "<b>$ps10a</b>";if (($raport_zbiorczy_koloruj==1) && ($ps10a>0)) echo "</font>"; if ($ps10a==0) echo "</font>"; echo "</td>";
		echo "<td class=center>"; if ($ps11a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps11a>0)) echo "<font color=brown>"; echo "<b>$ps11a</b>";if (($raport_zbiorczy_koloruj==1) && ($ps11a>0)) echo "</font>";  if ($ps11a==0) echo "</font>"; echo "</td>";
		echo "<td class=center>"; if ($ps12a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps12a>0)) echo "<font color=red>"; echo "<b>$ps12a</b>"; if (($raport_zbiorczy_koloruj==1) && ($ps12a>0)) echo "</font>"; if ($ps12a==0) echo "</font>"; echo "</td>";		
		
		// prace zlecone poza umową
		echo "<td class=center>"; if ($ps13a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps13a>0)) echo "<font color=green>"; echo "<b>$ps13a</b>";if (($raport_zbiorczy_koloruj==1) && ($ps13a>0)) echo "</font>"; if ($ps13a==0) echo "</font>"; echo "</td>";
		echo "<td class=center>"; if ($ps14a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps14a>0)) echo "<font color=brown>"; echo "<b>$ps14a</b>";if (($raport_zbiorczy_koloruj==1) && ($ps14a>0)) echo "</font>";  if ($ps14a==0) echo "</font>"; echo "</td>";
		echo "<td class=center>"; if ($ps15a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps15a>0)) echo "<font color=red>"; echo "<b>$ps15a</b>"; if (($raport_zbiorczy_koloruj==1) && ($ps15a>0)) echo "</font>"; if ($ps15a==0) echo "</font>"; echo "</td>";

		// prace na potrzeby Postdata
		echo "<td class=center>"; if ($ps16a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps16a>0)) echo "<font color=green>"; echo "<b>$ps16a</b>";if (($raport_zbiorczy_koloruj==1) && ($ps16a>0)) echo "</font>"; if ($ps16a==0) echo "</font>"; echo "</td>";
		echo "<td class=center>"; if ($ps17a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps17a>0)) echo "<font color=brown>"; echo "<b>$ps17a</b>";if (($raport_zbiorczy_koloruj==1) && ($ps17a>0)) echo "</font>";  if ($ps17a==0) echo "</font>"; echo "</td>";
		echo "<td class=center>"; if ($ps18a==0) echo "<font color=grey>"; if (($raport_zbiorczy_koloruj==1) && ($ps18a>0)) echo "<font color=red>"; echo "<b>$ps18a</b>"; if (($raport_zbiorczy_koloruj==1) && ($ps18a>0)) echo "</font>"; if ($ps18a==0) echo "</font>"; echo "</td>";
	
	}
	
	echo "</tr>";
	
echo "</table>";

echo "<form action=do_xls_htmlexcel_hd_g_raport_zbiorczy.php METHOD=POST target=_blank>";	

startbuttonsarea("right");

	echo "<span style='float:left'>";
		if ($_REQUEST[rr]=='D') {
			addlinkbutton("'Zmień kryteria'","hd_g_raport_zbiorczy.php?okres=$_REQUEST[rr]&rok=$_REQUEST[r_rok]&zakres=$_REQUEST[zakres]&kat=".urlencode($_REQUEST[kat])."&d1=".$_REQUEST[d_od]."&d2=".$_REQUEST[d_do]."");
		}

		if ($_REQUEST[rr]=='T') {
			addlinkbutton("'Zmień kryteria'","hd_g_raport_zbiorczy.php?okres=$_REQUEST[rr]&rok=$_REQUEST[r_rok]&zakres=$_REQUEST[zakres]&kat=".urlencode($_REQUEST[kat])."&tz=".$_REQUEST[r_T_zakres]."");
		}

		if ($_REQUEST[rr]=='M') {
			addlinkbutton("'Zmień kryteria'","hd_g_raport_zbiorczy.php?okres=$_REQUEST[rr]&rok=$_REQUEST[r_rok]&zakres=$_REQUEST[zakres]&kat=".urlencode($_REQUEST[kat])."&Msel=".$_REQUEST[r_M_zakres]."");
		}

		if ($_REQUEST[rr]=='K') {
			addlinkbutton("'Zmień kryteria'","hd_g_raport_zbiorczy.php?okres=$_REQUEST[rr]&rok=$_REQUEST[r_rok]&zakres=$_REQUEST[zakres]&kat=".urlencode($_REQUEST[kat])."&kz=".$_REQUEST[r_K_zakres]."");
		}
	echo "</span>";

	echo "<input class=buttons type=submit value='Export do XLS'>";
	
	echo "<input type=hidden name=tbl_suffix value='$es_nr'>";
	echo "<input type=hidden name=filia_skrot value='$es_skrot'>";
	echo "<input type=hidden name=g_okres_od value='$data_od'>";
	echo "<input type=hidden name=g_okres_do value='$data_do'>";
	echo "<input type=hidden name=g_zakres value='$temp_zakres'>";
	echo "<input type=hidden name=g_kat value='$temp_kategorie1'>";
	echo "<input type=hidden name=g_addcategories value='$_REQUEST[AddCategories]'>";
	
addbuttons("zamknij");
endbuttonsarea();	
echo "</form>";

?>

<script>HideWaitingMessage();</script>

<?php 

} else { 

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

pageheader("Generowanie raportu zbiorczego dla oddziału");

?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();

echo "<form name=ruch action=hd_g_raport_zbiorczy.php method=POST>";

	echo "<table cellspacing=1 align=center style=width:500px>";
	tbl_empty_row(1);

	$Rok_Min = '2011';

	echo "<tr>";
		echo "<td class=center colspan=2>";		
			echo "Raport dla roku:&nbsp;";

			if ($_REQUEST[rok]!='') { $Rok_Sel = $_REQUEST[rok]; } else { $Rok_Sel = date('Y'); }
			$Rok_Curr = date('Y');			
			$Lat_Wstecz = $Rok_Curr - $Rok_Min;
			
			echo "<select name=r_rok onChange=\"self.location='hd_g_raport_zbiorczy.php?okres=".$_REQUEST[okres]."&rok='+this.value+'&zakres=".$_REQUEST[zakres]."&kat=".$_REQUEST[kat]."&Msel=".$_REQUEST[Msel]."';\">";
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
			echo "<input type=radio name=rr id=rr_D value='D'"; if ($_REQUEST[okres]=='D') echo " checked "; echo " onClick=\"self.location='hd_g_raport_zbiorczy.php?okres=D&rok=".$_REQUEST[rok]."&q=".$_REQUEST[q]."&d1=".$_REQUEST[d1]."&d2=".$_REQUEST[d2]."&zakres=".$_REQUEST[zakres]."&kat=".$_REQUEST[kat]."'; \"><a class=normalfont href=# onClick=\"document.getElementById('rr_D').checked=true; self.location='hd_g_raport_zbiorczy.php?okres=D&rok=".$_REQUEST[rok]."&q=".$_REQUEST[q]."&d1=".$_REQUEST[d1]."&d2=".$_REQUEST[d2]."&zakres=".$_REQUEST[zakres]."&kat=".$_REQUEST[kat]."'; \">Dzienny (od..do)</a>";
		echo "</td>";

		echo "<td class=left "; 
		if ($_REQUEST[okres]=='D') { echo " style='display:' "; } else { echo " style='display:none' "; }
		echo ">";
		
		if ($_REQUEST[d1]!='') { $d1_value = $_REQUEST[d1]; } else { $d1_value = date('Y-m-d'); }
		if ($_REQUEST[d2]!='') { $d2_value = $_REQUEST[d2]; } else { $d2_value = date('Y-m-d'); }
		
			echo "<input type=text size=8 maxlength=10 name=d_od id=d_od value='".$d1_value."' onChange=\"self.location='hd_g_raport_zbiorczy.php?okres=D&rok=".$_REQUEST[rok]."&q=".$_REQUEST[q]."&d1='+this.value+'&d2=".$_REQUEST[d2]."&zakres=".$_REQUEST[zakres]."&kat=".$_REQUEST[kat]."'; \">";
			echo " ... ";
			echo "<input type=text size=8 maxlength=10 name=d_do id=d_do value='".$d2_value."' onChange=\"self.location='hd_g_raport_zbiorczy.php?okres=D&rok=".$_REQUEST[rok]."&q=".$_REQUEST[q]."&d2='+this.value+'&d1=".$_REQUEST[d1]."&zakres=".$_REQUEST[zakres]."&kat=".$_REQUEST[kat]."'; \">";
		echo "</td>";		
	echo "</tr>";	
	
	}
	
	echo "<tr height=30>";
		echo "<td>";
			echo "<input type=radio name=rr id=rr_T value='T'"; if ($_REQUEST[okres]=='T') echo " checked "; echo " onClick=\"self.location='hd_g_raport_zbiorczy.php?okres=T&rok=".$_REQUEST[rok]."&q=".$_REQUEST[q]."&d1=".$_REQUEST[d1]."&d2=".$_REQUEST[d2]."&zakres=".$_REQUEST[zakres]."&kat=".$_REQUEST[kat]."'; \"><a class=normalfont href=# onClick=\"document.getElementById('rr_T').checked=true; self.location='hd_g_raport_zbiorczy.php?okres=T&rok=".$_REQUEST[rok]."&q=".$_REQUEST[q]."&d1=".$_REQUEST[d1]."&d2=".$_REQUEST[d2]."&zakres=".$_REQUEST[zakres]."&kat=".$_REQUEST[kat]."'; \">Tygodniowy</a>";
		echo "</td>";

		if ($Rok_Curr == $Rok_Sel) { $Max_Week_Nr = date('W'); } else { 
			$Max_Week_Nr = date("W", mktime(0,0,0,12,28,$Rok_Sel));
		}
		
		$Week_nr = date('W');
		
		echo "<td>"; 
		echo "<span ";
		if ($_REQUEST[okres]=='T') { echo " style='display:' "; } else { echo " style='display:none' "; }
		echo ">";
			$isSel = false;
			echo "<select name=r_T_zakres>";
				for ($r=1; $r<=$Max_Week_Nr; $r++) { 
					$tValue = getFirstDayOfWeek($_REQUEST[rok],$r)."@".getLastDayOfWeek($_REQUEST[rok],$r);
					
					echo "<option value='".$tValue."'";
					
					if ($tValue==$_REQUEST[tz]) { 
						echo " SELECTED "; 
						$isSel = true;
					} else {
						if (($Week_nr==$r) && ($Rok_Curr == $Rok_Sel) && ($isSel==false)) echo " SELECTED ";
					}
					
					echo ">".getFirstDayOfWeek($_REQUEST[rok],$r)." - ".getLastDayOfWeek($_REQUEST[rok],$r)."</option>\n";
				}
			echo "</select>";			
		
		echo "</span>";
		echo "</td>";
	echo "</tr>";
	
	echo "<tr height=30>";
		echo "<td>";
			echo "<input type=radio name=rr id=rr_M value='M'"; if ($_REQUEST[okres]=='M') echo " checked "; echo " onClick=\"self.location='hd_g_raport_zbiorczy.php?okres=M&rok=".$_REQUEST[rok]."&q=".$_REQUEST[q]."&d1=".$_REQUEST[d1]."&d2=".$_REQUEST[d2]."&zakres=".$_REQUEST[zakres]."&kat=".$_REQUEST[kat]."'; \"><a class=normalfont href=# onClick=\"document.getElementById('rr_M').checked=true; self.location='hd_g_raport_zbiorczy.php?okres=M&rok=".$_REQUEST[rok]."&q=".$_REQUEST[q]."&d1=".$_REQUEST[d1]."&d2=".$_REQUEST[d2]."&zakres=".$_REQUEST[zakres]."&kat=".$_REQUEST[kat]."'; \">Miesięczny</a>";
		echo "</td>";

		echo "<td>"; 
		echo "<span ";
		if ($_REQUEST[okres]=='M') { echo " style='display:' "; } else { echo " style='display:none' "; }
		echo ">";

		$Miesiac_Curr = date('m');
		$isSel1 = false;
		$isSel2 = false;
		$isSel3 = false;
		$isSel4 = false;
		$isSel5 = false;
		$isSel6 = false;
		$isSel7 = false;
		$isSel8 = false;
		$isSel9 = false;
		$isSel10 = false;
		$isSel11 = false;
		$isSel12 = false;
		$isSelM = false;
		
			echo "<select name=r_M_zakres onChange=\"self.location='hd_g_raport_zbiorczy.php?okres=".$_REQUEST[okres]."&rok=".$_REQUEST[rok]."&zakres=".$_REQUEST[zakres]."&kat=".$_REQUEST[kat]."&Msel='+this.value+'';\">";
				$vStyczen = $Rok_Sel."-01-01@".$Rok_Sel."-01-31";
				$vLuty = $Rok_Sel."-02-01@".$Rok_Sel."-02-29";
				$vMarzec = $Rok_Sel."-03-01@".$Rok_Sel."-03-31";
				$vKwiecien = $Rok_Sel."-04-01@".$Rok_Sel."-04-30";
				$vMaj = $Rok_Sel."-05-01@".$Rok_Sel."-05-31";
				$vCzerwiec = $Rok_Sel."-06-01@".$Rok_Sel."-06-30";
				$vLipiec = $Rok_Sel."-07-01@".$Rok_Sel."-07-31";
				$vSierpien = $Rok_Sel."-08-01@".$Rok_Sel."-08-31";
				$vWrzesien = $Rok_Sel."-09-01@".$Rok_Sel."-09-30";
				$vPazdziernik = $Rok_Sel."-10-01@".$Rok_Sel."-10-31";
				$vListopad = $Rok_Sel."-11-01@".$Rok_Sel."-11-30";
				$vGrudzien = $Rok_Sel."-12-01@".$Rok_Sel."-12-31";				
				
				echo "<option value='$vStyczen' ";
					if ($vStyczen==$_REQUEST[Msel]) { 
						echo " SELECTED "; 
						$isSel1 = true;
						$isSelM = true;
					} else {
						if (($Miesiac_Curr=='01') && ($Rok_Curr == $Rok_Sel) && ($isSelM==false)) echo "SELECTED"; 
					}			
				echo ">Styczeń</option>\n";
				
				echo "<option value='$vLuty' "; 
					if ($vLuty==$_REQUEST[Msel]) { 
						echo " SELECTED "; $isSel2 = true;	$isSelM = true;
					} else { 
						if (($Miesiac_Curr=='02') && ($Rok_Curr == $Rok_Sel) && ($isSelM==false)) echo "SELECTED"; 
					}			
				echo ">Luty</option>\n";
				
				echo "<option value='$vMarzec' "; 
					if ($vMarzec==$_REQUEST[Msel]) { 
						echo " SELECTED "; $isSel3 = true;	$isSelM = true;
					} else { 
						if (($Miesiac_Curr=='03') && ($Rok_Curr == $Rok_Sel) && ($isSelM==false)) echo "SELECTED"; 
					}			
				echo ">Marzec</option>\n";
				
				echo "<option value='$vKwiecien' "; 
					if ($vKwiecien==$_REQUEST[Msel]) { 
						echo " SELECTED "; $isSel4 = true;	$isSelM = true;
					} else { 
						if (($Miesiac_Curr=='04') && ($Rok_Curr == $Rok_Sel) && ($isSelM==false)) echo "SELECTED"; 
					}			
				echo ">Kwiecień</option>\n";
				
				echo "<option value='$vMaj' ";
					if ($vMaj==$_REQUEST[Msel]) { 
						echo " SELECTED "; $isSel5 = true;	$isSelM = true;
					} else { 
						if (($Miesiac_Curr=='05') && ($Rok_Curr == $Rok_Sel) && ($isSelM==false)) echo "SELECTED"; 
					}			
				echo ">Maj</option>\n";
				
				echo "<option value='$vCzerwiec' "; 
					if ($vCzerwiec==$_REQUEST[Msel]) { 
						echo " SELECTED "; $isSel6 = true;	$isSelM = true;
					} else { 
						if (($Miesiac_Curr=='06') && ($Rok_Curr == $Rok_Sel) && ($isSelM==false)) echo "SELECTED"; 
					}
				echo ">Czerwiec</option>\n";
				
				echo "<option value='$vLipiec' "; 
					if ($vLipiec==$_REQUEST[Msel]) { 
						echo " SELECTED "; $isSel7 = true;	$isSelM = true;
					} else { 
						if (($Miesiac_Curr=='07') && ($Rok_Curr == $Rok_Sel) && ($isSelM==false)) echo "SELECTED"; 
					}			
				echo ">Lipiec</option>\n";
				
				echo "<option value='$vSierpien' "; 
					if ($vSierpien==$_REQUEST[Msel]) { 
						echo " SELECTED "; $isSel8 = true;	$isSelM = true;
					} else { 
						if (($Miesiac_Curr=='08') && ($Rok_Curr == $Rok_Sel) && ($isSelM==false)) echo "SELECTED"; 
					}			
				echo ">Sierpień</option>\n";
				
				echo "<option value='$vWrzesien' "; 
					if ($vWrzesien==$_REQUEST[Msel]) { 
						echo " SELECTED "; $isSel9 = true;	$isSelM = true;
					} else { 
						if (($Miesiac_Curr=='09') && ($Rok_Curr == $Rok_Sel) && ($isSelM==false)) echo "SELECTED"; 
					}			 
				echo ">Wrzesień</option>\n";
				
				echo "<option value='$vPazdziernik' ";
					if ($vPazdziernik==$_REQUEST[Msel]) { 
						echo " SELECTED "; $isSel10= true;	$isSelM = true;
					} else { 
						if (($Miesiac_Curr=='10') && ($Rok_Curr == $Rok_Sel) && ($isSelM==false)) echo "SELECTED"; 
					}			
				echo ">Październik</option>\n";
				
				echo "<option value='$vListopad' "; 
					if ($vListopad==$_REQUEST[Msel]) { 
						echo " SELECTED "; $isSel11= true;	$isSelM = true;
					} else { 
						if (($Miesiac_Curr=='11') && ($Rok_Curr == $Rok_Sel) && ($isSelM==false)) echo "SELECTED"; 
					}			
				echo ">Listopad</option>\n";
				
				echo "<option value='$vGrudzien' ";
					if ($vGrudzien==$_REQUEST[Msel]) { 
						echo " SELECTED "; $isSel12= true;	$isSelM = true;
					} else { 
						if (($Miesiac_Curr=='12') && ($Rok_Curr == $Rok_Sel) && ($isSelM==false)) echo "SELECTED"; 
					}			
				echo ">Grudzień</option>\n";
				
			echo "</select>";
		echo "</span>";
		echo "&nbsp;";
		echo "</td>";		
	echo "</tr>";

	echo "<tr height=30>";

		echo "<td>";
			echo "<input type=radio name=rr id=rr_K value='K'"; if ($_REQUEST[okres]=='K') echo " checked "; echo " onClick=\"self.location='hd_g_raport_zbiorczy.php?okres=K&rok=".$_REQUEST[rok]."&q=".$_REQUEST[q]."&d1=".$_REQUEST[d1]."&d2=".$_REQUEST[d2]."&zakres=".$_REQUEST[zakres]."&kat=".$_REQUEST[kat]."'; \"><a class=normalfont href=# onClick=\"document.getElementById('rr_K').checked=true; self.location='hd_g_raport_zbiorczy.php?okres=K&rok=".$_REQUEST[rok]."&q=".$_REQUEST[q]."&d1=".$_REQUEST[d1]."&d2=".$_REQUEST[d2]."&zakres=".$_REQUEST[zakres]."&kat=".$_REQUEST[kat]."'; \">Kwartalny</a>";
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
			
			$isSel1 = false;
			$isSel2 = false;
			$isSel3 = false;
			$isSel4 = false;
			$isSelK = false;
			
			echo "<select name=r_K_zakres>";
				$vK1 = $Rok_Sel."-01-01@".$Rok_Sel."-03-31";
				$vK2 = $Rok_Sel."-04-01@".$Rok_Sel."-06-30";
				$vK3 = $Rok_Sel."-07-01@".$Rok_Sel."-09-30";
				$vK4 = $Rok_Sel."-10-01@".$Rok_Sel."-12-31";
				
				echo "<option value='$vK1' "; 
					if ($vK1==$_REQUEST[kz]) { 
						echo " SELECTED "; $isSelK= true;	
					} else { 
						if (($kw_nr==1) && ($isSelK==false)) echo "SELECTED"; 
					}
				echo ">I kwartał</option>\n";
										
				echo "<option value='$vK2' "; 
					if ($vK2==$_REQUEST[kz]) { 
						echo " SELECTED "; $isSelK= true;	
					} else { 
						if (($kw_nr==2) && ($isSelK==false)) echo "SELECTED"; 
					}
				echo ">II kwartał</option>\n";
				
				echo "<option value='$vK3' "; 
					if ($vK3==$_REQUEST[kz]) { 
						echo " SELECTED "; $isSelK= true;	
					} else { 
						if (($kw_nr==3) && ($isSelK==false)) echo "SELECTED"; 
					}
				echo ">III kwartał</option>\n";
				
				echo "<option value='$vK4' "; 
					if ($vK4==$_REQUEST[kz]) { 
						echo " SELECTED "; $isSelK= true;	
					} else { 
						if (($kw_nr==4) && ($isSelK==false)) echo "SELECTED"; 
					}
				echo ">IV kwartał</option>\n";
				
			echo "</select>";
			echo "</span>";
			echo "&nbsp;";
		echo "</td>";		
	echo "</tr>";
	//tbl_empty_row(2);
	echo "<tr>";
		echo "<td class=center colspan=2>";		
			echo "<hr />";
		echo "</td>";
	echo "</tr>";
	
	echo "<tr>";
		echo "<td class=right>";		
			echo "Raport dla obszaru";
		echo "</td>";
		echo "<td class=left>";		
			echo "<select name=zakres onChange=\"self.location='hd_g_raport_zbiorczy.php?okres=".$_REQUEST[okres]."&rok=".$_REQUEST[rok]."&zakres='+this.value+'&kat=".$_REQUEST[kat]."&Msel=".$_REQUEST[Msel]."'; \">";
				if (($is_dyrektor==1) || ($es_m==1) || (2==2)) {
					echo "<option value='all' "; if ($_REQUEST[zakres]=='all') echo "SELECTED"; echo ">Cały oddział ".$obszar."</option>\n";
				
					$result6 = mysql_query("SELECT filia_id, filia_nazwa FROM $dbname.serwis_filie ORDER BY filia_nazwa", $conn) or die($k_b);				
					while (list($temp_id_filii,$temp_nazwa_filii) = mysql_fetch_array($result6)) {
						echo "<option value='$temp_id_filii'"; if ($_REQUEST[zakres]==$temp_id_filii) echo "SELECTED"; echo ">$temp_nazwa_filii</option>\n"; 
					}
				} else {
					$result6 = mysql_query("SELECT filia_nazwa FROM $dbname.serwis_filie WHERE filia_id=$es_filia LIMIT 1", $conn) or die($k_b);	
					list($temp_nazwa_filii) = mysql_fetch_array($result6);
					echo "<option value='$es_filia'"; 
					if ($_REQUEST[zakres]==$es_filia) echo " SELECTED ";
					echo ">$temp_nazwa_filii</option>\n"; 
				}
				
			echo "</select>";
		echo "</td>";
	echo "</tr>";	

	echo "<tr>";
		echo "<td class=right>";
			echo "Raport dla kategorii";
		echo "</td>";
		echo "<td class=left>";
			echo "<select name=kat onChange=\"self.location='hd_g_raport_zbiorczy.php?okres=".$_REQUEST[okres]."&rok=".$_REQUEST[rok]."&zakres=".$_REQUEST[zakres]."&kat='+this.value+'&Msel=".$_REQUEST[Msel]."'; \">";
				
				echo "<option value='all' "; if ($_REQUEST[kat]=='all') echo "SELECTED"; echo ">wszystkich</option>\n";
				
				$result6 = mysql_query("SELECT hd_kategoria_nr, hd_kategoria_opis FROM $dbname_hd.hd_kategoria WHERE (hd_kategoria_wlaczona=1) ORDER BY hd_kategoria_display_order ASC", $conn) or die($k_b);
				
				while (list($kat_nr,$kat_opis) = mysql_fetch_array($result6)) {
					echo "<option value='$kat_nr'"; if ($_REQUEST[kat]==$kat_nr) echo "SELECTED"; echo ">$kat_opis</option>\n"; 
				}
		
				
				
			echo "</select>";
		echo "</td>";
	echo "</tr>";

	if ($_REQUEST[kat]=='all') {
		echo "<tr>";
			echo "<td class=right>";
				//echo "Raport dla kategorii";
			echo "</td>";
			echo "<td class=left>";
			
				echo "<input class=border0 type=checkbox name=AddCategories id=AddCategories checked=checked>";
				echo "<a href=# class=normalfont onClick=\"if (document.getElementById('AddCategories').checked) { document.getElementById('AddCategories').checked=false; } else { document.getElementById('AddCategories').checked=true; } \">&nbsp;Dodaj kolumny ze wszystkimi kategoriami</a>";
				
			echo "</td>";
		echo "</tr>";
	}
	
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
<script>HideWaitingMessage();</script>
</body>
</html>

