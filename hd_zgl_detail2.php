<?php
//require_once "cfg_eserwis.php";
include_once('header.php');
require_once "cfg_helpdesk.php"; 

if(isset($_GET["id"])) {

	$sql = "SELECT zgl_szcz_nr_kroku, zgl_szcz_czas_rozpoczecia_kroku, zgl_szcz_wykonane_czynnosci,zgl_szcz_czas_wykonywania, zgl_szcz_osoba_wykonujaca_krok, zgl_szcz_status, zgl_szcz_byl_wyjazd FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$_GET[id]) and (zgl_szcz_widoczne=1) ORDER BY zgl_szcz_nr_kroku ASC";	
	
	$rsd = mysql_query($sql,$conn_hd);
	$ile = mysql_num_rows($rsd);
	if ($ile!=0) {

		list($DataRejZgl,$OsobaRejZgl)=mysql_fetch_array(mysql_query("SELECT zgl_data_wpisu, zgl_szcz_osoba_rejestrujaca FROM hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id=$_REQUEST[id]) LIMIT 1"));
		
		echo "<h4 style='text-align:left;font-family:tahoma;font-size:12px;padding:3px; margin-bottom:3px; color:black;font-weight:normal; margin-top:4px; margin-left:1px;";
		echo "'>";
		echo "Kroki zgłoszenia nr: <b>".$_GET[id]."</b> | Osoba rejestrująca: <b>$OsobaRejZgl</b> | Data rejestracji: <b>".substr($DataRejZgl,0,strlen($DataRejZgl)-3)."</b>";	
		echo "</h4>";
		
		//echo "<b>Kroki zgłoszenia nr $_GET[id]:</b><br />";
		
		starttable("auto");
		echo "<tr><th class=center>Krok nr</th><th class=center>Data rozpoczęcia<br />kroku</th><th class=center>Status</th><th>Wykonane czynności<hr />Informacje dodatkowe</th><th class=center>Czas wykonywania, data</th><th class=center>Osoba wykonująca</th></tr>";
		$k = (int) $_REQUEST[id] + rand(1,100000);
		while ($dane = mysql_fetch_array($rsd)) {
			$ttt = $dane[zgl_szcz_wykonane_czynnosci];
			
			$newTime = AddMinutesToDate($dane[zgl_szcz_czas_wykonywania],$dane[zgl_szcz_czas_rozpoczecia_kroku]);
			$part = explode(' ',$newTime);
			$newTime = $part[0]." ".substr($part[1],0,5);
					
			$ttt = str_replace('rejestracja zgłoszenia<br /><br />','rejestracja zgłoszenia:',$ttt);
			
			tbl_tr_highlight($k);
			//echo "<tr >";
			echo "<td class=center>";
				echo $dane[zgl_szcz_nr_kroku];
			echo "</td>";
			echo "<td class=center>";
				echo substr($dane[zgl_szcz_czas_rozpoczecia_kroku],0,16);
			echo "</td>";		
			echo "<td class=center>";
				list($status1)=mysql_fetch_array(mysql_query("SELECT hd_status_opis FROM $dbname_hd.hd_status WHERE hd_status_nr='$dane[zgl_szcz_status]' LIMIT 1", $conn_hd));
				echo $status1;
			echo "</td>";
			echo "<td>";
				echo $ttt;
				if ($dane[zgl_szcz_byl_wyjazd]=='1') {
					echo "<hr />";
					echo "<b>Krok powiązny z wyjazdem</b>";
				}
			echo "</td>";			
			echo "<td class=center>";
				echo $dane[zgl_szcz_czas_wykonywania]."min, ".$newTime;
			echo "</td>";
			echo "<td class=center>";
				echo $dane[zgl_szcz_osoba_wykonujaca_krok];
			echo "</td>";
			echo "</tr>";
			
			//echo "&nbsp;&nbsp;".$dane[zgl_szcz_nr_kroku].". (".substr($dane[zgl_szcz_czas_rozpoczecia_kroku],0,16)."): ".$ttt." (czas: ".$dane[zgl_szcz_czas_wykonywania]."min, ".$newTime."), ".$dane[zgl_szcz_osoba_wykonujaca_krok]."<br />";
			$k++;
		}
		echo "</table>";
	
if ($weryfikacja_more) {
	$wynik = mysql_query("SELECT zgl_E1C,zgl_E1P,zgl_E2C,zgl_E2P,zgl_E3C,zgl_E3P FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id=$_REQUEST[id]) LIMIT 1", $conn_hd);
	
	while ($dane_f1=mysql_fetch_array($wynik)) {
		$_e1c		= $dane_f1['zgl_E1C'];
		$_e1p		= $dane_f1['zgl_E1P'];
		$_e2c		= $dane_f1['zgl_E2C'];
		$_e2p		= $dane_f1['zgl_E2P'];
		$_e3c		= $dane_f1['zgl_E3C'];
		$_e3p		= $dane_f1['zgl_E3P'];

		
		pageheader("Czasy poszczególnych etapów dla zgłoszenia numer $_REQUEST[id]");
		//echo "<br />";
		starttable('auto');
		tr_();
			echo "<td width=50% class=right>";
				echo "Łączny czas na etapie reakcji";
			echo "</td>";
			echo "<td>";
				echo "<b>$_e1c min.</b>";
			echo "</td>";
		_tr();

		tr_();
			echo "<td class=right>";
				echo "Łączny czas przestojów na etapie reakcji";
			echo "</td>";
			echo "<td>";
				echo "<b>$_e1p min.</b>";
			echo "</td>";
		_tr();
		echo "<tr><td colspan=2></td></tr>";
		echo "<tr><td colspan=2></td></tr>";
		tr_();
			echo "<td class=right>";
				echo "Łączny czas na etapie rozwiązania";
			echo "</td>";
			echo "<td>";
				echo "<b>$_e2c min.</b>";
			echo "</td>";
		_tr();

		tr_();
			echo "<td class=right>";
				echo "Łączny czas przestojów na etapie rozwiązania";
			echo "</td>";
			echo "<td>";
				echo "<b>$_e2p min.</b>";
			echo "</td>";
		_tr();
		echo "<tr><td colspan=2></td></tr>";
		echo "<tr><td colspan=2></td></tr>";
		tr_();
			echo "<td class=right>";
				echo "Łączny czas na etapie zamknięcia";
			echo "</td>";
			echo "<td>";
				echo "<b>$_e3c min.</b>";
			echo "</td>";
		_tr();
		

		tr_();
			echo "<td class=right>";
				echo "Łączny czas przestojów na etapie zamknięcia";
			echo "</td>";
			echo "<td>";
				echo "<b>$_e3p min.</b>";
			echo "</td>";
		_tr();
		
		endtable();
	}
	
	//echo "<br />";
}		
		echo "<br />";
		?>
		<script>
			$('#sm_kroki_<?php echo $_GET[id];?>').hide();
			$('#hm_kroki_<?php echo $_GET[id];?>').show();
		</script>
		<?php
	} else {
		//echo "<input type=text id=hdoztelefon name=hdoztelefon size=15 maxlength=15 onKeyPress=\"return filterInput(1, event, false,' '); \" />";
	}

}


?>