<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php
if ($submit) { 
	$_POST=sanitize($_POST);
	$dddd = date("Y-m-d H:i:s");

	if ($_POST[projekt_filia]=='all') { 
		$_fff = $_POST[all_filie];
	} else $_fff = '|'.$_POST[projekt_filia].'|';
	
//		$jedna_filia = explode(";", $_POST[all_filie]);
//		$ile_filii = count($jedna_filia)-1;
			
//		for ($i=0; $i<$ile_filii; $i++) {
			//$sql_t = "INSERT INTO $dbname_hd.hd_projekty values ('', '$_POST[dzopis]','$_POST[projekt_filia]','0','$currentuser','$dddd','0',$_POST[dzpriorytet],'$_POST[dztermin]')";
			//if (mysql_query($sql_t, $conn)) { 
			//echo $jedna_filia[$i].",";
//		}

		$sql_t = "INSERT INTO $dbname_hd.hd_projekty values ('', '$_POST[dzopis]','$_fff','0','$currentuser','$dddd','0',$_POST[dzpriorytet],'','','$_POST[dzuwagi]','$_POST[dztermin]')";
		//echo $sql_t;
		
		if (mysql_query($sql_t, $conn_hd)) { 
			?>
			<script>
			if (opener) opener.location.reload(true);
			self.close();
			</script>
			<?php
		} else {
				?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); 
				self.close(); 
				</script><?php		
			}
		
} else { 
	pageheader("Dodawanie nowego projektu do realizacji");
	starttable();
	echo "<form name=addz action=$PHP_SELF method=POST onSubmit=\"return ProjektDodaj('Czy napewno chcesz dodać projekt do bazy ?'); \">";	
	tbl_empty_row();
	tr_();
		td("150;r;Opis projektu");
		td_(";;");
			echo "<input class=wymagane size=80 maxlength=255 type=text id=dzopis name=dzopis onkeypress='return handleEnter(this, event);'>";		
		_td();
	_tr();
	tr_();
		td("150;r;Termin graniczny dla projektu");	
		td_(";;");
			echo "<input size=10 maxlength=10 type=text id=dztermin name=dztermin maxlength=10 value='' onkeypress='return handleEnter(this, event);' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\">"; 
			echo "<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif width=16 height=16 border=0></a>";
			if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('dztermin').value='".Date('Y-m-d')."'; return false;\">";
		_td();
	_tr();
	tr_();
		td("150;rt;Uwagi");
		td_(";;");
			echo "<textarea name=dzuwagi cols=65 rows=3></textarea>";
		_td();
	_tr();
	tr_();
		td("150;r;Priorytet");
		td_(";;");
			echo "<select name=dzpriorytet onkeypress='return handleEnter(this, event);'>\n"; 			
			echo "<option value=0>Niski</option>\n";
			echo "<option SELECTED value=1>Normalny</option>\n"; 
			echo "<option value=2>Wysoki</option>\n";
			echo "</select>\n";
		_td();
	_tr();
	
	tr_();
		td("150;r;Dla filii/oddziału");
		td_(";;");
			$sql44="SELECT filia_id,filia_nazwa FROM $dbname.serwis_filie";
			$result44 = mysql_query($sql44, $conn) or die($k_b);
			$all_filie1 = '|';
			echo "<select name=projekt_filia />";
			echo "<option value='all' SELECTED>Cały oddział $obszar</option>\n";
			while ($newArray44 = mysql_fetch_array($result44)) {
				$temp_f	= $newArray44['filia_id'];
				$temp_fn = $newArray44['filia_nazwa'];
				$all_filie1 .= $temp_f."|";
				echo "<option value='$temp_f'>$temp_fn</option>\n";
			}
			echo "</select>";
		_td();
	_tr();
		
echo "<input type=hidden name=all_filie value='".$all_filie1."'>";
tbl_empty_row();
endtable();
startbuttonsarea("right");
addbuttons("zapisz","anuluj");
endbuttonsarea();
_form();
?>
<script language="JavaScript">

var cal1 = new calendar1(document.forms['addz'].elements['dztermin']);
	cal1.year_scroll = true;
	cal1.time_comp = false;
	
</script>
<?php } ?>
</body>
</html>