<?php
include_once('header.php'); 
include_once('cfg_helpdesk.php');

?><script>ShowWaitingMessage('Trwa przekierowanie do listy otwartych awarii...');</script><?php ob_flush(); flush();

$width = "<script>document.write((screen.width-300)/2); </script>"; 
$height = "<script>document.write((screen.height-20)/2); </script>"; 

ob_flush();	
flush();

?><script>self.location.href='z_awarie.php?nr=<?php echo $_REQUEST[nr]; ?>&up=<?php echo urlencode($_REQUEST[up]); ?>';</script><?php 

?>