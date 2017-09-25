<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body>
<?php include_once('body_start.php'); ?>
<?php
$sql="SELECT * FROM $dbname.serwis_zadania WHERE (";
if ($es_m!=1) {	$sql=$sql."(belongs_to=$es_filia) and ";}
//$sql=$sql."(belongs_to=$es_filia) and ";
if ($s=='nowe') 	{ $sql=$sql."(zadanie_status=-1)) ORDER BY zadanie_data_utworzenia DESC"; }
if ($s=='otwarte')	{ $sql=$sql."(zadanie_status=1) or (zadanie_status=0)) ORDER BY zadanie_data_utworzenia DESC";}
if ($s=='zakonczone') { $sql=$sql."(zadanie_status=9)) ORDER BY zadanie_data_zakonczenia DESC"; }
$result = mysql_query($sql, $conn) or die($k_b);
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
	
	if ($s=='nowe') 		pageheader("Przeglądanie nowych zadań",1,$e);
	if ($s=='otwarte')		pageheader("Przeglądanie otwartych zadań",1,$e);
	if ($s=='zakonczone')	pageheader("Przeglądanie zakończonych zadań",1,$e);

	?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();

	startbuttonsarea("center");
		if ($showall==0) {
			echo "<a class=paging href=z_zadania.php?showall=1&paget=$page&s=$s>Pokaż wszystko na jednej stronie</a>";
		} else {
			echo "<a class=paging href=z_zadania.php?showall=0&page=$paget&s=$s>Dziel na strony</a>";
		}
	echo "| Łącznie: <b>$count_rows pozycji</b>";	
	endbuttonsarea();
	starttable();
	if ($s!='zakonczone') {
		th("30;c;LP|;;Opis zadania<br />Ilość UP / Komórek przypisanych do zadania|;;Kategoria -> Podkategoria -> Podkategoria (poziom 2)<br />Osoba zgłaszająca|20;c;PR|115;;Utworzone przez<br />Data utworzenia|115;;Termin zakończenia|120;c;Status|40;c;Uwagi|;c;Opcje",$es_prawa);
	} else {
		th("30;c;LP|;;Opis zadania<br />Ilość UP / Komórek przypisanych do zadania|;;Kategoria -> Podkategoria -> Podkategoria (poziom 2)<br />Osoba zgłaszająca|20;c;PR|115;;Utworzone przez<br />Data utworzenia|115;;Termin zakończenia|115;;Zakończone przez<br />Data zakończenia|40;c;Uwagi|;c;Opcje",$es_prawa);
	}
	$i = 0;
	$j = $page*$rowpersite-$rowpersite+1;
	while ($newArray = mysql_fetch_array($result)) {
		$temp_id  			= $newArray['zadanie_id'];
		$temp_opis			= $newArray['zadanie_opis'];
		$temp_termin_zak	= $newArray['zadanie_termin_zakonczenia'];
		$temp_data_zak		= $newArray['zadanie_data_zakonczenia'];
		$temp_osoba_zak		= $newArray['zadanie_zakonczone_przez'];
		$temp_status		= $newArray['zadanie_status'];
		$temp_data_utw		= $newArray['zadanie_data_utworzenia'];
		$temp_osoba_utw		= $newArray['zadanie_utworzone_przez'];
		$temp_priorytet		= $newArray['zadanie_priorytet'];
		$temp_uwagi			= $newArray['zadanie_uwagi'];
		$temp_kat			= $newArray['zadanie_hd_kat'];
		$temp_podkat		= $newArray['zadanie_hd_podkat'];
		$temp_podkat2		= $newArray['zadanie_hd_podkat_poziom_2'];
		$temp_wc			= $newArray['zadanie_hd_wc'];
		$temp_osoba			= $newArray['zadanie_hd_osoba'];
	
		if ($temp_podkat2 == '') $temp_podkat2 = '-';
		
		$r2 = mysql_query("SELECT hd_podkategoria_opis FROM $dbname_hd.hd_podkategoria WHERE (hd_podkategoria_wlaczona=1) and (hd_podkategoria_nr='$temp_podkat') LIMIT 1", $conn_hd) or die($k_b);
		list($podkat_opis)=mysql_fetch_array($r2);
		$r2 = mysql_query("SELECT hd_kategoria_opis FROM $dbname_hd.hd_kategoria WHERE (hd_kategoria_wlaczona=1) and (hd_kategoria_nr='$temp_kat') LIMIT 1", $conn_hd) or die($k_b);
		list($kat_opis)=mysql_fetch_array($r2);
	
		if ($temp_podkat=='G') {
			$r2 = mysql_query("SELECT projekt_autor, projekt_data_utworzenia FROM $dbname_hd.hd_projekty WHERE (projekt_opis='$temp_podkat2') LIMIT 1", $conn_hd) or die($k_b);
			list($projekt_a, $projekt_d)=mysql_fetch_array($r2);
		}
	tbl_tr_highlight($i);	
		// wyliczenie ilości pozycji pod danym zadaniem
		$countall = mysql_num_rows(mysql_query("SELECT * FROM $dbname.serwis_zadania_pozycje WHERE (pozycja_zadanie_id=$temp_id)", $conn));
		$countwyk = mysql_num_rows(mysql_query("SELECT * FROM $dbname.serwis_zadania_pozycje WHERE (pozycja_zadanie_id=$temp_id) and (pozycja_status=9)", $conn));
		if ($countall>0) { $procent_ = ($countwyk/$countall)*100; } else $procent_=0;
		$procent = round_up($procent_, 2);
		// koniec wyliczenia ilości pozycji pod danym zadaniem
		td("30;c;".$j."");
		td_(";w");
			echo "<b>$temp_opis</b><br />$countall";
			if ($pokazpoczatekuwag==1) pokaz_uwagi($temp_uwagi,$iloscznakowuwag,"newWindow(480,265,'p_zadania_uwagi.php?id=$temp_id&nr=$temp_numer')");	
		_td();
		if ($temp_podkat=='G') {
			echo "<td>".$kat_opis." -> ".$podkat_opis." -> ".$temp_podkat2."<br /><font color=grey>Projekt utworzony przez <b>".$projekt_a."</b>, <b>".substr($projekt_d,0,16)."</b></font><br />".$temp_osoba;
		} else {
			td(";l;".$kat_opis." -> ".$podkat_opis." -> ".$temp_podkat2."<br />".$temp_osoba."");
		}
		td_("20;c");
			if ($temp_priorytet==0) echo "<a  title=' Priorytet czynności NISKI '><input type=image class=imgoption src=img/pr_low.gif></a>";
			if ($temp_priorytet==1) echo "<a title=' Priorytet czynności NORMALNY '><input type=image class=imgoption src=img/pr_normal.gif></a>";
			if ($temp_priorytet==2) echo "<a title=' Priorytet czynności WYSOKI '><input type=image class=imgoption src=img/pr_high.gif></a>";		
		_td();
			if ($temp_data_utw=='0000-00-00 00:00:00') $temp_data_utw='&nbsp;';
			if ($temp_termin_zak=='0000-00-00 00:00:00') $temp_termin_zak='nieokreślony';
			if ($temp_osoba_zak=='') $temp_osoba_zak='&nbsp;';
		td(";;".$temp_osoba_utw."<br />".substr($temp_data_utw,0,16)."");
			if ($temp_termin_zak!='nieokreślony') { $termin = substr($temp_termin_zak,0,10); } else $termin=$temp_termin_zak;
		td(";c;<b>".$termin."</b>");
			if ($s!='zakonczone') {	
				td_(";c;");
					if ($temp_status==-1) $status='w przygotowaniu';
					if ($temp_status==0) $status='nowe';
					if ($temp_status==1) $status='wykonywane'; 
					if ($temp_status==9) $status='zakończone';
					echo "<b>$status</b><br />";
					if ($temp_status==1) {
						echo "($countwyk/$countall) <b>$procent%</b>";
					}
				_td();
			} else {
				td(";;".$temp_osoba_zak."<br />".substr($temp_data_zak,0,16)."");
		}
		
		$j++;
		td_("50;c");
			if ($temp_uwagi!='') {
				echo "<a title=' Czytaj uwagi '><input class=imgoption type=image src=img/comment.gif onclick=\"newWindow(480,265,'p_zadania_uwagi.php?id=$temp_id&nr=$temp_numer')\"></a>";
			} 
			echo "<a title=' Edytuj uwagi '><input class=imgoption type=image src=img/edit_comment.gif onclick=\"newWindow(480,245,'e_zadania_uwagi.php?id=$temp_id')\"></a>";

		_td();
		td_("110;c");
			if (($countall>0) && ($temp_status==-1)) {
				echo "<a title=' Zmień status zadania $temp_opis na OTWARTE '><input class=imgoption type=image src=img/ok.gif onclick=\"newWindow($dialog_window_x,$dialog_window_y,'z_zadanie_status.php?id=$temp_id&s=1')\"></a>";	
			}
			
			if (($countall==$countwyk) && ($temp_status!=9) && ($countall!=0)) {	
				//list($kierownik1)=mysql_fetch_array(mysql_query("SELECT filia_leader FROM $dbname.serwis_filie WHERE filia_id=$es_filia", $conn));
				$kierownik = ($kierownik_nr==$es_nr);				
				if (($kierownik) || ($temp_osoba_utw==$currentuser)) {
					echo "<a title=' Zmień status zadania $temp_opis na ZAKOŃCZONE '><input class=imgoption type=image src=img/ok.gif onclick=\"newWindow($dialog_window_x,$dialog_window_y,'z_zadanie_status.php?id=$temp_id&s=9')\"></a>";	
				}
			}

			if ($temp_status==-1) {
				echo "<a title=' Popraw zadanie : $temp_opis '><input class=imgoption type=image src=img/edit.gif onclick=\"newWindow(800,600,'e_zadanie.php?id=$temp_id')\"></a>";	
				echo "<a title=' Usuń zadanie $temp_opis '><input type=image class=imgoption src=img/delete.gif 
onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_zadanie.php?id=$temp_id')\"></a>";
				echo "<a title=' Dodaj komórki/UP do zadania $temp_opis '><input class=imgoption type=image src=img/add_pos.gif onclick=\"newWindow_r(800,600,'d_zadanie_pozycje.php?id=$temp_id')\"></a>";
			}
			
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
		_td();
		$i++;
	_tr();
}
endtable();
include_once('paging_end.php');
} else {
	if ($s=='nowe') errorheader("Brak przygotowanych zadań w bazie");
	if ($s=='otwarte') errorheader("Brak otwartych zadań w bazie");
	if ($s=='zakończone') errorheader("Brak zakończonych zadań w bazie");
}
startbuttonsarea("right");

echo "<span style='float:left'>";
echo "&nbsp;Pokaż: ";
addlinkbutton("'Nowe czynności'","z_komorka_czynnosc.php?s=nowe");
//addlinkbutton("'Nowe zadania'","z_zadania.php?s=nowe");
if (($kierownik_nr==$es_nr) || ($is_dyrektor==1)) addlinkbutton("'Nowe projekty'","z_projekty.php?s=nowe");
echo "</span>";

if ($_REQUEST[noback]=='1') {
	addbuttons("zamknij");
} else {
	oddziel();
	addownlinkbutton("'Dodaj nowe zadanie'","Button1","button","newWindow_r(800,600,'d_zadanie.php')");
	if ($s=='nowe') {
		addlinkbutton("'Pokaż otwarte'","z_zadania.php?s=otwarte");
		addlinkbutton("'Pokaż zakończone'","z_zadania.php?s=zakonczone");
	} elseif ($s=='otwarte') {
		addlinkbutton("'Pokaż nowe'","z_zadania.php?s=nowe");
		addlinkbutton("'Pokaż zakończone'","z_zadania.php?s=zakonczone");
	} elseif ($s=='zakonczone') {
		addlinkbutton("'Pokaż nowe'","z_zadania.php?s=nowe");
		addlinkbutton("'Pokaż otwarte'","z_zadania.php?s=otwarte");
	}
	addbuttons("start");
}
endbuttonsarea();
include('body_stop.php');
?>

<script>HideWaitingMessage();</script>

</body>
</html>