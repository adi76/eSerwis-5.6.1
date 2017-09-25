<?php include_once('header.php');
include_once('cfg_helpdesk.php');
?>

<body OnLoad="document.forms[0].elements[1].focus();" />
<?php

	//echo $_REQUEST[action];
	//nowalinia();
	pageheader("Szczegóły zamówienia");
	
	$r4 = mysql_query("SELECT zam_id,zam_data,zam_numer,zam_uwagi FROM $dbname_hd.hd_zgloszenie_zamowienia WHERE (zam_zgl_szcz_id='$_GET[zglszczid]') and (zam_widoczne=1) and (belongs_to=$es_filia) LIMIT 1", $conn_hd) or die($k_b);
	list($Zid,$Zdata,$Znumer,$Zuwagi)=mysql_fetch_array($r4);

	if ($Zdata=='') $Zdata='-';
	if ($Znumer=='') $Znumer='-';
	if ($Zuwagi=='') $Zuwagi='-';
	
	starttable();
	tbl_empty_row(1);
		tr_();
			td("150;rt;Data wysłania zamówienia<br />do realizacji");
			td_(";;;");	
				echo "<b>".$Zdata."</b>";
			_td();
		_tr();
		tr_();
			td("150;rt;Numer zamówienia");
			td_(";;;");	
				echo "<b>".$Znumer."</b>";
			_td();
		_tr();
		tr_();
			td("150;rt;Uwagi dot. zamówienia");
			td_(";;;");	
				echo "<b>".nl2br(wordwrap($Zuwagi, 60, "<br />"))."</b>";
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