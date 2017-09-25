<?php 
include_once('header.php');
include_once('cfg_eserwis.php');
include_once('cfg_helpdesk.php');

echo "<body>";
$para = explode(',',$_GET['neworder']);

for ($t=0; $t<$_GET[cnt]; $t++) {
	
	$order = explode('@',$para[$t]);	
	//UPDATE $dbname_hd.hd_zgloszenie SET zgl_poledodatkowe3=1 WHERE zgl_nr =14 LIMIT 1 
	$sql4 = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_poledodatkowe3='".$order[1]."' WHERE (zgl_nr=".$order[0].") and (belongs_to='$es_filia') LIMIT 1";
	//echo $sql4."<br />";
	$result4 = mysql_query($sql4, $conn_hd) or die($k_b);
}

?><script>if (opener) opener.location.reload(); self.close();  </script><?php
?>
</body>
</html>