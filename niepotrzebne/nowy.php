function listabaz($poziomuprawnien="9", $pokazikony) {
	echo "<div style=margin-left:10px; id=floatmenu>";
	echo "<ul class=menulist id=listMenuRoot1>";
	echo "	<li><a class=menu_font href=#>&nbsp;Przejd&#313;&#351; do bazy&nbsp;</a>";
	echo "		<ul>";
	if($poziomuprawnien=="9") {
		echo "			<li><a href=z_uzytkownicy.php?showall=0>";
		if ($pokazikony==1) echo "<img src=img/database_users.png border=0 align=absmiddle>";
		echo "&nbsp;Pracownik&#258;³w</a></li>";
		echo "			<li><a href=z_pion.php>";
		if ($pokazikony==1) echo "<img src=img/database_piony.png border=0 align=absmiddle>";
		echo "&nbsp;Pion&#258;³w</a></li>";
		echo "			<li><a href=z_umowa.php>";
		if ($pokazikony==1) echo "<img src=img/database_umowy.png border=0 align=absmiddle>";
		echo "&nbsp;Um&#258;³w</a></li>";
		echo "			<li><a href=z_filie.php>";
		if ($pokazikony==1) echo "<img src=img/database_filie.png border=0 align=absmiddle>";
		echo "&nbsp;Filii</a></li>";
	}
	echo "			<li><a href=z_komorka.php?showall=0>";
	if ($pokazikony==1) echo "<img src=img/database_up.png border=0 align=absmiddle>";
	echo "&nbsp;Urz&#196;&#8482;d&#258;³w/kom&#258;³rek</a></li>";
	echo "			<li><a href=z_firmy_zewnetrzne.php>";
	if ($pokazikony==1) echo "<img src=img/database_fz.png border=0 align=absmiddle>";
	echo "&nbsp;Firm zewn&#196;&#8482;trznych</a></li>";
	echo "			<li><a href=z_oprogramowanie.php>";
	if ($pokazikony==1) echo "<img src=img/database_opr.png border=0 align=absmiddle>";
	echo "&nbsp;Oprogramowania</a></li>";
	echo "			<li><a href=z_typ_sprzetu.php>";
	if ($pokazikony==1) echo "<img src=img/database_typy.png border=0 align=absmiddle>";
	echo "&nbsp;Typ&#258;³w sprz&#196;&#8482;tu komputerowego</a></li>";
	echo "			<li><a href=z_konfiguracja.php>";
	if ($pokazikony==1) echo "<img src=img/database_konf.png border=0 align=absmiddle>";
	echo "&nbsp;Typ&#258;³w komputer&#258;³w z konfiguracj&#196;&#8230;</a></li>";
	echo "			<li><a href=z_monitorami.php>";
	if ($pokazikony==1) echo "<img src=img/database_monitor.png border=0 align=absmiddle>";
	echo "&nbsp;Modeli monitor&#258;³w</a></li>";
	echo "			<li><a href=z_drukarka.php>";
	if ($pokazikony==1) echo "<img src=img/database_print.png border=0 align=absmiddle>";
	echo "&nbsp;Modeli drukarek</a></li>		";
	echo "		</ul>";
	echo "	</li>";
	echo "</ul>";
	echo "</div>";
}