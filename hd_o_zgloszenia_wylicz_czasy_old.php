<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body>
<?php 	

if ($_REQUEST[nr]!=0) {	

	errorheader("Nie zamykaj tego okna");

	echo "<div id=TrwaLadowanie style=\"color:white; font-weight:normal; text-align:center; font-size:13px; border: 1px solid silver; background-color:black;padding:10px;\">";
	echo "Trwa aktualizowanie danych o zgłoszeniu...<input type=image class=border0 src=img/loader7.gif>";
	echo "</div>";
	ob_flush();
	flush();
	
	// wyczyść czasy wszystkich etapów
	$sql_etapy_clear = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_E1C=0, zgl_E1P=0, zgl_E2C=0, zgl_E2P=0, zgl_E3C=0, zgl_E3P=0 WHERE ((zgl_nr='$_REQUEST[nr]') and (belongs_to='$es_filia')) LIMIT 1";
	$result_etapy = mysql_query($sql_etapy_clear, $conn_hd) or die($k_b);
	
	$sql_petla="SELECT * FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$_REQUEST[nr]) and (zgl_szcz_widoczne=1) and (belongs_to=$es_filia)"; // and (belongs_to=$es_filia)";	
	$result_petla = mysql_query($sql_petla, $conn_hd) or die($k_b);
	
	$ile_krokow = mysql_num_rows($result_petla);
	if ($debug) echo "Ilość kroków: ".$ile_krokow."<br />";
	
	if ($ile_krokow==1) {
		$_e1c = 0;
		$_e1p = 0;
		$_e2c = 0;
		$_e2p = 0;
		$_e3c = 0;
		$_e3p = 0;
		
		list($dRozpoczecia, $dZakonczenia, $kat, $kwt)=mysql_fetch_array(mysql_query("SELECT zgl_data_rozpoczecia, zgl_data_zakonczenia, zgl_kategoria, zgl_komorka_working_hours FROM $dbname_hd.hd_zgloszenie WHERE zgl_id=$_REQUEST[nr] LIMIT 1", $conn_hd));
	
		if ($kwt=='') $kwt = $default_working_time;
	
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
		}
		$_e3c = $_temp_czasWyk;		
		$roznica = MinutesBetween(date("Y-m-d H:i:s"), $_temp_czasRozp, $temp_zgl_komorka_working_hours, $serwis_working_time);
		
		$sql_etapy_clear1 = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_E3C='$_temp_czasWyk' WHERE (zgl_nr=$_REQUEST[nr]) LIMIT 1";
		$result_etapy1 = mysql_query($sql_etapy_clear1, $conn_hd) or die($k_b);

	} elseif ($ile_krokow>1) {
		
		//$debug = true;
		
		list($dRozpoczecia, $dZakonczenia, $kat, $kwt)=mysql_fetch_array(mysql_query("SELECT zgl_data_rozpoczecia, zgl_data_zakonczenia, zgl_kategoria, zgl_komorka_working_hours FROM $dbname_hd.hd_zgloszenie WHERE zgl_id=$_REQUEST[nr] LIMIT 1", $conn_hd));
	
	
		if ($kwt=='') $kwt = $default_working_time;
		$_e1c = 0;
		$_e1p = 0;
		$_e2c = 0;
		$_e2p = 0;
		$_e3c = 0;
		$_e3p = 0;
		
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
			$_temp_czy_przesuniety = $newArray4['zgl_szcz_przesuniety_termin_rozpoczecia'];
			
			if ($debug) echo $cnt_krok." (CW: ".$_temp_czasWyk." | ";
			
				if ($cnt_krok>1) {
					$roznica = MinutesBetween($last_czas_rozp, $_temp_czasRozp, $kwt, $serwis_working_time);
				//	echo ">>>>>>>".$roznica;
					
				} else {
					$last_czas_rozp = $_temp_czasRozp;
					$last_status = $_temp_status;
					$last_czy_rozw = $_temp_czy_rozwiazany_szcz;
					$last_czy_przesuniety = $_temp_czy_przesuniety;	
					if ($debug) echo "Status: $last_status | ";
					$roznica = MinutesBetween($last_czas_rozp, $_temp_czasRozp, $kwt, $serwis_working_time);	
				}
				
				//echo " [$last_czas_rozp, $_temp_czasRozp, $kwt, $serwis_working_time] ";
				if ($cnt_krok>1) {
					if ($debug) echo ">Status: $last_status | ";
					switch ($last_status) {
						case '1' : 	$_e1c += $roznica;
									// dorobić przestuj na etapie reakcji (przesunięcie terminu rozpoczęcia)
									break;					
						case '2' : 	$_e1c += $roznica;
									// dorobić przestuj na etapie reakcji (przesunięcie terminu rozpoczęcia)
									break;
						case '3' : 	if ($last_czy_przesuniety==0) {
										if ($last_czy_rozw==0) { $_e2c += $roznica; } else { $_e3c += $roznica; }
									} else {
										$_e1p += $roznica;
									}
									break;
						case '3A': 	if ($last_czy_rozw==0) { $_e2p += $roznica; } else { $_e3p += $roznica; } // przestuj
									break;
						case '3B' : if ($last_czy_rozw==0) { $_e2c += $roznica; } else { $_e3c += $roznica; }
									break;
						case '4' : 	if ($last_czy_rozw==0) { $_e2p += $roznica; } else { $_e3p += $roznica; } // przestuj
									break;
						case '5' : 	if ($last_czy_rozw==0) { $_e2c += $roznica; } else { $_e3c += $roznica; }
									break;
						case '6' : 	if ($last_czy_rozw==0) { $_e2c += $roznica; } else { $_e3c += $roznica; }
									break;
						case '7' : 	if ($last_czy_rozw==0) { $_e2c += $roznica; } else { $_e3c += $roznica; }
									break;
					}
					
				}
				
			$last_czas_rozp = $_temp_czasRozp;
			$last_status = $_temp_status;
			$last_czy_rozw = $_temp_czy_rozwiazany_szcz;
			$last_czy_przesuniety = $_temp_czy_przesuniety;	
			
			if ($debug) echo " roznica = $roznica<br />";
			$cnt_krok++;
		}
		
	}
	
	// uaktualnij tabelę główną
	if ($debug) {
		echo "E1C (Czas etapu reakcji) = ".$_e1c."<br />";
		echo "E1P (Przestuj na etapie reakcji) = ".$_e1p."<br />";
		echo "E2C (Czas etapu rozwiązania) = ".$_e2c."<br />";
		echo "E2P (Przestuj etapu rozwiązania) = ".$_e2p."<br />";
		echo "E3C (Czas etapu zakończenia) = ".$_e3c."<br />";
		echo "E3P (Przestuj etapu zakończenia) = ".$_e3p."<br />";
	}
	
	$sql_etapy_update = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_E1C=$_e1c, zgl_E1P=$_e1p, zgl_E2C=$_e2c, zgl_E2P=$_e2p, zgl_E3C=$_e3c, zgl_E3P=$_e3p WHERE ((zgl_nr='$_REQUEST[nr]') and (belongs_to='$es_filia')) LIMIT 1";
		
	//echo "<br /> ---->".$sql_etapy_update;
	
	$result_update = mysql_query($sql_etapy_update, $conn_hd) or die($k_b);
	
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
		
		//echo $jedno_zgloszenie."<br />";
		
		// wyczyść czasy wszystkich etapów
		$sql_etapy_clear = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_E1C=0, zgl_E1P=0, zgl_E2C=0, zgl_E2P=0, zgl_E3C=0, zgl_E3P=0 WHERE ((zgl_nr='$jedno_zgloszenie') and (belongs_to='$es_filia')) LIMIT 1";
		$result_etapy = mysql_query($sql_etapy_clear, $conn_hd) or die($k_b);
		
		$sql_petla="SELECT * FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$jedno_zgloszenie') and (zgl_szcz_widoczne=1) and (belongs_to=$es_filia)"; // and (belongs_to=$es_filia)";	
		$result_petla = mysql_query($sql_petla, $conn_hd) or die($k_b);
		
		$ile_krokow = mysql_num_rows($result_petla);
		
		if ($ile_krokow==1) {
			$_e1c = 0;
			$_e1p = 0;
			$_e2c = 0;
			$_e2p = 0;
			$_e3c = 0;
			$_e3p = 0;
			
			list($dRozpoczecia, $dZakonczenia, $kat, $kwt)=mysql_fetch_array(mysql_query("SELECT zgl_data_rozpoczecia, zgl_data_zakonczenia, zgl_kategoria, zgl_komorka_working_hours FROM $dbname_hd.hd_zgloszenie WHERE zgl_nr='$jedno_zgloszenie' LIMIT 1", $conn_hd));
		
			if ($kwt=='') $kwt = $default_working_time;
		
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
			}
			
			$roznica = MinutesBetween(date("Y-m-d H:i:s"), $_temp_czasRozp, $temp_zgl_komorka_working_hours, $serwis_working_time);
			
			$sql_etapy_clear = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_E1C=0, zgl_E1P=0, zgl_E2C=0, zgl_E2P=0, zgl_E3C=$_temp_czasWyk, zgl_E3P=0 WHERE ((zgl_nr='$jedno_zgloszenie') and (belongs_to='$es_filia')) LIMIT 1";
			$result_etapy = mysql_query($sql_etapy_clear, $conn_hd) or die($k_b);
			
		} elseif ($ile_krokow>1) {
		
			list($dRozpoczecia, $dZakonczenia, $kat, $kwt)=mysql_fetch_array(mysql_query("SELECT zgl_data_rozpoczecia, zgl_data_zakonczenia, zgl_kategoria, zgl_komorka_working_hours FROM $dbname_hd.hd_zgloszenie WHERE zgl_nr='$jedno_zgloszenie' LIMIT 1", $conn_hd));
		
			if ($kwt=='') $kwt = $default_working_time;
			$_e1c = 0;
			$_e1p = 0;
			$_e2c = 0;
			$_e2p = 0;
			$_e3c = 0;
			$_e3p = 0;
			
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
				$_temp_czy_przesuniety = $newArray4['zgl_szcz_przesuniety_termin_rozpoczecia'];
				
				if ($debug) echo $cnt_krok." | ";
				
					if ($cnt_krok>1) {
						
						$roznica = MinutesBetween($last_czas_rozp, $_temp_czasRozp, $kwt, $serwis_working_time);
						
					} else {
						$last_czas_rozp = $_temp_czasRozp;
						$last_status = $_temp_status;
						$last_czy_rozw = $_temp_czy_rozwiazany_szcz;
						$last_czy_przesuniety = $_temp_czy_przesuniety;	
						if ($debug) echo "Status: $last_status | ";
						$roznica = MinutesBetween($last_czas_rozp, $_temp_czasRozp, $kwt, $serwis_working_time);	
					}
					
					//echo " [$last_czas_rozp, $_temp_czasRozp, $kwt, $serwis_working_time] ";
					if ($cnt_krok>1) {
						if ($debug) echo "Status: $last_status | ";
						switch ($last_status) {
							case '1' : 	$_e1c += $roznica;
										// dorobić przestuj na etapie reakcji (przesunięcie terminu rozpoczęcia)
										break;					
							case '2' : 	$_e1c += $roznica;
										// dorobić przestuj na etapie reakcji (przesunięcie terminu rozpoczęcia)
										break;
							case '3' : 	if ($last_czy_przesuniety==0) {
											if ($last_czy_rozw==0) { $_e2c += $roznica; } else { $_e3c += $roznica; }
										} else {
											$_e1p += $roznica;
										}
										break;
							case '3A': 	if ($last_czy_rozw==0) { $_e2p += $roznica; } else { $_e3p += $roznica; } // przestuj
										break;
							case '3B' : if ($last_czy_rozw==0) { $_e2c += $roznica; } else { $_e3c += $roznica; }
										break;
							case '4' : 	if ($last_czy_rozw==0) { $_e2p += $roznica; } else { $_e3p += $roznica; } // przestuj
										break;
							case '5' : 	if ($last_czy_rozw==0) { $_e2c += $roznica; } else { $_e3c += $roznica; }
										break;
							case '6' : 	if ($last_czy_rozw==0) { $_e2c += $roznica; } else { $_e3c += $roznica; }
										break;
							case '7' : 	if ($last_czy_rozw==0) { $_e2c += $roznica; } else { $_e3c += $roznica; }
										break;
						}
						
					}
					
				$last_czas_rozp = $_temp_czasRozp;
				$last_status = $_temp_status;
				$last_czy_rozw = $_temp_czy_rozwiazany_szcz;
				$last_czy_przesuniety = $_temp_czy_przesuniety;	
				
				if ($debug) echo " roznica = $roznica<br />";
				$cnt_krok++;
			}
			
		}
		
		// uaktualnij tabelę główną
		if ($debug) {
			echo "E1C (Czas etapu reakcji) = ".$_e1c."<br />";
			echo "E1P (Przestuj na etapie reakcji) = ".$_e1p."<br />";
			echo "E2C (Czas etapu rozwiązania) = ".$_e2c."<br />";
			echo "E2P (Przestuj etapu rozwiązania) = ".$_e2p."<br />";
			echo "E3C (Czas etapu zakończenia) = ".$_e3c."<br />";
			echo "E3P (Przestuj etapu zakończenia) = ".$_e3p."<br />";
		}
		
		$sql_etapy_update = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_E1C=$_e1c, zgl_E1P=$_e1p, zgl_E2C=$_e2c, zgl_E2P=$_e2p, zgl_E3C=$_e3c, zgl_E3P=$_e3p WHERE ((zgl_nr='$jedno_zgloszenie') and (belongs_to='$es_filia')) LIMIT 1";
			
		//ho $sql_etapy_update;
		
		$result_update = mysql_query($sql_etapy_update, $conn_hd) or die($k_b);
	}
}

?>
<script>
document.getElementById('TrwaLadowanie').style.display='none';
self.close();
</script>
</body>
</html>