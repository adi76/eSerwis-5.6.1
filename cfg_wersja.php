<?php 
include('cfg_oddzial.php');

$wnr 				= '5.6.1';
$dataaktualizacji	= '06.03.2015';
$testowa			= 0;
if ($testowa==1) $wnr.= " TESTOWA";

$wersja = " ".$wnr." | Ostatnia aktualizacja: ".$dataaktualizacji." " . $oddzial;
?>