<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php'); 
?>
<?php
echo "<body>";

// anuluj wymianê zestawu dla kroku zg³oszenia
if ($_REQUEST[unique]!='') {

	$sql_usun_z_wp = "DELETE FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_zgl_szcz_unique_nr='$_REQUEST[unique]')";
	if (mysql_query($sql_usun_z_wp,$conn_hd)) { 
		
		$sql_zmienstatuspozycji = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_czy_powiazane_z_wymiana_podzespolow=0 WHERE (zgl_id='$_REQUEST[id]') LIMIT 1";
		if (mysql_query($sql_zmienstatuspozycji,$conn)) { 
		}
		
	}
	
}
?>
<script>
self.close();
if (opener) opener.location.reload(true);
</script>
<?php
echo "</body>";

?>