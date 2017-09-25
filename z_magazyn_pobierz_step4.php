<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php

pageheader("Pobranie sprzętu serwisowego (krok 4/4) - Podsumowanie");

starttable();
echo "<form name=pobierzstep4 action=$PHP_SELF method=POST>";
tbl_empty_row();
	tr_();
		td("150;r;Typ");
		
		$_POST=sanitize($_POST);
		
		$magazyn_id1 = $_POST[id];
		if ($magazyn_id1=="") $magazyn_id1=$_GET[id];
		
		$result3 = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni,magazyn_uwagi_sa,magazyn_uwagi FROM $dbname.serwis_magazyn WHERE magazyn_id=$magazyn_id1 LIMIT 1", $conn) or die($k_b);
		list($mid,$mnazwa,$mmodel,$msn,$mni,$muwagi,$muwagi1)=mysql_fetch_array($result3);
		td_(";;;");
			echo "<b>$mnazwa</b>";
			if ($muwagi1!='') echo "<a title='Czytaj uwagi '><input class=imgoption align=absmiddle type=image src=img/comment.gif onclick=\"newWindow(480,265,'p_magazyn_uwagi.php?id=$mid'); return false; \"></a>";
		_td();
	_tr();

	tr_();
		td("150;r;Model");
		td_(";;;");
			echo "<b>$mmodel</b>";
		_td();
	_tr();
	
	tr_();
		td("150;r;Numer seryjny");
		td_(";;;");
			echo "<b>$msn</b>";
		_td();
	_tr();

	tr_();
		td("150;r;Numer inwentarzowy");
		td_(";;;");
			echo "<b>$mni</b>";
		_td();
	_tr();

	tr_();
		td("150;r;Osoba pobierająca");
		td_(";;;");
			echo "<b>$currentuser</b>";
		_td();
	_tr();

	tr_();
		td("150;r;Miejsce docelowe");
		td_(";;;");
		echo "<b>$_POST[tup]</b>";
		_td();
	_tr();
		
	tr_();
		td("150;rt;Uwagi");
		td_(";;;");
			echo "<textarea name=tkomentarz cols=43 rows=6 disabled>$_POST[tkomentarz]</textarea>";
		_td();
	_tr();

/*	echo "<input type=hidden name=typ_sprzetu value='$_POST[sz]'>";
	echo "<input type=hidden name=tid value=$id>";
	echo "<input type=hidden name=tstatus value='1'>";
	echo "<input type=hidden name=id value='$id'>";
*/
	tbl_empty_row();
endtable();

_form();

startbuttonsarea("center");
echo "<input class=buttons type=button onClick=window.location.href='z_magazyn_pobierz_step3.php?typ_sprzetu=$_POST[id]&id=$_POST[id]&tup=".urlencode($tup)."&uwagi=".urlencode($_POST[tkomentarz])."'  value='Wstecz'>";

echo "<input class=buttons type=submit name=submit value='Generuj protokół'>";

$dzien		= Date('d');
$miesiac	= Date('m');
$rok		= Date('Y');
	
?>
<input class=buttons type=button onClick=window.location.href='href="utworz_protokol.php?pnr=<?php echo "$pnr";?>&dzien=<?php echo "$dzien"; ?>&miesiac=<?php echo "$miesiac"; ?>&rok=<?php echo "$rok"; ?>&c_1=<?php echo "$c_1"; ?>&c_2=<?php echo "$c_2"; ?>&c_3=<?php echo "$c_3"; ?>&c_4=<?php echo "$c_4"; ?>&c_5=<?php echo "$c_5"; ?>&c_6=<?php echo "$c_6"; ?>&c_7=<?php echo "$c_7"; ?>&c_8=<?php echo "$c_8"; ?>&up=<?php echo urlencode($up); ?>&nazwa_urzadzenia=<?php echo urlencode($nazwa_urzadzenia); ?>&sn_urzadzenia=<?php echo urlencode($sn_urzadzenia); ?>&ni_urzadzenia=<?php echo urlencode($ni_urzadzenia); ?>&opis_uszkodzenia=<?php echo urlencode($opis_uszkodzenia); ?>&wykonane_czynnosci=<?php echo urlencode($wykonane_czynnosci); ?>&uwagi=<?php echo urlencode($uwagi); ?>&format_nr=<?php echo "$format_nr"; ?>&imieinazwisko=<?php echo urlencode($imieinazwisko); ?>&wersjap=<?php echo "$wersjap";?>&unr=<?php echo $unr;?>" value="Generuj protokół">

<?php

endbuttonsarea();
startbuttonsarea("right");
addbuttons("zamknij");
endbuttonsarea();
_form();

?>

<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("addt");
  frmvalidator.addValidation("tup","req","Nie wybrałeś miejsca docelowego");
</script>

</body>
</html>