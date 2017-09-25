<?php
$get_user = mysql_fetch_array(mysql_query("SELECT user_first_name, user_last_name FROM $dbname.serwis_uzytkownicy WHERE user_id=2 LIMIT 1", $conn));
$adminname = $get_user['user_first_name'].' '.$get_user['user_last_name'];
?>