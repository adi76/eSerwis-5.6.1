<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 
	$sql_d1 = "DELETE FROM $dbname.serwis_kb_kategorie WHERE (kb_kategoria_id = $_POST[pid]) LIMIT 1";
	if (mysql_query($sql_d1, $conn)) { 
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else { 
		?><script>info('Wystąpił błąd podczas operacji usuwania'); self.close(); </script><?php
	}
} else { ?>	
<?php
$sql_e = "SELECT kb_kategoria_id,kb_kategoria_nazwa,kb_parent_id FROM $dbname.serwis_kb_kategorie WHERE (kb_kategoria_id=$id) LIMIT 1";
$result = mysql_query($sql_e, $conn) or die($k_b);
$newArray = mysql_fetch_array($result);
$temp_id	  	= $newArray['kb_kategoria_id'];
$temp_par_id	= $newArray['kb_parent_id'];
$temp_nazwa  	= $newArray['kb_kategoria_nazwa'];	

$sql_e = "SELECT kb_kategoria_nazwa FROM $dbname.serwis_kb_kategorie WHERE (kb_kategoria_id=$temp_par_id) LIMIT 1";
$result = mysql_query($sql_e, $conn) or die($k_b);
$newArray = mysql_fetch_array($result);
$temp_par_nazwa  = $newArray['kb_kategoria_nazwa'];
	
if ($poziom==0) errorheader("Czy napewno chcesz usunąć kategorię z listy ?");
if ($poziom==1) errorheader("Czy napewno chcesz usunąć podkategorię z listy ?");

infoheader("<b>".$temp_par_nazwa."->".$temp_nazwa."");

startbuttonsarea("center");
echo "<form action=$PHP_SELF method=POST>";
echo "<input type=hidden name=pid value=$temp_id>";
addbuttons("tak","nie");
endbuttonsarea();

_form();
}
?>
</body>
</html>