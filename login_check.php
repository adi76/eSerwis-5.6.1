<?php
function getBrowser() {
	$u_agent = $_SERVER['HTTP_USER_AGENT'];
	$bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
		$platform = 'mac';
	}
	elseif (preg_match('/windows|win32/i', $u_agent)) {
		$platform = 'windows';
    }

    // Next get the name of the useragent yes separately and for good reason.
        if (preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
        {
            $bname = 'Internet Explorer';
            $ub = "MSIE";
        }
        elseif (preg_match('/Firefox/i',$u_agent))
        {
            $bname = 'Mozilla Firefox';
            $ub = "Firefox";
        }
        elseif (preg_match('/Chrome/i',$u_agent))
        {
            $bname = 'Google Chrome';
            $ub = "Chrome";
        }
        elseif (preg_match('/Safari/i',$u_agent))
        {
            $bname = 'Apple Safari';
            $ub = "Safari";
        }
        elseif (preg_match('/Opera/i',$u_agent))
        {
            $bname = 'Opera';
            $ub = "Opera";
        }
        elseif (preg_match('/Netscape/i',$u_agent))
        {
            $bname = 'Netscape';
            $ub = "Netscape";
        }

        // Finally get the correct version number.
        $known = array('Version', $ub, 'other');
        $pattern = '#(?<browser>' . join('|', $known) .
        ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
        if (!preg_match_all($pattern, $u_agent, $matches)) {
            // we have no matching number just continue
        }

        // See how many we have.
        $i = count($matches['browser']);
        if ($i != 1) {
            //we will have two since we are not using 'other' argument yet
            //see if version is before or after the name
            if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
                $version= $matches['version'][0];
            }
            else {
                $version= $matches['version'][1];
            }
        }
        else {
            $version= $matches['version'][0];
        }

        // Check if we have a number.
        if ($version==null || $version=="") {$version="?";}

        return array(
            'userAgent' => $u_agent,
            'name'      => $bname,
            'version'   => $version,
            'platform'  => $platform,
            'pattern'    => $pattern
        );
}

require_once 'cfg_eserwis.php';	
if (sprawdz($_POST['login'],md5($_POST['haslo']))) {
 	// logowanie poprawne
	session_unset();
	session_register('es_autoryzacja');
	session_register('es_imie');
	session_register('es_nazwisko');
	session_register('es_prawa');
	session_register('es_filia');
	session_register('es_nr');
	session_register('es_m'); // master user
	session_register('es_style');
	session_register('es_skrot');
	session_register('es_date');
	session_register('es_login');	
	session_register('es_admin_info');
	session_register('es_admin_info_a');
	session_register('es_block');
	session_register('adminname');
	session_register('currentuser');
	session_register('pokaz_ikony');
	session_register('allow_sell');
	session_register('wersja_light');
	session_register('kierownik_nr');
	session_register('es_menu_type');
	session_register('es_rps');	
	session_register('iin');
	session_register('es_telefon');
	session_register('es_spage');
	session_register('es_mminhd');

	session_register('es_hdim');
	session_register('es_auth_hdim');
	$es_hdim = 0;
	
	
	if ($_POST['hdim']=='on') {
		$es_auth_hdim = 1;
	} else {
		$es_auth_hdim = 0;
	}
	
	$es_hdim=0;
	$wersja_light=$_POST[wl];
	
/*	session_register('tablice_up_nazwa');
	session_register('read_from_mysql_to_tables');
	session_register('read_from_mysql_to_tables_time');
	$read_from_mysql_to_tables=false;
*/

	$es_autoryzacja		= true;
	$es_imie 			= $imie;
	$es_nazwisko 		= $nazwisko;
	$iin				= $imie." ".$nazwisko;
	$es_prawa 			= $prawa;
	$es_filia			= $filia;
	$es_nr				= $nr;
	$es_m				= $um;
	$es_style			= $styl;
	$es_skrot			= $fskrot;
	$es_date			= Date('Y-m-d');
	$es_login			= $loginname;
	$es_admin_info		= $info;
	$es_admin_info_a	= $info_a;
	$es_block			= $block;
	$pokaz_ikony		= $pikony;
	$allow_sell			= $allowsell;
	require_once 		'cfg_eserwis_admin.php';
	$currentuser		= $es_imie." ".$es_nazwisko;
	$dddd 				= Date('Y-m-d H:i:s');
	$dd 				= Date('Y-m-d');
	$ip 				= getenv('REMOTE_ADDR');
	$es_menu_type		= $menutype;
	$es_telefon			= $utelefon;
	$es_spage			= $spage;
	$es_mminhd			= $mminhd;

	if ($_COOKIE["es_rps_".$es_nr.""]>0) {
		$es_rps				= $_COOKIE["es_rps_".$es_nr.""];
	} else {
		$es_rps				= $rowpersite;
	}
	
	$_COOKIE['wz_priorytet_zakonczenia']=null;
	$_COOKIE['wz_priorytet_rozpoczecia']=null;
	$_COOKIE['mz_priorytet_zakonczenia']=null;
	$_COOKIE['mz_priorytet_rozpoczecia']=null;

	
	// czyszczenie starych plików cookie
	
	$cookies = $_COOKIE;

	while (list($key, $value) = each($cookies)) {
		//echo "key = ".$key." 					 value = ".$value."<br />";
		
		if (substr($key,0,7)=='eSerwis') { setcookie(''.$key.'','',time()-3600,'/'); }
		if (substr($key,0,12)=='nowy_status_') { setcookie(''.$key.'','',time()-3600,'/'); }
		if (substr($key,0,11)=='wpisane_wc_') { setcookie(''.$key.'','',time()-3600,'/'); }
		if (substr($key,0,4)=='tsn_') { setcookie(''.$key.'','',time()-3600,'/'); }
		if (substr($key,0,7)=='tuwagi_') { setcookie(''.$key.'','',time()-3600,'/'); }
		if (substr($key,0,15)=='czy_rozwiazane_') { setcookie(''.$key.'','',time()-3600,'/'); }
		
	}
	
	// koniec czyszczenia starych plików cookie
	
	if ($es_menu_type==0) { 
		$default_main_menu = 'main_simple.php'; 
	} else { 
		if ($es_spage == 0) $default_main_menu = 'main.php'; 
		if ($es_spage == 1) $default_main_menu = 'hd_p_zgloszenia.php?sa=0&p5=BZ&s=AD'; 
		if ($es_spage == 2) $default_main_menu = 'hd_check_backups_new.php?'; 
		if ($es_spage == 3) $default_main_menu = 'hd_check_backups_new.php?view=error'; 
	}
	
/*	$brouwser = $_SERVER['HTTP_USER_AGENT'];
	if ((strstr($brouwser,"MSIE 5.0")) 
		|| (strstr($brouwser,"MSIE 5.5")) 
		|| (strstr($brouwser,"MSIE 6.0")) 
		|| (strstr($brouwser,"MSIE 7.0"))) $brouwser = "MS Internet Explorer"; 
	if (strstr($brouwser,"Mozilla"))  $brouwser = "Mozilla FireFox"; 
	if (strstr($brouwser,"Opera")) 	 $brouwser = "Opera"; 
*/
	$ua = getBrowser();
	$brouwser = $ua['name'] . " " . $ua['version'];
	
// logowanie wejść
	$sql_log="INSERT INTO $dbname.serwis_uzytkownicy_log VALUES ('','$REMOTE_ADDR','".date("Y-m-d H:i:s")."','$currentuser',$es_filia,'$brouwser')";
	$zapisz = mysql_query($sql_log, $conn) or die($k_b);
	
// aktualiacja stanu użytkownika w bazie	
	$sql="UPDATE $dbname.serwis_uzytkownicy SET user_logged=1, user_ip = '$REMOTE_ADDR' , user_host = '$REMOTE_HOST' , user_lastlogin = '$dddd' WHERE user_id = '$es_nr' LIMIT 1";
	$wynik=mysql_query($sql, $conn) or die($k_b);

// sprawdzenie czy konto nie jest wyłączone
	$sql_lock = "SELECT user_locked FROM $dbname.serwis_uzytkownicy WHERE user_id='$es_nr' LIMIT 1";
	list($konto_wylaczone) = mysql_fetch_array(mysql_query($sql_lock,$conn));
	
// wyciągnij imie i nazwisko kierownika danej filii
	$sql_k = "SELECT filia_leader FROM $dbname.serwis_filie WHERE (filia_id=$es_filia) LIMIT 1";
	list($kierownik_nr) = mysql_fetch_array(mysql_query($sql_k,$conn));

// pobierz nr wersja zaakceptowanej przez użytkownika
	if ($testowa!=1) {
		$sql_k = "SELECT user_zaakceptowane_info_o_wersji FROM $dbname.serwis_uzytkownicy WHERE (user_id=$es_nr) LIMIT 1";
		list($nr_zaakceptowanej_wersji) = mysql_fetch_array(mysql_query($sql_k,$conn));
		$aktualny_nr_wersji = $wnr;
	}
	
// blacklist check	
	$host = getenv("REMOTE_ADDR");
	list ($octet1, $octet2, $octet3, $octet4) = split ("\.", $host, 4);	

	$sql = "SELECT * FROM $dbname.serwis_czarna_lista";
	$wynik = mysql_query($sql, $conn) or die($k_b);	

	while ($newArray = mysql_fetch_array($wynik)) {
		$temp_id  			= $newArray['bl_id'];
		$temp_ip			= $newArray['bl_ip'];
		$temp_active		= $newArray['bl_active'];
		$temp_kto			= $newArray['bl_osobadodajaca'];
		$temp_enddate		= $newArray['bl_enddate'];
		
		$part1 = 0;		$part2 = 0;		$part3 = 0;		$part4 = 0;
		list ($octet1a, $octet2a, $octet3a, $octet4a) = split ("\.", trim($temp_ip), 4);	
	
		if ($octet1a!='*') { if ($octet1a==$octet1) $part1=1; else $part1=0; } else $part1=1;
		if ($octet2a!='*') { if ($octet2a==$octet2) $part2=1; else $part2=0; } else $part2=1;
		if ($octet3a!='*') { if ($octet3a==$octet3) $part3=1; else $part3=0; } else $part3=1;
		if ($octet4a!='*') { if ($octet4a==$octet4) $part4=1; else $part4=0; } else $part4=1;

		$suma = $part1+$part2+$part3+$part4;
		
		if (($suma==4) && ($temp_active==1))
		{	
			$blockip = true;
			if (($temp_enddate!='0000-00-00') && (strtotime($dd)>$temp_enddate)) $blockip = false;
		
		} else $blockip = false;
				?>
				<script>
				alert('wait')
				</script>
				<?php

		
		if ($blockip) {
				?>
				<script>
				alert('Dostęp do bazy eSerwis z tego adresu IP jest zablokowany')
				</script>
				<?php
		}
	}

	if ($currentuser!=$adminname) {
		if ($es_block==1) {
			?>
				<script>
				alert('Trwają prace konserwacyjne na bazie eSerwis.\n\nProszę spróbować zalogować się później')
				</script>
			<?php		
		}
	}

	if ($currentuser!=$adminname) {
		if ($konto_wylaczone==1) {
			?>
				<script>
				alert('Twoje konto zostało wyłączone. Skontaktuj się z administratorem serwisu')
				</script>
			<?php		
		}
	}

	if ($testowa!=1) {
		if (($nr_zaakceptowanej_wersji!=$aktualny_nr_wersji) || ($nr_zaakceptowanej_wersji=='')) {
			?>
			<script>
				function newWindow_r99(x,y,_c){if(window.screen){var ah=screen.availHeight-30;var aw=screen.availWidth-10;var xx=(aw-x)/2;xx=Math.round(xx);var yy=(ah-y)/2;yy=Math.round(yy);}var _11="scrollbars=yes, width="+x+", innerWidth="+x+", height="+y+", innerHeight="+y+", top="+yy+", screenX="+xx+", screenY="+yy+", left="+xx;var _12="eSerwis"+Math.round(Math.random()*10000);neww=window.open(_c,_12,_11);neww.focus();}
				
				newWindow_r99(800,600,'wersja_info.php?w=<?php echo $aktualny_nr_wersji; ?>');
			</script>
			<?php
		}
	}	

?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />	
<?php 
	if (($blockip) && ($currentuser!=$adminname)) {
?>
	<script>
	self.close();
	</script>
<?php
} elseif (($konto_wylaczone==1) && ($currentuser!=$adminname)) { 
?>
	<meta http-equiv="REFRESH" content="0;url=<?php echo "$linkdostrony";?>"><?php
} else {
?>
	<meta http-equiv="REFRESH" content="0;url=<?php echo "$linkdostrony";?><?php echo $default_main_menu; ?>">
<?php } ?>

<title>Logowanie do systemu eSerwis</title>		
<link rel="stylesheet" type="text/css" href="css/listmenu_h.css" id="listmenu-h" title="Menu horyzontalne" />
<script type="text/javascript" src="js/fsmenu.js"></script>
</head>
<body>
<?php
} else { 
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="REFRESH" content="0;url=<?php echo "$linkdostrony";?>login1.php">
	<title>Logowanie do systemu eSerwis</title>
</head>
<body>
<script>
alert('Błędnie podany login lub hasło. Spróbuj ponownie...')
</script>
<br />
<?php  
exit;
}	
?>
</body>
</html>