<?php 
$folder = dirname($PHP_SELF);
//$linkdostrony = "http://10.6.0.192".$folder."/";
$linkdostrony = "http";
if ($ssl==true) { $linkdostrony .= "s"; }
$linkdostrony .= "://127.0.0.1".$folder."/";

//$linkdostrony = "http://10.216.39.150".$folder."/";

$remoteIP = $_SERVER['REMOTE_ADDR']; 
$ips = explode('.', $remoteIP);
$adresw=0;
$folder = dirname($PHP_SELF);

?>