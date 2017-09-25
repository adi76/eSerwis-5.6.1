<?php
include('cfg_db_helpdesk.php');

function ConnectToDatabase_HelpDesk() {
	global $link_hd, $dbtype_hd, $dbhost_hd, $dbusername_hd, $dbpassword_hd, $dbname_hd;
	$link_hd = mysql_connect( "$dbhost_hd", "$dbusername_hd" , "$dbpassword_hd" ) or die("<h2 style='font-size:13px;font-weight:bold;padding-top:10px;padding-bottom:10px;margin-top:0px;margin-bottom:5px;text-align:center;background: #FF9999;color:#313131;display: block; border: 1px solid #FF6C6C;'>Wystąpił błąd podczas łączenia się z bazą danych</h2><div align=right><input type=button class=buttons value='Spróbuj ponownie' onclick=\"window.location.reload(true);\"></div>");
	mysql_select_db("$dbname_hd", $link_hd) or die($k_b);
	return $link_hd;
}
$conn_hd = ConnectToDatabase_HelpDesk();
?>