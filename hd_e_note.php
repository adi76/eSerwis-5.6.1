<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body onLoad="document.forms[0].elements[<?php if ($_GET[edit]=='0') { echo "2"; } else { echo "0"; } ?>].focus();">
<?php 
if ($submit) { 
	$_POST=sanitize($_POST);

	$note_name1 = $_POST[note_name];
	if ($_POST[note_name]=='') $note_name1 = substr($_POST[note_tresc],0,20);
	$note_alert1 = $_POST[note_alertdate];
	if ($_POST[note_alertdate]=='') $note_alert1 = '0000-00-00';
	$notetresc = $_POST[note_tresc];
	
	if (($_POST[edit]=='0') && ($_POST[note_tresc_add]!='')) $notetresc.='<hr />'.$_POST[note_tresc_add];
	
	$sql_a = "UPDATE $dbname_hd.hd_notes SET note_name='$note_name1', note_tresc='$notetresc', note_alertdate='$note_alert1', note_user_id='$_POST[note_osoba]' WHERE note_id=$_POST[note_id] LIMIT 1";
	
	if (mysql_query($sql_a, $conn_hd)) { 
		$dddd = date("Y-m-d");
		?><script>
		self.close();
		if (opener) opener.document.getElementById('notes_refresh').click();	
		</script><?php
		if ($today!='') {
			?>
			<script>
				if (opener) opener.location.reload(true);
			</script>
			<?php
		}
	} else {
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
	}
} else {

$result = mysql_query("SELECT note_id,note_name,note_tresc, note_alertdate,note_user_id FROM $dbname_hd.hd_notes WHERE (note_id='$_GET[noteid]') and (belongs_to=$es_filia)", $conn_hd) or die($k_b);

list($nid,$nname,$ntresc,$nalertdate,$nuserid)=mysql_fetch_array($result);
pageheader("Notatka: $nname");

$r40 = mysql_query("SELECT user_first_name, user_last_name FROM $dbname.serwis_uzytkownicy WHERE (user_id='$nuserid') LIMIT 1", $conn) or die($k_b);
list($to_fname,$to_lname)=mysql_fetch_array($r40);
$to_person = $to_fname.'+'.$to_lname;

if ($_GET[edit]=='0') infoheader('Notatka utworzona przez <b>'.$_GET[cr_name].'</b><br />Data utworzenia <b>'.$_GET[cr_date].'</b>');

starttable();
echo "<form name=ed action=$PHP_SELF method=POST>";
tbl_empty_row();
tr_();
	td("100;r;Skrócona nazwa");
	td_(";;");
		if ($_GET[edit]=='0') {
			echo "<b>$nname</b>";
			echo "<input type=hidden name=note_name value='$nname'>";
		} else {
			echo "<input size=30 maxlength=20 type=text name=note_name value='$nname'> (max. 20 znaków)";
		}
	_td();
_tr();
tr_();
	td("100;rt;Treść notatki");
	td_(";;");
		if ($_GET[edit]=='0') {
			echo "<b>".nl2br($ntresc)."</b><br />";
			echo "<input type=hidden name=note_tresc value='$ntresc'>";
		} else {
			echo "<textarea class=wymagane name=note_tresc id=note_tresc cols=55 rows=8>$ntresc</textarea>";
		}
	_td();
_tr();
if ($_GET[edit]=='0') {
tr_();
	td("100;rt;Dodaj swoją treść");
	td_(";;");
		echo "<textarea class=wymagane name=note_tresc_add id=note_tresc_add cols=55 rows=3></textarea>";
	_td();
_tr();
}

if ($_GET[edit]!='0') {
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
				if ($temp_id==$nuserid) echo " SELECTED ";
				echo ">$temp_imie $temp_nazwisko</option>\n"; 
			}
			echo "</select>\n";
	_td();
_tr();
} else {
	tr_();
		td("100;r;Adresat notatki");
		td_(";;");
			echo "<input type=hidden name=note_osoba value=$nuserid>";
			echo "<b>".urldecode($to_person)."</b>";
		_td();
	_tr();
}

tr_();
	td("100;r;Data przypomnienia");
	td_(";;");
		if ($_GET[edit]=='0') {
			if ($nalertdate=='0000-00-00') $nalertdate='-';
			echo "<input type=hidden value='$nalertdate' name=note_alertdate>";
			echo "<b>$nalertdate</b>";
		} else {
			if ($nalertdate=='0000-00-00') $nalertdate='-';
			echo "<input size=10 maxlength=10 type=text value='$nalertdate' id=note_alertdate name=note_alertdate onkeypress='return handleEnter(this, event);'>";
			echo "<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif width=16 height=16 border=0></a>";
			if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('note_alertdate').value='".Date('Y-m-d')."'; return false;\">";
		} 
	_td();
_tr();

echo "<input type=hidden name=edit value=$_GET[edit]>";
echo "<input type=hidden name=note_id value=$nid>";
echo "<input type=hidden name=today value=$today>";
tbl_empty_row();	
endtable();
startbuttonsarea("right");
addbuttons("zapisz","anuluj");
endbuttonsarea();
_form();
?>
<script language="JavaScript">
var cal1 = new calendar1(document.forms['ed'].elements['note_alertdate']);
	cal1.year_scroll = true;
	cal1.time_comp = false;
</script>

<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("ed");
  frmvalidator.addValidation("note_tresc","req","Nie podano treści notatki");
</script>
<?php

}

?>
</body>
</html>