<?php include_once('header.php'); ?>
<body>
<?php 
$sql1="SELECT * FROM $dbname.serwis_fz WHERE ((fz_is_fs='on') and (fz_nazwa='$fsn')) ORDER BY fz_nazwa ASC";
$result1 = mysql_query($sql1, $conn) or die($k_b);
if (mysql_num_rows($result1)!=0) {
   
	$dane = mysql_fetch_array($result1);
   
	$mid 		= $dane['fz_id'];
	$mnazwa 	= $dane['fz_nazwa'];
	$madres		= $dane['fz_adres'];
	$mtelefon	= $dane['fz_telefon'];
	$mfax		= $dane['fz_fax'];
	$memail		= $dane['fz_email'];
	$mwww		= $dane['fz_www'];

	echo "<br /><table cellspacing=1 align=center>";
	echo "<tr><td colspan=2><b>&nbsp;Szczegółowe informacje o serwisie $mnazwa&nbsp;</b></td></tr>";
	echo "<tr><td width=300>Nazwa sprzętu, model</td><td><b>$mnazwa, $mmodel</b></td></tr>";
	echo "<tr><td width=300>Numer seryjny, NI</td><td><b>$msn, $mni</b></td></tr>";

	echo "<tr><td width=300>Uwagi</td><td><a href=p_naprawy_uwagi.php?id=$id target=_blank>czytaj</a></td></tr>";
	echo "<tr><td width=300>Sprzęt pobrano z</td><td><b>$mup</b></td></tr>";
	echo "<tr><td width=300>Przyjęto na serwis, data przyjęcia</td><td><b>$moo, $mdp</b></td></tr>";
	if ($mnwwzo!='') {
	echo "<tr><td width=350>Naprawa we własnym zakresie przez, data zmiany statusu</td><td><b>$mnwwzo, $mnwwzd</b></td></tr>"; }
	if ($mnow!='') {
	echo "<tr><td width=300>Wysyłka do serwisu, data wysyłki</td><td><b>$mnow, $mndw</b></td></tr>"; 
	echo "<tr><td width=300>Nazwa serwisu</td><td><b>$mnfs</td></tr>";
	echo "<tr><td width=300>Nazwa firmy kurierskiej, nr listu przewozowego</td><td><b>$mnfk, $mnnlp <a href=szczeg_fk.php?fkid=$id>(pokaż szczegóły)</a></b></td></tr>"; }
	if ($mnptn!='0000-00-00 00:00:00') {	
	echo "<tr><td width=300>Przewisywany termin naprawy</td><td><b>$mnptn</b></td></tr>"; }
	if ($mnopszs!='') {
	echo "<tr><td width=300>Odbiór naprawionego sprzętu, data odbioru</td><td><b>$mnopszs, $mndozs</b></td></tr>"; }
	if ($mnoos!='') {
	echo "<tr><td width=300>Przekazano do klienta, data przekazania</td><td><b>$mnoos, $mndos</b></td></tr>"; }
	
	
	endtable();
   
} else errorheader("Brak firm serwisowych w bazie");

startbuttonsarea("right");
addbuttons("zamknij");
endbuttonsarea();

?>

</body>
</html>