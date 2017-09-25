<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body>
<?php 
if ($submit) {	

// ukryj wyjazdy
	$sql1 = "SELECT zgl_szcz_unikalny_numer,zgl_szcz_osoba_wykonujaca_krok,zgl_szcz_il_km,zgl_szcz_czas_wykonywania FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_id='$_REQUEST[id]') and (belongs_to=$es_filia)";
	
//	echo "<br />".$sql1;
	$result = mysql_query($sql1, $conn_hd) or die($k_b);
	
// ukryj wyjazdy
	while ($dane=mysql_fetch_array($result)) {
		$temp_unique = $dane['zgl_szcz_unikalny_numer'];
		$temp_osoba = $dane['zgl_szcz_osoba_wykonujaca_krok'];
		$temp_km = $dane['zgl_szcz_il_km'];
		$temp_czas = $dane['zgl_szcz_czas_wykonywania'];
	}
		//echo "$temp_unique<br />";

		list($ilosc_km_razem,$ilosc_min_razem)=mysql_fetch_array(mysql_query("SELECT zgl_razem_km, zgl_razem_czas FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_nr=$_REQUEST[numer_zgl]) LIMIT 1", $conn_hd));
			
	//	echo "<br />".$ilosc_km_razem;
	//	echo "<br />".$ilosc_min_razem;
		
		$nowy_czas_razem = $ilosc_min_razem - $temp_czas;
		$nowe_km_razem = $ilosc_km_razem - $temp_km;
			
	//	echo "<br />".$nowy_czas_razem;
	//	echo "<br />".$nowe_km_razem;
		
		$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_razem_czas='$nowy_czas_razem' WHERE (belongs_to='$es_filia') and (zgl_nr='$_REQUEST[numer_zgl]') LIMIT 1";
		
	//	echo "<br />".$sql;
				
		$result = mysql_query($sql, $conn_hd) or die($k_b);

		if ($temp_km!=0) {	
			$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_razem_km='$nowe_km_razem' WHERE (belongs_to='$es_filia') and (zgl_id='$_REQUEST[numer_zgl]') LIMIT 1";
	//		echo "<br />".$sql;
			$result = mysql_query($sql, $conn_hd) or die($k_b);
			
			$sql = "UPDATE $dbname_hd.hd_zgloszenie_wyjazd SET wyjazd_widoczny='0' WHERE (belongs_to='$es_filia') and (wyjazd_zgl_szcz_id='$temp_unique') and (wyjazd_osoba='$temp_osoba') LIMIT 1";
	//		echo "<br />".$sql;			
			$result = mysql_query($sql, $conn_hd) or die($k_b);
			
/*
			$r3 = mysql_query("SELECT zgl_wymagany_wyjazd, zgl_wymagany_wyjazd_data_ustawienia, zgl_wymagany_wyjazd_osoba_wlaczajaca FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id='$_REQUEST[numer_zgl]') LIMIT 1", $conn_hd) or die($k_b);
			list($ww,$ww_data,$ww_osoba)=mysql_fetch_array($r3);	
			
			if ($ww_data!='0000-00-00 00:00:00') {
			
				$r3 = mysql_query("SELECT zgl_szcz_unikalny_numer FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$_REQUEST[numer_zgl]') and (zgl_szcz_widoczne=1) and (zgl_szcz_byl_wyjazd=1) and (zgl_szcz_unikalny_numer<>'$_REQUEST[unique]') ORDER BY zgl_szcz_nr_kroku DESC LIMIT 1", $conn_hd) or die($k_b);
				list($last_unique)=mysql_fetch_array($r3);
				
				$r3 = mysql_query("SELECT zgl_data_wpisu FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_unikalny_numer='$last_unique') LIMIT 1", $conn_hd) or die($k_b);
				list($data_wpisu)=mysql_fetch_array($r3);
				
				if ($data_wpisu<=$ww_data) {
					$sqld = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_wymagany_wyjazd=1 WHERE (zgl_id='$_REQUEST[numer_zgl]') LIMIT 1";
					$resultd = mysql_query($sqld, $conn_hd) or die($k_b);
				}
			}	
*/		
		}
		
	// ukryj zgloszenie szczegółowe
	$sql = "UPDATE $dbname_hd.hd_zgloszenie_szcz SET zgl_szcz_widoczne='0' WHERE (belongs_to='$es_filia') and (zgl_szcz_id='$_REQUEST[id]') LIMIT 1";
	//$result = mysql_query($sql, $conn_hd) or die($k_b);		

	if (mysql_query($sql, $conn_hd)) { 
		
		$r3 = mysql_query("SELECT zgl_szcz_status,zgl_szcz_osoba_wykonujaca_krok  FROM $dbname_hd.hd_zgloszenie_szcz WHERE ((zgl_szcz_zgl_id='$_REQUEST[numer_zgl]') and (zgl_szcz_widoczne=1)) ORDER BY zgl_szcz_nr_kroku DESC LIMIT 1", $conn_hd) or die($k_b);
		list($last_status,$last_osoba)=mysql_fetch_array($r3);
		
		$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_status='$last_status', zgl_osoba_przypisana='$last_osoba' WHERE (belongs_to='$es_filia') and (zgl_nr='$_REQUEST[numer_zgl]') LIMIT 1";
		$result = mysql_query($sql, $conn_hd) or die($k_b);
		
		?>
		<script>
			if (opener) opener.location.reload(true); 
			self.close();
			newWindow(600,50,'hd_o_zgloszenia_wylicz_czasy.php?nr=<?php echo $_REQUEST[numer_zgl]; ?>');
		</script>
		<?php
	} else { 
		?><script>info('Wystąpił błąd podczas operacji ukrywania zgłoszenia'); self.close(); </script><?php
	}
} else {
//$result = mysql_query("SELECT drukarka_id,drukarka_nazwa FROM $dbname.serwis_slownik_drukarka WHERE (drukarka_id=$id) LIMIT 1", $conn) or die($k_b);
//list($temp_id,$temp_nazwa)=mysql_fetch_array($result);
errorheader("Czy napewno chcesz ukryć krok <b>$_GET[nr]</b> w zgłoszeniu <b>$_GET[numer_zgl]</b>");
//infoheader("<b>".$temp_nazwa."</b> ze słownika ?");
startbuttonsarea("center");
nowalinia();
echo "<form action=$PHP_SELF method=POST>";
echo "<input type=hidden name=id value=$_GET[id]>";
echo "<input type=hidden name=nr value=$_GET[nr]>";
echo "<input type=hidden name=unique value=$_GET[unique]>";
echo "<input type=hidden name=numer_zgl value=$_GET[numer_zgl]>";
addbuttons("tak","nie");
endbuttonsarea();
_form();
}
?>
</body>
</html>