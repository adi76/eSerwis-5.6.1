<?php include_once('header.php'); ?>
<body>
<?php include('body_start.php');
$sql="SELECT oprogramowanie_slownik_id,oprogramowanie_slownik_nazwa FROM $dbname.serwis_slownik_oprogramowania ORDER BY oprogramowanie_slownik_nazwa";
$result = mysql_query($sql, $conn) or die($k_b);
$count_rows = mysql_num_rows($result);
if ($count_rows!=0) {
	pageheader("Słownik oprogramowania",1,1);
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
	if ($showall==0) { $rps=$rowpersite; } else $rps=10000;
	if(empty($page)){ $page = 1; }
	$limitvalue = $page * $rps - ($rps);
	$sql="SELECT oprogramowanie_slownik_id,oprogramowanie_slownik_nazwa FROM $dbname.serwis_slownik_oprogramowania ";
	$sql=$sql."ORDER BY oprogramowanie_slownik_nazwa LIMIT $limitvalue, $rps";
	$result = mysql_query($sql, $conn) or die($k_b);

	starttable();
	th("30;c;LP|;;Nazwa oprogramowania|40;c;Opcje;9",$es_prawa);
	$i = 0;
	$j = $page*$rowpersite-$rowpersite+1;
	while (list($temp_id,$temp_nazwa) = mysql_fetch_array($result)) {
		tbl_tr_highlight($i);
			td("30;c;".$j."");
			$j++;
			td(";;".$temp_nazwa."");
			$accessLevels = array("9");
			if (array_search($es_prawa, $accessLevels)>-1) {
				td_("50;c");
					echo "<a title=' Edytuj nazwę $temp_nazwa '><input class=imgoption type=image src=img/edit.gif  onclick=\"newWindow(500,130,'e_oprogramowanie.php?id=$temp_id')\"></a>";
					echo "<a title=' Usuń oprogramowanie $temp_nazwa ze słownika '><input class=imgoption type=image src=img/delete.gif onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_oprogramowanie_slownik.php?id=$temp_id')\"></a>";
				_td();
			} 
			$i++;
		_tr();
	}
endtable();
include_once('paging_end.php');
} else {
	errorheader("Baza oprogramowania jest pusta");
	?><meta http-equiv="REFRESH" content="<?php echo $http_refresh; ?>;url=<?php echo "$linkdostrony";?>main.php"><?php	
}

listabaz($es_prawa,"".$pokaz_ikony."");
echo "&nbsp;<span style='float:left; margin-left:5px;'>";addbuttons("wstecz");echo "</span>";

startbuttonsarea("right");
$accessLevels = array("9");
if (array_search($es_prawa, $accessLevels)>-1) {
	addownlinkbutton("'Dodaj oprogramowanie'","Button1","button","newWindow(500,200,'d_oprogramowanie.php')");
}
addbuttons("start");
endbuttonsarea();
include('body_stop.php');
include('js/menu.js');
?>
<script>HideWaitingMessage();</script>
</body>
</html>