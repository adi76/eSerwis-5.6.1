<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 
	$sql_d1="DELETE FROM $dbname.serwis_oprogramowanie WHERE ((oprogramowanie_ewidencja_id = '$_POST[id]') and (oprogramowanie_id='$_POST[opr_id]')) LIMIT 1";
	if (mysql_query($sql_d1, $conn)) { 
		$sql_t1 = "SELECT * FROM $dbname.serwis_oprogramowanie WHERE (oprogramowanie_ewidencja_id=$_POST[eid])";
		$result1 = mysql_query($sql_t1, $conn) or die($k_b);
		$count_rows=mysql_num_rows($result1);
		if ($count_rows==0) {
			$sql_t1 = "UPDATE $dbname.serwis_ewidencja SET ewidencja_oprogramowanie_id='0' WHERE (ewidencja_id=$_POST[eid])";
			$result1 = mysql_query($sql_t1, $conn) or die($k_b);
		}
			?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
		} 
	else {
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
	$temp_belongs_to	= $newArray['belongs_to'];	
}

	$result_r = mysql_query("SELECT rola_nazwa FROM $dbname.serwis_slownik_rola WHERE (rola_id=$temp_rola_id) LIMIT 1", $conn) or die($k_b);
	list($temp_rola_nazwa)=mysql_fetch_array($result_r);
	$result_l = mysql_query("SELECT up_nazwa FROM $dbname.serwis_komorki WHERE (up_id=$temp_up_id) LIMIT 1", $conn) or die($k_b);
	list($temp_up_nazwa)=mysql_fetch_array($result_l);
	$result_ll = mysql_query("SELECT oprogramowanie_nazwa FROM $dbname.serwis_oprogramowanie WHERE (oprogramowanie_id=$opr_id) LIMIT 1", $conn) or die($k_b);
	list($temp_opr_nazwa)=mysql_fetch_array($result_ll);
	errorheader("Czy napewno chcesz usunąć wybrane oprogramowanie ?");
	infoheader("<b>".$temp_opr_nazwa."<br /></b>z<b><br />".$temp_nazwa."</b> w <b>".skroc_tekst($temp_up_nazwa,40)." (SN: ".$temp_sn.") ?");
	startbuttonsarea("center");	
	echo "<form action=$PHP_SELF method=POST>";
	echo "<input type=hidden name=eid value=$temp_id>";	
	echo "<input type=hidden name=id value=$id>";
	echo "<input type=hidden name=opr_id value=$opr_id>";	
	addbuttons("tak","nie");
	endbuttonsarea();
	_form();
}
?>
</body>
</html>