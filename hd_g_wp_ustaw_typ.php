<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body onLoad="document.getElementById('uwagi').focus();">
<?php 

if ($submit) {

	if ($_REQUEST[pocztowy]=='1') {
		$sql_d1="UPDATE $dbname_hd.hd_zgl_wymiany_podzespolow SET wp_sprzet_pocztowy=1, wp_sprzet_pocztowy_uwagi='$_REQUEST[uwagi]' WHERE (wp_id='$_REQUEST[wpid]') LIMIT 1";

		if (mysql_query($sql_d1, $conn_hd)) { 
			?><script>
				self.close(); 
				if (opener) opener.location.reload(true); </script><?php
		} else { 
			?><script>alert('Wystąpił błąd podczas wykonywania powiązania'); //self.close(); </script><?php
		}
	} else {
		$sql_d1="UPDATE $dbname_hd.hd_zgl_wymiany_podzespolow SET wp_sprzet_pocztowy=0 WHERE (wp_id='$_REQUEST[wpid]') LIMIT 1";

		if (mysql_query($sql_d1, $conn_hd)) { 
			?><script>
				self.close(); 
				if (opener) opener.location.reload(true); </script><?php
		} else { 
			?><script>alert('Wystąpił błąd podczas wykonywania powiązania'); self.close(); </script><?php
		}

	}
	
} else {
	if ($_REQUEST[pocztowy]==1) {
		errorheader("Czy ustawić typ podzespołu <font color=white>".$_REQUEST[typ]."</font> jako 'pocztowy'");
	} else {
		errorheader("Czy ustawić typ podzespołu <font color=white>".$_REQUEST[typ]."</font> jako 'nowy'");
	}
	
	echo "<form action=$PHP_SELF method=POST>";
	
	if ($_REQUEST[pocztowy]==1) {
		starttable();
		tbl_empty_row();
			tr_();
				td(";rt;Uwagi do sprzętu");
				td_(";;");
					echo "<textarea id=uwagi name=uwagi cols=45 rows=3></textarea>";
				_td();
			_tr();	
		tbl_empty_row();
		endtable();
	} else {
		echo "<input type=hidden name=uwagi value=''>";
		}
		echo "<input type=hidden name=wpid value='$_REQUEST[wpid]'>";
		echo "<input type=hidden name=pocztowy value='$_REQUEST[pocztowy]'>";
		
		startbuttonsarea("center");
		addbuttons("tak","nie");
		endbuttonsarea();
		
	_form();

}
?> 
</body>
</html>