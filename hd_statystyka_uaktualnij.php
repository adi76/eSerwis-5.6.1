<?php 
include_once('header.php'); 
include_once('cfg_eserwis.php');
include_once('cfg_helpdesk.php');
?>
<body>
<?php include('body_start.php');

if ($_GET['newuser']!='1') {
	$sql_create = "TRUNCATE TABLE $dbname_hd.hd_statystyka";
	$result_create = mysql_query($sql_create, $conn_hd) or die($k_b);
}

$sql="SELECT user_login, user_first_name, user_last_name, belongs_to FROM $dbname.serwis_uzytkownicy WHERE (user_locked=0) ";

if ($_GET['newuser']=='1') {
	if ($_GET['loginname']!='') {
		$sql.=" and (user_login='$_GET[loginname]')";
	}
}

if ($_GET['newuser']=='1') {
	?><script>ShowWaitingMessage('Trwa aktualizacja danych na serwerze');</script><?php ob_flush(); flush();
}

$result = mysql_query($sql, $conn) or die($k_b);
$totalrows = mysql_num_rows($result);

if ($totalrows!=0) {
	$j = 1;
	while ($newArray = mysql_fetch_array($result)) {
		$temp_login				= $newArray['user_login'];
		$temp_fn				= $newArray['user_first_name'];
		$temp_ln				= $newArray['user_last_name'];
		
		$temp_current_user = $temp_fn." ".$temp_ln;
		
		// sprawdź czy nie było już wcześniej takiego użytkownika (problem ze zdublowanymi osobami np. Joanna Sokołowska) a innym loginem
		
		list($czy_jest_dublem)=mysql_fetch_array(mysql_query("SELECT COUNT(statystyka_id) FROM $dbname_hd.hd_statystyka WHERE (statystyka_osoba='".$temp_current_user."')", $conn_hd));
		
	if ($czy_jest_dublem==0) {	
		// koniec
		
		$temp_belongs_to		= $newArray['belongs_to'];
		//echo "$temp_current_user [$temp_login] [$temp_belongs_to] <br />";
		
		$sql_check="SELECT * FROM $dbname_hd.hd_statystyka WHERE statystyka_osoba='$temp_login' LIMIT 1";
		$result_check = mysql_query($sql_check, $conn_hd) or die($k_b);
		$totalrows_check = mysql_num_rows($result_check);
		
		if ($totalrows_check==0) {
		
			$sql_status="SELECT hd_status_nr FROM $dbname_hd.hd_status WHERE (hd_status_wlaczona=1) and (hd_status_nr<>'7') and (hd_status_nr<>'1')";
			$result_status = mysql_query($sql_status, $conn_hd) or die($k_b);

			while ($newArray_status = mysql_fetch_array($result_status)) {
				$temp_status = $newArray_status['hd_status_nr'];
				
				$sql_priorytet="SELECT hd_priorytet_nr FROM $dbname_hd.hd_priorytet WHERE hd_priorytet_wlaczona=1";
				$result_priorytet = mysql_query($sql_priorytet, $conn_hd) or die($k_b);

					while ($newArray_priorytet = mysql_fetch_array($result_priorytet)) {
						$temp_priorytet = $newArray_priorytet['hd_priorytet_nr'];
						
						$sql_kategoria="SELECT hd_kategoria_nr FROM $dbname_hd.hd_kategoria WHERE (hd_kategoria_wlaczona=1)";
						$result_kategoria = mysql_query($sql_kategoria, $conn_hd) or die($k_b);

							while ($newArray_kategoria = mysql_fetch_array($result_kategoria)) {
								$temp_kategoria = $newArray_kategoria['hd_kategoria_nr'];
								
								
								list($current_value)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$temp_belongs_to) and (zgl_status='$temp_status') and (zgl_priorytet='$temp_priorytet') and (zgl_osoba_przypisana='$temp_current_user') and (zgl_kategoria='$temp_kategoria') and (zgl_widoczne=1)", $conn_hd));
						
								// utworz nowÄ… pozycjÄ™ w tabeli hd_statystyka dla nowego pracownika
								$sql_create = "INSERT INTO $dbname_hd.hd_statystyka VALUES('',$temp_belongs_to,'$temp_current_user','$temp_status','$temp_priorytet','$temp_kategoria',$current_value)";
								//if ($current_value!=0) echo $sql_create."<br />";
								$result_create = mysql_query($sql_create, $conn_hd) or die($k_b);
							}
					}
			}
			
		} 
		
		} // duble
	}

	
	$sql44="SELECT filia_id FROM $dbname.serwis_filie";
	$result44 = mysql_query($sql44, $conn) or die($k_b);
	while ($newArray44 = mysql_fetch_array($result44)) {
		$temp_f	= $newArray44['filia_id'];
// uaktualnij nowe
				$temp_status = 1;
				
				$sql_priorytet="SELECT hd_priorytet_nr FROM $dbname_hd.hd_priorytet WHERE hd_priorytet_wlaczona=1";
				$result_priorytet = mysql_query($sql_priorytet, $conn_hd) or die($k_b);

					while ($newArray_priorytet = mysql_fetch_array($result_priorytet)) {
						$temp_priorytet = $newArray_priorytet['hd_priorytet_nr'];
						
						$sql_kategoria="SELECT hd_kategoria_nr FROM $dbname_hd.hd_kategoria WHERE (hd_kategoria_wlaczona=1)";
						$result_kategoria = mysql_query($sql_kategoria, $conn_hd) or die($k_b);

							while ($newArray_kategoria = mysql_fetch_array($result_kategoria)) {
								$temp_kategoria = $newArray_kategoria['hd_kategoria_nr'];
								
								list($current_value)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$temp_f) and (zgl_status='1') and (zgl_priorytet='$temp_priorytet') and (zgl_kategoria='$temp_kategoria') and (zgl_widoczne=1)", $conn_hd));
						
								// utworz nowÄ… pozycjÄ™ w tabeli hd_statystyka dla nowego pracownika
								$sql_create = "INSERT INTO $dbname_hd.hd_statystyka VALUES('',$temp_f,'','$temp_status','$temp_priorytet','$temp_kategoria',$current_value)";
								//if ($current_value!=0) echo $sql_create."<br />";
								$result_create = mysql_query($sql_create, $conn_hd) or die($k_b);
							}
					}
	}
		// koniec uaktualnienia nowych zgłoszeń

// uaktualnij rozpoczęte - nie zakończone
	$sql44="SELECT filia_id FROM $dbname.serwis_filie";
	$result44 = mysql_query($sql44, $conn) or die($k_b);
	while ($newArray44 = mysql_fetch_array($result44)) {
		$temp_f	= $newArray44['filia_id'];
				$temp_status = 7;
				
				$sql_priorytet="SELECT hd_priorytet_nr FROM $dbname_hd.hd_priorytet WHERE hd_priorytet_wlaczona=1";
				$result_priorytet = mysql_query($sql_priorytet, $conn_hd) or die($k_b);

					while ($newArray_priorytet = mysql_fetch_array($result_priorytet)) {
						$temp_priorytet = $newArray_priorytet['hd_priorytet_nr'];
						
						$sql_kategoria="SELECT hd_kategoria_nr FROM $dbname_hd.hd_kategoria WHERE (hd_kategoria_wlaczona=1)";
						$result_kategoria = mysql_query($sql_kategoria, $conn_hd) or die($k_b);

							while ($newArray_kategoria = mysql_fetch_array($result_kategoria)) {
								$temp_kategoria = $newArray_kategoria['hd_kategoria_nr'];
								
								list($current_value)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_widoczne='1') and (belongs_to=$temp_f) and (zgl_status='7') and (zgl_priorytet='$temp_priorytet') and (zgl_kategoria='$temp_kategoria') and (zgl_widoczne=1)", $conn_hd));
							
								//echo "SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (zgl_widoczne='1') and (belongs_to=$temp_f) and (zgl_status='7') and (zgl_priorytet='$temp_priorytet') and (zgl_kategoria='$temp_kategoria')<br />";
								
								// utworz nowÄ… pozycjÄ™ w tabeli hd_statystyka dla nowego pracownika
								$sql_create = "INSERT INTO $dbname_hd.hd_statystyka VALUES('',$temp_f,'','$temp_status','$temp_priorytet','$temp_kategoria',$current_value)";
								//if ($current_value!=0) echo $sql_create."<br />";
								$result_create = mysql_query($sql_create, $conn_hd) or die($k_b);
							}
					}		
		// koniec uaktualnienia rozpoczętych - nie zakończonych zgłoszeń
	}
		
	
} else errorheader("Baza użytkowników jest pusta");

startbuttonsarea("right");
addbuttons("zamknij");
endbuttonsarea();
if ($_GET['newuser']=='1') {
?><script>self.close();</script><?php
}
?>
</body>
</html>