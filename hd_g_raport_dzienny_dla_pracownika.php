<?php include_once('header.php'); ?>
<body>
<?php 

if ($submit) { 
	include('cfg_helpdesk.php');
	
	include('body_start.php');
	
	$tuser=$_GET[tuser];
	$okres_od1=$_GET[okres_od];
	$okres_do1=$_GET[okres_do];
	
	$all_from_filia = str_replace("\'", "'", $_REQUEST['all_from_filia']);
	$all_from_filia = str_replace("\'", "", $_REQUEST['all_from_filia']);
	
	$ile_from_filia = substr_count($_REQUEST['all_from_filia'],',');
	
	$jedna_osoba = explode(",",$all_from_filia);
	
	$status = $_GET[tstatus];
	$sql="SELECT * FROM $dbname_hd.hd_zgloszenie_szcz ";
	
	if ($_REQUEST[kategoria]!='') $sql.=" ,$dbname_hd.hd_zgloszenie ";
	
	$sql .=  " WHERE ";
	//if (($es_m==1) || ($is_dyrektor==1) || ($_REQUEST[fromraport]==1)) { } else $sql=$sql."(hd_zgloszenie_szcz.belongs_to=$es_filia) and ";
	// wg dnia
	
	$sql.=" (hd_zgloszenie_szcz.zgl_szcz_widoczne=1) AND ";
	$sql.=" (SUBSTRING(hd_zgloszenie_szcz.zgl_szcz_czas_rozpoczecia_kroku,1,10)>='$okres_od1') AND (SUBSTRING(hd_zgloszenie_szcz.zgl_szcz_czas_rozpoczecia_kroku,1,10)<='$okres_do1') ";
	// wg przypisania
	if ($_REQUEST[tuser]!='all') { 
		$sql.="AND ((hd_zgloszenie_szcz.zgl_szcz_osoba_wykonujaca_krok='$_REQUEST[tuser]') OR (zgl_szcz_dodatkowe_osoby_wykonujace_krok LIKE '%$_REQUEST[tuser]%')) ";
	} else {
		
		$sql.="AND (";
		for ($xx=0; $xx<=$ile_from_filia; $xx++) {
			$sql.="(zgl_szcz_dodatkowe_osoby_wykonujace_krok LIKE '%$jedna_osoba[$xx]%') OR ";
		}
		$sql = substr($sql,0,-4);
		$sql.=")"; //$sql.="AND ((hd_zgloszenie_szcz.zgl_szcz_osoba_wykonujaca_krok IN (".$all_from_filia."))) ";
	}
	
	if ($_REQUEST[kategoria]!='') {
		$sql.= " AND (zgl_kategoria=$_REQUEST[kategoria]) and (zgl_id=zgl_szcz_zgl_id) ";
	}
	
	$sql=$sql." AND (((hd_zgloszenie_szcz.zgl_szcz_status<>2) and (hd_zgloszenie_szcz.zgl_szcz_nr_kroku>=1)) or ((hd_zgloszenie_szcz.zgl_szcz_status=2) and (hd_zgloszenie_szcz.zgl_szcz_nr_kroku=1)) )";
	
	$sql=$sql."ORDER BY zgl_szcz_zgl_id ASC";
//	echo $sql;
		
	$result = mysql_query($sql, $conn_hd) or die($k_b);
	$count_rows = mysql_num_rows($result);
	
if ($count_rows!=0) {	
	
	if ($_REQUEST[special]!=1) {
		if ($_REQUEST[tuser]!='all') { 
		
			if ($_REQUEST[readonly]!=1) {
				pageheader("Raport dzienny dla pracownika <b>$_REQUEST[tuser]</b>",1,1); 
			} else {
				if ($_REQUEST[kategoria]!='') {
					$result6a = mysql_query("SELECT hd_kategoria_opis FROM $dbname_hd.hd_kategoria WHERE (hd_kategoria_nr=$_REQUEST[kategoria])", $conn) or die($k_b);
					list($nazwa_kategorii) = mysql_fetch_array($result6a);			
					pageheader("Raport dzienny dla kategorii <font color=red>$nazwa_kategorii</font> dla pracownika <b>$_REQUEST[tuser]</b>",1,0); 
				} else {
					pageheader("Raport dzienny dla kategoriidla pracownika <b>$_REQUEST[tuser]</b>",1,0); 
				}
			}
		
		} else { pageheader("Raport dzienny dla wszystkich pracowników",1,1); }
	} else {
		if ($_REQUEST[tuser]!='all') { pageheader("Raport dzienny dla pracownika: $_REQUEST[tuser]",1,0); } else { pageheader("Raport dzienny dla wszystkich pracowników",1,0); }	
	}

	?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();	
	
	echo "<table class=maxwidth cellspacing=1 align=center>";
	echo "<tr>";
	echo "<th><center>LP</center></th>";	
	echo "<th><center>Nr<br />kroku</center></th>";	
	echo "<th><center>Nr zgłoszenia</center></th>";
	echo "<th><center>Nr zgł.<br />poczty</center></th>";
	echo "<th><center>Data rozpoczęcia kroku</center></th>";
	echo "<th><center>Czas realizacji</center></th>";
	echo "<th><center>Ilość km / Czas przejazdu (min)</center></th>";
	echo "<th>Placówka zgłaszająca</th>";
	echo "<th>Wykonane czynności</th>";
	echo "<th><center>Osoba wykonująca<br /><sub>dodatkowe osoby</sub></center></th>";
	echo "<th><center>Status</center></th>";	
	echo "<th><center>Opcje</center></th>";
	echo "</tr>";
	
	$i = 0;
	$j = $page*$rowpersite-$rowpersite+1;
	$k = 1;
	
	$czas_razem = 0;
	$zgl_razem = 0;
	$km_razem = 0;
	$czas_p_razem = 0;
	
	while ($newArray = mysql_fetch_array($result)) {
		$temp_id			= $newArray['zgl_szcz_id'];		
		$temp_nr			= $newArray['zgl_szcz_zgl_id'];
		$temp_nr_kroku		= $newArray['zgl_szcz_nr_kroku'];
		$temp_data			= $newArray['zgl_szcz_czas_rozpoczecia_kroku'];
		
		list($zgl_nr,$temat,$placowka,$tresc,$nrpoczta,$status1)=mysql_fetch_array(mysql_query("SELECT zgl_nr,zgl_temat,zgl_komorka,zgl_tresc,zgl_poczta_nr,zgl_status FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id=$temp_nr) LIMIT 1", $conn_hd));
			
		list($zgl_seryjne)=mysql_fetch_array(mysql_query("SELECT zgl_poledodatkowe2 FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id=$temp_nr) LIMIT 1", $conn_hd));	
		
		
		$temp_status 		= $newArray['zgl_szcz_status'];
		$temp_wc			= $newArray['zgl_szcz_wykonane_czynnosci'];
		$temp_czas			= $newArray['zgl_szcz_czas_wykonywania'];
		$temp_km			= $newArray['zgl_szcz_il_km'];
		$temp_op			= $newArray['zgl_szcz_osoba_wykonujaca_krok'];
		$temp_dodatkowe_osoby = $newArray['zgl_szcz_dodatkowe_osoby_wykonujace_krok'];

		$temp_czas_przejazdu = $newArray['zgl_szcz_czas_trwania_wyjadu'];
		
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
		echo "<td class=center>$k</td>"; $k++;
		echo "<td class=center>$temp_nr_kroku</td>";
		echo "<td class=center>";
			echo "<a class=normalfont href=# title='Przejdź do zgłoszenia numer ".$zgl_nr."' onClick=\"self.location.href='hd_p_zgloszenia.php?sr=search-wyniki&search_zgl_nr=".$zgl_nr."'\">$zgl_nr</a>";
		echo "</td>";
		echo "<td class=center>$nrpoczta";
		//if ($nrpoczta!='') echo "<br />$nrpoczta";
		echo "</td>";
		echo "<td class=center>".substr($temp_data,0,16)."";
				
		echo "</td>";
		echo "<td class=center>";
		
		if ($temp_czas>0) echo "<b>";
		echo "$temp_czas minut";
		if ($temp_czas>0) echo "</b>";
		
		if ($temp_dodatkowe_osoby!='') {
			echo "<br />";
			$jeden_dodatkowy = explode(", ", $temp_dodatkowe_osoby);
			$ile_dodatkowych1 = substr_count($temp_dodatkowe_osoby,', ');
			//echo "$ile_dodatkowych1";
			echo "<a title=' Czas poświęcony na realizację kroku przez osoby dodatkowe '>";
			for ($xx=0; $xx<=$ile_dodatkowych1-1; $xx++) {
				echo "$temp_czas minut<br />";
			}
			
				echo "</a>";
				nowalinia();
		}
		
		echo "</td>";
		echo "<td class=center>";
		
		if ($temp_km>0) echo "<b>";		
		echo "$temp_km km";
		if ($temp_km>0) echo "</b>";		

		if ($temp_czas_przejazdu>0) echo "<b>";
		echo " / $temp_czas_przejazdu min";
		if ($temp_czas_przejazdu>0) echo "</b>";
		
		echo "</td>";
		echo "<td>$placowka</td>";
	
		echo "<td>".$temp_wc."</td>";
	
		
		echo "<td class=center>";
		
		echo "<b><a title=' Osoba główna wykonująca krok '>";
		echo "$temp_op";
		echo "</a></b></br />";
		
		//if (($_REQUEST[tuser]!='all') && (
		
	//	echo "$temp_dodatkowe_osoby<br />";
	//	echo "$_REQUEST[tuser]";
		
		//if (strpos("'".$temp_dodatkowe_osoby."'",$_REQUEST[tuser])) echo " (+)";
		
		//echo strpos("'".$temp_dodatkowe_osoby."'",$_REQUEST[tuser]);
		
		if ($temp_dodatkowe_osoby!='') {
			oddziel();
			$jeden_dodatkowy = explode(", ", $temp_dodatkowe_osoby);
			$ile_dodatkowych = substr_count($temp_dodatkowe_osoby,', ')-1;
			echo "<a title=' Dodatkowe osoby uczestniczące w realizacji kroku '>";
			for ($xx=0; $xx<=$ile_dodatkowych; $xx++) echo "$jeden_dodatkowy[$xx]<br />";
				echo "</a>";
				nowalinia();
		}
				
		echo "</td>";		
		echo "<td class=center>";
		
		if (($status1!=9) && ($temp_status!=9)) {
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
		}
		
		echo "</td>";
		echo "<td class=center>";
			$zgl_ser = 0;
			if ($zgl_seryjne!='') $zgl_ser = 1; 
			
			if ($status1!=9) {
				echo "<a class=normalfont href=# title=' Przejdź do obsługi zgłoszenia nr $temp_nr '  onclick=\"newWindow_r(800,600,'hd_o_zgloszenia.php?action=obsluga&nr=$temp_nr&zgl_s=$zgl_ser'); return false;\">";
				echo "<input class=imgoption type=image src=img/hd_obsluga_start.gif>";
				echo "</a>";
			} else {
				echo "<a class=normalfont href=# title=' Podgląd zgłoszenia nr $temp_nr '  onclick=\"newWindow_r(800,600,'hd_o_zgloszenia.php?action=podglad&nr=$temp_nr&zgl_s=$zgl_ser'); return false; \">";
				echo "<input class=imgoption type=image src=img/hd_podglad.gif>";
				echo "</a>";		
			}
		echo "</td>";
		
		$czas_razem+=$temp_czas;
	//	echo "$temp_czas $ile_dodatkowych1";
		if ($temp_dodatkowe_osoby!='') {
			if ($_REQUEST[tuser]=='all') $czas_razem+=($temp_czas*$ile_dodatkowych1);
		}
		
		//echo "$czas_razem";
		$km_razem+=$temp_km;
		$czas_p_razem += $temp_czas_przejazdu;
		
		$zgl_razem++;
		$i++;
		_tr();
	}	
	
	echo "</table>";
/*
echo "<form action=do_xls_htmlexcel_hd_g_raport_okresowy.php METHOD=POST target=_blank>";	
startbuttonsarea("right");

startbuttonsarea("left");
echo "<br />&nbsp;Łączna ilość zgłoszeń w wybranym okresie : <b>$zgl_razem</b>";
echo "<br />&nbsp;Łączny czas poświęcony w wybranym okresie : <b>".minutes2hours($czas_razem,'')."</b>";

echo "<input type=hidden name=g_zgl_razem value='$zgl_razem'>";
echo "<input type=hidden name=g_czas_razem value='".minutes2hours($czas_razem,'')."'>";

endbuttonsarea();

	echo "<input class=buttons type=submit value='Export do XLS'>";
	
	echo "<input type=hidden name=g_okres_od value='$_REQUEST[okres_od]'>";
	echo "<input type=hidden name=g_okres_do value='$_REQUEST[okres_do]'>";
	echo "<input type=hidden name=g_tzgldata value='$_REQUEST[tzgldata]'>";
	
	echo "<input type=hidden name=zapytanie value=\"$sql\" >";
	
//addlinkbutton("'Przeglądaj towary'","p_towary_dostepne.php?view=normal&wybor=".urlencode($wybor)."");
addlinkbutton("'Zmień kryteria'","main.php?action=hdddp&okres_od=$okres_od&okres_do=$okres_do&tuser=".urlencode($tuser)."&tstatus=$tstatus&kategoria=$kategoria&podkategoria=$podkategoria&priorytet=$priorytet&tzgldata=$tzgldata");
addbuttons("start");
endbuttonsarea();	
echo "</form>";
*/
startbuttonsarea("right");

	startbuttonsarea("left");
	echo "<br />&nbsp;Łączna ilość czynności (kroków) wykonanych w wybranym okresie: <b>$zgl_razem</b>";
	echo "<br />&nbsp;Łączny czas poświęcony w wybranym okresie (bez czasów przejazdu): <b>".$czas_razem." minut | ".minutes2hours($czas_razem,'')."</b>";
	echo "<br />&nbsp;Łączna ilość km w wybranym okresie: <b>".$km_razem." km</b>";
	echo "<br />&nbsp;Łączny czas poświęcony na przejazdy w wybranym okresie: <b>".$czas_p_razem." minut</b>";

	echo "<input type=hidden name=g_zgl_razem value='$zgl_razem'>";
	echo "<input type=hidden name=g_czas_razem value='".minutes2hours($czas_razem,'')."'>";
	echo "<input type=hidden name=g_km_razem value='$km_razem'>";

	endbuttonsarea();
	
nowalinia();
if ($_REQUEST[special]!=1) {
	echo "<span style='float:left'>";
	if ($_REQUEST[readonly]!=1) addlinkbutton("'Zmień kryteria'","main.php?action=hdddp&okres_od=$okres_od&okres_do=$okres_do&tuser=".urlencode($tuser)."&tstatus=$tstatus&kategoria=$kategoria&podkategoria=$podkategoria&priorytet=$priorytet&tzgldata=$tzgldata");
	echo "</span>";
	
	addownlinkbutton("'Nowe zgłoszenie'","Button1","button","newWindow_r(800,600,'hd_d_zgloszenie_simple.php?stage=1')");
	//addownlinkbutton("'Nowe zgłoszenie seryjne'","Button1","button","newWindow_r(800,600,'hd_d_zgloszenie_s.php?stage=1&filtr=X-X-X-X&p6=true')");
	echo " | ";
	addlinkbutton("'Przeglądaj zgłoszenia'","hd_p_zgloszenia.php?showall=0&p5=BZ&sort=AD&page=1");
	echo " | ";
	if ($_REQUEST[readonly]!=1) addbuttons("start");
} else {
	addbuttons("zamknij");
}

if ($_REQUEST[readonly]==1) addbuttons("zamknij");

endbuttonsarea();	

} else {

		errorheader("Nie znaleziono zgłoszeń spełniających podane przez Ciebie kryteria");
		startbuttonsarea("right");
		
		if ($_REQUEST[norefresh]!=1) {
			echo "<span style='float:left'>";
			addbuttons("wstecz");

			echo "<span style='float:left'>";
				if ($_REQUEST[readonly]!=1) addlinkbutton("'Zmień kryteria'","main.php?action=hdddp&okres_od=$okres_od&okres_do=$okres_do&tuser=".urlencode($tuser)."&tstatus=$tstatus&kategoria=$kategoria&podkategoria=$podkategoria&priorytet=$priorytet&tzgldata=$tzgldata");
			echo "</span>";

		//if ($_REQUEST[readonly]!=1) addbuttons("start");
			echo "</span>";
		}
		
		if ($_REQUEST[norefresh]==1) {
			addbuttons("zamknij");
		} else {
			addbuttons("start");
		}
			
		endbuttonsarea();	

		if (($_REQUEST[readonly]!=1) && ($_REQUEST[norefresh]==0)) {
		?><meta http-equiv="REFRESH" content="<?php echo $http_refresh; ?>;url=<?php echo "$linkdostrony";?>main.php?action=hdddp&tstatus=<?php echo $tstatus;?>&kategoria=<?php echo $kategoria; ?>&podkategoria=<?php echo $podkategoria;?>&priorytet=<?php echo $priorytet; ?>&tzgldata=<?php echo $tzgldata;?>"><?php	
		}
	}

	include('warning_messages_blinking.php');
	
	include('body_stop.php');
	echo "</body></html>";
	
} else {
br();

// wyciągnij imie i nazwisko kierownika danej filii
$KierownikId = $kierownik_nr;
//$r40 = mysql_query("SELECT filia_leader FROM $dbname.serwis_filie WHERE (filia_id='$es_filia') LIMIT 1", $conn) or die($k_b);
//list($KierownikId)=mysql_fetch_array($r40);
// koniec wyciągania danych o kierowniku

if (($es_nr!=$KierownikId) && ($es_m!=1)) {
	pageheader("Raport dzienny dla pracownika $currentuser");
} else pageheader("Raport dzienny dla pracownika");

starttable("500px");
echo "<form name=ruch action=hd_g_raport_dzienny_dla_pracownika.php method=GET>";
tbl_empty_row();
	tr_();
		td_colspan(1,'r');
			//echo "<b>Podaj zakres dat</b>";
		_td();
	
		td_colspan(1,'c'); echo "&nbsp;"; _td();

		td_img(";l");
			echo "od daty";
		_td();
		td_img(";l");
			echo "do daty";
		_td();	
	_tr();
	
	tr_();
		td_colspan(1,'r');
			echo "<b>Podaj zakres dat</b>";
		_td();

	$data1=Date('Y').'-'.Date('m').'-'.Date('d');
	
	if ($_GET[okres_od]!='') $data1 = $_GET[okres_od];
	if ($_GET[okres_do]!='') $data2 = $_GET[okres_do];
	
		td_colspan(1,'c'); echo "&nbsp;"; _td();

		td_img(";l");
			echo "<input class=wymagane size=10 maxlength=10 type=text id=okres_od name=okres_od value=$data1 onkeypress='return handleEnter(this, event);' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\">";
			echo "<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif width=16 height=16 border=0></a>";
			if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('okres_od').value='".Date('Y-m-d')."'; return false;\">";
		_td();
		td_img(";l");
			echo "<input class=wymagane size=10 maxlength=10 type=text id=okres_do name=okres_do value=$data1 onkeypress='return handleEnter(this, event);' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\">";
			echo "<a tabindex=-1 href=javascript:cal11.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif width=16 height=16 border=0></a>";
			if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('okres_do').value='".Date('Y-m-d')."'; return false;\">";			
		_td();	
	_tr();	
	tbl_empty_row();	
	if (($es_nr!=$KierownikId)) { // || ($es_m!=1)) {
		echo "<input type=hidden name=tuser value='$currentuser'>";
	} else {
			tr_();
				td_colspan(1,'r');
					echo "<b>Wybierz pracownika</b>";
				_td();
				td_colspan(1,'c'); echo "&nbsp;"; _td();
				td_colspan(2,'l');
				$all_from_filia = '';
					$result6 = mysql_query("SELECT user_first_name, user_last_name FROM $dbname.serwis_uzytkownicy WHERE (belongs_to=$es_filia) and (user_locked=0) ORDER BY user_first_name", $conn) or die($k_b);
					echo "<select name=tuser onkeypress='return handleEnter(this, event);'>\n"; 					 				
					echo "<option value='all'"; if ($_GET[tuser]=='all') echo " SELECTED "; echo ">Wszyscy z bieżącej filii";
					while (list($temp_imie,$temp_nazwisko) = mysql_fetch_array($result6)) {
						echo "<option value='$temp_imie $temp_nazwisko'"; 
						$iin = $temp_imie.' '.$temp_nazwisko;
						
						$all_from_filia .= "'".$iin ."',";
						
						if ($_GET[tuser]==$iin) echo " SELECTED "; echo ">$temp_imie $temp_nazwisko</option>\n"; 
					}
					echo "</select>\n";
					$all_from_filia = substr($all_from_filia,0,-1);
					echo "<input type=hidden name=all_from_filia value=\"$all_from_filia\">";
				_td();
			_tr();
			tbl_empty_row();
		}
		
endtable();
startbuttonsarea("center");
//echo "<input type=reset class=buttons value='Kryteria domyślne'>";
addownsubmitbutton("'Generuj raport'","submit");
endbuttonsarea();
_form();	
?>
<script language="JavaScript">
var cal1 = new calendar1(document.forms['ruch'].elements['okres_od']);
	cal1.year_scroll = true;
	cal1.time_comp = false;
var cal11 = new calendar1(document.forms['ruch'].elements['okres_do']);
	cal11.year_scroll = true;
	cal11.time_comp = false;
</script>
<script language="JavaScript" type="text/javascript">
	var frmvalidator  = new Validator("ruch");
	frmvalidator.addValidation("okres_od","req","Nie podałeś daty początkowej");  
	frmvalidator.addValidation("okres_do","req","Nie podałeś daty końcowej");  
	frmvalidator.addValidation("okres_od","numerichyphen","Użyłeś niedozwolonych znaków w polu \"od dnia\"");
	frmvalidator.addValidation("okres_do","numerichyphen","Użyłeś niedozwolonych znaków w polu \"do dnia\"");
</script>
<?php }
?>

<script>HideWaitingMessage();</script> 
</body>
</html>