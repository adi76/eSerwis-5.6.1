<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php
include_once('cfg_helpdesk.php');
pageheader("Wybierz pracownika danej komórki");
starttable();
echo "<form name=wybierz_pracownika>";
tbl_empty_row(2);
	tr_();
		td("20;r;");
		td_(";;;");
		
			echo "<select name=pracownik onChange=\"sendValue(this.form.pracownik);\">\n"; 		
		
			$sql44 = "SELECT hd_komorka_pracownicy_default_nazwa FROM $dbname_hd.hd_komorka_pracownicy_default ORDER BY hd_komorka_pracownicy_default_nazwa ASC";
			$result44 = mysql_query($sql44,$conn_hd);		
			$count_rows = mysql_num_rows($result44);
			if ($count_rows>0) { 
				while ($newArray = mysql_fetch_array($result44)) {
					$temp_nazwa = $newArray['hd_komorka_pracownicy_default_nazwa'];
					echo "<option value='$temp_nazwa'";
					if ($temp_nazwa=="Poczta") echo " SELECTED";
					echo ">$temp_nazwa</option>\n"; 
				}
			} 
		
			echo "<option value=''>-</option>\n";
			
			if ($_GET[komorka]!='') {
				$sql44 = "SELECT hd_komorka_pracownicy_nazwa FROM $dbname_hd.hd_komorka_pracownicy WHERE (hd_serwis_komorka_id=$_GET[komorka]) ORDER BY hd_komorka_pracownicy_nazwa ASC";
				$result44 = mysql_query($sql44,$conn_hd);		
				$count_rows = mysql_num_rows($result44);				 						
				while ($newArray = mysql_fetch_array($result44)) {
					$temp_nazwa = $newArray['hd_komorka_pracownicy_nazwa'];
					echo "<option value='$temp_nazwa'>$temp_nazwa</option>\n"; 
				}
					
			}
			echo "</select>\n";
			
		
		echo "<input class=buttons type=button value=\"Wybierz\" onClick=\"sendValue(this.form.pracownik);\">";
		addbuttons("anuluj");
			
		_td();
	_tr();
tbl_empty_row(2);
endtable();

_form();
?>
</body>
</html>