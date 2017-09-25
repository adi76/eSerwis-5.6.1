<?php 
include_once('cfg_helpdesk.php');
include_once('header.php');
echo "<body";

if ($submit=='Zapisz zmiany') { } else {
echo " OnLoad=\"document.forms[0].elements[0].focus(); document.forms[0].elements[0].select(); cookieForms('open', 'harmonogram_wyjazdu');\" onUnload=\"cookieForms('save', 'harmonogram_wyjazdu');\" ";
}
echo ">";

if ($submit=='Zapisz zmiany') {
	if ($_SESSION[generuj_harmonogram_wyjazdowy_zapisano]!='tak') {

		//echo "Ilość zgłoszeń : ".$_REQUEST[cnt_zgl]."<br />";
		$ile_zgloszen = $_REQUEST[cnt_zgl];
		
		$opzw = $_REQUEST[obsl_powiazana_z_wyjazdem];
		if (($opzw=='P') || ($opzw=='S')) { $obsluga_powiazana_z_wyjazdem = 1; } else { $obsluga_powiazana_z_wyjazdem = 0; }
		
	//	echo "Obsługa powiązana z wyjazdem : ".$obsluga_powiazana_z_wyjazdem." (km: ".$_REQUEST[ilosc_km].")<br />";			// true or false
		if ($obsluga_powiazana_z_wyjazdem==1) {
			echo "Trasa wyjazdu : ".$_REQUEST[trasa_wyjazdu]."<br />";
		}

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
			
			$sql = "INSERT INTO $dbname_hd.hd_zgloszenie_szcz VALUES ('',$current_zgl_id,'$zgl_seryjme_unique_nr',$last_nr_kroku,'$czas_START_STOP',$d_cw,'$newStartDate','$current_status','$current_wykonane_czynnosci','$osoba_przypisana','',0,$oferta_wyslana,$zam_wyslane,$bylwyjazd,'$dddd','$currentuser',1,$przejechane_km,$awaria_z_przesunieciem,'','','','','','','$_REQUEST[hdds]',$current_problem_rozwiazany,0,$es_filia)";
			
			$result = mysql_query($sql, $conn_hd) or die($k_b);
		
			// zaktualizuj zgl_czy_rozwiazany_problem w zgłoszeniu 
			if ($current_problem_rozwiazany==1) $czy_rozwiazany_data = $current_data;
			
			$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_czy_rozwiazany_problem='$current_problem_rozwiazany', zgl_czy_rozwiazany_problem_data='$czy_rozwiazany_data' WHERE ((belongs_to='$es_filia') and (zgl_nr='$current_zgl_nr')) LIMIT 1";
			
			$result = mysql_query($sql, $conn_hd) or die($k_b);

		// wyciągnij numer kategorii i podkategorii
			$r3 = mysql_query("SELECT zgl_kategoria, zgl_podkategoria FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to='$es_filia') and (zgl_id='$current_zgl_id'))", $conn_hd) or die($k_b);
			list($current_kategoria,$current_podkategoria)=mysql_fetch_array($r3);
		// koniec
		
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
				$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_poledodatkowe1='$current_nr_awarii_wan', zgl_data_zmiany_statusu='$current_data' WHERE ((belongs_to='$es_filia') and (zgl_nr='$current_zgl_nr')) LIMIT 1";
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
			$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_status='$current_status', zgl_data_zmiany_statusu='$current_data' , zgl_osoba_przypisana='$currentuser' WHERE ((zgl_widoczne=1) and (belongs_to='$es_filia') and (zgl_nr='$current_zgl_nr')) LIMIT 1";
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
		
				$sql = "INSERT INTO $dbname_hd.hd_zgloszenie_wyjazd VALUES ('','$zgl_seryjme_unique_nr','$current_data','$current_trasa','$current_kilometry','$currentuser','$_REQUEST[obsl_powiazana_z_wyjazdem]',1,$es_filia)";
			//	echo ">>>> $sql";
				
				//echo "<br />$sql";
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

} else {

	session_register('generuj_harmonogram_wyjazdowy_zapisano');	
	$_SESSION[generuj_harmonogram_wyjazdowy_zapisano]='nie';
	
if ($_REQUEST[cnt]!=0) {	
	pageheader("Dodawanie zgłoszeń do harmonogramu wyjazdowego",0);
	echo "<div id=content>";
	echo "<form name=harmonogram_wyjazdu id=harmonogram_wyjazdu method=POST action=$PHP_SELF onSubmit=\"return SeryjnaObslugaCheckOnSubmit();\" >";
	
	$ddddtt = Date('Y-m-d H:i');
	
	startbuttonsarea("center");
		$dddd = Date('Y-m-d');
		
		echo "<br />Nazwa harmonogramu: ";
		echo "<input type=text size=30 value='";
		
		if ($_REQUEST[nazwa_h]=='') { echo "".$dddd."_".$es_login.""; } else echo "$_REQUEST[nazwa_h]";
		
		echo "' name=nazwa_h id=nazwa_h>";
		
		echo " | Planowana data wykonania: ";
		
		echo "<input id=data_h size=10 maxlength=10 type=text name=data_h maxlength=10 value="; 

		if ($_REQUEST[data_h]=='') { echo "$dddd"; } else echo "$_REQUEST[data_h]";
		
		echo " onkeypress='return handleEnter(this, event);' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\">";
		echo "&nbsp;<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img src=img/cal.gif width=16 height=16 border=0></a>";
		if ($_today==1) echo "&nbsp;<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('data_h').value='".Date('Y-m-d')."'; return false;\">";
		
		echo " | Przypisanie do osoby: <select id=przypisanie_h name=przypisanie_h />";
		
		$sql_f1="SELECT user_first_name, user_last_name FROM $dbname.serwis_uzytkownicy WHERE (user_locked=0) and (belongs_to=$es_filia) ORDER BY user_first_name ASC";
		$result_f1=mysql_query($sql_f1,$conn) or die($k_b);
		
		echo "<option value='' ";
		
		if ($_REQUEST[przypisanie_h]=='nie przypisane') { echo " SELECTED "; }
		echo ">nie przypisane</option>\n";
		
		while ($dane_f1=mysql_fetch_array($result_f1)) {
			$temp_osoba1 = $dane_f1['user_first_name'];
			$temp_osoba2 = $dane_f1['user_last_name'];
			$iin = $temp_osoba1 ." ".$temp_osoba2;
			
			if ($iin=='') {
				
			} else {
				$iin = $temp_osoba1.' '.$temp_osoba2;
				echo "<option value='$temp_osoba1 $temp_osoba2'";	
				
				if ($_REQUEST[przypisanie_h]==urldecode($iin)) { echo " SELECTED "; }
				
				echo ">$temp_osoba1 $temp_osoba2</option>\n";
			}
		}
		echo "</select>";
		
		echo "<br /><br />";
	endbuttonsarea();	
	
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
	
	echo "<table id=TabelaZeZgloszeniami cellspacing=1 align=center class=maxwidth>";
	echo "<thead>";
	//th_("10;c;Nr<br />zgłoszenia|50;c;Kolejność|;l;Komórka<br />Temat|;c;Data|;c;Godzina|;l;Aktualny status<br />Nowy status|;c;Czas<br />(min)|;c;km",$es_prawa);

	echo "<th class=center>";
	echo "<span id=th_kolumna_z_nr>Nr zgłoszenia</span><span id=th_kolumna_z_kolejnoscia style='display:none'>Kolejność<br />wyjazdu</span></th>";
	
	echo "<th class=center>Status</th><th class=center>Data zgłoszenia</th>";
	
	echo "<th class=left>Placówka zgłaszająca</th><th class=left>Temat zgłoszenia</th><th class=center>Opcje</th>";
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
			
			$temp_parent_zgl	= $newArray['zgl_kontynuacja_zgloszenia_numer'];
			$temp_rekl_czy_jest = $newArray['zgl_czy_to_jest_reklamacyjne'];
			$temp_rekl_nr	 	= $newArray['zgl_nr_zgloszenia_reklamowanego'];
			$temp_rekl_czy_ma	= $newArray['zgl_czy_ma_zgl_reklamacyjne'];		
			$temp_naprawa_id	= $newArray['zgl_naprawa_id'];
			$temp_czy_pow_z_wp	= $newArray['zgl_czy_powiazane_z_wymiana_podzespolow'];
				
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
			
			echo "<td class=right>";
			echo "<span "; 
			if (($czy_wyroznic_zgloszenia_seryjne==1) && ($temp_zgl_seryjne!='')) echo " title=' To jest zgłoszenie seryjne '";
			echo "id=numer-$i>$temp_nr&nbsp;"; 
			if (($czy_wyroznic_zgloszenia_seryjne==1) && ($temp_zgl_seryjne!='')) echo "[s]";
			echo "</span>";
			
				if ($temp_parent_zgl!=0) {
					//echo "<input title=' Zgłoszenie utworzono na bazie zgłoszenia numer $temp_parent_zgl ' class=imgoption type=image src=img/have_parent.gif>";
					echo "<a class=normalfont title=' Zgłoszenie utworzono na bazie zgłoszenia numer $temp_parent_zgl ' href=#><img class=imgoption src=img/have_parent.gif border=0 width=16 height=16></a>";
				}
				
				if ($temp_naprawa_id>0) {
					//echo "<a title=' Przejdź do modułu NAPRAWY ' href=# onClick=\"self.location.href='przekieruj_do_napraw.php?hd_zgl_nr=$temp_nr'\"><img class=imgoption src=img/naprawa_unknown.gif border=0 width=16 height=16></a>";
					echo "<a title='Zgłoszenie powiązane z naprawą' href=#><img class=imgoption src=img/naprawa_unknown.gif border=0 width=16 height=16></a>";		
				}

				if (($temp_nrawarii!='') && ($temp_status!=9)) {
					//echo "<a title=' Przejdź do listy otwartych awarii WAN ' href=# onClick=self.location.href='z_awarie.php?nr=$temp_nrawarii&up=".urlencode($temp_komorka)."'><img class=imgoption src=img/wan_disconnect.gif border=0></a>";
					
					echo "<a title='Otwarta awaria WAN' href=#><img class=imgoption src=img/wan_disconnect.gif border=0 width=16 height=16></a>";
				}

				if (($temp_nrawarii!='') && ($temp_status==9)) {
					//echo "<a title=' Przejdź do listy zamkniętych awarii WAN ' href=# onClick=self.location.href='z_awarie_zamkniete.php?nr=$temp_nrawarii&up=".urlencode($temp_komorka)."'><img class=imgoption src=img/wan_connect.gif border=0></a>";
					echo "<a title='Zamknięta awaria WAN' href=#><img class=imgoption src=img/wan_connect.gif border=0 width=16 height=16></a>";
				}

				if (($temp_parent_zgl!=0)) {
					$sql88="SELECT zgl_czy_powiazane_z_wymiana_podzespolow FROM $dbname_hd.hd_zgloszenie WHERE (zgl_nr=$temp_parent_zgl) LIMIT 1";
					$result88 = mysql_query($sql88, $conn_hd) or die($k_b);
					list($temp_czy_pow_z_wp_parent)=mysql_fetch_array($result88);
					
					if ($temp_czy_pow_z_wp_parent==1) {
						$sql_wp="SELECT wp_sprzet_opis, wp_sprzet_sn, wp_sprzet_ni FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_zgl_id=$temp_parent_zgl) and (belongs_to=$es_filia) LIMIT 1";	
						$result_wp = mysql_query($sql_wp, $conn_hd) or die($k_b);

						list($wp_o,$wp_sn,$wp_ni)=mysql_fetch_array($result_wp);
						if ($wp_sn=='') $wp_sn='-';
						if ($wp_ni=='') $wp_ni='-';

						if ($enableHDszczPreviewDIV==1) {
							$rand = rand_str(10);			
							$sciezka_do_cookie = "hd_p_zgloszenie_kroki.php?nr=".$temp_nr."&id=".$temp_id."&searchtext=".urlencode($_REQUEST[st])."&kg=".substr($kolorgrupy,1,strlen($kolorgrupy))."&randval=".$rand."";
							
							//echo "<a href=# title='Zgłoszenie powiązane z wymianą podzespołów w sprzęcie: $wp_o (SN: $wp_sn, NI: $wp_ni) w zgłoszeniu $temp_parent_zgl' onclick=\"$('#ZawartoscDIV').load('hd_p_zgloszenie_kroki.php?nr=".$temp_nr."&id=".$temp_id."&searchtext=".urlencode($_REQUEST[st])."&kg=".substr($kolorgrupy,1,strlen($kolorgrupy))."&poz=under&randval=".$rand."&ss=".urlencode($_REQUEST[ss])."&st_wc=".urlencode($_REQUEST[st_wc])."&searcheserwisnr=".urlencode($_REQUEST[search_eserwis_nr])."'); createCookie('hd_p_zgloszenia_div_nr','$sciezka_do_cookie',365); $('#info').hide(); self.location.href='#_SZ'; return false;\" /><img class=imgoption style='border:0px' type=image src=img/wp.gif width=16 height=16></a>";
							
							echo "<a href=# title='Zgłoszenie powiązane z wymianą podzespołów w sprzęcie: $wp_o (SN: $wp_sn, NI: $wp_ni) w zgłoszeniu $temp_parent_zgl' ><img class=imgoption style='border:0px' type=image src=img/wp.gif width=16 height=16></a>";
							
						}
						
					}
					
				}

				if ($temp_czy_pow_z_wp==1) {
					$sql_wp="SELECT wp_sprzet_opis, wp_sprzet_sn, wp_sprzet_ni FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_zgl_id=$temp_nr) and (belongs_to=$es_filia) LIMIT 1";	
					$result_wp = mysql_query($sql_wp, $conn_hd) or die($k_b);
					list($wp_o,$wp_sn,$wp_ni)=mysql_fetch_array($result_wp);
					if ($wp_sn=='') $wp_sn='-';
					if ($wp_ni=='') $wp_ni='-';
					
					if ($enableHDszczPreviewDIV==1) {
						$rand = rand_str(10);			
						$sciezka_do_cookie = "hd_p_zgloszenie_kroki.php?nr=".$temp_nr."&id=".$temp_id."&searchtext=".urlencode($_REQUEST[st])."&kg=".substr($kolorgrupy,1,strlen($kolorgrupy))."&randval=".$rand."";
						
						//echo "<a href=# title='Zgłoszenie powiązane z wymianą podzespołów w sprzęcie: $wp_o (SN: $wp_sn, NI: $wp_ni)'  onclick=\"$('#ZawartoscDIV').load('hd_p_zgloszenie_kroki.php?nr=".$temp_nr."&id=".$temp_id."&searchtext=".urlencode($_REQUEST[st])."&kg=".substr($kolorgrupy,1,strlen($kolorgrupy))."&poz=under&randval=".$rand."&ss=".urlencode($_REQUEST[ss])."&st_wc=".urlencode($_REQUEST[st_wc])."&searcheserwisnr=".urlencode($_REQUEST[search_eserwis_nr])."'); createCookie('hd_p_zgloszenia_div_nr','$sciezka_do_cookie',365); $('#info').hide(); self.location.href='#_SZ'; return false;\" /><img class=imgoption style='border:0px' type=image src=img/wp.gif  width=16 height=16></a>";
						echo "<a href=# title='Zgłoszenie powiązane z wymianą podzespołów w sprzęcie: $wp_o (SN: $wp_sn, NI: $wp_ni)'><img class=imgoption style='border:0px' type=image src=img/wp.gif width=16 height=16></a>";
					}
				}
				
				
				if ($temp_rekl_czy_ma==1) {
					list($rekl_nr)=mysql_fetch_array(mysql_query("SELECT zgl_nr FROM hd_zgloszenie WHERE (zgl_nr_zgloszenia_reklamowanego=$temp_nr) LIMIT 1"));
					
					//echo "<a href=# title='To zgłoszenie było reklamowane przez klienta. Utworzono z niego zgłoszenie reklamacyjne o numerze $rekl_nr' onclick=\"self.location.href='hd_p_zgloszenia.php?sr=search-wyniki&search_zgl_nr=$rekl_nr';\"><input class=imgoption type=image src=img/is_reklamacyjne.gif></a>";
					
					echo "<a href=# title='To zgłoszenie było reklamowane przez klienta. Utworzono z niego zgłoszenie reklamacyjne o numerze $rekl_nr'><input class=imgoption type=image src=img/is_reklamacyjne.gif></a>";
				}
				
				if ($temp_rekl_czy_jest==1) {
					//echo "<a href=# title='To jest zgłoszenie reklamacyjne do zgłoszenia nr $temp_rekl_nr' onclick=\"self.location.href='hd_p_zgloszenia.php?sr=search-wyniki&search_zgl_nr=$temp_rekl_nr';\"><input class=imgoption type=image src=img/have_reklamacyjne.gif></a>";
					echo "<a href=# title='To jest zgłoszenie reklamacyjne do zgłoszenia nr $temp_rekl_nr'><input class=imgoption type=image src=img/have_reklamacyjne.gif></a>";
				}
				
			echo "<b><a href=# class=normalfont id=dec-$i onClick=\"Decrease(document.getElementById('order-$i')); return false; \" style='display:none;'>&nbsp;-&nbsp;</a> </b>";
			echo "<input name=order-$i id=order-$i type=text maxlength=2 size=1 style='text-align:center; background-color:yellow; display:none;' value='$t' onKeyPress=\"return filterInput(1, event, false, ''); \">";
			echo " <a href=# class=normalfont id=inc-$i onClick=\"Increase(document.getElementById('order-$i')); return false;\" style='display:none'>&nbsp;+&nbsp;</a>";
			echo "</td>";
			
			echo "<td class=center>";		
				echo "<a class='normalfont' href=# onclick=\"newWindow_r(800,600,'hd_o_zgloszenia.php?action=obsluga&id=$temp_id&nr=$temp_nr&zgl_s=$zgl_seryjne_mark'); return false;\">";
				
				switch ($temp_status) {
				case "1"	: echo "<input class=imgoption type=image src=img/zgl_nowe.gif>"; break;
				case "2"	: echo "<input class=imgoption type=image src=img/zgl_przypisane.gif>"; break;
				case "3"	: echo "<input class=imgoption type=image src=img/zgl_rozpoczete.gif>"; break;
				case "3A"	: echo "<input class=imgoption type=image src=img/zgl_w_serwisie_zewnetrznym.gif>"; break;
				case "3B"	: echo "<input class=imgoption type=image src=img/zgl_w_firmie.gif>"; break;
				case "4"	: echo "<input class=imgoption type=image src=img/zgl_oczekiwanie_na_odpowiedz.gif>"; break;
				case "5"	: echo "<input class=imgoption type=image src=img/zgl_oczekiwanie_na_sprzet.gif>"; break;
				case "6"	: echo "<input class=imgoption type=image src=img/zgl_do_oddania.gif>"; break;
				case "7"	: echo "<input class=imgoption type=image src=img/zgl_rozpoczete_nie_zakonczone.gif>"; break;
					//case "8"	: echo "<input class=imgoption type=image src=img/zgl_nowe.gif>"; break;
				case "9"	: echo "<input class=imgoption type=image src=img/zgl_zamkniete.gif>"; break;
				}
				
				echo "</a>";
			echo "</td>";
			
			echo "<td class=center>";
				echo $temp_data." ".substr($temp_godzina,0,5);
			echo "</td>";
			
			echo "<td class=left>";
					echo "$temp_komorka";
			echo "</td>";			
			
			echo "<td class=left>";
				echo "<input type=hidden tabindex=-1 name=komorka-$temp_nr id=komorka-$temp_nr value='$temp_komorka'>";
				echo nl2br(wordwrap($temp_temat, 60, "<br />"));				
				echo "<input type=hidden tabindex=-1 name=temat_zgl-$temp_nr id=temat_zgl-$temp_nr value='$temp_temat'>";				
			echo "</td>";			
					
			echo "<td class=center>";
				echo "<a href=# onclick=\"DeleteFomHarmonogram($temp_nr,$_REQUEST[cnt]); return false; \">"; 
				echo "<input class=imgoption type=image src=img/delete.gif title=' Usuń zgłoszenie nr $temp_nr z harmonogramu '>";
				echo "</a>";
			echo "</td>";
			_tr();
	
			$i++;
			$t++;
		}

	echo "</tbody>";
	endtable();

	echo "<input type=hidden name=cnt_zgl id=cnt_zgl value='$_GET[cnt]'>";
	echo "<input type=hidden name=numbers id=numbers value='$_REQUEST[nr]'>";

	startbuttonsarea("right");

	echo "<span style='float:left'>";

		echo "<input id=UstawKolejnosc class=buttons type=button value='Ustaw kolejność' onClick=\"PrepareRows(1); \" style='display:;'>";
		echo "<input id=ZapiszKolejnosc type=button class=buttons value='Pokaż nr zgłoszeń' onClick=\"PrepareRows(0); \" style='display:none' >";

	
	echo "</span>";
	
	echo "<input class=buttons id=WykonajObslugeSeryjna name=submit type=submit value='Zapisz zmiany'>";
	echo "<input class=buttons id=anuluj type=button onClick=\"pytanie_anuluj('Potwierdzasz anulowanie wprowadzonych zmian ?');\" value=Zamknij>";

	endbuttonsarea();

	echo "</form>";
	
	echo "</div>";	
	
	?>
	<script language="JavaScript">
	var cal1 = new calendar1(document.forms['harmonogram_wyjazdu'].elements['data_h']);
		cal1.year_scroll = true;
		cal1.time_comp = false;
	</script>
	<?php 	
	
	
	} else {
		errorheader("Brak zgłoszeń, które można dodać do harmonogramu",0);
		startbuttonsarea("right");
		echo "<input class=buttons id=anuluj type=button onClick=\"self.close();\" value='Zamknij'>";
		endbuttonsarea();
	}
}

?>

<script>ShowWaitingMessage('Trwa zapisywanie danych... proszę czekać', 'Saving');</script>

<script type="text/javascript" src="js/jquery/entertotab.js"></script>

<script type='text/javascript'>
	EnterToTab.init(document.forms.harmonogram_wyjazdu, true);
	WykonaneCzynnosci('pokaz');
</script>
	
</body>
</html>