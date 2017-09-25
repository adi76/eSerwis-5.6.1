<?php include_once('header.php'); ?>
<body>
<?php include('body_start.php');
$result = mysql_query("SELECT czytnik_id FROM $dbname.serwis_slownik_czytnik ORDER BY czytnik_nazwa", $conn) or die($k_b);
$count_rows = mysql_num_rows($result);
if ($count_rows!=0) {
	pageheader("Słownik modeli czytników",1,1);
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
	$sql="SELECT czytnik_id,czytnik_nazwa,czytnik_opis FROM $dbname.serwis_slownik_czytnik ORDER BY czytnik_nazwa LIMIT $limitvalue, $rps";
	$result = mysql_query($sql, $conn) or die($k_b);
	
	starttable();
	th("30;c;LP|;;Model czytnika|;;Opis czytnika|40;c;Opcje;9",$es_prawa);
	$i = 0;
	$j = $page*$rowpersite-$rowpersite+1;
	while (list($temp_id,$temp_nazwa,$temp_opis)=mysql_fetch_array($result)) {
		tbl_tr_highlight($i);
			td("30;c;".$j."");
			$j++;
			td(";;".$temp_nazwa."|;;".$temp_opis."");
			$accessLevels = array("9");
			if (array_search($es_prawa, $accessLevels)>-1) {
				td_("40;c");
					echo "<a title=' Usuń czytnik $temp_nazwa ze słownika '><input class=imgoption type=image src=img/delete.gif onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_czytnik.php?id=$temp_id&model=".urlencode($temp_nazwa)."')\"></a>";
				_td();
			} 
			$i++;
		_tr();
	}
endtable();
include_once('paging_end.php');
} else {
	errorheader("Baza modeli czytników jest pusta");
	?><meta http-equiv="REFRESH" content="<?php echo $http_refresh; ?>;url=<?php echo "$linkdostrony";?>main.php"><?php	
}

listabaz($es_prawa,"".$pokaz_ikony."");
echo "&nbsp;<span style='float:left; margin-left:5px;'>";addbuttons("wstecz");echo "</span>";

startbuttonsarea("right");
$accessLevels = array("9");
if (array_search($es_prawa, $accessLevels)>-1) {
	addownlinkbutton("'Dodaj model czytnika'","Button1","button","newWindow(530,155,'d_czytnik.php')");
}
addbuttons("start");
endbuttonsarea();
include('body_stop.php'); 
include('js/menu.js');
?>
<script>HideWaitingMessage();</script>
</body>
</html>