<?php include_once('header.php'); ?>
<body>
<?php
if ($logid!=0) {
	$result = mysql_query("SELECT user_id,user_first_name,user_last_name FROM $dbname.serwis_uzytkownicy WHERE (user_id=$logid) LIMIT 1", $conn) or die($k_b);
	list($temp_id,$temp_first_name,$temp_last_name)=mysql_fetch_array($result);
	$uzytkownik = $temp_first_name.' '.$temp_last_name;	
}
if ($logid!=0) {
	$sql = "SELECT * FROM $dbname.serwis_uzytkownicy_log WHERE (log_username='$uzytkownik') ORDER BY log_time DESC";
} else {
	$sql = "SELECT * FROM $dbname.serwis_uzytkownicy_log ";
	if ($es_m==0) $sql.= "WHERE (log_filia=$es_filia) ";
	$sql.= "ORDER BY log_time DESC";
}

$result = mysql_query($sql, $conn) or die($k_b);	
$count_rows = mysql_num_rows($result);
if ($count_rows!=0) {
	if ($logid!=0) { pageheader("Historia logowań użytkownika $uzytkownik do bazy"); } else {
		pageheader("Historia logowań użytkowników do bazy");
	}
	$totalrows = mysql_num_rows($result);
	if ($showall==0) { $rps=$rowpersite; } else $rps=10000;
	if(empty($page)){ $page = 1;}
	$limitvalue = $page * $rps - ($rps);

	$sql="SELECT log_id,log_ip,log_time,log_httpagent,log_username FROM $dbname.serwis_uzytkownicy_log ";
	if ($logid!=0) {
		$sql=$sql."WHERE (log_username='$uzytkownik') ";
	} else {
		$sql .= "WHERE (log_filia=$es_filia) ";
	}
	$sql=$sql."ORDER BY log_time DESC LIMIT $limitvalue, $rps";
	$result = mysql_query($sql, $conn) or die($k_b);
	// koniec - paging
	startbuttonsarea("center");
	if ($showall==0) {
		echo "<a class=paging href=p_uzytkownik_log.php?showall=1&paget=$page&logid=$logid>Pokaż wszystko na jednej stronie</a>";
	} else {
		echo "<a class=paging href=p_uzytkownik_log.php?showall=0&page=$paget&logid=$logid>Dziel na strony</a>";	
	}

	echo "<a href=# class=paging1>";
	echo "| Łącznie: <b>$totalrows pozycji</b>";
	echo "</a>";
	
	endbuttonsarea();
	starttable();
	if ($logid!=0) {
		th("30;c;LP|115;;Data logowania|100;;Logowanie z adresu IP|;;Przeglądarka",$es_prawa);
	} else {
		th("30;c;LP|115;;Data logowania|120;;Nazwa użytkownika|100;;Logowanie z adresu IP|;;Przeglądarka",$es_prawa);
	}
	$i = 0;
	$j = $page*$rowpersite-$rowpersite+1;
	while (list($temp_id,$temp_ip,$temp_time,$temp_agent,$temp_name)=mysql_fetch_array($result)) {	
		tbl_tr_highlight($i);
			if ($logid!=0) {
				td("30;c;".$j."|;;".$temp_time."|;;".$temp_ip."|;;".$temp_agent."");
			} else {
				td("30;c;".$j."|;;".$temp_time."|;;".$temp_name."|;;".$temp_ip."|;;".$temp_agent."");
			}
		_tr();
		$i++;
		$j++;
	}
endtable();
include_once('paging_end.php');
} else {
	errorheader("Użytkownik nie logował się jeszcze do bazy");
}
startbuttonsarea("right");
if ($count_rows!=0) addownlinkbutton("'Wyczyść historię logowań'","Button1","button","newWindow($dialog_window_x,$dialog_window_y,'u_uzytkownik_log.php?id=$logid')");
addbuttons("zamknij");
endbuttonsarea();
?>	
</body>
</html>