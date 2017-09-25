<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php
if ($submit) {
	$result = mysql_query("SELECT naprawa_uwagi FROM $dbname.serwis_naprawa WHERE (naprawa_id=$_POST[uid]) LIMIT 1", $conn) or die($k_b);
	if (mysql_num_rows($result)>0) { 
		list($temp_uwagi)=mysql_fetch_array($result);
	}
	$dddd = Date("Y-m-d H:i:s");	
	$dw = Date("Y-m-d");
	if ($_POST[data_wycofania]!='') $dw=$_POST[data_wycofania];	
	$powod_wycofania = $_POST[powod];
	if ($powod_wycofania=='inny') $powod_wycofania=nl2br($_POST[powod_inny]);
	
	if ($_POST[szid]=='0') {
		$sql_usun_z_serwisu = "UPDATE $dbname.serwis_naprawa SET naprawa_status=7, naprawa_powod_wycofania_z_serwisu='$powod_wycofania', naprawa_data_wycofania='$dw', naprawa_osoba_wycofujaca_sprzet_z_serwisu='$currentuser', naprawa_wycofanie_timestamp='$dddd' WHERE naprawa_id=$_POST[uid] LIMIT 1";	

		if (mysql_query($sql_usun_z_serwisu,$conn)) {
		} else {
				?><script>info('Wystąpił błąd podczas usuwania z napraw'); //self.close(); </script><?php
			}
		
		if ($_POST[ewid]!='') {
			$sql_zmien_status_w_ewid = "UPDATE $dbname.serwis_ewidencja SET ewidencja_status=9 WHERE ewidencja_id=$_POST[ewid] LIMIT 1";
			if (mysql_query($sql_zmien_status_w_ewid,$conn)) {
			} else {
				?><script>info('Wystąpił błąd podczas zmiany statusu'); // self.close(); </script><?php
			}
		}
		?><script> self.close(); </script><?php

	} else {
		$sql_usun_z_serwisu = "UPDATE $dbname.serwis_naprawa SET naprawa_status=7, naprawa_powod_wycofania_z_serwisu='$powod_wycofania', naprawa_data_wycofania='$dddd', naprawa_osoba_wycofujaca_sprzet_z_serwisu='$currentuser', naprawa_wycofanie_timestamp='$dw' WHERE naprawa_id=$_POST[uid] LIMIT 1";
		if (mysql_query($sql_usun_z_serwisu,$conn)) {
		} else {
			?><script>info('Wystąpił błąd podczas usuwania z napraw'); //self.close(); </script><?php
		}
		if ($_POST[ewid_id]!='0') {		
			$sql_zmien_status_w_ewid = "UPDATE $dbname.serwis_ewidencja SET ewidencja_status=7 WHERE ewidencja_id=$_POST[ewid_id] LIMIT 1";
			if (mysql_query($sql_zmien_status_w_ewid,$conn)) {
			} else {
				?><script>info('Wystąpił błąd podczas aktualizowania ewidencji'); </script><?php
			}
		}
		?><script> self.close(); </script><?php
	}
	?><script>if (opener) opener.location.reload(true); //self.close(); </script><?php	
} else {

$result = mysql_query("SELECT naprawa_id,naprawa_nazwa,naprawa_model,naprawa_sn,naprawa_status,naprawa_sprzet_zastepczy_id,naprawa_ew_id FROM $dbname.serwis_naprawa WHERE (naprawa_id=$id) LIMIT 1", $conn) or die($k_b);
list($temp_id,$temp_nazwa,$temp_model,$temp_sn,$temp_status,$temp_szid,$temp_ewid)=mysql_fetch_array($result);
errorheader("Czy napewno chcesz wycofać sprzęt z serwisu ?");
infoheader("".$temp_nazwa." <b>".$temp_model."</b><br />SN: <b>".$temp_sn."</b>");
starttable();
echo "<form name=edu action=$PHP_SELF method=POST>";

echo "&nbsp;Data wycofania z serwisu&nbsp;";
echo "<input type=text id=data_wycofania name=data_wycofania size=10 maxlength=10 value='".Date("Y-m-d")."' onkeypress=\"return handleEnter(this, event);\" onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\">";
echo "<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę wycofania sprzętu z serwisu '><img class=imgoption src=img/cal.gif width=16 height=16 border=0></a>";
if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('data_wycofania').value='".Date('Y-m-d')."'; return false;\">";
				
echo "<br /><br />";
echo "<b>&nbsp;Powód wycofania sprzętu z serwisu</b><br /><br />";

echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class=border0 type=radio id=powod1 name=powod value='Bez powodu' checked onClick=\"document.getElementById('powod_inny').style.display='none';\"><a class=normalfont href=# onClick=\"document.getElementById('powod1').checked=true;document.getElementById('powod_inny').style.display='none';\">Bez powodu</a><br /><br />";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class=border0 type=radio id=powod2 name=powod value='Decyzja o likwidacji sprzętu' onClick=\"document.getElementById('powod_inny').style.display='none';\"><a class=normalfont href=# onClick=\"document.getElementById('powod2').checked=true;document.getElementById('powod_inny').style.display='none';\">Decyzja o likwidacji sprzętu</a><br/><br />";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class=border0 type=radio id=powod3 name=powod value='Brak możliwości naprawy' onClick=\"document.getElementById('powod_inny').style.display='none';\"><a class=normalfont href=# onClick=\"document.getElementById('powod3').checked=true;document.getElementById('powod_inny').style.display='none';\">Brak możliwości naprawy</a><br/><br />";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class=border0 type=radio id=powod4 name=powod value='Brak zgody na naprawę' onClick=\"document.getElementById('powod_inny').style.display='none';\"><a class=normalfont href=# onClick=\"document.getElementById('powod4').checked=true; document.getElementById('powod_inny').style.display='none';\">Brak zgody na naprawę</a><br /><br />";

echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class=border0 type=radio id=powod5 name=powod value='inny' onClick=\"document.getElementById('powod_inny').style.display='';document.getElementById('powod_inny').focus();\"><a class=normalfont href=# onClick=\"document.getElementById('powod5').checked=true; document.getElementById('powod_inny').style.display='';document.getElementById('powod_inny').focus();\">Inny</a>&nbsp;";

echo "<br /><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<textarea rows=2 cols=55 name=powod_inny id=powod_inny style='display:none'></textarea><br />";

echo "<input type=hidden name=szid value='$_REQUEST[sz]'>";
echo "<input type=hidden name=uid value='$temp_id'>";
echo "<input type=hidden name=szid value=$temp_szid>";
echo "<input type=hidden name=tup1 value='$tup'>";	
echo "<input type=hidden name=ewid value=$temp_ewid>";	
echo "<input type=hidden name=ewid_id value=$ewid_id>";	


br();
startbuttonsarea("center");
addbuttons("tak","nie");
endbuttonsarea();
_form();
}
?>
<script language="JavaScript">
var cal1 = new calendar1(document.forms['edu'].elements['data_wycofania']);
	cal1.year_scroll = true;
	cal1.time_comp = false;
</script>

</body>
</html>