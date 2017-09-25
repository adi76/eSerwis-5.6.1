<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
if ($WlaczMaile=='1') require_once('phpMailer/class.phpmailer.php');
if ($WlaczMaile=='1') require_once('cfg_mails.php');
?>
<body onLoad="document.forms[0].elements[0].focus();
<?php 
if ($_REQUEST[element]=='tresc') { 
	echo " KopiujDo1Entera(document.getElementById('hd_tresc').value,'hd_temat'); document.getElementById('hd_tresc').focus(); "; 
}
?>
">
<?php 
if ($submit) { 
	$_POST=sanitize($_POST);
	
	if ($_POST[element]=='osoba') {
		$sql_a = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_osoba='$_POST[osoba_zgl]', zgl_telefon='$_POST[osoba_tel]' WHERE zgl_id=$_POST[id] LIMIT 1";
	}
	
	if ($_POST[element]=='tresc') {
		$sql_a = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_temat='$_POST[hd_temat]', zgl_tresc='$_POST[hd_tresc]' WHERE zgl_id=$_POST[id] LIMIT 1";
	}
	
	if ($_POST[element]=='czas_zak') {
	
	// ustalenie nr następnego kroku
		$last_nr=$_REQUEST[id];
		$r3 = mysql_query("SELECT zgl_szcz_nr_kroku FROM $dbname_hd.hd_zgloszenie_szcz WHERE ((zgl_szcz_zgl_id='$_REQUEST[id]') and (belongs_to='$es_filia') and (zgl_szcz_widoczne=1)) ORDER BY zgl_szcz_nr_kroku DESC LIMIT 1", $conn_hd) or die($k_b);
		list($last_nr_kroku)=mysql_fetch_array($r3);	
		$last_nr_kroku+=1;
	// koniec
	
		$zgl_seryjme_unique_nr = Date('YmdHis')."".rand_str();
		$czas_START_STOP='START';
		$d_cw = 0;
		$dddd = date("Y-m-d H:i:s");
		
	// ustalenie osoby przypisanej do zgłoszenia
	
		$r3 = mysql_query("SELECT zgl_osoba_przypisana, zgl_komorka FROM $dbname_hd.hd_zgloszenie WHERE ((zgl_id='$_REQUEST[id]') and (belongs_to='$es_filia') and (zgl_widoczne=1)) LIMIT 1", $conn_hd) or die($k_b);
		list($osobaprzypisana,$samoup)=mysql_fetch_array($r3);
	// koniec
	
	// treść ustaleń
		$tu = "<b>Treść ustaleń:</b><br />".$_REQUEST[tresc_ustalen];
		//$tu = '';
	// dodanie nowego kroku
		$awaria_z_przesunieciem=1;
		$przesunieta_data_rozpoczecia = $_REQUEST[nowa_data_zak]." ".$_REQUEST[nowy_czas_zak];
		$przesuniecie_osoba_z_poczty = $_REQUEST[hd_opp];
	
		list($czy_rozwiazany)=mysql_fetch_array(mysql_query("SELECT zgl_czy_rozwiazany_problem FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id=$last_nr) LIMIT 1", $conn_hd));
	
		$sql = "INSERT INTO $dbname_hd.hd_zgloszenie_szcz VALUES ('',$last_nr,'$zgl_seryjme_unique_nr',$last_nr_kroku,'$czas_START_STOP',$d_cw,'$dddd','3','$tu','$osobaprzypisana','',0,0,0,0,'$dddd','$currentuser',1,0,$awaria_z_przesunieciem,'$przesunieta_data_rozpoczecia','$przesuniecie_osoba_z_poczty','','','','','$_REQUEST[hdds]',$czy_rozwiazany,0,$es_filia)";
		$result = mysql_query($sql, $conn_hd) or die($k_b);		
		// koniec

		// zaktualizuj zgl_czy_rozwiazany_problem w zgłoszeniu 
		$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_czy_rozwiazany_problem='$czy_rozwiazany' WHERE ((belongs_to='$es_filia') and (zgl_nr='$last_nr')) LIMIT 1";
		$result = mysql_query($sql, $conn_hd) or die($k_b);
	
		// = nowa data rozpoczęcia + czas dodany zgodnie z umową
		$przesunieta_data_zakonczenia = '';

		if ($_POST[kat_zgl]=='2') {
			if ($_POST[prior_zgl]=='4') $przesunieta_data_zakonczenia = AddHoursToDate("8",$przesunieta_data_rozpoczecia).":00";
			if ($_POST[prior_zgl]=='2') {
				if (($_POST[podkat_zgl]=='2') || ($_POST[podkat_zgl]=='5')) 
					$przesunieta_data_zakonczenia = AddWorkingDays("5","".$przesunieta_data_rozpoczecia."")." ".$_REQUEST[nowy_czas_zak].":00";
				if (($_POST[podkat_zgl]=='3') || ($_POST[podkat_zgl]=='4')) 
					$przesunieta_data_zakonczenia = AddWorkingDays("14","".$przesunieta_data_rozpoczecia."")." ".$_REQUEST[nowy_czas_zak].":00";	
			}
		}
		
	// zaktualizowanie statusu w zgłoszeniu
		$sql_a = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_status='3', zgl_data_rozpoczecia='$_REQUEST[nowa_data_zak] $_REQUEST[nowy_czas_zak]', zgl_data_zakonczenia='$przesunieta_data_zakonczenia' WHERE zgl_id=$_REQUEST[id] LIMIT 1";
	
		if ($WlaczMaile=='1') {
		
			$result44 = mysql_query("SELECT serwis_komorki.up_id, serwis_komorki.up_nazwa, serwis_piony.pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) and (serwis_komorki.up_nazwa = '$samoup')) LIMIT 1", $conn) or die($k_b);
			$count_rows = mysql_num_rows($result44);	
			list($temp_up_id,$temp_nazwa,$temp_pion) = mysql_fetch_array($result44);
		
			$r3 = mysql_query("SELECT up_umowa_id FROM $dbname.serwis_komorki WHERE ((up_id='$temp_up_id') and (belongs_to='$es_filia')) LIMIT 1", $conn) or die($k_b);
			list($umowaid)=mysql_fetch_array($r3);
	
			$r4 = mysql_query("SELECT umowa_koordynator, umowa_koordynator_email FROM $dbname.serwis_umowy WHERE ((umowa_id='$umowaid') and (belongs_to='$es_filia')) LIMIT 1", $conn) or die($k_b);
			list($koord, $koord_email)=mysql_fetch_array($r4);
				
				$koord_email = 'maciej.adrjanowicz@postdata.pl';
				
			if ($koord_email!='') {
				// wyślij email
				$temat_maila = "Zmieniono czas zakończenia zgłoszenia nr $last_nr";
				//$tresc_maila = "Do bazy dodano nowe zgłoszenie nr $last_nr\n";
				$tresc_maila.= "Osoba zmieniająca czas zakończenia : $currentuser\n";
				$tresc_maila.= "Data zmiany : $dddd\n";
				$tresc_maila.= "$tu\n";
				$tresc_maila.= "\n\n";
				$tresc_maila.= "Mail został wygenerowany automatycznie - proszę na niego nie odpisywać";
				
				if (smtpmailer($koord_email, 'helpdesk-lodz@postdata.pl', 'Helpdesk - O/Łódź', $temat_maila, $tresc_maila, $last_nr)) {
					echo "<h3>Email został wysłany do $koord ($koord_email)</h3>";
					$emailSend = 1;
				}
				if (!empty($error)) echo $error;
				
				//echo "email pójdzie do : $koord ($koord_email)";
			
			} else { 
				echo "<h2>Email do koordynatora nie został wysłany, gdyż nie został zdefiniowany w bazie umów</h2>";
			}
					
		
		}
	
	}	
		
		if (mysql_query($sql_a, $conn_hd)) { 
				?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
		} else {
			?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
		}
	// koniec

} else {
	$r2 = mysql_query("SELECT zgl_komorka FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id='$_REQUEST[id]') LIMIT 1", $conn_hd) or die($k_b);
	list($zglk)=mysql_fetch_array($r2);	
	
	$sama_nazwa_up = substr($zglk,strpos($zglk," ")+1,strlen($zglk));
			
	$r2 = mysql_query("SELECT up_id FROM $dbname.serwis_komorki WHERE (up_nazwa='$sama_nazwa_up') and (belongs_to=$es_filia) LIMIT 1", $conn_hd) or die($k_b);
	list($komorkaid)=mysql_fetch_array($r2);	
			
	//echo "$komorkaid";
	echo "<input type=hidden name=komorka_id1 id=komorka_id1 value='$komorkaid'>";
			
			
switch ($_GET[element]) {
	case "tresc" : 	pageheader("Edycja treści zgłoszenia numer $_GET[nr]"); 
					$result = mysql_query("SELECT zgl_tresc,zgl_temat FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id=$_GET[id]) LIMIT 1", $conn_hd) or die($k_b);
					list($value_to_change,$value_to_change_2)=mysql_fetch_array($result);
					break;
	case "osoba" : 	pageheader("Edycja osoby zgłaszającej dla zgłoszenia numer $_GET[nr]"); 
					$result = mysql_query("SELECT zgl_osoba,zgl_telefon FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id=$_GET[id]) LIMIT 1", $conn_hd) or die($k_b);
					list($value_to_change,$value_to_change_2)=mysql_fetch_array($result);
					break;
	case "czas_zak" : pageheader("Edycja czasu rozpoczęcia realizacji zgłoszenia numer $_GET[nr]"); 
					$result = mysql_query("SELECT zgl_data_rozpoczecia,zgl_kategoria, zgl_podkategoria, zgl_priorytet FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id=$_GET[id]) LIMIT 1", $conn_hd) or die($k_b);
					list($value_to_change,$temp_kat,$temp_podkat,$temp_prior)=mysql_fetch_array($result);
	
					 break;
}

starttable();

echo "<form name=add action=$PHP_SELF method=POST onSubmit=\"return pytanie_zapisz_ez('Zapisać zmiany do bazy ?'); \">";

include_once('systemdate.php');
	
tbl_empty_row();

if ($_GET[element]=='osoba') {
tr_();
	td("100;r;Osoba zgłaszająca");
	td_(";;");
		echo "<input class=wymagane class=wymagane size=40 maxlength=200 type=text name=osoba_zgl value='".strtoupper($value_to_change)."' onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();
tr_();
	td("100;r;Telefon");
	td_(";;");
		echo "<input size=20 maxlength=200 type=text name=osoba_tel value='$value_to_change_2' onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();
}

if ($_GET[element]=='tresc') {
	tr_();
		td("150;rt;<b>Temat zgłoszenia</b>");
		td_(";;;");
			echo "<input tabindex=-1 type=text name=hd_temat id=hd_temat readonly size=80 style='border-width:0px;background-color:transparent;font-weight:bold; font-family:tahoma;' ";
			echo " value='$value_to_change_2' ";
			echo "><br />";		
		_td();
	_tr();
	tr_();
		td("150;rt;<b>Treść zgłoszenia</b>");
		td_(";;;");
			echo "<textarea class=wymagane name=hd_tresc id=hd_tresc cols=65 rows=2 onFocus=\"\" onKeyUp=\"KopiujDo1Entera(this.value,'hd_temat'); ex1(this);\">";
			echo $value_to_change;
			echo "</textarea>";			
		_td();
	_tr();	
}

if ($_GET[element]=='czas_zak') {

tr_();
	td("200;r;Aktualny czas rozpoczęcia realizacji zgłoszenia");
	td_(";;");
		echo "<b>$value_to_change</b>";
	_td();
_tr();

tr_();
	td("200;r;Nowy czas rozpoczęcia");
	td_(";;");
		$dddd = Date("Y-m-d");
		echo "<input class=wymagane size=10 maxlength=10 type=text id=nowa_data_zak name=nowa_data_zak value='".substr($value_to_change,0,10)."' onkeypress=\"return filterInput(1, event, false, ''); \" onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\" onBlur=\"CheckDate(this.value);\" onKeyUp=\"DopiszKreski('nowa_data_zak');\" >&nbsp;";
		
		echo "<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif width=16 height=16 border=0></a>&nbsp;";
		if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('nowa_data_zak').value='".Date('Y-m-d')."'; return false;\">";
		
		echo "<input class=wymagane size=5 maxlength=5 type=text id=nowy_czas_zak name=nowy_czas_zak value='".substr($value_to_change,11,5)."' onkeypress=\"return handleEnter(this, event);\" onBlur=\"CheckTime(this.value);\" onKeyUp=\"DopiszDwukropek('nowy_czas_zak');\">";
	_td();
_tr();

tr_();
	td("200;rt;Treść ustaleń odnośnie zmiany terminu");
	td_(";;");
		echo "<textarea class=wymagane id=tresc_ustalen name=tresc_ustalen rows=4 cols=50></textarea>";
	_td();
_tr();
tr_();
	td("200;rt;Osoba potwierdzająca przesunięcie");
	td_(";;");
		echo "<input class=wymagane type=text name=hd_opp id=hd_opp size=38 maxlength=30 onKeyPress=\"return filterInput(0, event, false, ' ęóąśłżźćńĘÓĄŚŁŻŹĆŃ'); \" onFocus=\"this.select();\" onBlur=\"cUpper(this); \" >";	
	_td();
_tr();

echo "<input type=hidden name=old_data_zak id=old_data_zak value='".substr($value_to_change,0,10)."'>";
echo "<input type=hidden name=old_czas_zak id=old_czas_zak value='".substr($value_to_change,11,5)."'>";

echo "<input type=hidden name=kat_zgl id=kat_zgl value='$temp_kat'>";
echo "<input type=hidden name=podkat_zgl id=podkat_zgl value='$temp_podkat'>";
echo "<input type=hidden name=prior_zgl id=prior_zgl value='$temp_prior'>";


}

echo "<input type=hidden name=id value=$_GET[id]>";
echo "<input type=hidden name=element value='$_GET[element]'>";

tbl_empty_row();	
endtable();
startbuttonsarea("right");
echo "<input class=buttons type=submit name=submit value=Zapisz />";
addbuttons("anuluj");
endbuttonsarea();
_form();
}
?>


<script language="JavaScript">
var cal1 = new calendar1(document.forms['add'].elements['nowa_data_zak']);
	cal1.year_scroll = true;
	cal1.time_comp = false;

$("#hd_opp").autocomplete("hd_get_pracownik_list.php", {
	width: 360,
	max:100,
	matchContains: true,
	mustMatch: false,
	minChars: 1,
	extraParams: { komorka: function() { return $("#komorka_id1").val(); } }, 
	//multiple: true,
	//highlight: false,
	//multipleSeparator: ",",
	selectFirst: false
});
	
</script>

</body>
</html>