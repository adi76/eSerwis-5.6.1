<?php include_once('header.php'); ?>
<body>
<?php include('body_start.php');
$sql="SELECT * FROM $dbname.serwis_fz ";
if ($pokaz=='fs') $sql.="WHERE (fz_is_fs='on') ";
if ($pokaz=='fk') $sql.="WHERE (fz_is_fk='on') ";
if ($pokaz=='ds') $sql.="WHERE (fz_is_ds='on') ";
$sql.= "ORDER BY fz_nazwa";
$result = mysql_query($sql, $conn) or die($k_b);
$count_rows = mysql_num_rows($result);
if ($count_rows!=0) {
pageheader("Przeglądanie bazy firm zewnętrznych",1,1);
?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
startbuttonsarea("center");
if ($showall==0) {
	echo "<a class=paging href=$phpfile?showall=1&paget=$page&pokaz=$pokaz>Pokaż wszystko na jednej stronie</a>";
} else {
	echo "<a class=paging href=$phpfile?showall=0&page=$paget&pokaz=$pokaz>Dziel na strony</a>";	
}
echo "| Łącznie: <b>$count_rows pozycji</b>";
endbuttonsarea();
startbuttonsarea("center");
hr();
echo "Pokaż: ";
	echo "<a class=paging href=$phpfile?showall=$showall&page=1&paget=$paget&pokaz=fs>Firmy serwisowe</a>";
	echo "<a class=paging href=$phpfile?showall=$showall&page=1&paget=$paget&pokaz=fk>Firmy kurierskie</a>";
	echo "<a class=paging href=$phpfile?showall=$showall&page=1&paget=$paget&pokaz=ds>Dostawców sprzętu</a>";
	echo "<a class=paging href=$phpfile?showall=$showall&page=$page&paget=$paget&pokaz=all>Wszystkie firmy</a>";
endbuttonsarea();
// paging
$totalrows = mysql_num_rows($result);
if ($showall==0) { $rps=$rowpersite; } else $rps=10000;
if(empty($page)){ $page = 1; }
$limitvalue = $page * $rps - ($rps);
$sql="SELECT * FROM $dbname.serwis_fz ";
if ($pokaz=='fs') $sql.="WHERE (fz_is_fs='on') ";
if ($pokaz=='fk') $sql.="WHERE (fz_is_fk='on') ";
if ($pokaz=='ds') $sql.="WHERE (fz_is_ds='on') ";
$sql=$sql."ORDER BY fz_nazwa LIMIT $limitvalue, $rps";
$result = mysql_query($sql, $conn) or die($k_b);
// koniec - paging
starttable();
th("30;c;LP|;;Nazwa firmy<br /><sub>Opis</sub>|;;Adres|;;Telefon<br />Fax|35;;Email<br />WWW|70;c;Typ firmy<br /><sub>Zakupy przez</sub>|60;c;Opcje",$es_prawa);
$i = 0;
$j = $page*$rowpersite-$rowpersite+1;
while ($newArray = mysql_fetch_array($result)) {
	$temp_id  			= $newArray['fz_id'];				$temp_nazwa			= $newArray['fz_nazwa'];
	$temp_adres			= $newArray['fz_adres'];			$temp_telefon		= $newArray['fz_telefon'];
	$temp_fax			= $newArray['fz_fax'];				$temp_email			= $newArray['fz_email'];
	$temp_www			= $newArray['fz_www'];				$temp_belongs_to	= $newArray['belongs_to'];
	$temp_opis			= $newArray['fz_opis'];				$temp_is_fs			= $newArray['fz_is_fs'];
	$temp_is_fk			= $newArray['fz_is_fk'];			$temp_is_ds			= $newArray['fz_is_ds'];
	$temp_realizacja	= $newArray['fz_realizacja_zakupu'];

	tbl_tr_highlight($i);
		td("30;c;".$j."");
		$j++;
		td_(";w");
			echo "$temp_nazwa";
			if ($temp_opis!='') echo "<br /><sub>$temp_opis</sub>";
		_td();
		td(";;".$temp_adres."|;;".$temp_telefon."<br />".$temp_fax."");
		td_(";c");
			if ($temp_email!='') echo "<a title='$temp_email' href=mailto:$temp_email><input class=imgoption type=image src=img/fz_email.gif></a>";
			if ($temp_www!='') echo "<a title='$temp_www' href=$temp_www target=_blank><input class=imgoption type=image src=img/fz_http.gif></a>";	
		_td();
		td_(";c");	
			if ($temp_is_fs=='on') echo "<b><a title=' Firma serwisowa' class=normalfont href=#> FS </a></b>";
			if ($temp_is_fk=='on') echo "<b><a title=' Firma kurierska' class=normalfont href=#> FK </a></b>";
			if ($temp_is_ds=='on') echo "<b><a title=' Dostawca sprzętu' class=normalfont href=#> DS </a></b>";		
			if ($temp_is_ds=='on') {
				if ($temp_realizacja!='') echo "<br /><sub>$temp_realizacja</sub>"; else echo "<br /><sub>nie zdefiniowano</sub>";
			}
		_td();
		td_("65;c");
			$accessLevels = array("9");
			if (array_search($es_prawa, $accessLevels)>-1) {
				echo "<a title=' Edytuj dane o firmie zewnętrznej $temp_nazwa '><input class=imgoption type=image src=img/edit.gif onclick=\"newWindow(460,380,'e_firma_zewnetrzna.php?select_id=$temp_id&all=1')\"></a>";	
				echo "<a title=' Usuń firmę zewnętrzną $temp_nazwa z bazy '><input class=imgoption type=image src=img/delete.gif onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_firma_zewnetrzna.php?select_id=$temp_id')\"></a>";
			} 
			echo "<a title=' Drukuj informacje o firmie $temp_nazwa ' href=#><input class=imgoption type=image src=img/print.gif onclick=\"newWindow_r(800,600,'p_firma_zewnetrzna.php?fzid=$temp_id')\"></a>";
		_td();
		$i++;
	_tr();
}
endtable();
include_once('paging_end.php');
} else {
	errorheader("Baza firm zewnętrznych jest pusta");
	?><meta http-equiv="REFRESH" content="<?php echo $http_refresh; ?>;url=<?php echo "$linkdostrony";?>main.php"><?php		
}

listabaz($es_prawa,"".$pokaz_ikony."");
echo "&nbsp;<span style='float:left; margin-left:5px;'>";addbuttons("wstecz");echo "</span>";

startbuttonsarea("right");
$accessLevels = array("9");
if(array_search($es_prawa, $accessLevels)>-1){
	addownlinkbutton("'Dodaj firmę zewnętrzną'","Button1","button","newWindow(460,380,'d_firma_zewnetrzna.php')");
}
addbuttons("start");
endbuttonsarea();
include('body_stop.php');
include('js/menu.js');
?>
<script>HideWaitingMessage();</script>
</body>
</html>