<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>

<body>
<?php include('body_start.php'); ?>
<?php
$sql="SELECT *,CONCAT(protokol_r,protokol_m,protokol_d) as DATA FROM $dbname.serwis_protokoly_historia ";
if ($es_m==1) { } else $sql=$sql."WHERE ((belongs_to=$es_filia)";
if ((submit) && ($okresm) && ($okresr)) {
	if ($pokaz!='all') $sql.=" and (protokol_m='$okresm') and (protokol_r='$okresr')";
}

if ($_REQUEST[osoba]!='') {
	$sql.= " and (protokol_kto='$_REQUEST[osoba]') ";
}

if ($_REQUEST[ss]!='') {
	$sql.= " and (protokol_hd_zgl_nr='$_REQUEST[ss]') ";
}

$sql=$sql.") ORDER BY DATA DESC, protokol_id DESC";
$result = mysql_query($sql, $conn) or die($k_b);
$count_rows = mysql_num_rows($result);
// paging
$totalrows = mysql_num_rows($result);
if ($showall==0) {  $rps=$rowpersite;} else $rps=10000;
if(empty($page)){ $page = 1; }
$limitvalue = $page * $rps - ($rps);
$sql="SELECT *,CONCAT(protokol_r,protokol_m,protokol_d) as DATA FROM $dbname.serwis_protokoly_historia ";
if ($es_m==1) {
} else $sql=$sql."WHERE ((belongs_to=$es_filia)";
if ((submit) && ($okresm) && ($okresr)) {
	if ($pokaz!='all') $sql.=" and (protokol_m='$okresm') and (protokol_r='$okresr')";
}
if ($_REQUEST[osoba]!='') {
	$sql.= " and (protokol_kto='$_REQUEST[osoba]') ";
}

if ($_REQUEST[ss]!='') {
	$sql.= " and (protokol_hd_zgl_nr='$_REQUEST[ss]') ";
}

$sql=$sql.") ORDER BY DATA DESC, protokol_id DESC LIMIT $limitvalue, $rps";
//echo $sql;
$result = mysql_query($sql, $conn) or die($k_b);
// koniec - paging

if ($_REQUEST[ss]!='') {
	pageheader("Przeglądanie zapisanych protokołów | <font color=red>powiązanych ze zgłoszeniem nr <b>$_REQUEST[ss]</b></font>",1,1);
} else {
	pageheader("Przeglądanie zapisanych protokołów",1,1);
}
	

// print_r($_SESSION);
// ====================================
		
	?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
		
	startbuttonsarea("center");
	if ($showall==0) {
		echo "<a class=paging href=$PHP_SELF?showall=1&paget=$page&okresm=$okresm&okresr=$okresr&osoba=".urlencode($osoba).">Pokaż wszystko na jednej stronie</a>";
	} else {
		echo "<a class=paging href=$PHP_SELF?showall=0&page=$paget&okresm=$okresm&okresr=$okresr&osoba=".urlencode($osoba).">Dziel na strony</a>";	
	}
	echo "| Łącznie: <b>$count_rows pozycji</b>";
	endbuttonsarea();
	startbuttonsarea("center");
	echo "<form name=protokoly action=$PHP_SELF method=GET>";
	hr();
	echo "Pokaż: ";
		echo "<a class='buttons normalfont' href=$phpfile?showall=$showall&page=$page&paget=$paget&pokaz=all&okresm=$okresm&okresr=$okresr>Wszystkie</a>";
		echo " | ";
		echo "dla osoby: ";
		
		$result44 = mysql_query("SELECT user_first_name,user_last_name FROM $dbname.serwis_uzytkownicy WHERE (belongs_to=$es_filia) and (user_locked=0) ORDER BY user_last_name ASC", $conn) or die($k_b);
		echo "<select name=osoba  onChange='document.location.href=document.protokoly.osoba.options[document.protokoly.osoba.selectedIndex].value'/>\n";
		echo "<option value='$PHP_SELF?showall=$showall&okresm=$okresm&okresr=$okresr&osoba='>Wybierz z listy</option>\n";
		while (list($temp_imie,$temp_nazwisko) = mysql_fetch_array($result44)) {
			$imieinazwisko = $temp_imie . " " . $temp_nazwisko;
			echo "<option value='$PHP_SELF?showall=$showall&okresm=$okresm&okresr=$okresr&osoba=".urlencode($imieinazwisko)."' ";
			if ($_REQUEST[osoba]==$imieinazwisko) echo " SELECTED";
			echo ">$imieinazwisko</option>\n"; 
		}
		echo "</select>\n";

		echo "| z wybranego okresu: ";
		$miesiac = Date('m');
		echo "<select name=okresm1 onChange='document.location.href=document.protokoly.okresm1.options[document.protokoly.okresm1.selectedIndex].value'>";
		echo "<option value='$PHP_SELF?showall=$showall&okresm=01&okresr=$okresr&osoba=".urlencode($osoba)."'"; if (('01'==$okresm)) echo " SELECTED"; echo ">Styczeń</option>\n";
		echo "<option value='$PHP_SELF?showall=$showall&okresm=02&okresr=$okresr&osoba=".urlencode($osoba)."'"; if (('02'==$okresm)) echo " SELECTED"; echo ">Luty</option>\n";
		echo "<option value='$PHP_SELF?showall=$showall&okresm=03&okresr=$okresr&osoba=".urlencode($osoba)."'"; if (('03'==$okresm)) echo " SELECTED"; echo ">Marzec</option>\n";
		echo "<option value='$PHP_SELF?showall=$showall&okresm=04&okresr=$okresr&osoba=".urlencode($osoba)."'"; if (('04'==$okresm)) echo " SELECTED"; echo ">Kwiecień</option>\n";
		echo "<option value='$PHP_SELF?showall=$showall&okresm=05&okresr=$okresr&osoba=".urlencode($osoba)."'"; if (('05'==$okresm)) echo " SELECTED"; echo ">Maj</option>\n";
		echo "<option value='$PHP_SELF?showall=$showall&okresm=06&okresr=$okresr&osoba=".urlencode($osoba)."'"; if (('06'==$okresm)) echo " SELECTED"; echo ">Czerwiec</option>\n";
		echo "<option value='$PHP_SELF?showall=$showall&okresm=07&okresr=$okresr&osoba=".urlencode($osoba)."'"; if (('07'==$okresm)) echo " SELECTED"; echo ">Lipiec</option>\n";
		echo "<option value='$PHP_SELF?showall=$showall&okresm=08&okresr=$okresr&osoba=".urlencode($osoba)."'"; if (('08'==$okresm)) echo " SELECTED"; echo ">Sierpień</option>\n";
		echo "<option value='$PHP_SELF?showall=$showall&okresm=09&okresr=$okresr&osoba=".urlencode($osoba)."'"; if (('09'==$okresm)) echo " SELECTED"; echo ">Wrzesień</option>\n";
		echo "<option value='$PHP_SELF?showall=$showall&okresm=10&okresr=$okresr&osoba=".urlencode($osoba)."'"; if (('10'==$okresm)) echo " SELECTED"; echo ">Październik</option>\n";
		echo "<option value='$PHP_SELF?showall=$showall&okresm=11&okresr=$okresr&osoba=".urlencode($osoba)."'"; if (('11'==$okresm)) echo " SELECTED"; echo ">Listopad</option>\n";
		echo "<option value='$PHP_SELF?showall=$showall&okresm=12&okresr=$okresr&osoba=".urlencode($osoba)."'"; if (('12'==$okresm)) echo " SELECTED"; echo ">Grudzień</option>\n";
		echo "</select>";
		echo "&nbsp;";
		echo "<select name=okresr1 onChange='document.location.href=document.protokoly.okresr1.options[document.protokoly.okresr1.selectedIndex].value'>";
		$rok = Date('Y');
		for ($r=2008;$r<=$rok;$r++) { 
			echo "<option value='$PHP_SELF?showall=$showall&okresm=$okresm&okresr=$r&osoba=".urlencode($osoba)."'"; 
			if ($r==$okresr) echo " SELECTED";
			echo ">$r</option>\n";
		}
		echo "</select>";
		
		//echo "<input type=submit style='height:21px; font-size:10px; padding-bottom:1px;' value='Pokaż'>";
	echo "</form>";
	endbuttonsarea();
	
if ($count_rows!=0) {
	
// zresetowanie zmiennych $_SESSION
$_SESSION[protokol_dodany_do_bazy]=1;
$_SESSION[wykonaj_magazyn_pobierz]=1;
$_SESSION[wykonaj_magazyn_zwrot]=1;
$_SESSION[wykonaj_naprawy_przyjecie]=1;
$_SESSION[wykonaj_naprawy_zwrot]=1;
$_SESSION[wykonaj_sprzedaz]=1;
$_SESSION[wykonaj_sprzedaz_zestawu]=1;
	
	starttable();
	th("30;c;LP|;c;Nr zgloszenia|;;Numer protokołu|;;Data protokołu|;;Urządzenie|;;UP / Komórka|;;Utworzony przez|;c;Opcje",$es_prawa);
	$i = 0;
	$j = $page*$rowpersite-$rowpersite+1;
	while ($newArray = mysql_fetch_array($result)) {
		$temp_id  			= $newArray['protokol_id'];			$temp_nr  			= $newArray['protokol_nr'];
		$temp_d  			= $newArray['protokol_d'];			$temp_m  			= $newArray['protokol_m'];
		$temp_r  			= $newArray['protokol_r'];			$temp_c1  			= $newArray['protokol_c1'];
		$temp_c2  			= $newArray['protokol_c2'];			$temp_c3  			= $newArray['protokol_c3'];
		$temp_c4  			= $newArray['protokol_c4'];			$temp_c5  			= $newArray['protokol_c5'];
		$temp_c6  			= $newArray['protokol_c6'];			$temp_c7  			= $newArray['protokol_c7'];
		$temp_c8  			= $newArray['protokol_c8'];			$temp_up			= $newArray['protokol_up'];
		$temp_nu 			= $newArray['protokol_nazwa_urzadzenia'];	$temp_upid			= $newArray['protokol_upid'];
		$temp_snu 			= $newArray['protokol_sn_urzadzenia'];
		$temp_niu 			= $newArray['protokol_ni_urzadzenia'];
		$temp_ou 			= $newArray['protokol_opis_uszkodzenia'];
		$temp_wc 			= $newArray['protokol_wykonane_czynnosci'];
		$temp_u 			= $newArray['protokol_uwagi'];		$temp_w 			= $newArray['protokol_wersja'];
		$temp_osoba			= $newArray['protokol_kto'];
		$temp_unique		= $newArray['protokol_unique'];
		$temp_source		= $newArray['protokol_source'];
		$temp_state			= $newArray['protokol_state'];
		$temp_hd_zgl_nr		= $newArray['protokol_hd_zgl_nr'];
		
		if ($temp_hd_zgl_nr=='0') $temp_hd_zgl_nr = '-';
		
		$hadim_nr = 0;
		if ($temp_hd_zgl_nr>0) {
			$result554 = mysql_query("SELECT zgl_poczta_nr FROM $dbname_hd.hd_zgloszenie WHERE (zgl_nr=$temp_hd_zgl_nr) LIMIT 1", $conn) or die($k_b);
			list($hadim_nr)=mysql_fetch_array($result554);
		}
			
		$numerpr			= substr($temp_nr,0,strpos($temp_nr,'/'));
		tbl_tr_highlight($i);
			td("30;c;<a href=# class=normalfont title='".$temp_id."'>".$j."</a>|60;c;".$temp_hd_zgl_nr."|;;".$temp_nr."|;;".$temp_d."/".$temp_m."/".$temp_r."|;;".$temp_nu."");
			
			td_(";;");
				echo "$temp_up";
				
				if ($temp_up=='') {
					$r40 = mysql_query("SELECT serwis_komorki.up_id, serwis_komorki.up_nazwa, serwis_piony.pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) and (serwis_komorki.up_id=$temp_upid) LIMIT 1", $conn) or die($k_b);
					list($temp_up_from_id,$temp_up_nazwa1,$temp_up_pion)=mysql_fetch_array($r40);
					echo "$temp_up_pion $temp_up_nazwa1";
				}
			_td();
			
			td(";;".$temp_osoba."");
			
			$j++;
			td_(";c");
				$accessLevels = array("9");
				if(array_search($es_prawa, $accessLevels)>-1) {
					echo "<a title=' Usuń protokół numer $temp_nr z listy '><input class=imgoption type=image src=img/delete.gif  onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_protokol.php?id=$temp_id')\"></a>";
				}
				//$temp_pnr = substr($temp_nr,0,strpos($temp_nr,'/'));
				$temp_pnr = $temp_nr;
				$setreadonly=1;
				if ($currentuser==$temp_osoba) $setreadonly=0;
	
				$upid = '';
				if ($temp_upid!='0') {
					$upid = $temp_upid;
				} else {
					if ($temp_up!='') {
						// z pełnej nazwy CP UP ......... wyciągnij up_id i przypisz do zmiennej $upid
						$SamoUP = substr($temp_up,strpos($temp_up,' ')+1,strlen($temp_up));
						//echo "$SamoUP";
						$sql21 = "SELECT up_id FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.up_nazwa = '$SamoUP') and (serwis_piony.pion_id=serwis_komorki.up_pion_id) and (belongs_to=$es_filia) LIMIT 1";
						$wynik21 = mysql_query($sql21,$conn);
						list($upid_temp) = mysql_fetch_array($wynik21);
						$upid = $upid_temp;
						//echo "$upid";
					}
				}
				
				$temp_pnr = substr($temp_pnr,0,strpos($temp_pnr,'/'));
				
				echo "<a href=# title=' Pokaż protokół nr $temp_nr ' onClick=\"newWindow_r(700,640,'pokaz_protokol_generuj.php?_view=1&_pnr=".urlencode($temp_pnr)."&_dzien=$temp_d&_miesiac=$temp_m&_rok=$temp_r&_c_1=$temp_c1&_c_2=$temp_c2&_c_3=$temp_c3&_c_4=$temp_c4&_c_5=$temp_c5&_c_6=$temp_c6&_c_7=$temp_c7&_c_8=$temp_c8&_up=".urlencode($temp_up)."&_nazwa_urzadzenia=".urlencode($temp_nu)."&_sn_urzadzenia=".urlencode($temp_snu)."&_ni_urzadzenia=".urlencode($temp_niu)."&_opis_uszkodzenia=".urlencode($temp_ou)."&_wykonane_czynnosci=".urlencode($temp_wc)."&_uwagi=".urlencode($temp_u)."&_imieinazwisko=".urlencode($temp_osoba)."&_readonly=$setreadonly&_wersjap=$temp_w&_submit=OK&_clear=0&unr=$temp_unique&_popraw=1&_state=$temp_state&_source=$temp_source&_wstecz=0&_fp=1&_odswiez=1&_akcja=update&_nzpdb=on&_new_upid=".$upid."&_hd_zgl_nr=$temp_hd_zgl_nr&hadim_nr=$hadim_nr'); return false; \"><img class=imgoption src=img//preview.gif border=0 width=16 width=16></a>";

				if ($temp_hd_zgl_nr!='-') {
					$LinkHDZglNr=$temp_hd_zgl_nr; include('linktohelpdesk.php');
				}
				
			_td();
		_tr();
	$i++;
}
endtable();
include_once('paging_end.php');

/*  startbuttonsarea("center");
	echo "<form name=protokoly action=$PHP_SELF method=GET>";
	echo "Pokaż: ";
		echo "<a class=paging href=$phpfile?showall=$showall&page=$page&paget=$paget&pokaz=all&okresm=$okresm&okresr=$okresr>Wszystkie</a>";
		
		echo " | ";
		echo "dla osoby: ";
		
		$result44 = mysql_query("SELECT user_first_name,user_last_name FROM $dbname.serwis_uzytkownicy WHERE (belongs_to=$es_filia) and (user_locked=0) ORDER BY user_last_name ASC", $conn) or die($k_b);
		echo "<select name=osoba  onChange='document.location.href=document.protokoly.osoba.options[document.protokoly.osoba.selectedIndex].value'/>\n";
		echo "<option value='$PHP_SELF?showall=$showall&okresm=$okresm&okresr=$okresr&osoba='>Wybierz z listy</option>\n";
		while (list($temp_imie,$temp_nazwisko) = mysql_fetch_array($result44)) {
			$imieinazwisko = $temp_imie . " " . $temp_nazwisko;
			echo "<option value='$PHP_SELF?showall=$showall&okresm=$okresm&okresr=$okresr&osoba=".urlencode($imieinazwisko)."' ";
			if ($_REQUEST[osoba]==$imieinazwisko) echo " SELECTED";
			echo ">$imieinazwisko</option>\n"; 
		}
		echo "</select>\n";
		
		echo "| z wybranego okresu: ";
		$miesiac = Date('m');
		echo "&nbsp;<select name=okresm1 onChange='document.location.href=document.protokoly.okresm1.options[document.protokoly.okresm1.selectedIndex].value'>";
		echo "<option value='$PHP_SELF?showall=$showall&okresm=01&okresr=$okresr&osoba=".urlencode($osoba)."'"; if (('01'==$okresm)) echo " SELECTED"; echo ">Styczeń</option>\n";
		echo "<option value='$PHP_SELF?showall=$showall&okresm=02&okresr=$okresr&osoba=".urlencode($osoba)."'"; if (('02'==$okresm)) echo " SELECTED"; echo ">Luty</option>\n";
		echo "<option value='$PHP_SELF?showall=$showall&okresm=03&okresr=$okresr&osoba=".urlencode($osoba)."'"; if (('03'==$okresm)) echo " SELECTED"; echo ">Marzec</option>\n";
		echo "<option value='$PHP_SELF?showall=$showall&okresm=04&okresr=$okresr&osoba=".urlencode($osoba)."'"; if (('04'==$okresm)) echo " SELECTED"; echo ">Kwiecień</option>\n";
		echo "<option value='$PHP_SELF?showall=$showall&okresm=05&okresr=$okresr&osoba=".urlencode($osoba)."'"; if (('05'==$okresm)) echo " SELECTED"; echo ">Maj</option>\n";
		echo "<option value='$PHP_SELF?showall=$showall&okresm=06&okresr=$okresr&osoba=".urlencode($osoba)."'"; if (('06'==$okresm)) echo " SELECTED"; echo ">Czerwiec</option>\n";
		echo "<option value='$PHP_SELF?showall=$showall&okresm=07&okresr=$okresr&osoba=".urlencode($osoba)."'"; if (('07'==$okresm)) echo " SELECTED"; echo ">Lipiec</option>\n";
		echo "<option value='$PHP_SELF?showall=$showall&okresm=08&okresr=$okresr&osoba=".urlencode($osoba)."'"; if (('08'==$okresm)) echo " SELECTED"; echo ">Sierpień</option>\n";
		echo "<option value='$PHP_SELF?showall=$showall&okresm=09&okresr=$okresr&osoba=".urlencode($osoba)."'"; if (('09'==$okresm)) echo " SELECTED"; echo ">Wrzesień</option>\n";
		echo "<option value='$PHP_SELF?showall=$showall&okresm=10&okresr=$okresr&osoba=".urlencode($osoba)."'"; if (('10'==$okresm)) echo " SELECTED"; echo ">Październik</option>\n";
		echo "<option value='$PHP_SELF?showall=$showall&okresm=11&okresr=$okresr&osoba=".urlencode($osoba)."'"; if (('11'==$okresm)) echo " SELECTED"; echo ">Listopad</option>\n";
		echo "<option value='$PHP_SELF?showall=$showall&okresm=12&okresr=$okresr&osoba=".urlencode($osoba)."'"; if (('12'==$okresm)) echo " SELECTED"; echo ">Grudzień</option>\n";
		echo "</select>";
		echo "&nbsp;<select name=okresr1 onChange='document.location.href=document.protokoly.okresr1.options[document.protokoly.okresr1.selectedIndex].value'>";
		$rok = Date('Y');
		for ($r=2008;$r<=$rok;$r++) { 
			echo "<option value='$PHP_SELF?showall=$showall&okresm=$okresm&okresr=$r&osoba=".urlencode($osoba)."'"; 
			if ($r==$okresr) echo " SELECTED";
			echo ">$r</option>\n";
		}
		echo "</select>";
		//echo "<input type=submit style='height:21px; font-size:10px; padding-bottom:1px;' value='Pokaż'>";
	echo "</form>";
	br();
	endbuttonsarea();	
*/

	startbuttonsarea("right");
	oddziel();
	echo "<span style='float:left'>";
	addbuttons("wstecz");	
	echo "</span>";

	if ($kierownik_nr==$es_nr) { $state=''; } else { $state='empty'; }
	if ($_REQUEST[ss]=='') addownlinkbutton("'Utwórz protokół'","Button1","button","newWindow_r(700,595,'utworz_protokol.php?state=$state&nowy=1')");
	//if ($count_rows==0) {
	//	addlinkbutton("'Pokaż wszystkie protokoły'","z_protokol.php?showall=$showall");	
	//}
	addbuttons("start");
	endbuttonsarea();
	
} else {
	if (!isset($okresm)) { errorheader("Lista protokołów jest pusta"); } else { errorheader("Brak protokołów z wybranego okresu (<b>$okresm/$okresr</b>)"); }
	
	startbuttonsarea("right");
	oddziel();
	echo "<span style='float:left'>";
	if ($kierownik_nr==$es_nr) { $state=''; } else { $state='empty'; }
	//addownlinkbutton("'Utwórz protokół'","Button1","button","newWindow_r(700,595,'utworz_protokol.php?state=$state&nowy=1')");
	if ($count_rows==0) {
	//	addlinkbutton("'Pokaż wszystkie protokoły'","z_protokol.php?showall=$showall");	
	addbuttons("wstecz");	
	}
	echo "</span>";
	addbuttons("start");
	endbuttonsarea();
	
}

include('body_stop.php'); 
?>

<script>HideWaitingMessage();</script>

</body>
</html>