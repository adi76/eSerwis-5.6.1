<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[1].focus();">
<?php 
//include('inc_encrypt.php');
if ($submit) {	
	$_POST=sanitize($_POST);
//	$cenanetto = $_POST['pcenanetto'];
//	$cena = str_replace(',','.',$cenanetto);
//	$cenanetto_cr = crypt_md5($cena,$key);
	
//	$cenaodsp = $_POST['pcenaodsp'];
//	$cena1 = str_replace(',','.',$cenaodsp);
//	$cenaodsp_cr = crypt_md5($cena1,$key);
	
	$sql_e1="UPDATE $dbname.serwis_faktura_szcz SET pozycja_sn = '$_POST[psn]', pozycja_uwagi='".nl2br($_POST[puwagi])."' WHERE pozycja_id = '$pid' LIMIT 1";
	
	if (mysql_query($sql_e1, $conn)) { 
			?><script>if (opener) opener.location.reload(true); self.close(); </script><?php	
	} else 
		{
		  ?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
		}
} else { ?>

<?php 
$sql_e = "SELECT * FROM $dbname.serwis_faktura_szcz WHERE (pozycja_id=$id) LIMIT 1";
$result = mysql_query($sql_e, $conn) or die($k_b);
while ($newArray = mysql_fetch_array($result)) {

	$temp_id 		= $newArray['pozycja_id'];
	$temp_nazwa 	= $newArray['pozycja_nazwa'];
	$temp_sn		= $newArray['pozycja_sn'];
	$temp_typ		= $newArray['pozycja_typ'];
	$temp_cenanetto_cr	= $newArray['pozycja_cena_netto'];
	$temp_cenaodsp_cr	= $newArray['pozycja_cena_netto_odsprzedazy'];
	$temp_uwagi			= $newArray['pozycja_uwagi'];
	$temp_gw			= $newArray['pozycja_gwarancja'];		
}

//	$temp_cenanetto 	= decrypt_md5($temp_cenanetto_cr,$key);
//	$temp_cenaodsp	 	= decrypt_md5($temp_cenaodsp_cr,$key);
	
	pageheader("Edycja pozycji z faktury obcej");

	starttable();
	echo "<form name=edu action=$PHP_SELF method=POST>";
	echo "<input type=hidden name=pid value=$temp_id>";

	tbl_empty_row();
	
		echo "<tr>";
		echo "<td width=120 class=right>Nazwa towaru / usługi</td>";
		echo "<td><b>$temp_nazwa</b></td>";
		echo "</tr>";

//		tr_();
//			td("200;r;Gwarancja");
//			td_(";;");
//				echo "<input class=wymagane size=3 style='text-align:right' maxlength=2 type=text name=gwarancja value='$temp_gw'>&nbsp;miesiące/cy";
//			_td();
//		_tr();
//		tbl_empty_row();
		
/*		echo "<tr>";
		echo "<td width=120 class=right>Kategoria</td>";

		$sql="SELECT * FROM $dbname.serwis_slownik_rola ORDER BY rola_nazwa";
		$result = mysql_query($sql, $conn) or die($k_b);
		$count_rows = mysql_num_rows($result);
		$i = 0;
		
		echo "<td>";		
		echo "<select class=wymagane name=prodzaj onkeypress='return handleEnter(this, event);' onChange=\"if (this.value=='Usługa') { document.getElementById('rodzaj_sprzedazy').value='Usługa'; } else { document.getElementById('rodzaj_sprzedazy').value='Towar';}\">\n"; 					 				
		echo "<option value=''>Wybierz z listy...";
				
		while ($newArray = mysql_fetch_array($result)) 
		 {
			$temp_rid  				= $newArray['rola_id'];
			$temp_rnazwa			= $newArray['rola_nazwa'];
			
			echo "<option value='$temp_rnazwa' "; 
			if ($temp_typ==$temp_rnazwa) echo "SELECTED";
			echo ">$temp_rnazwa</option>\n"; 
		}
		echo "</select>\n"; 
		echo "</td>";
	echo "</tr>";
	
	tr_();
		td("200;r;Określony rodzaj sprzedaży");
		td_(";;");
			echo "<select id=rodzaj_sprzedazy name=rodzaj_sprzedazy onkeypress='return handleEnter(this, event);'>\n";
			echo "<option value=''>nieokreślony";
				echo "<option value='Towar' "; if ($_REQUEST[trodzaj]=='Towar') echo "SELECTED "; echo ">Sprzedaż towaru</option>\n"; 
				echo "<option value='Materiał' "; if ($_REQUEST[trodzaj]=='Materiał') echo "SELECTED "; echo ">Sprzedaż materiału do wykonania usługi</option>\n"; 
				echo "<option value='Usługa' "; if ($_REQUEST[trodzaj]=='Usługa') echo "SELECTED "; echo ">Sprzedaż usługi</option>\n";
			echo "</select>\n"; 
		_td();
	_tr();
	tbl_empty_row();
*/	
		echo "<tr>";
		echo "<td width=120 class=right>Numer seryjny</td>";
		echo "<td><input size=30 maxlength=30 type=text name=psn value='$temp_sn'></td>";
		echo "</tr>";

/*		echo "<tr>";
		echo "<td width=120 class=right>Cena netto za sztukę</td>";
		echo "<td>";
		echo "<input type=hidden id=uco1>";
		echo "<input type=hidden id=marza_dla_kategorii name=marza_dla_kategorii value='$wskaznik_marzy'>";	
		echo "<input type=hidden id=marza name=marza value='$wskaznik_marzy'>";
		
		echo "<input style='text-align:right;' class=wymagane size=8 maxlength=15 type=text id=pcenanetto name=pcenanetto value='".correct_currency($temp_cenanetto)."' onChange=\"Przecinek_na_kropke(document.getElementById('pcenanetto')); document.getElementById('uco1').value=((Math.round((document.getElementById('pcenanetto').value*document.getElementById('marza').value)*100)/100)); if (confirm('Czy wyliczyć cenę netto odsprzedaży z ceny netto za sztukę ?')) { ZmienMarze_E(); document.getElementById('uco1').value=((Math.round((document.getElementById('pcenanetto').value*document.getElementById('marza').value)*100)/100)); } else { document.getElementById('pcenanetto').focus(); } SprawdzCeneOdsprzedazy(document.getElementById('pcenanetto').value,document.getElementById('uco1').value,document.getElementById('pcenaodsp').value); \" onBlur=\"if (parseInt(this.value)==0) { alert('Niepoprawna cena netto za sztukę'); document.getElementById('pcenanetto').value=''; document.getElementById('pcenanetto').focus();} \"> zł</td>";
		echo "</tr>";

		echo "<tr>";
			echo "<td width=120 class=right>Cena netto odsprzedaży</td>";
			echo "<td>";
				echo "<input style='text-align:right;' class=wymagane type=text id=pcenaodsp name=pcenaodsp size=8 maxlength=15 value='".correct_currency($temp_cenaodsp)."' onChange=\"Przecinek_na_kropke(document.getElementById('pcenaodsp')); \"  onBlur=\"document.getElementById('uco1').value=((Math.round((document.getElementById('pcenanetto').value*document.getElementById('marza').value)*100)/100)); SprawdzCeneOdsprzedazy(document.getElementById('pcenanetto').value,document.getElementById('uco1').value,document.getElementById('pcenaodsp').value); \"> zł";
				echo "&nbsp;";
				echo "<input type=button id=wylicz value='Wylicz cenę netto odsprzedaży z ceny netto za sztukę, uwzględniając ustaloną marżę' class=buttons onClick=\"ZmienMarze_E(); document.getElementById('uco1').value=((Math.round((document.getElementById('pcenanetto').value*document.getElementById('marza').value)*100)/100)); SprawdzCeneOdsprzedazy(document.getElementById('pcenanetto').value,document.getElementById('uco1').value,document.getElementById('pcenaodsp').value);\">";
			echo "</td>";
		echo "</tr>";

		echo "<tr><td></td><td>";
		echo "<span id=warning style='display:none'><b><font style=color:red>  Cena odsprzedaży przekracza określoną umową marżę ".(($wskaznik_marzy-1)*100)."%  </font></b></span>";
		echo "<span id=warning1 style='display:none'><b><font style=color:#BC4306>  Cena netto odsprzedaży jest niższa niż cena netto za sztukę</font></b></span>";
		echo "<span id=warning2 style='display:none'><b><font style=color:green>  Cena netto odsprzedaży jest niższa niż  wyliczona automatycznie";
		echo "</td></tr>";
*/		
		echo "<tr>";
		echo "<td width=120 class=righttop>Uwagi</td>";
		echo "<td><textarea name=puwagi rows=6 cols=65>".br2nl($temp_uwagi)."</textarea></td>";
		echo "</tr>";
		
		tbl_empty_row();
	endtable();

	startbuttonsarea("right");
	addbuttons("zapisz","anuluj");
	endbuttonsarea();

	_form();

} ?>

</body>
</html>