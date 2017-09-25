<?php include_once('header.php'); ?>
<body>
<?php
if ($es_m==1) {
	$result = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni,magazyn_uwagi_sa,magazyn_uwagi,magazyn_status,magazyn_osoba_rezerwujaca,magazyn_data_rezerwacji,belongs_to FROM $dbname.serwis_magazyn WHERE magazyn_status!='3' ORDER BY magazyn_nazwa", $conn) or die($k_b);
} else {

	if ($wybierz_typ=='') {
		$result = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni,magazyn_uwagi_sa,magazyn_uwagi,magazyn_status,magazyn_osoba_rezerwujaca,magazyn_data_rezerwacji,belongs_to FROM $dbname.serwis_magazyn WHERE magazyn_status!='3' and belongs_to=$es_filia ORDER BY magazyn_nazwa ASC", $conn) or die($k_b);
	} else {
		$result = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni,magazyn_uwagi_sa,magazyn_uwagi,magazyn_status,magazyn_osoba_rezerwujaca,magazyn_data_rezerwacji,belongs_to FROM $dbname.serwis_magazyn WHERE magazyn_status!='3' and belongs_to=$es_filia and (magazyn_nazwa='$wybierz_typ') ORDER BY magazyn_nazwa ASC", $conn) or die($k_b);
	}

//$result = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni,magazyn_uwagi_sa,magazyn_uwagi,magazyn_status,magazyn_osoba_rezerwujaca,magazyn_data_rezerwacji,belongs_to FROM $dbname.serwis_magazyn WHERE magazyn_status!='3' and belongs_to=$es_filia ORDER BY magazyn_nazwa", $conn) or die($k_b);
}
if (mysql_num_rows($result)!=0) {
	br();
	pageheader("Przeglądanie całego sprzętu serwisowego",1);
	
	startbuttonsarea("center");
	echo "<span style='float:left'>";
	//echo "&nbsp;&nbsp;";
	if ($kolor!=1) {
		echo "<input id=with_color type=button class=buttons value='Koloruj wg typu' onClick=\"self.location.href='main.php?action=cm&wybierz_typ=$wybierz_typ&kolor=1'\" />";
	} else {
		echo "<input id=without_color type=button class=buttons value='Bez kolorowania wg typu' onClick=\"self.location.href='main.php?action=cm&wybierz_typ=$wybierz_typ&kolor=0'\" />";
	}
	echo "</span>";
	
	echo "<form name=magazyn action=main.php method=GET>";
	echo "Pokaż: ";
	echo "<select name=wybierz_typ onChange='document.location.href=document.magazyn.wybierz_typ.options[document.magazyn.wybierz_typ.selectedIndex].value'>";
		$sql_lista_p = "SELECT DISTINCT magazyn_nazwa FROM $dbname.serwis_magazyn WHERE (belongs_to=$es_filia) and (magazyn_status!='3') ORDER BY magazyn_nazwa";
		$wynik_lista_typ = mysql_query($sql_lista_p,$conn) or die($k_b);
		echo "<option ";
		if ($wybierz_typ=='') echo "SELECTED ";
		echo "value='main.php?action=cm&wybierz_typ=&kolor=$kolor'>Cały sprzęt</option>\n";	
		while (list($temp_magazyn_nazwa)=mysql_fetch_array($wynik_lista_typ)) {
			echo "<option "; 
			if ($wybierz_typ==$temp_magazyn_nazwa) echo "SELECTED ";
			echo "value='main.php?action=cm&wybierz_typ=".urlencode($temp_magazyn_nazwa)."&kolor=$kolor'>$temp_magazyn_nazwa</option>\n";	
		}
	echo "</select>";
	echo "<input type=hidden name=action value=cm>";
	echo "<input type=hidden name=kolor value=$kolor>";
	
	echo "</form>";
	
	endbuttonsarea();	
	
	starttable();
	if ($es_m==1) { 
		th("30;c;LP|;;Typ sprzętu|;;Model|;;Numer seryjny|;;Numer inwentarzowy|;c;Uwagi<br />Filia|;c;Status<br />Informacje dodatkowe|;c;Opcje;019",$es_prawa);
	} else {
		th("30;c;LP|;;Typ sprzętu|;;Model|;;Numer seryjny|;;Numer inwentarzowy|;c;Uwagi|;c;Status<br />Informacje dodatkowe|;c;Opcje;019",$es_prawa);
	}
	$i = 0;
	while (list($mid,$mnazwa,$mmodel,$msn,$mni,$muwagi1,$muwagi,$mstatus,$mor,$dr,$bt) = mysql_fetch_array($result)) {
		
		if ($_GET[kolor]!=1) {
			tbl_tr_highlight($i);
			//tbl_tr_highlight_dblClick_z_magazyn_ukryj($i);
		} else {
			list($kolorgrupy)=mysql_fetch_array(mysql_query("SELECT rola_kolor FROM $dbname.serwis_slownik_rola WHERE rola_nazwa='$mnazwa' LIMIT 1",$conn));
			//tbl_tr_highlight_dblClick_e_magazyn_pobierz_kolor($i,$kolorgrupy);
			tbl_tr_highlight_kolor($i,$kolorgrupy);
		}
		
			$i++;
			//td("30;c;".$i."");
			td("30;c;<a class=normalfont href=# title=' $mid '>".$i."</a>");
			td_(";;".$mnazwa."");
				if ($pokazpoczatekuwag==1) pokaz_uwagi($muwagi,$iloscznakowuwag,"newWindow(480,265,'p_magazyn_uwagi.php?id=$mid'); return false;");
			_td();
			td(";;".$mmodel."|;;".$msn."|;;".$mni."");
			td_img(";c");
				if ($muwagi1=='1') { echo "<a title='Czytaj uwagi '><input type=image class=imgoption src=img/comment.gif onclick=\"newWindow(480,265,'p_magazyn_uwagi.php?id=$mid'); return false;\"></a>";}
				echo "<a title=' Edytuj uwagi '><input type=image class=imgoption src=img/edit_comment.gif onclick=\"newWindow(480,245,'e_magazyn_uwagi.php?id=$mid')\"></a>";
				if ($es_m==1) include("p_filia_nazwa.php");
			_td();
			
					$histsql = "SELECT historia_up, historia_user,historia_data,historia_komentarz FROM $dbname.serwis_historia WHERE historia_magid=$mid ORDER BY historia_data DESC LIMIT 1";
					$histwynik = mysql_query($histsql,$conn) or die($k_b);
					list($temp1_up,$temp1_user,$temp1_data,$temp1_uwagi)=mysql_fetch_array($histwynik);
					
					$sql_up = "SELECT up_id, up_pion_id FROM $dbname.serwis_komorki WHERE (up_nazwa='$temp1_up') and (belongs_to=$es_filia) LIMIT 1";
					$wynik = mysql_query($sql_up, $conn) or die($k_b);
					$dane_up = mysql_fetch_array($wynik);
					$temp_up_id = $dane_up['up_id'];
					$temp_pion_id = $dane_up['up_pion_id'];
					// nazwa pionu z id pionu
					$sql_get_pion = "SELECT pion_nazwa FROM $dbname.serwis_piony WHERE (pion_id='$temp_pion_id') LIMIT 1";
					$wynik_get_pion = mysql_query($sql_get_pion, $conn) or die($k_b);
					$dane_get_pion = mysql_fetch_array($wynik_get_pion);
					$temp_pion_nazwa = $dane_get_pion['pion_nazwa'];
					// koniec ustalania nazwy pionu
					
					$nup = $temp1_up;
					$pelnanazwakomorki = $temp_pion_nazwa." ".$nup;
					
			if ($mstatus=='0') td(";c;na stanie"); 
			if ($mstatus=='1') { 
				td_(";c;");	
					echo "<a href=# class=normalfont title=' Sprzęt pobrany ".substr($temp1_data,0,16)." do ".$temp_pion_nazwa." ".$temp1_up." przez ".$temp1_user." ' onclick=\"newWindow_r(700,600,'p_komorka_szczegoly.php?id=$temp_up_id'); return false;\">pobrano<br /><b>".skroc_tekst($temp_pion_nazwa." ".$temp1_up,35)."</b></a>";
				_td();
			}
			if ($mstatus=='2') { td_(";c"); echo "<a href=# class=normalfont title=' Data rezerwacji : $dr ' onClick='return false'><b>rezerwacja</b><br />( $mor )</a>"; _td(); }
			if ($mstatus=='3') td(";c;ukryty");

			td_img(";c;");				
			
				// przebudowana wersja przycisków
				
				// edycja i usunięcie sprzętu z magazynu
				if (($mstatus=='0') && ($es_prawa==9)) {
					echo "<a title=' Edytuj dane o $mnazwa $mmodel o numerze seryjnym $msn '><input class=imgoption type=image src=img/edit.gif  onclick=\"newWindow(480,220,'e_magazyn.php?id=$mid')\"></a>";		
					echo "<a title=' Usuń $mnazwa $mmodel o numerze seryjnym $msn z magazynu '><input class=imgoption type=image src=img/delete.gif onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_magazyn.php?id=$mid')\"></a>";
				}
				
				// jeżeli sprzęt pobrany
				if ($mstatus==1) {
					// pobranie id ewentualnej naprawy + status naprawy + nr zgłoszenia helpdesk
					//echo "SELECT naprawa_id,naprawa_status,naprawa_hd_zgl_id FROM $dbname.serwis_naprawa WHERE (naprawa_status<9) and (naprawa_sprzet_zastepczy_id=$mid) ORDER BY naprawa_id DESC LIMIT 1";
					
					$result_a4 = mysql_query("SELECT naprawa_id,naprawa_status,naprawa_hd_zgl_id FROM $dbname.serwis_naprawa WHERE (naprawa_status<5) and (naprawa_sprzet_zastepczy_id=$mid) ORDER BY naprawa_id DESC LIMIT 1", $conn) or die($k_b);
					list($nid1, $nstatus1, $n_zgl_id1) = mysql_fetch_array($result_a4);					
					
					//echo "$nid1, $nstatus1, $n_zgl_id";
					
					$nid = (int) $nid1;
					$nstatus = (int) $nstatus1;	
					$n_zgl_id = (int) $n_zgl_id1;
					
					//echo "$nid, $nstatus, $n_zgl_id";
					
					// jeżeli sprzęt jest powiązany z naprawą + helpdesk:
					if (($nid>0) && ($n_zgl_id>0)) {						
						
					}
					
					// jeżeli sprzęt powiązany z naprawą, a nr helpdesk = 0
					if (($nid>0) && ($n_zgl_id==0)) {				
						// próba ustalenia numeru zgłoszenia z tabeli ze zgłoszeniami						
						$result_a4 = mysql_query("SELECT zgl_nr FROM  $dbname_hd.hd_zgloszenie WHERE (zgl_naprawa_id=$nid) LIMIT 1", $conn) or die($k_b);
						list($n_zgl_id) = mysql_fetch_array($result_a4);
					}
					
					if (($nid==0) && ($n_zgl_id==0)) {					
						// próba ustalenia numeru zgłoszenia z tabeli ze zgłoszeniami (zgl_sprzet_serwisowy_id)
						$result_a4 = mysql_query("SELECT zgl_nr FROM  $dbname_hd.hd_zgloszenie WHERE (zgl_sprzet_serwisowy_id=$mid) LIMIT 1", $conn) or die($k_b);
						list($n_zgl_id) = mysql_fetch_array($result_a4);
						
						if ($n_zgl_id>0) $n_zgl_id_po_ss_id = 1;
						
					} else $n_zgl_id_po_ss_id = 0;
					
					if ($nid>0) {
						echo "<a title='Pobierz sprzęt serwisowy $mnazwa $mmodel (SN: $msn) z $temp_pion_nazwa $nup '><input class=imgoption type=image src=img//wycofaj_z_komorki.gif onclick=\"newWindow_r(700,595,'z_naprawy_wycofaj_sprzet_serwisowy.php?id=$nid&sz=$mid&tup=".urlencode($nup)."&hd_zgl_nr=$n_zgl_id&from=hd&up=".urlencode($pelnanazwakomorki)."')\"></a>";
						
						$statusn = $nstatus;
						$naprawaid = $nid;

						if ($statusn==-1) echo "<a href=main.php?action=npus&id=$naprawaid title=' Sprzęt pobrany od klienta - na stanie '><img class=imgoption src=img/snapraw_p.gif border=0 width=16 width=16></a>";	
						if ($statusn==0) echo "<a href=main.php?action=nwwz&id=$naprawaid title=' Sprzęt naprawiany we własnym zakresie '><img class=imgoption src=img/w_naprawie_wz.gif border=0 width=16 width=16></a>";	
						if ($statusn==1) echo "<a href=main.php?action=npswsz&id=$naprawaid title=' Sprzęt naprawiany w serwisie zewnętrznym '><img class=imgoption src=img/w_naprawie_sz.gif border=0 width=16 width=16></a>";
						if ($statusn==2) echo "<a href=main.php?action=nsnrl&id=$naprawaid title=' Sprzęt naprawiany na rynku lokalnym '><img class=imgoption src=img/w_naprawie_sl.gif border=0 width=16 width=16></a>";
						if ($statusn==3) echo "<a href=p_naprawy_zakonczone.php?id=$naprawaid title=' Sprzęt naprawiony - na stanie '><img class=imgoption src=img/snapraw_ok.gif border=0 width=16 width=16></a>";		
						if ($statusn==5) {
							
						if ($n_zgl_id>0) {
							echo "<a title=' Zwróć $mnazwa $mmodel o numerze seryjnym $msn na magazyn '><input class=imgoption type=image src=img//return.gif onclick=\"newWindow_r(700,595,'z_magazyn_zwrot.php?id=$mid&part=".urlencode($mnazwa)."&powiazzhd=0&hd_zgl_nr=".$n_zgl_id."&from=hd'); return false;\"></a>";
						} else {
							echo "<a title=' Zwróć $mnazwa $mmodel o numerze seryjnym $msn na magazyn '><input class=imgoption type=image src=img//return.gif onclick=\"newWindow_r(700,595,'z_magazyn_zwrot.php?id=$mid&part=".urlencode($mnazwa)."&powiazzhd=1'); return false;\"></a>";
						}
							
						}
						if ($statusn==7) echo "<a href=main.php?action=nsw&id=$naprawaid title=' Sprzęt wycofany z serwisu - na stanie '><img class=imgoption src=img/snapraw7.gif border=0 width=16 width=16></a>";
						if ($statusn==8) echo "<a title=' Zwróć $mnazwa $mmodel o numerze seryjnym $msn na magazyn '><input class=imgoption type=image  src=img//return.gif  onclick=\"newWindow_r(700,595,'z_magazyn_zwrot.php?id=$mid&part=".urlencode($mnazwa)."&powiazzhd=1'); return false;\"></a>";						
					
					}
					
					if ($n_zgl_id>0) {
						$LinkHDZglNr=$n_zgl_id; include('linktohelpdesk.php');						
					}
					
					if ((($nid==0) && ($n_zgl_id==0)) || ($n_zgl_id_po_ss_id==1)) {
						if ($n_zgl_id_po_ss_id==0) {
							echo "<a title='Zwróć $mnazwa $mmodel o numerze seryjnym $msn na magazyn'><input class=imgoption type=image src=img//return.gif onclick=\"newWindow_r(700,595,'z_magazyn_zwrot.php?id=$mid&part=".urlencode($mnazwa)."&powiazzhd=1'); return false;\"></a>";
						} else {
							echo "<a title='Zwróć $mnazwa $mmodel o numerze seryjnym $msn na magazyn'><input class=imgoption type=image src=img//return.gif onclick=\"newWindow_r(700,595,'z_magazyn_zwrot.php?id=$mid&part=".urlencode($mnazwa)."&powiazzhd=0&hd_zgl_nr=".$n_zgl_id."&from=hd'); return false;\"></a>";
							
							$n_zgl_id_po_ss_id = 0;
						}
					}
					
				}
				// jeżeli jest zarezerwowany
				if (($mstatus=='2') && (($currentuser==$mor) || ($kierownik_nr==$es_nr))) {
					echo "<a title=' Zwolnij sprzęt $mnazwa $mmodel o numerze seryjnym $msn '><input class=imgoption type=image src=img/zwolnij.gif onclick=\"newWindow($dialog_window_x,$dialog_window_y,'z_magazyn_zwolnij.php?select_id=$mid')\"></a>";
				}
				
				if ($mstatus=='0') echo "<a title=' Pobierz $mnazwa $mmodel o numerze seryjnym $msn magazynu '><input class=imgoption type=image src=img/pobierz.gif onclick=\"newWindow_r(700,595,'z_magazyn_pobierz.php?id=$mid&part=".urlencode($mnazwa)."&powiazzhd=1')\"></a>";
				
				// koniec 
				
			_td();
		_tr();
	}
	endtable();
} else
   { 
		br();
		errorheader("Baza sprzętu serwisowego jest pusta");
		?>
		<meta http-equiv="REFRESH" content="<?php echo $http_refresh; ?>;url=<?php echo "$linkdostrony";?>main.php">
		<?php
	}
	
startbuttonsarea("right");
oddziel();
echo "<span style='float:left'>";
echo "Pokaż: ";
//addlinkbutton("'Cały sprzęt'","main.php?action=cm&kolor=$kolor");
addlinkbutton("'Dostępny sprzęt'","main.php?action=asm&kolor=$kolor");
addlinkbutton("'Pobrany sprzęt'","main.php?action=sw&kolor=$kolor");
addlinkbutton("'Zarezerwowany sprzęt'","main.php?action=prs&kolor=$kolor");
addlinkbutton("'Ukryty sprzęt'","main.php?action=pus&kolor=$kolor");
echo "</span>";

addlinkbutton("'Przeglądaj zgłoszenia'","hd_p_zgloszenia.php?showall=0&p5=BZ&sort=AD&page=1");
addbuttons("start");
endbuttonsarea();
include('body_stop.php');
?>
</body>
</html>