<?php
include_once('header.php');

if (isset($_POST["sdate"])) {
	$ReturnValue = '';
	
	$basedate = $_POST[sdate];
	
	if ($_POST[k]==2) {
		$ReturnValue .= "#";
		for ($cd=2; $cd<=14; $cd++) {
			$ReturnValue .= AddWorkingDays($cd, $basedate)."#";
		}
	}
	
	if ($_POST[k]==6)	{
		$ReturnValue .= "#";
		$ReturnValue .= $basedate."#";
		$ReturnValue .= AddWorkingDays(1, $basedate)."#";
		
	} 
	
	$ReturnValue = substr($ReturnValue, 0, -1);
	
	echo ">>>>>$ReturnValue<<<<<";

} else {
	echo ">>>>><<<<<";
}
return false;

?>
