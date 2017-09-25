<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) { 

	$_POST=sanitize($_POST);
	$result44 = mysql_query("SELECT rola_nazwa FROM $dbname.serwis_slownik_rola WHERE (rola_id=$_POST[ts])", $conn) or die($k_b);
	list($rola_typ_nazwa)=mysql_fetch_array($result44);
	$result55 = mysql_query("SELECT up_nazwa FROM $dbname.serwis_komorki WHERE (up_id=$_POST[ls])", $conn) or die($k_b);
	list($lok_up_nazwa)=mysql_fetch_array($result55);

	if (($_POST['ls']!='')) {
		$dddd = Date('Y-m-d H:i:s');
		$endpoint1 = $_POST[ne];
		if ($_POST[ipend]=='on') { $endpoint1 = $_POST[nazwak];}

		$k__procesor=$_POST[konf_proc];
		$k__pamiec=$_POST[konf_ram];
		$k__dysk=$_POST[konf_hdd];

		$k__procesor = str_replace(',','.',$_POST[konf_proc]);
		$k__pamiec = str_replace(',','.',$_POST[konf_ram]);		
		$k__dysk = str_replace(',','.',$_POST[konf_hdd]);	

		if (($k__procesor=='') && ($k__pamiec=='') && ($k_dysk=='')) { 
			$konf_opis='';
		} else	
			$konf_opis='Procesor '.$k__procesor.'GHz, '.$k__pamiec.'MB RAM, '.$k__dysk.'GB HDD';

		if ($_POST[os]=='') { $ook=$_POST[ook1]; } else $ook=$_POST[os];
		$oom=$_POST[om];
		$ood=$_POST[od];
		
		if ($_POST[edittype]!='admin') { 
			$sql_t = "UPDATE $dbname.serwis_ewidencja SET ewidencja_nr_pokoju='$_POST[nrp]',ewidencja_zestaw_ni='$_POST[niz]',ewidencja_komputer_nazwa='$_POST[nazwak]',ewidencja_komputer_ip='$_POST[aip]',ewidencja_komputer_endpoint='$endpoint1',ewidencja_drukarka_opis='$ood',ewidencja_drukarka_sn='$_POST[nrsd]', ewidencja_drukarka_ni='$_POST[nid]',ewidencja_uwagi='".nl2br($_POST[uwagi])."',belongs_to='$_POST[ubelongs_to]',ewidencja_modyfikacja_date='$dddd',ewidencja_modyfikacja_user='$currentuser', ewidencja_gwarancja_do = '$_POST[gwarancja]', ewidencja_gwarancja_kto='$_POST[firmaserwisowa]', ewidencja_drukarka_powiaz_z='$_POST[drukpowiaz1]' WHERE (ewidencja_id=$_POST[id]) LIMIT 1";
} else 
	 { 
		$sql_t = "UPDATE $dbname.serwis_ewidencja SET ewidencja_typ='$_POST[ts]', ewidencja_typ_nazwa='$rola_typ_nazwa',  ewidencja_up_id='$_POST[ls]', ewidencja_up_nazwa='$lok_up_nazwa', ewidencja_uzytkownik='$_POST[us]',ewidencja_nr_pokoju='$_POST[nrp]',ewidencja_zestaw_ni='$_POST[niz]',ewidencja_komputer_nazwa='$_POST[nazwak]',ewidencja_komputer_opis='$ook',ewidencja_komputer_sn='$_POST[nrsk]',ewidencja_komputer_ip='$_POST[aip]',ewidencja_komputer_endpoint='$endpoint1',ewidencja_monitor_opis='$oom',ewidencja_monitor_sn='$_POST[nrsm]',ewidencja_drukarka_opis='$ood',ewidencja_drukarka_sn='$_POST[nrsd]', ewidencja_drukarka_ni='$_POST[nid]',ewidencja_uwagi='".nl2br($_POST[uwagi])."',belongs_to='$_POST[ubelongs_to]',ewidencja_modyfikacja_date='$dddd',ewidencja_modyfikacja_user='$currentuser', ewidencja_konfiguracja='$_POST[skk]',k_procesor='$k__procesor', k_pamiec='$k__pamiec', k_dysk='$k__dysk', ewidencja_konfiguracja='$konf_opis', ewidencja_gwarancja_do = '$_POST[gwarancja]', ewidencja_gwarancja_kto='$_POST[firmaserwisowa]', ewidencja_drukarka_powiaz_z='$_POST[drukpowiaz1]' WHERE (ewidencja_id=$_POST[id]) LIMIT 1";
}
	$isunique=true;
/*	if (($rola_typ_nazwa=='Komputer') || ($rola_typ_nazwa=='Serwer') || ($rola_typ_nazwa=='Notebook')) {
		$sql_sprawdz_unikalnosc = "SELECT ewidencja_id FROM $dbname.serwis_ewidencja WHERE ((ewidencja_komputer_sn='$_POST[nrsk]') and (ewidencja_komputer_sn<>''))";
		$wynik_unik = mysql_query($sql_sprawdz_unikalnosc,$conn) or die($k_b);
		$isunique = (mysql_num_rows($wynik_unik)==0);
	}
	if ($rola_typ_nazwa=='Drukarka') {
		$sql_sprawdz_unikalnosc = "SELECT ewidencja_id FROM $dbname.serwis_ewidencja WHERE ((ewidencja_drukarka_sn='$_POST[nrsd]') and (ewidencja_drukarka_sn<>''))";
		$wynik_unik = mysql_query($sql_sprawdz_unikalnosc,$conn) or die($k_b);
		$isunique = (mysql_num_rows($wynik_unik)==0);	
	}
*/
	if ($isunique) {
		if (mysql_query($sql_t, $conn)) { 
			okheader("Pomyślnie zapisano zmiany do bazy");
			startbuttonsarea("right");		
			addlinkbutton("'Edytuj ponownie'","e_ewidencja.php?id=$_POST[id]&edittype=$edittype");
			addclosewithreloadbutton("Zamknij");
			endbuttonsarea();
		} else {
			  ?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
				}
	} else {
		?>
		<script>
			alert('Istnieje już w bazie sprzęt o takim numerze seryjnym');
			window.location='e_ewidencja.php?id=<?php echo $_POST[id]; ?>&edittype=<?php echo $edittype;?>';
		</script>
		<?php	
	}
	}
} else {
pageheader("Edycja sprzętu w bazie ewidencji",1);
$sql = "SELECT * FROM $dbname.serwis_ewidencja WHERE (ewidencja_id=$id) LIMIT 1";

$result = mysql_query($sql, $conn) or die($k_b);
while ($dane = mysql_fetch_array($result)) {
	$eid 		= $dane['ewidencja_id'];
	$etyp_id	= $dane['ewidencja_typ'];
	$etyp_n		= $dane['ewidencja_typ_nazwa'];
	$eup_id		= $dane['ewidencja_up_id'];
	$euser		= $dane['ewidencja_uzytkownik'];		
	$enrpok		= $dane['ewidencja_nr_pokoju'];
	$enizest	= $dane['ewidencja_zestaw_ni'];
	$eknazwa	= $dane['ewidencja_komputer_nazwa'];
	$ekopis		= $dane['ewidencja_komputer_opis'];
	$eksn		= $dane['ewidencja_komputer_sn'];
	$ekip		= $dane['ewidencja_komputer_ip'];
	$eke		= $dane['ewidencja_komputer_endpoint'];
	$emo		= $dane['ewidencja_monitor_opis'];
	$emsn		= $dane['ewidencja_monitor_sn'];
	$edo		= $dane['ewidencja_drukarka_opis'];
	$edsn		= $dane['ewidencja_drukarka_sn'];
	$edni		= $dane['ewidencja_drukarka_ni'];
	$eu			= $dane['ewidencja_uwagi'];
	$es			= $dane['ewidencja_status'];
	$eo_id		= $dane['ewidencja_oprogramowanie'];
	$emoduser 	= $dane['ewidencja_modyfikacja_user'];
	$emoddata	= $dane['ewidencja_modyfikacja_date'];
	$ekonf		= $dane['ewidencja_konfiguracja'];
	$k__procesor= $dane['k_procesor'];
	$k__pamiec	= $dane['k_pamiec'];
	$k__dysk	= $dane['k_dysk'];
	$egwarancja	= $dane['ewidencja_gwarancja_do'];		
	$egwarancjakto= $dane['ewidencja_gwarancja_kto'];		
	$drukarkapow= $dane['ewidencja_drukarka_powiaz_z'];
}
	
starttable();
echo "<form name=editewid action=$PHP_SELF method=POST>";
echo "<input tabindex=-1 type=hidden name=ook1 value='$ekopis'>";
echo "<input tabindex=-1 type=hidden name=oom1 value='$emo'>";
echo "<input tabindex=-1 type=hidden name=ood1 value='$edo'>";

echo "<div class=hideme>";
startbuttonsarea("right");
addbuttons("zapisz","anuluj");
endbuttonsarea();
echo "</div>";

tr_();
	th_colspan(2,'l','&nbsp;Typ sprzętu');
	_th();
_tr();
tbl_empty_row(2);
tr_();
	td_("160;r;Typ sprzętu");
	_td();
	td_colspan(1,"l","");
		if ($edittype!='admin') { 
			$result44 = mysql_query("SELECT rola_id,rola_nazwa FROM $dbname.serwis_slownik_rola WHERE ((rola_do_ewidencji=1) and (rola_id=$etyp_id)) LIMIT 1", $conn) or die($k_b);
			list($temp_id,$temp_nazwa,$temp_nazwa_rola)=mysql_fetch_array($result44);
			$temp_nazwa_rola = $temp_nazwa;			
			$typ_sprzetu=$temp_nazwa;
			$typ_id=$temp_id;
			echo "<b>$temp_nazwa_rola</b>";
			echo "<input tabindex=-1 type=hidden name=ts value=$etyp_id>";
			
		} else {
			$result44 = mysql_query("SELECT rola_id,rola_nazwa FROM $dbname.serwis_slownik_rola WHERE rola_do_ewidencji=1", $conn) or die($k_b);
			echo "<select disabled class=wymagane name=ts1>\n"; 					 				
			while (list($temp_id,$temp_nazwa)=mysql_fetch_array($result44)) {
				echo "<option value='$temp_id'";
				if ($temp_id==$etyp_id) echo " SELECTED";
				echo ">$temp_nazwa</option>\n"; 
				if ($temp_id==$etyp_id) { 
					$typ_sprzetu=$temp_nazwa;
					$typ_id=$temp_id;
				}
			}
			echo "</select>\n"; 
			echo "<input tabindex=-1 type=hidden name=ts value=$etyp_id>";
			echo "<input tabindex=-1 type=hidden name=typ_sprzet_n value='$etyp_n'>";
			if ($edittype!='admin') { echo "<input tabindex=-1 type=hidden name=ts value=$etyp_id>"; } 
		}
	_td();
_tr();
tbl_empty_row(2);
tr_();
	th_colspan(2,'l','&nbsp;Informacje o lokalizacji sprzętu');
	_th();
_tr();
tr_();
	td_colspan(2,'c');
	_td();
_tr();
tr_();
	td("160;r;Lokalizacja sprzętu");	
		if ($edittype!='admin') { 		
			$result44 = mysql_query("SELECT serwis_komorki.up_id, serwis_komorki.up_nazwa, serwis_piony.pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) and (serwis_komorki.up_id=$eup_id) ORDER BY serwis_piony.pion_nazwa,up_nazwa", $conn) or die($k_b);
			list($temp_id1,$temp_nazwa1,$temp_pion)=mysql_fetch_array($result44);
		td_(";;");
			echo "<b>$temp_pion $temp_nazwa1</b>";
		_td();
			echo "<input tabindex=-1 type=hidden name=ls value=$eup_id>";
		} else {
			td_(";;");		
				//$result44 = mysql_query("SELECT up_id,up_nazwa FROM $dbname.serwis_komorki WHERE (belongs_to=$es_filia) and (up_active=1) ORDER BY up_nazwa", $conn) or die($k_b);
				$result44 = mysql_query("SELECT serwis_komorki.up_id, serwis_komorki.up_nazwa, serwis_piony.pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) ORDER BY serwis_piony.pion_nazwa,up_nazwa", $conn) or die($k_b);
				
				echo "<select class=wymagane name=ls onkeypress='return handleEnter(this, event);'>\n";
				echo "<option value=''>Wybierz z listy...</option>";
				while (list($temp_id,$temp_nazwa,$temp_pion)=mysql_fetch_array($result44)) {
					echo "<option value='$temp_id'";
					if ($temp_id==$eup_id) echo " SELECTED";
					echo ">$temp_pion $temp_nazwa</option>\n";
				}
				echo "</select>\n"; 
				if ($edittype!='admin') echo "<input tabindex=-1 type=hidden name=ls value=$eup_id>";
			_td();
		}
_tr();

if (($edittype!='admin') && ($edittype!='move')) {	
	tr_();
		td("160;r;Użytkownik sprzętu|;l;<b>$euser</b>");
		echo "<input type=hidden tabindex=-1 name=us value='$euser'>";
	_tr();
} else {	
	tr_();
		td("160;r;Użytkownik sprzętu");
		td_(";l");
			echo "<input size=20 maxlength=30 type=text name=us value='$euser' onkeypress='return handleEnter(this, event);'>";
		_td();
	_tr();
}
tr_();
	td("160;r;Nr pokoju");
	td_(";l");
		echo "<input size=10 maxlength=15 type=text name=nrp value='$enrpok' onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();
if (($typ_sprzetu=='Komputer') || ($typ_sprzetu=='Serwer') || ($typ_sprzetu=='Notebook')) {
		tbl_empty_row(2);
		tr_();
			th_colspan(2,'l','&nbsp;Informacje o zestawie komputerowym');
			_th();
		_tr();
		tr_();
			td_colspan(2,'c');
			_td();
		_tr();
		tr_();
		tr_();
			td("160;r;Nr inwentarzowy zestawu");
			td_(";l");
				echo "<input size=20 maxlength=20 type=text name=niz value='$enizest' onkeypress='return handleEnter(this, event);'>";
			_td();
		_tr();	

if (($typ_sprzetu=='Komputer') || ($typ_sprzetu=='Serwer'))	{
		tr_();
			th_(";r;Komputer / Serwer",$es_prawa);
			_th();
			td_(";;");
			_td();
		_tr();
} else {
		tr_();
			th_(";r;Notebook",$es_prawa);
			_th();
			td_(";;");
			_td();			
		_tr();
}
tr_();
		td("160;r;Nazwa komputera");
		td_(";;");
			echo "<input size=30 maxlength=30 type=text name=nazwak value='$eknazwa' onkeypress='return handleEnter(this, event);'>";
		_td();
_tr();
tr_();
		td("160;r;Opis komputera");
		td_(";;");
		if ($edittype!='admin') { 
			echo "<b>$ekopis</b>"; 
			echo "<input type=hidden tabindex=-1 name=os value='$ekopis'>";
		} else {
			$result44 = mysql_query("SELECT konfiguracja_id,konfiguracja_nazwa,konfiguracja_opis FROM $dbname.serwis_slownik_konfiguracja ORDER BY konfiguracja_opis", $conn) or die($k_b);
			echo "<select name=os onkeypress='return handleEnter(this, event);'>\n";
			echo "<option value=''>Wybierz z listy</option>\n";				
			while (list($temp_id,$temp_nazwa,$temp_opis)=mysql_fetch_array($result44)) {
				echo "<option ";
				if ($temp_opis==$ekopis) { echo "SELECTED "; }
				echo "value='$temp_opis'>$temp_opis</option>\n"; 
			}
			echo "</select>\n";
		}
		_td();
_tr();
tr_();
		td("160;r;Nr seryjny komputera");
		td_(";;");
			if (($edittype!='admin') && ($edittype!='change')) { 
				echo "<b>$eksn</b>"; 
				echo "<input type=hidden tabindex=-1 name=nrsk value='$eksn'>";
			} else {
				echo "<input size=20 maxlength=30 type=text name=nrsk ";	
				echo "value='$eksn' onkeypress='return handleEnter(this, event);'>";
			}
		_td();
_tr();
tr_();
		td("160;r;Adres IP");
		td_(";;");
			echo "<input size=15 maxlength=15 type=text name=aip value='$ekip' onkeypress='return handleEnter(this, event);'>";
			echo "&nbsp;&nbsp;Endpoint&nbsp;";
			echo "<input size=15 maxlength=30 type=text name=ne value='$eke'><input class=border0 type=checkbox name=ipend ";
			if ($eknazwa==$eke) echo "checked";
			echo " onkeypress='return handleEnter(this, event);'>";
			echo "<span style='cursor:hand' onclick=labelClick(document.editewid.ipend)>endpoint = nazwa komputera</span>";
		_td();
_tr();
tr_();
		td("160;r;Konfiguracja sprzętu");
		td_(";;");
			if (($edittype!='admin') && ($edittype!='change')) {
				echo "Procesor <b>$k__procesor GHz</b>, pamięć <b>$k__pamiec MB RAM</b>, dysk <b>$k__dysk GB</b>";
				echo "<input type=hidden tabindex=-1 name=konf_proc value='$k__procesor'>";
				echo "<input type=hidden tabindex=-1 name=konf_ram value='$k__pamiec'>";
				echo "<input type=hidden tabindex=-1 name=konf_hdd value='$k__dysk'>";
			} else {
				echo "Procesor (GHz) <input size=5 maxlength=5 type=text name=konf_proc "; 
				echo "value='$k__procesor' onkeypress='return handleEnter(this, event);'>";
				echo "Pamięć (MB) <input size=5 maxlength=5 type=text name=konf_ram ";
				if (($edittype!='admin') && ($edittype!='change')) echo "disabled ";
				echo "value='$k__pamiec' onkeypress='return handleEnter(this, event);'>";
				echo "Dysk (GB) <input size=5 maxlength=5 type=text name=konf_hdd ";
				if (($edittype!='admin') && ($edittype!='change')) echo "disabled ";
				echo "value='$k__dysk' onkeypress='return handleEnter(this, event);'>";
			}
		_td();
_tr();

if ($typ_sprzetu!='Notebook') {
		tr_();
			th_(";r;Monitor",$es_prawa);
			_th();
			td_(";;");
			_td();
		_tr();
		tr_();
			td(";r;Model monitora");
			if (($edittype!='admin') && ($edittype!='change')) {
				td(";;<b>".$emo."</b>");
				echo "<input type=hidden tabindex=-1 name=om value='$emo'>";
				_tr();
				tr_();
					td(";r;Nr seryjny monitora");
					td_(";;");
						echo "<b>$emsn</b>";
						echo "<input type=hidden tabindex=-1 name=nrsm value='$emsn'>";
					_td();
				_tr();
			} else {
				td_(";;");
					$result444 = mysql_query("SELECT monitor_id,monitor_nazwa,monitor_opis FROM $dbname.serwis_slownik_monitor ORDER BY monitor_nazwa",$conn) or die($k_b);
					echo "<select name=om onkeypress='return handleEnter(this, event);'>\n"; 
					echo "<option value=''>Wybierz z listy</option>\n";				
					echo "<option value=''>brak</option>\n";				
					while (list($temp_id,$temp_nazwa,$temp_opis)=mysql_fetch_array($result444)) {
						echo "<option ";
						if ($temp_nazwa==$emo) echo "SELECTED ";
						echo "value='$temp_nazwa'>$temp_nazwa</option>\n"; 
					}
					echo "</select>\n"; 
				_td();
			_tr();
			tr_();
				td(";r;Nr seryjny monitora");
				td_(";;");
					echo "<input size=20 maxlength=30 type=text name=nrsm value='$emsn' onkeypress='return handleEnter(this, event);'>";
				_td();
			_tr();
			}
	}
}

if (($typ_id==30) || ($typ_id==29) || ($typ_id==11) || ($typ_id==10) || ($typ_id==17)) {
tr_();
		td("160;r;Nr inwentarzowy sprzętu");
		td_(";l");
		
			if (($edittype!='admin') && ($edittype!='change')) { 
				echo "<b>$enizest</b>"; 
				echo "<input type=hidden tabindex=-1 name=niz value='$enizest'>";
			} else {
				echo "<input size=20 maxlength=20 type=text name=niz ";	
				echo "value='$enizest' onkeypress='return handleEnter(this, event);'>";
			}

		//echo "<input size=20 maxlength=20 type=text name=niz value='$enizest' onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();	
tr_();
		td("160;r;Nazwa");
		td_(";;");
		
			if (($edittype!='admin') && ($edittype!='change')) { 
				echo "<b>$eknazwa</b>"; 
				echo "<input type=hidden tabindex=-1 name=nazwak value='$eknazwa'>";
			} else {
				echo "<input size=20 maxlength=20 type=text name=nazwak ";	
				echo "value='$eknazwa' onkeypress='return handleEnter(this, event);'>";
			}
			
			//echo "<input size=30 maxlength=30 type=text name=nazwak value='$eknazwa' onkeypress='return handleEnter(this, event);'>";
		_td();
_tr();
tr_();
		td("160;r;Nr seryjny");
		td_(";;");
			if (($edittype!='admin') && ($edittype!='change')) { 
				echo "<b>$eksn</b>"; 
				echo "<input type=hidden tabindex=-1 name=nrsk value='$eksn'>";
			} else {
				echo "<input size=20 maxlength=30 type=text name=nrsk ";	
				echo "value='$eksn' onkeypress='return handleEnter(this, event);'>";
			}
		_td();
_tr();
}

if ($typ_sprzetu=='Czytnik') {
	tr_();
			td("160;r;Nr inwentarzowy sprzętu");
			td_(";l");
			
				if (($edittype!='admin') && ($edittype!='change')) { 
					echo "<b>$enizest</b>"; 
					echo "<input type=hidden tabindex=-1 name=niz value='$enizest'>";
				} else {
					echo "<input size=20 maxlength=20 type=text name=niz ";	
					echo "value='$enizest' onkeypress='return handleEnter(this, event);'>";
				}

			//echo "<input size=20 maxlength=20 type=text name=niz value='$enizest' onkeypress='return handleEnter(this, event);'>";
		_td();
	_tr();	

	tr_();
		td("160;r;Model czytnika");
		td_(";;");
			$result4444 = mysql_query("SELECT czytnik_id,czytnik_nazwa,czytnik_opis FROM $dbname.serwis_slownik_czytnik ORDER BY czytnik_nazwa", $conn) or die($k_b);
			if ($edittype=='admin') { 			
				echo "<select name=nazwak onkeypress='return handleEnter(this, event);'>\n"; 
				echo "<option value=''>Wybierz z listy</option>\n";				
				//echo "<option value=''>brak</option>\n";	
				while (list($temp_id,$temp_nazwa,$temp_opis)=mysql_fetch_array($result4444)) {
					echo "<option ";
					if ($temp_nazwa==$eknazwa) echo "SELECTED ";
					echo "value='$temp_nazwa'>$temp_nazwa</option>\n"; 
				}
				echo "</select>\n"; 
			} else {
				echo "<b>$eknazwa</b>";
				echo "<input type=hidden tabindex=-1 name=nazwak value='$eknazwa'>";
			}
		_td();
	_tr();
	
	tr_();
		td("160;r;Nr seryjny");
		td_(";;");
			if (($edittype!='admin') && ($edittype!='change')) { 
				echo "<b>$eksn</b>"; 
				echo "<input type=hidden tabindex=-1 name=nrsk value='$eksn'>";
			} else {
				echo "<input size=20 maxlength=30 type=text name=nrsk ";	
				echo "value='$eksn' onkeypress='return handleEnter(this, event);'>";
			}
		_td();
_tr();	
}

if ($typ_sprzetu=='Drukarka') {
	tbl_empty_row(2);
	tr_();
		th_colspan(2,'l','&nbsp;Informacje o urządzeniach peryferyjnych');
		_th();
	_tr();
	tr_();
		td_colspan(2,'c');
		_td();
	_tr();
	tr_();
		td("160;r;Model drukarki");
		td_(";;");
			$result4444 = mysql_query("SELECT drukarka_id,drukarka_nazwa,drukarka_opis FROM $dbname.serwis_slownik_drukarka ORDER BY drukarka_nazwa", $conn) or die($k_b);
			if ($edittype=='admin') { 
				echo "<select name=od onkeypress='return handleEnter(this, event);'>\n"; 
				echo "<option value=''>Wybierz z listy</option>\n";				
				echo "<option value=''>brak</option>\n";	
				while (list($temp_id,$temp_nazwa,$temp_opis)=mysql_fetch_array($result4444)) {
					echo "<option ";
					if ($temp_nazwa==$edo) echo "SELECTED ";
					echo "value='$temp_nazwa'>$temp_nazwa</option>\n"; 
				}
				echo "</select>\n"; 
			} else {
				echo "<b>$edo</b>";
				echo "<input type=hidden tabindex=-1 name=od value='$edo'>";
			}
		_td();
	_tr();
	tr_();
		td(";r;Nr seryjny drukarki");
		td_(";;");
			if ($edittype=='admin') { 
				echo "<input size=20 maxlength=30 type=text name=nrsd value='$edsn' onkeypress='return handleEnter(this, event);'>";
			} else {
				echo "<b>$edsn</b>";
				echo "<input type=hidden tabindex=-1 name=nrsd value='$edsn'>";
			}
		_td();
	_tr();
	tr_();
		td("160;r;Nr inwentarzowy drukarki");
		td_(";;");
			if ($edittype=='admin') {		
				echo "<input size=20 maxlength=20 type=text name=nid value='$edni' onkeypress='return handleEnter(this, event);'>";
			} else {
				echo "<b>$edni</b>";
				echo "<input type=hidden tabindex=-1 name=nid value='$edni'>";
			}
		_td();
	_tr();
	tr_();
	tr_();
		td("160;r;Adres IP drukarki");
		td_(";;");
			echo "<input size=20 maxlength=15 type=text name=aip value='$ekip' onkeypress='return handleEnter(this, event);'>";
		_td();
	_tr();
	tr_();
		td("160;r;Powiązana z");
		td_(";;");
			$sql5="SELECT ewidencja_id,ewidencja_drukarka_powiaz_z,ewidencja_komputer_nazwa,ewidencja_komputer_opis,ewidencja_komputer_sn,ewidencja_komputer_ip FROM $dbname.serwis_ewidencja WHERE ((ewidencja_up_id=$eup_id) and ((ewidencja_typ_nazwa='Komputer') or (ewidencja_typ_nazwa='Serwer') or (ewidencja_typ_nazwa='Notebook'))) ORDER BY ewidencja_komputer_nazwa";
			$result5 = mysql_query($sql5, $conn) or die($k_b);
			echo "<select name=drukpowiaz1 onkeypress='return handleEnter(this, event);'>\n"; 
			echo "<option ";
			if ($drukarkapow==0) echo "SELECTED ";
			echo "value=0>brak powiązania</option>\n";
			while ($newArray5 = mysql_fetch_array($result5)) {
				$temp_id1	= $newArray5['ewidencja_id'];
				$temp_1		= $newArray5['ewidencja_komputer_nazwa'];
				$temp_2		= $newArray5['ewidencja_komputer_opis'];
				$temp_3		= $newArray5['ewidencja_komputer_sn'];
				$temp_4		= $newArray5['ewidencja_komputer_ip'];
				$temp_pow	= $newArray5['ewidencja_drukarka_powiaz_z'];
				echo "<option ";
				if ($drukarkapow!=0) { 
					if ($temp_id1==$drukarkapow) { 
						echo "SELECTED "; 
					}
				}
				echo "value=$temp_id1>$temp_2, $temp_3, $temp_1, $temp_4</option>\n"; 
			}
			echo "</select>\n";
		_td();
	_tr();
}
tbl_empty_row(2);
tr_();
	th_colspan(2,'l','&nbsp;Informacje dodatkowe');
	_th();
_tr();
tr_();
	td_colspan(2,'c');
	_td();
_tr();
tr_();		
	td("160;rt;Uwagi");
	td_(";;");
		echo "<textarea name=uwagi cols=50 rows=2>".br2nl($eu)."</textarea>";
	_td();
_tr();
tr_();	
	td("160;r;Gwarancja do dnia");
	td_(";;");
		echo "<input ";
		if ($edittype!='admin') { 
			echo "disabled ";
		}
		if ($egwarancja=='0000-00-00') $egwarancja='';
		echo "size=10 maxlength=10 type=text id=gwarancja name=gwarancja value='$egwarancja' onkeypress='return handleEnter(this, event);' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\">&nbsp;";
		if ($edittype=='admin') { 
			echo "<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img src=img/cal.gif width=16 height=16 border=0></a>";
			if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('gwarancja').value='".Date('Y-m-d')."'; return false;\">";
		}
	_td();
_tr();
tr_();
	td("160;r;Sprzęt serwisowany przez");
	td_(";;");
		$result5 = mysql_query("SELECT fz_id,fz_nazwa FROM $dbname.serwis_fz WHERE (fz_is_fs='on') ORDER BY fz_nazwa ASC", $conn) or die($k_b);
		echo "<select ";
		echo "name=firmaserwisowa onkeypress='return handleEnter(this, event);'>\n"; 
		echo "<option ";
		if ($egwarancjakto==0) echo "SELECTED ";
		echo "value=0>Wybierz firmę serwisową...</option>\n";
		while (list($temp_id1,$temp_1)=mysql_fetch_array($result5)) {
			echo "<option ";
			if ($egwarancjakto!=0) { 
				if ($temp_id1==$egwarancjakto) { echo "SELECTED "; }
			}
			echo "value='$temp_id1'>$temp_1</option>\n"; 
		}
		echo "</select>\n"; 
	_td();
_tr();
tr_();
	td("160;r;Obsługiwany przez");
	td_(";;");
		$result = mysql_query("SELECT filia_nazwa,filia_id FROM $dbname.serwis_filie WHERE filia_id=$es_filia",$conn) or die($k_b);
 		if (mysql_num_rows($result) > 0) { 
			echo "<select name=ubelongs_to onkeypress='return handleEnter(this, event);'>\n"; 
			while (list($fnazwa,$fid)=mysql_fetch_array($result)) { echo "<option value='$fid'>$fnazwa</option>\n"; } 
		  	echo "</select>\n"; 
		}
	_td();
_tr();
tbl_empty_row(2);
endtable();
echo "<input tabindex=-1 type=hidden name=status value=$es>";
echo "<input tabindex=-1 type=hidden name=id value=$id>";
echo "<input tabindex=-1 type=hidden name=oprogramowanie value='0'>";
echo "<input tabindex=-1 type=hidden name=edittype value='$edittype'>";
echo "<div class=hideme>";
startbuttonsarea("right");
addbuttons("zapisz","anuluj");
endbuttonsarea();
echo "</div>";
_form();
?>
<script language="JavaScript">
var cal1 = new calendar1(document.forms['editewid'].elements['gwarancja']);
	cal1.year_scroll = true;
	cal1.time_comp = false;
</script>
<script language="JavaScript" type="text/javascript">
	var frmvalidator  = new Validator("editewid");
<?php if ($edittype=='admin') { ?>
	frmvalidator.addValidation("ls","dontselect=0","Nie wybrałeś lokalizacji sprzętu");
<?php } ?>
	frmvalidator.addValidation("gwarancja","numerichyphen","Użyłeś niedozwolonych znaków w polu \"Gwarancja\"");
</script>
<?php } ?>
</body>
</html>