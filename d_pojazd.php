<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) { 
	$_POST=sanitize($_POST);
	$dddd = Date("Y-m-d");
	
	list($zlicz_pojazdy)=mysql_fetch_array(mysql_query("SELECT COUNT(hd_pojazd_id) FROM $dbname_hd.hd_pojazdy WHERE hd_pojazd_user_id='$_POST[userid]'", $conn_hd));
	
	if ($zlicz_pojazdy>0) {
		$sql_deactivate_vehicles = "UPDATE $dbname_hd.hd_pojazdy SET hd_pojazd_active=0, hd_pojazd_do='$dddd' WHERE hd_pojazd_user_id='$_POST[userid]'";	
		$result = mysql_query($sql_deactivate_vehicles, $conn_hd);
	}
	
	$sql_a = "INSERT INTO $dbname_hd.hd_pojazdy VALUES ('','$_POST[userid]','$_POST[uzytkowany_od]','0000-00-00','$_POST[marka]','$_POST[nrrejestracyjny]','$_POST[pojemnosc]','$_POST[kategoria]','1',$es_filia)";
	//echo "$sql_a";

	if (mysql_query($sql_a, $conn_hd)) 
		{ 
			?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else 
		{
		  ?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
		}
} else { 
	pageheader("Dodawanie nowego pojazdu do bazy");
	starttable();
	echo "<form name=add action=$PHP_SELF method=POST>";	
	tbl_empty_row();
	tr_();
		td("150;r;Kategoria");
		td_(";;");
			echo "<select name=kategoria onkeypress='return handleEnter(this, event);' onChange=\"if (this.value!=4) { document.getElementById('marka').disabled=true; } else { document.getElementById('marka').disabled=false; } \">\n"; 
			echo "<option value='1'>Motorower</option>\n"; 
			echo "<option value='2'>Motocykl</option>\n"; 
			echo "<option value='3'>Samochód (pojemność do 900cm)</option>\n"; 
			echo "<option value='4' SELECTED>Samochód (pojemność powyżej 900cm)</option>\n"; 
			echo "</select>\n"; 
			
		_td();
	_tr();	
	tr_();
		td("150;r;Marka pojazdu");
		td_(";;");
		?>
			<select id="marka" name="marka">
			<option value=""></option>
			<option value="Alfa Romeo">Alfa Romeo</option>
			<option value="Audi">Audi</option>
			<option value="BMW">BMW</option>
			<option value="Chevrolet">Chevrolet</option>
			<option value="Citroën">Citroën</option>
			<option value="Daewoo">Daewoo</option>
			<option value="Fiat">Fiat</option>
			<option value="Ford">Ford</option>
			<option value="Honda">Honda</option>
			<option value="Hyundai">Hyundai</option>
			<option value="Kia">Kia</option>
			<option value="Lexus">Lexus</option>
			<option value="Mazda">Mazda</option>
			<option value="Mercedes">Mercedes</option>
			<option value="Mitsubishi">Mitsubishi</option>
			<option value="Nissan">Nissan</option>
			<option value="Opel">Opel</option>
			<option value="Peugeot">Peugeot</option>
			<option value="Polonez">Polonez</option>
			<option value="Renault">Renault</option>
			<option value="Rover">Rover</option>
			<option value="Saab">Saab</option>
			<option value="Seat">Seat</option>
			<option value="Subaru">Subaru</option>
			<option value="Suzuki">Suzuki</option>
			<option value="Škoda">Škoda</option>
			<option value="Toyota">Toyota</option>
			<option value="Trabant">Trabant</option>
			<option value="Volkswagen">Volkswagen</option>
			<option value="Volvo">Volvo</option>
			<option value="Inny">Inny</option>
			</select>
		<?php
		_td();
	_tr();	
	tr_();
		echo "<td style='text-align:right'>Pojemność silnika w cm";
		echo "<img src=img/pow_3.gif border=0 />";
		td_(";;");
			echo "<input class=wymagane size=3 maxlength=4 type=text name=pojemnosc onkeypress='return handleEnter(this, event);'>";
		_td();
	_tr();
	tr_();
		td("150;r;Numer rejestracyjny");
		td_(";;");
			echo "<input class=wymagane size=10 maxlength=10 type=text name=nrrejestracyjny onkeypress='return handleEnter(this, event);'>";
		_td();
	_tr();		
	tr_();
		td("150;r;Użytkowany od ");
		td_(";;");
			$dddd = Date("Y-m-d");
			echo "<input class=wymagane size=10 maxlength=10 type=text id=uzytkowany_od name=uzytkowany_od value='$dddd' onkeypress=\"return handleEnter(this, event);\" onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\" >";
			echo "<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif width=16 height=16 border=0></a>";
			if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('uzytkowany_od').value='".Date('Y-m-d')."'; return false;\">";
		_td();
	_tr();
/*	tr_();
		td("150;r;Użytkowany do ");
		td_(";;");
			echo "<input class=wymagane size=10 maxlength=10 type=text name=uzytkowany_do value='' onkeypress=\"return handleEnter(this, event);\" onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\" >";
			echo "<a tabindex=-1 href=javascript:cal2.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif width=16 height=16 border=0></a>";
		_td();
	_tr();
*/
	echo "<input type=hidden name=userid value='$_GET[userid]'>";
	
tbl_empty_row();	
endtable();
startbuttonsarea("right");
addbuttons("zapisz","anuluj");
endbuttonsarea();
_form();
?>
<script language="JavaScript">
var cal1 = new calendar1(document.forms['add'].elements['uzytkowany_od']);
	cal1.year_scroll = true;
	cal1.time_comp = false;
//var cal2 = new calendar1(document.forms['add'].elements['uzytkowany_do']);
//	cal2.year_scroll = true;
//	cal2.time_comp = false;
</script>

<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("add");
  frmvalidator.addValidation("uzytkowany_od","req","Nie podano daty");
//  frmvalidator.addValidation("uzytkowany_do","req","Nie podano daty");
  frmvalidator.addValidation("pojemnosc","req","Nie podano pojemności silnika");
  frmvalidator.addValidation("nrrejestracyjny","req","Nie podano numeru rejestracyjnego");
</script>
<?php } ?>
</body>
</html>