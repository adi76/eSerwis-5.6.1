<?

function round_up ($value, $places=0) {
  if ($places < 0) { $places = 0; }
  $mult = pow(10, $places);
  return ceil($value * $mult) / $mult;
}

$srcHost	= "localhost";
$srcDB 		= "serwis";
$srcUname	= "admin_serwis";
$srcPass	= "PostLodz2007";

$tgtHost	= "10.216.39.152:3306";
$tgtDB		= "poczta";
$tgtUname	= "app_user";
$tgtPass	= "app_pass";

if (!($srcCnx = mysql_connect($srcHost, $srcUname, $srcPass)))
echo "Unable to connect to $srcHost.<BR>";

if (!mysql_select_db($srcDB, $srcCnx))
echo "Unable to open database $srcDB on $srcHost.<BR>";

if (!($tgtCnx = mysql_connect($tgtHost, $tgtUname, $tgtPass)))
echo "Unable to connect to $tgtHost.<BR>";

if (!mysql_select_db($tgtDB, $tgtCnx))
echo "Unable to open database $tgtDB on $tgtHost.<BR>";

if (!($srcRst = mysql_list_tables($srcDB, $srcCnx))) {
echo "Unable to get table list for $srcHost.<BR>";
echo mysql_error($srcCnx);
}

if (!($tgtRst = mysql_list_tables($tgtDB, $tgtCnx))) {
echo "Unable to get table list for $tgtHost.<BR>";
echo mysql_error($tgtCnx);
}



#
# kopiowanie tabeli serwis_awarie -> poczta_awarie
#

$srcTable = "serwis_awarie";


// mysql_free_result($srcRst);
// mysql_free_result($tgtRst);

$srcRst = mysql_query("select * from $srcTable WHERE (awaria_status=0)", $srcCnx);

$fieldList = "";
$columns = mysql_num_fields($srcRst);
for ($i = 0; $i < $columns; $i++) {
$tempField = mysql_field_name($srcRst, $i);
$tempType = mysql_field_type($srcRst, $i);
$fieldList .= $tempField . ", ";
if ($tempType == "string")
$createSQL .= "$tempField VARCHAR (" . mysql_field_len($srcRst, $i) . "), ";
else
$createSQL .= "$tempField $tempType, ";
}
$fieldList = substr($fieldList, 0, strlen($fieldList) - 2);
$createSQL = substr($createSQL, 0, strlen($createSQL) - 2);


$tgtTable = $srcTable . "";


#
#
#
$tgtTable = "poczta_awarie";




mysql_query("delete from $tgtTable;", $tgtCnx);
echo "Table $tgtTable cleared.<BR>";

while ($srcRow = mysql_fetch_row($srcRst)) {
$insertSQL = "insert into $tgtTable ($fieldList) VALUES (";
for ($i = 0; $i < $columns; $i++) {
if ($srcRow[$i] == null)
$insertSQL .= "'', ";
else
$insertSQL .= "'" . $srcRow[$i] . "', ";
}
$insertSQL = substr($insertSQL, 0, strlen($insertSQL) - 2);
$insertSQL .= ");";
if ($verbose) echo "$insertSQL<BR>";
mysql_query($insertSQL, $tgtCnx);
}
echo "Table $srcTable copied.<BR>";

#
# koniec kopiowania #1
#



#
# kopiowanie tabeli serwis_naprawa -> poczta_naprawa
#

$srcTable = "serwis_naprawa";


// mysql_free_result($srcRst);
// mysql_free_result($tgtRst);

$srcRst = mysql_query("select * from $srcTable WHERE (naprawa_status<4)", $srcCnx);
$fieldList = "";
$columns = mysql_num_fields($srcRst);
for ($i = 0; $i < $columns; $i++) {
$tempField = mysql_field_name($srcRst, $i);
$tempType = mysql_field_type($srcRst, $i);
$fieldList .= $tempField . ", ";
if ($tempType == "string")
$createSQL .= "$tempField VARCHAR (" . mysql_field_len($srcRst, $i) . "), ";
else
$createSQL .= "$tempField $tempType, ";
}
$fieldList = substr($fieldList, 0, strlen($fieldList) - 2);
$createSQL = substr($createSQL, 0, strlen($createSQL) - 2);


$tgtTable = $srcTable . "";


#
#
#
$tgtTable = "poczta_naprawa";




mysql_query("delete from $tgtTable;", $tgtCnx);
echo "Table $tgtTable cleared.<BR>";

while ($srcRow = mysql_fetch_row($srcRst)) {
$insertSQL = "insert into $tgtTable ($fieldList) VALUES (";
for ($i = 0; $i < $columns; $i++) {
if ($srcRow[$i] == null)
$insertSQL .= "'', ";
else
$insertSQL .= "'" . $srcRow[$i] . "', ";
}
$insertSQL = substr($insertSQL, 0, strlen($insertSQL) - 2);
$insertSQL .= ");";
if ($verbose) echo "$insertSQL<BR>";
mysql_query($insertSQL, $tgtCnx);

}
echo "Table $srcTable copied.<BR>";

#
# koniec kopiowania #2
#



#
# kopiowanie tabeli serwis_zadania -> poczta_zadania
#

$srcTable = "serwis_zadania";


// mysql_free_result($srcRst);
// mysql_free_result($tgtRst);

$srcRst = mysql_query("select * from $srcTable WHERE (zadanie_status=1)", $srcCnx);
$fieldList = "";
$columns = mysql_num_fields($srcRst);
for ($i = 0; $i < $columns; $i++) {
$tempField = mysql_field_name($srcRst, $i);
$tempType = mysql_field_type($srcRst, $i);
$fieldList .= $tempField . ", ";
if ($tempType == "string")
$createSQL .= "$tempField VARCHAR (" . mysql_field_len($srcRst, $i) . "), ";
else
$createSQL .= "$tempField $tempType, ";
}
$fieldList = substr($fieldList, 0, strlen($fieldList) - 2);
$createSQL = substr($createSQL, 0, strlen($createSQL) - 2);


$tgtTable = $srcTable . "";


#
#
#
$tgtTable = "poczta_zadania";




mysql_query("delete from $tgtTable;", $tgtCnx);
echo "Table $tgtTable cleared.<BR>";

while ($srcRow = mysql_fetch_row($srcRst)) {
$insertSQL = "insert into $tgtTable ($fieldList) VALUES (";
for ($i = 0; $i < $columns; $i++) {
if ($srcRow[$i] == null)
$insertSQL .= "'', ";
else
$insertSQL .= "'" . $srcRow[$i] . "', ";
}
$insertSQL = substr($insertSQL, 0, strlen($insertSQL) - 2);
$insertSQL .= ");";
if ($verbose) echo "$insertSQL<BR>";
mysql_query($insertSQL, $tgtCnx);

}
echo "Table $srcTable copied.<BR>";

#
# koniec kopiowania #3
#



$tgtTable = "poczta_zadania_percent";

mysql_query("delete from $tgtTable;", $tgtCnx);

echo "Tabela $tgtTable została wyczyszczona<br />";
$sql = "SELECT zadanie_id FROM $dbname.serwis_zadania WHERE (zadanie_status=1)";
$result = mysql_query($sql, $srcCnx);

while ($dane1 = mysql_fetch_array($result)) {
	$temp_id = $dane1['zadanie_id'];

	$sql1="SELECT pozycja_id FROM $dbname.serwis_zadania_pozycje WHERE (pozycja_zadanie_id=$temp_id)";
	$result1 = mysql_query($sql1, $srcCnx);
	$countall = mysql_num_rows($result1);
		
	$sql2="SELECT pozycja_id FROM $dbname.serwis_zadania_pozycje WHERE (pozycja_zadanie_id=$temp_id) and (pozycja_status=9)";
	$result2 = mysql_query($sql2, $srcCnx);
	$countwyk = mysql_num_rows($result2);

	if ($countall>0) { $procent_ = ($countwyk/$countall)*100; } else $procent_=0;
	$procent = round_up($procent_, 2);

	$dodaj_sql = "INSERT INTO poczta_zadania_percent VALUES('',$temp_id,'".$procent."')";
	$dodaj	= mysql_query($dodaj_sql, $tgtCnx);
		
}



echo "Tabela $tgtTable została wypełniona danymi<br />";





/*


#
# kopiowanie tabeli serwis_zadania_pozycje -> poczta_zadania_pozycje
#

$srcTable = "serwis_zadania_pozycje";

// mysql_free_result($srcRst);
// mysql_free_result($tgtRst);

$srcRst = mysql_query("SELECT a.* FROM $dbname.serwis_zadania_pozycje a, serwis_zadania b WHERE  ((a.pozycja_zadanie_id=b.zadanie_id) and (b.zadanie_status=1))", $srcCnx);
$fieldList = "";
$columns = mysql_num_fields($srcRst);
for ($i = 0; $i < $columns; $i++) {
$tempField = mysql_field_name($srcRst, $i);
$tempType = mysql_field_type($srcRst, $i);
$fieldList .= $tempField . ", ";
if ($tempType == "string")
$createSQL .= "$tempField VARCHAR (" . mysql_field_len($srcRst, $i) . "), ";
else
$createSQL .= "$tempField $tempType, ";
}
$fieldList = substr($fieldList, 0, strlen($fieldList) - 2);
$createSQL = substr($createSQL, 0, strlen($createSQL) - 2);


$tgtTable = $srcTable . "";


#
#
#
$tgtTable = "poczta_zadania_pozycje";




mysql_query("delete from $tgtTable;", $tgtCnx);
echo "Table $tgtTable cleared.<BR>";

while ($srcRow = mysql_fetch_row($srcRst)) {
$insertSQL = "insert into $tgtTable ($fieldList) VALUES (";
for ($i = 0; $i < $columns; $i++) {
if ($srcRow[$i] == null)
$insertSQL .= "'', ";
else
$insertSQL .= "'" . $srcRow[$i] . "', ";
}
$insertSQL = substr($insertSQL, 0, strlen($insertSQL) - 2);
$insertSQL .= ");";
if ($verbose) echo "$insertSQL<BR>";
mysql_query($insertSQL, $tgtCnx);

}
echo "Table $srcTable copied.<BR>";

#
# koniec kopiowania #4
#

*/

mysql_free_result($srcRst);

mysql_close($srcCnx);
mysql_close($tgtCnx);

?>
