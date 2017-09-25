<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php
if ($submit) {	
	$dddd = Date('Y-m-d H:i:s');
	
	if ($_POST[tfs]!='-1') { 
		$sql_e1="UPDATE $dbname.serwis_naprawa SET naprawa_osoba_wysylajaca  = '$currentuser', naprawa_data_wysylki = '$dddd', naprawa_fs_nazwa = '$_POST[tfs]', naprawa_fk_nazwa = '$_POST[tfk]', naprawa_nr_listu_przewozowego = '$_POST[tnlp]', naprawa_przewidywany_termin_naprawy = '$_POST[ttn] 23:59:59' WHERE naprawa_id='$_POST[uid]' LIMIT 1";
	} else {
		$sql_e1="UPDATE $dbname.serwis_naprawa SET naprawa_osoba_wysylajaca  = '$currentuser', naprawa_data_wysylki = '$dddd', naprawa_fs_nazwa = '$_POST[staryserwis]', naprawa_fk_nazwa = '$_POST[tfk]', naprawa_nr_listu_przewozowego = '$_POST[tnlp]', naprawa_przewidywany_termin_naprawy = '$_POST[ttn] 23:59:59' WHERE naprawa_id='$_POST[uid]' LIMIT 1";	
	}
	//echo "$sql_e1";
	
	if (mysql_query($sql_e1, $conn)) { 
	//	$sql_e = "UPDATE $dbname.serwis_ewidencja SET ewidencja_status = '$_POST[stat]' WHERE (ewidencja_id='$_POST[ew_id]') LIMIT 1";
	//	$wynik2 = mysql_query($sql_e, $conn) or die($k_b);
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else { 
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php	
	}
	
	
} else {

$dddd = Date('Y-m-d H:i:s');
//$_POST=sanitize($_POST);

	$result1 = mysql_query("SELECT naprawa_nazwa,naprawa_model,naprawa_sn FROM $dbname.serwis_naprawa WHERE (naprawa_id=$nid) LIMIT 1", $conn) or die($k_b);
	list($temp_nazwa,$temp_model,$temp_sn)=mysql_fetch_array($result1);
	pageheader("Przesunięcie sprzętu do innego serwisu zewnętrznego");
	infoheader("".$temp_nazwa." ".$temp_model." (SN: ".$temp_sn.")");
	starttable();
	echo "<form name=edu action=$PHP_SELF method=POST>";
	tbl_empty_row();
		tr_();
			$result77 = mysql_query("SELECT fz_id,fz_nazwa,fz_telefon,fz_fax,fz_email,fz_www FROM $dbname.serwis_fz WHERE ((fz_is_fs='on') and (fz_id='$staryserwis')) LIMIT 1", $conn) or die($k_b);
			list($temp_id77,$temp_nazwa77,$temp_telefon77,$temp_fax77,$temp_email77,$temp_www77)=mysql_fetch_array($result77);
			td(";r;Stary serwis");
			td_(";;");
				echo "<b>$temp_nazwa77</b>";
				echo "<input type=hidden name=staryserwis value='$temp_nazwa77'>";
			_td();
		_tr();
		tr_();
			td(";r;Wybierz nowy serwis");
			td_(";;");
				$result = mysql_query("SELECT fz_id,fz_nazwa FROM $dbname.serwis_fz WHERE (fz_is_fs='on') ORDER BY fz_nazwa ASC", $conn) or die($k_b);
				echo "<select name=tfs>\n"; 	
				echo "<option value='-1' SELECTED>Wybierz z listy...</option>\n";
				while (list($temp_id1,$temp_nazwa1)=mysql_fetch_array($result)) {
					echo "<option value='$temp_nazwa1'";
					if ($temp_nazwa1==$fs) echo " SELECTED";
					echo ">$temp_nazwa1</option>\n"; 
				}
				echo "</select>\n"; 
			_td();
		_tr();
	if ($_REQUEST[lokalny]!=1) {
		tr_();
			td(";r;Wybierz firmę kurierską");
			td_(";;");
				$result = mysql_query("SELECT fz_id,fz_nazwa FROM $dbname.serwis_fz WHERE (fz_is_fk='on') ORDER BY fz_nazwa ASC", $conn) or die($k_b);
				echo "<select name=tfk>\n"; 					 				
				echo "<option value=-1>nie dotyczy</option>\n";
				while (list($temp_id2,$temp_nazwa2)=mysql_fetch_array($result)) {
					echo "<option value='$temp_nazwa2'";
					if ($temp_nazwa2==$fk) echo " SELECTED";
					echo ">$temp_nazwa2</option>\n"; 
				}
				echo "</select>\n"; 
			_td();
		_tr();
		tr_();
			td(";r;Numer listu przewozowego");
			td_(";;;");
				echo "<input type=text name=tnlp value='$fknlp'>";
			_td();
		_tr();
	} else {
		echo "<input type=hidden name=tfk value=''>";
		echo "<input type=hidden name=tnlp value=''>";
	}
		tr_();
			td(";r;Nowy termin naprawy");
			td_(";;;");
				$rok = Date('Y');
				$miesiac = Date('m');
				$dzien = Date('d');			
				$dzien+=14;
				$datacala1  = mktime (0,0,0,date("m")  ,date("d")+14,date("Y"));
				$datacala = date("Y-m-d",$datacala1);
				echo "<input size=10 maxlength=10 type=text id=ttn name=ttn value=$datacala onkeypress='return handleEnter(this, event);' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\">";
				echo "<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif width=16 height=16 border=0></a>";
				if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('ttn').value='".Date('Y-m-d')."'; return false;\">";
			_td();
		_tr();
	tbl_empty_row();
	endtable();
	
	startbuttonsarea("right");
	addbuttons("zapisz");
	addbuttons("anuluj");
	endbuttonsarea();
	//echo "<input size=30 type=hidden name=nid value='$nid'>";
	echo "<input type=hidden name=uid value='$nid'>";
	echo "<input type=hidden name=ew_id value='$_POST[ew_id]'>";
	echo "<input type=hidden name=ow value='$currentuser'>";
	echo "<input type=hidden name=dw value='$dddd'>";
	echo "<input type=hidden name=stat value='$_POST[tstatus1]'>";
	_form();
}
?>
<script language="JavaScript">
var cal1 = new calendar1(document.forms['edu'].elements['ttn']);
	cal1.year_scroll = true;
</script>
</body>
</html>