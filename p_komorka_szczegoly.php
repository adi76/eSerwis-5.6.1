<?php include_once('header.php'); ?>
<body>
<?php 
$innafilia=false;
if ($_REQUEST[komorka]!='') {
	if ($_REQUEST[f]>0) {
		if (($_REQUEST[f]!=$es_filia)) {
			$r44 = mysql_query("SELECT up_id FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((serwis_komorki.belongs_to=$_REQUEST[f]) and (serwis_piony.pion_id=serwis_komorki.up_pion_id) and ((CONCAT(UPPER(serwis_piony.pion_nazwa),' ',UPPER(serwis_komorki.up_nazwa)))='$_REQUEST[komorka]'))", $conn) or die($k_b);
			$innafilia = true;
		} else {
			$r44 = mysql_query("SELECT up_id FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((serwis_komorki.belongs_to=$es_filia) and (serwis_piony.pion_id=serwis_komorki.up_pion_id) and ((CONCAT(UPPER(serwis_piony.pion_nazwa),' ',UPPER(serwis_komorki.up_nazwa)))='$_REQUEST[komorka]'))", $conn) or die($k_b);	
		}
	} else {
		$r44 = mysql_query("SELECT up_id FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((serwis_komorki.belongs_to=$es_filia) and (serwis_piony.pion_id=serwis_komorki.up_pion_id) and ((CONCAT(UPPER(serwis_piony.pion_nazwa),' ',UPPER(serwis_komorki.up_nazwa)))='$_REQUEST[komorka]'))", $conn) or die($k_b);	
	}
	list($id)=mysql_fetch_array($r44);
}

$sql1 = "SELECT * FROM $dbname.serwis_komorki WHERE (up_id='$id')";
$result1 = mysql_query($sql1, $conn) or die($k_b);

if (mysql_num_rows($result1)!=0) {
	$dane = mysql_fetch_array($result1);
	$mid 			= $dane['up_id'];
	$mnazwa 		= $dane['up_nazwa'];
	$madres			= $dane['up_adres'];
	$mopis			= $dane['up_opis'];
	$mip			= $dane['up_ip'];
	$mnrwanportu	= $dane['up_nrwanportu'];
	$mstempel 		= $dane['up_stempel'];
	$mtelefon		= $dane['up_telefon'];
	$temp_pion_id	= $dane['up_pion_id'];
	$temp_umowa_id	= $dane['up_umowa_id'];	
	$uptyp			= $dane['up_typ'];
	$temp_kategoria	= $dane['up_kategoria'];
	$temp_ipserwera = $dane['up_ipserwera'];
	$temp_up_przypisanie_jednostki = $dane['up_przypisanie_jednostki'];
	
	$temp_komorka_macierzysta = $dane['up_komorka_macierzysta_id'];
	
	$temp_working_time		= $dane['up_working_time'];	
	$temp_working_time_alt	= $dane['up_working_time_alternative'];	
	$temp_working_time_start_date	= $dane['up_working_time_alternative_start_date'];		
	$temp_working_time_stop_date	= $dane['up_working_time_alternative_stop_date'];		

	
	$sql_1 = "SELECT pion_nazwa FROM $dbname.serwis_piony WHERE (pion_id=$temp_pion_id) LIMIT 1";
	$wynik = mysql_query($sql_1,$conn) or die($k_b);

	if (mysql_num_rows($wynik)>0) {
		$pion1 = mysql_fetch_array($wynik);
		$pionnazwa = $pion1['pion_nazwa']; 
	} else $pionnazwa='';

	$sql_1 = "SELECT * FROM $dbname.serwis_umowy WHERE (belongs_to=$es_filia) and (umowa_id IN (".$temp_umowa_id."))";
	$wynik = mysql_query($sql_1,$conn) or die($k_b);
	while ($umowa1 = mysql_fetch_array($wynik)) {
		$unr = $umowa1['umowa_nr']; 
		$uopis = $umowa1['umowa_opis'];
		$umowanrinazwa.=$uopis.' ('.$unr.')<br />';
	}

	pageheader("Szczegółowe informacje o UP/komórce");
	
	if ($innafilia) {
		$sql44aa="SELECT filia_nazwa FROM $dbname.serwis_filie WHERE filia_id=$_REQUEST[f]";
		$result44aa = mysql_query($sql44aa, $conn) or die($k_b);
		
		$newArray44aa = mysql_fetch_array($result44aa);
		$temp_fn = $newArray44aa['filia_nazwa'];
		
		okheader("Komórka podległa pod filię <b>".$temp_fn."</b>");
	}
	
	starttable();
	$temp_kategoria5='-';
	if ($temp_kategoria=='1') $temp_kategoria5='I';
	if ($temp_kategoria=='2') $temp_kategoria5='II';
	if ($temp_kategoria=='3') $temp_kategoria5='III';
	if ($temp_kategoria=='4') $temp_kategoria5='IV';
	if ($temp_kategoria=='5') $temp_kategoria5='V';
	
		tbl_empty_row();
		if ($pionnazwa!='') { tr_(); td("180;r;Pion|;l;<b>".$pionnazwa."</b>"); _tr(); }
		if ($mnazwa!='') { tr_(); td(";r;Nazwa UP/Komórki|;l;<b>".$mnazwa."</b>"); _tr(); }
		if ($temp_kategoria!='-') { tr_(); td(";r;Kategoria|;l;<b>".$temp_kategoria5."</b>"); _tr(); }
		if ($madres!='') { tr_(); td(";r;Adres|;l;<b>".$madres."</b>"); _tr(); }
		if ($mstempel!='') { tr_(); td(";r;Stempel okręgowy|;l;<b>".$mstempel."</b>"); _tr(); }
		if ($mopis!='') { tr_(); td(";r;Opis|;w;<b>".$mopis."</b>"); _tr(); }
		if ($mtelefon!='') { tr_(); td(";r;Telefon|;l;<b>".$mtelefon."</b>"); _tr(); }
		if ($mnrwanportu!='') { tr_(); td("100;r;Nr WAN-portu|;l;<b>".$mnrwanportu."</b>"); _tr(); }
		if ($mip!='') {	tr_(); td(";r;Podsieć|;l;<b>".$mip."</b>"); _tr(); }
		if ($temp_ipserwera!='') {	tr_(); td(";r;Adres IP serwera|;l;<b>".$mip.".".$temp_ipserwera."</b>"); _tr(); }
		
		if ($umowanrinazwa!='') { tr_(); td(";rt;Podlega pod umowę(y)|;l;<b>".$umowanrinazwa."</b>"); _tr(); }
		if ($temp_up_przypisanie_jednostki!='') { tr_(); td(";rt;Przypisanie do załącznika umowy|;l;<b>".$temp_up_przypisanie_jednostki."</b>"); _tr(); }
		
		if ($temp_komorka_macierzysta>0) { 
			tbl_empty_row();
			$sql_99 = "SELECT up_id, up_nazwa, pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.belongs_to=$es_filia) and (serwis_piony.pion_id=serwis_komorki.up_pion_id) and (serwis_komorki.up_id=$temp_komorka_macierzysta) LIMIT 1";
			
			$wynik = mysql_query($sql_99,$conn) or die($k_b);
			list($km_id, $km_nazwa, $km_pion) = mysql_fetch_array($wynik);
				
			tr_(); td(";rt;<font color=blue>Komórka macierzysta</font>|;l;<b>".$km_pion." ".$km_nazwa."</b>"); _tr(); 
		}
		
					$days = explode(";",$temp_working_time);
			
			$oneday1 = explode("@",$days[0]); 
			$oneday2 = explode("@",$days[1]); 
			$oneday3 = explode("@",$days[2]); 
			$oneday4 = explode("@",$days[3]); 
			$oneday5 = explode("@",$days[4]); 
			$oneday6 = explode("@",$days[5]); 
			$oneday7 = explode("@",$days[6]); 

			$gp_sa = 1;
			if (($oneday1[1]=='') && ($oneday2[1]=='') && ($oneday3[1]=='') && ($oneday4[1]=='') && ($oneday5[1]=='') && ($oneday6[1]=='') && ($oneday7[1]=='')) $gp_sa = 0;
			
			if ($oneday1[1]=='') $oneday1[1] = '-';
			if ($oneday2[1]=='') $oneday2[1] = '-';
			if ($oneday3[1]=='') $oneday3[1] = '-';
			if ($oneday4[1]=='') $oneday4[1] = '-';
			if ($oneday5[1]=='') $oneday5[1] = '-';
			if ($oneday6[1]=='') $oneday6[1] = '-';
			if ($oneday7[1]=='') $oneday7[1] = '-';
			
			// menu z godzinami pracy
			$opis_stanow = '<table>';
			$opis_stanow.= '<tr height=24><td colspan=2 class=center style=background-color:#FFFF7F><font color=black>Godziny pracy</b></font></td></tr>';
			
			$opis_stanow .= '<tr><td class=right width=100>Poniedziałek:</td><td><b>'.$oneday1[1].'</b></td></tr>';
			$opis_stanow .= '<tr><td class=right>Wtorek</td><td><b>'.$oneday2[1].'</b></td></tr>';
			$opis_stanow .= '<tr><td class=right>Środek:</td><td><b>'.$oneday3[1].'</b></td></tr>';
			$opis_stanow .= '<tr><td class=right>Czwartek:</td><td><b>'.$oneday4[1].'</b></td></tr>';
			$opis_stanow .= '<tr><td class=right>Piątek:</td><td><b>'.$oneday5[1].'</b></td></tr>';
			$opis_stanow .= '<tr><td class=right>Sobota:</td><td><b>'.$oneday6[1].'</b></td></tr>';
			$opis_stanow .= '<tr><td class=right>Niedziela:</td><td><b>'.$oneday7[1].'</b></td></tr>';
			$opis_stanow.= '</table>';
			
			
			if (($temp_working_time_start_date!='0000-00-00') && ($temp_working_time_stop_date!='0000-00-00')) {

				$days = explode(";",$temp_working_time_alt);
				
				$oneday1a = explode("@",$days[0]); 
				$oneday2a = explode("@",$days[1]); 
				$oneday3a = explode("@",$days[2]); 
				$oneday4a = explode("@",$days[3]); 
				$oneday5a = explode("@",$days[4]); 
				$oneday6a = explode("@",$days[5]); 
				$oneday7a = explode("@",$days[6]); 

				$gpa_sa = 1;
				if (($oneday1a[1]=='') && ($oneday2a[1]=='') && ($oneday3a[1]=='') && ($oneday4a[1]=='') && ($oneday5a[1]=='') && ($oneday6a[1]=='') && ($oneday7a[1]=='')) $gpa_sa = 0;
				
				
				if ($oneday1a[1]=='') $oneday1a[1] = '-';
				if ($oneday2a[1]=='') $oneday2a[1] = '-';
				if ($oneday3a[1]=='') $oneday3a[1] = '-';
				if ($oneday4a[1]=='') $oneday4a[1] = '-';
				if ($oneday5a[1]=='') $oneday5a[1] = '-';
				if ($oneday6a[1]=='') $oneday6a[1] = '-';
				if ($oneday7a[1]=='') $oneday7a[1] = '-';
				
				
				$alt_od = date('Y')."-".substr($temp_working_time_start_date,5,5);
				$alt_do = date('Y')."-".substr($temp_working_time_stop_date,5,5);
				
				// menu z godzinami pracy
				$opis_stanow .= '<table>';
				$opis_stanow .= '<tr height=24><td colspan=2 class=center style=background-color:#FFAA7F><font color=black>Godziny pracy (alternatywne)</b><br />obowiązują od: <b>'.$alt_od.' - '.$alt_do.'</b></font></td></tr>';
				
				$opis_stanow .= '<tr><td class=right width=100>Poniedziałek:</td><td><b>'.$oneday1a[1].'</b></td></tr>';
				$opis_stanow .= '<tr><td class=right>Wtorek</td><td><b>'.$oneday2a[1].'</b></td></tr>';
				$opis_stanow .= '<tr><td class=right>Środek:</td><td><b>'.$oneday3a[1].'</b></td></tr>';
				$opis_stanow .= '<tr><td class=right>Czwartek:</td><td><b>'.$oneday4a[1].'</b></td></tr>';
				$opis_stanow .= '<tr><td class=right>Piątek:</td><td><b>'.$oneday5a[1].'</b></td></tr>';
				$opis_stanow .= '<tr><td class=right>Sobota:</td><td><b>'.$oneday6a[1].'</b></td></tr>';
				$opis_stanow .= '<tr><td class=right>Niedziela:</td><td><b>'.$oneday7a[1].'</b></td></tr>';
				$opis_stanow.= '</table>';
			
			}
			tbl_empty_row();
			tr_();
				echo "<td class=righttop>";
//				echo "<td>";
					echo "<b>Godziny pracy</b>";
				echo "</td>";
				echo "<td>";
//				echo "<td>";
					echo $opis_stanow;
				echo "</td>";
			_tr();
			
			
		tbl_empty_row();
		
	endtable();
	
	if ($_REQUEST[f]>0) {
		if (($_REQUEST[f]!=$es_filia)) {
			$sql_99 = "SELECT up_id, up_nazwa, pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.belongs_to=$_REQUEST[f]) and (serwis_komorki.up_komorka_macierzysta_id=$mid) and (serwis_komorki.up_active=1) and (serwis_komorki.up_pion_id=serwis_piony.pion_id)";	
		} else {
			$sql_99 = "SELECT up_id, up_nazwa, pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.belongs_to=$es_filia) and (serwis_komorki.up_komorka_macierzysta_id=$mid) and (serwis_komorki.up_active=1) and (serwis_komorki.up_pion_id=serwis_piony.pion_id)";	
		}
	} else {
		$sql_99 = "SELECT up_id, up_nazwa, pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.belongs_to=$es_filia) and (serwis_komorki.up_komorka_macierzysta_id=$mid) and (serwis_komorki.up_active=1) and (serwis_komorki.up_pion_id=serwis_piony.pion_id)";	
	}
	
	// zlicz czy ma jakieś agencje/filie podległe (aktywne)

	$wynik = mysql_query($sql_99,$conn) or die($k_b);
		
	$podlegle = mysql_num_rows($wynik);
	//list($km_id, $km_nazwa, $km_pion) = mysql_fetch_array($wynik);
			
	if ($podlegle>0) {
		starttable();
		tbl_empty_row();
		pageheader("Komórki podległe");
			
			while ($newArray = mysql_fetch_array($wynik)) {
				$temp_id  				= $newArray['up_id'];
				$temp_nazwa				= $newArray['up_nazwa'];
				$temp_pion				= $newArray['pion_nazwa'];
								
				echo "<tr><td class=center><a class=normalfont title=' Szczegółowe informacje o $temp_pion $temp_nazwa ' onclick=\"newWindow(700,600,'p_komorka_szczegoly.php?id=$temp_id'); return false;\" href=#>".$temp_pion." ".$temp_nazwa."</a></td></tr>";
			
			}
			tbl_empty_row();
		endtable();
			
	}
			
} else errorheader("Brak danych o komórce");
startbuttonsarea("right");
addbuttons("zamknij");
endbuttonsarea();
?>
</body>
</html>