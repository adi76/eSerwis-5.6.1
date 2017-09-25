<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
$KierownikId = $kierownik_nr;

if ($submit) { 
	$_POST=sanitize($_POST);
	$umowy = '0';
	if (($es_nr!=$KierownikId) && ($is_dyrektor==0)) $godziny_pracy = '';
	
	// wygenerowanie godzin pracy
	//if (($es_nr==$KierownikId) || ($is_dyrektor==1)) {	
	
		$godziny_pracy_alt_start = $_POST[gp_alt_od];
		$godziny_pracy_alt_stop = $_POST[gp_alt_do];
		
		if ($_POST[SO_start]=='') $_POST[SO_start]='-';
		if ($_POST[SO_stop]=='') $_POST[SO_stop]='-';
		if ($_POST[NI_start]=='') $_POST[NI_start]='-';
		if ($_POST[NI_stop]=='') $_POST[NI_stop]='-';
		
		$godziny_pracy = "";
		$godziny_pracy .= "PN@".$_POST[PN_start]."-".$_POST[PN_stop].";";
		$godziny_pracy .= "WT@".$_POST[WT_start]."-".$_POST[WT_stop].";";
		$godziny_pracy .= "SR@".$_POST[SR_start]."-".$_POST[SR_stop].";";
		$godziny_pracy .= "CZ@".$_POST[CZ_start]."-".$_POST[CZ_stop].";";
		$godziny_pracy .= "PT@".$_POST[PT_start]."-".$_POST[PT_stop].";";
		$godziny_pracy .= "SO@".$_POST[SO_start]."-".$_POST[SO_stop].";";
		$godziny_pracy .= "NI@".$_POST[NI_start]."-".$_POST[NI_stop].";";

		if ($godziny_pracy == 'PN@-;WT@-;SR@-;CZ@-;PT@-;SO@-;NI@-;') $godziny_pracy = '';
		
		if ($_POST[SOa_start]=='') $_POST[SOa_start]='-';
		if ($_POST[SOa_stop]=='') $_POST[SOa_stop]='-';
		if ($_POST[NIa_start]=='') $_POST[NIa_start]='-';
		if ($_POST[NIa_stop]=='') $_POST[NIa_stop]='-';

		$godziny_pracy_a = "";
		$godziny_pracy_a .= "PN@".$_POST[PNa_start]."-".$_POST[PNa_stop].";";
		$godziny_pracy_a .= "WT@".$_POST[WTa_start]."-".$_POST[WTa_stop].";";
		$godziny_pracy_a .= "SR@".$_POST[SRa_start]."-".$_POST[SRa_stop].";";
		$godziny_pracy_a .= "CZ@".$_POST[CZa_start]."-".$_POST[CZa_stop].";";
		$godziny_pracy_a .= "PT@".$_POST[PTa_start]."-".$_POST[PTa_stop].";";
		$godziny_pracy_a .= "SO@".$_POST[SOa_start]."-".$_POST[SOa_stop].";";
		$godziny_pracy_a .= "NI@".$_POST[NIa_start]."-".$_POST[NIa_stop].";";
		
		if ($godziny_pracy_a == 'PN@-;WT@-;SR@-;CZ@-;PT@-;SO@-;NI@-;') $godziny_pracy_a = '';
	//}
	
	if ($_POST[umowa_id]!='') $umowy = implode(',',$_POST[umowa_id]);
	
	$typuslugi = '';
	if ($_POST[utypuslugi]!='') $typuslugi = implode(',',$_POST[utypuslugi]);
	
	if ($_POST[utyp]=='4') { $ukat1 = 0; } else { $ukat1 = $_POST[ukat]; }
	
	if (($_POST[utyp]=='2') || ($_POST[utyp]=='3')) {
		$komorka_nadrzedna_value = $_POST[komorka_nadrzedna];
	} else {
		$komorka_nadrzedna_value = 0;
	}
	
	$sql_a = "INSERT INTO $dbname.serwis_komorki values ('', '$_POST[unazwa]','$_POST[uopis]','$_POST[utelefon]','$_POST[upip]','$_POST[upwan]','$_POST[ubelongs_to]','$_POST[uadres]','$_POST[ustempel]',$_POST[pion_id],'$umowy','1',$_POST[utyp],$ukat1,$_POST[uko],'$godziny_pracy','$godziny_pracy_a','0000-$_POST[gp_alt_od]','0000-$_POST[gp_alt_do]','$typuslugi','$updz','$_POST[dok]','','$komorka_nadrzedna_value','$_REQUEST[backup]','$_REQUEST[ipserwera]')";
	
	if (mysql_query($sql_a, $conn)) { 
			?><script>if (opener) opener.location.reload(true); self.close();  </script><?php
	} else {
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
	}
} else {
	pageheader("Dodawanie nowej komórki");
	starttable();
	echo "<form name=addu action=$PHP_SELF method=POST onSubmit=\"return pytanie_dodaj_komorke('Czy dodać komórkę do bazy ?');\">";
	//tbl_empty_row();
	tr_();echo "<td colspan=2><b>Informacje ogólne o komórce</b><hr /></td>";_tr();	
	tr_();
		td("120;r;Pion");
		td_(";;");
			$result = mysql_query("SELECT pion_id,pion_nazwa FROM $dbname.serwis_piony ORDER BY pion_nazwa",$conn) or die($k_b);
			if(mysql_num_rows($result)>0) { 
				echo "<select name=pion_id id=pion_id class=wymagane onkeypress='return handleEnter(this, event);'>\n"; 
				echo "<option value=0>Wybierz z listy...</option>\n"; 
				while (list($pid,$pnazwa)=mysql_fetch_array($result)) {
				echo "<option value=$pid>$pnazwa</option>\n"; 
				} 
			  	echo "</select>\n"; 
			}
			
//			echo "&nbsp;&nbsp;";			
			
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			echo "Typ komórki&nbsp;";
			$result = mysql_query("SELECT slownik_typ_komorki_id,slownik_typ_komorki_opis FROM $dbname.serwis_slownik_typ_komorki", $conn) or die($k_b);
			if($success = mysql_num_rows($result) > 0) {		
				echo "<select name=utyp id=utyp onkeypress='return handleEnter(this, event);' onChange=\"if (this.value==4) { document.getElementById('kategoria').style.display='none'; } else { document.getElementById('kategoria').style.display='';} if ((this.value==2) || (this.value==3)) { document.getElementById('KomNad').style.display=''; } else { document.getElementById('KomNad').style.display='none'; document.getElementById('KomNad').value=''; }\">\n";
				while (list($typid,$typ_opis)=mysql_fetch_array($result)) { 
					echo "<option value='$typid'>$typ_opis</option>\n"; 
				}
			}
			echo "</select>";
			
			echo "<span id=kategoria style='display:'>";			
				echo "&nbsp;&nbsp;&nbsp;&nbsp;";
				echo "Kategoria&nbsp;";
				$result = mysql_query("SELECT slownik_kategoria_komorki_id,slownik_kategoria_komorki_opis FROM $dbname.serwis_slownik_kategoria_komorki", $conn) or die($k_b);
				if($success = mysql_num_rows($result) > 0) {		
					echo "<select name=ukat id=ukat onkeypress='return handleEnter(this, event);'>\n";
					while (list($katid,$kat_opis)=mysql_fetch_array($result)) { 
						echo "<option value='$katid'"; if ($katid==3) echo " SELECTED"; echo ">$kat_opis</option>\n"; 
					}
				}
				echo "</select>";
			echo "</span>";
			
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			echo "Stempel okręgowy&nbsp;";
			echo "<input size=10 maxlength=8 type=text name=ustempel onkeypress='return handleEnter(this, event);'>";
		_td();
	_tr();
	tr_();
		td("120;r;Nazwa komórki");
		td_(";;");
			//echo ": ";
			echo "<input class=wymagane id=up size=40 maxlength=60 type=text name=unazwa onKeyUp='slownik_upa()' onBlur='slownik_upa()'>";
			echo "<img name=status src=img//none.gif>";
			echo "<select name=lista id=lista style='display:none'>";
			$result=mysql_query("SELECT up_nazwa FROM $dbname.serwis_komorki WHERE belongs_to=$es_filia ORDER BY up_nazwa",$conn) or die($k_b);
			while (list($temp)=mysql_fetch_array($result)) { echo "<option value='$temp'>$temp</option>\n"; }
			echo "</select>";

			//echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			echo "Adres&nbsp;";
			echo "<input size=40 maxlength=100 type=text name=uadres onkeypress='return handleEnter(this, event);'>";

		_td();
	_tr();
	tr_();
		td("120;r;Telefon(y)");
		td_(";;");
			//echo "<input size=50 maxlength=100 type=text name=uadres onkeypress='return handleEnter(this, event);'>";
			echo "<input size=40 maxlength=100 type=text name=utelefon onkeypress='return handleEnter(this, event);'>";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			echo "Opis&nbsp;";
			echo "<input size=40 maxlength=80 type=text name=uopis onkeypress='return handleEnter(this, event);'>";
			
		_td();
	_tr();
	tr_();
		td("120;r;Podsieć");
		td_(";;");
			echo "<input size=11 maxlength=11 type=text name=upip onkeypress='return handleEnter(this, event);'>";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			echo "Nr WAN-portu&nbsp;";
			echo "<input size=20 maxlength=20 type=text name=upwan onkeypress='return handleEnter(this, event);'>";
		_td();
	_tr();

	tr_();
		td("120;r;Ostatni człon IP serwera");
		td_(";;");
			echo "<input size=3 maxlength=3 type=text name=ipserwera value='50' onkeypress='return handleEnter(this, event);' style='text-align:right;'>";
			echo "&nbsp; 10.216.39.xxx";
		_td();
	_tr();
	
	tr_();
		td("120;r;Obsługa przez");
		td_(";;");	
			$query = "SELECT filia_id, filia_nazwa FROM $dbname.serwis_filie ";
			if ($es_m!=1) $query=$query."WHERE filia_id=$es_filia"; 			
			if($result = mysql_query($query)) { 
				if($success = mysql_num_rows($result) > 0) { 
					echo "<select name='ubelongs_to' onkeypress='return handleEnter(this, event);'>\n"; 
					while (list($fid,$fnazwa) = mysql_fetch_array($result)) {  
					echo "<option value='$fid'>$fnazwa</option>\n"; 
					} 
					echo "</select>\n"; 
				}
			}	
		_td();
	_tr();
	
	tr_();
		td("120;r;Nazwa pliku z backup'em");
		td_(";;");	
			echo "<input class=wymagane id=backup size=40 maxlength=60 type=text name=backup onKeyUp=\"slownik_backup();\" onBlur=\"slownik_backup();\">";
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

			echo "<select name=komorka_nadrzedna>";
			$sql_lista_up = "SELECT up_id, up_nazwa, pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((serwis_komorki.belongs_to=$es_filia) and (serwis_piony.pion_id=serwis_komorki.up_pion_id) and (serwis_komorki.up_typ=1)) ORDER BY serwis_piony.pion_nazwa, serwis_komorki.up_nazwa";
			$wynik_lista_up = mysql_query($sql_lista_up,$conn) or die($k_b);
			echo "<option value='0'>brak</option>\n";
			
			while (list($temp_upid, $temp_upnazwa, $temp_pionnazwa)=mysql_fetch_array($wynik_lista_up)) {
				echo "<option "; 
				if ($wybierz==$temp_upid) echo "SELECTED ";
				echo "value='$temp_upid'>$temp_pionnazwa $temp_upnazwa</option>\n";				
			}
			echo "</select>";

		_td();
	_tr();
	
	tbl_empty_row();	
	tr_();
		td("120;r;<font color=blue>Data otwarcia komórki</font>");
		td_(";;");	
			$dddd = Date('Y-m-d');
			echo "<input class=wymagane id=dok size=10 maxlength=10 type=text name=dok maxlength=10 value=$dddd onkeypress='return handleEnter(this, event);' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\">";
			echo "&nbsp;<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img src=img/cal.gif width=16 height=16 border=0></a>";
			if ($_today==1) echo "&nbsp;<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('dok').value='".Date('Y-m-d')."'; return false;\">";
		_td();
	_tr();

		
	tbl_empty_row();
	tr_();echo "<td colspan=2><b>Szczegółowe informacje o komórce</b><hr /></td>";_tr();	
	
	tr_();
		td("120;r;Typ usługi<br /><br /><font color=red>Użyj Ctrl, <br />aby zaznaczyć więcej pozycji</font>");
		td_(";;");
			echo "<select class=wymagane multiple=multiple name=utypuslugi[] id=utypuslugi onkeypress='return handleEnter(this, event);'>";
			echo "<option value='KOI' SELECTED>Kompleksowa Obsługa Informatyczna (KOI)</option>\n";
			echo "<option value='OK'>Okresowa konserwacja (OK)</option>\n";
			echo "<option value='UZ'>Usługi na żądanie (UZ)</option>\n";
			echo "<option value='UUAK'>Usługa Usuwania Awarii Krytycznych (UUAK)</option>\n";
			echo "</select>";
			
			echo "&nbsp;&nbsp;&nbsp;&nbsp;	";
			echo "Kompleksowa osbługa: ";			
			
			echo "<select class=wymagane name=uko onkeypress='return handleEnter(this, event);'>";
			echo "<option value=0>NIE</option>\n";
			echo "<option value=1 SELECTED>TAK</option>\n";
			echo "</select>";
			
		_td();
	_tr();
	
	tr_();
		td("120;r;Przypisanie jednostki do załącznika");
		td_(";;");
			echo "<select name=updz onkeypress='return handleEnter(this, event);'>";
			
			echo "<option value=''>brak</option>\n";
			if ($obszar=='Szczecin') {
				echo "<option value='1Szczecin'>1Szczecin</option>\n";			
				echo "<option value='3Szczecin'>3Szczecin</option>\n";
				echo "<option value='4Szczecin'>4Szczecin</option>\n";
				echo "<option value='5Szczecin'>5Szczecin</option>\n";
			}
			if ($obszar=='Łódź') {
				echo "<option value='1Łódź'>1Łódź</option>\n";
				echo "<option value='2Łódź' SELECTED>2Łódź</option>\n";
				echo "<option value='3Łódź'>3Łódź</option>\n";
				echo "<option value='5Łódź'>5Łódź</option>\n";
			}
			echo "</select>";
			
		_td();
	_tr();
	tr_();
		td("120;rt;Podlega pod umowę<br /><br /><font color=red>Użyj Ctrl, <br />aby zaznaczyć więcej pozycji</font>");
		td_(";;");
			$query = "SELECT umowa_id,umowa_nr,umowa_opis FROM $dbname.serwis_umowy ";	
			if ($es_m!=1) $query=$query."WHERE belongs_to=$es_filia"; 
			$query.= " ORDER BY umowa_nr";
			if($result = mysql_query($query)) { 
			 	if($success = mysql_num_rows($result) > 0) { 
					echo "<select multiple=multiple name=umowa_id[] id=umowa_id class=wymagane onkeypress='return handleEnter(this, event);'>\n";
					echo "<option value=0>Wybierz umowę z listy...</option>\n";
					while (list($uid,$unr,$uopis)=mysql_fetch_array($result)) { 
						echo "<option value=$uid>$uopis | $unr</option>\n"; 
					} 
			  		echo "</select>\n"; 
				}
			}		
		_td();
	_tr();
	tbl_empty_row();

	if (($es_nr==$KierownikId) || ($is_dyrektor==1)) {
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
					echo "<td width=220><input type=text name=PN_start id=PN_start maxlength=5 size=4 value='' onDblClick=\"this.value='';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=PN_start_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=PN_start_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
					echo "<td width=220><input type=text name=PN_stop id=PN_stop maxlength=5 size=4 value='' onDblClick=\"this.value='';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=PN_stop_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=PN_stop_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
				echo "</tr>";

				echo "<tr>";
					echo "<td>Wtorek:</td>";					
					echo "<td width=220><input type=text name=WT_start id=WT_start maxlength=5 size=4 value='' onDblClick=\"this.value='';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=WT_start_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=WT_start_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
					echo "<td width=220><input type=text name=WT_stop id=WT_stop maxlength=5 size=4 value='' onDblClick=\"this.value='';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=WT_stop_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=WT_stop_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
				echo "</tr>";

				echo "<tr>";
					echo "<td>Środa:</td>";					
					echo "<td width=220><input type=text name=SR_start id=SR_start maxlength=5 size=4 value='' onDblClick=\"this.value='';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=SR_start_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=SR_start_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
					echo "<td width=220><input type=text name=SR_stop id=SR_stop maxlength=5 size=4 value='' onDblClick=\"this.value='';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=SR_stop_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=SR_stop_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
				echo "</tr>";

				echo "<tr>";
					echo "<td>Czwartek:</td>";					
					echo "<td width=220><input type=text name=CZ_start id=CZ_start maxlength=5 size=4 value='' onDblClick=\"this.value='';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=CZ_start_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=CZ_start_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
					echo "<td width=220><input type=text name=CZ_stop id=CZ_stop maxlength=5 size=4 value='' onDblClick=\"this.value='';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=CZ_stop_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=CZ_stop_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
				echo "</tr>";

				echo "<tr>";
					echo "<td>Piątek:</td>";					
					echo "<td width=220><input type=text name=PT_start id=PT_start maxlength=5 size=4 value='' onDblClick=\"this.value='';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=PT_start_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=PT_start_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
					echo "<td width=220><input type=text name=PT_stop id=PT_stop maxlength=5 size=4 value='' onDblClick=\"this.value='';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=PT_stop_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=PT_stop_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
				echo "</tr>";

				echo "<tr>";
					echo "<td>Sobota:</td>";					
					echo "<td width=220><input type=text name=SO_start id=SO_start maxlength=5 size=4 value='' onDblClick=\"this.value='';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=SO_start_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=SO_start_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
					echo "<td width=220><input type=text name=SO_stop id=SO_stop maxlength=5 size=4 value='' onDblClick=\"this.value='';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=SO_stop_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=SO_stop_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
				echo "</tr>";

				echo "<tr>";
					echo "<td>Niedziela:</td>";					
					echo "<td width=220><input type=text name=NI_start id=NI_start maxlength=5 size=4 value='' onDblClick=\"this.value='';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=NI_start_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=NI_start_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
					echo "<td width=220><input type=text name=NI_stop id=NI_stop maxlength=5 size=4 value='' onDblClick=\"this.value='';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
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
					echo "<td>";
						echo "Obowiązuje od (MM-DD)<br />czerwiec - wrzesień<br />np. 06-01 &nbsp;&nbsp;&nbsp;&nbsp; 09-30";
					echo "</td>";
					echo "<td>";
						echo "<input type=text name=gp_alt_od id=gp_alt_od maxlength=5 size=4 value='' onBlur=\"okres_ok(this.value, this.name); \" />";
						echo "<span id=gp_alt_od_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisany okres</font></span>";
						echo "<span id=gp_alt_od_span_ok style='display:none'><font color=green>&nbsp;Okres wpisany poprawnie</font></span>";						
					echo "</td>";
					echo "<td>";
						echo "<input type=text name=gp_alt_do id=gp_alt_do maxlength=5 size=5 value='' onBlur=\"okres_ok(this.value, this.name); \" />";
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
					echo "<td width=220><input type=text name=PNa_start id=PNa_start maxlength=5 size=4 value='' onDblClick=\"this.value='';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=PNa_start_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=PNa_start_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
					echo "<td width=220><input type=text name=PNa_stop id=PNa_stop maxlength=5 size=4 value='' onDblClick=\"this.value='';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=PNa_stop_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=PNa_stop_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
				echo "</tr>";

				echo "<tr>";
					echo "<td>Wtorek:</td>";					
					echo "<td width=220><input type=text name=WTa_start id=WTa_start maxlength=5 size=4 value='' onDblClick=\"this.value='';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=WTa_start_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=WTa_start_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
					echo "<td width=220><input type=text name=WTa_stop id=WTa_stop maxlength=5 size=4 value='' onDblClick=\"this.value='';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=WTa_stop_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=WTa_stop_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
				echo "</tr>";

				echo "<tr>";
					echo "<td>Środa:</td>";					
					echo "<td width=220><input type=text name=SRa_start id=SRa_start maxlength=5 size=4 value='' onDblClick=\"this.value='';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=SRa_start_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=SRa_start_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
					echo "<td width=220><input type=text name=SRa_stop id=SRa_stop maxlength=5 size=4 value='' onDblClick=\"this.value='';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=SRa_stop_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=SRa_stop_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
				echo "</tr>";

				echo "<tr>";
					echo "<td>Czwartek:</td>";					
					echo "<td width=220><input type=text name=CZa_start id=CZa_start maxlength=5 size=4 value='' onDblClick=\"this.value='';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=CZa_start_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=CZa_start_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
					echo "<td width=220><input type=text name=CZa_stop id=CZa_stop maxlength=5 size=4 value='' onDblClick=\"this.value='';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=CZa_stop_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=CZa_stop_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
				echo "</tr>";

				echo "<tr>";
					echo "<td>Piątek:</td>";					
					echo "<td width=220><input type=text name=PTa_start id=PTa_start maxlength=5 size=4 value='' onDblClick=\"this.value='';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=PTa_start_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=PTa_start_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
					echo "<td width=220><input type=text name=PTa_stop id=PTa_stop maxlength=5 size=4 value='' onDblClick=\"this.value='';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=PTa_stop_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=PTa_stop_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
				echo "</tr>";

				echo "<tr>";
					echo "<td>Sobota:</td>";					
					echo "<td width=220><input type=text name=SOa_start id=SOa_start maxlength=5 size=4 value='' onDblClick=\"this.value='';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=SOa_start_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=SOa_start_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
					echo "<td width=220><input type=text name=SOa_stop id=SOa_stop maxlength=5 size=4 value='' onDblClick=\"this.value='';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=SOa_stop_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=SOa_stop_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
				echo "</tr>";

				echo "<tr>";
					echo "<td>Niedziela:</td>";					
					echo "<td width=220><input type=text name=NIa_start id=NIa_start maxlength=5 size=4 value='' onDblClick=\"this.value='';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=NIa_start_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=NIa_start_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
					echo "<td width=220><input type=text name=NIa_stop id=NIa_stop maxlength=5 size=4 value='' onDblClick=\"this.value='';\" title='Dwuklik wpisuje wartość domyślną' onKeyUp=\"DopiszDwukropek1(this.id);\" onBlur=\"sprawdzWszystkieGodziny(); \"/>";
					
					echo "<span id=NIa_stop_span_error style='display:none'><font color=red>&nbsp;Błędnie wpisana godzina</font></span>";
					echo "<span id=NIa_stop_span_ok style='display:none'><font color=green>&nbsp;Godzina wpisana poprawnie</font></span>";
					
					echo "</td>";
				echo "</tr>";
			echo "</table>";
		_td();
	_tr();
	
	}
	
tbl_empty_row();
endtable();
startbuttonsarea("right");
addbuttons("zapisz","anuluj");
endbuttonsarea();
_form();
?>

<script language="JavaScript">
var cal1 = new calendar1(document.forms['addu'].elements['dok']);
	cal1.year_scroll = true;
	cal1.time_comp = false;
</script>
<?php } ?>
</body>
</html>