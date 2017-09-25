<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body OnLoad="document.forms[0].elements[0].focus(); ">
<?php
if ($submit) { 
	$_POST=sanitize($_POST);
	$dddd = date("Y-m-d H:i:s");
	
	if ($_REQUEST[dlaHD]=='on') {
		$_zad_kat 		= $_POST[dz_kat];
		$_zad_podkat 	= $_POST[dz_podkat];
		$_zad_wc		= $_POST[dz_wc];
		$_zad_osoba		= $_POST[dz_osoba];
		$_zad_podkat2 	= $_POST[sub_podkat_id];
	} else {
		$_zad_kat 		= 0;
		$_zad_podkat 	= 0;
		$_zad_podkat2	= 0;
		$_zad_wc		= '';
		$_zad_osoba		= '';
	}
	
	$sql_t = "UPDATE $dbname.serwis_zadania SET zadanie_opis='$_POST[dzopis]', zadanie_termin_zakonczenia='$_POST[dztermin]', zadanie_uwagi = '".nl2br($_POST[dzuwagi])."', zadanie_priorytet='$_POST[dzpriorytet]', zadanie_hd_kat='$_zad_kat', zadanie_hd_podkat='$_zad_podkat', zadanie_hd_podkat_poziom_2='$_zad_podkat2', zadanie_hd_wc='$_zad_wc',zadanie_hd_osoba='$_zad_osoba' WHERE (zadanie_id=$_POST[zid]) LIMIT 1";
	if (mysql_query($sql_t, $conn)) { 
		?><script>if (opener) opener.location.reload(true);	self.close(); </script><?php
	} else {
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php		
	}
} else {
$sql="SELECT * FROM $dbname.serwis_zadania WHERE (zadanie_id=$id)";
$result = mysql_query($sql, $conn) or die($k_b);
while ($newArray = mysql_fetch_array($result)) {
	$temp_id  			= $newArray['zadanie_id'];
	$temp_opis			= $newArray['zadanie_opis'];
	$temp_termin		= $newArray['zadanie_termin_zakonczenia'];
	$temp_uwagi			= $newArray['zadanie_uwagi'];
	$temp_priorytet		= $newArray['zadanie_priorytet'];
	$temp_kat			= $newArray['zadanie_hd_kat'];
	$temp_podkat		= $newArray['zadanie_hd_podkat'];
	$temp_podkat2		= $newArray['zadanie_hd_podkat_poziom_2'];
	$temp_wc			= $newArray['zadanie_hd_wc'];
	$temp_osoba			= $newArray['zadanie_hd_osoba'];
}
if ($temp_podkat2=='') $temp_podkat2 = 'Brak';

pageheader("Edycja zadania do wykonania");
starttable();
echo "<form name=addz action=$PHP_SELF method=POST onSubmit=\"return ZadanieEdytuj('Czy napewno chcesz zapisać zmiany w zadaniu ?'); \">";
tbl_empty_row();
	tr_();
		td("150;r;Opis zadania");
		td_(";;");
			echo "<input class=wymagane size=60 maxlength=255 type=text value='$temp_opis' id=dzopis name=dzopis onkeypress='return handleEnter(this, event);'>";
		_td();
	_tr();
	tr_();
		td("150;r;Termin zakończenia zadania");
		td_(";;");
			if ($temp_termin=='0000-00-00 00:00:00') $temp_termin='';
			echo "<input size=10 maxlength=10 type=text id=dztermin name=dztermin maxlength=10 value='".substr($temp_termin,0,10)."' onkeypress='return handleEnter(this, event);' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\">";
			echo "<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif width=16 height=16 border=0></a>";
			if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('dztermin').value='".Date('Y-m-d')."'; return false;\">";
		_td();
	_tr();
	tr_();
		td("150;rt;Uwagi");
		td_(";;");
			echo "<textarea name=dzuwagi cols=45 rows=3>".br2nl($temp_uwagi)."</textarea>";
		_td();
	_tr();
	tr_();
		td("150;r;Priorytet");
		td_(";;");
			echo "<select name=dzpriorytet onkeypress='return handleEnter(this, event);'>\n"; 			
			echo "<option "; if ($temp_priorytet==0) echo "SELECTED "; echo "value=0>Niski</option>\n";
			echo "<option "; if ($temp_priorytet==1) echo "SELECTED "; echo "value=1>Normalny</option>\n"; 
			echo "<option "; if ($temp_priorytet==2) echo "SELECTED "; echo "value=2>Wysoki</option>\n";
			echo "</select>\n";
		_td();
	_tr();
	
	tbl_empty_row();
	tr_();
		echo "<td colspan=2 class=left>";

			echo "<a tabindex=-1 class=normalfont href=# onClick=\"if (document.getElementById('dlaHD').checked) { document.getElementById('dlaHD').checked=false; document.getElementById('InfoDlaHD1').style.display='none'; document.getElementById('InfoDlaHD2').style.display='none';document.getElementById('InfoDlaHD3').style.display='none';document.getElementById('InfoDlaHD4').style.display='none';document.getElementById('InfoDlaHD5').style.display='none'; return false; } else { document.getElementById('dlaHD').checked=true; document.getElementById('InfoDlaHD1').style.display=''; document.getElementById('InfoDlaHD2').style.display='';document.getElementById('InfoDlaHD3').style.display='';document.getElementById('InfoDlaHD4').style.display='';document.getElementById('InfoDlaHD5').style.display=''; document.getElementById('InfoDlaHD6').style.display='';document.getElementById('tr_pk_hint').style.display=''; return false; }\"><font color=black>&nbsp;<b>Możliwość tworzenia zgłoszeń z zadania</b></font></a>";
			
			echo "<input class=border0 type=checkbox ";
			
			if ($temp_wc!='') echo "checked=checked";
			
			echo " name=dlaHD id=dlaHD onChange=\"if (document.getElementById('dlaHD').checked) { document.getElementById('InfoDlaHD1').style.display=''; document.getElementById('InfoDlaHD2').style.display='';document.getElementById('InfoDlaHD3').style.display='';document.getElementById('InfoDlaHD4').style.display='';document.getElementById('InfoDlaHD5').style.display='';} else { document.getElementById('InfoDlaHD1').style.display='none'; document.getElementById('InfoDlaHD2').style.display='none';document.getElementById('InfoDlaHD3').style.display='none';document.getElementById('InfoDlaHD4').style.display='none';document.getElementById('InfoDlaHD5').style.display='none'; document.getElementById('InfoDlaHD6').style.display='none';document.getElementById('tr_pk_hint').style.display='none'; return false;}\">";

			//echo "<input type=button onClick=\"alert('1'); document.getElementById('InfoDlaHD1').style.display='none';\" value='3dddd'>";
		echo "</td>";
	_tr();
	

	echo "<tr id=InfoDlaHD1>";
		echo "<td colspan=2>";
			echo "<hr />";
		_td();
	_tr();	
	
	echo "<tr id=InfoDlaHD2>";
		td("150;r;Kategoria");
		td_(";;");
			echo "<select class=wymagane id=dz_kat name=dz_kat onChange=\"ShowHints(document.getElementById('dz_kat').value,document.getElementById('dz_podkat').value); MakePodkategoriaList(this.options[this.options.selectedIndex].value); \" />\n";
				echo "<option value='3'"; if ($temp_kat==3) echo " SELECTED "; echo ">Prace zlecone w ramach umowy</option>\n";						
				echo "<option value='7'"; if ($temp_kat==7) echo " SELECTED "; echo ">Konserwacja</option>\n";				
				echo "<option value='4'"; if ($temp_kat==4) echo " SELECTED "; echo ">Prace zlecone poza umową</option>\n";
				echo "<option value='5'"; if ($temp_kat==5) echo " SELECTED "; echo ">Prace na potrzeby Postdata</option>\n";
			echo "</select>\n";	
			
			//echo "<b>Prace zlecone w ramach umowy</b>";
			//echo "<input type=hidden name=dz_kat id=dz_kat value=3>";
		_td();
	_tr();	
	echo "<tr id=InfoDlaHD3>";
		td("150;r;Podkategoria");
		td_(";;");
		
			//$r2 = mysql_query("SELECT hd_podkategoria_nr, hd_podkategoria_opis FROM $dbname_hd.hd_podkategoria WHERE (hd_podkategoria_wlaczona=1) and (hd_podkategoria_nr<>1) ORDER BY hd_podkategoria_opis", $conn_hd) or die($k_b);
			$r2 = mysql_query("SELECT hd_podkategoria_nr, hd_podkategoria_opis FROM $dbname_hd.hd_podkategoria WHERE (hd_podkategoria_wlaczona=1) and (hd_podkategoria_nr<>1) AND (hd_podkategoria_order>0) ORDER BY hd_podkategoria_order ASC", $conn_hd) or die($k_b);
			
			echo "<select class=wymagane name=dz_podkat id=dz_podkat onkeypress='return handleEnter(this, event);' onChange=\"GenerateSubPodkategoriaList(3,document.getElementById('dz_podkat').value); ShowHints(document.getElementById('dz_kat').value,this.value); if (this.value=='G') { FillProject($es_filia); }\" onBlur=\"if (this.value=='G') FillProjectInfo(document.getElementById('sub_podkat_id').value);\" />\n";
			
			echo "</select>\n";
		_td();
	_tr();
	echo "<tr style='display:none;' id=tr_pk_hint>";
		td("150;rt;");
		td_(";;;");	
			if ($temp_wc!='') echo "<div title='Kliknij, aby ukryć podpowiedzi' id=Hint style='border:1px solid #67ADE7; background-color:#B7D8F3; display:none; padding:5px;' onClick=\"this.style.display='none'; document.getElementById('tr_pk_hint').style.display='none';\" ></div>";
		_td();
	_tr();
	echo "<tr style='display:none' id=InfoDlaHD6>";
		td("140;rt;Podkategoria (poziom 2)");
		td_(";;;");	
			echo "<select class=wymagane id=sub_podkat_id name=sub_podkat_id onChange=\"if (document.getElementById('dz_podkat').value=='G') { FillProjectInfo(this.value); }\"/>\n";
			echo "<option value=''></option>\n";
			echo "</select>\n";
			echo "<div id=sub_podkat_id_opis></div>";
		_td();
	_tr();
	echo "<tr id=InfoDlaHD4>";
		td("150;rt;Wykonane czynności");
		td_(";;");
			echo "<textarea id=dz_wc name=dz_wc cols=65 rows=3 class=wymagane>$temp_wc</textarea>";
		_td();
	_tr();
	echo "<tr id=InfoDlaHD5>";
		td("150;rt;Osoba zgłaszająca");
		td_(";;");
			echo "<input type=text id=dz_osoba name=dz_osoba class=wymagane value='$temp_osoba' size=38 maxlength=30 onBlur=\"cUpper(this);\" />";
		_td();
	_tr();
	
tbl_empty_row();
endtable();
echo "<input type=hidden name=zid value=$temp_id>";	
startbuttonsarea("right");
echo "<input class=buttons type=submit name=submit id=submit value='Zapisz'>";
addbuttons("anuluj");
endbuttonsarea();
_form();//echo "<input type=button value='1' onClick=\"document.getElementById('sub_podkat_id').value = '$temp_podkat2';\" />";
?>
<script language="JavaScript">
ShowHints(document.getElementById('dz_kat').value,document.getElementById('dz_podkat').value);
MakePodkategoriaList(document.getElementById('dz_kat').value);

document.getElementById('dz_podkat').value = '<?php echo $temp_podkat; ?>';
GenerateSubPodkategoriaList(document.getElementById('dz_kat').value,document.getElementById('dz_podkat').value);

<?php if ($temp_podkat=='G') { ?>
	$("#sub_podkat_id").load("projekty.php?id999=<?php echo $es_filia; ?>");
	alert('Pomyślnie wczytano informacje o projekcie');
<?php } ?>
document.getElementById('sub_podkat_id').value = '<?php echo $temp_podkat2; ?>';

var cal1 = new calendar1(document.forms['addz'].elements['dztermin']);
	cal1.year_scroll = true;
	cal1.time_comp = false;
</script>

<?php if ($temp_wc=='') { ?>
<script>
document.getElementById('InfoDlaHD1').style.display='none'; document.getElementById('InfoDlaHD2').style.display='none';document.getElementById('InfoDlaHD3').style.display='none';document.getElementById('InfoDlaHD4').style.display='none';document.getElementById('InfoDlaHD5').style.display='none';

</script>
<?php } ?>

<?php } ?>
</body>
</html>