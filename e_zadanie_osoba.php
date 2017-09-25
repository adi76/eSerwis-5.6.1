<?php include_once('header.php'); ?>
<body>
<?php 
if ($_REQUEST[dosiebie]=='1') {
	$sql_d1="UPDATE $dbname.serwis_zadania_pozycje SET pozycja_przypisane_osobie='$_REQUEST[cu]', pozycja_uwagi='".nl2br($_POST[poz_uwagi])."' WHERE (pozycja_id = '$_REQUEST[id]') LIMIT 1";
	//echo $sql_d1;
	if (mysql_query($sql_d1, $conn)) { 
			?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else {
		?><script>info('Wystąpił błąd podczas wykonywania przypisania'); self.close(); </script><?php
	}
}

if ($submit) { 
	$_POST=sanitize($_POST);
	$OverWriteUwagi = 0;
	if ($_REQUEST[nadpisz_uwagi]=='on') $OverWriteUwagi = 1;
	
	if ($OverWriteUwagi==0) {
		$r3 = mysql_query("SELECT pozycja_uwagi FROM $dbname.serwis_zadania_pozycje WHERE (pozycja_id=$_POST[id]) LIMIT 1", $conn) or die($k_b);
		list($uwagi_istniejace)=mysql_fetch_array($r3);
		$uwagi_istniejace .= "<br />";
		
		$sql_d1="UPDATE $dbname.serwis_zadania_pozycje SET pozycja_przypisane_osobie='$_POST[todoosoba]', pozycja_uwagi='".$uwagi_istniejace."".nl2br($_POST[poz_uwagi])."', pozycja_zaplanowana_data_wykonania = '$_POST[tfdw]' WHERE (pozycja_id = '$_POST[id]') LIMIT 1";
		//echo $sql_d1;
		
	} else {
		$sql_d1="UPDATE $dbname.serwis_zadania_pozycje SET pozycja_przypisane_osobie='$_POST[todoosoba]', pozycja_uwagi='".nl2br($_POST[poz_uwagi])."', pozycja_zaplanowana_data_wykonania = '$_POST[tfdw]' WHERE (pozycja_id = '$_POST[id]') LIMIT 1";
	}	
	
	if (mysql_query($sql_d1, $conn)) { 
			?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else {
		?><script>info('Wystąpił błąd podczas operacji usuwania'); self.close(); </script><?php
	}
} else {
$sql="SELECT * FROM $dbname.serwis_zadania_pozycje WHERE pozycja_id=$id LIMIT 1";
$result = mysql_query($sql, $conn) or die($k_b);
$count_rows = mysql_num_rows($result);
	if ($count_rows>0) {
		pageheader("Przypisywanie pozycji zadania do osoby");
		while ($dane=mysql_fetch_array($result)) { 
			$temp_up_id 	= $dane['pozycja_komorka']; 		
			$temp_zid		= $dane['pozycja_zadanie_id'];
			$temp_uwagi		= $dane['pozycja_uwagi'];
			$temp_zdata 	= $dane['pozycja_zaplanowana_data_wykonania'];
		}
		$sql5="SELECT zadanie_opis FROM $dbname.serwis_zadania WHERE (zadanie_id=$temp_zid) LIMIT 1";
		$result5=mysql_query($sql5,$conn) or die($k_b);
		while ($dane5=mysql_fetch_array($result5)) { $opiszadania = $dane5['zadanie_opis']; }
		
		$sql44="SELECT user_first_name, user_last_name FROM $dbname.serwis_uzytkownicy ";
		if ($es_m!=1) $sql44 = $sql44."WHERE belongs_to=$es_filia ";
		$sql44 = $sql44." and user_locked=0 ";
		
		if ($_REQUEST[dosiebie]==$currentuser) $sql44 = $sql44." and (CONCAT(user_first_name,' ',user_last_name)='$currentuser') ";
		
		$sql44 = $sql44."ORDER BY user_last_name ASC";
		
		//echo $sql44;
		
		$result44 = mysql_query($sql44, $conn) or die($k_b);
		$count_rows = mysql_num_rows($result44);
		$i = 0;
		startbuttonsarea("left");
		$result9 = mysql_query("SELECT up_id, up_pion_id, up_komorka_macierzysta_id FROM $dbname.serwis_komorki WHERE (up_nazwa='$temp_up_id') and (belongs_to=$es_filia) LIMIT 1", $conn) or die($k_b);
		list($up_id,$temp_pion_id,$temp_komorka_macierzysta)=mysql_fetch_array($result9);
		
		// nazwa pionu z id pionu
		$sql_get_pion = "SELECT pion_nazwa FROM $dbname.serwis_piony WHERE (pion_id='$temp_pion_id') LIMIT 1";
		$wynik_get_pion = mysql_query($sql_get_pion, $conn) or die($k_b);
		$dane_get_pion = mysql_fetch_array($wynik_get_pion);
		$temp_pion_nazwa = $dane_get_pion['pion_nazwa'];

		infoheader("Zadanie : <b>$opiszadania</b><br />Do wykonania w : <b>$temp_pion_nazwa $temp_up_id</b>");
		hr();
		endbuttonsarea();
		//startbuttonsarea("left");

		starttable();
		tbl_empty_row();
		echo "<form name=epz action=$PHP_SELF method=POST>";
		echo "<input type=hidden name=id value=$id>";	
		tr_();
			td("170;r;Przypisz pozycję zadania osobie");
			td_(";;");
				echo "<select name=todoosoba onkeypress='return handleEnter(this, event);'>\n"; 
				echo "<option ";
				if ($osoba=='') echo "SELECTED";
				echo " value=''>Przypisz czynność dla oddziału</option>\n";				
				while ($newArray44 = mysql_fetch_array($result44)) {
					$temp_imie			= $newArray44['user_first_name'];
					$temp_nazwisko		= $newArray44['user_last_name'];
					$imieinazwisko = $temp_imie." ".$temp_nazwisko;
					
					echo "<option ";
					if (($imieinazwisko==$osoba) && ($_REQUEST[dosiebie]==0)) echo "SELECTED";
					if ($_REQUEST[dosiebie]==$imieinazwisko) echo " SELECTED ";
					echo " value='$temp_imie $temp_nazwisko'>$temp_imie $temp_nazwisko</option>\n"; 
				}
				echo "</select>\n";
			_td();
		_tr();
		
		tr_();
			td("170;r;Zaplanowana data wykonania");
			td_(";;");
				$dddd = Date('Y-m-d');
				if ($temp_zdata=='0000-00-00') $temp_zdata='';
				echo "<input id=tfdw size=10 maxlength=10 type=text name=tfdw maxlength=10 value='$temp_zdata' onkeypress='return handleEnter(this, event);' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\">";
				echo "&nbsp;<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img src=img/cal.gif width=16 height=16 border=0></a>";
				if ($_today==1) echo "&nbsp;<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('tfdw').value='".Date('Y-m-d')."'; return false;\">";
				echo "<a href=# class=normalfont onclick=\"getElementById('tfdw').value=''; return false; \" title=' Wyczyść zaplanowaną datę wykonania '> <img src=img/czysc.gif border=0></a>";
			_td();
		_tr();
		
		tr_();
			td("170;rt;Uwagi");
			td_(";;");
			echo "<textarea name=poz_uwagi cols=45 rows=5>".br2nl($temp_uwagi)."</textarea>";
			
			echo "<br /><input class=border0 type=checkbox name=nadpisz_uwagi id=nadpisz_uwagi checked=checked><a tabindex=-1 class=normalfont href=# onClick=\"if (document.getElementById('nadpisz_uwagi').checked) { document.getElementById('nadpisz_uwagi').checked=false; return false; } else { document.getElementById('nadpisz_uwagi').checked=true; return false; }\"><font color=red>&nbsp;Nadpisz istniejące uwagi</font></a>";
			
			_td();
		_tr();
		tbl_empty_row();
		
		endtable();
		//endbuttonsarea();
	    startbuttonsarea("right");
		addownsubmitbutton("'Przypisz osobie'","submit");	
		addbuttons("anuluj");
		endbuttonsarea();
		_form();
	}
} 
?>
<script language="JavaScript">
var cal1 = new calendar1(document.forms['epz'].elements['tfdw']);
	cal1.year_scroll = true;
	cal1.time_comp = false;
</script>
</body>
</html>