<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) { 

if ($_SESSION[ew_przesuniecie]!='tak') {					
	$_POST=sanitize($_POST);
	$sql55="SELECT up_nazwa FROM $dbname.serwis_komorki WHERE (up_id=$_POST[ls]) LIMIT 1";
	$result55 = mysql_query($sql55, $conn) or die($k_b);
			
	while ($newArray55 = mysql_fetch_array($result55)) 
	{
		$lok_up_nazwa				= $newArray55['up_nazwa'];		
	}
	
	$dddd = Date('Y-m-d H:i:s');

	$sql_t = "INSERT INTO $dbname.serwis_ewidencja_przesuniecia VALUES ('',$_POST[ewid_id],'$_POST[old_up]','$_POST[old_user]','$_POST[old_nrpok]','$dddd','$currentuser','".nl2br($_POST[old_uwagi])."','0',$es_filia)";
	
	$wynik = mysql_query($sql_t, $conn) or die($k_b);

	$sql_t1 = "UPDATE $dbname.serwis_ewidencja SET ewidencja_up_id='$_POST[ls]', ewidencja_up_nazwa='$lok_up_nazwa',  ewidencja_uzytkownik='$_POST[us]',ewidencja_nr_pokoju='$_POST[nrp]' WHERE (ewidencja_id='$_POST[ewid_id]')";

	if (mysql_query($sql_t1, $conn)) {
	 
		$_SESSION[ew_przesuniecie]='tak';
		okheader("Przesunięcie sprzętu zostało zakończone sukcesem");

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
			
		$temp_typ	= $dane['ewidencja_typ_nazwa'];
		$temp_nazwa = $dane['ewidencja_komputer_opis'];
		$temp_sn = $dane['ewidencja_komputer_sn'];
		$temp_ni = $dane['ewidencja_zestaw_ni'];
	} 
	
	if ($typsp=='Drukarka') 
	{
		$sql="SELECT * FROM $dbname.serwis_ewidencja WHERE ewidencja_id=$_POST[ewid_id] LIMIT 1";
		
		$result = mysql_query($sql, $conn) or die($k_b);
		$dane = mysql_fetch_array($result);		
		
		$temp_typ	= $dane['ewidencja_typ_nazwa'];
		$temp_nazwa = $dane['ewidencja_drukarka_opis'];
		$temp_sn = $dane['ewidencja_drukarka_sn'];
		$temp_ni = $dane['ewidencja_drukarka_ni'];
	}
	
		$uwagi_przekazujaca = 'Sprzęt był w użytkowaniu w pokoju nr '.$_POST[old_nrpok].' przez użytkownika : '.$_POST[old_user];
	
		$wykonane_czynnosci_przekazujaca = $uwagi_przekazujaca.'. Pobranie sprzętu do nowej lokalizacji : '.$lok_up_nazwa.', nr pokoju : '.$_POST[nrp].', użytkownik : '.$_POST[us];
		
		$wykonane_czynnosci_przyjmująca = 'Przyjęcie sprzętu ze starej lokalizacji : '.$_POST[old_up].', nr pokoju : '.$_POST[old_nrp].', użytkownik : '.$_POST[old_user];
	
		startbuttonsarea("right");
				
//		addsubmitbutton("'Generuj protokół - strona przekazująca'","newWindow_r(700,595,'utworz_protokol.php?dzien=$dd&miesiac=$mm&rok=$rr&c_1=&c_2=&c_3=&c_4=&c_5=&c_6=&c_7=&c_8=on&up=".urlencode($_POST[old_up])."&nazwa_urzadzenia=".urlencode($temp_nazwa)."&sn_urzadzenia=".urlencode($temp_sn)."&ni_urzadzenia=".urlencode($temp_ni)."&opis_uszkodzenia=-&wykonane_czynnosci=".urlencode($wykonane_czynnosci_przekazujaca)."&uwagi=".urlencode($_POST[old_uwagi])."&imieinazwisko=&readonly=0&odswiez_openera=2')");
//		addsubmitbutton("'Generuj protokół - strona odbierająca'","newWindow_r(700,595,'utworz_protokol.php?dzien=$dd&miesiac=$mm&rok=$rr&c_1=&c_2=&c_3=&c_4=&c_5=&c_6=&c_7=&c_8=on&up=".urlencode($lok_up_nazwa)."&nazwa_urzadzenia=".urlencode($temp_nazwa)."&sn_urzadzenia=".urlencode($temp_sn)."&ni_urzadzenia=".urlencode($temp_ni)."&opis_uszkodzenia=-&wykonane_czynnosci=".urlencode($wykonane_czynnosci_przyjmująca)."&uwagi=".urlencode($_POST[old_uwagi])."&imieinazwisko=&readonly=0&odswiez_openera=2')");

//		addclosewithreloadbutton("Zamknij");
		echo "<input class=buttons type=button onClick=\"self.close(); if (opener) opener.location.href='r_ewidencja_przesuniecia.php';\" value='Zamknij' />";

		endbuttonsarea();
		} 
	} else {
		okheader("Przesunięcie sprzętu zostało zakończone sukcesem");
		startbuttonsarea("right");
		echo "<input class=buttons type=button onClick=\"self.close(); if (opener) opener.location.href='r_ewidencja_przesuniecia.php';\" value='Zamknij' />";
		endbuttonsarea();	
	}
}
 else { ?>

<?php
session_register('ew_przesuniecie');
$_SESSION[ew_przesuniecie]='nie';

pageheader("Przesunięcie sprzętu");

$sql = "SELECT * FROM $dbname.serwis_ewidencja WHERE (belongs_to=$es_filia) and (ewidencja_id='$id') LIMIT 1";
$result = mysql_query($sql, $conn) or die($k_b);


starttable();
echo "<form name=editewid action=$PHP_SELF method=POST>";
echo "<tr height=30><th colspan=2>Aktualne informacje o lokalizacji sprzętu</th></tr>";

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
	
	$sql77="SELECT * FROM $dbname.serwis_slownik_rola WHERE rola_id='$etyp_id'";
	$result77 = mysql_query($sql77, $conn) or die($k_b);
	
	while ($newArray77 = mysql_fetch_array($result77))
	{
	  $rolanazwa		= $newArray77['rola_nazwa'];
	}							  

	echo "<tr><td>Rodzaj sprzętu</td><td><b>$rolanazwa</b></td></tr>";

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
			
	echo "<tr><td><b>Lokalizacja sprzętu</b></td><td><b>$temp_pion_nazwa $temp_up_nazwa</b></td></tr>";
	echo "<tr><td><b>Numer pokoju</b></td><td><b>$enrpok</b></b></td></tr>";
	echo "<tr><td><b>Użytkownik sprzętu</b></td><td><b>$euser</b></b></td></tr>";
	


	echo "<tr><td>Nr inwentarzowy zestawu</td><td><b>$enizest</b></b></td></tr>";
	echo "<tr><td>Model komputera</td><td><b>$ekopis</b></td></tr>";
	echo "<tr><td>Numer seryjny komputera</td><td><b>$eksn</b></td></tr>";
	echo "<tr><td>Nazwa komputera</td><td><b>$eknazwa</b></td></tr>";
	echo "<tr><td>Adres IP komputera</td><td><b>$ekip</b></td></tr>";
	echo "<tr><td>Konfiguracja komputera</td><td><b>$ekonf</b></td></tr>";

	echo "<tr><td>Numer endpointa</td><td><b>$eke</b></td></tr>";
	
	echo "<tr><td>Model monitora</td><td><b>$emo</b></td></tr>";
	echo "<tr><td>Numer seryjny monitora</td><td><b>$emsn</b></td></tr>";
	
	echo "<tr><td>Model drukarki</td><td><b>$edo</b></td></tr>";
	echo "<tr><td>Numer seryjny drukarki</td><td><b>$edsn</b></td></tr>";
	echo "<tr><td>Numer inwentarzowy drukarki</td><td><b>$edni</b></td></tr>";
	
	echo "<tr><td>Uwagi</td><td><b>$eu</b></td></tr>";
    echo "<tr><td></td></tr>";
}

	endtable();
	
	starttable();
echo "<tr height=30><th colspan=2>Nowa lokalizacja sprzętu</th></tr>";	
		echo "<tr>";
		echo "<td></td>";
		echo "<td></td>";
		echo "</tr>";
		
		echo "<tr>";
			echo "<td width=160 class=right>Lokalizacja sprzętu</td>";
			//$sql44="SELECT * FROM $dbname.serwis_komorki WHERE belongs_to=$es_filia ORDER BY up_nazwa";
			
			//$result44 = mysql_query($sql44, $conn) or die($k_b);
			
			$result44 = mysql_query("SELECT serwis_komorki.up_id, serwis_komorki.up_nazwa, serwis_piony.pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) ORDER BY serwis_piony.pion_nazwa,up_nazwa", $conn) or die($k_b);
			
			$count_rows = mysql_num_rows($result44);
			$i = 0;
			
			echo "<td>";		
			echo "<select class=wymagane name=ls>\n"; 					 				
			echo "<option value=''>Wybierz z listy...";
					
			while ($newArray44 = mysql_fetch_array($result44)) 
			 {
				$temp_id  				= $newArray44['up_id'];
				$temp_nazwa				= $newArray44['up_nazwa'];
				$temp_pion				= $newArray44['pion_nazwa'];
							
				echo "<option value='$temp_id'";
					if ($temp_up_nazwa==$temp_nazwa) { echo " SELECTED"; }
				echo ">$temp_pion $temp_nazwa</option>\n"; 
			
			}
			
			echo "</select>\n"; 
			echo "</td>";
		echo "</tr>";
	
		echo "<tr>";
		echo "<td width=160 class=right>Użytkownik sprzętu</td>";
		echo "<td><input size=20 type=text name=us value='$euser'></td>";
		echo "</tr>";
	
		echo "<tr>";
		echo "<td width=160 class=right>Nr pokoju</td>";
		echo "<td><input size=10 type=text name=nrp value='$enrpok'></td>";
		echo "</tr>";

		echo "<tr>";
		echo "<td width=160 class=righttop>Uwagi</td>";
		echo "<td><textarea cols=38 rows=3 name=old_uwagi></textarea></td>";
		echo "</tr>";
		tbl_empty_row();		
	endtable();
	
	echo "<input type=hidden name=old_up value='$temp_up_nazwa'>";
	echo "<input type=hidden name=old_nrpok value='$enrpok'>";
	echo "<input type=hidden name=old_user value='$euser'>";	
	echo "<input type=hidden name=ewid_id value='$eid'>";
	echo "<input type=hidden name=ewid_eidup value='$eup_id'>";
	
	startbuttonsarea("right");
	addownsubmitbutton("'Przesuń sprzęt'");
	addbuttons("anuluj");
	endbuttonsarea();

	_form();
?>	
<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("editewid");

  frmvalidator.addValidation("ls","dontselect=0","Nie wybrałeś lokalizacji sprzętu");
 
</script>
<?php } ?>

</body>
</html>