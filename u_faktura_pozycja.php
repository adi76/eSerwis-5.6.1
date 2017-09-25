<?php include_once('header.php'); ?>
<body>
<?php 
include('inc_encrypt.php');
if ($submit) {  
	$sql_e1b="DELETE FROM $dbname.serwis_faktura_szcz WHERE pozycja_id = '$_POST[uid]' LIMIT 1";
	if (mysql_query($sql_e1b, $conn)) { 
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else { 
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php	
	}
} else { 

$sql_e444 = "SELECT pozycja_id, pozycja_nazwa, pozycja_sn, pozycja_cena_netto FROM $dbname.serwis_faktura_szcz WHERE (pozycja_id=$id) LIMIT 1";
$result444 = mysql_query($sql_e444, $conn) or die($k_b);
$newArray = mysql_fetch_array($result444);
$temp_id  		= $newArray['pozycja_id'];
$temp_nazwa		= $newArray['pozycja_nazwa'];
$temp_sn		= $newArray['pozycja_sn'];
$temp_cena_cr	= $newArray['pozycja_cena_netto'];
$temp_cena 		= decrypt_md5($temp_cena_cr,$key);
	
errorheader("Czy napewno chcesz usunąć pozycję z faktury ?");
infoheader("<b>".skroc_tekst($temp_nazwa,40).", SN : ".skroc_tekst($temp_sn,20)."");

startbuttonsarea("center");
echo "<form action=$PHP_SELF method=POST>";
echo "<input type=hidden name=uid value=$temp_id>";
addbuttons("tak","nie");
endbuttonsarea();

_form();
}
?>
</body>
</html>