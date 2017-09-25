<?php include_once('header.php'); ?>
<body>
<?php include('body_start.php'); ?>
<div id="tiplayer" style="position:absolute; visibility:hidden; z-index:10000;"></div>
<?php 
$komorka='';
$result7a = mysql_query("SELECT up_nazwa FROM $dbname.serwis_komorki WHERE up_id='$sel_up' LIMIT 1", $conn) or die($k_b);
list($komorka)=mysql_fetch_array($result7a);
if ($komorka!='') $komorka = ''.$komorka;
if ($sel_up=='all') { 
	$sql = "SELECT * FROM $dbname.serwis_ewidencja,serwis_komorki WHERE (serwis_komorki.up_id=serwis_ewidencja.ewidencja_up_id) and (serwis_komorki.up_active=1) and (";
	if ($es_m!=1) { $sql = $sql."(serwis_ewidencja.belongs_to=$es_filia) and "; }
	$sql = $sql."(serwis_ewidencja.ewidencja_status>-1) ";
	if ($g==1) { $dddd = Date('Y-m-d');	$sql = $sql." and (serwis_ewidencja.ewidencja_gwarancja_do>='$dddd')";}
	if ($o=='none') { $sql = $sql . " and (serwis_ewidencja.ewidencja_oprogramowanie_id=0) and ((serwis_ewidencja.ewidencja_typ=1) or (serwis_ewidencja.ewidencja_typ=2) or (serwis_ewidencja.ewidencja_typ=18))"; }
	if ($uwagi==1) { $sql = $sql . " and (serwis_ewidencja.ewidencja_uwagi<>'') "; }
	
if ($wybierz!='') $sql.="AND (serwis_komorki.up_id=$wybierz) ";
	
	$sql = $sql.") ORDER BY serwis_ewidencja.ewidencja_up_nazwa, serwis_ewidencja.ewidencja_typ_nazwa"; 
} else { 
	$sql = "SELECT * FROM $dbname.serwis_ewidencja,serwis_komorki WHERE (serwis_komorki.up_id=serwis_ewidencja.ewidencja_up_id) and (serwis_komorki.up_active=1) and (";
	if ($es_m!=1) { $sql = $sql."(serwis_ewidencja.belongs_to=$es_filia) and "; }	
	$sql = $sql."(serwis_ewidencja.ewidencja_up_id='$sel_up') and (serwis_ewidencja.ewidencja_status>-1) ";
	if ($g==1) { $dddd = Date('Y-m-d');	$sql = $sql." and (serwis_ewidencja.ewidencja_gwarancja_do>='$dddd')"; }
	if ($o=='none') { $sql = $sql . " and (serwis_ewidencja.ewidencja_oprogramowanie_id=0) and ((serwis_ewidencja.ewidencja_typ=1) or (serwis_ewidencja.ewidencja_typ=2) or (serwis_ewidencja.ewidencja_typ=18))"; }
	if ($uwagi==1) { $sql = $sql . " and (serwis_ewidencja.ewidencja_uwagi<>'') "; }
	$sql = $sql.") ORDER BY serwis_ewidencja.ewidencja_up_nazwa, serwis_ewidencja.ewidencja_typ_nazwa";
}
$result = mysql_query($sql, $conn) or die($k_b);
$totalrows = mysql_num_rows($result);
$count_rows = $totalrows;
if(empty($page)){ $page = 1; }
if ($showall==0) { $rps=$rowpersite; } else $rps=10000;
$limitvalue = $page * $rps - ($rps);
if ($printpreview==0) { $sql=$sql." LIMIT $limitvalue, $rps"; }
$result = mysql_query($sql, $conn) or die($k_b);
if (mysql_num_rows($result)!=0) {
	if ($komorka=='') { 
		$komorka='widok całego sprzętu'; 
		if ($g==1) $komorka='sprzęt na gwarancji';
	}
	if (($view=='all') && ($o!='none')) pageheader("Ewidencja sprzętu - $komorka (widok SZCZEGÓŁOWY)",1,1);
	if (($view=='simple') && ($o!='none')) pageheader("Ewidencja sprzętu - $komorka (widok PROSTY)",1,1);
	if (($view=='user') && ($o!='none')) pageheader("Ewidencja sprzętu - $komorka (widok UŻYTKOWNIKA)",1,1);
	if ($o=='none') pageheader("Ewidencja sprzętu - sprzęt bez przypisanego oprogramowania",1,1);
	$nowe_view='';
	if ($view=='all') { $nowe_view='simple'; } else $nowe_view='all';

	?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
		
	startbuttonsarea("center");
	if ($printpreview==0) {
		if ($showall==0) {
			echo "<a class=paging href=p_ewidencja.php?action=ewid_all&view=$nowe_view&sel_up=$sel_up&paget=$page&showall=1&ew_action=$ew_action&g=$g&o=$o&uwagi=$uwagi>Pokaż wszystko na jednej stronie</a>";
		} else {
		echo "<a class=paging href=p_ewidencja.php?action=ewid_all&view=$nowe_view&sel_up=$sel_up&page=$paget&showall=0&ew_action=$ew_action&g=$g&o=$o&uwagi=$uwagi>Dziel na strony</a>";	
		}
		echo "| Łącznie: <b>$count_rows pozycji</b>";
		echo "&nbsp;|&nbsp;";
		echo "<a class=paging href=p_ewidencja.php?action=ewid_all&view=$nowe_view&sel_up=$sel_up&page=$page&showall=$showall&ew_action=$ew_action&g=$g&o=$o&uwagi=$uwagi>zmień widok ";
		if ($nowe_view=='all') echo "na szczegółowy"; else echo "na prosty";
		echo "</a>";
		echo "<a class=paging href=p_ewidencja.php?action=ewid_all&view=$nowe_view&sel_up=$sel_up&printpreview=1&page=$page&showall=$showall&ew_action=$ew_action&g=$g&o=$o&uwagi=$uwagi>Wersja do druku</a>";
	}					
	if ($printpreview==1) {
		echo "<a class=nav_normal href=p_ewidencja.php?action=ewid_all&view=$nowe_view&sel_up=$sel_up&printpreview=0&page=$page&showall=$showall&ew_action=$ew_action&g=$g&o=$o&uwagi=$uwagi>Wróć do normalnego widoku</a>";
		echo "&nbsp;<a class=nav_normal onclick='javascript:window.print();'>Drukuj</a>";
	}
	endbuttonsarea();
	
// ************************
	startbuttonsarea("center");
	echo "<form name=komorki action=$PHP_SELF method=GET>";
	hr();
	echo "Pokaż: ";
		echo "<select name=wybierz  onChange='document.location.href=document.komorki.wybierz.options[document.komorki.wybierz.selectedIndex].value'>";
		$sql_lista_up = "SELECT up_id, up_nazwa, pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((serwis_komorki.belongs_to=$es_filia) and (serwis_piony.pion_id=serwis_komorki.up_pion_id) and (serwis_komorki.up_active=1)) ORDER BY serwis_piony.pion_nazwa, serwis_komorki.up_nazwa";
		$wynik_lista_up = mysql_query($sql_lista_up,$conn) or die($k_b);
		echo "<option ";
		if ($wybierz=='') echo "SELECTED ";
		echo "value='$PHP_SELF?view=$view&sel_up=all&printpreview=$printpreview&ew_action=$ew_action&showall=$showall&okresm=01&okresr=$okresr&paget=$page&page=$paget&wybierz=&g=$g'>Wszystkie UP / komórki</option>\n";	
		while (list($temp_upid, $temp_upnazwa, $temp_pionnazwa)=mysql_fetch_array($wynik_lista_up)) {
			echo "<option "; 
			if ($wybierz==$temp_upid) echo "SELECTED ";
			echo "value='$PHP_SELF?view=$view&sel_up=$temp_upid&printpreview=$printpreview&ew_action=$ew_action&showall=$showall&okresm=01&okresr=$okresr&paget=$page&page=$paget&wybierz=$temp_upid&g=$g'>$temp_pionnazwa $temp_upnazwa</option>\n";	
		
		}
		echo "</select>";
		echo "</form>";
	endbuttonsarea();
// ************************
	
	starttable();
	tr_();
		th_colspan(3,'c'); _th();
		if ($view=='simple') { th_colspan(1,'c','Komputetr / Sprzęt'); _th(); } else { th_colspan(3,'c','Komputetr / Sprzęt'); _th(); }
		th_colspan(1,'c','Monitor'); _th();
		th_colspan(1,'c'); _th();
		if ($view=='simple') { th_colspan(1,'c','Drukarka'); _th(); } else { th_colspan(2,'c','Drukarka'); _th(); }
		if ($view!='simple') { th_colspan(1,'c'); _th(); }	
		if ($printpreview==0) { th_colspan(3,'c'); _th(); }
	_tr();
	tr_();
		th_("30;c;LP|;;Rodzaj",$es_prawa);
		if ($view=='simple') { th_(";;Lokalizacja",$es_prawa); } else th_(";;Lokalizacja / nr pokoju<br />Użytkownik",$es_prawa);
		if ($view=='simple') { th_(";;Model",$es_prawa); } else th_(";;Model<br />Numer seryjny",$es_prawa);
		if ($view!='simple') th_(";;Nazwa<br />Adres IP|;;Endpoint",$es_prawa); 
		th_colspan(1,'l','Model');	if ($view!='simple') { echo "<br />Numer seryjny";	} _th();
		th_(";;Nr inwentarzowy",$es_prawa);
		if ($view!='simple') { th_(";;Model<br /><sub>Adres IP drukarki</sub>",$es_prawa); } else { th_(";;Model",$es_prawa); }
		if ($view!='simple') { th_(";;Numer seryjny<br />Numer inwentarzowy",$es_prawa); }
		if ($view!='simple') th_(";;Konfiguracja sprzętu",$es_prawa);
		if ($printpreview==0) th_(";c;Uwagi|;c;Opcje",$es_prawa);
	_tr();
	$i = $page*$rowpersite-$rowpersite;
	while ($dane = mysql_fetch_array($result)) {
		$eid 		= $dane['ewidencja_id'];					$etyp_id	= $dane['ewidencja_typ'];
		$etyp_nazwa	= $dane['ewidencja_typ_nazwa'];				$eup_id		= $dane['ewidencja_up_id'];
		$euser		= $dane['ewidencja_uzytkownik'];			$enrpok		= $dane['ewidencja_nr_pokoju'];
		$enizest	= $dane['ewidencja_zestaw_ni'];				$eknazwa	= $dane['ewidencja_komputer_nazwa'];
		$ekopis		= $dane['ewidencja_komputer_opis'];			$eksn		= $dane['ewidencja_komputer_sn'];
		$ekip		= $dane['ewidencja_komputer_ip'];			$eke		= $dane['ewidencja_komputer_endpoint'];
		$emo		= $dane['ewidencja_monitor_opis'];			$emsn		= $dane['ewidencja_monitor_sn'];
		$edo		= $dane['ewidencja_drukarka_opis'];			$edsn		= $dane['ewidencja_drukarka_sn'];
		$edni		= $dane['ewidencja_drukarka_ni'];			$eu			= $dane['ewidencja_uwagi'];
		$es			= $dane['ewidencja_status'];				$eo_id		= $dane['ewidencja_oprogramowanie'];
		$emoduser 	= $dane['ewidencja_modyfikacja_user'];		$emoddata	= $dane['ewidencja_modyfikacja_date'];
		$ekonf		= $dane['ewidencja_konfiguracja'];			$egwarancja	= $dane['ewidencja_gwarancja_do'];
		$egwarancjakto	= $dane['ewidencja_gwarancja_kto'];	$drukarkapow	= $dane['ewidencja_drukarka_powiaz_z'];
		tbl_tr_highlight($i);
		$i++;
		td("30;c;<a class=normalfont href=# title=' $eid '>$i</a>");
		td_(";");
			$result77 = mysql_query("SELECT rola_id,rola_nazwa FROM $dbname.serwis_slownik_rola WHERE rola_id=$etyp_id LIMIT 1", $conn) or die($k_b);
			list($rolaid,$rolanazwa)=mysql_fetch_array($result77);
			$ok=1;
			$display='gt';
			if ($printpreview==0) { 
				if (($display=='g') || ($display=='gt')) {
					if ($rolanazwa=="Komputer") { echo "<img class=imgoption src=img/komputer.gif border=0 align=absmiddle title=' Komputer ' width=16 width=16>"; $ok=1; }
					if ($rolanazwa=="Czytnik") { echo "<img class=imgoption src=img/barcode.gif border=0 align=absmiddle title=' Czytnik ' width=16 width=16>"; $ok=1; }
					if ($rolanazwa=="Serwer") { echo "<img class=imgoption src=img/serwer.gif border=0 align=absmiddle title=' Serwer ' width=16 width=16>"; $ok=1; }
					if ($rolanazwa=="Drukarka") { echo "<img class=imgoption src=img/drukarka.gif border=0 align=absmiddle title=' Drukarka ' width=16 width=16>"; $ok=1; }
					if ($rolanazwa=="Router") { echo "<img class=imgoption src=img//router.gif border=0 align=absmiddle title=' Router ' width=16 width=16>"; $ok=1; }
					if (($rolanazwa=="Switch") || ($rolanazwa=="Hub")) { echo "<img class=imgoption src=img/switch.gif border=0 align=absmiddle title=' Switch ' width=16 width=16>"; $ok=1; }
					if ($rolanazwa=="Notebook") { echo "<img class=imgoption src=img//notebook.gif border=0 align=absmiddle title=' Notebook ' width=16 width=16>"; $ok=1; }
					if ($rolanazwa=="UPS") { echo "<img class=imgoption src=img/ups.gif border=0 align=absmiddle title=' UPS ' width=16 width=16>"; $ok=1; }
				}
			}
			if (($ok==0) || ($display=='gt')) echo "&nbsp;$rolanazwa";
			if ($display=='t') echo "&nbsp;$rolanazwa";
		_td();
		td_(";");
			$result7 = mysql_query("SELECT up_nazwa FROM $dbname.serwis_komorki WHERE (up_id='$eup_id') and (belongs_to=$es_filia) LIMIT 1", $conn) or die($k_b);
			list($upnazwa)=mysql_fetch_array($result7);
			
			$sql_up = "SELECT up_id, up_pion_id FROM $dbname.serwis_komorki WHERE (up_nazwa='$upnazwa') and (belongs_to=$es_filia) LIMIT 1";
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
	
			echo "<a class=normalfont title=' Szczegółowe informacje o $temp_pion_nazwa $upnazwa ' onclick=\"newWindow_r(700,600,'p_komorka_szczegoly.php?id=$eup_id')\" href=#>$temp_pion_nazwa $upnazwa</a>";	
			if ($view!='simple') { if ($enrpok!='') { echo "&nbsp;/&nbsp;$enrpok"; }
				if ($euser!='') { echo "<br />$euser";}
			}
		_td();
		td_(";");
			if (($rolaid==30) || ($rolaid==29) || ($rolaid==4) || ($rolaid==11) || ($rolaid==10) || ($rolaid==17)) {
				$ekopis = $eknazwa;
				$eknazwa = '';
			}
			
			echo "$ekopis";
			if ($view!='simple') { echo "<br />$eksn"; }
		_td();
		if ($view!='simple') { 
			if ($rolanazwa!='Drukarka') { td(";;".$eknazwa."<br />".$ekip.""); } else td(";;");
			td_(";;");
				if ((strtoupper($eknazwa)!=strtoupper($eke)) && ($eke!='')) echo "<a title='Nazwa endpointa niezgodna z nazwą komputera'>&nbsp;*&nbsp;</a></b>";
				if ((strtoupper($eknazwa)!=strtoupper($eke)) && ($eke=='')) echo "<a title='Brak zainstalowanego endpointa'>&nbsp;!&nbsp;</a></b>";
			if ($ekip!='') { 
				echo "<a title=' Sprawdź czy działa endpoint na komputerze o adresie IP : $ekip '  onclick=\"newWindow_r(800,600,'p_endpoint.php?ip=$ekip')\">$eke</a>";
			} else echo "&nbsp;";
			_td();
		}
		td_(";");
			echo "$emo";
			if ($view!='simple') echo "<br />$emsn";
		_td();
		td(";;".$enizest."");
		$dd = Date('d');
		$mm = Date('m');
		$rr = Date('Y');
		$nazwa_urzadzenia_=$edo;
		$sn_urzadzenia_=$edsn;
		$ni_urzadzenia_=$edni;
		// sprawdzenie czy drukarka jest podpiętą do jakiegoś komputera
		if ($rolanazwa=='Drukarka') {
			$result_d1 = mysql_query("SELECT ewidencja_komputer_opis, ewidencja_komputer_ip FROM $dbname.serwis_ewidencja WHERE ewidencja_id=$drukarkapow LIMIT 1", $conn) or die($k_b);
			$count_rows = mysql_num_rows($result_d1);
			if ($count_rows>0) list($nazwa_k,$ip_k)=mysql_fetch_array($result_d1);
		} else {
			// sprawdzenie czy komputer ma podpiętą drukarkę
			$result_d2 = mysql_query("SELECT ewidencja_drukarka_opis, ewidencja_drukarka_sn FROM $dbname.serwis_ewidencja WHERE ewidencja_drukarka_powiaz_z=$eid LIMIT 1", $conn) or die($k_b);
			$count_rows_k = mysql_num_rows($result_d2);
			if ($count_rows_k>0) list($nazwa_d,$sn_d)=mysql_fetch_array($result_d2);	
		}
		td_(";");
			if (($drukarkapow>0) and ($rolanazwa=='Drukarka')) { 
				echo "<a title=' Drukarka jest podłączona do komputera $nazwa_k ($ip_k) '>";
				echo "$edo";	
				echo "</a>";
			} else {
				echo "$edo";
				if (($ekip!='') && ($rolanazwa=='Drukarka') && ($view!='simple')) echo "<br /><sub>$ekip</sub>";
			}
			if ($rolanazwa!='Drukarka') {
				if (($count_rows_k>0) && ($printpreview==0)) {
					echo "<a title=' Do tego komputera podłączona jest drukarka $nazwa_d ($sn_d) '>";
					echo "<input class=imgoption type=image align=absmiddle src=img/link.gif onclick=\"newWindow_r(620,600,'p_ewidencja_szczegoly.php?id=$eid&ew_action=$ew_action')\">";
					echo "</a>";
				}
			}
			if (($drukarkapow>0) && ($printpreview==0)) {
				echo "<input class=imgoption type=image align=absmiddle src=img/link.gif onclick=\"newWindow_r(620,600,'p_ewidencja_szczegoly.php?id=$eid&ew_action=$ew_action')\">";
			}
		_td();
		if ($view!='simple') td(";;".$edsn."<br />".$edni."");
		if ($view!='simple') { 
			td_(";");
			if ($ekonf=='0') { echo "&nbsp;"; } else echo "$ekonf";
			_td();
		}
		if ($printpreview==0) {
			td_(";c");
			if ($eu!='') {
				echo "<a title=' Czytaj uwagi '><input class=imgoption type=image src=img/comment.gif  onclick=\"newWindow(480,265,'p_ewidencja_uwagi.php?id=$eid')\"></a>";
			}
			echo "<a title=' Edytuj uwagi '><input class=imgoption type=image src=img/edit_comment.gif onclick=\"newWindow(480,260,'e_ewidencja_uwagi.php?id=$eid')\"></a>";
			$dddd = Date('Y-m-d');
			if (($printpreview==0) && ($egwarancja!='0000-00-00') && ($egwarancja>=$dddd)) { 
				echo "<a title=' Gwarancja do $egwarancja '><input class=imgoption type=image src=img/gwarancja.gif ";
				if ($egwarancjakto>-1) echo "onclick=\"newWindow(600,250,'p_firma_serwisowa_szczegoly.php?id=$egwarancjakto')\"";
				echo "></a>";
			}
			_td();
			td_(";c");
				if ($ew_action=='move') { echo "<a title=' Przesunięcie sprzętu '><input class=imgoption type=image src=img/przesuniecie.gif onclick=\"newWindow_r(700,586,'ew_przesuniecie.php?id=$eid')\"></a>"; }
				if (($ew_action=='change') && (($etyp_nazwa=='Komputer') || ($etyp_nazwa=='Serwer') || ($etyp_nazwa=='Notebook'))) {
					echo "<a title=' Remont sprzętu '><input class=imgoption type=image src=img//remont.gif  onclick=\"newWindow_r(700,586,'ew_remont.php?id=$eid')\"></a>";
				}
				if ($ew_action=='delete') {
					echo "<a title=' Usunięcie sprzętu z ewidencji '><input class=imgoption type=image src=img/likwidacja.gif  onclick=\"newWindow_r(700,586,'ew_usuniecie.php?id=$eid')\"></a>";
				}
				if (($ew_action!='move') && ($ew_action!='delete') && ($ew_action!='change')) {
					$accessLevels = array("0","1","9");
					if (array_search($es_prawa, $accessLevels)>-1) {
							echo "<a title=' Popraw dane o wybranym sprzęcie '><input class=imgoption type=image src=img/edit.gif  onclick=\"newWindow_r(820,600,'e_ewidencja.php?id=$eid')\"></a>";
					}
					if (array_search($es_prawa, $accessLevels)>-1) {
							echo "<a title='Popraw wszystkie dane o wybranym sprzęcie (f-cja tylko dla Administratorów)'><input class=imgoption type=image src=img/edita.gif onclick=\"newWindow_r(820,600,'e_ewidencja.php?id=$eid&edittype=admin')\"></a>";
					}
				
				list($mid, $es)=mysql_fetch_array(mysql_query("SELECT naprawa_id, naprawa_status FROM $dbname.serwis_naprawa WHERE (naprawa_ew_id=$eid)  ORDER BY naprawa_data_pobrania DESC LIMIT 1",$conn));
				//echo "SELECT naprawa_id, naprawa_status FROM $dbname.serwis_naprawa WHERE (naprawa_ew_id=$eid)  ORDER BY naprawa_data_pobrania DESC LIMIT 1";
				if ($es==0) $es=10;
				//echo ">".$mid." status: ".$ms."";
				
					if ($es=='-1') { echo "<a title=' Sprzęt pobrany od klienta ' href=main.php?action=npus&id=$mid&cs=$es><img class=imgoption src=img/snapraw_p.gif border=0 width=16 width=16></a>"; }
					
					if ($es==0) { echo "<a title=' Sprzęt jest naprawiany we własnym zakresie ' href=main.php?action=nwwz&id=$mid&cs=$es><img class=imgoption src=img/w_naprawie_wz.gif border=0 width=16 width=16></a>"; }
					
					if ($es==1) { echo "<a title=' Sprzęt jest w naprawie w serwisie zewnętrznym ' href=main.php?action=npswsz&id=$mid&cs=$es><img class=imgoption src=img/w_naprawie_sz.gif border=0 width=16 width=16></a>"; }
					
					if ($es==2) { echo "<a title=' Sprzęt jest w naprawie w serwisie lokalnym ' href=main.php?action=nsnrl&id=$mid&cs=$es><img class=imgoption src=img/w_naprawie_sl.gif border=0 width=16 width=16></a>"; }
					
					if ($es==3) { 
						//echo ">$mid<";
						
						echo "<a title=' Sprzęt naprawiony na stanie ' href=p_naprawy_zakonczone.php?id=$mid&cs=$es><img class=imgoption src=img/snapraw_ok.gif border=0 width=16 width=16></a>"; 
					}
					
					if ($es==5) { 
						//echo "<a title=' Sprzęt oddany do klienta ' href=p_naprawy_wszystko.php?id=$mid&cs=$es><img class=imgoption src=img/snapraw_ok.gif border=0 width=16 width=16></a>"; 
						
						//echo "<a title=' Sprzęt naprawiony na stanie ' href=p_naprawy_zakonczone.php?id=$mid&cs=$es><img class=imgoption src=img/snapraw_ok.gif border=0 width=16 width=16></a>"; 
					}					
					if ($es==7) { echo "<a title=' Sprzęt wycofany z serwisu  ' href=main.php?action=nsw&id=$mid><img class=imgoption src=img//wycofaj_z_serwisu.gif border=0 width=16 width=16></a>"; }
				
					$accessLevels = array("9");
					if (array_search($es_prawa, $accessLevels)>-1) {
						echo "<a title=' Usuń sprzęt z ewidencji (nierejestrowane) '><input class=imgoption type=image src=img/delete.gif onclick=\"newWindow(600,170,'u_ewidencja.php?id=$eid')\"></a>"; 
					}
					if (($rolanazwa=="Komputer") || ($rolanazwa=="Serwer") || ($rolanazwa=="Notebook")) {
						$wynik_czyjest = mysql_num_rows(mysql_query("SELECT oprogramowanie_id FROM $dbname.serwis_oprogramowanie WHERE oprogramowanie_ewidencja_id=$eid",$conn));
						if ($wynik_czyjest>0) { echo "<a title=' Pokaż oprogramowanie zainstalowane na tym sprzęcie '><input class=imgoption type=image src=img/software.gif onclick=\"newWindow_r(600,600,'p_oprogramowanie.php?id=$eid')\"></a>";
						} else {
							echo "<a title=' Pokaż oprogramowanie zainstalowane na tym sprzęcie '><input class=imgoption type=image src=img/software_none.gif onclick=\"newWindow_r(600,600,'p_oprogramowanie.php?id=$eid')\"></a>";
						}
					}
					echo "<a title=' Szczegółowe informacje o sprzęcie i oprogramowaniu '><input class=imgoption type=image src=img/detail.gif  onclick=\"newWindow_r(620,600,'p_ewidencja_szczegoly.php?id=$eid&ew_action=$ew_action')\"></a>";
					if (($rolanazwa=="Komputer") || ($rolanazwa=="Serwer") || ($rolanazwa=="Notebook")) {
						$nazwa_urzadzenia_=$ekopis;
						$sn_urzadzenia_=$eksn;
						$ni_urzadzenia_=$enizest;
					}
					if ($rolanazwa=="Drukarka") {
						$nazwa_urzadzenia_=$edo;
						$sn_urzadzenia_=$edsn;
						$ni_urzadzenia_=$edni;
					}
				//	if ($rolanazwa!="Drukarka") {
						//echo "<a title=' Generuj protokół dla wybranego sprzętu '><input class=imgoption type=image src=img/print.gif onclick=\"newWindow_r(700,595,'utworz_protokol.php?dzien=$dd&miesiac=$mm&rok=$rr&c_1=&c_2=&c_3=&c_4=&c_5=&c_6=&c_7=&c_8=&up=".urlencode($upnazwa)."&nazwa_urzadzenia=".urlencode($nazwa_urzadzenia_)."&sn_urzadzenia=".urlencode($sn_urzadzenia_)."&ni_urzadzenia=".urlencode($ni_urzadzenia_)."&opis_urzadzenia=&wykonane_czynnosci=&uwagi=&imieinazwisko=&readonly=0&findpion=1&upid=$eup_id')\"></a>"; 
					//}
					//if (($rolanazwa=='Drukarka') && ($printpreview==0)) {
				//echo "<a title=' Generuj protokół dla drukarki $nazwa_urzadzenia_ o numerze seryjnym $sn_urzadzenia_ '><input class=imgoption type=image src=img/print.gif align=absmiddle onclick=\"newWindow_r(700,595,'utworz_protokol.php?dzien=$dd&miesiac=$mm&rok=$rr&c_1=&c_2=&c_3=&c_4=&c_5=&c_6=&c_7=&c_8=&up=".urlencode($upnazwa)."&nazwa_urzadzenia=".urlencode($nazwa_urzadzenia_)."&sn_urzadzenia=".urlencode($sn_urzadzenia_)."&ni_urzadzenia=".urlencode($ni_urzadzenia_)."&opis_urzadzenia=&wykonane_czynnosci=&uwagi=&imieinazwisko=&readonly=0&findpion=1&upid=$eup_id')\"></a>"; 
			//}
				}
			_td();
		}
		_tr();
	}
endtable();
include_once('paging_end.php');
} else {
			if (($g!='1') && ($komorka!='')) {
				errorheader("Nie ma żadnego sprzętu na ewidencji ".$komorka."");
			}
			if (($g!='1') && ($komorka=='')) {
				errorheader("Nie ma żadnego sprzętu na ewidencji");
			}
			if ($g=='1') {
				errorheader("Nie ma żadnego sprzętu na gwarancji w ewidencji");
			}
		}
startbuttonsarea("right");
oddziel();
echo "<span style='float:left'>";
addbuttons("wstecz");
echo "</span>";
$accessLevels = array("0","1","9");
if (array_search($es_prawa, $accessLevels)>-1) { if ($g!='1') addbuttons("dodajsprzet"); }
addlinkbutton("'Utwórz nowy widok użytkownika'","p_ewidencja_uzytkownika.php");
if (($sel_up!='all') && ($allowback==1)) {
	addlinkbutton("'Wróć do przeglądania komórek'","main.php?action=ewid_choose&sel_up=$sel_up");
}
if ($allowback==2) { addbuttons("wstecz");}
addbuttons("start");
endbuttonsarea();
include('body_stop.php');
?>

<script>HideWaitingMessage();</script>

</body>
</html>