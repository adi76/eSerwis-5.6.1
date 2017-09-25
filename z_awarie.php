<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body>
<?php include_once('body_start.php');
?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
require("classes/dateClass.php");

function toUpper($string) {
	$string = strtr($string,'qwertyuiopasdfghjklzxcvbnm','QWERTYUIOPASDFGHJKLZXCVBNM');
	return (strtr($string, 'ęóąśłżźćń','ĘÓĄŚŁŻŹĆŃ' )); 
}; 

$state='open';
$sql="SELECT * FROM $dbname.serwis_awarie WHERE (";
if ($es_m!=1) {
	$sql=$sql."(belongs_to=$es_filia) and ";
}
$sql=$sql."(awaria_status='0')) ORDER BY awaria_datazgloszenia DESC";
$result = mysql_query($sql, $conn) or die($k_b);
$count_rows = mysql_num_rows($result);
if ($count_rows!=0) {
	pageheader("Przeglądanie otwartych zgłoszeń awarii łączy WAN",1,1);
	
	startbuttonsarea("center");

	echo "<a href=# class=paging1>";
	echo "Łącznie: <b>$count_rows pozycji</b>";
	echo "</a>";
	
	endbuttonsarea();	
	starttable();
	if ($state=='open') {
		th("30;c;LP|;;Miejsce awarii|;;Nr WAN-portu|;;Nr zgłoszenia|;;Adres IP routera<br />Stan łącza|;;Osoba zgłaszająca<br />Data zgłoszenia|;c;Czas trwania awarii|;c;Status|;c;Opcje",$es_prawa);
	}
	if ($state=='close') {
		th("30;c;LP|;;Miejsce awarii|;;Nr WAN-portu|;;Nr zgłoszenia|;;Adres IP routera<br />Stan łącza|;;Osoba zgłaszająca<br />Data zgłoszenia|;c;Opcje",$es_prawa);
	}
	$i = 0;
	$j = 1;
	
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

		
		$wynik = mysql_query("SELECT up_id, up_pion_id FROM $dbname.serwis_komorki WHERE (up_nazwa='$temp_gdzie') and (belongs_to=$es_filia) LIMIT 1", $conn) or die($k_b);
		list($temp_up_id,$temp_pion_id)=mysql_fetch_array($wynik);
				
		// nazwa pionu z id pionu
		$sql_get_pion = "SELECT pion_nazwa FROM $dbname.serwis_piony WHERE (pion_id='$temp_pion_id') LIMIT 1";
		$wynik_get_pion = mysql_query($sql_get_pion, $conn) or die($k_b);
		$dane_get_pion = mysql_fetch_array($wynik_get_pion);
		$temp_pion_nazwa = $dane_get_pion['pion_nazwa'];
		
		if (($_GET[nr]==$temp_nrzgl) && ($_GET[up]==toUpper($temp_pion_nazwa.' '.$temp_gdzie))) {
			tbl_tr_color_with_border($i,'#FFFF55');
		} else tbl_tr_highlight($i);
		
		//tbl_tr_highlight($i);
	
			td("30;c;".$j."");
			td_(";;");

				// koniec ustalania nazwy pionu
	
				echo "<a class=normalfont title=' Szczegółowe informacje o $temp_pion_nazwa $temp_gdzie ' onclick=\"newWindow_r(700,600,'p_komorka_szczegoly.php?id=$temp_up_id')\" href=#>$temp_pion_nazwa $temp_gdzie</a>";
			_td();
			td(";;".$temp_nrwanportu."|;;".$temp_nrzgl."");
			td_(";;".$temp_ip."<br />");	

			// 12.05.2009 - wyłączenie sprawdzania czy działa łącze

				$cmd = shell_exec("ping -n 1 -w 2500 $temp_ip.1");
				$ping_results = explode(",",$cmd);
				$ping_results2 = explode(":",$cmd);
				if (eregi("Odebrane = 0", $ping_results[1], $out) or eregi("H",$ping_results2[2][1],$out)) {
					echo "<a title=' Nie działa lub router jest wyłączony '><img class=imgoption src=img/off.gif  align=top> - nie działa</a>";				
				}
				if (eregi("Odebrane = 1", $ping_results[1], $out)) {
					if (eregi("H",$ping_results2[2][1],$out)==FALSE) {
						echo "<a title=' Łącze działa prawidłowo '><img class=imgoption src=img/on.gif > - działa</a>";
					}
				}

			
			//echo "<i>f-kcja wyłączona</i>";	

			// koniec wyłączenia

			_td();
			$j++;

		if ($state=='open') {
			td_("115;;".$temp_osobar."<br />");
				if ($temp_datao!='0000-00-00 00:00:00') echo substr($temp_datao,0,16); 
				if ($temp_datao=='0000-00-00 00:00:00') echo "&nbsp;"; 
			_td();
		}
		td_(";c;");
			echo calculate_datediff($temp_datao,"0000-00-00 00:00:00","dgm");
			$CzasTrwania = calculate_datediff($temp_datao,"0000-00-00 00:00:00","m");
		_td();
			
		if ($temp_status==0) td("70;c;<b>otwarte</b>");
		if ($temp_status==1) td("70;c;<b>zamknięte</b>");
		if ($state=='close') {
			td_(";;".$temp_osobaz."<br />");
				if ($temp_dataz!='0000-00-00 00:00:00') echo "$temp_dataz"; 
				if ($temp_dataz=='0000-00-00 00:00:00') echo ""; 
			_td();
		}
		$wynik = mysql_query("SELECT up_id FROM $dbname.serwis_komorki WHERE up_nazwa='$temp_gdzie' LIMIT 1", $conn) or die($k_b);
		list($temp_up_id)=mysql_fetch_array($wynik);
		td_(";c;");

			if ($temp_nrzgl!='') {
				$r3 = mysql_query("SELECT zgl_nr FROM $dbname_hd.hd_zgloszenie WHERE (zgl_poledodatkowe1='$temp_nrzgl') and (belongs_to=$es_filia) and (zgl_status!='9')", $conn_hd) or die($k_b);
				list($nr_zgl)=mysql_fetch_array($r3);
			} 
			
			if ($nr_zgl>0) { 
				$zgl_powiazane_z_hd=true; 				
			} else { 
				$zgl_powiazane_z_hd=false; 
			}
			
			if ($zgl_powiazane_z_hd) {
				$LinkHDZglNr=$nr_zgl; include('linktohelpdesk.php');
			} else {
				echo "<a title='Zamknij zgłoszenie nr $temp_nrzgl'><input class=imgoption type=image src=img/accept.gif  onclick=\"newWindow(550,200,'z_awaria_zamknij.php?id=	$temp_id&czastrwania=$CzasTrwania')\"></a>";
			}
			
//			echo "<a title=' Anuluj zgłoszenie awarii w $temp_gdzie '><input class=imgoption type=image src=img/delete.gif  onclick=\"newWindow(550,120,'e_awaria_anuluj.php?id=$temp_id&czastrwania=$CzasTrwania')\"></a>";
			echo "<a title=' Szczegółowe informacje o $temp_gdzie '><input class=imgoption type=image src=img/detail.gif onclick=\"newWindow_r(700,600,'p_komorka_szczegoly.php?id=$temp_up_id')\"></a>";	
		_td();
		$i++;
	_tr();
	
	ob_flush(); 
	flush();
}
endtable();
okheader("Ostatnie odświeżenie strony: <b>".date("Y-m-d H:i")."</b><br /><font color=grey>(automatyczne odświeżanie strony co 5 minut)</font><br /><br /><input class=buttons type=button name=reloadpage value=\"Odśwież stronę\" onClick=\"window.location.reload(true);\">");
echo "<hr />";
?>
<meta http-equiv="REFRESH" content=300;url=<?php echo "$linkdostrony";?>z_awarie.php?nr=<?php echo "$_REQUEST[nr]"; ?>&up=<?php echo urlencode($_REQUEST[up]); ?>">
<?php

} else {
	errorheader("Brak otwartych zgłoszeń awarii w bazie");
}
startbuttonsarea("right");
oddziel();
echo "<span style='float:left'>";
echo "&nbsp;";
if ($_REQUEST[nr]>0) addbackbutton('Wróć do poprzedniego widoku');

echo "</span>";
addlinkbutton("'Pokaż zamknięte zgłoszenia'","z_awarie_zamkniete.php");
addownlinkbutton("'Zarejestruj awarię łącza WAN'","button","button","onclick=newWindow(600,220,'d_awaria.php')");

echo "<br />";

		
addbuttons("start");
endbuttonsarea();
include('body_stop.php'); 
?>
<script>HideWaitingMessage();</script>
</body>
</html>