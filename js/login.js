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

function GetKey(evt) {
var charCode = (evt.which) ? evt.which : event.keyCode;
if (charCode == 13) {
//	document.getElementById('submit').focus(); document.getElementById('submit').click();
}
return charCode;
}

function InfoAboutPasswordStrength() {
	if (document.getElementById('username').value=='') { alert('Nie wpisałeś nazwy użytkownika'); document.getElementById('username').focus(); return false; }
	if (document.getElementById('password').value=='') { document.getElementById('password').focus(); return false; }
	var wynik = passwordStrength(document.getElementById('password').value);
	if ((wynik == 0) || (wynik == 1)) alert('Siła Twojego hasła jest niska. \r\n\r\n Zalecana jest zmiana hasła na bardziej skomplikowane');
	return true;
}

function handleEnter(_1,_2){var _3=_2.keyCode?_2.keyCode:_2.which?_2.which:_2.charCode;if(_3==13){var i;for(i=0;i<_1.form.elements.length;i++){if(_1==_1.form.elements[i]){break;}}i=(i+1)%_1.form.elements.length;_1.form.elements[i].focus();return false;}else{return true;}}

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
function CheckKey(evt) {
var charCode = (evt.which) ? evt.which : event.keyCode;
if (charCode == 13) {
//	document.getElementById('submit').focus(); document.getElementById('submit').click();
}
return true;
}