<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 
	$sql_d1 = "UPDATE $dbname.serwis_czarna_lista SET bl_active=$_POST[newstate] WHERE (bl_id = '$_POST[umid]') LIMIT 1";
	
	if (mysql_query($sql_d1, $conn)) { 
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else { 
		?><script>info('Wystąpił błąd podczas operacji usuwania'); self.close(); </script><?php
	}
} else { ?>
<?php
$sql_e = "SELECT bl_id, bl_ip, bl_active FROM $dbname.serwis_czarna_lista WHERE (bl_id=$id) LIMIT 1";
$result = mysql_query($sql_e, $conn) or die($k_b);
$newArray = mysql_fetch_array($result);
$temp_id	= $newArray['bl_id'];
$temp_ip	= $newArray['bl_ip'];
$temp_act	= $newArray['bl_active'];


if ($temp_act==1) {	
	errorheader("Czy napewno chcesz deaktywować blokadę dla adresu");
	infoheader("<b>".$temp_ip."</b>");
	startbuttonsarea("center");
	echo "<form action=$PHP_SELF method=POST>";
	echo "<input type=hidden name=umid value=$temp_id>";	
	echo "<input type=hidden name=newstate value=0>";
} else { 
	errorheader("Czy napewno chcesz aktywować blokadę dla adresu");
	infoheader("<b>".$temp_ip."</b>");
	startbuttonsarea("center");
	echo "<form action=$PHP_SELF method=POST>";
	echo "<input type=hidden name=umid value=$temp_id>";	
	echo "<input type=hidden name=newstate value=1>";
}

addbuttons("tak","nie");
endbuttonsarea();

_form();
}
?>
</body>
</html>