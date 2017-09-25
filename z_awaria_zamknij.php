<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 
	$datazam = $_POST[ddate];

	$sql_e1b="UPDATE $dbname.serwis_awarie SET awaria_datazamkniecia  = '$datazam', awaria_osobazamykajaca = '$_POST[osobazam]', awaria_status  = '1' WHERE awaria_id = '$_POST[uid]' LIMIT 1";
	
	if ($_POST[UtworzZgloszenie]=='on') {
		$result44 = mysql_query("SELECT serwis_komorki.up_id, serwis_komorki.up_nazwa, serwis_piony.pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) and (serwis_komorki.up_nazwa='$_POST[tup1]') LIMIT 1", $conn) or die($k_b);
		list($temp_id,$temp_nazwa,$temp_pion) = mysql_fetch_array($result44);
		$komorka = $temp_pion ." ".$temp_nazwa;
	}
	
	if (mysql_query($sql_e1b, $conn)) { 
		?><script>
		if (opener) opener.location.reload(true); 
		self.close(); 
		<?php if ($_POST[UtworzZgloszenie]=='on') { ?>
		newWindow_r(800,600,'hd_d_zgloszenie.php?stage=1&special=wan&action=close&up=<?php echo urlencode($komorka); ?>&nrzglawarii=<?php echo $_POST[tnrzgl]; ?>&czastrwania=<?php echo urlencode($_POST[czastrwania]); ?>');
		<?php } ?>
		</script><?php
	} else {
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php	
	}
} else { ?>
<?php
$result444 = mysql_query("SELECT awaria_id,awaria_gdzie,awaria_nrwanportu,awaria_nrzgloszenia,awaria_datazgloszenia FROM $dbname.serwis_awarie WHERE (awaria_id=$id) LIMIT 1", $conn) or die($k_b);
list($temp_id,$temp_gdzie,$temp_nrwanportu,$temp_nrzgl,$temp_data)=mysql_fetch_array($result444);
$dddd = Date('Y-m-d H:i:s');
okheader("Czy napewno chcesz zamknąć poniższe zgłoszenie awarii ?<br />");

$sql_up = "SELECT up_id, up_pion_id FROM $dbname.serwis_komorki WHERE (up_nazwa='$temp_gdzie') and (belongs_to=$es_filia) LIMIT 1";
$wynik = mysql_query($sql_up, $conn) or die($k_b);
$dane_up = mysql_fetch_array($wynik);
$temp_up_id = $dane_up['up_id'];
$temp_pion_id = $dane_up['up_pion_id'];
	
// nazwa pionu z id pionu
$sql_get_pion = "SELECT pion_nazwa FROM $dbname.serwis_piony WHERE (pion_id='$temp_pion_id') LIMIT 1";
$wynik_get_pion = mysql_query($sql_get_pion, $conn) or die($k_b);
$dane_get_pion = mysql_fetch_array($wynik_get_pion);
$temp_pion_nazwa = $dane_get_pion['pion_nazwa'];
// koniec ustalania nazwy pionu
				
infoheader("<b>".$temp_pion_nazwa." ".$temp_gdzie."</b> | Nr zgłoszenia : <b>".$temp_nrzgl."</b>");
startbuttonsarea("center");
echo "<form name=ez action=$PHP_SELF method=POST>";
starttable();
tbl_empty_row();
echo "<tr>";
echo "<td class=right>Data zamknięcia zgłoszenia</td>";
echo "<td>";
echo "<input size=19 maxlength=19 class=wymagane type=text id=ddate name=ddate value='".Date('Y-m-d H:i:s')."' onkeypress='return handleEnter(this, event);' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\">";
echo "<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif align=abstop width=16 height=16 border=0></a>";
if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('ddate').value='".Date('Y-m-d')."'; return false;\">";
echo "</td>";
echo "</tr>";
tbl_empty_row();	
endtable();	
echo "<input type=hidden name=uid value=$temp_id>";
echo "<input type=hidden name=osobazam value='$currentuser'>";
echo "<input type=hidden name=tup1 value='$temp_gdzie'>";
echo "<input type=hidden name=tnrzgl value='$temp_nrzgl'>";
echo "<input type=hidden name=datazgl value='$temp_data'>";
echo "<input type=hidden name=czastrwania value='$_GET[czastrwania]'>";



//echo "<input type=hidden name=datazam value='$dddd'>";

//echo "<a onClick=\"if (document.getElementById('UtworzZgloszenie').checked) { document.getElementById('UtworzZgloszenie').checked=false; } else { document.getElementById('UtworzZgloszenie').checked=true; } \"><b><font color=red>Po zapisaniu utwórz zgłoszenie w bazie Helpdesk</font></b></a>";
//echo "&nbsp;<input style='border:0px' type=checkbox id=UtworzZgloszenie name=UtworzZgloszenie>&nbsp;";


addbuttons("tak","nie");
endbuttonsarea();
_form();

?>
<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("ez");
  frmvalidator.addValidation("ddate","req","Nie podano daty zakończenia awarii");
</script>

<script language="JavaScript">
var cal1 = new calendar1(document.forms['ez'].elements['ddate']);
	cal1.year_scroll = true;
	cal1.time_comp = true;
</script>

<?php
}
?>
</body>
</html>