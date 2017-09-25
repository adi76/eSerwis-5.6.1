<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 
include_once('cfg_helpdesk.php');

$sql="UPDATE $dbname.serwis_faktury SET faktura_status = 0 WHERE faktura_id = '$_POST[fid]'";
if (mysql_query($sql, $conn)) { 
	// ustalenie statusów pozycji
		$sql22="SELECT pozycja_id FROM $dbname.serwis_faktura_szcz WHERE pozycja_nr_faktury='$_POST[fid]'";
		$result22 = mysql_query($sql22, $conn) or die($k_b);
		while ($newArray22 = mysql_fetch_array($result22)) {
			$temp_pozid  = $newArray22['pozycja_id'];	
			
			$sql33="SELECT wp_zgl_id FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_sprzedaz_fakt_szcz_id='".$temp_pozid."') and  (wp_sprzet_active=1)";
			$result33 = mysql_query($sql33, $conn_hd) or die($k_b);
			$count_rows33 = mysql_num_rows($result33);
						
			if ($count_rows33==0) {
				$sql44="UPDATE $dbname.serwis_faktura_szcz SET pozycja_status=-1 WHERE pozycja_id='".$temp_pozid."' LIMIT 1";
				$result44 = mysql_query($sql44, $conn) or die($k_b);
			}
		}
	?><script>	
		if (opener) opener.location.reload(true); 
		//self.close();
		if (confirm("Czy chcesz przejść do niezatwierdzonych faktur ?")) {
			opener.location.href='z_faktury.php?showall=0>';
			self.close();
		} else self.close();
		</script><?php
} else {
	 ?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php	
}
} else { ?>
<?php
$sql_e444 = "SELECT * FROM $dbname.serwis_faktury WHERE (faktura_id='$id') LIMIT 1";
$result444 = mysql_query($sql_e444, $conn) or die($k_b);
$newArray = mysql_fetch_array($result444);
$temp_id  			= $newArray['faktura_id'];
$temp_numer			= $newArray['faktura_numer'];
$temp_data			= $newArray['faktura_data'];
$temp_dostawca		= $newArray['faktura_dostawca'];
$temp_koszty		= $newArray['faktura_koszty_dodatkowe'];
$temp_osoba			= $newArray['faktura_osoba'];
$temp_datawpisu		= $newArray['faktura_datawpisu'];
$temp_status		= $newArray['faktura_status'];

errorheader("Czy napewno chcesz cofnąć zatwierdzenie dla wybranej faktury ?");
infoheader("<b>Nr: ".$temp_numer.", ".$temp_dostawca." z ".$temp_data."</b>");
startbuttonsarea("center");
echo "<form action=$PHP_SELF method=POST>";
echo "<input type=hidden name=fid value=$temp_id>";	
echo "<input type=hidden name=pozycji value=$poz>";
addbuttons("tak","nie");
endbuttonsarea();

_form();
}
?>
</body>
</html>