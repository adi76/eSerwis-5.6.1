<?php 
// #############################################################################################################################################
// 
// zmienne wejściowe :	$temp_nr								$__zgl_nr
//						$temp_zgl_data_rozpoczecia				$__zgl_data_r
//						$temp_zgl_data_zakonczenia				$__zgl_data_z
//						$temp_zgl_E1P							$__zgl_e1p
//						$temp_zgl_E2P							$__zgl_e2p
//						$temp_zgl_E3P							$__zgl_e3p
//						$temp_zgl_komorka_working_hours			$__zgl_kwh
//						$temp_op								$__zgl_op
//						$temp_kategoria							$__zgl_kat
//						$temp_status							$__zgl_status
//

			if ($__wersja == 1) { $_X_ = "div"; echo "<br /><br />"; } else { $_X_ = "h2"; }
			
			$pokazczas_przekroczony = 0;
			$pokazczas_pozostaly = 0;
			
			// kontrola czasu rozpoczęcia i czasu zamknięcia (komunikaty)
			if (array_search($__zgl_kat, array('2','6'))>-1) {
			
				$poprawne_daty_umowne = (($__zgl_data_r!='0000-00-00 00:00:00') && ($__zgl_data_z!='0000-00-00 00:00:00'));
				

			if ($poprawne_daty_umowne) {
				// monit rozpoczęcia 
				if (array_search($__zgl_status, array('1','2'))>-1) {
					
					list($data_ostatniego_kroku, $czasSTARTSTOP, $czy_rozw)=mysql_fetch_array(mysql_query("SELECT zgl_szcz_czas_rozpoczecia_kroku, zgl_szcz_czas_start_stop,zgl_szcz_czy_rozwiazany_problem FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_widoczne=1) and (zgl_szcz_zgl_id=$__zgl_nr) ORDER BY zgl_szcz_nr_kroku DESC LIMIT 1", $conn_hd));
				
					//	if ($czasSTARTSTOP=='START') {
					if ($__zgl_data_r>date("Y-m-d H:i:s")) {
						$pokazczas_pozostaly = MinutesBetween(date("Y-m-d H:i:s"),$__zgl_data_r, $__zgl_kwh, $serwis_working_time);								
					}
						
//					echo ">>>>>".$pokazczas_pozostaly;
						
					if ($pokazczas_pozostaly!=0) {
						$pokazczas_pozostaly+=$__zgl_e1p;
					} else {
						$pokazczas_przekroczony = MinutesBetween($__zgl_data_r, date("Y-m-d H:i:s"), $__zgl_kwh, $serwis_working_time);
//						echo ">>>>>".$pokazczas_przekroczony;
						if ($pokazczas_przekroczony!=0) $pokazczas_przekroczony+=$__zgl_e1p;
					}						
						
						// jeżeli jeszcze mamy czas na rozpoczęcie
						if (($pokazczas_pozostaly>0) && ($pokazczas_przekroczony==0)) {
							$pokazczas = $pokazczas_pozostaly;
							$pokazczas_w_s = $pokazczas * 60;						
							if ($pokazczas_w_s<=$HD_prog_ostrzezenia_o_nierozpoczeciu_zgloszenia) {
								if ($pokazczas>0) {	
									echo "<$_X_ class='ostrzezenie_r_before ";
									if ($pokazczas_w_s<$blinking_R_from) echo "blinking";
									echo "'>";
									
									//echo "<font style='font-weight:normal'>Data <b>rozpoczęcia</b> realizacji zgłoszenia zgodna z umową:</font> <b>".substr($__zgl_data_r,0,16)."</b><br />";
									//if ($__add_br==1) echo "<br />";
									echo "<font style='font-weight:normal; color:#FFFF00;'><span class=blinking2>Czas pozostały do <b>rozpoczęcia</b> realizacji zgłoszenia: <b>";
									echo minutes2hours($pokazczas,'short');
									echo "</b>";
									echo "<br />Ustalona data: ".substr($__zgl_data_r,0,16)."";
									echo "</font></span>";
									
									//echo "<font style='font-weight:normal'>Czas pozostały do <b>rozpoczęcia</b> realizacji zgłoszenia: <b></font>";
									//echo minutes2hours($pokazczas,'short');
									//echo "</b>";
									
									echo "</$_X_>";
								}
							}
						}
						
						// czas na rozpoczęcie - przekroczony
						if (($pokazczas_przekroczony>0) && ($pokazczas_pozostaly==0)) {
					
							$pokazczas = $pokazczas_przekroczony;
							$pokazczas_w_s = $pokazczas * 60;						
							//echo "<br />$pokazczas_w_s<=$HD_prog_ostrzezenia_o_nierozpoczeciu_zgloszenia";
							//echo "$pokazczas_w_s>$HD_prog_ostrzezenia_o_nierozpoczeciu_zgloszenia";
							
							if ($pokazczas_w_s<=$HD_prog_ostrzezenia_o_nierozpoczeciu_zgloszenia) {
								if ($pokazczas>0) {	
									
									echo "<$_X_ class='ostrzezenie_r_after blinking'>";
									
									//echo "<font style='font-weight:normal'>Data <b>rozpoczęcia</b> realizacji zgłoszenia zgodna z umową:</font> <b>".substr($__zgl_data_r,0,16)."</b><br />";
									//if ($__add_br==1) echo "<br />";
									echo "<font style='font-weight:normal; color:#00FFFF;'><span class=blinking3>1Przekroczono umowny czas na <b>rozpoczęcia</b> realizacji zgłoszenia o ";
									echo "<b>".minutes2hours(abs(ceil($pokazczas)),'short')."</b></font></span>";
									if ($__zgl_status!=1) { 
										echo "<br /><br /><font style='font-weight:normal'>Osoba odpowiedzialna:</font><br /><b>$__zgl_op</b><br />";
									} else echo "<br /><br /><b>!!! Zgłoszenie nie przypisane !!!</b><br />";
									
									echo "</$_X_>";
								}
							}
						}							
					
				//	}

				} else {
					
					// sprawdzenie czy dotrzymany został czas na rozpoczęcie. Jeżeli nie - wyświetlenie komunikatu.
					list($data_ostatniego_kroku, $czasSTARTSTOP,$czy_rozw)=mysql_fetch_array(mysql_query("SELECT zgl_szcz_czas_rozpoczecia_kroku, zgl_szcz_czas_start_stop,zgl_szcz_czy_rozwiazany_problem FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_widoczne=1) and (zgl_szcz_zgl_id=$__zgl_nr) and (zgl_szcz_status<>1) and (zgl_szcz_status<>2) ORDER BY zgl_szcz_nr_kroku ASC LIMIT 1", $conn_hd));
					
					if ($data_ostatniego_kroku>$__zgl_data_r) {
						//echo "$__zgl_data_r, $data_ostatniego_kroku, $__zgl_kwh, $serwis_working_time";
						$pokazczas_przekroczony = MinutesBetween($__zgl_data_r, $data_ostatniego_kroku, $__zgl_kwh, $serwis_working_time); //+$__zgl_e1p;
						//echo "-->".$pokazczas_przekroczony;
					
						if ($__zgl_e2p>0) $pokazczas_przekroczony+=$__zgl_e2p;
						if ($czy_rozw==1) $pokazczas_przekroczony+=$__zgl_e3p;
						
						if ($pokazczas_przekroczony==0) {
							//echo "$data_ostatniego_kroku>".substr($__zgl_data_r,11,8)."";
							if ($data_ostatniego_kroku>substr($__zgl_data_r,11,8)) {
								//$pokazczas_przekroczony = HD_RoznicaDat_w_m($__zgl_data_r, $data_ostatniego_kroku);
								$pokazczas_przekroczony = MinutesBetween($__zgl_data_r, $data_ostatniego_kroku, $__zgl_kwh, $serwis_working_time);	
								if ($pokazczas_przekroczony==0) $pokazczas_przekroczony = MinutesBetween($__zgl_data_r, $data_ostatniego_kroku, $serwis_working_time, $serwis_working_time);	
								//echo "-->".$pokazczas_przekroczony;
							}
						}
						
						if ($pokazczas_przekroczony>0) {
							// było przekroczenie czasu rozpoczęcia
							echo "<$_X_ class='ostrzezenie_r_after'>";
							
							//echo "<font style='font-weight:normal'>Data <b>rozpoczęcia</b> realizacji zgłoszenia zgodna z umową:</font> <b>".substr($__zgl_data_r,0,16)."</b><br />";
							//if ($__add_br==1) echo "<br />";
							echo "<font style='font-weight:normal; color:#00FFFF;'><span class=blinking3>2Przekroczono umowny czas na <b>rozpoczęcie</b> realizacji zgłoszenia ";
							echo " o <b>".minutes2hours(abs(ceil($pokazczas_przekroczony)),'short')."</b></font></span>";
							
							echo "</$_X_>";						
						}
					} else {

// #######################################################################################################################################			
					
					$pokazczas_przekroczony = 0;
					$pokazczas_pozostaly = 0;
					// sprawdzenie czy dotrzymany został czas na zamknięcie. Jeżeli nie - wyświetlenie komunikatu.
					//list($data_ostatniego_kroku, $czasSTARTSTOP)=mysql_fetch_array(mysql_query("SELECT zgl_szcz_czas_rozpoczecia_kroku, zgl_szcz_czas_start_stop FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_widoczne=1) and (zgl_szcz_zgl_id=$__zgl_nr) and (zgl_szcz_status<>1) and (zgl_szcz_status<>2) ORDER BY zgl_szcz_nr_kroku ASC LIMIT 1", $conn_hd));
					
					//echo ">>>>>>".$data_ostatniego_kroku." - ".$__zgl_data_z."";
					
						if ($data_ostatniego_kroku>$__zgl_data_r) {
							
							$pokazczas_przekroczony = MinutesBetween($__zgl_data_r, $data_ostatniego_kroku, $__zgl_kwh, $serwis_working_time)+$__zgl_e1p;
							//echo "-->".$pokazczas_przekroczony;
							if ($__zgl_e2p>0) $pokazczas_przekroczony+=$__zgl_e2p;
							if ($czy_rozw==1) $pokazczas_przekroczony+=$__zgl_e3p;
							
							if ($pokazczas_przekroczony==0) {
							
								if ($data_ostatniego_kroku>substr($__zgl_data_z,11,8)) {
									//$pokazczas_przekroczony = HD_RoznicaDat_w_m($__zgl_data_r, $data_ostatniego_kroku);
									$pokazczas_przekroczony = MinutesBetween($__zgl_data_z, $data_ostatniego_kroku, $__zgl_kwh, $serwis_working_time);	
									if ($pokazczas_przekroczony==0) $pokazczas_przekroczony = MinutesBetween($__zgl_data_z, $data_ostatniego_kroku, $serwis_working_time, $serwis_working_time);	
									//echo "-->".$pokazczas_przekroczony;
								}
							
							
							
							
							//	if ($data_ostatniego_kroku>$__zgl_data_z) {
							//		$pokazczas_przekroczony = HD_RoznicaDat_w_m($__zgl_data_z, $data_ostatniego_kroku);					
							//	}
							}
							
							if ($pokazczas_przekroczony>0) {
								// było przekroczenie czasu rozpoczęcia
								echo "<$_X_ class='ostrzezenie_r_after'>";
								
								//echo "<font style='font-weight:normal'>Data <b>rozpoczęcia</b> realizacji zgłoszenia zgodna z umową:</font> <b>".substr($__zgl_data_r,0,16)."</b><br />";
								//if ($__add_br==1) echo "<br />";
								echo "<font style='font-weight:normal; color:#00FFFF;'><span class=blinking3>* Przekroczono umowny czas na <b>rozpoczęcie</b> realizacji zgłoszenia ";
								echo " o <b>".minutes2hours(abs(ceil($pokazczas_przekroczony)),'short')."</b></font></span>";
								
								echo "</$_X_>";						
							}
						}
					}
// #############################################################################################################################################
					
					// sprawdzenie czy nie kończy się czas na zakończenie zgłoszenia					
					$pokazczas_przekroczony = 0;
					$pokazczas_pozostaly = 0;
			
					list($data_ostatniego_kroku, $czasSTARTSTOP, $czy_rozw,$zglszczstatus)=mysql_fetch_array(mysql_query("SELECT zgl_szcz_czas_rozpoczecia_kroku, zgl_szcz_czas_start_stop, zgl_szcz_czy_rozwiazany_problem, zgl_szcz_status FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_widoczne=1) and (zgl_szcz_zgl_id=$__zgl_nr) and (zgl_szcz_status<>1) and (zgl_szcz_status<>2) ORDER BY zgl_szcz_nr_kroku DESC LIMIT 1", $conn_hd));

					if (($czasSTARTSTOP=='START')) {
					
					if ($__zgl_data_z>date("Y-m-d H:i:s")) {
						$pokazczas_pozostaly = MinutesBetween(date("Y-m-d H:i:s"),$__zgl_data_z, $__zgl_kwh, $serwis_working_time);
						
						if ($__zgl_status==9) $pokazczas_pozostaly = 0;
						
						if ($__zgl_e2p>0) $pokazczas_przekroczony+=$__zgl_e2p;
						if ($czy_rozw==1) $pokazczas_przekroczony+=$__zgl_e3p;
						
						if ($pokazczas_pozostaly!=0) {
							
							//$pokazczas_pozostaly+=($__zgl__zgl_e2p-$__zgl_e1p);
						} else {
							$pokazczas_przekroczony = MinutesBetween($__zgl_data_z, date("Y-m-d H:i:s"), $__zgl_kwh, $serwis_working_time);
							if ($pokazczas_przekroczony!=0) $pokazczas_przekroczony+=($__zgl__zgl_e2p-$__zgl_e1p);
						}
					
						//echo "$pokazczas_pozostaly ----- $pokazczas_przekroczony";
						// jeżeli jeszcze mamy czas na zakończenie
						if (($pokazczas_pozostaly>0) && ($pokazczas_przekroczony==0)) {
							$pokazczas = $pokazczas_pozostaly;
							$pokazczas_w_s = $pokazczas * 60;						
							if ($pokazczas_w_s<=$HD_prog_ostrzezenia_o_niezakonczeniu_zgloszenia) {
								if ($pokazczas>0) {	
									echo "<$_X_ class='ostrzezenie_z_before ";
									if ($pokazczas_w_s<$blinking_Z_from) echo "blinking";
									echo "'>";
									//echo "<font style='font-weight:normal'>Data <b>zamknięcia</b> zgłoszenia zgodna z umową:</font> <b>".substr($__zgl_data_z,0,16)."</b><br />";
									//if ($__add_br==1) echo "<br />";
									echo "<font style='font-weight:normal; color:#FFFF00;'><span class=blinking2>Czas pozostały do <b>zakończenia</b> realizacji zgłoszenia: <b>";
									echo minutes2hours($pokazczas,'short');
									echo "</b></font></span>";
									
									echo "</$_X_>";
								}
							}
						}
						
						// czas na zakończenie zgłoszenia - przekroczony
						if (($pokazczas_przekroczony>0) && ($pokazczas_pozostaly==0) && ($zglszczstatus!=9)) {
							$pokazczas = $pokazczas_przekroczony;
							$pokazczas_w_s = $pokazczas * 60;						
							if ($pokazczas_w_s<=$HD_prog_ostrzezenia_o_niezakonczeniu_zgloszenia) {
								if ($pokazczas>0) {	
									echo "<$_X_ class='ostrzezenie_z_after blinking'>";
									
									//echo "<font style='font-weight:normal'>Data <b>zamknięcia</b> zgłoszenia zgodna z umową:</font> <b>".substr($__zgl_data_z,0,16)."</b><br />";
									//if ($__add_br==1) echo "<br />";
									echo "<font style='font-weight:normal; color:#00FFFF;'><span class=blinking3>4Przekroczono umowny czas na <b>zamknięcie</b> realizacji zgłoszenia o ";
									echo "<b>".minutes2hours(abs(ceil($pokazczas)),'short')."</b></font></span>";
									if ($__zgl_status!=1) { 
										echo "<br /><br /><font style='font-weight:normal'>Osoba odpowiedzialna:</font><br /><b>$__zgl_op</b><br />";
									} else echo "<br /><br /><b>!!! Zgłoszenie nie przypisane !!!</b><br />";
									
									echo "</$_X_>";
								}
							}
						}

						if (($pokazczas_pozostaly==0) && ($pokazczas_przekroczony==0)) {
							
							if (($data_ostatniego_kroku>=$__zgl_data_z) && ($__zgl_status==9)) {
							$pokazczas_przekroczony = MinutesBetween($__zgl_data_z, $data_ostatniego_kroku, $__zgl_kwh, $serwis_working_time);
							
							if ($__zgl_e2p>0) $pokazczas_przekroczony+=$__zgl_e2p;
							if ($czy_rozw==1) $pokazczas_przekroczony+=$__zgl_e3p;

							$pokazczas_w_s = $pokazczas_przekroczony * 60;
							
							if ($pokazczas_w_s<=$HD_prog_ostrzezenia_o_niezakonczeniu_zgloszenia) {
								if ($pokazczas_w_s>0) {	
									echo "<$_X_ class='ostrzezenie_z_after'>";
									
									//echo "<font style='font-weight:normal'>Data <b>zamknięcia</b> zgłoszenia zgodna z umową:</font> <b>".substr($__zgl_data_z,0,16)."</b><br />";
									//if ($__add_br==1) echo "<br />";
									echo "<font style='font-weight:normal; color:#00FFFF;'><span class=blinking3>5Przekroczono umowny czas na <b>zamknięcie</b> realizacji zgłoszenia o ";
									echo "<b>".minutes2hours(abs(ceil($pokazczas_przekroczony)),'short')."</b></font></span>";
									if ($__zgl_status!=1) { 
										echo "<br /><br /><font style='font-weight:normal'>Osoba odpowiedzialna:</font><br /><b>$__zgl_op</b><br />";
									} else echo "<br /><br /><b>!!! Zgłoszenie nie przypisane !!!</b><br />";
									
									echo "</$_X_>";
								}
							}
							}
							
							
						}
						
					} else { 
						
						if (($data_ostatniego_kroku>=$__zgl_data_z) && ($__zgl_status==9)) {
							$pokazczas_przekroczony = MinutesBetween($__zgl_data_z, $data_ostatniego_kroku, $__zgl_kwh, $serwis_working_time);
														
							list($_zgl_zakonczenie_min)=mysql_fetch_array(mysql_query("SELECT zgl_zakonczenie_min FROM $dbname_hd.hd_zgloszenie WHERE (zgl_widoczne=1) and (zgl_id=$__zgl_nr) LIMIT 1", $conn_hd));
							
//							if ($__zgl_e2p>0) $pokazczas_przekroczony+=$__zgl_e2p;
							if ($czy_rozw==1) $pokazczas_przekroczony+=$__zgl_e3p;
							
							$pokazczas_w_s = $pokazczas_przekroczony * 60;
							//echo "<br />".$__zgl_data_z.",".$data_ostatniego_kroku.",".$__zgl_kwh.",".$serwis_working_time."<br />";
							//echo $pokazczas_w_s." ".$HD_prog_ostrzezenia_o_niezakonczeniu_zgloszenia;
							
							if (($pokazczas_w_s<=$HD_prog_ostrzezenia_o_niezakonczeniu_zgloszenia) && ($pokazczas_przekroczony>$_zgl_zakonczenie_min)) {
								if ($pokazczas_w_s>0) {	
									echo "<$_X_ class='ostrzezenie_z_after'>";
									
									//echo "<font style='font-weight:normal'>Data <b>zamknięcia</b> zgłoszenia zgodna z umową:</font> <b>".substr($__zgl_data_z,0,16)."</b><br />";
									//if ($__add_br==1) echo "<br />";
									echo "<font style='font-weight:normal; color:#00FFFF;'><span class=blinking3>6Przekroczono umowny czas na <b>zamknięcie</b> realizacji zgłoszenia o ";
									echo "<b>".minutes2hours(abs(ceil($pokazczas_przekroczony)),'short')."</b></font></span>";
									if ($__zgl_status!=1) { 
										echo "<br /><br /><font style='font-weight:normal'>Osoba odpowiedzialna:</font><br /><b>$__zgl_op</b><br />";
									} else echo "<br /><br /><b>!!! Zgłoszenie nie przypisane !!!</b><br />";
									
									echo "</$_X_>";
								}
							}
											
						}
						
						}
					} else {
						if (($temp_status=='3A') || ($temp_status=='4')) {
							echo "<$_X_ class='ostrzezenie_z_after'>";
								echo "<font style='font-weight:normal'>";
								if ($temp_status=='3A') {
									echo "Zgłoszenie w statusie <b>serwisie zewnętrznym</b>. Czas zatrzymany.<br />";
								}
								if ($temp_status=='4') {
									echo "Zgłoszenie w statusie <b>oczekiwanie na odpowiedź klienta</b>. Czas zatrzymany.<br />";
								}
								echo "</font>";
								
							echo "</$_X_>";
						}
					}
				}
				} else { 
					// jeżeli system źle wygenerował daty rozpoczęcia lub zakończenia
					echo "<$_X_ class='ostrzezenie_z_after blinking'>";
						if ($__zgl_data_r=='0000-00-00 00:00:00') echo "Błędnie wyliczony umowny czas na rozpoczęcie realizacji zgłoszenia";
						if (($__zgl_data_r!='0000-00-00 00:00:00') && ($__zgl_data_z!='0000-00-00 00:00:00')) echo "<br />";
						if ($__zgl_data_z=='0000-00-00 00:00:00') echo "Błędnie wyliczony umowny czas na zakończenie realizacji zgłoszenia";
						echo "";
					echo "</$_X_>";
					}
			}
// ############################################################################################################################################		
?>