<?php include_once('header.php'); ?>
<body>
<?php
	$sql = "TRUNCATE TABLE serwis_temp2";
	$result = mysql_query($sql,$conn) or die($k_b);
	pageheader("Typy oprogramowania wg słownika",1);
	?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();

	$sql="SELECT oprogramowanie_nazwa, COUNT(*) AS ilosc, belongs_to FROM $dbname.serwis_oprogramowanie ";
	if ($es_m!=1) {
		$sql=$sql."WHERE belongs_to=$es_filia ";
	}
	$sql=$sql."GROUP BY  oprogramowanie_nazwa, belongs_to";
	$result = mysql_query($sql, $conn) or die($k_b);

	while ($konfiguracja = mysql_fetch_array($result))
	{
		$typ 		= $konfiguracja['oprogramowanie_nazwa'];
		$typ_i 		= $konfiguracja['ilosc'];
		$typ_belongs= $konfiguracja['belongs_to'];
		
		if ($typ!='') $sql_temp2 	= "INSERT INTO $dbname.serwis_temp2 VALUES ('', '$typ','$typ_i','$typ_belongs','','','','','','','','','')";
		$wynik 		= mysql_query($sql_temp2, $conn);
	}
	
	starttable();
	echo "<tr>";
		echo "<th class=right>Typ</th>";

		$count_filie = 2;
		
				$sql1 = "SELECT filia_id, filia_nazwa FROM $dbname.serwis_filie ";
				
				if ($es_m!=1) {
					$sql1=$sql1."WHERE filia_id=$es_filia ";
				}

				$result1 = mysql_query($sql1,$conn) or die($k_b);
				
				while ($dane = mysql_fetch_array($result1)) 
				{
					$filia 	= $dane['filia_id'];
					$filian = $dane['filia_nazwa'];
					echo "<th class=center>$filian</th>";
					$count_filie+=1;
				}
	echo "<th class=center>Razem</th>";
	echo "</tr>";

	$suma_typ = 0;
	$suma_kolumna = 0;
	
	$total = 0;
	
	$sql2 = "SELECT DISTINCT(pole3),pole4,pole5 FROM $dbname.serwis_temp2 GROUP BY pole3";
	$result2 = mysql_query($sql2,$conn) or die($k_b);
	$i=0;
	while ($dane2 = mysql_fetch_array($result2)) 
		{
			$typ 	= $dane2['pole3'];
			$ilosc	= $dane2['pole4'];
			
			tbl_tr_highlight($i);

			$i++;
			
				$sql1 = "select filia_id, filia_nazwa FROM $dbname.serwis_filie ";
				
				if ($es_m!=1) {
					$sql1=$sql1."WHERE filia_id=$es_filia ";
				}
				
				$result1 = mysql_query($sql1,$conn) or die($k_b);
				echo "<td class=right>$typ</td>";
				
				while ($dane = mysql_fetch_array($result1)) 
				{
					$ilosc = 0;
					$filia = $dane['filia_id'];
					$filian = $dane['filia_nazwa'];
					
					$sql3 = "SELECT * FROM $dbname.serwis_temp2 WHERE pole5='$filia' and pole3='$typ'";
					$result3 = mysql_query($sql3,$conn) or die($k_b);
					$dane3a = mysql_fetch_array($result3);
					$ilosc = $dane3a['pole4'];
					
					$suma_typ+=$ilosc;
					
					if ($ilosc=='') $ilosc='0';
					
					echo "<td class=center>$ilosc</td>";
				}
				echo "<td class=center><b>$suma_typ</b></td>";
				$total+=$suma_typ;
				$suma_typ = 0;
				
			echo "</tr>";
		}	

	endtable();

	startbuttonsarea("right");
	addbuttons("zamknij");
	endbuttonsarea();
	
?>
<script>HideWaitingMessage();</script>
</body>
</html>