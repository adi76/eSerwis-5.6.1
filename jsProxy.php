<?php

require 'class.JavaScriptPacker.php';
if(substr($_REQUEST['js'],0,1)=="/")
  $_REQUEST['js']=substr($_REQUEST['js'],1);
$script = file_get_contents($_REQUEST['js']);

$packer = new JavaScriptPacker($script, 'Normal', true, false);
$packed = $packer->pack();

echo $packed;

?>
