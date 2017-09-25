<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body>
<?php

	$sl_okno = $_COOKIE[$_COOKIE['current_window_name_sl_add']];
	$sl_wybor = explode('|',$_COOKIE[$sl_okno]);
	$sl_wybor_tresc = $_COOKIE[$_COOKIE['current_window_name_sl_add_tresc']];
	
	if (($sl_wybor[0]!='') || ($sl_wybor[1]!='')) {

	$sql_a111 = "INSERT INTO $dbname_hd.hd_slownik_tresci VALUES('','$sl_wybor_tresc','$currentuser','$sl_wybor[0]','$sl_wybor[1]',$es_filia)";
	
	if (mysql_query($sql_a111, $conn_hd)) {
		?><script> //opener.location.reload(true); 
		alert('Pomyœlnie dodano treœæ do s³ownika');</script><?php
			//echo "$sql_a111";
			} else {
		?><?php
		}		
	
	}

?>