<?php include_once('header_simple.php');include_once('cfg_helpdesk.php');
switch ($_GET[typ]) {

case 'mz_priorytet_rozpoczecia': $result11 = mysql_query("SELECT *, TIMEDIFF('".Date('Y-m-d H:i:s')."',zgl_data_rozpoczecia) as CzasDoRozpoczecia FROM helpdesk.hd_zgloszenie WHERE (hd_zgloszenie.belongs_to=$es_filia) and (hd_zgloszenie.zgl_widoczne=1) AND ((zgl_kategoria='2') OR (zgl_kategoria='6')) AND (zgl_status<>'9') AND (zgl_data_rozpoczecia!='0000-00-00 00:00:00') and ((zgl_status=1) or (zgl_status=2)) and (zgl_osoba_przypisana='$currentuser')", $conn_hd) or die($k_b);
$MZPriorytetRozpoczecia = mysql_num_rows($result11);

if ($MZPriorytetRozpoczecia==0) {	?><script>
document.getElementById("tr_mz_priorytet_rozpoczecia_na_startowej").style.display='none';
document.getElementById("mz_priorytet_rozpoczecia_na_startowej").innerHTML='';
</script><?php
} else { ?><script>
document.getElementById("tr_mz_priorytet_rozpoczecia_na_startowej").style.display='';
document.getElementById("mz_priorytet_rozpoczecia_na_startowej").innerHTML='<?php echo $MZPriorytetRozpoczecia; ?>';
</script><?php }	

break;

case 'mz_priorytet_zakonczenia': $result2 = mysql_query("SELECT *, TIMEDIFF('".Date('Y-m-d H:i:s')."',zgl_data_zakonczenia) as CzasDoZakonczenia FROM helpdesk.hd_zgloszenie WHERE (hd_zgloszenie.belongs_to=$es_filia) and (hd_zgloszenie.zgl_widoczne=1) AND ((zgl_kategoria='2') OR (zgl_kategoria='6')) AND (zgl_status<>'9') AND (zgl_data_zakonczenia!='0000-00-00 00:00:00') and ((zgl_status<>1) and (zgl_status<>2)) and (zgl_osoba_przypisana='$currentuser')", $conn_hd) or die($k_b);
$MZPriorytetZakonczenia = mysql_num_rows($result2);

if ($MZPriorytetZakonczenia==0) {	?><script>
document.getElementById("tr_mz_priorytet_zakonczenia_na_startowej").style.display='none';
document.getElementById("mz_priorytet_zakonczenia_na_startowej").innerHTML='';</script><?php
} else { ?><script>
document.getElementById("tr_mz_priorytet_zakonczenia_na_startowej").style.display='';
document.getElementById("mz_priorytet_zakonczenia_na_startowej").innerHTML='<?php echo $MZPriorytetZakonczenia; ?>';</script><?php }	

	break;
	
case 'wz_priorytet_rozpoczecia': $result3 = mysql_query("SELECT *, TIMEDIFF('".Date('Y-m-d H:i:s')."',zgl_data_rozpoczecia) as CzasDoRozpoczecia FROM helpdesk.hd_zgloszenie WHERE (hd_zgloszenie.belongs_to=$es_filia) and (hd_zgloszenie.zgl_widoczne=1) AND (zgl_kategoria='2') AND (zgl_status<>'9') AND (zgl_data_rozpoczecia!='0000-00-00 00:00:00') and ((zgl_status=1) or (zgl_status=2))", $conn_hd) or die($k_b);
$WZPriorytetRozpoczecia = mysql_num_rows($result3);

if ($WZPriorytetRozpoczecia==0) {	?><script>
document.getElementById("tr_wz_priorytet_rozpoczecia_na_startowej").style.display='none';
document.getElementById("wz_priorytet_rozpoczecia_na_startowej").innerHTML='';</script><?php
} else { ?><script>
document.getElementById("tr_wz_priorytet_rozpoczecia_na_startowej").style.display='';
document.getElementById("wz_priorytet_rozpoczecia_na_startowej").innerHTML='<?php echo $WZPriorytetRozpoczecia; ?>';
</script><?php }	

break;

case 'wz_priorytet_zakonczenia': $result4 = mysql_query("SELECT *, TIMEDIFF('".Date('Y-m-d H:i:s')."',zgl_data_zakonczenia) as CzasDoZakonczenia FROM helpdesk.hd_zgloszenie WHERE (hd_zgloszenie.belongs_to=$es_filia) and (hd_zgloszenie.zgl_widoczne=1) AND (zgl_kategoria='2') AND (zgl_status<>'9') AND (zgl_data_zakonczenia!='0000-00-00 00:00:00') and ((zgl_status<>1) and (zgl_status<>2))", $conn_hd) or die($k_b);
$WZPriorytetZakonczenia = mysql_num_rows($result4);

if ($WZPriorytetZakonczenia==0) {	?><script>
document.getElementById("tr_wz_priorytet_zakonczenia_na_startowej").style.display='none';
document.getElementById("wz_priorytet_zakonczenia_na_startowej").innerHTML='';</script><?php
} else { ?><script>
document.getElementById("tr_wz_priorytet_zakonczenia_na_startowej").style.display='';
document.getElementById("wz_priorytet_zakonczenia_na_startowej").innerHTML='<?php echo $WZPriorytetZakonczenia; ?>';</script><?php }	

	break;

case 'mz_p': if ($GetLicznikFrom=='zgloszenia') {
				list($PrzypisaneDoMnie)=mysql_fetch_array(mysql_query("SELECT COUNT(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_status='2') and (zgl_osoba_przypisana='$currentuser') and (zgl_widoczne=1) and (belongs_to=$es_filia) ", $conn_hd));
			} else {
				list($PrzypisaneDoMnie)=mysql_fetch_array(mysql_query("SELECT sum(statystyka_wartosc) FROM $dbname_hd.hd_statystyka WHERE (statystyka_filia=$es_filia) and (statystyka_status='2') and (statystyka_osoba='$currentuser')", $conn_hd));
			}
if ($PrzypisaneDoMnie==0) {	?><script>
document.getElementById("tr_mz_p_na_startowej").style.display='none';
document.getElementById("mz_p_na_startowej").innerHTML='';
</script><?php
} else { ?><script>
document.getElementById("tr_mz_p_na_startowej").style.display='';
document.getElementById("mz_p_na_startowej").innerHTML='<?php echo $PrzypisaneDoMnie; ?>';

</script><?php }		
break;

case 'mz_rnz': if ($GetLicznikFrom=='zgloszenia') {
				list($RozpoczeteNieZak)=mysql_fetch_array(mysql_query("SELECT COUNT(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_status='7') and (zgl_osoba_przypisana='$currentuser') and (zgl_widoczne=1) and (belongs_to=$es_filia) ", $conn_hd));
			} else {
				list($RozpoczeteNieZak)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status='7') and (zgl_osoba_przypisana='$currentuser') and (zgl_widoczne=1)", $conn_hd));
			}

if ($RozpoczeteNieZak==0) {	?><script>
document.getElementById("tr_mz_rnz_na_startowej").style.display='none';
document.getElementById("mz_rnz_na_startowej").innerHTML='';
</script><?php
} else { ?><script>
document.getElementById("tr_mz_rnz_na_startowej").style.display='';
document.getElementById("mz_rnz_na_startowej").innerHTML='<?php echo $RozpoczeteNieZak; ?>';

</script><?php }		
break;

	case 'mz_r': if ($GetLicznikFrom=='zgloszenia') {
					list($RozpoczetePrzezeMnie)=mysql_fetch_array(mysql_query("SELECT COUNT(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_status='3') and (zgl_osoba_przypisana='$currentuser') and (zgl_widoczne=1) and (belongs_to=$es_filia) ", $conn_hd));
			} else {
				list($RozpoczetePrzezeMnie)=mysql_fetch_array(mysql_query("SELECT sum(statystyka_wartosc) FROM $dbname_hd.hd_statystyka WHERE (statystyka_filia=$es_filia) and (statystyka_status='3') and (statystyka_osoba='$currentuser')", $conn_hd));
			}

if ($RozpoczetePrzezeMnie==0) {	?><script>
document.getElementById("tr_mz_r_na_startowej").style.display='none';
document.getElementById("mz_r_na_startowej").innerHTML='';</script><?php
} else { ?><script>
document.getElementById("tr_mz_r_na_startowej").style.display='';
document.getElementById("mz_r_na_startowej").innerHTML='<?php echo $RozpoczetePrzezeMnie; ?>';</script><?php }		
break;

	case 'mz_ws': if ($GetLicznikFrom=='zgloszenia') {
					list($MojeWFirmie)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status='3A') and (zgl_osoba_przypisana='$currentuser') and (zgl_widoczne=1)", $conn_hd));
			} else {
				list($MojeWSerwisie)=mysql_fetch_array(mysql_query("SELECT sum(statystyka_wartosc) FROM $dbname_hd.hd_statystyka WHERE (statystyka_filia=$es_filia) and (statystyka_status='3A') and (statystyka_osoba='$currentuser')", $conn_hd));
			}

if ($MojeWSerwisie==0) {	?><script>
document.getElementById("tr_mz_ws_na_startowej").style.display='none';
document.getElementById("mz_ws_na_startowej").innerHTML='';</script><?php
} else { ?><script>
document.getElementById("tr_mz_ws_na_startowej").style.display='';
document.getElementById("mz_ws_na_startowej").innerHTML='<?php echo $MojeWSerwisie; ?>';</script><?php }		
break;

	case 'mz_nz': if ($GetLicznikFrom=='zgloszenia') {
					list($NieZakonczonePrzezeMnie)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status<>'9') and (zgl_osoba_przypisana='$currentuser') and (zgl_widoczne=1)", $conn_hd));
			} else {
				list($NieZakonczonePrzezeMnie)=mysql_fetch_array(mysql_query("SELECT sum(statystyka_wartosc) FROM $dbname_hd.hd_statystyka WHERE (statystyka_filia=$es_filia) and (statystyka_status<>'9') and (statystyka_osoba='$currentuser')", $conn_hd));
			}

if ($NieZakonczonePrzezeMnie==0) {	?><script>
document.getElementById("tr_mz_nz_na_startowej").style.display='none';
document.getElementById("mz_nz_na_startowej").innerHTML='';</script><?php
} else { ?><script>
document.getElementById("tr_mz_nz_na_startowej").style.display='';
document.getElementById("mz_nz_na_startowej").innerHTML='<?php echo $NieZakonczonePrzezeMnie; ?>';</script><?php }		
break;		

	case 'mz_z': if ($GetLicznikFrom=='zgloszenia') {
					list($ZakonczonePrzezeMnie)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status='9') and (zgl_osoba_przypisana='$currentuser') and (zgl_widoczne=1)", $conn_hd));
			} else {
				list($ZakonczonePrzezeMnie)=mysql_fetch_array(mysql_query("SELECT sum(statystyka_wartosc) FROM $dbname_hd.hd_statystyka WHERE (statystyka_filia=$es_filia) and (statystyka_status='9') and (statystyka_osoba='$currentuser')", $conn_hd));
			}

if ($ZakonczonePrzezeMnie==0) {	?><script>
document.getElementById("tr_mz_zak_na_startowej").style.display='none';
document.getElementById("mz_zak_na_startowej").innerHTML='';</script><?php
} else { ?><script>
document.getElementById("tr_mz_z_na_startowej").style.display='';
document.getElementById("mz_z_na_startowej").innerHTML='<?php echo $ZakonczonePrzezeMnie; ?>';</script><?php }		
break;		

	case 'mz_ak': if ($GetLicznikFrom=='zgloszenia') {
					list($MojeAwarieKrytyczne)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status<>'9') and (zgl_kategoria='6') and (zgl_osoba_przypisana='$currentuser') and (zgl_widoczne=1)", $conn_hd));
					
			} else {
				list($MojeAwarieKrytyczne)=mysql_fetch_array(mysql_query("SELECT sum(statystyka_wartosc) FROM $dbname_hd.hd_statystyka WHERE (statystyka_filia=$es_filia) and (statystyka_kategoria='6') and (statystyka_osoba='$currentuser')", $conn_hd));
			}

if ($MojeAwarieKrytyczne==0) {	?><script>
document.getElementById("tr_mz_ak_na_startowej").style.display='none';
document.getElementById("mz_ak_na_startowej").innerHTML='';</script><?php
} else { ?><script>
document.getElementById("tr_mz_ak_na_startowej").style.display='';
document.getElementById("mz_ak_na_startowej").innerHTML='<?php echo $MojeAwarieKrytyczne; ?>';</script><?php }		
break;	

	case 'mz_az': if ($GetLicznikFrom=='zgloszenia') {
					list($MojeAwarieZwykle)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status<>'9') and (zgl_kategoria='2') and (zgl_osoba_przypisana='$currentuser') and (zgl_widoczne=1)", $conn_hd));
			} else {
				list($MojeAwarieZwykle)=mysql_fetch_array(mysql_query("SELECT sum(statystyka_wartosc) FROM $dbname_hd.hd_statystyka WHERE (statystyka_filia=$es_filia) and (statystyka_kategoria='2') and (statystyka_osoba='$currentuser')", $conn_hd));
			}

if ($MojeAwarieZwykle==0) {	?><script>
document.getElementById("tr_mz_az_na_startowej").style.display='none';
document.getElementById("mz_az_na_startowej").innerHTML='';</script><?php
} else { ?><script>
document.getElementById("tr_mz_az_na_startowej").style.display='';
document.getElementById("mz_az_na_startowej").innerHTML='<?php echo $MojeAwarieZwykle; ?>';</script><?php }		
break;	
/*
	case 'mz_pzwrupw': if ($GetLicznikFrom=='zgloszenia') {
					list($MojePraceZleconeWRamachUmowy)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status<>'9') and (zgl_kategoria='3') and (zgl_osoba_przypisana='$currentuser') and (zgl_widoczne=1)", $conn_hd));
			} else {
				list($MojePraceZleconeWRamachUmowy)=mysql_fetch_array(mysql_query("SELECT sum(statystyka_wartosc) FROM $dbname_hd.hd_statystyka WHERE (statystyka_filia=$es_filia) and (statystyka_status='2') and (statystyka_kategoria='3') and (statystyka_priorytet='3') and (statystyka_osoba='$currentuser')", $conn_hd));
			}

if ($MojePraceZleconeWRamachUmowy==0) {	?><script>
document.getElementById("tr_mz_pzwrupw_na_startowej").style.display='none';
document.getElementById("mz_pzwrupw_na_startowej").innerHTML='';</script><?php
} else { ?><script>
document.getElementById("tr_mz_pzwrupw_na_startowej").style.display='';
document.getElementById("mz_pzwrupw_na_startowej").innerHTML='<?php echo $MojePraceZleconeWRamachUmowy; ?>';</script><?php }		
break;	
*/
	case 'wz_n': list($WszystkieNowe)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status='1') and (zgl_widoczne=1)", $conn_hd));

if ($WszystkieNowe==0) {	?><script>
document.getElementById("tr_wz_n_na_startowej").style.display='';
document.getElementById("wz_n_na_startowej").innerHTML='0';</script><?php
} else { ?><script>
document.getElementById("tr_wz_n_na_startowej").style.display='';
document.getElementById("wz_n_na_startowej").innerHTML='<?php echo $WszystkieNowe; ?>';</script><?php }		
break;

case 'wz_rnz': list($WszRozpoczeteNieZak)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status='7') and (zgl_widoczne=1)", $conn_hd));

if ($WszRozpoczeteNieZak==0) {	?><script>
document.getElementById("tr_wz_rnz_na_startowej").style.display='none';
document.getElementById("wz_rnz_na_startowej").innerHTML='';
</script><?php
} else { ?><script>
document.getElementById("tr_wz_rnz_na_startowej").style.display='';
document.getElementById("wz_rnz_na_startowej").innerHTML='<?php echo $WszRozpoczeteNieZak; ?>';

</script><?php }		
break;

	case 'wz_p': if ($GetLicznikFrom=='zgloszenia') {
					list($WszystkiePrzypisane)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status='2') and (zgl_widoczne=1)", $conn_hd));
			} else {
				list($WszystkiePrzypisane)=mysql_fetch_array(mysql_query("SELECT sum(statystyka_wartosc) FROM $dbname_hd.hd_statystyka WHERE (statystyka_filia=$es_filia) and (statystyka_status='2')", $conn_hd));
			}

if ($WszystkiePrzypisane==0) {	?><script>
document.getElementById("tr_wz_p_na_startowej").style.display='none';
document.getElementById("wz_p_na_startowej").innerHTML='';</script><?php
} else { ?><script>
document.getElementById("tr_wz_p_na_startowej").style.display='';
document.getElementById("wz_p_na_startowej").innerHTML='<?php echo $WszystkiePrzypisane; ?>';</script><?php }		
break;	

	case 'wz_r': if ($GetLicznikFrom=='zgloszenia') {
					list($WszystkieRozpoczete)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status='3') and (zgl_widoczne=1)", $conn_hd));
			} else {
				list($WszystkieRozpoczete)=mysql_fetch_array(mysql_query("SELECT sum(statystyka_wartosc) FROM $dbname_hd.hd_statystyka WHERE (statystyka_filia=$es_filia) and (statystyka_status='3')", $conn_hd));
			}

if ($WszystkieRozpoczete==0) {	?><script>
document.getElementById("tr_wz_r_na_startowej").style.display='none';
document.getElementById("wz_r_na_startowej").innerHTML='';</script><?php
} else { ?><script>
document.getElementById("tr_wz_r_na_startowej").style.display='';
document.getElementById("wz_r_na_startowej").innerHTML='<?php echo $WszystkieRozpoczete; ?>';</script><?php }		
break;	

	case 'wz_ws': if ($GetLicznikFrom=='zgloszenia') {
					list($WszystkieWSerwisie)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status='3A') and (zgl_widoczne=1)", $conn_hd));
			} else {
				list($WszystkieWSerwisie)=mysql_fetch_array(mysql_query("SELECT sum(statystyka_wartosc) FROM $dbname_hd.hd_statystyka WHERE (statystyka_filia=$es_filia) and (statystyka_status='3A')", $conn_hd));
			}

if ($WszystkieWSerwisie==0) {	?><script>
document.getElementById("tr_wz_ws_na_startowej").style.display='none';
document.getElementById("wz_ws_na_startowej").innerHTML='';</script><?php
} else { ?><script>
document.getElementById("tr_wz_ws_na_startowej").style.display='';
document.getElementById("wz_ws_na_startowej").innerHTML='<?php echo $WszystkieWSerwisie; ?>';</script><?php }		
break;

	case 'wz_nz': if ($GetLicznikFrom=='zgloszenia') {
					list($WszystkieNieZamkniete)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status<>'9') and (zgl_widoczne=1)", $conn_hd));
			} else {
				list($WszystkieNieZamkniete)=mysql_fetch_array(mysql_query("SELECT sum(statystyka_wartosc) FROM $dbname_hd.hd_statystyka WHERE (statystyka_filia=$es_filia) and (statystyka_status<>'9')", $conn_hd));
			}
			
if ($WszystkieNieZamkniete==0) {	?><script>
document.getElementById("tr_wz_nz_na_startowej").style.display='none';
document.getElementById("wz_nz_na_startowej").innerHTML='';</script><?php
} else { ?><script>
document.getElementById("tr_wz_nz_na_startowej").style.display='';
document.getElementById("wz_nz_na_startowej").innerHTML='<?php echo $WszystkieNieZamkniete; ?>';</script><?php }		
break;	
		
	case 'wz_z': if ($GetLicznikFrom=='zgloszenia') {
					list($WszystkieZamkniete)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status='9') and (zgl_widoczne=1)", $conn_hd));
			} else {
				list($WszystkieZamkniete)=mysql_fetch_array(mysql_query("SELECT sum(statystyka_wartosc) FROM $dbname_hd.hd_statystyka WHERE (statystyka_filia=$es_filia) and (statystyka_status='9')", $conn_hd));
			}

if ($WszystkieZamkniete==0) {	?><script>
document.getElementById("tr_wz_z_na_startowej").style.display='none';
document.getElementById("wz_z_na_startowej").innerHTML='';</script><?php
} else { ?><script>
document.getElementById("tr_wz_z_na_startowej").style.display='';
document.getElementById("wz_z_na_startowej").innerHTML='<?php echo $WszystkieZamkniete; ?>';</script><?php }		
break;	

	case 'wz_w': if ($GetLicznikFrom=='zgloszenia') {
					list($WszystkieZgloszenia)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and  (zgl_widoczne=1)", $conn_hd));
			} else {
				list($WszystkieZgloszenia)=mysql_fetch_array(mysql_query("SELECT sum(statystyka_wartosc) FROM $dbname_hd.hd_statystyka WHERE (statystyka_filia=$es_filia)", $conn_hd));
			}

if ($WszystkieZgloszenia==0) {	?><script>
document.getElementById("tr_wz_w_na_startowej").style.display='none';
document.getElementById("wz_w_na_startowej").innerHTML='';
document.getElementById("th_wz_na_startowej").style.display='none';
</script><?php
} else { ?><script>
document.getElementById("tr_wz_w_na_startowej").style.display='';
document.getElementById("th_wz_na_startowej").style.display='';
document.getElementById("wz_w_na_startowej").innerHTML='<?php echo $WszystkieZgloszenia; ?>';</script><?php }	
break;		

	case 'wz_ak':  if ($GetLicznikFrom=='zgloszenia') {
					list($WszystkieAwarieKrytyczne)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_kategoria='6') and (zgl_status<>'9') and (zgl_widoczne=1)", $conn_hd));
			} else {
				list($WszystkieAwarieKrytyczne)=mysql_fetch_array(mysql_query("SELECT sum(statystyka_wartosc) FROM $dbname_hd.hd_statystyka WHERE (statystyka_filia=$es_filia) and (statystyka_status<>'9') and (statystyka_kategoria='6')", $conn_hd));
			}

if ($WszystkieAwarieKrytyczne==0) {	?><script>
document.getElementById("tr_wz_ak_na_startowej").style.display='none';
document.getElementById("wz_ak_na_startowej").innerHTML='';</script><?php
} else { ?><script>
document.getElementById("tr_wz_ak_na_startowej").style.display='';
document.getElementById("wz_ak_na_startowej").innerHTML='<?php echo $WszystkieAwarieKrytyczne; ?>';</script><?php }		
break;	

	case 'wz_az': if ($GetLicznikFrom=='zgloszenia') {
					list($WszystkieAwarieZwykle)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_kategoria='2') and (zgl_status<>'9') and (zgl_widoczne=1)", $conn_hd));
			} else {
				list($WszystkieAwarieZwykle)=mysql_fetch_array(mysql_query("SELECT sum(statystyka_wartosc) FROM $dbname_hd.hd_statystyka WHERE (statystyka_filia=$es_filia) and (statystyka_status<>'9') and (statystyka_kategoria='2')", $conn_hd));
			}			

if ($WszystkieAwarieZwykle==0) {	?><script>
document.getElementById("tr_wz_az_na_startowej").style.display='none';
document.getElementById("wz_az_na_startowej").innerHTML='';</script><?php
} else { ?><script>
document.getElementById("tr_wz_az_na_startowej").style.display='';
document.getElementById("wz_az_na_startowej").innerHTML='<?php echo $WszystkieAwarieZwykle; ?>';</script><?php }		
break;	

/*
	case 'wz_pzwrupw': if ($GetLicznikFrom=='zgloszenia') {
					list($WszystkiePraceZleconeWRamachUmowy)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_kategoria='3') and (zgl_status<>'9') and (zgl_widoczne=1)", $conn_hd));
			} else {
				list($WszystkiePraceZleconeWRamachUmowy)=mysql_fetch_array(mysql_query("SELECT sum(statystyka_wartosc) FROM $dbname_hd.hd_statystyka WHERE (statystyka_filia=$es_filia) and (statystyka_status<>'9') and (statystyka_kategoria='3')", $conn_hd));
			}
			
if ($WszystkiePraceZleconeWRamachUmowy==0) {	?><script>
document.getElementById("tr_wz_pzwrupw_na_startowej").style.display='none';
document.getElementById("wz_pzwrupw_na_startowej").innerHTML='';</script><?php
} else { ?><script>
document.getElementById("tr_wz_pzwrupw_na_startowej").style.display='';
document.getElementById("wz_pzwrupw_na_startowej").innerHTML='<?php echo $WszystkiePraceZleconeWRamachUmowy; ?>';</script><?php }		
break;	
*/
}
?>
<script>

if (	
	(document.getElementById('tr_mz_p_na_startowej').style.display=='none') && 
	(document.getElementById('tr_mz_r_na_startowej').style.display=='none') && 
	(document.getElementById('tr_mz_ws_na_startowej').style.display=='none') && 
	(document.getElementById('tr_mz_nz_na_startowej').style.display=='none') && 
	(document.getElementById('tr_mz_ak_na_startowej').style.display=='none') && 
	(document.getElementById('tr_mz_az_na_startowej').style.display=='none') && 
	//(document.getElementById('tr_mz_pzwrupw_na_startowej').style.display=='none') && 
	(document.getElementById('tr_mz_rnz_na_startowej').style.display=='none') && 
	(document.getElementById('tr_mz_priorytet_rozpoczecia_na_startowej').style.display=='none') && 
	(document.getElementById('tr_mz_priorytet_zakonczenia_na_startowej').style.display=='none')
	) 
	{
	
		document.getElementById('mz').style.display='none';
	
	} else {
		document.getElementById('mz').style.display='';
	}
</script>
