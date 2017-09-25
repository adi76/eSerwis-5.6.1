<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 
	$sql_d1 = "DELETE FROM $dbname.serwis_kb_odpowiedzi WHERE (kb_odpowiedz_id = $_POST[pid]) LIMIT 1";
	if (mysql_query($sql_d1, $conn)) { 
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else { 
		?><script>info('Wystąpił błąd podczas operacji usuwania'); self.close(); </script><?php
	}
} else { ?>
<?php
$sql_e = "SELECT kb_odpowiedz_id, kb_odpowiedz_tresc FROM $dbname.serwis_kb_odpowiedzi WHERE (kb_odpowiedz_id=$id) LIMIT 1";
$result = mysql_query($sql_e, $conn) or die($k_b);
$newArray = mysql_fetch_array($result);
$temp_id	= $newArray['kb_odpowiedz_id'];
$temp_nazwa	= $newArray['kb_odpowiedz_tresc'];

if (strlen($temp_nazwa)>40) { $temat = substr($temp_nazwa,0,40).'...'; } else { $temat = $temp_nazwa; }
errorheader("Czy napewno chcesz usunąć odpowiedź ?");
infoheader("<b>".skroc_tekst($temat,70)."");
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