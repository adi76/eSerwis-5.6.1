<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php
pageheader("Dodawanie nowych UP / komórek do zadania");
?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
$result = mysql_query("SELECT zadanie_id,zadanie_opis,zadanie_termin_zakonczenia,zadanie_uwagi,zadanie_priorytet FROM $dbname.serwis_zadania WHERE (zadanie_id=$id) LIMIT 1", $conn) or die($k_b);
list($temp_id,$temp_opis,$temp_termin,$temp_uwagi,$temp_priorytet)=mysql_fetch_array($result);	
$termin = substr($temp_termin,0,10); 
if ($temp_priorytet==0) $priorytet='NISKI';
if ($temp_priorytet==1) $priorytet='NORMALNY';
if ($temp_priorytet==2) $priorytet='WYSOKI';
if ($termin=='0000-00-00') { $termin='nieokreślony'; }
startbuttonsarea("center");
echo "Zadanie : <b>$temp_opis</b><br />Termin zakończenia : <b>$termin</b><br />Priorytet : <b>$priorytet</b>";
endbuttonsarea();

echo "<span id=tbl99 style='display:none'>";
starttable();
echo "<form name=addzp action=d_zadanie_pozycje_zapisz.php method=POST onSubmit=\"if (confirm('Czy przypisać wybrane komórki (ilość: '+PoliczZaznaczone(false)+') do zadania ?')) { return true; } else { return false; } \" />";
th(";;Wybierz|;l;Nazwa UP / Komórki|;;Przypisz dla",$es_prawa);
$result = mysql_query("SELECT up_id,up_nazwa,up_pion_id, up_typ,up_kompleksowa_obsluga,pion_nazwa,up_komorka_macierzysta_id FROM $dbname.serwis_komorki,$dbname.serwis_piony WHERE (belongs_to=$es_filia) and (up_active=1) and (up_pion_id=pion_id) ORDER BY pion_nazwa, up_nazwa", $conn) or die($k_b);
$count_rows = mysql_num_rows($result);
$i = 0;
$j = 0;
$k = 1;

$result6 = mysql_query("SELECT user_first_name, user_last_name FROM $dbname.serwis_uzytkownicy WHERE (belongs_to=$es_filia) and (user_locked=0) ORDER BY user_last_name", $conn) or die($k_b);
$users = '';
while (list($temp_user_imie,$temp_user_nazwisko) = mysql_fetch_array($result6)) {
	$imieinazwisko = $temp_user_imie.' '.$temp_user_nazwisko;
	$users .= "<option value='$imieinazwisko'>$imieinazwisko</option>\n";
}
				
while (list($temp_up_id,$temp_up_nazwa,$temp_pion_id,$temp_typ,$ko,$temp_pion_nazwa,$temp_komorka_macierzysta)=mysql_fetch_array($result)) {
	$result_2 = mysql_query("SELECT pozycja_id FROM $dbname.serwis_zadania_pozycje WHERE ((pozycja_komorka='$temp_up_nazwa') and (pozycja_zadanie_id=$id))", $conn) or die($k_b);
	$count_rows = mysql_num_rows($result_2);	
	if ($count_rows==0) {
		tbl_tr_highlight($j);
		$j++;
			td_("40;c;");
				echo "<span style='float:left; margin-top:5px;'>";
				echo "$k";
				echo "</span>";
				$k++;
				echo "<span style='float:right; margin-top:1px;'>";
					echo "<input class=border0 type=checkbox id=wybierz$i name=wybierz$i onClick=\"PoliczZaznaczone(false);\">";
				echo "</span>";
			_td();		
			td_(";;");	
				$result9 = mysql_query("SELECT up_id, up_pion_id, up_komorka_macierzysta_id FROM $dbname.serwis_komorki WHERE (up_nazwa='$temp_up_nazwa') and (belongs_to=$es_filia) LIMIT 1", $conn) or die($k_b);
				list($up_id,$temp_pion_id,$temp_komorka_macierzysta)=mysql_fetch_array($result9);
				
				// nazwa pionu z id pionu
		/*		$sql_get_pion = "SELECT pion_nazwa FROM $dbname.serwis_piony WHERE (pion_id='$temp_pion_id') LIMIT 1";
				$wynik_get_pion = mysql_query($sql_get_pion, $conn) or die($k_b);
				$dane_get_pion = mysql_fetch_array($wynik_get_pion);
				$temp_pion_nazwa = $dane_get_pion['pion_nazwa'];
				// koniec ustalania nazwy pionu
		*/
				echo "<a class=normalfont title=' Szczegółowe informacje o $temp_pion_nazwa $temp_up_nazwa ' onclick=\"newWindow_r(700,600,'p_komorka_szczegoly.php?id=$up_id'); return false;\" href=#><b>";

				if ($temp_komorka_macierzysta>0) echo "<font color=grey>";			
				if (($temp_komorka_macierzysta>0) && ($wciecie_dla_podleglych_komorek==1)) echo "&nbsp;&nbsp;&nbsp;&nbsp;";			
					echo "$temp_pion_nazwa $temp_up_nazwa";
				if ($temp_komorka_macierzysta>0) echo "</font>";
				
				echo "</b></a>";

				echo "<input type=hidden id=idpozycji$i name=idpozycji$i value='$i'>";
				echo "<input type=hidden id=fullkomorka$i name=fullkomorka$i value='$temp_pion_nazwa $temp_up_nazwa'>";
				echo "<input type=hidden id=komorka$i name=komorka$i value='$temp_up_nazwa'>";
				echo "<input type=hidden id=typ_komorki$i name=typ_komorki$i value='$temp_typ'>";
				echo "<input type=hidden id=KOI_$i name=KOI_$i value='$ko'>";

			_td();

			td_(";;");
				// buduj listę dla SELECT'a z użytkowników danej filii
				$result6 = mysql_query("SELECT user_first_name, user_last_name FROM $dbname.serwis_uzytkownicy WHERE (belongs_to=$es_filia) and (user_locked=0) ORDER BY user_last_name", $conn) or die($k_b);
				echo "<select name=user$i>";
				echo "<option value=''>dowolnej osoby</option>\n";
				echo $users;
				
				echo "</select>";
				//koniec - buduj		
			_td();
		$i++;
		_tr();
	}
}
tbl_empty_row();
endtable();
echo "</span>";


if ($j>0) {
	//echo "<span style='float:right'>";
	startbuttonsarea("left");
	echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class=imgoption type=image src=img/obsluga_seryjna.png>&nbsp;Zaznaczonych: ";
	echo "<span id=IloscZaznaczonych style='font-weight:bold;'>0</span>";
	echo "&nbsp;|&nbsp;Wszystkie: <input class=buttons type=button onClick=\"selectAll1(this); PoliczZaznaczone(false);\" value='Zaznacz'>";
	echo "<input class=buttons type=button onClick=\"DeselectAll1(this); PoliczZaznaczone(false);\" value='Odznacz'>";
	echo "<input class=buttons type=button onClick=\"selectAll(this); PoliczZaznaczone(false);\" value='Odwróć zaznaczenie'>";
	//echo " | ";
	//echo "</span>";
	endbuttonsarea();
	startbuttonsarea("right");
	echo "Zaznacz: ";
	echo "<input type=button class=buttons value='UP' onClick=\"ZaznaczWgTypu(1);\">";
	echo "<input type=button class=buttons value='Filia' onClick=\"ZaznaczWgTypu(2);\">";
	echo "<input type=button class=buttons value='Agencja' onClick=\"ZaznaczWgTypu(3);\">";
	echo "<input type=button class=buttons value='Administracja' onClick=\"ZaznaczWgTypu(4);\">";
	echo "<input type=button class=buttons value='KOI' onClick=\"ZaznaczWgTypu(5);\">";
	echo " | ";
	echo "Odznacz: ";
	echo "<input type=button class=buttons value='UP' onClick=\"OdznaczWgTypu(1);\">";
	echo "<input type=button class=buttons value='Filia' onClick=\"OdznaczWgTypu(2);\">";
	echo "<input type=button class=buttons value='Agencja' onClick=\"OdznaczWgTypu(3);\">";
	echo "<input type=button class=buttons value='Administracja' onClick=\"OdznaczWgTypu(4);\">";
	echo "<input type=button class=buttons value='KOI' onClick=\"OdznaczWgTypu(5);\">";

	echo "<br />";	
	//echo "<input type=button class=buttons name=sAll value='Wyczyść' onClick=\"if (confirm('Czy wczytać ponownie formularz ?')) { self.location.reload(true); PoliczZaznaczone(false); return true; } else { return false; }\">";
	addbuttons("przypiszdozadania","anuluj");
	endbuttonsarea();
} else {
	startbuttonsarea("right");
	errorheader('Do tego zadania przypisane są już wszystkie komórki z bazy');
	addbuttons("zamknij");	
	endbuttonsarea();
}

echo "<input type=hidden name=zpilosc value=$i>";
echo "<input type=hidden name=zid value=$id>";
_form();
?>
<script>
<?php if ($j>0) { ?>document.getElementById('tbl99').style.display='';<?php } ?>
PoliczZaznaczone(false);
</script>
<script>HideWaitingMessage();</script>
</body>
</html>