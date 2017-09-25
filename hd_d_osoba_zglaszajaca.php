<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body onLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) { 
	$_POST=sanitize($_POST);
	$seryjne=0;
	if ($_POST[up_id]==0) $seryjne=1;
	$sql_a = "INSERT INTO $dbname_hd.hd_komorka_pracownicy values ('', '$_POST[up_id]','$_POST[osoba_zgl]','$_POST[osoba_tel]','$seryjne','$currentuser',$es_filia)";
	if (mysql_query($sql_a, $conn_hd)) { 
			?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else {
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
	}
} else {
pageheader("Dodawanie osoby zgłaszającej");
//$result = mysql_query("SELECT hd_komorka_pracownicy_id,hd_serwis_komorka_id,hd_komorka_pracownicy_nazwa,hd_komorka_pracownicy_telefon FROM $dbname_hd.hd_komorka_pracownicy WHERE (hd_komorka_pracownicy_id=$id) LIMIT 1", $conn_hd) or die($k_b);

//list($temp_id,$temp_komorka_id,$temp_osoba,$temp_telefon)=mysql_fetch_array($result);
starttable();
echo "<form name=add action=$PHP_SELF method=POST>";
tbl_empty_row();
tr_();
	td("100;r;Osoba zgłaszająca");
	td_(";;");
		echo "<input class=wymagane size=40 maxlength=200 type=text name=osoba_zgl value='' onBlur=\"cUpper(this); \" onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();
tr_();
	td("100;r;Telefon");
	td_(";;");
		echo "<input class=wymagane size=40 maxlength=200 type=text name=osoba_tel value='' onkeypress='return handleEnter(this, event);'>";
	_td();
_tr();

	tr_();
		td("100;r;Komórka");
		td_(";;");
		
			echo "<select class=wymagane name=up_id onkeypress='return handleEnter(this, event);'>";			
			$result1 = mysql_query("SELECT serwis_komorki.up_id, serwis_komorki.up_nazwa, serwis_piony.pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) ORDER BY serwis_piony.pion_nazwa,up_nazwa", $conn) or die($k_b);
			echo "<option></option>\n";
			echo "<option value=0>-- dla zgłoszeń seryjnych --</option>\n";
			while (list($temp_up_id,$temp_up_nazwa,$temp_up_pion)=mysql_fetch_array($result1)) {
				echo "<option value=$temp_up_id>$temp_up_pion $temp_up_nazwa</option>\n";
			}
			echo "</select>";
		_td();
	_tr();
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
  frmvalidator.addValidation("up_id","dontselect=0","Nie wybrałeś komórki");
</script>	
<?php } ?>
</body>
</html>