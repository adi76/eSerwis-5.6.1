<?php include_once('header.php'); ?>
<?php 
//include('conv_pl_to_latin1.php');
include('inc_encrypt.php');
if ($skrot==1) { $_POST[gdzie]=$gdzie; $search=$szukaj;	$skrot=0; }
if ($submit) {
	switch ($_POST[gdzie]) {
		case "M" : 	if ($es_m==1) {
						$sql = "SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni,magazyn_uwagi_sa,magazyn_uwagi,magazyn_status, magazyn_osobawprowadzajaca,magazyn_datawprowadzenia,magazyn_osoba_rezerwujaca,magazyn_data_rezerwacji,upper(magazyn_id),upper(magazyn_nazwa),upper(magazyn_model),upper(magazyn_sn),upper(magazyn_ni),upper(magazyn_uwagi_sa),upper(magazyn_uwagi),upper(magazyn_status),upper(magazyn_osobawprowadzajaca),upper(magazyn_datawprowadzenia),upper(magazyn_osoba_rezerwujaca),upper(magazyn_data_rezerwacji) FROM $dbname.serwis_magazyn WHERE (magazyn_status<>'3') AND  ((magazyn_nazwa LIKE '%".strtoupper($search)."%') OR (magazyn_model LIKE '%".strtoupper($search)."%') OR (magazyn_sn LIKE '%".strtoupper($search)."%') OR (magazyn_ni LIKE '%".strtoupper($search)."%'))";
					} else {
						$sql = "SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni,magazyn_uwagi_sa,magazyn_uwagi,magazyn_status, magazyn_osobawprowadzajaca,magazyn_datawprowadzenia,magazyn_osoba_rezerwujaca,magazyn_data_rezerwacji,upper(magazyn_id),upper(magazyn_nazwa),upper(magazyn_model),upper(magazyn_sn),upper(magazyn_ni),upper(magazyn_uwagi_sa),upper(magazyn_uwagi),upper(magazyn_status),upper(magazyn_osobawprowadzajaca),upper(magazyn_datawprowadzenia),upper(magazyn_osoba_rezerwujaca),upper(magazyn_data_rezerwacji), belongs_to FROM $dbname.serwis_magazyn WHERE (magazyn_status<>'3') and (belongs_to=$es_filia) AND ((magazyn_nazwa LIKE '%".strtoupper($search)."%') OR (magazyn_model LIKE '%".strtoupper($search)."%') OR (magazyn_sn LIKE '%".strtoupper($search)."%') OR (magazyn_ni LIKE '%".strtoupper($search)."%'))";
					}
					break;
		case "N" : 	if ($es_m==1) { 
					$sql = "SELECT upper(naprawa_nazwa),upper(naprawa_model), upper(naprawa_sn), upper(naprawa_ni), upper(naprawa_pobrano_z), upper(naprawa_sn_nazwa),upper(naprawa_fs_nazwa), upper(naprawa_nr_listu_przewozowego), upper(naprawa_fk_nazwa), upper(naprawa_osoba_pobierajaca), naprawa_nazwa, naprawa_model,naprawa_sn,naprawa_ni,naprawa_pobrano_z,naprawa_osoba_pobierajaca, naprawa_data_pobrania,naprawa_status,naprawa_uwagi,naprawa_id,naprawa_data_oddania_sprzetu,naprawa_uwagi_sa,belongs_to,naprawa_hd_zgl_id FROM $dbname.serwis_naprawa WHERE ((naprawa_nazwa LIKE '%".strtoupper($search)."%') OR (naprawa_model LIKE '%".strtoupper($search)."%') OR (naprawa_sn LIKE '%".strtoupper($search)."%') OR (naprawa_ni LIKE '%".strtoupper($search)."%') OR (naprawa_pobrano_z LIKE '%".strtoupper($search)."%') OR (naprawa_sn_nazwa LIKE '%".strtoupper($search)."%') OR (naprawa_fs_nazwa LIKE '%".strtoupper($search)."%') OR (naprawa_nr_listu_przewozowego LIKE '%".strtoupper($search)."%') OR (naprawa_fk_nazwa LIKE '%".strtoupper($search)."%') OR (naprawa_osoba_pobierajaca LIKE '%".strtoupper($search)."%')) ORDER BY naprawa_data_pobrania DESC";
					} else {
					$sql = "SELECT upper(naprawa_nazwa),upper(naprawa_model), upper(naprawa_sn), upper(naprawa_ni), upper(naprawa_pobrano_z), upper(naprawa_sn_nazwa),upper(naprawa_fs_nazwa), upper(naprawa_nr_listu_przewozowego), upper(naprawa_fk_nazwa), upper(naprawa_osoba_pobierajaca), naprawa_nazwa, naprawa_model,naprawa_sn,naprawa_ni,naprawa_pobrano_z,naprawa_osoba_pobierajaca, naprawa_data_pobrania,naprawa_status,naprawa_uwagi,naprawa_id,naprawa_data_oddania_sprzetu,naprawa_uwagi_sa,belongs_to,naprawa_hd_zgl_id FROM $dbname.serwis_naprawa WHERE (belongs_to=$es_filia) and ((naprawa_nazwa LIKE '%".strtoupper($search)."%') OR (naprawa_model LIKE '%".strtoupper($search)."%') OR (naprawa_sn LIKE '%".strtoupper($search)."%') OR (naprawa_ni LIKE '%".strtoupper($search)."%') OR (naprawa_pobrano_z LIKE '%".strtoupper($search)."%') OR (naprawa_sn_nazwa LIKE '%".strtoupper($search)."%') OR (naprawa_fs_nazwa LIKE '%".strtoupper($search)."%') OR (naprawa_nr_listu_przewozowego LIKE '%".strtoupper($search)."%') OR (naprawa_fk_nazwa LIKE '%".strtoupper($search)."%') OR (naprawa_osoba_pobierajaca LIKE '%".strtoupper($search)."%')) ORDER BY naprawa_data_pobrania DESC";
					}
					break;
		case "S" : 	$sql="SELECT  upper(pozycja_nr_faktury),upper(pozycja_numer),upper(pozycja_nazwa),upper(pozycja_sn),upper(pozycja_status),upper(pozycja_typ),pozycja_uwagi,pozycja_id,pozycja_nr_faktury,pozycja_numer,pozycja_nazwa,pozycja_ilosc,pozycja_sn,pozycja_cena_netto,pozycja_status,belongs_to,pozycja_datasprzedazy,pozycja_dolicz_koszty,pozycja_cena_netto_odsprzedazy,pozycja_typ, pozycja_uwagi FROM $dbname.serwis_faktura_szcz WHERE (belongs_to=$es_filia) and (pozycja_status='0') AND ((pozycja_sn LIKE '%".strtoupper($search)."%') OR (pozycja_nazwa LIKE '%".strtoupper($search)."%') OR (pozycja_nr_faktury LIKE '%".strtoupper($search)."%'))";
					break;
		case "P" : 	$sql="SELECT * FROM $dbname.serwis_faktura_szcz WHERE ((belongs_to=$es_filia) AND ((pozycja_sn LIKE '%$search%') OR (pozycja_nazwa LIKE '%$search%') OR (pozycja_nr_faktury LIKE '%$search%')))";
					break;
		case "F" : 	$sql="SELECT upper(faktura_numer),upper(faktura_dostawca),upper(faktura_nr_zamowienia),faktura_id,faktura_numer,faktura_data,faktura_dostawca,faktura_koszty_dodatkowe,faktura_osoba,faktura_datawpisu,faktura_status,faktura_nr_zamowienia,faktura_realizacjazakupu,faktura_uwagi FROM $dbname.serwis_faktury WHERE (belongs_to=$es_filia) AND ((faktura_numer LIKE '%".strtoupper($search)."%') OR (faktura_data LIKE '%$search%') OR (faktura_dostawca LIKE '%".strtoupper($search)."%') OR (faktura_nr_zamowienia LIKE '%".strtoupper($search)."%'))";
					break;
		case "E" : 	$sql = "SELECT * FROM $dbname.serwis_ewidencja WHERE (belongs_to=$es_filia) and (ewidencja_status>0) and ((ewidencja_typ_nazwa LIKE '%".strtoupper($search)."%') OR (ewidencja_up_nazwa LIKE '%".strtoupper($search)."%') OR (ewidencja_uzytkownik LIKE '%".strtoupper($search)."%') OR (ewidencja_zestaw_ni LIKE '%".strtoupper($search)."%') OR (ewidencja_komputer_nazwa LIKE '%".strtoupper($search)."%') OR (ewidencja_komputer_opis LIKE '%".strtoupper($search)."%') OR (ewidencja_komputer_sn LIKE '%".strtoupper($search)."%') OR (ewidencja_komputer_ip LIKE '%".strtoupper($search)."%') OR (ewidencja_komputer_endpoint LIKE '%".strtoupper($search)."%') OR (ewidencja_monitor_opis LIKE '%".strtoupper($search)."%') OR (ewidencja_monitor_sn LIKE '%".strtoupper($search)."%') OR (ewidencja_drukarka_opis LIKE '%".strtoupper($search)."%') OR (ewidencja_drukarka_sn LIKE '%".strtoupper($search)."%') OR (ewidencja_drukarka_ni LIKE '%".strtoupper($search)."%'))";
					break;
		case "A" : 	$sql = "SELECT * FROM $dbname.serwis_awarie WHERE (belongs_to=$es_filia) and ((awaria_gdzie LIKE '%$search%') OR (awaria_nrwanportu LIKE '%$search%') OR (awaria_nrzgloszenia LIKE '%$search%') OR (awaria_osobarejestrujaca LIKE '%$search%') OR (awaria_osobazamykajaca LIKE '%$search%')) ORDER BY awaria_datazgloszenia DESC";
					break;
		case "B" : 	$sql = "SELECT * FROM $dbname.serwis_kb_pytania WHERE (kb_pytanie_temat LIKE '%$search%') OR (kb_pytanie_tresc LIKE '%$search%')";
					break;
		case "BO" : $sql = "SELECT * FROM $dbname.serwis_kb_odpowiedzi WHERE (kb_odpowiedz_tresc LIKE '%$search%')";
					break;
	}
	$result = mysql_query($sql, $conn) or die($k_b);
	if (mysql_num_rows($result)!=0) {
		include('body_start.php');
		if ($_REQUEST[from]!='BW') nowalinia();
		pageheader("Wyniki wyszukiwania ciągu znaków",1,0);
		infoheader("<b>".$search."</b>");
		switch ($_POST[gdzie]) {
			case "M" : include_once("s_magazyn.php");			break;
			case "N" : include_once("s_naprawy.php");			break;
			case "S" : include_once("s_sprzedaz.php");			break;
			case "P" : include_once("s_pozycjenafakturze.php");	break; 
			case "E" : include_once("s_ewidencja.php");			break;
			case "A" : include_once("s_awarie.php");			break;
			case "F" : include_once("s_naglowki_faktur.php");	break;
			case "B" : include_once("s_bazawiedzy.php");		break;		
		}
		startbuttonsarea("right");
		echo "<span style='float:left;'>";
		echo "<input id=back class=buttons type=button onClick=history.go(-1); value='Wróć do bazy wiedzy'>";
		//addbuttons("wstecz");
		echo "</span>";
		
		if ($_REQUEST[from]!='BW') addlinkbutton("'Nowe szukanie'","main.php?action=szukaj&typs=$typs");
		addlinkbutton("'Przeglądaj zgłoszenia'","hd_p_zgloszenia.php?showall=0&p5=BZ&sort=AD&page=1");
		addbuttons("start");
		endbuttonsarea();
		include('body_stop.php');
	} else {
		if ($_REQUEST[from]!='BW') nowalinia();
		errorheader("Nie znaleziono pozycji spełniających podane przez Ciebie kryteria");
		startbuttonsarea("right");
		echo "<span style='float:left;'>";
		echo "<input id=back class=buttons type=button onClick=history.go(-1); value='Wróć do bazy wiedzy'>";
		//addbuttons("wstecz");
		echo "</span>";

		if ($_REQUEST[from]!='BW') addlinkbutton("'Nowe szukanie'","main.php?action=szukaj&typs=$typs");
		addlinkbutton("'Przeglądaj zgłoszenia'","hd_p_zgloszenia.php?showall=0&p5=BZ&sort=AD&page=1");
		addbuttons("start");
		endbuttonsarea();
	}

} else {
br();
pageheader("Szukaj");
starttable("300px");
echo "<form name=szukajwbazie action=main.php?action=szukaj&typs=$typs method=POST>";
tr_();
	th_colspan(2,'c');
		echo "<img class=imgoption src=img/search.gif align=absbottom border=0  width=16 width=16>&nbsp;<b>Znajdź ciąg znaków</b>";
	_th();
_tr();
tbl_empty_row();
tr_();
	td_colspan(2,'c');
		echo "<input id=sz class=wymagane size=30 type=text name=search onKeyUp=\"key=window.event.keyCode; if (key==13) { document.getElementById('submit111').focus();  }\" />";
	_td();
_tr();
tbl_empty_row();
tr_();
	td_colspan(2,'c');
	echo "szukaj w:<br /><br />";
	if ($typs!='all') {
		if ($typs=='M') echo "<input type=hidden name=gdzie value='M'><b>Magazynie</b>"; 
		if ($typs=='N') echo "<input type=hidden name=gdzie value='N'><b>Naprawach</b>"; 
		if ($typs=='S') echo "<input type=hidden name=gdzie value='S'><b>Towarach do odsprzedaży</b>";
		if ($typs=='F') echo "<input type=hidden name=gdzie value='F'><b>Nagłówkach faktur</b>"; 
		if ($typs=='P') echo "<input type=hidden name=gdzie value='P'><b>Pozycjach na fakturach</b>"; 
		if ($typs=='E') echo "<input type=hidden name=gdzie value='E'><b>Ewidencji sprzętu</b>"; 
		if ($typs=='A') echo "<input type=hidden name=gdzie value='A'><b>Historii awarii WAN'u</b>"; 
		if ($typs=='B') echo "<input type=hidden name=gdzie value='B'><b>Bazie Wiedzy</b>"; 
	} else {
		echo "<select name=gdzie onkeypress='return handleEnter(this, event);'>\n"; 
		if (($typs=='M') || ($typs=='all')) { echo "<option value='M'>Magazynie</option>\n"; }
		if (($typs=='N') || ($typs=='all')) { echo "<option value='N'>Naprawach</option>\n"; }
		if (($typs=='S') || ($typs=='all')) { echo "<option value='S'>Towarach do odsprzedaży</option>\n"; }
		if (($typs=='F') || ($typs=='all')) { echo "<option value='F'>Nagłówkach faktur</option>\n"; }
		if (($typs=='P') || ($typs=='all')) { echo "<option value='P'>Pozycjach na fakturach</option>\n"; }
		if (($typs=='E') || ($typs=='all')) { echo "<option value='E'>Ewidencji sprzętu</option>\n"; }
		if (($typs=='A') || ($typs=='all')) { echo "<option value='A'>Historii awarii WAN'u</option>\n"; }
		if (($typs=='B') || ($typs=='all')) { echo "<option value='B'>Bazie Wiedzy</option>\n"; }
		echo "</select>\n";	
	}
_tr();	
tbl_empty_row();

endtable();
startbuttonsarea("center");
echo "<input class=buttons type=submit id=submit111 name=submit value='Szukaj' onFocus=\"return false;\">";
echo "<input type=hidden name=typs value='$typs'>";
//addownsubmitbutton("'Szukaj'","submit");
endbuttonsarea();
_form();	
?>

<script language="JavaScript" type="text/javascript">
	var frmvalidator  = new Validator("szukajwbazie");
	frmvalidator.addValidation("search","req","Nie wpisano żadnego znaku");
</script>
<?php 
} 
//include('body_stop.php'); 
?>