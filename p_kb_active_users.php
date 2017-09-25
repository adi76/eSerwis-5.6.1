<?php include_once('header.php'); ?>
<body>
<?php
pageheader("Lista aktywnych użytkowników w bazie wiedzy");

infoheader("Utworzone wątki");
ob_flush();	flush();

starttable("auto");
echo "<tr><th class=center>LP</th><th>Imię i nazwisko</th><th class=center>Ilość utworzonych wątków</th><th class=center>Filia</th></tr>";
$result1 = mysql_query("SELECT DISTINCT(kb_user_id) FROM $dbname.serwis_kb_pytania", $conn) or die($k_b);
$i = 1;
while (list($temp_id) = mysql_fetch_array($result1)) {
	list($imie,$nazwisko,$filia)=mysql_fetch_array(mysql_query("SELECT user_first_name,user_last_name, filia_nazwa FROM $dbname.serwis_uzytkownicy, $dbname.serwis_filie WHERE (belongs_to=filia_id) and (user_id=$temp_id) ORDER BY belongs_to ASC, user_last_name ASC", $conn));
	
	list($count)=mysql_fetch_array(mysql_query("SELECT COUNT(kb_pytanie_id) FROM $dbname.serwis_kb_pytania WHERE (kb_user_id=$temp_id)", $conn));
	echo "<tr><td class=center>$i</td><td>$imie $nazwisko</td><td class=center>$count</td><td class=center>$filia</td></tr>";
	ob_flush();	flush();
	$i++;
}
endtable();
echo "<br />";

infoheader("Udzielone odpowiedzi");
ob_flush();	flush();
starttable("auto");
echo "<tr><th class=center>LP</th><th>Imię i nazwisko</th><th class=center>Ilość udzielonych odpowiedzi</th><th class=center>Filia</th></tr>";
$result1 = mysql_query("SELECT DISTINCT(kb_user_id) FROM $dbname.serwis_kb_odpowiedzi", $conn) or die($k_b);
$i = 1;
while (list($temp_id) = mysql_fetch_array($result1)) {
	list($imie,$nazwisko,$filia)=mysql_fetch_array(mysql_query("SELECT user_first_name,user_last_name, filia_nazwa FROM $dbname.serwis_uzytkownicy, $dbname.serwis_filie WHERE (belongs_to=filia_id) and (user_id=$temp_id) ORDER BY belongs_to ASC, user_last_name ASC", $conn));
	
	list($count)=mysql_fetch_array(mysql_query("SELECT COUNT(kb_pytanie_id) FROM $dbname.serwis_kb_odpowiedzi WHERE (kb_user_id=$temp_id)", $conn));
	echo "<tr><td class=center>$i</td><td>$imie $nazwisko</td><td class=center>$count</td><td class=center>$filia</td></tr>";
	ob_flush();	flush();
	$i++;
}
endtable();
startbuttonsarea("right");
echo "<hr />";
addbuttons("zamknij");
endbuttonsarea();
?>
</body>
</html>