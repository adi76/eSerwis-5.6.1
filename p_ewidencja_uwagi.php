<?php include_once('header.php'); ?>
<body>
<?php
$sql_e = "SELECT ewidencja_typ_nazwa, ewidencja_up_nazwa, ewidencja_id, ewidencja_komputer_opis, ewidencja_komputer_sn, ewidencja_drukarka_opis, ewidencja_drukarka_sn, ewidencja_uwagi FROM $dbname.serwis_ewidencja WHERE (ewidencja_id=$id) LIMIT 1";
$result = mysql_query($sql_e, $conn) or die($k_b);
$count_rows = mysql_num_rows($result);
if ($count_rows>0) {
	while ($newArray = mysql_fetch_array($result)) {
	$temp_id  		= $newArray['ewidencja_id'];
	$temp_typn		= $newArray['ewidencja_typ_nazwa'];
	$temp_upnazwa	= $newArray['ewidencja_up_nazwa'];
	if (($temp_typn=='Komputer') || ($temp_typn=='Serwer') || ($temp_typn=='Notebook')) {	
		$temp_nazwa  		= $newArray['ewidencja_komputer_opis'];
		$temp_sn  			= $newArray['ewidencja_komputer_sn'];
		pageheader("Uwagi o sprzęcie");
		startbuttonsarea("center");
			echo $temp_typn." ".$temp_nazwa." (SN: ".$temp_sn.")<br />z ".$temp_upnazwa;
		endbuttonsarea();
	} elseif ($temp_typn=='Drukarka') {
			$temp_dnazwa  		= $newArray['ewidencja_drukarka_opis'];
			$temp_dsn  			= $newArray['ewidencja_drukarka_sn'];
			pageheader("Uwagi o drukarce");
			startbuttonsarea("center");
				echo $temp_dnazwa." (SN: ".$temp_dsn.")<br />z ".$temp_upnazwa;
			endbuttonsarea();
		} else {
				pageheader("Uwagi o sprzęcie");
				startbuttonsarea("center");
					echo $temp_typn."<br />z ".$temp_upnazwa;
				endbuttonsarea();
			}
	hr();
	$temp_uwagi	= $newArray['ewidencja_uwagi'];
	startbuttonsarea("left");
	echo "$temp_uwagi";
	endbuttonsarea();
}
} else errorheader("Brak uwag");
startbuttonsarea("right");
addbuttons("zamknij");
endbuttonsarea();
?>
</body>
</html>