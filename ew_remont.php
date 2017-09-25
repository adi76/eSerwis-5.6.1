<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) { 

if ($_SESSION[ew_remont]!='tak') {	
	$_POST=sanitize($_POST);
	$dddd = Date('Y-m-d H:i:s');

	$k__procesor=$_POST[konf_proc];
	$k__pamiec=$_POST[konf_ram];
	$k__dysk=$_POST[konf_hdd];
	
	$konf_opis='Procesor '.$k__procesor.'GHz, '.$k__pamiec.'MB RAM, '.$k__dysk.'GB HDD';

	$sql_t = "INSERT INTO $dbname.serwis_ewidencja_remonty VALUES ('',$_POST[ewid_id],'$_POST[old_procesor]','$_POST[old_pamiec]','$_POST[old_dysk]','$dddd','$currentuser','".nl2br($_POST[old_uwagi])."','0',$es_filia,'$k__procesor', '$k__pamiec','$k__dysk')";
	
	$wynik = mysql_query($sql_t, $conn) or die($k_b);
	
	$sql_t1 = "UPDATE $dbname.serwis_ewidencja SET k_procesor='$k__procesor', k_pamiec='$k__pamiec', k_dysk='$k__dysk', ewidencja_konfiguracja='$konf_opis' WHERE (ewidencja_id='$_POST[ewid_id]')";

	if (mysql_query($sql_t1, $conn)) {
		$_SESSION[ew_remont]='tak';
		okheader("Remont sprzętu został pomyślnie zarejestrowany w bazie");

		$dd = Date('d');
		$mm = Date('m');
		$rr = Date('Y');
		
		$sql = "SELECT ewidencja_id, ewidencja_typ_nazwa FROM $dbname.serwis_ewidencja WHERE ewidencja_id=$_POST[ewid_id] LIMIT 1";
		$result=mysql_query($sql, $conn) or die($k_b);
		$dane = mysql_fetch_array($result);
		$typsp = $dane['ewidencja_typ_nazwa'];

		$temp_typ	= $dane['ewidencja_typ_nazwa'];
		$temp_nazwa = '';
		$temp_sn = '';
		$temp_ni = '';
					
	if (($typsp=='Komputer') || ($typsp=='Serwer') || ($typsp=='Notebook')) 
	{
		$sql="SELECT * FROM $dbname.serwis_ewidencja WHERE ewidencja_id=$_POST[ewid_id] LIMIT 1";
		$result = mysql_query($sql, $conn) or die($k_b);
		$dane = mysql_fetch_array($result);		
		
		$temp_up_nazwa 	= $dane['ewidencja_up_nazwa'];
		$temp_typ		= $dane['ewidencja_typ_nazwa'];
		$temp_nazwa 	= $dane['ewidencja_komputer_opis'];
		$temp_sn 		= $dane['ewidencja_komputer_sn'];
		$temp_ni 		= $dane['ewidencja_zestaw_ni'];
	} 

	$uwagi = 'Stara konfiguracja sprzętu : procesor '.$_POST[old_procesor].' GHz, pamięć '.$_POST[old_pamiec].' MB RAM, dysk twardy '.$_POST[old_dysk].' GB';
	
	$wykonane_czynnosci = 'Nowa konfiguracja sprzętu : procesor '.$k__procesor.' GHz, pamięć '.$k__pamiec.'MB RAM, dysk twardy '.$k__dysk.' GB';
	
	startbuttonsarea("right");
	//addsubmitbutton("'Generuj protokół z remontu'","newWindow_r(700,595,'utworz_protokol.php?dzien=$dd&miesiac=$mm&rok=$rr&c_1=&c_2=&c_3=&c_4=&c_5=&c_6=&c_7=on&c_8=&up=".urlencode($temp_up_nazwa)."&nazwa_urzadzenia=".urlencode($temp_nazwa)."&sn_urzadzenia=".urlencode($temp_sn)."&ni_urzadzenia=".urlencode($temp_ni)."&opis_uszkodzenia=&wykonane_czynnosci=".urlencode($wykonane_czynnosci)."&uwagi=".urlencode($uwagi)."&imieinazwisko=&readonly=0&odswiez_openera=2')");
	addclosewithreloadbutton("Zamknij");
	endbuttonsarea();
	}
	} else {
		okheader("Remont sprzętu został pomyślnie zarejestrowany w bazie");
		startbuttonsarea("right");
		echo "<input class=buttons type=button onClick=\"self.close(); if (opener) opener.location.href='r_ewidencja_remonty.php';\" value='Zamknij' />";
		endbuttonsarea();	
	}
}
else { ?>

<?php
session_register('ew_remont');
$_SESSION[ew_remont]='nie';
pageheader("Remont sprzętu");
	
$sql = "SELECT * FROM $dbname.serwis_ewidencja WHERE (belongs_to=$es_filia) and (ewidencja_id='$id') LIMIT 1";
$result = mysql_query($sql, $conn) or die($k_b);

starttable();
echo "<form name=editewid action=$PHP_SELF method=POST>";

echo "<tr height=30><th colspan=2>Aktualna konfiguracja sprzętu</th></tr>";

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
	$emoduser	= $dane['ewidencja_modyfikacja_user'];
	$emoddata	= $dane['ewidencja_modyfikacja_date'];
	$ekonf		= $dane['ewidencja_konfiguracja'];
	
	$k__procesor 	= $dane['k_procesor'];
	$k__pamiec		= $dane['k_pamiec'];
	$k__dysk		= $dane['k_dysk'];

	$sql77="SELECT rola_nazwa FROM $dbname.serwis_slownik_rola WHERE rola_id='$etyp_id' LIMIT 1";
	$result77 = mysql_query($sql77, $conn) or die($k_b);
	
	while ($newArray77 = mysql_fetch_array($result77))
	{
	  $rolanazwa		= $newArray77['rola_nazwa'];
	}							  

	echo "<td>Rodzaj sprzętu</td><td><b>$rolanazwa</b></td></tr>";

	$sql7a="SELECT up_nazwa FROM $dbname.serwis_komorki WHERE up_id='$eup_id' LIMIT 1";
	$result7a = mysql_query($sql7a, $conn) or die($k_b);
			
	while ($newArray7a = mysql_fetch_array($result7a))
		{
			  $temp_up_nazwa		= $newArray7a['up_nazwa'];
		}	
		
	$sql_up = "SELECT up_id, up_pion_id FROM $dbname.serwis_komorki WHERE (up_nazwa='$temp_up_nazwa') and (belongs_to=$es_filia) LIMIT 1";
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
	
	echo "<tr><td>Lokalizacja sprzętu</td><td><b>$temp_pion_nazwa $temp_up_nazwa</b></td></tr>";
	echo "<tr><td>Numer pokoju</td><td><b>$enrpok</b></td></tr>";
	echo "<tr><td>Użytkownik sprzętu</td><td><b>$euser&nbsp;</b></td></tr>";
	


	echo "<tr><td>Nr inwentarzowy zestawu</td><td><b>$enizest</b></td></tr>";
	
	echo "<tr><td>Model komputera</td><td><b>$ekopis</b></td></tr>";
	echo "<tr><td>Numer seryjny komputera</td><td><b>$eksn</b></td></tr>";
	echo "<tr><td>Nazwa komputera</td><td><b>$eknazwa</b></td></tr>";
	echo "<tr><td>Adres IP komputera</td><td><b>$ekip</b></td></tr>";
	echo "<tr><td><b>Konfiguracja komputera</b></td><td><b>$ekonf</b></td></tr>";

	echo "<tr><td>Numer endpointa</td><td><b>$eke</b></td></tr>";
	
	echo "<tr><td>Model monitora</td><td><b>$emo</b></td></tr>";
	echo "<tr><td>Numer seryjny monitora</td><td><b>$emsn</b></td></tr>";
	
	echo "<tr><td>Model drukarki</td><td><b>$edo</b></td></tr>";
	echo "<tr><td>Numer seryjny drukarki</td><td><b>$edsn</b></td></tr>";
	echo "<tr><td>Numer inwentarzowy drukarki</td><td><b>$edni</b></td></tr>";
	
	echo "<tr><td>Uwagi</td><td><b>$eu</b></td></tr>";

}

	endtable();

	echo "<table cellspacing=1 align=center class=parzyste>";
echo "<tr height=30><th colspan=2><b>Nowa konfiguracja sprzętu</b></th></tr>";	
	echo "<trn>";
	echo "<td></td>";
	echo "<td></td>";
	echo "</tr>";	
		
	echo "<trn>";
	echo "<td class=right>Procesor (GHz)</td><td><input class=wymagane size=5 type=text name=konf_proc value='$k__procesor'></td>";
	echo "</tr>";

	echo "<trn>";
	echo "<td class=right>Pamięć (MB)</td><td><input class=wymagane size=5 type=text name=konf_ram value='$k__pamiec'></td>";
	echo "</tr>";
	
	echo "<trn>";
	echo "<td class=right>Dysk (GB)</td><td><input class=wymagane size=5 type=text name=konf_hdd value='$k__dysk'></td>";
	echo "</tr>";

	echo "<trn>";
	echo "<td width=160 class=righttop>Uwagi</td>";
	echo "<td><textarea cols=38 rows=3 name=old_uwagi></textarea></td>";
	echo "</tr>";
	tbl_empty_row();	
	endtable();

	echo "<input type=hidden name=old_up value='$temp_up_nazwa'>";
	echo "<input type=hidden name=old_procesor value='$k__procesor'>";
	echo "<input type=hidden name=old_pamiec value='$k__pamiec'>";
	echo "<input type=hidden name=old_dysk value='$k__dysk'>";
	echo "<input type=hidden name=ewid_id value='$eid'>";
	echo "<input type=hidden name=ewid_eidup value='$eup_id'>";	
	
	startbuttonsarea("right");
	addownsubmitbutton("'Aktualizuj dane o sprzęcie'");
	addbuttons("anuluj");
	endbuttonsarea();
	
	_form();
	
?>	
<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("editewid");

  frmvalidator.addValidation("konf_proc","req","Nie wpisałeś żadnej wartości");
  frmvalidator.addValidation("konf_ram","req","Nie wpisałeś żadnej wartości");
  frmvalidator.addValidation("konf_hdd","req","Nie wpisałeś żadnej wartości");
 
</script>
<?php } ?>

</body>
</html>