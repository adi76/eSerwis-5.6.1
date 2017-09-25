<?php 
include_once('header.php'); 
include_once('cfg_eserwis.php');
include_once('cfg_helpdesk.php');
?>
<body>
<?php include('body_start.php');

/*echo "<span id=loading style='display:inline; float:right; background-color:yellow; margin:5px; vertical-align:center; paddin:3px;'>";
	echo "<br />&nbsp;&nbsp;Trwa analiza spójności bazy... ";
	echo "<span id=krok></span>";
	echo "<img class=imgoption style='border:0px' type=image src=img/loading.gif>&nbsp;<br /><br />";
echo "</span>";
*/

ob_flush();
flush();

// niezgodności statusów i osób przypisanych w tabelach zgloszenie i zgloszenie_szcz
$sql="SELECT zgl_nr, zgl_unikalny_nr, zgl_osoba_przypisana, zgl_status FROM $dbname_hd.hd_zgloszenie WHERE (zgl_widoczne=1)";

$result = mysql_query($sql, $conn_hd) or die($k_b);
$totalrows = mysql_num_rows($result);

if ($totalrows!=0) {
	$j = 0;
	$i = 1;
	$zapytania_do_wykonania = '';
	
	while ($newArray = mysql_fetch_array($result)) {
		$temp_zgl_nr				= $newArray['zgl_nr'];
		$temp_zgl_unique			= $newArray['zgl_unikalny_nr'];
		$temp_zgl_op				= $newArray['zgl_osoba_przypisana'];
		$temp_zgl_status			= $newArray['zgl_status'];
		
		$sql_check="SELECT zgl_szcz_status, zgl_szcz_osoba_wykonujaca_krok FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$temp_zgl_nr') ORDER BY zgl_szcz_nr_kroku DESC LIMIT 1";
		
		$result_check = mysql_query($sql_check, $conn_hd) or die($k_b);
		$totalrows_check = mysql_num_rows($result_check);
		
		if ($totalrows_check!=0) {
			$newArray2 = mysql_fetch_array($result_check);
			$temp_zgl_szcz_status		= $newArray2['zgl_szcz_status'];
			$temp_zgl_szcz_op			= $newArray2['zgl_szcz_osoba_wykonujaca_krok'];
			
			if (($temp_zgl_op!=$temp_zgl_szcz_op) || ($temp_zgl_status!=$temp_zgl_szcz_status)) {
				if ($j==0) echo "&nbsp;<b>Lista zgłoszeń w których jest niespójność:</b><br /><br />";
				echo "<font color=black>";
				echo "&nbsp;$i.&nbsp;&nbsp;<b>$temp_zgl_nr</b> (jest: ";
				echo "</font>";
				if ($temp_zgl_op!=$temp_zgl_szcz_op) { echo "<font color=red>"; } else echo "<font color=green>";
				echo $temp_zgl_op;
				echo "</font>";
				echo " ";
				if ($temp_zgl_status!=$temp_zgl_szcz_status) { echo "<font color=red>"; } else echo "<font color=green>";
				echo $temp_zgl_status;
				echo "<font color=black>";
				echo " | powinno być: ";
				echo "</font>";
				if ($temp_zgl_op!=$temp_zgl_szcz_op) { echo "<font color=red>"; } else echo "<font color=green>";
				echo $temp_zgl_szcz_op;
				echo "</font>";
				echo " ";
				if ($temp_zgl_status!=$temp_zgl_szcz_status) { echo "<font color=red>"; } else echo "<font color=green>";
				echo $temp_zgl_szcz_status;
				echo "</font>";
				echo "<font color=black>";
				echo ") <br />";
				echo"</font>";
				
				$zapytania_do_wykonania = $zapytania_do_wykonania ."UPDATE $dbname_hd.hd_zgloszenie SET zgl_osoba_przypisana='".$temp_zgl_szcz_op."' WHERE zgl_nr=".$temp_zgl_nr." LIMIT 1;";
				
				$zapytania_do_wykonania = $zapytania_do_wykonania ."UPDATE $dbname_hd.hd_zgloszenie SET zgl_status='".$temp_zgl_szcz_status."' WHERE zgl_nr=".$temp_zgl_nr." LIMIT 1;";
				
				ob_flush();
				flush();
				
				$j++;
				$i++;
			}
		}
	} 

if ($zapytania_do_wykonania!='') {
	echo "&nbsp;<textarea id=zap cols=90 rows=5 onFocus=\"this.select();\">".$zapytania_do_wykonania."</textarea>";
	ob_flush();
	flush();
} else echo "<input type=hidden id=zap value=''>";

// niezgodności statusów i osób przypisanych w tabelach zgloszenie i zgloszenie_szcz
$sql="SELECT zgl_id, zgl_nr FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id<>zgl_nr)";

$result = mysql_query($sql, $conn_hd) or die($k_b);
$totalrows = mysql_num_rows($result);

if ($totalrows!=0) {
	$k = 0;
	$l = 1;
	$zapytania_do_wykonania2 = '';
	while ($newArray = mysql_fetch_array($result)) {
		$temp_zgl_id				= $newArray['zgl_id'];
		$temp_zgl_nr				= $newArray['zgl_nr'];
		if ($k==0) {
			if ($j!=0) echo "<br /><hr />";
			echo "<font color=black>";
			echo "<br />&nbsp;<b>Lista zgłoszeń z błędnymi numerami:</b><br /><br />";
			echo "</font>";
		}
		echo "<font color=black>&nbsp;$l.&nbsp;&nbsp;ID zgłoszenia: </font><font color=red><b>$temp_zgl_id</b></font><font color=black>	-> NR zgłoszenia: </font><font color=red><b>$temp_zgl_nr</b></font><br />";
		
		$zapytania_do_wykonania2 = $zapytania_do_wykonania2 ."UPDATE $dbname_hd.hd_zgloszenie SET zgl_nr='".$temp_zgl_id."' WHERE zgl_id=".$temp_zgl_id." LIMIT 1;";
		
		ob_flush();
		flush();

		$k++;
		$l++;
	}
}

if ($zapytania_do_wykonania2!='') {
	echo "&nbsp;<textarea id=zap1 cols=90 rows=5 onFocus=\"this.select();\">".$zapytania_do_wykonania2."</textarea>";
	ob_flush();
	flush();
} else echo "<input type=hidden id=zap1 value=''>";

// puste tematy przy zgłoszeniach
$sql="SELECT zgl_id, zgl_nr, zgl_tresc FROM $dbname_hd.hd_zgloszenie WHERE (zgl_temat='')";

$result = mysql_query($sql, $conn_hd) or die($k_b);
$totalrows = mysql_num_rows($result);

if ($totalrows!=0) {
	$m = 0;
	$l = 1;
	$zapytania_do_wykonania3 = '';
	while ($newArray = mysql_fetch_array($result)) {
		$temp_zgl_id				= $newArray['zgl_id'];
		$temp_zgl_nr				= $newArray['zgl_nr'];
		$temp_zgl_tresc				= $newArray['zgl_tresc'];
		
		if ($m==0) {
			if (($j!=0) && ($k!=0)) echo "<br /><hr />";
			echo "<font color=black>";
			echo "<br />&nbsp;<b>Lista zgłoszeń z pustymi tematami:</b><br /><br />";
			echo "</font>";
		}
		echo "<font color=black>&nbsp;$l.&nbsp;&nbsp;ID zgłoszenia: <b></font><font color=red>$temp_zgl_id</font></b><br />";
		
		$nowy_temat = substr($temp_zgl_tresc,0,70);
		$zapytania_do_wykonania3 = $zapytania_do_wykonania3 ."UPDATE $dbname_hd.hd_zgloszenie SET zgl_temat='".$nowy_temat."' WHERE zgl_id=".$temp_zgl_id." LIMIT 1;";
		
		ob_flush();
		flush();

		$m++;
		$l++;
	}
}

if ($zapytania_do_wykonania3!='') {
	echo "&nbsp;<textarea id=zap2 cols=90 rows=5 onFocus=\"this.select();\">".$zapytania_do_wykonania3."</textarea>";
	ob_flush();
	flush();
} else echo "<input type=hidden id=zap2 value=''>";

// zgłoszenia bez wpisów w tabeli hd_zgloszenia_szcz
$sql="SELECT * FROM $dbname_hd.hd_zgloszenie";

$result = mysql_query($sql, $conn_hd) or die($k_b);
$totalrows = mysql_num_rows($result);

if ($totalrows!=0) {
	$n = 0;
	$l = 1;
	$zapytania_do_wykonania4 = '';
	while ($newArray = mysql_fetch_array($result)) {
		$temp_zgl_id			= $newArray['zgl_id'];
		$temp_zgl_unikalny_nr	= $newArray['zgl_unikalny_nr'];
		$temp_zgl_data			= $newArray['zgl_data'];
		$temp_zgl_godzina		= $newArray['zgl_godzina'];
		$temp_zgl_status		= $newArray['zgl_status'];
		$temp_zgl_osoba			= $newArray['zgl_osobsa_przypisana'];
		$temp_zgl_status		= $newArray['zgl_status'];
		$temp_zgl_czas			= $newArray['zgl_razem_czas'];
		$temp_zgl_km			= $newArray['zgl_razem_km'];
		$temp_zgl_widoczne		= $newArray['zgl_widoczne'];
		$temp_zgl_bt			= $newArray['belongs_to'];
		
		$sql_check="SELECT zgl_szcz_unikalny_numer FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$temp_zgl_id') and (zgl_szcz_unikalny_numer='$temp_zgl_unikalny_nr') LIMIT 1";
		
		$result_check = mysql_query($sql_check, $conn_hd) or die($k_b);
		$totalrows_check = mysql_num_rows($result_check);
		
		if ($totalrows_check==0) {
				
			if ($n==0) {
				if (($j!=0) && ($k!=0) && ($m!=0)) echo "<br /><hr />";
				echo "<font color=black>";
				echo "<br />&nbsp;<b>Lista zgłoszeń bez wpisów w tabeli z krokami (wymaga dokładnego sprawdzenia zapytań):</b><br /><br />";
				echo "</font>";
			}
			echo "<font color=black>&nbsp;$l.&nbsp;&nbsp;ID zgłoszenia: <b></font><font color=red>$temp_zgl_id</font></b><br />";
			
			$bylwyjazd = 0;
			if ($temp_zgl_km!=0) $bylwyjazd = 1;
			$widoczne = 1;
			if ($temp_zgl_widoczne==0) $widoczne = 0;
			if ($temp_zgl_status==1) $temp_zgl_osoba='';

			list($czy_rozwiazany)=mysql_fetch_array(mysql_query("SELECT zgl_czy_rozwiazany_problem FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id=$temp_zgl_id) LIMIT 1", $conn_hd));
				
			$zapytania_do_wykonania4 = $zapytania_do_wykonania4 ."INSERT INTO $dbname_hd.hd_zgloszenie_szcz VALUES('',$temp_zgl_id,'$temp_zgl_unikalny_nr',1,'','$temp_zgl_czas','$temp_zgl_data $temp_zgl_godzina','$temp_zgl_status','rejestracja zgłoszenia','$temp_zgl_osoba','',0,0,0,$bylwyjazd,'$temp_zgl_data $temp_zgl_godzina','$temp_zgl_osoba',$widoczne,$temp_zgl_km,0,'','','','','','','',$czy_rozwiazany,0,$temp_zgl_bt);";	
			
			$zapytania_do_wykonania4 = $zapytania_do_wykonania4 ."INSERT INTO $dbname_hd.hd_zgloszenie_wyjazd VALUES ('','$temp_zgl_unikalny_nr','$temp_zgl_data','-do uzupełnienia-',$temp_zgl_km,'$temp_zgl_osoba',1,$temp_zgl_bt);";
			
			ob_flush();
			flush();

			$n++;
			$l++;
		}
	}
}

if ($zapytania_do_wykonania4!='') {
	echo "&nbsp;<textarea id=zap3 cols=90 rows=5 onFocus=\"this.select();\">".$zapytania_do_wykonania4."</textarea>";
} else echo "<input type=hidden id=zap3 value=''>";

	if (($j==0) && ($k==0) && ($m==0) && ($n==0)) {
		echo "<br /><br /><b>&nbsp;Dane w bazie są spójne</b>&nbsp;<input type=button class=buttons value='Wykonaj ponowne sprawdzenie' onClick=\"history.go(0);\">&nbsp;<input type=button class=buttons value='Zamknij' onClick=\"self.close();\">";
	} else {
		echo "<br /><br />&nbsp;";
		echo "<input type=button class=buttons value='Wykonaj ponowne sprawdzenie' onClick=\"window.location.href=window.location.href; \">&nbsp;";
		echo "<span style='float:right'>";
		echo "<input type=button class=buttons value='Zamknij' onClick=\"self.close();\">";
		echo "</span>";
	}
}
?>
</body>
</html>