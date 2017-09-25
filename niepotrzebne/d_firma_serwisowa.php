<?php include_once('header.php'); ?>
<body OnLoad="document.add.dnf.focus();">
<?php 
if ($submit) { 
	if (($_POST['dnf']!='')) {
	$sql_a = "INSERT INTO $dbname.serwis_firmy_serwisowe VALUES ('','$_POST[dnf]','$_POST[dadres]','$_POST[dtelefon]','$_POST[dfax]','$_POST[demail]','$_POST[dwww]','$es_filia','$_POST[dsprzet]')";
	
	if (mysql_query($sql_a, $conn)) 
		{ 
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
echo "<h4>Dodawanie nowej firmy serwisowej</h4>";
	
echo "<table cellspacing=1 align=center>";
echo "<form name=add action=$PHP_SELF method=POST>";
tbl_empty_row();

echo "<tr>";
echo "<td width=100 class=right>Nazwa firmy</td>";
echo "<td><input id=serwisowa class=wymagane size=30 maxlength=100 type=text name=dnf onKeyUp='slownik_serwisow()'>";
echo "<img name=status src=img//none.gif>";
		echo "<select name=lista style='display:none' onkeypress='return handleEnter(this, event)'>";
		
		$sql="SELECT fs_nazwa FROM $dbname.serwis_firmy_serwisowe ORDER BY fs_nazwa";
		$result=mysql_query($sql,$conn);
		
		while ($dane=mysql_fetch_array($result)) {
			$temp = $dane['fs_nazwa'];
			echo "<option value=$temp>$temp</option>\n";
		}
		echo "</select>";		
		echo "</td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td width=100 class=right>Adres</td>";
		echo "<td><input size=30 maxlength=100 type=text name=dadres onkeypress='return handleEnter(this, event)'></td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td width=100 class=right>Telefon</td>";
		echo "<td><input size=30 maxlength=100 type=text name=dtelefon onkeypress='return handleEnter(this, event)'></td>";
	echo "</tr>";	
	echo "<tr>";
		echo "<td width=100 class=right>Fax</td>";
		echo "<td><input size=30 maxlength=100 type=text name=dfax onkeypress='return handleEnter(this, event)'></td>";
	echo "</tr>";	
	echo "<tr>";
		echo "<td width=100 class=right>e-mail</td>";
		echo "<td><input size=40 maxlength=100 type=text name=demail onkeypress='return handleEnter(this, event)'></td>";
	echo "</tr>";	
	echo "<tr>";
		echo "<td width=100 class=right>Strona WWW</td>";
		echo "<td><input size=30 maxlength=100 type=text name=dwww onkeypress='return handleEnter(this, event)'></td>";
	echo "</tr>";	
	echo "<tr>";
		echo "<td width=100 class=right>Serwisowany sprzêt</td>";
		echo "<td><input size=45 maxlength=100 type=text name=dsprzet onkeypress='return handleEnter(this, event)'></td>";
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
  frmvalidator.addValidation("dnf","req","Nie podano nazwy firmy serwisowej");
  frmvalidator.addValidation("demail","email","B³êdnie podany adres email");
</script>	
<?php } ?>
</body>
</html>