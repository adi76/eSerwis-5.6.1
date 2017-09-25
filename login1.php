<?php
session_start();
session_unset();
session_destroy();
include('cfg_adres.php');
include('cfg_wersja.php');
//if ($_SERVER['SERVER_PORT']!=443) {$d=$linkdostrony . 'login.php?key=eSerwis'; header("Location: $d"); }
if (($key!='5c18tpdr749nkh60w3y2sbxvqfjzmg0ng3zwxq95ypdmj67tc8vkb2hsf14r') && ($key!='eSerwis')) header("Location: ".$linkdostrony."");
$past = time() - 3600;
foreach ($_COOKIE as $key => $value) setcookie($key, $value, $past,'');
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<html>
<head>
	<title>Logowanie do bazy eSerwis <?php if ($_SERVER['SERVER_PORT']==443) { echo "| połączenie szyfrowane"; }  else { echo "| połączenie nieszyfrowane"; } ?></title>
	<link rel="stylesheet" type="text/css" href="css/login.css">
<?php if ($_GET[wk]==1) { ?>
	<link rel="stylesheet" type="text/css" href="js/VirtualKeyboard/keyboard.css">
	<script type="text/javascript" src="js/VirtualKeyboard/keyboard_min.js" charset="UTF-8"></script>
<?php } ?>
	<script type="text/javascript" src="js/login_min.js" charset="UTF-8"></script>
</head>
<body onLoad="document.getElementById('username').focus();">
<div id="header">&nbsp;<img src="img/key.gif" border="0" align="absmiddle" style="margin-right:4px" width="16" height="16">Logowanie do bazy eSerwis
<span style="align:right; vertical-align:absmiddle; font-size:10;">&nbsp;|&nbsp;
<?php if ($_SERVER['SERVER_PORT']==443) { echo "<font color=green>połączenie szyfrowane</font>&nbsp;"; }  else { echo "<font color=red>połączenie nieszyfrowane</font>&nbsp;"; } ?>
</span>
</div>
<div id="header-hr"><hr class="linia"></div>
<div id="horizon">
	<div id="content">
		<div class="bodytext">
			<form action="login_check.php" name="logowanie" method="POST" onSubmit="return InfoAboutPasswordStrength();">
			<div id="passwordbox">
				<input tabindex="1" class="keyboardInput" style="width:310px;margin:0 0 0.3em 0;border:1px solid #B4B8C2;padding:3 3 3 3;font-size:20px;font-family:'Courier New' Tahoma Verdana Arial; font-weight:bold; height:33px; color:#2D2D2D;" id="username" type="text" name="login" size="12" onFocus="this.style.border='2px solid #F39983'" onBlur="this.style.border='1px solid #B4B8C2';" onKeyDown="if GetKey(event)==13 document.getElementById('password').focus();"/>		
			</div>
			<input tabindex="2" class="keyboardInput" style="width:310px;margin:0 0 0.3em 0;border:1px solid #B4B8C2;padding:5 3 3 3;font-size:20px;font-family:'Courier New' Tahoma Verdana Arial; font-weight:bold; height:33px;color:#2D2D2D;" type="password" id="password" name="haslo" size="22" onFocus="this.style.border='2px solid #F39983'" onBlur="this.style.border='1px solid #B4B8C2';" onkeyup="passwordStrength(this.value)" />
			<div id="passwordStrength" class="strength0" title="Wskaźnik siły hasła"></div>
			<div style="text-align:right;margin-right:40px">
			<span id="wkon" style="float:left; position:relative; top:15px;">
				<a class="smalltext" href="login1.php?key=5c18tpdr749nkh60w3y2sbxvqfjzmg0ng3zwxq95ypdmj67tc8vkb2hsf14r&wk=1" onClick="document.getElementById('wkon').style.display='none'; document.getElementById('wkoff').style.display='';">Włącz wirtualną klawiaturę</a>
			</span>
			<span id="wkoff" style="float:left; position:relative; top:15px;">
				<a class="smalltext" href="login1.php?key=5c18tpdr749nkh60w3y2sbxvqfjzmg0ng3zwxq95ypdmj67tc8vkb2hsf14r&wk=0" onClick="document.getElementById('wkon').style.display=''; document.getElementById('wkoff').style.display='none';">Wyłącz wirtualną  klawiaturę</a>
			</span>
			<br /><br /><input type="checkbox" name="hdim" id="hdim">Po zalogowaniu, autoryzuj się w bazie HDIM
			<input tabindex="3" type="submit" id="submit" name="submitlogin" class="button" value="Zaloguj" />
			<input tabindex="4" type="button" id="close" name="zam" class="button" value="Zakończ" onClick="javascript:if(confirm('Czy napewno chcesz zakończyć pracę z bazą eSerwis ?')) self.close();"/>
			</div>
			</form>
		
		</div>
	</div>
</div>
<div id="footer-hr"><hr class="linia"></div>
<div id="footer">
				<script>
				/*	document.write('<font size="1" color="#2A2C32">Używana przez Ciebie przeglądarka:</font><font size="1" color="');
					if (((browserName=='Microsoft Internet Explorer') && (majorVersion<7)) ||
						((browserName=='Firefox') && (majorVersion<3)) ||
						((browserName=='Safari') && (majorVersion<5)) ||
						((browserName=='Opera')) ||
						((browserName=='Chrome') && (majorVersion<14)) 
						) {
							document.write('#FF0000');
							document.write('"><b> '+browserName+' '+majorVersion+'</b></font><br />');					
							document.write('<font size="1" color="FF0000">Zalecana przeglądarka: min. <b>Internet Explorer 7</b>, <b>Firefox 3</b>,<b> Safari 5</b>, <b>Chrome 14</b><br />Niektóre elementy bazy mogą działać lub wyświetlać się niepoprawnie</font>');
					} else {
						document.write('#38B838');
						document.write('"> '+browserName+' '+majorVersion+'</font>');	
					}
				*/
				</script>
				<font size="1" color="#666666">
				<?php echo "eSerwis, wersja $wersja "; ?>
				<br />Autor: 				
				    <SCRIPT LANGUAGE="JavaScript">
					<!-- Begin
					user = "maciej.adrjanowicz";
					site = "gmail.com";
					document.write('<a style="color:#666666; text-decoration:none;" href=\"mailto:' + user + '@' + site + '?subject=eSerwis\">');
					document.write('Maciej Adrjanowicz <br />(pracownik Postdata 01-10-2002 -> 31-08-2015)' + '</a>');
					// End -->
					</SCRIPT>
					
				</font>
</div>
<?php if ($_GET[wk]==1) { ?>
	<script>
		document.getElementById('wkon').style.display='none'; document.getElementById('wkoff').style.display='';
	</script>
<?php } else { ?>
	<script>
		document.getElementById('wkon').style.display=''; document.getElementById('wkoff').style.display='none';
	</script>
<?php } ?>

<script>
function createCookie(name,value,days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name,"",-1);
}

eraseCookie('mz_priorytet_rozpoczecia');
eraseCookie('mz_priorytet_zakonczenia');
eraseCookie('mz_wf');
eraseCookie('wz_priorytet_rozpoczecia');
eraseCookie('mz_do');
eraseCookie('mz_ws');
eraseCookie('wz_priorytet_zakonczenia');
eraseCookie('wz_nz');
eraseCookie('wz_do');
eraseCookie('wz_p');
eraseCookie('wz_ws');
eraseCookie('wz_r');
eraseCookie('wz_wf');
eraseCookie('mz_p');
eraseCookie('mz_nz');
eraseCookie('mz_rnz');
eraseCookie('mz_r');
eraseCookie('wz_n');
eraseCookie('wz_rnz');

</script>
</body>
</html>