<?php include_once('header.php'); ?>
<body>
<?php 

$result1 = mysql_query("SELECT zestawpozycja_fszcz_id FROM $dbname.serwis_zestaw_szcz WHERE zestawpozycja_zestaw_id=$_REQUEST[id]", $conn) or die($k_b);
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

$sql_d1="DELETE FROM $dbname.serwis_zestawy WHERE zestaw_id = '$_REQUEST[id]' LIMIT 1";
//echo $sql_d1."<br />";
$wynik = mysql_query($sql_d1, $conn);

?><script>if (opener) opener.location.reload(true); self.close(); </script>
</body>
</html>