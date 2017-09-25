<?php include_once('header.php'); ?>
<body>
<?php 
if (($submit) || ($_REQUEST[noquestion]==1)) { 
	$sql_zmienstatuspozycji = "UPDATE $dbname.serwis_faktura_szcz SET pozycja_status=0, pozycja_datasprzedazy='0000-00-00' WHERE (pozycja_id='$_REQUEST[id]') LIMIT 1";
	if (mysql_query($sql_zmienstatuspozycji,$conn)) { 
		$usunzzesprzedazy = "DELETE FROM $dbname.serwis_sprzedaz WHERE sprzedaz_pozycja_id=$_REQUEST[id] LIMIT 1";
		$wykonaj_usuniecie = mysql_query($usunzzesprzedazy,$conn) or die($k_b);
		$usunzzestawu = "DELETE FROM $dbname.serwis_zestaw_szcz WHERE zestawpozycja_fszcz_id=$_REQUEST[id] LIMIT 1";
		$wykonaj_usuniecie2 = mysql_query($usunzzestawu,$conn) or die($k_b);
		
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else { 
			?><script>info('Wystąpił błąd podczas operacji usuwania'); self.close(); </script><?php
		}
} else {
$result1 = mysql_query("SELECT pozycja_id, pozycja_nazwa, pozycja_sn FROM $dbname.serwis_faktura_szcz WHERE (pozycja_id=$id ) LIMIT 1", $conn) or die($k_b);
list($temp_pozid, $temp_poznazwa, $temp_pozsn)=mysql_fetch_array($result1);
errorheader("Czy napewno anulować sprzedaż wybranego towaru ?");
infoheader("<b>".skroc_tekst($temp_poznazwa,50)."</b> (SN: ".$temp_pozsn.")");
startbuttonsarea("center");
echo "<form action=$PHP_SELF method=POST>";
echo "<input type=hidden name=id value=$temp_pozid>";
addbuttons("tak","nie");
endbuttonsarea();
_form();
}
?>
</body>
</html>