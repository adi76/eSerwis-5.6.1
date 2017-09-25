<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body OnLoad="document.forms[0].elements[1].focus();">
<?php 
if ($submit99) { 

	$_POST=sanitize($_POST);
	$sql_d1="UPDATE $dbname.serwis_zadania_pozycje SET pozycja_data_modyfikacji='', pozycja_modyfikowane_przez ='', pozycja_status=0 WHERE (pozycja_id = '$_POST[id]') LIMIT 1";

	if (mysql_query($sql_d1, $conn)) { 
		?>
			<script>
				if (opener) opener.location.reload(true); self.close(); 
			</script>		
		<?php
	} else {
		?><script>info('Wystąpił błąd podczas operacji usuwania'); self.close(); </script><?php
	}
	
} else { ?>
<?php
errorheader("Czy potwierdzasz anulowanie wykonania zadania w");
infoheader("<b>".urldecode($komorka)." ?</b>");
echo "<form name=ez action=$PHP_SELF method=POST>";
startbuttonsarea("center");
echo "<br />";
echo "<input class=buttons type=submit name=submit99 value=TAK>";
addbuttons("nie");
endbuttonsarea();	
echo "<input type=hidden name=olduwagi value='$muwagi'>";
echo "<input type=hidden name=id value=$_REQUEST[id]>";
echo "<input type=hidden name=zid value=$_REQUEST[zid]>";

echo "<input type=hidden name=komorka value='$_REQUEST[komorka]'>";
echo "<input type=hidden name=zadanie value='$_REQUEST[zadanie]'>";

echo "<input type=hidden name=podkat_nr value='$_REQUEST[zpodkatnr]'>";
echo "<input type=hidden name=podkat_opis value='$_REQUEST[zpodkatopis]'>";

_form();

} 
?>
</body>
</html>