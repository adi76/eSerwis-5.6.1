<?php 
include_once('header.php');
include_once('cfg_eserwis.php');
include_once('cfg_helpdesk.php');

echo "<body>";

$sql1 = "SELECT * FROM $dbname_hd.hd_zgloszenie WHERE (zgl_nr IN (".$_GET[numery]."))";
//echo $sql1."<br />";

$result4 = mysql_query($sql1, $conn_hd) or die($k_b);

while ($newArray = mysql_fetch_array($result4)) {
	$temp_id  			= $newArray['zgl_id'];
	$temp_nr			= $newArray['zgl_nr'];
	
	// ustalenie ostatniego numeru kroku
	//echo "SELECT zgl_szcz_nr_kroku,zgl_szcz_unikalny_numer FROM $dbname_hd.hd_zgloszenie_szcz WHERE ((zgl_szcz_zgl_id='$temp_id') and (belongs_to='$es_filia') and (zgl_szcz_widoczne=1)) ORDER BY zgl_szcz_nr_kroku DESC LIMIT 1<br />";
	$r3 = mysql_query("SELECT zgl_szcz_nr_kroku,zgl_szcz_unikalny_numer,zgl_szcz_status,zgl_szcz_czas_rozpoczecia_kroku,zgl_szcz_czas_wykonywania FROM $dbname_hd.hd_zgloszenie_szcz WHERE ((zgl_szcz_zgl_id='$temp_id') and (belongs_to='$es_filia') and (zgl_szcz_widoczne=1)) ORDER BY zgl_szcz_nr_kroku DESC LIMIT 1", $conn_hd) or die($k_b);
	list($last_nr_kroku,$unique_number,$temp_zglszcz_status,$last_date1,$last_cw)=mysql_fetch_array($r3);

	$last_nr_kroku+=1;
	//$dddd=date("Y-m-d H:i:s");
	
	//$r3 = mysql_query("SELECT zgl_szcz_czas_rozpoczecia_kroku,zgl_szcz_czas_wykonywania FROM $dbname_hd.hd_zgloszenie_szcz WHERE ((zgl_szcz_zgl_id='$_REQUEST[id]') and (belongs_to='$es_filia') and (zgl_szcz_widoczne=1)) ORDER BY zgl_szcz_nr_kroku DESC LIMIT 1", $conn_hd) or die($k_b);
	//list($last_date1,$last_cw)=mysql_fetch_array($r3);
	
	$last_date = AddMinutesToDate($last_cw,$last_date1);
	$dddd = $last_date;
	
	//echo "SELECT zgl_kategoria FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id=$temp_id) LIMIT 1<br />";
	list($kategoria,$tmp_zgl_status)=mysql_fetch_array(mysql_query("SELECT zgl_kategoria, zgl_status FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id=$temp_id) LIMIT 1", $conn_hd));
	$osoba_przypisana=$currentuser;
	
	$dd = substr($dddd,0,8);
	$nowy_status = $temp_zglszcz_status;
	if ($temp_zglszcz_status=='1') $nowy_status = '2';
	
	//echo "UPDATE $dbname_hd.hd_zgloszenie SET zgl_status='2', zgl_osoba_przypisana='$osoba_przypisana' WHERE ((zgl_widoczne=1) and (belongs_to='$es_filia') and (zgl_id='$temp_id')) LIMIT 1<br />";
	$sql5 = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_status='$nowy_status', zgl_osoba_przypisana='$_GET[wo]', zgl_data_zmiany_statusu='$dd' WHERE ((zgl_widoczne=1) and (belongs_to='$es_filia') and (zgl_id='$temp_id')) LIMIT 1";
	$result5 = mysql_query($sql5, $conn_hd) or die($k_b);
	
	$CzasStartStop = 'START';
	if (($tmp_zgl_status=='3A') || ($tmp_zgl_status=='4')) $CzasStartStop='STOP';

	$przejechane_km = $_REQUEST[km];
	if (($_REQUEST[km]==0) || ($_REQUEST[km]=='')) $przejechane_km = 0;

	$ddddtt = Date('Y-m-d H:i');
	$awaria_z_przesunieciem=0;
	$komentarz = 'przypisanie do osoby <i>(przez: '.$currentuser.')</i>';
	
	list($czy_rozwiazany)=mysql_fetch_array(mysql_query("SELECT zgl_czy_rozwiazany_problem FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id=$temp_id) LIMIT 1", $conn_hd));
	
	$sql7 = "INSERT INTO $dbname_hd.hd_zgloszenie_szcz VALUES ('',$temp_id,'$unique_number',$last_nr_kroku,'$CzasStartStop',0,'$dddd','$nowy_status','$komentarz','$_GET[wo]','',0,0,0,0,'$dddd','$currentuser',1,$przejechane_km,$awaria_z_przesunieciem,'','','','','','1','$ddddtt',$czy_rozwiazany, 0,$es_filia)";	
	//echo $sql7."<br />";
	
	$result7 = mysql_query($sql7, $conn_hd) or die($k_b);
	// zaktualizuj zgl_czy_rozwiazany_problem w zg³oszeniu 
	$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_czy_rozwiazany_problem='$czy_rozwiazany' WHERE ((belongs_to='$es_filia') and (zgl_nr='$temp_id')) LIMIT 1";
	$result = mysql_query($sql, $conn_hd) or die($k_b);
	
}
?><script>if (opener) opener.location.reload(true); self.close();</script>
</body>
</html>