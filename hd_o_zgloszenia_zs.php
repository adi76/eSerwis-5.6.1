<?php 
include_once('header.php');
include_once('cfg_eserwis.php');
include_once('cfg_helpdesk.php');

echo "<body OnLoad=\"document.getElementById('zs_time').focus(); \" />";

function HexToNormal($string) {
	$string = urlencode($string);
	// 					ą		ć		ę			ł		ń		ó		ś		ź			ż
	$_hex = array ('%25u0105', '%25u0107','%25u0119','%25u0142','%25u0144','%25uFFFD','%25u015B','%25u017A','%25u017C', '%25u0104', '%25u0106', '%25u0118', '%25u0141', '%25u0143', '%25u00D3', '%25u015A', '%25u0179', '%25u017B','%F3','%D3' );
	$_normal = array ('ą','ć','ę','ł','ń','ó','ś','ź','ż','Ą','Ć','Ę','Ł','Ń','Ó','Ś','Ź','Ż','ó','Ó');
	
	$string = str_replace($_hex, $_normal, $string);
	return urldecode($string); 
}; 

function toUpper($string) {
	$string = strtr($string,'qwertyuiopasdfghjklzxcvbnm','QWERTYUIOPASDFGHJKLZXCVBNM');
	return (strtr($string, 'ęóąśłżźćń','ĘÓĄŚŁŻŹĆŃ' )); 
}; 

function ClearOutputText($s) {
	$s = str_replace("\\\\", "\\", $s);
	$s = str_replace("\\\"", "`", $s);
	$s = str_replace("\\'", "`", $s);
	$s = str_replace("\\", "/", $s);
	//$s = str_replace("\\'", "'", $s);
	//$s = str_replace("\\'", "'", $s);
	return $s;
}
//echo ">>>".$_SESSION['naprawa_id_for_zgloszenie_nr_'.$_REQUEST[id].''];

if ($submit) {
	
	//if ($_SESSION['naprawa_id_for_zgloszenie_nr_'.$_REQUEST[id].'']>0) $_SESSION['naprawa_id_for_zgloszenie_nr_'.$_REQUEST[id].'']=0;
	
	$_REQUEST=sanitize($_REQUEST);

	if ($_SESSION['refresh_o_zgloszenia_'.$_REQUEST[nr].'']==0) {
		$_SESSION['refresh_o_zgloszenia_'.$_REQUEST[id].'']=1;
	}
	
/*	echo "$_REQUEST[zs_data]<br />";
	echo "$_REQUEST[zs_time]<br />";
	echo "$_REQUEST[SelectZmienStatus]<br />";
	echo "$_REQUEST[zs_wcz]<br />";
	echo "$_REQUEST[PozwolWpisacKm]<br />";
	echo "$_REQUEST[trasa]<br />";
	echo "$_REQUEST[km]<br />";
	echo "$_REQUEST[czas_wykonywania]<br />";
*/
	// zapisanie danych do tabeli hd_zgloszenie_szcz (START)
	if ($_SESSION[zgloszenie_szcz_dodano]!='poprawnie') {
		//$zgl_seryjme_unique_nr = rand_str(20);

		
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
		if (($_REQUEST[SelectZmienStatus]==9) || ($_REQUEST[PozwolWpisacKm]=='on')) {
			$czy_wymagany_wyjazd = 0;
		//	$czy_wymagany_wyjazd_data = '';
		//	$czy_wymagany_wyjazd_osoba = '';
		}
		
		$zgl_seryjme_unique_nr = Date('YmdHis')."".rand_str();
		
		$bylwyjazd=0;
		if ($_REQUEST[PozwolWpisacKm]=='on') $bylwyjazd = 1;

		$d_cz_wyjazdu = 0;	// Czas trwania przejazdu
		if ($bylwyjazd==1) {
			if ($_REQUEST[czas_przejazdu_h]!='') { $h_na_m = (int) $_REQUEST[czas_przejazdu_h]*60; }
			if ($_REQUEST[czas_przejazdu_m]!='') { $m_na_m = (int) $_REQUEST[czas_przejazdu_m]; }		
			$d_cz_wyjazdu=$h_na_m+$m_na_m;
		}
		
		$dddd = date("Y-m-d H:i:s");
		$dd = date("Y-m-d H:i:s");
		
		$osobaprzypisana='';
		if (($_REQUEST[status_id_value]=='2') || ($_REQUEST[status_id_value]=='3') || ($_REQUEST[status_id_value]=='9') || ($_REQUEST[status_id_value]=='0')) $osobaprzypisana=$currentuser;

		$last_nr=$_REQUEST[id];
		$unique_nr=$_REQUEST[unr];
	
		//echo "SELECT zgl_szcz_nr_kroku,zgl_szcz_unikalny_numer FROM $dbname_hd.hd_zgloszenie_szcz WHERE ((zgl_szcz_zgl_id='$_REQUEST[id]') and (belongs_to='$es_filia') and (zgl_szcz_widoczne=1))";
		
		$r3 = mysql_query("SELECT zgl_szcz_nr_kroku FROM $dbname_hd.hd_zgloszenie_szcz WHERE ((zgl_szcz_zgl_id='$_REQUEST[id]')) ORDER BY zgl_szcz_nr_kroku DESC LIMIT 1", $conn_hd) or die($k_b);
		list($last_nr_kroku)=mysql_fetch_array($r3);
		$last_nr_kroku+=1;
		
		switch ($_REQUEST[SelectZmienStatus]) {
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
		
//		$d_cw = $_REQUEST[czas_wykonywania];
		
		//print_r($_REQUEST);
		
		$d_cw = 0;	// czas wykonywania
		if ($_REQUEST[czas_wykonywania_h]!='') { $h_na_m = (int) $_REQUEST[czas_wykonywania_h]*60; }
		if ($_REQUEST[czas_wykonywania_m]!='') { $m_na_m = (int) $_REQUEST[czas_wykonywania_m]; }
		
		$d_cw=$h_na_m+$m_na_m;

		list($Zdiagnozowany1)=mysql_fetch_array(mysql_query("SELECT zgl_szcz_zdiagnozowany FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$last_nr) ORDER BY zgl_szcz_id DESC LIMIT 1", $conn_hd));
			
		list($oferta_wyslana1)=mysql_fetch_array(mysql_query("SELECT zgl_szcz_oferta_wyslana FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$last_nr) ORDER BY zgl_szcz_id DESC LIMIT 1", $conn_hd));
			
		list($AkceptacjaKosztow1)=mysql_fetch_array(mysql_query("SELECT zgl_szcz_akceptacja_kosztow FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$last_nr) ORDER BY zgl_szcz_id DESC LIMIT 1", $conn_hd));
			
		list($zam_wyslane1)=mysql_fetch_array(mysql_query("SELECT zgl_szcz_zamowienie_wyslane FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$last_nr) ORDER BY zgl_szcz_id DESC LIMIT 1", $conn_hd));
			
		$zam_wyslane = $zam_wyslane1;
		if ($_REQUEST[ZamowienieWyslaneInput]!='') $zam_wyslane = $_REQUEST[ZamowienieWyslaneInput];
		//if ($_REQUEST[ZamowienieWyslaneInput]=='on') { $zam_wyslane=1; } else $zam_wyslane=0;

		$oferta_wyslana = $oferta_wyslana1;
		if ($_REQUEST[OfertaWyslanaInput]!='') $oferta_wyslana = $_REQUEST[OfertaWyslanaInput];
		//if ($_REQUEST[OfertaWyslanaInput]=='on') { $oferta_wyslana=1; } else $oferta_wyslana=0;
		
		$osoba_przypisana = $currentuser;
		//if ($_REQUEST[SelectZmienStatus]=='7') $osoba_przypisana = '';

		
		$akceptacja_kosztow = $AkceptacjaKosztow1;
		if ($_REQUEST[SelectZgoda]=='1') $akceptacja_kosztow=1; 
		if ($_REQUEST[SelectZgoda]=='0') $akceptacja_kosztow=0; 
		
		$przejechane_km = $_REQUEST[km];
		if (($_REQUEST[km]==0) || ($_REQUEST[km]=='')) $przejechane_km = 0;

		$awaria_z_przesunieciem=0;

		$Zdiagnozowany = $Zdiagnozowany1;
		if (($_REQUEST[SelectZmienStatus]=='3') || ($_REQUEST[SelectZmienStatus]=='7') || ($_REQUEST[SelectZmienStatus]=='3A') || ($_REQUEST[SelectZmienStatus]=='3B')) $Zdiagnozowany = $_REQUEST[SelectZdiagnozowany];
		
		$AkceptacjaKosztow = $AkceptacjaKosztow1;
		if ($_REQUEST[SelectAkceptacjaKosztow]!='') $AkceptacjaKosztow = $_REQUEST[SelectAkceptacjaKosztow];
		
		$wykonane_czynnosci = $_REQUEST[zs_wcz];
		
		if (($Zdiagnozowany=='1') && ($_REQUEST[hd_diagnoza_uwagi]!='') && ($_REQUEST[DodajDiagnozeDoWykonanychCzynnosci]=='on')) {
			$wykonane_czynnosci .= '<hr />Szczegóły diagnozy:<br />'.$_REQUEST[hd_diagnoza_uwagi];
		}
			
		if ($_REQUEST[WieleOsobCheck]=='on') {
			// lista dodatkowych osób wykonujących krok
			$DodatkoweOsoby = nl2br2($_REQUEST[lista_osob]);
			//$jeden_dodatkowy = explode(", ", $DodatkoweOsoby);
			$DodatkoweOsoby = str_replace(';', ',', $DodatkoweOsoby);
			$ile_dodatkowych = substr_count($DodatkoweOsoby,', ')-1;
		} else $DodatkoweOsoby = '';

		if (($_REQUEST[JakoInnaOsoba]=='on') && ($_REQUEST[zsjiu]!='')) {
			$osoba_przypisana = $_REQUEST[zsjiu];
		}
	
		// przelicz czas ropoczęcia kroku (czas zakończenia kroku - czas wykonywania)
		$newStartDate = SubMinutesFromDate($d_cw, $_REQUEST[zs_data]." ".$_REQUEST[zs_time]);

		$rozwiazany_problem = $_REQUEST[rozwiazany];
		// zamykając zgłoszenie - problem uznajemy za rozwiązany
		if ($_REQUEST[SelectZmienStatus]=='9') $rozwiazany_problem = 1;

		$sql = "INSERT INTO $dbname_hd.hd_zgloszenie_szcz VALUES ('',$last_nr,'$zgl_seryjme_unique_nr',$last_nr_kroku,'$czas_START_STOP',$d_cw,'$newStartDate','$_REQUEST[SelectZmienStatus]','$wykonane_czynnosci','$osoba_przypisana','$DodatkoweOsoby',0,'$oferta_wyslana','$zam_wyslane',$bylwyjazd,'$dddd','$currentuser',1,$przejechane_km,$awaria_z_przesunieciem,'','','$Zdiagnozowany','$AkceptacjaKosztow','$_REQUEST[hdnrzglgdansk]','','$_REQUEST[hdds]',$rozwiazany_problem,$d_cz_wyjazdu,$es_filia)";
		//echo "<br />$sql";

		$result = mysql_query($sql, $conn_hd) or die($k_b);
		
		// zaktualizuj zgl_czy_rozwiazany_problem w zgłoszeniu 
		$czy_rozwiazany_data = '';
		if ($rozwiazany_problem==1) $czy_rozwiazany_data = "".$_REQUEST[zs_data]." ".$_REQUEST[zs_time].":00";

		$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_czy_rozwiazany_problem='$rozwiazany_problem', zgl_czy_rozwiazany_problem_data='$czy_rozwiazany_data' WHERE ((zgl_nr='$last_nr')) LIMIT 1";
		$result = mysql_query($sql, $conn_hd) or die($k_b);
			
		$NowyStatusZgloszenia=$_REQUEST[SelectZmienStatus];
		
		$r3 = mysql_query("SELECT zgl_szcz_id FROM $dbname_hd.hd_zgloszenie_szcz WHERE ((zgl_szcz_unikalny_numer='$zgl_seryjme_unique_nr') and (zgl_szcz_osoba_wykonujaca_krok='$currentuser')) ORDER BY zgl_szcz_id DESC LIMIT 1", $conn_hd) or die($k_b);

		list($last_nr_szcz)=mysql_fetch_array($r3);

/*		
		nowalinia();
		echo "1. $_REQUEST[kat_id]<br />";
		echo "2. $_REQUEST[podkat_id]<br />";
		echo "3. $_REQUEST[SelectZmienStatus]<br />";
		echo "4. $_REQUEST[numerzgloszenia]<br />";	
		echo "5. $_REQUEST[numerzgloszenia1]<br />";	
*/		
		// zapisanie awarii WAN do bazy
		
		$_zgl_DZS = $_REQUEST[zs_data]." ".$_REQUEST[zs_time];
		
		if ((($_REQUEST[kat_id]=='2') || ($_REQUEST[kat_id]=='6')) && ($_REQUEST[podkat_id]=='0') && ($_REQUEST[SelectZmienStatus]=='3A') && ($_REQUEST[numerzgloszenia]!='')) {
		
			if (($_REQUEST[awaria_update]!='-')) {
			
				if ($_REQUEST[old_numerzgloszenia]!=$_REQUEST[numerzgloszenia]) {
					
					// zamknij poprzednią awarię
					$sql_t="UPDATE $dbname.serwis_awarie SET awaria_datazamkniecia  = '$_REQUEST[zs_data] $_REQUEST[zs_time]', awaria_osobazamykajaca = '$osoba_przypisana', awaria_status = '1' WHERE ((awaria_nrzgloszenia  = '$_REQUEST[old_numerzgloszenia]') and (awaria_status = '0'))";
					$result_t = mysql_query($sql_t, $conn) or die($k_b);
					
					// uaktualnij zgłoszenie HD
					$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_poledodatkowe1='$_REQUEST[numerzgloszenia]', zgl_data_zmiany_statusu='$_zgl_DZS' WHERE ((zgl_id='$_REQUEST[id]')) LIMIT 1";
					$result = mysql_query($sql, $conn_hd) or die($k_b);
					
					// dodaj nową awarię
					$sql_t = "INSERT INTO $dbname.serwis_awarie VALUES ('', '$_REQUEST[up_nazwa1]','$_REQUEST[up_wanport]','$_REQUEST[zs_data] $_REQUEST[zs_time]','','$_REQUEST[numerzgloszenia]','$_REQUEST[up_ip1]','$osoba_przypisana','','0',$es_filia)";
				} else {
					$sql_t = "UPDATE $dbname.serwis_awarie SET awaria_nrzgloszenia='$_REQUEST[numerzgloszenia]' WHERE awaria_id = $_REQUEST[awaria_update] LIMIT 1";
				}				
				
			} else {
				$sql_t = "INSERT INTO $dbname.serwis_awarie VALUES ('', '$_REQUEST[up_nazwa1]','$_REQUEST[up_wanport]','$_REQUEST[zs_data] $_REQUEST[zs_time]','','$_REQUEST[numerzgloszenia]','$_REQUEST[up_ip1]','$osoba_przypisana','','0',$es_filia)";
			}
			
			$result_t = mysql_query($sql_t, $conn) or die($k_b);			
			
			// zaktualizuj poledodatkowe1 (awaria wan) w zgłoszeniu 
				$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_poledodatkowe1='$_REQUEST[numerzgloszenia]',zgl_data_zmiany_statusu='$_zgl_DZS' WHERE ((zgl_id='$_REQUEST[id]')) LIMIT 1";
				$result = mysql_query($sql, $conn_hd) or die($k_b);

		}
		// koniec zapisywania awarii WAN do bazy

		// zamknięcie awarii WAN
		if ((($_REQUEST[kat_id]=='2') || ($_REQUEST[kat_id]=='6')) && ($_REQUEST[podkat_id]=='0') && ($_REQUEST[SelectZmienStatus]=='9') && ($_REQUEST[numerzgloszenia1]!='')) {
		
			$sql_t="UPDATE $dbname.serwis_awarie SET awaria_datazamkniecia  = '$_REQUEST[zs_data] $_REQUEST[zs_time]', awaria_osobazamykajaca = '$osoba_przypisana', awaria_status  = '1' WHERE ((awaria_nrzgloszenia  = '$_REQUEST[numerzgloszenia1]') and (awaria_status = '0')) LIMIT 1";

			$result_t = mysql_query($sql_t, $conn) or die($k_b);
		}
		// koniec zamykania awarii WAN		
	
			// *************************
				$_SESSION[zgloszenie_szcz_dodano]='poprawnie';
			// *************************


		// jeżeli wybrano sprzęt do wymiany podzespołów
		if (($_SESSION['wybrany_sprzet_do_wymiany_dla_zgl_nr_'.$_REQUEST[id].'']==1) && (($NowyStatusZgloszenia=='3') || ($NowyStatusZgloszenia=='7') || ($NowyStatusZgloszenia=='9') || ($NowyStatusZgloszenia=='6') || ($NowyStatusZgloszenia=='3B'))) {

			
			$wp_sprzet_opis = $_SESSION['typ_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].'']." ".$_SESSION['model_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].''];
			$wp_sprzet_sn = $_SESSION['sn_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].''];
			$wp_sprzet_ni = $_SESSION['ni_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].''];
			$wp_sprzet_wc = $_SESSION['wc_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].''];
			//$wp_sprzet_fszid = $_REQUEST['wp_fszcz_id'];
			
			if ($_SESSION['podzespoly_do_zgl_nr_'.$_REQUEST[id].'']=='magazyn') $wp_sprzet_z_magazynu = 1;
			if ($_SESSION['podzespoly_do_zgl_nr_'.$_REQUEST[id].'']=='zestaw') $wp_sprzet_z_magazynu = 1;
			if ($_SESSION['podzespoly_do_zgl_nr_'.$_REQUEST[id].'']=='typ') $wp_sprzet_z_magazynu = 0;

			$cnt = $_SESSION['podzespoly_do_zgl_nr_'.$_REQUEST[id].'_count'];
			
			if ($_SESSION['podzespoly_do_zgl_nr_'.$_REQUEST[id].'']=='magazyn') {
				$jeden_towar = explode("|#|", $_SESSION['faktura_szcz_id_for_zgloszenie_nr_'.$_REQUEST[id].'']);
				
				for ($i=0; $i<$cnt; $i++) { 
					$wp_sprzet_fszid = $jeden_towar[$i];
					
					$wp_typ_podzespolu = '';
					
					$sql_wp = "INSERT INTO $dbname_hd.hd_zgl_wymiany_podzespolow VALUES ('',$last_nr,'','$dddd','$wp_sprzet_opis','$wp_sprzet_sn','$wp_sprzet_ni',$wp_sprzet_z_magazynu,'$wp_typ_podzespolu','$wp_sprzet_wc','$wp_sprzet_fszid','1','$zgl_seryjme_unique_nr',0,'','',$es_filia)";
					
					$result_wp = mysql_query($sql_wp, $conn_hd) or die($k_b);				
				}
			}

			if ($_SESSION['podzespoly_do_zgl_nr_'.$_REQUEST[id].'']=='zestaw') {
				$jeden_towar = explode("|#|", $_SESSION['faktura_szcz_id_for_zgloszenie_nr_'.$_REQUEST[id].'']);
				
				for ($i=0; $i<$cnt; $i++) { 
					$wp_sprzet_fszid = $jeden_towar[$i];
					
					$wp_typ_podzespolu = '';
					
					$sql_wp = "INSERT INTO $dbname_hd.hd_zgl_wymiany_podzespolow VALUES ('',$last_nr,'','$dddd','$wp_sprzet_opis','$wp_sprzet_sn','$wp_sprzet_ni',$wp_sprzet_z_magazynu,'$wp_typ_podzespolu','$wp_sprzet_wc','$wp_sprzet_fszid','1','$zgl_seryjme_unique_nr',0,'','',$es_filia)";
					
					$result_wp = mysql_query($sql_wp, $conn_hd) or die($k_b);				
				}
			}
			
			if ($_SESSION['podzespoly_do_zgl_nr_'.$_REQUEST[id].'']=='typ') {
				$jeden_typ = explode("|#|", $_SESSION['typ_podzespolu_for_zgloszenie_nr_'.$_REQUEST[id].'']);
				
				for ($i=0; $i<$cnt; $i++) { 
					$wp_sprzet_fszid = 0;
					
					$wp_typ_podzespolu = $jeden_typ[$i];
					$sql_wp = "INSERT INTO $dbname_hd.hd_zgl_wymiany_podzespolow VALUES ('',$last_nr,'','$dddd','$wp_sprzet_opis','$wp_sprzet_sn','$wp_sprzet_ni',$wp_sprzet_z_magazynu,'$wp_typ_podzespolu','$wp_sprzet_wc','$wp_sprzet_fszid','1','$zgl_seryjme_unique_nr',0,'','',$es_filia)";
					
					$result_wp = mysql_query($sql_wp, $conn_hd) or die($k_b);				
				}
			}
			
			// uaktualnij tabelę ze zgłoszeniami o znacznik, że zgl jest powiązane z wymianą podzespołów
			$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_czy_powiazane_z_wymiana_podzespolow=1 WHERE ((zgl_widoczne=1) and (zgl_id='$_REQUEST[id]')) LIMIT 1";
			$result = mysql_query($sql, $conn_hd) or die($k_b);	
			
			// wyczyść zmienne $_SESSION
			unset($_SESSION['faktura_szcz_id_for_zgloszenie_nr_'.$_REQUEST[id].'']);
			unset($_SESSION['typ_podzespolu_for_zgloszenie_nr_'.$_REQUEST[id].'']);
			unset($_SESSION['podzespoly_do_zgl_nr_'.$_REQUEST[id].'']);
			unset($_SESSION['podzespoly_do_zgl_nr_'.$_REQUEST[id].'_count']);
			unset($_SESSION['wybrany_sprzet_do_wymiany_dla_zgl_nr_'.$_REQUEST[id].'']);
			unset($_SESSION['typ_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].'']);
			unset($_SESSION['model_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].'']);
			unset($_SESSION['sn_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].'']);
			unset($_SESSION['ni_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].'']);
			unset($_SESSION['wc_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].'']);
	
		} else {
			// wyczyść zmienne $_SESSION
			unset($_SESSION['faktura_szcz_id_for_zgloszenie_nr_'.$_REQUEST[id].'']);
			unset($_SESSION['typ_podzespolu_for_zgloszenie_nr_'.$_REQUEST[id].'']);
			unset($_SESSION['podzespoly_do_zgl_nr_'.$_REQUEST[id].'']);
			unset($_SESSION['podzespoly_do_zgl_nr_'.$_REQUEST[id].'_count']);
			unset($_SESSION['wybrany_sprzet_do_wymiany_dla_zgl_nr_'.$_REQUEST[id].'']);
			unset($_SESSION['typ_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].'']);
			unset($_SESSION['model_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].'']);
			unset($_SESSION['sn_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].'']);
			unset($_SESSION['ni_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].'']);
			unset($_SESSION['wc_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].'']);		
		}
			
		// jeżeli był wyjazd (START)
		$d_km = 0;
		if (($_REQUEST[km]!='') && ($_REQUEST[hd_wyjazd_rp]=='P')) $d_km=$_REQUEST[km];
			
		if ($bylwyjazd==1) {
		
			$trasaw = $_REQUEST[trasa];
			$wdata = $_REQUEST[hd_wyjazd_data];
			
			if ($_REQUEST[hd_wyjazd_rp]=='S') {
				$trasaw = 'wyjazd samochodem służbowym';
				//$wdata = $_REQUEST[zs_data];
			}
	
			$sql = "INSERT INTO $dbname_hd.hd_zgloszenie_wyjazd VALUES ('','$zgl_seryjme_unique_nr','$wdata','$trasaw',$d_km,'$osoba_przypisana','$_REQUEST[hd_wyjazd_rp]',1,$d_cz_wyjazdu,$es_filia)";
			//echo "<br />$sql";
			if ($TrybDebugowania==1) ShowSQL($sql);	
			
			$result = mysql_query($sql, $conn_hd) or die($k_b);
		}
		
		// uaktualnij tabelę ze zgłoszeniami o wartość statusu rozwiązany
		if ($rozwiazany_problem=='1') {
			$czy_rozwiazany_data = '';
			if ($rozwiazany_problem==1) $czy_rozwiazany_data = $_REQUEST[zs_data];
		
			$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_czy_rozwiazany_problem='1', zgl_czy_rozwiazany_problem_data='$czy_rozwiazany_data' WHERE ((zgl_id='$_REQUEST[id]')) LIMIT 1";
			if ($TrybDebugowania==1) ShowSQL($sql);				
			$result = mysql_query($sql, $conn_hd) or die($k_b);
		}
		
		// jeżeli był wyjazd (STOP)

	// 	uaktualnij osobę przypisaną -w przypadku statusu nr 7 (rozp. nie zakończone)
	//	if ($_REQUEST[SelectZmienStatus]=='7') {
	//	$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_osoba_przypisana='' WHERE ((zgl_widoczne=1) and (belongs_to='$es_filia') and (zgl_id='$_REQUEST[id]')) LIMIT 1";
	//	$result = mysql_query($sql, $conn_hd) or die($k_b);
	//	}
	
	// zaktualizuj status w zgłoszeniu 
		$NowyStatusZgloszenia=$_REQUEST[SelectZmienStatus];
		// jeżeli wybrano zmianę priorytetu zgłoszenia $_REQUEST[SelectZmienStatus]=8
		if ($_REQUEST[SelectZmienStatus]=='8') $NowyStatusZgloszenia='3';
		
		$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_osoba_przypisana='$osoba_przypisana', zgl_status='$NowyStatusZgloszenia',zgl_data_zmiany_statusu='$_zgl_DZS' WHERE ((zgl_widoczne=1) and (zgl_id='$_REQUEST[id]')) LIMIT 1";
		$result = mysql_query($sql, $conn_hd) or die($k_b);
	
		if ($_REQUEST[SelectZmienStatus]=='8') {
			// przelicz czas zakończenia zgłoszenia
			$d1 = $_REQUEST[zs_data];
			//$DataZakonczenia = AddHoursToDate("8",$DataGodzinaWpisu).":00";
			
			$ilosc_dni_do_zakonczenia_zgloszenia = $_REQUEST[zs_charakter_awarii];
			if (($ilosc_dni_do_zakonczenia_zgloszenia!='2') && 
				($ilosc_dni_do_zakonczenia_zgloszenia!='5') && 
				($ilosc_dni_do_zakonczenia_zgloszenia!='14')) $ilosc_dni_do_zakonczenia_zgloszenia='14';
				
			$DataZakonczenia = AddWorkingDays("".$ilosc_dni_do_zakonczenia_zgloszenia."","".$d1."") ." 07:00:00";

			$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_priorytet='2', zgl_data_zakonczenia='$DataZakonczenia' WHERE ((zgl_widoczne=1) and  (zgl_id='$_REQUEST[id]')) LIMIT 1";
			$result = mysql_query($sql, $conn_hd) or die($k_b);		
		}
	
	// zaktualizuj czas wykonywania w zgłoszeniu
	
		$r3 = mysql_query("SELECT zgl_razem_czas FROM $dbname_hd.hd_zgloszenie WHERE ((zgl_id='$_REQUEST[id]') and (zgl_widoczne=1)) LIMIT 1", $conn_hd) or die($k_b);
		list($razem_czas)=mysql_fetch_array($r3);
		$razem_czas += $d_cw;
		
		if ($_REQUEST[WieleOsobCheck]=='on') {
			// lista dodatkowych osób wykonujących krok
			//$DodatkoweOsoby = nl2br2($_REQUEST[lista_osob]);
			//$jeden_dodatkowy = explode(", ", $DodatkoweOsoby);
			//$ile_dodatkowych = substr_count($DodatkoweOsoby,', ')-1;
			$ile_dodatkowych++;
			$dodaj_czas_osob_dodatkowych = $d_cw * $ile_dodatkowych;
			$razem_czas+=$dodaj_czas_osob_dodatkowych;
		}
	
		$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_razem_czas=$razem_czas WHERE ((zgl_widoczne=1) and (zgl_id='$_REQUEST[id]')) LIMIT 1";
		//echo "$d_cw<br />$dodaj_czas_osob_dodatkowych<br />$ile_dodatkowych";
		//echo "<br />$sql";
		$result = mysql_query($sql, $conn_hd) or die($k_b);
	
	// zaktualizuj km w zgłoszeniu

		$r3 = mysql_query("SELECT zgl_razem_km FROM $dbname_hd.hd_zgloszenie WHERE ((zgl_id='$_REQUEST[id]') and (zgl_widoczne=1)) LIMIT 1", $conn_hd) or die($k_b);
		list($razem_km)=mysql_fetch_array($r3);
		if (($d_km!=0) && ($_REQUEST[km]!='')) $razem_km += $_REQUEST[km];
		
		$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_razem_km=$razem_km WHERE ((zgl_widoczne=1) and (zgl_id='$_REQUEST[id]')) LIMIT 1";
		$result = mysql_query($sql, $conn_hd) or die($k_b);

		// jeżeli zgłoszenie oznaczone jako wymagające wyjazdu

		if ($bylwyjazd==1) {		
			if ($_REQUEST[wymaga_wyjazdu]=='on') {
				$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_wymagany_wyjazd=1, zgl_wymagany_wyjazd_data_ustawienia='$czy_wymagany_wyjazd_data', zgl_wymagany_wyjazd_osoba_wlaczajaca='$czy_wymagany_wyjazd_osoba' WHERE ((zgl_widoczne=1) and (zgl_id='$_REQUEST[id]')) LIMIT 1";
				$result = mysql_query($sql, $conn_hd) or die($k_b);
			} else {
				$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_wymagany_wyjazd=0 WHERE ((zgl_widoczne=1) and (zgl_id='$_REQUEST[id]')) LIMIT 1";
				$result = mysql_query($sql, $conn_hd) or die($k_b);			
			}
		} else {
			if ($_REQUEST[wymaga_wyjazdu]=='on') {
				$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_wymagany_wyjazd=1, zgl_wymagany_wyjazd_data_ustawienia='$czy_wymagany_wyjazd_data', zgl_wymagany_wyjazd_osoba_wlaczajaca='$czy_wymagany_wyjazd_osoba' WHERE ((zgl_widoczne=1) and (zgl_id='$_REQUEST[id]')) LIMIT 1";
				$result = mysql_query($sql, $conn_hd) or die($k_b);
			} else {
				$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_wymagany_wyjazd=0 WHERE ((zgl_widoczne=1) and (zgl_id='$_REQUEST[id]')) LIMIT 1";
				$result = mysql_query($sql, $conn_hd) or die($k_b);	
			}
			
			if ($_REQUEST[SelectZmienStatus]=='9') {
				$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_wymagany_wyjazd=0 WHERE ((zgl_widoczne=1) and (zgl_id='$_REQUEST[id]')) LIMIT 1";
				$result = mysql_query($sql, $conn_hd) or die($k_b);					
			}
		}

	// aktualizacja tableli z ofertami - START
		$potw_oferty=0;
		if ($_REQUEST[OfertaWyslanaInput]=='1') $potw_oferty = 1;
	
		if ($potw_oferty==1) {
			$sql = "UPDATE $dbname_hd.hd_zgloszenie_szcz SET zgl_szcz_oferta_wyslana=1 WHERE (zgl_szcz_id='$last_nr_szcz') LIMIT 1";
			$result = mysql_query($sql, $conn_hd) or die($k_b);
		
			$sql = "INSERT INTO $dbname_hd.hd_zgloszenie_oferty VALUES ('',$last_nr_szcz,'$_REQUEST[hd_oferta_data]','$_REQUEST[hd_oferta_numer]','$_REQUEST[hd_oferta_uwagi]',1,'$osoba_przypisana',$es_filia)";

			if ($TrybDebugowania==1) ShowSQL($sql);	
			
			$wynik = mysql_query($sql, $conn_hd);
		}
	// aktualizacja tableli z ofertami - STOP

		$potw_zam=0;
		if ($_REQUEST[ZamowienieWyslaneInput]=='1') $potw_zam = 1;

		if ($potw_zam==1) {
			$sql = "UPDATE $dbname_hd.hd_zgloszenie_szcz SET zgl_szcz_zamowienie_wyslane=1 WHERE (zgl_szcz_id='$last_nr_szcz') LIMIT 1";
			//echo "<br />$sql";
			
			$result = mysql_query($sql, $conn_hd) or die($k_b);
		
			$sql = "INSERT INTO $dbname_hd.hd_zgloszenie_zamowienia VALUES ('',$last_nr_szcz,'$_REQUEST[hd_zam_data]','$_REQUEST[hd_zam_numer]','$_REQUEST[hd_zam_uwagi]',1,'$osoba_przypisana',$es_filia)";
			
			if ($TrybDebugowania==1) ShowSQL($sql);	
			
			//echo "<br />$sql";
			$wynik = mysql_query($sql, $conn_hd);
		}

		// jeżeli zamykamy zgłoszenie - dodaj informacje o osobie potw. zamknięcie zgłoszenia
		if ($_REQUEST[SelectZmienStatus]=='9') {
			$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_osoba_potwierdzajaca_zamkniecie_zgloszenia='$_REQUEST[hd_opz]' WHERE ((zgl_id='$_REQUEST[id]')) LIMIT 1";
			$result = mysql_query($sql, $conn_hd) or die($k_b);
			
			// zasadność zgłoszenia
			$zz = 0;
			if ($_REQUEST[zasadne]=='TAK') $zz = '1';
			
			$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_zasadne='$zz' WHERE (zgl_id='$_REQUEST[id]') LIMIT 1";
			$result = mysql_query($sql, $conn_hd) or die($k_b);
		}
		// koniec 

		if ($akceptacja_kosztow==1) {
			$sql = "UPDATE $dbname_hd.hd_zgloszenie_szcz SET zgl_szcz_akceptacja_kosztow=1 WHERE (zgl_szcz_id='$last_nr_szcz') LIMIT 1";
			//echo "<br />$sql";
			$result = mysql_query($sql, $conn_hd) or die($k_b);
		}
		
		$r3 = mysql_query("SELECT zgl_kategoria, zgl_podkategoria FROM $dbname_hd.hd_zgloszenie WHERE ((zgl_id='$last_nr') and (zgl_widoczne=1)) LIMIT 1",$conn_hd) or die($k_b);
	
		list($zgl_kat, $zgl_podkat)=mysql_fetch_array($r3);

		if ((($zgl_kat=='2') || ($zgl_kat=='3') || ($zgl_kat=='6')) && (($zgl_podkat=='2') || ($zgl_podkat=='5') || ($zgl_podkat=='3') || ($zgl_podkat=='4') || ($zgl_podkat=='7') || ($zgl_podkat=='9') || ($zgl_podkat=='0'))) {
			$step = 0;
			//echo "$_REQUEST[naprawaidnaprawaid]";
			//echo "($_REQUEST[naprawaid])";
			
			if ($_SESSION['naprawa_id_for_zgloszenie_nr_'.$_REQUEST[id].'']>0) $_REQUEST[naprawaid]=$_SESSION['naprawa_id_for_zgloszenie_nr_'.$_REQUEST[id].''];
			$_SESSION['naprawa_id_for_zgloszenie_nr_'.$_REQUEST[id].'']=0;
			
			if ($_REQUEST[naprawaid]>0) {
				// powiazanie Helpdesk <-> Naprawy

				// zmiany w tabeli Naprawy
					$sql_t9="UPDATE $dbname.serwis_naprawa SET naprawa_hd_zgl_id = '$last_nr' WHERE ((naprawa_id = '$_REQUEST[naprawaid]')) LIMIT 1";
					$result = mysql_query($sql_t9, $conn) or die($k_b);
				
				// zmiany w tabeli ze zgłoszeniami
					$sql_t9="UPDATE $dbname_hd.hd_zgloszenie SET zgl_naprawa_id  = '$_REQUEST[naprawaid]' WHERE ((zgl_id = '$last_nr')) LIMIT 1";
					$result = mysql_query($sql_t9, $conn_hd) or die($k_b);

					$r3 = mysql_query("SELECT hdnp_id FROM $dbname_hd.hd_naprawy_powiazane WHERE ((hdnp_zgl_id='$last_nr') and (hdnp_naprawa_id='$_REQUEST[naprawaid]') and (hdnp_widoczne=1)) LIMIT 1",$conn_hd) or die($k_b);
					list($jest_juz_powiazane)=mysql_fetch_array($r3);
		
					if ($jest_juz_powiazane>0) { 
					
					} else {
					// dodanie rekordu w tabeli hd_naprawy_powiazane
						$sql = "INSERT INTO $dbname_hd.hd_naprawy_powiazane VALUES ('',$last_nr,'$_REQUEST[naprawaid]','$dddd','$osoba_przypisana',9,9,9,9,9,9,9,1,'$zgl_seryjme_unique_nr',$es_filia)";

						//echo "INSERT SQL = ".$sql;
						
						if ($TrybDebugowania==1) ShowSQL($sql);	
						$wynik11 = mysql_query($sql, $conn_hd);	
					}
					$step = 1;
			} else {
					// zmiany w tabeli ze zgłoszeniami
					$sql_t9="UPDATE $dbname_hd.hd_zgloszenie SET zgl_naprawa_id  = '-1' WHERE ((zgl_id = '$last_nr')) LIMIT 1";
					$result = mysql_query($sql_t9, $conn_hd) or die($k_b);			

					$r3 = mysql_query("SELECT hdnp_id FROM $dbname_hd.hd_naprawy_powiazane WHERE ((hdnp_zgl_id='$last_nr') and (hdnp_naprawa_id='-1') and (hdnp_widoczne=1)) LIMIT 1",$conn_hd) or die($k_b);
					list($jest_juz_powiazane)=mysql_fetch_array($r3);
		
					if ($jest_juz_powiazane>0) { 
					
					} else {
						// dodanie rekordu w tabeli hd_naprawy_powiazane
						$sql = "INSERT INTO $dbname_hd.hd_naprawy_powiazane VALUES ('',$last_nr,'-1','$dddd','$osoba_przypisana',9,9,9,9,9,9,9,1,'$zgl_seryjme_unique_nr',$es_filia)";

						//echo "INSERT SQL2 = ".$sql;
						if ($TrybDebugowania==1) ShowSQL($sql);	
						$wynik11 = mysql_query($sql, $conn_hd);				
						$step = 0;
					
					}
			}
		
		$r3 = mysql_query("SELECT zgl_naprawa_id FROM $dbname_hd.hd_zgloszenie WHERE ((zgl_id='$last_nr') and (zgl_widoczne=1)) LIMIT 1", $conn_hd) or die($k_b);
		list($_naprawa_id)=mysql_fetch_array($r3);
		
		$_ss_id = $_REQUEST[tssid];
		
		//echo "_NAPRAWA ID = ".$_naprawa_id;
		//echo "_REQUEST[naprawaid] = ".$_REQUEST[naprawaid];
			

			// uaktualnij tabelę z powiązaniami Helpdesk <-> Naprawy
		/*	$Zdiagnozowany = 9;
			$oferta_wyslana = 9;
			$AkceptacjaKosztow = 9;
			$zam_wyslane = 9;
			$zam_zrealizowane = 9;
			$do_oddania = 9;
			$naprawa_zakonczona = 9;
	*/
	
			// ostatni stan paramatru: zdiagnozowany
/*			list($Zdiagnozowany)=mysql_fetch_array(mysql_query("SELECT zgl_szcz_zdiagnozowany FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$last_nr) ORDER BY zgl_szcz_id DESC LIMIT 1", $conn_hd));
			
			list($oferta_wyslana)=mysql_fetch_array(mysql_query("SELECT zgl_szcz_oferta_wyslana FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$last_nr) ORDER BY zgl_szcz_id DESC LIMIT 1", $conn_hd));
			
			list($AkceptacjaKosztow)=mysql_fetch_array(mysql_query("SELECT zgl_szcz_akceptacja_kosztow FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$last_nr) ORDER BY zgl_szcz_id DESC LIMIT 1", $conn_hd));
			
			list($zam_wyslane)=mysql_fetch_array(mysql_query("SELECT zgl_szcz_zamowienie_wyslane FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$last_nr) ORDER BY zgl_szcz_id DESC LIMIT 1", $conn_hd));
	
*/

			$Zdiagnozowany = $_REQUEST[SelectZdiagnozowany];
			$oferta_wyslana = $_REQUEST[OfertaWyslanaInput];
			$AkceptacjaKosztow = $_REQUEST[SelectAkceptacjaKosztow];
			$zam_wyslane = $_REQUEST[ZamowienieWyslaneInput];
			
			if ($_REQUEST[ZamowienieZrealizowaneInput]!='') $zam_zrealizowane = $_REQUEST[ZamowienieZrealizowaneInput];
			// do oddania
			//echo ">>>>>$_REQUEST[GotowyDoOddania]";
			
			$do_oddania = 9;
			if ($NowyStatusZgloszenia=='6') $do_oddania = 1;
			if (($_REQUEST[GotowyDoOddaniaInput]!=9) && ($_REQUEST[GotowyDoOddaniaInput]!='')) $do_oddania = $_REQUEST[GotowyDoOddaniaInput];
			
			$naprawa_zakonczona = 9;
			if ($NowyStatusZgloszenia=='9') $naprawa_zakonczona = 1;

			$sql_t9="UPDATE $dbname_hd.hd_naprawy_powiazane SET hdnp_zdiagnozowany  = '$Zdiagnozowany', hdnp_oferta_wyslana = '$oferta_wyslana', hdnp_akceptacja_kosztow = '$AkceptacjaKosztow', hdnp_zamowienie_wyslane = '$zam_wyslane', hdnp_zamowienie_zrealizowane = '$zam_zrealizowane', hdnp_gotowe_do_oddania = '$do_oddania', hdnp_naprawa_zakonczona = '$naprawa_zakonczona' WHERE (hdnp_zgl_id = '$last_nr') and (hdnp_widoczne=1) and (hdnp_naprawa_id=$_naprawa_id) LIMIT 1";
			
			//echo "UPDATE SQL = ".$sql_t9;
			$result = mysql_query($sql_t9, $conn_hd) or die($k_b);

			// jeżeli zdiagnozowany i wpisane szczegóły diagnozy => dodaj szczegóły diagnozy do uwag o naprawie
			if (($Zdiagnozowany=='1') && ($_REQUEST[hd_diagnoza_uwagi]!='') && ($_naprawa_id!='-1')) {
				list($napr_uwagi)=mysql_fetch_array(mysql_query("SELECT naprawa_uwagi FROM $dbname.serwis_naprawa WHERE (naprawa_id='$_naprawa_id') LIMIT 1", $conn));
				$dodana_diagnoza = $napr_uwagi ."<br />".$_REQUEST[hd_diagnoza_uwagi];
				$dodana_diagnoza=sanitize($dodana_diagnoza);
				$sql_a = "UPDATE $dbname.serwis_naprawa SET naprawa_uwagi='".nl2br($dodana_diagnoza)."', naprawa_uwagi_sa = '1' WHERE naprawa_id='$_naprawa_id' LIMIT 1";
				$ww = mysql_query($sql_a, $conn);
			}
		}

		?>
		<script>
			ClearCookie('wpisane_cw_m_<?php echo $_REQUEST[id]; ?>');
			ClearCookie('wpisane_wc_<?php echo $_REQUEST[id]; ?>');
			ClearCookie('wpisana_gzs_<?php echo $_REQUEST[id]; ?>');
			ClearCookie('nowy_status_<?php echo $_REQUEST[id]; ?>');
		</script>
		<?php
		// Uaktualnij czasy poszczególnych etapów w zgłoszeniu
		//echo "<span id=czasy></span>";
		?>
		<script>
			newWindow(600,50,'hd_o_zgloszenia_wylicz_czasy.php?nr=<?php echo $last_nr; ?>');
			//$("#czasy").load('hd_o_zgloszenia_wylicz_czasy.php?nr=<?php echo $last_nr; ?>&randval='+ Math.random());
		</script>
		<?php

		//	list($dRozpoczecia, $dZakonczenia, $kat, $kwt)=mysql_fetch_array(mysql_query("SELECT zgl_data_rozpoczecia, zgl_data_zakonczenia, zgl_kategoria, zgl_komorka_working_hours FROM $dbname_hd.hd_zgloszenie WHERE zgl_id='$_REQUEST[nr]' LIMIT 1"));
		
		//	UpdateStagesTime($last_nr,$es_filia,$kwt,$serwis_working_time);
		
		list($zgloszenie_kat)=mysql_fetch_array(mysql_query("SELECT zgl_kategoria FROM $dbname_hd.hd_zgloszenie WHERE (zgl_nr=$last_nr) LIMIT 1", $conn_hd));
		//echo $zgloszenie_kat;
		
		// jeżeli zamykamy zgłoszenie awarii krytycznej (pytanie o utworzenie nowego zgłoszenia awarii zwykłej)
		
		$addontext = '';
		if ($_REQUEST[tnaprawastatus]!='') $addontext = '<font color=red>Z uwagi na niezakończoną naprawę w zamykanym zgłoszeniu awarii ktycznej - zalecane jest utworzenie nowego zgłoszenia na bazie zamykanego.</font><br /><br />';
		
		if (($zgloszenie_kat==6) && ($_REQUEST[SelectZmienStatus]==9)) {
		?>
			<script>
				<?php if (($wymagane_protokoly_z_wyjazdow==1) && ($_REQUEST[ww]==1) && ($zgloszenie_kat!=1)) { ?>
						$.prompt('<?php echo $addontext;?>Czy wygenerować protokół potwierdzenia zakończenia awarii krytycznej dla zgłoszenia nr <?php echo $last_nr; ?> ?',{buttons:[{title: 'Tak',value:true},{title: 'Nie',value:false}], submit: function(v,m,f){ if (v==true) { $.prompt('<?php echo $addontext;?>Czy utworzyć nowe zgłoszenie <font color=red>Awarii zwykłej</font> lub <font color=red>Pracy  zleconej w ramach umowy</font>, będące kontynuacją zgłoszenia o numerze <?php echo $last_nr; ?> ?',{buttons:[{title: 'Tak',value:true},{title: 'Nie',value:false}], submit: function(v,m,f){ if (v==true) { self.location.href='hd_d_zgloszenie.php?stage=1&parent_zgl=<?php echo $last_nr;?>&naprawa_id=<?php echo $_naprawa_id; ?>&ss_id=<?php echo $_ss_id; ?>'; if (opener) opener.location.reload(true); } else { if (opener) opener.location.reload(true); self.close(); } } }); newWindow_r(800,600,'hd_potwierdzenie.php?id=<?php echo $last_nr;?>&nr=<?php echo $last_nr;?>&zgl_s='); } else { $.prompt('<?php echo $addontext;?>Czy utworzyć nowe zgłoszenie <font color=red>Awarii zwykłej</font> lub <font color=red>Pracy zleconej w ramach umowy</font>, będące kontynuacją zgłoszenia o numerze <?php echo $last_nr; ?> ?',{buttons:[{title: 'Tak',value:true},{title: 'Nie',value:false}], submit: function(v,m,f){ if (v==true) { self.location.href='hd_d_zgloszenie.php?stage=1&parent_zgl=<?php echo $last_nr;?>&naprawa_id=<?php echo $_naprawa_id; ?>&ss_id=<?php echo $_ss_id; ?>'; if (opener) opener.location.reload(true); } else { if (opener) opener.location.reload(true); self.close(); } } });} } });				
				<?php } else { ?>
					
					$.prompt('<?php echo $addontext;?>Czy utworzyć nowe zgłoszenie <font color=red>Awarii zwykłej</font> lub <font color=red>Pracy zleconej w ramach umowy</font>, będące kontynuacją zgłoszenia o numerze <?php echo $last_nr; ?> ?',{buttons:[{title: 'Tak',value:true},{title: 'Nie',value:false}], submit: function(v,m,f){ if (v==true) { self.location.href='hd_d_zgloszenie.php?stage=1&parent_zgl=<?php echo $last_nr;?>&naprawa_id=<?php echo $_naprawa_id; ?>&ss_id=<?php echo $_ss_id; ?>'; if (opener) opener.location.reload(true); } else { if (opener) opener.location.reload(true); self.close();	 } } });				
				<?php } ?>
				
			</script>
		<?php
		}

		// jeżeli zamykamy zgłoszenie awarii zwykłej (pytanie o utworzenie nowego zgłoszenia "prace zlecone w ramach umowy - priorytet: standard)
		
		$addontext = '';
		if ($_REQUEST[tnaprawastatus]!='') $addontext = '<font color=red>Z uwagi na niezakończoną naprawę w zamykanym zgłoszeniu awarii zwykłej - zalecane jest utworzenie nowego zgłoszenia na bazie zamykanego.</font><br /><br />';
		
		if (($zgloszenie_kat==2) && ($_REQUEST[SelectZmienStatus]==9)) {
		?>
			<script>				
				<?php if (($wymagane_protokoly_z_wyjazdow==1) && ($_REQUEST[ww]==1) && ($zgloszenie_kat!=1)) { ?>
						$.prompt('<?php echo $addontext;?>Czy wygenerować protokół potwierdzenia zakończenia awarii dla zgłoszenia nr <?php echo $last_nr; ?> ?',{buttons:[{title: 'Tak',value:true},{title: 'Nie',value:false}], submit: function(v,m,f){ if (v==true) { $.prompt('<?php echo $addontext;?>Czy utworzyć nowe zgłoszenie w kategorii <font color=red>Prace zlecone w ramach umowy</font>, będące kontynuacją zgłoszenia o numerze <?php echo $last_nr; ?> ?',{buttons:[{title: 'Tak',value:true},{title: 'Nie',value:false}], submit: function(v,m,f){ if (v==true) { self.location.href='hd_d_zgloszenie.php?stage=1&parent_zgl=<?php echo $last_nr;?>&naprawa_id=<?php echo $_naprawa_id; ?>&ss_id=<?php echo $_ss_id; ?>'; if (opener) opener.location.reload(true); } else { if (opener) opener.location.reload(true); self.close(); } } }); newWindow_r(800,600,'hd_potwierdzenie.php?id=<?php echo $last_nr;?>&nr=<?php echo $last_nr;?>&zgl_s='); } else { $.prompt('<?php echo $addontext;?>Czy utworzyć nowe zgłoszenie w kategorii <font color=red>Prace zlecone w ramach umowy</font>, będące kontynuacją zgłoszenia o numerze <?php echo $last_nr; ?> ?',{buttons:[{title: 'Tak',value:true},{title: 'Nie',value:false}], submit: function(v,m,f){ if (v==true) { self.location.href='hd_d_zgloszenie.php?stage=1&parent_zgl=<?php echo $last_nr;?>&naprawa_id=<?php echo $_naprawa_id; ?>&ss_id=<?php echo $_ss_id; ?>'; if (opener) opener.location.reload(true); } else { if (opener) opener.location.reload(true); self.close(); } } });} } });				
				<?php } else { ?>
					$.prompt('<?php echo $addontext;?>Czy utworzyć nowe zgłoszenie w kategorii <font color=red>Prace zlecone w ramach umowy</font>, będące kontynuacją zgłoszenia o numerze <?php echo $last_nr; ?> ?',{buttons:[{title: 'Tak',value:true},{title: 'Nie',value:false}], submit: function(v,m,f){ if (v==true) { self.location.href='hd_d_zgloszenie.php?stage=1&parent_zgl=<?php echo $last_nr;?>&naprawa_id=<?php echo $_naprawa_id; ?>&ss_id=<?php echo $_ss_id; ?>'; if (opener) opener.location.reload(true); } else { if (opener) opener.location.reload(true); self.close(); } } });
				<?php } ?>
				
			</script>
		<?php
		}	
		
		if ($_REQUEST[SelectZmienStatus]!=9) {
			?>
			<script>
				//SetCookie('byly_zmiany','1');				
				self.close();
				if (opener) opener.location.reload(true); 			
			</script>
			<?php
		}
		
		if (($zgloszenie_kat!=2) && ($zgloszenie_kat!=6) && ($_REQUEST[SelectZmienStatus]==9)) {
			?>
			<script>
				//opener.location.reload(true); 
				<?php if (($wymagane_protokoly_z_wyjazdow==1) && ($_REQUEST[ww]==1) && ($zgloszenie_kat!=1)) { ?>
					$.prompt('Czy wygenerować protokół potwierdzenia dla zgłoszenia nr <?php echo $last_nr; ?> ?',{buttons:[{title: 'Tak',value:true},{title: 'Nie',value:false}], submit: function(v,m,f){ if (v==true) { if (opener) opener.location.reload(true); self.location.href='hd_potwierdzenie.php?id=<?php echo $last_nr;?>&nr=<?php echo $last_nr;?>&zgl_s='; } else { if (opener) opener.location.reload(true); self.close(); } } });				
				<?php } else { ?>
					if (opener) opener.location.reload(true);
					//if (opener) opener.close();
					self.close();					
				<?php } ?>
				
			</script>
			<?php		
		}
		
		if ($_REQUEST[DodajDoBazyWiedzyChkBox]=='on') {
			$norefresh = 0;
			if ((($zgloszenie_kat==2) || ($zgloszenie_kat==6)) && ($_REQUEST[SelectZmienStatus]==9)) {
				$norefresh = 1;
			}
			?>
			<script>
				newWindow(700,500,'d_kb_pytanie.php?poziom=1&id=&opis=<?php echo urlencode($_REQUEST[zs_wcz]); ?>&temat=<?php echo urlencode($_REQUEST[temp_temat]); ?>&zgl_nr=<?php echo $_REQUEST[id]; ?>&norefresh=<?php echo $norefresh; ?>');
				//$("#czasy").load('hd_o_zgloszenia_wylicz_czasy.php?nr=<?php echo $last_nr; ?>&randval='+ Math.random());
			</script>
			<?php
		}
		
	} // if
	
} // if ($submit)

else  {
	
//print_r($_SESSION);

session_register('zgloszenie_szcz_dodano');
$_SESSION[zgloszenie_szcz_dodano]='nie';

if ($_REQUEST[clearcookies]=='1') {
	if (isset($_SESSION['refresh_o_zgloszenia_'.$_REQUEST[nr].''])) {
	} else {
		session_register('refresh_o_zgloszenia_'.$_REQUEST[nr].'');
		$_SESSION['refresh_o_zgloszenia_'.$_REQUEST[nr].'']=0;
	}
	
	if (isset($_SESSION['wybrany_sprzet_do_wymiany_dla_zgl_nr_'.$_REQUEST[nr].''])) {
	} else {
		session_register('wybrany_sprzet_do_wymiany_dla_zgl_nr_'.$_REQUEST[nr].'');
		$_SESSION['wybrany_sprzet_do_wymiany_dla_zgl_nr_'.$_REQUEST[nr].'']=0;
	}

	if (isset($_SESSION['naprawa_id_for_zgloszenie_nr_'.$_REQUEST[nr].''])) {
	} else { 
		session_register('naprawa_id_for_zgloszenie_nr_'.$_REQUEST[nr].'');
		$_SESSION['naprawa_id_for_zgloszenie_nr_'.$_REQUEST[nr].'']=0;
	}

	$_REQUEST[clearcookies] = 0;
}

echo "<div id=content>";
	
// weryfikacja aktywności dostępów czasowych dla wszystkich pracowników
	$aktualna_data = Date('Y-m-d H:i:s');
	$sql_dc = "UPDATE $dbname_hd.hd_dostep_czasowy SET dc_dostep_active=0 WHERE ((dc_dostep_active_to<'$aktualna_data') and (dc_dostep_active=1) and (belongs_to=$es_filia))";
	$result_dc = mysql_query($sql_dc, $conn_hd) or die($k_b);
// koniec weryfikacji aktywności dostępów czasowych
	
	$wynik = mysql_query("SELECT * FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id=$_REQUEST[id]) LIMIT 1", $conn_hd);
	while ($dane_f1=mysql_fetch_array($wynik)) {
		$temp2_zgl_nr		= $dane_f1['zgl_nr'];
		$temp_data			= $dane_f1['zgl_data'];
		$temp_godzina		= $dane_f1['zgl_godzina'];
		$temp_komorka		= $dane_f1['zgl_komorka'];
		$temp_osoba			= $dane_f1['zgl_osoba'];
		$temp_telefon		= $dane_f1['zgl_telefon'];
		$temp_poczta_nr		= $dane_f1['zgl_poczta_nr'];
		$temp_temat			= $dane_f1['zgl_temat'];
		$temp_tresc			= $dane_f1['zgl_tresc'];
		$temp_kategoria		= $dane_f1['zgl_kategoria'];
		$temp_podkategoria	= $dane_f1['zgl_podkategoria'];
		$temp_podkategoria2	= $dane_f1['zgl_podkategoria_poziom_2'];
		
		$temp_priorytet		= $dane_f1['zgl_priorytet'];
		$temp_osoba_przypisana	= $dane_f1['zgl_osoba_przypisana'];
		$temp_status		= $dane_f1['zgl_status'];	
		$temp_parent_zgl	= $dane_f1['zgl_kontynuacja_zgloszenia_numer'];
		$temp_naprawa_id	= $dane_f1['zgl_naprawa_id'];
		$temp_rekl_czy_jest = $dane_f1['zgl_czy_to_jest_reklamacyjne'];
		$temp_rekl_nr	 	= $dane_f1['zgl_nr_zgloszenia_reklamowanego'];
		$temp_rekl_czy_ma	= $dane_f1['zgl_czy_ma_zgl_reklamacyjne'];	
		$temp_czy_pow_z_wp	= $dane_f1['zgl_czy_powiazane_z_wymiana_podzespolow'];
		
		$temp_czy_ww 		= $dane_f1['zgl_wymagany_wyjazd'];
		$temp_czy_ww_data	= $dane_f1['zgl_wymagany_wyjazd_data_ustawienia'];
		$temp_czy_ww_osoba 	= $dane_f1['zgl_wymagany_wyjazd_osoba_wlaczajaca'];
		
		$temp_ss_id	= $dane_f1['zgl_sprzet_serwisowy_id'];
		
		$temp_data_roz		= $dane_f1['zgl_data_rozpoczecia'];
		$temp_data_zak		= $dane_f1['zgl_data_zakonczenia'];
	
		$temp_zgl_data_rozpoczecia		= $dane_f1['zgl_data_rozpoczecia'];
		$temp_zgl_data_zakonczenia		= $dane_f1['zgl_data_zakonczenia'];
		$temp_zgl_E1P		= $dane_f1['zgl_E1P'];
		$temp_zgl_E2P		= $dane_f1['zgl_E2P'];
		$temp_zgl_komorka_working_hours = $dane_f1['zgl_komorka_working_hours'];
		$temp_op			= $dane_f1['zgl_osoba_przypisana'];		
	}
	
	//pageheader("Zmiana statusu zgłoszenia nr $_GET[nr]");
	echo "<h4 style='padding:10px; font-weight:normal;'>";
	
	echo "<a id=PokazSzczZgl class=normalfont style='font-weight:normal' title=' Pokaż szczegóły zgłoszenia ' href=# onClick=\"$('#InformacjeOZgloszeniu').toggle(); $('#PokazSzczZgl').hide(); $('#UkryjSzczZgl').show(); \" >";
	echo "Zmiana statusu zgłoszenia nr <b>$_GET[nr]</b>";
	if ($czy_wyroznic_zgloszenia_seryjne==1) {
		if ($_GET[zgl_s]=='1') echo " [zgł. seryjne]";
	}	
	echo "&nbsp;z&nbsp;<b>".$temp_komorka."</b>";
	echo "&nbsp;<input type=image class=imgoption src=img/show_more_".$_REQUEST[tk].".gif>";	
	echo "</a>";

	?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();

	echo "<a id=UkryjSzczZgl style='display:none' class=normalfont style='font-weight:normal' title=' Ukryj szczegóły zgłoszenia ' href=# onClick=\"$('#InformacjeOZgloszeniu').toggle(); $('#PokazSzczZgl').show(); $('#UkryjSzczZgl').hide();\" >";	
	echo "Zmiana statusu zgłoszenia nr <b>$_GET[nr]</b>";
	if ($czy_wyroznic_zgloszenia_seryjne==1) {
		if ($_GET[zgl_s]=='1') echo " [zgł. seryjne]";
	}	
	echo "&nbsp;z&nbsp;<b>".$temp_komorka."</b>";
	echo "&nbsp;<input type=image class=imgoption src=img/hide_more_".$_REQUEST[tk].".gif>";
	echo "</a>";
	
	//echo "<br /><p align=center style='margin-top:8px; margin-bottom:5px;'><font color=black>$temp_komorka</font></p>";
	
	echo "</h4>";
	echo "<div id=InformacjeOZgloszeniu style='display:none'>";

switch ($temp_kategoria) {
	case 2:	$kolorgrupy='#FF7F2A'; break; 
	case 6: $kolorgrupy='#F73B3B'; break;
	case 3:	$kolorgrupy=''; break; 			
	default: if ($temp_status==9) { $kolorgrupy='#FFFFFF'; } else { $kolorgrupy=''; } break; 
}
	$rx = mysql_query("SELECT zgl_szcz_czas_rozpoczecia_kroku,zgl_szcz_czas_wykonywania FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$temp2_zgl_nr') and (zgl_szcz_widoczne=1) ORDER BY zgl_szcz_nr_kroku DESC LIMIT 1", $conn_hd) or die($k_b);
	list($data_ostatniego_kroku,$czas_wykonywania_ostatniego_kroku)=mysql_fetch_array($rx);
	
		echo "<br />";
		echo "<table class=left width=auto style='background-color:transparent; border:0px solid;' cellspacing=1>";
		echo "<tr><td class=righttop width=150>Data zgłoszenia</td><td><b>$temp_data ".substr($temp_godzina,0,5)."</b>";
			echo "<span style='float:right'>";
				echo "<input class=buttons type=button onClick=\"newWindow_r(800,600,'hd_potwierdzenie.php?id=".$_REQUEST[nr]."&nr=".$_REQUEST[nr]."&pdata=".date('Y-m-d')."');\" style='font-weight:bold' value='Drukuj potwierdzenie' />";
			echo "</span>";			
		echo "</td></tr>";
		$r44 = mysql_query("SELECT up_id, up_ip, up_telefon FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((serwis_komorki.belongs_to=$es_filia) and (serwis_piony.pion_id=serwis_komorki.up_pion_id) and (CONCAT(serwis_piony.pion_nazwa,' ',serwis_komorki.up_nazwa)='$temp_komorka'))", $conn) or die($k_b);
		list($_upid,$_ip,$_tel)=mysql_fetch_array($r44);
		echo "<tr><td class=righttop>Komórka</td><td><a class=normalfont title='Szczegółowe informacje o $temp_komorka' href=# onClick=\"newWindow_r(700,600,'p_komorka_szczegoly.php?id=$_upid'); return false;\" ><b>".($temp_komorka)."</b></a>";
			echo "<span style='float:right'>";
				if ($_ip!='') echo "<input type=button class=buttons onclick=\"newWindow(500,200,'hd_p_pokaz_ip.php?komorkanazwa=".urlencode($temp_komorka)."');return false;\" value='Adres IP'/>";
				if ($_tel!='') echo "<input type=button class=buttons onclick=\"newWindow(500,200,'hd_p_pokaz_tel.php?komorkanazwa=".urlencode($temp_komorka)."');return false;\" value='Telefon'/>";
				if ($temp_zgl_komorka_working_hours!='') echo "<input type=button class=buttons title=\"Obowiązujące w momencie rejestracji zgłoszenia godziny pracy komórki\" onclick=\"newWindow(500,300,'hd_p_pokaz_godziny.php?komorkanazwa=".urlencode($temp_komorka)."&wa=".urlencode($temp_zgl_komorka_working_hours)."');return false;\" value='Zapisane w zgłoszeniu godziny pracy'/>";
			echo "</span>";		
		echo "</td></tr>";
		echo "<tr><td class=right>Osoba zgłaszająca</td><td><b>$temp_osoba</b>";
			if ($temp_telefon!='') echo "&nbsp;|&nbsp;Telefon kontaktowy: <b>".$temp_telefon."</b>";
		echo "</td></tr>";
		echo "<tr><td class=right>Temat</td><td><b>".nl2br($temp_temat)."</b></td></tr>";
		echo "<tr><td class=righttop>Treść</td><td><b>".nl2br($temp_tresc)."</b></td></tr>";
			$r1 = mysql_query("SELECT hd_kategoria_opis FROM $dbname_hd.hd_kategoria WHERE (hd_kategoria_nr='$temp_kategoria') LIMIT 1", $conn_hd) or die($k_b);
			list($kat_opis)=mysql_fetch_array($r1);
			$r2 = mysql_query("SELECT hd_podkategoria_opis FROM $dbname_hd.hd_podkategoria WHERE (hd_podkategoria_nr='$temp_podkategoria') LIMIT 1", $conn_hd) or die($k_b);
			list($podkat_opis)=mysql_fetch_array($r2);
		echo "<tr><td class=right>Kategoria</td><td><b>$kat_opis -> $podkat_opis</b></td></tr>";
		if ($temp_podkategoria2=='') $temp_podkategoria2='brak';
		echo "<tr><td class=right>Podkategoria (poziom 2)</td><td><b>$temp_podkategoria2</b></td></tr>";
		
	// informacje o czasach zgodnych z umową - POCZĄTEK
		$__zgl_data_r 	= $temp_data_roz;
		$__zgl_data_z	= $temp_data_zak;
		$__temp_zgl_E2P	= $temp_zgl_E2P;
		$__color		= FALSE;				// czy mają być na czerwono wyróżnione daty wyliczone przez system
		
		include("hd_o_zgloszenia_SLA_info.php");
		
	// informacje o czasach zgodnych z umową - KONIEC
		
		echo "</table>";
		echo "<br />";
	
	echo "</div>";
	
	// jeżeli zgłoszenie jest powiązane z innym zgłoszeniem
	if (($temp_parent_zgl!=0)) {
		$wynik_parent = mysql_query("SELECT zgl_data,zgl_godzina,zgl_komorka,zgl_osoba,zgl_telefon,zgl_poczta_nr,zgl_temat,zgl_tresc,zgl_kategoria,zgl_podkategoria, zgl_priorytet,zgl_osoba_przypisana,zgl_status,zgl_kontynuacja_zgloszenia_numer FROM $dbname_hd.hd_zgloszenie WHERE (zgl_nr=$temp_parent_zgl) LIMIT 1", $conn_hd);
		
		while ($dane_f1_parent=mysql_fetch_array($wynik_parent)) {
			$temp_data_parent				= $dane_f1_parent['zgl_data'];
			$temp_godzina_parent			= $dane_f1_parent['zgl_godzina'];
			$temp_komorka_parent			= $dane_f1_parent['zgl_komorka'];
			$temp_osoba_parent				= $dane_f1_parent['zgl_osoba'];
			$temp_telefon_parent			= $dane_f1_parent['zgl_telefon'];
			$temp_poczta_nr_parent			= $dane_f1_parent['zgl_poczta_nr'];
			$temp_temat_parent				= $dane_f1_parent['zgl_temat'];
			$temp_tresc_parent				= $dane_f1_parent['zgl_tresc'];
			$temp_kategoria_parent			= $dane_f1_parent['zgl_kategoria'];
			$temp_podkategoria_parent		= $dane_f1_parent['zgl_podkategoria'];
			$temp_priorytet_parent			= $dane_f1_parent['zgl_priorytet'];
			$temp_osoba_przypisana_parent	= $dane_f1_parent['zgl_osoba_przypisana'];
			$temp_status_parent				= $dane_f1_parent['zgl_status'];
			$temp_parent_zgl_parent			= $dane_f1_parent['zgl_kontynuacja_zgloszenia_numer'];
		}
		
		switch ($temp_kategoria_parent) {
			case 2:	if ($temp_priorytet_parent==2) { $kolorgrupy='#FF7F2A'; break; 
						}
					if ($temp_priorytet_parent==4) { $kolorgrupy='#F73B3B'; break; 
						}				
			case 3:	if ($temp_priorytet_parent==3) { $kolorgrupy='#FFAA7F'; break; 
						}
			default: if ($temp_status_parent==9) { $kolorgrupy='#FFFFFF'; break; 
						} else {
						$kolorgrupy='';
				}
		}
		
		echo "<h3 class=h3powiazane><center>Zgłoszenie numer <b>$_REQUEST[nr]</b> utworzono na bazie zgłoszenia nr <b>".$temp_parent_zgl."</b>&nbsp;";
		echo "&nbsp;<input class=buttons type=button onClick=\"self.location.href='hd_o_zgloszenia.php?action=obsluga&nr=$temp_parent_zgl&id=$temp_parent_zgl'\" value=' Przejdź do obsługi zgłoszenia nr ".$temp_parent_zgl." ' />";
		echo "</center></h3>";
		echo "<div id=parent_zgl_info style='display:none'>";
		echo "</div>";
		
		$sql88="SELECT zgl_czy_powiazane_z_wymiana_podzespolow FROM $dbname_hd.hd_zgloszenie WHERE (zgl_nr=$temp_parent_zgl) LIMIT 1";
		$result88 = mysql_query($sql88, $conn_hd) or die($k_b);
		list($temp_czy_pow_z_wp_parent)=mysql_fetch_array($result88);
	}
	
	$mstatus='';
	$pozwol_na_6_i_9 = true;
	
	if ($temp_naprawa_id>0) {
		//echo "Zgłoszenie powiązane jest z naprawą";
		
		$numer_zgloszenia = $_REQUEST[nr];
		if ($temp_parent_zgl==0) {
			// sprawdź czy z tego zgłoszenia nie zostało stworzone inne
			$sql88="SELECT zgl_nr FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kontynuacja_zgloszenia_numer=$_REQUEST[nr]) LIMIT 1";
			$result88 = mysql_query($sql88, $conn_hd) or die($k_b);
			
			list($numer_zgloszenia1)=mysql_fetch_array($result88);
			//$numer_zgloszenia = $temp_parent_zgl;
			if ($numer_zgloszenia1>0) $numer_zgloszenia = $numer_zgloszenia1;
		}
		
	//	echo "SELECT * FROM $dbname.serwis_naprawa WHERE naprawa_hd_zgl_id='$numer_zgloszenia' LIMIT 1";
		
		//echo "SELECT * FROM $dbname.serwis_naprawa WHERE naprawa_hd_zgl_id='$numer_zgloszenia' LIMIT 1";
		
		$result99 = mysql_query("SELECT * FROM $dbname.serwis_naprawa WHERE naprawa_hd_zgl_id='$numer_zgloszenia' LIMIT 1");
		while ($dane99 = mysql_fetch_array($result99)) {
			$mid 		= $dane99['naprawa_id'];					
			$mnazwa 	= $dane99['naprawa_nazwa'];
			$mmodel		= $dane99['naprawa_model'];			
			$msn 		= $dane99['naprawa_sn'];
			$mstatus 	= $dane99['naprawa_status'];
			$msz		= $dane99['naprawa_sprzet_zastepczy_id'];
			$mup		= $dane99['naprawa_pobrano_z'];
			$mni		= $dane99['naprawa_ni'];
			$mewid_id 	= $dane99['naprawa_ew_id'];
			$bt 		= $dane99['belongs_to'];	
			$msz		= $dane99['naprawa_sprzet_zastepczy_id'];
			$mnwif		= $dane99['naprawa_przekazanie_naprawy_do'];
			$mnwifpz	= $dane99['naprawa_przekazanie_zakonczone'];
			
			$move_naprawa = false;
			
		if ($mnwifpz==0) {	
			if (($bt!=$mnwif) && ($es_filia==$bt)) {
				$move_naprawa = false;
				$r40 = mysql_query("SELECT filia_nazwa FROM $dbname.serwis_filie WHERE (filia_id='$mnwif') LIMIT 1", $conn) or die($k_b);
				list($mnwif_NazwaFilii)=mysql_fetch_array($r40);		
			}
			if (($bt!=$mnwif) && ($es_filia==$mnwif)) {
				$move_naprawa = true;
			}
			if (($bt==$es_filia) && ($mnwif==0)) {
				$move_naprawa = true;
			}
						
			if ($move_naprawa==true) {
				$r40 = mysql_query("SELECT filia_nazwa FROM $dbname.serwis_filie WHERE (filia_id='$bt') LIMIT 1", $conn) or die($k_b);
				list($NazwaMojejFilii)=mysql_fetch_array($r40);
			}
		} else $move_naprawa = true;
			//echo ">>>>>".$msz;
			
			$result54 = mysql_query("SELECT up_id FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (up_active=1) and (belongs_to=$bt) and (serwis_komorki.up_nazwa = '$mup') LIMIT 1", $conn) or die($k_b);
			list($temp_upid)=mysql_fetch_array($result54);
		
			if ($es_m==1) {
				$sql5 = "SELECT up_id FROM $dbname.serwis_komorki WHERE (up_nazwa='$mup') LIMIT 1";
			} else {
				$sql5 = "SELECT up_id FROM $dbname.serwis_komorki WHERE up_nazwa='$mup' AND (belongs_to=$bt) LIMIT 1";
			}
			
			$wynik = mysql_query($sql5,$conn) or die($k_b);
			list($upid)=mysql_fetch_array($wynik);
			
			$result65 = mysql_query("SELECT sn_nazwa FROM $dbname.serwis_sposob_naprawy WHERE (sn_id='$mstatus') LIMIT 1", $conn) or die($k_b);
			list($mstatus_opis)=mysql_fetch_array($result65);
				
			//naprawaheader("<center><b>Zgłoszenie powiązane z naprawą</b><a title=' Pokaż szczegóły naprawy'><input class=imgoption type=image src=img/detail.gif onclick=\"newWindow(800,600,'p_naprawy_szczegoly.php?id=$mid'); return false;\"></a><br /><br /><b>".$mnazwa." ".$mmodel."</b> | SN: <b>".$msn."</b> | Status naprawy: <b>".$mstatus_opis."</b></center>");
		
			if ($msz>0) {
				$result99 = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni FROM $dbname.serwis_magazyn WHERE magazyn_id='$msz' LIMIT 1", $conn) or die($k_b);
				list($szid, $sznazwa, $szmodel, $szsn, $szni) = mysql_fetch_array($result99);
				
				naprawaheader("<center>Zgłoszenie powiązane z naprawą | status naprawy: <b>".$mstatus_opis."</b> | <input type=button class=buttons value='szczegóły naprawy' onclick=\"newWindow(800,600,'p_naprawy_szczegoly.php?id=$mid'); return false;\" /><br /><b>Sprzęt uszkodzony: </b>".$mnazwa." ".$mmodel." | SN: ".$msn." | NI: $mni<br /><b>Sprzęt zastępczy: </b>".$sznazwa." ".$szmodel." | SN: ".$szsn." | NI: ".$szni." | <input type=button class=buttons value='szczegóły' onclick=\"newWindow(800,600,'p_serwis_szczegoly.php?id=$_REQUEST[nr]&parentid=$temp_parent_zgl'); return false;\" /></center>");
				
				if ($move_naprawa==false) {
					naprawaheader2("<center>Realizacja naprawy przekazana do lokalizacji: <b>".$mnwif_NazwaFilii."</b><br />Zamknięcie zgłoszenia możliwe będzie po zwrocie sprzętu do Twojej lokalizacji.");
					$pozwol_na_6_i_9 = false;
				}
				
				if (($move_naprawa==true) && ($mnwif!=0) && ($mnwifpz==0)) {
					naprawaheader2("<center>Naprawiany sprzęt przekazany z lokalizacji: <b>".$NazwaMojejFilii."</b><br />Zamknięcie zgłoszenia możliwe jest przez osobę z filii przekazującej.");
					$pozwol_na_6_i_9 = false;
				}
				$temp_ss_id = $msz;
				
			} else {
				naprawaheader("<center>Zgłoszenie powiązane z naprawą | status naprawy: <b>".$mstatus_opis."</b> | <input type=button class=buttons value='szczegóły naprawy' onclick=\"newWindow(800,600,'p_naprawy_szczegoly.php?id=$mid'); return false;\" /><br /><b>Sprzęt uszkodzony: </b>".$mnazwa." ".$mmodel." | SN: ".$msn." | NI: $mni</center>");
				
				if ($move_naprawa==false) {
					naprawaheader2("<center>Realizacja naprawy przekazana do lokalizacji: <b>".$mnwif_NazwaFilii."</b><br />Zamknięcie zgłoszenia możliwe będzie po zwrocie sprzętu do Twojej lokalizacji."); 
					$pozwol_na_6_i_9 = false;
				}
				
				if (($move_naprawa==true) && ($mnwif!=0) && ($mnwifpz==0)) {
					naprawaheader2("<center>Naprawiany sprzęt przekazany z lokalizacji: <b>".$NazwaMojejFilii."</b><br />Zamknięcie zgłoszenia możliwe jest przez osobę z filii przekazującej.");
					$pozwol_na_6_i_9 = false;
				}
				
			}
					
			//naprawaheader("<center>Zgłoszenie powiązane z naprawą | status naprawy: <b>".$mstatus_opis."</b> | <input type=button class=buttons value='szczegóły naprawy' onclick=\"newWindow(800,600,'p_naprawy_szczegoly.php?id=$mid'); return false;\" /><br />".$mnazwa." ".$mmodel." | SN: ".$msn." | NI: $mni</center>");
		
		}
			
	}
	
	$pokaz_zwrot = false;
	if (($mnwif!=0) && ($mnwifpz==1) && ($es_filia==$mnwif)) $pozwol_na_6_i_9 = false;	
	if (($mnwif!=0) && ($mnwifpz==0) && ($es_filia==$mnwif)) $pokaz_zwrot = true;	
	
	if ($pozwol_na_6_i_9==true) {
		echo "<input type=hidden name=pn6i9 id=pn6i9 value='1'>";
	} else {
		echo "<input type=hidden name=pn6i9 id=pn6i9 value='0'>";
	}
			
	if (($temp_ss_id>0) && (($msz<0) || ($msz==''))) {
		$result99 = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni,magazyn_uwagi,magazyn_uwagi_sa,magazyn_status,belongs_to FROM $dbname.serwis_magazyn WHERE magazyn_id='$temp_ss_id' LIMIT 1", $conn) or die($k_b);
		list($mid, $mnazwa, $mmodel, $msn, $mni, $muwagi, $muwagisa,$mstatus,$bt) = mysql_fetch_array($result99);
		
		ssheader("<center>Zgłoszenie powiązane z przekazaniem sprzętu serwisowego | <input type=button class=buttons value='szczegóły' onclick=\"newWindow_r(800,600,'p_serwis_szczegoly.php?id=$nr&komorka=".urlencode($_REQUEST[komorka])."'); return false;\" /><br />".$mnazwa." ".$mmodel." | SN: ".$msn." | NI: $mni</center>");
	}
	
	if ($temp_czy_pow_z_wp_parent==1) {
		$sql_wp="SELECT wp_sprzet_opis, wp_sprzet_sn, wp_sprzet_ni FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_zgl_id=$temp_parent_zgl)  LIMIT 1";	
		$result_wp = mysql_query($sql_wp, $conn_hd) or die($k_b);

		list($wp_o,$wp_sn,$wp_ni)=mysql_fetch_array($result_wp);
		if ($wp_sn=='') $wp_sn='-';
		if ($wp_ni=='') $wp_ni='-';
		
		echo "<h5 class=h5yellow><b>Zgłoszenie powiązane z wymianą podzespołów (w zgłoszeniu nr $temp_parent_zgl) w:</b><br /><br /><b>$wp_o</b> | SN: <b>$wp_sn</b> | NI: <b>$wp_ni</b></h5>";	
	}
	
	// jeżeli zgłoszenie powiązane z wymianą podzespołów w komputerze klienta
	if ($temp_czy_pow_z_wp==1) {
		$sql_wp="SELECT wp_sprzet_opis, wp_sprzet_sn, wp_sprzet_ni FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_zgl_id=$_REQUEST[id]) LIMIT 1";	
		$result_wp = mysql_query($sql_wp, $conn_hd) or die($k_b);

		list($wp_o,$wp_sn,$wp_ni)=mysql_fetch_array($result_wp);
		if ($wp_sn=='') $wp_sn='-';
		if ($wp_ni=='') $wp_ni='-';
	
		//echo "<h5 class=h5yellow><b>Zgłoszenie powiązane z wymianą podzespołów w:</b><br /><br /><b>$wp_o</b> | SN: <b>$wp_sn</b> | NI: <b>$wp_ni</b></h5>";
		echo "<h5 class=h5yellow>Zgłoszenie powiązane z wymianą podzespołów w:<br />$wp_o | SN: $wp_sn | NI: $wp_ni<br /></h5>";
	}
	
	if ($temp_rekl_czy_jest==1) {
		echo "<h5 class=h5blue><center>Zgłoszenie numer <b>$_REQUEST[nr]</b> jest reklamacją zgłoszenia nr <b>".$temp_rekl_nr."</b>";
		echo "&nbsp;<input class=buttons type=button onClick=\"self.location.href='hd_o_zgloszenia.php?action=obsluga&nr=$temp_rekl_nr&id=$temp_rekl_nr'\" value=' Przejdź do zgłoszenia nr ".$temp_rekl_nr." ' />";		
		echo "</center></h5>";
		
		$sql88="SELECT zgl_czy_powiazane_z_wymiana_podzespolow,zgl_naprawa_id FROM $dbname_hd.hd_zgloszenie WHERE (zgl_nr=$temp_rekl_nr) LIMIT 1";
		$result88 = mysql_query($sql88, $conn_hd) or die($k_b);
		list($temp_czy_pow_z_wp_parent, $temp_naprawa_id_parent)=mysql_fetch_array($result88);
		
		if ($temp_naprawa_id_parent>0) {
		
			$numer_zgloszenia = $temp_rekl_nr;
						
			$result99 = mysql_query("SELECT * FROM $dbname.serwis_naprawa WHERE naprawa_hd_zgl_id='$numer_zgloszenia' LIMIT 1");
			while ($dane99 = mysql_fetch_array($result99)) {
				$mid = $dane99['naprawa_id'];					
				$mnazwa = $dane99['naprawa_nazwa'];
				$mmodel= $dane99['naprawa_model'];			
				$msn = $dane99['naprawa_sn'];
				$mni = $dane99['naprawa_ni'];
				$mstatus = $dane99['naprawa_status'];
			}
	
			$result65 = mysql_query("SELECT sn_nazwa FROM $dbname.serwis_sposob_naprawy WHERE (sn_id='$mstatus') LIMIT 1", $conn) or die($k_b);
			list($mstatus_opis)=mysql_fetch_array($result65);
			
			naprawaheader("<center>Zgłoszenie powiązane z naprawą (w zgłoszeniu nr <b>$temp_rekl_nr</b>) | status naprawy: <b>".$mstatus_opis."</b> | <input type=button class=buttons value='szczegóły naprawy' onclick=\"newWindow(800,600,'p_naprawy_szczegoly.php?id=$mid'); return false;\" /><br />".$mnazwa." ".$mmodel." | SN: ".$msn." | NI: ".$mni."</center>");
			
			//naprawaheader("<center><b>Zgłoszenie powiązane z powiązane z naprawą (w zgłoszeniu nr $temp_rekl_nr)</b><a title=' Pokaż szczegóły naprawy'><input class=imgoption type=image src=img/detail.gif onclick=\"newWindow(800,600,'p_naprawy_szczegoly.php?id=$mid'); return false;\"></a><br /><br /><b>".$mnazwa." ".$mmodel."</b> | SN: <b>".$msn."</b> | Status naprawy: <b>".$mstatus_opis."</b></center>");
		}
	
		if ($temp_czy_pow_z_wp_parent==1) {
			$sql_wp="SELECT wp_sprzet_opis, wp_sprzet_sn, wp_sprzet_ni FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_zgl_id=$temp_rekl_nr)  LIMIT 1";	
			$result_wp = mysql_query($sql_wp, $conn_hd) or die($k_b);

			list($wp_o,$wp_sn,$wp_ni)=mysql_fetch_array($result_wp);
			if ($wp_sn=='') $wp_sn='-';
			if ($wp_ni=='') $wp_ni='-';

			//echo "<h5 class=h5yellow><b>Zgłoszenie powiązane z wymianą podzespołów (w zgłoszeniu nr $temp_rekl_nr) w:</b><br /><br /><b>$wp_o</b> | SN: <b>$wp_sn</b> | NI: <b>$wp_ni</b></h5>";
			echo "<h5 class=h5yellow>Zgłoszenie powiązane z wymianą podzespołów (w zgłoszeniu nr <b>$temp_rekl_nr</b>) w:<br />$wp_o | SN: $wp_sn | NI: $wp_ni<br /></h5>";
		}

	}

	
	if ($temp_rekl_czy_ma==1) {
		list($rekl_nr)=mysql_fetch_array(mysql_query("SELECT zgl_nr FROM hd_zgloszenie WHERE (zgl_nr_zgloszenia_reklamowanego=$temp_nr) LIMIT 1"));
		echo "<h5 class=h5blue>Zgłoszenie numer <b>$_REQUEST[nr]</b> było reklamowane. Utworzono z niego zgłoszenie nr <b>".$rekl_nr."</b>";
		
		echo "&nbsp;<input class=buttons type=button onClick=\"self.location.href='hd_o_zgloszenia.php?action=obsluga&nr=$rekl_nr&id=$rekl_nr'\" value=' Przejdź do zgłoszenia nr ".$rekl_nr." ' />";
		
		echo "</h5>";
	}
	
				// okienka ostrzegawcze | POCZĄTEK
				$__zgl_nr 		= $temp2_zgl_nr;
				$__zgl_data_r 	= $temp_zgl_data_rozpoczecia;
				$__zgl_data_z	= $temp_zgl_data_zakonczenia;
				$__zgl_e1p		= $temp_zgl_E1P;
				$__zgl_e2p		= $temp_zgl_E2P;
				$__zgl_e3p		= $temp_zgl_E3P;
				$__zgl_kwh		= $temp_zgl_komorka_working_hours;
				$__zgl_op		= $temp_op;
				$__zgl_kat		= $temp_kategoria;
				$__zgl_status	= $temp_status;

				$__wersja		= 2;				// 1 - div, 2 - h2
				$__add_refresh	= 0;				// dodatkowe wymuszenie odświeżenia formatki
				$__add_br		= 1;
				$__tunon		= $turnon__hd_o_zgloszenia_zs;
				
				if ($__tunon) include('warning_messages.php');
				// okienka ostrzegawcze | KONIEC		
				
	echo "<table class=maxwidth border:0 cellspacing=2>";
	//starttable();
	echo "<form id=hd_o_zgloszenia name=hd_o_zgloszenia action=$PHP_SELF method=POST onSubmit=\"return pytanie_zapisz_zs('Zmienić status zgłoszenia ?');\" />";

	$data_ostatniego_kroku = AddMinutesToDate($czas_wykonywania_ostatniego_kroku,$data_ostatniego_kroku);
	
	echo "<input type=hidden name=data_ostatniego_kroku_value id=data_ostatniego_kroku_value value='".substr($data_ostatniego_kroku,0,10)."'>";
	echo "<input type=hidden name=godzina_ostatniego_kroku_value id=godzina_ostatniego_kroku_value value='".substr($data_ostatniego_kroku,11,8)."'>";
	
	if ($temp_naprawa_id>0) {
		echo "<input type=hidden name=tnaprawastatus id=tnaprawastatus value='$mstatus'>";
	} else echo "<input type=hidden name=tnaprawastatus id=tnaprawastatus value=''>";
	
	echo "<input type=hidden name=tnaprawaid id=tnaprawaid value='$temp_naprawa_id'>";
	echo "<input type=hidden name=tssid id=tssid value='$temp_ss_id'>";

	$dddd = Date('Y-m-d');
	$tttt = Date('H:i');

	include_once('systemdate.php');
	
		echo "<tr style='display:;'>";
		td("150;r;Data <u>zakończenia</u> kroku");
			td_(";;;");
				echo "<select class=wymagane name=zs_data id=zs_data onBlur=\"SelectZmienStatusOnBlur(); \" onChange=\"ClearCookie('wpisana_dzs_".$_REQUEST[nr]."'); SetCookie('wpisana_dzs_".$_REQUEST[nr]."',this.value); if (document.getElementById('hd_wyjazd_data')) document.getElementById('hd_wyjazd_data').value=this.value; \" />";
				
				echo "<option value='$dddd' SELECTED>$dddd</option>\n";
				$last_step_date = substr($data_ostatniego_kroku,0,10);	
				
				if ((date("w",strtotime($dddd))!=0) || ($idw_dla_zbh_testowa)) {	
					for ($cd=1; $cd<($idw_dla_zbh+1); $cd++) {
						if ($last_step_date<=SubstractDays($cd,$dddd)) {
							echo "<option value='".SubstractDays($cd,$dddd)."'"; 

							if (($_COOKIE['wpisana_dzs_'.$_REQUEST[nr].'']!=null) && (SubstractDays($cd,$dddd)==$_COOKIE['wpisana_dzs_'.$_REQUEST[nr].''])) echo " SELECTED ";

							echo ">".SubstractDays($cd,$dddd)."&nbsp;";
							if ($idw_dla_zbh_testowa) echo "[dla testów]";
							echo "</option>\n";
						}
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
					echo "<option value='$temp_dc_data'";
					
					if (($_COOKIE['wpisana_dzs_'.$_REQUEST[nr].'']!=null) && ($temp_dc_data==$_COOKIE['wpisana_dzs_'.$_REQUEST[nr].''])) echo " SELECTED ";
					
					echo ">$temp_dc_data (dostępna do $temp_dc_dostep_do)</option>\n";
				}
			}
	// koniec sprawdzania dostępów czasowych dla pracownika
	
				echo "</select>\n";
				$tttt = Date('H:i');
				echo "&nbsp;<input class=wymagane type=text name=zs_time id=zs_time value='";
				
				if ($_COOKIE['wpisana_gzs_'.$_REQUEST[nr].'']!=null) {
					echo $_COOKIE['wpisana_gzs_'.$_REQUEST[nr].''];
				} else echo "$tttt";
				
				echo "' maxlength=5 size=4 onBlur=\"CheckTime(this.value);\" onKeyPress=\"return filterInput(1, event, false, ':'); \" onKeyUp=\"DopiszDwukropek('zs_time');\" onChange=\"ClearCookie('wpisana_gzs_".$_REQUEST[nr]."'); SetCookie('wpisana_gzs_".$_REQUEST[nr]."',this.value); \" />";	
			_td();
		_tr();
		
		echo "<tr id=StatusZakonczony style='display:none'>";
			td("150;rt;Czas wykonywania");
			td_(";;;");
				echo "<input style=text-align:right class=wymagane type=text id=czas_wykonywania_h name=czas_wykonywania_h value='";

				if ($_COOKIE['wpisane_cw_h_'.$_REQUEST[nr].'']!=null) {
					echo $_COOKIE['wpisane_cw_h_'.$_REQUEST[nr].''];
				} else echo "0";
				
				echo "' maxlength=2 size=2 onKeyPress=\"return filterInput(1, event, false); \" onChange=\"if (this.value=='') this.value='0'; ClearCookie('wpisane_cw_h_".$_REQUEST[nr]."'); SetCookie('wpisane_cw_h_".$_REQUEST[nr]."',this.value); \" /> godzin";
				
				echo "&nbsp;";
				
				echo "<input style=text-align:right class=wymagane type=text id=czas_wykonywania_m name=czas_wykonywania_m value='";

				if ($_COOKIE['wpisane_cw_m_'.$_REQUEST[nr].'']!=null) {
					echo $_COOKIE['wpisane_cw_m_'.$_REQUEST[nr].''];
				} else echo "0";
				
				echo "' maxlength=2 size=2 onKeyPress=\"return filterInput(1, event, false); \" onChange=\"if (this.value=='') this.value='0'; ClearCookie('wpisane_cw_m_".$_REQUEST[nr]."'); SetCookie('wpisane_cw_m_".$_REQUEST[nr]."',this.value); \"/> minut";
				echo "<div id=StatusChanged_prepare>";
				echo "</div>";
			_td();
		echo "</tr>";
		
		tr_();
			td("150;r;Aktualny status zgłoszenia");
			td_(";;;");	

				$r4 = mysql_query("SELECT hd_status_opis FROM $dbname_hd.hd_status WHERE (hd_status_nr='$_GET[ts]') LIMIT 1", $conn_hd) or die($k_b);
				list($status_opis)=mysql_fetch_array($r4);
				echo "<b>$status_opis</b>";				
			_td();
		_tr();
				
		tr_();
			td("150;r;Nowy status zgłoszenia");
			td_(";;;");	
				$accessLevels = array("9");
				$temp_kategoria = $_GET[tk];
				
				$r55 = mysql_query("SELECT hdnp_zdiagnozowany, hdnp_oferta_wyslana, hdnp_akceptacja_kosztow, hdnp_zamowienie_wyslane, hdnp_zamowienie_zrealizowane, hdnp_gotowe_do_oddania, hdnp_naprawa_zakonczona FROM $dbname_hd.hd_naprawy_powiazane WHERE (hdnp_zgl_id='$_GET[nr]') ORDER BY hdnp_id DESC LIMIT 1", $conn_hd) or die($k_b);
				list($temp_zdiagnozowany, $temp_oferta_wyslana, $temp_akceptacja_kosztow, $temp_zamowienie_wyslane, $temp_zamowienie_zrealizowane, $temp_gotowe_do_oddania, $temp_naprawa_zakonczona)=mysql_fetch_array($r55);

				$r5 = mysql_query("SELECT zgl_podkategoria,zgl_priorytet FROM $dbname_hd.hd_zgloszenie WHERE (zgl_nr='$_GET[nr]') LIMIT 1", $conn_hd) or die($k_b);
				list($temp_podkategoria,$temp_priorytet)=mysql_fetch_array($r5);
				
				$_new_status = $_GET[ts];
				if ($_new_status==2) $_new_status = 1;
				
				switch ($_new_status) {
					case "1" : 	if ($temp_kategoria=='2') { $ListaStatusow = "'3','3B','9'"; $default_status = '3'; }
								if ($temp_kategoria=='3') { $ListaStatusow = "'3','3A','3B','4','5','6','7','9'"; $default_status = '3'; }
								if ($temp_kategoria=='4') { $ListaStatusow = "'3','3A','3B','4','5','6','7','9'"; $default_status = '3'; }
								if ($temp_kategoria=='5') { $ListaStatusow = "'3','3A','3B','4','5','6','7','9'"; $default_status = '3'; }
								if ($temp_kategoria=='6') { $ListaStatusow = "'3','9'"; $default_status = '3'; }
								if ($temp_kategoria=='7') { $ListaStatusow = "'3','9'"; $default_status = '3'; }
								break;
					case "2" : 	if ($temp_kategoria=='2') { $ListaStatusow = "'3','3B','9'"; $default_status = '3'; }
								if ($temp_kategoria=='3') { $ListaStatusow = "'3','3A','3B','4','5','6','7','9'"; $default_status = '3'; }
								if ($temp_kategoria=='4') { $ListaStatusow = "'3','3A','3B','4','5','6','7','9'"; $default_status = '3'; }
								if ($temp_kategoria=='5') { $ListaStatusow = "'3','3A','3B','4','5','6','7','9'"; $default_status = '3'; }
								if ($temp_kategoria=='6') { $ListaStatusow = "'3','9';"; $default_status = '3'; }
								break;
					case "3" : 	if ($temp_kategoria=='2') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3B'; }
								if ($temp_kategoria=='3') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3B'; }
								if ($temp_kategoria=='4') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3B'; }
								if ($temp_kategoria=='5') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3B'; }
								if ($temp_kategoria=='6') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3B'; }
								if ($temp_kategoria=='7') { $ListaStatusow = "'7','9'"; $default_status = '9'; }
								break;
					case "3A" :	if ($temp_kategoria=='2') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3A'; }
								if ($temp_kategoria=='3') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3A'; }
								if ($temp_kategoria=='4') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3A'; }
								if ($temp_kategoria=='5') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3A'; }
								if ($temp_kategoria=='6') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3A'; }
								break;
					case "3B" :	if ($temp_kategoria=='2') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3B'; }
								if ($temp_kategoria=='3') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3B'; }
								if ($temp_kategoria=='4') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3B'; }
								if ($temp_kategoria=='5') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3B'; }
								if ($temp_kategoria=='6') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3B'; }
								break;
					case "4" :	if ($temp_kategoria=='2') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3B'; }
								if ($temp_kategoria=='3') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3B'; }
								if ($temp_kategoria=='4') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3B'; }
								if ($temp_kategoria=='5') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3B'; }
								if ($temp_kategoria=='6') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3B'; }
								break;
					case "5" :	if ($temp_kategoria=='2') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3B'; }
								if ($temp_kategoria=='3') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3B'; }
								if ($temp_kategoria=='4') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3B'; }
								if ($temp_kategoria=='5') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3B'; }
								if ($temp_kategoria=='6') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '3B'; }
								break;
					case "6" :	if ($temp_kategoria=='2') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '9'; }
								if ($temp_kategoria=='3') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '9'; }
								if ($temp_kategoria=='4') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '9'; }
								if ($temp_kategoria=='5') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '9'; }
								if ($temp_kategoria=='6') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '9'; }
								break;
					case "7" :	if ($temp_kategoria=='2') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '7'; }
								if ($temp_kategoria=='3') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '7'; }
								if ($temp_kategoria=='4') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '7'; }
								if ($temp_kategoria=='5') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '7'; }
								if ($temp_kategoria=='6') { $ListaStatusow = "'3A','3B','4','5','6','7','9'"; $default_status = '7'; }
								if ($temp_kategoria=='7') { $ListaStatusow = "'7','9'"; $default_status = '9'; }
								break;
					
				}
	
			//echo "Przed: ".$ListaStatusow."<br />";
			
			if ($pozwol_na_6_i_9==false) {
				//echo ">>Bez 6 i 9";	
				$ListaStatusow = str_replace(',\'6\'', '', $ListaStatusow);
				$ListaStatusow = str_replace(',\'9\'', '', $ListaStatusow);
			}
			
			//echo "---Po: ".$ListaStatusow."<br />";
			
			if ($ListaStatusow=='') {
				echo "<font color=red><b>Dla tego zgłoszenia nie można ustawić nowego statusu. Proszę skontaktować się z administratorem.</b></font>";
			}
			
		if ($ListaStatusow!='') {
			$sql = "SELECT hd_status_nr,hd_status_opis FROM $dbname_hd.hd_status WHERE ((hd_status_wlaczona=1) and (hd_status_nr IN (".$ListaStatusow."))) ORDER BY hd_status_id";

			//echo ">>>>".$_COOKIE['nowy_status_'.$_REQUEST[nr].''];
			
			//echo "$sql";
			$rsd = mysql_query($sql,$conn_hd);
			$ile = mysql_num_rows($rsd);

				if ($ile>0) {

					if ($_COOKIE['nowy_status_'.$_REQUEST[nr].'']!=null) {
						$default_status = $_COOKIE['nowy_status_'.$_REQUEST[nr].''];						
					}
					
					echo "<select id=SelectZmienStatus name=SelectZmienStatus style='display:";
					//if ($_REQUEST[zgoda]==9) echo "none";
					echo "' onChange=\"SelectZmienStatusOnBlur(); ClearCookie('nowy_status_".$_REQUEST[nr]."'); SetCookie('nowy_status_".$_REQUEST[nr]."',this.value); return false; \" />";
					
					while (list($temp_nr,$temp_opis) = mysql_fetch_array($rsd)) {
							echo "<option value='$temp_nr' ";
							if ($temp_nr==$default_status) echo " SELECTED ";
							echo ">$temp_opis</option>\n"; 
						}
					echo "</select>";
					
					
				}
				
		}
			_td();
		_tr();

/*		echo "<tr id=ZmianaPriorytetuInfo style='display:none;'>";
			td("150;r;Zmiana priorytetu");
			td_(";;;");			
					echo "<span >";
					echo "<br /><font color=red>&nbsp;Zmiana priorytetu awarii z <b>krytyczny</b> na <b>standard</b></font><br /><br />";
					
						echo "<fieldset><legend><b>Nowy termin zakończenia (ilość dni liczona od teraz) | Charakter awarii</b></legend>";
						nowalinia();
						echo "<table class=border0>";
						echo "<tr class=nieparzyste id=zs_charakter_awarii2tr><td>";
						echo "<input class=border0 type=radio name=zs_charakter_awarii id=zs_charakter_awarii2 value='2'><font color=red>&nbsp;2 dni </font></td><td>Awaria sprzętu, która ogranicza w sposób bardzo istotny świadczenie usługi</td></tr>";
						
						echo "<tr class=parzyste id=zs_charakter_awarii5tr><td><input class=border0 type=radio name=zs_charakter_awarii id=zs_charakter_awarii5 value='5'><font color=red>&nbsp;5 dni</font></td><td>dla awarii aplikacji</td></tr>";
						
						echo "<tr class=nieparzyste><td><input class=border0 type=radio name=zs_charakter_awarii id=zs_charakter_awarii14 value='14'><font color=red>&nbsp;14 dni </font></td><td>Wynik uzgodnień z kierownikiem Jednostki lub osobą przez niego upoważnioną.<br /><b>Uzgodnienie ma być przekazane drogą email do przedstawiciela CIT</b></td></tr>";
						echo "</table>";
						echo "</fieldset>";
						
					echo "</span>";
			_td();
		_tr();				
*/
// ####
		
		echo "<tr id=AwariaWAN style='display:none;'>";
			td("150;rt;Informacje o awarii");
			td_(";;;");
			
					echo "<div>";				
				
					$w1 = mysql_query("SELECT zgl_komorka FROM $dbname_hd.hd_zgloszenie WHERE ((zgl_nr=$_GET[nr])) LIMIT 1", $conn_hd) or die($k_b);
					list($zgl_komorka_nazwa)=mysql_fetch_array($w1);
				
					//$SzukajUP = substr($zgl_komorka_nazwa,strpos($zgl_komorka_nazwa,' ')+1,strlen($zgl_komorka_nazwa));
					
					$r44 = mysql_query("SELECT up_id, up_nrwanportu,up_adres,up_telefon,up_nazwa,up_ip, up_working_time,up_working_time_alternative,up_working_time_alternative_start_date,up_working_time_alternative_stop_date FROM $dbname.serwis_komorki,$dbname.serwis_piony WHERE (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.belongs_to=$es_filia) and (up_active=1) and (CONCAT(serwis_piony.pion_nazwa,' ',serwis_komorki.up_nazwa)='$zgl_komorka_nazwa') LIMIT 1", $conn) or die($k_b);
					
					list($komorka_id,$komorka_wanport,$komorka_adres,$komorka_telefon,$komorka_nazwa,$komorka_ip,$temp_working_time,$temp_working_time_alt,$temp_working_time_start_date,$temp_working_time_stop_date)=mysql_fetch_array($r44);
				
					echo "<fieldset><legend>Informacje potrzebne do zgłoszenia awarii WAN</legend>";
					nowalinia();
					
					// godziny pracy
				
					$days = explode(";",$temp_working_time);
					
					$oneday1 = explode("@",$days[0]); 
					$oneday2 = explode("@",$days[1]); 
					$oneday3 = explode("@",$days[2]); 
					$oneday4 = explode("@",$days[3]); 
					$oneday5 = explode("@",$days[4]); 
					$oneday6 = explode("@",$days[5]); 
					$oneday7 = explode("@",$days[6]); 

					$gp_sa = 1;
					if (($oneday1[1]=='') && ($oneday2[1]=='') && ($oneday3[1]=='') && ($oneday4[1]=='') && ($oneday5[1]=='') && ($oneday6[1]=='') && ($oneday7[1]=='')) $gp_sa = 0;
					
					if ($oneday1[1]=='') $oneday1[1] = '-';
					if ($oneday2[1]=='') $oneday2[1] = '-';
					if ($oneday3[1]=='') $oneday3[1] = '-';
					if ($oneday4[1]=='') $oneday4[1] = '-';
					if ($oneday5[1]=='') $oneday5[1] = '-';
					if ($oneday6[1]=='') $oneday6[1] = '-';
					if ($oneday7[1]=='') $oneday7[1] = '-';

					echo "Godziny pracy komórki:";
					echo "<table>";
					echo "<tr style='background-color:#B4B4B4'>";
						echo "<td width=80 class=center>PN</td><td width=80 class=center>WT</td><td width=80 class=center>ŚR</td><td width=80 class=center>CZ</td><td width=80 class=center>PT</td><td width=80 class=center>SO</td><td width=80 class=center>NI</td>";
					echo "</tr>";
					echo "<tr style='background-color:#FFFF7F'>";
						echo "<td class=center><b>$oneday1[1]</b></td>";
						echo "<td class=center><b>$oneday2[1]</b></td>";
						echo "<td class=center><b>$oneday3[1]</b></td>";
						echo "<td class=center><b>$oneday4[1]</b></td>";
						echo "<td class=center><b>$oneday5[1]</b></td>";
						echo "<td class=center><b>$oneday6[1]</b></td>";
						echo "<td class=center><b>$oneday7[1]</b></td>";
					echo "</tr>";
					echo "</table>";
					
					if ((substr($temp_working_time_start_date,5,5)!='00-00') && (substr($temp_working_time_stop_date,5,5)!='00-00')) {
						$days = explode(";",$temp_working_time_alt);
						
						$oneday1a = explode("@",$days[0]); 
						$oneday2a = explode("@",$days[1]); 
						$oneday3a = explode("@",$days[2]); 
						$oneday4a = explode("@",$days[3]); 
						$oneday5a = explode("@",$days[4]); 
						$oneday6a = explode("@",$days[5]); 
						$oneday7a = explode("@",$days[6]); 

						$gpa_sa = 1;
						if (($oneday1a[1]=='') && ($oneday2a[1]=='') && ($oneday3a[1]=='') && ($oneday4a[1]=='') && ($oneday5a[1]=='') && ($oneday6a[1]=='') && ($oneday7a[1]=='')) $gpa_sa = 0;
						
						
						if ($oneday1a[1]=='') $oneday1a[1] = '-';
						if ($oneday2a[1]=='') $oneday2a[1] = '-';
						if ($oneday3a[1]=='') $oneday3a[1] = '-';
						if ($oneday4a[1]=='') $oneday4a[1] = '-';
						if ($oneday5a[1]=='') $oneday5a[1] = '-';
						if ($oneday6a[1]=='') $oneday6a[1] = '-';
						if ($oneday7a[1]=='') $oneday7a[1] = '-';
						
						
						$alt_od = date('Y')."-".substr($temp_working_time_start_date,5,5);
						$alt_do = date('Y')."-".substr($temp_working_time_stop_date,5,5);

						echo "<br />Godziny pracy (alternatywne) obowiązują od: <b>$alt_od - $alt_do</b>";
						echo "<table>";
						echo "<tr style='background-color:#B4B4B4'>";
							echo "<td width=80 class=center>PN</td><td width=80 class=center>WT</td><td width=80 class=center>ŚR</td><td width=80 class=center>CZ</td><td width=80 class=center>PT</td><td width=80 class=center>SO</td><td width=80 class=center>NI</td>";
						echo "</tr>";
						echo "<tr style='background-color:#FFAA7F'>";
							echo "<td class=center><b>$oneday1[1]</b></td>";
							echo "<td class=center><b>$oneday2[1]t</b></td>";
							echo "<td class=center><b>$oneday3[1]</b></td>";
							echo "<td class=center><b>$oneday4[1]</b></td>";
							echo "<td class=center><b>$oneday5[1]</b></td>";
							echo "<td class=center><b>$oneday6[1]</b></td>";
							echo "<td class=center><b>$oneday7[1]</b></td>";
						echo "</tr>";
						echo "</table>";
					
					}
					
					echo "Sposób zgłoszenia awarii: ";
					
					
					$default_way = 2;
					if ($_COOKIE['way_saved_'.$_REQUEST[nr].'']!=null) {
						$default_way = $_COOKIE['way_saved_'.$_REQUEST[nr].''];						
					}
					
					echo "<select name=way_of id=way_of onChange=\"ChangeWay(this.value); ClearCookie('way_saved_".$_REQUEST[nr]."'); 
	SetCookie('way_saved_".$_REQUEST[nr]."',this.value);\" />";
						
						echo "<option value=1 ";
						if ($default_way==1) echo "SELECTED";
						echo ">Do Orange (stara droga)</option>";
						
						echo "<option value=2 ";
						if ($default_way==2) echo "SELECTED";
						echo ">HOUSTON (telefonicznie)</option>";
						
						echo "<option value=3 ";
						if ($default_way==3) echo "SELECTED";
						echo ">HOUSTON (mailowo)</option>";
						
						//echo "<option value=4 ";	if ($default_way==4) echo "SELECTED";	echo ">HOUSTON (przez HDIM)</option>";
					echo "</select>";
					
					echo "&nbsp;";
					echo "<input style='display:' id=uo type=button class=buttons value='Ukryj opis procedury' onClick=\"ChangeWay(0); $('#uo').hide(); $('#po').show(); ClearCookie('way_showed_".$_REQUEST[nr]."'); SetCookie('way_showed_".$_REQUEST[nr]."','0');\" />";
					echo "<input style='display:none' id=po type=button class=buttons value='Pokaż opis procedury' onClick=\"ChangeWay(readCookie('way_saved_".$_REQUEST[nr]."')); $('#uo').show(); $('#po').hide(); ClearCookie('way_showed_".$_REQUEST[nr]."'); SetCookie('way_showed_".$_REQUEST[nr]."','1'); \" />";
					
						echo "<div id=way_1 style='display:none;'>";	
							echo "<table>";
								echo "<tr><td class=right>Telefon do Orange</td><td><b><font color=red>$nr_telefonu_do_tpsa</font></b></td></tr>";
								echo "<tr><td class=right>Nr WAN-portu</td><td><b><font color=black><b>$komorka_wanport</b></font></b></td></tr>";
								echo "<tr><td class=right>Lokalizacja</td><td><b><font color=black><b>$komorka_adres</b></font></b></td></tr>";
								echo "<tr><td class=right>Telefon</td><td><b><font color=black><b>$komorka_telefon</b></b></td></tr>";
							echo "</table>";
						
							//echo "<br />&nbsp;Telefon do TPSA : <b><font color=red>$nr_telefonu_do_tpsa</font></b><br />";
							//echo "&nbsp;Nazwa komórki : <b><font color=black>$komorka_nazwa</font></b><br />";
							
							echo "<input type=hidden name=up_nazwa1 value='$komorka_nazwa'>";
							echo "<input type=hidden name=up_wanport value='$komorka_wanport'>";
							echo "<input type=hidden name=up_ip1 value='$komorka_ip'>";

							$w1a = mysql_query("SELECT awaria_id, awaria_nrzgloszenia FROM $dbname.serwis_awarie WHERE ((awaria_nrwanportu='$komorka_wanport') && (awaria_status=0)) LIMIT 1", $conn) or die($k_b);
							list($__awariaid, $__old_numerzgloszenia)=mysql_fetch_array($w1a);
							
							if ($__awariaid>0) {
								echo "<input type=hidden name=awaria_update value='$__awariaid'>";
								echo "<input type=hidden name=old_numerzgloszenia value='$__old_numerzgloszenia'>";
							} else {
								echo "<input type=hidden name=awaria_update value='-'>";
								echo "<input type=hidden name=old_numerzgloszenia value=''>";
							}

							//echo "[[[[[[".$komorka_ip."]]]]]]";
							/*	echo "&nbsp;Nr WAN-portu : "; echo "<input size=60 style='background-color:transparent;border:0px;font-size:12px;font-weight:bold;' type=text readonly tabindex=-1 value='$komorka_wanport'>"; nowalinia();
								echo "&nbsp;Lokalizacja : "; echo "<input size=60 style='background-color:transparent;border:0px;font-size:12px;' type=text readonly tabindex=-1 value='$komorka_adres'>"; nowalinia();
								echo "&nbsp;Telefon : "; echo "<input size=60 style='background-color:transparent;border:0px;font-size:12px;' type=text readonly tabindex=-1 value='$komorka_telefon'>";nowalinia();		
							*/
							//echo "&nbsp;Nr WAN-portu : <font color=black><b>$komorka_wanport</b></font><br />";
							//echo "&nbsp;Lokalizacja : <font color=black><b>$komorka_adres</b></font><br />";
							//echo "&nbsp;Telefon : <font color=black><b>$komorka_telefon</b></font><br />";		
						echo "</div>";
						
						echo "<div id=way_2>";
							echo "<table>";
								echo "<tr><td class=right>a)</td><td>Sprawdzamy czy na UP jest prąd</td></tr>";
								echo "<tr><td class=right>b)</td><td>Prosimy o restart urządzenia na UP</td></tr>";
								echo "<tr><td class=right>c)</td><td>Wymagane dane do zgłoszenia awarii:</td></tr>";
								
									echo "<tr><td></td><td class=left>&nbsp;&nbsp;Nr WAN-portu: <b><font color=black><b>$komorka_wanport</b></font></b></td></tr>";
									echo "<tr><td></td><td class=left>&nbsp;&nbsp;Lokalizacja: <b><font color=black><b>$komorka_adres</b></font></b></td></tr>";
									echo "<tr><td></td><td class=left>&nbsp;&nbsp;Telefon: <b><font color=black><b>$komorka_telefon</b></b></td></tr>";
								
								echo "<tr><td class=right>d)</td><td>Zgłaszamy niedziałające łącze dzwoniąc do Centrum HOUSTON na numer: <b>$HOUSTON_nr_tel_1</b> lub <b>$HOUSTON_nr_tel_2</b></td></tr>";
								echo "<tr><td class=right>e)</td><td>Podajemy dane z pkt. c</td></tr>";
								echo "<tr><td class=right>f)</td><td>W celu uzyskania dodatkowych informacji dzwonimy do Centrum HOUSTON</td></tr>";
							echo "</table>";
						echo "</div>";
						
						echo "<div id=way_3 style='display:none;'>";
							echo "<table>";
								echo "<tr><td class=right>a)</td><td>Sprawdzamy czy na UP jest prąd</td></tr>";
								echo "<tr><td class=right>b)</td><td>Prosimy o restart urządzenia na UP</td></tr>";

								$mail_topic = 'Awaria UP ('.$komorka_nazwa.') Orange';
								$mail_content = 'Port: '.$komorka_wanport.', Telefon na urzad: '.$komorka_telefon.', Kontakt do informatyka: '.$es_imie.' '.$es_nazwisko.' ('.$es_telefon.'), Godziny pracy komorki: PN: '.$oneday1[1].', WT: '.$oneday2[1].',SR: '.$oneday3[1].', CZ: '.$oneday4[1].', PT: '.$oneday5[1].', SO: '.$oneday6[1].', NI: '.$oneday7[1].', Stan diod na modemie:';
									
								echo "<tr><td class=right>c)</td><td>Wysyłamy maila na skrzynkę ";
								echo "<a title='Kliknij, aby wygenerować gotową wiadomość' href='mailto:$HOUSTON_mail?subject=".$mail_topic."&body=".$mail_content."'>$HOUSTON_mail</a> <a title='Kliknij, aby wygenerować gotową wiadomość' href='mailto:$HOUSTON_mail?subject=".$mail_topic."&body=".$mail_content."'><img src=img/send_email_1.gif border=0 width=14 height=9 /></a>";
								echo " o treści:</td></tr>";
								
									echo "<tr><td></td><td class=left><br /><u>Temat:</u><br />Awaria UP ($komorka_nazwa) Orange<br /><br /><u>Treść:</u><br />Port:$komorka_wanport<br />Telefon na urząd: $komorka_telefon<br />Kontakt do informatyka: $es_imie $es_nazwisko ($es_telefon)<br />Godziny pracy komórki: PN: $oneday1[1], WT: $oneday2[1],ŚR: $oneday3[1], CZ: $oneday4[1], PT: $oneday5[1], SO: $oneday6[1], NI: $oneday7[1]<br />Stan diod na modemie:</td></tr>";
									
									// echo "<tr><td></td><td class=left>";
									// echo "<a href='mailto:$HOUSTON_mail?subject=".$mail_topic."&body=".$mail_content."'>mail</a>";
									// echo "</td></tr>";
									
									//echo "<tr><td></td><td class=left>-wersja rozszerzona-</td></tr>";

								echo "<tr><td class=right>d)</td><td>W odpowiedzi na maila operator Centrum HOUSTON odsyła numer zgłoszenia w Orange </td></tr>";
							echo "</table>";
						echo "</div>";

						echo "<div id=way_4 style='display:none;'>";
							ECHO "HOUSTON (HDIM)";
						echo "</div>";
						
						echo "<br />Numer zgłoszenia w Orange: <input type=text name=numerzgloszenia id=numerzgloszenia>";
						nowalinia();			
						echo "</fieldset>";
					echo "</div>";					
				
/*				else {
						//echo "$_REQUEST[ls]";
						echo "Brak możliwości zmiany statusu";
					}*/
			_td();
		_tr();
		
	echo "<tr id=NrZgloszeniaGdansk style='display:none'>";
		td("140;r;<b>Identyfikator zgłoszenia</b>");
		td_(";;;");
			echo "<input type=text id=hdnrzglgdansk name=hdnrzglgdansk value='' maxlength=10 size=8 onFocus=\"this.select();\" />";
			echo "&nbsp;( numer zgłoszenia w bazie eSerwis w Gdańsku )";
		_td();
	echo "</tr>";

	echo "<tr id=InformacjaOZmianiePriorytetuZgloszenia style='display:none'>";
		td("140;r;");
		td_(";;;");
		
			list($zgloszenie_kat,$zgloszenie_podkat,$zgloszenie_priorytet,$zgloszenie_status)=mysql_fetch_array(mysql_query("SELECT zgl_kategoria, zgl_podkategoria, zgl_priorytet, zgl_status FROM $dbname_hd.hd_zgloszenie WHERE (zgl_nr=$_GET[nr]) LIMIT 1", $conn_hd));
			//echo $zgloszenie_kat." ".$zgloszenie_podkat." ".$zgloszenie_priorytet." ".$zgloszenie_status." ";
			if ($zgloszenie_kat=='6') {
				//echo "<font color=red>Po zapisaniu zmian - będzie możliwość utworzenia nowego zgłoszenia <b>awarii zwykłej</b><br />na bazie tego zgłoszenia ( kontynuacja )";
				if (($mstatus!=5) && ($mstatus!=8) && ($temp_naprawa_id>0)) {
					errorheader("<font style='font-weight:normal'>Z uwagi na niezakończoną naprawę w tym zgłoszeniu <font color=white>awarii krytycznej</font> - konieczne jest utworzenie z tego zgłoszenia, nowego zgłoszenia <b><font color=red>awarii zwykłej</font></b> lub <b><font color=red>prac zleconych w ramach umowy</font></b> ( kontynuacja )</b>");
				} else {
					infoheader("Po zapisaniu zmian - będzie możliwość utworzenia nowego zgłoszenia<br /><b><font color=red>awarii zwykłej</font></b> lub <b><font color=red>pracy zleconej w ramach umowy</font></b> <br />na bazie tego zgłoszenia ( kontynuacja )");
				}				
				
				//echo $mstatus;
			}
			if ($zgloszenie_kat=='2') {
				//echo "<font color=red>Po zapisaniu zmian - będzie możliwość utworzenia nowego zgłoszenia <b>prac zleconych w ramach umowy</b>, na bazie tego zgłoszenia ( kontynuacja )";
				//echo $mstatus;
				if (($mstatus!=5) && ($mstatus!=8) && ($temp_naprawa_id>0)) {
					errorheader("<font style='font-weight:normal'>Z uwagi na niezakończoną naprawę w tym zgłoszeniu <font color=white>awarii zwykłej</font> - konieczne jest utworzenie z tego zgłoszenia, nowego zgłoszenia <b>prac zleconych w ramach umowy</b> ( kontynuacja )</b>");
				} else {
					infoheader("Po zapisaniu zmian - będzie możliwość utworzenia nowego zgłoszenia<br /><b><font color=red>prac zleconych w ramach umowy</font></b>, na bazie tego zgłoszenia ( kontynuacja )");
				}
			}
			
		_td();
	echo "</tr>";

	echo "<tr id=ZmianaStatusuNaprawy style='display:none'>";
		td("140;r;");
		td_(";;;");
			// do dokończenia (problem z protokołami)
			
			if ($move_naprawa==true) {
				if (($temp_naprawa_id>0) && ($mstatus=='-1')) { 
					//echo "<input type=button class=buttons value=' Uruchom kreator przekazania sprzętu do serwisu zewnętrznego' onclick=\"newWindow_r(800,600,'z_naprawy_status.php?id=$temp_naprawa_id&cs=-1&from=hd&hdzglid=$_GET[nr]&hd_zgl_nr=$_GET[nr]');\" >";
					
					echo "<p class='block block_naprawa_wsz' onClick=\"newWindow_r(800,600,'z_naprawy_status.php?id=$temp_naprawa_id&cs=-1&from=hd&hdzglid=$_GET[nr]&hd_zgl_nr=$_GET[nr]'); return false;\">";
						echo "<b>Uruchom kreator przekazania sprzętu do serwisu zewnętrznego</b>";
					echo "</p>";				
				}
			}
			
		_td();
	echo "</tr>";
	
	echo "<tr id=WymianaPodzespolu style='display:none;'>";
		td("140;r;");
		td_(";;;");
		
		if ($pozwol_na_6_i_9==true) {
		
			if ($temp_podkategoria2!='') {
				$temp_rn = '';
				$temp_rnazwa = '';
				$w1 = mysql_query("SELECT rola_id FROM $dbname.serwis_slownik_rola WHERE (rola_nazwa='$temp_podkategoria2') LIMIT 1", $conn) or die($k_b);
				list($temp_rn)=mysql_fetch_array($w1);
				if ($temp_podkategoria2!='') $temp_rnazwa = $temp_podkategoria2;
				if ($temp_rn>0) {} else { $temp_rn = ''; }
			}
		
			if ($_SESSION['wybrany_sprzet_do_wymiany_dla_zgl_nr_'.$_REQUEST[id].'']==0) {			

				$w1 = mysql_query("SELECT zgl_komorka,zgl_tresc,zgl_podkategoria FROM $dbname_hd.hd_zgloszenie WHERE ((zgl_nr=$_REQUEST[nr])) LIMIT 1", $conn_hd) or die($k_b);
				list($zgl_komorka_nazwa, $temp_zgl_tresc,$temp_zgl_podkat)=mysql_fetch_array($w1);
				
				//$zgl_komorka_nazwa = substr($zgl_komorka_nazwa,strpos($zgl_komorka_nazwa," ")+1,strlen($zgl_komorka_nazwa));
					
				$result44 = mysql_query("SELECT serwis_komorki.up_id,serwis_komorki.up_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) and (CONCAT(UPPER(serwis_piony.pion_nazwa),' ',UPPER(serwis_komorki.up_nazwa)) = '$zgl_komorka_nazwa')) LIMIT 1", $conn) or die($k_b);

				list($temp_up_id,$zgl_komorka_nazwa) = mysql_fetch_array($result44);
						
				if (($temp_czy_pow_z_wp==1) || ($temp_czy_pow_z_wp_parent==1)) {
					$tn = substr($wp_o,0,strpos($wp_o,' '));
					$tm = substr($wp_o,strpos($wp_o,' ')+1,strlen($wp_o));
					$tsn = $wp_sn;
					$tni = $wp_ni;
	
					echo "<p class='block block_wymiana_podzespolow' onClick=\"newWindow_r(800,600,'z_wymiana_wybor_z_ewidencji.php?cat=".urlencode($temp_rnazwa)."&typid=$temp_rn&new_upid=$temp_up_id&hd_podkategoria_nr=$_REQUEST[tpk]&from=hd&hd_nr=$_REQUEST[nr]&tresc_zgl=".urlencode($temp_zgl_tresc)."&tup=".urlencode($zgl_komorka_nazwa)."&przyjmij=1&auto=1&tnazwa=".urlencode($tn)."&tmodel=".urlencode($tm)."&tsn=".urlencode($tsn)."&tni=".urlencode($tni)."&noback=1&hd_zgl_nr=$_GET[nr]'); return false;\">";
						echo "<b>Uruchom kreator wymiany podzespołów</b>";
					echo "</p>";
					
				} else {
					if ($temp_naprawa_id<=0) {		
					
						if ($temp_naprawa_id_parent>0) { 
							list($tn,$tm,$tsn,$tni)=mysql_fetch_array(mysql_query("SELECT naprawa_nazwa, naprawa_model, naprawa_sn, naprawa_ni FROM $dbname.serwis_naprawa WHERE (naprawa_id='$temp_naprawa_id_parent') LIMIT 1", $conn));

							echo "<p class='block block_wymiana_podzespolow' onClick=\"newWindow_r(800,600,'z_wymiana_wybor_z_ewidencji.php?cat=".urlencode($temp_rnazwa)."&typid=$temp_rn&new_upid=$temp_up_id&hd_podkategoria_nr=$_REQUEST[tpk]&from=hd&hd_nr=$_REQUEST[nr]&tresc_zgl=".urlencode($temp_zgl_tresc)."&tup=".urlencode($zgl_komorka_nazwa)."&przyjmij=1&tnazwa=".urlencode($tn)."&tmodel=".urlencode($tm)."&tsn=".urlencode($tsn)."&tni=".urlencode($tni)."&noback=1&auto=1&hd_zgl_nr=$_GET[nr]'); return false;\">";
							echo "<b>Uruchom kreator wymiany podzespołów</b>";
							echo "</p>";
						} else {
						
							echo "<p class='block block_wymiana_podzespolow' onClick=\"newWindow_r(800,600,'z_wymiana_podzespolu.php?cat=".urlencode($temp_rnazwa)."&typid=$temp_rn&new_upid=$temp_up_id&hd_podkategoria_nr=$_REQUEST[tpk]&from=hd&hd_nr=$_REQUEST[nr]&tresc_zgl=".urlencode($temp_zgl_tresc)."&tup=".urlencode($zgl_komorka_nazwa)."&przyjmij=1&hd_zgl_nr=$_GET[nr]'); return false;\">";
								echo "<b>Uruchom kreator wymiany podzespołów</b>";
							echo "</p>";
						}
						
					} else {
						list($tn,$tm,$tsn,$tni)=mysql_fetch_array(mysql_query("SELECT naprawa_nazwa, naprawa_model, naprawa_sn, naprawa_ni FROM $dbname.serwis_naprawa WHERE (naprawa_id='$temp_naprawa_id') LIMIT 1", $conn));

						echo "<p class='block block_wymiana_podzespolow' onClick=\"newWindow_r(800,600,'z_wymiana_wybor_z_ewidencji.php?cat=".urlencode($temp_rnazwa)."&typid=$temp_rn&new_upid=$temp_up_id&hd_podkategoria_nr=$_REQUEST[tpk]&from=hd&hd_nr=$_REQUEST[nr]&tresc_zgl=".urlencode($temp_zgl_tresc)."&tup=".urlencode($zgl_komorka_nazwa)."&przyjmij=1&tnazwa=".urlencode($tn)."&tmodel=".urlencode($tm)."&tsn=".urlencode($tsn)."&tni=".urlencode($tni)."&noback=1&auto=1&hd_zgl_nr=$_GET[nr]'); return false;\">";
							echo "<b>Uruchom kreator wymiany podzespołów</b>";
						echo "</p>";
						
					}
				}
				
			} else {
				
				$w1 = mysql_query("SELECT zgl_komorka,zgl_tresc,zgl_podkategoria FROM $dbname_hd.hd_zgloszenie WHERE ((zgl_nr=$_REQUEST[nr])) LIMIT 1", $conn_hd) or die($k_b);

				list($zgl_komorka_nazwa, $temp_zgl_tresc,$temp_zgl_podkat)=mysql_fetch_array($w1);
				$zgl_komorka_nazwa = substr($zgl_komorka_nazwa,strpos($zgl_komorka_nazwa," ")+1,strlen($zgl_komorka_nazwa));


				//$zgl_komorka_nazwa = substr($zgl_komorka_nazwa,strpos($zgl_komorka_nazwa," ")+1,strlen($zgl_komorka_nazwa));
					
				$result44 = mysql_query("SELECT serwis_komorki.up_id,serwis_komorki.up_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) and (CONCAT(UPPER(serwis_piony.pion_nazwa),' ',UPPER(serwis_komorki.up_nazwa)) = '$zgl_komorka_nazwa')) LIMIT 1", $conn) or die($k_b);

				list($temp_up_id,$zgl_komorka_nazwa) = mysql_fetch_array($result44);

				//	$result44 = mysql_query("SELECT serwis_komorki.up_id FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) and (UPPER(serwis_komorki.up_nazwa) = '$zgl_komorka_nazwa')) LIMIT 1", $conn) or die($k_b);
				//	list($temp_up_id) = mysql_fetch_array($result44);

				if ($temp_czy_pow_z_wp==1) {
				
				} else {

				//	addownlinkbutton("'Uruchom ponownie kreator wymiany podzespołów'","Button1","button","newWindow_r(800,600,'z_wymiana_podzespolu.php?new_upid=$temp_up_id&hd_podkategoria_nr=$_REQUEST[tpk]&from=hd&hd_nr=$_REQUEST[nr]&tresc_zgl=".urlencode($temp_zgl_tresc)."&tup=".urlencode($zgl_komorka_nazwa)."&przyjmij=1&clear_session=1&hd_zgl_nr=$_GET[nr]')");
					
					echo "<p class='block block_wymiana_podzespolow' onClick=\"newWindow_r(800,600,'z_wymiana_podzespolu.php?cat=".urlencode($temp_rnazwa)."&typid=$temp_rn&new_upid=$temp_up_id&hd_podkategoria_nr=$_REQUEST[tpk]&from=hd&hd_nr=$_REQUEST[nr]&tresc_zgl=".urlencode($temp_zgl_tresc)."&tup=".urlencode($zgl_komorka_nazwa)."&przyjmij=1&clear_session=1&hd_zgl_nr=$_GET[nr]'); return false;\">";
							echo "<b>Uruchom kreator wymiany podzespołów</b>";
					echo "</p>";					
				}
				
				$cnt = $_SESSION['podzespoly_do_zgl_nr_'.$_REQUEST[nr].'_count'];

				if ($_SESSION['podzespoly_do_zgl_nr_'.$_REQUEST[nr].'']=='magazyn') {
				
					//print_r($_SESSION);
				
					$jeden_towar = explode("|#|", $_SESSION['faktura_szcz_id_for_zgloszenie_nr_'.$_REQUEST[nr].'']);
					//echo $_SESSION['faktura_szcz_id_for_zgloszenie_nr_'.$_REQUEST[nr]];
					
					$list_pozycje = '';
					$list_pozycje_dla_sprzedazy = '';
					$list_pozycje_dla_sprzedazy2 = 'Wymieniono części: ';
					
					$sprzedanych = 0;
					$elzestawu = 0;
					
					for ($i=0; $i<$cnt; $i++) { 
						
						list($fsz_status)=mysql_fetch_array(mysql_query("SELECT pozycja_status FROM $dbname.serwis_faktura_szcz WHERE pozycja_id='$jeden_towar[$i]' LIMIT 1",$conn));
						
						if ($fsz_status==1) $sprzedanych++;
						if ($fsz_status==5) $elzestawu++;
						
					}
					$nooptions = 0;
					if ((($elzestawu<$cnt) && ($elzestawu!=0)) || (($sprzedanych<$cnt) && ($sprzedanych!=0))) $nooptions = 1;
					
					
					$sprzedanych = 0;
					$elzestawu = 0;	
					$onepos = '';
					
					for ($i=0; $i<$cnt; $i++) { 
						
						list($fsz_nazwa,$fsz_sn,$fsz_rodzaj,$fsz_fnr,$fsz_status)=mysql_fetch_array(mysql_query("SELECT pozycja_nazwa, pozycja_sn, pozycja_rodzaj_sprzedazy,pozycja_nr_faktury,pozycja_status FROM $dbname.serwis_faktura_szcz WHERE pozycja_id='$jeden_towar[$i]' LIMIT 1",$conn));
						
						$list_pozycje .= $fsz_nazwa;
						$onepos = $jeden_towar[$i];
						
						if ($fsz_sn!='') $list_pozycje .= " (SN: ".$fsz_sn.")";
						
						if ($fsz_rodzaj=='') { $acrs = 1; } else { $acrs = 0; }
						
						$nazwa_urzadzenia = $_SESSION['typ_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].'']." ".$_SESSION['model_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].''];
						$sn_urzadzenia = $_SESSION['sn_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].''];
						$ni_urzadzenia = $_SESSION['ni_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].''];
					
					
					//echo "<a title=' Sprzedaj towar : $temp_nazwa o numerze seryjnym : $temp_sn '><input class=imgoption type=image src=img/sell.gif  onclick=\"newWindow_r(700,595,'z_towary_obrot.php?id=$temp_id&f=$temp_id4&obzp=1&trodzaj=".urlencode($temp_rs)."&allow_change_rs=$acrs')\"></a>";
					
						$result_upid = mysql_query("SELECT up_id,up_nazwa,up_pion_id,up_umowa_id,pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (up_active=1) and (belongs_to=$es_filia) and (CONCAT(serwis_piony.pion_nazwa,' ',serwis_komorki.up_nazwa)='$temp_komorka') LIMIT 1", $conn) or die($k_b);
				
						list($temp_upid,$temp_nazwa,$temp_pion_id,$temp_umowa_id,$temp_pionnazwa)=mysql_fetch_array($result_upid);
						
							if ($fsz_status==0) {

								if (($nooptions==0) && ($cnt==1)) {
				/*
									$list_pozycje .= "&nbsp;<a title='Sprzedaj towar : $fsz_nazwa ";
									if ($fsz_sn!='') $list_pozycje .= "o numerze seryjnym : $fsz_sn";
							
									$list_pozycje .= "'><input class=imgoption type=image src=img/sell.gif ";

									$list_pozycje .= " onclick=\"newWindow_r(700,595,'z_towary_obrot.php?id=$jeden_towar[$i]&f=$fsz_fnr&obzp=1&trodzaj=".urlencode($fsz_rodzaj)."&allow_change_rs=$acrs&new_upid=$temp_upid&tdata=$wp_sprzedaz_data&nazwa_urzadzenia=".urlencode($nazwa_urzadzenia)."&sn_urzadzenia=".urlencode($sn_urzadzenia)."&ni_urzadzenia=".urlencode($ni_urzadzenia)."&ewid_id=1&quiet=1&readonly=1'); return false;\"";
									$list_pozycje .= "></a>";
				*/
				
								} //else $list_pozycje .= "&nbsp;<img class=imgoption type=image src=img/sell.gif>";
								
							}
							
							if ($fsz_status==1) {
								
								/*
								//if ($nooptions==0) {
									$list_pozycje .= "&nbsp;<a title='Anuluj sprzedaż: $fsz_nazwa ";
									if ($fsz_sn!='') $list_pozycje .= "o numerze seryjnym : $fsz_sn";
									
									$list_pozycje .= "'><input class=imgoption type=image src='img/money_delete.gif' onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_towary_sprzedaz.php?id=$jeden_towar[$i]'); return false;\"></a>";
									
								//} else $list_pozycje .= "&nbsp;<img class=imgoption type=image src=img/money_delete.gif>";
								*/
								$sprzedanych++;
								
								$sql_nr_zestawu = "SELECT zestawpozycja_zestaw_id FROM $dbname.serwis_zestaw_szcz WHERE zestawpozycja_fszcz_id=$jeden_towar[$i] LIMIT 1";
								list($nr_zestawu)=mysql_fetch_array(mysql_query($sql_nr_zestawu,$conn));
								if ($nr_zestawu>0) $elzestawu++;
							}				
						
							if ($fsz_status==5) {
							
								//if ($nooptions==0) {
									$sql_nr_zestawu = "SELECT zestawpozycja_zestaw_id FROM $dbname.serwis_zestaw_szcz WHERE zestawpozycja_fszcz_id=$jeden_towar[$i] LIMIT 1";
									list($nr_zestawu)=mysql_fetch_array(mysql_query($sql_nr_zestawu,$conn));

	/*
									$list_pozycje .= "&nbsp;<a title='Pokaż elementy zestawu";
									//if ($fsz_sn!='') $list_pozycje .= "o numerze seryjnym : $fsz_sn";
									
									$list_pozycje .= "'><input class=imgoption type=image src='img/basket.gif' onclick=\"newWindow_r(800,600,'p_zestaw_pozycje.php?id=$nr_zestawu&sold=0&showall=1&paget=1&allowchanges=0'); return false;\"></a>";
	*/
							//	} else $list_pozycje .= "&nbsp;<img class=imgoption type=image src=img/basket.gif>";
								$elzestawu++;
							}		

						
						$list_pozycje .= " <br />";
						
						$list_pozycje_dla_sprzedazy .= $fsz_nazwa;
						$list_pozycje_dla_sprzedazy2 .= $fsz_nazwa;
						
						if ($fsz_sn!='') $list_pozycje_dla_sprzedazy .= " (SN: ".$fsz_sn.")";
						if ($fsz_sn!='') $list_pozycje_dla_sprzedazy2 .= " (SN: ".$fsz_sn.")";
						
						$list_pozycje_dla_sprzedazy .= ", ";
						$list_pozycje_dla_sprzedazy2 .= ", ";						
					}
					
					$list_pozycje_dla_sprzedazy = substr($list_pozycje_dla_sprzedazy,0,-2);
					$list_pozycje_dla_sprzedazy2 = substr($list_pozycje_dla_sprzedazy2,0,-2);
					
					$opis_inf = "Wybrane podzespoły z magazynu";
					
				} 
				
				if ($_SESSION['podzespoly_do_zgl_nr_'.$_REQUEST[nr].'']=='typ') {
				
					$jeden_typ = explode("|#|", $_SESSION['typ_podzespolu_for_zgloszenie_nr_'.$_REQUEST[nr].'']);
					$list_pozycje = '';
					$list_pozycje_dla_sprzedazy = 'Wymiana części: ';
					
					for ($i=0; $i<$cnt; $i++) { 
						$list_pozycje .= $jeden_typ[$i]."<br />";
						//	echo $jeden_typ[$i]."<br />"; 
						$list_pozycje_dla_sprzedazy .= $jeden_typ[$i].", ";
					}
					
					$list_pozycje_dla_sprzedazy = substr($list_pozycje_dla_sprzedazy,0,-2);
					
					$opis_inf = "Wybrane typy podzespołów";
				}
				
				
				
				
				
				if ($_SESSION['podzespoly_do_zgl_nr_'.$_REQUEST[nr].'']=='zestaw') {
				
					$jeden_towar = explode("|#|", $_SESSION['faktura_szcz_id_for_zgloszenie_nr_'.$_REQUEST[nr].'']);
					//echo $_SESSION['faktura_szcz_id_for_zgloszenie_nr_'.$_REQUEST[nr]];
					
					$list_pozycje = '';
					$list_pozycje_dla_sprzedazy = '';
					$list_pozycje_dla_sprzedazy2 = 'Wymieniono części: ';
					
					$sprzedanych = 0;
					$elzestawu = 0;
					
					for ($i=0; $i<$cnt; $i++) { 
						
						list($fsz_status)=mysql_fetch_array(mysql_query("SELECT pozycja_status FROM $dbname.serwis_faktura_szcz WHERE pozycja_id='$jeden_towar[$i]' LIMIT 1",$conn));
						
						if ($fsz_status==1) $sprzedanych++;
						if ($fsz_status==5) $elzestawu++;
						
					}
					$nooptions = 0;
					if ((($elzestawu<$cnt) && ($elzestawu!=0)) || (($sprzedanych<$cnt) && ($sprzedanych!=0))) $nooptions = 1;
					
					
					$sprzedanych = 0;
					$elzestawu = 0;	
					$onepos = '';
					
					for ($i=0; $i<$cnt; $i++) { 
						
						list($fsz_nazwa,$fsz_sn,$fsz_rodzaj,$fsz_fnr,$fsz_status)=mysql_fetch_array(mysql_query("SELECT pozycja_nazwa, pozycja_sn, pozycja_rodzaj_sprzedazy,pozycja_nr_faktury,pozycja_status FROM $dbname.serwis_faktura_szcz WHERE pozycja_id='$jeden_towar[$i]' LIMIT 1",$conn));
						
						$list_pozycje .= $fsz_nazwa;
						$onepos = $jeden_towar[$i];
						
						if ($fsz_sn!='') $list_pozycje .= " (SN: ".$fsz_sn.")";
						
						if ($fsz_rodzaj=='') { $acrs = 1; } else { $acrs = 0; }
						
						$nazwa_urzadzenia = $_SESSION['typ_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].'']." ".$_SESSION['model_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].''];
						$sn_urzadzenia = $_SESSION['sn_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].''];
						$ni_urzadzenia = $_SESSION['ni_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].''];
					
					
					//echo "<a title=' Sprzedaj towar : $temp_nazwa o numerze seryjnym : $temp_sn '><input class=imgoption type=image src=img/sell.gif  onclick=\"newWindow_r(700,595,'z_towary_obrot.php?id=$temp_id&f=$temp_id4&obzp=1&trodzaj=".urlencode($temp_rs)."&allow_change_rs=$acrs')\"></a>";
					
						$result_upid = mysql_query("SELECT up_id,up_nazwa,up_pion_id,up_umowa_id,pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (up_active=1) and (belongs_to=$es_filia) and (CONCAT(serwis_piony.pion_nazwa,' ',serwis_komorki.up_nazwa)='$temp_komorka') LIMIT 1", $conn) or die($k_b);
				
						list($temp_upid,$temp_nazwa,$temp_pion_id,$temp_umowa_id,$temp_pionnazwa)=mysql_fetch_array($result_upid);
						
							if ($fsz_status==0) {

								if (($nooptions==0) && ($cnt==1)) {
				/*
									$list_pozycje .= "&nbsp;<a title='Sprzedaj towar : $fsz_nazwa ";
									if ($fsz_sn!='') $list_pozycje .= "o numerze seryjnym : $fsz_sn";
							
									$list_pozycje .= "'><input class=imgoption type=image src=img/sell.gif ";

									$list_pozycje .= " onclick=\"newWindow_r(700,595,'z_towary_obrot.php?id=$jeden_towar[$i]&f=$fsz_fnr&obzp=1&trodzaj=".urlencode($fsz_rodzaj)."&allow_change_rs=$acrs&new_upid=$temp_upid&tdata=$wp_sprzedaz_data&nazwa_urzadzenia=".urlencode($nazwa_urzadzenia)."&sn_urzadzenia=".urlencode($sn_urzadzenia)."&ni_urzadzenia=".urlencode($ni_urzadzenia)."&ewid_id=1&quiet=1&readonly=1'); return false;\"";
									$list_pozycje .= "></a>";
				*/
				
								} //else $list_pozycje .= "&nbsp;<img class=imgoption type=image src=img/sell.gif>";
								
							}
							
							if ($fsz_status==1) {
								
								/*
								//if ($nooptions==0) {
									$list_pozycje .= "&nbsp;<a title='Anuluj sprzedaż: $fsz_nazwa ";
									if ($fsz_sn!='') $list_pozycje .= "o numerze seryjnym : $fsz_sn";
									
									$list_pozycje .= "'><input class=imgoption type=image src='img/money_delete.gif' onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_towary_sprzedaz.php?id=$jeden_towar[$i]'); return false;\"></a>";
									
								//} else $list_pozycje .= "&nbsp;<img class=imgoption type=image src=img/money_delete.gif>";
								*/
								$sprzedanych++;
								
								$sql_nr_zestawu = "SELECT zestawpozycja_zestaw_id FROM $dbname.serwis_zestaw_szcz WHERE zestawpozycja_fszcz_id=$jeden_towar[$i] LIMIT 1";
								list($nr_zestawu)=mysql_fetch_array(mysql_query($sql_nr_zestawu,$conn));
								if ($nr_zestawu>0) $elzestawu++;
							}				
						
							if ($fsz_status==5) {
							
								//if ($nooptions==0) {
									$sql_nr_zestawu = "SELECT zestawpozycja_zestaw_id FROM $dbname.serwis_zestaw_szcz WHERE zestawpozycja_fszcz_id=$jeden_towar[$i] LIMIT 1";
									list($nr_zestawu)=mysql_fetch_array(mysql_query($sql_nr_zestawu,$conn));

	
	//								$list_pozycje .= "&nbsp;<a title='Pokaż elementy zestawu";
									//if ($fsz_sn!='') $list_pozycje .= "o numerze seryjnym : $fsz_sn";
		/*							
									$list_pozycje .= "'><input class=imgoption type=image src='img/basket.gif' onclick=\"newWindow_r(800,600,'p_zestaw_pozycje.php?id=$nr_zestawu&sold=0&showall=1&paget=1&allowchanges=0'); return false;\"></a>";
	*/
							//	} else $list_pozycje .= "&nbsp;<img class=imgoption type=image src=img/basket.gif>";
								$elzestawu++;
							}		

						
						$list_pozycje .= " <br />";
						
						$list_pozycje_dla_sprzedazy .= $fsz_nazwa;
						$list_pozycje_dla_sprzedazy2 .= $fsz_nazwa;
						
						if ($fsz_sn!='') $list_pozycje_dla_sprzedazy .= " (SN: ".$fsz_sn.")";
						if ($fsz_sn!='') $list_pozycje_dla_sprzedazy2 .= " (SN: ".$fsz_sn.")";
						
						$list_pozycje_dla_sprzedazy .= ", ";
						$list_pozycje_dla_sprzedazy2 .= ", ";						
					}
					
					$list_pozycje_dla_sprzedazy = substr($list_pozycje_dla_sprzedazy,0,-2);
					$list_pozycje_dla_sprzedazy2 = substr($list_pozycje_dla_sprzedazy2,0,-2);
					
					$opis_inf = "Wybrany zestaw z magazynu";
					
				}
				
				$pokaz_informacje_o_wc = 0;
				
				if ($pokaz_informacje_o_wc==1) {
					$inf_wykonane_czynnosci = "<br /><br /><b>Wykonane czynności:</b> <br />".$_SESSION['wc_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].'']."";
				} else $inf_wykonane_czynnosci = '';
				
				$inf_nazwa_zestawu = '';
				if ($elzestawu==$cnt) {
					$sql_nazwa_zestawu = "SELECT zestaw_opis FROM $dbname.serwis_zestawy WHERE zestaw_id=$nr_zestawu LIMIT 1";
					list($nazwa_zestawu)=mysql_fetch_array(mysql_query($sql_nazwa_zestawu,$conn));
				
					//$inf_nazwa_zestawu = "<br />Elementy zestawu: <b>".$nazwa_zestawu." </b>";
					$inf_nazwa_zestawu = "<br />".$nazwa_zestawu."<br />";
				}
				
				echo "<br />";
				infoheader("<b>Wybrany sprzęt:</b><br />".$_SESSION['typ_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].'']." ".$_SESSION['model_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].'']."<br />SN: ".$_SESSION['sn_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].'']."<br />NI: ".$_SESSION['ni_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].'']."".$inf_wykonane_czynnosci."<br /><br /><b>".$opis_inf.":</b>".$inf_nazwa_zestawu."<br />".$list_pozycje."");
				
				if ($_SESSION['podzespoly_do_zgl_nr_'.$_REQUEST[nr].'']=='magazyn') {
					errorheader("Aby wybrane podzespoły zostały powiązane ze zgłoszeniem, należy zapisać krok zgłoszenia do bazy");
				}
				if ($_SESSION['podzespoly_do_zgl_nr_'.$_REQUEST[nr].'']=='typ') {
					errorheader("Aby wybrane typy podzespołów zostały powiązane ze zgłoszeniem, należy zapisać krok zgłoszenia do bazy");
				}
				if ($_SESSION['podzespoly_do_zgl_nr_'.$_REQUEST[nr].'']=='zestaw') {
					errorheader("Aby wybrany zestaw został powiązany ze zgłoszeniem, należy zapisać krok zgłoszenia do bazy");
				}
				
				$fszid = 0;
				
				$niepokazujopcji = 0;
				
				//if (($elzestawu<$cnt) && ($sprzedanych<$cnt)) $niepokazujopcji = 1;
				
				if (($allow_sell==1) && ($_SESSION['podzespoly_do_zgl_nr_'.$_REQUEST[nr].'']=='magazyn')){
					
					if ($sprzedanych==0) {
						$fszid = $_SESSION['faktura_szcz_id_for_zgloszenie_nr_'.$_REQUEST[id].''];

						list($poz_nazwa, $poz_sn, $poz_f_numer, $poz_rs)=mysql_fetch_array(mysql_query("SELECT pozycja_nazwa, pozycja_sn, pozycja_nr_faktury, pozycja_rodzaj_sprzedazy FROM $dbname.serwis_faktura_szcz WHERE pozycja_id='$fszid' LIMIT 1",$conn));
					
						if ($poz_rs=='') { $acrs = 1; } else { $acrs = 0; }
					
						$result_upid = mysql_query("SELECT up_id,up_nazwa,up_pion_id,up_umowa_id,pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (up_active=1) and (belongs_to=$es_filia) and (CONCAT(serwis_piony.pion_nazwa,' ',serwis_komorki.up_nazwa)='$temp_komorka') LIMIT 1", $conn) or die($k_b);
				
						list($temp_upid,$temp_nazwa,$temp_pion_id,$temp_umowa_id,$temp_pionnazwa)=mysql_fetch_array($result_upid);
						
						$nazwa_urzadzenia = $_SESSION['typ_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].'']." ".$_SESSION['model_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].''];
						$sn_urzadzenia = $_SESSION['sn_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].''];
						$ni_urzadzenia = $_SESSION['ni_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].''];
						
						//startbuttonsarea("left");
						
						// jeżeli wszystkie elementy są w jednym zestawie
						
						if (($elzestawu==0) && ($cnt>1)) {
							okheader("Aby dokonać sprzedaży wybranych podzespołów, należy utworzyć z nich zestaw");
							
							echo "<input type=button class=buttons value='Utwórz zestaw z wybranych podzespołów' onClick=\"newWindow(800,600,'hd_g_zestaw_z_wymiany_podzespolow.php?nr=".$_REQUEST[id]."&pozcnt=".$cnt."&pozfsz=".urlencode($_SESSION['faktura_szcz_id_for_zgloszenie_nr_'.$_REQUEST[nr].''])."&hd_zgl_nr=$_GET[nr]'); return false;\">";
						}

						if (($cnt==1) && ($sprzedanych==0)) {
							echo "<input type=button class=buttons style='color:green;font-weight:normal;' value='Sprzedaj wybrany podzespół'  onclick=\"newWindow_r(700,595,'z_towary_obrot.php?id=$onepos&f=$fsz_fnr&obzp=1&trodzaj=".urlencode($fsz_rodzaj)."&allow_change_rs=$acrs&new_upid=$temp_upid&tdata=$wp_sprzedaz_data&nazwa_urzadzenia=".urlencode($nazwa_urzadzenia)."&sn_urzadzenia=".urlencode($sn_urzadzenia)."&ni_urzadzenia=".urlencode($ni_urzadzenia)."&ewid_id=1&quiet=1&readonly=1&hd_zgl_nr=$_GET[nr]'); return false;\">";
						}
						
						
						if ($elzestawu==$cnt) {
						//	$nr_zestawu
							echo "<input type=button class=buttons style='color:green;font-weight:normal;' value='Sprzedaj zestaw' onclick=\"newWindow_r(700,595,'g_zestaw_obrot.php?id=$nr_zestawu&allow_change_rs=0&new_upid=$temp_upid&readonly=1&nazwa_urzadzenia=".urlencode($nazwa_urzadzenia)."&sn_urzadzenia=".urlencode($sn_urzadzenia)."&ni_urzadzenia=".urlencode($ni_urzadzenia)."&ewid_id=1&quiet=1&hd_zgl_nr=$_GET[nr]'); return false;\">";
							
							echo "<input type=button class=buttons value='Usuń zestaw' onclick=\"if (confirm('Czy napewno chcesz usunąć utworzony zestaw ?')) { newWindow_r(700,595,'u_zestaw_caly.php?id=$nr_zestawu'); return false; }\">";
							
						} 
						echo "<span style='float:right'>";
							echo "<input type=button class=buttons style='color:red;font-weight:normal;' value='Utwórz protokół przekazania podzespołu/ów' onclick=\"newWindow_r(700,595,'utworz_protokol.php?source=only-protokol&state=empty&c_7=on&new_upid=$temp_upid&readonly=1&wykonane_czynnosci=".urlencode($wc)."&choosefromewid=0&nazwa_urzadzenia=".urlencode($nazwa_urzadzenia)."&sn_urzadzenia=".urlencode($sn_urzadzenia)."&ni_urzadzenia=".urlencode($ni_urzadzenia)."&quiet=1&ewid_id=1&wykonane_czynnosci=".urlencode($list_pozycje_dla_sprzedazy2)."&getfromparentwindow=0&hd_zgl_nr=$_GET[nr]')\"></a>";
						echo "</span>";
						
						//echo "<input type=button class=buttons style='color:green; font-weight:normal;' title=' Sprzedaj towar : $poz_nazwa o numerze seryjnym : $poz_sn ' onclick=\"newWindow_r(700,595,'z_towary_obrot.php?id=$fszid&f=$poz_f_numer&obzp=1&trodzaj=".urlencode($poz_rs)."&allow_change_rs=$acrs&new_upid=$temp_upid&tdata=$wp_sprzedaz_data&trodzaj=Towar&nazwa_urzadzenia=".urlencode($nazwa_urzadzenia)."&sn_urzadzenia=".urlencode($sn_urzadzenia)."&ni_urzadzenia=".urlencode($ni_urzadzenia)."&ewid_id=1&quiet=1'); return false;\" value='Sprzedaj wybrany towar z magazynu'>";
						//endbuttonsarea();
					}
					


					if ((($elzestawu<$cnt) && ($elzestawu!=0)) || (($sprzedanych<$cnt) && ($sprzedanych!=0))) {
						errorheader('Aby dokonać sprzedaży wybraych podzespołów, należy sprzedać je pojedynczo lub ze wszystkich utworzyć jeden zestaw');
					}
					
				} elseif (($allow_sell==1) && ($_SESSION['podzespoly_do_zgl_nr_'.$_REQUEST[nr].'']=='zestaw')) {
			  
				  		if ($elzestawu==$cnt) {
						//	$nr_zestawu
							echo "<input type=button class=buttons style='color:green;font-weight:normal;' value='Sprzedaj zestaw' onclick=\"newWindow_r(700,595,'g_zestaw_obrot.php?id=$nr_zestawu&allow_change_rs=0&new_upid=$temp_upid&readonly=1&nazwa_urzadzenia=".urlencode($nazwa_urzadzenia)."&sn_urzadzenia=".urlencode($sn_urzadzenia)."&ni_urzadzenia=".urlencode($ni_urzadzenia)."&ewid_id=1&quiet=1&hd_zgl_nr=$_GET[nr]'); return false;\">";
							
							//echo "<input type=button class=buttons value='Usuń zestaw' onclick=\"if (confirm('Czy napewno chcesz usunąć utworzony zestaw ?')) { newWindow_r(700,595,'u_zestaw_caly.php?id=$nr_zestawu'); return false; }\">";

							echo "<span style='float:right'>";
							echo "<input type=button class=buttons style='color:red;font-weight:normal;' value='Utwórz protokół przekazania zestawu' onclick=\"newWindow_r(700,595,'utworz_protokol.php?source=only-protokol&state=empty&c_7=on&new_upid=$temp_upid&readonly=1&wykonane_czynnosci=".urlencode($wc)."&choosefromewid=0&nazwa_urzadzenia=".urlencode($nazwa_urzadzenia)."&sn_urzadzenia=".urlencode($sn_urzadzenia)."&ni_urzadzenia=".urlencode($ni_urzadzenia)."&quiet=1&ewid_id=1&wykonane_czynnosci=".urlencode($list_pozycje_dla_sprzedazy2)."&getfromparentwindow=0&hd_zgl_nr=$_GET[nr]')\"></a>";
							echo "</span>";
						} 
				  
				} else {
					$result_upid = mysql_query("SELECT up_id,up_nazwa,up_pion_id,up_umowa_id,pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (up_active=1) and (belongs_to=$es_filia) and (CONCAT(serwis_piony.pion_nazwa,' ',serwis_komorki.up_nazwa)='$temp_komorka') LIMIT 1", $conn) or die($k_b);
				
					list($temp_upid,$temp_nazwa,$temp_pion_id,$temp_umowa_id,$temp_pionnazwa)=mysql_fetch_array($result_upid);
						
					$nazwa_urzadzenia = $_SESSION['typ_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].'']." ".$_SESSION['model_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].''];
					$sn_urzadzenia = $_SESSION['sn_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].''];
					$ni_urzadzenia = $_SESSION['ni_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].''];
					echo "<span style='float:right'>";
						echo "<input type=button class=buttons style='color:red;font-weight:normal;' value='Utwórz protokół przekazania podzespołów' onclick=\"newWindow_r(700,595,'utworz_protokol.php?source=only-protokol&state=empty&c_7=on&new_upid=$temp_upid&readonly=1&choosefromewid=0&nazwa_urzadzenia=".urlencode($nazwa_urzadzenia)."&sn_urzadzenia=".urlencode($sn_urzadzenia)."&ni_urzadzenia=".urlencode($ni_urzadzenia)."&quiet=1&ewid_id=1&wykonane_czynnosci=".urlencode($list_pozycje_dla_sprzedazy)."&getfromparentwindow=0&hd_zgl_nr=$_GET[nr]')\"></a>";
					echo "</span>";
				}
				
				if ($fszid!=0) {
					echo "<input type=hidden name=wp_fszcz_id value='$fszid'>";
				} else {
					echo "<input type=hidden name=wp_fszcz_id value=0>";
				}
				
				//echo $elzestawu." ".$cnt." ".$sprzedanych;
				
				if ($elzestawu==$cnt) {
					if ($sprzedanych==$cnt) {
						echo "<input type=button class=buttons value='Wycofaj sprzedaż zestawu' onClick=\"if (confirm('Czy napewno chcesz wycofać sprzedaż utworzonego zestawu ?')) newWindow(10,10,'u_zestaw_sprzedaz.php?id=".$nr_zestawu."&noquestion=1');\" />";
					} else {
						echo "<input type=button class=buttons style='color:red' value='Anuluj wymianę podzespołów z zestawu' onClick=\"if (confirm('Czy napewno chcesz anulować wymianę podzespołów z zestawu dla tego kroku zgłoszenia ?')) newWindow(10,10,'z_wymiana_podzespolu_anuluj.php?id=".$_REQUEST[id]."&zestawid=');\" />";
						
						echo "<input type=button class=buttons style='color:red' value='Anuluj wymianę podzespołów z zestawu i usuń zestaw' onClick=\"if (confirm('Czy napewno chcesz anulować wymianę podzespołów z zestawu dla tego kroku zgłoszenia ? Utworzony z podzespołów zestaw zostanie również usunięty. ')) newWindow(10,10,'z_wymiana_podzespolu_anuluj.php?id=".$_REQUEST[id]."&zestawid=".$nr_zestawu."');\" />";
					}
				} else {
					if ($sprzedanych==$cnt) {
						if ($_SESSION['podzespoly_do_zgl_nr_'.$_REQUEST[id].'_count']==1) {
							
							$temp_fakt_id = $_SESSION['faktura_szcz_id_for_zgloszenie_nr_'.$_REQUEST[id].''];

							echo "<br /><input type=button class=buttons value='Usuń sprzedaż i anuluj wymianę podzespołu' onClick=\"if (confirm('Czy napewno chcesz anulować wymianę podzespołu dla tego kroku zgłoszenia ? Wykonana sprzedaż zostanie wycofana.')) newWindow(10,10,'u_towary_sprzedaz.php?id=".$temp_fakt_id."&noquestion=1');\" />";
						}
					} else {
						echo "<input type=button class=buttons value='Anuluj wymianę podzespołu / ów' onClick=\"if (confirm('Czy napewno chcesz anulować wymianę podzespołów dla tego kroku zgłoszenia ?')) newWindow(10,10,'z_wymiana_podzespolu_anuluj.php?id=".$_REQUEST[id]."');\" />";
					}
				}
			}
		}
		_td();
	echo "</tr>";
	
	echo "<tr id=NaprawaWeWlasnymZakresie style='display:none'>";
		td("140;r;");
		td_(";;;");
			if ($move_naprawa==true) {
				// do dokończenia (problem z protokołami)
				if (($temp_naprawa_id>0) && ($mstatus=='-1')) { 
					//echo "<input type=button class=buttons value=' Naprawiaj sprzęt we własnym zakresie ' onclick=\"newWindow_r(800,600,'z_naprawy_status.php?id=$temp_naprawa_id&cs=-1&from=hd&filtruj=wwz&hdzglid=$_GET[nr]&hd_zgl_nr=$_GET[nr]');\" >";	
					
					echo "<p class='block block_naprawa_wz' onClick=\"newWindow_r(800,600,'z_naprawy_status.php?id=$temp_naprawa_id&cs=-1&from=hd&filtruj=wwz&hdzglid=$_GET[nr]&hd_zgl_nr=$_GET[nr]'); return false;\">";
						echo "<b>Naprawiaj sprzęt we własnym zakresie</b>";
					echo "</p>";				
				}
			}
		_td();
	echo "</tr>";

	echo "<tr id=PrzerzucZNWWZDoSerwisuZewnetrznego style='display:none'>";
		td("140;r;");
		td_(";;;");
			if ($move_naprawa==true) {
				// do dokończenia (problem z protokołami)
				if (($temp_naprawa_id>0) && ($mstatus=='0')) { 
					//echo "<input type=button class=buttons value=' Przesuń naprawiany sprzęt we własnym zakresie do serwisu zewnętrznego ' onclick=\"newWindow_r(800,600,'z_naprawy_status.php?id=$temp_naprawa_id&cs=0&from=hd&hdzglid=$_GET[nr]&hd_zgl_nr=$_GET[nr]');\" >";
					
					echo "<p class='block block_naprawa_do_sz' onClick=\"newWindow_r(800,600,'z_naprawy_status.php?id=$temp_naprawa_id&cs=-1&from=hd&hdzglid=$_GET[nr]&hd_zgl_nr=$_GET[nr]'); return false;\">";
						echo "<b>Przesuń naprawę do serwisu zewnętrznego</b>";
					echo "</p>";					
				}
			}
		_td();
	echo "</tr>";
	//echo ">>>>".$_SESSION['naprawa_id_for_zgloszenie_nr_'.$_REQUEST[nr].''];
	//echo "wybrany sprzet do wymiany: ".$_SESSION['wybrany_sprzet_do_wymiany_dla_zgl_nr_'.$_REQUEST[id].''];

	echo "<tr id=PrzyjmijUszkodzonySprzet style='display:none'>";
		td("140;r;");
		td_(";;;");
		// do dokończenia (problem z protokołami)
//echo ">".$temp_naprawa_id;
			if ($temp_naprawa_id>0) {
				echo "<input type=hidden id=naprawaid name=naprawaid value='$temp_naprawa_id'>";
				//echo ">>>>>>".$temp_naprawa_id;
				//echo "Zgłoszenie powiązane jest z naprawą";
			/*	nowalinia();
				$result99 = mysql_query("SELECT * FROM $dbname.serwis_naprawa WHERE naprawa_hd_zgl_id='$_REQUEST[nr]'");
				while ($dane99 = mysql_fetch_array($result99)) {
					$mid = $dane99['naprawa_id'];					
					$mnazwa = $dane99['naprawa_nazwa'];
					$mmodel= $dane99['naprawa_model'];			
					$msn = $dane99['naprawa_sn'];
			//echo "<a title=' Pokaż szczegóły naprawy'><input class=imgoption type=image src=img/detail.gif  onclick=\"newWindow(800,600,'p_naprawy_szczegoly.php?id=$mid')\"></a>";
					infoheader("Zgłoszenie powiązane jest z naprawą: <br />".$mnazwa." <b>".$mmodel."</b> (SN: ".$msn.")&nbsp; <a title=' Pokaż szczegóły naprawy'><input class=imgoption type=image src=img/detail.gif  onclick=\"newWindow(800,600,'p_naprawy_szczegoly.php?id=$mid'); return false;\"></a><br />");
				}
				*/
				
			} else {
				$w1 = mysql_query("SELECT zgl_komorka,zgl_tresc,zgl_podkategoria FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$es_filia) and (zgl_nr=$_REQUEST[nr])) LIMIT 1", $conn_hd) or die($k_b);

				list($zgl_komorka_nazwa, $temp_zgl_tresc,$temp_zgl_podkat)=mysql_fetch_array($w1);
				//$zgl_komorka_nazwa = substr($zgl_komorka_nazwa,strpos($zgl_komorka_nazwa," ")+1,strlen($zgl_komorka_nazwa));

				//$zgl_komorka_nazwa = substr($zgl_komorka_nazwa,strpos($zgl_komorka_nazwa," ")+1,strlen($zgl_komorka_nazwa));
					
				$result44 = mysql_query("SELECT serwis_komorki.up_id,serwis_komorki.up_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) and (CONCAT(UPPER(serwis_piony.pion_nazwa),' ',UPPER(serwis_komorki.up_nazwa)) = '$zgl_komorka_nazwa')) LIMIT 1", $conn) or die($k_b);

				list($temp_up_id,$zgl_komorka_nazwa) = mysql_fetch_array($result44);

				if (($temp_czy_pow_z_wp==0) && ($temp_czy_pow_z_wp_parent==0)) {
					if ($temp_naprawa_id_parent>0) { } else {
						if ($temp_podkategoria2!='') {
							$temp_rn = '';
							$w1 = mysql_query("SELECT rola_id FROM $dbname.serwis_slownik_rola WHERE (rola_nazwa='$temp_podkategoria2') LIMIT 1", $conn) or die($k_b);
							list($temp_rn)=mysql_fetch_array($w1);
							if ($temp_rn>0) {
								$temp_tpk2p = $temp_podkategoria2;
							} else { 
								$temp_rn = ''; 
								$temp_tpk2p = '';
							}
						}

						echo "<p id=p_PrzyjmijUszkodzonySprzet style='dispay:' class='block block_przyjmij_naprawa' onClick=\"newWindow_r(800,600,'z_naprawy_przyjmij.php?cat=".urlencode($temp_tpk2p)."&typid=$temp_rn&new_upid=$temp_up_id&hd_podkategoria_nr=$_REQUEST[tpk]&from=hd&hd_nr=$_REQUEST[nr]&tresc_zgl=".urlencode($temp_zgl_tresc)."&tup=".urlencode($zgl_komorka_nazwa)."&przyjmij=1&hd_zgl_nr=$_GET[nr]'); return false;\">";
							echo "<b>Uruchom kreator przyjmowania uszkodzonego sprzętu</b>";
						echo "</p>";
					}
					
				} else {
					$tn = substr($wp_o,0,strpos($wp_o,' '));
					$tm = substr($wp_o,strpos($wp_o,' ')+1,strlen($wp_o));
					$tsn = $wp_sn;
					$tni = $wp_ni;

					echo "<p id=p_PrzyjmijUszkodzonySprzet  style='dispay:' class='block block_przyjmij_naprawa' onClick=\"newWindow_r(800,600,'z_naprawy_uszkodzony.php?new_upid=$temp_up_id&hd_podkategoria_nr=$_REQUEST[tpk]&from=hd&hd_nr=$_REQUEST[nr]&tresc_zgl=".urlencode($temp_zgl_tresc)."&tup=".urlencode($zgl_komorka_nazwa)."&przyjmij=1&auto=1&tnazwa=".urlencode($tn)."&tmodel=".urlencode($tm)."&tsn=".urlencode($tsn)."&tni=".urlencode($tni)."&noback=1&hd_zgl_nr=$_GET[nr]'); return false;\">";
						echo "<b>Uruchom kreator przyjmowania uszkodzonego sprzętu</b>";
					echo "</p>";					
				}
				
				$result441 = mysql_query("SELECT serwis_komorki.up_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) and (CONCAT(UPPER(serwis_piony.pion_nazwa),' ',UPPER(serwis_komorki.up_nazwa)) = '$_REQUEST[komorka]')) LIMIT 1", $conn) or die($k_b);
				list($sama_nazwa_up) = mysql_fetch_array($result441);
			
				//$sama_nazwa_up = substr($_REQUEST[komorka],strpos($_REQUEST[komorka]," ")+1,strlen($_REQUEST[komorka]));
				
				$sql_up1 = "SELECT up_pion_id FROM $dbname.serwis_komorki WHERE (UPPER(up_nazwa)='$sama_nazwa_up') and (belongs_to=$es_filia) LIMIT 1";
				$wynik1 = mysql_query($sql_up1, $conn) or die($k_b);
				$dane_up1 = mysql_fetch_array($wynik1);
				$temp_pion_id = $dane_up1['up_pion_id'];
				
				// nazwa pionu z id pionu
				$sql_get_pion = "SELECT pion_nazwa FROM $dbname.serwis_piony WHERE (pion_id='$temp_pion_id') LIMIT 1";
				$wynik_get_pion = mysql_query($sql_get_pion, $conn) or die($k_b);
				$dane_get_pion = mysql_fetch_array($wynik_get_pion);
				$temp_pion_nazwa = $dane_get_pion['pion_nazwa'];
				
				// koniec ustalania nazwy pionu

				$zapyt = "SELECT * FROM $dbname.serwis_naprawa WHERE (belongs_to=$es_filia) and (naprawa_status='-1') and (naprawa_hd_zgl_id=0) and (UPPER(CONCAT('".$temp_pion_nazwa." ',naprawa_pobrano_z)='$_REQUEST[komorka]')) "; 
				
				//echo $zapyt;
				//if (($temp_zgl_podkat=='3') || ($temp_zgl_podkat=='4') || ($temp_zgl_podkat=='2') || ($temp_zgl_podkat=='5') || ($temp_zgl_podkat=='0')) $zapyt .= " AND ((naprawa_nazwa='Komputer') or (naprawa_nazwa='Serwer') or (naprawa_nazwa='Notebook') or (naprawa_nazwa='Monitor')) ";
				//if (($temp_zgl_podkat=='9')) $zapyt .= " AND ((naprawa_nazwa<>'Komputer') and (naprawa_nazwa<>'Serwer') and (naprawa_nazwa<>'Notebook')) ";
				
				$zapyt .= " ORDER BY naprawa_nazwa, naprawa_model, naprawa_sn ASC";
				
				//echo ">".$zapyt;
				
				$result = mysql_query($zapyt,$conn);
				$count_usnp = mysql_num_rows($result);
				
				// sprawdź czy nie ma napraw pobranych,powiązanych z zamkniętymi zgłoszeniami Helpdesk
				if ($count_usnp==0) {
					$zapyt = "SELECT * FROM $dbname.serwis_naprawa,$dbname_hd.hd_zgloszenie WHERE (hd_zgloszenie.belongs_to=$es_filia) and (serwis_naprawa.naprawa_status='-1') and (serwis_naprawa.naprawa_hd_zgl_id>0) and (hd_zgloszenie.zgl_status=9) and (UPPER(CONCAT('".$temp_pion_nazwa." ',serwis_naprawa.naprawa_pobrano_z)='$_REQUEST[komorka]')) and (serwis_naprawa.naprawa_id=hd_zgloszenie.zgl_naprawa_id)"; 
					//echo $zapyt;
					$result = mysql_query($zapyt,$conn);
					$count_usnp = mysql_num_rows($result);
				}
				
				if (($count_usnp>0) && ($temp_czy_pow_z_wp==0)) {
					$auto_select = 0;
					if ($_SESSION['naprawa_id_for_zgloszenie_nr_'.$_REQUEST[id].'']>0) $auto_select = 1;
//echo ">".$auto_select." | ".$_SESSION['naprawa_id_for_zgloszenie_nr_'.$_REQUEST[id].'']."";
					$auto_select_naprawa_id = $_SESSION['naprawa_id_for_zgloszenie_nr_'.$_REQUEST[id].''];
					
					echo "<br /><input type=button id=PokazSprzetZListy style='display:";
					//if ($auto_select==1) { echo "none"; } else { echo ""; }
					echo "' class=buttons value=' Wybierz inny uszkodzony sprzęt z listy ' onClick=\"document.getElementById('naprawaid').style.display=''; document.getElementById('PokazSprzetZListy').style.display='none'; document.getElementById('UkryjSprzetZListy').style.display=''; "; 
					if ($auto_select==1) { echo " document.getElementById('InfoOWynranymSprzecie').style.display='none'; document.getElementById('naprawaid').value=".$_SESSION['naprawa_id_for_zgloszenie_nr_'.$_REQUEST[id].'']."; "; }
					echo "\">";
					
					echo "<input type=button id=UkryjSprzetZListy style='display:";
					if ($auto_select==1) { echo "none"; } else { echo "none"; }
					echo "' class=buttons value=' Wybierz ostatni sprzęt wybrany z kreatora ' onClick=\"document.getElementById('naprawaid').style.display='none'; document.getElementById('PokazSprzetZListy').style.display=''; document.getElementById('UkryjSprzetZListy').style.display='none'; ";
					
					echo "document.getElementById('naprawaid').value='".$_SESSION['naprawa_id_for_zgloszenie_nr_'.$_REQUEST[id].'']."';"; 
					
					if ($auto_select==1) { echo " document.getElementById('InfoOWynranymSprzecie').style.display=''; "; } 
					
					echo "\">";
					
					//echo "<input type=button value='1' onClick=\"alert(document.getElementById('naprawaid').value);\" />";
					//echo "<br />lub<br />";
				
					if ($auto_select==1) {
						//echo "<br /><b>Informacje o sprzęcie uszkodzonym powiązanym ze zgłoszeniem:</b>";
					}

					echo "<br /><select "; 
					if ($auto_select==1) { echo "style='display:none' "; } else { echo " style='display:none'"; }
					  
					echo " class=wymagane id=naprawaid name=naprawaid onkeypress='return handleEnter(this, event);' onChange=\"if (this.value==0) { document.getElementById('hd_diagnoza_uwagi').value=''; document.getElementById('hd_diagnoza_uwagi').style.display='none';} else { document.getElementById('hd_diagnoza_uwagi').style.display=''; } \">\n";
					
					echo "<option value='-1'>Brak powiązania naprawy ze zgłoszeniem";

					while ($dane = mysql_fetch_array($result)) {
						$mid 	= $dane['naprawa_id'];					
						$mnazwa = $dane['naprawa_nazwa'];
						$mmodel	= $dane['naprawa_model'];			
						$msn 	= $dane['naprawa_sn'];
						$mni 	= $dane['naprawa_ni'];
						$moo	= $dane['naprawa_osoba_pobierajaca'];	
						$mdp	= $dane['naprawa_data_pobrania'];
						$msz 	= $dane['naprawa_sprzet_zastepczy_id'];
						
						//echo "<option value=''>$mid</option>";
						
						echo "<option value=$mid";
						if ($auto_select==1) {
							if ($mid==$_SESSION['naprawa_id_for_zgloszenie_nr_'.$_REQUEST[id].'']) {
								echo " SELECTED ";
								$auto_nazwa = $mnazwa;
								$auto_model= $mmodel;
								$auto_sn = $msn;
								$auto_ni = $mni;
							}
						}
						echo ">$mnazwa $mmodel";
						
						if ($msn!='') echo " | SN: $msn";
						if ($mni!='') echo " | NI: $mni";
						
						if ($msz!=0) {
							$result98 = mysql_query("SELECT magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni FROM $dbname.serwis_magazyn WHERE (magazyn_id=$msz) and (belongs_to=$es_filia) LIMIT 1", $conn) or die($k_b);
							list($mnazwa, $mmodel, $msn, $mni)=mysql_fetch_array($result98);
							
							echo " (Sprzęt zastępczy: $mnazwa $mmodel)";
							
						}
						echo "</option>\n"; 
					}
					
					echo "</select>";			
				
					if ($auto_select==1) {
						echo "<span id=InfoOWynranymSprzecie style='display:'>";
						
							if ($msz!=0) {
								$result98 = mysql_query("SELECT magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni FROM $dbname.serwis_magazyn WHERE (magazyn_id=$msz) and (belongs_to=$es_filia) LIMIT 1", $conn) or die($k_b);
								list($mnazwa, $mmodel, $msn, $mni)=mysql_fetch_array($result98);								
								//echo " (Sprzęt zastępczy: $mnazwa $mmodel)";
								infoheader("Wybrany sprzęt do powiązania ze zgłoszeniem:<br /><br /><b>$auto_nazwa $auto_model</b>, SN: <b>$auto_sn, </b>NI: <b>$auto_ni</b><br /><br /><b>Sprzęt zastępczy:</b> $mnazwa $mmodel | SN: $msn | NI: $mni");
								echo "<input type=hidden name=PokazPrzekazanieSS id=PokazPrzekazanieSS value='0'>";
								
							} else {
								infoheader("Wybrany sprzęt do powiązania ze zgłoszeniem:<br /><br /><b>$auto_nazwa $auto_model</b>, SN: <b>$auto_sn, </b>NI: <b>$auto_ni</b>");
								echo "<input type=hidden name=PokazPrzekazanieSS id=PokazPrzekazanieSS value='1'>";
							}
							
							
							//echo "<input type=hidden name=naprawaid value='$auto_select_naprawa_id'>";
						echo "</span>";
						}
					}
				}	
		_td();
	echo "</tr>";
	
	echo "<tr id=PowiazZNaprawaWSZ style='display:none'>";
		td("140;r;");
		td_(";;;");
		// do dokończenia (problem z protokołami)

			if ($temp_naprawa_id>0) {
				echo "<input type=hidden id=naprawaid name=naprawaid value='$temp_naprawa_id'>";
				//echo "Zgłoszenie powiązane jest z naprawą";
			/*	nowalinia();
				$result99 = mysql_query("SELECT * FROM $dbname.serwis_naprawa WHERE naprawa_hd_zgl_id='$_REQUEST[nr]'");
				while ($dane99 = mysql_fetch_array($result99)) {
					$mid = $dane99['naprawa_id'];					
					$mnazwa = $dane99['naprawa_nazwa'];
					$mmodel= $dane99['naprawa_model'];			
					$msn = $dane99['naprawa_sn'];
			//echo "<a title=' Pokaż szczegóły naprawy'><input class=imgoption type=image src=img/detail.gif  onclick=\"newWindow(800,600,'p_naprawy_szczegoly.php?id=$mid')\"></a>";
					infoheader("Zgłoszenie powiązane jest z naprawą: <br />".$mnazwa." <b>".$mmodel."</b> (SN: ".$msn.")&nbsp; <a title=' Pokaż szczegóły naprawy'><input class=imgoption type=image src=img/detail.gif  onclick=\"newWindow(800,600,'p_naprawy_szczegoly.php?id=$mid'); return false;\"></a><br />");
				}
				*/
				
			} else {
				$w1 = mysql_query("SELECT zgl_komorka,zgl_tresc,zgl_podkategoria FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$es_filia) and (zgl_nr=$_REQUEST[nr])) LIMIT 1", $conn_hd) or die($k_b);
					
				list($zgl_komorka_nazwa, $temp_zgl_tresc,$temp_zgl_podkat)=mysql_fetch_array($w1);
				//$zgl_komorka_nazwa = substr($zgl_komorka_nazwa,strpos($zgl_komorka_nazwa," ")+1,strlen($zgl_komorka_nazwa));
				
				//$zgl_komorka_nazwa = substr($zgl_komorka_nazwa,strpos($zgl_komorka_nazwa," ")+1,strlen($zgl_komorka_nazwa));
					
				$result44 = mysql_query("SELECT serwis_komorki.up_id,serwis_komorki.up_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) and (CONCAT(UPPER(serwis_piony.pion_nazwa),' ',UPPER(serwis_komorki.up_nazwa)) = '$zgl_komorka_nazwa')) LIMIT 1", $conn) or die($k_b);

				list($temp_up_id,$zgl_komorka_nazwa) = mysql_fetch_array($result44);

//				$result44 = mysql_query("SELECT serwis_komorki.up_id FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) and (UPPER(serwis_komorki.up_nazwa) LIKE '%$zgl_komorka_nazwa%')) LIMIT 1", $conn) or die($k_b);
				
				//list($temp_up_id) = mysql_fetch_array($result44);
				
				//addownlinkbutton("'Przyjmij uszkodzony sprzęt'","Button1","button","newWindow_r(700,595,'z_naprawy_przyjmij.php?from=hd&hd_nr=$_GET[nr]&hd_podkategoria_nr=$temp_podkategoria&new_upid=$temp_up_id&tresc_zgl=".urlencode($temp_zgl_tresc)."&tup=".urlencode($zgl_komorka_nazwa)."')");

				$result441 = mysql_query("SELECT serwis_komorki.up_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) and (CONCAT(UPPER(serwis_piony.pion_nazwa),' ',UPPER(serwis_komorki.up_nazwa)) = '$_REQUEST[komorka]')) LIMIT 1", $conn) or die($k_b);
				list($sama_nazwa_up) = mysql_fetch_array($result441);
//echo ">>>>".$sama_nazwa_up;
//				$sama_nazwa_up = substr($_REQUEST[komorka],strpos($_REQUEST[komorka]," ")+1,strlen($_REQUEST[komorka]));
				
				$sql_up1 = "SELECT up_pion_id FROM $dbname.serwis_komorki WHERE (UPPER(up_nazwa)='$sama_nazwa_up') and (belongs_to=$es_filia) LIMIT 1";
				$wynik1 = mysql_query($sql_up1, $conn) or die($k_b);
				$dane_up1 = mysql_fetch_array($wynik1);
				$temp_pion_id = $dane_up1['up_pion_id'];
				
				// nazwa pionu z id pionu
				$sql_get_pion = "SELECT pion_nazwa FROM $dbname.serwis_piony WHERE (pion_id='$temp_pion_id') LIMIT 1";
				$wynik_get_pion = mysql_query($sql_get_pion, $conn) or die($k_b);
				$dane_get_pion = mysql_fetch_array($wynik_get_pion);
				$temp_pion_nazwa = $dane_get_pion['pion_nazwa'];
				
				// koniec ustalania nazwy pionu

				$zapyt = "SELECT * FROM $dbname.serwis_naprawa WHERE ((naprawa_status='1') or (naprawa_status='2')) and (belongs_to=$es_filia) and (naprawa_hd_zgl_id=0) and (UPPER(CONCAT('".$temp_pion_nazwa." ',naprawa_pobrano_z)='$_REQUEST[komorka]')) "; 
				
				if (($temp_zgl_podkat=='3') || ($temp_zgl_podkat=='4')) $zapyt .= " AND ((naprawa_nazwa='Komputer') or (naprawa_nazwa='Serwer') or (naprawa_nazwa='Notebook')) ";
				if (($temp_zgl_podkat=='9')) $zapyt .= " AND ((naprawa_nazwa<>'Komputer') and (naprawa_nazwa<>'Serwer') and (naprawa_nazwa<>'Notebook')) ";
				
				$zapyt .= " ORDER BY naprawa_nazwa, naprawa_model, naprawa_sn ASC";
				//echo $zapyt;
				
				$result = mysql_query($zapyt,$conn);
				$count_usnp = mysql_num_rows($result);
				
				if ($count_usnp>0) {
					echo "<select class=wymagane id=naprawaid name=naprawaid onkeypress='return handleEnter(this, event);' onChange=\"if (this.value==0) { document.getElementById('hd_diagnoza_uwagi').value=''; document.getElementById('hd_diagnoza_uwagi').style.display='none';} else { document.getElementById('hd_diagnoza_uwagi').style.display=''; } \">\n";
					echo "<option value='-1'>Wybierz z listy sprzęt będący w serwisach zewnętrznych...";

					while ($dane = mysql_fetch_array($result)) {
						$mid = $dane['naprawa_id'];					
						$mnazwa = $dane['naprawa_nazwa'];
						$mmodel= $dane['naprawa_model'];			
						$msn = $dane['naprawa_sn'];
						$moo= $dane['naprawa_osoba_pobierajaca'];	
						$mdp= $dane['naprawa_data_pobrania'];

						echo "<option value=$mid>$mnazwa $mmodel | SN: $msn | $mdp $moo</option>\n"; 
					}
					
					echo "</select>";
				}
			}
	
		_td();
	echo "</tr>";
	
	echo "<tr id=ZmienSerwisZewnetrzny style='display:none'>";
		td("140;r;");
		td_(";;;");
		
		if ($move_naprawa==true) {	
			if ($temp_naprawa_id>0) {
				$zapyt22 = "SELECT * FROM $dbname.serwis_naprawa WHERE (naprawa_id=$temp_naprawa_id) LIMIT 1 "; 
				$result22 = mysql_query($zapyt22,$conn);				
				
				while ($dane22 = mysql_fetch_array($result22)) {
				
						$mnfs		= $dane22['naprawa_fs_nazwa'];
						$mnfk		= $dane22['naprawa_fk_nazwa'];
						$mnnlp		= $dane22['naprawa_nr_listu_przewozowego'];
						$msz		= $dane22['naprawa_sprzet_zastepczy_id'];	
						$mewid_id 	= $dane22['naprawa_ew_id'];
		
						$result9 = mysql_query("SELECT fz_id FROM $dbname.serwis_fz WHERE (fz_nazwa='$mnfs') LIMIT 1", $conn) or die($k_b);
						list($fs_id)=mysql_fetch_array($result9);
					}
					
				//http://10.216.39.150/serwis/z_naprawy_zmien_serwis.php?nid=2708&cs=2&tup=UP+Sieradz+4&staryserwis=93&fk=&fs=Rados%C5%82aw%20Zieli%C5%84ski&fknlp=
				echo "<p class='block block_naprawa_zsz' style='margin-top:4px' onClick=\"newWindow_r(600,300,'z_naprawy_zmien_serwis.php?nid=$temp_naprawa_id&cs=$mstatus&from=hd&hdzglid=$_GET[nr]&hd_zgl_nr=$_GET[nr]&staryserwis=$fs_id&fk=".urlencode($mnfk)."&fs=".urlencode($mnfs)."&fknlp=$mnnlp'); return false;\">";
					echo "<b>Zmień serwis zewnętrzny</b>";
				echo "</p>";
			}
		}
		_td();
	echo "</tr>";
	
	echo "<tr id=ZakonczNaprawe style='display:none'>";
		td("140;r;");
		td_(";;;");
		// do dokończenia (problem z protokołami	
		
		if ($move_naprawa==true) { 
		
			if (($temp_naprawa_id>0) && ($mstatus=='-1')) { 

				echo "<p class='block block_naprawa_wz' onClick=\"newWindow_r(800,600,'z_naprawy_status.php?id=$temp_naprawa_id&cs=-1&from=hd&filtruj=wwz&hdzglid=$_GET[nr]&hd_zgl_nr=$_GET[nr]'); return false;\">";
					echo "<b>Naprawiaj sprzęt we własnym zakresie</b>";
				echo "</p>";	
				
				echo "<p class='block block_naprawa_wsz' style='margin-top:4px' onClick=\"newWindow_r(800,600,'z_naprawy_status.php?id=$temp_naprawa_id&cs=-1&from=hd&hdzglid=$_GET[nr]&hd_zgl_nr=$_GET[nr]'); return false;\">";
					echo "<b>Uruchom kreator przekazania sprzętu do serwisu zewnętrznego</b>";
				echo "</p>";
				
			}
			
			if (($temp_naprawa_id>0) && ($mstatus=='3')) { 
			
				if ($_SESSION['wybrany_sprzet_do_wymiany_dla_zgl_nr_'.$_REQUEST[id].'']==1) {
					
					if ($pozwol_na_6_i_9==true) {
						echo "<p class='block block_naprawa_ok' onClick=\"if (confirm('Wybrane podzespoły do wymiany w tym kroku nie będą widoczne dla tego zgłoszenia do czasu zapisania kroku. Czy chcesz uruchomić kreator oddania sprzętu do klienta ?')) { newWindow_r(700,595,'z_naprawy_napraw5.php?id=$temp_naprawa_id&cs=3&sz=$msz&tup=".urlencode($mup)."&mni=$mni&msn=$msn&upid=$upid&new_upid=".$temp_upid."&ewid_id=$mewid_id&hdzglid=$_GET[nr]&hd_zgl_nr=$_GET[nr]&dodajwymianepodzespolow=1&from=hd'); return false; }\">";
							echo "<b>Uruchom kreator oddania sprzętu do klienta</b>";
						echo "</p>";
					}
				
				} else {			
					if ($pozwol_na_6_i_9==true) {
						echo "<p class='block block_naprawa_ok' onClick=\"newWindow_r(700,595,'z_naprawy_napraw5.php?id=$temp_naprawa_id&cs=3&sz=$msz&tup=".urlencode($mup)."&mni=$mni&msn=$msn&upid=$upid&new_upid=".$temp_upid."&ewid_id=$mewid_id&hdzglid=$_GET[nr]&hd_zgl_nr=$_GET[nr]&dodajwymianepodzespolow=1&from=hd');  return false; \">";
							echo "<b>Uruchom kreator oddania sprzętu do klienta</b>";
						echo "</p>";
					}
				}
				
			}

			if (($temp_naprawa_id>0) && ($mstatus=='0')) { 
				//echo "<input type=button class=buttons value=' Zmień status naprawy na \"naprawiony\" ' onclick=\"newWindow_r(800,600,'z_naprawy_napraw3.php?id=$temp_naprawa_id&cs=0&sz=$msz&tup=".urlencode($mup)."&mni=$mni&msn=$msn&upid=$upid&new_upid=".$temp_upid."&ewid_id=$mewid_id&hdzglid=$_GET[nr]&hd_zgl_nr=$_GET[nr]');\" >";				
				
				echo "<p class='block block_naprawiaj' onClick=\"newWindow_r(800,600,'z_naprawy_napraw3.php?id=$temp_naprawa_id&cs=0&sz=$msz&tup=".urlencode($mup)."&mni=$mni&msn=$msn&upid=$upid&new_upid=".$temp_upid."&ewid_id=$mewid_id&hdzglid=$_GET[nr]&hd_zgl_nr=$_GET[nr]&from=hd'); return false; \">";
					echo "<b>Zmień status na <font color=green>naprawiony</font></b>";
				echo "</p>";	
					
			}

			if (($temp_naprawa_id>0) && ($mstatus=='1')) { 
				//echo "<input type=button class=buttons value=' Zmień status naprawy na \"naprawiony\" ' onclick=\"newWindow_r(800,600,'z_naprawy_napraw3.php?id=$temp_naprawa_id&cs=1&sz=$msz&tup=".urlencode($mup)."&mni=$mni&msn=$msn&upid=$upid&new_upid=".$temp_upid."&ewid_id=$mewid_id&hdzglid=$_GET[nr]&hd_zgl_nr=$_GET[nr]');\" >";	
				
				echo "<p class='block block_naprawiaj' onClick=\"newWindow_r(800,600,'z_naprawy_napraw3.php?id=$temp_naprawa_id&cs=1&sz=$msz&tup=".urlencode($mup)."&mni=$mni&msn=$msn&upid=$upid&new_upid=".$temp_upid."&ewid_id=$mewid_id&hdzglid=$_GET[nr]&hd_zgl_nr=$_GET[nr]&from=hd'); return false; \">";
					echo "<b>Zmień status na <font color=green>naprawiony</font></b>";
				echo "</p>";					
			}

			if (($temp_naprawa_id>0) && ($mstatus=='2')) { 
				//echo "<input type=button class=buttons value=' Zmień status naprawy na \"naprawiony\" ' onclick=\"newWindow_r(800,600,'z_naprawy_napraw3.php?id=$temp_naprawa_id&cs=2&sz=$msz&tup=".urlencode($mup)."&mni=$mni&msn=$msn&upid=$upid&new_upid=".$temp_upid."&ewid_id=$mewid_id&hdzglid=$_GET[nr]&hd_zgl_nr=$_GET[nr]');\" >";	
				
				echo "<p class='block block_naprawiaj' onClick=\"newWindow_r(800,600,'z_naprawy_napraw3.php?id=$temp_naprawa_id&cs=2&sz=$msz&tup=".urlencode($mup)."&mni=$mni&msn=$msn&upid=$upid&new_upid=".$temp_upid."&ewid_id=$mewid_id&hdzglid=$_GET[nr]&hd_zgl_nr=$_GET[nr]&from=hd'); return false; \">";
					echo "<b>Zmień status na <font color=green>naprawiony</font></b>";
				echo "</p>";	
			}
		}
		_td();
	echo "</tr>";
	
	echo "<tr id=OddajSprzetDoKlienta style='display:none'>";
		td("140;r;");
		td_(";;;");
		// do dokończenia (problem z protokołami)
			
		if ($move_naprawa==true) {	
			//if (($temp_naprawa_id>0) && (($mstatus=='0') && ($mstatus=='1') && ($mstatus=='2'))) { 
			if (($temp_naprawa_id>0) && ($mstatus=='3')) { 
				if ($_SESSION['wybrany_sprzet_do_wymiany_dla_zgl_nr_'.$_REQUEST[id].'']==1) {
					
					if ($pozwol_na_6_i_9==true) {
						echo "<p class='block block_naprawa_ok' onClick=\"if (confirm('Wybrane podzespoły do wymiany w tym kroku nie będą widoczne dla tego zgłoszenia do czasu zapisania kroku. Czy chcesz uruchomić kreator oddania sprzętu do klienta ?')) { newWindow_r(700,595,'z_naprawy_napraw5.php?id=$temp_naprawa_id&cs=3&sz=$msz&tup=".urlencode($mup)."&mni=$mni&msn=$msn&upid=$upid&new_upid=".$temp_upid."&ewid_id=$mewid_id&hdzglid=$_GET[nr]&hd_zgl_nr=$_GET[nr]&dodajwymianepodzespolow=1&from=hd'); return false; } \">";
							echo "<b>Uruchom kreator oddania sprzętu do klienta</b>";
						echo "</p>";
					}
					
				} else {			
					
					if ($pozwol_na_6_i_9==true) {
						echo "<p class='block block_naprawa_ok' onClick=\"newWindow_r(700,595,'z_naprawy_napraw5.php?id=$temp_naprawa_id&cs=3&sz=$msz&tup=".urlencode($mup)."&mni=$mni&msn=$msn&upid=$upid&new_upid=".$temp_upid."&ewid_id=$mewid_id&hdzglid=$_GET[nr]&hd_zgl_nr=$_GET[nr]&dodajwymianepodzespolow=1&from=hd'); return false; \">";
							echo "<b>Uruchom kreator oddania sprzętu do klienta</b>";
						echo "</p>";
					}
					
				}			
			}

			if (($temp_naprawa_id>0) && ($mstatus=='0')) { 
				//echo "<input type=button class=buttons value=' Zmień status naprawy na \"naprawiony\" ' onclick=\"newWindow_r(800,600,'z_naprawy_napraw3.php?id=$temp_naprawa_id&cs=0&sz=$msz&tup=".urlencode($mup)."&mni=$mni&msn=$msn&upid=$upid&new_upid=".$temp_upid."&ewid_id=$mewid_id&hdzglid=$_GET[nr]&hd_zgl_nr=$_GET[nr]');\" >";				
				
				echo "<p class='block block_naprawiaj' onClick=\"newWindow_r(800,600,'z_naprawy_napraw3.php?id=$temp_naprawa_id&cs=0&sz=$msz&tup=".urlencode($mup)."&mni=$mni&msn=$msn&upid=$upid&new_upid=".$temp_upid."&ewid_id=$mewid_id&hdzglid=$_GET[nr]&hd_zgl_nr=$_GET[nr]&from=hd'); return false; \">";
					echo "<b>Zmień status na <font color=green>naprawiony</font></b>";
				echo "</p>";	
				
			}

			if (($temp_naprawa_id>0) && ($mstatus=='1')) { 
				//echo "<input type=button class=buttons value=' Zmień status naprawy na \"naprawiony\" ' onclick=\"newWindow_r(800,600,'z_naprawy_napraw3.php?id=$temp_naprawa_id&cs=1&sz=$msz&tup=".urlencode($mup)."&mni=$mni&msn=$msn&upid=$upid&new_upid=".$temp_upid."&ewid_id=$mewid_id&hdzglid=$_GET[nr]&hd_zgl_nr=$_GET[nr]');\" >";

				echo "<p class='block block_naprawiaj' onClick=\"newWindow_r(800,600,'z_naprawy_napraw3.php?id=$temp_naprawa_id&cs=1&sz=$msz&tup=".urlencode($mup)."&mni=$mni&msn=$msn&upid=$upid&new_upid=".$temp_upid."&ewid_id=$mewid_id&hdzglid=$_GET[nr]&hd_zgl_nr=$_GET[nr]&from=hd'); return false; \">";
					echo "<b>Zmień status na <font color=green>naprawiony</font></b>";
				echo "</p>";
				
			}

			if (($temp_naprawa_id>0) && ($mstatus=='2')) { 
				//echo "<input type=button class=buttons value=' Zmień status naprawy na \"naprawiony\" ' onclick=\"newWindow_r(800,600,'z_naprawy_napraw3.php?id=$temp_naprawa_id&cs=2&sz=$msz&tup=".urlencode($mup)."&mni=$mni&msn=$msn&upid=$upid&new_upid=".$temp_upid."&ewid_id=$mewid_id&hdzglid=$_GET[nr]&hd_zgl_nr=$_GET[nr]');\" >";

				echo "<p class='block block_naprawiaj' onClick=\"newWindow_r(800,600,'z_naprawy_napraw3.php?id=$temp_naprawa_id&cs=2&sz=$msz&tup=".urlencode($mup)."&mni=$mni&msn=$msn&upid=$upid&new_upid=".$temp_upid."&ewid_id=$mewid_id&hdzglid=$_GET[nr]&hd_zgl_nr=$_GET[nr]&from=hd'); return false; \">";
					echo "<b>Zmień status na <font color=green>naprawiony</font></b>";
				echo "</p>";
				
			}

			if (($temp_naprawa_id>0) && ($mstatus=='-1')) { 
				//echo "<input type=button class=buttons value=' Naprawiaj sprzęt we własnym zakresie ' onclick=\"newWindow_r(800,600,'z_naprawy_status.php?id=$temp_naprawa_id&cs=-1&from=hd&filtruj=wwz&hdzglid=$_GET[nr]&hd_zgl_nr=$_GET[nr]');\" >";
				
				echo "<p class='block block_naprawa_wz' onClick=\"newWindow_r(800,600,'z_naprawy_status.php?id=$temp_naprawa_id&cs=-1&from=hd&filtruj=wwz&hdzglid=$_GET[nr]&hd_zgl_nr=$_GET[nr]'); return false;\">";
					echo "<b>Naprawiaj sprzęt we własnym zakresie</b>";
				echo "</p>";
				
				//nowalinia();
				//echo "<input type=button class=buttons value=' Uruchom kreator przekazania sprzętu do serwisu zewnętrznego' onclick=\"newWindow_r(800,600,'z_naprawy_status.php?id=$temp_naprawa_id&cs=-1&from=hd&hdzglid=$_GET[nr]&hd_zgl_nr=$_GET[nr]');\" >";
				
			/* wylaczone 19.09.2011
			
				echo "<p class='block block_naprawa_wsz' style='margin-top:4px'  onClick=\"newWindow_r(800,600,'z_naprawy_status.php?id=$temp_naprawa_id&cs=-1&from=hd&hdzglid=$_GET[nr]&hd_zgl_nr=$_GET[nr]'); return false;\">";
					echo "<b>Uruchom kreator przekazania sprzętu do serwisu zewnętrznego</b>";
				echo "</p>";
				
			*/
				
			}
			
				//echo "<input type=button class=buttons value=' Uruchom kreator przekazania uszkodzonego sprzętu do serwisu ' onclick=\"newWindow_r(800,600,'z_naprawy_status.php?id=$temp_naprawa_id&cs=-1&from=hd');\" >";
		}
		_td();
	echo "</tr>";
	
	echo "<tr id=WycofajSprzetZSerwisu style='display:none'>";
		td("140;r;");
		td_(";;;");
		if ($move_naprawa==true) {
		//z_naprawy_wycofaj_z_serwisu.php?id=$mid&cs=$mstatus&tup=".urlencode($mup)."&szid=$msz&sz=$msz&ewid_id=$mewid_id
			if ($temp_naprawa_id>0) {
				if ($pozwol_na_6_i_9==true) {
					//echo "WycofajSprzetZSerwisu";
					echo "<p class='block block_naprawa_wzs' style='margin-top:4px' onClick=\"newWindow_r(550,400,'z_naprawy_wycofaj_z_serwisu.php?id=$temp_naprawa_id&cs=$mstatus&from=hd&hdzglid=$_GET[nr]&hd_zgl_nr=$_GET[nr]&&szid=$msz&sz=$msz&ewid_id=$mewid_id'); return false;\">";
						echo "<b>Wycofaj sprzęt z serwisu <font color=red>(rezygnacja z naprawy)</font></b>";
					echo "</p>";
				}
			}
		}
		_td();
	echo "</tr>";
	
	echo "<tr id=WycofanyOddajDoKlienta style='display:none'>";
		td("140;r;");
		td_(";;;");
		// do dokończenia (problem z protokołami)
			
		if ($move_naprawa==true) {	
			//if (($temp_naprawa_id>0) && (($mstatus=='0') && ($mstatus=='1') && ($mstatus=='2'))) { 
			if (($temp_naprawa_id>0) && ($mstatus=='7')) { 
				if ($_SESSION['wybrany_sprzet_do_wymiany_dla_zgl_nr_'.$_REQUEST[id].'']==1) {

					echo "<p class='block block_wycofaj' onClick=\"newWindow_r(700,595,'z_naprawy_wycofaj.php?id=$temp_naprawa_id&status=7&cs=7&sz=$msz&tup=".urlencode($mup)."&mni=$mni&msn=$msn&upid=$upid&new_upid=".$temp_upid."&ewid_id=$mewid_id&hdzglid=$_GET[nr]&hd_zgl_nr=$_GET[nr]&dodajwymianepodzespolow=1&from=hd'); return false;  \">";
						echo "<b>Uruchom kreator zwrotu wycofanego z serwisu sprzętu do klienta</b>";
					echo "</p>";
					
				} else {			

					echo "<p class='block block_wycofaj' onClick=\"newWindow_r(700,595,'z_naprawy_wycofaj.php?id=$temp_naprawa_id&status=7&cs=7&sz=$msz&tup=".urlencode($mup)."&mni=$mni&msn=$msn&upid=$upid&new_upid=".$temp_upid."&ewid_id=$mewid_id&hdzglid=$_GET[nr]&hd_zgl_nr=$_GET[nr]&dodajwymianepodzespolow=1&from=hd'); return false; \">";
						echo "<b>Uruchom kreator zwrotu wycofanego z serwisu sprzętu do klienta</b>";
					echo "</p>";
					
				}			
			
			}
		}
		_td();
	echo "</tr>";

	if (($pozwol_na_6_i_9==false) && ($pokaz_zwrot)) {
		echo "<tr id=ZwrocSprzetDoFiliiZrodlowej style='display:='>";
			td("140;r;");			
			td_(";;;");	
			
				if (($temp_naprawa_id>0) && ($mstatus=='0')) { 
					echo "<p class='block block_naprawiaj' onClick=\"newWindow_r(800,600,'z_naprawy_napraw3.php?id=$temp_naprawa_id&cs=0&sz=$msz&tup=".urlencode($mup)."&mni=$mni&msn=$msn&upid=$upid&new_upid=".$temp_upid."&ewid_id=$mewid_id&hdzglid=$_GET[nr]&hd_zgl_nr=$_GET[nr]&from=hd'); return false; \">";
						echo "<b>Zmień status na <font color=green>naprawiony</font></b>";
					echo "</p><br />";
				}

				if (($temp_naprawa_id>0) && ($mstatus=='1')) { 
					echo "<p class='block block_naprawiaj' onClick=\"newWindow_r(800,600,'z_naprawy_napraw3.php?id=$temp_naprawa_id&cs=1&sz=$msz&tup=".urlencode($mup)."&mni=$mni&msn=$msn&upid=$upid&new_upid=".$temp_upid."&ewid_id=$mewid_id&hdzglid=$_GET[nr]&hd_zgl_nr=$_GET[nr]&from=hd'); return false; \">";
						echo "<b>Zmień status na <font color=green>naprawiony</font></b>";
					echo "</p><br />";					
				}

				if (($temp_naprawa_id>0) && ($mstatus=='2')) { 
					echo "<p class='block block_naprawiaj' onClick=\"newWindow_r(800,600,'z_naprawy_napraw3.php?id=$temp_naprawa_id&cs=2&sz=$msz&tup=".urlencode($mup)."&mni=$mni&msn=$msn&upid=$upid&new_upid=".$temp_upid."&ewid_id=$mewid_id&hdzglid=$_GET[nr]&hd_zgl_nr=$_GET[nr]&from=hd'); return false; \">";
						echo "<b>Zmień status na <font color=green>naprawiony</font></b>";
					echo "</p><br />";
				}
			
				echo "<p class='block block_ZNSDFZ' onClick=\"newWindow(600,300,'z_naprawa_przekaz_zwrot.php?id=$mid&cs=$mstatus&tup=".urlencode($mup)."&hd_zgl_nr=$_REQUEST[nr]');\">";
					echo "<b>Zwróć sprzęt do filii/oddziału źródłowego</b>";
				echo "</p>";
				
			_td();
		echo "</tr>";
	}
	
	echo "<tr id=PrzekazSprzetSerwisowy style='display:none'>";
		td("140;r;");
		td_(";;;");
					
			echo "<input type=hidden id=PrzekazSprzetSerwisowy_value name=PrzekazSprzetSerwisowy_value value='$temp_ss_id' />";		
			
			if ($pozwol_na_6_i_9==true) {
			//if (($temp_naprawa_id>0) && ($mstatus=='7')) { 
//				if ($_SESSION['wybrany_sprzet_do_wymiany_dla_zgl_nr_'.$_REQUEST[id].'']==1) {

					$result_upid = mysql_query("SELECT up_id FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (up_active=1) and (belongs_to=$es_filia) and (CONCAT(serwis_piony.pion_nazwa,' ',serwis_komorki.up_nazwa)='$_REQUEST[komorka]') LIMIT 1", $conn) or die($k_b);
					list($temp_upid_1)=mysql_fetch_array($result_upid);
					
					$preselect = '';
					$dostepne = '';

					if (($_REQUEST[tpk]=='3') || ($_REQUEST[tpk]=='4') || ($_REQUEST[tpk]=='2') || ($_REQUEST[tpk]=='5') || ($_REQUEST[tpk]=='7')) {
						if ($mnazwa=='') {
							$preselect = "Komputer";
							$dostepne = "#Komputer#,#Serwer#,#Notebook#,#Monitor#";
						} else {
							$preselect = "$mnazwa";
							$dostepne = "#$mnazwa#";
						}
					}
					
					if (($_REQUEST[tpk]=='9') || ($_REQUEST[tpk]=='0')) {
						if ($mnazwa=='') {
							$preselect = "Drukarka";
							$dostepne = "#Drukarka#,#Czytnik#,#Skaner#,#Router#,#Switch#,#Monitor#,#Inne#";							
						} else {
							$preselect = "$mnazwa";
							$dostepne = "#$mnazwa#";
						}
					}
					
					//if ($temp_podkategoria2!='') $preselect = $temp_podkategoria2;
					//if ($preselect!='') $mnazwa = $preselect;
					
					//echo ">>".$preselect;
					
					echo "<p class='block block_PSS' onClick=\"newWindow_r(700,595,'z_przekaz_sprzet_serwisowy.php?tup=".urlencode($_REQUEST[komorka])."&new_upid=".$temp_upid_1."&hd_zgl_nr=$_GET[nr]&from=hd&naprawaid=$temp_naprawa_id&typ=".urlencode($preselect)."&preselect=".$preselect."&dostepne=".urlencode($dostepne)."&pkp2=".urlencode($temp_podkategoria2)."'); return false;  \">";
						echo "<b>Uruchom kreator przekazania sprzętu serwisowego</b>";
					echo "</p>";
					
			}
		_td();
	echo "</tr>";

	echo "<tr id=ZwrocSprzetSerwisowy style='display:none'>";
		td("140;r;");
		td_(";;;");
//			if (($temp_naprawa_id>0) && ($mstatus=='7')) { 
//				if ($_SESSION['wybrany_sprzet_do_wymiany_dla_zgl_nr_'.$_REQUEST[id].'']==1) {
			
			$temp_magazyn_status = 0;
			
			$result_upid = mysql_query("SELECT magazyn_status FROM $dbname.serwis_magazyn WHERE (belongs_to=$es_filia) and (magazyn_id=$temp_ss_id) LIMIT 1", $conn) or die($k_b);
			list($temp_magazyn_status)=mysql_fetch_array($result_upid);
			if ($temp_magazyn_status=='') $temp_magazyn_status=0;
			
			echo "<input type=hidden name=SS_Status id=SS_Status value='$temp_magazyn_status'>";
			
//			if ($temp_magazyn_status>0) {
			
				echo "<p class='block block_ZSS' onClick=\"newWindow_r(700,595,'z_magazyn_zwrot.php?id=$temp_ss_id&hd_zgl_nr=$_REQUEST[nr]&from=hd&info=0'); return false;  \">";
				echo "<b>Uruchom kreator zwrotu sprzętu serwisowego do magazynu</b>";
				echo "</p>";
				
			//}
				
/*				} else {			

					echo "<p class='block block_wycofaj' onClick=\"newWindow_r(700,595,'z_naprawy_wycofaj.php?id=$temp_naprawa_id&status=7&cs=7&sz=$msz&tup=".urlencode($mup)."&mni=$mni&msn=$msn&upid=$upid&new_upid=".$temp_upid."&ewid_id=$mewid_id&hdzglid=$_GET[nr]&hd_zgl_nr=$_GET[nr]&dodajwymianepodzespolow=1&from=hd'); return false; \">";
						echo "<b>Uruchom kreator zwrotu wycofanego z serwisu sprzętu do klienta</b>";
					echo "</p>";
					
				}			
			
			}
			*/
		_td();
	echo "</tr>";
	
	echo "<tr id=PobierzSprzetSerwisowy style='display:none'>";
		td("140;r;");
		td_(";;;");
//			if (($temp_naprawa_id>0) && ($mstatus=='7')) { 
//				if ($_SESSION['wybrany_sprzet_do_wymiany_dla_zgl_nr_'.$_REQUEST[id].'']==1) {
			if ($pozwol_na_6_i_9==true) {
					echo "<p class='block block_PSZZK' onClick=\"newWindow_r(700,595,'z_naprawy_wycofaj_sprzet_serwisowy.php?id=$temp_naprawa_id&sz=$temp_ss_id&hd_zgl_nr=$_REQUEST[nr]&from=hd&up=".urlencode($temp_komorka)."&info=0'); return false;  \">";
						echo "<b>Pobierz sprzęt zastępczy z komórki</b>";
					echo "</p>";
			}
/*				} else {			

					echo "<p class='block block_wycofaj' onClick=\"newWindow_r(700,595,'z_naprawy_wycofaj.php?id=$temp_naprawa_id&status=7&cs=7&sz=$msz&tup=".urlencode($mup)."&mni=$mni&msn=$msn&upid=$upid&new_upid=".$temp_upid."&ewid_id=$mewid_id&hdzglid=$_GET[nr]&hd_zgl_nr=$_GET[nr]&dodajwymianepodzespolow=1&from=hd'); return false; \">";
						echo "<b>Uruchom kreator zwrotu wycofanego z serwisu sprzętu do klienta</b>";
					echo "</p>";
					
				}			
			
			}
			*/
		_td();
	echo "</tr>";
	
	
	if ( (($_REQUEST[tk]=='2') || ($_REQUEST[tk]=='6') || ($_REQUEST[tk]=='3')) && (($_REQUEST[tpk]=='3') || ($_REQUEST[tpk]=='4') || ($_REQUEST[tpk]=='9') || ($_REQUEST[tpk]=='0') || ($_REQUEST[tpk]=='2') || ($_REQUEST[tpk]=='5')) ) {
		echo "<tr style='display:none;'>";
			td("140;r;Stany pośrednie");
				
			td_(";;;");	
			
				echo "<p id=sp_p class='block block_pokaz' onClick=\"$('#StanyPosrednie').show(); $('#sp_p').hide(); $('#sp_u').show();\">";
				echo "<b>Pokaż</b>";
				echo "</p>";

				echo "<p id=sp_u style='display:none' class='block block_ukryj' onClick=\"$('#StanyPosrednie').hide(); $('#sp_p').show(); $('#sp_u').hide();\">";
				echo "<b>Pokaż</b>";
				echo "</p>";
	

				//echo ">>>".$temp_ss_id."<<<<";
	
				//echo "<input type=button class=buttons style='background-color:#E3C079' id=sp_p value='Pokaż' onClick=\"$('#StanyPosrednie').show(); $('#sp_p').hide(); $('#sp_u').show(); \">";
			//	echo "<input style='display:none' type=button class=buttons id=sp_u value='Ukryj' onClick=\"$('#StanyPosrednie').hide(); $('#sp_p').show(); $('#sp_u').hide(); \">";
			_td();
		_tr();
	//}
	
	//if ( (($_REQUEST[tk]=='2') || ($_REQUEST[tk]=='6') || ($_REQUEST[tk]=='3')) && (($_REQUEST[tpk]=='3') || ($_REQUEST[tpk]=='4') || ($_REQUEST[tpk]=='9') || ($_REQUEST[tpk]=='0')) ) {
		echo "<tr id=StanyPosrednie style='display:none'>";
			td("150;rt;");
			td_(";;;");
			
//				if ($temp_naprawa_id>0) {
				$r55 = mysql_query("SELECT hdnp_zdiagnozowany, hdnp_oferta_wyslana, hdnp_akceptacja_kosztow, hdnp_zamowienie_wyslane, hdnp_zamowienie_zrealizowane, hdnp_gotowe_do_oddania, hdnp_naprawa_zakonczona FROM $dbname_hd.hd_naprawy_powiazane WHERE (hdnp_zgl_id='$_GET[nr]') ORDER BY hdnp_id DESC LIMIT 1", $conn_hd) or die($k_b);
				list($temp_zdiagnozowany, $temp_oferta_wyslana, $temp_akceptacja_kosztow, $temp_zamowienie_wyslane, $temp_zamowienie_zrealizowane, $temp_gotowe_do_oddania, $temp_naprawa_zakonczona)=mysql_fetch_array($r55);
					
//				} else {
				if ($temp_zdiagnozowany=='') $temp_zdiagnozowany=9;
				if ($temp_oferta_wyslana=='') $temp_oferta_wyslana=9;
				if ($temp_akceptacja_kosztow=='') $temp_akceptacja_kosztow=9;
				if ($temp_zamowienie_wyslane=='') $temp_zamowienie_wyslane=9;
				if ($temp_zamowienie_zrealizowane=='') $temp_zamowienie_zrealizowane=9;
				if ($temp_gotowe_do_oddania=='') $temp_gotowe_do_oddania=9;
				if ($temp_naprawa_zakonczona=='') $temp_naprawa_zakonczona=9;
//				}
		//	echo "<span style='display:inline'>";
				echo "<table style='width:auto'>";
				echo "<tr><td colspan=2 class=center style='background-color:white'><b>Aktualne</b></td><td width='150px' class=center style='background-color:white'><b>Nowe</b></td></tr>";
			// =================================================================================================================================================	
				echo "<tr><td style='text-align:right;'>";
				echo "zdiagnozowany";
				echo "</td><td style='text-align:center;'>";
				switch ($temp_zdiagnozowany) {
					case 0 : echo "<font color=red>NIE</font>"; break;
					case 1 : echo "<font color=green>TAK</font>"; break; 
					default : echo "<font color=grey>-</font>"; break; 
				}			
				echo "</td>";
			
				echo "<td id=TRSelectZdiagnozowany class=center>"; 	
				// zdiagnozowany
				echo "<input type=hidden name=_SelectZdiagnozowany id=_SelectZdiagnozowany value='$temp_zdiagnozowany'>";
				echo "<span id=Zdiagnozowany>";
				echo "<select ";

				switch ($temp_zdiagnozowany) {
					case 0 : echo " style='background-color:red' "; break;
					case 1 : echo " style='background-color:green' "; break; 
					case 9 : echo " style='background-color:yellow' "; break; 
					default : echo " style='background-color:transparent' "; break; 
				}					

				echo " id=SelectZdiagnozowany name=SelectZdiagnozowany onChange=\"ClearCookie('zdiagnozowany_".$_REQUEST[nr]."'); SetCookie('zdiagnozowany_".$_REQUEST[nr]."',this.value); if ((this.value==1) && (document.getElementById('naprawaid').value!=0)) { PokazUkryjDaneODiagnozie(true); document.getElementById('DodajDiagnozeDoWykonanychCzynnosci').checked=true; } else { PokazUkryjDaneODiagnozie(false); document.getElementById('DodajDiagnozeDoWykonanychCzynnosci').checked=false;} \">\n";
				if ($temp_zdiagnozowany==9) echo "<option value='9'></option>\n";
				echo "<option value='1'>TAK</option>\n";
				echo "<option value='0'>NIE</option>\n";
				echo "</select>";
				echo "</span>";					
				echo "</td>";
				
				echo "</tr>";
			// =================================================================================================================================================
				echo "<tr><td style='text-align:right;'>";
				echo "oferta wysłana";
				echo "</td><td style='text-align:center;'>";
				switch ($temp_oferta_wyslana) {
					case 0 : echo "<font color=red>NIE</font>"; break;
					case 1 : echo "<font color=green>TAK</font>"; break; 
					default : echo "<font color=grey>-</font>"; break; 
				}
				echo "</td>";
				
				echo "<td id=TROfertaWyslana class=center>";
				// oferta wysłana
				echo "<input type=hidden name=_OfertaWyslanaInput id=_OfertaWyslanaInput value='$temp_oferta_wyslana'>";
				echo "<span id=OfertaWyslana style='display:none'>";
				echo "<select ";
				
				switch ($temp_oferta_wyslana) {
					case 0 : echo " style='background-color:red' "; break;
					case 1 : echo " style='background-color:green' "; break; 
					case 9 : echo " style='background-color:yellow' "; break; 
					default : echo " style='background-color:transparent' "; break; 
				}
				
				echo " id=OfertaWyslanaInput name=OfertaWyslanaInput onChange=\"ClearCookie('oferta_wyslana_".$_REQUEST[nr]."'); SetCookie('oferta_wyslana_".$_REQUEST[nr]."',this.value); if (this.value==1) { PokazUkryjDaneOOfercie(true); } else { PokazUkryjDaneOOfercie(false);} \">\n";
				if ($temp_oferta_wyslana==9) echo "<option value='9'></option>\n";
				echo "<option value='1'>TAK</option>\n";
				echo "<option value='0'>NIE</option>\n";
				echo "</select>";
				echo "</span>";				
				echo "</td></tr>";
				
			// =================================================================================================================================================

				echo "<tr><td style='text-align:right;'>";
				echo "akceptacja kosztów";
				echo "</td><td style='text-align:center;'>";
				switch ($temp_akceptacja_kosztow) {
					case 0 : echo "<font color=red>NIE</font>"; break;
					case 1 : echo "<font color=green>TAK</font>"; break; 
					default : echo "<font color=grey>-</font>"; break; 
				}
				echo "</td>";
				echo "<td id=TRAkceptacjaKosztow class=center>";
				// akceptacja kosztów
				echo "<input type=hidden name=_SelectAkceptacjaKosztow id=_SelectAkceptacjaKosztow value='$temp_akceptacja_kosztow'>";
				echo "<span id=AkceptacjaKosztow style='display:none'>";
				echo "<select ";
				switch ($temp_akceptacja_kosztow) {
					case 0 : echo " style='background-color:red' "; break;
					case 1 : echo " style='background-color:green' "; break; 
					case 9 : echo " style='background-color:yellow' "; break; 
					default : echo " style='background-color:transparent' "; break; 
				}				
				echo " id=SelectAkceptacjaKosztow name=SelectAkceptacjaKosztow onChange=\"ClearCookie('akceptacja_kosztow_".$_REQUEST[nr]."'); SetCookie('akceptacja_kosztow_".$_REQUEST[nr]."',this.value);\">";
				if ($temp_akceptacja_kosztow==9) echo "<option value='9'></option>\n";
				echo "<option value='1'>TAK</option>\n";
				echo "<option value='0'>NIE</option>\n";
				echo "</select>";
				echo "</span>";				
				echo "</td></tr>";
				
			// =================================================================================================================================================

				echo "<tr><td style='text-align:right;' class=center>";
				echo "zamówienie wysłane";
				echo "</td><td style='text-align:center;'>";
				switch ($temp_zamowienie_wyslane) {
					case 0 : echo "<font color=red>NIE</font>"; break;
					case 1 : echo "<font color=green>TAK</font>"; break; 
					default : echo "<font color=grey>-</font>"; break; 
				}
				echo "</td>";
				echo "<td id=TRZamowienieWyslane class=center>";
				// zamówienie wysłane
				echo "<input type=hidden name=_ZamowienieWyslaneInput id=_ZamowienieWyslaneInput value='$temp_zamowienie_wyslane'>";
				echo "<span id=ZamowienieWyslane style='display:none;'>";
				echo "<select"; 
				switch ($temp_zamowienie_wyslane) {
					case 0 : echo " style='background-color:red' "; break;
					case 1 : echo " style='background-color:green' "; break; 
					case 9 : echo " style='background-color:yellow' "; break; 
					default : echo " style='background-color:transparent' "; break; 
				}				
				echo " id=ZamowienieWyslaneInput name=ZamowienieWyslaneInput onChange=\"ClearCookie('zamowienie_wys_".$_REQUEST[nr]."'); SetCookie('zamowienie_wys_".$_REQUEST[nr]."',this.value);if (this.value==1) { PokazUkryjDaneOZamowieniu(true); } else { PokazUkryjDaneOZamowieniu(false);} \">";
				if ($temp_zamowienie_wyslane==9) echo "<option value='9'></option>\n";
				echo "<option value='1'>TAK</option>\n";
				echo "<option value='0'>NIE</option>\n";
				echo "</select>";
				echo "</span>";				
				echo "</td></tr>";				

			// =================================================================================================================================================

				echo "<tr><td style='text-align:right;'>";
				echo "zamówienie zrealizowane";
				echo "</td><td style='text-align:center;'>";
				switch ($temp_zamowienie_zrealizowane) {
					case 0 : echo "<font color=red>NIE</font>"; break;
					case 1 : echo "<font color=green>TAK</font>"; break; 
					default : echo "<font color=grey>-</font>"; break; 
				}
				echo "</td>";
				echo "<td id=TRZamowienieZrealizowane class=center>";
				
				// zamówienie zrealizowane
				echo "<input type=hidden name=_ZamowienieZrealizowaneInput id=_ZamowienieZrealizowaneInput value='$temp_zamowienie_zrealizowane'>";
				echo "<span id=ZamowienieZrealizowane style='display:none;'>";
				echo "<select "; 
				switch ($temp_zamowienie_zrealizowane) {
					case 0 : echo " style='background-color:red' "; break;
					case 1 : echo " style='background-color:green' "; break; 
					case 9 : echo " style='background-color:yellow' "; break; 
					default : echo " style='background-color:transparent' "; break; 
				}				
				echo " id=ZamowienieZrealizowaneInput name=ZamowienieZrealizowaneInput onChange=\"ClearCookie('zamowienie_zreal_".$_REQUEST[nr]."'); SetCookie('zamowienie_zreal_".$_REQUEST[nr]."',this.value);\">";
				if ($temp_zamowienie_zrealizowane==9) echo "<option value='9'></option>\n";
				echo "<option value='1'>TAK</option>\n";
				echo "<option value='0'>NIE</option>\n";
				echo "</select>";			
				echo "</span>";
				echo "</td></tr>";

			// =================================================================================================================================================
				
				echo "<tr><td style='text-align:right;'>";
				echo "gotowe do oddania";
				echo "</td><td style='text-align:center;'>";
				switch ($temp_gotowe_do_oddania) {
					case 0 : echo "<font color=red>NIE</font>"; break;
					case 1 : echo "<font color=green>TAK</font>"; break; 
					default : echo "<font color=grey>-</font>"; break; 
				}
				echo "</td>";
				echo "<td id=TRGotoweDoOddania class=center>";
				// gotowe do oddania
				echo "<input type=hidden name=_GotowyDoOddaniaInput id=_GotowyDoOddaniaInput value='$temp_gotowe_do_oddania'>";
				echo "<span id=GotowyDoOddania style='display:none;'>";
				echo "<select ";
				switch ($temp_gotowe_do_oddania) {
					case 0 : echo " style='background-color:red' "; break;
					case 1 : echo " style='background-color:green' "; break; 
					case 9 : echo " style='background-color:yellow' "; break; 
					default : echo " style='background-color:transparent' "; break; 
				}
				echo " id=GotowyDoOddaniaInput name=GotowyDoOddaniaInput onChange=\"ClearCookie('gotowy_do_oddania_".$_REQUEST[nr]."'); SetCookie('gotowy_do_oddania_".$_REQUEST[nr]."',this.value);\">";
				if ($temp_gotowe_do_oddania==9) echo "<option value='9'></option>\n";
				echo "<option value='1'>TAK</option>\n";
				echo "<option value='0'>NIE</option>\n";
				echo "</select>";
				echo "</span>";
				echo "</td></tr>";
				
				echo "</table>";

				echo "<span id=UtworzProtokolButton style='display:none;float:right'>";
					echo "<input type=button class=buttons value=' Utwórz protokół ' onclick=\"newWindow_r(700,595,'utworz_protokol.php?blank=1&state=&nowy=1');\" >";			
				echo "</span>";
				
			_td();
		_tr();

	}
	
	echo "<tr id=TRDiagnoza_uwagi style=display:none>";
		td("150;rt;<b>Szczegóły diagnozy</b>");
		td_(";;;");
			echo "<textarea id=hd_diagnoza_uwagi name=hd_diagnoza_uwagi cols=70 rows=2 /></textarea><br />";
			echo "<input class=border0 type=checkbox name=DodajDiagnozeDoWykonanychCzynnosci id=DodajDiagnozeDoWykonanychCzynnosci checked />";				
			echo "<a href=# class=normalfont onClick=\"if (document.getElementById('DodajDiagnozeDoWykonanychCzynnosci').checked) { document.getElementById('DodajDiagnozeDoWykonanychCzynnosci').checked=false; } else { document.getElementById('DodajDiagnozeDoWykonanychCzynnosci').checked=true; } \"> <font color=red>Automatycznie dodaj treść diagnozy do wykonanych czynności przy zapisywaniu kroku</font></a><br /><br />";
			_td();
		_tr();	
	//tbl_empty_row(1);
		
// dla szcz o ofercie		
	echo "<tr id=TROferta_dw style=display:none>";
		td("150;r;<b>Data wysłania oferty</b>");
		td_(";;;");
			$dddd = Date('Y-m-d');
			echo "<select class=wymagane name=hd_oferta_data id=hd_oferta_data>";
			echo "<option value='$dddd' SELECTED>$dddd</option>\n";
			
			if ((date("w",strtotime($dddd))!=0) || ($idw_dla_zbh_testowa))  {
				for ($cd=1; $cd<($idw_dla_zbh+1); $cd++) {
					echo "<option value='".SubstractDays($cd,$dddd)."'>".SubstractDays($cd,$dddd)."&nbsp;";
					if ($idw_dla_zbh_testowa) echo "[dla testów]";
					echo "</option>\n";
				}
			}
			
			//echo "<option value='".SubstractWorkingDays(1,$dddd)."'>".SubstractWorkingDays(1,$dddd)."&nbsp;</option>\n";
			//echo "<option value='".SubstractWorkingDays(2,$dddd)."'>".SubstractWorkingDays(2,$dddd)."&nbsp;</option>\n";
			echo "</select>\n";
		_td();
	_tr();
	echo "<tr id=TROferta_no style=display:none>";
		td("150;rt;<b>Numer oferty</b>");
		td_(";;;");
			echo "<input type=text size=10 maxlength=20 name=hd_oferta_numer />";
		_td();
	_tr();	
	echo "<tr id=TROferta_uwagi style=display:none>";
		td("150;rt;<b>Uwagi<br />do oferty</b>");
		td_(";;;");
			echo "<textarea name=hd_oferta_uwagi cols=35 rows=5 /></textarea><br /><br />";
		_td();
	_tr();		
		

// dla szcz o zamowieniu		
	echo "<tr id=TRZam_dw style=display:none>";
		td("150;r;<b>Data wysłania zamówienia</b>");
		td_(";;;");
			$dddd = Date('Y-m-d');
			echo "<select class=wymagane name=hd_zam_data id=hd_zam_data>";
			echo "<option value='$dddd' SELECTED>$dddd</option>\n";

			if ((date("w",strtotime($dddd))!=0) || ($idw_dla_zbh_testowa))  {
				for ($cd=1; $cd<($idw_dla_zbh+1); $cd++) {
					echo "<option value='".SubstractDays($cd,$dddd)."'>".SubstractDays($cd,$dddd)."&nbsp;";
					if ($idw_dla_zbh_testowa) echo "[dla testów]";
					echo "</option>\n";
				}
			}
			
//			for ($cd=1; $cd<($idw_dla_zbh+1); $cd++) echo "<option value='".SubstractDays($cd,$dddd)."'>".SubstractDays($cd,$dddd)."&nbsp;</option>\n";			
			
			//echo "<option value='".SubstractWorkingDays(1,$dddd)."'>".SubstractWorkingDays(1,$dddd)."&nbsp;</option>\n";
			//echo "<option value='".SubstractWorkingDays(2,$dddd)."'>".SubstractWorkingDays(2,$dddd)."&nbsp;</option>\n";
			echo "</select>\n";
		_td();
	_tr();
	echo "<tr id=TRZam_nz style=display:none>";
		td("150;rt;<b>Numer zamówienia</b>");
		td_(";;;");
			echo "<input type=text size=10 maxlength=20 name=hd_zam_numer />";
		_td();
	_tr();	
	echo "<tr id=TRZam_uwagi style=display:none>";
		td("150;rt;<b>Uwagi<br />do zamówienia</b>");
		td_(";;;");
			echo "<textarea name=hd_zam_uwagi cols=35 rows=5 /></textarea><br /><br />";
		_td();
	_tr();		

		//tbl_empty_row(2);
		tr_();
			td("150;rt;Wykonane czynności");
			td_(";;;");	
			//echo ">>>".HexToNormal($_COOKIE['wpisane_wc_'.$_REQUEST[nr].''])."";	
			
				echo "<textarea class=wymagane name=zs_wcz id=zs_wcz cols=48 rows=3 onKeyUp=\"if (this.value!='') { document.getElementById('sl_d').style.display=''; document.getElementById('tr_clear').style.display=''; } else { document.getElementById('sl_d').style.display='none'; document.getElementById('tr_clear').style.display='none'; }\" onBlur=\"ZamienTekst(this.id); ClearCookie('wpisane_wc_".$_REQUEST[nr]."'); SetCookie('wpisane_wc_".$_REQUEST[nr]."',this.value); \" >";
				
				if ($_COOKIE['wpisane_wc_'.$_REQUEST[nr].'']!=null) {
					echo ClearOutputText(HexToNormal($_COOKIE['wpisane_wc_'.$_REQUEST[nr].'']));
				}				

				if ($_SESSION['wybrany_sprzet_do_wymiany_dla_zgl_nr_'.$_REQUEST[id].'']==1) {	
					echo trim($_SESSION['wc_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].'']);
				}
				
				echo "</textarea>";	
				
				echo "<a title=' Dodaj treść do słownika' style='display:none' id=sl_d class=imgoption  onClick=\"newWindow_byName('_dodaj_do_slownika',700,400,'hd_d_slownik_tresc_zs.php?akcja=fastadd&p2=".$_GET[tk]."&p3=".$_GET[tpk]."'); return false;\"><input class=imgoption type=image src=img/slownik_dodaj.gif></a>";
				echo "<a title=' Wybierz treść ze słownika' id=sl_wybierz class=imgoption  onClick=\"newWindow_byName_r('_wybierz_ze_slownika',700,600,'hd_z_slownik_tresci_zs.php?akcja=wybierz&p6=".urlencode($currentuser)."&p2=".$_GET[tk]."&p3=".$_GET[tpk]."'); return false;\"><input class=imgoption type=image src=img/ew_prosty.png></a>";
				
				echo "<a id=tr_clear href=# style='display:";
				
				if ($_SESSION['wybrany_sprzet_do_wymiany_dla_zgl_nr_'.$_REQUEST[id].'']==1) echo "";
				if ($_COOKIE['wpisane_wc_'.$_REQUEST[nr].'']!=null) { echo ""; } else { echo "none"; }			
				
				echo "' onclick=\"if (confirm('Czy wyczyścić treść zgłoszenia ?')) { document.getElementById('zs_wcz').value=''; document.getElementById('tr_clear').style.display='none'; document.getElementById('sl_d').style.display='none'; ClearCookie('wpisane_wc_".$_REQUEST[nr]."'); SetCookie('wpisane_wc_".$_REQUEST[nr]."',''); document.getElementById('hd_tresc').focus(); return false;}\" title=' Wyczyść treść zgłoszenia '><img src=img/czysc.gif border=0></a>";
				
				echo "<span id=PowiazaneZWyjazdem style=display:''>";
				echo "&nbsp;<input class=border0 type=checkbox name=PozwolWpisacKm id=PozwolWpisacKm onClick=\"if (this.checked) { document.getElementById('RodzajPojazdu').style.display=''; $('#CzasTrwaniaWyjazdu').show(); document.getElementById('hd_wyjazd_rp').options.selectedIndex=1;  document.getElementById('DataWyjazdu').style.display=''; document.getElementById('WpiszWyjazdTrasa').style.display='none'; document.getElementById('WpiszWyjazdKm').style.display='none'; document.getElementById('wymaga_wyjazdu').checked=false; } else { document.getElementById('RodzajPojazdu').style.display='none'; $('#CzasTrwaniaWyjazdu').hide(); document.getElementById('hd_wyjazd_rp').options.selectedIndex=1; document.getElementById('DataWyjazdu').style.display='none'; document.getElementById('WpiszWyjazdTrasa').style.display='none'; document.getElementById('WpiszWyjazdKm').style.display='none'; if (document.getElementById('ww_value').value=='1') { document.getElementById('wymaga_wyjazdu').checked=true; } if (document.getElementById('SelectZmienStatus').value=='6') { document.getElementById('wymaga_wyjazdu').checked=true; } } \" />";
			//if (document.getElementById('wymaga_wyjazdu').checked) { document.getElementById('wymaga_wyjazdu').checked=false; return false; } else { document.getElementById('wymaga_wyjazdu').checked=true; return false; }
				echo "<a href=# class=normalfont onClick=\"if (document.getElementById('PozwolWpisacKm').checked) { document.getElementById('PozwolWpisacKm').checked=false; document.getElementById('hd_wyjazd_rp').options.selectedIndex=1;  document.getElementById('RodzajPojazdu').style.display='none'; $('#CzasTrwaniaWyjazdu').hide(); document.getElementById('DataWyjazdu').style.display='none'; document.getElementById('WpiszWyjazdTrasa').style.display='none'; document.getElementById('WpiszWyjazdKm').style.display='none'; if (document.getElementById('ww_value').value=='1') { document.getElementById('wymaga_wyjazdu').checked=true; } if (document.getElementById('SelectZmienStatus').value=='6') { document.getElementById('wymaga_wyjazdu').checked=true; } } else { document.getElementById('PozwolWpisacKm').checked=true; document.getElementById('hd_wyjazd_rp').options.selectedIndex=1; document.getElementById('RodzajPojazdu').style.display=''; $('#CzasTrwaniaWyjazdu').show(); document.getElementById('DataWyjazdu').style.display=''; document.getElementById('WpiszWyjazdTrasa').style.display='none'; document.getElementById('WpiszWyjazdKm').style.display='none'; document.getElementById('wymaga_wyjazdu').checked=false;} \"><b>Pow. z wyjazdem</b></a>";
				echo "</span>";
				
			_td();
		_tr();
		
		echo "<tr id=WieleOsob style='display:'>";
			td(";;");
			td_(";;;");
			//	echo "<span id=PowiazaneZWyjazdem style=display:''>";
				echo "<input class=border0 type=checkbox name=WieleOsobCheck id=WieleOsobCheck onClick=\"if (this.checked) { document.getElementById('WieleOsobWybor').style.display=''; } else { document.getElementById('WieleOsobWybor').style.display='none'; } \" />";
				
				echo "<a href=# class=normalfont onClick=\"if (document.getElementById('WieleOsobCheck').checked) { document.getElementById('WieleOsobCheck').checked=false; } else { document.getElementById('WieleOsobCheck').checked=true; } if (document.getElementById('WieleOsobCheck').checked) { document.getElementById('WieleOsobWybor').style.display=''; } else { document.getElementById('WieleOsobWybor').style.display='none'; }\"> Krok wykonywany przez wiele osób</a>";
			//	echo "</span>";		
			_td();
		echo "</tr>";
		//tbl_empty_row(1);
		echo "<tr id=WieleOsobWybor style='display:'';'>";
			td(";rt;Osoba obsługująca<br /><br />Dodatkowe osoby");
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
		
		echo "<tr id=RodzajPojazdu style='display:none'>";
		td("140;r;<b>Rodzaj pojazdu</b>");
			td_(";;;");
			
				echo "<select name=hd_wyjazd_rp id=hd_wyjazd_rp onChange=\"if (this.value=='P') { $('#WpiszWyjazdTrasa').show(); $('#DataWyjazdu').show(); $('#WpiszWyjazdKm').show();} else { $('#WpiszWyjazdTrasa').hide(); $('#DataWyjazdu').show(); $('#WpiszWyjazdKm').hide();} \">";
				echo "<option value='P' SELECTED>prywatny</option>";
				echo "<option value='S'>służbowy</option>";
				echo "</select>";
				
				//echo "<input type=radio name=hd_wyjazd_rp id=hd_wyjazd_rp_P value='P' style=\"background-color:transparent;border:0px;\" onClick=\"$('#WpiszWyjazdTrasa').show(); $('#DataWyjazdu').show(); $('#WpiszWyjazdKm').show();\" />&nbsp;<a href=# class=normalfont onClick=\"if (document.getElementById('hd_wyjazd_rp_P').checked) { document.getElementById('hd_wyjazd_rp_P').checked=false; $('#WpiszWyjazdTrasa').hide(); $('#DataWyjazdu').hide(); $('#WpiszWyjazdKm').hide(); } else { document.getElementById('hd_wyjazd_rp_P').checked=true; $('#WpiszWyjazdTrasa').show(); $('#DataWyjazdu').show(); $('#WpiszWyjazdKm').hide();} \">prywatny</a>";
				
				//echo "<input type=radio name=hd_wyjazd_rp id=hd_wyjazd_rp_S value='S' style=\"background-color:transparent;border:0px;\" onClick=\"$('#WpiszWyjazdTrasa').hide(); $('#DataWyjazdu').hide(); $('#WpiszWyjazdKm').hide();\" />&nbsp;<a href=# class=normalfont onClick=\"if (document.getElementById('hd_wyjazd_rp_S').checked) { document.getElementById('hd_wyjazd_rp_S').checked=false; $('#WpiszWyjazdTrasa').show(); $('#DataWyjazdu').show(); $('#WpiszWyjazdKm').show(); } else { document.getElementById('hd_wyjazd_rp_S').checked=true; $('#WpiszWyjazdTrasa').hide(); $('#DataWyjazdu').hide(); $('#WpiszWyjazdKm').hide(); } \">służbowy</a>";
			_td();
		echo "</tr>";
	
		//tbl_empty_row(1);		
		echo "<tr id=WpiszWyjazdTrasa style='display:none'>";
			td("150;rt;<b>Trasa wyjazdowa<br /></b><sub>(tylko dla $currentuser)</sub>");
			td_(";;;");
			
				//echo "<fieldset style='background-color:silver;padding:3px;width:97%'>";
				//echo "<label for=trasa>Trasa:</label>"
				
				$result_k = mysql_query("SELECT filia_lokalizacja FROM $dbname.serwis_filie WHERE filia_id=$es_filia LIMIT 1", $conn) or die($k_b);
				list($temp_lok)=mysql_fetch_array($result_k);
				
				//echo "<input tabindex=-1 type=hidden id=lokalizacjazrodlowa value='$temp_lok'>";			
				$result_k = mysql_query("SELECT zgl_komorka FROM $dbname_hd.hd_zgloszenie WHERE ((zgl_widoczne=1) and (belongs_to=$es_filia) and (zgl_id=$_GET[id])) LIMIT 1", $conn) or die($k_b);
				list($temp_where)=mysql_fetch_array($result_k);
				
				echo "<input tabindex=-1 type=hidden id=lokalizacjazrodlowa value='$temp_lok'>";
				if ($_REQUEST[zgl_s]!=1) {
					echo "<input tabindex=-1 type=hidden id=lokalizacjadocelowa value='".$temp_where."'>";
				} else {
					echo "<input tabindex=-1 type=hidden id=lokalizacjadocelowa value='".toUpper($temp_where)."'>";
				}
				//echo "<br />";
				echo "<input class=buttons type=button onClick=\"document.getElementById('trasa').value=document.getElementById('lokalizacjazrodlowa').value.toUpperCase()+' - '+document.getElementById('lokalizacjadocelowa').value+' - '+document.getElementById('lokalizacjazrodlowa').value.toUpperCase(); document.getElementById('km').focus(); \" value='Generuj trasę ze zgłoszenia'/><br />";
				
				echo "<textarea class=wymagane id=trasa name=trasa cols=50 rows=3></textarea>";
				//echo "</fieldset>";
			_td();
		echo "</tr>";
		
		echo "<tr id=DataWyjazdu style='display:none;'>";
			td("150;rt;<b>Data wyjazdu</b>");
			td_(";;;");
				$dddd = Date('Y-m-d');
				$r4 = mysql_query("SELECT zgl_data FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id='$_GET[id]') LIMIT 1", $conn_hd) or die($k_b);
				list($data_utworzenia_zgloszenia)=mysql_fetch_array($r4);
				$za_pozno = 1;
				echo "<select name=hd_wyjazd_data id=hd_wyjazd_data>";
				echo "<option value='$dddd'"; if ($data_utworzenia_zgloszenia==$dddd) { $za_pozno=0; echo " SELECTED "; } echo ">$dddd</option>\n";
			
				for ($cd=1; $cd<($idw_dla_zbh+1); $cd++) {
					//if ($last_step_date<=SubstractDays($cd,$dddd)) {
						echo "<option value='".SubstractDays($cd,$dddd)."'"; 
						//if ($data_utworzenia_zgloszenia==SubstractDays($cd,$dddd)) { $za_pozno=0; echo " SELECTED "; } 
						echo ">".SubstractDays($cd,$dddd)."&nbsp;";
						if ($idw_dla_zbh_testowa) echo "[dla testów]";
						echo "</option>\n";
					//}
				}
				
			//	echo "<option value='".SubstractWorkingDays(1,$dddd)."'"; if ($data_utworzenia_zgloszenia==SubstractWorkingDays(1,$dddd)) { $za_pozno=0; echo " SELECTED "; } echo ">".SubstractWorkingDays(1,$dddd)."&nbsp;</option>\n";
			//	echo "<option value='".SubstractWorkingDays(2,$dddd)."'"; if ($data_utworzenia_zgloszenia==SubstractWorkingDays(2,$dddd)) { $za_pozno=0; echo " SELECTED "; } echo ">".SubstractWorkingDays(2,$dddd)."&nbsp;</option>\n";
				
	// sprawdź dostępy czasowe dla tego pracownika
			$sql_dc = "SELECT dc_dostep_dla_daty,dc_dostep_active_to FROM $dbname_hd.hd_dostep_czasowy WHERE ((dc_dostep_dla_osoby='$currentuser') and (dc_dostep_active=1) and (belongs_to=$es_filia)) ORDER BY dc_dostep_dla_daty DESC";
			$result_dc = mysql_query($sql_dc, $conn_hd) or die($k_b);
			$totalrows_dc = mysql_num_rows($result_dc);
			
			if ($totalrows_dc>0) {
				while ($newArray_dc = mysql_fetch_array($result_dc)) {
					$temp_dc_data	= $newArray_dc['dc_dostep_dla_daty'];
					$temp_dc_dostep_do	= $newArray_dc['dc_dostep_active_to'];
					echo "<option value='$temp_dc_data'"; 
					//if ($data_utworzenia_zgloszenia=='$temp_dc_data') { $za_pozno=0; echo "SELECTED"; } 
					echo ">$temp_dc_data (dostępna do $temp_dc_dostep_do)</option>\n";
				}
			}
	// koniec sprawdzania dostępów czasowych dla pracownika
	
				echo "</select>\n";
				//if ($za_pozno==0) echo "&nbsp;( Domyślnie data utworzenia zgłoszenia )";
				//if ($za_pozno==1) echo "&nbsp;<font color=red>Przekroczono czas wymagany na obsługę zgłoszenia (2 dni robocze)<br />Skontaktuj się z administratorem systemu</font>";
			_td();
		echo "</tr>";
		
		echo "<tr id=WpiszWyjazdKm style='display:none'>";
			td("150;rt;<b>Przejechane km</b>");
			td_(";;;");
				echo "<input class=wymagane id=km name=km style=text-align:right type=text size=3 maxlength=3 value=0 onBlur=\"if (this.value=='') this.value=0; \" onKeyPress=\"return filterInput(1, event, false); \"> km";
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

	echo "<tr id=DodajDoBazyWiedzy style='display:none;'>";
		td(";;");
		td_(";;;");
			echo "<input class=border0 type=checkbox name=DodajDoBazyWiedzyChkBox id=DodajDoBazyWiedzyChkBox onClick=\"if (this.checked) { document.getElementById('DodajDoBazyWiedzySelect').style.display=''; } else { document.getElementById('DodajDoBazyWiedzySelect').style.display='none'; } \" />";
				
			echo "<a href=# style='color:green;' class=normalfont onClick=\"if (document.getElementById('DodajDoBazyWiedzyChkBox').checked) { document.getElementById('DodajDoBazyWiedzyChkBox').checked=false; document.getElementById('DodajDoBazyWiedzySelect').style.display=''; return false;} else { document.getElementById('DodajDoBazyWiedzyChkBox').checked=true; document.getElementById('DodajDoBazyWiedzySelect').style.display='none'; return false;} \"> Dodaj wykonane czynności do bazy wiedzy</a>";
			echo "<br />";
		_td();
	echo "</tr>";
		
		echo "<tr id=OsobaPotwierdzajacaZamkniecie style='display:none;' >";
			td("150;r;Osoba potwierdzająca<br />zamknięcie");
			td_(";;;");
			
				$result441 = mysql_query("SELECT serwis_komorki.up_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) and (CONCAT(UPPER(serwis_piony.pion_nazwa),' ',UPPER(serwis_komorki.up_nazwa)) = '$_REQUEST[komorka]')) LIMIT 1", $conn) or die($k_b);
				list($sama_nazwa_up) = mysql_fetch_array($result441);
				
				//$sama_nazwa_up = substr($_REQUEST[komorka],strpos($_REQUEST[komorka]," ")+1,strlen($_REQUEST[komorka]));
				
				$r2 = mysql_query("SELECT up_id FROM $dbname.serwis_komorki WHERE (up_nazwa='$sama_nazwa_up') and (belongs_to=$es_filia) LIMIT 1", $conn_hd) or die($k_b);
				list($komorkaid)=mysql_fetch_array($r2);	
				
				//echo "$komorkaid";
				echo "<input type=hidden name=komorka_id1 id=komorka_id1 value='$komorkaid'>";
				echo "<input type=text name=hd_opz id=hd_opz size=38 maxlength=30 onKeyPress=\"return filterInput(0, event, false, ' ęóąśłżźćńĘÓĄŚŁŻŹĆŃ'); \" onFocus=\"this.select();\" onBlur=\"cUpper(this);\" >";
				echo "&nbsp;<input type=button class=buttons value=' Osoba zgłaszająca ' onClick=\"document.getElementById('hd_opz').value='".$_REQUEST[osoba]."';\">";
			_td();
		echo "</tr>";
			
		echo "<tr id=ZasadnoscZgloszenia style='display:none'>";
			td("120;r;Zasadność zgłoszenia");
			td_(";;;");
				echo "<input style='border:0px' type=radio name=zasadne id=zasadne value='TAK' CHECKED>TAK";
				echo "&nbsp;&nbsp;<input style='border:0px' type=radio name=zasadne id=zasadne value='NIE'>NIE";
			_td();
		echo "</tr>";
		//tbl_empty_row(1);	

		
		if (($kierownik_nr==$es_nr) && ($funkcja_kontroli_pracownikow==1)) {
			echo "<tr>";
			echo "<td colspan=2 class=left>";
				echo "<hr />";
			echo "</td>";
			
			echo "</tr>";
			echo "<tr>";
			echo "<td class=righttop>";
				echo "<b>Funkcje dla kierownika</b>";
			echo "</td>";
			echo "<td>";
				
				echo "<input class=border0 type=checkbox name=JakoInnaOsoba id=JakoInnaOsoba onClick=\"if (this.checked) { document.getElementById('zsjiu').style.display=''; } else { document.getElementById('zsjiu').style.display='none'; } \" />";
				
				echo "<a href=# class=normalfont onClick=\"if (document.getElementById('JakoInnaOsoba').checked) { document.getElementById('JakoInnaOsoba').checked=false; } else { document.getElementById('JakoInnaOsoba').checked=true; } if (document.getElementById('JakoInnaOsoba').checked) { document.getElementById('zsjiu').style.display=''; return false; } else { document.getElementById('zsjiu').style.display='none'; return false; }\"> <font color=blue>Zmień status zgłoszenia jako inny użytkownik</font></a>";
				
				$result44 = mysql_query("SELECT user_first_name,user_last_name FROM $dbname.serwis_uzytkownicy WHERE (belongs_to=$es_filia) and (user_locked=0) and (CONCAT(user_first_name,' ',user_last_name)<>'$currentuser') ORDER BY user_last_name ASC", $conn_hd) or die($k_b);
				$count_rows = mysql_num_rows($result44);
				
				echo "&nbsp;&nbsp;";
				echo "<select class=wymagane id=zsjiu name=zsjiu style='display:none' />\n";
				echo "<option value=''>wybierz osobę z listy</option>\n";
				while (list($temp_imie,$temp_nazwisko) = mysql_fetch_array($result44)) {
					$imieinazwisko = $temp_imie . " " . $temp_nazwisko;
					echo "<option value='$imieinazwisko' ";
					if ($currentuser=='$imieinazwisko') echo " SELECTED";
					echo ">$imieinazwisko</option>\n"; 
				}
				echo "</select>\n";	
			_td();
			echo "</tr>";
			echo "<tr>";
				echo "<td colspan=2 class=left>";
				//	echo "<hr />";
				echo "</td>";
			echo "</tr>";
			//tbl_empty_row(1);
			
		} else {
			echo "<tr>";
				echo "<td colspan=2 class=left>";
				echo "<br />";
				echo "</td>";
			echo "</tr>";
		}

	//echo "<span id= style='display:'>";
	// pytanie o rozwiązanie problemu (tylko dla Awarii i Awarii krytycznych
	echo "<input type=hidden name=old_rozwiazany id=old_rozwiazany value='$_REQUEST[rozwiazany]'>";
	
	if ((($_REQUEST[tk]=='2') || ($_REQUEST[tk]=='6')) && ($_REQUEST[rozwiazany]=='0')) {
	
		echo "<tr>";
			echo "<td colspan=2 class=left>";
				echo "<hr />";
			echo "</td>";
		echo "</tr>";
			
		echo "<tr id=block_rozliczone1>";
			td("150;rt;Problem rozwiązany");
			td_(";;;");		
				$czy_rozwiazany_cookie = $_REQUEST[rozwiazany];
				
				if (isset($_COOKIE['czy_rozwiazane_'.$_REQUEST[nr].''])) $czy_rozwiazany_cookie = $_COOKIE['czy_rozwiazane_'.$_REQUEST[nr].''];
				echo "<input type=hidden name=rozwiazany id=rozwiazany value='$czy_rozwiazany_cookie'>";								
				
//				echo ">->".$czy_rozwiazany_cookie;
				
				if ($czy_rozwiazany_cookie=='1') echo "<span id=rozw_status style='font-weight:bold'>TAK</span>";
				if ($czy_rozwiazany_cookie=='0') {
					echo "<span id=rozw_status style='font-weight:bold'>";
					if (isset($_COOKIE['czy_rozwiazane_'.$_REQUEST[nr].''])) {	
						echo "NIE";
					} else { echo "<font color=grey>?</font>"; }
					
					
					echo "</span>";	
				}
				
			_td();
		_tr();	
		
		echo "<tr id=block_rozliczone2>";
			td("150;;");
			echo "<td>";
				if ($czy_rozwiazany_cookie=='1') {
					echo "<h2 id=h_rozwiazany class=h3naprawa>Czy problem rozwiązany ? &nbsp;";
				} else {
					echo "<h2 id=h_rozwiazany style='background-color:#FC9E9E; border-color:#FB7373;'>Czy problem rozwiązany ? &nbsp;";
				}
				
					echo "<input type=button class=buttons value='TAK' onClick=\"document.getElementById('rozwiazany').value='1'; ClearCookie('czy_rozwiazane_$_REQUEST[nr]'); SetCookie('czy_rozwiazane_$_REQUEST[nr]','1');  document.getElementById('rozw_status').innerHTML='TAK'; document.getElementById('h_rozwiazany').style.backgroundColor='#319D4E'; document.getElementById('h_rozwiazany').style.borderColor='#2A8642'; document.getElementById('bottombuttons').style.display=''; \" />";
					echo "<input type=button class=buttons value='NIE' onClick=\"document.getElementById('rozwiazany').value='0'; ClearCookie('czy_rozwiazane_$_REQUEST[nr]'); SetCookie('czy_rozwiazane_$_REQUEST[nr]','0'); document.getElementById('rozw_status').innerHTML='NIE'; document.getElementById('h_rozwiazany').style.backgroundColor='#FCA2A2'; document.getElementById('h_rozwiazany').style.borderColor='#FA6666'; document.getElementById('bottombuttons').style.display='';\">";
				
				echo "</h2>";	
				echo "* Problem rozwiązany, kiedy awaria została usunięta za pomocą sprzętu zastępczego lub innego rozwiązania umożliwiającego normalną pracę";
			echo "</td>";
		_tr();	
		tbl_empty_row(1);
	} else {
		if ((($_REQUEST[tk]=='2') || ($_REQUEST[tk]=='6')) && ($_REQUEST[rozwiazany]=='1')) {
			echo "<span id=block_rozliczone1></span>";
			echo "<span id=block_rozliczone2></span>";
			
			echo "<tr id=block_rozliczone3>";
				td("150;rt;<font color=green>Problem rozwiązany</font>");
				td_(";;;");				
					echo "<span id=rozw_status><font color=green><b>TAK</b></font></span>";
				_td();
			_tr();		
			tbl_empty_row(1);
		}
		echo "<input type=hidden name=rozwiazany id=rozwiazany value='1'>";
		$czy_rozwiazany_cookie = '1';
	}
	//echo "</span>";
	
	echo "<input type=hidden name=zs_kategoria id=zs_kategoria value=$_REQUEST[tk]>";
	echo "<input type=hidden name=zs_podkategoria id=zs_podkategoria value=$_REQUEST[tpk]>";
	
	echo "<input type=hidden name=ww id=ww value=$_REQUEST[ww]>";
	echo "<input type=hidden name=temp_temat value='$temp_temat (zgłoszenie nr $_REQUEST[id])'>";
	
	endtable();	
	if ($temp_czy_ww==1) {
		echo "<span id=wymaga_wyjazdu_ustawione>";
			infoheader('Zgłoszenie wymaga wyjazdu | ustawione przez <b>'.$temp_czy_ww_osoba.'</b> w dniu <b>'.substr($temp_czy_ww_data,0,16).'</b>');
		echo "</span>";
	} else echo "<span id=wymaga_wyjazdu_ustawione></span>";
	
	echo "<span id=wymaga_wyjazdu_text>";
		infoheader('Domyślnie ustawiono znacznik: <b>"Zgłoszenie wymaga wyjazdu"</b>');
	echo "</span>";
	
	
	//echo "<input type=button class=buttons value='test' onClick=\"$.prompt('Czy utworzyć nowe zgłoszenie awarii zwykłej, będące kontynuacją zgłoszenia o numerze ?',{buttons:[{title: 'Tak',value:true},{title: 'Nie',value:false}], submit: function(v,m,f){ alert(v); } });\" />";
	
	if ((($_REQUEST[tk]=='2') || ($_REQUEST[tk]=='6')) && ($_REQUEST[rozwiazany]=='0')) {
		if ($czy_rozwiazany_cookie=='1') {
			echo "<span id=bottombuttons style='display:'>";
		} else {
			echo "<span id=bottombuttons style='display:none'>";
		}
	} else echo "<span id=bottombuttons style='display:'>";
	
	startbuttonsarea("right");
	oddziel();
	
	echo "<span style='float:left'>";
	echo "<input type=button class=buttons title='Pokaż kroki realizacji zgłoszenia nr $temp2_zgl_nr w nowym oknie' onclick=\"newWindow_r($_COOKIE[max_x],$_COOKIE[max_y],'hd_p_zgloszenie_kroki.php?newwindow=1&nr=$temp2_zgl_nr&id=$temp2_zgl_nr');\" value=\"Kroki realizacji\">";
	
	echo "<input type=hidden id=ww_value name=ww_value value=$temp_czy_ww>";
	
	echo "<span id=show_ww>";
	if ($_REQUEST[tk]!=1) {
		echo " | ";
		echo "<input class=border0 type=checkbox name=wymaga_wyjazdu id=wymaga_wyjazdu";
		if ($temp_czy_ww==1) { echo " checked=checked "; }
		echo "><a tabindex=-1 class=normalfont href=# onClick=\"if (document.getElementById('wymaga_wyjazdu').checked) { document.getElementById('wymaga_wyjazdu').checked=false; return false; } else { document.getElementById('wymaga_wyjazdu').checked=true; return false; }\"><font color=blue>&nbsp;Zgłoszenie wymaga wyjazdu</font></a>";
	}
	echo "</span>";

	echo "</span>";
	
	echo "<input id=submit type=submit class=buttons style='font-weight:bold' name=submit value='Zapisz' "; 
//	if (($_REQUEST[ts]=='4') && ($_REQUEST[zgoda]=='9')) echo " style='display:none'";
	echo "/>";
	//echo "<input type=button class=buttons name=reset value=\"Wyczyść zgłoszenie\" onClick=\"pytanie_wyczysc('Wyczyścić formularz ?'); \" />";
	echo "<input class=buttons id=anuluj type=button onClick=\"if (confirm('Potwierdzasz anulowanie wprowadzonych zmian ?')) { ClearCookie('wpisane_cw_m_".$_REQUEST[nr]."'); ClearCookie('wpisane_wc_".$_REQUEST[nr]."'); ClearCookie('wpisana_gzs_".$_REQUEST[nr]."'); ClearCookie('nowy_status_".$_REQUEST[nr]."'); ClearCookie('czy_rozwiazane_".$_REQUEST[nr]."'); if (opener) opener.location.reload(true); self.close(); };\" value=Anuluj>";
	
	//echo "<span id=Saving style=display:none><b><font color=red>Trwa zapisywanie danych... proszę czekać&nbsp;</font></b><input class=imgoption type=image src=img/loader.gif></span>";
	
	endbuttonsarea();
	
	if ((($_REQUEST[tk]=='2') || ($_REQUEST[tk]=='6')) && ($_REQUEST[rozwiazany]=='0')) {
		
	}
	echo "</span>";
	echo "<input type=hidden name=id value='$_GET[id]' />";
	echo "<input type=hidden id=hdnr name=nr value='$_GET[nr]' />";
	echo "<input type=hidden name=unr value='$_GET[unr]' />";
	
	$w2 = mysql_query("SELECT zgl_kategoria, zgl_podkategoria, zgl_status, zgl_poledodatkowe1 FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$es_filia) and (zgl_nr=$_GET[nr])) LIMIT 1", $conn_hd) or die($k_b);
	list($zgl_kat, $zgl_podkat, $zgl_status, $zgl_nrzglwan)=mysql_fetch_array($w2);

	echo "<input type=hidden id=kat_id name=kat_id value='$zgl_kat' />";
	echo "<input type=hidden id=podkat_id name=podkat_id value='$zgl_podkat' />";
	echo "<input type=hidden id=status_id name=status_id value='$zgl_status' />";
	
	if ($zgl_nrzglwan!='') {
		echo "<input type=hidden name=numerzgloszenia1 value='$zgl_nrzglwan' />";
	}

	echo "</form>";
	
echo "</div>";

	}

?>
<script>ShowWaitingMessage('Trwa zapisywanie danych... proszę czekać', 'Saving');</script>

<?php if ($_REQUEST[clearcookies]=='1') { ?>
<script>
	
	function ClearCookie (name, value) { document.cookie = name + "=" + escape (value) + ";expires=Thu, 01-Jan-1970 00:00:01 GMT"; }
	document.getElementById('ZmienStatus').focus();
	
	ClearCookie('way_showed_<?php echo $_REQUEST[nr]; ?>'); 
	ClearCookie('way_saved_<?php echo $_REQUEST[nr]; ?>');	
	ClearCookie('nowy_status_<?php echo $_REQUEST[nr]; ?>');	
	ClearCookie('wpisane_wc_<?php echo $_REQUEST[nr]; ?>');	
	ClearCookie('wpisana_dzs_<?php echo $_REQUEST[nr]; ?>');	
	ClearCookie('wpisana_gzs_<?php echo $_REQUEST[nr]; ?>');	
	ClearCookie('wpisane_cw_h_<?php echo $_REQUEST[nr]; ?>');	
	ClearCookie('wpisane_cw_m_<?php echo $_REQUEST[nr]; ?>');	
	ClearCookie('zdiagnozowany_<?php echo $_REQUEST[nr]; ?>');	
	ClearCookie('oferta_wyslana_<?php echo $_REQUEST[nr]; ?>');	
	ClearCookie('akceptacja_kosztow_<?php echo $_REQUEST[nr]; ?>');	
	ClearCookie('zamowienie_wys_<?php echo $_REQUEST[nr]; ?>');	
	ClearCookie('zamowienie_zreal_<?php echo $_REQUEST[nr]; ?>');	
	ClearCookie('gotowy_do_oddania_<?php echo $_REQUEST[nr]; ?>');	
	ClearCookie('wybrane_powiazanie_<?php echo $_REQUEST[nr]; ?>');	

</script>
<?php 
	$_REQUEST[clearcookies]=0;
	} ?>

<script>SelectZmienStatusOnBlur();</script>

<script type="text/javascript" src="js/jquery/entertotab.js"></script>
<script type='text/javascript'>
  
EnterToTab.init(document.forms.hd_o_zgloszenia, false);

$("#hd_opz").autocomplete("hd_get_pracownik_list.php", {
	width: 360,
	max:100,
	matchContains: true,
	mustMatch: false,
	minChars: 1,
	extraParams: { komorka: function() { return $("#komorka_id1").val(); } }, 
	//multiple: true,
	//highlight: false,
	//multipleSeparator: ",",
	selectFirst: false
});

document.getElementById('SelectZdiagnozowany').value=document.getElementById('_SelectZdiagnozowany').value;
document.getElementById('OfertaWyslanaInput').value=document.getElementById('_OfertaWyslanaInput').value;
document.getElementById('SelectAkceptacjaKosztow').value=document.getElementById('_SelectAkceptacjaKosztow').value;
document.getElementById('ZamowienieWyslaneInput').value=document.getElementById('_ZamowienieWyslaneInput').value;
document.getElementById('ZamowienieZrealizowaneInput').value=document.getElementById('_ZamowienieZrealizowaneInput').value;
document.getElementById('GotowyDoOddaniaInput').value=document.getElementById('_GotowyDoOddaniaInput').value;

<?php if ($_COOKIE['nowy_status_'.$_REQUEST[nr].'']!=null) { ?>document.getElementById('SelectZmienStatus').value=<?php echo $_COOKIE['nowy_status_'.$_REQUEST[nr].'']; } else { ?><?php } ?>
	
<?php if ($_COOKIE['zdiagnozowany_'.$_REQUEST[nr].'']!=null) { ?>document.getElementById('SelectZdiagnozowany').value=<?php echo $_COOKIE['zdiagnozowany_'.$_REQUEST[nr].'']; } else { ?><?php } ?>

<?php if ($_COOKIE['oferta_wyslana_'.$_REQUEST[nr].'']!=null) { ?>document.getElementById('OfertaWyslanaInput').value=<?php echo $_COOKIE['oferta_wyslana_'.$_REQUEST[nr].'']; } else { ?><?php } ?>

<?php if ($_COOKIE['akceptacja_kosztow_'.$_REQUEST[nr].'']!=null) { ?>document.getElementById('SelectAkceptacjaKosztow').value=<?php echo $_COOKIE['akceptacja_kosztow_'.$_REQUEST[nr].'']; } else { ?><?php } ?>

<?php if ($_COOKIE['zamowienie_wys_'.$_REQUEST[nr].'']!=null) { ?>document.getElementById('ZamowienieWyslaneInput').value=<?php echo $_COOKIE['zamowienie_wys_'.$_REQUEST[nr].'']; } else { ?><?php } ?>

<?php if ($_COOKIE['zamowienie_zreal_'.$_REQUEST[nr].'']!=null) { ?>document.getElementById('ZamowienieZrealizowaneInput').value=<?php echo $_COOKIE['zamowienie_zreal_'.$_REQUEST[nr].'']; } else { ?><?php } ?>

<?php if ($_COOKIE['gotowy_do_oddania_'.$_REQUEST[nr].'']!=null) { ?>document.getElementById('GotowyDoOddaniaInput').value=<?php echo $_COOKIE['gotowy_do_oddania_'.$_REQUEST[nr].'']; } else { ?><?php } ?>

</script>

<?php
	if ($auto_select==1) {
?>
	<script>
		document.getElementById('WymianaPodzespolu').style.display='none';
	</script>
<?php } ?>

<script type="text/javascript">
	$('.block').hover(
		function(){
			$(this).addClass('block_hover');
		},
		function(){
			$(this).removeClass('block_hover');
	});
	
	<?php if (((($_REQUEST[tk]=='2') || ($_REQUEST[tk]=='6')) && ($_REQUEST[tpk]=='0')) && ($default_status=='3A')) { ?>
		if ((readCookie('way_showed_<?php echo $_REQUEST[nr]; ?>')=='1') || (readCookie('way_showed_<?php echo $_REQUEST[nr]; ?>')==null)) {
			ChangeWay(readCookie('way_saved_<?php echo $_REQUEST[nr]; ?>')); $('#uo').show(); $('#po').hide();
		} else {
			ChangeWay(0); $('#uo').hide(); $('#po').show();
		}
	<?php } ?>
</script>

<?php if ($ListaStatusow=='') { ?>
	<script>
		document.getElementById('submit').style.display='none';
	</script>
<?php } ?>

<?php include('warning_messages_blinking.php'); ?>

<script>HideWaitingMessage();</script>

</body>
</html>