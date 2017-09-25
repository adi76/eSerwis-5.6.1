<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body onLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) { 
$dddd=Date("Y-m-d H:i:s");
$_POST=sanitize($_POST);
$note_name1 = $_POST[note_name];
if ($_POST[note_name]=='') $note_name1 = substr($_POST[note_tresc],0,20);
$note_alert1 = $_POST[note_alertdate];
if ($_POST[note_alertdate]=='') $note_alert1 = '0000-00-00';

	$sql_a = "INSERT INTO $dbname_hd.hd_notes VALUES ('',$_POST[note_osoba],'$note_name1','$_POST[note_tresc]','$note_alert1',1,$es_nr,'$dddd',$es_filia)";
	//echo $sql_a;
	
	if (mysql_query($sql_a, $conn)) { 
		?>
		<script>
		self.close();
		if (opener) opener.document.getElementById('notes_refresh').click();	
		</script><?php
	} else {
	  ?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
	}
} else { 
pageheader("Dodawanie nowej notatki");
starttable();
echo "<form name=add action=$PHP_SELF method=POST>";
tbl_empty_row();
tr_();
	td("100;r;Skrócona nazwa");
	td_(";;");
		echo "<input size=30 maxlength=20 type=text name=note_name> (max. 20 znaków)";
	_td();
_tr();
tr_();
	td("100;rt;Treść notatki");
	td_(";;");
		echo "<textarea class=wymagane cols=55 rows=8 name=note_tresc></textarea>";
	_td();
_tr();

$accessLevels = array("0","1","9");
if(array_search($es_prawa, $accessLevels)>-1) {
	tr_();
		td("100;r;Adresat notatki");
		td_(";;");
			$sql44="SELECT user_id, user_first_name, user_last_name FROM $dbname.serwis_uzytkownicy ";
			if ($es_m!=1) $sql44 = $sql44."WHERE (belongs_to=$es_filia) AND (user_locked=0) ";
			$sql44 = $sql44."ORDER BY user_last_name ASC";
			$result44 = mysql_query($sql44, $conn) or die($k_b);
			$count_rows = mysql_num_rows($result44);
			$i = 0;
			echo "<select name=note_osoba onkeypress='return handleEnter(this, event);'>\n"; 		
			while (list($temp_id, $temp_imie,$temp_nazwisko)=mysql_fetch_array($result44)) {
				echo "<option value=$temp_id"; 
				if ($temp_id==$es_nr) echo " SELECTED ";
				echo ">$temp_imie $temp_nazwisko</option>\n"; 
			}
			echo "</select>\n";
	_td();
_tr();
} else {
	echo "<input type=hidden name=note_osoba value=$es_nr>";
	//echo "<b>$currentuser</b>";
}
tr_();
	td("100;r;Data przypomnienia");
	td_(";;");
		$dddd=Date('Y-m-d');
		echo "<input size=10 maxlength=10 id=note_alertdate type=text name=note_alertdate onkeypress='return handleEnter(this, event);'>";
		echo "<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif width=16 height=16 border=0></a>&nbsp;";
		if ($_today==1) echo "<input title=' Ustaw datę przypomnienia na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('note_alertdate').value='$dddd'; return false;\">";
	_td();
_tr();

echo "<input type=hidden name=norefresh value='$_GET[norefresh]'>";
tbl_empty_row();
endtable();
startbuttonsarea("right");
addbuttons("zapisz","anuluj");
endbuttonsarea();
_form();
?>
<script language="JavaScript">
var cal1 = new calendar1(document.forms['add'].elements['note_alertdate']);
	cal1.year_scroll = true;
	cal1.time_comp = false;
</script>
<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("add");
  frmvalidator.addValidation("note_tresc","req","Nie podano treści notatki");
</script>	
<?php } ?>
</body>
</html>