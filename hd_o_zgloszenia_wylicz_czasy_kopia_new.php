<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body>
<?php 	
$debug = true;

if ($_REQUEST[nr]!=0) {	
	
	errorheader("Nie zamykaj tego okna");

	echo "<div id=TrwaLadowanie style=\"color:white; font-weight:normal; text-align:center; font-size:13px; border: 1px solid silver; background-color:black;padding:10px;\">";
	echo "Trwa aktualizowanie danych o zgłoszeniu...<input type=image class=border0 src=img/loader7.gif>";
	echo "</div>";
	ob_flush();
	flush();
	
	// wyczyść czasy wszystkich etapów
	//$sql_etapy_clear = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_E1C=0, zgl_E1P=0, zgl_E2C=0, zgl_E2P=0, zgl_E3C=0, zgl_E3P=0 WHERE ((zgl_nr='$_REQUEST[nr]') and (belongs_to='$es_filia')) LIMIT 1";
	//$result_etapy = mysql_query($sql_etapy_clear, $conn_hd) or die($k_b);

	
	// zmienne wejściowe
	
	
	
	
	
// START 
	
// ustalenie kategorii zgłoszenia
	list($kat)=mysql_fetch_array(mysql_query("SELECT zgl_kategoria FROM $dbname_hd.hd_zgloszenie WHERE zgl_id=$_REQUEST[nr] LIMIT 1", $conn_hd));
	
// ########################  KONSULTACJE - POCZĄTEK ############################	
	if ($kat == 1) {

		$_e1c = 0;
		$_e1p = 0;
		$_e2c = 0;
		$_e2p = 0;
	
			list($_e3c)=mysql_fetch_array(mysql_query("SELECT zgl_szcz_czas_wykonywania FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$_REQUEST[nr]) and (zgl_szcz_widoczne=1)", $conn_hd));
			
		$_e3p = 0;
		
		$sql_etapy_update = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_E1C=$_e1c, zgl_E1P=$_e1p, zgl_E2C=$_e2c, zgl_E2P=$_e2p, zgl_E3C=$_e3c, zgl_E3P=$_e3p WHERE (zgl_nr=$_REQUEST[nr]) LIMIT 1";
		$result_etapy = mysql_query($sql_etapy_update, $conn_hd) or die($k_b);
		
		?>
		<script>
			document.getElementById('TrwaLadowanie').style.display='none';
			self.close();
		</script>
		<?php 
	}
// ########################  KONSULTACJE - KONIEC ############################
	
// ########################  AWARIE - POCZĄTEK ############################	
	if (($kat == 2) || ($kat == 6)) {
		$_e1c = 0;
		$_e1p = 0;
		$_e2c = 0;
		$_e2p = 0;
		$_e3c = 0;			
		$_e3p = 0;
		
		list($ile_krokow)=mysql_fetch_array(mysql_query("SELECT count(zgl_szcz_id) FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$_REQUEST[nr]) and (zgl_szcz_widoczne=1)", $conn_hd));
		
		// prace wg umowy zakończone w jednym kroku
		if ($ile_krokow==1) {
			
			list($_temp_czasRozp, $_temp_czasWyk)=mysql_fetch_array(mysql_query("SELECT zgl_szcz_czas_rozpoczecia_kroku, zgl_szcz_czas_wykonywania FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$_REQUEST[nr]) and (zgl_szcz_widoczne=1)", $conn_hd));			
			$newTime = AddMinutesToDate($_temp_czasWyk,$_temp_czasRozp);
			
			$roznica = MinutesBetween($_temp_czasRozp, $newTime, $kwt, $serwis_working_time);
			if ($roznica==0) {
				$roznica = MinutesBetween($_temp_czasRozp, $newTime, $serwis_working_time, $serwis_working_time);
			}
			$_e1c += $roznica;
			
		} else {
		// prace wg umowy realizowane w wielu krokach
		
			// pobranie godzin pracy komórki
			list($kwt)=mysql_fetch_array(mysql_query("SELECT zgl_komorka_working_hours FROM $dbname_hd.hd_zgloszenie WHERE zgl_id=$_REQUEST[nr] LIMIT 1", $conn_hd));
			if ($kwt=='') $kwt = $default_working_time;

			$sql_petla="SELECT * FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$_REQUEST[nr]) and (zgl_szcz_widoczne=1)";
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
					
					if ($last_status==3) $last_status==1;
					
					$roznica = 0;
					
					$roznica = MinutesBetween($_temp_czasRozp, $newTime, $kwt, $serwis_working_time);
					if ($roznica==0) {
						$roznica = MinutesBetween($_temp_czasRozp, $newTime, $serwis_working_time, $serwis_working_time);
					}
			//		$_e1c += $roznica;
					
				} elseif ($cnt_krok<=$ile_krokow) { 
					//echo "<br />LAST: $last_SS  CURR: $currSS<br />";
					// KROK > 1    i     KROK < n
						if (($last_SS == 'START') && ($currSS == 'START')) {						

							$roznica = MinutesBetween($last_czas_zak, $newTime, $kwt, $serwis_working_time);
							if ($roznica==0) $roznica = MinutesBetween($last_czas_zak, $newTime, $serwis_working_time, $serwis_working_time);						
							
							// jeżeli status nowy lub przypisany
							if (($last_status==1) || ($last_status==2)) {
							
								$roznica2 = MinutesBetween($last_czas_rozp, $_temp_czasRozp, $kwt, $serwis_working_time);
								//echo "<br />|$last_czas_rozp  $_temp_czasRozp                 |<b>[[$roznica2]]</b><br />";
								if ($roznica2==0) $roznica = MinutesBetween($last_czas_rozp, $_temp_czasRozp, $serwis_working_time, $serwis_working_time);						

								$_e1c += $roznica2;
								
								$roznica3 = MinutesBetween($_temp_czasRozp, $newTime, $kwt, $serwis_working_time);
								if ($roznica3==0) $roznica = MinutesBetween($_temp_czasRozp, $newTime, $serwis_working_time, $serwis_working_time);			
								//echo "<br /><b>[[$roznica3]]</b><br />";
								$_e2c += $roznica3;
								
								
								
							} else {
								// pozostałe statusy ( > 2 )
								if ($last_czy_rozw==1) {
									if ($last_SS == 'START') $_e3c += $roznica;
									if ($last_SS == 'STOP') $_e3p += $roznica;
								} else {
									if ($last_SS == 'START') $_e2c += $roznica;
									if ($last_SS == 'STOP') $_e2p += $roznica;
								}
							}
						}
						
						if (($last_SS == 'START') && ($currSS == 'STOP')) {

							$roznica = MinutesBetween($last_czas_zak, $newTime, $kwt, $serwis_working_time);
							if ($roznica==0) $roznica = MinutesBetween($last_czas_zak, $newTime, $serwis_working_time, $serwis_working_time);						

							// jeżeli status nowy lub przypisany
							if (($last_status==1) || ($last_status==2)) {
								$roznica2 = MinutesBetween($last_czas_rozp, $_temp_czasRozp, $kwt, $serwis_working_time);
								if ($roznica2==0) $roznica = MinutesBetween($last_czas_rozp, $_temp_czasRozp, $serwis_working_time, $serwis_working_time);						

								$_e1c += $roznica2;
								
								$roznica3 = MinutesBetween($_temp_czasRozp, $newTime, $kwt, $serwis_working_time);
								if ($roznica3==0) $roznica = MinutesBetween($_temp_czasRozp, $newTime, $serwis_working_time, $serwis_working_time);			
								
								$_e2c += $roznica3;
							} else {
								// pozostałe statusy ( > 2 )
								if ($last_czy_rozw==1) {
									if ($last_SS == 'START') $_e3c += $roznica;
									if ($last_SS == 'STOP') $_e3p += $roznica;
								} else {
									if ($last_SS == 'START') $_e2c += $roznica;
									if ($last_SS == 'STOP') $_e2p += $roznica;
								}
							}
							
						}
						
						if (($last_SS == 'STOP') && ($currSS == 'START')) {

							$roznica = MinutesBetween($last_czas_zak, $_temp_czasRozp, $kwt, $serwis_working_time);
						//	if ($roznica==0) $roznica = MinutesBetween($last_czas_zak, $_temp_czasRozp, $serwis_working_time, $serwis_working_time);	
						
							// jeżeli status nowy lub przypisany
							if (($last_status==1) || ($last_status==2)) {
								$roznica2 = MinutesBetween($last_czas_rozp, $_temp_czasRozp, $kwt, $serwis_working_time);
								if ($roznica2==0) $roznica = MinutesBetween($last_czas_rozp, $_temp_czasRozp, $serwis_working_time, $serwis_working_time);						

								$_e1c += $roznica2;
								
								$roznica3 = MinutesBetween($_temp_czasRozp, $newTime, $kwt, $serwis_working_time);
								if ($roznica3==0) $roznica = MinutesBetween($_temp_czasRozp, $newTime, $serwis_working_time, $serwis_working_time);			
								
								$_e2c += $roznica3;
							} else {
								// pozostałe statusy ( > 2 )
								if ($last_czy_rozw==1) {
									if ($last_SS == 'START') $_e3c += $roznica;
									if ($last_SS == 'STOP') $_e3p += $roznica;
								} else {
									if ($last_SS == 'START') $_e2c += $roznica;
									if ($last_SS == 'STOP') $_e2p += $roznica;
								}
							}
							
							//$_e3p += $roznica;

							$roznica = MinutesBetween($_temp_czasRozp, $newTime, $kwt, $serwis_working_time);
							if ($roznica==0) {
								$roznica = MinutesBetween($_temp_czasRozp, $newTime, $serwis_working_time, $serwis_working_time);								
								//echo "<b>[ ! ]</b> ";								
							}
							
							// jeżeli status nowy lub przypisany
							if (($last_status==1) || ($last_status==2)) {
								$roznica2 = MinutesBetween($last_czas_rozp, $_temp_czasRozp, $kwt, $serwis_working_time);
								if ($roznica2==0) $roznica = MinutesBetween($last_czas_rozp, $_temp_czasRozp, $serwis_working_time, $serwis_working_time);						

								$_e1c += $roznica2;
								
								$roznica3 = MinutesBetween($_temp_czasRozp, $newTime, $kwt, $serwis_working_time);
								if ($roznica3==0) $roznica = MinutesBetween($_temp_czasRozp, $newTime, $serwis_working_time, $serwis_working_time);			
								
								$_e2c += $roznica3;
							} else {
								// pozostałe statusy ( > 2 )
								if ($last_czy_rozw==1) {
									if ($last_SS == 'START') $_e3c += $roznica;
									if ($last_SS == 'STOP') $_e3p += $roznica;
								} else {
									if ($last_SS == 'START') $_e2c += $roznica;
									if ($last_SS == 'STOP') $_e2p += $roznica;
								}
							}
						}
						
						if (($last_SS == 'STOP') && ($currSS == 'STOP')) {

							$roznica = MinutesBetween($last_czas_zak, $newTime, $kwt, $serwis_working_time);
							if ($roznica==0) $roznica = MinutesBetween($last_czas_zak, $newTime, $serwis_working_time, $serwis_working_time);	
							//echo "#".$roznica."#";
							// jeżeli status nowy lub przypisany
							if (($last_status==1) || ($last_status==2)) {
								$roznica2 = MinutesBetween($last_czas_rozp, $_temp_czasRozp, $kwt, $serwis_working_time);
								if ($roznica2==0) $roznica = MinutesBetween($last_czas_rozp, $_temp_czasRozp, $serwis_working_time, $serwis_working_time);						

								$_e1c += $roznica2;
								
								$roznica3 = MinutesBetween($_temp_czasRozp, $newTime, $kwt, $serwis_working_time);
								if ($roznica3==0) $roznica = MinutesBetween($_temp_czasRozp, $newTime, $serwis_working_time, $serwis_working_time);			
								
								$_e2c += $roznica3;
							} else {
								// pozostałe statusy ( > 2 )
								if ($last_czy_rozw==1) {
									if ($last_SS == 'START') $_e3c += $roznica;
									if ($last_SS == 'STOP') $_e3p += $roznica;
								} else {
									if ($last_SS == 'START') $_e2c += $roznica;
									if ($last_SS == 'STOP') $_e2p += $roznica;
								}
							}							
							
						}
					
				} 
				
				echo "Krok: <b>$cnt_krok</b>,	CurrSS = <b>$_temp_czasSS</b>,	LastSS = <b>$last_SS</b>,	różnica = <b>$roznica</b>, E1C = <b>$_e1c</b>, E1P = <b>$_e1p</b>, E2C = <b>$_e2c</b>, E2P = <b>$_e2p</b>, E3C = <b>$_e3c</b>, E3P = <b>$_e3p</b> <br />";
				
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
		
		$sql_etapy_update = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_E1C=$_e1c, zgl_E1P=$_e1p, zgl_E2C=$_e2c, zgl_E2P=$_e2p, zgl_E3C=$_e3c, zgl_E3P=$_e3p WHERE (zgl_nr=$_REQUEST[nr]) LIMIT 1";
		$result_etapy = mysql_query($sql_etapy_update, $conn_hd) or die($k_b);
		
		?>
		<script>
			document.getElementById('TrwaLadowanie').style.display='none';
			self.close();
		</script>
		<?php 
		
	}
// ########################  AWARIE - KONIEC ############################

// ########################  PRACE WG UMOWY - POCZĄTEK ############################	
	if (($kat == 3) || ($kat == 4) || ($kat == 5)) {
	
		$_e1c = 0;
		$_e1p = 0;
		$_e2c = 0;
		$_e2p = 0;
		$_e3c = 0;			
		$_e3p = 0;
		
		list($ile_krokow)=mysql_fetch_array(mysql_query("SELECT count(zgl_szcz_id) FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$_REQUEST[nr]) and (zgl_szcz_widoczne=1)", $conn_hd));
		
		// prace wg umowy zakończone w jednym kroku
		if ($ile_krokow==1) {

			list($_temp_czasRozp, $_temp_czasWyk)=mysql_fetch_array(mysql_query("SELECT zgl_szcz_czas_rozpoczecia_kroku, zgl_szcz_czas_wykonywania FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$_REQUEST[nr]) and (zgl_szcz_widoczne=1)", $conn_hd));			
			$newTime = AddMinutesToDate($_temp_czasWyk,$_temp_czasRozp);
			
			$roznica = MinutesBetween($_temp_czasRozp, $newTime, $kwt, $serwis_working_time);
			if ($roznica==0) {
				$roznica = MinutesBetween($_temp_czasRozp, $newTime, $serwis_working_time, $serwis_working_time);
			}
			$_e3c += $roznica;

		} else {
		// prace wg umowy realizowane w wielu krokach
		
			// pobranie godzin pracy komórki
			list($kwt)=mysql_fetch_array(mysql_query("SELECT zgl_komorka_working_hours FROM $dbname_hd.hd_zgloszenie WHERE zgl_id=$_REQUEST[nr] LIMIT 1", $conn_hd));
			if ($kwt=='') $kwt = $default_working_time;

			$sql_petla="SELECT * FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$_REQUEST[nr]) and (zgl_szcz_widoczne=1)";
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
						//echo "<b>[ ! ]</b> ";
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
				
				//echo "Krok: <b>$cnt_krok</b>,	CurrSS = <b>$_temp_czasSS</b>,	LastSS = <b>$last_SS</b>,	różnica = <b>$roznica</b>, E3C = <b>$_e3c</b>, E3P = <b>$_e3p</b> <br />";
				
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
		
		$sql_etapy_update = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_E1C=$_e1c, zgl_E1P=$_e1p, zgl_E2C=$_e2c, zgl_E2P=$_e2p, zgl_E3C=$_e3c, zgl_E3P=$_e3p WHERE (zgl_nr=$_REQUEST[nr]) LIMIT 1";
		$result_etapy = mysql_query($sql_etapy_update, $conn_hd) or die($k_b);
		
		?>
		<script>
			document.getElementById('TrwaLadowanie').style.display='none';
			self.close();
		</script>
		<?php 
		
	}
// ########################  PRACE WG UMOWY - KONIEC ############################

// KONIEC

} else {

	errorheader("Nie zamykaj tego okna");
	echo "<div id=TrwaLadowanie style=\"color:grey; font-weight:bold; text-align:center; font-size:13px; border: 1px solid #FC9898; background-color:white;padding:10px;\">";
	echo "Trwa aktualizowanie danych o obsługiwanych zgłoszeniach...<input type=image class=border0 src=img/loader.gif>";
	echo "</div>";
	ob_flush();
	flush();
	
	$ile_zgloszen = $_REQUEST[nrs_cnt];
	$zgloszenie = explode(",",$_REQUEST[nrs]);
	
	for ($ii=0; $ii<=$ile_zgloszen-1; $ii++) {
		$jedno_zgloszenie = $zgloszenie[$ii];

		// ustalenie kategorii zgłoszenia
			list($kat)=mysql_fetch_array(mysql_query("SELECT zgl_kategoria FROM $dbname_hd.hd_zgloszenie WHERE zgl_id=$jedno_zgloszenie LIMIT 1", $conn_hd));
			
		// ########################  KONSULTACJE - POCZĄTEK ############################	
			if ($kat == 1) {

				$_e1c = 0;
				$_e1p = 0;
				$_e2c = 0;
				$_e2p = 0;
			
					list($_e3c)=mysql_fetch_array(mysql_query("SELECT zgl_szcz_czas_wykonywania FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$jedno_zgloszenie) and (zgl_szcz_widoczne=1)", $conn_hd));
					
				$_e3p = 0;
				
				$sql_etapy_update = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_E1C=$_e1c, zgl_E1P=$_e1p, zgl_E2C=$_e2c, zgl_E2P=$_e2p, zgl_E3C=$_e3c, zgl_E3P=$_e3p WHERE (zgl_nr=$jedno_zgloszenie) LIMIT 1";
				$result_etapy = mysql_query($sql_etapy_update, $conn_hd) or die($k_b);
				
				?>
				<script>
					document.getElementById('TrwaLadowanie').style.display='none';
					self.close();
				</script>
				<?php 
			}
		// ########################  KONSULTACJE - KONIEC ############################
			
		// ########################  AWARIE - POCZĄTEK ############################	
			if (($kat == 2) || ($kat == 6)) {
				$_e1c = 0;
				$_e1p = 0;
				$_e2c = 0;
				$_e2p = 0;
				$_e3c = 0;			
				$_e3p = 0;
				
				list($ile_krokow)=mysql_fetch_array(mysql_query("SELECT count(zgl_szcz_id) FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$jedno_zgloszenie) and (zgl_szcz_widoczne=1)", $conn_hd));
				
				// prace wg umowy zakończone w jednym kroku
				if ($ile_krokow==1) {
					
					list($_temp_czasRozp, $_temp_czasWyk)=mysql_fetch_array(mysql_query("SELECT zgl_szcz_czas_rozpoczecia_kroku, zgl_szcz_czas_wykonywania FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$jedno_zgloszenie) and (zgl_szcz_widoczne=1)", $conn_hd));			
					$newTime = AddMinutesToDate($_temp_czasWyk,$_temp_czasRozp);
					
					$roznica = MinutesBetween($_temp_czasRozp, $newTime, $kwt, $serwis_working_time);
					if ($roznica==0) {
						$roznica = MinutesBetween($_temp_czasRozp, $newTime, $serwis_working_time, $serwis_working_time);
					}
					$_e1c += $roznica;
					
				} else {
				// prace wg umowy realizowane w wielu krokach
				
					// pobranie godzin pracy komórki
					list($kwt)=mysql_fetch_array(mysql_query("SELECT zgl_komorka_working_hours FROM $dbname_hd.hd_zgloszenie WHERE zgl_id=$jedno_zgloszenie LIMIT 1", $conn_hd));
					if ($kwt=='') $kwt = $default_working_time;

					$sql_petla="SELECT * FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$jedno_zgloszenie) and (zgl_szcz_widoczne=1)";
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
							if ($last_status==3) $last_status==1;
							
							$roznica = MinutesBetween($_temp_czasRozp, $newTime, $kwt, $serwis_working_time);
							if ($roznica==0) {
								$roznica = MinutesBetween($_temp_czasRozp, $newTime, $serwis_working_time, $serwis_working_time);
							}
							$_e1c += $roznica;
							
						} elseif ($cnt_krok<=$ile_krokow) { 
							// KROK > 1    i     KROK < n
								if (($last_SS == 'START') && ($currSS == 'START')) {						
									
									$roznica = MinutesBetween($last_czas_zak, $newTime, $kwt, $serwis_working_time);
									if ($roznica==0) $roznica = MinutesBetween($last_czas_zak, $newTime, $serwis_working_time, $serwis_working_time);						
									
									// jeżeli status nowy lub przypisany
									if (($last_status==1) || ($last_status==2)) {
										$roznica2 = MinutesBetween($last_czas_rozp, $_temp_czasRozp, $kwt, $serwis_working_time);
										
										if ($roznica2==0) $roznica = MinutesBetween($last_czas_rozp, $_temp_czasRozp, $serwis_working_time, $serwis_working_time);				
										$_e1c += $roznica2;
										
										$roznica3 = MinutesBetween($_temp_czasRozp, $newTime, $kwt, $serwis_working_time);
										if ($roznica3==0) $roznica = MinutesBetween($_temp_czasRozp, $newTime, $serwis_working_time, $serwis_working_time);			
										
										$_e2c += $roznica3;
										
									} else {
										// pozostałe statusy ( > 2 )
										if ($last_czy_rozw==1) {
											if ($last_SS == 'START') $_e3c += $roznica;
											if ($last_SS == 'STOP') $_e3p += $roznica;
										} else {
											if ($last_SS == 'START') $_e2c += $roznica;
											if ($last_SS == 'STOP') $_e2p += $roznica;
										}
									}
								}
								
								if (($last_SS == 'START') && ($currSS == 'STOP')) {

									$roznica = MinutesBetween($last_czas_zak, $newTime, $kwt, $serwis_working_time);
									if ($roznica==0) $roznica = MinutesBetween($last_czas_zak, $newTime, $serwis_working_time, $serwis_working_time);						

									// jeżeli status nowy lub przypisany
									if (($last_status==1) || ($last_status==2)) {
										$roznica2 = MinutesBetween($last_czas_rozp, $_temp_czasRozp, $kwt, $serwis_working_time);
										
										if ($roznica2==0) $roznica = MinutesBetween($last_czas_rozp, $_temp_czasRozp, $serwis_working_time, $serwis_working_time);				
										$_e1c += $roznica2;
										
										$roznica3 = MinutesBetween($_temp_czasRozp, $newTime, $kwt, $serwis_working_time);
										if ($roznica3==0) $roznica = MinutesBetween($_temp_czasRozp, $newTime, $serwis_working_time, $serwis_working_time);			
										
										$_e2c += $roznica3;
									} else {
										// pozostałe statusy ( > 2 )
										if ($last_czy_rozw==1) {
											if ($last_SS == 'START') $_e3c += $roznica;
											if ($last_SS == 'STOP') $_e3p += $roznica;
										} else {
											if ($last_SS == 'START') $_e2c += $roznica;
											if ($last_SS == 'STOP') $_e2p += $roznica;
										}
									}
									
								}
								
								if (($last_SS == 'STOP') && ($currSS == 'START')) {

									$roznica = MinutesBetween($last_czas_zak, $_temp_czasRozp, $kwt, $serwis_working_time);
								//	if ($roznica==0) $roznica = MinutesBetween($last_czas_zak, $_temp_czasRozp, $serwis_working_time, $serwis_working_time);	
								
									// jeżeli status nowy lub przypisany
									if (($last_status==1) || ($last_status==2)) {
										$roznica2 = MinutesBetween($last_czas_rozp, $_temp_czasRozp, $kwt, $serwis_working_time);
										
										if ($roznica2==0) $roznica = MinutesBetween($last_czas_rozp, $_temp_czasRozp, $serwis_working_time, $serwis_working_time);				
										$_e1c += $roznica2;
										
										$roznica3 = MinutesBetween($_temp_czasRozp, $newTime, $kwt, $serwis_working_time);
										if ($roznica3==0) $roznica = MinutesBetween($_temp_czasRozp, $newTime, $serwis_working_time, $serwis_working_time);			
										
										$_e2c += $roznica3;
									} else {
										// pozostałe statusy ( > 2 )
										if ($last_czy_rozw==1) {
											if ($last_SS == 'START') $_e3c += $roznica;
											if ($last_SS == 'STOP') $_e3p += $roznica;
										} else {
											if ($last_SS == 'START') $_e2c += $roznica;
											if ($last_SS == 'STOP') $_e2p += $roznica;
										}
									}
									
									//$_e3p += $roznica;

									$roznica = MinutesBetween($_temp_czasRozp, $newTime, $kwt, $serwis_working_time);
									if ($roznica==0) {
										$roznica = MinutesBetween($_temp_czasRozp, $newTime, $serwis_working_time, $serwis_working_time);								
										//echo "<b>[ ! ]</b> ";								
									}
									
									// jeżeli status nowy lub przypisany
									if (($last_status==1) || ($last_status==2)) {
										$roznica2 = MinutesBetween($last_czas_rozp, $_temp_czasRozp, $kwt, $serwis_working_time);
										
										if ($roznica2==0) $roznica = MinutesBetween($last_czas_rozp, $_temp_czasRozp, $serwis_working_time, $serwis_working_time);				
										$_e1c += $roznica2;
										
										$roznica3 = MinutesBetween($_temp_czasRozp, $newTime, $kwt, $serwis_working_time);
										if ($roznica3==0) $roznica = MinutesBetween($_temp_czasRozp, $newTime, $serwis_working_time, $serwis_working_time);			
										
										$_e2c += $roznica3;
									} else {
										// pozostałe statusy ( > 2 )
										if ($last_czy_rozw==1) {
											if ($last_SS == 'START') $_e3c += $roznica;
											if ($last_SS == 'STOP') $_e3p += $roznica;
										} else {
											if ($last_SS == 'START') $_e2c += $roznica;
											if ($last_SS == 'STOP') $_e2p += $roznica;
										}
									}
								}
								
								if (($last_SS == 'STOP') && ($currSS == 'STOP')) {

									$roznica = MinutesBetween($last_czas_zak, $newTime, $kwt, $serwis_working_time);
									if ($roznica==0) $roznica = MinutesBetween($last_czas_zak, $newTime, $serwis_working_time, $serwis_working_time);	
									//echo "#".$roznica."#";
									// jeżeli status nowy lub przypisany
									if (($last_status==1) || ($last_status==2)) {
										$roznica2 = MinutesBetween($last_czas_rozp, $_temp_czasRozp, $kwt, $serwis_working_time);
										
										if ($roznica2==0) $roznica = MinutesBetween($last_czas_rozp, $_temp_czasRozp, $serwis_working_time, $serwis_working_time);				
										$_e1c += $roznica2;
										
										$roznica3 = MinutesBetween($_temp_czasRozp, $newTime, $kwt, $serwis_working_time);
										if ($roznica3==0) $roznica = MinutesBetween($_temp_czasRozp, $newTime, $serwis_working_time, $serwis_working_time);			
										
										$_e2c += $roznica3;
									} else {
										// pozostałe statusy ( > 2 )
										if ($last_czy_rozw==1) {
											if ($last_SS == 'START') $_e3c += $roznica;
											if ($last_SS == 'STOP') $_e3p += $roznica;
										} else {
											if ($last_SS == 'START') $_e2c += $roznica;
											if ($last_SS == 'STOP') $_e2p += $roznica;
										}
									}							
									
								}
							
						} 
						
						//echo "Krok: <b>$cnt_krok</b>,	CurrSS = <b>$_temp_czasSS</b>,	LastSS = <b>$last_SS</b>,	różnica = <b>$roznica</b>, E1C = <b>$_e1c</b>, E1P = <b>$_e1p</b>, E2C = <b>$_e2c</b>, E2P = <b>$_e2p</b>, E3C = <b>$_e3c</b>, E3P = <b>$_e3p</b> <br />";
						
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
				
				$sql_etapy_update = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_E1C=$_e1c, zgl_E1P=$_e1p, zgl_E2C=$_e2c, zgl_E2P=$_e2p, zgl_E3C=$_e3c, zgl_E3P=$_e3p WHERE (zgl_nr=$jedno_zgloszenie) LIMIT 1";
				$result_etapy = mysql_query($sql_etapy_update, $conn_hd) or die($k_b);
				
				?>
				<script>
					document.getElementById('TrwaLadowanie').style.display='none';
					self.close();
				</script>
				<?php 
				
			}
		// ########################  AWARIE - KONIEC ############################

		// ########################  PRACE WG UMOWY - POCZĄTEK ############################	
			if (($kat == 3) || ($kat == 4) || ($kat == 5)) {
			
				$_e1c = 0;
				$_e1p = 0;
				$_e2c = 0;
				$_e2p = 0;
				$_e3c = 0;			
				$_e3p = 0;
				
				list($ile_krokow)=mysql_fetch_array(mysql_query("SELECT count(zgl_szcz_id) FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$jedno_zgloszenie) and (zgl_szcz_widoczne=1)", $conn_hd));
				
				// prace wg umowy zakończone w jednym kroku
				if ($ile_krokow==1) {

					list($_temp_czasRozp, $_temp_czasWyk)=mysql_fetch_array(mysql_query("SELECT zgl_szcz_czas_rozpoczecia_kroku, zgl_szcz_czas_wykonywania FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$jedno_zgloszenie) and (zgl_szcz_widoczne=1)", $conn_hd));			
					$newTime = AddMinutesToDate($_temp_czasWyk,$_temp_czasRozp);
					
					$roznica = MinutesBetween($_temp_czasRozp, $newTime, $kwt, $serwis_working_time);
					if ($roznica==0) {
						$roznica = MinutesBetween($_temp_czasRozp, $newTime, $serwis_working_time, $serwis_working_time);
					}
					$_e3c += $roznica;

				} else {
				// prace wg umowy realizowane w wielu krokach
				
					// pobranie godzin pracy komórki
					list($kwt)=mysql_fetch_array(mysql_query("SELECT zgl_komorka_working_hours FROM $dbname_hd.hd_zgloszenie WHERE zgl_id=$jedno_zgloszenie LIMIT 1", $conn_hd));
					if ($kwt=='') $kwt = $default_working_time;

					$sql_petla="SELECT * FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$jedno_zgloszenie) and (zgl_szcz_widoczne=1)";
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
								//echo "<b>[ ! ]</b> ";
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

									$roznica = MinutesBetween($_temp_czasRozp, $newTime, $kwt, $serwis_working_time);
									if ($roznica==0) {
										$roznica = MinutesBetween($_temp_czasRozp, $newTime, $serwis_working_time, $serwis_working_time);								
										//echo "<b>/</b> ";								
									}
									$_e3c += $roznica;
								}
								
								if (($last_SS == 'STOP') && ($currSS == 'STOP')) {

									$roznica = MinutesBetween($last_czas_zak, $newTime, $kwt, $serwis_working_time);
									//if ($roznica==0) $roznica = MinutesBetween($last_czas_zak, $newTime, $serwis_working_time, $serwis_working_time);	
									
									$_e3p += $roznica;
									
								}
							
						} 
						
						//echo "Krok: <b>$cnt_krok</b>,	CurrSS = <b>$_temp_czasSS</b>,	LastSS = <b>$last_SS</b>,	różnica = <b>$roznica</b>, E3C = <b>$_e3c</b>, E3P = <b>$_e3p</b> <br />";
						
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
				
				$sql_etapy_update = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_E1C=$_e1c, zgl_E1P=$_e1p, zgl_E2C=$_e2c, zgl_E2P=$_e2p, zgl_E3C=$_e3c, zgl_E3P=$_e3p WHERE (zgl_nr=$jedno_zgloszenie) LIMIT 1";
				$result_etapy = mysql_query($sql_etapy_update, $conn_hd) or die($k_b);
		
	}

	?>
	<script>
		document.getElementById('TrwaLadowanie').style.display='none';
		self.close();
	</script>
	<?php 
	
}
}

?>
<script>
document.getElementById('TrwaLadowanie').style.display='none';
self.close();
</script>
</body>
</html>