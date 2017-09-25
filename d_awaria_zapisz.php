<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[1].focus();">
<?php 
if ($submit) { 
$_POST=sanitize($_POST);
$sql_t = "INSERT INTO $dbname.serwis_awarie VALUES ('', '$_POST[tup1]','$_POST[wanport1]','$_POST[tdatar]','','$_POST[tnrzgl]','$_POST[tip1]','$currentuser','','$_POST[tstatus]',$es_filia)";

if ($_POST[UtworzZgloszenie]=='on') {
	$result44 = mysql_query("SELECT serwis_komorki.up_id, serwis_komorki.up_nazwa, serwis_piony.pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) and (serwis_komorki.up_nazwa='$_POST[tup1]') LIMIT 1", $conn) or die($k_b);
	list($temp_id,$temp_nazwa,$temp_pion) = mysql_fetch_array($result44);
	$komorka = $temp_pion ." ".$temp_nazwa;
}

if (mysql_query($sql_t, $conn)) { 
	?><script> 
		if (opener) opener.location.reload(true); 
		self.close(); 
		<?php if ($_POST[UtworzZgloszenie]=='on') { ?>
		newWindow_r(800,600,'hd_d_zgloszenie.php?stage=1&special=wan&action=open&up=<?php echo urlencode($komorka); ?>&nrzglawarii=<?php echo $_POST[tnrzgl]; ?>');
		<?php } ?>
		</script><?php
} else {
	?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php	
	}
} else { ?>
<?php
pageheader("Rejestracja awarii łącza WAN");
$dddd = Date('Y-m-d H:i:s');
starttable();
echo "<form name=aw action=$PHP_SELF method=POST>";
tbl_empty_row();

$result445 = mysql_query("SELECT up_nrwanportu,up_ip,up_nazwa,up_id FROM $dbname.serwis_komorki WHERE ((belongs_to=$es_filia) and (up_nazwa='$_POST[tup]')) LIMIT 1", $conn) or die($k_b);
list($temp1_nrwanportu,$temp1_ip,$temp_up_nazwa,$temp_up_id)=mysql_fetch_array($result445);
tr_();
	td("150;r;Miejsce awarii");
	$result_tel = mysql_query("SELECT up_telefon FROM $dbname.serwis_komorki WHERE ((belongs_to=$es_filia) and (up_nazwa='$_POST[tup]')) LIMIT 1", $conn) or die($k_b);
	list($temp_telefon)=mysql_fetch_array($result_tel);
	
	$sql_up = "SELECT up_id, up_pion_id FROM $dbname.serwis_komorki WHERE (up_nazwa='$temp_up_nazwa') and (belongs_to=$es_filia) LIMIT 1";
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
	
	td_(";l;<b>".$temp_pion_nazwa." ".$_POST[tup]."</b> (telefon: <b>$temp_telefon</b>)");
		echo "<a title=' Szczegółowe informacje o $temp_pion_nazwa $temp_up_nazwa '><input class=imgoption align=absmiddle type=image src=img/detail.gif onclick=\"newWindow_r(700,600,'p_komorka_szczegoly.php?id=$temp_up_id'); return false;\"></a>";	
	_td();
_tr();
tr_();
	td("150;r;Nr WAN-portu");
	td_(";l;<b>".$temp1_nrwanportu."</b>");
	_td();
_tr();
tr_();
	td("150;r;IP router'a");
	td_(";l;<b>".$temp1_ip.".1</b>");
	_td();
_tr();
tr_();
	td("150;r;Stan łącza");
	td_(";l;");
// 12.05.2009 - wyłączenie sprawdzania czy działa łącze
/*	
	$cmd = shell_exec("ping -n 1 -w 2500 $temp1_ip.1");
	$ping_results = explode(",",$cmd);
	$ping_results2 = explode(":",$cmd);
	if (eregi("Odebrane = 0", $ping_results[1], $out) or eregi("H",$ping_results2[2][1],$out)) {
		echo "<img class=imgoption align=absmiddle src=img/off.gif title=' Brak łączności lub wyłączony '><sub> ( brak łączności z routerem )</sub>";				
	}
	if (eregi("Odebrane = 1", $ping_results[1], $out)) {
		if (eregi("H",$ping_results2[2][1],$out)==FALSE) {
			echo "<img align=absmiddle src=img/on.gif title=' Łącze działa prawidłowo '><sub> ( jest łączność z routerem )</sub>";
		}				  
	}
*/
// koniec
	echo "<i>f-cja wyłączona</i>";
	_td();
_tr();
tr_();
	td("150;r;Data wystąpienia awarii");
	td_(";l;");
		echo "<input size=18 maxlength=19 type=text name=tdatar value='$dddd' onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();
tr_();
	td("150;r;Numer zgłoszenia");
	td_(";l;");
		echo "<input class=wymagane size=18 maxlength=20 type=text name=tnrzgl onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();
tbl_empty_row();	
endtable();
startbuttonsarea("right");

//echo "<a onClick=\"if (document.getElementById('UtworzZgloszenie').checked) { document.getElementById('UtworzZgloszenie').checked=false; } else { document.getElementById('UtworzZgloszenie').checked=true; } \"><b><font color=red>Po zapisaniu utwórz zgłoszenie w bazie Helpdesk</font></b></a>";
//echo "&nbsp;<input style='border:0px' type=checkbox id=UtworzZgloszenie name=UtworzZgloszenie>&nbsp;";

addbuttons("zapisz");
addbackbutton("Wybierz inną komórkę / UP");
addbuttons("anuluj");
endbuttonsarea();
echo "<input type=hidden name=tup1 value='$_POST[tup]'>";
echo "<input type=hidden name=wanport1 value='$temp1_nrwanportu'>";
echo "<input type=hidden name=tip1 value='$temp1_ip.1'>";
_form();
?>
<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("aw");
  frmvalidator.addValidation("tnrzgl","req","Nie wpisano numeru zgloszenia");
  frmvalidator.addValidation("tnrzgl","numeric","Błędnie wpisany numer zgłoszenia (dozwolone są tylko cyfry)");
</script>	
<?php } ?>
</body>
</html>