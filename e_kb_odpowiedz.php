<?php include_once('header.php'); ?>
<body onLoad="document.forms[0].elements[0].focus();">
<?php 
if (($submit) && ($co=='status')) {
$nowystatus = $value;
$_POST=sanitize($_POST);
$sql_a = "UPDATE $dbname.serwis_kb_odpowiedzi SET kb_pytanie_status=$nowystatus WHERE kb_pytanie_id=$id LIMIT 1";

	if (mysql_query($sql_a, $conn)) 
		{ 
			?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else 
		{
		  ?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
		}
exit;
}

if (($submit) && ($co=='dane')) { 
	$_POST=sanitize($_POST);
	$remoteIP = $_SERVER['REMOTE_ADDR']; 
	if (strstr($remoteIP, ', ')) { 
	   $ips = explode(', ', $remoteIP); 
	   $remoteIP = $ips[0]; 
	} 

	$dddd = date("Y-m-d H:i:s");
	
	$sql_a = "UPDATE $dbname.serwis_kb_odpowiedzi SET kb_user_ip='$remoteIP', kb_user_id=$es_nr, kb_odpowiedz_data='$dddd', kb_odpowiedz_tresc='".nl2br($_POST[eo])."', kb_odpowiedz_status=$_POST[dnos] WHERE kb_odpowiedz_id=$oid LIMIT 1";
	if (mysql_query($sql_a, $conn)) 
		{ 
			?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else 
		{
		  ?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
		}
	
} else { ?>
		
<?php

$sql1="SELECT * FROM $dbname.serwis_kb_odpowiedzi WHERE kb_odpowiedz_id=$id LIMIT 1";

$result1 = mysql_query($sql1, $conn) or die($k_b);

$newArray1 = mysql_fetch_array($result1);
$temp_odp_id 		= $newArray1['kb_odpowiedz_id'];
$temp_pyt_id		= $newArray1['kb_pytanie_id'];
$temp_tresc			= $newArray1['kb_odpowiedz_tresc'];
$temp_status		= $newArray1['kb_odpowiedz_status'];	

pageheader("Edycja odpowiedzi");

	starttable();
	echo "<form name=add action=$PHP_SELF method=POST>";
	echo "<input type=hidden name=co value=$co>";
	echo "<input type=hidden name=pid value=$temp_pyt_id>";	
	echo "<input type=hidden name=oid value=$temp_odp_id>";	
	tbl_empty_row();

	echo "<tr>";
		echo "<td width=100 class=righttop>Odpowiedź</td>";
		echo "<td><textarea rows=11 cols=58 name=eo>".br2nl($temp_tresc)."</textarea></td>";
	echo "</tr>";	

	echo "<tr>";
		echo "<td width=100 class=right>Status</td>";
		echo "<td>";

		// -
		// access control 
		$accessLevels = array("9");
		if(array_search($es_prawa, $accessLevels)>-1)
		{
			echo "<select name=dnos onkeypress='return handleEnter(this, event);'>";
			echo "<option value=1 "; if ($temp_status==1) echo "SELECTED "; echo ">Aktywna</option>\n";
			echo "<option value=0 "; if ($temp_status==0) echo "SELECTED "; echo ">Nieaktywna</option>\n";
			echo "</select>";
		} else {
 		// access control koniec
		// -
		echo "<b>Aktywne</b>";
		echo "<input type=hidden name=dnos value=$temp_status>";
		}
	echo "</td>";
	echo "</tr>";	
	tbl_empty_row();
	endtable();

	startbuttonsarea("right");
	addbuttons("zapisz","anuluj");
	endbuttonsarea();
	
	_form();

?>
<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("add");
  
  frmvalidator.addValidation("eo","req","Nie podano odpowiedzi");
//  frmvalidator.addValidation("dnko","req","Nie podano opisu kategorii");

</script>	
<?php } ?>

</body>
</html>