<?php include_once('header.php'); ?>
<body>
<?php 
include('cfg_helpdesk.php');
if ($submit) { 
include('body_start.php');
	$sql="SELECT * FROM $dbname_hd.hd_zgloszenie WHERE (zgl_poledodatkowe2='$_POST[tzgloszenie]') ";
	if ($es_m==1) { } else $sql=$sql." and (hd_zgloszenie.belongs_to=$es_filia) and (hd_zgloszenie.zgl_widoczne=1) ";
	$sql=$sql."ORDER BY zgl_komorka DESC";	

	$result = mysql_query($sql, $conn_hd) or die($k_b);
	$count_rows = mysql_num_rows($result);
	//echo $sql." <br />";
	
if ($count_rows!=0) {
	
	$result66 = mysql_query("SELECT zgl_data, zgl_temat FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$es_filia) and (zgl_poledodatkowe2='$_POST[tzgloszenie]') and (zgl_widoczne=1)) LIMIT 1", $conn_hd) or die($k_b);
	list($zgl_ser_data,$zgl_ser_temat) = mysql_fetch_array($result66);

	pageheader("Raport z % realizacji zgłoszenia seryjnego",1,1);
	infoheader("$zgl_ser_temat");

	?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
	
	echo "<table class=maxwidth cellspacing=1 align=center>";
	echo "<tr>";
	echo "<th><center>Nr zgłoszenia<br />Nr zgl. poczty</center></th>";
	echo "<th>Placówka zgłaszająca</th>";
	echo "<th><center>Osoba przypisana</center></th>";
	
	echo "<th><center>Czas wyk. (min)</center></th>";
	echo "<th><center>Km</center></th>";

	echo "<th><center>Status</center></th>";
	echo "<th><center>Opcje</center></th>";
	echo "</tr>";
	
	$i = 0;
	$j = $page*$rowpersite-$rowpersite+1;
	
	$czas_razem = 0;
	$km_razem = 0;
	$zgl_razem = 0;
	$zgl_zakonczone = 0;
	
	while ($newArray = mysql_fetch_array($result)) {
		$temp_nr			= $newArray['zgl_nr'];
		
			if ($_REQUEST[tzgldata]=='data_utworzenia') {	
				$temp_data			= $newArray['zgl_data'];
			}	

			if ($_REQUEST[tzgldata]=='data_modyfikacji') {	
				$temp_data			= $newArray['zgl_data_zmiany_statusu'];
			}
		
		$temp_komorka		= $newArray['zgl_komorka'];
		$temp_temat			= $newArray['zgl_temat'];
		$temp_status 		= $newArray['zgl_status'];
		$temp_czas			= $newArray['zgl_razem_czas'];
		$temp_km			= $newArray['zgl_razem_km'];	
		$temp_op			= $newArray['zgl_osoba_przypisana'];
		$temp_poczta		= $newArray['zgl_poczta_nr'];
	
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
		
		if ($temp_status==9) { $zgl_zakonczone++; $zgl_action = 'podglad'; } else { $zgl_action = 'obsluga'; }
		
		echo "<td class=center>";
		echo "$temp_nr";
		//echo "<input type=button class=buttons title=' Przejdź do obsługi zgłoszenia nr $temp_nr ' onclick=\"newWindow_r(800,600,'hd_o_zgloszenia.php?action=$zgl_action&nr=$temp_nr&zgl_s=1'); ";
		//if ($temp_status==9) echo " return false; ";
		//echo "\" value=$temp_nr>";
		
		if ($temp_poczta!='') echo "<br />$temp_poczta";
		echo "</td>";
		echo "<td>$temp_komorka</td>";
		echo "<td class=center>$temp_op</td>";	
		
		if ($temp_czas=='0') $temp_czas='-';
		if ($temp_km=='0') $temp_km='-';
		
		echo "<td class=center>$temp_czas</td>";
		echo "<td class=center>$temp_km</td>";
		
		echo "<td class=center>$status</td>";
		
		echo "<td class=center>";
			if ($temp_status!=9) {
				echo "<a class=normalfont href=# title=' Przejdź do obsługi zgłoszenia nr $temp_nr '  onclick=\"newWindow_r(800,600,'hd_o_zgloszenia.php?action=$zgl_action&nr=$temp_nr&zgl_s=1');\">";
				echo "<input class=imgoption type=image src=img/hd_obsluga_start.gif>";
				echo "</a>";
			} else {
				echo "<a class=normalfont href=# title=' Podgląd zgłoszenia nr $temp_nr '  onclick=\"newWindow_r(800,600,'hd_o_zgloszenia.php?action=podglad&nr=$temp_nr&zgl_s=1');\">";
				echo "<input class=imgoption type=image src=img/hd_podglad.gif>";
				echo "</a>";		
			}
		echo "</td>";
		
		$czas_razem+=$temp_czas;
		$km_razem+=$temp_km;
		$zgl_razem++;
		
		$i++;
		_tr();
	}	
	
	echo "</table>";
	
//echo "<form action=do_xls_htmlexcel_hd_g_raport_okresowy.php METHOD=POST target=_blank>";	
startbuttonsarea("right");

startbuttonsarea("left");
echo "<br />&nbsp;Komórek w wybranym zgłoszeniu seryjnym : <b>$zgl_razem</b>";
echo "<br />&nbsp;Komórek ze statusem zakończone : <b>$zgl_zakonczone</b>";
echo "<br /><br />&nbsp;% wykonania : <b>".ceiling(($zgl_zakonczone/$zgl_razem)*100)."%</b><br />";

echo "<br />&nbsp;Łączny czas poświęcony na realizację zgłoszenia : <b>".minutes2hours($czas_razem,'')."</b>";
echo "<br />&nbsp;Łączna ilość km związana z realizacją zgłoszenia : <b>".$km_razem." km</b>";

//echo "<input type=hidden name=g_zgl_razem value='$zgl_razem'>";
//echo "<input type=hidden name=g_czas_razem value='".minutes2hours($czas_razem,'')."'>";

endbuttonsarea();

//	echo "<input class=buttons type=submit value='Export do XLS'>";
	
//	echo "<input type=hidden name=g_okres_od value='$_REQUEST[okres_od]'>";
//	echo "<input type=hidden name=g_okres_do value='$_REQUEST[okres_do]'>";
//	echo "<input type=hidden name=g_tzgldata value='$_REQUEST[tzgldata]'>";
	
//	echo "<input type=hidden name=zapytanie value=\"$sql\" >";
	
addlinkbutton("'Zmień kryteria'","hd_g_raport_z_wykonania_zgl_s.php");
addbuttons("start");
endbuttonsarea();	
//echo "</form>";

} else {

		errorheader("Nie znaleziono zgłoszeń spełniających podane przez Ciebie kryteria");
		startbuttonsarea("right");
		addlinkbutton("'Zmień kryteria'","hd_g_raport_z_wykonania_zgl_s.php");
		addbuttons("start");
		endbuttonsarea();	

		?><meta http-equiv="REFRESH" content="<?php echo $http_refresh; ?>;url=<?php echo "$linkdostrony";?>main.php?action=hdgro"><?php	
}

	include('body_stop.php');
	echo "</body></html>";
	
} else {

pageheader("Generowanie raportu z % realizacji zgłoszenia seryjnego",0,1);
starttable();
echo "<form name=ruch action=hd_g_raport_z_wykonania_zgl_s.php method=POST>";
tbl_empty_row();
	tr_();
		td_(';r;');
			echo "Pokaż zgłoszenia seryjne";
		_td();
		td_(';;');
			//echo "[Z] - zakończone<br />[O] - otwarte";
			echo "<select name=wyb onChange=\"self.location.href=this.value\">";
			echo "<option "; if ($_GET[status_zgl_s]=='') echo " SELECTED "; echo " value=\"hd_g_raport_z_wykonania_zgl_s.php?status_zgl_s=\">wybierz z listy</option>";
			echo "<option "; if ($_GET[status_zgl_s]=='-') echo " SELECTED "; echo " value=\"hd_g_raport_z_wykonania_zgl_s.php?status_zgl_s=-\">wszystkie</option>";
			echo "<option "; if ($_GET[status_zgl_s]=='O') echo " SELECTED "; echo " value=\"hd_g_raport_z_wykonania_zgl_s.php?status_zgl_s=O\">otwarte</option>";
			echo "<option "; if ($_GET[status_zgl_s]=='Z') echo " SELECTED "; echo " value=\"hd_g_raport_z_wykonania_zgl_s.php?status_zgl_s=Z\">zakończone</option>";
			echo "</select>";
		_td();
	_tr();
	
?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
	
if ($_GET[status_zgl_s]!='') {
	
	tr_();
		td_colspan(1,'r');
			echo "Wybierz zgłoszenie seryjne";
		_td();
		td_(';;');
			$result6 = mysql_query("SELECT DISTINCT zgl_poledodatkowe2 FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$es_filia) and (zgl_poledodatkowe2<>'') and (zgl_widoczne=1)) ORDER BY zgl_data DESC, zgl_godzina DESC", $conn_hd) or die($k_b);
			echo "<select class=wymagane name=tzgloszenie onkeypress='return handleEnter(this, event);'>\n"; 					 				
			echo "<option value=''>Wybierz z listy...</option>";
			$ilosc_zgl = 0;
			while (list($unique_zgl_ser) = mysql_fetch_array($result6)) {
			
				$result66 = mysql_query("SELECT COUNT(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$es_filia) and (zgl_poledodatkowe2='$unique_zgl_ser') and (zgl_widoczne=1))", $conn_hd) or die($k_b);
				list($zgl_ser_ilosc_razem) = mysql_fetch_array($result66);

				$result66 = mysql_query("SELECT COUNT(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$es_filia) and (zgl_poledodatkowe2='$unique_zgl_ser') and (zgl_widoczne=1) and (zgl_status=9)) ", $conn_hd) or die($k_b);
				list($zgl_ser_ilosc_zakonczonych) = mysql_fetch_array($result66);
				
				$result66 = mysql_query("SELECT zgl_data, zgl_temat FROM $dbname_hd.hd_zgloszenie WHERE ((belongs_to=$es_filia) and (zgl_poledodatkowe2='$unique_zgl_ser') and (zgl_widoczne=1)) LIMIT 1", $conn_hd) or die($k_b);
				list($zgl_ser_data,$zgl_ser_temat) = mysql_fetch_array($result66);
					
				if ($_GET[status_zgl_s]=='O') {
					if ($zgl_ser_ilosc_razem!=$zgl_ser_ilosc_zakonczonych) {
						echo "<option value='$unique_zgl_ser'>";
						if (strlen($zgl_ser_temat)>100) { substr($zgl_ser_temat,0,100)."..."; } else echo "$zgl_ser_temat";
						echo " [$zgl_ser_data]";
						echo "</option>\n"; 
						$ilosc_zgl++;
					}
				}
				if ($_GET[status_zgl_s]=='Z') {
					if ($zgl_ser_ilosc_razem==$zgl_ser_ilosc_zakonczonych) {
						echo "<option value='$unique_zgl_ser'>";
						if (strlen($zgl_ser_temat)>100) { substr($zgl_ser_temat,0,100)."..."; } else echo "$zgl_ser_temat";
						echo " [$zgl_ser_data]";
						echo "</option>\n";
						$ilosc_zgl++;
					}
				}				
				if ($_GET[status_zgl_s]=='-') {
					echo "<option value='$unique_zgl_ser'>";
					if ($zgl_ser_ilosc_razem!=$zgl_ser_ilosc_zakonczonych) {
						echo "[O] ";
					} else {
						echo "[Z] ";
					}
					if (strlen($zgl_ser_temat)>100) { substr($zgl_ser_temat,0,100)."..."; } else echo "$zgl_ser_temat";
					echo " [$zgl_ser_data]";
					echo "</option>\n";
					$ilosc_zgl++;
				}
			}
			echo "</select>\n";
		_td();
	_tr();
if ($_GET[status_zgl_s]=='-') {
	tr_();
		td_colspan(1,'l');
			//echo "<b>Wybierz zgłoszenie seryjne</b>";
		_td();
		td_colspan(1,'l');
			echo "[O] - otwarte, [Z] - zakończone";
		_td();
	_tr();
}
}
	tbl_empty_row();
	endtable();
	
startbuttonsarea("center");
//echo "<input type=reset class=buttons value='Kryteria domyślne'>";
if (($_GET[status_zgl_s]!='') || ($ilosc_zgl!=0)) {
	addownsubmitbutton("'Generuj raport'","submit");
}
echo "<div style='float:left'>";
echo "&nbsp;";
addbuttons('wstecz');
echo "</div>";
echo "<div style='float:right'>";
addbuttons('start');
echo "</div>";
endbuttonsarea();
_form();	
?>
<script language="JavaScript" type="text/javascript">
	var frmvalidator  = new Validator("ruch");
<?php if ($_GET[status_zgl_s]!='') { ?>
	frmvalidator.addValidation("tzgloszenie","dontselect=0","Nie wybrałeś zgłoszenia seryjnego");  
<?php } ?>
//	frmvalidator.addValidation("okres_od","req","Nie podałeś daty początkowej");  
//	frmvalidator.addValidation("okres_do","req","Nie podałeś daty końcowej");  
//	frmvalidator.addValidation("okres_od","numerichyphen","Użyłeś niedozwolonych znaków w polu \"od dnia\"");
//	frmvalidator.addValidation("okres_do","numerichyphen","Użyłeś niedozwolonych znaków w polu \"do dnia\"");
</script>

<?php }
?>
<script>HideWaitingMessage();</script>
</body>
</html>