<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 
	$sql_d1="DELETE FROM $dbname.serwis_ewidencja WHERE ewidencja_id = '$_POST[eid]' LIMIT 1";
//	$sql_d1="SELECT * FROM $dbname.serwis_ewidencja";
	//echo "$sql_d1<br />";
	if (mysql_query($sql_d1, $conn)) { 
		$sql_do="DELETE FROM $dbname.serwis_oprogramowanie WHERE oprogramowanie_ewidencja_id=$_POST[eid]";
		//echo "$sql_do<br />";
		$run_sql_do=mysql_query($sql_do,$conn) or die($k_b);
		$sql_do1 = "UPDATE $dbname.serwis_ewidencja SET ewidencja_drukarka_powiaz_z=0 WHERE ewidencja_drukarka_powiaz_z=$_POST[eid]";
	//	echo "$sql_do1<br />";
		$run_sql_do1 = mysql_query($sql_do1,$conn) or die($k_b);
		?><script>if (opener) opener.location.reload(true); self.close();  </script><?php
	} else 	{
		?><script>info('Wystąpił błąd podczas operacji usuwania'); self.close(); </script><?php
	}
} else { 
$sql_e = "SELECT * FROM $dbname.serwis_ewidencja WHERE (ewidencja_id=$id) LIMIT 1";
$result = mysql_query($sql_e, $conn) or die($k_b);
while ($newArray = mysql_fetch_array($result)) {
	$temp_id  			= $newArray['ewidencja_id'];
	$temp_rola_id		= $newArray['ewidencja_typ'];
	$temp_up_id			= $newArray['ewidencja_up_id'];
	$temp_nazwa			= $newArray['ewidencja_komputer_opis'];
	$temp_sn			= $newArray['ewidencja_komputer_sn'];
	$temp_ni			= $newArray['ewidencja_zestaw_ni'];
	$temp_belongs_to	= $newArray['belongs_to'];
	$temp_dnazwa		= $newArray['ewidencja_drukarka_opis'];
	$temp_dsn			= $newArray['ewidencja_drukarka_sn'];
	$temp_dni			= $newArray['ewidencja_drukarka_ni'];
}
	$result_r = mysql_query("SELECT rola_nazwa FROM $dbname.serwis_slownik_rola WHERE (rola_id=$temp_rola_id) LIMIT 1", $conn) or die($k_b);
	list($temp_rola_nazwa)=mysql_fetch_array($result_r);

	if (($temp_rola_nazwa=='Komputer') || ($temp_rola_nazwa=='Serwer') || ($temp_rola_nazwa=='Notebook')) {
	} else {
			$temp_nazwa = $temp_dnazwa;
			$temp_sn	= $temp_dsn;
			$temp_ni	= $temp_dni;
		}
	$result_l = mysql_query("SELECT up_nazwa FROM $dbname.serwis_komorki WHERE (up_id=$temp_up_id) LIMIT 1", $conn) or die($k_b);
	list($temp_up_nazwa)=mysql_fetch_array($result_l);
	errorheader("Czy napewno chcesz usunąć wybrany sprzęt z ewidencji ?");
	if (($temp_sn!='') || ($temp_ni!='')) {
		infoheader("<b>".$temp_rola_nazwa." ".$temp_nazwa."</b><br />SN: ".$temp_sn.", NI: ".$temp_ni."<br />z<br /><b>".$temp_up_nazwa."</b>"); 
	} else infoheader("<b>".$temp_rola_nazwa."</b> z lokalizacji: <b>".$temp_up_nazwa."</b>");
	startbuttonsarea("center");
	echo "<form action=$PHP_SELF method=POST>";
	echo "<input type=hidden name=eid value=$temp_id>";
	addbuttons("tak","nie");
	endbuttonsarea();
	_form();
}
?>
</body>
</html>