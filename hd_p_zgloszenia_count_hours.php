<?php 

include_once('header_simple.php');
include_once('cfg_eserwis.php');
include_once('cfg_helpdesk.php');

$dd = Date('Y-m-d');
$result11a = mysql_query("SELECT SUM(zgl_szcz_czas_wykonywania) FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_widoczne=1) AND ((zgl_szcz_osoba_wykonujaca_krok='$currentuser') OR (zgl_szcz_dodatkowe_osoby_wykonujace_krok LIKE '%$currentuser%')) AND (SUBSTRING(zgl_szcz_czas_rozpoczecia_kroku,1,10)='$dd') AND (zgl_szcz_czas_wykonywania<>0)", $conn_hd) or die($k_b);

$newArr1 = mysql_fetch_array($result11a);
$RazemDzien1 = $newArr1[0];

$result11b = mysql_query("SELECT SUM(zgl_szcz_czas_trwania_wyjadu) FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_widoczne=1) AND ((zgl_szcz_osoba_wykonujaca_krok='$currentuser') OR (zgl_szcz_dodatkowe_osoby_wykonujace_krok LIKE '%$currentuser%')) AND (SUBSTRING(zgl_szcz_czas_rozpoczecia_kroku,1,10)='$dd')", $conn_hd) or die($k_b);

$newArr2 = mysql_fetch_array($result11b);
$RazemDzien2 = $newArr2[0];

$RazemDzien3 = $RazemDzien1 + $RazemDzien2;

$RazemDzien = minutes2hours($RazemDzien3,'short');

?><script>
document.getElementById("ilosc_godzin").innerHTML='<font color=blue>&nbsp;<?php echo $RazemDzien; ?></font>';
</script>