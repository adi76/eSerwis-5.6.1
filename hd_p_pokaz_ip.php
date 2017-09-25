<?php include_once('header.php');
include_once('cfg_helpdesk.php');
?>

<body OnLoad="document.forms[0].elements[1].focus();" />
<?php

	//echo $_REQUEST[action];
	//nowalinia();
	pageheader("Adres IP podsieci dla komórki");
	infoheader("<b>".$_REQUEST[komorkanazwa]."</b>");
	
	$r44 = mysql_query("SELECT up_id FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((serwis_komorki.belongs_to=$es_filia) and (serwis_piony.pion_id=serwis_komorki.up_pion_id) and (CONCAT(serwis_piony.pion_nazwa,' ',serwis_komorki.up_nazwa)='$_REQUEST[komorkanazwa]'))", $conn) or die($k_b);
	list($_upid)=mysql_fetch_array($r44);

	$r4 = mysql_query("SELECT up_ip FROM $dbname.serwis_komorki WHERE ((belongs_to=$es_filia) and (up_active=1) and (up_id=$_upid)) LIMIT 1", $conn) or die($k_b);
	
	list($kip)=mysql_fetch_array($r4);
	
	if ($kip=='') $kip = 'nie zdefiniowany';
	
	starttable("99%");
	tbl_empty_row(1);
		tr_();
			td("260;r;Adres IP podsieci:");
			td_(";;;");	
				echo "<b>".$kip."</b>";
			_td();
		_tr();		
	tbl_empty_row(1);
	endtable();
	startbuttonsarea("right");
	echo "<input class=buttons type=button onClick=\"self.close();\" value=Zamknij />";
	endbuttonsarea();
	
?>
</body>
</html>