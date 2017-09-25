<?php 
include_once('header.php');
include_once('cfg_eserwis.php');
include_once('cfg_helpdesk.php');
?>
<?php

if ($submit) {

	echo "Data wprowadzona : <b>$_REQUEST[hddz]</b><br />";
	echo "Godzina wprowadzona : <b>$_REQUEST[hdgz]</b><br />";
	echo "<br />";
	echo "Ilość godzin wg klasy : <b>$_REQUEST[klasa_up_id]</b><br />";
		
$r1 = mysql_query("SELECT hd_kategoria_opis FROM helpdesk.hd_kategoria WHERE (hd_kategoria_id='$_POST[kat_id]') LIMIT 1", $conn_hd) or die($k_b);list($kat_opis)=mysql_fetch_array($r1);

$r2 = mysql_query("SELECT hd_podkategoria_opis FROM helpdesk.hd_podkategoria WHERE (hd_podkategoria_id='$_POST[podkat_id_value]') LIMIT 1", $conn_hd) or die($k_b);
list($podkat_opis)=mysql_fetch_array($r2);

$r3 = mysql_query("SELECT hd_priorytet_opis FROM helpdesk.hd_priorytet WHERE (hd_priorytet_id='$_POST[priorytet_id_value]') LIMIT 1", $conn_hd) or die($k_b);
list($priorytet_opis)=mysql_fetch_array($r3);

	echo "Kategoria : <b>$kat_opis</b><br />";
	echo "Podkategoria : <b>$podkat_opis</b><br />";

	echo "Priorytet : <b>$priorytet_opis</b><br />";

	$DataGodzinaWpisu = $_REQUEST[hddz]." ".$_REQUEST[hdgz];
	$DataWpisu = $_REQUEST[hddz];
	
	switch ($_REQUEST[kat_id]) {
		case "2" : 	if ($_REQUEST[priorytet_id_value]=='4') {
							$DataRozpoczecia = AddHoursToDate($_REQUEST[klasa_up_id],$DataGodzinaWpisu).":00";
							// rozbić na kategorie UP jeszcze
							
							$DataZakonczenia = AddHoursToDate("8",$DataGodzinaWpisu).":00";
					} 
				   
					if ($_REQUEST[priorytet_id_value]=='2') {
						if (($_REQUEST[podkat_id_value]=='2') || ($_REQUEST[podkat_id_value]=='5')) {
							$DataRozpoczecia = AddWorkingDays("1",$DataWpisu)." 07:00:00";
							$DataZakonczenia = AddWorkingDays("5","".$DataWpisu."")." ".$_REQUEST[hdgz].":00";
						}
						
						if (($_REQUEST[podkat_id_value]=='3') || ($_REQUEST[podkat_id_value]=='4')) {
							$DataRozpoczecia = AddWorkingDays("1",$DataWpisu)." 07:00:00";
							$DataZakonczenia = AddWorkingDays("14","".$DataWpisu."")." ".$_REQUEST[hdgz].":00";
						}
					}
					break;
		default : $DataRozpoczecia=''; $DataZakonczenia='';
	}
	
	echo "<br />";
	echo "Data rozpoczęcia : <b>$DataRozpoczecia</b>";
	echo "<br />";
	echo "Data zakończenia : <b>$DataZakonczenia</b>";
	echo "<br />";
	
} else {
echo "<form action=$PHP_SELF method=POST>";
echo "<input type=text name=hddz value='".date("Y-m-d")."'><br />";
echo "<input type=text name=hdgz value='".date("H:i")."'><br />";

echo "<select name=podkat_id_value>";
echo "<option value=2>oprogramowanie PP</option>";
echo "<option value=3>stacja robocza</option>";
echo "<option value=4>serwer</option>";
echo "<option value=5>opr. administracja</option>";
echo "<option value=9>peryferiam</option>";
echo "</select>";

echo "<select name=priorytet_id_value>";
echo "<option value=2>standard</option>";
echo "<option value=4>krytyczna</option>";
echo "</select>";

echo "<select name=klasa_up_id>";
echo "<option value=2>I i II</option>";
echo "<option value=3>III i inne</option>";
echo "</select>";

echo "<input type=hidden name=kat_id value=2>";
echo "<input type=submit name=submit value='ok'>";
echo "</form>";
}

?>