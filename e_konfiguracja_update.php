<?php 
include_once('header.php');
$result55 = mysql_query("SELECT konfiguracja_id,konfiguracja_nazwa,pamiec,dysk,procesor FROM $dbname.serwis_slownik_konfiguracja WHERE konfiguracja_id=$id", $conn) or die($k_b);
while (list($sprz_id,$sprz_nazwa,$temp_RAM,$temp_HDD,$temp_PROCESOR)=mysql_fetch_array($result55)) {
	$konf_opis='Procesor '.$temp_PROCESOR.'GHz, '.$temp_RAM.'MB RAM, '.$temp_HDD.'GB HDD';
	$sql555="UPDATE $dbname.serwis_ewidencja SET ewidencja_konfiguracja='$sprz_nazwa', k_procesor='$temp_PROCESOR', k_pamiec='$temp_RAM', k_dysk='$temp_HDD' WHERE ((k_procesor='$PROC') and (k_pamiec='$RAM') and (k_dysk='$HDD'))";
	$result555 = mysql_query($sql555, $conn) or die($k_b);			
}
?>
<script>
alert('Baza sprzętu została zaktualizowana');
self.close();	
</script>