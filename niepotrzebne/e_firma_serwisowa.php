<?php include_once('header.php'); ?>
<?php echo "<body OnLoad=document.ed.dnf.focus();>"; ?>
<?php 
if ($submit) { 
	if (($_POST['dnf']!='')) {
	$sql_e1="UPDATE $dbname.serwis_firmy_serwisowe SET fs_nazwa = '$_POST[dnf]' , fs_adres = '$_POST[dadres]' , fs_telefon = '$_POST[dtelefon]' , fs_fax = '$_POST[dfax]' , fs_email = '$_POST[demail]' , fs_www = '$_POST[dwww]', belongs_to = '$es_filia', fs_sprzet = '$_POST[dsprzet]' WHERE fs_id = '$fsid' LIMIT 1";
	
	if (mysql_query($sql_e1, $conn)) 
		{ 
			?><script> opener.location.reload(true); self.close(); </script><?php
	} else 
		{
		  ?><script>info('Wyst¹pi³ b³¹d podczas zapisywania zmian do bazy'); self.close(); </script><?php
		}
	} else {	
			?><script>info('Nie wype³ni³eœ wymaganych pól'); window.history.go(-1); </script><?php
	}
} else { ?>

<?php

$sql_e = "SELECT * FROM $dbname.serwis_firmy_serwisowe WHERE (fs_id=$select_id) LIMIT 1";

$result = mysql_query($sql_e, $conn) or die(mysql_error());

while ($newArray = mysql_fetch_array($result)) {
	$temp_id  			= $newArray['fs_id'];
	$temp_nazwa			= $newArray['fs_nazwa'];
	$temp_adres			= $newArray['fs_adres'];
	$temp_telefon		= $newArray['fs_telefon'];
	$temp_fax			= $newArray['fs_fax'];
	$temp_email			= $newArray['fs_email'];
	$temp_www			= $newArray['fs_www'];
	$temp_belongs_to	= $newArray['belongs_to'];
	$temp_sp			= $newArray['fs_sprzet'];
}

	echo "<h4>Edycja danych firmie serwisowej</h4>";
	
	echo "<table cellspacing=1 align=center>";
	echo "<form name=ed action=$PHP_SELF method=POST>";
	echo "<input size=30 type=hidden name=fsid value='$temp_id'>";
	
	tbl_empty_row();
	echo "<tr>";
		echo "<td width=100 class=right>Nazwa firmy</td>";
		echo "<td>";

		echo "<input id=es class=wymagane size=45 maxlength=100 type=text name=dnf onKeyUp='slownik_es()' value='$temp_nazwa'>";
		echo "<img name=status src=img//none.gif>";
		echo "<select name=lista style='display:none'>";
		$sql="SELECT fs_nazwa FROM $dbname.serwis_firmy_serwisowe ORDER BY fs_nazwa";
		$result=mysql_query($sql,$conn);
		
		while ($dane=mysql_fetch_array($result)) {
			$temp = $dane['fs_nazwa'];
			if ($temp!=$temp_nazwa) echo "<option value=$temp>$temp</option>\n";
		}
		echo "</select>";
		echo "</td>";
		
		echo "</tr>";
	echo "<tr>";
		echo "<td width=100 class=right>Adres</td>";
		echo "<td><input size=45 maxlength=100 type=text name=dadres value='$temp_adres' onkeypress='return handleEnter(this, event)'></td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td width=100 class=right>Telefon</td>";
		echo "<td><input size=20 maxlength=100 type=text name=dtelefon value='$temp_telefon ' onkeypress='return handleEnter(this, event)'></td>";
	echo "</tr>";	
	echo "<tr>";
		echo "<td width=100 class=right>Fax</td>";
		echo "<td><input size=20 maxlength=100 type=text name=dfax value='$temp_fax' onkeypress='return handleEnter(this, event)'></td>";
	echo "</tr>";	
	echo "<tr>";
		echo "<td width=100 class=right>e-mail</td>";
		echo "<td><input size=45 maxlength=100 type=text name=demail value='$temp_email' onkeypress='return handleEnter(this, event)'></td>";
	echo "</tr>";	
	echo "<tr>";
		echo "<td width=100 class=right>Strona WWW</td>";
		echo "<td><input size=45 maxlength=100 type=text name=dwww value='$temp_www' onkeypress='return handleEnter(this, event)'></td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td width=100 class=right>Serwisowany sprzêt</td>";
		echo "<td><input size=45 maxlength=100 type=text name=dsprzet value='$temp_sp' onkeypress='return handleEnter(this, event)'></td>";
	echo "</tr>";
	tbl_empty_row();
	echo "</table>";
	
	startbuttonsarea("right");
	addbuttons("zapisz","anuluj");
	endbuttonsarea();

	echo "</form>";

?>
<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("ed");
  
  frmvalidator.addValidation("dnf","req","Nie podano nazwy firmy serwisowej");
//  frmvalidator.addValidation("demail","email","B³êdnie podany adres email");
</script>
<?php } ?>

</body>
</html>