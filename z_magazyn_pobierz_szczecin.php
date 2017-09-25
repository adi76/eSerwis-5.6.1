<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) { 
	$_POST=sanitize($_POST);
	$result55 = mysql_query("SELECT filia_nazwa FROM $dbname.serwis_filie WHERE filia_id=$es_filia", $conn) or die($k_b);
	list($filian)=mysql_fetch_array($result55);
	$dddd = Date('Y-m-d H:i:s');
	$sql_t = "INSERT INTO $dbname.serwis_historia VALUES ('','$_POST[tid]','$_POST[tup]','$_POST[tuser]','$dddd','pobrano na','".nl2br($_POST[tkomentarz])."',$es_filia)";
	if (mysql_query($sql_t, $conn)) { 
		$sql_e1b="UPDATE $dbname.serwis_magazyn SET magazyn_status = '1' WHERE magazyn_id = '$id' LIMIT 1";
		$wykonaj = mysql_query($sql_e1b, $conn) or die($k_b);
		?><script>if (opener) opener.location.reload(true); self.close();  </script><?php
	} else {
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
	}
} else { 
pageheader("Pobranie sprzętu serwisowego");
starttable();
echo "<form name=addt action=$PHP_SELF method=POST>";
tbl_empty_row();
	tr_();
		td("150;r;Nazwa");
		$result3 = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni,magazyn_uwagi_sa,magazyn_uwagi FROM $dbname.serwis_magazyn WHERE magazyn_id=$id LIMIT 1", $conn) or die($k_b);
		list($mid,$mnazwa,$mmodel,$msn,$mni,$muwagi,$muwagi1)=mysql_fetch_array($result3);
		td_(";;;");
			echo "<b>$part</b>";
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
		$result6 = mysql_query("SELECT up_id, up_nazwa FROM $dbname.serwis_komorki WHERE belongs_to=$es_filia and (up_active=1) ORDER BY up_nazwa", $conn) or die($k_b);		
		echo "<select class=wymagane name=tup>\n"; 					 				
		echo "<option value=''>Wybierz z listy...";
		while (list($temp_id,$temp_nazwa) = mysql_fetch_array($result6)) { echo "<option value='$temp_nazwa'>$temp_nazwa</option>\n"; }
		echo "</select>\n";
		_td();
	_tr();
		
	tr_();
		td("150;rt;Uwagi");
		td_(";;;");
			echo "<textarea name=tkomentarz cols=43 rows=6></textarea>";
		_td();
	_tr();

	echo "<input type=hidden name=tid value=$id>";
	echo "<input type=hidden name=tstatus value='1'>";
	echo "<input type=hidden name=id value='$id'>";
	echo "<input type=hidden name=tuser value='$currentuser'>";
	tbl_empty_row();
endtable();
startbuttonsarea("right");
addbuttons("zapisz","anuluj");
endbuttonsarea();
_form();
?>
<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("addt");
  frmvalidator.addValidation("tup","req","Nie wybrałeś miejsca docelowego");
</script>
<?php } ?>
</body>
</html>