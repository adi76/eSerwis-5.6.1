<?php 
include_once('header_simple.php');
include_once('cfg_eserwis.php');
include_once('cfg_helpdesk.php');

$dd = Date('Y-m-d');

	$sql33= "SELECT COUNT(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ";
	
	$sql33=$sql33."(hd_zgloszenie.belongs_to=$es_filia) and (hd_zgloszenie.zgl_widoczne=1) ";
	// wg dnia
	if (($_REQUEST[p1]!='X') && ($_REQUEST[p1]!='0') && ($_REQUEST[p1]!='')) $sql33.="AND (zgl_data='$_REQUEST[p1]') ";
	// wg kategorii
	if (($_REQUEST[p2]!='X') && ($_REQUEST[p2]!='0') && ($_REQUEST[p2]!='')) $sql33.="AND (zgl_kategoria='$_REQUEST[p2]') ";
	// wg podkategorii
	if (($_REQUEST[p3]!='X') && ($_REQUEST[p3]!='0') && ($_REQUEST[p3]!='')) $sql33.="AND (zgl_podkategoria='$_REQUEST[p3]') ";
	// wg priorytetu
	if (($_REQUEST[p4]!='X') && ($_REQUEST[p4]!='0') && ($_REQUEST[p4]!='')) $sql33.="AND (zgl_priorytet='$_REQUEST[p4]') ";
	// wg statusu
	if (($_REQUEST[p5]!='X') && ($_REQUEST[p5]!='0') && ($_REQUEST[p5]!='') && ($_REQUEST[p5]!='BZ')) $sql33.="AND (zgl_status='$_REQUEST[p5]') ";
	if ($_REQUEST[p5]=='BZ') $sql33.="AND (zgl_status<>'9') ";
	// wg przypisania
	if (($_REQUEST[p6]!='X') && ($_REQUEST[p6]!='0') && ($_REQUEST[p6]!='')) {
		$p_6='';
		if ($_REQUEST[p6]!='9') $p_6=$_REQUEST[p6];
		$sql33.="AND (zgl_osoba_przypisana='$p_6') ";
	}
	if ($_REQUEST[additional]=='pzw') $sql33.=" AND (hd_zgloszenie.zgl_razem_km>0) ";
	
	if (($_REQUEST[p8]!='X') && ($_REQUEST[p8]!='')) {
		if ($_REQUEST[p8]=='1') $sql33.=" AND (zgl_sprawdzone_osoba<>'') ";
		if ($_REQUEST[p8]=='0') $sql33.=" AND (zgl_sprawdzone_osoba='') ";
	}

$result33 = mysql_query($sql33, $conn_hd) or die($k_b);
$newArr33 = mysql_fetch_array($result33);
$count_filtr = $newArr33[0];

if ($count_filtr==0) {
?><script>
document.getElementById("KomunikatOIlosciZgloszen").innerHTML='<br /><h2>Brak zgłoszeń spełniających wybrane kryteria</h2>';
document.getElementById("KomunikatOIlosciZgloszen").style.display='';
//document.getElementById("ZastosujFiltr").style.display='none';
document.getElementById("ZastosujFiltr").value = ' Pokaż (<?php echo $count_filtr; ?>) ';
</script>
<?php } else { ?>
<script>
document.getElementById("KomunikatOIlosciZgloszen").innerHTML='';
document.getElementById("KomunikatOIlosciZgloszen").style.display='none';
//document.getElementById("ZastosujFiltr").style.display='';
document.getElementById("ZastosujFiltr").value = ' Pokaż (<?php echo $count_filtr; ?>) ';
</script>
<?php } ?>