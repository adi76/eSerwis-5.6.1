<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body>
<?php 
if ($submit) {	
	
	$sqld = "UPDATE $dbname_hd.hd_zgloszenie_wyjazd SET wyjazd_widoczny='0' WHERE (belongs_to='$es_filia') and (wyjazd_zgl_szcz_id='$_REQUEST[unique]')";
	$resultd = mysql_query($sqld, $conn_hd) or die($k_b);

	$sqld = "UPDATE $dbname_hd.hd_zgloszenie_szcz SET zgl_szcz_byl_wyjazd='0', zgl_szcz_il_km='0' WHERE (belongs_to='$es_filia') and (zgl_szcz_unikalny_numer='$_REQUEST[unique]') LIMIT 1";
	//$resultd = mysql_query($sqld, $conn_hd) or die($k_b);
		
	if (mysql_query($sqld, $conn_hd)) { 

/*
			$r3 = mysql_query("SELECT zgl_wymagany_wyjazd, zgl_wymagany_wyjazd_data_ustawienia, zgl_wymagany_wyjazd_osoba_wlaczajaca FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id='$_REQUEST[zgl_nr]') LIMIT 1", $conn_hd) or die($k_b);
			list($ww,$ww_data,$ww_osoba)=mysql_fetch_array($r3);	
			
			if ($ww_data!='0000-00-00 00:00:00') {
			
				$r3 = mysql_query("SELECT zgl_szcz_unikalny_numer FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$_REQUEST[zgl_nr]') and (zgl_szcz_widoczne=1) and (zgl_szcz_byl_wyjazd=1) and (zgl_szcz_unikalny_numer<>'$_REQUEST[unique]') and (zgl_data_wpisu<='$ww_data') ORDER BY zgl_szcz_nr_kroku DESC LIMIT 1", $conn_hd) or die($k_b);
				list($last_unique)=mysql_fetch_array($r3);
				
				if ($last_unique!=null) {
					$r3 = mysql_query("SELECT zgl_data_wpisu FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_unikalny_numer='$last_unique') LIMIT 1", $conn_hd) or die($k_b);
					list($data_wpisu)=mysql_fetch_array($r3);
				
					if ($data_wpisu<=$ww_data) {
						$sqld = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_wymagany_wyjazd=1 WHERE (zgl_id='$_REQUEST[zgl_nr]') LIMIT 1";
						$resultd = mysql_query($sqld, $conn_hd) or die($k_b);
					}
				}
			}
*/		
		$r3 = mysql_query("SELECT sum(zgl_szcz_il_km) FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$_REQUEST[zgl_nr]') and (zgl_szcz_widoczne=1) and (zgl_szcz_byl_wyjazd=1)", $conn_hd) or die($k_b);
		list($razem_km)=mysql_fetch_array($r3);
		if ($razem_km=='') $razem_km = 0;
		
		$sql_a = "UPDATE $dbname_hd.hd_zgloszenie_szcz SET zgl_szcz_byl_wyjazd=0 WHERE ((zgl_szcz_widoczne=1) and (zgl_szcz_unikalny_numer='$_REQUEST[unique]')) LIMIT 1";
		$result_a = mysql_query($sql_a, $conn_hd) or die($k_b);
		
		$sql_a = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_razem_km=$razem_km WHERE ((zgl_widoczne=1) and (zgl_id='$_REQUEST[zgl_nr]')) LIMIT 1";
		$result_a = mysql_query($sql_a, $conn_hd) or die($k_b);
		
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else { 
		?><script>info('Wystąpił błąd podczas operacji usuwania wyjazdu'); self.close(); </script><?php
	}
} else {

	errorheader("Czy napewno chcesz usunąć wyjazd z kroku nr <b>$_GET[nr_kroku]</b> w zgłoszeniu nr <b>$_GET[zgl_nr]</b>");
	startbuttonsarea("center");

	echo "<form action=$PHP_SELF method=POST>";
	echo "<input type=hidden name=unique value=$_GET[unique]>";
	echo "<input type=hidden name=nr_kroku value=$_GET[nr_kroku]>";
	echo "<input type=hidden name=zgl_nr value=$_GET[zgl_nr]><br />";

	addbuttons("tak","nie");
	endbuttonsarea();
	_form();
	}
	
?>
</body>
</html>