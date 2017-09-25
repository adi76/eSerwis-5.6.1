<?php
// START 

// ustalenie kategorii zgłoszenia
	list($kat)=mysql_fetch_array(mysql_query("SELECT zgl_kategoria FROM $dbname_hd.hd_zgloszenie WHERE zgl_id=$___nr LIMIT 1", $conn_hd));
	
// ########################################################################################################################################################################
// ########################################################################################################################################################################	
// ########################  KONSULTACJE - POCZĄTEK ############################	
	if ($kat == 1) {

		$_e1c = 0;
		$_e1p = 0;
	
		list($_e2c)=mysql_fetch_array(mysql_query("SELECT zgl_szcz_czas_wykonywania FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$___nr) and (zgl_szcz_widoczne=1)", $conn_hd));
		$_e2p = 0;

		$_e3c = 0;
		$_e3p = 0;
		
		$sql_etapy_update = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_E1C=$_e1c, zgl_E1P=$_e1p, zgl_E2C=$_e2c, zgl_E2P=$_e2p, zgl_E3C=$_e3c, zgl_E3P=$_e3p WHERE (zgl_nr=$___nr) LIMIT 1";
		$result_etapy = mysql_query($sql_etapy_update, $conn_hd) or die($k_b);

	}
// ########################  KONSULTACJE - KONIEC ############################
// ########################################################################################################################################################################
// ########################################################################################################################################################################	


// ########################################################################################################################################################################
// ########################################################################################################################################################################	
// ########################  AWARIE - POCZĄTEK ############################	
	if (($kat == 2) || ($kat == 6)) {
	
		$_e1c = 0;
		$_e1p = 0;
		$_e2c = 0;
		$_e2p = 0;
		$_e3c = 0;			
		$_e3p = 0;
		
		list($ile_krokow)=mysql_fetch_array(mysql_query("SELECT count(zgl_szcz_id) FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$___nr) and (zgl_szcz_widoczne=1)", $conn_hd));
		
		// prace wg umowy zakończone w jednym kroku
		if ($ile_krokow==1) {

			list($_temp_czasRozp, $_temp_czasWyk)=mysql_fetch_array(mysql_query("SELECT zgl_szcz_czas_rozpoczecia_kroku, zgl_szcz_czas_wykonywania FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$___nr) and (zgl_szcz_widoczne=1)", $conn_hd));			
			$newTime = AddMinutesToDate($_temp_czasWyk,$_temp_czasRozp);
			
			$roznica = MinutesBetween($_temp_czasRozp, $newTime, $kwt, $serwis_working_time);
			if ($roznica==0) {
				$roznica = MinutesBetween($_temp_czasRozp, $newTime, $serwis_working_time, $serwis_working_time);
			}
			if ($debug) echo ">>".$roznica."<";
			$_e1c += $roznica;

		} else {
		// prace wg umowy realizowane w wielu krokach
		
			// pobranie godzin pracy komórki
			list($kwt)=mysql_fetch_array(mysql_query("SELECT zgl_komorka_working_hours FROM $dbname_hd.hd_zgloszenie WHERE zgl_id=$___nr LIMIT 1", $conn_hd));
			if ($kwt=='') $kwt = $default_working_time;

			$sql_petla="SELECT * FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$___nr) and (zgl_szcz_widoczne=1)";
			$result_petla = mysql_query($sql_petla, $conn_hd) or die($k_b);
			
			$cnt_krok = 1;
			while ($newArray4 = mysql_fetch_array($result_petla)) {
				$_temp_id  			= $newArray4['zgl_szcz_id'];
				$_temp_nr			= $newArray4['zgl_szcz_nr_kroku'];
				$_temp_czasSS		= $newArray4['zgl_szcz_czas_start_stop'];
				$_temp_czasWyk		= $newArray4['zgl_szcz_czas_wykonywania'];
				$_temp_czasRozp		= $newArray4['zgl_szcz_czas_rozpoczecia_kroku'];
				$_temp_status		= $newArray4['zgl_szcz_status'];		
				$_temp_pt			= $newArray4['zgl_szcz_przesuniety_termin_rozpoczecia'];
				$_temp_widoczne		= $newArray['zgl_szcz_widoczne'];
				$_temp_czy_rozwiazany_szcz	= $newArray4['zgl_szcz_czy_rozwiazany_problem'];

				$newTime = AddMinutesToDate($_temp_czasWyk,$_temp_czasRozp);				
				
				// bug - dla statusu "zamknięte" START/STOP zawsze jest = START
				if ($_temp_status==9) $_temp_czasSS = 'START';
				
				$currSS = $_temp_czasSS;
				
				// KROK = 1
				if ($cnt_krok == 1) {
					$last_SS				= $_temp_czasSS;
					$last_czas_rozp 		= $_temp_czasRozp;
					$last_czas_zak 			= $newTime;
					$last_czas_wyk 			= $_temp_czasWyk;
					$last_status 			= $_temp_status;
					$last_czy_rozw 			= $_temp_czy_rozwiazany_szcz;
					$last_czy_przesuniety 	= $_temp_pt;
					
					$roznica = 0;
					
					$roznica = MinutesBetween($_temp_czasRozp, $newTime, $kwt, $serwis_working_time);
					if ($roznica==0) {
						$roznica = MinutesBetween($_temp_czasRozp, $newTime, $serwis_working_time, $serwis_working_time);
					}
					$_e1c += $roznica;
					
				} // KROK > 1	i	KROK < n
					elseif ($cnt_krok<=$ile_krokow) { 

						if (($last_SS == 'START') && ($currSS == 'START')) {						
							
							// jeżeli status = nowy
							if ($last_status==1) { 
							
								$roznica = MinutesBetween($last_czas_zak, $_temp_czasRozp, $kwt, $serwis_working_time);
								if ($debug) echo "<br />($roznica)";
								
								if ($debug) echo "[".godzina_start1($_temp_czasRozp,$kwt)."][".godzina_start1($_temp_czasRozp,$serwis_working_time)."][->$last_czas_zak<-, $_temp_czasRozp] [".$roznica."]";
				
				//  ===================================================				
								// 21.06.2012 - zgł. 74585
								if ($debug) echo "<br /><br />".godzina_start1($_temp_czasRozp,$kwt).">=".godzina_start1($_temp_czasRozp,$serwis_working_time)."<br />";
								
								if ($debug) echo "<hr />".substr($last_czas_zak,0,10)."<hr />";
								if ($debug) echo "<hr />".substr($_temp_czasRozp,0,10)."<hr />";
								
						// zmiana 08.08.2012 (zgł. 83160) -> do analizy czy jest ok
								if ((substr($last_czas_zak,0,10))==(substr($_temp_czasRozp,0,10))) {
									if (godzina_start1($_temp_czasRozp,$kwt)>=godzina_start1($_temp_czasRozp,$serwis_working_time)) {
										$roznica_x = MinutesBetween($last_czas_zak, $_temp_czasRozp, $serwis_working_time, $serwis_working_time);
										$roznica = $roznica_x;
									}
									if ($debug) echo "<br />($roznica)";
								}
				//	===================================================
				
								if ($roznica==0) $roznica = MinutesBetween($last_czas_zak, $_temp_czasRozp, $serwis_working_time, $serwis_working_time);
							
								$_e1c += $roznica;
								
								$roznica = MinutesBetween($_temp_czasRozp, $newTime, $kwt, $serwis_working_time);
								if ($roznica==0) $roznica = MinutesBetween($_temp_czasRozp, $newTime, $serwis_working_time, $serwis_working_time);
								if ($debug) echo "************ ($roznica)  - $_temp_czasWyk <br />";
				
				// zmiana 08.08.2012 -> do analizy
					//	if ($_temp_czasWyk>$roznica) $roznica = $_temp_czasWyk;
								
								$_e2c += $roznica;
								
							} else {
							
								$roznica = MinutesBetween($last_czas_zak, $newTime, $kwt, $serwis_working_time);
								if ($roznica==0) $roznica = MinutesBetween($last_czas_zak, $newTime	, $serwis_working_time, $serwis_working_time);
							
								if ($last_czy_rozw==1) {
									//if ($last_SS == 'START') 
									$_e3c += $roznica;
									//if ($last_SS == 'STOP') $_e3p += $roznica;
								} else {
									//if ($last_SS == 'START') 
									$_e2c += $roznica;
									//if ($last_SS == 'STOP') $_e2p += $roznica;
								}
							}

							
						}
						
						if (($last_SS == 'START') && ($currSS == 'STOP')) {

							$roznica = MinutesBetween($last_czas_zak, $newTime, $kwt, $serwis_working_time);
							if ($roznica==0) $roznica = MinutesBetween($last_czas_zak, $newTime, $serwis_working_time, $serwis_working_time);						

							if ($last_czy_rozw==1) {
								//if ($last_SS == 'START') 
								$_e3c += $roznica;
					//			if ($last_SS == 'STOP') $_e3p += $roznica;
							} else {
								//if ($last_SS == 'START') 
								$_e2c += $roznica;
								//if ($last_SS == 'STOP') $_e2p += $roznica;
							}
								
							//$_e3c += $roznica;
						}
						
						if (($last_SS == 'STOP') && ($currSS == 'START')) {

							$roznica = MinutesBetween($last_czas_zak, $_temp_czasRozp, $kwt, $serwis_working_time);
							//if ($roznica==0) $roznica = MinutesBetween($last_czas_zak, $_temp_czasRozp, $serwis_working_time, $serwis_working_time);	
							if ($debug) echo ">>>>>>>".$roznica."<<<<<";
							if ($last_status==1) {
								$data_rozp_pracy_up = substr($_temp_czasRozp,0,10)." ".godzina_start1($_temp_czasRozp,$kwt).":00";
								
								$last_data_zak_pracy_up = substr($last_czas_zak,0,10)." ".godzina_stop1($last_czas_zak,$kwt).":00";
								
								$roznica2 = MinutesBetween($last_data_zak_pracy_up, $data_rozp_pracy_up, $kwt, $serwis_working_time);
								
								$_e1p += $roznica2;
								$_e1c += ($roznica-$roznica2);
								
							} else {
								if ($last_czy_rozw==1) {
									//if ($last_SS == 'START') 
									$_e3p += $roznica;
						//			if ($last_SS == 'STOP') $_e3p += $roznica;
								} else {
									//if ($last_SS == 'START') 
									$_e2p += $roznica;
									//if ($last_SS == 'STOP') $_e2p += $roznica;
								}
							}
							if ($debug) echo ">>>>".$roznica."<<<<";
							$roznica = MinutesBetween($_temp_czasRozp, $newTime, $kwt, $serwis_working_time);
							if ($roznica==0) {
								$roznica = MinutesBetween($_temp_czasRozp, $newTime, $serwis_working_time, $serwis_working_time);								
								if ($debug) echo "<b>[ ! ]</b> ";								
							}
							
							if ($last_czy_rozw==1) {
								//if ($last_SS == 'START') 
								$_e3c += $roznica;
					//			if ($last_SS == 'STOP') $_e3p += $roznica;
							} else {
								//if ($last_SS == 'START') 
								$_e2c += $roznica;
								//if ($last_SS == 'STOP') $_e2p += $roznica;
							}
						}
						
						if (($last_SS == 'STOP') && ($currSS == 'STOP')) {

							$roznica = MinutesBetween($last_czas_zak, $newTime, $kwt, $serwis_working_time);
							//if ($roznica==0) $roznica = MinutesBetween($last_czas_zak, $newTime, $serwis_working_time, $serwis_working_time);	
							if ($last_czy_rozw==1) {
								//if ($last_SS == 'START') 
								$_e3p += $roznica;
					//			if ($last_SS == 'STOP') $_e3p += $roznica;
							} else {
								//if ($last_SS == 'START') 
								$_e2p += $roznica;
								//if ($last_SS == 'STOP') $_e2p += $roznica;
							}
							
							//$_e3p += $roznica;
							
						}
					
				} 
				
				//if ($debug) echo "Krok: <b>$cnt_krok</b>,	CurrSS = <b>$_temp_czasSS</b>,	LastSS = <b>$last_SS</b>,	różnica = <b>$roznica</b>, E3C = <b>$_e3c</b>, E3P = <b>$_e3p</b> <br />";
				if ($debug) echo "Krok: <b>$cnt_krok</b>,	CurrSS = <b>$_temp_czasSS</b>,	LastSS = <b>$last_SS</b>,	różnica = <b>$roznica</b>, E1C = <b>$_e1c</b>, E1P = <b>$_e1p</b>, E2C = <b>$_e2c</b>, E2P = <b>$_e2p</b>, E3C = <b>$_e3c</b>, E3P = <b>$_e3p</b> <br />";
				$cnt_krok++;

				$last_SS				= $_temp_czasSS;
				$last_czas_rozp 		= $_temp_czasRozp;
				$last_czas_zak 			= $newTime;
				$last_czas_wyk 			= $_temp_czasWyk;
				$last_status 			= $_temp_status;
				$last_czy_rozw 			= $_temp_czy_rozwiazany_szcz;
				$last_czy_przesuniety 	= $_temp_pt;
				
			}
			
		}
		
		$sql_etapy_update = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_E1C=$_e1c, zgl_E1P=$_e1p, zgl_E2C=$_e2c, zgl_E2P=$_e2p, zgl_E3C=$_e3c, zgl_E3P=$_e3p WHERE (zgl_nr=$___nr) LIMIT 1";
		$result_etapy = mysql_query($sql_etapy_update, $conn_hd) or die($k_b);
		
	}
// ########################  AWARIE - KONIEC ############################
// ########################################################################################################################################################################
// ########################################################################################################################################################################	


// ########################################################################################################################################################################
// ########################################################################################################################################################################	
// ########################  PRACE WG UMOWY - POCZĄTEK ############################	
	if (($kat == 3) || ($kat == 4) || ($kat == 5) || ($kat == 7)) {
	
		$_e1c = 0;
		$_e1p = 0;
		$_e2c = 0;
		$_e2p = 0;
		$_e3c = 0;			
		$_e3p = 0;
		
		list($ile_krokow)=mysql_fetch_array(mysql_query("SELECT count(zgl_szcz_id) FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$___nr) and (zgl_szcz_widoczne=1)", $conn_hd));
		
		// prace wg umowy zakończone w jednym kroku
		if ($ile_krokow==1) {

			list($_temp_czasRozp, $_temp_czasWyk)=mysql_fetch_array(mysql_query("SELECT zgl_szcz_czas_rozpoczecia_kroku, zgl_szcz_czas_wykonywania FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$___nr) and (zgl_szcz_widoczne=1)", $conn_hd));			
			$newTime = AddMinutesToDate($_temp_czasWyk,$_temp_czasRozp);
			
			$roznica = MinutesBetween($_temp_czasRozp, $newTime, $kwt, $serwis_working_time);
			if ($roznica==0) {
				$roznica = MinutesBetween($_temp_czasRozp, $newTime, $serwis_working_time, $serwis_working_time);
			}
			$_e3c += $roznica;

		} else {
		// prace wg umowy realizowane w wielu krokach
		
			// pobranie godzin pracy komórki
			list($kwt)=mysql_fetch_array(mysql_query("SELECT zgl_komorka_working_hours FROM $dbname_hd.hd_zgloszenie WHERE zgl_id=$___nr LIMIT 1", $conn_hd));
			if ($kwt=='') $kwt = $default_working_time;

			$sql_petla="SELECT * FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$___nr) and (zgl_szcz_widoczne=1)";
			$result_petla = mysql_query($sql_petla, $conn_hd) or die($k_b);
			
			$cnt_krok = 1;
			while ($newArray4 = mysql_fetch_array($result_petla)) {
				$_temp_id  			= $newArray4['zgl_szcz_id'];
				$_temp_nr			= $newArray4['zgl_szcz_nr_kroku'];
				$_temp_czasSS		= $newArray4['zgl_szcz_czas_start_stop'];
				$_temp_czasWyk		= $newArray4['zgl_szcz_czas_wykonywania'];
				$_temp_czasRozp		= $newArray4['zgl_szcz_czas_rozpoczecia_kroku'];
				$_temp_status		= $newArray4['zgl_szcz_status'];		
				$_temp_pt			= $newArray4['zgl_szcz_przesuniety_termin_rozpoczecia'];
				$_temp_widoczne		= $newArray['zgl_szcz_widoczne'];
				$_temp_czy_rozwiazany_szcz	= $newArray4['zgl_szcz_czy_rozwiazany_problem'];

				$newTime = AddMinutesToDate($_temp_czasWyk,$_temp_czasRozp);				
				
				// bug - dla statusu "zamknięte" START/STOP zawsze jest = START
				if ($_temp_status==9) $_temp_czasSS = 'START';
				
				$currSS = $_temp_czasSS;
				
				// KROK = 1
				if ($cnt_krok == 1) {
					$last_SS				= $_temp_czasSS;
					$last_czas_rozp 		= $_temp_czasRozp;
					$last_czas_zak 			= $newTime;
					$last_czas_wyk 			= $_temp_czasWyk;
					$last_status 			= $_temp_status;
					$last_czy_rozw 			= $_temp_czy_rozwiazany_szcz;
					$last_czy_przesuniety 	= $_temp_pt;
					
					$roznica = 0;
					
					$roznica = MinutesBetween($_temp_czasRozp, $newTime, $kwt, $serwis_working_time);
					if ($roznica==0) {
						$roznica = MinutesBetween($_temp_czasRozp, $newTime, $serwis_working_time, $serwis_working_time);
					}
					$_e3c += $roznica;
					
				} // KROK > 1    i     KROK < n
					elseif ($cnt_krok<=$ile_krokow) { 

						if (($last_SS == 'START') && ($currSS == 'START')) {						
							
							$roznica = MinutesBetween($last_czas_zak, $newTime, $kwt, $serwis_working_time);
							if ($roznica==0) $roznica = MinutesBetween($last_czas_zak, $newTime, $serwis_working_time, $serwis_working_time);						

							$_e3c += $roznica;
						}
						
						if (($last_SS == 'START') && ($currSS == 'STOP')) {

							$roznica = MinutesBetween($last_czas_zak, $newTime, $kwt, $serwis_working_time);
							if ($roznica==0) $roznica = MinutesBetween($last_czas_zak, $newTime, $serwis_working_time, $serwis_working_time);						

							$_e3c += $roznica;
						}
						
						if (($last_SS == 'STOP') && ($currSS == 'START')) {

							$roznica = MinutesBetween($last_czas_zak, $_temp_czasRozp, $kwt, $serwis_working_time);
							//	if ($roznica==0) $roznica = MinutesBetween($last_czas_zak, $_temp_czasRozp, $serwis_working_time, $serwis_working_time);	
						
							$_e3p += $roznica;
//echo ">>>>".$roznica."<<<<";
							$roznica = MinutesBetween($_temp_czasRozp, $newTime, $kwt, $serwis_working_time);
							if ($roznica==0) {
								$roznica = MinutesBetween($_temp_czasRozp, $newTime, $serwis_working_time, $serwis_working_time);								
								//echo "<b>[ ! ]</b> ";								
							}
							$_e3c += $roznica;
						}
						
						if (($last_SS == 'STOP') && ($currSS == 'STOP')) {

							$roznica = MinutesBetween($last_czas_zak, $newTime, $kwt, $serwis_working_time);
							//if ($roznica==0) $roznica = MinutesBetween($last_czas_zak, $newTime, $serwis_working_time, $serwis_working_time);	
							
							$_e3p += $roznica;
							
						}
					
				} 
				
				//if ($debug) echo "Krok: <b>$cnt_krok</b>,	CurrSS = <b>$_temp_czasSS</b>,	LastSS = <b>$last_SS</b>,	różnica = <b>$roznica</b>, E3C = <b>$_e3c</b>, E3P = <b>$_e3p</b> <br />";
				if ($debug) echo "Krok: <b>$cnt_krok</b>,	CurrSS = <b>$_temp_czasSS</b>,	LastSS = <b>$last_SS</b>,	różnica = <b>$roznica</b>, E1C = <b>$_e1c</b>, E1P = <b>$_e1p</b>, E2C = <b>$_e2c</b>, E2P = <b>$_e2p</b>, E3C = <b>$_e3c</b>, E3P = <b>$_e3p</b> <br />";
				$cnt_krok++;

				$last_SS				= $_temp_czasSS;
				$last_czas_rozp 		= $_temp_czasRozp;
				$last_czas_zak 			= $newTime;
				$last_czas_wyk 			= $_temp_czasWyk;
				$last_status 			= $_temp_status;
				$last_czy_rozw 			= $_temp_czy_rozwiazany_szcz;
				$last_czy_przesuniety 	= $_temp_pt;
				
			}
			
		}
		
		$sql_etapy_update = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_E1C=$_e1c, zgl_E1P=$_e1p, zgl_E2C=$_e2c, zgl_E2P=$_e2p, zgl_E3C=$_e3c, zgl_E3P=$_e3p WHERE (zgl_nr=$___nr) LIMIT 1";
		$result_etapy = mysql_query($sql_etapy_update, $conn_hd) or die($k_b);
		
	}
// ########################  PRACE WG UMOWY - KONIEC ############################
// ########################################################################################################################################################################
// ########################################################################################################################################################################	

// KONIEC
?>