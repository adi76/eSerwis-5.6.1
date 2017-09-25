<?php include_once('header.php'); ?>
<body>
<?php

if ($_REQUEST[old]!=1) {
	//pageheader("Opis zmian w bazie eSerwis w wersji <b>$_REQUEST[w]</b>");
	$sql_f1 = "SELECT wersja_nr, wersja_data, wersja_opis FROM $dbname.serwis_wersje WHERE (wersja_nr='$_REQUEST[w]') ORDER BY wersja_id DESC";
} else {
	pageheader("<b>Opis zmian w bazie eSerwis</b>");
	$sql_f1 = "SELECT wersja_nr, wersja_data, wersja_opis FROM $dbname.serwis_wersje WHERE (wersja_nr<>'$_REQUEST[w]') ORDER BY wersja_id DESC, wersja_nr DESC";
}

$result_f1=mysql_query($sql_f1,$conn) or die($k_b);

while ($dane_f1=mysql_fetch_array($result_f1)) {
	$nr_wersji = $dane_f1['wersja_nr'];
	$data_wersji = $dane_f1['wersja_data'];
	$opis_wersji = $dane_f1['wersja_opis'];
	
	if ($data_wersji!='') {
		pageheader("Opis zmian w bazie eSerwis w wersji <b>$nr_wersji</b> z <b>$data_wersji</b>");
	} else {
		pageheader("Opis zmian w bazie eSerwis w wersji <b>$nr_wersji</b>");
	}
	
	echo nl2br($opis_wersji)."<br /><br />";
}

startbuttonsarea("center");
echo "<hr />";

addbuttons("zamknij");
endbuttonsarea();

if (($_REQUEST[old]!=1) && ($_REQUEST[nouserupdate]!='1')) {
	$sql_k = "UPDATE $dbname.serwis_uzytkownicy SET user_zaakceptowane_info_o_wersji='$_REQUEST[w]' WHERE user_id=$es_nr LIMIT 1";
	mysql_query($sql_k,$conn);
}

?>
</body>
</html>