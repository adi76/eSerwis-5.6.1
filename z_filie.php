<?php include_once('header.php'); ?>
<body>
<?php include('body_start.php'); 
$result = mysql_query("SELECT filia_id FROM $dbname.serwis_filie", $conn) or die($k_b);
$count_rows = mysql_num_rows($result);
if ($count_rows!=0) {
	pageheader("Przeglądanie filii",1,1);
	?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
	$totalrows = mysql_num_rows($result);
	if ($showall==0) { $rps=$rowpersite; } else $rps=10000;
	if(empty($page)){ $page = 1; }
	$limitvalue = $page * $rps - ($rps);
	$sql="SELECT filia_id,filia_nazwa,filia_adres,filia_kontakt,filia_leader,filia_skrot,filia_lokalizacja FROM $dbname.serwis_filie";
	$sql=$sql. " ORDER BY filia_nazwa LIMIT $limitvalue, $rps";
	$result = mysql_query($sql, $conn) or die($k_b);
	startbuttonsarea("center");
	if ($showall==0) {
		echo "<a class=paging href=$phpfile?showall=1&paget=$page>Pokaż wszystko na jednej stronie</a>";
	} else {
		echo "<a class=paging href=$phpfile?showall=0&page=$paget>Dziel na strony</a>";	
	}
	echo "| Łącznie: <b>$count_rows pozycji</b>";
	endbuttonsarea();
	starttable();
	th("30;c;LP|;;Nazwa filii|;;Adres filii|;;Lokalizacja|;;Telefon|;;Kierownik|;c;Skrót|40;c;Opcje;9",$es_prawa);
	$i = 0;
	$j = $page*$rowpersite-$rowpersite+1;
	while (list($temp_id,$temp_nazwa,$temp_adres,$temp_kontakt,$temp_kierownik_id,$temp_skrot,$temp_lok)=mysql_fetch_array($result)) {
		tbl_tr_highlight($i);
			td("30;c;".$j."|200;;".$temp_nazwa."|;;".$temp_adres."|;;".$temp_lok."|;;".$temp_kontakt."");
			$j++;
			td_(";;");
				$result_k = mysql_query("SELECT user_first_name, user_last_name FROM $dbname.serwis_uzytkownicy WHERE user_id=$temp_kierownik_id LIMIT 1", $conn) or die($k_b);
				list($temp_i,$temp_n)=mysql_fetch_array($result_k);
				echo "$temp_i $temp_n";
			_td();
			td("40;c;".$temp_skrot."");
			$accessLevels = array("9");
			if (array_search($es_prawa, $accessLevels)>-1) {
				td_("40;c");
					echo "<a title=' Edytuj dane o filii $temp_nazwa '><input class=imgoption type=image src=img/edit.gif  onclick=\"newWindow(450,260,'e_filia.php?id=$temp_id')\"></a>";
				_td();
			}
			$i++;
		_tr();
	}
endtable();
include_once('paging_end.php');
} else pageheader("Baza filii jest pusta");

listabaz($es_prawa,"".$pokaz_ikony."");
echo "&nbsp;<span style='float:left; margin-left:5px;'>";addbuttons("wstecz");echo "</span>";

startbuttonsarea("right");
$accessLevels = array("9");
if(array_search($es_prawa, $accessLevels)>-1){
	addownlinkbutton("'Dodaj filię'","Button1","button","newWindow(450,260,'d_filia.php')");
}
addbuttons("start");
endbuttonsarea();
include('body_stop.php');
include('js/menu.js');
?>
<script>HideWaitingMessage();</script>
</body>
</html>