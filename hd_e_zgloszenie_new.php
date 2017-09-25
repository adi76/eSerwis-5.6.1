<?php

include_once('header.php');
include_once('cfg_helpdesk.php');
//require_once('phpMailer/class.phpmailer.php');
//require_once('cfg_mails.php');

echo "<body>";

function toUpper($string) {
	$string = strtr($string,'qwertyuiopasdfghjklzxcvbnm','QWERTYUIOPASDFGHJKLZXCVBNM');
	return (strtr($string, 'ęóąśłżźćńĘÓĄŚŁŻŹĆŃ','ĘÓĄŚŁŻŹĆŃĘÓĄŚŁŻŹĆŃ' )); 
}; 

function ClearOutputText($s) {
	$s = str_replace("\\\\", "\\", $s);
	$s = str_replace("\\\"", "`", $s);
	$s = str_replace("\\'", "`", $s);
	$s = str_replace("\\", "/", $s);
	//$s = str_replace("\\'", "'", $s);
	//$s = str_replace("\\'", "'", $s);
	return $s;
}

if ($submit) {

?><script>ShowWaitingMessage('save');</script><?php ob_flush(); flush();

	$_POST=sanitize($_POST);
	$dddd = date("Y-m-d H:i:s");
	// wyłapanie co zostało zmienione

	$lista_zmian = '';
	$sql = '';
/*
	if ($_POST[old_hdgz]!=$_POST[hdgz]) {
		$lista_zmian.='<u>Zmiana godziny rejestracji zgłoszenia z:</u> <b>'.$_POST[old_hdgz].'</b> -> <b>'.$_POST[hdgz].'</b><br />';
		$sql= "UPDATE $dbname_hd.hd_zgloszenie SET zgl_godzina='$_POST[hdgz]' WHERE (zgl_id=$_POST[zglid]) LIMIT 1";
		$wynik = mysql_query($sql, $conn_hd);
	}
*/	
	if ($_POST[old_hdnzhadim]!=$_POST[hdnzhadim]) {
		$lista_zmian.='<u>Zmiana nr zgłoszenia z Poczty:</u> <b>'.$_POST[old_hdnzhadim].'</b> -> <b>'.$_POST[hdnzhadim].'</b><br />';
		$sql= "UPDATE $dbname_hd.hd_zgloszenie SET zgl_poczta_nr='$_POST[hdnzhadim]' WHERE (zgl_id=$_POST[zglid]) LIMIT 1";
		$wynik = mysql_query($sql, $conn_hd);
	}
	
	if ($_POST[old_up_list]!=$_POST[up_list]) {
		$lista_zmian.='<u>Zmiana komórki:</u> <b>'.$_POST[old_up_list].'</b> -> <b>'.$_POST[up_list].'</b><br />';
		$sql= "UPDATE $dbname_hd.hd_zgloszenie SET zgl_komorka='$_POST[up_list]' WHERE (zgl_id=$_POST[zglid]) LIMIT 1";
		$wynik = mysql_query($sql, $conn_hd);
	}
	
	if ($_POST[old_hd_oz]!=$_POST[hd_oz]) {
		$lista_zmian.='<u>Zmiana osoby zgłaszającej:</u> <b>'.$_POST[old_hd_oz].'</b> -> <b>'.$_POST[hd_oz].'</b><br />';
		$sql= "UPDATE $dbname_hd.hd_zgloszenie SET zgl_osoba='$_POST[hd_oz]' WHERE (zgl_id=$_POST[zglid]) LIMIT 1";
		$wynik = mysql_query($sql, $conn_hd);
	}
	
	if ($_POST[old_hdoztelefon]!=$_POST[hdoztelefon]) {
		$lista_zmian.='<u>Zmiana telefonu osoby zgłaszającej:</u> <b>'.$_POST[old_hdoztelefon].'</b> -> <b>'.$_POST[hdoztelefon].'</b><br />';
		$sql= "UPDATE $dbname_hd.hd_zgloszenie SET zgl_telefon='$_POST[hdoztelefon]' WHERE (zgl_id=$_POST[zglid]) LIMIT 1";
		$wynik = mysql_query($sql, $conn_hd);
	}
			
	if ($_POST[old_hd_temat]!=$_POST[hd_temat]) {
		$lista_zmian.='<u>Zmiana tematu zgłoszenia:</u> <b>'.$_POST[old_hd_temat].'</b> -> <b>'.$_POST[hd_temat].'</b><br />';
		//$sql.= "UPDATE $dbname_hd.hd_zgloszenie SET (zgl_temat='".$_POST[hd_temat]."') WHERE (zgl_id=$_POST[zglid]) LIMIT 1;";
	}

	if ($_POST[old_zasadne]!=$_POST[zasadne]) {
		if ($_POST[zasadne]!='') {
			$lista_zmian.='<u>Zmiana zasadności zgłoszenia:</u> <b>'.$_POST[old_zasadne].'</b> -> <b>'.$_POST[zasadne].'</b><br />';
			$zz1 = 0;
			if ($_POST[zasadne]=='TAK') $zz1 = 1;
			$sql= "UPDATE $dbname_hd.hd_zgloszenie SET zgl_zasadne='$zz1' WHERE (zgl_id=$_POST[zglid]) LIMIT 1";
			$wynik = mysql_query($sql, $conn_hd);
		}
	}

	if ($_POST[spr_pomin]=='NIE') {
		$new_spr='NIE';
		if ($_POST[spr]=='TAK') $new_spr='TAK';

		if ($_POST[spr]!=$_POST[old_spr]) {
			$dddd = Date('Y-m-d H:i:s');
			if ($_POST[spr]=='TAK') {
				$lista_zmian.='<u>Potwierdzenie sprawdzenia zgłoszenia przez:</u> <b>'.$currentuser.', '.substr($dddd,0,16).'</b><br />';
				$sql= "UPDATE $dbname_hd.hd_zgloszenie SET zgl_sprawdzone_data='$dddd', zgl_sprawdzone_osoba='$currentuser' WHERE (zgl_id=$_POST[zglid]) LIMIT 1";
			} else {
				$lista_zmian.='<u>Anulowanie potwierdzenia sprawdzenia zgłoszenia przez:</u> <b>'.$currentuser.', '.substr($dddd,0,16).'</b><br />';
				$sql= "UPDATE $dbname_hd.hd_zgloszenie SET zgl_sprawdzone_data='', zgl_sprawdzone_osoba='' WHERE (zgl_id=$_POST[zglid]) LIMIT 1";	
			}
			$wynik = mysql_query($sql, $conn_hd);
		}
	}
	
	if ($_POST[old_czy_synchronizowac]!=$_POST[czy_synchronizowac]) {
		if ($_POST[old_czy_synchronizowac]=='on') { $old_synch_opis = 'TAK'; } else { $old_synch_opis = 'NIE'; }
		if ($_POST[czy_synchronizowac]=='on') { $synch_opis = 'TAK'; $synch_value = 1; } else { $synch_opis = 'NIE'; $synch_value = 0; }
		
		$lista_zmian.='<u>Zmiana widoczości zgłoszenia dla poczty:</u> <b>'.$old_synch_opis.'</b> -> <b>'.$synch_opis.'</b><br />';
		$sql= "UPDATE $dbname_hd.hd_zgloszenie SET zgl_czy_synchronizowac=".$synch_value." WHERE (zgl_id=$_POST[zglid]) LIMIT 1";
		
		$wynik = mysql_query($sql, $conn_hd);
	}
	//print_r($_REQUEST);
	if ($_POST[edit_awaria]=='1') {
		$r1 = mysql_query("SELECT hd_kategoria_opis FROM $dbname_hd.hd_kategoria WHERE (hd_kategoria_nr='$_POST[old_hd_kategoria]') LIMIT 1", $conn_hd) or die($k_b);
		list($old_kat_opis)=mysql_fetch_array($r1);

		$r1 = mysql_query("SELECT hd_podkategoria_opis FROM $dbname_hd.hd_podkategoria WHERE (hd_podkategoria_nr='$_POST[old_hd_podkategoria]') LIMIT 1", $conn_hd) or die($k_b);
		list($old_podkat_opis)=mysql_fetch_array($r1);
			
		$r1 = mysql_query("SELECT hd_kategoria_opis FROM $dbname_hd.hd_kategoria WHERE (hd_kategoria_nr='$_POST[kat_id]') LIMIT 1", $conn_hd) or die($k_b);
		list($kat_opis)=mysql_fetch_array($r1);

		$r1 = mysql_query("SELECT hd_podkategoria_opis FROM $dbname_hd.hd_podkategoria WHERE (hd_podkategoria_nr='$_POST[podkat_id]') LIMIT 1", $conn_hd) or die($k_b);
		list($podkat_opis)=mysql_fetch_array($r1);
			
		if ($_POST[old_hd_kategoria]!=$_POST[kat_id]) {
			$lista_zmian.='<u>Zmiana kategorii zgłoszenia:</u> <b>'.$old_kat_opis.'</b> -> <b>'.$kat_opis.'</b><br />';
			$sql= "UPDATE $dbname_hd.hd_zgloszenie SET zgl_kategoria='$_POST[kat_id]' WHERE (zgl_id=$_POST[zglid]) LIMIT 1";
			$wynik = mysql_query($sql, $conn_hd);
			
			if (($_POST[old_hd_kategoria]=='2') && ($_POST[kat_id]=='3')) {
				// jeżeli przejście z Awarii => Prace wg umowy => usuń wyliczone czasy
				$sql= "UPDATE $dbname_hd.hd_zgloszenie SET zgl_data_rozpoczecia='', zgl_data_zakonczenia='' WHERE (zgl_id=$_POST[zglid]) LIMIT 1";
				$wynik = mysql_query($sql, $conn_hd);
				$lista_zmian.='<br /><u><font color=red>Usunięto wyliczony automatycznie:</font></u><br />&nbsp;&nbsp;czas rozpoczęcia zgłoszenia: <b>'.$_POST[old_hd_dr].'</b><br />&nbsp;&nbsp;czas zakończenia zgłoszenia: <b>'.$_POST[old_hd_dz].'</b><br /><br />';
			}
		}
		
		if ($_POST[old_hd_podkategoria]!=$_POST[podkat_id]) {
			$lista_zmian.='<u>Zmiana podkategorii zgłoszenia:</u> <b>'.$old_podkat_opis.'</b> -> <b>'.$podkat_opis.'</b><br />';
			$sql= "UPDATE $dbname_hd.hd_zgloszenie SET zgl_podkategoria='$_POST[podkat_id]' WHERE (zgl_id=$_POST[zglid]) LIMIT 1";
			$wynik = mysql_query($sql, $conn_hd);
		}

		if ($_POST[old_hd_podkategoria2]!=$_POST[sub_podkat_id]) {
			$lista_zmian.='<u>Zmiana podkategorii (poziom 2) zgłoszenia:</u> <b>'.$_POST[old_hd_podkategoria2].'</b> -> <b>'.$_POST[sub_podkat_id].'</b><br />';
			$sql= "UPDATE $dbname_hd.hd_zgloszenie SET zgl_podkategoria_poziom_2='$_POST[sub_podkat_id]' WHERE (zgl_id=$_POST[zglid]) LIMIT 1";
			$wynik = mysql_query($sql, $conn_hd);
		}
		
	}

	if ($_POST[old_hd_tresc]!=$_POST[hd_tresc]) {
		$lista_zmian.='<u>Zmiana treści zgłoszenia:</u> <b>"'.nl2br($_POST[old_hd_tresc]).'"</b> -> <b>"'.nl2br($_POST[hd_tresc]).'"</b><br />';
		$sql= "UPDATE $dbname_hd.hd_zgloszenie SET zgl_tresc='".$_POST[hd_tresc]."', zgl_temat='".$_POST[hd_temat]."' WHERE (zgl_id=$_POST[zglid]) LIMIT 1";
		
		$wynik = mysql_query($sql, $conn_hd);
	}
	
	// jeżeli ma być nadpisany pierwszy krok zgłoszenia treścią zgłoszenia
	if ($_REQUEST[nadpisz_krok1]=='on') {
		$nowa_tresc = 'rejestracja zgłoszenia\n\r\n\r'.$_POST[hd_tresc];
	
		$sql_update = "UPDATE $dbname_hd.hd_zgloszenie_szcz SET zgl_szcz_wykonane_czynnosci='$nowa_tresc' WHERE (zgl_szcz_zgl_id=$_REQUEST[nr]) and (zgl_szcz_nr_kroku=1) and (belongs_to=$es_filia) LIMIT 1";
				
		$wynik = mysql_query($sql_update, $conn_hd);
	}
	
//	echo "$lista_zmian";
	
	if ($lista_zmian!='') {
	//	echo "$sql";
	//	nowalinia();
	
	
	//	if (mysql_query($sql, $conn_hd)) { 
			
			// insert do tabeli ze zmianami
			$sql_insert = "INSERT INTO $dbname_hd.hd_zgloszenie_historia_zmian values ('', '$_POST[zglid]','$dddd','$currentuser','$lista_zmian',1,'',$es_filia)";
			//echo "$sql_insert";
			$wynik = mysql_query($sql_insert, $conn_hd);
	
	//	} else {
		//		
		//}		
	
		?><script>if (opener) opener.location.reload(true);	self.close(); </script><?php
	
	} else {
		//okheader("Nie wprowadzono żadnych zmian w zgłoszeniu");
		if ($_REQUEST[nadpisz_krok1]=='on') {
			?>
			<script>
			alert('Nie wprowadzono żadnych zmian w zgłoszeniu. Uaktualniono wykonane czynności w pierwszym kroku zgłoszenia'); 
			self.close();
			</script>
			<?php 		
		} else {
			?>
			<script>
			alert('Nie wprowadzono żadnych zmian w zgłoszeniu'); 
			self.close();
			</script>
			<?php 
		}
	}
	
} else {

echo "<h4 style='padding:10px; font-weight:normal;'>";

	echo "<a id=PokazSzczZgl class=normalfont title=' Pokaż szczegóły zgłoszenia ' href=# onClick=\"$('#InformacjeOZgloszeniu').toggle(); $('#PokazSzczZgl').hide(); $('#UkryjSzczZgl').show(); \" >";
	
	echo "Edycja zgłoszenia nr <b>$_REQUEST[nr]</b>";
	
	if ($czy_wyroznic_zgloszenia_seryjne==1) {
		if ($_GET[zgl_s]=='1') echo " [zgłoszenie seryjne]";
	}	
	echo "&nbsp;<input type=image class=imgoption src=img/show_more_".$_REQUEST[tk].".gif>";
	echo "</a>";

	echo "<a id=UkryjSzczZgl style='display:none' class=normalfont title=' Ukryj szczegóły zgłoszenia ' href=# onClick=\"$('#InformacjeOZgloszeniu').toggle(); $('#PokazSzczZgl').show(); $('#UkryjSzczZgl').hide();\" >";	
	//echo "Zmiana statusu zgłoszenia nr $_GET[nr]";
	echo "Edycja zgłoszenia nr <b>$_REQUEST[nr]</b>";
	if ($czy_wyroznic_zgloszenia_seryjne==1) {
		if ($_GET[zgl_s]=='1') echo " [zgłoszenie seryjne]";
	}	
	echo "&nbsp;<input type=image class=imgoption src=img/hide_more_".$_REQUEST[tk].".gif>";
	echo "</a>";
echo "</h4>";

echo "<div id=InformacjeOZgloszeniu style='display:none'>";
$wynik = mysql_query("SELECT * FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id=$_REQUEST[id]) LIMIT 1", $conn_hd);
	
	while ($dane_f1=mysql_fetch_array($wynik)) {
		$temp2_zgl_nr		= $dane_f1['zgl_nr'];
		$temp_data			= $dane_f1['zgl_data'];
		$temp_godzina		= $dane_f1['zgl_godzina'];
		$temp_komorka		= $dane_f1['zgl_komorka'];
		$temp_osoba			= $dane_f1['zgl_osoba'];
		$temp_telefon		= $dane_f1['zgl_telefon'];
		$temp_poczta_nr		= $dane_f1['zgl_poczta_nr'];
		$temp_temat			= $dane_f1['zgl_temat'];
		$temp_tresc			= $dane_f1['zgl_tresc'];
		$temp_kategoria		= $dane_f1['zgl_kategoria'];
		$temp_podkategoria	= $dane_f1['zgl_podkategoria'];
		$temp_podkategoria2	= $dane_f1['zgl_podkategoria_poziom_2'];		
		$temp_osoba_przypisana	= $dane_f1['zgl_osoba_przypisana'];
		$temp_status		= $dane_f1['zgl_status'];	
		$temp_parent_zgl	= $dane_f1['zgl_kontynuacja_zgloszenia_numer'];
		$temp_naprawa_id	= $dane_f1['zgl_naprawa_id'];
		$temp_czy_synchronizowac = $dane_f1['zgl_czy_synchronizowac'];
		$temp_czy_zasadne	= $dane_f1['zgl_zasadne'];
		
		$temp_spr_data		= $dane_f1['zgl_sprawdzone_data'];
		$temp_spr_osoba		= $dane_f1['zgl_sprawdzone_osoba'];
	}

switch ($temp_kategoria) {
	case 2:	$kolorgrupy='#FF7F2A'; break; 
	case 6: $kolorgrupy='#F73B3B'; break;
	case 3:	$kolorgrupy=''; break; 			
	default: if ($temp_status==9) { $kolorgrupy='#FFFFFF'; } else { $kolorgrupy=''; } break; 
}	
//echo "<table style='background-color:transparent; border:0px solid'>";
starttable();
tbl_tr_color(0,$kolorgrupy);
echo "<td><b>Data zgłoszenia</b></td><td><b>Placówka zgłaszająca<br />Osoba zgłaszająca - Telefon</b></td><td><b>Temat<br />Treść<br />Kategoria->Podkategoria<br />Podkategoria (poziom 2)</b></td><td><b>Przypisane do</b></td></tr>";

	tbl_tr_color(1,$kolorgrupy);
	
	echo "<td>$temp_data<br />$temp_godzina</td>";
	echo "<td>".toUpper($temp_komorka)."<br />$temp_osoba - $temp_telefon</td>";
	
	$r1 = mysql_query("SELECT hd_kategoria_opis FROM $dbname_hd.hd_kategoria WHERE (hd_kategoria_nr='$temp_kategoria') LIMIT 1", $conn_hd) or die($k_b);
	list($kat_opis)=mysql_fetch_array($r1);

	$r2 = mysql_query("SELECT hd_podkategoria_opis FROM $dbname_hd.hd_podkategoria WHERE (hd_podkategoria_nr='$temp_podkategoria') LIMIT 1", $conn_hd) or die($k_b);
	list($podkat_opis)=mysql_fetch_array($r2);

	echo "<td>".nl2br(wordwrap($temp_temat, 60, "<br />"))."<br />".nl2br(wordwrap($temp_tresc, 60, "<br />"))."<br /><b>$kat_opis -> $podkat_opis<br />$temp_podkategoria2</b></td>";
	
	//list($priorytet)=mysql_fetch_array(mysql_query("SELECT hd_priorytet_opis FROM $dbname_hd.hd_priorytet WHERE hd_priorytet_nr=$temp_priorytet LIMIT 1", $conn_hd));
	
	echo "<td>";
		list($status)=mysql_fetch_array(mysql_query("SELECT hd_status_opis FROM $dbname_hd.hd_status WHERE hd_status_nr='$temp_status' LIMIT 1", $conn_hd));
		echo "<b>$temp_osoba_przypisana</b>";
	echo "</td>";
	echo "</tr>";
	echo "</table>";	
	
	echo "</div>";

$result = mysql_query("SELECT * FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id=$_GET[id]) LIMIT 1", $conn_hd) or die($k_b);
$dane_do_edycji = mysql_fetch_array($result);


starttable();
echo "<form id=hd_dodaj_zgl name=hd_dodaj_zgl action=$PHP_SELF method=POST autocomplete=off onSubmit=\"return pytanie_zatwierdz_edycja_zgloszenia('Zapisać zmiany w zgłoszeniu do bazy ?'); \" />";

	$r2 = mysql_query("SELECT zgl_komorka FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id='$_REQUEST[id]') LIMIT 1", $conn_hd) or die($k_b);
	list($zglk)=mysql_fetch_array($r2);	
	
	//$sama_nazwa_up = substr($zglk,strpos($zglk," ")+1,strlen($zglk));

	//$r2 = mysql_query("SELECT up_id FROM $dbname.serwis_komorki WHERE (up_nazwa='$sama_nazwa_up') and (belongs_to=$es_filia) LIMIT 1", $conn_hd) or die($k_b);
	
	$r2 = mysql_query("SELECT up_id FROM $dbname.serwis_komorki,$dbname.serwis_piony WHERE (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.belongs_to=$es_filia) and (up_active=1) and (CONCAT(serwis_piony.pion_nazwa,' ',serwis_komorki.up_nazwa)='$zglk') LIMIT 1", $conn_hd) or die($_k_b);
	
	list($komorkaid)=mysql_fetch_array($r2);	

	//echo "$komorkaid";

if ($_REQUEST[range]!='kat') {	
	echo "<input type=hidden name=komorka_id1 id=komorka_id1 value='$komorkaid'>";	
	echo "<input type=hidden name=old_hddz value='".$dane_do_edycji['zgl_data']."'>";	
	echo "<input type=hidden name=old_hdgz value='".substr($dane_do_edycji['zgl_godzina'],0,5)."'>";	
	echo "<input type=hidden name=old_hdnzhadim value='".$dane_do_edycji['zgl_poczta_nr']."'>";
	echo "<input type=hidden name=old_up_list value='".$dane_do_edycji['zgl_komorka']."'>";
	echo "<input type=hidden name=old_hd_oz value='".$dane_do_edycji['zgl_osoba']."'>";
	echo "<input type=hidden name=old_hdoztelefon value='".$dane_do_edycji['zgl_telefon']."'>";
	echo "<input type=hidden name=old_hd_temat value='".$dane_do_edycji['zgl_temat']."'>";
	echo "<input type=hidden name=old_hd_tresc value='".$dane_do_edycji['zgl_tresc']."'>";
}

//if ($_REQUEST[tk]=='2') {
	echo "<input type=hidden name=old_hd_kategoria value='".$dane_do_edycji['zgl_kategoria']."'>";
	echo "<input type=hidden name=old_hd_podkategoria value='".$dane_do_edycji['zgl_podkategoria']."'>";
	echo "<input type=hidden name=old_hd_podkategoria2 value='".$dane_do_edycji['zgl_podkategoria_poziom_2']."'>";
	//echo "<input type=hidden name=old_hd_priorytet value='".$dane_do_edycji['zgl_priorytet']."'>";	
	
	echo "<input type=hidden name=old_hd_dr value='".$dane_do_edycji['zgl_data_rozpoczecia']."'>";	
	echo "<input type=hidden name=old_hd_dz value='".$dane_do_edycji['zgl_data_zakonczenia']."'>";	
//}

echo "<input type=hidden name=zglid value='".$dane_do_edycji['zgl_id']."'>";
tbl_empty_row(2);

if ($_REQUEST[range]!='kat') {	
	tr_();
		td("140;r;Numer zgłoszenia HDIM");
		td_(";;;");
	//		echo "<b>".$dane_do_edycji['zgl_data']."</b>";
			
	//		$tttt = Date('H:i');
//			echo "&nbsp;&nbsp;Godzina zgłoszenia&nbsp;";
	//		echo "<b>".substr($dane_do_edycji['zgl_godzina'],0,5)."</b>";
			//echo "<input class=wymagane type=text name=hdgz id=hdgz value='".substr($dane_do_edycji['zgl_godzina'],0,5)."' maxlength=5 size=2 onFocus=\"this.select();\" onKeyPress=\"return filterInput(1, event, false, ''); \" onKeyUp=\"DopiszDwukropek('hdgz');\" onDblClick=\"this.value='".$tttt."'; \" title=\" Podwójne kliknięcie wpisuje godzinę otwarcia tego okna \" />";	
			
		//	echo "&nbsp;&nbsp;Numer zgłoszenia z Poczty&nbsp;";
			echo "<input type=text class=wymagane id=hdnzhadim name=hdnzhadim value='".$dane_do_edycji['zgl_poczta_nr']."' maxlength=".$HADIM_max." size=".$HADIM_width." onKeyPress=\"return filterInput(1, event, false); \" onFocus=\"this.select();\" />";

			//echo "<br /><a href=# class=normalfont><font color=red>Aby zmienić godzinę rejestracji zgłoszenia, zmień godzinę wykonania pierwszego kroku</font></a>";
		_td();
	_tr();
	tr_();
		td("140;rt;Komórka zgłaszająca");
		td_(";;;");
			echo "<input class=wymagane type=text size=70 maxlength=50 name=up_list id=up_list value='".toUpper($dane_do_edycji[zgl_komorka])."' onFocus=\"$(this).select();\"  onBlur=\"SprawdzKomorke(this.value); cUpper(this); \" ";
			
			//echo " onChange=\"if (confirm('Pozostawić wpisaną osobę zgłaszającą ?')) { } else { document.getElementById('hd_oz').value=''; document.getElementById('hd_oz').focus(); } \" ";
			
			echo "/>";
			
			if ($komorkaid>0) {
			//	echo "&nbsp;<span id=EW_S style='display:;'>";
			//	echo "<input type=button class=buttons style='padding:1px; margin:1px;' value='Ewidencja sprzętu' onClick=\"newWindow_r(600,600,'p_ewidencja_simple.php?upid=$komorkaid&komorka=".urlencode($zglk)."&alternative=1'); \">";
			//	echo "</span>";
			}
			
			echo "<div id=info1 style='display:none'><i><font color=red>Brak UP / komórki w bazie</font></i></div>";
		_td();
	_tr();
	echo "<tr style='display:none;'>";
		td("140;rt;Osoba zgłaszająca");
		td_(";;;");
			echo "<input class=wymagane type=text name=hd_oz id=hd_oz size=38 maxlength=30 value='".$dane_do_edycji['zgl_osoba']."' onKeyPress=\"return filterInput(0, event, false, ' ęóąśłżźćńĘÓĄŚŁŻŹĆŃ'); \" onFocus=\"this.select();\" onBlur=\"this.value=(trimAll(this.value.toUpperCase())); \" />";
			
			echo "<span id=tel_from_db style=display:inline;>";
	
			echo "&nbsp;Telefon&nbsp;<input type=text id=hdoztelefon name=hdoztelefon value='".$dane_do_edycji['zgl_telefon']."' size=14 maxlength=15 onKeyPress=\"return filterInput(1, event, false, ' '); \" onFocus=\"this.select();\"/>";
			echo "</span>";
			
			echo "<span id=ZapamietajDane style='display:none'>";
			echo "&nbsp;<input class=border0 type=checkbox id=zapamietaj_oz name=zapamietaj_oz>";
			echo "<a onClick=\"if (document.getElementById('zapamietaj_oz').checked) { document.getElementById('zapamietaj_oz').checked=false; } else { document.getElementById('zapamietaj_oz').checked=true; } \"> Zapamiętaj dane</a>";
			echo "</span>";
		_td();
	_tr();	
	tr_();
		td("140;rt;Temat zgłoszenia");
		td_(";;;");
			echo "<input tabindex=-1 type=text name=hd_temat id=hd_temat readonly value='".$dane_do_edycji['zgl_temat']."' size=75 style='border-width:0px;background-color:transparent;font-weight:bold; font-family:tahoma;' ";		
			echo "><br />";		
		_td();
	_tr();
	tr_();
		td("140;rt;Treść zgłoszenia");		
		td_(";;;");
			echo "<textarea class=wymagane name=hd_tresc id=hd_tresc cols=66 rows=2 onKeyUp=\"KopiujDo1Entera(this.value,'hd_temat'); ex1(this); if (this.value!='') { document.getElementById('tr_clear').style.display=''; } else { document.getElementById('tr_clear').style.display='none'; }\" onBlur=\"ZamienTekst(this.id); KopiujDo1Entera(this.value,'hd_temat'); \">";
			echo ClearOutputText($dane_do_edycji['zgl_tresc']);
			echo "</textarea>";
			
			//echo "&nbsp;<a title=' Dodaj treść do słownika' style='display:none' id=sl_d class=imgoption  onClick=\"newWindow_byName('_dodaj_do_slownika',700,400,'hd_d_slownik_tresc.php?akcja=fastadd'); return false;\"><input class=imgoption type=image src=img/slownik_dodaj.gif></a>";
			//echo "<a title=' Wybierz treść ze słownika' id=sl_wybierz class=imgoption  onClick=\"newWindow_byName_r('_wybierz_ze_slownika',700,600,'hd_z_slownik_tresci.php?a=2&akcja=wybierz&p6=".urlencode($currentuser)."'); return false;\"><input class=imgoption type=image src=img/ew_prosty.png></a>";
			
			echo "&nbsp;<a id=tr_clear href=# style='display:' onclick=\"if (confirm('Czy wyczyścić treść zgłoszenia ?')) { document.getElementById('hd_tresc').value=''; KopiujDo1Entera(document.getElementById('hd_tresc').value,'hd_temat'); ex1(document.getElementById('hd_tresc')); document.getElementById('tr_clear').style.display='none'; document.getElementById('sl_d').style.display='none'; document.getElementById('hd_tresc').focus(); }\" title=' Wyczyść treść zgłoszenia '><img src=img/czysc.gif border=0></a>";
			
			//echo "&nbsp;<a id=tr_clear href=# style='display:' onclick=\"document.getElementById('hd_tresc').value=''; KopiujDo1Entera(document.getElementById('hd_tresc').value,'hd_temat'); ex1(document.getElementById('hd_tresc')); document.getElementById('tr_clear').style.display='none'; document.getElementById('sl_d').style.display='none'; document.getElementById('hd_tresc').focus();\" title=' Wyczyść treść zgłoszenia '><img src=img/czysc.gif border=0></a>";
		_td();
	_tr();	
}

if (($_REQUEST[tk]=='1') || ($_REQUEST[tk]=='3') || ($_REQUEST[tk]=='4') || ($_REQUEST[tk]=='5') || ($_REQUEST[tk]=='7')) {
	echo "<tr style='display:none;'>";
		td("140;rt;Kategoria");
		td_(";;;");	
			if ($_REQUEST[tpk]=='G') {
				echo "<b>Prace zlecone w ramach umowy</b>";
				echo "<input type=hidden name=kat_id value='3'>";
			} else {
				echo "<select class=wymagane id=kat_id name=kat_id onChange=\"MakePodkategoriaList(this.options[this.options.selectedIndex].value); if (document.getElementById('kat_id').value==1) { document.getElementById('podkat_id').value = '1'; } if (document.getElementById('kat_id').value==3) { document.getElementById('podkat_id').value = document.getElementById('edit_tpk').value; } if (document.getElementById('kat_id').value==4) { document.getElementById('podkat_id').value = 'D'; } if (document.getElementById('kat_id').value==5) { document.getElementById('podkat_id').value = '1'; } GenerateSubPodkategoriaList(document.getElementById('kat_id').value,document.getElementById('podkat_id').value); \" onKeyUp=\"if (event.keyCode==13) document.getElementById('podkat_id').focus(); \" />\n";	
				echo "<option value='1'"; if ($_REQUEST[tk]=='1') echo " SELECTED "; echo ">Konsultacje</option>\n";
				echo "<option value='3'"; if ($_REQUEST[tk]=='3') echo " SELECTED "; echo ">Prace zlecone w ramach umowy</option>\n";
				echo "<option value='7'"; if ($_REQUEST[tk]=='7') echo " SELECTED "; echo ">Konserwacja</option>\n";
				echo "<option value='4'"; if ($_REQUEST[tk]=='4') echo " SELECTED "; echo ">Prace zlecone poza umową</option>\n";
				echo "<option value='5'"; if ($_REQUEST[tk]=='5') echo " SELECTED "; echo ">Prace na potrzeby Postdata</option>\n";			
				
				echo "</select>\n";
				echo "<span id=WyslijEmail style='display:none'>";
				if ($WlaczMaile=='1') {
					echo "<input class=border0 type=checkbox id=WyslijEmailCheckbox name=WyslijEmailCheckbox>";
					echo "<a onClick=\"if (document.getElementById('WyslijEmailCheckbox').checked) { document.getElementById('WyslijEmailCheckbox').checked=false; } else { document.getElementById('WyslijEmailCheckbox').checked=true; } \"> Wyślij email do koordynatora</a>";
				} 
				echo "</span>";
			}
		_td();
	_tr();
	
	tr_();
		td("140;rt;Podkategoria");
		td_(";;;");	
			if ($_REQUEST[tpk]=='G') {
				echo "<b>Projekty</b>";
				echo "<input type=hidden id=podkat_id name=podkat_id value='G'>";
			} else {
				echo "<select class=wymagane id=podkat_id name=podkat_id  onChange=\"GenerateSubPodkategoriaList(document.getElementById('kat_id').value,document.getElementById('podkat_id').value);\" />\n";
				//echo "<option value=''></option>\n";
				echo "</select>\n";
			}
		_td();
	_tr();
	
	echo "<tr style='display:none;'>";
		td("140;rt;Podkategoria (poziom 2)");
		td_(";;;");	
			if ($_REQUEST[tpk]=='G') {
				echo "<b>$temp_podkategoria2</b>";
				echo "<input type=hidden id=sub_podkat_id name=sub_podkat_id value='$temp_podkategoria2'>";
			} else {
				echo "<select class=wymagane id=sub_podkat_id name=sub_podkat_id />\n";
			//	echo "<option value=''></option>\n";
				echo "</select>\n";
			}
		_td();
	_tr();
	echo "<input type=hidden name=edit_awaria value=1>";
	echo "<input type=hidden name=edit_tpk id=edit_tpk value='$_REQUEST[tpk]'>";
	echo "<input type=hidden name=edit_tp id=edit_tp value='$_REQUEST[tp]'>";
	echo "<input type=hidden name=edit_tpk2 id=edit_tpk2 value='$_REQUEST[tpk2]'>";
}

if (($_REQUEST[tk]=='2') || ($_REQUEST[tk]=='6')) {
	echo "<tr style='display:none;'>";
		td("140;rt;Kategoria");
		td_(";;;");	
			echo "<select class=wymagane id=kat_id name=kat_id onChange=\"MakePodkategoriaList(this.options[this.options.selectedIndex].value);document.getElementById('podkat_id').value = document.getElementById('edit_tpk').value; GenerateSubPodkategoriaList(document.getElementById('kat_id').value,document.getElementById('podkat_id').value); \" onKeyUp=\"if (event.keyCode==13) document.getElementById('podkat_id').focus(); \" />\n";
			if ($_REQUEST[tk]=='6') {
				echo "<option SELECTED value='6'>Awarie krytyczne</option>\n";
			} else {
				if ($_REQUEST[tk]=='2') echo "<option SELECTED value='2'>Awarie</option>\n";
				echo "<option value='3'>Prace zlecone w ramach umowy</option>\n";
			}
			echo "</select>\n";
			echo "<span id=WyslijEmail style='display:none'>";
			if ($WlaczMaile=='1') {
				echo "<input class=border0 type=checkbox id=WyslijEmailCheckbox name=WyslijEmailCheckbox>";
				echo "<a onClick=\"if (document.getElementById('WyslijEmailCheckbox').checked) { document.getElementById('WyslijEmailCheckbox').checked=false; } else { document.getElementById('WyslijEmailCheckbox').checked=true; } \"> Wyślij email do koordynatora</a>";
			} 
			echo "</span>";
		_td();
	_tr();
	
	tr_();
		td("140;rt;Podkategoria");
		td_(";;;");	
			echo "<select class=wymagane id=podkat_id name=podkat_id  onChange=\"GenerateSubPodkategoriaList(document.getElementById('kat_id').value,document.getElementById('podkat_id').value);\" />\n";
			//echo "<option value=''></option>\n";
			echo "</select>\n";			
		_td();
	_tr();
	
	echo "<tr style='display:none;'>";
		td("140;rt;Podkategoria (poziom 2)");
		td_(";;;");	
			echo "<select class=wymagane id=sub_podkat_id name=sub_podkat_id />\n";
			//echo "<option value=''></option>\n";
			echo "</select>\n";
		_td();
	_tr();		
	
	echo "<input type=hidden name=edit_awaria value=1>";
	echo "<input type=hidden name=edit_tpk id=edit_tpk value='$_REQUEST[tpk]'>";
	echo "<input type=hidden name=edit_tp id=edit_tp value='$_REQUEST[tp]'>";
	echo "<input type=hidden name=edit_tpk2 id=edit_tpk2 value='$_REQUEST[tpk2]'>";
	
	if ($_REQUEST[tk]=='6') {
		?>
		<script>
			MakePodkategoriaList('<?php echo $_REQUEST[tk]; ?>'); 
			document.getElementById('podkat_id').value = document.getElementById('edit_tpk').value;
		</script>
		<?php 	
	}
	
} 
//else {
	//echo "<input type=hidden name=edit_awaria value=0>";
	//echo "<input type=hidden name=edit_tpk id=edit_tpk value=''>";
	//echo "<input type=hidden name=edit_tp id=edit_tp value=''>";		
//}
if ($_REQUEST[range]!='kat') {	
	tr_();
		td("140;rt;");
		td_(";;;");	
			echo "<input class=border0 type=checkbox id=nadpisz_krok1 name=nadpisz_krok1 checked=checked>";
			echo "<a href=# class=normalfont onClick=\"if (document.getElementById('nadpisz_krok1').checked) { document.getElementById('nadpisz_krok1').checked=false; } else { document.getElementById('nadpisz_krok1').checked=true; } \"> Nadpisz treść pierwszego kroku  treścią zgłoszenia</a>";
		
		_td();
	_tr();
	
	tbl_empty_row(2);

	echo "<tr style='display:none;'>";
		td("140;r;Widoczność dla Poczty");		
		td_(";;;");
			echo "<input class=border0 type=checkbox name=czy_synchronizowac id=czy_synchronizowac ";
			if ($temp_czy_synchronizowac==1) echo " checked=checked ";
			echo "><a tabindex=-1 class=normalfont href=# onClick=\"if (document.getElementById('czy_synchronizowac').checked) { document.getElementById('czy_synchronizowac').checked=false; return false; } else { document.getElementById('czy_synchronizowac').checked=true; return false; }\"><font color=red>&nbsp;Zgłoszenie widoczne dla Poczty</font></a>";
			
			if ($temp_czy_synchronizowac==1) $temp_czy_synchronizowac = 'on';
			
			echo "<input type=hidden name=old_czy_synchronizowac id=old_czy_synchronizowac value='$temp_czy_synchronizowac'>";	
		_td();
	_tr();	
}
	if ($temp_status==9) {
		echo "<tr style='display:none;'>";
			td("140;r;Zasadność zgłoszenia");
			td_(";;;");
				$old_z = 'NIE';	if ($temp_czy_zasadne==1) $old_z = 'TAK';		
				echo "<input type=hidden id=old_zasadne name=old_zasadne value='$old_z' />";

				echo "<input type=radio style='border:0px' name=zasadne id=zasadne value='TAK'";
				if ($temp_czy_zasadne==1) echo " CHECKED";
				echo ">TAK";
				echo "&nbsp;&nbsp;<input type=radio style='border:0px' name=zasadne id=zasadne value='NIE'";
				if ($temp_czy_zasadne==0) echo " CHECKED";
				echo ">NIE";
			_td();
		echo "</tr>";
		echo "<tr style='display:none;'>";
			td("140;r;Potwierdź sprawdzenie");
			td_(";;;");
				$old_spr_osoba1 = $temp_spr_osoba;
				$old_spr_data1 = $temp_spr_data;
				
				$old='NIE';
				if ($old_spr_osoba1!='') $old='TAK';
				echo "<input type=hidden id=old_spr name=old_spr value='$old' />";

				echo "<input type=radio style='border:0px' name=spr id=zasadne value='TAK'";
				if ($old_spr_osoba1!='') echo " CHECKED";
				echo ">TAK";
				echo "&nbsp;&nbsp;<input type=radio style='border:0px' name=spr id=spr value='NIE'";
				if ($old_spr_osoba1=='') echo " CHECKED";
				echo ">NIE";
				echo "<input type=hidden name=spr_pomin value='NIE' />";
			_td();
		echo "</tr>";		
	} else {
		$old_z = 'NIE';	if ($temp_czy_zasadne==1) $old_z = 'TAK';		
		echo "<input type=hidden id=old_zasadne name=old_zasadne value='$old_z' />";
		
		echo "<input type=hidden name=old_spr value='NIE' />";
		echo "<input type=hidden name=spr_pomin value='TAK' />";
		echo "<input type=hidden id=old_spr_osoba name=old_spr_osoba value='$old_spr_osoba1' />";
		echo "<input type=hidden id=old_spr_data name=old_spr_data value='$old_spr_data1' />";
		
		echo "<input type=hidden id=zasadne name=zasadne value='' />";
	}


tbl_empty_row(1);

endtable();
startbuttonsarea("right");
echo "<input id=submit type=submit class=buttons name=submit style='font-weight:bold;' value='Zapisz zmiany' />";

echo "<input id=reset type=button class=buttons name=reset value=\"Cofnij zmiany\" onClick=\"if (confirm('Cofnąć wprowadzone zmiany ?')) { self.location.reload(); } \" />";
echo "<input id=anuluj class=buttons type=button onClick=\"if (confirm('Potwierdzasz anulowanie wpisanego zgłoszenia ?')) { self.close(); } \" value=Anuluj>";


//echo "<span id=Saving style=display:none><b><font color=red>Trwa zapisywanie zgłoszenia... proszę czekać&nbsp;</font></b><input class=imgoption type=image src=img/loader.gif></span>";
endbuttonsarea();

$result444 = mysql_query("SELECT serwis_komorki.up_id FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) and (CONCAT(UPPER(serwis_piony.pion_nazwa),' ',UPPER(serwis_komorki.up_nazwa)) = '$dane_do_edycji[zgl_komorka]')) LIMIT 1", $conn) or die($k_b);
list($temp_up_id) = mysql_fetch_array($result444);

echo "<input type=hidden name=tuser value='$currentuser'>";
echo "<input type=hidden name=up_list_id id=up_list_id value='$temp_up_id' />";
echo "<span id=up_list_id1 style='display:none'></span>";
echo "<input type=hidden id=stage1 value='$_GET[stage]' />";
echo "<input type=hidden name=unique_nr1 value='$unique_nr1' />";

echo "<input type=hidden name=up_nazwa1 id=up_nazwa1 />";
echo "<input type=hidden name=up_ip1 id=up_ip1 />";
echo "<input type=hidden name=nr value='$_REQUEST[nr]' />";
echo "<input type=hidden name=unr value='$_REQUEST[unr]' />";

echo "<input type=hidden name=up_lokalizacja id=up_lokalizacja />";
echo "<input type=hidden name=up_telefon id=up_telefon />";
echo "<input type=hidden name=up_nazwa1 id=up_nazwa1 />";
echo "<input type=hidden name=up_ip1 id=up_ip1 />";

_form();

}
?>

<script>

//GenerateSubPodkategoriaList(document.getElementById('kat_id').value,document.getElementById('podkat_id').value);

$().ready(function() {
	$("#up_list").autocomplete("hd_get_up_list.php?filia=<?php echo $es_filia; ?>", {
		width: 360,
		max: 150,
		matchContains: false,
		mustMatch: false,
		minChars: 1,
		//multiple: true,
		//highlight: false,
		//multipleSeparator: ",",
		selectFirst: false
	});
	
	$("#up_list").result(function(event, data, formatted) { 
		$("#up_list_id").val(data[1]);
	});
	
	//$("#up_list").result(function(event, data, formatted) { $("#up_lokalizacja").val(data[4]); });	
	//$("#up_list").result(function(event, data, formatted) { $("#up_telefon").val(data[5]); });
	//$("#up_list").result(function(event, data, formatted) { $("#up_nazwa1").val(data[7]); });
	//$("#up_list").result(function(event, data, formatted) { $("#up_ip1").val(data[6]); });	

	$("#hd_oz").autocomplete("hd_get_pracownik_list.php", {
		width: 360,
		max:100,
		matchContains: true,
		mustMatch: false,
		minChars: 1,
		extraParams: { komorka: function() { return $("#up_list_id").val(); } }, 
		//multiple: true,
		//highlight: false,
		//multipleSeparator: ",",
		selectFirst: false
	});  
	$("#hd_oz").result(function(event, data, formatted) { $("#hdoztelefon").val(data[1]); });  

	});
</script>

<script type="text/javascript" src="js/jquery/entertotab_min.js"></script>
<script type='text/javascript'>
EnterToTab.init(document.forms.hd_dodaj_zgl, false);
</script>

<?php //if (($_REQUEST[tk]=='2') || ($_REQUEST[tk]=='3')) { ?>
<script>
MakePodkategoriaList_EZ(document.getElementById('kat_id').options[document.getElementById('kat_id').options.selectedIndex].value);
<?php if ($_REQUEST[tpk]=='') { ?>
	AddToList(document.getElementById('podkat_id'),'','',true,true);
<?php } ?>
GenerateSubPodkategoriaList('<?php echo $_REQUEST[tk]; ?>','<?php echo $_REQUEST[tpk]; ?>');  
<?php if ($temp_podkategoria2=='') { ?>
//	AddToList(document.getElementById('sub_podkat_id'),'','',false,false);
	document.getElementById('sub_podkat_id').value='';
<?php } else { ?>
	document.getElementById('sub_podkat_id').value='<?php echo $temp_podkategoria2; ?>';
<?php } ?>
document.getElementById('podkat_id').value = document.getElementById('edit_tpk').value;
</script>
<?php //} ?>

<?php if ($_REQUEST[tpk]=='G') { ?>
<script>
alert('Dla zgłoszeń w podkategorii `Projekty` nie można edytować kategorii, podkategorii i podkategorii (poziom 2)');
<?php if ($_REQUEST[range]=='kat') { ?>
self.close();
<?php } ?>
</script>
<script>HideWaitingMessage();</script>
<?php } ?>
</body>
</html>