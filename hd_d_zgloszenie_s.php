<?php include_once('header.php');
include_once('cfg_helpdesk.php');

if ($_GET[filtr]=='X-X-X-X') 
	{ 
		echo "<body OnLoad=\"document.getElementById('hdgz').focus(); GenerateOnClickEventForSlownikTresci();\" onUnload=\"eraseCookie(self.name); eraseCookie('current_window_name');\" />"; 
	} else {
		echo "<body OnLoad=\"document.getElementById('zamknij_button').focus();\" />"; 
		}

if ($submit) {
	
	function toUpper($string) {
		$string = strtr($string,'qwertyuiopasdfghjklzxcvbnm','QWERTYUIOPASDFGHJKLZXCVBNM');
		return (strtr($string, 'ęóąśłżźćń','ĘÓĄŚŁŻŹĆŃ' )); 
	}; 

	$_POST=sanitize($_POST);

	$_REQUEST[priorytet_id] = 2;
	
//	$unique_nr = $_REQUEST[unique_nr1];

	$komorki_all = nl2br2($_REQUEST[komorki]);
	
//	echo "WARTOŚĆ: ".$komorki_all."<hr />";
	
	$jedno_up = explode("; ", $komorki_all);
	//$ileup = substr_count($komorki_all,'; ')-1;
	$ileup = count($jedno_up)-1;
	
//	$dlug = strlen($komorki_all);
//	$ileup = 0;
//	for ($i=0; $i<=$dlug; $i++) {
		//if ($komorki_all[$i]==';') $ileup++;
	//}
		
	//echo "<br />ilość: ".$ileup;
	
	if ($ileup==0) { 
		echo "<h3>Informacje o wpisanym zgłoszeniu</h3>";
	} else {
		echo "<h3>Informacje o wpisanych zgłoszeniach</h3>";
	}
	
	starttable();
	//echo "<table width=790 border=0 cellspacing=0 cellpadding=0>";
	tbl_empty_row(2);
	
		tr_();	td_("150;r;Data zgłoszenia;"); 		_td(); 	td_(";;;");		echo "<b>$_REQUEST[hddz] $_REQUEST[hdgz]</b>";	_td(); 	_tr();
		if ($_REQUEST[hdnzhadim]!='') {
			tr_();	td_("150;r;Numer zgłoszenia z poczty;"); 	_td(); 	td_(";;;");		echo "<b>$_REQUEST[hdnzhadim]</b>";	_td(); 	_tr();
		}
		tr_();	td_("150;rt;Komórki;"); 				_td(); 	td_(";;;");		
		
		$list_all='';
		for ($i=0; $i<$ileup; $i++) {
			$dl = strlen($jedno_up[$i]);
			$p = strpos($jedno_up[$i],' ')+1;
			
			list($samoup)=mysql_fetch_array(mysql_query("SELECT serwis_komorki.up_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((serwis_komorki.belongs_to=$es_filia) and (serwis_piony.pion_id=serwis_komorki.up_pion_id) and (serwis_komorki.up_active=1) and (UPPER(CONCAT(serwis_piony.pion_nazwa,' ',serwis_komorki.up_nazwa))='".toUpper($jedno_up[$i])."'))", $conn_hd));
		
		//	$samoup = substr($jedno_up[$i],$p,$dl);

			$result44 = mysql_query("SELECT serwis_komorki.up_id, serwis_komorki.up_nazwa, serwis_piony.pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) and (serwis_komorki.up_nazwa = '$samoup')) LIMIT 1", $conn) or die($k_b);
			$count_rows = mysql_num_rows($result44);	
			list($temp_up_id,$temp_nazwa,$temp_pion) = mysql_fetch_array($result44);
			
			if (($i % 2)==0) { $list_all.= "<font color=black>$temp_pion $samoup, </font>"; } else { $list_all.= "<font color=grey>$temp_pion $samoup, </font>"; }
	//		$list_all.="$temp_pion $samoup, ";
	//		$list_all.= "</font>";

		}
		$list_all = substr($list_all,0,(strlen($list_all)-9));
	
	$zz = 0;
	if ($_REQUEST[zasadne]=='TAK') $zz = 1;
	
	$d_km = 0;
	if ($_REQUEST[km]!='') $d_km=$_REQUEST[km];
	
	$d_cw = 0;	// czas wykonywania
	if ($_REQUEST[czas_wykonywania_h]!='') { $h_na_m = (int) $_REQUEST[czas_wykonywania_h]*60; }
	if ($_REQUEST[czas_wykonywania_m]!='') { $m_na_m = (int) $_REQUEST[czas_wykonywania_m]; }
	
	$d_cw=$h_na_m+$m_na_m;
	
	
	if ($_REQUEST[CzasWykonywaniaLaczny]=='on') {
		$d_cw = ceil($d_cw/($ileup));
	}

		echo "<b>".nl2br($list_all)."</b>";
		
		_td(); 	_tr();
		tr_();	td_("150;rt;Osoba zgłaszające;"); 	_td(); 	td_(";;;");		
		echo "<b>$_REQUEST[hd_oz] </b>";
		
		if ($_REQUEST[hdoztelefon]!='')	echo "| Telefon kontaktowy: <b>";
		
		echo "<span id=UpdateTelefon>";
		echo $_REQUEST[hdoztelefon];
		echo "</span>";
		
		echo "</b>";
		echo "<span id=NrZaktualizowany style=display:none>";
			echo "&nbsp;<i>(zaktualizowano)</i>";
			echo "</span>";
			// jeżeli zaznaczono "zapamiętaj dane" - zapisz dane osoby zgłaszającej do bazy
			//if ($_POST['zapamietaj_oz']=='on') {
				
				// sprawdź czy nie ma już takiego wpisu (jeżeli tak - pomijamy zapisanie
				$result_k = mysql_query("SELECT hd_komorka_pracownicy_id, hd_komorka_pracownicy_telefon FROM $dbname_hd.hd_komorka_pracownicy WHERE ((hd_komorka_pracownicy_nazwa='$_POST[hd_oz]') and (hd_zgl_seryjne=1) and (belongs_to=$es_filia))", $conn_hd) or die($k_b);				
				list($oz_id, $oz_tel, $up_id)=mysql_fetch_array($result_k);
				if ($oz_id>0) {
				
					echo "<div id=warning1 style=\"display:'' style='background:red;' \" />";
					// jeżeli jest inny nr telefonu - daj możliwość jego aktualizacji
					if (($oz_tel!=$_POST[hdoztelefon]) && ($_POST[hdoztelefon]!='')) {
						echo "<br /><h3 style='font-weight:normal'><b>$_REQUEST[hd_oz]</b> jest już w bazie.<br />";
						nowalinia();	
						if ($oz_tel!='') {
							echo "Ma przypisany numer telefonu : <b>$oz_tel</b><br />";
						} else {
							echo "<font color=red><b>Ma przypisany pusty numer telefonu - wymagana jest aktualizacja</b></font><br />";
						}
						nowalinia();
						echo "<input type=button class=buttons value='Aktualizuj numer telefonu $_POST[hdoztelefon] dla tej osoby' onClick=\"$('#UpdateTelefon').load('hd_update_oz.php?ozid=$oz_id&nnt=".urlencode($_POST[hdoztelefon])."&randval='+ Math.random()); \" />";
						echo "</h3>";
					}
					echo "</div>";
						
				} else 
				{
						$sql_a = "INSERT INTO $dbname_hd.hd_komorka_pracownicy VALUES ('','','$_POST[hd_oz]','$_POST[hdoztelefon]',1,'$currentuser',$es_filia)";
						if (mysql_query($sql_a, $conn_hd)) { 
							?><script> //opener.location.reload(true); //self.close();  </script><?php
						} else {
							?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
						}
				}	
			//}		
		_td(); 	_tr();
		tr_();	td_("150;r;Temat zgłoszenia;"); 		_td(); 	td_(";;;");		echo "<b>".cleanup($_REQUEST[hd_temat])."</b>";	_td(); 	_tr();
		tr_();	td_("150;rt;Treść zgłoszenia;"); 		_td(); 	
		td_(";;;");
			echo "<b>".cleanup(nl2br(wordwrap($_REQUEST[hd_tresc], 110, "<br />")))."</b>";
		_td(); 	_tr();
		
		$r1 = mysql_query("SELECT hd_kategoria_opis FROM $dbname_hd.hd_kategoria WHERE (hd_kategoria_nr='$_POST[kat_id]') LIMIT 1", $conn_hd) or die($k_b);list($kat_opis)=mysql_fetch_array($r1);

		$r2 = mysql_query("SELECT hd_podkategoria_opis FROM $dbname_hd.hd_podkategoria WHERE (hd_podkategoria_id='$_POST[podkat_id]') LIMIT 1", $conn_hd) or die($k_b);list($podkat_opis)=mysql_fetch_array($r2);

		$r3 = mysql_query("SELECT hd_priorytet_opis FROM $dbname_hd.hd_priorytet WHERE (hd_priorytet_id='$_POST[priorytet_id]') LIMIT 1", $conn_hd) or die($k_b);list($priorytet_opis)=mysql_fetch_array($r3);

		$r4 = mysql_query("SELECT hd_status_opis FROM $dbname_hd.hd_status WHERE (hd_status_id='$_POST[status_id]') LIMIT 1", $conn_hd) or die($k_b);list($status_opis)=mysql_fetch_array($r4);
						
		tr_();	td_("150;r;Kategoria;"); 	_td(); 	td_(";;;");		echo "<b>$kat_opis</b>";	_td(); 	_tr();
		tr_();	td_("150;r;Podkategoria;"); _td(); 	td_(";;;");		echo "<b>$podkat_opis</b>";	_td(); 	_tr();
		tr_();	td_("150;r;Priorytet;"); 	_td(); 	td_(";;;");		echo "<b>$priorytet_opis</b>";	_td(); 	_tr();
		tr_();	td_("150;r;Status;"); 		_td(); 	td_(";;;");		echo "<b>$status_opis</b>";	_td(); 	_tr();

	if (($_REQUEST[status_id]==9) && ($d_cw!=0)) {
		tr_();	td_("150;r;Czas wykonywania;"); 		_td(); 	td_(";;;");		
		
		echo "<b>$d_cw minut</b>";
		echo " (dla każdej komórki) ";
		_td(); 	_tr();
	}
		
	if ($_REQUEST[PozwolWpisacKm]=='on') {
	tbl_empty_row(2);
		tr_();	td_("150;r;Przejechane kilometry;"); 	_td(); 	td_(";;;");		echo "<b>$_REQUEST[km] km</b>";	_td(); 	_tr();
		tr_();	td_("150;rt;Trasa wyjazdu;"); 			_td(); 	td_(";;;");		echo "<textarea cols=96 rows=2  style='border:0px;background-color:transparent;font-family:tahoma;font-size:11px;font-weight:bold;' readonly tabindex=-1>$_REQUEST[trasa]</textarea>";	_td(); 	_tr();
		
	tbl_empty_row(2);
	}

		tr_();	td_("150;r;Zgłoszenie zasadne;"); 		_td(); 	td_(";;;");		echo "<b>$_REQUEST[zasadne]</b>";	_td(); 	_tr();
	tbl_empty_row(2);
	
	endtable();

?>
<script>ShowWaitingMessage('Trwa zapisywanie danych... proszę czekać', 'Saving');</script>
<?php
	
// Zapisywanie zgłoszeń do bazy 
$dodatkowe2 = rand_str()."".Date('YmdHis');

if ($_SESSION['zgloszenie_s_dodano_'.$_REQUEST[unique_nr1].'']!='poprawnie') {

	$DataGodzinaWpisu = $_REQUEST[hddz]." ".$_REQUEST[hdgz];
	$DataWpisu = $_REQUEST[hddz];
	
	// początek pętli przechodzącej przez wszystkie wybrane komórki	
	$start_hour = $_REQUEST[hdgz];
	
	
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
	if ($_REQUEST[status_id]==9) {
		$czy_wymagany_wyjazd = 0;
		$czy_wymagany_wyjazd_data = '';
		$czy_wymagany_wyjazd_osoba = '';
	}	
	//$start_hour = $_REQUEST[hdgz];
	
	for ($i=0; $i<$ileup; $i++) {
		$dl = strlen($jedno_up[$i]);
		$p = strpos($jedno_up[$i],' ')+1;
		
		
		list($samoup)=mysql_fetch_array(mysql_query("SELECT serwis_komorki.up_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((serwis_komorki.belongs_to=$es_filia) and (serwis_piony.pion_id=serwis_komorki.up_pion_id) and (serwis_komorki.up_active=1) and (UPPER(CONCAT(serwis_piony.pion_nazwa,' ',serwis_komorki.up_nazwa))='".toUpper($jedno_up[$i])."'))", $conn_hd));
		
		//$samoup = substr($jedno_up[$i],$p,$dl);

		if ($TrybDebugowania==1) ShowSQL("SELECT serwis_komorki.up_id, serwis_komorki.up_nazwa, serwis_piony.pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) and (serwis_komorki.up_nazwa LIKE '%$samoup%')) LIMIT 1");
		
		$result44 = mysql_query("SELECT serwis_komorki.up_id, serwis_komorki.up_nazwa, serwis_piony.pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) and (serwis_komorki.up_nazwa = '$samoup')) LIMIT 1", $conn) or die($k_b);
			
		//list($temp_up_id,$temp_nazwa,$temp_pion)=mysql_fetch_array(mysql_query("SELECT serwis_komorki.up_id, serwis_komorki.up_nazwa, serwis_piony.pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((serwis_komorki.belongs_to=$es_filia) and (serwis_piony.pion_id=serwis_komorki.up_pion_id) and (serwis_komorki.up_active=1) and (CONCAT(serwis_piony.pion_nazwa,' ',serwis_komorki.up_nazwa)='$komorka_zglaszajaca'))", $conn_hd));

			
		$count_rows = mysql_num_rows($result44);
		list($temp_up_id,$temp_nazwa,$temp_pion) = mysql_fetch_array($result44);

		$pojedyncza_komorka="$temp_pion $samoup";
		//$pojedyncza_komorka=toUpper($pojedyncza_komorka);
		//echo "$i : $samoup ($temp_up_id) <br />";

		// pobranie aktualnie obowiązujących godzin pracy komórki w momencie rejestracji zgłoszenia
		$r2 = mysql_query("SELECT up_working_time,up_working_time_alternative,up_working_time_alternative_start_date,up_working_time_alternative_stop_date FROM $dbname.serwis_komorki WHERE (up_id='$temp_up_id') LIMIT 1", $conn_hd) or die($k_b);
		list($wt1,$wta1,$wtastart,$wtastop)=mysql_fetch_array($r2);
		
		$dni = explode(";",$wt1);
		$pn = explode("@",$dni[0]);
		$wt = explode("@",$dni[1]);
		$sr = explode("@",$dni[2]);
		$cz = explode("@",$dni[3]);
		$pt = explode("@",$dni[4]);
		$so = explode("@",$dni[5]);
		$ni = explode("@",$dni[6]);
		
		$week = $wt1;
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
		
		//if ($debug) echo ">>".$wtastart." ".$wtastop."<br />";
		if ((substr($wtastart,5,5)!='00-00') && (substr($wtastop,5,5)!='00-00')) {
			
			if ($debug) echo "wtastart (5,5): ".substr($wtastart,5,5)." | wtastop (5,5): ".substr($wtastop,5,5)."";

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
			}
		}	
			
		//if ($debug) echo $pn[1]."".$wt[1]."".$sr[1]."".$cz[1]."".$pt[1]."".$so[1]."".$ni[1];
		
			if ($czy_jest_wt==0) {
			//	echo "<h2>Dla komórki<br /><font color=white>$_REQUEST[up_list]</font><br />nie zdefiniowano godzin pracy.";
				
				if (($es_nr==$kierownik_nr) || ($is_dyrektor==1)) {
					//echo "<input type=button class=buttons value='Wypełnij godziny pracy tej komórki' onClick=\"newWindow_r(800,600,'e_komorka.php?select_id=$_POST[up_list_id]'); return false;\" />";
			}

			//echo "<br /><br />Do wyliczenia czasów rozpoczęcia i zakończenia użyte zostały wartości domyślne:<br />pn-pt:$gp_start_pn_pt-$gp_stop_pn_pt, so:$gp_start_so-$gp_stop_so, ni:$gp_start_ni-$gp_stop_ni ";
			//echo "</h2>";
			
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
		
	// 	koniec pobierania godzin pracy komórki		
			
			if ($TrybDebugowania==1) ShowSQL("SELECT up_kategoria FROM $dbname.serwis_komorki WHERE (up_id='$temp_up_id') LIMIT 1");
			
			$r2 = mysql_query("SELECT up_kategoria, up_przypisanie_jednostki FROM $dbname.serwis_komorki WHERE (up_id='$temp_up_id') LIMIT 1", $conn_hd) or die($k_b);
			
			$MinutDoRozpoczecia = 0;
			$MinutDoZakonczenia = 0;
				
			$DataRozpoczecia=''; 
			$DataZakonczenia='';
			//$OsobaPrzypisana = '';
			
			if ($_REQUEST[status_id]=='1') $OsobaPrzypisana=$currentuser;
			if ($_REQUEST[status_id]=='2') $OsobaPrzypisana=$_REQUEST['PrzypiszDoOsobyValue'];
			if ($_REQUEST[status_id]=='3') $OsobaPrzypisana=$currentuser;
			if ($_REQUEST[status_id]=='7') $OsobaPrzypisana=$currentuser;
			if ($_REQUEST[status_id]=='9') $OsobaPrzypisana=$currentuser;
			if ($_REQUEST[status_id]=='0') $OsobaPrzypisana=$currentuser;
			if ($_REQUEST[status_id]=='3A') $OsobaPrzypisana=$currentuser;
			if ($_REQUEST[status_id]=='3B') $OsobaPrzypisana=$currentuser;
			
			$dodatkowe1 = '';

			$dodatkowe3 = '';	// kolejnosc w zgloszeniu seryjnym
			
			//$unique_nr = rand_str();
			
			$unique_nr = Date('YmdHis')."".rand_str();
			
			$czy_synchr = 0;
			if ($_REQUEST[czy_synchronizowac]=='on') $czy_synchr = 1;

			// domyślnie z poziomu nowego zgłoszenia nie ma możliwości powiązania go z naprawą
			$hd_naprawa_id = 0;

			$rekl_czy_jest_reklamacyjne = 0;
			$rekl_nr_zgl_reklamowanego = 0;
			$rekl_czy_utworzono_z_niego_reklamacyjne = 0;
		
			// czy powiązane z wymianą podzespołów
			$czy_powiazane_z_wp = 0;

			// ustelenie zmiennej: czy rozwiązany
			$czy_rozwiazany = 0;
			if ($_REQUEST[kat_id]=='1') $czy_rozwiazany = 1;
			if ($_REQUEST[kat_id]=='2') $czy_rozwiazany = 0;
			if ($_REQUEST[kat_id]=='3') $czy_rozwiazany = 1;
			if ($_REQUEST[kat_id]=='4') $czy_rozwiazany = 1;
			if ($_REQUEST[kat_id]=='5') $czy_rozwiazany = 1;
			if ($_REQUEST[kat_id]=='6') $czy_rozwiazany = 0;
			
			$czy_rozwiazany_data = '';
			if ($czy_rozwiazany==1) $czy_rozwiazany_data = $_REQUEST[hddz];
	
			$sql = "INSERT INTO $dbname_hd.hd_zgloszenie VALUES ('','','$_REQUEST[hdnzhadim]','$unique_nr','$_REQUEST[hddz]','$start_hour','$pojedyncza_komorka','$_REQUEST[hd_oz]','$_REQUEST[hdoztelefon]','$_REQUEST[hd_temat]','$_REQUEST[hd_tresc]','$_REQUEST[kat_id]','$_REQUEST[podkat_id]','$_REQUEST[sub_podkat_id]','$_REQUEST[priorytet_id]','$_REQUEST[status_id]','$OsobaPrzypisana','$DataRozpoczecia','$DataZakonczenia',$MinutDoRozpoczecia,$MinutDoZakonczenia,$d_cw,$d_km,1,'$currentuser',$zz,'','$dodatkowe1','$dodatkowe2','$dodatkowe3','$_REQUEST[hddz]','',$czy_synchr,'-1',$hd_naprawa_id,'0','$_REQUEST[hdtu]','$kategoria_up','$przyp_jedn','$rekl_czy_jest_reklamacyjne','$rekl_nr_zgl_reklamowanego','$rekl_czy_utworzono_z_niego_reklamacyjne','$_REQUEST[hdds]',$czy_powiazane_z_wp,'$week',$czy_rozwiazany,'$czy_rozwiazany_data',0,0,0,0,0,0,$czy_wymagany_wyjazd,'$czy_wymagany_wyjazd_data','$czy_wymagany_wyjazd_osoba','-1','','',0,$es_filia)";

			//echo $sql."<br />";
			
			if ($TrybDebugowania==1) ShowSQL($sql);			
			//echo "$sql";
			$result = mysql_query($sql, $conn_hd) or die($k_b);			

		// *************************

			$_SESSION['zgloszenie_s_dodano_'.$_REQUEST[unique_nr1].'']='poprawnie';

		// *************************			
			
			if ($TrybDebugowania==1) ShowSQL("SELECT zgl_id FROM $dbname_hd.hd_zgloszenie WHERE ((zgl_unikalny_nr='$unique_nr') and (belongs_to='$es_filia') and (zgl_osoba_rejestrujaca='$currentuser')) ORDER BY zgl_id DESC LIMIT 1");		
			
			$r3 = mysql_query("SELECT zgl_id FROM $dbname_hd.hd_zgloszenie WHERE ((zgl_unikalny_nr='$unique_nr') and (belongs_to='$es_filia') and (zgl_osoba_rejestrujaca='$currentuser')) ORDER BY zgl_id DESC LIMIT 1", $conn_hd) or die($k_b);
			list($last_nr)=mysql_fetch_array($r3);
			// ustalenie numeru zgłoszenia (STOP)

			$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_nr=$last_nr WHERE ((zgl_unikalny_nr='$unique_nr') and (belongs_to='$es_filia') and (zgl_osoba_rejestrujaca='$currentuser')) LIMIT 1";
			
			if ($TrybDebugowania==1) ShowSQL($sql);	
			
			$result = mysql_query($sql, $conn_hd) or die($k_b);
			$lista_numerow_zgloszen.="$last_nr, ";
			
		//	if ($_SESSION[zgloszenie_s_szcz_dodano]!='poprawnie') {

			$bylwyjazd=0;
			if ($_REQUEST[PozwolWpisacKm]=='on') $bylwyjazd = 1;

			$dddd = date("Y-m-d H:i:s");
			//$osobaprzypisana='';
			//if (($_REQUEST[status_id]=='2') || ($_REQUEST[status_id]=='3') || ($_REQUEST[status_id]=='9')) $osobaprzypisana=$currentuser;

			$CzasStartStop='';
			
			switch ($_REQUEST[status_id]) {
				case "1"	: $CzasStartStop='START'; break;
				case "2"	: $CzasStartStop='START'; break;
				case "3"	: $CzasStartStop='START'; break;
				case "4"	: $CzasStartStop='STOP';  break;
				case "5"	: $CzasStartStop='START';  break;
				case "6"	: $CzasStartStop='START';  break;
				case "7"	: $CzasStartStop='START';  break;
				case "8"	: $CzasStartStop='START';  break;
				case "9"	: $CzasStartStop='STOP';  break;
				case "3A"	: $CzasStartStop='STOP';  break;
				case "3B"	: $CzasStartStop='START';	break;
			}

			
			if (($DataRozpoczecia!='') && ($DataZakonczenia!='') && ($_REQUEST[kat_id]=='2') ) {
				$CzasStartStop = 'START';
			}

			// jeżeli zaznaczono opcję wyślij email do koordynatora
			$emailSend = 0;

				if ($_REQUEST[WyslijEmailCheckbox]=='on') {
				
					if ($TrybDebugowania==1) ShowSQL("SELECT up_umowa_id FROM $dbname.serwis_komorki WHERE ((up_id='$_REQUEST[up_list_id]') and (belongs_to='$es_filia')) LIMIT 1");	
					
					$r3 = mysql_query("SELECT up_umowa_id FROM $dbname.serwis_komorki WHERE ((up_id='$_REQUEST[up_list_id]') and (belongs_to='$es_filia')) LIMIT 1", $conn) or die($k_b);
					list($umowaid)=mysql_fetch_array($r3);
					
					if ($TrybDebugowania==1) ShowSQL("SELECT umowa_koordynator, umowa_koordynator_email FROM $dbname.serwis_umowy WHERE ((umowa_id='$umowaid') and (belongs_to='$es_filia')) LIMIT 1");	
					
					$r4 = mysql_query("SELECT umowa_koordynator, umowa_koordynator_email FROM $dbname.serwis_umowy WHERE ((umowa_id='$umowaid') and (belongs_to='$es_filia')) LIMIT 1", $conn) or die($k_b);
					list($koord, $koord_email)=mysql_fetch_array($r4);
					
					if ($TrybDebugowania==1) ShowSQL("SELECT hd_kategoria_opis FROM $dbname_hd.hd_kategoria WHERE (hd_kategoria_nr='$_POST[kat_id]') LIMIT 1");	
					
					$r1 = mysql_query("SELECT hd_kategoria_opis FROM $dbname_hd.hd_kategoria WHERE (hd_kategoria_nr='$_POST[kat_id]') LIMIT 1", $conn_hd) or die($k_b);list($kat_opis)=mysql_fetch_array($r1);

					if ($TrybDebugowania==1) ShowSQL("SELECT hd_podkategoria_opis FROM $dbname_hd.hd_podkategoria WHERE (hd_podkategoria_nr='$_POST[podkat_id]') LIMIT 1");	
					
					$r2 = mysql_query("SELECT hd_podkategoria_opis FROM $dbname_hd.hd_podkategoria WHERE (hd_podkategoria_nr='$_POST[podkat_id]') LIMIT 1", $conn_hd) or die($k_b);list($podkat_opis)=mysql_fetch_array($r2);
						
					//$koord_email = 'sebastian.majewski@postdata.pl';
						
					if ($koord_email!='') {
						// wyślij email
						$temat_maila = "Zarejestrowano nowe zgłoszenie o nr $last_nr";
						$tresc_maila = "Do bazy dodano nowe zgłoszenie nr $last_nr\n";
						$tresc_maila.= "Osoba przyjmująca zgłoszenie : $currentuser\n";
						$tresc_maila.= "Data przyjęcia zgłoszenia : $_REQUEST[hddz] $_REQUEST[hdgz]\n";
						$tresc_maila.= "Kategoria zgłoszenia : $kat_opis\n";
						$tresc_maila.= "Podkategoria zgłoszenia : $podkat_opis\n";
						$tresc_maila.= "Temat zgłoszenia: $_REQUEST[hd_temat]\n";
						$tresc_maila.= "Treść zgłoszenia : $_REQUEST[hd_tresc]\n";
						$tresc_maila.= "Osoba zgłaszająca : $_REQUEST[hd_oz] ($_REQUEST[hdoztelefon])\n";
						$tresc_maila.= "\n\n";
						$tresc_maila.= "Mail został wygenerowany automatycznie - proszę na niego nie odpisywać";
						
						if (smtpmailer($koord_email, 'helpdesk-lodz@postdata.pl', 'Helpdesk - O/Łódź', $temat_maila, $tresc_maila, $last_nr)) {
							echo "<h3>Email został wysłany do $koord ($koord_email)</h3>";
							$emailSend = 1;
						}
						if (!empty($error)) echo $error;
						
						//echo "email pójdzie do : $koord ($koord_email)";
					
					} else { 
						echo "<h2>Email do koordynatora nie został wysłany, gdyż nie został zdefiniowany w bazie umów</h2>";
					}
				
				}
				
			$przejechane_km = $_REQUEST[km];
			if (($_REQUEST[km]==0) || ($_REQUEST[km]=='')) $przejechane_km = 0;

			$Zdiagnozowany = '9';
			if (($_REQUEST[kat_id]=='2') && (($status_zgloszenia=='3') || ($status_zgloszenia=='7') || ($status_zgloszenia=='3A') || ($status_zgloszenia=='3B'))) $Zdiagnozowany = $_REQUEST[SelectZdiagnozowany];
			
			$wyk_czyn = 'rejestracja zgłoszenia';
			//if ($_REQUEST[status_id]=='9') 
			$wyk_czyn .= "<br /><br />".$_REQUEST[hd_tresc];

			
			$awaria_z_przesunieciem=0;
			$sql = "INSERT INTO $dbname_hd.hd_zgloszenie_szcz VALUES ('',$last_nr,'$unique_nr',1,'$CzasStartStop',$d_cw,'$_REQUEST[hddz] $start_hour','$_REQUEST[status_id]','$wyk_czyn','$OsobaPrzypisana','',$emailSend,9,9,$bylwyjazd,'$dddd','$currentuser',1,$przejechane_km,$awaria_z_przesunieciem,'','','$Zdiagnozowany','9','','','$_REQUEST[hdds]',$czy_rozwiazany,0,$es_filia)";
			//echo "$sql";

			if ($_REQUEST[status_id]=='9') $start_hour = AddMinutesToHour($d_cw,$start_hour);
			
			if ($TrybDebugowania==1) ShowSQL($sql);	
					
			$result = mysql_query($sql, $conn_hd) or die($k_b);
			
				// *************************

					$_SESSION['zgloszenie_s_szcz_dodano_'.$_REQUEST[unique_nr1].'']='poprawnie';

				// *************************
			
			if ($TrybDebugowania==1) ShowSQL("SELECT zgl_szcz_id FROM $dbname_hd.hd_zgloszenie_szcz WHERE ((zgl_szcz_unikalny_numer='$unique_nr') and (belongs_to='$es_filia') and (zgl_szcz_osoba_wykonujaca_krok='$currentuser')) ORDER BY zgl_szcz_id DESC LIMIT 1");	
			
			//$r3 = mysql_query("SELECT zgl_szcz_id FROM $dbname_hd.hd_zgloszenie_szcz WHERE ((zgl_szcz_unikalny_numer='$unique_nr') and (belongs_to='$es_filia') and (zgl_szcz_osoba_wykonujaca_krok='$currentuser')) ORDER BY zgl_szcz_id DESC LIMIT 1", $conn_hd) or die($k_b);
			//list($last_nr_szcz)=mysql_fetch_array($r3);

	
			
		// }	
	
	// koniec pętli przechodzącej przez wszystkie wybrane komórki
	
	// obsługa liczenia czasów na poszczególnych etapach
	
	// jeżeli zamykamy w pierwszym kroku zgłoszenie - dolicz czas wykonywania do czasu etapu zakończenia	
	if ($_REQUEST[status_id]==9) {
		$sql_etapy = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_E3C=$d_cw WHERE ((zgl_unikalny_nr='$unique_nr') and (belongs_to='$es_filia')) LIMIT 1";
		$result_etapy = mysql_query($sql_etapy, $conn_hd) or die($k_b);
	}
	
	
}

// jeżeli był wyjazd (START)
if ($bylwyjazd==1) {
	$sql = "INSERT INTO $dbname_hd.hd_zgloszenie_wyjazd VALUES ('','$unique_nr','$_REQUEST[hddz]','$_REQUEST[trasa]',$d_km,'$currentuser',1,$es_filia)";
	//	echo "$sql";
	
	if ($TrybDebugowania==1) ShowSQL($sql);	
	
	$result = mysql_query($sql, $conn_hd) or die($k_b);
}
// jeżeli był wyjazd (STOP)	

$razemup = $ileup;

echo "<h5>Pomyślnie zarejestrowano <b>$razemup</b> zgłoszeń do bazy</b></h5>";
// koniec zapisywania zgłoszeń do bazy

}

?>
<script>
document.getElementById('Saving').style.display='none';
</script>
<?php

	startbuttonsarea("right");
	oddziel();
	echo "<span style='float:left;'>";
		echo "<input class=buttons type=button onClick=\"window.location.href='hd_d_zgloszenie.php?stage=".$_REQUEST[stage]."'\" value='Nowe zgłoszenie' />";
		echo "<input class=buttons type=button onClick=window.location.href=\"hd_d_zgloszenie_s.php?stage=".$_REQUEST[stage]."&filtr=X-X-X-X\" value='Nowe zgłoszenie seryjne'>";
	echo "</span>";
	
	echo "<input class=buttons type=button style='font-weight:bold;'  onClick=\"self.close();if (opener) opener.location.href='hd_p_zgloszenia.php?showall=0&p5=BZ&sort=AD&page=1';\" value='Przeglądaj zgłoszenia' />";
	echo "<input class=buttons id=zamknij_button type=button onClick=\"self.close(); if (opener) opener.location.reload(true); \" value=Zamknij>";
	endbuttonsarea();
	
}
 else {
$unique_nr1 = Date('YmdHis')."".rand_str();

session_register('session_komorka');
$_SESSION[session_komorka]=0;

session_register('zgloszenie_s_dodano_'.$unique_nr1.'');	session_register('zgloszenie_s_szcz_dodano_'.$unique_nr1.'');
$_SESSION['zgloszenie_s_dodano_'.$unique_nr1.'']='nie';		$_SESSION['zgloszenie_s_szcz_dodano_'.$unique_nr1.'']='nie';

// weryfikacja aktywności dostępów czasowych dla wszystkich pracowników
	$aktualna_data = Date('Y-m-d H:i:s');
	$sql_dc = "UPDATE $dbname_hd.hd_dostep_czasowy SET dc_dostep_active=0 WHERE ((dc_dostep_active_to<'$aktualna_data') and (dc_dostep_active=1) and (belongs_to=$es_filia))";
	$result_dc = mysql_query($sql_dc, $conn_hd) or die($k_b);
// koniec weryfikacji aktywności dostępów czasowych

pageheader("Rejestracja nowego zgłoszenia seryjnego");

?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();

echo "<div id=content>";

starttable();
echo "<form id=hd_dodaj_zgl_s name=hd_dodaj_zgl_s action=$PHP_SELF method=POST autocomplete=off onSubmit=\"return pytanie_zatwierdz2('Zapisać zgłoszenie do bazy ?');\" />";
//echo "<input type=hidden id=stage name=stage value='$_REQUEST[stage]'>";

$result_k = mysql_query("SELECT filia_lokalizacja FROM $dbname.serwis_filie WHERE filia_id=$es_filia LIMIT 1", $conn) or die($k_b);
list($temp_lok)=mysql_fetch_array($result_k);
echo "<input type=hidden id=lokalizacjazrodlowa value='$temp_lok'>";

	include_once('systemdate.php');
	
	tr_();
		td("120;r;Data zgłoszenia");
		td_(";;;");
			$dddd = Date('Y-m-d');
			echo "<select class=wymagane name=hddz id=hddz>";
			echo "<option value='$dddd'";
			if ($_REQUEST[p1]=='') echo " SELECTED";	echo ">$dddd</option>\n";

			if ((date("w",strtotime($dddd))!=0) || ($idw_dla_zbh_testowa))  {
				for ($cd=1; $cd<($idw_dla_zbh+1); $cd++) {
					echo "<option value='".SubstractDays($cd,$dddd)."'";
					if ($_REQUEST[p1]==SubstractDays($cd,$dddd)) echo " SELECTED "; 
					echo ">".SubstractDays($cd,$dddd)."&nbsp;";
					if ($idw_dla_zbh_testowa) echo "[dla testów]";
					echo "</option>\n";
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
					echo "<option value='$temp_dc_data'>$temp_dc_data (dostępna do $temp_dc_dostep_do)</option>\n";
				}
			}
	// koniec sprawdzania dostępów czasowych dla pracownika
	
			echo "</select>\n";
			
			$tttt = Date('H:i');
			if ($_REQUEST[p2]!='') $tttt=$_REQUEST[p2];
			echo "&nbsp;Godzina zgłoszenia&nbsp;<input class=wymagane type=text name=hdgz id=hdgz value='$tttt' maxlength=5 size=2 onFocus=\"this.select();\" onKeyPress=\"return filterInput(1, event, false, '');\" onKeyUp=\"DopiszDwukropek('hdgz');\" onDblClick=\"this.value='".$tttt."'; \" title=\" Podwójne kliknięcie wpisuje godzinę otwarcia tego okna \" />";
			echo "&nbsp;&nbsp;Numer zgłoszenia poczty&nbsp;";
			if ($_REQUEST[p3]!='') { $hadim=$_REQUEST[p3]; } else { $hadim=''; } 
			echo "<input type=text id=hdnzhadim name=hdnzhadim value='$hadim' maxlength=".$HADIM_max." size=".$HADIM_width." onKeyPress=\"return filterInput(1, event, false); \" onFocus=\"this.select();\" />";
		_td();
	_tr();		

	echo "<tr id=FiltrowanieKomorek style='display:;'>";
		
		//td("120;rt;Filtrowanie komórek");
		echo "<td class=right>Filtrowanie komórek</td>";
		td_(";;;");
			echo "<fieldset style='background-color:transparent;padding:3px;width:97%'>";
			
			$upfiltr = explode("-",$_REQUEST[filtr]);
	
			//echo "$_REQUEST[filtr]";
			
			// pion
			echo "<select id=upfiltr1 name=upfiltr1 onChange=\"ApplyFiltr(document.getElementById('autofiltr').checked);\">";
			echo "<option value='X'"; 	if ($upfiltr[0]=='X') echo " SELECTED";	echo ">wg pionu</option>\n"; 
			echo "<option value=0";		if ($upfiltr[0]==0) echo " SELECTED";	echo ">-wszystkie-</option>\n";
			
			$sql="SELECT pion_id,pion_nazwa FROM $dbname.serwis_piony WHERE (pion_active=1) ORDER BY pion_nazwa";
			$result=mysql_query($sql,$conn) or die($k_b);
	
			while ($dane=mysql_fetch_array($result)) {
				$temp_id = $dane['pion_id'];	$temp_nazwa = $dane['pion_nazwa'];
				echo "<option value=$temp_id";	if ($upfiltr[0]==$temp_id) echo " SELECTED";	echo ">$temp_nazwa</option>\n";
			}
			echo "</select>";
			echo "&nbsp;";
			// typ
			echo "<select id=upfiltr2 name=upfiltr2 onChange=\"ApplyFiltr(document.getElementById('autofiltr').checked);\">";
			
			echo "<option value='X'"; 	if ($upfiltr[1]=='X') echo " SELECTED";	echo ">wg typu</option>\n"; 
			echo "<option value=0";		if ($upfiltr[1]==0) echo " SELECTED";	echo ">-wszystkie-</option>\n";
			
			$sql="SELECT slownik_typ_komorki_id,slownik_typ_komorki_opis FROM $dbname.serwis_slownik_typ_komorki ORDER BY slownik_typ_komorki_id";
			$result=mysql_query($sql,$conn) or die($k_b);
	
			while ($dane=mysql_fetch_array($result)) {
				$temp_id = $dane['slownik_typ_komorki_id'];
				$temp_nazwa = $dane['slownik_typ_komorki_opis'];
				echo "<option value=$temp_id";	if ($upfiltr[1]==$temp_id) echo " SELECTED";	echo ">$temp_nazwa</option>\n";
			}
			echo "</select>";				
			echo "&nbsp;";
			// kategoria
			echo "<select id=upfiltr3 name=upfiltr3 onChange=\"ApplyFiltr(document.getElementById('autofiltr').checked);\">";
			
			echo "<option value='X'"; 	if ($upfiltr[2]=='X') echo " SELECTED";	echo ">wg kategorii</option>\n"; 
			echo "<option value=0";		if ($upfiltr[2]==0) echo " SELECTED";	echo ">-wszystkie-</option>\n";
			
			$sql="SELECT slownik_kategoria_komorki_id,slownik_kategoria_komorki_opis FROM $dbname.serwis_slownik_kategoria_komorki ORDER BY slownik_kategoria_komorki_id";
			$result=mysql_query($sql,$conn) or die($k_b);
	
			while ($dane=mysql_fetch_array($result)) {
				$temp_id = $dane['slownik_kategoria_komorki_id'];
				$temp_nazwa = $dane['slownik_kategoria_komorki_opis'];
				echo "<option value=$temp_id";	if ($upfiltr[2]==$temp_id) echo " SELECTED";	echo ">$temp_nazwa</option>\n";
			}
			echo "</select>";	
			echo "&nbsp;";
			// kompleksowa obsługa
			echo "<select id=upfiltr4 name=upfiltr4 onChange=\"ApplyFiltr(document.getElementById('autofiltr').checked);\">";
			echo "<option value='X'"; 	if ($upfiltr[3]=='X') echo " SELECTED";	echo ">wg obsługi</option>\n"; 
			echo "<option value=9";		if ($upfiltr[3]==9) echo " SELECTED";	echo ">-wszystkie-</option>\n";
			
			echo "<option value=1"; if ($upfiltr[3]=='1') echo " SELECTED"; echo ">kompleksowa</option>\n";
			echo "<option value=0"; if ($upfiltr[3]=='0') echo " SELECTED"; echo ">pozostałe</option>\n";
			echo "</select>";	
			echo "&nbsp;";
			echo "<input type=button class=buttons style='padding:1px; margin:0px;height:21px;' value=' Zastostuj filtr ' onClick=\"ApplyFiltr(true); document.getElementById('hd_oz').focus(); \" />";
			echo "&nbsp;<input type=button class=buttons style='padding:1px; margin:0px;height:21px;' value=' Czyść filtr ' onClick=\"document.getElementById('upfiltr1').selectedIndex=0;document.getElementById('upfiltr2').selectedIndex=0;document.getElementById('upfiltr3').selectedIndex=0;document.getElementById('upfiltr4').selectedIndex=0; ApplyFiltr(true);\">";
			echo "</fieldset>";
		_td();
	_tr();			
	
	tr_();
		echo "<td class=righttop>";
		echo "Komórka zgłaszająca<br /><font color=red><sub>tylko z typem usługi: <b>KOI</b></sub></font><br />";
			echo "<a href=# class=normalfont onClick=\"if (document.getElementById('autofiltr').checked) { document.getElementById('autofiltr').checked=false; } else { document.getElementById('autofiltr').checked=true; } \">auto-filtr</a>";
		
			echo "<input class=border0 type=checkbox id=autofiltr name=autofiltr"; 
			if ($upfiltr[4]=='true') echo " checked=checked";
			echo ">";
			echo "<br /><input type=button class=buttons style='width:135px;' onClick=\"$('#upids').toChecklist('checkAll'); document.getElementById('hd_oz').focus(); \" value=\"Zaznacz wszystkie\">";
			echo "<br /><input type=button class=buttons style='width:135px;'onClick=\"$('#upids').toChecklist('clearAll');\" value=\"Odznacz wszystkie\">";
			echo "<br /><input type=button class=buttons style='width:135px;'onClick=\"$('#upids').toChecklist('invert');\" value=\"Odwróć zaznaczenie\">";
		echo "</td>";
		td_(";;;");
			$sql_filtruj = "SELECT serwis_komorki.up_id, serwis_komorki.up_nazwa, serwis_piony.pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) and (serwis_komorki.up_typ_uslugi='KOI')";
			//and (LOCATE(',',serwis_komorki.up_nazwa)=0)";
			
			// and (LOCATE(',',serwis_komorki.up_nazwa)==-1) 
			
			if (($upfiltr[0]>0) && ($upfiltr[0]!='X')) $sql_filtruj.=" AND (serwis_piony.pion_id=$upfiltr[0])";
			if (($upfiltr[1]>0) && ($upfiltr[1]!='X')) $sql_filtruj.=" AND (serwis_komorki.up_typ=$upfiltr[1])";
			if (($upfiltr[2]>0) && ($upfiltr[2]!='X')) $sql_filtruj.=" AND (serwis_komorki.up_kategoria=$upfiltr[2])";
			if (($upfiltr[3]==1) && ($upfiltr[3]!='X')) $sql_filtruj.=" AND (serwis_komorki.up_kompleksowa_obsluga=$upfiltr[3])";
			if (($upfiltr[3]==0) && ($upfiltr[3]!='X')) $sql_filtruj.=" AND (serwis_komorki.up_kompleksowa_obsluga=$upfiltr[3])";
			
			$sql_filtruj.= " ORDER BY serwis_piony.pion_nazwa,up_nazwa";
			$result44 = mysql_query($sql_filtruj, $conn) or die($k_b);
			
			$count_rows = mysql_num_rows($result44);
		
			if ($count_rows>0) {
				echo "Pozycji na liście : <b>$count_rows</b><br/> ";
			}
			
			//$result55 = mysql_query("SELECT count(serwis_komorki.up_id) FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) and (serwis_komorki.up_typ_uslugi='KOI') and (LOCATE(',',serwis_komorki.up_nazwa)>0)",$conn) or die($k_b);
			
			//list($cnt_przecinek) = mysql_fetch_array($result55);

			//if ($cnt_przecinek>0) {
//				errorheader('Na liście nie ma '.$cnt_przecinek.' komórki / komórek, ponieważ zawierają w nazwie znak przecinka');
			//}
			
			if ($count_rows>0) {
				echo "<select class=wymagane name=upids[] size=7 id=upids multiple=multiple>\n";		 				
				$FirstPion='';
				$zamknij_grupe=0;
				$zamknij_grupe_up=0;
				while (list($temp_id,$temp_nazwa,$temp_pion) = mysql_fetch_array($result44)) {					
					echo "<option value='$temp_id' ";
					echo ">$temp_pion $temp_nazwa</option>\n"; 
				}
				echo "</select>";
			} else {
				echo "<b><center><font size=5 color=red>Brak komórek spełniających podane ktyteria</font></center></b><br />";
			}

			if ($count_rows>0) {
				echo "Wybrane komórki<br />";
				echo "<textarea style='background-color:transparent;font-size:9px;font-family:tahoma;' name=komorki id=upids_selectedItems readonly cols=130 rows=3></textarea>";
			} else echo "<input type=hidden name=komorki id=upids_selectedItems value=''>";
		_td();
	_tr();
	tr_();
		td("120;rt;Osoba zgłaszająca");
		td_(";;;");		
			if ($_REQUEST[p4]!='') { $osoba=$_REQUEST[p4]; } else { $osoba=''; }
			echo "<input class=wymagane type=text name=hd_oz id=hd_oz size=38 maxlength=30 value='$osoba' onKeyPress=\"return filterInput(0, event, false, ' ęóąśłżźćńĘÓĄŚŁŻŹĆŃ'); \" onFocus=\"this.select();\" onBlur=\"cUpper(this); go_tel2(); if (document.getElementById('hdoztelefon').value!='') { document.getElementById('hd_tresc').focus(); } else { document.getElementById('hdoztelefon').focus(); } \"/>";
			echo "<div id=pokaz_pracownikow style='display:none; background-color:grey; width:400px;'>";
			echo "<hr>";
			echo "<div id=lista_pracownikow_from_ajax>";
			echo "&nbsp;<select name=pracownik onChange=\"document.getElementById('hd_oz').value=this.form.pracownik.value; showhide('pokaz_pracownikow','pokaz_id_pracownikow'); \">\n";
			echo "</select>";
			echo "</div>";
			echo "<hr>";
			echo "</div>";
			if ($_REQUEST[p5]!='') { $tel=$_REQUEST[p5]; } else { $tel=''; }
			echo "&nbsp;Telefon&nbsp;<input type=text name=hdoztelefon id=hdoztelefon size=14 maxlength=15 ";
			if ($czy_wymagany_nr_telefonu==1) echo " class=wymagane ";
			echo " value='$tel'  onKeyPress=\"return filterInput(1, event, false,' '); \" onSelect=\"document.getElementById('hdoztelefon').value=document.getElementById('hdoztelefon1').value;\"/>";
			
		echo "<span id=ZapamietajDane style='display:none'>";	
			echo "&nbsp;<input class=border0 type=checkbox name=zapamietaj_oz id=zapamietaj_oz ";
			if ($_REQUEST[p6]=='true') echo "checked=checked";
			echo ">";		
			echo "<a href=# class=normalfont onClick=\"if (document.getElementById('zapamietaj_oz').checked) { document.getElementById('zapamietaj_oz').checked=false; } else { document.getElementById('zapamietaj_oz').checked=true; } \"> Zapamiętaj dane</a>";
		echo "</span>";
		
		_td();
	_tr();
	tr_();
		td("120;rt;Temat zgłoszenia");
		td_(";;;");
			echo "<input tabindex=-1 type=text name=hd_temat id=hd_temat readonly ";
			if ($_REQUEST[p7]!='') { $temat=$_REQUEST[p7]; } else { $temat=''; }
			echo "value='$temat' ";
			echo "size=80 style='border-width:0px;background-color:transparent;font-weight:bold; font-family:tahoma;'>";
		_td();
	_tr();
	tr_();
		td("120;rt;Treść zgłoszenia");
		td_(";;;");
			echo "<textarea class=wymagane name=hd_tresc id=hd_tresc cols=63 rows=2 onFocus=\"check_up_in_list(); return false; \" onKeyUp=\"KopiujDo1Entera(this.value,'hd_temat'); ex1(this); if (this.value!='') { document.getElementById('sl_d').style.display=''; document.getElementById('tr_clear').style.display=''; } else { document.getElementById('sl_d').style.display='none'; document.getElementById('tr_clear').style.display='none'; } \" onBlur=\"ZamienTekst(this.id); KopiujDo1Entera(this.value,'hd_temat'); \">";
			if ($_REQUEST[p8]!='') { $tresc=$_REQUEST[p8]; } else { $tresc=''; }
			echo $tresc;
			echo "</textarea>";
			
			echo "<a title=' Dodaj treść do słownika' style='display:none' id=sl_d class=imgoption  onClick=\"newWindow_byName('_dodaj_do_slownika',700,400,'hd_d_slownik_tresc.php?akcja=fastadd'); return false;\"><input class=imgoption type=image src=img/slownik_dodaj.gif></a>";
			
			echo "<a title=' Wybierz treść ze słownika' id=sl_wybierz class=imgoption  onClick=\"newWindow_byName_r('_wybierz_ze_slownika',700,600,'hd_z_slownik_tresci.php?akcja=wybierz&p6=".urlencode($currentuser)."'); return false;\"><input class=imgoption type=image src=img/ew_prosty.png></a>";
			
			echo "<a id=tr_clear href=# style='display:none' onclick=\"if (confirm('Czy wyczyścić treść zgłoszenia ?')) { document.getElementById('hd_tresc').value=''; KopiujDo1Entera(document.getElementById('hd_tresc').value,'hd_temat'); ex1(document.getElementById('hd_tresc')); document.getElementById('tr_clear').style.display='none'; document.getElementById('sl_d').style.display='none'; document.getElementById('hd_tresc').focus(); }\" title=' Wyczyść treść zgłoszenia '><img src=img/czysc.gif border=0></a>";
			//echo "<a id=tr_clear href=# style='display:none' onclick=\"document.getElementById('hd_tresc').value=''; KopiujDo1Entera(document.getElementById('hd_tresc').value,'hd_temat'); ex1(document.getElementById('hd_tresc')); document.getElementById('tr_clear').style.display='none'; document.getElementById('sl_d').style.display='none'; document.getElementById('hd_tresc').focus();\" title=' Wyczyść treść zgłoszenia '><img src=img/czysc.gif border=0></a>";
			
		_td();
	_tr();	

	tr_();
		td("150;rt;Kategoria");
		td_(";;;");	
			echo "<select class=wymagane id=kat_id name=kat_id onChange=\"MakePodkategoriaList2(this.options[this.options.selectedIndex].value); StatusChanged2(this.value,document.getElementById('status_id').value,'Kategoria'); GenerateOnClickEventForSlownikTresci(); ShowHints(document.getElementById('kat_id').value,document.getElementById('podkat_id').value);\" onBlur=\"SzybkiSkokZKategorii1(this.value);\" onKeyUp=\"if ((event.keyCode==13)) document.getElementById('podkat_id').focus(); \" />\n";
			echo "<option value=''></option>\n";	
			echo "<option value='1'>Konsultacje</option>\n";
			//echo "<option value='2'>Awarie</option>\n";
			echo "<option value='3'>Prace zlecone w ramach umowy</option>\n";
			echo "<option value='7'>Konserwacja</option>\n";
			echo "<option value='4'>Prace zlecone poza umową</option>\n";
			echo "<option value='5'>Prace na potrzeby Postdata</option>\n";
			echo "</select>\n";	
			
			echo "<span id=WyslijEmail style='display:none'>";
			if ($WlaczMaile=='1') {
				echo "<input class=border0 type=checkbox id=WyslijEmailCheckbox name=WyslijEmailCheckbox>";
				echo "<a href=# class=normalfont onClick=\"if (document.getElementById('WyslijEmailCheckbox').checked) { document.getElementById('WyslijEmailCheckbox').checked=false; } else { document.getElementById('WyslijEmailCheckbox').checked=true; } \"> Wyślij email do koordynatora</a>";
			} else {
				echo "<input style='border:0px' type=hidden id=WyslijEmailCheckbox name=WyslijEmailCheckbox>";
			}
			echo "</span>";
						
		_td();
	_tr();

	tr_();
		td("150;rt;Podkategoria");
		td_(";;;");	
			echo "<select class=wymagane id=podkat_id name=podkat_id disabled=true onKeyUp=\"if (event.keyCode==13) document.getElementById('priorytet_id').focus(); \" onChange=\"GenerateSubPodkategoriaList(document.getElementById('kat_id').value,document.getElementById('podkat_id').value); GenerateOnClickEventForSlownikTresci(); ShowHints(document.getElementById('kat_id').value,document.getElementById('podkat_id').value);\"/>\n";
			echo "<option value=''></option>\n";		
			echo "</select>\n";			
		_td();
	_tr();
	
	echo "<tr style='display:none' id=tr_pk_hint>";
		td("140;rt;");
		td_(";;;");	
			echo "<div title='Kliknij, aby ukryć podpowiedzi' id=Hint style='border:1px solid #67ADE7; background-color:#B7D8F3; display:none; padding:5px;' onClick=\"this.style.display='none'; document.getElementById('tr_pk_hint').style.display='none';\" ></div>";
		_td();
	_tr();	

		
	echo "<tr style='display:none'>";
		td("150;rt;Priorytet");
		td_(";;;");	
			echo "<select class=wymagane id=priorytet_id name=priorytet_id disabled=true onKeyUp=\"if (event.keyCode==13) document.getElementById('status_id').focus(); \" />\n";
			echo "<option value=''></option>\n";
			echo "</select>\n";		
		_td();
	_tr();

	tr_();
		td("140;rt;Podkategoria (poziom 2)");
		td_(";;;");	
			echo "<select class=wymagane id=sub_podkat_id name=sub_podkat_id disabled=true />\n";
			echo "<option value=''></option>\n";
			echo "</select>\n";
		_td();
	_tr();
		
	tr_();
		td("150;rt;Status");
		td_(";;;");	
			echo "<select class=wymagane id=status_id name=status_id disabled=true  onChange=\"StatusChanged2(document.getElementById('kat_id').value,this.value,'Status'); SzybkiSkokZeStatusu1(this.value); \" onKeyUp=\"if ((event.keyCode==13) && (document.getElementById('PowiazaneZWyjazdem').style.display=='none')) document.getElementById('PrzypiszDoOsobyValue').focus(); SzybkiSkokZeStatusu1(this.value); \" />\n";
			echo "<option value=''></option>\n";
			echo "</select>\n";
			
			
			echo "<span id=PrzypiszDoOsoby style='display:none'>";
			echo "&nbsp;";
			$result44 = mysql_query("SELECT user_first_name,user_last_name FROM $dbname.serwis_uzytkownicy WHERE (belongs_to=$es_filia) and (user_locked=0) ORDER BY user_last_name ASC", $conn_hd) or die($k_b);
				$count_rows = mysql_num_rows($result44);
				echo "<select class=wymagane id=PrzypiszDoOsobyValue name=PrzypiszDoOsobyValue onKeyUp=\"if ((event.keyCode==13) && (document.getElementById('PowiazaneZWyjazdem').style.display=='none')) document.getElementById('submit').focus(); \"/>\n";
				
				while (list($temp_imie,$temp_nazwisko) = mysql_fetch_array($result44)) {
					$imieinazwisko = $temp_imie . " " . $temp_nazwisko;
					echo "<option value='$imieinazwisko' ";
					if ($currentuser==$imieinazwisko) echo " SELECTED";
					echo ">$imieinazwisko</option>\n"; 
				}
				echo "</select>\n";
			
			echo "</span>";

			echo "<span id=Zdiagnozowany style='display:none'>";
				echo "Zdiagnozowany: ";
				echo "<select id=SelectZdiagnozowany name=SelectZdiagnozowany>\n";
				echo "<option value=''></option>\n";
				echo "<option value='1'>TAK</option>\n";
				echo "<option value='0'>NIE</option>\n";
				echo "</select>";
			echo "</span>";
			
			echo "&nbsp;";
			echo "<div id=PowiazaneZWyjazdem style='display:none'>";
			echo "<font color=red>Jeżeli chcesz powiązać zgłoszenie seryjne z wyjazdem - musisz to zrobić przez obsługę zgłoszenia seryjnego</font>";
			echo "</div>";
			
		_td();
	_tr();

	echo "<tr id=StatusZakonczony style='display:none'>";
		td("120;rt;Czas wykonywania");
		td_(";;;");
			echo "<input style=text-align:right type=text class=wymagane id=czas_wykonywania_h name=czas_wykonywania_h value='0' maxlength=2 size=2 onKeyPress=\"return filterInput(1, event, false); \" onFocus=\"this.select(); return false;\" onBlur=\"if (this.value>8) if (confirm('Wpisano więcej niż 8 godzin na wykonanie kroku. Potwierdzasz poprawność ?')) { return true; } else { this.value='0'; return false; } \" /> godzin";
			echo "&nbsp;";
			echo "<input style=text-align:right type=text class=wymagane id=czas_wykonywania_m name=czas_wykonywania_m value='0' maxlength=2 size=2 onKeyPress=\"return filterInput(1, event, false); \" onFocus=\"this.select(); return false;\" /> minut";
	
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class=border0 type=checkbox name=CzasWykonywaniaLaczny id=CzasWykonywaniaLaczny />";
			
			echo "<a href=# class=normalfont onClick=\"if (document.getElementById('CzasWykonywaniaLaczny').checked) { document.getElementById('CzasWykonywaniaLaczny').checked=false; } else { document.getElementById('CzasWykonywaniaLaczny').checked=true; } \"> Łączny czas dla wszystkich komórek</a>";

			echo "<div id=StatusChanged_prepare>";
			echo "</div>";
			nowalinia();
		_td();
	echo "</tr>";
	
	echo "<tr id=WpiszWyjazdTrasa style='display:none'>";
		td("150;rt;<font color=blue>Trasa wyjazdowa</font>");
		td_(";;;");
			echo "<input class=buttons type=button onClick=\"newWindow_r(400,300,'hd_edycja_trasy_wyjazdowej.php'); \" value='Edycja trasy wyjazdowej'/><br />";
			echo "<textarea class=wymagane id=trasa name=trasa cols=50 rows=3></textarea>";
			echo "<a href=# class=normalfont onclick=\"document.getElementById('trasa').value=''; \" title=' Wyczyść trasę wyjazdową'> <img src=img/czysc.gif border=0 width=16 height=16></a>";
		_td();
	echo "</tr>";
	echo "<tr id=WpiszWyjazdKm style='display:none'>";
		td("150;rt;<font color=blue>Przejechane km</font>");
		td_(";;;");
			echo "<input class=wymagane id=km name=km style=text-align:right type=text size=3 maxlength=3 onKeyPress=\"return filterInput(1, event, false); \"> km<br /><br />";
		_td();
	echo "</tr>";

	echo "<tr id=OsobaPotwierdzajacaZamkniecie style='display:none' >";
		td("150;r;Osoba potwierdzająca<br />zamknięcie");
		td_(";;;");
			echo "<input type=text name=hd_opz id=hd_opz size=38 maxlength=30 onKeyPress=\"return filterInput(0, event, false, ' ęóąśłżźćńĘÓĄŚŁŻŹĆŃ'); \" onFocus=\"this.select();\" onBlur=\"cUpper(this); if (OdrzucNiedozwolone('hd_opz',document.getElementById('hd_opz').value)==1) return false;\" >";
			echo "&nbsp;<input type=button class=buttons value=' Osoba zgłaszająca ' onClick=\"document.getElementById('hd_opz').value=document.getElementById('hd_oz').value;\">";
		_td();
	echo "</tr>";
	
	echo "<tr id=ZasadnoscZgloszenia style='display:none'>";
		td("120;rt;Zasadność zgłoszenia");
		td_(";;;");
			echo "<input style='border:0px' type=radio name=zasadne id=zasadne value='TAK' CHECKED>TAK";
			echo "&nbsp;&nbsp;<input style='border:0px' type=radio name=zasadne id=zasadne value='NIE'>NIE";
		_td();
	echo "</tr>";	
tbl_empty_row(1);
endtable();
startbuttonsarea("right");
oddziel();
echo "<span style='float:left'>";
echo "<input class=border0 type=checkbox name=czy_synchronizowac id=czy_synchronizowac checked=checked><a tabindex=-1 class=normalfont href=# onClick=\"if (document.getElementById('czy_synchronizowac').checked) { document.getElementById('czy_synchronizowac').checked=false; } else { document.getElementById('czy_synchronizowac').checked=true; }\"><font color=red>&nbsp;Widoczne dla Poczty</font></a>";

echo "<span id=show_ww style='display:none'>";
	echo " | <input class=border0 type=checkbox name=wymaga_wyjazdu id=wymaga_wyjazdu><a tabindex=-1 class=normalfont href=# onClick=\"if (document.getElementById('wymaga_wyjazdu').checked) { document.getElementById('wymaga_wyjazdu').checked=false; return false; } else { document.getElementById('wymaga_wyjazdu').checked=true; return false; }\"><font color=blue>&nbsp;Wymagają wyjazdu</font></a>";
echo "</span>";

echo "</span>";

echo "<input id=submit type=submit class=buttons style='font-weight:bold;' name=submit value='Zapisz' />";
echo "<input id=reset type=button class=buttons name=reset value=\"Wyczyść zgłoszenie\" onClick=\"pytanie_wyczysc_seryjne('Wyczyścić formularz ?'); \" />";
echo "<input id=anuluj class=buttons type=button onClick=\"pytanie_anuluj('Potwierdzasz anulowanie wpisanego zgłoszenia ?');\" value=Anuluj>";

//echo "<span id=Saving style=display:none><b><font color=red>Trwa zapisywanie zgłoszeń... proszę czekać&nbsp;</font></b><input class=imgoption type=image src=img/loader.gif></span>";

endbuttonsarea();
echo "<input type=hidden name=unique_nr1 value='$unique_nr1' />";

echo "<input type=hidden name=tuser value='$currentuser'>";
echo "<input type=hidden id=stage value='$_GET[stage]' />";

echo "<input type=hidden name=hdtu value='KOI'>";

_form();

echo "</div>";

}
?>

<script>ShowWaitingMessage('Trwa zapisywanie danych... proszę czekać', 'Saving');</script>

<script type="text/javascript" src="js/jquery/entertotab_min.js"></script>

<script type='text/javascript'>
	EnterToTab.init(document.forms.hd_dodaj_zgl_s, false);
</script>
<script>ShowHints(document.getElementById('kat_id').value,document.getElementById('podkat_id').value);</script>

<script>HideWaitingMessage();</script>
</body>
</html>