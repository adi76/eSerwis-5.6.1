<?php
$czas=0;
$szerokosc = 140;

// ilość sprzętu po naprawie na stanie

if ($es_m==1) {
	$result = mysql_query("SELECT * FROM $dbname.serwis_naprawa WHERE (naprawa_status='3')", $conn) or die($k_b);
} else {
	$result = mysql_query("SELECT * FROM $dbname.serwis_naprawa WHERE ((naprawa_status='3')) and belongs_to=$es_filia", $conn) or die($k_b);
}
$naprawiony_sprzet = mysql_num_rows($result);

// ilosc otwartych zgłoszeń awarii
$result = mysql_query("SELECT awaria_id FROM $dbname.serwis_awarie WHERE awaria_status='0' and belongs_to=$es_filia", $conn) or die($k_b);
$awarie_otwarte_ilosc = mysql_num_rows($result);

// Czynności do wykonania
$accessLevels = array("9"); 
if(array_search($es_prawa, $accessLevels)>-1) {
	$sql = "SELECT todo_id FROM $dbname.serwis_komorka_todo WHERE belongs_to=$es_filia and todo_status=1  ORDER BY todo_termin_koncowy ASC";
	$result = mysql_query($sql, $conn) or die($k_b);
	$czynnosci_do_wykonania_ilosc = mysql_num_rows($result);
	
	$sql = "SELECT todo_id FROM $dbname.serwis_komorka_todo WHERE (belongs_to=$es_filia) and (todo_status=1) and ((todo_przypisane_osobie='$currentuser')) ORDER BY todo_termin_koncowy ASC";
	$result = mysql_query($sql, $conn) or die($k_b);
	$czynnosci_do_wykonania_ilosc_kierownik = mysql_num_rows($result);
	
} else {
	$sql = "SELECT todo_id FROM $dbname.serwis_komorka_todo WHERE (belongs_to=$es_filia) and (todo_status=1) and ((todo_przypisane_osobie='$currentuser') or (todo_przypisane_osobie='')) ORDER BY todo_termin_koncowy ASC";
	$result = mysql_query($sql, $conn) or die($k_b);
	$czynnosci_do_wykonania_ilosc = mysql_num_rows($result);
}

// Czynności do wykonania dla osoby
$accessLevels = array("9"); 
if(array_search($es_prawa, $accessLevels)>-1)
{
	$sql = "SELECT todo_id FROM $dbname.serwis_komorka_todo WHERE belongs_to=$es_filia and todo_status=1";
} else 
{
	$sql = "SELECT todo_id FROM $dbname.serwis_komorka_todo WHERE ((belongs_to=$es_filia) and (todo_status=1) and (todo_przypisane_osobie='$currentuser'))";
}

$result = mysql_query($sql, $conn) or die($k_b);
$czynnosci_do_wykonania_osoba_ilosc = mysql_num_rows($result);

// Ilość otwartych zadań
$result = mysql_query("SELECT zadanie_id FROM $dbname.serwis_zadania WHERE zadanie_status=1 and belongs_to=$es_filia", $conn) or die($k_b);
$zadania_otwarte_ilosc = mysql_num_rows($result);

if (($awarie_otwarte_ilosc>0) || ($czynnosci_do_wykonania_ilosc>0) || ($czynnosci_do_wykonania_ilosc_kierownik>0) || ($zadania_otwarte_ilosc>0)) {
$czas=1;
//br();
echo "<h5 style='font-size:13px;font-weight:normal;padding-top:4px;padding-bottom:4px;margin-top:0px;margin-bottom:5px;text-align:left;background:#AAD4FF;color:#313131;display: block; border: 1px solid #83BFFB;'>&nbsp;";

echo "<a href=# class=normalfont id=pokaz_zbiorcze style='display:none' type=button onClick=\"document.getElementById('praport').style.display=''; document.getElementById('pokaz_zbiorcze').style.display='none'; document.getElementById('ukryj_zbiorcze').style.display=''; createCookie('p_raport','TAK',365); location.reload(true); \"><img class=imgoption style='border:0px' type=image src=img/expand.gif width=11 height=11>&nbsp;Informacje zbiorcze z bazy eSerwis</a>";
echo "<a href=# class=normalfont id=ukryj_zbiorcze style='display:none' type=button onClick=\"document.getElementById('praport').style.display='none'; document.getElementById('pokaz_zbiorcze').style.display=''; document.getElementById('ukryj_zbiorcze').style.display='none'; createCookie('p_raport','NIE',365); location.reload(true);\"><img class=imgoption style='border:0px' type=image src=img/collapse.gif width=11 height=11>&nbsp;Informacje zbiorcze z bazy eSerwis</a>";

echo "</h5>";

echo "<div id=praport class=center>";

?>
<script>
if (readCookie('p_raport')=='TAK') {
	document.getElementById('pokaz_zbiorcze').style.display='none';
	document.getElementById('ukryj_zbiorcze').style.display='';
	document.getElementById('praport').style.display='';
} else {
	document.getElementById('pokaz_zbiorcze').style.display='';
	document.getElementById('ukryj_zbiorcze').style.display='none';
	document.getElementById('praport').style.display='none';
}
if (readCookie('p_raport')==null) {
	document.getElementById('pokaz_zbiorcze').style.display='none';
	document.getElementById('ukryj_zbiorcze').style.display='';
	document.getElementById('praport').style.display='';
	createCookie('p_raport','TAK',365);
}

</script>

<?php

if (($_COOKIE['p_raport']=='TAK') || ($_COOKIE['p_raport']=='null')) {

if ($awarie_otwarte_ilosc>0) {
	starttable();
	if ($pokaz_ikony==1) {
		th(";;<img src=img/wan_error.gif border=0 align=absmiddle width=16 height=16> Awarie|$szerokosc;c;Ilość",$es_prawa);
	} else {
		th(";;Awarie|$szerokosc;c;Ilość",$es_prawa);
	}
	tbl_tr_highlight(70);
		td_(";;");
			echo "<a title=' Ilość otwartych zgłoszeń awarii ' class=normalfont href=z_awarie.php>Ilość otwartych zgłoszeń awarii";
			if ($pokaz_ikony==1) echo "<img class=imgoption src=img/goto.gif border=0 align=absmiddle width=16 height=16>";
			echo "</a>";
			//echo "<a title='Pokaż zgłoszenia awarii' onclick=\"expand('SzczegolyFrame_startpage', 'SzczegolyTresc_startpage, 'SzczegolyImg_startpage', this, 'tr_startpage');\" href=\"z_awarie.php\" /><input class=imgoption id=SzczegolyImg_startpage type=image src=img/expand.gif></a>";
		_td();
		td_(";c;");
			echo "<a title=' Pokaż otwarte zgłoszenia ' class=normalfont href=z_awarie.php>$awarie_otwarte_ilosc</a>";
		_td();
	_tr();
/*		echo "<tr style='display:;' id=tr_startpage>";
		echo "<td colspan=2 style='border:none;padding:0px;height:0px;'>";
		echo "<iframe class=hidden id=SzczegolyFrame_startpage name=SzczegolyFrame_startpage style='width:100%'></iframe>";
		echo "<div class=hidden id=SzczegolyTresc_startpage></div>";
		echo "</td>";
		echo "</tr>";
*/
	endtable();
	echo "<hr />";
}

if ($czynnosci_do_wykonania_ilosc>0) {

if ($czynnosci_do_wykonania_osoba_ilosc>0) {
starttable();
$accessLevels = array("9"); 

if(array_search($es_prawa, $accessLevels)>-1) {
	if ($pokaz_ikony==1) {
		th(";l;<img src=img/group_go.gif border=0 align=absmiddle width=16 height=16> Wszystkie czynności do wykonania w filii / oddziale|$szerokosc;c;Do wykonania",$es_prawa);
	} else {
		th(";l;Wszystkie czynności do wykonania w filii / oddziale|$szerokosc;c;Do wykonania",$es_prawa);
	}
} else {
	if ($pokaz_ikony==1) {
		th(";l;<img src=img/user_go.gif border=0 align=absmiddle width=16 height=16> Czynności do wykonania przez ".$currentuser."|$szerokosc;c;Do wykonania",$es_prawa);
	} else {
		th(";l;Czynności do wykonania przez ".$currentuser."|$szerokosc;c;Do wykonania",$es_prawa);
	}
}

$accessLevels = array("9"); 
if(array_search($es_prawa, $accessLevels)>-1)
{
	$sql = "SELECT DISTINCT todo_up_id FROM $dbname.serwis_komorka_todo WHERE (todo_status=1) and (belongs_to=$es_filia) ORDER BY todo_termin_koncowy ASC";
} else 
{
	$sql = "SELECT DISTINCT todo_up_id FROM $dbname.serwis_komorka_todo WHERE ((todo_status=1) and (belongs_to=$es_filia) and (todo_przypisane_osobie='$currentuser')) ORDER BY todo_termin_koncowy ASC";
}

$a=3000;
$result = mysql_query($sql, $conn) or die($k_b);
while ($dane1 = mysql_fetch_array($result)) {

	$temp_id = $dane1['todo_up_id'];
	list($temp_termin)=mysql_fetch_array(mysql_query("SELECT todo_termin_koncowy FROM $dbname.serwis_komorka_todo WHERE (todo_up_id=$temp_id) and (todo_status=1) ORDER BY todo_termin_koncowy ASC LIMIT 1"));
		
	$accessLevels = array("9"); 
	if(array_search($es_prawa, $accessLevels)>-1)
	{
		$sql3 = "SELECT todo_up_id FROM $dbname.serwis_komorka_todo WHERE (todo_status=1) and (belongs_to=$es_filia) and (todo_up_id=$temp_id) ORDER BY todo_termin_koncowy ASC";
	} else 
	{
		$sql3 = "SELECT todo_up_id FROM $dbname.serwis_komorka_todo WHERE ((todo_status=1) and (belongs_to=$es_filia) and (todo_przypisane_osobie='$currentuser') and (todo_up_id=$temp_id)) ORDER BY todo_termin_koncowy ASC";
	}	

	$result3 = mysql_query($sql3, $conn) or die($k_b);
	$ilosc = mysql_num_rows($result3);
	
	$sql2 = "SELECT up_nazwa,up_pion_id FROM $dbname.serwis_komorki WHERE ((belongs_to=$es_filia) and (up_id='$temp_id')) LIMIT 1";
	$result2 = mysql_query($sql2, $conn) or die($k_b);
	
	$dane = mysql_fetch_array($result2);
	$up = $dane['up_nazwa'];
	$temp_pion_id = $dane['up_pion_id'];
	// nazwa pionu z id pionu
	$sql_get_pion = "SELECT pion_nazwa FROM $dbname.serwis_piony WHERE (pion_id='$temp_pion_id') LIMIT 1";
	$wynik_get_pion = mysql_query($sql_get_pion, $conn) or die($k_b);
	$dane_get_pion = mysql_fetch_array($wynik_get_pion);
	$temp_pion_nazwa = $dane_get_pion['pion_nazwa'];
	// koniec ustalania nazwy pionu
	$calakomorka = $temp_pion_nazwa." ".$up;
	$dzisiaj = Date("Y-m-d");
	if ($ilosc>0) {
		tbl_tr_highlight($a);
			td_(";;");
				
				echo "<a title=' Ilość czynności do wykonania dla $temp_pion_nazwa $up = $ilosc ' class=normalfont onclick=\"newWindow(800,400,'p_komorka_czynnosc.php?id=$temp_id&filtruj=".urlencode($currentuser)."&komorka=".urlencode($calakomorka)."')\" href='#'>$temp_pion_nazwa $up";
				if ($pokaz_ikony==1) echo "<img class=imgoption src=img/goto.gif border=0 align=absmiddle width=16 height=16>";
				$dddd = date("Y-m-d H:i:s");
				$sql4 = "SELECT todo_id FROM $dbname.serwis_komorka_todo WHERE ((belongs_to=$es_filia) and (todo_up_id=$temp_id) and (todo_status=1) and (todo_termin_koncowy<>'0000-00-00 00:00:00') and (todo_termin_koncowy<'$dddd'))";
				$result4 = mysql_query($sql4, $conn) or die($k_b);
				$ilosc_po_terminie = mysql_num_rows($result4);
				if ($ilosc_po_terminie>0) echo "&nbsp; ( dla $ilosc_po_terminie czynności upłynął termin wykonania )";
				echo "</a>";
				$pozostalo_dni = round( abs(strtotime($dzisiaj)-strtotime($temp_termin)) / 86400, 0 );
				if ($temp_termin!='0000-00-00') {
					if ($dzisiaj<$temp_termin) {
						if ($ilosc==1) {
							echo "<br /><font color=grey>planowany termin wykonania czynności upływa ".substr($temp_termin,0,10)." (za $pozostalo_dni dni)</font>";
						} else {
							echo "<br /><font color=grey>planowany termin wykonania najwcześniejszej czynności dla tej komórki upływa ".substr($temp_termin,0,10)." (za $pozostalo_dni dni)</font>";
						}
					} else {
						if ($ilosc==1) {
							echo "<br /><font color=#FA7C7C>termin wykonania czynności ";
							if ($pozostalo_dni!=0) { echo "upłynął ".substr($temp_termin,0,10)." ($pozostalo_dni dni temu)"; } else { echo "upływa dzisiaj !!!"; }
							echo "</font>";
						} else {
							echo "<br /><font color=#FA7C7C>termin wykonania najwcześniejszej czynności dla tej komórki ";
							if ($pozostalo_dni!=0) { echo "upłynął ".substr($temp_termin,0,10)." ($pozostalo_dni dni temu)"; } else { echo "upływa dzisiaj !!!"; }
							echo "</font>";
						}
					}
				} else echo "<br /><font color=grey>brak określonego terminu wykonania czynności</font>";					
				
			_td();
			td_(";c;");
				echo "<a title=' Pokaż czynności do wykonania w $up = $ilosc ' class=normalfont onclick=\"newWindow(800,400,'p_komorka_czynnosc.php?id=$temp_id&filtruj=".urlencode($currentuser)."&komorka=".urlencode($calakomorka)."')\" href='#'>$ilosc szt</a>";
			_td();
		_tr();
	$a++;
	}
}
endtable();
echo "<hr />";
}
// === czynności ogólne dla wszystkich

$accessLevels = array("0","1"); 
if(array_search($es_prawa, $accessLevels)>-1)
{
$sql = "SELECT DISTINCT todo_up_id FROM $dbname.serwis_komorka_todo WHERE ((todo_status=1) and (belongs_to=$es_filia)) ORDER BY todo_termin_koncowy ASC";

$result = mysql_query($sql, $conn) or die($k_b);
$ilosc = mysql_num_rows($result);

$c=2000;
if ($czynnosci_do_wykonania_ilosc>0) {
starttable();

th(";l;<img src=img/group_go.gif border=0 align=absmiddle width=16 height=16> Wszystkie czynności do wykonania w filii/oddziale |$szerokosc;c;&nbsp;",$es_prawa);

while ($dane1 = mysql_fetch_array($result)) {

	$temp_id = $dane1['todo_up_id'];
	list($temp_termin)=mysql_fetch_array(mysql_query("SELECT todo_termin_koncowy FROM $dbname.serwis_komorka_todo WHERE (todo_up_id=$temp_id) and (todo_status=1) ORDER BY todo_termin_koncowy ASC LIMIT 1"));

	$accessLevels = array("9"); 
	if(array_search($es_prawa, $accessLevels)>-1)
	{
		$sql3 = "SELECT todo_up_id FROM $dbname.serwis_komorka_todo WHERE (todo_status=1) and (belongs_to=$es_filia) and (todo_up_id=$temp_id) ORDER BY todo_termin_koncowy ASC";
	} else 
	{
		$sql3 = "SELECT todo_up_id FROM $dbname.serwis_komorka_todo WHERE ((todo_status=1) and (belongs_to=$es_filia) and (todo_up_id=$temp_id))  ORDER BY todo_termin_koncowy ASC";
	}
	$result3 = mysql_query($sql3, $conn) or die($k_b);
	$ilosc = mysql_num_rows($result3);

	$result2 = mysql_query("SELECT up_nazwa, up_pion_id FROM $dbname.serwis_komorki WHERE ((belongs_to=$es_filia) and (up_id='$temp_id')) LIMIT 1", $conn) or die($k_b);
	list($up, $temp_pion_id)=mysql_fetch_array($result2);
	
	// nazwa pionu z id pionu
	$sql_get_pion = "SELECT pion_nazwa FROM $dbname.serwis_piony WHERE (pion_id='$temp_pion_id') LIMIT 1";
	$wynik_get_pion = mysql_query($sql_get_pion, $conn) or die($k_b);
	$dane_get_pion = mysql_fetch_array($wynik_get_pion);
	$temp_pion_nazwa = $dane_get_pion['pion_nazwa'];
	// koniec ustalania nazwy pionu
	$calakomorka = $temp_pion_nazwa." ".$up;	
	
	if ($ilosc>0) {
		tbl_tr_highlight($c);
			td_(";l;");
				echo "<a title=' Ilość czynności do wykonania dla $temp_pion_nazwa $up = $ilosc ' class=normalfont onclick=\"newWindow(800,400,'p_komorka_czynnosc.php?id=$temp_id&filtruj=all&komorka=".urlencode($calakomorka)."')\" href='#'>$temp_pion_nazwa $up";
				if ($pokaz_ikony==1) echo "<img class=imgoption src=img/goto.gif border=0 align=absmiddle width=16 height=16>";
				$dddd = date("Y-m-d H:i:s");
				$sql4 = "SELECT todo_id FROM $dbname.serwis_komorka_todo WHERE ((belongs_to=$es_filia) and (todo_up_id=$temp_id) and (todo_status=1) and (todo_termin_koncowy<>'0000-00-00 00:00:00') and (todo_termin_koncowy<'$dddd'))";
				$result4 = mysql_query($sql4, $conn) or die($k_b);
				$ilosc_po_terminie = mysql_num_rows($result4);
				if ($ilosc_po_terminie>0) echo "&nbsp; ( dla $ilosc_po_terminie czynności upłynął termin wykonania )";
				echo "</a>";
				$dzisiaj = Date("Y-m-d");
				$pozostalo_dni = round( abs(strtotime($dzisiaj)-strtotime($temp_termin)) / 86400, 0 );
				if ($temp_termin!='0000-00-00') {
					if ($dzisiaj<$temp_termin) {
						if ($ilosc==1) {
							echo "<br /><font color=grey>planowany termin wykonania czynności upływa ".substr($temp_termin,0,10)." (za $pozostalo_dni dni)</font>";
						} else {
							echo "<br /><font color=grey>planowany termin wykonania najwcześniejszej czynności dla tej komórki upływa ".substr($temp_termin,0,10)." (za $pozostalo_dni dni)</font>";
						}
					} else {
						if ($ilosc==1) {
							echo "<br /><font color=#FA7C7C>termin wykonania czynności ";
							if ($pozostalo_dni!=0) { echo "upłynął ".substr($temp_termin,0,10)." ($pozostalo_dni dni temu)"; } else { echo "upływa dzisiaj !!!"; }
							echo "</font>";
						} else {
							echo "<br /><font color=#FA7C7C>termin wykonania najwcześniejszej czynności dla tej komórki ";
							if ($pozostalo_dni!=0) { echo "upłynął ".substr($temp_termin,0,10)." ($pozostalo_dni dni temu)"; } else { echo "upływa dzisiaj !!!"; }
							echo "</font>";
						}
					}
				} else echo "<br /><font color=grey>brak określonego terminu wykonania czynności</font>";	
				
			_td();
			td_(";c;");
				echo "<a title=' Pokaż czynności do wykonania w $up = $ilosc ' class=normalfont onclick=\"newWindow(800,400,'p_komorka_czynnosc.php?id=$temp_id&filtruj=all&komorka=".urlencode($calakomorka)."')\" href='#'>$ilosc szt</a>";
			_td();
		_tr();
		$c++;
	}
}

}
endtable();
}
}

if ($czynnosci_do_wykonania_ilosc_kierownik>0) {
starttable();
if ($pokaz_ikony==1) {
		th(";l;<img src=img/user_go.gif border=0 align=absmiddle width=16 height=16> Czynności do wykonania przez ".$currentuser."|$szerokosc;c;Do wykonania",$es_prawa);
	} else {
		th(";l;Czynności do wykonania przez ".$currentuser."|$szerokosc;c;Do wykonania",$es_prawa);
	}

$sql = "SELECT DISTINCT todo_up_id FROM $dbname.serwis_komorka_todo WHERE ((todo_status=1) and (belongs_to=$es_filia) and (todo_przypisane_osobie='$currentuser')) ORDER BY todo_termin_koncowy ASC";
$a=3000;
$result = mysql_query($sql, $conn) or die($k_b);
while ($dane1 = mysql_fetch_array($result)) {

	$temp_id = $dane1['todo_up_id'];
	list($temp_termin)=mysql_fetch_array(mysql_query("SELECT todo_termin_koncowy FROM $dbname.serwis_komorka_todo WHERE (todo_up_id=$temp_id) and (todo_status=1) ORDER BY todo_termin_koncowy ASC LIMIT 1"));
		
	$accessLevels = array("9"); 
	$sql3 = "SELECT todo_up_id FROM $dbname.serwis_komorka_todo WHERE ((todo_status=1) and (belongs_to=$es_filia) and (todo_przypisane_osobie='$currentuser') and (todo_up_id=$temp_id)) ORDER BY todo_termin_koncowy ASC";
	

	$result3 = mysql_query($sql3, $conn) or die($k_b);
	$ilosc = mysql_num_rows($result3);
	
	$sql2 = "SELECT up_nazwa,up_pion_id FROM $dbname.serwis_komorki WHERE ((belongs_to=$es_filia) and (up_id='$temp_id')) LIMIT 1";
	$result2 = mysql_query($sql2, $conn) or die($k_b);
	
	if (($kierownik_nr==$es_nr) || ($is_dyrektor==1)) { $kv = 1; } else { $kv = 0; }
	
	$dane = mysql_fetch_array($result2);
	$up = $dane['up_nazwa'];
	$temp_pion_id = $dane['up_pion_id'];
	// nazwa pionu z id pionu
	$sql_get_pion = "SELECT pion_nazwa FROM $dbname.serwis_piony WHERE (pion_id='$temp_pion_id') LIMIT 1";
	$wynik_get_pion = mysql_query($sql_get_pion, $conn) or die($k_b);
	$dane_get_pion = mysql_fetch_array($wynik_get_pion);
	$temp_pion_nazwa = $dane_get_pion['pion_nazwa'];
	// koniec ustalania nazwy pionu
	$calakomorka = $temp_pion_nazwa." ".$up;
	$dzisiaj = Date("Y-m-d");
	if ($ilosc>0) {
		tbl_tr_highlight($a);
			td_(";;");
				
				echo "<a title=' Ilość czynności do wykonania dla $temp_pion_nazwa $up = $ilosc ' class=normalfont onclick=\"newWindow(800,400,'p_komorka_czynnosc.php?id=$temp_id&filtruj=".urlencode($currentuser)."&komorka=".urlencode($calakomorka)."&kierownik=".$kv."')\" href='#'>$temp_pion_nazwa $up";
				if ($pokaz_ikony==1) echo "<img class=imgoption src=img/goto.gif border=0 align=absmiddle width=16 height=16>";
				$dddd = date("Y-m-d H:i:s");
				$sql4 = "SELECT todo_id FROM $dbname.serwis_komorka_todo WHERE ((belongs_to=$es_filia) and (todo_up_id=$temp_id) and (todo_status=1) and (todo_termin_koncowy<>'0000-00-00 00:00:00') and (todo_termin_koncowy<'$dddd'))";
				$result4 = mysql_query($sql4, $conn) or die($k_b);
				$ilosc_po_terminie = mysql_num_rows($result4);
				if ($ilosc_po_terminie>0) echo "&nbsp; ( dla $ilosc_po_terminie czynności upłynął termin wykonania )";
				echo "</a>";
				$pozostalo_dni = round( abs(strtotime($dzisiaj)-strtotime($temp_termin)) / 86400, 0 );
				if ($temp_termin!='0000-00-00') {
					if ($dzisiaj<$temp_termin) {
						if ($ilosc==1) {
							echo "<br /><font color=grey>planowany termin wykonania czynności upływa ".substr($temp_termin,0,10)." (za $pozostalo_dni dni)</font>";
						} else {
							echo "<br /><font color=grey>planowany termin wykonania najwcześniejszej czynności dla tej komórki upływa ".substr($temp_termin,0,10)." (za $pozostalo_dni dni)</font>";
						}
					} else {
						if ($ilosc==1) {
							echo "<br /><font color=#FA7C7C>termin wykonania czynności ";
							if ($pozostalo_dni!=0) { echo "upłynął ".substr($temp_termin,0,10)." ($pozostalo_dni dni temu)"; } else { echo "upływa dzisiaj !!!"; }
							echo "</font>";
						} else {
							echo "<br /><font color=#FA7C7C>termin wykonania najwcześniejszej czynności dla tej komórki ";
							if ($pozostalo_dni!=0) { echo "upłynął ".substr($temp_termin,0,10)." ($pozostalo_dni dni temu)"; } else { echo "upływa dzisiaj !!!"; }
							echo "</font>";
						}
					}
				} else echo "<br /><font color=grey>brak określonego terminu wykonania czynności</font>";					
				
			_td();
			td_(";c;");				
				echo "<a title=' Pokaż czynności do wykonania w $up = $ilosc ' class=normalfont onclick=\"newWindow(800,400,'p_komorka_czynnosc.php?id=$temp_id&filtruj=".urlencode($currentuser)."&komorka=".urlencode($calakomorka)."&kierownik=".$kv."')\" href='#'>$ilosc szt</a>";
			_td();
		_tr();
	$a++;
	}
}
endtable();
echo "<hr />";

}

if ($zadania_otwarte_ilosc>0) {
starttable();
$accessLevels = array("9");

if ($pokaz_ikony==1) {
	th(";l;<img src=img/cog_go.gif border=0 align=absmiddle width=16 height=16> Zadania do wykonania w filii/oddziale|$szerokosc;c;Wykonano",$es_prawa);
} else {
	th(";l;Zadania do wykonania w filii/oddziale|$szerokosc;c;Wykonano;",$es_prawa);
}

$sql = "SELECT * FROM $dbname.serwis_zadania WHERE (zadanie_status=1)";
if ($es_m!=1) $sql=$sql." and (belongs_to=$es_filia)";
$sql=$sql." ORDER BY zadanie_termin_zakonczenia ASC";
$result = mysql_query($sql, $conn) or die($k_b);
$k=1000;
while ($dane1 = mysql_fetch_array($result)) {
	$temp_id 		= $dane1['zadanie_id'];
	$temp_opis	 	= $dane1['zadanie_opis'];
	$temp_termin	= $dane1['zadanie_termin_zakonczenia'];
	$temp_priorytet	= $dane1['zadanie_priorytet'];
	$temp_uwagi		= $dane1['zadanie_uwagi'];
	
	$termin = substr($temp_termin,0,10);
	$dzisiaj = Date("Y-m-d");
	
	tbl_tr_highlight($k);
		td_(";l;");
			echo "<a title='Pokaż niezakończone pozycje zadania: $temp_opis' class=normalfont onclick=\"newWindow_r(".$_COOKIE[max_x].",".$_COOKIE[max_y].",'p_zadanie_pozycje.php?id=$temp_id&all=0&dlaosoby=&nadzien=')\" href='#'><b>$temp_opis</b>&nbsp;";
			if ($pokaz_ikony==1) echo "<a title=' Czytaj uwagi do zadania'><input class=imgoption type=image align=absmiddle src=img/comment.gif onclick=\"newWindow(480,265,'p_zadania_uwagi.php?id=$temp_id');\"></a>";
			if ($pokaz_ikony==1) echo "<img class=imgoption src=img/goto.gif border=0 align=absmiddle width=16 height=16>";
			echo "</a>";
			$pozostalo_dni = round( abs(strtotime($dzisiaj)-strtotime($termin)) / 86400, 0 );

			if ($termin!='0000-00-00') {
				if ($dzisiaj<$termin)
				{
					echo "<br /><font color=grey>termin zakończenia zadania upływa $termin (za $pozostalo_dni dni)</font>";
				} else 
				{
					
					echo "<br /><font color=#FA7C7C>termin wykonania zadania ";
					if ($pozostalo_dni!=0) { echo "upłynął $termin ($pozostalo_dni dni temu)"; } else { echo "upływa dzisiaj !!!"; }
					echo "</font>";
				}
			} else echo "<br /><font color=grey>brak określonego terminu zakończenia zadania</font>";	
		_td();
		$sql1="SELECT * FROM $dbname.serwis_zadania_pozycje WHERE (pozycja_zadanie_id=$temp_id)";
		$result1 = mysql_query($sql1, $conn) or die($k_b);
		$countall = mysql_num_rows($result1);
		
		$sql2="SELECT * FROM $dbname.serwis_zadania_pozycje WHERE (pozycja_zadanie_id=$temp_id) and (pozycja_status=9)";
		$result2 = mysql_query($sql2, $conn) or die($k_b);
		$countwyk = mysql_num_rows($result2);

		if ($countall>0) { $procent_ = ($countwyk/$countall)*100; } else $procent_=0;
		$procent = round_up($procent_, 2);
		
		td_(";c;");
			echo "<b><a title='Pokaż zakończone pozycje zadania: $temp_opis' class=normalfont onclick=\"newWindow_r(".$_COOKIE[max_x].",".$_COOKIE[max_y].",'p_zadanie_pozycje.php?id=$temp_id&all=9&dlaosoby=&nadzien=')\" href='#'>$procent%</a></b>";
		_td();
	_tr();
	$k++;
	}
  }
}
endtable();
}
echo "</div>";
?>
<script>

function _hide(i) {
	if (document.getElementById(i)) document.getElementById(i).style.display='none';
}

function _show(i) {
	if (document.getElementById(i)) document.getElementById(i).style.display='';
}

function turn_on() {
	_show('praport');
	_hide('pokaz_zbiorcze');
	_show('ukryj_zbiorcze');
	createCookie('p_raport','TAK',365); 
	_show('helpdesk_szcz');
	_hide('pokaz_hd');
	_show('ukryj_hd');
	createCookie('p_raport_hd','TAK',365); 
	refresh_hd(); 
	_show('div_pokaz_informacje_o_sprzecie_wycofanym');
	_hide('pokaz_sprzet_wycofany');
	_show('ukryj_sprzet_wycofany');
	createCookie('p_naprawy_wycofane_na_startowej','TAK',365); 
	_show('div_pokaz_informacje_o_sprzecie_do_oddania');
	_hide('pokaz_sprzet_do_oddania');
	_show('ukryj_sprzet_do_oddania');
	createCookie('p_naprawy_zakonczone_na_startowej','TAK',365); 
	_show('pokaz_inf_po_terminie');
	_hide('pokaz_po_terminie');
	_show('ukryj_po_terminie');
	createCookie('p_naprawy_po_terminie','TAK',365); 
	_show('notatki');
	_show('notes_buttons');
	_hide('pokaz_notatki');
	_show('ukryj_notatki');
	createCookie('p_notatki','TAK',365); 
	refresh_notatki1(); 
	_show('zadania_buttons');
	_show('zadania1');
	_hide('pokaz_zadania');
	_show('ukryj_zadania');
	createCookie('p_zadania','TAK',1); 
	location.reload(true);
	return false;
}

function turn_off() {
	_hide('praport');
	_show('pokaz_zbiorcze');
	_hide('ukryj_zbiorcze');
	createCookie('p_raport','NIE',365); 
	_hide('helpdesk_szcz');
	_show('pokaz_hd');
	_hide('ukryj_hd');
	createCookie('p_raport_hd','NIE',365); 
	refresh_hd(); 
	_hide('div_pokaz_informacje_o_sprzecie_wycofanym');
	_show('pokaz_sprzet_wycofany');
	_hide('ukryj_sprzet_wycofany');
	createCookie('p_naprawy_wycofane_na_startowej','NIE',365); 
	_hide('div_pokaz_informacje_o_sprzecie_do_oddania');
	_show('pokaz_sprzet_do_oddania');
	_hide('ukryj_sprzet_do_oddania');
	createCookie('p_naprawy_zakonczone_na_startowej','NIE',365); 
	_hide('pokaz_inf_po_terminie');
	_show('pokaz_po_terminie');
	_hide('ukryj_po_terminie');
	createCookie('p_naprawy_po_terminie','NIE',365); 
	_hide('notatki');
	_hide('notes_buttons');
	_show('pokaz_notatki');
	_hide('ukryj_notatki');
	createCookie('p_notatki','NIE',365); 
	refresh_notatki1(); 
	_hide('zadania_buttons');
	_hide('zadania1');
	_show('pokaz_zadania');
	_hide('ukryj_zadania');
	createCookie('p_zadania','NIE',1); 
	location.reload(true);
	return false;
}

</script>
<?php

//if (($_COOKIE['p_raport']=='TAK') || ($_COOKIE['p_raport']=='null')) {

echo "<input class=imgoption type=image src=img/obsluga_seryjna.png>";
echo "<input class=buttons type=button onClick=\"turn_on();\" value='Rozwiń wszystkie informacje'>";
echo "<input class=buttons type=button onClick=\"turn_off();\" value='Zwiń wszystkie informacje'>";

//} else {

//} 
/*
	
	if ($_COOKIE['p_raport']=='NIE') {
	echo "<input class=imgoption type=image src=img/obsluga_seryjna.png>";
	echo "<input class=buttons type=button onClick=\"document.getElementById('praport').style.display=''; document.getElementById('pokaz_zbiorcze').style.display='none'; document.getElementById('ukryj_zbiorcze').style.display=''; createCookie('p_raport','TAK',365); location.reload(true); document.getElementById('helpdesk_szcz').style.display=''; document.getElementById('pokaz_hd').style.display='none'; document.getElementById('ukryj_hd').style.display=''; createCookie('p_raport_hd','TAK',365); refresh_hd();document.getElementById('div_pokaz_informacje_o_sprzecie_do_oddania').style.display=''; document.getElementById('pokaz_sprzet_do_oddania').style.display='none'; document.getElementById('ukryj_sprzet_do_oddania').style.display=''; createCookie('p_naprawy_zakonczone_na_startowej','TAK',365); document.getElementById('pokaz_inf_po_terminie').style.display=''; document.getElementById('pokaz_po_terminie').style.display='none'; document.getElementById('ukryj_po_terminie').style.display=''; createCookie('p_naprawy_po_terminie','TAK',365); document.getElementById('notatki').style.display=''; document.getElementById('notes_buttons').style.display=''; document.getElementById('pokaz_notatki').style.display='none'; document.getElementById('ukryj_notatki').style.display=''; createCookie('p_notatki','TAK',365); refresh_notatki1();\" value='Rozwiń wszystkie informacje'>";
} else {
	echo "<input class=buttons type=button onClick=\"document.getElementById('praport').style.display='none'; document.getElementById('pokaz_zbiorcze').style.display=''; document.getElementById('ukryj_zbiorcze').style.display='none'; createCookie('p_raport','NIE',365); location.reload(true);document.getElementById('helpdesk_szcz').style.display='none'; document.getElementById('pokaz_hd').style.display=''; document.getElementById('ukryj_hd').style.display='none'; createCookie('p_raport_hd','NIE',365); refresh_hd(); document.getElementById('div_pokaz_informacje_o_sprzecie_do_oddania').style.display='none'; document.getElementById('pokaz_sprzet_do_oddania').style.display=''; document.getElementById('ukryj_sprzet_do_oddania').style.display='none'; createCookie('p_naprawy_zakonczone_na_startowej','NIE',365); document.getElementById('pokaz_inf_po_terminie').style.display='none'; document.getElementById('pokaz_po_terminie').style.display=''; document.getElementById('ukryj_po_terminie').style.display='none'; createCookie('p_naprawy_po_terminie','NIE',365); document.getElementById('notatki').style.display='none';document.getElementById('notes_buttons').style.display='none';  document.getElementById('pokaz_notatki').style.display=''; document.getElementById('ukryj_notatki').style.display='none'; createCookie('p_notatki','NIE',365); refresh_notatki1();\" value='Zwiń wszystkie informacje'>";

	}
echo "---";
*/


//}
	
echo "<div style='float:right'>";
echo "<input type=checkbox id=rp name=rp style='border:0px solid;' "; 
if ($_COOKIE[rp]=='TAK') echo " checked=checked ";
echo " onClick=\"if (this.checked==true) { eraseCookie('rp'); createCookie('rp','TAK',365); } else { eraseCookie('rp'); createCookie('rp','NIE',365); } \">&nbsp;<a href=# class=normalfont style='color:blue;' onClick=\"if (document.getElementById('rp').checked==true) { document.getElementById('rp').checked=false; eraseCookie('rp'); createCookie('rp','NIE',365); return false;} else { document.getElementById('rp').checked=true; eraseCookie('rp'); createCookie('rp','TAK',365); return false;} \">Odświeżaj stronę co 5 minut</a>&nbsp;&nbsp;";
echo "</div>";

?>