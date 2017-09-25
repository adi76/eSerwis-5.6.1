<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php'); 
?>
<body>
<?php 
//if (($submit) || ($_REQUEST[noquestion]==1)) { 
if ($submit) { 

	if ($_REQUEST[typ]=='1') { 
		$sql_zmienstatuspozycji = "DELETE FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_id='$_REQUEST[id]') and (wp_zgl_id='$_REQUEST[zglid]') LIMIT 1";
		if (mysql_query($sql_zmienstatuspozycji,$conn_hd)) { 
			list($podzespolow_w_zgl)=mysql_fetch_array(mysql_query("SELECT COUNT(wp_id) FROM hd_zgl_wymiany_podzespolow WHERE (wp_zgl_id='$_REQUEST[zglid]')"));
			
			if ($podzespolow_w_zgl==0) {
				$aktualizuj_sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_czy_powiazane_z_wymiana_podzespolow=0 WHERE zgl_nr=$_REQUEST[zglid] LIMIT 1";
				$wykonaj_akt = mysql_query($aktualizuj_sql,$conn_hd) or die($k_b);
			}
			?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
		} else {
			?><script>info('Wystąpił błąd podczas operacji usuwania'); self.close(); </script><?php
		}
		
	} else { 
		$sql_zmienstatuspozycji = "DELETE FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_sprzedaz_fakt_szcz_id='$_REQUEST[id]') and (wp_zgl_id='$_REQUEST[zglid]') LIMIT 1";
		if (mysql_query($sql_zmienstatuspozycji,$conn_hd)) { 
			list($podzespolow_w_zgl)=mysql_fetch_array(mysql_query("SELECT COUNT(wp_id) FROM hd_zgl_wymiany_podzespolow WHERE (wp_zgl_id='$_REQUEST[zglid]')"));
			
			if ($podzespolow_w_zgl==0) {
				$aktualizuj_sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_czy_powiazane_z_wymiana_podzespolow=0 WHERE zgl_nr=$_REQUEST[zglid] LIMIT 1";
				$wykonaj_akt = mysql_query($aktualizuj_sql,$conn_hd) or die($k_b);
			}
			
	//		$usunzzesprzedazy = "DELETE FROM $dbname.serwis_sprzedaz WHERE sprzedaz_pozycja_id=$_REQUEST[id] LIMIT 1";
	//		$wykonaj_usuniecie = mysql_query($usunzzesprzedazy,$conn) or die($k_b);
			$usunzzestawu = "DELETE FROM $dbname.serwis_zestaw_szcz WHERE zestawpozycja_fszcz_id=$_REQUEST[id] LIMIT 1";
			$wykonaj_usuniecie2 = mysql_query($usunzzestawu,$conn) or die($k_b);
			
			?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
		} else { 
				?><script>info('Wystąpił błąd podczas operacji usuwania'); self.close(); </script><?php
			}
	}

} else {
	//$result1 = mysql_query("SELECT pozycja_id, pozycja_nazwa, pozycja_sn FROM $dbname.serwis_faktura_szcz WHERE (pozycja_id=$id ) LIMIT 1", $conn) or die($k_b);
	//list($temp_pozid, $temp_poznazwa, $temp_pozsn)=mysql_fetch_array($result1);
	if ($_REQUEST[typ]!=1) {
		errorheader("Czy napewno usunąć wymianę podzespołu w zgłoszeniu nr <b>".$_REQUEST[nr]."</b> ?");
		infoheader("".$_REQUEST[wp_opis]." (SN: ".$_REQUEST[wp_sn].")");
	} else {
		errorheader("Czy napewno usunąć wybrany typ podzespołu:<br /><b>$_REQUEST[wp_opis]</b><br /> z wymiany w zgłoszeniu nr <b>".$_REQUEST[nr]."</b> ?");
	}
	//infoheader("<b>".skroc_tekst($temp_poznazwa,50)."</b> (SN: ".$temp_pozsn.")");
	startbuttonsarea("center");
	echo "<form action=$PHP_SELF method=POST>";
	echo "<input type=hidden name=id value=$_REQUEST[id]>";
	echo "<input type=hidden name=typ value=$_REQUEST[typ]>";
	echo "<input type=hidden name=zglid value=$_REQUEST[nr]>";
	addbuttons("tak","nie");
	endbuttonsarea();
	_form();
}
?>
</body>
</html>