<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php
if ($_REQUEST[from]!='simple') br();
pageheader("Ewidencja sprzętu - wybór lokalizacji");
starttable("auto");
echo "<form name=ewidlok action=p_ewidencja.php method=GET>";
tbl_empty_row();
tr_();
	td(";r;Pokaż sprzęt z lokalizacji");	
	td_(";;");	
		//$result44 = mysql_query("SELECT up_id,up_nazwa FROM $dbname.serwis_komorki WHERE (belongs_to=$es_filia) and (up_active=1) ORDER BY up_nazwa", $conn) or die($k_b);
		
		$result44 = mysql_query("SELECT serwis_komorki.up_id, serwis_komorki.up_nazwa, serwis_piony.pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) ORDER BY serwis_piony.pion_nazwa,up_nazwa", $conn) or die($k_b);

		echo "<select name=sel_up>\n"; 					 						
		while (list($temp_id,$temp_nazwa,$temp_pion)=mysql_fetch_array($result44)) {
			echo "<option value='$temp_id' ";
			if (($sel_up>0) and ($temp_id==$sel_up)) echo "SELECTED";
			echo ">$temp_pion $temp_nazwa</option>\n"; 
		}
		echo "</select>\n"; 
	_td();
_tr();
tr_();
	td(";r;Widok");
	td_(";;");
		echo "<select name=view>\n"; 					 				
		echo "<option value='all'> szczegółowy </option>\n"; 
		echo "<option value='simple'> prosty </option>\n"; 
		echo "</select>\n"; 
	_td();
_tr();
tbl_empty_row();	
endtable();
echo "<input type=hidden name=action value='ewid_all'>";
echo "<input type=hidden name=printpreview value=0>";
echo "<input type=hidden name=allowback value=1>";
startbuttonsarea("center");
addownsubmitbutton("'Pokaż sprzęt'","submit");
endbuttonsarea();
_form();
?>
</body>
</html>