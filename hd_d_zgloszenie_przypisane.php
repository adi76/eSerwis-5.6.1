<?php 
include_once('header.php');
include_once('cfg_helpdesk.php');
?>
<body>
<?php 

/*
if ($_REQUEST[dosiebie]=='1') {
	$sql_d1="UPDATE $dbname.serwis_zadania_pozycje SET pozycja_przypisane_osobie='$_REQUEST[cu]' WHERE (pozycja_id  IN (".$_REQUEST[nr]."))";
	if (mysql_query($sql_d1, $conn)) { 
			?><script>opener.location.reload(true); self.close(); </script><?php
	} else {
		?><script>info('Wystąpił błąd podczas wykonywania przypisania'); self.close(); </script><?php
	}
}
*/

function toUpper($string) {
	$string = strtr($string,'qwertyuiopasdfghjklzxcvbnm','QWERTYUIOPASDFGHJKLZXCVBNM');
	return (strtr($string, 'ęóąśłżźćń','ĘÓĄŚŁŻŹĆŃ' )); 
}; 


if ($submit) { 

	?><script>ShowWaitingMessage('Trwa zapisywanie danych... proszę czekać', 'Saving');</script><?php 

	$_POST=sanitize($_POST);
	$jedna_pozycja = explode(",", $_REQUEST[numery]);
	$ile_pozycji = count($jedna_pozycja);
	
if ($_SESSION['zgloszenie_dodano_'.$_REQUEST[unique_nr1].'']!='poprawnie') {	

	$OverWriteUwagi = 0;	if ($_REQUEST[nadpisz_uwagi]=='on') $OverWriteUwagi = 1;
	$OverWriteData = 0;		if ($_REQUEST[nadpisz_date]=='on') $OverWriteData = 1;
	
	//echo "$OverWriteUwagi $OverWriteData<br />";
	//print_r($_POST);

	// wymagalność wyjazdu
	$czy_wymagany_wyjazd = 0;
	$czy_wymagany_wyjazd_data = Date("Y-m-d H:i:s");
	$czy_wymagany_wyjazd_osoba = $currentuser;
	
	if ($_REQUEST[wymaga_wyjazdu]=='on') $czy_wymagany_wyjazd = 1;
	
	$czy_synchr = 0;
	if ($_REQUEST[czy_synchronizowac]=='on') $czy_synchr = 1;
	
	$czy_rozwiazany = 1;
	$czy_rozwiazany_data = $_REQUEST[hddz];
	
	$DataWpisu1 		= $_REQUEST[hddz];
	$GodzinaWpisu1 		= $_REQUEST[hdgz];
	$OsobaPrzypisana	= $currentuser;
	$d_cw 				= 0;
	$d_km				= 0;
	$zz 				= 1;
	$dodatkowe1 		= '';
	$dodatkowe2 		= '';
	$parent_zgloszenie	= 0;
	$hd_naprawa_id		= 0;
	$rekl_czy_jest_reklamacyjne 				= 0;
	$rekl_nr_zgl_reklamowanego 					= 0;
	$rekl_czy_utworzono_z_niego_reklamacyjne 	= 0;
	$czy_powiazane_z_wp	= 0;
	$hd_ss_id 			= '-1';
	
	// dla kroku zgłoszenia
	$CzasStartStop								= 'START';
	$wyk_czyn 									= "rejestracja zgłoszenia<br /><br />".$_REQUEST[hd_tresc];
	$emailSend 									= 0;
	$bylwyjazd									= 0;
	$dddd 										= date("Y-m-d H:i:s");
	$awaria_z_przesunieciem						= 0;
	$Zdiagnozowany 								= '9';
	
	$sql="SELECT pozycja_komorka, pozycja_id FROM $dbname.serwis_zadania_pozycje WHERE (pozycja_id  IN (".$_REQUEST[numery]."))";
	$result99 = mysql_query($sql, $conn) or die($k_b);
	
	$lista_zgloszen_z_numerami = '';
	$i = 1;
	while ($dane=mysql_fetch_array($result99)) { 

		$kn 	= $dane['pozycja_komorka'];
		$pozid 	= $dane['pozycja_id'];
		
		$sql_99 = "SELECT * FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.belongs_to=$es_filia) and (serwis_piony.pion_id=serwis_komorki.up_pion_id) and (serwis_komorki.up_nazwa='".$kn."') LIMIT 1";
				
		$wynik 		= mysql_query($sql_99,$conn) or die($k_b);
		$dane1		= mysql_fetch_array($wynik);

		$_tu		= $dane1['up_typ_uslugi'];
		$unique_nr 	= Date('YmdHis')."".rand_str();
		
		$pnk 		= $dane1['pion_nazwa']." ".$dane1['up_nazwa'];
		$_up_kat 	= $dane1['up_kategoria']; 
		$_up_pj 	= $dane1['up_przypisanie_jednostki']; 
		$week		= $dane1['up_working_time']; 
		
		$pnk = toUpper($pnk);
		
		$_telefon = '';

		$sql = "INSERT INTO $dbname_hd.hd_zgloszenie VALUES ('','','$_REQUEST[hdnzhadim]','$unique_nr','$DataWpisu1','$GodzinaWpisu1','$pnk','$_REQUEST[hd_oz]','$_telefon','$_REQUEST[hd_temat]','$_REQUEST[hd_tresc]','$_REQUEST[kat_id]','$_REQUEST[podkat_id]','$_REQUEST[sub_podkat_id]','2','$_REQUEST[status_id]','$OsobaPrzypisana','','',0,0,$d_cw,$d_km,1,'$currentuser',$zz,'','$dodatkowe1','$dodatkowe2','','$DataWpisu1','$parent_zgloszenie',$czy_synchr,'-1',$hd_naprawa_id,'0','$_tu','$_up_kat','$_up_pj','$rekl_czy_jest_reklamacyjne','$rekl_nr_zgl_reklamowanego','$rekl_czy_utworzono_z_niego_reklamacyjne','$_REQUEST[hdds]',$czy_powiazane_z_wp,'$week',$czy_rozwiazany,'$czy_rozwiazany_data',0,0,0,0,0,0,$czy_wymagany_wyjazd,'$czy_wymagany_wyjazd_data','$czy_wymagany_wyjazd_osoba','$hd_ss_id','','',0,$es_filia)";
		//echo $sql;
		$result = mysql_query($sql, $conn_hd) or die($k_b);
	
	 
		// ustalenie numeru zgłoszenia
		$r3 = mysql_query("SELECT zgl_id FROM $dbname_hd.hd_zgloszenie WHERE ((zgl_unikalny_nr='$unique_nr') and (belongs_to='$es_filia') and (zgl_osoba_rejestrujaca='$currentuser')) ORDER BY zgl_id DESC LIMIT 1", $conn_hd) or die($k_b);
		list($last_nr)=mysql_fetch_array($r3);
		
		$sql2 = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_nr=$last_nr WHERE ((zgl_unikalny_nr='$unique_nr') and (belongs_to='$es_filia') and (zgl_osoba_rejestrujaca='$currentuser'))";	
		$result = mysql_query($sql2, $conn_hd) or die($k_b);
	
		// dodanie 1 kroku dla zgłoszenia
		
		$sql3 = "INSERT INTO $dbname_hd.hd_zgloszenie_szcz VALUES ('',$last_nr,'$unique_nr',1,'$CzasStartStop',$d_cw,'$DataWpisu1 $GodzinaWpisu1','$_REQUEST[status_id]','$wyk_czyn','$OsobaPrzypisana','',$emailSend,9,9,$bylwyjazd,'$dddd','$currentuser',1,$d_km,$awaria_z_przesunieciem,'','','$Zdiagnozowany','9','','','$_REQUEST[hdds]',$czy_rozwiazany,0, $es_filia)";
		$result = mysql_query($sql3, $conn_hd) or die($k_b);
		
		// uaktualni pozycję zadania 
		$sql_d1="UPDATE $dbname.serwis_zadania_pozycje SET pozycja_data_modyfikacji='$dddd', pozycja_modyfikowane_przez ='$currentuser', pozycja_status=9, pozycja_uwagi = '', pozycja_hd_zgloszenie = '$last_nr' WHERE (pozycja_id = '$pozid') LIMIT 1";
		$result = mysql_query($sql_d1, $conn_hd) or die($k_b);
		
		
		$lista_zgloszen_z_numerami .= "".$i.". ".$pnk." (<b>".$last_nr."</b>)<br />";
		//echo "<hr />".$sql."<br />".$sql2."<br />".$sql3."<br />".$sql_d1."";
		ob_flush(); flush();
		
		$i++;
	}
	
	// *************************

		$_SESSION['zgloszenie_dodano_'.$_REQUEST[unique_nr1].'']='poprawnie';

	// *************************

	echo "<h5>Pomyślnie zarejestrowano <b>$ile_pozycji</b> zgłoszeń do bazy Helpdesk</h5>";
	
	startbuttonsarea("center");
	echo "<br />".$lista_zgloszen_z_numerami."<br />";
	endbuttonsarea();
	
} else echo "<h5>Pomyślnie zarejestrowano <b>$ile_pozycji</b> zgłoszeń do bazy Helpdesk</h5>";
	
	echo "<hr />";
	
	startbuttonsarea("right");
	oddziel();
	echo "<span style='float:left;'>";
		echo "<input class=buttons type=button onClick=\"window.location.href='hd_d_zgloszenie_simple.php?stage=".$_REQUEST[stage]."'\" value='Nowe zgłoszenie' />";
		//echo "<input class=buttons type=button onClick=window.location.href=\"hd_d_zgloszenie_s.php?stage=".$_REQUEST[stage]."&filtr=X-X-X-X\" value='Nowe zgłoszenie seryjne'>";
	echo "</span>";
	
	echo "<input class=buttons type=button style='font-weight:bold;' onClick=\"self.close();if (opener) opener.close(); if (opener.opener)  opener.opener.location.href='hd_p_zgloszenia.php?showall=0&p5=BZ&sort=AD&page=1';\" value='Przeglądaj zgłoszenia' />";
	echo "<input class=buttons id=zamknij_button type=button onClick=\"self.close(); if (opener) opener.location.reload(true); \" value=Zamknij>";
	endbuttonsarea();
	
} else {

$unique_nr1 = Date('YmdHis')."".rand_str();

session_register('zgloszenie_dodano_'.$unique_nr1.'');	
$_SESSION['zgloszenie_dodano_'.$unique_nr1.'']='nie';


	$sql="SELECT * FROM $dbname.serwis_zadania_pozycje WHERE (pozycja_id  IN (".$_REQUEST[numery]."))";
	$result = mysql_query($sql, $conn) or die($k_b);
	$count_rows = mysql_num_rows($result);
	
	if ($count_rows>0) {
		pageheader("Generowanie zgłoszeń o statusie <b>przypisane</b> dla wybranych pozycji zadania");
		
		?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
		
		while ($dane=mysql_fetch_array($result)) { 
			$temp_up_id 	= $dane['pozycja_komorka']; 		
			$temp_zid		= $dane['pozycja_zadanie_id'];
			$temp_zdata 	= $dane['pozycja_zaplanowana_data_wykonania'];
		}
		$sql5="SELECT * FROM $dbname.serwis_zadania WHERE (zadanie_id=$temp_zid) LIMIT 1";
		$result5=mysql_query($sql5,$conn) or die($k_b);

		while ($dane5=mysql_fetch_array($result5)) { 
			$opiszadania 	= $dane5['zadanie_opis']; 
			$_zad_kat 		= $dane5['zadanie_hd_kat']; 
			$_zad_podkat	= $dane5['zadanie_hd_podkat']; 
			$_zad_wc		= $dane5['zadanie_hd_wc']; 
			$_zad_osoba		= $dane5['zadanie_hd_osoba']; 
			$_zad_podkat2	= $dane5['zadanie_hd_podkat_poziom_2'];
		}
		
		$sql44="SELECT user_first_name, user_last_name FROM $dbname.serwis_uzytkownicy ";
		if ($es_m!=1) $sql44 = $sql44."WHERE belongs_to=$es_filia ";
		$sql44 = $sql44." and user_locked=0 ";
		if ($_REQUEST[dosiebie]==$currentuser) $sql44 = $sql44." and (CONCAT(user_first_name,' ',user_last_name)='$currentuser') ";
		
		$sql44 = $sql44."ORDER BY user_last_name ASC";
		$result44 = mysql_query($sql44, $conn) or die($k_b);
		$count_rows = mysql_num_rows($result44);
		$i = 1;
		startbuttonsarea("left");
		echo "<h5>Zadanie : <b>$opiszadania</b><br /><br />Wybrane komórki: <br /><br /><b>";
			
			$sql="SELECT pozycja_komorka FROM $dbname.serwis_zadania_pozycje WHERE (pozycja_id  IN (".$_REQUEST[numery]."))";
			$result = mysql_query($sql, $conn) or die($k_b);
			while ($dane=mysql_fetch_array($result)) { 
				$kn = $dane['pozycja_komorka'];
				
				$sql_99 = "SELECT up_id, up_nazwa, pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.belongs_to=$es_filia) and (serwis_piony.pion_id=serwis_komorki.up_pion_id) and (serwis_komorki.up_nazwa='".$kn."') LIMIT 1";
			
				$wynik = mysql_query($sql_99,$conn) or die($k_b);
				$dane1=mysql_fetch_array($wynik);
				
				echo " ".$i.". ".$dane1['pion_nazwa']." ".$dane1['up_nazwa']."<br />";
				$i++;
			}
			
			
		echo "</b></h5>";
		//hr();
		endbuttonsarea();
		startbuttonsarea("left");
		
		starttable();
		
		
		echo "<form name=epz action=$PHP_SELF method=POST onSubmit=\"return pytanie_zatwierdz3('Czy utworzyć zgłoszenia dla wybranych komórek ?');\">";
		
		echo "<input type=hidden name=id value=$id>";	
		echo "<input type=hidden name=numery value='$_REQUEST[numery]'>";
		echo "<input type=hidden name=cu value='$_REQUEST[cu]'>";

	tr_();
		echo "<td colspan=2>";
			$dddd = Date('Y-m-d');
			$tttt = Date('H:i');
			include_once('systemdate.php');
		_td();
	_tr();
	
	tbl_empty_row();
	
	tr_();
		td("140;r;Data zgłoszenia");
		td_(";;;");		
			$dddd = Date('Y-m-d');
		
			echo "<select class=wymagane name=hddz id=hddz onChange=\"document.getElementById('hd_wyjazd_data').value=this.value;\" >";
			echo "<option value='$dddd' SELECTED>$dddd</option>\n";
			
			if ((date("w",strtotime($dddd))!=1) || ($idw_dla_zbh_testowa))  {
				for ($cd=1; $cd<($idw_dla_zbh+1); $cd++) {
					echo "<option value='".SubstractDays($cd,$dddd)."'>".SubstractDays($cd,$dddd)."&nbsp;";
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
			echo "&nbsp;Godzina zgłoszenia&nbsp;<input class=wymagane type=text name=hdgz id=hdgz value='$tttt' maxlength=5 size=2 onFocus=\"this.select();\" onKeyPress=\"return filterInput(1, event, false, ''); \" onKeyUp=\"DopiszDwukropek('hdgz');\" onDblClick=\"this.value='".$tttt."'; \" title=\" Podwójne kliknięcie wpisuje godzinę otwarcia tego okna \" />";	
			
			echo "&nbsp;&nbsp;Numer zgłoszenia z Poczty&nbsp;";
			echo "<input type=text id=hdnzhadim name=hdnzhadim "; 
			if ($_REQUEST[zgl_reklamacyjne]=='1') { echo " value='".$_REQUEST[nrzglpoczty]."' "; }
			echo " maxlength=".$HADIM_max." size=".$HADIM_width." onKeyPress=\"return filterInput(1, event, false); \" onFocus=\"this.select();\" />";

		_td();
	_tr();
	
	tr_();
		td("140;rt;Osoba zgłaszająca");
		td(";;<b>$_zad_osoba</b>");	
		echo "<input type=hidden name=hd_oz value='$_zad_osoba'>";
	_tr();

	tr_();
		td("140;rt;Temat zgłoszenia");
		td_(";;;");
			echo "<input tabindex=-1 type=text name=hd_temat id=hd_temat readonly size=70 style='border-width:0px;background-color:transparent;font-weight:bold; font-family:tahoma;' ";	
			if (($_GET[parent_zgl]!='') && ($_GET[parent_zgl]!=0)) {
				echo " value='$zgloszenie_temat' ";
			}
			
			echo "><br />";		
		_td();
	_tr();	
	tr_();
		td("140;rt;Treść zgłoszenia");		
		td_(";;;");
			echo "<textarea class=wymagane name=hd_tresc id=hd_tresc cols=66 rows=2 onKeyUp=\"KopiujDo1Entera(this.value,'hd_temat'); ex1(this); if (this.value!='') { document.getElementById('tr_clear').style.display=''; } else { document.getElementById('tr_clear').style.display='none'; }\" onBlur=\"ZamienTekst(this.id); KopiujDo1Entera(this.value,'hd_temat'); \" >";
			echo $_zad_wc;			
			echo "</textarea>";
			
			//echo "&nbsp;<a title='Dodaj treść do słownika' style='display:none' id=sl_d class=imgoption  onClick=\"newWindow_byName('_dodaj_do_slownika',700,400,'hd_d_slownik_tresc.php?akcja=fastadd'); return false;\"><input class=imgoption type=image src=img/slownik_dodaj.gif></a>";
			
			echo "<a title='Wybierz treść ze słownika' id=sl_wybierz class=imgoption  onClick=\"newWindow_byName_r('_wybierz_ze_slownika',700,600,'hd_z_slownik_tresci2.php?a=2&akcja=wybierz&p6=".urlencode($currentuser)."'); return false;\"><input class=imgoption type=image src=img/ew_prosty.png></a>";
			
			echo "<a id=tr_clear href=# style='display:none' onclick=\"if (confirm('Czy wyczyścić treść zgłoszenia ?')) { document.getElementById('hd_tresc').value=''; KopiujDo1Entera(document.getElementById('hd_tresc').value,'hd_temat'); ex1(document.getElementById('hd_tresc')); document.getElementById('tr_clear').style.display='none'; document.getElementById('sl_d').style.display='none'; document.getElementById('hd_tresc').focus(); }\" title=' Wyczyść treść zgłoszenia '><img src=img/czysc.gif border=0></a>";
			
		_td();
	_tr();	
	
	tr_();
		td("140;rt;Kategoria");
		list($kat_opis)=mysql_fetch_array(mysql_query("SELECT hd_kategoria_opis FROM hd_kategoria WHERE (hd_kategoria_nr='$_zad_kat') LIMIT 1"));
		td(";;<b>$kat_opis</b>");	
		echo "<input type=hidden name=kat_id value=$_zad_kat>";		
	_tr();		
	tr_();
		td("140;rt;Podkategoria");
		list($podkat_opis)=mysql_fetch_array(mysql_query("SELECT hd_podkategoria_opis FROM hd_podkategoria WHERE (hd_podkategoria_nr='$_zad_podkat') LIMIT 1"));
		td(";;<b>$podkat_opis</b>");	
		echo "<input type=hidden name=podkat_id value=$_zad_podkat>";
	_tr();
	
	tr_();
		td("140;rt;Podkategoria (poziom 2)");
		td_(";;");	
			$_zad_podkat2_o = $_zad_podkat2;
			if ($_zad_podkat2_o=='') $_zad_podkat2_o = 'Brak';	
			echo "<b>".$_zad_podkat2_o."</b>";
			echo "<input type=hidden name=sub_podkat_id value='$_zad_podkat2'>";
		_td();
	_tr();	
	

	tr_();
		td("140;rt;Status");
		td(";;<b>przypisane</b>");	
		echo "<input type=hidden name=status_id value=2>";
	_tr();
	tbl_empty_row();
	endtable();	
	
	echo "<span style='float:left'>";
		echo "<input class=border0 type=checkbox name=czy_synchronizowac id=czy_synchronizowac checked=checked><a tabindex=-1 class=normalfont href=# onClick=\"if (document.getElementById('czy_synchronizowac').checked) { document.getElementById('czy_synchronizowac').checked=false; return false; } else { document.getElementById('czy_synchronizowac').checked=true; return false; }\"><font color=red>&nbsp;Widoczne dla Poczty</font></a>";
		
		echo " | <input class=border0 type=checkbox name=wymaga_wyjazdu id=wymaga_wyjazdu><a tabindex=-1 class=normalfont href=# onClick=\"if (document.getElementById('wymaga_wyjazdu').checked) { document.getElementById('wymaga_wyjazdu').checked=false; return false; } else { document.getElementById('wymaga_wyjazdu').checked=true; return false; }\"><font color=blue>&nbsp;Wymaga wyjazdu</font></a>";
		
	echo "</span>";
	
	echo "<input type=hidden name=noback id=noback value='$_REQUEST[noback]'>";
	echo "<input type=hidden name=unique_nr1 id=unique_nr1 value='$unique_nr1'>";
	
	endbuttonsarea();
    startbuttonsarea("right");
	addownsubmitbutton("'Utwórz zgłoszenia'","submit");	
	addbuttons("anuluj");
	endbuttonsarea();
	_form();
	}
	
	?>
	
	<script>
		KopiujDo1Entera(document.getElementById('hd_tresc').value,'hd_temat'); ex1(document.getElementById('hd_tresc'));
	</script>
		
	<?php
	
}
?>
<script>HideWaitingMessage();</script>
</body>
</html>