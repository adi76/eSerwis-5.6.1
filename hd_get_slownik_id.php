<?php
//require_once "cfg_eserwis.php";
include_once('header.php');
require_once "cfg_helpdesk.php"; 

if (isset($_POST["wybierzid"])) {
	$q = $_POST[wybierzid];
	
	if ($_POST[s]=='Czytnik') {
		$sql = "SELECT czytnik_nazwa FROM $dbname.serwis_slownik_czytnik WHERE czytnik_nazwa LIKE '%$q%' ORDER BY czytnik_nazwa";
	} elseif ($_POST[s]=='Drukarka') {
		$sql = "SELECT drukarka_nazwa FROM $dbname.serwis_slownik_drukarka WHERE drukarka_nazwa LIKE '%$q%' ORDER BY drukarka_nazwa";
		} elseif ($_POST[s]=='Monitor') {
			$sql = "SELECT monitor_nazwa FROM $dbname.serwis_slownik_monitor WHERE monitor_nazwa LIKE '%$q%' ORDER BY monitor_nazwa";
			} elseif ($_POST[s]=='Komputer') {
				$sql = "SELECT konfiguracja_opis FROM $dbname.serwis_slownik_konfiguracja WHERE konfiguracja_opis LIKE '%$q%' ORDER BY konfiguracja_opis";
				} elseif ($_POST[s]=='Serwer') {
					$sql = "SELECT konfiguracja_opis FROM $dbname.serwis_slownik_konfiguracja WHERE konfiguracja_opis LIKE '%$q%' ORDER BY konfiguracja_opis";
					} elseif ($_POST[s]=='Notebook') {
						$sql = "SELECT konfiguracja_opis FROM $dbname.serwis_slownik_konfiguracja WHERE konfiguracja_opis LIKE '%$q%' ORDER BY konfiguracja_opis";
						} else return;

	$result2 = mysql_query($sql,$conn_hd) or die($k_b);
	$dane = mysql_fetch_array($result2);

	if ($_POST[s]=='Czytnik') {
			$ReturnValue = $dane['czytnik_nazwa'];
		} elseif ($_POST[s]=='Drukarka') {
				$ReturnValue = $dane['drukarka_nazwa'];
			} elseif ($_POST[s]=='Monitor') {
				$ReturnValue = $dane['monitor_nazwa'];
				} elseif ($_POST[s]=='Komputer') {
					$ReturnValue = $dane['konfiguracja_opis'];
					} elseif ($_POST[s]=='Serwer') {
						$ReturnValue = $dane['konfiguracja_opis'];
						} elseif ($_POST[s]=='Notebook') {
							$ReturnValue = $dane['konfiguracja_opis'];
						}

	echo ">>>>>$ReturnValue<<<<<";

} else {
	echo ">>>>><<<<<";
}
return false;
?>
