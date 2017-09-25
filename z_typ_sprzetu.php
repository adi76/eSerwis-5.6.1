<?php include_once('header.php'); ?>
<body>
<?php include('body_start.php');
$result = mysql_query("SELECT rola_id FROM $dbname.serwis_slownik_rola ORDER BY rola_nazwa", $conn) or die($k_b);
$count_rows = mysql_num_rows($result);
if ($count_rows!=0) {
	pageheader("Słownik typów sprzętu komputerowego",1,1);
	?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
	startbuttonsarea("center");
	if ($showall==0) {
	echo "<a class=paging href=$phpfile?showall=1&paget=$page>Pokaż wszystko na jednej stronie</a>";
	} else {
	echo "<a class=paging href=$phpfile?showall=0&page=$paget>Dziel na strony</a>";	
	}
	echo "| Łącznie: <b>$count_rows pozycji</b>";
	endbuttonsarea();
	$totalrows = mysql_num_rows($result);
	if ($showall==0) { $rps=$rowpersite;} else $rps=10000;
	if (empty($page)) { $page = 1; }
	$limitvalue = $page * $rps - ($rps);
	$sql="SELECT rola_id,rola_nazwa,rola_do_ewidencji,rola_kolor FROM $dbname.serwis_slownik_rola ";
	$sql=$sql."ORDER BY rola_nazwa LIMIT $limitvalue, $rps";
	$result = mysql_query($sql, $conn) or die($k_b);
	
	starttable();
	th("30;c;LP|;;Typ sprzętu komputerowego|100;c;Tylko do ewidencji|50;c;Kolor;9|40;c;Opcje;19",$es_prawa);
	$i = 0;
	$j = $page*$rowpersite-$rowpersite+1;
	while (list($temp_id,$temp_nazwa,$temp_do_ew,$temp_kolor)=mysql_fetch_array($result)) {
		tbl_tr_highlight($i);
			td("30;c;".$j."");
			$j++;
			td(";;".$temp_nazwa."");
			td_(";c");
				if ($temp_do_ew==1) {
					echo "<a title=' Wyłącz z ewidencji '><b>TAK</b><input class=imgoption type=image src=img/on.gif onclick=\"newWindow(540,295,'e_rola_zmien.php?id=$temp_id&value=0&submit=1');\"></a>";
//					echo "<a class=normalfont href=# onClick=e_rola_zmien.php?id=$temp_id&value=0&submit=1><b>TAK</b></a>";
				} else {
					echo "<a title=' Włącz do ewidencji '>NIE<input class=imgoption type=image src=img/off.gif onclick=\"newWindow(540,295,'e_rola_zmien.php?id=$temp_id&value=1&submit=1');\"></a>";
				}
			_td();
				$accessLevels = array("9");
				if (array_search($es_prawa, $accessLevels)>-1) {
					td_("50;c");
						echo "<input type=button style='border-width:0;border-style:solid;background:$temp_kolor' value='Zmień' onClick=\"newWindow(550,290,'e_typ_sprzetu_kolor.php?id=$temp_id')\">";
					_td();
					td_("40;c");
						echo "<a title=' Usuń $temp_nazwa ze słownika '><input class=imgoption type=image src=img/delete.gif onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_typ_sprzetu.php?id=$temp_id')\"></a>";
					_td();
				}
			$i++;
		_tr();
	}
endtable();
include_once('paging_end.php');
} else pageheader("Baza typów sprzętu komputerowego jest pusta");

listabaz($es_prawa,"".$pokaz_ikony."");
echo "&nbsp;<span style='float:left; margin-left:5px;'>";addbuttons("wstecz");echo "</span>";

startbuttonsarea("right");
$accessLevels = array("9");
if (array_search($es_prawa, $accessLevels)>-1) {
	addownlinkbutton("'Dodaj typ sprzętu komputerowego'","Button1","button","newWindow(550,340,'d_typ_sprzetu.php')");
}
addbuttons("start");
endbuttonsarea();
include('body_stop.php'); 
include('js/menu.js');
?>
<script>HideWaitingMessage();</script>
</body>
</html>