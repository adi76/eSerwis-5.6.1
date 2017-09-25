<?php include_once('header.php'); ?>
<body>
<script>
createCookie('break_loop','');
</script>
<?php include('body_start.php'); 
//echo "w trakcie budowy...";echo "<br /><input type=button class=buttons value='Zamknij' onClick=\"self.close();\" >";
//exit;

require_once "classes/Ping.php";

$sql="SELECT * FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ";
if ($es_m==1) { } else $sql=$sql."(serwis_komorki.belongs_to=$es_filia) and  ";
$sql.=" (serwis_komorki.up_pion_id=serwis_piony.pion_id) ";
$sql.="AND (serwis_komorki.up_ip<>'') and (serwis_komorki.up_active=1) ";
$sql.="ORDER BY serwis_piony.pion_nazwa,serwis_komorki.up_nazwa ASC";

$result = mysql_query($sql, $conn) or die($k_b);
$count_rows = mysql_num_rows($result);
// paging
$totalrows = mysql_num_rows($result);
if ($showall==0) { $rps=$rowpersite; } else $rps=10000;
if(empty($page)){ $page = 1; }
$limitvalue = $page * $rps - ($rps);
$sql="SELECT * FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ";
if ($es_m==1) { } else $sql=$sql."(serwis_komorki.belongs_to=$es_filia) and  ";
$sql.=" (serwis_komorki.up_pion_id=serwis_piony.pion_id) ";
$sql.="AND (serwis_komorki.up_ip<>'') and (serwis_komorki.up_active=1) ";
$sql.=" ORDER BY serwis_piony.pion_nazwa,serwis_komorki.up_nazwa ASC "; //LIMIT $limitvalue, $rps";

//echo $sql;
//echo validateIpAddress("10.216.63.1");

$result = mysql_query($sql, $conn) or die($k_b);
$count_rows = mysql_num_rows($result);

if ($count_rows!=0) {
	pageheader("Sprawdzenie komunikacji z placówkami",1,0);
	ob_flush(); flush();
	
	startbuttonsarea("center");
		echo "Ilość prób nawiązania łączności: ";
		echo "<select name=zakres1 onChange=\"self.location=this.value; \">";
		echo "<option value='hd_check_ping.php?cnt=1'"; if (($_REQUEST[cnt]=='1') || ($_REQUEST[cnt]=='')) echo " SELECTED "; echo ">1</option>\n";
		echo "<option value='hd_check_ping.php?cnt=2'"; if ($_REQUEST[cnt]=='2') echo " SELECTED "; echo ">2</option>\n";
		echo "<option value='hd_check_ping.php?cnt=3'"; if ($_REQUEST[cnt]=='3') echo " SELECTED "; echo ">3</option>\n";		
		echo "<option value='hd_check_ping.php?cnt=4'"; if ($_REQUEST[cnt]=='4') echo " SELECTED "; echo ">4</option>\n";		
		echo "<option value='hd_check_ping.php?cnt=5'"; if ($_REQUEST[cnt]=='5') echo " SELECTED "; echo ">5</option>\n";				
		echo "</select>&nbsp;|&nbsp;";
		
		echo "&nbsp;<input type=button class=buttons value='Rozpocznij test' style='font-weight:bold;' onClick=\"createCookie('break_loop',''); self.location='hd_check_ping.php?cnt=$_REQUEST[cnt]&go=1';\" />";
		echo "&nbsp;<input type=button class=buttons value='Domyślne ustawienia' onClick=\"self.location='hd_check_ping.php?cnt=1';\" />";
		if ($_REQUEST[go]=='1') echo "&nbsp;<input type=button class=buttons id=przerwij value='Przerwij test' onClick=\"window.stop(); HideWaitingMessage();  this.style.display='none'; document.getElementById('_h3').style.display='none'; alert('Test przerwano'); \" />";
		
		echo "<span style='float:right;'>";
			echo "<input type=button class=buttons value='Zamknij' onClick=\"self.close();\" >";
		echo "</span>";
		
	endbuttonsarea();
	
	if ($_REQUEST[go]!='1') exit;
	
	//okheader("Automatyczne odświeżanie strony co 5 minut");
	echo "<h3 id=_h3 style='padding:10px; font-weight:normal; display:none;'>Automatyczne odświeżanie strony co 5 minut</h3>";

	?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
	
	starttable();
	//th("30;c;LP|;;Nazwa komórki<br /><sub>Adres<br />Opis</sub>|;c;Podsieć|;c;Nr WAN-portu|;;Telefon|;c;Test łączności|;c;Opcje",$es_prawa);
	echo "<tr><th rowspan=2 width=30 class=center>LP</th><th rowspan=2 class=left>Nazwa komórki</th><th rowspan=2 class=center>Podsieć</th><th rowspan=2 class=center>Nr WAN-portu</th><th rowspan=2 class=left>Telefon</th><th class=center colspan=2>Test łączności</th><th rowspan=2 class=center>Opcje</th></tr>";
	echo "<tr><th class=center>Router</th><th class=center>Serwer</th></tr>";
	
	$i = 0;
	$j = $page*$rowpersite-$rowpersite+1;
	
	$count_bez_godzin_pracy = 0;
	$x = 1;
	
	while ($newArray = mysql_fetch_array($result)) {
		
		$temp_id  				= $newArray['up_id'];
		$temp_nazwa				= $newArray['up_nazwa'];
		$temp_opis				= $newArray['up_opis'];
		$temp_adres				= $newArray['up_adres'];
		$temp_telefon			= $newArray['up_telefon'];
		$temp_ip				= $newArray['up_ip'];
		$temp_nrwanportu		= $newArray['up_nrwanportu'];
		$temp_stempel			= $newArray['up_stempel'];	
		$temp_belongs_to		= $newArray['belongs_to'];
		$temp_pion_id			= $newArray['up_pion_id'];
		$temp_umowa_id			= $newArray['up_umowa_id'];
		$temp_active_status 	= $newArray['up_active'];
		$temp_up_typ			= $newArray['up_typ'];
		$temp_up_kategoria		= $newArray['up_kategoria'];
		$temp_up_ko				= $newArray['up_kompleksowa_obsluga'];
		$temp_working_time		= $newArray['up_working_time'];	
		$temp_working_time_alt	= $newArray['up_working_time_alternative'];	
		$temp_working_time_start_date	= $newArray['up_working_time_alternative_start_date'];		
		$temp_working_time_stop_date	= $newArray['up_working_time_alternative_stop_date'];		
		$temp_typ_uslugi				= $newArray['up_typ_uslugi'];
		$temp_przypisanie_jedn			= $newArray['up_przypisanie_jednostki'];
		$temp_komorka_macierzysta 		= $newArray['up_komorka_macierzysta_id'];
		$temp_backupname				= $newArray['up_backupname'];
		$temp_ipserwera					= $newArray['up_ipserwera'];
		
		tbl_tr_highlight($i);
		$result1 = mysql_query("SELECT filia_nazwa FROM $dbname.serwis_filie WHERE filia_id=$temp_belongs_to", $conn) or die($k_b);
		list($temp_filia_nazwa)=mysql_fetch_array($result1);
			echo "<td class=center>";
			if ($temp_active_status=='2') echo "<font color=red>";
			if ($temp_active_status=='0') echo "<strike>";
			echo $j;
			//echo "-".$_COOKIE['break_loop'];
			if ($temp_active_status=='0') echo "</strike>";
			if ($temp_active_status=='2') echo "</font>";
			echo "</td>";
			$j++;
			
			list($pionnazwa)=mysql_fetch_array(mysql_query("SELECT pion_nazwa FROM $dbname.serwis_piony WHERE pion_id=$temp_pion_id LIMIT 1", $conn));

		td_(";w");
			echo "<a href=# class=normalfont title='Adres komórki: ".$temp_adres."' onclick=\"newWindow_r(700,600,'p_komorka_szczegoly.php?id=$temp_id')\"><b>";
			if ($temp_active_status=='0') echo "<strike>";
			if ($temp_active_status=='2') echo "<font color=red>";
			
			if ($temp_komorka_macierzysta>0) echo "<font color=grey>";			
			if (($temp_komorka_macierzysta>0) && ($wciecie_dla_podleglych_komorek==1)) echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			
			echo "".$pionnazwa." ".$temp_nazwa."";
			
			if ($temp_active_status=='2') echo " - komórka zamknięta";
			
			if ($temp_active_status=='2') echo "</font>";
			if ($temp_active_status=='0') echo "</strike>";
			if ($temp_komorka_macierzysta>0) echo "</font>";
			
			echo "</b></a>";
	/*		echo "<br /><sub>";
			if ($temp_active_status=='0') echo "<strike>";
			if ($temp_active_status=='2') echo "<font color=red>";			
			if (($temp_komorka_macierzysta>0) && ($wciecie_dla_podleglych_komorek==1)) echo "&nbsp;&nbsp;&nbsp;&nbsp;";echo "".$temp_adres."";
			if ($temp_active_status=='2') echo "</font>";			
			if ($temp_active_status=='0') echo "</strike>";
			echo "</sub>";
			
			echo "<br /><sub>";
			if ($temp_active_status=='0') echo "<strike>";
			if ($temp_active_status=='2') echo "<font color=red>";			
			if (($temp_komorka_macierzysta>0) && ($wciecie_dla_podleglych_komorek==1)) echo "&nbsp;&nbsp;&nbsp;&nbsp;";echo "".$temp_opis."";
			if ($temp_active_status=='2') echo "</font>";			
			if ($temp_active_status=='0') echo "</strike>";
			echo "</sub>";		
	*/
			$days = explode(";",$temp_working_time);
			
			$oneday1 = explode("@",$days[0]); 
			$oneday2 = explode("@",$days[1]); 
			$oneday3 = explode("@",$days[2]); 
			$oneday4 = explode("@",$days[3]); 
			$oneday5 = explode("@",$days[4]); 
			$oneday6 = explode("@",$days[5]); 
			$oneday7 = explode("@",$days[6]); 

			$gp_sa = 1;
			if (($oneday1[1]=='') && ($oneday2[1]=='') && ($oneday3[1]=='') && ($oneday4[1]=='') && ($oneday5[1]=='') && ($oneday6[1]=='') && ($oneday7[1]=='')) $gp_sa = 0;
			
			if ($oneday1[1]=='') $oneday1[1] = '-';
			if ($oneday2[1]=='') $oneday2[1] = '-';
			if ($oneday3[1]=='') $oneday3[1] = '-';
			if ($oneday4[1]=='') $oneday4[1] = '-';
			if ($oneday5[1]=='') $oneday5[1] = '-';
			if ($oneday6[1]=='') $oneday6[1] = '-';
			if ($oneday7[1]=='') $oneday7[1] = '-';
			
			// menu z godzinami pracy
			$opis_stanow = '<table class=stany>';
			$opis_stanow.= '<tr height=24><td colspan=2 class=center style=background-color:#FFFF7F><font color=black>Godziny pracy</b></font></td></tr>';
			
			$opis_stanow .= '<tr><td class=right width=100>Poniedziałek:</td><td><b>'.$oneday1[1].'</b></td></tr>';
			$opis_stanow .= '<tr><td class=right>Wtorek</td><td><b>'.$oneday2[1].'</b></td></tr>';
			$opis_stanow .= '<tr><td class=right>Środek:</td><td><b>'.$oneday3[1].'</b></td></tr>';
			$opis_stanow .= '<tr><td class=right>Czwartek:</td><td><b>'.$oneday4[1].'</b></td></tr>';
			$opis_stanow .= '<tr><td class=right>Piątek:</td><td><b>'.$oneday5[1].'</b></td></tr>';
			$opis_stanow .= '<tr><td class=right>Sobota:</td><td><b>'.$oneday6[1].'</b></td></tr>';
			$opis_stanow .= '<tr><td class=right>Niedziela:</td><td><b>'.$oneday7[1].'</b></td></tr>';
			$opis_stanow.= '</table>';
			
			
			if (($temp_working_time_start_date!='0000-00-00') && ($temp_working_time_stop_date!='0000-00-00')) {

				$days = explode(";",$temp_working_time_alt);
				
				$oneday1a = explode("@",$days[0]); 
				$oneday2a = explode("@",$days[1]); 
				$oneday3a = explode("@",$days[2]); 
				$oneday4a = explode("@",$days[3]); 
				$oneday5a = explode("@",$days[4]); 
				$oneday6a = explode("@",$days[5]); 
				$oneday7a = explode("@",$days[6]); 

				$gpa_sa = 1;
				if (($oneday1a[1]=='') && ($oneday2a[1]=='') && ($oneday3a[1]=='') && ($oneday4a[1]=='') && ($oneday5a[1]=='') && ($oneday6a[1]=='') && ($oneday7a[1]=='')) $gpa_sa = 0;
				
				
				if ($oneday1a[1]=='') $oneday1a[1] = '-';
				if ($oneday2a[1]=='') $oneday2a[1] = '-';
				if ($oneday3a[1]=='') $oneday3a[1] = '-';
				if ($oneday4a[1]=='') $oneday4a[1] = '-';
				if ($oneday5a[1]=='') $oneday5a[1] = '-';
				if ($oneday6a[1]=='') $oneday6a[1] = '-';
				if ($oneday7a[1]=='') $oneday7a[1] = '-';
				
				
				$alt_od = date('Y')."-".substr($temp_working_time_start_date,5,5);
				$alt_do = date('Y')."-".substr($temp_working_time_stop_date,5,5);
				
				// menu z godzinami pracy
				$opis_stanow .= '<table class=stany>';
				$opis_stanow .= '<tr height=24><td colspan=2 class=center style=background-color:#FFAA7F><font color=black>Godziny pracy (alternatywne)</b><br />obowiązują od: <b>'.$alt_od.' - '.$alt_do.'</b></font></td></tr>';
				
				$opis_stanow .= '<tr><td class=right width=100>Poniedziałek:</td><td><b>'.$oneday1a[1].'</b></td></tr>';
				$opis_stanow .= '<tr><td class=right>Wtorek</td><td><b>'.$oneday2a[1].'</b></td></tr>';
				$opis_stanow .= '<tr><td class=right>Środek:</td><td><b>'.$oneday3a[1].'</b></td></tr>';
				$opis_stanow .= '<tr><td class=right>Czwartek:</td><td><b>'.$oneday4a[1].'</b></td></tr>';
				$opis_stanow .= '<tr><td class=right>Piątek:</td><td><b>'.$oneday5a[1].'</b></td></tr>';
				$opis_stanow .= '<tr><td class=right>Sobota:</td><td><b>'.$oneday6a[1].'</b></td></tr>';
				$opis_stanow .= '<tr><td class=right>Niedziela:</td><td><b>'.$oneday7a[1].'</b></td></tr>';
				$opis_stanow.= '</table>';
			
			}
			//echo "<br />";
//			if ($temp_backupname=='') $temp_backupname='</b><i><font color=grey>nie zdefiniowano</font></i>';
//			echo "<b><font color=green>$temp_backupname</font></b>";
		_td();

		echo "<td class=center>";
		if ($temp_active_status=='2') echo "<font color=red>";
		if ($temp_active_status=='0') echo "<strike>";
		echo $temp_ip;
		if ($temp_active_status=='0') echo "</strike>";
		if ($temp_active_status=='2') echo "</font>";
		echo "</td>";

		echo "<td class=center>";
		if ($temp_active_status=='2') echo "<font color=red>";
		if ($temp_active_status=='0') echo "<strike>";
		echo $temp_nrwanportu;
		if ($temp_active_status=='0') echo "</strike>";
		if ($temp_active_status=='2') echo "</font>";
		echo "</td>";

		td_(";;");
			echo $temp_telefon;
		_td();
	
		$serwer=0;
		$router=0;

		$_cnt = 1;
		if ($_REQUEST[cnt]=='1') $_cnt = 1;
		if ($_REQUEST[cnt]=='2') $_cnt = 2;
		if ($_REQUEST[cnt]=='3') $_cnt = 3;
		if ($_REQUEST[cnt]=='4') $_cnt = 4;
		if ($_REQUEST[cnt]=='5') $_cnt = 5;
		
		if ($temp_ipserwera>0) {
			if ((validateIpAddress("".$temp_ip.".".$temp_ipserwera)==1) || (validateIpAddress("".$temp_ip.".".$temp_ipserwera)==1)) {

				$ping_s = Net_Ping::factory();
				if(PEAR::isError($ping_s)) {
					echo $ping_s->getMessage();
				} else {
					$ping_s->setArgs(array('count' => $_cnt));
				}

				$retval_s = $ping_s->ping($temp_ip.".".$temp_ipserwera);
				
				if ($retval_s->_loss==0) {
					$serwer = "1";
					$router = "1";
				} 
				
				if (($retval_s->_loss>0) && ($retval_s->_loss<100)) {
					$serwer = "1p";
				}
				
				if ($retval_s->_loss==100) {
					$serwer = 0;
				}
			
			} else $serwer='X';
		} else $serwer="-1";
	
		if ((validateIpAddress("".$temp_ip.".1")==1) || (validateIpAddress("".$temp_ip.".1")==1)) {
			if ($serwer=="0") {
			
				$ping_r = Net_Ping::factory();
				if(PEAR::isError($ping_r)) {
					echo $ping_r->getMessage();
				} else {
					$ping_r->setArgs(array('count' => $_cnt));
				}

				$retval_r = $ping_r->ping($temp_ip.".1");
				
				if ($retval_r->_loss==0) {
					$router = "1";
				} 
				
				if (($retval_r->_loss>0) && ($retval_r->_loss<100)) {
					$router = "1p";
				}
				
				if ($retval_r->_loss==100) {
					$router = 0;
				}
				
			}
			
		} else $router="X";
		//echo validateIpAddress("".$temp_ip.".1")."<br />";
		
		td_(";c;");
			//echo $retval_r->_transmitted."/".$retval_r->_received."/".$retval_r->_loss;
			switch ($router) {
				case "X" : echo "<font color=red>błedny adres IP<br /></font><b>".$temp_ip.".1</b>";  break;
				case "1" : echo "<img class=imgoption src=img/on.gif align=top title='Jest kontakt z routerem ".$temp_ip.".1'>"; break;
				case "1p" : echo "<img class=imgoption src=img/on_partial.gif align=top title='Jest słaby kontakt z routerem ".$temp_ip.".1 | ".$retval_r->_loss."% utraconych pakietów'>"; break;
				case "0" : echo "<img class=imgoption src=img/off.gif align=top title='Nie ma kontaktu z routerem ".$temp_ip.".1'>"; break;
			}
		_td();			
		
		td_(";c;");
			//echo $retval_s->_transmitted."/".$retval_s->_received."/".$retval_s->_loss;
			switch ($serwer) {
				case "X" : echo "<font color=red>błedny adres IP</font><br /><b>".$temp_ip.".".$temp_ipserwera."</b>"; break;
				case "1" : echo "<img class=imgoption src=img/on.gif align=top title='Jest kontakt z serwerem ".$temp_ip.".".$temp_ipserwera."'>";; break;
				case "1p" : echo "<img class=imgoption src=img/on_partial.gif align=top title='Jest słaby kontakt z serwerem ".$temp_ip.".".$temp_ipserwera." | ".$retval_s->_loss."% utraconych pakietów'>";; break;
				case "0" : echo "<img class=imgoption src=img/off.gif align=top title='Nie ma kontaktu z serwerem ".$temp_ip.".".$temp_ipserwera."'>"; break;
				case "-1": echo "<img width=16 height=16 class=imgoption src=img/question.gif align=top title='Nie zdefiniowano ostatniego członu adresu IP serwera'>"; break;
			}			
		_td();
		
		//if ($temp_id==51) { 
/*		?>
		
		<script>
		
			var ref<?php echo $temp_id; ?> = setInterval(function() { 
				$('#r_<?php echo $temp_id; ?>').load('check_ping.php?typ=R&router=<?php echo $temp_ip.".1"; ?>&randval='+Math.random()+'');
				$('#s_<?php echo $temp_id; ?>').load('check_ping.php?typ=S&serwer=<?php echo $temp_ip.".".$temp_ipserwera; ?>&randval='+Math.random()+'');
			}, 5000);
	
		</script>
		<?php
*/
	//	}
		td_(";c");
		
			if ($temp_active_status=='2') 		
				if ($gp_sa==1) echo "<a class='normalfont title' href=# title='$opis_stanow'><input class=imgoption type=image src=img/godziny_pracy.gif></a>";

			if ($temp_active_status!='2') 		
				if ($gp_sa==1) echo "<a class='normalfont title' href=# title='$opis_stanow' onclick=\"newWindow_r(800,600,'e_komorka.php?select_id=$temp_id'); return false;\"><input class=imgoption type=image src=img/godziny_pracy.gif></a>";

			echo "<a title=' Edycja danych o $temp_nazwa '><input class=imgoption type=image src=img/edit.gif onclick=\"newWindow_r(800,600,'e_komorka.php?select_id=$temp_id'); return false;\"></a>";

		_td();
		ob_flush();	flush();
		$i++;
		$x++;
		
	_tr();
}
endtable();

//include_once('paging_end.php');
} else { 
	errorheader("Baza komórek jest pusta lub brak pozycji spełniających wybrane kryteria");
}

//listabaz($es_prawa,"".$pokaz_ikony."");
/*echo "<span style='float:left; margin-left:5px;'>";addbuttons("wstecz");echo "</span>";

if ($count_rows>0) {
	startbuttonsarea("right");
	echo "<form action=do_xls_htmlexcel_slownik_komorki.php METHOD=POST target=_blank>";		
		echo "<input type=hidden name=nr value='$es_nr'>";
		addownsubmitbutton("'Export do XLS'","refresh_");
	echo "</form>";
	endbuttonsarea();
}
*/
ob_flush(); flush();

startbuttonsarea("right");
addbuttons("zamknij");
endbuttonsarea();
echo "<br />";
echo "<br />";

include('body_stop.php');
//include('js/menu.js');
?>
<meta http-equiv="REFRESH" content=300;url=<?php echo "$linkdostrony";?>hd_check_ping.php?cnt=<?php echo $_REQUEST[cnt]; ?>&go=1">
<script>
document.getElementById('_h3').style.display='';
$(document).ready(function() { $('a.title').cluetip({splitTitle: '|'}); });
</script>
<script>HideWaitingMessage();</script>
</body>
</html>