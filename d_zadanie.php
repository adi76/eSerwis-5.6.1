<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php
if ($submit) { 
	$_POST=sanitize($_POST);
	$dddd = date("Y-m-d H:i:s");
	
	if ($_REQUEST[dlaHD]=='on') {
		$_zad_kat 		= $_POST[dz_kat];
		$_zad_podkat 	= $_POST[dz_podkat];
		$_zad_wc		= $_POST[dz_wc];
		$_zad_osoba		= $_POST[dz_osoba];
		$_zad_podkat2	= $_POST[sub_podkat_id];
	} else {
		$_zad_kat 		= 0;
		$_zad_podkat 	= 0;
		$_zad_podkat2 	= 0;
		$_zad_wc		= '';
		$_zad_osoba		= '';
	}
	
	$sql_t = "INSERT INTO $dbname.serwis_zadania values ('', '$_POST[dzopis]','$_POST[dztermin]','','',-1,'$dddd','$currentuser',$_POST[dzpriorytet],'".nl2br($_POST[dzuwagi])."','$_zad_kat','$_zad_podkat','$_zad_podkat2','$_zad_wc','$_zad_osoba',$es_filia)";
	
	if (mysql_query($sql_t, $conn)) { 
		$lastid = mysql_insert_id();
		?>
		<script>
		if (opener) opener.location.reload(true);
		</script>
		<?php if ($_POST[wybierz]>0) {
			$pocz=0;
			
			$sql88="SELECT COUNT(pozycja_id) FROM $dbname.serwis_zadania_pozycje WHERE (pozycja_zadanie_id=$_POST[wybierz])";
			$result88 = mysql_query($sql88, $conn) or die($k_b);
			list($ile)=mysql_fetch_array($result88);	
			
			//$ile=$_POST[zpilosc];
			$zid=$_POST[wybierz];
			
			$sql = "SELECT pozycja_komorka FROM $dbname.serwis_zadania_pozycje WHERE (pozycja_zadanie_id=$_POST[wybierz])";
			$result1 = mysql_query($sql, $conn) or die($k_b);
			
			$count_dodania=0;
			while ($newArray = mysql_fetch_array($result1)) {
				$nazwa  				= $newArray['pozycja_komorka'];
				
				$sql_t = "INSERT INTO $dbname.serwis_zadania_pozycje values ('', $lastid,'$nazwa','','',0,'','','',0,'',$es_filia)";
				$result = mysql_query($sql_t, $conn) or die($k_b);
				$count_dodania++;
			}
			
			okheader("Pomyślnie przypisano UP / Komórki do zadania (ilość dodanych pozycji : ".$count_dodania.")");
			startbuttonsarea("right");
			addbuttons("zamknij");
			endbuttonsarea();
		
		} else { ?>
			<script>
			if (confirm("Czy chcesz przejść do dodawania komórek do zadania ?")) {
				window.location.href='d_zadanie_pozycje.php?id=<?php echo $lastid;?>';
			} else self.close();
			</script>
		<?php } ?>
		
		<?php
	} else {
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php		
	}
} else { 
if ($_REQUEST[from]=='closed') {
	pageheader("Dodawanie nowego zadania na bazie innego zadania");
} else {
	pageheader("Dodawanie nowego zadania");
}
	starttable();
	echo "<form name=addz action=$PHP_SELF method=POST onSubmit=\"return ZadanieDodaj('Czy napewno chcesz dodać zadanie ?'); \">";	
	
	if ($_REQUEST[from]=='closed') {
		tbl_empty_row();

		tr_();
			td("150;r;Wybierz istniejące zadanie");
			td_(";;");
			
				$result6 = mysql_query("SELECT zadanie_id, zadanie_opis, zadanie_status FROM $dbname.serwis_zadania WHERE ((belongs_to=$es_filia) and (zadanie_hd_podkat_poziom_2<>'')) ORDER BY zadanie_id DESC", $conn_hd) or die($k_b);
				echo "<select name=tzgloszenie onkeypress='return handleEnter(this, event);' onChange=\"self.location.href=this.value;\">\n"; 					 				
				echo "<option value=''>Wybierz z listy...</option>";
				$ilosc_zgl = 0;
				while (list($_z_id, $_z_o, $_z_s) = mysql_fetch_array($result6)) {
			
						echo "<option value='d_zadanie.php?from=closed&wybierz=".$_z_id."' ";
						if ($_REQUEST[wybierz]==$_z_id) echo " SELECTED ";
						echo ">";
						if (strlen($_z_o)>100) { substr($_z_o,0,100)."..."; } else echo "$_z_o";
						
						if ($_z_s==0) echo " [nowe]";
						if ($_z_s==1) echo " [w trakcie]";
						if ($_z_s==9) echo " [zakończone]";
						
						echo "</option>\n";
		
				}
				echo "</select>\n";
			_td();
		_tr();

		tr_();
			echo "<td colspan=2><hr /></td>";
		_tr();
	
	}
	
	if ($_REQUEST[wybierz]!='') {
		$result66 = mysql_query("SELECT zadanie_opis, zadanie_uwagi, zadanie_priorytet, zadanie_hd_kat, zadanie_hd_podkat, zadanie_hd_podkat_poziom_2, zadanie_hd_wc, zadanie_hd_osoba, zadanie_termin_zakonczenia FROM $dbname.serwis_zadania WHERE (belongs_to=$es_filia) and (zadanie_id=$_REQUEST[wybierz]) LIMIT 1", $conn_hd) or die($k_b);
		list($load_opis, $load_uwagi, $load_prior, $load_hd_kat, $load_hd_podkat, $load_hd_podkat2, $load_hd_wc, $load_hd_osoba, $load_tz) = mysql_fetch_array($result66);
	} else { 
		$load_opis='';
		$load_uwagi='';
		$load_prior='';
		$load_hd_kat='';
		$load_hd_podkat='';
		$load_hd_podkat2='';
		$load_hd_wc='';
		$load_hd_osoba='';
		$load_tz='';
	}
	
	tbl_empty_row();
	tr_();
		td("150;r;Opis zadania");
		td_(";;");
			echo "<input class=wymagane size=80 maxlength=255 type=text id=dzopis name=dzopis onkeypress='return handleEnter(this, event);' value='$load_opis' />";		
		_td();
	_tr();
	tr_();
		td("150;r;Termin zakończenia zadania");	
		td_(";;");
			echo "<input size=10 maxlength=10 type=text id=dztermin name=dztermin maxlength=10 onkeypress='return handleEnter(this, event);' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\" value=\"".substr($load_tz,0,10)."\">"; 
			echo "<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif width=16 height=16 border=0></a>";
			if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('dztermin').value='".Date('Y-m-d')."'; return false;\">";
		_td();
	_tr();
	tr_();
		td("150;rt;Uwagi");
		td_(";;");
			echo "<textarea name=dzuwagi cols=65 rows=3>$load_uwagi</textarea>";
		_td();
	_tr();
	tr_();
		td("150;r;Priorytet");
		td_(";;");
			echo "<select name=dzpriorytet onkeypress='return handleEnter(this, event);'>\n"; 			
			echo "<option value=0 "; if ($load_prior==0) echo " SELECTED "; echo ">Niski</option>\n";
			echo "<option value=1 "; if (($load_prior==1) || ($_REQUEST[wybierz]=='')) echo " SELECTED "; echo ">Normalny</option>\n"; 
			echo "<option value=2 "; if ($load_prior==2) echo " SELECTED "; echo " >Wysoki</option>\n";
			echo "</select>\n";
		_td();
	_tr();
	tbl_empty_row();
	tr_();
		echo "<td colspan=2 class=left>";

			echo "<a tabindex=-1 class=normalfont href=# onClick=\"if (document.getElementById('dlaHD').checked) { document.getElementById('dlaHD').checked=false; document.getElementById('InfoDlaHD1').style.display='none'; document.getElementById('InfoDlaHD2').style.display='none';document.getElementById('InfoDlaHD3').style.display='none';document.getElementById('InfoDlaHD4').style.display='none';document.getElementById('InfoDlaHD5').style.display='none'; return false; } else { document.getElementById('dlaHD').checked=true; document.getElementById('InfoDlaHD1').style.display=''; document.getElementById('InfoDlaHD2').style.display='';document.getElementById('InfoDlaHD3').style.display='';document.getElementById('InfoDlaHD4').style.display='';document.getElementById('InfoDlaHD5').style.display=''; document.getElementById('InfoDlaHD6').style.display='';document.getElementById('tr_pk_hint').style.display='';return false; }\"><font color=black>&nbsp;<b>Możliwość tworzenia zgłoszeń z zadania</b></font></a>";
			
			echo "<input class=border0 type=checkbox checked name=dlaHD id=dlaHD checked=checked onChange=\"if (document.getElementById('dlaHD').checked) { document.getElementById('InfoDlaHD1').style.display=''; document.getElementById('InfoDlaHD2').style.display='';document.getElementById('InfoDlaHD3').style.display='';document.getElementById('InfoDlaHD4').style.display='';document.getElementById('InfoDlaHD5').style.display='';} else { document.getElementById('InfoDlaHD1').style.display='none'; document.getElementById('InfoDlaHD2').style.display='none';document.getElementById('InfoDlaHD3').style.display='none';document.getElementById('InfoDlaHD4').style.display='none';document.getElementById('InfoDlaHD5').style.display='none'; document.getElementById('InfoDlaHD6').style.display='none'; document.getElementById('tr_pk_hint').style.display='none';return false;}\">";

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
				echo "<option value='3' "; if ($load_hd_kat==3) echo " SELECTED "; echo ">Prace zlecone w ramach umowy</option>\n";						
				echo "<option value='7' "; if ($load_hd_kat==7) echo " SELECTED "; echo ">Konserwacja</option>\n";				
				echo "<option value='4' "; if ($load_hd_kat==4) echo " SELECTED "; echo ">Prace zlecone poza umową</option>\n";
				echo "<option value='5' "; if ($load_hd_kat==5) echo " SELECTED "; echo ">Prace na potrzeby Postdata</option>\n";
			echo "</select>\n";		
			//echo "<b>Prace zlecone w ramach umowy</b>";
			//echo "<input type=hidden name=dz_kat id=dz_kat value=3>";
		_td();
	_tr();	
	echo "<tr id=InfoDlaHD3>";
		td("150;r;Podkategoria");
		td_(";;");
			
			$r2 = mysql_query("SELECT hd_podkategoria_nr, hd_podkategoria_opis FROM $dbname_hd.hd_podkategoria WHERE (hd_podkategoria_wlaczona=1) and (hd_podkategoria_nr<>1) AND (hd_podkategoria_order>0) ORDER BY hd_podkategoria_order ASC", $conn_hd) or die($k_b);
			
			echo "<select class=wymagane name=dz_podkat id=dz_podkat onkeypress=\"return handleEnter(this, event); \" onChange=\"GenerateSubPodkategoriaList(3,document.getElementById('dz_podkat').value); ShowHints(document.getElementById('dz_kat').value,this.value); if (this.value=='G') { FillProject($es_filia); } \" onBlur=\"if (this.value=='G') FillProjectInfo(document.getElementById('sub_podkat_id').value);\">\n";
			
			while (list($podkat_nr, $podkat_opis)=mysql_fetch_array($r2)) {
				echo "<option value='$podkat_nr'";
				if (($podkat_nr==2) && ($load_hd_kat==0)) echo " SELECTED ";
				if ($podkat_nr==$load_hd_podkat) echo " SELECTED ";
				echo ">$podkat_opis</option>\n";
			}
			
			echo "</select>\n";
			
			$result_aaaa = mysql_query("SELECT * FROM $dbname_hd.hd_projekty WHERE (projekt_dla_filii LIKE '%|$es_filia|%') and (projekt_active=1) and (projekt_status=1)", $conn) or die($k_b);
			$count_rows111 = mysql_num_rows($result_aaaa);
			echo "<select name=dz_podkat_temp id=dz_podkat_temp style='display:none;' >\n";
			if ($count_rows111>0) {
				echo "<option value=''>Wybierz projekt z listy</option>";	
				while ($newArray9999 = mysql_fetch_array($result_aaaa)) {
					$temp_opis  	= $newArray9999[projekt_opis];
					$temp_kto  		= $newArray9999[projekt_autor];
					$temp_kiedy  	= $newArray9999[projekt_data_utworzenia];

					echo "<option value='$temp_opis'>$temp_opis</option>\n";
				}
			}
			echo "</select>\n";
		_td();
	_tr();	
	echo "<tr style='display:none' id=tr_pk_hint>";
		td("150;rt;");
		td_(";;;");	
			echo "<div title='Kliknij, aby ukryć podpowiedzi' id=Hint style='border:1px solid #67ADE7; background-color:#B7D8F3; display:none; padding:5px;' onClick=\"this.style.display='none'; document.getElementById('tr_pk_hint').style.display='none';\" ></div>";
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
			echo "<textarea name=dz_wc id=dz_wc cols=65 rows=3 class=wymagane>$load_hd_wc</textarea>";
		_td();
	_tr();
	echo "<tr id=InfoDlaHD5>";
		td("150;rt;Osoba zgłaszająca");
		td_(";;");
			echo "<input type=text id=dz_osoba name=dz_osoba class=wymagane value='$load_hd_osoba' size=38 maxlength=30 onBlur=\"cUpper(this);\" />";
		_td();
	_tr();

echo "<input type=hidden name=wybierz value='$_REQUEST[wybierz]'>";

tbl_empty_row();
endtable();
startbuttonsarea("right");
addbuttons("zapisz","anuluj");
endbuttonsarea();
_form();

?>

<script language="JavaScript">

ShowHints(document.getElementById('dz_kat').value,document.getElementById('dz_podkat').value);

var cal1 = new calendar1(document.forms['addz'].elements['dztermin']);
	cal1.year_scroll = true;
	cal1.time_comp = false;
</script>
	
<?php if ($load_hd_kat>0) { ?>
	<script>
		MakePodkategoriaList(<?php echo $load_hd_kat; ?>);
		document.getElementById('dz_podkat').value='<?php echo $load_hd_podkat; ?>';
		ShowHints(<?php echo $load_hd_kat; ?>,<?php echo $load_hd_podkat; ?>); 
		document.getElementById('sub_podkat_id').value='<?php echo $load_hd_podkat2; ?>';
	</script>
	<?php if ($load_hd_podkat=='G') { ?>
		<script>
			FillProject(<?php echo $es_filia; ?>);
		</script>
	<?php } ?>
<?php } else { ?>
	<script>
		GenerateSubPodkategoriaList(3,2);
	</script>
<?php } ?>
	
<?php } ?>
</body>
</html>