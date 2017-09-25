<?php 
include_once('header.php'); 
include('inc_encrypt.php');
include('cfg_vat.php');
?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) { 
	?><script>ShowWaitingMessage('Trwa zapisywanie danych... proszę czekać', 'Saving1');</script><?php
	
	$_POST=sanitize($_POST);
	$sql = "SELECT * FROM $dbname.serwis_fz WHERE (fz_id=$_POST[tffwf]) LIMIT 1";
	$result = mysql_query($sql,$conn) or die($k_b);
	$dostawcy = mysql_fetch_array($result);
	$dostawca = $dostawcy['fz_nazwa'];
	if (($_POST['tffwf']!='') && ($_POST['rzak']!='')) {
		$dddd1 = Date('Y-m-d H:i:s');
		$cena = str_replace(',','.',$_POST[tfkp]);
		
		$knf = str_replace(',','.',$_POST[knettof]);
		$knf_cr = crypt_md5($knf,$key);

		$kbf = str_replace(',','.',$_POST[kbruttof]);
		$kbf_cr = crypt_md5($kbf,$key);
		
		$sql_t = "INSERT INTO $dbname.serwis_faktury values ('', '$_POST[tfnr]','$_POST[tfdw]','$dostawca','$cena',$es_filia,'$currentuser','$dddd1','0','0','$_POST[rzak]','$_POST[tfnrzam]','".nl2br($_POST[fuwagi])."',$_POST[tffwf],'$knf_cr','$kbf_cr')";
		
		if (mysql_query($sql_t, $conn)) { 
			$lastid = mysql_insert_id();
			okheader("Pomyślnie dodano nową fakturę do bazy");
			?>
			<script>
				HideWaitingMessage('Saving1');
				
				if (opener) opener.location.reload(true);
				if (confirm("Czy chcesz dodać pozycje do tej faktury ?")) {
					window.location.href='d_faktura_pozycja.php?id=<?php echo $lastid;?>';
				} else self.close();
			</script>
			<?php
		} else {
			?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php 
		}
	} else {
		?><script>info('Nie wypełniłeś wymaganych pól'); self.close(); </script>
		<?php
			startbuttonsarea("right");
			addbuttons("zamknij");
			endbuttonsarea();
	}
} else { ?>
<?php
pageheader("Dodawanie nowej faktury");
?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
starttable();
echo "<form name=addf action=$PHP_SELF method=POST onSubmit=\"return pytanie_d_faktura();\">";
tbl_empty_row();
tr_();
	td("170;r;Numer faktury");
	td_(";;");
		echo "<input id=faktura_add size=33 maxlength=50 type=text name=tfnr>";
	_td();
_tr();
tr_();
	td("170;r;Data wystawienia faktury");
	td_(";;");
		$dddd = Date('Y-m-d');
		echo "<input id=tfdw size=10 maxlength=10 type=text name=tfdw maxlength=10 value=$dddd onkeypress='return handleEnter(this, event);' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\">";
		echo "&nbsp;<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img src=img/cal.gif width=16 height=16 border=0></a>";
		if ($_today==1) echo "&nbsp;<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('tfdw').value='".Date('Y-m-d')."'; return false;\">";
    _td();
_tr();
tr_();
	td("170;r;Firma wystawiająca fakturę");
	td_(";;");
		$sql6="SELECT fz_id, fz_nazwa FROM $dbname.serwis_fz WHERE ((fz_is_ds='on') or (fz_is_fs='on') or (fz_is_fk='on')) ORDER BY fz_nazwa ASC";	
		$result6 = mysql_query($sql6, $conn) or die($k_b);
		echo "<select class=wymagane id=wybranafz name=tffwf onkeypress='return handleEnter(this, event); realizacja_zakupu()' onKeyUp='realizacja_zakupu()' onKeyDown='realizacja_zakupu()' onBlur='realizacja_zakupu()' onChange='realizacja_zakupu()'>\n";
		echo "<option value=''>Wybierz z listy...";
		while ($newArray = mysql_fetch_array($result6)) {
			$temp_id	= $newArray['fz_id'];
			$temp_nazwa	= $newArray['fz_nazwa'];
			echo "<option value=$temp_id>$temp_nazwa</option>\n"; 
		}
		echo "</select>\n"; 		
		echo "<select name=lista id=lista style='display:none'>";	
		$result3=mysql_query("SELECT fz_realizacja_zakupu,fz_id FROM $dbname.serwis_fz WHERE ((fz_is_ds='on') or (fz_is_fs='on') or (fz_is_fk='on')) ORDER BY fz_nazwa",$conn) or die($k_b);
		while ($dane3=mysql_fetch_array($result3)) {
			$temp_id 		= $dane3['fz_id'];
			$temp 			= $dane3['fz_realizacja_zakupu'];
			if ($temp=='') $temp='-- nie zdefiniowano --';
			echo "<option value=$temp_id>$temp</option>\n";
		}
	echo "</select>";
	_td();
_tr();
tr_();
	td("170;r;Realizacja zakupu");
	td_(";;");
		echo "<input tabindex=-1 type=text readonly value='' style='background:transparent;border:0' name=rzak>";
	_td();
_tr();
tr_();
	td("170;r;Numer(y) zamówienia");
	td_(";;");
		echo "<input size=50 maxlength=50 type=text name=tfnrzam onkeypress='return handleEnter(this, event);' onFocus='realizacja_zakupu()'>";
	_td();
_tr();
tr_();
	td("170;rt;Uwagi");
	td_(";;");
		echo "<textarea name=fuwagi cols=60 rows=4></textarea>";
	_td();
_tr();
tbl_empty_row();
tr_();
	td(";r;Kwota netto z faktury");
	td_(";;");
		echo "<input type=hidden id=vat name=vat value='$VAT_value'>";
		echo "<input style='text-align:right;' class=wymagane size=8 maxlength=8 type=text id=knettof name=knettof  onkeypress=\"return filterInput(1, event, true);\" onChange=\"Przecinek_na_kropke(document.getElementById('knettof')); \">&nbsp;zł";
		echo "&nbsp;&nbsp;";
		echo "<input type=button class=buttons value='Wylicz z kwoty brutto' onClick=\"WeryfikujKwotyNettoBruttoFaktury('b');\">";
	_td();
_tr();
tr_();
	td(";r;Kwota brutto z faktury");
	td_(";;");
		echo "<input style='text-align:right;' class=wymagane size=8 maxlength=8 type=text id=kbruttof name=kbruttof  onkeypress=\"return filterInput(1, event, true);\" onChange=\"Przecinek_na_kropke(document.getElementById('kbruttof')); \" >&nbsp;zł";
		echo "&nbsp;&nbsp;";
		echo "<input type=button class=buttons value='Wylicz z kwoty netto' onClick=\"WeryfikujKwotyNettoBruttoFaktury('n');\">";
	_td();
_tr();
echo "<input type=hidden name=tfkp value=0>";
tbl_empty_row();		
endtable();
startbuttonsarea("right");
addbuttons("dalej","anuluj");
endbuttonsarea();
_form();
?>
<script language="JavaScript">
var cal1 = new calendar1(document.forms['addf'].elements['tfdw']);
	cal1.year_scroll = true;
	cal1.time_comp = false;
</script>
<?php } ?>
<script>HideWaitingMessage();</script>
</body>
</html>