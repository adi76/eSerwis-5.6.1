<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) {	
	$_POST=sanitize($_POST);
	
	//print_r($_POST);
	
	$dddd = date("Y-m-d H:i:s");
	$lista_zmian = '';
//	$sama_nazwa_up = substr($_POST[old_tup],strpos($_POST[old_tup]," ")+1,strlen($_POST[old_tup]));
	$sama_nazwa_up = $_POST[old_tup];

	$sql_up = "SELECT up_id, up_pion_id FROM $dbname.serwis_komorki WHERE (up_nazwa='$sama_nazwa_up') and (belongs_to=$es_filia) LIMIT 1";
	$wynik = mysql_query($sql_up, $conn) or die($k_b);
	$dane_up = mysql_fetch_array($wynik);
	$temp_up_id = $dane_up['up_id'];
	$temp_pion_id = $dane_up['up_pion_id'];
	
	// nazwa pionu z id pionu
	$sql_get_pion = "SELECT pion_nazwa FROM $dbname.serwis_piony WHERE (pion_id='$temp_pion_id') LIMIT 1";
	$wynik_get_pion = mysql_query($sql_get_pion, $conn) or die($k_b);
	$dane_get_pion = mysql_fetch_array($wynik_get_pion);
	$temp_pion_nazwa1 = $dane_get_pion['pion_nazwa'];
	// koniec ustalania nazwy pionu
	
	$nowa_nazwa_up = $_POST[tup];
	
	$sql_up = "SELECT up_id, up_pion_id FROM $dbname.serwis_komorki WHERE (up_nazwa='$nowa_nazwa_up') and (belongs_to=$es_filia) LIMIT 1";
	$wynik = mysql_query($sql_up, $conn) or die($k_b);
	$dane_up = mysql_fetch_array($wynik);
	$temp_up_id = $dane_up['up_id'];
	$temp_pion_id = $dane_up['up_pion_id'];
	
	// nazwa pionu z id pionu
	$sql_get_pion = "SELECT pion_nazwa FROM $dbname.serwis_piony WHERE (pion_id='$temp_pion_id') LIMIT 1";
	$wynik_get_pion = mysql_query($sql_get_pion, $conn) or die($k_b);
	$dane_get_pion = mysql_fetch_array($wynik_get_pion);
	$temp_pion_nazwa = $dane_get_pion['pion_nazwa'];
	// koniec ustalania nazwy pionu
	
	if ($sama_nazwa_up!=$_POST[tup]) $lista_zmian.='<u>Zmiana komórki:</u> <b>'.$temp_pion_nazwa1.' '.$sama_nazwa_up.'</b> -> <b>'.$temp_pion_nazwa.' '.$nowa_nazwa_up.'</b><br />';	
	if ($_REQUEST[old_typid]!=$_POST[typid]) $lista_zmian.='<u>Zmiana typu sprzętu:</u> <b>'.$_POST[old_typid].'</b> -> <b>'.$_POST[typid].'</b><br />';
	if ($_REQUEST[old_dmodel]!=$_POST[dmodel]) $lista_zmian.='<u>Zmiana modelu sprzętu:</u> <b>'.$_POST[old_dmodel].'</b> -> <b>'.$_POST[dmodel].'</b><br />';
	if ($_REQUEST[old_dsn]!=$_POST[dsn]) $lista_zmian.='<u>Zmiana numeru seryjnego sprzętu:</u> <b>'.$_POST[old_dsn].'</b> -> <b>'.$_POST[dsn].'</b><br />';
	if ($_REQUEST[old_dni]!=$_POST[dni]) $lista_zmian.='<u>Zmiana numeru inwentarzowego sprzętu:</u> <b>'.$_POST[old_dni].'</b> -> <b>'.$_POST[dni].'</b><br />';
	if ($_REQUEST[old_dnu]!=$_POST[dnu]) $lista_zmian.='<u>Zmiana uwag o uszkodzeniu:</u> <b>'.$_POST[old_dnu].'</b> -> <b>'.$_POST[dnu].'</b><br />';
	
	$sql_e1="UPDATE $dbname.serwis_naprawa SET naprawa_model = '$_POST[dmodel]', naprawa_sn = '$_POST[dsn]', naprawa_ni = '$_POST[dni]', naprawa_uwagi='".nl2br($_POST[dnu])."', naprawa_pobrano_z='$_POST[tup]', naprawa_nazwa='$_REQUEST[typid]' WHERE naprawa_id = '$uid' LIMIT 1";

	//echo $sql_e1;
	//echo $lista_zmian;

	if (mysql_query($sql_e1, $conn)) { 
	
		if ($lista_zmian!='') {
			$sql_insert = "INSERT INTO $dbname.serwis_naprawa_historia_zmian values ('', '$_POST[uid]','$dddd','$currentuser','$lista_zmian',1,'',$es_filia)";
			$wynik = mysql_query($sql_insert, $conn);
		}
		
		// aktualizacja w ewidencji
		
		if ($_POST[popraw_w_ewidencji]=='on') {
		
		//echo $_POST[typid];
		
		$sql_update_ewid = '';
		if ($_POST[typid]=='Komputer') $sql_update_ewid = "UPDATE $dbname.serwis_ewidencja SET ewidencja_komputer_opis='$_POST[dmodel]', ewidencja_komputer_sn='$_POST[dsn]', ewidencja_zestaw_ni='$_POST[dni]' WHERE ewidencja_id = $_POST[ewid_id] LIMIT 1";
		if ($_POST[typid]=='Serwer') $sql_update_ewid = "UPDATE $dbname.serwis_ewidencja SET ewidencja_komputer_opis='$_POST[dmodel]', ewidencja_komputer_sn='$_POST[dsn]', ewidencja_zestaw_ni='$_POST[dni]' WHERE ewidencja_id = $_POST[ewid_id] LIMIT 1";
		if ($_POST[typid]=='Notebook') $sql_update_ewid = "UPDATE $dbname.serwis_ewidencja SET ewidencja_komputer_opis='$_POST[dmodel]', ewidencja_komputer_sn='$_POST[dsn]', ewidencja_zestaw_ni='$_POST[dni]' WHERE ewidencja_id = $_POST[ewid_id] LIMIT 1";

		if ($_POST[typid]=='Monitor') $sql_update_ewid = "UPDATE $dbname.serwis_ewidencja SET ewidencja_monitor_opis='$_POST[dmodel]', ewidencja_monitor_sn='$_POST[dsn]', ewidencja_zestaw_ni='$_POST[dni]' WHERE ewidencja_id = $_POST[ewid_id] LIMIT 1";

		if ($_POST[typid]=='Drukarka') $sql_update_ewid = "UPDATE $dbname.serwis_ewidencja SET ewidencja_drukarka_opis='$_POST[dmodel]', ewidencja_drukarka_sn='$_POST[dsn]', ewidencja_drukarka_ni='$_POST[dni]' WHERE ewidencja_id = $_POST[ewid_id] LIMIT 1";
		
		if ($sql_update_ewid=='') {
			$sql_update_ewid = "UPDATE $dbname.serwis_ewidencja SET ewidencja_komputer_nazwa='$_POST[dmodel]', ewidencja_komputer_sn='$_POST[dsn]', ewidencja_zestaw_ni='$_POST[dni]' WHERE ewidencja_id = $_POST[ewid_id] LIMIT 1";
		}
		
	//	echo "$sql_update_ewid";
		
			$wynik = mysql_query($sql_update_ewid, $conn);
		}		

		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php	
	} else {
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php	
	}
} else {
$sql_e = "SELECT * FROM $dbname.serwis_naprawa WHERE (naprawa_id=$id) LIMIT 1";
$result = mysql_query($sql_e, $conn) or die($k_b);
while ($dane = mysql_fetch_array($result)) {
	$mid 		= $dane['naprawa_id'];
	$mnazwa 	= $dane['naprawa_nazwa'];
	$mmodel		= $dane['naprawa_model'];
	$msn	 	= $dane['naprawa_sn'];
	$mni		= $dane['naprawa_ni'];
	$mnu		= $dane['naprawa_uwagi'];
}

pageheader("Edycja danych o uszkodzonym sprzęcie");
?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
starttable();
echo "<form name=edu action=$PHP_SELF method=POST>";

tbl_empty_row(1);
tr_();
	td("120;rt;Komórka");
	td_(";;");
	if ($from_ewid==1) {
		echo "<input type=hidden name=tup value='".$_REQUEST[up]."'>";
		$result44 = mysql_query("SELECT serwis_komorki.up_id, serwis_komorki.up_nazwa, serwis_piony.pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) and (serwis_komorki.up_nazwa='$_REQUEST[up]') ORDER BY serwis_piony.pion_nazwa,up_nazwa", $conn) or die($k_b);
		list($temp_id,$temp_nazwa,$temp_pion)=mysql_fetch_array($result44);
		
		echo "<b>$temp_pion $temp_nazwa</b>";
	} else {
		$result44 = mysql_query("SELECT serwis_komorki.up_id, serwis_komorki.up_nazwa, serwis_piony.pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) ORDER BY serwis_piony.pion_nazwa,up_nazwa", $conn) or die($k_b);
		
		echo "<select name=tup onkeypress='return handleEnter(this, event);'>\n";		 				
		
		while (list($temp_id,$temp_nazwa,$temp_pion)=mysql_fetch_array($result44)) {				
			echo "<option value='$temp_nazwa' ";
			$pelna_nazwa_up = $temp_pion." ".$temp_nazwa;
			$nazwa_up = $temp_nazwa;
			if ($pelna_nazwa_up==$_REQUEST[up]) echo " SELECTED ";
			if ($nazwa_up==$_REQUEST[up]) echo " SELECTED ";
			echo ">$temp_pion $temp_nazwa</option>\n"; 
		}
		echo "</select>\n"; 
	}
	_td();
_tr();
tr_();
	td("120;rt;Typ sprzętu");
	td_(";;");
		if ($from_ewid==1) {
		echo "<input type=hidden name=typid value='".$_REQUEST[typ]."'>";
		//$result44 = mysql_query("SELECT serwis_komorki.up_id, serwis_komorki.up_nazwa, serwis_piony.pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) and (serwis_komorki.up_nazwa='$_REQUEST[up]') ORDER BY serwis_piony.pion_nazwa,up_nazwa", $conn) or die($k_b);
		//list($temp_id,$temp_nazwa,$temp_pion)=mysql_fetch_array($result44);
		
		echo "<b>$_REQUEST[typ]</b>";
		
	//	echo "<span style='float:right'><input type=button class=buttons value=' Włącz edycję wszystkich pól ' onClick=\" if (confirm('Włączenie edycji wszystkich pól uniemożliwi uaktualnienie danych w bazie ewidencji sprzętu. Czy kontynuować ?')) { self.location.href='e_naprawy.php?id=$_GET[id]&up=$_GET[up]&typ=$_GET[typ]&from_ewid=0'; }  \"></span>";
		
	} else {
		$result = mysql_query("SELECT rola_id,rola_nazwa FROM $dbname.serwis_slownik_rola WHERE rola_do_ewidencji=1 ORDER BY rola_nazwa", $conn) or die($k_b);
		$count_rows = mysql_num_rows($result);
		echo "<select name=typid onkeypress=\"return handleEnter(this, event);\">\n";
//		echo "<option value=''>Wybierz z listy...";
		while (list($temp_id,$temp_nazwa) = mysql_fetch_array($result)) {
			echo "<option value=$temp_nazwa";
			if ($temp_nazwa==$_REQUEST[typ]) echo " SELECTED";
			echo ">$temp_nazwa</option>\n"; 
		}
		echo "</select>\n"; 
	}
	_td();
_tr();
tr_();
	td("120;r;Model");
	td_(";;");
		
	if ($from_ewid==1) {
	
	switch ($_REQUEST[typ]) {
		case "Komputer" :
									$result44=mysql_query("SELECT konfiguracja_id,konfiguracja_nazwa,konfiguracja_opis FROM $dbname.serwis_slownik_konfiguracja ORDER BY konfiguracja_opis", $conn) or die($k_b);
									echo "<select name=dmodel onkeypress='return handleEnter(this, event);'>\n"; 				
									//echo "<option value=''>Wybierz z listy...";
										while (list($temp_id,$temp_nazwa,$temp_opis)=mysql_fetch_array($result44)) {
											echo "<option value='$temp_opis'";
											if ($temp_opis==$mmodel) echo " SELECTED";
											echo ">$temp_opis</option>\n";
										}
									echo "</select>\n";		
									break;

		case "Serwer" :
									$result44=mysql_query("SELECT konfiguracja_id,konfiguracja_nazwa,konfiguracja_opis FROM $dbname.serwis_slownik_konfiguracja ORDER BY konfiguracja_opis", $conn) or die($k_b);
									echo "<select name=dmodel onkeypress='return handleEnter(this, event);'>\n"; 				
									//echo "<option value=''>Wybierz z listy...";
										while (list($temp_id,$temp_nazwa,$temp_opis)=mysql_fetch_array($result44)) {
											echo "<option value='$temp_opis'";
											if ($temp_opis==$mmodel) echo " SELECTED";
											echo ">$temp_opis</option>\n";
										}
									echo "</select>\n";		
									break;

		case "Notebook" :
									$result44=mysql_query("SELECT konfiguracja_id,konfiguracja_nazwa,konfiguracja_opis FROM $dbname.serwis_slownik_konfiguracja ORDER BY konfiguracja_opis", $conn) or die($k_b);
									echo "<select name=dmodel onkeypress='return handleEnter(this, event);'>\n"; 				
									//echo "<option value=''>Wybierz z listy...";
										while (list($temp_id,$temp_nazwa,$temp_opis)=mysql_fetch_array($result44)) {
											echo "<option value='$temp_opis'";
											if ($temp_opis==$mmodel) echo " SELECTED";
											echo ">$temp_opis</option>\n";
										}
									echo "</select>\n";		
									break;
									
		case 'Monitor' :
						$result44=mysql_query("SELECT monitor_nazwa FROM $dbname.serwis_slownik_monitor ORDER BY monitor_nazwa", $conn) or die($k_b);
						echo "<select name=dmodel onkeypress='return handleEnter(this, event);'>\n"; 				
						//echo "<option value=''>Wybierz z listy...";
							while (list($temp_nazwa)=mysql_fetch_array($result44)) {
								echo "<option value='$temp_nazwa'";
								if ($temp_nazwa==$mmodel) echo " SELECTED";
								echo ">$temp_nazwa</option>\n";
							}
						echo "</select>\n";
						break;
		case 'Drukarka' :
						$result44=mysql_query("SELECT drukarka_nazwa FROM $dbname.serwis_slownik_drukarka ORDER BY drukarka_nazwa", $conn) or die($k_b);
						echo "<select name=dmodel onkeypress='return handleEnter(this, event);'>\n"; 				
						//echo "<option value=''>Wybierz z listy...";
							while (list($temp_nazwa)=mysql_fetch_array($result44)) {
								echo "<option value='$temp_nazwa'";
								if ($temp_nazwa==$mmodel) echo " SELECTED";
								echo ">$temp_nazwa</option>\n";
							}
						echo "</select>\n";
						break;
			default :	echo "<input class=wymagane size=35 type=text name=dmodel value='$mmodel'>";
						break;
			}

		echo "&nbsp;<font color=red><a title=' Można dokonać uaktualnienia tej wartości w bazie ewidencji sprzętu '>*</a></font>";

	} else {
		echo "<input class=wymagane size=35 type=text name=dmodel value='$mmodel'>";
	}
	_td();
_tr();
tr_();
	td("120;r;SN");
	td_(";;");
		echo "<input class=wymagane type=text size=40 name=dsn value='$msn'>";
		if ($from_ewid==1) echo "&nbsp;<font color=red><a title=' Można dokonać uaktualnienia tej wartości w bazie ewidencji sprzętu '>*</a></font>";
	_td();
_tr();
tr_();
	td("120;r;NI");
	td_(";;");
		echo "<input type=text name=dni size=40 value='$mni'>";
		if ($from_ewid==1) echo "&nbsp;<font color=red><a title=' Można dokonać uaktualnienia tej wartości w bazie ewidencji sprzętu '>*</a></font>";
	_td();
_tr();
tr_();
	td("120;rt;Uwagi");
	td_(";;");
		echo "<textarea cols=50 rows=4 name=dnu>".br2nl($mnu)."</textarea>";
	_td();
_tr();
tbl_empty_row();
endtable();

echo "<input type=hidden name=old_tup value='".$_GET[up]."'>";
echo "<input type=hidden name=old_typid value='".$_GET[typ]."'>";
echo "<input type=hidden name=old_dmodel value='".$mmodel."'>";
echo "<input type=hidden name=old_dsn value='".$msn."'>";
echo "<input type=hidden name=old_dni value='".$mni."'>";
echo "<input type=hidden name=old_dnu value='".$mnu."'>";
if ($from_ewid==1) echo "<input type=hidden name=ewid_id value='".$_REQUEST[ewid_id]."'>";
echo "<input size=30 type=hidden name=uid value='$mid'>";
startbuttonsarea("right");
if ($from_ewid==1) {
echo "<span style='float:left'>";
	echo "<input class=border0 type=checkbox name=popraw_w_ewidencji id=popraw_w_ewidencji>";
	echo "<a href=# class=normalfont onClick=\"if (document.getElementById('popraw_w_ewidencji').checked) { document.getElementById('popraw_w_ewidencji').checked=false; } else { document.getElementById('popraw_w_ewidencji').checked=true; }\">";
	echo "<font color=red>&nbsp;Uaktualnij ewidencję sprzętu *</font></a>&nbsp;&nbsp;&nbsp;&nbsp;";
echo "</span>";
}
addbuttons("zapisz","anuluj");
endbuttonsarea();
_form();
?>
<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("edu"); 
  frmvalidator.addValidation("dmodel","req","Nie podałeś modelu sprzętu");
  frmvalidator.addValidation("dsn","reg","Nie podałeś numeru seryjnego sprzętu");
</script>
<?php } ?>
<script>HideWaitingMessage();</script>
</body>
</html>