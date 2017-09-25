<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body>
<?php 

echo "$submit";

if ($submit) {

$_GET=sanitize($_GET);
// $note_name1 = $_GET[note_name];
//if ($_POST[note_name]=='') $note_name1 = substr($_GET[note_tresc],0,20);
//if ($_POST[note_alertdate]=='') $note_alert1 = '0000-00-00';

	$sql_a = "UPDATE $dbname_hd.hd_notes SET note_tresc = '$_GET[note_tresc]' WHERE note_id=$_GET[noteid] LIMIT 1";
	echo $sql_a;

	if (mysql_query($sql_a, $conn_hd)) { 
		?>
		<script>
		alert('1');
		self.location.reload(true);
		</script><?php
	} else {
	  ?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
	}

} else {

echo "<form name=add action=$PHP_SELF method=GET>";

$sql22 = "SELECT * FROM $dbname_hd.hd_notes WHERE (note_id='$_GET[noteid]') and (belongs_to=$es_filia)";
$result22 = mysql_query($sql22, $conn_hd) or die($k_b);

if (mysql_num_rows($result22)!=0) {
	
	$dane22 = mysql_fetch_array($result22);
	
	$nid 		= $dane22['note_id'];
	$nname	 	= $dane22['note_name'];
	$ntresc		= $dane22['note_tresc'];
	$nuserid	= $dane22['note_user_id'];
	$nalertdate	= $dane22['note_alertdate'];

	pageheader("Notatka $nname");
	
	echo "<span id='view'>";
	echo $ntresc;
	echo "</span>";
	
	echo "<span id=edit style='display:none'>";
	echo "&nbsp;<textarea cols=70 rows=15 name=note_tresc class=wymagane style='border:0px solid ;'>$ntresc</textarea>";
	echo "</span>";
	
} else errorheader("Brak danych notatce");

startbuttonsarea("right");
echo "<span style='float:left'>";

echo "<input id=popraw type=button class=buttons value='Popraw notatkę' onClick=\"document.getElementById('edit').style.display='';document.getElementById('view').style.display='none';document.getElementById('edit').focus();document.getElementById('popraw').style.display='none';document.getElementById('update').style.display='';\">";
echo "<input class=buttons id=update style='display:none' type=submit value='Zapisz zmiany'>";
echo "<input type=hidden name=noteid value=$_GET[noteid]>";
echo "</span>";
echo "</form>";
addbuttons("zamknij");
endbuttonsarea();

}
?>
</body>
</html>