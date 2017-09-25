<?php include_once('header.php'); ?>
<?php

$sql = "SELECT up_nazwa FROM $dbname.serwis_komorki WHERE up_id=$id LIMIT 1";
$result = mysql_query($sql, $conn) or die($k_b);
while ($newArray = mysql_fetch_array($result)) {
	$temp_nazwa 		= $newArray['up_nazwa'];
}

$nowanazwa = 'UP '.$temp_nazwa;

$sql = "UPDATE $dbname.serwis_komorki SET up_nazwa='$nowanazwa' WHERE up_id=$id LIMIT 1";
echo $sql;

mysql_query($sql, $conn) or die($k_b);

?><script>if (opener) opener.location.reload(true); self.close(); </script><?php	


?>