<?php include_once('header.php'); ?>
<body>
<?php
$cmd = shell_exec("ping -n 1 -w 1500 $ip");
$ping_results = explode(",",$cmd);
$ping_results2 = explode(":",$cmd);
if (eregi("Odebrane = 0", $ping_results[1], $out) or eregi("H",$ping_results2[2][1],$out)) {
	errorheader("Komputer nie odpowiada");
	startbuttonsarea("right");
	addbuttons("zamknij");
	endbuttonsarea();
}

if (eregi("Odebrane = 1", $ping_results[1], $out)) {
	if (eregi("H",$ping_results2[2][1],$out)==FALSE) {
	?>
	  	<meta http-equiv="REFRESH" content="0;url=http://<?php echo "$ip"; ?>:9495">
	<?php
	}				  
}
?>
</body>
</html>