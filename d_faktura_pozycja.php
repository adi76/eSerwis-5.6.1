<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php
$accessLevels = array("1", "9");
if(array_search($es_prawa, $accessLevels)>-1){
} else { 
	echo "</body>";
	?>
	<script>alert("Brak uprawnień do tej funkcji"); self.close(); </script>
	<?php 
	exit; 
}
pageheader("Dodawanie nowej pozycji do faktury");
$sql="SELECT * FROM $dbname.serwis_faktury WHERE ((belongs_to=$es_filia) and (faktura_id=$id)) LIMIT 1";

$result = mysql_query($sql, $conn) or die($k_b);
$newArray = mysql_fetch_array($result);
$temp_fid  		= $newArray['faktura_id'];
$temp_numer		= $newArray['faktura_numer'];
$temp_data		= $newArray['faktura_data'];
$temp_dostawca	= $newArray['faktura_dostawca'];
$temp_fnrz		= $newArray['faktura_nr_zamowienia'];
$temp_koszy		= $newArray['faktura_koszty_dodatkowe'];
	
startbuttonsarea("center");
//if ($temp_numer=='') $temp_numer='<i><font color=grey>nie wpisana</font></i>';

	$nw=0;
	echo "Numer faktury : <b>$temp_numer</b>";
	if ($temp_numer=='') { echo "<b><i><font color=grey>nie wpisana</font></i></b>"; $nw = 1; }	
	
	if ($nw==1) {
		echo "&nbsp;<a title=' Popraw fakturę '><input class=imgoption type=image src=img/edit.gif onclick=\"newWindow(800,600,'e_faktura.php?id=$temp_fid&fz=1'); return false;\"></a>";
	}
	
if ($temp_fnrz=='') $temp_fnrz='-';
echo "<br />Data wystawienia : <b>$temp_data</b><br />Firma wystawiająca fakturę : <b>$temp_dostawca</b><br />Numer zamówienia : <b>$temp_fnrz</b>";
endbuttonsarea();

starttable();
echo "<form name=addf action=d_faktura_pozycja_zapisz.php method=POST>";
tbl_empty_row();
tr_();
	td("200;r;Nazwa towaru / usługi");
	td_(";;");
		echo "<input class=wymagane size=70 maxlength=100 type=text name=tnazwa6>";
	_td();
_tr();

tr_();
	td("200;r;Gwarancja");
	td_(";;");
		echo "<input class=wymagane size=3 style='text-align:right' maxlength=2 type=text name=tgwarancja6 value='3'>&nbsp;miesiące/cy";
	_td();
_tr();
tbl_empty_row();
tr_();
	td("200;r;Kategoria");
	td_(";;");
		$result = mysql_query("SELECT rola_id,rola_nazwa FROM $dbname.serwis_slownik_rola ORDER BY rola_nazwa", $conn) or die($k_b);
		$count_rows = mysql_num_rows($result);
		echo "<select class=wymagane id=trodzaj name=trodzaj onkeypress='return handleEnter(this, event);' onChange=\"ZmienMarze(this.value); if (this.value==24) { document.getElementById('rodzaj_sprzedazy').value='Usługa'; } else { document.getElementById('rodzaj_sprzedazy').value='Towar';} \">\n";			
		echo "<option value=''>Wybierz z listy...";
		while ($newArray = mysql_fetch_array($result)) {
			$temp_id  				= $newArray['rola_id'];
			$temp_nazwa				= $newArray['rola_nazwa'];
			echo "<option value='$temp_id'>$temp_nazwa</option>\n"; 
		}
		echo "</select>\n"; 
	_td();
_tr();
tr_();
	td("200;r;Określony rodzaj sprzedaży");
	td_(";;");
		echo "<select id=rodzaj_sprzedazy name=rodzaj_sprzedazy onkeypress='return handleEnter(this, event);'>\n";
		echo "<option value=''>nieokreślony";
			echo "<option value='Towar'>Sprzedaż towaru</option>\n"; 
			echo "<option value='Materiał'>Sprzedaż materiału do wykonania usługi</option>\n"; 
			echo "<option value='Usługa'>Sprzedaż usługi</option>\n";
		echo "</select>\n"; 
	_td();
_tr();
tbl_empty_row();
tr_();
	td("200;r;Ilość towaru / uług");
	td_(";;");
		echo "<input type=hidden id=marza_dla_kategorii name=marza_dla_kategorii value='$wskaznik_marzy'>";	
		
		echo "<input style='text-align:right;' class=wymagane size=3 maxlength=3 type=text id=tilosc name=tilosc onKeyPress=\"return filterInput(1, event, false); \" onBlur=\"if  (parseInt(this.value)==0) { alert('Niepoprawna ilość'); document.getElementById('tilosc').value=''; } \" />&nbsp;szt";
		
		echo "&nbsp;";
		echo "<a tabindex=-1 class=normalfont href=# style='display:none' id=twartoscnettoo1>&nbsp;|&nbsp;Wartość netto łącznie za 'n' sztuk towaru / usług: </a>";
		echo "<input type=button id=wylicz value='Wylicz cenę netto za sztukę / usługę z wartości netto pozycji faktury' class=buttons onClick=\"document.getElementById('twartoscnettol').style.display=''; document.getElementById('twartoscnettoo').style.display=''; document.getElementById('twartoscnettoo1').style.display=''; document.getElementById('twartoscnettoa').style.display='';  document.getElementById('twartoscnettow').style.display=''; document.getElementById('wylicz').style.display='none'; document.getElementById('twartoscnettol').focus();\">";
		echo "<input style='text-align:right; display:none; ' size=5 maxlength=8 type=text id=twartoscnettol name=twartoscnettol onkeypress=\"return filterInput(1, event, true);\" onChange=\"Przecinek_na_kropke(document.getElementById('twartoscnettol')); \">";		
		echo "<a tabindex=-1 class=normalfont href=# style='display:none' id=twartoscnettoo>&nbsp;zł&nbsp;</a>";
		echo "<input type=button id=twartoscnettow value='Wylicz' style='display:none' class=buttons onClick=\"WyliczNettoJednostkowaZWartosci(); Przecinek_na_kropke(document.getElementById('tcena6')); ZmienMarze(document.getElementById('trodzaj').value); \" >";
		echo "<input type=button id=twartoscnettoa value='Anuluj' style='display:none' class=buttons onClick=\"document.getElementById('twartoscnettol').style.display='none'; document.getElementById('twartoscnettoo').style.display='none'; document.getElementById('twartoscnettoo1').style.display='none'; document.getElementById('twartoscnettoa').style.display='none';  document.getElementById('twartoscnettow').style.display='none'; document.getElementById('	').style.display=''; document.getElementById('tcena6').focus();\" >";
		
	_td();
_tr();
tr_();
	td("200;r;Cena netto za sztukę / usługę");
	td_(";;");
		echo "<input type=hidden id=marza name=marza value='$wskaznik_marzy'>";
		echo "<input style='text-align:right;' class=wymagane size=5 maxlength=8 type=text id=tcena6 name=tcena6  onkeypress=\"return filterInput(1, event, true);\" onBlur=\"Przecinek_na_kropke(document.getElementById('tcena6')); ZmienMarze(document.getElementById('trodzaj').value); \">&nbsp;zł";
	_td();
_tr();
tbl_empty_row();
tr_();
	td("200;r;Cena netto + koszty umowne<br />(wyliczone automatycznie)");
	td_(";;");
		echo "<input type=text readonly size=8 value='' id=cnku style='background-color:transparent; text-align:right;'> zł";
	_td();
_tr();
tbl_empty_row();
tr_();
	td("200;rt;Ustalona cena netto odsprzedaży");
	td_(";;");
		echo "<input type=hidden id=uco1>";
		echo "<input style='text-align:right;' size=5 maxlength=8 type=text id=uco name=uco onkeypress=\"return filterInput(1, event, true);\" onBlur=\"Przecinek_na_kropke(document.getElementById('uco')); SprawdzCeneOdsprzedazy(document.getElementById('tcena6').value,document.getElementById('uco1').value,this.value); \">&nbsp;zł";
		
		echo "&nbsp;<input type=button class=buttons onClick=\"document.getElementById('uco').value=((Math.round((document.getElementById('tcena6').value*".$wskaznik_marzy.")*100)/100)); document.getElementById('uco1').value=((Math.round((document.getElementById('tcena6').value*".$wskaznik_marzy.")*100)/100)); SprawdzCeneOdsprzedazy(document.getElementById('tcena6').value,document.getElementById('uco1').value,this.value); document.getElementById('uco').focus();\" value='Wyliczona automatycznie'>";
		echo "<br />";
		
		echo "<span id=warning style='display:none'><b><font style=color:red>  Cena odsprzedaży przekracza określoną umową marżę ".(($wskaznik_marzy-1)*100)."%  </font></b></span>";
		
		echo "<span id=warning1 style='display:none'><b><font style=color:#BC4306>  Ustalona cena odsprzedaży jest niższa niż cena zakupu</font></b></span>";
		
		echo "<span id=warning2 style='display:none'><b><font style=color:green>  Ustalona cena odsprzedaży jest niższa niż  wyliczona automatycznie";
		//echo "<input type=button id=proc value=''>";
		
		echo "</font></b></span>";
		
	_td();
_tr();
$curr1=$curr+1;
echo "<input type=hidden name=tcurr1 value=$curr1>";
echo "<input type=hidden name=tcurr value=$curr>";
echo "<input type=hidden name=tid value=$temp_fid>";
tbl_empty_row();	
endtable();
startbuttonsarea("right");
nowalinia();
addbuttons("dalej1","anuluj");
endbuttonsarea();
_form();
?>
<script language="JavaScript" type="text/javascript">
	var frmvalidator  = new Validator("addf");
	frmvalidator.addValidation("tnazwa6","req","Nie wpisałeś nazwy towaru / usługi");
	frmvalidator.addValidation("tgwarancja6","req","Nie wpisałeś ilości miesięcy gwarancji");
	frmvalidator.addValidation("tgwarancja6","numeric","Użyłeś niedozwolonych znaków w polu \"Gwarancja\"");
	frmvalidator.addValidation("trodzaj","dontselect=0","Nie wybrałeś kategorii");
	frmvalidator.addValidation("tilosc","req","Nie wpisałeś ilości towaru / usług");
	frmvalidator.addValidation("tilosc","numeric","Użyłeś niedozwolonych znaków w polu \"Ilość\"");	
	frmvalidator.addValidation("tcena6","req","Nie wpisałeś ceny netto za sztukę / usługę");
	frmvalidator.addValidation("tcena6","numericmoney","Użyłeś niedozwolonych znaków w polu \"Kwota netto podfaktury\"\nZnakiem oddzielającym musi być kropka (nie przecinek)");
	//frmvalidator.addValidation("uco","req","Nie wpisałeś ceny netto za sztukę / usługę");
	frmvalidator.addValidation("uco","numericmoney","Użyłeś niedozwolonych znaków w polu \"Ustalona cena netto odsprzedaży\"\nZnakiem oddzielającym musi być kropka (nie przecinek)");	
</script>
</body>
</html>