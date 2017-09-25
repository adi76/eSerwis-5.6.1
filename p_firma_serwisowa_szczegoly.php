<?php include_once('header.php'); ?>
<body>
<?php 
$sql1="SELECT * FROM $dbname.serwis_fz WHERE ((fz_is_fs='on') and (fz_id='$id')) LIMIT 1";
$result1 = mysql_query($sql1, $conn) or die($k_b);

if (mysql_num_rows($result1)!=0) {
	$dane = mysql_fetch_array($result1);
	$mid 		= $dane['fz_id'];
	$mnazwa 	= $dane['fz_nazwa'];
	$madres		= $dane['fz_adres'];
	$mtelefon 	= $dane['fz_telefon'];
	$mfax		= $dane['fz_fax'];
	$memail 	= $dane['fz_email'];
	$mwww		= $dane['fz_www'];

	pageheader("Szczegółowe informacje o firmie serwisowej");
	starttable();
	tbl_empty_row();
		tr_();	td("100;r;Nazwa serwisu|;l;<b>".$mnazwa."</b>"); _tr();
		tr_();	td("100;r;Adres serwisu|;l;<b>".$madres."</b>"); _tr();
		tr_();	td("100;r;Telefon|;l;<b>".$mtelefon."</b>"); _tr();
		tr_();	td("100;r;Fax|;l;<b>".$mfax."</b>"); _tr();
		tr_();	td("100;r;email|;l;<b><a href=mailto:".$memail.">".$memail."</a></b>"); _tr();
		tr_();	td("100;r;WWW|;l;<b><a href=http://".$mwww." target=_blank>".$mwww."</a></b>"); _tr();
	tbl_empty_row();
	endtable();
} else errorheader("Dla wybranego sprzętu nie przypisano firmy serwisowej");

startbuttonsarea("right");
addbuttons("zamknij");
endbuttonsarea();

?>
</body>
</html>