<?php 
include_once('header.php');
include_once('cfg_eserwis.php');
include_once('cfg_helpdesk.php');

//$sql="UPDATE helpdesk.hd_zgloszenie_szcz SET zgl_szcz_osoba_wykonujaca_krok = '$_GET[osoba]' WHERE zgl_szcz_zgl_id=$_GET[id] LIMIT 1";

// ustalenie ostatniego numeru kroku
	$r3 = mysql_query("SELECT zgl_szcz_czas_rozpoczecia_kroku,zgl_szcz_czas_wykonywania FROM $dbname_hd.hd_zgloszenie_szcz WHERE ((zgl_szcz_zgl_id='$_REQUEST[id]') and (belongs_to='$es_filia') and (zgl_szcz_widoczne=1)) ORDER BY zgl_szcz_nr_kroku DESC LIMIT 1", $conn_hd) or die($k_b);
	list($last_date1,$last_cw)=mysql_fetch_array($r3);	
	$last_date = AddMinutesToDate($last_cw,$last_date1);
	
	$r3 = mysql_query("SELECT zgl_szcz_nr_kroku,zgl_szcz_unikalny_numer,zgl_szcz_status FROM $dbname_hd.hd_zgloszenie_szcz WHERE ((zgl_szcz_zgl_id='$_REQUEST[id]') and (belongs_to='$es_filia') and (zgl_szcz_widoczne=1)) ORDER BY zgl_szcz_nr_kroku DESC LIMIT 1", $conn_hd) or die($k_b);
	list($last_nr_kroku,$unique_number,$temp_zglszcz_status)=mysql_fetch_array($r3);

	$last_nr_kroku+=1;
	$dddd=date("Y-m-d H:i:s");
	$dd=date("Y-m-d");
	
	list($kategoria,$tmp_zgl_status)=mysql_fetch_array(mysql_query("SELECT zgl_kategoria, zgl_status FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id=$_GET[id]) LIMIT 1", $conn_hd));
	
	//$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_status='2' WHERE ((zgl_widoczne=1) and (belongs_to='$es_filia') and (zgl_id='$_GET[id]')) LIMIT 1";
	//$result = mysql_query($sql, $conn_hd) or die($k_b);
	
	$CzasStartStop='START';
	if (($tmp_zgl_status=='3A') || ($tmp_zgl_status=='4')) $CzasStartStop='STOP';
	
	$osoba_przypisana = '';
	if ($_GET[osoba]!='') { $osoba_przypisana=$_GET[osoba]; } else { $osoba_przypisana=$currentuser; }
	
	$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_osoba_przypisana='$osoba_przypisana', zgl_data_zmiany_statusu='$dd' WHERE ((zgl_widoczne=1) and (belongs_to='$es_filia') and (zgl_id='$_GET[id]')) LIMIT 1";
	$result = mysql_query($sql, $conn_hd) or die($k_b);

	$przejechane_km = 0;
//	$przejechane_km = $_REQUEST[km];
//	if (($_REQUEST[km]==0) || ($_REQUEST[km]=='')) $przejechane_km = 0;
	
	$awaria_z_przesunieciem=0;
	$ddddtt = Date('Y-m-d H:i');

	list($czy_rozwiazany)=mysql_fetch_array(mysql_query("SELECT zgl_czy_rozwiazany_problem FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id=$_REQUEST[id]) LIMIT 1", $conn_hd));
	
	$nowy_status = $temp_zglszcz_status;	
	if ($temp_zglszcz_status=='1') $nowy_status = '2';	
	$komentarz = 'przypisanie do osoby <i>(przez: '.$currentuser.')</i>';
	
	$sql = "INSERT INTO $dbname_hd.hd_zgloszenie_szcz VALUES ('',$_REQUEST[id],'$unique_number',$last_nr_kroku,'$CzasStartStop',0,'$last_date','$nowy_status','$komentarz','$osoba_przypisana','',0,0,0,0,'$dddd','$currentuser',1,$przejechane_km,$awaria_z_przesunieciem,'','','','','','','$ddddtt',$czy_rozwiazany,0,$es_filia)";

	//echo $sql;
	
//$sql="UPDATE $dbname_hd.hd_zgloszenie_szcz SET zgl_szcz_osoba_wykonujaca_krok = '$_GET[osoba]' WHERE zgl_szcz_zgl_id=$_GET[id] LIMIT 1";

if (mysql_query($sql, $conn_hd)) {

	// zaktualizuj zgl_czy_rozwiazany_problem w zgłoszeniu 
	$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_czy_rozwiazany_problem='$czy_rozwiazany' WHERE ((belongs_to='$es_filia') and (zgl_nr='$_REQUEST[id]')) LIMIT 1";
	$result = mysql_query($sql, $conn_hd) or die($k_b);

	if ($_REQUEST[refresh]=='') {
	?>
		<script>
			SetCookie('byly_zmiany','1'); 
			window.location.reload(); 
			document.getElementById("pds_button").style.display='none';
			document.getElementById("pds_button1").style.display='none';  
		</script>
	<?php
		echo "$_GET[osoba]";		
	}
	}
?>

<?php if ($_REQUEST[refresh]=='1') { ?>
<script>
if (opener) opener.location.reload(true);
self.close();
</script>
<?php } ?>