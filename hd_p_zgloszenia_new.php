<?php 
include_once('header.php');
include_once('cfg_helpdesk.php');

echo "<body>";
include('body_start.php'); 

echo "<div id=content>";

//	echo "<div style='width:100%'>";
//		echo "Eddsfsffsdf";
//	echo "</div>";

	echo "<div style='display:inline;'>";
		echo "<iframe id=lista name=lista width=10% height=700px src=hd_p_lista_zlecen.php onload=\"this.autoIframe('lista');\">Twoja przeglądarka nie obsługuje ramek</iframe>";
	echo "</div>";
	
	echo "<div style='display:inline'>";
		echo "<iframe id=dane name=dane width=89% height=700px scrolling=auto src=hd_p_zgloszenia.php>Twoja przeglądarka nie obsługuje ramek</iframe>";
	echo "</div>";
	
	
echo "</div>";


/*
echo "<table width=100% border=0 style=boder:0px;>";
echo "<tr><td colspan=2>";
echo "assadd";
echo"</td></tr>";
echo "<tr>";
echo "<td width=150>";
	echo "<iframe width=100%>Twoja przeglądarka nie obsługuje ramek</iframe>";
echo "</td>";
echo "<td>";
	echo "<iframe width=100%>Twoja przeglądarka nie obsługuje ramek</iframe>";
echo "</td>";
echo "</tr>";
echo "</table>";

*/
include('body_stop.php');

?>
</body>
</html>