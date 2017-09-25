<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body OnLoad="document.forms[0].elements[1].focus(); document.getElementById('hd1').style.display='none'; document.getElementById('hd2').style.display='none'; document.getElementById('hd2a').style.display='none'; document.getElementById('hd3').style.display='none'; document.getElementById('hd4').style.display='none';">
<?php 

if ($submit99) { 

if ($_SESSION['session_pozycja_zadania_'.$_REQUEST[pid].'']=='nie') {
	$_POST=sanitize($_POST);
	$dddd = Date('Y-m-d H:i:s');
	$dddd = $_POST[ddate];
	$newuwagi = '';
	if ($_POST[olduwagi]!='') {
		$newuwagi = $_POST[olduwagi]."<br /><br />Uwaga dodana przy zakończeniu zadania $dddd przez $currentuser:<br />".$_POST[duwagi];
	} else 
		if ($_POST[duwagi]!='') { 
			$newuwagi="Uwaga dodana przy zakończeniu zadania $dddd przez $currentuser:<br />".$_POST[duwagi];
			}
	$sql_d1="UPDATE $dbname.serwis_zadania_pozycje SET pozycja_data_modyfikacji='$dddd', pozycja_modyfikowane_przez ='$currentuser', pozycja_status=9, pozycja_uwagi = '".nl2br($newuwagi)."' WHERE (pozycja_id = '$_POST[pid]') LIMIT 1";
	if (mysql_query($sql_d1, $conn)) { 
		?>
		<?php if (($_REQUEST[UtworzZgloszenieHD]=='on') && ($_REQUEST[enablehd]==1)) { ?>
			<script>
				newWindow_r(800,600,'hd_d_zgloszenie.php?stage=1&fromtask=1&komorka=<?php echo urlencode($_REQUEST[komorka]); ?>&zadanie=<?php echo urlencode($_REQUEST[z_hd_wc]); ?>&osoba=<?php echo urlencode($_REQUEST[z_hd_o]); ?>&kat_nr=<?php echo urlencode($_REQUEST[kat_nr]); ?>&kat_opis=<?php echo urlencode($_REQUEST[kat_opis]); ?>&podkat_nr=<?php echo urlencode($_REQUEST[podkat_nr]); ?>&podkat_opis=<?php echo urlencode($_REQUEST[podkat_opis]); ?>&zadanieid=<?php echo $_REQUEST[pid]; ?>&zid=<?php echo $_REQUEST[zid]; ?>&podkat2_opis=<?php echo urlencode($_REQUEST[podkat2_opis]); ?>');				
			</script>
		<?php } else { ?>
			<script>
				if (opener) opener.location.reload(true); self.close(); 
			</script>
		<?php } ?>
	<?php 
		$_SESSION['session_pozycja_zadania_'.$_REQUEST[pid].'']='tak';
		
	} else {
		?><script>info('Wystąpił błąd podczas operacji usuwania'); self.close(); </script><?php
	}
	
	infoheader('Pomyślnie zamknięto pozycję zadania');
	echo "<br />";
	
	echo "<p align=center id=nap>Otwarto okno z nowym zgłoszeniem dla pozycji zadania</p><br />";
		
	echo "<p align=center>";
	
	if ($_REQUEST[UtworzZgloszenieHD]=='on') {
		echo "<input id=ref1 type=button class=buttons style='color:blue' value='Wygeneruj ponownie okno z nowym zgłoszeniem helpdesk' onClick=\"newWindow_r(800,600,'hd_d_zgloszenie.php?stage=1&fromtask=1&komorka=".urlencode($_REQUEST[komorka])."&zadanie=".urlencode($_REQUEST[z_hd_wc])."&osoba=".urlencode($_REQUEST[z_hd_o])."&podkat_nr=".urlencode($_REQUEST[podkat_nr])."&podkat_opis=".urlencode($_REQUEST[podkat_opis])."&zadanieid=".$_REQUEST[pid]."&zid=".$_REQUEST[zid]."'); return false; \" />";
	}
	
	echo "<br /><input id=ref type=button class=buttons style='height:60px' value='Potwierdzam utworzenie zgłoszenia do pozycji zadania' onClick=\"if (opener) opener.location.reload(true); self.close();\" />";
	echo "</p>";
	
} else {


	echo "<p align=center>";
	echo "<input id=ref type=button class=buttons style='height:60px' value='Potwierdzam utworzenie zgłoszenia do pozycji zadania' onClick=\"if (opener) opener.location.reload(true); self.close();\" />";
	echo "</p>";
}

} else { ?>
<?php

if (!$_GET[id]>0) {
	echo "<br /><p align=center>";
	echo "<input id=ref type=button class=buttons style='height:60px' value='Potwierdzam utworzenie zgłoszenia do pozycji zadania' onClick=\"if (opener) opener.location.reload(true); self.close();\" />";
	echo "</p>";
	exit;
}

session_register("session_pozycja_zadania_".$_REQUEST[id]."");
$_SESSION['session_pozycja_zadania_'.$_REQUEST[id].'']='nie';

okheader("Czy potwierdzasz wykonanie zadania w");
infoheader("<b>".urldecode($komorka)." ?</b>");
$query1=mysql_query("SELECT pozycja_uwagi FROM $dbname.serwis_zadania_pozycje WHERE pozycja_id=$id LIMIT 1",$conn) or die($k_b);
list($muwagi)=mysql_fetch_array($query1);
if ($muwagi!='') {
	startbuttonsarea("center");
	echo "<a title=' Czytaj uwagi '>";
	echo "Do tej pozycji istnieją uwagi";
	echo "<input class=imgoption type=image src=img/comment.gif onclick=\"newWindow(480,265,'p_zadania_pozycje_uwagi.php?id=$id')\"></a>";
	endbuttonsarea();
}
?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
startbuttonsarea("center");
echo "<form name=ez action=$PHP_SELF method=POST>";

starttable();
tbl_empty_row();
	echo "<tr>";
	echo "<td class=right width=120>Data wykonania</td>";
	echo "<td>";
	echo "<input size=19 maxlength=19 class=wymagane type=text id=ddate name=ddate value='".Date('Y-m-d H:i:s')."' onkeypress='return handleEnter(this, event);' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\">";
	echo "<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif align=abstop width=16 height=16 border=0></a>";
	if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('ddate').value='".Date('Y-m-d')."'; return false;\">";	
	echo "</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td class=righttop>Dodaj uwagi</td>";
	echo "<td><textarea name=duwagi cols=50 rows=5></textarea></td>";
	echo "</tr>";
	tbl_empty_row();
	
if ($_REQUEST[enablehd]==1) {

	echo "<tr>";
	echo "<td class=righttop></td>";
	echo "<td>";
	
	echo  "<input class=border0 type=checkbox name=UtworzZgloszenieHD id=UtworzZgloszenieHD onChange=\"if (document.getElementById('UtworzZgloszenieHD').checked) { document.getElementById('hd1').style.display=''; document.getElementById('hd2').style.display=''; document.getElementById('hd2a').style.display=''; document.getElementById('hd3').style.display=''; document.getElementById('hd4').style.display=''; }  else { document.getElementById('hd1').style.display='none'; document.getElementById('hd2').style.display='none'; document.getElementById('hd2a').style.display='none'; document.getElementById('hd3').style.display='none'; document.getElementById('hd4').style.display='none'; }\" />";
	
	echo "<a href=# class=normalfont onClick=\"if (document.getElementById('UtworzZgloszenieHD').checked) { document.getElementById('UtworzZgloszenieHD').checked=false; } else { document.getElementById('UtworzZgloszenieHD').checked=true; } if (document.getElementById('UtworzZgloszenieHD').checked) { document.getElementById('hd1').style.display=''; document.getElementById('hd2').style.display=''; document.getElementById('hd2a').style.display=''; document.getElementById('hd3').style.display=''; document.getElementById('hd4').style.display=''; }  else { document.getElementById('hd1').style.display='none'; document.getElementById('hd2').style.display='none'; document.getElementById('hd2a').style.display='none'; document.getElementById('hd3').style.display='none'; document.getElementById('hd4').style.display='none'; }\"><font color=red>Utwórz zgłoszenie w bazie Helpdesk z tego zadania</font></a>";
	
	echo "</td>";
	echo "</tr>";

	tbl_empty_row();
	
	echo "<tr id=hd1>";
		echo "<td class=right>Kategoria</td>";
		echo "<td>";
			echo "<b>$_REQUEST[zkatopis]</b>";
		echo "</td>";
	echo "</tr>";

	echo "<tr id=hd2>";
		echo "<td class=right>Podkategoria</td>";
		echo "<td>";
			$r2 = mysql_query("SELECT zadanie_hd_podkat,zadanie_hd_wc,zadanie_hd_osoba FROM $dbname.serwis_zadania WHERE (zadanie_id='$_REQUEST[zid]') LIMIT 1", $conn_hd) or die($k_b);
			list($_podkat, $_wc, $_osoba)=mysql_fetch_array($r2);
			
			$r2 = mysql_query("SELECT hd_podkategoria_opis FROM $dbname_hd.hd_podkategoria WHERE (hd_podkategoria_nr='$_podkat') LIMIT 1", $conn_hd) or die($k_b);
			list($_podkat_opis)=mysql_fetch_array($r2);
			
			echo "<b>$_REQUEST[zpodkatopis]</b>";
		echo "</td>";
	echo "</tr>";

	echo "<tr id=hd2a>";
		echo "<td class=right>Podkategoria (poziom 2)</td>";
		echo "<td>";
			if ($_REQUEST[zpodkat2]=='') $_REQUEST[zpodkat2]='Brak';
			echo "<b>$_REQUEST[zpodkat2]</b>";
		echo "</td>";
	echo "</tr>";
	
	echo "<tr id=hd3>";
		echo "<td class=righttop>Wykonane czynności</td>";
		echo "<td>";
			echo "<textarea name=z_hd_wc cols=50 rows=5>$_wc</textarea>";
		echo "</td>";
	echo "</tr>";
	echo "<tr id=hd4>";
		echo "<td class=righttop>Osoba zgłaszająca</td>";
		echo "<td>";
			echo "<input type=text maxlenth=30 size=38 name=z_hd_o value='$_osoba' onBlur=\"cUpper(this);\" />";
		echo "</td>";
	echo "</tr>";
	tbl_empty_row();
}

echo "<input type=hidden name=enablehd value='$_REQUEST[enablehd]'>";

endtable();
echo "<br />";
echo "<input class=buttons type=submit name=submit99 value=TAK>";
addbuttons("nie");
endbuttonsarea();	
echo "<input type=hidden name=olduwagi value='$muwagi'>";
echo "<input type=hidden name=pid value=$id>";
echo "<input type=hidden name=zid value=$_REQUEST[zid]>";

echo "<input type=hidden name=komorka value='$_REQUEST[komorka]'>";
echo "<input type=hidden name=zadanie value='$_REQUEST[zadanie]'>";

echo "<input type=hidden name=kat_nr value='$_REQUEST[zkatnr]'>";
echo "<input type=hidden name=kat_opis value='$_REQUEST[zkatopis]'>";

echo "<input type=hidden name=podkat_nr value='$_REQUEST[zpodkatnr]'>";
echo "<input type=hidden name=podkat_opis value='$_REQUEST[zpodkatopis]'>";

echo "<input type=hidden name=podkat2_opis value='$_REQUEST[zpodkat2]'>";

_form();
?>
<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("ez");
  frmvalidator.addValidation("ddate","req","Nie podano daty zakończenia zadania");
</script>

<script language="JavaScript">
var cal1 = new calendar1(document.forms['ez'].elements['ddate']);
	cal1.year_scroll = true;
	cal1.time_comp = true;
</script>

<?php } ?>
<script>HideWaitingMessage();</script>
</body>
</html>