<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php'); 
?>
<body>
<?php 
if ($submit) { 

	$sql="SELECT zestawpozycja_fszcz_id FROM $dbname.serwis_zestaw_szcz WHERE zestawpozycja_zestaw_id=$_REQUEST[id]";
	$result=mysql_query($sql, $conn) or die($k_b);

	while (list($temp_fszcz_id)=mysql_fetch_array($result)) {
	
		$sql_zmienstatuspozycji = "UPDATE $dbname.serwis_faktura_szcz SET pozycja_status=0 WHERE (pozycja_id='$temp_fszcz_id') LIMIT 1";
		$wykonaj = mysql_query($sql_zmienstatuspozycji,$conn);
		
		$usunzzestawu = "DELETE FROM $dbname.serwis_zestaw_szcz WHERE zestawpozycja_fszcz_id=$temp_fszcz_id LIMIT 1";
		$wykonaj_usuniecie = mysql_query($usunzzestawu,$conn) or die($k_b);
	
	}
	
	$usunzestaw = "DELETE FROM $dbname.serwis_zestawy WHERE zestaw_id=$_REQUEST[id] LIMIT 1";
	$wykonaj_usuniecie = mysql_query($usunzestaw,$conn) or die($k_b);
		
	?><script>if (opener) opener.location.reload(true); self.close(); </script><?php

} else {
echo "<form action=$PHP_SELF method=POST>";
errorheader("Czy napewno chcesz wyrzucić podzespoły z zestawu i usunąć zestaw ?");

$result = mysql_query("SELECT zestawpozycja_id FROM $dbname.serwis_zestaw_szcz WHERE zestawpozycja_zestaw_id='$_REQUEST[id]'", $conn) or die($k_b);
$count_rows = mysql_num_rows($result);

$sql="SELECT zestawpozycja_id,zestawpozycja_fszcz_id,zestawpozycja_zestaw_id FROM $dbname.serwis_zestaw_szcz WHERE zestawpozycja_zestaw_id=$id";
//echo $sql;
$result=mysql_query($sql, $conn) or die($k_b);
	
list($opiszestawu, $zestaw__id,$temp_zestaw_from_hd)=mysql_fetch_array(mysql_query("SELECT zestaw_opis, zestaw_id,zestaw_from_hd FROM $dbname.serwis_zestawy WHERE zestaw_id=$_REQUEST[id] LIMIT 1",$conn));

if ($count_rows!=0) {
	starttable();
	th("30;c;LP|;;Nazwa towaru / usługi<br /><sub>Uwagi</sub>|;;Numer seryjny|40;c;Uwagi",$es_prawa);
	$i = 1;
	while (list($temp_id,$temp_fszcz_id,$temp_zestawid)=mysql_fetch_array($result)) {

		list($temp_towarid, $temp_nazwatowaru,$temp_sntowaru,$temp_cenanettotowaru,$temp_uwagitowar,$temp_cenaodsp,$temp_status,$temp_rs)=mysql_fetch_array(mysql_query("SELECT pozycja_id, pozycja_nazwa, pozycja_sn, pozycja_cena_netto,pozycja_uwagi,pozycja_cena_netto_odsprzedazy,pozycja_status,pozycja_rodzaj_sprzedazy FROM $dbname.serwis_faktura_szcz WHERE pozycja_id=$temp_fszcz_id LIMIT 1",$conn));
				
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
//			$accessLevels = array("9");
//			if (array_search($es_prawa, $accessLevels)>-1) {

			$i++;
		_tr();
	}
endtable();
}

startbuttonsarea("center");
echo "<input type=hidden name=id value=$_REQUEST[id]>";
addbuttons("tak","nie");
endbuttonsarea();
_form();
}
?>
</body>
</html>