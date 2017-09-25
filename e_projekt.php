<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body OnLoad="document.forms[0].elements[0].focus(); ">
<?php
if ($submit) { 
	$_POST=sanitize($_POST);
	$dddd = date("Y-m-d H:i:s");

	if ($_POST[projekt_filia]=='all') { 
		$_fff = $_POST[all_filie];
	} else $_fff = '|'.$_POST[projekt_filia].'|';
	
	$sql_t = "UPDATE $dbname_hd.hd_projekty SET projekt_opis='$_POST[dzopis]', projekt_termin_zakonczenia='$_POST[dztermin]', projekt_uwagi = '".nl2br($_POST[dzuwagi])."', projekt_priorytet='$_POST[dzpriorytet]', projekt_dla_filii='$_fff' WHERE (projekt_id=$_POST[zid]) LIMIT 1";
	//echo $sql_t;
	
	if (mysql_query($sql_t, $conn)) { 
		?><script>if (opener) opener.location.reload(true);	self.close(); </script><?php
	} else {
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php		
	}
} else {
$sql="SELECT * FROM $dbname_hd.hd_projekty WHERE (projekt_id=$id)";
$result = mysql_query($sql, $conn) or die($k_b);
while ($newArray = mysql_fetch_array($result)) {
	$temp_id  			= $newArray['projekt_id'];
	$temp_opis			= $newArray['projekt_opis'];
	$temp_termin_zak	= $newArray['projekt_termin_zakonczenia'];
	$temp_dla_filii		= $newArray['projekt_dla_filii'];
	$temp_active		= $newArray['projekt_active'];
	$temp_osoba_utw		= $newArray['projekt_autor'];
	$temp_data_utw		= $newArray['projekt_data_utworzenia'];
	$temp_status		= $newArray['projekt_status'];
	$temp_priorytet		= $newArray['projekt_priorytet'];
	$temp_uwagi			= $newArray['projekt_uwagi'];
	$temp_termin_gr		= $newArray['projekt_termin_graniczny'];
}
pageheader("Edycja informacji o projekcie");
starttable();
echo "<form name=addz action=$PHP_SELF method=POST onSubmit=\"return ProjektEdytuj('Czy napewno chcesz zapisać zmiany w zadaniu ?'); \">";
tbl_empty_row();
	tr_();
		td("150;r;Opis zadania");
		td_(";;");
			echo "<input class=wymagane size=60 maxlength=255 type=text value='$temp_opis' id=dzopis name=dzopis onkeypress='return handleEnter(this, event);'>";
		_td();
	_tr();
	tr_();
		td("150;r;Termin zakończenia zadania");
		td_(";;");
			if ($temp_termin_gr=='0000-00-00') $temp_termin_gr='';
			echo "<input size=10 maxlength=10 type=text id=dztermin name=dztermin maxlength=10 value='".substr($temp_termin_gr,0,10)."' onkeypress='return handleEnter(this, event);' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\">";
			echo "<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif width=16 height=16 border=0></a>";
			if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('dztermin').value='".Date('Y-m-d')."'; return false;\">";
		_td();
	_tr();
	tr_();
		td("150;rt;Uwagi");
		td_(";;");
			echo "<textarea name=dzuwagi cols=45 rows=3>".br2nl($temp_uwagi)."</textarea>";
		_td();
	_tr();
	tr_();
		td("150;r;Priorytet");
		td_(";;");
			echo "<select name=dzpriorytet onkeypress='return handleEnter(this, event);'>\n"; 			
			echo "<option "; if ($temp_priorytet==0) echo "SELECTED "; echo "value=0>Niski</option>\n";
			echo "<option "; if ($temp_priorytet==1) echo "SELECTED "; echo "value=1>Normalny</option>\n"; 
			echo "<option "; if ($temp_priorytet==2) echo "SELECTED "; echo "value=2>Wysoki</option>\n";
			echo "</select>\n";
		_td();
	_tr();
	tr_();
		td("150;r;Dla filii/oddziału");
		td_(";;");
			$sql44="SELECT filia_id,filia_nazwa FROM $dbname.serwis_filie";
			$result44 = mysql_query($sql44, $conn) or die($k_b);
			$all_filie1 = '|';
			echo "<select name=projekt_filia />";
			echo "<option value='all'"; if (strlen($temp_dla_filii)>2) echo " SELECTED "; echo ">Cały oddział $obszar</option>\n";
			while ($newArray44 = mysql_fetch_array($result44)) {
				$temp_f	= $newArray44['filia_id'];
				$temp_fn = $newArray44['filia_nazwa'];
				$all_filie1 .= $temp_f."|";
				echo "<option value='$temp_f'"; if ($temp_dla_filii==$temp_f) echo " SELECTED "; echo ">$temp_fn</option>\n";
			}
			echo "</select>";
		_td();
	_tr();	
tbl_empty_row();
endtable();
echo "<input type=hidden name=all_filie value='".$all_filie1."'>";
echo "<input type=hidden name=zid value=$temp_id>";	
startbuttonsarea("right");
echo "<input class=buttons type=submit name=submit id=submit value='Zapisz'>";
echo "<input id=anuluj class=buttons type=button onClick=\"if (confirm('Czy napewno chcesz anulować wprowadzone dane ?')) self.close();\" value=Anuluj>";
endbuttonsarea();
_form();//echo "<input type=button value='1' onClick=\"document.getElementById('sub_podkat_id').value = '$temp_podkat2';\" />";
?>
<script language="JavaScript">
var cal1 = new calendar1(document.forms['addz'].elements['dztermin']);
	cal1.year_scroll = true;
	cal1.time_comp = false;
</script>

<?php } ?>
</body>
</html>