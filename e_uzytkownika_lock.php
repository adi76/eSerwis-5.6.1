<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 
	$sql_d1="UPDATE $dbname.serwis_uzytkownicy SET user_locked=$_POST[nowystatus] WHERE user_id = $_POST[did] LIMIT 1";
	echo "$sql_d1";
	if (mysql_query($sql_d1, $conn)) { 
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else {
		?><script>info('Wystąpił błąd podczas wykonywania zapytania do bazy'); self.close(); </script><?php
	}
} else { ?>
<?php
$result = mysql_query("SELECT user_id,user_first_name,user_last_name FROM $dbname.serwis_uzytkownicy WHERE (user_id=$id) LIMIT 1", $conn) or die($k_b);
list($temp_id,$temp_first_name,$temp_last_name)=mysql_fetch_array($result);
$tekst='włączyć';
if ($l==1) $tekst='wyłączyć';
errorheader("Czy napewno chcesz ".$tekst." konto wybranego pracownika ?");
infoheader("<b>".$temp_first_name." ".$temp_last_name."</b>");
startbuttonsarea("center");	
echo "<form action=$PHP_SELF method=POST>";
echo "<input type=hidden name=did value=$temp_id>";
echo "<input type=hidden name=nowystatus value=$l>";
addbuttons("tak","nie");
endbuttonsarea();
_form();
}
?>
</body>
</html>