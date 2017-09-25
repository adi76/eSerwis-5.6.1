<?php 
include_once('header.php'); 
include_once('cfg_eserwis.php');
include_once('cfg_helpdesk.php');
?>
<body>
<?php include('body_start.php');

$sql_create = "UPDATE $dbname_hd.hd_statystyka SET statystyka_wartosc=0 WHERE statystyka_osoba='$currentuser'";
$result_create = mysql_query($sql_create, $conn_hd) or die($k_b);
		
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
								
								list($current_value)=mysql_fetch_array(mysql_query("SELECT count(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_status='$temp_status') and (zgl_priorytet='$temp_priorytet') and (zgl_osoba_przypisana='$currentuser') and (zgl_kategoria='$temp_kategoria') and (zgl_widoczne=1)", $conn_hd));
						
								// utworz nowÄ… pozycjÄ™ w tabeli hd_statystyka dla nowego pracownika
								$sql_update = "UPDATE $dbname_hd.hd_statystyka SET statystyka_wartosc=$current_value WHERE (statystyka_filia=$es_filia) and (statystyka_osoba='$currentuser') and (statystyka_status='$temp_status') and (statystyka_priorytet='$temp_priorytet') and (statystyka_kategoria='$temp_kategoria')";
								//if ($current_value!=0) echo $sql_create."<br />";
								$result_create = mysql_query($sql_update, $conn_hd) or die($k_b);
							}
					}
			}

/*			
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
*/

/*
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
		
*/	

?>
<script>
Refresh_Moje();
</script>
</body>
</html>