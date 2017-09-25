<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 
	$sql = "DELETE FROM $dbname.serwis_zadania WHERE zadanie_id = '$_POST[zid]' LIMIT 1";
	
	if (mysql_query($sql, $conn)) { 
		$sql1="DELETE FROM $dbname.serwis_zadania_pozycje WHERE pozycja_zadanie_id = '$_POST[zid]'";
		if (mysql_query($sql1, $conn)) { 
			?><script>if (opener) opener.location.reload(true); self.close();  </script><?php
		}	
	} else {
	  ?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php	
	}
} else { ?>
<?php
$sql_e444 = "SELECT * FROM $dbname.serwis_zadania WHERE (zadanie_id='$id') LIMIT 1";
$result444 = mysql_query($sql_e444, $conn) or die($k_b);
$newArray = mysql_fetch_array($result444);
$temp_id	= $newArray['zadanie_id'];
$temp_opis	= $newArray['zadanie_opis'];
$temp_uwagi	= $newArray['zadanie_uwagi'];

errorheader("Czy napewno chcesz usunąć wybrane zadanie z listy ?");
infoheader("<b>".skroc_tekst($temp_opis,70)."</b>");
startbuttonsarea("center");
echo "<form action=$PHP_SELF method=POST>";
echo "<input type=hidden name=zid value=$temp_id>";	
addbuttons("tak","nie");
endbuttonsarea();

_form();
}
?>
</body>
</html>