<?php
if ($ewidencja) {
	header("Location: ".$linkdostrony."e_protokol_z_ewidencji.php?c_1=$c_1&c_2=$c_2&c_3=$c_3&c_4=$c_4&c_5=$c_5&c_6=$c_6&c_7=$c_7&c_8=$c_8&opis_uszkodzenia=".$opis_uszkodzenia."&wykonane_czynnosci=".$wykonane_czynnosci."&uwagi=".$uwagi."&tup=".$tup."");
}
include_once('header_simple.php'); 
include_once('cfg_helpdesk.php'); 

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo "$nazwa_aplikacji"; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css" media="print">
.hideme { display: none; }
</style>
<style type="text/css">
<!--
.style8{font-family:Arial,Helvetica,sans-serif;font-size:16px;}
.style88{font-family:Arial,Helvetica,sans-serif;font-size:13px;}
.style888{font-family:Arial,Helvetica,sans-serif;font-size:10px;}
.style8x{font-family:Arial,Helvetica,sans-serif;font-size:15px;}
.style25{font-family:Arial,Helvetica,sans-serif;}
.style2525{font-family:Arial,Helvetica,sans-serif;font-size:12px;}
.style32d{border-top-style:solid;border-right-style:solid;border-bottom-style:solid;border-left-style:solid;border-top-color:#FFFFFF;border-right-color:#FFFFFF;border-bottom-color:#FFFFFF;border-left-color:#FFFFFF;padding:0px;margin:0px;background-position:center;font-family:Arial,Helvetica,sans-serif;background-color:#FFFFFF;}
.style34{font-family:Arial,Helvetica,sans-serif;font-weight:bold;border-top-color:#000000;border-right-color:#000000;border-bottom-color:#000000;border-left-color:#000000;background-color:#FFFFFF;border-width:0px;font-size:16px;}
.style34x{font-family:Arial,Helvetica,sans-serif;font-weight:bold;border-top-color:#000000;border-right-color:#000000;border-bottom-color:#000000;border-left-color:#000000;background-color:#FFFFFF;border-top-width:0px;border-right-width:0px;border-bottom-width:0px;border-left-width:0px;font-size:15px;}
.style32{padding:0px;margin:0px;background-position:center;font-family:Arial,Helvetica,sans-serif;border:1px solid #000000;}
.style42{padding:0px;margin:0px;background-position:center;font-family:Arial,Helvetica,sans-serif;border:1px solid #000000;padding-top:2px;}
.style43{padding:0px;margin:0px;background-position:center;font-family:Arial,Helvetica,sans-serif;border-top-width:1px;border-right-width:1px;border-bottom-width:1px;border-left-width:1px;border-top-style:solid;border-right-style:none;border-bottom-style:solid;border-left-style:none;border-top-color:#000000;border-right-color:#000000;border-bottom-color:#000000;border-left-color:#000000;padding-top:2px;}
.style44{padding:0px;margin:0px;background-position:center;font-family:Arial,Helvetica,sans-serif;border-top-width:1px;border-right-width:1px;border-bottom-width:1px;border-left-width:1px;border-top-style:none;border-right-style:solid;border-bottom-style:solid;border-left-style:solid;border-top-color:#000000;border-right-color:#000000;border-bottom-color:#000000;border-left-color:#000000;padding-top:1px;}
.style46{margin:0px;background-position:center;font-family:Arial,Helvetica,sans-serif;border-top-width:1px;border-right-width:1px;border-bottom-width:1px;border-left-width:1px;border-top-style:solid;border-right-style:none;border-bottom-style:none;border-left-style:solid;border-top-color:#000000;border-right-color:#000000;border-bottom-color:#000000;border-left-color:#000000;padding-top:2px;padding-right:0px;padding-bottom:2px;padding-left:0px;}
.style47{margin:0px;background-position:center;font-family:Arial,Helvetica,sans-serif;border-top-width:1px;border-right-width:1px;border-bottom-width:1px;border-left-width:1px;border-top-style:solid;border-right-style:solid;border-bottom-style:none;border-left-style:none;border-top-color:#000000;border-right-color:#000000;border-bottom-color:#000000;border-left-color:#000000;padding-top:2px;padding-right:0px;padding-bottom:2px;padding-left:3px;font-size:13px;}
.style47null{margin:0px;background-position:center;font-family:Arial,Helvetica,sans-serif;border-top-width:1px;border-right-width:1px;border-bottom-width:1px;border-left-width:1px;border-top-style:none;border-right-style:none;border-bottom-style:none;border-left-style:none;border-top-color:#000000;border-right-color:#000000;border-bottom-color:#000000;border-left-color:#000000;padding-top:2px;padding-right:0px;padding-bottom:2px;padding-left:0px;font-size:13px;}
.style45{margin:0px;background-position:center;font-family:Arial,Helvetica,sans-serif;border-top-width:1px;border-right-width:1px;border-bottom-width:1px;border-left-width:1px;border-top-style:none;border-right-style:solid;border-bottom-style:solid;border-left-style:none;border-top-color:#000000;border-right-color:#000000;border-bottom-color:#000000;border-left-color:#000000;padding-top:2px;padding-right:0px;padding-bottom:2px;padding-left:4px;}
.style50{margin:0px;background-position:center;font-family:Arial,Helvetica,sans-serif;border-top-width:1px;border-right-width:1px;border-bottom-width:1px;border-left-width:1px;border-top-style:solid;border-right-style:solid;border-bottom-style:none;border-left-style:solid;border-top-color:#000000;border-right-color:#000000;border-bottom-color:#000000;border-left-color:#000000;padding-top:3px;padding-right:3px;padding-bottom:3px;padding-left:3px;font-size:14px;font-weight:bold;}
.style51{margin:0px;background-position:center;font-family:Arial,Helvetica,sans-serif;border-top-width:1px;border-right-width:1px;border-bottom-width:1px;border-left-width:1px;border-top-style:none;border-right-style:solid;border-bottom-style:none;border-left-style:solid;border-top-color:#000000;border-right-color:#000000;border-bottom-color:#000000;border-left-color:#000000;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:3px;font-size:14px;font-weight:bold;}
.style36{font-size:14px;}
.style37{font-size:14;}
.style46a{margin:0px;background-position:center;font-family:Arial,Helvetica,sans-serif;border-top-width:1px;border-right-width:1px;border-bottom-width:1px;border-left-width:1px;border-top-style:none;border-right-style:none;border-bottom-style:none;border-left-style:solid;border-top-color:#000000;border-right-color:#000000;border-bottom-color:#000000;border-left-color:#000000;padding-top:2px;padding-right:0px;padding-bottom:2px;padding-left:0px;}
.style47lr{margin:0px;background-position:center;font-family:Arial,Helvetica,sans-serif;border-top-width:1px;border-right-width:1px;border-bottom-width:1px;border-left-width:1px;border-top-style:none;border-right-style:solid;border-bottom-style:none;border-left-style:solid;border-top-color:#000000;border-right-color:#000000;border-bottom-color:#000000;border-left-color:#000000;padding-top:2px;padding-right:0px;padding-bottom:2px;padding-left:0px;font-size:13px;}
.style47d,.style47a,.style47b{margin:0px;background-position:center;font-family:Arial,Helvetica,sans-serif;border-top-width:1px;border-right-width:1px;border-bottom-width:1px;border-left-width:1px;border-top-style:none;border-right-style:solid;border-bottom-style:none;border-left-style:none;border-top-color:#000000;border-right-color:#000000;border-bottom-color:#000000;border-left-color:#000000;padding-top:2px;padding-right:0px;padding-bottom:2px;padding-left:0px;font-size:13px;}
h3 {font-size:small; font-style:Tahoma; font-weight:bold;padding-top:10px;padding-bottom:10px;margin-top:0px;margin-bottom:5px;text-align:center;background:#79FF7A;color:Black;display: block; border: 1px solid #00A601;}
-->
</style>

<title>Untitled Document</title>
<style type="text/css">
.hideme { display: normal; }
</style>

<Script Language=JavaScript>
<!-- Hide
function rusure() {
if (confirm("Czy napewno chcesz zamknąć wygenerowany protokół ?\n\n!!! Pamiętaj o wydrukowaniu protokołu !!!\n"))
	{ 
	window.close();
return true;
	}
else {
	
      return false; }
   
}
// end hide -->

function newWindow_r(x,y,_c){if(window.screen){var ah=screen.availHeight-30;var aw=screen.availWidth-10;var xx=(aw-x)/2;xx=Math.round(xx);var yy=(ah-y)/2;yy=Math.round(yy);}var _11="scrollbars=yes, width="+x+", innerWidth="+x+", height="+y+", innerHeight="+y+", top="+yy+", screenX="+xx+", screenY="+yy+", left="+xx;var _12="eSerwis"+Math.round(Math.random()*10000);neww=window.open(_c,_12,_11);neww.focus();}


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

function ShowWaitingMessage(message, divname) {

	if ((divname==null) || (divname=='')) divname='TrwaLadowanie';
	if ((message==null) || (message=='')) message='Trwa wczytywanie danych...';

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
	document.write('px; color:'+fc+'; width:'+dx+'px; font-family:tahoma; font-weight:normal; text-align:center; font-size:13px; border: 2px solid '+bc+'; background-color:'+bgc+';padding:'+pad+'px">'+txt+'...<input type=image class=border0 src=img/loader7.gif></div>');
}

function HideWaitingMessage(divname) {
	if (divname==null) divname='TrwaLadowanie';
	if (document.getElementById(divname)) document.getElementById(divname).style.display='none';
}

</script>
</head>

<body>

<div class="hideme" align="center">
<a href="#" style="vertical-align:bottom; cursor:hand" onClick="window.print(); 
<?php if (($source=='naprawy-zwrot') && ($_REQUEST[from]=='') && ($_REQUEST[hd_zgl_nr]>0)) { ?>
	if (confirm('Czy przejść do obsługi zgłoszenia nr <?php echo $_REQUEST[hd_zgl_nr]; ?> ?')) { self.close(); newWindow_r(800,600,'hd_o_zgloszenia.php?action=obsluga&id=<?php echo $_REQUEST[hd_zgl_nr]; ?>&nr=<?php echo $_REQUEST[hd_zgl_nr]; ?>'); return false; }
<?php } ?>
"><img src="img/drukuj_protokol.jpg" border="0"></a>

<?php
if (($readonly!=1) || ($es_prawa=='9')) {
?>

<?php

if ($clear) {
	$dzien		= Date('d');
	$miesiac	= Date('m');
	$rok		= Date('Y');
}

if ($submit!='pusty') {
	if (($source!='towary-sprzedaz') || 
		($source!='naprawy-zwrot')
		)
	{
	
	$HD_nr_zgl = $_REQUEST[hd_zgl_nr];	
	
?><script>ShowWaitingMessage('Trwa zapisywanie danych...', 'Saving1');</script>
<?php ob_flush(); flush();
	
?>

<a style="vertical-align:bottom" href="utworz_protokol.php?hd_zgl_nr=<?php echo $HD_nr_zgl;?>&view=<?php echo $_REQUEST[view]; ?>&pnr=<?php echo "$pnr";?>&dzien=<?php echo "$_REQUEST[dzien]"; ?>&miesiac=<?php echo "$_REQUEST[miesiac]"; ?>&rok=<?php echo "$_REQUEST[rok]"; ?>&c_1=<?php echo "$c_1"; ?>&c_2=<?php echo "$c_2"; ?>&c_3=<?php echo "$c_3"; ?>&c_4=<?php echo "$c_4"; ?>&c_5=<?php echo "$c_5"; ?>&c_6=<?php echo "$c_6"; ?>&c_7=<?php echo "$c_7"; ?>&c_8=<?php echo "$c_8"; ?>&up=<?php echo urlencode($up); ?>&tup=<?php echo urlencode($_REQUEST[tup]); ?>&nazwa_urzadzenia=<?php echo urlencode($nazwa_urzadzenia); ?>&sn_urzadzenia=<?php echo urlencode($sn_urzadzenia); ?>&ni_urzadzenia=<?php echo urlencode($ni_urzadzenia); ?>&opis_uszkodzenia=<?php echo urlencode($_REQUEST[opis_uszkodzenia]);?>&wykonane_czynnosci=<?php echo urlencode($wykonane_czynnosci); ?>&uwagi=<?php echo urlencode($uwagi); ?>&format_nr=<?php echo "$format_nr"; ?>&imieinazwisko=<?php echo urlencode($imieinazwisko); ?>&wersjap=<?php echo "$wersjap";?>&unr=<?php echo $unr_session;?>&state=<?php echo "$_REQUEST[state]"; ?>&source=<?php echo $_REQUEST[source]; ?>&state=<?php echo $_REQUEST[state];?>&popraw=1&wstecz=<?php echo $_REQUEST[wstecz1];?>&sz=<?php echo $_REQUEST[sz]; ?>&id=<?php echo $_REQUEST[id]; ?>&nowy=<?php echo $_REQUEST[nowy];?>&nazwa_sprzetu=<?php echo urlencode($_REQUEST[nazwa_sprzetu]);?>&sn_sprzetu=<?php echo urlencode($_REQUEST[sn_sprzetu]);?>&pn=<?php echo $_REQUEST[pn];?>&szid=<?php echo $_REQUEST[szid];?>&status=<?php echo $_REQUEST[status];?>&uid=<?php echo $_REQUEST[uid];?>&serwisowy=<?php echo $_REQUEST[serwisowy];?>&ewid=<?php echo $_REQUEST[ewid];?>&nierobzapytan=1&antyF5=<?php echo $_REQUEST[antyF5];?>&obzp=<?php echo $_REQUEST[obzp]; ?>&tdata=<?php echo $_REQUEST[pdata]; ?>&tid=<?php echo $_REQUEST[tid]; ?>&nzpdb=<?php echo $_REQUEST[nzpdb]; ?>&odswiez=<?php echo $odswiez;?>&new_upid=<?php echo $_REQUEST[new_upid]; ?>&typ_urzadzenia=<?php echo $_REQUEST[typ_urzadzenia];?>&model_urzadzenia=<?php echo $_REQUEST[model_urzadzenia];?>&ewid_id=<?php echo $_REQUEST[ewid_id];?>&zestaw=<?php echo $_REQUEST[zestaw];?>&zid=<?php echo $_REQUEST[zid];?>&f=<?php echo $_REQUEST[f];?>&pozid=<?php echo $_REQUEST[pozid];?>&trodzaj=<?php echo $_REQUEST[trodzaj];?>&edit_towar=<?php echo $_REQUEST[edit_towar];?>&edit_zestaw=<?php echo $_REQUEST[edit_zestaw];?>&hd_nr=<?php echo $_REQUEST[hd_nr];?>&quiet=<?php echo $_REQUEST[quiet]; ?>&dodajwymianepodzespolow=<?php echo $_REQUEST[dodajwymianepodzespolow]; ?>&naprawaid=<?php echo $_REQUEST[naprawaid];?>&from=<?php echo $_REQUEST[from];?>&PNZSS=<?php echo $_REQUEST[PNZSS];?>"><img src="img/popraw.jpg" border="0"></a>

<?php 
 }
}	// pomiń poprawki
}	
?>
<?php if (($source=='naprawy-przyjecie') && ($_REQUEST[tresc_zgl]=='')) { ?>
	<a href="#" style="cursor:hand;vertical-align:bottom" onClick="if (opener) opener.location.reload(true); self.close();"><img src="img//zamknij_protokol.jpg" border="0"></a>
<?php } else { ?>
	<a href="#" style="cursor:hand;vertical-align:bottom" onClick="
	
	<?php if (($source=='naprawy-zwrot') && ($_REQUEST[from]=='') && ($_REQUEST[hd_zgl_nr]>0)) { ?>
	if (confirm('Czy przejść do obsługi zgłoszenia nr <?php echo $_REQUEST[hd_zgl_nr]; ?> ?')) { self.close(); newWindow_r(800,600,'hd_o_zgloszenia.php?action=obsluga&id=<?php echo $_REQUEST[hd_zgl_nr]; ?>&nr=<?php echo $_REQUEST[hd_zgl_nr]; ?>'); return false; } else { self.close(); }
	<?php } else { echo "self.close();"; } ?>
	
	"><img src="img//zamknij_protokol.jpg" border="0"></a>
<?php } ?>
<hr />
</div>

<?php
	if ($source=='towary-sprzedaz') { include('inc_encrypt.php'); }
	if ($source=='naprawy-zwrot') { include('inc_encrypt.php'); }
?>

<?php

if ($_REQUEST[new_upid]=="") { 
	$put_up="-";
} else { 
	//if ($up=="") { if ($_REQUEST[source]=='magazyn-zwrot') { $put_up=$_REQUEST[tup]; $up=$put_up;} else $put_up="-"; } else { $put_up=$up; }
	// ustal pełną nazwę komorki (pion + nazwa)
	$result = mysql_query("SELECT up_id,up_nazwa,up_pion_id,up_umowa_id,pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (up_active=1) and (belongs_to=$es_filia) and (serwis_komorki.up_id=$_REQUEST[new_upid]) LIMIT 1", $conn) or die($k_b);
	list($temp_id,$temp_nazwa,$temp_pion_id,$temp_umowa_id,$temp_pionnazwa)=mysql_fetch_array($result);

	$put_up=$temp_pionnazwa." ".$temp_nazwa;
}

// print_r($_REQUEST);

if ($nazwa_urzadzenia=="") {$put_nazwa_urzadzenia="-";}  else { $put_nazwa_urzadzenia=$nazwa_urzadzenia; }
if ($sn_urzadzenia==""){ $put_sn_urzadzenia="-";} else { $put_sn_urzadzenia=$sn_urzadzenia; }
if ($ni_urzadzenia=="") {$put_ni_urzadzenia="-";} else { $put_ni_urzadzenia=$ni_urzadzenia; }
if ($opis_uszkodzenia==""){ $put_opis_uszkodzenia="-";} else { $put_opis_uszkodzenia=$opis_uszkodzenia; }
if ($wykonane_czynnosci==""){ $put_wykonane_czynnosci="-";} else { $put_wykonane_czynnosci=$wykonane_czynnosci; }
if ($uwagi=="") {$put_uwagi="brak";} else { $put_uwagi=$uwagi; }

//echo "$pdata";

if ($popraw!=1) {
	$rok		= substr($pdata,0,4);
	$miesiac	= substr($pdata,5,2);
	$dzien		= substr($pdata,8,2);
}

if ($obzp==1) {
$rok		= substr($pdata,0,4);
$miesiac	= substr($pdata,5,2);
$dzien		= substr($pdata,8,2);
}

//if ($submit=="Generuj") {
	$rok		= substr($pdata,0,4);
	$miesiac	= substr($pdata,5,2);
	$dzien		= substr($pdata,8,2);
//}

/*
echo "zapis  : $zapisz<br />";
echo "wersja : $wersjap<br />";
echo "submit : $submit<br />";
echo "czysty : $czysty<br />";

echo "c1 : $c_1<br />";
echo "c2 : $c_2<br />";
echo "c3 : $c_3<br />";
echo "c4 : $c_4<br />";
echo "c5 : $c_5<br />";
echo "c6 : $c_6<br />";
echo "c7 : $c_7<br />";
echo "c8 : $c_8<br />";
*/

//echo "source = ".$source;

$username = $currentuser;
$format_nr=$es_skrot."/".$_REQUEST[miesiac]."/".$_REQUEST[rok];
$numerprotokolu='';
if ($pnr=='') $numerprotokolu='';
if (($pnr!='') && (strlen($pnr)<3)) {
	$numerprotokolu=$pnr."/".$format_nr;
	//$numerprotokolu=$pnr;
} else { $numerprotokolu=$pnr; }

//echo "numerprotokolu = $numerprotokolu<br />";

if ($dzien=='') $dzien=$_REQUEST[dzien];
if ($miesiac=='') $miesiac=$_REQUEST[miesiac];
if ($rok=='') $rok=$_REQUEST[rok];

if ($odswiez==1) $_SESSION[unr_session]=$unr;

if ($imieinazwisko!='') $username=$imieinazwisko;

if ($czysty) {
	$clear 	= 1;
	$submit	= 'ok';
} else $clear = 0;

if (($wersjap==2) && ($submit)) {

if ($submit!='pusty') {

if ($_REQUEST[unr]!='') {
	$sql = "SELECT * FROM $dbname.serwis_protokoly_historia WHERE (protokol_unique='$_REQUEST[unr]')";
	$result = mysql_query($sql, $conn) or die($k_b);
	$dane = mysql_fetch_array($result);
	$prid = $dane['protokol_id'];
	$count_rows = mysql_num_rows($result);
} else $count_rows=0;

$akcja = '';

//if ($count_rows==0) {

if ($_REQUEST[choosefromewid]=='1') {
//	$_SESSION[protokol_dodany_do_bazy]=0;
	//$_REQUEST[source]='recznie';
}


if ($_SESSION[protokol_dodany_do_bazy]!=1) {
	
	$unr='';
	$litery=array('a','b','c','d','e','f','g','h','i','j');
	for ($q=1;$q<26;$q++) { $unr.= rand(0,9); $unr.=$litery[rand(0,9)]; }
	
	session_register('unr_session');	$_SESSION[unr_session]=$unr;
	
	// sprawdzenie czy już nie zapisano protokołu do tabeli (zabezpieczenie przed klawiszem F5
/*	
		$result99 = mysql_query("SELECT protokol_d, protokol_m, protokol_r, protokol_up, protokol_nazwa_urzadzenia, protokol_sn_urzadzenia,protokol_wykonane_czynnosci, protokol_uwagi, protokol_kto, belongs_to FROM $dbname.serwis_protokoly_historia ORDER BY protokol_id  DESC LIMIT 1", $conn) or die($k_b);
		list($temp_d,$temp_m,$temp_r,$temp_up,$temp_nu,$temp_sn,$temp_wcz,$temp_u,$temp_kto,$temp_belongs)=mysql_fetch_array($result99);
		
		echo "$temp_d,$temp_m,$temp_r,$temp_up,$temp_nu,$temp_sn,$temp_wcz,$temp_u,$temp_kto,$temp_belongs";
		if (
			($temp_d==$_REQUEST[dzien]) &&
			($temp_m==$_REQUEST[miesiac]) &&
			($temp_m==$_REQUEST[rok]) &&
			($temp_up==$_REQUEST[put_up]) &&
			($temp_nu==$_REQUEST[put_nazwa_urzadzenia]) &&
			($temp_sn==$_REQUEST[put_sn_urzadzenia]) &&
			($temp_wcz==$_REQUEST[put_wykonane_czynnosci]) &&
			($temp_u==$_REQUEST[put_uwagi]) &&
			($temp_kto==$_REQUEST[username]) &&
			($temp_belongs==$es_filia)
			) {
				} else {
			*/
		
		$HD_nr_zgl = $_REQUEST[hd_zgl_nr];
		
		$sql_zapis = "INSERT INTO $dbname.serwis_protokoly_historia VALUES ('','$numerprotokolu','$dzien','$miesiac','$rok','$c_1','$c_2','$c_3','$c_4','$c_5','$c_6','$c_7','$c_8','$put_up','$_REQUEST[new_upid]','$put_nazwa_urzadzenia','$put_sn_urzadzenia','$put_ni_urzadzenia','$put_opis_uszkodzenia','$put_wykonane_czynnosci','$put_uwagi',$wersjap,'$username',$es_filia,'$unr','$_REQUEST[source]','$_REQUEST[state]','$_REQUEST[ewid_id]','$HD_nr_zgl')";
		$akcja='insert';
		$_REQUEST[unr]=$unr;

		// ustawia zmienną w sesji informującą o tym że protokół został dodany do bazy (możliwe bedą teraz tylko jego modyfikacje
		session_register('protokol_dodany_do_bazy');	$_SESSION[protokol_dodany_do_bazy]=1;
		
	
		//	}
} else {

	if ($view!='1') {
		if ($_REQUEST[unr]!='') {
			$unr=$_REQUEST[unr];
		} else $unr=$_SESSION[unr_session];
		
		$HD_nr_zgl = $_REQUEST[hd_zgl_nr];
		$sql_zapis = "UPDATE $dbname.serwis_protokoly_historia SET protokol_nr='$numerprotokolu', protokol_d='$dzien', protokol_m='$miesiac', protokol_r='$rok', protokol_c1 = '$c_1',protokol_c2 = '$c_2',protokol_c3 = '$c_3',protokol_c4 = '$c_4',protokol_c5 = '$c_5',protokol_c6 = '$c_6',protokol_c7 = '$c_7',protokol_c8 = '$c_8',protokol_nazwa_urzadzenia='$put_nazwa_urzadzenia', protokol_sn_urzadzenia='$put_sn_urzadzenia', protokol_ni_urzadzenia='$put_ni_urzadzenia',protokol_opis_uszkodzenia='$put_opis_uszkodzenia', protokol_wykonane_czynnosci='$put_wykonane_czynnosci',protokol_uwagi='$put_uwagi',protokol_wersja='$wersjap', protokol_kto='$username', protokol_up='$up',protokol_source='$_REQUEST[source]',protokol_state='$_REQUEST[state]', protokol_hd_zgl_nr='$HD_nr_zgl' WHERE (protokol_unique='$unr') and (belongs_to=$es_filia)";
		$akcja='update';

	} else {
		$sql_zapis = "SELECT up_id FROM $dbname.serwis_komorki WHERE up_id=1 LIMIT 1";
	}
	
}

//echo "$sql_zapis";
//echo "<br />source : $_REQUEST[source]<br />";
/*
echo "PS: $_REQUEST[ps]<br />";
echo "SZ: $_REQUEST[sz]<br />";
echo "source : $_REQUEST[source]<br />";
*/
//echo $sql_zapis;

?>
<?php if (($source=='naprawy-przyjecie') && ($_REQUEST[tresc_zgl]=='')) { ?>
<script>if (opener) opener.location.reload(true);</script>
<?php } ?>	
<?php

if (($source=='towary-sprzedaz') && ($_REQUEST[nzpdb]=='on')) {
	$sql_zapis = "SELECT sprzedaz_id FROM $dbname.serwis_sprzedaz WHERE sprzedaz_id=1";
}
//echo $sql_zapis;

if (mysql_query($sql_zapis, $conn)) { 

	/*	if ($akcja=='insert') { ?><script>alert('Pomyślnie dodano protokół do bazy');</script><?php }
		if ($akcja=='update') { ?><script>alert('Zmiany w protokole zostały zapisane');</script><?php }	
	*/

	// #####################################################################################################

	//print_r($_SESSION);
		
	// zdjęcie sprzętu ze stanu
	if (($_REQUEST[source]=='magazyn-pobierz')) {
		if ($_SESSION[wykonaj_magazyn_pobierz]!=1) {
			$_REQUEST=sanitize($_REQUEST);

			$result55 = mysql_query("SELECT filia_nazwa FROM $dbname.serwis_filie WHERE filia_id=$es_filia", $conn) or die($k_b);
			list($filian)=mysql_fetch_array($result55);
			$dddd = Date('Y-m-d H:i:s');
			
			$sql21 = "SELECT up_nazwa, pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.up_id=$_REQUEST[new_upid]) and (serwis_piony.pion_id=serwis_komorki.up_pion_id) and (belongs_to=$es_filia) LIMIT 1";
			$wynik21 = mysql_query($sql21,$conn);
			
			list($nazwa,$pion) = mysql_fetch_array($wynik21);
			$SamaNazwaKomorki = $nazwa;
			
			$sql_t = "INSERT INTO $dbname.serwis_historia VALUES ('','$_REQUEST[tid]','$SamaNazwaKomorki','$currentuser','$dddd','pobrano na','".nl2br($_REQUEST[tkomentarz])."','0','$_REQUEST[hd_zgl_nr]',$es_filia)";
			//echo $sql_t."<br />";
			//echo "Naprawa ID: ".$_REQUEST[naprawaid]."<br />";
			
			if (mysql_query($sql_t, $conn)) { 
				// *************************
					session_register('wykonaj_magazyn_pobierz');	$_SESSION[wykonaj_magazyn_pobierz]=1;
				// *************************
				
				$wykonaj = mysql_query("UPDATE $dbname.serwis_magazyn SET magazyn_status = '1' WHERE magazyn_id = '$_REQUEST[tid]' LIMIT 1", $conn) or die($k_b);
				//echo "1";
				
				if ($_REQUEST[naprawaid]>0) {
				//echo "UPDATE $dbname.serwis_naprawa SET naprawa_sprzet_zastepczy_id = '$_REQUEST[tid]' WHERE naprawa_id = '$_REQUEST[naprawaid]' LIMIT 1";
				
					$wykonaj = mysql_query("UPDATE $dbname.serwis_naprawa SET naprawa_sprzet_zastepczy_id = '$_REQUEST[tid]' WHERE naprawa_id = '$_REQUEST[naprawaid]' LIMIT 1", $conn) or die($k_b);			
				} else {					
					// dopisz id sprzętu serwisowego do zgłoszenia HelpDesk
					$wykonaj2 = mysql_query("UPDATE $dbname_hd.hd_zgloszenie SET zgl_sprzet_serwisowy_id = '$_REQUEST[tid]' WHERE zgl_nr = '$_REQUEST[hd_zgl_nr]' LIMIT 1", $conn) or die($k_b);
				}
				//echo "2";
				?><script>
					<?php if ($_REQUEST[from]=='hd') { ?>
						if (readCookie('wpisane_wc_<?php echo $_REQUEST[hd_zgl_nr]; ?>')==null) {
							SetCookie('wpisane_wc_<?php echo $_REQUEST[hd_zgl_nr]; ?>','<?php echo $wykonane_czynnosci; ?>'); 
						} else {
							if (confirm('Czy przenieść wykonane czynności z protokołu do wykonanych czynności w obsługiwanym kroku zgłoszenia ?')) 
								SetCookie('wpisane_wc_<?php echo $_REQUEST[hd_zgl_nr]; ?>','<?php echo $wykonane_czynnosci; ?>'); 
						}
					<?php } ?>
					
					if (opener) opener.location.reload(true); </script><?php
			} else {
				?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
			}
		}
	}	

	// ######################################################################################################
	if (($_REQUEST[source]=='magazyn-zwrot')) {
		if ($_SESSION[wykonaj_magazyn_zwrot]!=1) {
		
			if ($_REQUEST[naprawa_pozostaje]!=1) {
			
				$_REQUEST=sanitize($_REQUEST);
				$sql55="SELECT * FROM $dbname.serwis_filie WHERE filia_id=$es_filia";
				$result55 = mysql_query($sql55, $conn) or die($k_b);
				$dane55 = mysql_fetch_array($result55);
				$filian = $dane55['filia_nazwa'];
				$dddd = Date('Y-m-d H:i:s');

				$sql21 = "SELECT up_nazwa, pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.up_id=$_REQUEST[new_upid]) and (serwis_piony.pion_id=serwis_komorki.up_pion_id) and (belongs_to=$es_filia) LIMIT 1";
				
				$wynik21 = mysql_query($sql21,$conn);
				list($nazwa,$pion) = mysql_fetch_array($wynik21);
				$SamaNazwaKomorki = $nazwa;
				
				$sql_t = "INSERT INTO $dbname.serwis_historia VALUES ('','$_REQUEST[tid]','$SamaNazwaKomorki','$currentuser','$dddd','zwrócono z','".nl2br($_REQUEST[tkomentarz])."','0','$_REQUEST[hd_zgl_nr]',$es_filia)";		
				
				if (mysql_query($sql_t, $conn)) { 
					// ************************	
						session_register('wykonaj_magazyn_zwrot');	$_SESSION[wykonaj_magazyn_zwrot]=1;
					// *************************
					$sql_t1 = "UPDATE $dbname.serwis_magazyn SET magazyn_status='0' WHERE magazyn_id='$_REQUEST[tid]' LIMIT 1";					
					$wykonaj = mysql_query($sql_t1, $conn) or die($k_b);
					
					// dopisz id sprzętu serwisowego do zgłoszenia HelpDesk
					$wykonaj2 = mysql_query("UPDATE $dbname_hd.hd_zgloszenie SET zgl_sprzet_serwisowy_id = '-1' WHERE zgl_nr = '$_REQUEST[hd_zgl_nr]' LIMIT 1", $conn) or die($k_b);
				
					if ($_REQUEST[tid]>0) {
						$wykonaj = mysql_query("UPDATE $dbname.serwis_naprawa SET naprawa_sprzet_zastepczy_id = '0' WHERE naprawa_sprzet_zastepczy_id = '$_REQUEST[tid]' LIMIT 1", $conn) or die($k_b);
					}

					?><script>
						<?php if ($_REQUEST[from]=='hd') { ?>
							if (readCookie('wpisane_wc_<?php echo $_REQUEST[hd_zgl_nr]; ?>')==null) {
								SetCookie('wpisane_wc_<?php echo $_REQUEST[hd_zgl_nr]; ?>','<?php echo $wykonane_czynnosci; ?>'); 
							} else {
								if (confirm('Czy przenieść wykonane czynności z protokołu do wykonanych czynności w obsługiwanym kroku zgłoszenia ?')) 
									SetCookie('wpisane_wc_<?php echo $_REQUEST[hd_zgl_nr]; ?>','<?php echo $wykonane_czynnosci; ?>'); 
							}
						<?php } ?>
					
						if (opener) opener.location.reload(true); 
					
					</script><?php
				} else {
				  ?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php	
				}
				
			} else {
			
				$_REQUEST=sanitize($_REQUEST);
				$dddd = Date('Y-m-d H:i:s');
				
				$sql_usun_z_serwisu = "UPDATE $dbname.serwis_naprawa SET naprawa_sprzet_zastepczy_id=0 WHERE naprawa_id=$_REQUEST[uid] LIMIT 1";
	//			echo "$sql_usun_z_serwisu<br />";
				if (mysql_query($sql_usun_z_serwisu,$conn)) {
					// ************************	
					$_SESSION[wykonaj_magazyn_zwrot]=1;
					// ************************	
				} else {
						?><script>info('Wystąpił błąd podczas usuwania z napraw'); //self.close(); </script><?php
					}
					
				$sql_wroc_sprzet_na_magazyn = "UPDATE $dbname.serwis_magazyn SET magazyn_status=0, magazyn_naprawa_id='0' WHERE magazyn_id=$_REQUEST[szid] LIMIT 1";
		//		echo "$sql_wroc_sprzet_na_magazyn<br />";
				if (mysql_query($sql_wroc_sprzet_na_magazyn,$conn)) {
				} 

				$sql_t = "INSERT INTO $dbname.serwis_historia VALUES ('','$_REQUEST[szid]','$_REQUEST[tup]','$currentuser','$dddd','zwrócono z','".nl2br($uwagi)."','$_REQUEST[uid]','$_REQUEST[hd_zgl_nr]',$es_filia)";
			//	echo "$sql_t<br />";
				if (mysql_query($sql_t, $conn)) {
					// usuń id sprzętu serwisowego do zgłoszenia HelpDesk
					$wykonaj2 = mysql_query("UPDATE $dbname_hd.hd_zgloszenie SET zgl_sprzet_serwisowy_id = '-1' WHERE zgl_nr = '$_REQUEST[hd_zgl_nr]' LIMIT 1", $conn) or die($k_b);
				}

				?><script>
					<?php if ($_REQUEST[from]=='hd') { ?>
						SetCookie('wpisane_wc_<?php echo $_REQUEST[hd_zgl_nr]; ?>','<?php echo $wykonane_czynnosci; ?>'); 
					<?php } ?>
						
				if (opener) opener.location.reload(true); 
					//self.close(); 
				</script><?php		
			
			}
		}
	}

	// ########################################################################################################
		
	// wymiana podzespołu powiązana ze zgłoszeniem
	if (($_REQUEST[source]=='wymiana-podzespolow')) {
		if ($_SESSION[wykonaj_wymiane_podzespolu]!=1) {
		
		$_REQUEST=sanitize($_REQUEST);
			
		echo "do oprogramowania";
		
		echo "wykonaj_wymiane_podzespolu: ".$_SESSION[wykonaj_wymiane_podzespolu]."<br />";
		echo "source: <b>$_REQUEST[source]</b><br />";
		echo "hd_nr: <b>$_REQUEST[hd_nr]</b><br />";
		echo "ewid_id: <b>$_REQUEST[ewid_id]</b><br />";
		echo "sell_towar: <b>$_REQUEST[sell_towar]</b><br />";

		
		$dddd = Date('Y-m-d H:i:s');	
		if (($_SESSION[wykonaj_sprzedaz]!=1) && ($_REQUEST[sell_towar]!=0)) {

		if ($view=='1') {
			$rok = $_REQUEST[rok];
			$miesiac = $_REQUEST[miesiac];		
		} else {
			$rok = substr($_REQUEST[pdata],0,4);
			$miesiac = substr($_REQUEST[pdata],5,2);
		}
		
		$sql = "SELECT sr_miesiac FROM $dbname.serwis_sprzedaz_raport WHERE ((belongs_to=$es_filia) and (sr_rok=$rok) and (sr_miesiac=$miesiac))";
		$result = mysql_query($sql, $conn) or die($k_b);
		$count_rows = mysql_num_rows($result);

		if ($count_rows==0) {
			$unr='';
			$litery=array('a','b','c','d','e','f','g','h','i','j');
			for ($q=1;$q<26;$q++) { $unr.= rand(0,9); $unr.=$litery[rand(0,9)]; }
				
			$dddd = Date('Y-m-d H:i:s');

			$cena = str_replace(',','.',$_REQUEST[tcena]);
			$cenaodsp = str_replace(',','.',$_REQUEST[tcenaodsp]);

			$przes = str_replace(',','.',$_REQUEST[tprzesylka]);

			// if ($przes!="0") { $sumacena = (real) $cena+$przes;} else $sumacena=$cena;

			$cena_cr = crypt_md5($cena,$key);
			$cenaodsp_cr = crypt_md5($cenaodsp,$key);
			$przes_cr = crypt_md5($przes,$key);

			$sumacena_cr = crypt_md5($sumacena,$key);

			$nrup = $_REQUEST['new_upid'];

			$sql_get_info = "SELECT * FROM $dbname.serwis_komorki WHERE (belongs_to) and (up_id=$nrup) LIMIT 1";
			$wynik_get_info = mysql_query($sql_get_info, $conn) or die($k_b);
			$gi = mysql_fetch_array($wynik_get_info);
			$nazwaup = $gi['up_nazwa'];
			$pionid = $gi['up_pion_id'];
			$umowaid = $gi['up_umowa_id'];

			$sql_get_pion = "SELECT pion_nazwa FROM $dbname.serwis_piony WHERE pion_id=$pionid LIMIT 1";
			$wynik_get_pion = mysql_query($sql_get_pion, $conn) or die($k_b);
			$pi = mysql_fetch_array($wynik_get_pion);
			$pionnazwa = $pi['pion_nazwa'];

	/*		$sql_get_umowanr = "SELECT umowa_nr FROM $dbname.serwis_umowy WHERE umowa_id=$umowaid LIMIT 1";
			$wynik_get_umowanr = mysql_query($sql_get_umowanr, $conn) or die($k_b);
			$ui = mysql_fetch_array($wynik_get_umowanr);
			$umowanr = $ui['umowa_nr'];
	*/		
			$umowanr = $_REQUEST[tumowa];

			$sql_t = "INSERT INTO $dbname.serwis_sprzedaz VALUES ('', '$_REQUEST[tid]', '$_REQUEST[tnazwa]','$_REQUEST[tsn]','$cena_cr','$cenaodsp_cr','','$_REQUEST[pdata]','$dddd','$currentuser','$umowanr','$pionnazwa','$nazwaup','".nl2br($_REQUEST[tuwagi])."',$es_filia,'$_REQUEST[tidf]','$_REQUEST[trodzaj]','1','$_REQUEST[ttyp]','','$unr')";

			//echo "$sql_t";
			
			if (mysql_query($sql_t, $conn)) {
					
				$sql1a="UPDATE $dbname.serwis_faktura_szcz SET pozycja_status = '1', pozycja_datasprzedazy = '$_REQUEST[pdata]' WHERE pozycja_id = '$_REQUEST[tid]'";
				if (mysql_query($sql1a, $conn)) {
					// *************************
						session_register('wykonaj_sprzedaz');	$_SESSION[wykonaj_sprzedaz]=1;
					// *************************				
						?><script>if (opener) opener.location.reload(true);  </script><?php
				}
				echo "<input type=hidden name=nierobzapytan value=1>";
				
			} else {
				?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); </script><?php	
			}
					
		} else {
			errorheader("Sprzedaż towaru na dzień ".$_REQUEST[pdata]." jest niemożliwa - wygenerowano już raport ze sprzedaży");	
			startbuttonsarea("right");
			addbuttons("wstecz","zamknij");
			endbuttonsarea();
			exit;
		}
	 
		

			
		
	/*		$_REQUEST=sanitize($_REQUEST);
			$result55 = mysql_query("SELECT filia_nazwa FROM $dbname.serwis_filie WHERE filia_id=$es_filia", $conn) or die($k_b);
			list($filian)=mysql_fetch_array($result55);
			$dddd = Date('Y-m-d H:i:s');

			$sql21 = "SELECT up_nazwa, pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.up_id=$_REQUEST[new_upid]) and (serwis_piony.pion_id=serwis_komorki.up_pion_id) and (belongs_to=$es_filia) LIMIT 1";
			$wynik21 = mysql_query($sql21,$conn);
			list($nazwa,$pion) = mysql_fetch_array($wynik21);
			$SamaNazwaKomorki = $nazwa;
			
			$sql_t = "INSERT INTO $dbname.serwis_historia VALUES ('','$_REQUEST[tid]','$SamaNazwaKomorki','$currentuser','$dddd','pobrano na','".nl2br($_REQUEST[tkomentarz])."','0',$es_filia)";

			if (mysql_query($sql_t, $conn)) { 
				// *************************
					session_register('wykonaj_magazyn_pobierz');	$_SESSION[wykonaj_magazyn_pobierz]=1;
				// *************************
				$wykonaj = mysql_query("UPDATE $dbname.serwis_magazyn SET magazyn_status = '1' WHERE magazyn_id = '$_REQUEST[tid]' LIMIT 1", $conn) or die($k_b);
				?><script> opener.location.reload(true); </script><?php
			} else {
				?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
			}
	*/
			}
		
		}

	}

	//  ######################################################################################################

	if ($_REQUEST[source]=='naprawy-przyjecie') {
	
		if ($_SESSION[wykonaj_naprawy_przyjecie]!=1) {
			if ($_REQUEST[tuwagi]!='') { $tuwagisa='1'; } else $tuwagisa='0';
			if ($_REQUEST[uwagi]!='') { $tuwagisa='1'; } else $tuwagisa='0';
			$dddd = Date('Y-m-d H:i:s');
			
			$sql21 = "SELECT up_nazwa, pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.up_id=$_REQUEST[new_upid]) and (serwis_piony.pion_id=serwis_komorki.up_pion_id) and (belongs_to=$es_filia) LIMIT 1";
			
			$wynik21 = mysql_query($sql21,$conn);
			list($nazwa,$pion) = mysql_fetch_array($wynik21);
			$SamaNazwaKomorki = $nazwa;
		
			if ($_REQUEST[sz]!='0') {

				$wynik=mysql_query("INSERT INTO $dbname.serwis_historia VALUES ('','$_REQUEST[sz]','$SamaNazwaKomorki','$currentuser','$dddd','pobrano na','".nl2br($_REQUEST[tuwagi])."','0','$_REQUEST[hd_zgl_nr]',$es_filia)", $conn) or die($k_b);

				$sql22 = "SELECT historia_id FROM $dbname.serwis_historia WHERE ((belongs_to=$es_filia) and (historia_data='$dddd') and (historia_user='$currentuser')) LIMIT 1";
			
				$wynik22 = mysql_query($sql22,$conn);
				list($last_hid) = mysql_fetch_array($wynik22);

				// *************************
					session_register('numer_id_dopisanego_do_tabeli_serwis_historia');	
					$_SESSION[numer_id_dopisanego_do_tabeli_serwis_historia]=mysql_insert_id($conn);
				// *************************
				$wynik2=mysql_query("UPDATE $dbname.serwis_magazyn SET magazyn_status = '1' WHERE magazyn_id = '$_REQUEST[sz]' LIMIT 1", $conn) or die($k_b);
			}
			if ($_REQUEST[id]=='') $id='0';
			if ($_REQUEST[uwagi]!='') $_REQUEST[tuwagi]=$_REQUEST[uwagi];

			$unique = Date('YmdHis');
			
			$HD_nr_zgl1 = $_REQUEST[hd_zgl_nr];
			if ($_REQUEST[from]!='hd') {
				if ($HD_nr_zgl=='') $HD_nr_zgl1 = 0;
			} else {
				$HD_nr_zgl1 = 0;
			}
			
			$sql_t = "INSERT INTO $dbname.serwis_naprawa VALUES ('', '$_REQUEST[part]','$_REQUEST[tmodel]','$_REQUEST[sn_urzadzenia]','$_REQUEST[ni_urzadzenia]',$tuwagisa,'".nl2br($_REQUEST[opis_uszkodzenia])."','$SamaNazwaKomorki','$currentuser','$dddd','','','','','','','','','','','','',-1,$es_filia,'','','$_REQUEST[sz]',$id,'$unique','','','','','','','','','','',0,0,'$HD_nr_zgl1')";

			//echo $sql_t;
			
		/*	$r3 = mysql_query("SELECT naprawa_id FROM $dbname.serwis_naprawa WHERE ((naprawa_osoba_pobierajaca='$currentuser') and (belongs_to='$es_filia') and (naprawa_model='$_REQUEST[tmodel]') and (naprawa_sn='$_REQUEST[sn_urzadzenia]')) ORDER BY naprawa_id DESC LIMIT 1", $conn_hd);
			list($last_naprawa_id)=mysql_fetch_array($r3);
			$wynik2=mysql_query("UPDATE $dbname.serwis_magazyn SET magazyn_naprawa_id = '$last_naprawa_id' WHERE magazyn_id = '$_REQUEST[sz]' LIMIT 1", $conn) or die($k_b);
		*/
			
			if (mysql_query($sql_t, $conn)) { 				
				// *************************
					session_register('numer_id_dopisanego_do_tabeli_serwis_naprawa');	$_SESSION[numer_id_dopisanego_do_tabeli_serwis_naprawa]=mysql_insert_id($conn);
				// *************************
			
				$last_naprawa_id = $_SESSION[numer_id_dopisanego_do_tabeli_serwis_naprawa];
					
				if ($_REQUEST[sz]!='0') {
					// ustal numer id naprawy i dopisz ją do sprzętu w magazynie
					$sql22 = "SELECT naprawa_id FROM $dbname.serwis_naprawa WHERE ((naprawa_unique='$unique') and (belongs_to=$es_filia) and (naprawa_osoba_pobierajaca='$currentuser')) LIMIT 1";
				
					$wynik22 = mysql_query($sql22,$conn);

					list($last_nid) = mysql_fetch_array($wynik22);
					$sql21 = "UPDATE $dbname.serwis_magazyn SET magazyn_naprawa_id = '$last_nid' WHERE magazyn_id = '$_REQUEST[sz]' LIMIT 1";
		
					$wynik21 = mysql_query($sql21,$conn);

					$sql21 = "UPDATE $dbname.serwis_historia SET historia_naprawa_id = '$last_nid' WHERE historia_id = '$last_hid' LIMIT 1";
					$wynik21 = mysql_query($sql21,$conn);
									
				}
				
				if ($_REQUEST[auto]==1) {
				
				//	echo "UPDATE $dbname.serwis_ewidencja SET ewidencja_status = -1 WHERE ewidencja_id=$_REQUEST[ewid_id]";
					if ($_REQUEST[ewid_id]!='')
						$wynik3 = mysql_query("UPDATE $dbname.serwis_ewidencja SET ewidencja_status = -1 WHERE ewidencja_id=$_REQUEST[ewid_id]", $conn) or die($k_b);	
				}
				
				// uaktualnij dane w ewidencji sprzętu
				if ($_REQUEST[popraw_w_ewidencji]=='on') {

					$sql22 = "SELECT up_nazwa FROM $dbname.serwis_komorki WHERE ((up_id=$_REQUEST[new_upid]) and (belongs_to=$es_filia)) LIMIT 1";
					$wynik22 = mysql_query($sql22,$conn);
					list($nazwa_up) = mysql_fetch_array($wynik22);
					
					$d1 = date("Y-m-d H:i:s");
					$put_model_urzadzenia = $_REQUEST[model_urzadzenia];
					
					// aktualizuj słownik
					if (($_REQUEST[tnazwa]=='Komputer') || ($_REQUEST[tnazwa]=='Serwer') || ($_REQUEST[tnazwa]=='Notebook')) {
						$wynik3 = mysql_query("INSERT INTO $dbname.serwis_slownik_konfiguracja VALUES ('', 'pozycja dodana automatycznie w dniu $d1 przez $currentuser','$es_filia','$put_model_urzadzenia','','','')", $conn) or die($k_b);
					}
					
					if ($_REQUEST[tnazwa]=='Monitor') {
						$wynik3 = mysql_query("INSERT INTO $dbname.serwis_slownik_monitor VALUES ('', '$put_model_urzadzenia','pozycja dodana automatycznie w dniu $d1 przez $currentuser','','$es_filia','')", $conn) or die($k_b);
					}
					
					if ($_REQUEST[tnazwa]=='Drukarka') {
						$wynik3 = mysql_query("INSERT INTO $dbname.serwis_slownik_drukarka VALUES ('', '$put_model_urzadzenia','pozycja dodana automatycznie w dniu $d1 przez $currentuser','$es_filia')", $conn) or die($k_b);
					}
					
					if ($_REQUEST[tnazwa]=='Czytnik') {
						$wynik3 = mysql_query("INSERT INTO $dbname.serwis_slownik_czytnik VALUES ('', '$put_model_urzadzenia','pozycja dodana automatycznie w dniu $d1 przez $currentuser','$es_filia')", $conn) or die($k_b);
					}
				
					if ($_REQUEST[ewid_id]!='') {
					
						if ($_REQUEST[tnazwa]=='Monitor') {
							$wynik3 = mysql_query("UPDATE $dbname.serwis_ewidencja SET ewidencja_zestaw_ni='$put_ni_urzadzenia', ewidencja_monitor_sn='$put_sn_urzadzenia', ewidencja_monitor_opis='$_REQUEST[model_urzadzenia]' WHERE ewidencja_id=$_REQUEST[ewid_id] LIMIT 1", $conn) or die($k_b);
							
						} elseif ($_REQUEST[tnazwa]=='Drukarka') {
							$wynik3 = mysql_query("UPDATE $dbname.serwis_ewidencja SET ewidencja_drukarka_ni='$put_ni_urzadzenia', ewidencja_drukarka_sn='$put_sn_urzadzenia', ewidencja_drukarka_opis='$_REQUEST[model_urzadzenia]' WHERE ewidencja_id=$_REQUEST[ewid_id] LIMIT 1", $conn) or die($k_b);
							
						} else {
							$wynik3 = mysql_query("UPDATE $dbname.serwis_ewidencja SET ewidencja_zestaw_ni='$put_ni_urzadzenia', ewidencja_komputer_sn='$put_sn_urzadzenia', ewidencja_komputer_opis='$_REQUEST[model_urzadzenia]' WHERE ewidencja_id=$_REQUEST[ewid_id] LIMIT 1", $conn) or die($k_b);
						}
					}
					
				}
				
				// dodanie nowego sprzętu do bazy ewidencji
				if ($_REQUEST[dodaj_sprzet_do_ewidencji]=='on') {

					$sql22 = "SELECT up_nazwa FROM $dbname.serwis_komorki WHERE ((up_id=$_REQUEST[new_upid]) and (belongs_to=$es_filia)) LIMIT 1";
					$wynik22 = mysql_query($sql22,$conn);
					list($nazwa_up) = mysql_fetch_array($wynik22);
				
					$sql22 = "SELECT rola_id FROM $dbname.serwis_slownik_rola WHERE (rola_nazwa='$_REQUEST[typ_urzadzenia]') LIMIT 1";

					$wynik22 = mysql_query($sql22,$conn);
					list($typ_id) = mysql_fetch_array($wynik22);
					
					if ($_REQUEST[typ_urzadzenia]=='Monitor') {
						$wynik3 = mysql_query("INSERT INTO $dbname.serwis_ewidencja VALUES ('', '$typ_id','$_REQUEST[typ_urzadzenia]','$_REQUEST[new_upid]','$nazwa_up','','','$put_ni_urzadzenia','','','','','','$_REQUEST[model_urzadzenia]','$put_sn_urzadzenia','','','','',$es_filia,'-1',0,'$dddd','$currentuser','','','','','','',0)", $conn) or die($k_b);
						
					} elseif ($_REQUEST[typ_urzadzenia]=='Drukarka') {
						$wynik3 = mysql_query("INSERT INTO $dbname.serwis_ewidencja VALUES ('', '$typ_id','$_REQUEST[typ_urzadzenia]','$_REQUEST[new_upid]','$nazwa_up','','','','','','','','','','','$_REQUEST[model_urzadzenia]','$put_sn_urzadzenia','$put_ni_urzadzenia','',$es_filia,'-1',0,'$dddd','$currentuser','','','','','','',0)", $conn) or die($k_b);
						
					} else {
						$wynik3 = mysql_query("INSERT INTO $dbname.serwis_ewidencja VALUES ('', '$typ_id','$_REQUEST[typ_urzadzenia]','$_REQUEST[new_upid]','$nazwa_up','','','$put_ni_urzadzenia','','$_REQUEST[model_urzadzenia]','$put_sn_urzadzenia','','','','','','','','',$es_filia,'-1',0,'$dddd','$currentuser','','','','','','',0)", $conn) or die($k_b);
						
					}
					
					$d1 = date("Y-m-d H:i:s");
					$put_model_urzadzenia = $_REQUEST[model_urzadzenia];
					
					// aktualizuj słownik
					if (($_REQUEST[typ_urzadzenia]=='Komputer') || ($_REQUEST[typ_urzadzenia]=='Serwer') || ($_REQUEST[typ_urzadzenia]=='Notebook')) {
						$wynik3 = mysql_query("INSERT INTO $dbname.serwis_slownik_konfiguracja VALUES ('', 'pozycja dodana automatycznie w dniu $d1 przez $currentuser','$es_filia','$put_model_urzadzenia','','','')", $conn) or die($k_b);
					}
					
					if ($_REQUEST[typ_urzadzenia]=='Monitor') {
						$wynik3 = mysql_query("INSERT INTO $dbname.serwis_slownik_monitor VALUES ('', '$put_model_urzadzenia','pozycja dodana automatycznie w dniu $d1 przez $currentuser','','$es_filia','')", $conn) or die($k_b);
					}
					
					if ($_REQUEST[typ_urzadzenia]=='Drukarka') {
						$wynik3 = mysql_query("INSERT INTO $dbname.serwis_slownik_drukarka VALUES ('', '$put_model_urzadzenia','pozycja dodana automatycznie w dniu $d1 przez $currentuser','$es_filia')", $conn) or die($k_b);
					}
					
					if ($_REQUEST[typ_urzadzenia]=='Czytnik') {
						$wynik3 = mysql_query("INSERT INTO $dbname.serwis_slownik_czytnik VALUES ('', '$put_model_urzadzenia','pozycja dodana automatycznie w dniu $d1 przez $currentuser','$es_filia')", $conn) or die($k_b);
					}					
					
				}
				
				// powiązanie naprawy ze zgłoszeniem w bazie Helpdesk
					session_register('naprawa_id_for_zgloszenie_nr'.$_REQUEST[hd_nr].'');
					$_SESSION['naprawa_id_for_zgloszenie_nr_'.$_REQUEST[hd_nr].'']=$last_naprawa_id;
				
				
//				echo ">>>>>>>".$last_naprawa_id;
				
				if ($_REQUEST[ewid_id]>0) {
					if ($_REQUEST[ewid_id]!='') {
						$sql_zmien_status_w_ewid = "UPDATE $dbname.serwis_ewidencja SET ewidencja_status='-1' WHERE (ewidencja_id=$_REQUEST[ewid_id]) LIMIT 1";
						$wynik = mysql_query($sql_zmien_status_w_ewid,$conn);
					}
				}
				
				// jeżeli wiążemy naprawę z już przyjętym sprzętem serwisowym - wyczyść info o sprzęcie serwisowym dla zgłoszenia
				if ($_REQUEST[PNZSS]==1) {
					$wykonaj2 = mysql_query("UPDATE $dbname_hd.hd_zgloszenie SET zgl_sprzet_serwisowy_id = '-1' WHERE zgl_nr = '$_REQUEST[hd_zgl_nr]' LIMIT 1", $conn) or die($k_b);
				}
				
				?><script>if (opener) opener.location.reload(true); </script><?php
			} else {	
				?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php	
			}
			session_register('wykonaj_naprawy_przyjecie'); $_SESSION[wykonaj_naprawy_przyjecie]=1;
		} else {
			// jeżeli już zapisano raz protokół do bazy - wykonaj update'y (jeżeli zmianie uległy któreś z pól w protokole
		}
	}

	// #####################################################################################################
	//echo ">> wykonaj naprawy zwrot >>$_SESSION[wykonaj_naprawy_zwrot]<br/>";
	//echo ">> request >>$_REQUEST<br/>";
	//print_r($_REQUEST);

	$sprawdzstatus = mysql_query("SELECT naprawa_status FROM $dbname.serwis_naprawa WHERE naprawa_id='$_REQUEST[id]' LIMIT 1", $conn) or die($k_b);
	list($temp_status)=mysql_fetch_array($sprawdzstatus);

	if (($_REQUEST[source]=='naprawy-zwrot') && ($temp_status=='3')) {

		$sql21 = "SELECT up_nazwa, pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.up_id=$_REQUEST[new_upid]) and (serwis_piony.pion_id=serwis_komorki.up_pion_id) and (belongs_to=$es_filia) LIMIT 1";

		$wynik21 = mysql_query($sql21,$conn);
		list($nazwa,$pion) = mysql_fetch_array($wynik21);
		$SamaNazwaKomorki = $nazwa;

		$dddd = Date('Y-m-d H:i:s');
		if ($_SESSION[wykonaj_naprawy_zwrot]!=1) {
			$sql_e1="UPDATE $dbname.serwis_naprawa SET naprawa_status = '$_REQUEST[tstatus1]', naprawa_osoba_oddajaca_sprzet = '$currentuser', naprawa_data_oddania_sprzetu = '$dddd' WHERE naprawa_id='$_REQUEST[id]' LIMIT 1";
		
			if (mysql_query($sql_e1, $conn)) { 
				if ($_REQUEST[sz]>0) {				
					$res = mysql_query("INSERT INTO $dbname.serwis_historia VALUES ('','$_REQUEST[sz]','$SamaNazwaKomorki','$currentuser','$dddd','zwrócono z','','0','$_REQUEST[hd_zgl_nr]',$es_filia)", $conn) or die($k_b);
					//$wynik21 = mysql_query($sql21,$conn);
					
					// *************************
						session_register('wykonaj_naprawy_zwrot');	$_SESSION[wykonaj_naprawy_zwrot]=1;
					// *************************
					$res = mysql_query("UPDATE $dbname.serwis_magazyn SET magazyn_status='0', magazyn_naprawa_id='0' WHERE magazyn_id='$_REQUEST[sz]' LIMIT 1", $conn) or die($k_b);
				}

				$sql_e1="UPDATE $dbname.serwis_naprawa SET naprawa_sprzet_zastepczy_id='0' WHERE naprawa_id='$_REQUEST[id]' LIMIT 1";
				if (mysql_query($sql_e1, $conn)) { }
				$wynik_getid = mysql_query("SELECT naprawa_ew_id FROM $dbname.serwis_naprawa WHERE naprawa_id='$_REQUEST[id]' LIMIT 1", $conn) or die($k_b);
				list($ewid_id)=mysql_fetch_array($wynik_getid);	
				if (($_REQUEST[ewid_id]!=0) && ($_REQUEST[ewid_id]!='')) 
					$wynik2 = mysql_query("UPDATE $dbname.serwis_ewidencja SET ewidencja_status = '9' WHERE ewidencja_id=$_REQUEST[ewid_id]", $conn) or die($k_b);
				
			// sprzedaż towaru
			
				if ($_REQUEST[ps]!='') {
					$rok = substr($_REQUEST[pdata],0,4);
					$miesiac = substr($_REQUEST[pdata],5,2);
					$sql = "SELECT sr_miesiac FROM $dbname.serwis_sprzedaz_raport WHERE ((belongs_to=$es_filia) and (sr_rok=$rok) and (sr_miesiac=$miesiac))";
					$result = mysql_query($sql, $conn) or die($k_b);
					
					$count_rows = mysql_num_rows($result);

				if ($count_rows==0) {
					//echo "<br/>miesiac nie zamknięty<br />";

					$unr='';
					$litery=array('a','b','c','d','e','f','g','h','i','j');
					for ($q=1;$q<26;$q++) { $unr.= rand(0,9); $unr.=$litery[rand(0,9)]; }
						
					$dddd = Date('Y-m-d H:i:s');

					$cena = str_replace(',','.',$_REQUEST[tcena]);
					$cenaodsp = str_replace(',','.',$_REQUEST[tcenaodsp]);

					$przes = str_replace(',','.',$_REQUEST[tprzesylka]);

					// if ($przes!="0") { $sumacena = (real) $cena+$przes;} else $sumacena=$cena;

					$cena_cr = crypt_md5($cena,$key);
					$cenaodsp_cr = crypt_md5($cenaodsp,$key);
					$przes_cr = crypt_md5($przes,$key);

					$sumacena_cr = crypt_md5($sumacena,$key);

					$nrup = $_REQUEST['new_upid'];

					$sql_get_info = "SELECT * FROM $dbname.serwis_komorki WHERE (belongs_to=$es_filia) and (up_id='$nrup') LIMIT 1";
				
					$wynik_get_info = mysql_query($sql_get_info, $conn) or die($k_b);				
					
					$gi = mysql_fetch_array($wynik_get_info);
					$nazwaup = $gi['up_nazwa'];
					$pionid = $gi['up_pion_id'];
					$umowaid = $gi['up_umowa_id'];

					$sql_get_pion = "SELECT pion_nazwa FROM $dbname.serwis_piony WHERE (pion_id=$pionid) LIMIT 1";
					$wynik_get_pion = mysql_query($sql_get_pion, $conn) or die($k_b);
					$pi = mysql_fetch_array($wynik_get_pion);
					$pionnazwa = $pi['pion_nazwa'];

					$sql_get_umowanr = "SELECT umowa_nr FROM $dbname.serwis_umowy WHERE (umowa_id=$umowaid) LIMIT 1";
					$wynik_get_umowanr = mysql_query($sql_get_umowanr, $conn) or die($k_b);
				
					$ui = mysql_fetch_array($wynik_get_umowanr);
					$umowanr = $ui['umowa_nr'];

					$sql9="SELECT * FROM $dbname.serwis_faktura_szcz WHERE ((belongs_to=$es_filia) and (pozycja_id='$_REQUEST[ps]')) LIMIT 1";
					$result9 = mysql_query($sql9, $conn) or die($k_b);
					while ($newArray9 = mysql_fetch_array($result9)) {
						$temp_id9  			= $newArray9['pozycja_id'];
						$temp_nazwa9		= $newArray9['pozycja_nazwa'];
						$temp_sn9			= $newArray9['pozycja_sn'];
						$temp_ttyp			= $newArray9['pozycja_typ'];
						$temp_nrfak			= $newArray9['pozycja_nr_faktury'];
						
						$temp_cenanetto9_cr		= $newArray9['pozycja_cena_netto'];
						$temp_cenaodsp_cr		= $newArray9['pozycja_cena_netto_odsprzedazy'];
						
						$temp_cenanetto9 = decrypt_md5($temp_cenanetto9_cr,$key);
						$temp_cenaodsp = decrypt_md5($temp_cenaodsp_cr,$key);
					}
				
					$sql_t = "INSERT INTO $dbname.serwis_sprzedaz VALUES ('', '$_REQUEST[ps]', '$temp_nazwa9','$temp_sn9','$temp_cenanetto9_cr','$temp_cenaodsp_cr','','$_REQUEST[pdata]','$dddd','$currentuser','$umowanr','$pionnazwa','$nazwaup','".nl2br($_REQUEST[tuwagi])."',$es_filia,'$temp_nrfak','$_REQUEST[trodzaj]','1','$temp_ttyp','','$unr')";
					//echo "<br />$sql_t<br/>";
					//echo "<br/>insert sprzedaz<br />";


					if (mysql_query($sql_t, $conn)) {
						$sql1a="UPDATE $dbname.serwis_faktura_szcz SET pozycja_status = '1', pozycja_datasprzedazy = '$_REQUEST[pdata]' WHERE pozycja_id = '$_REQUEST[ps]'";
								//echo "<br/>update faktura szcz<br />";

								
						if (mysql_query($sql1a, $conn)) {
						
							// jeżeli sprzedaż wykonana bez istniejącego powiązania towaru/typu ze zgłoszeniem (dodajwymianepodzespolow=1) wtedy dodaj do ostatniego kroku zgłoszenia wymianę podzespołów.
								if ($_REQUEST[dodajwymianepodzespolow]=='1') {
									$ddd1 = Date('Y-m-d H:i:s');
									
									$sql_wp = "INSERT INTO $dbname_hd.hd_zgl_wymiany_podzespolow VALUES ('',$_REQUEST[hd_zgl_nr],'$_REQUEST[hadim_nr]','$ddd1','$_REQUEST[_wp_opis]','$_REQUEST[_wp_sn]','$_REQUEST[_wp_ni]',1,'','','$_REQUEST[ps]','1','$_REQUEST[_wp_unique]',0,'','',$es_filia)";			
									$result_wp = mysql_query($sql_wp, $conn_hd) or die($k_b);

									// uaktualnij znacznik w tabeli ze zgłoszeniami
									$sql441 = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_czy_powiazane_z_wymiana_podzespolow=1 WHERE ((zgl_widoczne=1) and (belongs_to='$es_filia') and (zgl_id='$_REQUEST[hd_zgl_nr]')) LIMIT 1";					
									$result441 = mysql_query($sql441, $conn_hd) or die($k_b);
								}
								
								// *************************
									session_register('wykonaj_sprzedaz');	$_SESSION[wykonaj_sprzedaz]=1;
								// *************************				
					
							?><script>if (opener) opener.location.reload(true);  </script><?php
						}
						echo "<input type=hidden name=nierobzapytan value=1>";
					} else 
						{
							?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); </script><?php	
						}

				}
				}
			// koniec sprzedaży towaru
			
				?><script>if (opener) opener.location.reload(true);</script><?php
			} else {
				?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php	
				}
		}
		
	 }
	// ######################################################################################################

	// sprzedaż towaru / usługi
	if (($_REQUEST[source]=='towary-sprzedaz') && ($_REQUEST[zestaw]!=1) && ($_REQUEST[edit_towar]!='1')) { 
	
		
		$dddd = Date('Y-m-d H:i:s');	
		if ($_SESSION[wykonaj_sprzedaz]!=1) {

		if ($view=='1') {
			$rok = $_REQUEST[rok];
			$miesiac = $_REQUEST[miesiac];		
		} else {
			$rok = substr($_REQUEST[pdata],0,4);
			$miesiac = substr($_REQUEST[pdata],5,2);
		}
		
		$sql = "SELECT sr_miesiac FROM $dbname.serwis_sprzedaz_raport WHERE ((belongs_to=$es_filia) and (sr_rok=$rok) and (sr_miesiac=$miesiac))";
	
		$result = mysql_query($sql, $conn) or die($k_b);
		$count_rows = mysql_num_rows($result);
		
		//echo ">>> $count_rows";

		if ($count_rows==0) {
			
			$unr='';
			$litery=array('a','b','c','d','e','f','g','h','i','j');
			for ($q=1;$q<26;$q++) { $unr.= rand(0,9); $unr.=$litery[rand(0,9)]; }
				
			$dddd = Date('Y-m-d H:i:s');

			$cena = str_replace(',','.',$_REQUEST[tcena]);
			$cenaodsp = str_replace(',','.',$_REQUEST[tcenaodsp]);

			$przes = str_replace(',','.',$_REQUEST[tprzesylka]);

			// if ($przes!="0") { $sumacena = (real) $cena+$przes;} else $sumacena=$cena;

			$cena_cr = crypt_md5($cena,$key);
			$cenaodsp_cr = crypt_md5($cenaodsp,$key);
			$przes_cr = crypt_md5($przes,$key);

			$sumacena_cr = crypt_md5($sumacena,$key);

			$nrup = $_REQUEST['new_upid'];

			$sql_get_info = "SELECT * FROM $dbname.serwis_komorki WHERE (belongs_to) and (up_id=$nrup) LIMIT 1";
			$wynik_get_info = mysql_query($sql_get_info, $conn) or die($k_b);
			$gi = mysql_fetch_array($wynik_get_info);
			$nazwaup = $gi['up_nazwa'];
			$pionid = $gi['up_pion_id'];
			$umowaid = $gi['up_umowa_id'];

			$sql_get_pion = "SELECT pion_nazwa FROM $dbname.serwis_piony WHERE pion_id=$pionid LIMIT 1";
			$wynik_get_pion = mysql_query($sql_get_pion, $conn) or die($k_b);
			$pi = mysql_fetch_array($wynik_get_pion);
			$pionnazwa = $pi['pion_nazwa'];

	/*		$sql_get_umowanr = "SELECT umowa_nr FROM $dbname.serwis_umowy WHERE umowa_id=$umowaid LIMIT 1";
			$wynik_get_umowanr = mysql_query($sql_get_umowanr, $conn) or die($k_b);
			$ui = mysql_fetch_array($wynik_get_umowanr);
			$umowanr = $ui['umowa_nr'];
	*/		
			$umowanr = $_REQUEST[tumowa];

			$sql_t = "INSERT INTO $dbname.serwis_sprzedaz VALUES ('', '$_REQUEST[tid]', '$_REQUEST[tnazwa]','$_REQUEST[tsn]','$cena_cr','$cenaodsp_cr','','$_REQUEST[pdata]','$dddd','$currentuser','$umowanr','$pionnazwa','$nazwaup','".nl2br($_REQUEST[tuwagi])."',$es_filia,'$_REQUEST[tidf]','$_REQUEST[trodzaj]','1','$_REQUEST[ttyp]','','$unr')";

			//echo "$sql_t";
			
			if (mysql_query($sql_t, $conn)) {
					
				$sql1a="UPDATE $dbname.serwis_faktura_szcz SET pozycja_status = '1', pozycja_datasprzedazy = '$_REQUEST[pdata]' WHERE pozycja_id = '$_REQUEST[tid]'";
				if (mysql_query($sql1a, $conn)) {				
				
					// jeżeli sprzedaż wykonana bez istniejącego powiązania towaru/typu ze zgłoszeniem (dodajwymianepodzespolow=1) wtedy dodaj do ostatniego kroku zgłoszenia wymianę podzespołów.
					if ($_REQUEST[dodajwymianepodzespolow]=='1') {
						$ddd1 = Date('Y-m-d H:i:s');
						
						$sql_wp = "INSERT INTO $dbname_hd.hd_zgl_wymiany_podzespolow VALUES ('',$_REQUEST[hd_zgl_nr],'$_REQUEST[hadim_nr]','$ddd1','$_REQUEST[_wp_opis]','$_REQUEST[_wp_sn]','$_REQUEST[_wp_ni]',1,'','','$_REQUEST[tid]','1','$_REQUEST[_wp_unique]',0,'','',$es_filia)";			
						$result_wp = mysql_query($sql_wp, $conn_hd) or die($k_b);

						// uaktualnij znacznik w tabeli ze zgłoszeniami
						$sql441 = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_czy_powiazane_z_wymiana_podzespolow=1 WHERE ((zgl_widoczne=1) and (belongs_to='$es_filia') and (zgl_id='$_REQUEST[hd_zgl_nr]')) LIMIT 1";					
						$result441 = mysql_query($sql441, $conn_hd) or die($k_b);
					}
					
					// *************************
						session_register('wykonaj_sprzedaz');	$_SESSION[wykonaj_sprzedaz]=1;
					// *************************				
						?><script>if (opener) opener.location.reload(true);  </script><?php
				}
				echo "<input type=hidden name=nierobzapytan value=1>";
			} else {
				?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); </script><?php	
			}	
			// zdjęcie z napraw, magazynu itp
				
	/*

					$sql_e1="UPDATE $dbname.serwis_naprawa SET naprawa_status = '5', naprawa_osoba_oddajaca_sprzet = '$currentuser', naprawa_data_oddania_sprzetu = '$dddd' WHERE naprawa_id='$_REQUEST[pn]' LIMIT 1";

					if (mysql_query($sql_e1, $conn)) { 
					
						if ($_REQUEST[sz]>0) {
						
							$result99 = mysql_query("SELECT historia_magid,historia_up,historia_user,historia_ruchsprzetu,belongs_to FROM $dbname.serwis_historia ORDER BY historia_id DESC LIMIT 1", $conn) or die($k_b);
							list($temp_magid,$temp_up,$temp_user,$temp_ruchsprzetu,$temp_belongs_to)=mysql_fetch_array($result99);
							
							if (
								($temp_magid==$_REQUEST[sz]) &&
								($temp_up==$_REQUEST[tup]) &&
								($temp_user==$currentuser) &&
								($temp_ruchsprzetu=='zwrócono z') &&
								($temp_belongs_to==$es_filia)
								) {
								
								} else {
									$result99 = mysql_query("SELECT up_nazwa FROM $dbname.serwis_komorki WHERE up_id=$_REQUEST[tup] LIMIT 1", $conn) or die($k_b);
									list($temp_upnazwa)=mysql_fetch_array($result99);
							
									$res = mysql_query("INSERT INTO $dbname.serwis_historia VALUES ('','$_REQUEST[sz]','$temp_upnazwa','$currentuser','$dddd','zwrócono z','',$es_filia)", $conn) or die($k_b);
								}
								
							$res = mysql_query("UPDATE $dbname.serwis_magazyn SET magazyn_status='0' WHERE magazyn_id='$_REQUEST[sz]' LIMIT 1", $conn) or die($k_b);
							
							$sql_e1="UPDATE $dbname.serwis_naprawa SET naprawa_sprzet_zastepczy_id='0' WHERE naprawa_id='$_REQUEST[pn]' LIMIT 1";
							if (mysql_query($sql_e1, $conn)) { }
							
						}
						
						$wynik_getid = mysql_query("SELECT naprawa_ew_id FROM $dbname.serwis_naprawa WHERE naprawa_id='$_REQUEST[pn]' LIMIT 1", $conn) or die($k_b);
						list($ewid_id)=mysql_fetch_array($wynik_getid);
						$wynik2 = mysql_query("UPDATE $dbname.serwis_ewidencja SET ewidencja_status = '9' WHERE (ewidencja_id=$ewid_id) LIMIT 1", $conn) or die($k_b);
					
						?><script>opener.location.reload(true);</script><?php
					} else {
						?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); </script><?php	
						}

						*/
					
			} else
				{
				errorheader("Sprzedaż towaru na dzień ".$_REQUEST[pdata]." jest niemożliwa - wygenerowano już raport ze sprzedaży");	
				startbuttonsarea("right");
				addbuttons("wstecz","zamknij");
				endbuttonsarea();
				exit;
				}
	  }
	  
	}
	// #####################################################################################################
	// edycja sprzedaży towaru
	if (($_REQUEST[source]=='towary-sprzedaz') && ($_REQUEST[edit_towar]==1)) { 
	 
		$dddd = Date('Y-m-d H:i:s');	
		if ($_SESSION[edytuj_sprzedaz]!=1) {

		if ($view=='1') {
			$rok = $_REQUEST[rok];
			$miesiac = $_REQUEST[miesiac];		
		} else {
			$rok = substr($_REQUEST[pdata],0,4);
			$miesiac = substr($_REQUEST[pdata],5,2);
		}
		
		$sql = "SELECT sr_miesiac FROM $dbname.serwis_sprzedaz_raport WHERE ((belongs_to=$es_filia) and (sr_rok=$rok) and (sr_miesiac=$miesiac))";
		$result = mysql_query($sql, $conn) or die($k_b);
		$count_rows = mysql_num_rows($result);

		if ($count_rows==0) {
			$unr='';
			$litery=array('a','b','c','d','e','f','g','h','i','j');
			for ($q=1;$q<26;$q++) { $unr.= rand(0,9); $unr.=$litery[rand(0,9)]; }
				
			$dddd = Date('Y-m-d H:i:s');

			$nrup = $_REQUEST['new_upid'];

			$sql_get_info = "SELECT * FROM $dbname.serwis_komorki WHERE (belongs_to) and (up_id=$nrup) LIMIT 1";
			$wynik_get_info = mysql_query($sql_get_info, $conn) or die($k_b);
			$gi = mysql_fetch_array($wynik_get_info);
			$nazwaup = $gi['up_nazwa'];
			$pionid = $gi['up_pion_id'];
			$umowaid = $gi['up_umowa_id'];

			$sql_get_pion = "SELECT pion_nazwa FROM $dbname.serwis_piony WHERE pion_id=$pionid LIMIT 1";
			$wynik_get_pion = mysql_query($sql_get_pion, $conn) or die($k_b);
			$pi = mysql_fetch_array($wynik_get_pion);
			$pionnazwa = $pi['pion_nazwa'];

	/*		$sql_get_umowanr = "SELECT umowa_nr FROM $dbname.serwis_umowy WHERE umowa_id=$umowaid LIMIT 1";
			$wynik_get_umowanr = mysql_query($sql_get_umowanr, $conn) or die($k_b);
			$ui = mysql_fetch_array($wynik_get_umowanr);
			$umowanr = $ui['umowa_nr'];
	*/		
			$umowanr = $_REQUEST[tumowa];

			//$sql_t = "INSERT INTO $dbname.serwis_sprzedaz VALUES ('', '$_REQUEST[tid]', '$_REQUEST[tnazwa]','$_REQUEST[tsn]','$cena_cr','$cenaodsp_cr','','$_REQUEST[pdata]','$dddd','$currentuser','$umowanr','$pionnazwa','$nazwaup','".nl2br($_REQUEST[tuwagi])."',$es_filia,'$_REQUEST[tidf]','$_REQUEST[trodzaj]','1','$_REQUEST[ttyp]','','$unr')";

			$sql_t = "UPDATE $dbname.serwis_sprzedaz SET sprzedaz_data='$_REQUEST[tdata]', sprzedaz_data_operacji='$dddd', sprzedaz_umowa_nazwa='$umowanr', sprzedaz_pion_nazwa = '$pionnazwa',sprzedaz_up_nazwa='$nazwaup',sprzedaz_uwagi ='".nl2br($_POST[tuwagi])."', sprzedaz_rodzaj='$_REQUEST[trodzaj]' WHERE sprzedaz_id=$_REQUEST[sid]";
			
			//echo "$sql_t";
			
			if (mysql_query($sql_t, $conn)) {
					
				$sql1a="UPDATE $dbname.serwis_faktura_szcz SET pozycja_status = '1', pozycja_datasprzedazy = '$_REQUEST[tdata]' WHERE pozycja_id = '$_REQUEST[tid]'";
				if (mysql_query($sql1a, $conn)) {
					// *************************
						session_register('edytuj_sprzedaz');	$_SESSION[edytuj_sprzedaz]=1;
					// *************************				
						?><script>if (opener) opener.location.reload(true);  </script><?php
				}
				echo "<input type=hidden name=nierobzapytan value=1>";
			} else {
				?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); </script><?php	
			}	
			// zdjęcie z napraw, magazynu itp
				
	/*

					$sql_e1="UPDATE $dbname.serwis_naprawa SET naprawa_status = '5', naprawa_osoba_oddajaca_sprzet = '$currentuser', naprawa_data_oddania_sprzetu = '$dddd' WHERE naprawa_id='$_REQUEST[pn]' LIMIT 1";

					if (mysql_query($sql_e1, $conn)) { 
					
						if ($_REQUEST[sz]>0) {
						
							$result99 = mysql_query("SELECT historia_magid,historia_up,historia_user,historia_ruchsprzetu,belongs_to FROM $dbname.serwis_historia ORDER BY historia_id DESC LIMIT 1", $conn) or die($k_b);
							list($temp_magid,$temp_up,$temp_user,$temp_ruchsprzetu,$temp_belongs_to)=mysql_fetch_array($result99);
							
							if (
								($temp_magid==$_REQUEST[sz]) &&
								($temp_up==$_REQUEST[tup]) &&
								($temp_user==$currentuser) &&
								($temp_ruchsprzetu=='zwrócono z') &&
								($temp_belongs_to==$es_filia)
								) {
								
								} else {
									$result99 = mysql_query("SELECT up_nazwa FROM $dbname.serwis_komorki WHERE up_id=$_REQUEST[tup] LIMIT 1", $conn) or die($k_b);
									list($temp_upnazwa)=mysql_fetch_array($result99);
							
									$res = mysql_query("INSERT INTO $dbname.serwis_historia VALUES ('','$_REQUEST[sz]','$temp_upnazwa','$currentuser','$dddd','zwrócono z','',$es_filia)", $conn) or die($k_b);
								}
								
							$res = mysql_query("UPDATE $dbname.serwis_magazyn SET magazyn_status='0' WHERE magazyn_id='$_REQUEST[sz]' LIMIT 1", $conn) or die($k_b);
							
							$sql_e1="UPDATE $dbname.serwis_naprawa SET naprawa_sprzet_zastepczy_id='0' WHERE naprawa_id='$_REQUEST[pn]' LIMIT 1";
							if (mysql_query($sql_e1, $conn)) { }
							
						}
						
						$wynik_getid = mysql_query("SELECT naprawa_ew_id FROM $dbname.serwis_naprawa WHERE naprawa_id='$_REQUEST[pn]' LIMIT 1", $conn) or die($k_b);
						list($ewid_id)=mysql_fetch_array($wynik_getid);
						$wynik2 = mysql_query("UPDATE $dbname.serwis_ewidencja SET ewidencja_status = '9' WHERE (ewidencja_id=$ewid_id) LIMIT 1", $conn) or die($k_b);
					
						?><script>opener.location.reload(true);</script><?php
					} else {
						?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); </script><?php	
						}

						*/
					
			} else
				{
				errorheader("Sprzedaż towaru na dzień ".$_REQUEST[pdata]." jest niemożliwa - wygenerowano już raport ze sprzedaży");	
				startbuttonsarea("right");
				addbuttons("wstecz","zamknij");
				endbuttonsarea();
				exit;
				}
	  }
	  
	}

	// ####################################################################################################

	// sprzedaż zestawu 
	if (($_REQUEST[source]=='towary-sprzedaz') && ($_REQUEST[zestaw]==1) && ($_REQUEST[edit_zestaw]=='')) { 

		$dddd = Date('Y-m-d H:i:s');	
		if ($_SESSION[wykonaj_sprzedaz_zestawu]!=1) {

			if ($view=='1') {
				$rok = $_REQUEST[rok];
				$miesiac = $_REQUEST[miesiac];		
			} else {
				$rok = substr($_REQUEST[pdata],0,4);
				$miesiac = substr($_REQUEST[pdata],5,2);
			}
		
			$sql = "SELECT sr_miesiac FROM $dbname.serwis_sprzedaz_raport WHERE ((belongs_to=$es_filia) and (sr_rok=$rok) and (sr_miesiac=$miesiac))";
			
			$result = mysql_query($sql, $conn) or die($k_b);
			$count_rows = mysql_num_rows($result);
			//echo ">>>>".$count_rows;
			
			if ($count_rows==0) {
				$unr='';
				$litery=array('a','b','c','d','e','f','g','h','i','j');
				for ($q=1;$q<26;$q++) { $unr.= rand(0,9); $unr.=$litery[rand(0,9)]; }

				$dddd = Date('Y-m-d H:i:s');
				$nrup = $_REQUEST['new_upid'];
				
				$sql_get_info = "SELECT up_nazwa, up_pion_id, up_umowa_id FROM $dbname.serwis_komorki WHERE up_id=$nrup LIMIT 1";
				$wynik_get_info = mysql_query($sql_get_info, $conn) or die($k_b);
				$gi = mysql_fetch_array($wynik_get_info);
				$nazwaup = $gi['up_nazwa'];
				$pionid = $gi['up_pion_id'];
				$umowaid = $gi['up_umowa_id'];
				$sql_get_pion = "SELECT pion_nazwa FROM $dbname.serwis_piony WHERE pion_id=$pionid LIMIT 1";
				$wynik_get_pion = mysql_query($sql_get_pion, $conn) or die($k_b);
				$pi = mysql_fetch_array($wynik_get_pion);
				$pionnazwa = $pi['pion_nazwa'];

				$umowanr = $_REQUEST[tumowa];

				$zestaw_sql = "UPDATE $dbname.serwis_zestawy SET zestaw_from_hd=1, zestaw_hd_zgl_nr='$_REQUEST[hd_zgl_nr]' WHERE zestaw_id=$zid";	
				$wynik = mysql_query($zestaw_sql,$conn) or die($k_b);
				
				$zestaw_sql = "SELECT zestawpozycja_fszcz_id FROM $dbname.serwis_zestaw_szcz WHERE zestawpozycja_zestaw_id=$zid";
				//echo "<br />".$zestaw_sql;
				
				$wynik = mysql_query($zestaw_sql,$conn) or die($k_b);

				while ($newarray = mysql_fetch_array($wynik)) {
				
					$temp_zestaw_fszcz_id = $newarray['zestawpozycja_fszcz_id'];
					
					list($temp1_nrf, $temp1_nazwa, $temp1_sn, $temp1_cn, $temp1_cno,$temp1_typ,$temp1_rs)=mysql_fetch_array(mysql_query("SELECT pozycja_nr_faktury,pozycja_nazwa, pozycja_sn, pozycja_cena_netto, pozycja_cena_netto_odsprzedazy, pozycja_typ, pozycja_rodzaj_sprzedazy FROM $dbname.serwis_faktura_szcz WHERE (pozycja_id=$temp_zestaw_fszcz_id) LIMIT 1",$conn));
					$cena_cr = $temp1_cn;
					$cenaodsp_cr = $temp1_cno;
					
					if ($temp1_rs=='') $temp1_rs = 'Towar';
					
					$sql_t1 = "INSERT INTO $dbname.serwis_sprzedaz VALUES ('',$temp_zestaw_fszcz_id,'$temp1_nazwa','$temp1_sn','$cena_cr','$cenaodsp_cr','','$_REQUEST[tdata]','$dddd','$currentuser','$umowanr','$pionnazwa','$nazwaup','".nl2br($_REQUEST[tuwagi])."',$es_filia,$temp1_nrf,'$temp1_rs','1','$temp1_typ','','$unr')";
					
					//echo "<br />".$sql_t1;
					
					$wstaw_sql = mysql_query($sql_t1, $conn) or die($k_b);
					$sql1a="UPDATE $dbname.serwis_faktura_szcz SET pozycja_status = 1, pozycja_datasprzedazy = '$_REQUEST[tdata]' WHERE pozycja_id = $temp_zestaw_fszcz_id";
					//echo "<br />".$sql1a;
					
					if (mysql_query($sql1a, $conn)) {
					
					
						// jeżeli sprzedaż wykonana bez istniejącego powiązania towaru/typu ze zgłoszeniem (dodajwymianepodzespolow=1) wtedy dodaj do ostatniego kroku zgłoszenia wymianę podzespołów.
						if ($_REQUEST[dodajwymianepodzespolow]=='1') {
							$ddd1 = Date('Y-m-d H:i:s');
							
							$sql_wp = "INSERT INTO $dbname_hd.hd_zgl_wymiany_podzespolow VALUES ('',$_REQUEST[hd_zgl_nr],'$_REQUEST[hadim_nr]','$ddd1','$_REQUEST[_wp_opis]','$_REQUEST[_wp_sn]','$_REQUEST[_wp_ni]',1,'','','$temp_zestaw_fszcz_id','1','$_REQUEST[_wp_unique]',0,'','',$es_filia)";			
							$result_wp = mysql_query($sql_wp, $conn_hd) or die($k_b);

							// uaktualnij znacznik w tabeli ze zgłoszeniami
							$sql441 = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_czy_powiazane_z_wymiana_podzespolow=1 WHERE ((zgl_widoczne=1) and (belongs_to='$es_filia') and (zgl_id='$_REQUEST[hd_zgl_nr]')) LIMIT 1";					
							$result441 = mysql_query($sql441, $conn_hd) or die($k_b);
						}
					
						// *************************
							session_register('wykonaj_sprzedaz_zestawu');	$_SESSION[wykonaj_sprzedaz_zestawu]=1;
						// *************************				
							?><script>if (opener) opener.location.reload(true);  </script><?php
					} else {
					?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); </script><?php	
					}		
						
					//$aktualizuj_fszcz = mysql_query($sql1a,$conn) or die($k_b);
				}
				
				$zmien_status_zestawu = "UPDATE $dbname.serwis_zestawy SET zestaw_status=1 WHERE zestaw_id=$zid LIMIT 1";
				$aktualizuj_zestaw = mysql_query($zmien_status_zestawu,$conn) or die($k_b);
				
	//			echo "<br />".$zmien_status_zestawu;
		
			} else {
				errorheader("Sprzedaż towarów/zestawów na dzień ".$_POST[tdata]." jest niemożliwa - wygenerowano już raport ze sprzedaży");	
				startbuttonsarea("right");
				addbuttons("wstecz","zamknij");
				endbuttonsarea();
				exit;
			}
		}

	}

	// #####################################################################################################
	if (($_REQUEST[source]=='towary-sprzedaz') && ($_REQUEST[edit_zestaw]==1)) { 

		$dddd = Date('Y-m-d H:i:s');	
		if ($_SESSION[wykonaj_edycje_zestawu]!=1) {

			if ($view=='1') {
				$rok = $_REQUEST[rok];
				$miesiac = $_REQUEST[miesiac];		
			} else {
				$rok = substr($_REQUEST[pdata],0,4);
				$miesiac = substr($_REQUEST[pdata],5,2);
			}
		
			$sql = "SELECT sr_miesiac FROM $dbname.serwis_sprzedaz_raport WHERE ((belongs_to=$es_filia) and (sr_rok=$rok) and (sr_miesiac=$miesiac))";
			
			$result = mysql_query($sql, $conn) or die($k_b);
			$count_rows = mysql_num_rows($result);
			//echo ">>>>".$count_rows;
			
			if ($count_rows==0) {
				$unr='';
				$litery=array('a','b','c','d','e','f','g','h','i','j');
				for ($q=1;$q<26;$q++) { $unr.= rand(0,9); $unr.=$litery[rand(0,9)]; }

				$dddd = Date('Y-m-d H:i:s');
				$nrup = $_REQUEST['new_upid'];
				
				$sql_get_info = "SELECT up_nazwa, up_pion_id, up_umowa_id FROM $dbname.serwis_komorki WHERE up_id=$nrup LIMIT 1";
				$wynik_get_info = mysql_query($sql_get_info, $conn) or die($k_b);
				$gi = mysql_fetch_array($wynik_get_info);
				$nazwaup = $gi['up_nazwa'];
				$pionid = $gi['up_pion_id'];
				$umowaid = $gi['up_umowa_id'];
				$sql_get_pion = "SELECT pion_nazwa FROM $dbname.serwis_piony WHERE pion_id=$pionid LIMIT 1";
				$wynik_get_pion = mysql_query($sql_get_pion, $conn) or die($k_b);
				$pi = mysql_fetch_array($wynik_get_pion);
				$pionnazwa = $pi['pion_nazwa'];

				$umowanr = $_REQUEST[tumowa];

				$zestaw_sql = "SELECT zestawpozycja_fszcz_id FROM $dbname.serwis_zestaw_szcz WHERE zestawpozycja_zestaw_id=$_REQUEST[zid]";
				//echo "<br />".$zestaw_sql;
				
				$wynik = mysql_query($zestaw_sql,$conn) or die($k_b);

				while ($newarray = mysql_fetch_array($wynik)) {
				
					$temp_zestaw_fszcz_id = $newarray['zestawpozycja_fszcz_id'];
					
					list($temp1_nrf, $temp1_nazwa, $temp1_sn, $temp1_cn, $temp1_cno,$temp1_typ)=mysql_fetch_array(mysql_query("SELECT pozycja_nr_faktury,pozycja_nazwa, pozycja_sn, pozycja_cena_netto, pozycja_cena_netto_odsprzedazy, pozycja_typ FROM $dbname.serwis_faktura_szcz WHERE (pozycja_id=$temp_zestaw_fszcz_id) LIMIT 1",$conn));
					$cena_cr = $temp1_cn;
					$cenaodsp_cr = $temp1_cno;
					
					list($temp1_sid)=mysql_fetch_array(mysql_query("SELECT sprzedaz_id FROM $dbname.serwis_sprzedaz WHERE sprzedaz_pozycja_id=$temp_zestaw_fszcz_id LIMIT 1",$conn));
			
					$sql_t1 = "UPDATE $dbname.serwis_sprzedaz SET sprzedaz_data='$_REQUEST[tdata]', sprzedaz_data_operacji='$dddd', sprzedaz_umowa_nazwa='$umowanr', sprzedaz_pion_nazwa = '$pionnazwa',sprzedaz_up_nazwa='$nazwaup' WHERE sprzedaz_id=$temp1_sid";
					
					//echo $sql_t1;
					
			
					//$sql_t1 = "INSERT INTO $dbname.serwis_sprzedaz VALUES ('',$temp_zestaw_fszcz_id,'$temp1_nazwa','$temp1_sn','$cena_cr','$cenaodsp_cr','','$_REQUEST[tdata]','$dddd','$currentuser','$umowanr','$pionnazwa','$nazwaup','".nl2br($_REQUEST[tuwagi])."',$es_filia,$temp1_nrf,'$_REQUEST[trodzaj]','1','$temp1_typ','','$unr')";
					
					//echo "<br />".$sql_t1;
					
					$wstaw_sql = mysql_query($sql_t1, $conn) or die($k_b);
					//$sql1a="UPDATE $dbname.serwis_faktura_szcz SET pozycja_status = 1, pozycja_datasprzedazy = '$_REQUEST[tdata]' WHERE pozycja_id = $temp_zestaw_fszcz_id";
					$sql1a="UPDATE $dbname.serwis_faktura_szcz SET pozycja_status = 1, pozycja_datasprzedazy = '$_REQUEST[tdata]' WHERE pozycja_id = $temp_zestaw_fszcz_id";
					//echo "<br />".$sql1a;
					
					if (mysql_query($sql1a, $conn)) {
						// *************************
							session_register('wykonaj_edycje_zestawu');	$_SESSION[wykonaj_edycje_zestawu]=1;
						// *************************				
							?><script>if (opener) opener.location.reload(true);  </script><?php
					} else {
					?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); </script><?php	
					}		
						
					//$aktualizuj_fszcz = mysql_query($sql1a,$conn) or die($k_b);
				}
				
				$zmien_status_zestawu = "UPDATE $dbname.serwis_zestawy SET zestaw_status=1 WHERE zestaw_id=$zid LIMIT 1";
				$aktualizuj_zestaw = mysql_query($zmien_status_zestawu,$conn) or die($k_b);
				
	//			echo "<br />".$zmien_status_zestawu;
		
			} else {
				errorheader("Sprzedaż towarów/zestawów na dzień ".$_POST[tdata]." jest niemożliwa - wygenerowano już raport ze sprzedaży");	
				startbuttonsarea("right");
				addbuttons("wstecz","zamknij");
				endbuttonsarea();
			}
		}

	}

	// #####################################################################################################
	if ($_REQUEST[source]=='naprawy-wycofaj') {

		$sql21 = "SELECT up_nazwa, pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.up_id=$_REQUEST[new_upid]) and (serwis_piony.pion_id=serwis_komorki.up_pion_id) and (belongs_to=$es_filia) LIMIT 1";
		$wynik21 = mysql_query($sql21,$conn);
		list($nazwa,$pion) = mysql_fetch_array($wynik21);
		$SamaNazwaKomorki = $nazwa;
			
		if ($_REQUEST[szid]=='0') {
			if ($_REQUEST[status]!='7') {
				$sql_usun_z_serwisu = "UPDATE $dbname.serwis_naprawa SET naprawa_status=8 WHERE naprawa_id=$_REQUEST[uid] LIMIT 1";
					if (mysql_query($sql_usun_z_serwisu,$conn)) {
					} else {
						?><script>info('Wystąpił błąd podczas usuwania z napraw'); //self.close(); </script><?php
					}
			} else {
				$sql_usun_z_serwisu = "UPDATE $dbname.serwis_naprawa SET naprawa_status=8 WHERE naprawa_id=$_REQUEST[uid] LIMIT 1";	

				if (mysql_query($sql_usun_z_serwisu,$conn)) {
				
				} else {
						?><script>info('Wystąpił błąd podczas usuwania z napraw'); //self.close(); </script><?php
					}		
			}
			if ($_REQUEST[ewid_id]!=0) {
				$sql_zmien_status_w_ewid = "UPDATE $dbname.serwis_ewidencja SET ewidencja_status=9 WHERE ewidencja_id=$_REQUEST[ewid_id] LIMIT 1";
				if (mysql_query($sql_zmien_status_w_ewid,$conn)) {
				} else {
					?><script>info('Wystąpił błąd podczas zmiany statusu'); //self.close(); </script><?php
				}
			}
			?><script>if (opener) opener.location.reload(true);
			//self.close(); </script><?php
		} else {
			
			if ($_REQUEST[status]!='7') {
				$sql_usun_z_serwisu = "UPDATE $dbname.serwis_naprawa SET naprawa_status=8 WHERE naprawa_id=$_REQUEST[uid] LIMIT 1";	

				if (mysql_query($sql_usun_z_serwisu,$conn)) {
				} else {
						?><script>info('Wystąpił błąd podczas usuwania z napraw'); //self.close(); </script><?php
					}

			} else {
			
				$sql_usun_z_serwisu = "UPDATE $dbname.serwis_naprawa SET naprawa_status=8 WHERE naprawa_id=$_REQUEST[uid] LIMIT 1";	

				if (mysql_query($sql_usun_z_serwisu,$conn)) {
				} else {
						?><script>info('Wystąpił błąd podczas usuwania z napraw'); //self.close(); </script><?php
					}

			}
			
			if ($_REQUEST[serwisowy]=='NIE') {
				$sql_wroc_sprzet_na_magazyn = "UPDATE $dbname.serwis_magazyn SET magazyn_status=0, magazyn_naprawa_id='0' WHERE magazyn_id=$_REQUEST[szid] LIMIT 1";
				
				if (mysql_query($sql_wroc_sprzet_na_magazyn,$conn)) {
						
					if ($_REQUEST[uid]>0) {
						$wykonaj = mysql_query("UPDATE $dbname.serwis_naprawa SET naprawa_sprzet_zastepczy_id = '0' WHERE naprawa_id = '$_REQUEST[uid]' LIMIT 1", $conn) or die($k_b);			
					}
						
					$dddd = Date('Y-m-d H:i:s');
					$sql_t = "INSERT INTO $dbname.serwis_historia VALUES ('','$_REQUEST[szid]','$SamaNazwaKomorki','$currentuser','$dddd','zwrócono z','','0','$_REQUEST[hd_zgl_nr]',$es_filia)";
					if (mysql_query($sql_t, $conn)) { 
						$sql22 = "SELECT historia_id FROM $dbname.serwis_historia WHERE ((belongs_to=$es_filia) and (historia_data='$dddd') and (historia_user='$currentuser')) LIMIT 1";
						$wynik22 = mysql_query($sql22,$conn);
						list($last_hid) = mysql_fetch_array($wynik22);
							
						$sql21 = "UPDATE $dbname.serwis_historia SET historia_naprawa_id = '$_REQUEST[uid]' WHERE historia_id = '$last_hid' LIMIT 1";
						$wynik21 = mysql_query($sql21,$conn);
					
					} else {
						?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); </script><?php	
					}
				} else {
					?><script>info('Wystąpił błąd podczas aktualizowania magazynu');  </script><?php
				}
			} else {
			
				$sql_usun_z_serwisu = "UPDATE $dbname.serwis_naprawa SET naprawa_status=8 WHERE naprawa_id=$_REQUEST[uid] LIMIT 1";		
		
				if (mysql_query($sql_usun_z_serwisu,$conn)) {
				} else {
						?><script>info('Wystąpił błąd podczas usuwania z napraw'); //self.close(); </script><?php
					}
			}
			
			if ($_REQUEST[ewid_id]>0) {
				$sql_zmien_status_w_ewid = "UPDATE $dbname.serwis_ewidencja SET ewidencja_status=9 WHERE ewidencja_id=$_REQUEST[ewid_id] LIMIT 1";
				if (mysql_query($sql_zmien_status_w_ewid,$conn)) {
				} else {
				?><script>info('Wystąpił błąd podczas aktualizowania ewidencji'); //self.close(); </script><?php
				}
			}
		}
		

		
	  ?><script>if (opener) opener.location.reload(true); </script><?php
	  }   // koniec

	// ######################################################################################################
	?><script>
	<?php if (($source=='naprawy-przyjecie') && ($_REQUEST[tresc_zgl]=='')) { ?>
	if (opener) opener.location.reload(true);
	<?php } ?>	
	</script><?php
	}

}

?>
<?php

	if (($clear==1) || ($submit=='pusty')) {

		$dzien 		= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		$miesiac 	= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		$rok		= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		$c_1		= '';
		$c_2		= '';
		$c_3		= '';
		$c_4		= '';
		$c_5		= '';
		$c_6		= '';
		$c_7		= '';
		$c_8		= '';
		$put_up		= '';
		$put_nazwa_urzadzenia = '';
		$put_sn_urzadzenia = '';
		$put_ni_urzadzenia = '';
		$put_opis_uszkodzenia = '';
		$put_wykonane_czynnosci = '';
		$put_uwagi = '';
		$username = '';
		$pnr='';
	}

if ($dzien!='') $_REQUEST[dzien]=$dzien;
if ($miesiac!='') $_REQUEST[miesiac]=$miesiac;
if ($rok!='') $_REQUEST[rok]=$rok;

//print_r($_REQUEST);

if ($source=='only-protokol') {
	$c_7 = $_REQUEST[c_7];
	echo $_POST[c_7];
}

?>

  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="25" align="left" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
	  <span class="style34">PROTOKÓŁ 
	  <?php //if ($pnr!='') echo "&nbsp;NR:&nbsp;$pnr";  
	  
		if ($HD_nr_zgl!=0) {
			echo "<br />NZ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ".$HD_nr_zgl."";
		} else {
			echo "<br />NZ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;: -";
		}
		
		if (($_REQUEST[hadim_nr]>0) && ($_REQUEST[hadim_nr]!='')) {
			echo "<br />Nr Landesk&nbsp;: $_REQUEST[hadim_nr]";
		} else {
			echo "<br />Nr Landesk&nbsp;: -";
		}

	  ?>
      </span></td>
      <td height="25" align="left" bordercolor="#FFFFFF" bgcolor="#FFFFFF">&nbsp;</td>
      <td height="25" align="left" bordercolor="#FFFFFF" bgcolor="#FFFFFF">&nbsp;</td>
      <td height="25" colspan="2" align="right" valign="top" bordercolor="#ffffff" bgcolor="#ffffff"><span class="style8">Data&nbsp;&nbsp;<?php echo "$_REQUEST[dzien]"; ?>/<?php echo "$_REQUEST[miesiac]"; ?>/<?php echo "$_REQUEST[rok]"; ?>&nbsp;
      </span> </td>
    </tr>
	<tr height="5px">
	<td colspan="5">
	</td>
	</tr>
    <tr height="1px">
      <td colspan="4" align="left" valign="middle" nowrap bordercolor="#FFFFFF"><?php if ($c_1=="on") { echo "<img src=img/checkbox_on.gif border=0 align=absmiddle>"; } else echo "<img src=img/checkbox.gif border=0 align=absmiddle>"; ?>
        <span class="style88">Pobranie do naprawy uszkodzonego sprzętu</span></td>
      <td align="right" colspan="1" rowspan="9" valign="top" bordercolor="#FFFFFF"><div align="right"><img src="img/logo2.gif"></div></td>
    </tr>
    <tr height="1px">
      <td colspan="4" align="left" valign="middle" nowrap bordercolor="#FFFFFF"><?php if ($c_2=="on") { echo "<img src=img/checkbox_on.gif border=0 align=absmiddle>"; } else echo "<img src=img/checkbox.gif border=0 align=absmiddle>"; ?>
          <span class="style88">Przekazanie sprzętu serwisowego</span></td>
    </tr>
    <tr height="1px">
      <td colspan="4" align="left" valign="middle" nowrap bordercolor="#FFFFFF"><?php if ($c_3=="on") { echo "<img src=img/checkbox_on.gif border=0 align=absmiddle>"; } else echo "<img src=img/checkbox.gif border=0 align=absmiddle>"; ?>
          <span class="style88">
			<?php 
				if ($_REQUEST[source]=='naprawy-wycofaj') echo "Zwrot sprzętu ";
				if ($_REQUEST[source]!='naprawy-wycofaj') echo "Zwrot naprawionego sprzętu ";
			?>
		  </span></td>
    </tr>
    <tr height="1px">
      <td colspan="4" align="left" bordercolor="#FFFFFF"><?php if ($c_4=="on") { echo "<img src=img/checkbox_on.gif border=0 align=absmiddle>"; } else echo "<img src=img/checkbox.gif border=0 align=absmiddle>"; ?>
          <span class="style88">Pobranie sprzętu serwisowego </span></td>
    </tr>
    <tr height="1px">
      <td colspan="4" align="left" bordercolor="#FFFFFF"><?php if ($c_5=="on") { echo "<img src=img/checkbox_on.gif border=0 align=absmiddle>"; } else echo "<img src=img/checkbox.gif border=0 align=absmiddle>"; ?>
          <span class="style88"> Przekazanie sprzętu do naprawy - serwisu </span></td>
    </tr>
    <tr height="1px">
      <td colspan="4" align="left" valign="middle" bordercolor="#FFFFFF"><?php if ($c_6=="on") { echo "<img src=img/checkbox_on.gif border=0 align=absmiddle>"; } else echo "<img src=img/checkbox.gif border=0 align=absmiddle>"; ?>
          <span class="style88">Odbiór sprzętu z naprawy - serwisu</span></td>
    </tr>
    <tr height="1px">
      <td colspan="4" align="left" valign="middle" bordercolor="#FFFFFF"><?php if ($c_7=="on") { echo "<img src=img/checkbox_on.gif border=0 align=absmiddle>"; } else echo "<img src=img/checkbox.gif border=0 align=absmiddle>"; ?>
        <span class="style88">Wymiana części / remont sprzętu</span></td>
    </tr>
    <tr height="1px">
      <td colspan="4" align="left" valign="middle" bordercolor="#FFFFFF"><?php if ($c_8=="on") { echo "<img src=img/checkbox_on.gif border=0 align=absmiddle>"; } else echo "<img src=img/checkbox.gif border=0 align=absmiddle>"; ?>
          <span class="style88">Przekazanie zamówionego sprzętu </span><span class="style888"><br /><img src=img/checkbox_empty.gif border=0 align=absmiddle><i>*zaznacz właściwe pola</i></span></td>
    </tr>
    <tr height="10px">
      <td colspan="5"></td>
    </tr>
  </table>	
  <table width="100%" border="0" cellspacing="0" cellpadding="0">	
    <tr>
      <td height="20" colspan="5" align="left" bgcolor="#ffffff" class="style50"><span class="style37">SZCZEGÓŁOWY OPIS WYKONANYCH CZYNNOŚCI</span></td>
    </tr>
    <tr>
      <td width="30%" height="100" align="left" valign="top" nowrap="nowrap" class="style42"><span class="style8x">&nbsp;Nazwa komórki-pieczątka</span></td>
      <td colspan="3" align="center" valign="top" bordercolor="#FFFFFF" class="style43"><div align="left" class="style2525"><em>&nbsp;Nazwa komórki</em></div>
          <br /><span class="style34x">
          <?php echo "<b>$put_up</b>"; ?>
	  </span></td>
      <td width="33%" align="left" valign="top" bordercolor="#FFFFFF" class="style42"><div align="left" class="style2525"><em>&nbsp;Pieczątka</em></div>
      <br /></td>
    </tr>
    <tr>
      <td height="25" align="left" class="style44"><span class="style8x">&nbsp;Nazwa urządzenia </span></td>
      <td colspan="4" align="left" class="style45"><span class="style34x">
	  <?php 
	  echo "<b>".substr(cleanup($put_nazwa_urzadzenia),0,50)."</b>"; ?></span></td>
    </tr>
    <tr>
      <td height="25" align="left" class="style44"><span class="style8x">&nbsp;Nr. seryjny urządzenia </span></td>
      <td colspan="4" align="left" class="style45"><span class="style34x">
	  <?php 
		echo "<b>".substr(cleanup($put_sn_urzadzenia),0,50)."</b>"; ?></span></td>
    </tr>
    <tr>
      <td height="25" align="left" class="style44"><span class="style8x">&nbsp;Nr. inwentarzowy </span></td>
      <td colspan="4" align="left" class="style45"><span class="style34x"><?php
		echo "<b>".substr(cleanup($put_ni_urzadzenia),0,50)."</b>"; ?></span></td>
    </tr>
    <tr>
      <td height="80" align="left" valign="top" class="style44"><span class="style8x">&nbsp;Opis uszkodzenia </span></td>
      <td colspan="4" align="left" valign="top" class="style45"><span class="style34x">
	  <?php echo "<b>".nl2br(cleanup($put_opis_uszkodzenia))."</b>"; ?></span></td>
    </tr>
    <tr>
      <td height="100" align="left" valign="top" class="style44"><span class="style8x">&nbsp;Wykonane czynności </span></td>
      <td colspan="4" align="left" valign="top" class="style45"><span class="style34x"><?php echo "<b>".nl2br(cleanup($put_wykonane_czynnosci))."</b>"; ?></span></td>
    </tr>
    <tr>
      <td height="80" align="left" valign="top" class="style44"><span class="style8x">&nbsp;Uwagi</span></td>
      <td colspan="4" align="left" valign="top" class="style45"><span class="style34x"><?php echo "<b>".nl2br(cleanup($put_uwagi))."</b>"; ?></span></td>
    </tr>
</table>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="26" colspan="4" bgcolor="#ffffff" class="style51">&nbsp;<span class="style25"><strong>POTWIERDZENIE WYKONANIA </strong></span></td>
    </tr>
    <tr class="style32">
      <td rowspan="4" valign="top" class="style46"><div align="right" class="style36 style25"><strong><span class="style36">POCZTA :</span>&nbsp;<br />
                <br />
      UWAGI :&nbsp;</strong></div></td>
      <td colspan="4" valign="top" class="style47"> Poniższym podpisem potwierdzam odbiór usługi/dostawy </td>
    </tr>
    <tr>
      <td colspan="4" align="left" valign="bottom" class="style47a"><br />
        ..............................................................................................................................................<br />
        <br />
		</td>
    </tr>
    <tr>
      <td class="style47null"><div align="center" class="style47null">.................................................................. </div></td>
      <td class="style47b"><div align="center" class="style36">.................................................................. </div></td>
    </tr>
    <tr>
      <td class="style47null"><div align="center" class="style36">Pieczęć jednostki </div></td>
      <td class="style47b"><div align="center" class="style36">Podpis osoby odbierającej prace </div></td>
    </tr>
	<tr>
	<td colspan="4" class="style44">&nbsp;</td>
	</tr>
    <tr>
      <td class="style46a"><div align="right" class="style36"><strong>&nbsp;POSTDATA :&nbsp;</strong></div>
	  </td>
      <td colspan="2" class="style47d">Poniższym podpisem potwierdzam wykonanie Usługi/dostawy</td>
    </tr>
	<tr>
	<td colspan="4" class="style47lr">&nbsp;</td>
	</tr>

    <tr>
      <td rowspan="3" class="style46a">&nbsp;
      </td>
    </tr>
    <tr>
      <td class="style47null">&nbsp;</td>
      <td class="style47b"><div align="center">..................................................................</div></td>
    </tr>
    <tr>
      <td class="style47null">&nbsp;</td>
      <td class="style47b"><div align="center"><span class="style36">Podpis osoby wykonującej prace</span></div></td>
    </tr>
    <tr>
      <td colspan="4" class="style44"><br />
      </td>
    </tr>
</table>

<div class="hideme" align="center">
<hr />

<a href="#" style="vertical-align:bottom; cursor:hand" onClick="window.print(); 
<?php if (($source=='naprawy-zwrot') && ($_REQUEST[from]=='') && ($_REQUEST[hd_zgl_nr]>0)) { ?>
	if (confirm('Czy przejść do obsługi zgłoszenia nr <?php echo $_REQUEST[hd_zgl_nr]; ?> ?')) { self.close(); newWindow_r(800,600,'hd_o_zgloszenia.php?action=obsluga&id=<?php echo $_REQUEST[hd_zgl_nr]; ?>&nr=<?php echo $_REQUEST[hd_zgl_nr]; ?>'); return false;}
<?php } ?>
"><img src="img/drukuj_protokol.jpg" border="0"></a>
<?php
if (($readonly!=1) || ($es_prawa=='9')) {
?>

<?php
if ($clear) {
	$dzien		= Date('d');
	$miesiac	= Date('m');
	$rok		= Date('Y');
}

if ($submit!='pusty') {
	if (($source!='towary-sprzedaz') || 
		($source!='naprawy-zwrot')
		)
	{
	
	$HD_nr_zgl = $_REQUEST[hd_zgl_nr];
?>

<a style="vertical-align:bottom" href="utworz_protokol.php?hd_zgl_nr=<?php echo $HD_nr_zgl;?>&view=<?php echo $_REQUEST[view]; ?>&pnr=<?php echo "$pnr";?>&dzien=<?php echo "$_REQUEST[dzien]"; ?>&miesiac=<?php echo "$_REQUEST[miesiac]"; ?>&rok=<?php echo "$_REQUEST[rok]"; ?>&c_1=<?php echo "$c_1"; ?>&c_2=<?php echo "$c_2"; ?>&c_3=<?php echo "$c_3"; ?>&c_4=<?php echo "$c_4"; ?>&c_5=<?php echo "$c_5"; ?>&c_6=<?php echo "$c_6"; ?>&c_7=<?php echo "$c_7"; ?>&c_8=<?php echo "$c_8"; ?>&up=<?php echo urlencode($up); ?>&tup=<?php echo urlencode($_REQUEST[tup]); ?>&nazwa_urzadzenia=<?php echo urlencode($nazwa_urzadzenia); ?>&sn_urzadzenia=<?php echo urlencode($sn_urzadzenia); ?>&ni_urzadzenia=<?php echo urlencode($ni_urzadzenia); ?>&opis_uszkodzenia=<?php echo urlencode($_REQUEST[opis_uszkodzenia]);?>&wykonane_czynnosci=<?php echo urlencode($wykonane_czynnosci); ?>&uwagi=<?php echo urlencode($uwagi); ?>&format_nr=<?php echo "$format_nr"; ?>&imieinazwisko=<?php echo urlencode($imieinazwisko); ?>&wersjap=<?php echo "$wersjap";?>&unr=<?php echo $unr_session;?>&state=<?php echo "$_REQUEST[state]"; ?>&source=<?php echo $_REQUEST[source]; ?>&state=<?php echo $_REQUEST[state];?>&popraw=1&wstecz=<?php echo $_REQUEST[wstecz1];?>&sz=<?php echo $_REQUEST[sz]; ?>&id=<?php echo $_REQUEST[id]; ?>&nowy=<?php echo $_REQUEST[nowy];?>&nazwa_sprzetu=<?php echo urlencode($_REQUEST[nazwa_sprzetu]);?>&sn_sprzetu=<?php echo urlencode($_REQUEST[sn_sprzetu]);?>&pn=<?php echo $_REQUEST[pn];?>&szid=<?php echo $_REQUEST[szid];?>&status=<?php echo $_REQUEST[status];?>&uid=<?php echo $_REQUEST[uid];?>&serwisowy=<?php echo $_REQUEST[serwisowy];?>&ewid=<?php echo $_REQUEST[ewid];?>&nierobzapytan=1&antyF5=<?php echo $_REQUEST[antyF5];?>&obzp=<?php echo $_REQUEST[obzp]; ?>&tdata=<?php echo $_REQUEST[pdata]; ?>&tid=<?php echo $_REQUEST[tid]; ?>&nzpdb=<?php echo $_REQUEST[nzpdb]; ?>&odswiez=<?php echo $odswiez;?>&new_upid=<?php echo $_REQUEST[new_upid]; ?>&typ_urzadzenia=<?php echo $_REQUEST[typ_urzadzenia];?>&model_urzadzenia=<?php echo $_REQUEST[model_urzadzenia];?>&ewid_id=<?php echo $_REQUEST[ewid_id];?>&zestaw=<?php echo $_REQUEST[zestaw];?>&zid=<?php echo $_REQUEST[zid];?>&f=<?php echo $_REQUEST[f];?>&pozid=<?php echo $_REQUEST[pozid];?>&trodzaj=<?php echo $_REQUEST[trodzaj];?>&edit_towar=<?php echo $_REQUEST[edit_towar];?>&edit_zestaw=<?php echo $_REQUEST[edit_zestaw];?>&hd_nr=<?php echo $_REQUEST[hd_nr];?>&quiet=<?php echo $_REQUEST[quiet]; ?>&dodajwymianepodzespolow=<?php echo $_REQUEST[dodajwymianepodzespolow]; ?>&naprawaid=<?php echo $_REQUEST[naprawaid];?>&from=<?php echo $_REQUEST[from];?>&PNZSS=<?php echo $_REQUEST[PNZSS];?>"><img src="img/popraw.jpg" border="0"></a>

<?php 
 }
}	// pomiń poprawki
}	
?>
<?php if (($source=='naprawy-przyjecie') && ($_REQUEST[tresc_zgl]=='')) { ?>
	<a href="#" style="cursor:hand;vertical-align:bottom" onClick="if (opener) opener.location.reload(true); self.close();"><img src="img//zamknij_protokol.jpg" border="0"></a>
<?php } else { ?>
	<a href="#" style="cursor:hand;vertical-align:bottom" onClick="
	
	<?php if (($source=='naprawy-zwrot') && ($_REQUEST[from]=='') && ($_REQUEST[hd_zgl_nr]>0)) { ?>
	if (confirm('Czy przejść do obsługi zgłoszenia nr <?php echo $_REQUEST[hd_zgl_nr]; ?> ?')) { self.close(); newWindow_r(800,600,'hd_o_zgloszenia.php?action=obsluga&id=<?php echo $_REQUEST[hd_zgl_nr]; ?>&nr=<?php echo $_REQUEST[hd_zgl_nr]; ?>'); return false; } else { self.close(); }
	<?php } else { echo "self.close();"; } ?>
	
	"><img src="img//zamknij_protokol.jpg" border="0"></a>
<?php } ?>
<br />
</div>

<?php 
}
// koniec protokołu w wersji 2(generuj protokół)	<img src="img/zamknij_protokol.jpg" border="0">
?>
<script>HideWaitingMessage('Saving1');</script>
<?php
//echo ">>>$akcja";
//print_r($_SESSION);
ob_flush();
flush();
	
if ($akcja=='insert') { 

	if ($_REQUEST[nzpdb]!='on') { ?><script>alert('Pomyślnie dodano protokół do bazy');</script><?php }
	if ($_REQUEST[nzpdb]=='on') { /* ?><script>alert('Pomyślnie dodano protokół do bazy');</script><?php */ }

}

if (($akcja=='update') && ($_REQUEST[fp]!=1) && ($_REQUEST[nzpdb]!='on')) { ?><script>if (opener) opener.location.reload(true);alert('Zaktualizowano protokół w bazie');</script><?php }	
?>


</body>
</html>