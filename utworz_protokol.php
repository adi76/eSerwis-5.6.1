<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<html>
<head>
<title><?php echo "$nazwa_aplikacji"; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>

<?php
//echo ">".$_REQUEST[from];
//echo "wykonaj_naprawy_przyjecie: >$_SESSION[wykonaj_naprawy_przyjecie]<";
//echo "source: <b>$source</b><br />";
//print_r($_REQUEST);
//echo "ewid_id: <b>$_REQUEST[ewid_id]</b><br />";
//print_r($_REQUEST);
/*
echo "wykonaj_wymiane_podzespolu: ".$_SESSION[wykonaj_wymiane_podzespolu]."<br />";
echo "source: <b>$source</b><br />";
echo "hd_nr: <b>$hd_nr</b><br />";
echo "ewid_id: <b>$ewid_id</b><br />";
echo "sell_towar: <b>$sell_towar</b><br />";
echo "wykonaj sprzedaz: <b>$_SESSION[wykonaj_sprzedaz]</b><br />";
*/
//echo "source: <b>$source</b><br />";

if ($numeruj_protokoly==1) { echo "<body onLoad='document.forms[0].elements[0].focus();'>"; } else { echo "<body>"; }

if ($wylacz_tworzenie_protokolow_z_menu_glownego==1) {

	$kierownik = ($kierownik_nr==$es_nr);
	if (($kierownik==false)  && ($_REQUEST[source]=='')) {
		errorheader('Dostęp do tej części systemu jest zablokowany. Protokoły należy generować z bazy Helpdesk');
		startbuttonsarea("right");
		addbuttons('zamknij');
		endbuttonsarea();	
		echo "</body>";
		exit;
	}
}

$czynnosc1 = 'Pobranie do naprawy uszkodzonego sprzętu';
$czynnosc2 = 'Przekazanie sprzętu serwisowego';
$czynnosc3 = 'Zwrot naprawionego sprzętu';
if ($source=='naprawy-wycofaj') $czynnosc3 = 'Zwrot sprzętu';
$czynnosc4 = 'Pobranie sprzętu serwisowego';
$czynnosc5 = 'Przekazanie sprzętu do naprawy - serwisu';
$czynnosc6 = 'Odbiór sprzętu z naprawy - serwisu';
$czynnosc7 = 'Wymiana części / remont sprzętu';
$czynnosc8 = 'Przekazanie zamówionego sprzętu';

$current_year 	= Date('Y');
$current_month 	= Date('m');
$numeracja_miesieczna = 0;

if (!isset($pnr)) {
	//znajdź kolejny numer z bazy
	$sql_max_wynik 	= mysql_query("SELECT protokol_nr FROM $dbname.serwis_protokoly_historia WHERE ((belongs_to=$es_filia) and (protokol_m='$current_month') and (protokol_r='$current_year')) ORDER BY protokol_id DESC LIMIT 1", $conn);
	$count_rows 	= mysql_num_rows($sql_max_wynik);
	list($temp_p_nr)=mysql_fetch_array($sql_max_wynik);
	if ($count_rows>0) {
	  $temp_pnr = substr($temp_p_nr,0,strpos($temp_p_nr,'/'));
	  $pnr 		= $temp_pnr+1;
	} else $pnr=1;
} else {
	
}

//echo $_REQUEST[zid]." | edit zestaw=".$_REQUEST[edit_zestaw];
//print_r($_REQUEST);
//echo "from: $_REQUEST[from]<br />nr zgl: $_REQUEST[hd_nr]";
//echo "$source<br />";
//echo "$_REQUEST[trodzaj]";
//echo "ewid: $_REQUEST[ewid_id]<br />sz: $_REQUEST[szid]<br />status:$_REQUEST[status]";

$current_filia	= $es_skrot;

if (($dzien=='') || ($miesiac=='') || ($rok=='')) {
	$dzien		= Date('d');
	$miesiac	= Date('m');
	$rok		= Date('Y');
	$pdata=$rok.'-'.$miesiac.'-'.$dzien;
} else {
	$pdata=$_REQUEST[rok].'-'.$_REQUEST[miesiac].'-'.$_REQUEST[dzien];
}

if ($source=='towary-sprzedaz') {
	$pdata=$_REQUEST[rok].'-'.$_REQUEST[miesiac].'-'.$_REQUEST[dzien];
	
	if ($tdata!='') $pdata=$tdata;
	
	if ($pdata=='--') {
		$dzien		= Date('d');
		$miesiac	= Date('m');
		$rok		= Date('Y');
		$pdata=$rok.'-'.$miesiac.'-'.$dzien;
	}
} else {
	//$pdata = $_REQUEST[tdata];
	$pdata=$_REQUEST[rok].'-'.$_REQUEST[miesiac].'-'.$_REQUEST[dzien];
	
	if ($pdata=='--') {
		$dzien		= Date('d');
		$miesiac	= Date('m');
		$rok		= Date('Y');
		$pdata=$rok.'-'.$miesiac.'-'.$dzien;
	}	
}

if (($miesiac=='') && ($rok=='')) {
	$format_number	= $current_filia.'/'.$current_month.'/'.$current_year;
} else {
	$format_number	= $current_filia.'/'.$miesiac.'/'.$rok;
}

if ($_REQUEST[pnr]=='') {
	$_SESSION[protokol_dodany_do_bazy]=0;
	$_SESSION[unr_session]='';
}

//echo "odswiez = $odswiez<br />";
//echo "pnr = $pnr<br />";
//echo $source;

// tryb debugowania :
/*
echo "source: $source<br />";
echo "tdata: $_REQUEST[tdata]<br />";
echo "pdata: $_REQUEST[pdata]<br />";
echo "nierobzapytan : $_REQUEST[nierobzapytan]<br />";
echo "tstatus : $_REQUEST[tstatus1]<br/>";
echo "tup : $_REQUEST[tup]<br/>";
echo "unr: $_REQUEST[unr]<br />";
echo "antyF5: $_REQUEST[antyF5]<br />";
echo "Protokół już dodany do bazy : $protokol_dodany_do_bazy<br/>";
echo "numer_id_dopisanego_do_tabeli_serwis_historia : $_SESSION[numer_id_dopisanego_do_tabeli_serwis_historia]<br />";
echo "id naprawy : <b>$_REQUEST[id]</b><br />";
echo "PS: $_REQUEST[ps]<br />";
echo "SZ: $_REQUEST[sz]<br />";
echo "serwisowy : <b>$_REQUEST[serwisowy]</b><br />";
echo "obzp : <b>$_REQUEST[obzp]</b><br />";
echo "popraw : <b>$_REQUEST[popraw]</b><br />";
echo "tnazwa : <b>$_REQUEST[tnazwa]</b><br />";
echo "tsn : <b>$_REQUEST[tsn]</b><br />";

*/
$dopisek = '';
if ($kierownik_nr==$es_nr) $dopisek = "<br /><font color=red>(wersja dla kierowników)</font>";

// echo "$_REQUEST[new_upid]";

pageheader("Utwórz nowy protokół $dopisek");
?><script>ShowWaitingMessage();</script><?php 

$result_sr = mysql_query("SELECT sr_rok, sr_miesiac FROM $dbname.serwis_sprzedaz_raport WHERE belongs_to=$es_filia ORDER BY sr_id DESC LIMIT 1",$conn) or die($k_b);
if (mysql_num_rows($result_sr)>0) list ($sr_r,$sr_m)=mysql_fetch_array($result_sr);
if ($sr_m<10) $sr_m = '0'.$sr_m;	
$today1 = Date('Y-m');
$closedmonth = ''.$sr_r.'-'.$sr_m.'';
if ($closedmonth==$today1) echo "<h5 style='padding:5px; font-weight:normal;'>Sprzedaż na miesiąc <b>$sr_r-$sr_m</b> została zamknięta</h5>";

echo "<table align=center>";
echo "<form name=protokol action=utworz_protokol_generuj.php method=GET>";

// wygenerowanie nr unikalnego, który ma zapobiec klawiszowi F5 w wygenerowanym protokole
//echo "---$_REQUEST[antyF5]+++";

if (!isset($_REQUEST[antyF5])) {
	$antyF5key='';
	$litery=array('k','l','m','n','o','p','q','r','s','t');
	for ($q=1;$q<26;$q++) { $antyF5key.= rand(0,9); $antyF5key.=$litery[rand(0,9)]; }
//	ECHO "NOWY ANTY F5 : $antyF5key";
} else {
	//echo "Stary antyF5 : $_REQUEST[antyF5]";
}

// koniec generowania antyF5
tr_();
	td_(";;");
		echo "<div>";
		// 17.01.2008 - wyłączenie numeracji protokołów do czasu ustalenia pewnych rzeczy

		if ($numeruj_protokoly==1) {
			echo "<input type=hidden name=pnr value='$pnr/$format_number'>";
		} else {
			echo "<input style='text-align:right; font-size:9px; font-weight:bold;' type=hidden name=pnr size=3 maxlength=5 onkeypress='return handleEnter(this, event);'>";
		}
		
		if ($_REQUEST[hd_zgl_nr]=='0') {
			echo "Numer zgłoszenia Helpdesk: <b>brak powiązania</b>";
			echo "<input type=hidden id=hd_zgl_nr name=hd_zgl_nr value='0'>";
		} 
		$t = 0;
		if ($_REQUEST[hd_zgl_nr]=='') {
			echo "Numer zgłoszenia Helpdesk: <input class=wymagane type=text id=hd_zgl_nr name=hd_zgl_nr value='' size=10 maxlength=10 onkeypress='return handleEnter(this, event);'>";	$t=1;	
		}
		
		if ($_REQUEST[hd_zgl_nr]>0) {
			echo "Numer zgłoszenia Helpdesk: <b>$_REQUEST[hd_zgl_nr]</b>";
			echo "<input type=hidden id=hd_zgl_nr name=hd_zgl_nr value='$_REQUEST[hd_zgl_nr]'>";
		}

		if ($_REQUEST[hd_zgl_nr]>0) {
			$result554 = mysql_query("SELECT zgl_poczta_nr FROM $dbname_hd.hd_zgloszenie WHERE (zgl_nr=$_REQUEST[hd_zgl_nr]) LIMIT 1", $conn) or die($k_b);
			list($hadim_nr)=mysql_fetch_array($result554);
			
			if ($hadim_nr>0) {
				//if ($t==1) 
				echo "<br />";
				echo "Numer zgłoszenia Landek: <b>$hadim_nr</b>";
				echo "<input type=hidden id=hadim_nr name=hadim_nr value='$hadim_nr'>";
			}
		}
		
		echo "</div>";
	_td();
	td_(";;");
		startbuttonsarea("right");
			echo "Data utworzenia:&nbsp;";
			echo "<input style='text-align:center; font-size: 9px;' type=text id=pdata name=pdata size=10 maxlength=10 value='$pdata' onkeypress='return handleEnter(this, event);'>";
			echo "&nbsp;<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif align=top width=16 height=16 border=0></a>";
			if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('pdata').value='".Date('Y-m-d')."'; return false;\">";
		endbuttonsarea();
	_td();
_tr();
tr_();
	td_(";;");
	_td();
	td_(";;");
	_td();
_tr();
endtable();
$accessLevels = array("9"); 
if(array_search($es_prawa, $accessLevels)>-1) $state='';
$state=$_REQUEST[state];

//echo $source;

echo "<input type=hidden name=dzien value='$_REQUEST[dzien]'>";
echo "<input type=hidden name=miesiac value='$_REQUEST[miesiac]'>";
echo "<input type=hidden name=rok value='$_REQUEST[rok]'>";
echo "<input type=hidden name=state value='$state'>";
echo "<input type=hidden name=source value='$source'>";
echo "<input type=hidden name=id value='$_REQUEST[id]'>";
echo "<input type=hidden name=part value='$_REQUEST[tnazwa]'>";
echo "<input type=hidden name=tmodel value='$_REQUEST[tmodel]'>";

//if ((($_REQUEST[sz]==0) || ($_REQUEST[sz]=='')) && ($source=='naprawy-zwrot')) {	$_REQUEST[c_4] = ''; }
if (($_REQUEST[sz]==0) && ($source=='naprawy-przyjecie')) { if ($_REQUEST[view]!=1) $_REQUEST[c_2] = ''; }		

if (($_REQUEST[trodzaj]=='Towar') && ($source=='towary-sprzedaz')) { 
//	$_REQUEST[c_7]='on'; //$_REQUEST[c_4]='';
//	$_REQUEST[c_8]='off';
}
if (($_REQUEST[trodzaj]=='Usługa') && ($source=='towary-sprzedaz') && ($_REQUEST[pn]!='')) { 
	$_REQUEST[c_6]='on';
	$wylacz_c_6 = 1;
	//$_REQUEST[c_4]='';
} else $wylacz_c_6 = 0;

if (($source=='naprawy-zwrot') && (($_REQUEST[sz]!='0') && ($_REQUEST[sz]!=''))) { 
	$_REQUEST[c_4]='on';
	$wylacz_c_4 = 1;
} 

if (($source=='naprawy-zwrot') && (($_REQUEST[sz]=='') || ($_REQUEST[sz]=='0')) && ($view!=1)) {
		$wylacz_c_4 = 0;
		$_REQUEST[c_4]='off';
	}

if (($_REQUEST[trodzaj]=='Usługa') && ($source=='towary-sprzedaz') && ($_REQUEST[sz]!=0)) { 
	//$_REQUEST[c_4]='on'; 
}

if (($_REQUEST[obzp]=='1') && ($source=='towary-sprzedaz')) { $_REQUEST[c_4]=''; }

if (($_REQUEST[serwisowy]=='TAK') && ($source=='naprawy-wycofaj')) { $_REQUEST[c_4]=''; }
if (($_REQUEST[serwisowy]=='NIE') && ($source=='naprawy-wycofaj')) { $_REQUEST[c_4]='on'; }

//echo ">>>>".$_REQUEST[c_4];

echo "<table cellpadding=1 cellspacing=1 align=center>";
tr_();
	td("110;r;<legend>Czynność</legend>");
	td_(";;");		
		echo "<input class=border0 type=checkbox id=c_1 name=c_1 ";
		if ($_REQUEST[c_1]=='on') { echo "checked=checked"; echo " value=on "; } 
		if (($state=='empty') || ($source=='towary-sprzedaz')) echo " disabled";
		echo " onkeypress='return handleEnter(this, event);'>";
		echo "<a href=# class=normalfont id=c_1a ";
		if ($state!='empty') echo "onclick=labelClick(document.protokol.c_1)";		
		echo ">$czynnosc1</a>";		
		//if ($state=='empty') echo "&nbsp;<span><i><font color=red>(opcja niedostępna z poziomu tworzeniaa nowego protokołu)</font></i></span>";
		if ($source=='naprawy-przyjecie') {
			echo "<input type=hidden name=c_1 checked=checked value=on>";
		}				
	_td();
_tr();
tr_();
	td("110;r;");
	td_(";;");
		echo "<input class=border0 type=checkbox id=c_2 name=c_2 ";
		if ($_REQUEST[c_2]=='on') { echo "checked=checked"; echo " value=on ";} 
		if (($state=='empty') || ($source=='towary-sprzedaz')) echo " disabled";
		echo " onkeypress='return handleEnter(this, event);'>";
		echo "<a href=# class=normalfont id=c_2a ";
		if ($state!='empty') echo "onclick=labelClick(document.protokol.c_2)";
		echo ">$czynnosc2</a>";
		//if ($state=='empty') echo "&nbsp;<span><i><font color=red>(opcja niedostępna z poziomu tworzeniaa nowego protokołu)</font></i></span>";
		if ($source=='magazyn-pobierz') {
			echo "<input type=hidden name=c_2 checked=checked value=on>";
		}
		if (($source=='naprawy-przyjecie') && ($_REQUEST[c_2]!='')) {
			echo "<input type=hidden name=c_2 checked=checked value=on>";
		}		
		_td();
_tr();
tr_();
	td("110;r;");
	td_(";;");
		echo "<input class=border0 type=checkbox id=c_3 name=c_3 ";
		if ($_REQUEST[c_3]=='on') { echo "checked=checked"; echo " value=on ";} 
		if (($state=='empty') || ($source=='towary-sprzedaz')) {
			if ($_REQUEST[zid]!='') {
			
			} else {
				echo " disabled";
			}
		}
		echo " onkeypress='return handleEnter(this, event);'>";
		echo "<a href=# class=normalfont id=c_3a ";
		if ($state!='empty') echo "onclick=labelClick(document.protokol.c_3)";
		echo ">$czynnosc3</a>";
		//if ($state=='empty') echo "&nbsp;<span><i><font color=red>(opcja niedostępna z poziomu tworzeniaa nowego protokołu)</font></i></span>";		_td();
		if ($source=='naprawy-wycofaj') {
			echo "<input type=hidden name=c_3 checked=checked value=on>";
		}		
_tr();
tr_();
	td("110;r;");
	td_(";;");
	
		echo "<input class=border0 type=checkbox id=c_4 name=c_4 ";	
		
		if (($source=='naprawy-wycofaj') && ($_REQUEST[serwisowy]=='NIE')) {
			//$_REQUEST[c_4]='';
			echo "checked=checked";
			echo " value=on ";
		}
		
		if ($source=='magazyn-zwrot') {
			$_REQUEST[c_4]='on';
			echo "checked=checked";
			echo " value=on ";
		}
		
		if ($source=='naprawy-zwrot') {
			if ($_REQUEST[c_4]=='on') { echo "checked=checked"; echo " value=on ";} 
		}
		
		if (($state=='empty') || ($source=='towary-sprzedaz')) echo " disabled";
		echo " onkeypress='return handleEnter(this, event);'>";
		echo "<a href=# class=normalfont id=c_4a ";
		if ($state!='empty') echo "onclick=labelClick(document.protokol.c_4)";
		echo ">$czynnosc4</a>";	
		//if ($state=='empty') echo "&nbsp;<span><i><font color=red>(opcja niedostępna z poziomu tworzeniaa nowego protokołu)</font></i></span>";
		if ($source=='magazyn-zwrot') {
			echo "<input type=hidden name=c_4 checked=checked value=on>";
		}	
		if ($source=='naprawy-zwrot') {
			echo "<input type=hidden name=c_4 value='$_REQUEST[c_4]'>";
		}
		if (($source=='naprawy-wycofaj') && ($_REQUEST[serwisowy]=='NIE')) {
			echo "<input type=hidden name=c_4 checked=checked value=on>";
		} 
		
		if (($source=='towary-sprzedaz') && ($_REQUEST[sz]!='0') && ($_REQUEST[obzp]!='1')) {
			//echo "<input type=hidden name=c_4 checked=checked value=on>";
		}
		
	_td();
_tr();
tr_();
	td("110;r;");
	td_(";;");
		echo "<input class=border0 type=checkbox id=c_5 name=c_5 ";
		if ($_REQUEST[c_5]=='on') { echo "checked=checked"; echo " value=on ";} 
		if (($state=='empty') && ($nowy!='1')) echo " disabled";
		if (($state=='empty') || ($source=='towary-sprzedaz')) echo " disabled";
		echo " onkeypress='return handleEnter(this, event);'>";
		echo "<a href=# class=normalfont id=c_5a ";
		if (($state!='empty') || ($nowy=='1')) echo "onclick=labelClick(document.protokol.c_5)";
		echo ">$czynnosc5</a>";
	_td();
_tr();
tr_();
	td("110;r;");
	td_(";;");
		echo "<input class=border0 type=checkbox id=c_6 name=c_6 ";
		
		if ($_REQUEST[disableall]==1) echo " disabled ";
		
		if ($_REQUEST[c_6]=='on') { echo "checked=checked"; echo " value=on ";} 
		
		if (($state=='empty') && ($nowy!='1')) {
	/*		if ($source=='towary-sprzedaz') {
				if ($_REQUEST[trodzaj]=='Usługa') {
					echo " disabled";
				} else { echo ""; }
			}
	*/
			if ($source=='naprawy-wycofaj') echo " DISABLED";
			if ($source=='naprawy-przyjecie') echo " DISABLED";
			if ($source=='magazyn-zwrot') echo " DISABLED";
			if ($source=='magazyn-pobierz') echo " DISABLED";
			if ($source=='naprawy-zwrot') echo " DISABLED";
			if ($source!='towary-sprzedaz') { echo " DISABLED"; } else echo "";
		}
		
		if (($source=='towary-sprzedaz') && ($_REQUEST[pn]!='')) echo " DISABLED";
		
		echo " onkeypress='return handleEnter(this, event);'>";
		echo "<a href=# class=normalfont id=c_6a ";
		if (($state!='empty') || ($nowy=='1')) echo "onclick=labelClick(document.protokol.c_6)";
		echo ">$czynnosc6</a>";
		if ($source=='naprawy-zwrot') {
			echo "<input type=hidden name=c_3 value=on>";
		}
		
		if ($source=='towary-sprzedaz') {
//			echo "SELECT up_nazwa FROM $dbname.serwis_komorki WHERE up_id=$_REQUEST[new_upid] LIMIT 1";
			$result_up = mysql_query("SELECT up_nazwa FROM $dbname.serwis_komorki WHERE up_id=$_REQUEST[new_upid] LIMIT 1",$conn) or die($k_b);
			if (mysql_num_rows($result_up)>0) list ($nazwa_up)=mysql_fetch_array($result_up);
			if ($_REQUEST[pn]!='') {
				$result = mysql_query("SELECT naprawa_id, naprawa_nazwa, naprawa_model, naprawa_sn, naprawa_uwagi, naprawa_sprzet_zastepczy_id, naprawa_ew_id FROM $dbname.serwis_naprawa WHERE (naprawa_id=$_REQUEST[pn]) LIMIT 1", $conn) or die($k_b);				
				if (mysql_num_rows($result)>0) { 
					list($temp_id,$temp_nazwa,$temp_model,$temp_sn,$temp_uwagi,$temp_sprzet_zast,$temp_ew_id)=mysql_fetch_array($result);
					
					$opis_uszkodzenia = $temp_uwagi;
				}
				echo "<input type=hidden name=pn value=$_REQUEST[pn]>";
				echo "<input type=hidden name=sz value=$temp_sprzet_zast>";
				
			}
			
		}
		if ($source=='towary-sprzedaz') {
		//	echo "<input type=hidden name=c_6 checked=checked value=on>";
		}
		
	_td();
_tr();
tr_();
	td("110;r;");
	td_(";;");
		echo "<input class=border0 type=checkbox id=c_7 name=c_7 ";

		if (($source=='naprawy-zwrot') && ($_REQUEST['wp_count']>0)) {
			$wybranych = 0;
			for ($q=0;$q<$_REQUEST['wp_count'];$q++) {
				if ($_REQUEST['markpoz'.$q.'']!='') $wybranych++;
			}
		}
		if ($wybranych>0) {
			$_REQUEST[c_7]='on';
			$_REQUEST[disableall]='1';
		}
		
		if ($_REQUEST[disableall]==1) echo " disabled ";
		if ($_REQUEST[c_7]=='on') { echo "checked=checked"; // echo " value=on ";
		} 		
		
		if (($state=='empty') && ($nowy!='1')) {
			if (($source!='towary-sprzedaz') && ($source!='naprawy-zwrot')) echo " disabled";
			if ($source=='towary-sprzedaz') echo "";
			if (($source=='towary-sprzedaz') && ($_REQUEST[pn]!='')) echo " DISABLED";
			
		}
		
		echo " onkeypress='return handleEnter(this, event);'>";
		
		if ($_REQUEST[c_7]=='on') {
			echo "<input type=hidden name=c_7 id=c_7 value='on'>";
		}
		
		if ($source=='only-protokol') {
			if ($_REQUEST[c_7]=='on') { echo "<input type=hidden id=c_7 name=c_7 value='on'>"; }
			if ($_REQUEST[c_7]!='on') echo "<input type=hidden id=c_7 name=c_7 value=''>";
		}
		
		echo "<a href=# class=normalfont id=c_7a ";
		if (($state!='empty') || ($nowy=='1')) echo "onclick=labelClick(document.protokol.c_7)";
		if ($source=='naprawy-zwrot') echo "onclick=labelClick(document.protokol.c_7)";
		echo ">$czynnosc7</a>";
		
		if ($source=='wymiana-podzespolow') {
			echo "<input type=hidden name=c_7 checked=checked value=on>";
		}
		
	_td();
_tr();
tr_();
	td("110;r;");
	td_(";;");
	//if ($_REQUEST[source]=='towary-sprzedaz') $_REQUEST[c_8]='on';
		echo "<input class=border0 type=checkbox id=c_8 name=c_8 ";
		
		if ($_REQUEST[disableall]==1) echo " disabled ";
		
		if ($_REQUEST[c_8]=='on') { echo "checked=checked"; echo " value=on ";} 
		if (($state=='empty') && ($nowy!='1')) {
			if ($source!='towary-sprzedaz') echo " disabled";
			if ($source=='towary-sprzedaz') echo "";
			if (($source=='towary-sprzedaz') && ($_REQUEST[pn]!='')) echo " DISABLED";
		}
		echo " onkeypress='return handleEnter(this, event);'>";
		echo "<a href=# class=normalfont id=c_8a ";
		if (($state!='empty') || ($nowy=='1')) echo "onclick=labelClick(document.protokol.c_8)";
		echo ">$czynnosc8</a>";
	_td();
_tr();
tr_();	td_(";;");	_td();	td_(";;");	_td(); _tr();
endtable();
echo "<table cellpadding=1 cellspacing=1 align=center>";
tr_();	td_(";;");	_td();	td_(";;");	_td(); _tr();
tr_();
	td("110;r;<legend>Nazwa komórki</legend>");
	td_(";;");
/*	
	if ($es_m==1) {
		$result = mysql_query("SELECT up_id,up_nazwa,up_pion_id,up_umowa_id FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (up_active=1) ORDER BY serwis_piony.pion_nazwa,up_nazwa", $conn) or die($k_b);
	} else {
		$result = mysql_query("SELECT up_id,up_nazwa,up_pion_id,up_umowa_id FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE belongs_to=$es_filia and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (up_active=1) ORDER BY serwis_piony.pion_nazwa,up_nazwa", $conn) or die($k_b);
	}
*/
	$BKWB = 0;	// brak komórki w bazie

	// jeżeli protokół generowany z innej formatki => wyświetl tylko sam tekst
	
	if (($source=='magazyn-pobierz') || ($source=='magazyn-zwrot') || ($source=='naprawy-zwrot') || ($source=='naprawy-przyjecie') || ($source=='towary-sprzedaz') || ($source=='naprawy-wycofaj') || ($source=='ewidencja-przesuniecia') || ($source=='wymiana-podzespolow') || ($source=='only-protokol')) {
	
		$result1 = mysql_query("SELECT up_id,up_nazwa,up_pion_id,up_umowa_id,pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (up_active=1) and (belongs_to=$es_filia) and (serwis_komorki.up_id='$_REQUEST[new_upid]') LIMIT 1", $conn);
		
		if (mysql_num_rows($result1)>0) {
			list($temp_id,$temp_nazwa,$temp_pion_id,$temp_umowa_id,$temp_pionnazwa)=mysql_fetch_array($result1);
			//	if ($is_komorka==0) echo "Brak komórki o takiej nazwie w bazie.";
			echo "<b>$temp_pionnazwa $temp_nazwa</b>";
			echo "<input type=hidden name=new_upid value=$_REQUEST[new_upid]>";		
			// setcookie('pustyprotokol',$_REQUEST[new_upid],time()+3600);
			$_COOKIE['pustyprotokol'] = $_REQUEST[new_upid];
			$BKWB = 0;
		} else {
			errorheader("Brak komórki o nazwie <font color=white>$tup</font> w bazie.<br /><font style=font-weight:normal>Prawdopodobnie komórka została usunięta z bazy lub jej nazwa została zmieniona<br /><br />Aby móc wygenerować poprawnie protokół, dodaj do bazy komórek nową komórkę o nazwie:<br /><font color=white>$tup</font></font>");
			
			startbuttonsarea("right");
				addownlinkbutton("'Dodaj komórkę/UP'","Button1","button","newWindow(800,600,'d_komorka.php')");
				echo "<input type=button class=buttons value='Zamknij' onClick=\"self.close();\">";
			endbuttonsarea();
			
			die("");
			$BKWB = 1;
		}
	} else {
	
		//if ($_REQUEST[source]!='only-protokol') {
			$result = mysql_query("SELECT up_id,up_nazwa,up_pion_id,up_umowa_id,pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (up_active=1) and (belongs_to=$es_filia) ORDER BY serwis_piony.pion_nazwa,up_nazwa", $conn) or die($k_b);
			echo "<input type=hidden id=upid_new value=0>";
			
			echo "<select style='text-align:left; font-size: 12px; color:black;' name=new_upid onkeypress='return handleEnter(this, event);' onChange=\"if (this.value>0) { createCookie('pustyprotokol',this.value,1); document.getElementById('wybierz_z_ewidencji').style.display=''; document.getElementById('upid_new').value=this.value;  document.getElementById('nazwa_urzadzenia').value=''; document.getElementById('sn_urzadzenia').value=''; document.getElementById('ni_urzadzenia').value=''; } else { document.getElementById('wybierz_z_ewidencji').style.display='none'; document.getElementById('upid_new').value=0;  createCookie('pustyprotokol',0,1);}\">\n";
			
			echo "<option value=''>Wybierz z listy...</option>\n";
			while (list($temp_id,$temp_nazwa,$temp_pion_id,$temp_umowa_id,$temp_pionnazwa)=mysql_fetch_array($result)) {
				echo "<option value=$temp_id";
				if ($temp_id==$_REQUEST[new_upid]) echo " SELECTED ";
				echo ">$temp_pionnazwa $temp_nazwa</option>\n";
			}
			echo "</select>";
			$BKWB = 0;
		//} else {
		
	//	}
	}
	_td();
_tr();
tr_();
	td("110;r;<legend>Nazwa urządzenia</legend>");
	td_(";;");
	
	//echo $_REQUEST[blank];
	//print_r($_REQUEST);
	
		if ($source=='magazyn-pobierz') {
		  if ($popraw==1) $nazwa_urzadzenia = $_REQUEST[nazwa_urzadzenia];
		  if ($popraw!=1) $nazwa_urzadzenia = $_REQUEST[part] ." ".$_REQUEST[mmodel];
		}

		if ($source=='magazyn-zwrot') {
		  if ($popraw==1) $nazwa_urzadzenia = $_REQUEST[nazwa_urzadzenia];
		  if ($popraw!=1) $nazwa_urzadzenia = $_REQUEST[part] ." ".$_REQUEST[mmodel];
		}

		if (($source=='naprawy-przyjecie') || ($source=='wymiana-podzespolow')) {
			if ($popraw==1) $nazwa_urzadzenia = $_REQUEST[nazwa_urzadzenia];
			if ($popraw!=1) $nazwa_urzadzenia = $_REQUEST[tnazwa] ." ".$_REQUEST[tmodel];
			//  if ($popraw!=1) $nazwa_urzadzenia = $_REQUEST[tmodel];
		
			if ($source=='wymiana-podzespolow') {
				$sn_urzadzenia = $tsn;
				$ni_urzadzenia = $tni;
			}
			echo "<input type=hidden name=typ_urzadzenia value='".$_REQUEST[tnazwa]."'>";
			echo "<input type=hidden name=model_urzadzenia value='".$_REQUEST[tmodel]."'>";
			
		}
			
		if ($source=='naprawy-zwrot') {
		  if ($popraw==1) $nazwa_urzadzenia = $_REQUEST[nazwa_urzadzenia];
		  if ($popraw!=1) $nazwa_urzadzenia = $_REQUEST[part] ." ".$_REQUEST[mmodel];
		}
		
		if ($source=='naprawy-wycofaj') {
		  if ($popraw==1) $nazwa_urzadzenia = $_REQUEST[nazwa_urzadzenia];
		  if ($popraw!=1) $nazwa_urzadzenia = $_REQUEST[part] ." ".$_REQUEST[mmodel];
		}			
		
		//echo "$nazwa_urzadzenia";
		if ($source=='towary-sprzedaz') {

			if ($_REQUEST[pn]!='') {
				
				$result = mysql_query("SELECT naprawa_id, naprawa_nazwa, naprawa_model, naprawa_sn, naprawa_ni, naprawa_uwagi, naprawa_ew_id FROM $dbname.serwis_naprawa WHERE (belongs_to=$es_filia) and (naprawa_id=$_REQUEST[pn]) LIMIT 1", $conn) or die($k_b);
				
				if (mysql_num_rows($result)>0) { 
					list($temp_id,$temp_nazwa,$temp_model,$temp_sn,$temp_ni,$temp_uwagi,$temp_ew_id)=mysql_fetch_array($result);
					
					$nazwa_urzadzenia = $temp_nazwa." ".$temp_model;
					$sn_urzadzenia = $temp_sn;
					$ni_urzadzenia = $temp_ni;
					//$opis_uszkodzenia = $temp_uwagi;
				}
			}

			//$nazwa_urzadzenia = $_REQUEST[nazwa_sprzetu];
		/*	if ($_REQUEST[obzp]==1) {
				if ($popraw==1) $nazwa_urzadzenia = $_REQUEST[nazwa_urzadzenia];
				if ($_REQUEST[trodzaj]!='Usługa') 
					if ($popraw!=1) $nazwa_urzadzenia = $_REQUEST[tnazwa];
			}
		*/	
		}
		
		if (($source=='towary-sprzedaz') && ($_REQUEST[wykonane_czynnosci]!='-')) {
			if ($_REQUEST[trodzaj]=='Usługa') $wykonane_czynnosci = $tnazwa . " (SN: ". $tsn .") w serwisie";
			if ($_REQUEST[trodzaj]=='Towar') $wykonane_czynnosci = "Przekazanie sprzętu: ".$tnazwa . " (SN: ". $tsn .")";
			if ($_REQUEST[pn]!='') $wykonane_czynnosci = $_REQUEST[nazwa_sprzetu]." (SN: ".$_REQUEST[sn_sprzetu].")";
		}
	
		if ($source=='towary-sprzedaz') {
		  if ($popraw==1) $uwagi = $_REQUEST[uwagi];
		  if ($popraw!=1) $uwagi=cleanup(cleanup($tuwagi));
		}
		
		if ($source=='towary-sprzedaz') {
			if (($_REQUEST[sz]!='0') && ($_REQUEST[sz]!='')) {				
								//echo "SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn FROM $dbname.serwis_magazyn WHERE (belongs_to=$es_filia) and (magazyn_status=1) and (magazyn_id='$_REQUEST[sz]') LIMIT 1";
			
				$result = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn FROM $dbname.serwis_magazyn WHERE (belongs_to=$es_filia) and (magazyn_status=1) and (magazyn_id='$_REQUEST[sz]') LIMIT 1", $conn) or die($k_b);

				if (mysql_num_rows($result)>0) { 
					list($temp_id,$temp_nazwa,$temp_model,$temp_sn)=mysql_fetch_array($result);
				}
				
				if (strpos($uwagi,"Pobrano sprzęt serwisowy: ".$temp_nazwa." (SN: ".$temp_model.",".$temp_sn.")")==false)
				{	
				
					$uwagi = "\nPobrano sprzęt serwisowy: ".$temp_nazwa." (SN: ".$temp_model.",".$temp_sn.")";
				}
			}
			
			if ($_REQUEST[zestaw]==1) {
				$wykonane_czynnosci = "Przekazanie sprzętu: ".$_REQUEST[tuwagi];
			}
		}

		//echo "$_REQUEST[ewid_id]";
		
		if (($source!='magazyn-pobierz') &&
			($source!='magazyn-zwrot') && 
			($source!='naprawy-zwrot') &&
			($source!='naprawy-przyjecie') &&
			($source!='naprawy-wycofaj') &&
			($source!='towary-sprzedaz') && 
			($source!='ewidencja-przesuniecia') && 
			($source!='wymiana-podzespolow') && 
			($source!='only-protokol')
			//($source!='towary-sprzedaz')			
			) {
				echo "<input type=text size=50 id=nazwa_urzadzenia name=nazwa_urzadzenia value='".cleanup(cleanup($nazwa_urzadzenia))."'>";
				
				//addownsubmitwithname("'Dane o urządzeniu z ewidencji'","ewidencja","submit");
				echo "&nbsp;";
				
				echo "<input class=buttons name=button id=wybierz_z_ewidencji style='display:none;' type=button onClick=\"self.location.href='e_protokol_z_ewidencji.php?blank=1&block_komorka=1&new_upid=&tup1=&source=&wykonane_czynnosci=&uwagi=&jedna_umowa=".urlencode($_REQUEST[jedna_umowa])."&tumowa=".urlencode($_REQUEST[tumowa])."&trodzaj=".urlencode($_REQUEST[trodzaj])."&uwagi=".urlencode($_REQUEST[uwagi])."&tstatus=".urlencode($_REQUEST[tstatus])."&ttyp=".urlencode($_REQUEST[ttyp])."&tidf=".urlencode($_REQUEST[tidf])."&tid=".urlencode($_REQUEST[tid])."&tcenaodsp=".urlencode($_REQUEST[tcenaodsp])."&tcena=".urlencode($_REQUEST[tcena])."&tnazwa=".urlencode($_REQUEST[tnazwa])."';\" value='Wybierz z ewidencji sprzętu'>";
				
			} else {
				
				if ($_REQUEST[dodajwymianepodzespolow]=='1') {
					$_REQUEST[ewid_id]='-1';
					$_REQUEST[quiet]='1';
					$nazwa_urzadzenia = $_REQUEST[_wp_opis];
				}
				
				if (($_REQUEST[ewid_id]!=0) && ($_REQUEST[ewid_id]!='')) {
					echo "<b>".cleanup(cleanup($nazwa_urzadzenia))."</b>";
					
					if ($_REQUEST[quiet]!='1') echo "<font color=red>&nbsp;[z ewidencji]</font>";
					
					if ($_REQUEST[popraw_w_ewidencji]=='on') {
						echo "<font color=green>&nbsp;[baza ewidencji sprzętu zostanie uaktualniona]</font>";
					}
					
					echo "<input type=hidden name=nazwa_urzadzenia value='".cleanup(cleanup($nazwa_urzadzenia))."'>";
				} else {
					
					switch ($source) {
					
						case 'ewidencja-przesuniecia' : if ($_REQUEST[nazwa_urzadzenia]!='') { $nazwa_urzadzenia=$_REQUEST[nazwa_urzadzenia]; } else { $nazwa_urzadzenia = $_REQUEST[nu]; }
														echo "<b>".cleanup(cleanup($nazwa_urzadzenia))."</b>";
														echo "<input type=hidden name=nazwa_urzadzenia value='".cleanup(cleanup($nazwa_urzadzenia))."'>";
														break;
													
						case 'magazyn-pobierz' 	: 	echo "<b>-</b>";
													echo "<input type=hidden name=nazwa_urzadzenia value='-'>";
													break;
													
						case 'magazyn-zwrot' 	: 	echo "<b>-</b>";
													echo "<input type=hidden name=nazwa_urzadzenia value='-'>";
													break;
						case 'naprawy-przyjecie' : 	echo "<input type=text name=nazwa_urzadzenia size=50 value='".cleanup(cleanup($nazwa_urzadzenia))."'>";
													if ($_REQUEST[dodaj_sprzet_do_ewidencji]=='on') {
														echo "<font color=blue>&nbsp;[nowy sprzęt zostanie dodany do ewidencji]</font>";
													}
													
													break;	
						case 'wymiana-podzespolow' :echo "<b>".cleanup(cleanup($nazwa_urzadzenia))."</b>"; 	
													echo "<input type=hidden name=nazwa_urzadzenia value='".cleanup(cleanup($nazwa_urzadzenia))."'>";
													if ($_REQUEST[dodaj_sprzet_do_ewidencji]=='on') {
														echo "<font color=blue>&nbsp;[nowy sprzęt zostanie dodany do ewidencji]</font>";
													}
													
													break;													
						case 'naprawy-zwrot' 	: 	echo "<b>".cleanup(cleanup($nazwa_urzadzenia))."</b>";
													echo "<input type=hidden name=nazwa_urzadzenia value='".cleanup(cleanup($nazwa_urzadzenia))."'>";
													break;
													
						case 'naprawy-wycofaj' 	: 	echo "<b>".cleanup(cleanup($nazwa_urzadzenia))."</b>";
													echo "<input type=hidden name=nazwa_urzadzenia value='".cleanup(cleanup($nazwa_urzadzenia))."'>";
													break;
													
						case 'towary-sprzedaz' 	:	echo "<input type=text name=nazwa_urzadzenia size=50 value='".cleanup(cleanup($_REQUEST[nazwa_urzadzenia]))."'>";
													echo "&nbsp;";
													//addownlinkbutton("'Wybierz z ewidencji sprzętu'","button","button","self.location.href='e_protokol_z_ewidencji.php?blank=0&block_komorka=1&new_upid=$_REQUEST[new_upid]&tup1=$tup&source=$_REQUEST[source]&wykonane_czynnosci=".urlencode($wykonane_czynnosci)."&uwagi=".urlencode($uwagi)."';");
												if ($_REQUEST[zestaw]!=1) {	
													echo "<input class=buttons name=button type=button onClick=\"self.location.href='e_protokol_z_ewidencji.php?blank=0&block_komorka=1&new_upid=$_REQUEST[new_upid]&tup1=$tup&source=$_REQUEST[source]&wykonane_czynnosci=".urlencode($wykonane_czynnosci)."&uwagi=".urlencode($uwagi)."&jedna_umowa=".urlencode($_REQUEST[jedna_umowa])."&tumowa=".urlencode($_REQUEST[tumowa])."&trodzaj=".urlencode($_REQUEST[trodzaj])."&uwagi=".urlencode($_REQUEST[uwagi])."&tstatus=".urlencode($_REQUEST[tstatus])."&ttyp=".urlencode($_REQUEST[ttyp])."&tidf=".urlencode($_REQUEST[tidf])."&tid=".urlencode($_REQUEST[tid])."&tcenaodsp=".urlencode($_REQUEST[tcenaodsp])."&tcena=".urlencode($_REQUEST[tcena])."&tnazwa=".urlencode($_REQUEST[tnazwa])."&zestaw=".$_REQUEST[zestaw]."&obzp=".$_REQUEST[obzp]."&edit_towar=".$_REQUEST[edit_towar]."&edit_zestaw=".$_REQUEST[edit_zestaw]."&wstecz=".$_REQUEST[wstecz]."&tstatus=".urlencode($_REQUEST[tstatus])."&ttyp=".urlencode($_REQUEST[ttyp])."&tidf=".urlencode($_REQUEST[tidf])."&tid=".urlencode($_REQUEST[tid])."&tcenaodsp=".urlencode($_REQUEST[tcenaodsp])."&tcena=".urlencode($_REQUEST[tcena])."&tnazwa=".urlencode($_REQUEST[tnazwa])."&zestaw=".$_REQUEST[zestaw]."&niedodawaj=1&zid=".$_REQUEST[zid]."&obzp=".$_REQUEST[obzp]."&edit_towar=".$_REQUEST[edit_towar]."&edit_zestaw=".$_REQUEST[edit_zestaw]."&wstecz=".$_REQUEST[wstecz]."&tdata=".$_REQUEST[tdata]."&tuwagi=".urlencode($_REQUEST[tuwagi])."&hd_zgl_nr=".$_REQUEST[hd_zgl_nr]."&tprzesylka=".$_REQUEST[tprzesylka]."&tsn=".urlencode($_REQUEST[tsn])."&ewid_id=".$_REQUEST[ewid_id]."&quiet=".$_REQUEST[quiet]."';\" value='Wybierz z ewidencji sprzętu'>";
												} else {
													echo "<input class=buttons name=button type=button onClick=\"self.location.href='e_protokol_z_ewidencji.php?blank=0&block_komorka=1&new_upid=$_REQUEST[new_upid]&tup1=$tup&source=$_REQUEST[source]&wykonane_czynnosci=".urlencode($wykonane_czynnosci)."&uwagi=".urlencode($uwagi)."&jedna_umowa=".urlencode($_REQUEST[jedna_umowa])."&tumowa=".urlencode($_REQUEST[tumowa])."&trodzaj=".urlencode($_REQUEST[trodzaj])."&uwagi=".urlencode($_REQUEST[uwagi])."&tstatus=".urlencode($_REQUEST[tstatus])."&ttyp=".urlencode($_REQUEST[ttyp])."&tidf=".urlencode($_REQUEST[tidf])."&tid=".urlencode($_REQUEST[tid])."&tcenaodsp=".urlencode($_REQUEST[tcenaodsp])."&tcena=".urlencode($_REQUEST[tcena])."&tnazwa=".urlencode($_REQUEST[tnazwa])."&zestaw=".$_REQUEST[zestaw]."&c_7=on&zid=".$_REQUEST[zid]."&obzp=".$_REQUEST[obzp]."&edit_towar=".$_REQUEST[edit_towar]."&edit_zestaw=".$_REQUEST[edit_zestaw]."&wstecz=".$_REQUEST[wstecz]."&tstatus=".urlencode($_REQUEST[tstatus])."&ttyp=".urlencode($_REQUEST[ttyp])."&tidf=".urlencode($_REQUEST[tidf])."&tid=".urlencode($_REQUEST[tid])."&tcenaodsp=".urlencode($_REQUEST[tcenaodsp])."&tcena=".urlencode($_REQUEST[tcena])."&tnazwa=".urlencode($_REQUEST[tnazwa])."&zestaw=".$_REQUEST[zestaw]."&niedodawaj=1&zid=".$_REQUEST[zid]."&obzp=".$_REQUEST[obzp]."&edit_towar=".$_REQUEST[edit_towar]."&edit_zestaw=".$_REQUEST[edit_zestaw]."&wstecz=".$_REQUEST[wstecz]."&tdata=".$_REQUEST[tdata]."&tuwagi=".urlencode($_REQUEST[tuwagi])."&hd_zgl_nr=".$_REQUEST[hd_zgl_nr]."&tprzesylka=".$_REQUEST[tprzesylka]."&tsn=".urlencode($_REQUEST[tsn])."&ewid_id=".$_REQUEST[ewid_id]."&quiet=".$_REQUEST[quiet]."';\" value='Wybierz z ewidencji sprzętu'>";
												}

													break;
													
						default :				  	echo "<input type=text name=nazwa_urzadzenia size=50 value='".cleanup(cleanup($_REQUEST[nazwa_urzadzenia]))."'>";
													echo "&nbsp;";
													//addownlinkbutton("'Wybierz z ewidencji sprzętu'","button","button","self.location.href='e_protokol_z_ewidencji.php?blank=0&block_komorka=1&new_upid=$_REQUEST[new_upid]&tup1=$tup&source=$_REQUEST[source]&wykonane_czynnosci=".urlencode($wykonane_czynnosci)."&uwagi=".urlencode($uwagi)."';");
													echo "<input class=buttons name=button type=button onClick=\"self.location.href='e_protokol_z_ewidencji.php?blank=0&block_komorka=1&new_upid=$_REQUEST[new_upid]&tup1=$tup&source=$_REQUEST[source]&wykonane_czynnosci=".urlencode($wykonane_czynnosci)."&uwagi=".urlencode($uwagi)."&jedna_umowa=".urlencode($_REQUEST[jedna_umowa])."&tumowa=".urlencode($_REQUEST[tumowa])."&trodzaj=".urlencode($_REQUEST[trodzaj])."&uwagi=".urlencode($_REQUEST[uwagi])."&tstatus=".urlencode($_REQUEST[tstatus])."&ttyp=".urlencode($_REQUEST[ttyp])."&tidf=".urlencode($_REQUEST[tidf])."&tid=".urlencode($_REQUEST[tid])."&tcenaodsp=".urlencode($_REQUEST[tcenaodsp])."&tcena=".urlencode($_REQUEST[tcena])."&tnazwa=".urlencode($_REQUEST[tnazwa])."&tstatus=".urlencode($_REQUEST[tstatus])."&ttyp=".urlencode($_REQUEST[ttyp])."&tidf=".urlencode($_REQUEST[tidf])."&tid=".urlencode($_REQUEST[tid])."&tcenaodsp=".urlencode($_REQUEST[tcenaodsp])."&tcena=".urlencode($_REQUEST[tcena])."&tnazwa=".urlencode($_REQUEST[tnazwa])."&zestaw=".$_REQUEST[zestaw]."&niedodawaj=1&zid=".$_REQUEST[zid]."&obzp=".$_REQUEST[obzp]."&edit_towar=".$_REQUEST[edit_towar]."&edit_zestaw=".$_REQUEST[edit_zestaw]."&wstecz=".$_REQUEST[wstecz]."&tdata=".$_REQUEST[tdata]."&tuwagi=".urlencode($_REQUEST[tuwagi])."&hd_zgl_nr=".$_REQUEST[hd_zgl_nr]."&tprzesylka=".$_REQUEST[tprzesylka]."&tsn=".urlencode($_REQUEST[tsn])."&ewid_id=".$_REQUEST[ewid_id]."&quiet=".$_REQUEST[quiet]."';\" value='Wybierz z ewidencji sprzętu'>";
													break;
									
					}

				}
			}
	_td();
_tr();
tr_();
	td("110;r;<legend>Numer seryjny</legend>");
	td_(";;");
	
		if ($source=='magazyn-pobierz') {
		  if ($popraw==1) $sn_urzadzenia = $_REQUEST[sn_urzadzenia];
		  if ($popraw!=1) $sn_urzadzenia = $_POST[msn];
		}

		if ($source=='magazyn-zwrot') {
		  if ($popraw==1) $sn_urzadzenia = $_REQUEST[sn_urzadzenia];
		  if ($popraw!=1) $sn_urzadzenia = $_POST[msn];
		}

		if ($source=='naprawy-przyjecie') {
		  if ($popraw==1) $sn_urzadzenia = $_REQUEST[sn_urzadzenia];
//		  if ($popraw!=1) $nazwa_urzadzenia = $_REQUEST[part] ." ".$_REQUEST[mmodel];
		  if ($popraw!=1) $sn_urzadzenia = $_REQUEST[tsn];
		}
		
		if ($source=='naprawy-zwrot') {
		  if ($popraw==1) $sn_urzadzenia = $_REQUEST[sn_urzadzenia];
		  if ($popraw!=1) $sn_urzadzenia = $_REQUEST[msn];
		}

		if ($source=='naprawy-wycofaj') {
		  if ($popraw==1) $sn_urzadzenia = $_REQUEST[sn_urzadzenia];
		  if ($popraw!=1) $sn_urzadzenia = $_REQUEST[msn];
		}

		if ($source=='towary-sprzedaz') {
		  if ($popraw==1) $sn_urzadzenia = $_REQUEST[sn_urzadzenia];
//		  if ($_REQUEST[trodzaj]!='Usługa') 
//			if ($popraw!=1) $sn_urzadzenia = $_REQUEST[tsn];
		}

		if (($source!='magazyn-pobierz') &&
			($source!='magazyn-zwrot') && 
			($source!='naprawy-zwrot') &&
			($source!='naprawy-przyjecie') &&
			($source!='naprawy-wycofaj') &&
			($source!='towary-sprzedaz') && 
			($source!='ewidencja-przesuniecia') && 
			($source!='wymiana-podzespolow') && 
			($source!='only-protokol')
			//($source!='towary-sprzedaz')			
			) {

				echo "<input type=text size=20 id=sn_urzadzenia name=sn_urzadzenia value='".cleanup(cleanup($sn_urzadzenia))."'>";
			
			} else {
				if ($sn_urzadzenia=='') $sn_urzadzenia = $_REQUEST[sn_urzadzenia];
				
				if ($_REQUEST[dodajwymianepodzespolow]=='1') {
					$_REQUEST[ewid_id]='-1';
					$_REQUEST[quiet]='1';
//					$nazwa_urzadzenia = $_REQUEST[_wp_opis];
					$sn_urzadzenia = $_REQUEST[_wp_sn];
//					$ni_urzadzenia = $_REQUEST[_wp_ni];
				}
				
				if (($_REQUEST[ewid_id]!=0) && ($_REQUEST[ewid_id]!='')) {
					echo "<b>".cleanup(cleanup($sn_urzadzenia))."</b>";
					//echo "<font color=red>&nbsp;[z ewidencji]</font>";
					echo "<input type=hidden name=sn_urzadzenia value='".cleanup(cleanup($sn_urzadzenia))."'>";
				} else {
					switch ($source) {
					
						case 'ewidencja-przesuniecia' : if ($_REQUEST[sn_urzadzenia]!='') { $sn_urzadzenia=$_REQUEST[sn_urzadzenia]; } else { $sn_urzadzenia = $_REQUEST[ns]; }
														echo "<b>".cleanup(cleanup($sn_urzadzenia))."</b>";
														echo "<input type=hidden name=sn_urzadzenia value='".cleanup(cleanup($sn_urzadzenia))."'>";
														break;					
						case 'magazyn-pobierz' 	: 	echo "<b>-</b>";
													echo "<input type=hidden name=sn_urzadzenia value='-'>";
													break;
													
						case 'magazyn-zwrot' 	: 	echo "<b>-</b>";
													echo "<input type=hidden name=sn_urzadzenia value='-'>";
													break;												
						case 'wymiana-podzespolow' :echo "<b>".cleanup(cleanup($sn_urzadzenia))."</b>"; 	
													echo "<input type=hidden name=sn_urzadzenia value='".cleanup(cleanup($sn_urzadzenia))."'>";
													if ($_REQUEST[dodaj_sprzet_do_ewidencji]=='on') {
														echo "<font color=blue>&nbsp;[nowy sprzęt zostanie dodany do ewidencji]</font>";
													}
													break;
						case 'naprawy-przyjecie' : 	echo "<input type=text name=sn_urzadzenia size=30 value='".cleanup(cleanup($sn_urzadzenia))."'>";
													break;
						case 'naprawy-zwrot' 	: 	echo "<b>".cleanup(cleanup($sn_urzadzenia))."</b>";
													echo "<input type=hidden name=sn_urzadzenia value='".cleanup(cleanup($sn_urzadzenia))."'>";
													break;
													
						case 'naprawy-wycofaj' 	: 	echo "<b>".cleanup(cleanup($sn_urzadzenia))."</b>";
													echo "<input type=hidden name=sn_urzadzenia value='".cleanup(cleanup($sn_urzadzenia))."'>";
													break;
													
						default :				  	echo "<input type=text name=sn_urzadzenia size=30 value='".cleanup(cleanup($_REQUEST[sn_urzadzenia]))."'>";
													echo "&nbsp;";
													break;
									
					}
				}
			}

			
	_td();
_tr();

tr_();
	td("110;r;<legend>Numer inwentarzowy</legend>");
	td_(";;");
	
		if ($source=='magazyn-pobierz') {
		  if ($popraw==1) $ni_urzadzenia = $_REQUEST[ni_urzadzenia];
		  if ($popraw!=1) $ni_urzadzenia = $_POST[mni];
		}
	
		if ($source=='magazyn-zwrot') {
		  if ($popraw==1) $ni_urzadzenia = $_REQUEST[ni_urzadzenia];
		  if ($popraw!=1) $ni_urzadzenia = $_POST[mni];
		}

		if ($source=='naprawy-przyjecie') {
		  if ($popraw==1) $ni_urzadzenia = $_REQUEST[ni_urzadzenia];
//		  if ($popraw!=1) $nazwa_urzadzenia = $_REQUEST[part] ." ".$_REQUEST[mmodel];
		  if ($popraw!=1) $ni_urzadzenia = $_REQUEST[tni];
		}
		
		if ($source=='naprawy-zwrot') {
		  if ($popraw==1) $ni_urzadzenia = $_REQUEST[ni_urzadzeniani_urzadzenia];
		  if ($popraw!=1) $ni_urzadzenia = $_POST[mni];
		}		
		
		if ($source=='naprawy-wycofaj') {
		  if ($popraw==1) $ni_urzadzenia = $_REQUEST[ni_urzadzeniani_urzadzenia];
		  if ($_REQUEST[trodzaj]!='Usługa') if ($popraw!=1) $ni_urzadzenia = $_REQUEST[mni];
		}	

		if ($source=='towary-sprzedaz') {
			if ($popraw==1) {
				$ni_urzadzenia = $_REQUEST[ni_urzadzeniani_urzadzenia];
			} else $ni_urzadzenia = "-";
		}	
		
		if ($ni_urzadzenia=='') $ni_urzadzenia = '-';

		if ($source=='towary-sprzedaz') { echo "<input type=hidden name=ni_urzadzenia value='".cleanup(cleanup($ni_urzadzenia))."'>"; }	
		
		if (($source!='magazyn-pobierz') &&
			($source!='magazyn-zwrot') && 
			($source!='naprawy-zwrot') &&
			($source!='naprawy-przyjecie') &&
			($source!='naprawy-wycofaj') &&
			($source!='towary-sprzedaz') && 
			($source!='ewidencja-przesuniecia') && 
			($source!='wymiana-podzespolow') && 
			($source!='only-protokol')			
			//($source!='towary-sprzedaz')			
			) {

				echo "<input type=text size=20 id=ni_urzadzenia name=ni_urzadzenia value='".cleanup(cleanup($ni_urzadzenia))."'>";
			
			} else {
				if ((($ni_urzadzenia=='') || ($ni_urzadzenia=='-')) && ($_REQUEST[ni_urzadzenia]!='')) $ni_urzadzenia = $_REQUEST[ni_urzadzenia];
				
				if ($_REQUEST[dodajwymianepodzespolow]=='1') {
					$_REQUEST[ewid_id]='-1';
					$_REQUEST[quiet]='1';
//					$nazwa_urzadzenia = $_REQUEST[_wp_opis];
//					$sn_urzadzenia = $_REQUEST[_wp_sn];
					$ni_urzadzenia = $_REQUEST[_wp_ni];
				}
				
				if (($_REQUEST[ewid_id]!=0) && ($_REQUEST[ewid_id]!='')) {
					echo "<b>".cleanup(cleanup($ni_urzadzenia))."</b>";
					//echo "<font color=red>&nbsp;[z ewidencji]</font>";
					echo "<input type=hidden name=ni_urzadzenia value='".cleanup(cleanup($ni_urzadzenia))."'>";
				} else {
					switch ($source) {
						case 'ewidencja-przesuniecia' : if ($_REQUEST[ni_urzadzenia]!='') { $ni_urzadzenia=$_REQUEST[ni_urzadzenia]; } else { $ni_urzadzenia = $_REQUEST[ni]; }						
														echo "<b>".cleanup(cleanup($ni_urzadzenia))."</b>";
														echo "<input type=hidden name=ni_urzadzenia value='".cleanup(cleanup($ni_urzadzenia))."'>";
														break;	
														
						case 'magazyn-pobierz' 	: 	echo "<b>-</b>";
													echo "<input type=hidden name=ni_urzadzenia value='-'>";
													break;
													
						case 'magazyn-zwrot' 	: 	echo "<b>-</b>";
													echo "<input type=hidden name=ni_urzadzenia value='-'>";
													break;	
													
						case 'wymiana-podzespolow' :echo "<b>".cleanup(cleanup($ni_urzadzenia))."</b>"; 	
													echo "<input type=hidden name=ni_urzadzenia value='".cleanup(cleanup($ni_urzadzenia))."'>";
													if ($_REQUEST[dodaj_sprzet_do_ewidencji]=='on') {
														echo "<font color=blue>&nbsp;[nowy sprzęt zostanie dodany do ewidencji]</font>";
													}
													break;					
													
						case 'naprawy-przyjecie' : 	echo "<input type=text name=ni_urzadzenia size=30 value='".cleanup(cleanup($ni_urzadzenia))."'>";
													break;
						
						case 'naprawy-zwrot' 	: 	echo "<b>".cleanup(cleanup($ni_urzadzenia))."</b>";
													echo "<input type=hidden name=ni_urzadzenia value='".cleanup(cleanup($ni_urzadzenia))."'>";
													break;

						case 'naprawy-wycofaj' 	: 	echo "<b>".cleanup(cleanup($ni_urzadzenia))."</b>";
													echo "<input type=hidden name=ni_urzadzenia value='".cleanup(cleanup($ni_urzadzenia))."'>";
													break;
													
						default :				  	echo "<input type=text name=ni_urzadzenia size=30 value='".cleanup(cleanup($_REQUEST[ni_urzadzenia]))."'>";
													echo "&nbsp;";
													break;
									
					}
				}
			}
			
		
	_td();
_tr();
tr_();
	td("110;rt;<legend>Opis uszkodzenia</legend>");
	td_(";;");
	
	//print_r($_REQUEST);
		
		if (($source=='naprawy-zwrot') && ($_REQUEST[muwagi]!='')) $opis_uszkodzenia=br2nl(cleanup(cleanup($_REQUEST[muwagi])));
		if (($source=='naprawy-przyjecie')) {
			$opis_uszkodzenia = $_REQUEST[tresc_zgl];		
			if ($popraw==1) $opis_uszkodzenia = $_REQUEST[opis_uszkodzenia];
			if ($popraw!=1) $opis_uszkodzenia=cleanup(cleanup($tuwagi));
		}
		
		if ($source=='naprawy-wycofaj') {
			
			$tuwagi = $_REQUEST[muwagi];
			
			if ($popraw==1) $uwagi = $_REQUEST[uwagi];
			if ($popraw!=1) $uwagi=cleanup(cleanup($tuwagi));
			
			$start_powod_wycofania = strpos($uwagi,'Powód wycofania');
			$opis_uszkodzenia_sprzetu = substr($uwagi,0,$start_powod_wycofania-1);
			$uwagi_new = substr($uwagi,$start_powod_wycofania,strlen($uwagi));
			
			$opis_uszkodzenia = br2nl($opis_uszkodzenia_sprzetu);
		}
		
		echo "<textarea style='text-align:left; font-size: 11px;' name=opis_uszkodzenia cols=70 rows=2 wrap=VIRTUAL>".cleanup(cleanup($opis_uszkodzenia))."</textarea>";
		echo "<a onclick='protokol.opis_uszkodzenia.value=\"\"' title=' Wyczyść opis uszkodzenia '> <img src=img/czysc.gif border=0></a>";
		
	_td();
_tr();
tr_();
	td("110;rt;<legend>Wykonane czynności</legend>");
	td_(";;");
		
		if ($source=='wymiana-podzespolow') $wykonane_czynnosci=trim(br2nl(cleanup(cleanup($_REQUEST[tuwagi]))));
		
		if ($source=='magazyn-pobierz') {
			if (($_REQUEST[wstecz]=='1') && ($_REQUEST[wykonane_czynnosci]!='-')) $wykonane_czynnosci = "Przekazanie sprzętu serwisowego: ".$nazwa_urzadzenia." (SN:".$sn_urzadzenia.", NI:".$ni_urzadzenia.")";
		}

		if (($source=='magazyn-zwrot') && ($_REQUEST[wykonane_czynnosci]!='-')) {
		
			if ($_REQUEST[wstecz]=='1') $wykonane_czynnosci = "Pobranie sprzętu serwisowego: ".$nazwa_urzadzenia." (SN:".$sn_urzadzenia.", NI:".$ni_urzadzenia.")";
			
			if ($_REQUEST[naprawa_pozostaje]==1) $wykonane_czynnosci = "Pobranie sprzętu serwisowego: ".$_REQUEST[mnazwa]." ".$_REQUEST[mmodel]." (SN:".$_REQUEST[msn].", NI:".$_REQUEST[mni].")";
			
		}
		
		if (($source=='naprawy-przyjecie') && ($_REQUEST[wykonane_czynnosci]!='-')) {
			$wykonane_czynnosci = "Pobranie uszkodzonego sprzętu do naprawy.\n";
			if ($_REQUEST[sz]!='0') {
				$result = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni FROM $dbname.serwis_magazyn WHERE (belongs_to=$es_filia) and (magazyn_id='$_REQUEST[sz]') LIMIT 1", $conn) or die($k_b);

				if (mysql_num_rows($result)>0) { 
					list($temp_id,$temp_nazwa,$temp_model,$temp_sn,$temp_ni)=mysql_fetch_array($result);
				}
				
				if ($_REQUEST[PNZSS]!=1) {
					if ($temp_nazwa!='') $wykonane_czynnosci .= "Przekazanie sprzętu serwisowego: ".$temp_nazwa." ".$temp_model." (SN:".$temp_sn.", NI:".$temp_ni.")";
				} else {
					$uwagi .= "Informacje o sprzęcie serwisowym przekazanym wcześniej do komórki: ".$temp_nazwa." ".$temp_model." (SN:".$temp_sn.", NI:".$temp_ni.")";
				}
			}
		}
	
		if (($source=='towary-sprzedaz') && ($_REQUEST[wykonane_czynnosci]!='-')) {
			if ($_REQUEST[trodzaj]=='Usługa') $wykonane_czynnosci = $tnazwa . " (SN: ". $tsn .") w serwisie";
			
			if ($_REQUEST[choosefromewid]!='1') {
				if ($_REQUEST[trodzaj]=='Towar') $wykonane_czynnosci = "Przekazanie sprzętu: ".$tnazwa . " (SN:". $tsn .")";
			} else {
				if ($_REQUEST[trodzaj]=='Towar') $wykonane_czynnosci = $_REQUEST[wykonane_czynnosci];
			}
			
			if ($_REQUEST[pn]!='') $wykonane_czynnosci = $_REQUEST[nazwa_sprzetu]." (SN:".$_REQUEST[sn_sprzetu].")";
			
			if ($_REQUEST[zestaw]=='1') {
				if ($_REQUEST[niedodawaj]=='1') { $przedrostek = ''; } else { $przedrostek = 'Przekazanie: '; }
				
				if ($_REQUEST[tuwagi]!='') $wykonane_czynnosci = $przedrostek.''.$_REQUEST[tuwagi];
				if ($_REQUEST[wykonane_czynnosci]!='') $wykonane_czynnosci = $przedrostek.''.$_REQUEST[wykonane_czynnosci];
			}
		}
		
		
		if (($source=='naprawy-wycofaj') && ($_REQUEST[wykonane_czynnosci]!='-')) {
		
			if ($_REQUEST[sz]!='0') {
				$wykonane_czynnosci = "Zwrot uszkodzonego sprzętu\n";
			
			}
			
			if (($_REQUEST[sz]!='0') && ($_REQUEST[serwisowy]=='NIE')) {
				$result = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn FROM $dbname.serwis_magazyn WHERE (belongs_to=$es_filia) and (magazyn_id='$_REQUEST[sz]') LIMIT 1", $conn) or die($k_b);

				if (mysql_num_rows($result)>0) { 
					list($temp_id,$temp_nazwa,$temp_model,$temp_sn)=mysql_fetch_array($result);
				}
				$wykonane_czynnosci .= "";
			}
			
			if ($_REQUEST[serwisowy]=='TAK') {
				
				$result = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn, magazyn_ni,magazyn_uwagi FROM $dbname.serwis_magazyn WHERE (magazyn_id=$_REQUEST[szid]) LIMIT 1", $conn) or die($k_b);
				list($temp_mid,$temp_nazwa1,$temp_model1,$temp_sn1,$temp_ni1,$temp_u1)=mysql_fetch_array($result);

				$wykonane_czynnosci .= "Pozostawiono na UP sprzęt serwisowy: ".$temp_nazwa1." ".$temp_model1." (SN:".$temp_sn1.", NI:".$temp_ni1.")";
			}

			if ($_REQUEST[serwisowy]=='NIE') {
				$result = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn, magazyn_ni,magazyn_uwagi FROM $dbname.serwis_magazyn WHERE (magazyn_id=$_REQUEST[szid]) LIMIT 1", $conn) or die($k_b);
				list($temp_mid,$temp_nazwa1,$temp_model1,$temp_sn1,$temp_ni1,$temp_u1)=mysql_fetch_array($result);
				$wykonane_czynnosci .= "Pobrano z UP sprzęt serwisowy: ".$temp_nazwa1." ".$temp_model1." (SN:".$temp_sn1.", NI:".$temp_ni1.")";
			}
		}

		if ($_REQUEST[nierobzapytan]!=1) {
			if (($source=='naprawy-zwrot') && ($_REQUEST[ps]!='') && ($_REQUEST[wykonane_czynnosci]!='-')) {
				$sql="SELECT pozycja_id,pozycja_nazwa,pozycja_sn FROM $dbname.serwis_faktura_szcz,serwis_faktury WHERE ((serwis_faktura_szcz.belongs_to=$es_filia) and (serwis_faktura_szcz.pozycja_status='0') and (serwis_faktury.faktura_id=serwis_faktura_szcz.pozycja_nr_faktury) and (serwis_faktura_szcz.pozycja_typ='Usługa') and (serwis_faktura_szcz.pozycja_id=$_REQUEST[ps])) LIMIT 1";
				$result = mysql_query($sql,$conn) or die($k_b);
					
				if (mysql_num_rows($result)>0) { 
					list($temp_id,$temp_nazwa,$temp_sn)=mysql_fetch_array($result);
					$wykonane_czynnosci = $temp_nazwa." (SN:".$temp_sn.") w serwisie zewnętrznym";
				} 
			}
		}
		
		if ($_REQUEST[nierobzapytan]!=1) {	
			if ($source=='naprawy-zwrot')  {
				if ($wykonane_czynnosci=='') $wykonane_czynnosci = "\n";
				
				$result55 = mysql_query("SELECT naprawa_wykonane_naprawy FROM $dbname.serwis_naprawa WHERE (naprawa_id=$_REQUEST[id]) LIMIT 1", $conn) or die($k_b);
				list($temp_naprawy_wykonane)=mysql_fetch_array($result55);
					
				if ($temp_naprawy_wykonane!='') {
					$wykonane_czynnosci = br2nl($temp_naprawy_wykonane);
				} else { //$wykonane_czynnosci .= "Zwrot sprzętu z naprawy"; 
				}
			}
		} else {
			$wykonane_czynnosci = $_REQUEST[wykonane_czynnosci];		
		}
		
		// jeżeli wymieniono w zgłoszeniu podzespoły
		if (($_REQUEST[wp]=='1') && ($source=='naprawy-zwrot')) {
			
			$lista = '|';
			for ($q=0;$q<$_REQUEST['wp_count'];$q++) {
				if ($_REQUEST['markpoz'.$q.'']!='') {
					$wykonane_czynnosci .= $_REQUEST['markpoz'.$q.''].", ";
					$lista .= $q."|";
				}
			}

			//$lista = substr($lista,0,-1);
			
			if (strlen(trim($wykonane_czynnosci))!=0) {
				$wykonane_czynnosci = 'Wymieniono podzespoły: '.$wykonane_czynnosci;
				$wykonane_czynnosci = substr($wykonane_czynnosci,0,-2);
			}
		} else $lista='';
		
		echo "<textarea style='text-align:left; font-size: 11px;' id=wykonane_czynnosci name=wykonane_czynnosci cols=70 rows=4>".cleanup(cleanup($wykonane_czynnosci))."</textarea>";
		echo "<a onclick='protokol.wykonane_czynnosci.value=\"\"' title=' Wyczyść wykonane czynności '> <img src=img/czysc.gif border=0></a>";
	_td();
_tr();
tr_();
	td("110;rt;<legend>Uwagi</legend>");
	td_(";;");
	
		//echo $_REQUEST[source];
		
		if ($source=='magazyn-pobierz') {
		  if ($popraw==1) $uwagi = $_REQUEST[uwagi];
		  if ($popraw!=1) $uwagi=cleanup(cleanup($tkomentarz));
		}

		if ($source=='magazyn-zwrot') {
		  if ($popraw==1) $uwagi = $_REQUEST[uwagi];
		  if ($popraw!=1) $uwagi=cleanup(cleanup($tkomentarz));
		  
		  if ($_REQUEST[naprawa_pozostaje]=='1') $uwagi = $_REQUEST[tuwagi];

		}			

		//if ($source=='naprawy-przyjecie') {
		//  if ($popraw==1) $uwagi = $_REQUEST[uwagi];
		//  if ($popraw!=1) $uwagi=cleanup(cleanup($tuwagi));
		//}

		if ($source=='naprawy-wycofaj') {
			
			$tuwagi = $_REQUEST[muwagi];
			//$tuwagi = br2nl($tuwagi);
			
			if ($popraw==1) $uwagi = $_REQUEST[uwagi];
			if ($popraw!=1) $uwagi=cleanup(cleanup($tuwagi));
			
			$uwagi = '';
			if ($_REQUEST[powod_wycofania]!='') $uwagi = 'Powód wycofania z serwisu: '.$_REQUEST[powod_wycofania];
		}

		if ($source=='naprawy-zwrot') {
			if ($_REQUEST[sz]!='0') {
				$result = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn FROM $dbname.serwis_magazyn WHERE (belongs_to=$es_filia) and (magazyn_status=1) and (magazyn_id='$_REQUEST[sz]') LIMIT 1", $conn) or die($k_b);

				if (mysql_num_rows($result)>0) { 
					list($temp_id,$temp_nazwa,$temp_model,$temp_sn)=mysql_fetch_array($result);
				
					if ($_REQUEST[uwagi]=='') {
						//$uwagi = "Pozostawiono na UP sprzęt serwisowy: ".$_REQUEST[nazwasz];
						$uwagi = "Pobrano sprzęt serwisowy: ".$temp_nazwa." ".$temp_model." (SN:".$temp_sn.")";
					} else {
						$uwagi=$_REQUEST[uwagi];
					}					
				}
			} else {
				$result = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn FROM $dbname.serwis_magazyn WHERE (belongs_to=$es_filia) and (magazyn_status=1) and (magazyn_id='$_REQUEST[sz]') LIMIT 1", $conn) or die($k_b);

				if (mysql_num_rows($result)>0) { 
					list($temp_id,$temp_nazwa,$temp_model,$temp_sn)=mysql_fetch_array($result);
				}
				
				if ($_REQUEST[uwagi]=='') {
					// $uwagi = "Pozostawiono na UP sprzęt serwisowy: ".$_REQUEST[nazwasz];
					$uwagi='';
				} else {
						$uwagi=$_REQUEST[uwagi];
					}
			}
		}
		
		if ($source=='towary-sprzedaz') {
		  if ($popraw==1) $uwagi = $_REQUEST[uwagi];
		  if ($popraw!=1) {
			$uwagi=cleanup(cleanup($_REQUEST[uwagi]));
			if ($uwagi=='') $uwagi=cleanup(cleanup($tuwagi));
			}
		}
		
		if ($source=='towary-sprzedaz') {
			if (($_REQUEST[sz]!='0') && ($_REQUEST[sz]!='')) {				
				$result = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn FROM $dbname.serwis_magazyn WHERE (belongs_to=$es_filia) and (magazyn_status=1) and (magazyn_id='$_REQUEST[sz]') LIMIT 1", $conn) or die($k_b);

				if (mysql_num_rows($result)>0) { 
					list($temp_id,$temp_nazwa,$temp_model,$temp_sn)=mysql_fetch_array($result);
				}
				
				if (strpos($uwagi,"Pobrano sprzęt serwisowy: ".$temp_nazwa." ".$temp_model." (SN:".$temp_sn.")")==false) {
					$uwagi = "\nPobrano sprzęt serwisowy: ".$temp_nazwa." ".$temp_model." (SN:".$temp_sn.")";
				}
			}
			if ($_REQUEST[zestaw]=='1') $uwagi = '';
		}
		
		echo "<textarea style='text-align:left; font-size: 11px;' name=uwagi cols=70 rows=2>".br2nl(cleanup(cleanup($uwagi)))."</textarea>";
		echo "<a onclick='protokol.uwagi.value=\"\"' title=' Wyczyść uwagi '> <img src=img/czysc.gif border=0></a>";

	_td();
_tr();
tr_();	td_(";;");	_td();	td_(";;");	_td(); _tr();
endtable();

echo "<input type=hidden name=format_nr value='$format_number'>";
echo "<input type=hidden name=unr value='$_REQUEST[unr]'>";

if (($source=='towary-sprzedaz') and ($_REQUEST[wstecz]==0)) { 
	echo "<input type=hidden name=tdata value='$_REQUEST[tdata]'>"; 
	echo "<input type=hidden name=nierobzapytan value=0>"; 
}

if ($source=='naprawy-zwrot') { 
	echo "<input type=hidden name=tdata value='$_REQUEST[tdata]'>"; 
}

startbuttonsarea("right");

echo "<span style='float:left'>";

if ($obzp=='1') {
	if ($allow_sell==1) {
		echo "<span style='float:left'>";
		echo "<input class=border0 type=checkbox id=nzpdb name=nzpdb";
		if ($_REQUEST[nzpdb]=='on') {
			echo " checked=checked";
		}
		echo " onClick=nazwa_przycisku(protokol);><font color=red>";
		
		echo "<a onClick=\"if (document.getElementById('nzpdb').checked) { document.getElementById('nzpdb').checked=false; nazwa_przycisku(protokol);} else { document.getElementById('nzpdb').checked=true; nazwa_przycisku(protokol);} \"> Nie zapisuj protokołu do bazy</a>";
		
		echo "</font>&nbsp;";
		echo "</span>";

	}
}

//echo "[".$_REQUEST[zestaw]."]";
//print_r($_REQUEST);
//echo "ewid id: $_REQUEST[ewid_id]";

if (($source=='magazyn-pobierz') && ($_REQUEST[wstecz]!=0)) {
echo "<input class=buttons type=button onClick=window.location.href='z_magazyn_pobierz.php?ewid_id=".$_REQUEST[ewid_id]."&id=$id&tup=".urlencode($_POST[tup])."&tkomentarz=".urlencode($uwagi)."&part=".urlencode($part)."&id=$id&new_upid=".$_REQUEST[new_upid]."&hd_zgl_nr=".$_REQUEST[hd_zgl_nr]."&powiazzhd=".$_REQUEST[powiazzhd]."&from=".$_REQUEST[from]."&naprawaid=".$_REQUEST[naprawaid]."' value='Wstecz'>";
}
if (($source=='magazyn-zwrot') && ($_REQUEST[wstecz]!=0)) {
echo "<input class=buttons type=button onClick=window.location.href='z_magazyn_zwrot.php?ewid_id=".$_REQUEST[ewid_id]."&id=$id&tup=".urlencode($_POST[tup])."&tkomentarz=".urlencode($uwagi)."&part=".urlencode($part)."&id=$id&new_upid=".$_REQUEST[new_upid]."&hd_zgl_nr=".$_REQUEST[hd_zgl_nr]."&powiazzhd=".$_REQUEST[powiazzhd]."&from=".$_REQUEST[from]."&up=".urlencode($_POST[up])."' value='Wstecz'>";
}

if (($source=='magazyn-zwrot') && ($_REQUEST[naprawa_pozostaje]==1)) {
echo "<input class=buttons type=button onClick=window.location.href='z_naprawy_wycofaj_sprzet_serwisowy.php?id=".$_REQUEST[uid]."&cs=".$_REQUEST[cs]."&tup=".urlencode($_POST[tup])."&tuwagi=".urlencode($_REQUEST[tuwagi])."&szid=".$_REQUEST[szid]."&sz=".$_REQUEST[szid]."&new_upid=".$_REQUEST[new_upid]."&hd_zgl_nr=".$_REQUEST[hd_zgl_nr]."&from=".$_REQUEST[from]."&up=".urlencode($_POST[up])."' value='Wstecz'>";
//echo "<input class=buttons type=button onClick=\"window.history.go(-1);\" value='Wstecz'>";
}

if (($source=='naprawy-zwrot') && ($_REQUEST[wstecz]!=0)) {
echo "<input class=buttons type=button onClick=window.location.href='z_naprawy_napraw5.php?ewid_id=".$_REQUEST[ewid_id]."&id=$id&tup=".urlencode($_REQUEST[tup])."&up=".urlencode($_REQUEST[up])."&part=".urlencode($part)."&mmodel=".urlencode($_REQUEST[mmodel])."&msn=".urlencode($_REQUEST[msn])."&mni=".urlencode($_REQUEST[mni])."&tstatus1=".$_REQUEST[tstatus1]."&sz=".$_REQUEST[sz]."&id=".$_REQUEST[id]."&unr=".$_REQUEST[unr]."&ps=".$_REQUEST[ps]."&new_upid=".$_REQUEST[new_upid]."&hd_zgl_nr=".$_REQUEST[hd_zgl_nr]."&lista=".urlencode($lista)."&from=".$_REQUEST[from]."&up=".urlencode($_POST[up])."' value='Wstecz'>";
}

//print_r($_REQUEST);

if (($source=='naprawy-przyjecie') && ($_REQUEST[wstecz]!=0)) {

if ($_REQUEST[tresc_zgl]=='') {

//if ($_REQUEST[dodaj_sprzet_do_ewidencji]!='on') {
	echo "<input class=buttons type=button onClick=window.location.href='z_naprawy_uszkodzony.php?ewid_id=".$_REQUEST[ewid_id]."&id=$id&tup=".urlencode($_REQUEST[tup])."&up=".urlencode($_REQUEST[up])."&tnazwa=".urlencode($_REQUEST[tnazwa])."&tmodel=".urlencode($_REQUEST[tmodel])."&tsn=".urlencode($_REQUEST[tsn])."&tni=".urlencode($_REQUEST[tni])."&tstatus1=".$_REQUEST[tstatus1]."&sz=".$_REQUEST[sz]."&unr=".$_REQUEST[unr]."&cat=".urlencode($_REQUEST[tnazwa])."&tuwagi=".urlencode($opis_uszkodzenia)."&auto=".$_REQUEST[auto]."&tresc_zgl=".urlencode($_REQUEST[tresc_zgl])."&upid=".urlencode($_REQUEST[upid])."&tup1=".urlencode($nazwa)."&new_upid=".$_REQUEST[new_upid]."&popraw_w_ewidencji=".$_REQUEST[popraw_w_ewidencji]."&popraw_dane=".$_REQUEST[popraw_dane]."&dodaj_sprzet_do_ewidencji=".$_REQUEST[dodaj_sprzet_do_ewidencji]."&dodaj_dane=".$_REQUEST[dodaj_dane]."&dodaj_do_ewidencji=".$_REQUEST[dodaj_do_ewidencji]."&hd_zgl_nr=".$_REQUEST[hd_zgl_nr]."' value='Wstecz'>";
//} else {
//	echo "<input class=buttons type=button onClick=window.location.href='z_naprawy_uszkodzony.php?ewid_id=".$_REQUEST[ewid_id]."&id=$id&tup=".urlencode($_REQUEST[tup])."&up=".urlencode($_REQUEST[up])."&tnazwa=".urlencode($_REQUEST[tnazwa])."&tmodel=".urlencode($_REQUEST[tmodel])."&tsn=".urlencode($_REQUEST[tsn])."&tni=".urlencode($_REQUEST[tni])."&tstatus1=".$_REQUEST[tstatus1]."&sz=".$_REQUEST[sz]."&unr=".$_REQUEST[unr]."&cat=".urlencode($_REQUEST[tnazwa])."&tuwagi=".urlencode($opis_uszkodzenia)."&auto=".$_REQUEST[auto]."&tresc_zgl=".urlencode($_REQUEST[tresc_zgl])."&upid=".urlencode($_REQUEST[upid])."&tup1=".urlencode($nazwa)."&new_upid=".$_REQUEST[new_upid]."&popraw_w_ewidencji=".$_REQUEST[popraw_w_ewidencji]."&popraw_dane=0&dodaj_sprzet_do_ewidencji=".$_REQUEST[dodaj_sprzet_do_ewidencji]."&dodaj_dane=1&dodaj_do_ewidencji=1' value='Wstecz'>";
//}
} else {
	if ($_REQUEST[PNZSS]!=1) {
		echo "<input class=buttons type=button onClick=window.location.href='z_naprawy_uszkodzony.php?ewid_id=".$_REQUEST[ewid_id]."&id=$id&tup=".urlencode($_REQUEST[tup])."&up=".urlencode($_REQUEST[tup])."&tnazwa=".urlencode($_REQUEST[tnazwa])."&tmodel=".urlencode($_REQUEST[tmodel])."&tsn=".urlencode($_REQUEST[tsn])."&tni=".urlencode($_REQUEST[tni])."&tstatus1=".$_REQUEST[tstatus1]."&sz=".$_REQUEST[sz]."&unr=".$_REQUEST[unr]."&cat=".urlencode($_REQUEST[tnazwa])."&tuwagi=".urlencode($uwagi)."&auto=".$_REQUEST[auto]."&tresc_zgl=".urlencode($_REQUEST[tresc_zgl])."&upid=".urlencode($_REQUEST[upid])."&tup1=".urlencode($nazwa)."&new_upid=".$_REQUEST[new_upid]."&from=".$_REQUEST[from]."&hd_nr=".$_REQUEST[hd_nr]."&hd_podkategoria_nr=".$_REQUEST[hd_podkategoria_nr]."&hd_zgl_nr=".$_REQUEST[hd_zgl_nr]."' value='Wstecz'>";
	} else {
		$uwagi = '';
		echo "<input class=buttons type=button onClick=window.location.href='z_naprawy_uszkodzony.php?ewid_id=".$_REQUEST[ewid_id]."&id=$id&tup=".urlencode($_REQUEST[tup])."&up=".urlencode($_REQUEST[tup])."&tnazwa=".urlencode($_REQUEST[tnazwa])."&tmodel=".urlencode($_REQUEST[tmodel])."&tsn=".urlencode($_REQUEST[tsn])."&tni=".urlencode($_REQUEST[tni])."&tstatus1=".$_REQUEST[tstatus1]."&sz=".$_REQUEST[sz]."&unr=".$_REQUEST[unr]."&cat=".urlencode($_REQUEST[tnazwa])."&tuwagi=".urlencode($uwagi)."&auto=".$_REQUEST[auto]."&tresc_zgl=".urlencode($_REQUEST[tresc_zgl])."&upid=".urlencode($_REQUEST[upid])."&tup1=".urlencode($nazwa)."&new_upid=".$_REQUEST[new_upid]."&from=".$_REQUEST[from]."&hd_nr=".$_REQUEST[hd_nr]."&hd_podkategoria_nr=".$_REQUEST[hd_podkategoria_nr]."&hd_zgl_nr=".$_REQUEST[hd_zgl_nr]."' value='Wstecz'>";
	}
}
echo "<input type=hidden name=tresc_zgl value='$_REQUEST[tresc_zgl]'>";
echo "<input type=hidden name=PNZSS value='$_REQUEST[PNZSS]'>";

}

if (($source=='towary-sprzedaz') && ($_REQUEST[wstecz]==1) && ($_REQUEST[zestaw]!='1') && ($_REQUEST[edit_towar]!='1')) {
echo "<input class=buttons type=button onClick=window.location.href='z_towary_obrot.php?ewid_id=".$_REQUEST[ewid_id]."&id=$tid&tup=".urlencode($_REQUEST[tup])."&tumowa=".urlencode($_REQUEST[tumowa])."&up=".urlencode($_REQUEST[up])."&tnazwa=".urlencode($_REQUEST[tnazwa])."&tmodel=".urlencode($_REQUEST[tmodel])."&tsn=".urlencode($_REQUEST[tsn])."&tni=".urlencode($_REQUEST[tni])."&tstatus1=".$_REQUEST[tstatus1]."&sz=".$_REQUEST[sz]."&unr=".$_REQUEST[unr]."&cat=".urlencode($_REQUEST[tnazwa])."&tuwagi=".urlencode($uwagi)."&auto=".$_REQUEST[auto]."&f=".$_REQUEST[tidf]."&tdata=".$_REQUEST[tdata]."&trodzaj=".urlencode($_REQUEST[trodzaj])."&pn=".$_REQUEST[pn]."&uwagi=".urlencode($uwagi)."&tuwagi=".urlencode($uwagi)."&obzp=".$_REQUEST[obzp]."&new_upid=".$_REQUEST[new_upid]."&nazwa_urzadzenia=".urlencode($_REQUEST[nazwa_urzadzenia])."&sn_urzadzenia=".urlencode($_REQUEST[sn_urzadzenia])."&ni_urzadzenia=".urlencode($_REQUEST[ni_urzadzenia])."&readonly=".$_REQUEST[readonly]."&hd_zgl_nr=".$_REQUEST[hd_zgl_nr]."&dodajwymianepodzespolow=".$_REQUEST[dodajwymianepodzespolow]."&_wp_opis=".urlencode($_REQUEST[_wp_opis])."&_wp_sn=".urlencode($_REQUEST[_wp_sn])."&_wp_ni=".urlencode($_REQUEST[_wp_ni])."&_wp_unique=".$_REQUEST[_wp_unique]."' value='Wstecz'>";
}

if (($source=='towary-sprzedaz') && ($_REQUEST[edit_zestaw]=='1') && ($_REQUEST[wstecz]==0)) {
	echo "<input type=hidden name=wstecz1 value=0>";
	
	echo "<input type=button class=buttons onClick=\"self.location.href='e_zestaw_obrot.php?zid=$_REQUEST[zid]&new_upid=$_REQUEST[new_upid]&sdata=$_REQUEST[tdata]&hd_zgl_nr=".$_REQUEST[hd_zgl_nr]."&dodajwymianepodzespolow=".$_REQUEST[dodajwymianepodzespolow]."&_wp_opis=".urlencode($_REQUEST[_wp_opis])."&_wp_sn=".urlencode($_REQUEST[_wp_sn])."&_wp_ni=".urlencode($_REQUEST[_wp_ni])."&_wp_unique=".$_REQUEST[_wp_unique]."';\" value='Wstecz'>";
}

if (($source=='towary-sprzedaz') && ($_REQUEST[edit_towar]=='1') && ($_REQUEST[wstecz]==0)) {

	echo "<input type=hidden name=wstecz1 value=0>";
	
	echo "<input type=hidden name=f value='$_REQUEST[tidf]'>";
	echo "<input type=hidden name=pozid value='$_REQUEST[pozid]'>";
	echo "<input type=hidden name=trodzaj value='$_REQUEST[trodzaj]'>";

	echo "<input type=button class=buttons onClick=\"self.location.href='e_towary_obrot.php?tid=$_REQUEST[sid]&f=$_REQUEST[tidf]&pozid=$_REQUEST[pozid]&new_upid=$_REQUEST[new_upid]&sdata=$_REQUEST[tdata]&srodzaj=".urlencode($_REQUEST[trodzaj])."&nazwa_urzadzenia=".$_REQUEST[nazwa_urzadzenia]."&sn_urzadzenia=".$_REQUEST[sn_urzadzenia]."&ni_urzadzenia=".$_REQUEST[ni_urzadzenia]."&hd_zgl_nr=".$_REQUEST[hd_zgl_nr]."&dodajwymianepodzespolow=".$_REQUEST[dodajwymianepodzespolow]."&_wp_opis=".urlencode($_REQUEST[_wp_opis])."&_wp_sn=".urlencode($_REQUEST[_wp_sn])."&_wp_ni=".urlencode($_REQUEST[_wp_ni])."&_wp_unique=".$_REQUEST[_wp_unique]."';\" value='Wstecz'>";
}

//echo "zid : ".$_REQUEST[zid];
if (($source=='towary-sprzedaz') && ($_REQUEST[zestaw]=='1') && ($_REQUEST[edit_zestaw]=='') && ($_REQUEST[wstecz]==1)) {
echo "<input class=buttons type=button onClick=window.location.href='g_zestaw_obrot.php?ewid_id=".$_REQUEST[ewid_id]."&id=$zid&tup=".urlencode($_REQUEST[tup])."&tumowa=".urlencode($_REQUEST[tumowa])."&up=".urlencode($_REQUEST[up])."&tnazwa=".urlencode($_REQUEST[tnazwa])."&tmodel=".urlencode($_REQUEST[tmodel])."&tsn=".urlencode($_REQUEST[tsn])."&tni=".urlencode($_REQUEST[tni])."&tstatus1=".$_REQUEST[tstatus1]."&sz=".$_REQUEST[sz]."&unr=".$_REQUEST[unr]."&cat=".urlencode($_REQUEST[tnazwa])."&tuwagi=".urlencode($uwagi)."&auto=".$_REQUEST[auto]."&f=".$_REQUEST[tidf]."&tdata=".$_REQUEST[tdata]."&trodzaj=".urlencode($_REQUEST[trodzaj])."&pn=".$_REQUEST[pn]."&uwagi=".urlencode($uwagi)."&tuwagi=".urlencode($uwagi)."&obzp=".$_REQUEST[obzp]."&new_upid=".$_REQUEST[new_upid]."&readonly=".$_REQUEST[readonly]."&ewid_id=".$_REQUEST[ewid_id]."&quiet=".$_REQUEST[quiet]."&nazwa_urzadzenia=".urlencode($_REQUEST[nazwa_urzadzenia])."&sn_urzadzenia=".urlencode($_REQUEST[sn_urzadzenia])."&ni_urzadzenia=".urlencode($_REQUEST[ni_urzadzenia])."&hd_zgl_nr=".$_REQUEST[hd_zgl_nr]."&dodajwymianepodzespolow=".$_REQUEST[dodajwymianepodzespolow]."&_wp_opis=".urlencode($_REQUEST[_wp_opis])."&_wp_sn=".urlencode($_REQUEST[_wp_sn])."&_wp_ni=".urlencode($_REQUEST[_wp_ni])."&_wp_unique=".$_REQUEST[_wp_unique]."' value='Wstecz'>";
}

if (($source=='naprawy-wycofaj') && ($_REQUEST[wstecz]!=0)) {
echo "<input class=buttons type=button onClick=window.location.href='z_naprawy_wycofaj.php?ewid_id=".$_REQUEST[ewid_id]."&id=".$_REQUEST[uid]."&tup=".urlencode($_REQUEST[tup])."&up=".urlencode($_REQUEST[up])."&part=".urlencode($part)."&mmodel=".urlencode($_REQUEST[mmodel])."&msn=".urlencode($_REQUEST[msn])."&mni=".urlencode($_REQUEST[mni])."&tstatus1=".$_REQUEST[tstatus1]."&szid=".$_REQUEST[szid]."&unr=".$_REQUEST[unr]."&serwisowy=".$_REQUEST[serwisowy]."&new_upid=".$_REQUEST[new_upid]."&hd_zgl_nr=".$_REQUEST[hd_zgl_nr]."' value='Wstecz'>";
}

if (($source=='wymiana-podzespolow') && ($_REQUEST[wstecz]==1)) {
	echo "<input class=buttons type=button onClick=window.location.href='z_wymiana_wybor_z_ewidencji.php?ewid_id=".$_REQUEST[ewid_id]."&id=$id&tup=".urlencode($_REQUEST[tup])."&up=".urlencode($_REQUEST[up])."&tnazwa=".urlencode($_REQUEST[tnazwa])."&tmodel=".urlencode($_REQUEST[tmodel])."&tsn=".urlencode($_REQUEST[tsn])."&tni=".urlencode($_REQUEST[tni])."&tstatus1=".$_REQUEST[tstatus1]."&sz=".$_REQUEST[sz]."&unr=".$_REQUEST[unr]."&cat=".urlencode($_REQUEST[tnazwa])."&tuwagi=".urlencode($opis_uszkodzenia)."&auto=".$_REQUEST[auto]."&tresc_zgl=".urlencode($_REQUEST[tresc_zgl])."&upid=".urlencode($_REQUEST[upid])."&tup1=".urlencode($nazwa)."&new_upid=".$_REQUEST[new_upid]."&popraw_w_ewidencji=".$_REQUEST[popraw_w_ewidencji]."&popraw_dane=".$_REQUEST[popraw_dane]."&dodaj_sprzet_do_ewidencji=".$_REQUEST[dodaj_sprzet_do_ewidencji]."&dodaj_dane=".$_REQUEST[dodaj_dane]."&dodaj_do_ewidencji=".$_REQUEST[dodaj_do_ewidencji]."&tuwagi=".urlencode($wykonane_czynnosci)."&sell_towar=".$_REQUEST[sell_towar]."&hd_nr=".$_REQUEST[hd_nr]."&hd_zgl_nr=".$_REQUEST[hd_zgl_nr]."' value='Wstecz'>";
}

echo "</span>";

echo "<input type=hidden name=auto value=$auto>";
echo "<input type=hidden name=wersjap value=2>";
echo "<input type=hidden name=source value=$source>";
//echo "<input type=hidden id=newupid name=newupid value=''>";
echo "<input type=hidden name=state value=$state>";
echo "<input type=hidden name=nowy value='$nowy'>";

echo "<input type=hidden name=tumowa value='$_REQUEST[tumowa]'>";
echo "<input type=hidden name=tstatus1 value='$_REQUEST[tstatus1]'>";

if (($source=='magazyn-pobierz') || ($source=='magazyn-zwrot')) {
	//echo "<input type=hidden name=tid value='$_REQUEST[id]'>";
	echo "<input type=hidden name=tid value='$_REQUEST[tid]'>";
} else 
	echo "<input type=hidden name=tid value='$_REQUEST[tid]'>";

echo "<input type=hidden name=tup value='$_REQUEST[tup]'>";
//echo "<input type=hidden name=up value='$_REQUEST[up]'>";
//echo "<input type=hidden name=up value='$_REQUEST[tup]'>";
echo "<input type=hidden name=tkomentarz value='$_REQUEST[tkomentarz]'>";
//echo "<input type=hidden name=unr value='$unr'>";
echo "<input type=hidden name=wstecz value='$_REQUEST[wstecz]'>";

if ($source!='towary-sprzedaz') echo "<input type=hidden name=sz value='$_REQUEST[sz]'>";

echo "<input type=hidden name=popraw value='$_REQUEST[popraw]'>";
echo "<input type=hidden name=nazwa_sprzetu value='$_REQUEST[nazwa_sprzetu]'>";
echo "<input type=hidden name=sn_sprzetu value='$_REQUEST[sn_sprzetu]'>";

if ($source=='naprawy-wycofaj') {
	echo "<input type=hidden name=szid value='$_REQUEST[szid]'>";
	echo "<input type=hidden name=status value='$_REQUEST[status]'>";
	echo "<input type=hidden name=uid value='$_REQUEST[uid]'>";
	echo "<input type=hidden name=ewid value='$_REQUEST[ewid]'>";
	echo "<input type=hidden name=serwisowy value='$_REQUEST[serwisowy]'>";
}

if (($source=='magazyn-zwrot') && ($_REQUEST[naprawa_pozostaje]==1)) {
	echo "<input type=hidden name=szid value='$_REQUEST[szid]'>";
	echo "<input type=hidden name=uid value='$_REQUEST[uid]'>";
}

/*
  echo "szid = $_REQUEST[szid]<br />";
  echo "status = $_REQUEST[status]<br />";
  echo "uid = $_REQUEST[uid]<br />";
  echo "ewid = $_REQUEST[ewid]<br />";
  echo "tup = $_REQUEST[tup]<br />";
  echo "serwisowy = $_REQUEST[serwisowy]<br />";
*/

if ($source=='towary-sprzedaz') {
	echo "<input type=hidden name=tnazwa value='$_REQUEST[tnazwa]'>";
	echo "<input type=hidden name=tsn value='$_REQUEST[tsn]'>";
	echo "<input type=hidden name=tcenaodsp value='$_REQUEST[tcenaodsp]'>";
	echo "<input type=hidden name=tcena value='$_REQUEST[tcena]'>";
	echo "<input type=hidden name=ttyp value='$_REQUEST[ttyp]'>";
	echo "<input type=hidden name=tidf value='$_REQUEST[tidf]'>";	
	echo "<input type=hidden name=trodzaj value='Usługa'>";
	echo "<input type=hidden name=tdata value='$_REQUEST[tdata]'>";
	echo "<input type=hidden name=obzp value='$_REQUEST[obzp]'>";	

	echo "<input type=hidden name=tid value='$_REQUEST[tid]'>";	
	echo "<input type=hidden name=zid value='$_REQUEST[zid]'>";		
}

if ($source=='naprawy-zwrot') {
	echo "<input type=hidden name=trodzaj value='Usługa'>";
	echo "<input type=hidden name=ps value=$_REQUEST[ps]>";
//	echo "<input type=hidden name=id value=$_REQUEST[id]>";
	
}
//echo "$source";

//echo "$_REQUEST[tup] - $_REQUEST[up]";
echo "<input type=hidden name=nierobzapytan value=$_REQUEST[nierobzapytan]>";
echo "<input type=hidden name=antyF5 value=$antyF5key>";
echo "<input type=hidden name=odswiez value=$odswiez>";
echo "<input type=hidden name=choosefromewid value=$choosefromewid>";

echo "<input type=hidden name=ewid_id value=$_REQUEST[ewid_id]>";
echo "<input type=hidden name=popraw_w_ewidencji value=$_REQUEST[popraw_w_ewidencji]>";

echo "<input type=hidden name=jedna_umowa value=$_REQUEST[jedna_umowa]>";
echo "<input type=hidden name=tumowa value='".$_REQUEST[tumowa]."'>";
echo "<input type=hidden name=trodzaj value='".$_REQUEST[trodzaj]."'>";

if ($source=='naprawy-przyjecie') {
	echo "<input type=hidden name=tuwagi value='".urlencode($_REQUEST[tuwagi])."'>";
}

echo "<input type=hidden name=tstatus value=".$_REQUEST[tstatus].">";
echo "<input type=hidden name=ttyp value='".$_REQUEST[ttyp]."'>";
echo "<input type=hidden name=tidf value='".$_REQUEST[tidf]."'>";
echo "<input type=hidden name=tid value='".$_REQUEST[tid]."'>";
echo "<input type=hidden name=tcenaodsp value='".$_REQUEST[tcenaodsp]."'>";
echo "<input type=hidden name=tcena value='".$_REQUEST[tcena]."'>";
echo "<input type=hidden name=tnazwa value='".$_REQUEST[tnazwa]."'>";

echo "<input type=hidden name=zestaw value=$_REQUEST[zestaw]>";

echo "<input type=hidden name=naprawa_pozostaje value='$_REQUEST[naprawa_pozostaje]'>";
echo "<input type=hidden name=dodaj_sprzet_do_ewidencji value='".$_REQUEST[dodaj_sprzet_do_ewidencji]."'>";

echo "<input type=hidden name=from value='$_REQUEST[from]'>";
echo "<input type=hidden name=hd_nr value='$_REQUEST[hd_nr]'>";
echo "<input type=hidden name=hd_podkategoria_nr value='$_REQUEST[hd_podkategoria_nr]'>";

echo "<input type=hidden name=edit_zestaw value='$_REQUEST[edit_zestaw]'>";
echo "<input type=hidden name=edit_towar value='$_REQUEST[edit_towar]'>";

echo "<input type=hidden name=sid value='$_REQUEST[sid]'>";	
	
echo "<input type=hidden name=quiet value='$_REQUEST[quiet]'>";	
echo "<input type=hidden name=readonly value='$_REQUEST[readonly]'>";	
echo "<input type=hidden name=naprawaid value='$_REQUEST[naprawaid]'>";

if ($_REQUEST[hd_zgl_nr]!='') echo "<input type=hidden name=hd_zgl_nr value='$_REQUEST[hd_zgl_nr]'>";	

echo "<input type=hidden name=_wp_opis value='".$_REQUEST[_wp_opis]."'>";
echo "<input type=hidden name=_wp_sn value='".$_REQUEST[_wp_sn]."'>";
echo "<input type=hidden name=_wp_ni value='".$_REQUEST[_wp_ni]."'>";
echo "<input type=hidden name=_wp_unique value='".$_REQUEST[_wp_unique]."'>";

echo "<input type=hidden name=dodajwymianepodzespolow value='$_REQUEST[dodajwymianepodzespolow]'>";
echo "<input type=hidden name=powiazzhd value='$_REQUEST[powiazzhd]'>";

//if ($source=='') addownsubmitwithname("'Drukuj pusty'","czysty","submit");

if ($source=='ewidencja-przesuniecia') {
	echo "<input id=submit type=submit class=buttons name=submit value='Zapisz & Generuj' onClick=\"return _onSubmit('Potwierdzasz poprawność danych na protokole ?',0);\"/>";
} else {
	if ($_REQUEST[nzpdb]!='on') echo "<input id=submit type=submit class=buttons name=submit value='Zapisz & Generuj' onClick=\"return _onSubmit('Potwierdzasz poprawność danych na protokole ?',1);\"/>";

//addownsubmitwithname("'Zapisz & Generuj'","submit","submit");
	if ($_REQUEST[nzpdb]=='on') echo "<input id=submit type=submit class=buttons name=submit value='Generuj protokół' onClick=\"return _onSubmit('Potwierdzasz poprawność danych na protokole ?',1);\"/>";
//addownsubmitwithname("'Generuj protokół'","submit","submit");
}
echo "<span id=Saving style=display:none><b><font color=red>Trwa zapisywanie zgłoszenia... proszę czekać&nbsp;</font></b><input class=imgoption type=image src=img/loader.gif></span>";

//if ($source=='only-protokol') addownsubmitwithname("'Generuj protokół'","submit","submit");

//addownsubmitwithname("'Zapisz'","zapisz","submit");
addownsubmitwithname("'Cofnij zmiany'","reset","reset");
//if ($odswiez_openera!=2) { addclosewithreloadbutton("Anuluj"); } else addbuttons("anuluj");
addbuttons("","anuluj");
endbuttonsarea();
_form();

//print_r($_REQUEST);

?>
<script language="JavaScript">
var cal1 = new calendar1(document.forms['protokol'].elements['pdata']);
	cal1.year_scroll = true;
	cal1.time_comp = false;
</script>
<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("protokol");
  frmvalidator.addValidation("pdata","req","Nie podano daty");
<!----  frmvalidator.addValidation("pnr","req","Nie podano numeru protokołu"); --->
  //frmvalidator.addValidation("pnr","alnumhyphen","Użyłeś niedozwolonych znaków w polu \"Nr protokołu\"");
  frmvalidator.addValidation("pdata","numerichyphen","Użyłeś niedozwolonych znaków w dacie");
	frmvalidator.addValidation("hd_zgl_nr","req","Nie podałeś numeru zgłoszenia Helpdesk");  
for (i=1; i<9; i++) {
	if (document.getElementById('c_'+i+'').disabled==false) { document.getElementById('c_'+i+'a').style.color='black'; } else { document.getElementById('c_'+i+'a').style.color='grey';}
}

</script>
<?php if ($BKWB == 1) { ?><script>document.getElementById('submit').style.display='none';</script><?php } else { ?><script>document.getElementById('submit').style.display='';</script><?php } ?>

<?php if (($source=='only-protokol') && ($_REQUEST[getfromparentwindow]==1)) { ?>
	<script>
		document.getElementById('wykonane_czynnosci').value = opener.document.getElementById('zs_wcz').value;
	</script>
<?php } ?>
<script>HideWaitingMessage();</script>
</body>
</html>