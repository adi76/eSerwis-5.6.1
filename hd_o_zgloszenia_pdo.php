<?php 
include_once('header.php');
include_once('cfg_eserwis.php');
include_once('cfg_helpdesk.php');

echo "<body>";


if ($submit) {
	$_REQUEST=sanitize($_REQUEST);

	list($czy_rozwiazany,$tmp_zgl_status)=mysql_fetch_array(mysql_query("SELECT zgl_czy_rozwiazany_problem, zgl_status FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id=$_REQUEST[id]) LIMIT 1", $conn_hd));
	
// ustalenie ostatniego numeru kroku
	$r3 = mysql_query("SELECT zgl_szcz_nr_kroku,zgl_szcz_unikalny_numer,zgl_szcz_status FROM $dbname_hd.hd_zgloszenie_szcz WHERE ((zgl_szcz_zgl_id='$_REQUEST[id]') and (belongs_to='$es_filia') and (zgl_szcz_widoczne=1)) ORDER BY zgl_szcz_nr_kroku DESC LIMIT 1", $conn_hd) or die($k_b);
	list($last_nr_kroku,$unique_number,$temp_zglszcz_status)=mysql_fetch_array($r3);

	$last_nr_kroku+=1;
	//$dddd=date("Y-m-d H:i:s");
	$dddd = $_REQUEST[last_date];
	//$dd=date("Y-m-d");
	$dd = substr($dddd,0,10);
	
	list($kategoria)=mysql_fetch_array(mysql_query("SELECT zgl_kategoria FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id=$_REQUEST[id]) LIMIT 1", $conn_hd));

	$CzasStartStop = 'START';
	if (($tmp_zgl_status=='3A') || ($tmp_zgl_status=='4')) $CzasStartStop='STOP';
	
	$osoba_przypisana = $_REQUEST[pdo];

	$nowy_status = $temp_zglszcz_status;
	
	if ($temp_zglszcz_status=='1') $nowy_status = '2';
	
	$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_status='$nowy_status', zgl_osoba_przypisana='$osoba_przypisana', zgl_data_zmiany_statusu='$dd' WHERE ((zgl_widoczne=1) and (belongs_to='$es_filia') and (zgl_id='$_REQUEST[id]')) LIMIT 1";
	$result = mysql_query($sql, $conn_hd) or die($k_b);
	//echo $sql."<br />";
	$przejechane_km = 0;
//	$przejechane_km = $_REQUEST[km];
//	if (($_REQUEST[km]==0) || ($_REQUEST[km]=='')) $przejechane_km = 0;
	
	$komentarz = 'przypisanie do osoby <i>(przez: '.$currentuser.')</i>';
	if ($_POST[przypisz_uwagi]!='') $komentarz.='<hr><u>Informacja od osoby przekazującej zgłoszenie:</u><br />'.$_POST[przypisz_uwagi];
	
	$awaria_z_przesunieciem=0;
	$sql = "INSERT INTO $dbname_hd.hd_zgloszenie_szcz VALUES ('',$_REQUEST[id],'$unique_number',$last_nr_kroku,'$CzasStartStop',0,'$dddd','$nowy_status','$komentarz','$osoba_przypisana','',0,0,0,0,'$dddd','$currentuser',1,$przejechane_km,$awaria_z_przesunieciem,'','','','','','1','$_REQUEST[hdds]',$czy_rozwiazany, 0, $es_filia)";
	
if (mysql_query($sql, $conn_hd)) {
	
	$old_ww_on = $_REQUEST[old_wymaga_wyjazdu];
	
	$ww_on = 0;
	if ($_REQUEST[wymaga_wyjazdu]=='on') $ww_on = 1;
	
	if ($old_ww_on!=$ww_on) {
	
		$ww_data = Date("Y-m-d H:i:s");
		$ww_osoba = $currentuser;
	
		$sql66 = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_wymagany_wyjazd=$ww_on, zgl_wymagany_wyjazd_data_ustawienia='$ww_data', zgl_wymagany_wyjazd_osoba_wlaczajaca='$ww_osoba' WHERE (zgl_nr='$_REQUEST[id]') LIMIT 1";
	
		if (mysql_query($sql66, $conn_hd)) {
		}
		
	}
	
	// zaktualizuj zgl_czy_rozwiazany_problem w zgłoszeniu 
	$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_czy_rozwiazany_problem='$czy_rozwiazany' WHERE ((belongs_to='$es_filia') and (zgl_nr='$_REQUEST[id]')) LIMIT 1";
	$result = mysql_query($sql, $conn_hd) or die($k_b);
	
	$_SESSION['refresh_o_zgloszenia_'.$_REQUEST[id].'']=1;	
	?><script>SetCookie('byly_zmiany','1'); if (opener) opener.location.reload(true); self.close(); </script><?php
		//} else {
	?><?php
	}		
} else {

pageheader("Przypisanie osoby do zgłoszenia nr <b>$_GET[nr]</b>");
if ($_REQUEST[osoba]!='') infoheader("Aktualnie przypisana osoba: <b>".$_REQUEST[osoba]."</b>");
starttable();
echo "<form id=hd_pdo name=hd_pdo action=$PHP_SELF method=POST />";

$r3 = mysql_query("SELECT zgl_szcz_czas_rozpoczecia_kroku,zgl_szcz_czas_wykonywania FROM $dbname_hd.hd_zgloszenie_szcz WHERE ((zgl_szcz_zgl_id='$_REQUEST[id]') and (belongs_to='$es_filia') and (zgl_szcz_widoczne=1)) ORDER BY zgl_szcz_nr_kroku DESC LIMIT 1", $conn_hd) or die($k_b);
list($last_date1,$last_cw)=mysql_fetch_array($r3);
	
$r3 = mysql_query("SELECT zgl_wymagany_wyjazd,zgl_wymagany_wyjazd_data_ustawienia,zgl_wymagany_wyjazd_osoba_wlaczajaca FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id='$_REQUEST[id]') and (zgl_widoczne=1) LIMIT 1", $conn_hd) or die($k_b);
list($temp_czy_ww,$temp_czy_ww_data,$temp_czy_ww_osoba)=mysql_fetch_array($r3);
$last_date = AddMinutesToDate($last_cw,$last_date1);

include_once('systemdate.php');	

tbl_empty_row(2);
	tr_();
		td("150;r;Przypisz zgłoszenie do");
		td_(";;;");
			$result44 = mysql_query("SELECT user_first_name,user_last_name FROM $dbname.serwis_uzytkownicy WHERE (belongs_to=$es_filia) and (user_locked=0) and (CONCAT(user_first_name,' ',user_last_name)<>'$_REQUEST[osoba]') ORDER BY user_last_name ASC", $conn_hd) or die($k_b);
			$count_rows = mysql_num_rows($result44);
			echo "<select class=wymagane id=pdo name=pdo />\n";
			
			while (list($temp_imie,$temp_nazwisko) = mysql_fetch_array($result44)) {
				$imieinazwisko = $temp_imie . " " . $temp_nazwisko;
				echo "<option value='$imieinazwisko' ";
				if ($currentuser=='$imieinazwisko') echo " SELECTED";
				echo ">$imieinazwisko</option>\n"; 
			}
			echo "</select>\n";
		_td();
	_tr();
	tr_();
		td("150;rt;Wykonane czynności / uwagi");
		td_(";;;");
			echo "<textarea name=przypisz_uwagi id=przypisz_uwagi cols=55 rows=5></textarea>";				
		_td();
	_tr();	
	tr_();
		td("150;rt;");
		td_(";;;");
			echo "<input type=hidden name=old_wymaga_wyjazdu value='$temp_czy_ww'>";
			echo "<input class=border0 type=checkbox name=wymaga_wyjazdu id=wymaga_wyjazdu";
			if ($temp_czy_ww==1) { echo " checked=checked "; }
			echo "><a tabindex=-1 class=normalfont href=# onClick=\"if (document.getElementById('wymaga_wyjazdu').checked) { document.getElementById('wymaga_wyjazdu').checked=false; return false; } else { document.getElementById('wymaga_wyjazdu').checked=true; return false; }\"><font color=blue>&nbsp;Zgłoszenie wymaga wyjazdu</font></a>&nbsp;|&nbsp;";
			echo "<font color=blue>Ustawione w dniu <b>".substr($temp_czy_ww_data,0,16)."</b> przez <b>$temp_czy_ww_osoba</b></font>";
		_td();
	_tr();	
			
	tbl_empty_row(2);
endtable();

echo "<input type=hidden name=id value='$_GET[id]'>";
echo "<input type=hidden name=last_date value='$last_date'>";

startbuttonsarea("right");
echo "<input id=submit type=submit class=buttons name=submit value='Zapisz'>";
//echo "<input type=button class=buttons name=reset value=\"Wyczyść zgłoszenie\" onClick=\"pytanie_wyczysc('Wyczyścić formularz ?'); \" />";
echo "<input class=buttons id=anuluj type=button onClick=\"self.close();\" value=Anuluj>";
endbuttonsarea();

echo "</form>";
}
?>

</body>
</html>