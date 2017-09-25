<?php 
include_once('header.php');
include_once('cfg_eserwis.php');
include_once('cfg_helpdesk.php');
?>
<?php

if ($submit) {

$sql="TRUNCATE TABLE helpdesk.hd_zgloszenie";					$wynik = mysql_query($sql, $conn_hd);
$sql="TRUNCATE TABLE helpdesk.hd_zgloszenie_oferty";			$wynik = mysql_query($sql, $conn_hd);
$sql="TRUNCATE TABLE helpdesk.hd_zgloszenie_szcz";				$wynik = mysql_query($sql, $conn_hd);
$sql="TRUNCATE TABLE helpdesk.hd_zgloszenie_wyjazd";			$wynik = mysql_query($sql, $conn_hd);
$sql="TRUNCATE TABLE helpdesk.hd_zgloszenie_zamowienia";		$wynik = mysql_query($sql, $conn_hd);
$sql="TRUNCATE TABLE helpdesk.hd_zgloszenie_historia_zmian";	$wynik = mysql_query($sql, $conn_hd);

infoheader("Wyczyszczono tabele ze zgłoszeniami");

echo "<br /><p align=center><input class=buttons type=button value=Zamknij onClick=\"self.close();\" ></p>";
} else {

echo "<br />&nbsp;<form action=test.php method=POST><center><input class=buttons type=submit name=submit value='Wyczyść wszystkie  tabele związane z bazą Helpdesk'></center></form>";

}

?>