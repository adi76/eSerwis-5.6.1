<?php
	// **********************************
	// podstawowe informacje o zgłoszeniu
	// **********************************

		$ZGL_NR = $_REQUEST[hd_zgl_nr]; if ($ZGL_NR=='') $ZGL_NR=$_REQUEST[hd_nr];
		$rx = mysql_query("SELECT zgl_temat, zgl_tresc, zgl_kategoria, zgl_podkategoria, zgl_podkategoria_poziom_2 FROM $dbname_hd.hd_zgloszenie WHERE (zgl_nr='$ZGL_NR') and (zgl_widoczne=1) LIMIT 1", $conn_hd) or die($k_b);
		list($temp_temat, $temp_tresc, $temp_kategoria, $temp_podkategoria, $temp_podkategoria_poziom_2)=mysql_fetch_array($rx);
		if ($temp_podkategoria_poziom_2=='') $temp_podkategoria_poziom_2 = '-';
		switch ($temp_kategoria) {
			case 2:	$kolorgrupy='#FF7F2A'; break; 
			case 6: $kolorgrupy='#F73B3B'; break;
			case 3:	$kolorgrupy=''; break; 			
			default: if ($temp_status==9) { $kolorgrupy='#FFFFFF'; } else { $kolorgrupy=''; } break; 
		}
		
		echo "<div id=InformacjeOZgloszeniu style='display:; background-color:$kolorgrupy'><br />";		
		
		$rx = mysql_query("SELECT zgl_szcz_czas_rozpoczecia_kroku,zgl_szcz_czas_wykonywania FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$ZGL_NR') and (zgl_szcz_widoczne=1) ORDER BY zgl_szcz_nr_kroku DESC LIMIT 1", $conn_hd) or die($k_b);
		list($data_ostatniego_kroku,$czas_wykonywania_ostatniego_kroku)=mysql_fetch_array($rx);
		
		echo "<fieldset style='padding:3px 6px 3px 6px; border-width:0px;'><legend>&nbsp;<u>Podstawowe informacje o zgłszeniu nr $ZGL_NR</u>&nbsp;</legend>";
		
			echo "<table class=left width=auto style='background-color:transparent; border:0px solid;' cellspacing=1>";	
			echo "<tr><td></td><td></td></tr>";
			echo "<tr><td width=60 class=right>Temat</td><td><b>".nl2br($temp_temat)."</b></td></tr>";
			echo "<tr><td class=righttop>Treść</td><td><b>".nl2br($temp_tresc)."</b></td></tr>";
				$r1 = mysql_query("SELECT hd_kategoria_opis FROM $dbname_hd.hd_kategoria WHERE (hd_kategoria_nr='$temp_kategoria') LIMIT 1", $conn_hd) or die($k_b);
				list($kat_opis)=mysql_fetch_array($r1);
				$r2 = mysql_query("SELECT hd_podkategoria_opis FROM $dbname_hd.hd_podkategoria WHERE (hd_podkategoria_nr='$temp_podkategoria') LIMIT 1", $conn_hd) or die($k_b);
				list($podkat_opis)=mysql_fetch_array($r2);
			echo "<tr><td class=right>Kategoria</td><td><b>$kat_opis -> $podkat_opis</b></td></tr>";
			echo "<tr><td class=right>Podkategoria (poziom 2)</td><td><b>$temp_podkategoria_poziom_2</b></td></tr>";
			echo "<tr><td></td><td></td></tr>";
			echo "</table>";
			
		echo "</fieldset>";
		echo "</div>";

	// ********************************************
	// podstawowe informacje o zgłoszeniu - koniec
	// ********************************************
?>