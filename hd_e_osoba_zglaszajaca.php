<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body onLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) { 
	$_POST=sanitize($_POST);
	$sql_a = "UPDATE $dbname_hd.hd_komorka_pracownicy SET hd_serwis_komorka_id='$_POST[up_id]', hd_komorka_pracownicy_nazwa='$_POST[osoba_zgl]', hd_komorka_pracownicy_telefon='$_POST[osoba_tel]', hd_aktualizowane_przez = '$currentuser' WHERE hd_komorka_pracownicy_id=$_POST[id] LIMIT 1";
	if (mysql_query($sql_a, $conn)) { 
			?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else {
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
	}
} else {
pageheader("Edycja danych o osobie zgłaszającej");
$result = mysql_query("SELECT hd_komorka_pracownicy_id,hd_serwis_komorka_id,hd_komorka_pracownicy_nazwa,hd_komorka_pracownicy_telefon FROM $dbname_hd.hd_komorka_pracownicy WHERE (hd_komorka_pracownicy_id=$id) LIMIT 1", $conn_hd) or die($k_b);

list($temp_id,$temp_komorka_id,$temp_osoba,$temp_telefon)=mysql_fetch_array($result);
starttable();
echo "<form name=add action=$PHP_SELF method=POST>";
tbl_empty_row();
tr_();
	td("100;r;Osoba zgłaszająca");
	td_(";;");
		echo "<input class=wymagane size=40 maxlength=200 type=text name=osoba_zgl value='".strtoupper($temp_osoba)."' onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();
tr_();
	td("100;r;Telefon");
	td_(";;");
		echo "<input class=wymagane size=40 maxlength=200 type=text name=osoba_tel value='$temp_telefon' onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();

if ($temp_komorka_id!=0) {
	tr_();
		td("100;r;Komórka");
		td_(";;");
		//echo "SELECT serwis_komorki.up_id, serwis_komorki.up_nazwa, serwis_piony.pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) ORDER BY serwis_piony.pion_nazwa,up_nazwa<br />";
		
			echo "<select class=wymagane name=up_id onkeypress='return handleEnter(this, event);'>";			
			$result1 = mysql_query("SELECT serwis_komorki.up_id, serwis_komorki.up_nazwa, serwis_piony.pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) ORDER BY serwis_piony.pion_nazwa,up_nazwa", $conn) or die($k_b);
			while (list($temp_up_id,$temp_up_nazwa,$temp_up_pion)=mysql_fetch_array($result1)) {
				$cala_nazwa = $temp_up_pion.' '.$temp_up_nazwa;
				echo "<option value=$temp_up_id";
				if ($temp_up_id==$temp_komorka_id) echo " SELECTED ";
				echo ">$temp_up_pion $temp_up_nazwa</option>\n";
			}
			echo "</select>";
		_td();
	_tr();
} else {
	echo "<input type=hidden name=up_id value=0>";
}
echo "<input type=hidden name=id value=$temp_id>";
tbl_empty_row();	
endtable();
startbuttonsarea("right");
addbuttons("zapisz","anuluj");
endbuttonsarea();
_form();
?>
<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("add");
  frmvalidator.addValidation("osoba_zgl","req","Nie podano osoby zgłaszającej");
  frmvalidator.addValidation("osoba_tel","req","Nie podano numeru telefonu");
//  frmvalidator.addValidation("dfkier","dontselect=0","Nie wybrałeś kierownika");
</script>	
<?php } ?>
</body>
</html>