<?php 
include_once('header_simple.php');
include_once('cfg_helpdesk.php');

switch ($_GET[typ]) {

case 'mz_priorytet_rozpoczecia': 
	
//	if (($_GET[on_load]=='1') && (isset($_COOKIE['mz_priorytet_rozpoczecia']))) {
//		$MZPriorytetRozpoczecia = $_COOKIE['mz_priorytet_rozpoczecia'];
//	} else {
		$result11 = mysql_query("SELECT *, TIMEDIFF('".Date('Y-m-d H:i:s')."',zgl_data_rozpoczecia) as CzasDoRozpoczecia FROM helpdesk.hd_zgloszenie WHERE (hd_zgloszenie.belongs_to=$es_filia) and (hd_zgloszenie.zgl_widoczne=1) AND ((zgl_kategoria='2') OR (zgl_kategoria='6')) AND (zgl_status<>'9') AND (zgl_data_rozpoczecia!='0000-00-00 00:00:00') and ((zgl_status=1) or (zgl_status=2)) and (zgl_osoba_przypisana='$currentuser')", $conn_hd) or die($k_b);
		$MZPriorytetRozpoczecia = mysql_num_rows($result11);
//	}

	if ($MZPriorytetRozpoczecia==0) { ?>
		<script>
			document.getElementById("tr_mz_priorytet_rozpoczecia").style.display='none';
			document.getElementById("mz_priorytet_rozpoczecia").innerHTML='';
		</script>
	<?php } else { ?>
		<script>
			document.getElementById("tr_mz_priorytet_rozpoczecia").style.display=document.getElementById('ukryj_hd_moje').style.display;
			document.getElementById("mz_priorytet_rozpoczecia").innerHTML='<?php echo $MZPriorytetRozpoczecia; ?>';
		</script>
	<?php }	
	setcookie('mz_priorytet_rozpoczecia',$MZPriorytetRozpoczecia);
	break;

	
case 'mz_priorytet_zakonczenia': 

//	if (($_GET[on_load]=='1') && (isset($_COOKIE['mz_priorytet_zakonczenia']))) {
//		$MZPriorytetZakonczenia = $_COOKIE['mz_priorytet_zakonczenia'];
//	} else {
		$result2 = mysql_query("SELECT *, TIMEDIFF('".Date('Y-m-d H:i:s')."',zgl_data_zakonczenia) as CzasDoZakonczenia FROM helpdesk.hd_zgloszenie WHERE (hd_zgloszenie.belongs_to=$es_filia) and (hd_zgloszenie.zgl_widoczne=1) AND ((zgl_kategoria='2') OR (zgl_kategoria='6')) AND (zgl_status<>'9') AND (zgl_data_zakonczenia!='0000-00-00 00:00:00') and ((zgl_status<>1) and (zgl_status<>2)) and (zgl_osoba_przypisana='$currentuser')", $conn_hd) or die($k_b);
		$MZPriorytetZakonczenia = mysql_num_rows($result2);
	//}

	if ($MZPriorytetZakonczenia==0) { ?>
	<script>
		document.getElementById("tr_mz_priorytet_zakonczenia").style.display='none';
		document.getElementById("mz_priorytet_zakonczenia").innerHTML='';
	</script>
	<?php } else { ?>
	<script>
		document.getElementById("tr_mz_priorytet_zakonczenia").style.display=document.getElementById('ukryj_hd_moje').style.display;
		document.getElementById("mz_priorytet_zakonczenia").innerHTML='<?php echo $MZPriorytetZakonczenia; ?>';
	</script>
	<?php }	
	setcookie('mz_priorytet_zakonczenia',$MZPriorytetZakonczenia);
	break;
	
case 'wz_priorytet_rozpoczecia': 
	
//	if (($_GET[on_load]=='1') && (isset($_COOKIE['wz_priorytet_rozpoczecia']))) {
//		$WZPriorytetRozpoczecia = $_COOKIE['wz_priorytet_rozpoczecia'];
//	} else {
		$result3 = mysql_query("SELECT *, TIMEDIFF('".Date('Y-m-d H:i:s')."',zgl_data_rozpoczecia) as CzasDoRozpoczecia FROM helpdesk.hd_zgloszenie WHERE (hd_zgloszenie.belongs_to=$es_filia) and (hd_zgloszenie.zgl_widoczne=1) AND ((zgl_kategoria='2') OR (zgl_kategoria='6')) AND (zgl_status<>'9') AND (zgl_data_rozpoczecia!='0000-00-00 00:00:00') and ((zgl_status=1) or (zgl_status=2))", $conn_hd) or die($k_b);
		$WZPriorytetRozpoczecia = mysql_num_rows($result3);
//	}

	if ($WZPriorytetRozpoczecia==0) {	?>
	<script>
		document.getElementById("tr_wz_priorytet_rozpoczecia").style.display='none';
		document.getElementById("wz_priorytet_rozpoczecia").innerHTML='';
	</script>
	<?php } else { ?>
	<script>
		document.getElementById("tr_wz_priorytet_rozpoczecia").style.display=document.getElementById('ukryj_hd_wszystkie').style.display;
		document.getElementById("wz_priorytet_rozpoczecia").innerHTML='<?php echo $WZPriorytetRozpoczecia; ?>';
	</script>
	<?php }	
	setcookie('wz_priorytet_rozpoczecia',$WZPriorytetRozpoczecia);
	break;

	
case 'wz_priorytet_zakonczenia': 
	
//	if (($_GET[on_load]=='1') && (isset($_COOKIE['wz_priorytet_zakonczenia']))) {
//		$WZPriorytetZakonczenia = $_COOKIE['wz_priorytet_zakonczenia'];		
//		$WZPriorytetZakonczenia = 1;
//	} else {
		$result4 = mysql_query("SELECT *, TIMEDIFF('".Date('Y-m-d H:i:s')."',zgl_data_zakonczenia) as CzasDoZakonczenia FROM helpdesk.hd_zgloszenie WHERE (hd_zgloszenie.belongs_to=$es_filia) and (hd_zgloszenie.zgl_widoczne=1) AND ((zgl_kategoria='2') OR (zgl_kategoria='6')) AND (zgl_status<>'9') AND (zgl_data_zakonczenia!='0000-00-00 00:00:00') and ((zgl_status<>1) and (zgl_status<>2))", $conn_hd) or die($k_b);
		$WZPriorytetZakonczenia = mysql_num_rows($result4);
//	}
	
	if ($WZPriorytetZakonczenia==0) { ?>
	<script>
		document.getElementById("tr_wz_priorytet_zakonczenia").style.display='none';
		document.getElementById("wz_priorytet_zakonczenia").innerHTML='';
	</script>
	<?php } else { ?>
	<script>
		document.getElementById("tr_wz_priorytet_zakonczenia").style.display=document.getElementById('ukryj_hd_wszystkie').style.display;
		document.getElementById("wz_priorytet_zakonczenia").innerHTML='<?php echo $WZPriorytetZakonczenia; ?>';
	</script>
	<?php }	
	setcookie('wz_priorytet_zakonczenia',$WZPriorytetZakonczenia);
	break;

	
case 'mz_p': 

//	if (($_GET[on_load]=='1') && (isset($_COOKIE['mz_p']))) {
////		$PrzypisaneDoMnie = $_COOKIE['mz_p'];
//	} else {
		if ($GetLicznikFrom=='zgloszenia') {
			list($PrzypisaneDoMnie)=mysql_fetch_array(mysql_query("SELECT COUNT(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_status='2') and (zgl_osoba_przypisana='$currentuser') and (zgl_widoczne=1) and (belongs_to=$es_filia) ", $conn_hd));
		} else {
			list($PrzypisaneDoMnie)=mysql_fetch_array(mysql_query("SELECT sum(statystyka_wartosc) FROM $dbname_hd.hd_statystyka WHERE (statystyka_filia=$es_filia) and (statystyka_status='2') and (statystyka_osoba='$currentuser') ", $conn_hd));
		}
//	}
	
	if ($PrzypisaneDoMnie==0) {	?>
	<script>
		document.getElementById("tr_mz_p").style.display='none';
		document.getElementById("mz_p").innerHTML='';
	</script>
	<?php } else { ?>
	<script>
		document.getElementById("tr_mz_p").style.display=document.getElementById('ukryj_hd_moje').style.display;
		document.getElementById("mz_p").innerHTML='<?php echo $PrzypisaneDoMnie; ?>';
	</script>
	<?php }
	setcookie('mz_p',$PrzypisaneDoMnie);
	break;

	
case 'mz_rnz': 

//	if (($_GET[on_load]=='1') && (isset($_COOKIE['mz_rnz']))) {
//		$RozpoczeteNieZakonczone = $_COOKIE['mz_rnz'];
//	} else {
	
		if ($GetLicznikFrom=='zgloszenia') {
			list($RozpoczeteNieZakonczone)=mysql_fetch_array(mysql_query("SELECT COUNT(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_status='7') and (zgl_osoba_przypisana='$currentuser') and (zgl_widoczne=1) and (belongs_to=$es_filia) ", $conn_hd));
		} else {
			list($RozpoczeteNieZakonczone)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status='7') and (zgl_osoba_przypisana='$currentuser') and (zgl_widoczne=1) ", $conn_hd));
		}
		
//	}
	if ($RozpoczeteNieZakonczone==0) {	?>
	<script>
		document.getElementById("tr_mz_rnz").style.display='none';
		document.getElementById("mz_rnz").innerHTML='';
	</script>
	<?php } else { ?>
	<script>
		document.getElementById("tr_mz_rnz").style.display=document.getElementById('ukryj_hd_moje').style.display;
		document.getElementById("mz_rnz").innerHTML='<?php echo $RozpoczeteNieZakonczone; ?>';
	</script>
	<?php }
	setcookie('mz_rnz',$RozpoczeteNieZakonczone);
	break;

	
case 'wz_rnz': 

//	if (($_GET[on_load]=='1') && (isset($_COOKIE['wz_rnz']))) {
//		$WszystkieRozpoczeteNieZakonczone = $_COOKIE['wz_rnz'];
//	} else {
	
		if ($GetLicznikFrom=='zgloszenia') {
			list($WszystkieRozpoczeteNieZakonczone)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status='7') and (zgl_widoczne=1)", $conn_hd));
		} else {
			list($WszystkieRozpoczeteNieZakonczone)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status='7') and (zgl_widoczne=1)", $conn_hd));
		}
//	}
	if ($WszystkieRozpoczeteNieZakonczone==0) {	?>
	<script>
		document.getElementById("tr_wz_rnz").style.display='none';
		document.getElementById("wz_rnz").innerHTML='';
	</script>
	<?php } else { ?>
	<script>
		document.getElementById("tr_wz_rnz").style.display=document.getElementById('ukryj_hd_wszystkie').style.display;
		document.getElementById("wz_rnz").innerHTML='<?php echo $WszystkieRozpoczeteNieZakonczone; ?>';
	</script>
	<?php }
	setcookie('wz_rnz',$WszystkieRozpoczeteNieZakonczone);
	break;

	
case 'mz_r': 

//	if (($_GET[on_load]=='1') && (isset($_COOKIE['mz_r']))) {
//		$RozpoczetePrzezeMnie = $_COOKIE['mz_r'];
//	} else {	
		if ($GetLicznikFrom=='zgloszenia') {
			list($RozpoczetePrzezeMnie)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status='3') and (zgl_osoba_przypisana='$currentuser') and (zgl_widoczne=1)", $conn_hd));
		} else {
			list($RozpoczetePrzezeMnie)=mysql_fetch_array(mysql_query("SELECT sum(statystyka_wartosc) FROM $dbname_hd.hd_statystyka WHERE (statystyka_filia=$es_filia) and (statystyka_status='3') and (statystyka_osoba='$currentuser')", $conn_hd));
		}
//	}
	if ($RozpoczetePrzezeMnie==0) {	?>
	<script>
		document.getElementById("tr_mz_r").style.display='none';
		document.getElementById("mz_r").innerHTML='';
	</script>
	<?php } else { ?>
	<script>
		document.getElementById("tr_mz_r").style.display=document.getElementById('ukryj_hd_moje').style.display;
		document.getElementById("mz_r").innerHTML='<?php echo $RozpoczetePrzezeMnie; ?>';
	</script>
	<?php }
	setcookie('mz_r',$RozpoczetePrzezeMnie);	
	break;

	
case 'mz_wf': 
//	if (($_GET[on_load]=='1') && (isset($_COOKIE['mz_wf']))) {
//		$MojeWFirmie = $_COOKIE['mz_wf'];
//	} else {
		if ($GetLicznikFrom=='zgloszenia') {
			list($MojeWFirmie)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status='3B') and (zgl_osoba_przypisana='$currentuser') and (zgl_widoczne=1)", $conn_hd));
		} else {
			list($MojeWFirmie)=mysql_fetch_array(mysql_query("SELECT sum(statystyka_wartosc) FROM $dbname_hd.hd_statystyka WHERE (statystyka_filia=$es_filia) and (statystyka_status='3B') and (statystyka_osoba='$currentuser')", $conn_hd));
		}
//	}
	if ($MojeWFirmie==0) {	?>
	<script>
		document.getElementById("tr_mz_wf").style.display='none';
		document.getElementById("mz_wf").innerHTML='';
	</script>
	<?php } else { ?>
	<script>
		document.getElementById("tr_mz_wf").style.display=document.getElementById('ukryj_hd_moje').style.display;
		document.getElementById("mz_wf").innerHTML='<?php echo $MojeWFirmie; ?>';
	</script>
	<?php }
	setcookie('mz_wf',$MojeWFirmie);
	break;

	
case 'mz_ws': 
//	if (($_GET[on_load]=='1') && (isset($_COOKIE['mz_ws']))) {
//		$MojeWSerwisie = $_COOKIE['mz_ws'];
//	} else {
		if ($GetLicznikFrom=='zgloszenia') {
			list($MojeWSerwisie)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status='3A') and (zgl_osoba_przypisana='$currentuser') and (zgl_widoczne=1)", $conn_hd));
		} else {
			list($MojeWSerwisie)=mysql_fetch_array(mysql_query("SELECT sum(statystyka_wartosc) FROM $dbname_hd.hd_statystyka WHERE (statystyka_filia=$es_filia) and (statystyka_status='3A') and (statystyka_osoba='$currentuser')", $conn_hd));
		}
//	}
	if ($MojeWSerwisie==0) { ?>
	<script>
		document.getElementById("tr_mz_ws").style.display='none';
		document.getElementById("mz_ws").innerHTML='';
	</script>
	<?php } else { ?>
	<script>
		document.getElementById("tr_mz_ws").style.display=document.getElementById('ukryj_hd_moje').style.display;
		document.getElementById("mz_ws").innerHTML='<?php echo $MojeWSerwisie; ?>';
	</script>
	<?php }
	setcookie('mz_ws',$MojeWSerwisie);
	break;

	
case 'mz_nz': 

//	if (($_GET[on_load]=='1') && (isset($_COOKIE['mz_nz']))) {
//		$NieZakonczonePrzezeMnie = $_COOKIE['mz_nz'];
//	} else {	
		if ($GetLicznikFrom=='zgloszenia') {
			list($NieZakonczonePrzezeMnie)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status<>'9') and (zgl_osoba_przypisana='$currentuser') and (zgl_widoczne=1)", $conn_hd));
		} else {
			list($NieZakonczonePrzezeMnie)=mysql_fetch_array(mysql_query("SELECT sum(statystyka_wartosc) FROM $dbname_hd.hd_statystyka WHERE (statystyka_filia=$es_filia) and (statystyka_status<>'9') and (statystyka_osoba='$currentuser')", $conn_hd));
		}
//	}
	if ($NieZakonczonePrzezeMnie==0) {	?>
	<script>
		document.getElementById("tr_mz_nz").style.display='none';
		document.getElementById("mz_nz").innerHTML='';
	</script>
	<?php } else { ?>
	<script>
		document.getElementById("tr_mz_nz").style.display=document.getElementById('ukryj_hd_moje').style.display;
		document.getElementById("mz_nz").innerHTML='<?php echo $NieZakonczonePrzezeMnie; ?>';
	</script>
	<?php }
	setcookie('mz_nz',$NieZakonczonePrzezeMnie);
	break;		

	
case 'mz_do': 

//	if (($_GET[on_load]=='1') && (isset($_COOKIE['mz_do']))) {
//		$MojeDoOddania = $_COOKIE['mz_do'];
//	} else {
		if ($GetLicznikFrom=='zgloszenia') {
			list($MojeDoOddania)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status='6') and (zgl_osoba_przypisana='$currentuser') and (zgl_widoczne=1)", $conn_hd));
		} else {
			list($MojeDoOddania)=mysql_fetch_array(mysql_query("SELECT sum(statystyka_wartosc) FROM $dbname_hd.hd_statystyka WHERE (statystyka_filia=$es_filia) and (statystyka_status='6') and (statystyka_osoba='$currentuser')", $conn_hd));
		}
//	}
	if ($MojeDoOddania==0) {	?>
	<script>
		document.getElementById("tr_mz_do").style.display='none';
		document.getElementById("mz_do").innerHTML='';
	</script>
	<?php } else { ?>
	<script>
		document.getElementById("tr_mz_do").style.display=document.getElementById('ukryj_hd_moje').style.display;
		document.getElementById("mz_do").innerHTML='<?php echo $MojeDoOddania; ?>';
	</script>
	<?php }	
	setcookie('mz_do',$MojeDoOddania);
	break;	

	
case 'mz_z': 

//	if (($_GET[on_load]=='1') && (isset($_COOKIE['mz_z']))) {
//		$ZakonczonePrzezeMnie = $_COOKIE['mz_z'];
//	} else {
		if ($GetLicznikFrom=='zgloszenia') {
			list($ZakonczonePrzezeMnie)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status='9') and (zgl_osoba_przypisana='$currentuser') and (zgl_widoczne=1)", $conn_hd));
		} else {
			list($ZakonczonePrzezeMnie)=mysql_fetch_array(mysql_query("SELECT sum(statystyka_wartosc) FROM $dbname_hd.hd_statystyka WHERE (statystyka_filia=$es_filia) and (statystyka_status='9') and (statystyka_osoba='$currentuser')", $conn_hd));
		}
//	}
	if ($ZakonczonePrzezeMnie==0) {	?>
	<script>
		document.getElementById("tr_mz_zak").style.display='none';
		document.getElementById("mz_zak").innerHTML='';
	</script>
	<?php } else { ?>
	<script>
		document.getElementById("tr_mz_z").style.display=document.getElementById('ukryj_hd_moje').style.display;
		document.getElementById("mz_z").innerHTML='<?php echo $ZakonczonePrzezeMnie; ?>';
	</script>
	<?php }
	setcookie('mz_z',$ZakonczonePrzezeMnie);
	break;		

	
case 'mz_ak': 

//	if (($_GET[on_load]=='1') && (isset($_COOKIE['mz_ak']))) {
//		$MojeAwarieKrytyczne = $_COOKIE['mz_ak'];
//	} else {
		if ($GetLicznikFrom=='zgloszenia') {
			list($MojeAwarieKrytyczne)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status<>'9') and (zgl_osoba_przypisana='$currentuser') and (zgl_widoczne=1) and (zgl_kategoria='6')", $conn_hd));
		} else {
			list($MojeAwarieKrytyczne)=mysql_fetch_array(mysql_query("SELECT sum(statystyka_wartosc) FROM $dbname_hd.hd_statystyka WHERE (statystyka_filia=$es_filia) and (statystyka_status<>'9') and (statystyka_kategoria='6') and (statystyka_osoba='$currentuser')", $conn_hd));
		}
//	}
	if ($MojeAwarieKrytyczne==0) {	?>
	<script>
		document.getElementById("tr_mz_ak").style.display='none';
		document.getElementById("mz_ak").innerHTML='';
	</script>
	<?php } else { ?>
	<script>
		document.getElementById("tr_mz_ak").style.display='';
		document.getElementById("mz_ak").innerHTML='<?php echo $MojeAwarieKrytyczne; ?>';
	</script>
	<?php }
	setcookie('mz_ak',$MojeAwarieKrytyczne);
	break;	

	
case 'mz_az': 

//	if (($_GET[on_load]=='1') && (isset($_COOKIE['mz_az']))) {
//		$MojeAwarieZwykle = $_COOKIE['mz_az'];
//	} else {
		if ($GetLicznikFrom=='zgloszenia') {
			list($MojeAwarieZwykle)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status<>'9') and (zgl_osoba_przypisana='$currentuser') and (zgl_widoczne=1) and (zgl_kategoria='2')", $conn_hd));
		} else {
			list($MojeAwarieZwykle)=mysql_fetch_array(mysql_query("SELECT sum(statystyka_wartosc) FROM $dbname_hd.hd_statystyka WHERE (statystyka_filia=$es_filia) and (statystyka_status<>'9') and (statystyka_kategoria='2') and (statystyka_osoba='$currentuser')", $conn_hd));
		}
//	}
	if ($MojeAwarieZwykle==0) {	?>
	<script>
		document.getElementById("tr_mz_az").style.display='none';
		document.getElementById("mz_az").innerHTML='';
	</script>
	<?php } else { ?>
	<script>
		document.getElementById("tr_mz_az").style.display='';
		document.getElementById("mz_az").innerHTML='<?php echo $MojeAwarieZwykle; ?>';
	</script>
	<?php }
	setcookie('mz_az',$MojeAwarieZwykle);
	break;	

	
case 'mz_pzwrupw': 
	
//	if (($_GET[on_load]=='1') && (isset($_COOKIE['mz_pzwrupw']))) {
//		$MojePraceZleconeWRamachUmowy = $_COOKIE['mz_pzwrupw'];
//	} else {
		if ($GetLicznikFrom=='zgloszenia') {
			list($MojePraceZleconeWRamachUmowy)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status<>'9') and (zgl_osoba_przypisana='$currentuser') and (zgl_widoczne=1) and (zgl_kategoria='3')", $conn_hd));
		} else {
			list($MojePraceZleconeWRamachUmowy)=mysql_fetch_array(mysql_query("SELECT sum(statystyka_wartosc) FROM $dbname_hd.hd_statystyka WHERE (statystyka_filia=$es_filia) and (statystyka_kategoria='3') and (statystyka_osoba='$currentuser')", $conn_hd));
		}
//	}
	if ($MojePraceZleconeWRamachUmowy==0) {	?>
	<script>
		document.getElementById("tr_mz_pzwrupw").style.display='none';
		document.getElementById("mz_pzwrupw").innerHTML='';
	</script>
	<?php } else { ?>
	<script>
		document.getElementById("tr_mz_pzwrupw").style.display='';
		document.getElementById("mz_pzwrupw").innerHTML='<?php echo $MojePraceZleconeWRamachUmowy; ?>';
	</script>
	<?php }
	setcookie('mz_pzwrupw',$MojePraceZleconeWRamachUmowy);
	break;	

	
case 'wz_n': 

	//if (($_GET[on_load]=='1') && (isset($_COOKIE['wz_n']))) {
	//	$WszystkieNowe = $_COOKIE['wz_n'];
	//} else {
	list($WszystkieNowe)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status='1') and (zgl_widoczne=1)", $conn_hd));
	//}
	if ($WszystkieNowe==0) { ?>
	<script>
		document.getElementById("tr_wz_n").style.display='';
		document.getElementById("wz_n").innerHTML='0';
	</script>
	<?php } else { ?>
	<script>
		document.getElementById("tr_wz_n").style.display=document.getElementById('ukryj_hd_wszystkie').style.display;
		document.getElementById("wz_n").innerHTML='<?php echo $WszystkieNowe; ?>';
	</script>
	<?php }
	setcookie('wz_n',$WszystkieNowe);
	break;

	
case 'wz_p': 
//	if (($_GET[on_load]=='1') && (isset($_COOKIE['wz_p']))) {
//		$WszystkiePrzypisane = $_COOKIE['wz_p'];
//	} else {
		if ($GetLicznikFrom=='zgloszenia') {
			list($WszystkiePrzypisane)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status='2') and (zgl_widoczne=1)", $conn_hd));
		} else {
			list($WszystkiePrzypisane)=mysql_fetch_array(mysql_query("SELECT sum(statystyka_wartosc) FROM $dbname_hd.hd_statystyka WHERE (statystyka_filia=$es_filia) and (statystyka_status='2')", $conn_hd));
		}	
//	}
	if ($WszystkiePrzypisane==0) {	?>
	<script>
		document.getElementById("tr_wz_p").style.display='none';
		document.getElementById("wz_p").innerHTML='';
	</script>
	<?php } else { ?>
	<script>
		document.getElementById("tr_wz_p").style.display=document.getElementById('ukryj_hd_wszystkie').style.display;
		document.getElementById("wz_p").innerHTML='<?php echo $WszystkiePrzypisane; ?>';
	</script>
	<?php }
	setcookie('wz_p',$WszystkiePrzypisane);
	break;	


case 'wz_r': 
//	if (($_GET[on_load]=='1') && (isset($_COOKIE['wz_r']))) {
//		$WszystkieRozpoczete = $_COOKIE['wz_r'];
//	} else {
		if ($GetLicznikFrom=='zgloszenia') {
			list($WszystkieRozpoczete)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status='3') and (zgl_widoczne=1)", $conn_hd));
		} else {
			list($WszystkieRozpoczete)=mysql_fetch_array(mysql_query("SELECT sum(statystyka_wartosc) FROM $dbname_hd.hd_statystyka WHERE (statystyka_filia=$es_filia) and (statystyka_status='3')", $conn_hd));
		}	
//	}
	if ($WszystkieRozpoczete==0) {	?>
	<script>
		document.getElementById("tr_wz_r").style.display='none';
		document.getElementById("wz_r").innerHTML='';
	</script>
	<?php } else { ?>
	<script>
		document.getElementById("tr_wz_r").style.display=document.getElementById('ukryj_hd_wszystkie').style.display;
		document.getElementById("wz_r").innerHTML='<?php echo $WszystkieRozpoczete; ?>';
	</script>
	<?php }
	setcookie('wz_r',$WszystkieRozpoczete);
	break;	

	
case 'wz_wf': 
//	if (($_GET[on_load]=='1') && (isset($_COOKIE['wz_wf']))) {
//		$WszystkieWFirmie = $_COOKIE['wz_wf'];
//	} else {
		if ($GetLicznikFrom=='zgloszenia') {
			list($WszystkieWFirmie)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status='3B') and (zgl_widoczne=1)", $conn_hd));
		} else {
			list($WszystkieWFirmie)=mysql_fetch_array(mysql_query("SELECT sum(statystyka_wartosc) FROM $dbname_hd.hd_statystyka WHERE (statystyka_filia=$es_filia) and (statystyka_status='3B')", $conn_hd));
		}
//	}
	if ($WszystkieWFirmie==0) {	?>
	<script>
		document.getElementById("tr_wz_wf").style.display='none';
		document.getElementById("wz_wf").innerHTML='';
	</script>
	<?php } else { ?>
	<script>
		document.getElementById("tr_wz_wf").style.display=document.getElementById('ukryj_hd_wszystkie').style.display;
		document.getElementById("wz_wf").innerHTML='<?php echo $WszystkieWFirmie; ?>';
	</script>
	<?php }
	setcookie('wz_wf',$WszystkieWFirmie);
	break;

	
case 'wz_ws': 

//	if (($_GET[on_load]=='1') && (isset($_COOKIE['wz_ws']))) {
//		$WszystkieWSerwisie = $_COOKIE['wz_ws'];
//	} else {
		if ($GetLicznikFrom=='zgloszenia') {
			list($WszystkieWSerwisie)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status='3A') and (zgl_widoczne=1)", $conn_hd));
		} else {
			list($WszystkieWSerwisie)=mysql_fetch_array(mysql_query("SELECT sum(statystyka_wartosc) FROM $dbname_hd.hd_statystyka WHERE (statystyka_filia=$es_filia) and (statystyka_status='3A')", $conn_hd));
		}	
//	}
	if ($WszystkieWSerwisie==0) {	?>
	<script>
		document.getElementById("tr_wz_ws").style.display='none';
		document.getElementById("wz_ws").innerHTML='';
	</script>
	<?php } else { ?>
	<script>
		document.getElementById("tr_wz_ws").style.display=document.getElementById('ukryj_hd_wszystkie').style.display;
		document.getElementById("wz_ws").innerHTML='<?php echo $WszystkieWSerwisie; ?>';
	</script>
	<?php }
	setcookie('wz_ws',$WszystkieWSerwisie);
	break;

	
case 'wz_nz': 

//	if (($_GET[on_load]=='1') && (isset($_COOKIE['wz_nz']))) {
//		$WszystkieNieZamkniete = $_COOKIE['wz_nz'];
//	} else {
		if ($GetLicznikFrom=='zgloszenia') {
			list($WszystkieNieZamkniete)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status<>'9') and (zgl_widoczne=1)", $conn_hd));
		} else {
			list($WszystkieNieZamkniete)=mysql_fetch_array(mysql_query("SELECT sum(statystyka_wartosc) FROM $dbname_hd.hd_statystyka WHERE (statystyka_filia=$es_filia) and (statystyka_status<>'9')", $conn_hd));
		}
//	}
	if ($WszystkieNieZamkniete==0) { ?>
	<script>
		document.getElementById("tr_wz_nz").style.display='none';
		document.getElementById("wz_nz").innerHTML='';
	</script>
	<?php } else { ?>
	<script>
		document.getElementById("tr_wz_nz").style.display=document.getElementById('ukryj_hd_wszystkie').style.display;
		document.getElementById("wz_nz").innerHTML='<?php echo $WszystkieNieZamkniete; ?>';
	</script>
	<?php }
	setcookie('wz_nz',$WszystkieNieZamkniete);
	break;	


case 'wz_do': 

//	if (($_GET[on_load]=='1') && (isset($_COOKIE['wz_do']))) {
//		$WszystkieDoOddania = $_COOKIE['wz_do'];
//	} else {
		if ($GetLicznikFrom=='zgloszenia') {
			list($WszystkieDoOddania)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status='6') and (zgl_widoczne=1)", $conn_hd));
		} else {
			list($WszystkieDoOddania)=mysql_fetch_array(mysql_query("SELECT sum(statystyka_wartosc) FROM $dbname_hd.hd_statystyka WHERE (statystyka_filia=$es_filia) and (statystyka_status='6')", $conn_hd));
		}	
//	}
	if ($WszystkieDoOddania==0) { ?>
	<script>
		document.getElementById("tr_wz_do").style.display='none';
		document.getElementById("wz_do").innerHTML='';
	</script>
	<?php } else { ?>
	<script>
		document.getElementById("tr_wz_do").style.display=document.getElementById('ukryj_hd_wszystkie').style.display;
		document.getElementById("wz_do").innerHTML='<?php echo $WszystkieDoOddania; ?>';
	</script>
	<?php }
	setcookie('wz_do',$WszystkieDoOddania);
	break;	

	
case 'wz_z': 

//	if (($_GET[on_load]=='1') && (isset($_COOKIE['wz_z']))) {
//		$WszystkieZamkniete = $_COOKIE['wz_z'];
//	} else {
		if ($GetLicznikFrom=='zgloszenia') {
			list($WszystkieZamkniete)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status='9') and (zgl_widoczne=1)", $conn_hd));
		} else {
			list($WszystkieZamkniete)=mysql_fetch_array(mysql_query("SELECT sum(statystyka_wartosc) FROM $dbname_hd.hd_statystyka WHERE (statystyka_filia=$es_filia) and (statystyka_status='9')", $conn_hd));
		}		
//	}
	if ($WszystkieZamkniete==0) {	?>
	<script>
		document.getElementById("tr_wz_z").style.display='none';
		document.getElementById("wz_z").innerHTML='';
	</script>
	<?php } else { ?>
	<script>
		document.getElementById("tr_wz_z").style.display=document.getElementById('ukryj_hd_wszystkie').style.display;
		document.getElementById("wz_z").innerHTML='<?php echo $WszystkieZamkniete; ?>';
	</script>
	<?php }
	setcookie('wz_z',$WszystkieZamkniete);
	break;	


case 'wz_w': 

//	if (($_GET[on_load]=='1') && (isset($_COOKIE['wz_w']))) {
//		$WszystkieZgloszenia = $_COOKIE['wz_w'];
//	} else {
		if ($GetLicznikFrom=='zgloszenia') {
			list($WszystkieZgloszenia)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and  (zgl_widoczne=1)", $conn_hd));
		} else {
			list($WszystkieZgloszenia)=mysql_fetch_array(mysql_query("SELECT sum(statystyka_wartosc) FROM $dbname_hd.hd_statystyka WHERE (statystyka_filia=$es_filia)", $conn_hd));
		}
//	}
	if ($WszystkieZgloszenia==0) {	?>
	<script>
		document.getElementById("tr_wz_w").style.display='none';
		document.getElementById("wz_w").innerHTML='';
		document.getElementById("th_wz").style.display='none';
	</script>
	<?php } else { ?>
	<script>
		document.getElementById("tr_wz_w").style.display=document.getElementById('ukryj_hd_wszystkie').style.display;
		document.getElementById("th_wz").style.display=document.getElementById('ukryj_hd_wszystkie').style.display;
		document.getElementById("wz_w").innerHTML='<?php echo $WszystkieZgloszenia; ?>';
	</script>
	<?php }
	setcookie('wz_w',$WszystkieZgloszenia);
	break;		

	
case 'wz_ak': 

//	if (($_GET[on_load]=='1') && (isset($_COOKIE['wz_ak']))) {
//		$WszystkieAwarieKrytyczne = $_COOKIE['wz_ak'];
//	} else {
		if ($GetLicznikFrom=='zgloszenia') {
			list($WszystkieAwarieKrytyczne)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and  (zgl_widoczne=1) and (zgl_kategoria='6') and (zgl_status<>'9')", $conn_hd));
		} else {
			list($WszystkieAwarieKrytyczne)=mysql_fetch_array(mysql_query("SELECT sum(statystyka_wartosc) FROM $dbname_hd.hd_statystyka WHERE (statystyka_filia=$es_filia) and (statystyka_status<>'9') and (statystyka_kategoria='6')", $conn_hd));
		}	
//	}
	if ($WszystkieAwarieKrytyczne==0) {	?>
	<script>
		document.getElementById("tr_wz_ak").style.display='none';
		document.getElementById("wz_ak").innerHTML='';
	</script>
	<?php } else { ?>
	<script>
		document.getElementById("tr_wz_ak").style.display='';
		document.getElementById("wz_ak").innerHTML='<?php echo $WszystkieAwarieKrytyczne; ?>';
	</script>
	<?php }
	setcookie('wz_ak',$WszystkieAwarieKrytyczne);
	break;	

	
case 'wz_az': 

//	if (($_GET[on_load]=='1') && (isset($_COOKIE['wz_az']))) {
// 		$WszystkieAwarieZwykle = $_COOKIE['wz_az'];
//	} else {
		if ($GetLicznikFrom=='zgloszenia') {
			list($WszystkieAwarieZwykle)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and  (zgl_widoczne=1) and (zgl_kategoria='2') and (zgl_status<>'9')", $conn_hd));
		} else {
			list($WszystkieAwarieZwykle)=mysql_fetch_array(mysql_query("SELECT sum(statystyka_wartosc) FROM $dbname_hd.hd_statystyka WHERE (statystyka_filia=$es_filia) and (statystyka_status<>'9') and (statystyka_priorytet='2')", $conn_hd));
		}		
//	}
	if ($WszystkieAwarieZwykle==0) {	?>
	<script>
		document.getElementById("tr_wz_az").style.display='none';
		document.getElementById("wz_az").innerHTML='';
	</script>
	<?php } else { ?>
	<script>
		document.getElementById("tr_wz_az").style.display='';
		document.getElementById("wz_az").innerHTML='<?php echo $WszystkieAwarieZwykle; ?>';
	</script>
	<?php }
	setcookie('wz_az',$WszystkieAwarieZwykle);
	break;	


case 'wz_pzwrupw': 

//	if (($_GET[on_load]=='1') && (isset($_COOKIE['wz_pzwrupw']))) {
//		$WszystkiePraceZleconeWRamachUmowy = $_COOKIE['wz_pzwrupw'];
//	} else {
		if ($GetLicznikFrom=='zgloszenia') {
			list($WszystkiePraceZleconeWRamachUmowy)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_widoczne=1) and (zgl_kategoria='3') and (zgl_status<>'9')", $conn_hd));
		} else {
			list($WszystkiePraceZleconeWRamachUmowy)=mysql_fetch_array(mysql_query("SELECT sum(statystyka_wartosc) FROM $dbname_hd.hd_statystyka WHERE (statystyka_filia=$es_filia) and (statystyka_status<>'9')", $conn_hd));
		}	
//	}
	if ($WszystkiePraceZleconeWRamachUmowy==0) {	?>
	<script>
		document.getElementById("tr_wz_pzwrupw").style.display='none';
		document.getElementById("wz_pzwrupw").innerHTML='';
	</script>
	<?php } else { ?>
	<script>
		document.getElementById("tr_wz_pzwrupw").style.display=document.getElementById('ukryj_hd_wszystkie').style.display;
		document.getElementById("wz_pzwrupw").innerHTML='<?php echo $WszystkiePraceZleconeWRamachUmowy; ?>';
	</script>
	<?php }
	setcookie('wz_pzwrupw',$WszystkiePraceZleconeWRamachUmowy);
	break;	
	
}
?>
<?php /*
<script>

if (	
	(document.getElementById('tr_mz_p').style.display=='none') && 
	(document.getElementById('tr_mz_r').style.display=='none') && 
	(document.getElementById('tr_mz_ws').style.display=='none') && 
	(document.getElementById('tr_mz_nz').style.display=='none') && 
	(document.getElementById('tr_mz_ak').style.display=='none') && 
	(document.getElementById('tr_mz_az').style.display=='none') && 
	(document.getElementById('tr_mz_pzwrupw').style.display=='none')
	) 
	{
	
		document.getElementById('mz').style.display='none';
	
	} else {
		document.getElementById('mz').style.display='';
	}
</script>
*/
?>