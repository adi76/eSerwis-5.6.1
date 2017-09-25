<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) { 

function toUpper($string) {
	$string = strtr($string,'qwertyuiopasdfghjklzxcvbnm','QWERTYUIOPASDFGHJKLZXCVBNM');
	return (strtr($string, 'ęóąśłżźćń','ĘÓĄŚŁŻŹĆŃ' )); 
}; 

	$dddd = Date('Y-m-d H:i:s');
	$dddd = $_POST[ddate];
	$sql_d1="UPDATE $dbname.serwis_komorka_todo SET todo_status=9, todo_data_wykonania='$dddd', todo_osoba_wykonujaca='$currentuser', todo_uwagi = '".nl2br($_POST[duwagi])."' WHERE (todo_id = $_POST[id]) LIMIT 1";
	//echo "$sql_d1";
	if (mysql_query($sql_d1, $conn)) { 
		?><script>
			if (opener) opener.location.reload(true); 
			self.close(); 
		</script><?php
		
		if ($_REQUEST[UtworzZgloszenieHD]=='on') {
		?>
			<script>
				newWindow_r(800,600,'hd_d_zgloszenie.php?stage=1&fromtask=1&komorka=<?php echo urlencode($_REQUEST[komorka]); ?>&zadanie=<?php echo urlencode($_REQUEST[zadanie]); ?>&osoba=<?php echo urlencode(toUpper($_REQUEST[osobawpisujaca])); ?>&czynnosc=1');
			</script>
		<?php
		}		
		
	} else { 
		?><script>info('Wystąpił błąd podczas zakańczania czynności'); self.close(); </script><?php
	}
} else { ?>			
<?php
$wynik = mysql_query("SELECT todo_opis FROM $dbname.serwis_komorka_todo WHERE todo_id=$id LIMIT 1",$conn) or die($k_b);
list($temp_opis)=mysql_fetch_array($wynik);
okheader("Czy potwierdzasz wykonanie wybranej czynności ?");
infoheader("".skroc_tekst($temp_opis,70)."<br />w</br />".$_REQUEST[komorka]."");
startbuttonsarea("center");
echo "<form name=ez action=$PHP_SELF method=POST>";
starttable();
tbl_empty_row();
	echo "<tr>";
	echo "<td width=120 class=right>Data wykonania</td>";
	echo "<td>";
	echo "<input size=19 class=wymagane maxlength=19 type=text id=ddate name=ddate value='".Date('Y-m-d H:i:s')."' onkeypress='return handleEnter(this, event);' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\">";
	echo "<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif align=abstop width=16 height=16 border=0></a>";
	if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('ddate').value='".Date('Y-m-d')."'; return false;\">";
	echo "</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td width=120 class=righttop>Dodaj uwagi</td>";
	echo "<td><textarea name=duwagi cols=48 rows=4></textarea></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td width=120 class=righttop></td>";
	echo "<td>";
	
	echo  "<input class=border0 type=checkbox name=UtworzZgloszenieHD id=UtworzZgloszenieHD checked />";
	
	echo "<a href=# class=normalfont onClick=\"if (document.getElementById('UtworzZgloszenieHD').checked) { document.getElementById('UtworzZgloszenieHD').checked=false; } else { document.getElementById('UtworzZgloszenieHD').checked=true; }\"><font color=red>Utwórz zgłoszenie w bazie Helpdesk z tego zadania</font></a>";
	
	echo "</td>";
	echo "</tr>";	
tbl_empty_row();	
endtable();

echo "<input type=hidden name=komorka value='$_REQUEST[komorka]'>";
echo "<input type=hidden name=zadanie value='$_REQUEST[czynnosc]'>";
echo "<input type=hidden name=osobawpisujaca value='$_REQUEST[osobawpisujaca]'>";

addbuttons("tak","nie");
endbuttonsarea();
echo "<input type=hidden name=id value=$id>";
_form();
?>
<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("ez");
  frmvalidator.addValidation("ddate","req","Nie podano daty wykonania czynności");
</script>

<script language="JavaScript">
var cal1 = new calendar1(document.forms['ez'].elements['ddate']);
	cal1.year_scroll = true;
	cal1.time_comp = true;
</script>

<?php } ?>
</body>
</html>