<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php
if ($submit) {
/*
	$sql_usun_z_serwisu = "UPDATE $dbname.serwis_naprawa SET naprawa_sprzet_zastepczy_id=0 WHERE naprawa_id=$_POST[uid] LIMIT 1";
	if (mysql_query($sql_usun_z_serwisu,$conn)) {
	} else {
			?><script>info('Wystąpił błąd podczas usuwania z napraw'); //self.close(); </script><?php
		}
		
	$sql_wroc_sprzet_na_magazyn = "UPDATE $dbname.serwis_magazyn SET magazyn_status=0, magazyn_naprawa_id='0' WHERE magazyn_id=$_POST[szid] LIMIT 1";
	if (mysql_query($sql_wroc_sprzet_na_magazyn,$conn)) {
	} 

	$dddd = Date('Y-m-d H:i:s');
	$sql_t = "INSERT INTO $dbname.serwis_historia VALUES ('','$_POST[szid]','$_POST[tup]','$currentuser','$dddd','zwrócono z','','$_POST[uid]',$es_filia)";
	if (mysql_query($sql_t, $conn)) {}

	?><script>opener.location.reload(true); self.close(); </script><?php
	*/
} else {

$result8 = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni FROM $dbname.serwis_magazyn WHERE ((belongs_to=$es_filia) and (magazyn_id=$sz)) LIMIT 1", $conn) or die($k_b);
list($temp_id,$temp_nazwa,$temp_model,$temp_sn,$temp_ni)=mysql_fetch_array($result8);

if ($_REQUEST[from]=='hd') {
	$tup = $up;
} else { 
	$sql21 = "SELECT up_id, up_nazwa, pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.up_nazwa='$tup') and (serwis_piony.pion_id=serwis_komorki.up_pion_id) and (belongs_to=$es_filia) LIMIT 1";
	$wynik21 = mysql_query($sql21,$conn);
	list($upid,$nazwa,$pion) = mysql_fetch_array($wynik21);
}
		
if ($temp_ni=='') $temp_ni = '-';
errorheader("Czy napewno chcesz wycofać sprzęt serwisowy z placówki ?<br /><br /><font color=white>$tup</font>");
infoheader("<b>Sprzęt serwisowy: </b><br /><br />Typ sprzętu: <b>".$temp_nazwa." ".$temp_model."</b><br />SN: <b>".$temp_sn."</b> | NI: <b>".$temp_ni."</b>");
?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
oddziel();
echo "<form name=edu action=utworz_protokol.php method=POST>";

starttable();
	tr_();
		td("150;rt;Uwagi");
		td_(";;");
			echo "<textarea name=tuwagi cols=50 rows=6>".cleanup(cleanup(($_REQUEST[tuwagi])))."</textarea>";
		_td();
	_tr();


	tr_();
		td("150;r;Nr zgłoszenia Helpdesk");
		td_(";;");	
			if ($_REQUEST[hd_zgl_nr]>0) {
				if ($_REQUEST[from]=='hd') {
					echo "<input type=hidden name=hd_zgl_nr value='$_REQUEST[hd_zgl_nr]'>";
					echo "<b>$_REQUEST[hd_zgl_nr]</b>";
					
					$testUP = $_REQUEST[up];
					if ($testUP == '') $testUP = $_REQUEST[tup];
					
					$sql21 = "SELECT serwis_komorki.up_nazwa,serwis_komorki.up_id FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) and (UCASE(CONCAT(serwis_piony.pion_nazwa,' ',serwis_komorki.up_nazwa)='".$testUP."'))) LIMIT 1";
					$wynik21 = mysql_query($sql21,$conn);
					list($tup,$upid) = mysql_fetch_array($wynik21);
					
					if ($upid=='') {
						$sql21 = "SELECT serwis_komorki.up_nazwa,serwis_komorki.up_id FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) and ((serwis_komorki.up_nazwa)='".$testUP."')) LIMIT 1";
						$wynik21 = mysql_query($sql21,$conn);
						list($tup,$upid) = mysql_fetch_array($wynik21);
					}
					
				} else {
					echo "<input class=wymagane type=text id_hd_zgl_nr name=hd_zgl_nr value='$_REQUEST[hd_zgl_nr]' size=10 maxlength=10 onKeyPress=\"return filterInput(1, event, false); \">";
				}
				echo "&nbsp;&nbsp;<input type=button class=buttons value='Szczegóły zgłoszenia' onClick=\"PokazZgloszenie($_REQUEST[hd_zgl_nr]); \">";
			} else {
				echo "<input class=wymagane type=text id=hd_zgl_nr name=hd_zgl_nr value='' size=10 maxlength=10 onKeyPress=\"return filterInput(1, event, false); \">";
				echo "&nbsp;&nbsp;<input type=button class=buttons value='Szczegóły zgłoszenia' onClick=\"PokazZgloszenie(document.getElementById('hd_zgl_nr').value); \">";
			}
			
		_td();
	_tr();

	endtable();
// *******************
session_register('wykonaj_magazyn_zwrot');	$_SESSION[wykonaj_magazyn_zwrot]=0;
// *******************

echo "<input type=hidden name=szid value='$_REQUEST[sz]'>";
echo "<input type=hidden name=uid value='$id'>";
echo "<input type=hidden name=szid value=$sz>";
echo "<input type=hidden name=tup value='$_REQUEST[tup]'>";
echo "<input type=hidden name=up value='$up'>";

echo "<input type=hidden name=cs value='$cs'>";	
echo "<input type=hidden name=ewid value='$ewid_id'>";

echo "<input type=hidden name=c_4 value='on'>";
echo "<input type=hidden name=state value='empty'>";
echo "<input type=hidden name=blank value='1'>";
echo "<input type=hidden name=new_upid value=$upid>";
echo "<input type=hidden name=source value='magazyn-zwrot'>";
echo "<input type=hidden name=naprawa_pozostaje value=1>";
echo "<input type=hidden name=from value='$_REQUEST[from]'>";

echo "<input type=hidden name=mnazwa value='$temp_nazwa'>";
echo "<input type=hidden name=mmodel value='$temp_model'>";
echo "<input type=hidden name=msn value='$temp_sn'>";
if ($temp_ni=='') $temp_ni='-';
echo "<input type=hidden name=mni value='$temp_ni'>";

startbuttonsarea("center");

if ($_REQUEST[info]!='0') {
	infoheader("<font color=red>Aby wycofanie sprzętu serwisowego było widoczne w krokach zgłoszenia, wykonaj je z poziomu obsługi zgłoszenia</font>");
	echo "<br />";
}

addbuttons("tak","nie");
endbuttonsarea();
_form();
}
?>
<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("edu");
  frmvalidator.addValidation("hd_zgl_nr","req","Nie podano numeru zgłoszenia Helpdesk");  
</script>
<script>HideWaitingMessage();</script>
</body>
</html>