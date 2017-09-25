<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php'); 
?>
<body>
<?php include('body_start.php'); ?>
<?php 
include('inc_encrypt.php');
if ($submit99) { 
	
$o=0;

$pozdolicz=0;

/*
// ilosc pozycji na fakturze
	$count=0;
	$sql1="SELECT * FROM $dbname.serwis_faktura_szcz WHERE ((belongs_to=$es_filia) and (pozycja_nr_faktury='$_POST[nrf]'))";
	$result1 = mysql_query($sql1, $conn) or die($k_b);
	$ilosc = mysql_num_rows($result1);
*/
while ($o<=$_POST[ilosc]-1)
{
  if ($_POST['dk'.$o]=='on') { $pozdolicz=$pozdolicz+1; }
  $o++;
}

$o=0;
$n=0;
if (($pozdolicz!=0) && ($_POST[kosztywysylki]!=0)) { 

$kw = $_POST[kosztywysylki];
$il = $pozdolicz;
$kj1 = (real) ($kw / $il);
$kj = round_up($kj1, 2);
}

while ($o<$_POST[ilosc]-1)
{
$poz99 = $_POST['pozycja'.$o];
$cena1 = $_POST['cn'.$o.''];
$cenao = $_POST['cno'.$o.''];

/* 
$nowanazwa = $_POST['nazwapozycji'.$o];
$nowysn	= $_POST['snpozycji'.$o];
*/

if ($_POST['dk'.$o.'']=='on') { $cena2 = $cena1 + $kj; } else $cena2 = $cena1;

$cena = str_replace(',','.',$cena2);

$cena_cr = crypt_md5($cena,$key);
$cenao_cr = crypt_md5($cenao,$key);

	if ($_POST['dk'.$o.'']=='on')
	{
		$sql_t = "UPDATE $dbname.serwis_faktura_szcz SET pozycja_dolicz_koszty = 'on', pozycja_cena_netto_odsprzedazy = '$cena_cr' WHERE pozycja_id=$poz99 LIMIT 1";
		$result = mysql_query($sql_t, $conn) or die($k_b);
	}
	else
	{
		$sql_t = "UPDATE $dbname.serwis_faktura_szcz SET pozycja_dolicz_koszty = 'off', pozycja_cena_netto_odsprzedazy = '$cena_cr' WHERE pozycja_id=$poz99 LIMIT 1";
		$result = mysql_query($sql_t, $conn) or die($k_b);	
	}
	
	if ($_POST['pstatus'.$o]==1) 
	{
		$sql_t2 = "UPDATE $dbname.serwis_sprzedaz SET sprzedaz_pozycja_cenaodsp = '$cena_cr', sprzedaz_pozycja_cenatwoja = '$cena_cr' WHERE sprzedaz_pozycja_id=$poz99 LIMIT 1";
		$result2 = mysql_query($sql_t2, $conn) or die($k_b);
	}

/*
	if ($_POST['nazwapozycji'.$n]!='') {
		$nowanazwa=$_POST['nazwapozycji'.$n];
		$sql_t="UPDATE $dbname.serwis_faktura_szcz SET pozycja_nazwa = '$nowanazwa' WHERE pozycja_id = $poz99 LIMIT 1";
		$result = mysql_query($sql_t, $conn) or die($k_b);
	}

// zmiana sn pozycji
	if ($_POST['snpozycji'.$n]!='') {
		$nowysn=$_POST['snpozycji'.$n];
		$sql_t="UPDATE $dbname.serwis_faktura_szcz SET pozycja_sn = '$nowysn' WHERE pozycja_id = $poz99 LIMIT 1";
		$result = mysql_query($sql_t, $conn) or die($k_b);
	}	
*/
	
$o++;
$n++;

}
}

$sql1="SELECT * FROM $dbname.serwis_faktury WHERE (faktura_id='$id') LIMIT 1";
$result1 = mysql_query($sql1, $conn) or die($k_b);
while ($newArray1 = mysql_fetch_array($result1)) {
	$temp_id1  			= $newArray1['faktura_id'];
	$temp_numer1		= $newArray1['faktura_numer'];
	$temp_data1			= $newArray1['faktura_data'];
	$temp_dostawca1		= $newArray1['faktura_dostawca'];
	$temp_koszty1_cr	= $newArray1['faktura_koszty_dodatkowe'];	
	$temp_fnrz			= $newArray1['faktura_nr_zamowienia'];	
	$temp_statusf		= $newArray1['faktura_status'];
	$temp_knf_cr		= $newArray1['faktura_kwota_netto'];	
	$temp_kbf_cr		= $newArray1['faktura_kwota_brutto'];
	$temp_btf			= $newArray1['belongs_to'];
}
	
$temp_koszty1 = decrypt_md5($temp_koszty1_cr,$key);

$sql1 = "SELECT * FROM $dbname.serwis_faktura_szcz WHERE (pozycja_nr_faktury='$id') and (pozycja_status>-2)";
$result1 = mysql_query($sql1, $conn) or die($k_b);

if (mysql_num_rows($result1)!=0) {
   
   $newArray = mysql_fetch_array($result1);
   
	$temp_id  			= $newArray['pozycja_id'];
	$temp_nrfaktury		= $newArray['pozycja_nr_faktury'];
	$temp_numer			= $newArray['pozycja_numer'];
	$temp_nazwa			= $newArray['pozycja_nazwa'];
	$temp_ilosc			= $newArray['pozycja_ilosc'];
	$temp_sn			= $newArray['pozycja_sn'];
	$temp_cenanetto		= $newArray['pozycja_cena_netto'];
	$temp_status		= $newArray['pozycja_status'];
	$temp_rs			= $newArray['pozycja_rodzaj_sprzedazy'];
	
	pageheader("Szczegółowe informacje o fakturze",1);
	startbuttonsarea("center");
	
	$nw=0;
	if ($temp_numer1=='') { $temp_numer1='<i><font color=grey>nie wpisana</font></i>'; $nw = 1; }
	echo "Numer faktury: <b>$temp_numer1</b>";
	
	if ($nw==1) {
		echo "&nbsp;<a title=' Popraw fakturę '><input class=imgoption type=image src=img/edit.gif onclick=\"newWindow(800,600,'e_faktura.php?id=$temp_nrfaktury&fz=1'); return false;\"></a>";
	}
	
	echo "<br />Data wystawienia: <b>$temp_data1</b><br />Firma wystawiająca fakturę: <b>$temp_dostawca1</b>";
	
	if ($temp_fnrz=='') $temp_fnrz = '-';
	echo "<br />Numer zamówienia: <b>$temp_fnrz</b>";
	endbuttonsarea();
	
	starttable();
	echo "<form name=koszty action=$PHP_SELF method=POST>";
	
	echo "<tr><th class=center>LP</th><th>Nazwa towaru / usługi<br />Rodzaj sprzedaży</th><th class=center width=30>Ilość</th><th>Nr seryjny<br />Gwarancja</th>";
	
// -
// access control 
$accessLevels = array("1","9");
if(array_search($es_prawa, $accessLevels)>-1){
	echo "<th class=right>Cena netto<br />z faktury</th>";
}
// access control koniec
// -	

// -
// access control 
$accessLevels = array("9");
if(array_search($es_prawa, $accessLevels)>-1){
	if ($temp_statusf!=9) {
		echo "<th width=30>Dolicz<br />koszty</th>";
	}
	echo "<th class=right>Cena netto<br />odsprzedaży</th>";
	
}
// access control koniec
// -		
	echo "<th class=center>Status<br /><sub>Informacje dodatkowe</sub></th>";
	echo "<th class=center>Opcje</th>";

echo "</tr>";
$il_podfaktur="SELECT * FROM $dbname.serwis_podfaktury WHERE pf_nr_faktury_id=$id and belongs_to=$es_filia";
$wykonaj = mysql_query($il_podfaktur,$conn) or die($k_b);
$sumapf = 0;
while ($wynik_ilpod = mysql_fetch_array($wykonaj)) {
	$pfkwota_cr = $wynik_ilpod['pf_kwota_netto'];
	$pfkwota = decrypt_md5($pfkwota_cr,$key);
	$sumapf+=$pfkwota;
}

$sql1 = "SELECT * FROM $dbname.serwis_faktura_szcz WHERE (pozycja_nr_faktury='$id') and (pozycja_status>-2)";
$result1 = mysql_query($sql1, $conn) or die($k_b);

$i = 0;
$j = 1;
$kwotarazem=0;

$soldout=0;
$raported=0;

while ($newArray = mysql_fetch_array($result1)) {
	$temp_id  			= $newArray['pozycja_id'];
	$temp_nrfaktury		= $newArray['pozycja_nr_faktury'];
	$temp_numer			= $newArray['pozycja_numer'];
	$temp_nazwa			= $newArray['pozycja_nazwa'];
	$temp_ilosc			= $newArray['pozycja_ilosc'];
	$temp_sn			= $newArray['pozycja_sn'];
	$temp_cenanetto_cr	= $newArray['pozycja_cena_netto'];
	$temp_status		= $newArray['pozycja_status'];
	$temp_datasprzedazy	= $newArray['pozycja_datasprzedazy'];
	$temp_belongs_to	= $newArray['belongs_to'];
	$temp_uwagi			= $newArray['pozycja_uwagi'];
	$temp_rs			= $newArray['pozycja_rodzaj_sprzedazy'];
	$temp_gw			= $newArray['pozycja_gwarancja'];	
	

	if ($temp_status==1) $soldout=1;
	if ($temp_status==9) $raported=1;
	
	$temp_dolicz_koszty	= $newArray['pozycja_dolicz_koszty'];
	$temp_cenanettoodsp_cr	= $newArray['pozycja_cena_netto_odsprzedazy'];
		
	$temp_cenanetto 	= decrypt_md5($temp_cenanetto_cr,$key);
	$temp_cenanettoodsp = decrypt_md5($temp_cenanettoodsp_cr,$key);
	
	$temp_knf = decrypt_md5($temp_knf_cr,$key);

	$kwotarazem=$kwotarazem+$temp_cenanetto;
	
	if ($_REQUEST[pozid]!='') {
		if ($_REQUEST[pozid]==$temp_id) {
			tbl_tr_color_with_border($i,'#FFFF55');
		} else {
			tbl_tr_highlight($i);
		}
	} else { 
		tbl_tr_highlight($i);
	}
	
	echo "<td width=30 class=center><a href=# class=normalfont title='$temp_id'>$j</a></td>";
	echo "<input type=hidden name=pozycja$i value=$temp_id>";
	echo "<input type=hidden name=nrf value='$temp_nrfaktury'>";

if (($temp_statusf==1) || ($temp_statusf==9)) { 
	echo "<td>$temp_nazwa"; 
	if ($pokazpoczatekuwag==1) pokaz_uwagi($temp_uwagi,$iloscznakowuwag,"newWindow(480,245,'e_towar_uwagi.php?id=$temp_id'); return false;"); 
	
		// sprawdzenie czy pozycja nie została już ujęta w sprzedaży (a nadal widnieje na fakturze)
		
	/*	$spnr_sql = "SELECT sprzedaz_id FROM $dbname.serwis_sprzedaz WHERE sprzedaz_pozycja_id=$temp_id LIMIT 1";
		
		$spnr_q = mysql_query($spnr_sql,$conn);
		if (mysql_num_rows($spnr_q)==0) echo "<b>BŁĄD</b>";
	*/
		
		//list($sprzedaz_pozycja_nr)=mysql_fetch_array(mysql_query("SELECT sprzedaz_id FROM $dbname.serwis_sprzedaz WHERE sprzedaz_pozycja_id=$temp_id LIMIT 1",$conn));
		//echo "SELECT sprzedaz_id FROM $dbname.serwis_sprzedaz WHERE sprzedaz_pozycja_id=$temp_id LIMIT 1";
		
		//if ($sprzedaz_pozycja_nr==0) echo "!" else echo "OK";
		
	if ($temp_rs!='') {
		echo "<br />";
		echo "<font color=grey>$temp_rs</font>";
	} else { 
		echo "<br /><font color=grey><a class=normalfont title=' Nie określono rodzaju sprzedaży ' href=#>-</a></font>";
	}	
	
	echo "</td>"; 
} else
	{ 	echo "<td>";
		echo "<input type=hidden name=nazwapozycji$i value='$temp_nazwa'>";
		echo "$temp_nazwa";
		if ($pokazpoczatekuwag==1) pokaz_uwagi($temp_uwagi,$iloscznakowuwag,"newWindow(480,245,'e_towar_uwagi.php?id=$temp_id')");

		if ($temp_rs!='') {
			echo "<br />";
			echo "<font color=grey>$temp_rs</font>";
		} else { 
			echo "<br /><font color=grey><a class=normalfont title=' Nie określono rodzaju sprzedaży ' href=#>-</a></font>";
		}
	
		echo "</td>"; }

	echo "<td class=center width=30>$temp_ilosc</td>"; 
	
if (($temp_statusf==1) || ($temp_statusf==9)) { 
	if ($temp_sn=='') $temp_sn='-';
	echo "<td>$temp_sn"; 
		if ($temp_gw!=0) echo "<br /><font color=grey>$temp_gw m-ce/cy</font>"; 
	echo "</td>"; 
} else { 
	echo "<td>";
	echo "<input type=hidden name=snpozycji$i value='$temp_sn'>";
	if ($temp_sn!='') {
		echo "&nbsp;$temp_sn&nbsp;";
	} else echo "-";
	  
	if ($temp_gw!=0) echo "<br />$temp_gw m-ce/cy";
	echo "</td>"; 
}

// -
// access control 
$accessLevels = array("1","9");
if(array_search($es_prawa, $accessLevels)>-1){
	echo "<td class=right>".correct_currency($temp_cenanetto)." zł</td>";

}
// access control koniec
// -		

// -
// access control 
$accessLevels = array("9");
if(array_search($es_prawa, $accessLevels)>-1){

if ($temp_statusf!=9) {

	echo "<td class=center>";

	if (($sumapf>0) && ($temp_status>-2) && ($temp_status<=1)) {
	
		echo "<input class=border0 type=checkbox name=dk$i ";

		if ($temp_dolicz_koszty=='on') { echo "checked=checked"; }
		echo ">";
		
	} else echo "-";
}

	echo "</td>";
	echo "<td class=right>".correct_currency($temp_cenanettoodsp)." zł</td>";

}
// access control koniec
// -	

	$j+=1;
	
	echo "<td class=center>";

	list($hd_zgl_id)=mysql_fetch_array(mysql_query("SELECT wp_zgl_id FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_sprzedaz_fakt_szcz_id='$temp_id') and (wp_sprzet_active=1) LIMIT 1",$conn_hd));	

	echo "<input type=hidden name=cn$i value=$temp_cenanetto>";	
	echo "<input type=hidden name=cno$i value=$temp_cenanettoodsp>";	
	echo "<input type=hidden name=pstatus$i value=$temp_status>";
	
	if ($temp_status=='-2') {
		echo "<b>usunięty</b>"; 	
	}
	
	if ($temp_belongs_to!=$es_filia) {
		
		if ($temp_btf==$es_filia) {
			echo "<font color=red><b>przeniesiony</b><br />do filii/oddziału:<br />";
			list($fn11)=mysql_fetch_array(mysql_query("SELECT filia_nazwa FROM $dbname.serwis_filie WHERE filia_id=$temp_belongs_to LIMIT 1",$conn));
			echo "<b>$fn11</b></font>";
			echo "<br />"; 
		} else {
			echo "<font color=grey>w magazynie:<br />";
			list($fn11)=mysql_fetch_array(mysql_query("SELECT filia_nazwa FROM $dbname.serwis_filie WHERE filia_id=$temp_belongs_to LIMIT 1",$conn));
			echo "<b>$fn11</b><br />";
			echo "</font></b>";
		}
		
	} else 
	{
		if ($temp_status=='-1') echo "<b>wprowadzona</b>";
		if (($temp_status=='0') || ($temp_status=='5')) { echo "<b>na stanie</b>"; }
		if ($temp_status=='5') {
			echo "<br />element zestawu";
		}
	}
	
	if ($temp_status=='1') { 
		list($osoba,$pion_nazwa,$up_nazwa)=mysql_fetch_array(mysql_query("SELECT sprzedaz_osoba, sprzedaz_pion_nazwa, sprzedaz_up_nazwa FROM $dbname.serwis_sprzedaz WHERE sprzedaz_pozycja_id=$temp_id LIMIT 1",$conn));
		echo "<a title=' Sprzedaż dla $pion_nazwa $up_nazwa ' class=normalfont href=#>";
		echo "<b>sprzedany</b><br />";
		if ($osoba) { 
			echo "<sub>$temp_datasprzedazy - $osoba</sub></a>";
		}
		
	}
	if ($temp_status=='9') { 

		$sql_r = "SELECT sprzedaz_data FROM $dbname.serwis_sprzedaz WHERE sprzedaz_pozycja_id=$temp_id LIMIT 1";
		$result_r = mysql_query($sql_r, $conn) or die($k_b);
		$newArray_r = mysql_fetch_array($result_r);
		$r_miesiac = $newArray_r['sprzedaz_data'];
		$okres = substr($r_miesiac,0,7);
	
		echo "<b>ujęty w raporcie</b><br /><sub>$okres</sub>"; 
	}
	
	echo "</td>";
	
	echo "<td class='center nowrap'>";
	
		if ($temp_belongs_to==$es_filia) {
			if (($temp_status==0) || ($temp_status==-1) || ($temp_status==5)) {
			
				if ($temp_btf!=$temp_belongs_to) {
					if ($_REQUEST[readonly]!='tak') 
						echo "<a title=' Popraw pozycję $temp_nazwa na fakturze'><input class=imgoption type=image src=img/edit_s.gif onclick=\"newWindow(800,300,'e_faktura_pozycja_simple.php?id=$temp_id&trodzaj=".urlencode($temp_rs)."'); return false;\"></a>";					
				} else {
					echo "<a title=' Popraw pozycję $temp_nazwa na fakturze'><input class=imgoption type=image src=img/edit.gif onclick=\"newWindow(800,600,'e_faktura_pozycja.php?id=$temp_id&trodzaj=".urlencode($temp_rs)."'); return false;\"></a>";
				}
			}
			
			if (($temp_status==0) || ($temp_status==-1)) {
				if ($temp_btf!=$temp_belongs_to) {
					if ($_REQUEST[readonly]!='tak') 
						echo "<a title=' Edytuj uwagę dla tego towaru / usługi '><input class=imgoption type=image src=img/edit_comment.gif onclick=\"newWindow(480,245,'e_towar_uwagi.php?id=$temp_id'); return false;\"></a>";
				}
			} else echo "<a title=' Czytaj uwagi o towarze '><input class=imgoption type=image src=img/comment.gif onclick=\"newWindow(480,265,'p_towar_uwagi.php?id=$temp_id'); return false;\"></a>";
			
			// -
			// access control 
			$accessLevels = array("9");
			if(array_search($es_prawa, $accessLevels)>-1){
				if (($temp_status==-1) && ($temp_belongs_to==$es_filia)) {
					echo "<a title=' Usuń pozycję $temp_nazwa o numerze seryjnym $temp_sn z faktury $temp_numer1 '><input class=imgoption type=image src=img/delete.gif  onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_faktura_pozycja.php?id=$temp_id'); return false;\"></a>";
					} else echo "&nbsp;";
			} else echo "&nbsp;";

			if ($hd_zgl_id>0) {
				$LinkHDZglNr=$hd_zgl_id; include('linktohelpdesk.php');
			}
			// koniec accesky 
		}
	echo "</td>";

	$i+=1;
	echo "</tr>";
}
endtable();
startbuttonsarea("right");
if ($temp_statusf==0) {
	echo "<span style='float:left'>";
	echo "<input type=button class=buttons value='Dodaj nowe pozycje do faktury' onclick=\"newWindow_r(800,600,'d_faktura_pozycja.php?id=$temp_id1&curr=$i');\"><br />";
	
	echo "</span>";
}

if ($temp_knf!='') echo "Wpisana kwota netto faktury: <b>".correct_currency($temp_knf)." zł</b>&nbsp;<br /> ";

echo "Łączna wartość netto pozycji na fakturze: <b>".correct_currency($kwotarazem)." zł</b>&nbsp;<br />";

if ($temp_knf!='') {
	$roznica = correct_currency($temp_knf) - correct_currency($kwotarazem);
	
	$roznica1 = abs($roznica*100);
	
		$o_grosz = 0;
		if ((strlen($roznica1)==1) && (substr($roznica1,0,1)=='1')){
			$o_grosz = 1;
			$o_wiecej = 0;
		} else { 
			if (substr($roznica1,0,1)!='0') {
				$o_grosz = 0; 
				$o_wiecej = 1; 
			} else {
				$o_grosz = 0; 
				if ($roznica1==0) {
					$o_wiecej = 0; 
				} else {
					$o_wiecej = 1; 
				}
				}
		}
	
	if ($o_grosz==1) { $set_color = '#FF5500'; } else { $set_color = 'red'; }

	$r1 = abs($roznica*100);
	if (($r1>0) && ($r1<=10)) { $set_color = '#FF5500'; }

	if ($roznica>0) echo "<br /><font color=$set_color>Wartość wpisanych pozycji na fakturze jest mniejsza niż wpisanej kwoty faktury netto o: <b>".correct_currency($roznica)." zł</b>&nbsp;</font><br /><br />";
	if ($roznica==0) echo "<br /><b><font color=green>Wpisana kwota netto faktury jest zgodna z wartością wpisanych pozycji</b></font>&nbsp;<br /><br />";
	if ($roznica<0) echo "<br /><font color=$set_color>Wartość wpisanych pozycji przekracza wpisaną kwotę faktury netto o: <b>".correct_currency(abs($roznica))." zł</b>&nbsp;</font><br /><br />";
}
endbuttonsarea();

echo "<input type=hidden name=ilosc value=$j>";

echo "<input type=hidden name=id value=$id>";
echo "<input type=hidden name=kosztywysylki value=$sumapf>";

// -
// access control 
$accessLevels = array("9");
if(array_search($es_prawa, $accessLevels)>-1) {

//if ($temp_koszty1!='0.00') {	
if ($temp_statusf!=9) {

  if ($sumapf>0) {
	startbuttonsarea("right");
	addselectallbutton("'Zaznacz wszystkie'");
	addinvertbutton("'Odwróć zaznaczenie'");
	addclearselectionbutton("'Odczytaj ponownie'");
	addownsubmitbutton("'Dolicz koszty i zapisz zmiany'","submit99");
	endbuttonsarea();
 }
}

}
// access control koniec
// -	
_form();

} else { 
	errorheader("Brak pozycji na tej fakturze");
	startbuttonsarea("right");
	addownlinkbutton("'Dodaj pozycje do tej faktury'","Button1","button","newWindow_r(800,600,'d_faktura_pozycja.php?id=$id&curr=0')");
	endbuttonsarea();
	}

	$sql1 = "SELECT * FROM $dbname.serwis_podfaktury WHERE pf_nr_faktury_id='$id'";
	$result1 = mysql_query($sql1, $conn) or die($k_b);

	if (mysql_num_rows($result1)!=0) {
	   
	   $newArray = mysql_fetch_array($result1);
	   
		$temp_id  			= $newArray['pf_id'];
		$temp_nrfaktury		= $newArray['pf_nr_faktury_id'];
		$temp_numer			= $newArray['pf_numer'];
		$temp_data			= $newArray['pf_data'];
		$temp_dostawca		= $newArray['pf_dostawca_nazwa'];
		$temp_kwota_netto	= $newArray['pf_kwota_netto'];
		$temp_uwagi			= $newArray['pf_uwagi'];
		
		br();
		pageheader("Wykaz podfaktur do faktury nr ".$temp_numer1." z dnia ".$temp_data1."");

		starttable();
		echo "<tr><th class=center>LP</th><th>Numer faktury</th><th>Data<br />wystwienia</th><th>Dostawca</th>";
		
	// -
	// access control 
	$accessLevels = array("1","9");
	if(array_search($es_prawa, $accessLevels)>-1){
		echo "<th class=right>Kwota<br />podfaktury</th>";
	}
	// access control koniec
	// -	

		echo "<th class=center>Uwagi</th>";
		
	// -
	// access control 
	$accessLevels = array("9");
	if(array_search($es_prawa, $accessLevels)>-1){
		echo "<th class=center>Opcje</th>";
	}
	// access control koniec
	// -		
		
		echo "</tr>";

	$sql1 = "SELECT * FROM $dbname.serwis_podfaktury WHERE pf_nr_faktury_id='$id'";
	$result1 = mysql_query($sql1, $conn) or die($k_b);

	$i = 550;
	$j = 1;
	$k = 0;
	$koszty_decrtypted=0;
		
	while ($newArray = mysql_fetch_array($result1)) {
		$temp_id  			= $newArray['pf_id'];
		$temp_nrfaktury		= $newArray['pf_nr_faktury_id'];
		$temp_numer			= $newArray['pf_numer'];
		$temp_data			= $newArray['pf_data'];
		$temp_dostawca		= $newArray['pf_dostawca_nazwa'];
		$temp_kwota_netto	= $newArray['pf_kwota_netto'];
		$temp_uwagi			= $newArray['pf_uwagi'];
		
		$koszty_decrtypted += decrypt_md5($temp_kwota_netto,$key);
		
		tbl_tr_highlight($i);
		$i++;
		echo "<td width=30 class=center>$j</td>";

		echo "<td>$temp_numer";
	
		if ($pokazpoczatekuwag==1) pokaz_uwagi($temp_uwagi,$iloscznakowuwag,"newWindow(480,330,'p_podfaktura_uwagi.php?id=$temp_id&nr=$temp_numer')");
		
		echo "</td>";
		echo "<td width=70>$temp_data</td>"; 
		
		echo "<td>$temp_dostawca</td>";

	// -
	// access control 
	$accessLevels = array("1","9");
	if(array_search($es_prawa, $accessLevels)>-1){
		echo "<td class=right width=80>";
		echo correct_currency(decrypt_md5($temp_kwota_netto,$key));
		echo " zł</td>";
	}
	// access control koniec
	// -		
	echo "<td width=50 class=center>";
	$uwagisa = ($temp_uwagi!='');
	if ($uwagisa=='1') {
		echo "<a title=' Czytaj uwagi '><input class=imgoption type=image src=img/comment.gif onclick=\"newWindow(480,330,'p_podfaktura_uwagi.php?id=$temp_id&nr=$temp_numer'); return false;\"></a>";
	}

	//if ($soldout!=1) {
		echo "<a title=' Edytuj uwagi '><input class=imgoption type=image src=img/edit_comment.gif onclick=\"newWindow(480,260,'e_podfaktura_edit.php?id=$temp_id&nr=$temp_numer'); return false;\"></a>";
	//}
	echo "</td>";
	echo "<td class=center width=50>";
	if ($temp_statusf!=9) {
	echo "<a title=' Edytuj podfakturę numer $temp_numer wystawioną przez $temp_dostawca '><input class=imgoption type=image src=img/edit.gif onclick=\"newWindow(600,295,'e_podfaktura.php?id=$temp_id&blockprice=$raported'); return false;\"></a>";
	}
	
	$accessLevels = array("9");
	if(array_search($es_prawa, $accessLevels)>-1){		
	if (($temp_statusf!=9)) // && ($soldout!=1)) 
	{
		
		echo "<a title=' Usuń podfakturę numer $temp_numer wystawioną przez $temp_dostawca '><input class=imgoption type=image src=img/delete.gif onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_podfaktura.php?id=$temp_id'); return false;\"></a>";
		
		} 
	} 
	echo "</td>";
	// access control koniec
	// -	
	
		echo "</tr>";
		}

	endtable();
	startbuttonsarea("right");
	echo "Łączne koszty dodatkowe : <b>".correct_currency($koszty_decrtypted)." zł</b>";
	endbuttonsarea();
	
//	echo "<input type=hidden name=ilosc value=$k>";
//	echo "<input type=hidden name=id value=$id>";

	} else {
		echo "<br />";
		errorheader("Brak podfaktur dla faktury nr ".$temp_numer1."");
	}
if ($temp_statusf!=9) {
	startbuttonsarea("right");
	addownlinkbutton("'Dodaj nową podfakturę'","Button1","button","newWindow_r(800,400,'d_podfaktura.php?id=$id')");
	endbuttonsarea();
}
	
startbuttonsarea("right");
addclosewithreloadbutton("Zamknij");
endbuttonsarea();
?>
<?php include('body_stop.php'); ?>
</body>
</html>