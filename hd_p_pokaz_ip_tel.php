<?php include_once('header.php');
include_once('cfg_helpdesk.php');
?>

<body OnLoad="document.forms[0].elements[1].focus();" />
<?php

	//echo $_REQUEST[action];
	//nowalinia();
	pageheader("Adres IP i telefon");
	infoheader("".$_REQUEST[komorkanazwa]."");
	
	//$SzukajUP = substr($_GET[komorkanazwa],strpos($_GET[komorkanazwa],' ')+1,strlen($_GET[komorkanazwa]));
	$SzukajUP = $_GET[komorkanazwa];
	
	//echo "SELECT up_ip FROM $dbname.serwis_komorki WHERE ((belongs_to=$es_filia) and (up_active=1) and (up_nazwa LIKE '%$SzukajUP%')) LIMIT 1";
	$innafilia = false;
	if ($_REQUEST[f]>0) {
		if (($_REQUEST[f]!=$es_filia)) {
			$r4 = mysql_query("SELECT up_ip,up_telefon FROM $dbname.serwis_komorki,$dbname.serwis_piony WHERE ((belongs_to=$_REQUEST[f]) and (up_active=1) and (CONCAT(pion_nazwa,' ',up_nazwa) LIKE '%$SzukajUP%')) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) LIMIT 1", $conn) or die($k_b);
			$innafilia = true;
		} else {
			$r4 = mysql_query("SELECT up_ip,up_telefon FROM $dbname.serwis_komorki,$dbname.serwis_piony WHERE ((belongs_to=$es_filia) and (up_active=1) and (CONCAT(pion_nazwa,' ',up_nazwa) LIKE '%$SzukajUP%')) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) LIMIT 1", $conn) or die($k_b);
		}
	} else {
		$r4 = mysql_query("SELECT up_ip,up_telefon FROM $dbname.serwis_komorki,$dbname.serwis_piony WHERE ((belongs_to=$es_filia) and (up_active=1) and (CONCAT(pion_nazwa,' ',up_nazwa) LIKE '%$SzukajUP%')) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) LIMIT 1", $conn) or die($k_b);
	}
	
//	$r4 = mysql_query("SELECT up_ip,up_telefon FROM $dbname.serwis_komorki,$dbname.serwis_piony WHERE ((belongs_to=$es_filia) and (up_active=1) and (CONCAT(pion_nazwa,' ',up_nazwa) LIKE '%$SzukajUP%')) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) LIMIT 1", $conn) or die($k_b);
	
	list($kip,$ktel)=mysql_fetch_array($r4);
	
	if ($kip=='') $kip = 'nie zdefiniowany';
	if ($ktel=='') $kip = 'nie zdefiniowany';
	
	starttable("99%");
	tbl_empty_row(1);
		tr_();
			td("150;rt;Adres IP podsieci:");
			td_(";;;");	
				echo "<b>".$kip."</b>";
			_td();
		_tr();
		tr_();
			td("150;rt;Telefon do lokalizacji:");
			td_(";;;");	
				echo "<b>".$ktel."</b>";
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