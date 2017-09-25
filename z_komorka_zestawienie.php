<?php include_once('header.php'); ?>
<body>
<?php
	pageheader("Zestawienie aktualnej ilości komórek w podziale na typ i kategorie",1);
	?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
	starttable('auto');
	th("150;r;Typ komórki \ Kategoria|50;c;I|50;c;II|50;c;III|50;c;IV|50;c;V|50;c;bez kat.|70;c;Łącznie",$es_prawa);
	
	$sql = "SELECT DISTINCT(up_typ) FROM $dbname.serwis_komorki WHERE (belongs_to=$es_filia) and up_typ>0";
	$result = mysql_query($sql, $conn) or die($k_b);

	$count_I_all = 0;
	$count_II_all = 0;
	$count_III_all = 0;
	$count_IV_all = 0;
	$count_V_all = 0;
	
	while ($newArray = mysql_fetch_array($result)) {
	
		$temp_typ = $newArray[up_typ];
		
		echo "<tr style='height:30px'>";
			echo "<td class=right>";
				list($typ_opis)=mysql_fetch_array(mysql_query("SELECT slownik_typ_komorki_opis FROM $dbname.serwis_slownik_typ_komorki WHERE (slownik_typ_komorki_id=$temp_typ) LIMIT 1", $conn));
				echo $typ_opis;
			echo "</td>";
			
			echo "<td class=center>";
				$count_I = 0;
				list($count_I)=mysql_fetch_array(mysql_query("SELECT COUNT(up_id) FROM $dbname.serwis_komorki WHERE (up_typ=$temp_typ) and (up_kategoria=1) and (up_active=1) and (belongs_to=$es_filia) and (up_pion_id>0)", $conn));
				if ($count_I==0) echo "<font color=silver>";
				if ($count_I>0) {
					echo "<a href=# class=normalfont onClick=\"$('#ZawartoscDIV').load('wait_ajax.php?randval='+ Math.random() + ''); $('#ZawartoscDIV').load('z_komorki_getlist.php?filia=".$es_filia."&kategoria=1&typ=".$temp_typ."'); \">$count_I</a>";
				} else {
					echo $count_I;
				}
				if ($count_I==0) echo "</font>";
			echo "</td>";
			
			echo "<td class=center>";
				$count_II = 0;
				list($count_II)=mysql_fetch_array(mysql_query("SELECT COUNT(up_id) FROM $dbname.serwis_komorki WHERE (up_typ=$temp_typ) and (up_kategoria=2) and (up_active=1) and (belongs_to=$es_filia) and (up_pion_id>0)", $conn));
				if ($count_II==0) echo "<font color=silver>";
				if ($count_II>0) {
					echo "<a href=# class=normalfont onClick=\"$('#ZawartoscDIV').load('wait_ajax.php?randval='+ Math.random() + ''); $('#ZawartoscDIV').load('z_komorki_getlist.php?filia=".$es_filia."&kategoria=2&typ=".$temp_typ."'); \">$count_II</a>";
				} else {
					echo $count_II;
				}
				if ($count_II==0) echo "</font>";
			echo "</td>";

			echo "<td class=center>";
				$count_III = 0;
				list($count_III)=mysql_fetch_array(mysql_query("SELECT COUNT(up_id) FROM $dbname.serwis_komorki WHERE (up_typ=$temp_typ) and (up_kategoria=3) and (up_active=1) and (belongs_to=$es_filia) and (up_pion_id>0) ", $conn));				
				if ($count_III==0) echo "<font color=silver>";
				if ($count_III>0) {
					echo "<a href=# class=normalfont onClick=\"$('#ZawartoscDIV').load('wait_ajax.php?randval='+ Math.random() + ''); $('#ZawartoscDIV').load('z_komorki_getlist.php?filia=".$es_filia."&kategoria=3&typ=".$temp_typ."'); \">$count_III</a>";
				} else {
					echo $count_III;
				}
				if ($count_III==0) echo "</font>";
			echo "</td>";
		
			echo "<td class=center>";
				$count_IV = 0;
				list($count_IV)=mysql_fetch_array(mysql_query("SELECT COUNT(up_id) FROM $dbname.serwis_komorki WHERE (up_typ=$temp_typ) and (up_kategoria=4) and (up_active=1) and (belongs_to=$es_filia) and (up_pion_id>0)", $conn));
				if ($count_IV==0) echo "<font color=silver>";
				if ($count_IV>0) {
					echo "<a href=# class=normalfont onClick=\"$('#ZawartoscDIV').load('wait_ajax.php?randval='+ Math.random() + ''); $('#ZawartoscDIV').load('z_komorki_getlist.php?filia=".$es_filia."&kategoria=4&typ=".$temp_typ."'); \">$count_IV</a>";
				} else {
					echo $count_IV;
				}
				if ($count_IV==0) echo "</font>";
			echo "</td>";

			echo "<td class=center>";
				$count_V = 0;
				list($count_V)=mysql_fetch_array(mysql_query("SELECT COUNT(up_id) FROM $dbname.serwis_komorki WHERE (up_typ=$temp_typ) and (up_kategoria=5) and (up_active=1) and (belongs_to=$es_filia) and (up_pion_id>0)", $conn));
				if ($count_V==0) echo "<font color=silver>";
				if ($count_V>0) {
					echo "<a href=# class=normalfont onClick=\"$('#ZawartoscDIV').load('wait_ajax.php?randval='+ Math.random() + ''); $('#ZawartoscDIV').load('z_komorki_getlist.php?filia=".$es_filia."&kategoria=5&typ=".$temp_typ."'); \">$count_V</a>";
				} else {
					echo $count_V;
				}
				if ($count_V==0) echo "</font>";
			echo "</td>";

			echo "<td class=center>";
				$count_bk = 0;
				list($count_bk)=mysql_fetch_array(mysql_query("SELECT COUNT(up_id) FROM $dbname.serwis_komorki WHERE (up_typ=$temp_typ) and (up_kategoria=0) and (up_active=1) and (belongs_to=$es_filia) and (up_pion_id>0)", $conn));
				if ($count_bk==0) echo "<font color=silver>";
				if ($count_bk>0) {
					echo "<a href=# class=normalfont onClick=\"$('#ZawartoscDIV').load('wait_ajax.php?randval='+ Math.random() + ''); $('#ZawartoscDIV').load('z_komorki_getlist.php?filia=".$es_filia."&kategoria=0&typ=".$temp_typ."'); \">$count_bk</a>";
				} else {
					echo $count_bk;
				}
				if ($count_bk==0) echo "</font>";
			echo "</td>";
			
			echo "<td class=center style='background-color:silver'>";
				$count_all = $count_I + $count_II + $count_III + $count_IV + $count_V + $count_bk;				
				
				if ($count_all>0) {
					if ($count_bk>0) {
						echo "<a href=# class=normalfont onClick=\"$('#ZawartoscDIV').load('wait_ajax.php?randval='+ Math.random() + ''); $('#ZawartoscDIV').load('z_komorki_getlist.php?filia=".$es_filia."&kategoria=0&typ=".$temp_typ."'); \">";
					} else {
						echo "<a href=# class=normalfont onClick=\"$('#ZawartoscDIV').load('wait_ajax.php?randval='+ Math.random() + ''); $('#ZawartoscDIV').load('z_komorki_getlist.php?filia=".$es_filia."&kategoria=all&typ=".$temp_typ."'); \">";
					}
					
					echo "<font color=green><b>";
					echo $count_all;
					echo "</b></font>";
					
					echo "</a>";
				} else {
					echo "<font color=grey>";
					echo $count_all;
					echo "</font>";
				}
				

			echo "</td>";
			
			$count_I_all += $count_I;
			$count_II_all += $count_II;
			$count_III_all += $count_III;
			$count_IV_all += $count_IV;
			$count_V_all += $count_V;
			$count_bk_all += $count_bk;
			
			$count_all_all += $count_all;
			
		echo "</tr>";
		
	}
	
	echo "<tr style='height:30px'>";
		echo "<td class=right>";
			echo "<b>Łącznie</b>";
		echo "</td>";	

		echo "<td class=center style='background-color:silver'>"; 
		if ($count_I_all>0) {
			echo "<a href=# class=normalfont onClick=\"$('#ZawartoscDIV').load('wait_ajax.php?randval='+ Math.random() + ''); $('#ZawartoscDIV').load('z_komorki_getlist.php?filia=".$es_filia."&kategoria=1&typ=all'); \">";
			echo "<font color=green><b>";
			echo $count_I_all;
			echo "</b></font>";
			echo "</a>";
		} else {
			echo "<font color=grey>";
			echo $count_I_all;
			echo "</font>";
		}				
		echo "</td>";

		echo "<td class=center style='background-color:silver'>"; 
		if ($count_II_all>0) {
			echo "<a href=# class=normalfont onClick=\"$('#ZawartoscDIV').load('wait_ajax.php?randval='+ Math.random() + ''); $('#ZawartoscDIV').load('z_komorki_getlist.php?filia=".$es_filia."&kategoria=2&typ=all'); \">";
			echo "<font color=green><b>";
			echo $count_II_all;
			echo "</b></font>";
			echo "</a>";
		} else {
			echo "<font color=grey>";
			echo $count_II_all;
			echo "</font>";
		}				
		echo "</td>";

		echo "<td class=center style='background-color:silver'>"; 
		if ($count_III_all>0) {
			echo "<a href=# class=normalfont onClick=\"$('#ZawartoscDIV').load('wait_ajax.php?randval='+ Math.random() + ''); $('#ZawartoscDIV').load('z_komorki_getlist.php?filia=".$es_filia."&kategoria=3&typ=all'); \">";
			echo "<font color=green><b>";
			echo $count_III_all;
			echo "</b></font>";
			echo "</a>";
		} else {
			echo "<font color=grey>";
			echo $count_III_all;
			echo "</font>";
		}				
		echo "</td>";

		echo "<td class=center style='background-color:silver'>"; 
		if ($count_IV_all>0) {
			echo "<a href=# class=normalfont onClick=\"$('#ZawartoscDIV').load('wait_ajax.php?randval='+ Math.random() + ''); $('#ZawartoscDIV').load('z_komorki_getlist.php?filia=".$es_filia."&kategoria=4&typ=all'); \">";
			echo "<font color=green><b>";
			echo $count_IV_all;
			echo "</b></font>";
			echo "</a>";
		} else {
			echo "<font color=grey>";
			echo $count_IV_all;
			echo "</font>";
		}				
		echo "</td>";

		echo "<td class=center style='background-color:silver'>"; 
		if ($count_V_all>0) {
			echo "<a href=# class=normalfont onClick=\"$('#ZawartoscDIV').load('wait_ajax.php?randval='+ Math.random() + ''); $('#ZawartoscDIV').load('z_komorki_getlist.php?filia=".$es_filia."&kategoria=5&typ=all'); \">";
			echo "<font color=green><b>";
			echo $count_V_all;
			echo "</b></font>";
			echo "</a>";
		} else {
			echo "<font color=grey>";
			echo $count_V_all;
			echo "</font>";
		}				
		echo "</td>";

		echo "<td class=center style='background-color:silver'>"; 
		if ($count_bk_all>0) {
			echo "<a href=# class=normalfont onClick=\"$('#ZawartoscDIV').load('wait_ajax.php?randval='+ Math.random() + ''); $('#ZawartoscDIV').load('z_komorki_getlist.php?filia=".$es_filia."&kategoria=0&typ=all'); \">";
			echo "<font color=green><b>";
			echo $count_bk_all;
			echo "</b></font>";
			echo "</a>";
		} else {
			echo "<font color=grey>";
			echo $count_bk_all;
			echo "</font>";
		}				
		echo "</td>";
		
		echo "<td class=center style='background-color:silver'>"; 
		if ($count_all_all>0) {
			echo "<a href=# class=normalfont onClick=\"$('#ZawartoscDIV').load('wait_ajax.php?randval='+ Math.random() + ''); $('#ZawartoscDIV').load('z_komorki_getlist.php?filia=".$es_filia."&kategoria=all&typ=all'); \">";
			echo "<font color=blue><b>";
			echo $count_all_all;
			echo "</b></font>";
			echo "</a>";
		} else {
			echo "<font color=grey>";
			echo $count_all_all;
			echo "</font>";
		}				
		echo "</td>";		

	echo "</tr>";
	
	endtable();

	
	list($count_check_typ)=mysql_fetch_array(mysql_query("SELECT COUNT(up_id) FROM $dbname.serwis_komorki WHERE (up_active=1) and (belongs_to=$es_filia) and (up_typ=0)", $conn));
	list($count_check_kat)=mysql_fetch_array(mysql_query("SELECT COUNT(up_id) FROM $dbname.serwis_komorki WHERE (up_active=1) and (belongs_to=$es_filia) and (up_kategoria=0)", $conn));
				
	//echo ">".$count_check_typ." | ".$count_all;
	//echo "[".$count_check_kat." | ".$count_all;

	startbuttonsarea("center");
	echo "<br />";
	errorheader("Uwagi / nieprawidłowości w bazie komórek: ");
	if ($count_check_typ>0) echo "&nbsp;<input type=button class=buttons title='Pokaż komórki bez ustalonego typu ' onClick=\"$('#ZawartoscDIV').load('wait_ajax.php?randval='+ Math.random() + ''); $('#ZawartoscDIV').load('z_komorki_getlist.php?filia=".$es_filia."&kategoria=all&typ=all&othertyp=1&otherkat=0');\" value=\"Komórki bez ustalonego typu (".$count_check_typ.")\" >";

	if ($count_check_kat>0) echo "<input type=button class=buttons title='Pokaż komórki bez ustalonej kategorii ' onClick=\"$('#ZawartoscDIV').load('wait_ajax.php?randval='+ Math.random() + ''); $('#ZawartoscDIV').load('z_komorki_getlist.php?filia=".$es_filia."&kategoria=all&typ=all&otherkat=1&othertyp=0'); \" value=\"Komórki bez ustalonej kategorii (".$count_check_kat.")\" >";
	
	endbuttonsarea();
	
	echo "<hr />";
	echo "<span id=ZawartoscDIV></span>";
	
	startbuttonsarea("right");
	echo "<br />";
	addbuttons("zamknij");
	endbuttonsarea();

?>
<script>HideWaitingMessage();</script>
</body>
</html>