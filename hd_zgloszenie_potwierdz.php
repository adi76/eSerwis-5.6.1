<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body>
<?php 
if ($_REQUEST[potwierdz]==1) {
	$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_sprawdzone_data='$_REQUEST[sprdata]', zgl_sprawdzone_osoba='$_REQUEST[spruser]' WHERE (zgl_id='$_REQUEST[id]') LIMIT 1";
} else {
	$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_sprawdzone_data='0000-00-00 00:00:00', zgl_sprawdzone_osoba='' WHERE (zgl_id='$_REQUEST[id]') LIMIT 1";
}

if ($_REQUEST[frommenu]!=1) {
	session_start();
	$_SESSION['refresh_o_zgloszenia_'.$_REQUEST[nr].'']=1;	
}

if (mysql_query($sql, $conn_hd)) { 

	$dddd = Date('Y-m-d H:i:s');
	if ($_REQUEST[potwierdz]==1) {
		$lista_zmian='<u>Potwierdzenie sprawdzenia zgłoszenia przez:</u> <b>'.$currentuser.', '.substr($dddd,0,16).'</b><br />';
	} else {
		$lista_zmian='<u>Anulowanie potwierdzenia sprawdzenia zgłoszenia przez:</u> <b>'.$currentuser.', '.substr($dddd,0,16).'</b><br />';
	}
	$sql_insert = "INSERT INTO $dbname_hd.hd_zgloszenie_historia_zmian values ('', '$_REQUEST[id]','$dddd','$currentuser','$lista_zmian',1,'',$es_filia)";
	$wynik = mysql_query($sql_insert, $conn_hd);
	
//echo "<input type=button value='eee' onClick=\"opener.document.getElementById('save$_REQUEST[nr]').style.display='';\" />";	
	?><script>
	<?php if ($_REQUEST[donotreloadopener]!=1) { ?>
	if (opener) opener.location.reload(true); 
	<?php } else { ?>
		//alert('save<?php echo $_REQUEST[nr]; ?>');
		
		if (opener) opener.document.getElementById('save<?php echo $_REQUEST[nr]; ?>').style.display='';
		
	<?php } ?>	
	
	self.close(); </script><?php
} else { 
	?><script>info('Wystąpił błąd podczas potwierdzania zgłoszenia'); self.close(); </script><?php
}
	
?>
</body>
</html>