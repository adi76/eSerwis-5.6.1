<?php include_once('header.php'); ?>
<body>
<?php include('body_start.php'); ?>
<?php
$sql = "SELECT * FROM $dbname.serwis_ewidencja_usuniecia WHERE (belongs_to=$es_filia) ORDER BY usuniecie_data DESC";
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
pageheader("Historia usunięć sprzętu z ewidencji",1,1);

startbuttonsarea("center");
if ($showall==0) {
	echo "<a class=paging href=r_ewidencja_usuniecia.php?paget=$page&showall=1>Pokaż wszystko na jednej stronie</a>";
} else {
	echo "<a class=paging href=r_ewidencja_usuniecia.php?page=$paget&showall=0>Dziel na strony</a>";
}
echo "| Łącznie: <b>$count_rows pozycji</b>";
endbuttonsarea();	
starttable();
echo "<tr>";
echo "<th class=center>LP</th><th>Podstawowe informacje o sprzęcie<th>Data usunięcie z ewidencji</th><th>Osoba odpowiedzialna</th><th class=center>Uwagi</th>";
echo "<th class=center>Opcje</th>";
echo "</tr>";

$i=0;

while ($dane = mysql_fetch_array($result)) {
	$uid 			= $dane['usuniecie_id'];
	$uewid_id		= $dane['usuniecie_ewidencja_id'];
	$udata 			= $dane['usuniecie_data'];
	$uuser 			= $dane['usuniecie_user'];
	$uuwagi			= $dane['usuniecie_uwagi'];
	
	tbl_tr_highlight($i);

	$i++;
	
	$sql1 = "SELECT * FROM $dbname.serwis_ewidencja WHERE (ewidencja_id=$uewid_id) LIMIT 1";
    $result1 = mysql_query($sql1, $conn) or die($k_b);
	while ($dane = mysql_fetch_array($result1)) {
		$etyp_id	= $dane['ewidencja_typ'];
		$enizest	= $dane['ewidencja_zestaw_ni'];
		$ekopis		= $dane['ewidencja_komputer_opis'];
		$eksn		= $dane['ewidencja_komputer_sn'];
		$emo		= $dane['ewidencja_monitor_opis'];
		$emsn		= $dane['ewidencja_monitor_sn'];
		$edo		= $dane['ewidencja_drukarka_opis'];
		$edsn		= $dane['ewidencja_drukarka_sn'];
		$edni		= $dane['ewidencja_drukarka_ni'];
		$k__procesor	= $dane['k_procesor'];
		$k__pamiec		= $dane['k_pamiec'];
		$k__dysk		= $dane['k_dysk'];
		$ekonf			= $dane1['ewidencja_konfiguracja'];
	}

	$sql77="SELECT * FROM $dbname.serwis_slownik_rola WHERE rola_id='$etyp_id'";
	$result77 = mysql_query($sql77, $conn) or die($k_b);
					
	while ($newArray77 = mysql_fetch_array($result77))
	{
	  $rolanazwa		= $newArray77['rola_nazwa'];
	}

	$konf_opis='Procesor '.$k__procesor.'GHz, '.$k__pamiec.'MB RAM, '.$k__dysk.'GB HDD';

	echo "<td class=center>$i</td>";
	echo "<td><b>$rolanazwa $ekopis, SN : $eksn, NI : $enizest</b></td>";
	echo "<td>$udata</td>";
	echo "<td>$uuser</td>";
	echo "<td class=center>";

	
	$uwagisa = ($uuwagi!='');
	if ($uwagisa=='1') {
		echo "<a title=' Czytaj uwagi '><input class=imgoption type=image src=img/comment.gif onclick=\"newWindow(480,330,'p_usuniecia_uwagi.php?type=u&id=$uid')\"></a>";
	} else echo "brak";
	
	echo "</td>";
	
	echo "<td class=center><a title=' Szczegółowe informacje o sprzęcie '><input class=imgoption type=image src=img/detail.gif onclick=\"newWindow_r(620,600,'p_ewidencja_szczegoly.php?id=$uewid_id')\"></a></td>";
	
	echo "</tr>";
}
endtable();

// paging_end
include_once('paging_end.php');
// paging_end

} else errorheader("Historia remontów jest pusta");

startbuttonsarea("right");
oddziel();
addlinkbutton("'Usuń sprzęt z ewidencji'","p_ewidencja.php?view=simple&sel_up=all&printpreview=0&ew_action=delete");
addbuttons("start");
endbuttonsarea();

include('body_stop.php'); 
?>
</body>
</html>