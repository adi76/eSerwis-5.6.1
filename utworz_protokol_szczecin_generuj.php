<?php
if ($ewidencja) {
	header("Location: ".$linkdostrony."e_protokol_z_ewidencji.php?c_1=$c_1&c_2=$c_2&c_3=$c_3&c_4=$c_4&c_5=$c_5&c_6=$c_6&c_7=$c_7&c_8=$c_8&opis_uszkodzenia=".$opis_uszkodzenia."&wykonane_czynnosci=".$wykonane_czynnosci."&uwagi=".$uwagi."");
}
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
style50a{margin:0px;background-position:center;font-family:Arial,Helvetica,sans-serif;border-top-width:1px;border-right-width:0px;border-bottom-width:1px;border-left-width:1px;border-top-style:solid;border-right-style:solid;border-bottom-style:none;border-left-style:solid;border-top-color:#000000;border-right-color:#000000;border-bottom-color:#000000;border-left-color:#000000;padding-top:3px;padding-right:3px;padding-bottom:3px;padding-left:3px;font-size:14px;font-weight:bold;}

.style51{margin:0px;background-position:center;font-family:Arial,Helvetica,sans-serif;border-top-width:1px;border-right-width:1px;border-bottom-width:1px;border-left-width:1px;border-top-style:none;border-right-style:solid;border-bottom-style:none;border-left-style:solid;border-top-color:#000000;border-right-color:#000000;border-bottom-color:#000000;border-left-color:#000000;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:3px;font-size:14px;font-weight:bold;}
.style36{font-size:14px;}
.style37{font-size:14;}
.style38{font-size:16;}

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

</head>

<body>

<?php	

if ($up=="") { $put_up="-";} else { $put_up=$up; }
if ($nazwa_urzadzenia=="") {$put_nazwa_urzadzenia="-";}  else { $put_nazwa_urzadzenia=$nazwa_urzadzenia; }
if ($sn_urzadzenia==""){ $put_sn_urzadzenia="-";} else { $put_sn_urzadzenia=$sn_urzadzenia; }
if ($ni_urzadzenia=="") {$put_ni_urzadzenia="-";} else { $put_ni_urzadzenia=$ni_urzadzenia; }
if ($opis_uszkodzenia==""){ $put_opis_uszkodzenia="-";} else { $put_opis_uszkodzenia=$opis_uszkodzenia; }
if ($wykonane_czynnosci==""){ $put_wykonane_czynnosci="-";} else { $put_wykonane_czynnosci=$wykonane_czynnosci; }
if ($uwagi=="") {$put_uwagi="brak";} else { $put_uwagi=$uwagi; }

if ($readonly!=1) {

$rok		= substr($pdata,0,4);
$miesiac	= substr($pdata,5,2);
$dzien		= substr($pdata,8,2);

}

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

$username = $currentuser;
$format_nr=$es_skrot."/".$miesiac."/".$rok;
$numerprotokolu='';
if ($pnr=='') $numerprotokolu='';
if ($pnr!='') $numerprotokolu=$pnr."/".$format_nr;

if ($imieinazwisko!='') $username=$imieinazwisko;

if ($zapisz) {
if ($unr!='') {
	$sql = "SELECT * FROM $dbname.serwis_protokoly_historia WHERE (protokol_unique='$unr')";
	$result = mysql_query($sql, $conn) or die($k_b);
	$dane = mysql_fetch_array($result);
	$prid = $dane['protokol_id'];
	$count_rows = mysql_num_rows($result);
} else $count_rows=0;

$akcja = '';
if ($count_rows==0) {
	$unr='';
	$litery=array('a','b','c','d','e','f','g','h','i','j');
	for ($q=1;$q<26;$q++) { $unr.= rand(0,9); $unr.=$litery[rand(0,9)]; }
	$sql_zapis = "INSERT INTO $dbname.serwis_protokoly_historia VALUES ('','$numerprotokolu','$dzien','$miesiac','$rok','$c_1','$c_2','$c_3','$c_4','$c_5','$c_6','$c_7','$c_8','$put_up','$put_nazwa_urzadzenia','$put_sn_urzadzenia','$put_ni_urzadzenia','$put_opis_uszkodzenia','$put_wykonane_czynnosci','$put_uwagi',$wersjap,'$username',$es_filia,'$unr')";
	$akcja='insert';
} else {
	$sql_zapis = "UPDATE $dbname.serwis_protokoly_historia SET protokol_nr='$numerprotokolu', protokol_d='$dzien', protokol_m='$miesiac', protokol_r='$rok', protokol_c1 = '$c_1',protokol_c2 = '$c_2',protokol_c3 = '$c_3',protokol_c4 = '$c_4',protokol_c5 = '$c_5',protokol_c6 = '$c_6',protokol_c7 = '$c_7',protokol_c8 = '$c_8',protokol_nazwa_urzadzenia='$put_nazwa_urzadzenia', protokol_sn_urzadzenia='$put_sn_urzadzenia', protokol_ni_urzadzenia='$put_ni_urzadzenia',protokol_opis_uszkodzenia='$put_opis_uszkodzenia', protokol_wykonane_czynnosci='$put_wykonane_czynnosci',protokol_uwagi='$put_uwagi',protokol_wersja='$wersjap', protokol_kto='$username', protokol_up='$up' WHERE protokol_unique='$unr'  LIMIT 1";
	$akcja='update';
}

if (mysql_query($sql_zapis, $conn)) { 
	if ($akcja=='insert') { ?><script>alert('Pomyślnie dodano protokół do bazy');</script><?php }
	if ($akcja=='update') { ?><script>alert('Zmiany w protokole zostały zapisane');</script><?php }	
	
	?><script>
		window.location=href="utworz_protokol_szczecin.php?pnr=<?php echo "$pnr";?>&dzien=<?php echo "$dzien"; ?>&miesiac=<?php echo "$miesiac"; ?>&rok=<?php echo "$rok"; ?>&c_1=<?php echo "$c_1"; ?>&c_2=<?php echo "$c_2"; ?>&c_3=<?php echo "$c_3"; ?>&c_4=<?php echo "$c_4"; ?>&c_5=<?php echo "$c_5"; ?>&c_6=<?php echo "$c_6"; ?>&c_7=<?php echo "$c_7"; ?>&c_8=<?php echo "$c_8"; ?>&up=<?php echo urlencode($up); ?>&nazwa_urzadzenia=<?php echo urlencode($nazwa_urzadzenia); ?>&sn_urzadzenia=<?php echo urlencode($sn_urzadzenia); ?>&ni_urzadzenia=<?php echo urlencode($ni_urzadzenia); ?>&opis_uszkodzenia=<?php echo urlencode($opis_uszkodzenia); ?>&wykonane_czynnosci=<?php echo urlencode($wykonane_czynnosci); ?>&uwagi=<?php echo urlencode($uwagi); ?>&wersjap=<?php echo "$wersjap";?>&format_nr=<?php echo "$format_nr"; ?>&unr=<?php echo $unr;?>";
		</script>
	<?php } else { 
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script>
	<?php }
//pageheader("Protokół pomyślnie zapisano do bazy");
}

if ($czysty) {
	$clear 	= 1;
	$submit	= 'ok';
} else $clear = 0;

if (($wersjap==2) && ($submit)) {

	if ($clear==1) {

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

?>

  <table width="100%" border="0" cellspacing="0" cellpadding="0">	
  <tr>
	<td align="left" valign="top" class="style50" style="border-right-width:0px">
		<img src="img/logo2.gif">
	</td>
	<td align="center" class="style50" style="border-left-width:0px" width="200px">
	<div align="top">
	<span class="style38">
		PROTOKÓŁ WYKONANIA<br />USŁUGI/DOSTAWY
		<br />
		DO UMOWY NR:<?php if ($umowa_nr!='') echo "&nbsp;$umowa_nr"; ?>
	</span>
	</div>
	</td>
	<td class="style50" style="border-left-width:0px">
	<div align="top">
	<span class="style38">
	<?php if ($pnr!='') echo "&nbsp;NR: $pnr"; ?>
	</span>
	</div>
	</td>
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
<br />
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

?>

<a style="vertical-align:bottom" href="utworz_protokol_szczecin.php?pnr=<?php echo "$pnr";?>&dzien=<?php echo "$dzien"; ?>&miesiac=<?php echo "$miesiac"; ?>&rok=<?php echo "$rok"; ?>&c_1=<?php echo "$c_1"; ?>&c_2=<?php echo "$c_2"; ?>&c_3=<?php echo "$c_3"; ?>&c_4=<?php echo "$c_4"; ?>&c_5=<?php echo "$c_5"; ?>&c_6=<?php echo "$c_6"; ?>&c_7=<?php echo "$c_7"; ?>&c_8=<?php echo "$c_8"; ?>&up=<?php echo urlencode($up); ?>&nazwa_urzadzenia=<?php echo urlencode($nazwa_urzadzenia); ?>&sn_urzadzenia=<?php echo urlencode($sn_urzadzenia); ?>&ni_urzadzenia=<?php echo urlencode($ni_urzadzenia); ?>&opis_uszkodzenia=<?php echo urlencode($opis_uszkodzenia); ?>&wykonane_czynnosci=<?php echo urlencode($wykonane_czynnosci); ?>&uwagi=<?php echo urlencode($uwagi); ?>&format_nr=<?php echo "$format_nr"; ?>&imieinazwisko=<?php echo urlencode($imieinazwisko); ?>&wersjap=<?php echo "$wersjap";?>&unr=<?php echo $unr;?>"><img src="img/popraw.jpg" border="0"></a>

<?php 
}	
?>
<a href="#" style="vertical-align:bottom; cursor:hand" onClick="self.close();"><img src="img/anuluj_protokol.jpg" border="0"></a>
</div>
</div>

<?php 
}
// koniec protokołu w wersji 2(generuj protokół)
?>
</body>
</html>