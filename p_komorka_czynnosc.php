<?php include_once('header.php'); ?>
<body>
<?php include('body_start.php');
$id = $_REQUEST[id];
$accessLevels = array("1","9"); 
if(array_search($es_prawa, $accessLevels)>-1) {
	$sql="SELECT * FROM $dbname.serwis_komorka_todo WHERE (belongs_to=$es_filia) and (todo_up_id=$id) and (todo_status=1) ";
	if ($_REQUEST[kierownik]=='1') {
		$sql = $sql. " and (todo_przypisane_osobie='$currentuser')";
	}
	$sql = $sql . " ORDER BY todo_termin_koncowy DESC";
} else {
	$sql="SELECT * FROM $dbname.serwis_komorka_todo WHERE (belongs_to=$es_filia) and (todo_up_id=$id) and (todo_status=1) ";
	
	if ($_REQUEST[kierownik]=='1') {
		$sql = $sql. " and (todo_przypisane_osobie='$currentuser')";
	} else { 
		if ($_REQUEST[filtruj]=='all') {
			$sql = $sql. "(todo_przypisane_osobie='') || (todo_przypisane_osobie='$currentuser') ";
		} 
		if ($_REQUEST[filtruj]=='') {	$sql = $sql. " and (todo_przypisane_osobie='')"; } 
		
		if ($_REQUEST[filtruj]!='all') { 
				$sql = $sql. " and (todo_przypisane_osobie='$currentuser')";
		}
	}
	
		$sql = $sql. " ORDER BY todo_termin_koncowy DESC";
	}
//echo $sql;
$result = mysql_query($sql, $conn) or die($k_b);
$count_rows = mysql_num_rows($result);

if ($count_rows!=0) {
	$result5=mysql_query("SELECT up_nazwa,up_pion_id FROM $dbname.serwis_komorki WHERE (up_id='$_REQUEST[id]') LIMIT 1",$conn) or die($k_b);
	list($nazwaup,$temp_pion_id)=mysql_fetch_array($result5);
	
	$sql_get_pion = "SELECT pion_nazwa FROM $dbname.serwis_piony WHERE (pion_id='$temp_pion_id') LIMIT 1";
	$wynik_get_pion = mysql_query($sql_get_pion, $conn) or die($k_b);
	$dane_get_pion = mysql_fetch_array($wynik_get_pion);
	$temp_pion_nazwa = $dane_get_pion['pion_nazwa'];
	
	$accessLevels = array("9"); 
	if(array_search($es_prawa, $accessLevels)>-1) {	
		if ($_REQUEST[kierownik]=='1') {
			pageheader("Czynności do wykonania w <b>".$temp_pion_nazwa." ".$nazwaup."</b> przez <b>".$currentuser."</b>");
		} else {
			pageheader("Czynności do wykonania w <b>".$temp_pion_nazwa." ".$nazwaup."</b>");
		}
	} else {
		pageheader("Czynności do wykonania w <b>".$temp_pion_nazwa." ".$nazwaup."</b> przez <b>".$currentuser."</b>");
	}
	?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
	starttable();
	th("30;c;LP|;;Czynność do wykonania|;c;<a title=' Priorytet czynności '>PR</a>|;;Przypisane do osoby<br />Termin ostateczny|;;Osoba wykonująca<br />Data wykonania|;c;Opcje",$es_prawa);
	$i = 0;
	$j = 1;
	while ($dane=mysql_fetch_array($result)) { 
		$temp_id		= $dane['todo_id'];						$temp_czynnosc 	= $dane['todo_opis']; 
		$temp_up_id 	= $dane['todo_up_id'];					$temp_termin	= $dane['todo_termin_koncowy'];
		$temp_osoba		= $dane['todo_przypisane_osobie'];		$temp_priorytet	= $dane['todo_priorytet'];
		$temp_osoba_wyk = $dane['todo_osoba_wykonujaca'];		$temp_data_wyk	= $dane['todo_data_wykonania'];
		$temp_status	= $dane['todo_status'];					$temp_kto		= $dane['todo_osobawpisujaca'];
		$temp_kiedy		= $dane['todo_datawpisu'];				$temp_uwagi		= $dane['todo_uwagi'];
		tbl_tr_highlight($i);
		td("30;c;".$j."");
		td_(";");
			echo "<a title=' Dodane przez $temp_kto | $temp_kiedy '>$temp_czynnosc</a>";
		_td();
		td_(";c");
			if ($temp_priorytet==0) echo "<a  title=' Priorytet czynności NISKI '><input type=image class=imgoption src=img/pr_low.gif></a>";
			if ($temp_priorytet==1) echo "<a title=' Priorytet czynności NORMALNY '><input type=image class=imgoption src=img/pr_normal.gif></a>";
			if ($temp_priorytet==2) echo "<a title=' Priorytet czynności WYSOKI '><input type=image class=imgoption src=img/pr_high.gif></a>";		
		_td();
		if ($temp_osoba=='') $temp_osoba = "<font color=grey>nie przypisane</font>";
		$temp_termin1 = substr($temp_termin,0,10);
		td(";;".$temp_osoba."<br />".$temp_termin1."");		
		if ($temp_data_wyk=='0000-00-00 00:00:00') $temp_data_wyk='';
		$temp_data_wyk1 = substr($temp_data_wyk,0,10);
		td(";;".$temp_osoba_wyk."<br />".$temp_data_wyk1."");
		td_(";c");
			if ($temp_termin!='0000-00-00 00:00:00') {
				$dddd = date("Y-m-d H:i:s");
				if (($dddd>$temp_termin) && ($temp_status==1)) echo "<a title=' Minął termin wykonania czynności '><input type=image class=imgoption src=img/poczasie.gif></a>";
			}
			if ($temp_osoba=='<font color=grey>nie przypisane</font>') $temp_osoba = '';
			if ($temp_status=='1') {
				$accessLevels = array("9");
				if(array_search($es_prawa, $accessLevels)>-1) {
					echo "<a title=' Przypisz czynność innej osobie '><input class=imgoption type=image src=img/user_edit.gif onclick=\"newWindow(600,400,'e_komorka_czynnosc_osoba.php?id=$temp_id&osoba=$temp_osoba')\"></a>";
				}
				echo "<a title=' Potwierdź wykonanie czynności '><input class=imgoption type=image src=img/snapraw_ok.gif onclick=\"newWindow(600,350,'e_komorka_czynnosc.php?id=$temp_id&komorka=".urlencode($_REQUEST[komorka])."&czynnosc=".urlencode($temp_czynnosc)."&osobawpisujaca=".urlencode($temp_kto)."')\"></a>";
			}
			if ($temp_status=='9') {
				echo "<a title=' Czynność została wykonana '><input class=imgoption type=image src=img/ok.gif></a>";
			}
	
			$accessLevels = array("9");
			if ((array_search($es_prawa, $accessLevels)>-1) || ($es_m==1)) {
				echo "<a title=' Usuń czynność z listy '><input class=imgoption type=image src=img/delete.gif  onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_komorka_czynnosc.php?id=$temp_id')\"></a>";		
			}
			echo "<a title=' Szczegółowe informacje o $nazwaup '><input class=imgoption type=image src=img/detail.gif onclick=\"newWindow_r(700,600,'p_komorka_szczegoly.php?id=$temp_up_id')\"></a>";	
		_td();
	_tr();
	$i++;
	$j++;
}
endtable();
} else {
	errorheader("Dla wybranego UP/komórki nie ma czynności zaplanowanych");
}
startbuttonsarea("right");
oddziel();
echo "<span style='float:left'>";
addlinkbutton("'Wybierz inną komórkę/UP'","p_komorka_wybierz.php?filtruj=all");
echo "</span>";
echo "<input class=buttons id=button name=button type=button style='font-weight:bold;' onClick=\"newWindow(570,350,'d_komorka_czynnosc.php?id=$_REQUEST[id]'); return false; \" value='Dodaj czynność' />";
//addownlinkbutton("'Dodaj czynność'","button","button","newWindow(570,350,'d_komorka_czynnosc.php?id=$id')");
addclosewithreloadbutton("Zamknij");
endbuttonsarea();
include('body_stop.php'); 
?>
<script>HideWaitingMessage();</script>
</body>
</html>