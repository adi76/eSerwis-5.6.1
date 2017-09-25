<?php 
include_once('header.php'); 

if ($submit5) { 

	$sql_r = "DELETE FROM $dbname.serwis_plot WHERE belongs_to=$es_filia";
	$result_r = mysql_query($sql_r, $conn) or die($k_b);

	switch ($_POST[wb]) {
	
		case serwis_slownik_konfiguracja : 

			$sql="SELECT ewidencja_komputer_opis, COUNT(*) AS ilosc FROM $dbname.serwis_ewidencja WHERE (belongs_to =$es_filia) GROUP BY ewidencja_komputer_opis";
			$result = mysql_query($sql, $conn) or die($k_b);

			while ($konfiguracja = mysql_fetch_array($result))
			{
				$typ 		= $konfiguracja['ewidencja_komputer_opis'];
				$typ_i 		= $konfiguracja['ilosc'];

				if ($typ_i>$_POST[limituj]) {
				
				$sql_plot 	= "INSERT INTO $dbname.serwis_plot VALUES ('', '$typ','$typ_i','','','','blue',$es_filia)";
				$wynik 		= mysql_query($sql_plot, $conn) or die($k_b);
				}
			}
			?>
			<img src="p_plot.php?rodzaj=1">
			<?php
			
			break;											
		case serwis_slownik_drukarka : 	
			$sql="SELECT ewidencja_drukarka_opis, COUNT(*) AS ilosc FROM $dbname.serwis_ewidencja WHERE (belongs_to =$es_filia) GROUP BY ewidencja_drukarka_opis";
			$result = mysql_query($sql, $conn) or die($k_b);
			
			while ($konfiguracja = mysql_fetch_array($result))
			{
				$typ 		= $konfiguracja['ewidencja_drukarka_opis'];
				$typ_i 		= $konfiguracja['ilosc'];
				
				if ($typ_i>$_POST[limituj]) {	
				
				$sql_plot 	= "INSERT INTO $dbname.serwis_plot VALUES ('', '$typ','$typ_i','','','','red',$es_filia)";
				$wynik 		= mysql_query($sql_plot, $conn) or die($k_b);
				
				}
			}
			?>
			<img src="p_plot.php?rodzaj=2">
			<?php
			break;
		case serwis_slownik_monitor :
			$sql="SELECT ewidencja_monitor_opis, COUNT(*) AS ilosc FROM $dbname.serwis_ewidencja WHERE (belongs_to =$es_filia) GROUP BY ewidencja_monitor_opis";
			$result = mysql_query($sql, $conn) or die($k_b);
			
			while ($konfiguracja = mysql_fetch_array($result))
			{
				$typ 		= $konfiguracja['ewidencja_monitor_opis'];
				$typ_i 		= $konfiguracja['ilosc'];
				
				if ($typ_i>$_POST[limituj]) {	
				
				$sql_plot 	= "INSERT INTO $dbname.serwis_plot VALUES ('', '$typ','$typ_i','','','','green',$es_filia)";
				$wynik 		= mysql_query($sql_plot, $conn) or die($k_b);
				
				}
			}
			?>
			<img src="p_plot.php?rodzaj=3">
			<?php		
			break;
		case serwis_slownik_oprogramowania :
			$sql="SELECT oprogramowanie_nazwa, COUNT(*) AS ilosc FROM $dbname.serwis_oprogramowanie WHERE (belongs_to =$es_filia) GROUP BY oprogramowanie_nazwa";
			$result = mysql_query($sql, $conn) or die($k_b);
			
			while ($konfiguracja = mysql_fetch_array($result))
			{
				$typ 		= $konfiguracja['oprogramowanie_nazwa'];
				$typ_i 		= $konfiguracja['ilosc'];
				
				if ($typ_i>$_POST[limituj]) {
	
				$sql_plot 	= "INSERT INTO $dbname.serwis_plot VALUES ('', '$typ','$typ_i','','','','yellow',$es_filia)";
				$wynik 		= mysql_query($sql_plot, $conn) or die($k_b);
				
				}
			}
			?>
			<img src="p_plot.php?rodzaj=4">
			<?php
			break;
		default : echo "none";			
		}
}
 else 
{
	br();
	pageheader("Generowanie zestawienia");

	echo "<table cellspacing=1 align=center style=width:55%>";
	echo "<form name=gp action=z_plot.php method=POST target=_blank>";
	tbl_empty_row();

	echo "<tr>";
		echo "<td width=280 class=right>Zestawienie</td>";
		
		echo "<td>";		
		echo "<select name=wb>\n"; 					 				
		echo "<option value='serwis_slownik_konfiguracja'>Komputerów,serwerów</option>\n"; 		
		echo "<option value='serwis_slownik_drukarka'>Drukarek</option>\n"; 		
		echo "<option value='serwis_slownik_monitor'>Monitorów</option>\n"; 		
		echo "<option value='serwis_slownik_oprogramowania'>Oprogramowania</option>\n"; 		
		echo "</select>\n"; 
		echo "</td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td width=280 class=right>Pomiń w zestawieniu, gdy ilość sztuk mniejsza lub równa </td>";
		echo "<td>";
		echo "<select name=limituj>\n";
		echo "<option value=0>Pokaż wszystko</option>\n";
		$t=1;
		while ($t<50) {
		  echo "<option value=$t>$t</option>\n";
		  $t++;
		}
		echo "</select>\n";
	echo "</tr>";
	tbl_empty_row();
	endtable();

	startbuttonsarea("center");
	addownlinkbutton("'Generuj wykres'","submit5","submit","");
	endbuttonsarea();	
	
	_form();
} 

?>

</body>
</html>