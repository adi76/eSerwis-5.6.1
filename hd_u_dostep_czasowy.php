<?php 
include_once('cfg_helpdesk.php');
include_once('header.php'); 
?>
<body>
<?php 
$sql_d1="UPDATE $dbname_hd.hd_dostep_czasowy SET dc_dostep_active=0 WHERE dc_id = $_GET[id] LIMIT 1";
echo $sql_d1;

if (mysql_query($sql_d1, $conn)) { 
	?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
} else {
	?><script>info('Wystąpił błąd podczas wykonywania zapytania do bazy'); self.close(); </script><?php
}
?>
</body>
</html>