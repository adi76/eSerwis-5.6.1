<?php include_once('header.php'); ?>
<body>
<?php include('body_start.php'); 
$result = mysql_query("SELECT umowa_id FROM $dbname.serwis_umowy", $conn) or die($k_b);
$count_rows = mysql_num_rows($result);
if ($count_rows!=0) {
	?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
	pageheader("Przeglądanie bazy umów",1,1);
	// paging
	$totalrows = mysql_num_rows($result);
	if ($showall==0) { $rps=$rowpersite; } else $rps=10000;
	if (empty($page)){ $page = 1; }
	$limitvalue = $page * $rps - ($rps);
	$sql="SELECT umowa_id,umowa_nr,umowa_nr_zlecenia,umowa_opis,umowa_koordynator,umowa_koordynator_email FROM $dbname.serwis_umowy ORDER BY umowa_nr LIMIT $limitvalue, $rps";
	$result = mysql_query($sql, $conn) or die($k_b);
	// koniec - paging
	startbuttonsarea("center");
	if ($showall==0) {
		echo "<a class=paging href=$phpfile?showall=1&paget=$page>Pokaż wszystko na jednej stronie</a>";
	} else {
		echo "<a class=paging href=$phpfile?showall=0&page=$paget>Dziel na strony</a>";		
	}
	echo "| Łącznie: <b>$count_rows pozycji</b>";
	endbuttonsarea();
	starttable();
	th("30;c;LP|;;Numer umowy|;;Numer zlecenia|;;Opis|;;Koordynator<br />e-mail koordynatora|;c;Opcje;9",$es_prawa);
	$i = 0;
	$j = $page*$rowpersite-$rowpersite+1;
	while (list($temp_id,$temp_nr,$temp_nr_zlecenia,$temp_opis,$temp_koord,$temp_koordemail)=mysql_fetch_array($result)) {
		tbl_tr_highlight($i);
			td("30;c;".$j."|200;;".$temp_nr."|;;".$temp_nr_zlecenia."|;;".$temp_opis."");
			td_(";;");
				echo "$temp_koord<br />$temp_koordemail";
			_td();
			$j++;
			$accessLevels = array("9");
			if (array_search($es_prawa, $accessLevels)>-1) {
				td_("40;c");
					echo "<a title=' Edycja umowy nr $temp_nr '><input class=imgoption type=image src=img/edit.gif onclick=\"newWindow(460,235,'e_umowa.php?id=$temp_id')\"></a>";
					echo "<a title=' Usuń umowę nr $temp_nr z bazy '><input type=image class=imgoption src=img/delete.gif class=imgoption onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_umowa.php?id=$temp_id')\"></a>";
				_td();
			} 
			$i++;
		_tr();
}
endtable();
include_once('paging_end.php');
} else {
	errorheader("Brak umów w bazie");
	?><meta http-equiv="REFRESH" content="<?php echo $http_refresh; ?>;url=<?php echo "$linkdostrony";?>main.php"><?php	
}

listabaz($es_prawa,"".$pokaz_ikony."");
echo "&nbsp;<span style='float:left; margin-left:5px;'>";addbuttons("wstecz");echo "</span>";

startbuttonsarea("right");
$accessLevels = array("9");
if(array_search($es_prawa, $accessLevels)>-1){
	addownlinkbutton("'Dodaj umowę'","Button1","button","newWindow(460,235,'d_umowa.php')");
}
addbuttons("start");
endbuttonsarea();
include('body_stop.php');
include('js/menu.js');
?>
<script>HideWaitingMessage();</script>
</body>
</html>