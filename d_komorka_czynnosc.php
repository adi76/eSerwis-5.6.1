<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) { 
	?>
	<script>ShowWaitingMessage('Trwa zapisywanie danych...', 'Saving1');</script>
	<?php
//	$_POST=sanitize($_POST);
	$dddd = date("Y-m-d H:i:s");
	$sql_t = "INSERT INTO $dbname.serwis_komorka_todo values ('',$_POST[todoupid],'".nl2br($_POST[todoczynnosc])."','$_POST[todotermin]','$_POST[todoosoba]',$_POST[todopriorytet],'','','$_POST[todostatus]',$es_filia,'$dddd','$currentuser','')";

	if (mysql_query($sql_t, $conn)) { 
		okheader("Czynność do wykonania została dodana pomyślnie");
		$lastid = mysql_insert_id();
		?>
		<script>HideWaitingMessage('Saving1');</script>
		<script>
		if (opener) opener.location.reload(true);
		if (confirm("Czy chcesz dodać kolejną czynność do wykonania w tej komórce / UP?")) {
			window.location.href='d_komorka_czynnosc.php?id=<?php echo $_POST[todoupid]; ?>';
		} else self.close();		
		</script>
		<?php
	}
} else {
$result1=mysql_query("SELECT up_nazwa,up_pion_id FROM $dbname.serwis_komorki WHERE (up_id=$id) LIMIT 1",$conn) or die($k_b);
list($nazwaup,$temp_pion_id)=mysql_fetch_array($result1);

$sql_get_pion = "SELECT pion_nazwa FROM $dbname.serwis_piony WHERE (pion_id='$temp_pion_id') LIMIT 1";
$wynik_get_pion = mysql_query($sql_get_pion, $conn) or die($k_b);
$dane_get_pion = mysql_fetch_array($wynik_get_pion);
$temp_pion_nazwa = $dane_get_pion['pion_nazwa'];

pageheader("Dodawanie nowej czynności do wykonania");
startbuttonsarea("center");
infoheader("<b>".$temp_pion_nazwa." ".$nazwaup."</b>");
endbuttonsarea();

starttable();
echo "<form name=addc action=$PHP_SELF method=POST>";
tbl_empty_row();
	tr_();
		td("170;rt;Opis czynności");
		td_(";;");
			echo "<textarea class=wymagane name=todoczynnosc cols=40 rows=4></textarea>";
		_td();
	_tr();
	tr_();
		td("170;rt;Przypisz osobie");
		td_(";;");
			$accessLevels = array("9");
			if(array_search($es_prawa, $accessLevels)>-1) {
				$sql44="SELECT user_first_name, user_last_name FROM $dbname.serwis_uzytkownicy ";
				if ($es_m!=1) $sql44 = $sql44."WHERE belongs_to=$es_filia ";
				$sql44 = $sql44."ORDER BY user_last_name ASC";
				$result44 = mysql_query($sql44, $conn) or die($k_b);
				$count_rows = mysql_num_rows($result44);
				$i = 0;
				echo "<select name=todoosoba onkeypress='return handleEnter(this, event);'>\n"; 
				echo "<option value=''>Przypisz czynność dla oddziału</option>\n";				
				while (list($temp_imie,$temp_nazwisko)=mysql_fetch_array($result44)) {
					echo "<option value='$temp_imie $temp_nazwisko'>$temp_imie $temp_nazwisko</option>\n"; 
				}
				echo "</select>\n";
			} else {
						echo "<select name=todoosoba onkeypress='return handleEnter(this, event);'>\n"; 
						echo "<option value=''>Przypisz czynność dla oddziału</option>\n";				
						echo "<option SELECTED value='$currentuser'>$currentuser</option>\n";	
						echo "</select>\n";
					}
		_td();
	_tr();
	tr_();
		td("170;rt;Termin ostateczny");	
		td_(";;");
			$rok 		= Date('Y');
			$miesiac 	= Date('m');
			$dzien 		= Date('d');
			$dzien+=7;
			$datacala1 = mktime (0,0,0,date("m")  ,date("d")+14,date("Y"));
			$datacala = date("Y-m-d",$datacala1);
			echo "<input id=tfdw size=10 maxlength=10 type=text id=todotermin name=todotermin maxlength=10 value=$datacala onkeypress='return handleEnter(this, event);' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\">"; 
			echo "<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif width=16 height=16 border=0></a>";
			if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('todotermin').value='".Date('Y-m-d')."'; return false;\">";
		_td();
	_tr();
	tr_();
		td("170;r;Priorytet");
		td_(";;");
			echo "<select name=todopriorytet onkeypress='return handleEnter(this, event);'>\n"; 			
			echo "<option value=0>Niski</option>\n";
			echo "<option SELECTED value=1>Normalny</option>\n"; 
			echo "<option value=2>Wysoki</option>\n";
			echo "</select>\n";
		_td();
	_tr();	
	echo "<input type=hidden name=todoupid value=$id>";	
	echo "<input type=hidden name=todostatus value=1>";
tbl_empty_row();
endtable();
startbuttonsarea("right");
addbuttons("zapisz","anuluj");
endbuttonsarea();
_form();
?>
<script language="JavaScript">
var cal1 = new calendar1(document.forms['addc'].elements['todotermin']);
	cal1.year_scroll = true;
	cal1.time_comp = false;
</script>
<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("addc");
  frmvalidator.addValidation("todotermin","numerichyphen","Użyłeś niedozwolonych znaków w polu \"Termin ostateczny\"");
  frmvalidator.addValidation("todoczynnosc","req","Nie podałeś czynności do wykonania");
</script>
<?php } ?>
</body>
</html>