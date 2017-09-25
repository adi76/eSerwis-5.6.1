<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php

if ($wylacz_rejestracje_awarii_wan_z_menu_glownego==1) {
	errorheader("Rejestracja awarii WAN dostępna jest tylko przez zgłoszenie w bazie Helpdesk");
	startbuttonsarea("right");
	
	echo "<span style='float:left'>";
	echo "<input type=button class=buttons value='Przejdź do rejestracji nowego zgłoszenia Helpdesk' onClick=\"self.close(); newWindow_r(800,600,'hd_d_zgloszenie.php'); \">";
	addownlinkbutton("'Przeglądaj zgłoszenia'","button","button","self.close(); if (opener) opener.location.href='hd_p_zgloszenia.php';");
	echo "</span>";
	
	addbuttons("zamknij");
	endbuttonsarea();	
} else { 
	pageheader("Rejestracja awarii łącza WAN");
	infoheader("Numer telefonu do złaszania awarii : <b>".$nr_telefonu_do_tpsa."</b><br />Numer klienta : <b>".$nr_klienta."</b><br />");

	starttable();
	echo "<form name=aw action=d_awaria_zapisz.php method=POST>";
	tbl_empty_row();
	tr_();
		td("150;r;Miejsce awarii");
		td_(";l;");
			//$result44 = mysql_query("SELECT up_id, up_ip, up_nazwa, up_nrwanportu FROM $dbname.serwis_komorki WHERE ((belongs_to=$es_filia) and (up_nrwanportu<>'') and (up_active=1)) ORDER BY up_nazwa", $conn) or die($k_b);
			
			$result44 = mysql_query("SELECT serwis_komorki.up_id,serwis_komorki.up_ip, serwis_komorki.up_nazwa, serwis_piony.pion_nazwa, serwis_komorki.up_nrwanportu FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) and (serwis_komorki.up_nrwanportu<>'') ORDER BY serwis_piony.pion_nazwa,up_nazwa", $conn) or die($k_b);
			
			echo "<select name=tup onkeypress='return handleEnter(this, event);'>\n";
			while (list($temp_id,$temp_ip,$temp_nazwa,$temp_pion,$temp_nrwanportu)=mysql_fetch_array($result44)) {
				echo "<option value='$temp_nazwa'>$temp_pion $temp_nazwa / $temp_nrwanportu</option>\n";
			}
			echo "</select>\n"; 
		_td();
	_tr();

	$result445 = mysql_query("SELECT up_nrwanportu, up_ip FROM $dbname.serwis_komorki WHERE ((belongs_to=$es_filia) and (up_nazwa='$temp_nazwa')) LIMIT 1", $conn) or die($k_b);
	list($temp1_nrwanportu,$temp1_ip)=mysql_fetch_array($result445);
	tbl_empty_row();
	echo "<input type=hidden name=wanport value='$temp_nrwanportu'>";
	echo "<input type=hidden name=tip value=$temp_ip>";
	endtable();
	startbuttonsarea("right");
	addbuttons("dalej1","anuluj");
	endbuttonsarea();
	_form();
}
?>
</body>
</html>