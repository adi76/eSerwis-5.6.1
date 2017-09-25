<?php include_once('header.php'); ?>
<body>
<?php include_once('body_start.php'); ?>
<?php
$sql="SELECT * FROM $dbname.serwis_komorka_todo WHERE (";
if ($es_m!=1) { $sql=$sql."(belongs_to=$es_filia) and "; }
if ($s=='nowe') 	{ $sql=$sql."(todo_status=1)) ORDER BY todo_termin_koncowy DESC"; }
if ($s=='zakonczone') { $sql=$sql."(todo_status=9)) ORDER BY todo_termin_koncowy DESC"; }
$result = mysql_query($sql, $conn) or die($k_b);
$count_rows = mysql_num_rows($result);
// paging
$totalrows = mysql_num_rows($result);
if(empty($page)){ $page = 1; }
if ($showall==0) { $rps=$rowpersite; } else $rps=10000;
$limitvalue = $page * $rps - ($rps);
$sql=$sql." LIMIT $limitvalue, $rps";
$result = mysql_query($sql, $conn) or die($k_b);
// koniec - paging

if ($count_rows!=0) {
	if ($s=='nowe') pageheader("Przeglądanie nowych czynności do wykonania",1,1);
	if ($s=='zakonczone') pageheader("Przeglądanie czynności zakończonych",1,1);
	
	?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
			
	startbuttonsarea("center");
	if ($showall==0) {
		echo "<a class=paging href=z_komorka_czynnosc.php?showall=1&paget=$page&s=$s>Pokaż wszystko na jednej stronie</a>";
	} else {
		echo "<a class=paging href=z_komorka_czynnosc.php?showall=0&page=$paget&s=$s>Dziel na strony</a>";	
	}
	
	echo "<a href=# class=paging1>";
	echo "| Łącznie: <b>$count_rows pozycji</b>";
	echo "</a>";
	
	endbuttonsarea();
	starttable();
	th("30;c;LP|auto;;Czynność do wykonania<br />Miejsce wykonania czynności|30;c;<a title=' Priorytet czynności '>PR</a>|120;;Przypisane do osoby<br />Termin ostateczny|120;;Osoba wykonująca<br />Data wykonania|80;c;Opcje",$es_prawa);
	$i = 0;
	$j = $page*$rowpersite-$rowpersite+1;
	while ($newArray = mysql_fetch_array($result)) {
		$temp_id		= $newArray['todo_id'];					$temp_up_id		= $newArray['todo_up_id'];
		$temp_czynnosc 	= $newArray['todo_opis']; 				$temp_termin	= $newArray['todo_termin_koncowy'];
		$temp_osoba		= $newArray['todo_przypisane_osobie'];	$temp_priorytet	= $newArray['todo_priorytet'];
		$temp_osoba_wyk = $newArray['todo_osoba_wykonujaca'];	$temp_data_wyk	= $newArray['todo_data_wykonania'];
		$temp_status	= $newArray['todo_status'];				$temp_uwagi		= $newArray['todo_uwagi'];
		$temp_kto 		= $newArray['todo_osobawpisujaca'];		$temp_kiedy		= $newArray['todo_datawpisu'];
		tbl_tr_highlight($i);
			$result1=mysql_query("SELECT up_nazwa FROM $dbname.serwis_komorki WHERE (up_id=$temp_up_id) and (belongs_to=$es_filia) LIMIT 1",$conn) or die($k_b);
			list($nazwaup)=mysql_fetch_array($result1);

			$sql_up = "SELECT up_id, up_pion_id FROM $dbname.serwis_komorki WHERE (up_nazwa='$nazwaup') and (belongs_to=$es_filia) LIMIT 1";
			$wynik = mysql_query($sql_up, $conn) or die($k_b);
			$dane_up = mysql_fetch_array($wynik);
			$temp_up_id = $dane_up['up_id'];
			$temp_pion_id = $dane_up['up_pion_id'];
			
			// nazwa pionu z id pionu
			$sql_get_pion = "SELECT pion_nazwa FROM $dbname.serwis_piony WHERE (pion_id='$temp_pion_id') LIMIT 1";
			$wynik_get_pion = mysql_query($sql_get_pion, $conn) or die($k_b);
			$dane_get_pion = mysql_fetch_array($wynik_get_pion);
			$temp_pion_nazwa = $dane_get_pion['pion_nazwa'];
			// koniec ustalania nazwy pionu
	
			td("30;c;".$j."");
			td_(";");
				echo "<a title=' Dodane przez : $temp_kto | $temp_kiedy '>$temp_czynnosc</a><br />";
				echo "<b><a class=normalfont title=' Szczegółowe informacje o $upnazwa ' onclick=\"newWindow_r(700,600,'p_komorka_szczegoly.php?id=$temp_up_id')\" href=#>$temp_pion_nazwa $nazwaup</a></b>";
				if ($pokazpoczatekuwag==1) pokaz_uwagi($temp_uwagi,$iloscznakowuwag,"newWindow(480,265,'p_czynnosc_uwagi.php?id=$temp_id')");
			_td();
			td_(";c");
				if ($temp_priorytet==0) echo "<a  title=' Priorytet czynności NISKI '><input type=image class=imgoption src=img/pr_low.gif></a>";
				if ($temp_priorytet==1) echo "<a title=' Priorytet czynności NORMALNY '><input type=image class=imgoption src=img/pr_normal.gif></a>";
				if ($temp_priorytet==2) echo "<a title=' Priorytet czynności WYSOKI '><input type=image class=imgoption src=img/pr_high.gif></a>";		
			_td();
			td(";;".$temp_osoba."<br />".substr($temp_termin,0,10)."");
			if ($temp_data_wyk=='0000-00-00 00:00:00') $temp_data_wyk='';
			td(";;".$temp_osoba_wyk."<br />".substr($temp_data_wyk,0,16)."");
			td_(";c");
				if ($temp_termin!='0000-00-00 00:00:00') {
					$dddd = date("Y-m-d H:i:s");
					if (($dddd>$temp_termin) && ($temp_status==1)) echo "<a title=' Minął termin wykonania czynności '><input type=image class=imgoption src=img/poczasie.gif></a>";
				}
				if ($temp_status=='1') {
					$accessLevels = array("9");
					if(array_search($es_prawa, $accessLevels)>-1) {
						echo "<a title=' Przypisz czynność innej osobie '><input class=imgoption type=image src=img/user_edit.gif onclick=\"newWindow(600,200,'e_komorka_czynnosc_osoba.php?id=$temp_id&osoba=$temp_osoba')\"></a>";
					}
					echo "<a title=' Potwierdź wykonanie czynności '><input class=imgoption type=image src=img/snapraw_ok.gif onclick=\"newWindow(500,265,'e_komorka_czynnosc.php?id=$temp_id')\"></a>";
				}
				if ($temp_status=='9') {
					echo "<a title=' Czynność została wykonana '><input class=imgoption type=image src=img/ok.gif></a>";
				}
				$accessLevels = array("9");
				if ((array_search($es_prawa, $accessLevels)>-1) || ($es_m==1)) {
					echo "<a title=' Usuń czynność z listy '><input class=imgoption type=image src=img/delete.gif  onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_komorka_czynnosc.php?id=$temp_id')\"></a>";		
				}
			_td();
		_tr();
		$i++;
		$j++;
}
endtable();
include_once('paging_end.php');
} else {
	errorheader("Brak czynności do wykonania w bazie");
	?><meta http-equiv="REFRESH" content="<?php echo $http_refresh; ?>;url=<?php echo "$linkdostrony";?>main.php"><?php	
}
startbuttonsarea("right");

echo "<span style='float:left'>";
echo "&nbsp;Pokaż: ";
//addlinkbutton("'Nowe czynności'","z_komorka_czynnosc.php?s=nowe");
addlinkbutton("'Nowe zadania'","z_zadania.php?s=nowe");
if (($kierownik_nr==$es_nr) || ($is_dyrektor==1)) addlinkbutton("'Nowe projekty'","z_projekty.php?s=nowe");
echo "</span>";


//oddziel();
if ($s=='nowe') {
	addownlinkbutton("'Dodaj czynność do wykonania'","button","button","newWindow_r(800,400,'p_komorka_wybierz.php?filtruj=all')");
	addlinkbutton("'Pokaż czynności zakończone'","z_komorka_czynnosc.php?s=zakonczone");
} else {
	addlinkbutton("'Pokaż czynności nowe'","z_komorka_czynnosc.php?s=nowe");
}
addbuttons("start");
endbuttonsarea();
include('body_stop.php'); 
?>
<script>HideWaitingMessage();</script>
</body>
</html>