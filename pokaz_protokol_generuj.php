<?php
include_once('header_simple.php'); ?>
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
</script>

</head>

<body>

<div class="hideme" align="center">
<a href="#" style="vertical-align:bottom; cursor:hand" onClick="window.print();"><img src="img/drukuj_protokol.jpg" border="0"></a>

<?php

if ($clear) {
	$dzien		= Date('d');
	$miesiac	= Date('m');
	$rok		= Date('Y');
}

if ($submit!='pusty') {
	//if (($source!='towary-sprzedaz') || ($source!='naprawy-zwrot'))
	//{
	
	$HD_nr_zgl = $_REQUEST[_hd_zgl_nr];
?>

<a style="vertical-align:bottom" href="utworz_protokol.php?hd_zgl_nr=<?php echo $_REQUEST[_hd_zgl_nr];?>&view=<?php echo $_REQUEST[_view]; ?>&pnr=<?php echo  $_REQUEST[_pnr]; ?>&dzien=<?php echo "$_REQUEST[_dzien]"; ?>&miesiac=<?php echo "$_REQUEST[_miesiac]"; ?>&rok=<?php echo "$_REQUEST[_rok]"; ?>&c_1=<?php echo "$_c_1"; ?>&c_2=<?php echo "$_c_2"; ?>&c_3=<?php echo "$_c_3"; ?>&c_4=<?php echo "$_c_4"; ?>&c_5=<?php echo "$_c_5"; ?>&c_6=<?php echo "$_c_6"; ?>&c_7=<?php echo "$_c_7"; ?>&c_8=<?php echo "$_c_8"; ?>&up=<?php echo urlencode($_up); ?>&tup=<?php echo urlencode($_REQUEST[_tup]); ?>&nazwa_urzadzenia=<?php echo urlencode($_nazwa_urzadzenia); ?>&sn_urzadzenia=<?php echo urlencode($_sn_urzadzenia); ?>&ni_urzadzenia=<?php echo urlencode($_ni_urzadzenia); ?>&opis_uszkodzenia=<?php echo urlencode($_REQUEST[_opis_uszkodzenia]);?>&wykonane_czynnosci=<?php echo urlencode($_wykonane_czynnosci); ?>&uwagi=<?php echo urlencode($_uwagi); ?>&format_nr=<?php echo "$_format_nr"; ?>&imieinazwisko=<?php echo urlencode($_imieinazwisko); ?>&wersjap=<?php echo "$_wersjap";?>&unr=<?php echo $unr_session;?>&state=<?php echo "$_REQUEST[_state]"; ?>&source=<?php echo $_REQUEST[_source]; ?>&state=<?php echo $_REQUEST[_state];?>&popraw=1&wstecz=<?php echo $_REQUEST[_wstecz1];?>&sz=<?php echo $_REQUEST[_sz]; ?>&id=<?php echo $_REQUEST[_id]; ?>&nowy=<?php echo $_REQUEST[_nowy];?>&nazwa_sprzetu=<?php echo urlencode($_REQUEST[_nazwa_sprzetu]);?>&sn_sprzetu=<?php echo urlencode($_REQUEST[_sn_sprzetu]);?>&pn=<?php echo $_REQUEST[_pn];?>&szid=<?php echo $_REQUEST[_szid];?>&status=<?php echo $_REQUEST[_status];?>&uid=<?php echo $_REQUEST[_uid];?>&serwisowy=<?php echo $_REQUEST[_serwisowy];?>&ewid=<?php echo $_REQUEST[_ewid];?>&nierobzapytan=1&antyF5=<?php echo $_REQUEST[_antyF5];?>&obzp=<?php echo $_REQUEST[_obzp]; ?>&tdata=<?php echo $_REQUEST[_pdata]; ?>&tid=<?php echo $_REQUEST[_tid]; ?>&nzpdb=<?php echo $_REQUEST[_nzpdb]; ?>&odswiez=<?php echo $odswiez;?>&new_upid=<?php echo $_REQUEST[_new_upid]; ?>&typ_urzadzenia=<?php echo $_REQUEST[_typ_urzadzenia];?>&model_urzadzenia=<?php echo $_REQUEST[_model_urzadzenia];?>&ewid_id=<?php echo $_REQUEST[_ewid_id];?>&zestaw=<?php echo $_REQUEST[_zestaw];?>&zid=<?php echo $_REQUEST[_zid];?>&f=<?php echo $_REQUEST[_f];?>&pozid=<?php echo $_REQUEST[_pozid];?>&trodzaj=<?php echo $_REQUEST[_trodzaj];?>&edit_towar=<?php echo $_REQUEST[_edit_towar];?>&edit_zestaw=<?php echo $_REQUEST[_edit_zestaw];?>&hd_nr=<?php echo $_REQUEST[_hd_nr];?>&quiet=<?php echo $_REQUEST[_quiet]; ?>&unr=<?php echo $_REQUEST[unr]; ?>"><img src="img/popraw.jpg" border="0"></a>

<?php if (($_source=='naprawy-przyjecie') && ($_REQUEST[tresc_zgl]=='')) { ?>
	<a href="#" style="cursor:hand;vertical-align:bottom" onClick="if (opener) opener.location.reload(true); self.close();"><img src="img//zamknij_protokol.jpg" border="0"></a>
<?php } else { ?>
	<a href="#" style="cursor:hand;vertical-align:bottom" onClick="self.close();"><img src="img//zamknij_protokol.jpg" border="0"></a>
<?php } ?>

<?php 
 
//}	// pomiń poprawki
}	
?>

<hr />
</div>

<?php

$source = $_REQUEST[_source];

//echo "SELECT up_id,up_nazwa,up_pion_id,up_umowa_id,pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (up_active=1) and (belongs_to=$es_filia) and (serwis_komorki.up_id=$_REQUEST[new_upid]) LIMIT 1";

?>

<?php

if ($_REQUEST[_new_upid]=="") { 
	$put_up="-";
} else { 
// ustal pełną nazwę komorki (pion + nazwa)
$result = mysql_query("SELECT up_id,up_nazwa,up_pion_id,up_umowa_id,pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (up_active=1) and (belongs_to=$es_filia) and (serwis_komorki.up_id=$_REQUEST[_new_upid]) LIMIT 1", $conn) or die($k_b);

list($temp_id,$temp_nazwa,$temp_pion_id,$temp_umowa_id,$temp_pionnazwa)=mysql_fetch_array($result);

$put_up=$temp_pionnazwa." ".$temp_nazwa;

}

//echo "$source";
//print_r($_REQUEST);

if ($nazwa_urzadzenia=="") {$put_nazwa_urzadzenia="-";}  else { $put_nazwa_urzadzenia=$_nazwa_urzadzenia; }
if ($sn_urzadzenia==""){ $put_sn_urzadzenia="-";} else { $put_sn_urzadzenia=$_sn_urzadzenia; }
if ($ni_urzadzenia=="") {$put_ni_urzadzenia="-";} else { $put_ni_urzadzenia=$_ni_urzadzenia; }
if ($opis_uszkodzenia==""){ $put_opis_uszkodzenia="-";} else { $put_opis_uszkodzenia=$_opis_uszkodzenia; }
if ($wykonane_czynnosci==""){ $put_wykonane_czynnosci="-";} else { $put_wykonane_czynnosci=$_wykonane_czynnosci; }
if ($uwagi=="") {$put_uwagi="brak";} else { $put_uwagi=$_uwagi; }

//echo "$pdata";

if ($popraw!=1) {
	$rok		= substr($_pdata,0,4);
	$miesiac	= substr($_pdata,5,2);
	$dzien		= substr($_pdata,8,2);
}

if ($obzp==1) {
$rok		= substr($_pdata,0,4);
$miesiac	= substr($_pdata,5,2);
$dzien		= substr($_pdata,8,2);
}

//if ($submit=="Generuj") {
	$rok		= substr($_pdata,0,4);
	$miesiac	= substr($_pdata,5,2);
	$dzien		= substr($_pdata,8,2);
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

$username = $_currentuser;
$format_nr=$es_skrot."/".$_REQUEST[_miesiac]."/".$_REQUEST[_rok];
$numerprotokolu='';
if ($pnr=='') $numerprotokolu='';
if (($pnr!='') && (strlen($pnr)<3)) {
	$numerprotokolu=$pnr."/".$format_nr;
	//$numerprotokolu=$pnr;
} else { $numerprotokolu=$_pnr; }

//echo "numerprotokolu = $numerprotokolu<br />";

if ($dzien=='') $dzien=$_REQUEST[_dzien];
if ($miesiac=='') $miesiac=$_REQUEST[_miesiac];
if ($rok=='') $rok=$_REQUEST[_rok];

if ($odswiez==1) {
	$_SESSION[unr_session]=$unr;
	//$unr_session = $_REQUEST[unr];	
}

if ($imieinazwisko!='') $username=$_imieinazwisko;

if ($czysty) {
	$clear 	= 1;
	$submit	= 'ok';
} else $clear = 0;

if (($_wersjap==2) && ($_submit)) {


?>
<?php

	if (($_clear==1) || ($_submit=='pusty')) {

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
	} else {
		$put_nazwa_urzadzenia = $_REQUEST[_nazwa_urzadzenia];
		$put_sn_urzadzenia = $_REQUEST[_sn_urzadzenia];
		$put_ni_urzadzenia = $_REQUEST[_ni_urzadzenia];
		$put_opis_uszkodzenia = $_REQUEST[_opis_uszkodzenia];
		$put_wykonane_czynnosci = $_REQUEST[_wykonane_czynnosci];
		$put_uwagi = $_REQUEST[_uwagi];
	}

if ($dzien!='') $_REQUEST[dzien]=$_dzien;
if ($miesiac!='') $_REQUEST[miesiac]=$_miesiac;
if ($rok!='') $_REQUEST[rok]=$_rok;

//print_r($_REQUEST);

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
      <td height="25" colspan="2" align="right" valign="top" bordercolor="#ffffff" bgcolor="#ffffff"><span class="style8">Data&nbsp;&nbsp;<?php echo "$_REQUEST[_dzien]"; ?>/<?php echo "$_REQUEST[_miesiac]"; ?>/<?php echo "$_REQUEST[_rok]"; ?>&nbsp;
      </span> </td>
    </tr>
	<tr height="5px">
	<td colspan="5">
	</td>
	</tr>
    <tr height="1px">
      <td colspan="4" align="left" valign="middle" nowrap bordercolor="#FFFFFF"><?php if ($_c_1=="on") { echo "<img src=img/checkbox_on.gif border=0 align=absmiddle>"; } else echo "<img src=img/checkbox.gif border=0 align=absmiddle>"; ?>
        <span class="style88">Pobranie do naprawy uszkodzonego sprzętu</span></td>
      <td align="right" colspan="1" rowspan="9" valign="top" bordercolor="#FFFFFF"><div align="right"><img src="img/logo2.gif"></div></td>
    </tr>
    <tr height="1px">
      <td colspan="4" align="left" valign="middle" nowrap bordercolor="#FFFFFF"><?php if ($_c_2=="on") { echo "<img src=img/checkbox_on.gif border=0 align=absmiddle>"; } else echo "<img src=img/checkbox.gif border=0 align=absmiddle>"; ?>
          <span class="style88">Przekazanie sprzętu serwisowego</span></td>
    </tr>
    <tr height="1px">
      <td colspan="4" align="left" valign="middle" nowrap bordercolor="#FFFFFF"><?php if ($_c_3=="on") { echo "<img src=img/checkbox_on.gif border=0 align=absmiddle>"; } else echo "<img src=img/checkbox.gif border=0 align=absmiddle>"; ?>
          <span class="style88">
			<?php 
				if ($_REQUEST[_source]=='naprawy-wycofaj') echo "Zwrot sprzętu ";
				if ($_REQUEST[_source]!='naprawy-wycofaj') echo "Zwrot naprawionego sprzętu ";
			?>
		  </span></td>
    </tr>
    <tr height="1px">
      <td colspan="4" align="left" bordercolor="#FFFFFF"><?php if ($_c_4=="on") { echo "<img src=img/checkbox_on.gif border=0 align=absmiddle>"; } else echo "<img src=img/checkbox.gif border=0 align=absmiddle>"; ?>
          <span class="style88">Pobranie sprzętu serwisowego </span></td>
    </tr>
    <tr height="1px">
      <td colspan="4" align="left" bordercolor="#FFFFFF"><?php if ($_c_5=="on") { echo "<img src=img/checkbox_on.gif border=0 align=absmiddle>"; } else echo "<img src=img/checkbox.gif border=0 align=absmiddle>"; ?>
          <span class="style88"> Przekazanie sprzętu do naprawy - serwisu </span></td>
    </tr>
    <tr height="1px">
      <td colspan="4" align="left" valign="middle" bordercolor="#FFFFFF"><?php if ($_c_6=="on") { echo "<img src=img/checkbox_on.gif border=0 align=absmiddle>"; } else echo "<img src=img/checkbox.gif border=0 align=absmiddle>"; ?>
          <span class="style88">Odbiór sprzętu z naprawy - serwisu</span></td>
    </tr>
    <tr height="1px">
      <td colspan="4" align="left" valign="middle" bordercolor="#FFFFFF"><?php if ($_c_7=="on") { echo "<img src=img/checkbox_on.gif border=0 align=absmiddle>"; } else echo "<img src=img/checkbox.gif border=0 align=absmiddle>"; ?>
        <span class="style88">Wymiana części / remont sprzętu</span></td>
    </tr>
    <tr height="1px">
      <td colspan="4" align="left" valign="middle" bordercolor="#FFFFFF"><?php if ($_c_8=="on") { echo "<img src=img/checkbox_on.gif border=0 align=absmiddle>"; } else echo "<img src=img/checkbox.gif border=0 align=absmiddle>"; ?>
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
      <td height="130" align="left" valign="top" class="style44"><span class="style8x">&nbsp;Wykonane czynności </span></td>
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
<a href="#" style="vertical-align:bottom; cursor:hand" onClick="window.print();"><img src="img/drukuj_protokol.jpg" border="0"></a>
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
	if (($_source!='towary-sprzedaz') || 
		($_source!='naprawy-zwrot')
		)
	{
	
	$HD_nr_zgl = $_REQUEST[_hd_zgl_nr];
?>

<a style="vertical-align:bottom" href="utworz_protokol.php?hd_zgl_nr=<?php echo $_REQUEST[_hd_zgl_nr];?>&view=<?php echo $_REQUEST[_view]; ?>&pnr=<?php echo  $_REQUEST[_pnr]; ?>&dzien=<?php echo "$_REQUEST[_dzien]"; ?>&miesiac=<?php echo "$_REQUEST[_miesiac]"; ?>&rok=<?php echo "$_REQUEST[_rok]"; ?>&c_1=<?php echo "$_c_1"; ?>&c_2=<?php echo "$_c_2"; ?>&c_3=<?php echo "$_c_3"; ?>&c_4=<?php echo "$_c_4"; ?>&c_5=<?php echo "$_c_5"; ?>&c_6=<?php echo "$_c_6"; ?>&c_7=<?php echo "$_c_7"; ?>&c_8=<?php echo "$_c_8"; ?>&up=<?php echo urlencode($_up); ?>&tup=<?php echo urlencode($_REQUEST[_tup]); ?>&nazwa_urzadzenia=<?php echo urlencode($_nazwa_urzadzenia); ?>&sn_urzadzenia=<?php echo urlencode($_sn_urzadzenia); ?>&ni_urzadzenia=<?php echo urlencode($_ni_urzadzenia); ?>&opis_uszkodzenia=<?php echo urlencode($_REQUEST[_opis_uszkodzenia]);?>&wykonane_czynnosci=<?php echo urlencode($_wykonane_czynnosci); ?>&uwagi=<?php echo urlencode($_uwagi); ?>&format_nr=<?php echo "$_format_nr"; ?>&imieinazwisko=<?php echo urlencode($_imieinazwisko); ?>&wersjap=<?php echo "$_wersjap";?>&unr=<?php echo $unr_session;?>&state=<?php echo "$_REQUEST[_state]"; ?>&source=<?php echo $_REQUEST[_source]; ?>&state=<?php echo $_REQUEST[_state];?>&popraw=1&wstecz=<?php echo $_REQUEST[_wstecz1];?>&sz=<?php echo $_REQUEST[_sz]; ?>&id=<?php echo $_REQUEST[_id]; ?>&nowy=<?php echo $_REQUEST[_nowy];?>&nazwa_sprzetu=<?php echo urlencode($_REQUEST[_nazwa_sprzetu]);?>&sn_sprzetu=<?php echo urlencode($_REQUEST[_sn_sprzetu]);?>&pn=<?php echo $_REQUEST[_pn];?>&szid=<?php echo $_REQUEST[_szid];?>&status=<?php echo $_REQUEST[_status];?>&uid=<?php echo $_REQUEST[_uid];?>&serwisowy=<?php echo $_REQUEST[_serwisowy];?>&ewid=<?php echo $_REQUEST[_ewid];?>&nierobzapytan=1&antyF5=<?php echo $_REQUEST[_antyF5];?>&obzp=<?php echo $_REQUEST[_obzp]; ?>&tdata=<?php echo $_REQUEST[_pdata]; ?>&tid=<?php echo $_REQUEST[_tid]; ?>&nzpdb=<?php echo $_REQUEST[_nzpdb]; ?>&odswiez=<?php echo $odswiez;?>&new_upid=<?php echo $_REQUEST[_new_upid]; ?>&typ_urzadzenia=<?php echo $_REQUEST[_typ_urzadzenia];?>&model_urzadzenia=<?php echo $_REQUEST[_model_urzadzenia];?>&ewid_id=<?php echo $_REQUEST[_ewid_id];?>&zestaw=<?php echo $_REQUEST[_zestaw];?>&zid=<?php echo $_REQUEST[_zid];?>&f=<?php echo $_REQUEST[_f];?>&pozid=<?php echo $_REQUEST[_pozid];?>&trodzaj=<?php echo $_REQUEST[_trodzaj];?>&edit_towar=<?php echo $_REQUEST[_edit_towar];?>&edit_zestaw=<?php echo $_REQUEST[_edit_zestaw];?>&hd_nr=<?php echo $_REQUEST[_hd_nr];?>&quiet=<?php echo $_REQUEST[_quiet]; ?>&unr=<?php echo $_REQUEST[unr]; ?>"><img src="img/popraw.jpg" border="0"></a>

<?php 
 }
}	// pomiń poprawki
}	
?>
<?php if (($_source=='naprawy-przyjecie') && ($_REQUEST[tresc_zgl]=='')) { ?>
	<a href="#" style="cursor:hand;vertical-align:bottom" onClick="if (opener) opener.location.reload(true); self.close();"><img src="img//zamknij_protokol.jpg" border="0"></a>
<?php } else { ?>
	<a href="#" style="cursor:hand;vertical-align:bottom" onClick="self.close();"><img src="img//zamknij_protokol.jpg" border="0"></a>
<?php } ?>
<br />
</div>

<?php 
}
// koniec protokołu w wersji 2(generuj protokół)	<img src="img/zamknij_protokol.jpg" border="0">
//print_r($_SESSION);

ob_flush();
flush();

if (($akcja=='update') && ($_REQUEST[fp]!=1) && ($_REQUEST[nzpdb]!='on')) { ?><script>if (opener) opener.location.reload(true);alert('Zaktualizowano protokół w bazie');</script><?php }	

?>

</body>
</html>