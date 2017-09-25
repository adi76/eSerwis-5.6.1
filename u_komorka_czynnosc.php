<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 
	$sql_d1="DELETE FROM $dbname.serwis_komorka_todo WHERE (todo_id = '$_POST[id]') LIMIT 1";
	if (mysql_query($sql_d1, $conn)) { 
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else {
		?><script>info('Wystąpił błąd podczas operacji usuwania'); self.close(); </script><?php
	}
} else { ?>
<?php
$wynik = mysql_query("SELECT todo_opis FROM $dbname.serwis_komorka_todo WHERE todo_id=$id LIMIT 1",$conn) or die($k_b);
list($temp_opis)=mysql_fetch_array($wynik);
errorheader("Czy napewno chcesz usunąć wybraną czynność z listy ?");
infoheader("".skroc_tekst($temp_opis,70)."");
startbuttonsarea("center");
echo "<form action=$PHP_SELF method=POST>";
echo "<input type=hidden name=id value=$id>";
addbuttons("tak","nie");
endbuttonsarea();

_form();
} 
?>
</body>
</html>