<?php include_once('header.php'); ?>
<body>
<?php 
$result1 = mysql_query("SELECT fz_id,fz_nazwa,fz_adres,fz_telefon,fz_fax,fz_email,fz_www,fz_opis,fz_is_fs,fz_is_fk,fz_is_ds FROM $dbname.serwis_fz WHERE fz_id=$id LIMIT 1", $conn) or die($k_b);
if (mysql_num_rows($result1)!=0) {
	list($fzid,$fznazwa,$fzadres,$fztelefon,$fzfax,$fzemail,$fzwww,$fzopis,$fzisfs,$fzisfk,$fzisds)=mysql_fetch_array($result1);
	pageheader("Szczegółowe informacje o firmie");
	$typf = '';
	if ($fzisfs=='on') $typf.='FS ';
	if ($fzisfk=='on') $typf.='FK ';
	if ($fzisds=='on') $typf.='DS ';
	starttable();
	tbl_empty_row();
	if ($fznazwa!='') { tr_(); td("100;r;Nazwa|;l;<b>".$fznazwa."</b>"); _tr(); }
	if ($fzadres!='') { tr_(); td("100;rt;Adres|;l;<b>".$fzadres."</b>"); _tr();}
	if ($fztelefon!='') { tr_(); td("100;rt;Telefon|;l;<b>".$fztelefon."</b>"); _tr();}
	if ($fzfax!='') { tr_(); td("100;rt;Fax|;l;<b>".$fzfax."</b>"); _tr();}
	if ($fzemail!='') { tr_(); td("100;r;Email|;l;<b>".$fzemail."</b>"); _tr();}
	if ($fzwww!='') { tr_(); td("100;r;WWW|;l;<b>".$fzwww."</b>"); _tr();}
	if ($fzopis!='') { tr_(); td("100;rt;Opis|;l;<b>".$fzopis."</b>"); _tr();}
	tr_(); td("100;r;Typ firmy|;l;<b>".$typf."</b>"); _tr();
	tbl_empty_row();
	endtable();
} else errorheader("Brak danych o firmie");
startbuttonsarea("right");
addbuttons("zamknij");
endbuttonsarea();
?>
</body>
</html>