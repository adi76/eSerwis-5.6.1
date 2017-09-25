<?php include_once('header_begin.php'); ?>
	<SCRIPT>
		function validateForm() 
		{
			if (document.ped.passold.value=="") { alert('Nie podano starego hasła'); document.ped.passold.focus(); return false; }		
			if (document.ped.passnew1.value=="") { alert('Nie podano nowego hasła (1 raz)'); document.ped.passnew1.focus(); return false; }		
			if (document.ped.passnew2.value=="") { alert('Nie podano nowego hasła (2 raz)'); document.ped.passnew2.focus(); return false; }		
		}
	</SCRIPT>
</head>
<body onload="document.forms[0].elements[0].focus();">
<?php 
if ($submit) { 
	?><script>ShowWaitingMessage('Trwa zapisywanie danych...', 'Saving1');</script>
	<?php
	$result = mysql_query("SELECT user_id,user_login,user_pass FROM $dbname.serwis_uzytkownicy WHERE (user_id=$es_nr) LIMIT 1", $conn) or die($k_b);
	list($temp_id,$temp_login,$temp_pass)=mysql_fetch_array($result);
	if (md5($_POST['passold'])==$temp_pass) {
		if ($_POST['passnew1']==$_POST['passnew2']) {
			$sql_e1="UPDATE $dbname.serwis_uzytkownicy SET user_pass=md5('$_POST[passnew1]') WHERE user_id = $_POST[pid] LIMIT 1";
			if (mysql_query($sql_e1, $conn)) { 
				okheader("Pomyślnie zmieniono hasło");
				startbuttonsarea("right");		
				addbuttons("zamknij");
				endbuttonsarea();
				?><script>HideWaitingMessage('Saving1');</script><?php 
			} else {
				errorheader("Wystąpił błąd podczas zmiany hasła");
				startbuttonsarea("right");		
				addbackbutton("Spróbuj jeszcze raz");
				addbuttons("zamknij");
				endbuttonsarea();
				?><script>HideWaitingMessage('Saving1');</script><?php 
			}
		} else {
			errorheader("Nowe hasło musisz podać poprawnie 2 razy");
			startbuttonsarea("right");		
			addbackbutton("Spróbuj jeszcze raz");
			addbuttons("zamknij");
			endbuttonsarea();
			?><script>HideWaitingMessage('Saving1');</script><?php 
			}
	} else {
		errorheader("Błędnie podane stare hasło");
		startbuttonsarea("right");		
		addbackbutton("Spróbuj jeszcze raz");
		addbuttons("zamknij");
		endbuttonsarea();
		?><script>HideWaitingMessage('Saving1');</script><?php 
	}
} else { 
pageheader("Zmiana hasła");
$result = mysql_query("SELECT user_id,user_login,user_pass FROM $dbname.serwis_uzytkownicy WHERE (user_id=$es_nr) LIMIT 1", $conn) or die($k_b);
list($temp_id,$temp_login,$temp_pass)= mysql_fetch_array($result);
starttable();
echo "<form name=ped action=$PHP_SELF method=POST onsubmit='return validateForm();'>";
tbl_empty_row();
	tr_();
		td("150;r;Stare hasło");
		td_(";;;");
			echo "<input class=wymagane size=30 type=password name=passold onkeypress='return handleEnter(this, event);'";
		_td();
	_tr();
	tr_();
		td("150;r;Nowe hasło");
		td_(";;;");
			echo "<input class=wymagane size=30 type=password name=passnew1 onkeypress='return handleEnter(this, event);'>";
		_td();
	_tr();
	tr_();
		td("150;r;Nowe hasło (powtórz)");
		td_(";;;");
			echo "<input class=wymagane size=30 type=password name=passnew2 onkeypress='return handleEnter(this, event);'>";
		_td();
	_tr();
tbl_empty_row();
endtable();
echo "<input size=30 type=hidden name=pid value=$temp_id>";	
startbuttonsarea("right");
addbuttons("zapisz","anuluj");
endbuttonsarea();
_form();
} 
?>
</body>