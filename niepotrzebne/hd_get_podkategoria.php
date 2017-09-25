<?php
//require_once "cfg_eserwis.php";
include_once('header.php');
require_once "cfg_helpdesk.php"; 


	
if($_POST["katid"]!=''){

$sql = "SELECT hd_podkategoria_nr,hd_podkategoria_opis FROM $dbname_hd.hd_podkategoria WHERE ((hd_podkategoria_wlaczona=1) and (hd_kategoria_ids LIKE '%$_POST[katid]%'))";
$rsd = mysql_query($sql,$conn_hd);
$ile = mysql_num_rows($rsd);

	echo "<select class=wymagane name=podkat_id id=podkat_id onBlur=\"document.getElementById('podkat_id_value').value=this.value; go_status(); \"	onKeyPress=\"SZPDP(event.keyCode);\" onChange=\"go_priorytet(); document.getElementById('podkat_id_value').value=document.getElementById('podkat_id').value; \" />";
	$i=0;
	//echo "<option value=''></option>";
	while($rs = mysql_fetch_assoc($rsd)){
		echo "<option value='$rs[hd_podkategoria_nr]'";
		//if (($_REQUEST[p10]!='') && ($_REQUEST[p10]==$rs[hd_podkategoria_nr]) echo "SELECTED";
		echo ">$rs[hd_podkategoria_opis]</option>";
		$i++;
	}
	echo "</select>";
} else {
			echo "<select class=wymagane id=podkat_id name=podkat_id />";
			echo "<option value=''>-</option>";
			echo "</select>\n";
}

?>