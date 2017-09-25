<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php
$cat=$_POST['cat'];
pageheader("Przyjęcie uszkodzonego sprzętu do serwisu");
starttable();
echo "<form name=addt action=z_naprawy_wybierz_szczecin.php method=POST>";
tbl_empty_row(2);
	tr_();
		td("200;r;Sprzęt pobrano z");
		td_(";;;");
			$result44 = mysql_query("SELECT serwis_komorki.up_id, serwis_komorki.up_nazwa, serwis_piony.pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) ORDER BY serwis_piony.pion_nazwa,up_nazwa", $conn) or die($k_b);
			$count_rows = mysql_num_rows($result44);				
			echo "<select class=wymagane name=upid onkeypress='return handleEnter(this, event);' onchange=\"reload_z_naprawy_przyjmij(this.form)\">\n"; 					 				
			echo "<option value=''>Wybierz z listy...</option>\n";	
			while (list($temp_id,$temp_nazwa,$temp_pion) = mysql_fetch_array($result44)) {
				echo "<option value=$temp_id ";
				if ($upid==$temp_id) echo "SELECTED";
				echo ">$temp_pion $temp_nazwa</option>\n"; 
			}
			echo "</select>\n"; 			
		_td();
	_tr();
	tr_();
		td("200;r;Typ sprzętu");
		td_(";;;");
			$result = mysql_query("SELECT rola_id,rola_nazwa FROM $dbname.serwis_slownik_rola WHERE rola_do_ewidencji=1 ORDER BY rola_nazwa", $conn) or die($k_b);
			$count_rows = mysql_num_rows($result);
			echo "<select class=wymagane name=typid onkeypress='return handleEnter(this, event);' onchange=\"reload_z_naprawy_przyjmij(this.form)\">\n";
			echo "<option value=''>Wybierz z listy...";
			while (list($temp_id,$temp_nazwa) = mysql_fetch_array($result)) {
				echo "<option value=$temp_id";
				if ($temp_id==$typid) echo " SELECTED";
				echo ">$temp_nazwa</option>\n"; 
			}
			echo "</select>\n"; 
		_td();
	_tr();
tbl_empty_row(2);
endtable();
startbuttonsarea("right");
addownlinkbutton2("'Sprzęt poza ewidencją'","noauto","submit1","z_naprawy_uszkodzony_szczecin.php?id=0&auto=0&cat=&typid=0&clear_typ=1");
//addownsubmitbutton("'Wybierz z ewidencji'","submit");
addbuttons("anuluj");
endbuttonsarea();
echo "<input type=hidden name=tuser value='$currentuser'>";
_form();
?>
<?php if ($noauto!='Sprzęt poza ewidencją') { ?>
<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("addt");
  frmvalidator.addValidation("upid","dontselect=0","Nie wybrałeś komórki z której pobrano sprzęt");  
  frmvalidator.addValidation("typid","dontselect=0","Nie wybrałeś typu sprzętu");  
</script>
<?php } ?>
</body>
</html>