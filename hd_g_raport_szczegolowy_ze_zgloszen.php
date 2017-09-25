<?php 
include_once('cfg_helpdesk.php');
include_once('header.php');

echo "<body>";

if ($submit) {

	//echo $_REQUEST[r_rok]."<br />";
	//echo "Okres: ".$_REQUEST[rr]."<br />";
	if ($_REQUEST[rr]=='T') $okres = $_REQUEST[r_T_zakres];
	if ($_REQUEST[rr]=='M') $okres = $_REQUEST[r_M_zakres];
	if ($_REQUEST[rr]=='K') $okres = $_REQUEST[r_K_zakres];
	//echo "		".$okres;

	$okres = str_replace('@',' - ',$okres);
	
	if ($_REQUEST[rr]=='D') $okres = $_REQUEST[d_od]." - ".$_REQUEST[d_do];
	
pageheader("Raport szczegółowy ze zgłoszeń",1,0);
infoheader("Okres raportowania: <b>".$okres."</b>");

/*
echo "<table cellspacing=1 align=center>";
echo "<tr>";	
	if ($_POST[pole_1]=='on') echo "<th>LP</th>";
	if ($_POST[pole_2]=='on') echo "<th>Nr<br />zgłoszenia</th>";
	if ($_POST[pole_3]=='on') echo "<th>Nr zgł.<br />poczty</th>";
	if ($_POST[pole_4]=='on') echo "<th>Data zgłoszenia</th>";
	if ($_POST[pole_5]=='on') echo "<th>Godzina zgłoszenia</th>";
	if ($_POST[pole_6]=='on') echo "<th>Komórka zgłaszająca</th>";
	if ($_POST[pole_7]=='on') echo "<th>Osoba zgłaszająca</th>";
	if ($_POST[pole_8]=='on') echo "<th>Telefon do<br />osoby zgłaszającej</th>";
	if ($_POST[pole_9]=='on') echo "<th>Temat zgłoszenia</th>";
	if ($_POST[pole_10]=='on') echo "<th>Treść zgłoszenia</th>";
	if ($_POST[pole_11]=='on') echo "<th>Kategoria</th>";
	if ($_POST[pole_12]=='on') echo "<th>Podkategoria</th>";
	if ($_POST[pole_13]=='on') echo "<th>Priorytet</th>";
	if ($_POST[pole_14]=='on') echo "<th>Status</th>";
	if ($_POST[pole_15]=='on') echo "<th>Osoba przypisana</th>";
	if ($_POST[pole_16]=='on') echo "<th>Umowna data rozp.</th>";
	if ($_POST[pole_17]=='on') echo "<th>Umowna data zakoń.</th>";
	if ($_POST[pole_18]=='on') echo "<th>Osoba rejestrująca</th>";
	if ($_POST[pole_19]=='on') echo "<th>Zgłoszenie zasadne</th>";
	if ($_POST[pole_20]=='on') echo "<th>Osoba potwierdzająca<br />zamknięcie zgłoszenia</th>";
	if ($_POST[pole_21]=='on') echo "<th>Data zamknięcia<br />zgłoszenia</th>";
	if ($_POST[pole_22]=='on') echo "<th>Filia</th>";
echo "</tr>";
*/

?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();

if ($_REQUEST[rr]!='D') {
	$zakres_dat = explode(" - ",$okres);
	$data_od = $zakres_dat[0];
	$data_do = $zakres_dat[1];
} else {
	$data_od = $_REQUEST[d_od];
	$data_do = $_REQUEST[d_do];
}

//echo $data_od." -> ".$data_do;
/*
$i = 1;

$sql4 = "SELECT zgl_id,zgl_nr,zgl_poczta_nr,zgl_data,zgl_godzina,zgl_komorka,zgl_osoba,zgl_telefon,zgl_temat,zgl_tresc,zgl_osoba_przypisana,zgl_data_rozpoczecia,zgl_data_zakonczenia,zgl_osoba_rejestrujaca,zgl_zasadne,zgl_osoba_potwierdzajaca_zamkniecie_zgloszenia,hd_kategoria_opis,hd_podkategoria_opis,hd_priorytet_opis,hd_status_opis,location_name,zgl_status FROM $dbname_hd.hd_zgloszenie, $dbname_hd.hd_kategoria, $dbname_hd.hd_podkategoria, $dbname_hd.hd_priorytet, $dbname_hd.hd_status, zgloszenia.locations WHERE (zgl_data BETWEEN '$data_od' and '$data_do') and (zgl_kategoria=hd_kategoria_nr) and (zgl_podkategoria=hd_podkategoria_nr) and (zgl_priorytet=hd_priorytet_nr) and (zgl_status=hd_status_nr) and (belongs_to=location_id) and (zgl_widoczne=1) and (hd_kategoria_nr<>5) ORDER BY zgl_id ASC";

$result4 = mysql_query($sql4, $conn_hd) or die($k_b);
$count_rows1 = mysql_num_rows($result4);
if ($count_rows1>0) {
	
	while ($newArray = mysql_fetch_array($result4)) {
		$temp_id  			= $newArray['zgl_id'];
		$temp_nr			= $newArray['zgl_nr'];
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
		$temp_priorytet		= $newArray['zgl_priorytet'];
		$temp_status 		= $newArray['zgl_status'];
		$temp_data_roz		= $newArray['zgl_data_rozpoczecia'];
		$temp_data_zak		= $newArray['zgl_data_zakonczenia'];
		$temp_opz			= $newArray['zgl_osoba_potwierdzajaca_zamkniecie_zgloszenia'];
		$temp_km			= $newArray['zgl_razem_km'];
		$temp_or			= $newArray['zgl_osoba_rejestrujaca'];
		$temp_op			= $newArray['zgl_osoba_przypisana'];
		$temp_zz			= $newArray['zgl_zasadne'];

		$temp_kategoria_opis 	= $newArray['hd_kategoria_opis'];
		$temp_podkategoria_opis = $newArray['hd_podkategoria_opis'];
		$temp_priorytet_opis 	= $newArray['hd_priorytet_opis'];
		$temp_status_opis 		= $newArray['hd_status_opis'];
		
		$temp_belongs_to_opis = $newArray['location_name'];
		
		tbl_tr_highlight($i);
		
		if ($_POST[pole_1]=='on') echo "<td>$i</td>";
		if ($_POST[pole_2]=='on') echo "<td>$temp_nr</td>";
		if ($_POST[pole_3]=='on') echo "<td>$temp_poczta_nr</td>";
		if ($_POST[pole_4]=='on') echo "<td>$temp_data</td>";
		if ($_POST[pole_5]=='on') echo "<td>$temp_godzina</td>";
		if ($_POST[pole_6]=='on') echo "<td>$temp_komorka</td>";
		if ($_POST[pole_7]=='on') echo "<td>$temp_osoba</td>";
		if ($_POST[pole_8]=='on') echo "<td>$temp_telefon</td>";
		if ($_POST[pole_9]=='on') echo "<td>$temp_temat</td>";
		if ($_POST[pole_10]=='on') echo "<td>$temp_tresc</td>";
		if ($_POST[pole_11]=='on') echo "<td>$temp_kategoria_opis</td>";
		if ($_POST[pole_12]=='on') echo "<td>$temp_podkategoria_opis</td>";
		if ($_POST[pole_13]=='on') echo "<td>$temp_priorytet_opis</td>";
		if ($_POST[pole_14]=='on') echo "<td>$temp_status_opis</td>";
		
		if ($temp_op=='') $temp_op = '-';
		if ($_POST[pole_15]=='on') echo "<td>$temp_op</td>";
		
		if ($temp_data_roz=='0000-00-00 00:00:00') $temp_data_roz = '';
		if ($temp_data_zak=='0000-00-00 00:00:00') $temp_data_zak = '';
		
		if ($_POST[pole_16]=='on') echo "<td>$temp_data_roz</td>";
		if ($_POST[pole_17]=='on') echo "<td>$temp_data_zak</td>";
		
		if ($_POST[pole_18]=='on') echo "<td>$temp_or</td>";
		if ($temp_zz=='1') { $temp_zz = 'TAK'; } else { $temp_zz = 'NIE'; }
		if ($_POST[pole_19]=='on') echo "<td>$temp_zz</td>";
		
		if ($temp_status!=9) { $temp_opz = '-'; } else { if ($temp_opz=='') $temp_opz='brak'; } 
		if ($_POST[pole_20]=='on') echo "<td>$temp_opz</td>";
		
		// jeżeli status=9 => pobierz czas zakończenia zgłoszenia
		if ($temp_status==9) {
			$r3 = mysql_query("SELECT zgl_szcz_czas_rozpoczecia_kroku, zgl_szcz_czas_wykonywania FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$temp_id') ORDER BY zgl_szcz_nr_kroku DESC LIMIT 1", $conn_hd) or die($k_b);
			list($w_zgl_szcz_czas_rozp, $w_zgl_szcz_czas_wyk)=mysql_fetch_array($r3);
			// wyznacz faktyczny czas zakończenia wykonywania kroku
			
			$newTime = AddMinutesToDate($w_zgl_szcz_czas_wyk,$w_zgl_szcz_czas_rozp);
			$temp_data_zamkniecia_zgloszenia = $newTime;
		} else $temp_data_zamkniecia_zgloszenia = '-';
		
		if ($_POST[pole_21]=='on') echo "<td>$temp_data_zamkniecia_zgloszenia</td>";
		if ($_POST[pole_22]=='on') echo "<td>$temp_belongs_to_opis</td>";
		
		echo "</tr>";
		
		
		// wylistuj kroki
		
		$sql_kroki = "SELECT zgl_szcz_nr_kroku, zgl_szcz_czas_rozpoczecia_kroku, hd_status_opis, zgl_szcz_wykonane_czynnosci, zgl_szcz_czas_wykonywania FROM $dbname_hd.hd_zgloszenie_szcz, $dbname_hd.hd_status WHERE (zgl_szcz_status=hd_status_nr) and (zgl_szcz_zgl_id='$temp_id') and (zgl_szcz_widoczne=1)";
		$result_kroki= mysql_query($sql_kroki, $conn_hd) or die($k_b);
		
		while ($newArray_kroki = mysql_fetch_array($result_kroki)) {
			$temp_krok_nr  			= $newArray_kroki['zgl_szcz_nr_kroku'];
			$temp_krok_czas_rozp	= $newArray_kroki['zgl_szcz_czas_rozpoczecia_kroku'];
			$temp_krok_status		= $newArray_kroki['hd_status_opis'];
			$temp_krok_wc  			= $newArray_kroki['zgl_szcz_wykonane_czynnosci'];
			$temp_krok_cw  			= $newArray_kroki['zgl_szcz_czas_wykonywania'];
			
			$newTime = AddMinutesToDate($temp_krok_cw,$temp_krok_czas_rozp);
			
			tbl_tr_highlight($i);
			
			echo "<td></td>";
			echo "<td>$temp_nr</td>";
			
			echo "<td>$temp_krok_nr</td>";
			$c = explode(" ",$temp_krok_czas_rozp);
			echo "<td>$c[0]</td>";
			echo "<td>$c[1]</td>";
			echo "<td>$temp_krok_cw</td>";
			$c = explode(" ",$newTime);
			echo "<td>$c[0]</td>";
			echo "<td>$c[1]</td>";
			echo "<td colspan=5>$temp_krok_wc</td>";
			echo "<td>$temp_krok_status</td>";
			echo "<td colspan=8></td>";
			echo "</tr>";			
		}
		
		$i++;
	}
}

echo "</table>";
*/
	echo "<form action=do_xls_htmlexcel_hd_g_raport_szczegolowy_ze_zgloszen.php METHOD=POST target=_blank>";

	echo "<input type=hidden name=pole_1 value='$_POST[pole_1]'>";
	echo "<input type=hidden name=pole_2 value='$_POST[pole_2]'>";
	echo "<input type=hidden name=pole_3 value='$_POST[pole_3]'>";
	echo "<input type=hidden name=pole_4 value='$_POST[pole_4]'>";
	echo "<input type=hidden name=pole_5 value='$_POST[pole_5]'>";
	echo "<input type=hidden name=pole_6 value='$_POST[pole_6]'>";
	echo "<input type=hidden name=pole_7 value='$_POST[pole_7]'>";
	echo "<input type=hidden name=pole_8 value='$_POST[pole_8]'>";
	echo "<input type=hidden name=pole_9 value='$_POST[pole_9]'>";
	echo "<input type=hidden name=pole_10 value='$_POST[pole_10]'>";
	echo "<input type=hidden name=pole_11 value='$_POST[pole_11]'>";
	echo "<input type=hidden name=pole_12 value='$_POST[pole_12]'>";
	echo "<input type=hidden name=pole_13 value='$_POST[pole_13]'>";
	echo "<input type=hidden name=pole_14 value='$_POST[pole_14]'>";
	echo "<input type=hidden name=pole_15 value='$_POST[pole_15]'>";
	echo "<input type=hidden name=pole_16 value='$_POST[pole_16]'>";
	echo "<input type=hidden name=pole_17 value='$_POST[pole_17]'>";
	echo "<input type=hidden name=pole_18 value='$_POST[pole_18]'>";
	echo "<input type=hidden name=pole_19 value='$_POST[pole_19]'>";
	echo "<input type=hidden name=pole_20 value='$_POST[pole_20]'>";
	echo "<input type=hidden name=pole_21 value='$_POST[pole_21]'>";
	echo "<input type=hidden name=pole_22 value='$_POST[pole_22]'>";
	
	echo "<input type=hidden name=data_od value='$data_od'>";
	echo "<input type=hidden name=data_do value='$data_do'>";
	echo "<input type=hidden name=obszar value='$obszar'>";

	startbuttonsarea("right");

	echo "<span style='float:left'>";
	echo "&nbsp;";
	addbuttons("wstecz");
	echo "</span>";

	addownsubmitbutton("'Generuj plik XLS'","refresh_");
	addbuttons("zamknij");

	endbuttonsarea();

_form();

?>

<script>HideWaitingMessage();</script>

<?php 

} else { 

$ok = 0;
$sql 	= "SELECT filia_leader FROM $dbname.serwis_filie WHERE filia_id=$es_filia";
$result = mysql_query($sql, $conn) or die($k_b);
while ($newArray = mysql_fetch_array($result)) {
	$temp_leader	= $newArray['filia_leader'];
	if ($temp_leader==$es_nr) $ok = 1;
}

function getFirstDayOfWeek($year, $weeknr) {
    $offset = date('w', mktime(0,0,0,1,1,$year));
    $offset = ($offset < 5) ?1-$offset : 8-$offset;
    $monday = mktime(0,0,0,1,1+$offset,$year);
    $date = strtotime('+' . ($weeknr- 1) . ' weeks', $monday);
    return date('Y-m-d',$date);
}

function getLastDayOfWeek($year, $weeknr) {
    $offset = date('w', mktime(0,0,0,1,1,$year));
    $offset = ($offset < 5) ?1-$offset : 8-$offset;
    $monday = mktime(0,0,0,1,1+$offset,$year);
    $date = strtotime('+' . ($weeknr) . ' weeks -1 day', $monday);
    return date('Y-m-d',$date);

}

pageheader("Generowanie szczegółowego raportu ze zgłoszeń");
echo "<form name=ruch action=hd_g_raport_szczegolowy_ze_zgloszen.php method=POST>";

	echo "<table cellspacing=1 align=center style=width:300px>";
	tbl_empty_row(1);

	$Rok_Min = '2010';

	echo "<tr>";
		echo "<td class=center colspan=2>";		
			echo "Raport dla roku:&nbsp;";

			if ($_REQUEST[rok]!='') { $Rok_Sel = $_REQUEST[rok]; } else { $Rok_Sel = date('Y'); }
			$Rok_Curr = date('Y');			
			$Lat_Wstecz = $Rok_Curr - $Rok_Min;
			
			echo "<select name=r_rok onChange=\"self.location='hd_g_raport_szczegolowy_ze_zgloszen.php?okres=".$_REQUEST[okres]."&rok='+this.value+'';\">";
				for ($r=$Rok_Curr-$Lat_Wstecz; $r<=$Rok_Curr; $r++) { 
					echo "<option value=$r "; if ($Rok_Sel==$r) echo "SELECTED"; echo ">$r</option>\n";
				}
			echo "</select>";
		echo "</td>";
	echo "</tr>";
	
	tbl_empty_row(1);

	echo "<tr height=30>";

		echo "<td>";
			echo "<input type=radio name=rr id=rr_D value='D'"; if ($_REQUEST[okres]=='D') echo " checked "; echo " onClick=\"self.location='hd_g_raport_szczegolowy_ze_zgloszen.php?okres=D&rok=".$_REQUEST[rok]."'; \"><a class=normalfont href=# onClick=\"document.getElementById('rr_D').checked=true; self.location='hd_g_raport_szczegolowy_ze_zgloszen.php?okres=D&rok=".$_REQUEST[rok]."'; \">Dzienny (od..do)</a>";
		echo "</td>";

		echo "<td class=left "; 
		if ($_REQUEST[okres]=='D') { echo " style='display:' "; } else { echo " style='display:none' "; }
		echo ">";
			echo "<input type=text size=8 maxlength=10 name=d_od id=d_od value='".date('Y-m-d')."'>";
			echo " ... ";
			echo "<input type=text size=8 maxlength=10 name=d_do id=d_do value='".date('Y-m-d')."'>";
		echo "</td>";		
	echo "</tr>";	
	
	echo "<tr height=30>";
		echo "<td>";
			echo "<input type=radio name=rr id=rr_T value='T'"; if ($_REQUEST[okres]=='T') echo " checked "; echo " onClick=\"self.location='hd_g_raport_szczegolowy_ze_zgloszen.php?okres=T&rok=".$_REQUEST[rok]."'; \"><a class=normalfont href=# onClick=\"document.getElementById('rr_T').checked=true; self.location='hd_g_raport_szczegolowy_ze_zgloszen.php?okres=T&rok=".$_REQUEST[rok]."'; \">Tygodniowy</a>";
		echo "</td>";

		if ($Rok_Curr == $Rok_Sel) { $Max_Week_Nr = date('W'); } else { 
			$Max_Week_Nr = date("W", mktime(0,0,0,12,28,$Rok_Sel));
		}
		
		$Week_nr = date('W');
		
		echo "<td class=left"; 
		if ($_REQUEST[okres]=='T') { echo " style='display:' "; } else { echo " style='display:none' "; }
		echo ">";

			echo "<select name=r_T_zakres>";
				for ($r=1; $r<=$Max_Week_Nr; $r++) { 
					echo "<option value='".getFirstDayOfWeek($_REQUEST[rok],$r)."@".getLastDayOfWeek($_REQUEST[rok],$r)."'";
					if (($Week_nr==$r) && ($Rok_Curr == $Rok_Sel)) echo " SELECTED ";
					echo ">".getFirstDayOfWeek($_REQUEST[rok],$r)." - ".getLastDayOfWeek($_REQUEST[rok],$r)."</option>\n";
				}
			echo "</select>";			
			
		echo "</td>";
	echo "</tr>";
	
	echo "<tr height=30>";
		echo "<td>";
			echo "<input type=radio name=rr id=rr_M value='M'"; if ($_REQUEST[okres]=='M') echo " checked "; echo " onClick=\"self.location='hd_g_raport_szczegolowy_ze_zgloszen.php?okres=M&rok=".$_REQUEST[rok]."'; \"><a class=normalfont href=# onClick=\"document.getElementById('rr_M').checked=true; self.location='hd_g_raport_szczegolowy_ze_zgloszen.php?okres=M&rok=".$_REQUEST[rok]."'; \">Miesięczny</a>";
		echo "</td>";

		echo "<td class=left"; 
		if ($_REQUEST[okres]=='M') { echo " style='display:' "; } else { echo " style='display:none' "; }
		echo ">";
			$Miesiac_Curr = date('m');
			
			echo "<select name=r_M_zakres>";
				echo "<option value='".$Rok_Sel."-01-01@".$Rok_Sel."-01-31' "; if (($Miesiac_Curr=='01') && ($Rok_Curr == $Rok_Sel)) echo "SELECTED"; echo ">Styczeń</option>\n";
				echo "<option value='".$Rok_Sel."-02-01@".$Rok_Sel."-02-29' "; if (($Miesiac_Curr=='02') && ($Rok_Curr == $Rok_Sel)) echo "SELECTED"; echo ">Luty</option>\n";
				echo "<option value='".$Rok_Sel."-03-01@".$Rok_Sel."-03-31' "; if (($Miesiac_Curr=='03') && ($Rok_Curr == $Rok_Sel)) echo "SELECTED"; echo ">Marzec</option>\n";
				echo "<option value='".$Rok_Sel."-04-01@".$Rok_Sel."-04-30' "; if (($Miesiac_Curr=='04') && ($Rok_Curr == $Rok_Sel)) echo "SELECTED"; echo ">Kwiecień</option>\n";
				echo "<option value='".$Rok_Sel."-05-01@".$Rok_Sel."-05-31' "; if (($Miesiac_Curr=='05') && ($Rok_Curr == $Rok_Sel)) echo "SELECTED"; echo ">Maj</option>\n";
				echo "<option value='".$Rok_Sel."-06-01@".$Rok_Sel."-06-30' "; if (($Miesiac_Curr=='06') && ($Rok_Curr == $Rok_Sel)) echo "SELECTED"; echo ">Czerwiec</option>\n";
				echo "<option value='".$Rok_Sel."-07-01@".$Rok_Sel."-07-31' "; if (($Miesiac_Curr=='07') && ($Rok_Curr == $Rok_Sel)) echo "SELECTED"; echo ">Lipiec</option>\n";
				echo "<option value='".$Rok_Sel."-08-01@".$Rok_Sel."-08-31' "; if (($Miesiac_Curr=='08') && ($Rok_Curr == $Rok_Sel)) echo "SELECTED"; echo ">Sierpień</option>\n";
				echo "<option value='".$Rok_Sel."-09-01@".$Rok_Sel."-09-30' "; if (($Miesiac_Curr=='09') && ($Rok_Curr == $Rok_Sel)) echo "SELECTED"; echo ">Wrzesień</option>\n";
				echo "<option value='".$Rok_Sel."-10-01@".$Rok_Sel."-10-31' "; if (($Miesiac_Curr=='10') && ($Rok_Curr == $Rok_Sel)) echo "SELECTED"; echo ">Październik</option>\n";
				echo "<option value='".$Rok_Sel."-11-01@".$Rok_Sel."-11-30' "; if (($Miesiac_Curr=='11') && ($Rok_Curr == $Rok_Sel)) echo "SELECTED"; echo ">Listopad</option>\n";
				echo "<option value='".$Rok_Sel."-12-01@".$Rok_Sel."-12-31' "; if (($Miesiac_Curr=='12') && ($Rok_Curr == $Rok_Sel)) echo "SELECTED"; echo ">Grudzień</option>\n";
				
			echo "</select>";
		echo "</td>";		
	echo "</tr>";

	echo "<tr height=30>";

		echo "<td>";
			echo "<input type=radio name=rr id=rr_K value='K'"; if ($_REQUEST[okres]=='K') echo " checked "; echo " onClick=\"self.location='hd_g_raport_szczegolowy_ze_zgloszen.php?okres=K&rok=".$_REQUEST[rok]."'; \"><a class=normalfont href=# onClick=\"document.getElementById('rr_K').checked=true; self.location='hd_g_raport_szczegolowy_ze_zgloszen.php?okres=K&rok=".$_REQUEST[rok]."'; \">Kwartalny</a>";
		echo "</td>";

		echo "<td class=left "; 
		if ($_REQUEST[okres]=='K') { echo " style='display:' "; } else { echo " style='display:none' "; }
		echo ">";
			$Miesiac_Curr = date('m');
			
			if ((($Miesiac_Curr=='01') || ($Miesiac_Curr=='02') || ($Miesiac_Curr=='03')) && ($Rok_Curr == $Rok_Sel)) $kw_nr = 1;
			if ((($Miesiac_Curr=='04') || ($Miesiac_Curr=='05') || ($Miesiac_Curr=='06')) && ($Rok_Curr == $Rok_Sel)) $kw_nr = 2;
			if ((($Miesiac_Curr=='07') || ($Miesiac_Curr=='08') || ($Miesiac_Curr=='09')) && ($Rok_Curr == $Rok_Sel)) $kw_nr = 3;
			if ((($Miesiac_Curr=='10') || ($Miesiac_Curr=='11') || ($Miesiac_Curr=='12')) && ($Rok_Curr == $Rok_Sel)) $kw_nr = 4;
			
			echo "<select name=r_K_zakres>";
				echo "<option value='".$Rok_Sel."-01-01@".$Rok_Sel."-03-31' "; if ($kw_nr==1) echo "SELECTED"; echo ">I kwartał</option>\n";
				echo "<option value='".$Rok_Sel."-04-01@".$Rok_Sel."-06-30' "; if ($kw_nr==2) echo "SELECTED"; echo ">II kwartał</option>\n";
				echo "<option value='".$Rok_Sel."-07-01@".$Rok_Sel."-09-30' "; if ($kw_nr==3) echo "SELECTED"; echo ">III kwartał</option>\n";
				echo "<option value='".$Rok_Sel."-10-01@".$Rok_Sel."-12-31' "; if ($kw_nr==4) echo "SELECTED"; echo ">IV kwartał</option>\n";
				
			echo "</select>";
			
		echo "</td>";		
	echo "</tr>";
	
	tbl_empty_row(2);
	endtable();

	echo "<table cellspacing=1 align=center>";
	//tbl_empty_row(1);

	echo "<tr>";
		echo "<td class=left>";		
			echo "<u>Pola do wyświetlenia w raporcie:</u>";
		echo "</td>";
	echo "</tr>";
	tbl_empty_row(1);
	echo "<tr>";
	echo "<td>";
		echo "<input type=checkbox name=pole_1 checked>&nbsp;LP";
		echo "<input type=checkbox name=pole_2 checked>&nbsp;Nr zgłoszenia";
		echo "<input type=checkbox name=pole_3 checked>&nbsp;Nr zgłoszenia poczty";
		echo "<input type=checkbox name=pole_4 checked>&nbsp;Data zgłoszenia";
		echo "<input type=checkbox name=pole_5 checked>&nbsp;Godzina zgłoszenia";
		echo "<input type=checkbox name=pole_6 checked>&nbsp;Komórka zgłaszająca";
		echo "<input type=checkbox name=pole_7 checked>&nbsp;Osoba zgłaszająca";
		echo "<input type=checkbox name=pole_8 checked>&nbsp;Telefon";
		echo "<input type=checkbox name=pole_9 checked>&nbsp;Temat";
		echo "<input type=checkbox name=pole_10 checked>&nbsp;Treść";
		echo "<input type=checkbox name=pole_11 checked>&nbsp;Kategoria";
		echo "<input type=checkbox name=pole_12 checked>&nbsp;Podkategoria";
		echo "<input type=checkbox name=pole_13 checked>&nbsp;Priorytet";
		echo "<input type=checkbox name=pole_14 checked>&nbsp;Status";
		echo "<input type=checkbox name=pole_15 checked>&nbsp;Osoba przypisana";
		echo "<input type=checkbox name=pole_16 checked>&nbsp;Umowna data rozpoczęcia";
		echo "<input type=checkbox name=pole_17 checked>&nbsp;Umowna data zakończenia";
		echo "<input type=checkbox name=pole_18 checked>&nbsp;Osoba rejestrująca";
		echo "<input type=checkbox name=pole_19 checked>&nbsp;Zasadność zgłoszenia";
		echo "<input type=checkbox name=pole_20 checked>&nbsp;Osoba potwierdzająca zamknięcie zgłoszenia";
		echo "<input type=checkbox name=pole_21 checked>&nbsp;Data zamknięcia zgłoszenia";
		echo "<input type=checkbox name=pole_22 checked>&nbsp;Filia";
	echo "</td>";
	echo "</tr>";
	tbl_empty_row(1);
	echo "</table>";

	
	startbuttonsarea("right");
	
	echo "<span style='float:left'>";
	addbuttons('zamknij');
	echo "</span>";

	addownsubmitbutton("'Generuj raport'","submit");
	endbuttonsarea();
	
	_form();	
	
	
	

}
// else errorheader("Funkcja dostępna tylko dla kierowników zespołów");
?>
</body>
</html>