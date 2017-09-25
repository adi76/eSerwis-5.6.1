<?php include_once('header.php'); ?>
<html>
<head>
<title><?php echo "$nazwa_aplikacji"; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<?php
echo "<body onLoad='document.forms[0].elements[0].focus();'>";

$czynnosc1 = 'Oprogramowanie';
$czynnosc2 = 'Sprzęt komputerowy';
$czynnosc3 = 'Konserwacja sprzętu';

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
} else $pnr=1;

$current_filia	= $es_skrot;
if (($dzien=='') || ($miesiac=='') || ($rok=='')) {
	$dzien		= Date('d');
	$miesiac	= Date('m');
	$rok		= Date('Y');
}

$pdata=$rok.'-'.$miesiac.'-'.$dzien;

if (($miesiac=='') && ($rok=='')) {
	$format_number	= $current_filia.'/'.$current_month.'/'.$current_year;
} else {
	$format_number	= $current_filia.'/'.$miesiac.'/'.$rok;
}

pageheader("Utwórz nowy protokół");
starttable();
echo "<form name=protokol action=utworz_protokol_szczecin_generuj.php method=GET>";
tr_();
	td(";l;<legend>PROTOKÓŁ</legend>");
_tr();
tr_();
	td("110;r;Nr");
	td_(";l;");
		echo "<input style='text-align:right; font-size:9px;' type=text name=pnr size=10 maxlength=10 value='' onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();
tr_();
	td("110;r;do umowy nr");
	td_(";l;");
		$result5 = mysql_query("SELECT umowa_nr, umowa_opis FROM $dbname.serwis_umowy WHERE belongs_to=$es_filia ORDER BY umowa_nr", $conn) or die($k_b);
		echo "<select style='text-align:left; font-size: 9px;' name=umowa_nr onkeypress='return handleEnter(this, event);'>\n";
		echo "<option value=''>Wybierz z listy...";
		while (list($temp_nr,$temp_opis)=mysql_fetch_array($result5)) {
			echo "<option";
			echo " value='$temp_nr'>$temp_nr ($temp_opis)</option>\n"; 
		}
		echo "</select>\n"; 
	_td();
_tr();
tr_();
	td_(";;");
	_td();
	td_(";;");
	_td();
_tr();
endtable();

echo "<table cellpadding=1 cellspacing=1 align=center>";
tr_();
	td(";l;<legend>ZGŁOSZENIE</legend>");
_tr();
tr_();	td_(";;");	_td();	td_(";;");	_td();_tr();
tr_();
	td("110;r;<legend>Nr urzędu</legend>");
	td_(";;");
	if ($es_m==1) {
		$result = mysql_query("SELECT up_id,up_nazwa,up_pion_id,up_umowa_id FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.up_pion_id=serwis_piony.pion_id) ORDER BY serwis_piony.pion_nazwa,up_nazwa", $conn) or die($k_b);
	} else {
		$result = mysql_query("SELECT up_id,up_nazwa,up_pion_id,up_umowa_id FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE belongs_to=$es_filia and (serwis_komorki.up_pion_id=serwis_piony.pion_id) ORDER BY serwis_piony.pion_nazwa,up_nazwa", $conn) or die($k_b);
	}
		echo "<select style='text-align:left; font-size: 9px;' name=up onkeypress='return handleEnter(this, event);'>\n";
		echo "<option value=''>Wybierz z listy...";
		while (list($temp_id,$temp_nazwa,$temp_pion_id,$temp_umowa_id)=mysql_fetch_array($result)) {
			$result444=mysql_query("SELECT pion_nazwa FROM $dbname.serwis_piony WHERE (pion_id=$temp_pion_id) LIMIT 1",$conn) or die($k_b);
			list($pionnazwa)=mysql_fetch_array($result444);
			
			echo "<option";
			$pionup = $pionnazwa." ".$temp_nazwa;
			if ($findpion!=1) {
				if ($pionup==$up) { 
					echo " SELECTED";
				}
			} else {
				if ($temp_id==$upid) echo " SELECTED";
			}
			
			echo " value='$pionnazwa $temp_nazwa'>$pionnazwa $temp_nazwa</option>\n"; 
		}
		echo "</select>\n"; 	
	_td();
_tr();
tr_();
	td("110;r;<legend>Data</legend>");
	td_(";;");
		echo "<input style='text-align:center; font-size: 9px;' type=text name=zgl_data size=10 maxlength=10 value=$pdata onkeypress='return handleEnter(this, event);'>";
		echo "<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif align=top width=16 height=16 border=0></a>&nbsp;";
		echo "&nbsp;";
		echo "Godzina: ";
		echo "<input style='text-align:left; font-size: 9px;' type=text name=zgl_godzina size=8 maxlength=8 value='' onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();
tr_();
	td("110;r;<legend>Osoba zgłaszająca</legend>");
	td_(";;");
		echo "<input style='text-align:left; font-size: 9px;' type=text name=zgl_osoba size=50 maxlength=50 value='' onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();
tr_();	td_(";;");	_td();	td_(";;");	_td();_tr();
endtable();

echo "<table cellpadding=1 cellspacing=1 align=center>";
tr_();
	td(";l;<legend>WYKONANIE</legend>");
_tr();
tr_();	td_(";;");	_td();	td_(";;");	_td();_tr();
tr_();
	td("110;r;<legend>Data</legend>");
	td_(";;");
		echo "<input style='text-align:center; font-size: 9px;' type=text name=wyk_data size=10 maxlength=10 value=$pdata onkeypress='return handleEnter(this, event);'>";
		echo "<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif align=top width=16 height=16 border=0></a>&nbsp;";
	_td();
_tr();
tr_();
	td("110;r;<legend>Godzina</legend>");
	td_(";;");
		echo "od "; 
		echo "<input style='text-align:left; font-size: 9px;' type=text name=wyk_godzina_od size=8 maxlength=8 value='' onkeypress='return handleEnter(this, event);'>";
		echo "&nbsp;do&nbsp;";
		echo "<input style='text-align:left; font-size: 9px;' type=text name=wyk_godzina_do size=8 maxlength=8 value='' onkeypress='return handleEnter(this, event);'>";	
	_td();
_tr();	
tr_();
	td_(";r;");
		echo "<input type=checkbox name=wyk_dodakowo_platne onkeypress='return handleEnter(this, event);'>";
	_td();
	td_(";l;");
		echo "<span style='cursor:hand' onclick=labelClick(document.protokol.wyk_dodakowo_platne)>Realizowane jako dodatkowo płatne</span>";
	_td();
_tr();
tr_();	td_(";;");	_td();	td_(";;");	_td();_tr();
endtable();

echo "<table cellpadding=1 cellspacing=1 align=center>";
tr_();
	td("110;l;<legend>CEL WIZYTY</legend>");
	td(";;");
_tr();
tr_();	td_(";;");	_td();	td_(";;");	_td();_tr();
tr_();
	td_("110;r;");
		echo "<input type=checkbox name=c_1 ";
		if ($_GET[c_1]=='on') { echo "checked=checked"; } 
		echo " onkeypress='return handleEnter(this, event);'>";
	_td();
	td_(";;");
		echo "<span style='cursor:hand' onclick=labelClick(document.protokol.c_1)>$czynnosc1</span>";		
	_td();
_tr();
tr_();
	td_("110;r;");
		echo "<input type=checkbox name=c_2 ";
		if ($_GET[c_2]=='on') { echo "checked=checked"; } 
		echo " onkeypress='return handleEnter(this, event);'>";
	_td();
	td_(";;");

		echo "<span style='cursor:hand' onclick=labelClick(document.protokol.c_2)>$czynnosc2</span>";
	_td();
_tr();
tr_();
	td_("110;r;");
		echo "<input type=checkbox name=c_3 ";
		if ($_GET[c_3]=='on') { echo "checked=checked"; } 
		echo " onkeypress='return handleEnter(this, event);'>";	
	_td();
	td_(";;");
		echo "<span style='cursor:hand' onclick=labelClick(document.protokol.c_3)>$czynnosc3</span>";
	_td();
_tr();
tr_();
	td_(";r;");
		//echo "<input type=checkbox name=c_cel ";
		//if ($_GET[c_4]=='on') { echo "checked=checked"; } 
		//echo " onkeypress='return handleEnter(this, event);'>";
		echo "inny";
	_td();
	td_(";;");
		echo "<input style='text-align:left; font-size: 9px;' type=text name=cel_inny size=80 maxlength=80 value='' onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();
tr_();	td_(";;");	_td();	td_(";;");	_td(); _tr();
endtable();
echo "<table cellpadding=1 cellspacing=1 align=center>";
tr_();
	td("110;l;<legend>OSOBA WYKONUJĄCA</legend>");
	td(";;");
_tr();
tr_();	td_(";;");	_td();	td_(";;");	_td();_tr();
tr_();
	td(";;");
	td_(";;");
		$result5 = mysql_query("SELECT user_first_name, user_last_name FROM $dbname.serwis_uzytkownicy WHERE belongs_to=$es_filia ORDER BY user_last_name", $conn) or die($k_b);
		echo "<select style='text-align:left; font-size: 9px;' name=osoba_wyk1 onkeypress='return handleEnter(this, event);'>\n";
		echo "<option value=''>Wybierz z listy...";
		while (list($temp_imie,$temp_nazwisko)=mysql_fetch_array($result5)) {
			echo "<option";
			$user = $temp_imie." ".$temp_nazwisko;
			echo " value='$user'>$user</option>\n"; 
		}
		echo "</select>\n"; 			
	_td();
_tr();
tr_();
	td(";;");
	td_(";;");
		$result5 = mysql_query("SELECT user_first_name, user_last_name FROM $dbname.serwis_uzytkownicy WHERE belongs_to=$es_filia ORDER BY user_last_name", $conn) or die($k_b);
		echo "<select style='text-align:left; font-size: 9px;' name=osoba_wyk2 onkeypress='return handleEnter(this, event);'>\n";
		echo "<option value=''>Wybierz z listy...";
		while (list($temp_imie,$temp_nazwisko)=mysql_fetch_array($result5)) {
			echo "<option";
			$user = $temp_imie." ".$temp_nazwisko;
			echo " value='$user'>$user</option>\n"; 
		}
		echo "</select>\n"; 			
	_td();
_tr();
tr_();
	td(";;");
	td_(";;");
		$result5 = mysql_query("SELECT user_first_name, user_last_name FROM $dbname.serwis_uzytkownicy WHERE belongs_to=$es_filia ORDER BY user_last_name", $conn) or die($k_b);
		echo "<select style='text-align:left; font-size: 9px;' name=osoba_wyk3 onkeypress='return handleEnter(this, event);'>\n";
		echo "<option value=''>Wybierz z listy...";
		while (list($temp_imie,$temp_nazwisko)=mysql_fetch_array($result5)) {
			echo "<option";
			$user = $temp_imie." ".$temp_nazwisko;
			echo " value='$user'>$user</option>\n"; 
		}
		echo "</select>\n"; 			
	_td();
_tr();
tr_();
	td_(";r;");
		//echo "<input type=checkbox name=osoba_inna onkeypress='return handleEnter(this, event);'>";
		echo "inna";
	_td();
	td_(";l;");
		echo "<input style='text-align:left; font-size: 9px;' type=text name=osoba_inna size=50 maxlength=50 value='' onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();
tr_();	td_(";;");	_td();	td_(";;");	_td(); _tr();
endtable();
echo "<table cellpadding=1 cellspacing=1 align=center>";
tr_();
	td("110;l;<legend>OPIS CZYNNOŚCI</legend>");
	td(";;");
_tr();
tr_();	td_(";;");	_td();	td_(";;");	_td();_tr();
tr_();
	td("110;rt;<legend></legend>");
	td_(";;");
		echo "<textarea style='text-align:left; font-size: 11px;' name=wykonane_czynnosci cols=70 rows=5>".cleanup(cleanup($wykonane_czynnosci))."</textarea>";
	_td();
_tr();
tr_();	td_(";;");	_td();	td_(";;");	_td();_tr();
endtable();

echo "<table cellpadding=1 cellspacing=1 align=center>";
tr_();
	echo "<td colspan=7 align=left>";
	echo "<legend>MATERIAŁY / TOWARY / SPRZĘT</legend>";
	echo "</td>";
_tr();
tr_();	td_(";;");	_td();	td_(";;");	_td();_tr();
tr_();
	td("110;rt;<legend></legend>");
	td_(";;");
		echo "Nazwa";
	_td();
	td_(";c;");
		echo "nr seryjny";
	_td();
	td_(";c;");
		echo "dołożone";
	_td();
	td_(";c;");
		echo "pobrane";
	_td();
	td_(";c;");
		echo "pobrane<br />do naprawy";
	_td();
	td_(";c;");
		echo "zwrot<br />po naprawie";
	_td();
_tr();
tr_();
	td(";r;<legend>1</legend>");
	td_(";;");
		echo "<input style='text-align:left; font-size: 9px;' type=text name=t1_o size=50 maxlength=50 value='' onkeypress='return handleEnter(this, event);'>";
	_td();
	td_(";;");
		echo "<input style='text-align:left; font-size: 9px;' type=text name=t1_sn size=30 maxlength=30 value='' onkeypress='return handleEnter(this, event);'>";
	_td();
	td_(";c;");
		echo "<input type=checkbox name=t1_dolozone onkeypress='return handleEnter(this, event);'>";
	_td();
	td_(";c;");
		echo "<input type=checkbox name=t1_pobrane onkeypress='return handleEnter(this, event);'>";
	_td();
	td_(";c;");
		echo "<input type=checkbox name=t1_naprawa onkeypress='return handleEnter(this, event);'>";
	_td();
	td_(";c;");
		echo "<input type=checkbox name=t1_zwrot onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();
tr_();
	td(";r;<legend>2</legend>");
	td_(";;");
		echo "<input style='text-align:left; font-size: 9px;' type=text name=t2_o size=50 maxlength=50 value='' onkeypress='return handleEnter(this, event);'>";
	_td();
	td_(";;");
		echo "<input style='text-align:left; font-size: 9px;' type=text name=t2_sn size=30 maxlength=30 value='' onkeypress='return handleEnter(this, event);'>";
	_td();
	td_(";c;");
		echo "<input type=checkbox name=t2_dolozone onkeypress='return handleEnter(this, event);'>";
	_td();
	td_(";c;");
		echo "<input type=checkbox name=t2_pobrane onkeypress='return handleEnter(this, event);'>";
	_td();
	td_(";c;");
		echo "<input type=checkbox name=t2_naprawa onkeypress='return handleEnter(this, event);'>";
	_td();
	td_(";c;");
		echo "<input type=checkbox name=t2_zwrot onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();
tr_();
	td(";r;<legend>3</legend>");
	td_(";;");
		echo "<input style='text-align:left; font-size: 9px;' type=text name=t3_o size=50 maxlength=50 value='' onkeypress='return handleEnter(this, event);'>";
	_td();
	td_(";;");
		echo "<input style='text-align:left; font-size: 9px;' type=text name=t3_sn size=30 maxlength=30 value='' onkeypress='return handleEnter(this, event);'>";
	_td();
	td_(";c;");
		echo "<input type=checkbox name=t3_dolozone onkeypress='return handleEnter(this, event);'>";
	_td();
	td_(";c;");
		echo "<input type=checkbox name=t3_pobrane onkeypress='return handleEnter(this, event);'>";
	_td();
	td_(";c;");
		echo "<input type=checkbox name=t3_naprawa onkeypress='return handleEnter(this, event);'>";
	_td();
	td_(";c;");
		echo "<input type=checkbox name=t3_zwrot onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();
tr_();
	td(";r;<legend>4</legend>");
	td_(";;");
		echo "<input style='text-align:left; font-size: 9px;' type=text name=t4_o size=50 maxlength=50 value='' onkeypress='return handleEnter(this, event);'>";
	_td();
	td_(";;");
		echo "<input style='text-align:left; font-size: 9px;' type=text name=t4_sn size=30 maxlength=30 value='' onkeypress='return handleEnter(this, event);'>";
	_td();
	td_(";c;");
		echo "<input type=checkbox name=t4_dolozone onkeypress='return handleEnter(this, event);'>";
	_td();
	td_(";c;");
		echo "<input type=checkbox name=t4_pobrane onkeypress='return handleEnter(this, event);'>";
	_td();
	td_(";c;");
		echo "<input type=checkbox name=t4_naprawa onkeypress='return handleEnter(this, event);'>";
	_td();
	td_(";c;");
		echo "<input type=checkbox name=t4_zwrot onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();
tr_();
	td(";r;<legend>5</legend>");
	td_(";;");
		echo "<input style='text-align:left; font-size: 9px;' type=text name=t5_o size=50 maxlength=50 value='' onkeypress='return handleEnter(this, event);'>";
	_td();
	td_(";;");
		echo "<input style='text-align:left; font-size: 9px;' type=text name=t5_sn size=30 maxlength=30 value='' onkeypress='return handleEnter(this, event);'>";
	_td();
	td_(";c;");
		echo "<input type=checkbox name=t5_dolozone onkeypress='return handleEnter(this, event);'>";
	_td();
	td_(";c;");
		echo "<input type=checkbox name=t5_pobrane onkeypress='return handleEnter(this, event);'>";
	_td();
	td_(";c;");
		echo "<input type=checkbox name=t5_naprawa onkeypress='return handleEnter(this, event);'>";
	_td();
	td_(";c;");
		echo "<input type=checkbox name=t5_zwrot onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();
tr_();
	td(";r;<legend>6</legend>");
	td_(";;");
		echo "<input style='text-align:left; font-size: 9px;' type=text name=t6_o size=50 maxlength=50 value='' onkeypress='return handleEnter(this, event);'>";
	_td();
	td_(";;");
		echo "<input style='text-align:left; font-size: 9px;' type=text name=t6_sn size=30 maxlength=30 value='' onkeypress='return handleEnter(this, event);'>";
	_td();
	td_(";c;");
		echo "<input type=checkbox name=t6_dolozone onkeypress='return handleEnter(this, event);'>";
	_td();
	td_(";c;");
		echo "<input type=checkbox name=t6_pobrane onkeypress='return handleEnter(this, event);'>";
	_td();
	td_(";c;");
		echo "<input type=checkbox name=t6_naprawa onkeypress='return handleEnter(this, event);'>";
	_td();
	td_(";c;");
		echo "<input type=checkbox name=t6_zwrot onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();
tr_();	td_(";;");	_td();	td_(";;");	_td();_tr();

endtable();


echo "<input type=hidden name=format_nr value='$format_number'>";
echo "<input type=hidden name=unr value='$unr'>";
startbuttonsarea("right");
echo "<input type=hidden name=wersjap value=2>";
//addownsubmitwithname("'Dane o urządzeniu z ewidencji'","ewidencja","submit");
addownsubmitwithname("'Drukuj pusty'","czysty","submit");
addownsubmitwithname("'Generuj'","submit","submit");
//addownsubmitwithname("'Zapisz'","zapisz","submit");
addownsubmitwithname("'Cofnij zmiany'","reset","reset");
if ($odswiez_openera!=2) { addclosewithreloadbutton("Anuluj"); } else addbuttons("anuluj");
endbuttonsarea();
_form();
?>
<script language="JavaScript">
var cal1 = new calendar1(document.forms['protokol'].elements['zgl_data']);
var cal2 = new calendar1(document.forms['protokol'].elements['wyk_data']);
	cal1.year_scroll = true;
	cal1.time_comp = false;
	cal2.year_scroll = true;
	cal2.time_comp = false;	
</script>
<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("protokol");
 // frmvalidator.addValidation("zgl_data","req","Nie podano daty zgłoszenia");
 // frmvalidator.addValidation("wyk_data","req","Nie podano daty wykonania");
  <!----  frmvalidator.addValidation("pnr","req","Nie podano numeru protokołu"); --->
  frmvalidator.addValidation("pnr","alnumhyphen","Użyłeś niedozwolonych znaków w polu \"Nr protokołu\"");
  frmvalidator.addValidation("zgl_data","numerichyphen","Użyłeś niedozwolonych znaków w dacie zgłoszenia");
  frmvalidator.addValidation("wyk_data","numerichyphen","Użyłeś niedozwolonych znaków w dacie wykonania");  
</script>
</body>
</html>