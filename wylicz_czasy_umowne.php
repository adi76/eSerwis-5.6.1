<?php 
// #############################################################################################################################################
// 
// zmienne wejściowe :	$CzasNaRozpoczecie				$__CzasNaRozpoczecie
//						$DataGodzinaWpisu				$__DataGodzinaWpisu
//						$CzasNaZakonczenie				$__CzasNaZakonczenie
// 						$_REQUEST[hddz]					$__DW
// 						$_REQUEST[hdgz]					$__GW


$DataRozpoczecia = AddHoursToDate($__CzasNaRozpoczecie,$__DataGodzinaWpisu)."";		
$DataZakonczenia = AddHoursToDate($__CzasNaZakonczenie,$__DataGodzinaWpisu)."";
	
$HoursToStart = $__CzasNaRozpoczecie;
$HoursToFinish = "8";
	
$DW = $__DW;
$GW = $__GW;

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

	// wyliczenie godziny rozpoczęcia
	$DR = '';
	$DZ = '';

	// $__CzasNaZakonczenie = "8";
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

	// LICZ CZAS ROZPOCZĘCIA 
	
	$CNRmin = $__CzasNaRozpoczecie*60;
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
			if ($CNRmin<=$IMWdniu) {
				$DR = AddMinutesToDate($CNRmin,godzina_start1s($temp_d,$week, $serwis_working_time)); 
				break;
			} else {
				$CNRmin = ($CNRmin-$IMWdniu);
			}	
		}
		$i++;
	endwhile;

	if ($debug) echo "# $DR #";
	
	if ($debug) echo "<hr />$mod_dw<hr />";	
	// LICZ CZAS ZAKOŃCZENIA 
	$i=0;					
	$CNZmin = 8*60;

	while (2==2):
		
		if ($debug) echo "<br /><br />przelot: $i<br />CNZ: <b>$CNZmin</b><br />";	
		
		$temp_d = AddHoursToDateSimple(($i*24),$DW);
		if ($mod_dw == 1) {
			$temp_d1 = AddMinutesToDate(($i*60),$DW); 
		} else {
			$temp_d1 = AddMinutesToDate(($i*60),($DW." ".$GW)); 
		}
		//if ($debug) echo "->".godzina_start1s($temp_d,$week, $serwis_working_time)."<-";
		
		
		if ($i>0) $GW = godzina_stop1($temp_d,$week, $serwis_working_time);
		
		$IMPWUP = ilosc_godzin_w_dniu1s($temp_d,$week,$GW, $serwis_working_time);
		$IMWdniu = ilosc_godzin_w_dniu1($temp_d,$week,$GW);	
		
		
		if ($debug) echo "{{<b>$temp_d1</b>}}";
		if ($debug) echo "Data analizowana: <b>$temp_d</b><br />";
		if ($debug) echo "Il. minut pracy: <b>".$IMPWUP."</b><br />";
		if ($debug) echo "[[[[[[[[[[[[[$temp_d]]]]]]]]]]]]";
		if ($debug) echo "<br />Il. minut pracy1: <b>".$IMWdniu."</b><br />";
		if ($i==0) {
			if ($CNZmin<=$IMPWUP) {
				$DZ = AddMinutesToDate($CNZmin,$temp_d1);
				if ($debug) echo "<br />DZ-->$DZ";
				break;
			} else {
				$CNZmin = ($CNZmin-$IMPWUP);
			}
		} else {
			
			if ($CNZmin<=$IMWdniu) {
				$DZ = AddMinutesToDate($CNZmin,godzina_start1s($temp_d,$week, $serwis_working_time)); 
				break;
			} else {
				$CNZmin = ($CNZmin-$IMWdniu);
			}
		}
		
		if ($debug) echo ">>>".$DZ."<<1<<".godzina_start1s($temp_d,$week, $serwis_working_time)."";
		$i++;
	endwhile;
	
	$samaG = substr($DZ, 11,10);
	$DZ = $temp_d." ".$samaG;
	
	//echo $samaG;
	
	if ($debug) echo "<br /><br /># DZ: # $DZ #";
	$DataRozpoczecia = $DR;
	$DataZakonczenia = $DZ;
	break;

?>