<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php'); 
?>
<body OnLoad="document.forms[0].elements[4].focus();">
<?php
function toUpper($string) {
	$string = strtr($string,'qwertyuiopasdfghjklzxcvbnm','QWERTYUIOPASDFGHJKLZXCVBNM');
	return (strtr($string, 'ęóąśłżźćń','ĘÓĄŚŁŻŹĆŃ' )); 
}; 

$result = mysql_query("SELECT naprawa_id,naprawa_nazwa,naprawa_model,naprawa_sn,naprawa_status,naprawa_sprzet_zastepczy_id,naprawa_uwagi FROM $dbname.serwis_naprawa WHERE (naprawa_id=$id) LIMIT 1", $conn) or die($k_b);
list($temp_id,$temp_nazwa,$temp_model,$temp_sn,$temp_status,$temp_szid,$temp_uwagi)=mysql_fetch_array($result);
pageheader("Zmiana statusu naprawy uszkodzonego sprzętu");
infoheader("".$temp_nazwa." ".$temp_model." (SN: ".$temp_sn.")");

if ($_REQUEST[hdzglid]!='') {
	
	list($GetBelongsToFromHD)=mysql_fetch_array(mysql_query("SELECT belongs_to FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id=$_REQUEST[hdzglid]) LIMIT 1"));
	
	list($FiliaNazwa)=mysql_fetch_array(mysql_query("SELECT filia_nazwa FROM $dbname.serwis_filie WHERE (filia_id=$GetBelongsToFromHD) LIMIT 1"));

	if ($_REQUEST[obcezgloszenie]==1) { $dopisek = "z filii: <b>".$FiliaNazwa."</b>"; } else { $dopisek = ""; }

	if ($_REQUEST[hdzglid]!='') naprawaheader("<center>Ta naprawa jest powiązana ze zgłoszeniem $dopisek nr <b>$_REQUEST[hdzglid]</b> w bazie Helpdesk</center>");
}

session_register('dodaj_krok_do_zgloszenia_sprzet_naprawiony');
session_register('dodaj_krok_do_zgloszenia_pow_z_naprawa');

$_SESSION[dodaj_krok_do_zgloszenia_pow_z_naprawa]='nie';
$_SESSION[dodaj_krok_do_zgloszenia_sprzet_naprawiony]='nie';

starttable();
echo "<form name=edu action=z_naprawy_serwis.php method=POST>";
tbl_empty_row();
	tr_();
		td(";r;Nowy status sprzętu");
		td_(";l");
			echo "<input type=hidden name=ew_id value=$temp_ew_id>";
		/*	$result = mysql_query("SELECT sn_id,sn_nazwa FROM $dbname.serwis_sposob_naprawy WHERE (sn_id='3')", $conn) or die($k_b);
			echo "<select name=tstatus1>\n"; 					 				
			while (list($temp_id1,$temp_nazwa1)=mysql_fetch_array($result)) {
				echo "<option value='$temp_id1' ";
				echo ">$temp_nazwa1</option>\n";
			}
			echo "</select>\n"; 
		*/
			echo "<input type=hidden name=tstatus1 value=3>";
			echo "<b>sprzęt naprawiony</b>";
		_td();
		td(";;;");
	_tr();
		tbl_empty_row();
	tr_();
		td(";r;Data zmiany statusus");
		td_(";l");
			$dddd = Date('Y-m-d');
			echo "<input type=text size=8 maxlength=10 value='$dddd' id=dzstatusu name=dzstatusu>";
			echo "&nbsp;<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img src=img/cal.gif width=16 height=16 border=0></a>";
			if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('dzstatusu').value='".Date('Y-m-d')."'; return false;\">";
		_td();
	_tr();
	tbl_empty_row();
	tr_();
		td(";rt;Uwagi");
		td_(";l");
			echo "<textarea readonly name=duwagi_old tabindex=-1 rows=3 cols=60 style='background-color:transparent'>".br2nl($temp_uwagi)."</textarea>";
		_td();
	_tr();
	tr_();
		td(";rt;Wykonane naprawy");
		td_(";l");
			echo "<textarea name=duwagi rows=3 cols=60></textarea>";
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
				echo "<b>do oddania</b>";
			_td();
		_tr();
		tr_();
			td(";rt;Wykonane czynności");
			td_(";;;");	
				echo "<textarea name=zs_wcz id=zs_wcz cols=55 rows=2>$_REQUEST[zs_wcz]</textarea>";
				echo "<span id=PowiazaneZWyjazdem style=display:''>";
				echo "&nbsp;<input class=border0 type=checkbox name=PozwolWpisacKm id=PozwolWpisacKm onClick=\"if (this.checked) { document.getElementById('DataWyjazdu').style.display=''; document.getElementById('WpiszWyjazdTrasa').style.display=''; document.getElementById('WpiszWyjazdKm').style.display=''; } else { document.getElementById('DataWyjazdu').style.display='none'; document.getElementById('WpiszWyjazdTrasa').style.display='none'; document.getElementById('WpiszWyjazdKm').style.display='none'; } \" "; 
				
				if ($_REQUEST[PozwolWpisacKm]=='on') echo "checked"; 
				
				echo "/>";
			
				echo "<a onClick=\"if (document.getElementById('PozwolWpisacKm').checked) { document.getElementById('PozwolWpisacKm').checked=false; } else { document.getElementById('PozwolWpisacKm').checked=true; } if (document.getElementById('PozwolWpisacKm').checked) { document.getElementById('DataWyjazdu').style.display=''; document.getElementById('WpiszWyjazdTrasa').style.display=''; document.getElementById('WpiszWyjazdKm').style.display=''; } else { document.getElementById('DataWyjazdu').style.display='none'; document.getElementById('WpiszWyjazdTrasa').style.display='none'; document.getElementById('WpiszWyjazdKm').style.display='none'; }\"> Powiązane z wyjazdem</a>";
				echo "</span>";
			_td();
		_tr();
		echo "<tr>";
			td(";r;Czas wykonywania");
			td_(";;;");
				echo "<input style=text-align:right type=text id=czas_wykonywania_h name=czas_wykonywania_h value='$_REQUEST[czas_wykonywania_h]' maxlength=3 size=2 onKeyPress=\"return filterInput(1, event, false); \" /> godzin";
				echo "&nbsp;";
				echo "<input style=text-align:right type=text id=czas_wykonywania_m name=czas_wykonywania_m value='$_REQUEST[czas_wykonywania_m]' maxlength=3 size=2 onKeyPress=\"return filterInput(1, event, false); \" /> minut";
				
			_td();
		echo "</tr>";
		echo "<tr id=WpiszWyjazdTrasa style='display:"; 
		if ($_REQUEST[PozwolWpisacKm]=='on') { echo ""; } else { echo "none"; }
		echo "'>";
			td(";rt;<br /><br /><b>Trasa wyjazdowa</b>");
			td_(";;;");
				list($GetBelongsToFromHD)=mysql_fetch_array(mysql_query("SELECT belongs_to FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id=$_REQUEST[hdzglid]) LIMIT 1"));
				$result_k = mysql_query("SELECT filia_lokalizacja FROM $dbname.serwis_filie WHERE filia_id=$GetBelongsToFromHD LIMIT 1", $conn) or die($k_b);
				list($temp_lok)=mysql_fetch_array($result_k);				
				$result_k = mysql_query("SELECT zgl_komorka FROM $dbname_hd.hd_zgloszenie WHERE ((zgl_widoczne=1) and (belongs_to=$GetBelongsToFromHD) and (zgl_id=$_REQUEST[hdzglid])) LIMIT 1", $conn) or die($k_b);
				list($temp_where)=mysql_fetch_array($result_k);
				
				echo "<input tabindex=-1 type=hidden id=lokalizacjazrodlowa value='$temp_lok'>";
				echo "<input tabindex=-1 type=hidden id=lokalizacjadocelowa value='".toUpper($temp_where)."'>";

				echo "<input class=buttons type=button onClick=\"document.getElementById('trasa').value=document.getElementById('lokalizacjazrodlowa').value.toUpperCase()+' - '+document.getElementById('lokalizacjadocelowa').value+' - '+document.getElementById('lokalizacjazrodlowa').value.toUpperCase(); document.getElementById('km').focus(); \" value='Generuj trasę ze zgłoszenia'/><br />";
				
				echo "<textarea class=wymagane id=trasa name=trasa cols=60 rows=3>$_REQUEST[trasa]</textarea>";
				//echo "</fieldset>";
			_td();
		echo "</tr>";
		echo "<tr id=DataWyjazdu style='display:";
		if ($_REQUEST[PozwolWpisacKm]=='on') { echo ""; } else { echo "none"; }
		echo "'>";		
			td(";rt;<b>Data wyjazdu</b>");
			td_(";;;");
				$dddd = Date('Y-m-d');
				if ($_REQUEST[hd_wyjazd_data]!='') $dddd=$_REQUEST[hd_wyjazd_data];
				$r4 = mysql_query("SELECT zgl_data FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id='$_GET[id]') LIMIT 1", $conn_hd) or die($k_b);
				list($data_utworzenia_zgloszenia)=mysql_fetch_array($r4);
				$za_pozno = 1;
				echo "<select class=wymagane name=hd_wyjazd_data id=hd_wyjazd_data>";
				echo "<option value='$dddd'"; if ($data_utworzenia_zgloszenia==$dddd) { $za_pozno=0; echo " SELECTED "; } echo ">$dddd</option>\n";

				if (date("w",strtotime($dddd))!=1) {				
					for ($cd=1; $cd<($idw_dla_zbh+1); $cd++) {
						echo "<option value='".SubstractDays($cd,$dddd)."'"; 
						if ($data_utworzenia_zgloszenia==SubstractDays($cd,$dddd)) { $za_pozno=0; echo " SELECTED "; } 
						echo ">".SubstractDays($cd,$dddd)."&nbsp;</option>\n";
					}
				}
				
			//	echo "<option value='".SubstractWorkingDays(1,$dddd)."'"; if ($data_utworzenia_zgloszenia==SubstractWorkingDays(1,$dddd)) { $za_pozno=0; echo " SELECTED "; } echo ">".SubstractWorkingDays(1,$dddd)."&nbsp;</option>\n";
			//	echo "<option value='".SubstractWorkingDays(2,$dddd)."'"; if ($data_utworzenia_zgloszenia==SubstractWorkingDays(2,$dddd)) { $za_pozno=0; echo " SELECTED "; } echo ">".SubstractWorkingDays(2,$dddd)."&nbsp;</option>\n";
				
	// sprawdź dostępy czasowe dla tego pracownika
			$sql_dc = "SELECT dc_dostep_dla_daty,dc_dostep_active_to FROM $dbname_hd.hd_dostep_czasowy WHERE ((dc_dostep_dla_osoby='$currentuser') and (dc_dostep_active=1) and (belongs_to=$es_filia)) ORDER BY dc_dostep_dla_daty DESC";
			$result_dc = mysql_query($sql_dc, $conn_hd) or die($k_b);
			$totalrows_dc = mysql_num_rows($result_dc);
			
			if ($totalrows_dc>0) {
				while ($newArray_dc = mysql_fetch_array($result_dc)) {
					$temp_dc_data	= $newArray_dc['dc_dostep_dla_daty'];
					$temp_dc_dostep_do	= $newArray_dc['dc_dostep_active_to'];
					echo "<option value='$temp_dc_data'"; if ($data_utworzenia_zgloszenia=='$temp_dc_data') { $za_pozno=0; echo "SELECTED"; } echo ">$temp_dc_data (dostępna do $temp_dc_dostep_do)</option>\n";
				}
			}
	// koniec sprawdzania dostępów czasowych dla pracownika
	
				echo "</select>\n";
				if ($za_pozno==0) echo "&nbsp;( Domyślnie data utworzenia zgłoszenia )";
				if ($za_pozno==1) echo "&nbsp;<font color=red>Przekroczono czas wymagany na obsługę zgłoszenia (2 dni robocze)<br />Skontaktuj się z administratorem systemu</font>";
			_td();
		echo "</tr>";
		echo "<tr id=WpiszWyjazdKm style='display:";
		if ($_REQUEST[PozwolWpisacKm]=='on') { echo ""; } else { echo "none"; }
		echo "'>";
			td(";rt;<b>Przejechane km</b>");
			td_(";;;");
				echo "<input class=wymagane id=km name=km value='$_REQUEST[km]' style=text-align:right type=text size=3 maxlength=3 onKeyPress=\"return filterInput(1, event, false); \"> km<br />";
			_td();
		echo "</tr>";		
		tbl_empty_row();

	endtable();
}

echo "<input type=hidden name=hdzglid value='$_REQUEST[hdzglid]'>";
$dddd = Date('Y-m-d');
$tttt = Date('H:i:s');
echo "<input type=hidden name=zs_data value='$dddd'>";
echo "<input type=hidden name=zs_time value='$tttt'>";

echo "<input type=hidden name=uid value='$temp_id'>";
echo "<input type=hidden name=szid value=$temp_szid>";
echo "<input type=hidden name=tup value='$_POST[tup]'>";

echo "<input type=hidden name=hd_zgl_nr value='$_REQUEST[hd_zgl_nr]'>";
echo "<input type=hidden name=cs value='$_REQUEST[cs]'>";
echo "<input type=hidden name=from value='$_REQUEST[from]'>";

startbuttonsarea("right");
addbuttons("zapisz","anuluj");
endbuttonsarea();	
_form();
?>
<script language="JavaScript">
var cal1 = new calendar1(document.forms['edu'].elements['dzstatusu']);
	cal1.year_scroll = true;
	cal1.time_comp = false;
</script>
</body>
</html>