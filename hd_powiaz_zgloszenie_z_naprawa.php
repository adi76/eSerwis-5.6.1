<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php
if ($submit) {

} else {

	$cat=$_POST['cat'];
	pageheader("Powiązanie zgłoszenia nr ".$_REQUEST[nr]." z naprawą");

	echo "<span id=block_1a>";
	
		starttable();
		echo "<form name=addt action=z_naprawy_wybierz.php method=POST>";
		tbl_empty_row(2);
			tr_();
				td_(";c;;");	
						$sql_1 = "SELECT naprawa_nazwa, naprawa_model, naprawa_sn, naprawa_ni FROM $dbname.serwis_naprawa WHERE (UCASE(naprawa_pobrano_z)='".$_REQUEST[komorka]."') and (belongs_to=$es_filia) ";
						
						if ($_REQUEST[tpk]=='3') $sql_1 .= " AND ((naprawa_nazwa='Komputer') OR (naprawa_nazwa='Monitor')) ";
						if ($_REQUEST[tpk]=='4') $sql_1 .= " AND ((naprawa_nazwa='Serwer') OR (naprawa_nazwa='Monitor')) ";
						if ($_REQUEST[tpk]=='9') $sql_1 .= " AND (naprawa_nazwa<>'Komputer') AND (naprawa_nazwa<>'Serwer') ";
						if ($_REQUEST[tpk]=='0') $sql_1 .= " AND ((naprawa_nazwa='Switch') OR (naprawa_nazwa='Router') OR (naprawa_nazwa='Inne')) ";
						
						if (($_REQUEST[ts]=='3B') || ($_REQUEST[ts]=='7')) $sql_1 .= " AND ((naprawa_status='-1') OR (naprawa_status='0')) ";
						if (($_REQUEST[ts]=='3A') || ($_REQUEST[ts]=='7')) $sql_1 .= " AND ((naprawa_status='1') OR (naprawa_status='2')) ";
						
						$sql_1 .= " ORDER BY naprawa_nazwa ASC";
						
						echo $sql_1;
						
						$result = mysql_query($sql_1, $conn) or die($k_b);
						$count_rows = mysql_num_rows($result);
						
						echo "<select class=wymagane name=typid onkeypress='return handleEnter(this, event);'>\n";
						echo "<option value=''>Wybierz z listy...";
						
						$t = 0;
						while (list($temp_nazwa,$temp_model, $temp_sn,$temp_ni) = mysql_fetch_array($result)) {
							echo "<option value=$temp_id";				
							echo ">$temp_nazwa $temp_model";

							if ($temp_sn!='') echo " | SN: $temp_sn";
							if ($temp_ni!='') echo " | NI: $temp_ni";
							
							echo "</option>\n"; 
							$t++;
						}
						echo "</select>\n"; 
					
					
				_td();
			_tr();
		tbl_empty_row(2);
		endtable();

	echo "</span>";

	echo "<span id=block_2 style='display:none'>";
		errorheader('Brak uszkodzonego sprzętu w bazie z wybranej komórki');
	echo "</span>";

	startbuttonsarea("right");
	
	echo "<span id=block_3 style='display:none'>";
		addbuttons("zapisz");
	echo "</span>";	
	
	addbuttons("zamknij");
	endbuttonsarea();

	_form();
}

if ($t==0) {
?>
<script>
document.getElementById('block_1').style.display='none';
document.getElementById('block_2').style.display='';
document.getElementById('block_3').style.display='none';
</script>
<?php } ?>
</body>
</html>