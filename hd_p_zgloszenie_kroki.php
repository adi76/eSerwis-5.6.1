<?php 

include_once('header.php');
include_once('cfg_helpdesk.php');

$span_name = 'StartLoading'.rand(5,10000);
if ($_REQUEST[newwindow]!='1') {

//	$span_name = 'StartLoading'.rand(5,10000);
	echo "<div id='".$span_name."' style='font-weight:normal; text-align:center; font-size:13px; border: 2px solid grey; color:white; background-color:black; margin-top:2px;"; 
	if ($_REQUEST[id]=='0') echo " display:none;";
	echo "'>";
	echo "<br /><center>Trwa pobieranie danych szczegółowych dla zgłoszenia nr <b>$_REQUEST[nr]</b> z serwera...<input type=image class=border0 src=img/loader7.gif></center><br />";
	echo "</div>";
} else {

	?><script>ShowWaitingMessage('Trwa pobieranie danych szczegółowych dla zgłoszenia nr <b><?php echo $_REQUEST[nr]; ?></b> z serwera','<?php echo $span_name; ?>');</script><?php ob_flush(); flush();

}
	if ($_REQUEST[id]=='0') exit;
	list($DataRejZgl,$OsobaRejZgl)=mysql_fetch_array(mysql_query("SELECT zgl_data_wpisu, zgl_szcz_osoba_rejestrujaca FROM hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$_REQUEST[id]) LIMIT 1"));
	
	$wynik = mysql_query("SELECT * FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id=$_REQUEST[id]) LIMIT 1", $conn_hd);
	
	while ($dane_f1=mysql_fetch_array($wynik)) {
		$temp2_zgl_nr		= $dane_f1['zgl_nr'];
		$temp_unikalny_nr	= $dane_f1['zgl_unikalny_nr'];
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
	//	$temp_priorytet		= $dane_f1['zgl_priorytet'];
		$temp_osoba_przypisana	= $dane_f1['zgl_osoba_przypisana'];
		$temp_status		= $dane_f1['zgl_status'];
		$temp_parent_zgl	= $dane_f1['zgl_kontynuacja_zgloszenia_numer'];
		$temp_naprawa_id	= $dane_f1['zgl_naprawa_id'];
		$temp_bt			= $dane_f1['belongs_to'];
		$temp_nr_awarii_wan	= $dane_f1['zgl_poledodatkowe1'];
		$temp_zgl_seryjne	= $dane_f1['zgl_poledodatkowe2'];
		
		$temp_rekl_czy_jest = $dane_f1['zgl_czy_to_jest_reklamacyjne'];
		$temp_rekl_nr	 	= $dane_f1['zgl_nr_zgloszenia_reklamowanego'];
		$temp_rekl_czy_ma	= $dane_f1['zgl_czy_ma_zgl_reklamacyjne'];
	
		$temp_czy_pow_z_wp	= $dane_f1['zgl_czy_powiazane_z_wymiana_podzespolow'];
		$temp_zgl_komorka_working_hours = $dane_f1['zgl_komorka_working_hours'];
		
		$temp_minut_do_rozpoczecia 		= $dane_f1['zgl_rozpoczecie_min'];
		$temp_minut_do_zakonczenia		= $dane_f1['zgl_zakonczenie_min'];
		
		$temp_nasz_czas_do_rozpoczecia = $temp_minut_do_rozpoczecia - $dane_f1['zgl_E1C'];
		$temp_nasz_czas_do_zakonczenia = $temp_minut_do_zakonczenia - ($dane_f1['zgl_E1C']+$dane_f1['zgl_E2C']);

		$temp_ss_id	= $dane_f1['zgl_sprzet_serwisowy_id'];
		
		$temp_zgl_data_rozpoczecia		= $dane_f1['zgl_data_rozpoczecia'];
		$temp_zgl_data_zakonczenia		= $dane_f1['zgl_data_zakonczenia'];
		$temp_zgl_E1P		= $dane_f1['zgl_E1P'];
		$temp_zgl_E2P		= $dane_f1['zgl_E2P'];
		$temp_zgl_E3P		= $dane_f1['zgl_E3P'];
		$temp_op			= $dane_f1['zgl_osoba_przypisana'];		
		
		$temp_data_roz		= $dane_f1['zgl_data_rozpoczecia'];
		$temp_data_zak		= $dane_f1['zgl_data_zakonczenia'];
	
	}
	
	if ($_GET[newwindow]=='1') pageheader("<font style='font-weight:normal'>Kroki realizacji zgłoszenia nr <b>$_REQUEST[nr]</b>&nbsp;z&nbsp;<b>$temp_komorka</b></font>",1,0);
	
	ob_flush();	
	flush();
	
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
	
	$newK = $_GET[kg];
	if ($newK=='') $newK = substr($kolorgrupy,1,strlen($kolorgrupy));
	
	echo "<h4 style='text-align:left;font-family:tahoma;font-size:12px;padding:3px; margin-bottom:3px; color:black;font-weight:normal; margin-top:4px; margin-left:1px;";
	echo "background-color:#$newK";
	echo "'>";
	
	echo "<a id=PokazSzczegolyZgloszenia class=normalfont title=' Pokaż szczegóły zgłoszenia ' href=# onClick=\"$('#InformacjeOZgloszeniu$_REQUEST[nr]').toggle(); $('#PokazSzczegolyZgloszenia').hide(); $('#UkryjSzczegolyZgloszenia').show(); return false; \" >";
	
	echo "Nr zgłoszenia: <b>".$_REQUEST[nr]."</b> | Osoba rejestrująca: <b>$OsobaRejZgl</b> | Data rejestracji: <b>".substr($DataRejZgl,0,strlen($DataRejZgl)-3)."</b>";	
	
	echo "&nbsp;<input type=image class=imgoption src=img/show_more_".$_REQUEST[kz].".gif>";
	echo "</a>";
	echo "<a id=UkryjSzczegolyZgloszenia style='display:none' class=normalfont title=' Ukryj szczegóły zgłoszenia ' href=# onClick=\"$('#InformacjeOZgloszeniu$_REQUEST[nr]').toggle(); $('#PokazSzczegolyZgloszenia').show(); $('#UkryjSzczegolyZgloszenia').hide(); return false; \" >";
	
	echo "Nr zgłoszenia: <b>".$_REQUEST[nr]."</b> | Osoba rejestrująca: <b>$OsobaRejZgl</b> | Data rejestracji: <b>".substr($DataRejZgl,0,strlen($DataRejZgl)-3)."</b>";	
	
	echo "&nbsp;<input type=image class=imgoption src=img/hide_more_".$_REQUEST[kz].".gif>";
	echo "</a>";
	
//	nowalinia();
	
	$numer_zgloszenia = $_REQUEST[nr];

	echo "<div id=InformacjeOZgloszeniu$_REQUEST[nr] style='display:none'>";
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
		if ($temp_podkategoria2=='') $temp_podkategoria2='Brak';
		echo "<tr><td class=right>Podkategoria (poziom 2)</td><td><b>$temp_podkategoria2</b></td></tr>";
		
	// informacje o czasach zgodnych z umową - POCZĄTEK
		$__zgl_data_r 	= $temp_data_roz;
		$__zgl_data_z	= $temp_data_zak;
		$__temp_zgl_E2P	= $temp_zgl_E2P;
		$__color		= FALSE;				// czy mają być na czerwono wyróżnione daty wyliczone przez system
		
		include("hd_o_zgloszenia_SLA_info.php");
		
	// informacje o czasach zgodnych z umową - KONIEC
	
		echo "</table>";
	echo "</div>";

	echo "<sub style='position:relative;float:right; text-decoration:none;'>";
		echo "<a title=' Informacje o zgłoszeniu nr $_REQUEST[nr] '></a>";
		//<input class=imgoption type=image src=img/obsluga_zgloszenia.gif onclick=\"newWindow_r(800,600,'hd_o_zgloszenia.php?action=info&id=$_REQUEST[id]&nr=$_REQUEST[nr]')\"></a>";
	echo "</sub>";
	
	echo "</h4>";
	
	if ($temp_zgl_komorka_working_hours=='') $temp_zgl_komorka_working_hours = $default_working_time;
	
	// jeżeli zgłoszenie jest powiązane z innym zgłoszeniem
	if (($temp_parent_zgl!=0) && ($_GET[is_child]!=1)) {
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
			$temp_parent_naprawa_id			= $dane_f1_parent['zgl_naprawa_id'];
			$temp_parent_bt					= $dane_f1_parent['belongs_to'];
			
		}
		
		switch ($temp_kategoria_parent) {
			case 2:	$kolorgrupy='#FF7F2A'; break; 
			case 6: $kolorgrupy='#F73B3B'; break;
			case 3:	$kolorgrupy=''; break; 			
			default: $kolorgrupy='#FFFFFF'; break; 
		}
		
		//echo ">>>> $_REQUEST[nr]";
		echo "<h3 class=h3powiazane><center>Zgłoszenie numer <b>$_REQUEST[nr]</b> utworzono na bazie zgłoszenia nr <b>".$temp_parent_zgl."</b>&nbsp;<input type=button class=buttons id=parent_zgl_button value=' Pokaż / ukryj szczegóły realizacji zgłoszenia ".$temp_parent_zgl."' onClick=\"$('#parent_zgl_info').toggle(); $('#parent_zgl_info').load('hd_p_zgloszenie_kroki.php?nr=".$temp_parent_zgl."&id=".$temp_parent_zgl."&is_parent=1&is_child=0&kg=".substr($kolorgrupy,1,strlen($kolorgrupy))."'); return false; \" /></center></h3>";
		echo "<div id=parent_zgl_info style='display:none'>";
		//if ($_GET[is_parent]=='1') echo "<br /><br />123";
		echo "</div>";
		
		$sql88="SELECT zgl_czy_powiazane_z_wymiana_podzespolow FROM $dbname_hd.hd_zgloszenie WHERE (zgl_nr=$temp_parent_zgl) LIMIT 1";
		$result88 = mysql_query($sql88, $conn_hd) or die($k_b);
		list($temp_czy_pow_z_wp_parent)=mysql_fetch_array($result88);		
	}
	
	if ($temp_naprawa_id>0) {
		//echo "Zgłoszenie powiązane jest z naprawą";
		$result99 = mysql_query("SELECT * FROM $dbname.serwis_naprawa WHERE naprawa_hd_zgl_id='$_REQUEST[id]' ORDER BY naprawa_id DESC LIMIT 1");
		
		while ($dane99 = mysql_fetch_array($result99)) {
			$mid 		= $dane99['naprawa_id'];					
			$mnazwa	 	= $dane99['naprawa_nazwa'];
			$mmodel		= $dane99['naprawa_model'];			
			$msn 		= $dane99['naprawa_sn'];
			$mni 		= $dane99['naprawa_ni'];
			$mdos		= $dane99['naprawa_data_oddania_sprzetu'];
			$moos		= $dane99['naprawa_osoba_oddajaca_sprzet'];
			$mstatus	= $dane99['naprawa_status'];
			$msz		= $dane99['naprawa_sprzet_zastepczy_id'];
			$bt 		= $dane99['belongs_to'];
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
			
			$result65 = mysql_query("SELECT sn_nazwa FROM $dbname.serwis_sposob_naprawy WHERE (sn_id='$mstatus') LIMIT 1", $conn) or die($k_b);
			list($mstatus_opis)=mysql_fetch_array($result65);
		
			$mid = $temp_naprawa_id;
			$make_link = '';
			if ($mstatus=='-1') $make_link =  "<a title=' Sprzęt pobrany od klienta ' href=# onClick=self.location.href='main.php?action=npus&id=$mid&cs=$es'><img class=imgoption src=img/snapraw_p.gif border=0 width=16 width=16></a>";
			if ($mstatus=='0') $make_link =  "<a title=' Sprzęt jest naprawiany we własnym zakresie ' href=# onClick=self.location.href='main.php?action=nwwz&id=$mid&cs=$es'><img class=imgoption src=img/w_naprawie_wz.gif border=0 width=16 width=16></a>";
			if ($mstatus=='1') $make_link =  "<a title=' Sprzęt jest w naprawie w serwisie zewnętrznym ' href=# onClick=self.location.href='main.php?action=npswsz&id=$mid&cs=$es'><img class=imgoption src=img/w_naprawie_sz.gif border=0 width=16 width=16></a>";
			if ($mstatus=='2') $make_link =  "<a title=' Sprzęt jest w naprawie w serwisie lokalnym ' href=# onClick=self.location.href='main.php?action=nsnrl&id=$mid&cs=$es'><img class=imgoption src=img/w_naprawie_sl.gif border=0 width=16 width=16></a>";
			if ($mstatus=='3') $make_link =  "<a title=' Sprzęt wrócił z naprawy ' href=# onClick=self.location.href='p_naprawy_zakonczone.php?id=$mid&cs=$es'><img class=imgoption
	 src=img/snapraw_ok.gif border=0></a>";
			if ($mstatus=='5') $make_link =  "<a title=' Zwrócony do klienta $mdos przez $moos '><input class=imgoption type=image
	 src=img/ok.gif></a>";
			if ($mstatus=='7') $make_link =  "<a title=' Sprzęt wycofany z serwisu '><input class=imgoption type=image src=img//wycofaj_z_serwisu.gif onclick=\"self.location.href='main.php?action=nsw&id=$mid'\"></a>";
			
			//naprawaheader("<center><b>Zgłoszenie powiązane z naprawą</b><a title=' Pokaż szczegóły naprawy'><input class=imgoption type=image src=img/detail.gif onclick=\"newWindow(800,600,'p_naprawy_szczegoly.php?id=$mid'); return false;\"></a><br /><br /><b>".$mnazwa." ".$mmodel."</b> | SN: <b>".$msn."</b> | Status naprawy: <b>".$mstatus_opis."</b></center>");

			if ($msz>0) {
				$result99 = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni FROM $dbname.serwis_magazyn WHERE magazyn_id='$msz' LIMIT 1", $conn) or die($k_b);
				list($szid, $sznazwa, $szmodel, $szsn, $szni) = mysql_fetch_array($result99);
				
				naprawaheader("<center>Zgłoszenie powiązane z naprawą | status naprawy: <b>".$mstatus_opis."</b> | <input type=button class=buttons value='szczegóły naprawy' onclick=\"newWindow(800,600,'p_naprawy_szczegoly.php?id=$mid'); return false;\" /><br /><b>Sprzęt uszkodzony: </b>".$mnazwa." ".$mmodel." | SN: ".$msn." | NI: $mni<br /><b>Sprzęt zastępczy: </b>".$sznazwa." ".$szmodel." | SN: ".$szsn." | NI: ".$szni." | <input type=button class=buttons value='szczegóły' onclick=\"newWindow(800,600,'p_serwis_szczegoly.php?id=$_REQUEST[nr]&parentid=$temp_parent_zgl'); return false;\" /></center>");
				
				if ($move_naprawa==false) naprawaheader2("<center>Realizacja naprawy przekazana do lokalizacji: <b>".$mnwif_NazwaFilii."</b><br />Zamknięcie zgłoszenia możliwe będzie po zwrocie sprzętu do Twojej lokalizacji.");
				
				if (($move_naprawa==true) && ($mnwif!=0) && ($mnwifpz==0)) naprawaheader2("<center>Naprawiany sprzęt przekazany z lokalizacji: <b>".$NazwaMojejFilii."</b><br />Zamknięcie zgłoszenia możliwe jest przez osobę z filii przekazującej.");
				
			} else {
				naprawaheader2("<center>Zgłoszenie powiązane z naprawą | status naprawy: <b>".$mstatus_opis."</b> | <input type=button class=buttons value='szczegóły naprawy' onclick=\"newWindow(800,600,'p_naprawy_szczegoly.php?id=$mid'); return false;\" /><br /><b>Sprzęt uszkodzony: </b>".$mnazwa." ".$mmodel." | SN: ".$msn." | NI: $mni</center>");
				
				if ($move_naprawa==false) naprawaheader2("<center>Realizacja naprawy przekazana do lokalizacji: <b>".$mnwif_NazwaFilii."</b><br />Zamknięcie zgłoszenia możliwe będzie po zwrocie sprzętu do Twojej lokalizacji.");
				
				if (($move_naprawa==true) && ($mnwif!=0) && ($mnwifpz==0)) naprawaheader2("<center>Naprawiany sprzęt przekazany z lokalizacji: <b>".$NazwaMojejFilii."</b><br />Zamknięcie zgłoszenia możliwe jest przez osobę z filii przekazującej.");
			}
			
			//naprawaheader("<center>Zgłoszenie powiązane z naprawą | status naprawy: <b>".$mstatus_opis."</b> | <input type=button class=buttons value='szczegóły naprawy' onclick=\"newWindow(800,600,'p_naprawy_szczegoly.php?id=$mid'); return false;\" /><br />".$mnazwa." ".$mmodel." | SN: ".$msn." | NI: $mni</center>");
			
			}
	}

	if (($temp_ss_id>0) && (($msz<0) || ($msz==''))) {
		$result99 = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni,magazyn_uwagi,magazyn_uwagi_sa,magazyn_status,belongs_to FROM $dbname.serwis_magazyn WHERE magazyn_id='$temp_ss_id' LIMIT 1", $conn) or die($k_b);
		list($mid, $mnazwa, $mmodel, $msn, $mni, $muwagi, $muwagisa,$mstatus,$bt) = mysql_fetch_array($result99);
		
		ssheader("<center>Zgłoszenie powiązane z przekazaniem sprzętu serwisowego | <input type=button class=buttons value='szczegóły' onclick=\"newWindow_r(800,600,'p_serwis_szczegoly.php?id=$nr&komorka=".urlencode($_REQUEST[komorka])."'); return false;\" /><br />".$mnazwa." ".$mmodel." | SN: ".$msn." | NI: $mni</center>");
	}
	
	if ($temp_czy_pow_z_wp_parent==1) {
		$sql_wp="SELECT wp_sprzet_opis, wp_sprzet_sn, wp_sprzet_ni FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_zgl_id=$temp_parent_zgl) and (belongs_to=$es_filia) LIMIT 1";	
		$result_wp = mysql_query($sql_wp, $conn_hd) or die($k_b);

		list($wp_o,$wp_sn,$wp_ni)=mysql_fetch_array($result_wp);
		if ($wp_sn=='') $wp_sn='-';
		if ($wp_ni=='') $wp_ni='-';

		echo "<h5 class=h5yellow><b>Zgłoszenie powiązane z wymianą podzespołów (w zgłoszeniu nr $temp_parent_zgl) w:</b><br /><br /><b>$wp_o</b> | SN: <b>$wp_sn</b> | NI: <b>$wp_ni</b></h5>";	
	}
	
	// jeżeli zgłoszenie powiązane z wymianą podzespołów w komputerze klienta
	if ($temp_czy_pow_z_wp==1) {
		$sql_wp="SELECT wp_sprzet_opis, wp_sprzet_sn, wp_sprzet_ni FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_zgl_id=$_REQUEST[id]) and (belongs_to=$es_filia) LIMIT 1";	
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
		
		$sql88="SELECT zgl_czy_powiazane_z_wymiana_podzespolow FROM $dbname_hd.hd_zgloszenie WHERE (zgl_nr=$temp_rekl_nr) LIMIT 1";
		$result88 = mysql_query($sql88, $conn_hd) or die($k_b);
		list($temp_czy_pow_z_wp_parent)=mysql_fetch_array($result88);
		
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
		list($rekl_nr)=mysql_fetch_array(mysql_query("SELECT zgl_nr FROM hd_zgloszenie WHERE (zgl_nr_zgloszenia_reklamowanego=$temp2_zgl_nr) LIMIT 1"));
		echo "<h5 class=h5blue>Zgłoszenie numer <b>$_REQUEST[nr]</b> było reklamowane. Utworzono z niego zgłoszenie nr <b>".$rekl_nr."</b>";
		
		echo "&nbsp;<input class=buttons type=button onClick=\"newWindow_r(800,600,'hd_o_zgloszenia.php?action=obsluga&nr=$rekl_nr&id=$rekl_nr'); return false; \" value=' Przejdź do zgłoszenia nr ".$rekl_nr." ' />";
		
		echo "</h5>";
	}
	
	$sql_d = "SELECT zgl_szcz_id FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$_REQUEST[id])";
	//echo "$sql_d";
	$result_d = mysql_query($sql_d, $conn_hd) or die($k_b);
	$IloscKrokowZgloszenia = mysql_num_rows($result_d);

	// ilość kroków widocznych
	$sql="SELECT * FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$_REQUEST[id]) and (zgl_szcz_widoczne=1)"; // and (belongs_to=$es_filia)";	
	$result = mysql_query($sql, $conn_hd) or die($k_b);
	$ile_krokow_widocznych = mysql_num_rows($result);
	
	$sql="SELECT * FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$_REQUEST[id]) ORDER BY zgl_szcz_nr_kroku ASC"; // and (belongs_to=$es_filia)";	
	//echo $sql;
	
	$result = mysql_query($sql, $conn_hd) or die($k_b);
	
	//nowalinia();
	starttable();
	if ($WlaczMaile==1) {
		if ($DK_SS) {
			//th("20;c;Krok<br/>nr|;c;Czas<br />START/STOP<br /><sub>Ostrzeżenia</sub>|80;c;Data<br />rozpoczęcia kroku|80;c;Czas<br />wykonywania|80;c;Data<br />zakończenia kroku|70;c;Czas<br />START/STOP|;c;Status<br /><sub>Inf. dodatkowe</sub>|;l;Wykonane czynności<hr />Wyjazd (km) / Data wyjazdu / Rodzaj pojazdu / Czas trwania wyjazdu (min)|;c;Osoba wykonująca<br /><sub>dodatkowe osoby</sub>|;c;Email<br />wysłany|;c;Wyjazd (km)<br />Data|;c;Opcje",$es_prawa);
			th("20;c;Krok<br/>nr|80;c;Data<br />rozpoczęcia kroku|80;c;Czas<br />wykonywania|80;c;Data<br />zakończenia kroku|;c;Status<br /><sub>Inf. dodatkowe</sub>|;l;Wykonane czynności<hr />Wyjazd (km) / Data wyjazdu / Rodzaj pojazdu / Czas trwania wyjazdu (min)|;c;Osoba wykonująca<br /><sub>dodatkowe osoby</sub>|;c;Email<br />wysłany|;c;Wyjazd (km)<br />Data|;c;Opcje",$es_prawa);
		} else {
			//th("20;c;Krok<br/>nr|;c;Czas<br />START/STOP<br /><sub>Ostrzeżenia</sub>|80;c;Data<br />rozpoczęcia kroku|80;c;Czas<br />wykonywania|80;c;Data<br />zakończenia kroku|;c;Status<br /><sub>Inf. dodatkowe</sub>|;l;Wykonane czynności<hr />Wyjazd (km) / Data wyjazdu / Rodzaj pojazdu / Czas trwania wyjazdu (min)|;c;Osoba wykonująca<br /><sub>dodatkowe osoby</sub>|;c;Email<br />wysłany|;c;Wyjazd (km)<br />Data|;c;Opcje",$es_prawa);
			th("20;c;Krok<br/>nr|80;c;Data<br />rozpoczęcia kroku|80;c;Czas<br />wykonywania|80;c;Data<br />zakończenia kroku|;c;Status<br /><sub>Inf. dodatkowe</sub>|;l;Wykonane czynności<hr />Wyjazd (km) / Data wyjazdu / Rodzaj pojazdu / Czas trwania wyjazdu (min)|;c;Osoba wykonująca<br /><sub>dodatkowe osoby</sub>|;c;Email<br />wysłany|;c;Wyjazd (km)<br />Data|;c;Opcje",$es_prawa);
		}
	} else {
		if ($DK_SS) {
			//th("20;c;Krok<br/>nr|;c;Czas<br />START/STOP<br /><sub>Ostrzeżenia</sub>|80;c;Data<br />rozpoczęcia kroku|80;c;Czas<br />wykonywania|80;c;Data<br />zakończenia kroku|70;c;Czas<br />START/STOP|;c;Status<br /><sub>Inf. dodatkowe</sub>|;l;Wykonane czynności<hr />Wyjazd (km) / Data wyjazdu / Rodzaj pojazdu / Czas trwania wyjazdu (min)|;c;Osoba wykonująca<br /><sub>dodatkowe osoby</sub>|;c;Opcje",$es_prawa);
			th("20;c;Krok<br/>nr|80;c;Data<br />rozpoczęcia kroku|80;c;Czas<br />wykonywania|80;c;Data<br />zakończenia kroku|;c;Status<br /><sub>Inf. dodatkowe</sub>|;l;Wykonane czynności<hr />Wyjazd (km) / Data wyjazdu / Rodzaj pojazdu / Czas trwania wyjazdu (min)|;c;Osoba wykonująca<br /><sub>dodatkowe osoby</sub>|;c;Opcje",$es_prawa);
		} else {
			//th("20;c;Krok<br/>nr|;c;Czas<br />START/STOP<br /><sub>Ostrzeżenia</sub>|80;c;Data<br />rozpoczęcia kroku|80;c;Czas<br />wykonywania|80;c;Data<br />zakończenia kroku|;c;Status<br /><sub>Inf. dodatkowe</sub>|;l;Wykonane czynności<hr />Wyjazd (km) / Data wyjazdu / Rodzaj pojazdu / Czas trwania wyjazdu (min)|;c;Osoba wykonująca<br /><sub>dodatkowe osoby</sub>|;c;Opcje",$es_prawa);
			th("20;c;Krok<br/>nr|80;c;Data<br />rozpoczęcia kroku|80;c;Czas<br />wykonywania|80;c;Data<br />zakończenia kroku|;c;Status<br /><sub>Inf. dodatkowe</sub>|;l;Wykonane czynności<hr />Wyjazd (km) / Data wyjazdu / Rodzaj pojazdu / Czas trwania wyjazdu (min)|;c;Osoba wykonująca<br /><sub>dodatkowe osoby</sub>|;c;Opcje",$es_prawa);
		}
	}
	
	$j = 1;
	$i = 300;
	$newTime='';
	
	$ile_krokow = mysql_num_rows($result);
	$set_rozwiazany = 0;
	
	$i_act = 1;
	
	$pokaz_napis_przyjecie_na_stan = false;
	$pokaz_wyslanie_oferty = false;
	$pokaz_oferte = false;
	$pokaz_zamowienie = false;
	
	$pokaz_zamowienie_wyslane = false;
	$pokaz_oferta_wyslana = false;

	$last_czasSS = 'START';
	
	while ($newArray = mysql_fetch_array($result)) {

		$temp_id  			= $newArray['zgl_szcz_id'];
		$temp_nr			= $newArray['zgl_szcz_nr_kroku'];
		$temp_zgl_id  		= $newArray['zgl_szcz_zgl_id'];
		$temp_czasSS		= $newArray['zgl_szcz_czas_start_stop'];
		$temp_czasWyk		= $newArray['zgl_szcz_czas_wykonywania'];
		$temp_czasRozp		= $newArray['zgl_szcz_czas_rozpoczecia_kroku'];
		$temp_status		= $newArray['zgl_szcz_status'];
		$temp_czynnosci		= $newArray['zgl_szcz_wykonane_czynnosci'];
		$temp_osobawyk		= $newArray['zgl_szcz_osoba_wykonujaca_krok'];
		$temp_email_byl		= $newArray['zgl_szcz_email_wyslany'];	
		$temp_zam_wys		= $newArray['zgl_szcz_zamowienie_wyslane'];	
		$temp_oferta_wys	= $newArray['zgl_szcz_oferta_wyslana'];	
		
		$temp_data_wpisu 	= $newArray['zgl_data_wpisu'];	
		
		$temp_wyjazd_byl	= $newArray['zgl_szcz_byl_wyjazd'];	
		$temp_ilosc_km		= $newArray['zgl_szcz_il_km'];
		$temp_osobarej		= $newArray['zgl_szcz_osoba_rejestrujaca'];
		
		$temp_pt			= $newArray['zgl_szcz_przesuniety_termin_rozpoczecia'];
		$temp_zdiagnozowany = $newArray['zgl_szcz_zdiagnozowany'];
		$temp_akceptacja_kosztow = $newArray['zgl_szcz_akceptacja_kosztow'];

		$temp_nr_gdansk		= $newArray['zgl_szcz_gdansk_nr'];
		
		$temp_widoczne		= $newArray['zgl_szcz_widoczne'];
		$temp_osoba_rejestrujaca		= $newArray['zgl_szcz_osoba_rejestrujaca'];
		
		if ($temp_pt==1) {
			$temp_pd			= $newArray['zgl_szcz_przesuniecie_data'];
			$temp_po			= $newArray['zgl_szcz_przesuniecie_osoba'];
		}
		
		$temp_zgl_unique_number 	= $newArray['zgl_szcz_unikalny_numer'];
		$temp_dodatkowe_osoby	 	= $newArray['zgl_szcz_dodatkowe_osoby_wykonujace_krok'];
		$temp_blokada_edycji	 	= $newArray['zgl_szcz_blokada_edycji_kroku'];
		
		$temp_powiazana_naprawa_id = $newArray['zgl_szcz_powiazana_naprawa_id'];
		$temp_powiazana_naprawa_status = $newArray['zgl_szcze_powiazana_naprawa_status'];
		
		$temp_zglszcz_bt		= $newArray['belongs_to'];
		$temp_czy_rozwiazany_szcz	= $newArray['zgl_szcz_czy_rozwiazany_problem'];		
		
		tbl_tr_highlight($i);		
		
		//echo "<table width=790 border=0 cellspacing=0 cellpadding=0>";
		
			//	$accessLevels = array("9");
			//	if (array_search($es_prawa, $accessLevels)>-1) {

			//	}
		
		if ($temp_widoczne!=0) {
		
				echo "<td style='text-align:center; vertical-align:top;'>";
					echo "<a title=' ID kroku : $temp_id | Data zapisu do bazy: $temp_data_wpisu | Wpis dokonany przez: $temp_osobarej '>";
				//	td(";c;".$temp_nr."");
					
					if ($temp_zglszcz_bt!=$temp_bt) echo "<font color=blue>";
						echo $temp_nr;
					if ($temp_zglszcz_bt!=$temp_bt) echo "</font>";
					echo "</a>";
				echo "</td>";
				
				if ($temp_czasSS=='') $temp_czasSS='-';
				//if ($temp_czasWyk=='') $temp_czasWyk	='-';
				
				echo "<td style='text-align:center;display:none; vertical-align:top;'>";
				
				if ($DK_SS) {
					if ($last_czasSS=='START') echo "<b><font color=green>";
					if ($last_czasSS=='STOP') echo "<b><font color=red>";
					echo $last_czasSS;
					$last_czasSS = $temp_czasSS;
				} else {
					if ($temp_czasSS=='START') echo "<b><font color=green>";
					if ($temp_czasSS=='STOP') echo "<b><font color=red>";
					echo $temp_czasSS;
					//$last_czasSS = $temp_czasSS;
				}
				
				echo "</font></b>";
				
				
				if ($PokazIkonyWHPrzegladanie==1) {	
					if ($temp_czasSS=='START') {
						//echo "&nbsp;<input style='float:right' title=' Czas realizacji wg umowy jest liczony ' class=imgoption type=image src=img/czasSTART.gif />";
					}
					
					if ($temp_czasSS=='STOP') {
						//echo "&nbsp;<input title=' Czas realizacji wg umowy nie jest liczony ' class=imgoption type=image src=img/czasSTOP.gif />";
					}
				}	
			
					list($status_zgloszenia,$kategoria_zgloszenia,$CzasDoZakon, $CzasDoRozp,$osoba_pz,$temp_czy_rozwiazany,$temp_czy_w)=mysql_fetch_array(mysql_query("SELECT zgl_status, zgl_kategoria, zgl_data_zakonczenia, zgl_data_rozpoczecia,zgl_osoba_potwierdzajaca_zamkniecie_zgloszenia,zgl_czy_rozwiazany_problem,zgl_wymagany_wyjazd FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id=$_REQUEST[id]) LIMIT 1", $conn_hd));
				
					if (($temp_status==$status_zgloszenia) && ($IloscKrokowZgloszenia==$j)) {
					
							// okienka ostrzegawcze | POCZĄTEK
							$__zgl_nr 		= $_REQUEST[id];
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
							$__add_refresh	= 0;				// dodatkowe wymuszenie odświeżenia formatki
							$__add_br		= 0;
							$__tunon		= $turnon__hd_p_zgloszenia_kroki;
							
							if ($__tunon) include('warning_messages.php');
							// okienka ostrzegawcze | KONIEC
					
					}

				echo "</td>";

				echo "<td style='text-align:center; vertical-align:top; ";
				if (($temp_czasRozp<$newTime) && ($newTime!='')) echo "background-color:#FF7FD4; ' title='Data rozpoczęcia kroku nr $temp_nr jest wcześniejsza niż data zakończenia kroku nr ".($temp_nr-1)."";
				echo "'>";
					$part = explode(' ',$temp_czasRozp);

					// obsługa błędnie zapisanej daty lub godziny 1 kroku
					if ($temp_nr==1) {	
						if (($part[0]=='0000-00-00') || ($part[1]=='00:00:00')) {
							echo "<span style='background-color:red; color:white'>";
						}
					}
					
					if ($temp_zglszcz_bt!=$temp_bt) echo "<font color=blue>";
					echo $part[0]."<br /><b>".substr($part[1],0,5)."</b>";
					if ($temp_zglszcz_bt!=$temp_bt) echo "</font>";		
					
					if ($temp_nr==1) {	
						if (($part[0]=='0000-00-00') || ($part[1]=='00:00:00')) {
							echo "</span><br />błędna data rozpoczęcia pierwszego kroku<br />";
							
							// ustaw datę pierwszego kroku zgodną z datą rejestracji zgłoszenia
							if ($temp_data!='0000-00-00') {
								echo "<input title='Popraw błędnie zarejestrowaną datę pierwszego kroku. Ustawiona zostanie data zgodna z datą rejestracji zgłoszenia' type=button class=buttons value='Popraw' onClick=\"if (confirm('Czy ustawić datę i godzinę pierwszego kroku na: $temp_data $temp_godzina ?')) { newWindow(600,100,'hd_popraw_date_pierwszego_kroku.php?id=$temp_id&nowadata=$temp_data&nowagodzina=$temp_godzina'); return false; } \" />";
							} else {
							
								$temp_data1 = substr($DataRejZgl,0,10);
								$temp_godzina1 = substr($DataRejZgl,11,8);
								
								echo "<input title='Popraw błędnie zarejestrowaną datę pierwszego kroku. Ustawiona zostanie data zgodna z datą systemową rejestracji zgłoszenia: ".substr($DataRejZgl,0,16)."' type=button class=buttons value='Popraw' onClick=\"if (confirm('Czy ustawić datę i godzinę pierwszego kroku na datę systemową rejestracji pierwszego kroku: $temp_data1 ".substr($temp_godzina1,0,5)." ?')) {  newWindow(600,100,'hd_popraw_date_pierwszego_kroku.php?id=$temp_id&nowadata=$temp_data1&nowagodzina=$temp_godzina1'); return false; }\" />";
							}
							
						}
					}

					if ($temp_nr!=1) {
						if (($part[0]=='0000-00-00') || ($part[1]=='00:00:00') || ($part[0]=='1970-01-01') || ($part[0]=='1969-12-31')) {
								
								list($data_kroku)=mysql_fetch_array(mysql_query("SELECT zgl_data_wpisu FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_widoczne=1) and (zgl_szcz_zgl_id=$_REQUEST[nr]) and (zgl_szcz_nr_kroku=$temp_nr) LIMIT 1", $conn_hd));

								$temp_data1 = substr($data_kroku,0,10);
								$temp_godzina1 = substr($data_kroku,11,8);
								
								echo "<br /><br /><input title='Popraw błędnie zarejestrowaną datę $temp_nr kroku. Ustawiona zostanie data zgodna z datą systemową zapisu kroku do bazy: ".substr($data_kroku,0,16)."' type=button class=buttons value='Popraw' onClick=\"if (confirm('Czy ustawić datę i godzinę pierwszego kroku na datę systemową zapisu $temp_nr kroku do bazy: $temp_data1 ".substr($temp_godzina1,0,5)." ?')) {  newWindow(600,100,'hd_popraw_date_pierwszego_kroku.php?id=$temp_id&nowadata=$temp_data1&nowagodzina=$temp_godzina1'); return false; }\" />";
						}
					}
					
				echo "</td>";
				
				if ($temp_czasWyk!='0') { $temp_cw = $temp_czasWyk ." min."; } else { $temp_cw = '-'; }
				
				echo "<td style='text-align:center; vertical-align:top;'>";

				if ($temp_zglszcz_bt!=$temp_bt) echo "<font color=blue>";	
					echo "$temp_cw";					
				if ($temp_zglszcz_bt!=$temp_bt) echo "</font>";	
				
				echo "</td>";

				echo "<td style='text-align:center; vertical-align:top;'>";					
					$newTime = AddMinutesToDate($temp_cw,$temp_czasRozp);
					
					$part = explode(' ',$newTime);
					
					if ($temp_zglszcz_bt!=$temp_bt) echo "<font color=blue>";
					echo $part[0]."<br /><b>".substr($part[1],0,5)."</b>";
					if ($temp_zglszcz_bt!=$temp_bt) echo "</font>";
					
					//echo $part[0]."<br /><b>".substr($part[1],0,5)."</b>";
					
				echo "</td>";

				$NrZgl = $temp_zgl_id;
				
//				list($NrZgl,$przypisanedo)=mysql_fetch_array(mysql_query("SELECT zgl_nr,zgl_osoba_przypisana FROM $dbname_hd.hd_zgloszenie WHERE zgl_id=$temp_zgl_id LIMIT 1", $conn_hd));
				
				list($status)=mysql_fetch_array(mysql_query("SELECT hd_status_opis FROM $dbname_hd.hd_status WHERE hd_status_nr='$temp_status' LIMIT 1", $conn_hd));
				
				if (($temp_status==5) && ($temp_zam_wys==1)) {
					
					if ($pokaz_zamowienie_wyslane==false) {
						list($zam_data)=mysql_fetch_array(mysql_query("SELECT zam_data FROM $dbname_hd.hd_zgloszenie_zamowienia WHERE zam_zgl_szcz_id=$temp_id LIMIT 1", $conn_hd));
						$status=$status ."<hr /><i>(zamówienie wysłane";
						if ($zam_data!='') $status=$status." - $zam_data";
						$status=$status .")</i>";
					}
				}
				
				if (($temp_status==4) && ($temp_oferta_wys==1)) {
					if ($pokaz_oferta_wyslana==false) {
						list($oferta_data)=mysql_fetch_array(mysql_query("SELECT oferta_data FROM $dbname_hd.hd_zgloszenie_oferty WHERE oferta_zgl_szcz_id=$temp_id LIMIT 1", $conn_hd));
						$status=$status ."<hr /><i>(oferta wysłana";
						if ($oferta_data!='') $status=$status . " - $oferta_data";
						$status=$status . ")</i>";
					}
				}
		
		
			if ($DK_SS) {	
				if ($temp_status==9) $temp_czasSS = 'STOP';
				
					echo "<td style='display:none; text-align:center; vertical-align:top; "; 
					
					$dodaj_button = '';
					$opt_jest = '';
					$opt_powinno = '';
					switch ($temp_czasSS) {
						case 'START' : if (($temp_status=='3A') || ($temp_status=='4')) { echo "background-color:yellow;"; $dodaj_button = 'Zmień na STOP'; $opt_jest='START'; $opt_powinno='STOP';} break;
					
						case 'STOP' : if (($temp_status=='2') || ($temp_status=='3') || ($temp_status=='3B') || ($temp_status=='5') || ($temp_status=='6') || ($temp_status=='7') || ($temp_status=='8')) {  echo "background-color:yellow;"; $dodaj_button = 'Zmień na START'; $opt_jest='STOP'; $opt_powinno='START'; } break;
						
						case 'STOP' : if (($temp_status=='1') && ($temp_pt=='0')) { echo "background-color:yellow;"; $dodaj_button = 'Zmień na START'; $opt_jest='STOP'; $opt_powinno='START';} break;
						
						default : $dodaj_button = ''; break;
					}
					
					echo " ' ";
					if ($dodaj_button!='') echo " title=' Błędnie ustawiony znacznik START-STOP dla tego kroku ' ";
					echo " >";
			
					if ($temp_czasSS=='START') echo "<b><font color=green>";
					if ($temp_czasSS=='STOP') echo "<b><font color=red>";
					echo $temp_czasSS;
					if ($temp_czasSS=='START') echo "</font></b>";
					if ($temp_czasSS=='STOP') echo "<b></font>";

					// dla kierowników i dyrektora możliwość poprawienia błędnie ustawionego znacznika START-STOP
					if (($kierownik_nr==$es_nr) || ($is_dyrektor==1)) {
						if ($dodaj_button!='') {
							echo "<br /><br />";
							
							if ($dodaj_button=='Zmień na START') 
								echo "<input type=button class=buttons value='".$dodaj_button."' onclick=\"if (confirm('Czy napewno chcesz zmienić znacznik dla kroku nr ".$temp_nr." w zgłoszeniu nr ".$temp_zgl_id." na START ?')) { newWindow_r(800,600,'hd_o_zgloszenia_zmien_ss.php?kid=$temp_id&newSS=START&nr=$temp_zgl_id'); return false;} \" />";
								
							if ($dodaj_button=='Zmień na STOP') 
								echo "<input type=button class=buttons value='".$dodaj_button."' onclick=\"if (confirm('Czy napewno chcesz zmienić znacznik dla kroku nr ".$temp_nr." w zgłoszeniu nr ".$temp_zgl_id." na STOP ?')) { newWindow_r(800,600,'hd_o_zgloszenia_zmien_ss.php?kid=$temp_id&newSS=STOP&nr=$temp_zgl_id'); return false;}\" />";								
						}
					} else {
						if ($dodaj_button!='') {
							echo "<br /><br />";
							echo "<input type=button class=buttons value='Zgłoś kierownikowi' onclick=\"if (confirm('Czy napewno chcesz utworzyć notatkę dla kierownika z informacją o nieprawidołowości w kroku nr ".$temp_nr." w zgłoszeniu nr ".$temp_zgl_id." ?')) { newWindow_r(100,100,'hd_o_zgloszenia_zk.php?kid=$temp_id&jest=$opt_jest&powinno=$opt_powinno&nr=$temp_zgl_id&nrkroku=$temp_nr'); return false; }\" />";
						}
					}
					
				echo "</td>";
			}
			
				$nid = $temp_naprawa_id;
				if ($nid=='-1') $nid='';
				
				echo "<td style='text-align:center; vertical-align:top;'>";
					if ($temp_zglszcz_bt!=$temp_bt) echo "<font color=blue>";
					echo "$status";
					
					if ((($es_nr==$kierownik_nr) || ($currentuser==$temp_osobawyk)) && ($temp_oferta_wys==1)) {
						if ($pokaz_oferta_wyslana==false) {
							echo "<br /><font color=grey>oferta wysłana</font><a class=normalfont href=# onclick=\"newWindow_r(800,300,'hd_e_zgloszenia_pwo.php?id=$temp_id&znr=$_REQUEST[nr]'); return false;\" title=' Edytuj informacje o wysłanej ofercie '>&nbsp;<input class=imgoption type=image src=img/edit.gif></a>";
							$pokaz_oferta_wyslana = true;
						}
					}
					
					if ((($es_nr==$kierownik_nr) || ($currentuser==$temp_osobawyk)) && ($temp_zam_wys==1)) {
						if ($pokaz_zamowienie_wyslane==false) {
							echo "<br /><font color=grey>zamówienie wysłane</font><a class=normalfont href=# onclick=\"newWindow_r(800,300,'hd_e_zgloszenia_pwz.php?id=$temp_id&znr=$_REQUEST[nr]'); return false;\" title=' Edytuj informacje o wysłanym zamówieniu '>&nbsp;<input class=imgoption type=image src=img/edit.gif></a>";
							$pokaz_zamowienie_wyslane = true;
						}
					}
					
					if ($temp_zglszcz_bt!=$temp_bt) echo "</font>";	
					
					//if ($temp_status==4) {
						nowalinia();	
				
						if ($temp_zglszcz_bt!=$temp_bt) echo "<font color=blue>";
						
						if ($temp_status==4)
							if ($temp_oferta_wys==0) {
								if ($_REQUEST[viewonly]!=1)
									if ($pokaz_wyslanie_oferty==false) {
										echo "<br /><a title=' Potwierdź wysłanie oferty do klienta ' id=OfertaWyslanaButton class='normalfont buttons' href=#  onClick=\"newWindow(500,300,'hd_o_zgloszenia_pwo.php?id=$temp_id&znr=$_REQUEST[nr]&nid=$nid'); return false; \" >&nbsp;Potwierdź wysłanie oferty&nbsp;</a><br /><br />";
										$pokaz_wyslanie_oferty = true;
									}
							}
							
							if ($temp_oferta_wys==1) {
								if ($_REQUEST[viewonly]!=1)
									if ($pokaz_oferte==false) {
										echo "<br /><a href=# class='normalfont buttons' onClick=\"newWindow_r(600,200,'hd_o_zgloszenia_szcz_oferty.php?oid=$id&znr=$_REQUEST[nr]&zglszczid=$temp_id'); return false;\" >&nbsp;Pokaż ofertę&nbsp;</a><br />";
										$pokaz_oferte = true;
									}
							}
							
						if ($temp_zglszcz_bt!=$temp_bt) echo "</font>";		
						
					//}
					
					if (($temp_status=='3A') && ($temp_nr_awarii_wan!='')) {
						nowalinia();
						echo "Nr zgłoszenia w Orange: <b>".$temp_nr_awarii_wan."</b>";
					}
					
					
					if ($temp_zglszcz_bt!=$temp_bt) echo "<font color=blue>";
					
					if ($temp_status==5) 
						if ($temp_zam_wys==0) {
							if ($_REQUEST[viewonly]!=1)
								echo "<br /><a title=' Potwierdź wysłanie zamówienia do realizacji ' href=# id=ZamowienieWyslaneButton class='normalfont buttons'  onClick=\"newWindow(500,300,'hd_o_zgloszenia_pwz.php?id=$temp_id&znr=$_REQUEST[nr]&nid=$nid'); return false; \" >&nbsp;Potwierdź wysłanie zamówienia&nbsp;</a><br /><br />";
						} 
						
						if ($temp_zam_wys==1) {
							if ($_REQUEST[viewonly]!=1)
								if ($pokaz_zamowienie==false) {
									echo "<br /><a href=# class='normalfont buttons' onClick=\"newWindow_r(600,200,'hd_o_zgloszenia_szcz_zam.php?zid=$Zid&znr=$_REQUEST[nr]&zglszczid=$temp_id'); return false;\" >&nbsp;Pokaż zamówienie&nbsp;</a><br />";
									$pokaz_zamowienie = true;
								}
						}
						if ($temp_zglszcz_bt!=$temp_bt) echo "</font>";							
					//}	

					echo "<br />";
					
					// jeżeli krok powiązany z przyjęciem uszkodzonego sprzetu na stan
					list($zgl_np_naprawa_id)=mysql_fetch_array(mysql_query("SELECT hdnp_naprawa_id FROM $dbname_hd.hd_naprawy_powiazane WHERE hdnp_zgl_szcz_unique_nr='$temp_zgl_unique_number' LIMIT 1", $conn_hd));
						
					if (($zgl_np_naprawa_id>0) && ($pokaz_napis_przyjecie_na_stan==false)) {
						echo "<br /><sub>przyjęto uszkodzony sprzęt na stan</sub>";
						$pokaz_napis_przyjecie_na_stan = true;
					}
					
					// jeżeli krok powiązany z wymianą podzespołów					
					$r1a = mysql_query("SELECT wp_sprzet_opis, wp_sprzet_sn, wp_sprzet_ni, wp_sprzet_wykonane_czynnosci, wp_sprzedaz_fakt_szcz_id FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_zgl_szcz_unique_nr='$temp_zgl_unique_number') and (belongs_to='$temp_zglszcz_bt') and (wp_sprzet_active=1)", $conn_hd) or die($k_b);
					
					$czy_powiazany_z_wymiana = (mysql_num_rows($r1a)>0);
					
					if ($czy_powiazany_z_wymiana==true) {
						
						$sprzedanych = 0;
						$elzestawu = 0;
						$cnt = 0;
						
						$dolinku = '';
						$doprotokolu = 'Wymiana części: ';
						
						while (list($wp_opis, $wp_sn, $wp_ni, $wp_wc, $wp_fszczid)=mysql_fetch_array($r1a)) {
							list($poz_status, $poz_nazwa, $poz_sn)=mysql_fetch_array(mysql_query("SELECT pozycja_status, pozycja_nazwa, pozycja_sn FROM $dbname.serwis_faktura_szcz WHERE pozycja_id='$wp_fszczid' LIMIT 1",$conn));
							
							if ($poz_status==1) $sprzedanych++;
							if ($poz_status==5) $elzestawu++;
							$cnt++;
							$dolinku .= $wp_fszczid."|#|";
							
							$doprotokolu .= $poz_nazwa;
							if ($poz_sn!='') $doprotokolu .= " (SN: ".$poz_sn.")";
							$doprotokolu .= ", ";
							
						}
						
						$doprotokolu = substr($doprotokolu,0,-2);
						
						$nooptions = 0;
						//if ((($elzestawu<$cnt) && ($elzestawu!=0)) || (($sprzedanych<$cnt) && ($sprzedanych!=0))) $nooptions = 1;

						$r1a = mysql_query("SELECT wp_id,wp_sprzet_opis, wp_sprzet_sn, wp_sprzet_ni, wp_sprzet_wykonane_czynnosci, wp_sprzedaz_fakt_szcz_id,wp_wskazanie_sprzetu_z_magazynu, wp_typ_podzespolu, wp_sprzet_pocztowy, wp_sprzet_pocztowy_uwagi,wp_uwagi FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_zgl_szcz_unique_nr='$temp_zgl_unique_number') and (belongs_to='$temp_zglszcz_bt') and (wp_sprzet_active=1)", $conn_hd) or die($k_b);
						
						$byla_wymiana = (mysql_num_rows($r1a)>0);

						if (mysql_num_rows($r1a)>0) {
							
							$r1as = mysql_query("SELECT COUNT(wp_wskazanie_sprzetu_z_magazynu) FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_zgl_szcz_unique_nr='$temp_zgl_unique_number') and (belongs_to='$temp_zglszcz_bt') and (wp_sprzet_active=1) and (wp_wskazanie_sprzetu_z_magazynu=0)", $conn_hd) or die($k_b);
							list($temp_czy_sa_wg_podzespolow)=mysql_fetch_array($r1as);
														
							if ($temp_czy_sa_wg_podzespolow==0) {
								echo "<hr />";
								echo "<sub>wymiana podzespołu/ów";
								echo ": </sub><br />";
							} else {
								echo "<hr />";
								echo "<sub>wymiana wg typu";
								echo ": </sub><br />";
							
							}
							
						}
						
						$z_magazynu = 0;
						
						$result_upid = mysql_query("SELECT up_id,up_nazwa,up_pion_id,up_umowa_id,pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (up_active=1) and (belongs_to=$es_filia) and (CONCAT(serwis_piony.pion_nazwa,' ',serwis_komorki.up_nazwa)='$temp_komorka') LIMIT 1", $conn) or die($k_b);
							
						list($temp_upid,$temp_nazwa,$temp_pion_id,$temp_umowa_id,$temp_pionnazwa)=mysql_fetch_array($result_upid);
						
						while (list($wp_id1,$wp_opis, $wp_sn, $wp_ni, $wp_wc, $wp_fszczid, $wp_sprz_z_mag, $wp_typ_podz,$wp_sp,$wp_sp_uwagi,$wp_uwagi)=mysql_fetch_array($r1a)) {
						
							if ($wp_sprz_z_mag=='1') $z_magazynu = 1;
												
								if ((($wp_fszczid>0) && ($wp_sprz_z_mag=='1')) || (($wp_sp=='1') && ($wp_fszczid=='0'))) {
									
									list($sprzedany,$pozfsz_nazwa,$pozfsz_sn)=mysql_fetch_array(mysql_query("SELECT pozycja_status, pozycja_nazwa, pozycja_sn FROM $dbname.serwis_faktura_szcz WHERE pozycja_id=$wp_fszczid LIMIT 1", $conn_hd));
									//echo "SELECT pozycja_status, pozycja_nazwa, pozycja_sn FROM $dbname.serwis_faktura_szcz WHERE pozycja_id=$wp_fszczid LIMIT 1";
									
									if ($wp_sp!='1') {
										if ($wp_opis!='') {
											echo $pozfsz_nazwa;
											if ($pozfsz_sn!='') echo " (SN: ".$pozfsz_sn.")";
										}
									} else {
										if ($wp_typ_podz!='') {
											echo $wp_typ_podz;
										}
									}

									//echo ">>>".$sprzedany."";
											
									list($poz_nazwa, $poz_sn, $poz_f_numer, $poz_rs)=mysql_fetch_array(mysql_query("SELECT pozycja_nazwa, pozycja_sn, pozycja_nr_faktury, pozycja_rodzaj_sprzedazy FROM $dbname.serwis_faktura_szcz WHERE pozycja_id='$wp_fszczid' LIMIT 1",$conn));
											
									if ($poz_rs=='') { $acrs = 1; } else { $acrs = 0; }
											
									$wp_sprzedaz_data = substr($temp_czasRozp,0,10);
									$nazwa_urzadzenia = $wp_opis;
									$sn_urzadzenia = $wp_sn;
									$ni_urzadzenia = $wp_ni;
									
									if ($wp_sp=='1') {
										echo " <font color=red>| pocztowy</font>"; 
										
										echo "<a title='Ustaw jako podzespół nowy'><input class=imgoption type=image src=img/none_poczta.gif onclick=\"newWindow_r($dialog_window_x,100,'hd_g_wp_ustaw_typ.php?wpid=$wp_id1&pocztowy=0&typ=".urlencode($wp_typ_podz)."'); return false;\"></a>";	
										
									}
									
									if ($sprzedany=='1') {
										echo " | <font color=green>Wykonano sprzedaż</font>"; 
										
										if ($allow_sell==1) {
	/*
											list($poz_nazwa, $poz_sn, $poz_f_numer, $poz_rs)=mysql_fetch_array(mysql_query("SELECT pozycja_nazwa, pozycja_sn, pozycja_nr_faktury, pozycja_rodzaj_sprzedazy FROM $dbname.serwis_faktura_szcz WHERE pozycja_id='$wp_fszczid' LIMIT 1",$conn));
										
											echo " | <a title='Anuluj sprzedaż: $poz_nazwa (SN: $poz_sn)'><input class=imgoption type=image src='img/money_delete_hd.gif' onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_towary_sprzedaz.php?id=$wp_fszczid'); return false;\"></a>";
	*/									}
										
									}
									if ($sprzedany=='9') echo "<br /><font color=blue><b>Wykonano sprzedaż i rozliczono</b></font>"; 

									if ($sprzedany=='5') {
										$sql_nr_zestawu = "SELECT zestawpozycja_zestaw_id, zestawpozycja_zestaw_id FROM $dbname.serwis_zestaw_szcz WHERE zestawpozycja_fszcz_id=$wp_fszczid LIMIT 1";
										list($nr_zestawu,$zestaw_id1)=mysql_fetch_array(mysql_query($sql_nr_zestawu,$conn));

										$sql_nazwa_zestawu = "SELECT zestaw_opis FROM $dbname.serwis_zestawy WHERE zestaw_id=$zestaw_id1 LIMIT 1";
										list($nazwa_zestawu)=mysql_fetch_array(mysql_query($sql_nazwa_zestawu,$conn));
										
	/*									
										echo "&nbsp;<a title='Pokaż elementy zestawu: $nazwa_zestawu";
										//if ($fsz_sn!='') $list_pozycje .= "o numerze seryjnym : $fsz_sn";
										
										echo "'><input class=imgoption type=image src='img/basket.gif' onclick=\"newWindow_r(800,600,'p_zestaw_pozycje.php?id=$nr_zestawu&sold=0&showall=1&paget=1&allowchanges=0'); return false;\"></a>";
		*/								
		
										if ($nooptions==0) {
											if ($allow_sell==1) {
	//											if ($cnt>1) {
	//												echo "<a title=' Sprzedaj zestaw: $nazwa_zestawu '><input class=imgoption type=image src=img/sell_zestaw.gif  onclick=\"newWindow_r(700,595,'g_zestaw_obrot.php?id=$nr_zestawu&allow_change_rs=0')\"></a>";
	//											}
											}
										} else echo "<img class=imgoption src=img/sell_zestaw.gif title='Aby dokonać sprzedaży wybraych podzespołów, należy sprzedać je pojedynczo lub ze wszystkich utworzyć jeden zestaw' width=16 width=16>";
									}
									
									
									if ($sprzedany=='0') {
										//echo $temp_komorka;
										if ($allow_sell==1) {
											if ($nooptions==0) {
												if ($cnt==1) {
												
	/*
													echo "<a title=' Sprzedaj towar : $poz_nazwa o numerze seryjnym : $poz_sn ' href=# >";
													echo "<input class=imgoption type=image src=img/sell_hd.gif onclick=\"newWindow_r(700,595,'z_towary_obrot.php?id=$wp_fszczid&f=$poz_f_numer&obzp=1&trodzaj=".urlencode($poz_rs)."&allow_change_rs=$acrs&new_upid=$temp_upid&tdata=$wp_sprzedaz_data&trodzaj=Towar&nazwa_urzadzenia=".urlencode($nazwa_urzadzenia)."&sn_urzadzenia=".urlencode($sn_urzadzenia)."&ni_urzadzenia=".urlencode($ni_urzadzenia)."&ewid_id=1&quiet=1&readonly=1'); return false;\">";
													echo "</a>";
	*/												echo " | <a title='Usuń wymianę podzespołu: $poz_nazwa (SN: $poz_sn) z tego zgłoszenia'><input class=imgoption type=image src='img/delete.gif' onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_hd_wp.php?id=$wp_fszczid&wp_opis=".urlencode($poz_nazwa)."&wp_sn=".urlencode($poz_sn)."&nr=".$_REQUEST[nr]."'); return false;\"></a>";
	
													$onepos = $wp_fszczid;
												} else {
													echo " | <a title='Usuń wymianę podzespołu: $poz_nazwa (SN: $poz_sn) z tego zgłoszenia'><input class=imgoption type=image src='img/delete.gif' onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_hd_wp.php?id=$wp_fszczid&wp_opis=".urlencode($poz_nazwa)."&wp_sn=".urlencode($poz_sn)."&nr=".$_REQUEST[nr]."'); return false;\"></a>";
												}
											} else echo "&nbsp;<img title='Aby dokonać sprzedaży wybraych podzespołów, należy sprzedać je pojedynczo lub ze wszystkich utworzyć jeden zestaw' class=imgoption type=image src=img/sell_hd.gif>";
										} else {
										
										}
										
									}
								} else {
								
									$doprotokolu = 'Wymieniono części: ';
									
									$r1a = mysql_query("SELECT wp_id, wp_sprzet_opis, wp_sprzet_sn, wp_sprzet_ni, wp_sprzet_wykonane_czynnosci, wp_wskazanie_sprzetu_z_magazynu, wp_typ_podzespolu, wp_sprzet_pocztowy, wp_sprzet_pocztowy_uwagi,wp_uwagi FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_zgl_szcz_unique_nr='$temp_zgl_unique_number') and (belongs_to='$temp_zglszcz_bt') and (wp_sprzet_active=1)", $conn_hd) or die($k_b);
									$cnt=0;
									while (list($wp_id2, $wp_opis, $wp_sn, $wp_ni, $wp_wc, $wp_z_mag, $wp_typ_pod,$wp_sp,$wp_sp_uwagi,$wp_uwagi)=mysql_fetch_array($r1a)) {
										$doprotokolu .= $wp_typ_pod.", ";
										$cnt++;
									
										echo "<b>$wp_typ_pod</b>";
										
										if ($wp_sp==1) {
											echo " <font color=red>| pocztowy</font>";		
											
											echo "<a title='Ustaw jako podzespół nowy'><input class=imgoption type=image src=img/none_poczta.gif onclick=\"newWindow_r($dialog_window_x,100,'hd_g_wp_ustaw_typ.php?wpid=$wp_id&pocztowy=0&typ=".urlencode($wp_typ)."'); return false;\"></a>";	
			
										}
										
										if ($wp_z_mag==0) {
											echo " | <a title='Usuń wybrany typ podzespołu z wymiany z tego zgłoszenia ($wp_typ_pod)'><input class=imgoption type=image src='img/delete.gif' onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_hd_wp.php?id=$wp_id2&wp_opis=".urlencode($wp_typ_pod)."&wp_sn=&nr=".$_REQUEST[nr]."&typ=1'); return false;\"></a>";
										}
										
										echo "<br />";
										
									$wp_sprzedaz_data = substr($temp_czasRozp,0,10);
									$nazwa_urzadzenia = $wp_opis;
									$sn_urzadzenia = $wp_sn;
									$ni_urzadzenia = $wp_ni;
									
									}
									
									$doprotokolu = substr($doprotokolu,0,-2);

									echo "<br /><input type=button class=buttons style='color:red;font-weight:bold;' value='Utwórz protokół'  onclick=\"newWindow_r(700,595,'utworz_protokol.php?source=only-protokol&state=empty&c_7=on&new_upid=$temp_upid&readonly=1&choosefromewid=0&nazwa_urzadzenia=".urlencode($nazwa_urzadzenia)."&sn_urzadzenia=".urlencode($sn_urzadzenia)."&ni_urzadzenia=".urlencode($ni_urzadzenia)."&quiet=1&ewid_id=1&wykonane_czynnosci=".urlencode($doprotokolu)."&getfromparentwindow=0&hd_zgl_nr=$temp_zgl_id')\"></a>";	
								
								}
								
								echo "<br />";
							
						}
					
					//echo $sprzedanych;
					
					if ($sprzedany=='0') {
						if ($allow_sell==1) {
							if ($nooptions==0) {
								if ($cnt==1) {
									echo "<br /><input type=button class=buttons style='color:green' value='Sprzedaj towar' onclick=\"newWindow_r(700,595,'z_towary_obrot.php?id=$onepos&f=$poz_f_numer&obzp=1&trodzaj=".urlencode($poz_rs)."&allow_change_rs=$acrs&new_upid=$temp_upid&tdata=$wp_sprzedaz_data&trodzaj=Towar&nazwa_urzadzenia=".urlencode($nazwa_urzadzenia)."&sn_urzadzenia=".urlencode($sn_urzadzenia)."&ni_urzadzenia=".urlencode($ni_urzadzenia)."&ewid_id=1&quiet=1&readonly=1&hd_zgl_nr=$temp_zgl_id'); return false;\">";
									
								}
							} else {
								//echo "&nbsp;<img title='Aby dokonać sprzedaży wybraych podzespołów, należy sprzedać je pojedynczo lub ze wszystkich utworzyć jeden zestaw' class=imgoption type=image src=img/sell_hd.gif>";
								}
						}
					}
									
					if (($elzestawu==0) && ($byla_wymiana==true) && ($z_magazynu=='1') && ($sprzedanych==0)) {
						
						if (($allow_sell==1) && ($cnt>1)) echo "<br /><input type=button class=buttons value='Utwórz zestaw' onClick=\"newWindow(800,600,'hd_g_zestaw_z_wymiany_podzespolow.php?pozcnt=".$cnt."&pozfsz=".urlencode($dolinku)."&unique=$temp_zgl_unique_number&hd_zgl_nr=$temp_zgl_id'); return false;\">";
						
						if ($sprzedanych==0) {
							echo "<br /><input type=button class=buttons style='color:red;font-weight:bold;' value='Utwórz protokół' onclick=\"newWindow_r(700,595,'utworz_protokol.php?source=only-protokol&state=empty&c_7=on&new_upid=$temp_upid&readonly=1&wykonane_czynnosci=".urlencode($wc)."&choosefromewid=0&nazwa_urzadzenia=".urlencode($nazwa_urzadzenia)."&sn_urzadzenia=".urlencode($sn_urzadzenia)."&ni_urzadzenia=".urlencode($ni_urzadzenia)."&quiet=1&ewid_id=1&wykonane_czynnosci=".urlencode($doprotokolu)."&getfromparentwindow=0&hd_zgl_nr=$temp_zgl_id');return false; \"></a>";	
						}
					}
					
					if (($elzestawu==$cnt) && ($byla_wymiana==true) && ($z_magazynu=='1')) {
						if ($allow_sell==1) 
							echo "<br />";
							// **************
							
							echo "<input type=button class=buttons style='color:reg;font-weight:normal;' value='Anuluj wymianę elementów zestawu' onClick=\"if (confirm('Czy napewno chcesz anulować wymianę podzespołów z zestawu dla kroku $temp_nr zgłoszenia nr $temp_zgl_id ?')) newWindow(10,10,'z_wymiana_podzespolu_usun.php?id=".$_REQUEST[id]."&unique=$temp_zgl_unique_number');\">";

							echo "<br /><input type=button class=buttons style='color:green;font-weight:normal;' value='Sprzedaj zestaw' onclick=\"newWindow_r(700,595,'g_zestaw_obrot.php?id=$nr_zestawu&allow_change_rs=0&new_upid=$temp_upid&readonly=1&nazwa_urzadzenia=".urlencode($nazwa_urzadzenia)."&sn_urzadzenia=".urlencode($sn_urzadzenia)."&ni_urzadzenia=".urlencode($ni_urzadzenia)."&ewid_id=1&quiet=1&hd_zgl_nr=$temp_zgl_id'); return false;\">";

						if ($sprzedanych==0) 
							echo "<br /><input type=button class=buttons style='color:red;font-weight:bold;' value='Utwórz protokół' onclick=\"newWindow_r(700,595,'utworz_protokol.php?source=only-protokol&state=empty&c_7=on&new_upid=$temp_upid&readonly=1&wykonane_czynnosci=".urlencode($wc)."&choosefromewid=0&nazwa_urzadzenia=".urlencode($nazwa_urzadzenia)."&sn_urzadzenia=".urlencode($sn_urzadzenia)."&ni_urzadzenia=".urlencode($ni_urzadzenia)."&quiet=1&ewid_id=1&wykonane_czynnosci=".urlencode($doprotokolu)."&getfromparentwindow=0&hd_zgl_nr=$temp_zgl_id');return false; \"></a>";	
					}					
					
					if ((($elzestawu<$cnt) && ($elzestawu!=0)) || (($sprzedanych<$cnt) && ($sprzedanych!=0))) {
						//errorheader('Aby dokonać sprzedaży wybraych podzespołów, należy sprzedać je pojedynczo lub ze wszystkich utworzyć jeden zestaw');
					}	
				}
				
				if ($temp_zglszcz_bt!=$temp_bt) echo "<font color=blue>";		
					if (($temp_status==9) && ($osoba_pz!='')) {
						//list($osoba_pz)=mysql_fetch_array(mysql_query("SELECT zgl_osoba_potwierdzajaca_zamkniecie_zgloszenia FROM $dbname_hd.hd_zgloszenie WHERE zgl_nr=$temp_id LIMIT 1", $conn_hd));
							
						echo "<hr />osoba potwierdzająca:<br /><b>$osoba_pz</B>";
						
					}

					/*if ($temp_zdiagnozowany!='9') {
						//list($osoba_pz)=mysql_fetch_array(mysql_query("SELECT zgl_osoba_potwierdzajaca_zamkniecie_zgloszenia FROM $dbname_hd.hd_zgloszenie WHERE zgl_nr=$temp_id LIMIT 1", $conn_hd));
						echo "<hr />zdiagnozowany: <b>";
						if ($temp_zdiagnozowany==1) echo "TAK";
						if ($temp_zdiagnozowany==0) echo "NIE";
						echo "</b>";
					}
					*/
					
					
					/*
					if ($temp_akceptacja_kosztow!='9') {
						//list($osoba_pz)=mysql_fetch_array(mysql_query("SELECT zgl_osoba_potwierdzajaca_zamkniecie_zgloszenia FROM $dbname_hd.hd_zgloszenie WHERE zgl_nr=$temp_id LIMIT 1", $conn_hd));
						echo "<hr />akceptacja kosztów: <b>";
						if ($temp_akceptacja_kosztow==1) echo "TAK";
						if ($temp_akceptacja_kosztow==0) echo "NIE";
						echo "</b>";
					}
					*/
					
					if (($temp_nr_gdansk!='') && ($temp_status=='3A')) {
						echo "<hr />Nr incydentu w Gdańsku: <b>";
						
						if (($_GET[ss]==3) && ($_GET[searcheserwisnr]!='')) {
							echo highlight($temp_nr_gdansk,$_GET[searcheserwisnr]);
						} else {
							echo $temp_nr_gdansk;
						}
				
						echo "</b>";
					}
					
				if (($temp_kategoria=='2') || ($temp_kategoria=='6')) 
					if ($temp_czy_rozwiazany_szcz=='1') {
						if ($set_rozwiazany!=1) echo "<br /><font color=green>Problem rozwiązany: <b>TAK</b></font>";
						$set_rozwiazany = 1;
					}
					
				if ($temp_zglszcz_bt!=$temp_bt) echo "</font>";		
				echo "</td>";
			
					
				if ($temp_czynnosci=='') $temp_czynnosci='-';				
				echo "<td style='text-align:left; vertical-align:top;'>";
				
				//$ttt = $dane[zgl_szcz_wykonane_czynnosci];
				$temp_czynnosci = str_replace('rejestracja zgłoszenia<br /><br />','rejestracja zgłoszenia: ',$temp_czynnosci);
			
			if ($temp_zglszcz_bt!=$temp_bt) echo "<font color=blue>";

				if (($_GET[ss]==7) && ($_GET[st_wc]!='')) {
					echo highlight(nl2br($temp_czynnosci),$_GET[st_wc]);
				} else {
					echo nl2br($temp_czynnosci);
				}

				//if ($temp_status=='2')
						//if ($temp_osobarej!=$temp_osobawyk) echo " <i>(przez: $temp_osobarej)</i>";
					
					
		//		if (($temp_status==3) && ($temp_osobarej!=$temp_osobawyk)) echo " <i>(przez $temp_osobarej)</i>";
				
				if ($temp_pt==1) {
					if ($temp_kategoria==2) {
						$dodatkowy_opis = "<br /><br /><font color=red><b>Ustalono przesunięcie terminu rozpoczęcia realizacji zgłoszenia</b><br />Ustalona data: <b>".substr($temp_pd,0,16)."</b><br />Osoba ze strony Poczy potwierdzająca przesunięcie: <b>$temp_po</b></font>";
					}
					if ($temp_kategoria==6) {
						$dodatkowy_opis = "<br /><br /><font color=red><b>Ustalono przesunięcie terminu rozpoczęcia realizacji zgłoszenia</b>";
						//<br />Ustalona data: <b>".substr($temp_pd,0,16)."</b>
						$dodatkowy_opis .= "<br />Ustalona data: <b>".substr($temp_pd,0,16)."</b><br />Osoba ze strony Poczy potwierdzająca przesunięcie: <b>$temp_po</b></font>";
					}
					echo nl2br($dodatkowy_opis);
				}
				
// wyjazd
				$czy_zgloszenie_seryjne = 0;
				
				//list($pole_temp)=mysql_fetch_array(mysql_query("SELECT zgl_szcz_unikalny_numer FROM $dbname_hd.hd_zgloszenie_szcz WHERE zgl_szcz_zgl_id='$_REQUEST[id]'", $conn_hd));
				
				$result44 = mysql_query("SELECT zgl_szcz_id FROM $dbname_hd.hd_zgloszenie_szcz WHERE zgl_szcz_unikalny_numer='$temp_zgl_unique_number'", $conn_hd) or die($k_b);
				$czy_zgloszenie_seryjne = (mysql_num_rows($result44)>1);
				
				//echo ">>>>".$czy_zgloszenie_seryjne;
			
				$czy_zgloszenie_seryjne = 0;
				
				if ($temp_wyjazd_byl!='0') { 
					echo "<hr />";
					list($pkm)=mysql_fetch_array(mysql_query("SELECT sum(wyjazd_km) FROM $dbname_hd.hd_zgloszenie_wyjazd WHERE wyjazd_zgl_szcz_id='$temp_zgl_unique_number'", $conn_hd));
					
					list($trasa,$data_wyj,$rodzaj_pojazdu,$wyj_czas)=mysql_fetch_array(mysql_query("SELECT wyjazd_trasa,wyjazd_data,wyjazd_rodzaj_pojazdu,wyjazd_czas_trwania FROM $dbname_hd.hd_zgloszenie_wyjazd WHERE (wyjazd_zgl_szcz_id='$temp_zgl_unique_number') and (wyjazd_widoczny=1) ORDER BY wyjazd_id DESC LIMIT 1", $conn_hd));
					
					if ($czy_zgloszenie_seryjne) {
						
					//	echo "<td style='text-align:center;'>";
					
						echo "<a title='Trasa wyjazdu: $trasa' class=normalfont href=# onClick=\"newWindow_r(600,400,'hd_p_seryjne.php?unique=$temp_zgl_unique_number'); return false; \">";
						if ($temp_zglszcz_bt!=$temp_bt) echo "<font color=blue>";					
						echo "$temp_ilosc_km km<br />wyjazd złożony";
						if ($temp_zglszcz_bt!=$temp_bt) echo "</font>";
						echo "</a><br />";
						
						//echo "</td>";
					
					} else {
						$temp_pkm = $pkm . " km";
						
						//echo "<td style='text-align:center;'>";
						echo "<a title=' Trasa wyjazdu: $trasa ' class=normalfont onClick=\"alert('Trasa wyjazdu: $trasa'); return false;\">";
						
						if ($rodzaj_pojazdu=='P') {
							if ($temp_zglszcz_bt!=$temp_bt) echo "<font color=blue>";
							echo "<b>$temp_ilosc_km km / $data_wyj</b>";
							if ($temp_zglszcz_bt!=$temp_bt) echo "</font>";	
						} else {
							if ($temp_zglszcz_bt!=$temp_bt) echo "<font color=blue>";
							echo "<b>- / $data_wyj</b>";
							if ($temp_zglszcz_bt!=$temp_bt) echo "</font>";							
						}
						echo "</a>";
						if ($temp_zglszcz_bt!=$temp_bt) echo "<font color=blue>";
						//echo " / $data_wyj";						
						
						echo "&nbsp;/&nbsp;";
						if ($rodzaj_pojazdu=='P') echo "<b>sam. prywatny</b>";
						if ($rodzaj_pojazdu=='S') echo "sam. służbowy";
				
						echo "<b>/ $wyj_czas min.</b>";
						
						if ($temp_zglszcz_bt!=$temp_bt) echo "</font>";	
					//	echo "</td>";
						
						//td(";c;".$temp_pkm."");
					}
				
				} else { 
					//echo "<td style='text-align:center;'>";
					$temp_pkm='-'; 
					if ($temp_zglszcz_bt!=$temp_bt) echo "<font color=blue>";
					echo "<hr />$temp_pkm";
					//echo "</td>";
					if ($temp_zglszcz_bt!=$temp_bt) echo "</font>";	
					}
					
					echo "</td>";

				if ($temp_osobawyk=='') $temp_osobawyk='-';
				
				echo "<td style='text-align:center; vertical-align:top;'>";
				if ($temp_dodatkowe_osoby!='') nowalinia();
					echo "<b><a title=' Osoba główna wykonująca krok '>";
				
				if ($temp_zglszcz_bt!=$temp_bt) echo "<font color=blue>";	
					echo "$temp_osobawyk";
				if ($temp_zglszcz_bt!=$temp_bt) echo "</font>";	
					echo "</a></b><br />";
					//echo ">".$temp_dodatkowe_osoby."<";
					if ($temp_dodatkowe_osoby!='') {
						
						//echo "Dodatko";;
						$jeden_dodatkowy = explode(", ", $temp_dodatkowe_osoby);
						$ile_dodatkowych = substr_count($temp_dodatkowe_osoby,', ')-1;
						//echo "<a title=' Dodatkowe osoby uczestniczące w realizacji kroku '>";
						
						//echo "<table>";
						
							echo "<br /><table style='background-color:transparent; border-width:0px;' width=auto class=center>";
							echo "<tr><th colspan=2 style='background-color:transparent; border-width:0px;' class=center>Osoby dodatkowe:</th></tr>";
							for ($xx=0; $xx<=$ile_dodatkowych; $xx++) {
								echo "<tr style='background-color:transparent'><td style='background-color:transparent' class=center>";
								if ($temp_zglszcz_bt!=$temp_bt) echo "<font color=blue>";	
								echo "$jeden_dodatkowy[$xx]";
								if ($temp_zglszcz_bt!=$temp_bt) echo "</font>";	
								echo "</td>";
								echo "<td class=center>";
									if ((($currentuser==$temp_osobawyk) || ($es_nr==$KierownikId) || ($is_dyrektor==1))) {
										echo "<a href=# title='Usuń wybraną osobę z wykonywania kroku nr $temp_nr' onclick=\"if (confirm('Czy na pewno chcesz usunąć osobę: $jeden_dodatkowy[$xx] z realizacji kroku nr $temp_nr zgłoszenia nr $_REQUEST[id] ?')) newWindow_r(400,200,'hd_u_osobe_z_kroku.php?zgl_krok_id=$temp_id&osobadousuniecia=".urlencode($jeden_dodatkowy[$xx])."&juzsa=".urlencode($temp_dodatkowe_osoby)."'); return false;\"><input class=imgoption type=image src=img/1osoba_delete.gif></a>";
									}
								echo "<br />";
								echo "</td></tr>";
							}
							endtable();
							
						//echo "</a>";
						//nowalinia();
					} //else "<br />";
					
					//echo "&nbsp;<br />";
					
				//echo "</table>";
				
				if ((($currentuser==$temp_osobawyk) || ($es_nr==$KierownikId) || ($is_dyrektor==1))) {
					echo "<hr /><input type=button class=buttons style='padding:0px; margin:2px; ' value='Dodaj osobę' onclick=\"newWindow_r(600,400,'hd_d_osobe_do_kroku.php?zgl_krok_id=$temp_id&zgl_nr=$_REQUEST[id]&kroknr=$temp_nr&pomin=".urlencode($temp_osobawyk)."&juzsa=".urlencode($temp_dodatkowe_osoby)."&filiaid=".$es_filia."'); return false;\">";
				}
				
				echo "</td>";
			if ($WlaczMaile==1) {	
				echo "<td style='text-align:center;'>";
					if ($temp_email_byl==1) {
						echo "<a class=normalfont title=' email został wysłany ' href=# onClick=\"return false;\" >";
						if ($PokazIkonyWHPrzegladanie==1) { echo "<input class=imgoption type=image src=img/email_send.gif />"; } else { echo "TAK"; }
						echo "</a>";	
					} else {
						echo "<a class=normalfont title=' email nie został wysłany ' href=# onClick=\"alert('ponowna wysyłka maila - w trakcie programowania');  return false; \" >";
						if ($PokazIkonyWHPrzegladanie==1) { echo "<input class=imgoption type=image src=img/email_notsend.gif />"; } else { echo "NIE"; }
						echo "</a>";	
					}
					
				echo "</td>";
			}
			
		echo "<td style='text-align:center; vertical-align:top;'>";
		
		$dodanyDDBW = false;
		
			if (($temp_zglszcz_bt==$temp_bt) && ($_REQUEST[viewonly]!=1)) {
				$KierownikId = $kierownik_nr;
				//$czy_zmiana_czasu_zakonczenia = strpos($temp_czynnosci,'Zmiana terminu zakończenia:');
				
				$pozwol_edytowac=0;
				$data_wpisu = substr($temp_data_wpisu,0,10);
				$data_wstecz = SubstractWorkingDays(1,Date('Y-m-d'));
				
				if ($data_wpisu==Date('Y-m-d')) $pozwol_edytowac=1;
				if ($data_wpisu==$data_wstecz) $pozwol_edytowac=1;
				
				if (($i_act!=$ile_krokow_widocznych) && ($pozwol_edytowac==1)) $pozwol_edytowac = 0;
				
				//echo "data bieżąca: ".Date('Y-m-d')." | data wpisu: ".$data_wpisu." | data wstecz: ".$data_wstecz."";
				//echo "datawstecz=".$data_wstecz." | DW: ".$data_wpisu." | >".SubstractWorkingDays(1,$data_wpisu)."<  | pozwol: ".$pozwol_edytowac."";
				
				if (($es_nr==$KierownikId) || ($is_dyrektor==1)) $pozwol_edytowac=1;
				
				if ((($currentuser==$temp_osobawyk) || ($es_nr==$KierownikId) || ($is_dyrektor==1)) && ($pozwol_edytowac==1)) {
					// popup menu z opcjami
					
					if ($temp_blokada_edycji==0) {
							echo "<a href=# onclick=\"newWindow_r(500,200,'hd_e_zgloszenie_krok.php?element=czas&id=$temp_id&nr=$temp_nr&zglnr=$NrZgl&zglid=$temp_zgl_id&zgl_krok_id=$temp_id'); return false;\" title=' Edytuj czas wykonywania kroku "; 
							if ($es_nr==$KierownikId) echo "oraz datę wykonania kroku (tylko dla kierowników)";
							echo "'><input class=imgoption type=image src=img/clock_play.png></a>";
							echo "<a href=# onclick=\"newWindow_r(800,300,'hd_e_zgloszenie_krok.php?element=czynnosc&id=$temp_id&nr=$temp_nr&zglnr=$NrZgl&zglid=$temp_zgl_id&zgl_krok_id=$temp_id'); return false;\" title=' Edytuj wykonane czynności '><input class=imgoption type=image src=img/cog_play.png></a>";

							$result44 = mysql_query("SELECT wyjazd_rodzaj_pojazdu FROM $dbname_hd.hd_zgloszenie_wyjazd WHERE wyjazd_zgl_szcz_id='$temp_zgl_unique_number'", $conn_hd) or die($k_b);
							$typ_pojazdu1 = mysql_fetch_array($result44);
							$czy_wyjazd_prywatnym = ($typ_pojazdu1[wyjazd_rodzaj_pojazdu]=='P');
							$czy_wyjazd_sluzbowym = ($typ_pojazdu1[wyjazd_rodzaj_pojazdu]=='S');
				
							if (($temp_wyjazd_byl!='0') && ($czy_wyjazd_prywatnym==true)) { 
								echo "<a href=# onclick=\"newWindow_r(800,300,'hd_e_zgloszenie_krok.php?element=trasa&id=$temp_id&nr=$temp_nr&zglnr=$NrZgl&zglid=$temp_zgl_id&zgl_krok_id=$temp_id'); return false;\" title=' Edycja trasy i km '><input class=imgoption type=image src=img/car.gif></a>";
							} 
							
							if (($temp_wyjazd_byl!='0') && ($czy_wyjazd_sluzbowym==true)) { 
								//echo "<input title='Wyjazd samochodem służbowym. Edycja niemożliwa' class=imgoption type=image src=img/car_S.gif>";
							}

						if (($WlaczMaile=='1') && (($temp_osobawyk==$currentuser) || ($temp_osobawyk=='-'))) {
							echo "<a href=# onclick=\"newWindow(500,450,'hd_o_zgloszenia_email.php?zglnr=$_REQUEST[nr]&nrkroku=$temp_nr&zglid=$temp_zgl_id&zgl_krok_id=$temp_id'); return false;\" title=' Wyślij email do koordynatora '>E</a>";
						}
						
					}					
					
					if (($upload_plikow==1) && ($temp_blokada_edycji==0)) {
							// zlicz ilość załączników do tego kroku
							$filecount=0;
							$directory = "attachements/";
							if (glob("$directory$temp_id*.*") != false)	{
								$filecount = count(glob("$directory$temp_id*.*"));
								//echo $filecount;
							} else { //	echo 0; 
							}
							
							if ($filecount>0) { 
								$ikona_attach='attach.gif'; 
								
								if (($currentuser==$temp_osobawyk) || ($es_nr==$KierownikId)) {
									$title_add = ' Zarządzaj załącznikami (jest już '.$filecount.' załączników do tego kroku) ';
									echo "<a href=# onclick=\"newWindow_r(800,600,'hd_d_zalacznik.php?zgl=$_REQUEST[nr]&zglszcz_id=$temp_id&krok=$temp_nr&edit=1'); return false;\" title='$title_add'><input class=imgoption type=image src=img/$ikona_attach></a>";
								} else {
									$title_add = ' Przeglądaj załącznikami (jest już '.$filecount.' załączników do tego kroku) ';	
									echo "<a href=# onclick=\"newWindow_r(800,600,'hd_d_zalacznik.php?zgl=$_REQUEST[nr]&zglszcz_id=$temp_id&krok=$temp_nr&edit=0'); return false;\" title='$title_add'><input class=imgoption type=image src=img/$ikona_attach></a>";
								}
							
							} else { 
								$ikona_attach='attach_new.gif'; 
								if ($currentuser==$temp_osobawyk) {
									$title_add = ' Dodaj załączniki do tego kroku ';
									echo "<a href=# onclick=\"newWindow_r(800,600,'hd_d_zalacznik.php?zgl=$_REQUEST[nr]&zglszcz_id=$temp_id&krok=$temp_nr&edit=1'); return false;\" title='$title_add'><input class=imgoption type=image src=img/$ikona_attach></a>";
								} else {
									if ($es_nr==$KierownikId) echo "<a href=# onclick=\"newWindow_r(800,600,'hd_d_zalacznik.php?zgl=$_REQUEST[nr]&zglszcz_id=$temp_id&krok=$temp_nr&edit=1'); return false;\" title='$title_add'><input class=imgoption type=image src=img/$ikona_attach></a>";
								}
							}
							
							
					//	}
					}					
					//echo "SELECT kb_pytanie_zgl_nr, kb_pytanie_id FROM $dbname.serwis_kb_pytania WHERE (kb_pytanie_zgl_nr='$temp_id') and (kb_pytanie_status=1)";
					
					$r1 = mysql_query("SELECT kb_pytanie_zgl_nr, kb_pytanie_id FROM $dbname.serwis_kb_pytania WHERE (kb_pytanie_zgl_szcz_id='$temp_id') and (kb_pytanie_status=1)", $conn) or die($k_b);
					
					list($DodanyDoBW, $id_pytania)=mysql_fetch_array($r1);
					//echo "$id_pytania";
					
					if ($DodanyDoBW>0) {
						echo "<a href=# onclick=\"newWindow_r(800,600,'p_kb_watek.php?poziom=0&id=$id_pytania&action=view&norefresh=1'); return false;\" title='Pokaż wątek w bazie wiedzy utworzony z tego zgłoszenia/kroku'><input class=imgoption type=image src=img/bw_show.gif></a>";
					} else {
						$r1 = mysql_query("SELECT zgl_temat FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id='$temp_zgl_id') and (zgl_widoczne=1)", $conn_hd) or die($k_b);
						list($temp_temat_zgl)=mysql_fetch_array($r1);
						$temp_temat_zgl = $temp_temat_zgl . ""; // (zgłoszenie nr ".$temp_zgl_id.")";
						
						echo "<a href=# onclick=\"newWindow_r(700,500,'d_kb_pytanie.php?poziom=1&id=&opis=".urlencode($temp_czynnosci)."&temat=".urlencode($temp_temat_zgl)."&zgl_nr=$temp_zgl_id&zgl_szcz_id=$temp_id'); return false;\" title='Dodaj czynności wykonane w kroku nr $temp_nr do bazy wiedzy'><input class=imgoption type=image src=img/bw.gif></a>";
						
						$dodanyDDBW = true;
					}
					
					// jeżeli była wymiana podzespołów w kroku - nie pozwól ukryć kroku
					$r1a = mysql_query("SELECT wp_id,wp_sprzet_opis, wp_sprzet_sn, wp_sprzet_ni, wp_sprzet_wykonane_czynnosci, wp_sprzedaz_fakt_szcz_id,wp_wskazanie_sprzetu_z_magazynu, wp_typ_podzespolu, wp_sprzet_pocztowy, wp_sprzet_pocztowy_uwagi,wp_uwagi FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_zgl_szcz_unique_nr='$temp_zgl_unique_number') and (belongs_to='$temp_zglszcz_bt') and (wp_sprzet_active=1)", $conn_hd) or die($k_b);
					$byla_wymiana = (mysql_num_rows($r1a)>0);
						
					if ($byla_wymiana==false) {
						if (($es_nr==$KierownikId) && ($temp_nr!=1)) {
							echo "<a href=# onclick=\"newWindow_r(500,100,'hd_u_zgloszenie_krok.php?id=$temp_id&nr=$temp_nr&data=$temp_data&numer_zgl=$numer_zgloszenia&status=$temp_status&unique=$temp_zgl_unique_number');return false; \">"; 
							echo "<input class=imgoption type=image src=img/delete.gif title=' Ukryj krok nr $temp_nr w zgłoszeniu nr $numer_zgloszenia '>";
							echo "</a>";
						}
					}
						//echo "</ul>";
						//echo "</div>";
						//echo "</div>";
						// koniec popup'a z opcjami 
					
				}

				if (($es_nr!=$KierownikId) && ($is_dyrektor!=1)) {
					$r1 = mysql_query("SELECT kb_pytanie_zgl_nr, kb_pytanie_id FROM $dbname.serwis_kb_pytania WHERE (kb_pytanie_zgl_szcz_id='$temp_id') and (kb_pytanie_status=1)", $conn) or die($k_b);
					
					list($DodanyDoBW, $id_pytania)=mysql_fetch_array($r1);
					//echo "$id_pytania";
					
					if ($DodanyDoBW>0) {
						echo "<a href=# onclick=\"newWindow_r(800,600,'p_kb_watek.php?poziom=0&id=$id_pytania&action=view&norefresh=1'); return false;\" title='Pokaż wątek w bazie wiedzy utworzony z tego zgłoszenia/kroku'><input class=imgoption type=image src=img/bw_show.gif></a>";
					} else {
						$r1 = mysql_query("SELECT zgl_temat FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id='$temp_zgl_id') and (zgl_widoczne=1)", $conn_hd) or die($k_b);
						list($temp_temat_zgl)=mysql_fetch_array($r1);
						$temp_temat_zgl = $temp_temat_zgl . ""; // (zgłoszenie nr ".$temp_zgl_id.")";
						
						if (!$dodanyDDBW) echo "<a href=# onclick=\"newWindow_r(700,500,'d_kb_pytanie.php?poziom=1&id=&opis=".urlencode($temp_czynnosci)."&temat=".urlencode($temp_temat_zgl)."&zgl_nr=$temp_zgl_id&zgl_szcz_id=$temp_id'); return false;\" title='Dodaj czynności wykonane w kroku nr $temp_nr do bazy wiedzy'><input class=imgoption type=image src=img/bw.gif></a>";
					}
				}
				
				
					if ($es_nr==$KierownikId) {
						$r1 = mysql_query("SELECT COUNT(zmiana_id) FROM $dbname_hd.hd_zgloszenie_kroki_historia_zmian WHERE (zmiana_krok_id='$temp_id') and (zmiana_widoczne=1)", $conn_hd) or die($k_b);
						list($czy_sa_zmiany_w_kroku)=mysql_fetch_array($r1);

						if ($czy_sa_zmiany_w_kroku>0) echo "<a title='Pokaż historię zmian w kroku nr $temp_nr'><input type=image class=imgoption src=img//faktury_nz.gif onclick=\"newWindow(800,600,'hd_p_zgloszenie_kroki_historia_zmian.php?id=$temp_id&nr_kroku=$temp_nr&zgl_nr=$temp_zgl_id')\"></a>";					
					}

					if ($temp_blokada_edycji==0) {
						if ($temp_wyjazd_byl==0) {
							if (($temp_status!='1') && ($temp_status!='2')) {
								if (($temp_osobawyk==$currentuser) || ($es_nr==$KierownikId)) {
									$data_kroku = substr($temp_czasRozp,0,10);
									echo "<a title='Dodaj wyjazd do kroku nr $temp_nr'><input type=image class=imgoption src=img//wyjazd_dodaj.gif onclick=\"newWindow_r(800,350,'hd_d_wyjazd.php?id=$temp_id&nr_kroku=$temp_nr&zgl_nr=$temp_zgl_id&unique=$temp_zgl_unique_number&komorka=".urlencode($temp_komorka)."&datakroku=$data_kroku&cu=".urlencode($currentuser)."&osk=".urlencode($temp_osobawyk)."')\"></a>";
								}
							}
						} else {
							if (($temp_status!='1') && ($temp_status!='2')) {
								if (($temp_osobawyk==$currentuser) || ($es_nr==$KierownikId)) {
									echo "<a title='Usuń wyjazd powiązany z krokiem nr $temp_nr'><input type=image class=imgoption src=img//wyjazd_usun.gif onclick=\"newWindow(600,100,'hd_u_wyjazd.php?id=$temp_id&nr_kroku=$temp_nr&zgl_nr=$temp_zgl_id&unique=$temp_zgl_unique_number')\"></a>";
								}
							}
						}
					}
		
			} // if ($temp_zglszcz_bt==$temp_bt) 
			else {
				if ($_REQUEST[viewonly]!=1) {
				
					$KierownikId = $kierownik_nr;
					//$czy_zmiana_czasu_zakonczenia = strpos($temp_czynnosci,'Zmiana terminu zakończenia:');
					
					$pozwol_edytowac=0;
					$data_wpisu = substr($temp_data_wpisu,0,10);
					$data_wstecz = SubstractWorkingDays(1,Date('Y-m-d'));
					
					if ($data_wpisu==Date('Y-m-d')) $pozwol_edytowac=1;
					if ($data_wpisu==$data_wstecz) $pozwol_edytowac=1;
					
					if (($i_act!=$ile_krokow_widocznych) && ($pozwol_edytowac==1)) $pozwol_edytowac = 0;
					
					//echo "data bieżąca: ".Date('Y-m-d')." | data wpisu: ".$data_wpisu." | data wstecz: ".$data_wstecz."";
					//echo "datawstecz=".$data_wstecz." | DW: ".$data_wpisu." | >".SubstractWorkingDays(1,$data_wpisu)."<  | pozwol: ".$pozwol_edytowac."";
					
					if (($es_nr==$KierownikId) || ($is_dyrektor==1)) $pozwol_edytowac=1;
					
					if ((($currentuser==$temp_osobawyk) || ($es_nr==$KierownikId) || ($is_dyrektor==1)) && ($pozwol_edytowac==1)) {
						if ($temp_blokada_edycji==0) {
								echo "<a href=# onclick=\"newWindow_r(500,200,'hd_e_zgloszenie_krok.php?element=czas&id=$temp_id&nr=$temp_nr&zglnr=$NrZgl&zglid=$temp_zgl_id&zgl_krok_id=$temp_id'); return false;\" title=' Edytuj czas wykonywania kroku "; 
								if ($es_nr==$KierownikId) echo "oraz datę wykonania kroku (tylko dla kierowników)";
								echo "'><input class=imgoption type=image src=img/clock_play.png></a>";
								echo "<a href=# onclick=\"newWindow_r(800,300,'hd_e_zgloszenie_krok.php?element=czynnosc&id=$temp_id&nr=$temp_nr&zglnr=$NrZgl&zglid=$temp_zgl_id&zgl_krok_id=$temp_id'); return false;\" title=' Edytuj wykonane czynności '><input class=imgoption type=image src=img/cog_play.png></a>";

								$result44 = mysql_query("SELECT wyjazd_rodzaj_pojazdu FROM $dbname_hd.hd_zgloszenie_wyjazd WHERE wyjazd_zgl_szcz_id='$temp_zgl_unique_number'", $conn_hd) or die($k_b);
								$typ_pojazdu1 = mysql_fetch_array($result44);
								$czy_wyjazd_prywatnym = ($typ_pojazdu1[wyjazd_rodzaj_pojazdu]=='P');
								$czy_wyjazd_sluzbowym = ($typ_pojazdu1[wyjazd_rodzaj_pojazdu]=='S');
					
								if (($temp_wyjazd_byl!='0') && ($czy_wyjazd_prywatnym==true)) { 
									echo "<a href=# onclick=\"newWindow_r(800,300,'hd_e_zgloszenie_krok.php?element=trasa&id=$temp_id&nr=$temp_nr&zglnr=$NrZgl&zglid=$temp_zgl_id&zgl_krok_id=$temp_id'); return false;\" title=' Edycja trasy i km '><input class=imgoption type=image src=img/car.gif></a>";
								} 
								
								if (($temp_wyjazd_byl!='0') && ($czy_wyjazd_sluzbowym==true)) { 
									//echo "<input title='Wyjazd samochodem służbowym. Edycja niemożliwa' class=imgoption type=image src=img/car_S.gif>";
								}

							if (($WlaczMaile=='1') && (($temp_osobawyk==$currentuser) || ($temp_osobawyk=='-'))) {
								echo "<a href=# onclick=\"newWindow(500,450,'hd_o_zgloszenia_email.php?zglnr=$_REQUEST[nr]&nrkroku=$temp_nr&zglid=$temp_zgl_id&zgl_krok_id=$temp_id'); return false;\" title=' Wyślij email do koordynatora '>E</a>";
							}
							
						}					
					}
					if ($es_nr==$KierownikId) {
						// jeżeli była wymiana podzespołów w kroku - nie pozwól ukryć kroku
						$r1a = mysql_query("SELECT wp_id,wp_sprzet_opis, wp_sprzet_sn, wp_sprzet_ni, wp_sprzet_wykonane_czynnosci, wp_sprzedaz_fakt_szcz_id,wp_wskazanie_sprzetu_z_magazynu, wp_typ_podzespolu, wp_sprzet_pocztowy, wp_sprzet_pocztowy_uwagi,wp_uwagi FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_zgl_szcz_unique_nr='$temp_zgl_unique_number') and (belongs_to='$temp_zglszcz_bt') and (wp_sprzet_active=1)", $conn_hd) or die($k_b);
						$byla_wymiana = (mysql_num_rows($r1a)>0);
							
						if ($byla_wymiana==false) {
							if (($es_nr==$KierownikId) && ($temp_nr!=1)) {
								echo "<a href=# onclick=\"newWindow_r(500,100,'hd_u_zgloszenie_krok.php?id=$temp_id&nr=$temp_nr&data=$temp_data&numer_zgl=$numer_zgloszenia&status=$temp_status&unique=$temp_zgl_unique_number');return false; \">"; 
								echo "<input class=imgoption type=image src=img/delete.gif title=' Ukryj krok nr $temp_nr w zgłoszeniu nr $numer_zgloszenia '>";
								echo "</a>";
							}
						}					
					}
					echo "<a href=# onclick=\"newWindow_r(700,500,'d_kb_pytanie.php?poziom=1&id=&opis=".urlencode($temp_czynnosci)."&temat=".urlencode($temp_temat_zgl)."&zgl_nr=$temp_zgl_id&zgl_szcz_id=$temp_id'); return false;\" title='Dodaj czynności wykonane w kroku nr $temp_nr do bazy wiedzy'><input class=imgoption type=image src=img/bw.gif></a>";
					echo "<center><b>Krok wykonany<br />przez osobę z innej filii</b></center>";
				} else {
			
					list($GetBelongsToFromHD)=mysql_fetch_array(mysql_query("SELECT belongs_to FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id=$_REQUEST[id]) LIMIT 1"));
		
		if ($temp_zglszcz_bt!=$GetBelongsToFromHD) {

				$KierownikId = $kierownik_nr;	
				//$r40 = mysql_query("SELECT filia_leader FROM $dbname.serwis_filie WHERE (filia_id='$GetBelongsToFromHD') LIMIT 1", $conn) or die($k_b);
				//list($KierownikId)=mysql_fetch_array($r40);
				// koniec wyciągania danych o kierowniku
				
				//$czy_zmiana_czasu_zakonczenia = strpos($temp_czynnosci,'Zmiana terminu zakończenia:');
				
				if (($currentuser==$temp_osobawyk) || ($es_nr==$KierownikId)) {
					// popup menu z opcjami
					
					if ($PokazIkonyWHPrzegladanie==1) {
						//echo "<div class=chromestyle id=chromemenu><ul><li><a href=# rel=dropmenu1><input class=imgoption type=image src=img/hd_zgl_opcje.gif></a></li></ul></div>";


						//echo "<a href=# title=' Kliknij, aby rozwinąć opcje ' onClick=\"$('#opcje$temp_nr').toggle();\">";
						//echo "<input class=imgoption type=image src=img/hd_zgl_opcje.gif>";
					} else {
						//echo "<a href=# class='anchorclass normalfont' rel=submenu2[click] title=' Kliknij, aby rozwinąć opcje '>";
					//	echo "<a href=# data-flexmenu=flexmenu1 title=' Kliknij, aby rozwinąć opcje '>";
					//	echo "Opcje";
					}
					
					//echo "</a>";
//					echo "<div id=submenu2 class=anylinkcss-kroki>";
					//echo "<div id=dropmenu1 class=dropmenudiv>";
					
					//echo "<div id=opcje$temp_nr style=display:none>";
					//echo "<ul>";
					//echo "<li><p style='background-color:#2A522A; color:white; padding:3px' align=center>&nbsp;Krok nr <b>$temp_nr</b> zgłoszenia nr <b>$NrZgl</b></p></li>";
					//	if ($currentuser==$temp_osobawyk) {
					
					if ($temp_blokada_edycji==0) {
							echo "<a href=# onclick=\"newWindow_r(500,200,'hd_e_zgloszenie_krok.php?element=czas&id=$temp_id&nr=$temp_nr&zglnr=$NrZgl&zglid=$temp_zgl_id&zgl_krok_id=$temp_id'); return false;\" title=' Edytuj czas wykonywania kroku "; 
							if ($es_nr==$KierownikId) echo "oraz datę wykonania kroku (tylko dla kierowników)";
							echo "'><input class=imgoption type=image src=img/clock_play.png></a>";
							echo "<a href=# onclick=\"newWindow_r(800,300,'hd_e_zgloszenie_krok.php?element=czynnosc&id=$temp_id&nr=$temp_nr&zglnr=$NrZgl&zglid=$temp_zgl_id&zgl_krok_id=$temp_id'); return false;\" title=' Edytuj wykonane czynności '><input class=imgoption type=image src=img/cog_play.png></a>";
							
							if ($temp_wyjazd_byl!='0') { 
								echo "<a href=# onclick=\"newWindow_r(800,300,'hd_e_zgloszenie_krok.php?element=trasa&id=$temp_id&nr=$temp_nr&zglnr=$NrZgl&zglid=$temp_zgl_id&zgl_krok_id=$temp_id'); return false;\" title=' Edycja trasy i km '><input class=imgoption type=image src=img/car.gif></a>";
							}
										
						if (($WlaczMaile=='1') && (($temp_osobawyk==$currentuser) || ($temp_osobawyk=='-'))) {
							echo "<a href=# onclick=\"newWindow(500,450,'hd_o_zgloszenia_email.php?zglnr=$_REQUEST[nr]&nrkroku=$temp_nr&zglid=$temp_zgl_id');return false; \" title=' Wyślij email do koordynatora '>E</a>";
						}
						
					}
					
					if (($es_nr==$KierownikId) && ($temp_nr!=1)) {
						echo "<a href=# onclick=\"newWindow_r(500,100,'hd_u_zgloszenie_krok.php?id=$temp_id&nr=$temp_nr&data=$temp_data&numer_zgl=$numer_zgloszenia&status=$temp_status&unique=$temp_zgl_unique_number'); return false; \">"; 
						echo "<input class=imgoption type=image src=img/delete.gif title=' Ukryj krok nr $temp_nr w zgłoszeniu nr $numer_zgloszenia '>";
						echo "</a>";			
					}
					
					//echo "</ul>";
					//echo "</div>";
					//echo "</div>";
					// koniec popup'a z opcjami 
				
				}		
			}
		}
		
	}

		//	echo "$temp_nr $ile_krokow";
		//if (($temp_status!=9) && ($temp_nr==$ile_krokow)) {
			
			if (($temp_status!=9) && ($i_act==$ile_krokow_widocznych)) {
				echo "<hr />";
				if ($temp_zgl_seryjne!='') { $zgl_seryjne_mark = '1'; } else { $zgl_seryjne_mark = ''; }
				
				if ($_REQUEST[readonly]!='1') 
				
					if ($zastap_obsluge_zmiana_statusu==0) {
						echo "<input type=button class=buttons value='Obsługa zgłoszenia' onclick=\"newWindow_r(800,600,'hd_o_zgloszenia.php?action=obsluga&id=$_REQUEST[id]&nr=$_REQUEST[nr]&zgl_s=$zgl_seryjne_mark&newwindow=$_REQUEST[newwindow]'); return false;\">";
					} else {					
						echo "<input type=button class=buttons value='Zmień status zgłoszenia' onclick=\"newWindow_r(800,600,'hd_o_zgloszenia_zs.php?id=$temp_zgl_id&unr=$temp_unikalny_nr&nr=$temp_zgl_id&ts=$temp_status&tk=$temp_kategoria&tpk=$temp_podkategoria&zgoda=9&komorka=".urlencode($temp_komorka)."&osoba=".urlencode($temp_osoba)."&zgl_s=$zgl_seryjne_mark&rozwiazany=$temp_czy_rozwiazany&ww=$temp_ww&clearcookies=1'); return false; \">";
					}
			}
			
			if ($temp_status==9) {
				echo "<hr />";
				if ($temp_zgl_seryjne!='') { $zgl_seryjne_mark = '1'; } else { $zgl_seryjne_mark = ''; }
				
				echo "<input type=button class=buttons value='Podgląd zgłoszenia' onclick=\"newWindow_r(800,600,'hd_o_zgloszenia.php?action=podgląd&id=$_REQUEST[id]&nr=$_REQUEST[nr]&zgl_s=$zgl_seryjne_mark'); return false;\">";
			}
			
		echo "</td>";
	} else {
		echo "<td colspan=10 style='text-align:center; background-color:red; color:white;'>Krok usunięty</td>";
	}

		$j++;
		$i++;
		
		if ($temp_widoczne==1) $i_act++;
		
	_tr();
	}	
	endtable();

	echo "<hr />";
	
	//if ($temp_bt==$es_filia) {	
	echo "<span style='float:left;'>";
		echo "&nbsp;";
		echo "<input type=button class=buttons value='Przelicz czasy dla poszczególnych etapów' onclick=\"newWindow_r(800,600,'hd_o_zgloszenia_wylicz_czasy.php?id=$_REQUEST[id]&nr=$_REQUEST[nr]'); return false;\">";
		
		echo "<input type=button class=buttons value='Pokaż czasy poszczególnych etapów' onclick=\"newWindow_r(500,300,'hd_p_zgloszenia_czasy.php?id=$_REQUEST[id]&nr=$_REQUEST[nr]'); return false;\">";
	echo "</span>";
	
// jeżeli oglądamy zgłoszenie na bazie którego zostało utworzone nowe zgłoszenie - pod krokami pokaż informację
	
	list($child_zgl)=mysql_fetch_array(mysql_query("SELECT zgl_nr FROM hd_zgloszenie WHERE (zgl_kontynuacja_zgloszenia_numer=$_REQUEST[nr]) LIMIT 1"));
//echo "SELECT zgl_nr FROM hd_zgloszenie WHERE (zgl_kontynuacja_zgloszenia_numer=$temp_parent_zgl) LIMIT 1";

	if (($child_zgl!=0) && ($_GET[is_parent]!=1)) {
		$wynik_child = mysql_query("SELECT zgl_data,zgl_godzina,zgl_komorka,zgl_osoba,zgl_telefon,zgl_poczta_nr,zgl_temat,zgl_tresc,zgl_kategoria,zgl_podkategoria, zgl_priorytet,zgl_osoba_przypisana,zgl_status,zgl_kontynuacja_zgloszenia_numer FROM $dbname_hd.hd_zgloszenie WHERE (zgl_nr=$child_zgl) LIMIT 1", $conn_hd);
		
		while ($dane_f1_child=mysql_fetch_array($wynik_child)) {
			$temp_data_child				= $dane_f1_child['zgl_data'];
			$temp_godzina_child				= $dane_f1_child['zgl_godzina'];
			$temp_komorka_child				= $dane_f1_child['zgl_komorka'];
			$temp_osoba_child				= $dane_f1_child['zgl_osoba'];
			$temp_telefon_child				= $dane_f1_child['zgl_telefon'];
			$temp_poczta_nr_child			= $dane_f1_child['zgl_poczta_nr'];
			$temp_temat_child				= $dane_f1_child['zgl_temat'];
			$temp_tresc_child				= $dane_f1_child['zgl_tresc'];
			$temp_kategoria_child			= $dane_f1_child['zgl_kategoria'];
			$temp_podkategoria_child		= $dane_f1_child['zgl_podkategoria'];
			$temp_priorytet_child			= $dane_f1_child['zgl_priorytet'];
			$temp_osoba_przypisana_child	= $dane_f1_child['zgl_osoba_przypisana'];
			$temp_status_child				= $dane_f1_child['zgl_status'];
			//$temp_child_zgl_child			= $dane_f1_child['zgl_kontynuacja_zgloszenia_numer'];
			$temp_child_naprawa_id			= $dane_f1_child['zgl_naprawa_id'];
			$temp_child_bt					= $dane_f1_child['belongs_to'];
			
		}
		
		switch ($temp_kategoria_child) {
			case 2:	if ($temp_priorytet_child==2) { $kolorgrupy='#FF7F2A'; break; 
						}
					if ($temp_priorytet_child==4) { $kolorgrupy='#F73B3B'; break; 
						}				
			case 3:	if ($temp_priorytet_child==3) { $kolorgrupy='#FFAA7F'; break; 
						}
			default: if ($temp_status_child==9) { $kolorgrupy='#FFFFFF'; break; 
						} else {
						$kolorgrupy='';
				}
		}
		
		echo "<h3 class=h3powiazane><center>Na bazie zgłoszenia numer <b>$_REQUEST[nr]</b> utworzono zgłoszenie numer <b>".$child_zgl."</b>&nbsp;<input type=button class=buttons id=child_zgl_button value=' Pokaż / ukryj szczegóły realizacji zgłoszenia ".$child_zgl."' onClick=\"$('#child_zgl_info').toggle(); $('#child_zgl_info').load('hd_p_zgloszenie_kroki.php?nr=".$child_zgl."&id=".$child_zgl."&is_parent=0&is_child=1&kg=".substr($kolorgrupy,1,strlen($kolorgrupy))."'); return false; \" /></center></h3>";
		echo "<div id=child_zgl_info style='display:none'>";
		echo "</div>";
	}
	
	if ($_GET[newwindow]=='1') {
		echo "<span style='float:right'>";		
		//addbuttons("zamknij");
		echo "<input type=button class=buttons value='Zamknij' onClick=\"";
		
		if (($_SESSION['refresh_o_zgloszenia_'.$_REQUEST[nr].'']==1)) echo "if (opener) opener.location.reload(true);";		
		echo "self.close(); ";
		echo " \" />";
		
		if (($_SESSION['refresh_o_zgloszenia_'.$_REQUEST[nr].'']==1) && ($_REQUEST[newwindow]==1)) unset($_SESSION['refresh_o_zgloszenia_'.$_REQUEST[nr].'']);
		
		echo "</span>";
	}

	ob_flush();
	flush();
?>
<?php include('warning_messages_blinking.php'); ?>
<script>HideWaitingMessage('<?php echo $span_name; ?>');</script>