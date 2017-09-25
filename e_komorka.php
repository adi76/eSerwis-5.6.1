<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
$KierownikId = $kierownik_nr;
function toUpper($string) {
	$string = strtr($string,'qwertyuiopasdfghjklzxcvbnm','QWERTYUIOPASDFGHJKLZXCVBNM');
	return (strtr($string, 'ęóąśłżźćń','ĘÓĄŚŁŻŹĆŃ' )); 
}; 

if ($submit) {	
	$_POST=sanitize($_POST);
	if (($es_nr!=$KierownikId) && ($is_dyrektor==0)) $godziny_pracy = '';
	
	// wygenerowanie godzin pracy
	if (($es_nr==$KierownikId) || ($is_dyrektor==1)) {	
	
		$godziny_pracy_alt_start = $_POST[gp_alt_od];
		$godziny_pracy_alt_stop = $_POST[gp_alt_do];
		
		if ($_POST[SO_start]=='') $_POST[SO_start]='';
		if ($_POST[SO_stop]=='') $_POST[SO_stop]='';
		if ($_POST[NI_start]=='') $_POST[NI_start]='';
		if ($_POST[NI_stop]=='') $_POST[NI_stop]='';
		
		$godziny_pracy = "";
		$godziny_pracy .= "PN@".$_POST[PN_start]."-".$_POST[PN_stop].";";
		$godziny_pracy .= "WT@".$_POST[WT_start]."-".$_POST[WT_stop].";";
		$godziny_pracy .= "SR@".$_POST[SR_start]."-".$_POST[SR_stop].";";
		$godziny_pracy .= "CZ@".$_POST[CZ_start]."-".$_POST[CZ_stop].";";
		$godziny_pracy .= "PT@".$_POST[PT_start]."-".$_POST[PT_stop].";";
		$godziny_pracy .= "SO@".$_POST[SO_start]."-".$_POST[SO_stop].";";
		$godziny_pracy .= "NI@".$_POST[NI_start]."-".$_POST[NI_stop].";";

		if ($godziny_pracy == 'PN@-;WT@-;SR@-;CZ@-;PT@-;SO@-;NI@-;') $godziny_pracy = '';
		
		if ($_POST[SOa_start]=='') $_POST[SOa_start]='';
		if ($_POST[SOa_stop]=='') $_POST[SOa_stop]='';
		if ($_POST[NIa_start]=='') $_POST[NIa_start]='';
		if ($_POST[NIa_stop]=='') $_POST[NIa_stop]='';

		$godziny_pracy_a = "";
		$godziny_pracy_a .= "PN@".$_POST[PNa_start]."-".$_POST[PNa_stop].";";
		$godziny_pracy_a .= "WT@".$_POST[WTa_start]."-".$_POST[WTa_stop].";";
		$godziny_pracy_a .= "SR@".$_POST[SRa_start]."-".$_POST[SRa_stop].";";
		$godziny_pracy_a .= "CZ@".$_POST[CZa_start]."-".$_POST[CZa_stop].";";
		$godziny_pracy_a .= "PT@".$_POST[PTa_start]."-".$_POST[PTa_stop].";";
		$godziny_pracy_a .= "SO@".$_POST[SOa_start]."-".$_POST[SOa_stop].";";
		$godziny_pracy_a .= "NI@".$_POST[NIa_start]."-".$_POST[NIa_stop].";";

		if ($godziny_pracy_a == 'PN@-;WT@-;SR@-;CZ@-;PT@-;SO@-;NI@-;') $godziny_pracy_a = '';
		
	}
	
	$umowy = '0';
	$typuslugi = '';
	
	echo ">".$_POST[utypuslugi];
	
	if ($_POST[utypuslugi]!='') $typuslugi = implode(',',$_POST[utypuslugi]);	
	if ($_POST[umowa_id]!='') $umowy = implode(',',$_POST[umowa_id]);
	
	//$umowy = implode(',',$_POST[umowa_id]);
	if ($_POST[utyp]=='4') { $ukat1 = 0; } else { $ukat1 = $_POST[ukat]; }

	if (($_POST[utyp]=='2') || ($_POST[utyp]=='3')) {
		$komorka_nadrzedna_value = $_POST[komorka_nadrzedna];
	} else {
		$komorka_nadrzedna_value = 0;
	}
	
	$datazamknieciakomorki = '';
	if ($_POST[active_status]=='2') $datazamknieciakomorki = $_POST[dzk];
	
	$sql_e1="UPDATE $dbname.serwis_komorki SET up_nazwa = '$_POST[upnazwa]' , up_opis = '$_POST[uopis]' ,  up_telefon = '$_POST[uptelefon]' , up_ip = '$_POST[upip]' , up_nrwanportu = '$_POST[upwan]', belongs_to = '$_POST[upbelongs_to]' , up_adres = '$_POST[upadres]', up_stempel = '$_POST[ustempel]', up_pion_id=$_POST[pion_id], up_umowa_id='$umowy', up_active=$_POST[active_status], up_typ=$_POST[utyp], up_kategoria=$ukat1, up_kompleksowa_obsluga=$_POST[uko], up_typ_uslugi = '$typuslugi', up_przypisanie_jednostki = '$_POST[updz]', up_close_date = '$datazamknieciakomorki', up_komorka_macierzysta_id = '$komorka_nadrzedna_value', up_backupname='$_POST[backup]', up_ipserwera='$_POST[ipserwera]' WHERE up_id = '$upid' LIMIT 1";
	
//	echo $sql_e1;
	
	if (mysql_query($sql_e1, $conn)) { 
	
		if (($es_nr==$KierownikId) || ($is_dyrektor==1)) {	
			$sql_e1="UPDATE $dbname.serwis_komorki SET up_working_time = '$godziny_pracy' , up_working_time_alternative = '$godziny_pracy_a' ,  up_working_time_alternative_start_date = '0000-$godziny_pracy_alt_start' , up_working_time_alternative_stop_date = '0000-$godziny_pracy_alt_stop' WHERE up_id = '$upid' LIMIT 1";
	//		echo $sql_e1;
			$wynik = mysql_query($sql_e1, $conn);
		}
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else {
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
	}
} else {

// sprawdzenie czy można edytować
$blokuj_edycje_nazwy = 0;

$result = mysql_query("SELECT up_id,up_nazwa FROM $dbname.serwis_komorki WHERE (up_id=$select_id) LIMIT 1", $conn) or die($k_b);
list($temp_id,$temp_nazwa)=mysql_fetch_array($result);

// sprawdzenie czy nie pobrano sprzętu serwisowego na stan tego UP
$result_a22 = mysql_query("SELECT magazyn_id,magazyn_naprawa_id FROM $dbname.serwis_magazyn WHERE magazyn_status=1 and belongs_to=$es_filia", $conn) or die($k_b);
while ($newArray = mysql_fetch_array($result_a22)) {
	$mid  			= $newArray['magazyn_id'];
	$mnid			= $newArray['magazyn_naprawa_id'];

	$s_pobrany = 0;
	$s_w_naprawie = 0;
	$s_awariawan = 0;
	$w_naprawie = 0;
	
	// sprawdź czy jest pobrany sam sprzęt
	$result_a = mysql_query("SELECT historia_id,historia_up, historia_komentarz,historia_data FROM $dbname.serwis_historia WHERE historia_magid=$mid ORDER BY historia_data DESC LIMIT 1", $conn) or die($k_b);
	list($hid,$hup,$hkomentarz,$hdata) = mysql_fetch_array($result_a);
 
 	// czy nie ma otwartych awarii wan z tej placówki
	
	$result_a44 = mysql_query("SELECT awaria_id FROM $dbname.serwis_awarie WHERE (awaria_gdzie='$temp_nazwa') and (awaria_status=0) and (belongs_to=$es_filia)", $conn) or die($k_b);
	list($aid) = mysql_fetch_array($result_a44);	

	if ($aid>0) {
		$s_awariawan = 1;
		break;
	}	
	
	$sql_up = "SELECT up_id, up_pion_id FROM $dbname.serwis_komorki WHERE (up_nazwa='$hup') and (belongs_to=$es_filia) LIMIT 1";
	$wynik = mysql_query($sql_up, $conn) or die($k_b);
	$dane_up = mysql_fetch_array($wynik);
	$temp_up_id = $dane_up['up_id'];
	$temp_pion_id = $dane_up['up_pion_id'];
 	
	if ($temp_up_id == $select_id ) { 
		$s_pobrany = 1; 
		break; 
	}
	
	// sprawdź czy z komórki nie ma sprzętu w naprawie
	$result_a33 = mysql_query("SELECT naprawa_id, naprawa_pobrano_z FROM $dbname.serwis_naprawa WHERE (naprawa_id=$mnid) and (naprawa_status<5) and (belongs_to=$es_filia)", $conn) or die($k_b);
	list($nid,$npz) = mysql_fetch_array($result_a33);
	//echo $nid;
	if (($nid>0) && ($npz=='$temp_nazwa')) {
		$s_w_naprawie = 1;
		break;
	}
}

// sprawdzenie czy z tej komórki nie ma sprzętu w naprawie 

$result_a33 = mysql_query("SELECT naprawa_id, naprawa_status FROM $dbname.serwis_naprawa WHERE ((naprawa_pobrano_z='$temp_nazwa') and (naprawa_status<5) and (belongs_to=$es_filia))", $conn) or die($k_b);
list($nid, $nstat)=mysql_fetch_array($result_a33);

if ($nid>0) { 
	$w_naprawie=1; 
}
	
if ($s_awariawan==1) { 
	?>
	<script>
	info('Nie można edytować nazwy tej komórki, gdyż otwarte jest dla niej zgłoszenie awarii WAN');
	</script>
	<?php
	$blokuj_edycje_nazwy = 1;
}

if (($s_pobrany==1) && ($w_naprawie==0)) { 
	?>
	<script>
	info('Nie można edytować nazwy tej komórki, gdyż do tej komórki pobrano sprzęt serwisowy');
	</script>
	<?php
	$blokuj_edycje_nazwy = 1;
}


if ($s_w_naprawie==1) { 
	?>
	<script>
	info('Nie można edytować nazwy tej komórki, gdyż z tej komórki pobrano sprzęt do naprawy');
	</script>
	<?php
	$blokuj_edycje_nazwy = 1;
}
//echo $w_naprawie." | ".$nstat."";
if ($w_naprawie==1) { 

	if ($nstat=='-1') {
		?>
		<script>
		info('Nie można edytować nazwy tej komórki.\n\rZ tej komórki pobrano sprzęt do naprawy');
		</script>
		<?php
		$blokuj_edycje_nazwy = 1;
	}

	if ($nstat=='0') {
		?>
		<script>
		info('Nie można edytować nazwy tej komórki.\n\rSprzęt z tej komórki jest w naprawie we własnym zakresie');
		</script>
		<?php
		$blokuj_edycje_nazwy = 1;
	}

	if ($nstat=='1') {
		?>
		<script>
		info('Nie można edytować nazwy tej komórki.\n\rSprzęt z tej komórki jest w naprawie w serwisie zewnętrznym');
		</script>
		<?php
		$blokuj_edycje_nazwy = 1;	
	}

	if ($nstat=='2') {
		?>
		<script>
		info('Nie można edytować nazwy tej komórki.\n\rSprzęt z tej komórki jest w naprawie w serwisie lokalnym');
		</script>
		<?php
		$blokuj_edycje_nazwy = 1;	
	}	

	if ($nstat=='3') {
		?>
		<script>
		info('Nie można edytować nazwy tej komórki.\n\rSprzęt z tej komórki jest gotowy do oddania.');
		</script>
		<?php
		$blokuj_edycje_nazwy = 1;	
	}
	
}

// koniec sprawdzania

$result = mysql_query("SELECT * FROM $dbname.serwis_komorki WHERE (up_id=$select_id) LIMIT 1", $conn) or die($k_b);
while ($newArray = mysql_fetch_array($result)) {
	$temp_id  			= $newArray['up_id'];				
	$temp_nazwa			= $newArray['up_nazwa'];
	$temp_opis			= $newArray['up_opis'];				
	$temp_telefon		= $newArray['up_telefon'];
	$temp_adres			= $newArray['up_adres'];			
	$temp_ip			= $newArray['up_ip'];
	$temp_nrwanportu	= $newArray['up_nrwanportu'];		
	$temp_stempel		= $newArray['up_stempel'];
	$temp_belongs_to	= $newArray['belongs_to'];			
	$temp_pion_id		= $newArray['up_pion_id'];
	$temp_umowa_id		= $newArray['up_umowa_id'];			
	$temp_active_status	= $newArray['up_active'];
	$temp_up_typ		= $newArray['up_typ'];				
	$temp_up_kategoria	= $newArray['up_kategoria'];
	$temp_up_ko			= $newArray['up_kompleksowa_obsluga'];
	$temp_ipserwera		= $newArray['up_ipserwera'];
	$temp_backupname 	= $newArray['up_backupname'];
	$temp_working_time				= $newArray['up_working_time'];	
	$temp_working_time_alt			= $newArray['up_working_time_alternative'];	
	$temp_working_time_start_date	= $newArray['up_working_time_alternative_start_date'];		
	$temp_working_time_stop_date	= $newArray['up_working_time_alternative_stop_date'];

	$temp_typ_uslugi	= $newArray['up_typ_uslugi'];
	$temp_przypisanie_jedn	= $newArray['up_przypisanie_jednostki'];
	
	$temp_up_open_date = $newArray['up_open_date'];
	$temp_up_close_date = $newArray['up_close_date'];
	
	$temp_komorka_macierzysta = $newArray['up_komorka_macierzysta_id'];
	
}

pageheader("Edycja danych o komórce");
starttable();
echo "<form name=edu action=$PHP_SELF method=POST onSubmit=\"return pytanie_edytuj_komorke('Czy potwierdzasz poprawność danych ?');\">";
//tbl_empty_row(2);
tr_();echo "<td colspan=2><b>Informacje ogólne o komórce</b><hr /></td>";_tr();	
tr_();
	td("120;r;Pion");
	td_(";;");
		$query = "SELECT pion_id,pion_nazwa FROM $dbname.serwis_piony ORDER BY pion_nazwa";
		if($result = mysql_query($query)) { 
			if($success = mysql_num_rows($result) > 0) { 
				echo "<select name=pion_id class=wymagane onkeypress='return handleEnter(this, event);'>\n";
				//echo "<option value=0>Wybierz z listy...</option>\n"; 
				while (list($pid,$pnazwa) = mysql_fetch_array($result)) { 
					echo "<option value=$pid";
					if ($pid==$temp_pion_id) echo " SELECTED ";
					echo ">$pnazwa</option>\n"; 
				}
		  		echo "</select>\n"; 
			}
		}

		echo "&nbsp;&nbsp;&nbsp;&nbsp;";
		echo "Typ komórki&nbsp;";		
		$result = mysql_query("SELECT slownik_typ_komorki_id,slownik_typ_komorki_opis FROM $dbname.serwis_slownik_typ_komorki", $conn) or die($k_b);
		if($success = mysql_num_rows($result) > 0) {		
			echo "<select name=utyp id=utyp onkeypress='return handleEnter(this, event);' onChange=\"if (this.value==4) { document.getElementById('kategoria').style.display='none'; } else { document.getElementById('kategoria').style.display='';} if ((this.value==2) || (this.value==3)) { document.getElementById('KomNad').style.display=''; } else { document.getElementById('KomNad').style.display='none'; document.getElementById('KomNad').value=''; } \">\n";
			while (list($typid,$typ_opis)=mysql_fetch_array($result)) { 
				echo "<option value=$typid"; if ($temp_up_typ==$typid) echo " SELECTED"; echo ">$typ_opis</option>\n"; 
			}
		}
		echo "</select>";
		
		
		echo "<span id=kategoria style='display:";
		if ($temp_up_typ=='4') echo "none";
		echo "'>";
		
			echo "&nbsp;&nbsp;&nbsp;&nbsp;Kategoria&nbsp;";
			$result = mysql_query("SELECT slownik_kategoria_komorki_id,slownik_kategoria_komorki_opis FROM $dbname.serwis_slownik_kategoria_komorki", $conn) or die($k_b);
			if($success = mysql_num_rows($result) > 0) {		
				echo "<select name='ukat' onkeypress='return handleEnter(this, event);'>\n";
				while (list($katid,$kat_opis)=mysql_fetch_array($result)) { 
					echo "<option value=$katid"; if ($temp_up_kategoria==$katid) echo " SELECTED"; echo ">$kat_opis</option>\n"; 
				}
			}
			echo "</select>";
		echo "</span>";
		
		echo "&nbsp;&nbsp;&nbsp;&nbsp;";
		echo "Stempel okręgowy&nbsp;";		
		echo "<input size=10 maxlength=8 type=text name=ustempel value='$temp_stempel' onkeypress='return handleEnter(this, event);'>";		
			
	_td();
_tr();

tr_();
	td("120;r;Nazwa komórki");
	td_(";;");
		if ($blokuj_edycje_nazwy==1) {
			echo "<input type=hidden id=upe name=upnazwa value='$temp_nazwa'>";
			echo "<input title=' Nazwa komórki nie może być edytowana ' size=50 type=text style='background-color:transparent' readonly value='$temp_nazwa'>";
		} else {
			echo "<input class=wymagane id=upe size=40 maxlength=60 type=text name=upnazwa onKeyUp='slownik_upe()' onBlur='slownik_upe()' value='$temp_nazwa'>";
			echo "<img name=status src=img//none.gif>";
			echo "<select class=wymagane name=lista style='display:none' onkeypress='return handleEnter(this, event);'>";
			$result=mysql_query("SELECT up_nazwa FROM $dbname.serwis_komorki WHERE belongs_to=$es_filia ORDER BY up_nazwa",$conn) or die($k_b);
			while (list($temp)=mysql_fetch_array($result)) {
				if ($temp!=$temp_nazwa) echo "<option value='$temp'>$temp</option>\n";
			}
			echo "</select>";
		}		

		echo "Adres&nbsp;";
		echo "<input size=40 maxlength=100 type=text name=upadres value='$temp_adres' onkeypress='return handleEnter(this, event);'>";
		
	_td();
_tr();

tr_();
	td("120;r;Telefon(y)");
	td_(";;");
		echo "<input size=40 maxlength=100 type=text name=uptelefon value='$temp_telefon' onkeypress='return handleEnter(this, event);'>";
		
		echo "&nbsp;&nbsp;&nbsp;&nbsp;";
		echo "Opis&nbsp;";
		echo "<input size=40 maxlength=100 type=text name=uopis value='$temp_opis' onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();
tr_();
	td("120;r;Podsieć");
	td_(";;");
		echo "<input size=11 maxlength=11 type=text name=upip value='$temp_ip' onkeypress='return handleEnter(this, event);'>";

		echo "&nbsp;&nbsp;&nbsp;&nbsp;";
		echo "Nr WAN-portu&nbsp;";		
		echo "<input size=20 maxlength=20 type=text name=upwan value='$temp_nrwanportu' onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();

tr_();
	td("120;r;Ostatni człon IP serwera");
	td_(";;");
		echo "<input size=3 maxlength=3 type=text name=ipserwera value='$temp_ipserwera' onkeypress='return handleEnter(this, event);' style='text-align:right;'>";
		echo "&nbsp; 10.216.39.xxx";
	_td();
_tr();

tr_();
	td("120;r;Obsługiwany przez");
	td_(";;");
		$query = "SELECT filia_id,filia_nazwa FROM $dbname.serwis_filie ";
		if ($es_m!=1) $query = $query. "WHERE filia_id=$es_filia"; 
		if ($result = mysql_query($query,$conn)) { 
		 	if($success = mysql_num_rows($result) > 0) { 
				echo "<select name='upbelongs_to' onkeypress='return handleEnter(this, event);'>\n"; 
				$result_e2 = mysql_query("SELECT filia_nazwa FROM $dbname.serwis_filie WHERE filia_id=$temp_belongs_to",$conn) or die($k_b);
				list($return_name)=mysql_fetch_array($result_e2);
				while (list($fid,$fnazwa)=mysql_fetch_array($result)) { 
					echo "<option value='$fid' ";
					if ($fnazwa==$return_name) echo "selected";
					echo " >$fnazwa</option>\n"; 
				} 
				echo "</select>\n"; 
			}
		}	
		echo "&nbsp;&nbsp;Status komórki ";
		
		echo "<select name=active_status class=wymagane onkeypress='return handleEnter(this, event);' onChange=\"if (this.value==2) { document.getElementById('closedate').style.display=''; document.getElementById('dzk').select(); document.getElementById('dzk').focus(); } else { document.getElementById('closedate').style.display='none'; } \">\n";

	// jeżeli komórka ma status: aktywna
	if ($temp_active_status=='1') {		
		echo "<option value='1'";
		if ($temp_active_status=='1') echo " SELECTED ";
		echo ">aktywna</option>\n"; 

		echo "<option value='0'";
		if ($temp_active_status=='0') echo " SELECTED ";
		echo ">zawieszona</option>\n"; 

		echo "<option value='2'";
		if ($temp_active_status=='2') echo " SELECTED ";
		echo ">zamknięta</option>\n"; 
	}
	
	// jeżeli komórka ma status: zawieszona
	if ($temp_active_status=='0') {		
		echo "<option value='1'";
		if ($temp_active_status=='1') echo " SELECTED ";
		echo ">aktywna</option>\n"; 

		echo "<option value='0'";
		if ($temp_active_status=='0') echo " SELECTED ";
		echo ">zawieszona</option>\n"; 

		echo "<option value='2'";
		if ($temp_active_status=='2') echo " SELECTED ";
		echo ">zamknięta</option>\n"; 
	}
	
	// jeżeli komórka ma status: zamknięta
	if ($temp_active_status=='2') {		
//		echo "<option value='1'";
//		if ($temp_active_status=='1') echo " SELECTED ";
//		echo ">aktywna</option>\n"; 

//		echo "<option value='0'";
//		if ($temp_active_status=='0') echo " SELECTED ";
//		echo ">zawieszona</option>\n"; 

		echo "<option value='2'";
		if ($temp_active_status=='2') echo " SELECTED ";
		echo ">zamknięta</option>\n"; 
	}
		
		echo "</select>\n";
		
		echo "<span id=closedate style='display:none'>";
			
			echo "<font color=red><b>&nbsp;&nbsp;Data zamknięcia komórki </b></font>";
			$dddd = Date('Y-m-d');
			echo "<input id=dzk size=10 maxlength=10 type=text name=dzk maxlength=10 value=$dddd onkeypress='return handleEnter(this, event);' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\" onBlur=\"if (this.value=='') { alert('Podanie daty zamknięcia komórki jest wymagane'); } \" />";
			echo "&nbsp;<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img src=img/cal.gif width=16 height=16 border=0></a>";
			if ($_today==1) echo "&nbsp;<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('dzk').value='".Date('Y-m-d')."'; return false;\">";		
		echo "</span>";
		
	_td();
_tr();

	tr_();
		td("120;r;Nazwa pliku z backup'em");
		td_(";;");	
			echo "<input class=wymagane id=backup size=40 maxlength=60 type=text name=backup value='$temp_backupname' onKeyUp=\"slownik_backupe();\" onBlur=\"slownik_backupe();\">";
			echo "<img name=statusb src=img//none.gif>";
			echo "<select name=listabackup id=listabackup style='display:none'>";
			$result=mysql_query("SELECT up_backupname FROM $dbname.serwis_komorki WHERE belongs_to=$es_filia AND (up_backupname<>'') ORDER BY up_backupname",$conn) or die($k_b);
			while (list($temp)=mysql_fetch_array($result)) { echo "<option value='$temp'>$temp</option>\n"; }
			echo "</select>";			
		_td();
	_tr();
	
tbl_empty_row();
	echo "<tr id=KomNad style='display:none'>";
		td("120;r;Komórka/UP macierzyste");
		td_(";;");	
			
			echo "<select name=komorka_nadrzedna id=komorka_nadrzedna>";
			$sql_lista_up = "SELECT up_id, up_nazwa, pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((serwis_komorki.belongs_to=$es_filia) and (serwis_piony.pion_id=serwis_komorki.up_pion_id) and (serwis_komorki.up_typ=1)) ORDER BY serwis_piony.pion_nazwa, serwis_komorki.up_nazwa";
			$wynik_lista_up = mysql_query($sql_lista_up,$conn) or die($k_b);
			echo "<option value='0'>brak</option>\n";
			
			while (list($temp_upid, $temp_upnazwa, $temp_pionnazwa)=mysql_fetch_array($wynik_lista_up)) {
				echo "<option "; 
				if ($temp_komorka_macierzysta==$temp_upid) echo "SELECTED ";
				echo "value='$temp_upid'>$temp_pionnazwa $temp_upnazwa</option>\n";				
			}
			echo "</select>";
			
			if ($temp_komorka_macierzysta==0) {
				$up_nazwa_part = substr($temp_nazwa,0,8);				
				$sql_99 = "SELECT up_id, up_nazwa, pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((serwis_komorki.belongs_to=$es_filia) and (serwis_piony.pion_id=serwis_komorki.up_pion_id) and (serwis_komorki.up_typ=1) and (up_active=1) and (serwis_komorki.up_nazwa LIKE '%".$up_nazwa_part."%')) ORDER BY serwis_piony.pion_nazwa, serwis_komorki.up_nazwa LIMIT 1";
				$wynik = mysql_query($sql_99,$conn) or die($k_b);
				list($podpowiedz, $pod_nazwa, $pod_pion) = mysql_fetch_array($wynik);
				
				if ($podpowiedz!='') {
					echo " | <font color=green>Proponowana komórka macierzysta: </font>";
					echo "<input type=button class=buttons value='$pod_pion $pod_nazwa' onClick=\"document.getElementById('komorka_nadrzedna').value='$podpowiedz'; return false; \" />";
				} else {
					echo " | <font color=green>Proponowana komórka macierzysta: </font>";
					echo "<font color=red>brak</font>";
				}
			}
		_td();
	_tr();
	
tbl_empty_row();	
tr_();
	td("120;r;Data otwarcia komórki");
	td_(";;");	
		echo "<b>$temp_up_open_date</b>";
	_td();
_tr();
	
tbl_empty_row();
tr_();echo "<td colspan=2><b>Szczegółowe informacje o komórce</b><hr /></td>";_tr();	
	
tr_();
	td("120;r;Typ usługi<br /><br /><font color=red>Użyj Ctrl, <br />aby zaznaczyć więcej pozycji</font>");
	td_(";;");
	
		$typuslugi = explode(',',$temp_typ_uslugi);
	
		echo "<select class=wymagane multiple=multiple name=utypuslugi[] onkeypress='return handleEnter(this, event);'>";
		echo "<option value='KOI' "; 
		foreach ($typuslugi as $thisone) { if ($thisone=='KOI') echo " SELECTED "; }
		echo ">Kompleksowa Obsługa Informatyczna (KOI)</option>\n";
		
		echo "<option value='OK' ";
		foreach ($typuslugi as $thisone) { if ($thisone=='OK') echo " SELECTED "; }
		echo ">Okresowa konserwacja (OK)</option>\n";
		
		echo "<option value='UZ' ";
		foreach ($typuslugi as $thisone) { if ($thisone=='UZ') echo " SELECTED "; }
		echo ">Usługi na żądanie (UZ)</option>\n";
		
		echo "<option value='UUAK' ";
		foreach ($typuslugi as $thisone) { if ($thisone=='UUAK') echo " SELECTED "; }
		echo ">Usługa Usuwania Awarii Krytycznych (UUAK)</option>\n";
		
		echo "</select>";
		
		echo "&nbsp;&nbsp;&nbsp;&nbsp;";		
		echo "Kompleksowa obsługa ";
		echo "<select class=wymagane name=uko onkeypress='return handleEnter(this, event);'>";
		echo "<option value=0"; if ($temp_up_ko==0) echo " SELECTED"; echo ">NIE</option>\n";
		echo "<option value=1"; if ($temp_up_ko==1) echo " SELECTED";echo ">TAK</option>\n";
		echo "</select>";
	_td();
_tr();
tr_();
	td("120;r;Przypisanie jednostki do załącznika");
	td_(";;");
		echo "<select name=updz onkeypress='return handleEnter(this, event);'>";
		
		echo "<option value=''>brak</option>\n";
		if ($obszar=='Szczecin') {
			echo "<option value='1Szczecin' "; if ($temp_przypisanie_jedn=='1Szczecin') echo "SELECTED"; echo ">1Szczecin</option>\n";			
			echo "<option value='3Szczecin' "; if ($temp_przypisanie_jedn=='3Szczecin') echo "SELECTED"; echo ">3Szczecin</option>\n";
			echo "<option value='4Szczecin' "; if ($temp_przypisanie_jedn=='4Szczecin') echo "SELECTED"; echo ">4Szczecin</option>\n";
			echo "<option value='5Szczecin' "; if ($temp_przypisanie_jedn=='5Szczecin') echo "SELECTED"; echo ">5Szczecin</option>\n";
		}
		if ($obszar=='Łódź') {
			echo "<option value='1Łódź' "; if ($temp_przypisanie_jedn=='1Łódź') echo "SELECTED"; echo ">1Łódź</option>\n";
			echo "<option value='2Łódź' "; if ($temp_przypisanie_jedn=='2Łódź') echo "SELECTED"; echo ">2Łódź</option>\n";
			echo "<option value='3Łódź' "; if ($temp_przypisanie_jedn=='3Łódź') echo "SELECTED"; echo ">3Łódź</option>\n";
			echo "<option value='5Łódź' "; if ($temp_przypisanie_jedn=='5Łódź') echo "SELECTED"; echo ">5Łódź</option>\n";
		}
		echo "</select>";
		
	_td();
_tr();
tr_();
	td("120;rt;Podlega pod umowę<br /><br /><font color=red>Użyj Ctrl, <br />aby zaznaczyć więcej pozycji</font>");
	td_(";;");
		$umowy = explode(',',$temp_umowa_id);
		
		$query = "SELECT umowa_id,umowa_nr,umowa_opis FROM $dbname.serwis_umowy ";
		if ($es_m!=1) $query=$query."WHERE belongs_to=$es_filia"; 
		$query.= " ORDER BY umowa_nr";
		if($result = mysql_query($query)) { 
			if($success = mysql_num_rows($result) > 0) { 
				echo "<select name=umowa_id[] id=umowa_id class=wymagane multiple=multiple onkeypress='return handleEnter(this, event);'>\n";
				//echo "<option value=0>Wybierz umowę z listy...</option>\n";
				while (list($uid,$unr,$uopis)=mysql_fetch_array($result)) { 
					echo "<option value=$uid";
					
					foreach ($umowy as $thisone) { if ($uid==$thisone) echo " SELECTED "; }

					echo ">$uopis ($unr)</option>\n"; 
				} 
		  		echo "</select>\n"; 
			}
		}	
	_td();
_tr();
tbl_empty_row();	

	if (($es_nr==$KierownikId) || ($is_dyrektor==1)) {
	
	$days = explode(";",$temp_working_time);
	
	$oneday = explode("@",$days[0]); $PN = explode("-",$oneday[1]);
	$oneday = explode("@",$days[1]); $WT = explode("-",$oneday[1]);
	$oneday = explode("@",$days[2]); $SR = explode("-",$oneday[1]);
	$oneday = explode("@",$days[3]); $CZ = explode("-",$oneday[1]);
	$oneday = explode("@",$days[4]); $PT = explode("-",$oneday[1]);
	$oneday = explode("@",$days[5]); $SO = explode("-",$oneday[1]);
	$oneday = explode("@",$days[6]); $NI = explode("-",$oneday[1]);

	if ($SO[0]=='') $SO[0]='';
	if ($SO[1]=='') $SO[1]='';
	if ($NI[0]=='') $NI[0]='';
	if ($NI[1]=='') $NI[1]='';
	
	$days = explode(";",$temp_working_time_alt);
	
	$oneday = explode("@",$days[0]); $PNa = explode("-",$oneday[1]);
	$oneday = explode("@",$days[1]); $WTa = explode("-",$oneday[1]);
	$oneday = explode("@",$days[2]); $SRa = explode("-",$oneday[1]);
	$oneday = explode("@",$days[3]); $CZa = explode("-",$oneday[1]);
	$oneday = explode("@",$days[4]); $PTa = explode("-",$oneday[1]);
	$oneday = explode("@",$days[5]); $SOa = explode("-",$oneday[1]);
	$oneday = explode("@",$days[6]); $NIa = explode("-",$oneday[1]);

	if ($SOa[0]=='') $SOa[0]='';
	if ($SOa[1]=='') $SOa[1]='';
	if ($NIa[0]=='') $NIa[0]='';
	if ($NIa[1]=='') $NIa[1]='';

	tr_();
		echo "<td class=righttop><b><u>Godziny pracy</u><br /><br /></b>";
		echo "<input type=button class=buttons value='Domyślne godziny' onClick=\"setDefaultWorkingHours(''); sprawdzWszystkieGodziny();\"><br />";
		echo "<input type=button class=buttons value='Wyczyść godziny' onClick=\"clearDefaultWorkingHours(''); sprawdzWszystkieGodziny(); \">";
		echo "</td>";
		td_(";;");
			echo "<table>";
				echo "<tr>";
					echo "<td>";
						echo "";
					echo "</td>";
					echo "<td>";
						echo "rozpoczęcie";
						echo "&nbsp;<input class=buttons type=button value='Kopia PN dla WT-PT' onClick=\"KopiujGodzinyPracy('start','');\" />";
					echo "</td>";
					echo "<td>";
						echo "zakończenie";
						echo "&nbsp;<input class=buttons type=button value='Kopia PN dla WT-PT' onClick=\"KopiujGodzinyPracy('stop','');\" />";
					echo "</td>";
				echo "</tr>";

				echo "<tr>";
					echo "<td>Poniedziałek:</td>";					
					echo "<td width=220><input type=text name=PN_start id=PN_start maxlength=5 size=4 value='$PN[0]' onDblClick=\"this.value='$PN[0]';\" onKeyUp=\"DopiszDwukropek1(this.id);\" title='Dwuklik wpisuje wartość domyślną' onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=PN_start_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=PN_start_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
					echo "<td width=220><input type=text name=PN_stop id=PN_stop maxlength=5 size=4 value='$PN[1]' onDblClick=\"this.value='$PN[1]';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=PN_stop_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=PN_stop_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
				echo "</tr>";

				echo "<tr>";
					echo "<td>Wtorek:</td>";					
					echo "<td width=220><input type=text name=WT_start id=WT_start maxlength=5 size=4 value='$WT[0]' onDblClick=\"this.value='$WT[0]';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=WT_start_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=WT_start_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
					echo "<td width=220><input type=text name=WT_stop id=WT_stop maxlength=5 size=4 value='$WT[1]' onDblClick=\"this.value='$WT[1]';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=WT_stop_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=WT_stop_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
				echo "</tr>";

				echo "<tr>";
					echo "<td>Środa:</td>";					
					echo "<td width=220><input type=text name=SR_start id=SR_start maxlength=5 size=4 value='$SR[0]' onDblClick=\"this.value='$SR[0]';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=SR_start_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=SR_start_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
					echo "<td width=220><input type=text name=SR_stop id=SR_stop maxlength=5 size=4 value='$SR[1]' onDblClick=\"this.value='$SR[1]';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=SR_stop_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=SR_stop_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
				echo "</tr>";

				echo "<tr>";
					echo "<td>Czwartek:</td>";					
					echo "<td width=220><input type=text name=CZ_start id=CZ_start maxlength=5 size=4 value='$CZ[0]' onDblClick=\"this.value='$CZ[0]';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=CZ_start_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=CZ_start_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
					echo "<td width=220><input type=text name=CZ_stop id=CZ_stop maxlength=5 size=4 value='$CZ[1]' onDblClick=\"this.value='$CZ[1]';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=CZ_stop_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=CZ_stop_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
				echo "</tr>";

				echo "<tr>";
					echo "<td>Piątek:</td>";					
					echo "<td width=220><input type=text name=PT_start id=PT_start maxlength=5 size=4 value='$PT[0]' onDblClick=\"this.value='$PT[0]';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=PT_start_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=PT_start_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
					echo "<td width=220><input type=text name=PT_stop id=PT_stop maxlength=5 size=4 value='$PT[1]' onDblClick=\"this.value='$PT[1]';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=PT_stop_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=PT_stop_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
				echo "</tr>";

				echo "<tr>";
					echo "<td>Sobota:</td>";					
					echo "<td width=220><input type=text name=SO_start id=SO_start maxlength=5 size=4 value='$SO[0]' onDblClick=\"this.value='$SO[0]';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=SO_start_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=SO_start_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
					echo "<td width=220><input type=text name=SO_stop id=SO_stop maxlength=5 size=4 value='$SO[1]' onDblClick=\"this.value='$SO[1]';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=SO_stop_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=SO_stop_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
				echo "</tr>";

				echo "<tr>";
					echo "<td>Niedziela:</td>";					
					echo "<td width=220><input type=text name=NI_start id=NI_start maxlength=5 size=4 value='$NI[0]' onDblClick=\"this.value='$NI[0]';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=NI_start_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=NI_start_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
					echo "<td width=220><input type=text name=NI_stop id=NI_stop maxlength=5 size=4 value='$NI[1]' onDblClick=\"this.value='$NI[1]';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=NI_stop_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=NI_stop_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
				echo "</tr>";
			echo "</table>";
		_td();
	_tr();
	
	tr_();
		echo "<td class=righttop><b><u>Godziny pracy (alternatywne)</u><br /></b><br />";
		echo "<input type=button class=buttons value='Domyślne godziny' onClick=\"setDefaultWorkingHours('a'); sprawdzWszystkieGodziny();\"><br />";
		echo "<input type=button class=buttons value='Wyczyść godziny' onClick=\"clearDefaultWorkingHours('a'); sprawdzWszystkieGodziny();\">";
		echo "</td>";
		td_(";;");
			echo "<table>";
			
				echo "<tr>";
				
				$alt_od = substr($temp_working_time_start_date,5,5);
				$alt_do = substr($temp_working_time_stop_date,5,5);
				
				if ($temp_working_time_start_date=='0000-00-00') $alt_od='';
				if ($temp_working_time_stop_date=='0000-00-00') $alt_do='';
				
					echo "<td>";
						echo "Obowiązuje od (MM-DD)<br />czerwiec - wrzesień<br />np. 06-01 &nbsp;&nbsp;&nbsp;&nbsp; 09-30";
					echo "</td>";
					echo "<td>";
						echo "<input type=text name=gp_alt_od id=gp_alt_od maxlength=5 size=4 value='$alt_od' onBlur=\"okres_ok(this.value, this.name); \" />";
						echo "<span id=gp_alt_od_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisany okres</font></span>";
						echo "<span id=gp_alt_od_span_ok style='display:none'><font color=green>&nbsp;Okres wpisany poprawnie</font></span>";						
					echo "</td>";
					echo "<td>";
						echo "<input type=text name=gp_alt_do id=gp_alt_do maxlength=5 size=5 value='$alt_do' onBlur=\"okres_ok(this.value, this.name); \" />";
						echo "<span id=gp_alt_do_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisany okres</font></span>";
						echo "<span id=gp_alt_do_span_ok style='display:none'><font color=green>&nbsp;Okres wpisany poprawnie</font></span>";									
					echo "</td>";
				echo "</tr>";
				
				echo "<tr>";
					echo "<td>";
						echo "";
					echo "</td>";				
					echo "<td>";
						echo "rozpoczęcie";
						echo "&nbsp;<input class=buttons type=button value='Kopia PN dla WT-PT' onClick=\"KopiujGodzinyPracy('start','a');\" />";
					echo "</td>";
					echo "<td>";
						echo "zakończenie";
						echo "&nbsp;<input class=buttons type=button value='Kopia PN dla WT-PT' onClick=\"KopiujGodzinyPracy('stop','a');\" />";
					echo "</td>";
				echo "</tr>";

				echo "<tr>";
					echo "<td>Poniedziałek:</td>";					
					echo "<td width=220><input type=text name=PNa_start id=PNa_start maxlength=5 size=4 value='$PNa[0]' onDblClick=\"this.value='$PNa[0]';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=PNa_start_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=PNa_start_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
					echo "<td width=220><input type=text name=PNa_stop id=PNa_stop maxlength=5 size=4 value='$PNa[1]' onDblClick=\"this.value='$PNa[1]';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=PNa_stop_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=PNa_stop_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
				echo "</tr>";

				echo "<tr>";
					echo "<td>Wtorek:</td>";					
					echo "<td width=220><input type=text name=WTa_start id=WTa_start maxlength=5 size=4 value='$WTa[0]' onDblClick=\"this.value='$WTa[0]';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=WTa_start_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=WTa_start_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
					echo "<td width=220><input type=text name=WTa_stop id=WTa_stop maxlength=5 size=4 value='$WTa[1]' onDblClick=\"this.value='$WTa[1]';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=WTa_stop_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=WTa_stop_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
				echo "</tr>";

				echo "<tr>";
					echo "<td>Środa:</td>";					
					echo "<td width=220><input type=text name=SRa_start id=SRa_start maxlength=5 size=4 value='$SRa[0]' onDblClick=\"this.value='$SRa[0]';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=SRa_start_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=SRa_start_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
					echo "<td width=220><input type=text name=SRa_stop id=SRa_stop maxlength=5 size=4 value='$SRa[1]' onDblClick=\"this.value='$SRa[1]';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=SRa_stop_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=SRa_stop_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
				echo "</tr>";

				echo "<tr>";
					echo "<td>Czwartek:</td>";					
					echo "<td width=220><input type=text name=CZa_start id=CZa_start maxlength=5 size=4 value='$CZa[0]' onDblClick=\"this.value='$CZa[0]';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=CZa_start_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=CZa_start_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
					echo "<td width=220><input type=text name=CZa_stop id=CZa_stop maxlength=5 size=4 value='$CZa[1]' onDblClick=\"this.value='$CZa[1]';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=CZa_stop_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=CZa_stop_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
				echo "</tr>";

				echo "<tr>";
					echo "<td>Piątek:</td>";					
					echo "<td width=220><input type=text name=PTa_start id=PTa_start maxlength=5 size=4 value='$PTa[0]' onDblClick=\"this.value='$PTa[0]';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=PTa_start_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=PTa_start_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
					echo "<td width=220><input type=text name=PTa_stop id=PTa_stop maxlength=5 size=4 value='$PTa[1]' onDblClick=\"this.value='$PTa[1]';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=PTa_stop_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=PTa_stop_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
				echo "</tr>";

				echo "<tr>";
					echo "<td>Sobota:</td>";					
					echo "<td width=220><input type=text name=SOa_start id=SOa_start maxlength=5 size=4 value='$SOa[0]' onDblClick=\"this.value='$SOa[0]';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=SOa_start_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=SOa_start_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
					echo "<td width=220><input type=text name=SOa_stop id=SOa_stop maxlength=5 size=4 value='$SOa[1]' onDblClick=\"this.value='$SOa[1]';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=SOa_stop_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=SOa_stop_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
				echo "</tr>";

				echo "<tr>";
					echo "<td>Niedziela:</td>";					
					echo "<td width=220><input type=text name=NIa_start id=NIa_start maxlength=5 size=4 value='$NIa[0]'  onDblClick=\"this.value='$NIa[0]';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=NIa_start_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=NIa_start_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
					echo "<td width=220><input type=text name=NIa_stop id=NIa_stop maxlength=5 size=4 value='$NIa[1]'  onDblClick=\"this.value='$NIa[1]';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=NIa_stop_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=NIa_stop_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
				echo "</tr>";
			echo "</table>";
		_td();
	_tr();
	
	} else {
		tr_();
			echo "<td></td><td>";
			okheader("Godziny pracy komórki może definiować kierownik filii");
			echo "</td>";
		_tr();
	
	
	}
	
tbl_empty_row();
echo "<input size=30 type=hidden name=upid value='$temp_id'>";	
endtable();
startbuttonsarea("right");
addbuttons("zapisz","anuluj");
endbuttonsarea();
_form();

?>
<script>
if ((document.getElementById('utyp').value==2) || (document.getElementById('utyp').value==3)) { 
	document.getElementById('KomNad').style.display=''; 
} else { 
	document.getElementById('KomNad').style.display='none'; 
	document.getElementById('KomNad').value='';
}
</script>

<script language="JavaScript">
var cal1 = new calendar1(document.forms['edu'].elements['dzk']);
	cal1.year_scroll = true;
	cal1.time_comp = false;
</script>

<?php } ?>

</body>
</html>