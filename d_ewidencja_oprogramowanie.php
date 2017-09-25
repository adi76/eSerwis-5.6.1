<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 
	?>
	<script>ShowWaitingMessage('Trwa zapisywanie danych...', 'Saving1');</script>
	<?php 
	$_POST=sanitize($_POST);
	$pocz=1;
	$ile=$_POST[tilosc];
	$granica = $pocz + $ile;
	$ile_dodano=0;
	while ($pocz <= $granica-1)	{
		$ed_id = $_POST[id];
		$opr_check = $_POST['oprog'.$pocz.''];
		$opr_nazwa = $_POST['op_naz'.$pocz.''];
		$sql_add = "SELECT oprogramowanie_slownik_id FROM $dbname.serwis_slownik_oprogramowania WHERE (oprogramowanie_slownik_nazwa='$opr_nazwa') LIMIT 1";
		$result_add = mysql_query($sql_add, $conn) or die($k_b);
		$opr_sl = mysql_fetch_array($result_add);
		$opr_sl_id = $opr_sl['oprogramowanie_slownik_id'];
		if ($opr_check=="on") {
			$sql_t = "INSERT INTO $dbname.serwis_oprogramowanie values ('',$ed_id,'$opr_nazwa',$es_filia,1,'$opr_sl_id')";
			$result = mysql_query($sql_t, $conn) or die($k_b);
			$ile_dodano++;
		}		
		$pocz++;
	}
	
	$sql_t1 = "UPDATE $dbname.serwis_ewidencja SET ewidencja_oprogramowanie_id='1' WHERE ewidencja_id=$ed_id LIMIT 1";
	$result1 = mysql_query($sql_t1, $conn) or die($k_b);
	
	if ($ile_dodano>0) {
		?><script>if (opener) opener.location.reload(true); self.close();</script><?php	
	} else
	  {
		?><script>info('Nie wprowadzono żadnych zmian do bazy'); self.close(); </script><?php
	  }
} else {
$sql_e = "SELECT ewidencja_id,ewidencja_typ,ewidencja_up_id,ewidencja_komputer_opis,ewidencja_komputer_sn,belongs_to FROM $dbname.serwis_ewidencja WHERE (ewidencja_id=$id)";
$result = mysql_query($sql_e, $conn) or die($k_b);
while ($newArray = mysql_fetch_array($result)) {
	$temp_id  			= $newArray['ewidencja_id'];
	$temp_rola_id		= $newArray['ewidencja_typ'];
	$temp_up_id			= $newArray['ewidencja_up_id'];
	$temp_nazwa			= $newArray['ewidencja_komputer_opis'];
	$temp_sn			= $newArray['ewidencja_komputer_sn'];
	$temp_belongs_to	= $newArray['belongs_to'];
}
list($temp_rola_nazwa)=mysql_fetch_array(mysql_query("SELECT rola_nazwa FROM $dbname.serwis_slownik_rola WHERE (rola_id=$temp_rola_id) LIMIT 1",$conn));
list($temp_up_nazwa)=mysql_fetch_array(mysql_query("SELECT up_nazwa FROM $dbname.serwis_komorki WHERE (up_id=$temp_up_id) LIMIT 1",$conn));
pageheader("Dodawanie nowego oprogramowania dla");
if ($temp_sn!='') { infoheader("<b>".$temp_rola_nazwa." ".$temp_nazwa."<br /> (SN: ".$temp_sn.")</b><br /><br />Lokalizacja: <b>".$temp_up_nazwa."</b>"); } else { 
	infoheader("<b>".$temp_rola_nazwa." ".$temp_nazwa."</b><br /><br />Lokalizacja: <b>".$temp_up_nazwa."</b>");
}
echo "<form name=add action=$PHP_SELF method=POST>";
$sql_o = "SELECT oprogramowanie_slownik_id,oprogramowanie_slownik_nazwa FROM $dbname.serwis_slownik_oprogramowania ORDER BY oprogramowanie_slownik_nazwa";
$result_o = mysql_query($sql_o, $conn) or die($k_b);
$lp=0;
$j=0;
starttable();
th(";r;Wybierz|;;Nazwa oprogramowania",$es_prawa);
	while ($newArray_o = mysql_fetch_array($result_o)) {
		$lp++;
		$temp_id	= $newArray_o['oprogramowanie_slownik_id'];
		$temp_oprog	= $newArray_o['oprogramowanie_slownik_nazwa'];
		$sql_o1  = "SELECT oprogramowanie_id,oprogramowanie_nazwa,oprogramowanie_slownik_id FROM $dbname.serwis_oprogramowanie WHERE ((oprogramowanie_ewidencja_id='$id') and (oprogramowanie_nazwa='$temp_oprog')) ORDER BY oprogramowanie_nazwa";
		$result_o1 = mysql_query($sql_o1, $conn) or die($k_b);
		while ($newArray_o1 = mysql_fetch_array($result_o1)) {
			$temp_id1		= $newArray_o1['oprogramowanie_id'];
			$temp_oprog1	= $newArray_o1['oprogramowanie_nazwa'];
			$temp_sl_oprog1	= $newArray_o1['oprogramowanie_slownik_id'];
		}
		if ($temp_oprog1!=$temp_oprog) {
		   	tbl_tr_highlight($j);
			$j++;
				td_(";r;");
					echo "<input class=border0 type=checkbox name=oprog$lp>";
				_td();
				td_(";l;");
					echo "<input type=hidden name=op_naz$lp value='$temp_oprog'>";
					echo "$temp_oprog";
				_td();
			_tr();
		}
	}
endtable();
echo "<input type=hidden name=tilosc value=$lp>";
echo "<input type=hidden name=id value=$id>";
startbuttonsarea("right");
//addbackbutton("'Wróć do poprzedniego widoku'");
addbuttons("zapisz","zamknij");
endbuttonsarea();
_form();
}
?>
<script>HideWaitingMessage('Saving1');</script>
</body>
</html>