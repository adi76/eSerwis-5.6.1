<?php include_once('header.php'); ?>
<body>
<?php
include('convert.php');
okheader("Rozpoczynam konwersję danych w bazie eSerwis");

/* ========================== serwis_fz ================================ */
$sql="SELECT * FROM $dbname.serwis_fz";
$result = mysql_query($sql, $conn) or die(mysql_error());

while ($newArray = mysql_fetch_array($result)) {
	$fz1_id			= $newArray['fz_id'];
	$fz1_nazwa		= $newArray['fz_nazwa'];
	$fz1_adres		= $newArray['fz_adres'];
	$fz1_telefon	= $newArray['fz_telefon'];
	$fz1_fax		= $newArray['fz_fax'];
	$fz1_email		= $newArray['fz_email'];
	$fz1_www		= $newArray['fz_www'];
	$fz1_opis		= $newArray['fz_opis'];
	$fz1_realiacja	= $newArray['fz_realizacja_zakupu'];
	//echo "$fz1_id,$fz1_nazwa,$fz1_adres,$fz1_telefon,$fz1_fax,$fz1_email,$fz1_www,$fz1_opis,$fz1_realiacja<br />";	
	
	$sql1="UPDATE $dbname.serwis_fz SET fz_nazwa='".win2utf8($fz1_nazwa)."', fz_adres='".win2utf8($fz1_adres)."', fz_telefon='".win2utf8($fz1_telefon)."', fz_fax='".win2utf8($fz1_fax)."', fz_email='".win2utf8($fz1_email)."', fz_www='".win2utf8($fz1_www)."', fz_opis='".win2utf8($fz1_opis)."', fz_realizacja_zakupu='".win2utf8($fz1_realiacja)."' WHERE (fz_id=$fz1_id) LIMIT 1";	
	$result1 = mysql_query($sql1, $conn) or die(mysql_error());
}
echo "serwis_fz - przekonwertowano<br />";
/* =================================================================== */
/* ========================== serwis_awarie ================================ */
$sql="SELECT awaria_id,awaria_gdzie, awaria_osobarejestrujaca, awaria_osobazamykajaca FROM $dbname.serwis_awarie";
$result = mysql_query($sql, $conn) or die(mysql_error());

while ($newArray = mysql_fetch_array($result)) {
	$aw1_id			= $newArray['awaria_id'];
	$aw1_gdzie		= $newArray['awaria_gdzie'];
	$aw1_o1			= $newArray['awaria_osobarejestrujaca'];
	$aw1_o2			= $newArray['awaria_osobazamykajaca'];
	
	$sql1="UPDATE $dbname.serwis_awarie SET awaria_gdzie='".win2utf8($aw1_gdzie)."', awaria_osobarejestrujaca='".win2utf8($aw1_o1)."', awaria_osobazamykajaca='".win2utf8($aw1_o2)."' WHERE (awaria_id=$aw1_id) LIMIT 1";	
	$result1 = mysql_query($sql1, $conn) or die(mysql_error());
}
echo "serwis_awarie - przekonwertowano<br />";

/* =================================================================== */
/* ========================== serwis_ewidencja ================================ */
$sql="SELECT ewidencja_id, ewidencja_up_nazwa, ewidencja_uzytkownik, ewidencja_uwagi, ewidencja_modyfikacja_user, ewidencja_komputer_opis FROM $dbname.serwis_ewidencja";
$result = mysql_query($sql, $conn) or die(mysql_error());

while ($newArray = mysql_fetch_array($result)) {
	$t1		= $newArray['ewidencja_id'];
	$t2		= $newArray['ewidencja_up_nazwa'];
	$t3		= $newArray['ewidencja_uzytkownik'];
	$t4		= $newArray['ewidencja_uwagi'];
	$t5		= $newArray['ewidencja_modyfikacja_user'];
	$t6		= $newArray['ewidencja_komputer_opis'];
	
//	echo "$t1 $t2 $t3 $t4 $t5";
	$sql1="UPDATE $dbname.serwis_ewidencja SET ewidencja_up_nazwa='".win2utf8($t2)."', ewidencja_uzytkownik='".win2utf8($t3)."', ewidencja_uwagi='".win2utf8(sanitize($t4))."', ewidencja_modyfikacja_user='".win2utf8($t5)."', ewidencja_komputer_opis='".win2utf8($t6)."' WHERE (ewidencja_id=$t1) LIMIT 1";	
	$result1 = mysql_query($sql1, $conn) or die(mysql_error());
}
echo "serwis_ewidencja - przekonwertowano<br />";

/* =================================================================== */
/* ========================== serwis_faktura_szcz ================================ */
$sql="SELECT pozycja_id, pozycja_nazwa, pozycja_typ, pozycja_uwagi FROM $dbname.serwis_faktura_szcz";
$result = mysql_query($sql, $conn) or die(mysql_error());

while ($newArray = mysql_fetch_array($result)) {
	$t1		= $newArray['pozycja_id'];
	$t2		= $newArray['pozycja_nazwa'];
	$t3		= $newArray['pozycja_typ'];
	$t4		= $newArray['pozycja_uwagi'];
	
//	echo "$t1 $t2 $t3 $t4 $t5";
	$sql1="UPDATE $dbname.serwis_faktura_szcz SET pozycja_nazwa='".win2utf8($t2)."', pozycja_typ='".win2utf8($t3)."', pozycja_uwagi='".win2utf8(sanitize($t4))."' WHERE (pozycja_id=$t1) LIMIT 1";	
	$result1 = mysql_query($sql1, $conn) or die(mysql_error());
}
echo "serwis_faktura_szcz - przekonwertowano<br />";

/* =================================================================== */

/* ========================== serwis_faktury ================================ */
$sql="SELECT faktura_id, faktura_dostawca,faktura_osoba,faktura_realizacjazakupu,faktura_uwagi FROM $dbname.serwis_faktury";
$result = mysql_query($sql, $conn) or die(mysql_error());

while ($newArray = mysql_fetch_array($result)) {
	$t1		= $newArray['faktura_id'];
	$t2		= $newArray['faktura_dostawca'];
	$t3		= $newArray['faktura_osoba'];
	$t4		= $newArray['faktura_realizacjazakupu'];
	$t5		= $newArray['faktura_uwagi'];
	
//	echo "$t1 $t2 $t3 $t4 $t5";
	$sql1="UPDATE $dbname.serwis_faktury SET faktura_dostawca='".win2utf8($t2)."', faktura_osoba='".win2utf8($t3)."', faktura_realizacjazakupu='".win2utf8($t4)."', faktura_uwagi='".win2utf8(sanitize($t5))."' WHERE (faktura_id=$t1) LIMIT 1";	
	$result1 = mysql_query($sql1, $conn) or die(mysql_error());
}
echo "serwis_faktury - przekonwertowano<br />";

/* =================================================================== */

/* ========================== serwis_filie ================================ */
$sql="SELECT filia_id, filia_nazwa, filia_adres FROM $dbname.serwis_filie";
$result = mysql_query($sql, $conn) or die(mysql_error());

while ($newArray = mysql_fetch_array($result)) {
	$t1		= $newArray['filia_id'];
	$t2		= $newArray['filia_nazwa'];
	$t3		= $newArray['filia_adres'];
//	$t4		= $newArray['faktura_realizacjazakupu'];
//	$t5		= $newArray['faktura_uwagi'];
	
//	echo "$t1 $t2 $t3 $t4 $t5";
	$sql1="UPDATE $dbname.serwis_filie SET filia_nazwa='".win2utf8($t2)."', filia_adres='".win2utf8(sanitize($t3))."' WHERE (filia_id=$t1) LIMIT 1";	
	$result1 = mysql_query($sql1, $conn) or die(mysql_error());
}
echo "serwis_filie - przekonwertowano<br />";

/* =================================================================== */

/* ========================== serwis_historia ================================ */
$sql="SELECT historia_id, historia_up,historia_user,historia_komentarz FROM $dbname.serwis_historia";
$result = mysql_query($sql, $conn) or die(mysql_error());

while ($newArray = mysql_fetch_array($result)) {
	$t1		= $newArray['historia_id'];
	$t2		= $newArray['historia_up'];
	$t3		= $newArray['historia_user'];
	$t4		= $newArray['historia_komentarz'];
//	$t5		= $newArray['faktura_uwagi'];
	
//	echo "$t1 $t2 $t3 $t4 $t5";
	$sql1="UPDATE $dbname.serwis_historia SET historia_up='".win2utf8($t2)."', historia_user='".win2utf8($t3)."', historia_komentarz='".win2utf8(sanitize($t4))."' WHERE (historia_id=$t1) LIMIT 1";	
	$result1 = mysql_query($sql1, $conn) or die(mysql_error());
}
echo "serwis_historia - przekonwertowano<br />";

/* =================================================================== */

/* ========================== serwis_komorka_todo  ================================ */
$sql="SELECT todo_id, todo_opis, todo_przypisane_osobie, todo_osoba_wykonujaca, todo_osobawpisujaca, todo_uwagi  FROM $dbname.serwis_komorka_todo";
$result = mysql_query($sql, $conn) or die(mysql_error());

while ($newArray = mysql_fetch_array($result)) {
	$t1		= $newArray['todo_id'];
	$t2		= $newArray['todo_opis'];
	$t3		= $newArray['todo_przypisane_osobie'];
	$t4		= $newArray['todo_osoba_wykonujaca'];
	$t5		= $newArray['todo_osobawpisujaca'];
	$t6		= $newArray['todo_uwagi'];
	
//	echo "$t1 $t2 $t3 $t4 $t5";
	$sql1="UPDATE $dbname.serwis_komorka_todo SET todo_opis='".win2utf8(sanitize($t2))."', todo_przypisane_osobie='".win2utf8($t3)."', todo_osoba_wykonujaca='".win2utf8($t4)."', todo_osobawpisujaca='".win2utf8($t5)."', todo_uwagi='".win2utf8(sanitize($t6))."' WHERE (todo_id=$t1) LIMIT 1";	
	$result1 = mysql_query($sql1, $conn) or die(mysql_error());
}
echo "serwis_komorka_todo - przekonwertowano<br />";

/* =================================================================== */

/* ========================== serwis_komorki  ================================ */
$sql="SELECT up_id, up_nazwa, up_opis, up_adres FROM $dbname.serwis_komorki";
$result = mysql_query($sql, $conn) or die(mysql_error());

while ($newArray = mysql_fetch_array($result)) {
	$t1		= $newArray['up_id'];
	$t2		= $newArray['up_nazwa'];
	$t3		= $newArray['up_opis'];
	$t4		= $newArray['up_adres'];
//	$t5		= $newArray['todo_osobawpisujaca'];
//	$t6		= $newArray['todo_uwagi'];
	
//	echo "$t1 $t2 $t3 $t4 $t5";
	$sql1="UPDATE $dbname.serwis_komorki SET up_nazwa='".win2utf8($t2)."', up_opis='".win2utf8(sanitize($t3))."', up_adres='".win2utf8($t4)."' WHERE (up_id=$t1) LIMIT 1";	
	$result1 = mysql_query($sql1, $conn) or die(mysql_error());
}
echo "serwis_komorki - przekonwertowano<br />";

/* =================================================================== */

/* ========================== serwis_magazyn  ================================ */
$sql="SELECT magazyn_id, magazyn_nazwa, magazyn_model,magazyn_uwagi,magazyn_osobawprowadzajaca, magazyn_osoba_rezerwujaca FROM $dbname.serwis_magazyn";
$result = mysql_query($sql, $conn) or die(mysql_error());

while ($newArray = mysql_fetch_array($result)) {
	$t1		= $newArray['magazyn_id'];
	$t2		= $newArray['magazyn_nazwa'];
	$t3		= $newArray['magazyn_model'];
	$t4		= $newArray['magazyn_uwagi'];
	$t5		= $newArray['magazyn_osobawprowadzajaca'];
	$t6		= $newArray['magazyn_osoba_rezerwujaca'];
	
//	echo "$t1 $t2 $t3 $t4 $t5";
	$sql1="UPDATE $dbname.serwis_magazyn SET magazyn_nazwa='".win2utf8($t2)."', magazyn_model='".win2utf8(sanitize($t3))."', magazyn_uwagi='".win2utf8(sanitize($t4))."', magazyn_osobawprowadzajaca='".win2utf8($t5)."', magazyn_osoba_rezerwujaca='".win2utf8($t6)."' WHERE (magazyn_id=$t1) LIMIT 1";	
	$result1 = mysql_query($sql1, $conn) or die(mysql_error());
}
echo "serwis_magazyn - przekonwertowano<br />";

/* =================================================================== */

/* ========================== serwis_naprawa  ================================ */
$sql="SELECT * FROM $dbname.serwis_naprawa";
$result = mysql_query($sql, $conn) or die(mysql_error());

while ($newArray = mysql_fetch_array($result)) {
	$t1		= $newArray['naprawa_id'];
	$t2		= $newArray['naprawa_uwagi'];
	$t3		= $newArray['naprawa_pobrano_z'];
	$t4		= $newArray['naprawa_osoba_pobierajaca'];
	$t5		= $newArray['naprawa_fs_nazwa'];
	$t6		= $newArray['naprawa_osoba_wysylajaca'];
	$t7		= $newArray['naprawa_fk_nazwa'];
	$t8		= $newArray['naprawa_osoba_przyjmujaca_sprzet_z_serwisu'];
	$t9		= $newArray['naprawa_osoba_oddajaca_sprzet'];
	$t10		= $newArray['naprawa_nwwz_osoba'];
	
//	echo "$t1 $t2 $t3 $t4 $t5";
	$sql1="UPDATE $dbname.serwis_naprawa SET naprawa_uwagi='".win2utf8(sanitize($t2))."', naprawa_pobrano_z='".win2utf8($t3)."', naprawa_osoba_pobierajaca='".win2utf8($t4)."', naprawa_fs_nazwa='".win2utf8($t5)."', naprawa_osoba_wysylajaca='".win2utf8($t6)."', naprawa_fk_nazwa='".win2utf8($t7)."', naprawa_osoba_przyjmujaca_sprzet_z_serwisu='".win2utf8($t8)."', naprawa_osoba_oddajaca_sprzet='".win2utf8($t9)."', naprawa_nwwz_osoba='".win2utf8($t10)."' WHERE (naprawa_id=$t1) LIMIT 1";	
	//echo "$sql1";
	$result1 = mysql_query($sql1, $conn) or die(mysql_error());
}
echo "serwis_naprawa - przekonwertowano<br />";

/* =================================================================== */

/* ========================== serwis_oprogramowanie  ================================ */
$sql="SELECT oprogramowanie_id, oprogramowanie_nazwa FROM $dbname.serwis_oprogramowanie";
$result = mysql_query($sql, $conn) or die(mysql_error());

while ($newArray = mysql_fetch_array($result)) {
	$t1		= $newArray['oprogramowanie_id'];
	$t2		= $newArray['oprogramowanie_nazwa'];
//	$t3		= $newArray['magazyn_model'];
//	$t4		= $newArray['magazyn_uwagi'];
//	$t5		= $newArray['magazyn_osobawprowadzajaca'];
//	$t6		= $newArray['magazyn_osoba_rezerwujaca'];
	
//	echo "$t1 $t2 $t3 $t4 $t5";
	$sql1="UPDATE $dbname.serwis_oprogramowanie SET oprogramowanie_nazwa='".win2utf8($t2)."' WHERE (oprogramowanie_id=$t1) LIMIT 1";	
	$result1 = mysql_query($sql1, $conn) or die(mysql_error());
}
echo "serwis_oprogramowanie - przekonwertowano<br />";

/* =================================================================== */

/* ========================== serwis_podfaktury  ================================ */
$sql="SELECT pf_id, pf_dostawca_nazwa, pf_uwagi, pf_osoba FROM $dbname.serwis_podfaktury";
$result = mysql_query($sql, $conn) or die(mysql_error());

while ($newArray = mysql_fetch_array($result)) {
	$t1		= $newArray['pf_id'];
	$t2		= $newArray['pf_dostawca_nazwa'];
	$t3		= $newArray['pf_uwagi'];
	$t4		= $newArray['pf_osoba'];
//	$t5		= $newArray['magazyn_osobawprowadzajaca'];
//	$t6		= $newArray['magazyn_osoba_rezerwujaca'];
	
//	echo "$t1 $t2 $t3 $t4 $t5";
	$sql1="UPDATE $dbname.serwis_podfaktury SET pf_dostawca_nazwa='".win2utf8($t2)."', pf_uwagi='".win2utf8(sanitize($t3))."', pf_osoba='".win2utf8($t4)."' WHERE (pf_id=$t1) LIMIT 1";	
	$result1 = mysql_query($sql1, $conn) or die(mysql_error());
}
echo "serwis_podfaktury - przekonwertowano<br />";

/* =================================================================== */

/* ========================== serwis_slownik_drukarka  ================================ */
$sql="SELECT drukarka_id, drukarka_opis FROM $dbname.serwis_slownik_drukarka";
$result = mysql_query($sql, $conn) or die(mysql_error());

while ($newArray = mysql_fetch_array($result)) {
	$t1		= $newArray['drukarka_id'];
	$t2		= $newArray['drukarka_opis'];
//	$t3		= $newArray['pf_uwagi'];
//	$t4		= $newArray['pf_osoba'];
//	$t5		= $newArray['magazyn_osobawprowadzajaca'];
//	$t6		= $newArray['magazyn_osoba_rezerwujaca'];
	
//	echo "$t1 $t2 $t3 $t4 $t5";
	$sql1="UPDATE $dbname.serwis_slownik_drukarka SET drukarka_opis='".win2utf8($t2)."' WHERE (drukarka_id=$t1) LIMIT 1";	
	$result1 = mysql_query($sql1, $conn) or die(mysql_error());
}
echo "serwis_slownik_drukarka - przekonwertowano<br />";

/* =================================================================== */

/* ========================== serwis_slownik_konfiguracja  ================================ */
$sql="SELECT konfiguracja_id,konfiguracja_opis FROM $dbname.serwis_slownik_konfiguracja";
$result = mysql_query($sql, $conn) or die(mysql_error());

while ($newArray = mysql_fetch_array($result)) {
	$t1		= $newArray['konfiguracja_id'];
	$t2		= $newArray['konfiguracja_opis'];
//	$t3		= $newArray['pf_uwagi'];
//	$t4		= $newArray['pf_osoba'];
//	$t5		= $newArray['magazyn_osobawprowadzajaca'];
//	$t6		= $newArray['magazyn_osoba_rezerwujaca'];
	
//	echo "$t1 $t2 $t3 $t4 $t5";
	$sql1="UPDATE $dbname.serwis_slownik_konfiguracja SET konfiguracja_opis='".win2utf8($t2)."' WHERE (konfiguracja_id=$t1) LIMIT 1";	
	$result1 = mysql_query($sql1, $conn) or die(mysql_error());
}
echo "serwis_slownik_konfiguracja - przekonwertowano<br />";

/* =================================================================== */

/* ========================== serwis_slownik_oprogramowania  ================================ */
$sql="SELECT oprogramowanie_slownik_id, oprogramowanie_slownik_nazwa FROM $dbname.serwis_slownik_oprogramowania";
$result = mysql_query($sql, $conn) or die(mysql_error());

while ($newArray = mysql_fetch_array($result)) {
	$t1		= $newArray['oprogramowanie_slownik_id'];
	$t2		= $newArray['oprogramowanie_slownik_nazwa'];
//	$t3		= $newArray['pf_uwagi'];
//	$t4		= $newArray['pf_osoba'];
//	$t5		= $newArray['magazyn_osobawprowadzajaca'];
//	$t6		= $newArray['magazyn_osoba_rezerwujaca'];
	
//	echo "$t1 $t2 $t3 $t4 $t5";
	$sql1="UPDATE $dbname.serwis_slownik_oprogramowania SET oprogramowanie_slownik_nazwa='".win2utf8($t2)."' WHERE (oprogramowanie_slownik_id=$t1) LIMIT 1";	
	$result1 = mysql_query($sql1, $conn) or die(mysql_error());
}
echo "serwis_slownik_oprogramowania - przekonwertowano<br />";

/* =================================================================== */

/* ========================== serwis_slownik_rola  ================================ */
$sql="SELECT rola_id, rola_nazwa FROM $dbname.serwis_slownik_rola";
$result = mysql_query($sql, $conn) or die(mysql_error());

while ($newArray = mysql_fetch_array($result)) {
	$t1		= $newArray['rola_id'];
	$t2		= $newArray['rola_nazwa'];
//	$t3		= $newArray['pf_uwagi'];
//	$t4		= $newArray['pf_osoba'];
//	$t5		= $newArray['magazyn_osobawprowadzajaca'];
//	$t6		= $newArray['magazyn_osoba_rezerwujaca'];
	
//	echo "$t1 $t2 $t3 $t4 $t5";
	$sql1="UPDATE $dbname.serwis_slownik_rola SET rola_nazwa='".win2utf8($t2)."' WHERE (rola_id=$t1) LIMIT 1";	
	$result1 = mysql_query($sql1, $conn) or die(mysql_error());
}
echo "serwis_slownik_rola - przekonwertowano<br />";

/* =================================================================== */

/* ========================== serwis_sposob_naprawy  ================================ */
$sql="SELECT sn_id, sn_nazwa FROM $dbname.serwis_sposob_naprawy";
$result = mysql_query($sql, $conn) or die(mysql_error());

while ($newArray = mysql_fetch_array($result)) {
	$t1		= $newArray['sn_id'];
	$t2		= $newArray['sn_nazwa'];
//	$t3		= $newArray['pf_uwagi'];
//	$t4		= $newArray['pf_osoba'];
//	$t5		= $newArray['magazyn_osobawprowadzajaca'];
//	$t6		= $newArray['magazyn_osoba_rezerwujaca'];
	
//	echo "$t1 $t2 $t3 $t4 $t5";
	$sql1="UPDATE $dbname.serwis_sposob_naprawy SET sn_nazwa='".win2utf8($t2)."' WHERE (sn_id=$t1) LIMIT 1";	
	$result1 = mysql_query($sql1, $conn) or die(mysql_error());
}
echo "serwis_sposob_naprawy - przekonwertowano<br />";

/* =================================================================== */

/* ========================== serwis_sprzedaz  ================================ */
$sql="SELECT sprzedaz_id, sprzedaz_pozycja_nazwa, sprzedaz_osoba,sprzedaz_up_nazwa, sprzedaz_uwagi, sprzedaz_rodzaj, sprzedaz_typ FROM $dbname.serwis_sprzedaz";
$result = mysql_query($sql, $conn) or die(mysql_error());

while ($newArray = mysql_fetch_array($result)) {
	$t1		= $newArray['sprzedaz_id'];
	$t2		= $newArray['sprzedaz_pozycja_nazwa'];
	$t3		= $newArray['sprzedaz_osoba'];
	$t4		= $newArray['sprzedaz_up_nazwa'];
	$t5		= $newArray['sprzedaz_uwagi'];
	$t6		= $newArray['sprzedaz_rodzaj'];
	$t7		= $newArray['sprzedaz_typ'];
	
//	echo "$t1 $t2 $t3 $t4 $t5";
	$sql1="UPDATE $dbname.serwis_sprzedaz SET sprzedaz_pozycja_nazwa='".win2utf8(sanitize($t2))."', sprzedaz_osoba='".win2utf8($t3)."', sprzedaz_up_nazwa='".win2utf8($t4)."', sprzedaz_uwagi='".win2utf8(sanitize($t5))."', sprzedaz_rodzaj='".win2utf8($t6)."', sprzedaz_typ='".win2utf8($t7)."' WHERE (sprzedaz_id=$t1) LIMIT 1";	
	$result1 = mysql_query($sql1, $conn) or die(mysql_error());
}
echo "serwis_sprzedaz - przekonwertowano<br />";

/* =================================================================== */

/* ========================== serwis_style  ================================ */
$sql="SELECT * FROM $dbname.serwis_style";
$result = mysql_query($sql, $conn) or die(mysql_error());

while ($newArray = mysql_fetch_array($result)) {
	$t1		= $newArray['style_nazwa'];
	$t2		= $newArray['style_opis'];
//	$t3		= $newArray['pf_uwagi'];
//	$t4		= $newArray['pf_osoba'];
//	$t5		= $newArray['magazyn_osobawprowadzajaca'];
//	$t6		= $newArray['magazyn_osoba_rezerwujaca'];
	
//	echo "$t1 $t2 $t3 $t4 $t5";
	$sql1="UPDATE $dbname.serwis_style SET style_opis='".win2utf8($t2)."' WHERE (style_nazwa='$t1') LIMIT 1";	
	$result1 = mysql_query($sql1, $conn) or die(mysql_error());
}
echo "serwis_style - przekonwertowano<br />";

/* =================================================================== */

/* ========================== serwis_umowy  ================================ */
$sql="SELECT umowa_id, umowa_opis FROM $dbname.serwis_umowy";
$result = mysql_query($sql, $conn) or die(mysql_error());

while ($newArray = mysql_fetch_array($result)) {
	$t1		= $newArray['umowa_id'];
	$t2		= $newArray['umowa_opis'];
//	$t3		= $newArray['pf_uwagi'];
//	$t4		= $newArray['pf_osoba'];
//	$t5		= $newArray['magazyn_osobawprowadzajaca'];
//	$t6		= $newArray['magazyn_osoba_rezerwujaca'];
	
//	echo "$t1 $t2 $t3 $t4 $t5";
	$sql1="UPDATE $dbname.serwis_umowy SET umowa_opis='".win2utf8($t2)."' WHERE (umowa_id=$t1) LIMIT 1";	
	$result1 = mysql_query($sql1, $conn) or die(mysql_error());
}
echo "serwis_umowy - przekonwertowano<br />";

/* =================================================================== */

/* ========================== serwis_uzytkownicy  ================================ */
$sql="SELECT user_id, user_first_name, user_last_name FROM $dbname.serwis_uzytkownicy";
$result = mysql_query($sql, $conn) or die(mysql_error());

while ($newArray = mysql_fetch_array($result)) {
	$t1		= $newArray['user_id'];
	$t2		= $newArray['user_first_name'];
	$t3		= $newArray['user_last_name'];
//	$t4		= $newArray['pf_osoba'];
//	$t5		= $newArray['magazyn_osobawprowadzajaca'];
//	$t6		= $newArray['magazyn_osoba_rezerwujaca'];
	
//	echo "$t1 $t2 $t3 $t4 $t5";
	$sql1="UPDATE $dbname.serwis_uzytkownicy SET user_first_name='".win2utf8($t2)."', user_last_name='".win2utf8($t3)."'  WHERE (user_id=$t1) LIMIT 1";	
	$result1 = mysql_query($sql1, $conn) or die(mysql_error());
}
echo "serwis_uzytkownicy - przekonwertowano<br />";

/* =================================================================== */

/* ========================== serwis_uzytkownicy_log  ================================ */
$sql="SELECT log_id, log_username FROM $dbname.serwis_uzytkownicy_log";
$result = mysql_query($sql, $conn) or die(mysql_error());

while ($newArray = mysql_fetch_array($result)) {
	$t1		= $newArray['log_id'];
	$t2		= $newArray['log_username'];
//	$t3		= $newArray['user_last_name'];
//	$t4		= $newArray['pf_osoba'];
//	$t5		= $newArray['magazyn_osobawprowadzajaca'];
//	$t6		= $newArray['magazyn_osoba_rezerwujaca'];
	
//	echo "$t1 $t2 $t3 $t4 $t5";
	$sql1="UPDATE $dbname.serwis_uzytkownicy_log SET log_username='".win2utf8($t2)."' WHERE (log_id=$t1) LIMIT 1";	
	$result1 = mysql_query($sql1, $conn) or die(mysql_error());
}
echo "serwis_uzytkownicy_log - przekonwertowano<br />";

/* =================================================================== */

/* ========================== serwis_zadania  ================================ */
$sql="SELECT zadanie_id, zadanie_opis,zadanie_zakonczone_przez, zadanie_utworzone_przez, zadanie_uwagi FROM $dbname.serwis_zadania";
$result = mysql_query($sql, $conn) or die(mysql_error());

while ($newArray = mysql_fetch_array($result)) {
	$t1		= $newArray['zadanie_id'];
	$t2		= $newArray['zadanie_opis'];
	$t3		= $newArray['zadanie_zakonczone_przez'];
	$t4		= $newArray['zadanie_utworzone_przez'];
	$t5		= $newArray['zadanie_uwagi'];
//	$t6		= $newArray['magazyn_osoba_rezerwujaca'];
	
//	echo "$t1 $t2 $t3 $t4 $t5";
	$sql1="UPDATE $dbname.serwis_zadania SET zadanie_opis='".win2utf8(sanitize($t2))."', zadanie_zakonczone_przez='".win2utf8($t3)."', zadanie_utworzone_przez='".win2utf8($t4)."', zadanie_uwagi='".win2utf8(sanitize($t5))."' WHERE (zadanie_id=$t1) LIMIT 1";	
	$result1 = mysql_query($sql1, $conn) or die(mysql_error());
}
echo "serwis_zadania - przekonwertowano<br />";

/* =================================================================== */

/* ========================== serwis_zadania_pozycje  ================================ */
$sql="SELECT pozycja_id, pozycja_komorka, pozycja_modyfikowane_przez, pozycja_uwagi, pozycja_przypisane_osobie  FROM $dbname.serwis_zadania_pozycje";
$result = mysql_query($sql, $conn) or die(mysql_error());

while ($newArray = mysql_fetch_array($result)) {
	$t1		= $newArray['pozycja_id'];
	$t2		= $newArray['pozycja_komorka'];
	$t3		= $newArray['pozycja_modyfikowane_przez'];
	$t4		= $newArray['pozycja_uwagi'];
	$t5		= $newArray['pozycja_przypisane_osobie'];
//	$t6		= $newArray['magazyn_osoba_rezerwujaca'];
	
//	echo "$t1 $t2 $t3 $t4 $t5";
	$sql1="UPDATE $dbname.serwis_zadania_pozycje SET pozycja_komorka='".win2utf8($t2)."', pozycja_modyfikowane_przez='".win2utf8($t3)."', pozycja_uwagi='".win2utf8($t4)."', pozycja_przypisane_osobie='".win2utf8($t5)."' WHERE (pozycja_id=$t1) LIMIT 1";	
	$result1 = mysql_query($sql1, $conn) or die(mysql_error());
}
echo "serwis_zadania_pozycje - przekonwertowano<br />";

/* =================================================================== */

/* ========================== serwis_zestawy  ================================ */
$sql="SELECT zestaw_id, zestaw_opis, zestaw_kto, zestaw_uwagi FROM $dbname.serwis_zestawy";
$result = mysql_query($sql, $conn) or die(mysql_error());

while ($newArray = mysql_fetch_array($result)) {
	$t1		= $newArray['zestaw_id'];
	$t2		= $newArray['zestaw_opis'];
	$t3		= $newArray['zestaw_kto'];
	$t4		= $newArray['zestaw_uwagi'];
//	$t5		= $newArray['z'];
//	$t6		= $newArray['magazyn_osoba_rezerwujaca'];
	
//	echo "$t1 $t2 $t3 $t4 $t5";
	$sql1="UPDATE $dbname.serwis_zestawy SET zestaw_opis='".win2utf8(sanitize($t2))."', zestaw_kto='".win2utf8($t3)."', zestaw_uwagi='".win2utf8(sanitize($t4))."' WHERE (zestaw_id=$t1) LIMIT 1";	
	$result1 = mysql_query($sql1, $conn) or die(mysql_error());
}
echo "serwis_zestawy - przekonwertowano<br />";

/* =================================================================== */
br();
okheader("Zakończono konwersję danych w bazie eSerwis");
startbuttonsarea();
addbuttons("zamknij");
endbuttonsarea();
?>
</body>
</html>