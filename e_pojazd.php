<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) { 
	$_POST=sanitize($_POST);

	$sql_a = "UPDATE $dbname_hd.hd_pojazdy SET hd_pojazd_od='$_POST[uzytkowany_od]', hd_pojazd_kategoria='$_POST[kategoria]', hd_pojazd_marka='$_POST[marka]', hd_pojazd_nr_rejestracyjny='$_POST[nrrejestracyjny]', hd_pojazd_pojemnosc = '$_POST[pojemnosc]' WHERE hd_pojazd_id='$_POST[id]' LIMIT 1";
	//echo "$sql_a";

	if (mysql_query($sql_a, $conn_hd)) 
		{ 
			?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else 
		{
		  ?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
		}
} else { 

	$result = mysql_query("SELECT hd_pojazd_od,hd_pojazd_marka,hd_pojazd_nr_rejestracyjny,hd_pojazd_pojemnosc,hd_pojazd_kategoria FROM $dbname_hd.hd_pojazdy WHERE (hd_pojazd_id=$_GET[id]) LIMIT 1", $conn) or die($k_b);
	list($temp_od,$temp_marka,$temp_nrrej,$temp_pojemnosc,$temp_kategoria)=mysql_fetch_array($result);
	
	pageheader("Edycja danych o pojeździe");
	starttable();
	echo "<form name=add action=$PHP_SELF method=POST>";	
	tbl_empty_row();
	tr_();
		td("150;r;Kategoria");
		td_(";;");
			echo "<select name=kategoria onkeypress='return handleEnter(this, event);' onChange=\"if (this.value!=4) { document.getElementById('marka').disabled=true; } else { document.getElementById('marka').disabled=false; } \">\n"; 
			echo "<option value='1'"; if ($temp_kategoria=='1') echo " SELECTED "; echo ">Motorower</option>\n"; 
			echo "<option value='2'"; if ($temp_kategoria=='2') echo " SELECTED "; echo ">Motocykl</option>\n"; 
			echo "<option value='3'"; if ($temp_kategoria=='3') echo " SELECTED "; echo ">Samochód (pojemność do 900cm)</option>\n"; 
			echo "<option value='4'"; if ($temp_kategoria=='4') echo " SELECTED "; echo ">Samochód (pojemność powyżej 900cm)</option>\n"; 
			echo "</select>\n"; 
			
		_td();
	_tr();	
	tr_();
		td("150;r;Marka pojazdu");
		td_(";;");
		?>
			<select id="marka" name="marka">
			<option value=""<?php if ($temp_marka=='') echo " SELECTED "; ?>></option>
			<option value="Alfa Romeo"<?php if ($temp_marka=='Alfa Romeo') echo " SELECTED "; ?>>Alfa Romeo</option>
			<option value="Audi"<?php if ($temp_marka=='Audi') echo " SELECTED "; ?>>Audi</option>
			<option value="BMW"<?php if ($temp_marka=='BMW') echo " SELECTED "; ?>>BMW</option>
			<option value="Chevrolet"<?php if ($temp_marka=='Chevrolet') echo " SELECTED "; ?>>Chevrolet</option>
			<option value="Citroën"<?php if ($temp_marka=='Citroën') echo " SELECTED "; ?>>Citroën</option>
			<option value="Daewoo"<?php if ($temp_marka=='Daewoo') echo " SELECTED "; ?>>Daewoo</option>
			<option value="Fiat"<?php if ($temp_marka=='Fiat') echo " SELECTED "; ?>>Fiat</option>
			<option value="Ford"<?php if ($temp_marka=='Ford') echo " SELECTED "; ?>>Ford</option>
			<option value="Honda"<?php if ($temp_marka=='Honda') echo " SELECTED "; ?>>Honda</option>
			<option value="Hyundai"<?php if ($temp_marka=='Hyundai') echo " SELECTED "; ?>>Hyundai</option>
			<option value="Kia"<?php if ($temp_marka=='Kia') echo " SELECTED "; ?>>Kia</option>
			<option value="Lexus"<?php if ($temp_marka=='Lexus') echo " SELECTED "; ?>>Lexus</option>
			<option value="Mazda"<?php if ($temp_marka=='Mazda') echo " SELECTED "; ?>>Mazda</option>
			<option value="Mercedes"<?php if ($temp_marka=='Mercedes') echo " SELECTED "; ?>>Mercedes</option>
			<option value="Mitsubishi"<?php if ($temp_marka=='Mitsubishi') echo " SELECTED "; ?>>Mitsubishi</option>
			<option value="Nissan"<?php if ($temp_marka=='Nissan') echo " SELECTED "; ?>>Nissan</option>
			<option value="Opel"<?php if ($temp_marka=='Opel') echo " SELECTED "; ?>>Opel</option>
			<option value="Peugeot"<?php if ($temp_marka=='Peugeot') echo " SELECTED "; ?>>Peugeot</option>
			<option value="Polonez"<?php if ($temp_marka=='Polonez') echo " SELECTED "; ?>>Polonez</option>
			<option value="Renault"<?php if ($temp_marka=='Renault') echo " SELECTED "; ?>>Renault</option>
			<option value="Rover"<?php if ($temp_marka=='Rover') echo " SELECTED "; ?>>Rover</option>
			<option value="Saab"<?php if ($temp_marka=='Saab') echo " SELECTED "; ?>>Saab</option>
			<option value="Seat"<?php if ($temp_marka=='Seat') echo " SELECTED "; ?>>Seat</option>
			<option value="Subaru"<?php if ($temp_marka=='Subaru') echo " SELECTED "; ?>>Subaru</option>
			<option value="Suzuki"<?php if ($temp_marka=='Suzuki') echo " SELECTED "; ?>>Suzuki</option>
			<option value="Škoda"<?php if ($temp_marka=='Škoda') echo " SELECTED "; ?>>Škoda</option>
			<option value="Toyota"<?php if ($temp_marka=='Toyota') echo " SELECTED "; ?>>Toyota</option>
			<option value="Trabant"<?php if ($temp_marka=='Trabant') echo " SELECTED "; ?>>Trabant</option>
			<option value="Volkswagen"<?php if ($temp_marka=='Volkswagen') echo " SELECTED "; ?>>Volkswagen</option>
			<option value="Volvo"<?php if ($temp_marka=='Volvo') echo " SELECTED "; ?>>Volvo</option>
			<option value="Inny"<?php if ($temp_marka=='Inny') echo " SELECTED "; ?>>Inny</option>
			</select>
		<?php
		_td();
	_tr();	
	tr_();
		td("150;r;Pojemność silnika");
		td_(";;");
			echo "<input class=wymagane size=3 maxlength=4 type=text name=pojemnosc onkeypress='return handleEnter(this, event);' value='$temp_pojemnosc' />";
		_td();
	_tr();
	tr_();
		td("150;r;Numer rejestracyjny");
		td_(";;");
			echo "<input class=wymagane size=10 maxlength=10 type=text name=nrrejestracyjny onkeypress='return handleEnter(this, event);'  value='$temp_nrrej' />";
		_td();
	_tr();		
	tr_();
		td("150;r;Użytkowany od ");
		td_(";;");
			$dddd = Date("Y-m-d");
			echo "<input class=wymagane size=10 maxlength=10 type=text id=uzytkowany_od name=uzytkowany_od value='$temp_od' onkeypress=\"return handleEnter(this, event);\" onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\" >";
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
	echo "<input type=hidden name=id value='$_GET[id]'>";
	
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