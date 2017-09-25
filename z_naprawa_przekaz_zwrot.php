<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body>
<?php 
if ($submit) { 
	$dddd = Date('Y-m-d H:i:s');
	$_POST=sanitize($_POST);

	if ($_POST[cs]=='3') {
		$sql_t = "UPDATE $dbname.serwis_naprawa SET naprawa_odbior_z_filii_data='$_POST[ttn]', naprawa_odbior_z_filii_osoba='$currentuser', naprawa_przekazanie_zakonczone=1, naprawa_przekazanie_naprawa_wykonana=1 WHERE (naprawa_id=$_POST[id]) LIMIT 1";
		//echo $sql_t;
	} else {
		$sql_t = "UPDATE $dbname.serwis_naprawa SET naprawa_status='-1', naprawa_odbior_z_filii_data='$_POST[ttn]', naprawa_odbior_z_filii_osoba='$currentuser', naprawa_przekazanie_zakonczone=1, naprawa_przekazanie_naprawa_wykonana=0 WHERE (naprawa_id=$_POST[id]) LIMIT 1";
	}

	if (mysql_query($sql_t, $conn)) { 
		
		if ($_POST[hdzgl]>0) {
			$sql_t = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_przekazane_do='0' WHERE (zgl_id=$_POST[hdzgl]) LIMIT 1";
			$wynik = mysql_query($sql_t, $conn_hd);
		}
		
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else 
		{
		  ?><script>info('Wystąpił błąd podczas wykonania zapytania do bazy'); self.close(); </script><?php		
		}

} else { 

	$result1 = mysql_query("SELECT * FROM $dbname.serwis_naprawa WHERE naprawa_id=$id LIMIT 1", $conn) or die($k_b);
	if (mysql_num_rows($result1)!=0) {

		$dane = mysql_fetch_array($result1);
		$mid 		= $dane['naprawa_id'];						$mnazwa 	= $dane['naprawa_nazwa'];
		$mmodel		= $dane['naprawa_model'];					$msn	 	= $dane['naprawa_sn'];
		$mni		= $dane['naprawa_ni'];						$muwagisa	= $dane['naprawa_uwagi_sa'];
		$muwagi		= $dane['naprawa_uwagi'];					$mup		= $dane['naprawa_pobrano_z'];
		$moo		= $dane['naprawa_osoba_pobierajaca'];		$mdp		= $dane['naprawa_data_pobrania'];
		$mnfs		= $dane['naprawa_fs_nazwa'];				$mnfk		= $dane['naprawa_fk_nazwa'];
		$mnow		= $dane['naprawa_osoba_wysylajaca'];		$mndw		= $dane['naprawa_data_wysylki'];
		$mnnlp		= $dane['naprawa_nr_listu_przewozowego'];	
		$mnptn		= $dane['naprawa_przewidywany_termin_naprawy'];		
		$mnopszs	= $dane['naprawa_osoba_przyjmujaca_sprzet_z_serwisu'];
		$mndozs		= $dane['naprawa_data_odbioru_z_serwisu'];	$mnoos		= $dane['naprawa_osoba_oddajaca_sprzet'];
		$mndos		= $dane['naprawa_data_oddania_sprzetu'];	$mstatus	= $dane['naprawa_status'];
		$mpwzs 		= $dane['naprawa_powod_wycofania_z_serwisu'];
		$mdwzs 		= $dane['naprawa_data_wycofania'];
		$mowzs 		= $dane['naprawa_osoba_wycofujaca_sprzet_z_serwisu'];
		$mtswzs 	= $dane['naprawa_wycofanie_timestamp'];
		$bt = $dane['belongs_to'];
		
		$n_przekaz_do = $dane['naprawa_przekazanie_naprawy_do'];
		$n_przekaz_data = $dane['naprawa_przekazanie_naprawy_data'];
		$n_przekaz_osoba = $dane['naprawa_przekazanie_naprawy_osoba'];
		$n_odbior_data = $dane['naprawa_odbior_z_filii_data'];
		$n_odbior_osoba = $dane['naprawa_odbior_z_filii_osoba'];		
		
		$sql_up = "SELECT up_id, up_pion_id FROM $dbname.serwis_komorki WHERE (up_nazwa='$mup') and (belongs_to=$bt) LIMIT 1";
		$wynik = mysql_query($sql_up, $conn) or die($k_b);
		$dane_up = mysql_fetch_array($wynik);
		$temp_up_id = $dane_up['up_id'];
		$temp_pion_id = $dane_up['up_pion_id'];
		
		$r40 = mysql_query("SELECT filia_nazwa FROM $dbname.serwis_filie WHERE (filia_id='$n_przekaz_do') LIMIT 1", $conn) or die($k_b);
		list($NazwaFilii)=mysql_fetch_array($r40);
		
		$r41 = mysql_query("SELECT filia_nazwa FROM $dbname.serwis_filie WHERE (filia_id='$bt') LIMIT 1", $conn) or die($k_b);
		list($NazwaFiliiZrodlowej)=mysql_fetch_array($r41);
		
		infoheader("$mnazwa <b>$mmodel</b> <br />SN: <b>$msn</b>, NI: <b>$mni</b>");
		
	if ($cs=='3') {
		okheader("Czy napewno chcesz zwrócić naprawiony sprzęt do filii $NazwaFiliiZrodlowej ?");		
	} else {
		errorheader("Czy napewno chcesz zwrócić nienaprawiony sprzęt do filii $NazwaFiliiZrodlowej ?<br /><br />Po przekazaniu sprzętu do filii $NazwaFiliiZrodlowej,<br />status naprawy zmieni się na <font color=white>\"uszkodzony (pobrany)\"</font>");
	}

		startbuttonsarea("center");
		echo "<form name=edu action=$PHP_SELF method=POST>";
		echo "<input type=hidden name=id value=$mid>";	
		echo "<input type=hidden name=cs value=$cs>";	
		
		starttable();
		tbl_empty_row();
			tr_();
				td(";r;Data zwrotu sprzętu");
				td_(";;;");
					$datacala = date("Y-m-d");
					echo "<input size=10 maxlength=10 type=text id=ttn name=ttn value=$datacala onkeypress='return handleEnter(this, event);' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\">";
					echo "<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif width=16 height=16 border=0></a>";
					if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('ttn').value='".Date('Y-m-d')."'; return false;\">";
				_td();
			_tr();
		tbl_empty_row();	
		endtable();
		
		echo "<br />";
		addbuttons("tak","nie");
		endbuttonsarea();

		if ($_REQUEST[hdzgl]>0) {
			echo "<input type=hidden name=hdzgl value='$_REQUEST[hdzgl]'>";
		} else {
			echo "<input type=hidden name=hdzgl value='$_REQUEST[hdzgl]'>";
		}
		
		_form();

	} else {
		errorheader("Brak powiązanej naprawy w bazie. Skontaktuj się z administratorem");
		startbuttonsarea("center");
		addbuttons("anuluj");
		endbuttonsarea();
	}
}
?>
<script language="JavaScript">
var cal1 = new calendar1(document.forms['edu'].elements['ttn']);
	cal1.year_scroll = true;
</script>
</body>
</html>