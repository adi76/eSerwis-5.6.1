<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body>
<?php include('body_start.php');

if ($submit) {


} else {

	pageheader("Przeglądanie pojazdów pracownika",1,0);
	startbuttonsarea("right");
	addbuttons("zamknij");
	endbuttonsarea();	 
}

?>
</body>
</html>