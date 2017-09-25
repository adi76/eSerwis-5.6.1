<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 
	$sql="SELECT zestawpozycja_id,zestawpozycja_fszcz_id,zestawpozycja_zestaw_id FROM $dbname.serwis_zestaw_szcz WHERE zestawpozycja_zestaw_id=$id";
	$result=mysql_query($sql, $conn) or die($k_b);	

	$wc = '';
	while (list($temp_id,$temp_fszcz_id,$temp_zestawid)=mysql_fetch_array($result)) {
		list($temp_towarid, $temp_nazwatowaru,$temp_sntowaru,$temp_uwagitowar)=mysql_fetch_array(mysql_query("SELECT pozycja_id, pozycja_nazwa, pozycja_sn FROM $dbname.serwis_faktura_szcz WHERE pozycja_id=$temp_fszcz_id LIMIT 1",$conn));
		$wc.=" ".$temp_nazwatowaru."";
		if ($temp_sntowaru!='') $wc.=" (SN : ".$temp_sntowaru.")";
		$wc.=",";
	}
	$wc=substr($wc,1,strlen($wc)-2);
	$d= Date('d');
	$m= Date('m');
	$r= Date('Y');

?>

<script>
self.close();
newWindow_r(700,595,'g_zestaw_obrot.php?tuwagi=<?php echo cleanup(urlencode($wc)); ?>&odswiez_openera=2&source1=<?php echo "$_REQUEST[source]"; ?>&source=<?php echo "$_REQUEST[source]"; ?>&state1=<?php echo "$_REQUEST[state]"; ?>&blank=1&c_7=on');
</script>
<?php

	//$sql_d1="DELETE FROM $dbname.serwis_slownik_drukarka WHERE (drukarka_id = '$_POST[id]') LIMIT 1";
	/*
	if (mysql_query($sql_d1, $conn)) { 
		?><script>//opener.location.reload(true); self.close(); </script><?php
	} else { 
		?><script>info('Wystąpił błąd podczas operacji usuwania'); self.close(); </script><?php
	}
	*/

} else {
$result = mysql_query("SELECT zestaw_id, zestaw_opis FROM $dbname.serwis_zestawy WHERE (zestaw_id=$id) LIMIT 1", $conn) or die($k_b);
list($temp_id,$temp_opis)=mysql_fetch_array($result);
okheader("Czy napewno chcesz utworzyć protokół do zestawu");
infoheader("<b>".skroc_tekst($temp_opis,40)." ?</b>");
startbuttonsarea("center");
echo "<form action=$PHP_SELF method=POST>";
echo "<input type=hidden name=id value=$temp_id>";
echo "<input type=hidden name=source value='$_REQUEST[source]'>";
echo "<input type=hidden name=state value='$_REQUEST[state]'>";
addbuttons("tak","nie");
endbuttonsarea();
_form();
}
?>
</body>
</html>