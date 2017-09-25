<?php 
include_once('cfg_helpdesk.php');
include_once('header.php');
echo "<body";

if ($submit=='Zapisz zmiany') { } else {
echo " OnLoad=\"this.focus(); cookieForms('open', 'seryjna_obsluga_zgloszen'); document.getElementById('czas_wykonywania_h').focus(); CzyscTrasyWyjazdowe(); PokazZasadnoscZgloszenia_onLoad(); if ((document.getElementById('obsl_powiazana_z_wyjazdem').value=='') || (document.getElementById('obsl_powiazana_z_wyjazdem').value=='S'))	{ ObslugaPowZWyjazdem(false); } else { ObslugaPowZWyjazdem(true); } \" onUnload=\"cookieForms('save', 'seryjna_obsluga_zgloszen');\" ";
}
echo ">";

function toUpper($string) {
	$string = strtr($string,'qwertyuiopasdfghjklzxcvbnm','QWERTYUIOPASDFGHJKLZXCVBNM');
	return (strtr($string, 'ęóąśłżźćń','ĘÓĄŚŁŻŹĆŃ' )); 
}; 


if ($submit=='Zapisz zmiany') {
?><script>ShowWaitingMessage('Trwa zapisywanie danych... proszę czekać', 'Saving1');</script><?php ob_flush(); flush();

	if ($_SESSION[seryjna_obsluga_zgloszenia_zapisano]!='tak') {

		//echo "Ilość zgłoszeń : ".$_REQUEST[cnt_zgl]."<br />";
		$ile_zgloszen = $_REQUEST[cnt_zgl];
		
		$opzw = $_REQUEST[obsl_powiazana_z_wyjazdem];
		if (($opzw=='P') || ($opzw=='S')) { $obsluga_powiazana_z_wyjazdem = 1; } else { $obsluga_powiazana_z_wyjazdem = 0; }
		
	//	echo "Obsługa powiązana z wyjazdem : ".$obsluga_powiazana_z_wyjazdem." (km: ".$_REQUEST[ilosc_km].")<br />";			// true or false
		if ($obsluga_powiazana_z_wyjazdem==1) {
			//echo "Trasa wyjazdu : ".$_REQUEST[trasa_wyjazdu]."<br />";
		}

		if ($_REQUEST[WieleOsobCheck]=='on') {
			// lista dodatkowych osób wykonujących krok
			$DodatkoweOsoby = nl2br2($_REQUEST[lista_osob]);
			//$jeden_dodatkowy = explode(", ", $DodatkoweOsoby);
			$DodatkoweOsoby = str_replace(';', ',', $DodatkoweOsoby);
			$ile_dodatkowych = substr_count($DodatkoweOsoby,', ')-1;
		} else $DodatkoweOsoby = '';

		
		$u = 1;
		echo "<span id=czasy></span>";

		for ($ii=0; $ii<=$ile_zgloszen-1; $ii++) {
			$current_zgl_nr = $_REQUEST['nr_zgl-'.$ii];
			$current_zgl_id = $_REQUEST['id_zgl-'.$ii];
			
			$zgl_seryjme_unique_nr = Date('YmdHis')."".rand_str();
			
			// $zgl_seryjme_unique_nr = rand_str(20);
			//echo ">>> $current_zgl_nr";
			
			$current_komorka = $_REQUEST['komorka-'.$current_zgl_nr];
			$current_temat = $_REQUEST['temat_zgl-'.$current_zgl_nr];
			$current_data = $_REQUEST['oz_data-'.$current_zgl_nr];
			$current_godzina = $_REQUEST['oz_godzina-'.$current_zgl_nr];
			$current_status = $_REQUEST['SelectZmienStatus-'.$current_zgl_nr];
			$current_zz = $_REQUEST['zasadne-'.$current_zgl_nr];if ($current_zz=='TAK') { $current_zz = 1; } else { $current_zz = 0; }
			$current_czas = $_REQUEST['oz_czas-'.$current_zgl_nr];
			$current_km = $_REQUEST['oz_km-'.$current_zgl_nr];
			$current_wykonane_czynnosci = $_REQUEST['wyk_czynnosci_value-'.$current_zgl_nr];
			$current_trasa = $_REQUEST['trasa-'.$u];
			$current_kilometry = $_REQUEST['km-'.$u];
			
			$current_czas_przejazdu = $_REQUEST['oz_czas_przejazdu-'.$current_zgl_nr];
			
			$current_problem_rozwiazany = $_REQUEST['problem_rozwiazany-'.$current_zgl_nr];
			
			$u++;
			
			$dodatkowe1 = '';
			$dodatkowe3 = '';	// kolejnosc w zgloszeniu seryjnym
			
			// przelicz czas ropoczęcia kroku (czas zakończenia kroku - czas wykonywania)
			$newStartDate = SubMinutesFromDate($current_czas, $current_data." ".$current_godzina);
		
			$bylwyjazd=$obsluga_powiazana_z_wyjazdem;
			$dddd = date("Y-m-d H:i:s");
			$last_nr=$current_zgl_nr;
			
			$r3 = mysql_query("SELECT zgl_unikalny_nr FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to='$es_filia') and (zgl_nr='$last_nr'))", $conn_hd) or die($k_b);
			list($unique_nr)=mysql_fetch_array($r3);

//			$r3 = mysql_query("SELECT zgl_poledodatkowe2 FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to='$es_filia') and (zgl_nr='$last_nr'))", $conn_hd) or die($k_b);
//			list($zgl_seryjme_unique_nr)=mysql_fetch_array($r3);
			
			$r3 = mysql_query("SELECT zgl_szcz_nr_kroku FROM $dbname_hd.hd_zgloszenie_szcz WHERE ((zgl_szcz_zgl_id='$current_zgl_id') and (belongs_to='$es_filia')) ORDER BY zgl_szcz_nr_kroku DESC LIMIT 1", $conn_hd) or die($k_b);
			list($last_nr_kroku)=mysql_fetch_array($r3);
			
			$last_nr_kroku+=1;
		
			switch ($current_status) {
				case "2"	: $czas_START_STOP='START'; break;
				case "3"	: $czas_START_STOP='START'; break;
				case "4"	: $czas_START_STOP='STOP';  break;
				case "5"	: $czas_START_STOP='START';  break;
				case "6"	: $czas_START_STOP='START';  break;
				case "7"	: $czas_START_STOP='START';  break;
				case "8"	: $czas_START_STOP='START';  break;
				case "9"	: $czas_START_STOP='START';  break;
				case "3A"	: $czas_START_STOP='STOP';  break;
				case "3B"	: $czas_START_STOP='START';	break;
			}
		
			$d_cw = $current_czas;	// czas wykonywani
			
			$osoba_przypisana = $currentuser;
			//if ($current_status=='7') $osoba_przypisana = '';		
			
			$zam_wyslane = 0;
			$oferta_wyslana = 0;
			
			$przejechane_km = $current_km;
			if (($przejechane_km==0) || ($przejechane_km=='')) $przejechane_km = 0;
			if ($_REQUEST[obsl_powiazana_z_wyjazdem]=='S') $przejechane_km = 0;
			
			$awaria_z_przesunieciem=0;
			if ($current_status=='9') { $rozwiazany_problem = 1; $current_problem_rozwiazany = 1; }
			
			$sql = "INSERT INTO $dbname_hd.hd_zgloszenie_szcz VALUES ('',$current_zgl_id,'$zgl_seryjme_unique_nr',$last_nr_kroku,'$czas_START_STOP',$d_cw,'$newStartDate','$current_status','$current_wykonane_czynnosci','$osoba_przypisana','$DodatkoweOsoby',0,$oferta_wyslana,$zam_wyslane,$bylwyjazd,'$dddd','$currentuser',1,$przejechane_km,$awaria_z_przesunieciem,'','','','','','','$_REQUEST[hdds]',$current_problem_rozwiazany,$current_czas_przejazdu, $es_filia)";
			
			$result = mysql_query($sql, $conn_hd) or die($k_b);
		
			// zaktualizuj zgl_czy_rozwiazany_problem w zgłoszeniu 
			if ($current_problem_rozwiazany==1) $czy_rozwiazany_data = $current_data;
			
			$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_czy_rozwiazany_problem='$current_problem_rozwiazany', zgl_czy_rozwiazany_problem_data='$czy_rozwiazany_data' WHERE ((belongs_to='$es_filia') and (zgl_nr='$current_zgl_nr')) LIMIT 1";
			
			$result = mysql_query($sql, $conn_hd) or die($k_b);

		// wyciągnij numer kategorii i podkategorii
			$r3 = mysql_query("SELECT zgl_kategoria, zgl_podkategoria FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to='$es_filia') and (zgl_id='$current_zgl_id'))", $conn_hd) or die($k_b);
			list($current_kategoria,$current_podkategoria)=mysql_fetch_array($r3);
		// koniec
			
			$_zgl_DZS = $_REQUEST[zs_data]." ".$_REQUEST[zs_time];
			
			// jeżeli jest awaria WAN (kat:2, podkat:0) - rejestruj awarię WAN'u
			// zapisanie awarii WAN do bazy
			$current_nr_awarii_wan = $_REQUEST['numerzgloszenia-'.$current_zgl_nr];
			if ((($current_kategoria=='2') || ($current_kategoria=='6')) && ($current_podkategoria=='0') && ($current_status=='3A') && ($current_nr_awarii_wan!='')) {
			
				$current_up_nazwa = $_REQUEST['up_nazwa-'.$current_zgl_nr];
				$current_up_wanport = $_REQUEST['up_wanport-'.$current_zgl_nr];
				$current_up_ip = $_REQUEST['up_ip-'.$current_zgl_nr];
				//$current_nr_awarii_wan = $_REQUEST['numerzgloszenia-'.$current_zgl_nr];
				
				$sql_t = "INSERT INTO $dbname.serwis_awarie VALUES ('', '$current_up_nazwa','$current_up_wanport','$newStartDate','','$current_nr_awarii_wan','$current_up_ip','$currentuser','','0',$es_filia)";
				//echo "<br />$sql_t";
				
				$result_t = mysql_query($sql_t, $conn) or die($k_b);
				
			// zaktualizuj poledodatkowe1 (awaria wan) w zgłoszeniu 
				$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_poledodatkowe1='$current_nr_awarii_wan', zgl_data_zmiany_statusu='$_zgl_DZS' WHERE ((belongs_to='$es_filia') and (zgl_nr='$current_zgl_nr')) LIMIT 1";
				//echo "$sql<br />";
				$result = mysql_query($sql, $conn_hd) or die($k_b);
			// koniec
			}
		
			// zamknięcie awarii WAN
	
			if ((($current_kategoria=='2') || ($current_kategoria=='6')) && ($current_podkategoria=='0') && ($current_status=='9')) {
			
			//	$sql_t="UPDATE $dbname.serwis_awarie SET awaria_datazamkniecia  = '$newStartDate', awaria_osobazamykajaca = '$currentuser', awaria_status  = '1' WHERE ((awaria_nrzgloszenia='$_REQUEST[numerzgloszenia1]') and (belongs_to=$es_filia)) LIMIT 1";
			//	echo "<br />$sql_t";
			//	$result_t = mysql_query($sql_t, $conn) or die($k_b);
			}


				$r3 = mysql_query("SELECT zgl_szcz_id FROM $dbname_hd.hd_zgloszenie_szcz WHERE ((zgl_szcz_unikalny_numer='$unique_nr') and (belongs_to='$es_filia') and (zgl_szcz_osoba_wykonujaca_krok='$currentuser')) ORDER BY zgl_szcz_id DESC LIMIT 1", $conn_hd) or die($k_b);
				//echo "<br />SELECT zgl_szcz_id FROM $dbname_hd.hd_zgloszenie_szcz WHERE ((zgl_szcz_unikalny_numer='$unique_nr') and (belongs_to='$es_filia') and (zgl_szcz_osoba_wykonujaca_krok='$currentuser')) ORDER BY zgl_szcz_id DESC LIMIT 1";
				
				list($last_nr_szcz)=mysql_fetch_array($r3);
		
			// zaktualizuj status w zgłoszeniu 
			$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_status='$current_status', zgl_data_zmiany_statusu='$_zgl_DZS' , zgl_osoba_przypisana='$currentuser' WHERE ((zgl_widoczne=1) and (belongs_to='$es_filia') and (zgl_nr='$current_zgl_nr')) LIMIT 1";
			$result = mysql_query($sql, $conn_hd) or die($k_b);
			
			// zaktualizuj czas wykonywania w zgłoszeniu
			//echo "<br />SELECT zgl_razem_km FROM $dbname_hd.hd_zgloszenie WHERE ((zgl_id='$_REQUEST[id]') and (belongs_to='$es_filia') and (zgl_widoczne=1)) LIMIT 1";
		
			$r3 = mysql_query("SELECT zgl_razem_czas FROM $dbname_hd.hd_zgloszenie WHERE ((zgl_id='$current_zgl_id') and (belongs_to='$es_filia') and (zgl_widoczne=1)) LIMIT 1", $conn_hd) or die($k_b);
			list($razem_czas)=mysql_fetch_array($r3);
			$razem_czas += $d_cw;
			
			$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_razem_czas=$razem_czas WHERE ((zgl_widoczne=1) and (belongs_to='$es_filia') and (zgl_id='$current_zgl_id')) LIMIT 1";
			//echo "<br />$sql";
			$result = mysql_query($sql, $conn_hd) or die($k_b);
		
			// zaktualizuj km w zgłoszeniu
			//echo "<br />SELECT zgl_razem_km FROM $dbname_hd.hd_zgloszenie WHERE ((zgl_id='$_REQUEST[id]') and (belongs_to='$es_filia') and (zgl_widoczne=1)) LIMIT 1";
		
			$r3 = mysql_query("SELECT zgl_razem_km FROM $dbname_hd.hd_zgloszenie WHERE ((zgl_id='$current_zgl_id') and (belongs_to='$es_filia') and (zgl_widoczne=1)) LIMIT 1", $conn_hd) or die($k_b);
			list($razem_km)=mysql_fetch_array($r3);
			
			if (($current_kilometry!=0) && ($current_km!='')) $razem_km += $current_kilometry;
			
			$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_razem_km=$razem_km WHERE ((zgl_widoczne=1) and (belongs_to='$es_filia') and (zgl_id='$current_zgl_id')) LIMIT 1";
			//echo "<br />$sql";
			$result = mysql_query($sql, $conn_hd) or die($k_b);

			// jeżeli był wyjazd (START)
			if ($bylwyjazd==1) {
					
				if ($_REQUEST[obsl_powiazana_z_wyjazdem]=='S') {
					$current_trasa = 'wyjazd samochodem służbowym';
				}
		
				$sql = "INSERT INTO $dbname_hd.hd_zgloszenie_wyjazd VALUES ('','$zgl_seryjme_unique_nr','$current_data','$current_trasa','$current_kilometry','$currentuser','$_REQUEST[obsl_powiazana_z_wyjazdem]',1,$current_czas_przejazdu, $es_filia)";
			//	echo ">>>> $sql";
				
				//echo "<br />$sql";
				$result = mysql_query($sql, $conn_hd) or die($k_b);
			
				$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_wymagany_wyjazd=0 WHERE ((zgl_widoczne=1) and (belongs_to='$es_filia') and (zgl_id='$current_zgl_id')) LIMIT 1";
				$result = mysql_query($sql, $conn_hd) or die($k_b);			

				
			}
			// jeżeli był wyjazd (STOP)
			// koniec

/*		
			echo "Nr zgłoszenia : $current_zgl_nr<br />";
			echo "Komórka : $current_komorka<br />";
			echo "Temat : $current_temat<br />";
			echo "Data / godzina : $current_data / $current_godzina	<br />";
			echo "Status : $current_status <br />";
			echo "Zasadne : $current_zz <br />";
			echo "Czas poświęcony : $current_czas<br />";
			echo "Km przejechane : $current_km <br />";
			echo "Wykonane czynności : $current_wykonane_czynnosci <br />";
			oddziel();
*/				

		}

	?><script>
		if (opener) opener.location.reload(true); 
		self.close();
		newWindow(600,50,'hd_o_zgloszenia_wylicz_czasy.php?nr=0&nrs=<?php echo $_REQUEST[numbers]; ?>&nrs_cnt=<?php echo $_REQUEST[cnt_zgl]; ?>');
	</script><?php
	
		//print_r($_POST);
	} 
?><script>HideWaitingMessage('Saving1');</script><?php 
} else {

//echo "<input type=button value=test onclick=\"newWindow(600,50,'hd_o_zgloszenia_wylicz_czasy.php?nr=0&nrs=$_REQUEST[nr]&nrs_cnt=$_REQUEST[cnt]');\" />";

	session_register('seryjna_obsluga_zgloszenia_zapisano');	
	$_SESSION[seryjna_obsluga_zgloszenia_zapisano]='nie';

// weryfikacja aktywności dostępów czasowych dla wszystkich pracowników
	$aktualna_data = Date('Y-m-d H:i:s');
	$sql_dc = "UPDATE $dbname_hd.hd_dostep_czasowy SET dc_dostep_active=0 WHERE ((dc_dostep_active_to<'$aktualna_data') and (dc_dostep_active=1) and (belongs_to=$es_filia))";
	$result_dc = mysql_query($sql_dc, $conn_hd) or die($k_b);
// koniec weryfikacji aktywności dostępów czasowych

	if ($_GET[cnt]!=1) {
		pageheader("Seryjna obsługa <b>$_GET[cnt]</b> zgłoszeń",0);
	} else {
		pageheader("Obsługa zgłoszenia",0);
	}

?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
	
echo "<div id=content>";
	
	echo "<form name=seryjna_obsluga_zgloszen id=seryjna_obsluga_zgloszen method=POST action=$PHP_SELF onSubmit=\"return SeryjnaObslugaCheckOnSubmit();\" >";
	
	startbuttonsarea("right");
		$wartiant3 = 2;
		include_once('systemdate.php');
	endbuttonsarea();
	
	echo "<div id=panel1>";
	echo "<fieldset><legend><b>&nbsp;Sumaryczne wartości przy obsłudze zgłoszeń&nbsp;</b></legend>";
	nowalinia();
	echo "Obsługa zgłoszeń powiązana z wyjazdem samochodem: ";
	
			echo "<select name=obsl_powiazana_z_wyjazdem id=obsl_powiazana_z_wyjazdem onChange=\"if (this.value=='P') { ObslugaPowZWyjazdem(true); PrepareRows(0); } else { PrepareRows(0); ObslugaPowZWyjazdem(false); } \">";
			echo "<option value='' SELECTED>wybierz z listy</option>";
			echo "<option value='P'>prywatnym</option>";
			echo "<option value='S'>służbowym</option>";
			echo "</select>";
			
	//echo "&nbsp;&nbsp;<input class=border0 type=radio name=obsl_powiazana_z_wyjazdem id=obsl_powiazana_z_wyjazdem_P value='P' style='border:0;' onClick=\"ObslugaPowZWyjazdem(true);\" ><a href=# class=normalfont onClick=\"document.getElementById('obsl_powiazana_z_wyjazdem_P').checked=true; ObslugaPowZWyjazdem(true); PrepareRows(0); \">&nbsp;samochodem prywatnym</a>";
	
	//	echo "<br />&nbsp;&nbsp;<input class=border0 type=radio name=obsl_powiazana_z_wyjazdem id=obsl_powiazana_z_wyjazdem_S value='S' style='border:0;' onClick=\"ObslugaPowZWyjazdem(false);\" ><a href=# class=normalfont onClick=\"document.getElementById('obsl_powiazana_z_wyjazdem_S').checked=true; ObslugaPowZWyjazdem(false); \">&nbsp;samochodem służbowym</a>";	

	/*
	echo "<br />&nbsp;&nbsp;<input type=checkbox id=obsl_powiazana_z_wyjazdem style='border:0;' checked onClick=\"ObslugaPowZWyjazdem(this.checked);\" ><a onClick=\"if (document.getElementById('obsl_powiazana_z_wyjazdem').checked) { document.getElementById('obsl_powiazana_z_wyjazdem').checked=false; ObslugaPowZWyjazdem(false); } else { document.getElementById('obsl_powiazana_z_wyjazdem').checked=true; ObslugaPowZWyjazdem(true); } \">&nbsp;Obsługa zgłoszeń powiązana z wyjazdem</a>";
	echo "<br />&nbsp;&nbsp;<input type=checkbox id=obsl_powiazana_z_wyjazdem style='border:0;' checked onClick=\"ObslugaPowZWyjazdem(this.checked);\" ><a onClick=\"if (document.getElementById('obsl_powiazana_z_wyjazdem').checked) { document.getElementById('obsl_powiazana_z_wyjazdem').checked=false; ObslugaPowZWyjazdem(false); } else { document.getElementById('obsl_powiazana_z_wyjazdem').checked=true; ObslugaPowZWyjazdem(true); } \">&nbsp;Obsługa zgłoszeń powiązana z wyjazdem</a>";
	echo "<br />&nbsp;&nbsp;<input type=checkbox id=obsl_powiazana_z_wyjazdem style='border:0;' checked onClick=\"ObslugaPowZWyjazdem(this.checked);\" ><a onClick=\"if (document.getElementById('obsl_powiazana_z_wyjazdem').checked) { document.getElementById('obsl_powiazana_z_wyjazdem').checked=false; ObslugaPowZWyjazdem(false); } else { document.getElementById('obsl_powiazana_z_wyjazdem').checked=true; ObslugaPowZWyjazdem(true); } \">&nbsp;Obsługa zgłoszeń powiązana z wyjazdem</a>";
	*/

	echo "<br /><br />";

		starttable();
		echo "<tr>";
		echo "<td style='text-align:right;' width=200>";
		echo "Łącznie czas poświęcony na wykonanie";
		echo "</td><td>";
		echo "<input style=text-align:right type=text id=czas_wykonywania_h name=czas_wykonywania_h value='0' maxlength=2 size=2 onKeyPress=\"return filterInput(1, event, false); \" onBlur=\"if (this.value=='') this.value=0; if (this.value!='0') { if (confirm('Czy rozbić podany czas na poszczególne zgłoszenia ?')) { UpdateCzas(document.getElementById('czas_wykonywania_h'),document.getElementById('czas_wykonywania_m'),''); } }\" /> godzin";
		echo "&nbsp;";
		echo "<input style=text-align:right type=text id=czas_wykonywania_m name=czas_wykonywania_m value='0' maxlength=2 size=2 onKeyPress=\"return filterInput(1, event, false); \" onBlur=\"if (this.value=='') this.value=0; if (this.value!='0') { if (confirm('Czy rozbić podany czas na poszczególne zgłoszenia ?')) { UpdateCzas(document.getElementById('czas_wykonywania_h'),document.getElementById('czas_wykonywania_m'),''); } }\" /> minut</td>";

		//echo "<td><input class=buttons type=button value='Podziel proporcjonalnie' onClick=\"UpdateCzas(document.getElementById('czas_wykonywania_h'),document.getElementById('czas_wykonywania_m'),'button');\" ></td>";
		echo "<td class=left>";
		echo "| W przeliczeniu na minuty: <input tabindex=-1 type=text size=2 readonly name=razem_minut id=razem_minut value='0' style='background-color:transparent;text-align:right;border:0;text-weight:bold;'> minut";
		echo "</td>";

		echo "</tr>";

		echo "<tr id=ObslugaPowiazanaZWyjazdem style='display:none'>";

		echo "<td style='text-align:right;'>";
		echo "Łącznie przejechane km";
		echo "</td><td>";
		echo "<input style=text-align:right type=text id=ilosc_km name=ilosc_km value='' maxlength=3 size=2 onKeyPress=\"return filterInput(1, event, false); \" onBlur=\"UpdateKM(document.getElementById('ilosc_km'),''); GenerujTraseWyjazdowa_NOWA(); \" /> km</td>";
		//echo "<td><input class=buttons type=button value='Podziel proporcjonalnie' onClick=\"UpdateKM(document.getElementById('ilosc_km'),'button');\"></td>";
		echo "<td></td>";
		echo "</tr>";

		echo "<tr id=CzasTrwaniaWyjazdu style='display:none;'>";
		td("140;r;<b>Łączny czas przejazdu</b>");
			td_(";;;");
				echo "<input style=text-align:right type=text id=czas_przejazdu_h name=czas_przejazdu_h value='0' maxlength=2 size=2 onKeyPress=\"return filterInput(1, event, false); \" onKeyUp=\"if ((event.keyCode==13) && (this.value!='')) document.getElementById('czas_przejazdu_m').focus(); \" onFocus=\"this.select(); return false;\" onBlur=\"if (this.value=='') this.value=0; UpdateCzas2(document.getElementById('czas_przejazdu_h'),document.getElementById('czas_przejazdu_m'),''); if (this.value>8) if (confirm('Wpisano więcej niż 8 godzin na przejazd. Potwierdzasz poprawność ?')) { return true; } else { this.value='0'; UpdateCzas2(document.getElementById('czas_przejazdu_h'),document.getElementById('czas_przejazdu_m'),''); return false; } \" /> godzin";
				echo "&nbsp;";
				echo "<input style=text-align:right type=text id=czas_przejazdu_m name=czas_przejazdu_m value='0' maxlength=2 size=2 onKeyPress=\"return filterInput(1, event, false); \" onKeyUp=\"if ((event.keyCode==13) && (this.value!='')) { document.getElementById('submit').focus(); }\" onFocus=\"this.select(); return false;\" onBlur=\"if (this.value=='') this.value=0; UpdateCzas2(document.getElementById('czas_przejazdu_h'),document.getElementById('czas_przejazdu_m'),'');\" /> minut";
			_td();
		echo "</tr>";

	
		endtable();

	echo "</fieldset>";
	echo "</div>";

	//nowalinia();	
	$sql = "SELECT * FROM $dbname_hd.hd_zgloszenie WHERE (zgl_nr IN (".$_GET[nr]."))";
	$result = mysql_query($sql, $conn_hd) or die($k_b);

	if ($_SESSION[ustawiono_kolejnosc]=='NIE') {
		// ustal kolejność wyjściową 
		$auto_numeracja = 1;
		while ($newArray = mysql_fetch_array($result)) {
			$temp_id  			= $newArray['zgl_id'];
			
			$sql_autonr = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_poledodatkowe3='$auto_numeracja' WHERE ((belongs_to='$es_filia') and (zgl_id='$temp_id')) LIMIT 1";
			$result_autonr = mysql_query($sql_autonr, $conn_hd) or die($k_b);
			$auto_numeracja++;
		}
		$_SESSION[ustawiono_kolejnosc]='TAK';
	}

	$sql = "SELECT * FROM $dbname_hd.hd_zgloszenie WHERE (zgl_nr IN (".$_GET[nr]."))";
	$result = mysql_query($sql, $conn_hd) or die($k_b);
	//starttable();
	nowalinia();
	echo "<table id=TabelaZeZgloszeniami cellspacing=1 align=center class=maxwidth>";
	echo "<thead>";
	//th_("10;c;Nr<br />zgłoszenia|50;c;Kolejność|;l;Komórka<br />Temat|;c;Data|;c;Godzina|;l;Aktualny status<br />Nowy status|;c;Czas<br />(min)|;c;km",$es_prawa);

	echo "<th class=center>";
	echo "<span id=th_kolumna_z_nr>Numer<br />zgłoszenia</span><span id=th_kolumna_z_kolejnoscia style='display:none'>Kolejność<br />wyjazdu</span></th>";

	echo "<th class=left>Komórka<br />Temat</th><th class=center>Data<br /><u>zakończenia</u> kroku</th><th class=center>Godzina<br /><u>zakończenia</u> kroku</th><th class=left>Aktualny status<br />Nowy status</th><th class=center>Czas wykonywania<br />(min)</th><th id=kolumna_z_km class=center style=display:none>Przejechane<br />km</th><th id=kolumna_z_czas_wyjazdu class=center style=display:none>Czas trwania przejazdu</th>";
	echo "</thead>";
	echo "<tbody>";

	$t = 1;
		$i = 0;
		$j = $page*$rowpersite-$rowpersite+1;
		while ($newArray = mysql_fetch_array($result)) {
			$temp_id  			= $newArray['zgl_id'];
			$temp_nr			= $newArray['zgl_nr'];
			$temp_data			= $newArray['zgl_data'];
			$temp_godzina		= $newArray['zgl_godzina'];
			$temp_komorka		= $newArray['zgl_komorka'];
			$temp_osoba			= $newArray['zgl_osoba'];
			$temp_telefon		= $newArray['zgl_telefon'];
			$temp_temat			= $newArray['zgl_temat'];	
			$temp_tresc			= $newArray['zgl_tresc'];
			$temp_kategoria		= $newArray['zgl_kategoria'];
			$temp_podkategoria	= $newArray['zgl_podkategoria'];
			$temp_priorytet		= $newArray['zgl_priorytet'];
			$temp_status 		= $newArray['zgl_status'];
			$temp_data_zak		= $newArray['zgl_data_zakonczenia'];
			$temp_czas			= $newArray['zgl_razem_czas'];
			$temp_km			= $newArray['zgl_razem_km'];
			$temp_or			= $newArray['zgl_osoba_rejestrujaca'];
			$temp_zz			= $newArray['zgl_zasadne'];
			$temp_nrawarii		= $newArray['zgl_poledodatkowe1'];
			$temp_zgl_seryjne	= $newArray['zgl_poledodatkowe2'];
			$temp_order			= $newArray['zgl_poledodatkowe3'];
			$temp_czy_rozwiazany= $newArray['zgl_czy_rozwiazany_problem'];
			
			$temp_naprawa_id	= $newArray['zgl_naprawa_id'];
			
			if ($KolorujWgStatusow==1) {
				switch ($temp_kategoria) {
					case 6: $kolorgrupy='#F73B3B'; tbl_tr_color_dblClick($i, $kolorgrupy); break;
					case 2:	if ($temp_priorytet==2) { $kolorgrupy='#FF7F2A'; tbl_tr_color($i, $kolorgrupy); break; }
							if ($temp_priorytet==4) { $kolorgrupy='#F73B3B'; tbl_tr_color($i, $kolorgrupy); break; }				
					case 3:	if ($temp_priorytet==3) { $kolorgrupy='#FFAA7F'; tbl_tr_color($i, $kolorgrupy); break; }
					default: tbl_tr_highlight($i);
							$kolorgrupy='';
				}
			} else {
				tbl_tr_highlight($i);
				$kolorgrupy='';
			}
			
			$j++;

			echo "<input type=hidden id=nr_tr-$i value='$i'>";
			
			echo "<input type=hidden name=id_zgl-$i id=id_zgl-$i value='$temp_id'>";
			
			echo "<input type=hidden name=kat_zgl-$temp_nr id=kat_zgl-$temp_nr value='$temp_kategoria'>";
			echo "<input type=hidden name=podkat_zgl-$temp_nr id=podkat_zgl-$temp_nr value='$temp_podkategoria'>";
			
			echo "<input type=hidden name=nr_zgl-$i id=nr_zgl-$i value='$temp_nr'>";
			echo "<td style='text-align:center;'>";
			echo "<span "; 
			if (($czy_wyroznic_zgloszenia_seryjne==1) && ($temp_zgl_seryjne!='')) echo " title=' To jest zgłoszenie seryjne '";
			echo "id=numer-$i>";
			
			echo "<a class=normalfont title='Podgląd zgłoszenia o nr $temp_nr' href=# onclick=\"newWindow_r(800,600,'hd_o_zgloszenia.php?action=podglad&id=$temp_nr&nr=$temp_nr'); return false;\" >";
			echo "$temp_nr";
			echo "</a>";
			echo " "; 
			
			if (($czy_wyroznic_zgloszenia_seryjne==1) && ($temp_zgl_seryjne!='')) echo "[s]";
			echo "</span>";
			echo "<b><a href=# class=normalfont id=dec-$i onClick=\"Decrease(document.getElementById('order-$i')); return false; \" style='display:none;'>&nbsp;-&nbsp;</a> </b>";
			echo "<input name=order-$i id=order-$i type=text maxlength=2 size=1 style='text-align:center; background-color:yellow; display:none;' value='$t' onKeyPress=\"return filterInput(1, event, false, ''); \">";
			echo " <a href=# class=normalfont id=inc-$i onClick=\"Increase(document.getElementById('order-$i')); return false;\" style='display:none'>&nbsp;+&nbsp;</a>";
			
			$temp_n_s = '';
			if ($temp_naprawa_id>0) {
				$sql88="SELECT naprawa_sprzet_zastepczy_id, naprawa_status  FROM $dbname.serwis_naprawa WHERE (naprawa_id=$temp_naprawa_id) LIMIT 1";
				$result88 = mysql_query($sql88, $conn) or die($k_b);
				list($temp_ss_id, $temp_n_s)=mysql_fetch_array($result88);

				$opis_statusu = '';
				switch ($temp_n_s) {
					case '-1' : $opis_statusu='pobrany od klienta'; break;
					case '0' : $opis_statusu='naprawa we własnym zakresie'; break;
					case '1' : $opis_statusu='naprawa w serwisie zewnętrznym'; break;
					case '2' : $opis_statusu='naprawa na rynku lokalnym'; break;
					case '3' : $opis_statusu='naprawiony - na stanie'; break;
					case '5' : $opis_statusu='zwrócony do klienta'; break;
					case '7' : $opis_statusu='wycofany z serwisu'; break;
					case '8' : $opis_statusu='wycofany z serwisu - oddany do klienta'; break;
				}
				
				echo "<br />";
				echo "<a href=# class=normalfont title='Zgłoszenie powiązane z naprawą. Status naprawy: ".$opis_statusu."'><img class=imgoption src=img/naprawa_unknown.gif border=0 width=16 height=16></a>";

				if ($temp_ss_id>0) {
					echo "<img class=imgoption title='Zgłoszenie powiazane z przekazaniem sprzętu serwisowego' src=img/service.gif border=0 width=16 height=16></a>";
				}	
			}
			echo "</td>";
			echo "<td>";
			//echo SkrocTekst($temp_komorka,35);
			
			if ($temp_zgl_seryjne!='') {
				$temp_komorka = toUpper($temp_komorka);
			}
		
			echo $temp_komorka;
			echo "<input type=hidden tabindex=-1 name=komorka-$temp_nr id=komorka-$temp_nr value='$temp_komorka'>";
			echo "<br /><i>";
			
			echo nl2br(wordwrap($temp_temat, 60, "<br />"));
			
			echo "</i>";
			
			echo "<input type=hidden tabindex=-1 name=temat_zgl-$temp_nr id=temat_zgl-$temp_nr value='$temp_temat'>";
			echo "</td>";
			echo "<td class=center>";
				$dddd = Date('Y-m-d');
				//echo SubstractWorkingDays(2,$dddd);
				echo "<select name=oz_data-$temp_nr id=oz_data-$temp_nr>";				
				echo "<option value='$dddd' SELECTED>$dddd</option>\n";
				
				
				if ((date("w",strtotime($dddd))!=1) || ($idw_dla_zbh_testowa))  {
					for ($cd=1; $cd<($idw_dla_zbh+1); $cd++) {
						echo "<option value='".SubstractDays($cd,$dddd)."'>".SubstractDays($cd,$dddd)."&nbsp;";
						if ($idw_dla_zbh_testowa) echo "[dla testów]";
						echo "</option>\n";
					}
				}
				
				//echo "<option value='".SubstractWorkingDays(1,$dddd)."'>".SubstractWorkingDays(1,$dddd)."&nbsp;</option>\n";
				//echo "<option value='".SubstractWorkingDays(2,$dddd)."'>".SubstractWorkingDays(2,$dddd)."&nbsp;</option>\n";
				
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
			echo "</td>";
			
			echo "<td class=center>";
				$tttt = Date('H:i');
				echo "<input class=wymagane type=text name=oz_godzina-$temp_nr id=oz_godzina-$temp_nr value='' maxlength=5 size=2 onBlur=\"CheckTime_req(this.value,this); \" onKeyPress=\"return filterInput(1, event, false, ':'); \" onKeyUp=\"DopiszDwukropek('oz_godzina-$temp_nr');\" onDblClick=\"this.value=document.getElementById('oz_godzina_dla_wszystkich').value;\" />";	
			echo "</td>";
			
			echo "<td>";
				$sql1 = "SELECT hd_status_opis FROM $dbname_hd.hd_status WHERE ((hd_status_wlaczona=1) and (hd_status_nr='$temp_status')) LIMIT 1";
				$rsd = mysql_query($sql1,$conn_hd);
				list($akt_status) = mysql_fetch_array($rsd);
				
				echo "&nbsp;$akt_status<br />";
				
				switch ($temp_status) {
					case "1" : 	if ($temp_kategoria=='2') { $ListaStatusow = "'3'"; $default_status = '3'; }
								if ($temp_kategoria=='3') { $ListaStatusow = "'3','3A','3B','4','5','6','7','9'"; $default_status = '3'; }
								if ($temp_kategoria=='4') { $ListaStatusow = "'3','3A','3B','4','5','6','7','9'"; $default_status = '3'; }
								if ($temp_kategoria=='5') { $ListaStatusow = "'3','3A','3B','4','5','6','7','9'"; $default_status = '3'; }
								if ($temp_kategoria=='6') { $ListaStatusow = "'3','9'"; $default_status = '3'; }
								if ($temp_kategoria=='7') { $ListaStatusow = "'3','7','9'"; $default_status = '9'; }
								break;
					case "2" : 	if ($temp_kategoria=='2') { $ListaStatusow = "'3'"; $default_status = '3'; }
								if ($temp_kategoria=='3') { $ListaStatusow = "'3','3A','3B','4','5','6','7','9'"; $default_status = '3'; }
								if ($temp_kategoria=='4') { $ListaStatusow = "'3','3A','3B','4','5','6','7','9'"; $default_status = '3'; }
								if ($temp_kategoria=='5') { $ListaStatusow = "'3','3A','3B','4','5','6','7','9'"; $default_status = '3'; }
								if ($temp_kategoria=='6') { $ListaStatusow = "'3','9'"; $default_status = '3'; }
								if ($temp_kategoria=='7') { $ListaStatusow = "'3','7','9'"; $default_status = '9'; }
								break;
					case "3" : 	if ($temp_kategoria=='2') { $ListaStatusow = "'3A','3B','4','5','6','7'"; $default_status = '3B'; }
								if ($temp_kategoria=='3') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3B'; }
								if ($temp_kategoria=='4') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3B'; }
								if ($temp_kategoria=='5') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3B'; }
								if ($temp_kategoria=='6') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3B'; }
								if ($temp_kategoria=='7') { $ListaStatusow = "'3','7','9'"; $default_status = '9'; }
								break;
					case "3A" :	if ($temp_kategoria=='2') { $ListaStatusow = "'3A','3B','4','5','6','7'"; $default_status = '3A'; }
								if ($temp_kategoria=='3') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3A'; }
								if ($temp_kategoria=='4') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3A'; }
								if ($temp_kategoria=='5') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3A'; }
								if ($temp_kategoria=='6') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3A'; }
								break;
					case "3B" :	if ($temp_kategoria=='2') { $ListaStatusow = "'3A','3B','4','5','6','7'"; $default_status = '3B'; }
								if ($temp_kategoria=='3') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3B'; }
								if ($temp_kategoria=='4') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3B'; }
								if ($temp_kategoria=='5') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3B'; }
								if ($temp_kategoria=='6') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3B'; }
								break;
					case "4" :	if ($temp_kategoria=='2') { $ListaStatusow = "'3A','3B','4','5','6','7'"; $default_status = '3B'; }
								if ($temp_kategoria=='3') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3B'; }
								if ($temp_kategoria=='4') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3B'; }
								if ($temp_kategoria=='5') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3B'; }
								if ($temp_kategoria=='6') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3B'; }
								break;
					case "5" :	if ($temp_kategoria=='2') { $ListaStatusow = "'3A','3B','4','5','6','7'"; $default_status = '3B'; }
								if ($temp_kategoria=='3') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3B'; }
								if ($temp_kategoria=='4') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3B'; }
								if ($temp_kategoria=='5') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3B'; }
								if ($temp_kategoria=='6') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3B'; }
								break;
					case "6" :	if ($temp_kategoria=='2') { $ListaStatusow = "'3A','3B','4','5','6','7'"; $default_status = '9'; }
								if ($temp_kategoria=='3') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '9'; }
								if ($temp_kategoria=='4') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '9'; }
								if ($temp_kategoria=='5') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '9'; }
								if ($temp_kategoria=='6') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '9'; }
								break;
					case "7" :	if ($temp_kategoria=='2') { $ListaStatusow = "'3A','3B','4','5','6','7'"; $default_status = '7'; }
								if ($temp_kategoria=='3') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '7'; }
								if ($temp_kategoria=='4') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '7'; }
								if ($temp_kategoria=='5') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '7'; }
								if ($temp_kategoria=='6') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '7'; }
								if ($temp_kategoria=='7') { $ListaStatusow = "'7','9'"; $default_status = '9'; }
								break;
					
				}
				
				$info_o_naprawie = '';
				if ($temp_naprawa_id>0) {
					if ($temp_n_s >= -1) {
						switch ($temp_n_s) {
							case '-1' : $ListaStatusow = str_replace("'3A'","''",$ListaStatusow); 
										$ListaStatusow = str_replace("'6'","''",$ListaStatusow); 
										$ListaStatusow = str_replace("'9'","''",$ListaStatusow); 
										$info_o_naprawie = '<font color=red>Status naprawy: <b>pobrany od klienta</b>. Nie ma możliwości ustawienia statusu: <b>w serwisie zewnętrznym, do oddania, zamknięte</b></font>';
										break;
							case '0' :  $ListaStatusow = str_replace("'9'","''",$ListaStatusow); 
										$info_o_naprawie = '<font color=red>Status naprawy: <b>naprawa we własnym zakresie</b>. Nie ma możliwości ustawienia statusu: <b>zamknięte</b></font>';
										break;
							case '1' :  $ListaStatusow = str_replace("'9'","''",$ListaStatusow); 
										$info_o_naprawie = '<font color=red>Status naprawy: <b>naprawa w serwisie zewnętrznym</b>. Nie ma możliwości ustawienia statusu: <b>zamknięte</b></font>';
										break;
							case '2' :  $ListaStatusow = str_replace("'9'","''",$ListaStatusow); 
										$info_o_naprawie = '<font color=red>Status naprawy: <b>naprawa na rynku lokalnym</b>. Nie ma możliwości ustawienia statusu: <b>zamknięte</b></font>';
										break;
							case '3' :  $ListaStatusow = str_replace("'9'","''",$ListaStatusow); 
										$info_o_naprawie = '<font color=red>Status naprawy: <b>naprawiony - na stanie</b>. Nie ma możliwości ustawienia statusu: <b>zamknięte</b></font>';
										break;
							case '5' :  $info_o_naprawie = '<font color=green>Status naprawy: <b>zwrócony do klienta</b></font>';
										break;
							case '7' :  $ListaStatusow = str_replace("'9'","''",$ListaStatusow); 
										$info_o_naprawie = '<font color=red>Status naprawy: <b>wycofany z serwisu</b>. Nie ma możliwości ustawienia statusu: <b>zamknięte</b></font>';
										break;
							case '8' :  $info_o_naprawie = '<font color=green>Status naprawy: <b>zwrócony do klienta</b></font>';
										break;
						}
					}
				}

				$sql1 = "SELECT hd_status_nr,hd_status_opis FROM $dbname_hd.hd_status WHERE ((hd_status_wlaczona=1) and (hd_status_nr IN (".$ListaStatusow."))) ORDER BY hd_status_id";
				
				$rsd = mysql_query($sql1,$conn_hd);
				$ile = mysql_num_rows($rsd);
				if ($ile>0) {
						echo "<select id=SelectZmienStatus-$temp_nr name=SelectZmienStatus-$temp_nr onChange=\"PokazZasadnoscZgloszenia(this.value,$temp_nr,$temp_kategoria,$temp_podkategoria); \">"; //onChange=\"SelectZmienStatusOnBlur();\" />";
						while (list($temp_num,$temp_opis) = mysql_fetch_array($rsd)) {
								echo "<option value='$temp_num' ";
								echo ">$temp_opis</option>\n"; 
							}
						echo "</select>";
				}

			echo "&nbsp;";
			
			if (($temp_czy_rozwiazany==0) && (($temp_kategoria=='2') || ($temp_kategoria=='6'))) {
				echo " | Problem rozwiązany: ";
				echo "<select id=problem_rozwiazany-$temp_nr name=problem_rozwiazany-$temp_nr />";
				echo "<option value=0>NIE</option>\n"; 
				echo "<option value=1>TAK</option>\n"; 
				echo "</select>";
			} else {
				if (($temp_kategoria=='2') || ($temp_kategoria=='6')) echo "Problem rozwiązany: <b>TAK</b>";
				echo "<input type=hidden id=problem_rozwiazany-$temp_nr name=problem_rozwiazany-$temp_nr value='1'/>";
			}
			
			//echo "<img title=' Wykonane czynności - pokaż ' class=imgoption type=image src=img/show_more.gif name=showmore-$i id=showmore-$i onClick=\"document.getElementById('wyk_czynnosci-$temp_nr').style.display=''; document.getElementById('showmore-$i').style.display='none'; document.getElementById('hidemore-$i').style.display=''; return false; \" >";
			
			//echo "<img title=' Wykonane czynności - ukryj ' class=imgoption type=image src=img/hide_more.gif name=hidemore-$i id=hidemore-$i style=display:none onClick=\"document.getElementById('wyk_czynnosci-$temp_nr').style.display='none'; document.getElementById('showmore-$i').style.display=''; document.getElementById('hidemore-$i').style.display='none'; return false; \" >";
	
			if ($info_o_naprawie!='') {
				echo "<br />".$info_o_naprawie."";
				if (($temp_n_s!='5') && ($temp_n_s!=8)) {
					echo "&nbsp;|&nbsp;<input type=button class=buttons value='Obsługa zgłoszenia' onClick=\"newWindow_r(800,600,'hd_o_zgloszenia.php?action=obsluga&id=$temp_nr&nr=$temp_nr&zgl_s=$zgl_seryjne_mark'); return false;\" >";
				}
			}
					echo "<span id=AwariaWAN-$temp_nr style='display:none;'>";
				
					$w1 = mysql_query("SELECT zgl_komorka FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$es_filia) and (zgl_nr=$temp_nr)) LIMIT 1", $conn_hd) or die($k_b);
					list($zgl_komorka_nazwa)=mysql_fetch_array($w1);
				
					$SzukajUP = substr($zgl_komorka_nazwa,strpos($zgl_komorka_nazwa,' ')+1,strlen($zgl_komorka_nazwa));
					$r44 = mysql_query("SELECT up_id, up_nrwanportu,up_adres,up_telefon,up_nazwa, up_id FROM $dbname.serwis_komorki WHERE ((belongs_to=$es_filia) and (up_active=1) and (up_nazwa LIKE '%$SzukajUP%')) LIMIT 1", $conn) or die($k_b);
					
					list($komorka_id,$komorka_wanport,$komorka_adres,$komorka_telefon,$komorka_nazwa,$komorka_ip)=mysql_fetch_array($r44);
				
					echo "<fieldset onClick=\"\"><legend>Informacje potrzebne do zgłoszenia awarii WAN</legend>";
					nowalinia();
					echo "&nbsp;Telefon do TPSA : <b><font color=red>$nr_telefonu_do_tpsa</font></b><br />";
					echo "&nbsp;Nazwa komórki : <b><font color=black>$komorka_nazwa</font></b><br />";
					
					//$sql_t = "INSERT INTO $dbname.serwis_awarie VALUES ('', '$_REQUEST[up_nazwa1]','$_REQUEST[up_wanport]','$_REQUEST[hddz] $_REQUEST[hdgz]','','$_REQUEST[numerzgloszenia]','$_REQUEST[up_ip1]','$currentuser','','0',$es_filia)";
							
					echo "<input type=hidden name=up_nazwa-$temp_nr id=up_nazwa-$temp_nr value='$komorka_nazwa'>";
					echo "<input type=hidden name=up_wanport-$temp_nr id=up_wanport-$temp_nr value='$komorka_wanport'>";
					echo "<input type=hidden name=up_ip-$temp_nr id=up_ip-$temp_nr value='$komorka_ip'>";

					echo "&nbsp;Nr WAN-portu : <font color=black><b>$komorka_wanport</b></font><br />";
					echo "&nbsp;Lokalizacja : <font color=black><b>$komorka_adres</b></font><br />";
					echo "&nbsp;Telefon : <font color=black><b>$komorka_telefon</b></font><br />";		
					
					echo "&nbsp;Numer zgłoszenia w Orange : <input type=text class=wymagane name=numerzgloszenia-$temp_nr id=numerzgloszenia-$temp_nr>";
					nowalinia();			
					echo "</fieldset>";
					echo "</span>";
					
			
		echo "<span id=OSZ_zasadnosc_zgloszenia-$temp_nr style=display:none>";
			nowalinia();
			echo "Zasadność zgłoszenia : ";
			echo "<input type=radio style='border:0px' name=zasadne-$temp_nr id=zasadne-$temp_nr value='TAK' checked>TAK";
			echo "&nbsp;&nbsp;";
			echo "<input type=radio style='border:0px' name=zasadne-$temp_nr id=zasadne-$temp_nr value='NIE'>NIE";
			
			//echo "<input type=button value=test onClick=\"setCheckedValue(document.forms['seryjna_obsluga_zgloszen'].elements['zasadne-$temp_nr'], 'NIE'); \" />";
		echo "</span>";
		
		if ($temp_kategoria=='2') {
			echo "<br /><br />";
			echo "<b>Aby zamknąć awarię zwykłą należy obsłużyć ją przez normalną obsługę zgłoszenia.</b>";
		}
		
			echo "</td>";
			echo "<td class=center>";
				echo "<input type=text class=wymagane value='0' id=oz_czas-$temp_nr name=oz_czas-$temp_nr size=1 onBlur=\"if (this.value=='') this.value='0'; UpdateSumaCzasu(); this.style='background-color:red;'\" onKeyPress=\"return filterInput(1, event, false, ''); \" style='text-align:right;'>";
			echo "</td>";		

			echo "<td id=kolumna_z_km-$temp_nr style='display:none;' class=center>";
				echo "<input type=text value='0' id=oz_km-$temp_nr name=oz_km-$temp_nr size=1 onBlur=\"if (this.value=='') this.value='0'; UpdateSumaKM(); GenerujTraseWyjazdowa_NOWA(); \" onKeyPress=\"return filterInput(1, event, false, ''); \" style='text-align:right;' >";
			echo "</td>";		
				
			echo "<td id=kolumna_z_czasem_przejazdu-$temp_nr style='display:none;' class=center>";
				echo "<input type=text value='0' id=oz_czas_przejazdu-$temp_nr name=oz_czas_przejazdu-$temp_nr size=1 onBlur=\"if (this.value=='') this.value='0'; UpdateSumaCzasu2(); \" onKeyPress=\"return filterInput(1, event, false, ''); \" style='text-align:right;' >";
			echo "</td>";		
			
			_tr();
		
		echo "<span id=wyk_czynnosci-$temp_nr name=wyk_czynnosci-$temp_nr style=display:>";
			if ($KolorujWgStatusow==1) {
				switch ($temp_kategoria) {
					case 6: $kolorgrupy='#F73B3B'; tbl_tr_color_dblClick($i, $kolorgrupy); break;
					case 2:	if ($temp_priorytet==2) { $kolorgrupy='#FF7F2A'; tbl_tr_color($i, $kolorgrupy); break; }
							if ($temp_priorytet==4) { $kolorgrupy='#F73B3B'; tbl_tr_color($i, $kolorgrupy); break; }				
					case 3:	if ($temp_priorytet==3) { $kolorgrupy='#FFAA7F'; tbl_tr_color($i, $kolorgrupy); break; }
					default: tbl_tr_highlight($i);
							$kolorgrupy='';
				}
			} else {
				tbl_tr_highlight($i);
				$kolorgrupy='';
			}
			
			echo "<td colspan=2 class=righttop>";
			echo "Wykonane czynności:";
			echo "</td>";
			echo "<td colspan=4 class=left>";
			//echo "<fieldset><legend><b>&nbsp;Wykonane czynności&nbsp;</b></legend>";
			
			echo "<textarea rows=2 cols=70 class=wymagane id=wyk_czynnosci_value-$temp_nr name=wyk_czynnosci_value-$temp_nr onBlur=\"ZamienTekst(this.id);\">";
			echo "</textarea>";
			
			$rx = mysql_query("SELECT zgl_szcz_czas_rozpoczecia_kroku,zgl_szcz_czas_wykonywania FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$temp_nr') and (zgl_szcz_widoczne=1) ORDER BY zgl_szcz_nr_kroku DESC LIMIT 1", $conn_hd) or die($k_b);
			list($data_ostatniego_kroku,$czas_wykonywania_ostatniego_kroku)=mysql_fetch_array($rx);
			
			//echo "<br />Data rozpoczęcia poprzedniego kroku: $data_ostatniego_kroku "; //,$czas_wykonywania_ostatniego_kroku";
			
			$data_ostatniego_kroku = AddMinutesToDate($czas_wykonywania_ostatniego_kroku,$data_ostatniego_kroku);
	
			echo "<input type=hidden name=data_ostatniego_kroku_value-$temp_nr id=data_ostatniego_kroku_value-$temp_nr value='".substr($data_ostatniego_kroku,0,10)."'>";
			echo "<input type=hidden name=godzina_ostatniego_kroku_value-$temp_nr id=godzina_ostatniego_kroku_value-$temp_nr value='".substr($data_ostatniego_kroku,11,5)."'>";
			
		//	echo "</fieldset>";
			echo "</td>";
			echo "<td id=addcol-$temp_nr colspan=2 style='display:none;'></td>";
			echo "</tr>";
		echo "</span>";
	
			$i++;
			$t++;
		}

	echo "<tr id=TRUstawDlaWszystkich style='display:";
	if ($_GET[cnt]=='1') echo "none";
	echo "'><td colspan=5></td><td style=text-align:center><sub>razem czas</sub></td><td id=kolumna_z_km2 style='display:none;text-align:center;'><sub>razem km</sub></td><td id=kolumna_z_km4 style='display:none;text-align:center;'><sub>razem czas wyjazów</sub></td></tr>";

	echo "<tr id=TRUstawDlaWszystkich style='background:grey;display:"; 
	if ($_GET[cnt]=='1') echo "none";
	echo "'>";

	echo "<td colspan=5 style='text-align:right'>";
	echo "<span style='float:left;padding-top:5px;'>";
	echo "<input id=UstawKolejnosc class=buttons type=button value='Ustaw kolejność' onClick=\"PrepareRows(1); \" style='display:none;'><input id=ZapiszKolejnosc type=button class=buttons value='Pokaż nr zgłoszeń' onClick=\"PrepareRows(0); \" style='display:none' >";
	echo "</span>";

	//echo "<td colspan=1></td>";
	// czas razem
	echo "<td class=center>";
	echo "<input type=text size=2 readonly name=czas_razem id=czas_razem value='0' style='background-color:transparent;text-align:right;border:0;text-weight:bold;'>";
	echo "</td>";

	// km razem
	echo "<td id=kolumna_z_km1 style=display:none class=center>";
	echo "<input type=text size=2 readonly name=km_razem id=km_razem value='0' style='background-color:transparent;text-align:right;border:0;text-weight:bold;'>";
	echo "</td>";

	// czas przejazdu km razem
	echo "<td id=kolumna_z_km3 style=display:none class=center>";
	echo "<input type=text size=2 readonly name=czas_przejazdu_razem id=czas_przejazdu_razem value='0' style='background-color:transparent;text-align:right;border:0;text-weight:bold;'>";
	echo "</td>";

	echo "</tr>";
	echo "</tbody>";
	endtable();

	echo "<fieldset><legend><b>&nbsp;Wartości dla wszystkich zgłoszeń&nbsp;</b></legend>";		
	nowalinia();
	//echo "<b>Ustaw dla wszystkich :</b>";
	starttable();
	echo "<tr><th class=center>Data<br /><u>zakończenia</u> kroku</th><th class=center>Godzina<br /><u>zakończenia</u> kroku</th><th class=left>Status zgłoszenia</th><th>Wykonane czynności</th><th>Dodatkowe osoby</th></tr>";
	echo "<tr>";
	echo "<td class=center>";
	$dddd = Date('Y-m-d');
	//echo SubstractWorkingDays(2,$dddd);
	echo "<select name=oz_data_dla_wszystkich id=oz_data_dla_wszystkich onChange=\"UpdateDateDlaWszystkich(this.value);\">";

	echo "<option value='$dddd' SELECTED>$dddd</option>\n";
	
	if ((date("w",strtotime($dddd))!=1) || ($idw_dla_zbh_testowa))  {
		for ($cd=1; $cd<($idw_dla_zbh+1); $cd++) {
			echo "<option value='".SubstractDays($cd,$dddd)."'>".SubstractDays($cd,$dddd)."&nbsp;";
			if ($idw_dla_zbh_testowa) echo "[dla testów]";
			echo "</option>\n";
		}
	}

	//for ($cd=1; $cd<($idw_dla_zbh+1); $cd++) echo "<option value='".SubstractDays($cd,$dddd)."'>".SubstractDays($cd,$dddd)."&nbsp;</option>\n";

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
	echo "</td>";

	echo "<td class=center>";
		$tttt = Date('H:i');
		echo "<input type=text name=oz_godzina_dla_wszystkich id=oz_godzina_dla_wszystkich value='$tttt' maxlength=5 size=2 onBlur=\"CheckTime_req2(this.value); \" onKeyPress=\"return filterInput(1, event, false, ':'); \" onChange=\"UpdateCzasDlaWszystkich(this.value);\" onKeyUp=\"DopiszDwukropek('oz_godzina_dla_wszystkich');\"/>";
	echo "</td>";

	echo "<td>";
		$ListaStatusow = "'4','5','6','7','9','3B','3A'";
		$sql1 = "SELECT hd_status_nr,hd_status_opis FROM $dbname_hd.hd_status WHERE ((hd_status_wlaczona=1) and (hd_status_nr IN (".$ListaStatusow."))) ORDER BY hd_status_id";

		$rsd = mysql_query($sql1,$conn_hd);
		$ile = mysql_num_rows($rsd);
		if ($ile>0) {
			echo "<select id=SelectZmienStatus_all name=SelectZmienStatus_all onChange=\"UpdateStatusDlaWszystkich(this.value); PokazZasadnoscZgloszenia_onLoad(); \" />";
			echo "<option value=''></option>\n>";
			while (list($temp_num,$temp_opis) = mysql_fetch_array($rsd)) {
				echo "<option value='$temp_num' ";
				echo ">$temp_opis</option>\n"; 
			}
			echo "</select>";
		}

		echo "<span id=OSZ_zasadnosc_zgloszenia-all style=display:none>";
			nowalinia();
			echo "Zasadność zgłoszenia : ";
			echo "<input type=radio style='border:0px' name=zasadne_all id=zasadne_all value='TAK' onClick=\"UstawZZDlaWszystkich('TAK');\" >TAK";
			echo "&nbsp;&nbsp;";
			echo "<input type=radio style='border:0px' name=zasadne_all id=zasadne_all value='NIE' onClick=\"UstawZZDlaWszystkich('NIE');\">NIE";
		echo "</span>";
		
	echo "</td>";

	echo "<td class=left>";
		
		echo "<span id=wyk_czynnosci-all style=display:''>";	
			echo "<textarea id=wyk_czynnosci_value-all rows=2 cols=40 onBlur=\"ZamienTekst(this.id); PowielWykonaneCzynnosci();	\" onKeyUp=\"PowielWykonaneCzynnosci();\">";
			echo "</textarea>";
		echo "</span>";

		echo "&nbsp;";
		//echo "<a title=' Pokaż wszystkie ' href=# onClick=\"document.getElementById('showmore-all').style.display='none'; document.getElementById('hidemore-all').style.display=''; WykonaneCzynnosci('pokaz'); return false; \"><img class=imgoption type=image src=img/show_more.gif id=showmore-all style='display:none' width=16 width=16></a>";
		
		//echo "<a title=' Ukryj wszystkie ' href=# onClick=\"document.getElementById('showmore-all').style.display=''; document.getElementById('hidemore-all').style.display='none'; WykonaneCzynnosci('ukryj'); return false; \"><img  class=imgoption type=image src=img/hide_more.gif id=hidemore-all style='display:;'></a>";
		echo "<a title=' Czyść wykonane czynności' href=# onClick=\"CzyscWykonaneCzynnosci(); return false; \"><img class=imgoption type=image src=img/czysc.gif></a>";
		
	echo "</td>";

	echo "<td>";
		
		echo "<input class=border0 type=checkbox name=WieleOsobCheck id=WieleOsobCheck onClick=\"if (this.checked) { document.getElementById('WieleOsobWybor').style.display=''; } else { document.getElementById('WieleOsobWybor').style.display='none'; } \" />";
				
		echo "<a href=# class=normalfont onClick=\"if (document.getElementById('WieleOsobCheck').checked) { document.getElementById('WieleOsobCheck').checked=false; } else { document.getElementById('WieleOsobCheck').checked=true; } if (document.getElementById('WieleOsobCheck').checked) { document.getElementById('WieleOsobWybor').style.display=''; } else { document.getElementById('WieleOsobWybor').style.display='none'; }\"> Zgłoszenia obsługiwane przez wiele osób</a>";
		
		echo "<span id=WieleOsobWybor style='display:'none';>";
			$sql_filtruj = "SELECT user_first_name, user_last_name FROM $dbname.serwis_uzytkownicy WHERE (belongs_to=$es_filia) and (user_locked=0) and (user_id<>$es_nr) and (CONCAT(user_first_name,' ',user_last_name)<>'$currentuser') ORDER BY user_last_name ASC";
			$result44 = mysql_query($sql_filtruj, $conn) or die($k_b);
			$count_rows = mysql_num_rows($result44);
					
					if ($count_rows>0) {
						echo "<select class=wymagane name=userlist_s[] size=7 id=userlist_s multiple=multiple>\n";
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
						echo "<textarea style='background-color:transparent;font-size:9px;font-family:tahoma;' name=lista_osob  id=userlist_s_selectedItems readonly cols=120 rows=3></textarea>";
					} else echo "<input type=hidden name=lista_osob id=userlist_s_selectedItems value=''>";
		echo "</span>";
	echo "</td>";
	
	echo "</tr>";

	endtable();
	echo "</fieldset>";

	echo "<input type=hidden name=cnt_zgl id=cnt_zgl value='$_GET[cnt]'>";

	$result_k = mysql_query("SELECT filia_lokalizacja FROM $dbname.serwis_filie WHERE filia_id=$es_filia LIMIT 1", $conn) or die($k_b);
	list($temp_lok)=mysql_fetch_array($result_k);

	echo "<div id=trasa_wyjazdowa style=display:none>";
	echo "<fieldset><legend><b>&nbsp;Trasy wyjazdowe&nbsp;</b></legend>";
	echo "<p align=right>";
	//nowalinia();
	echo "&nbsp;&nbsp;<input class=border0 type=checkbox id=WyjazdZFilii style='border:0;float:left;display:none' checked onClick=\"UpdateTrasaWyjazdowa();\" ><a style='float:left' onClick=\"if (document.getElementById('WyjazdZFilii').checked) { document.getElementById('WyjazdZFilii').checked=false; UpdateTrasaWyjazdowa(); } else { document.getElementById('WyjazdZFilii').checked=true; UpdateTrasaWyjazdowa(); } \">";
	//echo "&nbsp;Początek trasy: <b>$temp_lok</b>";
	echo "</a>";

	echo "&nbsp;&nbsp;<input class=border0 type=checkbox id=PowrotDoFilii style='border:0;float:right;display:none' checked onClick=\"UpdateTrasaWyjazdowa();\" ><a style='float:right' onClick=\"if (document.getElementById('PowrotDoFilii').checked) { document.getElementById('PowrotDoFilii').checked=false; UpdateTrasaWyjazdowa(); } else { document.getElementById('PowrotDoFilii').checked=true; UpdateTrasaWyjazdowa(); } \">";
	//echo "&nbsp;Koniec trasy: <b>$temp_lok</b>";
	echo "</a>";

	echo "<input id=btn_GenerujTrase type=button class=buttons value='Generuj trasę' onClick=\"GenerujTraseWyjazdowa_NOWA();\" >";
	echo "<input type=button class=buttons value='Czyść trasę' onClick=\"CzyscTrasyWyjazdowe();\" >";
	nowalinia();
	echo "</p>";

	// utworzenie pól dla tras dla wszystkich zgłoszeń
	$sql = "SELECT * FROM $dbname_hd.hd_zgloszenie WHERE (zgl_nr IN (".$_GET[nr]."))";
	$result = mysql_query($sql, $conn_hd) or die($k_b);
	
	$nrzgloszenia = 1;
	starttable();
	echo "<tr><th>Trasa wyjazdowa</th><th>Przejechane</th></tr>";
	while ($newArray = mysql_fetch_array($result)) {
		$temp_id  			= $newArray['zgl_id'];
		$temp_nr			= $newArray['zgl_nr'];	
		
		echo "<tr>";
		echo "<td>";
		echo "<input type=text name=trasa-$nrzgloszenia id=trasa-$nrzgloszenia value='' size=100>";
		echo "<input type=hidden name=trasa_hidden-$temp_nr id=trasa_hidden-$temp_nr value='' size=100>";
		echo "</td>";
		
		echo "<td>";
		echo "<input type=text readonly style='background-color:transparent; border:0px; text-align:right' name=km-$nrzgloszenia id=km-$nrzgloszenia value='' size=5> km";
		echo "</td>";
		
		echo "</tr>";
		$nrzgloszenia++;
	}
	
	endtable();
	echo "<textarea class=wymagane id=trasa_wyjazdu name=trasa_wyjazdu cols=80 rows=3 style=display:none></textarea>";

	echo "</fieldset>";
	echo "</div>";

	echo "<input type=hidden id=lokalizacjazrodlowa value='$temp_lok'>";

	echo "<input type=hidden name=numbers value='$_REQUEST[nr]'>";

	
	startbuttonsarea("right");
	oddziel();
	//echo "<input class=buttons type=button onClick=\"self.location.reload(); \" value='Odśwież widok' />";
	echo "<span style='float:left'>";
	echo "<input class=buttons id=reset name=reset type=reset value='Wyczyść wprowadzone zmiany' onClick=\"\">";
	echo "</span>";
	
	echo "<input class=buttons id=WykonajObslugeSeryjna name=submit type=submit value='Zapisz zmiany'>";
	echo "<input class=buttons id=anuluj type=button onClick=\"pytanie_anuluj('Potwierdzasz anulowanie wprowadzonych zmian ?');\" value=Zamknij>";
	
	//echo "<span id=Saving style=display:none><b><font color=red>Trwa zapisywanie danych... proszę czekać&nbsp;</font></b><input class=imgoption type=image src=img/loader.gif></span>";

	//echo "<div id=closebutton class='highslide-overlay closebutton' onclick=\"return eSerwisOZS.close(this)\" title=\"Close\" >wwww</div>";
	endbuttonsarea();

	echo "</form>";
	
echo "</div>";	
}

?>

<script>HideWaitingMessage();</script>

<script type="text/javascript" src="js/jquery/entertotab.js"></script>

<script type='text/javascript'>
	EnterToTab.init(document.forms.seryjna_obsluga_zgloszen, true);
	WykonaneCzynnosci('pokaz');
</script>
	
</body>
</html>