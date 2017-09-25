<?php include_once('header.php'); ?>
<body>
<?php 
function toUpper($string) {
	$string = strtr($string,'qwertyuiopasdfghjklzxcvbnm','QWERTYUIOPASDFGHJKLZXCVBNM');
	return (strtr($string, 'ęóąśłżźćń','ĘÓĄŚŁŻŹĆŃ' )); 
};

if ($submit) { 
	include('cfg_helpdesk.php');
	include('body_start.php');
	
	$tuser=$_GET[tuser];
	$okres_od1=$_GET[okres_od];
	$okres_do1=$_GET[okres_do];
	
	$status = $_GET[tstatus];
	
	if ($_REQUEST[tzgldata]=='data_utworzenia') {	
		$sql="SELECT * FROM $dbname_hd.hd_zgloszenie WHERE ";
		
		if (($es_m==1) || ($is_dyrektor==1) || ($_REQUEST[fromraport]==1)) { } else $sql=$sql."(hd_zgloszenie.belongs_to=$es_filia) AND ";
		
		$sql.=" (hd_zgloszenie.zgl_widoczne=1) AND ";
		
		// wg dnia
		$sql.=" (zgl_data>='$okres_od1') AND (zgl_data<='$okres_do1') ";
		// wg kategorii
		if ($_REQUEST[kategoria]!='') $sql.="AND (zgl_kategoria='$_REQUEST[kategoria]') ";
		// wg podkategorii
		if ($_REQUEST[podkategoria]!='') $sql.="AND (zgl_podkategoria='$_REQUEST[podkategoria]') ";
		// wg priorytetu
		if ($_REQUEST[priorytet]!='') $sql.="AND (zgl_priorytet='$_REQUEST[priorytet]') ";
		// wg statusu
		if ($_REQUEST[tstatus]!='0') $sql.="AND (zgl_status='$_REQUEST[tstatus]') ";
		// wg przypisania
		if ($_REQUEST[tuser]!='all') $sql.="AND (zgl_osoba_rejestrujaca='$_REQUEST[tuser]') ";
		
		if ($_REQUEST[potw_spr]=='1') $sql.="AND (zgl_sprawdzone_osoba<>'') ";
		if ($_REQUEST[potw_spr]=='0') $sql.="AND (zgl_sprawdzone_osoba='') ";
		
		if ($_REQUEST[nk]!='') $sql.=" AND (UPPER(zgl_komorka)='".toUpper($_REQUEST[nk])."') ";
		
		//if ($wybierz_p!='') $sql.="AND (serwis_komorki.up_pion_id=$wybierz_p) ";
		$sql=$sql."ORDER BY zgl_data ASC";
	}
	
	if ($_REQUEST[tzgldata]=='data_modyfikacji') {
		$sql="SELECT * FROM $dbname_hd.hd_zgloszenie WHERE ";
		
		if (($es_m==1) || ($is_dyrektor==1) || ($_REQUEST[fromraport]==1)) { } else $sql=$sql."(hd_zgloszenie.belongs_to=$es_filia) and (hd_zgloszenie.zgl_widoczne=1) AND ";
		
		//$sql.=" (hd_zgloszenie.zgl_widoczne=1) AND ";
		// wg dnia
		$sql.=" (zgl_data_zmiany_statusu>='$okres_od1') AND (zgl_data_zmiany_statusu<='$okres_do1') ";
		// wg kategorii
		if ($_REQUEST[kategoria]!='') $sql.="AND (zgl_kategoria='$_REQUEST[kategoria]') ";
		// wg podkategorii
		if ($_REQUEST[podkategoria]!='') $sql.="AND (zgl_podkategoria='$_REQUEST[podkategoria]') ";
		// wg priorytetu
		if ($_REQUEST[priorytet]!='') $sql.="AND (zgl_priorytet='$_REQUEST[priorytet]') ";
		// wg statusu
		if ($_REQUEST[tstatus]!='0') $sql.="AND (zgl_status='$_REQUEST[tstatus]') ";
		// wg przypisania
		if ($_REQUEST[tuser]!='all') $sql.="AND (zgl_osoba_przypisana='$_REQUEST[tuser]') ";
		
		if ($_REQUEST[potw_spr]=='1') $sql.="AND (zgl_sprawdzone_osoba<>'') ";
		if ($_REQUEST[potw_spr]=='0') $sql.="AND (zgl_sprawdzone_osoba='') ";
		
		if ($_REQUEST[nk]!='') $sql.=" AND (UPPER(zgl_komorka)='".toUpper($_REQUEST[nk])."') ";
		//if ($wybierz_p!='') $sql.="AND (serwis_komorki.up_pion_id=$wybierz_p) ";
		$sql=$sql."ORDER BY zgl_data_zmiany_statusu ASC";	
	}
	
	//echo $sql." <br />";
	
	$result = mysql_query($sql, $conn_hd) or die($k_b);
	$count_rows = mysql_num_rows($result);
	
	
if ($count_rows!=0) {	
	
/*	echo "Data od - do : $okres_od1 - $okres_do1<br />";
	echo "Pracownik : $tuser<br />";
	echo "Status zgłoszeń : $status<br />";

	echo "Kategoria zgłoszeń : $_GET[kategoria]<br />";
	echo "Podkategoria zgłoszeń : $_GET[podkategoria]<br />";
	echo "Priorytet zgłoszeń : $_GET[priorytet]<br />";
*/
	if ($_REQUEST[kategoria]!='') {
		$result6a = mysql_query("SELECT hd_kategoria_opis FROM $dbname_hd.hd_kategoria WHERE (hd_kategoria_nr=$_REQUEST[kategoria])", $conn) or die($k_b);
		list($nazwa_kategorii) = mysql_fetch_array($result6a);
	}
	
	$opis_tuser = '';
	if ($_REQUEST[tuser]=='all') { $opis_tuser='wszystkich'; } else { $opis_tuser = $_REQUEST[tuser]; }
	
	if ($_REQUEST[readonly]!=1) {
		if ($_REQUEST[kategoria]!='') {
			pageheader("Raport okresowy ze zgłoszeń w kategorii <font color=red>$nazwa_kategorii</font> dla: <font color=red>$opis_tuser</font> za okres <font color=red>$_REQUEST[okres_od]</font>-<font color=red>$_REQUEST[okres_do]</font>",1,0);
		} else {
			pageheader("Raport okresowy ze zgłoszeń dla: <font color=red>$opis_tuser</font> za okres <font color=red>$_REQUEST[okres_od]</font>-<font color=red>$_REQUEST[okres_do]</font>",1,1);
		}
	} else {
		if ($_REQUEST[kategoria]!='') {
			pageheader("Raport okresowy ze zgłoszeń w kategorii <font color=red>$nazwa_kategorii</font> dla: <font color=red>$opis_tuser</font> za okres <font color=red>$_REQUEST[okres_od]</font>-<font color=red>$_REQUEST[okres_do]</font>",1,0);
		} else {
			pageheader("Raport okresowy ze zgłoszeń dla: <font color=red>$opis_tuser</font> za okres <font color=red>$_REQUEST[okres_od]</font>-<font color=red>$_REQUEST[okres_do]</font>",1,0);
		}
	}
	
	?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
	
	echo "<table class=maxwidth cellspacing=1 align=center>";
	echo "<tr>";
	echo "<th><center>Nr zgłoszenia</center></th>";
	echo "<th><center>Nr zgł.<br />poczty</center></th>";
	
		if ($_REQUEST[tzgldata]=='data_utworzenia') {	
			echo "<th width=auto><center>Data utworzenia</center></th>";
		}	

		if ($_REQUEST[tzgldata]=='data_modyfikacji') {	
			echo "<th width=auto><center>Data modyfikacji</center></th>";
		}	
	echo "<th style='align:left'>Placówka zgłaszająca</th>";
	echo "<th style='align:left'>Temat</th>";
	echo "<th><center>Czas realizacji</center></th>";
	echo "<th><center>Osoba przypisana</center></th>";
	echo "<th><center>Status</center></th>";	
	echo "<th><center>Opcje</center></th>";	
	echo "</tr>";
	
	$i = 0;
	$j = $page*$rowpersite-$rowpersite+1;
	
	$czas_razem = 0;
	$zgl_razem = 0;
	
	$wyjazdowe_razem = 0;
	
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
		$temp_op			= $newArray['zgl_osoba_przypisana'];
		$temp_poczta		= $newArray['zgl_poczta_nr'];
	
		$temp_zgl_data_rozpoczecia	= $newArray['zgl_data_rozpoczecia'];
		$temp_zgl_data_zakonczenia	= $newArray['zgl_data_zakonczenia'];
		$temp_zgl_E1P				= $newArray['zgl_E1P'];
		$temp_zgl_E2P				= $newArray['zgl_E2P'];
		$temp_zgl_E3P				= $newArray['zgl_E3P'];
		$temp_zgl_komorka_working_hours	= $newArray['zgl_komorka_working_hours'];
		$temp_op					= $newArray['zgl_osoba_przypisana'];
		$temp_kategoria				= $newArray['zgl_kategoria'];

		$temp_zgl_spr_data		= $newArray['zgl_sprawdzone_data'];
		$temp_zgl_spr_osoba		= $newArray['zgl_sprawdzone_osoba'];

		if ($KolorujWgStatusow==1) {
			switch ($temp_kategoria) {
				case "6": $kolorgrupy='#F73B3B'; tbl_tr_color_dblClick($i, $kolorgrupy); break;			
				case "2": $kolorgrupy='#FF7F2A'; tbl_tr_color_dblClick($i, $kolorgrupy); break; 
				case "3": $kolorgrupy='#FFFFFF'; tbl_tr_color_dblClick($i, $kolorgrupy); break; 
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
		
		echo "<td class=center>";
		
			if ($temp_status==9) {
				if (($temp_zgl_spr_data!='0000-00-00 00:00:00') && ($temp_zgl_spr_data!='')) {
					echo "<a href=# onClick=\"alert('Zgłoszenie nr $temp_nr zostało sprawdzone przez $temp_zgl_spr_osoba w dniu ".substr($temp_zgl_spr_data,0,16)."');\"title='Zgłoszenie nr $temp_nr zostało sprawdzone przez $temp_zgl_spr_osoba w dniu ".substr($temp_zgl_spr_data,0,16)."'><input class=imgoption type=image src=img/zgl_checked.gif></a>&nbsp;";
				}
			}
			
			echo "<a class=normalfont href=# title='Przejdź do zgłoszenia numer ".$temp_nr."' onClick=\"self.location.href='hd_p_zgloszenia.php?sr=search-wyniki&search_zgl_nr=".$temp_nr."'\">$temp_nr</a>";
			
			if ($_REQUEST[wyj]=='1') {
				list($countf)=mysql_fetch_array(mysql_query("SELECT COUNT(zgl_szcz_byl_wyjazd) FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$temp_nr') AND (zgl_szcz_byl_wyjazd=1)", $conn_hd));
				if ($countf>=1) { 
					echo "&nbsp;<img class=imgoption src=img/car_ww_s.gif border=0 width=12 height=12>"; 
					$wyjazdowe_razem++;
				}
			}
	
		echo "</td>";
		echo "<td class=center>$temp_poczta</td>";	
		echo "<td class=center>$temp_data";

				// okienka ostrzegawcze | POCZĄTEK
				
				//list($temp_zgl_data_rozpoczecia, $temp_zgl_data_zakonczenia, $temp_zgl_E1P, $temp_zgl_E2P, $temp_zgl_E3P, $temp_zgl_komorka_working_hours,$temp_op, $temp_kategoria, $temp_status)=mysql_fetch_array(mysql_query("SELECT zgl_data_rozpoczecia, zgl_data_zakonczenia, zgl_E1P, zgl_E2P, zgl_E3P, zgl_komorka_working_hours, zgl_osoba_przypisana, zgl_kategoria, zgl_status FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id=$temp_nr) LIMIT 1", $conn_hd));	
				
				$__zgl_nr 		= $temp_nr;
				$__zgl_data_r 	= $temp_zgl_data_rozpoczecia;
				$__zgl_data_z	= $temp_zgl_data_zakonczenia;
				$__zgl_e1p		= $temp_zgl_E1P;
				$__zgl_e2p		= $temp_zgl_E2P;
				$__zgl_e3p		= $temp_zgl_E3P;
				$__zgl_kwh		= $temp_zgl_komorka_working_hours;
				$__zgl_op		= $temp_op;
				$__zgl_kat		= $temp_kategoria;
				$__zgl_status	= $temp_status;
				
				$__wersja		= 1;				// 1 - div, 2 - h2
				$__add_refresh	= 0;
				$__add_br		= 0;
				$__tunon		= $turnon__hd_p_zgloszenia;
				
				if ($__tunon) include('warning_messages.php');
				// okienka ostrzegawcze | KONIEC
				
		echo "</td>";
		echo "<td>$temp_komorka</td>";
		echo "<td>"; 
		echo nl2br(wordwrap($temp_temat, 78, "<br />"));
		echo "</td>";
		echo "<td class=center>$temp_czas minut</td>";
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
				echo "<a class=normalfont href=# title=' Przejdź do obsługi zgłoszenia nr $temp_nr '  onclick=\"newWindow_r(800,600,'hd_o_zgloszenia.php?action=obsluga&nr=$temp_nr&zgl_s=$zgl_ser'); return false;\">";
				echo "<input class=imgoption type=image src=img/hd_obsluga_start.gif>";
				echo "</a>";
			} else {
				if (($kierownik_nr==$es_nr) || ($is_dyrektor==1)) {
					echo "<a class=normalfont href=# title=' Edycja zgłoszenia nr $temp_nr ' onclick=\"newWindow_r(800,600,'hd_o_zgloszenia.php?action=obsluga&nr=$temp_nr&zgl_s=$zgl_ser'); return false;\">";
					echo "<input class=imgoption type=image src=img/hd_podglad.gif>";
					echo "</a>";				
				} else {
					echo "<a class=normalfont href=# title=' Podgląd zgłoszenia nr $temp_nr '  onclick=\"newWindow_r(800,600,'hd_o_zgloszenia.php?action=podglad&nr=$temp_nr&zgl_s=$zgl_ser'); return false;\">";
					echo "<input class=imgoption type=image src=img/hd_podglad.gif>";
					echo "</a>";
				}
			}
		echo "</td>";
		
		$czas_razem+=$temp_czas;
		$zgl_razem++;
		
		
		_tr();
		$i++;
	}	
	
	echo "</table>";
	
	echo "<form action=do_xls_htmlexcel_hd_g_raport_okresowy.php METHOD=POST target=_blank>";	
	startbuttonsarea("right");

	startbuttonsarea("left");
	if ($_REQUEST[wyj]=='1') {
		echo "<br />&nbsp;Łączna ilosć zgłoszeń wyjazdowych w wybranym okresie: <b>$wyjazdowe_razem</b>";
	}
	echo "<br />&nbsp;Łączna ilość zgłoszeń w wybranym okresie: <b>$zgl_razem</b>";
	echo "<br />&nbsp;Łączny czas poświęcony w wybranym okresie: <b>".minutes2hours($czas_razem,'')."</b>";

	echo "<input type=hidden name=g_zgl_razem value='$zgl_razem'>";
	echo "<input type=hidden name=g_czas_razem value='".minutes2hours($czas_razem,'')."'>";

	endbuttonsarea();

	if ($_REQUEST[readonly]!=1) {
		echo "<span style='float:left;'>";
		addlinkbutton("'Zmień kryteria'","main.php?action=hdgro&okres_od=$okres_od&okres_do=$okres_do&tuser=".urlencode($tuser)."&nk=".urlencode($_REQUEST[nk])."&wyj=$_REQUEST[wyj]&tstatus=$tstatus&kategoria=$kategoria&podkategoria=$podkategoria&priorytet=$priorytet&potw_spr=$_REQUEST[potw_spr]");
		echo "</span>";
	}
	
		addownlinkbutton("'Nowe zgłoszenie'","Button1","button","newWindow_r(800,600,'hd_d_zgloszenie_simple.php?stage=1')");
		//addownlinkbutton("'Nowe zgłoszenie seryjne'","Button1","button","newWindow_r(800,600,'hd_d_zgloszenie_s.php?stage=1&filtr=X-X-X-X&p6=true')");
		echo " | ";
		addlinkbutton("'Przeglądaj zgłoszenia'","hd_p_zgloszenia.php?showall=0&p5=BZ&sort=AD&page=1");
		echo " | ";
		
		echo "<input class=buttons type=submit style='color:blue;' value='Export do XLS'>";
			
		echo "<input type=hidden name=g_okres_od value='$_REQUEST[okres_od]'>";
		echo "<input type=hidden name=g_okres_do value='$_REQUEST[okres_do]'>";
		echo "<input type=hidden name=g_tzgldata value='$_REQUEST[tzgldata]'>";
//		echo ">>>>>".$sql;
		echo "<input type=hidden name=zapytanie value=\"$sql\" >";
		
	//addlinkbutton("'Przeglądaj towary'","p_towary_dostepne.php?view=normal&wybor=".urlencode($wybor)."");

	
	if ($_REQUEST[readonly]!=1) addbuttons("start");
	if ($_REQUEST[readonly]==1) addbuttons("zamknij");

	endbuttonsarea();	
	echo "</form>";

	?>
	<script>HideWaitingMessage();</script>
	<?php 
} else {

		errorheader("Nie znaleziono zgłoszeń spełniających podane przez Ciebie kryteria");
		startbuttonsarea("right");
		addlinkbutton("'Zmień kryteria'","main.php?action=hdgro&okres_od=$okres_od&okres_do=$okres_do&tuser=".urlencode($tuser)."&tuser=".urlencode($_REQUEST[nk])."&wyj=$_REQUEST[wyj]&tstatus=$tstatus&kategoria=$kategoria&podkategoria=$podkategoria&priorytet=$priorytet");
		addbuttons("start");
		endbuttonsarea();	

}

	include('body_stop.php');
	//echo "</body></html>";
	
} else {
br();
pageheader("Generowanie raportu okresowego z bazy Helpdesk");
starttable("650px");
echo "<form name=ruch action=hd_g_raport_okresowy.php method=GET>";
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
	
	$r1 = Date('Y');
	$m1 = Date('m');
	$d1 = '01';
	
	$r2 = Date('Y');
	$m2 = Date('m');
	
	if ($m2==1) $d2=31;
	if ($m2==2) $d2=29;
	if ($m2==3) $d2=31;
	if ($m2==4) $d2=30;
	if ($m2==5) $d2=31;
	if ($m2==6) $d2=30;
	if ($m2==7) $d2=31;
	if ($m2==8) $d2=31;
	if ($m2==9) $d2=30;
	if ($m2==10) $d2=31;
	if ($m2==11) $d2=30;
	if ($m2==12) $d2=31;

	$data1=$r1.'-'.$m1.'-'.$d1;
	$data2=$r1.'-'.$m1.'-'.$d2;
	
	
	if ($_GET[okres_od]!='') $data1 = $_GET[okres_od];
	if ($_GET[okres_do]!='') $data2 = $_GET[okres_do];
		td_colspan(1,'c'); echo "&nbsp;"; _td();
		td_img(";l");
			echo "<input class=wymagane size=10 maxlength=10 type=text id=okres_od name=okres_od value=$data1 onkeypress='return handleEnter(this, event);' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\">";
			echo "<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif width=16 height=16 border=0></a>";
			if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('okres_od').value='".Date('Y-m-d')."'; return false;\">";			
		_td();
		td_img(";l");
			echo "<input class=wymagane size=10 maxlength=10 type=text id=okres_do name=okres_do value=$data2 onkeypress='return handleEnter(this, event);' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\">";
			echo "<a tabindex=-1 href=javascript:cal11.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif width=16 height=16 border=0></a>";
			if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('okres_do').value='".Date('Y-m-d')."'; return false;\">";
		_td();	
	_tr();
	tr_();
		td_colspan(1,'r');
			echo "<b>Wybrany zakres dat dotyczy</b>";
		_td();
		td_colspan(1,'c'); echo "&nbsp;"; _td();
		td_colspan(2,'l');
			echo "<select name=tzgldata onkeypress='return handleEnter(this, event);'>\n"; 					 				
			echo "<option value='data_utworzenia'"; if ($_GET[tzgldata]=='data_utworzenia') echo " SELECTED ";echo ">Dat utworzenia zgłoszenia</option>\n"; 
			echo "<option value='data_modyfikacji' "; if ($_GET[tzgldata]=='data_modyfikacji') echo " SELECTED ";echo ">Dat  zmian statusu zgłoszeń</option>\n"; 
			echo "</select>\n";
		_td();
	_tr();		
	tbl_empty_row();
	tr_();
		td_colspan(1,'r');
			echo "<b>Wybierz pracownika</b>";
		_td();
		td_colspan(1,'c'); echo "&nbsp;"; _td();
		td_colspan(2,'l');
			$result6 = mysql_query("SELECT user_first_name, user_last_name FROM $dbname.serwis_uzytkownicy WHERE belongs_to=$es_filia ORDER BY user_first_name", $conn) or die($k_b);
			echo "<select name=tuser onkeypress='return handleEnter(this, event);'>\n"; 					 				
			echo "<option value='all'"; if ($_GET[tuser]=='all') echo " SELECTED "; echo ">Wszyscy z bieżącej filii";
			while (list($temp_imie,$temp_nazwisko) = mysql_fetch_array($result6)) {
				echo "<option value='$temp_imie $temp_nazwisko'"; 
				$iin = $temp_imie.' '.$temp_nazwisko;
				if ($_GET[tuser]==$iin) echo " SELECTED "; echo ">$temp_imie $temp_nazwisko</option>\n"; 
			}
			echo "</select>\n";
		_td();
	_tr();
	tbl_empty_row();
	tr_();
		td_colspan(1,'r');
			echo "<b>Uwzględnij zgłoszenia</b>";
		_td();
		td_colspan(1,'c'); echo "&nbsp;"; _td();
		td_colspan(2,'l');
			echo "<select name=tstatus onkeypress='return handleEnter(this, event);'>\n"; 					 				
			echo "<option value='0'"; if ($_GET[tstatus]=='0') echo " SELECTED ";echo ">Wszystkie</option>\n"; 
			echo "<option value='9' "; if ($_GET[tstatus]=='9') echo " SELECTED ";echo ">Tylko zakończone</option>\n"; 
			echo "</select>\n";
		_td();
	_tr();	
	
	tbl_empty_row();
	tr_();
		td_colspan(1,'r');
			echo "<b>Dla komórki</b>";
		_td();
		td_colspan(1,'c'); echo "&nbsp;"; _td();
		td_colspan(2,'l');
			// wg kategorii
			echo "<input type=text name=nk id=nk maxlength=60 size=50";
			if ($_REQUEST[nk]!='') echo " value='".$_REQUEST[nk]."' ";
			echo ">";			
			echo "<br />pozostaw puste, aby wziąć wszystkie komórki do raportu";
		_td();
		
	_tr();
	tr_();
		td_colspan(1,'r');
			echo "<b>Oznacz wyjazdowe</b>";
		_td();
		td_colspan(1,'c'); echo "&nbsp;"; _td();
		td_colspan(2,'l');
			echo "<select name=wyj id=wyj>";
			echo "<option value='1'"; if ($_REQUEST[wyj]=='1') echo " SELECTED "; echo ">TAK</option>\n";
			echo "<option value='0'"; if (($_REQUEST[wyj]=='0') || ($_REQUEST[wyj]=='')) echo " SELECTED "; echo ">NIE</option>\n";
			echo "</select>";
		_td();
		
	_tr();
	
	tbl_empty_row();
	tr_();
		td_colspan(1,'r');
			echo "<b>Kategoria zgłoszeń</b>";
		_td();
		td_colspan(1,'c'); echo "&nbsp;"; _td();
		td_colspan(2,'l');
			// wg kategorii
			echo "<select name=kategoria>";
			echo "<option value='' SELECTED>-wszystkie-</option>\n";			
			$sql_f1="SELECT DISTINCT(hd_kategoria.hd_kategoria_opis), hd_kategoria.hd_kategoria_nr, hd_zgloszenie.zgl_kategoria FROM $dbname_hd.hd_kategoria, $dbname_hd.hd_zgloszenie WHERE (hd_kategoria.hd_kategoria_wlaczona=1) and (hd_zgloszenie.zgl_kategoria=hd_kategoria.hd_kategoria_nr) ORDER BY hd_kategoria_display_order ASC";
			//echo "$sql_f1";
			$result_f1=mysql_query($sql_f1,$conn) or die($k_b);
			
			while ($dane_f1=mysql_fetch_array($result_f1)) {
				$temp_nr = $dane_f1['hd_kategoria_nr'];
				$temp_opis = $dane_f1['hd_kategoria_opis'];
				echo "<option value='$temp_nr'"; if ($_GET[kategoria]==$temp_nr) echo " SELECTED ";echo ">$temp_opis</option>\n";
			}
			echo "</select>";
	
		_td();
	_tr();
	
	tr_();
		td_colspan(1,'r');
			echo "<b>Podkategoria zgłoszeń</b>";
		_td();
		td_colspan(1,'c'); echo "&nbsp;"; _td();
		td_colspan(2,'l');

			echo "<select name=podkategoria>";
			echo "<option value='' SELECTED>-wszystkie-</option>\n";
			$sql_f1="SELECT DISTINCT(hd_podkategoria.hd_podkategoria_opis), hd_podkategoria.hd_podkategoria_nr, hd_zgloszenie.zgl_podkategoria FROM $dbname_hd.hd_podkategoria, $dbname_hd.hd_zgloszenie WHERE (hd_podkategoria.hd_podkategoria_wlaczona=1) and (hd_zgloszenie.zgl_podkategoria=hd_podkategoria.hd_podkategoria_nr) ORDER BY hd_podkategoria_opis ASC";
			$result_f1=mysql_query($sql_f1,$conn) or die($k_b);
			
			while ($dane_f1=mysql_fetch_array($result_f1)) {
				$temp_nr = $dane_f1['hd_podkategoria_nr'];
				$temp_opis = $dane_f1['hd_podkategoria_opis'];
				echo "<option value='$temp_nr'"; if ($_GET[podkategoria]==$temp_nr) echo " SELECTED ";echo ">$temp_opis</option>\n";
			}
			echo "</select>";
	
		_td();
	_tr();	

	echo "<tr style='display:none'>";
		td_colspan(1,'r');
			echo "<b>Priorytet zgłoszeń</b>";
		_td();
		td_colspan(1,'c'); echo "&nbsp;"; _td();
		td_colspan(2,'l');

		echo "<select name=priorytet>";
		echo "<option value='' SELECTED>-wszystkie-</option>\n";
		$sql_f1="SELECT DISTINCT(hd_priorytet.hd_priorytet_opis), hd_priorytet.hd_priorytet_nr, hd_zgloszenie.zgl_priorytet FROM $dbname_hd.hd_priorytet, $dbname_hd.hd_zgloszenie WHERE (hd_priorytet.hd_priorytet_wlaczona=1) and (hd_zgloszenie.zgl_priorytet=hd_priorytet.hd_priorytet_nr) ORDER BY hd_priorytet_nr ASC";
		
		$result_f1=mysql_query($sql_f1,$conn) or die($k_b);
		
		while ($dane_f1=mysql_fetch_array($result_f1)) {
			$temp_nr = $dane_f1['hd_priorytet_nr'];
			$temp_opis = $dane_f1['hd_priorytet_opis'];
			echo "<option value='$temp_nr'"; if ($_GET[priorytet]==$temp_nr) echo " SELECTED ";echo ">$temp_opis</option>\n";
		}
		echo "</select>";
	
		_td();
	_tr();		
	
	tr_();
		td_colspan(1,'r');
			echo "<b>Potwierdzone sprawdzenie</b>";
		_td();
		td_colspan(1,'c'); echo "&nbsp;"; _td();
		td_colspan(2,'l');

			echo "<select name=potw_spr>";
			echo "<option value='' ";
			if ($_REQUEST[potw_spr]=='') echo " SELECTED ";
			echo " >-wszystkie-</option>\n";
				echo "<option value='1'"; 
				if ($_REQUEST[potw_spr]=='1') echo " SELECTED ";
				echo ">TAK</option>\n";
				echo "<option value='0'"; 
				if ($_REQUEST[potw_spr]=='0') echo " SELECTED ";
				echo ">NIE</option>\n";

			echo "</select>";
	
		_td();
	_tr();	
	
tbl_empty_row();
endtable();
startbuttonsarea("center");
addownsubmitbutton("'Generuj raport'","submit");
echo "<input type=reset class=buttons value='Kryteria domyślne'>";
endbuttonsarea();
_form();	
?>
<script language="JavaScript" type="text/javascript">
	var frmvalidator  = new Validator("ruch");
	frmvalidator.addValidation("okres_od","req","Nie podałeś daty początkowej");  
	frmvalidator.addValidation("okres_do","req","Nie podałeś daty końcowej");  
	frmvalidator.addValidation("okres_od","numerichyphen","Użyłeś niedozwolonych znaków w polu \"od dnia\"");
	frmvalidator.addValidation("okres_do","numerichyphen","Użyłeś niedozwolonych znaków w polu \"do dnia\"");
</script>
<?php }
?>
</body>
</html>