<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php'); 
?>
<body>
<?php 
if ($submit) { 
	if ($id!='all') {
		$sql_zmienstatuspozycji = "UPDATE $dbname.serwis_faktura_szcz SET pozycja_status=0 WHERE (pozycja_id='$_POST[id]') LIMIT 1";
		if (mysql_query($sql_zmienstatuspozycji,$conn)) { 
			$usunzzestawu = "DELETE FROM $dbname.serwis_zestaw_szcz WHERE zestawpozycja_fszcz_id=$_POST[id] LIMIT 1";
			$wykonaj_usuniecie = mysql_query($usunzzestawu,$conn) or die($k_b);

		//	$usunzzestawu = "UPDATE $dbname_hd.hd_zgl_wymiany_podzespolow SET wp_sprzet_active=0 WHERE wp_sprzedaz_fakt_szcz_id=$_POST[id] LIMIT 1";
		//	$wykonaj_usuniecie = mysql_query($usunzzestawu,$conn) or die($k_b);
			
			?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
		} else { 
			?><script>info('Wystąpił błąd podczas operacji usuwania'); self.close(); </script><?php
		}
	} else {
		$usunzzestawu = "DELETE FROM $dbname.serwis_zestaw_szcz WHERE zestawpozycja_zestaw_id=$delall";
		$wykonaj_usuniecie = mysql_query($usunzzestawu,$conn) or die($k_b);
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	}
} else {
echo "<form action=$PHP_SELF method=POST>";
$result1 = mysql_query("SELECT pozycja_id, pozycja_nazwa, pozycja_sn FROM $dbname.serwis_faktura_szcz WHERE (pozycja_id=$id ) LIMIT 1", $conn) or die($k_b);

list($temp_pozid, $temp_poznazwa, $temp_pozsn)=mysql_fetch_array($result1);
errorheader("Czy napewno chcesz usunąć wybrany towar / usługę z zestawu ?");
//errorheader("Czy napewno chcesz usunąć wybrany towar / usługę z zestawu ?<br /><br /><font color=white>Usunięcie pozycji z zestawu spowoduje usunięcie jej z listy wymienianych podzespołów dla powiązanego zgłoszenia</font>");
infoheader("<b>".skroc_tekst($temp_poznazwa,40)." (SN: ".$temp_pozsn.")");
startbuttonsarea("center");

echo "<input type=hidden name=id value=$temp_pozid>";
addbuttons("tak","nie");
endbuttonsarea();
_form();
}
?>
</body>
</html>