<?php include_once('header.php'); ?>
<body onLoad="document.getElementById('dno').focus();">
<?php 
if ($submit) { 
	$_POST=sanitize($_POST);
	$remoteIP = $_SERVER['REMOTE_ADDR']; 
	if (strstr($remoteIP, ', ')) { 
	   $ips = explode(', ', $remoteIP); 
	   $remoteIP = $ips[0]; 
	} 

	$dddd = date("Y-m-d H:i:s");
	
	$sql_a = "INSERT INTO $dbname.serwis_kb_odpowiedzi VALUES ('',$_POST[id],$es_nr,'$remoteIP','$dddd','".nl2br($_POST[dno])."', '',$_POST[dnos],0,'')";
	
	if (mysql_query($sql_a, $conn)) 
		{ 
			?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else 
		{
		  ?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
		}
	
} else { 
$sql1="SELECT * FROM $dbname.serwis_kb_pytania WHERE kb_pytanie_id=$id LIMIT 1";
$result1 = mysql_query($sql1, $conn) or die($k_b);

$newArray1 = mysql_fetch_array($result1);
$temp_pyt_id 		= $newArray1['kb_pytanie_id'];
$temp_kat_id1		= $newArray1['kb_kategoria_id'];
$temp_temat			= $newArray1['kb_pytanie_temat'];
$temp_tresc			= $newArray1['kb_pytanie_tresc'];
$temp_keywords		= $newArray1['kb_pytanie_keywords'];
$temp_status		= $newArray1['kb_pytanie_status'];	
$temp_file			= $newArray1['kb_pytanie_file_attachement'];	

$sql_e = "SELECT * FROM $dbname.serwis_kb_kategorie WHERE (kb_kategoria_id=$temp_kat_id1) LIMIT 1";
$result = mysql_query($sql_e, $conn) or die($k_b);
$newArray = mysql_fetch_array($result);
$temp_par_id	= $newArray['kb_parent_id'];
$temp_pk_nazwa  = $newArray['kb_kategoria_nazwa'];

if ($temp_par_id!=0) 
{
	$sql_e = "SELECT * FROM $dbname.serwis_kb_kategorie WHERE (kb_kategoria_id=$temp_par_id) LIMIT 1";
	$result = mysql_query($sql_e, $conn) or die($k_b);
	$newArray = mysql_fetch_array($result);
	$temp_id	  	= $newArray['kb_kategoria_id'];
	$temp_par_id	= $newArray['kb_parent_id'];
	$temp_knazwa  	= $newArray['kb_kategoria_nazwa'];	
}

pageheader("Dodawanie odpowiedzi do wątku");

	starttable();
	echo "<form name=add action=$PHP_SELF method=POST onSubmit=\"return pytanie_dodaj_odp_do_kb('Czy dodać odpowiedź do wątku do bazy wiedzy ?'); \">";
	echo "<input type=hidden name=id value=$temp_pyt_id>";
	tbl_empty_row();

	echo "<tr>";
		echo "<td width=100 class=righttop>Temat wątku</td>";
		echo "<td>$temp_temat";
		echo "</td>";
	echo "</tr>";
	
	echo "<tr>";
		echo "<td width=100 class=righttop>Kategoria wątku</td>";
		echo "<td>";
		
		if ($temp_knazwa!='') echo "$temp_knazwa-> ";
		echo "$temp_pk_nazwa";
		
		echo "</td>";
	echo "</tr>";	

	echo "<tr>";
		echo "<td width=100 class=righttop>Opis wątku</td>";
		echo "<td>".($temp_tresc)."";
		echo "</td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td width=100 class=righttop>Odpowiedź</td>";
		echo "<td><textarea class=wymagane rows=8 cols=57 name=dno id=dno></textarea></td>";
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
			echo "<option value=1>Aktywne</option>\n";
			echo "<option value=0>Nieaktywne</option>\n";
			echo "</select>";
		} else {
 		// access control koniec
		// -
		echo "<b>Aktywna</b>";
		echo "<input type=hidden name=dnos value=1>";
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
  
<?php if ($poziom==0) { ?>
  
  frmvalidator.addValidation("dno","req","Nie podano odpowiedzi");
//  frmvalidator.addValidation("dnko","req","Nie podano opisu kategorii");
  
<?php } ?>

<?php if ($poziom==1) { ?>
  
  frmvalidator.addValidation("dno","req","Nie podano odpowiedzi");
//  frmvalidator.addValidation("dnpo","req","Nie podano opisu podkategorii");
  
<?php } ?>

</script>
<?php } ?>

</body>
</html>