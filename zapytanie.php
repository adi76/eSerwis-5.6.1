<?php include_once('header.php'); ?>
<body>
<?php
include('body_start.php');
include('inc_encrypt.php');

$sql="SELECT * FROM $dbname.serwis_faktura_szcz WHERE pozycja_nr_faktury IN (SELECT faktura_id FROM $dbname.serwis_faktury WHERE faktura_data >= '2009-09-01' and faktura_data <= '2009-09-30' ORDER BY faktura_data ASC)";
$result = mysql_query($sql, $conn) or die($k_b);

starttable();
$i = 0;
$j = 1;
$kwotarazem = 0;
$kwotarazempodfaktury = 0;
while ($newArray = mysql_fetch_array($result)) {
	$temp_id  			= $newArray['pozycja_id'];
	$temp_nrfaktury		= $newArray['pozycja_nr_faktury'];
	$temp_numer			= $newArray['pozycja_numer'];
	$temp_nazwa			= $newArray['pozycja_nazwa'];
	$temp_ilosc			= $newArray['pozycja_ilosc'];
	$temp_sn			= $newArray['pozycja_sn'];
	$temp_cenanetto_cr	= $newArray['pozycja_cena_netto'];
	$temp_status		= $newArray['pozycja_status'];
	$temp_cenanettoodsp_cr	= $newArray['pozycja_cena_netto_odsprzedazy'];
	$temp_typ			= $newArray['pozycja_typ'];
	$temp_uwagi			= $newArray['pozycja_uwagi'];
	$temp_pds = $newArray['pozycja_datasprzedazy'];
	
	$temp_cenanetto = decrypt_md5($temp_cenanetto_cr,$key);
	$temp_cenanettoodsp = decrypt_md5($temp_cenanettoodsp_cr,$key);
	
	echo "<tr>";
	
		$sql1="SELECT * FROM $dbname.serwis_faktury WHERE (faktura_id='$temp_nrfaktury')";
		$result1 = mysql_query($sql1, $conn) or die($k_b);
		while ($newArray4 = mysql_fetch_array($result1)) {
			$temp_id4  			= $newArray4['faktura_id'];
			$temp_numer4		= $newArray4['faktura_numer'];
			$temp_data4			= $newArray4['faktura_data'];
			$temp_dostawca4		= $newArray4['faktura_dostawca'];	
			$temp_nz			= $newArray4['faktura_nr_zamowienia'];	
		
		}
	
	echo "<td width=30 class=center>$j</td>";
	echo "<td class=wrap>$temp_nazwa</td>";
	echo "<td>$temp_typ</td>";
	echo "<td>$temp_sn</td>";
	echo "<td class=right>".correct_currency($temp_cenanetto)." zł</td>";
	echo "<td class=right>".correct_currency($temp_cenanettoodsp)." zł</td>";
	echo "<td class=center>$temp_pds</td>";
	echo "<td>$temp_dostawca4</td>";
	echo "<td>$temp_numer4</td>";
	echo "<td>$temp_data4</td>";
	echo "</tr>";
	$j+=1;
}

endtable();

include('body_stop.php');
?>
</body>
</html>