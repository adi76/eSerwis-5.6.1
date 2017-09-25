<?php 
	require 'cfg_eserwis.php';
	session_start();
	$inactive = 30;
	if (isset($_SESSION['timeout'])) {
		$session_life = time() - $_SESSION['start'];
		if ($session_life>$inactive) {
			header("Location: ".$linkdostrony."");
		}
		$_SESSION['timeout'] = time();
	}

	if (!session_is_registered('es_autoryzacja') || (!defined('eSerwis_OK'))) { header("Location: ".$linkdostrony.""); }
	if ($currentuser!=$adminname) {	if ($es_block==1) {	header("Location: ".$linkdostrony."");}	}
?>
