<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php
	pageheader("Przeglądanie towarów na stanie",0,1);
	echo "<table cellspacing=1 align=center>";
	tbl_empty_row();	
	echo "<tr>";
	echo "<td class=center>";
		startbuttonsarea("center");
		echo "<form name=towary>";
		echo "Wybierz grupę towarową: ";
		$sql2="SELECT * FROM $dbname.serwis_slownik_rola ORDER BY rola_nazwa";
		$result2 = mysql_query($sql2, $conn) or die($k_b);
		echo "<select name=trodzaj onChange='document.location.href=document.towary.trodzaj.options[document.towary.trodzaj.selectedIndex].value'>\n";
		echo "<option value=''>Wybierz z listy...</option>\n";
		echo "<option value='p_towary_dostepne.php?view=normal&wybor=wszystko'>Wszystko</option>\n";
		while ($newArray2 = mysql_fetch_array($result2)) {
			$temp_id5  		= $newArray2['rola_id'];
			$temp_nazwa5	= $newArray2['rola_nazwa'];
			
			$iloscnastanie = mysql_num_rows(mysql_query("SELECT pozycja_id FROM $dbname.serwis_faktura_szcz WHERE ((belongs_to=$es_filia) and (pozycja_typ='$temp_nazwa5') and (pozycja_status=0))",$conn));
			if ($iloscnastanie>0) {
				echo "<option ";
				if ($wybor==$temp_nazwa5) echo "SELECTED ";
				echo "VALUE='p_towary_dostepne.php?view=normal&wybor=$temp_nazwa5'>$temp_nazwa5</option>\n";
			}
		}	
		echo "</select>";
		_form();
		endbuttonsarea();
	echo "</td>";
	echo "</tr>";
	tbl_empty_row();	
	endtable();
	
	_form();
	
startbuttonsarea("right");
addbuttons("start");
endbuttonsarea();
?>
</body>
</html>