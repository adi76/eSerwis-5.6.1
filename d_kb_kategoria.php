<?php include_once('header.php'); ?>
<body onLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) { 
	$_POST=sanitize($_POST);
	$sql_a = "INSERT INTO $dbname.serwis_kb_kategorie VALUES ('',$_POST[dnpk],'$_POST[dnk]','".nl2br($_POST[dnko])."',$_POST[dnks],$_POST[dnka])";
	if (mysql_query($sql_a, $conn)) 
		{ 
			?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else 
		{
		  ?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
		}
} else { ?>
		
<?php

if ($poziom==0) pageheader("Dodawanie nowej kategorii do Bazy Wiedzy");
if ($poziom==1) pageheader("Dodawanie nowej podkategorii do Bazy Wiedzy");
	
	starttable();
	echo "<form name=add action=$PHP_SELF method=POST>";
	tbl_empty_row();
	echo "<tr>";
		echo "<td width=120 class=right>Nazwa</td>";
		echo "<td><input id=umowa class=wymagane size=50 type=text name=dnk>";
		echo "</td>";
	echo "</tr>";

	echo "<tr>";
		echo "<td width=100 class=right>Kategoria nadrzędna</td>";
		echo "<td>";
		
	if ($poziom==1) 
	{
		echo "<select name=dnpk>";
		echo "<option value=0>Kategoria główna</option>\n";

	$sql="SELECT kb_kategoria_id,kb_kategoria_nazwa FROM $dbname.serwis_kb_kategorie WHERE kb_parent_id=0 ORDER BY kb_kategoria_id";

		$result=mysql_query($sql,$conn) or die($k_b);
		while ($dane=mysql_fetch_array($result)) 
		{
			$temp_kat_id 	= $dane['kb_kategoria_id'];
			$temp_kat_nazwa = $dane['kb_kategoria_nazwa'];
			
			echo "<option ";
			if ($id==$temp_kat_id) echo "SELECTED ";
			echo "value=$temp_kat_id>$temp_kat_nazwa</option>\n";
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
		echo "<td><textarea class=wymagane rows=4 cols=40 name=dnko></textarea></td>";
	echo "</tr>";	

	echo "<tr>";
		echo "<td width=100 class=right>Status</td>";
		echo "<td>";
		echo "<select name=dnks onkeypress='return handleEnter(this, event);'>";
		echo "<option value=1>Aktywna</option>\n";
		echo "<option value=0>Nieaktywna</option>\n";
		echo "</select>";
		echo "</td>";
	echo "</tr>";	

	echo "<tr>";
		echo "<td width=100 class=right>Widoczna dla</td>";
		echo "<td>";
		echo "<select name=dnka onkeypress='return handleEnter(this, event);'>";
		echo "<option value=0>Wszystkich</option>\n";
		echo "<option value=9>Administratorów</option>\n";
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
  
<?php if ($poziom==0) { ?>
  
  frmvalidator.addValidation("dnk","req","Nie podano nazwy kategorii");
  frmvalidator.addValidation("dnko","req","Nie podano opisu kategorii");
  
<?php } ?>

<?php if ($poziom==1) { ?>
  
  frmvalidator.addValidation("dnk","req","Nie podano nazwy podkategorii");
  frmvalidator.addValidation("dnko","req","Nie podano opisu podkategorii");
  
<?php } ?>

</script>	
<?php } ?>

</body>
</html>