<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body>
<?php 
include('body_start.php');

// weryfikacja aktywności dostępów czasowych dla wszystkich pracowników
	$aktualna_data = Date('Y-m-d H:i:s');
	$sql_dc = "UPDATE $dbname_hd.hd_dostep_czasowy SET dc_dostep_active=0 WHERE ((dc_dostep_active_to<'$aktualna_data') and (dc_dostep_active=1) and (belongs_to=$es_filia))";
	$result_dc = mysql_query($sql_dc, $conn_hd) or die($k_b);
// koniec weryfikacji aktywności dostępów czasowych

$sql="SELECT * FROM $dbname_hd.hd_dostep_czasowy WHERE dc_dostep_dla_osoby ='$_GET[user]' ";
if ($es_m!=1) { $sql = $sql." AND belongs_to=$es_filia"; }
$sql = $sql . " AND dc_dostep_active=$_GET[active] ";
$sql = $sql." ORDER BY dc_dostep_dla_daty DESC";
//echo "$sql";
$result = mysql_query($sql, $conn_hd) or die($k_b);

$totalrows = mysql_num_rows($result);
if ($showall==0) { $rps=$rowpersite; } else $rps=10000;
if(empty($page)){ $page = 1; }
$limitvalue = $page * $rps - ($rps);
$sql="SELECT * FROM $dbname_hd.hd_dostep_czasowy WHERE dc_dostep_dla_osoby='$_GET[user]' ";
if ($es_m!=1) { $sql = $sql." AND belongs_to=$es_filia"; }
$sql = $sql . " AND dc_dostep_active=$_GET[active] ";
$sql = $sql." ORDER BY dc_dostep_dla_daty DESC LIMIT $limitvalue, $rps";
$result = mysql_query($sql, $conn_hd) or die($k_b);
if ($totalrows!=0) {

if ($_GET[active]=='1') pageheader("Lista udzielonych dostępów czasowych dla użytkownika",1,0);
if ($_GET[active]=='0') pageheader("Lista nieaktywnych dostępów czasowych dla użytkownika",1,0);

starttable();
th(";c;LP|;c;Dostęp do daty|120;c;Dostęp ważny do|;c;Dostęp udzielony przez|120;;Data przyznania dostępu|;c;Opcje",$es_prawa);
$i = 0;
$j = 1;
while ($newArray = mysql_fetch_array($result)) {
	$temp_id  						= $newArray['dc_id'];
	$temp_dc_data					= $newArray['dc_dostep_dla_daty'];
	//$temp_dc_osoba					= $newArray['dc_dostep_dla_osoby'];
	$temp_dc_active_to				= $newArray['dc_dostep_active_to'];
	$temp_dc_active					= $newArray['dc_dostep_active'];
	$temp_dc_osoba_przyznajaca		= $newArray['dc_osoba_przyznajaca_dostep'];
	$temp_dc_data_przyznania		= $newArray['dc_data_przyznania_dostepu_czasowego'];
	
	tbl_tr_highlight($i);
	
if ($temp_dc_active==0) { echo "<font color=grey>"; }
	
	td("40;c;".$j."|80;c;".$temp_dc_data."|120;c;".$temp_dc_active_to."|120;r;".$temp_dc_osoba_przyznajaca."|120;c;".$temp_dc_data_przyznania."");

echo "</font>";

	td_("50;c");
		if ($temp_dc_active==1) 
		echo "<a title=' Usuń dostęp czasowy dla daty $temp_dc_data '><input class=imgoption type=image src=img/delete.gif onclick=\"if (confirm('Czy napewno chcesz usunąć datę $temp_dc_data z dostępu czasowego ?')) newWindow(10,10,'hd_u_dostep_czasowy.php?id=$temp_id'); \"></a>";
	_td();
	_tr();
	
	$i++;
	$j++;
}
endtable();
} else {
		if ($_GET[active]=='1') errorheader("Dla wybranego pracownika brak jest aktywnych dostępów czasowych");
		if ($_GET[active]=='0') errorheader("Dla wybranego pracownika nie ma nieaktywnych dostępów czasowych");
	}
//listabaz($es_prawa,"".$pokaz_ikony."");
startbuttonsarea("right");

if (($es_nr==$es_m) || ($is_dyrektor==1)) {
} else {
	okheader("Możliwość dodania dostępu czasowego ma tylko dyrektor");
}

if ($_GET[active]=='1') {
	echo "<span style='float:left'>";
	echo "<input type=button class=buttons value=' Pokaż nieaktywne dostępy ' onClick=\"document.location.href='$PHP_SELF?userid=$_GET[user_id]&user=".urlencode($_GET[user])."&active=0'\" >";
	echo "</span>";
} else {
	echo "<span style='float:left'>";
	echo "<input type=button class=buttons value=' Pokaż aktywne dostępy ' onClick=\"document.location.href='$PHP_SELF?userid=$_GET[user_id]&user=".urlencode($_GET[user])."&active=1'\" >";
	echo "</span>";
}

if (($es_nr==$es_m) || ($is_dyrektor==1)) {
	addownlinkbutton("'Dodaj dostęp czasowy'","Button1","button","newWindow(800,600,'hd_dodaj_dostep_czasowy.php?userid=".$_GET[user_id]."&user=".urlencode($_GET[user])."')");
}

addbuttons("zamknij");
endbuttonsarea();	 
//include('body_stop.php');
//include('js/menu.js');
?>
</body>
</html>