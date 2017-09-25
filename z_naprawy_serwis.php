<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php'); 
?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php
$dddd = Date('Y-m-d H:i:s');
$_POST=sanitize($_POST);

if ($_POST[tstatus1]==0) {
	$dddd = $_POST[dzstatusu]." 00:00:00";
	
	if (strlen($_POST[duwagi_old])>0) { $noweuwagi = $_POST[duwagi_old]."\n".$_POST[duwagi]; } else { $noweuwagi = $_POST[duwagi]; }
	
	if (strlen($noweuwagi)>0) {
		$sql_e1="UPDATE $dbname.serwis_naprawa SET naprawa_status = '$_POST[tstatus1]', naprawa_nwwz_osoba = '$currentuser', naprawa_nwwz_data = '$dddd', naprawa_uwagi='".nl2br($noweuwagi)."', naprawa_uwagi_sa='1' WHERE naprawa_id='$_POST[uid]' LIMIT 1";
	} else {
		$sql_e1="UPDATE $dbname.serwis_naprawa SET naprawa_status = '$_POST[tstatus1]', naprawa_nwwz_osoba = '$currentuser', naprawa_nwwz_data = '$dddd', naprawa_uwagi='', naprawa_uwagi_sa='0' WHERE naprawa_id='$_POST[uid]' LIMIT 1";
	}
	
	if (mysql_query($sql_e1, $conn)) { 
		$sql_e = "UPDATE $dbname.serwis_ewidencja SET ewidencja_status = '$_POST[tstatus1]' WHERE (ewidencja_id='$_POST[ew_id]') LIMIT 1";
		$wynik2 = mysql_query($sql_e, $conn) or die($k_b);
		
		if ($_REQUEST[hdzglid____]!='') {	
		
			list($GetBelongsToFromHD)=mysql_fetch_array(mysql_query("SELECT belongs_to FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id=$_REQUEST[hdzglid]) LIMIT 1"));

			// dodanie kroku do zgłoszenia 
			if ($_SESSION[dodaj_krok_do_zgloszenia_pow_z_naprawa]!='poprawnie') {

				$zgl_seryjme_unique_nr = Date('YmdHis')."".rand_str();
				$dddd = date("Y-m-d H:i:s");
				$dd = date("Y-m-d H:i:s");
				
				$NowyStatusZgloszenia='3B';
				
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
	
				$sql = "INSERT INTO $dbname_hd.hd_zgloszenie_szcz VALUES ('',$last_nr,'$zgl_seryjme_unique_nr',$last_nr_kroku,'$czas_START_STOP',$d_cw,'$_REQUEST[zs_data] $_REQUEST[zs_time]','$NowyStatusZgloszenia','$wykonane_czynnosci','$osoba_przypisana','$DodatkoweOsoby',0,'9','9',$bylwyjazd,'$dddd','$currentuser',1,$przejechane_km,$awaria_z_przesunieciem,'','','9','9','','',$czy_rozwiazany,0,$es_filia)";
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
				
					$trasaw = $_REQUEST[trasa];
					$wdata = $_REQUEST[hd_wyjazd_data];
					$przejechane_km = $_REQUEST[km];
					
					if ($_REQUEST[hd_wyjazd_rp]=='S') {
						$trasaw = 'wyjazd samochodem służbowym';
						$wdata = $_REQUEST[hddz];
						$przejechane_km = 0;
					}
	
					$sql = "INSERT INTO $dbname_hd.hd_zgloszenie_wyjazd VALUES ('','$zgl_seryjme_unique_nr','$wdata','$trasaw',$przejechane_km,'$currentuser','$_REQUEST[hd_wyjazd_rp]',1,$es_filia)";
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
				if (($przejechane_km!=0) && ($przejechane_km!='')) $razem_km += $przejechane_km;
				
				$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_razem_km=$razem_km WHERE ((zgl_widoczne=1) and (belongs_to='$GetBelongsToFromHD') and (zgl_id='$_REQUEST[hdzglid]')) LIMIT 1";
				//echo "<br />$sql";
				$result = mysql_query($sql, $conn_hd) or die($k_b);
				
			}
		}
		?><script>
			if (opener) opener.location.reload(true); 
			self.close();
			<?php if ($_POST[from]=='hd') { ?>
						
					if (readCookie('wpisane_wc_<?php echo $_POST[hd_zgl_nr]; ?>')==null) {
						SetCookie('wpisane_wc_<?php echo $_POST[hd_zgl_nr]; ?>','<?php echo $_POST[duwagi]; ?>'); 
					} else {
						if (confirm('Czy przenieść wykonane czynności z protokołu do wykonanych czynności w obsługiwanym kroku zgłoszenia ?')) 
							SetCookie('wpisane_wc_<?php echo $_POST[hd_zgl_nr]; ?>','<?php echo $_POST[duwagi]; ?>'); 
					}
					
					//if (readCookie('wpisane_wc_<?php echo $_POST[hd_zgl_nr]; ?>')==null) SetCookie('wpisane_wc_<?php echo $_POST[hd_zgl_nr]; ?>','<?php echo $_POST[duwagi]; ?>'); 
			<?php } ?>		

			</script><?php
	} else {
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php	
	}
}
if ($_POST[tstatus1]==1) {
	$result1 = mysql_query("SELECT naprawa_nazwa,naprawa_model,naprawa_sn FROM $dbname.serwis_naprawa WHERE (naprawa_id=$uid) LIMIT 1", $conn) or die($k_b);
	list($temp_nazwa,$temp_model,$temp_sn)=mysql_fetch_array($result1);
	pageheader("Naprawa w serwisie zewnętrznym");
	infoheader("".$temp_nazwa." ".$temp_model." (SN: ".$temp_sn.")");
	if ($_REQUEST[hdzglid]!='') naprawaheader("<center>Ta naprawa jest powiązana ze zgłoszeniem nr $_REQUEST[hdzglid] w bazie Helpdesk</center>");
	starttable();
	echo "<form name=edu action=z_naprawy_status_zmien.php method=POST onSubmit=\"return pytanie_zatwierdz_sz('Potwierdzasz poprawność wprowadzonych danych ?');\">";

	include_once('systemdate.php');

	tr_();
			td(";r;Wybierz serwis");
			td_(";;");
				$result = mysql_query("SELECT fz_id,fz_nazwa FROM $dbname.serwis_fz WHERE (fz_is_fs='on') ORDER BY fz_nazwa ASC", $conn) or die($k_b);
				echo "<select name=tfs>\n"; 					 				
				while (list($temp_id1,$temp_nazwa1)=mysql_fetch_array($result)) {
					echo "<option value='$temp_nazwa1'>$temp_nazwa1</option>\n"; 
				}
				echo "</select>\n"; 
			_td();
		_tr();
		tr_();
			td(";r;Wybierz firmę kurierską");
			td_(";;");
				$result = mysql_query("SELECT fz_id,fz_nazwa FROM $dbname.serwis_fz WHERE (fz_is_fk='on') ORDER BY fz_nazwa ASC", $conn) or die($k_b);
				echo "<select name=tfk>\n"; 					 				
				echo "<option value=-1>nie dotyczy</option>\n";
				while (list($temp_id2,$temp_nazwa2)=mysql_fetch_array($result)) {
					echo "<option value='$temp_nazwa2'>$temp_nazwa2</option>\n"; 
				}
				echo "</select>\n"; 
			_td();
		_tr();
		tr_();
			td(";r;Numer listu przewozowego");
			td_(";;;");
				echo "<input type=text name=tnlp>";
			_td();
		_tr();
		tr_();
			td(";r;Termin naprawy");
			td_(";;;");
				$rok = Date('Y');
				$miesiac = Date('m');
				$dzien = Date('d');			
				$dzien+=14;
				$datacala1 = mktime (0,0,0,date("m"),date("d")+14,date("Y"));
				$datacala = date("Y-m-d",$datacala1);
				echo "<input size=10 maxlength=10 type=text id=ttn name=ttn value=$datacala onkeypress='return handleEnter(this, event);' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\">";
				echo "<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif width=16 height=16 border=0></a>";
				if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('ttn').value='".Date('Y-m-d')."'; return false;\">";
			_td();
		_tr();
	tbl_empty_row();
	endtable();

if ($_REQUEST[hdzglid____]!='')  {
	infoheader("<center>Wprowadź zmiany w zgłoszeniu nr $_REQUEST[hdzglid] <input type=button class=buttons onclick=\"newWindow_r(800,600,'hd_o_zgloszenia.php?action=info&id=$_REQUEST[hdzglid]&nr=$_REQUEST[hdzglid]'); return false;\" value=\"Podgląd zgłoszenia\"></center>");
	starttable();
		tbl_empty_row();
		tr_();
			td(";r;Nowy status zgłoszenia");
			td_(";;;");	
				echo "<b>w serwisie zewnętrznym</b>";
			_td();
		_tr();
		tr_();
			td(";rt;Wykonane czynności");
			td_(";;;");	
				echo "<textarea style='background-color:transparent;' name=zs_wcz id=zs_wcz cols=55 rows=2 readonly>$_REQUEST[zs_wcz]</textarea>";
				echo "<span id=PowiazaneZWyjazdem style=display:''>";
			_td();
		_tr();
		echo "<tr>";
			td(";r;Czas wykonywania");
			td_(";;;");
				echo "<b>$_REQUEST[czas_wykonywania_h] godzin $_REQUEST[czas_wykonywania_m] minut</b>";
			_td();
		echo "</tr>";
if ($_REQUEST[PozwolWpisacKm]=='on') {
		echo "<tr id=WpiszWyjazdTrasa>";
			td("150;rt;<br /><br /><b>Trasa wyjazdowa</b>");
			td_(";;;");
				echo "<textarea style='background-color:transparent;' id=trasa name=trasa cols=50 rows=3 readonly>$_REQUEST[trasa]</textarea>";
			_td();
		echo "</tr>";
		echo "<tr id=DataWyjazdu>";
			td("150;rt;<b>Data wyjazdu</b>");
			td_(";;;");
				echo "<b>$_REQUEST[zs_data]</b>";
			_td();
		echo "</tr>";
		echo "<tr id=WpiszWyjazdKm>";
			td("150;rt;<b>Przejechane km</b>");
			td_(";;;");
				echo "<b>$_REQUEST[km]</b>";
			_td();
		echo "</tr>";
}
		tbl_empty_row();
	endtable();

}
//print_r($_REQUEST);

	startbuttonsarea("right");
	echo "<span style='float:left'>";
	echo "<input class=buttons type=button onClick=window.location.href='z_naprawy_status.php?id=$_REQUEST[uid]&oldstatus=$_REQUEST[tstatus1]&hdzglid=$_REQUEST[hdzglid]&zs_wcz=".urlencode($_REQUEST[zs_wcz])."&duwagi=".urlencode($_REQUEST[duwagi])."&czas_wykonywania_h=$_REQUEST[czas_wykonywania_h]&czas_wykonywania_m=$_REQUEST[czas_wykonywania_m]&PozwolWpisacKm=$_REQUEST[PozwolWpisacKm]&trasa=".urlencode($_REQUEST[trasa])."&hd_wyjazd_data=$_REQUEST[hd_wyjazd_data]&km=$_REQUEST[km]&obcezgloszenie=$_REQUEST[obcezgloszenie]&cs=$_REQUEST[cs]&hd_zgl_nr=$_REQUEST[hd_zgl_nr]&from=$_REQUEST[from]' value='Zmień sposób naprawy'>";
	echo "</span>";
	addbuttons("zapisz");
	addbuttons("anuluj");
	endbuttonsarea();
	echo "<input size=30 type=hidden name=uid value='$_POST[uid]'>";
	echo "<input type=hidden name=ew_id value='$_POST[ew_id]'>";
	echo "<input type=hidden name=ow value='$currentuser'>";
	echo "<input type=hidden name=dw value='$dddd'>";
	echo "<input type=hidden name=stat value='$_POST[tstatus1]'>";
	echo "<input type=hidden name=duwagi_old value='$_POST[duwagi_old]'>";
	echo "<input type=hidden name=duwagi value='$_POST[duwagi]'>";
	echo "<input type=hidden name=dzstatusu value='$_POST[dzstatusu]'>";

	echo "<input type=hidden name=zs_data value='$_REQUEST[zs_data]'>";
	echo "<input type=hidden name=zs_time value='$_REQUEST[zs_time]'>";
	echo "<input type=hidden name=zs_wcz value='$_REQUEST[zs_wcz]'>";	
	echo "<input type=hidden name=hdzglid value='$_REQUEST[hdzglid]'>";
	echo "<input type=hidden name=czas_wykonywania_h value='$_REQUEST[czas_wykonywania_h]'>";
	echo "<input type=hidden name=czas_wykonywania_m value='$_REQUEST[czas_wykonywania_m]'>";
	echo "<input type=hidden name=PozwolWpisacKm value='$_REQUEST[PozwolWpisacKm]'>";	
	echo "<input type=hidden name=trasa value='$_REQUEST[trasa]'>";
	echo "<input type=hidden name=hd_wyjazd_data value='$_REQUEST[hd_wyjazd_data]'>";
	echo "<input type=hidden name=km value='$_REQUEST[km]'>";
	echo "<input type=hidden name=obcezgloszenie value='$_REQUEST[obcezgloszenie]'>";
	echo "<input type=hidden name=hd_zgl_nr value='$_REQUEST[hd_zgl_nr]'>";
	echo "<input type=hidden name=cs value='$_REQUEST[cs]'>";
	echo "<input type=hidden name=from value='$_REQUEST[from]'>";
	echo "<input type=hidden name=duwagi value='$_POST[duwagi]'>";	
	
	_form();
}
if ($_POST[tstatus1]==2) {
	$result1 = mysql_query("SELECT naprawa_nazwa,naprawa_model,naprawa_sn FROM $dbname.serwis_naprawa WHERE (naprawa_id=$uid) LIMIT 1", $conn) or die($k_b);
	list($temp_nazwa,$temp_model,$temp_sn)=mysql_fetch_array($result1);
	pageheader("Naprawa na rynku lokalnym");
	infoheader("".$temp_nazwa." ".$temp_model." (SN: ".$temp_sn.")");
	if ($_REQUEST[hdzglid]!='') naprawaheader("<center>Ta naprawa jest powiązana ze zgłoszeniem nr $_REQUEST[hdzglid] w bazie Helpdesk</center>");
	starttable();
	echo "<form name=edu action=z_naprawy_lokalny.php method=POST onSubmit=\"return pytanie_zatwierdz_sl('Potwierdzasz poprawność wprowadzonych danych ?');\">";

	include_once('systemdate.php');
	
		tr_();
			td(";r;Wybierz serwis");
			td_(";;");
				$result = mysql_query("SELECT fz_id,fz_nazwa FROM $dbname.serwis_fz WHERE (fz_is_fs='on') ORDER BY fz_nazwa ASC", $conn) or die($k_b);
				echo "<select name=tfs>\n"; 					 				
				while (list($temp_id1,$temp_nazwa1)=mysql_fetch_array($result)) {
					echo "<option value='$temp_nazwa1'>$temp_nazwa1</option>\n"; 
				}
				echo "</select>\n"; 
			_td();
		_tr();
		tr_();
			td(";r;Termin naprawy");
			td_(";;;");
				$rok = Date('Y');
				$miesiac = Date('m');
				$dzien = Date('d');			
				$dzien+=14;
				$datacala1  = mktime (0,0,0,date("m")  ,date("d")+14,date("Y"));
				$datacala = date("Y-m-d",$datacala1);
				echo "<input size=10 maxlength=10 type=text id=ttn name=ttn value=$datacala onkeypress='return handleEnter(this, event);' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\">";
				echo "<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif width=16 height=16 border=0></a>";
				if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('ttn').value='".Date('Y-m-d')."'; return false;\">";				
			_td();
		_tr();
	tbl_empty_row(2);
	endtable();

if ($_REQUEST[hdzglid___]!='')  {
	infoheader("<center>Wprowadź zmiany w zgłoszeniu nr $_REQUEST[hdzglid] <input type=button class=buttons onclick=\"newWindow_r(800,600,'hd_o_zgloszenia.php?action=info&id=$_REQUEST[hdzglid]&nr=$_REQUEST[hdzglid]'); return false;\" value=\"Podgląd zgłoszenia\"></center>");
	starttable();
		tbl_empty_row();
		tr_();
			td(";r;Nowy status zgłoszenia");
			td_(";;;");	
				echo "<b>w serwisie zewnętrznym</b>";
			_td();
		_tr();
		tr_();
			td(";rt;Wykonane czynności");
			td_(";;;");	
				echo "<textarea style='background-color:transparent;' name=zs_wcz id=zs_wcz cols=55 rows=2 readonly>$_REQUEST[zs_wcz]</textarea>";
				echo "<span id=PowiazaneZWyjazdem style=display:''>";
			_td();
		_tr();
		echo "<tr>";
			td(";r;Czas wykonywania");
			td_(";;;");
				echo "<b>$_REQUEST[czas_wykonywania_h] godzin $_REQUEST[czas_wykonywania_m] minut</b>";
			_td();
		echo "</tr>";
if ($_REQUEST[PozwolWpisacKm]=='on') {
		echo "<tr id=WpiszWyjazdTrasa>";
			td("150;rt;<br /><br /><b>Trasa wyjazdowa</b>");
			td_(";;;");
				echo "<textarea style='background-color:transparent;' id=trasa name=trasa cols=50 rows=3 readonly>$_REQUEST[trasa]</textarea>";
			_td();
		echo "</tr>";
		echo "<tr id=DataWyjazdu>";
			td("150;rt;<b>Data wyjazdu</b>");
			td_(";;;");
				echo "<b>$_REQUEST[zs_data]</b>";
			_td();
		echo "</tr>";
		echo "<tr id=WpiszWyjazdKm>";
			td("150;rt;<b>Przejechane km</b>");
			td_(";;;");
				echo "<b>$_REQUEST[km]</b>";
			_td();
		echo "</tr>";
}
		tbl_empty_row();
	endtable();

}	
	startbuttonsarea("right");
	echo "<span style='float:left'>";
	echo "<input class=buttons type=button onClick=window.location.href='z_naprawy_status.php?id=$_REQUEST[uid]&oldstatus=$_REQUEST[tstatus1]&hdzglid=$_REQUEST[hdzglid]&zs_wcz=".urlencode($_REQUEST[zs_wcz])."&duwagi=".urlencode($_REQUEST[duwagi])."&czas_wykonywania_h=$_REQUEST[czas_wykonywania_h]&czas_wykonywania_m=$_REQUEST[czas_wykonywania_m]&PozwolWpisacKm=$_REQUEST[PozwolWpisacKm]&trasa=".urlencode($_REQUEST[trasa])."&hd_wyjazd_data=$_REQUEST[hd_wyjazd_data]&km=$_REQUEST[km]&obcezgloszenie=$_REQUEST[obcezgloszenie]&cs=$_REQUEST[cs]&hd_zgl_nr=$_REQUEST[hd_zgl_nr]&from=$_REQUEST[from]' value='Zmień sposób naprawy'>";
	echo "</span>";
	addbuttons("zapisz");
	addbuttons("anuluj");
	endbuttonsarea();
	echo "<input size=30 type=hidden name=uid value='$_POST[uid]'>";
	echo "<input type=hidden name=ew_id value=$_POST[ew_id]>";	
	echo "<input type=hidden name=ow value='$currentuser'>";
	echo "<input type=hidden name=dw value='$dddd'>";
	echo "<input type=hidden name=stat value='$_POST[tstatus1]'>";
	echo "<input type=hidden name=duwagi_old value='$_POST[duwagi_old]'>";
	echo "<input type=hidden name=duwagi value='$_POST[duwagi]'>";	
	echo "<input type=hidden name=dzstatusu value='$_POST[dzstatusu]'>";
	
	echo "<input type=hidden name=zs_data value='$_REQUEST[zs_data]'>";	
	echo "<input type=hidden name=zs_time value='$_REQUEST[zs_time]'>";
	echo "<input type=hidden name=zs_wcz value='$_REQUEST[zs_wcz]'>";	
	echo "<input type=hidden name=hdzglid value='$_REQUEST[hdzglid]'>";
	echo "<input type=hidden name=czas_wykonywania_h value='$_REQUEST[czas_wykonywania_h]'>";
	echo "<input type=hidden name=czas_wykonywania_m value='$_REQUEST[czas_wykonywania_m]'>";	
	echo "<input type=hidden name=PozwolWpisacKm value='$_REQUEST[PozwolWpisacKm]'>";
	echo "<input type=hidden name=trasa value='$_REQUEST[trasa]'>";
	echo "<input type=hidden name=hd_wyjazd_data value='$_REQUEST[hd_wyjazd_data]'>";
	echo "<input type=hidden name=km value='$_REQUEST[km]'>";
	echo "<input type=hidden name=obcezgloszenie value='$_REQUEST[obcezgloszenie]'>";	
	
	echo "<input type=hidden name=hd_zgl_nr value='$_REQUEST[hd_zgl_nr]'>";
	echo "<input type=hidden name=cs value='$_REQUEST[cs]'>";
	echo "<input type=hidden name=from value='$_REQUEST[from]'>";
	
	_form();
}
if ($_POST[tstatus1]==3) {
	$dddd = $_POST[dzstatusu]." ".Date("H:i:s");

	if (strlen($_POST[duwagi_old])>0) { $noweuwagi = $_POST[duwagi_old]."\n".$_POST[duwagi]; } else { $noweuwagi = $_POST[duwagi]; }
	
	$wykonane_naprawy = '';
	if ($_POST[duwagi]!='') $wykonane_naprawy = $_POST[duwagi];
	
//	if (strlen($noweuwagi)>0) {
	$sql_e1="UPDATE $dbname.serwis_naprawa SET naprawa_status = '$_POST[tstatus1]', naprawa_osoba_przyjmujaca_sprzet_z_serwisu = '$currentuser', naprawa_data_odbioru_z_serwisu = '$dddd', naprawa_wykonane_naprawy='".nl2br($wykonane_naprawy)."' WHERE naprawa_id='$_POST[uid]' LIMIT 1";
//	} else {
	//	$sql_e1="UPDATE $dbname.serwis_naprawa SET naprawa_status = '$_POST[tstatus1]', naprawa_osoba_przyjmujaca_sprzet_z_serwisu = '$currentuser', naprawa_data_odbioru_z_serwisu = '$dddd',naprawa_uwagi='', naprawa_uwagi_sa=0 WHERE naprawa_id='$_POST[uid]' LIMIT 1";
//	}
	
	if (mysql_query($sql_e1, $conn)) { 
		$wynik_getid = mysql_query("SELECT naprawa_ew_id FROM $dbname.serwis_naprawa WHERE naprawa_id='$_POST[uid]' LIMIT 1", $conn) or die($k_b);
		list($ewid_id)=mysql_fetch_array($wynik_getid);
		$sql_e = "UPDATE $dbname.serwis_ewidencja SET ewidencja_status = '$_POST[tstatus1]' WHERE (ewidencja_id=$ewid_id) LIMIT 1";
		$wynik2 = mysql_query($sql_e, $conn) or die($k_b);
		
		if ($_REQUEST[hdzglid____]!='') {	
		
			list($GetBelongsToFromHD)=mysql_fetch_array(mysql_query("SELECT belongs_to FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id=$_REQUEST[hdzglid]) LIMIT 1"));
			// dodanie kroku do zgłoszenia 
			if ($_SESSION[dodaj_krok_do_zgloszenia_pow_z_naprawa]!='poprawnie') {

				$zgl_seryjme_unique_nr = Date('YmdHis')."".rand_str();
				$dddd = date("Y-m-d H:i:s");
				$dd = date("Y-m-d H:i:s");
				
				$NowyStatusZgloszenia='6';
				
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

				$sql = "INSERT INTO $dbname_hd.hd_zgloszenie_szcz VALUES ('',$last_nr,'$zgl_seryjme_unique_nr',$last_nr_kroku,'$czas_START_STOP',$d_cw,'$_REQUEST[zs_data] $_REQUEST[zs_time]','$NowyStatusZgloszenia','$wykonane_czynnosci','$osoba_przypisana','$DodatkoweOsoby',0,'9','9',$bylwyjazd,'$dddd','$currentuser',1,$przejechane_km,$awaria_z_przesunieciem,'','','9','9','','',$czy_rozwiazany,0,$es_filia)";
				//echo "<br />$sql";

				$result = mysql_query($sql, $conn_hd) or die($k_b);
				
				// zaktualizuj zgl_czy_rozwiazany_problem w zgłoszeniu 
				$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_czy_rozwiazany_problem='$czy_rozwiazany' WHERE ((belongs_to='$es_filia') and (zgl_nr='$last_nr')) LIMIT 1";
				$result = mysql_query($sql, $conn_hd) or die($k_b);
				
				// *************************
					$_SESSION[dodaj_krok_do_zgloszenia_sprzet_naprawiony]='poprawnie';
				// *************************
			
					// jeżeli był wyjazd (START)
				if ($bylwyjazd==1) {
				
					$trasaw = $_REQUEST[trasa];
					$wdata = $_REQUEST[hd_wyjazd_data];
					$przejechane_km = $_REQUEST[km];
					
					if ($_REQUEST[hd_wyjazd_rp]=='S') {
						$trasaw = 'wyjazd samochodem służbowym';
						$wdata = $_REQUEST[hddz];
						$przejechane_km = 0;
					}
					
					$sql = "INSERT INTO $dbname_hd.hd_zgloszenie_wyjazd VALUES ('','$zgl_seryjme_unique_nr','$wdata','$trasaw',$przejechane_km,'$currentuser',1,$es_filia)";
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
				if (($przejechane_km!=0) && ($przejechane_km!='')) $razem_km += $przejechane_km;
				
				$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_razem_km=$razem_km WHERE ((zgl_widoczne=1) and (belongs_to='$GetBelongsToFromHD') and (zgl_id='$_REQUEST[hdzglid]')) LIMIT 1";
				//echo "<br />$sql";
				$result = mysql_query($sql, $conn_hd) or die($k_b);
				
			}
		}		
		?><script>
			if (opener) opener.location.reload(true); 
			self.close();		
		<?php if ($_POST[from]=='hd') { ?>

					if (readCookie('wpisane_wc_<?php echo $_POST[hd_zgl_nr]; ?>')==null) {
						SetCookie('wpisane_wc_<?php echo $_POST[hd_zgl_nr]; ?>','<?php echo $_POST[duwagi]; ?>'); 
					} else {
						if (confirm('Czy przenieść wykonane czynności z protokołu do wykonanych czynności w obsługiwanym kroku zgłoszenia ?')) 
							SetCookie('wpisane_wc_<?php echo $_POST[hd_zgl_nr]; ?>','<?php echo $_POST[duwagi]; ?>'); 
					}
						
					//if (readCookie('wpisane_wc_<?php echo $_POST[hd_zgl_nr]; ?>')==null) SetCookie('wpisane_wc_<?php echo $_POST[hd_zgl_nr]; ?>','<?php echo $_POST[duwagi]; ?>'); 
			<?php } ?>
			

			</script><?php		
	} else {
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php	
	}
}
if ($_POST[tstatus1]==5) {
/*
	$sql_e1="UPDATE $dbname.serwis_naprawa SET naprawa_status = '$_POST[tstatus1]', naprawa_osoba_oddajaca_sprzet = '$currentuser', naprawa_data_oddania_sprzetu = '$dddd' WHERE naprawa_id='$_POST[uid]' LIMIT 1";
	if (mysql_query($sql_e1, $conn)) { 
		if ($_POST[szid]>0) {
			$res = mysql_query("INSERT INTO $dbname.serwis_historia VALUES ('','$_POST[szid]','$_POST[tup1]','$currentuser','$dddd','zwrócono z','',$es_filia)", $conn) or die($k_b);
			$res = mysql_query("UPDATE $dbname.serwis_magazyn SET magazyn_status='0' WHERE magazyn_id='$_POST[szid]' LIMIT 1", $conn) or die($k_b);
		}		
		$wynik_getid = mysql_query("SELECT naprawa_ew_id FROM $dbname.serwis_naprawa WHERE naprawa_id='$_POST[uid]' LIMIT 1", $conn) or die($k_b);
		list($ewid_id)=mysql_fetch_array($wynik_getid);
		$wynik2 = mysql_query("UPDATE $dbname.serwis_ewidencja SET ewidencja_status = '9' WHERE (ewidencja_id=$ewid_id) LIMIT 1", $conn) or die($k_b);
		?><script>opener.location.reload(true); self.close(); </script><?php		
	} else {
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php	
		}
*/
}
?>
<script language="JavaScript">
var cal1 = new calendar1(document.forms['edu'].elements['ttn']);
	cal1.year_scroll = true;
	
function pytanie_zatwierdz_sz(message){ 

	if (confirm(message)) { 
		document.forms.edu.submit(); 
		return true; 
	} else return false; 
}	

function pytanie_zatwierdz_sl(message){ 

	if (confirm(message)) { 
		document.forms.edu.submit(); 
		return true; 
	} else return false; 
}	
</script>
</body>
</html>