<?php include_once('header.php'); ?>
<body onUnload="if (opener) opener.location.reload(true);">
<?php include('body_start.php'); ?>
<?php
include('inc_encrypt.php');

$sql1="SELECT * FROM $dbname.serwis_faktury WHERE ((belongs_to=$es_filia) and (faktura_id='$id')) LIMIT 1";
$result1 = mysql_query($sql1, $conn) or die($k_b);
while ($newArray1 = mysql_fetch_array($result1)) {
	$temp_id1  			= $newArray1['faktura_id'];
	$temp_numer1		= $newArray1['faktura_numer'];
	$temp_data1			= $newArray1['faktura_data'];
	$temp_dostawca1		= $newArray1['faktura_dostawca'];
	$temp_koszty1		= $newArray1['faktura_koszty_dodatkowe'];	
	$temp_fnrz			= $newArray1['faktura_nr_zamowienia'];	
	$temp_statusf		= $newArray1['faktura_status'];
}

$sql1 = "SELECT * FROM $dbname.serwis_podfaktury WHERE pf_nr_faktury_id='$id'";
$result1 = mysql_query($sql1, $conn) or die($k_b);

if (mysql_num_rows($result1)!=0) {
   
   $newArray = mysql_fetch_array($result1);
   
	$temp_id  			= $newArray['pf_id'];
	$temp_nrfaktury		= $newArray['pf_nr_faktury_id'];
	$temp_numer			= $newArray['pf_numer'];
	$temp_data			= $newArray['pf_data'];
	$temp_dostawca		= $newArray['pf_dostawca_nazwa'];
	$temp_kwota_netto	= $newArray['pf_kwota_netto'];
	$temp_uwagi			= $newArray['pf_uwagi'];
	
	
	pageheader("Wykaz podfaktur do faktury");
	startbuttonsarea("center");
	echo "Numer faktury : <b>$temp_numer1</b><br />Data wystawienia : <b>$temp_data1</b><br />Firma wystawiająca fakturę : <b>$temp_dostawca1</b><br />Numer zamówienia : <b>$temp_fnrz</b>";
	endbuttonsarea();
	
// -
// access control 
$accessLevels = array("9");
if(array_search($es_prawa, $accessLevels)>-1){
	//if ($temp_koszty1!=0) { echo "<br /><br />Koszty dodatkowe : <b>$temp_koszty1 zł</b>";}
}
// access control koniec
	

	starttable();
	echo "<tr><th class=center>LP</th><th>Numer faktury</th><th>Data<br />wystwienia</th><th>Dostawca</th>";
	
// -
// access control 
$accessLevels = array("1","9");
if(array_search($es_prawa, $accessLevels)>-1){
	echo "<th class=right>Kwota<br />podfaktury</th>";
}
// access control koniec


	echo "<th class=center>Uwagi</th>";
	
	// -
	// access control 
	$accessLevels = array("9");
	if(array_search($es_prawa, $accessLevels)>-1){
		echo "<th>&nbsp;Opcje&nbsp;</th>";
	}
	// access control koniec
		
	
	echo "</tr>";

$sql1 = "SELECT * FROM $dbname.serwis_podfaktury WHERE pf_nr_faktury_id='$id'";
$result1 = mysql_query($sql1, $conn) or die($k_b);

$i = 0;
$j = 1;
$k = 0;
$koszty_decrtypted=0;
	
while ($newArray = mysql_fetch_array($result1)) {
	$temp_id  			= $newArray['pf_id'];
	$temp_nrfaktury		= $newArray['pf_nr_faktury_id'];
	$temp_numer			= $newArray['pf_numer'];
	$temp_data			= $newArray['pf_data'];
	$temp_dostawca		= $newArray['pf_dostawca_nazwa'];
	$temp_kwota_netto	= $newArray['pf_kwota_netto'];
	$temp_uwagi			= $newArray['pf_uwagi'];
	
	$koszty_decrtypted += decrypt_md5($temp_kwota_netto,$key);
	
	tbl_tr_highlight($i);

	echo "<td width=30 class=center>$j</td>";

	echo "<td>$temp_numer";
	if ($pokazpoczatekuwag==1) pokaz_uwagi($temp_uwagi,$iloscznakowuwag,"newWindow(480,330,'p_podfaktura_uwagi.php?id=$temp_id&nr=$temp_numer')");	
	echo "</td>";
	echo "<td width=70>$temp_data</td>"; 
	
	echo "<td>$temp_dostawca</td>";

// -
// access control 
$accessLevels = array("1","9");
if(array_search($es_prawa, $accessLevels)>-1){
	echo "<td class=right width=80>";
	echo correct_currency(decrypt_md5($temp_kwota_netto,$key));
	echo " zł</td>";
}
// access control koniec
	


	$j+=1;
	$i+=1;
	$k+=1;
	
	$uwagisa = ($temp_uwagi!='');

	if ($uwagisa=='1') {
	echo "<td width=50 class=center><a title=' Czytaj uwagi '><input class=imgoption type=image src=img/comment.gif  onclick=\"newWindow(480,330,'p_podfaktura_uwagi.php?id=$temp_id&nr=$temp_numer')\"></a>";
	
	} else {
	// -
	// access control 
	$accessLevels = array("9");
	if(array_search($es_prawa, $accessLevels)>-1){
		echo "<a title=' Edytuj uwagi '><input class=imgoption type=image src=img/edit_comment.gif onclick=\"newWindow(480,260,'e_podfaktura_edit.php?id=$temp_id&nr=$temp_numer')\"></a>";
	}
	// access control koniec
	// -
	}

	// -
	// access control 
	$accessLevels = array("9");
	if(array_search($es_prawa, $accessLevels)>-1){		
		echo "<td class=center width=50>&nbsp;<a title=' Edytuj podfakturę numer $temp_numer wystawioną przez $temp_dostawca '><input class=imgoption type=image src=img/edit.gif  onclick=\"newWindow(600,295,'e_podfaktura.php?id=$temp_id')\"></a>";

		echo "<a title=' Usuń podfakturę numer $temp_numer wystawioną przez $temp_dostawca '><input class=imgoption type=image src=img/delete.gif  onclick=\"newWindow(400,150,'u_podfaktura.php?id=$temp_id')\"></a>";
		echo "</td>";
	}
	// access control koniec
	
	
	echo "</tr>";
}

endtable();
startbuttonsarea("right");
echo "Łączne koszty dodatkowe : <b>".correct_currency($koszty_decrtypted)." zł</b>";
endbuttonsarea();

} else errorheader("Brak podfaktur dla faktury nr ".$temp_numer1."");

//}
	if ($submit99) {
		startbuttonsarea("right");
		addclosewithreloadbutton("'Zamknij'");
		endbuttonsarea();
	} else 
	{
		startbuttonsarea("right");
		addlinkbutton("'Dodaj nową podfakturę'","d_podfaktura.php?id=$id");
		addbuttons("zamknij");
		endbuttonsarea();	
	}

include('body_stop.php'); 

?>
</body>
</html>