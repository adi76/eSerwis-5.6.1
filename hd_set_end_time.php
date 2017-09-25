<?php
include_once('header.php');
require_once "cfg_helpdesk.php"; 

if (isset($_POST["sdate"])) {
	$ReturnValue = '';
	
	$basedate = $_POST[sdate];
	$_wt = $_POST[wt];
	
	if ($_POST["k"]==2) {		
		$ReturnValue = substr(godzina_stop($basedate,$_wt),11,5);
	}
	
	if ($_POST["k"]==6)	{
		$ReturnValue = substr(godzina_stop($basedate,$_wt),11,5);
	} 
		
	echo ">>>>>$ReturnValue<<<<<";

} else {
	echo ">>>>><<<<<";
}
return false;
?>
