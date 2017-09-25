<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php
pageheader("Dodawanie nowej czynności do wykonania");
?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
starttable();
echo "<form name=komwyb action=p_komorka_czynnosc.php method=GET>";
tbl_empty_row();
tr_();
	td("200;r;Wybierz komórkę / UP");
	//$result44 = mysql_query("SELECT up_id,up_nazwa FROM $dbname.serwis_komorki WHERE (belongs_to=$es_filia) and (up_active=1) ORDER BY up_nazwa", $conn) or die($k_b);
	$result44 = mysql_query("SELECT serwis_komorki.up_id, serwis_komorki.up_nazwa, serwis_piony.pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) ORDER BY serwis_piony.pion_nazwa,up_nazwa", $conn) or die($k_b);
	
	td_(";l");
		echo "<select name=id>\n"; 					 				
		while (list($temp_id,$temp_nazwa,$temp_pion)=mysql_fetch_array($result44)) { 
			echo "<option value='$temp_id' ";
			if (($sel>0) and ($temp_id==$sel)) echo "SELECTED";
			echo ">$temp_pion $temp_nazwa</option>\n"; 
		}
		echo "</select>\n"; 
	_td();
_tr();
echo "<input type=hidden name=filtruj value='$filtruj'>";
tbl_empty_row();
endtable();
startbuttonsarea("right");
addbuttons("dalej","zamknij");
endbuttonsarea();
_form();
?>
<script>HideWaitingMessage();</script>
</body>
</html>