<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');

if ($_REQUEST[sr]=='1') { 
	echo "<body onLoad=\"RecoverScroll.init('hd_p_zgloszenia'); "; 
	if ($_REQUEST[zmien_ww]==1) {
		
	} else {
		echo " document.getElementById('search_zgl_nr').focus(); ";
	}
	echo "\" />"; 
} 
else { echo "<body onLoad=\"document.getElementById('filtr1').focus(); UpdateIloscZaznaczen();\" />"; }
?>

<?php 

function toUpper($string) {
	$string = strtr($string,'qwertyuiopasdfghjklzxcvbnm','QWERTYUIOPASDFGHJKLZXCVBNM');
	return (strtr($string, 'ęóąśłżźćń','ĘÓĄŚŁŻŹĆŃ' )); 
};
	
include('body_start.php');

/*	
	$res1a = mysql_query("SELECT zgl_szcz_id, zgl_szcz_czas_rozpoczecia_kroku, zgl_szcz_czas_wykonywania FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='83180') and (zgl_szcz_widoczne=1) and (zgl_szcz_status=9) LIMIT 1");
		
	$_pole29 = '2012-08-03 11:00:00';
	$_pole29_cw = '80';
		
	$_pole29 = AddMinutesToDate($_pole29_cw, $_pole29); // <-- new (02.07.2012)		
	$_pole29 = substr($_pole29,0,16);
	echo ">>>>".$_pole29;
*/

if ($es_mminhd==1) { 
	echo "<div id=mainmenu style='display:;'>";
		include_once('login_info.php');
		include_once('mainmenu.php');
		echo "<br />";

	echo "</div>";
}

if ($_REQUEST[sr]=='1') {
	pageheader("Wyszukaj zgłoszenia",0,1);
	echo "<form name=szukaj action=$PHP_SELF method=POST onSubmit=\"return SzukajSprawdzPola();\">";
	starttable("auto");
	tbl_empty_row(1);
	tr_(); 
	td(";r;Nr zgłoszenia");
	td_(";l;;");
	echo "<input type=radio name=ss id=search_sel1 value='1' style='border:0px; display:none;' checked onClick=\"document.getElementById('search_zgl_nr').focus(); \">&nbsp;";
	echo "<input class='hdszgl' type=text name=search_zgl_nr id=search_zgl_nr maxlength=10 size=9 onKeyPress=\"return filterInputEnter(1, event, false); \" onFocus=\"document.getElementById('search_sel1').checked=true;\" ";
	if (($_REQUEST[zmien_ww]==1) && ($_REQUEST[search_zgl_nr]!='')) echo " value='$_REQUEST[search_zgl_nr]' ";
	echo ">";	
	_td(); _tr();
	tr_();  
	td(";r;Nr Landesk");
	td_(";l;;");	
	echo "<input type=radio name=ss id=search_sel2 value='2' style='border:0px; display:none;' onClick=\"document.getElementById('search_hadim_nr').focus(); \">&nbsp;";
	echo "<input class='hdszgl' type=text name=search_hadim_nr id=search_hadim_nr maxlength=10 size=10 onKeyPress=\"return filterInputEnter(1, event, false); \" onFocus=\"document.getElementById('search_sel2').checked=true;\""; 
	if (($_REQUEST[zmien_ww]==1) && ($_REQUEST[search_hadim_nr]!='')) echo " value='$_REQUEST[search_hadim_nr]' ";
	echo ">";	
	_td(); _tr();
	tr_(); 
	td(";r;Data zgłoszenia");
	td_(";l;;");
	echo "<input type=radio name=ss id=search_sel4 value='4' style='border:0px; display:none;' onClick=\"document.getElementById('sd').focus(); \">&nbsp;";
	echo "<input class='hdszgl' type=text name=sd id=sd maxlength=10 size=10 onKeyPress=\"return filterInputEnter(1, event, false,'-'); \" onKeyUp=\"DopiszKreski('sd');\" onFocus=\"document.getElementById('search_sel4').checked=true;\"";
	if (($_REQUEST[zmien_ww]==1) && ($_REQUEST[sd]!='')) echo " value='$_REQUEST[sd]' ";
	echo ">";	
	echo "&nbsp;<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img src=img/cal.gif width=16 height=16 border=0></a>";
	if ($_today==1) echo "&nbsp;<a title=' Ustaw datę na dzień bieżący ' onClick=\"document.getElementById('sd').value='".Date('Y-m-d')."'; return false;\"><img src=img/hd_note_today.gif width=16 height=16 border=0></a>";	
	_td(); _tr();
	tr_();	 
	td(";r;Nazwa komórki");
	td_(";l;;");
	echo "<input type=radio name=ss id=search_sel5 value='5' style='border:0px; display:none;' onClick=\"document.getElementById('sk').focus(); \">&nbsp;";
	echo "<input class='hdszgl' type=text name=sk id=sk maxlength=60 size=60 onFocus=\"document.getElementById('search_sel5').checked=true;\"";
	if (($_REQUEST[zmien_ww]==1) && ($_REQUEST[sk]!='')) echo " value='$_REQUEST[sk]' ";
	echo ">";	
	echo "<input type=hidden name=sk_id id=sk_id>";
	_td(); _tr();
	
	tr_();	 
	td(";r;Osoba zgłaszająca");
	td_(";l;;");
	echo "<input type=radio name=ss id=search_sel5a value='8' style='border:0px; display:none;' onClick=\"document.getElementById('so').focus(); \">&nbsp;";
	echo "<input class='hdszgl' type=text name=so id=so maxlength=60 size=30 onFocus=\"document.getElementById('search_sel5a').checked=true;\"";
	if (($_REQUEST[zmien_ww]==1) && ($_REQUEST[so]!='')) echo " value='$_REQUEST[so]' ";
	echo ">";	
	echo "<input type=hidden name=so_id id=so_id> (można podać dowolną część imienia lub nazwiska, np. anna)";
	_td(); _tr();
	
	tr_();	 
	td(";r;Osoba rejestrująca zgłoszenie");
	td_(";l;;");
		echo "<input type=radio name=ss id=search_sel6a value='9' style='border:0px; display:none;' onClick=\"document.getElementById('sor').focus(); \">&nbsp;";
		$all_from_filia = '';
		$result6x = mysql_query("SELECT user_first_name, user_last_name FROM $dbname.serwis_uzytkownicy WHERE (belongs_to=$es_filia) and (user_locked=0) ORDER BY user_last_name", $conn) or die($k_b);
		echo "<select class='hdszgl' style='height:32px; font-weight:normal;' name=p6 id=p6 onFocus=\"document.getElementById('search_sel6a').checked=true;\">\n"; 					 				
		echo "<option value='' SELECTED>Dowolna</option>\n";
		while (list($temp_imie1,$temp_nazwisko1) = mysql_fetch_array($result6x)) {
			echo "<option value='$temp_imie1 $temp_nazwisko1'"; 
			$iin = $temp_imie1.' '.$temp_nazwisko1;
			$all_from_filia .= "'".$iin ."',";
			if (($_REQUEST[zmien_ww]==1) && ($_REQUEST[p6]==''.$iin.'')) echo " SELECTED ";
			//if ($_GET[tuser]==$iin) echo " SELECTED "; 
			echo ">$temp_imie1 $temp_nazwisko1</option>\n"; 
		}
		echo "</select>\n";
		echo "<input type=hidden name=so_id id=so_id> ";
	_td(); _tr();
	
	tr_();	 
	td(";r;Kategoria zgłoszenia");
	td_(";l;;");
		echo "<input type=radio name=ss id=k value='10' style='border:0px; display:none;' onClick=\"document.getElementById('k').focus(); \">&nbsp;";
		echo "<select class='hdszgl' style='height:32px; font-weight:normal;' id=p2 name=p2 onChange=\"MakePodkategoriaList(this.options[this.options.selectedIndex].value);\" />";
			if (($_REQUEST[zmien_ww]==1) && ($_REQUEST[p6]==''.$iin.'')) echo " SELECTED ";
			
			echo "<option value='' ";  if (($_REQUEST[zmien_ww]==1) && ($_REQUEST[p2]=='')) echo " SELECTED "; echo ">dowolna</option>\n";
			echo "<option value='1' "; if (($_REQUEST[zmien_ww]==1) && ($_REQUEST[p2]=='1')) echo " SELECTED "; echo ">Konsultacje</option>\n";
			echo "<option value='2' "; if (($_REQUEST[zmien_ww]==1) && ($_REQUEST[p2]=='2')) echo " SELECTED "; echo ">Awarie</option>\n";
			echo "<option value='6' "; if (($_REQUEST[zmien_ww]==1) && ($_REQUEST[p2]=='6')) echo " SELECTED "; echo ">Awarie krytyczne</option>\n";
			echo "<option value='3' "; if (($_REQUEST[zmien_ww]==1) && ($_REQUEST[p2]=='3')) echo " SELECTED "; echo ">Prace zlecone w ramach umowy</option>\n";
			echo "<option value='7' "; if (($_REQUEST[zmien_ww]==1) && ($_REQUEST[p2]=='7')) echo " SELECTED "; echo ">Konserwacja</option>\n";
			echo "<option value='4' "; if (($_REQUEST[zmien_ww]==1) && ($_REQUEST[p2]=='4')) echo " SELECTED "; echo ">Prace zlecone poza umową</option>\n";
			echo "<option value='5' "; if (($_REQUEST[zmien_ww]==1) && ($_REQUEST[p2]=='5')) echo " SELECTED "; echo ">Prace na potrzeby Postdata</option>\n";
		echo "</select>\n";		
	_td(); 
	_tr();
	tr_();	 
	td(";r;Podkategoria zgłoszenia");
	td_(";l;;");
		echo "<input type=radio name=ss id=pk value='10a' style='border:0px; display:none;' onClick=\"document.getElementById('pk').focus(); \">&nbsp;";
		echo "<select class='hdszgl' style='height:32px; font-weight:normal;' id=p3 name=p3>\n";
		echo "<option value=''></option>\n";		
		echo "</select>\n";
	_td(); 
	_tr();
	
	tbl_empty_row(1);
	tr_();  
	td(";r;Nr w eSerwisie Gdańsk");
	td_(";l;;");
	echo "<input type=radio name=ss id=search_sel3 value='3' style='border:0px' onClick=\"document.getElementById('search_eserwis_nr').focus(); \">&nbsp;";
	echo "<input class='hdszgl' type=text name=search_eserwis_nr id=search_eserwis_nr maxlength=10 size=10 onFocus=\"document.getElementById('search_sel3').checked=true;\"";
	if (($_REQUEST[zmien_ww]==1) && ($_REQUEST[search_eserwis_nr]!='')) echo " value='$_REQUEST[search_eserwis_nr]' ";
	echo ">";
	_td(); _tr();
	tr_();	 
	td(";r;Treść zgłoszenia");
	td_(";l;;");
	echo "<input type=radio name=ss id=search_sel6 value='6' style='border:0px' onClick=\"document.getElementById('st').focus(); \">&nbsp;";
	echo "<input class='hdszgl' type=text name=st id=st maxlength=60 size=60 onFocus=\"document.getElementById('search_sel6').checked=true;\""; 
	if (($_REQUEST[zmien_ww]==1) && ($_REQUEST[st]!='')) echo " value='$_REQUEST[st]' ";
	echo ">";
	_td(); _tr();	

	tr_();	 
	td(";r;Wykonane czynności");
	td_(";l;;");	
	echo "<input type=radio name=ss id=search_sel7 value='7' style='border:0px' onClick=\"document.getElementById('st_wc').focus(); \">&nbsp;";
	echo "<input class='hdszgl' type=text name=st_wc id=st_wc maxlength=60 size=60 onFocus=\"document.getElementById('search_sel7').checked=true;\"";
	if (($_REQUEST[zmien_ww]==1) && ($_REQUEST[st_wc]!='')) echo " value='$_REQUEST[st_wc]' ";
	echo ">";
	_td(); _tr();	
	
	tr_();	 
	echo "<td colspan=2 class=center>";
	nowalinia();
	echo "<input type=submit class=buttons value='Szukaj'><br/>";
	echo "<br />";
	echo "<input type=button class=buttons value='Nowe wyszukiwanie' onClick=\"if (confirm('Czy napewno chcesz wykonać nowe wyszukiwanie ?')) { self.location.href='hd_p_zgloszenia.php?sa=0&sr=1'; return false; } \" />";
	
	_td();
	_tr();
	endtable();
	
	startbuttonsarea('right');
	oddziel();
	echo "<input type=button class=buttons value='Przeglądaj zgłoszenia' onClick=\"window.location.href='hd_p_zgloszenia.php?sa=0&p5=BZ&s=AD'\" />";

	echo "<span style='float:left'>";
	addbuttons("wstecz","start");
	echo "</span>";

	endbuttonsarea();
	
	echo "<input type=hidden name=sa id=sa value='0'>";
	echo "<input type=hidden name=sr id=sr value='search-wyniki'>";
	echo "</form>";

} else {

	echo "<input type=hidden name=sa1 id=sa1 value='$_REQUEST[sa]'>";
	echo "<input type=hidden name=page11 id=page11 value='$_REQUEST[page]'>";
	echo "<input type=hidden name=page12 id=page12 value='$_REQUEST[paget]'>";
	//$newSortowanie = false;
	
	if ($_REQUEST[sr]!='search-wyniki') {

		$sql="SELECT *";
		
		if ($_REQUEST[p0]=='R') {
			$sql.= ", TIMEDIFF('".Date('Y-m-d H:i:s')."',zgl_data_rozpoczecia) as CzasDoRozpoczecia ";
		}
		if ($_REQUEST[p0]=='Z') {
			$sql.= ", TIMEDIFF('".Date('Y-m-d H:i:s')."',zgl_data_zakonczenia) as CzasDoZakonczenia ";
		}	
		
		$sql.= " FROM $dbname_hd.hd_zgloszenie "; 
		if ($_REQUEST[add]=='ptr') $sql.= ",$dbname_hd.hd_zgloszenie_szcz ";
		if ($_REQUEST[add]=='drk0') $sql.= ",$dbname_hd.hd_zgloszenie_szcz ";
		
		if ($_REQUEST[add]=='startstop') $sql.= ",$dbname_hd.hd_zgloszenie_szcz ";
		
		$sql.= " WHERE ";
		
		$sql=$sql."((hd_zgloszenie.belongs_to=$es_filia)  or (hd_zgloszenie.zgl_przekazane_do=$es_filia)) and (hd_zgloszenie.zgl_widoczne=1) ";
		// wg dnia
		if (($_REQUEST[p1]!='X') && ($_REQUEST[p1]!='0') && ($_REQUEST[p1]!='')) $sql.="AND (zgl_data='$_REQUEST[p1]') ";
		// wg kategorii
		if (($_REQUEST[p2]!='X') && ($_REQUEST[p2]!='0') && ($_REQUEST[p2]!='')) $sql.="AND (zgl_kategoria='$_REQUEST[p2]') ";
		// wg podkategorii
		if (($_REQUEST[p3]!='X') && ($_REQUEST[p3]!='')) $sql.="AND (zgl_podkategoria='$_REQUEST[p3]') ";
		// wg priorytetu
		if (($_REQUEST[p4]!='X') && ($_REQUEST[p4]!='0') && ($_REQUEST[p4]!='')) $sql.="AND (zgl_priorytet='$_REQUEST[p4]') ";
		
		// jeżeli chcemy wyświetlić sprawdzone - to ustwiamy automatycznie status na "zamknięte"
		if ($_REQUEST[p8]=='1') {
			$_REQUEST[p5]='9';
		}
		
		// wg statusu
		if (($_REQUEST[p5]!='X') && ($_REQUEST[p5]!='0') && ($_REQUEST[p5]!='') && ($_REQUEST[p5]!='BZ')) $sql.="AND (zgl_status='$_REQUEST[p5]') ";
		
		if ($_REQUEST[p5]=='BZ') $sql.="AND (zgl_status<>'9') ";
		// wg przypisania
		if (($_REQUEST[p6]!='X') && ($_REQUEST[p6]!='0') && ($_REQUEST[p6]!='')) {
			$p_6='';
			if ($_REQUEST[p6]!='9') $p_6=$_REQUEST[p6];
			$sql.="AND (zgl_osoba_przypisana='$p_6') ";
		}
		
		if (($_REQUEST[p8]!='X') && ($_REQUEST[p8]!='')) {
			if ($_REQUEST[p8]=='1') $sql.="AND (zgl_sprawdzone_osoba<>'') ";
			if ($_REQUEST[p8]=='0') $sql.="AND (zgl_sprawdzone_osoba='') ";
		}
		
		if ($_REQUEST[p0]=='R') {
			
			$sql .= "AND (zgl_data_rozpoczecia!='0000-00-00 00:00:00') and ((zgl_status=1) or (zgl_status=2)) ";
		}
		if ($_REQUEST[p0]=='Z') {
			
			$sql .= "AND (zgl_data_zakonczenia!='0000-00-00 00:00:00') and ((zgl_status<>1) and (zgl_status<>2)) ";
		}	
		
		if ($_REQUEST[add]=='ptr') $sql.=" AND (hd_zgloszenie.zgl_id=hd_zgloszenie_szcz.zgl_szcz_zgl_id) and (hd_zgloszenie_szcz.zgl_szcz_przesuniety_termin_rozpoczecia=1) and ((hd_zgloszenie.zgl_status=1) or (hd_zgloszenie.zgl_status=2)) ";
		
		if ($_REQUEST[add]=='drk0')	$sql.=" AND (hd_zgloszenie.zgl_id=hd_zgloszenie_szcz.zgl_szcz_zgl_id) and ((hd_zgloszenie_szcz.zgl_szcz_czas_rozpoczecia_kroku LIKE  '%0000-00-00%') or (hd_zgloszenie_szcz.zgl_szcz_czas_rozpoczecia_kroku LIKE  '%1970-01-01%') or (hd_zgloszenie_szcz.zgl_szcz_czas_rozpoczecia_kroku LIKE  '%1969-12-31%'))";
		
		if ($_REQUEST[add]=='pzw') $sql.=" AND (hd_zgloszenie.zgl_razem_km>0) ";
		if ($_REQUEST[add]=='pzn') $sql.=" AND (hd_zgloszenie.zgl_naprawa_id>0) ";
		
		if ($_REQUEST[add]=='pzpss') $sql.=" AND ((hd_zgloszenie.zgl_sprzet_serwisowy_id>-1) or (hd_zgloszenie.zgl_naprawa_id=(SELECT naprawa_id FROM $dbname.serwis_naprawa WHERE (naprawa_sprzet_zastepczy_id>0) and (serwis_naprawa.naprawa_hd_zgl_id=hd_zgloszenie.zgl_id) LIMIT 1)))";
		
		if ($_REQUEST[add]=='sn14') {
			
			$data_min = SubstractDays(14, Date('Y-m-d'));
			$sql.=" AND (hd_zgloszenie.zgl_data<'$data_min') ";
			
		}

		if ($_REQUEST[add]=='rekl') $sql.=" AND (hd_zgloszenie.zgl_czy_to_jest_reklamacyjne=1) ";
		if ($_REQUEST[add]=='wp') $sql.=" AND (hd_zgloszenie.zgl_czy_powiazane_z_wymiana_podzespolow=1) ";
		if ($_REQUEST[add]=='nr') $sql.=" AND (hd_zgloszenie.zgl_czy_rozwiazany_problem=0) ";
		
		if ($_REQUEST[add]=='ww') $sql.=" AND (hd_zgloszenie.zgl_wymagany_wyjazd=1) ";
		
		if ($_REQUEST[add]=='drz0') $sql.=" AND (hd_zgloszenie.zgl_data='0000-00-00') ";
		
		if ($_REQUEST[add]=='startstop') $sql.=" AND ((hd_zgloszenie.zgl_id=hd_zgloszenie_szcz.zgl_szcz_zgl_id) AND (hd_zgloszenie_szcz.zgl_szcz_widoczne='1') AND (((hd_zgloszenie_szcz.zgl_szcz_czas_start_stop='START') AND (hd_zgloszenie_szcz.zgl_szcz_status IN ('3A','4'))) OR ((hd_zgloszenie_szcz.zgl_szcz_czas_start_stop='STOP') AND (hd_zgloszenie_szcz.zgl_szcz_status IN ('2','3','3B','5','6','7','8'))) OR ((hd_zgloszenie_szcz.zgl_szcz_czas_start_stop='STOP') AND (hd_zgloszenie_szcz.zgl_szcz_status IN ('1'))  AND (hd_zgloszenie_szcz.zgl_szcz_przesuniety_termin_rozpoczecia='0'))) and (hd_zgloszenie.zgl_data>='$_REQUEST[STARTSTOP_data]')) ";
		
		// wg tresci
		if ($_REQUEST[st]!='') $sql.=" AND (zgl_tresc LIKE '%$_REQUEST[st]%') ";
		
		//if ($wybierz_p!='') $sql.="AND (serwis_komorki.up_pion_id=$wybierz_p) ";
	
	$sql=$sql."ORDER BY ";
		
	if ($_REQUEST[p5]!='9') {
			//if ($_REQUEST[s]=='') $sql=$sql."hd_zgloszenie.zgl_nr DESC";
			
			if ($_REQUEST[s]=='') $_REQUEST[s]='AD';
			
			if ($_REQUEST[s]!='') {
				if ($_REQUEST[s]=='AA') $sql=$sql."hd_zgloszenie.zgl_nr ASC";
				if ($_REQUEST[s]=='AD') $sql=$sql."hd_zgloszenie.zgl_nr DESC";
				
				if ($_REQUEST[s]=='BA') $sql=$sql."hd_zgloszenie.zgl_data ASC";
				if ($_REQUEST[s]=='BD') $sql=$sql."hd_zgloszenie.zgl_data DESC";
				
				if ($_REQUEST[s]=='CA') $sql=$sql."hd_zgloszenie.zgl_komorka ASC";
				if ($_REQUEST[s]=='CD') $sql=$sql."hd_zgloszenie.zgl_komorka DESC";	
				
				if ($_REQUEST[s]=='DA') $sql=$sql."hd_zgloszenie.zgl_priorytet ASC";
				if ($_REQUEST[s]=='DD') $sql=$sql."hd_zgloszenie.zgl_priorytet DESC";		

				if ($_REQUEST[s]=='EA') $sql=$sql."hd_zgloszenie.zgl_status ASC";
				if ($_REQUEST[s]=='ED') $sql=$sql."hd_zgloszenie.zgl_status DESC";		

				if ($_REQUEST[s]=='FA') $sql=$sql."hd_zgloszenie.zgl_temat ASC";
				if ($_REQUEST[s]=='FD') $sql=$sql."hd_zgloszenie.zgl_temat DESC";		
				
				if ($_REQUEST[p0]=='R') {
					if ($_REQUEST[s]!='') $sql.=", ";
					$sql .= " CzasDoRozpoczecia ASC ";
					//$sql=$sql.",hd_zgloszenie.zgl_nr DESC";
				}	

				if ($_REQUEST[p0]=='Z') {
					if ($_REQUEST[s]!='') $sql.=", ";
					$sql .= " CzasDoZakonczenia ASC ";
					//$sql=$sql.",hd_zgloszenie.zgl_nr DESC";
				}	
				
				if (($_REQUEST[p0]!='R') && ($_REQUEST[p0]!='Z') && ($_REQUEST[s]=='')) {
					if ($_REQUEST[s]=='') $sql=$sql."hd_zgloszenie.zgl_nr DESC";
				}
			} else {
				$sql=$sql."hd_zgloszenie.zgl_nr DESC";
			}
	} else {
			if ($_REQUEST[s]=='') $_REQUEST[s]='XD';
			
			if ($_REQUEST[s]!='') {
				if ($_REQUEST[s]=='AA') $sql=$sql."hd_zgloszenie.zgl_nr ASC";
				if ($_REQUEST[s]=='AD') $sql=$sql."hd_zgloszenie.zgl_nr DESC";
				
				if ($_REQUEST[s]=='BA') $sql=$sql."hd_zgloszenie.zgl_data ASC";
				if ($_REQUEST[s]=='BD') $sql=$sql."hd_zgloszenie.zgl_data DESC";
				
				if ($_REQUEST[s]=='CA') $sql=$sql."hd_zgloszenie.zgl_komorka ASC";
				if ($_REQUEST[s]=='CD') $sql=$sql."hd_zgloszenie.zgl_komorka DESC";	
				
				if ($_REQUEST[s]=='DA') $sql=$sql."hd_zgloszenie.zgl_priorytet ASC";
				if ($_REQUEST[s]=='DD') $sql=$sql."hd_zgloszenie.zgl_priorytet DESC";		

				if ($_REQUEST[s]=='EA') $sql=$sql."hd_zgloszenie.zgl_status ASC";
				if ($_REQUEST[s]=='ED') $sql=$sql."hd_zgloszenie.zgl_status DESC";		

				if ($_REQUEST[s]=='FA') $sql=$sql."hd_zgloszenie.zgl_temat ASC";
				if ($_REQUEST[s]=='FD') $sql=$sql."hd_zgloszenie.zgl_temat DESC";		
				
				if ($_REQUEST[p0]=='R') {
					if ($_REQUEST[s]!='') $sql.=", ";
					$sql .= " CzasDoRozpoczecia ASC ";
					//$sql=$sql.",hd_zgloszenie.zgl_nr DESC";
				}	

				if ($_REQUEST[p0]=='Z') {
					if ($_REQUEST[s]!='') $sql.=", ";
					$sql .= " CzasDoZakonczenia ASC ";
					//$sql=$sql.",hd_zgloszenie.zgl_nr DESC";
				}	
				
				if (($_REQUEST[p0]!='R') && ($_REQUEST[p0]!='Z') && ($_REQUEST[s]=='')) {
					if ($_REQUEST[s]=='') $sql=$sql."hd_zgloszenie.zgl_nr DESC";
				}
						
				if ($_REQUEST[s]=='XA') $sql=$sql."hd_zgloszenie.zgl_data_zmiany_statusu ASC";
				if ($_REQUEST[s]=='XD') $sql=$sql."hd_zgloszenie.zgl_data_zmiany_statusu DESC";	
			} 
			/*else {
				$sql=$sql."hd_zgloszenie.zgl_data_zmiany_statusu DESC";
				$newSortowanie = true;
			}*/
		}
//		echo "$sql<br />";
	} else {

		$sciezka_do_cookie = "hd_p_zgloszenie_kroki.php?nr=0&id=0";
		?><script>
		$('#ZawartoscDIV').load('empty.php');
		createCookie('hd_p_zgloszenia_div_nr','$sciezka_do_cookie',365);
		</script><?php

		// jeżeli ma pokazać wyniki wyszukiwania
		$sql="SELECT * FROM $dbname_hd.hd_zgloszenie ";
		
		if ($_REQUEST[ss]==3) $sql.= ",$dbname_hd.hd_zgloszenie_szcz ";
		if ($_REQUEST[ss]==7) $sql.= ",$dbname_hd.hd_zgloszenie_szcz ";

		$sql.=" WHERE ((hd_zgloszenie.belongs_to=$es_filia) or (hd_zgloszenie.zgl_przekazane_do=$es_filia))and (hd_zgloszenie.zgl_widoczne=1) ";
		
		// wg dnia
		if (($_REQUEST[p1]!='X') && ($_REQUEST[p1]!='0') && ($_REQUEST[p1]!='')) $sql.="AND (zgl_data='$_REQUEST[p1]') ";
		// wg kategorii
		if (($_REQUEST[p2]!='X') && ($_REQUEST[p2]!='0') && ($_REQUEST[p2]!='')) $sql.="AND (zgl_kategoria='$_REQUEST[p2]') ";
		// wg podkategorii
		if (($_REQUEST[p3]!='X') && ($_REQUEST[p3]!='')) $sql.="AND (zgl_podkategoria='$_REQUEST[p3]') ";
		// wg priorytetu
		if (($_REQUEST[p4]!='X') && ($_REQUEST[p4]!='0') && ($_REQUEST[p4]!='')) $sql.="AND (zgl_priorytet='$_REQUEST[p4]') ";
		// wg statusu
		if (($_REQUEST[p5]!='X') && ($_REQUEST[p5]!='0') && ($_REQUEST[p5]!='') && ($_REQUEST[p5]!='BZ')) $sql.="AND (zgl_status='$_REQUEST[p5]') ";
		if ($_REQUEST[p5]=='BZ') $sql.="AND (zgl_status<>'9') ";
		// wg przypisania
		if (($_REQUEST[p6]!='X') && ($_REQUEST[p6]!='0') && ($_REQUEST[p6]!='')) {
			$p_6='';
			if ($_REQUEST[p6]!='9') $p_6=$_REQUEST[p6];
			$sql.="AND (zgl_osoba_przypisana='$p_6') ";
		}
		
		// wg nr zlecenia
		if ($_REQUEST[search_zgl_nr]!='') $sql.="AND ((zgl_nr='$_REQUEST[search_zgl_nr]') OR (zgl_poczta_nr='$_REQUEST[search_zgl_nr]'))";
		// wg nr zlecenia hadim
		if ($_REQUEST[search_hadim_nr]!='') $sql.="AND (zgl_poczta_nr='$_REQUEST[search_hadim_nr]') ";
		// wg daty zgłoszenia
		if ($_REQUEST[sd]!='') $sql.="AND (zgl_data='$_REQUEST[sd]') ";
		// wg komórki
		if ($_REQUEST[sk]!='') $sql.="AND (zgl_komorka='$_REQUEST[sk]') ";
		
		//if (($_REQUEST[p5]!='X') && ($_REQUEST[p5]!='0') && ($_REQUEST[p5]!='') && ($_REQUEST[p5]!='BZ')) $sql.="AND (zgl_status='$_REQUEST[p5]') ";
		//if ($_REQUEST[p5]=='BZ') $sql.="AND (zgl_status<>'9') ";
		
		// wg przypisania
		if (($_REQUEST[p6]!='X') && ($_REQUEST[p6]!='0') && ($_REQUEST[p6]!='')) {
			$sql.="AND (zgl_osoba_przypisana='$_REQUEST[p6]') ";
		}
		
		//		if ($_REQUEST[p5]=='BZ') $sql.=" AND (zgl_status<>'9') ";
		
		if ($_REQUEST[ss]==3) $sql.=" AND (zgl_szcz_gdansk_nr LIKE '%$_REQUEST[search_eserwis_nr]%') AND (zgl_szcz_zgl_id=zgl_id) ";
		if ($_REQUEST[ss]==6) $sql.=" AND (zgl_tresc LIKE '%$_REQUEST[st]%') ";
		if ($_REQUEST[ss]==7) $sql.=" AND (zgl_szcz_wykonane_czynnosci LIKE '%$_REQUEST[st_wc]%') AND (zgl_szcz_zgl_id=zgl_id)";
		
		if ($_REQUEST[ss]==8) $sql.=" AND (zgl_osoba LIKE '%$_REQUEST[so]%') ";
		
		$sql=$sql."ORDER BY ";
		
		if ($_REQUEST[p5]!='9') {
			if ($_REQUEST[s]=='') $sql=$sql."hd_zgloszenie.zgl_nr DESC";
		} else {
			$sql=$sql."hd_zgloszenie.zgl_data_zmiany_statusu DESC";
		}		
			if ($_REQUEST[s]=='AA') $sql=$sql."hd_zgloszenie.zgl_nr ASC";
			if ($_REQUEST[s]=='AD') $sql=$sql."hd_zgloszenie.zgl_nr DESC";
			
			if ($_REQUEST[s]=='BA') $sql=$sql."hd_zgloszenie.zgl_data ASC";
			if ($_REQUEST[s]=='BD') $sql=$sql."hd_zgloszenie.zgl_data DESC";
			
			if ($_REQUEST[s]=='CA') $sql=$sql."hd_zgloszenie.zgl_komorka ASC";
			if ($_REQUEST[s]=='CD') $sql=$sql."hd_zgloszenie.zgl_komorka DESC";	
			
			if ($_REQUEST[s]=='DA') $sql=$sql."hd_zgloszenie.zgl_priorytet ASC";
			if ($_REQUEST[s]=='DD') $sql=$sql."hd_zgloszenie.zgl_priorytet DESC";		

			if ($_REQUEST[s]=='EA') $sql=$sql."hd_zgloszenie.zgl_status ASC";
			if ($_REQUEST[s]=='ED') $sql=$sql."hd_zgloszenie.zgl_status DESC";		

			if ($_REQUEST[s]=='FA') $sql=$sql."hd_zgloszenie.zgl_temat ASC";
			if ($_REQUEST[s]=='FD') $sql=$sql."hd_zgloszenie.zgl_temat DESC";	
		

	}
	
	//echo $sql;
	
	$result = mysql_query($sql, $conn_hd) or die($k_b);
	$count_rows = mysql_num_rows($result);
	// paging
	$totalrows = $count_rows; 

	if ($_GET[hd_rps]!='') {
		$rowpersite = $_GET[hd_rps];
		$_SESSION['es_rps']=$_GET[hd_rps];
	} else {

		if ($_SESSION['es_rps']!='') {
			
			$_GET[hd_rps] = $_SESSION['es_rps'];
			$rowpersite = $_SESSION['es_rps'];
			
		} else {
			
			$_GET[hd_rps] = $rowpersite;
		}		
	}
	
	if ($sa==0) { $rps=$rowpersite; } else $rps=10000;
	if(empty($page)){ $page = 1; }
	$limitvalue = $page * $rps - ($rps);

	echo "<input type=hidden name=rpsite id=rpsite value=$rps>";

	$sql=$sql." LIMIT $limitvalue, $rps";
	//echo $sql;
	
	$result = mysql_query($sql, $conn_hd) or die($k_b);
	$count_rows = mysql_num_rows($result);

	$header_add = '';
	
	if ($is_dyrektor==1) {	
		$sql44="SELECT filia_nazwa FROM $dbname.serwis_filie WHERE filia_id=$es_filia";
		$result44 = mysql_query($sql44, $conn) or die($k_b);
		
		$newArray44 = mysql_fetch_array($result44);
		$temp_fn = $newArray44['filia_nazwa'];
		$header_add .= ' z obszaru: '.$temp_fn.' ';
	}

/*	if ($_REQUEST[p5]!=1) {
		if ($_REQUEST[p4]!='') {
			$header_add .= ' | <font color=red><b>';
		} else {
			$header_add .= '<font color=red><b>';
		}
	} else {
		$header_add .= ' | <font color=green><b>';
	}
*/
	if ($_REQUEST[p5]!='') $header_add .= ' | ';
	if ($_REQUEST[p4]!='') $header_add .= ' | ';
	if (($_REQUEST[add]=='drz0') || ($_REQUEST[add]=='drk0')) $header_add = substr($header_add,strlen($header_add),-4);
	//if ($_REQUEST[p6]=='9') $header_add.=' zgłoszenia nie przypisane';
	
	if (($_REQUEST[p5]!='') && ($_REQUEST[p4]!='1')) $header_add .= '<font color=red><b>';
	
	if ($_REQUEST[p4]=='4') $header_add.='awarie krytyczne ';

	if ($_REQUEST[p5]=='1') $header_add.='<font color=green>nowe</font>';
	if ($_REQUEST[p5]=='0') $header_add.='wszystkie';
	if ($_REQUEST[p5]=='9') $header_add.='zamknięte';
	if ($_REQUEST[p5]=='2') $header_add.='przypisane';
	if ($_REQUEST[p5]=='3') $header_add.='rozpoczęte';
	if ($_REQUEST[p5]=='3A') $header_add.='w serwisie zewnętrznym';
	if ($_REQUEST[p5]=='3B') $header_add.='w firmie';
	if ($_REQUEST[p5]=='4') $header_add.='oczekiwanie na odpowiedź klienta';
	if ($_REQUEST[p5]=='5') $header_add.='oczekiwanie na sprzęt';
	if ($_REQUEST[p5]=='7') $header_add.='rozpoczęte, nie zakończone';
	if ($_REQUEST[p5]=='6') $header_add.='do oddania';
	// ($_REQUEST[p5]=='1') $header_add.=' nowe zgłoszenia';
	if ($_REQUEST[p0]=='R') $header_add.='priorytet rozpoczęcia';
	if ($_REQUEST[p0]=='Z') $header_add.='priorytet zakończenia';
	
	if (($_REQUEST[p0]!='R') && ($_REQUEST[p0]!='Z')) {
		if ($_REQUEST[p5]=='BZ') $header_add.='nie zamknięte';
	}
		
	if (strlen($_REQUEST[p6])>2) { $header_add.=' <font color=black></b>|&nbsp;przypisane do:&nbsp;<b></font> '.$_REQUEST[p6].'</b>'; } else { 
		//	if ($_REQUEST[add]!='ptr') $header_add.='wszystkie';
	}
	
	if ($_REQUEST[add]!='') $header_add.=' <font color=black></b>|<b></font> ';
	if ($_REQUEST[add]=='ptr') $header_add.='zmiana terminu rozpoczęcia ';
	if ($_REQUEST[add]=='pzw') $header_add.='powiązane z wyjazdem ';	
	if ($_REQUEST[add]=='pzn') $header_add.='powiązane z naprawami ';
	if ($_REQUEST[add]=='pzpss') $header_add.='powiązane z przekazaniem sprzętu serwisowego ';
	if ($_REQUEST[add]=='sn14') $header_add.='starsze niż 14 dni ';	
	if ($_REQUEST[add]=='rekl') $header_add.='reklamacyjne ';	
	if ($_REQUEST[add]=='wp') $header_add.='powiązane z wymianą podzespołów ';	
	if ($_REQUEST[add]=='nr') {
		$header_add.='nierozwiązane problemy ';	
		
		if ($_REQUEST[p2]=='2') $header_add.=' <font color=black></b>|<b></font> awarie zwykłe ';	
		if ($_REQUEST[p2]=='6') $header_add.=' <font color=black></b>|<b></font> awarie krytyczne ';	
	} else {
		if ($_REQUEST[p2]=='2') $header_add.=' <font color=black></b>|<b></font> awarie zwykłe ';	
		if ($_REQUEST[p2]=='6') $header_add.=' <font color=black></b>|<b></font> awarie krytyczne ';			
	}
	
	if ($_REQUEST[add]=='ww') $header_add.='zgłoszenia wymagające wyjazdu ';	
	if ($_REQUEST[add]=='drz0') $header_add.='z datą rejestracji zgłoszenia = 0000-00-00 ';
	if ($_REQUEST[add]=='drk0') $header_add.='z datą rejestracji kroku = 0000-00-00 ';
	if ($_REQUEST[add]=='startstop') $header_add.='błędnie ustawiony znacznik START/STOP w krokach (dla zgłoszeń od '.$_REQUEST[STARTSTOP_data].') ';	
	
	if ($_REQUEST[p6]=='9') $header_add.='nie przypisane';
	
	if (($_REQUEST[p1]!='') && ($_REQUEST[p1]!='X') && ($_REQUEST[p1]!='0')) $header_add.=' z dnia '.$_REQUEST[p1];
	
	if ($_REQUEST[p8]=='1') $header_add.='sprawdzone';
	if ($_REQUEST[p8]=='0') $header_add.='niesprawdzone';
	
	$header_add.='</b></font>';
		
	if ($_REQUEST[sr]=='search-wyniki') {	
		$header_add.=' <font color=black> | wyniki wyszukiwania';
		
		if ($_REQUEST[ss]==3) $header_add.=' ciągu "<b>'.$_REQUEST[search_eserwis_nr].'</b>" w numerach zgłoszeń w bazie eSerwis (w Gdańsku)';
		if ($_REQUEST[ss]==6) $header_add.=' ciągu "<b>'.$_REQUEST[st].'</b>" w treści zgłoszenia';
		if ($_REQUEST[ss]==7) $header_add.=' ciągu "<b>'.$_REQUEST[st_wc].'</b>" w wykonanych krokach</font>';
		
		if ($_REQUEST[ss]==8) $header_add.=' zgłoszeń przyjętych od osoby "<b>'.$_REQUEST[so].'</b>"</font>';
		if ($_REQUEST[sk]!='') $header_add.=' komórki: <b>'.$_REQUEST[sk].'</b></font><font color=red></font>';
		if ($_REQUEST[search_zgl_nr]!='') $header_add.= ' zgłoszenia po numerze</font>';
	}
	
	$header_add.='</font>';
	//echo $sql;

/*
	echo "<h4 style='padding:10px; font-weight:normal;'>";
	
		echo "<div class=hideme>";
		echo "<a href=# class=normalfont onClick=\"if (parent.window.name=='eSerwis') { window.location.href='main.php'; } else { if (confirm('Zamknąć okno i przejść do strony głównej ?')) { self.close(); } else { window.location.href='main.php'; } }\" title='Przejdź do strony głównej'>";
		if ($pokaz_ikony==0) { 
			echo "<sub style='position:relative;float:left;margin-left:3px;text-decoration:none;' class=normaltext>";
			echo "<img src=img/house.gif class=imgoption style='position:relative;float:left;margin-left:6px;' border=0>";
			echo "</sub>";
		} else {
			echo "<sub style='position:relative;float:left;margin-left:3px;text-decoration:none;' class=normaltext><b>Start</b>";
			echo "</sub>";			
		}
		echo "</a>";

		
			echo "<sub id=show_mm style='position:relative;float:left;margin-left:3px;text-decoration:none;' class=normaltext>";
			echo "&nbsp;|&nbsp;";
			?>
			<a title="Pokaż menu główne bazy eSerwis" class="normalfont" style="color:blue; font-weight:bold;" href="#" value="Pokaż menu główne" onClick="$('#mainmenu').load('mainmenu.php?es_prawa=<?php echo $es_prawa; ?>&currentuser=<?php echo urlencode($currentuser); ?>&adminname=<?php echo urlencode($adminname); ?>&es_block=<?php echo $es_block; ?>&es_login=<?php echo urlencode($es_login); ?>'); $('#show_mm').hide(); $('#hide_mm').show();" >Pokaż menu główne</a>
			<?php 
			echo "</sub>";

			echo "<sub id=hide_mm style='display:none; position:relative;float:left;margin-left:3px;text-decoration:none;' class=normaltext>";
			echo "&nbsp;|&nbsp;<a title='Ukryj menu główne bazy eSerwis' class=normalfont style='color:blue; font-weight:bold;' href=# value='Ukryj menu główne' onClick=\"$('#mainmenu').hide(); $('#show_mm').show(); $('#hide_mm').hide();\" >Ukryj menu główne</a>";
			echo "</sub>";
			

		
		echo "</div>";
	

		echo "<div class=hideme>";
		echo "<a href=# class=normalfont title=' Wydrukuj zawartość strony ' onClick='window.print();'>";
		if ($pokaz_ikony==0) { 
			echo "<img src=img/print_preview.gif class=imgoption style='position:relative;float:right;margin-right:6px;' border=0>";
		} else echo "<sub style='position:relative;float:right;margin-right:3px;text-decoration:none;'><b>Drukuj</b></sub>";
		echo "</a>";
		echo "</div>";
	
	echo "Przeglądanie bazy zgłoszeń ".$header_add."";
	echo "</h4><hr style='margin:0px;' />";

	*/
	if ($es_mminhd==1) { 
		pageheader("Przeglądanie bazy zgłoszeń ".$header_add."",1,0,false);
	} else {
		pageheader("Przeglądanie bazy zgłoszeń ".$header_add."",1,1,false);
	}
	

	?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();

	if ($_REQUEST[sr]!='search-wyniki') {	
		startbuttonsarea("center");		
		if ($sa==0) { } else {
			echo "<a class=paging href=$PHP_SELF?sa=0&page=$paget&p1=$p1&p2=$p2&p3=$p3&p4=$p4&p5=$p5&p6=$p6&p7=$p7&sr=$sr&s=$s>Dziel na strony</a>";
		}
		endbuttonsarea();
	}
	
	?><script>ShowWaitingMessage('Trwa aktualizowanie liczników','licznik_refresh');</script><?php ob_flush(); flush();

	startbuttonsarea("center");
	echo "<table class='maxwidth hideme' cellspacing=1 ";
	if ($sr=='search-wyniki') echo "style='display:none;'";
	echo "><tr><td style='text-align:center;'>";

	echo "<a href=# class=normalfont title=' auto filtr '>A</a><input class=border0 type=checkbox id=autofiltr name=autofiltr"; 
	if ($_REQUEST[p7]=='true') echo " checked=checked";
	echo ">";
	// wg dnia
	echo "<select class=select_hd_p_zgloszenia "; 
	if (strlen($_REQUEST[p1])>1) echo " style='background-color:yellow; '"; 
	echo " id=filtr1 name=filtr1 onChange=\"ApplyFiltrHD(document.getElementById('autofiltr').checked); \" />";
	echo "<option value='X'"; 	if ($_REQUEST[p1]=='X') echo " SELECTED";	echo ">wg daty</option>\n"; 
	echo "<option value=0";		if ($_REQUEST[p1]=='0') echo " SELECTED";	echo ">-wszystkie-</option>\n";
	$dddd = date("Y-m-d");
	echo "<option value='$dddd'>$dddd</option>\n";
	
	for ($cd=1; $cd<30; $cd++) echo "<option value='".SubstractDays($cd,$dddd)."'>".SubstractDays($cd,$dddd)."</option>\n";
	echo "</select>";
	echo "&nbsp;";
	// wg kategorii
	echo "<select class=select_hd_p_zgloszenia ";
	if ($_REQUEST[p2]>=1) echo " style='background-color:yellow; '"; 
	echo " id=filtr2 name=filtr2 onChange=\"ApplyFiltrHD(document.getElementById('autofiltr').checked);\" />";
	echo "<option value='X'"; 	if ($_REQUEST[p2]=='X') echo " SELECTED";	echo ">wg kategorii</option>\n"; 
	echo "<option value=0";		if ($_REQUEST[p2]=='0') echo " SELECTED";	echo ">-wszystkie-</option>\n";

	$sql_f1="SELECT * FROM $dbname_hd.hd_kategoria WHERE (hd_kategoria.hd_kategoria_wlaczona=1) ORDER BY hd_kategoria_display_order ASC";
	$result_f1=mysql_query($sql_f1,$conn) or die($k_b);
	
	while ($dane_f1=mysql_fetch_array($result_f1)) {
		$temp_nr = $dane_f1['hd_kategoria_nr'];
		$temp_opis = $dane_f1['hd_kategoria_opis'];
		echo "<option value='$temp_nr'"; if ($_REQUEST[p2]==$temp_nr) echo " SELECTED";	echo ">$temp_opis</option>\n";
	}
	echo "</select>";
	echo "&nbsp;";
	
	// wg podkategorii
	echo "<select class=select_hd_p_zgloszenia ";
	if (($_REQUEST[p3]!='X') && ($_REQUEST[p3]!='')) echo " style='background-color:yellow; '"; 
	echo " id=filtr3 name=filtr3 onChange=\"ApplyFiltrHD(document.getElementById('autofiltr').checked);\" />";
	echo "<option value='X'"; 	if ($_REQUEST[p3]=='X') echo " SELECTED";	echo ">wg podkategorii</option>\n"; 
	echo "<option value='X'";	if ($_REQUEST[p3]=='X') echo " SELECTED";	echo ">-wszystkie-</option>\n";
	
	$sql_f1="SELECT * FROM $dbname_hd.hd_podkategoria WHERE (hd_podkategoria.hd_podkategoria_wlaczona=1) ORDER BY hd_podkategoria_opis ASC";	
	$result_f1=mysql_query($sql_f1,$conn) or die($k_b);
	
	while ($dane_f1=mysql_fetch_array($result_f1)) {
		$temp_nr = $dane_f1['hd_podkategoria_nr'];
		$temp_opis = $dane_f1['hd_podkategoria_opis'];
		echo "<option value='$temp_nr'";	if ($_REQUEST[p3]==$temp_nr) echo " SELECTED";	echo ">$temp_opis</option>\n";
	}
	echo "</select>";
	echo "&nbsp;";

	echo "<input type=hidden name=filtr4 id=filtr4 value='0'>";
	
	// wg statusu
	echo "<select class=select_hd_p_zgloszenia ";
	if (($_REQUEST[p5]!='X') && ($_REQUEST[p5]!='')) echo " style='background-color:yellow; '"; 
	echo " id=filtr5 name=filtr5 onChange=\"ApplyFiltrHD(document.getElementById('autofiltr').checked);\" />";
	echo "<option value='X'"; 	if ($_REQUEST[p5]=='X') echo " SELECTED";	echo ">wg statusu</option>\n"; 
	echo "<option value=0";		if ($_REQUEST[p5]=='0') echo " SELECTED";	echo ">-wszystkie-</option>\n";
	echo "<option value='BZ'";		if ($_REQUEST[p5]=='BZ') echo " SELECTED";	echo ">bez zamkniętych</option>\n";

	$sql_f1="SELECT * FROM $dbname_hd.hd_status WHERE (hd_status.hd_status_wlaczona=1) ORDER BY hd_status_nr ASC";
	$result_f1=mysql_query($sql_f1,$conn) or die($k_b);
	while ($dane_f1=mysql_fetch_array($result_f1)) {
		$temp_nr = $dane_f1['hd_status_nr'];
		$temp_opis = $dane_f1['hd_status_opis'];
		echo "<option value='$temp_nr'";	if ($_REQUEST[p5]==$temp_nr) echo " SELECTED";	echo ">$temp_opis</option>\n";
	}
	echo "</select>";	
	echo "&nbsp;";

	// wg przypisania
	echo "<select class=select_hd_p_zgloszenia ";
	if (strlen($_REQUEST[p6])>1) echo " style='background-color:yellow; '"; 
	echo " id=filtr6 name=filtr6 onChange=\"ApplyFiltrHD(document.getElementById('autofiltr').checked);\" />";
	echo "<option value='X'"; 	if ($_REQUEST[p6]=='X') echo " SELECTED";	echo ">wg przypisania</option>\n"; 
	echo "<option value=0";		if ($_REQUEST[p6]=='0') echo " SELECTED";	echo ">-wszystkie-</option>\n";	
	
	$sql_f1="SELECT user_first_name, user_last_name FROM $dbname.serwis_uzytkownicy WHERE (user_locked=0) and (belongs_to=$es_filia) ORDER BY user_first_name ASC";
	$result_f1=mysql_query($sql_f1,$conn) or die($k_b);
	
	while ($dane_f1=mysql_fetch_array($result_f1)) {
		$temp_osoba1 = $dane_f1['user_first_name'];
		$temp_osoba2 = $dane_f1['user_last_name'];
		$iin = $temp_osoba1 ." ".$temp_osoba2;
		
		if ($iin=='') {
			echo "<option value='9'";	if ($_REQUEST[p6]=='9') echo " SELECTED";	echo ">nie przypisane</option>\n";
		} else {
			echo "<option value='$temp_osoba1 $temp_osoba2'";	if ($_REQUEST[p6]==$iin) echo " SELECTED";	echo ">$temp_osoba1 $temp_osoba2</option>\n";
		}
	}
	echo "</select>";
	echo "&nbsp;";
	
	// wg sprawdzenia 
	echo "<select title=' wg sprawdzenia ' class=select_hd_p_zgloszenia ";
	if (strlen($_REQUEST[p8])!='') echo " style='background-color:yellow; '"; 
	echo " id=filtr8 name=filtr8 onChange=\"if (document.getElementById('filtr5').value!='9') { alert('Tylko zgłoszenia zamknięte mogą mieć ustawiony znacznik `sprawdzone`. Automatycznie ustawiono filtr wg statusu na `zamknięte`'); document.getElementById('filtr5').value='9'; } ApplyFiltrHD(document.getElementById('autofiltr').checked);\" />";
		echo "<option value='X'"; 	if ($_REQUEST[p8]=='') echo " SELECTED";	echo ">wg spr.</option>\n"; 
		echo "<option value='X'";	if ($_REQUEST[p8]=='X') echo " SELECTED";	echo ">-wszystkie-</option>\n";	
		echo "<option value='1'";	if ($_REQUEST[p8]=='1') echo " SELECTED";	echo ">TAK</option>\n";	
		echo "<option value='0'";	if ($_REQUEST[p8]=='0') echo " SELECTED";	echo ">NIE</option>\n";	
	echo "</select>";
	echo "&nbsp;";
	
	echo "<input type=button class=buttons style='font-size:10px; padding:1px; margin:0px;height:20px;' id=ZastosujFiltr value=' Pokaż ' onClick=\"ApplyFiltrHD(true); if ((document.getElementById('filtr1').value!='X') && (document.getElementById('filtr1').value!=0)) createCookie('selected_date',document.getElementById('filtr1').value,1);\" />";
	echo "&nbsp;<input type=button class=buttons style='font-size:10px; padding:1px; margin:0px;height:20px;' value=' Czyść ' onClick=\"document.getElementById('filtr1').selectedIndex=0;document.getElementById('filtr2').selectedIndex=0;document.getElementById('filtr3').selectedIndex=0;document.getElementById('filtr4').selectedIndex=0;document.getElementById('filtr5').selectedIndex=0;document.getElementById('filtr6').selectedIndex=0; eraseCookie('selected_date'); ApplyFiltrHD_clear(true); \">";
	echo "<div id=KomunikatOIlosciZgloszen style='display:none; color:red; font-weight:bold;'>";
	echo "</div>";

	echo "</td></tr></table>";
	endbuttonsarea();

	$phpfile = $PHP_SELF;

	starttable();

	$zbij_wiersze = ($rowpersite + 1)*2;
	
	echo "<th rowspan=$zbij_wiersze width=155 style='background-color:transparent; vertical-align:top; margin:0px; padding:0px;' id=PodgladStatystyk class=hideme >";

	// pasek boczy - początek
	
	echo "<table class=maxwidth style='background-color:transparent;'>";		
	
	// domyślny widok - początek
	echo "<tr style='background-color:transparent;padding:0px;'>";
	echo "<td class=_th style='margin:0px; padding:0px;margin:0px;background-color:transparent;'>";
	echo "<center><input type=button title=' Pokaż zgłoszenia w domyślnym widoku ' class=buttons style='margin-left:0px; margin-right:0px; margin-top:1px; margin-bottom:1px; width:147px;font-weight:bold;' onClick=\"createCookie('hd_p_zgloszenia_div_nr','$sciezka_do_cookie',365); eraseCookie('selected_date'); self.location.href='hd_p_zgloszenia.php?sa=0&p5=BZ&s=AD&page=1'\" value='Domyślny widok'></center>";		
	echo "<input type=hidden id=additional_param name=additional_param value='$_GET[add]'>";			
	echo "</td>";
	echo "</tr>";
	// domyślny widok - koniec
	
	// liczniki dla wszystkich - początek
	echo "<tr>";
	echo "<td class=_th style='margin:0px; padding:0px;'>";
	echo "<table cellspacing=0 cellpadding=1 style='background-color:transparent; padding:0; margin:0;border:0px solid #CCCCD2;'>";
	echo "<tr><td style='padding-top:5px; padding-bottom:5px;'>";
	echo "<a href=# class=normalfont id=pokaz_hd_wszystkie style='display:none' type=button onClick=\"document.getElementById('pokaz_hd_wszystkie').style.display='none'; document.getElementById('ukryj_hd_wszystkie').style.display=''; $('#liczniki_wszyscy_dane').show(); createCookie('hd_p_zgloszenia_wszystkie','TAK',365); WszystkieZgloszeniaShow1(); Refresh_Wszystkie(); \"><img class=imgoption style='border:0px' type=image src=img/expand.gif width=11 height=11>";
	echo "&nbsp;Wszystkie&nbsp;</a>";
	echo "<a href=# class=normalfont id=ukryj_hd_wszystkie style='display:none' type=button onClick=\"document.getElementById('pokaz_hd_wszystkie').style.display=''; document.getElementById('ukryj_hd_wszystkie').style.display='none'; $('#liczniki_wszyscy_dane').hide(); createCookie('hd_p_zgloszenia_wszystkie','NIE',365); WszystkieZgloszeniaHide();\"><img class=imgoption style='border:0px' type=image src=img/collapse.gif  width=11 height=11>";
	echo "&nbsp;Wszystkie&nbsp;</a>";
	echo "</td>";
	echo "<td style='text-align:right;'>";
	echo "<a title=' Kliknij, aby odświeżyć liczniki wszystkich zgłoszeń ' href=# onClick=\"$('#liczniki_wszyscy').load('wait_ajax.php?randval='+ Math.random()); $('#licznik_refresh').show(); $('#licznik_refresh').load('hd_uaktualnij_liczniki.php?f=$es_filia&range=W&moj_nr=$es_nr&randval='+Math.random()+'&refresh_parent=0&todiv=1&sourcepage=hd'); \"><img style=border:0px type=image src=img/hd_notes_refresh.gif width=10 height=10></a>";
	echo "</td>";
	echo "</tr>";
	echo "</table>";
	echo "</td>";
	echo "</tr>";
	
	echo "<tr id=liczniki_wszyscy_dane>";
	echo "<td style='background-color:transparent;'>";
	echo "<table cellspacing=0 cellpadding=0 class='maxwidth hd_licz_header' id=liczniki_wszyscy style='border:0px solid #CCCCD2;'></table>";
	echo "</td>";			
	echo "</tr>";		
	// liczniki dla wszystkich - koniec
	
	// pokazanie zgłoszeń wg typu - początek		
	echo "<tr>";
	echo "<td class=_th style='margin:0px; padding:0px;'>";
	echo "<table cellspacing=0 cellpadding=1 style='background-color:transparent; padding:0; margin:0;border:0px solid #CCCCD2;'>";
	echo "<tr><td style='padding-top:5px; padding-bottom:5px;'>";
	echo "<a href=# class=normalfont id=pokaz_hd_wybrane style='display:none' type=button onClick=\"document.getElementById('pokaz_hd_wybrane').style.display='none'; document.getElementById('ukryj_hd_wybrane').style.display=''; $('#liczniki_wybrane_dane').show(); createCookie('hd_p_zgloszenia_wybrane','TAK',365); document.getElementById('liczniki_wybrane').style.display=''; return false; \"><img class=imgoption style='border:0px' type=image src=img/expand.gif width=11 height=11>";
	echo "&nbsp;Pokaż zgłoszenia&nbsp;</a>";
	
	echo "<a href=# class=normalfont id=ukryj_hd_wybrane style='display:none' type=button onClick=\"document.getElementById('pokaz_hd_wybrane').style.display=''; document.getElementById('ukryj_hd_wybrane').style.display='none'; $('#liczniki_wybrane_dane').hide(); createCookie('hd_p_zgloszenia_wybrane','NIE',365); document.getElementById('liczniki_wybrane').style.display='none'; return false;\"><img class=imgoption style='border:0px' type=image src=img/collapse.gif  width=11 height=11>";
	echo "&nbsp;Pokaż zgłoszenia&nbsp;</a>";
	
	echo "<a href=#><img style=border:0px type=image src=img/empty.gif width=10 height=10></a>";
	echo "</td>";
	echo "</tr>";
	echo "</table>";
	echo "</td>";
	echo "</tr>";
	
	echo "<tr id=liczniki_wybrane_dane>";
	echo "<td style='background-color:transparent;'>";
	echo "<table cellspacing=0 cellpadding=0 class='maxwidth hd_licz_header' id=liczniki_wybrane style='border:0px solid #CCCCD2;'>";
	
	echo "<tr>";
	echo "<td>";
	echo "<font style='font-weight:normal'>";
	if ($_REQUEST[add]=='ptr') {	echo "<b>"; }	
	echo "<a title=' Pokaż otwarte zgłoszenia z przesuniętymi terminani rozpoczęcia realizacji ' href=# class='normalfont' style='margin-top:6px;' onClick=\"createCookie('hd_p_zgloszenia_div_nr','$sciezka_do_cookie',365); self.location.href='hd_p_zgloszenia.php?sa=0&sr=0&p5=BZ&page=1&add=ptr&p1=$p1&p2=&p3=$p3&p4=$p4&p5=BZ&p6=$p6&sk=".urlencode($_REQUEST[sk])."&st=".urlencode($_REQUEST[st])."&ss=$_REQUEST[ss]'\" >Z przes. terminem</a>";
	if ($_REQUEST[add]=='ptr') {	echo "</b>"; }	
	echo "</font>";
	echo "</td>";
	echo "<td>";
	if (($_REQUEST[p1]!='') && ($_REQUEST[p1]!='X')) {
		echo "<a title=' Wyeksportuj zgłoszenia z przesuniętym terminem rozpoczęcia z dnia $_REQUEST[p1] do pliku XLS '><input class=imgoption type=image src=img/przesuniety_termin.gif onclick=\"if (confirm('Pokazać zgłoszenia z przesuniętym terminem (OK) czy wyeksportować do XLS (Cancel) ?')) { self.location.href='hd_p_zgloszenia.php?sa=0&sr=0&p5=BZ&page=1&add=ptr&p1=$p1&p2=&p3=$p3&p4=$p4&p5=BZ&p6=$p6&sk=".urlencode($_REQUEST[sk])."&st=".urlencode($_REQUEST[st])."&ss=$_REQUEST[ss]'; return false; } else { newWindow_r(800,600,'do_xls_htmlexcel_hd_g_raport_przesuniete_terminy.php?okres=$_REQUEST[p1]&typ=day'); return false; }\"></a>";
	} else {
		echo "<a title=' Wyeksportuj wszystkie otwarte zgłoszenia z przesuniętym terminem rozpoczęcia do pliku XLS. Aby wyeksportować do XLS zgłoszenia z dowolnego dnia (z przesuniętym tereminem rozpoczęcia) ustaw filtr na wybrany dzień. '><input class=imgoption type=image src=img/przesuniety_termin.gif onclick=\"if (confirm('Pokazać zgłoszenia z przesuniętym terminem (OK) czy wyeksportować do XLS (Cancel) ?')) { self.location.href='hd_p_zgloszenia.php?sa=0&sr=0&p5=BZ&page=1&add=ptr&p1=$p1&p2=&p3=$p3&p4=$p4&p5=BZ&p6=$p6&sk=".urlencode($_REQUEST[sk])."&st=".urlencode($_REQUEST[st])."&ss=$_REQUEST[ss]'; return false; } else { newWindow_r(800,600,'do_xls_htmlexcel_hd_g_raport_przesuniete_terminy.php?okres=$_REQUEST[p1]&typ=all'); return false; } \"></a>";
	}		
	echo "</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td>";	
	echo "<font style='font-weight:normal'>";
	if ($_REQUEST[add]=='pzw') {	echo "<b>"; }	
	echo "<a title=' Pokaż zgłoszenia powiązane z wyjazdami ' href=# class='normalfont' style='margin-top:6px;' onClick=\"createCookie('hd_p_zgloszenia_div_nr','$sciezka_do_cookie',365); self.location.href='hd_p_zgloszenia.php?sa=0&p5=BZ&s=AD&page=1&add=pzw&p1=$p1&p2=&p3=$p3&p4=$p4&p5=$p5&p6=$p6&sk=".urlencode($_REQUEST[sk])."&st=".urlencode($_REQUEST[st])."&ss=$_REQUEST[ss]'\" >Pow. z wyjazdem</a>";
	if ($_REQUEST[add]=='pzw') {	echo "</b>"; }
	echo "</font>";
	echo "</td>";
	echo "<td class=center>";
	echo "<a title=' Pokaż zgłoszenia powiązane z wyjazdami ' href=# class='normalfont' style='margin-top:6px;' onClick=\"createCookie('hd_p_zgloszenia_div_nr','$sciezka_do_cookie',365); self.location.href='hd_p_zgloszenia.php?sa=0&p5=BZ&s=AD&page=1&add=pzw&p1=$p1&p2=&p3=$p3&p4=$p4&p6=$p6&sk=".urlencode($_REQUEST[sk])."&st=".urlencode($_REQUEST[st])."&ss=$_REQUEST[ss]'\" ><img class=imgoption src=img/car_s1.gif border=0 width=12 height=12></a>";
	echo "</td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td>";
	echo "<font style='font-weight:normal'>";
	if ($_REQUEST[add]=='pzn') {	echo "<b>"; }		
	echo "<a title=' Pokaż zgłoszenia powiązane z naprawami ' href=# class='normalfont' style='margin-top:6px;' onClick=\"createCookie('hd_p_zgloszenia_div_nr','$sciezka_do_cookie',365); self.location.href='hd_p_zgloszenia.php?sa=0&p5=BZ&s=AD&page=1&add=pzn&p1=$p1&p2=&p3=$p3&p4=$p4&p6=$p6&sk=".urlencode($_REQUEST[sk])."&st=".urlencode($_REQUEST[st])."&ss=$_REQUEST[ss]'\" >Pow. z naprawami</a>";
	if ($_REQUEST[add]=='pzn') {	echo "</b>"; }
	echo "</font>";
	echo "</td>";
	echo "<td class=center>";
	echo "<a title=' Pokaż zgłoszenia powiązane z naprawami ' href=# class='normalfont' style='margin-top:6px;' onClick=\"createCookie('hd_p_zgloszenia_div_nr','$sciezka_do_cookie',365); self.location.href='hd_p_zgloszenia.php?sa=0&p5=BZ&s=AD&page=1&add=pzn&p1=$p1&p2=&p3=$p3&p4=$p4&p6=$p6&sk=".urlencode($_REQUEST[sk])."&st=".urlencode($_REQUEST[st])."&ss=$_REQUEST[ss]'\" ><img class=imgoption src=img/naprawa_unknown_s.gif border=0 width=12 height=12></a>";
	echo "</td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td>";
	echo "<font style='font-weight:normal'>";
	if ($_REQUEST[add]=='pzpss') {	echo "<b>"; }		
	echo "<a title=' Pokaż zgłoszenia powiązane z przekazaniem sprzętu serwisowego ' href=# class='normalfont' style='margin-top:6px;' onClick=\"createCookie('hd_p_zgloszenia_div_nr','$sciezka_do_cookie',365); self.location.href='hd_p_zgloszenia.php?sa=0&p5=BZ&s=AD&page=1&add=pzpss&p1=$p1&p2=&p3=$p3&p4=$p4&p6=$p6&sk=".urlencode($_REQUEST[sk])."&st=".urlencode($_REQUEST[st])."&ss=$_REQUEST[ss]'\" >Pow. z przek. s. ser.</a>";
	if ($_REQUEST[add]=='pzpss') {	echo "</b>"; }
	echo "</font>";
	echo "</td>";
	echo "<td class=center>";
	echo "<a title=' Pokaż zgłoszenia powiązane z przekazaniem sprzętu serwisowego ' href=# class='normalfont' style='margin-top:6px;' onClick=\"createCookie('hd_p_zgloszenia_div_nr','$sciezka_do_cookie',365); self.location.href='hd_p_zgloszenia.php?sa=0&p5=BZ&s=AD&page=1&add=pzpss&p1=$p1&p2=&p3=$p3&p4=$p4&p6=$p6&sk=".urlencode($_REQUEST[sk])."&st=".urlencode($_REQUEST[st])."&ss=$_REQUEST[ss]'\" ><img class=imgoption src=img/service.gif border=0 width=12 height=12></a>";
	echo "</td>";
	echo "</tr>";		
	
	echo "<tr>";
	echo "<td>";
	echo "<font style='font-weight:normal'>";
	if ($_REQUEST[add]=='sn14') { echo "<b>"; }		
	echo "<a title=' Pokaż zgłoszenia starsze niż 14 dni ' href=# class='normalfont' style='margin-top:6px;' onClick=\"createCookie('hd_p_zgloszenia_div_nr','$sciezka_do_cookie',365); self.location.href='hd_p_zgloszenia.php?sa=0&sr=0&p5=BZ&page=1&add=sn14&p1=$p1&p2=&p3=$p3&p4=$p4&p6=$p6&sk=".urlencode($_REQUEST[sk])."&st=".urlencode($_REQUEST[st])."&ss=$_REQUEST[ss]'\" >Starsze niż 14 dni</a>";
	if ($_REQUEST[add]=='sn14') { echo "</b>"; }
	echo "</font>";
	echo "</td>";
	echo "<td class=center>";
	echo "<a title=' Pokaż zgłoszenia starsze niż 14 dni ' href=# class='normalfont' style='margin-top:6px;' onClick=\"createCookie('hd_p_zgloszenia_div_nr','$sciezka_do_cookie',365); self.location.href='hd_p_zgloszenia.php?sa=0&sr=0&p5=BZ&page=1&add=sn14&p1=$p1&p2=&p3=$p3&p4=$p4&p6=$p6&sk=".urlencode($_REQUEST[sk])."&st=".urlencode($_REQUEST[st])."&ss=$_REQUEST[ss]'\" ><img class=imgoption src=img/sn14.gif border=0 width=12 height=12></a>";
	echo "</td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td>";
	echo "<font style='font-weight:normal'>";
	if ($_REQUEST[add]=='rekl') { echo "<b>"; }		
	echo "<a title=' Pokaż zgłoszenia reklamacyjne ' href=# class='normalfont' style='margin-top:6px;' onClick=\"createCookie('hd_p_zgloszenia_div_nr','$sciezka_do_cookie',365); self.location.href='hd_p_zgloszenia.php?sa=0&sr=0&p5=BZ&page=1&add=rekl&p1=$p1&p2=&p3=$p3&p4=$p4&p6=$p6&sk=".urlencode($_REQUEST[sk])."&st=".urlencode($_REQUEST[st])."&ss=$_REQUEST[ss]'\" >Reklamacyjne</a>";
	if ($_REQUEST[add]=='rekl') {	echo "</b>"; }
	echo "</font>";
	echo "</td>";
	echo "<td class=center>";
	echo "<a title=' Pokaż zgłoszenia reklamacyjne ' href=# class='normalfont' style='margin-top:6px;' onClick=\"createCookie('hd_p_zgloszenia_div_nr','$sciezka_do_cookie',365); self.location.href='hd_p_zgloszenia.php?sa=0&sr=0&p5=BZ&page=1&add=rekl&p1=$p1&p2=&p3=$p3&p4=$p4&p6=$p6&sk=".urlencode($_REQUEST[sk])."&st=".urlencode($_REQUEST[st])."&ss=$_REQUEST[ss]'\" ><img class=imgoption src=img/have_reklamacyjne_s.gif border=0 height=12></a>";
	echo "</td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td>";
	echo "<font style='font-weight:normal'>";
	if ($_REQUEST[add]=='wp') {	echo "<b>"; }	
	echo "<a title=' Pokaż zgłoszenia powiązane z wymianami podzespołów ' href=# class='normalfont' style='margin-top:6px;' onClick=\"createCookie('hd_p_zgloszenia_div_nr','$sciezka_do_cookie',365); self.location.href='hd_p_zgloszenia.php?sa=0&sr=0&p5=BZ&page=1&add=wp&p1=$p1&p2=&p3=$p3&p4=$p4&p6=$p6&sk=".urlencode($_REQUEST[sk])."&st=".urlencode($_REQUEST[st])."&ss=$_REQUEST[ss]'\" >Pow. z wym. podz.</a>";
	if ($_REQUEST[add]=='wp') {	echo "</b>"; }
	echo "</font>";
	echo "</td>";
	echo "<td class=center>";
	echo "<a title=' Pokaż zgłoszenia powiązane z wymianami podzespołów ' href=# class='normalfont' style='margin-top:6px;' onClick=\"createCookie('hd_p_zgloszenia_div_nr','$sciezka_do_cookie',365); self.location.href='hd_p_zgloszenia.php?sa=0&sr=0&p5=BZ&page=1&add=wp&p1=$p1&p2=&p3=$p3&p4=$p4&p6=$p6&sk=".urlencode($_REQUEST[sk])."&st=".urlencode($_REQUEST[st])."&ss=$_REQUEST[ss]'\" ><img class=imgoption src=img/wp.gif border=0 width=12 height=12></a>";
	echo "</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td>";
	echo "<font style='font-weight:normal'>";
	if ($_REQUEST[add]=='ww') { echo "<b>"; }		
	echo "<a title=' Pokaż zgłoszenia wymagające wyjazdu ' href=# class='normalfont' style='margin-top:6px;' onClick=\"createCookie('hd_p_zgloszenia_div_nr','$sciezka_do_cookie',365); self.location.href='hd_p_zgloszenia.php?sa=0&sr=0&p5=BZ&page=1&add=ww&p1=$p1&p2=&p3=$p3&p4=$p4&p6=$p6&sk=".urlencode($_REQUEST[sk])."&st=".urlencode($_REQUEST[st])."&ss=$_REQUEST[ss]'\" >Wymagane wyjazdy</a>";
	if ($_REQUEST[add]=='ww') {	echo "</b>"; }
	echo "</font>";
	echo "</td>";
	echo "<td class=center>";
	echo "<a title=' Pokaż zgłoszenia wymagające wyjazdu ' href=# class='normalfont' style='margin-top:6px;' onClick=\"createCookie('hd_p_zgloszenia_div_nr','$sciezka_do_cookie',365); self.location.href='hd_p_zgloszenia.php?sa=0&sr=0&p5=BZ&page=1&add=ww&p1=$p1&p2=&p3=$p3&p4=$p4&p6=$p6&sk=".urlencode($_REQUEST[sk])."&st=".urlencode($_REQUEST[st])."&ss=$_REQUEST[ss]'\" ><img class=imgoption src=img/car_ww_s.gif border=0 width=12 height=12></a>";
	echo "</td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td>";
	echo "<font style='font-weight:normal'>";
	echo "<u>Awarie:</u>";
	echo "</font>";						
	echo "</td>";
	echo "<td class=center>";
	echo "</td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td class=right>";
	echo "<font style='font-weight:normal'>";
	if (($_REQUEST[add]=='nr') && ($_REQUEST[p2]=='2')) { echo "<b>"; }	
	echo "<a title='Pokaż nierozwiązane zgłoszenia awarii' href=# class='normalfont' style='margin-top:6px;' onClick=\"createCookie('hd_p_zgloszenia_div_nr','$sciezka_do_cookie',365); self.location.href='hd_p_zgloszenia.php?sa=0&sr=0&p5=BZ&page=1&add=nr&p1=&p2=2&p3=$p3&p4=$p4&p5=BZ&p6=$p6&sk=".urlencode($_REQUEST[sk])."&st=".urlencode($_REQUEST[st])."&ss=$_REQUEST[ss]'\" >nierozwiązane</a>";
	if (($_REQUEST[add]=='nr') && ($_REQUEST[p2]=='2')) { echo "</b>"; }
	echo "</font>";						
	echo "</td>";
	echo "<td class=center>";
	echo "<a title='Pokaż nierozwiązane zgłoszenia awarii' href=# class='normalfont' style='margin-top:6px;' onClick=\"createCookie('hd_p_zgloszenia_div_nr','$sciezka_do_cookie',365); self.location.href='hd_p_zgloszenia.php?sa=0&sr=0&p5=BZ&page=1&add=nr&p1=&p2=2&p3=$p3&p4=$p4&p5=BZ&p6=$p6&sk=".urlencode($_REQUEST[sk])."&st=".urlencode($_REQUEST[st])."&ss=$_REQUEST[ss]'\" ><img class=imgoption src=img/az_s.gif border=0 width=12 height=12></a>";
	echo "</td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td class=right>";
	echo "<font style='font-weight:normal'>";
	if ($_REQUEST[add]=='z_az') { echo "<b>"; }		
	echo "<a title=' Pokaż zamknięte awarie ' href=# class='normalfont' style='margin-top:6px;' onClick=\"createCookie('hd_p_zgloszenia_div_nr','$sciezka_do_cookie',365); self.location.href='hd_p_zgloszenia.php?sa=0&sr=0&p5=BZ&page=1&add=&p1=$p1&p2=2&p3=$p3&p4=$p4&p6=$p6&p5=9&sk=".urlencode($_REQUEST[sk])."&st=".urlencode($_REQUEST[st])."&ss=$_REQUEST[ss]'\" >zamknięte</a>";
	if ($_REQUEST[add]=='z_az') {	echo "</b>"; }
	echo "</font>";
	echo "</td>";
	echo "<td class=center>";
	echo "<a title=' Pokaż zamknięte awarie ' href=# class='normalfont' style='margin-top:6px;' onClick=\"createCookie('hd_p_zgloszenia_div_nr','$sciezka_do_cookie',365); self.location.href='hd_p_zgloszenia.php?sa=0&sr=0&p5=BZ&page=1&add=&p1=$p1&p2=6&p3=$p3&p4=$p4&p6=$p6&p5=9&sk=".urlencode($_REQUEST[sk])."&st=".urlencode($_REQUEST[st])."&ss=$_REQUEST[ss]'\" ><img class=imgoption src=img/az_z.gif border=0 width=12 height=12></a>";
	echo "</td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td>";
	echo "<font style='font-weight:normal'>";
	echo "<u>Awarie krytyczne:</u>";
	echo "</font>";						
	echo "</td>";
	echo "<td class=center>";
	echo "</td>";
	echo "</tr>";
	
	echo "<tr>";
	echo "<td class=right>";
	echo "<font style='font-weight:normal'>";
	if (($_REQUEST[add]=='nr') && ($_REQUEST[p2]=='6')) { echo "<b>"; }		
	echo "<a title='Pokaż nierozwiązane zgłoszenia awarii krytycznych ' href=# class='normalfont' style='margin-top:6px;' onClick=\"createCookie('hd_p_zgloszenia_div_nr','$sciezka_do_cookie',365); self.location.href='hd_p_zgloszenia.php?sa=0&sr=0&p5=BZ&page=1&add=nr&p1=&p2=6&p3=$p3&p4=$p4&p5=BZ&p6=$p6&sk=".urlencode($_REQUEST[sk])."&st=".urlencode($_REQUEST[st])."&ss=$_REQUEST[ss]'\" >nierozwiązane</a>";
	if (($_REQUEST[add]=='nr') && ($_REQUEST[p2]=='6')) { echo "<b>"; }
	echo "</font>";
	echo "</td>";
	echo "<td class=center>";
	echo "<a title='Pokaż nierozwiązane zgłoszenia awarii krytycznych ' href=# class='normalfont' style='margin-top:6px;' onClick=\"createCookie('hd_p_zgloszenia_div_nr','$sciezka_do_cookie',365); self.location.href='hd_p_zgloszenia.php?sa=0&sr=0&p5=BZ&page=1&add=nr&p1=&p2=6&p3=$p3&p4=$p4&p5=BZ&p6=$p6&sk=".urlencode($_REQUEST[sk])."&st=".urlencode($_REQUEST[st])."&ss=$_REQUEST[ss]'\" ><img class=imgoption src=img/ak_s.gif border=0 width=12 height=12></a>";
	echo "</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td class=right>";
	echo "<font style='font-weight:normal'>";
	if ($_REQUEST[add]=='z_ak') { echo "<b>"; }		
	echo "<a title=' Pokaż zamknięte awarie ' href=# class='normalfont' style='margin-top:6px;' onClick=\"createCookie('hd_p_zgloszenia_div_nr','$sciezka_do_cookie',365); self.location.href='hd_p_zgloszenia.php?sa=0&sr=0&p5=BZ&page=1&add=&p1=$p1&p2=6&p3=$p3&p4=$p4&p6=$p6&p5=9&sk=".urlencode($_REQUEST[sk])."&st=".urlencode($_REQUEST[st])."&ss=$_REQUEST[ss]'\" >zamknięte</a>";
	if ($_REQUEST[add]=='z_ak') {	echo "</b>"; }
	echo "</font>";
	echo "</td>";
	echo "<td class=center>";
	echo "<a title=' Pokaż zamknięte awarie krytyczne ' href=# class='normalfont' style='margin-top:6px;' onClick=\"createCookie('hd_p_zgloszenia_div_nr','$sciezka_do_cookie',365); self.location.href='hd_p_zgloszenia.php?sa=0&sr=0&p5=BZ&page=1&add=&p1=$p1&p2=6&p3=$p3&p4=$p4&p6=$p6&p5=9&sk=".urlencode($_REQUEST[sk])."&st=".urlencode($_REQUEST[st])."&ss=$_REQUEST[ss]'\" ><img class=imgoption src=img/ak_z.gif border=0 width=12 height=12></a>";
	echo "</td>";
	echo "</tr>";

	//					echo "<tr>";
	//					echo "<td colspan=2>";
	//						echo "<center><input type=button title=' Pokaż zgłoszenia w domyślnym widoku ' class=buttons style='margin-top:5px;'  onClick=\"createCookie('hd_p_zgloszenia_div_nr','$sciezka_do_cookie',365); eraseCookie('selected_date'); self.location.href='hd_p_zgloszenia.php?sa=0&p5=BZ&s=AD&page=1'\" value='Domyślny widok'></center>";		
	//						echo "<input type=hidden id=additional_param name=additional_param value='$_GET[add]'>";			
	//					echo "</td>";
	//					echo "</tr>";
	echo "</table>";
	echo "</td>";			
	echo "</tr>";
	// pokazanie zgłoszeń wg typu - koniec
	
	// liczniki dla mnie - początek
	echo "<tr>";
	echo "<td class=_th style='margin:0px; padding:0px;'>";
	echo "<table cellspacing=0 cellpadding=1 style='background-color:transparent; padding:0; margin:0;border:0px solid #CCCCD2;'>";
	echo "<tr><td style='padding-top:5px; padding-bottom:5px;'>";
	echo "<a href=# class=normalfont id=pokaz_hd_moje style='display:none' type=button onClick=\"document.getElementById('pokaz_hd_moje').style.display='none'; document.getElementById('ukryj_hd_moje').style.display=''; $('#liczniki_moje_dane').show(); createCookie('hd_p_zgloszenia_moje','TAK',365); MojeZgloszeniaShow(); Refresh_Moje(); \"><img class=imgoption style='border:0px' type=image src=img/expand.gif width=11 height=11>";	
	echo "&nbsp;Moje&nbsp;</a>";
	
	echo "<a href=# class=normalfont id=ukryj_hd_moje style='display:none' type=button onClick=\"document.getElementById('pokaz_hd_moje').style.display=''; document.getElementById('ukryj_hd_moje').style.display='none'; $('#liczniki_moje_dane').hide(); createCookie('hd_p_zgloszenia_moje','NIE',365); MojeZgloszeniaHide(); return false;\"><img class=imgoption style='border:0px' type=image src=img/collapse.gif width=11 height=11>";
	echo "&nbsp;Moje&nbsp;</a>";
	echo "</td>";
	echo "<td style='text-align:right;'>";
	echo "<a title=' Kliknij, aby odświeżyć liczniki moich zgłoszeń ' href=# onClick=\"$('#liczniki_moje').load('wait_ajax.php?randval='+ Math.random()); $('#licznik_refresh').show(); $('#licznik_refresh').load('hd_uaktualnij_liczniki.php?f=$es_filia&range=M&moj_nr=$es_nr&cu=".urlencode($currentuser)."&randval='+Math.random()+'&refresh_parent=0&todiv=1&sourcepage=hd'); \"><img style=border:0px type=image src=img/hd_notes_refresh.gif width=10 height=10></a>&nbsp;";

	echo " <a href=# class=normalfont id=ilosc_godzin_count title=' Ilość przepracowanych godzin w dniu dzisiejszym (".Date('Y-m-d')."). Kliknij, aby odświeżyć ilość przepracowanych godzin '><img style=border:0px type=image src=img/clock_hd.gif width=10 height=10></a>";
	
	echo " <span style='text-align:center;' title='Ilość przepracowanych godzin w dniu dzisiejszym (".Date('Y-m-d').") ' id=ilosc_godzin></span>";
	echo "</td>";
	echo "</tr>";
	echo "</table>";
	echo "</td>";
	echo "</tr>";
	
	echo "<tr id=liczniki_moje_dane>";
	echo "<td style='background-color:transparent;'>";
	echo "<table cellspacing=0 cellpadding=0 class='maxwidth hd_licz_header' id=liczniki_moje style='border:0px solid #CCCCD2;'></table>";
	echo "</td>";			
	echo "</tr>";
	
	// liczniki dla mnie - koniec		
	
	// wyszukiwanie - początek
	echo "<tr>";
	echo "<td class=_th style='margin:0px; padding:0px;'>";
	echo "<table cellspacing=0 cellpadding=1 style='background-color:transparent; padding:0; margin:0;border:0px solid #CCCCD2;'>";
	echo "<tr><td style='padding-top:5px; padding-bottom:5px;'>";
	echo "<a href=# class=normalfont id=pokaz_szukaj_zgl style='display:none' type=button onClick=\"document.getElementById('pokaz_szukaj_zgl').style.display='none'; document.getElementById('ukryj_szukaj_zgl').style.display=''; $('#szukaj_dane').show(); createCookie('hd_szukaj_zgl','TAK',365); \"><img class=imgoption style='border:0px' type=image src=img/expand.gif width=11 height=11>";	
	echo "&nbsp;Wyszukiwanie&nbsp;</a>";
	
	echo "<a href=# class=normalfont id=ukryj_szukaj_zgl style='display:none' type=button onClick=\"document.getElementById('pokaz_szukaj_zgl').style.display=''; document.getElementById('ukryj_szukaj_zgl').style.display='none'; $('#szukaj_dane').hide(); createCookie('hd_szukaj_zgl','NIE',365); return false;\"><img class=imgoption style='border:0px' type=image src=img/collapse.gif width=11 height=11>";
	echo "&nbsp;Wyszukiwanie&nbsp;</a>";
	echo "</td>";
	echo "</tr>";
	echo "</table>";
	echo "</td>";
	echo "</tr>";
	
	echo "<tr id=szukaj_dane><td style='padding-top:5px; padding-bottom:5px;' class=center>";
	echo "<form name=szukaj_szybko action=$PHP_SELF method=POST >";
	echo "&nbsp;<input name=search_zgl_nr id=search_zgl_nr title=' Wyszukiwanie po numerze zgłoszenia Helpdesk/Landesk' maxlength=10 size=10 style='font-size:12px;color:#686868;' value='' onKeyPress=\"return filterInputEnter(1, event, false); \" onFocus=\"this.value=''; \" />";
	echo "&nbsp; <input type=submit class=buttons style='margin-top:4px; padding:2px 3px 1px 3px;' value='Szukaj'>";	
	echo "<br />";
	echo "<span style='padding:0px'><a href=# class='normalfont' style='font-size:9px; margin-bottom:15px;' onClick=\"self.location.href='hd_p_zgloszenia.php?sa=0&sr=1'\" ><font style='font-weight:normal'>zaawansowane</font></a></span>";
	
	if ($_REQUEST[sr]=='search-wyniki') {
		echo "<br /><br /><input type=button class=buttons style='background-color:green;' value='Zmień wyszukiwanie' onClick=\"self.location.href='hd_p_zgloszenia.php?sa=0&sr=1&zmien_ww=1&search_zgl_nr=".urlencode($_REQUEST[search_zgl_nr])."&search_hadim_nr=".urlencode($_REQUEST[search_hadim_nr])."&sd=".urlencode($_REQUEST[sd])."&sk=".urlencode($_REQUEST[sk])."&so=".urlencode($_REQUEST[so])."&p6=".urlencode($_REQUEST[p6])."&p2=".urlencode($_REQUEST[p2])."&p3=".urlencode($_REQUEST[p3])."&search_eserwis_nr=".urlencode($_REQUEST[search_eserwis_nr])."&st=".urlencode($_REQUEST[st])."&st_wc=".urlencode($_REQUEST[st_wc])."'; return false; \" />";
	}
	echo "<input type=hidden name=sa id=sa value='0'>";
	echo "<input type=hidden name=filtr value='true'>";
	echo "<input type=hidden name=sr id=sr value='search-wyniki'>";
	$sciezka_do_cookie = "hd_p_zgloszenie_kroki.php?nr=0&id=0";
	echo "</form>";		
	echo "</td>";
	
	echo "</tr>";
	// wyszukiwanie - koniec
	
	// notatki - początek
	echo "<tr>";
	echo "<td class=_th style='margin:0px; padding:0px;'>";
	echo "<table cellspacing=0 cellpadding=1 style='background-color:transparent; padding:0; margin:0;border:0px solid #CCCCD2;'>";
	echo "<tr><td style='padding-top:5px; padding-bottom:5px;'>";
	echo "<a href=# class=normalfont id=pokaz_notatki style='display:none' type=button onClick=\"document.getElementById('pokaz_notatki').style.display='none'; document.getElementById('ukryj_notatki').style.display=''; $('#notatki_dane').show(); $('#notatki_dane1').show(); createCookie('hd_pokaz_notatki','TAK',365); PokazNotatki(true); \"><img class=imgoption style='border:0px' type=image src=img/expand.gif width=11 height=11>";	
	echo "&nbsp;Notatki&nbsp;</a>";		
	echo "<a href=# class=normalfont id=ukryj_notatki style='display:none' type=button onClick=\"document.getElementById('pokaz_notatki').style.display=''; document.getElementById('ukryj_notatki').style.display='none'; $('#notatki_dane').hide(); $('#notatki_dane1').hide(); createCookie('hd_pokaz_notatki','NIE',365); PokazNotatki(false);\"><img class=imgoption style='border:0px' type=image src=img/collapse.gif width=11 height=11>";		
	echo "&nbsp;Notatki&nbsp;</a>";
	
	echo "</td>";
	echo "<td style='text-align:right;'>";
	echo "<a href=# class=normalfont onClick=\"$('#notatki').load('wait_ajax.php?randval='+ Math.random()); $('#count_notatki_moje').load('hd_count_notes.php?user_id=$es_nr&randval='+ Math.random());$('#count_notatki_moje'); $('#notatki').load('hd_refresh_notes.php?userid=$es_nr&randval='+ Math.random()); $('#notatki1').hide(); return false;\"><img id=notes_refresh title=' Ilość notatek. Kliknij, aby odświeżyć listę ' style=border:0px type=image src=img/hd_notes_refresh.gif width=10 height=10></a>";
	echo " <span id=count_notatki_moje title=' Ilość moich notatek '></span>";
	echo "</td>";
	echo "</tr>";
	echo "</table>";
	echo "</td>";
	echo "</tr>";
	
	echo "<tr id=notatki_dane>";
	echo "<td style='background-color:transparent;'>";
	echo "<span id=notatki1></span>";
	echo "<span id=notatki></span>";
	//echo "<table cellspacing=0 cellpadding=0 class='maxwidth hd_licz_header' id=liczniki_moje style='border:0px solid #CCCCD2;'></table>";
	echo "</td>";			
	echo "</tr>";

	echo "<tr id=notatki_dane1>";
	echo "<td style='background-color:transparent; padding-top:5px' class=center>";
	echo "<input type=button class='buttons' title=' Dodaj notatkę ' onclick=\"newWindow_r(600,400,'hd_d_note.php?user_id=$es_nr&norefresh=0'); $('#count_notatki_moje').load('hd_count_notes.php?user_id=$es_nr&randval='+ Math.random());$('#count_notatki_moje'); $('#notatki').load('hd_refresh_notes.php?userid=$es_nr&randval='+ Math.random());\" value=' Dodaj notatkę ' />";
	echo "</td>";			
	echo "</tr>";		
	// notatki - koniec	
	
	if ((($kierownik_nr==$es_nr) || ($is_dyrektor==1)) && ($funkcja_kontroli_pracownikow==1)) {		
		// pracownicy - początek
		echo "<tr>";
		echo "<td class=_th style='margin:0px; padding:0px;'>";
		echo "<table cellspacing=0 cellpadding=1 style='background-color:transparent; padding:0; margin:0;border:0px solid #CCCCD2;'>";
		echo "<tr><td style='padding-top:5px; padding-bottom:5px;'>";
		
		echo "<a href=# class=normalfont id=pokaz_pracownikow style='display:none' type=button onClick=\"document.getElementById('pokaz_pracownikow').style.display='none'; document.getElementById('ukryj_pracownikow').style.display=''; createCookie('hd_pokaz_pracownikow','TAK',365); $('#pracownicy').show(); $('#pracownicy').load('wait_ajax.php?randval='+ Math.random()); $('#pracownicy').load('hd_refresh_pracownicy.php?randval='+ Math.random()); self.location.reload(true);  return false;\"><img class=imgoption style='border:0px' type=image src=img/expand.gif width=11 height=11>";	
		echo "&nbsp;Pracownicy&nbsp;</a>";
		
		echo "<a href=# class=normalfont id=ukryj_pracownikow style='display:none' type=button onClick=\"document.getElementById('pokaz_pracownikow').style.display=''; document.getElementById('ukryj_pracownikow').style.display='none'; createCookie('hd_pokaz_pracownikow','NIE',365); $('#pracownicy').hide();  return false;\"><img class=imgoption style='border:0px' type=image src=img/collapse.gif width=11 height=11>";
		echo "&nbsp;Pracownicy&nbsp;</a>";
		
		echo "</td>";
		
		echo "<td style='text-align:right;'>";				
		echo "<a title=' Kliknij, aby odświeżyć listę ' href=# onClick=\"$('#pracownicy').load('wait_ajax.php?randval='+ Math.random()); $('#pracownicy').load('hd_refresh_pracownicy.php?randval='+ Math.random()); \"><img style=border:0px type=image src=img/hd_notes_refresh.gif width=10 height=10></a>";
		echo "</td>";
		
		echo "</tr>";
		echo "</table>";
		echo "</td>";
		echo "</tr>";
		
		echo "<tr id=tr_pracownicy>";
		echo "<td style='background-color:transparent;font-weight:normal;'>";
		echo "<span id=pracownicy>&nbsp;</span>";
		echo "</td>";			
		echo "</tr>";
		// pracownicy - koniec		
		
		// błedne zgłoszenia - początek
		echo "<tr>";
		echo "<td class=_th style='margin:0px; padding:0px;'>";
		echo "<table cellspacing=0 cellpadding=1 style='background-color:transparent; padding:0; margin:0;border:0px solid #CCCCD2;'>";
		echo "<tr><td style='padding-top:5px; padding-bottom:5px;'>";
		echo "<a href=# class=normalfont id=pokaz_hd_bledne style='display:none' type=button onClick=\"document.getElementById('pokaz_hd_bledne').style.display='none'; document.getElementById('ukryj_hd_bledne').style.display=''; $('#bledne_dane').show(); createCookie('hd_pokaz_bledne_dane','TAK',365); return false; \"><img class=imgoption style='border:0px' type=image src=img/expand.gif width=11 height=11>";
		echo "&nbsp;Błędy w zgł.</a>";
		
		echo "<a href=# class=normalfont id=ukryj_hd_bledne style='display:none' type=button onClick=\"document.getElementById('pokaz_hd_bledne').style.display=''; document.getElementById('ukryj_hd_bledne').style.display='none'; $('#bledne_dane').hide(); createCookie('hd_pokaz_bledne_dane','NIE',365); return false;\"><img class=imgoption style='border:0px' type=image src=img/collapse.gif  width=11 height=11>";
		echo "&nbsp;Błędy w zgł.</a>";
		
		echo "<a href=#><img style=border:0px type=image src=img/empty.gif width=10 height=10></a>";
		echo "</td>";
		echo "</tr>";
		echo "</table>";
		echo "</td>";
		echo "</tr>";
		
		echo "<tr id=bledne_dane>";
		echo "<td style='background-color:transparent;'>";
		echo "<table cellspacing=0 cellpadding=0 class='maxwidth hd_licz_header' id=liczniki_wybrane style='border:0px solid #CCCCD2;'>";
		
		echo "<tr>";
		echo "<td>";
		echo "<font style='font-weight:normal'>";
		echo "<a title=' Pokaż zgłoszenia z datą rejestracji=0000-00-00 ' href=# class='normalfont' style='margin-top:6px;' onClick=\"self.location.href='hd_p_zgloszenia.php?sa=0&sr=0&p5=X&page=1&add=drz0&p1=$p1&p2=&p3=$p3&p4=$p4&p6=$p6&sk=".urlencode($_REQUEST[sk])."&st=".urlencode($_REQUEST[st])."&ss=$_REQUEST[ss]'\" >Data rej. zgł.</a>";
		echo "</font>";
		echo "</td>";
		echo "<td class=center>";
		echo "<a title=' Pokaż zgłoszenia z datą rejestracji=0000-00-00 ' href=# class='normalfont' style='margin-top:6px;' onClick=\"self.location.href='hd_p_zgloszenia.php?sa=0&sr=0&p5=X&page=1&add=drz0&p1=$p1&p2=&p3=$p3&p4=$p4&p6=$p6&sk=".urlencode($_REQUEST[sk])."&st=".urlencode($_REQUEST[st])."&ss=$_REQUEST[ss]'\" ><img class=imgoption src=img/00.gif border=0 width=12 height=12></a>";
		echo "</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td>";
		echo "<font style='font-weight:normal'>";
		echo "<a title=' Pokaż zgłoszenia z datą rozpoczęcia kroku=0000-00-00 ' href=# class='normalfont' style='margin-top:6px;' onClick=\"self.location.href='hd_p_zgloszenia.php?sa=0&sr=0&p5=X&page=1&add=drk0&p1=$p1&p2=&p3=$p3&p4=$p4&p6=$p6&sk=".urlencode($_REQUEST[sk])."&st=".urlencode($_REQUEST[st])."&ss=$_REQUEST[ss]'\" >Data rej. kroku</a>";
		echo "</font>";
		echo "</td>";
		echo "<td class=center>";
		echo "<a title=' Pokaż zgłoszenia z datą rozpoczęcia kroku=0000-00-00 ' href=# class='normalfont' style='margin-top:6px;' onClick=\"self.location.href='hd_p_zgloszenia.php?sa=0&sr=0&p5=X&page=1&add=drk0&p1=$p1&p2=&p3=$p3&p4=$p4&p6=$p6&sk=".urlencode($_REQUEST[sk])."&st=".urlencode($_REQUEST[st])."&ss=$_REQUEST[ss]'\" ><img class=imgoption src=img/00.gif border=0 width=12 height=12></a>";
		echo "</td>";
		echo "</tr>";
	
		echo "<tr>";
		echo "<td colspan=2>";
			echo "<hr />";
		echo "</td>";
		echo "</tr>";
		
		echo "<tr>";
		echo "<td colspan=2>";
		echo "<form name=szukaj_ss action=$PHP_SELF method=GET style='display:inline'>";
		
		echo "<input type=hidden name=sk_id id=sk_id>";	
		echo "<input type=hidden name=sa id=sa value='0'>";
		echo "<input type=hidden name=hd_rps id=hd_rps value='$_REQUEST[hd_rps]'>";
		echo "<input type=hidden name=page id=page value='1'>";
		echo "<input type=hidden name=p1 id=p1 value='$_REQUEST[p1]'>";
		echo "<input type=hidden name=p2 id=p2 value='$_REQUEST[p2]'>";
		echo "<input type=hidden name=p3 id=p3 value='$_REQUEST[p3]'>";
		echo "<input type=hidden name=p4 id=p4 value='$_REQUEST[p4]'>";
		echo "<input type=hidden name=p5 id=p5 value=''>";
		echo "<input type=hidden name=p6 id=p6 value='$_REQUEST[p6]'>";
		echo "<input type=hidden name=p7 id=p7 value='$_REQUEST[p7]'>";
		echo "<input type=hidden name=p8 id=p8 value='$_REQUEST[p8]'>";
		echo "<input type=hidden name=add id=add value='startstop'>";
		//echo "<input type=hidden name=sk id=sk value='$_REQUEST[sk]'>";
		
		echo "<font style='font-weight:normal'>";
		echo "<a title=' Pokaż zgłoszenia z błędnie ustawionym znacznikiem START-STOP ' href=# class='normalfont' style='margin-top:6px;' onClick=\"document.forms.szukaj_ss.submit();\">Źle ust. START-STOP</a>";
		echo "</font>";
		echo "</td>";
		//echo "<td class=center>";
		//echo "<a title=' Pokaż zgłoszenia z błędnie ustawionym czasem START-STOP ' href=# class='normalfont' style='margin-top:6px;' onClick=\"document.forms.szukaj_ss.submit();\"><img class=imgoption src=img/00.gif border=0 width=12 height=12></a>";
		
		//echo "</td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td colspan=2>";
		if ($_REQUEST[STARTSTOP_data]=='') { $ssd = Date('Y-m')."-01"; } else $ssd = $_REQUEST[STARTSTOP_data];
			echo "<input title='Data w formacie RRRR-MM-DD' style='font-size:11px;' type=text name=STARTSTOP_data id=STARTSTOP_data maxlength=10 size=10 value='".$ssd."'/>";	
			echo "&nbsp;<input type=button class=buttons style='padding:0px; margin:0px;' value='Pokaż' onClick=\"document.forms.szukaj_ss.submit();\" />";
		echo "</td>";
		echo "</tr>";

		echo "</form>";
		
		echo "</table>";
		echo "</td>";
		echo "</tr>";
		
		echo "<tr>";
		echo "<td colspan=2>";
			echo "<hr />";
		echo "</td>";
		echo "</tr>";
		
		// błedne zgłoszenia - koniec
		
	}
	
	echo "</table>";

	// pasek boczny - koniec
	
	echo "</th>";		
	
	echo "<tr class=hideme>";
	echo "<td colspan=9 style='padding-top:5px;vertical-align:top'>";
	
	echo "<span style='float:left; font-weight:normal;' class=hideme>";
	
	echo "<a id=zwin_stats href=# onclick=\"eraseCookie('hd_p_zgloszenia_pokaz_statystyki'); createCookie('hd_p_zgloszenia_pokaz_statystyki','NIE',365); $('#PodgladStatystyk').hide(); $('#pokaz_stats').show(); $('#zwin_stats').hide();\" title=' Ukryj statystyki i inne opcje '><img src=img/stats_collapse.gif width=16 height=16 border=0></a>";
	
	echo "<a id=pokaz_stats href=# onclick=\"eraseCookie('hd_p_zgloszenia_pokaz_statystyki'); createCookie('hd_p_zgloszenia_pokaz_statystyki','TAK',365); $('#PodgladStatystyk').show(); $('#pokaz_stats').hide(); $('#zwin_stats').show();  Refresh_Moje(); Refresh_Wszystkie(); \" title=' Pokaż statystyki i inne opcje '><img src=img/stats_expand.gif border=0 width=16 height=16></a>";
	echo "</span>";

	if ($count_rows>0) include_once('paging_begin_hd.php');
	echo "</td>";
	echo "</tr>";
	
	echo "<tr class=hideme>";
	echo "<td colspan=6 style='vertical-align:top; padding:0px;'>";
	echo "<h6 class='hd_h6 hideme' style='left:-5px; margin-bottom:1px;'>Lista zgłoszeń ($totalrows) | ";
	
	echo "<input type=button class=buttons style='padding:0px; margin:0px;' value=\"Nowe zgłoszenie\" onClick=\"newWindow_r(800,600,'hd_d_zgloszenie_simple.php?stage=1'); return false;\" />";
	
	if ($_REQUEST[sk]!='') {
		echo "&nbsp;<input type=button class=buttons style='padding:0px; margin:0px; color:blue;' value=\"Nowe zgłoszenie dla ".$_REQUEST[sk]."\" onClick=\"newWindow_r(800,600,'hd_d_zgloszenie_simple.php?stage=1&dlakomorki=".urlencode($_REQUEST[sk])."'); return false;\" />";
	}

	echo "&nbsp;<input type=button class=buttons style='padding:0px; margin:0px;' value='Otwarte zadania'  onClick=\"newWindow_r($_COOKIE[max_x],$_COOKIE[max_y],'z_zadania.php?s=otwarte&noback=1'); return false;\" />";
	
	echo "</h6>";
	
	echo "</td>";
	echo "</tr>";
	
	if ($count_rows>0) {	
		
		echo "<th class=center style='height:15px'>Nr zgłoszenia<br />";

		$sort_active = $_REQUEST[s];
		
		if ($_REQUEST[p0]=='') {
			
			echo "<a href=# onClick='self.location.href=\"$phpfile?sa=$sa&page=1&sel=$sel&id=$id&p7=$p7&p8=$p8&sr=$sr&hd_rps=$hd_rps&s=AD&p0=$p0&sd=".urlencode($_REQUEST[sd])."&sk=".urlencode($_REQUEST[sk])."&st=".urlencode($_REQUEST[st])."&ss=$_REQUEST[ss]&p1=".$_REQUEST[p1]."&p2=".$_REQUEST[p2]."&p3=".$_REQUEST[p3]."&p4=".$_REQUEST[p4]."&p5=".$_REQUEST[p5]."&p6=".$_REQUEST[p6]."&add=".$_REQUEST[add]."&sr=".$_REQUEST[sr]."\"'>";
			echo "<img class=imgoption style='border:0px' type=image src=img/sortuj_malejaco"; if ($sort_active!='AD') echo "_inactive"; echo ".gif title=' Sortuj malejąco po numerze zgłoszenia ' width=16 height=16></a>";


			echo "<a href=# onClick='self.location.href=\"$phpfile?sa=$sa&page=1&sel=$sel&id=$id&p7=$p7&p8=$p8&sr=$sr&hd_rps=$hd_rps&s=AA&p0=$p0&sd=".urlencode($_REQUEST[sd])."&sk=".urlencode($_REQUEST[sk])."&st=".urlencode($_REQUEST[st])."&ss=$_REQUEST[ss]&p1=".$_REQUEST[p1]."&p2=".$_REQUEST[p2]."&p3=".$_REQUEST[p3]."&p4=".$_REQUEST[p4]."&p5=".$_REQUEST[p5]."&p6=".$_REQUEST[p6]."&add=".$_REQUEST[add]."&sr=".$_REQUEST[sr]."\"'>";
			echo "<img class=imgoption style='border:0px' type=image src=img/sortuj_rosnaco"; if ($sort_active!='AA') echo "_inactive"; echo ".gif title=' Sortuj rosnąco po numerze zgłoszenia ' width=16 height=16>";
			
			echo "</a>";
		}
		echo "</th>";
		
		echo "<th width=36 class=center>Status<br />";

		if ($_REQUEST[p0]=='') {	
			echo "<a href=# onClick='self.location.href=\"$phpfile?sa=$sa&page=1&sel=$sel&id=$id&p7=$p7&p8=$p8&sr=$sr&hd_rps=$hd_rps&s=ED&p0=$p0&sd=".urlencode($_REQUEST[sd])."&sk=".urlencode($_REQUEST[sk])."&st=".urlencode($_REQUEST[st])."&ss=$_REQUEST[ss]&p1=".$_REQUEST[p1]."&p2=".$_REQUEST[p2]."&p3=".$_REQUEST[p3]."&p4=".$_REQUEST[p4]."&p5=".$_REQUEST[p5]."&p6=".$_REQUEST[p6]."&add=".$_REQUEST[add]."&sr=".$_REQUEST[sr]."\"'>";
			echo "<img class=imgoption style='border:0px' type=image src=img/sortuj_malejaco"; if ($sort_active!='ED') echo "_inactive"; echo ".gif title=' Sortuj malejąco po statusie zgłoszenia ' width=16 height=16></a>";
			
			echo "<a href=# onClick='self.location.href=\"$phpfile?sa=$sa&page=1&sel=$sel&id=$id&p7=$p7&p8=$p8&sr=$sr&hd_rps=$hd_rps&s=EA&p0=$p0&sd=".urlencode($_REQUEST[sd])."&sk=".urlencode($_REQUEST[sk])."&st=".urlencode($_REQUEST[st])."&ss=$_REQUEST[ss]&p1=".$_REQUEST[p1]."&p2=".$_REQUEST[p2]."&p3=".$_REQUEST[p3]."&p4=".$_REQUEST[p4]."&p5=".$_REQUEST[p5]."&p6=".$_REQUEST[p6]."&add=".$_REQUEST[add]."&sr=".$_REQUEST[sr]."\"'>";
			echo "<img class=imgoption style='border:0px' type=image src=img/sortuj_rosnaco"; if ($sort_active!='EA') echo "_inactive"; echo ".gif title=' Sortuj rosnąco po statusie zgłoszenia ' width=16 height=16>";
			echo "</a>";
		}
		
		echo "</th>";

//	if ($_REQUEST[p5]!='9') {
		echo "<th class=center>";
		
		//if (($_REQUEST[s]!='XD') && ($_REQUEST[s]!='XA')) {
		//	echo "Data zgłoszenia";
		//} else {
		if ($_REQUEST[p5]=='9') echo "<font color=grey>";
			echo "Data zgłoszenia";
		if ($_REQUEST[p5]=='9') echo "</font>";
		//	}
		
		echo "<br />";
		if ($_REQUEST[p0]=='') {
			echo "<a href=# onClick='self.location.href=\"$phpfile?sa=$sa&page=1&sel=$sel&id=$id&p7=$p7&p8=$p8&sr=$sr&hd_rps=$hd_rps&s=BD&p0=$p0&sd=".urlencode($_REQUEST[sd])."&sk=".urlencode($_REQUEST[sk])."&st=".urlencode($_REQUEST[st])."&ss=$_REQUEST[ss]&p1=".$_REQUEST[p1]."&p2=".$_REQUEST[p2]."&p3=".$_REQUEST[p3]."&p4=".$_REQUEST[p4]."&p5=".$_REQUEST[p5]."&p6=".$_REQUEST[p6]."&add=".$_REQUEST[add]."&sr=".$_REQUEST[sr]."\"'>";
			echo "<img class=imgoption style='border:0px' type=image src=img/sortuj_malejaco"; if ($sort_active!='BD') echo "_inactive"; echo ".gif title=' Sortuj malejąco po dacie zgłoszenia ' width=16 height=16></a>";
			
			echo "<a href=# onClick='self.location.href=\"$phpfile?sa=$sa&page=1&sel=$sel&id=$id&p7=$p7&p8=$p8&sr=$sr&hd_rps=$hd_rps&s=BA&p0=$p0&sd=".urlencode($_REQUEST[sd])."&sk=".urlencode($_REQUEST[sk])."&st=".urlencode($_REQUEST[st])."&ss=$_REQUEST[ss]&p1=".$_REQUEST[p1]."&p2=".$_REQUEST[p2]."&p3=".$_REQUEST[p3]."&p4=".$_REQUEST[p4]."&p5=".$_REQUEST[p5]."&p6=".$_REQUEST[p6]."&add=".$_REQUEST[add]."&sr=".$_REQUEST[sr]."\"'>";
			echo "<img class=imgoption style='border:0px' type=image src=img/sortuj_rosnaco"; if ($sort_active!='BA') echo "_inactive"; echo ".gif title=' Sortuj rosnąco po dacie zgłoszenia ' width=16 height=16></a>";
		}
	if ($_REQUEST[p5]=='9') {
		//if (($_REQUEST[s]=='XD') || ($_REQUEST[s]=='XA')) {
			echo "<hr />";
			echo "Data ostatniej<br />zmiany statusu<br />";
			echo "<a href=# onClick='self.location.href=\"$phpfile?sa=$sa&page=1&sel=$sel&id=$id&p7=$p7&p8=$p8&sr=$sr&hd_rps=$hd_rps&s=XD&p0=$p0&sd=".urlencode($_REQUEST[sd])."&sk=".urlencode($_REQUEST[sk])."&st=".urlencode($_REQUEST[st])."&ss=$_REQUEST[ss]&p1=".$_REQUEST[p1]."&p2=".$_REQUEST[p2]."&p3=".$_REQUEST[p3]."&p4=".$_REQUEST[p4]."&p5=".$_REQUEST[p5]."&p6=".$_REQUEST[p6]."&add=".$_REQUEST[add]."&sr=".$_REQUEST[sr]."\"'>";
			echo "<img class=imgoption style='border:0px' type=image src=img/sortuj_malejaco"; if ($_REQUEST[s]!='XD') echo "_inactive"; echo ".gif title=' Sortuj malejąco po dacie ostatniej zmiany statusu zgłoszenia ' width=16 height=16></a>";
			
			echo "<a href=# onClick='self.location.href=\"$phpfile?sa=$sa&page=1&sel=$sel&id=$id&p7=$p7&p8=$p8&sr=$sr&hd_rps=$hd_rps&s=XA&p0=$p0&sd=".urlencode($_REQUEST[sd])."&sk=".urlencode($_REQUEST[sk])."&st=".urlencode($_REQUEST[st])."&ss=$_REQUEST[ss]&p1=".$_REQUEST[p1]."&p2=".$_REQUEST[p2]."&p3=".$_REQUEST[p3]."&p4=".$_REQUEST[p4]."&p5=".$_REQUEST[p5]."&p6=".$_REQUEST[p6]."&add=".$_REQUEST[add]."&sr=".$_REQUEST[sr]."\"'>";
			echo "<img class=imgoption style='border:0px' type=image src=img/sortuj_rosnaco"; if ($_REQUEST[s]!='XA') echo "_inactive"; echo ".gif title=' Sortuj rosnąco po dacie ostatniej zmiany statusu zgłoszenia ' width=16 height=16></a>";
		//}
	}
		echo "</th>";
//	} else {
//	}
		echo "<th class=left>";
		echo "Placówka zgłaszająca&nbsp;";
		
		echo "<form name=szukaj_po_komorce action=$PHP_SELF method=GET style='display:inline'>";
		echo "&nbsp;";
		echo "<input title='Wpisz nazwę komórki do wyfiltrowania' style='font-size:11px;' type=text name=sk id=sk maxlength=60 size=50 onFocus=\"document.getElementById('search_sel5').checked=true;\" />";	
		//echo "<img class=imgoption style='border:0px' type=image src=img/filter.gif title=' Wpisz nazwę komórki do wyfiltrowania ' onClick=\"$('#sk').focus();\"></a>";
		echo "&nbsp;<input type=button class=buttons style='padding:0px; margin:0px;' value='Pokaż' onClick=\"document.forms.szukaj_po_komorce.submit();\" />";
		
		echo "<input type=hidden name=sk_id id=sk_id>";	
		echo "<input type=hidden name=sa id=sa value='0'>";
		echo "<input type=hidden name=sr id=sr value='search-wyniki'>";
		echo "<input type=hidden name=ss id=ss value='$_REQUEST[ss]'>";
		echo "<input type=hidden name=hd_rps id=hd_rps value='$_REQUEST[hd_rps]'>";
		echo "<input type=hidden name=sa id=sa value='$_REQUEST[sa]'>";
		echo "<input type=hidden name=page id=page value='1'>";
		echo "<input type=hidden name=p1 id=p1 value='$_REQUEST[p1]'>";
		echo "<input type=hidden name=p2 id=p2 value='$_REQUEST[p2]'>";
		echo "<input type=hidden name=p3 id=p3 value='$_REQUEST[p3]'>";
		echo "<input type=hidden name=p4 id=p4 value='$_REQUEST[p4]'>";
		echo "<input type=hidden name=p5 id=p5 value='$_REQUEST[p5]'>";
		echo "<input type=hidden name=p6 id=p6 value='$_REQUEST[p6]'>";
		echo "<input type=hidden name=p7 id=p7 value='$_REQUEST[p7]'>";
		echo "<input type=hidden name=p8 id=p8 value='$_REQUEST[p8]'>";
		echo "<input type=hidden name=add id=add value='$_REQUEST[add]'>";
		echo "<input type=hidden name=STARTSTOP_data id=STARTSTOP_data value='$_REQUEST[STARTSTOP_data]'>";
		
		echo "</form>";
		
		echo "<br />";
		if ($_REQUEST[p0]=='') {
			echo "<a href=# onClick='self.location.href=\"$phpfile?sa=$sa&page=1&sel=$sel&id=$id&p7=$p7&p8=$p8&sr=$sr&hd_rps=$hd_rps&s=CD&p0=$p0&sd=".urlencode($_REQUEST[sd])."&sk=".urlencode($_REQUEST[sk])."&st=".urlencode($_REQUEST[st])."&ss=$_REQUEST[ss]&p1=".$_REQUEST[p1]."&p2=".$_REQUEST[p2]."&p3=".$_REQUEST[p3]."&p4=".$_REQUEST[p4]."&p5=".$_REQUEST[p5]."&p6=".$_REQUEST[p6]."&add=".$_REQUEST[add]."&sr=".$_REQUEST[sr]."\"'>";
			echo "<img class=imgoption style='border:0px' type=image src=img/sortuj_malejaco"; if ($sort_active!='CD') echo "_inactive"; echo ".gif title=' Sortuj malejąco po placówce zgłaszającej ' width=16 height=16></a>";
			
			echo "<a href=# onClick='self.location.href=\"$phpfile?sa=$sa&page=1&sel=$sel&id=$id&p7=$p7&p8=$p8&sr=$sr&hd_rps=$hd_rps&s=CA&p0=$p0&sd=".urlencode($_REQUEST[sd])."&sk=".urlencode($_REQUEST[sk])."&st=".urlencode($_REQUEST[st])."&ss=$_REQUEST[ss]&p1=".$_REQUEST[p1]."&p2=".$_REQUEST[p2]."&p3=".$_REQUEST[p3]."&p4=".$_REQUEST[p4]."&p5=".$_REQUEST[p5]."&p6=".$_REQUEST[p6]."&add=".$_REQUEST[add]."&sr=".$_REQUEST[sr]."\"'>";
			echo "<img class=imgoption style='border:0px' type=image src=img/sortuj_rosnaco"; if ($sort_active!='CA') echo "_inactive"; echo ".gif title=' Sortuj rosnąco po placówce zgłaszającej ' width=16 height=16>";
			echo "</a>";
		}			
		//			echo "Dane osoby zgłaszającej";
		echo "</th>";	
		
		echo "<th>Temat zgłoszenia&nbsp;<br />";
		if ($_REQUEST[p0]=='') {
			echo "<a href=# onClick='self.location.href=\"$phpfile?sa=$sa&page=1&sel=$sel&id=$id&p7=$p7&p8=$p8&sr=$sr&hd_rps=$hd_rps&s=FD&p0=$p0&sd=".urlencode($_REQUEST[sd])."&sk=".urlencode($_REQUEST[sk])."&st=".urlencode($_REQUEST[st])."&p1=".$_REQUEST[p1]."&p2=".$_REQUEST[p2]."&p3=".$_REQUEST[p3]."&p4=".$_REQUEST[p4]."&p5=".$_REQUEST[p5]."&p6=".$_REQUEST[p6]."&add=".$_REQUEST[add]."&sr=".$_REQUEST[sr]."\"'>";
			echo "<img class=imgoption style='border:0px' type=image src=img/sortuj_malejaco"; if ($sort_active!='FD') echo "_inactive"; echo ".gif title=' Sortuj malejąco po temacie zgłoszenia ' width=16 height=16></a>";
			
			echo "<a href=# onClick='self.location.href=\"$phpfile?sa=$sa&page=1&sel=$sel&id=$id&p7=$p7&p8=$p8&sr=$sr&hd_rps=$hd_rps&s=FA&p0=$p0&sd=".urlencode($_REQUEST[sd])."&sk=".urlencode($_REQUEST[sk])."&st=".urlencode($_REQUEST[st])."&p1=".$_REQUEST[p1]."&p2=".$_REQUEST[p2]."&p3=".$_REQUEST[p3]."&p4=".$_REQUEST[p4]."&p5=".$_REQUEST[p5]."&p6=".$_REQUEST[p6]."&add=".$_REQUEST[add]."&sr=".$_REQUEST[sr]."\"'>";
			echo "<img class=imgoption style='border:0px' type=image src=img/sortuj_rosnaco"; if ($sort_active!='FA') echo "_inactive"; echo ".gif title=' Sortuj rosnąco po temacie zgłoszenia ' width=16 height=16></a>";
		}
		//			echo "<br />Kategoria -> Podkategoria<br />";
		echo "</th>";

		$KierownikId = $kierownik_nr;
		echo "<form name=obsluga onSubmit=\"return false;\">";
		$i = 0;
		$j = $page*$rowpersite-$rowpersite+1;

		while ($newArray = mysql_fetch_array($result)) {
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
			//		$temp_czas			= $newArray['zgl_razem_czas'];
			//		$temp_km			= $newArray['zgl_razem_km'];
			//		$temp_or			= $newArray['zgl_osoba_rejestrujaca'];
			$temp_op			= $newArray['zgl_osoba_przypisana'];
			//		$temp_zz			= $newArray['zgl_zasadne'];
			$temp_nrawarii		= $newArray['zgl_poledodatkowe1'];
			$temp_zgl_seryjne	= $newArray['zgl_poledodatkowe2'];
			$temp_parent_zgl	= $newArray['zgl_kontynuacja_zgloszenia_numer'];
			$temp_dzs			= $newArray['zgl_data_zmiany_statusu'];
			$temp_rekl_czy_jest = $newArray['zgl_czy_to_jest_reklamacyjne'];
			$temp_rekl_nr	 	= $newArray['zgl_nr_zgloszenia_reklamowanego'];
			$temp_rekl_czy_ma	= $newArray['zgl_czy_ma_zgl_reklamacyjne'];		
			$temp_naprawa_id	= $newArray['zgl_naprawa_id'];
			$temp_czy_pow_z_wp	= $newArray['zgl_czy_powiazane_z_wymiana_podzespolow'];
			$temp_zgl_komorka_working_hours = $newArray['zgl_komorka_working_hours'];
			$temp_minut_do_rozpoczecia 		= $newArray['zgl_rozpoczecie_min'];
			$temp_minut_do_zakonczenia		= $newArray['zgl_zakonczenie_min'];
			
			$temp_czy_rozwiazany	= $newArray['zgl_czy_rozwiazany_problem'];
			$temp_zgl_data_rozpoczecia		= $newArray['zgl_data_rozpoczecia'];
			$temp_zgl_data_zakonczenia		= $newArray['zgl_data_zakonczenia'];
			
			$temp_nasz_czas_do_rozpoczecia = $temp_minut_do_rozpoczecia - $newArray['zgl_E1C'];
			$temp_nasz_czas_do_zakonczenia = $temp_minut_do_zakonczenia - ($newArray['zgl_E1C']+$newArray['zgl_E2C']);
			
			$temp_czy_ww	= 	$newArray['zgl_wymagany_wyjazd'];
			$temp_czy_ww_data	= $newArray['zgl_wymagany_wyjazd_data_ustawienia'];		$temp_czy_ww_data = substr($temp_czy_ww_data,0,16);
			$temp_czy_ww_osoba	= $newArray['zgl_wymagany_wyjazd_osoba_wlaczajaca'];
			
			$temp_zgl_E1P		= $newArray['zgl_E1P'];
			$temp_zgl_E2P		= $newArray['zgl_E2P'];
			
			$temp_ss_id			= $newArray['zgl_sprzet_serwisowy_id'];
		
			$temp_zgl_spr_data		= $newArray['zgl_sprawdzone_data'];
			$temp_zgl_spr_osoba		= $newArray['zgl_sprawdzone_osoba'];
			$temp_zgl_przekazane_do	= $newArray['zgl_przekazane_do'];
			$temp_bt				= $newArray['belongs_to'];
			
			if ($temp_zgl_komorka_working_hours=='') $temp_zgl_komorka_working_hours = $default_working_time;
			
			$change_color_start = "";
			$change_color_stop = "";
					
			if ($temp_naprawa_id>0) {
				
				$sql888="SELECT belongs_to, naprawa_przekazanie_naprawy_do,naprawa_przekazanie_zakonczone,naprawa_przekazanie_naprawy_data,naprawa_przekazanie_naprawy_osoba FROM $dbname.serwis_naprawa WHERE (naprawa_id=$temp_naprawa_id) LIMIT 1";
				$result888 = mysql_query($sql888, $conn) or die($k_b);
				list($bt,$n_przekaz_do,$n_przekazanie_zakonczone,$n_przekaz_data,$n_przekaz_osoba)=mysql_fetch_array($result888);

				$r40 = mysql_query("SELECT filia_nazwa FROM $dbname.serwis_filie WHERE (filia_id='$n_przekaz_do') LIMIT 1", $conn) or die($k_b);
				list($NazwaFilii)=mysql_fetch_array($r40);
				
				$r41 = mysql_query("SELECT filia_nazwa FROM $dbname.serwis_filie WHERE (filia_id='$bt') LIMIT 1", $conn) or die($k_b);
				list($NazwaFiliiZrodlowej)=mysql_fetch_array($r41);
				
				$naprawa_przekazana_do_innej_filii = 0;
				if (($n_przekaz_do!=$bt) && ($n_przekaz_do!=0)) {
					$change_color_start = "style='color:blue;'";
					$change_color_stop = ""; 
					$naprawa_przekazana_do_innej_filii = 1;
		//			if ($n_przekaz_do==$es_filia) { $naprawa_przekazana_do_innej_filii = 1; } else { $naprawa_przekazana_do_innej_filii = 0; }
				} else {
					$change_color_start = "";
					$change_color_stop = "";
					$naprawa_przekazana_do_innej_filii = 0;
		//			if ($n_przekaz_do==$es_filia) { $naprawa_przekazana_do_innej_filii = 1; } else { $naprawa_przekazana_do_innej_filii = 0; }
				}
				
				if ($n_przekazanie_zakonczone==1) {
					$change_color_start = "";
					$change_color_stop = "";
					$naprawa_przekazana_do_innej_filii = 0;
				}
			}
			
			if ($KolorujWgStatusow==1) {
				
				switch ($temp_kategoria) {
					case 6: $kolorgrupy='#F73B3B'; tbl_tr_color_dblClick($i, $kolorgrupy); break;
					case 2:	$kolorgrupy='#FF7F2A'; tbl_tr_color_dblClick($i, $kolorgrupy); break; 
					default: if ($temp_status==9) { 
								$kolorgrupy='#FFFFFF'; 
								tbl_tr_highlight_dblClick($i);	
								//tbl_tr_color_dblClick($i, $kolorgrupy); 
								break; 
							} else {
								tbl_tr_highlight_dblClick($i);	
								$kolorgrupy='';
							}
				}
				
			} else {
				tbl_tr_highlight_dblClick($i);	
				$kolorgrupy='';
			}

			$j++;
			echo "<td class=righttop>";
			
			$przypisanedo = $temp_op;
			$krok_status = $temp_status;

			//if (($temp_status!=9)) { // && ($temp_kategoria!='6') && ($temp_kategoria!='2')) {
				echo "<span style='float:left; margin-top:2px;'>";
				echo "<input class='border0 hideme' type=checkbox name=markzgl$i id=markzgl$i value=$temp_id onClick=\"SelectTRById($i);\" />";
				echo "<input type=hidden name=K$temp_id id=K$temp_id value='$temp_kategoria' />";
				echo "</span>";
			//}
			
			echo "<span style='float:left; margin-top:2px;'>";

			echo "<a class=normalfont ";
			if ($temp_zgl_przekazane_do>0) {
				if (($temp_zgl_przekazane_do!=$es_filia)) {
					echo " title='Zgłoszenie przekazane do serwisu filii $NazwaFilii w dniu $n_przekaz_data przez $n_przekaz_osoba'";
				} else {
					echo " title='Zgłoszenie przekazane z filii $NazwaFiliiZrodlowej w dniu $n_przekaz_data przez $n_przekaz_osoba'";	
				}
			}
			echo $change_color_start;
			echo " href=# ";
			echo " onclick=\"newWindow_r($_COOKIE[max_x],$_COOKIE[max_y],'hd_p_zgloszenie_kroki.php?newwindow=1&nr=$temp_nr&id=$temp_id&searchtext=".urlencode($_REQUEST[st])."&kg=".substr($kolorgrupy,1,strlen($kolorgrupy))."&ss=".urlencode($_REQUEST[ss])."&st_wc=".urlencode($_REQUEST[st_wc])."&searcheserwisnr=".urlencode($_REQUEST[search_eserwis_nr])."&kz=$temp_kategoria'); return false; \">";
			if (($czy_wyroznic_zgloszenia_seryjne==1) && ($temp_zgl_seryjne!='')) echo "";
			echo "&nbsp;$temp_nr&nbsp;";
			if (($czy_wyroznic_zgloszenia_seryjne==1) && ($temp_zgl_seryjne!='')) echo "[s]";
			echo "</a>";
			
			if ($temp_poczta_nr!='') {
				echo "&nbsp;|&nbsp;";
			
				if ($_REQUEST[search_hadim_nr]!='') {
					echo highlight($temp_poczta_nr,$_REQUEST[search_hadim_nr]);
				} else {					
					if ($_REQUEST[search_zgl_nr]==$temp_poczta_nr) {			
						echo highlight($temp_poczta_nr,$temp_poczta_nr);
					} else 
						echo $temp_poczta_nr;
				}
				//echo "</a>";
			} else {
				echo "&nbsp;|&nbsp;<a title='Edytuj nr HADIM' href=# onclick=\"newWindow_r(600,200,'hd_e_zgloszenie_hadim.php?id=$temp_id&nr=$temp_nr&tk=$temp_kategoria&tpk=$temp_podkategoria&tp=$temp_priorytet&unr=$temp_unique');return false;\"><img class=imgoption src=img/addhadim.gif border=0 width=6 height=6></a>";
			}
						
			echo "</span>";
			
			// jeżeli zgłoszenie utworzono na bazie innego zgłoszenia pokaż ! obok numeru zgłoszenia	
			if ($temp_parent_zgl!=0) {
				echo "<input title=' Zgłoszenie utworzono na bazie zgłoszenia numer $temp_parent_zgl ' class=imgoption type=image src=img/have_parent.gif onclick=\"newWindow_r(800,600,'hd_o_zgloszenia.php?action=obsluga&id=$temp_parent_zgl&nr=$temp_parent_zgl&zgl_s='); return false;\">";
				
				//echo "<a class=normalfont title=' Zgłoszenie utworzono na bazie innego zgłoszenia ' href=# onClick=\"return false;\">!</a>";
			}

			/*	
	list($child_zgl)=mysql_fetch_array(mysql_query("SELECT zgl_nr FROM hd_zgloszenie WHERE (zgl_kontynuacja_zgloszenia_numer=$temp_nr) LIMIT 1"));
	if ($child_zgl!=0) {
		echo "<input title=' Na bazie zgłoszenia nr $temp_nr utworzono zgłoszenie numer $child_zgl ' class=imgoption type=image src=img/have_child.gif>";
	}
*/
			
			if ($temp_naprawa_id>0) {
				echo "<a title='Przejdź do modułu NAPRAWY. ";
				
				/*if ($n_przekaz_do==$es_filia) {
					echo "Naprawa przekazana z serwisu filii $NazwaFiliiZrodlowej w dniu $n_przekaz_data przez $n_przekaz_osoba";
				} else {
					echo "Naprawa przekazana do serwisu filii $NazwaFilii w dniu $n_przekaz_data przez $n_przekaz_osoba";
				}
				*/
				
				echo "' href=# onClick=\"self.location.href='przekieruj_do_napraw.php?hd_zgl_nr=$temp_nr'\"><img class=imgoption src=img/";
				
				if ($naprawa_przekazana_do_innej_filii==1) {
					echo "naprawa_unknown_przekazana.gif ";
				} else { 
					echo "naprawa_unknown.gif ";
				}
				echo "border=0 width=16 height=16></a>";
				
				if ($temp_ss_id<=0) {
					$sql88="SELECT naprawa_sprzet_zastepczy_id  FROM $dbname.serwis_naprawa WHERE (naprawa_id=$temp_naprawa_id) LIMIT 1";
					$result88 = mysql_query($sql88, $conn) or die($k_b);
					list($temp_ss_id)=mysql_fetch_array($result88);
				}
			}
			
			if ($temp_ss_id>0) {
				if ($naprawa_przekazana_do_innej_filii==1) {
					$sql88="SELECT magazyn_nazwa, magazyn_model, magazyn_sn, magazyn_ni FROM $dbname.serwis_magazyn WHERE (magazyn_id='$temp_ss_id') LIMIT 1";
					$result88 = mysql_query($sql88, $conn) or die($k_b);
					list($tmp_m_nazwa, $tmp_m_model, $tmp_m_sn, $tmp_m_ni)=mysql_fetch_array($result88);
					
					echo "<a title='Naprawa powiązana z przekazaniem sprzętu serwisowego: $tmp_m_nazwa $tmp_m_model (SN: $tmp_m_sn, NI: $tmp_m_ni)' href=# onClick=\"alert('Informacje o sprzęcie serwisowym: $tmp_m_nazwa $tmp_m_model (SN: $tmp_m_sn, NI: $tmp_m_ni)'); return false;\"><img class=imgoption src=img/service.gif border=0 width=16 height=16></a>";
				} else {
					echo "<a title=' Przejdź do modułu SPRZĘT SERWISOWY ' href=# onClick=\"self.location.href='przekieruj_do_sprzetu_serwisowego.php?id=$temp_ss_id&hd_zgl_nr=$temp_nr'\"><img class=imgoption src=img/service.gif border=0 width=16 height=16></a>";
				}
			}
			
			if (($temp_nrawarii!='') && ($temp_status!=9)) 
			echo "<a title='Przejdź do listy otwartych awarii WAN. Nr zgłoszenia w Orange: ".$temp_nrawarii."' href=# onClick=self.location.href='przekieruj_do_awarii_o.php?nr=$temp_nrawarii&up=".urlencode($temp_komorka)."'><img class=imgoption src=img/wan_disconnect.gif border=0></a>";

			if (($temp_nrawarii!='') && ($temp_status==9)) 
			echo "<a title='Przejdź do listy zamkniętych awarii WAN. Nr zgłoszenia w Orange: ".$temp_nrawarii."' href=# onClick=self.location.href='przekieruj_do_awarii_z.php?nr=$temp_nrawarii&up=".urlencode($temp_komorka)."'><img class=imgoption src=img/wan_connect.gif border=0></a>";	

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
						
						echo "<a href=# title='Zgłoszenie powiązane z wymianą podzespołów w sprzęcie: $wp_o (SN: $wp_sn, NI: $wp_ni) w zgłoszeniu $temp_parent_zgl' onclick=\"$('#ZawartoscDIV').load('hd_p_zgloszenie_kroki.php?nr=".$temp_nr."&id=".$temp_id."&searchtext=".urlencode($_REQUEST[st])."&kg=".substr($kolorgrupy,1,strlen($kolorgrupy))."&poz=under&randval=".$rand."&ss=".urlencode($_REQUEST[ss])."&st_wc=".urlencode($_REQUEST[st_wc])."&searcheserwisnr=".urlencode($_REQUEST[search_eserwis_nr])."&kz=$temp_kategoria'); createCookie('hd_p_zgloszenia_div_nr','$sciezka_do_cookie',365); $('#info').hide(); self.location.href='#_SZ'; return false;\" /><img class=imgoption style='border:0px' type=image src=img/wp.gif width=16 height=16></a>";
						
					} else {
						echo "<a title='Zgłoszenie powiązane z wymianą podzespołów w sprzęcie: $wp_o (SN: $wp_sn, NI: $wp_ni)' href=#><img class=imgoption src=img/wp.gif border=0 width=16 height=16></a>";
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
					
					echo "<a href=# title='Zgłoszenie powiązane z wymianą podzespołów w sprzęcie: $wp_o (SN: $wp_sn, NI: $wp_ni)'  onclick=\"$('#ZawartoscDIV').load('hd_p_zgloszenie_kroki.php?nr=".$temp_nr."&id=".$temp_id."&searchtext=".urlencode($_REQUEST[st])."&kg=".substr($kolorgrupy,1,strlen($kolorgrupy))."&poz=under&randval=".$rand."&ss=".urlencode($_REQUEST[ss])."&st_wc=".urlencode($_REQUEST[st_wc])."&searcheserwisnr=".urlencode($_REQUEST[search_eserwis_nr])."'); createCookie('hd_p_zgloszenia_div_nr','$sciezka_do_cookie',365); $('#info').hide(); self.location.href='#_SZ'; return false;\" /><img class=imgoption style='border:0px' type=image src=img/wp.gif  width=16 height=16></a>";
					
				} else {
					echo "<a title='Zgłoszenie powiązane z wymianą podzespołów w sprzęcie: $wp_o (SN: $wp_sn, NI: $wp_ni)' href=#><img class=imgoption src=img/wp.gif width=16 height=16 border=0></a>";
				}
			}
			
			
			if ($temp_rekl_czy_ma==1) {
				list($rekl_nr)=mysql_fetch_array(mysql_query("SELECT zgl_nr FROM hd_zgloszenie WHERE (zgl_nr_zgloszenia_reklamowanego=$temp_nr) LIMIT 1"));
				
				echo "<a href=# title='To zgłoszenie było reklamowane przez klienta. Utworzono z niego zgłoszenie reklamacyjne o numerze $rekl_nr' onclick=\"self.location.href='hd_p_zgloszenia.php?sr=search-wyniki&search_zgl_nr=$rekl_nr';\"><input class=imgoption type=image src=img/is_reklamacyjne.gif></a>";
			}
			
			if ($temp_rekl_czy_jest==1) {
				echo "<a href=# title='To jest zgłoszenie reklamacyjne do zgłoszenia nr $temp_rekl_nr' onclick=\"self.location.href='hd_p_zgloszenia.php?sr=search-wyniki&search_zgl_nr=$temp_rekl_nr';\"><input class=imgoption type=image src=img/have_reklamacyjne.gif></a>";
			}
			
			if ((($temp_kategoria=='2') || ($temp_kategoria=='6')) && (($temp_status=='2') || ($temp_status=='1'))) {
			
				// sprawdz czy było przesunięcie terminu rozpoczęcia realizacji zgłoszenia
				$r33 = mysql_query("SELECT zgl_szcz_przesuniety_termin_rozpoczecia,zgl_szcz_przesuniecie_data,zgl_szcz_przesuniecie_osoba FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$temp_nr') and (zgl_szcz_widoczne=1) and (belongs_to=$es_filia) and (zgl_szcz_status=1) ORDER BY zgl_szcz_nr_kroku DESC LIMIT 1", $conn_hd) or die($k_b);
				list($przesuniety,$przesuniety_data,$przesuniety_osoba)=mysql_fetch_array($r33);
			
				if (($przesuniety==1) && ($temp_kategoria=='2')) {
					echo "<a href=# onClick=\"alert('Dla zgłoszenie nr $temp_nr ustalono termin rozpoczęcia na ".substr($przesuniety_data,0,16).".  Osoba z którą ustalono przesunięcie: $przesuniety_osoba.');\"title='Zgłoszenie z przesuniętym terminem rozpoczęcia. Kliknij, aby zobaczyć szczegóły przesunięcia.'><input class=imgoption type=image src=img/przesuniety_termin.gif></a>";
				}
				if (($przesuniety==1) && ($temp_kategoria=='6')) {
						$r44 = mysql_query("SELECT up_working_time, up_working_time_alternative, up_working_time_alternative_start_date,up_working_time_alternative_stop_date FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((serwis_komorki.belongs_to=$es_filia) and (serwis_piony.pion_id=serwis_komorki.up_pion_id) and (CONCAT(serwis_piony.pion_nazwa,' ',serwis_komorki.up_nazwa)='$temp_komorka'))", $conn) or die($k_b);
						list($temp_k_wh,$temp_k_wha,$temp_k_wha_start,$temp_k_wha_stop)=mysql_fetch_array($r44);
						
						if (($temp_k_wha!='') && ($temp_k_wha_start>=date('Y-m-d')) && ($temp_k_wha_stop<=date('Y-m-d'))) {
							$daysUP = explode(";",$temp_k_wha);
						} else $daysUP = explode(";",$temp_k_wh);
						
						$oneday1b = explode("@",$daysUP[0]); 
						$oneday2b = explode("@",$daysUP[1]); 
						$oneday3b = explode("@",$daysUP[2]); 
						$oneday4b = explode("@",$daysUP[3]); 
						$oneday5b = explode("@",$daysUP[4]); 
						$oneday6b = explode("@",$daysUP[5]); 
						$oneday7b = explode("@",$daysUP[6]); 
						
						$days = explode(";",$temp_zgl_komorka_working_hours);
						
						$opis_stanow = '';
						
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
						
						// menu z godzinami pracy
						$opis_stanow = '\r\n\r\n';
						
						if ($oneday1[1]!=$oneday1b[1]) { $opis_stanow .= '->\t'; } else { $opis_stanow .= '\t'; }
						$opis_stanow .= 'Poniedziałek:\t'.$oneday1[1].'\r\n';
						if ($oneday2[1]!=$oneday2b[1]) { $opis_stanow .= '->\t'; } else { $opis_stanow .= '\t'; }
						$opis_stanow .= 'Wtorek:\t\t\t'.$oneday2[1].'\r\n';
						if ($oneday3[1]!=$oneday3b[1]) { $opis_stanow .= '->\t'; } else { $opis_stanow .= '\t'; }
						$opis_stanow .= 'Środek:\t\t\t'.$oneday3[1].'\r\n';
						if ($oneday4[1]!=$oneday4b[1]) { $opis_stanow .= '->\t'; } else { $opis_stanow .= '\t'; }
						$opis_stanow .= 'Czwartek:\t\t'.$oneday4[1].'\r\n';
						if ($oneday5[1]!=$oneday5b[1]) { $opis_stanow .= '->\t'; } else { $opis_stanow .= '\t'; }
						$opis_stanow .= 'Piątek:\t\t\t'.$oneday5[1].'\r\n';
						if ($oneday6[1]!=$oneday6b[1]) { $opis_stanow .= '->\t'; } else { $opis_stanow .= '\t'; }
						$opis_stanow .= 'Sobota:\t\t\t\t'.$oneday6[1].'\r\n';
						if ($oneday7[1]!=$oneday7b[1]) { $opis_stanow .= '->\t'; } else { $opis_stanow .= '\t'; }
						$opis_stanow .= 'Niedziela:\t\t\t'.$oneday7[1].'\r\n';
						
					echo "<a href=# onClick=\"alert('Dla zgłoszenie nr $temp_nr zmodyfikowano godziny pracy (ustalono z: $przesuniety_osoba): $opis_stanow');\"title='Zgłoszenie ze zmodyfikowanymi godzinami pracy. Kliknij, aby zobaczyć szczegóły.'><input class=imgoption type=image src=img/mod_wh.gif></a>";
				}
			}
			
			if ($temp_czy_ww==1) {
				//echo "<a href=# title='Wymaga wyjazdu. Ustawione przez $temp_czy_ww_osoba w dniu $temp_czy_ww_data'><input class=imgoption type=image src=img/car_ww.gif></a>";
				echo "<a href=#><input class=imgoption type=image src=img/car_ww.gif></a>";
			}
			
			if ($temp_status==9) {
				if (($temp_zgl_spr_data!='0000-00-00 00:00:00') && ($temp_zgl_spr_data!='')) {
					echo "<a href=# onClick=\"alert('Zgłoszenie nr $temp_nr zostało sprawdzone przez $temp_zgl_spr_osoba w dniu ".substr($temp_zgl_spr_data,0,16)."');\"title='Zgłoszenie nr $temp_nr zostało sprawdzone przez $temp_zgl_spr_osoba w dniu ".substr($temp_zgl_spr_data,0,16)."'><input class=imgoption type=image src=img/zgl_checked.gif></a>";
				}
			}
			
			echo "<span>";
			if ($PokazIkonyWHPrzegladanie==1) {
				echo "<a href=# class=anchorclass rel='submenu".$i."[click]'>";
				echo "<input class=imgoption type=image src=img/hd_zgl_opcje.gif>";
			} else {
				echo "<a href=# class='anchorclass normalfont' rel='submenu".$i."[click]'>";
				echo "Opcje";
			}
			echo "</a>";
			echo "</span>";
			
			echo "<div id=submenu".$i." class=anylinkcss>";
			echo "<p style='background-color:#2F3047; color:white; padding:3px'>&nbsp;Zgłoszenie nr <b>$temp_nr</b></p>";
			echo "<ul>";
			echo "<li>";
			if ($enableHDszczPreviewDIV==1) {
				$rand = rand_str(10);
				$sciezka_do_cookie = "hd_p_zgloszenie_kroki.php?nr=".$temp_nr."&id=".$temp_id."&randval=".$rand."";
				echo "<a href=# title='Kroki zgłoszenia $temp_nr' onclick=\"$('#ZawartoscDIV').load('hd_p_zgloszenie_kroki.php?nr=$temp_nr&id=$temp_id&randval=$rand&kg=".substr($kolorgrupy,1,strlen($kolorgrupy))."&kz=$temp_kategoria'); createCookie('hd_p_zgloszenia_div_nr','$sciezka_do_cookie',365); $('#info').hide(); self.location.href='#_SZ'; return false;\" /><img class=imgoption style='border:0px' type=image src=img/expand.gif width=11 height=11>&nbsp;Kroki realizacji</a>";
			}
			echo "</li>";
			echo "<li><hr /></hr></li>";
			
			if ($czy_wyroznic_zgloszenia_seryjne==1) {
				if ($temp_zgl_seryjne!='') { $zgl_seryjne_mark = '1'; } else { $zgl_seryjne_mark = ''; }
			} else { $zgl_seryjne_mark = ''; }
			
			if ($temp_status!=9) {
				
				if ($zastap_obsluge_zmiana_statusu==0) {
					echo "<li><a href=# onclick=\"newWindow_r(800,600,'hd_o_zgloszenia.php?action=obsluga&id=$temp_id&nr=$temp_nr&zgl_s=$zgl_seryjne_mark'); return false;\"><b>Obsługa zgłoszenia</b></a></li>";
				} else {
					//echo "<li><a href=# onclick=\"newWindow_r(800,600,'hd_o_zgloszenia.php?action=obsluga&id=$temp_id&nr=$temp_nr&zgl_s=$zgl_seryjne_mark'); return false;\">Obsługa zgłoszenia</a></li>";
				}
				
				if ($zastap_obsluge_zmiana_statusu==1) {
					echo "<li><a href=# onclick=\"newWindow_r(800,600,'hd_o_zgloszenia_zs.php?id=$temp_id&unr=$temp_unikalny_nr&nr=$temp_nr&ts=$temp_status&tk=$temp_kategoria&tpk=$temp_podkategoria&zgoda=9&komorka=".urlencode($temp_komorka)."&osoba=".urlencode($temp_osoba)."&zgl_s=$zgl_seryjne_mark&rozwiazany=$temp_czy_rozwiazany&ww=$temp_ww&clearcookies=1'); return false;\"><b>Zmień status zgłoszenia</b></a></li>";
				}
				
				echo "<li><hr /></li>";
				
				if ($przypisanedo!=$currentuser) {
				
				//echo "&nbsp;<input id=pds_button type=button class=buttons value='Przypisz do siebie' onClick=\"if (confirm('Czy na pewno chcesz przypisać to zgłoszenie do siebie ?')) { $('#OsobaPrzypisana').load('hd_o_zgloszenia_pds.php?id=".$temp_id."&nr=".$temp_nr."&osoba=".urlencode($currentuser)."&randval='+ Math.random()); } \" />";
				
					echo "<li><a href=# onClick=\"if (confirm('Czy napewno chcesz przypisać zgłoszenie nr $temp_nr z $temp_komorka do siebie ?')) { newWindow(500,250,'hd_o_zgloszenia_pds.php?id=".$temp_id."&nr=".$temp_nr."&osoba=".urlencode($currentuser)."&refresh=1');return false; }\">Przypisz do siebie</a></li>";
				}
				
				echo "<li><a href=# onClick=\"newWindow(700,400,'hd_o_zgloszenia_pdo.php?id=$temp_id&nr=$temp_nr&osoba=".urlencode($przypisanedo)."'); return false; \"><font color=blue>Przypisz do osoby</font></a></li>";
			}
			
			if ($temp_status==9) {
				
				// jeżeli zgłoszenie nie jest konsultacją ani pracą na potrzeby Postdata -> pozwól utworzyć zgłoszenie reklamacyjne
				if (($temp_kategoria!=1) && ($temp_kategoria!=5)) {
					
					// jeżeli nie utworzono jeszcze zgłoszenia reklamacyjnego do tego zgłoszenia
					if ($temp_rekl_czy_ma==0) {
						//echo "<li><a href=# onclick=\"newWindow_r(800,600,'hd_d_zgloszenie_simple.php?zgl_reklamacyjne=1&zgl_reklamacyjne_do_zgl_nr=$temp_nr&komorka=".urlencode($temp_komorka)."&osobazgl=".urlencode($temp_osoba)."&osobazgltel=".$temp_telefon."&nrzglpoczty=$temp_poczta_nr&zglkat=$temp_kategoria&zglpodkat=$temp_podkategoria&uniquenr_zgl_reklamowanego=$temp_unique&zgl_temat=".urlencode($temp_temat)."&zgl_tresc=".urlencode($temp_tresc)."'); return false; \"><font color=blue>Utwórz zgł. reklamacyjne</font></a></li>";
						//echo "<li><hr /></li>";
					}
				}
				
				echo "<li><a href=# onclick=\"newWindow_r(800,600,'hd_o_zgloszenia.php?action=podglad&id=$temp_id&nr=$temp_nr&zgl_s=$zgl_seryjne_mark'); return false; \">Podgląd zgłoszenia</a></li>";
			}

			if (($es_nr==$KierownikId) || ($is_dyrektor==1)) {
				echo "<li><a href=# onclick=\"newWindow_r(800,600,'hd_e_zgloszenie_new.php?id=$temp_id&nr=$temp_nr&tk=$temp_kategoria&tpk=$temp_podkategoria&tp=$temp_priorytet&unr=$temp_unique');return false;\"><font color=green>Edytuj zgłoszenie</font></a></li>";
				
				echo "<li><a href=# onclick=\"newWindow_r(800,600,'hd_e_zgloszenie_new.php?range=kat&id=$temp_id&nr=$temp_nr&tk=$temp_kategoria&tpk=$temp_podkategoria&tp=$temp_priorytet&unr=$temp_unique'); return false;\"><font color=green>Edytuj kategorie zgłoszenia</font></a></li>";
				echo "<li><a href=# onclick=\"newWindow_r(600,200,'hd_e_zgloszenie_hadim.php?id=$temp_id&nr=$temp_nr&tk=$temp_kategoria&tpk=$temp_podkategoria&tp=$temp_priorytet&unr=$temp_unique');return false;\"><font color=green>Edytuj nr zgł. HADIM</font></a></li>";
				
				if ((($temp_czy_pow_z_wp==0) && (($temp_naprawa_id<=0) || (($temp_naprawa_id>0) && ($temp_status==9)))) || ($allow_hide_for_all==true)) {	
					echo "<li><a href=# onclick=\"newWindow_r($dialog_window_x,$dialog_window_y,'hd_u_zgloszenie.php?id=$temp_id&nr=$temp_nr&data=$temp_data'); return false;\"><font color=red>Ukryj zgłoszenie</font></a></li>";
				}
				
			} else {
				if ($temp_status!=9) {
					echo "<li><a href=# onclick=\"newWindow_r(800,600,'hd_e_zgloszenie_new.php?id=$temp_id&nr=$temp_nr&tk=$temp_kategoria&tpk=$temp_podkategoria&tp=$temp_priorytet&unr=$temp_unique');return false;\"><font color=green>Edytuj zgłoszenie</font></a></li>";
				} else {
					echo "<li><a href=# onclick=\"newWindow_r(600,200,'hd_e_zgloszenie_hadim.php?id=$temp_id&nr=$temp_nr&tk=$temp_kategoria&tpk=$temp_podkategoria&tp=$temp_priorytet&unr=$temp_unique');return false;\"><font color=green>Edytuj nr zgł. HADIM</font></a></li>";
				}
			}
			
			if (($es_nr==$KierownikId) || ($is_dyrektor==1)) {				
				
			}	else {			
					if (substr($temp_data,0,7)==$edycja_dla_wszystkich_dla_okresu) {
						echo "<li><a href=# onclick=\"newWindow_r(800,600,'hd_e_zgloszenie_new.php?range=kat&id=$temp_id&nr=$temp_nr&tk=$temp_kategoria&tpk=$temp_podkategoria&tp=$temp_priorytet&unr=$temp_unique'); return false;\"><font color=green>Edytuj kategorie zgł.</font></a></li>";
				}
			}
			
			if (($temp_status==9) && ((($kierownik_nr==$es_nr) || ($is_dyrektor==1)) && ($funkcja_weryfikacji_zgloszen_przez_kierownikow==true)) && ($temp_zgl_spr_data=='0000-00-00 00:00:00')) {
				echo "<li><a href=# onclick=\"newWindow_r($dialog_window_x,$dialog_window_y,'hd_zgloszenie_potwierdz.php?frommenu=1&potwierdz=1&id=$temp_id&nr=$temp_nr&sprdata=".Date("Y-m-d H:i:s")."&spruser=".urlencode($currentuser)."');return false;\"><font color=green>*Potwierdź sprawdzenie*</font></a></li>";
			}
			if (($temp_status==9) && ((($kierownik_nr==$es_nr) || ($is_dyrektor==1)) && ($funkcja_weryfikacji_zgloszen_przez_kierownikow==true)) && ($temp_zgl_spr_data!='0000-00-00 00:00:00')) {
				echo "<li><a href=# onclick=\"newWindow_r($dialog_window_x,$dialog_window_y,'hd_zgloszenie_potwierdz.php?potwierdz=0&id=$temp_id&nr=$temp_nr&sprdata=".Date("Y-m-d H:i:s")."&spruser=".urlencode($currentuser)."');return false;\"><font color=red>*Anuluj sprawdzenie*</font></a></li>";
			}
			
			if (($temp_kategoria!=1) && ($temp_kategoria!=5) && ($temp_kategoria!=4)) {
			
				list($czy_sa_stany_posrednie)=mysql_fetch_array(mysql_query("SELECT COUNT(hdnp_id) FROM $dbname_hd.hd_naprawy_powiazane WHERE (hdnp_zgl_id='$temp_nr') and ((hdnp_zdiagnozowany<>9) or (hdnp_oferta_wyslana<>9) or (hdnp_akceptacja_kosztow<>9) or (hdnp_zamowienie_wyslane<>9) or (hdnp_zamowienie_zrealizowane<>9) or (hdnp_gotowe_do_oddania<>9))", $conn_hd));
			
				if ($czy_sa_stany_posrednie>0) {
					echo "<li><a href=# onclick=\"newWindow_r(800,600,'hd_e_zgloszenie_sp.php?id=$temp_id&nr=$temp_nr');return false;\"><font color=#336749>Edytuj stany pośrednie</font></a></li>";
				}
			
			}
			
			if (($temp_kategoria!=1) && ($temp_status!=9)) {

				if ($temp_czy_ww==1) {
					echo "<li><a href=# onClick=\"newWindow(500,50,'hd_o_zgloszenia_ww.php?zgl_nr=".$temp_nr."&set=0'); return false;\"><font color=blue>Anuluj wymagany wyjazd</font></a></li>";
				} else {
					echo "<li><a href=# onClick=\"newWindow(500,50,'hd_o_zgloszenia_ww.php?zgl_nr=".$temp_nr."&set=1&ww_data=".urlencode(Date('Y-m-d H:i:s'))."&ww_osoba=".urlencode($currentuser)."'); return false;\"><font color=blue>Ustaw wymagany wyjazd</font></a></li>";
					//echo "<span style=''><input type=button class=buttons value='Ustaw wymagany wyjazd'  onClick=\"newWindow(500,50,'hd_o_zgloszenia_ww.php?zgl_nr=".$_REQUEST[nr]."&set=1&ww_data=".urlencode(Date('Y-m-d H:i:s'))."&ww_osoba=".urlencode($currentuser)."'); \"></span>";		
				}
				
			}
			
			
			// jeżeli nie było wymian sprzętu w zgłoszeniu -> daj możliwość powiązania zgłoszenia z naprawą
			/*
			if ((($temp_kategoria=='2') || ($temp_kategoria=='6') || ($temp_kategoria=='3')) && 
				(($temp_podkategoria=='3') || ($temp_podkategoria=='4') || ($temp_podkategoria=='9') || ($temp_podkategoria=='0') || ($temp_podkategoria=='E'))) { 
				if (($temp_naprawa_id<=0) && ($temp_czy_pow_z_wp==0) && (($temp_status=='3A') || ($temp_status=='3B') || ($temp_status==7))) {
					//echo "<li><a href=# onclick=\"newWindow_r(800,$dialog_window_y,'hd_powiaz_zgloszenie_z_naprawa.php?id=".$temp_id."&nr=".$temp_nr."&data=".$temp_data."&tk=".$temp_kategoria."&tpk=".$temp_podkategoria."&ts=".$temp_status."&komorkanazwa=".urlencode($temp_komorka)."&komorka=".urlencode(substr($temp_komorka,strpos($temp_komorka,' ')+1,strlen($temp_komorka)))."'); return false;\">Powiąż z naprawą</a></li>";
				}
			}
*/

			echo "<li><hr /></li>";
			echo "<li><a href=# onclick=\"if (confirm('Czy napewno chcesz wydrukować potwierdzenie dla zgłoszenia nr $temp_nr ?')) { newWindow_r(800,600,'hd_potwierdzenie.php?id=$temp_id&nr=$temp_nr&zgl_s=$zgl_seryjne_mark'); return false; } else { return false; } \"><b>Drukuj potwierdzenie</b></a></li>";
			echo "<li><hr /></li>";
			
			echo "<li><a href=# onclick=\"self.location.href='z_protokol.php?okresm=".Date('m')."&okresr=".Date('Y')."&ss=$temp_nr';\">Protokoły powiązane</a></li>";
			echo "<li><hr /></li>";

			if ((($temp_kategoria=='2')) && ((($currentuser==$przypisanedo) && (($temp_status=='2'))) || ($temp_status=='1'))) {
				if ($temp_data_zak!='0000-00-00 00:00:00') {
					echo "<li><a href=# onclick=\"newWindow_r(700,450,'hd_e_zgloszenie.php?element=czas_zak&id=$temp_id&nr=$temp_nr');return false;\">Zmień czas rozpoczęcia</a></li>";
					echo "<li><hr /></li>";
				}
			}
			
			echo "<li><a href=# onclick=\"newWindow(400,200,'hd_p_pokaz_ip_tel.php?komorkanazwa=".urlencode($temp_komorka)."&f=$temp_bt');return false;\">Telefon i IP komórki</a></li>";
			//echo "<li><a href=# onclick=\"newWindow(400,150,'hd_p_pokaz_ip.php?komorkanazwa=".urlencode($temp_komorka)."');return false;\">Adres IP placówki</a></li>";
			//echo "<li><a href=# onclick=\"newWindow(400,150,'hd_p_pokaz_tel.php?komorkanazwa=".urlencode($temp_komorka)."');return false;\">Telefon do placówki</a></li>";
			echo "<li><a href=# onclick=\"newWindow(500,300,'hd_p_pokaz_godziny.php?nr=$temp_nr&komorkanazwa=".urlencode($temp_komorka)."&wa=".urlencode($temp_zgl_komorka_working_hours)."'); return false;\">Zapisane godziny pracy</a></li>";			
			echo "<li><hr /></hr></li>";
			echo "<li><a href=# onclick=\"newWindow_r(800,600,'hd_p_zgloszenie_historia_zmian.php?id=$temp_id&nr=$temp_nr');return false;\">Historia zmian</a></li>";
			echo "</ul>";
			echo "</div>";
			// koniec popup'a z opcjami 

			if ($temp_podkategoria=='') {
				//echo "<br />";
				echo "<p align=center style='border:1px solid red; background-color:red; padding:3px;'>Brak podkategorii dla tego zgłoszenia</p>";
			}

			echo "</td>";
			
			echo "<td style='text-align:center;vertical-align:top;padding-top:3px'>";
			list($status)=mysql_fetch_array(mysql_query("SELECT hd_status_opis FROM $dbname_hd.hd_status WHERE hd_status_nr='$temp_status' LIMIT 1", $conn_hd));

			if ($wiecej_informacji_w_Helpdesk) {	
				//list($czy_sa_stany_posrednie)=mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM $dbname_hd.hd_naprawy_powiazane WHERE (hdnp_zgl_id='$temp_nr') and ((hdnp_zdiagnozowany<>9) or (hdnp_oferta_wyslana<>9) or (hdnp_akceptacja_kosztow<>9) or (hdnp_zamowienie_wyslane<>9) or (hdnp_zamowienie_zrealizowane<>9) or (hdnp_gotowe_do_oddania<>9))", $conn_hd));
				
				$r55 = mysql_query("SELECT hdnp_zdiagnozowany, hdnp_oferta_wyslana, hdnp_akceptacja_kosztow, hdnp_zamowienie_wyslane, hdnp_zamowienie_zrealizowane, hdnp_gotowe_do_oddania, hdnp_naprawa_zakonczona FROM $dbname_hd.hd_naprawy_powiazane WHERE (hdnp_zgl_id='$temp_nr') ORDER BY hdnp_id DESC LIMIT 1", $conn_hd) or die($k_b);
		
				list($temp_zdiagnozowany, $temp_oferta_wyslana, $temp_akceptacja_kosztow, $temp_zamowienie_wyslane, $temp_zamowienie_zrealizowane, $temp_gotowe_do_oddania, $temp_naprawa_zakonczona)=mysql_fetch_array($r55);

				if ($temp_zdiagnozowany=='') $temp_zdiagnozowany='9';
				if ($temp_oferta_wyslana=='') $temp_oferta_wyslana='9';
				if ($temp_akceptacja_kosztow=='') $temp_akceptacja_kosztow='9';
				if ($temp_zamowienie_wyslane=='') $temp_zamowienie_wyslane='9';
				if ($temp_zamowienie_zrealizowane=='') $temp_zamowienie_zrealizowane='9';
				if ($temp_gotowe_do_oddania=='') $temp_gotowe_do_oddania='9';
				
				switch ($temp_zdiagnozowany) {
					case 0 : $zdiagnozowany = "<font color=red><b>NIE</b></font>"; break;
					case 1 : $zdiagnozowany = "<font color=green><b>TAK</b></font>"; break; 
					default : $zdiagnozowany = "<font color=grey><b>-</b></font>"; break; 
				}
				switch ($temp_oferta_wyslana) {
					case 0 : $oferta_wyslana = "<font color=red><b>NIE</b></font>"; break;
					case 1 : $oferta_wyslana = "<font color=green><b>TAK</b></font>"; break; 
					default : $oferta_wyslana = "<font color=grey><b>-</b></font>"; break; 
				}				
				switch ($temp_akceptacja_kosztow) {
					case 0 : $akceptacja_kosztow = "<font color=red><b>NIE</b></font>"; break;
					case 1 : $akceptacja_kosztow = "<font color=green><b>TAK</b></font>"; break; 
					default : $akceptacja_kosztow = "<font color=grey><b>-</b></font>"; break; 
				}
				switch ($temp_zamowienie_wyslane) {
					case 0 : $zamowienie_wyslane = "<font color=red><b>NIE</b></font>"; break;
					case 1 : $zamowienie_wyslane = "<font color=green><b>TAK</b></font>"; break; 
					default : $zamowienie_wyslane = "<font color=grey><b>-</b></font>"; break; 
				}
				switch ($temp_zamowienie_zrealizowane) {
					case 0 : $zamowienie_zrealizowane = "<font color=red><b>NIE</b></font>"; break;
					case 1 : $zamowienie_zrealizowane = "<font color=green><b>TAK</b></font>"; break; 
					default : $zamowienie_zrealizowane = "<font color=grey><b>-</b></font>"; break; 
				}
				switch ($temp_gotowe_do_oddania) {
					case 0 : $gotowe_do_oddania = "<font color=red><b>NIE</b></font>"; break;
					case 1 : $gotowe_do_oddania = "<font color=green><b>TAK</b></font>"; break; 
					default : $gotowe_do_oddania = "<font color=grey><b>-</b></font>"; break; 
				}			
				//$opis_stanow = '<br /><br /><u>Stany pośrednie:</u><br />Zdiagnozowany: '.$zdiagnozowany.'<br />Oferta wysłana: <br />Akceptacja kosztów: <br />Zamówienie wysłane: <br />Zamówienie zrealizowane: <br />Gotowe do oddania: <br />';

				$opis_stanow = '<table class=stany>';
				$opis_stanow.= '<tr height=24><td colspan=2 class=center style=background-color:#2F3047><font color=white>Zgłoszenie nr <b>'.$temp_nr.'</b></font></td></tr>';
				
				$r1 = mysql_query("SELECT hd_kategoria_opis FROM $dbname_hd.hd_kategoria WHERE (hd_kategoria_nr='$temp_kategoria') LIMIT 1", $conn_hd) or die($k_b);
				list($kat_opis)=mysql_fetch_array($r1);

				$r2 = mysql_query("SELECT hd_podkategoria_opis FROM $dbname_hd.hd_podkategoria WHERE (hd_podkategoria_nr='$temp_podkategoria') LIMIT 1", $conn_hd) or die($k_b);
				list($podkat_opis)=mysql_fetch_array($r2);

				$temp_podkategoria2_o = $temp_podkategoria2;
				if ($temp_podkategoria2_o=='') $temp_podkategoria2_o='Brak';
				
				$opis_stanow.= '<tr><td class=right>Kategoria</td><td><b>'.$kat_opis.'</b></td></tr>';
				$opis_stanow.= '<tr><td class=right>Podkategoria</td><td><b>'.$podkat_opis.'</b></td></tr>';
				$opis_stanow.= '<tr><td class=right>Podkategoria (poziom 2)</td><td><b>'.$temp_podkategoria2_o.'</b></td></tr>';
				$opis_stanow.= '<tr><td colspan=2><hr /></td></tr>';
				if ($przypisanedo!='') {
					$opis_stanow .= '<tr><td class=right>Status</td><td><b>'.$status.'</b></td></tr>';
					
					if ($temp_nrawarii!='') {
						$opis_stanow .= '<tr><td class=right><font color=blue>Nr zgłoszenia w Orange</font></td><td><font color=blue><b>'.$temp_nrawarii.'</b></font></td></tr>';
					}
					$opis_stanow.= '<tr><td colspan=2><hr /></td></tr>';
					
					list($temp_dzs1,$temp_cwk)=mysql_fetch_array(mysql_query("SELECT zgl_szcz_czas_rozpoczecia_kroku,zgl_szcz_czas_wykonywania FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_widoczne=1) and (zgl_szcz_zgl_id=$temp_nr) ORDER BY zgl_szcz_nr_kroku DESC LIMIT 1", $conn_hd));
					$opis_stanow .= '<tr><td class=right>Ostatnia zmiana statusu</td><td><b>'.substr(AddMinutesToDate($temp_cwk,$temp_dzs1),0,16).'</b></td></tr>';
					
					//$opis_stanow .= '<tr><td class=right>Ostatnia zmiana statusu</td><td><b>'.substr($temp_dzs,0,16).'</b></td></tr>';
								
					$opis_stanow .= '<tr><td class=right>Przypisane do</td><td><b>'.$przypisanedo.'</b></td></tr>';
					
					$res1 = mysql_query("SELECT zgl_szcz_id FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$temp_nr') and (zgl_szcz_widoczne=1) and (zgl_szcz_byl_wyjazd=1)") or die($k_b);
					list($_bw)=mysql_fetch_array($res1);
					if ($_bw>1) {
						$opis_stanow .= '<tr><td class=right>Był wyjazd ?</td><td><b><font color=green>TAK</font></b></td></tr>';
					} else {
						$opis_stanow .= '<tr><td class=right>Był wyjazd ?</td><td><b><font color=red>NIE</font></b></td></tr>';
					}
					
				} else {
					$opis_stanow .= '<tr><td class=right>Status</td><td><b>'.$status.'</b></td></tr>';
					$opis_stanow.= '<tr><td colspan=2><hr /></td></tr>';
					
					$res1 = mysql_query("SELECT zgl_szcz_id FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$temp_nr') and (zgl_szcz_widoczne=1) and (zgl_szcz_byl_wyjazd=1)") or die($k_b);
					list($_bw)=mysql_fetch_array($res1);
					if ($_bw>1) {
						$opis_stanow .= '<tr><td class=right>Był wyjazd ?</td><td><b><font color=green>TAK</font></b></td></tr>';
					} else {
						$opis_stanow .= '<tr><td class=right>Był wyjazd ?</td><td><b><font color=red>NIE</font></b></td></tr>';
					}
					
				}
		
			
				if ($czy_sa_stany_posrednie>0) {
				
					if ( (($temp_kategoria=='2') || ($temp_kategoria=='6') || ($temp_kategoria=='3')) && (($temp_podkategoria=='3') || ($temp_podkategoria=='4') || ($temp_podkategoria=='9') || ($temp_podkategoria=='0') || ($temp_podkategoria=='5') || ($temp_podkategoria=='2')) ) {
						$opis_stanow.= '<tr><td colspan=2></td></tr>';
						$opis_stanow.= '<tr height=24><td colspan=2 class=center style=background-color:#2F3047><font color=white>Stany pośrednie</font></td></tr>';
						if ($temp_zdiagnozowany!='9') $opis_stanow.= '<tr><td class=right>Zdiagnozowany</td><td>'.$zdiagnozowany.'</td></tr>';
						if ($temp_oferta_wyslana!='9') $opis_stanow.= '<tr><td class=right>Oferta wysłana</td><td>'.$oferta_wyslana.'</td></tr>';
						if ($temp_akceptacja_kosztow!='9') $opis_stanow.= '<tr><td class=right>Akceptacja kosztów</td><td>'.$akceptacja_kosztow.'</td></tr>';
						if ($temp_zamowienie_wyslane!='9') $opis_stanow.= '<tr><td class=right>Zamówienie wysłane</td><td>'.$zamowienie_wyslane.'</td></tr>';
						if ($temp_zamowienie_zrealizowane!='9') $opis_stanow.= '<tr><td class=right>Zamówienie zrealizowane</td><td>'.$zamowienie_zrealizowane.'</td></tr>';
						if ($temp_gotowe_do_oddania!='9') $opis_stanow.= '<tr><td class=right>Gotowe do oddania</td><td>'.$gotowe_do_oddania.'</td></tr>';
						
					} 
				}
				$opis_stanow.= '</table>';
			}
						
			echo "<a class='normalfont ";
			
			if ($wiecej_informacji_w_Helpdesk) {
				echo "title' title='$opis_stanow'";
			} else {
				echo "' title='Status zgłoszenia: ";
					switch ($temp_status) {
						case "1"	: echo "nowe"; break;
						case "2"	: echo "przypisane"; break;
						case "3"	: echo "rozpoczęte"; break;
						case "3A"	: echo "w serwisie zewnętrznym"; break;
						case "3B"	: echo "w firmie"; break;
						case "4"	: echo "oczekiwanie na odpowiedź klienta"; break;
						case "5"	: echo "oczekiwanie na sprzęt"; break;
						case "6"	: echo "do oddania"; break;
						case "7"	: echo "rozpoczęte, nie zakończone"; break;
						case "9"	: echo "zamknięte"; break;
					}			
				echo "' ";
			}
			
			if ($zastap_obsluge_zmiana_statusu==0) {
				echo " href=# onclick=\"newWindow_r(800,600,'hd_o_zgloszenia.php?action=obsluga&id=$temp_id&nr=$temp_nr&zgl_s=$zgl_seryjne_mark'); return false;\">";
			} else {
				if ($temp_status!=9) {
					echo " href=# onclick=\"newWindow_r(800,600,'hd_o_zgloszenia_zs.php?id=$temp_id&unr=$temp_unikalny_nr&nr=$temp_nr&ts=$temp_status&tk=$temp_kategoria&tpk=$temp_podkategoria&zgoda=9&komorka=".urlencode($temp_komorka)."&osoba=".urlencode($temp_osoba)."&zgl_s=$zgl_seryjne_mark&rozwiazany=$temp_czy_rozwiazany&ww=$temp_ww&clearcookies=1'); return false; \">";
				} else {
					echo " href=# onclick=\"newWindow_r(800,600,'hd_o_zgloszenia.php?action=obsluga&id=$temp_id&nr=$temp_nr&zgl_s=$zgl_seryjne_mark'); return false;\">";
				}
			}
			
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
			
			echo "<td style='text-align:center;vertical-align:top;padding-top:3px'>";
			
			// obsługa błędnie zapisanej daty lub godziny zgłoszenia
			if (($temp_data=='0000-00-00') || ($temp_godzina=='00:00:00')) {
				list($data_1_kroku)=mysql_fetch_array(mysql_query("SELECT zgl_szcz_czas_rozpoczecia_kroku FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_widoczne=1) and (zgl_szcz_zgl_id=$temp_nr) and (zgl_szcz_nr_kroku=1) LIMIT 1", $conn_hd));
				
				echo "<span style='background-color:red; color:white'>";
			}
			
			//if (($_REQUEST[s]!='XD') && ($_REQUEST[s]!='XA')) {
			//	echo $temp_data." ".substr($temp_godzina,0,5);
			//} else {
			if ($_REQUEST[p5]=='9') echo "<font color=grey>";
				echo "<a class=normalfont onClick=\"return false;\" ".$change_color_start.">";
				echo $temp_data." ".substr($temp_godzina,0,5);
				echo "</a>";
			if ($_REQUEST[p5]=='9') echo "</font>";
			//}
		
			if ($_REQUEST[p5]=='9') {
			//if (($_REQUEST[s]=='XD') || ($_REQUEST[s]=='XA')) {
				echo "<br />";
				list($data_ostatniego_kroku)=mysql_fetch_array(mysql_query("SELECT zgl_szcz_czas_rozpoczecia_kroku FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_widoczne=1) and (zgl_szcz_zgl_id=$temp_nr) ORDER BY zgl_szcz_nr_kroku DESC LIMIT 1", $conn_hd));
				echo substr($data_ostatniego_kroku,0,16);
			//}
			}
			
			if (($temp_data=='0000-00-00') || ($temp_godzina=='00:00:00')) {
				echo "</span><br />błędna data rejestracji zgłoszenia";
				
				if ($data_1_kroku!='0000-00-00 00:00:00') {
					echo "<br /><input title='Popraw błędnie zarejestrowaną datę zgłoszenia. ' type=button class=buttons value='Popraw' onClick=\"if (confirm('Czy ustawić datę i godzinę rejestracji zgłoszenia na datę rejestracji pierwszego kroku: ".substr($data_1_kroku,0,16)." ?')) {  newWindow(600,100,'hd_popraw_date_zgloszenia.php?nr=$temp_nr'); return false } ;\" />";
				} else {
					echo "<br />Popraw datę rejestracji pierwszego kroku zgłoszenia";
				}
				
			}			
			
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
				
				$__wersja		= 1;				// 1 - div, 2 - h2
				$__add_refresh	= 0;
				$__add_br		= 0;
				$__tunon		= $turnon__hd_p_zgloszenia;
				
				if ($__tunon) include('warning_messages.php');
				// okienka ostrzegawcze | KONIEC
			
			_td();
			
			echo "<td style='text-align:left;vertical-align:top;padding-top:3px'>";
			if ($temp_zgl_seryjne!='') {
				$temp_komorka_upper = toUpper($temp_komorka);
			} else {
				$temp_komorka_upper = $temp_komorka;
			}
			//$temp_komorka_upper = strtr($temp_komorka_upper,'ă','Ą');			
			//echo $temp_komorka_upper;
			echo "<a class=normalfont ".$change_color_start." href=# onClick=\"newWindow_r(700,600,'p_komorka_szczegoly.php?komorka=".urlencode($temp_komorka_upper)."&f=$temp_bt'); return false;\" >".$temp_komorka_upper."</a>";
			
/*
	$r44 = mysql_query("SELECT up_id, up_ip, up_telefon FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((serwis_komorki.belongs_to=$es_filia) and (serwis_piony.pion_id=serwis_komorki.up_pion_id) and (CONCAT(serwis_piony.pion_nazwa,' ',serwis_komorki.up_nazwa)='$temp_komorka'))", $conn) or die($k_b);
	list($_upid,$_ip,$_tel)=mysql_fetch_array($r44);
		
	tr_();	td_("150;rt;Komórka;"); 				_td(); 	td_(";;;");		echo "<a class=normalfont title='Szczegółowe informacje o $temp_komorka' href=# onClick=\"newWindow_r(700,600,'p_komorka_szczegoly.php?id=$_upid'); return false;\" ><b>".($temp_komorka)."</b></a>&nbsp;&nbsp;";
*/

			_td();
			echo "<td style='text-align:left;vertical-align:top;padding-top:3px'>";
			echo "<span style='overflow:auto'>";
			
			if ($zastap_obsluge_zmiana_statusu==1) {
				echo "<a class=normalfont ".$change_color_start." title='Pokaż szczegóły zgłoszenia nr $temp_nr' href=# onclick=\"newWindow_r(800,600,'hd_o_zgloszenia.php?action=obsluga&id=$temp_id&nr=$temp_nr&zgl_s=$zgl_seryjne_mark'); return false;\">";
			}
				if ($_REQUEST[st]!='') {
					echo highlight(nl2br($temp_temat),$_REQUEST[st]);
				} else {
					echo nl2br($temp_temat);
				}
			if ($zastap_obsluge_zmiana_statusu==1) {
				echo "</a>";
			}
			
			if (($temp_naprawa_id>0) && ($szczegoly_naprawy_w_przegladaniu_zgloszen)) {
				echo "&nbsp;";
				echo "<a id=i_$temp_id href=# title='Pobierz informacje o naprawianym sprzęcie' onclick=\"$('#n_$temp_id').load('naprawa_pokaz_info.php?id=$temp_id&nid=$temp_naprawa_id&randval=".rand(1000,1000000)."');$('#n_$temp_id').show();\"><input class=imgoption type=image width=8 height=8 src=img/get_info.gif></a>";
				echo "<a id=h_$temp_id href=# title='Ukryj informacje o naprawianym sprzęcie' style='display:none' onclick=\"$('#n_$temp_id').hide();$('#i_$temp_id').show();$('#h_$temp_id').hide();\"><input class=imgoption type=image width=8 height=8 src=img/hide_info.gif></a>";
				echo "<div id=n_$temp_id style='display:'></div>";
				echo "<span id=l_$temp_id style='display:'></span>";
			}
			
			echo "</span>";
			_td();
			_tr();
			
			$i++;
			
			if ($i % 16 ==0) { ob_flush();	flush(); }
		}

		if ($_GET[p5]==9) {
			if (($es_nr==$KierownikId) || ($es_m==1)) {
				echo "<tr class=hideme><td colspan=9>";
					echo "<span>";
					echo "<input class=imgoption type=image src=img/obsluga_seryjna.png>";
				
					echo "Zaznaczono: ";
				
					echo "<span id=IloscZaznaczonych style='font-weight:bold; '>0</span>";
						echo "<span style='display:none;' id=NapisPrzed>";
							echo "&nbsp;|&nbsp;Oznacz jako:&nbsp;<input type=button id=OznaczJakoSprawdzone1 class=buttons style='color:blue' value='Sprawdzone' title='Oznacz wybrane zgłoszenia jako sprawdzone' onClick=\"if (confirm('Czy napewno chcesz oznaczyć wybrane zgłoszenia jako `sprawdzone` ?')) OznaczJakoSprawdzone(1);\" />";
							echo "<input type=button id=OznaczJakoSprawdzone2 class=buttons style='color:blue' value='Niesprawdzone' title='Oznacz wybrane zgłoszenia jako niesprawdzone' onClick=\"if (confirm('Czy napewno chcesz oznaczyć wybrane zgłoszenia jako `niesprawdzone` ?')) OznaczJakoSprawdzone(0);\" />";			
						echo "</span>";
					echo "</span>";
					
				startbuttonsarea("left");
				echo "<br />Wszystkie: ";
				echo "<input class=buttons type=button onClick=\"MarkCheckboxes('zaznacz'); UpdateIloscZaznaczen(); \" value='Zaznacz'>";
				echo "<input class=buttons type=button onClick=\"MarkCheckboxes('odznacz'); UpdateIloscZaznaczen(); \" value='Odznacz'>";
				echo "<input class=buttons type=button onClick=\"MarkCheckboxes('odwroc'); UpdateIloscZaznaczen(); \"value='Odwróć zaznaczenie'>";
				endbuttonsarea();
			
				echo "</td></tr>";
			}
		}
		
		if ($_GET[p5]!=9) {
			//	starttable();
			echo "<tr class=hideme><td colspan=9>";
			echo "<span>";
			echo "<input class=imgoption type=image src=img/obsluga_seryjna.png>";
			
			echo "Zaznaczono: ";
			
			echo "<span id=IloscZaznaczonych style='font-weight:bold; '>0</span>";
			//	echo "&nbsp;|&nbsp;";
			
			//echo "&nbsp;<input type=button id=ObslugaZgloszen class=buttons value='Dodaj wybrane zgłoszenia do harmonogramu wyjazdu' onClick=\"UtworzHarmonogramWyjazdu();\" />";
			//echo "</span>";
			//if ($_GET[p5]=='1') {
			echo "<span style='display:none;' id=NapisPrzed>";
			echo "&nbsp;|&nbsp;";
			echo "<input type=button id=DrukujPotwierdzenia class=buttons style='font-weight:normal;' value='Drukuj potwierdzenia' onClick=\"if (confirm('Czy napewno chcesz wydrukować potwierdzenie dla wybranych zgłoszeń ?')) { DrukujPotwierdzeniaSeryjnie(); } else { return false; }\" />";
			echo "<input type=button id=ObslugaZgloszen class=buttons style='font-weight:bold;' value='Obsługuj wybrane' onClick=\"PokazNrZgloszen();\" />";
			
			//echo "<fieldset id=Wyj style='display:inline; padding:3px 6px 3px 6px; '><legend>&nbsp;Wyjazdowe&nbsp;</legend>";	
			echo "<span id=Wyj>";
			echo "&nbsp;|&nbsp;<input type=button id=OznaczDoWyjazdu1 class=buttons style='color:blue' value='Ustaw wyjazdowe' title='Oznacz wybrane zgłoszenia jako wymagające wyjazdu' onClick=\"if (confirm('Czy napewno chcesz ustawić wybrane zgłoszenia jako wyjazdowe ?')) { OznaczJakoWyjazdowe(1); } else { return false; } \" />";
			
			echo "<input type=button id=OznaczDoWyjazdu2 class=buttons style='color:blue' value='Usuń wyjadowe' title='Oznacz wybrane zgłoszenia jako wymagające wyjazdu' onClick=\"if (confirm('Czy napewno chcesz usunąć oznaczenie wybranych zgłoszeń jako wyjazdowe ?')) { OznaczJakoWyjazdowe(0); } else { return false; } \" />";
			
			echo "</span>";
			
			echo "</span>";
			
			//echo "</fieldset>";
			echo "</span>";
			
			if (($es_nr==$KierownikId) || ($es_m==1)) {
				//echo "<fieldset id=FSPrzypiszDoOsoby style='display:inline; padding:3px 6px 3px 6px;'><legend>&nbsp;Przypisz do osoby&nbsp;</legend>";
				//echo "&nbsp;|&nbsp;Przypisz do osoby: ";
				echo "<span id=FSPrzypiszDoOsoby style='display:none;'>";
				echo "&nbsp;|&nbsp;";//Przypisz do osoby:";
				$result44 = mysql_query("SELECT DISTINCT user_first_name,user_last_name FROM $dbname.serwis_uzytkownicy WHERE (belongs_to=$es_filia) and (user_locked=0) ORDER BY user_last_name ASC", $conn_hd) or die($k_b);
				//$count_rows1 = mysql_num_rows($result44);
				echo "<input type=button class=buttons value='Przypisz do osoby' onClick=\"if (confirm('Czy napewno chcesz przypisać wybrane zgłoszenia do wybranej osoby ?')) { PrzypiszSeryjnieDoOsoby(); return false; } else { return false; } \" />";
				echo "<select class=wymagane id=pdo name=pdo />\n";
				echo "<option value=''></option>\n";
				while (list($temp_imie,$temp_nazwisko) = mysql_fetch_array($result44)) {
					$imieinazwisko = $temp_imie . " " . $temp_nazwisko;
					echo "<option value='$imieinazwisko' ";
					if ($currentuser=='$imieinazwisko') echo " SELECTED";
					echo ">$imieinazwisko</option>\n"; 
				}
				echo "</select>\n";					
				echo "</span>";
				//echo "</fieldset>";
			}
			
			//}
			echo "</span>";
			//echo "<img src='img\obsluga_seryjna.png' />";
			
			startbuttonsarea("left");
			echo "<br />Wszystkie: ";
			echo "<input class=buttons type=button onClick=\"MarkCheckboxes('zaznacz'); UpdateIloscZaznaczen(); \" value='Zaznacz'>";
			echo "<input class=buttons type=button onClick=\"MarkCheckboxes('odznacz'); UpdateIloscZaznaczen(); \" value='Odznacz'>";
			echo "<input class=buttons type=button onClick=\"MarkCheckboxes('odwroc'); UpdateIloscZaznaczen(); \"value='Odwróć zaznaczenie'>";
			endbuttonsarea();
			
			echo "</td></tr>";
			//endtable();
		}

		echo "<tr class=hideme><td colspan=6>";
		if ($count_rows>0) include_once('paging_end_hd.php');
		echo "</td></tr>";

		if ($enableHDszczPreviewDIV==1) {
			//echo "<tr class=kolor_tla style='height:2px; background-color:transparent;'><td colspan=6 ><hr style='margin:0px;' /></td></tr>";
			
			echo "<tr id=PodgladDIV style='vertical-align:top'>";
			echo "<td colspan=6 style='padding:0px;'>";
			echo "<h6 class=hd_h6 style='margin-bottom:1px;'>Szczegóły realizacji wybranego zgłoszenia";
			echo "<span style='float:right'><a class='normalfont hideme' title='Ukryj wybrane szczegóły zgłoszenia' href=# onClick=\"$('#ZawartoscDIV').load('empty.php'); createCookie('hd_p_zgloszenia_div_nr','',365);\">X</a>&nbsp;</span>";
			echo "</h6>";
			echo "<a name=_SZ onClick=\"return false;\"></a>";
			echo "<span id=ZawartoscDIV></span>";
			nowalinia();
			echo "<span id=info>";
			echo "</span>";
			echo "</td>";
			echo "</tr>";
		}	

		if ($count_rows<15) {
			$uzupelnij_rows = ( 15 - $count_rows + 0 );
			for ($h=1; $h<$uzupelnij_rows; $h++) { echo "<tr class=hideme><td colspan=6>&nbsp;</td></tr>"; }
		} else {	
			//$uzupelnij_rows = 5;
			//for ($h=1; $h<$uzupelnij_rows; $h++) { echo "<tr><td colspan=6>&nbsp;</td></tr>"; }
		}
		endtable();
	} else {
		echo "<tr class=hideme>";
		echo "<td colspan=6 style='vertical-align:top; '>";
		$sql_search = $sql;
		$sql_search1a = substr($sql_search,9,strpos($sql_search,'(hd_zgloszenie.belongs_to='));
		$sql_search2a = substr($sql_search,strpos($sql_search,'(hd_zgloszenie.zgl_widoczne=1)'),strlen($sql_search));
		
		$sql_search_where = 'SELECT zgl_id FROM '.$dbname_hd.'.hd_zgloszenie, '.$dbname_hd.'.hd_zgloszenie_szcz WHERE '.$sql_search2a;
		
		$result99 = mysql_query($sql_search_where, $conn_hd) or die($k_b);
		$count_rows99 = mysql_num_rows($result99);
		$bz = 0;	
		if ($count_rows99==0) {
			switch ($_REQUEST[add]) {
			case "ptr" :	if (strlen($_REQUEST[p6])>1) {
					echo "<h2>Nie znaleziono zgłoszeń z przesuniętymi terminami rozpoczęcia dla użytkownika: $_REQUEST[p6]</h2>"; 
				} else echo "<h2>Nie znaleziono zgłoszeń z przesuniętymi terminami rozpoczęcia</h2>";
				break;
				echo "<h2>Nie znaleziono zgłoszeń z przesuniętymi terminami rozpoczęcia</h2>"; break;
			case "pzw" :	if (strlen($_REQUEST[p6])>1) {
					echo "<h2>Nie znaleziono zgłoszeń powiązanych z wyjazdem dla użytkownika: $_REQUEST[p6]</h2>"; 
				} else echo "<h2>Nie znaleziono zgłoszeń powiązanych z wyjazdem</h2>";
				break;
			case "pzn" :	if (strlen($_REQUEST[p6])>1) {
					echo "<h2>Nie znaleziono zgłoszeń powiązanych z naprawami dla użytkownika: $_REQUEST[p6]</h2>"; 
				} else echo "<h2>Nie znaleziono zgłoszeń powiązanych z naprawami</h2>";
				break;								
			case "sn14" :	if (strlen($_REQUEST[p6])>1) {
					echo "<h2>Nie znaleziono zgłoszeń starszych niż 14 dni dla użytkownika: $_REQUEST[p6]</h2>"; 
				} else echo "<h2>Nie znaleziono zgłoszeń starszych niż 14 dni</h2>";
				break;
			case "wp" :		if (strlen($_REQUEST[p6])>1) {
					echo "<h2>Nie znaleziono zgłoszeń powiązanych z wymianą podzespołów dla użytkownika: $_REQUEST[p6]</h2>"; 
				} else echo "<h2>Nie znaleziono zgłoszeń powiązanych z wymianą podzespołów</h2>";
				break;
			case "rekl" :	if (strlen($_REQUEST[p6])>1) {
					echo "<h2>Nie znaleziono zgłoszeń reklamacyjnych dla użytkownika: $_REQUEST[p6]</h2>"; 
				} else echo "<h2>Nie znaleziono zgłoszeń reklamacyjnych</h2>";
				break;
			case "nr" :		if ($_REQUEST[p2]=='2') { $opis1 = 'awarii zwykłych'; } else { $opis1 = 'awarii krytycznych'; }
				if (strlen($_REQUEST[p6])>1) {
					echo "<h2>Nie znaleziono zgłoszeń ".$opis1." nie rozwiązanych dla użytkownika: $_REQUEST[p6]</h2>"; 
				} else echo "<h2>Nie znaleziono zgłoszeń ".$opis1." nie rozwiązanych</h2>";
				break;
				
				default : echo "<h2>Nie znaleziono zgłoszeń spełniających wybrane kryteria</h2>"; $bz = 1; break;
				
			}
		} else {
			if ($_REQUEST[search_zgl_nr]!='') {
				list($czy_widoczne,$jaka_filia,$naprawa_id5,$naprawa_przek_id5,$naprawa_pz)=mysql_fetch_array(mysql_query("SELECT zgl_widoczne, $dbname_hd.hd_zgloszenie.belongs_to,zgl_naprawa_id,naprawa_przekazanie_naprawy_do,naprawa_przekazanie_zakonczone FROM $dbname.serwis_naprawa,$dbname_hd.hd_zgloszenie WHERE (zgl_id=$_REQUEST[search_zgl_nr]) and (hd_zgloszenie.zgl_naprawa_id>0) and (hd_zgloszenie.zgl_naprawa_id=serwis_naprawa.naprawa_id) LIMIT 1", $conn_hd));
			} else {
				$pg = $_REQUEST[page];
				if ($pg>1) {
					$pg--;
					
					$url = 'sa='.$_REQUEST[sa].'&page='.$pg.'&sel='.$_REQUEST[sel].'&id='.$_REQUEST[id].'&p1='.$_REQUEST[p1].'&p2='.$_REQUEST[p2].'&p3='.$_REQUEST[p3].'&p4='.$_REQUEST[p4].'&p5='.$_REQUEST[p5].'&p6='.$_REQUEST[p6].'&p7='.$_REQUEST[p7].'&sr='.$_REQUEST[sr].'&hd_rps='.$_REQUEST[hd_rps].'&s='.$_REQUEST[s].'&p0='.$_REQUEST[p0].'&ss='.$_REQUEST[ss].'&st='.$_REQUEST[st].'&sd='.$_REQUEST[sd].'&sk='.$_REQUEST[sk].'&add='.$_REQUEST[add].'';
					
					$new_path = "hd_p_zgloszenia.php?$url";
					infoheader("Automatyczne przejście na stronę nr <b>".$pg."</b> nastąpi za 5 sekund");
					echo "<br />";
					//echo $new_path;
					?><meta http-equiv="REFRESH" content="5;url=<?php echo "$new_path";?>"><?php
				} else {
					
					if ($count_rows>0) {
						$url = 'sa=0&p5=BZ&s=AD&page=1';
						$new_path = "hd_p_zgloszenia.php?$url";
						//echo $new_path;
						infoheader("Automatyczne załadowanie domyślnej strony nastąpi za 5 sekund");
						echo "<br />";
						?><meta http-equiv="REFRESH" content="5;url=<?php echo "$new_path";?>"><?php
					}
					
				}
				
			}
			// warunkowe dopuszczenie do przeglądania zgłoszenia w przypadku naprawy sprzętu przekazanej do docelowej filii
			$obca_filia = ($es_filia!=$naprawa_przek_id5);
			$warunkowo_dopusc = 0;
			if (($naprawa_przek_id5==$es_filia) && ($es_filia!=$jaka_filia)) {
				$warunkowo_dopusc = 1;
			}
			if (($naprawa_przek_id5==0) && ($es_filia==$jaka_filia)) {
				$warunkowo_dopusc = 1;
			}
			
			if ($warunkowo_dopusc!=1) {
				switch ($_REQUEST[add]) {
				case "ptr" :	echo "<h2>Nie znaleziono zgłoszeń z przesuniętymi terminami rozpoczęcia</h2>"; break;
				case "pzw" :	echo "<h2>Nie znaleziono zgłoszeń powiązanych z wyjazdem</h2>"; break;
				case "pzn" :	echo "<h2>Nie znaleziono zgłoszeń powiązanych z naprawami</h2>"; break;
				case "sn14" :	echo "<h2>Nie znaleziono zgłoszeń starszych niż 14 dni</h2>"; break;
				case "wp" :	if (strlen($_REQUEST[p6])>1) {
						echo "<h2>Nie znaleziono zgłoszeń powiązanych z wymianą podzespołów dla użytkownika: $_REQUEST[p6]</h2>"; 
					} else echo "<h2>Nie znaleziono zgłoszeń powiązanych z wymianą podzespołów</h2>";
					break;
				case "rekl" :	if (strlen($_REQUEST[p6])>1) {
						echo "<h2>Nie znaleziono zgłoszeń reklamacyjnych dla użytkownika: $_REQUEST[p6]</h2>"; 
					} else echo "<h2>Nie znaleziono zgłoszeń reklamacyjnych</h2>";
					break;
					
					default : echo "<h2>Nie znaleziono zgłoszeń spełniających wybrane kryteria</h2>"; 
				}
			}
			
		}
		
		if ($_REQUEST[search_zgl_nr]!='') {	
			//echo "SELECT zgl_widoczne, $dbname_hd.hd_zgloszenie.belongs_to,zgl_naprawa_id,naprawa_przekazanie_naprawy_do FROM $dbname.serwis_naprawa,$dbname_hd.hd_zgloszenie WHERE (zgl_id=$_REQUEST[search_zgl_nr]) and (hd_zgloszenie.zgl_naprawa_id>0) and (hd_zgloszenie.zgl_naprawa_id=serwis_naprawa.naprawa_id) LIMIT 1";
			
			list($czy_widoczne,$jaka_filia,$test_nr)=mysql_fetch_array(mysql_query("SELECT zgl_widoczne,belongs_to,zgl_nr FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id=$_REQUEST[search_zgl_nr]) LIMIT 1", $conn_hd));
			
			if ($czy_widoczne==1) {
				if ($obca_filia==false) {
					echo "<br /><h5>Zgłoszenie o numerze <b>$_REQUEST[search_zgl_nr]</b> zostało zarejestrowane w innej filii/oddziale. ";
					if ($naprawa_pz==0) {
						echo "<br /><br />Naprawa powiązana z tym zgłoszeniem została przesunięta do Twojej filii/oddziału. ";
						echo "<input type=button class=buttons value=' Obsługa zgłoszenia ' onclick=\"newWindow_r(800,600,'hd_o_zgloszenia.php?action=obsluga&id=$_REQUEST[search_zgl_nr]&nr=$_REQUEST[search_zgl_nr]'); return false;\" />";
					} else {
						echo "<br /><br />Naprawa powiązana z tym zgłoszeniem była przesunięta do Twojej filii/oddziału. ";
						echo "<input type=button class=buttons value=' Podgląd zgłoszenia ' onclick=\"newWindow_r(800,600,'hd_o_zgloszenia.php?action=podglad&id=$_REQUEST[search_zgl_nr]&nr=$_REQUEST[search_zgl_nr]'); return false;\" />";
					}
					
					echo "</h5>";
				} else {
					echo "<br /><h5 style='height:25px'>Zgłoszenie o numerze <b>$_REQUEST[search_zgl_nr]</b> zostało zarejestrowane w innej filii/oddziale. ";
					echo "<input type=button class=buttons value=' Podgląd zgłoszenia ' onclick=\"newWindow_r(800,600,'hd_o_zgloszenia.php?action=podglad&id=$_REQUEST[search_zgl_nr]&nr=$_REQUEST[search_zgl_nr]'); return false;\" />";
					echo "</h5>";
				}
			}
			
			if ((($czy_widoczne==0) && (($kierownik_nr==$es_nr) || ($is_dyrektor==1))) || ($warunkowo_dopusc==1)) { 
				
				if ($jaka_filia!=$es_filia) {
					//echo "<br /><h5>Zgłoszenie o numerze <b>$_REQUEST[search_zgl_nr]</b> zostało zarejestrowane w innej filii/oddziale. ";
					//echo "</h5>";				
				}
				
				if (($czy_widoczne==0) && ($test_nr>0)) echo "<br /><h3 style='font-weight:normal'>Zgłoszenie o numerze <b>$_REQUEST[search_zgl_nr]</b> zostało ukryte&nbsp;";
				
				if ($czy_widoczne==0)
					if ($jaka_filia==$es_filia) {
						echo "<input type=button class=buttons value=' Podgląd zgłoszenia ' onclick=\"newWindow_r(800,600,'hd_o_zgloszenia.php?action=podglad&id=$_REQUEST[search_zgl_nr]&nr=$_REQUEST[search_zgl_nr]'); return false;\" />";
				}
				if ($czy_widoczne==0) echo "</h3>";
			}
		}	
		
		$uzupelnij_rows = 25;
		for ($h=1; $h<$uzupelnij_rows; $h++) { echo "<tr><td colspan=6>&nbsp;</td></tr>"; }
		
		echo "</td>";
		echo "</tr>";
		endtable();
	}
	echo "</form>";
	
	session_register('ustawiono_kolejnosc');
	$_SESSION[ustawiono_kolejnosc]='NIE';
	oddziel();
	startbuttonsarea("right");

	echo "<span style='float:left'>";
	//echo "&nbsp;";
	addbuttons("wstecz");
	addbuttons("start");
	echo "&nbsp;|&nbsp;";
	addownlinkbutton("'Wykaz telefonów'","Button1","button","newWindow_r(800,600,'hd_p_komorki.php'); return false;");
	addownlinkbutton("'Osoby zgłaszające'","Button1","button","newWindow_r(800,600,'hd_z_osoby_zglaszajace.php?view=all'); return false;");
	
	echo "</span>";
	
	if (($kierownik_nr==$es_nr) || ($is_dyrektor==1)) {
		echo "&nbsp;<input type=button class=buttons value='Otwarte zadania'  onClick=\"newWindow_r($_COOKIE[max_x],$_COOKIE[max_y],'z_zadania.php?s=otwarte&noback=1'); return false;\" />";
		addownlinkbutton("'Towary dostępne'","Button1","button","self.location.href='p_towary_dostepne.php?view=normal';");
		addlinkbutton("'Sprzedaż w okresie'","main.php?action=pswo");
		echo "<br />";
	}

	addownlinkbutton("'Nowe zgłoszenie'","Button1","button","newWindow_r(800,600,'hd_d_zgloszenie_simple.php?stage=1')");
	if ($_REQUEST[sk]!='') {
		echo "<input class=buttons id=button1 name=button1 type=button style='color:blue;' onClick=\"newWindow_r(800,600,'hd_d_zgloszenie_simple.php?stage=1&dlakomorki=".urlencode($_REQUEST[sk])."'); return false;\" value='Nowe zgłoszenie dla ".$_REQUEST[sk]."'>";
	}
	
	//addownlinkbutton("'Nowe zgłoszenie seryjne'","Button1","button","newWindow_r(800,600,'hd_d_zgloszenie_s.php?stage=1&filtr=X-X-X-X&p6=true')");
	
	endbuttonsarea();

	include('body_stop.php');
}

// liczniki w filii dla wszystkich
$czy_jest_tabela_ze_statystykami_dla_filii = mysql_query("SELECT licznik_last_update FROM $dbname_hd.hd_licznik_zgloszen_".$es_filia." LIMIT 1", $conn_hd);
if ($czy_jest_tabela_ze_statystykami_dla_filii) {	

	list($last_update)=mysql_fetch_array($czy_jest_tabela_ze_statystykami_dla_filii);	
	$ddddd = date("Y-m-d H:i:s");
	
	if ($last_update!='') {
		if ($last_update<$ddddd) {
			?><script>
			$('#licznik_refresh').load('hd_uaktualnij_liczniki.php?f=<?php echo $es_filia; ?>&range=W&moj_nr=<?php echo $es_nr; ?>&randval='+Math.random()+'&refresh_parent=0&todiv=1&sourcepage=hd');
			</script><?php			
		}
		
	} else {		
		?>
		<script>
		$('#licznik_refresh').load('hd_uaktualnij_liczniki.php?f=<?php echo $es_filia; ?>&range=W&moj_nr=<?php echo $es_nr; ?>&randval='+Math.random()+'&refresh_parent=0&todiv=1&sourcepage=hd');
		</script><?php 
	}
	
} else {
	$sql_report = "CREATE TABLE `$dbname_hd`.`hd_licznik_zgloszen_".$es_filia."` (`licznik_id` INT( 10 ) NOT NULL AUTO_INCREMENT ,`licznik_opis` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL ,`licznik_wartosc` INT( 10 ) NOT NULL ,`licznik_kogo` VARCHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL COMMENT 'M - moje, W - wszystkie',`licznik_osoba` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL COMMENT 'jeżeli licznik_kogo = M',`licznik_last_update` DATETIME NOT NULL ,`belongs_to` TINYINT( 2 ) NOT NULL ,PRIMARY KEY ( `licznik_id` )) ENGINE = MYISAM ;";	
	$result_report = mysql_query($sql_report, $conn_hd) or die($k_b);	

	// uaktualnik liczniki
	?><script>
	$('#licznik_refresh').load('hd_uaktualnij_liczniki.php?f=<?php echo $es_filia; ?>&range=W&moj_nr=<?php echo $es_nr; ?>&randval='+Math.random()+'&refresh_parent=0&todiv=1&sourcepage=hd');
	
	</script><?php
	
}

// liczniki moich zgłoszeń
$czy_jest_tabela_ze_statystykami_dla_mnie= mysql_query("SELECT licznik_last_update FROM $dbname_hd.hd_licznik_zgloszen_".$es_filia."_".$es_nr." LIMIT 1", $conn_hd);
if ($czy_jest_tabela_ze_statystykami_dla_mnie) {	

	list($last_update)=mysql_fetch_array($czy_jest_tabela_ze_statystykami_dla_mnie);	
	$ddddd = date("Y-m-d H:i:s");
	
	if ($last_update!='') {
		if ($last_update<$ddddd) {
			// uaktualnik liczniki
			?><script>		
			$('#licznik_refresh').load('hd_uaktualnij_liczniki.php?f=<?php echo $es_filia; ?>&range=M&moj_nr=<?php echo $es_nr; ?>&cu=<?php echo urlencode($currentuser); ?>&randval='+Math.random()+'&refresh_parent=0&todiv=1&sourcepage=hd');
			
			</script><?php
		}
	} else {		
		?>
		<script>		
		$('#licznik_refresh').load('hd_uaktualnij_liczniki.php?f=<?php echo $es_filia; ?>&range=M&moj_nr=<?php echo $es_nr; ?>&cu=<?php echo urlencode($currentuser); ?>&randval='+Math.random()+'&refresh_parent=0&todiv=1&sourcepage=hd');			
		</script><?php 
	}
	
} else {
	$sql_report = "CREATE TABLE `$dbname_hd`.`hd_licznik_zgloszen_".$es_filia."_".$es_nr."` (`licznik_id` INT( 10 ) NOT NULL AUTO_INCREMENT ,`licznik_opis` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL ,`licznik_wartosc` INT( 10 ) NOT NULL ,`licznik_kogo` VARCHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL COMMENT 'M - moje, W - wszystkie',`licznik_osoba` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL COMMENT 'jeżeli licznik_kogo = M',`licznik_last_update` DATETIME NOT NULL ,`belongs_to` TINYINT( 2 ) NOT NULL ,PRIMARY KEY ( `licznik_id` )) ENGINE = MYISAM ;";	
	$result_report = mysql_query($sql_report, $conn_hd) or die($k_b);	

	// uaktualnik liczniki
	?><script>
	$('#licznik_refresh').load('hd_uaktualnij_liczniki.php?f=<?php echo $es_filia; ?>&range=M&moj_nr=<?php echo $es_nr; ?>&cu=<?php echo urlencode($currentuse); ?>&randval='+Math.random()+'&refresh_parent=0&todiv=1&sourcepage=hd');
	
	</script><?php
}

?>

<script>

anylinkcssmenu.init("anchorclass");
ApplyFiltrHD(false);

$('#ilosc_godzin').load('hd_p_zgloszenia_count_hours.php?randval='+ Math.random()).show();
$('#count_notatki_moje').load('hd_count_notes.php?user_id=<?php echo $es_nr; ?>&randval='+ Math.random()).show();
$('#notatki').load('hd_refresh_notes.php?userid=<?php echo $es_nr; ?>&randval='+ Math.random());
</script>

<?php if ($_REQUEST[sr]==1) { ?>
	<script language="JavaScript">
	var cal1 = new calendar1(document.forms['szukaj'].elements['sd']);
	cal1.year_scroll = true;
	cal1.time_comp = false;
	</script>
	<?php } ?>

<script>
//alert(readCookie('hd_p_zgloszenia_pokaz_statystyki'));
if ((readCookie('hd_p_zgloszenia_pokaz_statystyki')=='TAK') || (readCookie('hd_p_zgloszenia_pokaz_statystyki')==null)) {
	$('#PodgladStatystyk').show();
	$('#zwin_stats').show();
	$('#pokaz_stats').hide();
}
if (readCookie('hd_p_zgloszenia_pokaz_statystyki')=='NIE') {
	$('#PodgladStatystyk').hide();
	$('#zwin_stats').hide();
	$('#pokaz_stats').show();
} 
</script>
<script>
<?php if (($ClueTipOn==1) && ($wiecej_informacji_w_Helpdesk)) { ?>
	$(document).ready(function() { $('a.title').cluetip({splitTitle: '|', closePosition: 'title'}); });
<?php } ?>

<?php if ((($kierownik_nr==$es_nr) || ($is_dyrektor==1)) && ($funkcja_kontroli_pracownikow==1)) { ?>
	if (readCookie('hd_pokaz_pracownikow')=='TAK') { $('#pracownicy').show(); $('#pracownicy').load('wait_ajax.php?randval='+ Math.random()); $('#pracownicy').load('hd_refresh_pracownicy.php?randval=<?php echo date('HHiiss');?>'+ Math.random()); } else { $('#pracownicy').hide(); }
	<?php } ?>

<?php if (($_REQUEST[sr]=='search-wyniki') && ($_REQUEST[search_zgl_nr]>0)) { ?>
	$('#ZawartoscDIV').load('hd_p_zgloszenie_kroki.php?nr=<?php echo $temp_nr;?>&id=<?php echo $temp_id; ?>&randval=<?php echo $rand;?>');
	createCookie('hd_p_zgloszenia_div_nr','$sciezka_do_cookie',365); 
	$('#info').hide(); 
	self.location.href='#_SZ';
	<?php } ?>

<?php if ($totalrows==1) { ?>
	$('#ZawartoscDIV').load('hd_p_zgloszenie_kroki.php?nr=<?php echo $temp_nr;?>&id=<?php echo $temp_id; ?>&randval=<?php echo $rand;?>');
	createCookie('hd_p_zgloszenia_div_nr','$sciezka_do_cookie',365); 
	$('#info').hide(); 
	self.location.href='#_SZ';
	<?php } ?>


</script>

<script>
if ((readCookie('hd_p_zgloszenia_wszystkie')=='TAK') || (readCookie('hd_p_zgloszenia_wszystkie')==null)) {
	$('#pokaz_hd_wszystkie').hide();
	$('#ukryj_hd_wszystkie').show();
	$('#liczniki_wszyscy_dane').show();
	//WszystkieZgloszeniaShow();
	createCookie('hd_p_zgloszenia_wszystkie','TAK',365);
} 

if (readCookie('hd_p_zgloszenia_wszystkie')=='NIE') {
	$('#pokaz_hd_wszystkie').show();
	$('#ukryj_hd_wszystkie').hide();
	$('#liczniki_wszyscy_dane').hide();
	WszystkieZgloszeniaHide();
}

if ((readCookie('hd_p_zgloszenia_wybrane')=='TAK') || (readCookie('hd_p_zgloszenia_wybrane')==null)) {
	$('#pokaz_hd_wybrane').hide();
	$('#ukryj_hd_wybrane').show();
	$('#liczniki_wybrane_dane').show();
	//WszystkieZgloszeniaShow();
	document.getElementById('liczniki_wybrane').style.display='';
	createCookie('hd_p_zgloszenia_wybrane','TAK',365);
} 

if (readCookie('hd_p_zgloszenia_wybrane')=='NIE') {
	$('#pokaz_hd_wybrane').show();
	$('#ukryj_hd_wybrane').hide();
	$('#liczniki_wybrane_dane').hide();
	document.getElementById('liczniki_wybrane').style.display='none';
	//WszystkieZgloszeniaHide();
}

if ((readCookie('hd_p_zgloszenia_moje')=='TAK') || (readCookie('hd_p_zgloszenia_moje')==null)) {
	$('#pokaz_hd_moje').hide();
	$('#ukryj_hd_moje').show();
	$('#liczniki_moje_dane').show();
	//MojeZgloszeniaShow();
	createCookie('hd_p_zgloszenia_moje','TAK',365);
} 
if (readCookie('hd_p_zgloszenia_moje')=='NIE') {
	$('#pokaz_hd_moje').show();
	$('#ukryj_hd_moje').hide();
	$('#liczniki_moje_dane').hide();
	MojeZgloszeniaHide();
}

if ((readCookie('hd_szukaj_zgl')=='TAK') || (readCookie('hd_szukaj_zgl')==null)) {
	$('#pokaz_szukaj_zgl').hide();
	$('#ukryj_szukaj_zgl').show();
	$('#szukaj_dane').show();
} 
if (readCookie('hd_szukaj_zgl')=='NIE') {
	$('#pokaz_szukaj_zgl').show();
	$('#ukryj_szukaj_zgl').hide();
	$('#szukaj_dane').hide();
}

if ((readCookie('hd_pokaz_notatki')=='TAK') || (readCookie('hd_pokaz_notatki')==null)) {
	$('#pokaz_notatki').hide();
	$('#ukryj_notatki').show();
	$('#notatki_dane').show();
	$('#notatki_dane1').show();
	PokazNotatki(true);
	createCookie('hd_pokaz_notatki','TAK',365);
} 

if (readCookie('hd_pokaz_notatki')=='NIE') {
	$('#pokaz_notatki').show();
	$('#ukryj_notatki').hide();
	$('#notatki_dane').hide();
	$('#notatki_dane1').hide();			
	PokazNotatki(false);
}

if ((readCookie('hd_pokaz_pracownikow')=='TAK') || (readCookie('hd_pokaz_pracownikow')==null)) {
	$('#pokaz_pracownikow').hide();
	$('#ukryj_pracownikow').show();
	$('#tr_pracownicy').show();
	createCookie('hd_pokaz_pracownikow','TAK',365);
} 

if (readCookie('hd_pokaz_pracownikow')=='NIE') {
	$('#pokaz_pracownikow').show();
	$('#ukryj_pracownikow').hide();
	$('#tr_pracownicy').hide();
}

if ((readCookie('hd_pokaz_bledne_dane')=='TAK') || (readCookie('hd_pokaz_bledne_dane')==null)) {
	$('#pokaz_hd_bledne').hide();
	$('#ukryj_hd_bledne').show();
	$('#bledne_dane').show();
	createCookie('hd_pokaz_bledne_dane','TAK',365);
} 

if (readCookie('hd_pokaz_bledne_dane')=='NIE') {
	$('#pokaz_hd_bledne').show();
	$('#ukryj_hd_bledne').hide();
	$('#bledne_dane').hide();
}			

</script>

<?php include('warning_messages_blinking.php'); ?>

<?php if ($es_mminhd==1) {  ?>
<script type="text/javascript">
//<![CDATA[

var listMenu = new FSMenu('listMenu', true, 'display', 'block', 'none');
listMenu.showDelay = 100;
listMenu.switchDelay = 125;
listMenu.hideDelay = 300;
listMenu.cssLitClass = 'highlighted';
//listMenu.showOnClick = 1;
listMenu.animInSpeed = 0.5;
listMenu.animOutSpeed = 0.5;

function animClipDown(ref, counter)
{
var cP = Math.pow(Math.sin(Math.PI*counter/200),0.75);
ref.style.clip = (counter==100 ? (window.opera ? '': 'rect(auto, auto, auto, auto)') :
'rect(0, ' + ref.offsetWidth + 'px, '+(ref.offsetHeight*cP)+'px, 0)');
};

function animFade(ref, counter)
{
var f = ref.filters, done = (counter==100);
if (f)
{
if (!done && ref.style.filter.indexOf("alpha") == -1)
ref.style.filter += ' alpha(opacity=' + counter + ')';
else if (f.length && f.alpha) with (f.alpha)
{
if (done) enabled = false;
else { opacity = counter; enabled=true }
}
}
else ref.style.opacity = ref.style.MozOpacity = counter/100.1;
};

// I'm applying them both to this menu and setting the speed to 20%. Delete this to disable.
			//listMenu.animations[listMenu.animations.length] = animFade;
			//listMenu.animations[listMenu.animations.length] = animClipDown;
			//listMenu.animations[listMenu.animations.length] = FSMenu.animFade;
			//listMenu.animations[listMenu.animations.length] = FSMenu.animSwipeDown;

			//listMenu.animSpeed = 500;

			var arrow = null;
			if (document.createElement && document.documentElement)
			{
				arrow = document.createElement('img');
				arrow.src = 'img/menu.gif';
				arrow.style.borderWidth = '0';
				arrow.className = 'subind';
				arrow.width = '16';
				arrow.height = '16';
			}

			addEvent(window, 'load', new Function('listMenu.activateMenu("listMenuRoot", arrow)'));

			//]]>
</script>
<?php } ?>
<script>
<?php if ($_REQUEST[zmien_ww]=='1') { ?>
		if (document.getElementById('p2').value!='') MakePodkategoriaList(document.getElementById('p2').value);		
		document.getElementById('p3').value = '<?php echo $_REQUEST[p3]; ?>';
		
		if (document.getElementById('search_eserwis_nr').value!='') {  document.getElementById('search_sel3').click(); }
		if (document.getElementById('st').value!='') {  document.getElementById('search_sel6').click(); }
		if (document.getElementById('st_wc').value!='') {  document.getElementById('search_sel7').click(); }
<?php } ?>
</script>		

<script>HideWaitingMessage('licznik_refresh');</script>
<script>HideWaitingMessage();</script>

</body>
</html>