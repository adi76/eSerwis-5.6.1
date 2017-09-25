<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body>
<?php include_once('body_start.php'); ?>
<?php
$sql="SELECT * FROM $dbname_hd.hd_projekty WHERE (";
//if ($es_m!=1) {	$sql=$sql."(belongs_to=$es_filia) and ";}
//$sql=$sql."(belongs_to=$es_filia) and ";
if ($s=='nowe') 	{ $sql=$sql."(projekt_status=0)) ORDER BY projekt_termin_zakonczenia DESC"; }
if ($s=='otwarte')	{ $sql=$sql."(projekt_status=1)) ORDER BY projekt_termin_zakonczenia DESC";}
if ($s=='zakonczone') { $sql=$sql."(projekt_status=9)) ORDER BY projekt_termin_zakonczenia DESC"; }

$result = mysql_query($sql, $conn_hd) or die($k_b);
$count_rows = mysql_num_rows($result);
// paging
$totalrows = mysql_num_rows($result);
if(empty($page)){ $page = 1; }
if ($showall==0) {  $rps=$rowpersite;} else $rps=10000;
$limitvalue = $page * $rps - ($rps);
$sql=$sql." LIMIT $limitvalue, $rps";
$result = mysql_query($sql, $conn) or die($k_b);

// koniec - paging
if ($count_rows!=0) {
	$e = 1;
	if ($_REQUEST[noback]=='1') $e = 0;
	
	if ($s=='nowe') 		pageheader("Przeglądanie nowych projektów",1,$e);
	if ($s=='otwarte')		pageheader("Przeglądanie projektów otwartych",1,$e);
	if ($s=='zakonczone')	pageheader("Przeglądanie projektów zakończonych",1,$e);

	?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();

	startbuttonsarea("center");
		if ($showall==0) {
			echo "<a class=paging href=z_projekty.php?showall=1&paget=$page&s=$s>Pokaż wszystko na jednej stronie</a>";
		} else {
			echo "<a class=paging href=z_projekty.php?showall=0&page=$paget&s=$s>Dziel na strony</a>";
		}
	echo "| Łącznie: <b>$count_rows pozycji</b>";	
	endbuttonsarea();
	starttable();
	if ($s!='zakonczone') {
		th("30;c;LP|;;Opis projektu<br />Uwagi|100;c;Dotyczy filii/oddziału|20;c;PR|115;;Utworzone przez<br />Data utworzenia|115;c;Termin graniczny wykonania|120;c;Status|40;c;Uwagi|;c;Opcje",$es_prawa);
	} else {
		th("30;c;LP|;;Opis projektu<br />Uwagi|100;c;Dotyczy filii/oddziału|20;c;PR|115;;Utworzone przez<br />Data utworzenia|115;c;Termin graniczny wykonania|115;;Zakończone przez<br />Data zakończenia|40;c;Uwagi",$es_prawa);
	}
	$i = 0;
	$j = $page*$rowpersite-$rowpersite+1;
	while ($newArray = mysql_fetch_array($result)) {
		$temp_id  			= $newArray['projekt_id'];
		$temp_opis			= $newArray['projekt_opis'];
		$temp_termin_zak	= $newArray['projekt_termin_zakonczenia'];
		$temp_planowany_termin	= $newArray['projekt_termin_graniczny'];
		$temp_dla_filii		= $newArray['projekt_dla_filii'];
		$temp_active		= $newArray['projekt_active'];
		$temp_osoba_utw		= $newArray['projekt_autor'];
		$temp_data_utw		= $newArray['projekt_data_utworzenia'];
		$temp_status		= $newArray['projekt_status'];
		$temp_priorytet		= $newArray['projekt_priorytet'];
		$temp_uwagi			= $newArray['projekt_uwagi'];
		$temp_osoba_zak		= $newArray['projekt_osoba_zakanczajaca'];
		
		tbl_tr_highlight($i);	
		// wyliczenie ilości pozycji pod danym zadaniem
		//$countall = mysql_num_rows(mysql_query("SELECT * FROM $dbname.serwis_zadania_pozycje WHERE (pozycja_zadanie_id=$temp_id)", $conn));
		//$countwyk = mysql_num_rows(mysql_query("SELECT * FROM $dbname.serwis_zadania_pozycje WHERE (pozycja_zadanie_id=$temp_id) and (pozycja_status=9)", $conn));
		//if ($countall>0) { $procent_ = ($countwyk/$countall)*100; } else $procent_=0;
		//$procent = round_up($procent_, 2);
		// koniec wyliczenia ilości pozycji pod danym zadaniem
		td("30;c;".$j."");
		td_(";w");
			echo "<b>$temp_opis</b>";
			if ($pokazpoczatekuwag==1) pokaz_uwagi($temp_uwagi,$iloscznakowuwag,"newWindow(480,265,'p_projekt_uwagi.php?id=$temp_id&nr=$temp_numer')");	
		_td();
		
		$jedna_filia = explode("|", $temp_dla_filii);
		$ile_filii = count($jedna_filia);
		$list_filie = '';
		for ($i=0; $i<$ile_filii; $i++) {
			$result444 = mysql_query("SELECT filia_nazwa FROM $dbname.serwis_filie WHERE (filia_id='$jedna_filia[$i]') LIMIT 1", $conn_hd) or die($k_b);
			list($temp_opis)=mysql_fetch_array($result444);
			if ($temp_opis!='') $list_filie .= $temp_opis."<br />";
		}
		

		td_(";c");
			echo "$list_filie";
		_td();
				
		td_("20;c");
			if ($temp_priorytet==0) echo "<a  title=' Priorytet projektu NISKI '><input type=image class=imgoption src=img/pr_low.gif></a>";
			if ($temp_priorytet==1) echo "<a title=' Priorytet projektu NORMALNY '><input type=image class=imgoption src=img/pr_normal.gif></a>";
			if ($temp_priorytet==9) echo "<a title=' Priorytet projektu WYSOKI '><input type=image class=imgoption src=img/pr_high.gif></a>";		
		_td();
			if ($temp_data_utw=='0000-00-00 00:00:00') $temp_data_utw='&nbsp;';
			if ($temp_planowany_termin=='0000-00-00') $temp_planowany_termin='nieokreślony';
			//if ($temp_osoba_zak=='') $temp_osoba_zak='&nbsp;';
		td(";;".$temp_osoba_utw."<br />".substr($temp_data_utw,0,16)."");
			if ($temp_planowany_termin!='nieokreślony') { $termin = substr($temp_planowany_termin,0,10); } else $termin=$temp_planowany_termin;
		td(";c;<b>".$termin."</b>");
			if ($s!='zakonczone') {	
				td_(";c;");
				//	if ($temp_status==-1) $status='w przygotowaniu';
					if ($temp_status==0) $status='nowe';
					if ($temp_status==1) $status='wykonywane'; 
					if ($temp_status==9) $status='zakończone';
					echo "<b>$status</b><br />";
				_td();
			} else {
				td(";;".$temp_osoba_zak."<br />".substr($temp_termin_zak,0,16)."");
				//td(";;");
				
		}
		
		$j++;
		td_("50;c");
			if ($temp_uwagi!='') {
				echo "<a title=' Czytaj uwagi '><input class=imgoption type=image src=img/comment.gif onclick=\"newWindow(500,300,'p_projekt_uwagi.php?id=$temp_id&nr=$temp_numer')\"></a>";
			} 
			echo "<a title=' Edytuj uwagi '><input class=imgoption type=image src=img/edit_comment.gif onclick=\"newWindow(500,300,'e_projekt_uwagi.php?id=$temp_id')\"></a>";

		_td();
		
	if ($s!='zakonczone') {		
		td_("110;c");
			if ($temp_status==0) {
				if (($kierownik_nr==$es_nr) || ($is_dyrektor==1)) {
					echo "<a title=' Zmień status projektu $temp_opis na OTWARTE '><input class=imgoption type=image src=img/ok.gif onclick=\"newWindow($dialog_window_x,$dialog_window_y,'z_projekt_status.php?id=$temp_id&s=1')\"></a>";	
				}
			}
			
			if ($temp_status==1) {	
				//list($kierownik1)=mysql_fetch_array(mysql_query("SELECT filia_leader FROM $dbname.serwis_filie WHERE filia_id=$es_filia", $conn));
				if (($kierownik_nr==$es_nr) || ($is_dyrektor==1)) {
					echo "<a title=' Zmień status projektu $temp_opis na NOWY'><input class=imgoption type=image src=img/unaccept.gif onclick=\"newWindow($dialog_window_x,$dialog_window_y,'z_projekt_status.php?id=$temp_id&s=cofnij')\"></a>";	
					
					echo "<a title=' Zmień status projektu $temp_opis na ZAKOŃCZONY '><input class=imgoption type=image src=img/ok.gif onclick=\"newWindow($dialog_window_x,$dialog_window_y,'z_projekt_status.php?id=$temp_id&s=9')\"></a>";	
					
					echo "<a title=' Usuń projekt $temp_opis '><input type=image class=imgoption src=img/delete.gif 
onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_projekt.php?id=$temp_id')\"></a>";
				}
				
			}

			if ($temp_status==0) {
				if (($kierownik_nr==$es_nr) || ($is_dyrektor==1)) {
					echo "<a title=' Popraw projekt : $temp_opis '><input class=imgoption type=image src=img/edit.gif onclick=\"newWindow(800,600,'e_projekt.php?id=$temp_id')\"></a>";	
					echo "<a title=' Usuń projekt $temp_opis '><input type=image class=imgoption src=img/delete.gif 
onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_projekt.php?id=$temp_id')\"></a>";

				//echo "<a title=' Dodaj komórki/UP do zadania $temp_opis '><input class=imgoption type=image src=img/add_pos.gif onclick=\"newWindow_r(800,600,'d_zadanie_pozycje.php?id=$temp_id')\"></a>";
				}
			}
			
			if ($temp_status==9) {
				//echo "sss";
			}
	/*		
			if (($kierownik_nr==$es_nr) && (($temp_status==0) || ($temp_status==1))) {
				echo "<a title=' Popraw zadanie : $temp_opis '><input class=imgoption type=image src=img/edit.gif onclick=\"newWindow(800,600,'e_zadanie.php?id=$temp_id')\"></a>";	
				echo "<a title=' Usuń zadanie $temp_opis '><input type=image class=imgoption src=img/delete.gif 
onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_zadanie.php?id=$temp_id')\"></a>";
			}

			if ($temp_status==1) {
				echo "<a title=' Dodaj komórki/UP do zadania $temp_opis '><input class=imgoption type=image src=img/add_pos.gif onclick=\"newWindow_r(800,600,'d_zadanie_pozycje.php?id=$temp_id')\"></a>";
			}
			
			if ($countall>0) {
				echo "<a title=' Pokaż komórki/UP przypisane do zadania $temp_opis '><input class=imgoption type=image src=img/search.gif  onclick=\"newWindow_r($_COOKIE[max_x],$_COOKIE[max_y],'p_zadanie_pozycje.php?id=$temp_id&all=0&dlaosoby=&nadzien=&noback=$_REQUEST[noback]')\"></a>";
			}
		*/
		_td();
	}
		$i++;
	_tr();
}
endtable();
include_once('paging_end.php');
} else {
	if ($s=='nowe') errorheader("Brak nowych projektów w bazie");
	if ($s=='otwarte') errorheader("Brak otwartych projektów w bazie");
	if ($s=='zakonczone') errorheader("Brak zakończonych projektów w bazie");
}
startbuttonsarea("right");

echo "<span style='float:left'>";
echo "&nbsp;Pokaż: ";
addlinkbutton("'Nowe czynności'","z_komorka_czynnosc.php?s=nowe");
addlinkbutton("'Nowe zadania'","z_zadania.php?s=nowe");
//if (($kierownik_nr==$es_nr) || ($is_dyrektor==1)) addlinkbutton("'Nowe projekty'","z_projekty.php?s=nowe");
echo "</span>";

if ($_REQUEST[noback]=='1') {
	addbuttons("zamknij");
} else {
	oddziel();
	if (($kierownik_nr==$es_nr) || ($is_dyrektor==1)) {
		addownlinkbutton("'Dodaj nowy projekt'","Button1","button","newWindow_r(800,600,'d_projekt.php')"); 
		if ($s=='nowe') {
			addlinkbutton("'Pokaż otwarte'","z_projekty.php?s=otwarte");
			addlinkbutton("'Pokaż zakończone'","z_projekty.php?s=zakonczone");
		} elseif ($s=='otwarte') {
			addlinkbutton("'Pokaż nowe'","z_projekty.php?s=nowe");
			addlinkbutton("'Pokaż zakończone'","z_projekty.php?s=zakonczone");
		} elseif ($s=='zakonczone') {
			addlinkbutton("'Pokaż nowe'","z_projekty.php?s=nowe");
			addlinkbutton("'Pokaż otwarte'","z_projekty.php?s=otwarte");
		}
	}
	addbuttons("start");
}
endbuttonsarea();
include('body_stop.php');
?>

<script>HideWaitingMessage();</script>

</body>
</html>