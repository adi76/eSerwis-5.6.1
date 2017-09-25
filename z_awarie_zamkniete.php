<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body>
<?php 
include_once('body_start.php');

function toUpper($string) {
	$string = strtr($string,'qwertyuiopasdfghjklzxcvbnm','QWERTYUIOPASDFGHJKLZXCVBNM');
	return (strtr($string, 'ęóąśłżźćń','ĘÓĄŚŁŻŹĆŃ' )); 
}; 

$state='close';
$sql="SELECT * FROM $dbname.serwis_awarie WHERE (";
if ($es_m!=1) {	$sql=$sql."(belongs_to=$es_filia) and "; }
$sql=$sql."((awaria_status=1) or (awaria_status=2))) ORDER BY awaria_datazamkniecia DESC";
$result = mysql_query($sql, $conn) or die($k_b);
$count_rows = mysql_num_rows($result);
// paging
$totalrows = mysql_num_rows($result);
if(empty($page)){ $page = 1; }
if ($showall==0) { $rps=$rowpersite; } else $rps=10000;
$limitvalue = $page * $rps - ($rps);
$sql=$sql." LIMIT $limitvalue, $rps";
$result = mysql_query($sql, $conn) or die($k_b);
// koniec - paging

if ($count_rows!=0) {
	pageheader("Przeglądanie zamkniętych zgłoszeń awarii łącz WAN-owskich",1,1);

	?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();

	startbuttonsarea("center");
	if ($showall==0) {
		echo "<a class=paging href=z_awarie_zamkniete.php?showall=1&paget=$page&nr=$_REQUEST[nr]&up=".urlencode($_REQUEST[up]).">Pokaż wszystko na jednej stronie</a>";
	} else {
		echo "<a class=paging href=z_awarie_zamkniete.php?showall=0&page=$paget&nr=$_REQUEST[nr]&up=".urlencode($_REQUEST[up]).">Dziel na strony</a>";	
	}

	echo "<a href=# class=paging1>";
	echo "| Łącznie: <b>$count_rows pozycji</b>";
	echo "</a>";
	
	endbuttonsarea();	
	starttable();
	th("30;c;LP|;;Miejsce awarii|;;Nr zgłoszenia|;;Osoba zgłaszająca<br />Data zgłoszenia|;;Osoba zamykająca<br />Data zamknięcia|;c;Czas trwania awarii|;c;Status|;c;Opcje",$es_prawa);
	$i = 0;
	$j = $page*$rowpersite-$rowpersite+1;
	
	while ($newArray = mysql_fetch_array($result)) {
		$temp_id  			= $newArray['awaria_id'];
		$temp_gdzie			= $newArray['awaria_gdzie'];
		$temp_nrwanportu	= $newArray['awaria_nrwanportu'];
		$temp_datao			= $newArray['awaria_datazgloszenia'];
		$temp_dataz			= $newArray['awaria_datazamkniecia'];
		$temp_nrzgl			= $newArray['awaria_nrzgloszenia'];
		$temp_ip			= $newArray['awaria_ip'];
		$temp_osobar		= $newArray['awaria_osobarejestrujaca'];
		$temp_osobaz		= $newArray['awaria_osobazamykajaca'];	
		$temp_status		= $newArray['awaria_status'];
		$temp_belongs_to	= $newArray['belongs_to'];

		$wynik = mysql_query("SELECT up_id,up_pion_id FROM $dbname.serwis_komorki WHERE (up_nazwa='$temp_gdzie') and (belongs_to=$es_filia) LIMIT 1", $conn) or die($k_b);
		list($temp_up_id, $temp_pion_id)=mysql_fetch_array($wynik);
				
		// nazwa pionu z id pionu
		$sql_get_pion = "SELECT pion_nazwa FROM $dbname.serwis_piony WHERE (pion_id='$temp_pion_id') LIMIT 1";
		$wynik_get_pion = mysql_query($sql_get_pion, $conn) or die($k_b);
		$dane_get_pion = mysql_fetch_array($wynik_get_pion);
		$temp_pion_nazwa = $dane_get_pion['pion_nazwa'];
		// koniec ustalania nazwy pionu
		
		if (($_REQUEST[nr]==$temp_nrzgl) && ($_REQUEST[up]==toUpper($temp_pion_nazwa.' '.$temp_gdzie))) {
			tbl_tr_color_with_border($i,'#FFFF55');
		} else tbl_tr_highlight($i);
		
		$i++;
			td("30;c;".$j."");
			td_(";;");		
				echo "<a class=normalfont title=' Szczegółowe informacje o $temp_pion_nazwa $temp_gdzie ' onclick=\"newWindow_r(700,600,'p_komorka_szczegoly.php?id=$temp_up_id')\" href=#>$temp_pion_nazwa $temp_gdzie</a>";
			_td();	
			td(";;".$temp_nrzgl."");
			td_(";;".$temp_osobar."<br />");
				if ($temp_datao!='0000-00-00 00:00:00') echo substr($temp_datao,0,16); 
				if ($temp_datao=='0000-00-00 00:00:00') echo "&nbsp;"; 
			_td();
			td_(";;".$temp_osobaz."<br />");
				if ($temp_dataz!='0000-00-00 00:00:00') echo substr($temp_dataz,0,16); 
				if ($temp_dataz=='0000-00-00 00:00:00') echo ""; 
			_td();
			td(";c;".calculate_datediff($temp_datao,$temp_dataz,"dgm")."");
			if ($temp_status==0) td("70;c;<b>otwarte</b>");
			if ($temp_status==1) td("70;c;<b>zamknięte</b>");
			if ($temp_status==2) td("70;c;<b>anulowane</b>");	
			td_(";c;");
			
				$r3 = mysql_query("SELECT zgl_nr FROM $dbname_hd.hd_zgloszenie WHERE (zgl_poledodatkowe1=$temp_nrzgl) and (belongs_to=$es_filia) and (zgl_status='9')", $conn_hd) or die($k_b);
				list($nr_zgl)=mysql_fetch_array($r3);
				
				if ($nr_zgl>0) { 
					$zgl_powiazane_z_hd=true; 				
				} else { 
					$zgl_powiazane_z_hd=false; 
				}
				
				// INCLUDE
				if ($zgl_powiazane_z_hd) { $LinkHDZglNr=$nr_zgl; include('linktohelpdesk.php'); }
			
				echo "<a title=' Szczegółowe informacje o $temp_gdzie '><input class=imgoption type=image src=img/detail.gif onclick=\"newWindow_r(700,600,'p_komorka_szczegoly.php?id=$temp_up_id')\"></a>";	
			_td();
			$j++;
		_tr();
	}
endtable();
include_once('paging_end.php');
} else {
	errorheader("Brak zamkniętych zgłoszeń awarii w bazie");
	?><meta http-equiv="REFRESH" content="<?php echo $http_refresh; ?>;url=<?php echo "$linkdostrony";?>main.php"><?php	
}
	
startbuttonsarea("right");
oddziel();
addlinkbutton("'Pokaż otwarte zgłoszenia'","z_awarie.php");
addbuttons("start");
endbuttonsarea();
include('body_stop.php'); 
?>

<script>HideWaitingMessage();</script>

</body>
</html>