<?php 
if ($wartiant3!=2) {
	tr_();
		echo "<td></td>";
		echo "<td class=right>";
		$ddddtt = Date('Y-m-d H:i');
		echo "<font color=".$datasystemowa_kolor." style='background-color:".$datasystemowa_tlo."'>";
		echo "&nbsp;Data systemowa: <b>".substr($ddddtt,0,16)."&nbsp;</b>";
		echo "</font>";		
		echo "<input type=hidden name=hdds value='".substr($ddddtt,0,16)."'>";
		echo "</td>";
	_tr();
} else {
		$ddddtt = Date('Y-m-d H:i');
		echo "<font color=".$datasystemowa_kolor." style='background-color:".$datasystemowa_tlo."'>";
		echo "&nbsp;Data systemowa: <b>".substr($ddddtt,0,16)."&nbsp;</b>";
		echo "</font>";		
		echo "<input type=hidden name=hdds value='".substr($ddddtt,0,16)."'>";
}
?>