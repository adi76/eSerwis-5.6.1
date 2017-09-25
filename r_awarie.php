<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 
	?><script>ShowWaitingMessage('','a');</script><?php ob_flush(); flush();
	include('body_start.php');
	$okres_od=$_GET[okres_od];
	$okres_do=$_GET[okres_do];
	$sql_a = "SELECT * FROM $dbname.serwis_awarie WHERE ";
	if ($es_m==1) { } else { $sql_a=$sql_a."belongs_to=$es_filia and "; }
	$sql_a=$sql_a."((awaria_datazgloszenia BETWEEN '$okres_od 00:00:00' AND '$okres_do 23:59:59') or (awaria_datazamkniecia BETWEEN '$okres_od 00:00:00' AND '$okres_do 23:59:59'))";
	$result_a = mysql_query($sql_a, $conn) or die($k_b);
	$count_rows=mysql_num_rows($result_a);
	// paging
	$totalrows = $count_rows;
	if ($showall==0) {
	  $rps=$rowpersite;
	} else $rps=10000;
	if(empty($page)){ $page = 1; }
	$limitvalue = $page * $rps - ($rps);
	$sql_a="SELECT * FROM $dbname.serwis_awarie WHERE belongs_to=$es_filia and ((awaria_datazgloszenia BETWEEN '$okres_od 00:00:00' AND '$okres_do 23:59:59') or (awaria_datazamkniecia BETWEEN '$okres_od 00:00:00' AND '$okres_do 23:59:59')) ORDER BY awaria_datazgloszenia DESC LIMIT $limitvalue, $rps";
	$result_a = mysql_query($sql_a, $conn) or die($k_b);
	// koniec - paging
	if ($count_rows!=0) {
		pageheader("Historia awarii w okresie",1,1);
		infoheader("<b>".$okres_od."</b> - <b>".$okres_do."</b>");
				
		?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
		
		startbuttonsarea("center");
		if ($showall==0) {
			echo "<a class=paging href=r_awarie.php?showall=1&paget=$page&okres_od=$okres_od&okres_do=$okres_do&submit=$submit>Pokaż wszystko na jednej stronie</a>";
		} else {
			echo "<a class=paging href=r_awarie.php?showall=0&page=$paget&okres_od=$okres_od&okres_do=$okres_do&submit=$submit>Dziel na strony</a>";	
			}
			
		echo "<a href=# class=paging1>";
		echo "| Łącznie: <b>$totalrows pozycji</b>";
		echo "</a>";
	
		endbuttonsarea();
		starttable();
		th("30;c;LP|;;Miejsce awarii|100;;Nr zgłoszenia|115;;Osoba zgłaszająca<br />Data zgłoszenia|115;;Osoba zamykająca<br />Data zamknięcia|120;c;Czas trwania awarii|80;c;Status|40;c;Opcje",$es_prawa);

		$i = 0;
		$j = $page*$rowpersite-$rowpersite+1;
		
		while ($newArray = mysql_fetch_array($result_a)) {
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
	
			tbl_tr_highlight($i);
			$i++;
			td("30;c;".$j."");
			td_(";nw;");
				//$wynik = mysql_query("SELECT up_id FROM $dbname.serwis_komorki WHERE (up_nazwa='$temp_gdzie') and (belongs_to=$es_filia) LIMIT 1", $conn);
				//list($temp_up_id)=mysql_fetch_array($wynik);

				$sql_up = "SELECT up_id, up_pion_id FROM $dbname.serwis_komorki WHERE (up_nazwa='$temp_gdzie') and (belongs_to=$es_filia) LIMIT 1";
				$wynik = mysql_query($sql_up, $conn) or die($k_b);
				$dane_up = mysql_fetch_array($wynik);
				$temp_up_id = $dane_up['up_id'];
				$temp_pion_id = $dane_up['up_pion_id'];
				
				// nazwa pionu z id pionu
				$sql_get_pion = "SELECT pion_nazwa FROM $dbname.serwis_piony WHERE (pion_id='$temp_pion_id') LIMIT 1";
				$wynik_get_pion = mysql_query($sql_get_pion, $conn) or die($k_b);
				$dane_get_pion = mysql_fetch_array($wynik_get_pion);
				$temp_pion_nazwa = $dane_get_pion['pion_nazwa'];
				// koniec ustalania nazwy pionu
	
				echo "<a class=normalfont title=' Szczegółowe informacje o $temp_pion_nazwa $temp_gdzie ' onclick=\"newWindow_r(700,600,'p_komorka_szczegoly.php?id=$temp_up_id')\" href=#>$temp_pion_nazwa $temp_gdzie</a>";
			_td();	
			td(";;".$temp_nrzgl."");
			td_("115;;".$temp_osobar."<br />");
				if ($temp_datao!='0000-00-00 00:00:00') echo substr($temp_datao,0,16); 
				if ($temp_datao=='0000-00-00 00:00:00') echo "&nbsp;"; 
			_td();
			td_("115;;".$temp_osobaz."<br />");
				if ($temp_dataz!='0000-00-00 00:00:00') echo substr($temp_dataz,0,16); 
				if ($temp_dataz=='0000-00-00 00:00:00') echo ""; 
			_td();

			td(";c;".calculate_datediff($temp_datao,$temp_dataz,"dgm")."");

			if ($temp_status==0) td("70;c;<b>otwarte</b>");
			if ($temp_status==1) td("70;c;<b>zamknięte</b>");
			if ($temp_status==2) td("70;c;<b>anulowane</b>");				
			td_("40;c;");
				echo "<a title=' Szczegółowe informacje o $temp_gdzie '><input class=imgoption type=image src=img/detail.gif onclick=\"newWindow_r(700,600,'p_komorka_szczegoly.php?id=$temp_up_id')\"></a>";	
			_td();
			$j++;
	_tr();
}
endtable();
include_once('paging_end.php');
?>

<script>HideWaitingMessage();</script>

<?php 
} else errorheader("Nie znaleziono pozycji spełniających podane przez Ciebie kryteria");

startbuttonsarea("right");
oddziel();
addlinkbutton("'Zmień kryteria'","main.php?action=hawo");
addbuttons("start");
endbuttonsarea();
include('body_stop.php');
} else {
br();
pageheader("Historia awarii w okresie");
starttable("40%");
echo "<form name=ruch action=r_awarie.php method=GET>";	
tbl_empty_row();
	tr_();
		td_colspan(2,'c');
			echo "<b>Podaj zakres dat<br /><br /></b>";
		_td();
	_tr();
	tr_();
		td("150;c;od dnia");
		td("150;c;do dnia");
	_tr();

	$r1 = Date('Y');
	$m1 = Date('m');
	$d1 = '01';
	
	$r2 = Date('Y');
	$m2 = Date('m');
	
	if ($m2==1) $d2=31;
	if ($m2==2) $d2=29;
	if ($m2==3) $d2=31;
	if ($m2==4) $d2=30;
	if ($m2==5) $d2=31;
	if ($m2==6) $d2=30;
	if ($m2==7) $d2=31;
	if ($m2==8) $d2=31;
	if ($m2==9) $d2=30;
	if ($m2==10) $d2=31;
	if ($m2==11) $d2=30;
	if ($m2==12) $d2=31;

	$data1=$r1.'-'.$m1.'-'.$d1;
	$data2=$r1.'-'.$m1.'-'.$d2;

	tr_();
		td_img(";c");
			echo "<input class=wymagane size=10 maxlength=10 type=text id=okres_od name=okres_od value=$data1 onkeypress='return handleEnter(this, event);' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\">";	
			echo "<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif width=16 height=16 border=0></a>";
			if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('okres_od').value='".Date('Y-m-d')."'; return false;\">";
		_td();
		td_img(";c");
			echo "<input class=wymagane size=10 maxlength=10 type=text id=okres_do name=okres_do value=$data2 onkeypress='return handleEnter(this, event);' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\">";
			echo "<a tabindex=-1 href=javascript:cal11.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif width=16 height=16 border=0></a>";
			if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('okres_do').value='".Date('Y-m-d')."'; return false;\">";
		_td();	
	_tr();		
	tbl_empty_row();
	endtable();
	
	startbuttonsarea("center");
	addownsubmitbutton("'Pokaż'","submit");

	oddziel();
	echo "<div style='float:left'>";
	
	addbuttons('start');
	echo "</div>";
	
	endbuttonsarea();
	echo "</form>";	
?>
<script language="JavaScript">
var cal1 = new calendar1(document.forms['ruch'].elements['okres_od']);
	cal1.year_scroll = true;
	cal1.time_comp = false;
var cal11 = new calendar1(document.forms['ruch'].elements['okres_do']);
	cal11.year_scroll = true;
	cal11.time_comp = false;
</script>

<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("ruch");
  frmvalidator.addValidation("okres_od","req","Nie podano daty poczatkowej");
  frmvalidator.addValidation("okres_do","req","Nie podano daty końcowej");
  frmvalidator.addValidation("okres_od","numerichyphen","Użyłeś niedozwolonych znaków w polu \"od dnia\"");
  frmvalidator.addValidation("okres_do","numerichyphen","Użyłeś niedozwolonych znaków w polu \"do dnia\"");
</script>
<?php } ?>
<script>HideWaitingMessage('a');</script>
</body>
</html>