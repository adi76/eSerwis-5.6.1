<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body>
<?php 	

if ($_REQUEST[zglnr]!='') {	

	errorheader("Nie zamykaj tego okna");
	echo "<div id=TrwaLadowanie style=\"color:grey; font-weight:bold; text-align:center; font-size:13px; border: 1px solid #FC9898; background-color:white;padding:10px;\">";
	
	echo "Trwa ponowne zapisywanie zgłoszenia do bazy...<input type=image class=border0 src=img/loader.gif>";
	echo "</div>";
	ob_flush();
	flush();
 
	$sqlX = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_widoczne = 0 WHERE zgl_nr = $_REQUEST[zglnr]"; 
	$wynikX = mysql_query($sqlX, $conn_hd);		
	
	$_SESSION[zgloszenie_szcz_dodano]='';
	$_SESSION[zgloszenie_dodano]='';
	
}

?>
<script>
document.getElementById('TrwaLadowanie').style.display='none';
self.close();
</script>
<?php
if ($_REQUEST[refresh_parent]==1) {
?>
<script>
if (opener) opener.location.reload(true);
</script>
<?php } ?>
</body>
</html>