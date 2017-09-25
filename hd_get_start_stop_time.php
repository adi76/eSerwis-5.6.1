<?php
include_once('header.php');
require_once "cfg_helpdesk.php"; 

if (isset($_POST["sdate"])) {
	$ReturnValue = '';
	
	$basedate = $_POST[sdate];
	$_wt = $_POST[wt];
	
	if ($_POST["k"]==6)	{
		$ReturnValue1 = substr(godzina_start($basedate,$_wt),11,5);
		$ReturnValue2 = substr(godzina_stop($basedate,$_wt),11,5);
		$ReturnValue3 = jaki_dzien_upper($basedate);
	} 
		
	echo "$ReturnValue1#$ReturnValue2#$ReturnValue3";

} else {
	echo "";
}
return false;
?>
