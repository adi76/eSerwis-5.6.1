<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body onLoad="document.forms[0].elements[0].focus();">
<?php 

if ($submit) { 
$dddd=Date("Y-m-d H:i:s");
$_POST=sanitize($_POST);

	$trasaw = $_REQUEST[trasa];
	$wdata = $_REQUEST[hd_wyjazd_data];
	
	if ($_REQUEST[hd_wyjazd_rp]=='S') {
		$trasaw = 'wyjazd samochodem służbowym';
	}
	
	$unique_nr = $_REQUEST[unique];

	$d_cz_wyjazdu = 0;	// czas trwania wyjazdu
	if ($_REQUEST[czas_przejazdu_h]!='') { $h_na_m = (int) $_REQUEST[czas_przejazdu_h]*60; }
	if ($_REQUEST[czas_przejazdu_m]!='') { $m_na_m = (int) $_REQUEST[czas_przejazdu_m]; }		
	$d_cz_wyjazdu=$h_na_m+$m_na_m;
	
	if ($_REQUEST[hd_wyjazd_rp]=='P') {
		$d_km = $_REQUEST[km];
		
		$sql2 = "INSERT INTO $dbname_hd.hd_zgloszenie_wyjazd VALUES ('','$unique_nr','$wdata','$trasaw',$d_km,'$_REQUEST[hd_wyjazd_osoba]','$_REQUEST[hd_wyjazd_rp]',1,$d_cz_wyjazdu, $es_filia)";	
		$result2 = mysql_query($sql2, $conn_hd) or die($k_b);

		$sql_a = "UPDATE $dbname_hd.hd_zgloszenie_szcz SET zgl_szcz_czas_trwania_wyjadu = $d_cz_wyjazdu, zgl_szcz_byl_wyjazd = '1', zgl_szcz_il_km='$d_km' WHERE (zgl_szcz_unikalny_numer='$unique_nr') LIMIT 1";	
		$wynik_sql_a = mysql_query($sql_a, $conn_hd);

		$r3 = mysql_query("SELECT sum(zgl_szcz_il_km) FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$_REQUEST[zgl_nr]') and (zgl_szcz_widoczne=1)", $conn_hd) or die($k_b);
		list($razem_km)=mysql_fetch_array($r3);

		$sql_a = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_razem_km=$razem_km WHERE ((zgl_widoczne=1) and (zgl_id='$_REQUEST[zgl_nr]')) LIMIT 1";
	
	} else {
		$d_km = 0;
		$sql2 = "INSERT INTO $dbname_hd.hd_zgloszenie_wyjazd VALUES ('','$unique_nr','$wdata','$trasaw',$d_km,'$_REQUEST[hd_wyjazd_osoba]','$_REQUEST[hd_wyjazd_rp]',1,$d_cz_wyjazdu,$es_filia)";	
		$result2 = mysql_query($sql2, $conn_hd) or die($k_b);
		$sql_a = "UPDATE $dbname_hd.hd_zgloszenie_szcz SET zgl_szcz_czas_trwania_wyjadu = $d_cz_wyjazdu, zgl_szcz_byl_wyjazd = '1', zgl_szcz_il_km='$d_km' WHERE (zgl_szcz_unikalny_numer='$unique_nr') LIMIT 1";
		//$wynik_sql_a = mysql_query($sql_a, $conn_hd);
	}
	
	if (mysql_query($sql_a, $conn)) { 
		?>
		<script>
		self.close();
		if (opener) opener.location.reload(true);
		</script><?php
	} else {
	  ?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
	}
} else { 

function toUpper($string) {
	$string = strtr($string,'qwertyuiopasdfghjklzxcvbnm','QWERTYUIOPASDFGHJKLZXCVBNM');
	return (strtr($string, 'ęóąśłżźćń','ĘÓĄŚŁŻŹĆŃ' )); 
}; 

pageheader("Dodawanie wyjazdu do kroku nr $_REQUEST[nr_kroku] w zgłoszeniu nr $_REQUEST[zgl_nr]");
starttable();
echo "<form name=add action=$PHP_SELF method=POST onSubmit=\"return pytanie_zapisz('Zapisać zmiany ?');\">";
tbl_empty_row();

		echo "<tr id=RodzajPojazdu style='display:'>";
		td("140;r;<b>Rodzaj pojazdu</b>");
			td_(";;;");
				echo "<select name=hd_wyjazd_rp id=hd_wyjazd_rp onChange=\"if (this.value=='P') { $('#WpiszWyjazdTrasa').show(); $('#DataWyjazdu').show(); $('#WpiszWyjazdKm').show();} else { $('#WpiszWyjazdTrasa').hide(); $('#DataWyjazdu').hide(); $('#WpiszWyjazdKm').hide();} \">";
				echo "<option value='P'>prywatny</option>";
				echo "<option value='S' SELECTED>służbowy</option>";
				echo "</select>";
			_td();
		echo "</tr>";
		tbl_empty_row();
		
		echo "<tr id=WpiszWyjazdTrasa style='display:none'>";
			td("150;rt;<b>Trasa wyjazdowa");
			td_(";;;");
				$result_k = mysql_query("SELECT filia_lokalizacja FROM $dbname.serwis_filie WHERE filia_id=$es_filia LIMIT 1", $conn) or die($k_b);
				list($temp_lok)=mysql_fetch_array($result_k);
				
				$result_k = mysql_query("SELECT zgl_komorka FROM $dbname_hd.hd_zgloszenie WHERE ((zgl_widoczne=1) and (belongs_to=$es_filia) and (zgl_id=$_GET[id])) LIMIT 1", $conn) or die($k_b);
				list($temp_where)=mysql_fetch_array($result_k);
				
				echo "<input tabindex=-1 type=hidden id=lokalizacjazrodlowa value='$temp_lok'>";
				echo "<input tabindex=-1 type=hidden id=lokalizacjadocelowa value='".toUpper($_REQUEST[komorka])."'>";
				//echo "<br />";
				echo "<input class=buttons type=button onClick=\"document.getElementById('trasa').value=document.getElementById('lokalizacjazrodlowa').value.toUpperCase()+' - '+document.getElementById('lokalizacjadocelowa').value+' - '+document.getElementById('lokalizacjazrodlowa').value.toUpperCase(); document.getElementById('km').focus(); \" value='Generuj trasę ze zgłoszenia'/><br />";
				
				echo "<textarea class=wymagane id=trasa name=trasa cols=50 rows=3></textarea>";
				//echo "</fieldset>";
			_td();
		echo "</tr>";
		
		echo "<tr id=DataWyjazdu style='display:'>";
			td("150;rt;<b>Data wyjazdu</b>");
			td_(";;;");
				//echo "<input type=hidden name=hd_wyjazd_data value='$_REQUEST[datakroku]'>";
				//echo "<b>$_REQUEST[datakroku]</b>";
				$dddd = Date('Y-m-d');
				$r4 = mysql_query("SELECT zgl_data FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id='$_GET[id]') LIMIT 1", $conn_hd) or die($k_b);
				list($data_utworzenia_zgloszenia)=mysql_fetch_array($r4);
				$za_pozno = 1;
				echo "<select name=hd_wyjazd_data id=hd_wyjazd_data>";
				echo "<option value='$dddd'"; if ($data_utworzenia_zgloszenia==$dddd) { $za_pozno=0; echo " SELECTED "; } echo ">$dddd</option>\n";
			
				for ($cd=1; $cd<($idw_dla_zbh+1); $cd++) {
					if ($last_step_date<=SubstractDays($cd,$dddd)) {
						echo "<option value='".SubstractDays($cd,$dddd)."'"; 
						//if ($data_utworzenia_zgloszenia==SubstractDays($cd,$dddd)) { $za_pozno=0; echo " SELECTED "; } 
						echo ">".SubstractDays($cd,$dddd)."&nbsp;";
						if ($idw_dla_zbh_testowa) echo "[dla testów]";
						echo "</option>\n";
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
					echo "<option value='$temp_dc_data'"; 
					//if ($data_utworzenia_zgloszenia=='$temp_dc_data') { $za_pozno=0; echo "SELECTED"; } 
					echo ">$temp_dc_data (dostępna do $temp_dc_dostep_do)</option>\n";
				}
			}
	// koniec sprawdzania dostępów czasowych dla pracownika
	
				echo "</select>\n";
				
			_td();
		echo "</tr>";
		
		echo "<tr id=WpiszWyjazdKm style='display:none'>";
			td("150;rt;<b>Przejechane km</b>");
			td_(";;;");
				echo "<input class=wymagane id=km name=km style=text-align:right type=text size=3 maxlength=3 onKeyPress=\"return filterInput(1, event, false); \"> km";
			_td();
		echo "</tr>";

		echo "<tr id=CzasTrwaniaWyjazdu style='display:;'>";
		td("140;rt;<b>Czas trwania wyjazdu</b>");
			td_(";;;");
				echo "<input style=text-align:right type=text id=czas_przejazdu_h name=czas_przejazdu_h value='0' maxlength=2 size=2 onKeyPress=\"return filterInput(1, event, false); \" onKeyUp=\"if ((event.keyCode==13) && (this.value!='')) document.getElementById('czas_przejazdu_m').focus(); \" onFocus=\"this.select(); return false;\" onBlur=\"if (this.value=='') this.value=0; if (this.value>8) if (confirm('Wpisano więcej niż 8 godzin na przejazd. Potwierdzasz poprawność ?')) { return true; } else { this.value='0'; return false; }\" /> godzin";
				echo "&nbsp;";
				echo "<input style=text-align:right type=text id=czas_przejazdu_m name=czas_przejazdu_m value='0' maxlength=2 size=2 onKeyPress=\"return filterInput(1, event, false); \" onKeyUp=\"if ((event.keyCode==13) && (this.value!='')) { document.getElementById('submit').focus(); }\" onFocus=\"this.select(); return false;\" onBlur=\"if (this.value=='') this.value=0; \" /> minut";
				echo "<div id=StatusChanged_prepare2>";
				echo "</div>";
				echo "<br />";
			_td();
		echo "</tr>";
	
		if (($kierownik_nr==$es_nr) && ($funkcja_kontroli_pracownikow==1)) {
			echo "<tr id=WyjazdDlaOsoby style='display:'>";
				td("150;rt;<b>Wyjazd dla osoby</b>");
				td_(";;;");
				//if ($_REQUEST[cu]!=$_REQUEST[osk]) {
					echo "<input type=hidden name=hd_wyjazd_osoba id=hd_wyjazd_osoba value='$_REQUEST[osk]'>";
					echo "<b><font color=red>$_REQUEST[osk]</font></b>";
				//}
				_td();
			_tr();
		} else echo "<input type=hidden name=hd_wyjazd_osoba id=hd_wyjazd_osoba value='$_REQUEST[osk]'>";
		
		
tbl_empty_row();
endtable();

echo "<input type=hidden name=id value='$_REQUEST[id]' />";
echo "<input type=hidden name=nr_kroku value='$_REQUEST[nr_kroku]' />";
echo "<input type=hidden name=zgl_nr value='$_REQUEST[zgl_nr]' />";
echo "<input type=hidden name=unique value='$_REQUEST[unique]' />";
echo "<input type=hidden name=datakroku value='$_REQUEST[datakroku]' />";

startbuttonsarea("right");
addbuttons("zapisz","anuluj");
endbuttonsarea();
_form();
}
?>

</body>
</html>