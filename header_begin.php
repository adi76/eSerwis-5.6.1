<?php include_once('header_simple.php'); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>
<?php 
$_f = dirname($PHP_SELF);
echo "$nazwa_aplikacji"; 
//if ($_SESSION[is_dyrektor]==1) echo " | Wybrana lokalizacja: ".$es_filia; 
?>
</title>
<link rel="icon" href="img/favicon.ico" type="image/x-icon" />
<?php if ($es_style=='') { $template='oryginal'; } else $template=$es_style; ?>
<link rel="stylesheet" type="text/css" href="css/print_e.css" media="print" />
<?php 
$pw = array($_f."/hd_p_zgloszenia.php");
if (array_search($PHP_SELF, $pw)<0) {
	?><link rel="stylesheet" type="text/css" href="css/print.css" media="print" /><?php
}
?>
<link rel="stylesheet" type="text/css" id="serwis" href="templates/<?php echo $template;?>/eserwis.css" media="screen" />
<?php
$pw = array($_f."/main_simple.php");
if (array_search($PHP_SELF, $pw)>-1) {
?>
<link rel="stylesheet" type="text/css" id="main_simle_serwis" href="css/main_simple.css" media="screen" />
<?php } else { ?>
<?php 
$pw = array($_f."/hd_p_zgloszenia.php",$_f."/hd_d_zgloszenie.php",$_f."/hd_d_zgloszenie_s.php",$_f."/hd_o_zgloszenia.php",$_f."/hd_o_zgloszenia_zs.php",$_f."/hd_o_zgloszenia_s.php",$_f."/hd_p_zgloszenie_kroki.php",$_f."/z_kb_kategorie.php",$_f."/d_kb_kategoria.php",$_f."/z_magazyn_zwroty.php",$_f."/hd_d_note.php",$_f."/hd_g_raport_okresowy.php",$_f."/hd_g_delegacje.php",$_f."/hd_g_raport_dzienny_dla_pracownika.php",$_f."/hd_g_raport_z_wykonania_zgl_s.php",$_f."/hd_z_osoby_zglaszajace.php",$_f."/hd_z_slownik_tresci.php",$_f."/hd_d_slownik_tresc.php",$_f."/p_towary_dostepne.php",$_f."/hd_check_backup_new.php"); 
if (array_search($PHP_SELF, $pw)>-1) { ?>
<?php } else { ?>
<link rel="stylesheet" type="text/css" href="templates/<?php echo $template;?>/menu.css" id="listmenu-h" />
<?php } ?>
<link rel="stylesheet" type="text/css" id="serwis" href="css/eserwis_default.css" media="screen" />
<?php $pw = array($_f."/hd_p_zgloszenia.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
<?php if ($_GET[search]!='1') { ?>
<link rel="stylesheet" type="text/css" href="js/anylinkcssmenu/anylinkcssmenu.css" />
<?php } ?>
<link rel="stylesheet" type="text/css" href="js/jquery/jquery.autocomplete.css" />
<?php } ?>
<?php } ?>

<?php
$pw = array($_f."/hd_p_zgloszenia.php", $_f."/z_kb_kategorie.php",$_f."/hd_check_backups_new.php");
if (array_search($PHP_SELF, $pw)>-1) { ?>
<link rel="stylesheet" type="text/css" href="templates/<?php echo $template;?>/menu.css" id="listmenu-h" />
<script type="text/javascript" src="js/fsmenu.js"></script>

<script>
function PrepareKategoria(v) {
	var tid = document.getElementById('pozid_'+v).value;
	var taction = document.getElementById('action').value;
	newWindow_r(800,600,'p_kb_pytania.php?id='+tid+'&poziom=0&action='+taction+'');
}
function PrepareKategoria2(v) {
	var tid = document.getElementById('pozid_'+v).value;
	var taction = document.getElementById('action').value;
	newWindow_r(800,600,'p_kb_pytania.php?id='+tid+'&poziom=1&action='+taction+'');
}
</script>
<?php } ?>

<?php 
$pw = array($_f."/hd_p_zgloszenie_kroki.php");
if (array_search($PHP_SELF, $pw)==0) { 
	if ($_GET[poz]!='under') {
	?>
<script type="text/javascript" src="js/security.js"></script>
	<?php } ?>
<?php } ?>
<?php 
$pw = array($_f."/main_simple.php");
if ((substr($PHP_SELF,0,10)!=''.$_f.'/u_') && (substr($PHP_SELF,0,10)!=''.$_f.'/d_') && ((array_search($PHP_SELF, $pw)<0))) {
?>
<?php } ?>
<?php
$pw = array($_f."/p_towary_dostepne.php",$_f."/hd_p_zgloszenia.php", $_f."/e_magazyn_pobierz.php", $_f."/z_magazyn_zwroty.php",$_f."/main.php", $_f."/p_naprawy_w_okresie.php", $_f."/p_towary_raport_sprzedaz.php",$_f."hd_g_raport_okresowy.php");
if (array_search($PHP_SELF, $pw)>-1) {
?>
<script type="text/javascript" src="js/colorlightrow.js"></script>
<?php } ?>
<?php
$pw = array($_f."/main.php",$_f."/z_uzytkownicy.php",$_f."/z_pion.php",$_f."/z_umowa.php",$_f."/z_filie.php",$_f."/z_komorka.php",$_f."/z_firmy_zewnetrzne.php",$_f."/z_oprogramowanie.php",$_f."/z_typ_sprzetu.php",$_f."/z_konfiguracja.php",$_f."/z_monitorami.php",$_f."/z_drukarka.php",$_f."/z_czytnik.php");
if (array_search($PHP_SELF, $pw)>-1) {
?><script type="text/javascript" src="js/fsmenu.js"></script><?php
} ?>
<?php
$pw = array($_f."/d_ewidencja.php");
if (array_search($PHP_SELF, $pw)>-1) {
?><script type="text/javascript" src="js/ewid.js"></script><?php
}
?>
<?php 
//if (substr($PHP_SELF,0,10)!=''.$_f.'/u_') {
?><script type="text/javascript" src="js/moje.js"></script>
<?php //} ?>
<?php
$pw = array($_f."/p_sprzedaz_w_okresie.php",$_f."/e_ewidencja.php",$_f."/r_awarie.php",$_f."/p_towary_stan.php",$_f."/r_faktury_okres.php",$_f."/p_naprawy_w_okresie.php",$_f."/p_magazyn_historia_wg_up.php",$_f."/p_magazyn_historia_okres.php",$_f."/d_ewidencja.php",$_f."/z_towary_obrot.php",$_f."/utworz_protokol.php",$_f."/d_czarna_lista.php",$_f."/d_zadanie.php",$_f."/e_zadanie.php",$_f."/d_komorka_czynnosc.php",$_f."/d_faktura.php",$_f."/e_podfaktura.php",$_f."/d_podfaktura.php",$_f."/e_faktura.php",$_f."/g_zestaw_obrot.php",$_f."/z_naprawy_serwis.php",$_f."/p_sprzedaz_obciazenie.php",$_f."/e_zadanie_pozycja.php",$_f."/e_komorka_czynnosc.php",$_f."/z_naprawy_zmien_serwis.php",$_f."/z_awaria_zamknij.php",$_f."/z_naprawy_status.php",$_f."/z_naprawy_napraw3.php",$_f."/d_pojazd.php",$_f."/hd_p_zgloszenia.php",$_f."/hd_e_zgloszenie.php",$_f."/hd_dodaj_dostep_czasowy.php",$_f."/z_naprawy_wycofaj_z_serwisu.php",$_f."/e_naprawy_powod_wycofania.php",$_f."/hd_d_note.php",$_f."/hd_e_note.php",$_f."/z_naprawa_przekaz.php",$_f."/z_naprawa_przekaz_zwrot.php",$_f."/e_towary_obrot.php",$_f."/e_zestaw_obrot.php",$_f."/hd_e_zgloszenie_krok.php",$_f."/hd_g_raport_przesuniete_terminy.php",$_f."/d_komorka.php",$_f."/e_komorka.php",$_f."/hd_g_harmonogram_wyjazdu.php",$_f."/e_zadanie_osoba_many.php",$_f."/e_zadanie_osoba.php",$_f."/d_projekt.php",$_f."/e_projekt.php",$_f."/e_komorka_czynnosc_osoba.php");
if (array_search($PHP_SELF, $pw)>-1) {
//,$_f."/main.php"	- 23.08.2010 wyrzucone
?>
<script type="text/javascript" src="js/calendar.js"></script>
<?php } ?>
<?php if ($tooltips==1) { ?>
<script type="text/javascript" src="js/tooltips.js"></script>
<?php } ?>
<?php
$pw = array($_f."/d_.php",$_f."/d_awaria_zapisz.php",$_f."/d_czarna_lista.php",$_f."/d_dostawca.php",$_f."/d_drukarka.php",$_f."/d_ewidencja.php",$_f."/d_faktura.php",$_f."/d_faktura_pozycja.php",$_f."/d_filia.php",$_f."/d_firma_kurierska.php",$_f."/d_firma_serwisowa.php",$_f."/d_kb_kategoria.php",$_f."/d_komorka_czynnosc.php",$_f."/d_konfiguracja.php",$_f."/d_magazyn.php",$_f."/d_monitor.php",$_f."/d_oprogramowanie.php",$_f."/d_pion.php",$_f."/d_podfaktura.php",$_f."/d_typ_sprzetu.php",$_f."/d_umowa.php",$_f."/d_uzytkownika.php",$_f."/d_zadanie.php",$_f."/ew_przesuniecie.php",$_f."/ew_remont.php",$_f."/ew_usuniecie.php",$_f."/e_.php",$_f."/e_ewidencja.php",$_f."/e_faktura.php",$_f."/e_faktura_pozycja.php",$_f."/e_filia.php",$_f."/e_firma_kurierska.php",$_f."/e_firma_serwisowa.php",$_f."/e_firma_zewnetrzna.php",$_f."/e_kb_kategoria.php",$_f."/e_kb_odpowiedz.php",$_f."/e_konfiguracja.php",$_f."/e_magazyn.php",$_f."/e_naprawy.php",$_f."/e_oprogramowanie.php",$_f."/e_podfaktura.php",$_f."/e_protokol_z_ewidencji.php",$_f."/e_uzytkownika.php",$_f."/e_zadanie.php",$_f."/g_zestaw_obrot.php",$_f."/p_magazyn_historia_okres.php",$_f."/p_magazyn_historia_wg_up.php",$_f."/p_naprawy_w_okresie.php",$_f."/p_sprzedaz_obciazenie.php",$_f."/p_sprzedaz_w_okresie.php",$_f."/p_towary_przesuniecia_zapisz.php",$_f."/p_towary_raport_sprzedaz.php",$_f."/p_towary_stan.php",$_f."/r_.php",$_f."/r_awarie.php",$_f."/r_faktury_okres.php",$_f."/szukaj.php",$_f."/utworz_protokol.php",$_f."/z_magazyn_pobierz.php",$_f."/z_naprawy_przyjmij.php",$_f."/z_naprawy_uszkodzony.php",$_f."/z_towary_obrot.php",$_f."/e_zadanie_pozycja.php",$_f."/e_komorka_czynnosc.php",$_f."/z_naprawy_napraw5.php",$_f."/z_awaria_zamknij.php",$_f."/d_pojazd.php",$_f."/hd_e_osoba_zglaszajaca.php",$_f."/hd_d_osoba_zglaszajaca.php",$_f."/hd_e_zgloszenie.php",$_f."/hd_g_raport_z_wykonania_zgl_s.php",$_f."/hd_d_note.php",$_f."/hd_e_note.php",$_f."/z_naprawa_przekaz.php",$_f."/z_naprawa_przekaz_zwrot.php",$_f."/e_towary_obrot.php",$_f."/z_naprawy_wycofaj.php",$_f."/z_naprawy_wycofaj_sprzet_serwisowy.php",$_f."/e_zadanie_osoba_many.php",$_f."/e_zadanie_osoba.php",$_f."/hd_o_zgloszenia.php");
if ((array_search($PHP_SELF, $pw)>-1) || ($action=='szukaj')) {
?><script type="text/javascript" src="js/validate.js"></script>
<?php } else { ?>
<script type="text/javascript" src="templates/<?php echo $template;?>/lightrow.js"></script>
<?php } ?>
<?php if ($useDateMask==1) { ?><script type="text/javascript" src="js/dFilter.js"></script><?php } ?>
<?php if ($tooltips==1) { ?><script type="text/javascript">window.onload=function(){enableTooltips("content")};</script><?php } ?>
<!--[if lt IE 7]>
<script defer type="text/javascript" src="js/pngfix.js"></script>
<![endif]-->	
<?php
$pw = array($_f."/z_naprawy_uszkodzony.php");
if (array_search($PHP_SELF, $pw)>-1) { 
?>
<script>
function urlencode1 (str) {
    str = (str+'').toString();
    return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+');
}
</script>
<?php if ($_REQUEST[auto]!=1) { ?>
<link rel="stylesheet" type="text/css" href="js/jquery/jquery.autocomplete.css" />
<script type="text/javascript" src="js/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="js/jquery/jquery.autocomplete.js"></script>

<SCRIPT language=JavaScript>
function getXhr(){
var xhr = null; 
	if(window.XMLHttpRequest) // Firefox
	   xhr = new XMLHttpRequest(); 
	else if(window.ActiveXObject){ // IE
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

function WybierzWartosc(c) {
	var wartosc = c.split('>>>>>')[1].split('<<<<<');
	var wynik = wartosc[0].substring(1,wartosc[0].length);
	if (wynik=='') { 
		//$('#dodaj_sprzet_do_ewidencji').show();
		//$('#a_dodaj_sprzet_do_ewidencji').show();
		$('#gw3').show();
		$('#info1').show('slow');
	} else {
		$('#info1').hide('slow');
		//$('#dodaj_sprzet_do_ewidencji').hide();
		//$('#a_dodaj_sprzet_do_ewidencji').hide();
		$('#gw3').hide();
	}
	return wynik;
}

function SprawdzSlownik(k) {
var xhr = getXhr();

	if (document.getElementById('tmodel').value!='') {
		xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 && xhr.status == 200){
			leselect = xhr.responseText;
			//alert(leselect);
			document.getElementById('tmodelid').innerHTML = leselect;
			document.getElementById('tmodelid1').value = WybierzWartosc((leselect));	
			}
		} 	
		xhr.open("POST","hd_get_slownik_id.php",true);
		xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		sel = document.getElementById('tmodel').value;		
		var parametry = ""+urlencode1(sel);
		//alert("wybierzid="+parametry+'&s='+document.getElementById('tnazwa').value);
		xhr.send("wybierzid="+parametry+"&s="+document.getElementById('tnazwa').value);
		return true;
	} else return true;
}

	$().ready(function() {
		$("#tmodel").autocomplete("hd_get_slownik_values.php?s=<?php echo $_REQUEST[cat]; ?>", {
			width: 360,
			max: 150,
			matchContains: false,
			mustMatch: false,
			minChars: 1,
			//multiple: true,
			//highlight: false,
			//multipleSeparator: ",",
			selectFirst: false
		});
	});
	
</script>
<?php } ?>

<script>

function in_array (needle, haystack, argStrict) {
    // http://kevin.vanzonneveld.net
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: vlado houba
    // +   input by: Billy
    // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
    // *     example 1: in_array('van', ['Kevin', 'van', 'Zonneveld']);
    // *     returns 1: true
    // *     example 2: in_array('vlado', {0: 'Kevin', vlado: 'van', 1: 'Zonneveld'});
    // *     returns 2: false
    // *     example 3: in_array(1, ['1', '2', '3']);
    // *     returns 3: true
    // *     example 3: in_array(1, ['1', '2', '3'], false);
    // *     returns 3: true
    // *     example 4: in_array(1, ['1', '2', '3'], true);
    // *     returns 4: false
    var key = '',
        strict = !! argStrict;
    if (strict) {
        for (key in haystack) {
            if (haystack[key] === needle) {
                return true;
            }
        }
    } else {
        for (key in haystack) {
            if (haystack[key] == needle) {
                return true;
            }
        }
    }
    return false;
}

function reload_z_naprawy_uszkodzony(form) {
	var zgl=document.getElementById('tresc_zgl').value;
	var val=form.tnazwa.options[form.tnazwa.options.selectedIndex].value;
	var tmodel=form.tmodel.value;
	
	if (zgl!='') {
		var newupid=form.new_upid.value;
		if (newupid==0) newupid='';
	} else {
		var newupid=form.new_upid.options[form.new_upid.options.selectedIndex].value;
	}
	var tup=form.tup.value;
	if (zgl!='') {
		var from=form.from.value;
		var hd_nr=form.hd_nr.value;
	} else {
		var from='';
		var hd_nr='';
	}	
	
	var auto1=document.getElementById('auto1').value;
	
	var hd_podkategoria_nr=form.hd_podkategoria_nr.value;
	var up=form.up.value;
	var id=form.id.value;

	var tsn=form.tsn.value;
	var tni=form.tni.value;
	var tuwagi=form.tuwagi.value;
	var dde=form.dodaj_do_ewidencji.value;
	self.location='z_naprawy_uszkodzony.php?id=' + id + '&cat=' + val + '&new_upid=' + newupid + '&tup='+urlencode1(tup)+'&up='+urlencode1(up)+'&tmodel=' +urlencode1(tmodel)+'&tsn='+urlencode1(tsn)+'&tni='+urlencode1(tni)+'&tuwagi='+urlencode1(tuwagi)+ '&tresc_zgl=' + zgl + '&dodaj_do_ewidencji='+dde+'&hd_podkategoria_nr='+hd_podkategoria_nr+'&from='+from+'&hd_nr='+hd_nr+'&auto='+auto1;
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
function ClearCookie (name, value) {  
	document.cookie = name + "=" + escape (value) + ";expires=Thu, 01-Jan-1970 00:00:01 GMT";
}
function SetCookie(name, value) {  
	var argv = SetCookie.arguments;  
	var argc = SetCookie.arguments.length;  
	var expires = (argc > 2) ? argv[2] : null;  
	var path = (argc > 3) ? argv[3] : null;  
	var domain = (argc > 4) ? argv[4] : null;  
	var secure = (argc > 5) ? argv[5] : false;  
	document.cookie = name + "=" + escape (value) + 
	((expires == null) ? "" : ("; expires=" + expires.toGMTString())) + 
	((path == null) ? "" : ("; path=" + path)) +  
	((domain == null) ? "" : ("; domain=" + domain)) +    
	((secure == true) ? "; secure" : "");
}		
</script>
<?php } ?>

<?php $pw = array($_f."/z_wymiana_podzespolu.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
<SCRIPT language=JavaScript>
function reload_z_naprawy_przyjmij_wp(form) {
var _from = form.from.value;
var _przyjmij = form.przyjmij.value; 
var _hd_nr = form.hd_nr.value;
var _hd_podkategoria_nr = form.hd_podkategoria_nr.value;
var zgl=form.tresc_zgl.value;
if (zgl!='') {
	var upid=form.new_upid.value;
} else {
	var upid=form.new_upid.options[form.new_upid.options.selectedIndex].value;
}
var typid=form.typid.options[form.typid.options.selectedIndex].value;
var val=form.typid.options[form.typid.options.selectedIndex].text;
var tup1=form.tup.value;
var from1 = form.from.value;
var hd_nr1 = form.hd_nr.value;
var hd_podkategoria_nr1 = form.hd_podkategoria_nr.value;
var hdzglnr = form.hd_zgl_nr.value;
self.location='z_wymiana_podzespolu.php?cat='+ val+ '&new_upid=' + upid + '&typid=' +typid+ '&tresc_zgl=' + zgl + '&tup='+tup1 + '&tup1='+tup1+'&from='+_from+'&hd_nr='+_hd_nr+'&hd_podkategoria_nr='+_hd_podkategoria_nr+'&przyjmij='+_przyjmij+'&hd_zgl_nr='+hdzglnr+'';
}

</script>
<?php } ?>

<?php $pw = array($_f."/z_naprawy_przyjmij.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
<SCRIPT language=JavaScript>
function reload_z_naprawy_przyjmij(form) {
var _from = form.from.value;
var _przyjmij = form.przyjmij.value;
var _hd_nr = form.hd_nr.value;
var _hd_podkategoria_nr = form.hd_podkategoria_nr.value;
var zgl=form.tresc_zgl.value;
if (zgl!='') {
	var upid=form.new_upid.value;
} else {
	var upid=form.new_upid.options[form.new_upid.options.selectedIndex].value;
}
var typid=form.typid.options[form.typid.options.selectedIndex].value;
var val=form.typid.options[form.typid.options.selectedIndex].text;
var tup1=form.tup.value;
var from1 = form.from.value;
var hd_nr1 = form.hd_nr.value;
var hd_podkategoria_nr1 = form.hd_podkategoria_nr.value;
var hdzglnr = form.hd_zgl_nr.value;
self.location='z_naprawy_przyjmij.php?cat='+ val+ '&new_upid=' + upid + '&typid=' +typid+ '&tresc_zgl=' + zgl + '&tup='+tup1 + '&tup1='+tup1+'&from='+_from+'&hd_nr='+_hd_nr+'&hd_podkategoria_nr='+_hd_podkategoria_nr+'&przyjmij='+_przyjmij+'&hd_zgl_nr='+hdzglnr+'';
}
function aktualizuj_button(form) {
var upid=form.upid.options[form.upid.options.selectedIndex].value;
var typid=form.typid.options[form.typid.options.selectedIndex].value;
form.pozaewid.onClick='z_naprawy_przyjmij.php?cat='+ val+ '&upid=' + upid + '&typid=' +typid;
}
</script>
<?php } ?>
<?php $pw = array($_f."/z_naprawy_uszkodzony_szczecin.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
<SCRIPT language=JavaScript>
function reload_z_naprawy_przyjmij_szczecin(form) {
var upid=form.tup.options[form.tup.options.selectedIndex].value;
var typid=form.tnazwa.options[form.tnazwa.options.selectedIndex].value;
var val=form.tnazwa.options[form.tnazwa.options.selectedIndex].value;
var smodel=form.tmodel.value;
var ssn=form.tsn.value;
var sni=form.tni.value;
self.location='z_naprawy_uszkodzony_szczecin.php?auto=0&cat='+ val+ '&upid=' + upid + '&typid=' +typid+'&tmodel='+smodel+'&tsn='+ssn+'&tni='+sni;
}
</script>
<?php } ?>

<?php $pw = array($_f."/hd_d_slownik_tresc_zs.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
<script>
function AddToList(listname, list_opis, list_value, bool1, bool2) {
	listname.options[listname.options.length] = new Option(""+list_opis+"",""+list_value+"",bool1, bool2);
}
</script>
<?php } ?>

<?php $pw = array($_f."/z_naprawy_status.php",$_f."/z_naprawy_wycofaj.php",$_f."/z_naprawy_napraw3.php"); if (array_search($PHP_SELF, $pw)>-1) { 
?>
<SCRIPT language=JavaScript>
function pokazdalej(w) { 
	if (w==0) {
		document.getElementById('submit').value='Zapisz & Zamknij';
	} else {
		document.getElementById('submit').value='Dalej';
	}
	document.getElementById('dalej').style.display='';
}
function pokaz_tak(w) {	document.getElementById("pokaztak").style.display="";}
function pokaz_szcz(w) { document.getElementById("szcz").style.display="";}
function ukryj_szcz(w) {document.getElementById("szcz").style.display="none";}
function filterInput(filterType,evt,allowDecimal,allowCustom){var keyCode,Char,inputField,filter='';var alpha='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';var num='0123456789';if(window.event){keyCode=window.event.keyCode;evt=window.event}else if(evt)keyCode=evt.which;else return true;if(filterType==0)filter=alpha;else if(filterType==1)filter=num;else if(filterType==2)filter=alpha+num;if(allowCustom)filter+=allowCustom;if(filter=='')return true;inputField=evt.srcElement?evt.srcElement:evt.target||evt.currentTarget;if((keyCode==null)||(keyCode==0)||(keyCode==8)||(keyCode==9)||(keyCode==27))return true;Char=String.fromCharCode(keyCode);if((filterType==1)&&(String.fromCharCode(keyCode)==',')){Char='.'}if((filter.indexOf(Char)>-1))return true;else if(filterType==1&&allowDecimal&&(Char=='.')&&inputField.value.indexOf('.')==-1)return true;else return false}
</script>
<?php } ?>
<?php $pw = array($_f."/g_zestaw_obrot.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
<SCRIPT language=JavaScript>
function urlencode1 (str) {
    str = (str+'').toString();
    return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+');
}
function reload1_obrot(form) {
if (document.getElementById('readonly').value=='1') {
	var tup=form.new_upid.value;
} else {
	var tup=form.new_upid.options[form.new_upid.options.selectedIndex].value;
}
var tdata=form.tdata.value;
var tid=form.zid.value;
var tuwagi=form.tuwagi.value;
var thd_zgl_nr=form.hd_zgl_nr.value;
var trodzaj = 'Towar';
var dwp = form.dodajwymianepodzespolow.value;
if (document.getElementById('_wp_opis')) { var wp1_o = document.getElementById('_wp_opis').value; } else { var wp1_o = ''; }
if (document.getElementById('_wp_sn')) { var wp1_sn = document.getElementById('_wp_sn').value; } else { var wp1_sn = ''; }
if (document.getElementById('_wp_ni')) { var wp1_ni = document.getElementById('_wp_ni').value; } else { var wp1_ni = ''; }
if (document.getElementById('jedna_umowa')==null) {
	var tumowa1=0;
} else {
	if (document.getElementById('jedna_umowa').value==0) {
	var tumowa1=form.tumowa.options[form.tumowa.options.selectedIndex].value;
	} else {
	var tumowa1=form.tumowa.value;
	}
}
var allow_change_rs=form.allow_change_rs.value;
var nazwa_urzadzenia=form.nazwa_urzadzenia.value;
var sn_urzadzenia=form.sn_urzadzenia.value;
var ni_urzadzenia=form.ni_urzadzenia.value;
var readonly=form.readonly.value;
var ewid_id=form.ewid_id.value;
self.location='g_zestaw_obrot.php?id='+tid+'&new_upid=' + urlencode1(tup) + '&tdata=' + tdata + '&trodzaj=' +urlencode1(trodzaj)+'&tuwagi='+urlencode1(tuwagi)+'&tumowa='+urlencode1(tumowa1)+'&hd_zgl_nr='+thd_zgl_nr+'&dodajwymianepodzespolow='+dwp+'&_wp_opis='+urlencode1(wp1_o)+'&_wp_sn='+urlencode1(wp1_sn)+'&_wp_ni='+urlencode1(wp1_ni)+'&allow_change_rs='+allow_change_rs+'&nazwa_urzadzenia='+urlencode1(nazwa_urzadzenia)+'&sn_urzadzenia='+urlencode1(sn_urzadzenia)+'&ni_urzadzenia='+urlencode1(ni_urzadzenia)+'&readonly='+readonly+'&ewid_id='+ewid_id+'';
}
function PokazZgloszenie(n) {
	if (!isNaN(parseInt(n))) {
		newWindow_r(800,600,'hd_o_zgloszenia.php?action=podglad&nr='+n+'&id='+n+'');
	} else {
		alert('Błednie podany numer zgłoszenia');
	}
}
</script>
<?php } ?>
<?php $pw = array($_f."/z_towary_obrot.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
<SCRIPT language=JavaScript>
function urlencode1 (str) {
    str = (str+'').toString();
    return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+');
}
function reload1_obrot(form) {
if (document.getElementById('readonly').value=='1') {
	var tup=form.new_upid.value;
} else {
	var tup=form.new_upid.options[form.new_upid.options.selectedIndex].value;
}
var rs = form.rs_select.value;
if (rs==1) {
	var trodzaj=form.trodzaj.options[form.trodzaj.options.selectedIndex].value;
} else {
	var trodzaj=form.trodzaj.value;
}
if (document.getElementById('_wp_opis')) { var wp1_o = document.getElementById('_wp_opis').value; } else { var wp1_o = ''; }
if (document.getElementById('_wp_sn')) { var wp1_sn = document.getElementById('_wp_sn').value; } else { var wp1_sn = ''; }
if (document.getElementById('_wp_ni')) { var wp1_ni = document.getElementById('_wp_ni').value; } else { var wp1_ni = ''; }
var _acrs = form.acrs.value;
var tdata=form.tdata.value;
var snazwa=form.nazwa_sprzetu.value;
var ssn=form.sn_sprzetu.value;
var tid=form.tid.value;
var tidf=form.tidf.value;
var tuwagi=form.tuwagi.value;
var obzp=form.obzp.value;
var ew_id=form.ewid_id.value;
var quiet=form.quiet.value;
var readonly=form.readonly.value;
var thd_zgl_nr=form.hd_zgl_nr.value;
var nazwa_urzadzenia = form.nazwa_urzadzenia.value;
var sn_urzadzenia = form.sn_urzadzenia.value;
var ni_urzadzenia = form.ni_urzadzenia.value;
var dwp = form.dodajwymianepodzespolow.value;
if (document.getElementById('jedna_umowa')==null) {
	var tumowa1=0;
} else {
	if (document.getElementById('jedna_umowa').value==0) {
	var tumowa1=form.tumowa.options[form.tumowa.options.selectedIndex].value;
	} else {
	var tumowa1=form.tumowa.value;
	}
}
self.location='z_towary_obrot.php?id='+tid+'&f='+urlencode1(tidf)+'&new_upid=' + urlencode1(tup) + '&trodzaj=' +urlencode1(trodzaj)+'&tdata=' + tdata + '&nazwa_sprzetu='+urlencode1(snazwa)+'&sn_sprzetu='+urlencode1(ssn)+'&tuwagi='+urlencode1(tuwagi)+'&obzp='+obzp+'&tumowa='+urlencode1(tumowa1)+'&allow_change_rs='+_acrs+'&ewid_id='+ew_id+'&quiet='+quiet+'&nazwa_urzadzenia='+urlencode1(nazwa_urzadzenia)+'&sn_urzadzenia='+urlencode1(sn_urzadzenia)+'&ni_urzadzenia='+urlencode1(ni_urzadzenia)+'&readonly='+readonly+'&hd_zgl_nr='+thd_zgl_nr+'&dodajwymianepodzespolow='+dwp+'&_wp_opis='+urlencode1(wp1_o)+'&_wp_sn='+urlencode1(wp1_sn)+'&_wp_ni='+urlencode1(wp1_ni);
}
function reload2_obrot(form) {
var tup=form.new_upid.options[form.new_upid.options.selectedIndex].value;
var trodzaj=form.trodzaj.options[form.trodzaj.options.selectedIndex].value;
var snazwa=form.nazwa_sprzetu.value;
var ssn=form.sn_sprzetu.value;
var tid=form.tid.value;
var tidf=form.tidf.value;
var thd_zgl_nr=form.hd_zgl_nr.value;
var tuwagi=form.tuwagi.value;
var pn=form.pn.options[form.pn.options.selectedIndex].value;
self.location='z_towary_obrot.php?id='+tid+'&f='+tidf+'&new_upid=' + tup + '&trodzaj=' +trodzaj+'&nazwa_sprzetu='+snazwa+'&sn_sprzetu='+ssn+'&pn='+pn+'&tuwagi='+tuwagi+'&hd_zgl_nr='+thd_zgl_nr;
}
function reload3_obrot(form) {
var tup=form.new_upid.options[form.new_upid.options.selectedIndex].value;
var trodzaj=form.trodzaj.options[form.trodzaj.options.selectedIndex].value;
var snazwa=form.nazwa_sprzetu.value;
var ssn=form.sn_sprzetu.value;
var thd_zgl_nr=form.hd_zgl_nr.value;
var tid=form.tid.value;
var tidf=form.tidf.value;
var tuwagi=form.tuwagi.value;
var pn=form.pn.options[form.pn.options.selectedIndex].value;
var magid=form.sz.options[form.sz.options.selectedIndex].value;
self.location='z_towary_obrot.php?id='+tid+'&f='+tidf+'&new_upid=' + tup + '&trodzaj=' +trodzaj+'&nazwa_sprzetu='+snazwa+'&sn_sprzetu='+ssn+'&pn='+pn+'&sz='+magid+'&tuwagi='+tuwagi+'&hd_zgl_nr='+thd_zgl_nr;
}
function PokazZgloszenie(n) {
	if (!isNaN(parseInt(n))) {
		newWindow_r(800,600,'hd_o_zgloszenia.php?action=podglad&nr='+n+'&id='+n+'');
	} else {
		alert('Błednie podany numer zgłoszenia');
	}
}
</script>
<?php } ?>
<?php $pw = array($_f."/e_towary_obrot.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
<SCRIPT language=JavaScript>
function urlencode1 (str) {
    str = (str+'').toString();
    return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+');
}
function reload1_obrot(form) {
if (document.getElementById('readonly').value=='1') {
	var tup=form.new_upid.value;
} else {
	var tup=form.new_upid.options[form.new_upid.options.selectedIndex].value;
}
var trodzaj=form.trodzaj.options[form.trodzaj.options.selectedIndex].value;
var tdata=form.tdata.value;
var snazwa=form.nazwa_sprzetu.value;
var ssn=form.sn_sprzetu.value;
var tid=form.tid.value;
var tidf=form.tidf.value;
var pozid=form.pozid.value;
var thd_zgl_nr=form.hd_zgl_nr.value;
var tuwagi=form.tuwagi.value;
var obzp=form.obzp.value;
if (document.getElementById('_wp_opis')) { var wp1_o = document.getElementById('_wp_opis').value; } else { var wp1_o = ''; }
if (document.getElementById('_wp_sn')) { var wp1_sn = document.getElementById('_wp_sn').value; } else { var wp1_sn = ''; }
if (document.getElementById('_wp_ni')) { var wp1_ni = document.getElementById('_wp_ni').value; } else { var wp1_ni = ''; }
if (document.getElementById('jedna_umowa')==null) {
	var tumowa1=0;
} else {
	if (document.getElementById('jedna_umowa').value==0) {
	var tumowa1=form.tumowa.options[form.tumowa.options.selectedIndex].value;
	} else {
	var tumowa1=form.tumowa.value;
	}
}
self.location='e_towary_obrot.php?tid='+tid+'&f='+urlencode1(tidf)+'&new_upid=' + urlencode1(tup) + '&trodzaj=' +urlencode1(trodzaj)+'&tdata=' + tdata + '&nazwa_sprzetu='+urlencode1(snazwa)+'&sn_sprzetu='+urlencode1(ssn)+'&tuwagi='+urlencode1(tuwagi)+'&obzp='+obzp+'&tumowa='+urlencode1(tumowa1)+'&pozid='+pozid+'&hd_zgl_nr='+thd_zgl_nr+'&_wp_opis='+urlencode1(wp1_o)+'&_wp_sn='+urlencode1(wp1_sn)+'&_wp_ni='+urlencode1(wp1_ni);
}
function reload2_obrot(form) {
	var tup=form.new_upid.options[form.new_upid.options.selectedIndex].value;
	var trodzaj=form.trodzaj.options[form.trodzaj.options.selectedIndex].value;
	var snazwa=form.nazwa_sprzetu.value;
	var ssn=form.sn_sprzetu.value;
	var thd_zgl_nr=form.hd_zgl_nr.value;
	var tid=form.tid.value;
	var tidf=form.tidf.value;
	var pozid=form.pozid.value;
	var tuwagi=form.tuwagi.value;
	var pn=form.pn.options[form.pn.options.selectedIndex].value;
	self.location='e_towary_obrot.php?id='+tid+'&f='+tidf+'&new_upid=' + tup + '&trodzaj=' +trodzaj+'&nazwa_sprzetu='+snazwa+'&sn_sprzetu='+ssn+'&pn='+pn+'&tuwagi='+tuwagi+'&pozid='+pozid+'&hd_zgl_nr='+thd_zgl_nr;
}
function reload3_obrot(form) {
	var tup=form.new_upid.options[form.new_upid.options.selectedIndex].value;
	var trodzaj=form.trodzaj.options[form.trodzaj.options.selectedIndex].value;
	var thd_zgl_nr=form.hd_zgl_nr.value;
	var snazwa=form.nazwa_sprzetu.value;
	var ssn=form.sn_sprzetu.value;
	var pozid=form.pozid.value;
	var tid=form.tid.value;
	var tidf=form.tidf.value;
	var tuwagi=form.tuwagi.value;
	var pn=form.pn.options[form.pn.options.selectedIndex].value;
	var magid=form.sz.options[form.sz.options.selectedIndex].value;
	self.location='e_towary_obrot.php?id='+tid+'&f='+tidf+'&new_upid=' + tup + '&trodzaj=' +trodzaj+'&nazwa_sprzetu='+snazwa+'&sn_sprzetu='+ssn+'&pn='+pn+'&sz='+magid+'&tuwagi='+tuwagi+'&pozid='+pozid+'&hd_zgl_nr='+thd_zgl_nr;
}
</script>
<?php } ?>
<?php $pw = array($_f."/z_naprawy_napraw5.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
<SCRIPT language=JavaScript>
function reload_naprawy(form) {
	var id=form.id.value;
	var tup=form.tup.value;
	var part=form.part.value;
	var mmodel=form.mmodel.value;
	var msn=form.msn.value;
	var mni=form.mni.value;
	var tstatus1=5;
	if (form.sz!=null) { var sz=form.sz.options[form.sz.options.selectedIndex].value; } else var sz='0';
	var hd_zgl_nr=form.hd_zgl_nr.value;
	var new_upid=form.new_upid.value;
	var up_id=form.upid.value;
	var cs=form.cs.value;
	var dodajwymianepodzespolow=form.dodajwymianepodzespolow.value;
	var ps=form.ps.options[form.ps.options.selectedIndex].value;
	self.location='z_naprawy_napraw5.php?id='+id+'&tup='+tup+'&part=' +part+'&mmodel='+mmodel+'&msn='+msn+'&mni='+mni+'&tstatus1='+tstatus1+'&sz='+sz+'&ps='+ps+'&hd_zgl_nr='+hd_zgl_nr+'&new_upid='+new_upid+'&up_id='+up_id+'&cs='+cs+'&dodajwymianepodzespolow='+dodajwymianepodzespolow+'';
}
function MarkCheckboxes(akcja) {
	for(var i=0,l=document.edu.elements.length; i<l; i++) {
		if (document.edu.elements[i].type == 'checkbox') {
			if (akcja=='odwroc') {
document.edu.elements[i].checked=document.edu.elements[i].checked?false:true;
			} else if (akcja=='zaznacz') {
document.edu.elements[i].checked=true;
			} else if (akcja=='odznacz') {
document.edu.elements[i].checked=false;
			}
		}	
	}
}
</script>
<?php } ?>
<?php $pw = array($_f."/utworz_protokol.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
<SCRIPT language=JavaScript>
function nazwa_przycisku(form) {
	var checkbox1=form.nzpdb.checked;
	if (checkbox1) { form.submit.value='Generuj protokół'; } else {	form.submit.value='Zapisz & Generuj'; }
}
</script>
<?php } ?>
<?php $pw = array($_f."/hd_e_zgloszenie_new.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
<link rel="stylesheet" type="text/css" href="js/jquery/jquery.autocomplete.css" />
<script type="text/javascript" src="js/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="js/jquery/jquery.autocomplete.js"></script>
<script>
function pytanie_zatwierdz_edycja_zgloszenia(message){ 

	if (document.getElementById('hdnzhadim')) {
		if (document.getElementById('hdnzhadim').value=='') { 
			alert('Nie podałeś numeru HDIM'); document.getElementById('hdnzhadim').focus(); return false; 
		}
		
		if ((document.getElementById('hdnzhadim').value.length)<7) {
			alert('Podano za krótki numer HDIM'); document.getElementById('hdnzhadim').focus(); return false; 
		}
	}
			
	if (document.getElementById('up_list')) {
		if (document.getElementById('up_list').value=='') { 
			alert('Nie podałeś komórki'); document.getElementById('up_list').focus(); return false; 
		}
	}
/*	
	if (document.getElementById('hd_oz')) {
		if (document.getElementById('hd_oz').value=='') { 
			alert('Nie podałeś osoby zgłaszającej'); document.getElementById('hd_oz').focus(); return false; 
		}
	}

	if (document.getElementById('hd_oz')) {
		if (OdrzucNiedozwolone('hd_oz',document.getElementById('hd_oz').value)=='1') {
			alert("Wpisano nieprawidłową wartość w polu. Osobę zgłaszającą należy wpisać z imienia i nazwiska");
			document.getElementById('hd_oz').focus();
			return false;
		}
	}
*/
	
	if (document.getElementById('hd_tresc')) {
		if (document.getElementById('hd_tresc').value=='') { 
			alert('Nie podałeś treści zgłoszenia'); document.getElementById('hd_tresc').focus(); return false; 
		}
	}
	
	if (document.getElementById('priorytet_id')) { 
		if (document.getElementById('priorytet_id').value=='') {
			alert('Nie wybrałeś priorytetu zgłoszenia'); 
			document.getElementById('priorytet_id').focus(); return false; 
		}
	}

	if (document.getElementById('podkat_id').value=='') {
		alert('Nie wybrałeś podkategorii'); 
		document.getElementById('podkat_id').focus();
		return false;
	}

	<?php if ($_REQUEST[tpk]!='G') { ?>
		if (document.getElementById('sub_podkat_id').options[document.getElementById('sub_podkat_id').options.selectedIndex].text=='') {
			alert('Nie wybrałeś poziomu 2 podkategorii'); 
			document.getElementById('sub_podkat_id').focus();
			return false;
		}
	<?php } ?>

	if (confirm(message)) { 
		document.getElementById('submit').style.display='none';		
		document.getElementById('reset').style.display='none';
		document.getElementById('anuluj').style.display='none';
		//document.getElementById('Saving').style.display='';
		document.forms.hd_dodaj_zgl.submit(); 
		return true; 
	} else return false; 
	
}
function load_data_1() {
var v = document.getElementById('up_list').value;
  $('#t').load('hd_d_zgloszenie_load_info.php?wk='+urlencode(v)+'');
}
function KopiujDo1Entera(val, dest) {
	var w1 = val.replace("\n","<br>");
	var s1 = w1.search("<br>");
	if (s1>0) { document.getElementById(dest).value=w1.substring(0,s1);	return true; }
	document.getElementById(dest).value=w1;
}
function ex1(val) {
	var text = val.value.replace(/\s+$/g,"") 
	var split = text.split("\n") 
	if (split.length<5) val.rows = split.length+1;
}
function trimAll(sString) {
	while (sString.substring(0,1) == ' ') { sString = sString.substring(1, sString.length); }
	while (sString.substring(sString.length-1, sString.length) == ' ') { sString = sString.substring(0,sString.length-1); }
	return sString;
} 
function cUpper(cObj) { cObj.value=trimAll(cObj.value.toUpperCase()); }

function OdrzucNiedozwolone(iid, v) {
var o = trimAll(v.toUpperCase());
var l = o.length;
if (l==0) return 0;
var x = 0;

if (o.indexOf(" ")<0) return 1;
if (l<6) return 1;

if  ((o.indexOf("NACZELNIK")>=0) || 
	 (o.indexOf("ASYSTENT")>=0) || 
	 (o.indexOf("AGENT")>=0) || 
	 (o.indexOf("AGENTKA")>=0) || 
	 (o.indexOf("AGENT POCZTOWY")>=0) || 
	 (o.indexOf("CIT")>=0) || 
	 (o.indexOf("EKSPEDYCJA")>=0) || 
	 (o.indexOf("KASA GŁÓWNA")>=0) || 
	 (o.indexOf("KASJER")>=0) || 
	 (o.indexOf("KASJERKA")>=0) || 
	 (o.indexOf("KIEROWNIK")>=0) || 
	 (o.indexOf("KIEROWNIK ZMIANY")>=0) || 
	 (o.indexOf("KONTROLER")>=0) || 
	 (o.indexOf("POCZTA POLSKA")>=0) || 
	 (o.indexOf("PRACOWNIK")>=0) || 
	 (o.indexOf("PRACOWNIK UP")>=0) || 
	 (o.indexOf("PRACOWNIK EKSPEDYCJI")>=0) || 
	 (o.indexOf("PRACOWNIK AGENCJI")>=0) || 
	 (o.indexOf("PRACOWNIK FILII")>=0) || 
	 (o.indexOf("PRACOWNIK FUP")>=0)) return 1;
}

function getXhr(){
var xhr = null; 
	if(window.XMLHttpRequest) // Firefox
	   xhr = new XMLHttpRequest(); 
	else if(window.ActiveXObject){ // IE
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
function WybierzWartosc(c) {
	var wartosc = c.split('>>>>>')[1].split('<<<<<');
	var wynik = wartosc[0].substring(1,wartosc[0].length);
	if (wynik=='') { 
		$('#submit').hide();
		$('#info1').show('slow');
	} else {
		$('#info1').hide('slow');
		$('#submit').show();
	}
	return wynik;
}
function urlencode1 (str) {
    str = (str+'').toString();
    return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+');
}
function SprawdzKomorke(k) {
var xhr = getXhr();
	if (document.getElementById('up_list').value!='') {
		xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 && xhr.status == 200){
				leselect = xhr.responseText;
				document.getElementById('up_list_id1').innerHTML = leselect;
				document.getElementById('up_list_id').value = WybierzWartosc((leselect));	
			}
		} 	
		xhr.open("POST","hd_get_up_list_id.php",true);
		xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		sel = document.getElementById('up_list').value;		
		var parametry = ""+urlencode1(sel);
		xhr.send("wybierzid="+parametry);
		return false;
	} else return false;
}
function pytanie_wyczysc(message){ if (confirm(message)) self.location.reload();}
</script>
<?php } ?>
<?php $pw = array($_f."/hd_d_zgloszenie.php"); if (array_search($PHP_SELF, $pw)>-1) { 
include_once('js/hd_d_zgloszenie_i_s_scripts.php');
include_once('js/hd_d_zgloszenie_scripts.php');
}

$pw = array($_f."/hd_d_zgloszenie_simple.php"); if (array_search($PHP_SELF, $pw)>-1) { 
?>
<script type="text/javascript" src="js/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="js/jquery/jquery.autocomplete.js"></script>
<script>
function KopiujDo1Entera(val, dest) {
	var w1 = val.replace("\n","<br>");
	var s1 = w1.search("<br>");
	if (s1>0) {
		document.getElementById(dest).value=w1.substring(0,s1);
		return true;
	}
	document.getElementById(dest).value=w1;
}
function ex1(val) {
	var text = val.value.replace(/\s+$/g,"") 
	var split = text.split("\n") 
	if (split.length<5) val.rows = split.length+1;
}

function pytanie_anuluj(message){ if (confirm(message)) self.close();}
function pytanie_wyczysc(message){ if (confirm(message)) self.location.reload();}

function getHDIM_opis(v) {
	newWindow_r(800,600,"https://sd.cit.net.pp/helpdesk/issue.php?id=" + document.getElementById('hdnzhadim').value + "");

/*	jQuery.ajax({
		url: "https://sd.cit.net.pp/helpdesk/issue.php?id=" + document.getElementById('hdnzhadim').value + "",
		success: function (result) {
			html = jQuery('<div>').html(result);
		//	alert(html.find("textarea#description").html());
			alert(html.find('div#sitesBox').html());
		},
	});
	*/
	//$('#hd_tresc').load('https://sd.cit.net.pp/helpdesk/issue.php?id=2265888 #description');

	
//	$.get('http://www.onet.pl', {}, function (content) { $('hd_tresc').html(content)}, 'html');
}

function filterInput(filterType,evt,allowDecimal,allowCustom){var keyCode,Char,inputField,filter='';var alpha='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';var num='0123456789';if(window.event){keyCode=window.event.keyCode;evt=window.event}else if(evt)keyCode=evt.which;else return true;if(filterType==0)filter=alpha;else if(filterType==1)filter=num;else if(filterType==2)filter=alpha+num;if(allowCustom)filter+=allowCustom;if(filter=='')return true;inputField=evt.srcElement?evt.srcElement:evt.target||evt.currentTarget;if((keyCode==null)||(keyCode==0)||(keyCode==8)||(keyCode==9)||(keyCode==27))return true;Char=String.fromCharCode(keyCode);if((filterType==1)&&(String.fromCharCode(keyCode)==',')){Char='.'}if((filter.indexOf(Char)>-1))return true;else if(filterType==1&&allowDecimal&&(Char=='.')&&inputField.value.indexOf('.')==-1)return true;else return false}
</script>
<link rel="stylesheet" type="text/css" href="js/jquery/jquery.autocomplete.css" />
<?php
include_once('js/hd_d_zgloszenie_i_s_scripts.php');
include_once('js/hd_d_zgloszenie_scripts.php');
}

$pw = array($_f."/hd_d_zgloszenie_s.php");
if (array_search($PHP_SELF, $pw)>-1) {
include_once('js/hd_d_zgloszenie_i_s_scripts.php');
include_once('js/hd_d_zgloszenie_scripts.php');
include_once('js/hd_d_zgloszenie_s_scripts.php');
}
?>
<?php $pw = array($_f."/hd_select_up.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
<SCRIPT LANGUAGE="JavaScript">
function sendValue (s){
var selvalue = s.value;
var w = s.selectedIndex;
var seltext = s.options[w].text
window.opener.document.getElementById('up_list_id').value = selvalue;
window.opener.document.getElementById('up_list').value = seltext;
window.close();
}
</script>
<?php } ?>
<?php $pw = array($_f."/d_faktura_pozycja.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
<script type="text/javascript">
function SprawdzCeneOdsprzedazy(cnetto1, cpopr1, codsp1) {
	if (codsp1=='') return false;
	document.getElementById('warning').style.display='none';
	document.getElementById('warning1').style.display='none';
	document.getElementById('warning2').style.display='none';
	
	cnetto=cnetto1*1;
	cpopr=cpopr1*1;
	codsp=codsp1*1;
	document.getElementById('submit').style.display='';
	if (codsp>cpopr) { 
		document.getElementById('warning').style.display=''; 
		document.getElementById('submit').style.display='none';
		this.focus();
	} else { 
	}
	if (codsp<cnetto) { 
		document.getElementById('warning1').style.display=''; this.focus(); 
		document.getElementById('submit').style.display='none';
	} else { 
	} 
	if ((codsp>=cnetto) && (codsp<cpopr)) {
		document.getElementById('warning2').style.display=''; this.focus(); 
		document.getElementById('submit').style.display='';	
	}
	return false;
}
function PrzecinekNaKropke() {
	if (window.event) {
		key=window.event.keyCode;
		if (key==44) { window.event.keyCode=46; return false; }
		if ((key>47) && (key<58)) return true;
	} else if (e.which) {
		key=e.which;
		if (key==44) { e.which=46; return false; }
		if ((key>47) && (key<58)) {
			return true;
		}
	}
	return false;
}
function ZmienMarze(x) { 
/*	if (x==24) {document.getElementById('marza_dla_kategorii').value='1.00';} else {document.getElementById('marza_dla_kategorii').value=document.getElementById('marza').value;}
*/
	document.getElementById('marza_dla_kategorii').value=document.getElementById('marza').value;
	document.getElementById('uco1').value=((Math.round((document.getElementById('tcena6').value*document.getElementById('marza_dla_kategorii').value)*100)/100)); 
	document.getElementById('cnku').value=((Math.round((document.getElementById('tcena6').value*document.getElementById('marza_dla_kategorii').value)*100)/100));
}
function WyliczNettoJednostkowaZWartosci(x) {
	if (document.getElementById('tilosc').value=='') {
		alert('Nie wpisałeś ilości towaru / usług');
		document.getElementById('tilosc').focus();
		return false;
	}
	if (document.getElementById('twartoscnettol').value=='') {
		alert('Nie wpisałeś wartości netto łącznie za pozycję');
		document.getElementById('twartoscnettol').focus();
		return false;
	}
	document.getElementById('tcena6').value=((Math.round((document.getElementById('twartoscnettol').value/document.getElementById('tilosc').value)*100)/100));
}
</script>
<?php } ?>
<?php $pw = array($_f."/hd_select_pracownik.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
<script type="text/javascript">
$().ready(function() {
	$("#hd_oz").autocomplete("hd_get_pracownik_list.php", {
		width: 360,
		max:50,
		matchContains: true,
		mustMatch: false,
		minChars: 1,
		selectFirst: false
	});
	$("#hd_oz").result(function(event, data, formatted) { $("#hd_oz_id").val(data[1]); 	});
});
</script>
<SCRIPT LANGUAGE="JavaScript">
	function sendValue (s){
	var selvalue = s.value;
	window.opener.document.getElementById('hd_oz').value = selvalue;
	window.close();
	}
</script>
<?php } ?>
<?php $pw = array($_f."/hd_p_zgloszenia.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
<script type="text/javascript" src="js/jquery/jquery-1.6.min.js"></script>

<?php if ($_GET[search]!='1') { ?>
<script type="text/javascript" src="js/anylinkcssmenu/anylinkcssmenu-min.js"></script>
<script type="text/javascript" src="js/anylinkcssmenu/anylink.js"></script>
<?php } ?>
<script type="text/javascript" src="js/jquery/jquery.autocomplete.js"></script>
<script type="text/javascript">
function urlencode1(str) {
    str = (str+'').toString();
    return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+');
}
function ApplyFiltrHD(bool) {
if (bool) {
var sa = $("#sa1").val();
var pa = $("#page11").val();
var pt = $("#page12").val();
var data = $("#filtr1").val();
var kat = $("#filtr2").val();
var podkat = $("#filtr3").val();
var pr = $("#filtr4").val();
var stat = $("#filtr5").val();
var przyp = $("#filtr6").val();
var spr = $("#filtr8").val();
przyp = przyp.replace(" ","+");
var auto = $("#autofiltr").val();
var addit = $("#additional_param").val();
var rpsite1 = $("#rpsite").val();
self.location='hd_p_zgloszenia.php?hd_rps='+rpsite1+'&sa='+sa+'&page='+pa+'&paget='+pt+'&p1='+data+'&p2='+kat+'&p3='+podkat+'&p4='+pr+'&p5='+stat+'&p6='+przyp+'&p7='+auto+'&p8='+spr+'&add='+addit+'';
} else {
	var data = $("#filtr1").val();
	var kat = $("#filtr2").val();
	var podkat = $("#filtr3").val();
	var pr = $("#filtr4").val();
	var stat = $("#filtr5").val();
	var przyp = $("#filtr6").val();
	var spr = $("#filtr8").val();
	var addit = $("#additional_param").val();
	$('#KomunikatOIlosciZgloszen').load('hd_p_zgloszenia_fast_search.php?randval='+ Math.random()+'&p1='+data+'&p2='+kat+'&p3='+podkat+'&p4='+pr+'&p5='+stat+'&p8='+spr+'&add='+addit+'&p6='+urlencode1(przyp)).show();
}
}
function ApplyFiltrHD_clear(bool) {
if (bool) {
var sa = $("#sa1").val();
var pa = $("#page11").val();
var pt = $("#page12").val();
var data = $("#filtr1").val();
var kat = $("#filtr2").val();
var podkat = $("#filtr3").val();
var pr = $("#filtr4").val();
var stat = $("#filtr5").val();
var przyp = $("#filtr6").val();
przyp = przyp.replace(" ","+");
var auto = $("#autofiltr").val();
var addit = $("#additional_param").val();
var rpsite1 = $("#rpsite").val();
addit = '';
self.location='hd_p_zgloszenia.php?hd_rps='+rpsite1+'&sa='+sa+'&page='+pa+'&paget='+pt+'&p1='+data+'&p2='+kat+'&p3='+podkat+'&p4='+pr+'&p5='+stat+'&p6='+przyp+'&p7='+auto+'&add='+addit+'';
} else {
	var data = $("#filtr1").val();
	var kat = $("#filtr2").val();
	var podkat = $("#filtr3").val();
	var pr = $("#filtr4").val();
	var stat = $("#filtr5").val();
	var przyp = $("#filtr6").val();
	var addit = $("#additional_param").val();
	$('#KomunikatOIlosciZgloszen').load('hd_p_zgloszenia_fast_search.php?randval='+ Math.random()+'&p1='+data+'&p2='+kat+'&p3='+podkat+'&p4='+pr+'&p5='+stat+'&add='+addit+'&p6='+urlencode1(przyp)).show();
}
}
<?php if ($_GET[sr]=='1') { ?>


	function AddToList(listname, list_opis, list_value, bool1, bool2) {
		listname.options[listname.options.length] = new Option(""+list_opis+"",""+list_value+"",bool1, bool2);
	}
	function MakePodkategoriaList(o) {
		if (document.getElementById('p2')) {
			var kateg_select = document.getElementById('p2');
		}
		
		if (document.getElementById('p3')) {
			var ps_ = document.getElementById('p3');
			ps_.options.length=0;
		}
		
		if (o == "7") {
			ps_.options[ps_.options.length] = new Option("Konserwacja sprzętu","8",true,true);
			ps_.disabled=false;
			
			if (document.getElementById('status_id')) {
				ss.options[ss.options.length] = new Option("nowe","1",true,true);
				ss.options[ss.options.length] = new Option("rozpoczęte","3",false,false);
				ss.options[ss.options.length] = new Option("rozpoczęte - nie zakończone","7",false,false);
				ss.options[ss.options.length] = new Option("zamknięte","9",false,false);
				ss.disabled=false;
			}
			
			AddToList(sps,'Brak','',true,true);
			
		}
		
		if (o == "1") {
			ps_.options[ps_.options.length] = new Option("Brak","1",true,true);
			ps_.disabled=false;

			AddToList(sps,'Sprzęt','Sprzęt',false,false);
			AddToList(sps,'SP2000','SP2000',true,true);
			AddToList(sps,'Inne','Inne',false,false);
			
		}
		if (o == "2") {
			ps_.options[ps_.options.length] = new Option("dowolna","",true,true);
			ps_.options[ps_.options.length] = new Option("Serwer","4",false,false);
			ps_.options[ps_.options.length] = new Option("Stacja robocza","3",false,false);
			ps_.options[ps_.options.length] = new Option("Urządzenia peryferyjne","9",false,false);
			ps_.options[ps_.options.length] = new Option("Oprogramowanie - wsparcie użytkownika","2",false,false);
			ps_.options[ps_.options.length] = new Option("WAN/LAN","0",false,false);
			ps_.options[ps_.options.length] = new Option("Oprogramowanie - problemy techniczne","7",false,false);
			ps_.disabled=false;			
		}
		
		// awaria krytyczna
		if (o == "6") {
			ps_.options[ps_.options.length] = new Option("dowolna","",true,true);
			ps_.options[ps_.options.length] = new Option("Serwer","4",false,false);
			ps_.options[ps_.options.length] = new Option("Stacja robocza","3",false,false);
			ps_.options[ps_.options.length] = new Option("Urządzenia peryferyjne","9",false,false);
			ps_.options[ps_.options.length] = new Option("WAN/LAN","0",false,false);
			ps_.options[ps_.options.length] = new Option("Oprogramowanie - problemy techniczne","7",false,false);		
			ps_.disabled=false;
		}
		
		if (o == "3") {
			ps_.options[ps_.options.length] = new Option("dowolna","",true,true);
			ps_.options[ps_.options.length] = new Option("Serwer","4",false,false);
			ps_.options[ps_.options.length] = new Option("Stacja robocza","3",false,false);
			ps_.options[ps_.options.length] = new Option("Urządzenia peryferyjne","9",false,false);
			ps_.options[ps_.options.length] = new Option("Oprogramowanie - wsparcie użytkownika","2",false,false);
			ps_.options[ps_.options.length] = new Option("Oprogramowanie - problemy techniczne","7",false,false);
			ps_.options[ps_.options.length] = new Option("Aktualizacje oprogramowania","6",false,false);
			ps_.options[ps_.options.length] = new Option("Kopie bezpieczeństwa","H",false,false);
			ps_.options[ps_.options.length] = new Option("Domena","I",false,false);
			ps_.options[ps_.options.length] = new Option("Alarmy","E",false,false);
			ps_.options[ps_.options.length] = new Option("WAN/LAN","0",false,false);
			ps_.options[ps_.options.length] = new Option("Inne","D",false,false);
			ps_.options[ps_.options.length] = new Option("Otwarcie placówki pocztowej","A",false,false);
			ps_.options[ps_.options.length] = new Option("Przeniesienie placówki pocztowej","B",false,false);
			ps_.options[ps_.options.length] = new Option("Zamknięcie placówki pocztowej","C",false,false);			
			ps_.options[ps_.options.length] = new Option("Prace administracyjno-sprawozdawcze","F",false,false);			
			ps_.disabled=false;
		}
		
		if (o == "4") {
			ps_.options[ps_.options.length] = new Option("dowolna","",true,true);
			ps_.options[ps_.options.length] = new Option("Inne","D",false,false);
			ps_.options[ps_.options.length] = new Option("Otwarcie placówki pocztowej","A",false,false);
			ps_.disabled=false;
		}
		if (o == "5") {
			ps_.options[ps_.options.length] = new Option("Brak","1",true,true);
			ps_.disabled=false;
		}	
		
	}


function DopiszKreski(v) {
	var r = document.getElementById(v).value.length;
	if (r==4) document.getElementById(v).value = document.getElementById(v).value+'-';
	if (r==7) document.getElementById(v).value = document.getElementById(v).value+'-';
}
function filterInput(filterType,evt,allowDecimal,allowCustom){var keyCode,Char,inputField,filter='';var alpha='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';var num='0123456789';if(window.event){keyCode=window.event.keyCode;evt=window.event}else if(evt)keyCode=evt.which;else return true;if(filterType==0)filter=alpha;else if(filterType==1)filter=num;else if(filterType==2)filter=alpha+num;if(allowCustom)filter+=allowCustom;if(filter=='')return true;inputField=evt.srcElement?evt.srcElement:evt.target||evt.currentTarget;if((keyCode==null)||(keyCode==0)||(keyCode==8)||(keyCode==9)||(keyCode==27))return true;Char=String.fromCharCode(keyCode);if((filterType==1)&&(String.fromCharCode(keyCode)==',')){Char='.'}if((filter.indexOf(Char)>-1))return true;else if(filterType==1&&allowDecimal&&(Char=='.')&&inputField.value.indexOf('.')==-1)return true;else return false}
<?php } ?>
function filterInputEnter(filterType,evt,allowDecimal,allowCustom){var keyCode,Char,inputField,filter='';var alpha='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';var num='0123456789';if(window.event){keyCode=window.event.keyCode;evt=window.event}else if(evt)keyCode=evt.which;else return true;if(filterType==0)filter=alpha;else if(filterType==1)filter=num;else if(filterType==2)filter=alpha+num;if(allowCustom)filter+=allowCustom;if(filter=='')return true;inputField=evt.srcElement?evt.srcElement:evt.target||evt.currentTarget;if((keyCode==null)||(keyCode==0)||(keyCode==8)||(keyCode==9)||(keyCode==13)||(keyCode==27))return true;Char=String.fromCharCode(keyCode);if((filterType==1)&&(String.fromCharCode(keyCode)==',')){Char='.'}if((filter.indexOf(Char)>-1))return true;else if(filterType==1&&allowDecimal&&(Char=='.')&&inputField.value.indexOf('.')==-1)return true;else return false}
<?php if ($_GET[sr]=='1') { ?>
$().ready(function() {
	$("#sk").autocomplete("hd_get_up_list.php?filia=<?php echo $es_filia; ?>", {
		width: 360,
		max:150,
		matchContains: true,
		mustMatch: false,
		minChars: 1,
		selectFirst: false
	});	
	$("#sk").result(function(event, data, formatted) { $("#sk_id").val(data[1]); });
});
function SzukajSprawdzPola() {
	if (($("#search_zgl_nr").val()=='') && 
		($("#search_hadim_nr").val()=='') && 
		($("#search_eserwis_nr").val()=='') && 
		($("#sd").val()=='') && 
		($("#st").val()=='') && 
		($("#so").val()=='') && 
		($("#st_wc").val()=='') && 
		($("#p6").val()=='') && 
		($("#p2").val()=='') && 
		($("#p3").val()=='') && 
		($("#sk").val()=='')) {
			alert('Nie podałeś żadnego kryterium wyszukiwania'); 
			return false;		
		}
}
<?php } else { ?>
$().ready(function() {
	$("#sk").autocomplete("hd_get_up_list.php?filia=<?php echo $es_filia; ?>", {
		width: 360,
		max:150,
		matchContains: true,
		mustMatch: false,
		minChars: 1,
		selectFirst: false
	});	
	$("#sk").result(function(event, data, formatted) { $("#sk_id").val(data[1]); });
});
<?php } ?>
function PrzypiszSeryjnieDoOsoby() {
var elLength = document.obsluga.elements.length;
	if (document.getElementById('pdo').value=='') {
		alert('Nie wybrano osoby do której mają być przypisane wybrane zgłoszenia');
		return false;
	}
	var count_checked = 0;
	var params = "numery=";
    for (i=0; i<elLength; i++){
        var type = document.obsluga.elements[i].type;
        if (type=="checkbox" && document.obsluga.elements[i].checked && (document.obsluga.elements[i].name.substring(0,7)=='markzgl')){
			count_checked = count_checked + 1;
			params += document.obsluga.elements[i].value + ",";
        }
    }
	var dl = params.length;
	dl--;
	if (dl==6) {
		alert('Nie zaznaczyłeś żadnego zgłoszenia');
		return false;
	}
	var params1 = params.substring(0,dl);
	var osobaw = document.getElementById('pdo').value;
	var lokacja = "hd_o_zgloszenia_s_przypisz_do_osoby.php?" + params1 + "&cnt=" + count_checked + "&wo=" + urlencode1(osobaw);
	var x = 1;
	var y = 1;
	if (window.screen) {
		var ah = screen.availHeight - 30;
		var aw = screen.availWidth - 10;
		var xx=(aw-x) / 2;
		xx=Math.round(xx);
		var yy=(ah-y) / 2;
		yy=Math.round(yy);
		var opcje="scrollbars=no, width="+x+", innerWidth="+x+", height="+y+", innerHeight="+y+", top="+yy+", screenX="+xx+", screenY="+yy+", left="+xx;
	}
	window.open(lokacja, "eSerwisOSZPDO", opcje);
}

</script>
<?php } ?>
<?php $pw = array($_f."/main.php"); if ((array_search($PHP_SELF, $pw)>-1) && ($wersja_light!='on') && ($_GET[action]=='')) {
?>
<script type="text/javascript" src="js/jquery/jquery-1.6.min.js"></script>
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
function eraseCookie(name) { createCookie(name,"",-1); }
function refresh_hd() {
	$(document).ready(function() {
	 	$("#liczniki_moje_na_startowej").load('hd_p_zgloszenia_live_view_m_na_startowej_new.php?f=<?php echo $es_filia; ?>&randval='+ Math.random()+'&range=M&moj_nr=<?echo $es_nr; ?>&cu=<?php echo urlencode($currentuser); ?>');		
		$("#liczniki_wszystkie_na_startowej").load('hd_p_zgloszenia_live_view_w_na_startowej_new.php?f=<?php echo $es_filia; ?>&randval='+ Math.random()+'&range=W&moj_nr=<?echo $es_nr; ?>&cu=<?php echo urlencode($currentuser); ?>');
		$('#count_notatki_moje').load('hd_count_notes.php?user_id=<?php echo $es_nr; ?>&randval='+ Math.random()).show();		
	});
}
function refresh_notatki() {
	$(document).ready(function() {
		if ((readCookie('show_notatki')=='all') || (readCookie('show_notatki')==null)) {
			$('#notatki').load('hd_refresh_notes_na_startowej.php?userid=<?php echo $es_nr; ?>&randval='+ Math.random()+'&today=&nastartowej=1');
			$('#notatki_suffix').text(' | wszystkie');
		}
		if ((readCookie('show_notatki')=='today')) {
			$('#notatki').load('hd_refresh_notes_na_startowej.php?userid=<?php echo $es_nr; ?>&randval='+ Math.random()+'&today=<?php echo Date('Y-m-d'); ?>&nastartowej=1');
			$('#notatki_suffix').text(' | na dzisiaj');
		}
	});
}
function refresh_notatki1() {
	$(document).ready(function() {
		if ((readCookie('show_notatki')=='all') || (readCookie('show_notatki')==null)) {
			$('#notatki').load('hd_refresh_notes_na_startowej.php?userid=<?php echo $es_nr; ?>&randval='+ Math.random()+'&today=&nastartowej=1');
			$('#notatki_suffix').text(' | wszystkie');
		}
		if ((readCookie('show_notatki')=='today')) {
			$('#notatki').load('hd_refresh_notes_na_startowej.php?userid=<?php echo $es_nr; ?>&randval='+ Math.random()+'&today=<?php echo Date('Y-m-d'); ?>&nastartowej=1');
			$('#notatki_suffix').text(' | na dzisiaj');
		}
	});
}
function refresh_zadania1a() {
//	$(document).ready(function() {

		if ((readCookie('show_zadania')=='all')) {
			$('#zadania1').load('hd_refresh_zadania.php?osoba=<?php echo urlencode($currentuser); ?>&userid=<?php echo $es_nr; ?>&randval='+ Math.random()+'&today=&nastartowej=1');
			$('#zadanie_suffix').text(' | wszystkie');
		} 
		
		if ((readCookie('show_zadania')=='today') || (readCookie('show_zadania')==null)) {
			$('#zadania1').load('hd_refresh_zadania.php?osoba=<?php echo urlencode($currentuser); ?>&userid=<?php echo $es_nr;?>&randval='+ Math.random()+'&nastartowej=1&today=<?php echo Date('Y-m-d'); ?>');
			$('#zadanie_suffix').text(' | na dzisiaj');
		}
//	});
}
</script>
<script>
	if (readCookie('p_raport_hd')=='TAK') {
		$(document).ready(function() {
			$('#licznik_refresh_na_startowej1').hide();
			$('#count_notatki_moje').load('hd_count_notes.php?user_id=<?php echo $es_nr; ?>&randval='+ Math.random()).show();
			$('#notatki').load('hd_refresh_notes_na_startowej.php?userid=<?php echo $es_nr; ?>&randval='+ Math.random());
			var timetowait = (1000*60*<?php echo $noofminutes; ?>);
			var refreshId_co_n_minut = setInterval(function() { 
			$('#licznik_refresh_na_startowej').load('hd_uaktualnij_liczniki.php?f=<?php echo $es_filia; ?>&range=MW&moj_nr=<?php echo $es_nr; ?>&randval='+Math.random()+'&cu=<?php echo urlencode($currentuser); ?>&refresh_parent=0&todiv=1&sourcepage=start');
		   }, timetowait);
	});
}
</script>
<?php } ?>
<?php
$pw = array($_f."/hd_o_zgloszenia.php",$_f."/hd_g_statystyka_zgloszen_rozbudowany.php");
if (array_search($PHP_SELF, $pw)>-1) {
?>
<?php if ($_GET[action]=='obsluga') { ?>
<script type="text/javascript" src="js/jquery/jquery-1.6.min.js"></script>
<?php } ?>
<?php } ?>
<?php
$pw = array($_f."/hd_o_zgloszenia_zs.php");
if (array_search($PHP_SELF, $pw)>-1) {
?>
<link rel="stylesheet" type="text/css" href="js/jquery/jquery.autocomplete.css" />
<link type="text/css" rel="stylesheet" media="screen" href="js/jquery/jquery.toChecklist.css" />
<link rel="stylesheet" type="text/css" href="css/jquery.ipromptu.css" />
<script type="text/javascript" src="js/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="js/jquery/jquery.toChecklist_min.js"></script>
<script type="text/javascript" src="js/jquery/jquery.autocomplete.js"></script>
<script type="text/javascript" src="js/jquery/jquery.impromptu.3.1.js"></script>
<script>
$().ready(function() {
	$("#userlist").toChecklist({
		showSelectedItems : true,
		submitDataAsArray : true,
		showCheckboxes:true,
		preferIdOverName : false
	});	
	$("#WieleOsobWybor").hide();
});
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
function ClearCookie (name, value) {  
	document.cookie = name + "=" + escape (value) + ";expires=Thu, 01-Jan-1970 00:00:01 GMT";
}
function SetCookie(name, value) {  
	var argv = SetCookie.arguments;  
	var argc = SetCookie.arguments.length;  
	var expires = (argc > 2) ? argv[2] : null;  
	var path = (argc > 3) ? argv[3] : null;  
	var domain = (argc > 4) ? argv[4] : null;  
	var secure = (argc > 5) ? argv[5] : false;  
	document.cookie = name + "=" + escape (value) + 
	((expires == null) ? "" : ("; expires=" + expires.toGMTString())) + 
	((path == null) ? "" : ("; path=" + path)) +  
	((domain == null) ? "" : ("; domain=" + domain)) +    
	((secure == true) ? "; secure" : "");
}
function urlencode1(str) {
    str = (str+'').toString();
    return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+');
}
function ChangeWay(nr) {
if (nr==null) nr=2;
<?php //if ($_REQUEST[ts]=='3A') { ?>
	if (nr==0) { $('#way_1').hide(); $('#way_2').hide(); $('#way_3').hide(); } else { $('#way_1').hide(); $('#way_2').hide(); $('#way_3').hide(); $('#way_'+nr+'').show(); }
<?php //} ?>
}
</script>
<?php } ?>
<?php 
$pw = array($_f."/hd_d_zgloszenie.php",$_f."/hd_d_zgloszenie_s.php",$_f."/hd_o_zgloszenia_zs.php",$_f."/hd_e_zgloszenie.php");
if (array_search($PHP_SELF, $pw)>-1) { 
?>
<SCRIPT language=JavaScript>
function load_data_1() {
var v = document.getElementById('up_list').value;
  $('#t').load('hd_d_zgloszenie_load_info.php?wk='+urlencode(v)+'');
}
function trimAll(sString) {
	while (sString.substring(0,1) == ' ') { sString = sString.substring(1, sString.length); }
	while (sString.substring(sString.length-1, sString.length) == ' ') { sString = sString.substring(0,sString.length-1); }
	return sString;
} 
function OdrzucNiedozwolone(iid, v) {
var o = trimAll(v.toUpperCase());
var l = o.length;
if (l==0) return 0;
var x = 0;

if (o.indexOf(" ")<0) return 1;
if (l<6) return 1;

if  ((o.indexOf("NACZELNIK")>=0) || 
	 (o.indexOf("ASYSTENT")>=0) || 
	 (o.indexOf("AGENT")>=0) || 
	 (o.indexOf("AGENTKA")>=0) || 
	 (o.indexOf("AGENT POCZTOWY")>=0) || 
	 (o.indexOf("CIT")>=0) || 
	 (o.indexOf("EKSPEDYCJA")>=0) || 
	 (o.indexOf("KASA GŁÓWNA")>=0) || 
	 (o.indexOf("KASJER")>=0) || 
	 (o.indexOf("KASJERKA")>=0) || 
	 (o.indexOf("KIEROWNIK")>=0) || 
	 (o.indexOf("KIEROWNIK ZMIANY")>=0) || 
	 (o.indexOf("KONTROLER")>=0) || 
	 (o.indexOf("POCZTA POLSKA")>=0) || 
	 (o.indexOf("PRACOWNIK")>=0) || 
	 (o.indexOf("PRACOWNIK UP")>=0) || 
	 (o.indexOf("PRACOWNIK EKSPEDYCJI")>=0) || 
	 (o.indexOf("PRACOWNIK AGENCJI")>=0) || 
	 (o.indexOf("PRACOWNIK FILII")>=0) || 
	 (o.indexOf("PRACOWNIK FUP")>=0)) return 1;
}
</script>
<?php } ?>
<?php
$pw = array($_f."/hd_o_zgloszenia_zs.php",$_f."/hd_o_zgloszenia_s.php");
if (array_search($PHP_SELF, $pw)>-1) {
?>
<script>	
function formatTime(time) {
    var result1 = false, m;
    var re = /^\s*([01]?\d|2[0-3]):?([0-5]\d)\s*$/;
    if ((m = time.match(re))) {
        //result1 = (m[1].length == 2 ? "" : "0") + m[1] + ":" + m[2];
		result1 = true;
    }
    return result1;
}
function CheckTime(t) {
if (formatTime(t)==false) {
	alert('Błędnie wpisana godzina'); document.getElementById('zs_time').value=''; document.getElementById('zs_time').focus(); return false;
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
	if (TerazData==document.getElementById('hddz').value) {
		if (CzasWpisany>TerazCzas) { alert('Wpisałeś godzinę która jeszcze nie nastała'); document.getElementById('zs_time').focus(); }
	}
	return true;
} 
}
function DopiszDwukropek(v) {
var r = document.getElementById(v).value.length;
if (r==2) document.getElementById(v).value = document.getElementById(v).value+':';
if (r==5) CheckTime(document.getElementById(v).value);
}
function CompareDates() {
	var last_step_date = document.getElementById('data_ostatniego_kroku_value').value;
	var last_step_time= document.getElementById('godzina_ostatniego_kroku_value').value;
	var new_step_date = document.getElementById('zs_data').value;
	var new_step_time = document.getElementById('zs_time').value;
	var str1 = last_step_date+" "+last_step_time;
	var str2 = new_step_date+" "+new_step_time+":00";
	if (str1 == str2) {
		alert("daty są takie same");
	}
	
	if (str1>str2) {
		alert("data ostatniego kroku jest wcześniejsza niż nowego kroku");
	}
	
	if (str1<str2) {
		alert("nowa data jest poprawna");
	}
}
function cUpper(cObj) { cObj.value=trimAll(cObj.value.toUpperCase()); }
function pytanie_zapisz_zs(message){ 
	var last_step_date = document.getElementById('data_ostatniego_kroku_value').value;
	var last_step_time= document.getElementById('godzina_ostatniego_kroku_value').value;
	var new_step_date = document.getElementById('zs_data').value;
	var new_step_time = document.getElementById('zs_time').value;
	var new_cw_h = document.getElementById('czas_wykonywania_h').value;
	var new_cw_m = document.getElementById('czas_wykonywania_m').value;
	var last_step_time_in_seconds = ((last_step_time.substring(0,2)*1*3600)+(last_step_time.substring(3,5)*1*60)+(last_step_time.substring(6,8)*1));
	var new_step_time_in_seconds = ((new_step_time.substring(0,2)*1*3600)+(new_step_time.substring(3,5)*1*60)+(new_step_time.substring(6,8)*1))-((new_cw_h*1*3600)+(new_cw_m*1*60));
	var h = Math.floor(new_step_time_in_seconds / 3600);
	var m = Math.floor(new_step_time_in_seconds % 3600 / 60);
	if (h<10) h = "0"+h+"";
	if (m<10) m = "0"+m+"";
	if (new_step_time_in_seconds<=last_step_time_in_seconds) {
		if (last_step_date==new_step_date) {
			alert('Nowa data zakończenia kroku, po uwzględnieniu czasu wykonywania ('+new_step_date+' '+h+':'+m+') jest wcześniejsza niż data zakończenia poprzedniego kroku ('+last_step_date+' '+last_step_time.substring(0,5)+')');
			return false;
		}
	}
	if (document.getElementById('zs_time').value=='') { 
	//	alert('Nie podałeś godziny zmiany statusu'); document.getElementById('zs_time').focus(); return false; 
	} else {
		//if (CheckTime(document.getElementById('zs_time').value)==false) return false;
	}
	if (document.getElementById('StatusZakonczony').style.display=='') {
		/*if ((document.getElementById('czas_wykonywania_h').value=='') && (document.getElementById('czas_wykonywania_m').value=='')) {
			alert('Nie podałeś czasu wykonywania zgłoszenia');
			document.getElementById('czas_wykonywania_h').focus();
			return false;
		}*/
	}
	
	<?php if ($zerowe_czasy_wykonania==FALSE) { ?>
		/*if ((document.getElementById('czas_wykonywania_h').value==0) && (document.getElementById('czas_wykonywania_m').value==0)) {
			alert('Podano zerowy czas wykonywania');
			document.getElementById('czas_wykonywania_m').select();
			document.getElementById('czas_wykonywania_m').focus();
			return false;
		}*/
	<?php } ?>

	if (document.getElementById('zs_wcz').value=='') {
		alert('Nie wpisano żadnych wykonanych czynności'); document.getElementById('zs_wcz').focus(); return false; 
	}
	
	if (document.getElementById('PozwolWpisacKm').checked) {	
		
		if (document.getElementById('hd_wyjazd_rp').value=='P') {
			if (document.getElementById('trasa').value=='') {
alert('Nie wpisałeś trasy przejazdu');	document.getElementById('trasa').focus();	return false;
			}
			if (document.getElementById('km').value=='') {
alert('Nie wpisałeś ilości km');	document.getElementById('km').focus();	return false;
			}		
			
			if ((document.getElementById('km').value=='0') || (document.getElementById('km').value=='00') || (document.getElementById('km').value=='000')) {
			//	alert('Podałeś złą ilość km');	document.getElementById('km').focus();	return false;
			}
		}
		if ((document.getElementById('czas_przejazdu_h').value=='0') && (document.getElementById('czas_przejazdu_m').value=='0')) {
			if (confirm('Podano zerowy czas trwania wyjazdu. Czy chcesz poprawić te dane ?')) {
				//alert('Nie podałeś czasu trwania wyjazdu');
				document.getElementById('czas_przejazdu_m').focus();
				return false;
			}
		}
	}
	if (document.getElementById('AwariaWAN').style.display=='') {
		if (document.getElementById('numerzgloszenia').value=='') {
			alert('Nie wpisałeś numeru zgłoszenia w Orange'); document.getElementById('numerzgloszenia').focus(); return false; 
		}
	}

	if (document.getElementById('SelectZmienStatus').value=='9') {	
		if (OdrzucNiedozwolone('hd_opz',document.getElementById('hd_opz').value)=='1') {
			alert("Wpisano nieprawidłową wartość w polu. Osoba potwierdzająca zamknięcie zgłoszenia należy wpisać z imienia i nazwiska");
			document.getElementById('hd_opz').focus();
			return false;
		}
	}
	
/*	if (document.getElementById('DodajDoBazyWiedzyChkBox').checked) {	
		
		if (document.getElementById('dnp').value=='') {
			alert('Nie wpisano tematu wątku w bazie wiedzy');
			document.getElementById('dnp').focus();
			return false;
		}
		if (document.getElementById('DodajDoBazyWiedzySelect').selectedIndex== '0') {
			alert('Nie wybrano kategorii w bazie wiedzy, do której ma być dodany nowy wątek');
			document.getElementById('DodajDoBazyWiedzySelect').focus();
			return false;
		}
	}
*/	
	if (confirm(message)) { 
		document.getElementById('content').style.display='none';
		document.getElementById('submit').style.display='none';
		document.getElementById('anuluj').style.display='none';
		document.getElementById('Saving').style.display='';
		document.forms.hd_o_zgloszenia.submit(); 
		return true; 
	} else return false; 
}
function CheckTime_req2(t) {
	if (formatTime(t)==false) {
		document.getElementById('oz_godzina_dla_wszystkich').value=''; document.getElementById('oz_godzina_dla_wszystkich').focus(); return false;
	}
}
function CheckTime_req(t,v) {
	if (t=='') {
		if (confirm('Pole z godziną nie może być puste !\r\n\r\nUstawić wartość automatyczną ?')) {
			v.value=document.getElementById('oz_godzina_dla_wszystkich').value;
		} else {
			v.focus();
			return false;
		}
	}
	t=v.value;
	if (formatTime(t)==false) {
		document.getElementById(v.name).value=''; document.getElementById(v.name).focus(); return false;
	}
}
function filterInput(filterType,evt,allowDecimal,allowCustom){var keyCode,Char,inputField,filter='';var alpha='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';var num='0123456789';if(window.event){keyCode=window.event.keyCode;evt=window.event}else if(evt)keyCode=evt.which;else return true;if(filterType==0)filter=alpha;else if(filterType==1)filter=num;else if(filterType==2)filter=alpha+num;if(allowCustom)filter+=allowCustom;if(filter=='')return true;inputField=evt.srcElement?evt.srcElement:evt.target||evt.currentTarget;if((keyCode==null)||(keyCode==0)||(keyCode==8)||(keyCode==9)||(keyCode==27))return true;Char=String.fromCharCode(keyCode);if((filterType==1)&&(String.fromCharCode(keyCode)==',')){Char='.'}if((filter.indexOf(Char)>-1))return true;else if(filterType==1&&allowDecimal&&(Char=='.')&&inputField.value.indexOf('.')==-1)return true;else return false}
function pytanie_anuluj(message){ if (confirm(message)) self.close();}
function pytanie_wyczysc(message){ if (confirm(message)) self.location.reload();}
function PokazUkryjDaneOZamowieniu(bool) {
	document.getElementById('TRZam_dw').style.display='none';
	document.getElementById('TRZam_nz').style.display='none';
	document.getElementById('TRZam_uwagi').style.display='none';		
	if (bool) {
		PokazUkryjDaneOOfercie(false);
		document.getElementById('TRZam_dw').style.display='';
		document.getElementById('TRZam_nz').style.display='';
		document.getElementById('TRZam_uwagi').style.display='';
	}
}
function PokazUkryjDaneOOfercie(bool) {
	document.getElementById('TROferta_dw').style.display='none';
	document.getElementById('TROferta_no').style.display='none';
	document.getElementById('TROferta_uwagi').style.display='none';		
	if (bool) {
		PokazUkryjDaneOZamowieniu(false);
		document.getElementById('TROferta_dw').style.display='';
		document.getElementById('TROferta_no').style.display='';
		document.getElementById('TROferta_uwagi').style.display='';
	}
}
function in_array (needle, haystack, argStrict) {
    // http://kevin.vanzonneveld.net
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: vlado houba
    // +   input by: Billy
    // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
    // *     example 1: in_array('van', ['Kevin', 'van', 'Zonneveld']);
    // *     returns 1: true
    // *     example 2: in_array('vlado', {0: 'Kevin', vlado: 'van', 1: 'Zonneveld'});
    // *     returns 2: false
    // *     example 3: in_array(1, ['1', '2', '3']);
    // *     returns 3: true
    // *     example 3: in_array(1, ['1', '2', '3'], false);
    // *     returns 3: true
    // *     example 4: in_array(1, ['1', '2', '3'], true);
    // *     returns 4: false
    var key = '',
        strict = !! argStrict;
    if (strict) {
        for (key in haystack) {
            if (haystack[key] === needle) {
                return true;
            }
        }
    } else {
        for (key in haystack) {
            if (haystack[key] == needle) {
                return true;
            }
        }
    }
    return false;
}
function SelectZmienStatusOnBlur() {

	$('#OfertaWyslana').hide();
	$('#ZamowienieWyslane').hide();
	$('#ZamowienieZrealizowane').hide();
	$('#AwariaWAN').hide();
	$('#ZasadnoscZgloszenia').hide();
	$('#OsobaPotwierdzajacaZamkniecie').hide();
	$('#NrZgloszeniaGdansk').hide();
	$('#WymianaPodzespolu').hide;
	$('#PrzyjmijUszkodzonySprzet').hide();
	//$('#ZmianaPriorytetuInfo').hide();
	$('#InformacjaOZmianiePriorytetuZgloszenia').hide();
	$('#ZmianaStatusuNaprawy').hide();
	$('#OddajSprzetDoKlienta').hide();
	$('#NaprawaWeWlasnymZakresie').hide();
	$('#PrzerzucZNWWZDoSerwisuZewnetrznego').hide();
	$('#ZakonczNaprawe').hide();
	$('#GotowyDoOddania').hide();
	$('#PowiazZNaprawaWSZ').hide();
	$('#TRSelectZdiagnozowany').hide();
	$('#TROfertaWyslana').hide();
	$('#TRAkceptacjaKosztow').hide();
	$('#TRZamowienieWyslane').hide();
	$('#TRZamowienieZrealizowane').hide();
	$('#TRGotoweDoOddania').hide();
	$('#PowiazaneZWyjazdem').show();
	$('#WieleOsob').show();
	$('#show_ww').show();
	$('#WycofanyOddajDoKlienta').hide();
	$('#PrzekazSprzetSerwisowy').hide();
	$('#ZwrocSprzetSerwisowy').hide();
	$('#PobierzSprzetSerwisowy').hide();
	$('#OpcjeDlaNaprawyTrwajacej').hide();
	$('#WycofajSprzetZSerwisu').hide();
	$('#ZmienSerwisZewnetrzny').hide();
	
	var NS = document.getElementById('SelectZmienStatus').value;
	var NK = document.getElementById('zs_kategoria').value;
	var NPK = document.getElementById('zs_podkategoria').value;
	var TNS = document.getElementById('tnaprawastatus').value;
	var PSS = document.getElementById('PrzekazSprzetSerwisowy_value').value;
	
	var czy_ww = document.getElementById('ww_value').value;	
	if (czy_ww==1) {
		document.getElementById('wymaga_wyjazdu').checked=true;
	} else {
		document.getElementById('wymaga_wyjazdu').checked=false;
	}
	
	document.getElementById('wymaga_wyjazdu_text').style.display='none';
	if (document.getElementById('block_rozliczone1')) $('#block_rozliczone1').show();
	if (document.getElementById('block_rozliczone2')) $('#block_rozliczone2').show();
	if (document.getElementById('block_rozliczone3')) $('#block_rozliczone3').show();
	if (document.getElementById('bottombuttons')) $('#bottombuttons').show();
	
	$('#submit').show();
	PokazUkryjDaneOOfercie(false);
	PokazUkryjDaneOZamowieniu(false);
	if (NS=='9') {
		$('#ZasadnoscZgloszenia').hide();
		$('#OsobaPotwierdzajacaZamkniecie').hide();
		$('#InformacjaOZmianiePriorytetuZgloszenia').show();
	} else {
		$('#ZasadnoscZgloszenia').hide();
		$('#OsobaPotwierdzajacaZamkniecie').hide();
		$('#InformacjaOZmianiePriorytetuZgloszenia').hide();
	}
	
	if (NS=='5') {
		$('#ZamowienieWyslane').show();
		$('#ZamowienieZrealizowane').show();
	} else {
		$('#ZamowienieWyslane').hide();
		$('#ZamowienieZrealizowane').hide();
	}
	
	if (in_array(NS,['3','3A','3B','4','5','6','7','9'])) {
		$('#TRSelectZdiagnozowany').show();
		$('#TROfertaWyslana').show();
		$('#TRAkceptacjaKosztow').show();
		$('#TRZamowienieWyslane').show();
		$('#TRZamowienieZrealizowane').show();
		$('#TRGotoweDoOddania').show();			
		$('#Zdiagnozowany').show();
		$('#OfertaWyslana').show();
		$('#AkceptacjaKosztow').show();
		$('#ZamowienieWyslane').show();
		$('#ZamowienieZrealizowane').show();
		$('#GotowyDoOddania').show();
	} 
	if ((in_array(NS,['3A'])) && (in_array(NK,['2','3','6'])) && (in_array(NPK, ['2','5','6','7']))) { 
		$('#NrZgloszeniaGdansk').show(); 
	}
	if ((in_array(NS,['3A'])) && (in_array(NK,['2','3','6'])) && (in_array(NPK,['2','3','4','5','7','9'])) && (in_array(TNS,['']))) {
		$('#PowiazZNaprawaWSZ').show();
		$('#submit').show();
	}
	if ((in_array(NS,['3A'])) && (in_array(NK,['2','3','6'])) && (in_array(NPK,['0','2','3','4','5','7','9'])) && (in_array(TNS,['-1']))) {
		$('#PowiazZNaprawaWSZ').hide();
		$('#ZmianaStatusuNaprawy').show();
		$('#NaprawaWeWlasnymZakresie').show();
		$('#submit').hide();
	}
	if ((in_array(NS,['3A'])) && (in_array(NK,['2','3','6'])) && (in_array(NPK,['0','2','3','4','5','7','9'])) && (!in_array(TNS,['','-1']))) {
		$('#PowiazZNaprawaWSZ').hide();
		$('#ZmianaStatusuNaprawy').hide();
		$('#submit').show();	
	}
	if ((in_array(NS,['3B'])) && (in_array(NK,['2','3','6'])) && (in_array(NPK,['0','2','3','4','5','7','9'])) && (in_array(TNS,['1','2']))) {
		$('#WycofajSprzetZSerwisu').show();
	}
	if ((in_array(NS,['3A'])) && (in_array(NK,['2','3','6'])) && (in_array(NPK,['0','2','3','4','5','7','9'])) && (in_array(TNS,['1','2']))) {
		$('#ZmienSerwisZewnetrzny').show();
		$('#WycofajSprzetZSerwisu').show();
	}
	if ((in_array(NS,['3A','3B','6','9'])) && (in_array(NK,['2','3','6'])) && (in_array(NPK,['0','2','3','4','5','7','9'])) && (in_array(TNS,['-1','0']))) {
		$('#WycofajSprzetZSerwisu').show();
	}
	if ((in_array(NS,['3A'])) && (in_array(NK,['2','3','6'])) && (in_array(NPK,['0','2','3','4','5','7','9'])) && (!in_array(TNS,['-1','0','']))) {
		$('#PowiazZNaprawaWSZ').hide();
		$('#PrzerzucZNWWZDoSerwisuZewnetrznego').hide();
		$('#submit').show();
		
	}
	if ((in_array(NS,['3A'])) && (in_array(NK,['2','3','6'])) && (in_array(NPK,['0','2','3','4','5','7','9'])) && (in_array(TNS,['0']))) {
		$('#PowiazZNaprawaWSZ').hide();
		$('#PrzerzucZNWWZDoSerwisuZewnetrznego').show();
		$('#submit').hide();
	}
	if ((in_array(NS,['3A'])) && (in_array(NK,['2','3','6'])) && (in_array(NPK,['0','2','3','4','5','7','9'])) && (in_array(TNS,['3']))) {
		$('#PowiazZNaprawaWSZ').hide();
		$('#OddajSprzetDoKlienta').show();
		$('#submit').hide();
	}
	if ((in_array(NS,['6'])) && (in_array(NK,['2','3','6'])) && (in_array(NPK,['0','2','3','4','5','7','9'])) && (in_array(TNS,['-1','0','1','2']))) {
		$('#OddajSprzetDoKlienta').show();
		$('#submit').hide();	
	}
	if ((in_array(NS,['6'])) && (in_array(NK,['2','3','6'])) && (in_array(NPK,['0','2','3','4','5','7','9'])) && (in_array(TNS,['3']))) {
		$('#OddajSprzetDoKlienta').show();
		$('#submit').show();	
	}
	if ((in_array(NS,['6'])) && (in_array(NK,['2','3','6'])) && (in_array(NPK,['0','2','3','4','5','7','9'])) && (!in_array(TNS,['-1','0','1','2','3']))) {
		$('#OddajSprzetDoKlienta').hide();
		$('#submit').show();	
	}
	if (in_array(NS,['6'])) {
		document.getElementById('wymaga_wyjazdu').checked=true;
		document.getElementById('wymaga_wyjazdu_text').style.display='';	
	}
	var hidesubmit = 0;
	if ((in_array(NS,['9'])) && (in_array(NK,['2','3','6'])) && (in_array(NPK,['0','2','3','4','5','7','9'])) && (!in_array(TNS,['5','']))) {
		$('#UtworzProtokolButton').show();
		$('#ZakonczNaprawe').show();		
		if (!in_array(NK,['2','6'])) $('#submit').hide();
		if (in_array(TNS,['8'])) $('#submit').show();
		if (!in_array(TNS,['5','8'])) hidesubmit = 1;
		if ((!in_array(NK,['2','6'])) && (in_array(TNS,['3']))) {
			hidesubmit = 1;
			$('#submit').hide();			
		}	
	}
	if ((in_array(NS,['9'])) && (in_array(NK,['2','3','6'])) && (in_array(NPK,['0','2','3','4','5','7','9'])) && (in_array(TNS,['5']))) {
		$('#UtworzProtokolButton').show();
		$('#ZakonczNaprawe').hide();
		$('#submit').show();	
	}
	if ((in_array(NS,['9'])) && (in_array(NK,['2','3','6'])) && (in_array(NPK,['0','2','3','4','5','7','9'])) && (in_array(TNS,['7']))) {
		$('#WycofanyOddajDoKlienta').show();
		$('#submit').hide();	
	}
	if ((in_array(NS,['9'])) && (in_array(NK,['2','3','6'])) && (in_array(NPK,['0','2','3','4','5','7','9'])) && (in_array(TNS,['8']))) {
		$('#submit').show();
	}
	if ((in_array(NS,['3A'])) && (in_array(NK,['2','3','6'])) && (in_array(NPK,['0','2','3','4','5','7','9'])) && (in_array(TNS,['3']))) {
		$('#OddajSprzetDoKlienta').show();
		$('#submit').hide();	
	}
	if ((in_array(NS,['3B','7'])) && (in_array(NK,['2','3','6'])) && (in_array(NPK,['0','2','3','4','5','7','9'])) && (in_array(TNS,['-1']))) {
		$('#NaprawaWeWlasnymZakresie').show();
		$('#submit').show();	
	}
	if ((in_array(NS,['3B'])) && (in_array(NK,['2','3','6'])) && (in_array(NPK,['0','2','3','4','5','7','9'])) && (in_array(TNS,['0']))) {
		$('#NaprawaWeWlasnymZakresie').hide();
		$('#submit').show();	
	}
	if ((in_array(NS,['3','3B','6','7','9'])) && (in_array(NK,['2','3','6'])) && (in_array(NPK,['0','2','3','4','5','7','9']))) {
		$('#WymianaPodzespolu').show();
	} else {
		$('#WymianaPodzespolu').hide();
	}
	if ((in_array(NS,['3','3B','3A'])) && (in_array(NK,['2','3','6'])) && (in_array(NPK,['0','2','3','4','5','7','9']))) {
		$('#PrzyjmijUszkodzonySprzet').show();
			
		if (NS=='3') {
			if (document.getElementById('p_PrzyjmijUszkodzonySprzet')) document.getElementById('p_PrzyjmijUszkodzonySprzet').style.display='none';
			if (document.getElementById('PokazSprzetZListy')) document.getElementById('PokazSprzetZListy').style.display='none';
			
		} else {
			if (document.getElementById('p_PrzyjmijUszkodzonySprzet')) document.getElementById('p_PrzyjmijUszkodzonySprzet').style.display='';
			if (document.getElementById('PokazSprzetZListy')) document.getElementById('PokazSprzetZListy').style.display='';
		}	
	}
	if ((in_array(NS,['3A'])) && (in_array(NK,['2','6'])) && (in_array(NPK,['0']))) {
		$('#AwariaWAN').show();
	}
	if ((in_array(NS,['3','3B','3A','4','5','7'])) && (in_array(NK,['2','3','6'])) && (in_array(NPK,['0','2','3','4','5','7','9'])) && (PSS!='-1') && (document.getElementById('tnaprawaid').value>0)) {
		if (document.getElementById('PrzekazSprzetSerwisowy_value')) $('#PobierzSprzetSerwisowy').show();
		$('#submit').show();	
	}
	if ((in_array(NS,['3','3B','3A','4','5','7'])) && (in_array(NK,['2','3','6'])) && (in_array(NPK,['0','2','3','4','5','7','9'])) && (PSS=='-1')) {
		if (document.getElementById('PokazPrzekazanieSS')) {
			if (document.getElementById('PokazPrzekazanieSS').value!=0) {
				$('#PrzekazSprzetSerwisowy').show();
			} else {
				$('#PrzekazSprzetSerwisowy').hide();
			}
		} else {
			$('#PrzekazSprzetSerwisowy').show();
		}		
		$('#submit').show();
	}
	if ((in_array(NS,['9'])) && (in_array(NK,['2','3','6'])) && (in_array(NPK,['0','2','3','4','5','7','9'])) && (PSS=='-1')) {
		$('#ZwrocSprzetSerwisowy').hide();		
		if (hidesubmit != 1) $('#submit').show();	
	}
	if ((in_array(NS,['9'])) && (in_array(NK,['3'])) && (in_array(NPK,['0','2','3','4','5','7','9'])) && (PSS!='-1')) {
		if (document.getElementById('SS_Status').value!='0') $('#ZwrocSprzetSerwisowy').show();
		
		if (in_array(TNS,['5','8'])) {
			if (document.getElementById('SS_Status').value=='0') {
				$('#submit').show();
			} else {
				$('#submit').hide();
			}
		} else {
			$('#submit').hide();
		}
	}
	if ((in_array(NS,['9'])) && (in_array(NK,['2','6'])) && (in_array(NPK,['0','2','3','4','5','7','9'])) && (PSS!='-1')) {
		if (document.getElementById('SS_Status').value!='0') $('#ZwrocSprzetSerwisowy').show();
		$('#submit').show();	
	}
	if ((in_array(NS,['6'])) && (in_array(NK,['2','3','6'])) && (in_array(NPK,['0','2','3','4','5','7','9'])) && (PSS!='-1')) {
		$('#ZwrocSprzetSerwisowy').show();
		$('#submit').show();	
	}
	if ((in_array(NS,['3B','3A','4','5','7'])) && (in_array(NK,['2','3','6'])) && (in_array(NPK,['0','2','3','4','5','7','9'])) && (TNS=='') && (document.getElementById('PrzekazSprzetSerwisowy_value').value>0)) {
		$('#ZwrocSprzetSerwisowy').show();
		$('#submit').show();	
	}	
	if ((in_array(NS,['9'])) && (in_array(NK,['2','6']))) {
		if (document.getElementById('block_rozliczone1')) $('#block_rozliczone1').hide();
		if (document.getElementById('block_rozliczone2')) $('#block_rozliczone2').hide(); 
		if (document.getElementById('block_rozliczone3')) $('#block_rozliczone3').hide(); 
		if (document.getElementById('bottombuttons')) $('#bottombuttons').show();
	}
	if ((!in_array(NS,['9'])) && (in_array(NK,['2','6']))) {
		if (document.getElementById('block_rozliczone1')) $('#block_rozliczone1').show();
		if (document.getElementById('block_rozliczone2')) $('#block_rozliczone2').show(); 
		if (document.getElementById('block_rozliczone3')) $('#block_rozliczone3').show(); 
		var cr = document.getElementById('rozwiazany').value;
		if (cr == '1') {
			if (document.getElementById('bottombuttons')) $('#bottombuttons').show();
		} else {
			var rrr = 'czy_rozwiazane_'+document.getElementById('hdnr').value+'';
			
			if ((readCookie(rrr)=='1') || (readCookie(rrr)=='0')) {
				if (document.getElementById('bottombuttons')) $('#bottombuttons').show();
			} else {
				if (document.getElementById('bottombuttons')) $('#bottombuttons').hide();
			}
		}		
	}
	if ((!in_array(NS,['9'])) && (!in_array(NK,['2','6']))) {
		if (document.getElementById('bottombuttons')) $('#bottombuttons').show();
	}
	if ((in_array(NS,['9'])) && (!in_array(NK,['2','6']))) {
		if (document.getElementById('bottombuttons')) $('#bottombuttons').show();
	}
	if (in_array(NS,['9'])) {
		$('#show_ww').hide();
		if (document.getElementById('wymaga_wyjazdu_ustawione')) $('#wymaga_wyjazdu_ustawione').show();
		if (document.getElementById('wymaga_wyjazdu')) document.getElementById('wymaga_wyjazdu').checked=false;
	} else {
		if (document.getElementById('wymaga_wyjazdu_ustawione')) $('#wymaga_wyjazdu_ustawione').show();
	}

}
function PokazUkryjDaneODiagnozie(bool) {
	document.getElementById('TRDiagnoza_uwagi').style.display='none';
	if (bool) {
		document.getElementById('TRDiagnoza_uwagi').style.display='';
	}
}
</script>
<?php } ?>
<?php $pw = array($_f."/hd_p_zgloszenia.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
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
function eraseCookie(name) { createCookie(name,"",-1); }
function Refresh_Moje() {
	if (readCookie('hd_p_zgloszenia_moje')=='TAK') {
		$(document).ready(function() {
			$("#liczniki_moje").load('hd_p_zgloszenia_live_view_m.php?f=<?php echo $es_filia; ?>&range=M&moj_nr=<?php echo $es_nr; ?>&randval='+ Math.random());
			$('#count_notatki_moje').load('hd_count_notes.php?user_id=<?php echo $es_nr; ?>&randval='+ Math.random()).show();
			$('#notatki').load('hd_refresh_notes.php?userid=<?php echo $es_nr; ?>&randval='+ Math.random());	
		});
	}
}
$(document).ready(function () {
  $('#ilosc_godzin_count').click(
    function() {
		$('#ilosc_godzin').load('hd_p_zgloszenia_count_hours.php?randval='+ Math.random()).show();
		return false;
    }
  ); 
});
if ((readCookie('hd_p_zgloszenia_div_nr')!='') && (readCookie('hd_p_zgloszenia_div_nr')!='$sciezka_do_cookie')) {
	$(document).ready(function() {
		$('#ZawartoscDIV').load(readCookie('hd_p_zgloszenia_div_nr'));
	});		
}	
	
function Refresh_Wszystkie() {
	$(document).ready(function() {
		$("#liczniki_wszyscy").load('hd_p_zgloszenia_live_view_w.php?f=<?php echo $es_filia; ?>&range=W&randval='+ Math.random());
	});
}
if (readCookie('hd_p_zgloszenia_moje')=='TAK') {
	$(document).ready(function() {
		$("#liczniki_moje").load('hd_p_zgloszenia_live_view_m.php?f=<?php echo $es_filia; ?>&range=M&moj_nr=<?php echo $es_nr; ?>&randval='+ Math.random());
	});
}
if (readCookie('hd_p_zgloszenia_wszystkie')=='TAK') {
	$(document).ready(function() {
		$("#liczniki_wszyscy").load('hd_p_zgloszenia_live_view_w.php?f=<?php echo $es_filia; ?>&range=W&randval='+ Math.random());		
	});
}
var timetowait = (1000*60*<?php echo $noofminutes; ?>);
$(document).ready(function() {
	if (readCookie('hd_p_zgloszenia_moje')=='TAK') {
		var refreshId_co_n_minut_mz = setInterval(function() { 	
			$('#licznik_refresh').show();
			$('#licznik_refresh').load('hd_uaktualnij_liczniki.php?f=<?php echo $es_filia; ?>&range=MW&moj_nr=<?php echo $es_nr; ?>&cu=<?php echo urlencode($currentuser); ?>&randval='+Math.random()+'&refresh_parent=0&todiv=1&sourcepage=hd');
			this.location.reload(true);
		}, timetowait);
	}
	
});
function MojeZgloszeniaShow() {	document.getElementById('liczniki_moje').style.display=''; }
function MojeZgloszeniaHide() {	document.getElementById('liczniki_moje').style.display='none'; }
function WszystkieZgloszeniaShow() {
//	$("#liczniki_wszyscy").load('hd_p_zgloszenia_live_view_w.php?f=<?php echo $es_filia; ?>&range=W&randval='+ Math.random());
}
function WszystkieZgloszeniaShow1() {
	$("#liczniki_wszyscy").load('hd_p_zgloszenia_live_view_w.php?f=<?php echo $es_filia; ?>&range=W&randval='+ Math.random());
}
function PokazNotatki(what) {
	var state = '';
	if (what==true) state = '';
	if (what==false) state = 'none';
	for (i=0; i<30; i++) {
		if (document.getElementById('tr_notatki_'+i+'')) {
			document.getElementById('tr_notatki_'+i+'').style.display=state;
		} else break;
	}
	document.getElementById('notatki_dane').style.display=state;
	document.getElementById('notatki_dane1').style.display=state;
}
<?php if ((($kierownik_nr==$es_nr) || ($is_dyrektor==1)) && ($funkcja_kontroli_pracownikow==1)) { ?>
function PokazPracownikow(what) {
	var state = '';
	if (what==true) state = '';
	if (what==false) state = 'none';
	
	for (i=0; i<30; i++) {
		if (document.getElementById('tr_pracownik_'+i+'')) {
			document.getElementById('tr_pracownik_'+i+'').style.display=state;
		} else break;
	}
	document.getElementById('tr_pracownicy').style.display=state;
}
<?php } ?>
function WszystkieZgloszeniaHide() {
	$("#liczniki_wszyscy").load('hd_p_zgloszenia_live_view_w.php?f=hide&range=W&randval='+ Math.random());
}
function ClearCookie (name, value) {  
	document.cookie = name + "=" + escape (value) + ";expires=Thu, 01-Jan-1970 00:00:01 GMT";
}
function CountCheckboxesChecked(){
    var elLength = document.obsluga.elements.length;
	var count_checked = 0;
    for (i=0; i<elLength; i++)
    {
        var type = obsluga.elements[i].type;
        if (type=="checkbox" && obsluga.elements[i].checked){
            count_checked = count_checked + 1;
        }
    }
	return count_checked;
}
function SetCookie (name, value) {  
	var argv = SetCookie.arguments;  
	var argc = SetCookie.arguments.length;  
	var expires = (argc > 2) ? argv[2] : null;  
	var path = (argc > 3) ? argv[3] : null;  
	var domain = (argc > 4) ? argv[4] : null;  
	var secure = (argc > 5) ? argv[5] : false;  
	document.cookie = name + "=" + escape (value) + 
	((expires == null) ? "" : ("; expires=" + expires.toGMTString())) + 
	((path == null) ? "" : ("; path=" + path)) +  
	((domain == null) ? "" : ("; domain=" + domain)) +    
	((secure == true) ? "; secure" : "");
}
function PokazNrZgloszen() {

	SetCookie('byly_zmiany', '0');
	var elLength = document.obsluga.elements.length;
	var count_checked = 0;
	var params = "nr=";
	var params2 = "nr=";
	var count_2i6 = 0;
	var save1 =	0;
	
    for (i=0; i<elLength; i++)
    {
        var type = document.obsluga.elements[i].type;	
        if (type=="checkbox" && document.obsluga.elements[i].checked && (document.obsluga.elements[i].name.substring(0,7)=='markzgl')) {
			
			if ((document.getElementById('K'+document.obsluga.elements[i].value+'').value=='2') || (document.getElementById('K'+document.obsluga.elements[i].value+'').value=='6')) {
				count_2i6 = count_2i6 + 1;
				save1 = params + document.obsluga.elements[i].value;
			} else {
				count_checked = count_checked + 1;
				params += document.obsluga.elements[i].value + ",";
			}
        }
     
	}
	
	//alert('poprawnych:'+count_checked+' , wykluczonych: '+count_2i6+'');
	
	if (count_checked>0) {
		document.getElementById('IloscZaznaczonych').innerText=count_checked;
	}
	//
	var dl = params.length;
	//var dl2 = params2.length;
	
	dl--;
	
	if (dl==2) {
		if (count_2i6==0) {
			alert('Nie zaznaczyłeś żadnego zgłoszenia');
			return false;
		} 
		if (count_2i6==1) {
			params = save1;
			dl = save1.length;
			//dl--;
		}
		
	} 
	
	//alert(params);
	ClearCookie('saved_seryjna_obsluga_zgloszen', '1');
	var okno_max = 0;

	//alert(lokacja);
	var count_checked_suma = count_2i6 + count_checked;
		
	if (count_2i6>0) {
		if (count_checked>0) {
			if (confirm(''+count_2i6+' z '+count_checked_suma+' zaznaczonych zgłoszeń to zgłoszenia awarii / awarii krytycznej. Nie mogą one być obsługiwane seryjnie. \n\r\n\rOK - przerwanie obsługi seryjnej\n\rCancel (ESC) - obsługa seryjna pozostałych '+count_checked+' zgłoszeń ')) {
				return true;
			} else {

				if (dl!=2) { 
					var params1 = params.substring(0,dl); 
					//var params1 = '';
					if (count_checked>1) {
						var lokacja1 = "hd_o_zgloszenia_s.php?";
					}
					
					if (count_checked==1) {
						var lokacja2 = "hd_o_zgloszenia.php?action=obsluga&";
					}
					
					if (count_checked==1) {
						var lokacja_1 = params1 + "&cnt=" + count_checked + "&clear_cookie=1";
						lokacja = lokacja2 + lokacja_1;
						if (count_checked>1) { okno_max = 1; } else { okno_max = 0;	}
					} else {
						var lokacja_1 = params1 + "&cnt=" + count_checked + "&clear_cookie=1";
						lokacja = lokacja1 + lokacja_1;
						if (count_checked>1) { okno_max = 1; } else { okno_max = 0;	}
						
					}
					
				}

				if (okno_max==0) {
					var x = 800;
					var y = 600;
				} else {
					var x=window.screen.availWidth-5;
					var y=window.screen.availHeight-30; 	
				}
				
				if (window.screen) {
					var ah = screen.availHeight - 30;
					var aw = screen.availWidth - 10;
					
					var xx=(aw-x) / 2;
					xx=Math.round(xx);
					var yy=(ah-y) / 2;
					yy=Math.round(yy);
				}
				
				var opcje="scrollbars=yes, width="+x+", innerWidth="+x+", height="+y+", innerHeight="+y+", top="+yy+", screenX="+xx+", screenY="+yy+", left="+xx;
				
					
				//alert('===>'+lokacja);
				NewWindowOpen = window.open(lokacja, "eSerwisOSZ", opcje);
				NewWindowOpen.focus;
			}
		} else {
			if (count_2i6==1) {
			
				if (dl!=2) { 
					var params1 = params.substring(0,dl); 
					if (count_checked>1) var lokacja1 = "hd_o_zgloszenia_s.php?";		
					if (count_checked==1)var lokacja2 = "hd_o_zgloszenia.php?action=obsluga&";
					
					if (count_checked==1) {
						var lokacja_1 = params1 + "&cnt=" + count_checked + "&clear_cookie=1";
						lokacja = lokacja2 + lokacja_1;
						okno_max = 0;
					} else {
						if (count_2i6==1) {
							var lokacja_1 = params1 + "&cnt=" + count_checked + "&clear_cookie=1";
							lokacja = lokacja2 + lokacja_1;
							if (count_checked>1) { okno_max = 1; } else { okno_max = 0;	}
						} else {
							var lokacja_1 = params1 + "&cnt=" + count_checked + "&clear_cookie=1";
							lokacja = lokacja1 + lokacja_1;
							if (count_checked>1) { okno_max = 1; } else { okno_max = 0;	}
						}
					}
					
				}
				//alert(lokacja);
				if (okno_max==0) {
					var x = 800;
					var y = 600;
				} else {
					var x=window.screen.availWidth-5;
					var y=window.screen.availHeight-30; 	
				}
				
				if (window.screen) {
					var ah = screen.availHeight - 30;
					var aw = screen.availWidth - 10;
					
					var xx=(aw-x) / 2;
					xx=Math.round(xx);
					var yy=(ah-y) / 2;
					yy=Math.round(yy);
				}
				
				var opcje="scrollbars=yes, width="+x+", innerWidth="+x+", height="+y+", innerHeight="+y+", top="+yy+", screenX="+xx+", screenY="+yy+", left="+xx;

				
				NewWindowOpen = window.open(lokacja, "eSerwisOSZ", opcje);
				NewWindowOpen.focus;
			} else {
				if (count_2i6<=4) alert(''+count_2i6+' zaznaczone zgłoszenia to zgłoszenia awarii / awarii krytycznej. Zgłoszenia te nie mogą być obsługiwane seryjnie');
				if (count_2i6>4) alert(''+count_2i6+' zaznaczonych zgłoszeń to zgłoszenia awarii / awarii krytycznej. Zgłoszenia te nie mogą być obsługiwane seryjnie');
				return true;
			}
		}
	} else { 
		if (dl!=2) { 
			var params1 = params.substring(0,dl); 
			//var params1 = '';
			if (count_checked>1) {
				var lokacja1 = "hd_o_zgloszenia_s.php?";
			}
					
			if (count_checked==1) {
				var lokacja2 = "hd_o_zgloszenia.php?action=obsluga&";
			}
					
			if (count_checked==1) {
				var lokacja_1 = params1 + "&cnt=" + count_checked + "&clear_cookie=1";
				lokacja = lokacja2 + lokacja_1;
				if (count_checked>1) { okno_max = 1; } else { okno_max = 0;	}
			} else {
				var lokacja_1 = params1 + "&cnt=" + count_checked + "&clear_cookie=1";
				lokacja = lokacja1 + lokacja_1;
				if (count_checked>1) { okno_max = 1; } else { okno_max = 0;	}
			}
		}
		
		if (okno_max==0) {
			var x = 800;
			var y = 600;
		} else {
			var x=window.screen.availWidth-5;
			var y=window.screen.availHeight-30; 	
		}
				
		if (window.screen) {
			var ah = screen.availHeight - 30;
			var aw = screen.availWidth - 10;
			
			var xx=(aw-x) / 2;
			xx=Math.round(xx);
			var yy=(ah-y) / 2;
			yy=Math.round(yy);
		}

		var opcje="scrollbars=yes, width="+x+", innerWidth="+x+", height="+y+", innerHeight="+y+", top="+yy+", screenX="+xx+", screenY="+yy+", left="+xx;

		NewWindowOpen = window.open(lokacja, "eSerwisOSZ", opcje);
		NewWindowOpen.focus;
	}
	//return false;
}
function DrukujPotwierdzeniaSeryjnie() {
	SetCookie('byly_zmiany', '0');
	var elLength = document.obsluga.elements.length;
	var count_checked = 0;
	var params = "nr=";
	var count_radio = 0;
	var params2 = "nr=";
	
    for (i=0; i<elLength; i++)
    {
        var type = document.obsluga.elements[i].type;
		
        if (type=="checkbox" && document.obsluga.elements[i].checked && (document.obsluga.elements[i].name.substring(0,7)=='markzgl')) {
			count_checked = count_checked + 1;
			params += document.obsluga.elements[i].value + ",";
        }
        if (type=="radio" && document.obsluga.elements[i].checked && (document.obsluga.elements[i].name.substring(0,7)=='markzgl')) {
			count_radio = count_radio + 1;
			params2 += document.obsluga.elements[i].value + ",";
        }
		
	}
	
	if (count_checked>0) {
		document.getElementById('IloscZaznaczonych').innerText=count_checked;
	} else if (count_radio>0) {
		document.getElementById('IloscZaznaczonych').innerText=count_radio;
	}
	
	var dl = params.length;
	var dl2 = params2.length;
	
	dl--;
	dl2--;
	
	if ((dl==2) && (dl2==2)) {
		alert('Nie zaznaczyłeś żadnego zgłoszenia');
		return false;
	}
	var okno_max = 0;
	
	if (dl!=2) { 
		var params1 = params.substring(0,dl); 
		var lokacja1 = "hd_potwierdzenie_many.php?";		
		var lokacja2 = "hd_potwierdzenie_many?action=obsluga&";
		
		if (count_checked==1) {
			var lokacja_1 = params1 + "&cnt=" + count_checked + "&clear_cookie=1";
			lokacja = lokacja2 + lokacja_1;
			okno_max = 0;
		} else {
			var lokacja_1 = params1 + "&cnt=" + count_checked + "&clear_cookie=1";
			lokacja = lokacja1 + lokacja_1;
			okno_max = 1;
		}
		
	} else if (dl2!=2) { 
			var params1 = params2.substring(0,dl2); 
			var lokacja = "hd_potwierdzenie_many?action=obsluga&" + params1 + "&cnt=" + count_radio + "&clear_cookie=1";
			okno_max = 0;
			}
	if (okno_max==0) {
		var x = 800;
		var y = 600;
	} else {
		var x=window.screen.availWidth-5;
		var y=window.screen.availHeight-30; 	
	}
	
	if (window.screen) {
		var ah = screen.availHeight - 30;
		var aw = screen.availWidth - 10;
		
		var xx=(aw-x) / 2;
		xx=Math.round(xx);
		var yy=(ah-y) / 2;
		yy=Math.round(yy);
	}
	
	var opcje="scrollbars=yes, width="+x+", innerWidth="+x+", height="+y+", innerHeight="+y+", top="+yy+", screenX="+xx+", screenY="+yy+", left="+xx;
	
	NewWindowOpen = window.open(lokacja, "eSerwisOSZ", opcje);
	NewWindowOpen.focus;
	//return false;
}
function UtworzHarmonogramWyjazdu() {
	SetCookie('byly_zmiany', '0');
	var elLength = document.obsluga.elements.length;
	var count_checked = 0;
	var params = "nr=";
	var count_radio = 0;
	var params2 = "nr=";
	
    for (i=0; i<elLength; i++)
    {
        var type = document.obsluga.elements[i].type;
		
        if (type=="checkbox" && document.obsluga.elements[i].checked && (document.obsluga.elements[i].name.substring(0,7)=='markzgl')) {
			count_checked = count_checked + 1;
			params += document.obsluga.elements[i].value + ",";
        }
        if (type=="radio" && document.obsluga.elements[i].checked && (document.obsluga.elements[i].name.substring(0,7)=='markzgl')) {
			count_radio = count_radio + 1;
			params2 += document.obsluga.elements[i].value + ",";
        }	
	}
	if (count_checked>0) {
		document.getElementById('IloscZaznaczonych').innerText=count_checked;
	} else if (count_radio>0) {
		document.getElementById('IloscZaznaczonych').innerText=count_radio;
	}
	var dl = params.length;
	var dl2 = params2.length;
	dl--;
	dl2--;
	if ((dl==2) && (dl2==2)) {
		alert('Nie zaznaczyłeś żadnego zgłoszenia');
		return false;
	}
	ClearCookie('saved_seryjna_obsluga_zgloszen', '1');
	var okno_max = 0;
	
	if (dl!=2) { 
		var params1 = params.substring(0,dl); 
		var lokacja1 = "hd_g_harmonogram_wyjazdu.php?";		
		var lokacja2 = "hd_g_harmonogram_wyjazdu.php?";
		
		if (count_checked==1) {
			var lokacja_1 = params1 + "&cnt=" + count_checked + "&clear_cookie=1";
			lokacja = lokacja2 + lokacja_1;
			okno_max = 0;
		} else {
			var lokacja_1 = params1 + "&cnt=" + count_checked + "&clear_cookie=1";
			lokacja = lokacja1 + lokacja_1;
			okno_max = 1;
		}
		
	} else if (dl2!=2) { 
			var params1 = params2.substring(0,dl2); 
			var lokacja = "hd_g_harmonogram_wyjazdu.php?" + params1 + "&cnt=" + count_radio + "&clear_cookie=1";
			okno_max = 0;
			}
	if (okno_max==0) {
		var x = 800;
		var y = 600;
	} else {
		var x=window.screen.availWidth-5;
		var y=window.screen.availHeight-30; 	
	}
	
	if (window.screen) {
		var ah = screen.availHeight - 30;
		var aw = screen.availWidth - 10;
		
		var xx=(aw-x) / 2;
		xx=Math.round(xx);
		var yy=(ah-y) / 2;
		yy=Math.round(yy);
	}
	var opcje="scrollbars=yes, width="+x+", innerWidth="+x+", height="+y+", innerHeight="+y+", top="+yy+", screenX="+xx+", screenY="+yy+", left="+xx;
	NewWindowOpen = window.open(lokacja, "eSerwisGHW", opcje);
	NewWindowOpen.focus;
	//return false;
}
function MarkCheckboxes1(x,b,t,j){
	if (typeof x!='object')x=document.forms[x];
	else x=x.form
	for(j=0;t=x.getElementsByTagName("input")[j++];)
		if( /chec/i.test( t.type ) )t.checked=b!=-1?b:!t.checked;
}
function UpdateIloscZaznaczen() {
	var elLength = document.obsluga.elements.length;
	
	var count_checked = 0;
	var count_radio = 0;
	
    for (i=0; i<elLength; i++)
    {
        var type = document.obsluga.elements[i].type;
		
        if (type=="checkbox" && document.obsluga.elements[i].checked && (document.obsluga.elements[i].name.substring(0,7)=='markzgl')) count_checked = count_checked + 1;
        if (type=="radio" && document.obsluga.elements[i].checked && (document.obsluga.elements[i].name.substring(0,7)=='markzgl')) count_radio = count_radio + 1;
		
	}
	
	if (count_checked>=0) {
		document.getElementById('IloscZaznaczonych').innerText=count_checked;	
		document.getElementById('IloscZaznaczonych').textContent=count_checked;
		if (count_checked==0) {
			if (document.getElementById('ObslugaZgloszen')) document.getElementById('ObslugaZgloszen').style.display='none';
			if (document.getElementById('NapisPrzed')) document.getElementById('NapisPrzed').style.display='none'; 
			if (document.getElementById('FSPrzypiszDoOsoby'))
document.getElementById('FSPrzypiszDoOsoby').style.display='none';
		} else {			
			if (document.getElementById('ObslugaZgloszen')) document.getElementById('ObslugaZgloszen').style.display='';
			if (document.getElementById('NapisPrzed')) document.getElementById('NapisPrzed').style.display=''; 
			if (document.getElementById('FSPrzypiszDoOsoby'))
document.getElementById('FSPrzypiszDoOsoby').style.display='inline';
		}
	}
	
	if (count_radio>0) {
		document.getElementById('IloscZaznaczonych').innerText=count_radio;
		document.getElementById('IloscZaznaczonych').textContent=count_radio;
		if (count_radio>0) {
			if (document.getElementById('NapisPrzed')) document.getElementById('NapisPrzed').style.display=''; 
			if (document.getElementById('ObslugaZgloszen')) document.getElementById('ObslugaZgloszen').style.display=''; 
			if (document.getElementById('FSPrzypiszDoOsoby'))			
document.getElementById('FSPrzypiszDoOsoby').style.display='inline';
		} else {
			if (document.getElementById('ObslugaZgloszen')) document.getElementById('ObslugaZgloszen').style.display='none';
			if (document.getElementById('NapisPrzed')) document.getElementById('NapisPrzed').style.display='none'; 
			if (document.getElementById('FSPrzypiszDoOsoby'))
document.getElementById('FSPrzypiszDoOsoby').style.display='none';
		}
	}
	
}
function OdznaczRadioButton(obj) {
  for (var i=0; i < obj.length; i++) {
	if (obj[i].type=='radio') obj[i].checked = false;
  }
  UpdateIloscZaznaczen();
}
function OdznaczWszystkieCheckboxy(obj) {
	for(var i=0,l=obj.length; i<l; i++) {	
		if (obj[i].type == 'checkbox') {
			obj[i].checked=false;
		}
	}
	UpdateIloscZaznaczen();
}
function MarkCheckboxes(akcja) {
	for(var i=0,l=document.obsluga.elements.length; i<l; i++) {
		
		if (document.obsluga.elements[i].type == 'checkbox') {
			if (akcja=='odwroc') {
document.obsluga.elements[i].checked=document.obsluga.elements[i].checked?false:true;
			} else if (akcja=='zaznacz') {
document.obsluga.elements[i].checked=true;
			} else if (akcja=='odznacz') {
document.obsluga.elements[i].checked=false;
			}
		}	
	}
	UpdateIloscZaznaczen();
	OdznaczRadioButton(document.obsluga.markzgl);
}
function SelectTRById(v) {
	UpdateIloscZaznaczen();	
}
function OznaczJakoWyjazdowe(x) {
	SetCookie('byly_zmiany', '0');
	var elLength = document.obsluga.elements.length;
	var count_checked = 0;
	var params = "nr=";
	var count_radio = 0;
	var params2 = "nr=";
	
    for (i=0; i<elLength; i++)
    {
        var type = document.obsluga.elements[i].type;
        if (type=="checkbox" && document.obsluga.elements[i].checked && (document.obsluga.elements[i].name.substring(0,7)=='markzgl')) {
			count_checked = count_checked + 1;
			params += document.obsluga.elements[i].value + ",";
        }
        if (type=="radio" && document.obsluga.elements[i].checked && (document.obsluga.elements[i].name.substring(0,7)=='markzgl')) {
			count_radio = count_radio + 1;
			params2 += document.obsluga.elements[i].value + ",";
        }	
	}
	if (count_checked>0) {
		document.getElementById('IloscZaznaczonych').innerText=count_checked;
	} else if (count_radio>0) {
		document.getElementById('IloscZaznaczonych').innerText=count_radio;
	}	
	var dl = params.length;
	var dl2 = params2.length;
	dl--;
	dl2--;
	
	if ((dl==2) && (dl2==2)) {
		alert('Nie zaznaczyłeś żadnego zgłoszenia');
		return false;
	}
	//ClearCookie('saved_seryjna_obsluga_zgloszen', '1');
	var okno_max = 0;
	
	if (dl!=2) { 
		var params1 = params.substring(0,dl); 
		var lokacja1 = "hd_o_zgloszenia_ww_s.php?set="+x+"&";		
		var lokacja2 = "hd_o_zgloszenia_ww_s.php?set="+x+"&";
		
		if (count_checked==1) {
			var lokacja_1 = params1 + "&cnt=" + count_checked + "&clear_cookie=1";
			lokacja = lokacja2 + lokacja_1;
			okno_max = 0;
		} else {
			var lokacja_1 = params1 + "&cnt=" + count_checked + "&clear_cookie=1";
			lokacja = lokacja1 + lokacja_1;
			okno_max = 0;
		}
		
	} else if (dl2!=2) { 
			var params1 = params2.substring(0,dl2); 
			var lokacja = "hd_o_zgloszenia_ww_s.php?set="+x+"&" + params1 + "&cnt=" + count_radio + "&clear_cookie=1";
			okno_max = 0;
			}
	if (okno_max==0) {
		var x = 500;
		var y = 50;
	} else {
		var x=window.screen.availWidth-5;
		var y=window.screen.availHeight-30; 	
	}
	
	if (window.screen) {
		var ah = screen.availHeight - 30;
		var aw = screen.availWidth - 10;
		
		var xx=(aw-x) / 2;
		xx=Math.round(xx);
		var yy=(ah-y) / 2;
		yy=Math.round(yy);
	}
	
	var opcje="scrollbars=yes, width="+x+", innerWidth="+x+", height="+y+", innerHeight="+y+", top="+yy+", screenX="+xx+", screenY="+yy+", left="+xx;
	
	NewWindowOpen = window.open(lokacja, "eSerwisOJW", opcje);
	NewWindowOpen.focus;
	//return false;
}
function OznaczJakoSprawdzone(x) {
	SetCookie('byly_zmiany', '0');
	var elLength = document.obsluga.elements.length;
	var count_checked = 0;
	var params = "nr=";
	var count_radio = 0;
	var params2 = "nr=";
	
    for (i=0; i<elLength; i++)
    {
        var type = document.obsluga.elements[i].type;
        if (type=="checkbox" && document.obsluga.elements[i].checked && (document.obsluga.elements[i].name.substring(0,7)=='markzgl')) {
			count_checked = count_checked + 1;
			params += document.obsluga.elements[i].value + ",";
        }
        if (type=="radio" && document.obsluga.elements[i].checked && (document.obsluga.elements[i].name.substring(0,7)=='markzgl')) {
			count_radio = count_radio + 1;
			params2 += document.obsluga.elements[i].value + ",";
        }	
	}
	if (count_checked>0) {
		document.getElementById('IloscZaznaczonych').innerText=count_checked;
	} else if (count_radio>0) {
		document.getElementById('IloscZaznaczonych').innerText=count_radio;
	}	
	var dl = params.length;
	var dl2 = params2.length;
	dl--;
	dl2--;
	
	if ((dl==2) && (dl2==2)) {
		alert('Nie zaznaczyłeś żadnego zgłoszenia');
		return false;
	}
	//ClearCookie('saved_seryjna_obsluga_zgloszen', '1');
	var okno_max = 0;
	
	if (dl!=2) { 
		var params1 = params.substring(0,dl); 
		var lokacja1 = "hd_o_zgloszenia_potw_spr_s.php?set="+x+"&";		
		var lokacja2 = "hd_o_zgloszenia_potw_spr_s.php?set="+x+"&";
		
		if (count_checked==1) {
			var lokacja_1 = params1 + "&cnt=" + count_checked + "&clear_cookie=1";
			lokacja = lokacja2 + lokacja_1;
			okno_max = 0;
		} else {
			var lokacja_1 = params1 + "&cnt=" + count_checked + "&clear_cookie=1";
			lokacja = lokacja1 + lokacja_1;
			okno_max = 0;
		}
		
	} else if (dl2!=2) { 
			var params1 = params2.substring(0,dl2); 
			var lokacja = "hd_o_zgloszenia_potw_spr_s.php?set="+x+"&" + params1 + "&cnt=" + count_radio + "&clear_cookie=1";
			okno_max = 0;
			}
	if (okno_max==0) {
		var x = 500;
		var y = 50;
	} else {
		var x=window.screen.availWidth-5;
		var y=window.screen.availHeight-30; 	
	}
	
	if (window.screen) {
		var ah = screen.availHeight - 30;
		var aw = screen.availWidth - 10;
		
		var xx=(aw-x) / 2;
		xx=Math.round(xx);
		var yy=(ah-y) / 2;
		yy=Math.round(yy);
	}
	
	var opcje="scrollbars=yes, width="+x+", innerWidth="+x+", height="+y+", innerHeight="+y+", top="+yy+", screenX="+xx+", screenY="+yy+", left="+xx;
	
	NewWindowOpen = window.open(lokacja, "eSerwisOJS", opcje);
	NewWindowOpen.focus;
	//return false;
}
</script>
<?php } ?>
<?php $pw = array($_f."/hd_p_zgloszenia.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
<?php if (($ClueTipOn==1) && ($wiecej_informacji_w_Helpdesk)) { ?>
<script type="text/javascript" src="js/jquery/jquery.cluetip_min.js"></script>
<?php } ?>
<script type="text/javascript" src="js/recoverscroll_min.js"></script>
<?php } ?>
<?php $pw = array($_f."/p_towary_dostepne.php",$_f."/p_naprawy_wszystko.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
<script type="text/javascript" src="js/recoverscroll_min.js"></script>
<script>
function PrepareTowar(v) {
	var tid = document.getElementById('pozid'+v).value;
	var tf = document.getElementById('pozf'+v).value;
	newWindow_r(700,595,'z_towary_obrot.php?id='+tid+'&f='+tf+'&obzp=1');
}
</script>
<?php } ?>
<?php $pw = array($_f."/e_magazyn_pobierz.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
<script type="text/javascript" src="js/recoverscroll_min.js"></script>
<script>
function PreparePobierz(v) {
	var tid = document.getElementById('pozid'+v).value;
	var tnazwa = document.getElementById('pozname'+v).value;
	newWindow_r(800,600,'z_magazyn_pobierz.php?id='+tid+'&part='+tnazwa+'&a=1');
}
</script>
<?php } ?>
<?php $pw = array($_f."/z_magazyn_zwroty.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
<script type="text/javascript" src="js/recoverscroll_min.js"></script>
<script>
function PrepareZMagazynZwroty(v) {
	var tid = document.getElementById('pozid'+v).value;
	var tnazwa = document.getElementById('pozname'+v).value;
	newWindow_r(700,595,'z_magazyn_zwrot.php?id='+tid+'&part='+tnazwa+'&a=1');
}
</script>
<?php } ?>
<?php $pw = array($_f."/main.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
<script>
function PreparePMagazynDoRezerwacji(v) { var tid = document.getElementById('pozid'+v).value; newWindow(500,115,'z_magazyn_zarezerwuj.php?select_id='+tid+''); }
function PrepareZMagazynUkryj(v) { var tid = document.getElementById('pozid'+v).value; newWindow(500,115,'e_magazyn_ukryj.php?select_id='+tid+'');}
function PreparePMagazynAktualne(v) {var tid = document.getElementById('pozid'+v).value;	var tnazwa = document.getElementById('pozname'+v).value;	newWindow_r(700,595,'z_magazyn_pobierz.php?id='+tid+'&part='+tnazwa+'');}
function PreparePMagazynPobrany(v) {var tid = document.getElementById('pozid'+v).value;var tnazwa = document.getElementById('pozname'+v).value;newWindow_r(700,595,'z_magazyn_zwrot.php?id='+tid+'&part='+tnazwa+'');}
function PreparePMagazynRezerwacje(v) {var tid = document.getElementById('pozid'+v).value;var tnazwa = document.getElementById('pozname'+v).value;newWindow(700,595,'z_magazyn_pobierz.php?id='+tid+'&part='+tnazwa+'');}
function PrepareZMagazynUkryty(v) {var tid = document.getElementById('pozid'+v).value;newWindow(500,115,'z_magazyn_odkryj.php?select_id='+tid+'');}
</script>
<?php } ?>
<?php $pw = array($_f."/hd_edycja_trasy_wyjazdowej.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
<script type="text/javascript" src="js/jquery/jquery-1.6.min.js"></script>
<?php } ?>
<?php
$pw = array($_f."/hd_o_zgloszenia.php",$_f."/hd_o_zgloszenia_pdo.php",$_f."/hd_o_zgloszenia_pds.php");
if (array_search($PHP_SELF, $pw)>-1) {
?>
<script type="text/javascript" src="js/cookie_form.js"></script>
<?php } ?>
<?php $pw = array($_f."/hd_o_zgloszenia_s.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
<link type="text/css" rel="stylesheet" media="screen" href="js/jquery/jquery.toChecklist.css" />
<script type="text/javascript" src="js/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="js/jquery/jquery.toChecklist_min.js"></script>
<script type="text/javascript" src="js/cookie_form.js"></script>
<script>
$().ready(function() {
	$("#userlist_s").toChecklist({
		showSelectedItems : true,
		submitDataAsArray : true,
		showCheckboxes:true,
		preferIdOverName : false
	});	
	$("#WieleOsobWybor").hide();
});
function UpdateKM(v,from) {
	if (v.value=='') { 
		//if (from=='button') { alert('Nie wpisałeś km'); v.focus(); }
	} else {
		var ilosc_zgl = document.getElementById('cnt_zgl').value;
		var km_dla_jednego = v.value / ilosc_zgl;
		km_dla_jednego = Math.round(km_dla_jednego);
		
		var km_dla_jednego_pomniejsz = km_dla_jednego - 1;
		
		var reszta_z_dzielenia = v.value-(ilosc_zgl*km_dla_jednego);
		
		for (var g=0; g<ilosc_zgl; g++) {
			id_nr = document.getElementById('nr_zgl-'+g).value;
			document.getElementById('oz_km-'+id_nr).value=km_dla_jednego;
		}
		
		if ((reszta_z_dzielenia)!=0) {
			if (reszta_z_dzielenia<0) { 
if (v.value>ilosc_zgl) alert('Ilość zgłoszeń większa niż ilość kilometrów.\n\rZweryfikuj wyliczone automatycznie odległości');
	var cel = Math.abs(reszta_z_dzielenia);
	for (var g=0; g<cel; g++) {
		id_nr = document.getElementById('nr_zgl-'+g).value;
		document.getElementById('oz_km-'+id_nr).value=km_dla_jednego_pomniejsz;
	}
			} else {
km_dla_jednego+=reszta_z_dzielenia;
document.getElementById('oz_km-'+id_nr).value=km_dla_jednego;
			}
		}
		var sumuj_km = 0;
		for (var g=0; g<ilosc_zgl; g++) {
			id_nr = document.getElementById('nr_zgl-'+g).value;
			sumuj_km+=(document.getElementById('oz_km-'+id_nr).value*1);
		}
		document.getElementById('km_razem').value=sumuj_km;
		if ((reszta_z_dzielenia)>0) alert('Resztę z podziału km uwzględniono w zgłoszeniu nr : '+id_nr);		
	}
}
function UpdateCzas(h,m,from) {
	if ((h.value=='') && (m.value=='') && (from=='button')) { 
		alert('Nie wpisałeś czasu obsługi'); h.focus(); 
	} else {
		var czas_w_minutach = 0;
		czas_w_minutach += (m.value*1)+(h.value*60);
		
		document.getElementById('razem_minut').value=czas_w_minutach;
		
		var ilosc_zgl = document.getElementById('cnt_zgl').value;
		var czas_dla_jednego = czas_w_minutach / ilosc_zgl;
		czas_dla_jednego = Math.round(czas_dla_jednego);
			
		var reszta_z_dzielenia = czas_w_minutach-(ilosc_zgl*czas_dla_jednego);
		
		for (var g=0; g<ilosc_zgl; g++) {
			id_nr = document.getElementById('nr_zgl-'+g).value;
			document.getElementById('oz_czas-'+id_nr).value=czas_dla_jednego;
		}
		
		if ((reszta_z_dzielenia)!=0) { 
			czas_dla_jednego+=reszta_z_dzielenia;
			document.getElementById('oz_czas-'+id_nr).value=czas_dla_jednego;
		}
		var sumuj_czas = 0;
		for (var g=0; g<ilosc_zgl; g++) {
			id_nr = document.getElementById('nr_zgl-'+g).value;
			sumuj_czas+=(document.getElementById('oz_czas-'+id_nr).value*1);
		}
		document.getElementById('czas_razem').value=sumuj_czas;
		if ((reszta_z_dzielenia)!=0) alert('Resztę z podziału czasu uwzględniono w zgłoszeniu nr : '+id_nr);
	}
}

function UpdateCzas2(h,m,from) {
	if ((h.value=='') && (m.value=='') && (from=='button')) { 
		alert('Nie wpisałeś czasu trwania wyjazdu'); h.focus(); 
	} else {
		var czas_w_minutach = 0;
		czas_w_minutach += (m.value*1)+(h.value*60);
		
		document.getElementById('czas_przejazdu_razem').value=czas_w_minutach;
		
		var ilosc_zgl = document.getElementById('cnt_zgl').value;
		var czas_dla_jednego = czas_w_minutach / ilosc_zgl;
		czas_dla_jednego = Math.round(czas_dla_jednego);
			
		var reszta_z_dzielenia = czas_w_minutach-(ilosc_zgl*czas_dla_jednego);
		
		for (var g=0; g<ilosc_zgl; g++) {
			id_nr = document.getElementById('nr_zgl-'+g).value;
			document.getElementById('oz_czas_przejazdu-'+id_nr).value=czas_dla_jednego;
		}
		
		if ((reszta_z_dzielenia)!=0) { 
			czas_dla_jednego+=reszta_z_dzielenia;
			document.getElementById('oz_czas_przejazdu-'+id_nr).value=czas_dla_jednego;
		}
		
		var sumuj_czas = 0;
		for (var g=0; g<ilosc_zgl; g++) {
			id_nr = document.getElementById('nr_zgl-'+g).value;
			sumuj_czas+=(document.getElementById('oz_czas_przejazdu-'+id_nr).value*1);
		}
		document.getElementById('czas_przejazdu_razem').value=sumuj_czas;
		//alert(sumuj_czas);
		if ((reszta_z_dzielenia)!=0) alert('Resztę z podziału czasu poświęconego na wyjazd uwzględniono w zgłoszeniu nr : '+id_nr);
	}
}

function ObslugaPowZWyjazdem(x) {
	var ilosc_zgl = document.getElementById('cnt_zgl').value;
	var id_name = '';
	var id_nr = '';
	document.getElementById('CzasTrwaniaWyjazdu').style.display = 'none';
	
	for (var g=0; g<ilosc_zgl; g++) {
		id_nr = document.getElementById('nr_zgl-'+g).value;
		document.getElementById('addcol-'+id_nr).style.display = 'none';
	}			

	if (x==true) { 
		document.getElementById('ObslugaPowiazanaZWyjazdem').style.display='';
		document.getElementById('CzasTrwaniaWyjazdu').style.display = '';
		
		if (document.getElementById('obsl_powiazana_z_wyjazdem').value=='P') {
			document.getElementById('kolumna_z_km').style.display=''; 
			document.getElementById('kolumna_z_czas_wyjazdu').style.display=''; 
		
			document.getElementById('kolumna_z_km1').style.display='';
			document.getElementById('kolumna_z_km2').style.display='';
			document.getElementById('kolumna_z_km3').style.display='';
			document.getElementById('kolumna_z_km4').style.display=''; 
			
			document.getElementById('UstawKolejnosc').style.display='';
			document.getElementById('ZapiszKolejnosc').style.display='none';	
			document.getElementById('trasa_wyjazdowa').style.display=''; 
			for (var g=0; g<ilosc_zgl; g++) {
				id_nr = document.getElementById('nr_zgl-'+g).value;
				document.getElementById('kolumna_z_km-'+id_nr).style.display='';
				document.getElementById('kolumna_z_czasem_przejazdu-'+id_nr).style.display='';
				document.getElementById('addcol-'+id_nr).style.display = '';
			}
			document.getElementById('ilosc_km').focus();
		}

	} else { 
		document.getElementById('ObslugaPowiazanaZWyjazdem').style.display='none'; 
		
		if (document.getElementById('obsl_powiazana_z_wyjazdem').value=='S') {
			document.getElementById('CzasTrwaniaWyjazdu').style.display = '';
			
			document.getElementById('kolumna_z_km').style.display='none'; 
			document.getElementById('kolumna_z_czas_wyjazdu').style.display=''; 
		
			//document.getElementById('kolumna_z_km1').style.display=''; 
			//document.getElementById('kolumna_z_km2').style.display=''; 
			document.getElementById('kolumna_z_km3').style.display=''; 
			document.getElementById('kolumna_z_km4').style.display=''; 
			
			document.getElementById('UstawKolejnosc').style.display='';
			document.getElementById('ZapiszKolejnosc').style.display='none';	
			document.getElementById('trasa_wyjazdowa').style.display=''; 
			for (var g=0; g<ilosc_zgl; g++) {
				id_nr = document.getElementById('nr_zgl-'+g).value;
				document.getElementById('kolumna_z_km-'+id_nr).style.display='none';
				document.getElementById('kolumna_z_czasem_przejazdu-'+id_nr).style.display='';
				document.getElementById('addcol-'+id_nr).style.display = '';
			}			
			document.getElementById('kolumna_z_km4').style.display='none';
			document.getElementById('kolumna_z_km1').style.display='none';
			
		} 
		if (document.getElementById('obsl_powiazana_z_wyjazdem').value=='P') {
			document.getElementById('kolumna_z_km').style.display='none'; 
			document.getElementById('kolumna_z_czas_wyjazdu').style.display='none'; 
			
			document.getElementById('kolumna_z_km1').style.display='none'; 
			document.getElementById('kolumna_z_km2').style.display='none'; 
			
			document.getElementById('kolumna_z_km3').style.display='none'; 
			document.getElementById('kolumna_z_km4').style.display='none'; 
			
			document.getElementById('UstawKolejnosc').style.display='none';		
			document.getElementById('ZapiszKolejnosc').style.display='none';
			document.getElementById('trasa_wyjazdowa').style.display='none'; 
			for (var g=0; g<ilosc_zgl; g++) {
				id_nr = document.getElementById('nr_zgl-'+g).value;	
				td_km = 'kolumna_z_km-'+id_nr;
				document.getElementById(td_km).style.display='';
				document.getElementById('kolumna_z_czasem_przejazdu-'+id_nr).style.display='none';
				document.getElementById('addcol-'+id_nr).style.display = 'none';
			}
			document.getElementById('kolumna_z_km4').style.display='';
			document.getElementById('kolumna_z_km1').style.display='';
			
		}
		
		if (document.getElementById('obsl_powiazana_z_wyjazdem').value=='') {
			document.getElementById('kolumna_z_km').style.display='none'; 
			document.getElementById('kolumna_z_czas_wyjazdu').style.display='none'; 
			
			document.getElementById('kolumna_z_km1').style.display='none'; 
			document.getElementById('kolumna_z_km2').style.display='none'; 
			
			document.getElementById('kolumna_z_km3').style.display='none'; 
			document.getElementById('kolumna_z_km4').style.display='none'; 
			
			document.getElementById('UstawKolejnosc').style.display='none';		
			document.getElementById('ZapiszKolejnosc').style.display='none';
			document.getElementById('trasa_wyjazdowa').style.display='none'; 
			for (var g=0; g<ilosc_zgl; g++) {
				id_nr = document.getElementById('nr_zgl-'+g).value;	
				td_km = 'kolumna_z_km-'+id_nr;
				document.getElementById(td_km).style.display='none';
				document.getElementById('kolumna_z_czasem_przejazdu-'+id_nr).style.display='none';
				document.getElementById('addcol-'+id_nr).style.display = 'none';
			}
			document.getElementById('kolumna_z_km4').style.display='none';
			document.getElementById('kolumna_z_km1').style.display='none';
			
		}		
		
	}
}
function UpdateDateDlaWszystkich(s) {
var ilosc_zgl = document.getElementById('cnt_zgl').value;
	for (var g=0; g<ilosc_zgl; g++) {
		id_nr = document.getElementById('nr_zgl-'+g).value;
		var il_pozycji = document.getElementById('oz_data-'+id_nr).options.length-1;
		for (var h=0; h<=il_pozycji; h++) {
			if (document.getElementById('oz_data-'+id_nr).options[h].value==s) {		
document.getElementById('oz_data-'+id_nr).options[h].selected=true;
break;
			}
		}
	}
}
function UpdateCzasDlaWszystkich(s) {
	if (s=='') {
		var now = new Date();
		var hour        = now.getHours();
		var minute      = now.getMinutes();
		document.getElementById('oz_godzina_dla_wszystkich').value=hour+":"+minute;
		return true;
	} else {
		var ilosc_zgl = document.getElementById('cnt_zgl').value;
		for (var g=0; g<ilosc_zgl; g++) {
			id_nr = document.getElementById('nr_zgl-'+g).value;		
			document.getElementById('oz_godzina-'+id_nr).value=document.getElementById('oz_godzina_dla_wszystkich').value;
		}
	}
}
function UpdateStatusDlaWszystkich(s) {
var ilosc_zgl = document.getElementById('cnt_zgl').value;
	for (var g=0; g<ilosc_zgl; g++) {
		id_nr = document.getElementById('nr_zgl-'+g).value;
		var il_pozycji = document.getElementById('SelectZmienStatus-'+id_nr).options.length-1;
		for (var h=0; h<=il_pozycji; h++) {
			if (document.getElementById('SelectZmienStatus-'+id_nr).options[h].value==s) {		
document.getElementById('SelectZmienStatus-'+id_nr).options[h].selected=true;
break;
			}
		}
	}
}
function UpdateSumaCzasu() {
var ilosc_zgl = document.getElementById('cnt_zgl').value;
var _temp_suma = 0;
	for (var g=0; g<ilosc_zgl; g++) {
		id_nr = document.getElementById('nr_zgl-'+g).value;		
		_temp_suma += (document.getElementById('oz_czas-'+id_nr).value*1);
	}
	document.getElementById('czas_razem').value = _temp_suma;
}

function UpdateSumaCzasu2() {
var ilosc_zgl = document.getElementById('cnt_zgl').value;
var _temp_suma = 0;
	for (var g=0; g<ilosc_zgl; g++) {
		id_nr = document.getElementById('nr_zgl-'+g).value;		
		_temp_suma += (document.getElementById('oz_czas_przejazdu-'+id_nr).value*1);
	}
	document.getElementById('czas_przejazdu_razem').value = _temp_suma;
}

function UpdateSumaKM() {
var ilosc_zgl = document.getElementById('cnt_zgl').value;
var _temp_suma = 0;
	for (var g=0; g<ilosc_zgl; g++) {
		id_nr = document.getElementById('nr_zgl-'+g).value;		
		_temp_suma += (document.getElementById('oz_km-'+id_nr).value*1);
	}	
	document.getElementById('km_razem').value = _temp_suma;
}
function GenerujTraseWyjazdowa() {
	var ilosc_zgl = document.getElementById('cnt_zgl').value;
	var pole = new Array();
	for (var s=1; s<=ilosc_zgl; s++) { pole[s]=s; }
	
	for (var k=0; k<ilosc_zgl; k++) {
		var ok = 0;
		if (document.getElementById('order-'+k).value=='') { ok=0; } else {
			for (var s=1; s<=ilosc_zgl; s++) { 
var current_order = document.getElementById('order-'+k).value;
if ((current_order==pole[s]) ) { 
	ok = 1; 
	pole[s]=0;
	break; 
} 
			}	
		}
		if (ok!=1) {
			alert('Niepoprawnie określona kolejność wyjazdowa. \n\rProszę poprawić błędne wpisy !');
			PrepareRows(1);
			document.getElementById('trasa_wyjazdu').value='';
			document.getElementById('order-'+k).value='';
			document.getElementById('order-'+k).focus();
			return false;		
		}
	}	
	// jeżeli nie wpisano km
	if (document.getElementById('km_razem').value=='0') {
		document.getElementById('ilosc_km').focus();
		return false;
	}
	
	var lz = document.getElementById('lokalizacjazrodlowa').value.toUpperCase();		
	var wyjazd_z_lz = document.getElementById('WyjazdZFilii').checked;
	var powrot_do_lz = document.getElementById('PowrotDoFilii').checked;
	var trasa = '';
	if (wyjazd_z_lz) { 
		trasa+=lz; 
		if ((powrot_do_lz) || (ilosc_zgl>0)) trasa+=' - '; 
	}
	var nr = 0;
	for (var g=0; g<ilosc_zgl; g++) {
		nr = g + 1;
		for (var h=0; h<ilosc_zgl; h++) {
			order_nr = document.getElementById('order-'+h).value;
			if (order_nr==nr) {
id_nr = document.getElementById('nr_zgl-'+h).value;
var punkt_trasy = document.getElementById('komorka-'+id_nr).value.toUpperCase();
trasa+=punkt_trasy;
			}
		}
		if ((g<ilosc_zgl-1) || (powrot_do_lz)) trasa+=' - ';
	}
	if (powrot_do_lz) trasa+=lz;
	document.getElementById('trasa_wyjazdu').value = trasa;
}
function GenerujTraseWyjazdowa_NOWA() {
var ilosc_zgl = document.getElementById('cnt_zgl').value;
var pole = new Array();
	for (var s=1; s<=ilosc_zgl; s++) { pole[s]=s; }
	for (var k=0; k<ilosc_zgl; k++) {
		
		var ok = 0;
		if (document.getElementById('order-'+k).value=='') { ok=0; } else {
			for (var s=1; s<=ilosc_zgl; s++) { 
			var current_order = document.getElementById('order-'+k).value;
			if ((current_order==pole[s]) ) { 
				ok = 1; 
				pole[s]=0;
				break; 
			} 
			}
		}
		if (ok!=1) {
			alert('Niepoprawnie określona kolejność wyjazdowa. \n\rProszę poprawić błędne wpisy !');
			PrepareRows(1);
			document.getElementById('trasa_wyjazdu').value='';
			document.getElementById('order-'+k).value='';
			document.getElementById('order-'+k).focus();
			return false;		
		}
	}
	// jeżeli nie wpisano km
	if (document.getElementById('km_razem').value=='0') {
		//document.getElementById('ilosc_km').focus();
		return false;
	}
	
	var lz = document.getElementById('lokalizacjazrodlowa').value.toUpperCase();		
	var wyjazd_z_lz = document.getElementById('WyjazdZFilii').checked;
	var powrot_do_lz = document.getElementById('PowrotDoFilii').checked;
	var trasa = '';
	if (wyjazd_z_lz) { 
		trasa+=lz; 
		if ((powrot_do_lz) || (ilosc_zgl>0)) trasa+=' - '; 
	}
	var kolejny = 0;
	var nr = 0;
	for (var g=0; g<ilosc_zgl; g++) {
		nr = g + 1;
		for (var h=0; h<ilosc_zgl; h++) {
			order_nr = document.getElementById('order-'+h).value;
			if (order_nr==nr) {
id_nr = document.getElementById('nr_zgl-'+h).value;
var punkt_trasy = document.getElementById('komorka-'+id_nr).value.toUpperCase();
var punkt_trasy_sam = document.getElementById('komorka-'+id_nr).value.toUpperCase();
if ((kolejny>0) && (kolejny<ilosc_zgl)) {
	punkt_trasy = old_punkt + ' - ' + punkt_trasy;
}
if (kolejny==0) { punkt_trasy = lz + ' - ' + punkt_trasy; }
var old_punkt=punkt_trasy_sam;
kolejny+=1;
if (kolejny==ilosc_zgl) { punkt_trasy = punkt_trasy + ' - '+ lz; }
document.getElementById('trasa-'+nr).value = punkt_trasy;
document.getElementById('trasa_hidden-'+id_nr).value = punkt_trasy;
document.getElementById('km-'+nr).value = document.getElementById('oz_km-'+id_nr).value;
			}
		}		
	}
}
function CzyscTrasyWyjazdowe() {
	var ilosc_zgl = document.getElementById('cnt_zgl').value;
	document.getElementById('trasa_wyjazdu').value='';
	for (var s=1; s<=ilosc_zgl; s++) { 
		document.getElementById('trasa-'+s).value=''; 
		document.getElementById('km-'+s).value='-';
	}
}
function PrepareRows(v) {
	var ilosc_zgl = document.getElementById('cnt_zgl').value;
	var params = '';
	
	if (v==1) {
		for (var g=0; g<ilosc_zgl; g++) {
			document.getElementById('order-'+g).style.display='';
			document.getElementById('numer-'+g).style.display='none';
			
			document.getElementById('dec-'+g).style.display='';
			document.getElementById('inc-'+g).style.display='';
			
			var nr = g;
			nr++;
			if (document.getElementById('order-'+g).value=='') document.getElementById('order-'+g).value=nr;
		}
		document.getElementById('th_kolumna_z_nr').style.display='none';
		document.getElementById('th_kolumna_z_kolejnoscia').style.display='';			
		document.getElementById('UstawKolejnosc').style.display='none';
		document.getElementById('ZapiszKolejnosc').style.display='';
		
	} else {
	
		for (var g=0; g<ilosc_zgl; g++) {
			document.getElementById('order-'+g).style.display='none';
			document.getElementById('numer-'+g).style.display='';
	
			document.getElementById('dec-'+g).style.display='none';
			document.getElementById('inc-'+g).style.display='none';
	
			var temp_zgl = document.getElementById('nr_zgl-'+g).value;
			var temp_order = document.getElementById('order-'+g).value;
			var pair = temp_zgl + "@" + temp_order + ",";
			params += pair;
		}
		
		document.getElementById('th_kolumna_z_nr').style.display='';
		document.getElementById('th_kolumna_z_kolejnoscia').style.display='none';			
		
		if (document.getElementById('obsl_powiazana_z_wyjazdem').checked==false) {
			document.getElementById('UstawKolejnosc').style.display='none';
		} else 
			document.getElementById('UstawKolejnosc').style.display='';
		document.getElementById('ZapiszKolejnosc').style.display='none';
	}
}
function Decrease(v) { 
	if (v.value>1) { 
		v.value--; 
		CzyscTrasyWyjazdowe(); 
	} 
}
function Increase(v) { 
var ilosc_zgl = document.getElementById('cnt_zgl').value;
	if (v.value<ilosc_zgl) v.value++;
	CzyscTrasyWyjazdowe();
}
function SeryjnaObslugaCheckOnSubmit() {
var ilosc_zgl = document.getElementById('cnt_zgl').value;
	
	var ok = 0;
	var okwc = 0;
	
	for (var g=0; g<ilosc_zgl; g++) {
		var temp_zgl = document.getElementById('nr_zgl-'+g).value;		
		
		if (formatTime(document.getElementById("oz_godzina-"+temp_zgl+"").value)==false) {
			alert('Błędnie wpisana godzina'); 
			document.getElementById("oz_godzina-"+temp_zgl+"").value=''; 
			document.getElementById("oz_godzina-"+temp_zgl+"").focus(); 
			return false;
		}
		
		if (document.getElementById("oz_godzina-"+temp_zgl+"").value!="")  { ok = ok + 1; } else { last_name = "oz_godzina-"+temp_zgl+""; }
		
		if (document.getElementById("wyk_czynnosci_value-"+temp_zgl+"").value!="")  { okwc = okwc + 1; } else { last_name = "wyk_czynnosci_value-"+temp_zgl+""; }
	}
	
	if (ok!=ilosc_zgl) {
		alert('Nie uzupełniono wszystkich godzin zakończenia dla obsługiwanych zgłoszeń');
		document.getElementById(''+last_name+'').focus();
		return false;
	} 
	if (okwc!=ilosc_zgl) {
		alert('Nie wpisano wykonanych czynności dla każdego ze zgłoszeń');
		document.getElementById(''+last_name+'').focus();
		return false;
	} 
	
	if ((document.getElementById('trasa-1').value=='') && (document.getElementById('obsl_powiazana_z_wyjazdem').checked)) {
		if (confirm('Trasa wyjazdowa jest pusta !\r\n\r\nWygenerować ją teraz ?')) {
			if (GenerujTraseWyjazdowa_NOWA()) { SeryjnaObslugaCheckOnSubmit(); }
			return false;
		} else { 
	document.getElementById('btn_GenerujTrase').focus();
	return false; 
}
	}
	
	// sprawdź poprawność czasów
	/*
	for (var g=0; g<ilosc_zgl; g++) {
		var temp_zgl = document.getElementById('nr_zgl-'+g).value;		
		var last_step_date = document.getElementById("data_ostatniego_kroku_value-"+temp_zgl+"").value;	
		var last_step_time = document.getElementById("godzina_ostatniego_kroku_value-"+temp_zgl+"").value;	
		var new_step_date = document.getElementById("oz_data-"+temp_zgl+"").value;	
		var new_step_time = document.getElementById("oz_godzina-"+temp_zgl+"").value;
		var new_cw_m = document.getElementById("oz_czas-"+temp_zgl+"").value;
		
		var last_step_time_in_seconds = ((last_step_time.substring(0,2)*1*3600)+(last_step_time.substring(3,5)*1*60));
		var new_step_time_in_seconds = (((new_step_time.substring(0,2)*1*3600)+(new_step_time.substring(3,5)*1*60))-(new_cw_m*1*60));
		
		var h = Math.floor(new_step_time_in_seconds / 3600);
		var m = Math.floor(new_step_time_in_seconds % 3600 / 60);
		if (h<10) h = "0"+h+"";
		if (m<10) m = "0"+m+"";
		if (new_step_time_in_seconds<=last_step_time_in_seconds) {
			if (last_step_date==new_step_date) {
				alert('Dla zgłoszenia numer: '+temp_zgl+', nowa data zakończenia kroku, po uwzględnieniu czasu wykonywania ('+new_step_date+' '+h+':'+m+') jest wcześniejsza niż data zakończenia poprzedniego kroku ('+last_step_date+' '+last_step_time.substring(0,5)+')');
				return false;
			}
		}	
	}
	*/
	
/*	if (document.getElementById('czas_razem').value==0) {
		alert('Nie podano łącznego czasu poświęconego na wykonanie');
		document.getElementById('czas_wykonywania_m').select();
		document.getElementById('czas_wykonywania_m').focus();
		return false;		
	}
*/	
/*
	for (var g=0; g<ilosc_zgl; g++) {
		var temp_zgl = document.getElementById('nr_zgl-'+g).value;	
		var new_cw_m = document.getElementById("oz_czas-"+temp_zgl+"").value;
		
		if (new_cw_m == 0) {
			alert('Podano zerowy czas wykonywania w zgłoszeniu nr '+temp_zgl+'');
			document.getElementById("oz_czas-"+temp_zgl+"").select();
			document.getElementById("oz_czas-"+temp_zgl+"").focus();
			return false;
		}
	}
*/	
	//alert(((document.getElementById('czas_wykonywania_h').value*60)+(document.getElementById('czas_wykonywania_m').value*1)));
	// sprawdź czy podane czasy łączne nie przekraczają wyliczonych z poszczególnych zgłoszeń
	
/*
	if (((document.getElementById('czas_wykonywania_h').value*60)+(document.getElementById('czas_wykonywania_m').value*1)) != document.getElementById('czas_razem').value) {
		alert('Podany łącznie czas poświęcony na wykonanie jest niezgodny z sumą czasów przypisanych do poszczegółnych zgłoszeń');
		return false;
	}
*/

/*	if (document.getElementById('ilosc_km').style.display=='') {
		if (document.getElementById('ilosc_km').value=='') document.getElementById('ilosc_km').value=0;
		if ((document.getElementById('ilosc_km').value) != document.getElementById('km_razem').value) {
			alert('Podane łącznie przejechane km są niezgodne z sumą km przypisanych do poszczegółnych zgłoszeń');
			return false;
		}
	}
*/

/*	if (((document.getElementById('czas_przejazdu_h').value*60)+(document.getElementById('czas_przejazdu_m').value*1)) != document.getElementById('czas_przejazdu_razem').value) {
		alert('Podany łącznie czas poświęcony na przejazd jest niezgodny z sumą czasów przejazdu przypisanych do poszczegółnych zgłoszeń');
		return false;
	}
*/
	
	if (confirm('Czy napewno chcesz zapisać zmiany ?')) {
		document.getElementById('content').style.display='none';
		document.getElementById('WykonajObslugeSeryjna').style.display='none';
		document.getElementById('anuluj').style.display='none';
		document.getElementById('reset').style.display='none';
		document.getElementById('Saving').style.display='';
		document.forms.seryjna_obsluga_zgloszen.submit(); 
		return true;
	} else {
		//document.getElementById('czas_wykonywania_h').focus();
		return false;
	}
	
}
function WykonaneCzynnosci(v) {
	var ilosc_zgl = document.getElementById('cnt_zgl').value;
	
    for (i=0; i<ilosc_zgl; i++) {
        var zgl_nr_temp = document.getElementById('nr_zgl-'+i).value;
		
		//if (document.getElementById('wyk_czynnosci-'+zgl_nr_temp).name.substring(0,13) == 'wyk_czynnosci') {
			if (v=='pokaz') { 
document.getElementById('wyk_czynnosci-'+zgl_nr_temp).style.display=''; 
document.getElementById('showmore-'+i).style.display='none'; 
document.getElementById('hidemore-'+i).style.display=''; 
			}
			
			if (v=='ukryj') { 
document.getElementById('wyk_czynnosci-'+zgl_nr_temp).style.display='none'; 
document.getElementById('showmore-'+i).style.display=''; 
document.getElementById('hidemore-'+i).style.display='none'; 
			}
      // }
	}
	return false;
}
function PowielWykonaneCzynnosci() {
var ilosc_zgl = document.getElementById('cnt_zgl').value;
	for (i=0; i<ilosc_zgl; i++) {
		var zgl_nr_temp = document.getElementById('nr_zgl-'+i).value;
		document.getElementById('wyk_czynnosci_value-'+zgl_nr_temp).value = document.getElementById('wyk_czynnosci_value-all').value;
	}
	return false;
}
function CzyscWykonaneCzynnosci() {
	if (document.getElementById('wyk_czynnosci_value-all').value == '') return true;
	if (confirm('Czy napewno chcesz wyczyścić opis wykonanych czynności we wszystkich wybranych zgłoszeniach ?')) {
		var ilosc_zgl = document.getElementById('cnt_zgl').value;
		
		for (i=0; i<ilosc_zgl; i++) {
			var zgl_nr_temp = document.getElementById('nr_zgl-'+i).value;
			document.getElementById('wyk_czynnosci_value-'+zgl_nr_temp).value = '';
		}
		document.getElementById('wyk_czynnosci_value-all').value = '';
	}
	return false;
}
function PokazZasadnoscZgloszenia_onLoad() {
	var ilosc_zgl = document.getElementById('cnt_zgl').value;
	
    for (i=0; i<ilosc_zgl; i++) {
        var zgl_nr_temp = document.getElementById('nr_zgl-'+i).value;
		var k = document.getElementById('kat_zgl-'+zgl_nr_temp).value;
		var p = document.getElementById('podkat_zgl-'+zgl_nr_temp).value;
		var z = document.getElementById('SelectZmienStatus-'+zgl_nr_temp).value;
		
			document.getElementById('OSZ_zasadnosc_zgloszenia-'+zgl_nr_temp).style.display='none';
			document.getElementById('AwariaWAN-'+zgl_nr_temp).style.display='none';
		
			if (z==9) {
document.getElementById('OSZ_zasadnosc_zgloszenia-'+zgl_nr_temp).style.display='';
			} 
			if ((z=='3A')&&(k==2)&&(p==0)) { document.getElementById('AwariaWAN-'+zgl_nr_temp).style.display=''; }
	}
	
	if (document.getElementById('SelectZmienStatus_all').value==9) {
		document.getElementById('OSZ_zasadnosc_zgloszenia-all').style.display='';
	} else {
		document.getElementById('OSZ_zasadnosc_zgloszenia-all').style.display='none';
	}
	return false;
}
function PokazZasadnoscZgloszenia(z,v,k,p) {
	document.getElementById('AwariaWAN-'+v).style.display='none';
	document.getElementById('OSZ_zasadnosc_zgloszenia-'+v).style.display='none';
	if (z==9) { document.getElementById('OSZ_zasadnosc_zgloszenia-'+v).style.display=''; exit; }
	if ((z=='3A')&&(k==2)&&(p==0)) { document.getElementById('AwariaWAN-'+v).style.display=''; exit; }
}
function setCheckedValue(radioObj, newValue) {
	if(!radioObj)
		return;
	var radioLength = radioObj.length;
	if(radioLength == undefined) {
		radioObj.checked = (radioObj.value == newValue.toString());
		return;
	}
	for(var i = 0; i < radioLength; i++) {
		radioObj[i].checked = false;
		if(radioObj[i].value == newValue.toString()) {
			radioObj[i].checked = true;
		}
	}
}
function UstawZZDlaWszystkich(s) {
var ilosc_zgl = document.getElementById('cnt_zgl').value;
	for (var g=0; g<ilosc_zgl; g++) {
		id_nr = document.getElementById('nr_zgl-'+g).value;
		if (s=='TAK') {
			setCheckedValue(document.forms['seryjna_obsluga_zgloszen'].elements['zasadne-'+id_nr], s);
		} else {
			setCheckedValue(document.forms['seryjna_obsluga_zgloszen'].elements['zasadne-'+id_nr], s);	
		}
	}
}
</script>
<?php } ?>
<?php $pw = array($_f."/hd_g_delegacje.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
<script>
function CheckForm_hd_g_delegacje() {
	var miesiac = document.getElementById('Okres').value;
	var pojazd = document.getElementById('Pojazd').value;
	var GenerujButton = document.getElementById('generuj_submit');
	if ((miesiac!='') && (pojazd!='')) {
		GenerujButton.style.display='';
	} else GenerujButton.style.display='none';
}
</script>
<?php } ?>
<?php $pw = array($_f."/d_faktura_pozycja_zapisz.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
<script>
function UsunMalpy(c) {
	if ((c.value[0]=='@') && (c.value[c.value.length - 1]=='@')) c.value = c.value.substring(1, c.value.length - 1);
}
function Przecinek_na_kropke(strSource,strFind,strReplace) {
	var temp = strSource;
	var resuly = '';
	while (temp.length>0){
		index = temp.indexOf(strFind);
		if (index>=0) {
			result += temp.substring(0,index);
			result += strReplace;
			temp = temp.substring(index + strFind.length);
		} else {
			result += temp;
			temp = '';
		}
	}
	return result;
}
</script>
<?php } ?>

<?php $pw = array($_f."/hd_e_zgloszenie.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
<link rel="stylesheet" type="text/css" href="js/jquery/jquery.autocomplete.css" />
<script type="text/javascript" src="js/jquery/jquery-1.6.min.js"></script>
<script type="text/javascript" src="js/jquery/jquery.autocomplete.js"></script>
<script>
function cUpper(cObj) { cObj.value=trimAll(cObj.value.toUpperCase()); }
function CompareDates() {
    var data1 = document.getElementById('old_data_zak').value;
    var data2 = document.getElementById('nowa_data_zak').value;
    var czas1 = document.getElementById('old_czas_zak').value;
    var czas2 = document.getElementById('nowy_czas_zak').value;
	
	if (data2<data1) {
		alert("Nowa data zakończenia zgłoszenia nie może być wcześniejsza niż aktualna");
		document.getElementById('nowa_data_zak').focus();
        return false;		
	}
	
	if (data2==data1) {
		if (czas2<=czas1) {
			alert("Nowa godzina zakończenia zgłoszenia musi być większa niż aktualna");
			document.getElementById('nowy_czas_zak').focus();
			return false;
		} else {
			return true;
		}	
	}
	if (data2>data1) return true;
} 
function pytanie_zapisz_ez(message) {
	if (document.getElementById('nowa_data_zak').value=='') {
		alert('Nie podano nowej daty zakończenia zgłoszenia'); document.getElementById('nowa_data_zak').focus(); return false; 
	}
	
	if (document.getElementById('nowy_czas_zak').value=='') {
		alert('Nie podano nowego czasu zakończenia zgłoszenia"'); document.getElementById('nowy_czas_zak').focus(); return false; 
	}
	
	if (CompareDates()==false) return false;
		
	if (document.getElementById('tresc_ustalen').value=='') {
		alert('Nie podano treści ustaleń'); document.getElementById('tresc_ustalen').focus(); return false; 
	}	
	if (document.getElementById('hd_opp').value=='') {
		alert('Nie podano osoby potwierdzającej przesunięcie terminu'); document.getElementById('hd_opp').focus(); return false; 
	}	
	
	if (OdrzucNiedozwolone('hd_opp',document.getElementById('hd_opp').value)=='1') {
		alert("Wpisano nieprawidłową wartość w polu. Osoba potwierdzająca przesunięcie terminu rozpoczęcia realizacji zgłoszenia należy wpisać z imienia i nazwiska");
		document.getElementById('hd_opp').focus();
		return false;
	}	
	
	if (confirm(message)) { 
		document.getElementById('submit').style.display='none';
		document.getElementById('anuluj').style.display='none';
		document.getElementById('Saving').style.display='';
		document.forms.add.submit(); 
		return true; 
	} else return false; 
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
function CheckTime(t) {
	if (formatTime(t)==false) {
		alert('Błędnie wpisana godzina'); document.getElementById('nowy_czas_zak').value=''; document.getElementById('nowy_czas_zak').focus(); return false;
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
		if (IsNumeric(CzasWpisany)==false) { alert('Błędnie wpisany czas'); document.getElementById('nowy_czas_zak').value=''; document.getElementById('nowy_czas_zak').focus(); return false; }
		
		if (TerazData==document.getElementById('nowy_czas_zak').value) {
			if (CzasWpisany>TerazCzas) { alert('Wpisałeś godzinę która jeszcze nie nastała'); document.getElementById('hdgz').focus(); return false; }
		}
		return true;
	}
}
function CheckDate(t) {
	if (t=='') return false;
	DataWpisana = t.substring(0,4)+""+t.substring(5,7)+""+t.substring(8,10);
	if (t.substring(4,5)!='-') { alert('Błędnie wpisana data'); document.getElementById('nowa_data_zak').value=''; document.getElementById('nowa_data_zak').focus(); return false; }
	if (t.substring(7,8)!='-') { alert('Błędnie wpisana data'); document.getElementById('nowa_data_zak').value=''; document.getElementById('nowa_data_zak').focus(); return false; }
	if ((t.substring(0,4)<2010) || (t.substring(0,4)>2100)) { alert('Błędnie wpisana data (rok)'); document.getElementById('nowa_data_zak').value=''; document.getElementById('nowa_data_zak').focus(); return false; }
	if ((t.substring(5,7)<0) || (t.substring(5,7)>12)) { alert('Błędnie wpisana data (miesiac)'); document.getElementById('nowa_data_zak').value=''; document.getElementById('nowa_data_zak').focus(); return false; }
	if ((t.substring(8,10)<0) || (t.substring(5,7)>31)) { alert('Błędnie wpisana data (dzien)'); document.getElementById('nowa_data_zak').value=''; document.getElementById('nowa_data_zak').focus(); return false; }
}
function KopiujDo1Entera(val, dest) {
	var w1 = val.replace("\n","<br>");
	var s1 = w1.search("<br>");
	if (s1>0) {
		document.getElementById(dest).value=w1.substring(0,s1);
		return true;
	}
	document.getElementById(dest).value=w1;
}
function ex1(val) {
	var text = val.value.replace(/\s+$/g,"") 
	var split = text.split("\n") 
	if (split.length<5) val.rows = split.length+1;
}
</script>
<?php } ?>
<?php $pw = array($_f."/hd_p_zgloszenie_kroki.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
<script type="text/javascript" src="js/jquery/jquery-1.6.min.js"></script>
<?php } ?>
<?php $pw = array($_f."/hd_e_zgloszenie_krok.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
<script>
function formatTime(time) {
    var result1 = false, m;
    var re = /^\s*([01]?\d|2[0-3]):?([0-5]\d)\s*$/;
    if ((m = time.match(re))) {
        //result1 = (m[1].length == 2 ? "" : "0") + m[1] + ":" + m[2];
		result1 = true;
    }
    return result1;
}
function CheckTime(t) {
if (formatTime(t)==false) {
	alert('Błędnie wpisana godzina'); 
	document.getElementById('data_wykonywania_g').value=''; document.getElementById('data_wykonywania_g').focus(); return false;
} else {
	return true;
}
}
function filterInput(filterType,evt,allowDecimal,allowCustom){var keyCode,Char,inputField,filter='';var alpha='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';var num='0123456789';if(window.event){keyCode=window.event.keyCode;evt=window.event}else if(evt)keyCode=evt.which;else return true;if(filterType==0)filter=alpha;else if(filterType==1)filter=num;else if(filterType==2)filter=alpha+num;if(allowCustom)filter+=allowCustom;if(filter=='')return true;inputField=evt.srcElement?evt.srcElement:evt.target||evt.currentTarget;if((keyCode==null)||(keyCode==0)||(keyCode==8)||(keyCode==9)||(keyCode==27))return true;Char=String.fromCharCode(keyCode);if((filterType==1)&&(String.fromCharCode(keyCode)==',')){Char='.'}if((filter.indexOf(Char)>-1))return true;else if(filterType==1&&allowDecimal&&(Char=='.')&&inputField.value.indexOf('.')==-1)return true;else return false}
function ex1(val) {
	var text = val.value.replace(/\s+$/g,"") 
	var split = text.split("\n") 
	if (split.length<5) val.rows = split.length+1;
}
function DopiszDwukropek(v) {
var r = document.getElementById(v).value.length;
if (r==2) document.getElementById(v).value = document.getElementById(v).value+':';
if (r==5) CheckTime(document.getElementById(v).value);
}
function pytanie_zatwierdz_edycje_kroku(message){ 
var a = document.getElementById('element').value;
	if (a=='czas') {
	
		if (CheckTime(document.getElementById('data_wykonywania_g').value)==false) {
			//alert('Błędnie wpisana godzina wykonania kroku'); 
			document.getElementById('data_wykonywania_g').focus();
			return false; 
		}
		
		<?php if ($zerowe_czasy_wykonania==FALSE) { ?>
		if ((document.getElementById('czas_wykonywania_h').value==0) && (document.getElementById('czas_wykonywania_m').value==0)) {
			alert('Podano zerowy czas wykonywania');
			document.getElementById('czas_wykonywania_m').select();
			document.getElementById('czas_wykonywania_m').focus();
			return false;
		}
		<?php } ?>
	
		if ((document.getElementById('kat_id')) && (document.getElementById('kat_id').value=='1')) {
			var il_m = (document.getElementById('czas_wykonywania_h').value*60);
			il_m = il_m + ((document.getElementById('czas_wykonywania_m').value)*1);
			
			if (il_m > <?php echo $max_ilosc_minut_dla_konsultacji; ?>) {
				alert('Przekroczono maksymalną dopuszczalną ilość minut dla konsultacji (<?php echo $max_ilosc_minut_dla_konsultacji; ?> minut).\r\n\r\nNależy poprawić czas wykonywania.');
				document.getElementById('czas_wykonywania_m').focus();
				return false;
			}
		}
		
	}
	if (confirm(message)) { 
		
		document.forms.add.submit(); 
		return true; 
	} else return false; 
	
	return false;
}
</script>
<?php } ?>
<?php
$pw = array($_f."/hd_d_slownik_tresc.php",$_f."/hd_e_slownik_tresc.php",$_f."/hd_z_slownik_tresci.php",$_f."/hd_d_slownik_tresc_zs.php");
if (array_search($PHP_SELF, $pw)>-1) {
	include_once('js/hd_d_zgloszenie_i_s_scripts.php');
?>
<script>
function urlencode1 (str) {
    str = (str+'').toString();
    return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+');
}

function pytanie_zatwierdz_d_slownik(message){ 
	
	if (document.getElementById('sl_tresc').value=='') { 
		alert('Nie podałeś treści'); document.getElementById('sl_tresc').focus(); return false; 
	}

	if (document.getElementById('sl_kat_id').value=='') { 
		alert('Nie wybrałeś kategorii'); document.getElementById('sl_kat_id').focus(); return false; 
	}
	
	if (document.getElementById('sl_podkat_id').value=='') { 
		alert('Nie wybrałeś podkategorii'); document.getElementById('sl_podkat_id').focus(); return false; 
	}

	if (document.getElementById('sl_sub_podkat_id').options[document.getElementById('sl_sub_podkat_id').options.selectedIndex].text=='') { 
		alert('Nie wybrałeś podkategorii (poziom 2)'); document.getElementById('sl_sub_podkat_id').focus(); return false; 
	}
	
	if (confirm(message)) { 
		document.getElementById('submit').style.display='none';		
		document.getElementById('anuluj').style.display='none';
		document.getElementById('Saving').style.display='';
		
		document.forms.add.submit(); 
		return true; 
	} else return false; 
}

function pytanie_zatwierdz_e_slownik(message){ 
	
	if (document.getElementById('sl_tresc').value=='') { 
		alert('Nie podałeś treści'); document.getElementById('sl_tresc').focus(); return false; 
	}

	if (document.getElementById('sl_kat_id').value=='') { 
		alert('Nie wybrałeś kategorii'); document.getElementById('sl_kat_id').focus(); return false; 
	}
	
	if (document.getElementById('sl_podkat_id').value=='') { 
		alert('Nie wybrałeś podkategorii'); document.getElementById('sl_podkat_id').focus(); return false; 
	}

	if (document.getElementById('sl_sub_podkat_id').options[document.getElementById('sl_sub_podkat_id').options.selectedIndex].text=='') { 
		alert('Nie wybrałeś podkategorii (poziom 2)'); document.getElementById('sl_sub_podkat_id').focus(); return false; 
	}
	
	if (confirm(message)) { 
		document.getElementById('submit').style.display='none';		
		document.getElementById('anuluj').style.display='none';
		document.getElementById('Saving').style.display='';
		
		document.forms.add.submit(); 
		return true; 
	} else return false; 
}


function AddToList(listname, list_opis, list_value, bool1, bool2) {
	listname.options[listname.options.length] = new Option(""+list_opis+"",""+list_value+"",bool1, bool2);
}
function MakePodkategoriaList_sl(o,sel) {
	if (document.getElementById('sl_kat_id')) {
		var k_s = document.getElementById('sl_kat_id');
	} 
		
	if (document.getElementById('sl_podkat_id')) {
		var p_s = document.getElementById('sl_podkat_id');
		p_s.options.length=0;
	} 
	
	if (document.getElementById('sl_sub_podkat_id')) {
		var p2_s = document.getElementById('sl_sub_podkat_id');
		p2_s.options.length=0;
	}
	
	// konserwacje
	if (o == "7") {
		p_s.options[p_s.options.length] = new Option("Konserwacja sprzętu","8",true,true);
		p_s.disabled=false;
		AddToList(p2_s,'Brak','',true,true);
	}

	// konsultacje 
	if (o == "1") {
		p_s.options[p_s.options.length] = new Option("Brak","1",true,true);
		p_s.disabled=false;
		AddToList(p2_s,'Brak','',true,true);
	}		
		
	if (o == "2") {
		p_s.options[p_s.options.length] = new Option("Serwer","4",false,false);
		p_s.options[p_s.options.length] = new Option("Stacja robocza","3",true,true);
		p_s.options[p_s.options.length] = new Option("Urządzenia peryferyjne","9",false,false);
		p_s.options[p_s.options.length] = new Option("Oprogramowanie - wsparcie użytkownika","2",false,false);
		p_s.options[p_s.options.length] = new Option("Oprogramowanie - problemy techniczne","7",false,false);
		p_s.options[p_s.options.length] = new Option("WAN/LAN","0",false,false);
		p_s.disabled=false;
	
		AddToList(p2_s,'Sprzęt','Sprzęt',true,true);
		AddToList(p2_s,'Oprogramowanie','Oprogramowanie',false,false);
	}

	if (o == "6") {	
		p_s.options[p_s.options.length] = new Option("Serwer","4",true,true);
		p_s.options[p_s.options.length] = new Option("Stacja robocza","3",false,false);
		p_s.options[p_s.options.length] = new Option("Urządzenia peryferyjne","9",false,false);
		p_s.options[p_s.options.length] = new Option("WAN/LAN","0",false,false);
		p_s.options[p_s.options.length] = new Option("Oprogramowanie - problemy techniczne","7",false,false);
		p_s.disabled=false;
		
		AddToList(p2_s,'Sprzęt','Sprzęt',true,true);
		AddToList(p2_s,'Oprogramowanie','Oprogramowanie',false,false);			
	}
	
	
	if (o == "3") {
		p_s.options[p_s.options.length] = new Option("Serwer","4",false,false);
		p_s.options[p_s.options.length] = new Option("Stacja robocza","3",false,false);
		p_s.options[p_s.options.length] = new Option("Urządzenia peryferyjne","9",false,false);
		p_s.options[p_s.options.length] = new Option("Oprogramowanie - wsparcie użytkownika","2",true,true);
		p_s.options[p_s.options.length] = new Option("Oprogramowanie - problemy techniczne","7",false,false);
		p_s.options[p_s.options.length] = new Option("Aktualizacje oprogramowania","6",false,false);
		p_s.options[p_s.options.length] = new Option("Kopie bezpieczeństwa","H",false,false);
		p_s.options[p_s.options.length] = new Option("Domena","I",false,false);
		p_s.options[p_s.options.length] = new Option("Alarmy","E",false,false);
		p_s.options[p_s.options.length] = new Option("WAN/LAN","0",false,false);
		p_s.options[p_s.options.length] = new Option("Inne","D",false,false);
		p_s.options[p_s.options.length] = new Option("Otwarcie placówki pocztowej","A",false,false);
		p_s.options[p_s.options.length] = new Option("Przeniesienie placówki pocztowej","B",false,false);
		p_s.options[p_s.options.length] = new Option("Zamknięcie placówki pocztowej","C",false,false);
		p_s.options[p_s.options.length] = new Option("Prace administracyjno-sprawozdawcze","F",false,false);
		p_s.disabled=false;
		
		AddToList(p2_s,'SP2000','SP2000',true,true);
		AddToList(p2_s,'MBP','MBP',false,false);
		AddToList(p2_s,'ŁP14','ŁP14',false,false);
		AddToList(p2_s,'MRU','MRU',false,false);
		AddToList(p2_s,'SEDI','SEDI',false,false);
		AddToList(p2_s,'MRUm','MRUm',false,false);
		AddToList(p2_s,'ZST WiemPost','ZST WiemPost',false,false);
		AddToList(p2_s,'Reklamacje','Reklamacje',false,false);
		AddToList(p2_s,'Lotus Notes','Lotus Notes',false,false);
		AddToList(p2_s,'Office','Office',false,false);
		AddToList(p2_s,'Przeglądarka internetowa','Przeglądarka internetowa',false,false);
		AddToList(p2_s,'Outlook Express','Outlook Express',false,false);
		AddToList(p2_s,'S.N.O.S.P.','S.N.O.S.P.',false,false);
		AddToList(p2_s,'PenGuard','PenGuard',false,false);			
		AddToList(p2_s,'Acrobat Reader','Acrobat Reader',false,false);
		AddToList(p2_s,'Obsługa systemu operacyjnego','Obsługa systemu operacyjnego',false,false);
		AddToList(p2_s,'Inne','Inne',false,false);		
	}
	
	
	if (o == "4") {
		p_s.options[p_s.options.length] = new Option("Inne","D",false,false);
		p_s.options[p_s.options.length] = new Option("Otwarcie placówki pocztowej","A",false,false);
		p_s.disabled=false;	
		AddToList(p2_s,'Brak','',true,true);
	}
	if (o == "5") {
		p_s.options[p_s.options.length] = new Option("Brak","1",true,true);
		p_s.disabled=false;	
		AddToList(p2_s,'Brak','',true,true);
	}

}

function urldecode1(str) {
	str = str.replace(' ', '+');
	str = unescape(str);
	str = str.replace(' ', '+');
	return str;
}

function ApplyFiltrHD_sl_1(bool) {
	if (bool) {
		var sa = document.getElementById('showall1').value; 
		var pa = document.getElementById('page11').value; 
		var pt = document.getElementById('page12').value; 
		var kat = document.getElementById('filtr2').value; 
		var podkat = document.getElementById('filtr3').value; 
		var podkat2 = document.getElementById('filtr4').value;
		var przyp = document.getElementById('filtr6').value; 
		przyp = przyp.replace(" ","+");
		var rpsite1 = document.getElementById('rpsite').value; 
		var akcja = document.getElementById('akcja').value; 
		self.location='hd_z_slownik_tresci.php?hd_rps='+rpsite1+'&sa='+sa+'&page='+pa+'&paget='+pt+'&p2='+kat+'&p3='+podkat+'&p4='+urldecode1(podkat2)+'&p6='+przyp+'&akcja='+akcja+'&refreshed=1';
	}
}
function ApplyFiltrHD_sl_1a(bool) {
	if (bool) {
		var sa = document.getElementById('showall1').value; 
		var pa = document.getElementById('page11').value; 
		var pt = document.getElementById('page12').value; 
		var kat = document.getElementById('filtr2').value; 
	//	var podkat = document.getElementById('filtr3').value; 
		var podkat = 'X';
		var podkat2 = 'X'
		var przyp = document.getElementById('filtr6').value; 
		przyp = przyp.replace(" ","+");
		var rpsite1 = document.getElementById('rpsite').value; 
		var akcja = document.getElementById('akcja').value; 
		self.location='hd_z_slownik_tresci.php?hd_rps='+rpsite1+'&sa='+sa+'&page='+pa+'&paget='+pt+'&p2='+kat+'&p3='+podkat+'&p4='+podkat2+'&p6='+przyp+'&akcja='+akcja+'&refreshed=1';
	
	}
}
function ApplyFiltrHD_sl_2(bool) {
	if (bool) {
		var sa = document.getElementById('showall1').value; 
		var pa = document.getElementById('page11').value; 
		var pt = document.getElementById('page12').value; 
		
		var kat = document.getElementById('filtr2').value; 
		var podkat = document.getElementById('filtr3').value; 
		var przyp = document.getElementById('filtr6').value; 
		przyp = przyp.replace(" ","+");
		var rpsite1 = document.getElementById('rpsite').value; 
		var akcja = document.getElementById('akcja').value; 
		self.location='hd_z_slownik_tresci.php?hd_rps='+rpsite1+'&sa='+sa+'&page='+pa+'&paget='+pt+'&p2='+kat+'&p3='+podkat+'&p6='+przyp+'&akcja=1';
	}
}
function ApplyFiltrHD_sl_3(bool) {
	if (bool) {
		var sa = document.getElementById('showall1').value; 
		var pa = document.getElementById('page11').value; 
		var pt = document.getElementById('page12').value; 
		
		var kat = document.getElementById('filtr2').value; 
		var podkat = document.getElementById('filtr3').value; 
		var przyp = document.getElementById('filtr6').value; 
		przyp = przyp.replace(" ","+");
		var rpsite1 = document.getElementById('rpsite').value; 
		var akcja = document.getElementById('akcja').value; 
		self.location='hd_z_slownik_tresci.php?hd_rps='+rpsite1+'&sa='+sa+'&page='+pa+'&paget='+pt+'&p2='+kat+'&p3='+podkat+'&p6='+przyp+'&akcja='+akcja+'';
	}
}
</script>
<?php 
}
?>
<?php
$pw = array($_f."/hd_z_slownik_tresci.php",$_f."/p_towary_dostepne.php");
if (array_search($PHP_SELF, $pw)>-1) {
?>
<script>
	function AddToList(listname, list_opis, list_value, bool1, bool2) {
		listname.options[listname.options.length] = new Option(""+list_opis+"",""+list_value+"",bool1, bool2);
	}
	
	function MakePodkategoriaList_opener(o,r) {
		var isIE = /*@cc_on!@*/false;
		
		var k_s = opener.document.getElementById('kat_id');
		var ps = opener.document.getElementById('podkat_id');
		if (!isIE) ps.options.length=0;
		var prs = opener.document.getElementById('priorytet_id');
		if (!isIE) prs.options.length=0;
		var ss = opener.document.getElementById('status_id');
		if (!isIE) ss.options.length=0;
		var swe = opener.document.getElementById('WyslijEmail');
		sweCheckbox = opener.document.getElementById('WyslijEmailCheckbox');
		swe.style.display='none';
		var sps = opener.document.getElementById('sub_podkat_id');
		if (!isIE) sps.options.length=0;
		
		if (o == "7") {
			ps.options[ps.options.length] = new Option("Konserwacja sprzętu","8",true,true);
			ps.disabled=false;
			ss.options[ss.options.length] = new Option("zamknięte","9",true,true);
			ss.disabled=false;
			AddToList(sps,'Brak','',true,true);
		}
		
		if (o == '1') {
			ps.options[0] = new Option("Brak","1");
			ps.disabled=false;			
			
			AddToList(sps,'Brak','',true,true);
			ss.options[ss.options.length] = new Option("zamknięte","9",true,true);
			ss.disabled=false;
			sweCheckbox.checked=false;
			
		}
		
		if (o == "2") {
			ps.options[ps.options.length] = new Option("Serwer","4",false,false);
			ps.options[ps.options.length] = new Option("Stacja robocza","3",true,true);
			ps.options[ps.options.length] = new Option("Urządzenia peryferyjne","9",false,false);
			ps.options[ps.options.length] = new Option("Oprogramowanie - wsparcie użytkownika","2",false,false);
			ps.options[ps.options.length] = new Option("WAN/LAN","0",false,false);
			ps.options[ps.options.length] = new Option("Oprogramowanie - problemy techniczne","7",false,false);
			ps.disabled=false;
			
			AddToList(sps,'Sprzęt','Sprzęt',true,true);
			AddToList(sps,'Oprogramowanie','Oprogramowanie',false,false);
			
			ps.disabled=false;
			prs.options[prs.options.length] = new Option("standard","2",true,true);
			prs.options[prs.options.length] = new Option("wysoki","3",false,false);	
			prs.options[prs.options.length] = new Option("krytyczny","4",false,false);	
			prs.disabled=false;	
			ss.options[ss.options.length] = new Option("nowe","1",false,false);
			ss.disabled=false;

		}
		// awaria krytyczna
		if (o == "6") {
		
			ps.options[ps.options.length] = new Option("Serwer","4",true,true);
			ps.options[ps.options.length] = new Option("Stacja robocza","3",false,false);
			ps.options[ps.options.length] = new Option("Urządzenia peryferyjne","9",false,false);
			ps.options[ps.options.length] = new Option("WAN/LAN","0",false,false);
			ps.options[ps.options.length] = new Option("Oprogramowanie - problemy techniczne","7",false,false);
	
			ps.disabled=false;
			prs.options[prs.options.length] = new Option("standard","2",true,true);
			prs.options[prs.options.length] = new Option("wysoki","3",false,false);	
			prs.options[prs.options.length] = new Option("krytyczny","4",false,false);	
			prs.disabled=false;	
			ss.options[ss.options.length] = new Option("nowe","1",false,false);
			ss.disabled=false;

			AddToList(sps,'Sprzęt','Sprzęt',true,true);
			AddToList(sps,'Oprogramowanie','Oprogramowanie',false,false);			
		}
		
		if (o == "3") {
		
			ps.options[ps.options.length] = new Option("Serwer","4",false,false);
			ps.options[ps.options.length] = new Option("Stacja robocza","3",false,false);
			ps.options[ps.options.length] = new Option("Urządzenia peryferyjne","9",false,false);
			//ps.options[ps.options.length] = new Option("Konserwacja sprzętu","8",false,false);
			//ps.options[ps.options.length] = new Option("Oprogramowanie biurowe","5",false,false);
			ps.options[ps.options.length] = new Option("Oprogramowanie - wsparcie użytkownika","2",true,true);
			ps.options[ps.options.length] = new Option("Oprogramowanie - problemy techniczne","7",false,false);
			ps.options[ps.options.length] = new Option("Aktualizacje oprogramowania","6",false,false);	
			//ps.options[ps.options.length] = new Option("Projekty","G",false,false);
			ps.options[ps.options.length] = new Option("Kopie bezpieczeństwa","H",false,false);
			ps.options[ps.options.length] = new Option("Domena","I",false,false);
			ps.options[ps.options.length] = new Option("Alarmy","E",false,false);
			ps.options[ps.options.length] = new Option("WAN/LAN","0",false,false);
			ps.options[ps.options.length] = new Option("Inne","D",false,false);
			ps.options[ps.options.length] = new Option("Otwarcie placówki pocztowej","A",false,false);
			ps.options[ps.options.length] = new Option("Przeniesienie placówki pocztowej","B",false,false);
			ps.options[ps.options.length] = new Option("Zamknięcie placówki pocztowej","C",false,false);
			ps.options[ps.options.length] = new Option("Prace administracyjno-sprawozdawcze","F",false,false);
			
			ps.disabled=false;
			prs.options[prs.options.length] = new Option("niski","1",false,false);
			prs.options[prs.options.length] = new Option("standard","2",true,true);	
			prs.options[prs.options.length] = new Option("wysoki","3",false,false);	
			prs.disabled=false;
			
			AddToList(sps,'SP2000','SP2000',true,true);
			AddToList(sps,'MBP','MBP',false,false);
			AddToList(sps,'ŁP14','ŁP14',false,false);
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
			AddToList(sps,'Inne','Inne',false,false);
			
			ss.options[ss.options.length] = new Option("nowe","1",false,false);
			//ss.options[ss.options.length] = new Option("przypisane","2",true,true);
			ss.options[ss.options.length] = new Option("rozpoczęte","3",false,false);
			ss.options[ss.options.length] = new Option("rozpoczęte - nie zakończone","7",false,false);
			ss.options[ss.options.length] = new Option("w firmie","3B",false,false);	
			//ss.options[ss.options.length] = new Option("w serwisie zewnętrznym","3A",false,false);			
			ss.options[ss.options.length] = new Option("zamknięte","9",false,false);
			ss.disabled=false;	
			sweCheckbox.checked=false;		
		}
		if (o == "4") {
			ps.options[ps.options.length] = new Option("Inne","D",false,false);
			ps.options[ps.options.length] = new Option("Otwarcie placówki pocztowej","A",false,false);
			
			ps.disabled=false;
			prs.options[prs.options.length] = new Option("standard","2",true,true);
			prs.disabled=false;
			ss.options[ss.options.length] = new Option("nowe","1",false,false);
			ss.options[ss.options.length] = new Option("przypisane","2",true,true);
			ss.options[ss.options.length] = new Option("rozpoczęte","3",false,false);
			ss.options[ss.options.length] = new Option("rozpoczęte - nie zakończone","7",false,false);		
			ss.options[ss.options.length] = new Option("zamknięte","9",false,false);
			ss.disabled=false;
			
			AddToList(sps,'Brak','',true,true);	
		}
		if (o == "5") {
			ps.options[ps.options.length] = new Option("Brak","1",true,true);
			ps.disabled=false;
			prs.options[prs.options.length] = new Option("standard","2",true,true);
			prs.disabled=false;
			ss.options[ss.options.length] = new Option("nowe","1",false,false);
			ss.options[ss.options.length] = new Option("przypisane","2",true,true);
			ss.options[ss.options.length] = new Option("rozpoczęte","3",false,false);
			ss.options[ss.options.length] = new Option("rozpoczęte - nie zakończone","7",false,false);		
			ss.options[ss.options.length] = new Option("zamknięte","9",false,false);
			ss.disabled=false;	
			
			AddToList(sps,'Brak','',true,true);			
			// jezeli kategoria to "Prace na potrzeby Postdata" -> odznacz widocznosc zgloszenia dla Poczty
			opener.document.getElementById('czy_synchronizowac').checked=false;
		}
		opener.document.getElementById('czy_synchronizowac').checked=true;		
		opener.document.getElementById('podkat_id').value = r;
	}
function ex1(val) {
	var text = val.value.replace(/\s+$/g,"") 
	var split = text.split("\n") 
	if (split.length<5) val.rows = split.length+1;
}
function KopiujDo1Entera_sl(val, dest) {
	var w1 = val.replace("\n","<br>");
	var s1 = w1.search("<br>");
	if (s1>0) {
		opener.document.getElementById(dest).value=w1.substring(0,s1);
		return true;
	}
	opener.document.getElementById(dest).value=w1;
}
function urldecode(str) {
	str = str.replace('+', ' ');
	str = unescape(str);
	str = str.replace('+', ' ');
	return str;
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
var date = new Date();
    date.setTime(date.getTime()-(10*24*60*60*1000));
    document.cookie = name+"=; expires="+date.toGMTString();
}
function str_replace (search, replace, subject, count) {
    // *     example 1: str_replace(' ', '.', 'Kevin van Zonneveld');
    // *     returns 1: 'Kevin.van.Zonneveld'
    var i = 0, j = 0, temp = '', repl = '', sl = 0, fl = 0,
            f = [].concat(search),
            r = [].concat(replace),
            s = subject,
            ra = r instanceof Array, sa = s instanceof Array;
    s = [].concat(s);
    if (count) {
        this.window[count] = 0;
    }
    for (i=0, sl=s.length; i < sl; i++) {
        if (s[i] === '') {
            continue;
        }
        for (j=0, fl=f.length; j < fl; j++) {
            temp = s[i]+'';
            repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0];
            s[i] = (temp).split(f[j]).join(repl);
            if (count && s[i] !== temp) {
                this.window[count] += (temp.length-s[i].length)/f[j].length;}
        }
    }
    return sa ? s : s[0];
}
function WybierzTresc(tresc,K,P,PP2) {
var isIE = /*@cc_on!@*/false;

	opener.document.getElementById('hd_tresc').value = str_replace('<br />','\n',tresc);
	KopiujDo1Entera_sl(opener.document.getElementById('hd_tresc').value,'hd_temat'); 
	ex1(opener.document.getElementById('hd_tresc'));
	
	if (isIE) {
		alert('Kategoria i podkategoria zgłoszenia nie została zmodyfikowana zgodnie z treścią ze słownika (problem dotyczy tylko przeglądarki IE)');
	} else {
		
		if (confirm('Czy zmienić kategorię, podkategorię oraz podkategorię (poziom 2) zgłoszenia zgodnie z zapisaną w treści ze słownika ?')) {
			
			<?php if ($_REQUEST[p2]!='X') { ?>
				opener.document.getElementById('kat_id').value = '<?php echo $_REQUEST[p2];?>';
			<?php } else { ?>
				opener.document.getElementById('kat_id').value = K;
			<?php } ?>
			
			<?php if (($_REQUEST[p2]!='X') && ($_REQUEST[p2]!='1')) { ?>
				<?php if (($_REQUEST[p3]!='X')) { ?>
					MakePodkategoriaList_opener('<?php echo $_REQUEST[p2]; ?>','<?php echo $_REQUEST[p3]; ?>');
				<?php } else { ?>
					MakePodkategoriaList_opener('<?php echo $_REQUEST[p2]; ?>',P);
				<?php } ?>
			<?php } ?>
			
			<?php if (($_REQUEST[p2]=='X') && ($_REQUEST[p2]!='1')) { ?>
				<?php if (($_REQUEST[p3]=='X')) { ?>
					MakePodkategoriaList_opener(K,P);
				<?php } else { ?>
					MakePodkategoriaList_opener(K,'<?php echo $_REQUEST[p3]; ?>');
				<?php } ?>			
			<?php } ?>
			
			<?php if ($_REQUEST[p2]=='1') { ?>
				MakePodkategoriaList_opener('1','1');
			<?php } ?>		
			
			GenerateSubPodkategoriaList(K,P);
			
			<?php if (($_REQUEST[p4]!='X') && ($_REQUEST[p4]!='')) { ?>				
				opener.document.getElementById('sub_podkat_id').value = ''+urldecode(PP2)+'';
			<?php } ?>
		}
		
	}
	
	self.close();
	opener.document.getElementById('hd_tresc').focus();
}
</script>
<?php } ?>
<?php
$pw = array($_f."/hd_e_zgloszenie.php",$_f."/hd_e_zgloszenie_new.php");
if (array_search($PHP_SELF, $pw)>-1) { ?>
<script>
function DopiszDwukropek(v) {
	var r = document.getElementById(v).value.length;
	if (r==2) document.getElementById(v).value = document.getElementById(v).value+':';
	if (r==5) CheckTime(document.getElementById(v).value);
}
function DopiszKreski(v) {
	var r = document.getElementById(v).value.length;
	if (r==4) document.getElementById(v).value = document.getElementById(v).value+'-';
	if (r==7) document.getElementById(v).value = document.getElementById(v).value+'-';
}
function filterInput(filterType,evt,allowDecimal,allowCustom){var keyCode,Char,inputField,filter='';var alpha='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';var num='0123456789';if(window.event){keyCode=window.event.keyCode;evt=window.event}else if(evt)keyCode=evt.which;else return true;if(filterType==0)filter=alpha;else if(filterType==1)filter=num;else if(filterType==2)filter=alpha+num;if(allowCustom)filter+=allowCustom;if(filter=='')return true;inputField=evt.srcElement?evt.srcElement:evt.target||evt.currentTarget;if((keyCode==null)||(keyCode==0)||(keyCode==8)||(keyCode==9)||(keyCode==27))return true;Char=String.fromCharCode(keyCode);if((filterType==1)&&(String.fromCharCode(keyCode)==',')){Char='.'}if((filter.indexOf(Char)>-1))return true;else if(filterType==1&&allowDecimal&&(Char=='.')&&inputField.value.indexOf('.')==-1)return true;else return false}
function KopiujDo1Entera(val, dest) {
	var w1 = val.replace("\n","<br>");
	var s1 = w1.search("<br>");
	if (s1>0) {
		document.getElementById(dest).value=w1.substring(0,s1);
		return true;
	}
	document.getElementById(dest).value=w1;
}
</script>
<?php } ?>
<?php $pw = array($_f."/hd_d_osoba_zglaszajaca.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
<script>
function trimAll(sString) {
	while (sString.substring(0,1) == ' ') { sString = sString.substring(1, sString.length); }
	while (sString.substring(sString.length-1, sString.length) == ' ') { sString = sString.substring(0,sString.length-1); }
	return sString;
} 
function cUpper(cObj) { cObj.value=trimAll(cObj.value.toUpperCase()); }
</script>
<?php } ?>
<?php $pw = array($_f."/hd_g_delegacje.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
<SCRIPT language=JavaScript>
function urlencode1 (str) {
    str = (str+'').toString();
    return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+');
}
function reload_osoba(form) {
if (document.getElementById('Okres')) {
	var miesiac = document.getElementById('Okres').value;
} else {
	var miesiac = '';
}
var tosoba=document.getElementById('Osoba').options[document.getElementById('Osoba').options.selectedIndex].value;
self.location='hd_g_delegacje.php?osoba='+urlencode1(tosoba)+'&okres='+miesiac;
}
</script>
<?php } ?>
<?php 
$pw = array($_f."/e_protokol_z_ewidencji.php",$_f."/utworz_protokol.php",$_f."/z_naprawy_uszkodzony.php");
if (array_search($PHP_SELF, $pw)>-1) { 
?>
<SCRIPT language=JavaScript>
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
function urlencode1 (str) {
    str = (str+'').toString();
    return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+');
}
function nl2br(text) {
	text = escape(text);
	if (text.indexOf('%0D%0A') > -1) {
		re_nlchar = /%0D%0A/g;
	} else if (text.indexOf('%0A')> -1 ) {
		re_nlchar = /%0A/g;
	} else if (text.indexOf('%0D')> -1 ) {
		re_nlchar = /%0D/g;
	}
	return unescape(text.replace(re_nlchar,'<br />'));
}
function br2nl(text) {
	var i = 0;
	var a = '<br />';
	var b = '\n';
	while (i!=-1) {
		i=text.indexOf(a,i);
		if (i>=0) {
			text = text.substring(0,i)+b+text.substring(i+a.length);
			i+=b.length;
		}
	} 
	return text;
}
</script>
<?php } ?>
<?php $pw = array($_f."/main.php"); if ((array_search($PHP_SELF, $pw)>-1) && ($_REQUEST[action]!='')) { ?>
<script type="text/javascript" src="js/calendar.js"></script>
<?php } ?>
<?php $pw = array($_f."/hd_dodaj_dostep_czasowy.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
<script>
function selectAllList() {
var aSelect = document.add.wybrane_dni;
var aSelectLen = aSelect.length;
	for (i = 0; i < aSelectLen; i++) {
		aSelect.options[i].selected = true;
	}
}
function AddDateToList(v,nowdate) {
	if (v.value>nowdate) {
		alert('Nie można dać dostępu do dnia w przyszłości');
		return true;
	}
	var elLength = document.add.wybrane_dni.length;
	var elLength_aktywne = document.add.wybrane_dni_aktywne.length;
	var juzbyl = 0;
	var juzjest = 0;
// sprawdź czy już nie dodano tego dnia
	for (i=0; i<elLength; i++) { if (document.add.wybrane_dni.options[i].value==v.value) { juzjest=1; } }
	
// sprawdź czy już nie dodano tego dnia wcześniej
	for (i=0; i<elLength_aktywne; i++) { if (document.add.wybrane_dni_aktywne.options[i].value==v.value) { juzbyl=1; } }
	if (juzbyl==1) {
		alert('Wybrana data '+v.value+' była dodana wcześniej do listy dostępu czasowego dla tego pracownika');	
	} else {
		if (juzjest==0) {	
			document.add.wybrane_dni.options[document.add.wybrane_dni.options.length] = new Option(v.value,v.value,false,false);
		} else alert('Wybrana data '+v.value+' jest już na liście');
	}	
	
	elLength = document.add.wybrane_dni.length;
	if (elLength==0) { document.getElementById('usun').style.display='none'; } else { document.getElementById('usun').style.display='';}
	}
	function UpdateDateToList() {
	var elLength = document.add.wybrane_dni.length;
	if (elLength==0) { 
		document.getElementById('usun').style.display='none'; 
		document.getElementById('czysc').style.display='none';
		document.getElementById('zapisz').style.display='none'; 
	} else { 
		document.getElementById('usun').style.display='';
		document.getElementById('czysc').style.display='';
		document.getElementById('zapisz').style.display=''; 
	}
}
function ClearDateToList() {
	if (confirm('Czy na pewno chcesz wyczyścić listę z datami ?')) { 	
		document.getElementById('wybrane_dni').length=0; 
		UpdateDateToList(); 
	}
}
function DeleteDateFromList() {
	if (document.add.wybrane_dni.options.selectedIndex>-1) {  
		if (confirm('Czy na pewno chcesz usunąć datę '+document.add.wybrane_dni.options[document.add.wybrane_dni.options.selectedIndex].value+' z listy ?')) { 	
			document.add.wybrane_dni.options[document.add.wybrane_dni.options.selectedIndex]=null; 
		}
	} else { 
		if (document.add.wybrane_dni.length>0) { 
			alert('Zaznacz pozycję do usunięcia'); 
		} else { 
alert('Lista dat jest pusta'); 
			} 
		}
	UpdateDateToList(); 
}
</script>
<?php } ?>
<?php $pw = array($_f."/hd_z_slownik_tresci_zs.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
<script>
function ex1(val) {
	var text = val.value.replace(/\s+$/g,"") 
	var split = text.split("\n") 
	if (split.length<5) val.rows = split.length+1;
}
function KopiujDo1Entera_sl(val, dest) {
	var w1 = val.replace("\n","<br>");
	var s1 = w1.search("<br>");
	if (s1>0) {
		opener.document.getElementById(dest).value=w1.substring(0,s1);
		return true;
	}
	opener.document.getElementById(dest).value=w1;
}
function urldecode(str) {
	str = str.replace('+', ' ');
	str = unescape(str);
	str = str.replace('+', ' ');
	return str;
}
function urldecode1(str) {
	str = str.replace(' ', '+');
	str = unescape(str);
	str = str.replace(' ', '+');
	return str;
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
var date = new Date();
    date.setTime(date.getTime()-(10*24*60*60*1000));
    document.cookie = name+"=; expires="+date.toGMTString();
}
function str_replace (search, replace, subject, count) {
    // *     example 1: str_replace(' ', '.', 'Kevin van Zonneveld');
    // *     returns 1: 'Kevin.van.Zonneveld'
    var i = 0, j = 0, temp = '', repl = '', sl = 0, fl = 0,
            f = [].concat(search),
            r = [].concat(replace),
            s = subject,
            ra = r instanceof Array, sa = s instanceof Array;
    s = [].concat(s);
    if (count) {
        this.window[count] = 0;
    }
    for (i=0, sl=s.length; i < sl; i++) {
        if (s[i] === '') {
            continue;
        }
        for (j=0, fl=f.length; j < fl; j++) {
            temp = s[i]+'';
            repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0];
            s[i] = (temp).split(f[j]).join(repl);
            if (count && s[i] !== temp) {
                this.window[count] += (temp.length-s[i].length)/f[j].length;}
        }
    }
    return sa ? s : s[0];
}
function WybierzTrescZS(tresc) {
	opener.document.getElementById('zs_wcz').value = str_replace('<br />','\n',tresc);
	opener.document.getElementById('zs_wcz').focus();
	self.close();
}
function ApplyFiltrHD_sl_1a(bool) {
	if (bool) {
		var sa = document.getElementById('showall1').value; 
		var pa = document.getElementById('page11').value; 
		var pt = document.getElementById('page12').value; 
		var kat = document.getElementById('filtr2').value; 
		var podkat = document.getElementById('filtr3').value; 
		var podkat2 = document.getElementById('filtr4').value;
		var przyp = document.getElementById('filtr6').value; 
		przyp = przyp.replace(" ","+");
		var rpsite1 = document.getElementById('rpsite').value; 
		var akcja = document.getElementById('akcja').value; 
		self.location='hd_z_slownik_tresci_zs.php?hd_rps='+rpsite1+'&sa='+sa+'&page='+pa+'&paget='+pt+'&p2='+kat+'&p3='+podkat+'&p4='+urldecode1(podkat2)+'&p6='+przyp+'&akcja='+akcja+'&refreshed=1';
	}
}
function ApplyFiltrHD_sl_2(bool) {
	if (bool) {
		var sa = document.getElementById('showall1').value; 
		var pa = document.getElementById('page11').value; 
		var pt = document.getElementById('page12').value; 
		
		var kat = document.getElementById('filtr2').value; 
		var podkat = document.getElementById('filtr3').value; 
		var przyp = document.getElementById('filtr6').value; 
		przyp = przyp.replace(" ","+");
		var rpsite1 = document.getElementById('rpsite').value; 
		var akcja = document.getElementById('akcja').value; 
		self.location='hd_z_slownik_tresci_zs.php?hd_rps='+rpsite1+'&sa='+sa+'&page='+pa+'&paget='+pt+'&p2='+kat+'&p3='+podkat+'&p6='+przyp+'&akcja=1';
	}
}
function ApplyFiltrHD_sl_3(bool) {
	if (bool) {
		var sa = document.getElementById('showall1').value; 
		var pa = document.getElementById('page11').value; 
		var pt = document.getElementById('page12').value; 
		
		var kat = document.getElementById('filtr2').value; 
		var podkat = document.getElementById('filtr3').value; 
		var przyp = document.getElementById('filtr6').value; 
		przyp = przyp.replace(" ","+");
		var rpsite1 = document.getElementById('rpsite').value; 
		var akcja = document.getElementById('akcja').value; 
		self.location='hd_z_slownik_tresci_zs.php?hd_rps='+rpsite1+'&sa='+sa+'&page='+pa+'&paget='+pt+'&p2='+kat+'&p3='+podkat+'&p6='+przyp+'&akcja=manage';
	}
}
</script>
<?php } ?>
<?php $pw = array($_f."/hd_d_slownik_tresc_zs.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
<script>
function MakePodkategoriaList_sl2(o,r) {
	if (document.getElementById('sl_kat_id')) {
		var k_s = document.getElementById('sl_kat_id');
	} 
		
	if (document.getElementById('sl_podkat_id')) {
		var p_s = document.getElementById('sl_podkat_id');
		p_s.options.length=0;
	} 
	
	if (document.getElementById('sl_sub_podkat_id')) {
		var p2_s = document.getElementById('sl_sub_podkat_id');
		p2_s.options.length=0;
	}
	
	// konserwacje
	if (o == "7") {
		p_s.options[p_s.options.length] = new Option("Konserwacja sprzętu","8",true,true);
		p_s.disabled=false;
		AddToList(p2_s,'Brak','',true,true);
	}

	// konsultacje 
	if (o == "1") {
		p_s.options[p_s.options.length] = new Option("Brak","1",true,true);
		p_s.disabled=false;
		AddToList(p2_s,'Brak','',true,true);
	}		
		
	if (o == "2") {
		p_s.options[p_s.options.length] = new Option("Serwer","4",false,false);
		p_s.options[p_s.options.length] = new Option("Stacja robocza","3",true,true);
		p_s.options[p_s.options.length] = new Option("Urządzenia peryferyjne","9",false,false);
		p_s.options[p_s.options.length] = new Option("Oprogramowanie - wsparcie użytkownika","2",false,false);
		p_s.options[p_s.options.length] = new Option("Oprogramowanie - problemy techniczne","7",false,false);
		p_s.options[p_s.options.length] = new Option("WAN/LAN","0",false,false);
		p_s.disabled=false;
	
		AddToList(p2_s,'Sprzęt','Sprzęt',true,true);
		AddToList(p2_s,'Oprogramowanie','Oprogramowanie',false,false);
	}

	if (o == "6") {	
		p_s.options[p_s.options.length] = new Option("Serwer","4",true,true);
		p_s.options[p_s.options.length] = new Option("Stacja robocza","3",false,false);
		p_s.options[p_s.options.length] = new Option("Urządzenia peryferyjne","9",false,false);
		p_s.options[p_s.options.length] = new Option("WAN/LAN","0",false,false);
		p_s.options[p_s.options.length] = new Option("Oprogramowanie - problemy techniczne","7",false,false);
		p_s.disabled=false;
		
		AddToList(p2_s,'Sprzęt','Sprzęt',true,true);
		AddToList(p2_s,'Oprogramowanie','Oprogramowanie',false,false);			
	}
	
	
	if (o == "3") {
		p_s.options[p_s.options.length] = new Option("Serwer","4",false,false);
		p_s.options[p_s.options.length] = new Option("Stacja robocza","3",false,false);
		p_s.options[p_s.options.length] = new Option("Urządzenia peryferyjne","9",false,false);
		p_s.options[p_s.options.length] = new Option("Oprogramowanie - wsparcie użytkownika","2",true,true);
		p_s.options[p_s.options.length] = new Option("Oprogramowanie - problemy techniczne","7",false,false);
		p_s.options[p_s.options.length] = new Option("Aktualizacje oprogramowania","6",false,false);
		p_s.options[p_s.options.length] = new Option("Kopie bezpieczeństwa","H",false,false);
		p_s.options[p_s.options.length] = new Option("Domena","I",false,false);
		p_s.options[p_s.options.length] = new Option("Alarmy","E",false,false);
		p_s.options[p_s.options.length] = new Option("WAN/LAN","0",false,false);
		p_s.options[p_s.options.length] = new Option("Inne","D",false,false);
		p_s.options[p_s.options.length] = new Option("Otwarcie placówki pocztowej","A",false,false);
		p_s.options[p_s.options.length] = new Option("Przeniesienie placówki pocztowej","B",false,false);
		p_s.options[p_s.options.length] = new Option("Zamknięcie placówki pocztowej","C",false,false);
		p_s.options[p_s.options.length] = new Option("Prace administracyjno-sprawozdawcze","F",false,false);
		p_s.disabled=false;
		
		AddToList(p2_s,'SP2000','SP2000',true,true);
		AddToList(p2_s,'MBP','MBP',false,false);
		AddToList(p2_s,'ŁP14','ŁP14',false,false);
		AddToList(p2_s,'MRU','MRU',false,false);
		AddToList(p2_s,'SEDI','SEDI',false,false);
		AddToList(p2_s,'MRUm','MRUm',false,false);
		AddToList(p2_s,'ZST WiemPost','ZST WiemPost',false,false);
		AddToList(p2_s,'Reklamacje','Reklamacje',false,false);
		AddToList(p2_s,'Lotus Notes','Lotus Notes',false,false);
		AddToList(p2_s,'Office','Office',false,false);
		AddToList(p2_s,'Przeglądarka internetowa','Przeglądarka internetowa',false,false);
		AddToList(p2_s,'Outlook Express','Outlook Express',false,false);
		AddToList(p2_s,'S.N.O.S.P.','S.N.O.S.P.',false,false);
		AddToList(p2_s,'PenGuard','PenGuard',false,false);			
		AddToList(p2_s,'Acrobat Reader','Acrobat Reader',false,false);
		AddToList(p2_s,'Obsługa systemu operacyjnego','Obsługa systemu operacyjnego',false,false);
		AddToList(p2_s,'Inne','Inne',false,false);		
	}
	
	
	if (o == "4") {
		p_s.options[p_s.options.length] = new Option("Inne","D",false,false);
		p_s.options[p_s.options.length] = new Option("Otwarcie placówki pocztowej","A",false,false);
		p_s.disabled=false;	
		AddToList(p2_s,'Brak','',true,true);
	}
	if (o == "5") {
		p_s.options[p_s.options.length] = new Option("Brak","1",true,true);
		p_s.disabled=false;	
		AddToList(p2_s,'Brak','',true,true);
	}
}
</script>
<?php } ?>
<?php
$pw = array($_f."/hd_spojnosc_zgloszen_ze_statystyka.php",$_f."/hd_spojnosc_run_sql.php");
if (array_search($PHP_SELF, $pw)>-1) {
?>
<script>
function copyToClipBoard(sContents) {
	window.clipboardData.setData("Text", sContents);
	alert("Zawartość pola została skopiowana do schowka.\t");
	return false;
}
</script>
<?php } ?>
<?php $pw = array($_f."/hd_g_delegacje_pieczatki.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
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
</script>
<?php } ?>
<?php $pw = array($_f."/d_magazyn.php",$_f."/utworz_protokol.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
<script>
function _onSubmit(message,xx) {
<?php if ($weryfikacja_dat_protokolow==TRUE) { ?>
	var dp = document.getElementById('pdata').value;
	var part = dp.split('-');
	<?php
		$result_sr = mysql_query("SELECT sr_rok, sr_miesiac FROM $dbname.serwis_sprzedaz_raport WHERE belongs_to=$es_filia ORDER BY sr_id DESC LIMIT 1",$conn) or die($k_b);
		if (mysql_num_rows($result_sr)>0) list ($sr_r,$sr_m)=mysql_fetch_array($result_sr);
		if ($sr_m<10) $sr_m = '0'.$sr_m;
	?>
	var dp_check = '<?php echo "$sr_r"; ?>-<?php echo $sr_m; ?>';
	var part_check = dp_check.split('-');
	
	if ((parseInt(part_check[0]))>(parseInt(part[0]))) {
		alert('Sprzedaż na miesiąc '+part_check[0]+'-'+part_check[1]+' została zamknięta.\n\r\n\rGenerowanie protokołu na wcześniejszą datę '+part[0]+'-'+part[1]+' jest niemożliwe. \n\r\n\rSkontaktuj się z kierownikiem filii lub wykonaj sprzedaż na nastepny miesiąc.');
		document.getElementById('pdata').focus();
		return false;
	}
	if (((parseInt(part[0]))==(parseInt(part_check[0]))) && ((parseInt(part[1]))>=(parseInt(part_check[1])))) {
		alert('Sprzedaż na miesiąc '+part_check[0]+'-'+part_check[1]+' została zamknięta.\n\r\n\rSkontaktuj się z kierownikiem filii lub wykonaj sprzedaż na nastepny miesiąc.');
		document.getElementById('pdata').focus();
		return false;
	}
<?php } ?>
	if ((document.getElementById('HD_nr_zgloszenia')) && (document.getElementById('HD_nr_zgloszenia').value=='')) {
		alert('Musisz podać numer zgłoszenia Helpdesk');
		document.getElementById('HD_nr_zgloszenia').focus();
		return false;
	}
	var c_c = 0;
	if (document.getElementById('c_1').checked==true) c_c=c_c+1;
	if (document.getElementById('c_2').checked==true) c_c=c_c+1;
	if (document.getElementById('c_3').checked==true) c_c=c_c+1;
	if (document.getElementById('c_4').checked==true) c_c=c_c+1;
	if (document.getElementById('c_5').checked==true) c_c=c_c+1;
	if (document.getElementById('c_6').checked==true) c_c=c_c+1;
	if (document.getElementById('c_7').checked==true) c_c=c_c+1;
	if (document.getElementById('c_8').checked==true) c_c=c_c+1;
	if (xx==0) c_c=1;
	if (c_c>0) {
		if (confirm(message)) { 
			if (document.getElementById('submit')!=null) document.getElementById('submit').style.display='none';		
			if (document.getElementById('reset')!=null)	document.getElementById('reset').style.display='none';
			if (document.getElementById('anuluj')!=null) document.getElementById('anuluj').style.display='none';
			if (document.getElementById('back')!=null) document.getElementById('back').style.display='none';
			document.getElementById('Saving').style.display='';
			return true; 
		} else return false; 
	} else {
		alert('Musisz zaznaczyć przynajmniej jedną czynność');
		return false;
	}
}
</script>
<?php } ?>
<?php $pw = array($_f."/main_simple.php"); if (array_search($PHP_SELF, $pw)<0) { ?>
<script>
function _onSubmitDefault(message) {
<?php if ($potwierdzaj_submit==1) { ?>
if (confirm(message)) { 
	if (document.getElementById('submit')!=null) document.getElementById('submit').style.display='none';		
	if (document.getElementById('reset')!=null)	document.getElementById('reset').style.display='none';
	if (document.getElementById('anuluj')!=null) document.getElementById('anuluj').style.display='none';
	if (document.getElementById('back')!=null) document.getElementById('back').style.display='none';
	document.getElementById('Saving').style.display='';
	return true; 
} else return false; 
<?php } else { ?>
	return true; 
<?php } ?>
}
</script>
<?php } ?>
<?php
$pw = array($_f."/e_faktura_pozycja.php");
if (array_search($PHP_SELF, $pw)>-1) {
?>
<script>
function SprawdzCeneOdsprzedazy(cnetto1, cpopr1, codsp1) {
if (codsp1=='') return false;
document.getElementById('warning').style.display='none';
document.getElementById('warning1').style.display='none';
document.getElementById('warning2').style.display='none';
cnetto=cnetto1*1;
cpopr=cpopr1*1;
codsp=codsp1*1;
document.getElementById('submit').style.display='';
	if (codsp>cpopr) { 
		document.getElementById('warning').style.display=''; 
		document.getElementById('submit').style.display='none';
		this.focus();
	} else { 
		//document.getElementById('warning').style.display='none';
		//document.getElementById('submit').style.display='none';
	}
	
	if (codsp<cnetto) { 
		document.getElementById('warning1').style.display=''; this.focus(); 
		document.getElementById('submit').style.display='none';
	} else { 
		//document.getElementById('warning1').style.display='none'; 
		//document.getElementById('submit').style.display='';
	} 
	
	//var procent = Math.round(((codsp/cnetto)*100)-100);
	if ((codsp>=cnetto) && (codsp<cpopr)) {
		document.getElementById('warning2').style.display=''; this.focus(); 
		document.getElementById('submit').style.display='';	
		//document.getElementById('proc').value= procent;
	}	
	return false;
}
</script>
<?php } ?>
<?php
$pw = array($_f."/d_faktura_pozycja.php", $_f."/e_faktura_pozycja.php", $_f."/d_podfaktura.php", $_f."/d_faktura.php",$_f."/e_faktura.php");
if (array_search($PHP_SELF, $pw)>-1) {
?>
<script>
function Przecinek_na_kropke(strSource) {
	var newString = strSource.value.split(',');
	newString = newString.join('.');
	strSource.value = newString;
}
function filterInput(filterType,evt,allowDecimal,allowCustom){var keyCode,Char,inputField,filter='';var alpha='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';var num='0123456789';if(window.event){keyCode=window.event.keyCode;evt=window.event}else if(evt)keyCode=evt.which;else return true;if(filterType==0)filter=alpha;else if(filterType==1)filter=num;else if(filterType==2)filter=alpha+num;if(allowCustom)filter+=allowCustom;if(filter=='')return true;inputField=evt.srcElement?evt.srcElement:evt.target||evt.currentTarget;if((keyCode==null)||(keyCode==0)||(keyCode==8)||(keyCode==9)||(keyCode==27))return true;Char=String.fromCharCode(keyCode);if((filterType==1)&&(String.fromCharCode(keyCode)==',')){Char='.'}if((filter.indexOf(Char)>-1))return true;else if(filterType==1&&allowDecimal&&(Char=='.')&&inputField.value.indexOf('.')==-1)return true;else return false}
function WeryfikujKwotyNettoBruttoFaktury(t) {
	if (t=='n') {
		var kb = Math.round(document.getElementById('knettof').value*document.getElementById('vat').value*100)/100;
		if ((document.getElementById('knettof').value!='') && (document.getElementById('knettof').value!='0')) {
			if (confirm('Czy automatycznie wpisać kwotę brutto faktury: '+kb+' zł ?')) {
document.getElementById('kbruttof').value = kb;
			} else document.getElementById('kbruttof').focus();
		} else { alert('Nie wpisałeś kwoty netto faktury'); document.getElementById('knettof').focus(); }
	} else {
		var kn = Math.round((document.getElementById('kbruttof').value/document.getElementById('vat').value)*100)/100;
		if ((document.getElementById('kbruttof').value!='') && (document.getElementById('kbruttof').value!='0')) {
			if (confirm('Czy automatycznie wpisać kwotę netto faktury: '+kn+' zł ?')) {
document.getElementById('knettof').value = kn;
			} else document.getElementById('knettof').focus();
		} else { alert('Nie wpisałeś kwoty brutto faktury'); document.getElementById('kbruttof').focus(); }
	}
	
}
function pytanie_d_faktura() {
var firmasel = document.getElementById('wybranafz').value;
var kn = document.getElementById('knettof').value;
var kb = document.getElementById('kbruttof').value;
var wyl_br = Math.round(document.getElementById('knettof').value*document.getElementById('vat').value*100)/100;
var roznica = Math.round((wyl_br - kb)*100)/100;
if (firmasel=='') {
	alert('Nie wybrałeś firmy wystawiającej fakturę');
	document.getElementById('wybranafz').focus();
	return false;
}
if (kn=='') {
	alert('Nie wpisałeś kwoty netto faktury');
	document.getElementById('knettof').focus();
	return false;
}
if (kb=='') {
	alert('Nie wpisałeś kwoty brutto faktury');
	document.getElementById('kbruttof').focus();
	return false;
}
if (Math.abs(Math.round(roznica*100))==1) {
	
	if (confirm('Różnica między wyliczoną kwotą brutto, a podaną wynosi: 1 grosz. \r\n\r\nCzy wpisać porawną kwotę brutto faktury ? '+wyl_br+'zł')) {
		document.getElementById('kbruttof').value = wyl_br;
		//return false;
	}// else return false;
	
} else {
	if (roznica<0) {
		if (confirm('Kwota brutto jest nieprawidłowa (za wysoka o '+Math.abs(roznica)+'zł)\r\n\r\nCzy wpisać porawną kwotę brutto faktury ? '+wyl_br+'zł')) {
			document.getElementById('kbruttof').value = wyl_br;
			//return false;
		} else {
			return false;
		}
	}
	if (roznica>0) {
		if (confirm('Kwota brutto jest nieprawidłowa (za niska o '+Math.abs(roznica)+'zł)\r\n\r\nCzy wpisać porawną kwotę brutto faktury ? '+wyl_br+'zł')) {
			document.getElementById('kbruttof').value = wyl_br;
			//return false;
		} else {
			return false;
		}
	}
}
if (confirm('Czy akceptujesz wprowadzone dane ?')) {
		return true;
	} else {
		return false;
	}
}
function ZmienMarze_E(x) { 
	document.getElementById('marza_dla_kategorii').value=document.getElementById('marza').value;
	document.getElementById('pcenaodsp').value=((Math.round((document.getElementById('pcenanetto').value*document.getElementById('marza_dla_kategorii').value)*100)/100)); 
}
</script>
<?php } ?>
<?php $pw = array($_f."/e_zestaw_obrot.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
<SCRIPT language=JavaScript>
function urlencode1 (str) {
    str = (str+'').toString();
    return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+');
}
function reload1_obrot(form) {
	if (document.getElementById('readonly').value=='1') {
		var tup=form.new_upid.value;
	} else {
		var tup=form.new_upid.options[form.new_upid.options.selectedIndex].value;
	}
	var tdata=form.tdata.value;
	var tid=form.zid.value;
	var tuwagi=form.tuwagi.value;
	var trodzaj = 'Towar';
	var dwp = form.dodajwymianepodzespolow.value;
	if (document.getElementById('_wp_opis')) { var wp1_o = document.getElementById('_wp_opis').value; } else { var wp1_o = ''; }
	if (document.getElementById('_wp_sn')) { var wp1_sn = document.getElementById('_wp_sn').value; } else { var wp1_sn = ''; }
	if (document.getElementById('_wp_ni')) { var wp1_ni = document.getElementById('_wp_ni').value; } else { var wp1_ni = ''; }
	if (document.getElementById('jedna_umowa')==null) {
		var tumowa1=0;
	} else {
		if (document.getElementById('jedna_umowa').value==0) {
		var tumowa1=form.tumowa.options[form.tumowa.options.selectedIndex].value;
		} else {
		var tumowa1=form.tumowa.value;
		}
	}
	self.location='e_zestaw_obrot.php?zid='+tid+'&new_upid=' + urlencode1(tup) + '&tdata=' + tdata + '&trodzaj=' +urlencode1(trodzaj)+'&tuwagi='+urlencode1(tuwagi)+'&tumowa='+urlencode1(tumowa1)+'&dodajwymianepodzespolow='+dwp+'&_wp_opis='+urlencode1(wp1_o)+'&_wp_sn='+urlencode1(wp1_sn)+'&_wp_ni='+urlencode1(wp1_ni);
}
</script>
<?php } ?>
<?php $pw = array($_f."/hd_e_zgloszenie_new.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
<?php 
	include_once('js/hd_d_zgloszenie_scripts.php');
	include_once('js/hd_d_zgloszenie_i_s_scripts.php');
?>
<script>

function MakePodkategoriaList_edit(o) {
	var k_s = document.getElementById('kat_id');
	var p_s = document.getElementById('podkat_id');
		p_s.options.length=0;
	var SpanWyslijEmail = document.getElementById('WyslijEmail');
		SpanWyslijEmailCheckbox = document.getElementById('WyslijEmailCheckbox');
	if (SpanWyslijEmail) SpanWyslijEmail.style.display='none';
	
	if ((o == "2") || (o == "6")) {
		p_s.options[p_s.options.length] = new Option("Oprogramowanie biurowe","5",false,false);
		p_s.options[p_s.options.length] = new Option("Oprogramowanie - placówka pocztowa","2",true,true);
		p_s.options[p_s.options.length] = new Option("Serwer","4",false,false);
		p_s.options[p_s.options.length] = new Option("Stacja robocza","3",true,true);
		p_s.options[p_s.options.length] = new Option("Urządzenia peryferyjne","9",false,false);
		p_s.options[p_s.options.length] = new Option("WAN/LAN","0",false,false);		
		p_s.disabled=false;	
		if (SpanWyslijEmail) SpanWyslijEmail.style.display='';
		if (SpanWyslijEmailCheckbox) SpanWyslijEmailCheckbox.checked=true;	
	}
	if (o == "3") {		
		p_s.options[p_s.options.length] = new Option("Aktualizacja oprogramowania","6",false,false);
		p_s.options[p_s.options.length] = new Option("Alarmy","E",false,false);
		p_s.options[p_s.options.length] = new Option("Inne","D",false,false);
		p_s.options[p_s.options.length] = new Option("Konserwacja sprzętu","8",false,false);
		p_s.options[p_s.options.length] = new Option("Oprogramowanie biurowe","5",false,false);
		p_s.options[p_s.options.length] = new Option("Oprogramowanie - placówka pocztowa","2",false,false);
		p_s.options[p_s.options.length] = new Option("Oprogramowanie techniczne","7",false,false);
		p_s.options[p_s.options.length] = new Option("Otwarcie placówki pocztowej","A",false,false);
		p_s.options[p_s.options.length] = new Option("Przeniesienie placówki pocztowej","B",false,false);
		p_s.options[p_s.options.length] = new Option("Serwer","4",false,false);
		p_s.options[p_s.options.length] = new Option("Stacja robocza","3",false,false);
		p_s.options[p_s.options.length] = new Option("Urządzenia peryferyjne","9",false,false);
		p_s.options[p_s.options.length] = new Option("WAN/LAN","0",false,false);
		p_s.options[p_s.options.length] = new Option("Zamknięcie placówki pocztowej","C",false,false);		
		
		p_s.disabled=false;
		if (SpanWyslijEmailCheckbox) SpanWyslijEmailCheckbox.checked=false;		
	}
}
</script>
<?php } ?>
<?php $pw = array($_f."/z_komorka.php",$_f."/hd_check_ping.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
<?php if ($ClueTipOn==1) { ?>
<script type="text/javascript" src="js/jquery/jquery-1.6.min.js"></script>
<script type="text/javascript" src="js/jquery/jquery.cluetip_min.js"></script>
<?php } ?>
<?php } ?>
<?php
$pw = array($_f."/z_komorka_zestawienie.php",$_f."/z_naprawy_status.php");
if (array_search($PHP_SELF, $pw)>-1) {
?>
<script type="text/javascript" src="js/jquery/jquery-1.6.min.js"></script>
<?php } ?>
<?php 
$pw = array($_f."/d_zadanie.php",$_f."/e_zadanie.php");
if (array_search($PHP_SELF, $pw)>-1) { 
?>
<link rel="stylesheet" type="text/css" href="js/jquery/jquery.autocomplete.css" />
<script type="text/javascript" src="js/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="js/jquery/jquery.autocomplete.js"></script>
<script>
	$().ready(function() {
		$("#dz_osoba").autocomplete("hd_get_oz_zgl_ser.php?filia=<?php echo $es_filia; ?>", {
			width: 360,
			max:150,
			matchContains: true,
			mustMatch: false,
			minChars: 1,
			//multiple: true, highlight: false, multipleSeparator: ",",
			selectFirst: false
		});
	});

/*	function ShowHints(k1,v1) {	
	//	alert(k1+" "+v1);
		if (k1!='') {
			$("#tr_pk_hint").show();
			$("#Hint").show();
			$("#Hint").load("klasyfikacja.php?k="+k1+"&pk="+v1+"");
		}
	}
*/
</script>
<?php } ?>
<?php 
$pw = array($_f."/d_zadanie.php",$_f."/e_zadanie.php",$_f."/e_zadanie_pozycja.php");
if (array_search($PHP_SELF, $pw)>-1) { 
?>
<script>
function trimAll(sString) {
	while (sString.substring(0,1) == ' ') { sString = sString.substring(1, sString.length); }
	while (sString.substring(sString.length-1, sString.length) == ' ') { sString = sString.substring(0,sString.length-1); }
	return sString;
} 
function cUpper(cObj) { cObj.value=trimAll(cObj.value.toUpperCase()); }
</script>
<?php } ?>
<?php $pw = array($_f."/d_zadanie_pozycje.php");if (array_search($PHP_SELF, $pw)>-1) { ?>
<script>
function PoliczZaznaczone(pokaz) {
var elLength = document.addzp.elements.length;
var licznik1 = 0;
	for (i=0; i<elLength; i++) {
		var type = document.addzp.elements[i].type;
		if ((type=="checkbox") && (document.addzp.elements[i].checked==true)) licznik1++;
	}
	
	//if (pokaz==true) alert('Liczba zaznaczonych pozycji: '+licznik1);
	
	document.getElementById('IloscZaznaczonych').innerText=licznik1;	
	document.getElementById('IloscZaznaczonych').textContent=licznik1;
	
	if (licznik1>0) { 
		document.getElementById('PrzypiszDoZadania').style.display=''; 
	} else { 
		document.getElementById('PrzypiszDoZadania').style.display='none'; 
	}
	return licznik1;
}
function ZaznaczWgTypu(typkomorki) {
var elLength = document.addzp.elements.length;
var licznik = 0;
    for (i=0; i<elLength; i++) {
        var type = document.addzp.elements[i].type;
		if ((type=="checkbox")) {
			var nr = document.addzp.elements[i].name.substring(7,10);
			if (typkomorki<5) {
var typ = document.getElementById('typ_komorki'+nr).value;		
if (typ==typkomorki) {
	document.getElementById('wybierz'+nr).checked=true;
	licznik++;
}
			} else {
var typ = document.getElementById('KOI_'+nr).value;
if (typ==1) {
	document.getElementById('wybierz'+nr).checked=true;
	licznik++;
}
			}
		}
    }
	//alert('Zaznaczono '+licznik+' pozycji');
	PoliczZaznaczone(true);
}
function OdznaczWgTypu(typkomorki) {
var elLength = document.addzp.elements.length;
var licznik = 0;
    for (i=0; i<elLength; i++) {
	
        var type = document.addzp.elements[i].type;
		
		if ((type=="checkbox")) {
			var nr = document.addzp.elements[i].name.substring(7,10);
			if (typkomorki<5) {
var typ = document.getElementById('typ_komorki'+nr).value;
if (typ==typkomorki) {
	document.getElementById('wybierz'+nr).checked=false;
	licznik++;
}
			} else {
var typ = document.getElementById('KOI_'+nr).value;
if (typ==1) {
	document.getElementById('wybierz'+nr).checked=false;
	licznik++;
}
			}
		}
    }
	//alert('Odznaczono '+licznik+' pozycji');
	PoliczZaznaczone(true);
}
</script>
<?php } ?>
<?php $pw = array($_f."/z_wymiana_wybor_z_ewidencji.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
<SCRIPT language=JavaScript>
function reload_wymiana_podzespolow(form) {
	var zgl=document.getElementById('tresc_zgl').value;
	var val=form.tnazwa.options[form.tnazwa.options.selectedIndex].value;
	if (zgl!='') {
		var newupid=form.new_upid.value;
		if (newupid==0) newupid='';
	} else {
		var newupid=form.new_upid.options[form.new_upid.options.selectedIndex].value;
	}
	var tup=form.tup.value;
	if (zgl!='') {
		var from=form.from.value;
		var hd_nr=form.hd_nr.value;
	} else {
		var from='';
		var hd_nr='';
	}	
	
	var auto1=document.getElementById('auto1').value;
	
	var hd_podkategoria_nr=form.hd_podkategoria_nr.value;
	var up=form.up.value;
	var id=form.id.value;
	var tmodel=form.tmodel.value;
	var tsn=form.tsn.value;
	var tni=form.tni.value;
	var tuwagi=form.tuwagi.value;
	var dde=form.dodaj_do_ewidencji.value;
	self.location='z_wymiana_wybor_z_ewidencji.php?id=' + id + '&cat=' + val + '&new_upid=' + newupid + '&tup='+urlencode1(tup)+'&up='+urlencode1(up)+'&tmodel=' +urlencode1(tmodel)+'&tsn='+urlencode1(tsn)+'&tni='+urlencode1(tni)+'&tuwagi='+urlencode1(tuwagi)+ '&tresc_zgl=' + zgl + '&dodaj_do_ewidencji='+dde+'&hd_podkategoria_nr='+hd_podkategoria_nr+'&from='+from+'&hd_nr='+hd_nr+'&auto='+auto1+'';
}
</script>


<?php if ($_REQUEST[auto]!=1) { ?>
<link rel="stylesheet" type="text/css" href="js/jquery/jquery.autocomplete.css" />
<script type="text/javascript" src="js/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="js/jquery/jquery.autocomplete.js"></script>

<SCRIPT language=JavaScript>
function getXhr(){
var xhr = null; 
	if(window.XMLHttpRequest) // Firefox
	   xhr = new XMLHttpRequest(); 
	else if(window.ActiveXObject){ // IE
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

function WybierzWartosc(c) {
	var wartosc = c.split('>>>>>')[1].split('<<<<<');
	var wynik = wartosc[0].substring(1,wartosc[0].length);
	
	if (wynik=='') { 
		//$('#dodaj_sprzet_do_ewidencji').show();
		//$('#a_dodaj_sprzet_do_ewidencji').show();
		$('#gw3').show();
		$('#info1').show('slow');
	} else {
		$('#info1').hide('slow');
		//$('#dodaj_sprzet_do_ewidencji').hide();
		//$('#a_dodaj_sprzet_do_ewidencji').hide();
		$('#gw3').hide();
	}
	return wynik;
}
function urlencode1 (str) {
    str = (str+'').toString();
    return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+');
}
function SprawdzSlownik(k) {
var xhr = getXhr();

	if (document.getElementById('tmodel').value!='') {
		xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 && xhr.status == 200){
			leselect = xhr.responseText;
			document.getElementById('tmodelid').innerHTML = leselect;
			document.getElementById('tmodelid1').value = WybierzWartosc((leselect));	
			}
		} 	
		xhr.open("POST","hd_get_slownik_id.php",true);
		xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		sel = document.getElementById('tmodel').value;		
		var parametry = ""+urlencode1(sel);
		//alert("wybierzid="+parametry+'&s='+document.getElementById('tnazwa').value);
		xhr.send("wybierzid="+parametry+"&s="+document.getElementById('tnazwa').value);
		return true;
	} else return true;
}

	$().ready(function() {
		$("#tmodel").autocomplete("hd_get_slownik_values.php?s=<?php echo $_REQUEST[cat]; ?>", {
			width: 360,
			max: 150,
			matchContains: false,
			mustMatch: false,
			minChars: 1,
			//multiple: true,
			//highlight: false,
			//multipleSeparator: ",",
			selectFirst: false
		});
	});
	
</script>


<?php } else { ?>
<script type="text/javascript" src="js/jquery/jquery-1.6.min.js"></script>
<?php } ?>
<?php } ?>

<?php $pw = array($_f."/z_wymiana_wybor_z_ewidencji.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
<script>
function ClearCookie (name, value) {  
	document.cookie = name + "=" + escape (value) + ";expires=Thu, 01-Jan-1970 00:00:01 GMT";
}
function SetCookie(name, value) {  
	var argv = SetCookie.arguments;  
	var argc = SetCookie.arguments.length;  
	var expires = (argc > 2) ? argv[2] : null;  
	var path = (argc > 3) ? argv[3] : null;  
	var domain = (argc > 4) ? argv[4] : null;  
	var secure = (argc > 5) ? argv[5] : false;  
	document.cookie = name + "=" + escape (value) + 
	((expires == null) ? "" : ("; expires=" + expires.toGMTString())) + 
	((path == null) ? "" : ("; path=" + path)) +  
	((domain == null) ? "" : ("; domain=" + domain)) +    
	((secure == true) ? "; secure" : "");
}		

function pytanie_zatwierdz_wp(message){ 
var a = document.getElementById('auto').value;
var wp_pw1 = document.getElementById('wp_pw').value;
var towar_z_listy = ((document.getElementById('sell_towar').length-1)>0);
var typ_z_listy = ((document.getElementById('typ_podzespolu').length-1)>0);
	if (a==0) {
	
		if (document.getElementById('tnazwa').options[document.getElementById('tnazwa').options.selectedIndex].value=='') {
			alert('Nie podałeś typu sprzętu'); document.getElementById('tnazwa').focus(); return false; 
		}
		
		if (document.getElementById('tmodel').value=='') { 
			alert('Nie podałeś modelu sprzętu'); document.getElementById('tmodel').focus(); return false; 
		}
		if (document.getElementById('tsn').value=='') { 
			alert('Nie podałeś numeru seryjnego sprzętu'); document.getElementById('tsn').focus(); return false; 
		}
		if (document.getElementById('tni').value=='') { 
			alert('Nie podałeś numeru inwentarzowego sprzętu'); document.getElementById('tni').focus(); return false; 
		}
	}
	if (document.getElementById('tuwagi').value=='') { 
		alert('Nie wpisałeś wykonanych czynności / wymienionych podzespołów'); document.getElementById('tuwagi').focus(); return false; 
	}
	
	if (wp_pw1==0) {
		if (typ_z_listy==false) {
			alert('Nie wybrałeś żadnego typu podzespołu'); 
			document.getElementById('typ_podzespolu').focus(); 
			return false; 
		}
	} else if (wp_pw1==1) {
		if (towar_z_listy==false) {
			alert('Nie wybrałeś żadnego towaru z magazynu lub brak jest towarów w magazynie'); 
			document.getElementById('sell_towar').focus(); 
			return false; 
		}
	} else {
		if (document.getElementById('sell_zestaw').value=='') {
			alert('Nie wybrałeś żadnego zestawu z listy'); 
			document.getElementById('sell_zestaw').focus(); 
			return false; 
		}
	}
	if (confirm(message)) { 
		
		document.forms.addt.submit(); 
		return true; 
	} else return false; 
	
	return false;
}
</script>
<script type="text/javascript" language="javascript">
	 
    function addOptionP(targetSelect,sourceSelect,hiddenField,hiddenField2) {
        var z = document.getElementById(sourceSelect);
        if (z.selectedIndex > 0 ) {
            var display = z.options[z.selectedIndex].text;
            var option = z.options[z.selectedIndex].value;
            var y = document.createElement('option');
            y.text = display;
            y.value = option;
            var x = document.getElementById(targetSelect);
            try {
                x.add(y,null);
            }
            catch(ex) {
                x.add(y);
            }
            x.options[0].text = '* wybranych towarów: '+(x.length-1)+' *';
            // Set hidden field
            var h = document.getElementById(hiddenField);
			var hcount = document.getElementById(hiddenField2);
			hcount.value = (x.length-1);
			
            h.value = h.value+option+'|#|';
        }
    }
	
    function addOptionT(targetSelect,sourceSelect,hiddenField,hiddenField2) {
        var z = document.getElementById(sourceSelect);
        if (z.selectedIndex > 0 ) {
            var display = z.options[z.selectedIndex].text;
            var option = z.options[z.selectedIndex].value;
            var y = document.createElement('option');
            y.text = display;
            y.value = option;
            var x = document.getElementById(targetSelect);
            try {
                x.add(y,null);
            }
            catch(ex) {
                x.add(y);
            }
            x.options[0].text = '* wybranych typów: '+(x.length-1)+' *';
            // Set hidden field
            var h = document.getElementById(hiddenField);
			var hcount = document.getElementById(hiddenField2);
			hcount.value = (x.length-1);
			
            h.value = h.value+option+'|#|';
        }
    }
	 
    function removeOptionP(selectBox,hiddenField,hiddenField2) {
        try {
         var x = document.getElementById(selectBox);
           if (x.selectedIndex > 0 ) {
                // Remove from hidden field
               var h = document.getElementById(hiddenField);
			   var hcount = document.getElementById(hiddenField2);
			   h.value = h.value.replace('|#|'+x.options[x.selectedIndex].value+'|#|','|#|');
			   
              x.remove(x.selectedIndex);
              x.options[0].text = '* wybranych towarów: '+(x.length-1)+' *';
              x.selectedIndex = 0;
			  
			  hcount.value = (x.length-1);
          }
     }
     catch (err) {
         // Do nothing
     }
    }
	
    function removeOptionT(selectBox,hiddenField,hiddenField2) {
        try {
         var x = document.getElementById(selectBox);
           if (x.selectedIndex > 0 ) {
                // Remove from hidden field
               var h = document.getElementById(hiddenField);
			   var hcount = document.getElementById(hiddenField2);
			   h.value = h.value.replace('|#|'+x.options[x.selectedIndex].value+'|#|','|#|');
			   
              x.remove(x.selectedIndex);
              x.options[0].text = '* wybranych typów: '+(x.length-1)+' *';
              x.selectedIndex = 0;
			  
			  hcount.value = (x.length-1);
          }
     }
     catch (err) {
         // Do nothing
     }
    }
</script>
<?php } ?>
<?php $pw = array($_f."/d_komorka.php",$_f."/e_komorka.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
<script>
function DopiszDwukropek1(v) {
	var r = document.getElementById(v).value.length;
	if (r==2) document.getElementById(v).value = document.getElementById(v).value+':';
	if (r==5) CheckTime1(document.getElementById(v).value);
}

function godzina_ok(v,v1) {
	var c1 = parseInt(document.getElementById(v).value[0]);
	var c2 = parseInt(document.getElementById(v).value[1]);
	var c3 = document.getElementById(v).value[2];
	var c4 = parseInt(document.getElementById(v).value[3]);
	var c5 = parseInt(document.getElementById(v).value[4]);
  if (document.getElementById(v).value=='') {
	document.getElementById(v1+'_span_ok').style.display='none';		document.getElementById(v1+'_span_error').style.display='none'; 
	return true;
  }
  if ((document.getElementById(v).value.length!=5)) { 
	document.getElementById(v1+'_span_ok').style.display='none';	  document.getElementById(v1+'_span_error').style.display='';
	document.getElementById('submit').style.display='none';
	return false; 
  }
  
  if ((c1==0) || (c1==1) || (c1==2)) {
	if ((c1==2) && (c2>3)) {
		document.getElementById(v1+'_span_ok').style.display='none';	  document.getElementById(v1+'_span_error').style.display='';
		document.getElementById('submit').style.display='none';
		return false;   		
	} else {
	
	}
  } else {
	document.getElementById(v1+'_span_ok').style.display='none';	  document.getElementById(v1+'_span_error').style.display='';
	document.getElementById('submit').style.display='none';
	return false;   
  }
  if (c3!=':') {
	document.getElementById(v1+'_span_ok').style.display='none';	  document.getElementById(v1+'_span_error').style.display='';
	document.getElementById('submit').style.display='none';
	return false;   
  }
  
  if ((c4==0) || (c4==1) || (c4==2) || (c4==3) || (c4==4) || (c4==5)) {
  } else {
	document.getElementById(v1+'_span_ok').style.display='none';	  document.getElementById(v1+'_span_error').style.display='';
	document.getElementById('submit').style.display='none';
	return false;   
  }
 
  if (c5>=0) { 
  } else {
	document.getElementById(v1+'_span_ok').style.display='none';	  document.getElementById(v1+'_span_error').style.display='';
	document.getElementById('submit').style.display='none';
	return false;   
  }
  document.getElementById('submit').style.display='';
  document.getElementById(v1+'_span_ok').style.display='';		document.getElementById(v1+'_span_error').style.display='none'; 
  return true;
}
function okres_ok(v,v1) {
	var c1 = parseInt(document.getElementById(v).value[0]);
	var c2 = parseInt(document.getElementById(v).value[1]);
	var c3 = document.getElementById(v).value[2];
	var c4 = parseInt(document.getElementById(v).value[3]);
	var c5 = parseInt(document.getElementById(v).value[4]);
  if (document.getElementById(v).value=='') {
	document.getElementById(v1+'_span_ok').style.display='none';		document.getElementById(v1+'_span_error').style.display='none'; 
	return true;
  }
  if ((document.getElementById(v).value.length!=5)) { 
	document.getElementById(v1+'_span_ok').style.display='none';	  document.getElementById(v1+'_span_error').style.display='';
	document.getElementById('submit').style.display='none';
	return false; 
  }
  
  if ((c1==0) || (c1==1)) {
	if (((c1==1) && (c2>2)) || ((c1==0) && (c2==0))) {
		document.getElementById(v1+'_span_ok').style.display='none';	  document.getElementById(v1+'_span_error').style.display='';
		document.getElementById('submit').style.display='none';
		return false;   		
	} else {
	
	}
  } else {
	document.getElementById(v1+'_span_ok').style.display='none';	  document.getElementById(v1+'_span_error').style.display='';
	document.getElementById('submit').style.display='none';
	return false;   
  }
  if (c3!='-') {
	document.getElementById(v1+'_span_ok').style.display='none';	  document.getElementById(v1+'_span_error').style.display='';
	document.getElementById('submit').style.display='none';
	return false;   
  }
  
  if ((c4==0) || (c4==1) || (c4==2) || (c4==3)) {
	
	if ((c4==3) && (c5>1)) {
		document.getElementById(v1+'_span_ok').style.display='none';	  document.getElementById(v1+'_span_error').style.display='';
		document.getElementById('submit').style.display='none';
		return false;   		
	} 
	
  } else {
	document.getElementById(v1+'_span_ok').style.display='none';	  document.getElementById(v1+'_span_error').style.display='';
	document.getElementById('submit').style.display='none';
	return false;   
  }
  
  document.getElementById('submit').style.display='';
  document.getElementById(v1+'_span_ok').style.display='';		document.getElementById(v1+'_span_error').style.display='none'; 
  return true;
}
function sprawdzWszystkieGodziny() {
	var gp = 0;
	var gpa = 0;
	if (godzina_ok('PN_start','PN_start')) gp=gp+1;
	if (godzina_ok('PN_stop','PN_stop')) gp=gp+1;
	if (godzina_ok('WT_start','WT_start')) gp=gp+1;
	if (godzina_ok('WT_stop','WT_stop')) gp=gp+1;
	if (godzina_ok('SR_start','SR_start')) gp=gp+1;
	if (godzina_ok('SR_stop','SR_stop')) gp=gp+1;
	if (godzina_ok('CZ_start','CZ_start')) gp=gp+1;
	if (godzina_ok('CZ_stop','CZ_stop')) gp=gp+1;
	if (godzina_ok('PT_start','PT_start')) gp=gp+1;
	if (godzina_ok('PT_stop','PT_stop')) gp=gp+1;
	if (godzina_ok('SO_start','SO_start')) gp=gp+1;
	if (godzina_ok('SO_stop','SO_stop')) gp=gp+1;
	if (godzina_ok('NI_start','NI_start')) gp=gp+1;
	if (godzina_ok('NI_stop','NI_stop')) gp=gp+1;
	
	if (godzina_ok('PNa_start','PNa_start')) gpa=gpa+1;	
	if (godzina_ok('PNa_stop','PNa_stop')) gpa=gpa+1;
	if (godzina_ok('WTa_start','WTa_start')) gpa=gpa+1;
	if (godzina_ok('WTa_stop','WTa_stop')) gpa=gpa+1;
	if (godzina_ok('SRa_start','SRa_start')) gpa=gpa+1;
	if (godzina_ok('SRa_stop','SRa_stop')) gpa=gpa+1;
	if (godzina_ok('CZa_start','CZa_start')) gpa=gpa+1;
	if (godzina_ok('CZa_stop','CZa_stop')) gpa=gpa+1;
	if (godzina_ok('PTa_start','PTa_start')) gpa=gpa+1;
	if (godzina_ok('PTa_stop','PTa_stop')) gpa=gpa+1;
	if (godzina_ok('SOa_start','SOa_start')) gpa=gpa+1;
	if (godzina_ok('SOa_stop','SOa_stop')) gpa=gpa+1;
	if (godzina_ok('NIa_start','NIa_start')) gpa=gpa+1;
	if (godzina_ok('NIa_stop','NIa_stop')) gpa=gpa+1;
	
	if (gp==14) document.getElementById('submit').style.display='';
}
function setDefaultWorkingHours(sufix) {
	document.getElementById('PN'+sufix+'_start').value='<?php echo $gp_start_pn_pt; ?>';	
	document.getElementById('PN'+sufix+'_stop').value='<?php echo $gp_stop_pn_pt; ?>';
	document.getElementById('WT'+sufix+'_start').value='<?php echo $gp_start_pn_pt; ?>';	
	document.getElementById('WT'+sufix+'_stop').value='<?php echo $gp_stop_pn_pt; ?>';
	document.getElementById('SR'+sufix+'_start').value='<?php echo $gp_start_pn_pt; ?>';	
	document.getElementById('SR'+sufix+'_stop').value='<?php echo $gp_stop_pn_pt; ?>';
	document.getElementById('CZ'+sufix+'_start').value='<?php echo $gp_start_pn_pt; ?>';	
	document.getElementById('CZ'+sufix+'_stop').value='<?php echo $gp_stop_pn_pt; ?>';
	document.getElementById('PT'+sufix+'_start').value='<?php echo $gp_start_pn_pt; ?>';	
	document.getElementById('PT'+sufix+'_stop').value='<?php echo $gp_stop_pn_pt; ?>';
	document.getElementById('SO'+sufix+'_start').value='<?php echo $gp_start_pn_pt; ?>';	
	document.getElementById('SO'+sufix+'_stop').value='<?php echo $gp_stop_so; ?>';
	document.getElementById('NI'+sufix+'_start').value='<?php echo $gp_start_ni; ?>';		
	document.getElementById('NI'+sufix+'_stop').value='<?php echo $gp_stop_ni; ?>';
}
function clearDefaultWorkingHours(sufix) {
	document.getElementById('PN'+sufix+'_start').value='';		document.getElementById('PN'+sufix+'_stop').value='';
	document.getElementById('WT'+sufix+'_start').value='';		document.getElementById('WT'+sufix+'_stop').value='';
	document.getElementById('SR'+sufix+'_start').value='';		document.getElementById('SR'+sufix+'_stop').value='';
	document.getElementById('CZ'+sufix+'_start').value='';		document.getElementById('CZ'+sufix+'_stop').value='';
	document.getElementById('PT'+sufix+'_start').value='';		document.getElementById('PT'+sufix+'_stop').value='';
	document.getElementById('SO'+sufix+'_start').value='';		document.getElementById('SO'+sufix+'_stop').value='';
	document.getElementById('NI'+sufix+'_start').value='';		document.getElementById('NI'+sufix+'_stop').value='';
	
	if (sufix=='a') {
		document.getElementById('gp_alt_od').value='';		
		document.getElementById('gp_alt_do').value='';
	}
}
function KopiujGodzinyPracy(typ, sufix) {
	var pn_start = document.getElementById('PN'+sufix+'_'+typ).value;
	document.getElementById('WT'+sufix+'_'+typ).value=pn_start;
	document.getElementById('SR'+sufix+'_'+typ).value=pn_start;
	document.getElementById('CZ'+sufix+'_'+typ).value=pn_start;
	document.getElementById('PT'+sufix+'_'+typ).value=pn_start;
}
</script>
<script language="JavaScript" type="text/javascript">
function pytanie_dodaj_komorke(message) {
	if (document.getElementById('pion_id').value==0) {
		alert("Nie wybrałeś pionu");
		document.getElementById('pion_id').focus();
		return false;		
	}
	if (document.getElementById('up').value=='') {
		alert("Nie podano nazwy komórki");
		document.getElementById('up').focus();
		return false;		
	}
	if (document.getElementById('dok').value=='') {
		alert("Nie podano daty otwarcia komórki");
		document.getElementById('dok').focus();
		return false;		
	}
	if (document.getElementById('umowa_id').value==0) {
		alert("Nie wybrałeś umowy");
		document.getElementById('umowa_id').focus();
		return false;		
	}
	if (confirm(message)) { 
		document.getElementById('submit').style.display='none';		
		document.getElementById('anuluj').style.display='none';
		document.forms.addu.submit(); 
		return true; 
	} else return false; 
}
function pytanie_edytuj_komorke(message) {
	if (document.getElementById('upe').value=='') {
		alert("Nie podano nazwy komórki");
		document.getElementById('upe').focus();
		return false;		
	}
	if (document.getElementById('umowa_id').value==0) {
		alert("Nie wybrałeś umowy");
		document.getElementById('umowa_id').focus();
		return false;		
	}
	if (confirm(message)) { 
		document.getElementById('submit').style.display='none';		
		document.getElementById('anuluj').style.display='none';
		document.forms.addu.submit(); 
		return true; 
	} else return false; 
}
</script>
<?php } ?>
<?php $pw = array($_f."/z_magazyn_pobierz.php",$_f."/z_magazyn_zwrot.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
<script>
function urlencode1 (str) {
    str = (str+'').toString();
    return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+');
}
function reload1_obrot(form) {
	var t_id = form.tid.value;
	var tup=form.new_upid.options[form.new_upid.options.selectedIndex].value;
	var tkom = form.tkomentarz.value;
	var thd_zgl_nr=form.hd_zgl_nr.value;
	var tpart=form.part.value;
	var tpowiazzhd=form.powiazzhd.value;
	self.location='z_magazyn_pobierz.php?id='+t_id+'&part='+urlencode1(tpart)+'&new_upid='+urlencode1(tup)+'&hd_zgl_nr='+thd_zgl_nr+'&powiazzhd='+tpowiazzhd+'&tkomentarz='+urlencode1(tkom)+'';
}
function PokazZgloszenie(n) {
	if (!isNaN(parseInt(n))) {
		newWindow_r(800,600,'hd_o_zgloszenia.php?action=podglad&nr='+n+'&id='+n+'');
	} else {
		alert('Błednie podany numer zgłoszenia');
	}
}
</script>
<?php } ?>
<?php $pw = array($_f."/z_magazyn_zwrot.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
<script>
function urlencode1 (str) {
    str = (str+'').toString();
    return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+');
}
function reload1_obrot(form) {
	var t_id = form.tid.value;
	var tkom = form.tkomentarz.value;
	var thd_zgl_nr=form.hd_zgl_nr.value;
	var tpart=form.part.value;
	var tpowiazzhd=form.powiazzhd.value;
	self.location='z_magazyn_zwrot.php?id='+t_id+'&part='+urlencode1(tpart)+'&hd_zgl_nr='+thd_zgl_nr+'&powiazzhd='+tpowiazzhd+'&tkomentarz='+urlencode1(tkom)+'';
}
</script>
<?php } ?>
<?php $pw = array($_f."/hd_d_wyjazd.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
<script type="text/javascript" src="js/jquery/jquery-1.6.min.js"></script>
<script>
function filterInput(filterType,evt,allowDecimal,allowCustom){var keyCode,Char,inputField,filter='';var alpha='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';var num='0123456789';if(window.event){keyCode=window.event.keyCode;evt=window.event}else if(evt)keyCode=evt.which;else return true;if(filterType==0)filter=alpha;else if(filterType==1)filter=num;else if(filterType==2)filter=alpha+num;if(allowCustom)filter+=allowCustom;if(filter=='')return true;inputField=evt.srcElement?evt.srcElement:evt.target||evt.currentTarget;if((keyCode==null)||(keyCode==0)||(keyCode==8)||(keyCode==9)||(keyCode==27))return true;Char=String.fromCharCode(keyCode);if((filterType==1)&&(String.fromCharCode(keyCode)==',')){Char='.'}if((filter.indexOf(Char)>-1))return true;else if(filterType==1&&allowDecimal&&(Char=='.')&&inputField.value.indexOf('.')==-1)return true;else return false}
function pytanie_zapisz(message) {
	if (document.getElementById('hd_wyjazd_rp').value=='P') {
		if (document.getElementById('trasa').value=='') {
			alert('Nie wpisałeś trasy przejazdu');	document.getElementById('trasa').focus();	return false;
		}
//		if ((document.getElementById('km').value=='') || (document.getElementById('km').value=='0')){
//			alert('Nie wpisałeś ilości km');	document.getElementById('km').focus();	return false;
//		}

	}
	
	if ((document.getElementById('czas_przejazdu_h').value=='0') && (document.getElementById('czas_przejazdu_m').value=='0')) {
		if (confirm('Podano zerowy czas trwania wyjazdu. Czy chcesz poprawić te dane ?')) {
			//alert('Nie podałeś czasu trwania wyjazdu');
			document.getElementById('czas_przejazdu_m').focus();
			return false;
		}
	}
		
	if (confirm(message)) { 
		document.getElementById('submit').style.display='none';		
		document.getElementById('anuluj').style.display='none';
		document.forms.add.submit(); 
		return true; 
	} else return false; 
}
</script>
<?php } ?>
<?php
$pw = array($_f."/g_zestaw_obrot.php",$_f."/z_towary_obrot.php",$_f."/z_magazyn_zwrot.php",$_f."/z_magazyn_pobierz.php",$_f."/e_towary_obrot.php",$_f."/z_naprawy_wycofaj.php",$_f."/z_naprawy_wycofaj_sprzet_serwisowy.php");
if (array_search($PHP_SELF, $pw)>-1) { 
?>
<script>
function filterInput(filterType,evt,allowDecimal,allowCustom){var keyCode,Char,inputField,filter='';var alpha='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';var num='0123456789';if(window.event){keyCode=window.event.keyCode;evt=window.event}else if(evt)keyCode=evt.which;else return true;if(filterType==0)filter=alpha;else if(filterType==1)filter=num;else if(filterType==2)filter=alpha+num;if(allowCustom)filter+=allowCustom;if(filter=='')return true;inputField=evt.srcElement?evt.srcElement:evt.target||evt.currentTarget;if((keyCode==null)||(keyCode==0)||(keyCode==8)||(keyCode==9)||(keyCode==27)||(keyCode==13))return true;Char=String.fromCharCode(keyCode);if((filterType==1)&&(String.fromCharCode(keyCode)==',')){Char='.'}if((filter.indexOf(Char)>-1))return true;else if(filterType==1&&allowDecimal&&(Char=='.')&&inputField.value.indexOf('.')==-1)return true;else return false}
</script>
<?php } ?>
<?php
$pw = array($_f."/hd_g_harmonogram_wyjazdu.php");
if (array_search($PHP_SELF, $pw)>-1) { 
?>
<script type="text/javascript" src="js/jquery/jquery-1.6.min.js"></script>
<script>
function PrepareRows(v) {
	var ilosc_zgl = document.getElementById('cnt_zgl').value;
	var params = '';
	
	if (v==1) {
		for (var g=0; g<ilosc_zgl; g++) {
			document.getElementById('order-'+g).style.display='';
			document.getElementById('numer-'+g).style.display='none';
			
			document.getElementById('dec-'+g).style.display='';
			document.getElementById('inc-'+g).style.display='';
			
			var nr = g;
			nr++;
			if (document.getElementById('order-'+g).value=='') document.getElementById('order-'+g).value=nr;
		}
		document.getElementById('th_kolumna_z_nr').style.display='none';
		document.getElementById('th_kolumna_z_kolejnoscia').style.display='';			
		document.getElementById('UstawKolejnosc').style.display='none';
		document.getElementById('ZapiszKolejnosc').style.display='';
		
	} else {
	
		for (var g=0; g<ilosc_zgl; g++) {
			document.getElementById('order-'+g).style.display='none';
			document.getElementById('numer-'+g).style.display='';
	
			document.getElementById('dec-'+g).style.display='none';
			document.getElementById('inc-'+g).style.display='none';
	
			var temp_zgl = document.getElementById('nr_zgl-'+g).value;
			var temp_order = document.getElementById('order-'+g).value;
			var pair = temp_zgl + "@" + temp_order + ",";
			params += pair;
		}
		
		document.getElementById('th_kolumna_z_nr').style.display='';
		document.getElementById('th_kolumna_z_kolejnoscia').style.display='none';			
		
		document.getElementById('UstawKolejnosc').style.display='';
		document.getElementById('ZapiszKolejnosc').style.display='none';
	}
}
function Decrease(v) { 
	if (v.value>1) { 
		v.value--; 
		CzyscTrasyWyjazdowe(); 
	} 
}
function Increase(v) { 
var ilosc_zgl = document.getElementById('cnt_zgl').value;
	if (v.value<ilosc_zgl) v.value++;
	CzyscTrasyWyjazdowe();
}
function urlencode1 (str) {
    str = (str+'').toString();
    return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+');
}
function DeleteFomHarmonogram(n,liczba) {
	var zgloszenia = document.getElementById('numbers').value;
	var a_zgl = new Array();
	var b_zgl = new Array();
	
	var bez_przecinka = liczba - 1;
	
	a_zgl = zgloszenia.split(',');
	b_zgl = '';
	
	for (var g=0; g<liczba; g++) {
		if (n!=a_zgl[g]) {
			b_zgl = b_zgl + a_zgl[g] + ','; 
		}
	}
	
	b_zgl = b_zgl.substring(0, b_zgl.length-1);
	
	var liczba1 = liczba - 1;
	
	var n_h = urlencode1(document.getElementById('nazwa_h').value);
	var d_h = document.getElementById('data_h').value;
	var p_h = urlencode1(document.getElementById('przypisanie_h').value);
	if (confirm('Czy na pewno chcesz usunąć zgłoszenie nr '+n+' z tworzonego harmonogramu?')) {
		self.location='hd_g_harmonogram_wyjazdu.php?nr=' + b_zgl + '&cnt=' + liczba1 + '&clear_cookie=1&nazwa_h='+n_h+'&data_h='+d_h+'&przypisanie_h='+p_h+'';
	} else return false;
	
}
</script>
<?php } ?>
<?php
$pw = array($_f."/p_zadanie_pozycje.php");
if (array_search($PHP_SELF, $pw)>-1) { 
?>
<script>
function UpdateIloscZaznaczen(n) {
	var elLength = document.addzp.elements.length;		
	var count_checked = 0;
	var prawa = document.getElementById('user_prawa').value;	
	
	//document.getElementById('PrzypiszDoSiebie').style.display='none';
	if (document.getElementById('PrzypiszDoOsoby')) document.getElementById('PrzypiszDoOsoby').style.display='none';
	if (document.getElementById('PrzypiszWHD')) document.getElementById('PrzypiszWHD').style.display='none';
	
//	if (prawa=='9') document.getElementById('PrzypiszDoOsoby').style.display='none';
	if ((prawa=='9') && (document.getElementById('UsunZZadania'))) document.getElementById('UsunZZadania').style.display='none';
	
	if (document.getElementById('ZamknijBezHD')) document.getElementById('ZamknijBezHD').style.display='none';
	if (document.getElementById('przerwa')) document.getElementById('przerwa').style.display='none';
	
    for (i=0; i<elLength; i++) {
        var type = document.addzp.elements[i].type;		
        if (type=="checkbox" && document.addzp.elements[i].checked && (document.addzp.elements[i].name.substring(0,7)=='markzgl')) count_checked = count_checked + 1;
	}
	
	document.getElementById('IloscZaznaczonych').innerText=count_checked;	
	document.getElementById('IloscZaznaczonych').textContent=count_checked;
	//alert(''+n+' | '+count_checked);
	
	if (n==1) {
		if (count_checked>0) {
			if (document.getElementById('przerwa')) document.getElementById('przerwa').style.display='';
			//document.getElementById('PrzypiszDoSiebie').style.display='';
			if (document.getElementById('PrzypiszDoOsoby')) document.getElementById('PrzypiszDoOsoby').style.display='';
			if ((prawa=='9') && (document.getElementById('UsunZZadania'))) document.getElementById('UsunZZadania').style.display='';
			if (document.getElementById('PrzypiszWHD')) document.getElementById('PrzypiszWHD').style.display='';  
			if (document.getElementById('ZamknijBezHD')) document.getElementById('ZamknijBezHD').style.display='';
			document.getElementById('l').style.display='none';
		}
	} else {
		if (count_checked>0) {
			if (document.getElementById('przerwa')) document.getElementById('przerwa').style.display='';
			if (document.getElementById('PrzypiszDoOsoby')) document.getElementById('PrzypiszDoOsoby').style.display='';
			if ((prawa=='9') && (document.getElementById('UsunZZadania'))) document.getElementById('UsunZZadania').style.display='';
			if (document.getElementById('PrzypiszWHD')) document.getElementById('PrzypiszWHD').style.display='';
			if (document.getElementById('ZamknijBezHD')) document.getElementById('ZamknijBezHD').style.display='';
			//document.getElementById('PrzypiszDoSiebie').style.display='';
			document.getElementById('l').style.display='none';
		}	
	}
}
function MarkCheckboxes2(akcja) {
	for(var i=0,l=document.addzp.elements.length; i<l; i++) {
		
		if (document.addzp.elements[i].type == 'checkbox') {
			if (akcja=='odwroc') {
document.addzp.elements[i].checked=document.addzp.elements[i].checked?false:true;
			} else if (akcja=='zaznacz') {
document.addzp.elements[i].checked=true;
			} else if (akcja=='odznacz') {
document.addzp.elements[i].checked=false;
			}
		}	
	}
}
function PrzypiszDoSiebie1(n) {
	var cu = '<?php echo urlencode($currentuser); ?>';
	var elLength = document.addzp.elements.length;
	var count_checked = 0;
	var params = "nr=";
	var count_radio = 0;
	var params2 = "nr=";
	
    for (i=0; i<elLength; i++)
    {
        var type = document.addzp.elements[i].type;
		
        if (type=="checkbox" && document.addzp.elements[i].checked && (document.addzp.elements[i].name.substring(0,7)=='markzgl')) {
			count_checked = count_checked + 1;
			params += document.addzp.elements[i].value + ",";
        }
        if (type=="radio" && document.addzp.elements[i].checked && (document.addzp.elements[i].name.substring(0,7)=='markzgl')) {
			count_radio = count_radio + 1;
			params2 += document.addzp.elements[i].value + ",";
        }
		
	}
	
	if (count_checked>0) {
		document.getElementById('IloscZaznaczonych').innerText=count_checked;
	} else if (count_radio>0) {
		document.getElementById('IloscZaznaczonych').innerText=count_radio;
	}
	var dl = params.length;
	var dl2 = params2.length;
	
	dl--;
	dl2--;
	
	if ((dl==2) && (dl2==2)) {
		alert('Nie zaznaczyłeś żadnej pozycji z zadania');
		return false;
	}
	
	var okno_max = 0;
	
	if (dl!=2) { 
		var params1 = params.substring(0,dl); 
		var lokacja1 = "e_zadanie_osoba_many.php?";		
		var lokacja2 = "e_zadanie_osoba_many.php?action=addzp&";
		
		if (count_checked==1) {
			var lokacja_1 = params1 + "&cnt=" + count_checked + "&clear_cookie=1";
			lokacja = lokacja2 + lokacja_1;
			okno_max = 0;
		} else {
			var lokacja_1 = params1 + "&cnt=" + count_checked + "&clear_cookie=1";
			lokacja = lokacja1 + lokacja_1;
			okno_max = 0;
		}
		
	} else if (dl2!=2) { 
			var params1 = params2.substring(0,dl2); 
			var lokacja = "e_zadanie_osoba_many.php?action=addzp&" + params1 + "&cnt=" + count_radio + "&clear_cookie=1";
			okno_max = 0;
			}
	
	lokacja = lokacja + "&dosiebie="+cu+"&cu="+cu+"";
	
	if (okno_max==0) {
		var x = 800;
		var y = 600;
	} else {
		var x=window.screen.availWidth-5;
		var y=window.screen.availHeight-30; 	
	}
	
	if (window.screen) {
		var ah = screen.availHeight - 30;
		var aw = screen.availWidth - 10;
		
		var xx=(aw-x) / 2;
		xx=Math.round(xx);
		var yy=(ah-y) / 2;
		yy=Math.round(yy);
	}
	
	var opcje="scrollbars=yes, width="+x+", innerWidth="+x+", height="+y+", innerHeight="+y+", top="+yy+", screenX="+xx+", screenY="+yy+", left="+xx;
	
	NewWindowOpen = window.open(lokacja, "eSerwisOSZ", opcje);
	NewWindowOpen.focus;
	//return false;
}
function UsunPozycjeZZadania(n) {
	var cu = '<?php echo urlencode($currentuser); ?>';
	var elLength = document.addzp.elements.length;
	var count_checked = 0;
	var params = "nr=";
	var count_radio = 0;
	var params2 = "nr=";
	
    for (i=0; i<elLength; i++)
    {
        var type = document.addzp.elements[i].type;
		
        if (type=="checkbox" && document.addzp.elements[i].checked && (document.addzp.elements[i].name.substring(0,7)=='markzgl')) {
			count_checked = count_checked + 1;
			params += document.addzp.elements[i].value + ",";
        }
        if (type=="radio" && document.addzp.elements[i].checked && (document.addzp.elements[i].name.substring(0,7)=='markzgl')) {
			count_radio = count_radio + 1;
			params2 += document.addzp.elements[i].value + ",";
        }
		
	}
	
	if (count_checked>0) {
		document.getElementById('IloscZaznaczonych').innerText=count_checked;
	} else if (count_radio>0) {
		document.getElementById('IloscZaznaczonych').innerText=count_radio;
	}
	var dl = params.length;
	var dl2 = params2.length;
	
	dl--;
	dl2--;
	
	if ((dl==2) && (dl2==2)) {
		alert('Nie zaznaczyłeś żadnej pozycji z zadania');
		return false;
	}
	
	var okno_max = 0;
	
	if (dl!=2) { 
		var params1 = params.substring(0,dl); 
		var lokacja1 = "u_zadanie_pozycja_many.php?";		
		var lokacja2 = "u_zadanie_pozycja_many.php?";
		
		if (count_checked==1) {
			var lokacja_1 = params1 + "&cnt=" + count_checked + "&clear_cookie=1";
			lokacja = lokacja2 + lokacja_1;
			okno_max = 0;
		} else {
			var lokacja_1 = params1 + "&cnt=" + count_checked + "&clear_cookie=1";
			lokacja = lokacja1 + lokacja_1;
			okno_max = 0;
		}
		
	} else if (dl2!=2) { 
			var params1 = params2.substring(0,dl2); 
			var lokacja = "u_zadanie_pozycja_many.php?" + params1 + "&cnt=" + count_radio + "&clear_cookie=1";
			okno_max = 0;
			}
	
	lokacja = lokacja + "&cu="+cu+"";
	
	if (okno_max==0) {
		var x = 800;
		var y = 600;
	} else {
		var x=window.screen.availWidth-5;
		var y=window.screen.availHeight-30; 	
	}
	
	if (window.screen) {
		var ah = screen.availHeight - 30;
		var aw = screen.availWidth - 10;
		
		var xx=(aw-x) / 2;
		xx=Math.round(xx);
		var yy=(ah-y) / 2;
		yy=Math.round(yy);
	}
	
	var opcje="scrollbars=yes, width="+x+", innerWidth="+x+", height="+y+", innerHeight="+y+", top="+yy+", screenX="+xx+", screenY="+yy+", left="+xx;
	
	NewWindowOpen = window.open(lokacja, "eSerwisUPzZ", opcje);
	NewWindowOpen.focus;
	//return false;
}
function ZamknijBezZgloszeniaHD() {
	var cu = '<?php echo urlencode($currentuser); ?>';
	var elLength = document.addzp.elements.length;
	var count_checked = 0;
	var params = "nr=";
	var count_radio = 0;
	var params2 = "nr=";
	
    for (i=0; i<elLength; i++)
    {
        var type = document.addzp.elements[i].type;
		
        if (type=="checkbox" && document.addzp.elements[i].checked && (document.addzp.elements[i].name.substring(0,7)=='markzgl')) {
			count_checked = count_checked + 1;
			params += document.addzp.elements[i].value + ",";
        }
        if (type=="radio" && document.addzp.elements[i].checked && (document.addzp.elements[i].name.substring(0,7)=='markzgl')) {
			count_radio = count_radio + 1;
			params2 += document.addzp.elements[i].value + ",";
        }
		
	}
	
	if (count_checked>0) {
		document.getElementById('IloscZaznaczonych').innerText=count_checked;
	} else if (count_radio>0) {
		document.getElementById('IloscZaznaczonych').innerText=count_radio;
	}
	var dl = params.length;
	var dl2 = params2.length;
	
	dl--;
	dl2--;
	
	if ((dl==2) && (dl2==2)) {
		alert('Nie zaznaczyłeś żadnej pozycji z zadania');
		return false;
	}
	
	var okno_max = 0;
	
	if (dl!=2) { 
		var params1 = params.substring(0,dl); 
		var lokacja1 = "z_zadanie_pozycja_many.php?";		
		var lokacja2 = "z_zadanie_pozycja_many.php?";
		
		if (count_checked==1) {
			var lokacja_1 = params1 + "&cnt=" + count_checked + "&clear_cookie=1";
			lokacja = lokacja2 + lokacja_1;
			okno_max = 0;
		} else {
			var lokacja_1 = params1 + "&cnt=" + count_checked + "&clear_cookie=1";
			lokacja = lokacja1 + lokacja_1;
			okno_max = 0;
		}
		
	} else if (dl2!=2) { 
			var params1 = params2.substring(0,dl2); 
			var lokacja = "z_zadanie_pozycja_many.php?" + params1 + "&cnt=" + count_radio + "&clear_cookie=1";
			okno_max = 0;
			}
	
	lokacja = lokacja + "&cu="+cu+"";
	
	if (okno_max==0) {
		var x = 800;
		var y = 600;
	} else {
		var x=window.screen.availWidth-5;
		var y=window.screen.availHeight-30; 	
	}
	
	if (window.screen) {
		var ah = screen.availHeight - 30;
		var aw = screen.availWidth - 10;
		
		var xx=(aw-x) / 2;
		xx=Math.round(xx);
		var yy=(ah-y) / 2;
		yy=Math.round(yy);
	}
	
	var opcje="scrollbars=yes, width="+x+", innerWidth="+x+", height="+y+", innerHeight="+y+", top="+yy+", screenX="+xx+", screenY="+yy+", left="+xx;
	
	//alert(lokacja);
	NewWindowOpen = window.open(lokacja, "eSerwisUPzZ", opcje);
	NewWindowOpen.focus;
	//return false;
}
function UtworzPrzypisaneZgloszeniaWHD() {
	var nb = document.getElementById('noback').value;
	var cu = '<?php echo urlencode($currentuser); ?>';
	var elLength = document.addzp.elements.length;
	var count_checked = 0;
	var params = "numery=";
	var count_radio = 0;
	var params2 = "numery=";
	
    for (i=0; i<elLength; i++)
    {
        var type = document.addzp.elements[i].type;
		
        if (type=="checkbox" && document.addzp.elements[i].checked && (document.addzp.elements[i].name.substring(0,7)=='markzgl')) {
			count_checked = count_checked + 1;
			params += document.addzp.elements[i].value + ",";
        }
        if (type=="radio" && document.addzp.elements[i].checked && (document.addzp.elements[i].name.substring(0,7)=='markzgl')) {
			count_radio = count_radio + 1;
			params2 += document.addzp.elements[i].value + ",";
        }
		
	}
	
	if (count_checked>0) {
		document.getElementById('IloscZaznaczonych').innerText=count_checked;
	} else if (count_radio>0) {
		document.getElementById('IloscZaznaczonych').innerText=count_radio;
	}
	var dl = params.length;
	var dl2 = params2.length;
	
	dl--;
	dl2--;
	
	if ((dl==2) && (dl2==2)) {
		alert('Nie zaznaczyłeś żadnej pozycji z zadania');
		return false;
	}
	
	var okno_max = 0;
	
	if (dl!=2) { 
		var params1 = params.substring(0,dl); 
		var lokacja1 = "hd_d_zgloszenie_przypisane.php?";		
		var lokacja2 = "hd_d_zgloszenie_przypisane.php?";
		
		if (count_checked==1) {
			var lokacja_1 = params1 + "&cnt=" + count_checked + "&clear_cookie=1";
			lokacja = lokacja2 + lokacja_1;
			okno_max = 0;
		} else {
			var lokacja_1 = params1 + "&cnt=" + count_checked + "&clear_cookie=1";
			lokacja = lokacja1 + lokacja_1;
			okno_max = 0;
		}
		
	} else if (dl2!=2) { 
			var params1 = params2.substring(0,dl2); 
			var lokacja = "hd_d_zgloszenie_przypisane.php?" + params1 + "&cnt=" + count_radio + "&clear_cookie=1";
			okno_max = 0;
			}
	
	lokacja = lokacja + "&cu="+cu+"&noback="+nb+"";
	
	if (okno_max==0) {
		var x = 800;
		var y = 600;
	} else {
		var x=window.screen.availWidth-5;
		var y=window.screen.availHeight-30; 	
	}
	
	if (window.screen) {
		var ah = screen.availHeight - 30;
		var aw = screen.availWidth - 10;
		
		var xx=(aw-x) / 2;
		xx=Math.round(xx);
		var yy=(ah-y) / 2;
		yy=Math.round(yy);
	}
	
	var opcje="scrollbars=yes, width="+x+", innerWidth="+x+", height="+y+", innerHeight="+y+", top="+yy+", screenX="+xx+", screenY="+yy+", left="+xx;
	
	//alert(lokacja);
	NewWindowOpen = window.open(lokacja, "eSerwisUPzZ", opcje);
	NewWindowOpen.focus;
	//return false;
}
</script>
<?php } ?>
<?php
$pw = array($_f."/z_naprawy_wycofaj.php",$_f."/z_naprawy_wycofaj_sprzet_serwisowy.php");
if (array_search($PHP_SELF, $pw)>-1) { 
?>
<script>
function PokazZgloszenie(n) {
	if (!isNaN(parseInt(n))) {
		newWindow_r(800,600,'hd_o_zgloszenia.php?action=podglad&nr='+n+'&id='+n+'');
	} else {
		alert('Błednie podany numer zgłoszenia');
	}
}
function urlencode1 (str) {
    str = (str+'').toString();
    return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+');
}
function reload1(form) {
var _ew_id=form.ewid_id.value;
var _id=form.id.value;
var _tup=form.tup.value;
var _up=form.up.value;
var _part=form.part.value;
var _mmodel=form.mmodel.value;
var _msn=form.msn.value;
var _mni=form.mni.value;
var _tstatus1=form.tstatus1.value;
var _szid=form.szid.value;
var _unr=form.unr.value;
var _serwisowy=form.serwisowy.value;
var _new_upid=form.new_upid.value;
var _hd_zgl_nr=form.hd_zgl_nr.value;
self.location='z_naprawy_wycofaj.php?ew_id='+_ew_id+'&id='+_id+'&tup=' + urlencode1(_tup) + '&up=' +urlencode1(_up)+'&part=' +urlencode1(_part)+ '&mmodel='+urlencode1(_mmodel)+'&_msn='+urlencode1(_msn)+'&mni='+urlencode1(_mni)+'&tstatus1='+_tstatus1+'&szid='+_szid+'&unr='+urlencode1(_unr)+'&serwisowy='+urlencode1(_serwisowy)+'&new_upid='+_new_upid+'&hd_zgl_nr='+_hd_zgl_nr+'';
}
</script>
<?php } ?>
<?php
$pw = array($_f."/z_naprawy_status_zmien.php",$_f."/z_naprawy_serwis.php",$_f."/z_naprawy_napraw3.php",$_f."/z_naprawy_lokalny.php");
if (array_search($PHP_SELF, $pw)>-1) { 
?>
<script>
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
function SetCookie (name, value) {  
	var argv = SetCookie.arguments;  
	var argc = SetCookie.arguments.length;  
	var expires = (argc > 2) ? argv[2] : null;  
	var path = (argc > 3) ? argv[3] : null;  
	var domain = (argc > 4) ? argv[4] : null;  
	var secure = (argc > 5) ? argv[5] : false;  
	document.cookie = name + "=" + escape (value) + 
	((expires == null) ? "" : ("; expires=" + expires.toGMTString())) + 
	((path == null) ? "" : ("; path=" + path)) +  
	((domain == null) ? "" : ("; domain=" + domain)) +    
	((secure == true) ? "; secure" : "");
}
function ClearCookie (name, value) {  
	document.cookie = name + "=" + escape (value) + ";expires=Thu, 01-Jan-1970 00:00:01 GMT";
}
</script>
<?php } ?>
<?php
$pw = array($_f."/hd_e_zgloszenie_hadim.php");
if (array_search($PHP_SELF, $pw)>-1) { 
?>
<script>
function pytanie_zapisz_ehadim(message) {
	
	if (document.getElementById('hadim_nr').value=='') { 
		alert('Nie podałeś numeru HDIM'); document.getElementById('hadim_nr').focus(); return false; 
	}

	if (confirm(message)) { 
		document.getElementById('submit').style.display='none';
		document.getElementById('anuluj').style.display='none';
		document.forms.add.submit(); 
		return true; 
	} else return false; 
}
</script>
<?php } ?>
<?php
$pw = array($_f."/main.php");
if (array_search($PHP_SELF, $pw)>-1) { 
?>
<script>
function filterInputEnter(filterType,evt,allowDecimal,allowCustom){var keyCode,Char,inputField,filter='';var alpha='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';var num='0123456789';if(window.event){keyCode=window.event.keyCode;evt=window.event}else if(evt)keyCode=evt.which;else return true;if(filterType==0)filter=alpha;else if(filterType==1)filter=num;else if(filterType==2)filter=alpha+num;if(allowCustom)filter+=allowCustom;if(filter=='')return true;inputField=evt.srcElement?evt.srcElement:evt.target||evt.currentTarget;if((keyCode==null)||(keyCode==0)||(keyCode==8)||(keyCode==9)||(keyCode==13)||(keyCode==27))return true;Char=String.fromCharCode(keyCode);if((filterType==1)&&(String.fromCharCode(keyCode)==',')){Char='.'}if((filter.indexOf(Char)>-1))return true;else if(filterType==1&&allowDecimal&&(Char=='.')&&inputField.value.indexOf('.')==-1)return true;else return false}
</script>
<?php } ?>
<?php
$pw = array($_f."/p_sprzedaz_w_okresie.php");
if (array_search($PHP_SELF, $pw)>-1) { 
?>
<script type="text/javascript" src="templates/<?php echo $template;?>/lightrow.js"></script>
<?php } ?>
<?php
$pw = array($_f."/z_naprawy_wybierz.php");
if (array_search($PHP_SELF, $pw)>-1) { 
?>
<script>
function ClearCookie (name, value) {  
	document.cookie = name + "=" + escape (value) + ";expires=Thu, 01-Jan-1970 00:00:01 GMT";
}
</script>
<?php } ?>
<?php
$pw = array($_f."/p_ewidencja_simple.php");
if (array_search($PHP_SELF, $pw)>-1) { 
?>
<script>
function str_replace (search, replace, subject, count) {
    // *     example 1: str_replace(' ', '.', 'Kevin van Zonneveld');
    // *     returns 1: 'Kevin.van.Zonneveld'
    var i = 0, j = 0, temp = '', repl = '', sl = 0, fl = 0,
            f = [].concat(search),
            r = [].concat(replace),
            s = subject,
            ra = r instanceof Array, sa = s instanceof Array;
    s = [].concat(s);
    if (count) {
        this.window[count] = 0;
    }
    for (i=0, sl=s.length; i < sl; i++) {
        if (s[i] === '') {
            continue;
        }
        for (j=0, fl=f.length; j < fl; j++) {
            temp = s[i]+'';
            repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0];
            s[i] = (temp).split(f[j]).join(repl);
            if (count && s[i] !== temp) {
                this.window[count] += (temp.length-s[i].length)/f[j].length;}
        }
    }
    return sa ? s : s[0];
}
function ex1(val) {
	var text = val.value.replace(/\s+$/g,"") 
	var split = text.split("\n") 
	if (split.length<5) val.rows = split.length+1;
}
function KopiujDo1Entera_sl(val, dest) {
	var w1 = val.replace("\n","<br>");
	var s1 = w1.search("<br>");
	if (s1>0) {
		opener.document.getElementById(dest).value=w1.substring(0,s1);
		return true;
	}
	opener.document.getElementById(dest).value=w1;
}
function DodajTresc(tresc) {
	opener.document.getElementById('hd_tresc').value = opener.document.getElementById('hd_tresc').value + str_replace('<br />','\n',tresc);
	
	opener.document.getElementById('sl_d').style.display=''; 
	opener.document.getElementById('tr_clear').style.display='';
	
	KopiujDo1Entera_sl(opener.document.getElementById('hd_tresc').value,'hd_temat');
	ex1(opener.document.getElementById('hd_tresc'));	
	self.close();
	opener.document.getElementById('hd_tresc').focus();
}
function DodajTrescA(tresc) {
	opener.document.getElementById('hd_tresc').value = opener.document.getElementById('hd_tresc').value + str_replace('<br />','\n',tresc);
	
	//opener.document.getElementById('sl_d').style.display=''; 
	opener.document.getElementById('tr_clear').style.display='';
	
	KopiujDo1Entera_sl(opener.document.getElementById('hd_tresc').value,'hd_temat');
	ex1(opener.document.getElementById('hd_tresc'));	
	self.close();
	opener.document.getElementById('hd_tresc').focus();
}
function NadpiszTresc(tresc) {
	opener.document.getElementById('hd_tresc').value = str_replace('<br />','\n',tresc);
	
	opener.document.getElementById('sl_d').style.display=''; 
	opener.document.getElementById('tr_clear').style.display='';
	
	KopiujDo1Entera_sl(opener.document.getElementById('hd_tresc').value,'hd_temat');
	ex1(opener.document.getElementById('hd_tresc'));	
	self.close();
	opener.document.getElementById('hd_tresc').focus();
}
function NadpiszTrescA(tresc) {
	opener.document.getElementById('hd_tresc').value = str_replace('<br />','\n',tresc);
	
	//opener.document.getElementById('sl_d').style.display=''; 
	opener.document.getElementById('tr_clear').style.display='';
	
	KopiujDo1Entera_sl(opener.document.getElementById('hd_tresc').value,'hd_temat');
	ex1(opener.document.getElementById('hd_tresc'));	
	self.close();
	opener.document.getElementById('hd_tresc').focus();
}
</script>
<?php } ?>
<?php
$pw = array($_f."/hd_d_osobe_do_kroku.php");
if (array_search($PHP_SELF, $pw)>-1) {
?>
<link type="text/css" rel="stylesheet" media="screen" href="js/jquery/jquery.toChecklist.css" />
<script type="text/javascript" src="js/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="js/jquery/jquery.toChecklist_min.js"></script>
<script>
$().ready(function() {
	$("#userlist").toChecklist({
		showSelectedItems : true,
		submitDataAsArray : true,
		showCheckboxes:true,
		preferIdOverName : false
	});	
	$("#WieleOsobWybor").hide();
});
function pytanie_check1(message) {
	
	if (document.getElementById('userlist_selectedItems').value=='') { 
		alert('Nie wybrałeś żadnej osoby z listy'); document.getElementById('userlist_selectedItems').focus(); return false; 
	}
	
	if (confirm(message)) { 
		
		document.forms.hd_dodaj_osoby.submit(); 
		return true; 
	} else return false; 
}
function pytanie_anuluj(message){ if (confirm(message)) self.close();}
</script>
<?php } ?>
<script>
function ShowWaitingMessage(message, divname) {
	if ((divname==null) || (divname=='')) divname='TrwaLadowanie';
	if ((message==null) || (message=='')) message='Trwa wczytywanie danych';
	if (message=='save') message='Trwa zapisywanie danych';
	
	if (document.body && document.body.offsetWidth) {
	 winW = document.body.offsetWidth;
	 winH = document.body.offsetHeight;
	}
	if (document.compatMode=='CSS1Compat' &&
		document.documentElement &&
		document.documentElement.offsetWidth ) {
	 winW = document.documentElement.offsetWidth;
	 winH = document.documentElement.offsetHeight;
	}
	if (window.innerWidth && window.innerHeight) {
	 winW = window.innerWidth;
	 winH = window.innerHeight;
	}
	var dx = 400;
	if (message.length>30) { dx = dx+50; } else if (message.length>40) { dx = dx+100; } else if (message.length>50) { dx = dx+150; }
	
	var dy = 50;
	var pad = 20;
	var xx = ((winW-dx)/2)-20;
	var yy = ((winH-(dy+pad))/2)-50;
	var bgc = 'black';
	var fc = 'white';
	var bc = 'grey';
	var txt = ''+message+''	;
	var special = '';
	if (divname=='licznik_refresh') special = 'none';
	if (divname=='Saving') special = 'none';
	
	document.write('<div id='+divname+' style="display:'+special+'; position:absolute; left:');
	document.write(xx);
	document.write('px; top:');
	document.write(yy);
	document.write('px; color:'+fc+'; width:'+dx+'px; font-weight:normal; text-align:center; font-size:13px; border: 2px solid '+bc+'; background-color:'+bgc+';padding:'+pad+'px">'+txt+'...<input type=image class=border0 src=img/loader7.gif></div>');
}
function HideWaitingMessage(divname) {
	if (divname==null) divname='TrwaLadowanie';
	if (document.getElementById(divname)) document.getElementById(divname).style.display='none';
}
</script>
<?php
$pw = array($_f."/hd_d_zgloszenie.php",$_f."/hd_d_zgloszenie_s.php", $_f."/hd_o_zgloszenia_zs.php", $_f."/hd_e_zgloszenie_krok.php", $_f."/hd_e_zgloszenie_new.php",$_f."/hd_o_zgloszenia_s.php");
if (array_search($PHP_SELF, $pw)>-1) {
	if ($ZamianaZnakowSpecjalnych==true) { 
?>
	<script>
	function ZamienTekst(i) {
		var str = document.getElementById(i).value;
		str1 = str.replace(/"/g,"`");
		str1 = str1.replace(/\'/g,"`");
		str1 = str1.replace(/\\/g,"\/");
		document.getElementById(i).value = str1;
		return true;
	}
	</script>
<?php } else { ?>
			<script>
			function ZamienTekst(i) {
				return true;
			}
			</script>	
	<?php } ?>
<?php } ?>
<?php
$pw = array($_f."/hd_d_zgloszenie_przypisane.php");
if (array_search($PHP_SELF, $pw)>-1) {
?>
<script>
function filterInput(filterType,evt,allowDecimal,allowCustom){var keyCode,Char,inputField,filter='';var alpha='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';var num='0123456789';if(window.event){keyCode=window.event.keyCode;evt=window.event}else if(evt)keyCode=evt.which;else return true;if(filterType==0)filter=alpha;else if(filterType==1)filter=num;else if(filterType==2)filter=alpha+num;if(allowCustom)filter+=allowCustom;if(filter=='')return true;inputField=evt.srcElement?evt.srcElement:evt.target||evt.currentTarget;if((keyCode==null)||(keyCode==0)||(keyCode==8)||(keyCode==9)||(keyCode==27))return true;Char=String.fromCharCode(keyCode);if((filterType==1)&&(String.fromCharCode(keyCode)==',')){Char='.'}if((filter.indexOf(Char)>-1))return true;else if(filterType==1&&allowDecimal&&(Char=='.')&&inputField.value.indexOf('.')==-1)return true;else return false
}
function DopiszDwukropek(v) {
var r = document.getElementById(v).value.length;
if (r==2) document.getElementById(v).value = document.getElementById(v).value+':';
if (r==5) CheckTime(document.getElementById(v).value);
}
function ZamienTekst(i) {
	var str = document.getElementById(i).value;
	str1 = str.replace(/"/g,"`");
	str1 = str1.replace(/\'/g,"`");
	str1 = str1.replace(/\\/g,"\/");
	document.getElementById(i).value = str1;
	return true;
}
function KopiujDo1Entera(val, dest) {
	var w1 = val.replace("\n","<br>");
	var s1 = w1.search("<br>");
	if (s1>0) { document.getElementById(dest).value=w1.substring(0,s1);	return true; }
	document.getElementById(dest).value=w1;
}
function ex1(val) {
	var text = val.value.replace(/\s+$/g,"") 
	var split = text.split("\n") 
	if (split.length<5) val.rows = split.length+1;
}
function KopiujDo1Entera_sl(val, dest) {
	var w1 = val.replace("\n","<br>");
	var s1 = w1.search("<br>");
	if (s1>0) {
		opener.document.getElementById(dest).value=w1.substring(0,s1);
		return true;
	}
	opener.document.getElementById(dest).value=w1;
}
function WybierzTresc2(tresc) {
	opener.document.getElementById('hd_tresc').value = str_replace('<br />','\n',tresc);
	KopiujDo1Entera_sl(opener.document.getElementById('hd_tresc').value,'hd_temat'); 
	ex1(opener.document.getElementById('hd_tresc'));
	self.close();
	opener.document.getElementById('hd_tresc').focus();
}
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
function pytanie_zatwierdz3(message){ 
	if (CheckTime4(document.getElementById('hdgz').value)!=true) return false;
	
	if (document.getElementById('hdgz').value=='') { 
		alert('Nie podałeś godziny zgłoszenia'); document.getElementById('hdgz').focus(); return false; 
	} else {
		if (CheckTime(document.getElementById('hdgz').value)==false) {
			return false;
		}
	}
	
	if (document.getElementById('hd_tresc').value=='') { 
		alert('Nie podałeś treści zgłoszenia'); document.getElementById('hd_tresc').focus(); return false; 
	}
	
	if (document.getElementById('hd_temat').value=='') { 
		alert('Wygenerowano pusty temat zgłoszenia - usuń pierwszą pustą linię z treści zgłoszenia.'); document.getElementById('hd_tresc').focus(); return false; 
	}
	
	if (confirm(message)) { 
		document.getElementById('content').style.display='none';	
		document.getElementById('submit').style.display='none';		
		document.getElementById('reset').style.display='none';
		document.getElementById('anuluj').style.display='none';
		document.getElementById('Saving').style.display='';
		
		document.forms.hd_dodaj_zgl.submit(); 
		return true; 
	} else return false; 
}
</script>
<?php } ?>
<?php
$pw = array($_f."/hd_z_slownik_tresci2.php");
if (array_search($PHP_SELF, $pw)>-1) {
?>
<script>
function KopiujDo1Entera(val, dest) {
	var w1 = val.replace("\n","<br>");
	var s1 = w1.search("<br>");
	if (s1>0) { document.getElementById(dest).value=w1.substring(0,s1);	return true; }
	document.getElementById(dest).value=w1;
}
function ex1(val) {
	var text = val.value.replace(/\s+$/g,"") 
	var split = text.split("\n") 
	if (split.length<5) val.rows = split.length+1;
}
function KopiujDo1Entera_sl(val, dest) {
	var w1 = val.replace("\n","<br>");
	var s1 = w1.search("<br>");
	if (s1>0) {
		opener.document.getElementById(dest).value=w1.substring(0,s1);
		return true;
	}
	opener.document.getElementById(dest).value=w1;
}
function str_replace (search, replace, subject, count) {
    // *     example 1: str_replace(' ', '.', 'Kevin van Zonneveld');
    // *     returns 1: 'Kevin.van.Zonneveld'
    var i = 0, j = 0, temp = '', repl = '', sl = 0, fl = 0,
            f = [].concat(search),
            r = [].concat(replace),
            s = subject,
            ra = r instanceof Array, sa = s instanceof Array;
    s = [].concat(s);
    if (count) {
        this.window[count] = 0;
    }
    for (i=0, sl=s.length; i < sl; i++) {
        if (s[i] === '') {
            continue;
        }
        for (j=0, fl=f.length; j < fl; j++) {
            temp = s[i]+'';
            repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0];
            s[i] = (temp).split(f[j]).join(repl);
            if (count && s[i] !== temp) {
                this.window[count] += (temp.length-s[i].length)/f[j].length;}
        }
    }
    return sa ? s : s[0];
}
function WybierzTresc2(tresc) {
	opener.document.getElementById('hd_tresc').value = str_replace('<br />','\n',tresc);
	KopiujDo1Entera_sl(opener.document.getElementById('hd_tresc').value,'hd_temat'); 
	ex1(opener.document.getElementById('hd_tresc'));
	self.close();
	opener.document.getElementById('hd_tresc').focus();
}
function ApplyFiltrHD_sl_2(bool) {
	if (bool) {
		var sa = document.getElementById('showall1').value; 
		var pa = document.getElementById('page11').value; 
		var pt = document.getElementById('page12').value; 
		
		var kat = document.getElementById('filtr2').value; 
		var podkat = document.getElementById('filtr3').value; 
		var przyp = document.getElementById('filtr6').value; 
		przyp = przyp.replace(" ","+");
		var rpsite1 = document.getElementById('rpsite').value; 
		var akcja = document.getElementById('akcja').value; 
		self.location='hd_z_slownik_tresci2.php?hd_rps='+rpsite1+'&sa='+sa+'&page='+pa+'&paget='+pt+'&p2='+kat+'&p3='+podkat+'&p6='+przyp+'&akcja=1';
	}
}
function ApplyFiltrHD_sl_3(bool) {
	if (bool) {
		var sa = document.getElementById('showall1').value; 
		var pa = document.getElementById('page11').value; 
		var pt = document.getElementById('page12').value; 
		
		var kat = document.getElementById('filtr2').value; 
		var podkat = document.getElementById('filtr3').value; 
		var przyp = document.getElementById('filtr6').value; 
		przyp = przyp.replace(" ","+");
		var rpsite1 = document.getElementById('rpsite').value; 
		var akcja = document.getElementById('akcja').value; 
		self.location='hd_z_slownik_tresci2.php?hd_rps='+rpsite1+'&sa='+sa+'&page='+pa+'&paget='+pt+'&p2='+kat+'&p3='+podkat+'&p6='+przyp+'&akcja='+akcja+'';
	}
}
</script>
<?php } ?>
<?php 
$pw = array($_f."/d_zadanie.php",$_f."/e_zadanie.php");
if (array_search($PHP_SELF, $pw)>-1) { 
?>
<script>
function OdrzucNiedozwolone(iid, v) {
var o = trimAll(v.toUpperCase());
var l = o.length;
if (l==0) return 0;
var x = 0;

if (o.indexOf(" ")<0) return 1;
if (l<6) return 1;

if  ((o.indexOf("NACZELNIK")>=0) || 
	 (o.indexOf("ASYSTENT")>=0) || 
	 (o.indexOf("AGENT")>=0) || 
	 (o.indexOf("AGENTKA")>=0) || 
	 (o.indexOf("AGENT POCZTOWY")>=0) || 
	 (o.indexOf("CIT")>=0) || 
	 (o.indexOf("EKSPEDYCJA")>=0) || 
	 (o.indexOf("KASA GŁÓWNA")>=0) || 
	 (o.indexOf("KASJER")>=0) || 
	 (o.indexOf("KASJERKA")>=0) || 
	 (o.indexOf("KIEROWNIK")>=0) || 
	 (o.indexOf("KIEROWNIK ZMIANY")>=0) || 
	 (o.indexOf("KONTROLER")>=0) || 
	 (o.indexOf("POCZTA POLSKA")>=0) || 
	 (o.indexOf("PRACOWNIK")>=0) || 
	 (o.indexOf("PRACOWNIK UP")>=0) || 
	 (o.indexOf("PRACOWNIK EKSPEDYCJI")>=0) || 
	 (o.indexOf("PRACOWNIK AGENCJI")>=0) || 
	 (o.indexOf("PRACOWNIK FILII")>=0) || 
	 (o.indexOf("PRACOWNIK FUP")>=0)) return 1;
}
function ZadanieDodaj(message){ 
	
	if (document.getElementById('dzopis').value=='') { 
		alert('Nie podałeś opisu zadania'); document.getElementById('dzopis').focus(); return false; 
	}

	if (document.getElementById('dz_kat').value=='3')  { 
		if (document.getElementById('dz_podkat').value=='G') { 
			if (document.getElementById('sub_podkat_id').options.length==1) {
				alert('Brak projektów do wybrania dla podkategorii (poziom 2)'); document.getElementById('sub_podkat_id').focus(); return false; 
			}
			if ((document.getElementById('sub_podkat_id').options.length>1) && (document.getElementById('sub_podkat_id').options[document.getElementById('sub_podkat_id').options.selectedIndex].value=='')) {
				alert('Nie wybrałeś projektu z listy'); document.getElementById('sub_podkat_id').focus(); return false; 
			}
		}		
	}
	
	if (document.getElementById('dlaHD').checked==true) {
		if (document.getElementById('dz_wc').value=='') { 
			alert('Nie podałeś wykonanych czynności'); document.getElementById('dz_wc').focus(); return false; 
		}
	
		if (document.getElementById('dz_osoba').value=='') { 
			alert('Nie podałeś osoby zgłaszającej'); document.getElementById('dz_osoba').focus(); return false; 
		}
		if (OdrzucNiedozwolone('dz_osoba',document.getElementById('dz_osoba').value)=='1') {
			alert("Wpisano nieprawidłową wartość w polu. Osobę zgłaszającą należy wpisać z imienia i nazwiska");
			document.getElementById('dz_osoba').focus();
			return false;
		}
	
	}
	if (confirm(message)) { 
	//	document.getElementById('content').style.display='none';	
		document.getElementById('submit').style.display='none';		
	//	document.getElementById('reset').style.display='none';
		document.getElementById('anuluj').style.display='none';
	//	document.getElementById('Saving').style.display='';
		
		document.forms.addz.submit(); 
		return true; 
	} else return false; 
}
function ZadanieEdytuj(message){ 
	
	if (document.getElementById('dzopis').value=='') { 
		alert('Nie podałeś opisu zadania'); document.getElementById('dzopis').focus(); return false; 
	}
	
	if (document.getElementById('dz_kat').value=='3')  { 
		if (document.getElementById('dz_podkat').value=='G') { 
			if (document.getElementById('sub_podkat_id').options.length==1) {
				alert('Brak projektów do wybrania dla podkategorii (poziom 2)'); document.getElementById('sub_podkat_id').focus(); return false; 
			}
			if ((document.getElementById('sub_podkat_id').options.length>1) && (document.getElementById('sub_podkat_id').options[document.getElementById('sub_podkat_id').options.selectedIndex].value=='')) {
				alert('Nie wybrałeś projektu z listy'); document.getElementById('sub_podkat_id').focus(); return false; 
			}
		}		
	}
		
	if (document.getElementById('dlaHD').checked==true) {
		if (document.getElementById('dz_wc').value=='') { 
			alert('Nie podałeś wykonanych czynności'); document.getElementById('dz_wc').focus(); return false; 
		}
	
		if (document.getElementById('dz_osoba').value=='') { 
			alert('Nie podałeś osoby zgłaszającej'); document.getElementById('dz_osoba').focus(); return false; 
		}
		if (OdrzucNiedozwolone('dz_osoba',document.getElementById('dz_osoba').value)=='1') {
			alert("Wpisano nieprawidłową wartość w polu. Osobę zgłaszającą należy wpisać z imienia i nazwiska");
			document.getElementById('dz_osoba').focus();
			return false;
		}
	
	}
	if (confirm(message)) { 
	//	document.getElementById('content').style.display='none';	
		document.getElementById('submit').style.display='none';		
	//	document.getElementById('reset').style.display='none';
		document.getElementById('anuluj').style.display='none';
	//	document.getElementById('Saving').style.display='';
		
		document.forms.addz.submit(); 
		return true; 
	} else return false; 
}
</script>
<?php } ?>

<?php $pw = array($_f."/hd_e_zgloszenie_sp.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
<script type="text/javascript" src="js/jquery/jquery-1.6.min.js"></script>
<?php } ?>

<?php 
$pw = array($_f."/d_zadanie.php", $_f."/e_zadanie.php"); 
if (array_search($PHP_SELF, $pw)>-1) { 
	include_once('js/hd_d_zgloszenie_i_s_scripts.php');
	include_once('js/hd_d_zgloszenie_scripts.php');
?>	

<script>
function FillProject(pn) {
var isIE = /*@cc_on!@*/false;
	if (isIE) {
		$('#sub_podkat_id').html($('#dz_podkat_temp').html());
	} else {
		$("#sub_podkat_id").load("projekty.php?id999="+pn+"&rand="+(Math.random()*99999));
	}
	
}

function FillProjectInfo(nr) {
	$("#sub_podkat_id_opis").load("projekty_szczegoly.php?popis="+urlencode(nr)+"&rand="+(Math.random()*99999));
}
	
</script>
<?php } ?>

<?php 
$pw = array($_f."/d_projekt.php",$_f."/e_projekt.php");
if (array_search($PHP_SELF, $pw)>-1) { 
?>
<script>
function ProjektDodaj(message){ 
	if (document.getElementById('dzopis').value=='') { 
		alert('Nie podałeś opisu projektu'); document.getElementById('dzopis').focus(); return false; 
	}
	if (confirm(message)) { 
		document.getElementById('submit').style.display='none';		
		document.getElementById('anuluj').style.display='none';
		document.forms.addz.submit(); 
		return true; 
	} else return false; 
}

function ProjektEdytuj(message){ 
	
	if (document.getElementById('dzopis').value=='') { 
		alert('Nie podałeś opisu projektu'); document.getElementById('dzopis').focus(); return false; 
	}
	if (confirm(message)) { 
		document.getElementById('submit').style.display='none';		
		document.getElementById('anuluj').style.display='none';	
		document.forms.addz.submit(); 
		return true; 
	} else return false; 
}
</script>
<?php } ?>

<?php $pw = array($_f."/hd_g_raport_weryfikacja_zgloszen.php"); if (array_search($PHP_SELF, $pw)>-1) { ?>
<link rel="stylesheet" type="text/css" href="js/anylinkcssmenu/anylinkcssmenu.css" />
<script type="text/javascript" src="js/jquery/jquery-1.6.min.js"></script>

<script type="text/javascript" src="js/anylinkcssmenu/anylinkcssmenu-min.js"></script>
<script type="text/javascript" src="js/anylinkcssmenu/anylink.js"></script>
<script type="text/javascript" src="js/colorlightrow.js"></script>

<script>
function UpdateIloscZaznaczen() {
	var elLength = document.weryfikacja.elements.length;
	
	var count_checked = 0;
	var count_radio = 0;
	
    for (i=0; i<elLength; i++)
    {
        var type = document.weryfikacja.elements[i].type;
		
        if (type=="checkbox" && document.weryfikacja.elements[i].checked && (document.weryfikacja.elements[i].name.substring(0,7)=='markzgl')) count_checked = count_checked + 1;
        if (type=="radio" && document.weryfikacja.elements[i].checked && (document.weryfikacja.elements[i].name.substring(0,7)=='markzgl')) count_radio = count_radio + 1;
		
	}
	
	if (count_checked>=0) {
		document.getElementById('IloscZaznaczonych').innerText=count_checked;	
		document.getElementById('IloscZaznaczonych').textContent=count_checked;
		if (count_checked==0) {
//			if (document.getElementById('ObslugaZgloszen')) document.getElementById('ObslugaZgloszen').style.display='none';
			if (document.getElementById('NapisPrzed')) document.getElementById('NapisPrzed').style.display='none'; 
//			if (document.getElementById('FSPrzypiszDoOsoby'))
//document.getElementById('FSPrzypiszDoOsoby').style.display='none';
		} else {			
//			if (document.getElementById('ObslugaZgloszen')) document.getElementById('ObslugaZgloszen').style.display='';
			if (document.getElementById('NapisPrzed')) document.getElementById('NapisPrzed').style.display=''; 
//			if (document.getElementById('FSPrzypiszDoOsoby'))
//document.getElementById('FSPrzypiszDoOsoby').style.display='inline';
		}
	}
	
}
function OdznaczRadioButton(obj) {
  for (var i=0; i < obj.length; i++) {
	if (obj[i].type=='radio') obj[i].checked = false;
  }
  UpdateIloscZaznaczen();
}
function OdznaczWszystkieCheckboxy(obj) {
	for(var i=0,l=obj.length; i<l; i++) {	
		if (obj[i].type == 'checkbox') {
			obj[i].checked=false;
		}
	}
	UpdateIloscZaznaczen();
}
function MarkCheckboxes(akcja) {
	for(var i=0,l=document.weryfikacja.elements.length; i<l; i++) {
		
		if (document.weryfikacja.elements[i].type == 'checkbox') {
			if (akcja=='odwroc') {
document.weryfikacja.elements[i].checked=document.weryfikacja.elements[i].checked?false:true;
			} else if (akcja=='zaznacz') {
document.weryfikacja.elements[i].checked=true;
			} else if (akcja=='odznacz') {
document.weryfikacja.elements[i].checked=false;
			}
		}	
	}
	UpdateIloscZaznaczen();
	OdznaczRadioButton(document.weryfikacja.markzgl);
}

function SelectTRById(v) {
	UpdateIloscZaznaczen();	
}
function OznaczJakoSprawdzone(x) {
	//SetCookie('byly_zmiany', '0');
	var elLength = document.weryfikacja.elements.length;
	var count_checked = 0;
	var params = "nr=";
	var count_radio = 0;
	var params2 = "nr=";
	
    for (i=0; i<elLength; i++)
    {
        var type = document.weryfikacja.elements[i].type;
        if (type=="checkbox" && document.weryfikacja.elements[i].checked && (document.weryfikacja.elements[i].name.substring(0,7)=='markzgl')) {
			count_checked = count_checked + 1;
			params += document.weryfikacja.elements[i].value + ",";
        }
        if (type=="radio" && document.weryfikacja.elements[i].checked && (document.weryfikacja.elements[i].name.substring(0,7)=='markzgl')) {
			count_radio = count_radio + 1;
			params2 += document.weryfikacja.elements[i].value + ",";
        }	
	}
	
	
	if (count_checked>0) {
		document.getElementById('IloscZaznaczonych').innerText=count_checked;
	} else if (count_radio>0) {
		document.getElementById('IloscZaznaczonych').innerText=count_radio;
	}	
	var dl = params.length;
	var dl2 = params2.length;
	dl--;
	dl2--;
	
	if ((dl==2) && (dl2==2)) {
		alert('Nie zaznaczyłeś żadnego zgłoszenia');
		return false;
	}
	//ClearCookie('saved_seryjna_obsluga_zgloszen', '1');
	var okno_max = 0;
	
	if (dl!=2) { 
		var params1 = params.substring(0,dl); 
		var lokacja1 = "hd_o_zgloszenia_potw_spr_s.php?set="+x+"&";		
		var lokacja2 = "hd_o_zgloszenia_potw_spr_s.php?set="+x+"&";
		
		if (count_checked==1) {
			var lokacja_1 = params1 + "&cnt=" + count_checked + "&clear_cookie=1";
			lokacja = lokacja2 + lokacja_1;
			okno_max = 0;
		} else {
			var lokacja_1 = params1 + "&cnt=" + count_checked + "&clear_cookie=1";
			lokacja = lokacja1 + lokacja_1;
			okno_max = 0;
		}
		
	} else if (dl2!=2) { 
			var params1 = params2.substring(0,dl2); 
			var lokacja = "hd_o_zgloszenia_potw_spr_s.php?set="+x+"&" + params1 + "&cnt=" + count_radio + "&clear_cookie=1";
			okno_max = 0;
			}
			
	lokacja = lokacja + '&donotreloadparent=1';
	if (okno_max==0) {
		var x = 500;
		var y = 50;
	} else {
		var x=window.screen.availWidth-5;
		var y=window.screen.availHeight-30; 	
	}
	
	if (window.screen) {
		var ah = screen.availHeight - 30;
		var aw = screen.availWidth - 10;
		
		var xx=(aw-x) / 2;
		xx=Math.round(xx);
		var yy=(ah-y) / 2;
		yy=Math.round(yy);
	}
	
	var opcje="scrollbars=yes, width="+x+", innerWidth="+x+", height="+y+", innerHeight="+y+", top="+yy+", screenX="+xx+", screenY="+yy+", left="+xx;
	
	NewWindowOpen = window.open(lokacja, "eSerwisOJS", opcje);
	NewWindowOpen.focus;
	//return false;
}

function ShowAllDetails(v) {
	var elLength = document.weryfikacja.elements.length;

    for (i=0; i<elLength; i++) {
        var type = document.weryfikacja.elements[i].type;
		//alert(type+' ,'+document.weryfikacja.elements[i].name.substring(0,3));
        if (type=="hidden" && (document.weryfikacja.elements[i].name.substring(0,3)=='ID_')) {
			var lp = document.weryfikacja.elements[i].name.substring(3,100);
			var nr = document.weryfikacja.elements[i].value;
			
			if (v==true) {
				$('#kroki'+lp+'').load('hd_zgl_detail2.php?id='+nr+'');
				$('#kroki'+lp+'').show();
			} else {
				$('#kroki'+lp+'').hide(); 
				$('#sm_kroki_'+nr+'').show(); 
				$('#hm_kroki_'+nr+'').hide();
			}
        }
		
	}
}


</script>
<?php } ?>

<?php 
$pw = array($_f."/hd_check_ping.php");
if (array_search($PHP_SELF, $pw)>-1) { 
?>
<script type="text/javascript" src="js/jquery/jquery.countdown.js"></script>
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
</script>
<?php } ?>

<?php 
$pw = array($_f."/d_kb_pytanie.php", $_f."/e_kb_pytanie.php");
if (array_search($PHP_SELF, $pw)>-1) { 
?>
<script>

	function pytanie_dodaj_do_kb(message) {

		if (document.getElementById('dnp').value=='') {
			alert('Nie podano treści nowego wątku'); document.getElementById('dnp').focus(); return false; 
		}
	
		if (document.getElementById('dnpk').options[document.getElementById('dnpk').options.selectedIndex].text=='') { 
			alert('Nie wybrałeś kategorii wątku'); document.getElementById('dnpk').focus(); return false; 
		}
	
		if (confirm(message)) { 
			document.getElementById('submit').style.display='none';
			document.forms.add.submit(); 
			return true; 
		} else return false; 
	}
</script>
<?php } ?>

<?php 
$pw = array($_f."/d_kb_odpowiedz.php");
if (array_search($PHP_SELF, $pw)>-1) { 
?>
<script>

	function pytanie_dodaj_odp_do_kb(message) {
		if (document.getElementById('dno').value=='') {
			alert('Nie podano treści odpowiedzi do wątku'); document.getElementById('dno').focus(); return false; 
		}
		
		if (confirm(message)) { 
			document.getElementById('submit').style.display='none';
			document.forms.add.submit(); 
			return true; 
		} else return false; 
	}
</script>
<?php } ?>

<?php $pw = array($_f."/main.php"); 
if ($_REQUEST[action]='hdgro') { 

if (array_search($PHP_SELF, $pw)>-1) { ?>

<script type="text/javascript" src="js/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="js/jquery/jquery.autocomplete.js"></script>
<link rel="stylesheet" type="text/css" href="js/jquery/jquery.autocomplete.css" />

<script>
$().ready(function() {
	$("#nk").autocomplete("hd_get_up_list.php?filia=<?php echo $es_filia; ?>", {
		width: 360,
		max:150,
		matchContains: true,
		mustMatch: false,
		minChars: 1,
		selectFirst: false
	});	
});
</script>
<?php } 
} 
?>

<?php $pw = array($_f."/e_zadanie.php"); 
if (array_search($PHP_SELF, $pw)>-1) { ?>
<script>
	function AddToList(listname, list_opis, list_value, bool1, bool2) {
		listname.options[listname.options.length] = new Option(""+list_opis+"",""+list_value+"",bool1, bool2);
	}
	function MakePodkategoriaList(o) {
		if (document.getElementById('dz_kat')) {
			var kateg_select = document.getElementById('dz_kat');
		}
		
		if (document.getElementById('dz_podkat')) {
			var ps_ = document.getElementById('dz_podkat');
			ps_.options.length=0;
		}

		if (document.getElementById('sub_podkat_id')) {
			var sps = document.getElementById('sub_podkat_id');
			sps.options.length=0;
		}
		
		if (o == "7") {
			ps_.options[ps_.options.length] = new Option("Konserwacja sprzętu","8",true,true);
			ps_.disabled=false;
			
			if (document.getElementById('status_id')) {
				ss.options[ss.options.length] = new Option("nowe","1",true,true);
				ss.options[ss.options.length] = new Option("rozpoczęte","3",false,false);
				ss.options[ss.options.length] = new Option("rozpoczęte - nie zakończone","7",false,false);
				ss.options[ss.options.length] = new Option("zamknięte","9",false,false);
				ss.disabled=false;
			}

			AddToList(sps,'Brak','',true,true);
			
		}
		
		if (o == "1") {
			ps_.options[ps_.options.length] = new Option("Brak","1",true,true);
			ps_.disabled=false;

			AddToList(sps,'Sprzęt','Sprzęt',false,false);
			AddToList(sps,'SP2000','SP2000',true,true);
			AddToList(sps,'Inne','Inne',false,false);
			
		}
		if (o == "2") {
			ps_.options[ps_.options.length] = new Option("dowolna","",true,true);
			ps_.options[ps_.options.length] = new Option("Serwer","4",false,false);
			ps_.options[ps_.options.length] = new Option("Stacja robocza","3",false,false);
			ps_.options[ps_.options.length] = new Option("Urządzenia peryferyjne","9",false,false);
			ps_.options[ps_.options.length] = new Option("Oprogramowanie - wsparcie użytkownika","2",false,false);
			ps_.options[ps_.options.length] = new Option("WAN/LAN","0",false,false);
			ps_.options[ps_.options.length] = new Option("Oprogramowanie - problemy techniczne","7",false,false);
			ps_.disabled=false;			
		}
		
		// awaria krytyczna
		if (o == "6") {
			ps_.options[ps_.options.length] = new Option("dowolna","",true,true);
			ps_.options[ps_.options.length] = new Option("Serwer","4",false,false);
			ps_.options[ps_.options.length] = new Option("Stacja robocza","3",false,false);
			ps_.options[ps_.options.length] = new Option("Urządzenia peryferyjne","9",false,false);
			ps_.options[ps_.options.length] = new Option("WAN/LAN","0",false,false);
			ps_.options[ps_.options.length] = new Option("Oprogramowanie - problemy techniczne","7",false,false);		
			ps_.disabled=false;
		}
		
		if (o == "3") {
			ps_.options[ps_.options.length] = new Option("dowolna","",true,true);
			ps_.options[ps_.options.length] = new Option("Serwer","4",false,false);
			ps_.options[ps_.options.length] = new Option("Stacja robocza","3",false,false);
			ps_.options[ps_.options.length] = new Option("Urządzenia peryferyjne","9",false,false);
			ps_.options[ps_.options.length] = new Option("Oprogramowanie - wsparcie użytkownika","2",false,false);
			ps_.options[ps_.options.length] = new Option("Oprogramowanie - problemy techniczne","7",false,false);
			ps_.options[ps_.options.length] = new Option("Aktualizacje oprogramowania","6",false,false);
			ps_.options[ps_.options.length] = new Option("Kopie bezpieczeństwa","H",false,false);
			ps_.options[ps_.options.length] = new Option("Domena","I",false,false);
			ps_.options[ps_.options.length] = new Option("Alarmy","E",false,false);
			ps_.options[ps_.options.length] = new Option("WAN/LAN","0",false,false);
			ps_.options[ps_.options.length] = new Option("Inne","D",false,false);
			ps_.options[ps_.options.length] = new Option("Otwarcie placówki pocztowej","A",false,false);
			ps_.options[ps_.options.length] = new Option("Przeniesienie placówki pocztowej","B",false,false);
			ps_.options[ps_.options.length] = new Option("Zamknięcie placówki pocztowej","C",false,false);			
			ps_.options[ps_.options.length] = new Option("Prace administracyjno-sprawozdawcze","F",false,false);			
			ps_.disabled=false;
		}
		
		if (o == "4") {
			ps_.options[ps_.options.length] = new Option("dowolna","",true,true);
			ps_.options[ps_.options.length] = new Option("Inne","D",false,false);
			ps_.options[ps_.options.length] = new Option("Otwarcie placówki pocztowej","A",false,false);
			ps_.disabled=false;
		}
		if (o == "5") {
			ps_.options[ps_.options.length] = new Option("Brak","1",true,true);
			ps_.disabled=false;
		}	
		
	}
</script>
<?php } ?>

<?php if (($es_nr==7) && ($es_filia==1) && ($es_skrot=='SI') && ($templates_funny)) { ?><style>body{background: url(img/3q.gif);}</style><?php } ?>
<?php if (($es_nr==8) && ($es_filia==1) && ($es_skrot=='SI') && ($templates_funny)) { ?><style>body{background: url(img/3q.gif);}</style><?php } ?>
<?php if (($es_nr==52) && ($es_filia==1) && ($es_skrot=='SI') && ($templates_funny)) { ?><style>body{background: url(img/3q.gif);}</style><?php } ?>
<?php if (($es_nr==2) && ($es_filia==1) && ($es_skrot=='SI') && ($templates_funny)) { ?><style>body{background: url(img/3q.gif);}</style><?php } ?>

<?php if (($es_nr==50) && ($es_filia==2) && ($es_skrot=='LD') && ($templates_funny)) { ?><style>body{background: url(img/hellokitty.gif);}</style><?php } ?>
<?php if (($es_nr==34) && ($es_filia==2) && ($es_skrot=='LD') && ($templates_funny)) { ?><style>body{background: url(img/bat.gif);}</style><?php } ?>
<?php if (($es_nr==36) && ($es_filia==2) && ($es_skrot=='LD') && ($templates_funny)) { ?><style>body{background: url(img/bat.gif);}</style><?php } ?>
<?php if (($es_nr==30) && ($es_filia==2) && ($es_skrot=='LD') && ($templates_funny)) { ?><style>body{background: url(img/lp.gif);}</style><?php } ?>