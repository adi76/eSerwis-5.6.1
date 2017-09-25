<?php
include_once('header.php'); 
include_once('cfg_helpdesk.php');

?><script>ShowWaitingMessage('Trwa przekierowanie do modułu NAPRAWY');</script><?php ob_flush(); flush();

$width = "<script>document.write((screen.width-300)/2); </script>"; 
$height = "<script>document.write((screen.height-20)/2); </script>"; 

ob_flush();	
flush();

?><script>self.location.href='z_magazyn_zwroty.php?kolor=0&id=<?php echo $_REQUEST[id]; ?>';</script><?php 

?>