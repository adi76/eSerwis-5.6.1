<?php include_once('header.php'); ?>
<body OnLoad="document.add.dnf.focus();">
<?php 
if ($submit) { 
	if (($_POST['dnf']!='')) {
	$sql_a = "INSERT INTO $dbname.serwis_firmy_kurierskie VALUES ('','$_POST[dnf]','$_POST[dtelefon]','$_POST[demail]','$_POST[dwww]','$es_filia')";
	if (mysql_query($sql_a, $conn)) { 
			?><script> opener.location.reload(true); self.close(); </script><?php
	} else 
		{
		  ?><script>info('Wyst¹pi³ b³¹d podczas zapisywania zmian do bazy'); self.close(); </script><?php
		}
	} else {	
			?><script>info('Nie wype³ni³eœ wymaganych pól'); self.close(); </script><?php
	}
} else { ?>
		
<?php
echo "<h4>Dodawanie nowej firmy kurierskiej</h4>";
echo "<table cellspacing=1 align=center>";
echo "<form name=add action=$PHP_SELF method=POST>";
tbl_empty_row();

echo "<tr>";
	echo "<td width=100 class=right>Nazwa firmy</td>";

		echo "<td><input id=kurier class=wymagane size=30 maxlength=100 type=text name=dnf onKeyUp='slownik_kurierow()'>";

		echo "<img name=status src=img//none.gif>";
		echo "<select name=lista style='display:none'>";
		
		$sql="SELECT fk_nazwa FROM $dbname.serwis_firmy_kurierskie ORDER BY fk_nazwa";
		$result=mysql_query($sql,$conn);
		
		while ($dane=mysql_fetch_array($result)) {
			$temp = $dane['fk_nazwa'];
			echo "<option value=$temp>$temp</option>\n";
		}
		echo "</select>";		
		echo "</td>";


		echo "</tr>";
	echo "<tr>";
		echo "<td width=100 class=right>Telefon</td>";
		echo "<td><input size=30 maxlength=100 type=text name=dtelefon onkeypress='return handleEnter(this, event)'></td>";
	echo "</tr>";	
	echo "<tr>";
		echo "<td width=100 class=right>e-mail</td>";
		echo "<td><input size=40 maxlength=100 type=text name=demail onkeypress='return handleEnter(this, event)'></td>";
	echo "</tr>";	
	echo "<tr>";
		echo "<td width=100 class=right>Strona WWW</td>";
		echo "<td><input size=30 maxlength=60 type=text name=dwww onkeypress='return handleEnter(this, event)'></td>";
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
  frmvalidator.addValidation("dnf","req","Nie podano nazwy firmy kurierskiej");
  frmvalidator.addValidation("dnf","alnumhyphen","Niedozwolone znaki w nazwie firmy kurierskiej");
</script>
<?php } ?>
</body>
</html>