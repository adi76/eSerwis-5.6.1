<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 
	$sql_d1="DELETE FROM $dbname.serwis_protokoly_historia WHERE (protokol_id = $nrid) LIMIT 1";
	if (mysql_query($sql_d1, $conn)) { 
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else { 
		?><script>info('Wystąpił błąd podczas operacji usuwania'); self.close(); </script><?php
	}
} else { ?>
<?php
$sql_e = "SELECT protokol_nr, protokol_id FROM $dbname.serwis_protokoly_historia WHERE (protokol_id='$id') LIMIT 1";

$result = mysql_query($sql_e, $conn) or die($k_b);
$newArray = mysql_fetch_array($result);
$temp_id	= $newArray['protokol_id'];
$temp_nr	= $newArray['protokol_nr'];

errorheader("Czy napewno chcesz usunąć wybrany protokół ?");
infoheader("<b>".$temp_nr."</b>");

startbuttonsarea("center");
echo "<form action=$PHP_SELF method=POST>";
echo "<input type=hidden name=nrid value=$id>";
addbuttons("tak","nie");
endbuttonsarea();

_form();
}
?>
</body>
</html>