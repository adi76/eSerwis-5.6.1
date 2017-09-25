<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body onLoad="document.forms[0].elements[1].focus();">
<?php 
function ClearOutputText($s) {
	$s = str_replace("\\\\", "\\", $s);
	$s = str_replace("\\\"", "`", $s);
	$s = str_replace("\\'", "`", $s);
	$s = str_replace("\\", "/", $s);
	//$s = str_replace("\\'", "'", $s);
	//$s = str_replace("\\'", "'", $s);
	return $s;
}
if ($submit) { 

	function toUpper($string) {
		$string = strtr($string,'qwertyuiopasdfghjklzxcvbnm','QWERTYUIOPASDFGHJKLZXCVBNM');
		return (strtr($string, 'ęóąśłżźćń','ĘÓĄŚŁŻŹĆŃ' )); 
	}; 

	$_POST=sanitize($_POST);
	
	$dddd = date("Y-m-d H:i:s");
	$lista_zmian = '';
	$sql = '';
	
	if ($_POST[element]=='trasa') {
		$sql_a = "UPDATE $dbname_hd.hd_zgloszenie_szcz SET zgl_szcz_il_km='$_POST[km]', zgl_szcz_czas_trwania_wyjadu='$_POST[wyjazd_czas_trwania]' WHERE (zgl_szcz_id='$_POST[zgl_krok_id]') and (zgl_szcz_nr_kroku='$_POST[nr]') LIMIT 1";
		
		if (mysql_query($sql_a, $conn_hd)) { 
			$sql_a = "UPDATE $dbname_hd.hd_zgloszenie_wyjazd SET wyjazd_data='$_POST[hd_zam_data]', wyjazd_trasa='$_POST[trasa]', wyjazd_km='$_POST[km]', wyjazd_czas_trwania = '$_POST[wyjazd_czas_trwania]' WHERE wyjazd_zgl_szcz_id='$_POST[unique_nr]' LIMIT 1";
			
				if (mysql_query($sql_a, $conn_hd)) {
					$r3 = mysql_query("SELECT sum(zgl_szcz_il_km) FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$_REQUEST[id]') and (zgl_szcz_widoczne=1)", $conn_hd) or die($k_b);
					list($razem_km)=mysql_fetch_array($r3);
					
					$sql_a = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_razem_km=$razem_km WHERE ((zgl_widoczne=1) and (zgl_id='$_REQUEST[id]')) LIMIT 1";
					
				}
				
		}
		
		if ($_POST[old_wyjazd_data]!=$_POST[hd_zam_data]) {
			$lista_zmian.='<u>Zmiana daty wyjazdu:</u> <b>'.$_POST[old_wyjazd_data].'</b> -> <b>'.$_POST[hd_zam_data].'</b><br />';
		}
		if ($_POST[old_wyjazd_trasa]!=$_POST[trasa]) {
			$lista_zmian.='<u>Zmiana trasy wyjazdu:</u> <b>'.$_POST[old_wyjazd_trasa].'</b> -> <b>'.$_POST[trasa].'</b><br />';
		}
		if ($_POST[old_wyjazd_km]!=$_POST[km]) {
			$lista_zmian.='<u>Zmiana ilości km:</u> <b>'.$_POST[old_wyjazd_km].'</b> -> <b>'.$_POST[km].'</b><br />';
		}
		if ($_POST[old_wyjazd_czas_trwania]!=$_POST[wyjazd_czas_trwania]) {
			$lista_zmian.='<u>Zmiana czas trwania przejazdu:</u> <b>'.$_POST[old_wyjazd_czas_trwania].' min.</b> -> <b>'.$_POST[wyjazd_czas_trwania].' min. </b><br />';
		}	
	}
	
	if ($_POST[element]=='czas') {
		$d_cw = 0;	// czas wykonywania
		if ($_POST[czas_wykonywania_h]!='') { $h_na_m = (int) $_POST[czas_wykonywania_h]*60; }
		if ($_POST[czas_wykonywania_m]!='') { $m_na_m = (int) $_POST[czas_wykonywania_m]; }
		
		$d_cw=$h_na_m+$m_na_m;
	
		if ($_REQUEST[zmiana_daty]=='1') {
			$nowa_data_wykonania = $_REQUEST[data_wykonywania_d].' '.$_REQUEST[data_wykonywania_g].':00';
			$sql_a = "UPDATE $dbname_hd.hd_zgloszenie_szcz SET zgl_szcz_czas_wykonywania='$d_cw', zgl_szcz_czas_rozpoczecia_kroku ='$nowa_data_wykonania' WHERE (zgl_szcz_id='$_POST[zgl_krok_id]') and (zgl_szcz_nr_kroku='$_POST[nr]') LIMIT 1";
		} else {
			$sql_a = "UPDATE $dbname_hd.hd_zgloszenie_szcz SET zgl_szcz_czas_wykonywania='$d_cw' WHERE (zgl_szcz_id='$_POST[zgl_krok_id]') and (zgl_szcz_nr_kroku='$_POST[nr]') LIMIT 1";
		}
		
		if (mysql_query($sql_a, $conn_hd)) { 
			
			// jeżeli nr edytowanego kroku = 1 wtedy zmień datę rejestracji zgłoszenia na taką samą
			if (($_REQUEST[nr]=='1') && ($_REQUEST[zmiana_daty]=='1')) {
				$sql_a1 = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_data='".$_REQUEST[data_wykonywania_d]."', zgl_godzina='".$_REQUEST[data_wykonywania_g].":00' WHERE ((zgl_widoczne=1) and (zgl_id='$_REQUEST[id]')) LIMIT 1";
				$wykonaj_a1 = mysql_query($sql_a1, $conn_hd);
			}
			
			// uaktualnienie daty ostatniej zmiany statusu
			list($temp_dzs1,$temp_cwk)=mysql_fetch_array(mysql_query("SELECT zgl_szcz_czas_rozpoczecia_kroku,zgl_szcz_czas_wykonywania FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_widoczne=1) and (zgl_szcz_zgl_id='$_REQUEST[id]') ORDER BY zgl_szcz_nr_kroku DESC LIMIT 1", $conn_hd));
			$temp_dzs1 = AddMinutesToDate($temp_cwk,$temp_dzs1);
			
			$sql_a1 = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_data_zmiany_statusu='".$temp_dzs1."' WHERE ((zgl_widoczne=1) and (zgl_id='$_REQUEST[id]')) LIMIT 1";
			$wykonaj_a1 = mysql_query($sql_a1, $conn_hd);

			$r3 = mysql_query("SELECT sum(zgl_szcz_czas_wykonywania) FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$_REQUEST[id]')", $conn_hd) or die($k_b);
			list($razem_czas)=mysql_fetch_array($r3);
			$sql_a = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_razem_czas=$razem_czas WHERE ((zgl_widoczne=1) and (zgl_id='$_REQUEST[id]')) LIMIT 1";
		}	

		if ($_POST[zmiana_daty]==1) {
			if ($_POST[old_zgl_szcz_czas_wykonywania]!=$d_cw) {
				$lista_zmian.='<u>Zmiana czasu wykonywania kroku:</u> <b>'.$_POST[old_zgl_szcz_czas_wykonywania].' min</b> -> <b>'.$d_cw.' min</b><br />';
			}

			if ($_POST[old_zgl_szcz_czas_rozpoczecia_kroku]!=$nowa_data_wykonania) {
				$lista_zmian.='<u>Zmiana czasu rozpoczęcia kroku:</u> <b>'.$_POST[old_zgl_szcz_czas_rozpoczecia_kroku].'</b> -> <b>'.$nowa_data_wykonania.'</b><br />';
			}
		}
		
	}
	
	if ($_POST[element]=='czas') {
		if ($_POST[nr]==1) {
			$r3 = mysql_query("SELECT zgl_kategoria, zgl_data, zgl_godzina, zgl_podkategoria, zgl_komorka_working_hours, zgl_komorka FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id='$_REQUEST[zglnr]')", $conn_hd) or die($k_b);
			list($_temp_kategoria, $_temp_zgl_data, $_temp_zgl_godz, $zgl_podkategoria, $week, $temp_komorka)=mysql_fetch_array($r3);
			//echo "SELECT up_id FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((serwis_komorki.belongs_to=$es_filia) and (serwis_piony.pion_id=serwis_komorki.up_pion_id) and (UPPER(CONCAT(serwis_piony.pion_nazwa,' ',serwis_komorki.up_nazwa))='".toUpper($temp_komorka)."'))";
			
			$r44 = mysql_query("SELECT up_id FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((serwis_komorki.belongs_to=$es_filia) and (serwis_piony.pion_id=serwis_komorki.up_pion_id) and (UPPER(CONCAT(serwis_piony.pion_nazwa,' ',serwis_komorki.up_nazwa))='".toUpper($temp_komorka)."'))", $conn) or die($k_b);
			list($_temp_upid)=mysql_fetch_array($r44);

			$r2 = mysql_query("SELECT up_working_time,up_working_time_alternative,up_working_time_alternative_start_date,up_working_time_alternative_stop_date FROM $dbname.serwis_komorki WHERE (up_id='$_temp_upid') LIMIT 1", $conn_hd) or die($k_b);
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
	
			if (($_temp_kategoria=='2') || ($_temp_kategoria=='6')) {
			
			
				$DataWpisu = $_temp_zgl_data;
				
				$MinutDoRozpoczecia = 0;
				$MinutDoZakonczenia = 0;
				
				if (($_temp_kategoria=='2') || ($_temp_kategoria=='6')) {
						
					$r2 = mysql_query("SELECT up_kategoria FROM $dbname.serwis_komorki WHERE (up_id='$_temp_upid') LIMIT 1", $conn_hd) or die($k_b);				
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

				switch ($_temp_kategoria) {
					case "6" : 	{	$DataRozpoczecia = AddHoursToDate($CzasNaRozpoczecie,$DataGodzinaWpisu)."";		
									$DataZakonczenia = AddHoursToDate($CzasNaZakonczenie,$DataGodzinaWpisu)."";
										
									$HoursToStart = $CzasNaRozpoczecie;
									$HoursToFinish = "8";
										
									$DW = $_temp_zgl_data;
									$GW = $_temp_zgl_godz;

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
					
					case "2" : 	if (($zgl_podkategoria=='2') || ($zgl_podkategoria=='5') || ($zgl_podkategoria=='7')) { // oprogramowanie 
									
										$y = 1;
										$max = 1;
										while (2==2):	
											$_next_working_hours = godzina_stop1(AddWorkingDays_UP($y,$DataWpisu,$week),$week);
											$DataRozpoczecia = AddWorkingDays_UP($y,$DataWpisu,$week)." ".$_next_working_hours."";
											//$DataZakonczenia = AddWorkingDays_UP("2","".$DataWpisu."",$week)." ".$_temp_zgl_godz."";	
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
										$DataZakonczenia = AddWorkingDays("5","".$DataWpisu."")." ".$_temp_zgl_godz."";
									*/
										
										$DataGodzinaWpisu1 = $_temp_zgl_data." ".$_temp_zgl_godz.":00";
										$MinutDoRozpoczecia = MinutesBetween($DataGodzinaWpisu1, $DataRozpoczecia, $week, $serwis_working_time);
										$MinutDoZakonczenia = MinutesBetween($DataGodzinaWpisu1, $DataZakonczenia, $week, $serwis_working_time);

										$HoursToStart = "-";
										$HoursToFinish = ""; $DaysToFinish = "5";
										
									}
									
									if (($zgl_podkategoria=='3') || ($zgl_podkategoria=='4')) {	// stacja robocza, serwer
										$y = 1;
										$max = 1;
										while (2==2):
											$_next_working_hours = godzina_stop1(AddWorkingDays_UP($y,$DataWpisu,$week),$week);
											$DataRozpoczecia = AddWorkingDays_UP($y,$DataWpisu,$week)." ".$_next_working_hours."";
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
										
										//echo "_next_working_hours : ".$_next_working_hours;
									/*
										$_next_working_hours = godzina_stop1(AddWorkingDays("1",$DataWpisu),$week);
										$DataRozpoczecia = AddWorkingDays("1",$DataWpisu)." ".$_next_working_hours."";
										$DataZakonczenia = AddWorkingDays("14","".$DataWpisu."")." ".$_temp_zgl_godz."";
									*/
									
										$DataGodzinaWpisu1 = $_temp_zgl_data." ".$_temp_zgl_godz.":00";
										$MinutDoRozpoczecia = MinutesBetween($DataGodzinaWpisu1, $DataRozpoczecia, $week, $serwis_working_time);
										$MinutDoZakonczenia = MinutesBetween($DataGodzinaWpisu1, $DataZakonczenia, $week, $serwis_working_time);
										
										$HoursToStart = "-";
										$HoursToFinish = ""; $DaysToFinish = "14";
									}
									
									if (($zgl_podkategoria=='9') || ($zgl_podkategoria=='0')) { // urządzenia peryferyjne, WAN/LAN
									
										$y = 1;
										$max = 1;
										while (2==2):	
											$_next_working_hours = godzina_stop1(AddWorkingDays_UP($y,$DataWpisu,$week),$week);
											$DataRozpoczecia = AddWorkingDays_UP($y,$DataWpisu,$week)." ".$_next_working_hours."";
											//$DataZakonczenia = AddWorkingDays_UP("2","".$DataWpisu."",$week)." ".$_temp_zgl_godz."";	
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
										$DataZakonczenia = AddWorkingDays("14","".$DataWpisu."")." ".$_temp_zgl_godz."";
									*/
									
										$DataGodzinaWpisu1 = $_temp_zgl_data." ".$_temp_zgl_godz.":00";
										$MinutDoRozpoczecia = MinutesBetween($DataGodzinaWpisu1, $DataRozpoczecia, $week, $serwis_working_time);
										$MinutDoZakonczenia = MinutesBetween($DataGodzinaWpisu1, $DataZakonczenia, $week, $serwis_working_time);
									
										$HoursToStart = "-";
										$HoursToFinish = ""; $DaysToFinish = "14";
									}
				
								break;
								
					default : $DataRozpoczecia=''; $DataZakonczenia='';
				}
				
				$sql_a1 = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_data_rozpoczecia='".$DataRozpoczecia."', zgl_data_zakonczenia='".$DataZakonczenia."', zgl_rozpoczecie_min='".$MinutDoRozpoczecia."', zgl_zakonczenie_min='".$MinutDoZakonczenia."' WHERE ((zgl_widoczne=1) and (zgl_id='$_REQUEST[zglnr]')) LIMIT 1";
				$wykonaj_a1 = mysql_query($sql_a1, $conn_hd);
				
				//echo $sql_a1;
				

			}
		}
	}
	
	
	if ($_POST[element]=='czynnosc') {
		$sql_a = "UPDATE $dbname_hd.hd_zgloszenie_szcz SET zgl_szcz_wykonane_czynnosci='$_POST[hd_tresc]' WHERE (zgl_szcz_id='$_POST[zgl_krok_id]') and (zgl_szcz_nr_kroku='$_POST[nr]') LIMIT 1";
		
		if ($_POST[old_zgl_szcz_wykonane_czynnosci]!=$_POST[hd_tresc]) {
			$lista_zmian.='<u>Zmiana wykonanych czynności:</u> <b>'.$_POST[old_zgl_szcz_wykonane_czynnosci].'</b> -> <b>'.$_POST[hd_tresc].'</b><br />';
		}
	}	

	if (mysql_query($sql_a, $conn_hd)) { 
	
		if ($lista_zmian!='') {
			$sql_insert = "INSERT INTO $dbname_hd.hd_zgloszenie_kroki_historia_zmian values ('', '$_POST[zgl_krok_id]','$dddd','$currentuser','$lista_zmian',1,'',$es_filia)";
			//echo "$sql_insert";
			$wynik = mysql_query($sql_insert, $conn_hd);		
		}
	
			?>
			<script> 
				if (opener) opener.location.reload(true); 
				self.close(); 
				newWindow(600,50,'hd_o_zgloszenia_wylicz_czasy.php?nr=<?php echo $_REQUEST[zglnr]; ?>');
			</script>
			<?php
	} else {
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
	}


} else {

$przesuniety=0;
$r2 = mysql_query("SELECT zgl_szcz_przesuniety_termin_rozpoczecia FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_id='$_REQUEST[id]') LIMIT 1", $conn_hd) or die($k_b);
list($przesuniety)=mysql_fetch_array($r2);

if ($przesuniety==1) {
	?>
	<script>
	alert('Nie można edytować czasu wykonania kroku, dla awarii z przesuniętym terminem rozpoczęcia');
	self.close();
	</script>	
	<?php
}

starttable();
echo "<form name=add action=$PHP_SELF method=POST onSubmit=\"return pytanie_zatwierdz_edycje_kroku('Zapisać zmiany do bazy ?');\">";

$result = mysql_query("SELECT zgl_kategoria FROM $dbname_hd.hd_zgloszenie WHERE (zgl_nr=$_GET[zglid]) LIMIT 1", $conn_hd) or die($k_b);

list($_temp_kat_id)=mysql_fetch_array($result);
echo "<input type=hidden name=kat_id id=kat_id value='$_temp_kat_id'>";

switch ($_GET[element]) {
	case "trasa" : 	pageheader("Edycja trasy wyjazdowej w kroku nr <b>$_GET[nr]</b> w zgłoszeniu nr <b>$_GET[zglnr]</b>"); 
					$result = mysql_query("SELECT zgl_szcz_unikalny_numer FROM $dbname_hd.hd_zgloszenie_szcz WHERE ((zgl_szcz_id=$_GET[id]) and (zgl_szcz_nr_kroku=$_GET[nr])) LIMIT 1", $conn_hd) or die($k_b);
					list($value_to_change_unique)=mysql_fetch_array($result);					
					$result = mysql_query("SELECT wyjazd_data,wyjazd_trasa,wyjazd_km,wyjazd_czas_trwania FROM $dbname_hd.hd_zgloszenie_wyjazd WHERE (wyjazd_zgl_szcz_id='$value_to_change_unique') LIMIT 1", $conn_hd) or die($k_b);
					list($value_to_change,$value_to_change2,$value_to_change3,$value_to_change4)=mysql_fetch_array($result);
					
					echo "<input type=hidden name=old_wyjazd_data value='$value_to_change'>";
					echo "<input type=hidden name=old_wyjazd_trasa value='$value_to_change2'>";
					echo "<input type=hidden name=old_wyjazd_km value='$value_to_change3'>";
					echo "<input type=hidden name=old_wyjazd_czas_trwania value='$value_to_change4'>";
					
					break;
	case "czas" : 	pageheader("Edycja czasu wykonywania kroku nr <b>$_GET[nr]</b> w zgłoszeniu nr <b>$_GET[zglnr]</b>"); 
					$result = mysql_query("SELECT zgl_szcz_unikalny_numer FROM $dbname_hd.hd_zgloszenie_szcz WHERE ((zgl_szcz_id=$_GET[id]) and (zgl_szcz_nr_kroku=$_GET[nr])) LIMIT 1", $conn_hd) or die($k_b);
					list($value_to_change_unique)=mysql_fetch_array($result);	
					
					$result = mysql_query("SELECT zgl_szcz_czas_wykonywania,zgl_szcz_czas_rozpoczecia_kroku  FROM $dbname_hd.hd_zgloszenie_szcz WHERE ((zgl_szcz_id=$_GET[id]) and (zgl_szcz_nr_kroku=$_GET[nr])) LIMIT 1", $conn_hd) or die($k_b);
					list($value_to_change,$value_to_change2)=mysql_fetch_array($result);
					
					echo "<input type=hidden name=old_zgl_szcz_czas_wykonywania value='$value_to_change'>";
					echo "<input type=hidden name=old_zgl_szcz_czas_rozpoczecia_kroku value='$value_to_change2'>";
					
					break;
	case "czynnosc" : 	pageheader("Edycja wykonanych czynności w kroku nr <b>$_GET[nr]</b> w zgłoszeniu nr <b>$_GET[zglnr]</b>"); 
					$result = mysql_query("SELECT zgl_szcz_unikalny_numer FROM $dbname_hd.hd_zgloszenie_szcz WHERE ((zgl_szcz_id=$_GET[id]) and (zgl_szcz_nr_kroku=$_GET[nr])) LIMIT 1", $conn_hd) or die($k_b);
					list($value_to_change_unique)=mysql_fetch_array($result);	
				
					$result = mysql_query("SELECT zgl_szcz_wykonane_czynnosci FROM $dbname_hd.hd_zgloszenie_szcz WHERE ((zgl_szcz_id=$_GET[id]) and (zgl_szcz_nr_kroku=$_GET[nr])) LIMIT 1", $conn_hd) or die($k_b);
					list($value_to_change)=mysql_fetch_array($result);
					
					echo "<input type=hidden name=old_zgl_szcz_wykonane_czynnosci value='$value_to_change'>";
					
					break;					
}

tbl_empty_row();

if ($_GET[element]=='czas') {
//$r40 = mysql_query("SELECT filia_leader FROM $dbname.serwis_filie WHERE (filia_id='$es_filia') LIMIT 1", $conn) or die($k_b);
//list($KierownikId)=mysql_fetch_array($r40);
$KierownikId = $kierownik_nr;

if (($es_nr==$KierownikId) && ($is_dyrektor==0)) {
tr_();
	td("150;r;Data wykonania kroku");
	td_(";;");
			//echo ">".$value_to_change2;
	
	
			$result = mysql_query("SELECT zgl_szcz_czas_rozpoczecia_kroku,zgl_szcz_nr_kroku FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$_GET[zglnr]) and (zgl_szcz_widoczne=1) and (zgl_szcz_status<>2) ORDER BY zgl_szcz_id DESC LIMIT 1", $conn_hd) or die($k_b);

			list($max_date, $max_krok)=mysql_fetch_array($result);
			$max_date = substr($max_date,0,10);
			
			//echo ">>>".$max_date;
			
			$dddd = Date('Y-m-d');
			//echo SubstractWorkingDays(2,$dddd);
			$sama_data = substr($value_to_change2,0,10);
			
			echo "<select class=wymagane name=data_wykonywania_d id=data_wykonywania_d>";

			$dodaj_date_zgloszenia = 0;
			
			for ($cd=1; $cd<($idw_dla_zbh+1); $cd++) {
				$test = SubstractDays($cd,$dddd);
				if ($test==substr($value_to_change2,0,10)) { $dodaj_date_zgloszenia = 1; break; } else { $dodaj_date_zgloszenia = 0; }
			}
			
			//if ($dodaj_date_zgloszenia==1) {
			echo "<option value='".$sama_data."' SELECTED ";
			echo ">".$sama_data."&nbsp;";
			echo "</option>\n";
			//}
			
			if (($sama_data!=$dddd) && ($max_krok==$_GET[nr])) echo "<option value='$dddd'>$dddd</option>\n";

			if (date("w",strtotime($dddd))!=1) {
				for ($cd=1; $cd<($idw_dla_zbh+1); $cd++) {
				
					if ($max_krok==$_GET[nr]) {
					//if (SubstractDays($cd,$dddd)<=$max_date) {
						echo "<option value='".SubstractDays($cd,$dddd)."'";
						if ($sama_data==SubstractDays($cd,$dddd)) echo " SELECTED ";
						echo ">".SubstractDays($cd,$dddd)."&nbsp;";
						
						if ($idw_dla_zbh_testowa) echo "[dla testów]";
						
						echo "</option>\n";
					//}
					}
				}
			}
		//	echo "<option value='".SubstractWorkingDays(1,$dddd)."'>".SubstractWorkingDays(1,$dddd)."&nbsp;</option>\n";
		//	echo "<option value='".SubstractWorkingDays(2,$dddd)."'>".SubstractWorkingDays(2,$dddd)."&nbsp;</option>\n";
			
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
			
			/* echo "<input class=wymagane type=text name=hddz value='$dddd' maxlength=10 size=10 id=datepicker onBlur=\"CheckDate(this.value);\" />";			  
			echo "&nbsp;<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img src=img/cal.gif width=16 height=16 border=0></a>";*/
			$tttt = Date('H:i');
			$sama_godzina = substr($value_to_change2,11,5);
			echo "&nbsp;Godzina&nbsp;<input class=wymagane type=text name=data_wykonywania_g id=data_wykonywania_g value='$sama_godzina' maxlength=5 size=3 onFocus=\"this.select();\" onKeyPress=\"return filterInput(1, event, false, ''); \" onKeyUp=\"DopiszDwukropek('data_wykonywania_g');\"  onBlur=\"if (this.value=='') this.focus();\" onDblClick=\"this.value='".$tttt."'; \" title=\" Podwójne kliknięcie wpisuje godzinę otwarcia tego okna \" />";	

			
	
			echo "<input type=hidden name=zmiana_daty value=1>";
		//echo "<input type=text class=wymagane name=data_wykonywania_m value='$value_to_change2' maxlength=19 size=19 onKeyPress=\"return filterInput(1, event, false); \" onKeyUp=\"if ((event.keyCode==13) && (this.value!='')) document.getElementById('czas_wykonywania_h').focus(); \" />";
			
	_td();
_tr();
} 

if ($is_dyrektor==1) {
tr_();
	td("150;r;Data wykonania kroku");
	td_(";;");
	
			$dddd = Date('Y-m-d');
			//echo SubstractWorkingDays(2,$dddd);
			$sama_data = substr($value_to_change2,0,10);
			
			echo "<input type=text class=wymagane name=data_wykonywania_d id=data_wykonywania_d value='$sama_data' maxlength=10 size=10 onKeyPress=\"return filterInputEnter(1, event, false,'-'); \" onKeyUp=\"DopiszKreski('search_data');\">";
			echo "&nbsp;<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img src=img/cal.gif width=16 height=16 border=0></a>";
			if ($_today==1) echo "&nbsp;<a title=' Ustaw datę na dzień bieżący ' type=image class=imgoption onClick=\"document.getElementById('data_wykonywania_d').value='".Date('Y-m-d')."'; return false;\"><img src=img/hd_note_today.gif width=16 height=16 border=0></a>";
		
			$tttt = Date('H:i');
			$sama_godzina = substr($value_to_change2,11,5);
			echo "&nbsp;Godzina&nbsp;<input class=wymagane type=text name=data_wykonywania_g id=data_wykonywania_g value='$sama_godzina' maxlength=5 size=3 onFocus=\"this.select();\" onKeyPress=\"return filterInput(1, event, false, ''); \" onKeyUp=\"DopiszDwukropek('data_wykonywania_g');\" onBlur=\"if (this.value=='') this.focus();\" onDblClick=\"this.value='".$tttt."'; \" title=\" Podwójne kliknięcie wpisuje godzinę otwarcia tego okna \" />";	

			
	
			echo "<input type=hidden name=zmiana_daty value=1>";
		//echo "<input type=text class=wymagane name=data_wykonywania_m value='$value_to_change2' maxlength=19 size=19 onKeyPress=\"return filterInput(1, event, false); \" onKeyUp=\"if ((event.keyCode==13) && (this.value!='')) document.getElementById('czas_wykonywania_h').focus(); \" />";
			
	_td();
_tr();
} 

if (($is_dyrektor==0) && ($es_nr!=$KierownikId)) {
	echo "<input type=hidden name=zmiana_daty value=0>";
}

if ($_GET[nr]=='1') {
	tr_();
		echo "<td colspan=2 class=center>";
			//echo "<a href=# class=normalfont><font color=red>Zmiana daty wykonania pierwszego kroku spowoduje zmianę daty rejestracji zgłoszenia</font></a><br /><br />";
		_td();
	_tr();
}

tr_();
	td("150;r;Czas wykonywania");
	td_(";;");
		echo "<input style=text-align:right type=text class=wymagane id=czas_wykonywania_h name=czas_wykonywania_h value='0' maxlength=2 size=2 onKeyPress=\"return filterInput(1, event, false); \" onKeyUp=\"if ((event.keyCode==13) && (this.value!='')) document.getElementById('czas_wykonywania_m').focus(); \" onBlur=\"if (this.value=='') this.value=0; if (this.value>8) if (confirm('Wpisano więcej niż 8 godzin na wykonanie kroku. Potwierdzasz poprawność ?')) { return true; } else { this.value='0'; return false; }\"/> godzin";
		echo "&nbsp;";
		echo "<input style=text-align:right type=text class=wymagane id=czas_wykonywania_m name=czas_wykonywania_m value='$value_to_change' maxlength=2 size=2 onKeyPress=\"return filterInput(1, event, false); \" onBlur=\"if (this.value=='') this.value=0; \" onKeyUp=\"if ((event.keyCode==13) && (this.value!='')) document.getElementById('submit').focus(); \" /> minut";
		
	_td();
_tr();
}

if (($es_nr==$KierownikId) && ($is_dyrektor==0)) {
} else {
	if ($_GET[element]=='czas') {
		tr_();
			echo "<td colspan=2 class=center>";
				$sama_data = substr($value_to_change2,0,16);
				echo "<font color=red>Czas wykonywania będzie doliczony do daty rozpoczęcia kroku: <b>$sama_data</b></font>";
			_td();
		_tr();
	}
}

if ($_GET[element]=='czynnosc') {
	tr_();
		td("150;rt;<b>Wykonane czynności</b>");
		td_(";;;");
			echo "<textarea class=wymagane name=hd_tresc id=hd_tresc cols=65 rows=2 onBlur=\"ZamienTekst(this.id);\" onKeyUp=\"ex1(this);\">";
			echo ClearOutputText(br2nl2($value_to_change));
			echo "</textarea>";			
		_td();
	_tr();	
}

if ($_GET[element]=='trasa') {
	tr_();
		td("150;r;Data wyjazdu");
		td_(";;;");
			list($uniq1,$data_kroku)=mysql_fetch_array(mysql_query("SELECT zgl_szcz_unikalny_numer,zgl_szcz_czas_rozpoczecia_kroku FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_id=$_REQUEST[id]) and (zgl_szcz_widoczne=1) LIMIT 1", $conn_hd));

			if ($uniq1!='') 
				list($w_data)=mysql_fetch_array(mysql_query("SELECT wyjazd_data FROM $dbname_hd.hd_zgloszenie_wyjazd WHERE (wyjazd_zgl_szcz_id ='$uniq1') and (wyjazd_widoczny=1) LIMIT 1", $conn_hd));
				
			if ($w_data!=$data_kroku) $w_data = substr($data_kroku,0,10);
			
			echo "<input type=hidden name=hd_zam_data value='$w_data'>";
			echo "<b>$w_data</b>";
			
			if ($w_data!=$data_kroku) echo " | <font color=red>automatycznie zmieniono datę wyjazdu na datę wykonania kroku</font>";
			/*			

			$dddd = Date('Y-m-d');
			echo "<select class=wymagane name=hd_zam_data id=hd_zam_data>";
			echo "<option value='$dddd'"; if ($value_to_change==$dddd) echo " SELECTED "; echo ">$dddd</option>\n";
			
			for ($cd=1; $cd<($idw_dla_zbh+1); $cd++) {
				echo "<option value='".SubstractDays($cd,$dddd)."'"; 
				if ($value_to_change==SubstractDays($cd,$dddd)) echo " SELECTED"; 
				echo ">".SubstractDays($cd,$dddd)."&nbsp;</option>\n";
			}
			
	// sprawdź dostępy czasowe dla tego pracownika
			$sql_dc = "SELECT dc_dostep_dla_daty,dc_dostep_active_to FROM $dbname_hd.hd_dostep_czasowy WHERE ((dc_dostep_dla_osoby='$currentuser') and (dc_dostep_active=1) and (belongs_to=$es_filia)) ORDER BY dc_dostep_dla_daty DESC";
			$result_dc = mysql_query($sql_dc, $conn_hd) or die($k_b);
			$totalrows_dc = mysql_num_rows($result_dc);
			
			if ($totalrows_dc>0) {
				while ($newArray_dc = mysql_fetch_array($result_dc)) {
					$temp_dc_data	= $newArray_dc['dc_dostep_dla_daty'];
					$temp_dc_dostep_do	= $newArray_dc['dc_dostep_active_to'];
					echo "<option value='$temp_dc_data'"; if ($value_to_change==$temp_dc_data) echo " SELECTED "; echo ">$temp_dc_data (dostępna do $temp_dc_dostep_do)</option>\n";
				}
			}
	// koniec sprawdzania dostępów czasowych dla pracownika
	
//			echo "<option value='".SubstractWorkingDays(1,$dddd)."'"; if ($value_to_change==SubstractWorkingDays(1,$dddd)) echo " SELECTED "; echo ">".SubstractWorkingDays(1,$dddd)."&nbsp;</option>\n";
	//		echo "<option value='".SubstractWorkingDays(2,$dddd)."'"; if ($value_to_change==SubstractWorkingDays(2,$dddd)) echo " SELECTED "; echo ">".SubstractWorkingDays(2,$dddd)."&nbsp;</option>\n";
			echo "</select>\n";
		*/
		_td();	
	_tr();
	tr_();
		td("150;rt;Trasa wyjazdu");
		td_(";;;");
			echo "<textarea class=wymagane id=trasa name=trasa cols=70 rows=5 onKeyUp=\"this.value=this.value.toUpperCase();\" onBlur=\"ZamienTekst(this.id);\">$value_to_change2</textarea>";	
		_td();
	_tr();	
	tr_();
		td("150;rt;Przejechane km");
		td_(";;;");
			echo "<input class=wymagane id=km name=km style=text-align:right type=text size=3 maxlength=3 value='$value_to_change3' onKeyPress=\"return filterInput(1, event, false); \"> km";
		_td();
	_tr();
	tr_();
		td("150;rt;Czas przejazdu");
		td_(";;;");
			echo "<input class=wymagane id=wyjazd_czas_trwania name=wyjazd_czas_trwania style=text-align:right type=text size=3 maxlength=3 value='$value_to_change4' onKeyPress=\"return filterInput(1, event, false); \" onBlur=\"if (this.value=='') this.value=0; \" /> minut<br />";
		_td();
	_tr();	

}

echo "<input type=hidden name=id value=$_GET[zglid]>";
echo "<input type=hidden name=zglnr value=$_GET[zglnr]>";
echo "<input type=hidden name=element id=element value='$_GET[element]'>";
echo "<input type=hidden name=unique_nr value='$value_to_change_unique'>";
echo "<input type=hidden name=nr value=$_GET[nr]>";
echo "<input type=hidden name=zgl_krok_id value='$_REQUEST[zgl_krok_id]'>";

tbl_empty_row();	
endtable();
startbuttonsarea("right");
echo "<input id=submit type=submit class=buttons name=submit value='Zapisz' />";
addbuttons("anuluj");
endbuttonsarea();
_form();
?>
<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("add");
  frmvalidator.addValidation("osoba_zgl","req","Nie podano osoby zgłaszającej");
  frmvalidator.addValidation("osoba_tel","req","Nie podano numeru telefonu");
//  frmvalidator.addValidation("dfkier","dontselect=0","Nie wybrałeś kierownika");
</script>	

<?php if (($_GET[element]=='czas') && ($is_dyrektor==1)) { ?>
<script language="JavaScript">
var cal1 = new calendar1(document.forms['add'].elements['data_wykonywania_d']);
	cal1.year_scroll = true;
	cal1.time_comp = false;
</script>
<?php } ?>

<?php } ?>
</body>
</html>