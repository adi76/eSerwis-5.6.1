<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php'); 
?>
<body>
<?php
$_POST=sanitize($_POST);
$sql_e1="UPDATE $dbname.serwis_naprawa SET naprawa_status = '$_POST[stat]', naprawa_osoba_wysylajaca  = '$_POST[ow]', naprawa_data_wysylki = '$_POST[dw]', naprawa_fs_nazwa = '$_POST[tfs]', naprawa_przewidywany_termin_naprawy = '$_POST[ttn] 23:59:59' WHERE naprawa_id='$_POST[uid]' LIMIT 1";		
if (mysql_query($sql_e1, $conn)) { 
	$sql_e = "UPDATE $dbname.serwis_ewidencja SET ewidencja_status = '$_POST[stat]' WHERE (ewidencja_id='$_POST[ew_id]') LIMIT 1";
	$wynik2 = mysql_query($sql_e, $conn) or die($k_b);

	if ($_REQUEST[hdzglid____]!='') {
	
		list($GetBelongsToFromHD)=mysql_fetch_array(mysql_query("SELECT belongs_to FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id=$_REQUEST[hdzglid]) LIMIT 1"));
		
		// dodanie kroku do zgłoszenia 
		if ($_SESSION[dodaj_krok_do_zgloszenia_pow_z_naprawa]!='poprawnie') {

			$zgl_seryjme_unique_nr = Date('YmdHis')."".rand_str();
			$dddd = date("Y-m-d H:i:s");
			$dd = date("Y-m-d H:i:s");
			
			$NowyStatusZgloszenia='3A';
			
			$osoba_przypisana = $currentuser;
			$last_nr=$_REQUEST[hdzglid];
			
			$r3 = mysql_query("SELECT zgl_szcz_nr_kroku FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$_REQUEST[hdzglid]') ORDER BY zgl_szcz_nr_kroku DESC LIMIT 1", $conn_hd) or die($k_b);
			list($last_nr_kroku)=mysql_fetch_array($r3);
			$last_nr_kroku+=1;

			$czas_START_STOP='STOP';

			$d_cw = 0;	// czas wykonywania
			if ($_REQUEST[czas_wykonywania_h]!='') { $h_na_m = (int) $_REQUEST[czas_wykonywania_h]*60; }
			if ($_REQUEST[czas_wykonywania_m]!='') { $m_na_m = (int) $_REQUEST[czas_wykonywania_m]; }
			
			$d_cw=$h_na_m+$m_na_m;

			list($Zdiagnozowany1)=mysql_fetch_array(mysql_query("SELECT zgl_szcz_zdiagnozowany FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$last_nr) ORDER BY zgl_szcz_id DESC LIMIT 1", $conn_hd));
				
			list($oferta_wyslana1)=mysql_fetch_array(mysql_query("SELECT zgl_szcz_oferta_wyslana FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$last_nr) ORDER BY zgl_szcz_id DESC LIMIT 1", $conn_hd));
				
			list($AkceptacjaKosztow1)=mysql_fetch_array(mysql_query("SELECT zgl_szcz_akceptacja_kosztow FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$last_nr) ORDER BY zgl_szcz_id DESC LIMIT 1", $conn_hd));
				
			list($zam_wyslane1)=mysql_fetch_array(mysql_query("SELECT zgl_szcz_zamowienie_wyslane FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$last_nr) ORDER BY zgl_szcz_id DESC LIMIT 1", $conn_hd));
				
			$zam_wyslane = $zam_wyslane1;
			$oferta_wyslana = $oferta_wyslana1;
			$akceptacja_kosztow = $AkceptacjaKosztow1;

			$przejechane_km = $_REQUEST[km];
			if (($_REQUEST[km]==0) || ($_REQUEST[km]=='')) $przejechane_km = 0;

			$awaria_z_przesunieciem=0;
			$Zdiagnozowany = $Zdiagnozowany1;
			$AkceptacjaKosztow = $AkceptacjaKosztow1;
			$wykonane_czynnosci = $_REQUEST[zs_wcz];
			
		if ($_REQUEST[WieleOsobCheck]=='on') {
			// lista dodatkowych osób wykonujących krok
			$DodatkoweOsoby = nl2br2($_REQUEST[lista_osob]);
			//$jeden_dodatkowy = explode(", ", $DodatkoweOsoby);
			$ile_dodatkowych = substr_count($DodatkoweOsoby,', ')-1;
		} else $DodatkoweOsoby = '';
		
			$bylwyjazd=0;
			if ($_REQUEST[PozwolWpisacKm]=='on') $bylwyjazd = 1;
			
			list($czy_rozwiazany)=mysql_fetch_array(mysql_query("SELECT zgl_czy_rozwiazany_problem FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id=$last_nr) LIMIT 1", $conn_hd));
			
			$sql = "INSERT INTO $dbname_hd.hd_zgloszenie_szcz VALUES ('',$last_nr,'$zgl_seryjme_unique_nr',$last_nr_kroku,'$czas_START_STOP',$d_cw,'$_REQUEST[zs_data] $_REQUEST[zs_time]','$NowyStatusZgloszenia','$wykonane_czynnosci','$osoba_przypisana','$DodatkoweOsoby',0,'9','9',$bylwyjazd,'$dddd','$currentuser',1,$przejechane_km,$awaria_z_przesunieciem,'','','9','9','','','$_REQUEST[hdds]',$czy_rozwiazany, 0, $es_filia)";
			//echo "<br />$sql";
			$result = mysql_query($sql, $conn_hd) or die($k_b);
			
			// zaktualizuj zgl_czy_rozwiazany_problem w zgłoszeniu 
			$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_czy_rozwiazany_problem='$czy_rozwiazany' WHERE ((belongs_to='$es_filia') and (zgl_nr='$last_nr')) LIMIT 1";
			$result = mysql_query($sql, $conn_hd) or die($k_b);
					
			// *************************
				$_SESSION[dodaj_krok_do_zgloszenia_pow_z_naprawa]='poprawnie';
			// *************************
		
				// jeżeli był wyjazd (START)
			if ($bylwyjazd==1) {
				$sql = "INSERT INTO $dbname_hd.hd_zgloszenie_wyjazd VALUES ('','$zgl_seryjme_unique_nr','$_REQUEST[hd_wyjazd_data]','$_REQUEST[trasa]',$_REQUEST[km],'$currentuser',1,$es_filia)";
				//echo "<br />$sql";
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
			
			// jeżeli wybrano zmianę priorytetu zgłoszenia $_REQUEST[SelectZmienStatus]=8
			
			$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_osoba_przypisana='$osoba_przypisana', zgl_status='$NowyStatusZgloszenia',zgl_data_zmiany_statusu='$_REQUEST[zs_data]' WHERE ((zgl_widoczne=1) and (belongs_to='$GetBelongsToFromHD') and (zgl_id='$_REQUEST[hdzglid]')) LIMIT 1";
			$result = mysql_query($sql, $conn_hd) or die($k_b);
		
			// zaktualizuj czas wykonywania w zgłoszeniu
			$r3 = mysql_query("SELECT zgl_razem_czas FROM $dbname_hd.hd_zgloszenie WHERE ((zgl_id='$_REQUEST[hdzglid]') and (belongs_to='$GetBelongsToFromHD') and (zgl_widoczne=1)) LIMIT 1", $conn_hd) or die($k_b);
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
		
			$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_razem_czas=$razem_czas WHERE ((zgl_widoczne=1) and (belongs_to='$GetBelongsToFromHD') and (zgl_id='$_REQUEST[hdzglid]')) LIMIT 1";
			//echo "$d_cw<br />$dodaj_czas_osob_dodatkowych<br />$ile_dodatkowych";
			//echo "<br />$sql";
			$result = mysql_query($sql, $conn_hd) or die($k_b);

			// zaktualizuj km w zgłoszeniu
			$r3 = mysql_query("SELECT zgl_razem_km FROM $dbname_hd.hd_zgloszenie WHERE ((zgl_id='$_REQUEST[hdzglid]') and (belongs_to='$GetBelongsToFromHD') and (zgl_widoczne=1)) LIMIT 1", $conn_hd) or die($k_b);
			list($razem_km)=mysql_fetch_array($r3);
			if (($_REQUEST[km]!=0) && ($_REQUEST[km]!='')) $razem_km += $_REQUEST[km];
			
			$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_razem_km=$razem_km WHERE ((zgl_widoczne=1) and (belongs_to='$GetBelongsToFromHD') and (zgl_id='$_REQUEST[hdzglid]')) LIMIT 1";
			//echo "<br />$sql";
			$result = mysql_query($sql, $conn_hd) or die($k_b);
			
		}
	}
	
	?><script>
			<?php if ($_POST[from]=='hd') { ?>
			
					if (readCookie('wpisane_wc_<?php echo $_POST[hd_zgl_nr]; ?>')==null) {
						SetCookie('wpisane_wc_<?php echo $_POST[hd_zgl_nr]; ?>','<?php echo $_POST[duwagi]; ?>'); 
					} else {
						if (confirm('Czy przenieść wykonane czynności z protokołu do wykonanych czynności w obsługiwanym kroku zgłoszenia ?')) 
							SetCookie('wpisane_wc_<?php echo $_POST[hd_zgl_nr]; ?>','<?php echo $_POST[duwagi]; ?>'); 
					}
					
					//if (readCookie('wpisane_wc_<?php echo $_POST[hd_zgl_nr]; ?>')==null) SetCookie('wpisane_wc_<?php echo $_POST[hd_zgl_nr]; ?>','<?php echo $_POST[duwagi]; ?>'); 
			<?php } ?>		
			if (opener) opener.location.reload(true); 
			self.close(); </script><?php 
} else {
	?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
	}
?>
</body>
</html>