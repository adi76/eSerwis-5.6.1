<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');

if ($_REQUEST[search]=='1') { echo "<body onLoad=\"document.getElementById('search_zgl_nr').focus();\" />"; } 
	else { echo "<body onLoad=\"document.getElementById('filtr1').focus();\" />"; }
?>
	
<?php include('body_start.php'); 

if ($_REQUEST[search]=='1') {
	pageheader("Wyszukaj zgłoszenia",1,1);
	echo "<form name=szukaj action=$PHP_SELF method=POST onSubmit=\"return SzukajSprawdzPola();\" />";
	starttable("600px");
	tbl_empty_row(1);
	//tr_();	td_("200;r;<b>Wyszukaj wg</b>;"); _td(); td_(";;;");		_td(); _tr();
	tr_(); td_(";c;;");	
	echo "<fieldset><legend>Po numerze zgłoszenia</legend>";
	echo "<input style='height:40px; font-weight:bold; font-size:34px; padding:5px; text-align:center;' type=text name=search_zgl_nr id=search_zgl_nr maxlength=10 size=9 onKeyPress=\"return filterInputEnter(1, event, false); \">";	
	echo "</fieldset>";
	_td(); _tr();
	tr_();  td_(";c;;");	
	echo "<fieldset><legend>Po numerze zgłoszenia Poczty (HADIM)</legend>"; echo "<input style='height:40px; font-weight:bold; font-size:34px; padding:5px; text-align:center;' type=text name=search_hadim_nr id=search_hadim_nr maxlength=6 size=6 onKeyPress=\"return filterInputEnter(1, event, false); \">";	
	_td(); _tr();
	tr_(); td_(";c;;");	echo "<fieldset><legend>Po dacie zgłoszenia</legend>";echo "<input style='height:40px; font-weight:bold; font-size:34px; padding:5px; text-align:center;' type=text name=search_data id=search_data maxlength=10 size=10 onKeyPress=\"return filterInputEnter(1, event, false,'-'); \">";	
	echo "&nbsp;<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img src=img/cal.gif width=16 height=16 border=0></a>";
	_td(); _tr();
	tr_();	 td_(";c;;");
		echo "<fieldset><legend>Po nazwie komórki</legend>";	
		echo "<input style='height:40px; font-weight:bold; font-size:24px; padding:5px; text-align:center;' type=text name=search_komorka id=search_komorka maxlength=60 size=40>";	
		echo "<input type=hidden name=search_komorka_id id=search_komorka_id>";
	_td(); _tr();
	tr_();	 td_(";c;;");
		nowalinia();
		echo "<input type=submit class=buttons_hd value='Szukaj'><br/>";
		//echo "<input type=button class=buttons value='Wróć do strony głównej' onClick=\"window.location.href='main.php'\" />";
		addbuttons("wstecz","start");
		nowalinia();nowalinia();
	_td(); _tr();
	endtable();

	echo "<input type=hidden name=showall id=showall value='0'>";
	echo "<input type=hidden name=search id=search value='search-wyniki'>";
	
	echo "</form>";

} else {

echo "<input type=hidden name=showall1 id=showall1 value=$_REQUEST[showall]>";
echo "<input type=hidden name=page11 id=page11 value=$_REQUEST[page]>";
echo "<input type=hidden name=page12 id=page12 value=$_REQUEST[paget]>";

if ($_REQUEST[search]!='search-wyniki') {

	if (($_REQUEST[p6]!='X') && ($_REQUEST[p6]!='0') && ($_REQUEST[p6]!='')) {
	$sql="SELECT  hd_zgloszenie.zgl_id,hd_zgloszenie.zgl_nr,hd_zgloszenie.zgl_data,hd_zgloszenie.zgl_godzina,hd_zgloszenie.zgl_komorka,hd_zgloszenie.zgl_temat,hd_zgloszenie.zgl_kategoria,hd_zgloszenie.zgl_podkategoria,hd_zgloszenie.zgl_priorytet,hd_zgloszenie.zgl_status,hd_zgloszenie_szcz.zgl_szcz_osoba_wykonujaca_krok FROM $dbname_hd.hd_zgloszenie,$dbname_hd.hd_zgloszenie_szcz WHERE ";
	} else {
		$sql="SELECT * FROM $dbname_hd.hd_zgloszenie WHERE ";
	}
	
	if ($es_m==1) { } else $sql=$sql."(hd_zgloszenie.belongs_to=$es_filia) and (hd_zgloszenie.zgl_widoczne=1) ";
	// wg dnia
	if (($_REQUEST[p1]!='X') && ($_REQUEST[p1]!='0') && ($_REQUEST[p1]!='')) $sql.="AND (zgl_data='$_REQUEST[p1]') ";
	// wg kategorii
	if (($_REQUEST[p2]!='X') && ($_REQUEST[p2]!='0') && ($_REQUEST[p2]!='')) $sql.="AND (zgl_kategoria='$_REQUEST[p2]') ";
	// wg podkategorii
	if (($_REQUEST[p3]!='X') && ($_REQUEST[p3]!='0') && ($_REQUEST[p3]!='')) $sql.="AND (zgl_podkategoria='$_REQUEST[p3]') ";
	// wg priorytetu
	if (($_REQUEST[p4]!='X') && ($_REQUEST[p4]!='0') && ($_REQUEST[p4]!='')) $sql.="AND (zgl_priorytet='$_REQUEST[p4]') ";
	// wg statusu
	if (($_REQUEST[p5]!='X') && ($_REQUEST[p5]!='0') && ($_REQUEST[p5]!='')) $sql.="AND (zgl_status='$_REQUEST[p5]') ";
	// wg przypisania
	if (($_REQUEST[p6]!='X') && ($_REQUEST[p6]!='0') && ($_REQUEST[p6]!='')) {

			$p_6='';
			if ($_REQUEST[p6]!='9') $p_6=$_REQUEST[p6];
			$sql.="AND ((hd_zgloszenie_szcz.zgl_szcz_osoba_wykonujaca_krok='$p_6') and (hd_zgloszenie_szcz.zgl_szcz_zgl_id=hd_zgloszenie.zgl_nr)) ";
	}

	//if ($wybierz_p!='') $sql.="AND (serwis_komorki.up_pion_id=$wybierz_p) ";
	$sql=$sql."ORDER BY hd_zgloszenie.zgl_nr DESC";
	//echo "$sql";
} else {
	// jeżeli ma pokazać wyniki wyszukiwania
	$sql="SELECT * FROM $dbname_hd.hd_zgloszenie WHERE (hd_zgloszenie.belongs_to=$es_filia) and (hd_zgloszenie.zgl_widoczne=1) ";
	// wg nr zlecenia
	if ($_REQUEST[search_zgl_nr]!='') $sql.="AND (zgl_nr='$_REQUEST[search_zgl_nr]') ";
	// wg nr zlecenia hadim
	if ($_REQUEST[search_hadim_nr]!='') $sql.="AND (zgl_poczta_nr='$_REQUEST[search_hadim_nr]') ";
	// wg daty zgłoszenia
	if ($_REQUEST[search_data]!='') $sql.="AND (zgl_data='$_REQUEST[search_data]') ";
	// wg komórki
	if ($_REQUEST[search_komorka]!='') $sql.="AND (zgl_komorka='$_REQUEST[search_komorka]') ";
	$sql=$sql."ORDER BY hd_zgloszenie.zgl_nr DESC";
}

$result = mysql_query($sql, $conn_hd) or die($k_b);
$count_rows = mysql_num_rows($result);
// paging
$totalrows = mysql_num_rows($result);
if ($showall==0) { $rps=$rowpersite; } else $rps=10000;
if(empty($page)){ $page = 1; }
$limitvalue = $page * $rps - ($rps);

if ($_REQUEST[search]!='search-wyniki') {

	if (($_REQUEST[p6]!='X') && ($_REQUEST[p6]!='0') && ($_REQUEST[p6]!='')) {
	$sql="SELECT  hd_zgloszenie.zgl_id,hd_zgloszenie.zgl_nr,hd_zgloszenie.zgl_data,hd_zgloszenie.zgl_godzina,hd_zgloszenie.zgl_komorka,hd_zgloszenie.zgl_temat,hd_zgloszenie.zgl_kategoria,hd_zgloszenie.zgl_podkategoria,hd_zgloszenie.zgl_priorytet,hd_zgloszenie.zgl_status,hd_zgloszenie_szcz.zgl_szcz_osoba_wykonujaca_krok FROM $dbname_hd.hd_zgloszenie,$dbname_hd.hd_zgloszenie_szcz WHERE ";
	} else {
		$sql="SELECT * FROM $dbname_hd.hd_zgloszenie WHERE ";
	}
	
	if ($es_m==1) { } else $sql=$sql."(hd_zgloszenie.belongs_to=$es_filia) and (hd_zgloszenie.zgl_widoczne=1) ";
	// wg dnia
	if (($_REQUEST[p1]!='X') && ($_REQUEST[p1]!='0') && ($_REQUEST[p1]!='')) $sql.="AND (zgl_data='$_REQUEST[p1]') ";
	// wg kategorii
	if (($_REQUEST[p2]!='X') && ($_REQUEST[p2]!='0') && ($_REQUEST[p2]!='')) $sql.="AND (zgl_kategoria='$_REQUEST[p2]') ";
	// wg podkategorii
	if (($_REQUEST[p3]!='X') && ($_REQUEST[p3]!='0') && ($_REQUEST[p3]!='')) $sql.="AND (zgl_podkategoria='$_REQUEST[p3]') ";
	// wg priorytetu
	if (($_REQUEST[p4]!='X') && ($_REQUEST[p4]!='0') && ($_REQUEST[p4]!='')) $sql.="AND (zgl_priorytet='$_REQUEST[p4]') ";
	// wg statusu
	if (($_REQUEST[p5]!='X') && ($_REQUEST[p5]!='0') && ($_REQUEST[p5]!='')) $sql.="AND (zgl_status='$_REQUEST[p5]') ";
	// wg przypisania
	if (($_REQUEST[p6]!='X') && ($_REQUEST[p6]!='0') && ($_REQUEST[p6]!='')) {

			$p_6='';
			if ($_REQUEST[p6]!='9') $p_6=$_REQUEST[p6];
			$sql.="AND ((hd_zgloszenie_szcz.zgl_szcz_osoba_wykonujaca_krok='$p_6') and (hd_zgloszenie_szcz.zgl_szcz_zgl_id=hd_zgloszenie.zgl_nr)) ";
	}

	//if ($wybierz_p!='') $sql.="AND (serwis_komorki.up_pion_id=$wybierz_p) ";
	$sql=$sql."ORDER BY hd_zgloszenie.zgl_nr DESC LIMIT $limitvalue, $rps";
	//echo "$sql";
} else {
	// jeżeli ma pokazać wyniki wyszukiwania
	$sql="SELECT * FROM $dbname_hd.hd_zgloszenie WHERE (hd_zgloszenie.belongs_to=$es_filia) and (hd_zgloszenie.zgl_widoczne=1) ";
	// wg nr zlecenia
	if ($_REQUEST[search_zgl_nr]!='') $sql.="AND (zgl_nr='$_REQUEST[search_zgl_nr]') ";
	// wg nr zlecenia hadim
	if ($_REQUEST[search_hadim_nr]!='') $sql.="AND (zgl_poczta_nr='$_REQUEST[search_hadim_nr]') ";
	// wg daty zgłoszenia
	if ($_REQUEST[search_data]!='') $sql.="AND (zgl_data='$_REQUEST[search_data]') ";
	// wg komórki
	if ($_REQUEST[search_komorka]!='') $sql.="AND (zgl_komorka='$_REQUEST[search_komorka]') ";
	$sql=$sql."ORDER BY hd_zgloszenie.zgl_nr DESC LIMIT $limitvalue, $rps";
}
//if ($wybierz_p!='') $sql.="AND (serwis_komorki.up_pion_id=$wybierz_p) ";
//$sql=$sql."ORDER BY hd_zgloszenie.zgl_nr DESC LIMIT $limitvalue, $rps";

//echo "<br/>$sql<br />";
$result = mysql_query($sql, $conn) or die($k_b);
// koniec - paging
if ($count_rows!=0) {
	$header_add = '';
	if ($_REQUEST[p4]=='4') $header_add.=' - awarie krytyczne';
	if ($_REQUEST[p5]=='1') $header_add.=' - nowe zgłoszenia';
	if ($_REQUEST[p5]=='1') $header_add.=' - nowe zgłoszenia';
	if ($_REQUEST[p6]=='9') $header_add.=' - zgłoszenia nie przypisane';
	
	if (($_REQUEST[p1]!='') && ($_REQUEST[p1]!='X') && ($_REQUEST[p1]!='0')) $header_add.=' z dnia '.$_REQUEST[p1];
	
	pageheader("Przeglądanie bazy zgłoszeń ".$header_add."",1,1);
	startbuttonsarea("center");
	if ($showall==0) {
		echo "<a class=paging href=$PHP_SELF?showall=1&paget=$page&p1=$p1&p2=$p2&p3=$p3&p4=$p4&p5=$p5&p6=$p6&p7=$p7&search=$search>Pokaż wszystko na jednej stronie</a>";
	} else {
		echo "<a class=paging href=$PHP_SELF?showall=0&page=$paget&p1=$p1&p2=$p2&p3=$p3&p4=$p4&p5=$p5&p6=$p6&p7=$p7&search=$search>Dziel na strony</a>";	
	}
	endbuttonsarea();
	
startbuttonsarea("center");
echo "<fieldset style='background-color:#898989;padding:3px;width:99%;"; 
if ($search=='search-wyniki') echo "display:none;";
echo "'>";

	echo "auto-filtr<input style='border:0px;' type=checkbox id=autofiltr name=autofiltr"; 
	if ($_REQUEST[p7]=='true') echo " checked=checked";
	echo ">&nbsp;&nbsp;";
	// wg dnia
	echo "<select id=filtr1 name=filtr1 onChange=\"ApplyFiltrHD(document.getElementById('autofiltr').checked);\" />";
	echo "<option value='X'"; 	if ($_REQUEST[p1]=='X') echo " SELECTED";	echo ">wg daty</option>\n"; 
	echo "<option value=0";		if ($_REQUEST[p1]=='0') echo " SELECTED";	echo ">-wszystkie-</option>\n";
	echo "<option>-</options>\n";
	$sql_f1="SELECT DISTINCT(zgl_data) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_widoczne=1) and (belongs_to=$es_filia) ORDER BY zgl_data DESC LIMIT ".$max_ilosc_dni_w_przegladaniu_zgloszen."";
	$result_f1=mysql_query($sql_f1,$conn) or die($k_b);
	
	while ($dane_f1=mysql_fetch_array($result_f1)) {
		$temp_data = $dane_f1['zgl_data'];
		echo "<option value='$temp_data'";	if ($_REQUEST[p1]==$temp_data) echo " SELECTED";	echo ">$temp_data</option>\n";
	}
	echo "</select>";
	echo "&nbsp;";

	// wg kategorii
	echo "<select id=filtr2 name=filtr2 onChange=\"ApplyFiltrHD(document.getElementById('autofiltr').checked);\" />";
	echo "<option value='X'"; 	if ($_REQUEST[p2]=='X') echo " SELECTED";	echo ">wg kategorii</option>\n"; 
	echo "<option value=0";		if ($_REQUEST[p2]=='0') echo " SELECTED";	echo ">-wszystkie-</option>\n";
	echo "<option>-</options>\n";
	
	$sql_f1="SELECT DISTINCT(hd_kategoria.hd_kategoria_opis), hd_kategoria.hd_kategoria_nr, hd_zgloszenie.zgl_kategoria FROM $dbname_hd.hd_kategoria, $dbname_hd.hd_zgloszenie WHERE (hd_kategoria.hd_kategoria_wlaczona=1) and (hd_zgloszenie.zgl_kategoria=hd_kategoria.hd_kategoria_nr) ORDER BY hd_kategoria_nr ASC";
	$result_f1=mysql_query($sql_f1,$conn) or die($k_b);
	
	while ($dane_f1=mysql_fetch_array($result_f1)) {
		$temp_nr = $dane_f1['hd_kategoria_nr'];
		$temp_opis = $dane_f1['hd_kategoria_opis'];
		echo "<option value='$temp_nr'";	if ($_REQUEST[p2]==$temp_nr) echo " SELECTED";	echo ">$temp_opis</option>\n";
	}
	echo "</select>";
	echo "&nbsp;";
	
	// wg podkategorii
	echo "<select id=filtr3 name=filtr3 onChange=\"ApplyFiltrHD(document.getElementById('autofiltr').checked);\" />";
	echo "<option value='X'"; 	if ($_REQUEST[p3]=='X') echo " SELECTED";	echo ">wg podkategorii</option>\n"; 
	echo "<option value=0";		if ($_REQUEST[p3]=='0') echo " SELECTED";	echo ">-wszystkie-</option>\n";
	echo "<option>-</options>\n";
	
	$sql_f1="SELECT DISTINCT(hd_podkategoria.hd_podkategoria_opis), hd_podkategoria.hd_podkategoria_nr, hd_zgloszenie.zgl_podkategoria FROM $dbname_hd.hd_podkategoria, $dbname_hd.hd_zgloszenie WHERE (hd_podkategoria.hd_podkategoria_wlaczona=1) and (hd_zgloszenie.zgl_podkategoria=hd_podkategoria.hd_podkategoria_nr) ORDER BY hd_podkategoria_nr ASC";
	
	$result_f1=mysql_query($sql_f1,$conn) or die($k_b);
	
	while ($dane_f1=mysql_fetch_array($result_f1)) {
		$temp_nr = $dane_f1['hd_podkategoria_nr'];
		$temp_opis = $dane_f1['hd_podkategoria_opis'];
		echo "<option value='$temp_nr'";	if ($_REQUEST[p3]==$temp_nr) echo " SELECTED";	echo ">$temp_opis</option>\n";
	}
	echo "</select>";
	echo "&nbsp;";

	// wg priorytetu
	echo "<select id=filtr4 name=filtr4 onChange=\"ApplyFiltrHD(document.getElementById('autofiltr').checked);\" />";
	echo "<option value='X'"; 	if ($_REQUEST[p4]=='X') echo " SELECTED";	echo ">wg priorytetu</option>\n"; 
	echo "<option value=0";		if ($_REQUEST[p4]=='0') echo " SELECTED";	echo ">-wszystkie-</option>\n";
	echo "<option>-</options>\n";
	
//	$sql_f1="SELECT hd_priorytet_nr, hd_priorytet_opis FROM $dbname_hd.hd_priorytet WHERE (hd_priorytet_wlaczona=1) ORDER BY hd_priorytet_nr ASC";

	$sql_f1="SELECT DISTINCT(hd_priorytet.hd_priorytet_opis), hd_priorytet.hd_priorytet_nr, hd_zgloszenie.zgl_priorytet FROM $dbname_hd.hd_priorytet, $dbname_hd.hd_zgloszenie WHERE (hd_priorytet.hd_priorytet_wlaczona=1) and (hd_zgloszenie.zgl_priorytet=hd_priorytet.hd_priorytet_nr) ORDER BY hd_priorytet_nr ASC";
	
	$result_f1=mysql_query($sql_f1,$conn) or die($k_b);
	
	while ($dane_f1=mysql_fetch_array($result_f1)) {
		$temp_nr = $dane_f1['hd_priorytet_nr'];
		$temp_opis = $dane_f1['hd_priorytet_opis'];
		echo "<option value='$temp_nr'";	if ($_REQUEST[p4]==$temp_nr) echo " SELECTED";	echo ">$temp_opis</option>\n";
	}
	echo "</select>";
	echo "&nbsp;";

	// wg statusu
	echo "<select id=filtr5 name=filtr5 onChange=\"ApplyFiltrHD(document.getElementById('autofiltr').checked);\" />";
	echo "<option value='X'"; 	if ($_REQUEST[p5]=='X') echo " SELECTED";	echo ">wg statusu</option>\n"; 
	echo "<option value=0";		if ($_REQUEST[p5]=='0') echo " SELECTED";	echo ">-wszystkie-</option>\n";
	echo "<option>-</options>\n";
	
//	$sql_f1="SELECT hd_status_nr, hd_status_opis FROM $dbname_hd.hd_status WHERE (hd_status_wlaczona=1) ORDER BY hd_status_nr ASC";

	$sql_f1="SELECT DISTINCT(hd_status.hd_status_opis), hd_status.hd_status_nr, hd_zgloszenie.zgl_status FROM $dbname_hd.hd_status, $dbname_hd.hd_zgloszenie WHERE (hd_status.hd_status_wlaczona=1) and (hd_zgloszenie.zgl_status=hd_status.hd_status_nr) ORDER BY hd_status_nr ASC";
	
	$result_f1=mysql_query($sql_f1,$conn) or die($k_b);
	
	while ($dane_f1=mysql_fetch_array($result_f1)) {
		$temp_nr = $dane_f1['hd_status_nr'];
		$temp_opis = $dane_f1['hd_status_opis'];
		echo "<option value='$temp_nr'";	if ($_REQUEST[p5]==$temp_nr) echo " SELECTED";	echo ">$temp_opis</option>\n";
	}
	echo "</select>";
	echo "&nbsp;";	

	// wg przypisania
	echo "<select id=filtr6 name=filtr6 onChange=\"ApplyFiltrHD(document.getElementById('autofiltr').checked);\" />";
	echo "<option value='X'"; 	if ($_REQUEST[p6]=='X') echo " SELECTED";	echo ">wg przypisania</option>\n"; 
	echo "<option value=0";		if ($_REQUEST[p6]=='0') echo " SELECTED";	echo ">-wszystkie-</option>\n";
//	echo "<option value=9";		if ($_REQUEST[p6]=='9') echo " SELECTED";	echo ">nie przypisane</option>\n";
	echo "<option>-</options>\n";	
	
	$sql_f1="SELECT DISTINCT(zgl_szcz_osoba_wykonujaca_krok) FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_widoczne=1) and (belongs_to=$es_filia) ORDER BY zgl_szcz_osoba_wykonujaca_krok ASC";
	$result_f1=mysql_query($sql_f1,$conn) or die($k_b);
	
	while ($dane_f1=mysql_fetch_array($result_f1)) {
		$temp_osoba = $dane_f1['zgl_szcz_osoba_wykonujaca_krok'];
		
		if ($temp_osoba=='') {
			echo "<option value='9'";	if ($_REQUEST[p6]=='9') echo " SELECTED";	echo ">nie przypisane</option>\n";
		} else {
			echo "<option value='$temp_osoba'";	if ($_REQUEST[p6]=='$temp_osoba') echo " SELECTED";	echo ">$temp_osoba</option>\n";
		}
	}
	echo "</select>";
	echo "&nbsp;";
	
echo "<input type=button class=buttons style='padding:1px; margin:0px;height:21px;' value=' Zastostuj filtr ' onClick=\"ApplyFiltrHD(true); \" />";
echo "&nbsp;<input type=button class=buttons style='padding:1px; margin:0px;height:21px;' value=' Czyść filtr ' onClick=\"document.getElementById('filtr1').selectedIndex=0;document.getElementById('filtr2').selectedIndex=0;document.getElementById('filtr3').selectedIndex=0;document.getElementById('filtr4').selectedIndex=0;document.getElementById('filtr5').selectedIndex=0;document.getElementById('filtr6').selectedIndex=0;ApplyFiltrHD(true);\">";
			
echo "</fieldset>";
endbuttonsarea();
/*	
	startbuttonsarea("center");
	echo "<form name=zgloszenia action=$PHP_SELF method=GET>";
	hr();
	echo "Pokaż: ";
		echo "<select name=wybierz  onChange='document.location.href=document.komorki.wybierz.options[document.komorki.wybierz.selectedIndex].value'>";
		$sql_lista_up = "SELECT up_id, up_nazwa, pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((serwis_komorki.belongs_to=$es_filia) and (serwis_piony.pion_id=serwis_komorki.up_pion_id)) ORDER BY serwis_piony.pion_nazwa, serwis_komorki.up_nazwa";
		$wynik_lista_up = mysql_query($sql_lista_up,$conn) or die($k_b);
		echo "<option ";
		if ($wybierz=='') echo "SELECTED ";
		echo "value='$PHP_SELF?showall=$showall&okresm=01&okresr=$okresr&paget=$page&page=$paget&wybierz='>Wszystkie UP / komórki</option>\n";	
		while (list($temp_upid, $temp_upnazwa, $temp_pionnazwa)=mysql_fetch_array($wynik_lista_up)) {
			echo "<option "; 
			if ($wybierz==$temp_upid) echo "SELECTED ";
			echo "value='$PHP_SELF?showall=$showall&okresm=01&okresr=$okresr&paget=$page&page=$paget&wybierz=$temp_upid'>$temp_pionnazwa $temp_upnazwa</option>\n";	
		
		}
		echo "</select>";
		echo "&nbsp;";
		echo "<select name=wybierz_p  onChange='document.location.href=document.komorki.wybierz_p.options[document.komorki.wybierz_p.selectedIndex].value'>";
		$sql_lista_p = "SELECT DISTINCT pion_nazwa, pion_id FROM $dbname.serwis_piony ORDER BY pion_nazwa";
		$wynik_lista_p = mysql_query($sql_lista_p,$conn) or die($k_b);
		echo "<option ";
		if ($wybierz_p=='') echo "SELECTED ";
		echo "value='$PHP_SELF?showall=$showall&okresm=01&okresr=$okresr&paget=$page&page=$paget&wybierz_p='>Wszystkie piony</option>\n";	
		while (list($temp_pionnazwa, $temp_p_id)=mysql_fetch_array($wynik_lista_p)) {
			echo "<option "; 
			if ($wybierz_p==$temp_p_id) echo "SELECTED ";
			echo "value='$PHP_SELF?showall=$showall&okresm=01&okresr=$okresr&paget=1&page=1&wybierz_p=$temp_p_id'>$temp_pionnazwa</option>\n";	
		
		}
		echo "</select>";
		echo "</form>";
	endbuttonsarea();
*/	
	starttable();
	th(";c;LP|50;c;<sub>Numer<br />zgłoszenia</sub>|80;c;Data zgłoszenia|150;l;Placówka zgłaszająca|100%;;Temat|50;c;Priorytet|100;c;Status|100;;Przypisane do|40;c;Opcje",$es_prawa);
	// |30;;Kategoria<br />Podkategoria

	$i = 0;
	$j = $page*$rowpersite-$rowpersite+1;
	while ($newArray = mysql_fetch_array($result)) {
		$temp_id  			= $newArray['zgl_id'];
		$temp_nr			= $newArray['zgl_nr'];
		$temp_data			= $newArray['zgl_data'];
		$temp_godzina		= $newArray['zgl_godzina'];
		$temp_komorka		= $newArray['zgl_komorka'];
		$temp_osoba			= $newArray['zgl_osoba'];
		$temp_telefon		= $newArray['zgl_telefon'];
		$temp_temat			= $newArray['zgl_temat'];	
		$temp_kategoria		= $newArray['zgl_kategoria'];
		$temp_podkategoria	= $newArray['zgl_podkategoria'];
		$temp_priorytet		= $newArray['zgl_priorytet'];
		$temp_status 		= $newArray['zgl_status'];
		$temp_data_zak		= $newArray['zgl_data_zakonczenia'];
		$temp_czas			= $newArray['zgl_razem_czas'];
		$temp_km			= $newArray['zgl_razem_km'];
		$temp_or			= $newArray['zgl_osoba_rejestrujaca'];
		$temp_zz			= $newArray['zgl_zasadne'];
		
		
		switch ($temp_kategoria) {
			case 2: 
				if ($temp_priorytet==2) { $kolorgrupy='#FF7F2A'; tbl_tr_color($i, $kolorgrupy); break; }
				if ($temp_priorytet==4) { $kolorgrupy='#F73B3B'; tbl_tr_color($i, $kolorgrupy); break; }			
			case 3:
				if ($temp_priorytet==3) { $kolorgrupy='#FFAA7F'; tbl_tr_color($i, $kolorgrupy); break; }
			default: tbl_tr_highlight($i);
		}
		
/*		
		if ($temp_kategoria==2) {
			if ($temp_priorytet==2) $kolorgrupy='#FF7F2A';
			if ($temp_priorytet==4) $kolorgrupy='#F73B3B';
			tbl_tr_color($i, $kolorgrupy);
		} 
	*/
//		$result1 = mysql_query("SELECT filia_nazwa FROM $dbname.serwis_filie WHERE filia_id=$temp_belongs_to", $conn) or die($k_b);
//		list($temp_filia_nazwa)=mysql_fetch_array($result1);
			td(";c;".$j."");
		
			
			
			$j++;
			//td("40;c;".$temp_nr."&nbsp;<img src='img/expand.gif's border=0>");
			echo "<td style='text-align:right;'>";
			echo $temp_nr;
			echo "&nbsp;";
			
			//echo "<a title=' Pokaż szczegóły zgłoszenia '><input class=imgoption type=image src=img/expand.gif onclick=\"newWindow(800,400,'p_komorka_czynnosc.php?id=$temp_id')\"></a>";
		if ($enableHDszczPreview==1) {
			echo "<a onclick=\"expand('SzczegolyFrame_".$i."', 'SzczegolyTresc_".$i."', 'SzczegolyImg_".$i."', this, 'tr_".$i."');\" href=\"hd_p_zgloszenia_test.php?search=1&detailrow=DetailContent_detail0_$i&a1=111&a2=222 \" /><input class=imgoption id=SzczegolyImg_".$i." type=image src=img/expand.gif></a>";
		}	
			//echo "<a onclick=\"expand('SzczegolyFrame_".$i."', 'SzczegolyTresc_".$i."', 'SzczegolyImg_".$i."', this);\" href=\"test.php?detailrow=DetailContent_detail0_$i&a1=111&a2=222 \" /><input class=imgoption id=SzczegolyImg_".$i." type=image src=img/expand.gif></a>";
			
			echo "</td>";
			td(";c;".$temp_data." ".$temp_godzina."");
			td(";;".$temp_komorka."");
			td(";;".$temp_temat."");
//			list($kategoria)=mysql_fetch_array(mysql_query("SELECT hd_kategoria_opis FROM $dbname_hd.hd_kategoria WHERE hd_kategoria_nr=$temp_kategoria LIMIT 1", $conn_hd));
//			list($podkategoria)=mysql_fetch_array(mysql_query("SELECT hd_podkategoria_opis FROM $dbname_hd.hd_podkategoria WHERE hd_podkategoria_nr=$temp_podkategoria LIMIT 1", $conn_hd));
			list($priorytet)=mysql_fetch_array(mysql_query("SELECT hd_priorytet_opis FROM $dbname_hd.hd_priorytet WHERE hd_priorytet_nr=$temp_priorytet LIMIT 1", $conn_hd));
			list($status)=mysql_fetch_array(mysql_query("SELECT hd_status_opis FROM $dbname_hd.hd_status WHERE hd_status_nr=$temp_status LIMIT 1", $conn_hd));
//			td(";;".$kategoria."<br />".$podkategoria."");
			//td(";;".$podkategoria."");	
			if ($temp_priorytet==1) echo "<font color=grey><b>";
			if ($temp_priorytet==2) echo "<font color=green><b>";
			if ($temp_priorytet==3) echo "<font color=#BC4306><b>";
			if ($temp_priorytet==4) echo "<font color=black><b>";

			td(";c;".$priorytet."");
			echo "</b></font>";
			td(";c;".$status."");
			
		//	td(";;".$data_zak."");
		//	td(";r;".$temp_czas."");
		//	td(";r;".$temp_km."");
		list($przypisanedo)=mysql_fetch_array(mysql_query("SELECT zgl_szcz_osoba_wykonujaca_krok FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$temp_nr) LIMIT 1", $conn_hd));
		//echo "SELECT zgl_szcz_osoba_wykonujaca_krok FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id_nr=$temp_nr) LIMIT 1";
		
			if ($przypisanedo=='') $przypisanedo="<b><font color=black>nie przypisane</font></b>";
			td(";c;".$przypisanedo."");
			//td(";;".$temp_zz."");
			
//			list($typkomorki)=mysql_fetch_array(mysql_query("SELECT slownik_typ_komorki_opis FROM $dbname.serwis_slownik_typ_komorki WHERE slownik_typ_komorki_id=$temp_up_typ LIMIT 1", $conn));
//			list($kategoriakomorki)=mysql_fetch_array(mysql_query("SELECT slownik_kategoria_komorki_opis FROM $dbname.serwis_slownik_kategoria_komorki WHERE slownik_kategoria_komorki_id=$temp_up_kategoria LIMIT 1", $conn));
			
//			if ($temp_up_ko==0) $kompleksowa_obsluga = 'NIE';
//			if ($temp_up_ko==1) $kompleksowa_obsluga = '<b>TAK</b>';
			
//			list($pionnazwa)=mysql_fetch_array(mysql_query("SELECT pion_nazwa FROM $dbname.serwis_piony WHERE pion_id=$temp_pion_id LIMIT 1", $conn));
//			list($umowanr,$umowaopis)=mysql_fetch_array(mysql_query("SELECT umowa_nr,umowa_opis FROM $dbname.serwis_umowy WHERE umowa_id=$temp_umowa_id LIMIT 1",$conn));
		td_(";c");
			$result1 = mysql_query("SELECT todo_id FROM $dbname.serwis_komorka_todo WHERE (todo_up_id=$temp_id) and (belongs_to=$es_filia) and (todo_status=1)", $conn) or die($k_b);
			$lista_ilosc = mysql_num_rows($result1);
			if ($lista_ilosc>0) {
				echo "<a title=' Ilość czynności wykonanych w $temp_nazwa = $lista_ilosc '><input class=imgoption type=image src=img/czynnosc_lista.gif onclick=\"newWindow(800,400,'p_komorka_czynnosc.php?id=$temp_id')\"></a>";
			}
			echo "<a title=' Edycja danych o $temp_nazwa '><input class=imgoption type=image src=img/edit.gif onclick=\"newWindow(580,440,'e_komorka.php?select_id=$temp_id')\"></a>";
			$accessLevels = array("9");
			if (array_search($es_prawa, $accessLevels)>-1) {
			//	echo "<a title=' Usuń $temp_nazwa z bazy '><input class=imgoption type=image src=img/delete.gif  onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_komorka.php?select_id=$temp_id')\"></a>";
			}
			//echo "<a title=' Edytuj listę czynności do wykonania w $temp_nazwa '><input class=imgoption type=image src=img/clock_add.png onclick=\"newWindow(800,370,'p_komorka_czynnosc.php?id=$temp_id')\"></a>";
			echo "<a title=' Szczegóły zgłoszenia nr $temp_nr '><input class=imgoption type=image src=img/detail.gif onclick=\"newWindow_r(700,600,'p_komorka_szczegoly.php?id=$temp_id')\"></a>";
			//echo "<a title=' Pokaż sprzęt będący na stanie $temp_nazwa ' href=p_ewidencja.php?action=ewid_all&view=all&sel_up=$temp_id&printpreview=0&allowback=2 title=' Pokaż sprzęt będący na stanie $temp_nazwa '><img class=imgoption src=img/software.gif border=0></a>";
		_td();
		
	_tr();
	
	if ($enableHDszczPreview==1) {
		echo "<tr style='display:none;' id=tr_".$i.">";
		echo "<td colspan=9 style='border:none;padding:0px;height:0px;'>";
		echo "<iframe class=hidden id=SzczegolyFrame_".$i." name=SzczegolyFrame_".$i." style='width:100%'></iframe>";
		echo "<div class=hidden id=SzczegolyTresc_".$i."></div>";
		echo "</td>";
		echo "</tr>";
	}
	
	$i++;
}
endtable();
include_once('paging_end.php');

startbuttonsarea("right");
addlinkbutton("'Szukaj zgłoszenia'","hd_p_zgloszenia.php?showall=0&search=1");
addownlinkbutton("'Dodaj zgłoszenie'","Button1","button","newWindow_r(800,600,'hd_d_zgloszenie_simple.php?stage=1')");
//addownlinkbutton("'Dodaj zgłoszenie seryjne'","Button1","button","newWindow_r(800,600,'hd_d_zgloszenie_s.php?stage=1&filtr=X-X-X-X')");
addbuttons("wstecz","start");
endbuttonsarea();
} else { 
	errorheader("Brak zgłoszeń spełniających wybrane kryteria");
	startbuttonsarea("right");
	addbuttons("wstecz");
	addbuttons("start");
	endbuttonsarea();
	if ($search=='search-wyniki') {
		?><meta http-equiv="REFRESH" content="2;url=<?php echo "$linkdostrony";?>hd_p_zgloszenia.php?showall=0&search=1"><?php	
	}
}


include('body_stop.php');

}
?>
<script language="JavaScript">
var cal1 = new calendar1(document.forms['szukaj'].elements['search_data']);
	cal1.year_scroll = true;
	cal1.time_comp = false;
</script>
</body>
</html>