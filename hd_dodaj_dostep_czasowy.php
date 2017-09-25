<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body>
<?php include('body_start.php');

if ($submit) {
	$_POST=sanitize($_POST);
	$dddd = Date("Y-m-d H:i:s");
	
	if ($_POST['wybrane_dni']){
		foreach ($_POST['wybrane_dni'] as $dzien){
			$sql_a = "INSERT INTO $dbname_hd.hd_dostep_czasowy VALUES ('','$dzien','$_POST[dc_osoba]','$_POST[data_graniczna]','1','$currentuser','$dddd',$es_filia)";
			if (mysql_query($sql_a, $conn_hd)) { 
				?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
			} else {
				?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
			}
		}
	}
} else {

	pageheader("Dodanie dostępu czasowego dla wybranych dni dla pracownika $_GET[user]",1,0);
	
	starttable();
	echo "<form name=add action=$PHP_SELF method=POST>";
	tbl_empty_row();
	tr_();
		td_("80;c;");
			echo "<fieldset><legend>&nbsp;Wybrany dzień&nbsp;</legend>";
			nowalinia();
			$dddd = Date("Y-m-d");
			echo "&nbsp;&nbsp;<input class=wymagane size=10 maxlength=10 type=text name=wybrany_dzien id=wybrany_dzien value='$dddd' onkeypress=\"return handleEnter(this, event);\" onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\">";
			echo "<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif width=16 height=16 border=0></a>";
			if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('wybrany_dzien').value='".Date('Y-m-d')."'; return false;\">";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type=button class=buttons value=' Dodaj do listy ' onClick=\"AddDateToList(document.getElementById('wybrany_dzien'),'".Date('Y-m-d')."'); UpdateDateToList(); \" >&nbsp;&nbsp;";
			
			echo "</fieldset>";
		_td();
		td_("380;;");	
			echo "<fieldset><legend>&nbsp;Wybrane dni&nbsp;</legend>";
			nowalinia();
			echo "&nbsp;&nbsp;<select name=wybrane_dni[] id=wybrane_dni multiple=multiple size=5>";
			echo "</select>";
			//alert(document.add.wybrane_dni.options.selectedIndex);
			echo "<br /><br />&nbsp;&nbsp;<input type=button class=buttons id=usun style='display:none' value=' Usuń wybraną pozycję z listy ' onClick=\"DeleteDateFromList(); \" >";
			echo "<input type=button class=buttons id=czysc style='display:none' value=' Czyść listę ' onClick=\"ClearDateToList();\" >";
			nowalinia();
			echo "</fieldset>";
		_td();

		td_("380;;");	
			echo "<fieldset><legend>&nbsp;Aktywne dostępy&nbsp;</legend>";
			nowalinia();
			echo "&nbsp;&nbsp;<select name=wybrane_dni_aktywne[] id=wybrane_dni_aktywne multiple=multiple size=7>";
			$sql="SELECT * FROM $dbname_hd.hd_dostep_czasowy WHERE (dc_dostep_dla_osoby ='$_GET[user]') and (dc_dostep_active=1) ";
			$result = mysql_query($sql, $conn_hd) or die($k_b);
			while ($newArray = mysql_fetch_array($result)) {
				$temp_dc_data = $newArray['dc_dostep_dla_daty'];
				echo "<option value='$temp_dc_data'>$temp_dc_data</option>\n";
			}
			echo "</select>";			
			nowalinia();
			echo "</fieldset>";
		_td();	
		
		
	_tr();
	tbl_empty_row();
	endtable();
	
	starttable("420px");
	tbl_empty_row();
	tr_();
		td_(";c;");	
			echo "<fieldset><legend>&nbsp;Dostęp do wybranych dni, możliwy będzie do&nbsp;</legend>";
			nowalinia();
			$rok = Date('Y');
			$miesiac = Date('m');
			$dzien = Date('d');		
			$g = Date('H');
			$m = Date('i');
			$s = Date('s');
			$datacala1  = mktime ($g+1,$m,$s,date("m")  ,date("d"),date("Y"));
			$datacala = date("Y-m-d H:i:s",$datacala1);
				
			echo "&nbsp;&nbsp;<input class=wymagane size=20 maxlength=19 type=text name=data_graniczna id=data_graniczna value='$datacala' onkeypress=\"return handleEnter(this, event);\" onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\" >";
			echo "<a tabindex=-1 href=javascript:cal2.popup(); title=' Kliknij, aby wybrać graniczną datę i godzinę'><img class=imgoption src=img/cal.gif width=16 height=16 border=0></a>";
			if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('data_graniczna').value='".Date('Y-m-d')."'; return false;\">";
			echo "<br /><br />";
			echo "</fieldset>";
		_td();
		td_("150;;");
			//	echo "<input type=button name=test class=buttons value='select all' onClick=\"selectAllList();\">";
			echo "<input type=submit name=submit id=zapisz class=buttons style='display:none' value='Udziel dostępu czasowego' onClick=\"selectAllList();\">&nbsp;&nbsp;";
		_td();
	_tr();
	tbl_empty_row();
	endtable();
	
	echo "<input type=hidden name=dc_osoba value='".$_GET[user]."'>";
	
	echo "</form>";
	
	startbuttonsarea("right");
	
	addbuttons("zamknij");
	endbuttonsarea();	 
}

?>
<script language="JavaScript">
var cal1 = new calendar1(document.forms['add'].elements['wybrany_dzien']);
	cal1.year_scroll = true;
	cal1.time_comp = false;
var cal2 = new calendar1(document.forms['add'].elements['data_graniczna']);
	cal2.year_scroll = true;
	cal2.time_comp = true;	
//var cal2 = new calendar1(document.forms['add'].elements['uzytkowany_do']);
//	cal2.year_scroll = true;
//	cal2.time_comp = false;
</script>
</body>
</html>