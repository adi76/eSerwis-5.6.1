<?php include_once('header.php');
include_once('cfg_helpdesk.php');
?>

<body OnLoad="document.forms[0].elements[1].focus();" />
<?php

	//echo $_REQUEST[action];
	//nowalinia();
	pageheader("Szczegóły oferty");
	
	$r4 = mysql_query("SELECT oferta_id,oferta_data,oferta_numer,oferta_uwagi FROM $dbname_hd.hd_zgloszenie_oferty WHERE (oferta_zgl_szcz_id='$_GET[zglszczid]') and (oferta_widoczna=1) and (belongs_to=$es_filia) LIMIT 1", $conn_hd) or die($k_b);
	list($Oid,$Odata,$Onumer,$Ouwagi)=mysql_fetch_array($r4);

	if ($Odata=='') $Odata='-';
	if ($Onumer=='') $Onumer='-';
	if ($Ouwagi=='') $Ouwagi='-';
	
	starttable();
	tbl_empty_row(1);
		tr_();
			td("150;rt;Data wysłania oferty");
			td_(";;;");	
				echo "<b>".$Odata."</b>";
			_td();
		_tr();	
		tr_();
			td("150;rt;Numer oferty");
			td_(";;;");	
				echo "<b>".$Onumer."</b>";
			_td();
		_tr();
		tr_();
			td("150;rt;Uwagi dot. oferty");
			td_(";;;");	
				echo "<b>".nl2br(wordwrap($Ouwagi, 60, "<br />"))."</b>";
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