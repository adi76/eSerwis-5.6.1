<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 
	$sql_d1="DELETE FROM $dbname.serwis_komorki WHERE up_id = '$_POST[upid]' LIMIT 1";	
	if (mysql_query($sql_d1, $conn)) { 
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else {
		?><script>info('Wystąpił błąd podczas operacji usuwania'); self.close(); </script><?php
	}
} else {

$result = mysql_query("SELECT up_id,up_nazwa FROM $dbname.serwis_komorki WHERE (up_id=$select_id) LIMIT 1", $conn) or die($k_b);
list($temp_id,$temp_nazwa)=mysql_fetch_array($result);

// sprawdzenie czy nie pobrano sprzętu serwisowego na stan tego UP
$result_a22 = mysql_query("SELECT magazyn_id,magazyn_naprawa_id FROM $dbname.serwis_magazyn WHERE magazyn_status=1 and belongs_to=$es_filia", $conn) or die($k_b);
while ($newArray = mysql_fetch_array($result_a22)) {
	$mid  			= $newArray['magazyn_id'];
	$mnid			= $newArray['magazyn_naprawa_id'];

	$s_pobrany = 0;
	$s_w_naprawie = 0;
	$s_awariawan = 0;
	
	// sprawdź czy jest pobrany sam sprzęt
	$result_a = mysql_query("SELECT historia_id,historia_up, historia_komentarz,historia_data FROM $dbname.serwis_historia WHERE historia_magid=$mid ORDER BY historia_data DESC LIMIT 1", $conn) or die($k_b);
	list($hid,$hup,$hkomentarz,$hdata) = mysql_fetch_array($result_a);
 
 
 	// czy nie ma otwartych awarii wan z tej placówki
	
	$result_a44 = mysql_query("SELECT awaria_id FROM $dbname.serwis_awarie WHERE (awaria_gdzie='$temp_nazwa') and (awaria_status=0) and (belongs_to=$es_filia)", $conn) or die($k_b);
	list($aid) = mysql_fetch_array($result_a44);	

	if ($aid>0) {
		$s_awariawan = 1;
		break;
	}	
	
	$sql_up = "SELECT up_id, up_pion_id FROM $dbname.serwis_komorki WHERE (up_nazwa='$hup') and (belongs_to=$es_filia) LIMIT 1";
	$wynik = mysql_query($sql_up, $conn) or die($k_b);
	$dane_up = mysql_fetch_array($wynik);
	$temp_up_id = $dane_up['up_id'];
	$temp_pion_id = $dane_up['up_pion_id'];
 	
	if ($temp_up_id == $select_id ) { 
		$s_pobrany = 1; 
		break; 
	}
	
	// sprawdź czy z komórki nie ma sprzętu w naprawie
	$result_a33 = mysql_query("SELECT naprawa_id, naprawa_pobrano_z FROM $dbname.serwis_naprawa WHERE (naprawa_id=$mnid) and (naprawa_status<5) and (belongs_to=$es_filia)", $conn) or die($k_b);
	list($nid,$npz) = mysql_fetch_array($result_a33);
	if (($nid>0) && ($npz=='$temp_nazwa')) {
		$s_w_naprawie = 1;
		break;
	}
	
}

if ($s_awariawan==1) { 
	?>
	<script>
	info('Nie można usunąć tej komórki gdyż otwarte jest dla niej zgłoszenie awarii WAN');self.close();
	</script>
	<?php
echo "</body></html>";
exit;
}

if ($s_pobrany==1) { 
	?>
	<script>
	info('Nie można usunąć tej komórki gdyż do tej komórki pobrano sprzęt serwisowy');self.close();
	</script>
	<?php
echo "</body></html>";
exit;
}

if ($s_w_naprawie==1) { 
	?>
	<script>
	info('Nie można usunąć tej komórki gdyż z tej komórki pobrano sprzęt do naprawy');self.close();
	</script>
	<?php
echo "</body></html>";
exit;
}

$result_e1 = mysql_query("SELECT ewidencja_id FROM $dbname.serwis_ewidencja WHERE (ewidencja_up_id=$select_id)", $conn) or die($k_b);
$count_rows = mysql_num_rows($result_e1);
if ($count_rows!=0) { 
	?>
	<script>
	info('Nie można usunąć tej komórki gdyż istnieje sprzęt w ewidencji sprzętu przypisany do tej komórki');self.close();
	</script>
	<?php
echo "</body></html>";
exit;
}
$result = mysql_query("SELECT up_id,up_nazwa FROM $dbname.serwis_komorki WHERE (up_id=$select_id) LIMIT 1", $conn) or die($k_b);
list($temp_id,$temp_nazwa)=mysql_fetch_array($result);
errorheader("Czy napewno chcesz usunąć wybraną komórkę / UP ?");
infoheader("<b>".skroc_tekst($temp_nazwa,70)."</b>");
startbuttonsarea("center");
echo "<form action=$PHP_SELF method=POST>";
echo "<input type=hidden name=upid value=$temp_id>";
addbuttons("tak","nie");
endbuttonsarea();
_form();
}
?>
</body>
</html>