<?php include_once('header.php'); ?>
<body>
<?php
$sql88="SELECT * FROM $dbname.serwis_oprogramowanie WHERE ((belongs_to=$es_filia) and (oprogramowanie_status=1))";
$result88 = mysql_query($sql88, $conn) or die($k_b);
$count_rows = mysql_num_rows($result88);
if ($count_rows==0) {
	br();
	errorheader("Nie ma żadnych informacji o oprogramowaniu w bazie");
	break;
}

pageheader("Oprogramowanie wg typu");
$sql77="SELECT * FROM $dbname.serwis_slownik_oprogramowania";
$result77 = mysql_query($sql77, $conn) or die($k_b);

echo "<table cellspacing=1 align=center style=width:400px>";
echo "<tr><th>Nazwa oprogramowania</th><th class=center>Ilość</th></tr>";
$i=0;

while ($newArray77 = mysql_fetch_array($result77)) {
	$oprnazwa= $newArray77['oprogramowanie_slownik_nazwa'];
  
	$sql88="SELECT * FROM $dbname.serwis_oprogramowanie WHERE (oprogramowanie_nazwa='$oprnazwa') and (belongs_to=$es_filia)";
	$result88 = mysql_query($sql88, $conn) or die($k_b);  
    $count_rows = mysql_num_rows($result88);

	if ($count_rows!=0) {
		tbl_tr_highlight($i);
		
		$i+=1;
		echo "<td>$oprnazwa</td><td class=center><b>";
		if ($count_rows==0) { echo "</b>"; }
		echo "$count_rows";
		if ($count_rows!=0) { echo "</b>"; }
		echo "</td></tr>";
	}
}
endtable();
?>
</body>
</html>