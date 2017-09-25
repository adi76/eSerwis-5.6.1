<?php include_once('header.php'); ?>
<?php
echo "<body>";

// wyczyœæ zmienne $_SESSION
unset($_SESSION['faktura_szcz_id_for_zgloszenie_nr_'.$_REQUEST[id].'']);
unset($_SESSION['typ_podzespolu_for_zgloszenie_nr_'.$_REQUEST[id].'']);
unset($_SESSION['podzespoly_do_zgl_nr_'.$_REQUEST[id].'']);
unset($_SESSION['podzespoly_do_zgl_nr_'.$_REQUEST[id].'_count']);
unset($_SESSION['wybrany_sprzet_do_wymiany_dla_zgl_nr_'.$_REQUEST[id].'']);
unset($_SESSION['typ_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].'']);
unset($_SESSION['model_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].'']);
unset($_SESSION['sn_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].'']);
unset($_SESSION['ni_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].'']);
unset($_SESSION['wc_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].'']);

// usuñ utworzony zestaw
if ($_REQUEST[zestawid]!='') {

	$result1 = mysql_query("SELECT zestawpozycja_fszcz_id FROM $dbname.serwis_zestaw_szcz WHERE zestawpozycja_zestaw_id=$_REQUEST[zestawid]", $conn) or die($k_b);
	//echo "SELECT zestawpozycja_id FROM $dbname.serwis_zestaw_szcz WHERE zestawpozycja_zestaw_id=$_REQUEST[id]<br />";

	while (list($pozid)=mysql_fetch_array($result1)) {

		$sql_zmienstatuspozycji = "UPDATE $dbname.serwis_faktura_szcz SET pozycja_status=0 WHERE (pozycja_id='$pozid') LIMIT 1";
	//	echo $sql_zmienstatuspozycji."<br />";
		if (mysql_query($sql_zmienstatuspozycji,$conn)) { 
			$usunzzestawu = "DELETE FROM $dbname.serwis_zestaw_szcz WHERE zestawpozycja_fszcz_id=$pozid";
	//		echo $usunzzestawu."<br />";
			$wykonaj_usuniecie = mysql_query($usunzzestawu,$conn) or die($k_b);
		}

	}

	$sql_d1="DELETE FROM $dbname.serwis_zestawy WHERE zestaw_id = '$_REQUEST[zestawid]' LIMIT 1";
	//echo $sql_d1."<br />";
	$wynik = mysql_query($sql_d1, $conn);

}
?>
<script>
self.close();
if (opener) opener.location.reload(true);
</script>
<?php
echo "</body>";

?>