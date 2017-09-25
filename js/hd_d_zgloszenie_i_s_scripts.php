<?php
// wspolne funckje dla zgloszenia pojedynczego i seryjnego (pierwsza na liscie)
$pw = array($_f."/hd_d_zgloszenie.php",$_f."/hd_d_zgloszenie_s.php",$_f."/d_zadanie.php",$_f."/e_zadanie.php",$_f."/hd_e_zgloszenie_new.php",$_f."/hd_d_slownik_tresc.php",$_f."/hd_z_slownik_tresci.php",$_f."/hd_e_slownik_tresc.php",$_f."/hd_d_slownik_tresc_zs.php", $_f."/hd_d_zgloszenie_simple.php");
if (array_search($PHP_SELF, $pw)>-1) { ?>
<link rel="stylesheet" type="text/css" href="js/jquery/jquery.autocomplete.css" />

<script type="text/javascript" src="js/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="js/jquery/jquery.autocomplete.js"></script>

<script>

function IsNumeric(sText) {
   var ValidChars = "0123456789";
   var IsNumber=true;
   var Char;

   for (i = 0; i < sText.length && IsNumber == true; i++) 
      { 
      Char = sText.charAt(i); 
      if (ValidChars.indexOf(Char) == -1) 
         {
         IsNumber = false;
         }
      }
   return IsNumber;
   
}

function formatTime(time) {
    var result1 = false, m;
    var re = /^\s*([01]?\d|2[0-3]):?([0-5]\d)\s*$/;
    if ((m = time.match(re))) {
        result1 = (m[1].length == 2 ? "" : "0") + m[1] + ":" + m[2];
		//result1 = true;
    }
    return result1;
}

function CheckTime4(t) {
	if (formatTime(t)==false) {
		alert('Błędnie wpisana godzina'); document.getElementById('hdgz').value=''; document.getElementById('hdgz').focus(); 
		return false;
	} else {
		var d = new Date();

		var curr_hours = d.getHours();
		var curr_minutes = d.getMinutes();

		if (curr_hours<10) curr_hours="0"+curr_hours;
		if (curr_minutes<10) curr_minutes="0"+curr_minutes;

		var TerazCzas1 = curr_hours+""+curr_minutes;
		var TerazCzas = '<?php echo Date('Hi'); ?>';
		
		var currentTime = new Date();
		var month = currentTime.getMonth() + 1
		if (month<10) month="0"+month;
		var day = currentTime.getDate(); 
		if (day<10) day="0"+day;
		var year = currentTime.getFullYear();

		var TerazData = year + "-" + month + "-" + day;
		CzasWpisany = t.substring(0,2)+""+t.substring(3,5);
		
		if (TerazData==document.getElementById('hddz').value) {
			if (CzasWpisany>TerazCzas) { alert('Wpisałeś godzinę która jeszcze nie nastała'); document.getElementById('hdgz').focus(); return false; }
		}
		return true;
	}
}

function CheckTime(t) {
	if (formatTime(t)==false) {
		if (t=='') return true;
		alert('Błędnie wpisana godzina'); document.getElementById('hdgz').value=''; document.getElementById('hdgz').focus(); return false;
	} else {
		var d = new Date();

		var curr_hours = d.getHours();
		var curr_minutes = d.getMinutes();

		if (curr_hours<10) curr_hours="0"+curr_hours;
		if (curr_minutes<10) curr_minutes="0"+curr_minutes;

		var TerazCzas1 = curr_hours+""+curr_minutes;
		var TerazCzas = '<?php echo Date('Hi'); ?>';
		
		var currentTime = new Date();
		var month = currentTime.getMonth() + 1
		if (month<10) month="0"+month;
		var day = currentTime.getDate(); 
		if (day<10) day="0"+day;
		var year = currentTime.getFullYear();

		var TerazData = year + "-" + month + "-" + day;
		CzasWpisany = t.substring(0,2)+""+t.substring(3,5);
		
		if (TerazData==document.getElementById('hddz').value) {
			if (CzasWpisany>TerazCzas) { alert('Wpisałeś godzinę która jeszcze nie nastała'); document.getElementById('hdgz').focus(); return false; }
		}
		return true;
	}
}

function CheckTime5(t) {
	if (formatTime(t)==false) {
		alert('Błędnie wpisana godzina'); document.getElementById('nowy_czas_rozpoczecia').value=''; document.getElementById('nowy_czas_rozpoczecia').focus(); return false;
	}
}

function CheckTime1(t) {
	if (t=='') return true;
	
	if (formatTime(t)==false) {
		alert('Błędnie wpisana godzina'); document.getElementById('nowy_czas_rozpoczecia').value=''; document.getElementById('nowy_czas_rozpoczecia').focus(); return false;
	}
}

function CheckDate(t) {
	if (t=='') return false;
	DataWpisana = t.substring(0,4)+""+t.substring(5,7)+""+t.substring(8,10);
	if (t.substring(4,5)!='-') { alert('Błędnie wpisana data'); document.getElementById('nowa_data_zakonczenia').value=''; document.getElementById('nowa_data_zakonczenia').focus(); return false; }
	if (t.substring(7,8)!='-') { alert('Błędnie wpisana data'); document.getElementById('nowa_data_zakonczenia').value=''; document.getElementById('nowa_data_zakonczenia').focus(); return false; }
	if ((t.substring(0,4)<2010) || (t.substring(0,4)>2100)) { alert('Błędnie wpisana data (rok)'); document.getElementById('nowa_data_zakonczenia').value=''; document.getElementById('nowa_data_zakonczenia').focus(); return false; }
	if ((t.substring(5,7)<0) || (t.substring(5,7)>12)) { alert('Błędnie wpisana data (miesiac)'); document.getElementById('nowa_data_zakonczenia').value=''; document.getElementById('nowa_data_zakonczenia').focus(); return false; }
	if ((t.substring(8,10)<0) || (t.substring(5,7)>31)) { alert('Błędnie wpisana data (dzien)'); document.getElementById('nowa_data_zakonczenia').value=''; document.getElementById('nowa_data_zakonczenia').focus(); return false; }
}

function DopiszDwukropek(v) {
	var r = document.getElementById(v).value.length;
	if (r==2) document.getElementById(v).value = document.getElementById(v).value+':';
	if (r==5) CheckTime(document.getElementById(v).value);
}

function DopiszDwukropek1(v) {
	var r = document.getElementById(v).value.length;
	if (r==2) document.getElementById(v).value = document.getElementById(v).value+':';
	if (r==5) CheckTime1(document.getElementById(v).value);
}

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

function GenerateOnClickEventForSlownikTresci() {
	eraseCookie(self.name);
	eraseCookie('current_window_name');
	createCookie('current_window_name',self.name,1);
	createCookie(self.name,''+document.getElementById('kat_id').value+'|'+document.getElementById('podkat_id').value+'',1);
	//alert(self.name+':'+document.getElementById('kat_id').value+'|'+document.getElementById('podkat_id').value+'');
}

function trim(s) { return rtrim(ltrim(s)); }
function ltrim(s) {	var l=0; while(l < s.length && s[l] == ' ')	{	l++; }	return s.substring(l, s.length); }
function rtrim(s) {	var r=s.length -1;	while(r > 0 && s[r] == ' ')	{	r-=1;	}	return s.substring(0, r+1);}

function getXhr(){
var xhr = null; 
	if(window.XMLHttpRequest) // Firefox et autres
	   xhr = new XMLHttpRequest(); 
	else if(window.ActiveXObject){ // Internet Explorer 
		try {
			xhr = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			xhr = new ActiveXObject("Microsoft.XMLHTTP");
		}
	} else { 
		alert("XMLHTTPRequest not supported"); 
		xhr = false; 
	} 
	return xhr;
}

function filterInput(filterType,evt,allowDecimal,allowCustom){var keyCode,Char,inputField,filter='';var alpha='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';var num='0123456789';if(window.event){keyCode=window.event.keyCode;evt=window.event}else if(evt)keyCode=evt.which;else return true;if(filterType==0)filter=alpha;else if(filterType==1)filter=num;else if(filterType==2)filter=alpha+num;if(allowCustom)filter+=allowCustom;if(filter=='')return true;inputField=evt.srcElement?evt.srcElement:evt.target||evt.currentTarget;if((keyCode==null)||(keyCode==0)||(keyCode==8)||(keyCode==9)||(keyCode==27))return true;Char=String.fromCharCode(keyCode);if((filterType==1)&&(String.fromCharCode(keyCode)==',')){Char='.'}if((filter.indexOf(Char)>-1))return true;else if(filterType==1&&allowDecimal&&(Char=='.')&&inputField.value.indexOf('.')==-1)return true;else return false}

function pytanie_anuluj(message){ if (confirm(message)) { eraseCookie(self.name); self.close(); } }
function pytanie_wyczysc(message){ if (confirm(message)) self.location.reload();}

function ex1(val) {
	var text = val.value.replace(/\s+$/g,"") 
	var split = text.split("\n") 
	if (split.length<5) val.rows = split.length+1;
}

function KopiujDo1Entera(val, dest) {
	var w1 = val.replace("\n","<br>");
	var s1 = w1.search("<br>");
	if (s1>=0) {
		document.getElementById(dest).value=w1.substring(0,s1);
		return true;
	}
	document.getElementById(dest).value=w1;
}

function trimAll(sString) {
	while (sString.substring(0,1) == ' ') { sString = sString.substring(1, sString.length); }
	while (sString.substring(sString.length-1, sString.length) == ' ') { sString = sString.substring(0,sString.length-1); }
	return sString;
} 

function cUpper1(cObj) { cObj.value=trimAll(cObj.value.toUpperCase()); }
function cUpper(cObj) { cObj.value=trimAll(cObj.value.toUpperCase()); }
function cUpper_k(cObj) { 
	cObj.value = trimAll(cObj.value.toUpperCase());
}

function urlencode (str) {
    str = (str+'').toString();
    return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+');
}

var state = 'none';
function showhide(layer_ref, obj_id) {
	if (state == 'block') {
	state = 'none';
	document.getElementById(obj_id).value='Pokaż listę';
	}
	else {
	state = 'block';
	document.getElementById(obj_id).value='Ukryj listę';
	}
	if (document.all) { //IS IE 4 or 5 (or 6 beta)
	eval( "document.all." + layer_ref + ".style.display = state");
	}
	if (document.layers) { //IS NETSCAPE 4 or below
	document.layers[layer_ref].display = state;
	}
	if (document.getElementById &&!document.all) {
	hza = document.getElementById(layer_ref);
	hza.style.display = state;
	}
}

function ShowHints(k1,v1) {	
return true;
	//alert("k1="+k1+", v1="+v1);
	if (k1!='') {
		$("#tr_pk_hint").show();
		$("#Hint").show();
		$("#Hint").load("klasyfikacja.php?k="+k1+"&pk="+v1+"");
	}
	if ((k1=='') && (v1=='')) {
		$("#tr_pk_hint").hide();
		$("#Hint").hide();
	}
}

function GenerateSubPodkategoriaList(k, p) {
<?php
$pw = array($_f."/hd_d_slownik_tresc.php",$_f."/hd_e_slownik_tresc.php");
if (array_search($PHP_SELF, $pw)>-1) {  ?>
	var sps = document.getElementById('sl_sub_podkat_id');
<?php } ?>
<?php
$pw = array($_f."/hd_z_slownik_tresci.php");
if (array_search($PHP_SELF, $pw)>-1) {  ?>
	var sps = opener.document.getElementById('sub_podkat_id');
<?php } ?>
<?php
$pw = array($_f."/hd_d_slownik_tresc_zs.php");
if (array_search($PHP_SELF, $pw)>-1) {  ?>
	var sps = document.getElementById('sl_sub_podkat_id');
<?php } ?>
<?php
$pw = array($_f."/hd_d_slownik_tresc.php",$_f."/hd_z_slownik_tresci.php",$_f."/hd_e_slownik_tresc.php",$_f."/hd_d_slownik_tresc_zs.php");
if (array_search($PHP_SELF, $pw)>-1) { } else {  ?>
	var sps = document.getElementById('sub_podkat_id');
<?php } ?>
sps.options.length=0;
<?php
$pw = array($_f."/hd_d_slownik_tresc.php",$_f."/hd_e_slownik_tresc.php");
if (array_search($PHP_SELF, $pw)>-1) {  ?>
AddToList(sps,'','',true,true);	
<?php } ?>

	if ((k=="1")) {
		AddToList(sps,'Sprzęt','Sprzęt',false,false);
		AddToList(sps,'SP2000','SP2000',true,true);
		AddToList(sps,'Inne','Inne',false,false);
	}
	if ((k=="7")) {
		AddToList(sps,'Brak','',true,true);
	}
	if ((k=="4")) {
		AddToList(sps,'Brak','',true,true);
	}
	if ((k=="5")) {
		AddToList(sps,'Brak','',true,true);
	}

	if (k=="2") {
		if ((p=="4") || (p=="3")) {
			AddToList(sps,'Sprzęt','Sprzęt',true,true);
			AddToList(sps,'Oprogramowanie','Oprogramowanie',false,false);
		}	
		if (p=="9") {
			AddToList(sps,'Drukarka','Drukarka',true,true);
			AddToList(sps,'Monitor','Monitor',false,false);
			AddToList(sps,'Czytnik kodów kreskowych','Czytnik',false,false);
			AddToList(sps,'Skaner','Skaner',false,false);
			AddToList(sps,'UPS','UPS',false,false);
			AddToList(sps,'Terminal','Terminal',false,false);
			AddToList(sps,'Inne','Inne',false,false);
		}
		if (p=="2") {
			AddToList(sps,'SP2000','SP2000',true,true);
			AddToList(sps,'MBP','MBP',false,false);
			AddToList(sps,'MRU','MRU',false,false);
			AddToList(sps,'SEDI','SEDI',false,false);
			AddToList(sps,'SQL Serwer','SQL Serwer',false,false);			
			AddToList(sps,'Inne','Inne',false,false);		
		}		
		if (p=="0") {			
			AddToList(sps,'Łącze','Łącze',true,true);
			AddToList(sps,'Router','Router',false,false);
			AddToList(sps,'Modem','Modem',false,false);
			AddToList(sps,'Switch','Switch',false,false);
			AddToList(sps,'Inne','Inne',false,false);		
		}
		if (p=="7") {
			AddToList(sps,'SEDI','SEDI',false,false);
			AddToList(sps,'OCSI','OCSI',false,false);
			AddToList(sps,'Obsługa systemu operacyjnego','Obsługa systemu operacyjnego',false,false);
			AddToList(sps,'SQL Serwer','SQL Serwer',true,true);						
			AddToList(sps,'Zabbix','Zabbix',false,false);			
			AddToList(sps,'Symantec','Symantec',false,false);						
			AddToList(sps,'Inne','Inne',false,false);		
		}
	}
	if (k=="6") {
		if ((p=="4") || (p=="3")) {
			AddToList(sps,'Sprzęt','Sprzęt',true,true);
			AddToList(sps,'Oprogramowanie','Oprogramowanie',false,false);
		}	
		if (p=="9") {
			AddToList(sps,'Drukarka','Drukarka',true,true);
			AddToList(sps,'Monitor','Monitor',false,false);
		}
		if (p=="0") {			
			AddToList(sps,'Łącze','Łącze',true,true);
			AddToList(sps,'Router','Router',false,false);
			AddToList(sps,'Modem','Modem',false,false);
			AddToList(sps,'Switch','Switch',false,false);
			AddToList(sps,'Inne','Inne',false,false);		
		}
		if (p=="7") {
			AddToList(sps,'SP2000','SP2000',false,false);
			AddToList(sps,'SQL Serwer','SQL Serwer',true,true);
			AddToList(sps,'Inne','Inne',false,false);
		}
	}
	if (k=="3") {
		if (p=="H") {
			AddToList(sps,'Brak','',true,true);
		}
		if (p=="I") {			
			AddToList(sps,'Dodanie użytkownika lub komputera','Dodanie użytkownika lub komputera',false,false);
			AddToList(sps,'Kasowanie','Kasowanie',false,false);
			AddToList(sps,'Przeniesienie','Przeniesienie',false,false);	
			AddToList(sps,'Reset hasła','Reset hasła',true,true);
			AddToList(sps,'Odblokowanie konta','Odblokowanie konta',false,false);
			AddToList(sps,'Inne','Inne',false,false);
		}		
		if ((p=="4") || (p=="3")) {
			AddToList(sps,'Sprzęt','Sprzęt',true,true);
			AddToList(sps,'Oprogramowanie','Oprogramowanie',false,false);
			AddToList(sps,'Inne','Inne',false,false);
		}
		if (p=="0") {			
			AddToList(sps,'Łącze','Łącze',true,true);
			AddToList(sps,'Router','Router',false,false);
			AddToList(sps,'Modem','Modem',false,false);
			AddToList(sps,'Switch','Switch',false,false);
			AddToList(sps,'Inne','Inne',false,false);		
		}
		if (p=="D") {
			AddToList(sps,'Instalacja i przenoszenie sprzętu','Instalacja i przenoszenie sprzętu',false,false);
			AddToList(sps,'Inne','Inne',true,true);
		}
		if (p=="F") {
			AddToList(sps,'Raportowanie','Raportowanie',false,false);
			AddToList(sps,'Dokonywanie ustaleń','Dokonywanie ustaleń',true,true);
			AddToList(sps,'Ekspertyzy','Ekspertyzy',false,false);
			AddToList(sps,'Inne','Inne',false,false);
		}		
		if ((p=="A") || (p=="B") || (p=="C") || (p=="E")) {
			AddToList(sps,'Brak','',true,true);
		}		
		if (p=="9") {
			AddToList(sps,'Drukarka','Drukarka',true,true);
			AddToList(sps,'Monitor','Monitor',false,false);
			AddToList(sps,'Czytnik kodów kreskowych','Czytnik',false,false);
			AddToList(sps,'Skaner','Skaner',false,false);
			AddToList(sps,'UPS','UPS',false,false);
			AddToList(sps,'Terminal','Terminal',false,false);
			AddToList(sps,'Inne','Inne',false,false);
		}
		if (p=="2") {
			AddToList(sps,'SP2000','SP2000',true,true);
			AddToList(sps,'MBP','MBP',false,false);
			AddToList(sps,'ŁP14','ŁP14',false,false);
			AddToList(sps,'ORM','ORM',false,false);
			AddToList(sps,'MRU','MRU',false,false);
			AddToList(sps,'SEDI','SEDI',false,false);
			AddToList(sps,'MRUm','MRUm',false,false);
			AddToList(sps,'ZST WiemPost','ZST WiemPost',false,false);
			AddToList(sps,'Reklamacje','Reklamacje',false,false);
			AddToList(sps,'Lotus Notes','Lotus Notes',false,false);
			AddToList(sps,'Office','Office',false,false);
			AddToList(sps,'Przeglądarka internetowa','Przeglądarka internetowa',false,false);
			AddToList(sps,'Outlook Express','Outlook Express',false,false);
			AddToList(sps,'S.N.O.S.P.','S.N.O.S.P.',false,false);
			AddToList(sps,'PenGuard','PenGuard',false,false);			
			AddToList(sps,'Acrobat Reader','Acrobat Reader',false,false);
			AddToList(sps,'Obsługa systemu operacyjnego','Obsługa systemu operacyjnego',false,false);
			AddToList(sps,'SQL Serwer','SQL Serwer',false,false);	
			AddToList(sps,'Inne','Inne',false,false);		
		}
		if ((p=="7") || (p=="6")) {
			AddToList(sps,'SP2000','SP2000',true,true);
			AddToList(sps,'MBP','MBP',false,false);
			AddToList(sps,'ŁP14','ŁP14',false,false);
			AddToList(sps,'ORM','ORM',false,false);
			AddToList(sps,'MRU','MRU',false,false);
			AddToList(sps,'SEDI','SEDI',false,false);
			AddToList(sps,'MRUm','MRUm',false,false);
			AddToList(sps,'ZST WiemPost','ZST WiemPost',false,false);
			AddToList(sps,'Reklamacje','Reklamacje',false,false);
			AddToList(sps,'Lotus Notes','Lotus Notes',false,false);
			AddToList(sps,'Office','Office',false,false);
			AddToList(sps,'Przeglądarka internetowa','Przeglądarka internetowa',false,false);
			AddToList(sps,'Outlook Express','Outlook Express',false,false);
			AddToList(sps,'S.N.O.S.P.','S.N.O.S.P.',false,false);
			AddToList(sps,'PenGuard','PenGuard',false,false);			
			AddToList(sps,'Acrobat Reader','Acrobat Reader',false,false);
			AddToList(sps,'Obsługa systemu operacyjnego','Obsługa systemu operacyjnego',false,false);
			
			AddToList(sps,'OCSI','OCSI',false,false);			
			AddToList(sps,'Zabbix','Zabbix',false,false);			
			AddToList(sps,'Symantec','Symantec',false,false);			
			AddToList(sps,'SQL Serwer','SQL Serwer',false,false);			
			
			AddToList(sps,'Inne','Inne',false,false);		
		}		
	}
}

</script>
<?php } ?>