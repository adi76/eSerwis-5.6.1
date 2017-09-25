<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
session_start();
?>
<body>
<?php 	

if ($_REQUEST[f]!='') {	

	if ($_REQUEST[todiv]!=1) {
		errorheader("Nie zamykaj tego okna");
		echo "<div id=TrwaLadowanie style=\"color:white; font-weight:normal; text-align:center; font-size:13px; border: 1px solid grey; background-color:black;padding:10px;\">";
		echo "Trwa aktualizowanie liczników...<input type=image class=border0 src=img/loader7.gif>";
		echo "</div>";
		ob_flush();
		flush();
	}

//	echo "<div id=TrwaLadowanie style=\"color:grey; font-weight:bold; text-align:center; font-size:13px; border: 1px solid #FC9898; background-color:white;padding:10px;\">";

	if ($_REQUEST[range]=='M') $s = $_REQUEST[cu];
	if ($_REQUEST[range]=='W') $s = "wszystkich";
	if ($_REQUEST[range]=='MW') $s = "".$_REQUEST[cu]." i wszystkich";

	if ($_REQUEST[sourcepage]=='hd') {
		echo "Trwa aktualizowanie liczników...<input type=image class=border0 src=img/loader7.gif>";
		//echo "Trwa aktualizowanie liczników ze zgłoszeń dla ".$s."...<input type=image class=border0 src=img/loader.gif>";
	//	echo "</div>";
	} else {
		//echo "<br />";
		//echo "<div id=TrwaLadowanie style=\"color:white; font-weight:normal; text-align:center; font-size:13px; border: 1px solid grey; background-color:black;padding:10px;\">";
		//echo "Trwa aktualizowanie liczników...<input type=image class=border0 src=img/loader7.gif>";
		//echo "</div>";
	}
	
	ob_flush();
	flush();

	if ($_REQUEST[range]=='MW') {

		$sql_report = "TRUNCATE TABLE $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]."";	
		$result_report = mysql_query($sql_report, $conn_hd) or die($k_b);		
		
		$sql_report = "TRUNCATE TABLE $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]."_".$_REQUEST[moj_nr]."";
		$result_report = mysql_query($sql_report, $conn_hd) or die($k_b);		
		
		$czy_jest_tabela_ze_statystykami_dla_mnie= mysql_query("SELECT licznik_last_update FROM $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]."_".$_REQUEST[moj_nr]." LIMIT 1", $conn_hd);
		if ($czy_jest_tabela_ze_statystykami_dla_mnie) { } else { 
			$sql_report = "CREATE TABLE `$dbname_hd`.`hd_licznik_zgloszen_".$_REQUEST[f]."_".$_REQUEST[moj_nr]."` (`licznik_id` INT( 10 ) NOT NULL AUTO_INCREMENT ,`licznik_opis` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL ,`licznik_wartosc` INT( 10 ) NOT NULL ,`licznik_kogo` VARCHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL COMMENT 'M - moje, W - wszystkie',`licznik_osoba` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL COMMENT 'jeżeli licznik_kogo = M',`licznik_last_update` DATETIME NOT NULL ,`belongs_to` TINYINT( 2 ) NOT NULL ,PRIMARY KEY ( `licznik_id` )) ENGINE = MYISAM ;";	
			$result_report = mysql_query($sql_report, $conn_hd) or die($k_b);				
		}
	
		$czy_jest_tabela_ze_statystykami_dla_filii = mysql_query("SELECT licznik_last_update FROM $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]." LIMIT 1", $conn_hd);
		if ($czy_jest_tabela_ze_statystykami_dla_filii) {	} else {
			$sql_report = "CREATE TABLE `$dbname_hd`.`hd_licznik_zgloszen_".$_REQUEST[f]."` (`licznik_id` INT( 10 ) NOT NULL AUTO_INCREMENT ,`licznik_opis` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL ,`licznik_wartosc` INT( 10 ) NOT NULL ,`licznik_kogo` VARCHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL COMMENT 'M - moje, W - wszystkie',`licznik_osoba` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL COMMENT 'jeżeli licznik_kogo = M',`licznik_last_update` DATETIME NOT NULL ,`belongs_to` TINYINT( 2 ) NOT NULL ,PRIMARY KEY ( `licznik_id` )) ENGINE = MYISAM ;";	
			$result_report = mysql_query($sql_report, $conn_hd) or die($k_b);		
		}
		
		if ($l_w_1) {
			$resultX = mysql_query("SELECT *, TIMEDIFF('".Date('Y-m-d H:i:s')."',zgl_data_rozpoczecia) as CzasDoRozpoczecia FROM helpdesk.hd_zgloszenie WHERE ((hd_zgloszenie.belongs_to=$_REQUEST[f]) or (hd_zgloszenie.zgl_przekazane_do=$_REQUEST[f])) and (hd_zgloszenie.zgl_widoczne=1) AND ((zgl_kategoria='2') OR (zgl_kategoria='6')) AND (zgl_status<>'9') AND (zgl_data_rozpoczecia!='0000-00-00 00:00:00') and ((zgl_status=1) or (zgl_status=2))", $conn_hd) or die($k_b);
			$WZPriorytetRozpoczecia = mysql_num_rows($resultX);
		}
		
		if ($l_w_2) {
			$resultX = mysql_query("SELECT *, TIMEDIFF('".Date('Y-m-d H:i:s')."',zgl_data_zakonczenia) as CzasDoZakonczenia FROM helpdesk.hd_zgloszenie WHERE ((hd_zgloszenie.belongs_to=$_REQUEST[f]) or (hd_zgloszenie.zgl_przekazane_do=$_REQUEST[f])) and (hd_zgloszenie.zgl_widoczne=1) AND ((zgl_kategoria='2') OR (zgl_kategoria='6')) AND (zgl_status<>'9') AND (zgl_data_zakonczenia!='0000-00-00 00:00:00') and ((zgl_status<>1) and (zgl_status<>2))", $conn_hd) or die($k_b);
			$WZPriorytetZakonczenia = mysql_num_rows($resultX);
		}

		if ($l_w_4) 
			list($WszystkieRozpoczeteNieZakonczone)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and (zgl_status='7') and (zgl_widoczne=1)", $conn_hd));
		
		if ($l_w_3) 
			list($WszystkieNowe)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and (zgl_status='1') and (zgl_widoczne=1)", $conn_hd));
		
		if ($l_w_5) 
			list($WszystkiePrzypisane)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and (zgl_status='2') and (zgl_widoczne=1)", $conn_hd));
			
		if ($l_w_6) 
			list($WszystkieRozpoczete)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and (zgl_status='3') and (zgl_widoczne=1)", $conn_hd));
		
		if ($l_w_7)	
			list($WszystkieWFirmie)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and (zgl_status='3B') and (zgl_widoczne=1)", $conn_hd));
			
		if ($l_w_8)
			list($WszystkieWSerwisie)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and (zgl_status='3A') and (zgl_widoczne=1)", $conn_hd));
		
		if ($l_w_9)	
			list($WszystkieNieZamkniete)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and (zgl_status<>'9') and (zgl_widoczne=1)", $conn_hd));
			
		if ($l_w_10) 
			list($WszystkieDoOddania)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and (zgl_status='6') and (zgl_widoczne=1)", $conn_hd));
		
		if ($l_w_11)
			list($WszystkieZamkniete)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and (zgl_status='9') and (zgl_widoczne=1)", $conn_hd));
		
		if ($l_w_12) 	
			list($WszystkieZgloszenia)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and  (zgl_widoczne=1)", $conn_hd));
			
		if ($l_w_13) 
			list($WszystkieAwarieKrytyczne)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and  (zgl_widoczne=1) and (zgl_kategoria='6') and (zgl_status<>'9')", $conn_hd));
		
		if ($l_w_14)
			list($WszystkieAwarieZwykle)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and  (zgl_widoczne=1) and (zgl_kategoria='2') and (zgl_status<>'9')", $conn_hd));

		if ($l_w_15)
			list($WszystkieOczekNaOdpKl)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and  (zgl_widoczne=1) and (zgl_status='4')", $conn_hd));

		if ($l_w_16)
			list($WszystkieOczekNaSprz)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and  (zgl_widoczne=1) and (zgl_status='5')", $conn_hd));
			
			$noofminutes = $noofminutes_w;
			
			$dataX = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s"))+$noofminutes*60);
			
			$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]." VALUES ('', 'Pr. rozpoczęcia','$WZPriorytetRozpoczecia','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
			$wynikX = mysql_query($sqlX, $conn_hd);
			
			$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]." VALUES ('', 'Pr. zakończenia','$WZPriorytetZakonczenia','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
			$wynikX = mysql_query($sqlX, $conn_hd);

			$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]." VALUES ('', 'Nowe','$WszystkieNowe','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
			$wynikX = mysql_query($sqlX, $conn_hd);

			$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]." VALUES ('', 'Rozpoczęte nie zak.','$WszystkieRozpoczeteNieZakonczone','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
			$wynikX = mysql_query($sqlX, $conn_hd);

			$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]." VALUES ('', 'Przypisane','$WszystkiePrzypisane','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
			$wynikX = mysql_query($sqlX, $conn_hd);

			$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]." VALUES ('', 'Rozpoczęte','$WszystkieRozpoczete','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
			$wynikX = mysql_query($sqlX, $conn_hd);

			$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]." VALUES ('', 'W firmie','$WszystkieWFirmie','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
			$wynikX = mysql_query($sqlX, $conn_hd);

			$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]." VALUES ('', 'W serwisie zewn.','$WszystkieWSerwisie','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
			$wynikX = mysql_query($sqlX, $conn_hd);

			$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]." VALUES ('', 'Oczek. na odp. kl.','$WszystkieOczekNaOdpKl','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
			$wynikX = mysql_query($sqlX, $conn_hd);

			$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]." VALUES ('', 'Oczek. na sprzęt.','$WszystkieOczekNaSprz','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
			$wynikX = mysql_query($sqlX, $conn_hd);
			
			$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]." VALUES ('', 'Nie zamknięte','$WszystkieNieZamkniete','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
			$wynikX = mysql_query($sqlX, $conn_hd);

			$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]." VALUES ('', 'Do oddania','$WszystkieDoOddania','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
			$wynikX = mysql_query($sqlX, $conn_hd);

			$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]." VALUES ('', 'Zamknięte','$WszystkieZamkniete','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
			$wynikX = mysql_query($sqlX, $conn_hd);
			
			$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]." VALUES ('', 'Wszystkie','$WszystkieZgloszenia','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
			$wynikX = mysql_query($sqlX, $conn_hd);
			
			$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]." VALUES ('', 'Awarie krytyczne','$WszystkieAwarieKrytyczne','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
			$wynikX = mysql_query($sqlX, $conn_hd);		

			$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]." VALUES ('', 'Awarie zwykłe','$WszystkieAwarieZwykle','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
			$wynikX = mysql_query($sqlX, $conn_hd);	
		
		if ($l_m_1)	{
			$resultX = mysql_query("SELECT *, TIMEDIFF('".Date('Y-m-d H:i:s')."',zgl_data_rozpoczecia) as CzasDoRozpoczecia FROM helpdesk.hd_zgloszenie WHERE ((hd_zgloszenie.belongs_to=$_REQUEST[f]) or (hd_zgloszenie.zgl_przekazane_do=$_REQUEST[f])) and (hd_zgloszenie.zgl_widoczne=1) AND ((zgl_kategoria='2') OR (zgl_kategoria='6')) AND (zgl_status<>'9') AND (zgl_data_rozpoczecia!='0000-00-00 00:00:00') and ((zgl_status=1) or (zgl_status=2)) and (zgl_osoba_przypisana='$_REQUEST[cu]')", $conn_hd) or die($k_b);
			$MZPriorytetRozpoczecia = mysql_num_rows($resultX);
		}
		
		if ($l_m_2)	{
			$resultX = mysql_query("SELECT *, TIMEDIFF('".Date('Y-m-d H:i:s')."',zgl_data_zakonczenia) as CzasDoZakonczenia FROM helpdesk.hd_zgloszenie WHERE ((hd_zgloszenie.belongs_to=$_REQUEST[f]) or (hd_zgloszenie.zgl_przekazane_do=$_REQUEST[f])) and (hd_zgloszenie.zgl_widoczne=1) AND ((zgl_kategoria='2') OR (zgl_kategoria='6')) AND (zgl_status<>'9') AND (zgl_data_zakonczenia!='0000-00-00 00:00:00') and ((zgl_status<>1) and (zgl_status<>2)) and (zgl_osoba_przypisana='$_REQUEST[cu]')", $conn_hd) or die($k_b);
			$MZPriorytetZakonczenia = mysql_num_rows($resultX);
		}

		if ($l_m_3)	
			list($MojeRozpoczeteNieZakonczone)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and (zgl_status='7') and (zgl_widoczne=1) and (zgl_osoba_przypisana='$_REQUEST[cu]')", $conn_hd));
			
		if ($l_m_4)	
			list($MojePrzypisane)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and (zgl_status='2') and (zgl_widoczne=1) and (zgl_osoba_przypisana='$_REQUEST[cu]')", $conn_hd));
		
		if ($l_m_5)	
			list($MojeRozpoczete)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and (zgl_status='3') and (zgl_widoczne=1 and (zgl_osoba_przypisana='$_REQUEST[cu]'))", $conn_hd));
			
		if ($l_m_6)	
			list($MojeWFirmie)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and (zgl_status='3B') and (zgl_widoczne=1) and (zgl_osoba_przypisana='$_REQUEST[cu]')", $conn_hd));
			
		if ($l_m_7)	
			list($MojeWSerwisie)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and (zgl_status='3A') and (zgl_widoczne=1) and (zgl_osoba_przypisana='$_REQUEST[cu]')", $conn_hd));
			
		if ($l_m_8)	
			list($MojeNieZamkniete)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and (zgl_status<>'9') and (zgl_widoczne=1) and (zgl_osoba_przypisana='$_REQUEST[cu]')", $conn_hd));
		
		if ($l_m_9) 
			list($MojeDoOddania)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and (zgl_status='6') and (zgl_widoczne=1) and (zgl_osoba_przypisana='$_REQUEST[cu]')", $conn_hd));
			
		if ($l_m_10)	
			list($MojeZamkniete)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and (zgl_status='9') and (zgl_widoczne=1) and (zgl_osoba_przypisana='$_REQUEST[cu]')", $conn_hd));
			
		if ($l_m_11) 
			list($MojeWszystkie)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and  (zgl_widoczne=1) and (zgl_osoba_przypisana='$_REQUEST[cu]')", $conn_hd));
			
		if ($l_m_12) 
			list($MojeAwarieKrytyczne)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and (zgl_status<>'9') and (zgl_osoba_przypisana='$_REQUEST[cu]') and (zgl_widoczne=1) and (zgl_kategoria='6')", $conn_hd));
			
		if ($l_m_13) 
			list($MojeAwarieZwykle)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and (zgl_status<>'9') and (zgl_osoba_przypisana='$_REQUEST[cu]') and (zgl_widoczne=1) and (zgl_kategoria='2')", $conn_hd));
			
		if ($l_m_15)
			list($MojeOczekNaOdpKl)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and  (zgl_widoczne=1) and (zgl_osoba_przypisana='$_REQUEST[cu]') and (zgl_status='4')", $conn_hd));

		if ($l_m_16)
			list($MojeOczekNaSprz)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and  (zgl_widoczne=1) and (zgl_osoba_przypisana='$_REQUEST[cu]') and (zgl_status='5')", $conn_hd));
			
			$noofminutes = $noofminutes_m;
			
			$dataX = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s"))+$noofminutes*60);
			
			$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]."_".$_REQUEST[moj_nr]." VALUES ('', 'Pr. rozpoczęcia','$MZPriorytetRozpoczecia','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
			$wynikX = mysql_query($sqlX, $conn_hd);
			
			$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]."_".$_REQUEST[moj_nr]." VALUES ('', 'Pr. zakończenia','$MZPriorytetZakonczenia','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
			$wynikX = mysql_query($sqlX, $conn_hd);

			$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]."_".$_REQUEST[moj_nr]." VALUES ('', 'Rozpoczęte nie zak.','$MojeRozpoczeteNieZakonczone','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
			$wynikX = mysql_query($sqlX, $conn_hd);

			$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]."_".$_REQUEST[moj_nr]." VALUES ('', 'Przypisane','$MojePrzypisane','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
			$wynikX = mysql_query($sqlX, $conn_hd);

			$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]."_".$_REQUEST[moj_nr]." VALUES ('', 'Rozpoczęte','$MojeRozpoczete','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
			$wynikX = mysql_query($sqlX, $conn_hd);

			$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]."_".$_REQUEST[moj_nr]." VALUES ('', 'W firmie','$MojeWFirmie','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
			$wynikX = mysql_query($sqlX, $conn_hd);

			$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]."_".$_REQUEST[moj_nr]." VALUES ('', 'W serwisie zewn.','$MojeWSerwisie','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
			$wynikX = mysql_query($sqlX, $conn_hd);

			$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]."_".$_REQUEST[moj_nr]." VALUES ('', 'Oczek. na odp. kl.','$MojeOczekNaOdpKl','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
			$wynikX = mysql_query($sqlX, $conn_hd);

			$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]."_".$_REQUEST[moj_nr]." VALUES ('', 'Oczek. na sprzęt.','$MojeOczekNaSprz','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
			$wynikX = mysql_query($sqlX, $conn_hd);
			
			$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]."_".$_REQUEST[moj_nr]." VALUES ('', 'Nie zamknięte','$MojeNieZamkniete','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
			$wynikX = mysql_query($sqlX, $conn_hd);

			$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]."_".$_REQUEST[moj_nr]." VALUES ('', 'Do oddania','$MojeDoOddania','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
			$wynikX = mysql_query($sqlX, $conn_hd);

			$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]."_".$_REQUEST[moj_nr]." VALUES ('', 'Zamknięte','$MojeZamkniete','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
			$wynikX = mysql_query($sqlX, $conn_hd);

			$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]."_".$_REQUEST[moj_nr]." VALUES ('', 'Wszystkie','$MojeWszystkie','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
			$wynikX = mysql_query($sqlX, $conn_hd);
			
			$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]."_".$_REQUEST[moj_nr]." VALUES ('', 'Awarie krytyczne','$MojeAwarieKrytyczne','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
			$wynikX = mysql_query($sqlX, $conn_hd);		

			$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]."_".$_REQUEST[moj_nr]." VALUES ('', 'Awarie zwykłe','$MojeAwarieZwykle','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
			$wynikX = mysql_query($sqlX, $conn_hd);		
		
	 	
	}
	
	if ($_REQUEST[range]=='W') {

		$czy_jest_tabela_ze_statystykami_dla_filii = mysql_query("SELECT licznik_last_update FROM $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]." LIMIT 1", $conn_hd);
		if ($czy_jest_tabela_ze_statystykami_dla_filii) {	} else {
			$sql_report = "CREATE TABLE `$dbname_hd`.`hd_licznik_zgloszen_".$_REQUEST[f]."` (`licznik_id` INT( 10 ) NOT NULL AUTO_INCREMENT ,`licznik_opis` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL ,`licznik_wartosc` INT( 10 ) NOT NULL ,`licznik_kogo` VARCHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL COMMENT 'M - moje, W - wszystkie',`licznik_osoba` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL COMMENT 'jeżeli licznik_kogo = M',`licznik_last_update` DATETIME NOT NULL ,`belongs_to` TINYINT( 2 ) NOT NULL ,PRIMARY KEY ( `licznik_id` )) ENGINE = MYISAM ;";	
			$result_report = mysql_query($sql_report, $conn_hd) or die($k_b);		
		}
		
	$sql_report = "TRUNCATE TABLE $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]."";	
	$result_report = mysql_query($sql_report, $conn_hd) or die($k_b);
	
	if ($l_w_1) {
		$resultX = mysql_query("SELECT *, TIMEDIFF('".Date('Y-m-d H:i:s')."',zgl_data_rozpoczecia) as CzasDoRozpoczecia FROM helpdesk.hd_zgloszenie WHERE ((hd_zgloszenie.belongs_to=$_REQUEST[f]) or (hd_zgloszenie.zgl_przekazane_do=$_REQUEST[f])) and (hd_zgloszenie.zgl_widoczne=1) AND ((zgl_kategoria='2') OR (zgl_kategoria='6')) AND (zgl_status<>'9') AND (zgl_data_rozpoczecia!='0000-00-00 00:00:00') and ((zgl_status=1) or (zgl_status=2))", $conn_hd) or die($k_b);
		$WZPriorytetRozpoczecia = mysql_num_rows($resultX);
	}
	
	if ($l_w_2) {
		$resultX = mysql_query("SELECT *, TIMEDIFF('".Date('Y-m-d H:i:s')."',zgl_data_zakonczenia) as CzasDoZakonczenia FROM helpdesk.hd_zgloszenie WHERE ((hd_zgloszenie.belongs_to=$_REQUEST[f]) or (hd_zgloszenie.zgl_przekazane_do=$_REQUEST[f])) and (hd_zgloszenie.zgl_widoczne=1) AND ((zgl_kategoria='2') OR (zgl_kategoria='6')) AND (zgl_status<>'9') AND (zgl_data_zakonczenia!='0000-00-00 00:00:00') and ((zgl_status<>1) and (zgl_status<>2))", $conn_hd) or die($k_b);
		$WZPriorytetZakonczenia = mysql_num_rows($resultX);
	}

	if ($l_w_4) 
		list($WszystkieRozpoczeteNieZakonczone)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and (zgl_status='7') and (zgl_widoczne=1)", $conn_hd));
	
	if ($l_w_3) 
		list($WszystkieNowe)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and (zgl_status='1') and (zgl_widoczne=1)", $conn_hd));
	
	if ($l_w_5) 
		list($WszystkiePrzypisane)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and (zgl_status='2') and (zgl_widoczne=1)", $conn_hd));
		
	if ($l_w_6) 
		list($WszystkieRozpoczete)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and (zgl_status='3') and (zgl_widoczne=1)", $conn_hd));
	
	if ($l_w_7)	
		list($WszystkieWFirmie)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and (zgl_status='3B') and (zgl_widoczne=1)", $conn_hd));
		
	if ($l_w_8)
		list($WszystkieWSerwisie)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and (zgl_status='3A') and (zgl_widoczne=1)", $conn_hd));
	
	if ($l_w_9)	
		list($WszystkieNieZamkniete)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and (zgl_status<>'9') and (zgl_widoczne=1)", $conn_hd));
		
	if ($l_w_10) 
		list($WszystkieDoOddania)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and (zgl_status='6') and (zgl_widoczne=1)", $conn_hd));
	
	if ($l_w_11)
		list($WszystkieZamkniete)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and (zgl_status='9') and (zgl_widoczne=1)", $conn_hd));
	
	if ($l_w_12) 	
		list($WszystkieZgloszenia)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and  (zgl_widoczne=1)", $conn_hd));
		
	if ($l_w_13) 
		list($WszystkieAwarieKrytyczne)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and  (zgl_widoczne=1) and (zgl_kategoria='6') and (zgl_status<>'9')", $conn_hd));
	
	if ($l_w_14)
		list($WszystkieAwarieZwykle)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and  (zgl_widoczne=1) and (zgl_kategoria='2') and (zgl_status<>'9')", $conn_hd));

	if ($l_w_15)
		list($WszystkieOczekNaOdpKl)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and  (zgl_widoczne=1) and (zgl_status='4')", $conn_hd));

	if ($l_w_16)
		list($WszystkieOczekNaSprz)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and  (zgl_widoczne=1) and (zgl_status='5')", $conn_hd));
			
		$noofminutes = $noofminutes_w;
		
		$dataX = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s"))+$noofminutes*60);
		
		$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]." VALUES ('', 'Pr. rozpoczęcia','$WZPriorytetRozpoczecia','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
		$wynikX = mysql_query($sqlX, $conn_hd);
		
		$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]." VALUES ('', 'Pr. zakończenia','$WZPriorytetZakonczenia','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
		$wynikX = mysql_query($sqlX, $conn_hd);

		$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]." VALUES ('', 'Nowe','$WszystkieNowe','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
		$wynikX = mysql_query($sqlX, $conn_hd);

		$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]." VALUES ('', 'Rozpoczęte nie zak.','$WszystkieRozpoczeteNieZakonczone','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
		$wynikX = mysql_query($sqlX, $conn_hd);

		$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]." VALUES ('', 'Przypisane','$WszystkiePrzypisane','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
		$wynikX = mysql_query($sqlX, $conn_hd);

		$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]." VALUES ('', 'Rozpoczęte','$WszystkieRozpoczete','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
		$wynikX = mysql_query($sqlX, $conn_hd);

		$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]." VALUES ('', 'W firmie','$WszystkieWFirmie','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
		$wynikX = mysql_query($sqlX, $conn_hd);

		$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]." VALUES ('', 'W serwisie zewn.','$WszystkieWSerwisie','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
		$wynikX = mysql_query($sqlX, $conn_hd);

		$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]." VALUES ('', 'Oczek. na odp. kl.','$WszystkieOczekNaOdpKl','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
		$wynikX = mysql_query($sqlX, $conn_hd);

		$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]." VALUES ('', 'Oczek. na sprzęt.','$WszystkieOczekNaSprz','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
		$wynikX = mysql_query($sqlX, $conn_hd);
			
		$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]." VALUES ('', 'Nie zamknięte','$WszystkieNieZamkniete','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
		$wynikX = mysql_query($sqlX, $conn_hd);

		$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]." VALUES ('', 'Do oddania','$WszystkieDoOddania','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
		$wynikX = mysql_query($sqlX, $conn_hd);

		$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]." VALUES ('', 'Zamknięte','$WszystkieZamkniete','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
		$wynikX = mysql_query($sqlX, $conn_hd);
		
		$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]." VALUES ('', 'Wszystkie','$WszystkieZgloszenia','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
		$wynikX = mysql_query($sqlX, $conn_hd);
		
		$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]." VALUES ('', 'Awarie krytyczne','$WszystkieAwarieKrytyczne','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
		$wynikX = mysql_query($sqlX, $conn_hd);		

		$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]." VALUES ('', 'Awarie zwykłe','$WszystkieAwarieZwykle','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
		$wynikX = mysql_query($sqlX, $conn_hd);		

		
	} 

	if ($_REQUEST[range]=='M') {
	
		$czy_jest_tabela_ze_statystykami_dla_mnie= mysql_query("SELECT licznik_last_update FROM $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]."_".$_REQUEST[moj_nr]." LIMIT 1", $conn_hd);
		if ($czy_jest_tabela_ze_statystykami_dla_mnie) { } else { 
			$sql_report = "CREATE TABLE `$dbname_hd`.`hd_licznik_zgloszen_".$_REQUEST[f]."_".$_REQUEST[moj_nr]."` (`licznik_id` INT( 10 ) NOT NULL AUTO_INCREMENT ,`licznik_opis` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL ,`licznik_wartosc` INT( 10 ) NOT NULL ,`licznik_kogo` VARCHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL COMMENT 'M - moje, W - wszystkie',`licznik_osoba` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL COMMENT 'jeżeli licznik_kogo = M',`licznik_last_update` DATETIME NOT NULL ,`belongs_to` TINYINT( 2 ) NOT NULL ,PRIMARY KEY ( `licznik_id` )) ENGINE = MYISAM ;";	
			$result_report = mysql_query($sql_report, $conn_hd) or die($k_b);				
		}
		
		$sql_report = "TRUNCATE TABLE $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]."_".$_REQUEST[moj_nr]."";	$result_report = mysql_query($sql_report, $conn_hd) or die($k_b);
	
	if ($l_m_1)	{
		$resultX = mysql_query("SELECT *, TIMEDIFF('".Date('Y-m-d H:i:s')."',zgl_data_rozpoczecia) as CzasDoRozpoczecia FROM helpdesk.hd_zgloszenie WHERE ((hd_zgloszenie.belongs_to=$_REQUEST[f]) or (hd_zgloszenie.zgl_przekazane_do=$_REQUEST[f])) and (hd_zgloszenie.zgl_widoczne=1) AND ((zgl_kategoria='2') OR (zgl_kategoria='6')) AND (zgl_status<>'9') AND (zgl_data_rozpoczecia!='0000-00-00 00:00:00') and ((zgl_status=1) or (zgl_status=2)) and (zgl_osoba_przypisana='$_REQUEST[cu]')", $conn_hd) or die($k_b);
		$MZPriorytetRozpoczecia = mysql_num_rows($resultX);
	}
	
	if ($l_m_2)	{
		$resultX = mysql_query("SELECT *, TIMEDIFF('".Date('Y-m-d H:i:s')."',zgl_data_zakonczenia) as CzasDoZakonczenia FROM helpdesk.hd_zgloszenie WHERE ((hd_zgloszenie.belongs_to=$_REQUEST[f]) or (hd_zgloszenie.zgl_przekazane_do=$_REQUEST[f])) and (hd_zgloszenie.zgl_widoczne=1) AND ((zgl_kategoria='2') OR (zgl_kategoria='6')) AND (zgl_status<>'9') AND (zgl_data_zakonczenia!='0000-00-00 00:00:00') and ((zgl_status<>1) and (zgl_status<>2)) and (zgl_osoba_przypisana='$_REQUEST[cu]')", $conn_hd) or die($k_b);
		$MZPriorytetZakonczenia = mysql_num_rows($resultX);
	}

	if ($l_m_3)	
		list($MojeRozpoczeteNieZakonczone)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and (zgl_status='7') and (zgl_widoczne=1) and (zgl_osoba_przypisana='$_REQUEST[cu]')", $conn_hd));
		
	if ($l_m_4)	
		list($MojePrzypisane)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and (zgl_status='2') and (zgl_widoczne=1) and (zgl_osoba_przypisana='$_REQUEST[cu]')", $conn_hd));
	
	if ($l_m_5)	
		list($MojeRozpoczete)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and (zgl_status='3') and (zgl_widoczne=1 and (zgl_osoba_przypisana='$_REQUEST[cu]'))", $conn_hd));
		
	if ($l_m_6)	
		list($MojeWFirmie)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and (zgl_status='3B') and (zgl_widoczne=1) and (zgl_osoba_przypisana='$_REQUEST[cu]')", $conn_hd));
		
	if ($l_m_7)	
		list($MojeWSerwisie)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and (zgl_status='3A') and (zgl_widoczne=1) and (zgl_osoba_przypisana='$_REQUEST[cu]')", $conn_hd));
		
	if ($l_m_8)	
		list($MojeNieZamkniete)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and (zgl_status<>'9') and (zgl_widoczne=1) and (zgl_osoba_przypisana='$_REQUEST[cu]')", $conn_hd));
	
	if ($l_m_9) 
		list($MojeDoOddania)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and (zgl_status='6') and (zgl_widoczne=1) and (zgl_osoba_przypisana='$_REQUEST[cu]')", $conn_hd));
		
	if ($l_m_10)	
		list($MojeZamkniete)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and (zgl_status='9') and (zgl_widoczne=1) and (zgl_osoba_przypisana='$_REQUEST[cu]')", $conn_hd));
		
	if ($l_m_11) 
		list($MojeWszystkie)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and  (zgl_widoczne=1) and (zgl_osoba_przypisana='$_REQUEST[cu]')", $conn_hd));
		
	if ($l_m_12) 
		list($MojeAwarieKrytyczne)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and (zgl_status<>'9') and (zgl_osoba_przypisana='$_REQUEST[cu]') and (zgl_widoczne=1) and (zgl_kategoria='6')", $conn_hd));
		
	if ($l_m_13) 
		list($MojeAwarieZwykle)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and (zgl_status<>'9') and (zgl_osoba_przypisana='$_REQUEST[cu]') and (zgl_widoczne=1) and (zgl_kategoria='2')", $conn_hd));

	if ($l_m_15)
		list($MojeOczekNaOdpKl)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and  (zgl_widoczne=1) and (zgl_osoba_przypisana='$_REQUEST[cu]') and (zgl_status='4')", $conn_hd));

	if ($l_m_16)
		list($MojeOczekNaSprz)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$_REQUEST[f]) or (zgl_przekazane_do=$_REQUEST[f])) and  (zgl_widoczne=1) and (zgl_osoba_przypisana='$_REQUEST[cu]') and (zgl_status='5')", $conn_hd));
			
		$noofminutes = $noofminutes_m;
		
		$dataX = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s"))+$noofminutes*60);
		
		$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]."_".$_REQUEST[moj_nr]." VALUES ('', 'Pr. rozpoczęcia','$MZPriorytetRozpoczecia','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
		$wynikX = mysql_query($sqlX, $conn_hd);
		
		$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]."_".$_REQUEST[moj_nr]." VALUES ('', 'Pr. zakończenia','$MZPriorytetZakonczenia','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
		$wynikX = mysql_query($sqlX, $conn_hd);

		$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]."_".$_REQUEST[moj_nr]." VALUES ('', 'Rozpoczęte nie zak.','$MojeRozpoczeteNieZakonczone','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
		$wynikX = mysql_query($sqlX, $conn_hd);

		$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]."_".$_REQUEST[moj_nr]." VALUES ('', 'Przypisane','$MojePrzypisane','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
		$wynikX = mysql_query($sqlX, $conn_hd);

		$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]."_".$_REQUEST[moj_nr]." VALUES ('', 'Rozpoczęte','$MojeRozpoczete','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
		$wynikX = mysql_query($sqlX, $conn_hd);

		$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]."_".$_REQUEST[moj_nr]." VALUES ('', 'W firmie','$MojeWFirmie','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
		$wynikX = mysql_query($sqlX, $conn_hd);

		$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]."_".$_REQUEST[moj_nr]." VALUES ('', 'W serwisie zewn.','$MojeWSerwisie','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
		$wynikX = mysql_query($sqlX, $conn_hd);

		$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]."_".$_REQUEST[moj_nr]." VALUES ('', 'Oczek. na odp. kl.','$MojeOczekNaOdpKl','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
		$wynikX = mysql_query($sqlX, $conn_hd);

		$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]."_".$_REQUEST[moj_nr]." VALUES ('', 'Oczek. na sprzęt.','$MojeOczekNaSprz','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
		$wynikX = mysql_query($sqlX, $conn_hd);
			
		$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]."_".$_REQUEST[moj_nr]." VALUES ('', 'Nie zamknięte','$MojeNieZamkniete','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
		$wynikX = mysql_query($sqlX, $conn_hd);

		$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]."_".$_REQUEST[moj_nr]." VALUES ('', 'Do oddania','$MojeDoOddania','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
		$wynikX = mysql_query($sqlX, $conn_hd);

		$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]."_".$_REQUEST[moj_nr]." VALUES ('', 'Zamknięte','$MojeZamkniete','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
		$wynikX = mysql_query($sqlX, $conn_hd);

		$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]."_".$_REQUEST[moj_nr]." VALUES ('', 'Wszystkie','$MojeWszystkie','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
		$wynikX = mysql_query($sqlX, $conn_hd);
		
		$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]."_".$_REQUEST[moj_nr]." VALUES ('', 'Awarie krytyczne','$MojeAwarieKrytyczne','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
		$wynikX = mysql_query($sqlX, $conn_hd);		

		$sqlX = "INSERT INTO $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]."_".$_REQUEST[moj_nr]." VALUES ('', 'Awarie zwykłe','$MojeAwarieZwykle','$_REQUEST[range]','$currentuser','$dataX','$_REQUEST[f]')"; 
		$wynikX = mysql_query($sqlX, $conn_hd);		
		
	}
}

?>
<script>
//document.getElementById('TrwaLadowanie').style.display='none';
<?php if ($_REQUEST[todiv]!=1) { ?>
self.close();
<?php } else { ?>	
	<?php if ($_REQUEST[sourcepage]=='hd') { ?>
	$("#liczniki_moje").load('hd_p_zgloszenia_live_view_m.php?f=<?php echo $_REQUEST[f]; ?>&range=M&moj_nr=<?php echo $_REQUEST[moj_nr]; ?>&randval='+ Math.random());
	$("#liczniki_wszyscy").load('hd_p_zgloszenia_live_view_w.php?f=<?php echo $_REQUEST[f]; ?>&range=W&randval='+ Math.random());
	<?php } ?>

	<?php if ($_REQUEST[sourcepage]=='start') { ?>
	$("#liczniki_moje_na_startowej").load('hd_p_zgloszenia_live_view_m_na_startowej_new.php?f=<?php echo $_REQUEST[f]; ?>&randval='+ Math.random()+'&range=M&moj_nr=<?echo $_REQUEST[moj_nr]; ?>&cu=<?php echo urlencode($currentuser); ?>');
	
	$("#liczniki_wszystkie_na_startowej").load('hd_p_zgloszenia_live_view_w_na_startowej_new.php?f=<?php echo $_REQUEST[f]; ?>&randval='+ Math.random()+'&range=W&moj_nr=<?echo $_REQUEST[moj_nr]; ?>&cu=<?php echo urlencode($currentuser); ?>');	
	<?php } ?>

	document.getElementById('licznik_refresh').style.display='none';
//self.location.reload(true);
<?php } ?>
</script>
<?php if ($_REQUEST[refresh_parent]==1) { ?>
<script>
if (opener) opener.location.reload(true);
</script>
<?php } ?>
<script>
document.getElementById('TrwaLadowanie').style.display='none';
</script>
</body>
</html>