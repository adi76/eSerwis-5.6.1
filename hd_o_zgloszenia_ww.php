<?php 
include_once('header.php');
include_once('cfg_eserwis.php');
include_once('cfg_helpdesk.php');

echo "<div id=TrwaLadowanie style='color:white; width:auto; font-weight:normal; text-align:center; font-size:13px; border: 2px solid grey; background-color:black;padding:20px'>";
echo "Trwa aktualizowanie danych w bazie...<input type=image class=border0 src=img/loader7.gif>";
echo "</div>";
ob_flush();
flush();
		
if ($_REQUEST[set]==0) {
	
	$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_wymagany_wyjazd=0 WHERE ((zgl_widoczne=1) and (zgl_nr='$_REQUEST[zgl_nr]')) LIMIT 1";
	if (mysql_query($sql, $conn_hd)) {
		//echo "<font color=blue><b>NIE</b>";
		$_SESSION['refresh_o_zgloszenia_'.$_REQUEST[zgl_nr].''] = 1;
	}

} else {
	$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_wymagany_wyjazd=1,zgl_wymagany_wyjazd_data_ustawienia='$_REQUEST[ww_data]', zgl_wymagany_wyjazd_osoba_wlaczajaca='$_REQUEST[ww_osoba]' WHERE ((zgl_widoczne=1) and (zgl_nr='$_REQUEST[zgl_nr]')) LIMIT 1";
	if (mysql_query($sql, $conn_hd)) {
	//	echo "<font color=blue><b>TAK</b> | Ustawione w dniu <b>".$_REQUEST[ww_data]."</b> przez <b>".$_REQUEST[ww_osoba]."</b></font>";
		$_SESSION['refresh_o_zgloszenia_'.$_REQUEST[zgl_nr].''] = 1;
	}
}

?>
<script>
self.close();
if (opener) opener.location.reload(true);
</script>