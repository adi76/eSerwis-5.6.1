<?php 
include_once('header.php');
include_once('cfg_helpdesk.php');
if ($WlaczMaile=='1') require_once('phpMailer/class.phpmailer.php');
if ($WlaczMaile=='1') require_once('cfg_mails.php');
?>

<body OnLoad="document.forms[0].elements[1].focus();" />
<?php

if ($submit) {

if (smtpmailer_dowolny_mail($_POST[koord_email], 'helpdesk-lodz@postdata.pl', 'Helpdesk - O/Łódź', $_POST[temat], $_POST[tresc], $last_nr,$_POST[koord_email])) {
	echo "<h3>Email został wysłany do $koord ($koord_email)</h3>";
	//$emailSend = 1;
}
if (!empty($error)) echo $error;

startbuttonsarea("right");
echo "<input class=buttons type=button onClick=\"self.close();\" value=Zamknij>";
endbuttonsarea();

} else {
pageheader("Mail do koordynatora");
starttable();
echo "<form id=hd_email name=hd_email action=$PHP_SELF method=POST />";

$result44 = mysql_query("SELECT user_email, user_allow_emails FROM $dbname.serwis_uzytkownicy WHERE (belongs_to=$es_filia) and (user_locked='0') and (user_allow_emails='1')  and (user_id='$es_nr') LIMIT 1", $conn) or die($k_b);
list($temp_email, $temp_allow) = mysql_fetch_array($result44);

$r3 = mysql_query("SELECT zgl_komorka FROM $dbname_hd.hd_zgloszenie WHERE ((zgl_nr='$_REQUEST[zglnr]') and (belongs_to='$es_filia')) LIMIT 1", $conn_hd) or die($k_b);
list($KomorkaNazwa)=mysql_fetch_array($r3);

$KomorkaNazwa = substr($KomorkaNazwa,strpos($KomorkaNazwa,' ')+1,strlen($KomorkaNazwa));
	
$result44 = mysql_query("SELECT up_umowa_id FROM $dbname.serwis_komorki WHERE (belongs_to=$es_filia) and (up_active=1) and (UPPER(up_nazwa)='$KomorkaNazwa') LIMIT 1", $conn) or die($k_b);
list($temp_um_id) = mysql_fetch_array($result44);
	
$r4 = mysql_query("SELECT umowa_koordynator, umowa_koordynator_email FROM $dbname.serwis_umowy WHERE ((umowa_id='$temp_um_id') and (belongs_to='$es_filia')) LIMIT 1", $conn) or die($k_b);
list($koord, $koord_email)=mysql_fetch_array($r4);

tbl_empty_row(2);
	tr_();
		td("50;r;<b>Od</b>");
		td_(";;;");
			echo $currentuser."&nbsp;";
			
			if ($temp_allow=='1') {
				if ($temp_email!='') {
					echo " (".$temp_email.")";
				} else {
					echo "<font color=red>(nie masz zdefiniowanego adresu email w bazie)</font>";
				}
			} else {
				echo "<font color=red>(nie masz włączonej opcji otrzymywania maili)</font>";
			}
		_td();
	_tr();
	tr_();
		td("50;r;<b>Do</b>");
		td_(";;;");
			echo $koord." (".$koord_email.")";
		_td();
	_tr();
	tr_();
		td("50;r;<b>Temat</b>");
		td_(";;;");
			echo "<input type=text name=temat size=60 value='Dotyczy zgłoszenia nr $_GET[zglnr]'>";
		_td();
	_tr();
	tr_();
		td("50;rt;<b>Treść</b>");
		td_(";;;");
			echo "<textarea name=tresc cols=48 rows=10></textarea>";
		_td();
	_tr();

tbl_empty_row(2);
endtable();

startbuttonsarea("right");
echo "<input id=submit type=submit class=buttons name=submit value='Wyślij' onClick=\"document.getElementById('anuluj').style.display='none';document.getElementById('submit').style.display='none';document.getElementById('Sending').style.display='';\" />";
//echo "<input id=reset type=button class=buttons name=reset value=\"Wyczyść zgłoszenie\" onClick=\"pytanie_wyczysc('Wyczyścić formularz ?'); \" />";
echo "<input id=anuluj class=buttons type=button onClick=\"self.close();\" value=Anuluj>";
echo "<span id=Sending style=display:none><b>Trwa wysyłanie maila...proszę czekać</b></span>";
endbuttonsarea();

echo "<input type=hidden name=od value='$temp_email'>";
echo "<input type=hidden name=koord_email value='$koord_email'>";

echo "</form>";
}

?>

</body>
</html>