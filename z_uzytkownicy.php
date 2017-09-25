<?php include_once('header.php'); ?>
<body>
<?php include('body_start.php');
$sql="SELECT * FROM $dbname.serwis_uzytkownicy";
if ($es_m!=1) { 
	$sql = $sql." WHERE (belongs_to=$es_filia) and (belongs_to<>'99') "; 
} else {
	$sql = $sql." WHERE (belongs_to<>'99') "; 
}

$sql = $sql." ORDER BY user_last_name ASC";
$result = mysql_query($sql, $conn) or die($k_b);
$totalrows = mysql_num_rows($result);
if ($showall==0) { $rps=$rowpersite; } else $rps=10000;
if(empty($page)){ $page = 1; }
$limitvalue = $page * $rps - ($rps);
$sql="SELECT * FROM $dbname.serwis_uzytkownicy";
//if ($es_m!=1) { $sql = $sql." WHERE belongs_to=$es_filia"; }
if ($es_m!=1) { 
	$sql = $sql." WHERE (belongs_to=$es_filia) and (belongs_to<>'99') "; 
} else {
	$sql = $sql." WHERE (belongs_to<>'99') "; 
}
$sql = $sql." ORDER BY user_last_name ASC LIMIT $limitvalue, $rps";
//echo $sql;

$result = mysql_query($sql, $conn) or die($k_b);
if ($totalrows!=0) {
pageheader("Przeglądanie bazy podległych pracowników",1,1);
	
?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();

startbuttonsarea("center");
if ($showall==0) {
echo "<a class=paging href=$PHP_SELF?showall=1&paget=$page>Pokaż wszystko na jednej stronie</a>";
} else {
	echo "<a class=paging href=$PHP_SELF?showall=0&page=$paget>Dziel na strony</a>";	
}
echo "| Łącznie: <b>$totalrows pozycji</b>";
endbuttonsarea();
starttable();
th("30%;;Imię i nazwisko (login)<span style=float:right>Strona startowa</span><br />Adres zamieszkania<span style=float:right>Menu główne w Helpdesk</span>|;c;Filia|;;Telefon<br /><sub>Adres email</sub>|50;c;Otrzymywanie<br />emaili|;c;Uprawnienia|;c;Status konta|;c;Widok po zalogowaniu|150;c;Opcje",$es_prawa);
$i = 0;
while ($newArray = mysql_fetch_array($result)) {
	$temp_id  			= $newArray['user_id'];
	$temp_login			= $newArray['user_login'];
	$temp_pass			= $newArray['user_pass'];
	$temp_first_name	= $newArray['user_first_name'];
	$temp_last_name		= $newArray['user_last_name'];
	$temp_email			= $newArray['user_email'];
	$temp_email_allow	= $newArray['user_allow_emails'];
	$temp_phone			= $newArray['user_phone'];
	$temp_rank			= $newArray['user_ranking'];
	$temp_belongs_to	= $newArray['belongs_to'];
	$temp_logged		= $newArray['user_logged'];
	$temp_ip			= $newArray['user_ip'];
	$temp_host			= $newArray['user_host'];
	$temp_last			= $newArray['user_lastlogin'];
	$temp_um			= $newArray['user_master'];
	$temp_lock			= $newArray['user_locked'];
	$temp_sprzedaz		= $newArray['user_allow_sell'];
	$temp_ulica			= $newArray['user_ulica'];
	$temp_kod			= $newArray['user_kod_pocztowy'];
	$temp_miejscowosc	= $newArray['user_miejscowosc'];	
	$temp_menu_type		= $newArray['user_menu_type'];	
	$temp_startpage		= $newArray['user_startpage'];	
	$temp_mminhd		= $newArray['user_mainmenu_in_helpdesk'];	
		
	tbl_tr_highlight($i);	
		list($temp_filia_nazwa)=mysql_fetch_array(mysql_query("SELECT filia_nazwa FROM $dbname.serwis_filie WHERE filia_id=$temp_belongs_to LIMIT 1",$conn));
		td_(";");
			echo "<b>".$temp_first_name." ".$temp_last_name." (".$temp_login.")</b>";
			
			echo "<span style='float:right;'>";
			if ($temp_startpage==0) echo "<font color=black>strona główna</font>";
			if ($temp_startpage==1) echo "<font color=grey>przeglądanie zgłoszeń</font>";
			echo "</span>";
			
				$r40 = mysql_query("SELECT count(user_id) FROM $dbname.serwis_uzytkownicy WHERE (UPPER(user_first_name)='".strtoupper($temp_first_name)."') and (UPPER(user_last_name)='".strtoupper($temp_last_name)."') LIMIT 1", $conn) or die($k_b);	
				list($czy_jest_dubel)=mysql_fetch_array($r40);
				
				if ($czy_jest_dubel>1) echo "<font color=red><b> - Dubel imienia i nazwiska w bazie pracowników</b></font>";
			if (($temp_ulica!='') && ($temp_kod!='') && ($temp_miejscowosc!='')) {
				echo "<br />$temp_ulica, $temp_kod $temp_miejscowosc";
			} else { echo "<br />"; }
			
			echo "<span style='float:right;'>";
			if ($temp_mminhd==0) echo "<font color=red>NIE</font>";
			if ($temp_mminhd==1) echo "<font color=green>TAK</font>";
			echo "</span>";
			
		_td();
		
		td("120;ct;".$temp_filia_nazwa."|250;;".$temp_phone."<br /><a class=normalfont href='mailto:".$temp_email."'>".$temp_email."</a>");
		
		if ($temp_email_allow==0) td(";c;NIE");
		if ($temp_email_allow==1) td(";c;<b>TAK</b>");
		
		td_("150;c");
			$usertype = "nieokreślony";
			if ($temp_rank==2) $usertype='Helpdesk';
			if ($temp_rank==0) $usertype='Zwykły';
			if ($temp_rank==1) $usertype='Zaawansowany';
			if ($temp_rank==9) $usertype='Administrator'; 	
			if ($temp_rank==9) echo "<b>";
			echo "$usertype";
			if ($temp_rank==9) echo "</b>";
			if ($temp_um==1) { 
				$master="*";	
				echo "<b>$master</b>";
				echo "";
			}
			echo "&nbsp;";
			if ($temp_sprzedaz=='1') {
				echo "<a href=# style=cursor:hand title='Użytkownik może sprzedawać towary/usługi'><img class=imgoption type=image src=img/sell.gif border=0 width=16 width=16></a>";
			} else {
				//echo "<a href=# style=cursor:hand title='Użytkownik nie może sprzedawać towarów/usług'><img class=imgoption type=image src=img/sell.gif border=0></a>";
			}
		_td();
		td_("80;c");
			$dddd1 = Date('Y-m-d H:i:s');
			if (gethours($temp_last,$dddd1)>8) {
				$wynik=mysql_query("UPDATE $dbname.serwis_uzytkownicy SET user_logged=0 WHERE user_id = '$temp_id' LIMIT 1", $conn) or die($k_b);	
			$temp_logged=0;
			}
			if ($temp_logged==1) {
				echo "<b><a href=# style=cursor:hand title='Ostatnio zalogowany : $temp_last\nZ adresu : $temp_ip\nNazwa komputera : $temp_host'><img class=imgoption type=image src=img/on.gif border=0 width=16 width=16></a></b>";
			} else { 
				echo "<a href=# title='Ostatnio zalogowany : $temp_last\nZ adresu : $temp_ip\nNazwa komputera : $temp_host'><img class=imgoption type=image src=img/off.gif border=0 width=16 width=16></a>";
			}
			if ($temp_lock==0) {
			echo "<a title=' Konto użytkownika: $temp_first_name $temp_last_name jest włączone '><input class=imgoption type=image src=img/unlocked.png></a>";
			} else {
				echo "<a title=' Konto użytkownika: $temp_first_name $temp_last_name jest wyłączone '><input class=imgoption type=image src=img/locked.png></a>";
			}
		_td();
		td_("40;c");
			if ($temp_menu_type==0) {
				echo "prosty";
			} else {
				echo "zaawansowany";
			}
			
			//if ($temp_startpage==0) echo "<hr /><font color=grey>strona główna</font>";
			//if ($temp_startpage==1) echo "<hr /><font color=grey>przeglądanie zgłoszeń</font>";
			
		_td();		
		td_(";c");
			echo "<a title=' Edytuj dane o pracowniku $temp_first_name $temp_last_name '><input class=imgoption type=image src=img/edit.gif onclick=\"newWindow(600,600,'e_uzytkownika.php?select_id=$temp_id&all=1&login=".urlencode($temp_login)."')\">";
			if (($currentuser!="$temp_first_name $temp_last_name") && (($es_prawa==1) || ($es_prawa==9))) {
			//	echo "<a title=' Usuń pracownika $temp_first_name $temp_last_name z bazy '><input class=imgoption type=image src=img/delete.gif onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_uzytkownika.php?select_id=$temp_id&l=$temp_logged')\"></a>";
			}
			if (($currentuser!="$temp_first_name $temp_last_name") && (($es_prawa==1) || ($es_prawa==9))) {
				if ($temp_lock==1) {
				echo "<a title=' Włącz konto użytkownika: $temp_first_name $temp_last_name '><input class=imgoption type=image src=img/unlocked.png onclick=\"newWindow($dialog_window_x,$dialog_window_y,'e_uzytkownika_lock.php?id=$temp_id&l=0')\"></a>";
				} else {
					echo "<a title=' Wyłącz konto użytkownika: $temp_first_name $temp_last_name '><input class=imgoption type=image src=img/locked.png onclick=\"newWindow($dialog_window_x,$dialog_window_y,'e_uzytkownika_lock.php?id=$temp_id&l=1')\"></a>";
				}
			}
			
			$accessLevels = array("9");
			if (array_search($es_prawa, $accessLevels)>-1) {
				echo "<a title=' Usuń konto użytkownika: $temp_first_name $temp_last_name z bazy '><input class=imgoption type=image src=img/delete.gif  onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_pracownik.php?select_id=$temp_id')\"></a>";
			}
			
				echo "<a title=' Pokaż historię logowań użytkownika : $temp_first_name $temp_last_name '><input  class=imgoption type=image src=img/log.png onclick=\"newWindow_r(800,600,'p_uzytkownik_log.php?logid=$temp_id&showall=0')\"></a>";
				
				echo "<a title=' Pojazdy użytkownika : $temp_first_name $temp_last_name '><input class=imgoption type=image src=img/car.gif onclick=\"newWindow_r(800,600,'hd_z_pojazdy.php?userid=$temp_id');\"></a>";
				
				if (($es_nr==$kierownik_nr) || ($is_dyrektor==1)) {
					echo "<a title=' Pokaż listę dostępu czasowego do bazy Helpdesk dla pracownika $temp_first_name $temp_last_name'><input class=imgoption type=image src=img/zgl_dodaj_dostep_czasowy.gif onclick=\"newWindow_r(800,400,'hd_z_dostep_czasowy.php?userid=$temp_id&user=".urlencode($temp_first_name.' '.$temp_last_name)."&active=1');\"></a>";
				}
				
		_td();
	_tr();
	$i++;
}
endtable();
include_once('paging_end.php');
} else pageheader("Baza pracowników jest pusta");

listabaz($es_prawa,"".$pokaz_ikony."");
echo "&nbsp;<span style='float:left; margin-left:5px;'>";addbuttons("wstecz");echo "</span>";

startbuttonsarea("right");
if (($es_prawa==1) || ($es_prawa==9)) {
	addownlinkbutton("'Pokaż logowania podległych użytkowników'","Button1","button","newWindow_r(700,600,'p_uzytkownik_log.php?logid=0&showall=0')");
}
$accessLevels = array("9");
if(array_search($es_prawa, $accessLevels)>-1){
	addownlinkbutton("'Dodaj pracownika'","Button1","button","newWindow_r(600,600,'d_uzytkownika.php')");
}

addbuttons("start");
endbuttonsarea();	
include('body_stop.php');
include('js/menu.js'); 

?>

<script>HideWaitingMessage();</script>

</body>
</html>