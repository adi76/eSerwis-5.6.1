<?php
require_once "cfg_eserwis.php";
$q=$_GET["q"];
if (!$q) return;

if ($_GET[s]=='Czytnik') {
	$sql = "SELECT czytnik_nazwa FROM $dbname.serwis_slownik_czytnik WHERE czytnik_nazwa LIKE '%$q%' ORDER BY czytnik_nazwa";
} elseif ($_GET[s]=='Drukarka') {
	$sql = "SELECT drukarka_nazwa FROM $dbname.serwis_slownik_drukarka WHERE drukarka_nazwa LIKE '%$q%' ORDER BY drukarka_nazwa";
	} elseif ($_GET[s]=='Monitor') {
		$sql = "SELECT monitor_nazwa FROM $dbname.serwis_slownik_monitor WHERE monitor_nazwa LIKE '%$q%' ORDER BY monitor_nazwa";
		} elseif ($_GET[s]=='Komputer') {
			$sql = "SELECT konfiguracja_opis FROM $dbname.serwis_slownik_konfiguracja WHERE konfiguracja_opis LIKE '%$q%' ORDER BY konfiguracja_opis";
			} elseif ($_GET[s]=='Serwer') {
				$sql = "SELECT konfiguracja_opis FROM $dbname.serwis_slownik_konfiguracja WHERE konfiguracja_opis LIKE '%$q%' ORDER BY konfiguracja_opis";
				} elseif ($_GET[s]=='Notebook') {
					$sql = "SELECT konfiguracja_opis FROM $dbname.serwis_slownik_konfiguracja WHERE konfiguracja_opis LIKE '%$q%' ORDER BY konfiguracja_opis";
					} else return;

$rsd = mysql_query($sql,$conn);

while($rs = mysql_fetch_array($rsd)) {
	if ($_GET[s]=='Czytnik') {
		$cnazwa = $rs['czytnik_nazwa'];
	} elseif ($_GET[s]=='Drukarka') {
			$cnazwa = $rs['drukarka_nazwa'];
		} elseif ($_GET[s]=='Monitor') {
			$cnazwa = $rs['monitor_nazwa'];
			} elseif ($_GET[s]=='Komputer') {
				$cnazwa = $rs['konfiguracja_opis'];
				} elseif ($_GET[s]=='Serwer') {
					$cnazwa = $rs['konfiguracja_opis'];
					} elseif ($_GET[s]=='Notebook') {
						$cnazwa = $rs['konfiguracja_opis'];
						}
	
	echo "$cnazwa\n";
}
return false;
?>