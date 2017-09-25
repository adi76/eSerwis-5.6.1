<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body onLoad="document.getElementById('search').focus();">
<?php
include('body_start.php');
		
$sql="SELECT * FROM $dbname_hd.hd_komorka_pracownicy ";
if ($es_m!=1) { $sql = $sql." WHERE belongs_to=$es_filia"; }

if ($_GET[view]=='normalne') $sql.=" and (hd_zgl_seryjne=0) ";
if ($_GET[view]=='seryjne') $sql.=" and (hd_zgl_seryjne=1) ";

if ($_GET[search]!='') $sql.=" AND (hd_komorka_pracownicy_nazwa LIKE '%".$_GET[search]."%') ";

$sql = $sql." ORDER BY hd_komorka_pracownicy_nazwa ASC";
//echo "$sql<br />";
$result = mysql_query($sql, $conn_hd) or die($k_b);

$totalrows = mysql_num_rows($result);

if ($showall==0) { $rps=$rowpersite; } else $rps=10000;
if(empty($page)){ $page = 1; }
$limitvalue = $page * $rps - ($rps);
$sql="SELECT * FROM $dbname_hd.hd_komorka_pracownicy ";
if ($es_m!=1) { $sql = $sql." WHERE belongs_to=$es_filia"; }

if ($_GET[view]=='normalne') $sql.=" and (hd_zgl_seryjne=0) ";
if ($_GET[view]=='seryjne') $sql.=" and (hd_zgl_seryjne=1) ";

if ($_GET[search]!='') $sql.=" AND (hd_komorka_pracownicy_nazwa LIKE '%".$_GET[search]."%') ";

$sql = $sql." ORDER BY hd_komorka_pracownicy_nazwa ASC LIMIT $limitvalue, $rps";
$result = mysql_query($sql, $conn_hd) or die($k_b);
if ($totalrows!=0) {

pageheader("Przeglądanie bazy osób zgłaszających",1,0);

?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
	
starttable();

	startbuttonsarea("center");
	if ($showall==0) {
		echo "<a class=paging href=$PHP_SELF?showall=1&paget=$page&search=".urlencode($_REQUEST[search]).">Pokaż wszystko na jednej stronie</a>";
	} else {
		echo "<a class=paging href=$PHP_SELF?showall=0&page=$paget&search=".urlencode($_REQUEST[search]).">Dziel na strony</a>";	
	}
	echo "| Łącznie: <b>$totalrows pozycji</b>";
	endbuttonsarea();

	startbuttonsarea("center");
	echo "<form name=komorki action=$PHP_SELF method=GET value='$_REQUEST[search]'>";
	hr();
	echo "<center>Szukaj wg osoby zgłaszającej: <input id=search type=text name=search size=30 onBlur=\"if (this.value=='') document.komorki.submit=false;\">";
	
	echo "&nbsp;<input type=button class=buttons value='Pokaż wszystko' onClick=\"document.location.href='$PHP_SELF?showall=$showall&okresm=01&okresr=$okresr&paget=$page&page=$paget&wybierz_kat=&wybierz_p=&wybierz_typ=&wybierz=&view=$view'\" />";
	echo "</center>";
	echo "</form>";
	endbuttonsarea();
	
th("40;c;LP|;;Osoba zgłaszająca|;;Telefon<br /><sub>Osoba aktualizująca</sub>|;;Komórka|50;c;Dla zgł. seryjnych|60;c;Opcje",$es_prawa);
$i = 0;
$j = $page*$rowpersite-$rowpersite+1;
while ($newArray = mysql_fetch_array($result)) {
	$temp_id_os  						= $newArray['hd_komorka_pracownicy_id'];
	$temp_komorka_id				= $newArray['hd_serwis_komorka_id'];
	$temp_nazwa_os					= $newArray['hd_komorka_pracownicy_nazwa'];
	$temp_telefon					= $newArray['hd_komorka_pracownicy_telefon'];
	$temp_zgl_seryjne				= $newArray['hd_zgl_seryjne'];
	$temp_aktualizowane_przez		= $newArray['hd_aktualizowane_przez'];

	tbl_tr_highlight($i);

		td(";c;".$j."|;;".$temp_nazwa_os."");
		
		td_(";");
			if ($temp_telefon=='') {
				echo "-<br /><sub><font color=red>$temp_aktualizowane_przez</font></sub>";
			}
			echo "<a class=normalfont title='Aktualizowane przez: $temp_aktualizowane_przez' href=# onClick=\"return false;\">$temp_telefon</a>";
		_td();
		
		td_(";");
			$result44 = mysql_query("SELECT serwis_komorki.up_id, serwis_komorki.up_nazwa, serwis_piony.pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) and (serwis_komorki.up_id=$temp_komorka_id) LIMIT 1", $conn) or die($k_b);
			list($temp_id,$temp_nazwa,$temp_pion)=mysql_fetch_array($result44);
		
			if ($temp_komorka_id!=0) { echo "$temp_pion $temp_nazwa"; } else { echo "-"; }
		
		_td();
			
		td_(";c");
			if ($temp_zgl_seryjne=='1') { echo "<b>TAK</b>"; } else { echo "NIE"; }
		_td();
		
		td_(";c");
			if (($temp_pion=='') && ($temp_nazwa=='')) $temp_pion = ' -';
			echo "<a title=' Edytuj dane o osobie zgłaszającej $temp_nazwa_os '><input class=imgoption type=image src=img/edit.gif  onclick=\"newWindow(600,200,'hd_e_osoba_zglaszajaca.php?id=$temp_id_os')\"></a>";
			echo "<a title=' Usuń osobę zgłaszającą $temp_nazwa_os ($temp_pion $temp_nazwa) z bazy '><input class=imgoption type=image src=img/delete.gif onclick=\"if (confirm('Czy napewno chcesz usunąć $temp_nazwa_os ($temp_pion $temp_nazwa) z bazy ?')) newWindow(10,10,'hd_u_osobe_zglaszajaca.php?select_id=$temp_id_os')\"></a>";
		_td();
	_tr();
	$i++;
	$j++;
}
endtable();
include_once('paging_end.php');
startbuttonsarea("right");
echo "<span style='float:left'>";
echo "<input type=button class=buttons value='Dla zgłoszeń pojedynczych' onClick=\"self.location.href='hd_z_osoby_zglaszajace.php?view=normalne'\" />";
echo "<input type=button class=buttons value='Dla zgłoszeń seryjnych' onClick=\"self.location.href='hd_z_osoby_zglaszajace.php?view=seryjne'\" />";
echo "<input type=button class=buttons value='Pokaż wszystkie' onClick=\"self.location.href='hd_z_osoby_zglaszajace.php?view=all'\" />";

$result44 = mysql_query("SELECT COUNT(hd_komorka_pracownicy_id) FROM $dbname_hd.hd_komorka_pracownicy WHERE (belongs_to=$es_filia) and (hd_komorka_pracownicy_telefon='')", $conn_hd) or die($k_b);
list($count_puste)=mysql_fetch_array($result44);

if (($kierownik_nr==$es_nr) && ($count_puste>0)) {
	echo "&nbsp;|&nbsp;<input type=button class=buttons style='color:red;' value='Usuń osoby bez wpisanego numeru telefonu ($count_puste)' onClick=\"if (confirm('Czy usunąć osoby bez wpisanego numeru telefonu ?')) { newWindow_r(400,200,'hd_z_osoby_zglaszajace_usun.php?countpuste=$count_puste'); } \" />";
}

echo "</span>";
addownlinkbutton("'Dodaj osobę zgłaszającą'","Button1","button","newWindow(600,200,'hd_d_osoba_zglaszajaca.php')");
addbuttons("zamknij");
endbuttonsarea();	 
	
} else {
	errorheader("Słownik treści jest pusty");
	//listabaz($es_prawa,"".$pokaz_ikony."");
	startbuttonsarea("right");
	echo "<span style='float:left'>";
	echo "<input type=button class=buttons value=' Dla zgłoszeń pojedynczych ' onClick=\"self.location.href='hd_z_osoby_zglaszajace.php?view=normalne'\" />";
	echo "<input type=button class=buttons value=' Dla zgłoszeń seryjnych ' onClick=\"self.location.href='hd_z_osoby_zglaszajace.php?view=seryjne'\" />";
	echo "<input type=button class=buttons value=' Pokaż wszystkie ' onClick=\"self.location.href='hd_z_osoby_zglaszajace.php?view=all'\" />";
	echo "</span>";
	addownlinkbutton("'Dodaj osobę zgłaszającą'","Button1","button","newWindow(600,200,'hd_d_osoba_zglaszajaca.php')");

	addbuttons("zamknij");
	endbuttonsarea();	 
}
//include('body_stop.php');
//include('js/menu.js');
?>

<script>HideWaitingMessage();</script>

</body>
</html>