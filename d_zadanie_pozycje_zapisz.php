<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
?>
<script>ShowWaitingMessage('Trwa zapisywanie danych...', 'Saving1');</script>
<?php 

$_POST=sanitize($_POST);
$pocz=0;
$ile=$_POST[zpilosc];
$zid=$_POST[zid];
$count_dodania=0;
while ($pocz <= $ile-1) { 
	$nazwa = $_POST['komorka'.$pocz.''];
	$osoba = $_POST['user'.$pocz.''];
	$wybrane = $_POST['wybierz'.$pocz.''];
	if ($wybrane=='on') { 
		$sql_t = "INSERT INTO $dbname.serwis_zadania_pozycje values ('', $zid,'$nazwa','','',0,'','$osoba','',0,'',$es_filia)";
		$result = mysql_query($sql_t, $conn) or die($k_b);
		$count_dodania++;
	}
	$pocz++;
}
okheader("Pomyślnie przypisano UP / Komórki do zadania (Ilość dodanych pozycji : ".$count_dodania.")");
startbuttonsarea("right");

echo "<span style='float:left'>";
echo "<input type=button class=buttons value='Dodaj komórki do tego zadania' onClick=\"self.location.href='d_zadanie_pozycje.php?id=$zid';\" />";
echo "</span>";

addclosewithreloadbutton("Zamknij");
endbuttonsarea();
?>
<script>HideWaitingMessage('Saving1');</script>
</body>
</html>