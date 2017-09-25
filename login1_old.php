<?php
session_start();
session_unset();
session_destroy();
include('cfg_adres.php');
include('cfg_wersja.php');
//if ($_SERVER['SERVER_PORT']!=443) {$d=$linkdostrony . 'login.php?key=eSerwis'; header("Location: $d"); }
if (($key!='5c18tpdr749nkh60w3y2sbxvqfjzmg0ng3zwxq95ypdmj67tc8vkb2hsf14r') && ($key!='eSerwis')) header("Location: ".$linkdostrony."");
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<html>
<head>
<title>Logowanie do bazy eSerwis</title>
<script type="text/javascript" src="js/VirtualKeyboard/keyboard_min.js" charset="UTF-8"></script>
<link rel="stylesheet" type="text/css" href="js/VirtualKeyboard/keyboard.css">
<style>
#passwordStrength{height:10px; margin-left:90px; display:block;}
.strength0{	width:208px;background:#cccccc;}
.strength1{	width:50px;	background:#ff0000;}
.strength2{	width:100px;background:#ff5f5f;}
.strength3{	width:150px;background:#56e500;}
.strength4{	background:#4dcd00;	width:208px;}
.strength5{	background:#399800;	width:208px;}
</style>
<script>
function passwordStrength(password){
	var desc = new Array();
	desc[0] = "Bardzo słaba";desc[1] = "Słaba";desc[2] = "Całkiem dobra";desc[3] = "Średnia";desc[4] = "Wysoka";desc[5] = "Bardzo wysoka";
	var score   = 0;
	if (password.length > 6) score++;
	if ( ( password.match(/[a-z]/) ) && ( password.match(/[A-Z]/) ) ) score++;
	if (password.match(/\d+/)) score++;
	if ( password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/) )	score++;
	if (password.length > 12) score++;
	document.getElementById("passwordStrength").className = "strength" + score;
	document.getElementById("passwordStrength").title = "Siła hasła: " + desc[score];	
	return score;
}
function InfoAboutPasswordStrength() {
	if (document.getElementById('username').value=='') { alert('Nie wpisałeś nazwy użytkownika'); document.getElementById('username').focus(); return false; }
	if (document.getElementById('password').value=='') { alert('Nie wpisałeś hasła'); document.getElementById('password').focus(); return false; }
	var wynik = passwordStrength(document.getElementById('password').value);
	if ((wynik == 0) || (wynik == 1)) alert('Siła Twojego hasła jest niska. \r\n\r\n Zalecana jest zmiana hasła na bardziej skomplikowane');
	return true;
}
</script>
<script type="text/javascript" language="javascript">
function handleEnter(_1,_2){var _3=_2.keyCode?_2.keyCode:_2.which?_2.which:_2.charCode;if(_3==13){var i;for(i=0;i<_1.form.elements.length;i++){if(_1==_1.form.elements[i]){break;}}i=(i+1)%_1.form.elements.length;_1.form.elements[i].focus();return false;}else{return true;}}
function labelClick(cbObject){cbObject.checked=!cbObject.checked}
function changeField(value){var status=document.getElementById("przelacznik").checked;if (status) {document.getElementById("passwordbox").innerHTML="<input id=\"password-field\" class=\"inputbox\" type=\"password\" name=\"login\" />";} else {document.getElementById("passwordbox").innerHTML="<input id=\"password-field\" class=\"inputbox\" type=\"text\" name=\"login\" />";} setTimeout("document.logowanie.getElementById(\"login\".focus());",10);}
</script>
<script>
var nVer = navigator.appVersion;
var nAgt = navigator.userAgent;
var browserName  = navigator.appName;
var fullVersion  = ''+parseFloat(navigator.appVersion); 
var majorVersion = parseInt(navigator.appVersion,10);
var nameOffset,verOffset,ix;

// In MSIE, the true version is after "MSIE" in userAgent
if ((verOffset=nAgt.indexOf("MSIE"))!=-1) {
 browserName = "Microsoft Internet Explorer";
 fullVersion = nAgt.substring(verOffset+5);
}
// In Opera, the true version is after "Opera" 
else if ((verOffset=nAgt.indexOf("Opera"))!=-1) {
 browserName = "Opera";
 fullVersion = nAgt.substring(verOffset+6);
}
// In Chrome, the true version is after "Chrome" 
else if ((verOffset=nAgt.indexOf("Chrome"))!=-1) {
 browserName = "Chrome";
 fullVersion = nAgt.substring(verOffset+7);
}
// In Safari, the true version is after "Safari" 
else if ((verOffset=nAgt.indexOf("Safari"))!=-1) {
 browserName = "Safari";
 fullVersion = nAgt.substring(verOffset+7);
}
// In Firefox, the true version is after "Firefox" 
else if ((verOffset=nAgt.indexOf("Firefox"))!=-1) {
 browserName = "Firefox";
 fullVersion = nAgt.substring(verOffset+8);
}
// In most other browsers, "name/version" is at the end of userAgent 
else if ( (nameOffset=nAgt.lastIndexOf(' ')+1) < (verOffset=nAgt.lastIndexOf('/')) ) 
{
 browserName = nAgt.substring(nameOffset,verOffset);
 fullVersion = nAgt.substring(verOffset+1);
 if (browserName.toLowerCase()==browserName.toUpperCase()) {
  browserName = navigator.appName;
 }
}
// trim the fullVersion string at semicolon/space if present
if ((ix=fullVersion.indexOf(";"))!=-1) fullVersion=fullVersion.substring(0,ix);
if ((ix=fullVersion.indexOf(" "))!=-1) fullVersion=fullVersion.substring(0,ix);

majorVersion = parseInt(''+fullVersion,10);
if (isNaN(majorVersion)) {
 fullVersion  = ''+parseFloat(navigator.appVersion); 
 majorVersion = parseInt(navigator.appVersion,10);
}

//alert(browserName+' '+majorVersion);

</script>
<link rel="stylesheet" type="text/css" href="css/serwis.css" />
<style type="text/css">
.login{margin-left:auto;margin-right:auto;margin-top:7em;padding:10em;border:0px solid transparent;}
.login-form{text-align:left;width:365px;}
.form-block{margin-left:auto;margin-right:auto;margin-top:0em;border:1px solid #B4B8C2;background:#E5E6E9;padding-top:20px;padding-left:10px;padding-bottom:5px;padding-right:10px;}
.form-block1{margin-left:auto;margin-right:auto;border:1px solid #B4B8C2; background:#BBBDC5;padding-top:4px;padding-left:4px;padding-bottom:4px;padding-right:10px;font-weight:bold;color:#242424;height:14px;}
.inputlabel{float:left;width:70px;text-align:right;font-size:11px;margin:4px 6px 0 13;}
.inputbox{width:110px;margin:0 0 0.3em 0;border:1px solid #B4B8C2;padding:3 3 3 3;font-size:10px;height:22px;}
.button{border:solid 1px #A8ADB8;background:#C6C9D0;color:black;font-weight:normal;font-size:11px;padding:3px;width:70px;margin:1em 0 1em 0;}
.button:hover{border:solid 1px #A8ADB8;background:#FDFDCD;color:black;font-weight:normal;font-size:11px;padding:3px;width:70px;}
.footer{position:absolute;bottom:0;text-align:center;width:345px;margin-bottom:5px;margin-top:0px;}
.linia{color:#5F6676;border-style:dotted;height:1px;border-bottom:none;border-right:none;border-left:none;background-color:transparent;margin:10px 0 0 0;padding:0px;}
</style>
</head>
<body OnLoad="document.forms[0].elements[0].focus();">
<div align="center">
	<div class="login">
		<div class="login-form">	
			<form action="login_check.php" name="logowanie" method="POST" onSubmit="return InfoAboutPasswordStrength();">
				<div class="form-block1">
					<img src="img/key.gif" border="0" align="absmiddle" style="margin-right:4px">Logowanie do bazy eSerwis
				</div>
				<div class="form-block">
					<div class="inputlabel">Użytkownik</div>
					<div id="passwordbox"><input class="keyboardInput" style="width:210px;margin:0 0 0.3em 0;border:1px solid #B4B8C2;padding:3 3 3 3;font-size:10px;height:22px;" id="username" type="text" name="login" size="12" /></div>
					<div class="inputlabel">Hasło</div>
					<div><input class="keyboardInput" style="width:210px;margin:0 0 0.3em 0;border:1px solid #B4B8C2;padding:3 3 3 3;font-size:10px;height:22px;" type="password" id="password" name="haslo" size="22" onkeyup="passwordStrength(this.value)" /></div>
					<div class="inputlabel"></div>
					<div id="passwordStrength" class="strength0"></div>
					<div class="inputlabel"></div>
					<div id="passwordbox" style="text-align:right;margin-right:10px">
					<div>
						<br />
						<span onclick="labelClick(document.logowanie.hidelogin)">Ukryj nazwę użytkownika</span>
						<input style="margin:0; margin-top:2px; margin-right:2px;" id="przelacznik" type="checkbox" name="hidelogin" tabindex="-1" onClick="document.logowanie.login.focus(true);">
					</div>
					</div>
					<div class="inputlabel" ></div>
					<div id="passwordbox" style="text-align:right; margin-right:10px">
					<span>Wersja "lekka"</span>
					<input style="margin:0; margin-top:2px; margin-right:2px;" id="przelacznik2" type="checkbox" name="wl" tabindex="-1" onClick="document.logowanie.login.focus(true);">
					</div>
					
					<div align="right">
						<input type="submit" name="submitlogin" class="button" value="Zaloguj" />
						<input type="button" name="zam" class="button" value="Zakończ" onClick="javascript:if(confirm('Czy napewno chcesz zakończyć pracę z bazą eSerwis ?')) self.close();"/>
					</div>
				</div>
			</form>
			<div class="footer">
				<hr class="linia">
				<script>
					document.write('<font size="1" color="#2A2C32">Używana przez Ciebie przeglądarka:</font><font size="1" color="');
					if (((browserName=='Microsoft Internet Explorer') && (majorVersion<7)) ||
						((browserName=='Firefox') && (majorVersion<3)) ||
						((browserName=='Opera') || (browserName=='Safari') || (browserName=='Chrome'))
						) {
							document.write('#FF0000');
							document.write('"><b> '+browserName+' '+majorVersion+'</b></font><br />');					
							document.write('<font size="1" color="FF0000">Zalecana przeglądarka: min. <b>Internet Explorer 7</b> lub <b>Firefox 3</b><br />Niektóre elementy bazy mogą działać lub wyświetlać się niepoprawnie</font>');
					} else {
						document.write('#38B838');
						document.write('"> '+browserName+' '+majorVersion+'</font>');	
					}
				</script>
				<font size="1" color="#666666">
				<?php echo "<br />eSerwis, wersja $wersja "; ?>
				<br />Autor: 				
				    <SCRIPT LANGUAGE="JavaScript">
					<!-- Begin
					user = "maciej.adrjanowicz";
					site = "postdata.pl";
					document.write('<a style="color:#666666; text-decoration:none;" href=\"mailto:' + user + '@' + site + '?subject=eSerwis\">');
					document.write('Maciej Adrjanowicz' + '</a>');
					// End -->
					</SCRIPT>
					
				</font>
			</div>					
		</div>
	</div>
</div>

</body>
</html>