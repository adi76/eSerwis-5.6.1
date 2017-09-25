<?php 
include_once('cfg_helpdesk.php');
include_once('header.php');
?>

<script>
function UserSetting(bool) {
	
	SprawdzCzySieZmiesciNaA4(false);
	
	if (bool==true) {
		document.pieczatki.predefiniowane.options.selectedIndex=1;
		//document.getElementById('UserSettingsSave').style.display='';
	} else {
		document.pieczatki.predefiniowane.options.selectedIndex=0;
		//document.getElementById('UserSettingsSave').style.display='none';
	}
	return false;
}

function SprawdzCzySieZmiesciNaA4(bool) {
var max_px = 960;
var height_sum = 0
var height_free = 0;
var height_over = 0;
var increase_count = 0;
var decrease_count = 0;
if (document.getElementById('naglowek').value=='TAK') height_sum = 20;
height_sum = height_sum + (((document.getElementById('td_h').value*1)+4)*document.getElementById('td_count').value);

//alert(height_sum);

if (height_sum>max_px) {
	height_over = height_sum - max_px;
	decrease_count = (Math.floor(height_over / document.getElementById('td_h').value));
	if (decrease_count>0) {
		document.getElementById('Warning').style.display='';
		document.getElementById('Info').style.display='none';
		document.getElementById('Optimal').style.display='none';	
		document.getElementById('zmniejsz_o').innerText=''+decrease_count+'';
		document.getElementById('iw').style.color='red';
		document.getElementById('ww').style.color='red';
		if (bool==true) if (confirm('Czy chcesz kontynuować ?')) { return true; } else { return false; }
	} else {
		document.getElementById('Warning').style.display='';
		document.getElementById('Info').style.display='none';
		document.getElementById('Optimal').style.display='none';	
		document.getElementById('iw').style.color='red';
		document.getElementById('ww').style.color='red';
		if (bool==true) if (confirm('Czy chcesz kontynuować ?')) { return true; } else { return false; }	
	}
} else {
	height_free = max_px - height_sum;
	increase_count = (Math.floor(height_free / document.getElementById('td_h').value));
//	alert(height_free);
	if (increase_count>0) {
		document.getElementById('Warning').style.display='none';
		document.getElementById('Info').style.display='';
		document.getElementById('Optimal').style.display='none';	
		document.getElementById('zwieksz_o').innerText=''+increase_count+'';
		document.getElementById('iw').style.color='black';
		document.getElementById('ww').style.color='black';
		return true;
	} else {
		document.getElementById('Warning').style.display='none';
		document.getElementById('Info').style.display='none';
		document.getElementById('Optimal').style.display='';	
		document.getElementById('zwieksz_o').innerText='';
		document.getElementById('iw').style.color='black';
		document.getElementById('ww').style.color='black';	
	}
}
}
</script>

<style type="text/css">
.style50{margin:0px;background-position:center;font-family:Arial,Helvetica,sans-serif;border-top-width:1px;border-right-width:1px;border-bottom-width:1px;border-left-width:1px;border-top-style:solid;border-right-style:solid;border-bottom-style:none;border-left-style:solid;border-top-color:#000000;border-right-color:#000000;border-bottom-color:#000000;border-left-color:#000000;padding-top:3px;padding-right:3px;padding-bottom:3px;padding-left:3px;font-size:14px;font-weight:bold;}
</style>

<body>
<?php if ($submit=='Generuj') { ?>

<?php 
	$_POST=sanitize($_POST);

	$h = $_POST[td_h];
	$th_h = 20;
	$td_w0 = $_POST[td_1].'%';
	$td_w1 = $_POST[td_2].'%';
	$td_w2 = $_POST[td_3].'%';
	$td_w3 = $_POST[td_4].'%';
	$ilosc_pozycji = $_POST[td_count];
	
?>
<div class="hideme" align="center">
<br /><a href="#" style="vertical-align:bottom; cursor:hand" onClick="window.print(); return false;"><img src="img/drukuj_protokol.jpg" border="0"></a>
<a href="#" onClick="self.location.href='hd_g_delegacje_pieczatki.php?param1=<?php echo urlencode($_POST[predefiniowane]); ?>&param2=<?php echo urlencode($_POST[naglowek]); ?>&param3=<?php echo urlencode($_POST[td_h]); ?>&param4=<?php echo urlencode($_POST[td_1]); ?>&param5=<?php echo urlencode($_POST[td_2]); ?>&param6=<?php echo urlencode($_POST[td_3]); ?>&param7=<?php echo urlencode($_POST[td_4]); ?>&param8=<?php echo urlencode($_POST[td_count]); ?>&submit='" style="vertical-align:bottom; cursor:hand" onClick="history.go(-1);  return false;"><img src="img/popraw.jpg" border="0"></a>
<a href="#" style="cursor:hand;vertical-align:bottom" onClick="if (opener) opener.location.reload(true); self.close();  return false;"><img src="img//zamknij_protokol.jpg" border="0"></a><br /><br />
</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0">	

<?php if ($_POST[naglowek]=='TAK') { ?>
	<tr>
		<td height="<?php echo $th_h; ?>" width="<?php echo $td_w0; ?>" align="left" bgcolor="#ffffff" class="style50" style="text-align:center;">LP</td>
		<td height="<?php echo $th_h; ?>" width="<?php echo $td_w; ?>" align="left" bgcolor="#ffffff" class="style50" style="text-align:center;">LOKALIZACJA (PIECZĄTKA)</td>
		<td height="<?php echo $th_h; ?>" width="<?php echo $td_w; ?>" align="left" bgcolor="#ffffff" class="style50" style="text-align:center;">DATA</td>
		<td height="<?php echo $th_h; ?>" width="<?php echo $td_w; ?>" align="left" bgcolor="#ffffff" class="style50" style="text-align:center;">POTWIERDZENIE (PIECZĄTKA)</td>
	</tr>
<?php } ?>
<?php for ($i=0; $i<$ilosc_pozycji; $i++) { ?>
	<tr>
		<td height="<?php echo $h; ?>" width="<?php echo $td_w0; ?>" align="left" bgcolor="#ffffff" class="style50"></td>
		<td height="<?php echo $h; ?>" width="<?php echo $td_w1; ?>" align="left" bgcolor="#ffffff" class="style50"></td>
		<td height="<?php echo $h; ?>" width="<?php echo $td_w2; ?>" align="left" bgcolor="#ffffff" class="style50"></td>
		<td height="<?php echo $h; ?>" width="<?php echo $td_w3; ?>" align="left" bgcolor="#ffffff" class="style50"></td>
	</tr>
<?php } ?>
</table>
<div class="hideme" align="center">
<br /><a href="#" style="vertical-align:bottom; cursor:hand" onClick="window.print(); return false;"><img src="img/drukuj_protokol.jpg" border="0"></a>
<a href="#" onClick="self.location.href='hd_g_delegacje_pieczatki.php?param1=<?php echo urlencode($_POST[predefiniowane]); ?>&param2=<?php echo urlencode($_POST[naglowek]); ?>&param3=<?php echo urlencode($_POST[td_h]); ?>&param4=<?php echo urlencode($_POST[td_1]); ?>&param5=<?php echo urlencode($_POST[td_2]); ?>&param6=<?php echo urlencode($_POST[td_3]); ?>&param7=<?php echo urlencode($_POST[td_4]); ?>&param8=<?php echo urlencode($_POST[td_count]); ?>&submit='" style="vertical-align:bottom; cursor:hand" onClick="history.go(-1);  return false;"><img src="img/popraw.jpg" border="0"></a>
<a href="#" style="cursor:hand;vertical-align:bottom" onClick="if (opener) opener.location.reload(true); self.close();  return false;"><img src="img//zamknij_protokol.jpg" border="0"></a><br /><br />
</div>
<script>
//window.print();
</script>

<?php } else { 
	pageheader("Ustawienia parametrów wydruku kart dla pieczątek");
	
	echo "<div id=Warning style='display:none;'>";
		errorheader('<font style=\'font-weight:normal;\'>Przy wpisanych wartościach wydruk nie zmieści się na jednej stronie A4.<br /><br />(zmniejsz ilość wierszy o <b><span id=zmniejsz_o></span></b> lub ich wysokość)</font>');
	echo "</div>";

	echo "<div id=Info style='display:none'>";
		infoheader('Przy wpisanych wartościach, pozostanie jeszcze wolne miejsce na stronie A4.<br /><br />(możesz zwiększyć ilość wierszy o <b><span id=zwieksz_o></span></b> sztuk)');
	echo "</div>";

	echo "<div id=Optimal style='display:none'>";
		okheader('Wpisane wartości optymalnie wykorzystują dostępną przestrzeń na A4.');
	echo "</div>";	
	starttable();
	
	// wartości defaultowe
	$default_wiersz_nagl = 0; // 0 = TAK, 1 - NIE
	$default_wiersz_height = 90; // px
	$defalut_td_1_width = 8; // %
	$defalut_td_2_width = 36; // %
	$defalut_td_3_width = 20; // %
	$defalut_td_4_width = 36; // %
	$default_td_count = 10; // szt.
	
	if ($_REQUEST[param1]=='2') { // jeżeli były wpisywane parametry przez użytkownika
		$value_wiersz_nagl = 0; // 0 = TAK, 1 - NIE
		$value_wiersz_height = $_REQUEST[param3]; // px
		$value_td_1_width = $_REQUEST[param4]; // %
		$value_td_2_width = $_REQUEST[param5]; // %
		$value_td_3_width = $_REQUEST[param6]; // %
		$value_td_4_width = $_REQUEST[param7]; // %
		$value_td_count = $_REQUEST[param8]; // szt.		
	} else {
		$_REQUEST[param1]='1';
		$_REQUEST[param2]='TAK';
		$_REQUEST[param3]=$default_wiersz_height;
		$_REQUEST[param4]=$defalut_td_1_width;
		$_REQUEST[param5]=$defalut_td_2_width;
		$_REQUEST[param6]=$defalut_td_3_width;
		$_REQUEST[param7]=$defalut_td_4_width;
		$_REQUEST[param8]=$default_td_count;
		
		$value_wiersz_nagl = 0; // 0 = TAK, 1 - NIE
		$value_wiersz_height = $default_wiersz_height; // px
		$value_td_1_width = $defalut_td_1_width; // %
		$value_td_2_width = $defalut_td_2_width; // %
		$value_td_3_width = $defalut_td_3_width; // %
		$value_td_4_width = $defalut_td_4_width; // %
		$value_td_count = $default_td_count; // szt.		
	}
	
	echo "<form name=pieczatki action=$PHP_SELF method=POST onSubmit=\"return SprawdzCzySieZmiesciNaA4(true);\">";	
	tbl_empty_row();
	tr_();
		td("150;r;Ustawienia");
		td_(";;");
			echo "<select name=predefiniowane onkeypress='return handleEnter(this, event);' onChange=\"if ((this.value==1) && (confirm('Czy napewno chcesz ustawić wartości standardowe ?'))) { self.location.href='hd_g_delegacje_pieczatki.php?param1=1'; } else { UserSetting(true); } \">\n"; 
// document.pieczatki.naglowek.options.selectedIndex=".$default_wiersz_nagl."; document.getElementById('td_h').value='".$default_wiersz_height."'; document.getElementById('td_1').value='".$defalut_td_1_width."'; document.getElementById('td_2').value='".$defalut_td_2_width."'; document.getElementById('td_3').value='".$defalut_td_3_width."'; document.getElementById('td_4').value='".$defalut_td_4_width."'; document.getElementById('td_count').value='".$default_td_count."';
			echo "<option value='1' "; 
			if ($_REQUEST[param1]!='2') echo " SELECTED "; 
			echo " >Domyślne</option>\n"; 
			echo "<option value='2' "; 
			if ($_REQUEST[param1]=='2') echo " SELECTED "; 
			echo " >Użytkownika</option>\n"; 
			echo "</select>\n"; 
			//echo "&nbsp;";
			//echo "<input type=submit id=UserSettingsSave style='display:none' name=UserSettingsSave class=buttons value='Generuj' onSubmit=\"alert('1');\" />";
			
		_td();
	_tr();	
	tbl_empty_row();
	tr_();
		td("150;r;<u>Wiersz nagłówkowy:</u>");
		td_(";;");
			echo "<select id=naglowek name=naglowek onChange=\"UserSetting(true);\">";
			echo "<option value='TAK' ";
			if ($_REQUEST[param2]!='NIE') echo " SELECTED "; 
			echo ">TAK</option>\n"; 
			echo "<option value='NIE' ";
			if ($_REQUEST[param2]=='NIE') echo " SELECTED "; 
			echo ">NIE</option>\n"; 
			echo "</select>\n"; 		
		_td();
	_tr();
	tr_();	
	tbl_empty_row();
	tr_();
		echo "<td id=ww style='text-align:right'><u>Wysokość wiersza:</u>";
		td_(";;");
			echo "<input style='text-align:right' type=text maxlength=3 size=2 name=td_h id=td_h value='";
			if ($_REQUEST[param3]!='') { echo $value_wiersz_height; } else { echo $default_wiersz_height; }
			echo "' onChange=\"UserSetting(true);\"> px";
		_td();
	_tr();
	tr_();
	tr_();
		echo "<td id=iw style='text-align:right'><u>Ilość wierszy:</u>";
		td_(";;");
			echo "<input style='text-align:right' type=text maxlength=3 size=2 name=td_count id=td_count value='";
			if ($_REQUEST[param8]!='') { echo $value_td_count; } else { echo $default_td_count; }
			echo "' onChange=\"UserSetting(true);\"> szt.";
			echo "<span id=InfoOWolnych_px>";
			echo "</span>";
		_td();
	_tr();
	tr_();	
	tbl_empty_row();	
	tr_();
		td("150;r;<u>Szerokość kolumn:</u>");
		td_(";;");
		_td();
	_tr();
	tr_();
		td("150;r;<b>LP</b>");
		td_(";;");
			echo "<input style='text-align:right' type=text maxlength=3 size=2 name=td_1 id=td_1 value='";
			if ($_REQUEST[param4]!='') { echo $value_td_1_width; } else { echo $default_td_1_width; }
			echo "' onChange=\"UserSetting(true);\"> % szerokości strony";
		_td();
	_tr();
	tr_();
		td("150;r;<b>Lokalizacja (Pieczątka)</b>");
		td_(";;");
			echo "<input style='text-align:right' type=text maxlength=3 size=2 name=td_2 id=td_2 value='";
			if ($_REQUEST[param5]!='') { echo $value_td_2_width; } else { echo $default_td_2_width; }
			echo "' onChange=\"UserSetting(true);\"> % szerokości strony";
		_td();
	_tr();
	tr_();
		td("150;r;<b>Data</b>");
		td_(";;");
			echo "<input style='text-align:right' type=text maxlength=3 size=2 name=td_3 id=td_3 value='";
			if ($_REQUEST[param6]!='') { echo $value_td_3_width; } else { echo $default_td_3_width; }
			echo "' onChange=\"UserSetting(true);\"> % szerokości strony";
		_td();
	_tr();
	tr_();
		td("150;r;<b>Potwierdzenie (Pieczątka)</b>");
		td_(";;");
			echo "<input style='text-align:right' type=text maxlength=3 size=2 name=td_4 id=td_4 value='";
			if ($_REQUEST[param7]!='') { echo $value_td_4_width; } else { echo $default_td_4_width; }
			echo "' onChange=\"UserSetting(true);\"> % szerokości strony";
		_td();
	_tr();
	tbl_empty_row();
	endtable();

	startbuttonsarea("right");
	
	echo "<span style='float:left'>";
	echo "<input type=button class=buttons value='Zalecane ustawienia strony' onClick=\"newWindow_r(800,600,'info.php?newwindow=1');\" />";
	echo "</span>";
	
	echo "<input type=submit name=submit class=buttons value='Generuj' onSubmit=\"\" />";
	addbuttons("anuluj");
	endbuttonsarea();
	
	_form();	
}
?>
<script>
SprawdzCzySieZmiesciNaA4(false);
</script>
</body>
</html>