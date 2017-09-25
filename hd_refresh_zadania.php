<?php 

if ($_REQUEST[from]=='main_simple') {
	include_once('header.php');
} else {
	include_once('header_simple.php');
}

include_once('cfg_eserwis.php');
include_once('cfg_helpdesk.php');

//$r40 = mysql_query("SELECT filia_leader FROM $dbname.serwis_filie WHERE (filia_id='$es_filia') LIMIT 1", $conn) or die($k_b);
//list($KierownikId)=mysql_fetch_array($r40);
$KierownikId = $kierownik_nr;
$span_zadania_moje = "";

if ($_REQUEST[today]=='') {
	$result88 = mysql_query("SELECT * FROM $dbname.serwis_zadania_pozycje, $dbname.serwis_zadania WHERE (serwis_zadania.belongs_to=$es_filia) and (serwis_zadania_pozycje.pozycja_status<>9) and (pozycja_przypisane_osobie='$_REQUEST[osoba]') and (serwis_zadania_pozycje.pozycja_zadanie_id=serwis_zadania.zadanie_id) ORDER BY zadanie_opis, pozycja_id DESC", $conn);
	
	$result88a = mysql_query("SELECT * FROM $dbname.serwis_komorka_todo, $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorka_todo.todo_up_id=serwis_komorki.up_id) and (serwis_piony.pion_id=serwis_komorki.up_pion_id) and (serwis_komorka_todo.todo_status<>9)  and (serwis_komorka_todo.belongs_to='$es_filia') ORDER BY todo_termin_koncowy ASC, up_nazwa ASC", $conn);

} else {
	$result88 = mysql_query("SELECT * FROM $dbname.serwis_zadania_pozycje, $dbname.serwis_zadania WHERE (serwis_zadania.belongs_to=$es_filia) and (serwis_zadania_pozycje.pozycja_status<>9) and (pozycja_przypisane_osobie='$_REQUEST[osoba]') and (serwis_zadania_pozycje.pozycja_zadanie_id=serwis_zadania.zadanie_id) and (serwis_zadania_pozycje.pozycja_zaplanowana_data_wykonania='$_REQUEST[today]') ORDER BY zadanie_opis, pozycja_id DESC", $conn);
	
	$result88a = mysql_query("SELECT * FROM $dbname.serwis_komorka_todo, $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorka_todo.todo_up_id=serwis_komorki.up_id) and (serwis_piony.pion_id=serwis_komorki.up_pion_id) and (serwis_komorka_todo.todo_status<>9) and (todo_przypisane_osobie='$_REQUEST[osoba]') and (serwis_komorka_todo.belongs_to='$es_filia') and (serwis_komorka_todo.todo_termin_koncowy='$_REQUEST[today] 00:00:00') ORDER BY todo_termin_koncowy ASC, up_nazwa ASC", $conn);	
	
}
//echo "SELECT up_nazwa, pion_nazwa, todo_opis, todo_termin_koncowy FROM $dbname.serwis_komorka_todo, $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorka_todo.todo_up_id=serwis_komorki.up_id) and (serwis_piony.pion_id=serwis_komorki.up_pion_id) and (serwis_komorka_todo.todo_status<>9) and (todo_przypisane_osobie='$_REQUEST[osoba]') ORDER BY todo_termin_koncowy ASC, up_nazwa ASC";

$count_rows = mysql_num_rows($result88);
$count_rows_a = mysql_num_rows($result88a);
$count_rows+=$count_rows_a;

$czyistnieje = mysql_query("SELECT * FROM $dbname.serwis_todo_$es_filia LIMIT 1", $conn);
if ($czyistnieje) {	
	$sql_report = "TRUNCATE TABLE $dbname.serwis_todo_$es_filia";	
	$result_report = mysql_query($sql_report, $conn) or die($k_b);
} else { 
	$sql_report = "CREATE TABLE $dbname.`serwis_todo_$es_filia` (
				`todo_komorka` varchar(150) collate utf8_polish_ci NOT NULL,
				`todo_temat` varchar(120) collate utf8_polish_ci NOT NULL,
				`todo_ustalona_data` varchar(10) collate utf8_polish_ci NOT NULL,
				`todo_ustalona_osoba` varchar(50) collate utf8_polish_ci NOT NULL,
				`todo_cid` varchar(10)  collate utf8_polish_ci NOT NULL,
				`todo_zid` varchar(10)  collate utf8_polish_ci NOT NULL,
				`todo_poz_zid` varchar(10)  collate utf8_polish_ci NOT NULL,
				`todo_type` varchar(1)  collate utf8_polish_ci NOT NULL,
				
				`todo_zgl_kat_nr` varchar(3) collate utf8_polish_ci NOT NULL,
				`todo_zgl_kat_opis` varchar(50) collate utf8_polish_ci NOT NULL,
				`todo_zgl_podkat_nr` varchar(3) collate utf8_polish_ci NOT NULL,
				`todo_zgl_podkat_opis` varchar(50) collate utf8_polish_ci NOT NULL,
				`todo_zgl_podkat2` varchar(50) collate utf8_polish_ci NOT NULL,
				`todo_zgl_osoba` varchar(50) collate utf8_polish_ci NOT NULL,
				`todo_uwagi` text collate utf8_polish_ci NOT NULL,
				
				`belongs_to` varchar (2) COLLATE utf8_polish_ci NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci";

				$result_report = mysql_query($sql_report, $conn_hd) or die($k_b);	
}

while ($dane88 = mysql_fetch_array($result88)) {
		$temp_id			= $dane88['pozycja_id'];
		$temp_uwagi			= $dane88['pozycja_uwagi'];
		$temp_komorka		= $dane88['pozycja_komorka'];
		$temp_przyp_osobie 	= $dane88['pozycja_przypisane_osobie'];
		$n_zgl_id			= $dane88['pozycja_hd_zgloszenie'];
		$temp_z_id 			= $dane88['pozycja_zadanie_id'];
		$temp_data_zapl		= $dane88['pozycja_zaplanowana_data_wykonania'];
		$temp_zadanie_id 	= $dane88['zadanie_id'];
		$temp_zadanie_opis 	= $dane88['zadanie_opis'];
		$temp_zadanie_tz	= $dane88['zadanie_termin_zakonczenia'];
		$temp_wc			= $dane88['zadanie_hd_wc'];
		$temp_osoba			= $dane88['zadanie_hd_osoba'];

		
		$result9 = mysql_query("SELECT up_id, up_pion_id, up_komorka_macierzysta_id FROM $dbname.serwis_komorki WHERE (up_nazwa='$temp_komorka') and (belongs_to=$es_filia) LIMIT 1", $conn) or die($k_b);
		list($up_id,$temp_pion_id,$temp_komorka_macierzysta)=mysql_fetch_array($result9);

		// nazwa pionu z id pionu
		$sql_get_pion = "SELECT pion_nazwa FROM $dbname.serwis_piony WHERE (pion_id='$temp_pion_id') LIMIT 1";
		$wynik_get_pion = mysql_query($sql_get_pion, $conn) or die($k_b);
		$dane_get_pion = mysql_fetch_array($wynik_get_pion);
		$temp_pion_nazwa = $dane_get_pion['pion_nazwa'];
		
		
		
		$r2 = mysql_query("SELECT zadanie_hd_podkat,zadanie_hd_wc,zadanie_hd_osoba,zadanie_hd_kat,zadanie_hd_podkat_poziom_2 FROM $dbname.serwis_zadania WHERE (zadanie_id='$temp_zadanie_id') LIMIT 1", $conn_hd) or die($k_b);
		list($_podkat, $_wc, $_osoba, $_kat, $_podkat2)=mysql_fetch_array($r2);

		$r2 = mysql_query("SELECT hd_podkategoria_opis FROM $dbname_hd.hd_podkategoria WHERE (hd_podkategoria_nr='$_podkat') LIMIT 1", $conn_hd) or die($k_b);
		list($_podkat_opis)=mysql_fetch_array($r2);

		$r2 = mysql_query("SELECT hd_kategoria_opis FROM $dbname_hd.hd_kategoria WHERE (hd_kategoria_nr='$_kat') LIMIT 1", $conn_hd) or die($k_b);
		list($_kat_opis)=mysql_fetch_array($r2);

//	echo $temp_pion_nazwa." ".$temp_komorka.", ".$temp_zadanie_opis.", ".$temp_data_zapl.", ".$temp_przyp_osobie.", ".$temp_zadanie_id.", ".$temp_id.", Z, ".$es_filia."<br />";
	
	
		$insert_zadanie = mysql_query("INSERT INTO $dbname.serwis_todo_$es_filia VALUES ('$temp_pion_nazwa $temp_komorka','$temp_zadanie_opis','$temp_data_zapl','$temp_przyp_osobie','0','$temp_zadanie_id','$temp_id','Z','$_kat','$_kat_opis','$_podkat','$_podkat_opis','$_podkat2','$_osoba','$temp_uwagi','$es_filia')");

	
}
// up_nazwa, pion_nazwa, todo_opis, todo_termin_koncowy
while ($dane88 = mysql_fetch_array($result88a)) {
	$temp_id			= $dane88['pozycja_id'];
	$temp_komorka		= $dane88['pion_nazwa']." ".$dane88['up_nazwa'];
	$temp_uwagi			= $dane88['todo_uwagi'];
	$temp_opis			= $dane88['todo_opis'];
	$temp_przyp_os		= $dane88['todo_przypisane_osobie'];
	$temp_data_zapl		= $dane88['todo_termin_koncowy'];
	$temp_cid			= $dane88['todo_id'];
	
	//echo ($i++)." ".$temp_komorka.", ".$temp_opis.", ".$temp_data_zapl.", ".$temp_przyp_os.", ".$temp_cid.", C, ".$es_filia."<br />";
	$insert_czynnosc = mysql_query("INSERT INTO $dbname.serwis_todo_$es_filia VALUES ('$temp_komorka','$temp_opis','$temp_data_zapl','$temp_przyp_os','$temp_cid','0','0','C','','','','','','','$temp_uwagi','$es_filia')");
}

$t = 0;
$dddd=Date('Y-m-d');
	
if ($_REQUEST[from]=='main_simple') {
	pageheader('Zadania i czynności zaplanowane dla <b>'.$_REQUEST[osoba].'</b> na dzień <b>'.$_REQUEST[today].'</b>',0,0);
}

$result88 = mysql_query("SELECT * FROM $dbname.serwis_todo_$es_filia WHERE (belongs_to=$es_filia) and (todo_ustalona_osoba='$_REQUEST[osoba]') ORDER BY todo_ustalona_data DESC, todo_komorka ASC", $conn);

if ($count_rows>0) {
	$span_zadania_moje .= '<table align=center class=maxwidth>';
	$o = 0;
	$span_zadania_moje.= "<tr><th class=center width=20>LP</th><th>Zadanie i czynności do wykonania</th><th>UP / Komórka<br />UWAGI</th><th class=center width=150>Zaplanowana data<br/>wykonania</th><th class=center>Opcje</th></tr>"; 
	$h = 1;
	while ($dane88 = mysql_fetch_array($result88)) {
		$temp_id			= $dane88['todo_poz_zid'];
		$temp_komorka		= $dane88['todo_komorka'];
		$temp_status		= 1;		
		$temp_przyp_osobie 	= $dane88['todo_ustalona_osoba']; 
		$n_zgl_id			= ''; //$dane88['pozycja_hd_zgloszenie'];
		$temp_z_id 			= $dane88['todo_zid'];
		$temp_c_id 			= $dane88['todo_cid'];
		$temp_data_zapl		= $dane88['todo_ustalona_data'];
		
		$temp_uwagi			= $dane88['todo_uwagi'];
		
		$temp_typ 			= $dane88['todo_type'];
		$temp_zadanie_id 	= $dane88['todo_zid'];
		$temp_zadanie_opis 	= $dane88['todo_temat']; //$dane88['zadanie_opis'];
		$temp_zadanie_tz	= ''; //$dane88['zadanie_termin_zakonczenia'];
		
		$temp_wc			= '-'; //$dane88['zadanie_hd_wc'];
		$temp_osoba			= ''; //$dane88['zadanie_hd_osoba'];

		$enableHD = 1;
		//if (($temp_wc == '') || ($temp_osoba == '')) $enableHD = 0;
		
		$temp_termin	= $dane88['zadanie_termin_zakonczenia'];
		$r2 = mysql_query("SELECT zadanie_termin_zakonczenia FROM $dbname.serwis_zadania WHERE (zadanie_id='$temp_zadanie_id') LIMIT 1", $conn) or die($k_b);
		list($temp_termin)=mysql_fetch_array($r2);

		$termin = substr($temp_termin,0,10);
		$dzisiaj = Date("Y-m-d");
		$pozostalo_dni = round( abs(strtotime($dzisiaj)-strtotime($termin)) / 86400, 0 );
		
		$r4 = mysql_query("SELECT up_id, up_pion_id, up_komorka_macierzysta_id  FROM $dbname.serwis_komorki,$dbname.serwis_piony WHERE ((belongs_to=$es_filia) and (up_active=1) and (CONCAT(pion_nazwa,' ',up_nazwa) =  '$temp_komorka')) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) LIMIT 1", $conn) or die($k_b);
		list($up_id,$temp_pion_id,$temp_komorka_macierzysta)=mysql_fetch_array($r4);

		// nazwa pionu z id pionu
		$sql_get_pion = "SELECT pion_nazwa FROM $dbname.serwis_piony WHERE (pion_id='$temp_pion_id') LIMIT 1";
		$wynik_get_pion = mysql_query($sql_get_pion, $conn) or die($k_b);
		$dane_get_pion = mysql_fetch_array($wynik_get_pion);
		$temp_pion_nazwa = $dane_get_pion['pion_nazwa'];
		// koniec ustalania nazwy pionu
			
		$span_zadania_moje.= "<tr "; 
		
		if ($o % 2 != 0 ) $span_zadania_moje.=" class=nieparzyste ";
		$span_zadania_moje.=" class=parzyste ";
			
		$span_zadania_moje.=" id=tr_zadania_$t style='margin=0px; padding:0px;'>";
		
		$span_zadania_moje.="<td class=center>".$h."</td><td>"; $h++;
		
		if ($temp_typ=='Z') {
			$span_zadania_moje.= "<a title='Pokaż komórki przypisane do zadania $temp_zadanie_opis' class=normalfont onclick=\"newWindow_r(".$_COOKIE[max_x].",".$_COOKIE[max_y].",'p_zadanie_pozycje.php?id=$temp_zadanie_id&all=0&dlaosoby=&nadzien=')\" href='#'>";
		} else {
			$span_zadania_moje.= "<a>";
		//	$span_zadania_moje.= "<a title='Pokaż czynności' class=normalfont onclick=\"newWindow_r(".$_COOKIE[max_x].",".$_COOKIE[max_y].",'p_komorka_czynnosc..php?id=$temp_zadanie_id&all=0&dlaosoby=&nadzien=')\" href='#'>";
		}
	
		if (($temp_data_zapl<Date('Y-m-d')) && ($temp_data_zapl!='0000-00-00')) $span_zadania_moje.="<font color=red><b>";
		$span_zadania_moje.= "[$temp_typ] $temp_zadanie_opis";
		if (($temp_data_zapl<Date('Y-m-d')) && ($temp_data_zapl!='0000-00-00')) $span_zadania_moje.="</b></font>";
		$span_zadania_moje.= "</a>";

		if ($temp_typ=='Z') {
			if (($termin!='0000-00-00')) {
					if ($dzisiaj<$termin) {
						$span_zadania_moje.="<br /><font color=grey>termin zakończenia zadania upływa $termin (za $pozostalo_dni dni)</font>";
					} else { 
						$span_zadania_moje.= "<br /><font color=#FA7C7C>termin wykonania zadania ";
						if ($pozostalo_dni!=0) { $span_zadania_moje.= "upłynął $termin ($pozostalo_dni dni temu)"; } else { $span_zadania_moje.= "upływa dzisiaj !!!"; }
						$span_zadania_moje.= "</font>";
					}
			} else $span_zadania_moje.= "<br /><font color=grey>brak określonego terminu zakończenia zadania</font>";	
		}
		
		if ($temp_typ=='C') {
			$pozostalo_dni = round( abs(strtotime($dzisiaj)-strtotime($temp_data_zapl)) / 86400, 0 );
			if (($temp_data_zapl!='0000-00-00')) {
					if ($dzisiaj<$temp_data_zapl) {
						$span_zadania_moje.="<br /><font color=grey>termin wykonania czynności upływa $temp_data_zapl (za $pozostalo_dni dni)</font>";
					} else { 
						$span_zadania_moje.= "<br /><font color=#FA7C7C>termin wykonania czynności ";
						if ($pozostalo_dni!=0) { $span_zadania_moje.= "upłynął $temp_data_zapl ($pozostalo_dni dni temu)"; } else { $span_zadania_moje.= "upływa dzisiaj !!!"; }
						$span_zadania_moje.= "</font>";
					}
			} else $span_zadania_moje.= "<br /><font color=grey>brak określonego terminu wykonania czynności</font>";	
		}
		
		$span_zadania_moje.="</td>";
		
		$span_zadania_moje.="<td>";
		
		$span_zadania_moje.= "<a class=normalfont title=' Szczegółowe informacje o $temp_komorka ' onclick=\"newWindow_r(700,600,'p_komorka_szczegoly.php?id=$up_id'); return false;\" href=#>";
		if (($temp_data_zapl<Date('Y-m-d')) && ($temp_data_zapl!='0000-00-00')) $span_zadania_moje.="<font color=red><b>";
		$span_zadania_moje.= "$temp_komorka";
		if (($temp_data_zapl<Date('Y-m-d')) && ($temp_data_zapl!='0000-00-00')) $span_zadania_moje.="</b></font>";		
		$span_zadania_moje.= "</a>";		
		
		if ($pokazpoczatekuwag==1) {
		
			if (strlen($temp_uwagi)>0) {
			$span_zadania_moje.="<br />";
			$uwagi123=br2nl($temp_uwagi);
			if (strlen($temp_uwagi)>$iloscznakowuwag) $uwagi123 = (substr($temp_uwagi,0,$iloscznakowuwag).'...');	
			$span_zadania_moje.="<a title=' Pokaż uwagi w nowym oknie ' href=# style='color:#3C3C3C; text-decoration:none;' onClick=\"newWindow(480,265,'p_zadania_pozycje_uwagi.php?id=$temp_id&nr=$temp_numer'); return false;\">";
			$span_zadania_moje.="<sub style='font-size:11px; color:grey;'><u>UWAGI:</u> $uwagi123</sub>";
			$span_zadania_moje.="</a>";
		}
		
			//$span_zadania_moje.= "".pokaz_uwagi($temp_uwagi,$iloscznakowuwag,"newWindow(480,265,'p_zadania_pozycje_uwagi.php?id=$temp_id&nr=$temp_numer'); return false;")."";
		}
		
		$span_zadania_moje.="</td>";
		$span_zadania_moje.="<td class=center>";
		
		if ($temp_data_zapl!='0000-00-00') {
		
			if (($temp_data_zapl<Date('Y-m-d')) && ($temp_data_zapl!='0000-00-00') && ($temp_status!=9)) $span_zadania_moje.="<font color=red><b>";		
			$span_zadania_moje.="$temp_data_zapl";
			if (($temp_data_zapl<Date('Y-m-d')) && ($temp_data_zapl!='0000-00-00') && ($temp_status!=9)) $span_zadania_moje.="</b></font>";				
			
			if ($temp_data_zapl<Date('Y-m-d') && ($temp_status!=9)) $span_zadania_moje.="<br /><font color=red><b>minął termin wykonania</b></font>";
			if (($temp_data_zapl==Date('Y-m-d')) && ($_REQUEST[today]=='') && ($temp_status!=9)) $span_zadania_moje.="<br /><font color=#FF5500><b>termin upływa dzisiaj</b></font>";
			
		} else { 
			$span_zadania_moje.="-";
		}
		
		$span_zadania_moje.="</td>";		
		$span_zadania_moje.="<td class=center>";
		
				$accessLevels = array("9");
				if(array_search($es_prawa, $accessLevels)>-1) {
					if ($temp_status!=9) {
						if ($temp_typ=='Z') { 
							$span_zadania_moje.="<a title=' Przypisz wykonanie zadania w $temp_komorka innej osobie '><input class=imgoption type=image src=img/user_edit.gif onclick=\"newWindow(600,400,'e_zadanie_osoba.php?id=$temp_id&osoba=".urlencode($temp_przyp_osobie)."&dosiebie=0'); return false;\"></a>";
						} else {
							$span_zadania_moje.="<a title=' Przypisz wykonanie czynności innej osobie '><input class=imgoption type=image src=img/user_edit.gif onclick=\"newWindow(600,400,'e_komorka_czynnosc_osoba.php?id=$temp_c_id&osoba=".urlencode($temp_przyp_osobie)."&dosiebie=0'); return false;\"></a>";
						}
					}
				} else {
					// przypisanie do siebie
					if (($temp_status!=9) && ($temp_przyp_osobie!=$currentuser)) {
						if ($temp_typ=='Z') { 
							$span_zadania_moje.="<a title=' Przypisz wykonanie zadania w $temp_komorka innej osobie '><input class=imgoption type=image src=img/user_edit.gif onclick=\"newWindow(600,400,'e_zadanie_osoba.php?id=$temp_id&osoba=".urlencode($temp_przyp_osobie)."&dosiebie=".urlencode($currentuser)."'); return false;\"></a>";
						} else {
							$span_zadania_moje.="<a title=' Przypisz wykonanie czynności innej osobie '><input class=imgoption type=image src=img/user_edit.gif onclick=\"newWindow(600,400,'e_komorka_czynnosc_osoba.php?id=$temp_c_id&osoba=".urlencode($temp_przyp_osobie)."&dosiebie=0'); return false;\"></a>";
						}
					}
				}
				
			if ($temp_typ=='Z') {
				if ($temp_status<=1) {
					if (($currentuser==$temp_przyp_osobie) || ($temp_przyp_osobie=='') || ($kierownik==true) || ($es_m==1)) {
						if ($temp_status_z!=-1)	{
						
							$r2 = mysql_query("SELECT zadanie_hd_podkat,zadanie_hd_wc,zadanie_hd_osoba,zadanie_hd_kat,zadanie_hd_podkat_poziom_2 FROM $dbname.serwis_zadania WHERE (zadanie_id='$temp_zadanie_id') LIMIT 1", $conn_hd) or die($k_b);
							list($_podkat, $_wc, $_osoba, $_kat, $_podkat2)=mysql_fetch_array($r2);

							$r2 = mysql_query("SELECT hd_podkategoria_opis FROM $dbname_hd.hd_podkategoria WHERE (hd_podkategoria_nr='$_podkat') LIMIT 1", $conn_hd) or die($k_b);
							list($_podkat_opis)=mysql_fetch_array($r2);
							
							$r2 = mysql_query("SELECT hd_kategoria_opis FROM $dbname_hd.hd_kategoria WHERE (hd_kategoria_nr='$_kat') LIMIT 1", $conn_hd) or die($k_b);
							list($_kat_opis)=mysql_fetch_array($r2);
							
							$span_zadania_moje.="<a title=' Potwierdź wykonanie zadania w $temp_komorka '><input class=imgoption type=image src=img/snapraw_ok.gif onclick=\"newWindow_r(600,600,'e_zadanie_pozycja.php?id=$temp_id&k=".urlencode($temp_komorka)."&komorka=".urlencode($temp_komorka)."&zadanie=".urlencode($temp_opis)."&zid=$temp_z_id&zpodkatnr=$_podkat&zpodkatopis=".urlencode($_podkat_opis)."&osoba=".urlencode($_osoba)."&enablehd=$enableHD&zkatnr=$_kat&zkatopis=".urlencode($_kat_opis)."&zpodkat2=".urlencode($_podkat2)."'); return false;\"></a>";
						}
					}
				}
			}

			if ($temp_typ=='C') {
				$span_zadania_moje.="<a title=' Potwierdź wykonanie czynności '><input class=imgoption type=image src=img/snapraw_ok.gif onclick=\"newWindow_r(600,350,'e_komorka_czynnosc.php?id=$temp_c_id&komorka=".urlencode($temp_komorka)."&czynnosc=".urlencode($temp_zadanie_opis)."'); return false;\"></a>";
			}
			
				if ($temp_status==9) {
					if (($temp_mod_przez==$currentuser) || ($kierownik==true) || ($es_m==1)) {
							$span_zadania_moje.="<a title=' Anuluj wykonanie pozycji zadania w $temp_komorka '><input class=imgoption type=image src=img/cofnij_wykonanie.gif onclick=\"newWindow_r(600,150,'e_zadanie_pozycja_anuluj.php?id=$temp_id&k=".urlencode($temp_komorka)."&komorka=".urlencode($temp_pion_nazwa." ".$temp_komorka)."&zadanie=".urlencode($temp_opis)."&zid=$temp_z_id&zpodkatnr=$_podkat&zpodkatopis=".urlencode($_podkat_opis)."&osoba=".urlencode($_osoba)."'); return false;\"></a>";
					}
				}

				if(array_search($es_prawa, $accessLevels)>-1) { 
					if ($temp_typ=='Z') {
						$span_zadania_moje.="<a title=' Usuń $temp_komorka z listy UP/komórek do wykonania tego zadania '><input class=imgoption type=image src=img/delete.gif  onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_zadanie_pozycja.php?id=$temp_id&k=".urlencode($temp_komorka)."'); return false;\"></a>";	
					} else { 
						$span_zadania_moje.="<a title=' Usuń czynność do wykonania z $temp_komorka '><input class=imgoption type=image src=img/delete.gif  onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_komorka_czynnosc.php?id=$temp_c_id&k=".urlencode($temp_komorka)."'); return false;\"></a>";
					}
				}
				
				if (($n_zgl_id>0) && ($temp_status==0)) {
					$span_zadania_moje.="<hr />";
					$span_zadania_moje.="Wykonanie tej pozycji zadania<br />jest już powiązane<br />ze zgłoszeniem Helpdesk<br />";
				}
				
				if ($n_zgl_id>0) {
					$span_zadania_moje.="<a title='Powiązanie ze zgłszeniem o numerze ".$n_zgl_id." w bazie Helpdesk'><input class=imgoption type=image src=img/naprawa_powiazana_z_hd.gif onclick=\"if (opener) opener.location.href='hd_p_zgloszenia.php?sr=search-wyniki&search_zgl_nr=".$n_zgl_id."'; return false;\"></a>";

					$r2 = mysql_query("SELECT zgl_status FROM $dbname_hd.hd_zgloszenie WHERE (zgl_nr='$n_zgl_id') LIMIT 1", $conn_hd) or die($k_b);
					list($status_id)=mysql_fetch_array($r2);
					
					$span_zadania_moje.="<a class='normalfont' href=# onclick=\"newWindow_r(800,600,'hd_o_zgloszenia.php?action=obsluga&id=$n_zgl_id&nr=$n_zgl_id&zgl_s='); return false;\">";
					
						switch ($status_id) {
						case "1"	: $span_zadania_moje.= "<input title='Nowe' class=imgoption type=image src=img/zgl_nowe.gif>"; break;
						case "2"	: $span_zadania_moje.= "<input title='Przypisane' class=imgoption type=image src=img/zgl_przypisane.gif>"; break;
						case "3"	: $span_zadania_moje.= "<input title='Rozpoczęte' class=imgoption type=image src=img/zgl_rozpoczete.gif>"; break;
						case "3A"	: $span_zadania_moje.= "<input title='W serwisie zewnętrznym' class=imgoption type=image src=img/zgl_w_serwisie_zewnetrznym.gif>"; break;
						case "3B"	: $span_zadania_moje.= "<input title='W firmie' class=imgoption type=image src=img/zgl_w_firmie.gif>"; break;
						case "4"	: $span_zadania_moje.= "<input title='Oczekiwanie na odp. klienta' class=imgoption type=image src=img/zgl_oczekiwanie_na_odpowiedz.gif>"; break;
						case "5"	: $span_zadania_moje.= "<input title='Oczekiwanie na sprzęt' class=imgoption type=image src=img/zgl_oczekiwanie_na_sprzet.gif>"; break;
						case "6"	: $span_zadania_moje.= "<input title='Do Oddania' class=imgoption type=image src=img/zgl_do_oddania.gif>"; break;
						case "7"	: $span_zadania_moje.= "<input title='Rozpoczęte - nie zakończone' class=imgoption type=image src=img/zgl_rozpoczete_nie_zakonczone.gif>"; break;
							//case "8"	: $span_zadania_moje.= "<input class=imgoption type=image src=img/zgl_nowe.gif>"; break;
						case "9"	: $span_zadania_moje.= "<input title='Zamknięte' class=imgoption type=image src=img/zgl_zamkniete.gif>"; break;
						}
						
					$span_zadania_moje.= "</a>";
				
				}
				
		$span_zadania_moje.="</td>";

		$span_zadania_moje.= "</tr>";
		$t+=1;
		$o++;
	}
$span_zadania_moje.= "</table><br />";
} else {
	$span_zadania_moje.= "<h2>Brak zadań zaplanowanych dla Ciebie do wykonania na dzisiaj</h2>";
}

echo $span_zadania_moje;

//if ($today!='') echo "<br /><br />";
if ($nastartowej=='2') {
	echo "<tr><td colspan=1><span style='margin-left:-10px;'><input type=button class=buttons value=\"Pokaż wszystkie notatki\"  onClick=\"$('#notatki').load('hd_refresh_notes.php?userid=$es_nr&randval='+ Math.random()+'&nastartowej=1&today=');\"/></span>";
	
	echo "<span style=''><input type=button class=buttons value='Pokaż notatki na dziś' onClick=\"refresh_notatki1();\"/></span></td></tr>";	
}
?>

<?php 
if ($_REQUEST[from]=='main_simple') { 
	startbuttonsarea('right');
	addbuttons('zamknij');
	endbuttonsarea();
}
?>