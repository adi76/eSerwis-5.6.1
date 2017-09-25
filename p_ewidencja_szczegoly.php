<?php include_once('header.php'); ?>
<body>
<?php include('body_start.php'); ?>
<div id="tiplayer" style="position:absolute; visibility:hidden; z-index:10000;"></div>
<?php 
$result = mysql_query("SELECT * FROM $dbname.serwis_ewidencja WHERE (belongs_to=$es_filia) and (ewidencja_id='$id')", $conn) or die($k_b);
pageheader("Szczegółowe informacje o sprzęcie",1);
starttable();
$i=0;
while ($dane = mysql_fetch_array($result)) {
	$eid 		= $dane['ewidencja_id'];					$etyp_id	= $dane['ewidencja_typ'];
	$eup_id		= $dane['ewidencja_up_id'];					$euser		= $dane['ewidencja_uzytkownik'];  
	$enrpok		= $dane['ewidencja_nr_pokoju'];				$enizest	= $dane['ewidencja_zestaw_ni'];
	$eknazwa	= $dane['ewidencja_komputer_nazwa'];		$ekopis		= $dane['ewidencja_komputer_opis'];
	$eksn		= $dane['ewidencja_komputer_sn'];			$ekip		= $dane['ewidencja_komputer_ip'];
	$eke		= $dane['ewidencja_komputer_endpoint'];		$emo		= $dane['ewidencja_monitor_opis'];
	$emsn		= $dane['ewidencja_monitor_sn'];			$edo		= $dane['ewidencja_drukarka_opis'];
	$edsn		= $dane['ewidencja_drukarka_sn'];			$edni		= $dane['ewidencja_drukarka_ni'];
	$eu			= $dane['ewidencja_uwagi'];					$es			= $dane['ewidencja_status'];
	$eo_id		= $dane['ewidencja_oprogramowanie_id'];		$emoduser	= $dane['ewidencja_modyfikacja_user'];
	$emoddata	= $dane['ewidencja_modyfikacja_date'];		$ekonf		= $dane['ewidencja_konfiguracja'];
	$egwarancja	= $dane['ewidencja_gwarancja_do'];			$egwarancjakto	= $dane['ewidencja_gwarancja_kto'];		$drukarkapow= $dane['ewidencja_drukarka_powiaz_z'];		$edo1		= $edo;
	
	if ($enrpok=='') $enrpok='-';
	if ($enizest=='') $enizest='-';
	if ($eknazwa=='') $eknazwa='-';
	if ($ekopis=='') $ekopis='-';
	if ($eksn=='') $eksn='-';
	if ($ekip=='') $ekip='-';
	if ($eke=='') $eke='-';
	if ($emo=='') $emo='-';
	if ($emsn=='') $emsn='-';
	if ($edo=='') $edo='-';
	if ($edsn=='') $edsn='-';
	if ($edni=='') $edni='-';
	if ($ekonf=='') $ekonf='-';
	if ($euser=='') $euser='-';
	if ($eu=='') $eu='-';		

	$result77 = mysql_query("SELECT rola_nazwa FROM $dbname.serwis_slownik_rola WHERE rola_id='$etyp_id'", $conn) or die($k_b);
	list($rolanazwa)=mysql_fetch_array($result77);

	tbl_tr_highlight($i); td(";;Rodzaj sprzętu|;;<b>".$rolanazwa."</b>"); $i++; _tr(); 
		$result7a = mysql_query("SELECT up_nazwa, up_id FROM $dbname.serwis_komorki WHERE (up_id='$eup_id') and (belongs_to=$es_filia) LIMIT 1", $conn) or die($k_b);
		list($temp_up_nazwa,$temp_up_id)=mysql_fetch_array($result7a);
		
		$sql_up = "SELECT up_id, up_pion_id FROM $dbname.serwis_komorki WHERE (up_nazwa='$temp_up_nazwa') and (belongs_to=$es_filia) LIMIT 1";
		$wynik = mysql_query($sql_up, $conn) or die($k_b);
		$dane_up = mysql_fetch_array($wynik);
		$temp_up_id = $dane_up['up_id'];
		$temp_pion_id = $dane_up['up_pion_id'];
		
		// nazwa pionu z id pionu
		$sql_get_pion = "SELECT pion_nazwa FROM $dbname.serwis_piony WHERE (pion_id='$temp_pion_id') LIMIT 1";
		$wynik_get_pion = mysql_query($sql_get_pion, $conn) or die($k_b);
		$dane_get_pion = mysql_fetch_array($wynik_get_pion);
		$temp_pion_nazwa = $dane_get_pion['pion_nazwa'];
		// koniec ustalania nazwy pionu
		
	tbl_tr_highlight($i); td_(";;Lokalizacja sprzętu|;;<b>".$temp_pion_nazwa." ".$temp_up_nazwa."</b>"); $i++; 	
		echo "<a title=' Szczegółowe informacje o $temp_up_nazwa '><input class=imgoption type=image src=img/detail.gif align=absmiddle onclick=\"newWindow_r(700,600,'p_komorka_szczegoly.php?id=$temp_up_id')\"></a>";
		_td();
	_tr();
	if (($rolanazwa=='Komputer') || ($rolanazwa=='Serwer') || ($rolanazwa=='Notebook') || ($rolanazwa=='Czytnik')) {
		tbl_tr_highlight($i); td(";;Numer pokoju|;;<b>".$enrpok."</b>"); $i++; _tr(); 	
	}
	if (($rolanazwa=='Komputer') || ($rolanazwa=='Serwer') || ($rolanazwa=='Notebook') || ($rolanazwa=='Czytnik')) {
		tbl_tr_highlight($i); td(";;Użytkownik sprzętu|;;<b>".$euser."</b>"); $i++; _tr();
	}
	if (($rolanazwa!='Drukarka') || ($rolanazwa=='Czytnik')) {
		tbl_tr_highlight($i); td(";;Nr inwentarzowy|;;<b>".$enizest."</b>"); $i++; _tr(); 
	}
	if (($rolanazwa=='Komputer') || ($rolanazwa=='Serwer') || ($rolanazwa=='Notebook')) {
		tbl_tr_highlight($i); td(";;Model komputera|;;<b>".$ekopis."</b>"); $i++; _tr(); 
		tbl_tr_highlight($i); td(";;Numer seryjny komputera|;;<b>".$eksn."</b>"); $i++; _tr(); 
		tbl_tr_highlight($i); td(";;Nazwa komputera|;;<b>".$eknazwa."</b>"); $i++; _tr(); 
		tbl_tr_highlight($i); td(";;Adres IP komputera|;;<b>".$ekip."</b>"); $i++; _tr(); 
		tbl_tr_highlight($i); td(";;Konfiguracja komputera|;;<b>".$ekonf."</b>"); $i++; _tr(); 
		tbl_tr_highlight($i); td(";;Numer endpointa|;;<b>".$eke."</b>"); $i++; _tr(); 
	}
	if (($rolanazwa=='Komputer') || ($rolanazwa=='Serwer')) {
		tbl_tr_highlight($i); td(";;Model monitora|;;<b>".$emo."</b>"); $i++; _tr(); 
		tbl_tr_highlight($i); td(";;Numer seryjny monitora|;;<b>".$emsn."</b>"); $i++; _tr(); 
	}
	if (($rolanazwa=='Drukarka') || ($edo1!='')) {
		tbl_tr_highlight($i); td(";;Model drukarki|;;<b>".$edo."</b>"); $i++; _tr(); 
		tbl_tr_highlight($i); td(";;Numer seryjny drukarki|;;<b>".$edsn."</b>"); $i++; _tr(); 
		tbl_tr_highlight($i); td(";;Numer inwentarzowy drukarki|;;<b>".$edni."</b>"); $i++; _tr(); 
		if ($drukarkapow>0) {
			$sql5a="SELECT ewidencja_id, ewidencja_komputer_nazwa, ewidencja_komputer_opis, ewidencja_komputer_sn, ewidencja_komputer_ip, ewidencja_drukarka_powiaz_z FROM $dbname.serwis_ewidencja WHERE (ewidencja_id=$drukarkapow)";
			$result5a = mysql_query($sql5a, $conn) or die($k_b);
			while ($newArray5a = mysql_fetch_array($result5a)) {
				$temp_id1			= $newArray5a['ewidencja_id'];
				$temp_1				= $newArray5a['ewidencja_komputer_nazwa'];
				$temp_2				= $newArray5a['ewidencja_komputer_opis'];
				$temp_3				= $newArray5a['ewidencja_komputer_sn'];
				$temp_4				= $newArray5a['ewidencja_komputer_ip'];
				$temp_pow			= $newArray5a['ewidencja_drukarka_powiaz_z'];			
				$komp_info="$temp_2<br />SN: $temp_3<br />$temp_1, $temp_4";
			}
			tbl_tr_highlight($i); td(";;Powiązania z|;;<b>".$komp_info."</b>"); $i++; _tr(); 
		}
	}
	
	if ($rolanazwa=='Czytnik') {
		tbl_tr_highlight($i); td(";;Model czytnika|;;<b>".$eknazwa."</b>"); $i++; _tr(); 
		tbl_tr_highlight($i); td(";;Numer seryjny czytnika|;;<b>".$eksn."</b>"); $i++; _tr(); 
		//tbl_tr_highlight($i); td(";;Numer inwentarzowy drukarki|;;<b>".$ekni."</b>"); $i++; _tr(); 
		if ($drukarkapow>0) {
			$sql5a="SELECT ewidencja_id, ewidencja_komputer_nazwa, ewidencja_komputer_opis, ewidencja_komputer_sn, ewidencja_komputer_ip, ewidencja_drukarka_powiaz_z FROM $dbname.serwis_ewidencja WHERE (ewidencja_id=$drukarkapow)";
			$result5a = mysql_query($sql5a, $conn) or die($k_b);
			while ($newArray5a = mysql_fetch_array($result5a)) {
				$temp_id1			= $newArray5a['ewidencja_id'];
				$temp_1				= $newArray5a['ewidencja_komputer_nazwa'];
				$temp_2				= $newArray5a['ewidencja_komputer_opis'];
				$temp_3				= $newArray5a['ewidencja_komputer_sn'];
				$temp_4				= $newArray5a['ewidencja_komputer_ip'];
				$temp_pow			= $newArray5a['ewidencja_drukarka_powiaz_z'];			
				$komp_info="$temp_2<br />SN: $temp_3<br />$temp_1, $temp_4";
			}
			tbl_tr_highlight($i); td(";;Powiązania z|;;<b>".$komp_info."</b>"); $i++; _tr(); 
		}
	}
	
	$result_d = mysql_query("SELECT ewidencja_drukarka_opis, ewidencja_drukarka_sn, ewidencja_drukarka_ni FROM $dbname.serwis_ewidencja WHERE ewidencja_drukarka_powiaz_z=$eid", $conn) or die($k_b);
	$count_rows_k = mysql_num_rows($result_d);
	if ($count_rows_k>0) {
		$o=1;
		while (list($nazwa_d,$sn_d,$ni_d)=mysql_fetch_array($result_d)) {	
			$druk_info="$nazwa_d<br />SN: $sn_d<br />NI: $ni_d";
			tbl_tr_highlight($i); td(";;Podłączona drukarka #$o|;;<b>".$druk_info."</b>"); $i++; _tr();
			$o++;
		}
	}
	tbl_tr_highlight($i); td_(";;Uwagi|;w;<b>".$eu."</b>"); $i++; _tr(); 
		$dddd = Date('Y-m-d');
		if ($egwarancja!='0000-00-00') {
			tbl_tr_highlight($i); 
			td_(";;Gwarancja do");_td();
				td_(";;<b>".$egwarancja."</b>");
				if ($dddd>$egwarancja) echo "- gwarancja wygasła";
				_td();
			_tr();
		}
	if ($egwarancjakto>0) {
			$sql5="SELECT fz_id,fz_nazwa FROM $dbname.serwis_fz WHERE ((fz_is_fs='on') and (fz_id=$egwarancjakto)) ORDER BY fz_nazwa ASC LIMIT 1";
			$result5 = mysql_query($sql5, $conn) or die($k_b);
			list($temp_id1, $temp_nazwa)=mysql_fetch_array($result5);
			$gwar_fz="$temp_nazwa";
	tbl_tr_highlight($i); td_(";;Sprzęt serwisowany przez|;;<b>".$gwar_fz."</b>"); $i++; 
		echo "<input type=image class=imgoption src=img/print.gif title=' Drukuj informacje o firmie serwisowej ' onclick=\"newWindow_r(800,600,'p_firma_zewnetrzna.php?fzid=$temp_id1')\">$gwar_fz2";
		_td();
	_tr();
	}
}
endtable();
if (($rolanazwa=="Komputer") || ($rolanazwa=="Serwer") || ($rolanazwa=="Notebook")) {
	if ($eo_id!=0) { 
starttable();
		tr_();
			th_colspan(2,'c',"Oprogramowanie zainstalowane na tym sprzęcie");
		_tr();
		$i++;
		$result8 = mysql_query("SELECT oprogramowanie_nazwa FROM $dbname.serwis_oprogramowanie WHERE (oprogramowanie_ewidencja_id='$id')", $conn) or die($k_b);
		while (list($temp_nazwa_opr)=mysql_fetch_array($result8)) {
			tbl_tr_highlight($i);
				td_colspan(2,'c',$temp_nazwa_opr);
				_td();
				$i++;
			_tr();
		}
	endtable();
	}
}
startbuttonsarea("right");
addbuttons("zamknij");
endbuttonsarea();
?>
</body>
</html>