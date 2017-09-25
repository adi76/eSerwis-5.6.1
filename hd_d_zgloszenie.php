<?php
include_once('header.php');
include_once('cfg_helpdesk.php');
if ($WlaczMaile=='1') require_once('phpMailer/class.phpmailer.php');
if ($WlaczMaile=='1') require_once('cfg_mails.php');

$_body = "N";
if ($_GET[zgl_reklamacyjne]=='1') $_body = 'R';
if (($_REQUEST[fromtask]==1) && ($_REQUEST[zadanie]!='')) $_body = "T";
if ($_REQUEST[dlakomorki]!='') $_body = "K";
if ($submit) $_body = "S";
if ($_REQUEST[parent_zgl]!='') $_body = "KZ";

switch ($_body) {
	case "N" : echo "<body OnLoad=\"document.getElementById('hdnzhadim').focus(); MakePodkategoriaList('1'); StatusChanged(1,'9','Kategoria'); GenerateOnClickEventForSlownikTresci(); \" onUnload=\"eraseCookie(self.name); eraseCookie('current_window_name'); \" />"; break;
	case "R" : echo "<body OnLoad=\"document.forms[0].czas_wykonywania_h.focus();\" />"; break;
	case "T" : echo "<body OnLoad=\"document.forms[0].hdgz.focus();\" />"; break;
	case "K" : echo "<body OnLoad=\"document.getElementById('hd_oz').focus();\" />"; break; 
	case "S" : echo "<body OnLoad=\"document.getElementById('zamknij_button').focus(); \" />";  break;
	case "KZ" : echo "<body OnLoad=\"document.getElementById('hd_tresc').focus(); \" />";  break;
	default	: echo "<body OnLoad=\"document.getElementById('up_list').focus(); MakePodkategoriaList('1'); StatusChanged(1,'9','Kategoria'); GenerateOnClickEventForSlownikTresci(); \" onUnload=\"eraseCookie(self.name); eraseCookie('current_window_name'); \" />"; break;
}

function toUpper($string) {
	$string = strtr($string,'qwertyuiopasdfghjklzxcvbnm','QWERTYUIOPASDFGHJKLZXCVBNM');
	return (strtr($string, 'ęóąśłżźćń','ĘÓĄŚŁŻŹĆŃ' )); 
}; 

if ($submit) {
//$debug=true;
	
	$_REQUEST=sanitize($_REQUEST);
	
	$_REQUEST[priorytet_id] = 2;
	
	if ($debug) ob_flush();
	
	if ($_SESSION['zgloszenie_dodano_'.$_REQUEST[unique_nr1].'']=='') {
		$unique_nr = Date('YmdHis')."".rand_str();
	} else {
		$unique_nr = $_REQUEST[unique_nr1];
	}
	
	$status_zgloszenia = $_REQUEST[status_id];
	$DataGodzinaWpisu = $_REQUEST[hddz]." ".$_REQUEST[hdgz];
	
	$DataWpisu1 = $_REQUEST[hddz];
	$GodzinaWpisu1 = $_REQUEST[hdgz];
	
// wymagalność wyjazdu
	$czy_wymagany_wyjazd = 0;
	$czy_wymagany_wyjazd_data = Date("Y-m-d H:i:s");
	$czy_wymagany_wyjazd_osoba = $currentuser;
	
	if ($_REQUEST[wymaga_wyjazdu]=='on') $czy_wymagany_wyjazd = 1;
	if ($_REQUEST[kat_id]==1) {
		$czy_wymagany_wyjazd = 0;
		$czy_wymagany_wyjazd_data = '';
		$czy_wymagany_wyjazd_osoba = '';
	}
	if (($status_zgloszenia==9) || ($_REQUEST[PozwolWpisacKm]=='on')) {
		$czy_wymagany_wyjazd = 0;
		$czy_wymagany_wyjazd_data = '';
		$czy_wymagany_wyjazd_osoba = '';
	}
	
// zapis zgłoszenia do tabeli hd_zgloszenie
	$awaria_z_przesunieciem = 0;
	
	if ($_REQUEST[UstalonaDataZakonczeniaCheck]=='on') {
		if ($_REQUEST[kat_id]==2) {
			$awaria_z_przesunieciem = 1;
			$status_zgloszenia = 1;
			$NowaDataRozpoczecia = $_REQUEST[nowa_data_rozpoczecia];
			$NowyCzasRozpoczecia = $_REQUEST[nowy_czas_rozpoczecia];
			if ($NowyCzasRozpoczecia=='') { $NowyCzasRozpoczecia="07:00:00"; } else { $NowyCzasRozpoczecia = $NowyCzasRozpoczecia.':00'; }
		}
		
		if ($_REQUEST[kat_id]==6) {
			$awaria_z_przesunieciem = 1;
			$status_zgloszenia = 1;
			$NowaDataRozpoczecia = $_REQUEST[hddz];
			$NowyCzasRozpoczecia = $_REQUEST[hdgz];
			
			// modyfikacji godzin pracy komórki
		}
	}
	
	if ($debug) echo "$NowaDataRozpoczecia $NowyCzasRozpoczecia";
	
	$zz = 0;
	if ($_REQUEST[zasadne]=='TAK') $zz = 1;
	
	$d_km = 0;
	if (($_REQUEST[km]!='') && ($_REQUEST[hd_wyjazd_rp]=='P')) $d_km=$_REQUEST[km];
	
	$d_cw = 0;	// czas wykonywania
	if ($_REQUEST[czas_wykonywania_h]!='') { $h_na_m = (int) $_REQUEST[czas_wykonywania_h]*60; }
	if ($_REQUEST[czas_wykonywania_m]!='') { $m_na_m = (int) $_REQUEST[czas_wykonywania_m]; }
	
	$d_cw=$h_na_m+$m_na_m;

	$r2 = mysql_query("SELECT up_working_time,up_working_time_alternative,up_working_time_alternative_start_date,up_working_time_alternative_stop_date FROM $dbname.serwis_komorki WHERE (up_id='$_POST[up_list_id]') LIMIT 1", $conn_hd) or die($k_b);
	list($wt1,$wta1,$wtastart,$wtastop)=mysql_fetch_array($r2);
	
	if ($debug) echo "wt: $wt1 <br />wta: $wta1 <br />wta1: $wtastart <br />wta2: $wtastop<br />";
	
	if ($debug) {
		echo "<hr />";
		echo "nowy_czas_rozpoczecia : $_REQUEST[nowy_czas_rozpoczecia]<br />";
		echo "nowa_data_rozpoczecia : $_REQUEST[nowa_data_rozpoczecia]<br />";
		echo "test_dayname : $_REQUEST[test_dayname]<br />";
		echo "test_gSTART : $_REQUEST[test_gSTART]<br />";
		echo "test_gSTOP : $_REQUEST[test_gSTOP]<br />";
		echo "test_przesun : $_REQUEST[test_przesun]<br />";
		echo "<hr />";
	}
	
	$dni = explode(";",$wt1);
	$pn = explode("@",$dni[0]);
	$wt = explode("@",$dni[1]);
	$sr = explode("@",$dni[2]);
	$cz = explode("@",$dni[3]);
	$pt = explode("@",$dni[4]);
	$so = explode("@",$dni[5]);
	$ni = explode("@",$dni[6]);
	
	$week = $wt1;
	
	if ($debug) echo "<br />week: $week";
	
	$ciag  = $pn[1]."".$wt[1]."".$sr[1]."".$cz[1]."".$pt[1]."".$so[1]."".$ni[1];

	$czy_jest_wt = strlen($pn[1]."".$wt[1]."".$sr[1]."".$cz[1]."".$pt[1]."".$so[1]."".$ni[1]);
	
	if (($czy_jest_wt==7) && ($ciag=='-------')) $czy_jest_wt = 0;
	
	//echo $ciag."<br />";
	$ciag_pn = str_replace("-","",$pn[1]);
	$ciag_pn = str_replace("wolne","",$pn[1]);
	
	$ciag_wt = str_replace("-","",$wt[1]);
	$ciag_wt = str_replace("wolne","",$wt[1]);

	$ciag_sr = str_replace("-","",$sr[1]);
	$ciag_sr = str_replace("wolne","",$sr[1]);

	$ciag_cz = str_replace("-","",$cz[1]);
	$ciag_cz = str_replace("wolne","",$cz[1]);

	$ciag_pt = str_replace("-","",$pt[1]);
	$ciag_pt = str_replace("wolne","",$pt[1]);

	$ciag_so = str_replace("-","",$so[1]);
	$ciag_so = str_replace("wolne","",$so[1]);

	$ciag_ni = str_replace("-","",$ni[1]);
	$ciag_ni = str_replace("wolne","",$ni[1]);
	
	/*
	echo "<br />".$ciag_pn;	echo "<br />".$ciag_wt;	echo "<br />".$ciag_sr;	echo "<br />".$ciag_cz;	echo "<br />".$ciag_pt;	echo "<br />".$ciag_so;	echo "<br />".$ciag_ni;
	*/

	if ((strlen($ciag_pn)<4) && (strlen($ciag_pn)!=0) && ($ciag_pn!='-')) $czy_jest_wt = 0;
	if ((strlen($ciag_wt)<4) && (strlen($ciag_wt)!=0) && ($ciag_wt!='-')) $czy_jest_wt = 0;
	if ((strlen($ciag_sr)<4) && (strlen($ciag_sr)!=0) && ($ciag_sr!='-')) $czy_jest_wt = 0;
	if ((strlen($ciag_cz)<4) && (strlen($ciag_cz)!=0) && ($ciag_cz!='-')) $czy_jest_wt = 0;
	if ((strlen($ciag_pt)<4) && (strlen($ciag_pt)!=0) && ($ciag_pt!='-')) $czy_jest_wt = 0;
	if ((strlen($ciag_so)<4) && (strlen($ciag_so)!=0) && ($ciag_so!='-')) $czy_jest_wt = 0;
	if ((strlen($ciag_ni)<4) && (strlen($ciag_ni)!=0) && ($ciag_ni!='-')) $czy_jest_wt = 0;

	$wtastart = date('Y')."-".substr($wtastart,5,5);
	$wtastop = date('Y')."-".substr($wtastop,5,5);	
	
	if ($debug) echo "<br />wtastart: $wtastart, wtastop: $wtastop";
	
	//if ($debug) echo ">>".$wtastart." ".$wtastop."<br />";
	if ((substr($wtastart,5,5)!='00-00') && (substr($wtastop,5,5)!='00-00')) {
		
		if (($_REQUEST[hddz]>=$wtastart) && ($_REQUEST[hddz]<=$wtastop)) {

			$dni = explode(";",$wta1);
			$pn = explode("@",$dni[0]);
			$wt = explode("@",$dni[1]);
			$sr = explode("@",$dni[2]);
			$cz = explode("@",$dni[3]);
			$pt = explode("@",$dni[4]);
			$so = explode("@",$dni[5]);
			$ni = explode("@",$dni[6]);
			
			$czy_jest_wta = strlen($pn[1]."".$wt[1]."".$sr[1]."".$cz[1]."".$pt[1]."".$so[1]."".$ni[1]);
			$ciag  = $pn[1]."".$wt[1]."".$sr[1]."".$cz[1]."".$pt[1]."".$so[1]."".$ni[1];
			
			if (($czy_jest_wta==7) && ($ciag=='-------')) $czy_jest_wta = 0;
			
			$ciag_pn = str_replace("-","",$pn[1]);
			$ciag_pn = str_replace("wolne","",$pn[1]);
			
			$ciag_wt = str_replace("-","",$wt[1]);
			$ciag_wt = str_replace("wolne","",$wt[1]);

			$ciag_sr = str_replace("-","",$sr[1]);
			$ciag_sr = str_replace("wolne","",$sr[1]);

			$ciag_cz = str_replace("-","",$cz[1]);
			$ciag_cz = str_replace("wolne","",$cz[1]);

			$ciag_pt = str_replace("-","",$pt[1]);
			$ciag_pt = str_replace("wolne","",$pt[1]);

			$ciag_so = str_replace("-","",$so[1]);
			$ciag_so = str_replace("wolne","",$so[1]);

			$ciag_ni = str_replace("-","",$ni[1]);
			$ciag_ni = str_replace("wolne","",$ni[1]);
	
			if ((strlen($ciag_pn)<4) && (strlen($ciag_pn)!=0) && ($ciag_pn!='-')) $czy_jest_wta = 0;
			if ((strlen($ciag_wt)<4) && (strlen($ciag_wt)!=0) && ($ciag_wt!='-')) $czy_jest_wta = 0;
			if ((strlen($ciag_sr)<4) && (strlen($ciag_sr)!=0) && ($ciag_sr!='-')) $czy_jest_wta = 0;
			if ((strlen($ciag_cz)<4) && (strlen($ciag_cz)!=0) && ($ciag_cz!='-')) $czy_jest_wta = 0;
			if ((strlen($ciag_pt)<4) && (strlen($ciag_pt)!=0) && ($ciag_pt!='-')) $czy_jest_wta = 0;
			if ((strlen($ciag_so)<4) && (strlen($ciag_so)!=0) && ($ciag_so!='-')) $czy_jest_wta = 0;
			if ((strlen($ciag_ni)<4) && (strlen($ciag_ni)!=0) && ($ciag_ni!='-')) $czy_jest_wta = 0;
			
			$week = $wta1;
			if ($debug) echo "<br />[]week: $week";
			
		}
	}	
		
	//if ($debug) echo $pn[1]."".$wt[1]."".$sr[1]."".$cz[1]."".$pt[1]."".$so[1]."".$ni[1];
	
	if ($czy_jest_wt==0) {
		echo "<h2>Dla komórki<br /><font color=white>$_REQUEST[up_list]</font><br />nie zdefiniowano godzin pracy.";
		
		if (($es_nr==$kierownik_nr) || ($is_dyrektor==1)) {
			echo "<input type=button class=buttons value='Wypełnij godziny pracy tej komórki' onClick=\"newWindow_r(800,600,'e_komorka.php?select_id=$_POST[up_list_id]'); return false;\" />";
		}
		
		if (($_REQUEST[kat_id]=='2') || ($_REQUEST[kat_id]=='6')) {
			echo "<br /><br />Do wyliczenia czasów rozpoczęcia i zakończenia użyte zostały wartości domyślne:<br />pn-pt:$gp_start_pn_pt-$gp_stop_pn_pt, so:$gp_start_so-$gp_stop_so, ni:$gp_start_ni-$gp_stop_ni ";
		}
		
		echo "</h2>";
		
		$week = "PN@$gp_start_pn_pt-$gp_stop_pn_pt;WT@$gp_start_pn_pt-$gp_stop_pn_pt;SR@$gp_start_pn_pt-$gp_stop_pn_pt;CZ@$gp_start_pn_pt-$gp_stop_pn_pt;PT@$gp_start_pn_pt-$gp_stop_pn_pt;SO@$gp_start_so-$gp_stop_so;NI@$gp_start_ni-$gp_stop_ni";
		
		if ($debug) echo "...".$week;
		
		$pn[1] = $gp_start_pn_pt.'-'.$gp_stop_pn_pt;
		$wt[1] = $gp_start_pn_pt.'-'.$gp_stop_pn_pt;
		$sr[1] = $gp_start_pn_pt.'-'.$gp_stop_pn_pt;
		$cz[1] = $gp_start_pn_pt.'-'.$gp_stop_pn_pt;
		$pt[1] = $gp_start_pn_pt.'-'.$gp_stop_pn_pt;
		$so[1] = $gp_start_so.'-'.$gp_stop_so;
		$ni[1] = $gp_start_ni.'-'.$gp_stop_ni;

	}
	
	if ($debug) echo "<br />[*]<font color=red>week: $week</font>";
	
	// modyfikacja godzin pracy (wynikająca z przesunięcia
	
	$WH_modyfied = 0;
	if ($awaria_z_przesunieciem==1) {
		if ($_REQUEST[kat_id]==6) {
			$_week_old = $week;
			
			$week = ModifyWorkingTime($week, $_REQUEST[nowy_czas_rozpoczecia], $_REQUEST[nowa_data_rozpoczecia], $_REQUEST[test_dayname], $_REQUEST[test_gSTART], $_REQUEST[test_gSTOP], $_REQUEST[test_przesun]);
		//	echo "$week, $_REQUEST[nowy_czas_rozpoczecia], $_REQUEST[nowa_data_rozpoczecia], $_REQUEST[test_dayname], $_REQUEST[test_gSTART], $_REQUEST[test_gSTOP], $_REQUEST[test_przesun]";
			$_week_new = $week;
			
			if ($_week_old!=$_week_new) $WH_modyfied = 1;
			
			if ($debug) echo "<br />[*stare*]<font color=blue>old week: $_week_old</font>";
			if ($debug) echo "<br />[*nowe*]<font color=blue>week: $week</font>";
		}
	}
	
	//$DataGodzinaWpisu = $_REQUEST[hddz]." ".$_REQUEST[hdgz];
	
if ($_SESSION['zgloszenie_dodano_'.$_REQUEST[unique_nr1].'']!='poprawnie') {					

	$DataWpisu = $_REQUEST[hddz];
	
	$MinutDoRozpoczecia = 0;
	$MinutDoZakonczenia = 0;
	
	if (($_REQUEST[kat_id]=='2') || ($_REQUEST[kat_id]=='6')) {
		$r2 = mysql_query("SELECT up_kategoria FROM $dbname.serwis_komorki WHERE (up_id='$_POST[up_list_id]') LIMIT 1", $conn_hd) or die($k_b);
		list($kategoria_up)=mysql_fetch_array($r2);
		if (($kategoria_up=='1') || ($kategoria_up=='2')) {
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
	}
	
	switch ($_REQUEST[kat_id]) {
		case "6" : 	{	$DataRozpoczecia = AddHoursToDate($CzasNaRozpoczecie,$DataGodzinaWpisu)."";		
						$DataZakonczenia = AddHoursToDate($CzasNaZakonczenie,$DataGodzinaWpisu)."";
							
						$HoursToStart = $CzasNaRozpoczecie;
						$HoursToFinish = "8";
							
						$DW = $_REQUEST[hddz];
						$GW = $_REQUEST[hdgz];

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

							// LICZ CZAS ROZPOCZĘCIA 
							
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

							if ($debug) echo ">>>>>>>>>>>>>>>>>>>>>>>>>>>> DR:      # $DR #";
							
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
										$DZ = AddMinutesToDate($CNZmin,$temp_d." ".godzina_start1s($temp_d,$week, $serwis_working_time)); 
										break;
									} else {
										$CNZmin = ($CNZmin-$IMWdniu);
									}
								}
								
								if ($debug) echo ">>>".$DZ."<<1<<".$temp_d." ".godzina_start1s($temp_d,$week, $serwis_working_time)."";
								$i++;
							endwhile;
							
							$samaG = substr($DZ, 11,10);
							$DZ = $temp_d." ".$samaG;
							
							//echo $samaG;
							
							if ($debug) echo "<br /><br /># DZ: # $DZ #";
							$DataRozpoczecia = $DR;
							$DataZakonczenia = $DZ;
							break;
					}
		
		case "2" : 		if (($_REQUEST[podkat_id]=='2') || ($_REQUEST[podkat_id]=='5') || ($_REQUEST[podkat_id]=='7')) { // oprogramowanie 
						
							$y = 1;
							$max = 1;
							while (2==2):	
								$_next_working_hours = godzina_stop1(AddWorkingDays_UP($y,$DataWpisu,$week),$week);
								$DataRozpoczecia = AddWorkingDays_UP($y,$DataWpisu,$week)." ".$_next_working_hours."";
								//$DataZakonczenia = AddWorkingDays_UP("2","".$DataWpisu."",$week)." ".$_REQUEST[hdgz]."";	
								if (czy_pracuje(substr($DataRozpoczecia,0,10),$week)) break;
								$y++;			
							endwhile;
							
							$y = 1;
							$count = 1;
							$max = 5;
							while (2==2):	
								$_next_working_hours = godzina_stop1(AddWorkingDays_UP($y,$DataWpisu,$week),$week);								
								//$DataRozpoczecia = AddWorkingDays_UP($y$DataWpisu,$week)." ".$_next_working_hours."";
								if ($_next_working_hours!='0') {
									$DataZakonczenia = AddWorkingDays_UP($y,"".$DataWpisu."",$week)." ".$_next_working_hours."";	
									//echo "<br />NWH: $y: $_next_working_hours | DZ: $y: $DataZakonczenia";	ob_flush();		flush();
									if ((czy_pracuje(substr($DataZakonczenia,0,10),$week)) && ($count==$max)) { break; } else { $count++; $y++; }
								} else $y++;
								
								//echo "<br />koniec pętli: $y: $_next_working_hours";	ob_flush();		flush();
							endwhile;
								
						/*	$_next_working_hours = godzina_stop1(AddWorkingDays("1",$DataWpisu),$week);
							
							$DataRozpoczecia = AddWorkingDays("1",$DataWpisu)." ".$_next_working_hours."";
							$DataZakonczenia = AddWorkingDays("5","".$DataWpisu."")." ".$_REQUEST[hdgz]."";
						*/
							
							$DataGodzinaWpisu1 = $_REQUEST[hddz]." ".$_REQUEST[hdgz].":00";
							$MinutDoRozpoczecia = MinutesBetween($DataGodzinaWpisu1, $DataRozpoczecia, $week, $serwis_working_time);
							$MinutDoZakonczenia = MinutesBetween($DataGodzinaWpisu1, $DataZakonczenia, $week, $serwis_working_time);

							$HoursToStart = "-";
							$HoursToFinish = ""; $DaysToFinish = "5";
							
						}
						
						if (($_REQUEST[podkat_id]=='3') || ($_REQUEST[podkat_id]=='4')) {	// stacja robocza, serwer
	//echo "<br />---1.";	ob_flush();		flush();
								
							$y = 1;
							$max = 1;
							while (2==2):	
								$_next_working_hours = godzina_stop1(AddWorkingDays_UP($y,$DataWpisu,$week),$week);
								$DataRozpoczecia = AddWorkingDays_UP($y,$DataWpisu,$week)." ".$_next_working_hours."";
								if (czy_pracuje(substr($DataRozpoczecia,0,10),$week)) break;
								$y++;
							endwhile;
							
							
						//	echo "<br />2 pętla"; ob_flush();		flush();
							
							$y = 1;
							$count = 1;
							$max = 14;
							while (2==2):	
								$_next_working_hours = godzina_stop1(AddWorkingDays_UP($y,$DataWpisu,$week),$week);								
								//$DataRozpoczecia = AddWorkingDays_UP($y$DataWpisu,$week)." ".$_next_working_hours."";
								if ($_next_working_hours!='0') {
									$DataZakonczenia = AddWorkingDays_UP($y,"".$DataWpisu."",$week)." ".$_next_working_hours."";	
									//echo "<br />NWH: $y: $_next_working_hours | DZ: $y: $DataZakonczenia";	ob_flush();		flush();
									if ((czy_pracuje(substr($DataZakonczenia,0,10),$week)) && ($count==$max)) { break; } else { $count++; $y++; }
								} else $y++;
								
								//echo "<br />koniec pętli: $y: $_next_working_hours";	ob_flush();		flush();
							endwhile;
							
						/*
							$_next_working_hours = godzina_stop1(AddWorkingDays("1",$DataWpisu),$week);
							$DataRozpoczecia = AddWorkingDays("1",$DataWpisu)." ".$_next_working_hours."";
							$DataZakonczenia = AddWorkingDays("14","".$DataWpisu."")." ".$_REQUEST[hdgz]."";
						*/
						
							$DataGodzinaWpisu1 = $_REQUEST[hddz]." ".$_REQUEST[hdgz].":00";
							$MinutDoRozpoczecia = MinutesBetween($DataGodzinaWpisu1, $DataRozpoczecia, $week, $serwis_working_time);
							$MinutDoZakonczenia = MinutesBetween($DataGodzinaWpisu1, $DataZakonczenia, $week, $serwis_working_time);
							
							$HoursToStart = "-";
							$HoursToFinish = ""; $DaysToFinish = "14";
						}
						
						if (($_REQUEST[podkat_id]=='9') || ($_REQUEST[podkat_id]=='0')) { // urządzenia peryferyjne, WAN/LAN
						
							$y = 1;
							$max = 1;
							while (2==2):	
								$_next_working_hours = godzina_stop1(AddWorkingDays_UP($y,$DataWpisu,$week),$week);
								$DataRozpoczecia = AddWorkingDays_UP($y,$DataWpisu,$week)." ".$_next_working_hours."";
								//$DataZakonczenia = AddWorkingDays_UP("2","".$DataWpisu."",$week)." ".$_REQUEST[hdgz]."";	
								if (czy_pracuje(substr($DataRozpoczecia,0,10),$week)) break;
								$y++;		
							endwhile;
							
							$y = 1;
							$count = 1;
							$max = 14;
							while (2==2):	
								$_next_working_hours = godzina_stop1(AddWorkingDays_UP($y,$DataWpisu,$week),$week);								
								//$DataRozpoczecia = AddWorkingDays_UP($y$DataWpisu,$week)." ".$_next_working_hours."";
								if ($_next_working_hours!='0') {
									$DataZakonczenia = AddWorkingDays_UP($y,"".$DataWpisu."",$week)." ".$_next_working_hours."";	
									//echo "<br />NWH: $y: $_next_working_hours | DZ: $y: $DataZakonczenia";	ob_flush();		flush();
									if ((czy_pracuje(substr($DataZakonczenia,0,10),$week)) && ($count==$max)) { break; } else { $count++; $y++; }
								} else $y++;
								
								//echo "<br />koniec pętli: $y: $_next_working_hours";	ob_flush();		flush();
							endwhile;
							
						/*	
							$_next_working_hours = godzina_stop1(AddWorkingDays("1",$DataWpisu),$week);							
							$DataRozpoczecia = AddWorkingDays("1",$DataWpisu)." ".$_next_working_hours."";
							$DataZakonczenia = AddWorkingDays("14","".$DataWpisu."")." ".$_REQUEST[hdgz]."";
						*/
						
							$DataGodzinaWpisu1 = $_REQUEST[hddz]." ".$_REQUEST[hdgz].":00";
							$MinutDoRozpoczecia = MinutesBetween($DataGodzinaWpisu1, $DataRozpoczecia, $week, $serwis_working_time);
							$MinutDoZakonczenia = MinutesBetween($DataGodzinaWpisu1, $DataZakonczenia, $week, $serwis_working_time);
						
							$HoursToStart = "-";
							$HoursToFinish = ""; $DaysToFinish = "14";
						}
						
					
					break;
					
		default : $DataRozpoczecia=''; $DataZakonczenia='';
	}

	if ($debug) { echo "<font color=red>$awaria_z_przesunieciem</font>"; ob_flush(); }
	
	if ($awaria_z_przesunieciem==1) {
		$status_zgloszenia = '1';
		
		//$NowaDataGodzina = $NowaDataRozpoczecia." ".$NowyCzasRozpoczecia;
		$NowaDataGodzina = $_REQUEST[hddz]." ".$GW = $_REQUEST[hdgz].":00";						

		$DataRozpoczecia=$NowaDataRozpoczecia." ".$NowyCzasRozpoczecia;
		
			switch ($_REQUEST[kat_id]) {
			
				case "6" : 	{	$DataRozpoczecia = AddHoursToDate($CzasNaRozpoczecie,$DataGodzinaWpisu)."";		
								$DataZakonczenia = AddHoursToDate($CzasNaZakonczenie,$DataGodzinaWpisu)."";
									
								$HoursToStart = $CzasNaRozpoczecie;
								$HoursToFinish = "8";
									
								$DW = $_REQUEST[hddz];
								$GW = $_REQUEST[hdgz];

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

									// LICZ CZAS ROZPOCZĘCIA 
									
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

									if ($debug) echo ">>>>>>>>>>>>>>>>>>>>>>>>>>>> DR:      # $DR #";
									
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
												$DZ = AddMinutesToDate($CNZmin,$temp_d." ".godzina_start1s($temp_d,$week, $serwis_working_time)); 
												break;
											} else {
												$CNZmin = ($CNZmin-$IMWdniu);
											}
										}
										
										if ($debug) echo ">>>".$DZ."<<1<<".$temp_d." ".godzina_start1s($temp_d,$week, $serwis_working_time)."";
										$i++;
									endwhile;
									
									$samaG = substr($DZ, 11,10);
									$DZ = $temp_d." ".$samaG;
									
									//echo $samaG;
									
									if ($debug) echo "<br /><br /># DZ: # $DZ #";
									$DataRozpoczecia = $DR;
									$DataZakonczenia = $DZ;
									break;
							}
					
				case "2" : 		if (($_REQUEST[podkat_id]=='2') || ($_REQUEST[podkat_id]=='5') || ($_REQUEST[podkat_id]=='7')) {
				
								//	$DataRozpoczecia = AddHoursToDate(0,$NowaDataGodzina);	
								/*
									$y = 1;
									$max = 1;
									while (2==2):	
										$_next_working_hours = godzina_stop1(AddWorkingDays_UP($y,$DataRozpoczecia,$week),$week);
										$DataRozpoczecia = AddWorkingDays_UP($y,$DataRozpoczecia,$week)." ".$_next_working_hours."";
										//$DataZakonczenia = AddWorkingDays_UP("2","".$DataRozpoczecia."",$week)." ".$_REQUEST[hdgz]."";	
										if (czy_pracuje(substr($DataRozpoczecia,0,10),$week)) break;
										$y++;			
									endwhile;
								*/
									$y = 1;
									$count = 1;
									$max = 5;
									while (2==2):	
										$_next_working_hours = godzina_stop1(AddWorkingDays_UP($y,$DataRozpoczecia,$week),$week);								
										//$DataRozpoczecia = AddWorkingDays_UP($y$DataRozpoczecia,$week)." ".$_next_working_hours."";
										if ($_next_working_hours!='0') {
											$DataZakonczenia = AddWorkingDays_UP($y,"".$DataRozpoczecia."",$week)." ".$_next_working_hours."";	
											//echo "<br />NWH: $y: $_next_working_hours | DZ: $y: $DataZakonczenia";	ob_flush();		flush();
											if ((czy_pracuje(substr($DataZakonczenia,0,10),$week)) && ($count==$max)) { break; } else { $count++; $y++; }
										} else $y++;
										
										//echo "<br />koniec pętli: $y: $_next_working_hours";	ob_flush();		flush();
									endwhile;
										
								/*	$_next_working_hours = godzina_stop1(AddWorkingDays("1",$DataRozpoczecia),$week);
									
									$DataRozpoczecia = AddWorkingDays("1",$DataRozpoczecia)." ".$_next_working_hours."";
									$DataZakonczenia = AddWorkingDays("5","".$DataRozpoczecia."")." ".$_REQUEST[hdgz]."";
								*/
									
									$DataGodzinaWpisu1 = $NowaDataGodzina;
									$MinutDoRozpoczecia = MinutesBetween($DataGodzinaWpisu1, $DataRozpoczecia, $week, $serwis_working_time);
									$MinutDoZakonczenia = MinutesBetween($DataGodzinaWpisu1, $DataZakonczenia, $week, $serwis_working_time);

									$HoursToStart = "-";
									$HoursToFinish = ""; $DaysToFinish = "1";

								}
								
								if (($_REQUEST[podkat_id]=='3') || ($_REQUEST[podkat_id]=='4')) {
									
									//$DataZakonczenia = AddWorkingDays("14","".$DataRozpoczecia."")." ".$NowyCzasRozpoczecia."";
								/*	
									$DataRozpoczecia = AddHoursToDate(0,$NowaDataGodzina);	
									$y = 1;
									$max = 1;
									while (2==2):	
										$_next_working_hours = godzina_stop1(AddWorkingDays_UP($y,$DataRozpoczecia,$week),$week);
										$DataRozpoczecia = AddWorkingDays_UP($y,$DataRozpoczecia,$week)." ".$_next_working_hours."";
										//$DataZakonczenia = AddWorkingDays_UP("2","".$DataRozpoczecia."",$week)." ".$_REQUEST[hdgz]."";	
										if (czy_pracuje(substr($DataRozpoczecia,0,10),$week)) break;
										$y++;			
									endwhile;
								*/	
									$y = 1;
									$count = 1;
									$max = 14;
									while (2==2):	
										$_next_working_hours = godzina_stop1(AddWorkingDays_UP($y,$DataRozpoczecia,$week),$week);								
										//$DataRozpoczecia = AddWorkingDays_UP($y$DataRozpoczecia,$week)." ".$_next_working_hours."";
										if ($_next_working_hours!='0') {
											$DataZakonczenia = AddWorkingDays_UP($y,"".$DataRozpoczecia."",$week)." ".$_next_working_hours."";	
											//echo "<br />NWH: $y: $_next_working_hours | DZ: $y: $DataZakonczenia";	ob_flush();		flush();
											if ((czy_pracuje(substr($DataZakonczenia,0,10),$week)) && ($count==$max)) { break; } else { $count++; $y++; }
										} else $y++;
										
										//echo "<br />koniec pętli: $y: $_next_working_hours";	ob_flush();		flush();
									endwhile;
										
								/*	$_next_working_hours = godzina_stop1(AddWorkingDays("1",$DataRozpoczecia),$week);
									
									$DataRozpoczecia = AddWorkingDays("1",$DataRozpoczecia)." ".$_next_working_hours."";
									$DataZakonczenia = AddWorkingDays("5","".$DataRozpoczecia."")." ".$_REQUEST[hdgz]."";
								*/
									
									$DataGodzinaWpisu1 = $NowaDataGodzina;
									$MinutDoRozpoczecia = MinutesBetween($DataGodzinaWpisu1, $DataRozpoczecia, $week, $serwis_working_time);
									
									//echo "<hr />$DataGodzinaWpisu1, $DataRozpoczecia, $week, $serwis_working_time";
									
									$MinutDoZakonczenia = MinutesBetween($DataGodzinaWpisu1, $DataZakonczenia, $week, $serwis_working_time);

									//echo "$DataGodzinaWpisu1, $DataZakonczenia, $week, $serwis_working_time";
									
									$HoursToStart = "-";
									$HoursToFinish = ""; $DaysToFinish = "14";									
								}
								
								if (($_REQUEST[podkat_id]=='9') || ($_REQUEST[podkat_id]=='0')) {
								//	$DataRozpoczecia = AddHoursToDate(0,$NowaDataGodzina);	
								/*
									$y = 1;
									$max = 1;
									while (2==2):	
										$_next_working_hours = godzina_stop1(AddWorkingDays_UP($y,$DataRozpoczecia,$week),$week);
										$DataRozpoczecia = AddWorkingDays_UP($y,$DataRozpoczecia,$week)." ".$_next_working_hours."";
										//$DataZakonczenia = AddWorkingDays_UP("2","".$DataRozpoczecia."",$week)." ".$_REQUEST[hdgz]."";	
										if (czy_pracuje(substr($DataRozpoczecia,0,10),$week)) break;
										$y++;			
									endwhile;
								*/
									$y = 1;
									$count = 1;
									$max = 14;
									while (2==2):	
										$_next_working_hours = godzina_stop1(AddWorkingDays_UP($y,$DataRozpoczecia,$week),$week);								
										//$DataRozpoczecia = AddWorkingDays_UP($y$DataRozpoczecia,$week)." ".$_next_working_hours."";
										if ($_next_working_hours!='0') {
											$DataZakonczenia = AddWorkingDays_UP($y,"".$DataRozpoczecia."",$week)." ".$_next_working_hours."";	
											//echo "<br />NWH: $y: $_next_working_hours | DZ: $y: $DataZakonczenia";	ob_flush();		flush();
											if ((czy_pracuje(substr($DataZakonczenia,0,10),$week)) && ($count==$max)) { break; } else { $count++; $y++; }
										} else $y++;
										
										//echo "<br />koniec pętli: $y: $_next_working_hours";	ob_flush();		flush();
									endwhile;
										
								/*	$_next_working_hours = godzina_stop1(AddWorkingDays("1",$DataRozpoczecia),$week);
									
									$DataRozpoczecia = AddWorkingDays("1",$DataRozpoczecia)." ".$_next_working_hours."";
									$DataZakonczenia = AddWorkingDays("5","".$DataRozpoczecia."")." ".$_REQUEST[hdgz]."";
								*/
									
									$DataGodzinaWpisu1 = $NowaDataGodzina;
									
									$MinutDoRozpoczecia = MinutesBetween($DataGodzinaWpisu1, $DataRozpoczecia, $week, $serwis_working_time);
									$MinutDoZakonczenia = MinutesBetween($DataGodzinaWpisu1, $DataZakonczenia, $week, $serwis_working_time);
									//echo "<hr />$DataGodzinaWpisu1, $DataZakonczenia, $week, $serwis_working_time";

									$HoursToStart = "-";
									$HoursToFinish = ""; $DaysToFinish = "14";									
								}

							break;
			}
		
		//$DataZakonczenia=$DataRozpoczecia." ".$_REQUEST[hdgz];
	}
	
//	$OsobaPrzypisana = '';
	//echo "<br /> >>>>>>".$MinutDoRozpoczecia;
	//echo "<br /> >>>>>>".$MinutDoZakonczenia;
	
	if ($status_zgloszenia=='1') $OsobaPrzypisana=$currentuser;
	if ($status_zgloszenia=='2') $OsobaPrzypisana=$_REQUEST['PrzypiszDoOsobyValue'];
	if ($status_zgloszenia=='3') $OsobaPrzypisana=$currentuser;
	if ($status_zgloszenia=='9') $OsobaPrzypisana=$currentuser;
	if ($status_zgloszenia=='0') $OsobaPrzypisana=$currentuser;
	if ($status_zgloszenia=='3A') $OsobaPrzypisana=$currentuser;
	if ($status_zgloszenia=='3B') $OsobaPrzypisana=$currentuser;	
	if ($status_zgloszenia=='7') $OsobaPrzypisana=$currentuser;
	
	$dodatkowe1 = '';
	$dodatkowe2 = '';
	
	if (($_REQUEST[kat_id]=='2') && ($_REQUEST[podkat_id]=='0') && ($status_zgloszenia=='3A') && ($_REQUEST[numerzgloszenia]!='')) {
		$dodatkowe1 = $_REQUEST[numerzgloszenia];
	}
	
	$czy_synchr = 0;
	if ($_REQUEST[czy_synchronizowac]=='on') $czy_synchr = 1;

	$osoba_potwierdzajaca_zamkniecie='';
	if ($status_zgloszenia==9) $osoba_potwierdzajaca_zamkniecie=$_REQUEST[hd_opz];
	
	$parent_zgloszenie=0;
	if (($_REQUEST[parent_zgl]!='') && ($_REQUEST[parent_zgl]!=0)) $parent_zgloszenie = $_REQUEST[parent_zgl];

	$hd_naprawa_id=0;
	if (($_REQUEST[naprawa_id]!='') && ($_REQUEST[naprawa_id]!=0)) $hd_naprawa_id = $_REQUEST[naprawa_id];
	
	// domyślnie z poziomu nowego zgłoszenia nie ma możliwości powiązania go z naprawą
	//$hd_naprawa_id = 0;
	
	if ($_REQUEST[kat_id]=='6') $_REQUEST[priorytet_id]='4';
	
	if ($_REQUEST[zgl_reklamacyjne]=='1') {
		$rekl_czy_jest_reklamacyjne = 1;
		$rekl_nr_zgl_reklamowanego = $_REQUEST[zgl_reklamacyjne_do_zgl];
		$rekl_czy_utworzono_z_niego_reklamacyjne = 0;
		
		// ustwienie znacznika "1" (zgl_czy_ma_zgl_reklamacyjne) w zgłoszeniu dla którego utworzono zgłoszenie reklamacyjne
		$sql_rekl = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_czy_ma_zgl_reklamacyjne=1 WHERE ((zgl_unikalny_nr='$_REQUEST[zgl_reklamacyjne_unique]') and (zgl_nr='$rekl_nr_zgl_reklamowanego') and (belongs_to='$es_filia')) LIMIT 1";
		//echo $sql_rekl;
		$result_rekl = mysql_query($sql_rekl, $conn_hd) or die($k_b);
		
	} else {
		$rekl_czy_jest_reklamacyjne = 0;
		$rekl_nr_zgl_reklamowanego = 0;
		$rekl_czy_utworzono_z_niego_reklamacyjne = 0;
	}

	// ustelenie zmiennej: czy rozwiązany
	$czy_rozwiazany = 0;
	if ($_REQUEST[kat_id]=='1') $czy_rozwiazany = 1;
	if ($_REQUEST[kat_id]=='2') $czy_rozwiazany = 0;
	if ($_REQUEST[kat_id]=='3') $czy_rozwiazany = 1;
	if ($_REQUEST[kat_id]=='4') $czy_rozwiazany = 1;
	if ($_REQUEST[kat_id]=='5') $czy_rozwiazany = 1;
	if ($_REQUEST[kat_id]=='6') $czy_rozwiazany = 0;
	
	$czy_rozwiazany_data = '';
	if ($czy_rozwiazany==1) $czy_rozwiazany_data = $_REQUEST[hddz];
	
	// czy powiązane z wymianą podzespołów
	$czy_powiazane_z_wp = 0;

	$_tu = $_REQUEST[hdtu];
	if ($_tu == '') $_tu = 'KOI';
	
	$hd_ss_id = '-1';
	if (($_REQUEST[ss_id]!='') && ($_REQUEST[ss_id]>0)) $hd_ss_id = $_REQUEST[ss_id];
	//$_REQUEST[up_list]=toUpper($_REQUEST[up_list]);
		
	$sql = "INSERT INTO $dbname_hd.hd_zgloszenie VALUES ('','','$_REQUEST[hdnzhadim]','$unique_nr','$DataWpisu1','$GodzinaWpisu1','$_REQUEST[up_list]','$_REQUEST[hd_oz]','$_REQUEST[hdoztelefon]','$_REQUEST[hd_temat]','$_REQUEST[hd_tresc]','$_REQUEST[kat_id]','$_REQUEST[podkat_id]','$_REQUEST[sub_podkat_id]','$_REQUEST[priorytet_id]','$status_zgloszenia','$OsobaPrzypisana','$DataRozpoczecia','$DataZakonczenia',$MinutDoRozpoczecia,$MinutDoZakonczenia,$d_cw,$d_km,1,'$currentuser',$zz,'$osoba_potwierdzajaca_zamkniecie','$dodatkowe1','$dodatkowe2','','$DataWpisu1','$parent_zgloszenie',$czy_synchr,'-1',$hd_naprawa_id,'0','$_tu','$_REQUEST[kategoria_komorki]','$_REQUEST[przypisanie_jednostki]','$rekl_czy_jest_reklamacyjne','$rekl_nr_zgl_reklamowanego','$rekl_czy_utworzono_z_niego_reklamacyjne','$_REQUEST[hdds]',$czy_powiazane_z_wp,'$week',$czy_rozwiazany,'$czy_rozwiazany_data',0,0,0,0,0,0,$czy_wymagany_wyjazd,'$czy_wymagany_wyjazd_data','$czy_wymagany_wyjazd_osoba','$hd_ss_id','','',0,$es_filia)";
	
	//echo ">".$sql;
	//wyczyść ss_id w zgłoszeniu "parent"
	if (($parent_zgloszenie!='') && ($hd_ss_id>0)) {
		$wykonaj2 = mysql_query("UPDATE $dbname_hd.hd_zgloszenie SET zgl_sprzet_serwisowy_id = '-1' WHERE zgl_nr = '$parent_zgloszenie' LIMIT 1", $conn) or die($k_b);
	}

	//wyczyść naprawa_id w zgłoszeniu "parent"
	if (($parent_zgloszenie!='') && ($parent_zgloszenie>0)) {
		$wykonaj2 = mysql_query("UPDATE $dbname_hd.hd_zgloszenie SET zgl_naprawa_id = '-1' WHERE zgl_nr = '$parent_zgloszenie' LIMIT 1", $conn) or die($k_b);
	}
	
	//echo "<hr />$sql<hr />";
	
	if ($TrybDebugowania==1) ShowSQL($sql);
	
	//echo "$sql";
	$result = mysql_query($sql, $conn_hd) or die($k_b);
	
	// *************************

		$_SESSION['zgloszenie_dodano_'.$_REQUEST[unique_nr1].'']='poprawnie';

	// *************************

	// zapisanie awarii WAN do bazy 
	
	$DataGodzinaWpisu = $_REQUEST[hddz]." ".$_REQUEST[hdgz];
	
	if (($_REQUEST[kat_id]=='2') && ($_REQUEST[podkat_id]=='0') && ($status_zgloszenia=='3A') && ($_REQUEST[numerzgloszenia]!='')) {
		$sql_t = "INSERT INTO $dbname.serwis_awarie VALUES ('', '$_REQUEST[up_nazwa1]','$_REQUEST[up_wanport]','$DataGodzinaWpisu','','$dodatkowe1','$_REQUEST[up_ip1]','$currentuser','','0',$es_filia)";

		if ($TrybDebugowania==1) ShowSQL($sql_t);

		$result_t = mysql_query($sql_t, $conn) or die($k_b);
		
	}
	// koniec zapisywania awarii WAN do bazy

	// ustalenie numeru zgłoszenia (START)
		
	$r3 = mysql_query("SELECT zgl_id FROM $dbname_hd.hd_zgloszenie WHERE ((zgl_unikalny_nr='$unique_nr') and (belongs_to='$es_filia') and (zgl_osoba_rejestrujaca='$currentuser')) ORDER BY zgl_id DESC LIMIT 1", $conn_hd) or die($k_b);
	list($last_nr)=mysql_fetch_array($r3);
	// ustalenie numeru zgłoszenia (STOP)
	
	if ($last_nr=='0') {
		$r3 = mysql_query("SELECT zgl_id FROM $dbname_hd.hd_zgloszenie WHERE ((zgl_unikalny_nr='$unique_nr') and (zgl_osoba_rejestrujaca='$currentuser')) ORDER BY zgl_id DESC LIMIT 1", $conn_hd) or die($k_b);
		list($last_nr)=mysql_fetch_array($r3);
	
		if ($last_nr!='0') errorheader("Podczas nadawania numeru zgłoszenia wystąpił błąd.<br />Proszę skontaktować się z administratorem systemu, podając mu numer: <font color=white>$unique_nr ($last_nr)</font>");
	}
	
	$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_nr=$last_nr WHERE ((zgl_unikalny_nr='$unique_nr') and (belongs_to='$es_filia') and (zgl_osoba_rejestrujaca='$currentuser'))";
	
	$result = mysql_query($sql, $conn_hd) or die($k_b);
	
	// jeżeli utworzono nowe zgłoszenie na bazie innego (Awaria krytyczna -> Awaria zwykła lub Awaria zwykła -> Prace zlecone w ramach umowy) => skopiuj wiersz z tabeli hd_naprawy_powiazane dla nowego numeru zgłoszenia
	
	if ($hd_naprawa_id!=0) {
	
		$r3 = mysql_query("SELECT hdnp_powiazanie_data, hdnp_powiazanie_osoba, hdnp_zdiagnozowany, hdnp_oferta_wyslana, hdnp_akceptacja_kosztow, hdnp_zamowienie_wyslane, hdnp_zamowienie_zrealizowane, hdnp_gotowe_do_oddania, hdnp_naprawa_zakonczona FROM $dbname_hd.hd_naprawy_powiazane WHERE ((hdnp_zgl_id='$parent_zgloszenie') and (hdnp_naprawa_id='$hd_naprawa_id') and (belongs_to='$es_filia') and (hdnp_widoczne='1')) LIMIT 1", $conn_hd) or die($k_b);
		list($_hdnp_data, $_hdnp_os, $_zdi, $_ow, $_ak, $_zw, $_zz, $_gd, $_nz)=mysql_fetch_array($r3);
	
		$sql55 = "INSERT INTO $dbname_hd.hd_naprawy_powiazane VALUES ('',$last_nr,'$hd_naprawa_id','$_hdnp_data','$_hdnp_os',$_zdi, $_ow, $_ak, $_zw, $_zz, $_gd, $_nz,1,'$unique_nr',$es_filia)";
		
		if ($TrybDebugowania==1) ShowSQL($sql);
		$result1 = mysql_query($sql55, $conn_hd) or die($k_b);
		// zmiany w tabeli Naprawy
		$sql_t9="UPDATE $dbname.serwis_naprawa SET naprawa_hd_zgl_id  = '$last_nr' WHERE ((naprawa_id = '$_REQUEST[naprawa_id]') and (belongs_to=$es_filia)) LIMIT 1";
		$result = mysql_query($sql_t9, $conn) or die($k_b);
		
	}
	// koniec zapisywania zgłoszenia do tabeli hd_zgloszenie
	// zapisanie danych do tabeli hd_zgloszenie_szcz (START)
} // koniec bloku z zapisywaniem zgłoszenia		<<<

if ($_SESSION['zgloszenie_szcz_dodano_'.$_REQUEST[unique_nr1].'']!='poprawnie') {

$bylwyjazd=0;
if ($_REQUEST[PozwolWpisacKm]=='on') $bylwyjazd = 1;

$d_cz_wyjazdu = 0;	// czas trwania wyjazdu
if ($bylwyjazd==1) {
	if ($_REQUEST[czas_przejazdu_h]!='') { $h_na_m = (int) $_REQUEST[czas_przejazdu_h]*60; }
	if ($_REQUEST[czas_przejazdu_m]!='') { $m_na_m = (int) $_REQUEST[czas_przejazdu_m]; }		
	$d_cz_wyjazdu=$h_na_m+$m_na_m;
}

$dddd = date("Y-m-d H:i:s");
//$osobaprzypisana='';
//if (($_REQUEST[status_id]=='2') || ($_REQUEST[status_id]=='3') || ($_REQUEST[status_id]=='9')) $osobaprzypisana=$currentuser;

$CzasStartStop='';

switch ($status_zgloszenia) {
	case "1"	: $CzasStartStop='START'; 	break;
	case "2"	: $CzasStartStop='START'; 	break;
	case "3"	: $CzasStartStop='START'; 	break;
	case "4"	: $CzasStartStop='STOP';  	break;
	case "5"	: $CzasStartStop='START';  	break;
	case "6"	: $CzasStartStop='START';  	break;
	case "7"	: $CzasStartStop='START';  	break;
	case "8"	: $CzasStartStop='START';  	break;
	case "9"	: $CzasStartStop='STOP';  	break;
	case "3A"	: $CzasStartStop='STOP';  	break;
	case "3B"	: $CzasStartStop='START';	break;
}
	
if (($DataRozpoczecia!='') && ($DataZakonczenia!='') && ($_REQUEST[kat_id]=='2') ) {
	$CzasStartStop = 'START';
}

if ($awaria_z_przesunieciem==1) {
	$CzasStartStop = 'STOP';
}

// jeżeli zaznaczono opcję wyślij email do koordynatora
$emailSend = 0;

	if ($_REQUEST[WyslijEmailCheckbox]=='on') {
	
		if ($TrybDebugowania==1) ShowSQL("SELECT up_umowa_id FROM $dbname.serwis_komorki WHERE ((up_id='$_REQUEST[up_list_id]') and (belongs_to='$es_filia')) LIMIT 1");
	
		$r3 = mysql_query("SELECT up_umowa_id FROM $dbname.serwis_komorki WHERE ((up_id='$_REQUEST[up_list_id]') and (belongs_to='$es_filia')) LIMIT 1", $conn) or die($k_b);
		//echo "SELECT up_umowa_id FROM $dbname.serwis.serwis_komorki WHERE ((up_id='$_REQUEST[up_list_id]') and (belongs_to='$es_filia')) LIMIT 1";
		list($umowaid)=mysql_fetch_array($r3);
		
		if ($TrybDebugowania==1) ShowSQL("SELECT umowa_koordynator, umowa_koordynator_email FROM $dbname.serwis_umowy WHERE ((umowa_id='$umowaid') and (belongs_to='$es_filia')) LIMIT 1");
		
		$r4 = mysql_query("SELECT umowa_koordynator, umowa_koordynator_email FROM $dbname.serwis_umowy WHERE ((umowa_id='$umowaid') and (belongs_to='$es_filia')) LIMIT 1", $conn) or die($k_b);
		//echo "SELECT umowa_koordynator, umowa_koordynator_email FROM $dbname.serwis_umowy WHERE ((umowa_id='$umowaid') and (belongs_to='$es_filia')) LIMIT 1";
		list($koord, $koord_email)=mysql_fetch_array($r4);
		
		if ($TrybDebugowania==1) ShowSQL("SELECT hd_kategoria_opis FROM $dbname_hd.hd_kategoria WHERE (hd_kategoria_nr='$_POST[kat_id]') LIMIT 1");
		
		$r1 = mysql_query("SELECT hd_kategoria_opis FROM $dbname_hd.hd_kategoria WHERE (hd_kategoria_nr='$_POST[kat_id]') LIMIT 1", $conn_hd) or die($k_b);list($kat_opis)=mysql_fetch_array($r1);

		if ($TrybDebugowania==1) ShowSQL("SELECT hd_podkategoria_opis FROM $dbname_hd.hd_podkategoria WHERE (hd_podkategoria_nr='$_POST[podkat_id]') LIMIT 1");
		
		$r2 = mysql_query("SELECT hd_podkategoria_opis FROM $dbname_hd.hd_podkategoria WHERE (hd_podkategoria_nr='$_POST[podkat_id]') LIMIT 1", $conn_hd) or die($k_b);list($podkat_opis)=mysql_fetch_array($r2);
			
		//	$koord_email = 'sebastian.majewski@postdata.pl';
			
		if ($koord_email!='') {
			// wyślij email
			$temat_maila = "Zarejestrowano nowe zgłoszenie o nr $last_nr";
			//$tresc_maila = "Do bazy dodano nowe zgłoszenie nr $last_nr\n";
			$tresc_maila.= "Osoba przyjmująca zgłoszenie : $currentuser\n";
			$tresc_maila.= "Data przyjęcia zgłoszenia : $DataGodzinaWpisu\n";
			$tresc_maila.= "Kategoria zgłoszenia : $kat_opis\n";
			$tresc_maila.= "Podkategoria zgłoszenia : $podkat_opis\n";
			$tresc_maila.= "Temat zgłoszenia: $_REQUEST[hd_temat]\n";
			$tresc_maila.= "Treść zgłoszenia : $_REQUEST[hd_tresc]\n";
			$tresc_maila.= "Osoba zgłaszająca : $_REQUEST[hd_oz] ($_REQUEST[hdoztelefon])\n";
			$tresc_maila.= "\n\n";
			
			if ($awaria_z_przesunieciem==1) {
				$tresc_maila.= "Ustalona data rozpoczęcia zgłoszenia : $_REQUEST[nowa_data_rozpoczecia] !!!\n";
			}
			
			$tresc_maila.= "Mail został wygenerowany automatycznie - proszę na niego nie odpisywać";
			
			if (smtpmailer($koord_email, 'helpdesk-lodz@postdata.pl', 'Helpdesk - O/Łódź', $temat_maila, $tresc_maila, $last_nr)) {
				echo "<h3>Email został wysłany do $koord ($koord_email)</h3>";
				$emailSend = 1;
			}
			if (!empty($error)) echo $error;
			
			//echo "email pójdzie do : $koord ($koord_email)";
		
		} else { 
			echo "<h2>Email do koordynatora nie został wysłany, gdyż nie został zdefiniowany w bazie umów</h2>";
		}
		
	}
	
$przejechane_km = $_REQUEST[km];
if (($_REQUEST[km]==0) || ($_REQUEST[km]=='')) $przejechane_km = 0;

if ($_REQUEST[hd_wyjazd_rp]=='S') $przejechane_km = 0;

//if ($awaria_z_przesunieciem==1) {
//	$wyk_czyn = '<b>Zmiana terminu zakończenia:</b><br />rejestracja zgłoszenia';
//} else {
$wyk_czyn = "rejestracja zgłoszenia<br /><br />".$_REQUEST[hd_tresc];
//if ($status_zgloszenia=='9') $wyk_czyn .= "<br /><br />".$_REQUEST[hd_tresc];
//}

if ($_REQUEST[UstalonaDataZakonczeniaCheck]=='on') {
	$przesunieta_data_rozpoczecia = $_REQUEST[nowa_data_rozpoczecia]." ".$_REQUEST[nowy_czas_rozpoczecia];
	$przesuniecie_osoba_z_poczty = $_REQUEST[hd_opp];
} else {
	$przesunieta_data_rozpoczecia = '';
	$przesuniecie_osoba_z_poczty = '';
}

$Zdiagnozowany = '9';
if (($_REQUEST[kat_id]=='2') && (($status_zgloszenia=='3') || ($status_zgloszenia=='7') || ($status_zgloszenia=='3A') || ($status_zgloszenia=='3B'))) 
	$Zdiagnozowany = $_REQUEST[SelectZdiagnozowany];

$AkceptacjaKosztow = '9';
if ($_REQUEST[SelectAkceptacjaKosztow]!=9) $AkceptacjaKosztow = $_REQUEST[SelectAkceptacjaKosztow];

$NrZglGdansk = $_REQUEST[hdnrzglgdansk];

if ($_REQUEST[WieleOsobCheck]=='on') {
	// lista dodatkowych osób wykonujących krok
	$DodatkoweOsoby = nl2br2($_REQUEST[lista_osob]);
	//$jeden_dodatkowy = explode(", ", $DodatkoweOsoby);
	$DodatkoweOsoby = str_replace(';', ',', $DodatkoweOsoby);
	$ile_dodatkowych = substr_count($DodatkoweOsoby,', ')-1;
} else $DodatkoweOsoby = '';
	
$sql = "INSERT INTO $dbname_hd.hd_zgloszenie_szcz VALUES ('',$last_nr,'$unique_nr',1,'$CzasStartStop',$d_cw,'$DataGodzinaWpisu','$status_zgloszenia','$wyk_czyn','$OsobaPrzypisana','$DodatkoweOsoby',$emailSend,9,9,$bylwyjazd,'$dddd','$currentuser',1,$przejechane_km,$awaria_z_przesunieciem,'$przesunieta_data_rozpoczecia','$przesuniecie_osoba_z_poczty','$Zdiagnozowany','$AkceptacjaKosztow','$NrZglGdansk','','$_REQUEST[hdds]',$czy_rozwiazany,$d_cz_wyjazdu,$es_filia)";
//echo "$sql";

if ($TrybDebugowania==1) ShowSQL($sql);
$result = mysql_query($sql, $conn_hd) or die($k_b);
	
	// *************************

		$_SESSION['zgloszenie_szcz_dodano_'.$_REQUEST[unique_nr1].'']='poprawnie';

	// *************************
	
if ($TrybDebugowania==1) ShowSQL("SELECT zgl_szcz_id FROM $dbname_hd.hd_zgloszenie_szcz WHERE ((zgl_szcz_unikalny_numer='$unique_nr') and (belongs_to='$es_filia') and (zgl_szcz_osoba_wykonujaca_krok='$currentuser')) ORDER BY zgl_szcz_id DESC LIMIT 1");

//$r3 = mysql_query("SELECT zgl_szcz_id FROM $dbname_hd.hd_zgloszenie_szcz WHERE ((zgl_szcz_unikalny_numer='$unique_nr') and (belongs_to='$es_filia') and (zgl_szcz_osoba_wykonujaca_krok='$currentuser')) ORDER BY zgl_szcz_id DESC LIMIT 1", $conn_hd) or die($k_b);
//list($last_nr_szcz)=mysql_fetch_array($r3);

// jeżeli był wyjazd (START)
if ($bylwyjazd==1) {
	$trasaw = $_REQUEST[trasa];
	$wdata = $_REQUEST[hd_wyjazd_data];
	
	if ($_REQUEST[hd_wyjazd_rp]=='S') {
		$trasaw = 'wyjazd samochodem służbowym';
		$wdata = $_REQUEST[hddz];
	}
	
	$sql = "INSERT INTO $dbname_hd.hd_zgloszenie_wyjazd VALUES ('','$unique_nr','$wdata','$trasaw',$d_km,'$currentuser','$_REQUEST[hd_wyjazd_rp]',1,$d_cz_wyjazdu,$es_filia)";
//	echo "$sql";
	if ($TrybDebugowania==1) ShowSQL($sql);
	$result = mysql_query($sql, $conn_hd) or die($k_b);
}
// jeżeli był wyjazd (STOP)

}

// zapisanie danych do tabeli hd_zgloszenie_szcz (STOP)

	$r3 = mysql_query("SELECT zgl_id FROM $dbname_hd.hd_zgloszenie WHERE ((zgl_unikalny_nr='$unique_nr') and (belongs_to='$es_filia') and (zgl_osoba_rejestrujaca='$currentuser')) ORDER BY zgl_id DESC LIMIT 1", $conn_hd) or die($k_b);
	list($last_nr)=mysql_fetch_array($r3);
	
	// ==========================================================
	// sprawdzenie czy dodano pierwszy krok do nowego zgłoszenia
	// ==========================================================
	
	$r3 = mysql_query("SELECT zgl_szcz_unikalny_numer FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$last_nr') and (belongs_to='$es_filia') LIMIT 1", $conn_hd) or die($k_b);
	list($unikalny_nr)=mysql_fetch_array($r3);
	
	if ($unikalny_nr=='') {
		errorheader("Wystąpił błąd podczas zapisywania zgłoszenia do bazy");
		startbuttonsarea("center");
		
		echo "<input class=buttons type=button onClick=\"newWindow_r(600,150,'hd_u_bledne_zgloszenie.php?zglnr=$last_nr&refresh_parent=1');\" value='Spróbuj ponownie zapisać zgłoszenie' />";
		
		echo "<span style='float:right'>";
			addbuttons("zamknij");
		echo "</span>";
	
		endbuttonsarea();
		echo "</body></html>";
		exit;
		
	}
	
	//echo "<h3>Informacje o wpisanym zgłoszeniu</h3>";
	echo "<h5>Zgłoszeniu nadano numer: <b>$last_nr";
	if ($last_nr=='0') echo "<font color=red><a title='Wystąpił błąd podczas nadawania numeru zgłoszenia'> ! </a></font>";
		
	echo "</b></h5>";
	starttable();
	//echo "<table width=790 border=0 cellspacing=0 cellpadding=0>";
		tbl_empty_row(2);
		
		tr_();	td_("150;rt;Data zgłoszenia;"); 		_td(); 	td_(";;;");		echo "<b>$DataGodzinaWpisu</b>";	
			echo "<span style='float:right'>";
				echo "<input class=buttons type=button onClick=\"newWindow_r(800,600,'hd_potwierdzenie.php?id=$last_nr&nr=$last_nr&pdata=".date('Y-m-d')."');\" style='font-weight:bold' value='Drukuj potwierdzenie' />";
			echo "</span>";
		_td(); 	_tr();
		if ($_REQUEST[hdnzhadim]!='') {
			tr_();	td_("150;r;Numer zgłoszenia HDIM;"); 	_td(); 	td_(";;;");		echo "<b>$_REQUEST[hdnzhadim]</b>";	_td(); 	_tr();
		}
		tr_();	td_("150;rt;Komórka;"); 				_td(); 	td_(";;;");		echo "<b>$_REQUEST[up_list]</b>";	
		
				echo "<span style='float:right'>";
				//if ($_ip!='') echo "<input type=button class=buttons onclick=\"newWindow(500,200,'hd_p_pokaz_ip.php?komorkanazwa=".urlencode($temp_komorka)."');return false;\" value='Adres IP'/>";
				//if ($_tel!='') echo "<input type=button class=buttons onclick=\"newWindow(500,200,'hd_p_pokaz_tel.php?komorkanazwa=".urlencode($temp_komorka)."');return false;\" value='Telefon'/>";
				if ($temp_zgl_komorka_working_hours!='') echo "<input type=button class=buttons title=\"Obowiązujące w momencie rejestracji zgłoszenia godziny pracy komórki\" onclick=\"newWindow(500,300,'hd_p_pokaz_godziny.php?komorkanazwa=".urlencode($temp_komorka)."&wa=".urlencode($temp_zgl_komorka_working_hours)."');return false;\" value='Zapisane w zgłoszeniu godziny pracy'/>";
				
			echo "</span>";
			
		_td(); 	_tr();
		tr_();	td_("150;r;Typ usługi;"); 				_td(); 	td_(";;;");		echo "<b>$_tu</b>";	_td(); 	_tr();
		
		tr_();	td_("150;rt;Osoba zgłaszające;"); 	_td(); 	td_(";;;");		
		echo "<b>$_REQUEST[hd_oz] </b>";
		
		if ($_REQUEST[hdoztelefon]!='') echo "| Telefon kontaktowy: <b>";
		
		echo "<span id=UpdateTelefon>";
		echo $_REQUEST[hdoztelefon];
		echo "</span>";
		
		echo "</b>";	
			echo "<span id=NrZaktualizowany style=display:none>";
			echo "&nbsp;<i>(zaktualizowano)</i>";
			echo "</span>";
			// jeżeli zaznaczono "zapamiętaj dane" - zapisz dane osoby zgłaszającej do bazy
			//if ($_POST['zapamietaj_oz']=='on') {
				
				//echo "[BŁĄD NR: $_POST[up_list_id]]";
				// sprawdź czy nie ma już takiego wpisu (jeżeli tak - pomijamy zapisanie
				//echo "SELECT hd_komorka_pracownicy_id, hd_komorka_pracownicy_telefon,hd_serwis_komorka_id FROM $dbname_hd.hd_komorka_pracownicy WHERE ((hd_komorka_pracownicy_nazwa='$_POST[hd_oz]') and (hd_serwis_komorka_id=$_POST[up_list_id]))";
				//echo "SELECT hd_komorka_pracownicy_id, hd_komorka_pracownicy_telefon,hd_serwis_komorka_id FROM $dbname_hd.hd_komorka_pracownicy WHERE ((hd_komorka_pracownicy_nazwa='$_POST[hd_oz]') and (hd_serwis_komorka_id=$_POST[up_list_id]))";
				
				$result_k = mysql_query("SELECT hd_komorka_pracownicy_id, hd_komorka_pracownicy_telefon,hd_serwis_komorka_id FROM $dbname_hd.hd_komorka_pracownicy WHERE ((hd_komorka_pracownicy_nazwa='$_POST[hd_oz]') and (hd_serwis_komorka_id=$_POST[up_list_id]))", $conn_hd) or die($k_b);				
				list($oz_id, $oz_tel, $up_id)=mysql_fetch_array($result_k);
				if ($oz_id>0) {
				
					echo "<div id=warning1 style=\"display:'' style='background:red;' \" />";
					// jeżeli jest inny nr telefonu - daj możliwość jego aktualizacji
					if (($oz_tel!=$_POST[hdoztelefon]) && ($_POST[hdoztelefon]!='')) {
						echo "<br /><h3 style='font-weight:normal'><b>$_REQUEST[hd_oz]</b> jest już w bazie.<br />";
						nowalinia();						
						if ($oz_tel!='') {
							echo "Ma przypisany numer telefonu : <b>$oz_tel</b><br />";
						} else {
							echo "<font color=red><b>Ma przypisany pusty numer telefonu - wymagana jest aktualizacja</b></font><br />";
						}
						nowalinia();
						
						echo "<input type=button class=buttons value='Aktualizuj numer telefonu $_POST[hdoztelefon] dla tej osoby' onClick=\"$('#UpdateTelefon').load('hd_update_oz.php?ozid=$oz_id&nnt=".urlencode($_POST[hdoztelefon])."&randval='+ Math.random()); \" />";

						echo "</h3>";
					}
					echo "</div>";
						
				} else 
				{
						$sql_a = "INSERT INTO $dbname_hd.hd_komorka_pracownicy VALUES ('', $_POST[up_list_id],'$_POST[hd_oz]','$_POST[hdoztelefon]',0,'$currentuser',$es_filia)";
						if (mysql_query($sql_a, $conn_hd)) { 
							?><script> //opener.location.reload(true); //self.close();  </script><?php
						} else {
							?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
						}
				}	
		//	}		
		_td(); 	_tr();
		tr_();	td_("150;r;Temat zgłoszenia;"); 		_td(); 	td_(";;;");		echo "<b>".cleanup($_REQUEST[hd_temat])."</b>";	_td(); 	_tr();
		tr_();	td_("150;rt;Treść zgłoszenia;"); 		_td(); 	
		td_(";;;");
			echo "<b>".cleanup(nl2br(wordwrap($_REQUEST[hd_tresc], 100, "<br />")))."</b>";
		_td(); 	_tr();
		
			$r1 = mysql_query("SELECT hd_kategoria_opis FROM $dbname_hd.hd_kategoria WHERE (hd_kategoria_nr='$_POST[kat_id]') LIMIT 1", $conn_hd) or die($k_b);list($kat_opis)=mysql_fetch_array($r1);

			$r2 = mysql_query("SELECT hd_podkategoria_opis FROM $dbname_hd.hd_podkategoria WHERE (hd_podkategoria_nr='$_POST[podkat_id]') LIMIT 1", $conn_hd) or die($k_b);list($podkat_opis)=mysql_fetch_array($r2);

		//	$r3 = mysql_query("SELECT hd_priorytet_opis FROM $dbname_hd.hd_priorytet WHERE (hd_priorytet_id='$_POST[priorytet_id]') LIMIT 1", $conn_hd) or die($k_b);list($priorytet_opis)=mysql_fetch_array($r3);

			$r4 = mysql_query("SELECT hd_status_opis FROM $dbname_hd.hd_status WHERE (hd_status_nr='$status_zgloszenia') LIMIT 1", $conn_hd) or die($k_b);list($status_opis)=mysql_fetch_array($r4);
				
		tr_();	td_("150;r;Kategoria;"); 	_td(); 	td_(";;;");		
			if (($_REQUEST[kat_id]=='2') || ($_REQUEST[kat_id]=='6')) echo "<font color=red>";
				echo "<b>$kat_opis</b>";	
			if (($_REQUEST[kat_id]=='2') || ($_REQUEST[kat_id]=='6')) echo "</font>";
		_td(); 	_tr();
		
		tr_();	td_("150;r;Podkategoria;"); _td(); 	td_(";;;");		
			if (($_REQUEST[kat_id]=='2') || ($_REQUEST[kat_id]=='6')) echo "<font color=red>";
				echo "<b>$podkat_opis</b>";	
			if (($_REQUEST[kat_id]=='2') || ($_REQUEST[kat_id]=='6')) echo "</font>";
		_td(); 	_tr();
		
		tr_();	td_("150;r;Podkategoria (poziom 2);"); _td(); 	td_(";;;");		
			if ($_REQUEST[sub_podkat_id]=='') $_REQUEST[sub_podkat_id] = 'brak';
			echo "<b>$_REQUEST[sub_podkat_id]</b>";	
		_td(); 	_tr();
		
/*		

		tr_();	td_("150;r;Priorytet;"); 	_td(); 	td_(";;;");		
			if ($_REQUEST[kat_id]=='2') echo "<font color=red>";
			echo "<b>$priorytet_opis</b>";	
			
			echo "</font>";
		_td(); 	_tr();
*/	
	if (($DataRozpoczecia!='') && ($DataZakonczenia!='') && (($_REQUEST[kat_id]=='2') || ($_REQUEST[kat_id]=='6')) && ($_REQUEST[status_id]!=9)) {
			tbl_empty_row(2);
			tr_();	
				td_("150;r;Max. data rozpoczęcia działań<br />(zgodnie z umową);"); 		_td(); 	
				td_(";;;");		
					if ( ($_REQUEST[kat_id]=='2') || ($_REQUEST[kat_id]=='6') ) echo "<font color=red>";
					echo "<b>".substr($DataRozpoczecia,0,16)."</b>";	
				
					if ($awaria_z_przesunieciem==0) {
						if (($HoursToStart!='') && ($HoursToStart!='-')) { 
							echo " [ +".$HoursToStart." godzin ]";
						} else echo "";//echo " następnego dnia roboczego rano";
					} else {
						echo " - ustalona z użytkownikiem: <b>$_REQUEST[hd_opp]</b>";
					}
					
					if (($awaria_z_przesunieciem==1) && ($_REQUEST[kat_id]=='6')) {
						//echo "<br /><font color=red>Zmodyfikowano godziny pracy komórki</font>";
					}
					
					echo "</font>";
				_td(); 	
			_tr();
			
			tr_();	
				td_("150;r;Max. data usunięcia awarii<br />(zgodnie z umową);"); 		_td(); 	
				td_(";;;");		
					if ( ($_REQUEST[kat_id]=='2') || ($_REQUEST[kat_id]=='6') ) echo "<font color=red>";
					echo "<b>".substr($DataZakonczenia,0,16)."</b>"; 
					echo "</font>";
				_td(); 	
			_tr();			
			tbl_empty_row(2);
	
	}
		
		tr_();	td_("150;r;Status;"); 		_td(); 	td_(";;;");		
			
			echo "<b>$status_opis</b>";
			echo "&nbsp;";
			if ($_REQUEST[numerzgloszenia]!='')	echo "| numer zgłoszenia awarii w Orange : $_REQUEST[numerzgloszenia]";
		_td(); 	_tr();

	if (($status_zgloszenia==9) && ($d_cw!=0)) {
		tr_();	td_("150;r;Czas wykonywania;"); 		_td(); 	td_(";;;");		echo "<b>$d_cw minut</b>";	_td(); 	_tr();
	}
	
	if (($_REQUEST[PozwolWpisacKm]=='on') && ($_REQUEST[hd_wyjazd_rp]=='P')) {
	tbl_empty_row(2);
		//tr_();	td_("150;r;Trasa wyjazdu;"); 			_td(); 	td_(";;;");		echo "<b>$_REQUEST[trasa]</b>";	_td(); 	_tr();
		tr_();	td_("150;r;Przejechane kilometry;"); 	_td(); 	td_(";;;");		echo "<b>$_REQUEST[km] km</b>";	_td(); 	_tr();
		
		tr_();	td_("150;rt;Trasa wyjazdu;"); 			_td(); 	td_(";;;");		echo "<textarea cols=96 rows=2  style='border:0px;background-color:transparent;font-family:tahoma;font-size:11px;font-weight:bold;' readonly tabindex=-1>$_REQUEST[trasa]</textarea>";	_td(); 	_tr();
		
	tbl_empty_row(2);
	}
		tbl_empty_row(2);
		tr_();	td_("150;r;Zgłoszenie zasadne;"); 		_td(); 	td_(";;;");		echo "<b>$_REQUEST[zasadne]</b>";	_td(); 	_tr();
				
	if ($czy_wymagany_wyjazd==1) {
		tr_();	td_("150;r;<font color=blue>Wymagany wyjazd</font>;"); 		_td(); 	td_(";;;");		echo "<font color=blue><b>TAK</b></font>";	_td(); 	_tr();	
	}
	
	tbl_empty_row(2);
	endtable();
	
	?><script></script><?php
	startbuttonsarea("right");
	oddziel();
	echo "<span style='float:left'>";
	
	if (($_REQUEST[kat_id]==6) && ($_REQUEST[status_id]==9)) {
		echo "&nbsp;&nbsp;<input id=nzaznbtz style='display:none' type=button class=buttons value=' Utwórz nowe zgłoszenie\nawarii zwykłej\nna bazie tego zgłoszenia ' onClick=\"if (confirm('Czy utworzyć nowe zgłoszenie w kategorii \'Awaria zwykła\', będące kontynuacją zgłoszenia o numerze ".$last_nr." ?')) { self.location.href='hd_d_zgloszenie.php?stage=".$_REQUEST[stage]."&parent_zgl=".$last_nr."'; }\">";
	}

	if (($_REQUEST[kat_id]==2) && ($_REQUEST[status_id]==9)) {
		echo "&nbsp;&nbsp;<input id=nzpzwrunbtz style='display:none' type=button class=buttons value=' Utwórz nowe zgłoszenie\nprac zleconych w ramach umowy\nna bazie tego zgłoszenia' onClick=\"if (confirm('Czy utworzyć nowe zgłoszenie w kategorii \'Prace zlecone w ramach umowy\', będące kontynuacją zgłoszenia o numerze ".$last_nr." ?')) { self.location.href='hd_d_zgloszenie.php?stage=".$_REQUEST[stage]."&parent_zgl=".$last_nr."'; }\">";
	}
	
	echo "</span>";
	
	if (($_REQUEST[parent_zgl]!='') && ($_REQUEST[parent_zgl]!=0)) {
		if ($_REQUEST[status_id]!=9) {
			echo "<input class=buttons style='font-weight:bold' type=button onClick=\"window.location.href='hd_o_zgloszenia.php?action=obsluga&nr=$last_nr&id=$last_nr'\" value='Obsługa zgłoszenia' />";
		}		
	} else {

		echo "<span style=float:left>";
			echo "<input class=buttons type=button onClick=\"window.location.href='hd_d_zgloszenie.php?stage=".$_REQUEST[stage]."'\" value='Nowe zgłoszenie' />";
			echo "<input class=buttons type=button onClick=window.location.href=\"hd_d_zgloszenie_s.php?stage=".$_REQUEST[stage]."&filtr=X-X-X-X\" value='Nowe zgłoszenie seryjne'>";
		echo "</span>";

		if ($_REQUEST[status_id]!=9) {
			echo "<input class=buttons style='font-weight:bold' type=button onClick=\"window.location.href='hd_o_zgloszenia.php?action=obsluga&nr=$last_nr&id=$last_nr'\" value='Obsługa zgłoszenia' />";
		}
		
		//echo "<br />";
		if ($parent_zgloszenie==0) {
			if ($_REQUEST[stage]==1) {
				if ($_REQUEST[fromtask]!=1) 
					echo "<input class=buttons type=button onClick=\"self.close(); if (opener) opener.location.href='hd_p_zgloszenia.php?showall=0&p5=BZ&sort=AD&page=1';\" value='Przeglądaj zgłoszenia' />";
			} else {
				echo "<input class=buttons type=button onClick=\"newWindow_r($_COOKIE[max_x],$_COOKIE[max_y],'hd_p_zgloszenia.php?showall=0&p5=BZ&sort=AD&page=1'); return false\" value='Przeglądaj zgłoszenia' />";
			}
			
		}
	}

	// zapis numeru wygenerowanego zgłoszenia helpdesk do pozycji zadania
	if (($_REQUEST[fromtask]=='1') && ($_REQUEST[zadanieid]!='')) {
		$sql_etapy = "UPDATE $dbname.serwis_zadania_pozycje SET pozycja_hd_zgloszenie=$last_nr WHERE (pozycja_id=$_REQUEST[zadanieid]) and (belongs_to='$es_filia') LIMIT 1";
		$result_etapy = mysql_query($sql_etapy, $conn) or die($k_b);		

		?>
		<script>
			if (opener) opener.document.getElementById('ref').style.display = '';
			if (opener) opener.document.getElementById('nap').style.display = 'none';
		</script>
	<?php		
		
	}
	
	// obsługa liczenia czasów na poszczególnych etapach
	// jeżeli zamykamy w pierwszym kroku zgłoszenie - dolicz czas wykonywania do czasu etapu zakończenia
	if ($_REQUEST[status_id]==9) {
		$sql_etapy = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_E3C=$d_cw WHERE ((zgl_unikalny_nr='$unique_nr') and (belongs_to='$es_filia')) LIMIT 1";
		$result_etapy = mysql_query($sql_etapy, $conn_hd) or die($k_b);
	}
		
	echo "<input class=buttons type=button id=zamknij_button onClick=\"self.close(); ";
	if (($_REQUEST[fromtask]=='1') && ($_REQUEST[zadanieid]!='')) {

	} else 	echo "if (opener) opener.location.reload(true);";

	echo " \" value=Zamknij />";
	
	
	
	startbuttonsarea("left");
/*	if (($_REQUEST[fromtask]=='1') && ($_REQUEST[zadanieid]!='')) {
		echo "<br />";
		echo "<div class=center>";
		echo "<input class=buttons type=button style='height:40px; font-weight:bold;' onClick=\"if (opener.opener) opener.opener.location.reload(true); if (opener) opener.close(); self.close(); newWindow_r(800,600,'hd_d_zgloszenie.php?stage=1&komorka=".urlencode($_REQUEST[up_list])."&osoba_zgl=".urlencode($_REQUEST[hd_oz])."&osoba_zgl_tel=".urlencode($_REQUEST[hdoztelefon])."&fromtask=1&zadanie=".urlencode($_REQUEST[zadanie])."&podkat_nr=".$_REQUEST[podkat_id]."&podkat_opis=".urlencode($_REQUEST[podkat_opis])."&zadanieid=".$_REQUEST[zadanieid]."&zid=".$_REQUEST[zid]."&osoba=".urlencode($_REQUEST[hd_oz])."'); \" value=\"Nowe zgłoszenie bliźniacze dla $_REQUEST[up_list]\" />";
		echo "</div>";	
	} else {
*/
		echo "<div style='float:left'>";
		echo "<input class=buttons type=button onClick=\"window.location.href='hd_d_zgloszenie.php?stage=".$_REQUEST[stage]."&komorka=".urlencode($_REQUEST[up_list])."&osoba_zgl=".urlencode($_REQUEST[hd_oz])."&osoba_zgl_tel=".urlencode($_REQUEST[hdoztelefon])."'\" value='Nowe zgłoszenie dla: $_REQUEST[up_list]' />";
		echo "</div>";
//	}
	endbuttonsarea();
	

} else {

$unique_nr1 = Date('YmdHis')."".rand_str();

session_register('session_komorka');
$_SESSION[session_komorka]=0;

session_register('zgloszenie_dodano_'.$unique_nr1.'');	
session_register('zgloszenie_szcz_dodano_'.$unique_nr1.'');

$_SESSION['zgloszenie_dodano_'.$unique_nr1.'']='nie';
$_SESSION['zgloszenie_szcz_dodano_'.$unique_nr1.'']='nie';


// weryfikacja aktywności dostępów czasowych dla wszystkich pracowników
	$aktualna_data = Date('Y-m-d H:i:s');
	$sql_dc = "UPDATE $dbname_hd.hd_dostep_czasowy SET dc_dostep_active=0 WHERE ((dc_dostep_active_to<'$aktualna_data') and (dc_dostep_active=1) and (belongs_to=$es_filia))";
	$result_dc = mysql_query($sql_dc, $conn_hd) or die($k_b);
// koniec weryfikacji aktywności dostępów czasowych

if ($_REQUEST[zgl_reklamacyjne]=='1') {
	pageheader("Rejestracja nowego zgłoszenia <font color=blue><b>reklamacyjnego</b></font> do zgłoszenia nr <b>$_REQUEST[zgl_reklamacyjne_do_zgl_nr]</b>");
} else {
	if ($_REQUEST[parent_zgl]>0) {
		pageheader("Rejestracja nowego zgłoszenia | kontynuacja zgłoszenia nr <b>$_REQUEST[parent_zgl]</b>");
	} else {
		pageheader("Rejestracja nowego zgłoszenia");
	}
}

?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();

echo "<div id=content>";

starttable();
echo "<form id=hd_dodaj_zgl name=hd_dodaj_zgl action=$PHP_SELF method=POST autocomplete=off onSubmit=\"return pytanie_zatwierdz('Zapisać zgłoszenie do bazy ?');\" />";

$result_k = mysql_query("SELECT filia_lokalizacja FROM $dbname.serwis_filie WHERE filia_id=$es_filia LIMIT 1", $conn) or die($k_b);
list($temp_lok)=mysql_fetch_array($result_k);
echo "<input tabindex=-1 type=hidden id=lokalizacjazrodlowa value='$temp_lok'>";

//$dddd = Date('Y-m-d');
//$sql_dw = "SELECT dzien_data FROM $dbname_hd.hd_dniwolne WHERE (dzien_data>='".$dddd."')";
//$wynik_dw = mysql_query($sql_dw,$conn_hd) or die($k_b);
//$dzienwolny = mysql_fetch_array($wynik_dw);
$dddd = Date('Y-m-d');

include_once('systemdate.php');

	tr_();
		td("140;r;Data zgłoszenia");
		td_(";;;");		
		//echo ">".date("w",strtotime($dddd));
			echo "<input type=hidden id=test value=''>";
			echo "<input type=hidden id=test2 value=''>";
			echo "<select class=wymagane name=hddz id=hddz onChange=\"document.getElementById('hd_wyjazd_data').value=this.value; BudujListeDatRozpoczecia(document.getElementById('kat_id').value); MakeListaDat(0); AnalyseTimes(); \" onBlur=\"BudujListeDatRozpoczecia(document.getElementById('kat_id').value);\">";
			echo "<option value='$dddd' SELECTED>$dddd</option>\n";
			
			if ((date("w",strtotime($dddd))!=1) || ($idw_dla_zbh_testowa))  {
				for ($cd=1; $cd<($idw_dla_zbh+1); $cd++) {
					echo "<option value='".SubstractDays($cd,$dddd)."'>".SubstractDays($cd,$dddd)."&nbsp;";
					if ($idw_dla_zbh_testowa) echo "[dla testów]";
					echo "</option>\n";
				}
			}
			
			// sprawdź dostępy czasowe dla tego pracownika
			$sql_dc = "SELECT dc_dostep_dla_daty,dc_dostep_active_to FROM $dbname_hd.hd_dostep_czasowy WHERE ((dc_dostep_dla_osoby='$currentuser') and (dc_dostep_active=1) and (belongs_to=$es_filia)) ORDER BY dc_dostep_dla_daty DESC";
			$result_dc = mysql_query($sql_dc, $conn_hd) or die($k_b);
			$totalrows_dc = mysql_num_rows($result_dc);
			
			if ($totalrows_dc>0) {
				while ($newArray_dc = mysql_fetch_array($result_dc)) {
					$temp_dc_data	= $newArray_dc['dc_dostep_dla_daty'];
					$temp_dc_dostep_do	= $newArray_dc['dc_dostep_active_to'];
					echo "<option value='$temp_dc_data'>$temp_dc_data (dostępna do $temp_dc_dostep_do)</option>\n";
				}
			}
			// koniec sprawdzania dostępów czasowych dla pracownika
			
			echo "</select>\n";

			$tttt = Date('H:i');
			echo "&nbsp;Godzina zgłoszenia&nbsp;<input class=wymagane type=text name=hdgz id=hdgz value='$tttt' maxlength=5 size=2 onFocus=\"this.select();\" onKeyPress=\"return filterInput(1, event, false, ''); \" onKeyUp=\"DopiszDwukropek('hdgz');\" onDblClick=\"this.value='".$tttt."'; \" title=\" Podwójne kliknięcie wpisuje godzinę otwarcia tego okna \" onChange=\"BudujListeDatRozpoczecia(document.getElementById('kat_id').value); MakeListaDat(0); \" onBlur=\"BudujListeDatRozpoczecia(document.getElementById('kat_id').value); GetStartStopTimeFromDate(document.getElementById('hddz').value); \" />";	
			
			echo "&nbsp;&nbsp;Numer zgłoszenia HDIM&nbsp;";
			echo "<input type=text id=hdnzhadim name=hdnzhadim class=wymagane"; 
			if ($_REQUEST[zgl_reklamacyjne]=='1') { echo " value='".$_REQUEST[nrzglpoczty]."' "; }		
			if (($_GET[parent_zgl]!='') && ($_GET[parent_zgl]!=0) && ($_REQUEST[fromtask]!=1)) {
				$r1a = mysql_query("SELECT zgl_poczta_nr FROM $dbname_hd.hd_zgloszenie WHERE (zgl_nr='$_GET[parent_zgl]') LIMIT 1", $conn_hd) or die($k_b);
				list($parent_zgl_poczta_nr)=mysql_fetch_array($r1a);
				echo " value='".$parent_zgl_poczta_nr."' ";
			}
			echo " maxlength=".$HADIM_max." size=".$HADIM_width." onKeyPress=\"return filterInput(1, event, false); \" onFocus=\"this.select();\" />";
			
			echo "<input type=hidden id=MGP_allow value='' />";

		_td();
	_tr();
	tr_();
		//td("140;rt;
		echo "<td class=righttop>";
		echo "Komórka zgłaszająca";		
		td_(";;;");
			// jeżeli zgłoszenie jest tworzone na bazie innego - wypełnij pole z automatu i ustaw je tylko do odczytu
			if (($_GET[parent_zgl]!='') && ($_GET[parent_zgl]!=0)) {
				list($komorka_zglaszajaca,$zgloszenie_osoba,$zgloszenie_telefon,$zgloszenie_temat,$zgloszenie_tresc,$zgloszenie_kat,$zgloszenie_podkat,$zgloszenie_priorytet,$zgloszenie_status)=mysql_fetch_array(mysql_query("SELECT zgl_komorka, zgl_osoba, zgl_telefon, zgl_temat, zgl_tresc, zgl_kategoria, zgl_podkategoria, zgl_priorytet, zgl_status FROM $dbname_hd.hd_zgloszenie WHERE (zgl_nr=$_GET[parent_zgl]) LIMIT 1", $conn_hd));

				echo "<b>".$komorka_zglaszajaca."</b>";
				
				echo "&nbsp;<span id=EW_S style='display:none;'>";
				echo "<input type=button class=buttons style='padding:1px; margin:1px;' value='Ewidencja sprzętu' onClick=\"newWindow_r(600,600,'p_ewidencja_simple.php?upid='+document.getElementById('up_list_id').value+''); \">";
				echo "</span>";
			
				echo "<input type=hidden name=up_list id=up_list value='$komorka_zglaszajaca'>";
				
				list($komorka_id)=mysql_fetch_array(mysql_query("SELECT up_id FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((serwis_komorki.belongs_to=$es_filia) and (serwis_piony.pion_id=serwis_komorki.up_pion_id) and (CONCAT(serwis_piony.pion_nazwa,' ',serwis_komorki.up_nazwa)='$komorka_zglaszajaca'))", $conn_hd));
				//echo "($komorka_id)";

				// przechowaj "parent_zgl"
				echo "<input type=hidden id=parent_zgl name=parent_zgl value='$_GET[parent_zgl]'>";
				
			} else {
				echo "<input class=wymagane type=text size=70 maxlength=50 name=up_list id=up_list onFocus=\"$(this).select(); \" onBlur=\"SprawdzKomorke(this.value);  BudujListeDatRozpoczecia(document.getElementById('kat_id').value); MakeListaDat(0); if (document.getElementById('up_list_id').value>0) { $('#gp').show(); } cUpper_k(document.getElementById('up_list')); return false; \" onChange=\"if ((document.getElementById('gp_default').style.display=='none') && (document.getElementById('gp').style.display=='none') && (document.getElementById('gpa').style.display=='none')) { if (document.getElementById('info1').style.display=='none') { $('#t').load('wait_ajax.php?randval='+ Math.random()); load_data_1(); } else { } } document.getElementById('hd_oz').value=''; document.getElementById('hd_oz').focus(); \" onKeyUp=\"if ((document.getElementById('info1').style.display=='') && (document.getElementById('up_list').value=='')) { document.getElementById('info1').style.display='none'; } \"/>";
				
				echo "&nbsp;<span id=EW_S style='display:none;'>";
				echo "<input type=button class=buttons style='padding:1px; margin:1px;' value='Ewidencja sprzętu' onClick=\"newWindow_r(600,600,'p_ewidencja_simple.php?upid='+document.getElementById('up_list_id').value+'&komorka='+urlencode(document.getElementById('up_list').value)+''); \">";
				echo "</span>";

				//echo "<input type=button value='123' onClick=\"cUpper_k(document.getElementById('up_list'));\" />";
				//echo "&nbsp;<input class=buttons type=button id=load_info style='display:none' value='Uzupełnij dane powiązane z komórką' onClick=\"load_data_1(document.getElementById('up_list')); \">";
				
				if (($_REQUEST[fromtask]=='1') && ($_REQUEST[komorka]!='')) {
					list($komorka_id)=mysql_fetch_array(mysql_query("SELECT up_id FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((serwis_komorki.belongs_to=$es_filia) and (serwis_piony.pion_id=serwis_komorki.up_pion_id) and (CONCAT(serwis_piony.pion_nazwa,' ',serwis_komorki.up_nazwa)='$_REQUEST[komorka]'))", $conn_hd));
				}
				
				//if ($_GET[special]=='wan') echo "value='$_GET[up]' readonly";
		//	echo "<span id=blok_podciec style='display:none'>";
				echo "<span id=t style='display:inline'></span>";

				echo "<br /><span id=tel_do_komorki_title style='display:none'>Telefon do komórki:";
				echo "<input tabindex=-1 style='background-color:transparent; border:0px solid; font-size:12px; font-weight:bold; width:250px;' type=text readonly id=tel_do_komorki />";
				echo "</span>";
				
				echo "<span id=adres_podsieci_title style='display:none;'>| Podsieć:";
				echo "<input tabindex=-1 style='background-color:transparent; border:0px solid; font-size:12px; font-weight:bold; display:inline;' type=text readonly id=up_adres_podsieci></span>";
		//	echo "</span>";
			}
			echo "<div id=info1 style='display:none'><i><font color=red>Brak UP / komórki w bazie</font></i></div>";
		_td();
	_tr();

	if ($_GET[parent_zgl]=='') {
		echo "<tr id=up_typ_uslugi style='display:none;'>";	
			echo "<td class=righttop>Typ usługi";	
			echo "</td>";
			echo "<td>";
			
			if ($_REQUEST[fromtask]!=1) {
				echo "<select class=wymagane name=hdtu id=hdtu>";
				echo "</select>";
				
				echo "<input type=hidden name=kategoria_komorki id=kategoria_komorki value=''/>";
				echo "<input type=hidden name=przypisanie_jednostki id=przypisanie_jednostki value=''/>";
				
				echo "<input type=hidden name=zadanieid id=zadanieid value='0'/>";
			} else {
			
				$sama_nazwa = substr($_REQUEST[komorka], strpos($_REQUEST[komorka],' ')+1, strlen($_REQUEST[komorka]));
				
				list($komorka_tu,$komorka_kk,$komorka_pj)=mysql_fetch_array(mysql_query("SELECT up_typ_uslugi,up_kategoria,up_kategoria FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((serwis_komorki.belongs_to=$es_filia) and (serwis_piony.pion_id=serwis_komorki.up_pion_id) and (CONCAT(serwis_piony.pion_nazwa,' ',serwis_komorki.up_nazwa)='$_REQUEST[komorka]'))", $conn_hd));
				
				//echo "SELECT up_typ_uslugi,up_kategoria,up_kategoria FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((serwis_komorki.belongs_to=$es_filia) and (serwis_piony.pion_id=serwis_komorki.up_pion_id) and (CONCAT(serwis_piony.pion_nazwa,' ',serwis_komorki.up_nazwa)='$_REQUEST[komorka]'))";
				
//				echo "$komorka_tu,$komorka_kk,$komorka_pj";
				
				echo "<input type=hidden name=hdtu id=hdtu value='$komorka_tu'/>";
				echo "<input type=hidden name=kategoria_komorki id=kategoria_komorki value='$komorka_kk'/>";
				echo "<input type=hidden name=przypisanie_jednostki id=przypisanie_jednostki value='$komorka_pj'/>";
				echo "<input type=hidden name=zadanieid id=zadanieid value='$_REQUEST[zadanieid]'/>";
			
			}
			
			echo "</td>";
		echo "</tr>";

	} else {
		list($_hdtu,$_hdkk,$_hdpj)=mysql_fetch_array(mysql_query("SELECT zgl_typ_uslugi,zgl_kategoria_komorki,zgl_przypisanie_jednostki FROM $dbname_hd.hd_zgloszenie WHERE (zgl_widoczne=1) and (zgl_nr=$_GET[parent_zgl])", $conn_hd));
		
		echo "<input type=hidden name=hdtu value='".$_hdtu."'>";		
		echo "<input type=hidden name=kategoria_komorki id=kategoria_komorki value='".$_hdkk."'/>";
		echo "<input type=hidden name=przypisanie_jednostki id=przypisanie_jednostki value='".$_hdpj."'/>";
	}
	
	echo "<tr id=none_gp style='display:none;'>";	
		echo "<td class=righttop>";	
		echo "</td>";

		echo "<td>";
			echo "<font color=red>Dla wybranej komórki nie zdefiniowano godzin pracy. Przy rejestracji awarii użyte będą domyślne godziny pracy:</font><br />";
		echo "</td>";
	echo "</tr>";
	
	echo "<tr id=gp_default style='display:none'>";	
		echo "<td class=righttop>";
	//	echo "<font color=red>Domyślne godziny pracy</font>";
		echo "</td>";
		//td("140;rt;");
		echo "<td>";
			echo "<table style='border:1px solid red'>";
			echo "<tr style='color:red'>";
				echo "<td width=80 class=center>PN</td><td width=80 class=center>WT</td><td width=80 class=center>ŚR</td><td width=80 class=center>CZ</td><td width=80 class=center>PT</td><td width=80 class=center>SO</td><td width=80 class=center>NI</td>";
			echo "</tr>";
			echo "<tr style='color:red'>";
				echo "<td class=center><b>$gp_start_pn_pt - $gp_stop_pn_pt</b></td>";
				echo "<td class=center><b>$gp_start_pn_pt - $gp_stop_pn_pt</b></td>";
				echo "<td class=center><b>$gp_start_pn_pt - $gp_stop_pn_pt</b></td>";
				echo "<td class=center><b>$gp_start_pn_pt - $gp_stop_pn_pt</b></td>";
				echo "<td class=center><b>$gp_start_pn_pt - $gp_stop_pn_pt</b></td>";
				echo "<td class=center><b>$gp_start_so - $gp_stop_so</b></td>";
				echo "<td class=center><b>$gp_start_ni - $gp_stop_ni</b></td>";
			echo "</tr>";
			echo "</table>";
		_td();
	_tr();
	
	echo "<tr id=gp style='display:none'>";	
		echo "<td class=righttop>";
		echo "Aktualne godziny pracy";
		echo "</td>";
		//td("140;rt;");
		echo "<td>";
			echo "<table>";
			echo "<tr style='background-color:#B4B4B4'>";
				echo "<td width=80 class=center>PN</td><td width=80 class=center>WT</td><td width=80 class=center>ŚR</td><td width=80 class=center>CZ</td><td width=80 class=center>PT</td><td width=80 class=center>SO</td><td width=80 class=center>NI</td>";
			echo "</tr>";
			echo "<tr style='background-color:#FFFF7F'>";
				echo "<td class=center><b><span name=PN id=PN></span></b></td>";
				echo "<td class=center><b><span id=WT></span></b></td>";
				echo "<td class=center><b><span id=SR></span></b></td>";
				echo "<td class=center><b><span id=CZ></span></b></td>";
				echo "<td class=center><b><span id=PT></span></b></td>";
				echo "<td class=center><b><span id=SO></span></b></td>";
				echo "<td class=center><b><span id=NI></span></b></td>";
			echo "</tr>";
			echo "</table>";
		_td();
	_tr();

	echo "<tr id=gpa style='display:none;'>";	
		echo "<td class=righttop>";
		echo "Aktualne godziny pracy";
		//echo "<br /><b><span id=gpa_active style='display:none'>Aktywne -></span></b>";
		echo "</td>";
		//td("140;rt;");
		td_(";;;");
			echo "<font color=blue>Obowiązują alternatywne godziny pracy. Okres obowiązywania: <b><span id=gpa_start></span></b> - ";
			echo "<b><span id=gpa_stop></span></b>";
			echo "</font>";
			echo "<table>";
			echo "<tr style='background-color:#B4B4B4'>";
				echo "<td width=80 class=center>PN</td><td width=80 class=center>WT</td><td width=80 class=center>ŚR</td><td width=80 class=center>CZ</td><td width=80 class=center>PT</td><td width=80 class=center>SO</td><td width=80 class=center>NI</td>";
			echo "</tr>";
			echo "<tr style='background-color:#FFAA7F'>";
				echo "<td class=center><b><span name=PNa id=PNa></span></b></td>";
				echo "<td class=center><b><span id=WTa></span></b></td>";
				echo "<td class=center><b><span id=SRa></span></b></td>";
				echo "<td class=center><b><span id=CZa></span></b></td>";
				echo "<td class=center><b><span id=PTa></span></b></td>";
				echo "<td class=center><b><span id=SOa></span></b></td>";
				echo "<td class=center><b><span id=NIa></span></b></td>";
			echo "</tr>";
			echo "</table>";
		_td();
	_tr();
	
	echo "<tr id=lista_zgloszen_pokaz style='display:none;'>";	
		echo "<td class=righttop>";
			echo "Lista otwartych zgłoszeń";
		echo "</td>";
		td_(";;;");
			echo "<input type=button class=buttons value='Pokaż' onClick=\"$('#lista_zgloszen').show(); $('#lista_zgloszen_pokaz').hide();\">";
		_td();
		
	echo "</tr>";
	
	echo "<tr id=lista_zgloszen style='display:none;'>";	
		echo "<td class=righttop>";
			echo "Lista otwartych zgłoszeń";
			
		
		echo "</td>";
		td_(";;;");
			echo "<input type=button class=buttons value='Ukryj' onClick=\"$('#lista_zgloszen').hide(); $('#lista_zgloszen_pokaz').show();\">";
			echo "<span id=lista_zgloszen_data></span>";
			//echo "<font color=blue>Obowiązują alternatywne godziny pracy. Okres obowiązywania: <b><span id=gpa_start></span></b> - ";
			//echo "<b><span id=gpa_stop></span></b>";
			//echo "</font>";
/*			echo "<table>";
			echo "<tr style='background-color:#B4B4B4'>";
				echo "<td width=50 class=center>Nr zgłoszenia</td><td>Temat zgłoszenia</td><td>Opcje</td>";
			echo "</tr>";
			echo "<tr style='background-color:#FFAA7F'>";
				echo "<td class=center><b><span id=SOa></span></b></td>";
				echo "<td class=center><b><span id=NIa></span></b></td>";
				echo "<td class=center><b><span id=NIa></span></b></td>";
			echo "</tr>";
			echo "</table>";
*/
		_td();
	_tr();
	
	
	tr_();
		td("140;rt;Osoba zgłaszająca");
		td_(";;;");		
		// jeżeli zgłoszenie jest tworzone na bazie innego - wypełnij pole z automatu i ustaw je tylko do odczytu
			if (($_GET[parent_zgl]!='') && ($_GET[parent_zgl]!=0)) {
				echo "<b>".$zgloszenie_osoba."</b>";
				echo "<input type=hidden name=hd_oz value='$zgloszenie_osoba'>";
				echo "&nbsp;|&nbsp;Telefon&nbsp;<b>".$zgloszenie_telefon."</b>";
				echo "<input type=hidden name=hdoztelefon value='$zgloszenie_telefon'>";				

			} else {
				echo "<input class=wymagane type=text name=hd_oz id=hd_oz size=38 maxlength=30 ";
				
				if ($_REQUEST[zgl_reklamacyjne]=='1') { echo " value='".$_REQUEST[osobazgl]."' "; }
				if (($_REQUEST[fromtask]==1) && ($_REQUEST[zadanie]!='')) echo " value='".$_REQUEST[osoba]."' ";
				
				echo " onKeyPress=\"return filterInput(0, event, false, ' ęóąśłżźćńĘÓĄŚŁŻŹĆŃ'); \"  onFocus=\"this.select();\" onBlur=\"cUpper(this);\" />";
				//if ($_GET[special]=='wan') echo "value='$currentuser' ";
				// echo " />";
				
				echo "<span id=tel_from_db style=display:inline;>";
				echo "&nbsp;Telefon&nbsp;<input type=text id=hdoztelefon name=hdoztelefon size=14 maxlength=15 ";
				if ($czy_wymagany_nr_telefonu==1) echo " class=wymagane ";
				if ($_REQUEST[zgl_reklamacyjne]=='1') { echo " value='".$_REQUEST[osobazgltel]."' "; }
				echo " onKeyPress=\"return filterInput(1, event, false, ' '); \" onFocus=\"this.select();\" />"; 	
				echo "</span>";

		//	echo "<span id=blok_podciec style='display:none'>";	
//				echo "<span id=tel_do_komorki_title style='display:none'>&nbsp;|&nbsp;Telefon:</span>";
//				echo "<input tabindex=-1 size=30 style='background-color:transparent; border:0px solid; font-size:12px; font-weight:bold;' type=text readonly id=tel_do_komorki />";
		//		echo "</span>";
		//	echo "</span>";
			
				echo "<div id=pokaz_pracownikow style='display:none; background-color:grey; width:400px;'>";
				echo "<hr>";
/*		
				echo "<div id=lista_pracownikow_from_ajax>";
					echo "&nbsp;<select name=pracownik onChange=\"document.getElementById('hd_oz').value=this.form.pracownik.value; showhide('pokaz_pracownikow','pokaz_id_pracownikow'); \">\n";
					
					echo "</select>";
				echo "</div>";
*/
				echo "<hr>";
				echo "</div>";
				
				echo "<span id=ZapamietajDane style='display:none'>";
				echo "&nbsp;<input class=border0 type=checkbox id=zapamietaj_oz name=zapamietaj_oz>";
				echo "<a href=# class=normalfont onClick=\"if (document.getElementById('zapamietaj_oz').checked) { document.getElementById('zapamietaj_oz').checked=false; } else { document.getElementById('zapamietaj_oz').checked=true; } \"> Zapamiętaj dane</a>";
				echo "</span>";
			}
		_td();
	_tr();	
	tr_();
		td("140;rt;Temat zgłoszenia");
		td_(";;;");
			echo "<input tabindex=-1 type=text name=hd_temat id=hd_temat readonly size=70 style='border-width:0px;background-color:transparent;font-weight:bold; font-family:tahoma;' ";	
			if (($_GET[parent_zgl]!='') && ($_GET[parent_zgl]!=0)) {
				echo " value='$zgloszenie_temat' ";
			}
			
			if ($_REQUEST[zgl_reklamacyjne]=='1') echo " value='$_REQUEST[zgl_tresc]' ";
			
			echo "><br />";		
		_td();
	_tr();
	tr_();
		td("140;rt;Treść zgłoszenia");		
		td_(";;;");
			echo "<textarea class=wymagane name=hd_tresc id=hd_tresc cols=66 rows=2 onKeyUp=\"KopiujDo1Entera(this.value,'hd_temat'); ex1(this); if (this.value!='') { document.getElementById('sl_d').style.display=''; document.getElementById('tr_clear').style.display=''; } else { document.getElementById('sl_d').style.display='none'; document.getElementById('tr_clear').style.display='none'; }\" onBlur=\"ZamienTekst(this.id); KopiujDo1Entera(this.value,'hd_temat'); \" >";
			if (($_GET[parent_zgl]!='') && ($_GET[parent_zgl]!=0)) {
				echo "$zgloszenie_tresc";
			}
			if (($_REQUEST[fromtask]==1) && ($_REQUEST[zadanie]!='')) echo $_REQUEST[zadanie];
			if ($_REQUEST[zgl_reklamacyjne]=='1') echo $_REQUEST[zgl_tresc];
			echo "</textarea>";
			
			echo "&nbsp;<a title=' Dodaj treść do słownika' style='display:none' id=sl_d class=imgoption  onClick=\"newWindow_byName('_dodaj_do_slownika',700,400,'hd_d_slownik_tresc.php?akcja=fastadd'); return false;\"><input class=imgoption type=image src=img/slownik_dodaj.gif></a>";
			echo "<a title=' Wybierz treść ze słownika' id=sl_wybierz class=imgoption  onClick=\"newWindow_byName_r('_wybierz_ze_slownika',800,600,'hd_z_slownik_tresci.php?a=2&akcja=wybierz&p6=".urlencode($currentuser)."'); return false;\"><input class=imgoption type=image src=img/ew_prosty.png></a>";
			
			echo "<a id=tr_clear href=# style='display:none' onclick=\"if (confirm('Czy wyczyścić treść zgłoszenia ?')) { document.getElementById('hd_tresc').value=''; KopiujDo1Entera(document.getElementById('hd_tresc').value,'hd_temat'); ex1(document.getElementById('hd_tresc')); document.getElementById('tr_clear').style.display='none'; document.getElementById('sl_d').style.display='none'; document.getElementById('hd_tresc').focus(); }\" title=' Wyczyść treść zgłoszenia '><img src=img/czysc.gif border=0></a>";
			
		_td();
	_tr();	

if (($_GET[parent_zgl]!='') && ($_GET[parent_zgl]!=0) && ($_REQUEST[fromtask]!=1)) {

	$test1 = 0;
	// zejście z awarii zwykłej -> prace zlecone w ramach umowy - priorytet standard
	if ($zgloszenie_kat=='2') {
//		$zgloszenie_kat='3';
		echo "<input type=hidden id=NewDefaultPodkat_id name=NewDefaultPodkat_id value='$zgloszenie_podkat'>";
		$zgloszenie_priorytet='2';
		$test1 = 1;
	}
	
	// zejście z awarii krytycznej -> awarię zwykłą
	if (($zgloszenie_kat=='6') && ($test1==0)) {
		//$zgloszenie_kat='2';
		
		echo "<input type=hidden id=NewDefaultPodkat_id name=NewDefaultPodkat_id value='$zgloszenie_podkat'>";
		
		$zgloszenie_priorytet='2';
	}
	
	$r1 = mysql_query("SELECT hd_kategoria_opis FROM $dbname_hd.hd_kategoria WHERE (hd_kategoria_nr='$zgloszenie_kat') LIMIT 1", $conn_hd) or die($k_b);list($kat_opis)=mysql_fetch_array($r1);
	$r2 = mysql_query("SELECT hd_podkategoria_opis FROM $dbname_hd.hd_podkategoria WHERE (hd_podkategoria_nr='$zgloszenie_podkat') LIMIT 1", $conn_hd) or die($k_b);list($podkat_opis)=mysql_fetch_array($r2);
	//$r3 = mysql_query("SELECT hd_priorytet_opis FROM $dbname_hd.hd_priorytet WHERE (hd_priorytet_id='$zgloszenie_priorytet') LIMIT 1", $conn_hd) or die($k_b);list($priorytet_opis)=mysql_fetch_array($r3);
	$r4 = mysql_query("SELECT hd_status_opis FROM $dbname_hd.hd_status WHERE (hd_status_nr='$zgloszenie_status') LIMIT 1", $conn_hd) or die($k_b);list($status_opis)=mysql_fetch_array($r4);
	
	if ($_REQUEST[parent_zgl]!='') {
		$r2 = mysql_query("SELECT zgl_podkategoria_poziom_2 FROM $dbname_hd.hd_zgloszenie WHERE (zgl_nr='$parent_zgl') LIMIT 1", $conn_hd) or die($k_b);
		list($podkat_poziom_2_opis)=mysql_fetch_array($r2);
	}
	
	tr_();
		td("140;rt;Kategoria");
		td_(";;;");
			
			if ($zgloszenie_kat==6) {
				//echo "<select class=wymagane id=kat_id name=kat_id >";
				echo "<select class=wymagane id=kat_id name=kat_id onChange=\"MakePodkategoriaList(this.options[this.options.selectedIndex].value);  GenerateOnClickEventForSlownikTresci(); BudujListeDatRozpoczecia(document.getElementById('kat_id').value); MakeListaDat(0); ShowHints(document.getElementById('kat_id').value,document.getElementById('podkat_id').value); GenerateSubPodkategoriaList(document.getElementById('kat_id').value,document.getElementById('podkat_id').value); ";
				
				if ($_REQUEST[parent_zgl]!='') {
					echo "document.getElementById('sub_podkat_id').value='$podkat_poziom_2_opis';";
				}
			
				echo " \" onBlur=\"SzybkiSkokZKategorii(this.value); BudujListeDatRozpoczecia(document.getElementById('kat_id').value); \" onKeyUp=\"if (event.keyCode==13) document.getElementById('podkat_id').focus(); \" />\n";	
					if ($zgloszenie_kat==6) echo "<option value='2' SELECTED>Awarie</option>\n";	
					echo "<option value='3'>Prace zlecone w ramach umowy</option>\n";
				echo "</select>\n";		
		
				//echo "<b>".$kat_opis."</b>";
			}
			
			if ($zgloszenie_kat==2) {
				echo "<b>Prace zlecone w ramach umowy</b>";
				echo "<input type=hidden id=kat_id name=kat_id value='3'>";
			}
	//			echo "<input type=hidden name=kat_id value='$zgloszenie_kat'>";
		_td();
	_tr();
	
	tr_();
		td("140;rt;Podkategoria");
		td_(";;;");
			echo "<select class=wymagane id=podkat_id name=podkat_id disabled=true onChange=\"GenerateSubPodkategoriaList(document.getElementById('kat_id').value,document.getElementById('podkat_id').value); GenerateOnClickEventForSlownikTresci(); ShowHints(document.getElementById('kat_id').value,this.value); ";
			
			if ($_REQUEST[parent_zgl]!='') {
				echo "document.getElementById('sub_podkat_id').value='$podkat_poziom_2_opis';";
			}

			
			echo " \" />\n";
				echo "<option value=''></option>\n";		
			echo "</select>\n";	

			echo "<br />Podkategoria zgłoszenia nr $_REQUEST[parent_zgl]: <b>".$podkat_opis."</b>";
			
			//echo "<input type=hidden name=podkat_id value='$zgloszenie_podkat'>";
		_td();
	_tr();
	tr_();
		td("140;rt;Podkategoria (poziom 2)");
		td_(";;;");	
			echo "<select class=wymagane id=sub_podkat_id name=sub_podkat_id onChange=\"GenerateSubPodkategoriaList(document.getElementById('kat_id').value,document.getElementById('podkat_id').value); ";			
			echo "\"/>\n";
			echo "<option value=''></option>\n";
			echo "</select>\n";

			echo "<br />Podkategoria (poziom 2) zgłoszenia nr $_REQUEST[parent_zgl]: <b>".$podkat_poziom_2_opis."</b>";
		_td();
	_tr();	
			
	echo "<input type=hidden name=priorytet_id value='$zgloszenie_priorytet'>";
	
} else {
		if (($_REQUEST[fromtask]==1) && ($_REQUEST[zadanie]!='')) {
			tr_();
				td("140;rt;Kategoria");
				
				if ($_REQUEST[kat_nr]!='') {
					td_(";;<b>$_REQUEST[kat_opis]</b>");	
					echo "<input type=hidden id=kat_id name=kat_id value='$_REQUEST[kat_nr]'>";		
				} else {
					td_(";;<b>Prace zlecone w ramach umowy</b>");	
					echo "<input type=hidden id=kat_id name=kat_id value='3'>";
				}
				
			_tr();		
		} else {
			tr_();
				td("140;rt;Kategoria");
				td_(";;;");	
					echo "<select class=wymagane id=kat_id name=kat_id onChange=\"MakePodkategoriaList(this.options[this.options.selectedIndex].value); StatusChanged(this.value,document.getElementById('status_id').value,'Kategoria'); GenerateOnClickEventForSlownikTresci(); if (document.getElementById('kat_id').value==2) { document.getElementById('nowy_czas_rozpoczecia').readOnly = true; BudujListeDatRozpoczecia(document.getElementById('kat_id').value); MakeListaDat(0); } if (document.getElementById('kat_id').value==6) { document.getElementById('nowy_czas_rozpoczecia').readOnly = false; BudujListeDatRozpoczecia(document.getElementById('kat_id').value); MakeListaDat(0);} ShowHints(document.getElementById('kat_id').value,document.getElementById('podkat_id').value);\" onBlur=\"SzybkiSkokZKategorii(this.value); BudujListeDatRozpoczecia(document.getElementById('kat_id').value);\" onKeyUp=\"if (event.keyCode==13) document.getElementById('podkat_id').focus(); \" />\n";
					//echo "<option value=''></option>\n";	
					echo "<option value='1'>Konsultacje</option>\n";
					
					echo "<option value='2'"; 
					if (($_REQUEST[zgl_reklamacyjne]=='1') && ($_REQUEST[zglkat]=='2')) echo " SELECTED ";
					echo ">Awarie</option>\n";
					
					echo "<option value='6' "; 
					if (($_REQUEST[zgl_reklamacyjne]=='1') && ($_REQUEST[zglkat]=='6')) echo " SELECTED ";
					echo " >Awarie krytyczne</option>\n";
					
					echo "<option value='3'";
					if ($_GET[special]=='wan') { 
						echo " SELECTED ";
					}
					if (($_REQUEST[zgl_reklamacyjne]=='1') && ($_REQUEST[zglkat]=='3')) echo " SELECTED ";
					echo ">Prace zlecone w ramach umowy</option>\n";			
					
					echo "<option value='7'>Konserwacja</option>\n";
					
					echo "<option value='4' "; 
					if (($_REQUEST[zgl_reklamacyjne]=='1') && ($_REQUEST[zglkat]=='4')) echo " SELECTED ";
					echo ">Prace zlecone poza umową</option>\n";
					
					echo "<option value='5' "; 
					if (($_REQUEST[zgl_reklamacyjne]=='1') && ($_REQUEST[zglkat]=='5')) echo " SELECTED ";
					echo ">Prace na potrzeby Postdata</option>\n";
					
					echo "</select>\n";
					echo "<span id=WyslijEmail style='display:none'>";
					if ($WlaczMaile=='1') {
						echo "<input class=border0 type=checkbox id=WyslijEmailCheckbox name=WyslijEmailCheckbox>";
						echo "<a href=# class=normalfont onClick=\"if (document.getElementById('WyslijEmailCheckbox').checked) { document.getElementById('WyslijEmailCheckbox').checked=false; } else { document.getElementById('WyslijEmailCheckbox').checked=true; } \"> Wyślij email do koordynatora</a>";
					} else {
						echo "<input style='border:0px' type=hidden id=WyslijEmailCheckbox name=WyslijEmailCheckbox>";
					}
					echo "</span>";
				_td();
			_tr();
	
		}
		
		if (($_REQUEST[fromtask]==1) && ($_REQUEST[zadanie]!='')) {
			tr_();
				td("140;rt;Podkategoria");
					if ($_REQUEST[podkat_opis]!='') {
						td_(";;<b>$_REQUEST[podkat_opis]</b>");	
						echo "<input type=hidden id=podkat_id name=podkat_id value='$_REQUEST[podkat_nr]'>";
					} else {
						td_(";;");
							echo "<select class=wymagane id=podkat_id name=podkat_id onChange=\"GenerateSubPodkategoriaList(document.getElementById('kat_id').value,document.getElementById('podkat_id').value); StatusChanged(document.getElementById('kat_id').value,document.getElementById('status_id').value,'Status'); GenerateOnClickEventForSlownikTresci(); 
							ShowHints(document.getElementById('kat_id').value,this.value);\" />\n";
							echo "<option value=''></option>\n";		
							echo "</select>\n";
						_td();
					}
			_tr();
		} else {
			tr_();
				td("140;rt;Podkategoria");
				td_(";;;");	
					echo "<select class=wymagane id=podkat_id name=podkat_id disabled=true onChange=\"GenerateSubPodkategoriaList(document.getElementById('kat_id').value,document.getElementById('podkat_id').value); StatusChanged(document.getElementById('kat_id').value,document.getElementById('status_id').value,'Status'); GenerateOnClickEventForSlownikTresci(); BudujListeDatRozpoczecia(document.getElementById('kat_id').value); MakeListaDat(0); ShowHints(document.getElementById('kat_id').value,this.value); \" onBlur=\"\"/>\n";
					echo "<option value=''></option>\n";		
					echo "</select>\n";			
				_td();
			_tr();
		}
		
		if (($_REQUEST[fromtask]==1) && ($_REQUEST[zadanie]!='')) {
			echo "<tr style='display:none' id=tr_pk_hint>";
				td("140;rt;");
				td_(";;;");	
					echo "<div title='Kliknij, aby ukryć podpowiedzi' id=Hint style='border:1px solid #67ADE7; background-color:#B7D8F3; display:none; padding:5px;' onClick=\"this.style.display='none'; document.getElementById('tr_pk_hint').style.display='none';\" ></div>";
				_td();
			_tr();
			
			if ($_REQUEST[czynnosc]!='1') {
				tr_();
					td("140;rt;Podkategoria (poziom 2)");
					td_(";;<b>$_REQUEST[podkat2_opis]</b>");	
					echo "<input type=hidden id=sub_podkat_id name=sub_podkat_id value='$_REQUEST[podkat2_opis]'>";			
				_tr();
			} else {
				tr_();
					td("140;rt;Podkategoria (poziom 2)");
					td_(";;;");	
						echo "<select class=wymagane id=sub_podkat_id name=sub_podkat_id />\n";
						echo "<option value=''></option>\n";
						echo "</select>\n";
					_td();
				_tr();
			}
			
		} else {
			echo "<tr style='display:none' id=tr_pk_hint>";
				td("140;rt;");
				td_(";;;");	
					echo "<div title='Kliknij, aby ukryć podpowiedzi' id=Hint style='border:1px solid #67ADE7; background-color:#B7D8F3; display:none; padding:5px;' onClick=\"this.style.display='none'; document.getElementById('tr_pk_hint').style.display='none';\" ></div>";
				_td();
			_tr();
				
			tr_();
				td("140;rt;Podkategoria (poziom 2)");
				td_(";;;");	
					echo "<select class=wymagane id=sub_podkat_id name=sub_podkat_id />\n";
					echo "<option value=''></option>\n";
					echo "</select>\n";
				_td();
			_tr();		
		}
		
		echo "<tr style='display:none'>";
			td("140;rt;Priorytet");
			td_(";;;");	
				echo "<select class=wymagane id=priorytet_id name=priorytet_id disabled=true onChange=\"StatusChanged(document.getElementById('kat_id').value,document.getElementById('status_id').value,'Status');\" onKeyUp=\"if (event.keyCode==13) document.getElementById('status_id').focus(); \" />\n";
				echo "<option value=''></option>\n";
				echo "</select>\n";		
			_td();
		_tr();
	}
		
	if (($_REQUEST[fromtask]==1) && ($_REQUEST[zadanie]!='')) {
		tr_();
			td("140;r;Status");
			td_(";;");	
				echo "<select class=wymagane id=status_id name=status_id onChange=\"StatusChanged_fromtask(3,this.value,'Status');\">";
				//echo "<option value='2'>przypisane</option>\n";
				echo "<option value='1'>nowe</option>\n";
				echo "<option value='7'>rozpoczęte - nie zakończone</option>\n";
				echo "<option value='9' SELECTED>zamknięte</option>\n";
				echo "</select>";
				//echo "1";
				echo "<span id=PrzypiszDoOsoby style='display:none;'>";
					$result44 = mysql_query("SELECT user_first_name,user_last_name FROM $dbname.serwis_uzytkownicy WHERE (belongs_to=$es_filia) and (user_locked=0) ORDER BY user_last_name ASC", $conn_hd) or die($k_b);
					$count_rows = mysql_num_rows($result44);
					echo "&nbsp;<select class=wymagane id=PrzypiszDoOsobyValue name=PrzypiszDoOsobyValue onKeyUp=\"if ((event.keyCode==13) && (document.getElementById('PowiazaneZWyjazdem').style.display=='none')) document.getElementById('submit').focus(); \"/>\n";
					
					while (list($temp_imie,$temp_nazwisko) = mysql_fetch_array($result44)) {
						$imieinazwisko = $temp_imie . " " . $temp_nazwisko;
						echo "<option value='$imieinazwisko' ";
						if ($currentuser==$imieinazwisko) echo " SELECTED";
						echo ">$imieinazwisko</option>\n"; 
					}
					echo "</select>\n";
				echo "</span>";
				
				echo "<span id=PowiazaneZWyjazdem style='display:'>";
				echo "&nbsp;<input class=border0 type=checkbox name=PozwolWpisacKm id=PozwolWpisacKm onClick=\"if (this.checked) { document.getElementById('RodzajPojazdu').style.display=''; $('#CzasTrwaniaWyjazdu').show(); document.getElementById('hd_wyjazd_rp').options.selectedIndex=1;  document.getElementById('DataWyjazdu').style.display='none'; document.getElementById('WpiszWyjazdTrasa').style.display='none'; document.getElementById('WpiszWyjazdKm').style.display='none'; document.getElementById('wymaga_wyjazdu').checked=false; } else { document.getElementById('RodzajPojazdu').style.display='none'; $('#CzasTrwaniaWyjazdu').hide(); document.getElementById('hd_wyjazd_rp').options.selectedIndex=1;  document.getElementById('DataWyjazdu').style.display='none'; document.getElementById('WpiszWyjazdTrasa').style.display='none'; document.getElementById('WpiszWyjazdKm').style.display='none'; } document.getElementById('hd_wyjazd_rp_P').checked=true;  return false; \" />";
				
				echo "<a href=# class=normalfont onClick=\"if (document.getElementById('PozwolWpisacKm').checked) { document.getElementById('PozwolWpisacKm').checked=false; } else { document.getElementById('PozwolWpisacKm').checked=true; } if (document.getElementById('PozwolWpisacKm').checked) { document.getElementById('wymaga_wyjazdu').checked=false; document.getElementById('RodzajPojazdu').style.display=''; $('#CzasTrwaniaWyjazdu').show(); document.getElementById('hd_wyjazd_rp').options.selectedIndex=1;  document.getElementById('DataWyjazdu').style.display='none'; document.getElementById('WpiszWyjazdTrasa').style.display='none'; document.getElementById('WpiszWyjazdKm').style.display='none'; document.getElementById('hd_wyjazd_rp_P').checked=true; } else { document.getElementById('RodzajPojazdu').style.display='none';  $('#CzasTrwaniaWyjazdu').hide(); document.getElementById('hd_wyjazd_rp').options.selectedIndex=1; document.getElementById('DataWyjazdu').style.display='none'; document.getElementById('WpiszWyjazdTrasa').style.display='none'; document.getElementById('WpiszWyjazdKm').style.display='none'; document.getElementById('hd_wyjazd_rp_P').checked=true; } return false; \"> <b>Powiązane z wyjazdem</b>&nbsp;</a>";
				echo "</span>";
				
		_tr();
		
	} else {
		tr_();
			td("140;rt;Status");
			td_(";;;");	
			
			if (($_GET[parent_zgl]!='') && ($_GET[parent_zgl]!=0)) {
				echo "<select class=wymagane id=status_id name=status_id>";
				echo "<option value='3'>rozpoczęte</option>\n";
				echo "</select>";
			} else {
			
				echo "<select class=wymagane id=status_id name=status_id ";
				if ($_GET[special]!='wan') echo "disabled=true ";
				echo "onChange=\"StatusChanged(document.getElementById('kat_id').value,this.value,'Status');\" onKeyUp=\"if (event.keyCode==13) SzybkiSkokZeStatusu(this);\" />\n";
			
				if ($_GET[special]!='wan') {
					echo "<option value=''></option>\n";
				} else {	
					if ($_GET[action]=='open') echo "<option value='1' SELECTED>nowe</option>\n";	
					echo "<option value='9' ";
					if ($_GET[action]=='close') echo " SELECTED ";
					echo ">zamknięte</option>\n";	
				}
				echo "</select>\n";
			}
				//echo "&nbsp;";
				
				//echo "&nbsp;";
				echo "<span id=PrzypiszDoOsoby style='display:none;'>";
					$result44 = mysql_query("SELECT user_first_name,user_last_name FROM $dbname.serwis_uzytkownicy WHERE (belongs_to=$es_filia) and (user_locked=0) ORDER BY user_last_name ASC", $conn_hd) or die($k_b);
					$count_rows = mysql_num_rows($result44);
					echo "<select class=wymagane id=PrzypiszDoOsobyValue name=PrzypiszDoOsobyValue onKeyUp=\"if ((event.keyCode==13) && (document.getElementById('PowiazaneZWyjazdem').style.display=='none')) document.getElementById('submit').focus(); \"/>\n";
					
					while (list($temp_imie,$temp_nazwisko) = mysql_fetch_array($result44)) {
						$imieinazwisko = $temp_imie . " " . $temp_nazwisko;
						echo "<option value='$imieinazwisko' ";
						if ($currentuser==$imieinazwisko) echo " SELECTED";
						echo ">$imieinazwisko</option>\n"; 
					}
					echo "</select>\n";
				
				echo "</span>";

				echo "<span id=Zdiagnozowany style='display:none'>";
					echo "Zdiagnozowany: ";
					echo "<select id=SelectZdiagnozowany name=SelectZdiagnozowany>\n";
					echo "<option value='9'></option>\n";
					echo "<option value='1'>TAK</option>\n";
					echo "<option value='0'>NIE</option>\n";
					echo "</select>";
				echo "</span>";
				
				echo "<span id=AkceptacjaKosztow style='display:none'>";
					echo "Akceptacja kosztów: ";
					echo "<select id=SelectAkceptacjaKosztow name=SelectAkceptacjaKosztow>\n";
					echo "<option value='9'></option>\n";
					echo "<option value='1'>TAK</option>\n";
					echo "<option value='0'>NIE</option>\n";
					echo "</select>";
				echo "</span>";
				
				echo "<span id=PowiazaneZWyjazdem style='display:none'>";
				echo "&nbsp;<input class=border0 type=checkbox name=PozwolWpisacKm id=PozwolWpisacKm onClick=\"if (this.checked) { document.getElementById('RodzajPojazdu').style.display=''; $('#CzasTrwaniaWyjazdu').show(); document.getElementById('hd_wyjazd_rp').options.selectedIndex=1;  document.getElementById('DataWyjazdu').style.display='none'; document.getElementById('WpiszWyjazdTrasa').style.display='none'; document.getElementById('WpiszWyjazdKm').style.display='none'; document.getElementById('wymaga_wyjazdu').checked=false; } else { document.getElementById('RodzajPojazdu').style.display='none'; $('#CzasTrwaniaWyjazdu').hide(); document.getElementById('hd_wyjazd_rp').options.selectedIndex=1;  document.getElementById('DataWyjazdu').style.display='none'; document.getElementById('WpiszWyjazdTrasa').style.display='none'; document.getElementById('WpiszWyjazdKm').style.display='none'; } document.getElementById('hd_wyjazd_rp_P').checked=true; \" />";
				
				echo "<a href=# class=normalfont onClick=\"if (document.getElementById('PozwolWpisacKm').checked) { document.getElementById('PozwolWpisacKm').checked=false;} else { document.getElementById('PozwolWpisacKm').checked=true; } if (document.getElementById('PozwolWpisacKm').checked) { document.getElementById('RodzajPojazdu').style.display=''; $('#CzasTrwaniaWyjazdu').show(); document.getElementById('wymaga_wyjazdu').checked=false; document.getElementById('hd_wyjazd_rp').options.selectedIndex=1;  document.getElementById('DataWyjazdu').style.display='none'; document.getElementById('WpiszWyjazdTrasa').style.display='none'; document.getElementById('WpiszWyjazdKm').style.display='none'; document.getElementById('hd_wyjazd_rp_P').checked=true; } else { document.getElementById('RodzajPojazdu').style.display='none'; $('#CzasTrwaniaWyjazdu').hide(); document.getElementById('hd_wyjazd_rp').options.selectedIndex=1; document.getElementById('DataWyjazdu').style.display='none'; document.getElementById('WpiszWyjazdTrasa').style.display='none'; document.getElementById('WpiszWyjazdKm').style.display='none'; document.getElementById('hd_wyjazd_rp_P').checked=true; } \"><b>Powiązane z wyjazdem</b>&nbsp;</a>";
				echo "</span>";
				
			echo "<span id=UstalonaDataZakonczenia style='display:none'>";
				echo "<input class=border0 type=checkbox name=UstalonaDataZakonczeniaCheck id=UstalonaDataZakonczeniaCheck onClick=\"if (this.checked) { document.getElementById('UstalonaDataZakonczeniaTR').style.display=''; document.getElementById('UstalonaDataZakonczeniaTR').style.display='';  document.getElementById('nowa_data_rozpoczecia').focus(); } else { document.getElementById('UstalonaDataZakonczeniaTR').style.display='none'; } BudujListeDatRozpoczecia(document.getElementById('kat_id').value); MakeListaDat(1); \" />";
				echo "<a href=# class=normalfont onClick=\"if (document.getElementById('UstalonaDataZakonczeniaCheck').checked) { document.getElementById('UstalonaDataZakonczeniaCheck').checked=false; } else { document.getElementById('UstalonaDataZakonczeniaCheck').checked=true; } if (document.getElementById('UstalonaDataZakonczeniaCheck').checked) {  document.getElementById('UstalonaDataZakonczeniaTR').style.display=''; } else { document.getElementById('UstalonaDataZakonczeniaTR').style.display='none'; } BudujListeDatRozpoczecia(document.getElementById('kat_id').value); MakeListaDat(1); \"> <b><font color=red>";
				echo "<span id=UCR_opis_AZ_1 style='display:none;'>Ustalony czas rozpoczęcia</span>";
				echo "<span id=UCR_opis_AK_1 style='display:none;'>Ustalony czas rozpoczęcia</span>";
				
				echo "</font></b></a>";
				
				echo "<input type=hidden name=UDR_MinDate id=UDR_MinDate value='".AddWorkingDays(2,date("Y-m-d"))."' />";
				
			echo "</span>";
			
				echo "<div id=AwariaWAN style='display:none;'>";
				echo "<fieldset id=BlokAwariaWAN><legend>Informacje potrzebne do zgłoszenia awarii WAN</legend>";
				nowalinia();
				echo "&nbsp;Telefon do TPSA : <b><font color=red>$nr_telefonu_do_tpsa</font></b><br />";
				echo "&nbsp;Nr WAN-portu : "; echo "<input size=80 style='background-color:transparent;border:0px;font-size:12px;font-weight:bold;' type=text readonly name=up_wanport id=up_wanport tabindex=-1>"; nowalinia();
				echo "&nbsp;Lokalizacja : "; echo "<input size=80 style='background-color:transparent;border:0px;font-size:12px;' type=text readonly name=up_lokalizacja id=up_lokalizacja tabindex=-1>"; nowalinia();
				echo "&nbsp;Telefon : "; echo "<input size=80 style='background-color:transparent;border:0px;font-size:12px;' type=text readonly name=up_telefon id=up_telefon tabindex=-1>";nowalinia();		
				
				echo "&nbsp;Numer zgłoszenia w Orange : <input type=text class=wymagane name=numerzgloszenia id=numerzgloszenia>";
				nowalinia();			
				echo "</fieldset>";
				echo "<span id=BrakWANportuKomunikat style=display:none><font color=red><b>Wybrana komórka nie ma zdefiniowanego numeru WAN-portu</b></font></span>";
				echo "</div>";
				
			_td();
		_tr();
	}
	
	echo "<tr id=NrZgloszeniaGdansk style='display:none'>";
		td("140;r;<b>Identyfikator zgłoszenia</b>");
		td_(";;;");
			echo "<input type=text id=hdnrzglgdansk name=hdnrzglgdansk value='' maxlength=10 size=8 onFocus=\"this.select();\" />";
		_td();
	echo "</tr>";

		echo "<tr id=WieleOsob style='display:;'>";
			td(";;");
			td_(";;;");
			//	echo "<span id=PowiazaneZWyjazdem style=display:''>";
				echo "<input class=border0 type=checkbox name=WieleOsobCheck id=WieleOsobCheck onClick=\"if (this.checked) { document.getElementById('WieleOsobWybor').style.display=''; } else { document.getElementById('WieleOsobWybor').style.display='none'; } \" />";
				
				echo "<a href=# class=normalfont onClick=\"if (document.getElementById('WieleOsobCheck').checked) { document.getElementById('WieleOsobCheck').checked=false; } else { document.getElementById('WieleOsobCheck').checked=true; } if (document.getElementById('WieleOsobCheck').checked) { document.getElementById('WieleOsobWybor').style.display=''; } else { document.getElementById('WieleOsobWybor').style.display='none'; }\"> Zgłoszenie obsługiwane  przez wiele osób</a>";
			//	echo "</span>";		
			_td();
		echo "</tr>";
		//tbl_empty_row(1);
		echo "<tr id=WieleOsobWybor style='display:;'>";
			td(";rt;Osoba rejestrująca<br /><br />Dodatkowe osoby");
			td_(";;;");
				echo "<b>".$currentuser."</b>";
				echo "<br /><br />";
				$sql_filtruj = "SELECT user_first_name, user_last_name FROM $dbname.serwis_uzytkownicy WHERE (belongs_to=$es_filia) and (user_locked=0) and (user_id<>$es_nr) and (CONCAT(user_first_name,' ',user_last_name)<>'$currentuser') ORDER BY user_last_name ASC";
				$result44 = mysql_query($sql_filtruj, $conn) or die($k_b);
				$count_rows = mysql_num_rows($result44);
				
				if ($count_rows>0) {
					echo "<select class=wymagane name=userlist[] size=7 id=userlist multiple=multiple>\n";
					while (list($temp_imie,$temp_nazwisko) = mysql_fetch_array($result44)) {
						$imieinazwisko = $temp_imie." ".$temp_nazwisko;
						echo "<option value='$temp_imie $temp_nazwisko' ";
						//if ($imieinazwisko==$currentuser) echo " SELECTED ";
						echo ">$temp_imie $temp_nazwisko</option>\n"; 
					}
					echo "</select>";
				}
				
				if ($count_rows>0) {
					echo "Wybrane osoby<br />";
					echo "<textarea style='background-color:transparent;font-size:9px;font-family:tahoma;' name=lista_osob  id=userlist_selectedItems readonly cols=120 rows=3></textarea>";
				} else echo "<input type=hidden name=lista_osob id=userlist_selectedItems value=''>";
		
			_td();
		echo "</tr>";
		
	echo "<tr id=StatusZakonczony style='display:'>";
		td("140;rt;Czas wykonywania ");
		td_(";;;");
			
			if (($_GET[special]!='wan') && ($_GET[action]=='close')) { 
				$CzasTrwaniaAwarii = $_GET[czastrwania];
				//echo ">>> $CzasTrwaniaAwarii";
			}
			
			echo "<input style=text-align:right type=text class=wymagane id=czas_wykonywania_h name=czas_wykonywania_h value='0' maxlength=2 size=2 onKeyPress=\"return filterInput(1, event, false); \" onKeyUp=\"if ((event.keyCode==13) && (this.value!='')) document.getElementById('czas_wykonywania_m').focus(); \" onFocus=\"this.select(); return false;\" onBlur=\"if (this.value=='') this.value=0; if (this.value>8) if (confirm('Wpisano więcej niż 8 godzin na wykonanie kroku. Potwierdzasz poprawność ?')) { return true; } else { this.value='0'; return false; }\" /> godzin";
			echo "&nbsp;";
			echo "<input style=text-align:right type=text class=wymagane id=czas_wykonywania_m name=czas_wykonywania_m value='0' maxlength=2 size=2 onKeyPress=\"return filterInput(1, event, false); \" onKeyUp=\"if ((event.keyCode==13) && (this.value!='')) { document.getElementById('submit').focus(); }\" onFocus=\"this.select(); return false;\" onBlur=\"if (this.value=='') this.value=0; \" /> minut";
			echo "<div id=StatusChanged_prepare>";
			echo "</div>";
			nowalinia();
			//echo "<input type=button value='test' onClick=\"test555();\" />";
		_td();
	echo "</tr>";	
	
	echo "<tr id=RodzajPojazdu style='display:none'>";
	td("140;r;<b>Rodzaj pojazdu</b>");
		td_(";;;");
			echo "<select class=wymagane name=hd_wyjazd_rp id=hd_wyjazd_rp onChange=\"if (this.value=='P') { $('#WpiszWyjazdTrasa').show(); $('#DataWyjazdu').show(); $('#WpiszWyjazdKm').show(); } else { $('#WpiszWyjazdTrasa').hide(); $('#DataWyjazdu').hide(); $('#WpiszWyjazdKm').hide(); } \">";
			echo "<option value='P'>prywatny</option>";
			echo "<option value='S' SELECTED>służbowy</option>";
			echo "</select>";
				
			//echo "<input type=radio name=hd_wyjazd_rp id=hd_wyjazd_rp_P value='P' checked=checked style=\"background-color:transparent;border:0px;\" onClick=\"$('#WpiszWyjazdTrasa').show(); $('#DataWyjazdu').show(); $('#WpiszWyjazdKm').show();\" />&nbsp;<a href=# class=normalfont onClick=\"if (document.getElementById('hd_wyjazd_rp_P').checked) { document.getElementById('hd_wyjazd_rp_P').checked=false; } else { document.getElementById('hd_wyjazd_rp_P').checked=true; } $('#WpiszWyjazdTrasa').show(); $('#DataWyjazdu').show(); $('#WpiszWyjazdKm').show(); \">prywatny</a>";
			//echo "<input type=radio name=hd_wyjazd_rp id=hd_wyjazd_rp_S value='S' style=\"background-color:transparent;border:0px;\" onClick=\"$('#WpiszWyjazdTrasa').hide(); $('#DataWyjazdu').hide(); $('#WpiszWyjazdKm').hide();\" />&nbsp;<a href=# class=normalfont onClick=\"if (document.getElementById('hd_wyjazd_rp_S').checked) { document.getElementById('hd_wyjazd_rp_S').checked=false; } else { document.getElementById('hd_wyjazd_rp_S').checked=true; } $('#WpiszWyjazdTrasa').hide(); $('#DataWyjazdu').hide(); $('#WpiszWyjazdKm').hide(); \">służbowy</a>";
		_td();
	echo "</tr>";
	
	echo "<tr id=WpiszWyjazdTrasa style='display:none'>";
	
	td("140;rt;<b>Trasa wyjazdowa</b>");
		td_(";;;");
			echo "<input class=buttons type=button onClick=\"if (document.getElementById('up_list').value!='') { document.getElementById('trasa').value=document.getElementById('lokalizacjazrodlowa').value.toUpperCase()+' - '+document.getElementById('up_list').value+' - '+document.getElementById('lokalizacjazrodlowa').value.toUpperCase(); document.getElementById('km').focus(); } else { alert('Nie wybrano komórki docelowej (punkt wyjazdowy)'); } \" value='Generuj trasę ze zgłoszenia'/>";
			nowalinia();
			
			echo "<textarea class=wymagane id=trasa name=trasa cols=50 rows=3></textarea>";
			echo "<a href=# class=normalfont onclick=\"document.getElementById('trasa').value=''; \" title=' Wyczyść trasę wyjazdową'> <img src=img/czysc.gif border=0 width=16 height=16></a>";
			//echo "</fieldset>";
		_td();
	echo "</tr>";
	
	echo "<tr id=DataWyjazdu style='display:none'>";
	td("140;rt;<b>Data wyjazdu</b>");
		td_(";;;");
			echo "<input type=text readonly name=hd_wyjazd_data id=hd_wyjazd_data value=$dddd style=\"background-color:transparent;border:0px;\">";
		_td();
	echo "</tr>";
		
	echo "<tr id=WpiszWyjazdKm style='display:none'>";
		td("140;rt;<b>Przejechane km</b>");
		td_(";;;");
			echo "<input class=wymagane id=km name=km style=text-align:right type=text size=3 maxlength=3 onKeyPress=\"return filterInput(1, event, false); \" onKeyUp=\"if ((event.keyCode==13)) { document.getElementById('submit').focus(); }\"> ";
		_td();
	echo "</tr>";
	
	echo "<tr id=CzasTrwaniaWyjazdu style='display:none;'>";
	td("140;rt;<b>Czas trwania przejazdu</b>");
		td_(";;;");
			echo "<input style=text-align:right type=text id=czas_przejazdu_h name=czas_przejazdu_h value='0' maxlength=2 size=2 onKeyPress=\"return filterInput(1, event, false); \" onKeyUp=\"if ((event.keyCode==13) && (this.value!='')) document.getElementById('czas_przejazdu_m').focus(); \" onFocus=\"this.select(); return false;\" onBlur=\"if (this.value=='') this.value=0; if (this.value>8) if (confirm('Wpisano więcej niż 8 godzin na przejazd. Potwierdzasz poprawność ?')) { return true; } else { this.value='0'; return false; }\" /> godzin";
			echo "&nbsp;";
			echo "<input style=text-align:right type=text id=czas_przejazdu_m name=czas_przejazdu_m value='0' maxlength=2 size=2 onKeyPress=\"return filterInput(1, event, false); \" onKeyUp=\"if ((event.keyCode==13) && (this.value!='')) { document.getElementById('submit').focus(); }\" onFocus=\"this.select(); return false;\" onBlur=\"if (this.value=='') this.value=0; \" /> minut";
			echo "<div id=StatusChanged_prepare2>";
			echo "</div>";
			echo "<br />";
		_td();
	echo "</tr>";
	
	echo "<tr id=UstalonaDataZakonczeniaTR style='display:none'>";
		echo "<td class=righttop style='border-right:1px solid grey'><b><font color=red>Szczegóły ustaleń&nbsp;</font></b></td>";
		td_(";;;");
			echo "<span id=UCR_info_AK style='display:none;'>";
				errorheader("<b>!!! UWAGA !!!</b><br /><br />Ustalony czas rozpoczęcia dla awarii krytycznych stosujemy w przypadkach, kiedy pracownik UP chce podjęcia działań przed otwarciem UP w kolejnym dniu roboczym<br />");
				echo "<br />";
			echo "</span>";
			$dddd = Date("Y-m-d");
			echo "<div id=NowaDataZakonczenia style='display:;'>";
				echo "<table style='border:0px solid'>";
				echo "<tr>";
				echo "<td class=right>";
				
					echo "<span id=UCR_opis_AZ_2 style='display:none;'>Data rozpoczęcia</span>";
					echo "<span id=UCR_opis_AK_2 style='display:none;'>Data rozpoczęcia</span>";
				
				echo "</td>";
				echo "<td>";
				
					echo "<select class=wymagane id=nowa_data_rozpoczecia name=nowa_data_rozpoczecia disabled=true onChange=\"document.getElementById('nowy_czas_rozpoczecia').value=''; document.getElementById('test_dayname').value=''; document.getElementById('test_gSTART').value=''; document.getElementById('test_gSTOP').value=''; document.getElementById('test_przesun').value=''; if (document.getElementById('kat_id').value=='2') { GetStopDateFromDate(this.value); } if (document.getElementById('kat_id').value=='6') { GetStartStopTimeFromDate(this.value); AnalyseTimes(); document.getElementById('nowy_czas_rozpoczecia').onblur();} \" onBlur=\"if (document.getElementById('kat_id').value=='6') { GetStartStopTimeFromDate(this.value); AnalyseTimes(); } \"/>\n";
					echo "<option value=''></option>\n";		
					echo "</select>\n";	

					echo "<span id=UCR_granica_AK style='display:none;'>&nbsp;|&nbsp;Graniczna data rozpoczęcia: ";
						echo "<input type=hidden id=test_maxDRa value=''>";
						echo "<input type=text id=test_maxDR readonly style='background-color:transparent;border:0px solid;font-weight:bold;' value='' >";					
					echo "</span>";
					
					//echo "<input type=text maxlength=10 value='' size=12 name=nowa_data_rozpoczecia2 id=nowa_data_rozpoczecia2 disabled=true onChange=\"document.getElementById('test_dayname').value=''; document.getElementById('test_gSTART').value=''; document.getElementById('test_gSTOP').value=''; document.getElementById('test_przesun').value=''; if (document.getElementById('kat_id').value=='2') { GetStopDateFromDate(this.value); } if (document.getElementById('kat_id').value=='6') { GetStartStopTimeFromDate(this.value); AnalyseTimes(); document.getElementById('nowy_czas_rozpoczecia').onblur();} \" onBlur=\"if (document.getElementById('kat_id').value=='6') { GetStartStopTimeFromDate(this.value); AnalyseTimes(); } \">";
					
					//echo "<input class=wymagane name=nowa_data_rozpoczecia id=nowa_data_rozpoczecia type=text size=8 maxlength=10 onkeypress=\"return filterInput(1, event, false, '-'); \" onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\" onFocus=\"if (document.getElementById('UstalonaDataZakonczeniaCheck').checked) document.getElementById('status_id').value='3';\" onKeyUp=\"DopiszKreski('nowa_data_rozpoczecia');\" onBlur=\"CheckDate(this.value); document.getElementById('nowy_czas_rozpoczecia').value='07:00'; \">&nbsp;";
					//echo "<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif width=16 height=16 border=0></a>&nbsp;";
//					if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('nowa_data_rozpoczecia').value='".Date('Y-m-d')."'; return false;\">";

					echo "<br /><span id=InfoMinDate></span>";
				echo "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td class=righttop>";
					echo "<span id=UCR_opis_AZ_3 style='display:none;'>Godzina rozpoczęcia</span>";
					echo "<span id=UCR_opis_AK_3 style='display:none;'>Godzina rozpoczęcia</span>";
					
					echo "<span id=UCR_opis_AK_4r style='display:none;'></span>";
					echo "<span id=UCR_opis_AK_4z style='display:none;'></span>";
					
				echo "</td>";
				echo "<td>";
					echo "<input class=wymagane type=text name=nowy_czas_rozpoczecia id=nowy_czas_rozpoczecia value='' maxlength=5 size=3 onKeyPress=\"return filterInput(1, event, false, ''); \" onKeyUp=\"DopiszDwukropek1('nowy_czas_rozpoczecia');\" onBlur=\"CheckTimePrzesuniecia(this.value);\" />";
					
					echo "<input type=hidden id=test_g1 value='' >";
					echo "<input type=hidden id=test_g value='' >";
					echo "<input type=hidden id=test_g3 value='' >";
					
					echo "<input type=hidden name=test_dayname readonly id=test_dayname value='' >";
					
					echo "<input type=hidden name=test_gSTART id=test_gSTART value='' >";
					echo "<input type=hidden name=test_gSTOP id=test_gSTOP value='' >";
					echo "<input type=hidden name=test_przesun id=test_przesun value='' >";
					
				echo "</td>";
				echo "</tr>";
				
				echo "<tr id=UCR_info_AK2a>";
					echo "<td colspan=2>";					
						echo "<div id=UCR_info_AK2 style='display:none;'>";
							errorheader("Jeżeli wpisana godzina rozpoczęcia będzie przed rozpoczęciem pracy komórki lub po jej zakończeniu - będzie to skutkowało zmianą godzin pracy komórki w tym dniu.");
							echo "<br />";
						echo "</div>";
					echo "</td>";
				echo "</tr>";
				
				echo "<tr>";
				echo "<td class=right>Osoba potwierdzająca ustalenia<br />( ze strony Poczty )</td>";
				echo "<td>";
					echo "<input class=wymagane type=text name=hd_opp id=hd_opp size=38 maxlength=30 onKeyPress=\"return filterInput(0, event, false, ' ęóąśłżźćńĘÓĄŚŁŻŹĆŃ'); \" onFocus=\"this.select();\" onBlur=\"cUpper(this); if (OdrzucNiedozwolone('hd_opp',document.getElementById('hd_opp').value)==1) return false;\" >";
					echo "&nbsp;<input type=button class=buttons value=' Osoba zgłaszająca ' onClick=\"document.getElementById('hd_opp').value=document.getElementById('hd_oz').value;\">";
				echo "</td>";
				echo "</tr>";
				
				echo "<tr><td colspan=2 class=center><br />";
				errorheader(">>> O ustaleniach należy poinformować drogą mailową koordynatora ze strony Poczty <<<");
				echo "</td></tr>";
				
				echo "</table>";
			echo "</div>";
		_td();
	_tr();
	
	echo "<tr id=OsobaPotwierdzajacaZamkniecie style='display:' >";
		td("140;r;Osoba potwierdzająca<br />zamknięcie");
		td_(";;;");
			echo "<input type=text name=hd_opz id=hd_opz size=38 maxlength=30 onFocus=\"this.select();\" onKeyPress=\"return filterInput(0, event, false, ' ęóąśłżźćńĘÓĄŚŁŻŹĆŃ'); \" onBlur=\"cUpper(this);	if (OdrzucNiedozwolone('hd_opz',document.getElementById('hd_opz').value)==1) return false;\" >";
			echo "&nbsp;<input type=button class=buttons value=' Osoba zgłaszająca ' onClick=\"document.getElementById('hd_opz').value=document.getElementById('hd_oz').value;\">";
		_td();
	echo "</tr>";

	echo "<tr id=ZasadnoscZgloszenia style='display:' >";
		td("140;r;Zasadność zgłoszenia");
		td_(";;;");
			echo "<input type=radio style='border:0px' name=zasadne id=zasadne value='TAK' CHECKED>TAK";
			echo "&nbsp;&nbsp;<input type=radio style='border:0px' name=zasadne id=zasadne value='NIE'>NIE";
		_td();
	echo "</tr>";
	
tbl_empty_row(1);
endtable();
startbuttonsarea("right");
oddziel();
echo "<span style='float:left'>";

echo "<input class=border0 type=checkbox name=czy_synchronizowac id=czy_synchronizowac checked=checked><a tabindex=-1 class=normalfont href=# onClick=\"if (document.getElementById('czy_synchronizowac').checked) { document.getElementById('czy_synchronizowac').checked=false; return false; } else { document.getElementById('czy_synchronizowac').checked=true; return false; }\"><font color=red>&nbsp;Widoczne dla Poczty</font></a>";

echo "<span id=show_ww>";
	echo " | <input class=border0 type=checkbox name=wymaga_wyjazdu id=wymaga_wyjazdu><a tabindex=-1 class=normalfont href=# onClick=\"if (document.getElementById('wymaga_wyjazdu').checked) { document.getElementById('wymaga_wyjazdu').checked=false; return false; } else { document.getElementById('wymaga_wyjazdu').checked=true; return false; }\"><font color=blue>&nbsp;Wymaga wyjazdu</font></a>";
echo "</span>";
echo "</span>";

echo "<input id=submit type=submit class=buttons style='font-weight:bold;' name=submit value='Zapisz' />";
echo "<input id=reset type=button class=buttons name=reset value=\"Wyczyść zgłoszenie\" onClick=\"pytanie_wyczysc('Wyczyścić formularz ?'); \" />";
echo "<input id=anuluj class=buttons type=button onClick=\"pytanie_anuluj('Potwierdzasz anulowanie wpisanego zgłoszenia ?');\" value=Anuluj>";

//echo "<span id=Saving2 style=display:none><b><font color=red>Trwa zapisywanie zgłoszenia... proszę czekać&nbsp;</font></b><input class=imgoption type=image src=img/loader.gif></span>";


endbuttonsarea();

echo "<input type=hidden name=tuser value='$currentuser'>";
echo "<input type=hidden name=up_list_id id=up_list_id "; 
if (($_GET[parent_zgl]!='') && ($_GET[parent_zgl]!=0)) {
	echo " value=$komorka_id ";
}

if (($_REQUEST[fromtask]=='1') && ($_REQUEST[komorka]!='')) {
	echo " value=$komorka_id ";
}
echo "/>";

echo "<span id=up_list_id1 style='display:none'></span>";

//echo "<input type=button class=buttons name=aaaa value='test' onClick=\"alert(document.getElementById('kat_id').value); alert(document.getElementById('podkat_id').value);\" />";

echo "<input type=hidden name=stage id=stage value='$_GET[stage]' />";
echo "<input type=hidden name=naprawa_id id=stage value='$_REQUEST[naprawa_id]' />";

echo "<input type=hidden id=unique_nr1 name=unique_nr1 value='$unique_nr1' />";

echo "<input type=hidden id=wrk name=wrk />";

echo "<input type=hidden name=ss_id id=ss_id value='$_REQUEST[ss_id]' />";

echo "<input type=hidden name=up_nazwa1 id=up_nazwa1 />";
echo "<input type=hidden name=up_ip1 id=up_ip1 />";
echo "<input type=hidden name=fromtask id=fromtask value='$_REQUEST[fromtask]' />";

echo "<input type=hidden name=zid value='$_REQUEST[zid]' />";
echo "<input type=hidden name=zadanie value='$_REQUEST[zadanie]' />";
echo "<input type=hidden name=podkat_opis value='$_REQUEST[podkat_opis]' />";

if ($_REQUEST[zgl_reklamacyjne]=='1') {	
	echo "<input type=hidden name=zgl_reklamacyjne id=zgl_reklamacyjne value='1'/>";
	echo "<input type=hidden name=zgl_reklamacyjne_do_zgl id=zgl_reklamacyjne_do_zgl value='$_REQUEST[zgl_reklamacyjne_do_zgl_nr]'/>";
	echo "<input type=hidden name=zgl_reklamacyjne_unique id=zgl_reklamacyjne_unique value='$_REQUEST[uniquenr_zgl_reklamowanego]'/>";
} else {
	echo "<input type=hidden name=zgl_reklamacyjne id=zgl_reklamacyjne value='0'/>";
	echo "<input type=hidden name=zgl_reklamacyjne_do_zgl id=zgl_reklamacyjne_do_zgl value='0'/>";
	echo "<input type=hidden name=zgl_reklamacyjne_unique id=zgl_reklamacyjne_unique value='$_REQUEST[uniquenr_zgl_reklamowanego]'/>";	
}
echo "<input type=hidden name=zgl_reklamacyjne value='$_REQUEST[zgl_reklamacyjne]' />";

if ($_GET[special]=='wan') {
?>
<script>
document.getElementById('up_list').focus();
document.getElementById('hd_oz').focus();

<?php if (($_GET[special]=='wan') && ($_GET[action]=='close')) { ?>
document.getElementById('StatusZakonczony').style.display='';
document.getElementById('ZasadnoscZgloszenia').style.display='';
document.getElementById('StatusZakonczony').focus();
<?php } ?>

</script>
<?php
}

_form();

echo "</div>";

}
?>
<script>ShowWaitingMessage('Trwa zapisywanie danych... proszę czekać', 'Saving');</script>
<?php

if (($_GET[parent_zgl]!='') && ($_GET[parent_zgl]!=0)) {
?>
<script>
StatusChanged(2,3,'Status');
</script>
<?php 
}
?>

<script>
GenerateOnClickEventForSlownikTresci();
</script>

<?php if ($_REQUEST[fromtask]=='1') { ?>
<script>
	KopiujDo1Entera(document.getElementById('hd_tresc').value,'hd_temat');
</script>
<?php } ?>

<script type="text/javascript" src="js/jquery/entertotab_min.js"></script>
<script type='text/javascript'>
	EnterToTab.init(document.forms.hd_dodaj_zgl, false);
</script>

<?php

if ($_REQUEST[zgl_reklamacyjne]=='1') {	?>
<script>
document.getElementById('up_list').value='<?php echo $_REQUEST[komorka]; ?>';
SprawdzKomorke(document.getElementById('up_list').value);
cUpper_k(document.getElementById('up_list'));
MakePodkategoriaList(<?php echo $_REQUEST[zglkat]; ?>);
$('#podkat_id').val(<?php echo $_REQUEST[zglpodkat]; ?>);
$('#status_id').val(3);
StatusChanged(<?php echo $_REQUEST[zglkat]; ?>,document.getElementById('status_id').value,'Status'); 
GenerateOnClickEventForSlownikTresci();
</script>
<?php } 

if (($_REQUEST[komorka]!='') && ($_REQUEST[zgl_reklamacyjne]!='1') && ($_REQUEST[fromtask]!=1) && ($_REQUEST[zadanie]=='')) {	?>
<script>
document.getElementById('up_list').value='<?php echo $_REQUEST[komorka]; ?>';
SprawdzKomorke(document.getElementById('up_list').value);
cUpper_k(document.getElementById('up_list'));
GenerateOnClickEventForSlownikTresci();
document.getElementById('hd_oz').value='<?php echo $_REQUEST[osoba_zgl]; ?>';
document.getElementById('hdoztelefon').value='<?php echo $_REQUEST[osoba_zgl_tel]; ?>';
</script>
<?php } ?>

<?php 
if (($_REQUEST[fromtask]==1) && ($_REQUEST[zadanie]!='')) {
?>
<script>
document.getElementById('up_list').value='<?php echo $_REQUEST[komorka]; ?>';
cUpper_k(document.getElementById('up_list'));
SprawdzKomorke(document.getElementById('up_list').value);
</script>
<?php 
}
?>

<?php if ($_REQUEST[parent_zgl]!='') { ?>
<script>
<?php if ($zgloszenie_kat==2) { ?>
	MakePodkategoriaList(2);
<?php } else { ?>
	MakePodkategoriaList(document.getElementById('kat_id').options[document.getElementById('kat_id').options.selectedIndex].value);
<?php } ?>
document.getElementById('podkat_id').value = '<?php echo $zgloszenie_podkat; ?>';
document.getElementById("OsobaPotwierdzajacaZamkniecie").style.display = 'none';
ShowHints(document.getElementById('kat_id').value,document.getElementById('podat_id').value);
</script>
<?php } ?>

<?php
if (($_REQUEST[zadanie]!='') && ($_REQUEST[fromtask]==1) && ($_REQUEST[podkat_opis]=='')) {
?>
<script>
//StatusChanged(document.getElementById('kat_id').value,document.getElementById('status_id').value,'Kategoria');
MakePodkategoriaList(3);
var ss = document.getElementById('status_id');
ss.options.length=0;
//ss.options[ss.options.length] = new Option("przypisane","2",false,false);
ss.options[ss.options.length] = new Option("nowe","1",false,false);
ss.options[ss.options.length] = new Option("rozpoczęte - nie zakończone","7",false,false);
ss.options[ss.options.length] = new Option("zamknięte","9",true,true);
</script><?php } ?>

<?php 
if ($_REQUEST[dlakomorki]!='') {
?>
<script>
document.getElementById('up_list').value='<?php echo $_REQUEST[dlakomorki]; ?>';
cUpper_k(document.getElementById('up_list'));
SprawdzKomorke(document.getElementById('up_list').value);
MakePodkategoriaList(1);
document.getElementById('hd_oz').focus();
</script>
<?php 
}
?>
<?php

if ((	($_REQUEST[zgl_reklamacyjne]=='1') || 
		(($_REQUEST[fromtask]==1) && ($_REQUEST[zadanie]!='')) || 
		($_REQUEST[dlakomorki]!='')
	) || (	
	(($_REQUEST[komorka]!='') && ($_REQUEST[zgl_reklamacyjne]!='1') && ($_REQUEST[fromtask]!=1) && ($_REQUEST[zadanie]==''))
	) 	
	) {
		
		$komorka99 = $_REQUEST[komorka];
		if ($_REQUEST[dlakomorki]!='') $komorka99 = $_REQUEST[dlakomorki];
		$komorka99 = toUpper($komorka99);
		
		$sql = "SELECT serwis_komorki.up_id, serwis_komorki.up_nazwa, serwis_piony.pion_nazwa, serwis_komorki.up_umowa_id, serwis_komorki.up_adres, serwis_komorki.up_telefon, serwis_komorki.up_nrwanportu, serwis_komorki.up_ip, serwis_komorki.up_working_time, serwis_komorki.up_working_time_alternative, serwis_komorki.up_working_time_alternative_start_date, serwis_komorki.up_working_time_alternative_stop_date, serwis_komorki.up_typ_uslugi, serwis_komorki.up_kategoria, serwis_komorki.up_przypisanie_jednostki FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) and (UCASE(CONCAT(serwis_piony.pion_nazwa,' ',serwis_komorki.up_nazwa)='".$komorka99."'))) LIMIT 1";

		$rsd = mysql_query($sql,$conn);
		while($rs = mysql_fetch_array($rsd)) {
			$cid = $rs['up_id'];
			$cnazwa = $rs['up_nazwa'];
			$cpion = $rs['pion_nazwa'];
			$cumid = $rs['up_umowa_id'];
			
			$clok = $rs['up_adres'];
			$ctel = $rs['up_telefon'];
			$cnrwanportu = $rs['up_nrwanportu'];
			$cip = $rs['up_ip'];
			
			$cwt = $rs['up_working_time'];
			$cwta = $rs['up_working_time_alternative'];
			$cwta1 = $rs['up_working_time_alternative_start_date'];
			$cwta2 = $rs['up_working_time_alternative_stop_date'];
			
			$ctu = $rs['up_typ_uslugi'];
			$cku = $rs['up_kategoria'];
			$cpj = $rs['up_przypisanie_jednostki'];
		}
		
	?>
	<script>
		$("#EW_S").show();
		$("#hdtu").val('<?php echo $ctu; ?>');		
		$("#kategoria_komorki").val('<?php echo $cku; ?>');		
		$("#przypisanie_jednostki").val('<?php echo $cpj; ?>');		
		
		$("#up_list_id").val('<?php echo $cid; ?>');
		$("#up_wanport").val('<?php echo $cnrwanportu; ?>');

		$("#up_lokalizacja").val('<?php echo $clok; ?>');
		$("#up_nazwa1").val('<?php echo $cnazwa; ?>');

		$("#up_telefon").val('<?php echo $ctel; ?>'); 
		<?php if ($ctel!='') { ?>
			$("#tel_do_komorki_title").show();
			$("#tel_do_komorki").val('<?php echo $ctel; ?>');
		<?php } ?>

		$("#up_ip1").val('<?php echo $cip; ?>');
		<?php if ($cip!='') { ?>
			$("#adres_podsieci_title").show();
			$("#up_adres_podsieci").val('<?php echo $cip; ?>');
		<?php } ?>

		$('#lista_zgloszen_data').load('wait_ajax.php?randval='+ Math.random());	
		$('#lista_zgloszen_data').load('hd_g_lista_zgloszen.php?randval='+ Math.random() +'&komorka=<?php echo urlencode($komorka99); ?>');

		var data8 = '<?php echo $cwt; ?>';
		var data9 = '<?php echo $cwta; ?>';
		var data10 = '<?php echo $cwta1; ?>';
		var data11 = '<?php echo $cwta2; ?>';

					if (data8!='') {
						var pn = data8.split(";")[0].split("@");	$("#PN").text(pn[1]); 
						var wt = data8.split(";")[1].split("@");	$("#WT").text(wt[1]); 
						var sr = data8.split(";")[2].split("@");	$("#SR").text(sr[1]); 
						var cz = data8.split(";")[3].split("@");	$("#CZ").text(cz[1]); 
						var pt = data8.split(";")[4].split("@");	$("#PT").text(pt[1]); 
						var so = data8.split(";")[5].split("@");	$("#SO").text(so[1]); 
						var ni = data8.split(";")[6].split("@");	$("#NI").text(ni[1]); 
						$("#none_gp").hide();
						$("#gp_default").hide();
						$("#gp").show();
						$("#gpa").hide();
					} else {
						$("#PN").text('?'); 
						$("#WT").text('?'); 
						$("#SR").text('?'); 
						$("#CZ").text('?'); 
						$("#PT").text('?'); 
						$("#SO").text('?'); 
						$("#NI").text('?'); 
								
						$("#gp").hide();
						$("#gpa").hide();
						$("#none_gp").show();
						$("#gp_default").show();
						
					}
					document.getElementById('test_g3').value = data[8];
					
					if ((data10!='0000-00-00') && (data11!='0000-00-00')) {
						var s1 = data10;
						var s2 = data11;
						var y = $('#hddz').val();
					
						y1 = y.substring(0,5);
						
						s1 = y1 + s1.substring(5,10);
						s2 = y1 + s2.substring(5,10);
						
						if ((y>=s1) && (y<=s2)) {
							$("#gp_active").hide();
							$("#gpa_active").show();
							$("#gpa").show();
							$("#gp").hide();
						} else {
							$("#gp_active").show();
							$("#gpa_active").hide();
							$("#gp").show();
							$("#gpa").hide();
						}
						
						$("#gpa_start").text(s1);
						$("#gpa_stop").text(s2);
						
						if (data9!='') {
							var pn = data9.split(";")[0].split("@");	$("#PNa").text(pn[1]); 
							var wt = data9.split(";")[1].split("@");	$("#WTa").text(wt[1]); 
							var sr = data9.split(";")[2].split("@");	$("#SRa").text(sr[1]); 
							var cz = data9.split(";")[3].split("@");	$("#CZa").text(cz[1]); 
							var pt = data9.split(";")[4].split("@");	$("#PTa").text(pt[1]); 
							var so = data9.split(";")[5].split("@");	$("#SOa").text(so[1]); 
							var ni = data9.split(";")[6].split("@");	$("#NIa").text(ni[1]); 
							$("#none_gp").hide();
							$("#gp_default").hide();
							//$("#gpa").show();
						} else {
							$("#PNa").text('?'); 
							$("#WTa").text('?'); 
							$("#SRa").text('?'); 
							$("#CZa").text('?'); 
							$("#PTa").text('?'); 
							$("#SOa").text('?'); 
							$("#NIa").text('?'); 
							
							$("#none_gp").show();
							$("#gp_default").show();
							$("#gp").hide();
							$("#gpa").hide();
						}
						document.getElementById('test_g3').value = data[10];
					}	
	</script>	
	<?php 
}

?>
<script>
ShowHints(document.getElementById('kat_id').value,document.getElementById('podkat_id').value);
GenerateSubPodkategoriaList(document.getElementById('kat_id').value,document.getElementById('podkat_id').value); 
<?php if ($_REQUEST[parent_zgl]!='') { ?>
document.getElementById('sub_podkat_id').value='<?php echo $podkat_poziom_2_opis; ?>';
<?php } ?>
</script>
<script>GetStopDateFromDate(document.getElementById('nowa_data_rozpoczecia').value);</script>
<script>HideWaitingMessage();</script>
</body>
</html>