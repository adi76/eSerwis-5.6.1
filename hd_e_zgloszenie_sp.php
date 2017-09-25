<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body onLoad="document.forms[0].elements[<?php if ($_GET[edit]=='0') { echo "2"; } else { echo "0"; } ?>].focus();">
<?php 
if ($submit) { 
	?><script>ShowWaitingMessage('Trwa zapisywanie danych... proszę czekać', 'Saving');</script><?php ob_flush(); flush();
	
	$_POST=sanitize($_POST);

	$Zdiagnozowany = $_POST[SelectZdiagnozowany];
	$oferta_wyslana = $_POST[OfertaWyslanaInput];
	$AkceptacjaKosztow = $_POST[SelectAkceptacjaKosztow];
	$zam_wyslane = $_POST[ZamowienieWyslaneInput];
	$zam_zrealizowane = $_POST[ZamowienieZrealizowaneInput];
	$do_oddania = $_POST[GotowyDoOddaniaInput];
	
	
	$sql_t9="UPDATE $dbname_hd.hd_naprawy_powiazane SET hdnp_zdiagnozowany  = '$Zdiagnozowany', hdnp_oferta_wyslana = '$oferta_wyslana', hdnp_akceptacja_kosztow = '$AkceptacjaKosztow', hdnp_zamowienie_wyslane = '$zam_wyslane', hdnp_zamowienie_zrealizowane = '$zam_zrealizowane', hdnp_gotowe_do_oddania = '$do_oddania' WHERE (hdnp_zgl_id = '$_POST[last_nr]') and (hdnp_widoczne=1) LIMIT 1";
		
	//echo "UPDATE SQL = ".$sql_t9;
	if (mysql_query($sql_t9, $conn_hd) or die($k_b)) {
		?>
		<script>
		self.close();
		if (opener) opener.location.reload(true);
		</script>
		<?php
	} else {
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
	}
	
} else {

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

	echo "<h4>";
	
		echo "<a id=PokazSzczZgl class=normalfont style='font-weight:normal' title=' Pokaż szczegóły zgłoszenia ' href=# onClick=\"$('#InformacjeOZgloszeniu').toggle(); $('#PokazSzczZgl').hide(); $('#UkryjSzczZgl').show(); \" >";
		//echo "Zmiana statusu zgłoszenia nr <b>$_GET[nr]</b>";
		echo "Edycja stanów pośrednich dla zgłoszenia nr <b>$_GET[nr]</b>";
		if ($czy_wyroznic_zgloszenia_seryjne==1) {
			if ($_GET[zgl_s]=='1') echo " [zgł. seryjne]";
		}	
		//echo "&nbsp;z&nbsp;<b>".$temp_komorka."</b>";
		echo "&nbsp;<input type=image class=imgoption src=img/show_more_".$temp_kategoria.".gif>";	
		echo "</a>";
		
		echo "<a id=UkryjSzczZgl style='display:none' class=normalfont style='font-weight:normal' title=' Ukryj szczegóły zgłoszenia ' href=# onClick=\"$('#InformacjeOZgloszeniu').toggle(); $('#PokazSzczZgl').show(); $('#UkryjSzczZgl').hide(); \" >";	
		//echo "Zmiana statusu zgłoszenia nr <b>$_GET[nr]</b>";
		echo "<font style='font-weight:normal'>Edycja stanów pośrednich dla zgłoszenia nr <b>$_GET[nr]</b></font>";
		if ($czy_wyroznic_zgloszenia_seryjne==1) {
			if ($_GET[zgl_s]=='1') echo " [zgł. seryjne]";
		}	
		//echo "&nbsp;z&nbsp;<b>".$temp_komorka."</b>";
		echo "&nbsp;<input type=image class=imgoption src=img/hide_more_".$temp_kategoria.".gif>";
		echo "</a>";

	echo "</h4>";

?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
	
echo "<div id=InformacjeOZgloszeniu style='display:none;'>";

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
	
	starttable('center');
	echo "<form name=ed action=$PHP_SELF method=POST onSubmit=\"return (confirm('Czy zapisać zmiany stanów pośrednich ?'));\">";
	tbl_empty_row(2);
		echo "<tr>";		
			echo "<td>";
				$r55 = mysql_query("SELECT hdnp_zdiagnozowany, hdnp_oferta_wyslana, hdnp_akceptacja_kosztow, hdnp_zamowienie_wyslane, hdnp_zamowienie_zrealizowane, hdnp_gotowe_do_oddania, hdnp_naprawa_zakonczona FROM $dbname_hd.hd_naprawy_powiazane WHERE (hdnp_zgl_id='$_GET[nr]') ORDER BY hdnp_id DESC LIMIT 1", $conn_hd) or die($k_b);
				list($temp_zdiagnozowany, $temp_oferta_wyslana, $temp_akceptacja_kosztow, $temp_zamowienie_wyslane, $temp_zamowienie_zrealizowane, $temp_gotowe_do_oddania, $temp_naprawa_zakonczona)=mysql_fetch_array($r55);

				if ($temp_zdiagnozowany=='') $temp_zdiagnozowany=9;
				if ($temp_oferta_wyslana=='') $temp_oferta_wyslana=9;
				if ($temp_akceptacja_kosztow=='') $temp_akceptacja_kosztow=9;
				if ($temp_zamowienie_wyslane=='') $temp_zamowienie_wyslane=9;
				if ($temp_zamowienie_zrealizowane=='') $temp_zamowienie_zrealizowane=9;
				if ($temp_gotowe_do_oddania=='') $temp_gotowe_do_oddania=9;
				if ($temp_naprawa_zakonczona=='') $temp_naprawa_zakonczona=9;

				echo "<table style='width:auto' align=center>";
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

				echo " id=SelectZdiagnozowany name=SelectZdiagnozowany >\n";
				if ($temp_zdiagnozowany==9) echo "<option value='9'></option>\n";
				echo "<option value='1'"; if ($temp_zdiagnozowany==1) echo " SELECTED "; echo ">TAK</option>\n";
				echo "<option value='0'"; if ($temp_zdiagnozowany==0) echo " SELECTED "; echo ">NIE</option>\n";
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
				echo "<span id=OfertaWyslana style=''>";
				echo "<select ";
				
				switch ($temp_oferta_wyslana) {
					case 0 : echo " style='background-color:red' "; break;
					case 1 : echo " style='background-color:green' "; break; 
					case 9 : echo " style='background-color:yellow' "; break; 
					default : echo " style='background-color:transparent' "; break; 
				}
				
				echo " id=OfertaWyslanaInput name=OfertaWyslanaInput >\n";
				if ($temp_oferta_wyslana==9) echo "<option value='9'></option>\n";
				echo "<option value='1'"; if ($temp_oferta_wyslana==1) echo " SELECTED "; echo ">TAK</option>\n";
				echo "<option value='0'"; if ($temp_oferta_wyslana==0) echo " SELECTED "; echo ">NIE</option>\n";
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
				echo "<span id=AkceptacjaKosztow style=''>";
				echo "<select ";
				switch ($temp_akceptacja_kosztow) {
					case 0 : echo " style='background-color:red' "; break;
					case 1 : echo " style='background-color:green' "; break; 
					case 9 : echo " style='background-color:yellow' "; break; 
					default : echo " style='background-color:transparent' "; break; 
				}				
				echo " id=SelectAkceptacjaKosztow name=SelectAkceptacjaKosztow >";
				if ($temp_akceptacja_kosztow==9) echo "<option value='9'></option>\n";
				echo "<option value='1'"; if ($temp_akceptacja_kosztow==1) echo " SELECTED "; echo ">TAK</option>\n";
				echo "<option value='0'"; if ($temp_akceptacja_kosztow==0) echo " SELECTED "; echo ">NIE</option>\n";
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
				echo "<span id=ZamowienieWyslane style=';'>";
				echo "<select"; 
				switch ($temp_zamowienie_wyslane) {
					case 0 : echo " style='background-color:red' "; break;
					case 1 : echo " style='background-color:green' "; break; 
					case 9 : echo " style='background-color:yellow' "; break; 
					default : echo " style='background-color:transparent' "; break; 
				}				
				echo " id=ZamowienieWyslaneInput name=ZamowienieWyslaneInput >";
				if ($temp_zamowienie_wyslane==9) echo "<option value='9'></option>\n";
				echo "<option value='1'"; if ($temp_zamowienie_wyslane==1) echo " SELECTED "; echo ">TAK</option>\n";
				echo "<option value='0'"; if ($temp_zamowienie_wyslane==0) echo " SELECTED "; echo ">NIE</option>\n";
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
				echo "<span id=ZamowienieZrealizowane style=';'>";
				echo "<select "; 
				switch ($temp_zamowienie_zrealizowane) {
					case 0 : echo " style='background-color:red' "; break;
					case 1 : echo " style='background-color:green' "; break; 
					case 9 : echo " style='background-color:yellow' "; break; 
					default : echo " style='background-color:transparent' "; break; 
				}				
				echo " id=ZamowienieZrealizowaneInput name=ZamowienieZrealizowaneInput >";
				if ($temp_zamowienie_zrealizowane==9) echo "<option value='9'></option>\n";
				echo "<option value='1'"; if ($temp_zamowienie_zrealizowane==1) echo " SELECTED "; echo ">TAK</option>\n";
				echo "<option value='0'"; if ($temp_zamowienie_zrealizowane==0) echo " SELECTED "; echo ">NIE</option>\n";
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
				echo "<span id=GotowyDoOddania style=';'>";
				echo "<select ";
				switch ($temp_gotowe_do_oddania) {
					case 0 : echo " style='background-color:red' "; break;
					case 1 : echo " style='background-color:green' "; break; 
					case 9 : echo " style='background-color:yellow' "; break; 
					default : echo " style='background-color:transparent' "; break; 
				}
				echo " id=GotowyDoOddaniaInput name=GotowyDoOddaniaInput >";
				if ($temp_gotowe_do_oddania==9) echo "<option value='9'></option>\n";
				echo "<option value='1'"; if ($temp_gotowe_do_oddania==1) echo " SELECTED "; echo ">TAK</option>\n";
				echo "<option value='0'"; if ($temp_gotowe_do_oddania==0) echo " SELECTED "; echo ">NIE</option>\n";
				echo "</select>";
				echo "</span>";
				echo "</td></tr>";
				
				echo "</table>";
				
			_td();
		_tr();

tbl_empty_row(2);	
endtable();

echo "<input type=hidden name=last_nr value='$_GET[nr]' />";

startbuttonsarea("right");
addbuttons("zapisz","anuluj");
endbuttonsarea();
_form();

}

?>
<script>HideWaitingMessage();</script>
</body>
</html>