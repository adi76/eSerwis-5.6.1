<?php
//include_once('cfg_helpdesk.php');

list($fn)=mysql_fetch_array(mysql_query("SELECT filia_nazwa FROM $dbname.serwis_filie WHERE filia_id=$bt LIMIT 1",$conn));
echo "<br /><b><sub>".$fn."</sub></b>";
?>