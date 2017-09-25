<?php include_once('header.php'); ?>
<body onLoad="document.forms[0].elements[0].focus();">
<?php 

if (($submit) && ($co=='status')) {
$_POST=sanitize($_POST);
$nowystatus=$value;

$sql_a = "UPDATE $dbname.serwis_kb_kategorie SET kb_kategoria_status=$nowystatus WHERE kb_kategoria_id=$id LIMIT 1";

	if (mysql_query($sql_a, $conn)) 
		{ 
			?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else 
		{
		  ?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
		}
exit;
}

if (($submit) && ($co=='dostep')) {
$_POST=sanitize($_POST);
$nowydostep=$value;

$sql_a = "UPDATE $dbname.serwis_kb_kategorie SET kb_kategoria_accesible_for=$nowydostep WHERE kb_kategoria_id=$id LIMIT 1";

	if (mysql_query($sql_a, $conn)) 
		{ 
			?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else 
		{
		  ?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
		}
exit;
}

if (($submit) && ($co='dane')) { 
$_POST=sanitize($_POST);
	$sql_a = "UPDATE $dbname.serwis_kb_kategorie SET kb_parent_id=$_POST[dnpk], kb_kategoria_nazwa='$_POST[dnk]', kb_kategoria_opis='".nl2br($_POST[dnko])."', kb_kategoria_status=$_POST[dnks], kb_kategoria_accesible_for=$_POST[dnka] WHERE kb_kategoria_id=$_POST[id] LIMIT 1";

	if (mysql_query($sql_a, $conn)) 
		{ 
			?><script> opener.location.reload(true); self.close(); </script><?php
	} else 
		{
		  ?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
		}
	
} else { ?>
		
<?php

if ($poziom==0) pageheader("Edycja kategorii");
if ($poziom==1) pageheader("Edycja podkategorii");

$sql1="SELECT * FROM $dbname.serwis_kb_kategorie WHERE kb_kategoria_id=$id LIMIT 1";
$result1 = mysql_query($sql1, $conn) or die($k_b);

$newArray1 = mysql_fetch_array($result1);
$temp_kat_id 		= $newArray1['kb_kategoria_id'];
$temp_par_id		= $newArray1['kb_parent_id'];
$temp_nazwa			= $newArray1['kb_kategoria_nazwa'];
$temp_opis			= $newArray1['kb_kategoria_opis'];
$temp_status		= $newArray1['kb_kategoria_status'];
$temp_access_for	= $newArray1['kb_kategoria_accesible_for'];	

	starttable();
	echo "<form name=add action=$PHP_SELF method=POST>";
	echo "<input type=hidden name=co value=$co>";
	echo "<input type=hidden name=id value=$temp_kat_id>";
	tbl_empty_row();
	echo "<tr>";
		echo "<td width=120 class=right>Nazwa</td>";
		echo "<td><input id=umowa class=wymagane size=50 type=text name=dnk value=$temp_nazwa>";
		echo "</td>";
	echo "</tr>";

	echo "<tr>";
		echo "<td width=100 class=right>Kategoria nadrzędna</td>";
		echo "<td>";
		
	if ($poziom==1) 
	{

		echo "<select name=dnpk>";
		echo "<option value=0>Kategoria główna</option>\n";

//if ($poziom==0)	
	$sql="SELECT kb_kategoria_id,kb_kategoria_nazwa FROM $dbname.serwis_kb_kategorie WHERE kb_parent_id=0 ORDER BY kb_kategoria_id";

//if ($poziom==1) $sql="SELECT kb_kategoria_id,kb_kategoria_nazwa FROM $dbname.serwis_kb_kategorie WHERE kb_parent_id<>0 ORDER BY kb_kategoria_id";

		$result=mysql_query($sql,$conn) or die($k_b);
		while ($dane=mysql_fetch_array($result)) 
		{
			$temp_kat_id1	= $dane['kb_kategoria_id'];
			$temp_kat_nazwa1 = $dane['kb_kategoria_nazwa'];
			
			echo "<option ";
			if ($temp_par_id==$temp_kat_id1) echo " SELECTED ";
			echo "value=$temp_kat_id1>$temp_kat_nazwa1</option>\n";
		}
	
		echo "</select>";		
	} else 
	{
		echo "-"; echo "<input type=hidden name=dnpk value=0>";
	}
		
		echo "</td>";
	echo "</tr>";	
	echo "<tr>";
		echo "<td width=100 class=righttop>Opis</td>";
		echo "<td><textarea class=wymagane rows=4 cols=40 name=dnko>".br2nl($temp_opis)."</textarea></td>";
	echo "</tr>";	

	echo "<tr>";
		echo "<td width=100 class=right>Status</td>";
		echo "<td>";
		echo "<select name=dnks onkeypress='return handleEnter(this, event);'>";
		echo "<option value=1 "; if ($temp_status==1) echo "SELECTED"; echo ">Aktywna</option>\n";
		echo "<option value=0 "; if ($temp_status==0) echo "SELECTED"; echo ">Nieaktywna</option>\n";
		echo "</select>";
		echo "</td>";
	echo "</tr>";	

	echo "<tr>";
		echo "<td width=100 class=right>Widoczna dla</td>";
		echo "<td>";
		echo "<select name=dnka onkeypress='return handleEnter(this, event);'>";
		echo "<option value=0 "; if ($temp_access_for==0) echo "SELECTED"; echo ">Wszystkich</option>\n";
		echo "<option value=9 "; if ($temp_access_for==9) echo "SELECTED"; echo ">Administratorów</option>\n";
		echo "</select>";
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
  
  frmvalidator.addValidation("dnk","req","Nie podano nazwy kategorii");
  frmvalidator.addValidation("dnko","req","Nie podano opisu kategorii");
  
</script>	
<?php } ?>

</body>
</html>