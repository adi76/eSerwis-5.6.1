<?php 
include_once('header_begin.php'); 
include_once('cfg_helpdesk.php'); 
?>
<script>
function potwierdz() {
  if (confirm("Czy na pewno chcesz zatwierdzić raport ?\n\nPo jego zatwierdzeniu dalsza sprzedaż w raportowanym okresie będzie niemożliwa.\n\n")) {
		return true 
	} else return false;
}
function usun_potwierdz() {
  if (confirm("Czy na pewno chcesz usunąć zatwierdzenie raportu ?\n\nPo jego usunięciu możliwa będzie sprzedaż w raportowanym okresie.\n\n")) {
		return true 
	} else return false;
}

</script>
</head>
<body>
<?php 
include('inc_encrypt.php');
//include('convert.php');
if ($usun_zatwierdzenie=='Usuń zatwierdzenie raportu') {

	$rr44 = substr($okres_do,0,4);
	$mm44 = substr($okres_do,5,2);
		
	$ile = $_POST[ilosc];
	$o = 1;
	while ($o<$ile)	{

		$sprzedazid		= $_POST['sid'.$o];
		$pozid			= $_POST['pid'.$o];
		$fakid			= $_POST['fnr'.$o];

		$miesiac 		= $_POST[u_miesiac];
		$rok 			= $_POST[u_rok];
		
		$sql_u = "UPDATE $dbname.serwis_sprzedaz SET sprzedaz_status='1' WHERE sprzedaz_id=$sprzedazid LIMIT 1";
		$result_u = mysql_query($sql_u, $conn) or die($k_b);

		$sql_u = "UPDATE $dbname.serwis_faktura_szcz SET pozycja_status=1 WHERE pozycja_id=$pozid LIMIT 1";
		$result_u = mysql_query($sql_u, $conn) or die($k_b);

		$sql_u = "UPDATE $dbname.serwis_faktury SET faktura_status=1 WHERE faktura_id=$fakid LIMIT 1";
		$result_u = mysql_query($sql_u,$conn) or die($k_b);
		$o++;
	}
	
	$sql_d1="DELETE FROM $dbname.serwis_sprzedaz_raport WHERE (sr_rok = '$rr44') and (sr_miesiac='$mm44') and (belongs_to=$es_filia)";
	
	$result_d1 = mysql_query($sql_d1,$conn) or die($k_b);
	
	okheader("Pomyślnie usunięto zatwierdzenie raportu za okres <b>$miesiac-$rok</b>");
	$submit='Generuj raport';
	$zapiszzmiany = 'koniec';
}

if ($zatwierdz=='Zatwierdź raport') { 
	$ile = $_POST[ilosc];
	$o = 1;
	while ($o<$ile)	{
		
		$tcena			= $_POST['tc'.$o];
		$sprzedazid		= $_POST['sid'.$o];
		$pozid			= $_POST['pid'.$o];
		$fakid			= $_POST['fnr'.$o];
		
		$cenatwoja 	= str_replace(',','.',$tcena);
		$cenatwoja_cr 	= crypt_md5($cenatwoja,$key);
		
		$miesiac = intval(substr($_POST[okres_do],5,2));
		$rok = substr($_POST[okres_do], 0,4);
		
		$sql_u = "UPDATE $dbname.serwis_sprzedaz SET sprzedaz_pozycja_cenatwoja='$cenatwoja_cr', sprzedaz_status='9' WHERE sprzedaz_id=$sprzedazid LIMIT 1";
		$result_u = mysql_query($sql_u, $conn) or die($k_b);

		$sql_u = "UPDATE $dbname.serwis_faktura_szcz SET pozycja_status=9 WHERE pozycja_id=$pozid LIMIT 1";
		$result_u = mysql_query($sql_u, $conn) or die($k_b);

		$sql_u = "UPDATE $dbname.serwis_faktury SET faktura_status=9 WHERE faktura_id=$fakid LIMIT 1";
		$result_u = mysql_query($sql_u,$conn) or die($k_b);
		$o++;
	}
	
	$sql_u = "INSERT INTO $dbname.serwis_sprzedaz_raport VALUES ('',$rok,$miesiac,$es_filia)";
	$result_u = mysql_query($sql_u,$conn) or die($k_b);
		
	okheader("Raport został zatwierdzony - sprzedaż w miesiącu, którego raport dotyczył została zamknięta");
	
	$submit='Generuj raport';
	$zapiszzmiany='koniec';
	$zatwierdz='koniec';
}

if ($zapiszzmiany=='Zapisz zmiany') { 
	$ile = $_POST[ilosc];
	
	$o = 1;
	while ($o<$ile)
	{
		$tcena			= $_POST['tc'.$o];
		$sprzedazid		= $_POST['sid'.$o];
		
		//$cenatwoja 		= str_replace(',','.',$tcena);
		$cenatwoja		= correct_currency($tcena);
		$cenatwoja_cr 	= crypt_md5($cenatwoja,$key);

		$sql_u = "UPDATE $dbname.serwis_sprzedaz SET sprzedaz_pozycja_cenatwoja='$cenatwoja_cr' WHERE sprzedaz_id=$sprzedazid LIMIT 1";
	//	$sql_u1 = "UPDATE $dbname.serwis_sprzedaz SET sprzedaz_pozycja_cenatwoja='$cenatwoja' WHERE sprzedaz_id=$sprzedazid LIMIT 1";
	//	echo "$sql_u<br/>$sql_u1<br/><br />";
		$result_u = mysql_query($sql_u, $conn);// or die($k_b);
	
	  $o++;
	}
	
	okheader("Wprowadzone przez Ciebie zmiany zostały zapisane");
	$submit='Generuj raport';
	$zapiszzmiany = 'koniec';
	$ustalceny=$_REQUEST[ustalceny];
}	

if ($pokazwybrane=='Pokaż tylko wybrane') 
{ 
	$ile = $_POST[ilosc];
	$o = 1;
	while ($o<$ile)
	{
		$tzaznaczone	= $_POST['rpt'.$o];
		$sprzedazid		= $_POST['sid'.$o];
		
		if ($tzaznaczone=='on') $status='on'; 
		if ($tzaznaczone=='')   $status='';
		$sql_u = "UPDATE $dbname.serwis_sprzedaz SET sprzedaz_temp='$status' WHERE sprzedaz_id=$sprzedazid LIMIT 1";
		$result_u = mysql_query($sql_u, $conn) or die($k_b);
	
	  $o++;
	}

	$submit='Generuj raport';
	$zapiszzmiany = 'koniec';
}	

if (($pokazwybrane=='Pokaż wszystkie') || ($pokazwybrane=='Zaznacz wszystkie') || ($pokazwybrane=='Pokaż wszystko'))
{ 
	$ile = $_POST[ilosc];
	
	$o = 1;
	while ($o<$ile)
	{
		$tzaznaczone	= $_POST['rpt'.$o];
		$sprzedazid		= $_POST['sid'.$o];
		
		$sql_u = "UPDATE $dbname.serwis_sprzedaz SET sprzedaz_temp='on' WHERE sprzedaz_id=$sprzedazid LIMIT 1";
		$result_u = mysql_query($sql_u, $conn) or die($k_b);
	  $o++;
	}
	$submit='Generuj raport';
	$zapiszzmiany = 'koniec';
}	

if ($pokazwybrane=='selectall') {
	$ile = $_POST[ilosc];
	$o = 1;
	while ($o<$ile)	{
		$tzaznaczone	= $_POST['rpt'.$o];
		$sprzedazid		= $_POST['sid'.$o];
		
		$sql_u = "UPDATE $dbname.serwis_sprzedaz SET sprzedaz_temp='on' WHERE sprzedaz_id=$sprzedazid LIMIT 1";
		$result_u = mysql_query($sql_u, $conn) or die($k_b);
		echo "selectall - zmieniam status na ON<Br />";
	
	  $o++;
	}

	$submit='Generuj raport';
	$zapiszzmiany = 'koniec';
	$pokazwybrane = 'all';

}	

if ($submit) { 
include('body_start.php');
$unique=rand(0,999999);

$sql99="TRUNCATE TABLE $dbname.serwis_temp_raport$es_filia";
$result99=mysql_query($sql99,$conn) or die($k_b);

$tumowa=$_REQUEST[tumowa];
$okres_od=$_REQUEST[okres_od];
$okres_do=$_REQUEST[okres_do];

$il_umow = $_REQUEST[umowailosc];

$miesiac = intval(substr($_REQUEST[okres_do],5,2));
$rok = substr($_REQUEST[okres_do], 0,4);

$czyraportwyg_sql = "SELECT * FROM $dbname.serwis_sprzedaz_raport WHERE ((sr_rok=".$rok.") and (sr_miesiac=".$miesiac.") and (belongs_to=".$es_filia.")) LIMIT 1";
$czyraportwyg_wyg = mysql_query($czyraportwyg_sql,$conn) or die($k_b);
$czyraportwyg = mysql_num_rows($czyraportwyg_wyg);

echo "<input type=hidden name=tumowa value='$tumowa'>";

if (($tumowa!='') && ($okres_od!='') && ($okres_do!='')) {

if ($tumowa=='all') 
{
	$sql_a = "SELECT * FROM $dbname.serwis_sprzedaz WHERE ";
//	$sql_a = "SELECT * FROM $dbname.serwis_sprzedaz,serwis_umowy WHERE ";
	if ($pokazwybrane=='Pokaż tylko wybrane')
	{
		$sql_a = $sql_a . "(sprzedaz_temp='on') and";
	}
	
	$sql_a = $sql_a . "(";
	if ($il_umow>0) {
		for ($i = 1; $i <= $il_umow; $i++) 
		{
			$nr_umowy = $_REQUEST['umowa'.$i.''];
			$sql_a = $sql_a . " (sprzedaz_umowa_nazwa='$nr_umowy') ";
			if ($i==$il_umow) { $sql_a = $sql_a . ") AND"; } else { $sql_a = $sql_a . " OR"; }
			echo "<input type=hidden name=umowa$i value='$nr_umowy'>";
		}
	}
	
	$sql_a .= " (sprzedaz_data BETWEEN '$okres_od' AND '$okres_do') and (serwis_sprzedaz.belongs_to=$es_filia) and (sprzedaz_pozycja_id<>0) ";
//	$sql_a .= " AND (serwis_sprzedaz.sprzedaz_umowa_nazwa=serwis_umowy.umowa_nr)";
//	$sql_a .= " AND (serwis_sprzedaz.sprzedaz_umowa_nazwa=serwis_umowy.umowa_nr)";
	if (($_REQUEST[ustalceny]=='1') && ($czyraportwyg==0)) { 
		//$sql_a = $sql_a ." ORDER BY serwis_sprzedaz.sprzedaz_typ ASC, serwis_sprzedaz.sprzedaz_pozycja_nazwa ASC, serwis_umowy.umowa_nr_zlecenia ASC";
		$sql_a = $sql_a ." ORDER BY serwis_sprzedaz.sprzedaz_typ ASC, serwis_sprzedaz.sprzedaz_pozycja_nazwa ASC";		
	} else {
		//$sql_a = $sql_a." ORDER BY serwis_umowy.umowa_nr_zlecenia ASC, serwis_sprzedaz.sprzedaz_data ASC, serwis_sprzedaz.sprzedaz_unique ASC";
		$sql_a = $sql_a." ORDER BY serwis_sprzedaz.sprzedaz_umowa_nazwa ASC, serwis_sprzedaz.sprzedaz_data ASC";
//		$sql_a = $sql_a." ORDER BY serwis_umowy.umowa_nr_zlecenia ASC, serwis_sprzedaz.sprzedaz_data ASC";
	}
	
	
} else {
//	$sql_a = "SELECT * FROM $dbname.serwis_sprzedaz,serwis_umowy WHERE ";
	$sql_a = "SELECT * FROM $dbname.serwis_sprzedaz WHERE ";
	if ($pokazwybrane=='Pokaż tylko wybrane')
	{
		$sql_a = $sql_a . "(sprzedaz_temp='on') and";
	}
	$sql_a .= " (serwis_sprzedaz.sprzedaz_umowa_nazwa='$tumowa') and (serwis_sprzedaz.sprzedaz_data BETWEEN '$okres_od' AND '$okres_do') and (serwis_sprzedaz.belongs_to=$es_filia) and (sprzedaz_pozycja_id<>0)";
//	$sql_a .= " AND (serwis_sprzedaz.sprzedaz_umowa_nazwa=serwis_umowy.umowa_nr)";
//	$sql_a .= " AND (serwis_sprzedaz.sprzedaz_umowa_nazwa=serwis_umowy.umowa_nr)";
	if (($_REQUEST[ustalceny]=='1') && ($czyraportwyg==0)) {
		//$sql_a = $sql_a ." ORDER BY serwis_sprzedaz.sprzedaz_typ ASC, serwis_sprzedaz.sprzedaz_pozycja_nazwa ASC, serwis_umowy.umowa_nr_zlecenia ASC";
		$sql_a = $sql_a ." ORDER BY serwis_sprzedaz.sprzedaz_typ ASC, serwis_sprzedaz.sprzedaz_pozycja_nazwa ASC";		
	} else {
		//$sql_a = $sql_a." ORDER BY serwis_umowy.umowa_nr_zlecenia ASC, serwis_sprzedaz.sprzedaz_data ASC, serwis_sprzedaz.sprzedaz_unique ASC";
		$sql_a = $sql_a." ORDER BY serwis_sprzedaz.sprzedaz_umowa_nazwa ASC, serwis_sprzedaz.sprzedaz_data ASC, serwis_sprzedaz.sprzedaz_unique ASC";
	}
}

//echo "$sql_a";
$result_a = mysql_query($sql_a, $conn) or die($k_b);
$count_rows=0;

while ($dane3 = mysql_fetch_array($result_a)) {
  $count_rows+=1;				
}

if ($count_rows==0) {

	errorheader("Nie znaleziono pozycji spełniających podane przez Ciebie kryteria");
	startbuttonsarea("right");
	addbackbutton("Wróć do poprzedniego widoku");
	addbuttons("start");
	endbuttonsarea();
	echo "</body></html>";
  exit;
  
} else {

pageheader("Generowanie raportu ze sprzedaży z okresu",1,1);

?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();

//$width = "<script>document.write((screen.width-300)/2); </script>"; 
//$height = "<script>document.write((screen.height-20)/2); </script>"; 

if ($tumowa!='all') infoheader("<b>".$okres_od." - ".$okres_do."</b><br /><br />dla umowy nr <b>".$tumowa."</b>");
if ($tumowa=='all') infoheader("<b>".$okres_od." - ".$okres_do."</b><br /><br />dla wszystkich umów przypisanych do aktualnej filii/oddziału");

starttable();
echo "<form name=raport action=p_towary_raport_sprzedaz.php METHOD=POST>";
echo "<tr>";
echo "<th class=center>LP</th>";

// do raportu
echo "<th class=center>do<br />raportu</th>";

echo "<th>Nazwa towaru<br />Rodzaj sprzedaży</th><th class=center>Nr zgłoszenia Helpdesk<br /><font color=red>Nr Landesk</font></th><th class=right>Cena netto<br />z faktury</th><th class=right>Cena netto<br />zakupu</th><th class=right>Sugerowana cena<br />odsprzedaży</th><th width=10 title=' Weryfikacja ceny netto odsprzedaży '>~</th><th class=left>Twoja cena<br />netto odsprzedaży</th><th>Sprzedano dla</th><th class=center>Nr zlecenia</th><th class=center>Opcje</th>";

echo "</tr>";
}

/*if ($tumowa=='all') { 
	$sql = "SELECT * FROM $dbname.serwis_sprzedaz WHERE (belongs_to=$es_filia) and (sprzedaz_data BETWEEN '$okres_od' AND '$okres_do') ORDER BY sprzedaz_typ ASC";

} else {
$sql = "SELECT * FROM $dbname.serwis_sprzedaz WHERE (belongs_to=$es_filia) and (sprzedaz_umowa_nazwa='$tumowa') and (sprzedaz_data BETWEEN '$okres_od' AND '$okres_do') ORDER BY sprzedaz_typ ASC";
}
*/
$result = mysql_query($sql_a, $conn) or die($k_b);

$i = 0;
$j = 1;
$pokaz_zapis=0;

while ($newArray = mysql_fetch_array($result)) {
	$temp_id  			= $newArray['sprzedaz_id'];
	$temp_poz_pid		= $newArray['sprzedaz_pozycja_id'];
	$temp_poz_nazwa		= $newArray['sprzedaz_pozycja_nazwa'];
	$temp_poz_sn		= $newArray['sprzedaz_pozycja_sn'];
	$temp_poz_cena_cr	= $newArray['sprzedaz_pozycja_cenanetto'];

	$temp_poz_cenaodsp_cr	= $newArray['sprzedaz_pozycja_cenaodsp'];
	$temp_poz_cenatwoja_cr	= $newArray['sprzedaz_pozycja_cenatwoja'];

	$temp_data			= $newArray['sprzedaz_data'];
	$temp_umowa			= $newArray['sprzedaz_umowa_nazwa'];
	$temp_pion			= $newArray['sprzedaz_pion_nazwa'];
	$temp_up			= $newArray['sprzedaz_up_nazwa'];
	$temp_uwagi			= $newArray['sprzedaz_uwagi'];
	$temp_fakt_id		= $newArray['sprzedaz_faktura_id'];
	$temp_rodzaj		= $newArray['sprzedaz_rodzaj'];
	$temp_typ			= $newArray['sprzedaz_typ'];
	$temp_temp 			= $newArray['sprzedaz_temp'];

	$temp_status		= $newArray['sprzedaz_status'];

	$sql2 = "SELECT * FROM $dbname.serwis_umowy WHERE (umowa_nr='$temp_umowa')";
	$result2 = mysql_query($sql2, $conn) or die($k_b);		
	$newArray2 = mysql_fetch_array($result2);
	$temp_nrzlecenia = $newArray2['umowa_nr_zlecenia'];

	if ($temp_status=='9') { 
		$zatwierdz='koniec'; 
	/*	
		?><script>document.getElementById('doraportu').style.display='';</script><?php
	*/
	} else { $zatwierdz='123'; }

	$temp_poz_cena	= decrypt_md5($temp_poz_cena_cr,$key);
	
	$temp_poz_cenaodsp	= decrypt_md5($temp_poz_cenaodsp_cr,$key);	
	$temp_poz_cenatwoja	= decrypt_md5($temp_poz_cenatwoja_cr,$key);	
	
	list($kolorgrupy)=mysql_fetch_array(mysql_query("SELECT rola_kolor FROM $dbname.serwis_slownik_rola WHERE rola_nazwa='$temp_typ' LIMIT 1",$conn));
	
	if (($czyraportwyg==0) && ($_REQUEST[ustalceny]=='1')) {
		tbl_tr_color($i, $kolorgrupy);
	} else tbl_tr_highlight($i);
	
	echo "<td class=center width=30><a title=' $temp_id ' href=# class=normalfont>$j</a>";
	echo "<input type=hidden name=sid$j value=$temp_id>";
	echo "<input type=hidden name=pid$j value=$temp_poz_pid>";
	
	echo "</td>";
	echo "<td class=center>";

	if ($temp_status=='9') {
		if ($zatwierdz=='koniec') 
		{
			echo "<input class=border0 type=checkbox name=rpt$j ";
			if ($temp_temp=='on') echo "checked";
			echo ">";
		} else echo "TAK";
	} else
	{
		if ($zatwierdz=='koniec') 
		{
			echo "<input class=border0 type=checkbox name=rpt$j ";
			if ($temp_temp=='on') echo "checked";
			echo ">";
		} else echo "<b>NIE</b>";
	}
	echo "</td>";
	
	$sql1="SELECT * FROM $dbname.serwis_faktury WHERE (faktura_id='$temp_fakt_id')";
	//echo "$sql1";
	$result1 = mysql_query($sql1, $conn) or die($k_b);
	
	while ($newArray4 = mysql_fetch_array($result1)) {
		$temp_id4  			= $newArray4['faktura_id'];
		$temp_numer4		= $newArray4['faktura_numer'];
		$temp_data4			= $newArray4['faktura_data'];
		$temp_dostawca4		= $newArray4['faktura_dostawca'];	
	}

	$sql_czyjestpodfaktura = "SELECT pf_id FROM $dbname.serwis_podfaktury WHERE (pf_nr_faktury_id=$temp_id4)";
	$wynik_czyjestpodfaktura = mysql_query($sql_czyjestpodfaktura,$conn) or die($k_b);
	$jest_podfaktura = (mysql_num_rows($wynik_czyjestpodfaktura)>0);

	if ($jest_podfaktura) {
		$doliczono_koszty=false;
		$sql_czydoliczono_koszty = "SELECT pozycja_id FROM $dbname.serwis_faktura_szcz WHERE ((belongs_to=$es_filia) && (pozycja_nr_faktury=$temp_id4) and (pozycja_dolicz_koszty='on'))";
		$wynik_czydoliczono_koszty = mysql_query($sql_czydoliczono_koszty,$conn) or die($k_b);
		$doliczono_koszty=(mysql_num_rows($wynik_czydoliczono_koszty)>0);
	} else $doliczono_koszty=true;
	
	echo "<input type=hidden name=fnr$j value=$temp_id4>";
	echo "<td>$temp_poz_nazwa";

	if ($doliczono_koszty==false) {
		echo "<a title=' Nie doliczono kosztów z podfaktur do żadnej z pozycji na fakturze zakupowej '><input class=imgoption type=image src=img/info_price.gif align=top onclick=\"newWindow_r(800,600,'p_faktura_pozycje.php?id=$temp_id4'); return false;\"></a>";
	}

	if ($pokazpoczatekuwag==1) pokaz_uwagi($temp_uwagi,$iloscznakowuwag,"newWindow(480,265,'p_sprzedaz_uwagi.php?id=$temp_id');return false;");	
	
	if ($temp_rodzaj!='') {
		echo "<br />";
		echo "<font color=grey>$temp_rodzaj</font>";
	} else { 
		echo "<br /><font color=grey><a class=normalfont title=' Nie określono rodzaju sprzedaży ' href=#>-</a></font>";
	}
	
	//echo "<br />$temp_data";
	echo "</td>";
	
	echo "<td class=center>";
		list($hd_zgl_id)=mysql_fetch_array(mysql_query("SELECT wp_zgl_id FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_sprzedaz_fakt_szcz_id='$temp_poz_pid') and (wp_sprzet_active=1) LIMIT 1",$conn_hd));
		if ($hd_zgl_id>0) {
			list($hd_zgl_hadim)=mysql_fetch_array(mysql_query("SELECT zgl_poczta_nr FROM $dbname_hd.hd_zgloszenie WHERE (zgl_nr='$hd_zgl_id') LIMIT 1",$conn_hd));
			$LinkHDZglNr=$hd_zgl_id; 
			echo "<a class=normalfont href=# title='Powiązanie ze zgłoszeniem numer ".$LinkHDZglNr." w bazie Helpdesk' onclick=\"self.location.href='przekieruj_do_zgloszenia.php?sr=search-wyniki&search_zgl_nr=".$LinkHDZglNr."'; return false;\"><b>$LinkHDZglNr</b></a>";
			if ($hd_zgl_hadim>0) {
				//echo "<br /><a class=normalfont style='color:red' href=# title='Powiązanie ze zgłoszeniem numer ".$hd_zgl_hadim." w bazie HDIM' onclick=\"newWindow_r(800,600,'https://sd.cit.net.pp/helpdesk/issue.php?id=$hd_zgl_hadim'); return false;\"><b>$hd_zgl_hadim</b></a>";
			}
		} else echo "<b>-</b>";
	echo "</td>";
			
	echo "<td class=right>"; 
	
	if ($temp_poz_cena!=0) { echo correct_currency($temp_poz_cena)." zł"; }
	
	echo "</td>";
	echo "<td class=right>";
	if ($temp_poz_cenaodsp!=$temp_poz_cena) echo "<b>";
	echo correct_currency($temp_poz_cenaodsp)." zł";
	if ($temp_poz_cenaodsp!=$temp_poz_cena) echo "</b>";
	echo "</td>";

	if ($temp_poz_cenaodsp!=$temp_poz_cena) {
		$cena_z_marza = (float) $temp_poz_cena * $wskaznik_marzy;
		$cena_z_marza = round_up($cena_z_marza, 2);
		
		$cena_z_marza = $temp_poz_cenaodsp;
		
	} else {
		$cena_z_marza = (float) $temp_poz_cenaodsp * $wskaznik_marzy;
		$cena_z_marza = round_up($cena_z_marza, 2);
		
		$cena_z_marza = $temp_poz_cenaodsp;
	}
	
	//if ($temp_poz_cenatwoja=='') 
	$temp_poz_cenatwoja = $cena_z_marza;
	
	echo "<td class=right>".correct_currency($cena_z_marza)." zł";
	echo "<input type=hidden id=cs$j value='$cena_z_marza'>";
	
	echo "</td>";	// cena odsprzedaży
	
	echo "<td class=center>"; 
	
		if ($temp_poz_cenaodsp!=$temp_poz_cena) {
			$cena_do_porownania = correct_currency($temp_poz_cenatwoja);
		} else {
			$cena_do_porownania = correct_currency($temp_poz_cenatwoja);
		}
		
		if (correct_currency($cena_z_marza)==correct_currency($cena_do_porownania)) 
			echo "<img title=' Cena odsprzedaży jest optymalna ' src='img/coo.gif' border=0>";
			//echo "onClick=\"document.getElementById('tc".$j."').value=document.getElementById('cs".$j."').value; \"";
			//echo ">";
			
		if (correct_currency($cena_z_marza)>correct_currency($cena_do_porownania)) 
			echo "<img title='Cena odsprzedaży jest niższa od optymalnej. Kliknij, aby ustawić cene odsprzedaży = sugerowanej cenie'  src='img/cozm.gif' border=0 onClick=\"document.getElementById('tc".$j."').value=document.getElementById('cs".$j."').value; \">";
		
		if (correct_currency($cena_z_marza)<correct_currency($cena_do_porownania)) 
			echo "<img title='Cena odsprzedaży jest wyższa niż wg umowy.Kliknij, aby ustawić cene odsprzedaży = sugerowanej cenie' src='img/cozd.gif' border=0 onClick=\"document.getElementById('tc".$j."').value=document.getElementById('cs".$j."').value; \">";
		
	echo "</td>";
	
	echo "<td class=left>";

	if (($zatwierdz!='koniec') or ($temp_status!=9))
	{
		if ($temp_poz_cenaodsp!=$temp_poz_cena) {
			echo "<input type=text maxlength=10 id=tc$j name=tc$j size=9 value='".correct_currency($temp_poz_cenatwoja)."' style='text-align:right' onBlur=\"Przecinek_na_kropke(document.getElementById('tc$j'));\">&nbsp;zł";
		} else {
			echo "<input type=text maxlength=10 id=tc$j name=tc$j size=9 value='".correct_currency($temp_poz_cenatwoja)."' style='text-align:right' onBlur=\"Przecinek_na_kropke(document.getElementById('tc$j'));\">&nbsp;zł";
		}
	} else
		{
			echo "<input type=hidden maxlength=10 id=tc$j name=tc$j value='".correct_currency($temp_poz_cenatwoja)."'>".correct_currency($temp_poz_cenatwoja)." zł";
		}
	
	if ($temp_status==1) $pokaz_zapis=1;
	
	echo "</td>";	// twoja cena
	echo "<td>";

	if ($temp_pion!="") { echo "$temp_pion&nbsp;"; } 
	
	echo "$temp_up</td>";


	echo "<td class=center>$temp_nrzlecenia</td>";
	$j+=1;
	
	$r=substr($temp_data,0,4);
	$m=substr($temp_data,5,2);
	$d=substr($temp_data,8,2);
	
	
	$s1=strpos($temp_up,' ');
	$upup=substr($temp_up,$s1+1,strlen($temp_up)-$s1);
	
	echo "<td class=center>";
		$sql_czy_w_zestawie = "SELECT zestawpozycja_fszcz_id FROM $dbname.serwis_zestaw_szcz WHERE (zestawpozycja_fszcz_id=$temp_poz_pid) LIMIT 1";
		$wynik=mysql_num_rows(mysql_query($sql_czy_w_zestawie,$conn));
		if ($temp_status==1) {
			if ($wynik==0) {
				echo "<a title=' Anuluj sprzedaż: $temp_poz_nazwa '><input class=imgoption type=image src='img/money_delete_hd.gif' onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_towary_sprzedaz.php?id=$temp_poz_pid'); return false;\"></a>";
			} else {
				$sql_zestaw_id = "SELECT zestawpozycja_zestaw_id FROM $dbname.serwis_zestaw_szcz WHERE (zestawpozycja_fszcz_id=$temp_poz_pid) LIMIT 1";
				list($nr_zestawu)=mysql_fetch_array(mysql_query($sql_zestaw_id,$conn));	
				$sql_zestaw_nazwa = "SELECT zestaw_opis FROM $dbname.serwis_zestawy WHERE (zestaw_id=$nr_zestawu) LIMIT 1";
				list($nazwa_zestawu)=mysql_fetch_array(mysql_query($sql_zestaw_nazwa,$conn));
				
				if ($allow_sell==1) {
					echo "<a title=' Anuluj sprzedaż zestawu: $nazwa_zestawu '><input class=imgoption type=image src='img/sell_zestaw_delete.gif' onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_zestaw_sprzedaz.php?id=$nr_zestawu'); return false;\"></a>";
				}
						
				echo "<a title=' Element zestawu: $nazwa_zestawu '><input class=imgoption type=image src=img/basket.gif onclick=\"newWindow_r(800,600,'p_zestaw_pozycje.php?id=$nr_zestawu&sold=1&showall=1&paget=1'); return false;\"></a>";
			}
			echo "<a title=' Pokaż fakturę dla tego towaru / usługi '><input class=imgoption type=image src=img/search.gif  onclick=\"newWindow_r(800,600,'p_faktura_pozycje.php?id=$temp_fakt_id'); return false;\"></a>";
		}
			//echo "SELECT wp_zgl_id FROM $dbname_hd.zgl_wymiany_podzespolow WHERE wp_sprzedaz_fakt_szcz_id='$temp_poz_pid' LIMIT 1";
			
		if ($hd_zgl_id>0) {
			$LinkHDZglNr=$hd_zgl_id; include('linktohelpdesk.php');
		}
		
	echo "</td>";
	
	$cena = str_replace('.',',',$temp_poz_cena);
	
	$cenan1 = $temp_poz_cena*$wskaznik_marzy;
	$cenan = str_replace('.',',',$cenan1);
	
//	$raport_cena_z_faktury = crypt_md5($cena,$key);
//	$raport_cena_twoja_cr = crypt_md5($temp_poz_cenatwoja,$key);

	$raport_cena_z_faktury_cr = str_replace('.',',',correct_currency($cena));
	$raport_cena_twoja_cr = str_replace('.',',',correct_currency($temp_poz_cenatwoja));

	// $cena = str_replace('.',',',$temp_poz_cena);
	
	$uppluspion = $temp_pion." ".$temp_up;
	$sql99="INSERT INTO $dbname.serwis_temp_raport$es_filia VALUES ('$temp_poz_nazwa','$uppluspion','1','szt.','$raport_cena_z_faktury_cr','$raport_cena_twoja_cr','$temp_numer4','$temp_data4','$temp_dostawca4','$temp_rodzaj','$unique','$temp_nrzlecenia')";

//	if (document.raport.rpt$i.checked==false) 
	$result99=mysql_query($sql99,$conn) or die($k_b);

	$sql99 = "UPDATE $dbname.serwis_sprzedaz SET sprzedaz_temp='on' WHERE sprzedaz_id=$temp_id LIMIT 1";
	$result99 = mysql_query($sql99,$conn) or die($k_b);
	
	$i+=1;
	echo "</tr>";
}

endtable();
oddziel();
$sqlf="SELECT * FROM $dbname.serwis_filie WHERE filia_id=$es_filia";
$resultf = mysql_query($sqlf, $conn) or die($k_b);
$dane1f = mysql_fetch_array($resultf);
$filian = $dane1f['filia_nazwa'];


	if (($zatwierdz!='koniec') or ($pokaz_zapis==1)) {
		startbuttonsarea("right");
		echo "<span style='float:left'>";
		if ($_REQUEST[ustalceny]=='0') {
		addownlinkbutton2("'Sortuj wg typu sprzętu'","button","button","p_towary_raport_sprzedaz.php?tumowa=$tumowa&umowa1=".urlencode($umowa1)."&umowa2=".urlencode($umowa2)."&umowa3=".urlencode($umowa3)."&umowa4=".urlencode($umowa4)."&umowa5=".urlencode($umowa5)."&umowa6=".urlencode($umowa6)."&umowa7=".urlencode($umowa7)."&umowa8=".urlencode($umowa8)."&umowa9=".urlencode($umowa9)."&umowa10=".urlencode($umowa10)."&okres_od=$okres_od&okres_do=$okres_do&umowailosc=$il_umow&ustalceny=1&submit=1");
		}
		if ($_REQUEST[ustalceny]=='1') {
		addownlinkbutton2("'Sortuj wg daty sprzedaży'","button","button","p_towary_raport_sprzedaz.php?tumowa=$tumowa&umowa1=".urlencode($umowa1)."&umowa2=".urlencode($umowa2)."&umowa3=".urlencode($umowa3)."&umowa4=".urlencode($umowa4)."&umowa5=".urlencode($umowa5)."&umowa6=".urlencode($umowa6)."&umowa7=".urlencode($umowa7)."&umowa8=".urlencode($umowa8)."&umowa9=".urlencode($umowa9)."&umowa10=".urlencode($umowa10)."&okres_od=$okres_od&okres_do=$okres_do&umowailosc=$il_umow&ustalceny=0&submit=1");
		}
		echo "</span>";
		addownlinkbutton2("'Podgląd do XLS'","button","button","do_xls_htmlexcel.php?preview=1");
		addownlinkbutton("'Cofnij niezapisane zmiany'","odswiez","button","window.location.reload(true);");
		addownsubmitbutton("'Zapisz zmiany'","zapiszzmiany");
		echo "<input class=buttons type=submit name=zatwierdz style='color:green' value='Zatwierdź raport' onClick=\"return potwierdz();\">";
		
		nowalinia();
		addlinkbutton("'Towary na stanie'","p_towary_dostepne.php?view=normal");
		addlinkbutton("'Pokaż utworzone zestawy'","z_towary_zestawy.php");
		addlinkbutton("'Sprzedaż w okresie'","main.php?action=pswo");
		endbuttonsarea();
	}
	
	if ($zatwierdz=='koniec') {
		startbuttonsarea("right");	
		echo "<span style='float:left'>";	
			//addownlinkbutton("'Cofnij niezapisane zmiany'","odswiez","button","window.location.reload(true);");
			if ($pokazwybrane!='Pokaż tylko wybrane') {
				addownsubmitbutton("'Pokaż tylko wybrane'","pokazwybrane");
				addownsubmitbutton("'Zaznacz wszystkie'","pokazwybrane");
				addinvertbutton("'Odwróć zaznaczenie'");
			} else {
				addownsubmitbutton("'Pokaż wszystko'","pokazwybrane");
			}
		echo "</span>";
	
		$result777 = mysql_query("SELECT sr_id, sr_rok, sr_miesiac FROM $dbname.serwis_sprzedaz_raport WHERE (belongs_to=$es_filia) ORDER BY sr_id DESC LIMIT 1", $conn) or die($k_b);
		list($temp1_id,$temp1_rok, $temp1_miesiac)=mysql_fetch_array($result777);

		$r1 = Date('Y');
		$m1 = Date('m');
		
		if ($m1==1) { $m1=12; $r1--; } else $m1--;
		
		$rr44 = substr($okres_do,0,4);
		$mm44 = substr($okres_do,5,2);
			
		if (($temp1_id>0) && ($temp1_rok==$rr44) && ($temp1_miesiac==$mm44)) {

			echo "<input type=hidden name=u_miesiac value='$mm44'>";
			echo "<input type=hidden name=u_rok value='$rr44'>";
			echo "<input class=buttons type=submit name=usun_zatwierdzenie style='color:red' value='Usuń zatwierdzenie raportu' onClick=\"return usun_potwierdz();\">";
		}
		endbuttonsarea();
	}
	
	echo "<input type=hidden name=umowailosc value=$umowailosc>";
	
	for ($jj=1; $jj<=$umowailosc; $jj++) {
		$umowanr = $_POST['umowa'.$jj];
		echo "<input type=hidden name=umowa$jj value=$umowanr>";
	}
			
	echo "<input type=hidden name=ilosc value=$j>";
	echo "<input type=hidden name=okres_od value='$okres_od'>";
	echo "<input type=hidden name=okres_do value='$okres_do'>";	
	echo "<input type=hidden name=tumowa value='$tumowa'>";
	echo "<input type=hidden name=ustalceny value='$ustalceny'>";
	_form();

//	echo "<form action=do_xls_utf8.php METHOD=POST target=_blank>";

	echo "<form action=do_xls_htmlexcel_mr.php METHOD=POST target=_blank>";
	
	$rr = substr($okres_do,0,4);
	$mm = substr($okres_do,5,2);
	
	echo "<input type=hidden name=r_rok value='$rr'>";
	echo "<input type=hidden name=r_miesiac value='$mm'>";	
	echo "<input type=hidden name=poczatek value='$okres_od'>";
	echo "<input type=hidden name=koniec value='$okres_do'>";
	echo "<input type=hidden name=umowanr value='$tumowa'>";
	echo "<input type=hidden name=unique value=$unique>";
	echo "<input type=hidden name=filian value=$filian>";
	echo "<input type=hidden name=preview value=0>";

	startbuttonsarea("right");
	if ($zatwierdz=='koniec') {	addownsubmitbutton("'Generuj plik XLS (miesięczny protokół odbioru)'","refresh_"); }
	endbuttonsarea();
	_form();

	echo "<form action=do_xls_htmlexcel.php METHOD=POST target=_blank>";
	
	$rr = substr($okres_do,0,4);
	$mm = substr($okres_do,5,2);
	
	echo "<input type=hidden name=r_rok value='$rr'>";
	echo "<input type=hidden name=r_miesiac value='$mm'>";	
	echo "<input type=hidden name=poczatek value='$okres_od'>";
	echo "<input type=hidden name=koniec value='$okres_do'>";
	echo "<input type=hidden name=umowanr value='$tumowa'>";
	echo "<input type=hidden name=unique value=$unique>";
	echo "<input type=hidden name=filian value=$filian>";
	echo "<input type=hidden name=preview value=0>";

	startbuttonsarea("right");
	if ($zatwierdz=='koniec') {	addownsubmitbutton("'Generuj plik XLS'","refresh_"); }
	addownlinkbutton("'Zmień kryteria'","button","button","window.location.href='main.php?action=grzs'");
	addbuttons("start");
	endbuttonsarea();
	_form();
	
	include('body_stop.php');
	
	?>
	
	<script>HideWaitingMessage();</script>
	
	<?php 
	
} else
     {
		errorheader("Nie wypełniłeś wymaganych pól");
		startbuttonsarea("right");
		addownlinkbutton("'Zmień kryteria'","button","button","window.location.href='main.php?action=grzs'");	
		addbuttons("start");
		endbuttonsarea();
}

} else { ?>

<?php
$ok = 0;
$sql 	= "SELECT filia_leader FROM $dbname.serwis_filie WHERE filia_id=$es_filia";
$result = mysql_query($sql, $conn) or die($k_b);
while ($newArray = mysql_fetch_array($result)) {
	$temp_leader	= $newArray['filia_leader'];
	if ($temp_leader==$es_nr) $ok = 1;
}

if ($is_dyrektor==1) $ok = 1;

if ($ok==1) {
	// przygotowanie tabeli tymczasowej
	
	// tworzenie tabeli tycznasowej dla raportów
	$czyistnieje = mysql_query("SELECT * FROM $dbname.serwis_temp_raport$es_filia LIMIT 1", $conn);
	$sql_f = "SELECT filia_leader FROM $dbname.serwis_filie WHERE filia_leader=$es_nr LIMIT 1";
	$result_f = mysql_query($sql_f, $conn) or die($k_b);
	$countf = mysql_num_rows($result_f);
		
	if ($czyistnieje==true) {	
		if ($countf>0) { 
			$sql_report = "TRUNCATE TABLE $dbname.serwis_temp_raport$es_filia";
			$result_report = mysql_query($sql_report, $conn) or die($k_b);		
		}
	} else { 
		if ($countf>0) { 
			$sql_report = "CREATE TABLE $dbname.serwis_temp_raport$es_filia (`pole2` varchar(100) collate utf8_polish_ci NOT NULL,`pole3` varchar(100) collate utf8_polish_ci NOT NULL,`pole4` varchar(100) collate utf8_polish_ci NOT NULL,`pole5` varchar(100) collate utf8_polish_ci NOT NULL,`pole6` varchar(100) collate utf8_polish_ci NOT NULL,`pole7` varchar(100) collate utf8_polish_ci NOT NULL,`pole8` varchar(100) collate utf8_polish_ci NOT NULL,`pole9` varchar(100) collate utf8_polish_ci NOT NULL,`pole10` varchar(100) collate utf8_polish_ci NOT NULL,`pole11` varchar(100) collate utf8_polish_ci NOT NULL,`pole12` varchar(100) collate utf8_polish_ci NOT NULL,`pole13` varchar(100) collate utf8_polish_ci NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci";
			$result_report = mysql_query($sql_report, $conn) or die($k_b);	
		}
	}

	// koniec przygotowywania tabeli tymczasowej

	br();
	pageheader("Generowanie raportu ze sprzedaży w okresie");
	echo "<form name=ruch action=p_towary_raport_sprzedaz.php method=POST>";

	echo "<table cellspacing=1 align=center style=width:400px>";
	tbl_empty_row(2);

	echo "<tr>";
	echo "<td class=center colspan=2>";
	echo "<b>Wybierz okres, którego raport ma dotyczyć<br /><br /></b>";
	
	$r1 = Date('Y');
	$m1 = Date('m');
	$d1 = '01';
	
	$r2 = Date('Y');
	$m2 = Date('m');
	
	if ($m1==1) { $m1=12; $r1--; } else $m1--;
	
	// wyliczenie liczby lat wstecz (wg najstarszej daty sprzedaży)
	$r40 = mysql_query("SELECT sprzedaz_data FROM $dbname.serwis_sprzedaz WHERE (belongs_to='$es_filia') ORDER BY sprzedaz_data ASC LIMIT 1", $conn) or die($k_b);	
	list($Rok_Min)=mysql_fetch_array($r40);
	$Rok_Curr = date('Y');	
	$Lat_Wstecz = $Rok_Curr - $Rok_Min;
	
	echo "<select name=r_o_k onkeypress='return handleEnter(this, event);' onChange=\"document.ruch.m_i_e_s_i_a_c.value=0\">";
	
	for ($r=$r1-$Lat_Wstecz; $r<=$r1+1; $r++) 
	{ 
		echo "<option value=$r "; if ($r1==$r) echo "SELECTED"; echo ">$r</option>\n";
	
	}
	echo "</select>";
	
	echo "<select name=m_i_e_s_i_a_c onkeypress='return handleEnter(this, event);' OnChange=\"
	if (this.value==1) { document.ruch.okres_od.value=document.ruch.r_o_k.value+'-0'+this.value+'-01';
						 document.ruch.okres_do.value=document.ruch.r_o_k.value+'-0'+this.value+'-31';
						}
	if (this.value==2) { document.ruch.okres_od.value=document.ruch.r_o_k.value+'-0'+this.value+'-01';
						 document.ruch.okres_do.value=document.ruch.r_o_k.value+'-0'+this.value+'-29';
						}
	if (this.value==3) { document.ruch.okres_od.value=document.ruch.r_o_k.value+'-0'+this.value+'-01';
						 document.ruch.okres_do.value=document.ruch.r_o_k.value+'-0'+this.value+'-31';
						}
	if (this.value==4) { document.ruch.okres_od.value=document.ruch.r_o_k.value+'-0'+this.value+'-01';
						 document.ruch.okres_do.value=document.ruch.r_o_k.value+'-0'+this.value+'-30';
						}
	if (this.value==5) { document.ruch.okres_od.value=document.ruch.r_o_k.value+'-0'+this.value+'-01';
						 document.ruch.okres_do.value=document.ruch.r_o_k.value+'-0'+this.value+'-31';
						}
	if (this.value==6) { document.ruch.okres_od.value=document.ruch.r_o_k.value+'-0'+this.value+'-01';
						 document.ruch.okres_do.value=document.ruch.r_o_k.value+'-0'+this.value+'-30';
						}
	if (this.value==7) { document.ruch.okres_od.value=document.ruch.r_o_k.value+'-0'+this.value+'-01';
						 document.ruch.okres_do.value=document.ruch.r_o_k.value+'-0'+this.value+'-31';
						}
	if (this.value==8) { document.ruch.okres_od.value=document.ruch.r_o_k.value+'-0'+this.value+'-01';
						 document.ruch.okres_do.value=document.ruch.r_o_k.value+'-0'+this.value+'-31';
						}
	if (this.value==9) { document.ruch.okres_od.value=document.ruch.r_o_k.value+'-0'+this.value+'-01';
						 document.ruch.okres_do.value=document.ruch.r_o_k.value+'-0'+this.value+'-30';
						}
	if (this.value==10) { document.ruch.okres_od.value=document.ruch.r_o_k.value+'-'+this.value+'-01';
						 document.ruch.okres_do.value=document.ruch.r_o_k.value+'-'+this.value+'-31';
						}
	if (this.value==11) { document.ruch.okres_od.value=document.ruch.r_o_k.value+'-'+this.value+'-01';
						 document.ruch.okres_do.value=document.ruch.r_o_k.value+'-'+this.value+'-30';
						}
	if (this.value==12) { document.ruch.okres_od.value=document.ruch.r_o_k.value+'-'+this.value+'-01';
						 document.ruch.okres_do.value=document.ruch.r_o_k.value+'-'+this.value+'-31';
						}
	\">";
	echo "<option value=0>Wybierz miesiąc</option>\n";
	echo "<option value=1 "; if ($m1==1) echo "SELECTED"; echo ">Styczeń</option>\n";
	echo "<option value=2 "; if ($m1==2) echo "SELECTED"; echo ">Luty</option>\n";
	echo "<option value=3 "; if ($m1==3) echo "SELECTED"; echo ">Marzec</option>\n";
	echo "<option value=4 "; if ($m1==4) echo "SELECTED"; echo ">Kwiecień</option>\n";
	echo "<option value=5 "; if ($m1==5) echo "SELECTED"; echo ">Maj</option>\n";
	echo "<option value=6 "; if ($m1==6) echo "SELECTED"; echo ">Czerwiec</option>\n";
	echo "<option value=7 "; if ($m1==7) echo "SELECTED"; echo ">Lipiec</option>\n";
	echo "<option value=8 "; if ($m1==8) echo "SELECTED"; echo ">Sierpień</option>\n";
	echo "<option value=9 "; if ($m1==9) echo "SELECTED"; echo ">Wrzesień</option>\n";
	echo "<option value=10 "; if ($m1==10) echo "SELECTED"; echo ">Październik</option>\n";
	echo "<option value=11 "; if ($m1==11) echo "SELECTED"; echo ">Listopad</option>\n";
	echo "<option value=12 "; if ($m1==12) echo "SELECTED"; echo ">Grudzień</option>\n";
	echo "</select>";
	
	echo "</td>";
	echo "</tr>";

	if ($m1==1) $d2=31;
	if ($m1==2) $d2=29;
	if ($m1==3) $d2=31;
	if ($m1==4) $d2=30;
	if ($m1==5) $d2=31;
	if ($m1==6) $d2=30;
	if ($m1==7) $d2=31;
	if ($m1==8) $d2=31;
	if ($m1==9) $d2=30;
	if ($m1==10) $d2=31;
	if ($m1==11) $d2=30;
	if ($m1==12) $d2=31;
	if (strlen($m1)==1) $m1='0'.$m1;
	
	$data1=$r1.'-'.$m1.'-'.$d1;
	$data2=$r1.'-'.$m1.'-'.$d2;
			
		echo "<tr>";
	
		echo "<td class=center colspan=2>";
		echo "<b><br />dla umowy<br /><br /></b>";
		echo "</td></tr>";
		echo "<tr>";	
		echo "<td class=center colspan=2>";
		
		$sql6="SELECT * FROM $dbname.serwis_umowy ";
		if ($es_m!=1) $sql6 = $sql6. " WHERE belongs_to=$es_filia";
		$result6 = mysql_query($sql6, $conn) or die($k_b);
		
		echo "<select class=wymagane name=tumowa onkeypress='return handleEnter(this, event);'>\n";
		echo "<option value='all'>Wszystkie z listy</option>\n"; 

		while ($newArray = mysql_fetch_array($result6)) 
		 {
			$temp_id  				= $newArray['umowa_id'];
			$temp_nr				= $newArray['umowa_nr'];			
			$temp_nazwa				= $newArray['umowa_opis'];
			echo "<option value='$temp_nr'>$temp_nazwa ($temp_nr)</option>\n"; 
		}
		echo "</select>\n"; 
				
// lista nr umów
		$sql6="SELECT * FROM $dbname.serwis_umowy ";
		if ($es_m!=1) $sql6 = $sql6. " WHERE belongs_to=$es_filia";
		$result6 = mysql_query($sql6, $conn) or die($k_b);
		$i = 0;

		while ($newArray = mysql_fetch_array($result6)) 
		 {
			$temp_id  				= $newArray['umowa_id'];
			$temp_nr				= $newArray['umowa_nr'];			
			$temp_nazwa				= $newArray['umowa_opis'];
			$i++;
			echo "<input type=hidden name=umowa$i value='$temp_nr'>";
		}
		
	echo "<input type=hidden name=umowailosc value=$i>";
	echo "<input type=hidden name=okres_od value=$data1>";			
	echo "<input type=hidden name=okres_do value=$data2>";
	echo "<input type=hidden name=pokazwybrane value='selectall'>";
	echo "<input type=hidden name=ustalceny value='1'>";
	
	echo "</td>";
	echo "</tr>";
	tbl_empty_row(2);
	endtable();

	startbuttonsarea("center");
	addownsubmitbutton("'Generuj raport'","submit");
	endbuttonsarea();
	
	_form();	
	
?>
<script language="JavaScript">
var cal1 = new calendar1(document.forms['ruch'].elements['okres_od']);
	cal1.year_scroll = true;
	cal1.time_comp = false;
var cal11 = new calendar1(document.forms['ruch'].elements['okres_do']);
	cal11.year_scroll = true;
	cal11.time_comp = false;
</script>

<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("ruch");
  
  frmvalidator.addValidation("okres_od","req","Nie podano daty poczatkowej");
  frmvalidator.addValidation("okres_do","req","Nie podano daty końcowej");
  frmvalidator.addValidation("okres_od","numerichyphen","Użyłeś niedozwolonych znaków w polu \"od dnia\"");
  frmvalidator.addValidation("okres_do","numerichyphen","Użyłeś niedozwolonych znaków w polu \"do dnia\"");

  frmvalidator.addValidation("m_i_e_s_i_a_c","dontselect=0","Nie wybrałeś miesiąca");
  
  frmvalidator.addValidation("tumowa","dontselect","Nie wybrano umowy");  
</script>

<?php 
} else { br(); errorheader("Funkcja dostępna tylko dla kierowników zespołów"); }
}
?>
</body>
</html>