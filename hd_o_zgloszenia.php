<?php 
include_once('header.php');
include_once('cfg_helpdesk.php');
?>

<body>

<script>ShowWaitingMessage();</script><?php ob_flush(); flush();

function toUpper($string) {
	$string = strtr($string,'qwertyuiopasdfghjklzxcvbnm','QWERTYUIOPASDFGHJKLZXCVBNM');
	return (strtr($string, 'ęóąśłżźćń','ĘÓĄŚŁŻŹĆŃ' )); 
}; 

if (isset($_SESSION['refresh_o_zgloszenia_'.$_REQUEST[nr].''])) {
} else {
	session_register('refresh_o_zgloszenia_'.$_REQUEST[nr].'');
	$_SESSION['refresh_o_zgloszenia_'.$_REQUEST[nr].'']=0;
}

//if (isset($_SESSION['wybrany_sprzet_do_wymiany_dla_zgl_nr_'.$_REQUEST[nr].''])) {
//} else {
session_register('wybrany_sprzet_do_wymiany_dla_zgl_nr_'.$_REQUEST[nr].'');
$_SESSION['wybrany_sprzet_do_wymiany_dla_zgl_nr_'.$_REQUEST[nr].'']=0;
//}
session_register('naprawa_id_for_zgloszenie_nr'.$_REQUEST[nr].'');
$_SESSION['naprawa_id_for_zgloszenie_nr_'.$_REQUEST[nr].'']=0;

//	echo $_REQUEST[action];
	
	$sql="SELECT * FROM $dbname_hd.hd_zgloszenie WHERE (zgl_nr=$_REQUEST[nr]) LIMIT 1";
	$result = mysql_query($sql, $conn_hd) or die($k_b);

	$newArray = mysql_fetch_array($result);
	
	$temp_id  			= $newArray['zgl_id'];
	$temp_nr			= $newArray['zgl_nr'];
	$temp_unikalny_nr	= $newArray['zgl_unikalny_nr'];
	$temp_poczta_nr		= $newArray['zgl_poczta_nr'];
	$temp_data			= $newArray['zgl_data'];
	$temp_godzina		= $newArray['zgl_godzina'];
	$temp_komorka		= $newArray['zgl_komorka'];
	$temp_osoba			= $newArray['zgl_osoba'];
	$temp_telefon		= $newArray['zgl_telefon'];
	$temp_temat			= $newArray['zgl_temat'];	
	$temp_tresc			= $newArray['zgl_tresc'];	
	
	$temp_kategoria		= $newArray['zgl_kategoria'];
	$temp_podkategoria	= $newArray['zgl_podkategoria'];
	$temp_podkategoria2	= $newArray['zgl_podkategoria_poziom_2'];
	$temp_priorytet		= $newArray['zgl_priorytet'];
	$temp_status 		= $newArray['zgl_status'];
	
	$temp_data_roz		= $newArray['zgl_data_rozpoczecia'];
	$temp_data_zak		= $newArray['zgl_data_zakonczenia'];
	
	$temp_zgl_data_rozpoczecia		= $newArray['zgl_data_rozpoczecia'];
	$temp_zgl_data_zakonczenia		= $newArray['zgl_data_zakonczenia'];
	
	$temp_czas			= $newArray['zgl_razem_czas'];
	$temp_km			= $newArray['zgl_razem_km'];
	$temp_or			= $newArray['zgl_osoba_rejestrujaca'];
	$temp_zz			= $newArray['zgl_zasadne'];
	$temp_bt1			= $newArray['belongs_to'];
	
	$temp_zgl_E1P		= $newArray['zgl_E1P'];
	$temp_zgl_E2P		= $newArray['zgl_E2P'];
	$temp_op			= $newArray['zgl_osoba_przypisana'];
	$temp_opz			= $newArray['zgl_osoba_potwierdzajaca_zamkniecie_zgloszenia'];
	
	$temp_nrawarii		= $newArray['zgl_poledodatkowe1'];
	$temp_111			= $newArray['zgl_poledodatkowe2'];
	$temp_parent_zgl	= $newArray['zgl_kontynuacja_zgloszenia_numer'];
	$temp_naprawa_id	= $newArray['zgl_naprawa_id'];
	
	$temp_rekl_czy_jest = $newArray['zgl_czy_to_jest_reklamacyjne'];
	$temp_rekl_nr	 	= $newArray['zgl_nr_zgloszenia_reklamowanego'];
	$temp_rekl_czy_ma	= $newArray['zgl_czy_ma_zgl_reklamacyjne'];
	$temp_czy_pow_z_wp	= $newArray['zgl_czy_powiazane_z_wymiana_podzespolow'];
	$temp_czy_rozwiazany	= $newArray['zgl_czy_rozwiazany_problem'];
	$temp_zgl_komorka_working_hours = $newArray['zgl_komorka_working_hours'];

	$temp_czy_ww	= $newArray['zgl_wymagany_wyjazd'];
	$temp_czy_ww_data	= $newArray['zgl_wymagany_wyjazd_data_ustawienia'];		$temp_czy_ww_data = substr($temp_czy_ww_data,0,16);
	$temp_czy_ww_osoba	= $newArray['zgl_wymagany_wyjazd_osoba_wlaczajaca'];
	
	$temp_ss_id	= $newArray['zgl_sprzet_serwisowy_id'];

	$temp_czy_synchronizowac = $newArray['zgl_czy_synchronizowac'];

	$czy_wyroznic_zgloszenia_seryjne = 0;
	
	$temp_zgl_spr_data		= $newArray['zgl_sprawdzone_data'];
	$temp_zgl_spr_osoba		= $newArray['zgl_sprawdzone_osoba'];
	
	$r40 = mysql_query("SELECT zgl_szcz_id FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$_REQUEST[nr]) and (zgl_szcz_widoczne=1) and (zgl_szcz_byl_wyjazd=1)", $conn) or die($k_b);
	$CzyBylWyjazd = false;
	list($_zgl_wyj)=mysql_fetch_array($r40);
	if ($_zgl_wyj>0) $CzyBylWyjazd = true;
	
	$r40 = mysql_query("SELECT SUM(zgl_szcz_czas_trwania_wyjadu) FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$_REQUEST[nr]) and (zgl_szcz_widoczne=1) and (zgl_szcz_byl_wyjazd=1)", $conn) or die($k_b);
	list($PrzejazCzasLacznie)=mysql_fetch_array($r40);
	
	if ($_GET[zgl_s]=='1') $czy_wyroznic_zgloszenia_seryjne = 1;
	
	if ($_GET[action]=='obsluga') {
		echo "<h3 style='padding:10px; font-weight:normal;'>Obsługa zgłoszenia nr <b>".$_REQUEST[nr]."</b>"; 
		if ($czy_wyroznic_zgloszenia_seryjne==1) {
			if ($_GET[zgl_s]=='1') echo " [zgł. seryjne]";
		}
		echo "</h3>";
	} else {
		echo "<h3>Informacje o zgłoszeniu nr ".$_GET[nr]."</h3>";
	}
		
	if ($temp_zgl_komorka_working_hours=='') $temp_zgl_komorka_working_hours = $default_working_time;
		
		
				// okienka ostrzegawcze | POCZĄTEK
				$__zgl_nr 		= $temp_nr;
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
				$__add_refresh	= 1;				// dodatkowe wymuszenie odświeżenia formatki
				$__add_br		= 1;
				$__tunon		= $turnon__hd_o_zgloszenia;
				
				if ($__tunon) include('warning_messages.php');
				// okienka ostrzegawcze | KONIEC		
		
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
	$pozwol_na_modikacje = true;
	if (($temp_bt1!=$es_filia) && ($temp_naprawa_id<=0)) $pozwol_na_modikacje = false;
	
	if ($temp_naprawa_id>0) {
	
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
		
		$result99 = mysql_query("SELECT * FROM $dbname.serwis_naprawa WHERE naprawa_hd_zgl_id='$numer_zgloszenia' LIMIT 1");
		while ($dane99 = mysql_fetch_array($result99)) {
			$mid 		= $dane99['naprawa_id'];					
			$mnazwa 	= $dane99['naprawa_nazwa'];
			$mmodel		= $dane99['naprawa_model'];			
			$msn 		= $dane99['naprawa_sn'];
			$mni 		= $dane99['naprawa_ni'];
			$mstatus 	= $dane99['naprawa_status'];
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
			
		}

		$naprawa_lokalna = true;
		if (($mnwif!=$bt) && ($mnwif!=0) && ($es_filia!=$mnwif)) $naprawa_lokalna = false;
		
		$pozwol_na_modikacje = true;
		//echo "<br />($mnwif!=$bt) && ($mnwif!=0) && ($mnwifpz==1) && ($es_filia==$mnwif)";
		//echo "<br />$mnwif!=$bt) && ($mnwifpz==1";
		
		//if (($mnwif!=$bt) && ($mnwif!=0)) $pozwol_na_modikacje = false;
		if (($mnwif!=$bt) && ($mnwif!=0) && ($mnwifpz==1) && ($es_filia==$mnwif)) $pozwol_na_modikacje = false;
		
		//if ($pozwol_na_modikacje == true) { echo "true"; } else { echo "false"; }
		
		$result65 = mysql_query("SELECT sn_nazwa FROM $dbname.serwis_sposob_naprawy WHERE (sn_id='$mstatus') LIMIT 1", $conn) or die($k_b);
		list($mstatus_opis)=mysql_fetch_array($result65);

	//	naprawaheader("<center><b>Zgłoszenie powiązane z naprawą</b><a title=' Pokaż szczegóły naprawy'><input class=imgoption type=image src=img/detail.gif onclick=\"newWindow(800,600,'p_naprawy_szczegoly.php?id=$mid'); return false;\"></a><br /><br /><b>".$mnazwa." ".$mmodel."</b> | SN: <b>".$msn."</b> | Status naprawy: <b>".$mstatus_opis."</b></center>");
		
			if ($msz>0) {
				$result99 = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni FROM $dbname.serwis_magazyn WHERE magazyn_id='$msz' LIMIT 1", $conn) or die($k_b);
				list($szid, $sznazwa, $szmodel, $szsn, $szni) = mysql_fetch_array($result99);
				
				naprawaheader("<center>Zgłoszenie powiązane z naprawą | status naprawy: <b>".$mstatus_opis."</b> | <input type=button class=buttons value='szczegóły naprawy' onclick=\"newWindow(800,600,'p_naprawy_szczegoly.php?id=$mid'); return false;\" /><br /><b>Sprzęt uszkodzony: </b>".$mnazwa." ".$mmodel." | SN: ".$msn." | NI: $mni<br /><b>Sprzęt zastępczy: </b>".$sznazwa." ".$szmodel." | SN: ".$szsn." | NI: ".$szni." | <input type=button class=buttons value='szczegóły' onclick=\"newWindow(800,600,'p_serwis_szczegoly.php?id=$_REQUEST[nr]&parentid=$temp_parent_zgl'); return false;\" /></center>");
				
				if (($move_naprawa==false) && ($mnwif_NazwaFilii!='')) naprawaheader2("<center>Realizacja naprawy przekazana do lokalizacji: <b>".$mnwif_NazwaFilii."</b><br />Zamknięcie zgłoszenia możliwe będzie po zwrocie sprzętu do Twojej lokalizacji.");
				if (($move_naprawa==true) && ($mnwif!=0) && ($mnwifpz==0)) naprawaheader2("<center>Naprawiany sprzęt przekazany z lokalizacji: <b>".$NazwaMojejFilii."</b><br />Zamknięcie zgłoszenia możliwe jest przez osobę z filii przekazującej.");
				
			} else {
				naprawaheader("<center>Zgłoszenie powiązane z naprawą | status naprawy: <b>".$mstatus_opis."</b> | <input type=button class=buttons value='szczegóły naprawy' onclick=\"newWindow(800,600,'p_naprawy_szczegoly.php?id=$mid'); return false;\" /><br /><b>Sprzęt uszkodzony: </b>".$mnazwa." ".$mmodel." | SN: ".$msn." | NI: $mni</center>");
				
				if (($move_naprawa==false) && ($mnwif_NazwaFilii!='')) naprawaheader2("<center>Realizacja naprawy przekazana do lokalizacji: <b>".$mnwif_NazwaFilii."</b><br />Zamknięcie zgłoszenia możliwe będzie po zwrocie sprzętu do Twojej lokalizacji.");
				if (($move_naprawa==true) && ($mnwif!=0) && ($mnwifpz==0)) naprawaheader2("<center>Naprawiany sprzęt przekazany z lokalizacji: <b>".$NazwaMojejFilii."</b><br />Zamknięcie zgłoszenia możliwe jest przez osobę z filii przekazującej.");
			}
		
	}
		
	if (($temp_ss_id>0) && (($msz<0) || ($msz==''))) {
		$result99 = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni,magazyn_uwagi,magazyn_uwagi_sa,magazyn_status,belongs_to FROM $dbname.serwis_magazyn WHERE magazyn_id='$temp_ss_id' LIMIT 1", $conn) or die($k_b);
		list($mid, $mnazwa, $mmodel, $msn, $mni, $muwagi, $muwagisa,$mstatus,$bt) = mysql_fetch_array($result99);
		
		ssheader("<center>Zgłoszenie powiązane z przekazaniem sprzętu serwisowego | <input type=button class=buttons value='szczegóły' onclick=\"newWindow_r(800,600,'p_serwis_szczegoly.php?id=$nr&komorka=".urlencode($_REQUEST[komorka])."'); return false;\" /><br />".$mnazwa." ".$mmodel." | SN: ".$msn." | NI: $mni</center>");
	}		
	// jeżeli zgłoszenie powiązane z wymianą podzespołów w komputerze klienta
	
	if ($temp_czy_pow_z_wp_parent==1) {
		$sql_wp="SELECT wp_sprzet_opis, wp_sprzet_sn, wp_sprzet_ni FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_zgl_id=$temp_parent_zgl) and (belongs_to=$es_filia) LIMIT 1";	
		$result_wp = mysql_query($sql_wp, $conn_hd) or die($k_b);

		list($wp_o,$wp_sn,$wp_ni)=mysql_fetch_array($result_wp);
		if ($wp_sn=='') $wp_sn='-';
		if ($wp_ni=='') $wp_ni='-';

		echo "<h5 class=h5yellow><b>Zgłoszenie powiązane z wymianą podzespołów (w zgłoszeniu nr $temp_parent_zgl) w:</b><br /><br /><b>$wp_o</b> | SN: <b>$wp_sn</b> | NI: <b>$wp_ni</b></h5>";	
	}
	
	if ($temp_czy_pow_z_wp==1) {
		$sql_wp="SELECT wp_sprzet_opis, wp_sprzet_sn, wp_sprzet_ni FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_zgl_id=$_REQUEST[nr]) and (belongs_to=$es_filia) LIMIT 1";	
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
			
			//naprawaheader("<center><b>Zgłoszenie powiązane z naprawą (w zgłoszeniu nr $temp_rekl_nr)</b><a title=' Pokaż szczegóły naprawy'><input class=imgoption type=image src=img/detail.gif onclick=\"newWindow(800,600,'p_naprawy_szczegoly.php?id=$mid'); return false;\"></a><br /><br /><b>".$mnazwa." ".$mmodel."</b> | SN: <b>".$msn."</b> | Status naprawy: <b>".$mstatus_opis."</b></center>");
		}
	
		if ($temp_czy_pow_z_wp_parent==1) {
			$sql_wp="SELECT wp_sprzet_opis, wp_sprzet_sn, wp_sprzet_ni FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_zgl_id=$temp_rekl_nr) and (belongs_to=$es_filia) LIMIT 1";	
			$result_wp = mysql_query($sql_wp, $conn_hd) or die($k_b);

			list($wp_o,$wp_sn,$wp_ni)=mysql_fetch_array($result_wp);
			if ($wp_sn=='') $wp_sn='-';
			if ($wp_ni=='') $wp_ni='-';

			//echo "<h5 class=h5yellow><b>Zgłoszenie powiązane z wymianą podzespołów (w zgłoszeniu nr $temp_rekl_nr) w:</b><br /><br /><b>$wp_o</b> | SN: <b>$wp_sn</b> | NI: <b>$wp_ni</b></h5>";
			echo "<h5 class=h5yellow>Zgłoszenie powiązane z wymianą podzespołów (w zgłoszeniu nr <b>$temp_rekl_nr</b>) w:<br />$wp_o | SN: $wp_sn | NI: $wp_ni<br /></h5>";
		}
				
	}
	
	if ($temp_rekl_czy_ma==1) {
		$result_rekl = mysql_query("SELECT zgl_nr FROM $dbname_hd.hd_zgloszenie WHERE (zgl_nr_zgloszenia_reklamowanego=$temp_nr)");
		
		while (list($rekl_nr) = mysql_fetch_array($result_rekl)) {	
			echo "<h5 class=h5blue>Zgłoszenie numer <b>$_REQUEST[nr]</b> było reklamowane. Utworzono z niego zgłoszenie nr <b>".$rekl_nr."</b>";
			echo "&nbsp;<input class=buttons type=button onClick=\"self.location.href='hd_o_zgloszenia.php?action=obsluga&nr=$rekl_nr&id=$rekl_nr'\" value=' Przejdź do zgłoszenia nr ".$rekl_nr." ' />";		
			echo "</h5>";
		}
		
	}
	
	//starttable();
	echo "<table class=maxwidth border=0 cellspacing=2 cellpadding=0>";
	$dddd = Date('Y-m-d');
	$tttt = Date('H:i');

	include_once('systemdate.php');

//	tbl_empty_row(1);
	
		tr_();	td_("150;rt;Data zgłoszenia;"); 		_td(); 	td_(";;;");		echo "<b>$temp_data ".substr($temp_godzina,0,5)."</b>";	
			echo "<span style='float:right'>";
				echo "<input class=buttons type=button onClick=\"newWindow_r(800,600,'hd_potwierdzenie.php?id=".$_REQUEST[nr]."&nr=".$_REQUEST[nr]."&pdata=".date('Y-m-d')."');\" style='font-weight:bold' value='Drukuj potwierdzenie' />";
			echo "</span>";
		_td(); 	_tr();
		if ($temp_poczta_nr!='') {
			tr_();	td_("150;r;Numer zgłoszenia z poczty;"); 	_td(); 	td_(";;;");		echo "<b>$temp_poczta_nr</b>";	_td(); 	_tr();
			tbl_empty_row(2);
		}
		
		$r44 = mysql_query("SELECT up_id, up_ip, up_telefon FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((serwis_komorki.belongs_to=$es_filia) and (serwis_piony.pion_id=serwis_komorki.up_pion_id) and (CONCAT(serwis_piony.pion_nazwa,' ',serwis_komorki.up_nazwa)='$temp_komorka'))", $conn) or die($k_b);
		list($_upid,$_ip,$_tel)=mysql_fetch_array($r44);
		
		tr_();	td_("150;rt;Komórka;"); 				_td(); 	td_(";;;");		
		
		if ($_REQUEST[zgl_s]==1) {
			$temp_komorka = toUpper($temp_komorka);
		}
		
		echo "<a class=normalfont title='Szczegółowe informacje o $temp_komorka' href=# onClick=\"newWindow_r(700,600,'p_komorka_szczegoly.php?id=$_upid'); return false;\" ><b>".($temp_komorka)."</b></a>&nbsp;&nbsp;";
		
			echo "<span style='float:right'>";
				if ($_ip!='') echo "<input type=button class=buttons onclick=\"newWindow(500,200,'hd_p_pokaz_ip.php?komorkanazwa=".urlencode($temp_komorka)."');return false;\" value='Adres IP'/>";
				if ($_tel!='') echo "<input type=button class=buttons onclick=\"newWindow(500,200,'hd_p_pokaz_tel.php?komorkanazwa=".urlencode($temp_komorka)."');return false;\" value='Telefon'/>";
				if ($temp_zgl_komorka_working_hours!='') echo "<input type=button class=buttons title=\"Obowiązujące w momencie rejestracji zgłoszenia godziny pracy komórki\" onclick=\"newWindow(500,300,'hd_p_pokaz_godziny.php?nr=$temp_nr&komorkanazwa=".urlencode($temp_komorka)."&wa=".urlencode($temp_zgl_komorka_working_hours)."');return false;\" value='Zapisane w zgłoszeniu godziny pracy'/>";
			echo "</span>";
		_td(); 	_tr();
		//tr_();	td_("150;rt;Osoba zgłaszająca;"); 	_td(); 	td_(";;;");		echo "<b>$temp_osoba</b>"; if ($temp_telefon!='') echo " | Telefon kontaktowy: <b>$temp_telefon</b>"; _td(); 	_tr();
		tr_();	td_("150;rt;Temat;"); 		_td(); 	td_(";;;");		echo "<b>".nl2br(wordwrap($temp_temat, 110, "<br />"))."</b>";	_td(); 	_tr();
		tr_();	td_("150;rt;Treść;"); 		_td(); 	td_(";;;"); 	echo "<b>".nl2br(wordwrap($temp_tresc, 110, "<br />"))."</b>";	_td(); 	_tr();
//		tr_();	td_("150;rt;Treść zgłoszenia;"); 		_td(); 	td_(";;;");		echo "<textarea cols=96 rows=5  style='border:0px;background-color:transparent;font-family:tahoma;font-size:11px;font-weight:bold;' readonly tabindex=-1>$_REQUEST[hd_tresc]</textarea>";	_td(); 	_tr();
		
		$r1 = mysql_query("SELECT hd_kategoria_opis FROM $dbname_hd.hd_kategoria WHERE (hd_kategoria_nr='$temp_kategoria') LIMIT 1", $conn_hd) or die($k_b);list($kat_opis)=mysql_fetch_array($r1);
		$r2 = mysql_query("SELECT hd_podkategoria_opis FROM $dbname_hd.hd_podkategoria WHERE (hd_podkategoria_nr='$temp_podkategoria') LIMIT 1", $conn_hd) or die($k_b);list($podkat_opis)=mysql_fetch_array($r2);
		$r3 = mysql_query("SELECT hd_priorytet_opis FROM $dbname_hd.hd_priorytet WHERE (hd_priorytet_nr='$temp_priorytet') LIMIT 1", $conn_hd) or die($k_b);list($priorytet_opis)=mysql_fetch_array($r3);
		$r4 = mysql_query("SELECT hd_status_opis FROM $dbname_hd.hd_status WHERE (hd_status_nr='$temp_status') LIMIT 1", $conn_hd) or die($k_b);list($status_opis)=mysql_fetch_array($r4);

		//tbl_empty_row(2);
			
		/*
		tr_();	td_("150;r;Kategoria;"); 	_td(); 	
		td_(";;;");		
		if ($temp_kategoria=='2') echo "<font color=red>";
		if ($temp_kategoria=='6') echo "<font color=red>";
		echo "<b>$kat_opis</b> -> ";	
		if ($temp_kategoria=='2') echo "</font>";
		if ($temp_kategoria=='6') echo "</font>";
		*/
		tr_();	td_("150;r;Podkategoria;"); 	_td(); 	
		td_(";;;");
		if ($temp_kategoria=='2') echo "<font color=red>";
		if ($temp_kategoria=='6') echo "<font color=red>";
		echo "<b>$podkat_opis</b>";	
		if ($temp_kategoria=='2') echo "</font>";
		if ($temp_kategoria=='6') echo "</font>";
		_td(); 	_tr();
		
		
		tr_();	td_("150;r;Podkategoria (poziom 2);"); _td(); 	
		td_(";;;");		
			if ($temp_podkategoria2=='') $temp_podkategoria2='Brak';
			if ($temp_kategoria=='2') echo "<font color=red>";
			if ($temp_kategoria=='6') echo "<font color=red>";
				echo "<b>$temp_podkategoria2</b>";
			if ($temp_kategoria=='2') echo "</font>";
			if ($temp_kategoria=='6') echo "</font>";
		_td(); 	_tr();
		
/*		tr_();	td_("150;r;Podkategoria;"); _td(); 	
		td_(";;;");		
		if ($temp_kategoria=='2') echo "<font color=red>";
		echo "<b>$podkat_opis</b>";	
		if ($temp_kategoria=='2') echo "</font>";
		_td(); 	_tr();
*/
/*		tr_();	td_("150;r;Priorytet;"); 	_td(); 	
		td_(";;;");		
		echo "<b>$priorytet_opis</b>";	
		_td(); 	_tr();
*/

	// informacje o czasach zgodnych z umową - POCZĄTEK
		$__zgl_data_r 	= $temp_data_roz;
		$__zgl_data_z	= $temp_data_zak;
		$__temp_zgl_E2P	= $temp_zgl_E2P;
		$__color		= TRUE;				// czy mają być na czerwono wyróżnione daty wyliczone przez system
		
		include("hd_o_zgloszenia_SLA_info.php");
		
	// informacje o czasach zgodnych z umową - KONIEC
	
	tr_();	
		td_("150;r;Aktualny status;"); 		
		_td(); 	td_(";;;");	
/*
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
	*/				
			echo "<b>$status_opis</b>";
			
				$r4 = mysql_query("SELECT zgl_szcz_czas_rozpoczecia_kroku FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$temp_id') ORDER BY zgl_szcz_nr_kroku  DESC LIMIT 1", $conn_hd) or die($k_b);
				list($data_ostatniego_statusu)=mysql_fetch_array($r4);
			
			echo " | zmieniono: ".substr($data_ostatniego_statusu,0,16)."";
			
			if ($temp_status==4) {
				// sprawdz czy wysłano ofertę (jeżeli status = 4)
		/*		$r4 = mysql_query("SELECT zgl_szcz_oferta_wyslana,zgl_szcz_id FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$temp_id') and (zgl_szcz_status=4) and (zgl_szcz_widoczne=1) and (belongs_to=$es_filia) LIMIT 1", $conn_hd) or die($k_b);
				//echo "SELECT zgl_szcz_oferta_wyslana,zgl_szcz_id FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$temp_id') and (zgl_szcz_status=4) and (zgl_szcz_widoczne=1) and (belongs_to=$es_filia) LIMIT 1";
				
				list($oferta_wyslana,$temp_zglszczid)=mysql_fetch_array($r4);
				
				$r4 = mysql_query("SELECT oferta_id,oferta_data,oferta_numer,oferta_uwagi FROM $dbname_hd.hd_zgloszenie_oferty WHERE (oferta_zgl_szcz_id='$temp_zglszczid') and (oferta_widoczna=1) and (belongs_to=$es_filia) LIMIT 1", $conn_hd) or die($k_b);
				list($Oid,$Odata,$Onumer,$Ouwagi)=mysql_fetch_array($r4);
				//echo ">>>>> $temp_zglszczid";
				
				if ($oferta_wyslana==1) {
					echo "<br /><br />Oferta została wysłana do klienta w dniu <b>$Odata</b>&nbsp;<a href=# class=hd_button  onClick=\"newWindow_r(600,200,'hd_o_zgloszenia_szcz_oferty.php?oid=$Oid&onr=$Onumer&zglszczid=$temp_zglszczid'); return false;\" />&nbsp;Pokaż szczegóły&nbsp;</a>";	
				} else { echo "<br /><br />Oferta nie została wysłana do klienta"; 
					echo "&nbsp;<a id=OfertaWyslanaButton class=hd_button href=# onClick=\"newWindow(500,300,'hd_o_zgloszenia_pwo.php?id=$temp_zglszczid'); return false; \" />&nbsp;Potwierdź wysłanie oferty do klienta&nbsp;</a><br /><br />";
				}
			*/
			}

			if ($temp_status==5) {			
				// sprawdz czy wysłano zamówienie (jeżeli status = 5)

		/*		$r4 = mysql_query("SELECT zgl_szcz_zamowienie_wyslane,zgl_szcz_id FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$temp_id') and (zgl_szcz_status=5) and (zgl_szcz_widoczne=1) and (belongs_to=$es_filia) LIMIT 1", $conn_hd) or die($k_b);
				list($zamowienie_wyslane,$temp_zglszczid)=mysql_fetch_array($r4);
				
				//echo "SELECT zam_id,oferta_data,zam_numer,zam_uwagi FROM $dbname_hd.hd_zgloszenie_zamowienia WHERE (zam_zgl_szcz_id='$temp_zglszczid') and (zam_widoczne=1) and (belongs_to=$es_filia) LIMIT 1";
				
				$r4 = mysql_query("SELECT zam_id,zam_data,zam_numer,zam_uwagi FROM $dbname_hd.hd_zgloszenie_zamowienia WHERE (zam_zgl_szcz_id='$temp_zglszczid') and (zam_widoczne=1) and (belongs_to=$es_filia) LIMIT 1", $conn_hd) or die($k_b);
				list($Zid,$Zdata,$Znumer,$Zuwagi)=mysql_fetch_array($r4);
				
				if ($zamowienie_wyslane==1) {
					echo "<br /><br />Zamówienie zostało wysłane do realizacji w dniu <b>$Zdata</b>&nbsp;<a href=# class=hd_button  onClick=\"newWindow_r(600,200,'hd_o_zgloszenia_szcz_zam.php?zid=$Zid&znr=$Znumer&zglszczid=$temp_zglszczid'); return false;\" />&nbsp;Pokaż szczegóły&nbsp;</a>";
				} else { echo "<br /><br />Zamówienie nie zostało wysłane do realizacji"; 
				
					echo "&nbsp;<a href=# id=ZamowienieWyslaneButton class=hd_button  onClick=\"newWindow(500,300,'hd_o_zgloszenia_pwz.php?id=$temp_zglszczid'); return false; \" >&nbsp;Potwierdź wysłanie zamówienia do realizacji&nbsp;</a><br /><br />";
				
				}
			*/
			
			}
			
			if (($temp_status=='3A') && (($temp_kategoria=='2') || ($temp_kategoria=='6')) && ($temp_podkategoria=='0')) 
				echo " | Nr zgłoszenia w Orange: <b>$temp_nrawarii</b>";
			
			if ($temp_status=='9')
				if ($temp_opz!='')
					echo " | Osoba ze strony Poczty potw. zamknięcie: <b>$temp_opz</b>";
			
			// sprawdz czy było przesunięcie terminu rozpoczęcia realizacji zgłoszenia
			$r33 = mysql_query("SELECT zgl_szcz_przesuniety_termin_rozpoczecia,zgl_szcz_przesuniecie_data,zgl_szcz_przesuniecie_osoba FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$temp_id') and (zgl_szcz_widoczne=1) and (belongs_to=$es_filia) ORDER BY zgl_szcz_nr_kroku DESC LIMIT 1", $conn_hd) or die($k_b);
			list($przesuniety,$przesuniety_data,$przesuniety_osoba)=mysql_fetch_array($r33);
			
			if ($przesuniety=='1') {
				if ($temp_kategoria=='2') {
					echo "<br /><font color=red>Przesunięty termin rozpoczęcia: <b>".substr($przesuniety_data,0,16)."</b> | Ustalono z: <b>$przesuniety_osoba</b></font>";
				}
				if ($temp_kategoria=='6') {
					echo "<br /><font color=red>Przesunięty termin rozpoczęcia: <b>".substr($przesuniety_data,0,16)."</b>";
					//echo "<b>".substr($przesuniety_data,0,16)."</b>";
					echo " | Ustalono z: <b>$przesuniety_osoba</b></font>";
					
					//	if ($temp_zgl_komorka_working_hours!='') echo "<input type=button class=buttons title=\"Obowiązujące w momencie rejestracji zgłoszenia godziny pracy komórki\" onclick=\"newWindow(500,300,'hd_p_pokaz_godziny.php?komorkanazwa=".urlencode($temp_komorka)."&wa=".urlencode($temp_zgl_komorka_working_hours)."');return false;\" value='Zapisane w zgłoszeniu godziny pracy'/>";
					
				}
			}
				
			if (($temp_status=='3A') && (($temp_kategoria=='2') || ($temp_kategoria=='6') || ($temp_kategoria=='3')) && (($temp_podkategoria=='2') || ($temp_podkategoria=='5') || ($temp_podkategoria=='6') || ($temp_podkategoria=='7'))) {
				$r33 = mysql_query("SELECT zgl_szcz_gdansk_nr FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$temp_id') and (zgl_szcz_widoczne=1) and (belongs_to=$es_filia) ORDER BY zgl_szcz_nr_kroku DESC LIMIT 1", $conn_hd) or die($k_b);
				list($NrZglGdansk)=mysql_fetch_array($r33);			
				if ($NrZglGdansk!='') echo " | Nr incydenntu w Gdańsku: <b>$NrZglGdansk</b>";
			}
			
		_td(); 	_tr();

		$r55 = mysql_query("SELECT hdnp_zdiagnozowany, hdnp_oferta_wyslana, hdnp_akceptacja_kosztow, hdnp_zamowienie_wyslane, hdnp_zamowienie_zrealizowane, hdnp_gotowe_do_oddania, hdnp_naprawa_zakonczona FROM $dbname_hd.hd_naprawy_powiazane WHERE (hdnp_zgl_id='$_GET[nr]') ORDER BY hdnp_id DESC LIMIT 1", $conn_hd) or die($k_b);
		list($temp_zdiagnozowany, $temp_oferta_wyslana, $temp_akceptacja_kosztow, $temp_zamowienie_wyslane, $temp_zamowienie_zrealizowane, $temp_gotowe_do_oddania, $temp_naprawa_zakonczona)=mysql_fetch_array($r55);
		
//		echo "$temp_zdiagnozowany, $temp_oferta_wyslana, $temp_akceptacja_kosztow, $temp_zamowienie_wyslane, $temp_zamowienie_zrealizowane, $temp_gotowe_do_oddania, $temp_naprawa_zakonczona";
		
		if ($temp_zdiagnozowany=='') $temp_zdiagnozowany='9';
		if ($temp_oferta_wyslana=='') $temp_oferta_wyslana='9';
		if ($temp_akceptacja_kosztow=='') $temp_akceptacja_kosztow='9';
		if ($temp_zamowienie_wyslane=='') $temp_zamowienie_wyslane='9';
		if ($temp_zamowienie_zrealizowane=='') $temp_zamowienie_zrealizowane='9';
		if ($temp_gotowe_do_oddania=='') $temp_gotowe_do_oddania='9';
		
		if (($temp_zdiagnozowany!='9') || ($temp_oferta_wyslana!='9') || ($temp_akceptacja_kosztow!='9') || ($temp_zamowienie_wyslane!='9') || ($temp_zamowienie_zrealizowane!='9') || ($temp_gotowe_do_oddania!='9')) {			
		
		//tbl_empty_row(2);
			tr_();
				echo "<td class=righttop>Aktualne stany pośrednie";
				echo "</td>";
				td_(";;;");	
		
					echo "<table style='width:auto'>";
					if ($temp_zdiagnozowany!='9') {
						echo "<tr>";
						echo "<td style='text-align:right;'>";
							echo "zdiagnozowany";
							echo "</td><td style='text-align:center;'>";
							switch ($temp_zdiagnozowany) {
								case 0 : echo "<font color=red>NIE</font>"; break;
								case 1 : echo "<font color=green>TAK</font>"; break; 
								default : echo "<font color=grey>-</font>"; break; 
							}			
							echo "</td>";
							echo "<td></td>";
						echo "</tr>";
					}
					if ($temp_oferta_wyslana!='9') {
						echo "<tr>";
						echo "<td style='text-align:right;'>";
							echo "oferta wysłana";
							echo "</td><td style='text-align:center;'>";
							switch ($temp_oferta_wyslana) {
								case 0 : echo "<font color=red>NIE</font>"; break;
								case 1 : echo "<font color=green>TAK</font>"; break; 
								default : echo "<font color=grey>-</font>"; break; 
							}
							echo "</td><td>";
							
								if ($temp_oferta_wyslana==1) {
									// sprawdz czy wysłano ofertę (jeżeli status = 4)
									$r4 = mysql_query("SELECT zgl_szcz_oferta_wyslana,zgl_szcz_id FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$_REQUEST[nr]') and (zgl_szcz_status=4) and (zgl_szcz_widoczne=1) and (belongs_to=$es_filia) LIMIT 1", $conn_hd) or die($k_b);
									//echo "SELECT zgl_szcz_oferta_wyslana,zgl_szcz_id FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$temp_id') and (zgl_szcz_status=4) and (zgl_szcz_widoczne=1) and (belongs_to=$es_filia) LIMIT 1";
									
									list($oferta_wyslana,$temp_zglszczid)=mysql_fetch_array($r4);
									
									if ($temp_zglszczid=='') {
										$oferta_wyslana = 0;
										
										$r4 = mysql_query("SELECT zgl_szcz_id FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$_REQUEST[nr]') and  (zgl_szcz_widoczne=1) and (belongs_to=$es_filia) ORDER BY zgl_szcz_id DESC LIMIT 1", $conn_hd) or die($k_b);
										list($temp_zglszczid)=mysql_fetch_array($r4);
									}
									
									if ($oferta_wyslana=='') {
										$r4 = mysql_query("SELECT zgl_szcz_oferta_wyslana FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$_REQUEST[nr]') and (zgl_szcz_oferta_wyslana=1) and (zgl_szcz_widoczne=1) and (belongs_to=$es_filia) LIMIT 1", $conn_hd) or die($k_b);										
										list($oferta_wyslana)=mysql_fetch_array($r4);
										
									}
									
									//echo $oferta_wyslana." ".$temp_zglszczid;
									$r4 = mysql_query("SELECT oferta_id,oferta_data,oferta_numer,oferta_uwagi FROM $dbname_hd.hd_zgloszenie_oferty WHERE (oferta_zgl_szcz_id='$temp_zglszczid') and (oferta_widoczna=1) and (belongs_to=$es_filia) LIMIT 1", $conn_hd) or die($k_b);
									list($Oid,$Odata,$Onumer,$Ouwagi)=mysql_fetch_array($r4);
									//echo ">>>>> $temp_zglszczid";
									if ($Odata=='') $Odata='-';
									
									if ($oferta_wyslana==1) {
										echo "(<b>$Odata</b>)&nbsp;<a href=# class='buttons normalfont'  onClick=\"newWindow_r(600,200,'hd_o_zgloszenia_szcz_oferty.php?oid=$Oid&onr=$Onumer&zglszczid=$temp_zglszczid'); return false;\" />&nbsp;Pokaż szczegóły&nbsp;</a>";	
									} else { echo ""; 
										echo "<a id=OfertaWyslanaButton class='buttons normalfont' href=# onClick=\"newWindow(500,300,'hd_o_zgloszenia_pwo.php?id=$temp_zglszczid'); return false; \" />&nbsp;Potwierdź wysłanie oferty do klienta&nbsp;</a><br /><br />";
									}
								}
				
							echo "</td>";
						echo "</tr>";
					}
					if ($temp_akceptacja_kosztow!='9') {
						echo "<tr>";					
						echo "<td style='text-align:right;'>";
							echo "akceptacja kosztów";
							echo "</td><td style='text-align:center;'>";
							switch ($temp_akceptacja_kosztow) {
								case 0 : echo "<font color=red>NIE</font>"; break;
								case 1 : echo "<font color=green>TAK</font>"; break; 
								default : echo "<font color=grey>-</font>"; break; 
							}			
							echo "</td>";
						echo "</tr>";
					}
					if ($temp_zamowienie_wyslane!='9') {					
						echo "<tr>";
						echo "<td style='text-align:right;'>";
							echo "zamówienie wysłane";
							echo "</td><td style='text-align:center;'>";
							switch ($temp_zamowienie_wyslane) {
								case 0 : echo "<font color=red>NIE</font>"; break;
								case 1 : echo "<font color=green>TAK</font>"; break; 
								default : echo "<font color=grey>-</font>"; break; 
							}
							
							echo "</td><td>";
							
								if ($temp_zamowienie_wyslane==1) {			
									// sprawdz czy wysłano zamówienie (jeżeli status = 5)

									$r4 = mysql_query("SELECT zgl_szcz_zamowienie_wyslane,zgl_szcz_id FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$_REQUEST[nr]') and (zgl_szcz_status=5) and (zgl_szcz_widoczne=1) and (belongs_to=$es_filia) LIMIT 1", $conn_hd) or die($k_b);
									list($zamowienie_wyslane,$temp_zglszczid)=mysql_fetch_array($r4);
									
									if ($temp_zglszczid=='') {
										$zamowienie_wyslane = 0;
										
										$r4 = mysql_query("SELECT zgl_szcz_id FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$_REQUEST[nr]') and  (zgl_szcz_widoczne=1) and (belongs_to=$es_filia) ORDER BY zgl_szcz_id DESC LIMIT 1", $conn_hd) or die($k_b);
										list($temp_zglszczid)=mysql_fetch_array($r4);
									}
									
									if ($zamowienie_wyslane=='') {
										$r4 = mysql_query("SELECT zgl_szcz_zamowienie_wyslane FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$_REQUEST[nr]') and (zgl_szcz_zamowienie_wyslane=1) and (zgl_szcz_widoczne=1) and (belongs_to=$es_filia) LIMIT 1", $conn_hd) or die($k_b);										
										list($zamowienie_wyslane)=mysql_fetch_array($r4);
										
									}
									
									//echo "SELECT zgl_szcz_zamowienie_wyslane,zgl_szcz_id FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$_REQUEST[nr]') and (zgl_szcz_status=5) and (zgl_szcz_widoczne=1) and (belongs_to=$es_filia) LIMIT 1";
									
									$r4 = mysql_query("SELECT zam_id,zam_data,zam_numer,zam_uwagi FROM $dbname_hd.hd_zgloszenie_zamowienia WHERE (zam_zgl_szcz_id='$temp_zglszczid') and (zam_widoczne=1) and (belongs_to=$es_filia) LIMIT 1", $conn_hd) or die($k_b);
									list($Zid,$Zdata,$Znumer,$Zuwagi)=mysql_fetch_array($r4);
									
									if ($zamowienie_wyslane==1) {
										echo "(<b>$Zdata</b>)&nbsp;<a href=# class='buttons normalfont'   onClick=\"newWindow_r(600,200,'hd_o_zgloszenia_szcz_zam.php?zid=$Zid&znr=$Znumer&zglszczid=$temp_zglszczid'); return false;\" />&nbsp;Pokaż szczegóły&nbsp;</a>";
									} else { echo ""; 
									
										echo "<a href=# id=ZamowienieWyslaneButton class='buttons normalfont'  onClick=\"newWindow(500,300,'hd_o_zgloszenia_pwz.php?id=$temp_zglszczid');  return false; \" >&nbsp;Potwierdź wysłanie zamówienia do realizacji&nbsp;</a><br /><br />";
									
									}
								}
							
							echo "</td>";
						echo "</tr>";
					}
					if ($temp_zamowienie_zrealizowane!='9') {					
						echo "<tr>";
						echo "<td style='text-align:right;'>";
							echo "zamówienie zrealizowane";
							echo "</td><td style='text-align:center;'>";
							switch ($temp_zamowienie_zrealizowane) {
								case 0 : echo "<font color=red>NIE</font>"; break;
								case 1 : echo "<font color=green>TAK</font>"; break; 
								default : echo "<font color=grey>-</font>"; break; 
							}			
							echo "</td>";
						echo "</tr>";
					}
					if ($temp_gotowe_do_oddania!='9') {						
						echo "<tr>";					
						echo "<td style='text-align:right;'>";
							echo "gotowe do oddania";
							echo "</td><td style='text-align:center;'>";
							switch ($temp_gotowe_do_oddania) {
								case 0 : echo "<font color=red>NIE</font>"; break;
								case 1 : echo "<font color=green>TAK</font>"; break; 
								default : echo "<font color=grey>-</font>"; break; 
							}			
							echo "</td>";
						echo "</tr>";
					}
					echo "</table>";
					
					echo "<input style='display:inline;' type=button class=buttons onclick=\"newWindow_r(800,600,'hd_e_zgloszenie_sp.php?id=$_GET[id]&nr=$_GET[nr]');\" value='Edytuj stany pośrednie' >";
				}
				
			_td(); 
		_tr();
	
		tr_();	td_("150;r;Przypisane do;"); 		_td(); 	td_(";;;");		
		
		$r1 = mysql_query("SELECT zgl_szcz_osoba_wykonujaca_krok FROM $dbname_hd.hd_zgloszenie_szcz WHERE (belongs_to) and (zgl_szcz_widoczne=1) and (zgl_szcz_zgl_id='$temp_nr') ORDER BY zgl_szcz_nr_kroku DESC LIMIT 1", $conn_hd) or die($k_b);
		list($temp_osoba_przypisana)=mysql_fetch_array($r1);
		
		$temp_osoba_przypisana_opis='';
		if ($temp_osoba_przypisana=='') $temp_osoba_przypisana_opis = "nie przypisane";
		
		echo "<b>";
		echo "<span id=OsobaPrzypisana style='color:red'>";
			echo "$temp_osoba_przypisana$temp_osoba_przypisana_opis";
			
			if ($temp_status=='7') echo " (osoba wykonująca ostatnią zmianę statusu)";
		echo "</span>";
		echo "</b>";	
		
		if ($_GET[action]=='obsluga') {
			
			if ($temp_status!=9) {
				// sprawdź czy zgłoszenie jest wolne
				if (($temp_osoba_przypisana=='') || ($temp_osoba_przypisana!=$currentuser)) {
				
					if ($pozwol_na_modikacje) {
						echo "&nbsp;<input id=pds_button type=button class=buttons value='Przypisz do siebie' onClick=\"if (confirm('Czy na pewno chcesz przypisać to zgłoszenie do siebie ?')) { $('#OsobaPrzypisana').load('hd_o_zgloszenia_pds.php?id=".$temp_id."&nr=".$temp_nr."&osoba=".urlencode($currentuser)."&randval='+ Math.random()); } \" />";
					}
				}
			}
		}
		_td(); 	_tr();
	
	if (($temp_kategoria=='2') || ($temp_kategoria=='6')) {
		tr_();
		if ($temp_czy_rozwiazany==1) {
			td_("150;rt;<font color=green>Problem rozwiązany</font>"); 		_td(); 	
		} else {
			td_("150;rt;Problem rozwiązany"); 		_td(); 	
		}
			td_(";;;");	
				if ($temp_czy_rozwiazany==0) echo "<b>NIE</b>";
				if ($temp_czy_rozwiazany==1) echo "<font color=green><b>TAK</b></font>";
			_td();
		_tr();
		//tbl_empty_row(1);
	}
	
	if (($temp_kategoria!=1) && ($temp_status!=9)) {
		tbl_empty_row(2);
		
		if ($temp_czy_ww==1) {
			tr_();	td_("150;rt;<font color=blue>Wymagany wyjazd</font>;"); 		_td(); 	td_(";;;");		
			echo "<span id=execute>";
				echo "<font color=blue><b>TAK</b> | Ustawione w dniu <b>".substr($temp_czy_ww_data,0,16)."</b> przez <b>$temp_czy_ww_osoba</b></font>";
			echo "</span>";
			
			if ($pozwol_na_modikacje) {
				echo "<span style='float:right'><input type=button class=buttons value='Anuluj wymagany wyjazd' onClick=\"if (confirm('Czy napewno chcesz anulować ustawiony wymagany wyjazd ?')) newWindow(500,50,'hd_o_zgloszenia_ww.php?zgl_nr=".$_REQUEST[nr]."&set=0'); \"></span>";
			}
			_td(); 	_tr();	
		} else {
			tr_();	td_("150;rt;<font color=blue>Wymagany wyjazd</font>;"); 		_td(); 	td_(";;;");		
			echo "<span id=execute>";
				//echo "<font color=blue><b>NIE</b></font>";
			echo "</span>";
			
			if ($pozwol_na_modikacje) {
				echo "<span style=''><input type=button class=buttons style='color:blue;' value='Ustaw wymagany wyjazd' onClick=\"if (confirm('Czy napewno chcesz ustawić wymagany wyjazd ?')) newWindow(500,50,'hd_o_zgloszenia_ww.php?zgl_nr=".$_REQUEST[nr]."&set=1&ww_data=".urlencode(Date('Y-m-d H:i:s'))."&ww_osoba=".urlencode($currentuser)."'); \"></span>";
			}
			_td(); 	_tr();			
		}
	}
		
	if (($temp_czas!='') || ($temp_km!='')) {
		//tbl_empty_row(1);
		tr_(); 
		echo "<td colspan=2>";
		oddziel();
		echo "</td>";
		_tr();
		tr_();
		echo "<td class=righttop><b><u>Sumarycznie:</u></b></td><td></td>";
		_tr();
		if ($temp_czas!='') {
			tr_();	td_("150;r;Łączny czas wykonywania;"); 		_td(); 	td_(";;;");		echo "<b>$temp_czas minut</b>";	_td(); 	_tr();
		}
		if ($CzyBylWyjazd==true) {
			tr_();	td_("150;r;Łączny czas przejadów;"); 		_td(); 	td_(";;;");		echo "<b>$PrzejazCzasLacznie minut</b>";	_td(); 	_tr();
		}
	
		if (($temp_czas+$PrzejazCzasLacznie)>0) {
			tr_();	echo "<td colspan=1><hr />"; echo "</td><td></td>"; 	_tr();
			tr_();	echo "<td class=right><b>Łącznie poświecono</b></td><td><b>".($temp_czas+$PrzejazCzasLacznie)." minut</b></td>"; 	_tr();
			tbl_empty_row(2);
		}
		if (($temp_km!='') && ($temp_km!='0')) {
		//	tbl_empty_row(2);
				tr_();	td_("150;r;Łączna ilość kilometrów;"); 	_td(); 	td_(";;;");		echo "<b>$temp_km km</b>";	_td(); 	_tr();
				//	tr_();	td_("150;rt;Trasa wyjazdu;"); 			_td(); 	td_(";;;");		echo "<textarea cols=96 rows=2  style='border:0px;background-color:transparent;font-family:tahoma;font-size:11px;font-weight:bold;' readonly tabindex=-1>$temp_trasa</textarea>";	_td(); 	_tr();
					
			//tbl_empty_row(2);
		}
	}
	tbl_empty_row(1);

	if ($temp_status=='9') {
		tr_();	td_("150;r;Zgłoszenie zasadne;"); 		_td(); 	td_(";;;");		if ($temp_zz=='1') { echo "<font color=green><b>TAK</b></font>"; } else { echo "<font color=red><b>NIE</b></font>"; }	_td(); 	_tr();
		tbl_empty_row(2);
	}
	
	if ($temp_czy_synchronizowac==1) {
		tr_();	td_("150;r;Widoczne dla Poczty;"); 		_td(); 	td_(";;;");		echo "<font color=green><b>TAK</b></font>";  _td(); 	_tr();
	} else {
		tr_();	td_("150;r;Widoczne dla Poczty;"); 		_td(); 	td_(";;;");		echo "<font color=red><b>NIE</b></font>";  _td(); 	_tr();
	}
	tbl_empty_row(1);
	
	endtable();

	list($child_zgl)=mysql_fetch_array(mysql_query("SELECT zgl_nr FROM hd_zgloszenie WHERE (zgl_kontynuacja_zgloszenia_numer=$_REQUEST[nr]) LIMIT 1"));
	
	if ($child_zgl!=0) {
		echo "<h3 class=h3powiazane><center>Na bazie zgłoszenia numer <b>$_REQUEST[nr]</b> utworzono zgłoszenie numer <b>".$child_zgl."</b>";
		echo "&nbsp;&nbsp;<input class=buttons type=button onClick=\"self.location.href='hd_o_zgloszenia.php?action=obsluga&nr=$child_zgl&id=$child_zgl'\" value=' Przejdź do obsługi zgłoszenia nr ".$child_zgl." ' />";
		echo "</center></h3>";
	}

	if (($temp_status==9) && ($funkcja_weryfikacji_zgloszen_przez_kierownikow==true) && ($temp_zgl_spr_data=='0000-00-00 00:00:00')) {
		echo "<h2 style='font-weight:normal;'><center><font color=red>Zgłoszenie nie zostało sprawdzone przez przełożonego.</font> ";
			if ((($kierownik_nr==$es_nr) || ($is_dyrektor==1))) {
				echo " <input type=button class=buttons style='color:green' value='*Potwierdź sprawdzenie*' onClick=\"newWindow_r($dialog_window_x,$dialog_window_y,'hd_zgloszenie_potwierdz.php?potwierdz=1&id=$temp_id&nr=$temp_nr&sprdata=".Date("Y-m-d H:i:s")."&spruser=".urlencode($currentuser)."');\">";
			}
		echo "</center></h2>";
	}
	
	if (($temp_status==9) && ((($kierownik_nr==$es_nr) || ($is_dyrektor==1)) && ($funkcja_weryfikacji_zgloszen_przez_kierownikow==true)) && ($temp_zgl_spr_data!='0000-00-00 00:00:00')) {
		echo "<h3 style='font-weight:normal;'><center><input class=imgoption type=image src=img/zgl_checked.gif>&nbsp;<font color=black>Zgłoszenie zostało sprawdzone przez <b>$temp_zgl_spr_osoba</b> w dniu <b>".substr($temp_zgl_spr_data,0,16)."</b>.</font> ";
		if ((($kierownik_nr==$es_nr) || ($is_dyrektor==1))) {
			echo "<input type=button class=buttons style='color:red' value='*Anuluj sprawdzenie*' onClick=\"newWindow_r($dialog_window_x,$dialog_window_y,'hd_zgloszenie_potwierdz.php?potwierdz=0&id=$temp_id&nr=$temp_nr&sprdata=".Date("Y-m-d H:i:s")."&spruser=".urlencode($currentuser)."'); return false;\">";
		}
		echo "</center></h3>";
	}
	
	$KierownikId = $kierownik_nr;	
	
	if (($es_nr!=$KierownikId) && ($is_dyrektor!=1)) {
		if ((substr($temp_data,0,7)==$edycja_dla_wszystkich_dla_okresu)) {
			echo "&nbsp;<input type=button class=buttons style='color:green' value='Edytuj kategorie zgłoszenia' onClick=\"newWindow(800,600,'hd_e_zgloszenie_new.php?range=kat&id=$temp_id&nr=$temp_nr&tk=$temp_kategoria&tpk=$temp_podkategoria&tp=$temp_priorytet&unr=$temp_unique');\">";	
		}
	}
	
	if ($_GET[action]=='obsluga') {
		startbuttonsarea('right');
		oddziel();
		echo "<form>";
		// oddziel();
		// opcje dla kierownika - START			
		
		echo "<span style=float:left>";

			if (($es_nr==$KierownikId) || ($is_dyrektor==1)) {
				
				echo "<input type=button class=buttons style='color:green' value='Edytuj kategorię' onClick=\"newWindow(800,600,'hd_e_zgloszenie_new.php?range=kat&id=$temp_id&nr=$temp_nr&tk=$temp_kategoria&tpk=$temp_podkategoria&tp=$temp_priorytet&unr=$temp_unique');\">";	
				
				echo "<input type=button class=buttons style='color:green' value='Edytuj zgłoszenie' onClick=\"newWindow(800,600,'hd_e_zgloszenie_new.php?id=$temp_id&nr=$temp_nr&tk=$temp_kategoria&tpk=$temp_podkategoria&tp=$temp_priorytet&unr=$temp_unique');\">";
			} else {	
				echo "<input type=button class=buttons style='color:green;' title='Edytuj nr HADIM' onClick=\"newWindow_r(600,200,'hd_e_zgloszenie_hadim.php?id=$temp_id&nr=$temp_nr&tk=$temp_kategoria&tpk=$temp_podkategoria&tp=$temp_priorytet&unr=$temp_unique'); return false;\" value='Edytuj nr HADIM'/>";
			}
			
		$accessLevels = array("0","1","9");		
		if (array_search($es_prawa, $accessLevels)>-1) {				

			//if (($temp_status==1) || ($temp_status==2) || ($temp_status==7)) echo "<input type=button class=buttons value='Przypisz zgłoszenie do osoby' onClick=\"newWindow(500,150,'hd_o_zgloszenia_pdo.php?id=$temp_id&nr=$temp_nr');\">";
			
			if (($temp_status!=9) && ($temp_status!=1)) {
				//echo "Funkcje dla kierownika : ";
				if ($pozwol_na_modikacje) {
					echo "<input type=button class=buttons style='color:blue' value='Przypisz do osoby' onClick=\"newWindow(700,400,'hd_o_zgloszenia_pdo.php?id=$temp_id&nr=$temp_nr&osoba=".urlencode($temp_osoba_przypisana)."');\">";
				}
			}
			
			$accessLevels2 = array("9");
			if (array_search($es_prawa, $accessLevels2)>-1) {
				if ($pozwol_na_modikacje) {
					if ($temp_status==1) echo "&nbsp;<input type=button class=buttons value='Przypisz do osoby' onClick=\"newWindow(600,400,'hd_o_zgloszenia_pdo.php?id=$temp_id&nr=$temp_nr');\">";
				}
			}
			
			echo "<input type=button class=buttons title=' Pokaż kroki realizacji zgłoszenia nr $temp_nr w nowym oknie ' onClick=\"newWindow_r(".$_COOKIE[max_x].",".$_COOKIE[max_y].",'hd_p_zgloszenie_kroki.php?newwindow=1&nr=$temp_nr&id=$temp_id'); return false;\" value=\"Kroki realizacji\">";

			if ($temp_status==9) echo "<input class=buttons type=button onClick=\"window.location.href='hd_d_zgloszenie.php?stage=1';\" value='Nowe zgłoszenie' />";			

			//echo "<input type=button class=buttons_hd value='Ukryj zgłoszenie'>";
			
			
		
		}
		
		echo "</span>";
	// opcje dla kierownika - STOP
		
		$r1 = mysql_query("SELECT zgl_szcz_osoba_wykonujaca_krok FROM $dbname_hd.hd_zgloszenie_szcz WHERE (belongs_to) and (zgl_szcz_widoczne=1) and (zgl_szcz_zgl_id='$temp_nr') ORDER BY zgl_szcz_nr_kroku DESC LIMIT 1", $conn_hd) or die($k_b);
		list($temp_osoba_przypisana)=mysql_fetch_array($r1);
	
		if (($temp_status==1) || ($temp_status==7)) {
			
			// sprawdź czy zgłoszenie jest wolne
			if ($temp_osoba_przypisana=='') {
				//echo "&nbsp;<input id=pds_button1 type=button class=buttons value='Przypisz do siebie' onClick=\"if (confirm('Czy na pewno chcesz przypisać to zgłoszenie do siebie ?')) { $('#OsobaPrzypisana').load('hd_o_zgloszenia_pds.php?id=".$temp_id."&nr=".$temp_nr."&osoba=".urlencode($currentuser)."&randval='+ Math.random()); } \" />";
			}
		}
		
		
		$accessLevels = array("9");
		if (($temp_status!=9)) {
		
			//if (($move_naprawa==true) && ($mnwif!=$bt)) {

				if ($pozwol_na_modikacje) {
				
					echo "<input type=button id=ZmienStatus name=ZmienStatus class=buttons style='font-weight:bold;' value='Zmień status' onClick=\"ClearCookie('czy_rozwiazane_$_REQUEST[nr]'); newWindow_r(800,600,'hd_o_zgloszenia_zs.php?id=$temp_id&unr=$temp_unikalny_nr&nr=$temp_nr&ts=$temp_status&tk=$temp_kategoria&tpk=$temp_podkategoria&zgoda=9&komorka=".urlencode($temp_komorka)."&osoba=".urlencode($temp_osoba)."&zgl_s=$_GET[zgl_s]&rozwiazany=$temp_czy_rozwiazany&ww=$temp_czy_ww'); \" />";
					
				}
			//}
			
		}
		
		//$ListaStatusow = str_replace("'","",str_replace(",","-",$ListaStatusow));
		//$ListaStatusow = str_replace(",","-",$ListaStatusow);
		//echo ">".$ListaStatusow."<";
		
		echo "<input type=button class=buttons value='Zamknij' onClick=\"";
			if ($_REQUEST[donotreloadopener]!='1') {
				if (($_SESSION['refresh_o_zgloszenia_'.$_REQUEST[nr].'']==1)) echo "if (opener) opener.location.reload(true);";
			} else {
				echo "if (opener) opener.document.getElementById('save$_REQUEST[nr]').style.display='';";
			}
		echo " self.close();\">";		
		
		//echo "<input type=button class=buttons value='EEEEE' onClick=\"newWindow(600,50,'hd_o_zgloszenia_wylicz_czasy.php?id=$temp_id&nr=$temp_id'); \" />";
		
		echo "</form>";
	
		endbuttonsarea();
		if (($_SESSION['refresh_o_zgloszenia_'.$_REQUEST[nr].'']==1) && ($_REQUEST[newwindow]!=1)) unset($_SESSION['refresh_o_zgloszenia_'.$_REQUEST[nr].'']);
	} else {	
		startbuttonsarea('right');
		oddziel();
		echo "<span style='float:left'>";
		echo "<input type=button class=buttons title=' Pokaż kroki realizacji zgłoszenia nr $temp_nr w nowym oknie '  onclick=\"newWindow_r($_COOKIE[max_x],$_COOKIE[max_y],'hd_p_zgloszenie_kroki.php?newwindow=1&nr=$temp_nr&id=$temp_id&viewonly=1');\" value=\"Kroki realizacji\">";
		
		echo "<input type=button class=buttons style='color:green;' title='Edytuj numer HADIM' onClick=\"newWindow_r(600,200,'hd_e_zgloszenie_hadim.php?id=$temp_id&nr=$temp_nr&tk=$temp_kategoria&tpk=$temp_podkategoria&tp=$temp_priorytet&unr=$temp_unique'); return false;\" value='Edytuj nr HADIM'/>";
		
		if ($temp_status==9) echo "<input class=buttons type=button onClick=\"window.location.href='hd_d_zgloszenie.php?stage=1';\" value='Nowe zgłoszenie' />";
		
		echo "</span>";
		
		echo "<input type=button class=buttons value='Zamknij' onClick=\"";
			if ($_REQUEST[donotreloadopener]!='1') {
				if (($_SESSION['refresh_o_zgloszenia_'.$_REQUEST[nr].'']==1)) echo "if (opener) opener.location.reload(true);";
			} else {
				echo "if (opener) opener.document.getElementById('save$_REQUEST[nr]').style.display='';";
			}
		echo " self.close();\">";
		endbuttonsarea();
	}
	//echo ">>>".$_SESSION['refresh_o_zgloszenia_'.$_REQUEST[nr].'']."";
	// wyczyszczenie plików cookie
	
/*	setcookie('nowy_status_'.$temp_nr.'','',time()-3600);
	setcookie('wpisane_wc_'.$temp_nr.'','',time()-3600);
	setcookie('wpisana_dzs_'.$temp_nr.'','',time()-3600);
	setcookie('wpisana_gzs_'.$temp_nr.'','',time()-3600);
	setcookie('wpisane_cw_h_'.$temp_nr.'','',time()-3600);
	setcookie('wpisane_cw_m_'.$temp_nr.'','',time()-3600);
	
	
	setcookie('zdiagnozowany_'.$temp_nr.'','',time()-3600);
	setcookie('oferta_wyslana_'.$temp_nr.'','',time()-3600);
	setcookie('akceptacja_kosztow_'.$temp_nr.'','',time()-3600);
	setcookie('zamowienie_wys_'.$temp_nr.'','',time()-3600);
	setcookie('zamowienie_zreal_'.$temp_nr.'','',time()-3600);
	setcookie('gotowy_do_oddania_'.$temp_nr.'','',time()-3600);
*/

?>
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

<?php if ($wlacz_miganie_ostrzegawcze) { ?>
<script>
	var blink2 = function() { $('.blinking').css("background-color","white").css("color","red"); };
	var blink1 = function() { $('.blinking').css("background-color","red").css("color","white"); };
	$(document).ready(function() { 
		setInterval(blink1, 1000); 
		setInterval(blink2, 1500); 
	});
</script>
<?php } ?>
<?php include('warning_messages_blinking.php'); ?>
<script>HideWaitingMessage();</script>
</body>
</html>