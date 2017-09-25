<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body>
<?php 	
if ($_REQUEST[nr]!=0) {	
	
	errorheader("Nie zamykaj tego okna");

	echo "<div id=TrwaLadowanie style=\"color:white; font-weight:normal; text-align:center; font-size:13px; border: 1px solid silver; background-color:black;padding:10px;\">";
	echo "Trwa aktualizowanie danych o zgłoszeniu...<input type=image class=border0 src=img/loader7.gif>";
	echo "</div>";
	ob_flush();
	flush();
	
	// zmienne wejściowe
	$___nr 			= $_REQUEST[nr];
	
	include('algorytm_wyliczania_czasu.php');

} else {

	errorheader("Nie zamykaj tego okna");
	echo "<div id=TrwaLadowanie style=\"color:grey; font-weight:bold; text-align:center; font-size:13px; border: 1px solid #FC9898; background-color:white;padding:10px;\">";
	echo "Trwa aktualizowanie danych o obsługiwanych zgłoszeniach...<input type=image class=border0 src=img/loader.gif>";
	echo "</div>";
	ob_flush();
	flush();
	
	$ile_zgloszen = $_REQUEST[nrs_cnt];
	$zgloszenie = explode(",",$_REQUEST[nrs]);
	
	for ($ii=0; $ii<=$ile_zgloszen-1; $ii++) {
		$jedno_zgloszenie = $zgloszenie[$ii];

		$___nr 			= $jedno_zgloszenie;
		
		include('algorytm_wyliczania_czasu.php');
	}
}

?>
<script>
document.getElementById('TrwaLadowanie').style.display='none';
<?php if (!$debug) { ?>self.close();<?php } ?>
</script>
</body>
</html>