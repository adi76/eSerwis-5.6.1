<?php 
include_once('cfg_helpdesk.php');
include_once('header.php'); 

//require_once('phpMailer/class.phpmailer.php');
//require_once('cfg_mails.php');
?>
<body onLoad="document.forms[0].elements[0].focus();" />
<?php 
if ($submit) { 
	$_POST=sanitize($_POST);	
	$sql_a = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_poczta_nr='$_POST[hadim_nr]' WHERE zgl_id=$_POST[id] LIMIT 1";
	$dddd = date("Y-m-d H:i:s");
	if (mysql_query($sql_a, $conn_hd)) { 
	
		if ($_POST[old_hadim_nr]!=$_POST[hadim_nr]) {
			$old_hadim_nr1 = $_POST[old_hadim_nr];
			if ($old_hadim_nr1=='') $old_hadim_nr1 = "<i>pusty</i>";
			$lista_zmian='<u>Zmiana numeru HADIM zgłoszenia:</u> <b>'.$old_hadim_nr1.'</b> -> <b>'.$_POST[hadim_nr].'</b><br />';
			
			$sql_insert = "INSERT INTO $dbname_hd.hd_zgloszenie_historia_zmian values ('', '$_POST[id]','$dddd','$currentuser','$lista_zmian',1,'',$es_filia)";
			$wynik = mysql_query($sql_insert, $conn_hd);
			
		}
	?>
	
	<script>
	if (opener) opener.location.reload(true);
	self.close(); 
	</script>
	<?php
		} else {
			?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
		}
	// koniec

} else {

	pageheader("Edycja numeru zgłoszenia HADIM dla zgłoszenia nr <b>$_GET[nr]</b>"); 
	$result = mysql_query("SELECT zgl_poczta_nr FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id=$_GET[id]) LIMIT 1", $conn_hd) or die($k_b);
	list($value_to_change)=mysql_fetch_array($result);

	echo "<form name=add action=$PHP_SELF method=POST onSubmit=\"return pytanie_zapisz_ehadim('Zapisać zmiany do bazy ?'); \">";

	starttable();
	tbl_empty_row();	
	$ddddtt = Date('Y-m-d H:i');
	tr_();
		td("300;r;Numer zgłoszenia w HADIM");
		td_(";;");	
			echo "<input maxlength=10 size=10 type=text id=hadim_nr name=hadim_nr value='$value_to_change' >";
		_td();
	_tr();

	echo "<input type=hidden name=id value=$_GET[id]>";
	echo "<input type=hidden name=old_hadim_nr value='$value_to_change'>";

	tbl_empty_row();
	endtable();
	startbuttonsarea("right");
	echo "<input class=buttons type=submit name=submit id=submit value=Zapisz />";
	addbuttons("anuluj");
	endbuttonsarea();
	_form();
	}
	?>
<script>
document.getElementById('hadim_nr').select();
document.getElementById('hadim_nr').focus();
</script>
</body>
</html>