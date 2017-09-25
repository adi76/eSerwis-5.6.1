<?php include_once('header.php'); ?>
<body>
<?php include('body_start.php');
$result = mysql_query("SELECT pion_id,pion_nazwa FROM $dbname.serwis_piony ORDER BY pion_nazwa", $conn) or die($k_b);
$count_rows = mysql_num_rows($result);
if ($count_rows!=0) {
	pageheader("Przeglądanie bazy pionów",1,1);
	?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
	startbuttonsarea("center");
		if ($showall==0) {
			echo "<a class=paging href=$phpfile?showall=1&paget=$page>Pokaż wszystko na jednej stronie</a>";
		} else {
			echo "<a class=paging href=$phpfile?showall=0&page=$paget>Dziel na strony</a>";	
		}
	echo "| Łącznie: <b>$count_rows pozycji</b>";	
	endbuttonsarea();
	// paging
	$totalrows = mysql_num_rows($result);
	if ($showall==0) { $rps=$rowpersite; } else $rps=10000;
	if(empty($page)) { $page = 1; }
	$limitvalue = $page * $rps - ($rps);
	$sql="SELECT pion_id,pion_nazwa,pion_active FROM $dbname.serwis_piony ORDER BY pion_nazwa LIMIT $limitvalue, $rps";
	$result=mysql_query($sql, $conn) or die($k_b);
	// koniec - paging
	starttable();
	th("30;c;LP|;;Nazwa pionu|50;c;Widoczny|40;c;Opcje;9",$es_prawa);
	$i = 0;
	$j = $page*$rowpersite-$rowpersite+1;
	while (list($temp_id,$temp_nazwa,$temp_active)=mysql_fetch_array($result)) {
		tbl_tr_highlight($i);
			td("30;c;".$j."|;;".$temp_nazwa."");
			td_("50;c");
				if ($temp_active==0) { 
					echo "<a title=' Włącz pion $temp_nazwa w bazie '><input class=imgoption type=image src=img/off.gif onclick=\"newWindow($dialog_window_x,$dialog_window_y,'w_pion.php?id=$temp_id&action=wlacz');\"></a>"; 
				} else 
					{ 
						echo "<img src=img/on.gif border=0>";
					}
			_td();
			$j++;	
			$accessLevels = array("9");
			if (array_search($es_prawa, $accessLevels)>-1) {
			td_("40;c");
				$result_e1 = mysql_query("SELECT up_pion_id FROM $dbname.serwis_komorki WHERE (up_pion_id=$temp_id)", $conn) or die($k_b);
				$count_rows = mysql_num_rows($result_e1);
				if ($count_rows==0) { 
					if ($temp_active!=0) {
						echo "<a title=' Wyłącz pion $temp_nazwa w bazie (brak jest komorek do niego przypisanych '><input class=imgoption type=image src=img/ukryj.gif onclick=\"newWindow($dialog_window_x,$dialog_window_y,'w_pion.php?id=$temp_id&action=wylacz')\"></a>";
					}
				}

				echo "<a title=' Usuń pion $temp_nazwa z bazy '><input class=imgoption type=image src=img/delete.gif onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_pion.php?id=$temp_id')\"></a>";
			_td();
			} 
			$i++;
		_tr();
	}
endtable();
include_once('paging_end.php');
} else pageheader("Baza pionów jest pusta");

listabaz($es_prawa,"".$pokaz_ikony."");
echo "&nbsp;<span style='float:left; margin-left:5px;'>";addbuttons("wstecz");echo "</span>";

startbuttonsarea("right");
$accessLevels = array("9");
if(array_search($es_prawa, $accessLevels)>-1){
	addownlinkbutton("'Dodaj pion'","Button1","button","newWindow(400,200,'d_pion.php')");
}
addbuttons("start");
endbuttonsarea();
include('body_stop.php');
include('js/menu.js');
?>
<script>HideWaitingMessage();</script>
</body>
</html>