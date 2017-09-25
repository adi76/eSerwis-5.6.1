<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 
	$dddd = date("Y-m-d H:i:s");
	$sql="UPDATE $dbname.serwis_zadania SET zadanie_status = 9, zadanie_data_zakonczenia = '$dddd', zadanie_zakonczone_przez = '$currentuser' WHERE zadanie_id = '$_POST[zid]'";

	if (mysql_query($sql, $conn)) { 
		$sql1="UPDATE $dbname.serwis_zadania_pozycje SET pozycja_status = 9 WHERE pozycja_zadanie_id = '$_POST[zid]'";
		if (mysql_query($sql1, $conn)) { 
			?><script>if (opener) opener.location.reload(true); self.close();  </script><?php
		}	
	} else { 
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php	
	}
} else { ?>
<?php
$sql_e444 = "SELECT zadanie_id,zadanie_opis,zadanie_uwagi FROM $dbname.serwis_zadania WHERE (zadanie_id='$id') LIMIT 1";
$result444 = mysql_query($sql_e444, $conn) or die($k_b);
$newArray = mysql_fetch_array($result444);
$temp_id	= $newArray['zadanie_id'];
$temp_opis	= $newArray['zadanie_opis'];
$temp_uwagi	= $newArray['zadanie_uwagi'];
	
errorheader("Czy napewno chcesz zakończyć wybrane zadanie ?");
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