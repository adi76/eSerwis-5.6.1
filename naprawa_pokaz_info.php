<?php 
include_once('header.php'); 
session_start();
?>
<script>
$('#l_<?php echo $_REQUEST[id]; ?>').load('loader.php');
</script>
<?php

$r=mysql_query("SELECT naprawa_nazwa,naprawa_model,naprawa_sn,naprawa_ni,naprawa_przekazanie_naprawy_do, belongs_to FROM $dbname.serwis_naprawa WHERE naprawa_id=$_REQUEST[nid] LIMIT 1", $conn) or die($k_b);
if (mysql_num_rows($r)!=0) {
	$dane = mysql_fetch_array($r);
	$mnazwa 	= $dane['naprawa_nazwa'];
	$mmodel		= $dane['naprawa_model'];
	$msn	 	= $dane['naprawa_sn'];
	$mni		= $dane['naprawa_ni'];	
	$n_przekaz_do2= $dane['naprawa_przekazanie_naprawy_do'];
	$bt2		= $dane['belongs_to'];
	$n_przekazanie_zakonczone2 = $dane['naprawa_przekazanie_zakonczone'];
	
				$naprawa_przekazana_do_innej_filii2 = 0;
				if (($n_przekaz_do2!=$bt2) && ($n_przekaz_do2!=0)) {
					$change_color_start2 = "<font color=blue>";
					$change_color_stop2 = "</font>"; 
					$naprawa_przekazana_do_innej_filii2 = 1;
		//			if ($n_przekaz_do2==$es_filia) { $naprawa_przekazana_do_innej_filii2 = 1; } else { $naprawa_przekazana_do_innej_filii2 = 0; }
				} else {
					$change_color_start2 = "";
					$change_color_stop2 = "";
					$naprawa_przekazana_do_innej_filii2 = 0;
		//			if ($n_przekaz_do2==$es_filia) { $naprawa_przekazana_do_innej_filii2 = 1; } else { $naprawa_przekazana_do_innej_filii2 = 0; }
				}
				
				if ($n_przekazanie_zakonczone2==1) {
					$change_color_start2 = "";
					$change_color_stop2 = "";
					$naprawa_przekazana_do_innej_filii2 = 0;
				}
				
	echo "<hr /><u><a class=normalfont onClick=\"return false;\" href=#>";
	if ($naprawa_przekazana_do_innej_filii2==1) echo "<font color=blue>";
	echo "Naprawiany sprzęt:</u> $mnazwa $mmodel </a>";
	if (($msn!='') || ($mni!='')) echo "(";
	if ($msn!='') echo "SN: $msn";
	if (($msn!='')) echo ", ";
	if ($mni!='') echo "NI: $mni";
	if (($msn!='') || ($mni!='')) echo ")";
	
	echo "<a title='Pokaż szczegóły'><input class=imgoption type=image src=img/detail.gif onClick=\"newWindow_r(800,600,'p_naprawy_szczegoly.php?id=$_REQUEST[nid]'); return false; \"></a>";
	if ($naprawa_przekazana_do_innej_filii2==1) echo "</font>";
}
?>
<script>
$('#l_<?php echo $_REQUEST[id]; ?>').hide();
$('#i_<?php echo $_REQUEST[id]; ?>').hide();
$('#h_<?php echo $_REQUEST[id]; ?>').show();
</script>
