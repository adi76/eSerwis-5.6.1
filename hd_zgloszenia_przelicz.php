<?php 
include_once('cfg_helpdesk.php');
include_once('header.php');

echo "<body>";

if ($obszar=='Łódź') $obszar='Lodz';

$mozna_eksportowac = 1;
$wlacz_zakres_dzienny = 1; 

if ($submit) {

	pageheader("Przeliczanie czasów poszczególnych etapów dla zgłoszeń z okresu <b>$_REQUEST[d_od]</b> - <b>$_REQUEST[d_do]</b>",0,0);
	$t0 = date('Y-m-d H:i:s');
	echo "<p align=center style='padding:5px; background-color:transparent; color:green;'><span id=time0>Rozpoczęcie przeliczania: <b>$t0</b></span>";
	echo "<p align=center style='padding:5px; background-color:white; color:blue;'><span id=step1></span><span id=step2></span></p>";	
	echo "<p align=center style='padding:5px; background-color:transparent; color:green;'><span id=time1></span></p>";
	
	?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
	
	$sql_0a = "SELECT zgl_nr FROM $dbname_hd.hd_zgloszenie WHERE (zgl_data BETWEEN '$_REQUEST[d_od]' and '$_REQUEST[d_do]') and (zgl_widoczne=1)";
	$result_0a = mysql_query($sql_0a, $conn_hd) or die($k_b);
	$ilosc_zgl_z_okresu = mysql_num_rows($result_0a);

	$licznik =0;

	?><script>document.getElementById('step1').innerHTML=' Trwa przeliczanie zgłoszeń...<b><?php echo $licznik; ?></b> / <b><?php echo $ilosc_zgl_z_okresu; ?></b>';</script><?php ob_flush(); flush();
	
		while ($newArray_0a = mysql_fetch_array($result_0a)) {
			$___nr 	= $newArray_0a['zgl_nr'];

			include('algorytm_wyliczania_czasu.php');
			
			$licznik++;
		
			?>
			<script>document.getElementById('step1').innerHTML=' Trwa przeliczanie zgłoszeń...<b><?php echo $licznik; ?></b> / <b><?php echo $ilosc_zgl_z_okresu; ?></b>&nbsp;|&nbsp;<?php echo "<font color=red>Zgłoszenie aktualnie przeliczane: <b>$___nr</font></b>"; ?>';</script>
			<?php

			//if (($licznik % 10)==0) { ob_flush();flush(); }
			ob_flush();flush();			
			
		}
	
	$t1 = date('Y-m-d H:i:s');
	$t = 0;
	$t = calculate_datediff($t0, $t1, 'gms');
	if ($t==null) $t = '0 sekund';
	?><script>
		document.getElementById('step1').innerHTML=' Czasy poszczególnych etapów dla zgłoszeń z okresu zostały przeliczone | ilość zgłoszeń: <b><?php echo $ilosc_zgl_z_okresu; ?></b>&nbsp;'; 
	
		document.getElementById('time1').innerHTML='Zakończenie przeliczania: <b><?php echo $t1; ?></b>&nbsp;|&nbsp;<font color=red>Czas trwania przeliczania: <b><?php echo "$t</font>"; ?></b>'; </script>
	</script><?php
	ob_flush();
	flush();
	echo "<hr />";
	startbuttonsarea("right");
	echo "<span style='float:left'>";
		addbuttons("wstecz");
	echo "</span>";
	addbuttons("zamknij");
	endbuttonsarea();

	?><script>HideWaitingMessage();</script>

<?php 

} else { 

	pageheader("Przeliczanie czasów poszczególnych etapów dla zgłoszeń z okresu");
	echo "<form name=ruch action=$PHP_SELF method=POST onSubmit=\"if (confirm('Czy napewno chcesz przeliczyć czasy etapów w podanym okresie ?')) { return true; } else { return false; } \">";

	echo "<table cellspacing=1 align=center style='width:100%'>";
	tbl_empty_row(1);
		
	echo "<tr height=30>";

		echo "<td class=right>Zakres dat do przeliczenia</td>";
		echo "<td>";
		
			$d1_value = date('Y-m-d');
			$d2_value = date('Y-m-d');
		
			echo "<input type=text size=8 maxlength=10 name=d_od id=d_od value='".$d1_value."'>";
			echo " ... ";
			echo "<input type=text size=8 maxlength=10 name=d_do id=d_do value='".$d2_value."'>";
			
		echo "</td>";		
	echo "</tr>";	
	
	tbl_empty_row(1);
	endtable();

	startbuttonsarea("center");
	addownsubmitbutton("'Rozpocznij przeliczanie'","submit");

	echo "<span style='float:right'>";
	addbuttons('zamknij');
	echo "</span>";
	
	endbuttonsarea();
	
	_form();	
	
}

?>
</body>
</html>

