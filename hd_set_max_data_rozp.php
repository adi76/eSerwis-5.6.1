<?php
include_once('header.php');
require_once "cfg_helpdesk.php"; 

if (isset($_POST["upid"])) {
	$ReturnValue = '';
	$basedate = $_POST[sdate];
	$DataGodzinaWpisu = $_POST[sdate]." ".$_POST[ctime];
	
	$sql5 = "SELECT up_kategoria,up_working_time,up_working_time_alternative, up_working_time_alternative_start_date, up_working_time_alternative_stop_date FROM $dbname.serwis_komorki WHERE (up_id='$_POST[upid]') LIMIT 1";
	$result2 = mysql_query($sql5,$conn_hd) or die($k_b);
	$dane = mysql_fetch_array($result2);
	
		$_temp_kategoria = $dane['up_kategoria'];
		$week = $dane['up_working_time'];
		$_temp_wha = $dane['up_working_time_alternative'];
		$_temp_wha_start = $dane['up_working_time_alternative_start_date'];
		$_temp_wha_stop = $dane['up_working_time_alternative_stop_date'];
	
		// dodaæ sprawdzenie czy nie obowi¹zuj¹ alternatywne godziny pracy. je¿eli tak to $week = $_temp_wha

		if (($_temp_kategoria=='1') || ($_temp_kategoria=='2')) {
			$CzasNaRozpoczecie = "2";
			$CzasNaZakonczenie = "8";
			
			$MinutDoRozpoczecia = 120;
			$MinutDoZakonczenia = 480;
		} else {
			$CzasNaRozpoczecie = "3";
			$CzasNaZakonczenie = "8";

			$MinutDoRozpoczecia = 180;
			$MinutDoZakonczenia = 480;	
		}	
	
	
						$DataRozpoczecia = AddHoursToDate($CzasNaRozpoczecie,$DataGodzinaWpisu)."";		
						$DataZakonczenia = AddHoursToDate($CzasNaZakonczenie,$DataGodzinaWpisu)."";
							
						$HoursToStart = $CzasNaRozpoczecie;
						$HoursToFinish = "8";
							
						$DW = $_POST['sdate'];
						$GW = $_POST['ctime'];

						if ($GW<'07:00') { $GW = '07:00'; }
							
						if (jaki_dzien($DW)=='so') {
							if ($GW>'15:00') { $GW = '07:00'; $DW = AddHoursToDateSimple(48,$DW); }
						} else {
							if ($GW>'21:00') { $GW = '07:00'; $DW = AddHoursToDateSimple(24,$DW); }							
						}
							
						if ($debug) echo "Data wpisu: <b>".$DW." ".$GW."</b><br />";
						//$week = "PN@07:00-15:00;WT@07:00-15:00;SR@07:00-15:00;CZ@07:00-15:00;PT@07:00-15:00;SO@;NI@-;";
						if ($debug) echo "Godziny pracy: <b>".$week."</b><br />";
	
							$i = 0;

							// wyliczenie godziny rozpoczêcia
							$DR = '';
							$DZ = '';

							// $CzasNaZakonczenie = "8";
							$temp_dr = $DW." ".$GW;
							$mod_dw = 0;

							if ($temp_dr<godzina_start($DW,$week)) {
								$DW = godzina_start($DW,$week);
								$mod_dw = 1;
							}

							//echo "temp_dr: $temp_dr     ".godzina_stop($DW,$week)."<br />";

							if ($temp_dr>godzina_stop($DW,$week)) {
								
								$i = 1;
								while (2==2):
									$temp_d = AddHoursToDateSimple(($i*24),$DW);
									if (czy_pracuje($temp_d, $week)) {			
										$DW = godzina_start((AddHoursToDateSimple(($i*24),$DW)),$week);
										$GW = substr($DW,11,20);
										$mod_dw = 1;
										break;
									}
									$i++;
								endwhile;
							}
							
							$i=0;
							
							if ($debug) echo "[$mod_dw]";
							if ($debug) echo "Data rozpoczecia liczenia czasu: <b>$DW</b><br />";

							// LICZ CZAS ROZPOCZÊCIA 
							
							$CNRmin = $CzasNaRozpoczecie*60;
							while (2==2):
								if ($debug) echo "przelot: $i<br />CNR: $CNRmin<br />";	
								
								$temp_d = AddHoursToDateSimple(($i*24),$DW);
								if ($debug) echo "<b>$temp_d</b><br />";
								if ($mod_dw == 1) {
									$temp_d1 = AddMinutesToDate(($i*60),$DW); 
								} else {
									$temp_d1 = AddMinutesToDate(($i*60),($DW." ".$GW)); 
								}
								
								if ($debug) echo "{<b>$temp_d</b>}";
								if ($debug) echo "{<b>$temp_d1</b>}";
								
								$IMPWUP = ilosc_godzin_w_dniu1s($temp_d,$week,$GW, $serwis_working_time);
								
								if ($debug) echo "Data analizowana: <b>$temp_d</b><br />";
								if ($debug) echo "Il. minut pracy: <b>".$IMPWUP."</b><br />";
								
								$IMWdniu = ilosc_godzin_w_dniu1($temp_d,$week,$GW);
								if ($debug) echo "Il. minut pracy1: <b>".$IMWdniu."</b><br />";
								
								if ($i==0) {
									if ($CNRmin<=$IMPWUP) {
										$DR = AddMinutesToDate($CNRmin,$temp_d1);
										if ($debug) echo "<br />DR-->$DR";
										break;
									} else {
										$CNRmin = ($CNRmin-$IMPWUP);
									}
								} else {
									if ($debug) echo "##### $CNRmin<=$IMWdniu $temp_d";
									if ($CNRmin<=$IMWdniu) {
										$DR = AddMinutesToDate($CNRmin,$temp_d." ".godzina_start1s($temp_d,$week, $serwis_working_time)); 
										if ($debug) echo ">>>>>".$DR."<<<{{{{{ ".AddMinutesToDate($CNRmin,$temp_d." ".godzina_start1s($temp_d,$week, $serwis_working_time))."<<";
										if ($debug) echo "<br />".AddMinutesToDate(110,godzina_start1s($temp_d,$week, $serwis_working_time))."<br />";
										break;
									} else {
										$CNRmin = ($CNRmin-$IMWdniu);
									}	
								}
								$i++;
							endwhile;
	$ReturnValue = substr($DR,0,16);
		
	//$ReturnValue = substr($ReturnValue, 0, -1);
	
	echo ">>>>>$ReturnValue<<<<<";

} else {
	echo ">>>>><<<<<";
}
return false;

?>
