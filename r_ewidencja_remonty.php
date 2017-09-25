<?php include_once('header.php'); ?>
<body>
<?php include('body_start.php'); ?>
<?php

$sql = "SELECT * FROM $dbname.serwis_ewidencja_remonty WHERE (belongs_to=$es_filia) ORDER BY remont_data DESC";
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
pageheader("Historia remontów sprzętu",1,1);

startbuttonsarea("center");
if ($showall==0) {
	echo "<a class=paging href=r_ewidencja_remonty.php?paget=$page&showall=1>Pokaż wszystko na jednej stronie</a>";
} else {
	echo "<a class=paging href=r_ewidencja_remonty.php?page=$paget&showall=0>Dziel na strony</a>";
	}
echo "| Łącznie: <b>$count_rows pozycji</b>";
endbuttonsarea();
starttable();
echo "<tr>";
echo "<th class=center>LP</th><th>Typ sprzętu, model<br />SN, NI</th><th>Poprzednia konfiguracja<th>Nowa konfiguracja<th>Data remontu</th><th>Osoba odpowiedzialna</th><th>Uwagi</th>";
echo "<th class=center>Opcje</th>";
echo "</tr>";

$i=0;

while ($dane = mysql_fetch_array($result)) {
	$rid 			= $dane['remont_id'];
	$rewid_id		= $dane['remont_ewidencja_id'];
	$r_old_procesor	= $dane['remont_old_procesor'];
	$r_old_pamiec	= $dane['remont_old_pamiec'];
	$r_old_dysk		= $dane['remont_old_dysk'];
	$rdata 			= $dane['remont_data'];
	$ruser 			= $dane['remont_user'];
	$ruwagi			= $dane['remont_uwagi'];

	$r_new_procesor	= $dane['remont_new_procesor'];
	$r_new_pamiec	= $dane['remont_new_pamiec'];
	$r_new_dysk		= $dane['remont_new_dysk'];
	
	tbl_tr_highlight($i);

	$i++;

	$sql1 = "SELECT * FROM $dbname.serwis_ewidencja WHERE (ewidencja_id=$rewid_id) LIMIT 1";
    $result1 = mysql_query($sql1, $conn) or die($k_b);
	while ($dane1 = mysql_fetch_array($result1)) {
		$etyp_id	= $dane1['ewidencja_typ'];
		$e_proc		= $dane1['k_procesor'];
		$e_pam		= $dane1['k_pamiec'];									  
		$e_dysk		= $dane1['k_dysk'];
		$ekonf		= $dane1['ewidencja_konfiguracja'];
		
		$ew_typ_n  = $dane1['ewidencja_typ_nazwa'];
		$ew_zestaw_ni = $dane1['ewidencja_zestaw_ni'];
		
		$ew_k_opis = $dane1['ewidencja_komputer_opis'];
		$ew_k_sn = $dane1['ewidencja_komputer_sn'];
		
		$ew_m_opis = $dane1['ewidencja_monitor_opis'];
		$ew_m_sn = $dane1['ewidencja_monitor_sn'];
		
		$ew_d_opis = $dane1['ewidencja_drukarka_opis'];
		$ew_d_sn = $dane1['ewidencja_drukarka_sn'];
		$ew_d_ni = $dane1['ewidencja_drukarka_ni'];		
	}

	$konf_opis_old='Procesor '.$r_old_procesor.'GHz, '.$r_old_pamiec.'MB RAM, '.$r_old_dysk.'GB HDD';
	$konf_opis='Procesor '.$r_new_procesor.'GHz, '.$r_new_pamiec.'MB RAM, '.$r_new_dysk.'GB HDD';
	
	echo "<td class=center>$i</td>";
	
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
	
	echo "<td class=nowrap><b>$konf_opis_old</td>";
	echo "<td class=nowrap valign=center><b>$konf_opis</b></td>";
	echo "<td>$rdata</td>";
	echo "<td>$ruser</td>";
	echo "<td class=center>";

	$uwagisa = ($ruwagi!='');
	if ($uwagisa=='1') {
		echo "<a title=' Czytaj uwagi '><input class=imgoption type=image src=img/comment.gif  onclick=\"newWindow(480,330,'p_remonty_uwagi.php?type=r&id=$rid')\"></a>";
	} else echo "brak";
	
	echo "</td>";
	
	echo "<td class=center><a title=' Szczegółowe informacje o aktualnej konfiguracji sprzętu '><input class=imgoption type=image src=img/detail.gif onclick=\"newWindow(610,600,'p_ewidencja_szczegoly.php?id=$rewid_id')\"></a></td>";
	
	echo "</tr>";
}
endtable();

// paging_end
include_once('paging_end.php');
// paging_end

} else errorheader("Historia remontów jest pusta");

startbuttonsarea("right");
oddziel();
addlinkbutton("'Remontuj sprzęt'", "p_ewidencja.php?view=simple&sel_up=all&printpreview=0&ew_action=change");
addbuttons("start");
endbuttonsarea();

include('body_stop.php'); 
?>
</body>
</html>