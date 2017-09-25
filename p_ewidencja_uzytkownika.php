<?php include_once('header_begin.php'); ?>
<script type="text/javascript" src="js/ewidencja.js"></script>
</head>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php
pageheader("Ewidencja sprzętu - widok użytkownika",0,1);
starttable();
echo "<form name=ewidlok action=p_ewidencja_filtruj.php method=GET>";
echo "<tr><th class=right width=200>Możliwe pola do wyboru</th><th class=center>Wybierz</th><th class=center>Dowolna<br />wartość</th><th>Wartość</th></tr>";
echo "<tr>";
	echo "<td class=right>Rodzaj sprzętu</td>";
	$sql44="SELECT rola_id,rola_nazwa FROM $dbname.serwis_slownik_rola WHERE rola_do_ewidencji='1' ORDER BY rola_nazwa";
	$result44 = mysql_query($sql44, $conn) or die($k_b);
	echo "<td class=center><input class=border0 type=checkbox name=esk_rola_check checked onClick=enable_rola();></td>";
	echo "<td class=center></td>";
	echo "<td>";		
	echo "<select name=esk_rola>\n"; 	
	echo "<option selected value='-1'>...dowolny...</option>\n";				 				
	
	while ($newArray44 = mysql_fetch_array($result44)) {
		$temp_id  	= $newArray44['rola_id'];
		$temp_nazwa	= $newArray44['rola_nazwa'];
		echo "<option value='$temp_id'>$temp_nazwa</option>\n"; 
	}

	echo "</select>\n"; 
	//echo "<input type=checkbox name=stat checked>&nbsp;pokaż podsumowanie</td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td class=right>Pokaż sprzęt z lokalizacji</td>";
	//	$sql44="SELECT up_id,up_nazwa FROM $dbname.serwis_komorki WHERE (belongs_to=$es_filia) and (up_active=1) ORDER BY up_nazwa";
	//	$result44 = mysql_query($sql44, $conn) or die($k_b);
		
		$result44 = mysql_query("SELECT serwis_komorki.up_id, serwis_komorki.up_nazwa, serwis_piony.pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) ORDER BY serwis_piony.pion_nazwa,up_nazwa", $conn) or die($k_b);
		
		echo "<td class=center><input class=border0 type=checkbox name=esk_lok_check checked onClick=enable_lok();></td>";	
		echo "<td class=center></td>";
		echo "<td>";		
		echo "<select name=esk_lok>\n"; 					 				
		echo "<option selected value='-1'>...dowolny...</option>\n";		
					
		while ($newArray44 = mysql_fetch_array($result44)) 
		 {
			$temp_id  				= $newArray44['up_id'];
			$temp_nazwa				= $newArray44['up_nazwa'];
			$temp_pion				= $newArray44['pion_nazwa'];
			
			echo "<option value='$temp_id'>$temp_pion $temp_nazwa</option>\n"; 
		}
		echo "</select>\n"; 
		echo "</td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td class=right>Użytkownik sprzętu</td>";	
		echo "<td class=center><input class=border0 type=checkbox name=esk_user_check onClick=enable_user();></td>";
		echo "<td class=center><input class=border0 type=checkbox name=esk_user_checkw checked></td>";
		echo "<td class=left><input size=20 type=text name=esk_user disabled onkeypress=disable_user();></td>";
	echo "</tr>";

	echo "<tr>";
		echo "<td class=right>Numer pokoju</td>";	
		echo "<td class=center><input class=border0 type=checkbox name=esk_nrpok_check onClick=enable_nrpok();></td>";
		echo "<td class=center><input class=border0 type=checkbox name=esk_nrpok_checkw checked></td>";
		echo "<td class=left><input size=5 type=text name=esk_nrpok disabled onkeypress=disable_nrpok();></td>";
	echo "</tr>";
	
	echo "<tr>";
		echo "<td class=right>Numer inwentarzowy zestawu</td>";	
		echo "<td class=center><input class=border0 type=checkbox name=esk_nrinwz_check checked onClick=enable_nrinwz();></td>";
		echo "<td class=center><input class=border0 type=checkbox name=esk_nrinwz_checkw checked></td>";
		echo "<td class=left><input size=15 type=text name=esk_nrinwz onkeypress=disable_nrinwz();></td>";
	echo "</tr>";
	
	echo "<tr>";
		echo "<td class=right>Nazwa komputera</td>";	
		echo "<td class=center><input class=border0 type=checkbox name=esk_nazwak_check onClick=enable_nazwak();></td>";
		echo "<td class=center><input class=border0 type=checkbox name=esk_nazwak_checkw checked></td>";
		echo "<td class=left><input size=20 type=text name=esk_nazwak disabled onkeypress=disable_nazwak();></td>";
	echo "</tr>";

	echo "<tr>";
		echo "<td class=right>Opis komputera</td>";	
		echo "<td class=center><input class=border0 type=checkbox name=esk_opisk_check checked onClick=enable_opisk();></td>";
		echo "<td class=center><input class=border0 type=checkbox name=esk_opisk_checkw checked></td>";
		echo "<td class=left><input size=40 type=text name=esk_opisk onkeypress=disable_opisk(); ></td>";
	echo "</tr>";

	echo "<tr>";
		echo "<td class=right>Numer seryjny komputera</td>";	
		echo "<td class=center><input class=border0 type=checkbox name=esk_nrsk_check onClick=enable_nrsk();></td>";
		echo "<td class=center><input class=border0 type=checkbox name=esk_nrsk_checkw checked></td>";
		echo "<td class=left><input size=20 type=text name=esk_nrsk disabled onkeypress=disable_nrsk();></td>";
	echo "</tr>";

	echo "<tr>";
		echo "<td class=right>Adres IP komputera</td>";	
		echo "<td class=center><input class=border0 type=checkbox name=esk_nrip_check onClick=enable_nrip();></td>";
		echo "<td class=center><input class=border0 type=checkbox name=esk_nrip_checkw checked></td>";
		echo "<td class=left><input size=15 type=text name=esk_nrip disabled onkeypress=disable_nrip();></td>";
	echo "</tr>";

	echo "<tr>";
		echo "<td class=right>Endpoint</td>";	
		echo "<td class=center><input class=border0 type=checkbox name=esk_endpoint_check onClick=enable_endpoint();></td>";
		echo "<td class=center><input class=border0 type=checkbox name=esk_endpoint_checkw checked></td>";
		echo "<td class=left><input size=20 type=text name=esk_endpoint disabled onkeypress=disable_endpoint();></td>";
	echo "</tr>";

	echo "<tr>";
		echo "<td class=right>Nazwa monitora</td>";	
		echo "<td class=center><input class=border0 type=checkbox name=esk_nazwam_check checked onClick=enable_nazwam();></td>";
		echo "<td class=center><input class=border0 type=checkbox name=esk_nazwam_checkw checked></td>";
		echo "<td class=left><input size=40 type=text name=esk_nazwam onkeypress=disable_nazwam();></td>";
	echo "</tr>";

	echo "<tr>";
		echo "<td class=right>Numer seryjny monitora</td>";	
		echo "<td class=center><input class=border0 type=checkbox name=esk_nrsm_check onClick=enable_nrsm();></td>";
		echo "<td class=center><input class=border0 type=checkbox name=esk_nrsm_checkw checked></td>";
		echo "<td class=left><input size=20 type=text name=esk_nrsm disabled onkeypress=disable_nrsm();></td>";
	echo "</tr>";

	echo "<tr>";
		echo "<td class=right>Nazwa drukarki</td>";	
		echo "<td class=center><input class=border0 type=checkbox name=esk_nazwad_check checked onClick=enable_nazwad();></td>";
		echo "<td class=center><input class=border0 type=checkbox name=esk_nazwad_checkw checked></td>";
		echo "<td class=left><input size=40 type=text name=esk_nazwad onkeypress=disable_nazwad();></td>";
	echo "</tr>";

	echo "<tr>";
		echo "<td class=right>Numer seryjny drukarki</td>";	
		echo "<td class=center><input class=border0 type=checkbox name=esk_nrsd_check onClick=enable_nrsd();></td>";
		echo "<td class=center><input class=border0 type=checkbox name=esk_nrsd_checkw checked></td>";
		echo "<td class=left><input size=20 type=text name=esk_nrsd disabled onkeypress=disable_nrsd();></td>";
	echo "</tr>";

	echo "<tr>";
		echo "<td class=right>Numer inwentarzowy drukarki</td>";	
		echo "<td class=center><input class=border0 type=checkbox name=esk_nrinwd_check onClick=enable_nrinwd();></td>";
		echo "<td class=center><input class=border0 type=checkbox name=esk_nrinwd_checkw checked></td>";
		echo "<td class=left><input size=20 type=text name=esk_nrinwd disabled onkeypress=disable_nrinwd();></td>";
	echo "</tr>";

	echo "<tr>";
		echo "<td class=right>Konfiguracja sprzętu komputerowego</td>";	
		echo "<td class=center><input class=border0 type=checkbox name=esk_konf_check onClick=enable_konf();></td>";
		echo "<td class=center><input class=border0 type=checkbox name=esk_konf_checkw checked></td>";
		echo "<td class=left><input size=50 type=text name=esk_konf disabled onkeypress=disable_konf();></td>";
	echo "</tr>";

	echo "<tr>";
		echo "<td class=right>Uwagi</td>";	
		echo "<td class=center><input class=border0 type=checkbox checked name=esk_uwagi_check></td>";
		echo "<td class=left></td>";
		echo "<td class=left></td>";
	echo "</tr>";
	
	echo "<tr>";
		echo "<td class=right>Opcje</td>";	
		echo "<td class=center><input class=border0 type=checkbox name=esk_opcje_check checked onClick=enable_opt();></td>";
		echo "<td colspan=1 class=left></td>";
		echo "<td class=left>";
		
		echo "<select name=ew_action>\n"; 	
		echo "<option selected value='normal'>standardowe</option>\n";			
		echo "<option value='move'>przesunięcie sprzętu</option>\n";
		echo "<option value='delete'>usunięcie sprzętu</option>\n";
		echo "<option value='change'>remont sprzętu</option>\n";
		
// -
// access control 
$accessLevels = array("1","9");
if(array_search($es_prawa, $accessLevels)>-1){
		echo "<option value='admin'>administratora</option>\n";
}
// access control koniec
// -

	echo "</select>\n"; 
	echo "</td>";
	
	echo "</tr>";

	echo "<tr>";
		echo "<td colspan=3></td><td class=left>Znak % zastępuje dowolny ciąg znaków (np. p216% pokaże wszystkie komputery których nazwa rozpoczyn się na p216)</td>";		
	echo "</tr>";
	

	echo "<input type=hidden name=action value='ewid_all'>";
	echo "<input type=hidden name=sel value='all'>";
	echo "<input type=hidden name=showall value='0'>";
	echo "<tr><td></td></tr>";
	endtable();

	startbuttonsarea("right");
	addownlinkbutton("'Zaznacz wszystkie'","checkall","button","check_all();");
	addownlinkbutton("'Odznacz wszystkie'","uncheckall","button","uncheck_all();");
	addownlinkbutton("'Domyślny widok'","defaultcheck","button","window.location.reload(true);");
	
	echo "<input class=buttons type=submit id=submit name=submit style='font-weight:bold' value='Pokaż dane spełniające wybrane kryteria'>";
	
	addbuttons("start");
	endbuttonsarea();
	
	_form();

?>

</body>
</html>