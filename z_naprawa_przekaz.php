<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php'); 
?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php
if ($submit) {
	$dddd = Date('Y-m-d H:i:s');
	$_POST=sanitize($_POST);

	$sql_t = "UPDATE $dbname.serwis_naprawa SET naprawa_status='-1', naprawa_przekazanie_naprawy_do='$_POST[filia_docelowa]', naprawa_przekazanie_naprawy_data='$_POST[ttn]', naprawa_przekazanie_naprawy_osoba='$currentuser', naprawa_przekazanie_zakonczone=0 WHERE (naprawa_id=$_POST[id]) LIMIT 1";

	//echo $sql_t;
	
	if (mysql_query($sql_t, $conn)) { 
		if ($_POST[hdzgl]>0) {
			$sql_t = "UPDATE $dbname_hd.hd_zgloszenie SET zgl_przekazane_do='$_POST[filia_docelowa]' WHERE (zgl_id=$_POST[hdzgl]) LIMIT 1";
			$wynik = mysql_query($sql_t, $conn_hd);
		}
		
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else 
		{
		  ?><script>info('Wystąpił błąd podczas wykonania zapytania do bazy'); self.close(); </script><?php		
		}
} else {
	pageheader("Wybierz filię/oddział do którego przekazujesz uszkodzony sprzęt");
	echo "<form name=edu action=z_naprawa_przekaz.php method=POST>";

	$result = mysql_query("SELECT naprawa_id,naprawa_nazwa,naprawa_model,naprawa_sn,naprawa_ni,naprawa_status,naprawa_ew_id,naprawa_uwagi FROM $dbname.serwis_naprawa WHERE (naprawa_id=$id) LIMIT 1", $conn) or die($k_b);
	list($temp_id,$temp_nazwa,$temp_model,$temp_sn,$temp_ni,$temp_status,$temp_ew_id,$temp_uwagi)=mysql_fetch_array($result);
	infoheader("".$temp_nazwa." <b>".$temp_model."</b> <br />SN: <b>".$temp_sn."</b>, NI: <b>".$temp_ni."</b>");
	
	if ($cs!='-1') {
		errorheader("Po przekazaniu sprzętu do innej filii/oddziału,<br />status naprawy zmieni się na <font color=white>\"uszkodzony (pobrany)\"</font>");
	}
	
	if ($_REQUEST[hdzgl]>0) okheader('Powiązane z naprawą zgłoszenie nr <b>'.$_REQUEST[hdzgl].'</b>, będzie widoczne również w filii docelowej');
	
	starttable();
	tbl_empty_row();
		tr_();
			td("150;r;Przekaż do filii / oddziału");
			td_(";;;");
				$sql1="SELECT * FROM $dbname.serwis_filie WHERE (filia_id<>$es_filia) ORDER BY filia_nazwa";
				$result1 = mysql_query($sql1, $conn) or die($k_b);
				echo "<select class=wymagane name=filia_docelowa onkeypress='return handleEnter(this, event);'>\n"; 					 				
				echo "<option value=''></option>\n";
				
				while ($newArray44 = mysql_fetch_array($result1)) 
				 {
					$f_id		= $newArray44['filia_id'];
					$f_nazwa	= $newArray44['filia_nazwa'];
					echo "<option value='$f_id'>$f_nazwa</option>\n"; 
				}
				
				echo "</select>\n"; 
			_td();
		_tr();
		tr_();
			td(";r;Data przekazania");
			td_(";;;");
				$datacala = date("Y-m-d");
				echo "<input size=10 maxlength=10 type=text id=ttn name=ttn value=$datacala onkeypress='return handleEnter(this, event);' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\">";
				echo "<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif width=16 height=16 border=0></a>";
				if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('ttn').value='".Date('Y-m-d')."'; return false;\">";
			_td();
		_tr();
	tbl_empty_row();	
	endtable();
	
	echo "<input type=hidden name=id value='$id'>";
	if ($_REQUEST[hdzgl]>0) {
		echo "<input type=hidden name=hdzgl value='$_REQUEST[hdzgl]'>";
	} else {
		echo "<input type=hidden name=hdzgl value='$_REQUEST[hdzgl]'>";
	}	
	
	startbuttonsarea("right");
	//addownsubmitbutton("'Zapisz'","submit");
	addbuttons("zapisz","anuluj");
	endbuttonsarea();
	
	_form();



?>
<script language="JavaScript">
var cal1 = new calendar1(document.forms['edu'].elements['ttn']);
	cal1.year_scroll = true;
</script>
<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("edu");
  frmvalidator.addValidation("filia_docelowa","dontselect=0","Nie wybrałeś filii/oddziału do którego miał trafić sprzęt");
</script>
<?php
}
?>
</body>
</html>