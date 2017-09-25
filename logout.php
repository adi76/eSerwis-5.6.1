<?php include_once('header.php');

session_start();
// wyczyszczenie danych z tabeli tymczasowej do raportów
$sql_f = "SELECT filia_leader FROM $dbname.serwis_filie WHERE filia_leader=$es_nr LIMIT 1";
$result_f = mysql_query($sql_f, $conn) or die($k_b);
$countf = mysql_num_rows($result_f);
if ($countf>0) {
	$sql_report = "TRUNCATE TABLE serwis_temp_raport$es_filia";
	$result_report = mysql_query($sql_report, $conn);
	
	$sql_report = "TRUNCATE TABLE serwis_temp_m1$es_filia";
	$result_report = mysql_query($sql_report, $conn);	

	$sql_report = "TRUNCATE TABLE serwis_temp_m2$es_filia";
	$result_report = mysql_query($sql_report, $conn);		
}
$sql="UPDATE $dbname.serwis_uzytkownicy SET user_logged=0 WHERE user_id = '$es_nr' LIMIT 1";
$wynik=mysql_query($sql, $conn) or die($k_b);

if ($_SESSION['es_rps']!='') {
	?>
	<script>
		function createCookie(name,value,days) {
			if (days) {
				var date = new Date();
				date.setTime(date.getTime()+(days*24*60*60*1000));
				var expires = "; expires="+date.toGMTString();
			}
			else var expires = "";
		document.cookie = name+"="+value+expires+"; path=/";
		}
		createCookie('es_rps_<?php echo $es_nr; ?>',<?php echo $_SESSION['es_rps']; ?>,7);
	</script>
	<?php
}
//include("index.php");
session_unset();
session_destroy();

?>
<script>
function eraseCookie(name) { createCookie(name,"",-1); }
eraseCookie("show_zadania");
</script>
<meta http-equiv="REFRESH" content="0;url=<?php echo "$linkdostrony";?>login.php">