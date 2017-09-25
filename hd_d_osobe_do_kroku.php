<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body>
<?php 
if ($submit) {
	$sql88="SELECT zgl_szcz_dodatkowe_osoby_wykonujace_krok FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_id=$_REQUEST[zgl_krok_id]) LIMIT 1";
	$result88 = mysql_query($sql88, $conn_hd) or die($k_b);
	list($osobypowiazane)=mysql_fetch_array($result88);

	$DodatkoweOsoby = nl2br2($_REQUEST[lista_osob]);
	$DodatkoweOsoby = str_replace(';', ',', $DodatkoweOsoby);
	$ile_dodatkowych = substr_count($DodatkoweOsoby,', ')-1;
	
	//echo $DodatkoweOsoby."<br />";
	
	//$DodatkoweOsoby = substr($DodatkoweOsoby,0,-2);
	
	//echo $_REQUEST[juzsa];
	//echo $DodatkoweOsoby;
	$do_zapisu = $_REQUEST[juzsa] ."". $DodatkoweOsoby;
	$dddd = Date('Y-m-d H:i:s');
	
	$lista_zmian='<u>Zmiana dodatkowych osób wykonujących krok (dodanie):</u> <b>'.$_POST[oldjuzsa].'</b> -> <b>'.substr($do_zapisu,0,-2).'</b><br />';
		
	$sql_d1="UPDATE $dbname_hd.hd_zgloszenie_szcz SET zgl_szcz_dodatkowe_osoby_wykonujace_krok='$do_zapisu' WHERE (zgl_szcz_id = '$_REQUEST[zgl_krok_id]') LIMIT 1";
	if (mysql_query($sql_d1, $conn_hd)) { 
	
		if ($lista_zmian!='') {
			$sql_insert = "INSERT INTO $dbname_hd.hd_zgloszenie_kroki_historia_zmian values ('', '$_POST[zgl_krok_id]','$dddd','$currentuser','$lista_zmian',1,'',$es_filia)";
			$wynik = mysql_query($sql_insert, $conn_hd);		
		}
	
		?><script>
		self.close(); 
		if (opener) opener.location.reload(true);
		</script><?php
	} else { 
		?><script>info('Wystąpił błąd podczas operacji dodawania osób do kroku'); self.close(); </script><?php
	}	
	
} else {

$lista_sql = '\'';
$lista_sql .= $_REQUEST[pomin];
$lista_sql .= '\'';

if ($_REQUEST[juzsa]!='') {
	$lista_sql .= ',';
	$jeden_dodatkowy = explode(", ", $_REQUEST[juzsa]);
	$ile_dodatkowych = substr_count($_REQUEST[juzsa],', ')-1;
	for ($xx=0; $xx<=$ile_dodatkowych; $xx++) {
		$lista_sql .= '\'';
		$lista_sql .= $jeden_dodatkowy[$xx];
		$lista_sql .= '\'';
		if ($xx<$ile_dodatkowych) $lista_sql .= ',';
	}
}
	
$sql_filtruj = "SELECT user_first_name, user_last_name FROM $dbname.serwis_uzytkownicy WHERE (belongs_to=$_REQUEST[filiaid]) and (user_locked=0) and (CONCAT(user_first_name,' ',user_last_name) NOT IN (".$lista_sql.")) ORDER BY user_last_name ASC";
//echo "$sql_filtruj";

$result44 = mysql_query($sql_filtruj, $conn) or die($k_b);
$count_rows = mysql_num_rows($result44);
	
//if ($count_rows>0) {
	
	pageheader('Dodawanie osób do kroku nr <b>'.$_REQUEST[kroknr].'</b> zgłoszenia nr <b>'.$_REQUEST[zgl_nr].'</b>');
	starttable();
	echo "<form name=hd_dodaj_osoby action=$PHP_SELF method=POST onSubmit=\"return pytanie_check1('Potwierdzasz poprawność danych ?');\" />";
	echo "<tr><td colspan=2></td></tr>";
	echo "<tr><td class=right>Osoba rejestrująca krok</td><td><b>$_REQUEST[pomin]</b></td></tr>";
	echo "<tr><td colspan=2></td></tr>";
	echo "<tr><td colspan=2></td></tr>";
	
	echo "<tr>";
		echo "<td class=right>";
			echo "Pokaż osoby z filii";
		echo "</td>";
		echo "<td>";
			$sql1="SELECT * FROM $dbname.serwis_filie ORDER BY filia_nazwa";
			$result1 = mysql_query($sql1, $conn) or die($k_b);
						
			echo "<select name=filia_docelowa onChange=\"self.location='hd_d_osobe_do_kroku.php?zgl_krok_id=".$_REQUEST[zgl_krok_id]."&zgl_nr=".$_REQUEST[zgl_nr]."&kroknr=".$_REQUEST[kroknr]."&pomin=".urlencode($_REQUEST[pomin])."&juzsa=".$_REQUEST[juzsa]."&filiaid='+this.value+''; \">\n"; 					 				
			
			while ($newArray44 = mysql_fetch_array($result1)) {
				$f_id		= $newArray44['filia_id'];
				$f_nazwa	= $newArray44['filia_nazwa'];
				echo "<option "; if ($_REQUEST[filiaid]==$f_id) echo " SELECTED"; echo " value='$f_id'>$f_nazwa</option>\n"; 
			}
			
			echo "</select>\n"; 		
		echo "</td>";
	echo "</tr>";
	
	echo "<tr>";		
		echo "<td class=righttop><br />Dodatkowe osoby";
		echo "</td>";
		
		if ($count_rows!=0) {
			td_(";;;");
				//echo "<b>".$currentuser."</b>";
				echo "<br />";			
			
				if ($count_rows>0) {
					echo "<select class=wymagane name=userlist[] size=10 id=userlist multiple=multiple>\n";
					while (list($temp_imie,$temp_nazwisko) = mysql_fetch_array($result44)) {
						$imieinazwisko = $temp_imie." ".$temp_nazwisko;
						echo "<option value='$temp_imie $temp_nazwisko' ";
						//if ($imieinazwisko==$currentuser) echo " SELECTED ";
						echo ">$temp_imie $temp_nazwisko</option>\n"; 
					}
					echo "</select>";
				}
				
				if ($count_rows>0) {
					//echo "Wybrane osoby<br />";
					echo "<textarea style='background-color:transparent;font-size:9px;font-family:tahoma;display:none;' name=lista_osob  id=userlist_selectedItems readonly cols=85 rows=3></textarea>";
				} else echo "<input type=hidden name=lista_osob id=userlist_selectedItems value=''>";
				
				_td();
			} else echo "<td><br /><font color=red>brak osób do dodania z tej filii</font></td>";
		echo "</tr>";
	echo "<tr><td colspan=2></td></tr>";
	
//} 
//else {
?>
<script>
//alert('Do tego kroku nie możesz dodać więcej osób z tej filii');
//self.close();
</script>
<?php
//}

endtable();	
startbuttonsarea("right");

echo "<input type=hidden name=oldjuzsa value='".substr($_REQUEST[juzsa],0,-2)."' />";

echo "<input type=hidden name=zgl_krok_id value='$_REQUEST[zgl_krok_id]' />";
echo "<input type=hidden name=juzsa value='$_REQUEST[juzsa]' />";

if ($count_rows!=0) echo "<input id=submit type=submit class=buttons name=submit value='Zapisz' />";
echo "<input id=anuluj class=buttons type=button onClick=\"pytanie_anuluj('Potwierdzasz anulowanie wprowadzonych zmian ?');\" value=Anuluj>";
endbuttonsarea();
echo "</form>";

}
?>
</body>
</html>