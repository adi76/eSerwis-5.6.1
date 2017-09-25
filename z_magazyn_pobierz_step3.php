<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php

pageheader("Pobranie sprzętu serwisowego (krok 3/4)");

starttable();
echo "<form name=pobierzstep3 action=z_magazyn_pobierz_step4.php method=POST>";
tbl_empty_row();
	tr_();
		td("150;r;Typ");
		$_POST=sanitize($_POST);	

		$magazyn_id1 = $_POST[sz];
		if ($magazyn_id1=="") $magazyn_id1=$_GET[typ_sprzetu];
			
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
		$result6 = mysql_query("SELECT up_id, up_nazwa FROM $dbname.serwis_komorki WHERE belongs_to=$es_filia ORDER BY up_nazwa", $conn) or die($k_b);		
		echo "<select class=wymagane name=tup>\n"; 					 				
		echo "<option value=''>Wybierz z listy...";
		while (list($temp_id,$temp_nazwa) = mysql_fetch_array($result6)) { 
		echo "<option value='$temp_nazwa'";
		if ($_GET[tup]==$temp_nazwa) echo " selected";
		echo ">$temp_nazwa</option>\n"; }
		echo "</select>\n";
		_td();
	_tr();
		
	tr_();
		td("150;rt;Uwagi");
		td_(";;;");
			echo "<textarea name=tkomentarz cols=43 rows=6>$_GET[uwagi]</textarea>";
		_td();
	_tr();

	echo "<input type=hidden name=typ_sprzetu value='$_POST[sz]'>";
/*	echo "<input type=hidden name=tid value=$id>";
	echo "<input type=hidden name=tstatus value='1'>";*/
	echo "<input type=hidden name=id value='$magazyn_id1'>";	
	echo "<input type=hidden name=tuser value='$currentuser'>";
	tbl_empty_row();
endtable();

startbuttonsarea("center");
echo "<input class=buttons type=button onClick=window.location.href='z_magazyn_pobierz_step2.php?typ_sprzetu=$mnazwa&id=$magazyn_id1'  value='Wstecz'>";
addbuttons("dalej");
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