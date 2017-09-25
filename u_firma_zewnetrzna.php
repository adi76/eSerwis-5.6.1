<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 
	$sql_d1="DELETE FROM $dbname.serwis_fz WHERE fz_id = '$_POST[fzid]' LIMIT 1";	
	if (mysql_query($sql_d1, $conn)) { 
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else {
		?><script>info('Wystąpił błąd podczas operacji usuwania'); self.close(); </script><?php
	}
} else { ?>
<?php

// sprawdzenie czy nie ma powiązań do serwisu w bazie ewidencji lub innych tabelach
$sql_e1 = "SELECT ewidencja_gwarancja_kto FROM $dbname.serwis_ewidencja WHERE (ewidencja_gwarancja_kto=$select_id)";
$result_e1 = mysql_query($sql_e1, $conn) or die($k_b);
$count_rows = mysql_num_rows($result_e1);
if ($count_rows!=0) { 
	?>
	<script>
	info('Nie można usunąć tej firmy, gdyż istnieją z nią powiązania w ewidencji sprzętu');self.close();
	</script>
	<?php
echo "</body></html>";
exit;
}

$sql_e = "SELECT * FROM $dbname.serwis_fz WHERE (fz_id=$select_id) LIMIT 1";
$result = mysql_query($sql_e, $conn) or die($k_b);
$newArray = mysql_fetch_array($result);
$temp_id  			= $newArray['fz_id'];
$temp_nazwa			= $newArray['fz_nazwa'];
$temp_adres			= $newArray['fz_adres'];
$temp_telefon		= $newArray['fz_telefon'];
$temp_fax			= $newArray['fz_fax'];
$temp_email			= $newArray['fz_email'];
$temp_www			= $newArray['fz_www'];
$temp_belongs_to	= $newArray['belongs_to'];

errorheader("Czy napewno chcesz usunąć wybraną firmę z bazy ?");
infoheader("<b>".skroc_tekst($temp_nazwa,70)."</b>");
startbuttonsarea("center");
echo "<form action=$PHP_SELF method=POST>";
echo "<input type=hidden name=fzid value=$temp_id>";
addbuttons("tak","nie");
endbuttonsarea();

_form();
}
?>
</body>
</html>