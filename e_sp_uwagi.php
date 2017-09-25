<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php'); 
?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) { 
	$_POST=sanitize($_POST);
	$sql_d1="UPDATE $dbname_hd.hd_zgl_wymiany_podzespolow SET wp_sprzet_pocztowy_uwagi='$_REQUEST[uwagi]' WHERE (wp_id='$_REQUEST[wpid]') LIMIT 1";

		if (mysql_query($sql_d1, $conn_hd)) { 
			?><script>
				self.close(); 
				if (opener) opener.location.reload(true); </script><?php
		} else { 
			?><script>alert('Wystąpił błąd podczas wykonywania powiązania'); self.close(); </script><?php
		}
} else { ?>
<?php
$sql_e123 = "SELECT wp_typ_podzespolu, wp_sprzet_pocztowy_uwagi FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_id=$_REQUEST[wpid]) LIMIT 1";
$result123 = mysql_query($sql_e123, $conn) or die($k_b);
$newArray123 = mysql_fetch_array($result123);
$temp_uwagi123	= $newArray123['wp_sprzet_pocztowy_uwagi'];
$temp_typ123	= $newArray123['wp_typ_podzespolu'];

pageheader("Edytuj uwagi o podzespole <font color=white>".$temp_typ123."</font>");

starttable();
echo "<form name=edu action=$PHP_SELF method=POST>";

tbl_empty_row();

echo "<tr>";
	echo "<td width=120 class=righttop>Uwagi do sprzętu</td>";
	echo "<td><textarea name=uwagi cols=35 rows=6>".br2nl($temp_uwagi123)."</textarea></td>";
echo "</tr>";

tbl_empty_row();	
endtable();
echo "<input size=30 type=hidden name=wpid value='$_REQUEST[wpid]'>";
startbuttonsarea("right");
addbuttons("zapisz","anuluj");
endbuttonsarea();

_form();
}
?>
</body>
</html>