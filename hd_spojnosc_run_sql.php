<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body>
<?php 
if ($submit) {	
// ukryj zgloszenie
//	$sql = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_widoczne='0' WHERE (belongs_to='$es_filia') and (zgl_id='$_POST[id]') LIMIT 1";
//	$result = mysql_query($sql, $conn_hd) or die($k_b);
	
	echo urldecode($_POST[zapytanie]);
/*	if (mysql_query($sql, $conn_hd)) { 
		?><script>opener.location.reload(true); self.close(); </script><?php
	} else { 
		?><script>info('Wystąpił błąd podczas operacji ukrywania zgłoszenia'); self.close(); </script><?php
	}
	*/
} else {
errorheader("Czy napewno chcesz wykonać zapytanie/a ?");
startbuttonsarea("center");
nowalinia();
echo "<form action=$PHP_SELF method=POST>";
?>
<script>
document.write("<input type=hidden name=zapytanie value='"+urlencode(readCookie('<?php echo $_GET[zap]; ?>'))+"'>");
</script>
<?php 

addbuttons("tak","nie");
endbuttonsarea();
_form();
}
?>
</body>
</html>