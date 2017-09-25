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
	$sql66 = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_sprawdzone_data='', zgl_sprawdzone_osoba='' WHERE (zgl_nr IN (".$_REQUEST[nr]."))";
//	$result66 = mysql_query($sql66, $conn_hd) or die($k_b);
//	$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_wymagany_wyjazd=0 WHERE ((zgl_widoczne=1) and (belongs_to='$es_filia') and (zgl_nr='$_REQUEST[zgl_nr]')) LIMIT 1";
	if (mysql_query($sql66, $conn_hd)) {
	
		$ssql = "SELECT zgl_id FROM $dbname_hd.hd_zgloszenie WHERE (zgl_nr IN (".$_REQUEST[nr]."))";
		$result_dc = mysql_query($ssql, $conn_hd) or die($k_b);
		
		$dddd = Date('Y-m-d H:i:s');
		$lista_zmian='<u>Anulowanie potwierdzenia sprawdzenia zgłoszenia przez:</u> <b>'.$currentuser.', '.substr($dddd,0,16).'</b><br />';
		
		while ($newArray_dc = mysql_fetch_array($result_dc)) {
			$temp_zgl_id	= $newArray_dc['zgl_id'];	
			$sql_insert = "INSERT INTO $dbname_hd.hd_zgloszenie_historia_zmian values ('', '$temp_zgl_id','$dddd','$currentuser','$lista_zmian',1,'',$es_filia)";
			$wynik = mysql_query($sql_insert, $conn_hd);
		}
		//echo "<font color=blue><b>NIE</b>";
		//$_SESSION['refresh_o_zgloszenia_'.$_REQUEST[zgl_nr].''] = 1;
		
	}
} else {
	$ww_data = Date("Y-m-d H:i:s");
	$ww_osoba = $currentuser;
	$sql66 = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_sprawdzone_data='$ww_data', zgl_sprawdzone_osoba='$ww_osoba' WHERE (zgl_nr IN (".$_REQUEST[nr]."))";

	//$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_wymagany_wyjazd=1,zgl_wymagany_wyjazd_data_ustawienia='$_REQUEST[ww_data]', zgl_wymagany_wyjazd_osoba_wlaczajaca='$_REQUEST[ww_osoba]' WHERE ((zgl_widoczne=1) and (belongs_to='$es_filia') and (zgl_nr='$_REQUEST[zgl_nr]')) LIMIT 1";
	if (mysql_query($sql66, $conn_hd)) {

		$ssql = "SELECT zgl_id FROM $dbname_hd.hd_zgloszenie WHERE (zgl_nr IN (".$_REQUEST[nr]."))";
		$result_dc = mysql_query($ssql, $conn_hd) or die($k_b);	
		
		$dddd = Date('Y-m-d H:i:s');
		$lista_zmian='<u>Potwierdzenie sprawdzenia zgłoszenia przez:</u> <b>'.$currentuser.', '.substr($dddd,0,16).'</b><br />';

		while ($newArray_dc = mysql_fetch_array($result_dc)) {
			$temp_zgl_id	= $newArray_dc['zgl_id'];	
			$sql_insert = "INSERT INTO $dbname_hd.hd_zgloszenie_historia_zmian values ('', '$temp_zgl_id','$dddd','$currentuser','$lista_zmian',1,'',$es_filia)";
			$wynik = mysql_query($sql_insert, $conn_hd);
			
			?>
			<script>
				if (opener) opener.document.getElementById('save<?php echo $temp_zgl_id; ?>').style.display='';
			</script>
			<?php
		}		
	
	//	echo "<font color=blue><b>TAK</b> | Ustawione w dniu <b>".$_REQUEST[ww_data]."</b> przez <b>".$_REQUEST[ww_osoba]."</b></font>";
	//$_SESSION['refresh_o_zgloszenia_'.$_REQUEST[zgl_nr].''] = 1;
	}
}

?>
<script>
self.close();
<?php if ($_REQUEST[donotreloadparent]!=1) { ?>
if (opener) opener.location.reload(true);
<?php } else { ?>

<?php } ?>
</script>