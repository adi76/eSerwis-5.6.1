<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php'); 
?>
<body>
<?php 
if ($submit) { 
	$sql = "DELETE FROM $dbname_hd.hd_projekty WHERE projekt_id = '$_POST[zid]' LIMIT 1";
	
	if (mysql_query($sql, $conn)) { 
		?><script>if (opener) opener.location.reload(true); self.close();  </script><?php
	}	
	else {
	  ?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php	
	}
} else { ?>
<?php
$sql_e444 = "SELECT * FROM $dbname_hd.hd_projekty WHERE (projekt_id='$id') LIMIT 1";
$result444 = mysql_query($sql_e444, $conn) or die($k_b);
$newArray = mysql_fetch_array($result444);
$temp_id	= $newArray['projekt_id'];
$temp_opis	= $newArray['projekt_opis'];
$temp_uwagi	= $newArray['projekt_uwagi'];

errorheader("Czy napewno chcesz usunąć wybrany projekt z bazy ?");
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