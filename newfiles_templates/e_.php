<?php include_once('header.php'); ?>
<body onLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) { 
	$sql_a = "UPDATE $dbname.serwis_**** SET filia_nazwa='$_POST[dnf]', filia_adres='$_POST[dna]', filia_kontakt='$_POST[dfk]', filia_leader=$_POST[dfkier], filia_skrot='$_POST[dfs]' WHERE filia_id=$_POST[id] LIMIT 1";
	
	$es_skrot='$_POST[dfs]';
	
	if (mysql_query($sql_a, $conn)) 
		{ 
			?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else 
		{
		  ?><script>info('Wyst¹pi³ b³¹d podczas zapisywania zmian do bazy'); self.close(); </script><?php
		}
	
} else { ?>
		
<?php

echo "<h4>******</h4>";

$sql_e = "SELECT * FROM $dbname.serwis_filie WHERE (filia_id=$id) LIMIT 1";
$result = mysql_query($sql_e, $conn) or die(mysql_error());
while ($newArray = mysql_fetch_array($result)) {
	$temp_id  			= $newArray['filia_id'];
	$temp_nazwa			= $newArray['filia_nazwa'];
	$temp_adres			= $newArray['filia_adres'];
	$temp_kontakt		= $newArray['filia_kontakt'];
	$temp_kierownik_id	= $newArray['filia_leader'];
	$temp_skrot			= $newArray['filia_skrot'];
}

	echo "<table cellspacing=1 align=center>";
	echo "<form name=add action=$PHP_SELF method=POST>";
	echo "<input type=hidden name=id value=$temp_id>";

	tbl_empty_row();
	echo "<tr>";
		echo "<td width=100 class=right>Nazwa filii</td>";
		
		echo "<td><input id=umowa class=wymagane size=40 maxlength=30 type=text name=dnf value='$temp_nazwa' onKeyUp='slownik_filie()'>";

		echo "<img name=status src=img//none.gif>";
		echo "<select name=lista style='display:none'>";
		
		$sql="SELECT filia_nazwa FROM $dbname.serwis_filie";
		$result=mysql_query($sql,$conn);
		
		while ($dane=mysql_fetch_array($result)) {
			$temp = $dane['filia_nazwa'];
			if ($temp!=$temp_nazwa) echo "<option value='$temp'>$temp</option>\n";			
		}
		echo "</select>";		
		echo "</td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td width=100 class=right>Adres</td>";
		echo "<td><input size=40 maxlength=200 type=text name=dna value='$temp_adres' onkeypress='return handleEnter(this, event)'></td>";
	echo "</tr>";	
	echo "<tr>";
		echo "<td width=100 class=right>Telefon/Fax</td>";
		echo "<td><input size=25 maxlength=50 type=text name=dfk value='$temp_kontakt' onkeypress='return handleEnter(this, event)'></td>";
	echo "</tr>";	
	echo "<tr>";
		echo "<td width=100 class=right>Kierownik</td>";
		echo "<td>";
		echo "<select class=wymagane name=dfkier onkeypress='return handleEnter(this, event)'>";
		echo "<option value=''>Wybierz z listy</option>\n";
		
		$sql="SELECT user_id, user_first_name, user_last_name FROM $dbname.serwis_uzytkownicy ORDER BY user_login ASC";
		$result=mysql_query($sql,$conn);
		
		while ($dane=mysql_fetch_array($result)) {
			$temp_fid	= $dane['user_id'];
			$temp_i 	= $dane['user_first_name'];
			$temp_n 	= $dane['user_last_name'];
			echo "<option ";
			
			if ($temp_fid==$temp_kierownik_id) { echo "SELECTED "; }
			
			echo "value=$temp_fid>$temp_i $temp_n</option>\n";
		}
		echo "</select>";		
		
		
		echo "</td>";
	echo "</tr>";	

	echo "<tr>";
		echo "<td width=100 class=right>Skrót nazwy</td>";
		echo "<td><input size=10 maxlength=10 type=text name=dfs value='$temp_skrot' onkeypress='return handleEnter(this, event)'></td>";
	echo "</tr>";
	tbl_empty_row();	

	echo "</table>";

	startbuttonsarea("right");
	addbuttons("zapisz","anuluj");
	endbuttonsarea();
	
	echo "</form>";

?>
<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("add");
  
  frmvalidator.addValidation("dnf","req","Nie podano nazwy filii");
  frmvalidator.addValidation("dfkier","dontselect=0","Nie wybra³eœ kierownika");

  
</script>	
<?php } ?>

</body>
</html>