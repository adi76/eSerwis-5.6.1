<?php //include_once('header.php');
include_once('cfg_helpdesk.php');

// ilosc nowych zgłoszeń w bazie
$sql="SELECT * FROM $dbname_hd.hd_zgloszenie WHERE ";
if ($es_m==1) { } else $sql=$sql."(hd_zgloszenie.belongs_to=$es_filia) and (hd_zgloszenie.zgl_widoczne=1) ";
$sql.=" AND (zgl_status='1') ";
$result = mysql_query($sql, $conn_hd) or die($k_b);
$Nowe = mysql_num_rows($result);

// ilosc nowych (nie przypisanych) zgłoszeń w bazie
$sql="SELECT * FROM $dbname_hd.hd_zgloszenie WHERE ";
if ($es_m==1) { } else $sql=$sql."(belongs_to=$es_filia) and (zgl_widoczne=1) ";
$sql.=" AND (zgl_status='1') ";
$sql.="AND (zgl_osoba_przypisana='') ";
$result = mysql_query($sql, $conn_hd) or die($k_b);
$NoweNiePrzypisane = mysql_num_rows($result);

// ilosc przypisanych do mnie zgłoszeń w bazie
$sql="SELECT * FROM $dbname_hd.hd_zgloszenie WHERE ";
if ($es_m==1) { } else $sql=$sql."(belongs_to=$es_filia) and (zgl_widoczne=1) ";
$sql.=" AND (zgl_status='2') ";
$sql.="AND (zgl_osoba_przypisana='$currentuser') ";
$result = mysql_query($sql, $conn_hd) or die($k_b);
$PrzypisaneDoMnie= mysql_num_rows($result);

// ilosc zgłoszeń rozpoczętych (w trakcie wykonywania)
$sql="SELECT * FROM $dbname_hd.hd_zgloszenie WHERE ";
if ($es_m==1) { } else $sql=$sql."(hd_zgloszenie.belongs_to=$es_filia) and (hd_zgloszenie.zgl_widoczne=1) ";
$sql.=" AND (zgl_status='3') ";
$result = mysql_query($sql, $conn_hd) or die($k_b);
$Rozpoczete = mysql_num_rows($result);

// ilosc Awarii
$sql="SELECT * FROM $dbname_hd.hd_zgloszenie WHERE ";
if ($es_m==1) { } else $sql=$sql."(hd_zgloszenie.belongs_to=$es_filia) and (hd_zgloszenie.zgl_widoczne=1) ";
$sql.=" AND (zgl_kategoria='2') AND (zgl_priorytet=2) ";
$result = mysql_query($sql, $conn_hd) or die($k_b);
$IloscAwarii = mysql_num_rows($result);

// ilosc Awarii Krytycznych
$sql="SELECT * FROM $dbname_hd.hd_zgloszenie WHERE ";
if ($es_m==1) { } else $sql=$sql."(hd_zgloszenie.belongs_to=$es_filia) and (hd_zgloszenie.zgl_widoczne=1) ";
$sql.=" AND (zgl_kategoria='2') AND (zgl_priorytet=4) ";
$result = mysql_query($sql, $conn_hd) or die($k_b);
$IloscAwariiKrytycznych = mysql_num_rows($result);

//infoheader("Informacje zbiorcze z bazy zgłoszeń (Helpdesk)",0);
echo "<span id=ZbiorczeTable>";
br();
echo "<h5 id=ZbiorczeHD>Informacje zbiorcze z bazy zgłoszeń (Helpdesk)</h5>";
echo "<table style='background-color:transparent; border:0px;' border=0 align=center>";
tbl_empty_row(1);
tr_();
echo "<td id=ak_tr style=display:none>";
echo "<a class=normalfont href=hd_p_zgloszenia.php?showall=0&p2=2&p4=4&p5=1>";
echo "<div id=ak_div style='margin: 0pt auto; width: 180px; background-color: #F73B3B; font-family: Tahoma; font-weight: normal; font-size: 1.5em; font-size-adjust: none; font-stretch: normal; color: #3C3C3C; text-align: center' class=rounded6>";
echo "<span id=ak><font size=6><b>$IloscAwariiKrytycznych</b></font></span>";
echo "<br />nowe awarie<br />krytyczne";
echo "</div></a>";
echo "<input type=hidden id=ak_count>";
?><script type="text/javascript">Rounded('rounded6', 6, 6);</script><?php
_td();
//td_("200;c;");
echo "<td id=az_tr style=display:none>";
echo "<a class=normalfont href=hd_p_zgloszenia.php?showall=0&p2=2&p4=2&p5=1>";
echo "<div id=az_div style='margin: 0pt auto; width: 180px; background-color: #FF7F2A; font-family: Tahoma; font-weight: normal; font-size: 1.5em; font-size-adjust: none; font-stretch: normal; color: #3C3C3C; text-align: center' class=rounded5>";
//echo "<font size=6><b>$IloscAwarii</b></font>";
echo "<span id=az><font size=6><b>$IloscAwarii</b></font></span>";
echo "<br />nowe awarie<br />zwykłe";
echo "</div></a>";
echo "<input type=hidden id=az_count>";
?><script type="text/javascript">Rounded('rounded5', 6, 6);</script><?php
_td();
echo "<td id=zn_tr style=display:none>";
echo "<a class=normalfont href=hd_p_zgloszenia.php?showall=0&p5=1>";
echo "<div id=zn_div style='margin: 0pt auto; width: 180px; background-color: #AAFF7F; font-family: Tahoma; font-weight: normal; font-size: 1.5em; font-size-adjust: none; font-stretch: normal; color: #3C3C3C; text-align: center' class=rounded1>";
//echo "<font size=6><b>$Nowe</b></font>";
echo "<span id=zn><font size=6><b>$Nowe</b></font></span>";
echo "<br />wszystkie nowe<br />zgłoszenia";
echo "</div></a>";
echo "<input type=hidden id=zn_count>";
?><script type="text/javascript">Rounded('rounded1', 6, 6);</script><?php
_td();
echo "<td id=znnp_tr style=display:none>";
echo "<a class=normalfont href=hd_p_zgloszenia.php?showall=0&p5=1&p6=9>";
echo "<div id=znnp_div style='margin: 0pt auto; width: 180px; background-color: #8383D8; font-family: Tahoma; font-weight: normal; font-size: 1.5em; font-size-adjust: none; font-stretch: normal; color: #3C3C3C; text-align: center' class=rounded2>";
//echo "<font size=6><b>$NoweNiePrzypisane</b></font>"; 
echo "<span id=znnp><font size=6><b>$NoweNiePrzypisane</b></font></span>";
echo "<br />nowe zgłoszenia<br />nieprzypisane";
echo "</div></a>";
echo "<input type=hidden id=znnp_count>";
?><script type="text/javascript">Rounded('rounded2', 6, 6);</script><?php
_td();
echo "<td id=znpdm_tr style=display:>";
echo "<a class=normalfont href=hd_p_zgloszenia.php?showall=0&p6=".urlencode($currentuser)."&p5=2 />";
echo "<div id=znpdm_div style='margin: 0pt auto; width: 180px; background-color: #A3CEA3; font-family: Tahoma; font-weight: normal; font-size: 1.5em; font-size-adjust: none; font-stretch: normal; color: #3C3C3C; text-align: center' class=rounded3>";
echo "<span id=znpdm><font size=6><b>$PrzypisaneDoMnie</b></font></span>";
echo "<br />zgłoszenie<br />przypisane do mnie";
echo "</div></a>";
echo "<input type=hidden id=znpdm_count>";
?><script type="text/javascript">Rounded('rounded3', 6, 6);</script><?php
_td();
echo "<td id=zr_tr style=display:none>";
echo "<a class=normalfont href=hd_p_zgloszenia.php?showall=0&p5=3>";
echo "<div id=zr_div style='margin: 0pt auto; width: 180px; background-color: #CEA3CE; font-family: Tahoma; font-weight: normal; font-size: 1.5em; font-size-adjust: none; font-stretch: normal; color: #3C3C3C; text-align: center' class=rounded4>";
//echo "<font size=6><b>$Rozpoczete</b></font>"; 
echo "<span id=zr><font size=6><b>$Rozpoczete</b></font></span>";
echo "<br />rozpoczęte<br />&nbsp;";
echo "</div></a>";
echo "<input type=hidden id=zr_count>";
?><script type="text/javascript">Rounded('rounded4', 6, 6);</script><?php
_td();
_tr();
tbl_empty_row(1);
endtable();
startbuttonsarea("right");
echo "<input type=button class=buttons value='Zestawienie szczegółowe' onClick=\"newWindow_r(800,600,'p_raport_hd_szczegolowy.php'); \" />&nbsp;&nbsp;";
endbuttonsarea();

echo "</span>";
?>

