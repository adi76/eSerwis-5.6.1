<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[2].focus();">
<?php 
if ($submit) { 
	?>
	<script>ShowWaitingMessage('Trwa zapisywanie danych...', 'Saving1');</script>
	<?php 
	$_POST=sanitize($_POST);
	$sql_1 = "SELECT rola_nazwa FROM $dbname.serwis_slownik_rola WHERE (rola_id=$_POST[ts]) LIMIT 1";
	list($rola_typ_nazwa)=mysql_fetch_array(mysql_query($sql_1,$conn));
	$sql_2 = "SELECT up_nazwa FROM $dbname.serwis_komorki WHERE (up_id=$_POST[ls]) LIMIT 1";
	list($lok_up_nazwa)=mysql_fetch_array(mysql_query($sql_2,$conn));
	$dddd = Date('Y-m-d H:i:s');
	$endpoint1 = $_POST['ne'];
	if ($_POST['ipend']=='on') { $endpoint1 = $_POST['nazwak']; }
	$konf_opis='';
	if (($rola_typ_nazwa=='Komputer') || ($rola_typ_nazwa=='Serwer') || ($rola_typ_nazwa=='Notebook')) {
		if ($_POST[wpis]=='auto'){ 
			$result444 = mysql_query("SELECT konfiguracja_id, konfiguracja_nazwa, konfiguracja_opis, procesor, pamiec,dysk FROM $dbname.serwis_slownik_konfiguracja WHERE (konfiguracja_id=$_POST[sk]) LIMIT 1", $conn) or die($k_b);
			list($temp_id,$temp_nazwa,$temp_opis,$temp_procesor,$temp_pamiec,$temp_dysk)=mysql_fetch_array($result444);
			$k__procesor	= $temp_procesor;
			$k__pamiec		= $temp_pamiec;
			$k__dysk		= $temp_dysk;			
			$k__procesor	= str_replace(',','.',$k__procesor);
			$k__pamiec		= str_replace(',','.',$k__pamiec);		
			$k__dysk		= str_replace(',','.',$k__dysk);
			$konf_opis='Procesor '.$k__procesor.'GHz, '.$k__pamiec.'MB RAM, '.$k__dysk.'GB HDD';	
		} else  { 
			$k__procesor	= $_POST[konf_proc];
			$k__pamiec		= $_POST[konf_ram];
			$k__dysk		= $_POST[konf_hdd];
			$k__procesor	= str_replace(',','.',$k__procesor);
			$k__pamiec		= str_replace(',','.',$k__pamiec);		
			$k__dysk		= str_replace(',','.',$k__dysk);
			$konf_opis='Procesor '.$k__procesor.'GHz, '.$k__pamiec.'MB RAM, '.$k__dysk.'GB HDD';
			}	
	}
	
	if ($_POST[aipd]!='') { $aip_=$_POST[aipd]; } else { $aip_=$_POST[aip]; }
	
		if ($rola_typ_nazwa=='Czytnik') $_POST[nazwak]=$_POST[nazwak1];
		
		$sql_t = "INSERT INTO $dbname.serwis_ewidencja values ('', '$_POST[ts]','$rola_typ_nazwa','$_POST[ls]','$lok_up_nazwa','$_POST[us]','$_POST[nrp]','$_POST[niz]','$_POST[nazwak]','$_POST[os]','$_POST[nrsk]','$aip_','$endpoint1','$_POST[m]','$_POST[nrsm]','$_POST[d]','$_POST[nrsd]','$_POST[nid]','".nl2br($_POST[uwagi])."',$_POST[ubelongs_to],$_POST[status],$_POST[oprogramowanie],'$dddd','$currentuser','$konf_opis','$k__procesor','$k__pamiec','$k__dysk','$_POST[gwarancja]','$_POST[firmaserwisowa]',0)";

	//	echo "$sql_t";
		
	$isunique=true;
	if (($rola_typ_nazwa=='Komputer') || ($rola_typ_nazwa=='Serwer') || ($rola_typ_nazwa=='Notebook')) {
		$sql_sprawdz_unikalnosc = "SELECT ewidencja_id FROM $dbname.serwis_ewidencja WHERE ((ewidencja_komputer_sn='$_POST[nrsk]') and (ewidencja_komputer_sn<>''))";
		$wynik_unik = mysql_query($sql_sprawdz_unikalnosc,$conn) or die($k_b);
		$isunique = (mysql_num_rows($wynik_unik)==0);
	}
	if ($rola_typ_nazwa=='Drukarka') {
		$sql_sprawdz_unikalnosc = "SELECT ewidencja_id FROM $dbname.serwis_ewidencja WHERE ((ewidencja_drukarka_sn='$_POST[nrsd]') and (ewidencja_drukarka_sn<>''))";
		$wynik_unik = mysql_query($sql_sprawdz_unikalnosc,$conn) or die($k_b);
		$isunique = (mysql_num_rows($wynik_unik)==0);	
	}
if ($isunique) {	
	if (mysql_query($sql_t, $conn)) { 
		$lastid = mysql_insert_id();
		okheader("Pomyślnie dodano nowy sprzęt do ewidencji");
		startbuttonsarea("right");
		addlinkbutton("'Dodaj nowy sprzęt'","d_ewidencja.php");
		
		if (($rola_typ_nazwa=='Drukarka') || ($rola_typ_nazwa=='Komputer') || ($rola_typ_nazwa=='Serwer') || ($rola_typ_nazwa=='Notebook')) {
	//		okheader("Pomyślnie dodano nowy sprzęt do ewidencji");
			if ($rola_typ_nazwa=='Drukarka') {
				addlinkbutton("'Przypisz drukarkę do komputera'","z_ewidencja_przypisz_drukarke.php?id=$lastid&upid=$_POST[ls]");
			//	addownlinkbutton("'Przypisz drukarkę do komputera2'","button","button","newWindow(600,600,'z_ewidencja_przypisz_drukarke.php?id=$lastid&upip=$_POST[ls]')");
			}
			if (($rola_typ_nazwa=='Komputer') || ($rola_typ_nazwa=='Serwer') || ($rola_typ_nazwa=='Notebook')) {
	//			addlinkbutton("'Dodaj nowy sprzęt'","d_ewidencja.php");
				addlinkbutton("'Dodaj oprogramowanie dla tego komputera'","p_oprogramowanie.php?id=$lastid&autoadd=1&upid=$_POST[ls]");
			}
		}
		
		
		addlinkbutton("'Dodaj nowy sprzęt na bazie poprzedniego'","d_ewidencja.php?clone=1&ts=".urlencode($_POST[ts])."&ls=".$_POST[ls]."&us=".urlencode($_POST[us])."&nrp=".urlencode($_POST[nrp])."&niz=".urlencode($_POST[niz])."&nazwak=".urlencode($_POST[nazwak])."&os=".urlencode($_POST[os])."&nrsk=".urlencode($_POST[nrsk])."&aip=".urlencode($aip_)."&ne=".urlencode($endpoint1)."&m=".urlencode($_POST[m])."&nrsm=".urlencode($_POST[nrsm])."&d=".urlencode($_POST[d])."&nrsd=".urlencode($_POST[nrsd])."&nid=".urlencode($_POST[nid])."&uwagi=".urlencode(br2nl($_POST[uwagi]))."&ubelongs_to=".$_POST[ubelongs_to]."&oprogramowanie=".$_POST[oprogramowanie]."&wpis=".$_POST[wpis]."&sk=".$_POST[sk]."&konf_proc=".urlencode($_POST[konf_proc])."&konf_ram=".urlencode($_POST[konf_ram])."&konf_hdd=".urlencode($_POST[konf_hdd])."&gwarancja=".$_POST[gwarancja]."&firmaserwisowa=".$_POST[firmaserwisowa]."");
		
		addclosewithreloadbutton("Zamknij");
		endbuttonsarea();
		
	/*	else { 
		?>
		<script> opener.location.reload(true); self.close(); </script>
		<?php
		}
	*/	
	} else 
	{
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); //self.close(); </script><?php
	} 
} else {
	?>
	<script>
		alert('Istnieje już w bazie sprzęt o takim numerze seryjnym');
		window.location='d_ewidencja.php?clone=1&ts=<?php echo urlencode($_POST[ts]);?>&ls=<?php echo $_POST[ls];?>&us=<?php echo urlencode($_POST[us]);?>&nrp=<?php echo urlencode($_POST[nrp]);?>&niz=<?php echo urlencode($_POST[niz]);?>&nazwak=<?php echo urlencode($_POST[nazwak]);?>&os=<?php echo urlencode($_POST[os]);?>&nrsk=<?php echo  urlencode($_POST[nrsk]);?>&aip=<?php echo urlencode($_POST[aip]);?>&ne=<?php echo urlencode($endpoint1);?>&m=<?php echo urlencode($_POST[m]);?>&nrsm=<?php echo urlencode($_POST[nrsm]);?>&d=<?php echo urlencode($_POST[d]);?>&nrsd=<?php echo urlencode($_POST[nrsd]);?>&nid=<?php echo urlencode($_POST[nid]);?>&uwagi=<?php echo urlencode(br2nl($_POST[uwagi]));?>&ubelongs_to=<?php echo $_POST[ubelongs_to];?>&oprogramowanie=<?php echo $_POST[oprogramowanie];?>&wpis=<?php echo $_POST[wpis];?>&sk=<?php echo $_POST[sk];?>&konf_proc=<?php echo urlencode($_POST[konf_proc]);?>&konf_ram=<?php echo urlencode($_POST[konf_ram]);?>&konf_hdd=<?php echo urlencode($_POST[konf_hdd]);?>&gwarancja=<?php echo $_POST[gwarancja];?>&firmaserwisowa=<?php echo $_POST[firmaserwisowa];?>';
	</script>
	<?php
	}
} else {
pageheader("Dodawanie nowego sprzętu do ewidencji",1);
starttable();
echo "<form name=addewid action=$PHP_SELF method=POST>";
startbuttonsarea("right");
addbuttons("zapisz","anuluj");
endbuttonsarea();
tr_();
	th_colspan(2,'l','&nbsp;Typ sprzętu');
	_th();
_tr();
tbl_empty_row(2);
tr_();
	td_("160;r;Typ sprzętu");
	_td();
	td_colspan(1,"l","");
	if ($clone!=1) {
		$result44 = mysql_query("SELECT rola_id, rola_nazwa FROM $dbname.serwis_slownik_rola WHERE (rola_do_ewidencji=1) ORDER BY rola_nazwa", $conn) or die($k_b);
		echo "<select class=wymagane name=ts ";
//		if (($clone==1) && ($wpis=='auto')) echo "wlacz_slownik(); '";
//		if (($clone==1) && ($wpis=='manual')) echo "wlacz_manual(); '";
		echo "onchange='ewid(this.value); clearForm(this.form);'";
		echo "onfocus='ewid(this.value); clearForm(this.form);' onblur='ewid(this.value); clearForm(this.form);' >\n";
		echo "<option value=''>Wybierz z listy..."; 
		while (list($temp_id,$temp_nazwa)=mysql_fetch_array($result44)) {
			if ($temp_nazwa!='Monitor') {
				echo "<option value='$temp_id'"; 
				if ($clone==1) if ($temp_id==$ts) echo " SELECTED";
				echo ">$temp_nazwa</option>\n";
			}
		}
		echo "</select>\n"; 
	} else {
		$result44 = mysql_query("SELECT rola_id, rola_nazwa FROM $dbname.serwis_slownik_rola WHERE (rola_do_ewidencji=1) and (rola_id=$ts) ORDER BY rola_nazwa", $conn) or die($k_b);
		echo "<select class=wymagane name=ts ";
		echo "onchange='ewid(this.value); clearForm(this.form);'";
		echo "onfocus='ewid(this.value); clearForm(this.form);' onblur='ewid(this.value); clearForm(this.form);' >\n";
		echo "<option value=''>Wybierz z listy..."; 
		while (list($temp_id,$temp_nazwa)=mysql_fetch_array($result44)) {
			if ($temp_nazwa!='Monitor') {
				echo "<option value='$temp_id'"; 
				if ($clone==1) if ($temp_id==$ts) echo " SELECTED";
				echo ">$temp_nazwa</option>\n";
			}
		}
		echo "</select>\n";
		
		//echo "<input tabindex=-1 type=hidden name=ts value=$ts>";
		echo "<b>$temp_nazwa</b>";
	}
	_td();
_tr();
echo "<tr id=lokalizacja1 style=display:none >";
	td_colspan(2);
		br();
	_td();
_tr();
echo "<tr id=lokalizacja2 style=display:none >";
	th_colspan(2,'l','&nbsp;Informacje o lokalizacji sprzętu');
	_th();
_tr();
echo "<tr id=lokalizacja3 style=display:none >";
	td_colspan(2,'c');
	_td();
_tr();
echo "<tr id=lokalizacja4 style=display:none>";
	td("160;r;Lokalizacja sprzętu");
	td_(";;");	
		//$result44 = mysql_query("SELECT up_id, up_nazwa FROM $dbname.serwis_komorki WHERE ((belongs_to=$es_filia) and (up_active=1)) ORDER BY up_nazwa", $conn) or die($k_b);
		
		$result44 = mysql_query("SELECT serwis_komorki.up_id, serwis_komorki.up_nazwa, serwis_piony.pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) ORDER BY serwis_piony.pion_nazwa,up_nazwa", $conn) or die($k_b);
				
		echo "<select class=wymagane name=ls onkeypress='return handleEnter(this, event);'>\n";
		echo "<option value=''>Wybierz z listy...";
		while (list($temp_id,$temp_nazwa,$temp_pion)=mysql_fetch_array($result44)) {
			echo "<option value='$temp_id'";
			if ($clone==1) if ($temp_id==$ls) echo " SELECTED";
			echo ">$temp_pion $temp_nazwa</option>\n"; 
		}
		echo "</select>\n"; 
	_td();
_tr();
echo "<tr id=lokalizacja5 style=display:none>";
	td("160;r;Użytkownik sprzętu");
	td_(";;");
		echo "<input size=20 maxlength=30 type=text name=us onkeypress='return handleEnter(this, event);' ";
		if ($clone==1) echo "value='$us'";
		echo ">";
	_td();
_tr();
echo "<tr id=lokalizacja6 style=display:none>";
	td("160;r;Nr pokoju");
		echo "<td><input size=10 maxlength=15 type=text name=nrp onkeypress='return handleEnter(this, event);' ";
		if ($clone==1) echo "value='$nrp'";
		echo ">";
	_td();
_tr();
echo "<tr id=zestaw1 style=display:none><td colspan=2><br /></td></tr>";
echo "<tr id=zestaw2 style=display:none>";
	th_colspan(2,'l','&nbsp;Informacje o zestawie komputerowym');
	_th();
_tr();
echo "<tr id=zestaw3 style=display:none><td colspan=2></td></tr>";
echo "<tr id=zestaw4 style=display:none>";
	td("160;r;Nr inwentarzowy sprzętu");
	td_(";;");
		echo "<input size=20 maxlength=20 type=text name=niz onkeypress='return handleEnter(this, event);' ";
		if ($clone==1) echo "value='$niz'";
		echo ">";
	_td();
_tr();
echo "<tr id=zestaw5 style=display:none><th class=right colspan=1><b>Komputer/Serwer</th><td></td></tr>";

echo "<tr id=zestaw6 style=display:none>";
	td("160;r;Nazwa");
	td_(";;");
		echo "<input size=30 maxlength=30 type=text name=nazwak onkeypress='return handleEnter(this, event);' ";
		if ($clone==1) echo "value='$nazwak'";
		echo "><a class=nav_normal style='background-color:transparent; border:0px solid black;'>np. p21xxxxxxx</a>";
	_td();
_tr();

echo "<tr id=czytnik1 style=display:none>";
	td("160;r;Model czytnika");
	td_(";;");
		$result4444 = mysql_query("SELECT czytnik_id,czytnik_nazwa,czytnik_opis FROM $dbname.serwis_slownik_czytnik ORDER BY czytnik_nazwa", $conn) or die($k_b);
		echo "<select name=nazwak1 onkeypress='return handleEnter(this, event);'>\n";
		echo "<option value=''>Wybierz z listy...";
		while (list($temp_id,$temp_nazwa,$temp_opis)=mysql_fetch_array($result4444)) {
			echo "<option value='$temp_nazwa'";
			if ($clone==1) if ($temp_nazwa=='$nazwak') echo " SELECTED";
			echo ">$temp_nazwa</option>\n"; 
		}
		echo "</select>\n";
	_td();
_tr();

echo "<tr id=zestaw7 style=display:none>";
	td("160;r;Opis");
	td_(";;");
		$result44=mysql_query("SELECT konfiguracja_id,konfiguracja_nazwa,konfiguracja_opis FROM $dbname.serwis_slownik_konfiguracja ORDER BY konfiguracja_opis", $conn) or die($k_b);
		echo "<select name=os onkeypress='return handleEnter(this, event);'>\n"; 				
		echo "<option value=''>Wybierz z listy...";
			while (list($temp_id,$temp_nazwa,$temp_opis)=mysql_fetch_array($result44)) {
				echo "<option value='$temp_opis'";
				if ($clone==1) if ($temp_opis==$os) echo " SELECTED";
				echo ">$temp_opis</option>\n";
			}
		echo "</select>\n";
	_td();
_tr();
echo "<tr id=zestaw8 style=display:none>";
	td("160;r;Nr seryjny");
	td_(";;");
		echo "<input size=30 maxlength=30 type=text name=nrsk onkeypress='return handleEnter(this, event);'";
		if ($clone==1) echo "value='$nrsk'";
		echo ">";
	_td();
_tr();
echo "<tr id=zestaw9 style=display:none>";
	td("160;r;Adres IP");
	td_(";;");
		echo "<input size=15 type=text maxlength=15 name=aip onkeypress='return handleEnter(this, event);'";
		if ($clone==1) echo "value='$aip'";
		echo ">";
	_td();
_tr();
echo "<tr id=zestaw9a style=display:none>";
	td("160;r;Endpoint");
	td_(";;");
		//echo "<a class=nav_normal>&nbsp;Endpoint&nbsp;</a>";
		echo "<input size=15 maxlenght=30 type=text name=ne ";
		if ($clone==1) echo "value='$ne'";
		echo ">";
		echo "<input class=border0 type=checkbox name=ipend onkeypress='return handleEnter(this, event);'>";
		echo "<span style='cursor:hand' onclick=labelClick(document.addewid.ipend)><a class=nav_normal>endpoint = nazwa komputera</a></span>";
	_td();
_tr();
echo "<tr id=zestaw10 style=display:none>";
	td("160;r;Konfiguracja sprzętu");
	td_(";;");
		echo "<input type=radio name=wpis id=wpis1 ";
		if ($clone==1) if ($wpis=='auto') echo " checked=checked ";
		echo "value='auto' onclick='wlacz_slownik(); document.addewid.sk.focus();'><a class=nav_normal href=# style='background-color:transparent; border:0px solid black;' onClick=\"if (document.getElementById('wpis1').checked) { document.getElementById('wpis1').checked=false; } else { document.getElementById('wpis1').checked=true; } wlacz_slownik(); document.addewid.sk.focus(); return false;\" >ze słownika</a>";
		echo "<input type=radio name=wpis id=wpis2 value='manual' onclick='wlacz_manual(); document.addewid.konf_proc.focus(); '";
		if ($clone==1) if ($wpis!='auto') echo " checked=checked ";
		echo "><a class=nav_normal href=# style='background-color:transparent; border:0px solid black;' onClick=\"if (document.getElementById('wpis2').checked) { document.getElementById('wpis2').checked=false; } else { document.getElementById('wpis2').checked=true; } wlacz_manual(); document.addewid.konf_proc.focus(); return false;\">wpisz ręcznie</a>";
	_td();
_tr();
echo "<tr id=zestaw11 style=display:none>";
//if ($clone==1) if ($wpis=='auto') { echo ">"; } else echo " style='display:none'>";
echo "<div id=slownik>";
//if ($clone==1) if ($wpis=='auto') { echo ">"; } else echo " style='display:none'>";
	td("160;;");
		$result44 = mysql_query("SELECT konfiguracja_id,konfiguracja_nazwa,konfiguracja_opis FROM $dbname.serwis_slownik_konfiguracja ORDER BY konfiguracja_nazwa", $conn) or die($k_b);
	echo "<td id=slownik>";		
		echo "<select name=sk>\n"; 					 				
		echo "<option value=0>Wybierz z listy...";
		while (list($temp_id,$temp_nazwa,$temp_opis)=mysql_fetch_array($result44)) {
			echo "<option value='$temp_id'";
			if ($clone==1) if ($temp_id==$sk) echo " SELECTED";
			echo ">$temp_nazwa | $temp_opis</option>\n"; 
		}
		echo "</select>\n"; 
	_td();
	echo "</div>";
_tr();
echo "<tr id=zestaw12 style=display:none>";
//if ($clone==1) if ($wpis=='manual') { echo ">"; } else echo " style='display:none'>";
echo "<div id=wpiszsam style=display:none>";
//if ($clone==1) if ($wpis=='manual') { echo ">"; } else echo " style='display:none'>";
//style=display:none>";
	td("160;r");
	td_(";;");
		echo "<a class=nav_normal style='background-color:transparent; border:0px solid black;'>Procesor (GHz) </a><input size=5 maxlength=5 type=text name=konf_proc onkeypress='return handleEnter(this, event);' ";
		if ($clone==1) echo "value='$konf_proc'";
		echo ">";
		echo "<a class=nav_normal style='background-color:transparent; border:0px solid black;'>&nbsp;Pamięć (MB) </a><input size=5 maxlength=5 type=text name=konf_ram onkeypress='return handleEnter(this, event);' ";
		if ($clone==1) echo "value='$konf_ram'";
		echo ">&nbsp;";
		echo "<a class=nav_normal style='background-color:transparent; border:0px solid black;'>&nbsp;Dysk (GB) </a><input size=5 maxlength=5 type=text name=konf_hdd onkeypress='return handleEnter(this, event);' ";
		if ($clone==1) echo "value='$konf_hdd'";
		echo ">&nbsp;";
	_td();
	echo "</div>";
_tr();
echo "<tr id=zestaw13 style=display:none><th class=right>Monitor</th><td></td></tr>";
	echo "<tr id=zestaw14 style=display:none>";
	td("160;r;Nazwa monitora");
	td_(";;");
		$result444 = mysql_query("SELECT monitor_id,monitor_nazwa,monitor_opis,monitor_typ,monitor_cale FROM $dbname.serwis_slownik_monitor ORDER BY monitor_nazwa", $conn) or die($k_b);
		echo "<select name=m onkeypress='return handleEnter(this, event);'>\n";
		echo "<option value=''>Wybierz z listy...";
		while (list($temp_id,$temp_nazwa,$temp_opis,$temp_typ,$temp_cale)=mysql_fetch_array($result444)) {
			echo "<option value='$temp_nazwa'";
			if ($clone==1) if ($temp_nazwa==$m) echo " SELECTED";
			echo ">$temp_nazwa - $temp_cale\" $temp_typ ($temp_opis)</option>\n"; 
		}
		echo "</select>\n";
	_td();
_tr();
echo "<tr id=zestaw15 style=display:none>";
	td("160;r;Nr seryjny monitora");
	td_(";;");
		echo "<input size=30 maxlength=30 type=text name=nrsm onkeypress='return handleEnter(this, event);'";
		if ($clone==1) echo "value='$nrsm'";
		echo ">";
	_td();
_tr();
echo "<tr id=druk1 style=display:none><td colspan=2><br /></td></tr>";
echo "<tr id=druk2 style=display:none>";
	th_colspan(2,'l','&nbsp;Informacje o urządzeniach peryferyjnych');
	_th();
_tr();
echo "<tr id=druk3 style=display:none><td colspan=2></td></tr>";
echo "<tr id=druk4 style=display:none>";
	td("160;r;Model drukarki");
	td_(";;");
		$result4444 = mysql_query("SELECT drukarka_id,drukarka_nazwa,drukarka_opis FROM $dbname.serwis_slownik_drukarka ORDER BY drukarka_nazwa", $conn) or die($k_b);
		echo "<select name=d onkeypress='return handleEnter(this, event);'>\n";
		echo "<option value=''>Wybierz z listy...";
		while (list($temp_id,$temp_nazwa,$temp_opis)=mysql_fetch_array($result4444)) {
			echo "<option value='$temp_nazwa'";
			if ($clone==1) if ($temp_nazwa=='$d') echo " SELECTED";
			echo ">$temp_nazwa</option>\n"; 
		}
		echo "</select>\n";
	_td();
_tr();
echo "<tr id=druk5 style=display:none>";
	td(";r;Nr seryjny drukarki");
	td_(";;");
		echo "<input size=30 maxlength=30 type=text name=nrsd onkeypress='return handleEnter(this, event);'";
		if ($clone==1) echo "value='$nrsd'";
		echo ">";
	_td();
_tr();
echo "<tr id=druk6 style=display:none>";
	td("160;r;Nr inwentarzowy drukarki");
	td_(";;");
		echo "<input size=20 maxlength=20 type=text name=nid onkeypress='return handleEnter(this, event);'";
		if ($clone==1) echo "value='$nid'";
		echo ">";
	_td();
_tr();
echo "<tr id=zestaw9b style=display:none>";
	td("160;r;Adres IP drukarki");
	td_(";;");
		echo "<input size=15 type=text maxlength=15 name=aipd onkeypress='return handleEnter(this, event);'";
		if ($clone==1) echo "value='$aipd'";
		echo ">";
	_td();
_tr();
echo "<tr id=lokalizacja7 style=display:none><td colspan=2><br /></td></tr>";
echo "<tr id=lokalizacja8 style=display:none>";
	th_colspan(2,'l','&nbsp;Informacje dodatkowe');
	_th();
_tr();
echo "<tr id=lokalizacja9 style=display:none><td colspan=2></td></tr>";			
echo "<tr id=lokalizacja10 style=display:none>";
	td("160;rt;Uwagi");
	td_(";;");
		echo "<textarea name=uwagi cols=50 rows=2>";
		if ($clone==1) echo "$uwagi"; 
		echo "</textarea>";
	_td();
_tr();
echo "<tr id=lokalizacja11 style=display:none>";
	td("160;r;Gwarancja do dnia");
	td_(";;");
		echo "<input title=' format daty : RRRR-MM-DD ' size=10 maxlength=10 type=text id=gwarancja name=gwarancja ";
		if ($clone==1) echo "value='$gwarancja'";
		echo "onkeypress='return handleEnter(this, event);' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\">";
		echo "<a tabindex=-1 href=javascript:cal1.popup();><img src=img/cal.gif width=16 height=16 border=0 alt='Kliknij, aby wybrać datę'></a>";
		if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('gwarancja').value='".Date('Y-m-d')."'; return false;\">";
	_td();
_tr();
echo "<tr id=lokalizacja12 style=display:none>";
	td("160;r;Sprzęt serwisowany przez");
	td_(";;");
		$result5 = mysql_query("SELECT fz_id,fz_nazwa,fz_adres FROM $dbname.serwis_fz WHERE (fz_is_fs='on') ORDER BY fz_nazwa ASC", $conn) or die($k_b);
		echo "<select ";
		echo "name=firmaserwisowa onkeypress='return handleEnter(this, event);'>\n";
		echo "<option value=0>Wybierz firmę serwisową...</option>\n";
		while (list($temp_id,$temp_nazwa)=mysql_fetch_array($result5)) { 
			echo "<option value=$temp_id";
			if ($clone==1) if ($temp_id==$firmaserwisowa) echo " SELECTED";
			echo ">$temp_nazwa</option>\n"; 
		}
		echo "</select>\n";
	_td();
_tr();
echo "<tr id=lokalizacja13 style=display:none>";
	td("160;r;Obsługiwany przez");
	td_(";;");
		$result=mysql_query("SELECT filia_nazwa,filia_id FROM $dbname.serwis_filie WHERE filia_id=$es_filia",$conn) or die($k_b);
		if(mysql_num_rows($result) > 0) {
			echo "<select name=ubelongs_to  onkeypress='return handleEnter(this, event);'>\n"; 
			while (list($fnazwa,$fid)=mysql_fetch_array($result)) { 
				echo "<option value='$fid'";
				if ($clone==1) if ($fid=='$ubelongs_to') echo " SELECTED";
				echo ">$fnazwa</option>\n";
			}
			echo "</select>\n"; 
		}
	_td();
_tr();
echo "<tr id=lokalizacja14 style=display:none>";
	td_colspan(2,'c');
    _td();
_tr();
tbl_empty_row(2);
echo "<input type=hidden name=status value='9'>";
echo "<input type=hidden name=oprogramowanie value='0'>";
endtable();
startbuttonsarea("right");
addbuttons("zapisz","anuluj");
endbuttonsarea();	
_form();
?>
<script language="JavaScript">
var cal1 = new calendar1(document.forms['addewid'].elements['gwarancja']);
	cal1.year_scroll = true;
	cal1.time_comp = false;
</script>
<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("addewid");
  frmvalidator.addValidation("ts","dontselect=0","Nie wybrałeś typu sprzętu");
  frmvalidator.addValidation("ls","dontselect=0","Nie wybrałeś lokalizacji sprzętu");
  frmvalidator.addValidation("gwarancja","numerichyphen","Użyłeś niedozwolonych znaków w polu \"Gwarancja\"");
</script>
<?php } ?>
<script>HideWaitingMessage('Saving1');</script>
</body>
</html>