<?php
//require_once "cfg_eserwis.php";
include_once('header.php');
require_once "cfg_helpdesk.php"; 

if(isset($_POST["wybierzid"])) {
	$oz1 = $_POST[wybierzid];
	
	$rozbij = explode("#",$oz1);
	
	$oz = $rozbij[0];
	$upid = $rozbij[1];
	
/*	$sql1 = "SELECT up_id FROM $dbname.serwis_komorki,serwis_piony WHERE (CONCAT(serwis_piony.pion_nazwa,' ',serwis_komorki.up_nazwa)='$upid') and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.belongs_to=$es_filia)";
	$rsd1 = mysql_query($sql1,$conn);
	$dane1 = mysql_fetch_array($rsd1);
	$upid1 = $dane1[up_id];
*/
	if ($upid!='') {
	
	$sql = "SELECT hd_komorka_pracownicy_telefon FROM $dbname_hd.hd_komorka_pracownicy WHERE ((hd_komorka_pracownicy_nazwa='$oz') and (hd_serwis_komorka_id=$upid) and (hd_zgl_seryjne=0) and (belongs_to=$es_filia)) LIMIT 1";
	
	$rsd = mysql_query($sql,$conn_hd);
	$ile = mysql_num_rows($rsd);
	if ($ile!=0) {
		$dane = mysql_fetch_array($rsd);
		?>
		<script>
		document.getElementById('hdoztelefon').value="<?php echo $dane[hd_komorka_pracownicy_telefon]; ?>";
		</script>
		<?php
	//	echo "<input type=text id=hdoztelefon name=hdoztelefon size=15 maxlength=15 value=\"$dane[hd_komorka_pracownicy_telefon]\" onKeyPress=\"return filterInput(1, event, false,' '); \" />";
	} else {
		//echo "<input type=text id=hdoztelefon name=hdoztelefon size=15 maxlength=15 onKeyPress=\"return filterInput(1, event, false,' '); \" />";
	}
}
}


?>