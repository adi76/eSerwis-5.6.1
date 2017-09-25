<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body>
<?php 
if ($submit) {	
// ukryj zgloszenie
	$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_widoczne='0' WHERE (belongs_to='$es_filia') and (zgl_id='$_POST[id]') LIMIT 1";
	$result = mysql_query($sql, $conn_hd) or die($k_b);

// ukryj kroki
	$sql = "UPDATE $dbname_hd.hd_zgloszenie_szcz SET zgl_szcz_widoczne='0' WHERE (belongs_to='$es_filia') and (zgl_szcz_zgl_id='$_POST[id]')";
	$result = mysql_query($sql, $conn_hd) or die($k_b);
	
// ukryj wyjazdy
	$sql1 = "SELECT zgl_szcz_unikalny_numer,zgl_szcz_osoba_wykonujaca_krok FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_byl_wyjazd='1') and (zgl_szcz_zgl_id='$_POST[id]') and (belongs_to=$es_filia)";
	$result = mysql_query($sql1, $conn_hd) or die($k_b);
	
// ukryj wyjazdy
	while ($dane=mysql_fetch_array($result)) {
		$temp_unique = $dane['zgl_szcz_unikalny_numer'];
		$temp_osoba = $dane['zgl_szcz_osoba_wykonujaca_krok'];
		//echo "$temp_unique<br />";
		
		$sql = "UPDATE $dbname_hd.hd_zgloszenie_wyjazd SET wyjazd_widoczny='0' WHERE (belongs_to='$es_filia') and (wyjazd_zgl_szcz_id='$temp_unique') and (wyjazd_osoba='$temp_osoba') LIMIT 1";
		$result = mysql_query($sql, $conn_hd) or die($k_b);
	}
	if (mysql_query($sql, $conn_hd)) { 
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else { 
		?><script>info('Wystąpił błąd podczas operacji ukrywania zgłoszenia'); self.close(); </script><?php
	}
} else {
//$result = mysql_query("SELECT drukarka_id,drukarka_nazwa FROM $dbname.serwis_slownik_drukarka WHERE (drukarka_id=$id) LIMIT 1", $conn) or die($k_b);
//list($temp_id,$temp_nazwa)=mysql_fetch_array($result);
errorheader("Czy napewno chcesz ukryć zgłoszenie nr <b>$_GET[nr]</b> z dnia <b>$_GET[data]</b>");
//infoheader("<b>".$temp_nazwa."</b> ze słownika ?");
startbuttonsarea("center");
echo "<form action=$PHP_SELF method=POST>";
echo "<br /><input type=hidden name=id value=$_GET[id]>";
echo "<input type=hidden name=nr value=$_GET[nr]>";
addbuttons("tak","nie");
endbuttonsarea();
_form();
}
?>
</body>
</html>