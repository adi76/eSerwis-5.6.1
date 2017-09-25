<?php 
include_once('header.php');
include_once('cfg_eserwis.php');
include_once('cfg_helpdesk.php');
 ?>
<?php
switch ($_GET[typ]) {
	case 'zn' :
				// ilosc nowych zgłoszeń w bazie
				$sql="SELECT * FROM $dbname_hd.hd_zgloszenie WHERE ";
				if ($es_m==1) { } else $sql=$sql."(hd_zgloszenie.belongs_to=$es_filia) and (hd_zgloszenie.zgl_widoczne=1) ";
				$sql.=" AND (zgl_status='1') ";
				$result = mysql_query($sql, $conn_hd) or die($k_b);
				$Nowe = mysql_num_rows($result);	
				echo "<font style='font-family: Tahoma; font-weight: bold; font-size: 32px; font-size-adjust: none; font-stretch: normal; color: #3C3C3C; text-align: center'>";
				echo $Nowe;
				echo "</font>";
				if ($Nowe==0) {
					?><script>document.getElementById("zn_tr").style.display='none'; document.getElementById("zn_count").value='0'; </script><?php
				} else {zn_count
					?><script>document.getElementById("zn_tr").style.display=''; document.getElementById("zn_count").value='<?php echo $Nowe; ?>';</script><?php
				}				
				break;
	case 'znnp' : 
				// ilosc nowych (nie przypisanych) zgłoszeń w bazie
				$sql="SELECT * FROM $dbname_hd.hd_zgloszenie WHERE ";
				if ($es_m==1) { } else $sql=$sql."(belongs_to=$es_filia) and (zgl_widoczne=1) ";
				$sql.=" AND (zgl_status='1') ";
				$sql.="AND (zgl_osoba_przypisana='') ";
				$result = mysql_query($sql, $conn_hd) or die($k_b);
				$NoweNiePrzypisane = mysql_num_rows($result);
				echo "<font style='font-family: Tahoma; font-weight: bold; font-size: 32px; font-size-adjust: none; font-stretch: normal; color: #3C3C3C; text-align: center'>";
				echo $NoweNiePrzypisane;
				echo "</font>";
				if ($NoweNiePrzypisane==0) {
					?><script>document.getElementById("znnp_tr").style.display='none';document.getElementById("znnp_count").value='0';</script><?php
				} else {
					?><script>document.getElementById("znnp_tr").style.display=''; document.getElementById("znnp_count").value='<?php echo $NoweNiePrzypisane; ?>';</script><?php
				}						
				break;
	case 'znpdm'	:
				// ilosc przypisanych do mnie zgłoszeń w bazie
				$sql="SELECT * FROM $dbname_hd.hd_zgloszenie WHERE ";
				if ($es_m==1) { } else $sql=$sql."(belongs_to=$es_filia) and (zgl_widoczne=1) ";
				$sql.=" AND (zgl_status='2') ";
				$sql.="AND (zgl_osoba_przypisana='$currentuser') ";		
				$result = mysql_query($sql, $conn_hd) or die($k_b);
				$PrzypisaneDoMnie= mysql_num_rows($result);	
				echo "<font style='font-family: Tahoma; font-weight: bold; font-size: 32px; font-size-adjust: none; font-stretch: normal; color: #3C3C3C; text-align: center'>";
				echo $PrzypisaneDoMnie;
				echo "</font>";
				if ($PrzypisaneDoMnie==0) {
					?><script>document.getElementById("znpdm_tr").style.display='none';document.getElementById("znpdm_count").value='0';</script><?php
				} else {
					?><script>document.getElementById("znpdm_tr").style.display='';document.getElementById("znpdm_count").value='<?php echo $PrzypisaneDoMnie; ?>';</script><?php
				}				
				break;
	case 'zr'	:
				// ilosc zgłoszeń rozpoczętych (w trakcie wykonywania)
				$sql="SELECT * FROM $dbname_hd.hd_zgloszenie WHERE ";
				if ($es_m==1) { } else $sql=$sql."(hd_zgloszenie.belongs_to=$es_filia) and (hd_zgloszenie.zgl_widoczne=1) ";
				$sql.=" AND (zgl_status='3') ";
				$result = mysql_query($sql, $conn_hd) or die($k_b);
				$Rozpoczete = mysql_num_rows($result);
				echo "<font style='font-family: Tahoma; font-weight: bold; font-size: 32px; font-size-adjust: none; font-stretch: normal; color: #3C3C3C; text-align: center'>";
				echo $Rozpoczete;
				echo "</font>";
				if ($Rozpoczete==0) {
					?><script>document.getElementById("zr_tr").style.display='none';document.getElementById("zr_count").value='0';</script><?php
				} else {
					?><script>document.getElementById("zr_tr").style.display='';document.getElementById("zr_count").value='<?php echo $Rozpoczete; ?>';</script><?php
				}				
				break;
	case 'az' 	:
				// ilosc Awarii
				$sql="SELECT * FROM $dbname_hd.hd_zgloszenie WHERE ";
				if ($es_m==1) { } else $sql=$sql."(hd_zgloszenie.belongs_to=$es_filia) and (hd_zgloszenie.zgl_widoczne=1) ";
				$sql.=" AND (zgl_kategoria='2') AND (zgl_priorytet=2) ";
				$sql.=" AND (zgl_status='1') ";
				$result = mysql_query($sql, $conn_hd) or die($k_b);
				$IloscAwarii = mysql_num_rows($result);
				echo "<font style='font-family: Tahoma; font-weight: bold; font-size: 32px; font-size-adjust: none; font-stretch: normal; color: #3C3C3C; text-align: center'>";
				echo $IloscAwarii;
				echo "</font>";
				if ($IloscAwarii==0) {
					?><script>document.getElementById("az_tr").style.display='none';document.getElementById("az_count").value='0';</script><?php
				} else {
					?><script>document.getElementById("az_tr").style.display='';document.getElementById("az_count").value='<?php echo $IloscAwarii; ?>';</script><?php
				}
				break;
				
	case 'ak' 	:
				// ilosc Awarii
				$sql="SELECT * FROM $dbname_hd.hd_zgloszenie WHERE ";
				if ($es_m==1) { } else $sql=$sql."(hd_zgloszenie.belongs_to=$es_filia) and (hd_zgloszenie.zgl_widoczne=1) ";
				$sql.=" AND (zgl_kategoria='2') AND (zgl_priorytet='4') ";
				$sql.=" AND (zgl_status='1') ";
				$result = mysql_query($sql, $conn_hd) or die($k_b);
				$IloscAwariiKrytycznych = mysql_num_rows($result);
				
				echo "<font style='font-family: Tahoma; font-weight: bold; font-size: 32px; font-size-adjust: none; font-stretch: normal; color: #3C3C3C; text-align: center'>";
				echo $IloscAwariiKrytycznych;
				echo "</font>";
				
				if ($IloscAwariiKrytycznych==0) {
					?><script>document.getElementById("ak_tr").style.display='none';document.getElementById("ak_count").value='0';</script><?php
				} else {
					?><script>document.getElementById("ak_tr").style.display='';document.getElementById("ak_count").value='<?php echo $IloscAwariiKrytycznych; ?>';</script><?php
				}
				break;	

}

?>
<script>
var $ZgloszeniaRazem = 	document.getElementById('zn_count').value+
									document.getElementById('znnp_count').value+
									document.getElementById('znpdm_count').value+
									document.getElementById('zr_count').value+
									document.getElementById('az_count').value+
									document.getElementById('ak_count').value+0;
//alert($ZgloszeniaRazem);

if ($ZgloszeniaRazem==0) {
	document.getElementById("ZbiorczeHD").style.display='none';
	document.getElementById("ZbiorczeTable").style.display='none';
} else {
	document.getElementById("ZbiorczeTable").style.display='';
	document.getElementById("ZbiorczeHD").style.display='';
	
}
</script>
