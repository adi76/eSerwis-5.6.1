<?php
//require_once "cfg_eserwis.php";
include_once('header.php');
require_once "cfg_helpdesk.php"; 


	
if($_POST["wybierzid"]!=''){

$kat1 = $_POST[wybierzid][0];
$podk1 = $_POST[wybierzid][1];

$sql = "SELECT hd_priorytet_nr,hd_priorytet_opis,hd_priorytet_default FROM $dbname_hd.hd_priorytet WHERE ((hd_priorytet_wlaczona=1) and (hd_kategoria_ids LIKE '%$kat1%') and (hd_podkategoria_ids LIKE '%$podk1%'))";
$rsd = mysql_query($sql,$conn_hd);
$ile = mysql_num_rows($rsd);

echo "<select class=wymagane name=priorytet_id id=priorytet_id  onChange=\"add_hidden_priorytet('lista_priorytetow_from_ajax',this.options[this.selectedIndex].text,this.value); \" onKeyPress=\"SZPDS(event.keyCode);\" />";

$i=0;
while($rs = mysql_fetch_assoc($rsd)){
	echo "<option value='$rs[hd_priorytet_nr]'";
	if ($rs[hd_priorytet_default]==1) {
		echo " SELECTED ";
		$t1 = $rs[hd_priorytet_id];
		$t2 = $rs[hd_priorytet_opis];
	}
	echo ">$rs[hd_priorytet_opis]</option>";
	$i++;
	
}
echo "</select>";

} else {
			echo "<select class=wymagane id=priorytet_id name=priorytet_id />\n"; 		
			echo "<option value=''>-</option>";			
			echo "</select>\n";
}

?>