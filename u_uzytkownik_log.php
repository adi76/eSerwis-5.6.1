<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 
if ($_POST[uid]!=0) {
	$sql_d1 = "DELETE FROM $dbname.serwis_uzytkownicy_log WHERE ((log_username='$_POST[imie] $_POST[nazwisko]') and (log_filia='$es_filia'))";
} else {
	$sql_d1 = "DELETE FROM $dbname.serwis_uzytkownicy_log ";
	if ($es_m!=1) $sql_d1 .= "WHERE (log_filia='$es_filia')";
}
	if (mysql_query($sql_d1, $conn)) { 
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else { 
		?><script>info('Wystąpił błąd podczas operacji usuwania'); self.close(); </script><?php
	}
} else { 
if ($id!=0) {
	$result = mysql_query("SELECT user_id, user_first_name, user_last_name FROM $dbname.serwis_uzytkownicy WHERE (user_id=$id) LIMIT 1", $conn) or die($k_b);
	list($temp_id,$temp_imie,$temp_nazwisko)=mysql_fetch_array($result);
	errorheader("Czy napewno chcesz usunąć historię logowań użytkownika");
	infoheader("<b>".$temp_imie." ".$temp_nazwisko."</b>");

	startbuttonsarea("center");
	echo "<form action=$PHP_SELF method=POST>";
	echo "<input type=hidden name=uid value=$temp_id>";
	echo "<input type=hidden name=imie value='$temp_imie'>";
	echo "<input type=hidden name=nazwisko value='$temp_nazwisko>";
	addbuttons("tak","nie");
	endbuttonsarea();
	_form();
} else {
	errorheader("Czy napewno chcesz usunąć historię logowań podległych pracowników");
	startbuttonsarea("center");
	echo "<form action=$PHP_SELF method=POST>";
	echo "<input type=hidden name=uid value=0>";
	br();
	addbuttons("tak","nie");
	endbuttonsarea();
	_form();
	
}
}
?>
</body>
</html>