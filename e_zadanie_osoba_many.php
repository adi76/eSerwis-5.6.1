<?php include_once('header.php'); ?>
<body>
<?php 

/*
if ($_REQUEST[dosiebie]=='1') {
	$sql_d1="UPDATE $dbname.serwis_zadania_pozycje SET pozycja_przypisane_osobie='$_REQUEST[cu]' WHERE (pozycja_id  IN (".$_REQUEST[nr]."))";
	if (mysql_query($sql_d1, $conn)) { 
			?><script>opener.location.reload(true); self.close(); </script><?php
	} else {
		?><script>info('Wystąpił błąd podczas wykonywania przypisania'); self.close(); </script><?php
	}
}
*/
if ($submit) { 
	$_POST=sanitize($_POST);
	
	$jedna_pozycja = explode(",", $_REQUEST[nr]);
	$ile_pozycji = count($jedna_pozycja);	
	
	$OverWriteUwagi = 0;	if ($_REQUEST[nadpisz_uwagi]=='on') $OverWriteUwagi = 1;
	$OverWriteData = 0;		if ($_REQUEST[nadpisz_date]=='on') $OverWriteData = 1;
	
	//echo "$OverWriteUwagi $OverWriteData<br />";
	
	$sql_d1 = '';
	
	for ($i=0; $i<$ile_pozycji; $i++) {
		$r3 = mysql_query("SELECT pozycja_uwagi, pozycja_zaplanowana_data_wykonania FROM $dbname.serwis_zadania_pozycje WHERE (pozycja_id=$jedna_pozycja[$i]) LIMIT 1", $conn) or die($k_b);
		list($uwagi_istniejace, $data_istniejaca)=mysql_fetch_array($r3);
		
		$sql_d1 = "UPDATE $dbname.serwis_zadania_pozycje SET pozycja_przypisane_osobie='$_POST[todoosoba]' ";
		
		if ($OverWriteUwagi==0) { 
			if ($uwagi_istniejace!='') {
				if ($_POST[poz_uwagi]!='') {
					$uwagi_istniejace = nl2br($uwagi_istniejace)."\n"; 
				}
			}
		} else { $uwagi_istniejace = ""; }
		$sql_d1 .= ", pozycja_uwagi='".$uwagi_istniejace."".nl2br($_POST[poz_uwagi])."' ";
		
		if ($OverWriteData==1) { 
			if ($data_istniejaca=='0000-00-00') {
				$data_do_zapisu = $_POST[tfdw];
				$sql_d1 .= ", pozycja_zaplanowana_data_wykonania ='".$data_do_zapisu."' ";
			}
		} else {
			$data_do_zapisu = $_POST[tfdw];
			$sql_d1 .= ", pozycja_zaplanowana_data_wykonania ='".$data_do_zapisu."' ";
		}		

		$sql_d1 .= " WHERE (pozycja_id = $jedna_pozycja[$i]) ";
		//echo $sql_d1."<br />";
		$wynik = mysql_query($sql_d1, $conn);
		
	}
	//echo $sql_d1;
	
	?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	
/*	
	if ($_POST[poz_uwagi]!='') {
		$sql_d1="UPDATE $dbname.serwis_zadania_pozycje SET pozycja_przypisane_osobie='$_POST[todoosoba]', pozycja_uwagi='".nl2br($_POST[poz_uwagi])."',  pozycja_zaplanowana_data_wykonania ='".$_POST[tfdw]."' WHERE (pozycja_id  IN (".$_REQUEST[nr]."))";
	} else {
		$sql_d1="UPDATE $dbname.serwis_zadania_pozycje SET pozycja_przypisane_osobie='$_POST[todoosoba]', pozycja_zaplanowana_data_wykonania ='".$_POST[tfdw]."' WHERE (pozycja_id  IN (".$_REQUEST[nr]."))";
	}
	
	if (mysql_query($sql_d1, $conn)) { 
			?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else {
		?><script>info('Wystąpił błąd podczas operacji usuwania'); self.close(); </script><?php
	}
*/

} else {
$sql="SELECT * FROM $dbname.serwis_zadania_pozycje WHERE (pozycja_id  IN (".$_REQUEST[nr]."))";
$result = mysql_query($sql, $conn) or die($k_b);
$count_rows = mysql_num_rows($result);
	if ($count_rows>0) {
		pageheader("Przypisywanie pozycji zadania do osoby");
		while ($dane=mysql_fetch_array($result)) { 
			$temp_up_id 	= $dane['pozycja_komorka']; 		
			$temp_zid		= $dane['pozycja_zadanie_id'];
			$temp_zdata 	= $dane['pozycja_zaplanowana_data_wykonania'];
		}
		$sql5="SELECT zadanie_opis FROM $dbname.serwis_zadania WHERE (zadanie_id=$temp_zid) LIMIT 1";
		$result5=mysql_query($sql5,$conn) or die($k_b);
		while ($dane5=mysql_fetch_array($result5)) { $opiszadania = $dane5['zadanie_opis']; }
		
		$sql44="SELECT user_first_name, user_last_name FROM $dbname.serwis_uzytkownicy ";
		$sql44 = $sql44."WHERE belongs_to=$es_filia ";
		$sql44 = $sql44." and user_locked=0 ";
		//if ($_REQUEST[dosiebie]==$currentuser) $sql44 = $sql44." and (CONCAT(user_first_name,' ',user_last_name)='$currentuser') ";
		
		$sql44 = $sql44."ORDER BY user_last_name ASC";
		
		//echo $sql44; 
		
		$result44 = mysql_query($sql44, $conn) or die($k_b);
		$count_rows = mysql_num_rows($result44);
		$i = 0;
		$r = 1;
		startbuttonsarea("left");
		echo "<h5>Zadanie: <b>$opiszadania</b><br /><br />";
		echo "<p align=left>";
		echo "<u>Wybrane pozycje:</u><br /><br /><b>";
			
			$sql="SELECT pozycja_komorka,pozycja_zaplanowana_data_wykonania FROM $dbname.serwis_zadania_pozycje WHERE (pozycja_id  IN (".$_REQUEST[nr]."))";
			$result = mysql_query($sql, $conn) or die($k_b);
			
			while ($dane=mysql_fetch_array($result)) { 
				$kn 			= $dane['pozycja_komorka'];
				$temp_zdata 	= $dane['pozycja_zaplanowana_data_wykonania'];
				
				$sql_99 = "SELECT up_id, up_nazwa, pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.belongs_to=$es_filia) and (serwis_piony.pion_id=serwis_komorki.up_pion_id) and (serwis_komorki.up_nazwa='".$kn."') LIMIT 1";
			
				$wynik = mysql_query($sql_99,$conn) or die($k_b);
				$dane1=mysql_fetch_array($wynik);
				
				echo "&nbsp;&nbsp;&nbsp;&nbsp; ".$r.". ".$dane1['pion_nazwa']." ".$dane1['up_nazwa']."";
				if ($temp_zdata!='0000-00-00') echo " | <font color=blue>zaplanowane na: ".$temp_zdata."</font>";
				echo "<br />";
				$r++;
			}
			echo "</p>";
			
		echo "</b></h5>";
		hr();
		endbuttonsarea();
		startbuttonsarea("left");
		
		starttable();
		tbl_empty_row();
		
		echo "<form name=epz action=$PHP_SELF method=POST>";
		
		echo "<input type=hidden name=id value=$id>";	
		echo "<input type=hidden name=nr value='$_REQUEST[nr]'>";
		echo "<input type=hidden name=cu value='$_REQUEST[cu]'>";

		tr_();
			td(";r;Przypisz wybrane pozycje osobie");
			td_(";;");
				if ($_REQUEST[dosiebie]=='1') {
					echo "<b>$_REQUEST[cu]</b>";
					echo "<input type=hidden name=todoosoba value='$_REQUEST[cu]'>";	
				} else {
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
				}
			_td();
		_tr();

		tr_();
			td(";rt;Zaplanowana data wykonania");
			td_(";;");
				$dddd = Date('Y-m-d');
				if ($temp_zdata=='0000-00-00') $temp_zdata='';
				if ($_REQUEST[cnt]>1) $temp_zdata = '';
				echo "<input id=tfdw size=10 maxlength=10 type=text name=tfdw maxlength=10 value='$temp_zdata' onkeypress='return handleEnter(this, event);' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\">";
				echo "&nbsp;<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img src=img/cal.gif width=16 height=16 border=0></a>";
				if ($_today==1) echo "&nbsp;<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('tfdw').value='".Date('Y-m-d')."'; return false;\">";
				echo "<a href=# class=normalfont onclick=\"getElementById('tfdw').value=''; return false; \" title=' Wyczyść zaplanowaną datę wykonania '> <img src=img/czysc.gif border=0></a>";
				
				echo "<br /><input class=border0 type=checkbox name=nadpisz_date id=nadpisz_date checked=checked><a tabindex=-1 class=normalfont href=# onClick=\"if (document.getElementById('nadpisz_date').checked) { document.getElementById('nadpisz_date').checked=false; return false; } else { document.getElementById('nadpisz_date').checked=true; return false; }\"><font color=red>&nbsp;Ustaw datę tylko dla pozycji bez zdefiniowanej daty wykonania</font></a>";
			_td();
		_tr();
		
		tr_();
			td(";rt;Uwagi");
			td_(";;");
				echo "<textarea name=poz_uwagi cols=50 rows=4></textarea>";
				echo "<br /><input class=border0 type=checkbox name=nadpisz_uwagi id=nadpisz_uwagi><a tabindex=-1 class=normalfont href=# onClick=\"if (document.getElementById('nadpisz_uwagi').checked) { document.getElementById('nadpisz_uwagi').checked=false; return false; } else { document.getElementById('nadpisz_uwagi').checked=true; return false; }\"><font color=red>&nbsp;Nadpisz istniejące uwagi</font></a>";
			_td();
		_tr();
		
		tbl_empty_row();
		endtable();
		
		endbuttonsarea();
	    startbuttonsarea("right");
		addownsubmitbutton("'Przypisz osobie'","submit");	
		//addbuttons("anuluj");
		echo "<input id=anuluj class=buttons type=button onClick=\"if (confirm('Czy anulować przypisywanie pozycji zadania ?')) self.close(); \" value='Anuluj'>";
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