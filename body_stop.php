<?php 
//if ($tooltips==1) echo "</div>"; 
if ($splt==1) { 
	$pagetime = $timer->stop();
	echo "<div class=hideme>";
	startbuttonsarea();
	echo "<sub style='color:#A8A8A8'>Strona została wygenerowana w ".round_to_penny($pagetime)." sekund</sub>&nbsp;&nbsp;<br />";
	echo "<hr style='color:#D7D7D7; border-style:dotted; height:1px; border-bottom:none;border-right:none;border-left:none; background-color:transparent; margin:0px; padding:0px;'>";
	endbuttonsarea();
	echo "</div>";
}
?>