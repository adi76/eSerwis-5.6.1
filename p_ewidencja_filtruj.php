<?php include_once('header.php'); ?>
<body>
<?php include('body_start.php'); ?>	
<?php
$sqlnew="SELECT * FROM $dbname.serwis_ewidencja WHERE ";
if ($es_m!=1) { $sqlnew = $sqlnew."(belongs_to=$es_filia) and "; }	
$sqlnew = $sqlnew."(ewidencja_status>-2)";

$esk__rola=$esk_rola;
$esk__lok=$esk_lok;
$esk__user=$esk_user;
$esk__nrpok=$esk_nrpok;
$esk__nrinwz=$esk_nrinwz;
$esk__nazwak=$esk_nazwak;
$esk__opisk=$esk_opisk;
$esk__nrsk=$esk_nrsk;
$esk__nrip=$esk_nrip;
$esk__endpoint=$esk_endpoint;
$esk__nazwam=$esk_nazwam;
$esk__nrsm=$esk_nrsm;
$esk__nazwad=$esk_nazwad;
$esk__nrsd=$esk_nrsd;
$esk__nrinwd=$esk_nrinwd;
$esk__konf=$esk_konf;
		
if ($esk_rola_check=="on") {
	if ($esk_rola!='-1') { $sqlnew=$sqlnew." AND (ewidencja_typ=$esk__rola)"; } else
		$sqlnew=$sqlnew." AND (ewidencja_typ>0)";
	}
	if ($esk_lok_check=="on") {
	  if ($esk_lok!='-1') { $sqlnew=$sqlnew." AND (ewidencja_up_id=$esk__lok)"; } else
	    $sqlnew=$sqlnew." AND (ewidencja_up_id>0)";
	}	
	if (($esk_user_check=="on") && ($esk_user_checkw!="on")) { $sqlnew=$sqlnew." AND (ewidencja_uzytkownik LIKE '".cleanup($esk__user)."')"; }
	if (($esk_nrpok_check=="on") && ($esk_nrpok_checkw!="on")) { $sqlnew=$sqlnew." AND (ewidencja_nr_pokoju LIKE '".cleanup($esk__nrpok)."')"; }
	if (($esk_nrinwz_check=="on") && ($esk_nrinwz_checkw!="on")) { $sqlnew=$sqlnew." AND (ewidencja_zestaw_ni LIKE '".cleanup($esk__nrinwz)."')"; }	
	if (($esk_nazwak_check=="on") && ($esk_nazwak_checkw!="on")) { $sqlnew=$sqlnew." AND (ewidencja_komputer_nazwa LIKE '".cleanup(esk__nazwak)."')"; }
	if (($esk_opisk_check=="on") && ($esk_opisk_checkw!="on")) { $sqlnew=$sqlnew." AND (ewidencja_komputer_opis LIKE '".cleanup($esk__opisk)."')"; }
	if (($esk_nrsk_check=="on") && ($esk_nrsk_checkw!="on")) { $sqlnew=$sqlnew." AND (ewidencja_komputer_sn LIKE '".cleanup($esk__nrsk)."')"; }
	if (($esk_nrip_check=="on") && ($esk_nrip_checkw!="on")) { $sqlnew=$sqlnew." AND (ewidencja_komputer_ip LIKE '".cleanup($esk__nrip)."')"; }
	if (($esk_endpoint_check=="on") && ($esk_endpoint_checkw!="on")) { $sqlnew=$sqlnew." AND (ewidencja_komputer_endpoint LIKE '".cleanup($esk__endpoint)."')"; }
	if (($esk_nazwam_check=="on") && ($esk_nazwam_checkw!="on")) { $sqlnew=$sqlnew." AND (ewidencja_monitor_opis LIKE '".cleanup($esk__nazwam)."')"; }
	if (($esk_nrsm_check=="on") && ($esk_nrsm_checkw!="on")) { $sqlnew=$sqlnew." AND (ewidencja_monitor_sn LIKE '".cleanup($esk__nrsm)."')"; }
	if (($esk_nazwad_check=="on") && ($esk_nazwad_checkw!="on")) { $sqlnew=$sqlnew." AND (ewidencja_drukarka_opis LIKE '".cleanup($esk__nazwad)."')"; }
	if (($esk_nrsd_check=="on") && ($esk_nrsd_checkw!="on")) { $sqlnew=$sqlnew." AND (ewidencja_drukarka_sn LIKE '".cleanup($esk__nrsd)."')"; }
	if (($esk_nrinwd_check=="on") && ($esk_nrinwd_checkw!="on")) { $sqlnew=$sqlnew." AND (ewidencja_drukarka_ni LIKE '".cleanup($esk__nrinwd)."')"; }
	if (($esk_konf_check=="on") && ($esk_konf_checkw!="on")) { $sqlnew=$sqlnew." AND (ewidencja_konfiguracja LIKE '".cleanup($esk__konf)."')"; }

	$sqlnew=$sqlnew." ORDER BY ewidencja_up_nazwa, ewidencja_typ_nazwa";
	if ($stat=="on") $result_truncate = mysql_query("TRUNCATE TABLE serwis_temp", $conn) or die($k_b);
	
	$result = mysql_query($sqlnew, $conn) or die($k_b);
	
// paging
$totalrows = mysql_num_rows($result);
if(empty($page)){ $page = 1; }

if ($showall==0) {
  $rps=$rowpersite;
} else $rps=10000;

if ($showall==0) 
{
	$limitvalue = $page * $rps - ($rps);
	$sqlnew=$sqlnew." LIMIT $limitvalue, $rps";
}

$esk_user=urlencode($esk_user);
$esk_nrpok=urlencode($esk_nrpok);
$esk_nrinwz=urlencode($esk_nrinwz);
$esk_nazwak=urlencode($esk_nazwak);
$esk_opisk=urlencode($esk_opisk);
$esk_nrsk=urlencode($esk_nrsk);
$esk_nrip=urlencode($esk_nrip);
$esk_endpoint=urlencode($esk_endpoint);
$esk_nazwam=urlencode($esk_nazwam);
$esk_nrsm=urlencode($esk_nrsm);
$esk_nazwad=urlencode($esk_nazwad);
$esk_nrsd=urlencode($esk_nrsd);
$esk_nrinwd=urlencode($esk_nrinwd);
$esk_konf=urlencode($esk_konf);

$result = mysql_query($sqlnew, $conn) or die($k_b);
// koniec - paging
	
if (mysql_num_rows($result)!=0) {
pageheader("Ewidencja sprzętu - widok użytkownika",1,1);	
	
?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
	
startbuttonsarea("center");
if ($showall==0) {			
echo "<input type=button class=buttons onClick=\"location.href='p_ewidencja_filtruj.php?showall=1&page=$paget&paget=$page&okres_od=$okres_od&okres_do=$okres_do&sel=$sel&view=$view&ew_action=$ew_action&g=$g&allowback=$allowback&s=$s&sel_up=$sel_up&esk_rola_check=$esk_rola_check&esk_rola=$esk_rola&stat=$stat&esk_lok_check=$esk_lok_check&esk_lok=$esk_lok&esk_user_check=$esk_user_check&esk_user_checkw=$esk_user_checkw&esk_user=$esk_user&esk_nrpok_check=$esk_nrpok_check&esk_nrpok_checkw=$esk_nrpok_checkw&esk_nrpok=$esk_nrpok&esk_nrinwz_check=$esk_nrinwz_check&esk_nrinwz_checkw=$esk_nrinwz_checkw&esk_nrinwz=$esk_nrinwz&esk_nazwak_check=$esk_nazwak_check&esk_nazwak_checkw=$esk_nazwak_checkw&esk_nazwak=$esk_nazwak&esk_opisk_check=$esk_opisk_check&esk_opisk_checkw=$esk_opisk_checkw&esk_opisk=$esk_opisk&esk_nrsk_check=$esk_nrsk_check&esk_nrsk_checkw=$esk_nrsk_checkw&esk_nrsk=$esk_nrsk&esk_nrip_check=$esk_nrip_check&esk_nrip_checkw=$esk_nrip_checkw&esk_nrip=$esk_nrip&esk_endpoint_check=$esk_endpoint_check&esk_endpoint_checkw=$esk_endpoint_checkw&esk_endpoint=$esk_endpoint&esk_nazwam_check=$esk_nazwam_check&esk_nazwam_checkw=$esk_nazwam_checkw&esk_nazwam=$esk_nazwam&esk_nrsm_check=$esk_nrsm_check&esk_nrsm_checkw=$esk_nrsm_checkw&esk_nrsm=$esk_nrsm&esk_nazwad_check=$esk_nazwad_check&esk_nazwad_checkw=$esk_nazwad_checkw&esk_nazwad=$esk_nazwad&esk_nrsd_check=$esk_nrsd_check&esk_nrsd_checkw=$esk_nrsd_checkw&esk_nrsd=$esk_nrsd&esk_nrinwd_check=$esk_nrinwd_check&esk_nrinwd_checkw=$esk_nrinwd_checkw&esk_nrinwd=$esk_nrinwd&esk_konf_check=$esk_konf_check&esk_konf_checkw=$esk_konf_checkw&esk_konf=$esk_konf&esk_uwagi_check=$esk_uwagi_check&esk_status_check=$esk_status_check&esk_opcje_check=$esk_opcje_check&action=$action';\" value='Pokaż wszystko na jednej stronie'>| ";
} 	

if (($showall==1) && ($printpreview==0)) {		
echo "<input type=button class=buttons onClick=\"location.href='p_ewidencja_filtruj.php?showall=0&page=$paget&okres_od=$okres_od&okres_do=$okres_do&sel=$sel&view=$view&ew_action=$ew_action&g=$g&allowback=$allowback&s=$s&sel_up=$sel_up&esk_rola_check=$esk_rola_check&esk_rola=$esk_rola&stat=$stat&esk_lok_check=$esk_lok_check&esk_lok=$esk_lok&esk_user_check=$esk_user_check&esk_user_checkw=$esk_user_checkw&esk_user=$esk_user&esk_nrpok_check=$esk_nrpok_check&esk_nrpok_checkw=$esk_nrpok_checkw&esk_nrpok=$esk_nrpok&esk_nrinwz_check=$esk_nrinwz_check&esk_nrinwz_checkw=$esk_nrinwz_checkw&esk_nrinwz=$esk_nrinwz&esk_nazwak_check=$esk_nazwak_check&esk_nazwak_checkw=$esk_nazwak_checkw&esk_nazwak=$esk_nazwak&esk_opisk_check=$esk_opisk_check&esk_opisk_checkw=$esk_opisk_checkw&esk_opisk=$esk_opisk&esk_nrsk_check=$esk_nrsk_check&esk_nrsk_checkw=$esk_nrsk_checkw&esk_nrsk=$esk_nrsk&esk_nrip_check=$esk_nrip_check&esk_nrip_checkw=$esk_nrip_checkw&esk_nrip=$esk_nrip&esk_endpoint_check=$esk_endpoint_check&esk_endpoint_checkw=$esk_endpoint_checkw&esk_endpoint=$esk_endpoint&esk_nazwam_check=$esk_nazwam_check&esk_nazwam_checkw=$esk_nazwam_checkw&esk_nazwam=$esk_nazwam&esk_nrsm_check=$esk_nrsm_check&esk_nrsm_checkw=$esk_nrsm_checkw&esk_nrsm=$esk_nrsm&esk_nazwad_check=$esk_nazwad_check&esk_nazwad_checkw=$esk_nazwad_checkw&esk_nazwad=$esk_nazwad&esk_nrsd_check=$esk_nrsd_check&esk_nrsd_checkw=$esk_nrsd_checkw&esk_nrsd=$esk_nrsd&esk_nrinwd_check=$esk_nrinwd_check&esk_nrinwd_checkw=$esk_nrinwd_checkw&esk_nrinwd=$esk_nrinwd&esk_konf_check=$esk_konf_check&esk_konf_checkw=$esk_konf_checkw&esk_konf=$esk_konf&esk_uwagi_check=$esk_uwagi_check&esk_status_check=$esk_status_check&esk_opcje_check=$esk_opcje_check&action=$action';\" value='Dziel na strony'>| ";
}	

echo "Łącznie: <b>$totalrows pozycji</b>&nbsp;|&nbsp;";

if ($printpreview==0) {
echo "<input type=button class=buttons onClick=\"location.href='p_ewidencja_filtruj.php?esk_rola_check=$esk_rola_check&esk_rola=$esk_rola&stat=$stat&esk_lok_check=$esk_lok_check&esk_lok=$esk_lok&esk_user_check=$esk_user_check&esk_user_checkw=$esk_user_checkw&esk_user=$esk_user&esk_nrpok_check=$esk_nrpok_check&esk_nrpok_checkw=$esk_nrpok_checkw&esk_nrpok=$esk_nrpok&esk_nrinwz_check=$esk_nrinwz_check&esk_nrinwz_checkw=$esk_nrinwz_checkw&esk_nrinwz=$esk_nrinwz&esk_nazwak_check=$esk_nazwak_check&esk_nazwak_checkw=$esk_nazwak_checkw&esk_nazwak=$esk_nazwak&esk_opisk_check=$esk_opisk_check&esk_opisk_checkw=$esk_opisk_checkw&esk_opisk=$esk_opisk&esk_nrsk_check=$esk_nrsk_check&esk_nrsk_checkw=$esk_nrsk_checkw&esk_nrsk=$esk_nrsk&esk_nrip_check=$esk_nrip_check&esk_nrip_checkw=$esk_nrip_checkw&esk_nrip=$esk_nrip&esk_endpoint_check=$esk_endpoint_check&esk_endpoint_checkw=$esk_endpoint_checkw&esk_endpoint=$esk_endpoint&esk_nazwam_check=$esk_nazwam_check&esk_nazwam_checkw=$esk_nazwam_checkw&esk_nazwam=$esk_nazwam&esk_nrsm_check=$esk_nrsm_check&esk_nrsm_checkw=esk_nrsm_checkw&esk_nrsm=$esk_nrsm&esk_nazwad_check=$esk_nazwad_check&esk_nazwad_checkw=$esk_nazwad_checkw&esk_nazwad=$esk_nazwad&esk_nrsd_check=$esk_nrsd_check&esk_nrsd_checkw=$esk_nrsd_checkw&esk_nrsd=$esk_nrsd&esk_nrinwd_check=$esk_nrinwd_check&esk_nrinwd_checkw=$esk_nrinwd_checkw&esk_nrinwd=$esk_nrinwd&esk_konf_check=$esk_konf_check&esk_konf_checkw=$esk_konf_checkw&esk_konf=$esk_konf&esk_uwagi_check=$esk_uwagi_check&esk_status_check=$esk_status_check&esk_opcje_check=$esk_opcje_check&action=$action&submit=submit&showall=1&printpreview=1&paget=$page&sel_up=$sel_up';\" value='Wersja do druku'>";
}

if ($printpreview==1) {
echo "<input type=button class=buttons onClick=\"location.href='p_ewidencja_filtruj.php?esk_rola_check=$esk_rola_check&esk_rola=$esk_rola&stat=$stat&esk_lok_check=$esk_lok_check&esk_lok=$esk_lok&esk_user_check=$esk_user_check&esk_user_checkw=$esk_user_checkw&esk_user=$esk_user&esk_nrpok_check=$esk_nrpok_check&esk_nrpok_checkw=$esk_nrpok_checkw&esk_nrpok=$esk_nrpok&esk_nrinwz_check=$esk_nrinwz_check&esk_nrinwz_checkw=$esk_nrinwz_checkw&esk_nrinwz=$esk_nrinwz&esk_nazwak_check=$esk_nazwak_check&esk_nazwak_checkw=$esk_nazwak_checkw&esk_nazwak=$esk_nazwak&esk_opisk_check=$esk_opisk_check&esk_opisk_checkw=$esk_opisk_checkw&esk_opisk=$esk_opisk&esk_nrsk_check=$esk_nrsk_check&esk_nrsk_checkw=$esk_nrsk_checkw&esk_nrsk=$esk_nrsk&esk_nrip_check=$esk_nrip_check&esk_nrip_checkw=$esk_nrip_checkw&esk_nrip=$esk_nrip&esk_endpoint_check=$esk_endpoint_check&esk_endpoint_checkw=$esk_endpoint_checkw&esk_endpoint=$esk_endpoint&esk_nazwam_check=$esk_nazwam_check&esk_nazwam_checkw=$esk_nazwam_checkw&esk_nazwam=$esk_nazwam&esk_nrsm_check=$esk_nrsm_check&esk_nrsm_checkw=esk_nrsm_checkw&esk_nrsm=$esk_nrsm&esk_nazwad_check=$esk_nazwad_check&esk_nazwad_checkw=$esk_nazwad_checkw&esk_nazwad=$esk_nazwad&esk_nrsd_check=$esk_nrsd_check&esk_nrsd_checkw=$esk_nrsd_checkw&esk_nrsd=$esk_nrsd&esk_nrinwd_check=$esk_nrinwd_check&esk_nrinwd_checkw=$esk_nrinwd_checkw&esk_nrinwd=$esk_nrinwd&esk_konf_check=$esk_konf_check&esk_konf_checkw=$esk_konf_checkw&esk_konf=$esk_konf&esk_uwagi_check=$esk_uwagi_check&esk_status_check=$esk_status_check&esk_opcje_check=$esk_opcje_check&action=$action&submit=submit&showall=0&printpreview=0&page=$paget&sel_up=$sel_up';\" value='Wróć do normalnego widoku'>";
}

endbuttonsarea();
starttable();
echo "<tr>";
echo "<th class=center width=40>LP</th>";

if ($esk_rola_check=="on") {				
	echo "<th>Rodzaj</th>"; 
}
 else { echo "<th></th>"; }

	if (($esk_lok_check=="on") || ($esk_nrpok_check=="on") || ($esk_user_check=="on") ) {
			echo "<th>";
				if ($esk_lok_check=="on") echo "Lokalizacja";				
				if ($esk_nrpok_check=="on") {
		   			if ($esk_lok_check=="on") echo "&nbsp;/&nbsp;";
echo "Nr pokoju"; 				
				 }
				
				if ($esk_user_check=="on") {
if (($esk_lok_check=="on") || ($esk_nrpok_check=="on")) br();
echo "&nbsp;Użytkownik"; 
				 }
			echo "</th>";
	}		
// komputer
 	if (($esk_opisk_check=="on") || ($esk_nrsk_check=="on")) {
 			echo "<th>";
			if ($esk_opisk_check=="on")	echo "Model komputera;";
			if ($esk_nrsk_check=="on")	
			  {
	   			if ($esk_opisk_check=="on")	br();
				echo "SN";
			  }
			echo "</th>";
	}
	
	if (($esk_nazwak_check=="on") || ($esk_nrip_check=="on")) {
			echo "<th class=nowrap>";
			if ($esk_nazwak_check=="on") echo "Nazwa komputera";

			if ($esk_nrip_check=="on") {
				if ($esk_nazwak_check=="on") br();
				echo "Adres IP";
			}
			echo "</th>";
	}
	
	if ($esk_endpoint_check=="on") {
			echo "<th>";
			if ($esk_endpoint_check=="on") echo "Endpoint";
			echo "</th>";
	}
	
	if (($esk_nazwam_check=="on") || ($esk_nrsm_check=="on")) {		
			echo "<th>";
			if ($esk_nazwam_check=="on") echo "Monitor";
			if ($esk_nrsm_check=="on") {
				if ($esk_nazwam_check=="on") br();
				echo "SN";
			}
			echo "</th>";
	}
				
			if ($esk_nrinwz_check=="on") echo "<th>NI zestawu</th>";
			if ($esk_nazwad_check=="on") echo "<th>Model drukarki<br /><sub>Adres IP drukarki</sub></th>";
	
	if (($esk_nrsd_check=="on") || ($esk_nrinwd_check=="on")) {		
			echo "<th>";
			if ($esk_nrsd_check=="on") echo "SN drukarki";
			if ($esk_nrinwd_check=="on") {
				if ($esk_nrsd_check=="on") br();
				echo "NI drukarki";
			}
			echo "</th>";
	}
	
	if ($esk_konf_check=="on") {
			echo "<th>";
			if ($esk_konf_check=="on") echo "Konfiguracja sprzętu";
			echo "</th>";
	}

			if ($esk_uwagi_check=="on") {
				echo "<th class=center>Uwagi</th>";
			}
				
			if ($esk_status_check=="on") {
				echo "<th aling=center>Status</th>";
			}
			
			if ($esk_opcje_check=="on") {
				echo "<th class=center>Opcje</th>";
			}

			echo "</tr>";

			$i = $page*$rps-$rps;

		    while ($dane = mysql_fetch_array($result)) {
				$eid 		= $dane['ewidencja_id'];
				$etyp_id	= $dane['ewidencja_typ'];
				$eup_id		= $dane['ewidencja_up_id'];
				$euser		= $dane['ewidencja_uzytkownik'];				  
				$enrpok		= $dane['ewidencja_nr_pokoju'];
				$enizest	= $dane['ewidencja_zestaw_ni'];
				$eknazwa	= $dane['ewidencja_komputer_nazwa'];
				$ekopis		= $dane['ewidencja_komputer_opis'];
				$eksn		= $dane['ewidencja_komputer_sn'];
				$ekip		= $dane['ewidencja_komputer_ip'];
				$eke		= $dane['ewidencja_komputer_endpoint'];
				$emo		= $dane['ewidencja_monitor_opis'];
				$emsn		= $dane['ewidencja_monitor_sn'];
				$edo		= $dane['ewidencja_drukarka_opis'];
				$edsn		= $dane['ewidencja_drukarka_sn'];
				$edni		= $dane['ewidencja_drukarka_ni'];
				$eu			= $dane['ewidencja_uwagi'];
				$es			= $dane['ewidencja_status'];
				$eo_id		= $dane['ewidencja_oprogramowanie'];
				$emoduser 	= $dane['ewidencja_modyfikacja_user'];
				$emoddata	= $dane['ewidencja_modyfikacja_date'];
				$ekonf		= $dane['ewidencja_konfiguracja'];
				$egwarancja	= $dane['ewidencja_gwarancja_do'];
				$egwarancjakto	= $dane['ewidencja_gwarancja_kto'];		
				$drukarkapow	= $dane['ewidencja_drukarka_powiaz_z'];

				tbl_tr_highlight($i);

				$i+=1;
			
			echo "<td width=30 class=center>$i</td>";

			$sql77="SELECT rola_nazwa FROM $dbname.serwis_slownik_rola WHERE rola_id='$etyp_id'";
			$result77 = mysql_query($sql77, $conn) or die($k_b);
				
			while ($newArray77 = mysql_fetch_array($result77))
			{
				$rolanazwa	= $newArray77['rola_nazwa'];
			}

		if ($stat=="on") 
		{
			$sqlSTAT = "INSERT INTO $dbname.serwis_temp VALUES ('','$rolanazwa',1)";
			$resultSTAT = mysql_query($sqlSTAT, $conn) or die($k_b);			
		
			if (($edo!="") && ($rolanazwa!='Drukarka')) {
				
				$sqlSTAT = "INSERT INTO $dbname.serwis_temp VALUES ('','Drukarka',1)";
				$resultSTAT = mysql_query($sqlSTAT, $conn) or die($k_b);		
	
			}	
		}		

			if ($esk_rola_check=="on") {
				echo "<td class=nowrap>";
				if ($printpreview==0) { 
						if ($rolanazwa=="Komputer") { echo "<img class=imgoption src=img/komputer.gif border=0 align=absmiddle title=' Komputer ' width=16 width=16>"; $ok=1; }
						if ($rolanazwa=="Serwer") { echo "<img class=imgoption src=img/serwer.gif border=0 align=absmiddle title=' Serwer ' width=16 width=16>"; $ok=1; }
						if ($rolanazwa=="Drukarka") { echo "<img class=imgoption src=img/drukarka.gif border=0 align=absmiddle title=' Drukarka ' width=16 width=16>"; $ok=1; }
						if ($rolanazwa=="Router") { echo "<img class=imgoption src=img//router.gif border=0 align=absmiddle title=' Router ' width=16 width=16>"; $ok=1; }
						if (($rolanazwa=="Switch") || ($rolanazwa=="Hub")) { echo "<img class=imgoption src=img/switch.gif border=0 align=absmiddle title=' Switch ' width=16 width=16>"; $ok=1; }
						if ($rolanazwa=="Notebook") { echo "<img class=imgoption src=img//notebook.gif border=0 align=absmiddle title=' Notebook ' width=16 width=16>"; $ok=1; }
						if ($rolanazwa=="UPS") { echo "<img class=imgoption src=img/ups.gif border=0 align=absmiddle title=' UPS ' width=16 width=16>"; $ok=1; }
				echo "&nbsp;";
				}
			
				echo "$rolanazwa</td>"; 
			}
			
			$sql7="SELECT up_nazwa FROM $dbname.serwis_komorki WHERE (up_id='$eup_id') and (belongs_to=$es_filia)";
			$result7 = mysql_query($sql7, $conn) or die($k_b);
	
			while ($newArray7 = mysql_fetch_array($result7))
			{
			  $upnazwa		= $newArray7['up_nazwa'];
			}
			
			$sql_up = "SELECT up_id, up_pion_id FROM $dbname.serwis_komorki WHERE (up_nazwa='$upnazwa') and (belongs_to=$es_filia) LIMIT 1";
			$wynik = mysql_query($sql_up, $conn) or die($k_b);
			$dane_up = mysql_fetch_array($wynik);
			$temp_up_id = $dane_up['up_id'];
			$temp_pion_id = $dane_up['up_pion_id'];
	
			// nazwa pionu z id pionu
			$sql_get_pion = "SELECT pion_nazwa FROM $dbname.serwis_piony WHERE (pion_id='$temp_pion_id') LIMIT 1";
			$wynik_get_pion = mysql_query($sql_get_pion, $conn) or die($k_b);
			$dane_get_pion = mysql_fetch_array($wynik_get_pion);
			$temp_pion_nazwa = $dane_get_pion['pion_nazwa'];
			// koniec ustalania nazwy pionu
			
	if (($esk_lok_check=="on") || ($esk_nrpok_check=="on") || ($esk_user_check=="on") ) {				
			echo "<td>";
				if ($esk_lok_check=="on") 
				echo "<a class=normalfont title=' Szczegółowe informacje o $temp_pion_nazwa $upnazwa ' onclick=\"newWindow_r(700,600,'p_komorka_szczegoly.php?id=$eup_id')\" href=#><b>$temp_pion_nazwa $upnazwa</b></a>";				
				if ($esk_nrpok_check=="on") {
		   			if ($esk_lok_check=="on") { if ($enrpok!='') echo "&nbsp;/&nbsp;"; 
echo "$enrpok"; }
				 }
				
				if ($esk_user_check=="on") {
if (($esk_lok_check=="on") || ($esk_nrpok_check=="on")) br();
echo "$euser"; 
				}
			echo "</td>";
	}		
// komputer
 	if (($esk_opisk_check=="on") || ($esk_nrsk_check=="on")) { 
		echo "<td>";
		
			if ($esk_opisk_check=="on")	
				if ($_REQUEST[esk_rola]!=3) echo "$ekopis";
			if ($esk_nrsk_check=="on") {
				if ($esk_opisk_check=="on")	br();
				echo "$eksn";
			}
			
		echo "</td>";
	}
	
	if (($esk_nazwak_check=="on") || ($esk_nrip_check=="on")) {			
			echo "<td>";
			if ($esk_nazwak_check=="on") echo "$eknazwa";

			if ($esk_nrip_check=="on") {
				if ($esk_nazwak_check=="on") br();
				if ($rolanazwa!='Drukarka') echo "$ekip";
			}
			echo "</td>";
	}
			
			if ($esk_endpoint_check=="on") {
			
			//echo "<td>$eke</td>";
			echo "<td>";
			
				if ($ekip!='') {
echo "<a title=' Sprawdź czy działa endpoint na komputerze o adresie IP : $ekip ' class=endpoint_s title=' Sprawdź czy działa ten endpoint ' onclick=\"newWindow_r(800,600,'p_endpoint.php?ip=$ekip')\">$eke</a>";
				} else echo "&nbsp;";			
				
			echo "</td>";
			}
			
	if (($esk_nazwam_check=="on") || ($esk_nrsm_check=="on")) {	
			echo "<td>";
			if ($esk_nazwam_check=="on") echo "$emo";
			if ($esk_nrsm_check=="on") {
				if ($esk_nazwam_check=="on") br();
				echo "$emsn";
			}
			echo "</td>";
	}		
				

			$dd = Date('d');
			$mm = Date('m');
			$rr = Date('Y');
			$nazwa_urzadzenia_=$edo;
			$sn_urzadzenia_=$edsn;
			$ni_urzadzenia_=$edni;
				
			// sprawdzenie czy drukarka jest podpiętą do jakiegoś komputera
			if ($rolanazwa=='Drukarka') 
			{
				$sql_d = "SELECT ewidencja_komputer_opis, ewidencja_komputer_ip, ewidencja_drukarka_opis, ewidencja_drukarka_sn FROM $dbname.serwis_ewidencja WHERE ewidencja_id=$drukarkapow LIMIT 1";

				$result_d = mysql_query($sql_d, $conn) or die($k_b);
				$count_rows = mysql_num_rows($result_d);

				if ($count_rows>0)
				{
					$info_1 = mysql_fetch_array($result_d);	
					$nazwa_k	= $info_1['ewidencja_komputer_opis'];
					$ip_k		= $info_1['ewidencja_komputer_ip'];
				}

			} 
			else
			{
			// sprawdzenie czy komputer ma podpiętą drukarkę

			$sql_d = "SELECT ewidencja_drukarka_opis, ewidencja_drukarka_sn FROM $dbname.serwis_ewidencja WHERE ewidencja_drukarka_powiaz_z=$eid LIMIT 1";

			$result_d = mysql_query($sql_d, $conn) or die($k_b);
			$count_rows_k = mysql_num_rows($result_d);

			if ($count_rows_k>0)
				{
$info_1 = mysql_fetch_array($result_d);	
$nazwa_d	= $info_1['ewidencja_drukarka_opis'];
$sn_d		= $info_1['ewidencja_drukarka_sn'];
				}
			// ====
			}
				
			if ($esk_nrinwz_check=="on") echo "<td class=nowrap>$enizest</td>";

			if ($esk_nazwad_check=="on") {
		if ($rolanazwa=='Drukarka') {
			$result_d1 = mysql_query("SELECT ewidencja_komputer_opis, ewidencja_komputer_ip FROM $dbname.serwis_ewidencja WHERE ewidencja_id=$drukarkapow LIMIT 1", $conn) or die($k_b);
			$count_rows = mysql_num_rows($result_d1);
			if ($count_rows>0) list($nazwa_k,$ip_k)=mysql_fetch_array($result_d1);
		} else {
			// sprawdzenie czy komputer ma podpiętą drukarkę
			$result_d2 = mysql_query("SELECT ewidencja_drukarka_opis, ewidencja_drukarka_sn FROM $dbname.serwis_ewidencja WHERE ewidencja_drukarka_powiaz_z=$eid LIMIT 1", $conn) or die($k_b);
			$count_rows_k = mysql_num_rows($result_d2);
			if ($count_rows_k>0) list($nazwa_d,$sn_d)=mysql_fetch_array($result_d2);	
		}
		td_(";nw");
			if (($drukarkapow>0) and ($rolanazwa=='Drukarka')) { 
				echo "<a title=' Drukarka jest podłączona do komputera $nazwa_k ($ip_k) '>";
				echo "$edo";	
				echo "</a>";
			} else {
				echo "$edo";
				if (($ekip!='') && ($rolanazwa=='Drukarka')) echo "<br /><sub>$ekip</sub>";
			}
			if ($rolanazwa!='Drukarka') {
				if (($count_rows_k>0) && ($printpreview==0)) {
					echo "<a title=' Do tego komputera podłączona jest drukarka $nazwa_d ($sn_d) '>";
					echo "<input class=imgoption type=image align=absmiddle src=img/link.gif onclick=\"newWindow_r(620,600,'p_ewidencja_szczegoly.php?id=$eid&ew_action=$ew_action')\">";
					echo "</a>";
				}
			}
			if (($drukarkapow>0) && ($printpreview==0)) {
				echo "<input class=imgoption type=image align=absmiddle src=img/link.gif onclick=\"newWindow_r(620,600,'p_ewidencja_szczegoly.php?id=$eid&ew_action=$ew_action')\">";
			}
		_td();
			
			
			
			}

	if (($esk_nrsd_check=="on") || ($esk_nrinwd_check=="on")) {	
			echo "<td class=nowrap>";
			if ($esk_nrsd_check=="on") echo "$edsn";
			if ($esk_nrinwd_check=="on") {
				if ($esk_nrsd_check=="on") br();
				echo "$edni";
			}
			echo "</td>";
	}

			if ($esk_konf_check=="on") 
				{
echo "<td>"; 
if ($ekonf=='0') { echo "&nbsp;"; } else echo "$ekonf";
echo "</td>";
			}
			
if ($esk_uwagi_check=="on") {
	$uwagisa = ($eu!='');
echo "<td class=center>";
if ($uwagisa=='1') {
	echo "<a><input class=imgoption type=image src=img/comment.gif onclick=\"newWindow(480,265,'p_ewidencja_uwagi.php?id=$eid')\"></a>";
}
echo "<a title=' Edytuj uwagi '><input class=imgoption type=image src=img/edit_comment.gif onclick=\"newWindow(480,260,'e_ewidencja_uwagi.php?id=$eid')\"></a>";

$dddd = Date('Y-m-d H:i:s');

if (($printpreview==0) && ($egwarancja!='0000-00-00') && ($egwarancja>=$dddd)) { 

	echo "<a title=' Gwarancja do $egwarancja '><input class=imgoption type=image src=img/gwarancja.gif ";
	if ($egwarancjakto>-1) echo "onclick=\"newWindow(600,250,'p_firma_serwisowa_szczegoly.php?id=$egwarancjakto')\"";
echo "></a>";
}	

echo "</td>";
			}
				
			if ($esk_status_check=="on") {
				if ($es=='-1') echo "<td class=center><a title=' Sprzęt pobrany od klienta ' href=main.php?action=npus&id=$mid&cs=$es><img class=imgoption src=img/snapraw_p.gif border=0 width=16 width=16></a></td>";
				if ($es=='0') echo "<td class=center><a title=' Sprzęt jest naprawiany we własnym zakresie ' href=main.php?action=npus&id=$mid&cs=$es><img class=imgoption src=img/w_naprawie_wz.gif border=0 width=16 width=16></a></td>";
				if ($es=='1') echo "<td class=center><a title=' Sprzęt jest w naprawie w serwisie zewnętrznym ' href=main.php?action=npswsz&id=$mid&cs=$es><img class=imgoption src=img/w_naprawie_sz.gif border=0 width=16 width=16></a></td>";
				if ($es=='2') echo "<td class=center><a title=' Sprzęt jest w naprawie w serwisie lokalnym ' href=main.php?action=npswsz&id=$mid&cs=$es><img class=imgoption src=img/w_naprawie_sl.gif border=0 width=16 width=16></a></td>";
				if ($es=='3') echo "<td class=center><a title=' Sprzęt wrócił z naprawy ' href=p_naprawy_zakonczone.php?id=$eid&cs=$es><img class=imgoption src=img/snapraw_ok.gif border=0 width=16 width=16></a></td>";
			}
			
			if ($esk_opcje_check=="on") {
				echo "<td class=center>";

if (($ew_action=='move') || ($ew_action=='admin')) {
	echo "<a title=' Przesunięcie sprzętu '><input class=imgoption type=image src=img/przesuniecie.gif  onclick=\"newWindow_r(700,586,'ew_przesuniecie.php?id=$eid')\"></a>";
}

if (($ew_action=='delete') || ($ew_action=='admin')) {
	echo "<a title=' Likwidacja sprzętu '><input class=imgoption type=image src=img/likwidacja.gif  onclick=\"newWindow_r(700,586,'ew_likwidacja.php?id=$eid')\"></a>";
}

if (($ew_action=='change') || ($ew_action=='admin')) {
	echo "<a title=' Remont sprzętu '><input class=imgoption type=image src=img//remont.gif  onclick=\"newWindow_r(700,586,'ew_remont.php?id=$eid')\"></a>";
}

if (($ew_action!='move') && ($ew_action!='delete') && ($ew_action!='change')) {

// -
// access control 
$accessLevels = array("0","1","9");
if(array_search($es_prawa, $accessLevels)>-1){

echo "<a title=' Popraw dane o wybranym sprzęcie '><input class=imgoption type=image src=img/edit.gif  onclick=\"newWindow_r(820,600,'e_ewidencja.php?id=$eid')\"></a>";		
}
// access control koniec
// -

// -
// access control 
$accessLevels = array("0","1","9");
if(array_search($es_prawa, $accessLevels)>-1){
echo "<a title='Popraw wszystkie dane o wybranym sprzęcie (f-cja tylko dla Administratorów)'><input class=imgoption type=image src=img/edita.gif onclick=\"newWindow_r(820,600,'e_ewidencja.php?id=$eid&edittype=admin')\"></a>";
}

// access control koniec
// -

if ($es==-1) { echo "<a title=' Sprzęt pobrany od klienta ' href=main.php?action=npus&id=$mid&cs=$es><img class=imgoption src=img/snapraw_p.gif border=0 width=16 width=16></a>"; }
if ($es==0) { echo "<a title=' Sprzęt jest naprawiany we własnym zakresie ' href=main.php?action=npus&id=$mid&cs=$es><img class=imgoption src=img/w_naprawie_wz.gif border=0 width=16 width=16></a>"; }
if ($es==1) { echo "<a title=' Sprzęt jest w naprawie w serwisie zewnętrznym ' href=main.php?action=npswsz&id=$mid&cs=$es><img class=imgoption src=img/w_naprawie_sz.gif border=0 width=16 width=16></a>"; }
if ($es==2) { echo "<a title=' Sprzęt jest w naprawie w serwisie lokalnym ' href=main.php?action=npswsz&id=$mid&cs=$es><img class=imgoption src=img/w_naprawie_sl.gif border=0 width=16 width=16></a>"; }
if ($es==3) { echo "<a title=' Sprzęt wrócił z naprawy ' href=main.php?action=npns&id=$eid&cs=$es><img class=imgoption src=img/snapraw_ok.gif border=0 width=16 width=16></a>"; } 

// -
// access control 
$accessLevels = array("9");
if(array_search($es_prawa, $accessLevels)>-1){				
	if (($es==3) || ($es==1)) { 
echo "<a title=' Usuń sprzęt z ewidencji (nierejestrowane) '><input class=imgoption type=image src=img/delete.gif  onclick=\"newWindow(600,170,'u_ewidencja.php?id=$eid')\"></a>"; 
}
}
// access control koniec
// -
if (($rolanazwa=="Komputer") || ($rolanazwa=="Serwer") || ($rolanazwa=="Notebook")) {			
	$wynik_czyjest	 = mysql_num_rows(mysql_query("SELECT oprogramowanie_id FROM $dbname.serwis_oprogramowanie WHERE oprogramowanie_ewidencja_id=$eid",$conn));
	if ($wynik_czyjest>0) { 
		echo "<a title=' Pokaż oprogramowanie zainstalowane na tym sprzęcie '><input class=imgoption type=image src=img/software.gif onclick=\"newWindow_r(600,600,'p_oprogramowanie.php?id=$eid')\"></a>";
	} else {
		echo "<a title=' Pokaż oprogramowanie zainstalowane na tym sprzęcie '><input class=imgoption type=image src=img/software_none.gif onclick=\"newWindow_r(600,600,'p_oprogramowanie.php?id=$eid')\"></a>";
	}
}
echo "<a title=' Szczegółowe informacje o sprzęcie i oprogramowaniu '><input class=imgoption type=image src=img/detail.gif  onclick=\"newWindow_r(620,600,'p_ewidencja_szczegoly.php?id=$eid')\"></a>";

$dd = Date('d');
$mm = Date('m');
$rr = Date('Y');

if (($rolanazwa=="Komputer") || ($rolanazwa=="Serwer") || ($rolanazwa=="Notebook")) {

	$nazwa_urzadzenia_=$ekopis;
	$sn_urzadzenia_=$eksn;
	$ni_urzadzenia_=$enizest;

}

if ($rolanazwa=="Drukarka"){

	$nazwa_urzadzenia_=$edo;
	$sn_urzadzenia_=$edsn;
	$ni_urzadzenia_=$edni;

}
//if ($rolanazwa!="Drukarka") {

$accessLevels = array("9");

if(array_search($es_prawa, $accessLevels)>-1){
echo "<a title=' Generuj protokół dla wybranego sprzętu '><input class=imgoption type=image src=img/print.gif  onclick=\"newWindow_r(700,595,'utworz_protokol.php?dzien=$dd&miesiac=$mm&rok=$rr&c_1=&c_2=&c_3=&c_4=&c_5=&c_6=&c_7=&c_8=&up=".urlencode($upnazwa)."&nazwa_urzadzenia=".urlencode($nazwa_urzadzenia_)."&sn_urzadzenia=".urlencode($sn_urzadzenia_)."&ni_urzadzenia=".urlencode($ni_urzadzenia_)."&opis_urzadzenia=&wykonane_czynnosci=&uwagi=&imieinazwisko=&readonly=0&findpion=1&upid=$eup_id')\"></a>"; 

}


//}

echo "</td>";	
	}		
}
echo "</tr>";
}
	endtable();
	
// paging_end
echo "<br />";
include_once('paging_end_filtruj.php');
// paging_end
		
		if (($printpreview==0) && ($stat=="on") && ($showall==1))
		{	
			br();
			pageheader("Podsumowanie");

			$sql77="SELECT * FROM $dbname.serwis_slownik_rola WHERE rola_do_ewidencji='1'";
			$result77 = mysql_query($sql77, $conn) or die($k_b);
			
			
			echo "<table cellspacing=1 align=center style=width:400px>";
			echo "<tr><td>Typ sprzętu</td><td class=center>Ilość</td></tr>";
			$i=0;
				
			while ($newArray77 = mysql_fetch_array($result77))
			{


		  	$i+=1;
			
			  $rolaid			= $newArray77['rola_id'];	
			  $rolanazwa		= $newArray77['rola_nazwa'];
			  
				
				$sql88="SELECT * FROM $dbname.serwis_temp WHERE (temp_nazwa='$rolanazwa')";
				$result88 = mysql_query($sql88, $conn) or die($k_b);			  
			    $count_rows = mysql_num_rows($result88);
				
				if ($count_rows!=0) {
tbl_tr_highlight($i);
echo "<td>$rolanazwa</td><td class=center><b>";
if ($count_rows==0) { echo "</b>"; }
echo "$count_rows";
if ($count_rows!=0) { echo "</b>"; }
			
echo "</td>";
echo "</tr>";

}
}

endtable();
}
} else pageheader("Nie ma żadnego sprzętu spełniającego podane warunki");

if ($printpreview==0) {
startbuttonsarea("right");
// -
// access control 
$accessLevels = array("1","9");
if(array_search($es_prawa, $accessLevels)>-1){
addbuttons("dodajsprzet");
} 
// access control koniec
// -
addlinkbutton("'Nowy widok użytkownika'","p_ewidencja_uzytkownika.php");
addbuttons("start");
endbuttonsarea();

}
?>
<?php include('body_stop.php'); ?>

<script>HideWaitingMessage();</script>

</body>
</html>