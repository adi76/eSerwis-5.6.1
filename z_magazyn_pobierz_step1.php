<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php

pageheader("Pobranie sprzętu serwisowego (krok 1/4)");

starttable();
echo "<form name=pobierzstep1 action=z_magazyn_pobierz_step2.php method=POST>";
tbl_empty_row();
	tr_();
		td("200;r;Wybierz typ sprzętu");
		td_(";;");
			$result = mysql_query("SELECT DISTINCT magazyn_nazwa FROM $dbname.serwis_magazyn WHERE (belongs_to=$es_filia) and ((magazyn_status=0) or ((magazyn_status=2) and (magazyn_osoba_rezerwujaca='$currentuser'))) ORDER BY magazyn_nazwa", $conn) or die($k_b);
			while (list($temp_nazwa)=mysql_fetch_array($result)) {
				echo "<input type=radio name=typ_sprzetu value='$temp_nazwa'";
				if ($_GET[typ_sprzetu]==$temp_nazwa) echo "checked";
				echo ">$temp_nazwa<br />"; 
			}
		_td();
	_tr();
tbl_empty_row();
endtable();

startbuttonsarea("center");
addbuttons("dalej");
endbuttonsarea();
startbuttonsarea("right");
addbuttons("zamknij");
endbuttonsarea();
_form();

?>
</body>
</html>