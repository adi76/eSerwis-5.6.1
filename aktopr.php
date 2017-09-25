<?php include_once('header.php'); ?>
<body>
<?php
$sql55="SELECT * FROM serwis_slownik_oprogramowania WHERE oprogramowanie_slownik_id=$id LIMIT 1";
$result55 = mysql_query($sql55, $conn) or die(mysql_error());
while ($newArray55 = mysql_fetch_array($result55)) {
	$opr_id		= $newArray55['oprogramowanie_slownik_id'];		
	$opr_nazwa	= $newArray55['oprogramowanie_slownik_nazwa'];		
	
	$sql555="UPDATE $dbname.serwis_oprogramowanie SET oprogramowanie_nazwa='$opr_nazwa' WHERE (oprogramowanie_slownik_id=$opr_id)";
	//echo "$sql555<br>";
	$result555 = mysql_query($sql555, $conn) or die(mysql_error());			
}
?>
<script>
alert('Baza oprogramowania została zaktualizowana');
self.close();	
</script>
<?php			
//	echo "<h3><br><center>Zmiany zostały wprowadzone</center><br></h3>";
//	echo "<br><p align=center><input class=buttons type=button onClick=window.close() value='&nbsp;&nbsp;&nbsp;Zamknij&nbsp;&nbsp;&nbsp;'></p>";
?>
</body>
</html>