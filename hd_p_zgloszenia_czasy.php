<?php 
include_once('header.php');
include_once('cfg_eserwis.php');
include_once('cfg_helpdesk.php');

echo "<body>";

	$wynik = mysql_query("SELECT zgl_E1C,zgl_E1P,zgl_E2C,zgl_E2P,zgl_E3C,zgl_E3P FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id=$_REQUEST[id]) LIMIT 1", $conn_hd);
	
	while ($dane_f1=mysql_fetch_array($wynik)) {
		$_e1c		= $dane_f1['zgl_E1C'];
		$_e1p		= $dane_f1['zgl_E1P'];
		$_e2c		= $dane_f1['zgl_E2C'];
		$_e2p		= $dane_f1['zgl_E2P'];
		$_e3c		= $dane_f1['zgl_E3C'];
		$_e3p		= $dane_f1['zgl_E3P'];

		
		pageheader("Czasy poszczególnych etapów dla zgłoszenia numer $_REQUEST[nr]");
		echo "<br />";
		starttable('auto');
		tr_();
			echo "<td class=right>";
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
	
echo "<br />";
startbuttonsarea('right');
addbuttons('zamknij');
endbuttonsarea();
	
	
?>

</body>
</html>