<?php include_once('header.php'); 
include_once('cfg_helpdesk.php');

?>
<body>
<?php 
//if ($submit) { 

$sql_d1="UPDATE $dbname_hd.hd_zgl_wymiany_podzespolow SET wp_wskazanie_sprzetu_z_magazynu=1, wp_sprzedaz_fakt_szcz_id=$_REQUEST[fszcz_id], wp_typ_podzespolu='' WHERE ((wp_zgl_szcz_unique_nr='$_REQUEST[unique]') and (wp_typ_podzespolu='$_REQUEST[typ]')) LIMIT 1";
echo $sql_d1;

if (mysql_query($sql_d1, $conn_hd)) { 
	?><script>
		self.close(); 
		if (opener) opener.location.reload(true); 
	</script><?php
} else { 
	?><script>alert('Wystąpił błąd podczas wykonywania powiązania'); //self.close(); </script><?php
}
//} else {
/*
$result = mysql_query("SELECT zestaw_id, zestaw_opis FROM $dbname.serwis_zestawy WHERE (zestaw_id=$id) LIMIT 1", $conn) or die($k_b);
list($temp_id,$temp_opis)=mysql_fetch_array($result);
errorheader("Czy napewno chcesz usunąć wybrany pusty zestaw z listy ?");
infoheader("<b>".skroc_tekst($temp_opis,40)."</b>");
startbuttonsarea("center");
echo "<form action=$PHP_SELF method=POST>";
echo "<input type=hidden name=mid value=$temp_id>";
addbuttons("tak","nie");
endbuttonsarea();
_form();
}
*/
?>
</body>
</html>