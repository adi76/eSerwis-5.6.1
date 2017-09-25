<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 
	include('cfg_helpdesk.php');
	include('body_start.php');

	$okres1=$_GET[okres];
	
	$sql="SELECT zgl_nr,zgl_data,zgl_godzina,zgl_komorka,zgl_temat, zgl_status, zgl_osoba_przypisana,zgl_szcz_przesuniecie_data,zgl_szcz_przesuniecie_osoba FROM $dbname_hd.hd_zgloszenie, $dbname_hd.hd_zgloszenie_szcz WHERE (hd_zgloszenie.belongs_to=$es_filia) and (hd_zgloszenie.zgl_widoczne=1) AND (hd_zgloszenie.zgl_id=hd_zgloszenie_szcz.zgl_szcz_zgl_id) and (hd_zgloszenie_szcz.zgl_szcz_przesuniety_termin_rozpoczecia=1) ";
	
	if ($_REQUEST[typ]=='day') $sql .= " and (SUBSTRING(hd_zgloszenie_szcz.zgl_szcz_czas_rozpoczecia_kroku,1,10)='".$_REQUEST[okres]."') ";
	if ($_REQUEST[typ]=='all') $sql .= " and (hd_zgloszenie.zgl_status<>9) ";
	
	$sql .= "ORDER BY zgl_data ASC";
	
	//echo $sql;
	
	$result = mysql_query($sql, $conn_hd) or die($k_b);
	$count_rows = mysql_num_rows($result);
	//echo $sql." <br />";
	
if ($count_rows!=0) {	

	if ($_GET[typ]=='all') {
		pageheader("Raport ze zgłoszeń niezamkniętych z przesuniętymi terminami rozpoczęcia",1,1);
	} 
	
	if ($_GET[typ]=='allwithclosed') {
		pageheader("Raport ze wszystkich zgłoszeń z przesuniętymi terminami rozpoczęcia",1,1);
		infoheader("Stan na dzień: $_GET[okres]");
	}

	if ($_GET[typ]=='day') {
		pageheader("Raport ze zgłoszeń z przesuniętymi terminami rozpoczęcia",1,1);
		infoheader("Stan na dzień: $_GET[okres]");
	}
	
	echo "<table class=maxwidth cellspacing=1 align=center>";
	echo "<tr>";
	echo "<th><center>Nr zgłoszenia</center></th>";
	echo "<th><center>Data rejestracji</center></th>";
	echo "<th>Placówka zgłaszająca</th>";
	
	echo "<th style='align:left'>Temat</th>";
	echo "<th><center>Ustalona data rozpoczęcia<br />Ustalenia z osobą</center></th>";
	echo "<th><center>Osoba przypisana</center></th>";
	echo "<th><center>Status</center></th>";	
	echo "<th><center>Opcje</center></th>";	
	echo "</tr>";
	
	$i = 0;
	$j = $page*$rowpersite-$rowpersite+1;
	
	while ($newArray = mysql_fetch_array($result)) {
		$temp_nr			= $newArray['zgl_nr'];
		$temp_komorka		= $newArray['zgl_komorka'];
		$temp_data			= $newArray['zgl_data'];
		$temp_godzina		= $newArray['zgl_godzina'];
		$temp_temat			= $newArray['zgl_temat'];
		$temp_status 		= $newArray['zgl_status'];
		$temp_czas			= $newArray['zgl_razem_czas'];
		$temp_op			= $newArray['zgl_osoba_przypisana'];
		$temp_poczta		= $newArray['zgl_poczta_nr'];
	
		$temp_szcz_przes_data = $newArray['zgl_szcz_przesuniecie_data'];
		$temp_szcz_przes_osoba = $newArray['zgl_szcz_przesuniecie_osoba'];
		
		if ($KolorujWgStatusow==1) {
			switch ($temp_kategoria) {
				case 6: $kolorgrupy='#F73B3B'; tbl_tr_color_dblClick($i, $kolorgrupy); break;
				case 2:	if ($temp_priorytet==2) { $kolorgrupy='#FF7F2A'; tbl_tr_color_dblClick($i, $kolorgrupy); break; 
							}
						if ($temp_priorytet==4) { $kolorgrupy='#F73B3B'; tbl_tr_color_dblClick($i, $kolorgrupy); break; 
							}				
				case 3:	if ($temp_priorytet==3) { $kolorgrupy='#FFAA7F'; tbl_tr_color_dblClick($i, $kolorgrupy); break; 
							}
				default: if ($temp_status==9) { $kolorgrupy='#FFFFFF'; tbl_tr_color_dblClick($i, $kolorgrupy); break; 
							} else {
							tbl_tr_highlight_dblClick($i);	
							$kolorgrupy='';
						}
			}
		} else {
			tbl_tr_highlight_dblClick($i);	
			$kolorgrupy='';
		}
		
		$j++;
		
		list($status)=mysql_fetch_array(mysql_query("SELECT hd_status_opis FROM $dbname_hd.hd_status WHERE hd_status_nr='$temp_status' LIMIT 1", $conn_hd));
		
		echo "<td class=center>$temp_nr</td>";
		echo "<td class=center>$temp_data ".substr($temp_godzina,0,5)."</td>";
		echo "<td>$temp_komorka</td>";
		echo "<td>"; 
		echo nl2br(wordwrap($temp_temat, 78, "<br />"));
		echo "</td>";
		echo "<td class=center>";
			$data_rej = $temp_data." ".$temp_godzina;
			if ($data_rej>$temp_szcz_przes_data) echo "<font color=red>";
			echo "<b>".substr($temp_szcz_przes_data,0,16)."</b><br />";
			echo "$temp_szcz_przes_osoba";
			if ($data_rej>$temp_szcz_przes_data) echo "</font>";
		echo "</td>";
		echo "<td class=center>$temp_op</td>";
		echo "<td class=center>";
			switch ($temp_status) {
				case "1"	: echo "<input title=' Status: $status ' class=imgoption type=image src=img/zgl_nowe.gif>"; break;
				case "2"	: echo "<input title=' Status: $status ' class=imgoption type=image src=img/zgl_przypisane.gif>"; break;
				case "3"	: echo "<input title=' Status: $status ' class=imgoption type=image src=img/zgl_rozpoczete.gif>"; break;
				case "3A"	: echo "<input title=' Status: $status ' class=imgoption type=image src=img/zgl_w_serwisie_zewnetrznym.gif>"; break;
				case "3B"	: echo "<input title=' Status: $status ' class=imgoption type=image src=img/zgl_w_firmie.gif>"; break;
				case "4"	: echo "<input title=' Status: $status ' class=imgoption type=image src=img/zgl_oczekiwanie_na_odpowiedz.gif>"; break;
				case "5"	: echo "<input title=' Status: $status ' class=imgoption type=image src=img/zgl_oczekiwanie_na_sprzet.gif>"; break;
				case "6"	: echo "<input title=' Status: $status ' class=imgoption type=image src=img/zgl_do_oddania.gif>"; break;
				case "7"	: echo "<input title=' Status: $status ' class=imgoption type=image src=img/zgl_rozpoczete_nie_zakonczone.gif>"; break;
				//case "8"	: echo "<input title=' Status: $status ' class=imgoption type=image src=img/zgl_nowe.gif>"; break;
				case "9"	: echo "<input title=' Status: $status ' class=imgoption type=image src=img/zgl_zamkniete.gif>"; break;
			}		
		echo "</td>";
		echo "<td class=center>";
			$zgl_ser = 0;
			if ($zgl_seryjne!='') $zgl_ser = 1; 
			
			if ($temp_status!=9) {
				echo "<a class=normalfont href=# title=' Przejdź do obsługi zgłoszenia nr $temp_nr '  onclick=\"newWindow_r(800,600,'hd_o_zgloszenia.php?action=obsluga&nr=$temp_nr&zgl_s=$zgl_ser');\">";
				echo "<input class=imgoption type=image src=img/hd_obsluga_start.gif>";
				echo "</a>";
			} else {
				echo "<a class=normalfont href=# title=' Podgląd zgłoszenia nr $temp_nr '  onclick=\"newWindow_r(800,600,'hd_o_zgloszenia.php?action=podglad&nr=$temp_nr&zgl_s=$zgl_ser');\">";
				echo "<input class=imgoption type=image src=img/hd_podglad.gif>";
				echo "</a>";		
			}
		echo "</td>";
		
		_tr();
		$i++;
	}	
	
	echo "</table>";
	
echo "<form action=do_xls_htmlexcel_hd_g_raport_przesuniete_terminy.php METHOD=POST target=_blank>";	
startbuttonsarea("right");
	echo "<span style='float:left;'>";
	addlinkbutton("'Zmień kryteria'","main.php?action=hdzzptr&typ=$_REQUEST[typ]&okres=$okres");
	echo "</span>";

	echo "<input class=buttons type=submit value='Export do XLS'>";
	
	echo "<input type=hidden name=okres value='$_REQUEST[okres]'>";
	echo "<input type=hidden name=typ value='$_REQUEST[typ]' >";
	
addbuttons("start");
endbuttonsarea();	
echo "</form>";

} else {

		errorheader("Nie znaleziono zgłoszeń z przesuniętym terminem rozpoczęcia w wybranym okresie");
		startbuttonsarea("right");
		echo "<span style='float:left;'>";
		addlinkbutton("'Zmień kryteria'","main.php?action=hdzzptr&typ=$_REQUEST[typ]&okres=$okres");
		echo "</span>";

		addbuttons("start");
		endbuttonsarea();	

		?><meta http-equiv="REFRESH" content="<?php echo $http_refresh; ?>;url=<?php echo "$linkdostrony";?>main.php?action=hdzzptr&typ=<?php echo $_REQUEST[typ]; ?>&okres=<?php echo $_REQUEST[okres]; ?>"><?php	
}

	include('body_stop.php');
	echo "</body></html>";
	
} else {
br();
pageheader("Generowanie raportu ze zgłoszeń z przesuniętymi terminami rozpoczęcia");
starttable("650px");
echo "<form name=ruch action=hd_g_raport_przesuniete_terminy.php method=GET>";
tbl_empty_row();	
	tr_();
		echo "<td class=right>";
			echo "Wybierz zakres dla raportu";
		_td();
		
		echo "<td colspan=2 width=200>";
			echo "<input type=radio name=typ id=typ_0 value='allwithclosed' ";
			if ($_REQUEST[typ]=='allwithclosed') echo " checked ";
			echo " onClick=\"document.getElementById('nadzien').style.display='none';\"><a href=# class=normalfont onClick=\"document.getElementById('nadzien').style.display='none';document.getElementById('typ_0').checked=true;\">wszystkie</a>&nbsp;";
			echo "<br />";
			echo "<input type=radio name=typ id=typ_1 value='all' ";
			if ($_REQUEST[typ]=='all') echo " checked ";
			echo " onClick=\"document.getElementById('nadzien').style.display='none';\"><a href=# class=normalfont onClick=\"document.getElementById('nadzien').style.display='none';document.getElementById('typ_1').checked=true;\">wszystkie nie zamknięte</a>&nbsp;";
			echo "<br />";
			echo "<input type=radio name=typ id=typ_2 value='day' ";
			if (($_REQUEST[typ]=='') || ($_REQUEST[typ]=='day')) echo " checked ";
			echo " onClick=\"document.getElementById('nadzien').style.display='';\"><a href=# class=normalfont onClick=\"document.getElementById('nadzien').style.display='';document.getElementById('typ_2').checked=true;\">na dzień</a>&nbsp;";
			echo "<span id=nadzien";
			if ($_REQUEST[typ]=='all') echo " style='display:none' ";
			echo ">";
				$dzien = SubstractDays(1,Date('Y-m-d'));
				if ($_REQUEST[okres]!='') $dzien = $_REQUEST[okres];
				echo "<input class=wymagane size=10 maxlength=10 type=text id=okres name=okres value=".$dzien." onkeypress='return handleEnter(this, event);' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\">";
				echo "<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif width=16 height=16 border=0></a>";
				if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('okres').value='".Date('Y-m-d')."'; return false;\">";	
			echo "</span>";
		_td();
	_tr();
	
tbl_empty_row();
endtable();
startbuttonsarea("center");
addownsubmitbutton("'Generuj raport'","submit");
//echo "<input type=reset class=buttons value='Kryteria domyślne'>";
endbuttonsarea();
_form();	
?>
<script language="JavaScript" type="text/javascript">
	var frmvalidator  = new Validator("ruch");
	frmvalidator.addValidation("okres","req","Nie podałeś daty początkowej");  
	frmvalidator.addValidation("okres","numerichyphen","Użyłeś niedozwolonych znaków w polu \"od dnia\"");
</script>

<script language="JavaScript">
var cal1 = new calendar1(document.forms['ruch'].elements['okres']);
	cal1.year_scroll = true;
	cal1.time_comp = false;
</script>

<?php }
?>
</body>
</html>