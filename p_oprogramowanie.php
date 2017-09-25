<?php include_once('header.php'); ?>
<body>
<?php include('body_start.php');
$result = mysql_query("SELECT oprogramowanie_id, oprogramowanie_nazwa FROM $dbname.serwis_oprogramowanie WHERE oprogramowanie_ewidencja_id=$id ORDER BY oprogramowanie_nazwa", $conn) or die($k_b);
$count_rows = mysql_num_rows($result);
if ($count_rows!=0) {
	$result4 = mysql_query("SELECT ewidencja_id, ewidencja_typ, ewidencja_up_id,ewidencja_komputer_opis,ewidencja_komputer_sn  FROM $dbname.serwis_ewidencja WHERE (ewidencja_id=$id) LIMIT 1", $conn) or die($k_b);
	list($temp_id,$temp_rola_id,$temp_up_id,$temp_nazwa,$temp_sn)=mysql_fetch_array($result4);
	$result_r = mysql_query("SELECT rola_nazwa FROM $dbname.serwis_slownik_rola WHERE rola_id=$temp_rola_id LIMIT 1", $conn) or die($k_b);
	list($temp_rola_nazwa)=mysql_fetch_array($result_r);
	$result_l = mysql_query("SELECT up_nazwa FROM $dbname.serwis_komorki WHERE up_id=$temp_up_id LIMIT 1", $conn) or die($k_b);
	list($temp_up_nazwa)=mysql_fetch_array($result_l);
	pageheader("Przeglądanie oprogramowania zainstalowanego na");
	if ($temp_sn!='') { 
	infoheader("<b>".$temp_rola_nazwa." ".$temp_nazwa."<br /> (SN: ".$temp_sn.")</b><br /><br />Lokalizacja: <b>".$temp_up_nazwa."</b>");
	} else {
		infoheader("<b>".$temp_rola_nazwa." ".$temp_nazwa."<br /></b><br />Lokalizacja: <b>".$temp_up_nazwa."</b>");
	}
	starttable();
	th("30;c;LP|;;Nazwa oprogramowania|40;c;Opcje",$es_prawa);
	$i = 0;
	$j = 1;
	while (list($temp_id,$temp_nazwa)=mysql_fetch_array($result)) {
		tbl_tr_highlight($i);
		$i++;
		td("30;c;".$j."|;;".$temp_nazwa."");
		$j++;
		td_("40;c");
			echo "<a title=' Usuń $temp_nazwa z ewidencji na tym sprzęcie '><input class=imgoption type=image src=img/delete.gif onclick=\"newWindow(650,150,'u_oprogramowanie.php?id=$id&opr_id=$temp_id')\"></a>";
		_td();
	}
endtable();
} else errorheader("Na tym sprzęcie nie ma zainstalowanego żadnego oprogramowania");
echo "<input type=hidden name=id value=$id>";
startbuttonsarea("right");
$accessLevels = array("0","1","9");
if(array_search($es_prawa, $accessLevels)>-1){
	addownlinkbutton("'Dodaj oprogramowanie'","submit1","button","newWindow_r(700,600,'d_ewidencja_oprogramowanie.php?id=$id')");
}
addclosewithreloadbutton("Zamknij");
endbuttonsarea();
include('body_stop.php');
?>
</body>
</html>