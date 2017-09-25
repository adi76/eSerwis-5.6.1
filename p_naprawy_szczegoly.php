<?php include_once('header.php'); ?>
<body>
<?php include('body_start.php');
$result1 = mysql_query("SELECT * FROM $dbname.serwis_naprawa WHERE naprawa_id=$id LIMIT 1", $conn) or die($k_b);
if (mysql_num_rows($result1)!=0) {
	$dane = mysql_fetch_array($result1);
	$mid 		= $dane['naprawa_id'];						$mnazwa 	= $dane['naprawa_nazwa'];
	$mmodel		= $dane['naprawa_model'];					$msn	 	= $dane['naprawa_sn'];
	$mni		= $dane['naprawa_ni'];						$muwagisa	= $dane['naprawa_uwagi_sa'];
	$muwagi		= $dane['naprawa_uwagi'];					$mup		= $dane['naprawa_pobrano_z'];
	$moo		= $dane['naprawa_osoba_pobierajaca'];		$mdp		= $dane['naprawa_data_pobrania'];
	$mnfs		= $dane['naprawa_fs_nazwa'];				$mnfk		= $dane['naprawa_fk_nazwa'];
	$mnow		= $dane['naprawa_osoba_wysylajaca'];		$mndw		= $dane['naprawa_data_wysylki'];
	$mnnlp		= $dane['naprawa_nr_listu_przewozowego'];	
	$mnptn		= $dane['naprawa_przewidywany_termin_naprawy'];		
	$mnopszs	= $dane['naprawa_osoba_przyjmujaca_sprzet_z_serwisu'];
	$mndozs		= $dane['naprawa_data_odbioru_z_serwisu'];	$mnoos		= $dane['naprawa_osoba_oddajaca_sprzet'];
	$mndos		= $dane['naprawa_data_oddania_sprzetu'];	$mstatus	= $dane['naprawa_status'];
	$mpwzs = $dane['naprawa_powod_wycofania_z_serwisu'];
	$mdwzs = $dane['naprawa_data_wycofania'];
	$mowzs = $dane['naprawa_osoba_wycofujaca_sprzet_z_serwisu'];
	$mtswzs = $dane['naprawa_wycofanie_timestamp'];
	$bt = $dane['belongs_to'];
		
	$n_przekaz_do = $dane['naprawa_przekazanie_naprawy_do'];
	$n_przekaz_data = $dane['naprawa_przekazanie_naprawy_data'];
	$n_przekaz_osoba = $dane['naprawa_przekazanie_naprawy_osoba'];
	$n_odbior_data = $dane['naprawa_odbior_z_filii_data'];
	$n_odbior_osoba = $dane['naprawa_odbior_z_filii_osoba'];		
		
	$n_przekazanie_zakonczone = $dane['naprawa_przekazanie_zakonczone'];		
	$n_przekazanie_naprawa_wykonana = $dane['naprawa_przekazanie_naprawa_wykonana'];
	
	pageheader("Szczegółowe informacje o naprawie",1,0);
	starttable();
		tbl_empty_row(2);
		tr_();
			td("220;r;Nazwa sprzętu, model|;l;<b>".$mnazwa.", ".$mmodel."</b>");
		_tr();
		tr_();
			td("220;r;Numer seryjny, NI|;l;<b>".$msn.", ".$mni."</b>");
		_tr();
		if ($muwagisa==1) {
			tr_();
				td("220;rt;Uwagi|;w;<b>".$muwagi."</b>");
			_tr();
		}
				
		tr_();
		
			$sql_up = "SELECT up_id, up_pion_id FROM $dbname.serwis_komorki WHERE (up_nazwa='$mup') and (belongs_to=$es_filia) LIMIT 1";
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
	
			td("220;r;Sprzęt pobrano z|;l;<b>".$temp_pion_nazwa." ".$mup."</b>");
		_tr();
		
		tbl_empty_row(2);
		tr_();
			$result = mysql_query("SELECT sn_nazwa FROM $dbname.serwis_sposob_naprawy WHERE (sn_id='$mstatus') LIMIT 1", $conn) or die($k_b);
			list($mstatus_opis)=mysql_fetch_array($result);
			
			td("220;r;Status naprawy|;l;<b>".$mstatus_opis."</b>");
		_tr();
		
		tbl_empty_row(2);
		tr_();
			td("220;r;Przyjęto na serwis, data przyjęcia|;l;<b>".$moo.", ".substr($mdp,0,16)."</b>");
		_tr();
		
		if ($n_przekaz_do!=0) {
			tbl_empty_row(1);
			tr_();
				$r40 = mysql_query("SELECT filia_nazwa FROM $dbname.serwis_filie WHERE (filia_id='$n_przekaz_do') LIMIT 1", $conn) or die($k_b);
				list($NazwaFilii)=mysql_fetch_array($r40);			
				td("220;r;Przekazanie do innej filii|;l;<b>".$NazwaFilii."</b>");
			_tr();
			tr_();
				td("220;r;Osoba przekazujące, data przekazania|;l;<b>".$n_przekaz_osoba.", ".substr($n_przekaz_data,0,16)."</b>");
			_tr();
			tbl_empty_row(1);			
		}
		
		if ($mnwwzo!='') {
			tr_();
				td("220;r;Naprawa we własnym zakresie, data zmiany statusu|;l;<b>".$mnwwzo.", ".substr($mnwwzd,0,16)."</b>");
			_tr();
		}
		if ($mnow!='') {
			tr_();
				td("220;r;Wysyłka do serwisu, data wysyłki|;l;<b>".$mnow.", ".substr($mndw,0,16)."</b>");
			_tr();	
			tr_();
				td("220;r;Nazwa serwisu");
				td_(";l;<b>".$mnfs."</b>");
				$result = mysql_query("SELECT fz_id,fz_nazwa,fz_adres,fz_telefon,fz_fax,fz_email,fz_www FROM $dbname.serwis_fz WHERE ((fz_is_fs='on') and (fz_nazwa='$mnfs')) LIMIT 1", $conn) or die($k_b);
				list($temp_id,$temp_nazwa,$temp_adres,$temp_telefon,$temp_fax,$temp_email,$temp_www)=mysql_fetch_array($result);
				if (trim($temp_telefon)!='') echo " <b>tel. $temp_telefon</b>";
				_td();
			_tr();
			if (($mnfk!='') && ($mnfk!='-1')) {
				td("220;r;Nazwa firmy kurierskiej, nr listu przewozowego|;l;<b>".$mnfk.", ".$mnnlp."</b>");
			}
		}
		if ($mnptn!='0000-00-00 00:00:00') {
			tr_();
				td("220;r;Przewidywany termin naprawy|;l;<b>".substr($mnptn,0,16)."</b>");
			_tr();
		}
		
		if ($mpwzs!='') {
			tr_();
				td("220;r;Wycofanie sprzętu z serwisu, data wycofania|;l;<b>".$mowzs.", ".$mtswzs." (wycofanie z datą: ".substr($mdwzs,0,16).")</b>");
			_tr();		
		}

		if ($mnopszs!='') {
			tr_();
				td("220;r;Odbiór naprawionego sprzętu, data odbioru|;l;<b>".$mnopszs.", ".substr($mndozs,0,16)."</b>");
			_tr();
		}
				
		if ($n_odbior_data!='0000-00-00') {
			tbl_empty_row(1);
			tr_();
				$r40 = mysql_query("SELECT filia_nazwa FROM $dbname.serwis_filie WHERE (filia_id='$n_przekaz_do') LIMIT 1", $conn) or die($k_b);
				list($NazwaFilii)=mysql_fetch_array($r40);		
				$r41 = mysql_query("SELECT filia_nazwa FROM $dbname.serwis_filie WHERE (filia_id='$bt') LIMIT 1", $conn) or die($k_b);
				list($NazwaFiliiZrodlowej)=mysql_fetch_array($r41);	
				
				if ($n_przekazanie_naprawa_wykonana==1) {
					td("220;r;Zwrot naprawionego sprzętu do filii źródłowej|;l;<b>".$NazwaFiliiZrodlowej."</b>");
				} else {
					td("220;r;Zwrot nienaprawionego sprzętu do filii źródłowej|;l;<b>".$NazwaFiliiZrodlowej."</b>");
				}
				
			_tr();
			tr_();
				td("220;r;Osoba zwracająca sprzęt z filii/oddziału, data zwrotu|;l;<b>".$n_odbior_osoba.", ".substr($n_odbior_data,0,16)."</b>");
			_tr();			
		}		
			
		if ($mnoos!='') {
			tr_();
				td("220;r;Przekazano do klienta, data przekazania|;l;<b>".$mnoos.", ".substr($mndos,0,16)."</b>");
			_tr();
		}
	tbl_empty_row(2);
		
	endtable();
} else errorheader("Brak informacji o naprawie");
startbuttonsarea("right");
addbuttons("zamknij");
endbuttonsarea();
include('body_stop.php'); 
?>
</body>
</html>