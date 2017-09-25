<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
include('inc_encrypt.php');
if ($submit) { 
$dddd = Date('Y-m-d');
$dddd1 = Date('Y-m-d H:i:s');

$cena = str_replace(',','.',$_POST[kwotapf]);
$crypted_cena = crypt_md5($cena, $key);

$sql_t = "DELETE FROM $dbname.serwis_podfaktury WHERE pf_id='$_POST[pfid]' LIMIT 1";

if (mysql_query($sql_t, $conn)) { 

		// zebranie sumy kosztów z wszystkich podfaktury
		$sql_get_sum = "SELECT pf_kwota_netto FROM $dbname.serwis_podfaktury WHERE ((pf_nr_faktury_id=$_POST[fid]) and (belongs_to=$es_filia))";
		$lacznie_kwota = 0;
		$result_get_suma = mysql_query($sql_get_sum, $conn) or die($k_b);
		while ($newArray1 = mysql_fetch_array($result_get_suma)) {
			$temp_koszty	= $newArray1['pf_kwota_netto'];

			$lacznie_kwota += decrypt_md5($temp_koszty,$key);
		}
	
		$lacznie_kwota_crypted = crypt_md5($lacznie_kwota,$key);

		$sql_update_faktura = "UPDATE $dbname.serwis_faktury SET faktura_koszty_dodatkowe='$lacznie_kwota_crypted' WHERE faktura_id=$_POST[fid] LIMIT 1";
		
		$doit = mysql_query($sql_update_faktura,$conn) or die($k_b);
		
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
} else 
	{
	  ?><script>info('Wystąpił błąd podczas dodawania podfaktury do bazy'); self.close(); </script><?php		
		}

} else { 

$sql5 = "SELECT * FROM $dbname.serwis_podfaktury WHERE pf_id=$id";
$result5 = mysql_query($sql5, $conn) or die($k_b);
$newArray = mysql_fetch_array($result5);

$temp_id  			= $newArray['pf_id'];
$temp_nrfaktury		= $newArray['pf_nr_faktury_id'];
$temp_numer			= $newArray['pf_numer'];
$temp_data			= $newArray['pf_data'];
$temp_dostawca		= $newArray['pf_dostawca_nazwa'];
$temp_kwota_netto	= $newArray['pf_kwota_netto'];
$temp_uwagi			= $newArray['pf_uwagi'];


$kwotan = decrypt_md5($temp_kwota_netto,$key);
	
errorheader("Czy napewno chcesz usunąć podfakturę ?");
infoheader("<b>".$temp_numer."</b> z dnia <b>".$temp_data."</b>");

startbuttonsarea("center");
echo "<form action=$PHP_SELF method=POST>";
addbuttons("tak","nie");
endbuttonsarea();
echo "<input type=hidden name=pfid value=$temp_id>";
echo "<input type=hidden name=fid value=$temp_nrfaktury>";
_form();

}
?>
</body>
</html>