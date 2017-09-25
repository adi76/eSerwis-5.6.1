<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php
pageheader("Wybierz UP / komórkę z listy");
starttable();
echo "<form name=wybierz_up>";
tbl_empty_row(2);
	tr_();
		td("20;r;");
		td_(";;;");
			$result44 = mysql_query("SELECT serwis_komorki.up_id, serwis_komorki.up_nazwa, serwis_piony.pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) ORDER BY serwis_piony.pion_nazwa,up_nazwa", $conn) or die($k_b);
			$count_rows = mysql_num_rows($result44);				
			echo "<select class=wymagane name=upid onChange=\"sendValue(this.form.upid);\">\n"; 					 				
			echo "<option value=''>Wybierz z listy...</option>\n";	
			while (list($temp_id,$temp_nazwa,$temp_pion) = mysql_fetch_array($result44)) {
				echo "<option value='$temp_id' ";
				if ($upid==$temp_id) echo "SELECTED";
				echo ">$temp_pion $temp_nazwa</option>\n"; 
			}
			echo "</select>\n";

			echo "<input class=buttons type=button value=\"Wybierz\" onClick=\"sendValue(this.form.upid);\">";
			addbuttons("anuluj");
 			
		_td();
	_tr();
tbl_empty_row(2);
endtable();
_form();
?>
<?php if ($noauto!='Sprzęt poza ewidencją') { ?>
<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("wybierz_up");
  frmvalidator.addValidation("upid","dontselect=0","Nie wybrałeś komórki");    
</script>
<?php } ?>
</body>
</html>