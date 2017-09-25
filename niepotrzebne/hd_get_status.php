<?php
//require_once "cfg_eserwis.php";
include_once('header.php');
require_once "cfg_helpdesk.php"; 

if(isset($_POST["wybierzid"])){

$kat1 = $_POST[wybierzid][0];
$podk1 = $_POST[wybierzid][1];
$prior = $_POST[wybierzid][2];
$stage2 = $_POST[wybierzid][3];

$sql = "SELECT hd_status_nr,hd_status_opis FROM $dbname_hd.hd_status WHERE ((hd_status_wlaczona=1) and (hd_kategoria_ids LIKE '%$kat1%') and (hd_podkategoria_ids LIKE '%$podk1%') and (hd_priorytet_ids LIKE '%$prior%') and (hd_status_enabled_on_stage='$stage2'))";

$rsd = mysql_query($sql,$conn_hd);
$ile = mysql_num_rows($rsd);

echo "<select class=wymagane name=status_id id=status_id onChange=\"add_hidden_status('lista_priorytetow_from_ajax',this.options[this.selectedIndex].text,this.value); StatusChanged(document.getElementById('kat_id').value,this.value,'Status');\" onBlur=\"document.getElementById('priorytet_name').value=document.getElementById('priorytet_id').options[document.getElementById('priorytet_id').selectedIndex].text;document.getElementById('priorytet_id_value').value=document.getElementById('priorytet_id').value;document.getElementById('podkat_name').value=document.getElementById('podkat_id').options[document.getElementById('podkat_id').selectedIndex].text; document.getElementById('podkat_id_value').value=document.getElementById('podkat_id').value; \" onKeyPress=\"SZPDS(event.keyCode);\" />";

$i=0;
while($rs = mysql_fetch_assoc($rsd)){
	echo "<option value='$rs[hd_status_nr]'>$rs[hd_status_opis]</option>";
	$i++;
	
}
echo "</select>";

}

?>