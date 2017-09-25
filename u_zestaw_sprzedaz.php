<?php include_once('header.php'); ?>
<body>
<?php 
if (($submit) || ($_REQUEST[noquestion]=='1')) { 
	/*
		1. usunięcie wszystkich pozycji z tabeli serwis_sprzedaż dla odpowiednich pozycji z zestawu
		2. zmiana statusu odpowiednich pozycji w tabeli serwis_faktura_szcz oraz wyczyszczenie pola pozycja_data_sprzedazy
		3. zmiana statusu zestawu
	*/

	$sql_1 = "SELECT * FROM $dbname.serwis_zestaw_szcz WHERE (zestawpozycja_zestaw_id=$_REQUEST[id])";
	$wynik_1 = mysql_query($sql_1,$conn);
	if ($wynik_1) {
		
		while ($dane_1 = mysql_fetch_array($wynik_1)) {
			$fszcz_id = $dane_1['zestawpozycja_fszcz_id'];

			$sql_usun_1 = "DELETE FROM $dbname.serwis_sprzedaz WHERE (sprzedaz_pozycja_id=$fszcz_id) LIMIT 1";
			//echo "$sql_usun_1<br />";
			$usun_1	= mysql_query($sql_usun_1,$conn) or die($k_b);
			$sql_aktualizuj_fszcz = "UPDATE $dbname.serwis_faktura_szcz SET pozycja_datasprzedazy='0000-00-00', pozycja_status=5 WHERE (pozycja_id=$fszcz_id) LIMIT 1";
			//echo "$sql_aktualizuj_fszcz<br />";
			$aktualizuj_fszcz = mysql_query($sql_aktualizuj_fszcz,$conn) or die ($k_b);
		}
		$sql_zmien_status_zestawu = "UPDATE $dbname.serwis_zestawy SET zestaw_status=0 WHERE zestaw_id=$_REQUEST[id] LIMIT 1";
		//echo "$sql_zmien_status_zestawu<br />";
		$zmien_status_zestawu = mysql_query($sql_zmien_status_zestawu,$conn) or die($k_b);
		
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else { 
			?><script>info('Wystąpił błąd podczas operacji usuwania'); self.close(); </script><?php
		}
} else {
$result1 = mysql_query("SELECT zestaw_id, zestaw_opis FROM $dbname.serwis_zestawy WHERE (zestaw_id=$id ) LIMIT 1", $conn) or die($k_b);
list($temp_zid, $temp_zestawnazwa)=mysql_fetch_array($result1);
errorheader("Czy napewno anulować sprzedaż wybranego zestawu ?");
infoheader("<b>".skroc_tekst($temp_zestawnazwa,50)."</b>");

//$result = mysql_query("SELECT zestawpozycja_id FROM $dbname.serwis_zestaw_szcz WHERE zestawpozycja_zestaw_id=$_REQUEST[id]", $conn) or die($k_b);

$sql="SELECT zestawpozycja_id,zestawpozycja_fszcz_id,zestawpozycja_zestaw_id FROM $dbname.serwis_zestaw_szcz WHERE zestawpozycja_zestaw_id=$_REQUEST[id]";
$result=mysql_query($sql, $conn) or die($k_b);
	
$i = 1;
starttable();
th("30;c;LP|;;Nazwa towaru / usługi<br /><sub>Uwagi</sub>|;;Numer seryjny|40;c;Uwagi",$es_prawa);
	while (list($temp_id,$temp_fszcz_id,$temp_zestawid)=mysql_fetch_array($result)) {
	
		list($temp_towarid, $temp_nazwatowaru,$temp_sntowaru,$temp_cenanettotowaru,$temp_uwagitowar,$temp_cenaodsp,$temp_status,$temp_rs)=mysql_fetch_array(mysql_query("SELECT pozycja_id, pozycja_nazwa, pozycja_sn, pozycja_cena_netto,pozycja_uwagi,pozycja_cena_netto_odsprzedazy,pozycja_status,pozycja_rodzaj_sprzedazy FROM $dbname.serwis_faktura_szcz WHERE pozycja_id=$temp_fszcz_id LIMIT 1",$conn));
		
		if ($temp_rs=='') $bez_rs++;
		
		tbl_tr_highlight($i);
			td("30;c;<a href=# class=normalfont title='$temp_id'>".$i."</a>");
			td_(";;".$temp_nazwatowaru."");
				if ($pokazpoczatekuwag==1) pokaz_uwagi($temp_uwagitowar,$iloscznakowuwag,"newWindow(480,265,'p_towar_uwagi.php?id=$temp_towarid')");
				if ($temp_rs!='') {
					echo "<br />";
					echo "<font color=grey>$temp_rs</font>";
				} else { 
					echo "<br /><font color=grey><a class=normalfont title=' Nie określono rodzaju sprzedaży ' href=#>-</a></font>";
					if ($temp_rs=='') echo "&nbsp;&nbsp;<a title='Nie określono rodzaju sprzedaży na poziomie wprowadzania faktury. Jeżeli chcesz go zmienić - skontaktuj się z osobą, która ma uprawnienia do zmian w pozycjach na fakturach. Domyślny rodzaj sprzedaży: Towar' class=normalfont style='border:1px solid red; color:red' href=#>&nbsp;?&nbsp;</a>";
				}
				
			_td();
			td_(";");
				echo "$temp_sntowaru";
			td_(";c;");
				if ($temp_uwagitowar!='') {
					echo "<a title=' $temp_uwagitowar '><input class=imgoption type=image src=img/comment.gif onclick=\"newWindow(480,265,'p_towar_uwagi.php?id=$temp_towarid')\"></a>";
				}
				if ($sold==0) echo "<a title=' Edytuj uwagi o towarze '><input class=imgoption type=image src=img/edit_comment.gif onclick=\"newWindow(480,245,'e_towar_uwagi.php?id=$temp_towarid')\"></a>";
			_td();
			$j++;
			$i++;
		_tr();
	}

endtable();

startbuttonsarea("center");
echo "<form action=$PHP_SELF method=POST>";
echo "<input type=hidden name=id value=$temp_zid>";
addbuttons("tak","nie");
endbuttonsarea();
_form();
}
?>
</body>
</html>