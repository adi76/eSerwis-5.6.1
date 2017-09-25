<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) { 
	$_POST=sanitize($_POST);
	$dddd = Date('Y-m-d H:i:s');

	$sql_t = "INSERT INTO $dbname.serwis_ewidencja_usuniecia VALUES ('',$_POST[ewid_id],'$dddd','$currentuser','".nl2br($_POST[old_uwagi])."','0',$es_filia)";
	$wynik = mysql_query($sql_t, $conn);

	$sql_t2 = "UPDATE $dbname.serwis_oprogramowanie SET oprogramowanie_status=0 WHERE (oprogramowanie_ewidencja_id=$_POST[ewid_id])";
	$result2 = mysql_query($sql_t2, $conn);	
	
	$sql_t1 = "UPDATE $dbname.serwis_ewidencja SET ewidencja_status=-1, ewidencja_oprogramowanie_id=0 WHERE (ewidencja_id=$_POST[ewid_id])";
	//echo $sql_t1;
	//$result1 = mysql_query($sql_t1, $conn);	

	if (mysql_query($sql_t1, $conn)) {
	 
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} 
}
 else { ?>

<?php

pageheader("Usunięcie sprzętu z ewidencji");

$sql = "SELECT * FROM $dbname.serwis_ewidencja WHERE (belongs_to=$es_filia) and (ewidencja_id='$id') LIMIT 1";
$result = mysql_query($sql, $conn) or die($k_b);

starttable();
echo "<form name=editewid action=$PHP_SELF method=POST>";

echo "<tr height=30><th colspan=2>Aktualne informacje o sprzęcie</th></tr>";

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
	$emsn		= $dane['ewidencja_monitor_sn'];
	$edo		= $dane['ewidencja_drukarka_opis'];
	$eke		= $dane['ewidencja_komputer_endpoint'];
	$emo		= $dane['ewidencja_monitor_opis'];
	$edsn		= $dane['ewidencja_drukarka_sn'];
	$edni		= $dane['ewidencja_drukarka_ni'];
	$eu			= $dane['ewidencja_uwagi'];
	$es			= $dane['ewidencja_status'];
	$eo_id		= $dane['ewidencja_oprogramowanie_id'];
	$emoduser	= $dane['ewidencja_modyfikacja_user'];
	$emoddata	= $dane['ewidencja_modyfikacja_date'];
	$ekonf		= $dane['ewidencja_konfiguracja'];
	
	$k__procesor 	= $dane['k_procesor'];
	$k__pamiec	= $dane['k_pamiec'];
	$k__dysk		= $dane['k_dysk'];

	$sql77="SELECT rola_nazwa FROM $dbname.serwis_slownik_rola WHERE rola_id='$etyp_id' LIMIT 1";
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
	
	echo "<tr><td>Lokalizacja sprzętu</td><td><b>$temp_pion_nazwa $temp_up_nazwa</b></td></tr>";
	echo "<tr><td>Numer pokoju</td><td><b>$enrpok</b></b></td></tr>";
	echo "<tr><td>Użytkownik sprzętu</td><td><b>$euser</b></b></td></tr>";
	


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

if (($rolanazwa=="Komputer") || ($rolanazwa=="Serwer") || ($rolanazwa=="Notebook")) {

if ($eo_id!=0) { 

	echo "<br /><table cellspacing=1 align=center class=parzyste_>";
	echo "<tr height=30><th colspan=2>Oprogramowanie zainstalowane na tym sprzęcie</th></tr>";
	
			$sql8 = "SELECT oprogramowanie_nazwa FROM $dbname.serwis_oprogramowanie WHERE (oprogramowanie_ewidencja_id='$id')";
			$result8 = mysql_query($sql8, $conn) or die($k_b);
			
			while ($dane8 = mysql_fetch_array($result8)) {

					$temp_nazwa_opr 	= $dane8['oprogramowanie_nazwa'];
					echo "<tr><td class=center colspan=2>$temp_nazwa_opr</td></tr>";

			}
	}
}
			
	endtable();
		
	br();
	errorheader("Potwierdź usunięcie sprzętu z ewidencji");
	starttable();
	echo "<tr height=30><th colspan=2>Uzasadnienie usunięcia z ewidencji</th></tr>";
    echo "<tr><td></td></tr>";
	echo "<tr>";
	echo "<td width=160 class=righttop>Uwagi</td>";
	echo "<td><textarea class=wymagane cols=50 rows=5 name=old_uwagi></textarea></td>";
	echo "</tr>";
	echo "<tr><td></td></tr>";
	endtable();
	
	echo "<input type=hidden name=ewid_id value='$eid'>";
	
	startbuttonsarea("right");
	addownsubmitbutton("'Usuń sprzęt'");
	addbuttons("anuluj");
	endbuttonsarea();
	
	_form();

?>	
<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("editewid");

  frmvalidator.addValidation("old_uwagi","req","Nie wpisałeś powodu usunięcia sprzętu z ewidencji");

 
</script>
<?php } ?>

</body>
</html>