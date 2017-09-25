<?php 
include_once('header.php'); 
include('inc_encrypt.php');
include('cfg_vat.php');
?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) { 
$_POST=sanitize($_POST);
$sql = "SELECT * FROM $dbname.serwis_fz WHERE (fz_id=$_POST[tffwf]) LIMIT 1";
$result = mysql_query($sql,$conn) or die($k_b);
$dostawcy = mysql_fetch_array($result);
$dostawca = $dostawcy['fz_nazwa'];

if (($_POST['tffwf']!='') && ($_POST['rzak']!='')) {


$dddd = Date('Y-m-d');
$dddd1 = Date('Y-m-d H:i:s');

$cena = str_replace(',','.',$_POST[tfkp]);
if (($cena!='0') || ($cena!='')) {
  $cena=$cena/$VAT_value;
}

$knf = str_replace(',','.',$_POST[knettof]);
$knf_cr = crypt_md5($knf,$key);

$kbf = str_replace(',','.',$_POST[kbruttof]);
$kbf_cr = crypt_md5($kbf,$key);
		
$sql_t = "UPDATE $dbname.serwis_faktury SET faktura_numer ='$_POST[tfnr]', faktura_data='$_POST[tfdw]', faktura_dostawca='$dostawca',faktura_realizacjazakupu='$_POST[rzak]', faktura_nr_zamowienia = '$_POST[tfnrzam]', faktura_koszty_dodatkowe='$cena', faktura_osoba='$currentuser', faktura_datawpisu='$dddd1', faktura_uwagi='".nl2br($_POST[fuwagi])."', faktura_dostawca_id='$_POST[tffwf]',faktura_kwota_netto='$knf_cr', faktura_kwota_brutto='$kbf_cr' WHERE faktura_id=$id";

if (mysql_query($sql_t, $conn)) { 
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
} else 
	{
	  ?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php		
		}

} else
     {
		?><script>info('Nie wypełniłeś wymaganych pól'); self.close(); </script><?php			
		startbuttonsarea("right");
		addbuttons("zamknij");
		endbuttonsarea();	
}

} else { 

if ($fz!=1) {
$sql="SELECT * FROM $dbname.serwis_faktury WHERE ((belongs_to=$es_filia) and (faktura_status='0') and (faktura_id=$id)) ORDER BY faktura_data DESC";
} else {
$sql="SELECT * FROM $dbname.serwis_faktury WHERE ((belongs_to=$es_filia) and (faktura_id=$id)) ORDER BY faktura_data DESC";
}

$result = mysql_query($sql, $conn) or die($k_b);
while ($newArray = mysql_fetch_array($result)) {
	$temp_id  			= $newArray['faktura_id'];
	$temp_numer			= $newArray['faktura_numer'];
	$temp_data			= $newArray['faktura_data'];
	$temp_dostawca		= $newArray['faktura_dostawca'];
	$temp_koszty		= $newArray['faktura_koszty_dodatkowe'];
	$temp_osoba			= $newArray['faktura_osoba'];
	$temp_datawpisu		= $newArray['faktura_datawpisu'];
	$temp_status		= $newArray['faktura_status'];
	$temp_realizacja	= $newArray['faktura_realizacjazakupu'];
	$temp_fnrz			= $newArray['faktura_nr_zamowienia'];
	$temp_uwagi			= $newArray['faktura_uwagi'];
	
	$knf				= $newArray['faktura_kwota_netto'];
	$kbf				= $newArray['faktura_kwota_brutto'];
	
}

	pageheader("Edycja nagłówka faktury");
	starttable();
	echo "<form name=ef action=$PHP_SELF method=POST onSubmit=\"return pytanie_d_faktura();\">";	
	tbl_empty_row();

		echo "<tr>";
		echo "<td width=170 class=right>Numer faktury</td>";		
		echo "<td><input id=fake size=33 maxlength=50 type=text name=tfnr value='$temp_numer' onkeypress='return handleEnter(this, event);'>";
		echo "</td>";
	echo "</tr>";
	
	echo "<tr>";
		echo "<td width=170 class=right>Data wystawienia faktury</td>";		
		echo "<td><input id=tfdw size=10 type=text name=tfdw maxlength=10 value='$temp_data' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\" onkeypress='return handleEnter(this, event);'>"; 
		echo "<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif width=16 height=16 border=0></a>";
		if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('tfdw').value='".Date('Y-m-d')."'; return false;\">";
	    echo "</td>";
	echo "</tr>";

	echo "<tr>";
		echo "<td width=170 class=right>Firma wystawiająca fakturę</td>";
		echo "<td>";

		$sql6 = "SELECT * FROM $dbname.serwis_fz ORDER BY fz_nazwa ASC";
		
		$result6 = mysql_query($sql6, $conn) or die($k_b);
		$i = 0;
		
		echo "<select class=wymagane id=wybranafz name=tffwf onkeypress='return handleEnter(this, event); realizacja_zakupue()' onKeyUp='realizacja_zakupue()' onKeyDown='realizacja_zakupue()' onBlur='realizacja_zakupue()' onChange='realizacja_zakupue()'>\n"; 					 				
		echo "<option value=''>Wybierz z listy...";

		while ($newArray = mysql_fetch_array($result6)) 
		{
			$temp_id				= $newArray['fz_id'];
			$temp_nazwa				= $newArray['fz_nazwa'];
			echo "<option value='$temp_id'"; 
			if ($temp_nazwa==$temp_dostawca) { echo " selected"; }
			echo ">$temp_nazwa</option>\n"; 
		}		
		echo "</select>\n";

		echo "<select tabindex=-1 name=lista id=lista style='display:none'>";	
		$result3=mysql_query("SELECT fz_realizacja_zakupu,fz_id FROM $dbname.serwis_fz WHERE ((fz_is_ds='on') or (fz_is_fs='on')) ORDER BY fz_nazwa",$conn) or die($k_b);
		while ($dane3=mysql_fetch_array($result3)) {
			$temp_id 		= $dane3['fz_id'];
			$temp 			= $dane3['fz_realizacja_zakupu'];
			if ($temp=='') $temp='-- nie zdefiniowano --';
			echo "<option value=$temp_id>$temp</option>\n";
		}
		echo "</select>";
		
		echo "</td>";
	echo "</tr>";

	echo "<tr>";
		echo "<td width=170 class=right>Realizacja zakupu</td>";
		echo "<td>";
		echo "<input tabindex=-1 type=text readonly value='$temp_realizacja' style='background:transparent;border:0' name=rzak>";
		echo "</td>";
	echo "</tr>";

	echo "<tr>";
		echo "<td width=170 class=right>Numer zamówienia</td>";
		echo "<td><input size=50 maxlength=50 type=text name=tfnrzam value='$temp_fnrz' onkeypress='return handleEnter(this, event);'></td>";
	echo "</tr>";

	echo "<tr>";
		echo "<td width=170 class=righttop>Uwagi</td>";
		echo "<td><textarea name=fuwagi cols=60 rows=4>".br2nl($temp_uwagi)."</textarea></td>";
	echo "</tr>";
	
tbl_empty_row();
tr_();
	td(";r;Kwota netto z faktury");
	td_(";;");
		echo "<input type=hidden id=vat name=vat value='$VAT_value'>";
		echo "<input style='text-align:right;' class=wymagane size=8 maxlength=8 type=text id=knettof name=knettof value='".decrypt_md5($knf,$key)."' onkeypress=\"return filterInput(1, event, true);\" onChange=\"Przecinek_na_kropke(document.getElementById('knettof')); \">&nbsp;zł";
		echo "&nbsp;&nbsp;";
		echo "<input type=button class=buttons value='Wylicz z kwoty brutto' onClick=\"WeryfikujKwotyNettoBruttoFaktury('b');\">";
	_td();
_tr();
tr_();
	td(";r;Kwota brutto z faktury");
	td_(";;");
		echo "<input style='text-align:right;' class=wymagane size=8 maxlength=8 type=text id=kbruttof name=kbruttof value='".decrypt_md5($kbf,$key)."' onkeypress=\"return filterInput(1, event, true);\" onChange=\"Przecinek_na_kropke(document.getElementById('kbruttof')); \" >&nbsp;zł";
		echo "&nbsp;&nbsp;";
		echo "<input type=button class=buttons value='Wylicz z kwoty netto' onClick=\"WeryfikujKwotyNettoBruttoFaktury('n');\">";
	_td();
_tr();
	
// access control koniec


	tbl_empty_row();

	endtable();
	echo "<input type=hidden name=id value=$id>";

	startbuttonsarea("right");
	addbuttons("zapisz","anuluj");
	endbuttonsarea();
	
	_form();

?>
<script language="JavaScript">
var cal1 = new calendar1(document.forms['ef'].elements['tfdw']);
	cal1.year_scroll = true;
	cal1.time_comp = false;

</script>

<?php } ?>

</body>
</html>