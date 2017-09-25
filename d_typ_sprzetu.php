<?php include_once('header_begin.php'); ?>
<link rel="stylesheet" href="css/js_color_picker_v2.css" media="screen">
<script src="js/color_functions.js"></script>		
<script type="text/javascript" src="js/js_color_picker_v2.js"></script>
</head>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) { 
	?><script>ShowWaitingMessage('Trwa zapisywanie danych...', 'Saving1');</script>
	<?php
	$_POST=sanitize($_POST);
	$tylkodoew='0';
	if ($_POST['tde']=='on' ) $tylkodoew='1';
	$sql_a = "INSERT INTO $dbname.serwis_slownik_rola VALUES ('','$_POST[dnp]','$tylkodoew','$_POST[tdk]')";
	if (mysql_query($sql_a, $conn)) { 
		?>
		<script>HideWaitingMessage('Saving1');</script>
		<script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else {
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
	}
} else { 
	pageheader("Dodawanie nowego typu sprzętu do słownika");
	starttable();
	echo "<form name=add action=$PHP_SELF method=POST>";	
	tbl_empty_row();
	tr_();
		td(";r;Nazwa typu sprzętu");
		td_(";;");
			echo "<input id=typsp class=wymagane size=35 maxlength=30 type=text name=dnp onKeyUp='slownik_typowsp()' onBlur='slownik_typowsp()'>";
			echo "<img name=status src=img//none.gif>";
			echo "<select name=lista style='display:none'>";
			$result=mysql_query("SELECT rola_nazwa FROM $dbname.serwis_slownik_rola ORDER BY rola_nazwa",$conn) or die($k_b);
			while (list($temp)=mysql_fetch_array($result)) {
				echo "<option value=$temp>$temp</option>\n";
			}
			echo "</select>";		
		_td();
	_tr();
	tr_();
		td(";r;Tylko do ewidencji");
		td_(";;");
			echo "<input class=border0 type=checkbox name=tde>";
		_td();
	_tr();
	tr_();
		td(";r;Kolor podświetlenia grupy");
		td_(";;");
			echo "<input type=text size=10 name=tdk value='#FFFFFF' onClick=showColorPicker(this,document.forms[0].tdk);>";
			echo "&nbsp;<a class=normalfont href=# onClick=showColorPicker(this,document.forms[0].tdk); title=' Wybierz kolor '>Zmień kolor</a>";
			//echo "<A class=normalfont HREF=# onClick=cp.select(document.forms[0].tdk,'pick2');return false; NAME=pick2 ID=pick2>&nbsp;Wybierz kolor</A>";
		_td();
	_tr();
	tr_();
		td_(";;");
		?><script>
		//showColorPicker(this,document.forms[0].tdk);
		//return true;
		</script>
		<?php
		_td();
	_tr();	
tbl_empty_row();
endtable();
startbuttonsarea("right");
addbuttons("zapisz","anuluj");
endbuttonsarea();
_form();
?>
<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("add");
  frmvalidator.addValidation("dnp","req","Nie podano nazwy typu sprzętu");
</script>
<?php } ?>
</body>
</html>