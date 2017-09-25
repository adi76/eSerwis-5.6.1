<?php include_once('header.php'); ?>
<body>
<?php include('body_start.php');
include('inc_encrypt.php');
$result = mysql_query("SELECT zestawpozycja_id FROM $dbname.serwis_zestaw_szcz WHERE zestawpozycja_zestaw_id=$id", $conn) or die($k_b);
$count_rows = mysql_num_rows($result);
list($opiszestawu, $zestaw__id,$temp_zestaw_from_hd)=mysql_fetch_array(mysql_query("SELECT zestaw_opis, zestaw_id,zestaw_from_hd FROM $dbname.serwis_zestawy WHERE zestaw_id=$id LIMIT 1",$conn));
if ($count_rows!=0) {
	if ($temp_zestaw_from_hd==0) {
		pageheader("Przeglądanie zestawu ".skroc_tekst($opiszestawu,50)."",1);
	} else {
		pageheader("Przeglądanie zestawu ".skroc_tekst($opiszestawu,50)." <font color=red>utworzony przez Helpdesk</font>",1);
	}
	
	startbuttonsarea("center");
		if ($showall==0) {
			echo "<a class=paging href=$phpfile?showall=1&paget=$page&id=$id&sold=$sold>Pokaż wszystko na jednej stronie</a>";
		} else {
			echo "<a class=paging href=$phpfile?showall=0&page=$paget&id=$id&sold=$sold>Dziel na strony</a>";	
		}
	endbuttonsarea();
	// paging
	$totalrows = mysql_num_rows($result);
	if ($showall==0) { $rps=$rowpersite; } else $rps=10000;
	if(empty($page)) { $page = 1; }
	$limitvalue = $page * $rps - ($rps);
	$sql="SELECT zestawpozycja_id,zestawpozycja_fszcz_id,zestawpozycja_zestaw_id FROM $dbname.serwis_zestaw_szcz WHERE zestawpozycja_zestaw_id=$id LIMIT $limitvalue, $rps";
	$result=mysql_query($sql, $conn) or die($k_b);
	// koniec - paging
	starttable();
	th("30;c;LP|;;Nazwa towaru / usługi<br /><sub>Uwagi</sub>|;;Numer seryjny|40;c;Uwagi|;c;Opcje",$es_prawa);
	$i = 0;
	$j = $page*$rowpersite-$rowpersite+1;
	$kwota_zestawu = 0;
	$kwota_zestawu_odsp = 0;
	$bez_rs = 0;
	
	while (list($temp_id,$temp_fszcz_id,$temp_zestawid)=mysql_fetch_array($result)) {
	
		list($temp_towarid, $temp_nazwatowaru,$temp_sntowaru,$temp_cenanettotowaru,$temp_uwagitowar,$temp_cenaodsp,$temp_status,$temp_rs)=mysql_fetch_array(mysql_query("SELECT pozycja_id, pozycja_nazwa, pozycja_sn, pozycja_cena_netto,pozycja_uwagi,pozycja_cena_netto_odsprzedazy,pozycja_status,pozycja_rodzaj_sprzedazy FROM $dbname.serwis_faktura_szcz WHERE pozycja_id=$temp_fszcz_id LIMIT 1",$conn));
		
		$temp_cenatowaru = decrypt_md5($temp_cenanettotowaru,$key);
		$temp_cenatowaru_odsp = decrypt_md5($temp_cenaodsp,$key);
		
		$kwota_zestawu+=$temp_cenatowaru;
		$kwota_zestawu_odsp+=$temp_cenatowaru_odsp;
		
		if ($temp_rs=='') $bez_rs++;
		
		tbl_tr_highlight($i);
			td("30;c;<a href=# class=normalfont title='$temp_id'>".$j."</a>");
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
			td_(";c;");
				
			//	if ($sold==0) { 
				$accessLevels = array("9");
				if(array_search($es_prawa, $accessLevels)>-1){

					if (($temp_status==0) || ($temp_status==-1) || ($temp_status==5)) {
						echo "<a title=' Popraw pozycję $temp_nazwatowaru na fakturze'><input class=imgoption type=image src=img/edit.gif  onclick=\"newWindow(800,600,'e_faktura_pozycja.php?id=$temp_towarid&trodzaj=".urlencode($temp_rs)."&allow_change_rs=1'); return false;\"></a>";
					}
				}	

				if (($temp_status==0) || ($temp_status==5)) {
					if ($temp_zestaw_from_hd==0) {
						echo "<a title='Usuń $temp_nazwatowaru ($temp_sntowaru) z zestawu'><input class=imgoption type=image src=img/basket_remove.gif onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_towar_z_zestawu.php?id=$temp_towarid')\"></a>";
					} else {
						echo "<a title='Usuń $temp_nazwatowaru ($temp_sntowaru) z zestawu powiązanego z bazą Helpdesk'><input class=imgoption type=image src=img/basket_remove.gif onclick=\"newWindow($dialog_window_x,200,'u_towar_z_zestawu_hd.php?id=$temp_towarid')\"></a>";
					}
				} elseif ($temp_status==9) { 
						$sql_r = "SELECT sprzedaz_data,sprzedaz_osoba FROM $dbname.serwis_sprzedaz WHERE sprzedaz_pozycja_id=$temp_towarid  LIMIT 1";
						$result_r = mysql_query($sql_r, $conn) or die($k_b);
						$newArray_r = mysql_fetch_array($result_r);
						$r_miesiac = $newArray_r['sprzedaz_data'];
						$sosoba = $newArray_r['sprzedaz_osoba'];
						$okres = substr($r_miesiac,0,7);
						echo "<b><a title=' Osoba sprzedająca: $sosoba '>ujęty w raporcie $okres</a></b>"; 
				} else {
						$sql_r = "SELECT sprzedaz_data,sprzedaz_osoba FROM $dbname.serwis_sprzedaz WHERE sprzedaz_pozycja_id=$temp_towarid  LIMIT 1";
						$result_r = mysql_query($sql_r, $conn) or die($k_b);
						$newArray_r = mysql_fetch_array($result_r);
						$r_miesiac = $newArray_r['sprzedaz_data'];
						$sosoba = $newArray_r['sprzedaz_osoba'];
						$okres = substr($r_miesiac,0,7);			
						
						//echo "<a title=' Edycja daty sprzedaży i komórki '><input class=imgoption type=image src=img/edit.gif onclick=\"newWindow(580,600,'e_komorka.php?select_id=$temp_id')\"><br /></a>";
						
						echo "<b><a title=' Osoba sprzedająca: $sosoba '>sprzedany<br />($r_miesiac)</a></b>";
						
				}
			_td();
//			} 
			$i++;
		_tr();
	}
endtable();

if ($bez_rs>0) {
	if ($bez_rs==1) $prefix = 'Jeden element zestawu ma ';
	if ($bez_rs==2) $prefix = 'Dwa elementy zestawu mają ';
	if ($bez_rs==3) $prefix = 'Trzy elementy zestawu mają ';
	if ($bez_rs==4) $prefix = 'Cztery elementy zestawu mają ';
	if ($bez_rs==5) $prefix = 'Pięć elementów zestawu ma ';
	if ($bez_rs>5) $prefix = 'Więcej niż 5 elementów zestawu ma ';
	
	echo "<br />";
	errorheader("<font style='font-weight:normal'>$prefix nieokreślony rodzaj sprzedaży.<br />Te elementy zestawu zostaną sprzedane jako <b>Towar</b>.</font>");
}
		
include_once('paging_end.php');
$accessLevels = array("9");
if (array_search($es_prawa, $accessLevels)>-1) {
	startbuttonsarea("right");
	echo "Wartość towarów / usług na magazynie : <b>".correct_currency($kwota_zestawu)." zł</b><br />";
	echo "Wartość towarów / usług na magazynie powiększona o koszty podfaktur : <b>".correct_currency($kwota_zestawu_odsp)." zł</b>";
	endbuttonsarea();
} 
} else errorheader("Zestaw : ".skroc_tekst($opiszestawu,50)." jest pusty");
startbuttonsarea("right");
if ($sold==0) {
	if ($allowchanges!=0) addownlinkbutton("'Pokaż utworzone zestawy'","button","button"," if (opener) opener.location.href='z_towary_zestawy.php'; self.close();");
	if ($allowchanges!=0) addownlinkbutton("'Dodaj nowy towar / usługę do tego zestawu'","button","button"," if (opener)  opener.location.href='p_towary_dostepne.php?view=dozestawu&addto=".$zestaw__id."'; self.close();");
	//addownlinkbutton("'Usuń wszystkie pozycje z zestawu'","button","button"," opener.location.href='u_towar_z_zestawu.php?id=all&submit=1&delall=".$zestaw__id."'; self.close();");
} else {
	addownlinkbutton("'Pokaż utworzone zestawy'","button","button"," if (opener) opener.location.href='z_towary_zestawy.php'; self.close();");
	//addownlinkbutton("'Usuń wszystkie pozycje z zestawu'","button","button"," opener.location.href='u_towar_z_zestawu.php?id=all&submit=1&delall=".$zestaw__id."'; self.close();");
}
addclosewithreloadbutton("Zamknij");
endbuttonsarea();
include('body_stop.php');
?>
</body>
</html>