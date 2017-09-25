<?php include_once('header.php'); ?>
<body>
<?php include('body_start.php'); ?>
<?php

$sql = "SELECT * FROM $dbname.serwis_ewidencja_przesuniecia WHERE (belongs_to=$es_filia) ORDER BY przesuniecie_data DESC";
$result = mysql_query($sql, $conn) or die($k_b);
$count_rows = mysql_num_rows($result);

// paging
// -
$totalrows = mysql_num_rows($result);
if(empty($page)){ $page = 1; }

if ($showall==0) {
  $rps=$rowpersite;
} else $rps=10000;

$limitvalue = $page * $rps - ($rps);

if ($printpreview==0) {
	$sql=$sql." LIMIT $limitvalue, $rps";
}

$result = mysql_query($sql, $conn) or die($k_b);
// -
// koniec - paging

if ($count_rows!=0) {
pageheader("Historia przesunięć sprzętu",1,1);

startbuttonsarea("center");
if ($showall==0) {
	echo "<a class=paging href=r_ewidencja_przesuniecia.php?paget=$page&showall=1>Pokaż wszystko na jednej stronie</a>";
} else {
	echo "<a class=paging href=r_ewidencja_przesuniecia.php?page=$paget&showall=0>Dziel na strony</a>";
}
echo "| Łącznie: <b>$count_rows pozycji</b>";
endbuttonsarea();

starttable();
echo "<tr>";
echo "<th class=center>LP</th><th>Typ sprzętu, model<br />SN, NI</th><th>Poprzednia lokalizacja<br />Użytkownik, nr pokoju</th><th>Aktualna lokalizacja<br />Użytkownik, nr pokoju</th><th>Data przesunięcia</th><th>Osoba odpowiedzialna</th><th class=center>Uwagi</th>";
echo "<th class=center width=70>Opcje</th>";
echo "</tr>";

$i=0;
$j = $page*$rps-$rps+1;
while ($dane = mysql_fetch_array($result)) {

	$pid 		= $dane['przesuniecie_id'];
	$pewid_id	= $dane['przesuniecie_ewidencja_id'];
	$p_old_lok	= $dane['przesuniecie_old_lokalizacja'];
	$p_old_user	= $dane['przesuniecie_old_user'];
	$p_old_pokoj= $dane['przesuniecie_old_pokoj'];
	$pdata 		= $dane['przesuniecie_data'];
	$puser 		= $dane['przesuniecie_user'];
	$puwagi		= $dane['przesuniecie_uwagi'];

	tbl_tr_highlight($i);

	$i++;
	
	$sql1 = "SELECT * FROM $dbname.serwis_ewidencja WHERE (ewidencja_id=$pewid_id) LIMIT 1";
    $result1 = mysql_query($sql1, $conn) or die($k_b);
	while ($dane1 = mysql_fetch_array($result1)) {
		$etyp_id	= $dane1['ewidencja_typ'];
		$eup_nazwa	= $dane1['ewidencja_up_nazwa'];
		$euser		= $dane1['ewidencja_uzytkownik'];									  
		$enrpok		= $dane1['ewidencja_nr_pokoju'];
		
		$ew_typ_n  = $dane1['ewidencja_typ_nazwa'];
		$ew_zestaw_ni = $dane1['ewidencja_zestaw_ni'];
		
		$ew_k_opis = $dane1['ewidencja_komputer_opis'];
		$ew_k_sn = $dane1['ewidencja_komputer_sn'];
		
		$ew_m_opis = $dane1['ewidencja_monitor_opis'];
		$ew_m_sn = $dane1['ewidencja_monitor_sn'];
		
		$ew_d_opis = $dane1['ewidencja_drukarka_opis'];
		$ew_d_sn = $dane1['ewidencja_drukarka_sn'];
		$ew_d_ni = $dane1['ewidencja_drukarka_ni'];
		
		$ew_uwagi = $dane1['ewidencja_uwagi'];
	}
	echo "<td class=center>$j</td>";
	echo "<td>";
	
	if ($ew_typ_n=="Komputer") { echo "<img class=imgoption src=img/komputer.gif border=0 align=absmiddle title=' Komputer ' width=16 width=16>"; $ok=1; }
	if ($ew_typ_n=="Serwer") { echo "<img class=imgoption src=img/serwer.gif border=0 align=absmiddle title=' Serwer ' width=16 width=16>"; $ok=1; }
	if ($ew_typ_n=="Drukarka") { echo "<img class=imgoption src=img/drukarka.gif border=0 align=absmiddle title=' Drukarka ' width=16 width=16>"; $ok=1; }
	if ($ew_typ_n=="Router") { echo "<img class=imgoption src=img//router.gif border=0 align=absmiddle title=' Router ' width=16 width=16>"; $ok=1; }
	if (($ew_typ_n=="Switch") || ($ew_typ_n=="Hub")) { echo "<img class=imgoption src=img/switch.gif border=0 align=absmiddle title=' Switch ' width=16 width=16>"; $ok=1; }
	if ($ew_typ_n=="Notebook") { echo "<img class=imgoption src=img//notebook.gif border=0 align=absmiddle title=' Notebook ' width=16 width=16>"; $ok=1; }
	if ($ew_typ_n=="UPS") { echo "<img class=imgoption src=img/ups.gif border=0 align=absmiddle title=' UPS ' width=16 width=16>"; $ok=1; }

	echo "&nbsp;<b>$ew_typ_n&nbsp;";
	
	switch ($ew_typ_n) {
		case "Komputer"		: echo "$ew_k_opis</b><br />SN: $ew_k_sn, NI: $ew_zestaw_ni"; break; 
		case "Serwer"		: echo "$ew_k_opis"; break; 
		case "Notebook"		: echo "$ew_k_opis"; break; 
		case "Monitor"		: echo "$ew_m_opis"; break; 
		case "Drukarka"		: echo "$ew_d_opis"; break; 
		default				: echo "$ew_k_opis"; break; 
	}
	
	echo "</td>";
	
	$j++;
	
	$sql_up = "SELECT up_id, up_pion_id FROM $dbname.serwis_komorki WHERE (up_nazwa='$p_old_lok') and (belongs_to=$es_filia) LIMIT 1";
	$wynik = mysql_query($sql_up, $conn) or die($k_b);
	$dane_up = mysql_fetch_array($wynik);
	$temp_up_id1 = $dane_up['up_id'];
	$temp_pion_id = $dane_up['up_pion_id'];
	
	// nazwa pionu z id pionu
	$sql_get_pion = "SELECT pion_nazwa FROM $dbname.serwis_piony WHERE (pion_id='$temp_pion_id') LIMIT 1";
	$wynik_get_pion = mysql_query($sql_get_pion, $conn) or die($k_b);
	$dane_get_pion = mysql_fetch_array($wynik_get_pion);
	$temp_pion_nazwa_old = $dane_get_pion['pion_nazwa'];
	// koniec ustalania nazwy pionu

	$sql_up = "SELECT up_id, up_pion_id FROM $dbname.serwis_komorki WHERE (up_nazwa='$eup_nazwa') and (belongs_to=$es_filia) LIMIT 1";
	$wynik = mysql_query($sql_up, $conn) or die($k_b);
	$dane_up = mysql_fetch_array($wynik);
	$temp_up_id2 = $dane_up['up_id'];
	$temp_pion_id = $dane_up['up_pion_id'];
	
	// nazwa pionu z id pionu
	$sql_get_pion = "SELECT pion_nazwa FROM $dbname.serwis_piony WHERE (pion_id='$temp_pion_id') LIMIT 1";
	$wynik_get_pion = mysql_query($sql_get_pion, $conn) or die($k_b);
	$dane_get_pion = mysql_fetch_array($wynik_get_pion);
	$temp_pion_nazwa_new = $dane_get_pion['pion_nazwa'];
	// koniec ustalania nazwy pionu
	
	echo "<td class=nowrap>$temp_pion_nazwa_old $p_old_lok<br />$p_old_user, $p_old_pokoj</td>";
	echo "<td class=nowrap>$temp_pion_nazwa_new $eup_nazwa<br />$euser, $enrpok</td>";
	echo "<td>$pdata</td>";
	echo "<td>$puser</td>";
	echo "<td class=center>";
	$uwagisa = ($puwagi!='');
	if ($uwagisa=='1') {
		echo "<a  title=' Czytaj uwagi '><input class=imgoption type=image src=img/comment.gif onclick=\"newWindow(480,330,'p_przesuniecia_uwagi.php?type=p&id=$pid')\"></a>";
	} else echo "brak";
	
	echo "</td>";
	
	echo "<td class=center>";
	
		$_r = substr($pdata,0,4);
		$_m = substr($pdata,5,2);
		$_d = substr($pdata,8,2);
		
		switch ($ew_typ_n) {
			case "Komputer"		: $nu = $ew_typ_n ." ".$ew_k_opis; $ns = $ew_k_sn; $ni = $ew_zestaw_ni; break;
			case "Serwer"		: $nu = $ew_typ_n ." ".$ew_k_opis; $ns = $ew_k_sn; $ni = $ew_zestaw_ni; break;
			case "Notebook"		: $nu = $ew_typ_n ." ".$ew_k_opis; $ns = $ew_k_sn; $ni = $ew_zestaw_ni; break;
			case "Router"		: $nu = $ew_typ_n ." ".$ew_k_opis; $ns = $ew_k_sn; $ni = $ew_zestaw_ni; break;
			case "Switch"		: $nu = $ew_typ_n ." ".$ew_k_opis; $ns = $ew_k_sn; $ni = $ew_zestaw_ni; break;
			case "UPS"			: $nu = $ew_typ_n ." ".$ew_k_opis; $ns = $ew_k_sn; $ni = $ew_zestaw_ni; break;
			case "Hub"			: $nu = $ew_typ_n ." ".$ew_k_opis; $ns = $ew_k_sn; $ni = $ew_zestaw_ni; break;
			case "Monitor"		: $nu = $ew_typ_n ." ".$ew_m_opis; $ns = $ew_m_sn; $ni = '-'; break;
			case "Drukarka"		: $nu = $ew_typ_n ." ".$ew_d_opis; $ns = $ew_d_sn; $ni = '-'; break;
			default				: $nu = $ew_typ_n ." ".$ew_k_opis; $ns = $ew_k_sn; $ni = '-'; break;
		}

		if ($ns=='') $ns='-';
		if ($ni=='') $ni='-';
		
		$wc = 'Pobranie sprzętu dla komórki '.$temp_pion_nazwa_new.' '.$eup_nazwa.' '.$euser.', '.$enrpok.'';
		// protokół pobrania
		echo "<a title=' Generuj protokół pobrania '><input class=imgoption type=image src=img//protokol_pobrania.gif  onclick=\"newWindow_r(700,595,'utworz_protokol.php?dzien=$_d&miesiac=$_m&rok=$_r&source=ewidencja-przesuniecia&new_upid=$temp_up_id1&nu=".urlencode($nu)."&ns=".urlencode($ns)."&ni=".urlencode($ni)."&wykonane_czynnosci=".urlencode($wc)."&choosefromewid=0&state=empty&nowy=1&opis_uszkodzenia=&disableall=1')\"></a>";	

		// protokół przekazania
		$wc = 'Przekazanie sprzętu z komórki '.$temp_pion_nazwa_old.' '.$p_old_lok.' '.$p_old_user.', '.$p_old_pokoj.'';
		// protokół pobrania
		echo "<a title=' Generuj protokół pobrania '><input class=imgoption type=image src=img//protokol_przekazania.gif  onclick=\"newWindow_r(700,595,'utworz_protokol.php?dzien=$_d&miesiac=$_m&rok=$_r&source=ewidencja-przesuniecia&new_upid=$temp_up_id2&nu=".urlencode($nu)."&ns=".urlencode($ns)."&ni=".urlencode($ni)."&wykonane_czynnosci=".urlencode($wc)."&choosefromewid=0&state=empty&nowy=1&opis_uszkodzenia=&disableall=1')\"></a>";	
		
		echo "<a title=' Szczegółowe informacje aktualnej lokalizacji sprzętu '><input class=imgoption type=image src=img/detail.gif onclick=\"newWindow_r(620,600,'p_ewidencja_szczegoly.php?id=$pewid_id')\"></a>";
	echo "</td>";
	
	echo "</tr>";
}
endtable();

// paging_end
include_once('paging_end.php');
// paging_end

} else errorheader("Historia przesunięć jest pusta");

startbuttonsarea("right");
oddziel();
addlinkbutton("'Przesuń sprzęt'", "p_ewidencja.php?view=simple&sel_up=all&printpreview=0&ew_action=move");
addbuttons("start");
endbuttonsarea();

include('body_stop.php'); 
?>
</body>
</html>