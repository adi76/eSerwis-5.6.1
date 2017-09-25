<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 
	$sql_d1="DELETE FROM $dbname.serwis_uzytkownicy WHERE user_id = '$_POST[did]' LIMIT 1";
	if (mysql_query($sql_d1, $conn)) { 
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else {
		?><script>info('Wystąpił błąd podczas operacji usuwania'); self.close(); </script><?php
	}
} else { ?>
<?php
$result = mysql_query("SELECT user_id,user_first_name,user_last_name FROM $dbname.serwis_uzytkownicy WHERE (user_id=$select_id) LIMIT 1", $conn) or die($k_b);
list($temp_id,$temp_first_name,$temp_last_name)=mysql_fetch_array($result);
if ($l==0) {
	errorheader("Czy napewno chcesz usunąć wybranego pracownika z bazy ?");
	infoheader("<b>".$temp_first_name." ".$temp_last_name."</b>");
	startbuttonsarea("center");	
	echo "<form action=$PHP_SELF method=POST>";
	echo "<input type=hidden name=did value=$temp_id>";	
	addbuttons("tak","nie");
	endbuttonsarea();
} else
	{
		errorheader("<br />Nie możesz usunąć użytkownika aktualnie zalogowanego<br /><br />");
		startbuttonsarea("center");	
		echo "<form action=$PHP_SELF method=POST>";
		echo "<input type=hidden name=did value=$temp_id>";
		startbuttonsarea("center");
		addbuttons("zamknij");
		endbuttonsarea();
	}
_form();
}
?>
</body>
</html>