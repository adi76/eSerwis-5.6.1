<?php

include_once('header_simple.php');

if ($_REQUEST[typ]=='S') {
echo "ping -n 1 -w 2500 $_REQUEST[serwer]";
	$cmd = shell_exec("ping -n 1 -w 2500 $_REQUEST[serwer]");
	$ping_results = explode(",",$cmd);
	$ping_results2 = explode(":",$cmd);
	if (eregi("Odebrane = 0", $ping_results[1], $out) or eregi("H",$ping_results2[2][1],$out)) {
		echo "<a title=' Nie dzia³a lub router jest wy³¹czony '><img class=imgoption src=img/off.gif  align=top></a>";				
	}
	if (eregi("Odebrane = 1", $ping_results[1], $out)) {
		if (eregi("H",$ping_results2[2][1],$out)==FALSE) {
			echo "<a title=' £¹cze dzia³a prawid³owo '><img class=imgoption src=img/on.gif ></a>";
		}
	}
}

if ($_REQUEST[typ]=='R') {
echo "ping -n 1 -w 2500 $_REQUEST[router]";
	if ($serwer==false) {
		$cmd = shell_exec("ping -n 1 -w 2500 $_REQUEST[router]");
		$ping_results = explode(",",$cmd);
		$ping_results2 = explode(":",$cmd);
		if (eregi("Odebrane = 0", $ping_results[1], $out) or eregi("H",$ping_results2[2][1],$out)) {
			echo "<a title=' Nie dzia³a lub router jest wy³¹czony '><img class=imgoption src=img/off.gif  align=top></a>";				
		}
		if (eregi("Odebrane = 1", $ping_results[1], $out)) {
			if (eregi("H",$ping_results2[2][1],$out)==FALSE) {
				echo "<a title=' £¹cze dzia³a prawid³owo '><img class=imgoption src=img/on.gif ></a>";
			}
		}
	}
}
