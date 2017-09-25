<?php include_once('header.php'); ?>
<body>
<?php include('body_start.php'); ?>
<?php
$result = mysql_query("SELECT * FROM $dbname.serwis_czarna_lista", $conn) or die($k_b);
$count_rows = mysql_num_rows($result);
// paging
$totalrows = mysql_num_rows($result);
if ($showall==0) { $rps=$rowpersite;} else $rps=10000;
if(empty($page)){ $page = 1; }
$limitvalue = $page * $rps - ($rps);
$sql="SELECT bl_id,bl_ip,bl_active,bl_osobadodajaca,bl_enddate FROM $dbname.serwis_czarna_lista ORDER BY bl_enddate DESC LIMIT $limitvalue, $rps";
$result = mysql_query($sql, $conn) or die($k_b);
// koniec - paging

if ($count_rows!=0) {
	pageheader("Przeglądanie czarnej listy IP");
	startbuttonsarea("center");
	if ($showall==0) {
		echo "<a class=paging href=$phpfile?showall=1&paget=$page>Pokaż wszystko na jednej stronie</a>";
	} else {
		echo "<a class=paging href=$phpfile?showall=0&page=$paget>Dziel na strony</a>";	
	}
	endbuttonsarea();

	starttable();
	th(";c;LP|;;Adres IP|;;Blokada aktywna|;nw;Blokada wygasa|;nw;Blokada ustawiona przez|;c;Opcje;9",$es_prawa);

	$i = 0;
	$j = $page*$rowpersite-$rowpersite+1;
	while (list($temp_id,$temp_ip,$temp_active,$temp_kto,$temp_enddate) = mysql_fetch_array($result)) {
		tbl_tr_highlight($i);
			td("30;c;".$j."");
			$j++;
			td(";;".$temp_ip."");
			td_img(";;");
				if ($temp_active==1) echo "<a title=' Deaktywuj blokadę '><input class=imgoption type=image src=img/on.gif align=absmiddle onclick=\"newWindow($dialog_window_x,$dialog_window_y,'z_czarna_lista_s.php?id=$temp_id')\"></a>TAK";
				if ($temp_active==0) echo "<a title=' Aktywuj blokadę '><input class=imgoption type=image src=img/off.gif align=absmiddle onclick=\"newWindow($dialog_window_x,$dialog_window_y,'z_czarna_lista_s.php?id=$temp_id')\"></a>NIE";
			_td();
			td_img(";;");
				if ($temp_enddate=='0000-00-00') {	echo "-"; } else {  echo "$temp_enddate";}
			_td();	
			td(";;".$temp_kto."");
			td_img(";c");
				$accessLevels = array("9");
				if(array_search($es_prawa, $accessLevels)>-1){
					echo "<a title=' Usuń adres $temp_ip z czarnej listy '><input class=imgoption type=image src=img/delete.gif onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_czarna_lista.php?id=$temp_id')\"></a>";
				}
			_td();
		_tr();
		$i++;
	}
endtable();
include_once('paging_end.php');
} else {
	errorheader("Czarna lista jest pusta");
	?><meta http-equiv="REFRESH" content="<?php echo $http_refresh; ?>;url=<?php echo "$linkdostrony";?>main.php"><?php	
}

startbuttonsarea("right");
$accessLevels = array("9");
if(array_search($es_prawa, $accessLevels)>-1){
addownlinkbutton("'Dodaj nowe IP do czarnej listy'","Button1","button","newWindow(460,185,'d_czarna_lista.php')");
}
addbuttons("start");
endbuttonsarea();
include('body_stop.php');
?>
</body>
</html>