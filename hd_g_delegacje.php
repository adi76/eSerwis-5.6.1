<?php 
include_once('cfg_helpdesk.php');
include_once('header.php');

echo "<body OnLoad=\""; 
if ($submit) { } 
else { 
	if ($_GET[poz]==1) echo "document.getElementById('Osoba').focus();";
	if ($_GET[poz]==2) echo "document.getElementById('Okres').focus();";
	if ($_GET[poz]==3) echo "document.getElementById('Pojazd').focus();";
	}
echo " CheckForm_hd_g_delegacje(); \" />";
//echo "SELECT * FROM $dbname_hd.helpdesk.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_poledodatkowe2='$_GET[unique]')";

if ($submit) {

	?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
	
	$result1 = mysql_query("SELECT hd_pojazd_marka,hd_pojazd_nr_rejestracyjny, hd_pojazd_pojemnosc FROM $dbname_hd.hd_pojazdy WHERE (hd_pojazd_id='$_POST[Pojazd]')", $conn) or die($k_b);
	list($pmarka,$pnr,$ppoj)=mysql_fetch_array($result1);	
	
// wyciągnij adres zamiejszkania z bazy
	$result1 = mysql_query("SELECT user_id, user_ulica,user_kod_pocztowy,user_miejscowosc FROM $dbname.serwis_uzytkownicy WHERE (CONCAT(user_first_name,' ',user_last_name)='$_POST[d_osoba]')", $conn) or die($k_b);
	list($temp_user_id, $temp_ulica,$temp_kod, $temp_miejscowosc)=mysql_fetch_array($result1);	


	echo "<span id=pre style='display:none;'>";
	pageheader("Wyjazdy za okres <b>$_POST[d_okres]</b> dla osoby <b>$_POST[d_osoba]</b>",1,0);
	nowalinia();
	if (($temp_ulica!='') && ($temp_kod!='') && ($temp_miejscowosc!='')) {
		echo "&nbsp;&nbsp;&nbsp;&nbsp;Adres zamieszkania: <b>$temp_ulica, $temp_kod $temp_miejscowosc</b><br />";
	} else { 
		echo "<font color=red><b>&nbsp;&nbsp;&nbsp;&nbsp;Nie wypełniono danych adresowych - eksport do XLS niemożliwy</b></font>&nbsp;";
		addownlinkbutton("' Uzupełnij dane '","button","button","newWindow_r(600,600,'e_uzytkownika.php?select_id=$temp_user_id&all=1')");
		nowalinia();
	}
	
//	echo "<fieldset><legend><b>&nbsp;Informacje o pojeździe&nbsp;</b></legend>";
	nowalinia();
	if ($pmarka!='') echo "&nbsp;&nbsp;&nbsp;&nbsp;Marka pojazdu: <b>$pmarka</b><br />";
	echo "&nbsp;&nbsp;&nbsp;&nbsp;Numer rejestracyjny: "; if ($pnr!='') { echo "<b>$pnr</b><br />"; } else echo "-";
	echo "&nbsp;&nbsp;&nbsp;&nbsp;Pojemność silnika: "; if ($ppoj!='') { echo "<b>$ppoj cm</b><img src=img/pow_3.gif border=0 /><br />"; } else echo "-";
	
	nowalinia();
//	echo "</fieldset>";
	nowalinia();
	echo "</span>";
	$sql = "SELECT wyjazd_zgl_szcz_id,wyjazd_trasa,wyjazd_km,wyjazd_data,wyjazd_rodzaj_pojazdu FROM $dbname_hd.hd_zgloszenie_wyjazd WHERE (wyjazd_osoba='$_POST[d_osoba]') and (wyjazd_widoczny=1) and (wyjazd_data LIKE '%$_POST[d_okres]%') ";
	//echo "$sql";
	
	if (($_REQUEST[pokaz_zerowe]=='on') && ($_REQUEST[pomijaj_wyjazdy_sluzbowe]!='on')) {
		$sql .= " and (((wyjazd_rodzaj_pojazdu='P') and (wyjazd_km<>'0')) or (wyjazd_rodzaj_pojazdu='S'))";
	}

	if (($_REQUEST[pokaz_zerowe]=='on') && ($_REQUEST[pomijaj_wyjazdy_sluzbowe]=='on')) {	
		$sql .= " and (wyjazd_rodzaj_pojazdu='P') and (wyjazd_km<>'0')";
	}

	if (($_REQUEST[pokaz_zerowe]!='on') && ($_REQUEST[pomijaj_wyjazdy_sluzbowe]=='on')) {	
		$sql .= " and (wyjazd_rodzaj_pojazdu='P')";
	}
	
	$sql .= " ORDER BY wyjazd_data ASC";
	
	//echo $sql;
	
	$result = mysql_query($sql, $conn_hd) or die($k_b);
	$count_rows = mysql_num_rows($result);
	
if ($count_rows>0) {
	?>
	<script>
	document.getElementById('pre').style.display='';
	</script>
	<?php 
	$km_razem = 0;
	$zl_razem = 0;
	
	$poz = 1;
	$i = 0;
	
	// ustalenie stawki wg wybranego pojazdu
		$result1 = mysql_query("SELECT hd_pojazd_kategoria FROM $dbname_hd.hd_pojazdy WHERE (hd_pojazd_id='$_POST[Pojazd]')", $conn_hd) or die($k_b);
		list($poj_kat)=mysql_fetch_array($result1);
		
		$dddd = Date('Y-m-d');
		
		switch ($poj_kat) {
			case "1" : $result1 = mysql_query("SELECT hd_stawka_motorower FROM helpdesk.hd_stawki_za_km WHERE hd_stawka_od<'".$dddd."' and hd_stawka_active=1", $conn) or die($k_b); break;
			case "2" : $result1 = mysql_query("SELECT hd_stawka_motocykl FROM helpdesk.hd_stawki_za_km WHERE hd_stawka_od<'".$dddd."' and hd_stawka_active=1", $conn) or die($k_b); break;
			case "3" : $result1 = mysql_query("SELECT hd_stawka_samochod_do_900 FROM helpdesk.hd_stawki_za_km WHERE hd_stawka_od<'".$dddd."' and hd_stawka_active=1", $conn) or die($k_b); break;
			case "4" : $result1 = mysql_query("SELECT hd_stawka_samochod_od_900 FROM helpdesk.hd_stawki_za_km WHERE hd_stawka_od<'".$dddd."' and hd_stawka_active=1", $conn) or die($k_b); break;
		}
		
		list($stawka)=mysql_fetch_array($result1);	
	//$stawka = 0.8358;
	//echo ">>>$stawka<<<";
	starttable();
	th("20;c;LP|;c;Data|;l;Trasa|;c;Nr<br />zgłoszenia|;;Cel wyjazdu|;r;Km|;c;Stawka|;r;Kwota|;c;Opcje",$es_prawa);
	
	while (list($w_zgl_szcz_id,$w_trasa,$w_km,$w_data,$prodzaj)=mysql_fetch_array($result)) {
		
		tbl_tr_highlight($i);
		//echo "SELECT zgl_szcz_zgl_id,zgl_nr_kroku FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_unikalny_numer='$w_zgl_szcz_id') and (zgl_szcz_osoba_wykonujaca_krok='$_REQUEST[d_osoba]') and (belongs_to=$es_filia)";
		$r3 = mysql_query("SELECT zgl_szcz_zgl_id,zgl_szcz_nr_kroku, zgl_szcz_id FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_unikalny_numer='$w_zgl_szcz_id') and (zgl_szcz_osoba_wykonujaca_krok='$_REQUEST[d_osoba]') and (belongs_to=$es_filia)", $conn_hd) or die($k_b);
		list($zgloszenie_id,$zgloszenie_szcz_nr_kroku,$zgl_szcz_id)=mysql_fetch_array($r3);
		
		//echo "SELECT zgl_nr, zgl_temat, zgl_tresc FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id='$zgloszenie_id')";
		$r3 = mysql_query("SELECT zgl_nr, zgl_temat, zgl_tresc FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id='$zgloszenie_id')", $conn_hd) or die($k_b);
		list($zgloszenie_numer,$zgloszenie_temat,$zgloszenie_tresc)=mysql_fetch_array($r3);
		
		if ($prodzaj=='S') $new_kolor = 'grey';
		if ($prodzaj=='P') $new_kolor = 'black';
		if (($prodzaj=='P') && ($w_km==0)) $new_kolor = 'red';
		
		echo "<td style='height:20px' class=center>";
		if ($w_km==0) echo "<font color=$new_kolor>";
		echo "$poz";
		if ($w_km==0) echo "</font>";
		echo "</td>";
		
		echo "<td class='center'>";
		if ($w_km==0) echo "<font color=$new_kolor>";
		echo "$w_data";
		if ($w_km==0) echo "</font>";
		echo "</td>";
		
		echo "<td>";
		if ($w_km==0) echo "<font color=$new_kolor>";
		echo nl2br(wordwrap($w_trasa, 50, "<br />"));
		if ($w_km==0) echo "</font>";
		echo "</td>";

		echo "<td class=center>";
		if ($w_km==0) echo "<font color=$new_kolor>";
		echo "<a href=# title='Pokaż w nowym oknie zgłoszenie nr $zgloszenie_numer' class=normalfont onclick=\"newWindow_r(800,600,'hd_o_zgloszenia.php?id=".$zgloszenie_numer."&nr=".$zgloszenie_numer."&action=obsluga'); return false;\">";
		echo "<b>$zgloszenie_numer</b>";
		echo "</a>";
		if ($w_km==0) echo "</font>";
		echo "</td>";
		
		echo "<td class=left>";
		if ($w_km==0) echo "<font color=$new_kolor>";
		echo "$zgloszenie_temat";
		if ($w_km==0) echo "</font>";
		echo "</td>";
		//echo "<td class=left>".nl2br(wordwrap($zgloszenie_tresc, 100, "<br />"))."</td>";
				
		echo "<td class=right>";
		if ($w_km==0) echo "<font color=$new_kolor>";
		echo "$w_km km";
		if ($w_km==0) echo "</font>";
		echo "</td>";

		echo "<td class=center>";
		if ($w_km==0) echo "<font color=$new_kolor>";
		echo "$stawka zł";
		if ($w_km==0) echo "</font>";
		echo "</td>";
		
		echo "<td class=right>";
		if ($w_km==0) echo "<font color=$new_kolor>";
		//printf("%.04f", ($stawka*$w_km));
		FormatFloat(($stawka*$w_km),4);		
		echo " zł";
		if ($w_km==0) echo "</font>";		
		echo "</td>";
		
		echo "<td class=center>";
		
			$dddd = Date('Y-m-d');
			$ddddtt = Date('Y-m-d H:i:s');
			$mozna_edytowac = 0;
			
			if ($dddd==$w_data) $mozna_edytowac = 1;
			
			if ($mozna_edytowac==0) {
				for ($cd=1; $cd<($idw_dla_zbh+1); $cd++) {
					if ($w_data == "".SubstractDays($cd,$dddd)."") $mozna_edytowac = 1;
				}
			}
			
			if ($mozna_edytowac==0) {
				$aktualna_data = Date('Y-m-d H:i:s');
				$sql_dc = "SELECT dc_dostep_dla_daty,dc_dostep_active_to FROM $dbname_hd.hd_dostep_czasowy WHERE ((dc_dostep_dla_osoby='$currentuser') and (dc_dostep_active=1) and (belongs_to=$es_filia) and (dc_dostep_active_to>='$aktualna_data') and (dc_dostep_dla_daty='$w_data')) ORDER BY dc_dostep_dla_daty DESC";
				
				$result_dc = mysql_query($sql_dc, $conn_hd) or die($k_b);
				$totalrows_dc = mysql_num_rows($result_dc);
				
				if ($totalrows_dc>0) {
					while ($newArray_dc = mysql_fetch_array($result_dc)) {
						$temp_dc_data	= $newArray_dc['dc_dostep_dla_daty'];
						$temp_dc_dostep_do	= $newArray_dc['dc_dostep_active_to'];
						if (($temp_dc_dostep_do>=$ddddtt) && ($w_data==$temp_dc_data)) $mozna_edytowac = 1;
						
					}
				}
				
			}
			
			if ($mozna_edytowac==1) {
				if ($prodzaj=='P') {
					echo "<a href=# onclick=\"newWindow_r(800,300,'hd_e_zgloszenie_krok.php?element=trasa&id=$zgl_szcz_id&nr=$zgloszenie_szcz_nr_kroku&zglnr=$zgloszenie_numer&zglid=$zgloszenie_numer'); return false;\" title=' Edycja trasy i km '><input class=imgoption type=image src=img/car.gif></a>";
				} else {
					echo "<input title='Wyjazd samochodem służbowym. Edycja niemożliwa' class=imgoption type=image src=img/car_S.gif>";
				}
				
				echo "<a title='Usuń wyjazd powiązany z krokiem nr $zgloszenie_szcz_nr_kroku'><input type=image class=imgoption src=img//wyjazd_usun.gif onclick=\"newWindow(600,100,'hd_u_wyjazd.php?id=$temp_id&nr_kroku=$zgloszenie_szcz_nr_kroku&zgl_nr=$zgloszenie_numer&unique=$w_zgl_szcz_id')\"></a>";
				
			} else {
				echo "<a href=# class=normalfont title='Aby móc edytować ten wyjazd otwórz dzień $w_data do edycji'>?</a>";
			}
		echo "</td>";

		echo "</tr>";
		$poz++;
		$i++;
		$kwota_razem+=($stawka*$w_km);
		$km_razem+=$w_km;
	}
	
	echo "<tr>";
	echo "<td colspan=5 class=right style='height:20px'>";
	echo "<b>Razem:</b>&nbsp;";
	echo "</td>";
	echo "<td class=right nowrap>";
	echo "<b>$km_razem km</b>";
	echo "</td>";
	echo "<td class=center>-</td><td class=right nowrap><b>";
	FormatFloat($kwota_razem,2);
	echo " zł</b></td>";
	echo "</tr>";
	
	endtable();
	oddziel();
	startbuttonsarea("right");
	echo "<input style=float:left class=buttons type=button onClick=history.go(-1); value='Zmień kryteria'>";
	
	echo "<form action=do_xls_htmlexcel_hd_g_delegacje.php METHOD=POST target=_blank style='display:inline'>";
	if (($temp_ulica!='') && ($temp_kod!='') && ($temp_miejscowosc!='')) {
		echo "<input class=buttons type=submit value='Export do XLS'>";
	}
	echo "<input type=hidden name=g_okres value='$_POST[d_okres]'>";
	echo "<input type=hidden name=g_osoba value='$_POST[d_osoba]'>";
	echo "<input type=hidden name=g_pojazd value='$_POST[Pojazd]'>";

	echo "<input type=hidden name=g_ulica value='$temp_ulica'>";
	echo "<input type=hidden name=g_kod value='$temp_kod'>";
	echo "<input type=hidden name=g_miejscowosc value='$temp_miejscowosc'>";

	echo "<input type=hidden name=g_nrrej value='$pnr'>";
	echo "<input type=hidden name=g_marka value='$pmarka'>";
	echo "<input type=hidden name=g_poj value='$ppoj'>";	
	echo "<input type=hidden name=g_km_razem value='$km_razem'>";
	echo "<input type=hidden name=g_kwota_razem value='$kwota_razem'>";

	echo "<input type=hidden name=pokaz_zerowe value='$_REQUEST[pokaz_zerowe]'>";	
	echo "<input type=hidden name=pomijaj_wyjazdy_sluzbowe value='$_REQUEST[pomijaj_wyjazdy_sluzbowe]'>";		
	
	addbuttons("zamknij");	
	echo "</form>";
	endbuttonsarea();
} else {
	errorheader("Brak wyjazdów zarejestrowanych za miesiąc <b>".$_POST[d_okres]."</b>");
	startbuttonsarea("right");
	echo "<input style=float:left class=buttons type=button onClick=history.go(-1); value='Zmień kryteria'>";
	addbuttons("zamknij");	
	
	endbuttonsarea();
}	
} else {

	$result1 = mysql_query("SELECT hd_pojazd_id,hd_pojazd_active FROM $dbname_hd.hd_pojazdy WHERE (belongs_to=$es_filia) and (hd_pojazd_user_id=$es_nr) and (hd_pojazd_active=1) and (hd_pojazd_do='0000-00-00')", $conn) or die($k_b);
	list($poj_id,$poj_active)=mysql_fetch_array($result1);
	if (($poj_id=='') && ($is_dyrektor==0)) { 
	?>
		<script>
			alert('Wybrana osoba nie ma zdefiniowanego pojazdu w bazie eSerwis. Generowanie raportu jest niemożliwe. Proszę uzupełnić dane w bazie i spróbować ponownie.');
			self.location.href='hd_z_pojazdy.php?userid=<?php echo $es_nr; ?>';
		</script>
	<?php
	}

	pageheader("Generowanie listy wyjazdów dla osoby w okresie",0);

	?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
	
	echo "<form name=delegacja action=$PHP_SELF method=POST >";
	
	starttable();
	
	tbl_empty_row();

	$accessLevels = array("9"); if (array_search($es_prawa, $accessLevels)>-1) { 

		echo "<tr>";
		echo "<td class=right width=150px>";
		echo "Wybierz osobę";
		echo "</td>";
		$phpfile = $PHP_SELF;
		
		echo "<td class=left>";
					echo "<span id=FKOsoba style='display:;'>";
					$result44 = mysql_query("SELECT user_first_name,user_last_name FROM $dbname.serwis_uzytkownicy WHERE (belongs_to=$es_filia) and (user_locked=0) ORDER BY user_last_name ASC", $conn_hd) or die($k_b);
					$count_rows = mysql_num_rows($result44);
					echo "<select class=wymagane id=Osoba name=d_osoba onChange=\"reload_osoba(this.form);\" >\n";
					//echo "<option value=''></option>";
					while (list($temp_imie,$temp_nazwisko) = mysql_fetch_array($result44)) {
						$imieinazwisko = $temp_imie . " " . $temp_nazwisko;
					//	echo "<option value=\"$phpfile?osoba=".urlencode($imieinazwisko)."&poz=1&idpoj=$_GET[idpoj]&okres=$_GET[okres]\" ";
						echo "<option value='$imieinazwisko' ";
						if ($_GET[osoba]==$imieinazwisko) echo " SELECTED";
						echo ">$imieinazwisko</option>\n"; 
					}
					echo "</select>\n";
				echo "<input type=hidden name=d_osoba value='$_GET[osoba]'>";
				
				echo "</span>";
		echo "</td>";	
		echo "</tr>";
	
	} else {
		echo "<input type=hidden name=d_osoba value='$currentuser'>";
	}
	
	echo "<tr>";
	echo "<td class=right width=150px>";
	echo "Wybierz miesiąc";
	echo "</td>";
	
	echo "<td>";

	$result44 = mysql_query("SELECT DISTINCT SUBSTRING(wyjazd_data,1,7) FROM $dbname_hd.hd_zgloszenie_wyjazd WHERE (belongs_to=$es_filia) and (wyjazd_osoba='$_GET[osoba]') and (wyjazd_widoczny=1) ORDER BY wyjazd_data DESC", $conn_hd) or die($k_b);
	$count_rows = mysql_num_rows($result44);

	if ($count_rows>0) {
		echo "<select class=wymagane id=Okres name=d_okres onChange=\"CheckForm_hd_g_delegacje(); reload_osoba(this.form);\" onKeyUp=\"if ((event.keyCode==13) document.getElementById('Pojazd').focus(); \"/>\n";
		//echo "<option></option>";
		while (list($temp_okres) = mysql_fetch_array($result44)) {
			echo "<option "; 
			if ($_GET[okres]==$temp_okres) echo " SELECTED ";
		//	echo "value=\"$phpfile?osoba=".urlencode($_GET[osoba])."&poz=2&idpoj=$_GET[idpoj]&okres=$temp_okres\">$temp_okres</option>\n"; 
			echo "value=$temp_okres>$temp_okres</option>\n"; 
		}
		echo "</select>\n";
	} else {
		echo "<b><font color=red>Brak zarejestrowanych wyjazdów w bazie Helpdesk</font></b>";
	}
	
	echo "</td>";	
	echo "</tr>";	

	echo "<tr>";
	echo "<td class=right width=150px>";
	echo "Wybierz pojazd";
	echo "</td>";
	
	echo "<td>";
	//echo "SELECT user_id FROM $dbname.serwis_uzytkownicy WHERE (belongs_to=$es_filia) and (CONCAT(user_first_name,' ',user_last_name)='$_REQUEST[osoba]')";
	
	$result44 = mysql_query("SELECT user_id FROM $dbname.serwis_uzytkownicy WHERE (belongs_to=$es_filia) and (CONCAT(user_first_name,' ',user_last_name)='$_REQUEST[osoba]')", $conn_hd) or die($k_b);
	list($osoba_nr) = mysql_fetch_array($result44);
	//echo $osoba_nr;
	
	//echo "<br />SELECT hd_pojazd_id,hd_pojazd_marka, hd_pojazd_nr_rejestracyjny, hd_pojazd_pojemnosc FROM $dbname_hd.hd_pojazdy WHERE (belongs_to=$es_filia) and (hd_pojazd_user_id=$osoba_nr) and (hd_pojazd_active=1) ORDER BY hd_pojazd_id DESC";
	
	$result44 = mysql_query("SELECT hd_pojazd_id,hd_pojazd_marka, hd_pojazd_nr_rejestracyjny, hd_pojazd_pojemnosc FROM $dbname_hd.hd_pojazdy WHERE (belongs_to=$es_filia) and (hd_pojazd_user_id=$osoba_nr) and (hd_pojazd_active=1) ORDER BY hd_pojazd_id DESC", $conn_hd) or die($k_b);
	$count_rows2 = mysql_num_rows($result44);
	
	if ($count_rows2>0) {
	
		if ($count_rows>0) {
			echo "<select id=Pojazd name=Pojazd onChange=\"CheckForm_hd_g_delegacje(); \" onKeyUp=\"if ((event.keyCode==13) document.getElementById('submit').focus(); \"/>\n";
			//echo "<option></option>";
			while (list($temp_id,$temp_marka,$temp_nr,$temp_poj) = mysql_fetch_array($result44)) {
				echo "<option "; 
			//	if ($_GET[idpoj]==$temp_id) echo " SELECTED ";
			//	echo "value=\"$phpfile?osoba=".urlencode($_GET[osoba])."&poz=3&idpoj=$temp_id&okres=".$_GET[okres]."\">$temp_marka | $temp_nr | $temp_poj</option>\n"; 
				echo "value='$temp_id'>$temp_marka | $temp_nr | $temp_poj</option>\n"; 
				}
			echo "</select>\n";
			
			//echo "<input type=hidden name=g_id value='$temp_id'>";
		/*	echo "<input type=hidden name=g_marka value='$temp_marka'>";
			echo "<input type=hidden name=g_nr value='$temp_nr'>";
			echo "<input type=hidden name=g_poj value='$temp_poj'>";
		*/
	
			echo "<input class=border0 type=checkbox name=pokaz_zerowe id=pokaz_zerowe checked=checked><a tabindex=-1 class=normalfont href=# onClick=\"if (document.getElementById('pokaz_zerowe').checked) { document.getElementById('pokaz_zerowe').checked=false; } else { document.getElementById('pokaz_zerowe').checked=true; }\"><font color=red>&nbsp;Pomijaj wyjazdy samochodem prywatnym z zerową ilością km</font></a>";
			
			echo "&nbsp;&nbsp;&nbsp;&nbsp;<input class=border0 type=checkbox name=pomijaj_wyjazdy_sluzbowe id=pomijaj_wyjazdy_sluzbowe checked=checked><a tabindex=-1 class=normalfont href=# onClick=\"if (document.getElementById('pomijaj_wyjazdy_sluzbowe').checked) { document.getElementById('pomijaj_wyjazdy_sluzbowe').checked=false; } else { document.getElementById('pomijaj_wyjazdy_sluzbowe').checked=true; }\">&nbsp;Pomijaj wyjazdy służbowe</a>";
			
		} else echo "-";
		
	} else {
		echo "<b><font color=red>Użytkownik nie posiada wpisanych w bazie żadnych pojazdów</font></b>";	
		}

	echo "</td>";	
	echo "</tr>";
	
	tbl_empty_row();	
	
	endtable();
	startbuttonsarea("center");
	if ($count_rows>0) { echo "<input type=submit id=generuj_submit style='display:none' name=submit class=buttons value='Generuj' />"; }
	
	echo "<span style='float:right'>";
	addbuttons("zamknij");
	echo "</span>";

	endbuttonsarea();
	
	echo "</form>";	
}

?>
<script type="text/javascript" src="js/jquery/entertotab.js"></script>
<script type='text/javascript'>
  
EnterToTab.init(document.forms.delegacja, true);

</script>

<script>HideWaitingMessage();</script>

</body>
</html>