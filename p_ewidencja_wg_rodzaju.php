<?php include_once('header.php'); ?>
<body>
<?php
$sql88="SELECT * FROM $dbname.serwis_ewidencja WHERE (belongs_to=$es_filia) and (ewidencja_status>0)";
$result88 = mysql_query($sql88, $conn) or die($k_b);  
$count_rows = mysql_num_rows($result88);

if ($count_rows==0) {
	br();
	errorheader("Nie ma żadnych informacji o sprzęcie w bazie");
	break;
}
pageheader("Ilość sprzętu wg rodzaju sprzętu");

$sql77="SELECT * FROM $dbname.serwis_slownik_rola WHERE rola_do_ewidencji='1'";
$result77 = mysql_query($sql77, $conn) or die($k_b);


echo "<table cellspacing=1 align=center style=width:400px>";
echo "<tr><th>Typ sprzętu</th><th class=center>Ilość</th></tr>";
$i=0;

while ($newArray77 = mysql_fetch_array($result77))
{

  $rolaid= $newArray77['rola_id'];
  $rolanazwa= $newArray77['rola_nazwa'];
  

$sql88="SELECT * FROM $dbname.serwis_ewidencja WHERE (ewidencja_typ='$rolaid') and (belongs_to=$es_filia)";
$result88 = mysql_query($sql88, $conn) or die($k_b);  
    $count_rows = mysql_num_rows($result88);

if (($rolanazwa=='Drukarka')||($rolanazwa=='drukarka')||($rolanazwa=='DRUKARKA')) { 
$sql88a="SELECT * FROM $dbname.serwis_ewidencja WHERE (ewidencja_drukarka_opis<>'') and (belongs_to=$es_filia)";
$result88a = mysql_query($sql88a, $conn) or die($k_b);  
$count_rowsa = mysql_num_rows($result88a);
$count_rows+=$count_rowsa;
}
if ($count_rows!=0) {
tbl_tr_highlight($i);

$i+=1;
echo "<td>$rolanazwa&nbsp;</td><td class=center><b>&nbsp;";

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