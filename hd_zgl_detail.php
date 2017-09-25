<?php
//require_once "cfg_eserwis.php";
include_once('header.php');
require_once "cfg_helpdesk.php"; 

if(isset($_GET["id"])) {

	
	
	$sql = "SELECT zgl_tresc FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id=$_GET[id]) LIMIT 1";	
	$rsd = mysql_query($sql,$conn_hd);
	$ile = mysql_num_rows($rsd);
	if ($ile!=0) {
		$dane = mysql_fetch_array($rsd);
		echo "<br /><b>Treść:</b><br />".$dane[zgl_tresc];
		?>
		<script>
			$('#sm<?php echo $_GET[id];?>').hide();
			$('#hm<?php echo $_GET[id];?>').show();
		</script>
		<?php
	} else {
		//echo "<input type=text id=hdoztelefon name=hdoztelefon size=15 maxlength=15 onKeyPress=\"return filterInput(1, event, false,' '); \" />";
	}

}


?>