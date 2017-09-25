<?php

include_once('header.php');
include_once('cfg_eserwis.php');
include_once('cfg_helpdesk.php');

echo "<body>";

if (($_GET[ran]=='notclosed') && ($_GET[who]=='0')) {
	list($AwarieKrytyczneCount)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='6') and (zgl_status<>9) and (zgl_widoczne=1)", $conn_hd)); 
	list($AwarieZwykleCount)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='2') and (zgl_status<>9) and (zgl_widoczne=1)", $conn_hd)); 
//	list($PraceWRamachUmowyN)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='3') and (zgl_priorytet='1') and (zgl_status<>9)", $conn_hd)); 
	list($PraceWRamachUmowyS)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='3') and (zgl_status<>9) and (zgl_widoczne=1)", $conn_hd)); 
//	list($PraceWRamachUmowyW)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='3') and (zgl_priorytet='3') and (zgl_status<>9)", $conn_hd)); 
	list($Konsultacje)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='1') and (zgl_status<>9) and (zgl_widoczne=1)", $conn_hd)); 
	list($PracePozaUmowa)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='4') and (zgl_status<>9) and (zgl_widoczne=1)", $conn_hd)); 
} 

if (($_GET[ran]=='all') && ($_GET[who]=='0')) {
	list($AwarieKrytyczneCount)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='6') and (zgl_widoczne=1)", $conn_hd)); 
	list($AwarieZwykleCount)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='2') and (zgl_widoczne=1)", $conn_hd)); 
//	list($PraceWRamachUmowyN)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='3') and (zgl_priorytet='1')", $conn_hd)); 
	list($PraceWRamachUmowyS)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='3') and (zgl_widoczne=1)", $conn_hd)); 
//	list($PraceWRamachUmowyW)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='3') and (zgl_priorytet='3')", $conn_hd)); 
	list($Konsultacje)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='1') and (zgl_widoczne=1)", $conn_hd)); 
	list($PracePozaUmowa)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='4') and (zgl_widoczne=1)", $conn_hd)); 
}

if (($_GET[ran]=='notclosed') && ($_GET[who]!='0')) {
	list($AwarieKrytyczneCount)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='6') and (zgl_osoba_przypisana='$_GET[who]') and (zgl_status<>9) and (zgl_widoczne=1)", $conn_hd)); 
	list($AwarieZwykleCount)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='2') and (zgl_osoba_przypisana='$_GET[who]') and (zgl_status<>9) and (zgl_widoczne=1)", $conn_hd)); 
//	list($PraceWRamachUmowyN)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='3') and (zgl_priorytet='1') and (zgl_osoba_przypisana='$_GET[who]') and (zgl_status<>9)", $conn_hd)); 
	list($PraceWRamachUmowyS)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='3') and (zgl_osoba_przypisana='$_GET[who]') and (zgl_status<>9) and (zgl_widoczne=1)", $conn_hd)); 
//	list($PraceWRamachUmowyW)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='3') and (zgl_priorytet='3') and (zgl_osoba_przypisana='$_GET[who]') and (zgl_status<>9)", $conn_hd)); 
	list($Konsultacje)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='1') and (zgl_osoba_przypisana='$_GET[who]') and (zgl_status<>9) and (zgl_widoczne=1)", $conn_hd)); 
	list($PracePozaUmowa)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='4') and (zgl_osoba_przypisana='$_GET[who]') and (zgl_status<>9) and (zgl_widoczne=1)", $conn_hd)); 
}

if (($_GET[ran]=='all') && ($_GET[who]!='0')) {
	list($AwarieKrytyczneCount)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='6') and (zgl_osoba_przypisana='$_GET[who]') and (zgl_widoczne=1)", $conn_hd)); 
	list($AwarieZwykleCount)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='2') and (zgl_osoba_przypisana='$_GET[who]') and (zgl_widoczne=1)", $conn_hd)); 
//	list($PraceWRamachUmowyN)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='3') and (zgl_priorytet='1') and (zgl_osoba_przypisana='$_GET[who]')", $conn_hd)); 
	list($PraceWRamachUmowyS)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='3') and (zgl_osoba_przypisana='$_GET[who]') and (zgl_widoczne=1)", $conn_hd)); 
//	list($PraceWRamachUmowyW)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='3') and (zgl_priorytet='3') and (zgl_osoba_przypisana='$_GET[who]')", $conn_hd)); 
	list($Konsultacje)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='1') and (zgl_osoba_przypisana='$_GET[who]') and (zgl_widoczne=1)", $conn_hd)); 
	list($PracePozaUmowa)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_kategoria='4') and (zgl_osoba_przypisana='$_GET[who]') and (zgl_widoczne=1)", $conn_hd)); 
}

$suma = $AwarieKrytyczneCount + $AwarieZwykleCount + $PraceWRamachUmowyS + $Konsultacje + $PracePozaUmowa;

$maxvalue1 = max($AwarieKrytyczneCount,$AwarieZwykleCount,$PraceWRamachUmowyS,$Konsultacje,$PracePozaUmowa);
$s = ($maxvalue1 * 0.01)*10;

$maxvalue = $maxvalue1+$s;

?>
<script>
//self.location.href="hd_generate_chart.php?p1=<?php echo "$AwarieKrytyczneCount"; ?>&p2=<?php echo "$AwarieZwykleCount"; ?>&p3=<?php echo "$PraceWRamachUmowyN"; ?>&p4=<?php echo "$PraceWRamachUmowyS"; ?>&p5=<?php echo "$PraceWRamachUmowyW"; ?>&p6=<?php echo "$Konsultacje"; ?>&p7=<?php echo "$PracePozaUmowa"; ?>&filia=<?php echo "$es_filia"; ?>&razem=<?php echo "$suma"; ?>&ran=<?php echo "$_GET[ran]";?>&maxv=<?php echo $maxvalue; ?>&who=<?php echo "$_GET[who]"; ?>&whoid=<?php echo "$es_nr"; ?>";
self.location.href="hd_generate_chart.php?p1=<?php echo "$AwarieKrytyczneCount"; ?>&p2=<?php echo "$AwarieZwykleCount"; ?>&p3=<?php echo "$PraceWRamachUmowyS"; ?>&p4=<?php echo "$Konsultacje"; ?>&p5=<?php echo "$PracePozaUmowa"; ?>&filia=<?php echo "$es_filia"; ?>&razem=<?php echo "$suma"; ?>&ran=<?php echo "$_GET[ran]";?>&maxv=<?php echo $maxvalue; ?>&who=<?php echo "$_GET[who]"; ?>&whoid=<?php echo "$es_nr"; ?>";
</script>
