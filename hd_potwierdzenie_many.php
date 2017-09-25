<?php
include_once('header_simple.php'); 
include_once('cfg_helpdesk.php');
include_once('cfg_eserwis.php');
include_once('cfg_db_helpdesk.php');
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
.style50x{margin:0px;background-position:center;font-family:Arial,Helvetica,sans-serif;border-top-width:1px;border-right-width:1px;border-bottom-width:1px;border-left-width:1px;border-top-style:solid;border-right-style:solid;border-bottom-style:solid;border-left-style:solid;border-top-color:#000000;border-right-color:#000000;border-bottom-color:#000000;border-left-color:#000000;padding-top:3px;padding-right:3px;padding-bottom:3px;padding-left:3px;font-size:14px;font-weight:bold;}

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
	if (confirm("Czy napewno chcesz zamknąć wygenerowany protokół ?\n\n!!! Pamiętaj o wydrukowaniu protokołu !!!\n")) { 
		window.close();
		return true;
	} else {
		return false; 
	}
}
// end hide -->
</script>

</head>

<body>

<script>ShowWaitingMessage();</script><?php ob_flush(); flush();?>

<div class="hideme" align="center">
<a href="#" style="vertical-align:bottom; cursor:hand" onClick="window.print();"><img src="img/drukuj_protokol.jpg" border="0"></a>
<a href="#" style="cursor:hand;vertical-align:bottom" onClick="self.close();"><img src="img//zamknij_protokol.jpg" border="0"></a>
<hr />
</div>

<?php
$licz = 0;
if ($_REQUEST[nr]!="") { 
	$sql="SELECT * FROM $dbname_hd.hd_zgloszenie WHERE (zgl_nr IN (".$_REQUEST[nr]."))";
	
	$result = mysql_query($sql, $conn_hd) or die($k_b);
	
	while ($newArray = mysql_fetch_array($result)) {
		
		$temp_id  			= $newArray['zgl_id'];
		$temp_nr			= $newArray['zgl_nr'];
		$temp_poczta_nr		= $newArray['zgl_poczta_nr'];
		$temp_data			= $newArray['zgl_data'];
		$temp_godzina		= $newArray['zgl_godzina'];
		$temp_komorka		= $newArray['zgl_komorka'];
		$temp_osoba			= $newArray['zgl_osoba'];
		$temp_telefon		= $newArray['zgl_telefon'];
		$temp_temat			= $newArray['zgl_temat'];	
		$temp_tresc			= $newArray['zgl_tresc'];	
		
		$temp_kategoria		= $newArray['zgl_kategoria'];
		$temp_podkategoria	= $newArray['zgl_podkategoria'];
		$temp_priorytet		= $newArray['zgl_priorytet'];
		$temp_status 		= $newArray['zgl_status'];

		$temp_or			= $newArray['zgl_osoba_rejestrujaca'];
		$temp_opz			= $newArray['zgl_osoba_potwierdzajaca_zamkniecie_zgloszenia'];
		
		$put_up=$temp_komorka;	

		?>

		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td align="left" valign="top" bordercolor="#FFFFFF" bgcolor="#FFFFFF">
		<span align="right">&nbsp;<img src="img/logo_small.gif"></span>
		</td>
		<td align="center" style='padding-bottom:10px' bordercolor="#FFFFFF" bgcolor="#FFFFFF">
		<span class="style34">Raport ze zgłoszenia nr:<?php if ($_REQUEST[nr]!='') { echo "&nbsp;$temp_nr"; } else { echo "<font style='font-weight:normal'>&nbsp;......................</font>"; } ?>
		</span>
		<span class="style88"><br /><br />&nbsp;Data zgłoszenia: <?php if ($temp_data!='') { echo "".$temp_data.""; } else { echo "&nbsp;................................"; }?></span>
		</td>
		<td align="left" bordercolor="#FFFFFF" bgcolor="#FFFFFF">&nbsp;</td>
		<td align="left" bordercolor="#FFFFFF" bgcolor="#FFFFFF">&nbsp;</td>
		<td colspan="2" align="right" valign="top" bordercolor="#ffffff" bgcolor="#ffffff">
		</td>
		</tr>
		<tr height="5px">
		<td colspan="6">
		</td>
		</tr>
		
		<tr height="5px">
		<td colspan="5"></td>
		</tr>
		
		</table>
		
		  <table width="100%" border="0" cellspacing="0" cellpadding="0">	
			<tr>
			  <td height="20" colspan="5" align="left" bgcolor="#ffffff" class="style50x"><span class="style37">POTWIERDZENIE POBYTU W JEDNOSTCE POCZTOWEJ/AGENCJI</span></td>
			</tr>	
			<tr>
			<?php 
				if ($put_up=='') $put_up = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			?>
			  <td height="30" width="200" align="left" valign="middle" class="style44"><span class="style8x">&nbsp;Nazwa komórki </span></td>
			  <td colspan="4" align="left" valign="middle" class="style45"><span class="style34x">
			  <?php echo "<b>".nl2br(cleanup($put_up))."</b>"; ?></span></td>
			</tr>
			<tr>
			  <td height="<?php if ($temp_tresc=='') { echo "30"; } else { echo "auto"; } ?>" align="left" valign="middle" class="style44"><span class="style8x">&nbsp;Treść zgłoszenia </span></td>
			  <td colspan="4" align="left" valign="top" class="style45"><span class="style34x">
			  <?php echo "<b>".nl2br(cleanup($temp_tresc))."</b>"; ?></span></td>
			</tr>
			<tr>
			  <td height="300" align="left" valign="top" class="style44"><span class="style8x">&nbsp;Wykonane czynności </span></td>
			  
			<?php
			if ($temp_nr!="") { 

				$sql4="SELECT zgl_szcz_nr_kroku,zgl_szcz_czas_rozpoczecia_kroku,zgl_szcz_status,zgl_szcz_wykonane_czynnosci,zgl_szcz_widoczne FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$temp_nr)"; // and (belongs_to=$es_filia)";	
				$result4 = mysql_query($sql4, $conn_hd) or die($k_b);
				$put_wykonane_czynnosci='';
				
				while ($newArray4 = mysql_fetch_array($result4)) {

					$temp_nr			= $newArray4['zgl_szcz_nr_kroku'];
					$temp_czasRozp		= $newArray4['zgl_szcz_czas_rozpoczecia_kroku'];
					$temp_status		= $newArray4['zgl_szcz_status'];
					$temp_czynnosci		= $newArray4['zgl_szcz_wykonane_czynnosci'];
					$temp_widoczne		= $newArray4['zgl_szcz_widoczne'];
					
					if (($temp_nr>1) && ($temp_widoczne==1)) $put_wykonane_czynnosci .= $temp_czasRozp.": ".$temp_czynnosci."<br />";
				}
				
				if ($put_wykonane_czynnosci!='') $put_wykonane_czynnosci .= "<hr />";
			} else {
				$put_wykonane_czynnosci = "";
			}
		?>

			  <td colspan="4" align="left" valign="top" class="style45"><span class="style34x"><?php echo "<b>".nl2br(cleanup($put_wykonane_czynnosci))."</b>"; ?></span></td>
			</tr>
			<tr>
			  <td height="50" align="left" valign="top" class="style44"><span class="style8x">&nbsp;Data, godzina<br />&nbsp;wykonania czynności<br />&nbsp;w lokalizacji klienta</span></td>
			  <td colspan="4" align="left" valign="top" class="style45"><span class="style34x"></span></td>
			</tr>
			<tr>
			  <td height="150" align="left" valign="top" class="style44"><span class="style8x">&nbsp;Uwagi</span></td>
			  <td colspan="4" align="left" valign="top" class="style45"><span class="style34x"></span></td>
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
			  <td class="style47b"><div align="center" class="style36">Data i podpis osoby odbierającej prace </div></td>
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
		
		<?php	
		$licz++;
		//echo "$licz";
		if ($licz>0) echo "<p style='page-break-after:always;'></p>";

	}
}

?>
<div class="hideme" align="center">
<hr />
<a href="#" style="vertical-align:bottom; cursor:hand" onClick="window.print();"><img src="img/drukuj_protokol.jpg" border="0"></a>
<?php
	if (($readonly!=1) || ($es_prawa=='9')) {
		if ($clear) {
			$dzien		= Date('d');
			$miesiac	= Date('m');
			$rok		= Date('Y');
		}
	}	
?>
<a href="#" style="cursor:hand;vertical-align:bottom" onClick="self.close();"><img src="img//zamknij_protokol.jpg" border="0"></a>
<br />
</div>

<script>HideWaitingMessage();</script>	

<script>
window.print();
</script>
</body>
</html>