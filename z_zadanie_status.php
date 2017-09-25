<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 
	$dddd = Date('Y-m-d H:i:s');
	$dz='';
	$oz='';
	if ($_POST[status]==9) {
		$dz=$dddd;
		$oz=$currentuser;
	}
	$sql = "UPDATE $dbname.serwis_zadania SET zadanie_status=$_POST[status], zadanie_data_zakonczenia='$dz', zadanie_zakonczone_przez='$oz' WHERE zadanie_id = '$_POST[zid]' LIMIT 1";
	if (mysql_query($sql, $conn)) { 
		?><script>if (opener) opener.location.reload(true); self.close();  </script><?php
	} else 	{
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php	
	}
} else { ?>
<?php
$result444 = mysql_query("SELECT zadanie_id, zadanie_opis, zadanie_uwagi FROM $dbname.serwis_zadania WHERE (zadanie_id='$id') LIMIT 1", $conn) or die($k_b);
list($temp_id,$temp_opis,$temp_uwagi)=mysql_fetch_array($result444);

if ($s!=9) okheader("Czy napewno chcesz zmienić status zadania na OTWARTE ?");
if ($s==9) okheader("Czy napewno chcesz zakończyć zadanie ?");

infoheader("<b>".skroc_tekst($temp_opis,70)."</b>");

startbuttonsarea("center");
echo "<form action=$PHP_SELF method=POST>";
echo "<input type=hidden name=zid value=$temp_id>";	
echo "<input type=hidden name=status value=$s>";
addbuttons("tak","nie");
endbuttonsarea();

_form();
}
?>
</body>
</html>