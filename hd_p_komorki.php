<?php include_once('header.php'); ?>
<body onLoad="document.getElementById('search').focuse();">
<?php include('body_start.php'); 
if ($_GET[search]!='') $wybierz='';

$sql="SELECT up_id,up_nazwa,up_adres,up_ip,up_nrwanportu,up_telefon,up_komorka_macierzysta_id FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ";
if ($es_m==1) { } else $sql=$sql."(serwis_komorki.belongs_to=$es_filia) and  ";
$sql.=" (serwis_komorki.up_pion_id=serwis_piony.pion_id) ";
if ($wybierz!='') $sql.="AND (serwis_komorki.up_id=$wybierz) ";
if ($wybierz_p!='') $sql.="AND (serwis_komorki.up_pion_id=$wybierz_p) ";

if ($wybierz_typ!='') $sql.="AND (serwis_komorki.up_typ=$wybierz_typ) ";
if ($wybierz_kat!='') $sql.="AND (serwis_komorki.up_kategoria=$wybierz_kat) ";
if ($aktywne!='') $sql.="AND (serwis_komorki.up_active=$aktywne) ";
if ($ko!='') $sql.="AND (serwis_komorki.up_kompleksowa_obsluga=$ko) ";

$sql.=" AND ((serwis_komorki.up_telefon<>'') OR (serwis_komorki.up_nrwanportu<>'')) ";

if ($_GET[search]!='') $sql.=" AND (serwis_komorki.up_nazwa LIKE '%".$_GET[search]."%') ";

$sql=$sql."ORDER BY serwis_piony.pion_nazwa,serwis_komorki.up_nazwa ASC";

$result = mysql_query($sql, $conn) or die($k_b);
$count_rows = mysql_num_rows($result);
// paging
$totalrows = mysql_num_rows($result);
if ($showall==0) { $rps=$rowpersite; } else $rps=10000;
if(empty($page)){ $page = 1; }
$limitvalue = $page * $rps - ($rps);
$sql="SELECT up_id,up_nazwa,up_adres,up_ip,up_nrwanportu,up_telefon,up_pion_id,belongs_to,up_komorka_macierzysta_id FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ";
if ($es_m==1) { } else $sql=$sql."(serwis_komorki.belongs_to=$es_filia) and ";
$sql.=" (serwis_komorki.up_pion_id=serwis_piony.pion_id) ";
if ($wybierz!='') $sql.="AND (serwis_komorki.up_id=$wybierz) ";
if ($wybierz_p!='') $sql.="AND (serwis_komorki.up_pion_id=$wybierz_p) ";

if ($wybierz_typ!='') $sql.="AND (serwis_komorki.up_typ=$wybierz_typ) ";
if ($wybierz_kat!='') $sql.="AND (serwis_komorki.up_kategoria=$wybierz_kat) ";
if ($aktywne!='') $sql.="AND (serwis_komorki.up_active=$aktywne) ";
if ($ko!='') $sql.="AND (serwis_komorki.up_kompleksowa_obsluga=$ko) ";

$sql.=" AND ((serwis_komorki.up_telefon<>'') OR (serwis_komorki.up_nrwanportu<>'')) ";

if ($_GET[search]!='') $sql.=" AND (serwis_komorki.up_nazwa LIKE '%".$_GET[search]."%') ";

$sql=$sql."ORDER BY serwis_piony.pion_nazwa,serwis_komorki.up_nazwa ASC LIMIT $limitvalue, $rps";

$result = mysql_query($sql, $conn) or die($k_b);
// koniec - paging
if ($count_rows!=0) {
	pageheader("Wykaz numerów telefonów i adresów IP",1,0);
	startbuttonsarea("center");
	if ($showall==0) {
		echo "<a class='paging hideme' href=$PHP_SELF?showall=1&paget=$page&wybierz_p=$wybierz_p&wybierz_typ=$wybierz_typ&wybierz_kat=$wybierz_kat&aktywne=$aktywne&ko=$ko&search=".urlencode($_REQUEST[search]).">Pokaż wszystko na jednej stronie</a>";
	} else {
		echo "<a class='paging hideme' href=$PHP_SELF?showall=0&page=$paget&wybierz_p=$wybierz_p&wybierz_typ=$wybierz_typ&wybierz_kat=$wybierz_kat&aktywne=$aktywne&ko=$ko&search=".urlencode($_REQUEST[search]).">Dziel na strony</a>";	
	}
	echo "<span class=hideme>";
	echo "| Łącznie: <b>$count_rows pozycji</b>";
	echo "<span class=hideme>";
	
	endbuttonsarea();
	
	startbuttonsarea("center");
	echo "<form name=komorki action=$PHP_SELF method=GET value='$_REQUEST[search]'>";
	hr();
	echo "<span class=hideme>";
	echo "Szukaj: <input id=search type=text name=search size=30 onBlur=\"if (this.value=='') document.komorki.submit=false;\">";
	echo "</span>";
	
/*	echo "&nbsp;|&nbsp;Pokaż: ";
		echo "<select class=select_hd_p_zgloszenia name=wybierz  onChange='document.location.href=document.komorki.wybierz.options[document.komorki.wybierz.selectedIndex].value'>";
		$sql_lista_up = "SELECT up_id, up_nazwa, pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((serwis_komorki.belongs_to=$es_filia) and (serwis_piony.pion_id=serwis_komorki.up_pion_id) AND ((serwis_komorki.up_telefon<>'') OR (serwis_komorki.up_nrwanportu<>''))) ORDER BY serwis_piony.pion_nazwa, serwis_komorki.up_nazwa";
		$wynik_lista_up = mysql_query($sql_lista_up,$conn) or die($k_b);
		echo "<option ";
		if ($wybierz=='') echo "SELECTED ";
		echo "value='$PHP_SELF?showall=$showall&okresm=01&okresr=$okresr&paget=$page&page=1&wybierz=&wybierz_p=$wybierz_p&wybierz_typ=$wybierz_typ&wybierz_kat=$wybierz_kat&aktywne=$aktywne&ko=$ko'>Wszystkie UP / komórki</option>\n";	
		while (list($temp_upid, $temp_upnazwa, $temp_pionnazwa)=mysql_fetch_array($wynik_lista_up)) {
			echo "<option "; 
			if ($wybierz==$temp_upid) echo "SELECTED ";
			echo "value='$PHP_SELF?showall=$showall&okresm=01&okresr=$okresr&paget=$page&page=1&wybierz=$temp_upid&wybierz_p=$wybierz_p&wybierz_typ=$wybierz_typ&wybierz_kat=$wybierz_kat&aktywne=$aktywne&ko=$ko'>$temp_pionnazwa $temp_upnazwa</option>\n";	
		
		}
		echo "</select>";
*/
		echo "&nbsp;<input type=button class=buttons value='Pokaż wszystkie komórki' onClick=\"document.location.href='$PHP_SELF?showall=$showall&okresm=01&okresr=$okresr&paget=$page&page=$paget&wybierz_kat=&wybierz_p=&wybierz_typ=&wybierz='\" />";
		
		echo "</form>";
	endbuttonsarea();
	
	starttable();
	th("30;c;LP|;;Nazwa<br /><sub>Adres</sub>|70;;Podsieć<br /><sub>Nr WAN-portu</sub>|;;Telefon",$es_prawa);
	$i = 0;
	$j = $page*$rowpersite-$rowpersite+1;
	while ($newArray = mysql_fetch_array($result)) {
		$temp_id  			= $newArray['up_id'];
		$temp_nazwa			= $newArray['up_nazwa'];
		$temp_adres			= $newArray['up_adres'];
		$temp_telefon		= $newArray['up_telefon'];
		$temp_ip			= $newArray['up_ip'];
		$temp_nrwanportu	= $newArray['up_nrwanportu'];
		$temp_belongs_to	= $newArray['belongs_to'];
		$temp_pion_id		= $newArray['up_pion_id'];
		$temp_komorka_macierzysta = $newArray['up_komorka_macierzysta_id'];
		
		tbl_tr_highlight($i);
		$result1 = mysql_query("SELECT filia_nazwa FROM $dbname.serwis_filie WHERE filia_id=$temp_belongs_to", $conn) or die($k_b);
		list($temp_filia_nazwa)=mysql_fetch_array($result1);
			td("30;c;".$j."");
			$j++;
			
			if ($temp_up_ko==0) $kompleksowa_obsluga = 'NIE';
			if ($temp_up_ko==1) $kompleksowa_obsluga = '<b>TAK</b>';
			
			list($pionnazwa)=mysql_fetch_array(mysql_query("SELECT pion_nazwa FROM $dbname.serwis_piony WHERE pion_id=$temp_pion_id LIMIT 1", $conn));

		td_("50%;w");
			echo "<a href=# class=normalfont onclick=\"newWindow_r(700,600,'p_komorka_szczegoly.php?id=$temp_id')\"><b>";
			
			if ($temp_komorka_macierzysta>0) echo "<font color=grey>";			
			if (($temp_komorka_macierzysta>0) && ($wciecie_dla_podleglych_komorek==1)) echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			
			if ($temp_active_status=='0') echo "<strike>";
			echo "<b>".$pionnazwa." ".$temp_nazwa."</b>";
			if ($temp_active_status=='0') echo "</strike>";
			if ($temp_komorka_macierzysta>0) echo "</font>";
			
			echo "</a></b>";
			echo "<br /><sub>";
			if ($temp_active_status=='0') echo "<strike>";
			echo "".$temp_adres."";
			if ($temp_active_status=='0') echo "</strike>";
			echo "</sub>";
		_td();
		
		td(";;".$temp_ip."<br /><sub>".$temp_nrwanportu."</sub>|;w;".$temp_telefon."");
	
		$i++;
	_tr();
}
endtable();
include_once('paging_end.php');
//listabaz($es_prawa,"".$pokaz_ikony."");
startbuttonsarea("right");
$accessLevels = array("1","9");
if(array_search($es_prawa, $accessLevels)>-1){
	addbuttons("zamknij");
//	addownlinkbutton("'Dodaj komórkę/UP'","Button1","button","newWindow(580,600,'d_komorka.php')");
}
endbuttonsarea();

} else { 
	errorheader("Brak komórek spełniających wybrane kryteria");
	startbuttonsarea("right");
	addbuttons("wstecz");
	addbuttons("zamknij");
	endbuttonsarea();	
}
		

include('body_stop.php');
?>
<script>document.getElementById('search').focus();</script>
</body>
</html>