<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php

pageheader("Pobranie sprzętu serwisowego (krok 2/4)");

starttable();
echo "<form name=pobierzstep2 action=z_magazyn_pobierz_step3.php method=POST>";
tbl_empty_row();
	tr_();
		td("200;r;Dostępny sprzęt serwisowy");
		td_(";;");		
			$_POST=sanitize($_POST);
			
			$typ_sprzetu_do_zapytania = $_POST[typ_sprzetu];
			if ($typ_sprzetu_do_zapytania=="") $typ_sprzetu_do_zapytania=$_GET[typ_sprzetu];
			
			//echo "SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn FROM $dbname.serwis_magazyn WHERE (belongs_to=$es_filia) and ((magazyn_status=0) or ((magazyn_status=2) and (magazyn_osoba_rezerwujaca='$currentuser'))) and (magazyn_nazwa='$typ_sprzetu_do_zapytania') ORDER BY magazyn_model";
			
			$result = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn FROM $dbname.serwis_magazyn WHERE (belongs_to=$es_filia) and ((magazyn_status=0) or ((magazyn_status=2) and (magazyn_osoba_rezerwujaca='$currentuser'))) and (magazyn_nazwa='$typ_sprzetu_do_zapytania') ORDER BY magazyn_model", $conn) or die($k_b);
			echo "<select name=sz onkeypress='return handleEnter(this,event);'>\n";			 				
			echo "<option value='0'>Wybierz z listy...";
			while (list($temp_id,$temp_nazwa,$temp_model,$temp_sn)=mysql_fetch_array($result)) {
				echo "<option value='$temp_id'";
				if ($_GET[id]==$temp_id) echo " selected";
				echo ">$temp_nazwa ($temp_model, $temp_sn)</option>\n"; 
			}
			echo "</select>\n";
		_td();
	_tr();
tbl_empty_row();
endtable();

startbuttonsarea("center");
if ($_POST[typ_sprzetu]!='') {
	echo "<input class=buttons type=button onClick=window.location.href='z_magazyn_pobierz_step1.php?typ_sprzetu=$_POST[typ_sprzetu]'  value='Wstecz'>";
} else {
	echo "<input class=buttons type=button onClick=window.location.href='z_magazyn_pobierz_step1.php?typ_sprzetu=$_GET[typ_sprzetu]'  value='Wstecz'>";
}
addbuttons("dalej");
endbuttonsarea();
startbuttonsarea("right");
addbuttons("zamknij");
endbuttonsarea();
_form();

?>
</body>
</html>