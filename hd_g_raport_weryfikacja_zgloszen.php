<?php include_once('header.php'); ?>
<body>
<?php 

if ($submit) { 
	include('cfg_helpdesk.php');
	include('body_start.php');
	
	$tuser=$_GET[tuser];
	$okres_od1=$_GET[okres_od];
	$okres_do1=$_GET[okres_do];
	
	$status = $_GET[tstatus];
	
	if ($_REQUEST[tzgldata]=='data_utworzenia') {	
		$sql="SELECT * FROM $dbname_hd.hd_zgloszenie WHERE ";
		
		if (($es_m==1) || ($is_dyrektor==1) || ($_REQUEST[fromraport]==1)) { } else $sql=$sql."(hd_zgloszenie.belongs_to=$es_filia) and (hd_zgloszenie.zgl_widoczne=1) AND ";
		
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
		
		//if ($wybierz_p!='') $sql.="AND (serwis_komorki.up_pion_id=$wybierz_p) ";
		$sql=$sql."ORDER BY zgl_data ASC";
	}
	
	$result = mysql_query($sql, $conn_hd) or die($k_b);
	$count_rows = mysql_num_rows($result);
	//echo $sql." <br />";

	// paging
	$totalrows = $count_rows; 

	$rowpersite = $_REQUEST[rps111];
	
	if ($sa==0) { $rps=$rowpersite; } else $rps=10000;
	if(empty($page)){ $page = 1; }
	$limitvalue = $page * $rps - ($rps);

	echo "<input type=hidden name=rpsite id=rpsite value=$rps>";

	$sql=$sql." LIMIT $limitvalue, $rps";	
	
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
	if ($_REQUEST[podkategoria]!='') {
		$result6a = mysql_query("SELECT hd_podkategoria_opis FROM $dbname_hd.hd_podkategoria WHERE (hd_podkategoria_nr=$_REQUEST[podkategoria])", $conn) or die($k_b);
		list($nazwa_podkategorii) = mysql_fetch_array($result6a);
	}	
	$opis_tuser = '';
	if ($_REQUEST[tuser]=='all') { $opis_tuser='wszystkich'; } else { $opis_tuser = $_REQUEST[tuser]; }
	
	$_header = "Lista zgłoszeń ";
	if ($_REQUEST[kategoria]!='') $_header .= "w kategorii <font color=red>".$nazwa_kategorii."</font>";
	if ($_REQUEST[podkategoria]!='') {
		if ($_REQUEST[kategoria]!='') {
			$_header .= "-><font color=red>".$nazwa_podkategorii."</font>";
		} else {
			$_header .= "w podkategorii <font color=red>".$nazwa_podkategorii."</font>";			
		}
	}
	$_header .= " dla: <font color=red>".$opis_tuser."</font>";
	$_header .= " za okres <font color=red>".$_REQUEST[okres_od]."</font>-<font color=red>".$_REQUEST[okres_do]."</font>";
	
	pageheader($_header,0,1);
	
/*	if ($_REQUEST[readonly]!=1) {
		if ($_REQUEST[kategoria]!='') {
			if ($_REQUEST[podkategoria]!='') {
				pageheader("Lista zgłoszeń w kategorii <font color=red>$nazwa_kategorii->$nazwa_podkategorii</font> dla: <font color=red>$opis_tuser</font> za okres <font color=red>$_REQUEST[okres_od]</font>-<font color=red>$_REQUEST[okres_do]</font>",1,0);
			} else {
				pageheader("Lista zgłoszeń w kategorii <font color=red>$nazwa_kategorii</font> dla: <font color=red>$opis_tuser</font> za okres <font color=red>$_REQUEST[okres_od]</font>-<font color=red>$_REQUEST[okres_do]</font>",1,0);
			}
		} else {
			pageheader("Lista zgłoszeń dla: <font color=red>$opis_tuser</font> za okres <font color=red>$_REQUEST[okres_od]</font>-<font color=red>$_REQUEST[okres_do]</font>",1,1);
		}
	} else {
		if ($_REQUEST[kategoria]!='') {
			pageheader("Lista zgłoszeń w kategorii <font color=red>$nazwa_kategorii</font> dla: <font color=red>$opis_tuser</font> za okres <font color=red>$_REQUEST[okres_od]</font>-<font color=red>$_REQUEST[okres_do]</font>",1,0);
		} else {
			pageheader("Lista zgłoszeń dla: <font color=red>$opis_tuser</font> za okres <font color=red>$_REQUEST[okres_od]</font>-<font color=red>$_REQUEST[okres_do]</font>",1,0);
		}
	}
	*/
	?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();

	if ($count_rows>0) { include_once('paging_begin_hd.php'); echo "<br />"; }
	
	echo "<table class=maxwidth cellspacing=1 align=center>";
	
	echo "<tr class=hideme>";
	echo "<td colspan=6 style='vertical-align:top; padding:0px;'>";
	echo "<h6 class='hd_h6 hideme' style='left:-5px; margin-bottom:1px;'>Lista zgłoszeń ($totalrows)";
	echo "</h6>";
	
	echo "<tr>";
	echo "<th><center>Nr zgłoszenia</center></th>";
	echo "<th><center>Status</center></th>";
	echo "<th width=auto><center>Data utworzenia</center></th>";
	echo "<th style='align:left'>Placówka zgłaszająca</th>";

	echo "<th width=auto>Kategoria -> Podkategoria -> Podkategoria (poziom 2)</th>";
	echo "<th style='align:left'>Temat</th>";

	echo "</tr>";
	
	$i = 0;
	$j = $page*$rowpersite-$rowpersite+1;
	
	$czas_razem = 0;
	$zgl_razem = 0;
echo "<form name=weryfikacja onSubmit=\"return false;\">";	
	while ($newArray = mysql_fetch_array($result)) {
		$temp_id			= $newArray['zgl_id'];
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
		$temp_podkategoria			= $newArray['zgl_podkategoria'];
		$temp_podkategoria2			= $newArray['zgl_podkategoria_poziom_2'];
		
		$temp_zgl_spr_data			= $newArray['zgl_sprawdzone_data'];
		$temp_zgl_spr_osoba			= $newArray['zgl_sprawdzone_osoba'];
		$temp_czy_ww				= 	$newArray['zgl_wymagany_wyjazd'];

		$temp_nrawarii				= $newArray['zgl_poledodatkowe1'];
		$temp_zgl_seryjne			= $newArray['zgl_poledodatkowe2'];
		$temp_parent_zgl			= $newArray['zgl_kontynuacja_zgloszenia_numer'];
		$temp_dzs					= $newArray['zgl_data_zmiany_statusu'];
		$temp_rekl_czy_jest			= $newArray['zgl_czy_to_jest_reklamacyjne'];
		$temp_rekl_nr	 			= $newArray['zgl_nr_zgloszenia_reklamowanego'];
		$temp_rekl_czy_ma			= $newArray['zgl_czy_ma_zgl_reklamacyjne'];		
		$temp_naprawa_id			= $newArray['zgl_naprawa_id'];
		$temp_czy_pow_z_wp			= $newArray['zgl_czy_powiazane_z_wymiana_podzespolow'];
		$temp_zgl_komorka_working_hours = $newArray['zgl_komorka_working_hours'];
		$temp_zgl_data_rozpoczecia		= $newArray['zgl_data_rozpoczecia'];
		$temp_zgl_data_zakonczenia		= $newArray['zgl_data_zakonczenia'];
		$temp_zgl_r_km					= $newArray['zgl_razem_km'];
		
		$temp_nasz_czas_do_rozpoczecia = $temp_minut_do_rozpoczecia - $newArray['zgl_E1C'];
		$temp_nasz_czas_do_zakonczenia = $temp_minut_do_zakonczenia - ($newArray['zgl_E1C']+$newArray['zgl_E2C']);
			
		$temp_czy_ww				= 	$newArray['zgl_wymagany_wyjazd'];
		$temp_czy_ww_data			= $newArray['zgl_wymagany_wyjazd_data_ustawienia'];		$temp_czy_ww_data = substr($temp_czy_ww_data,0,16);
		$temp_czy_ww_osoba			= $newArray['zgl_wymagany_wyjazd_osoba_wlaczajaca'];
			
		$temp_zgl_E1P				= $newArray['zgl_E1P'];
		$temp_zgl_E2P				= $newArray['zgl_E2P'];
			
		$temp_ss_id			= $newArray['zgl_sprzet_serwisowy_id'];
		$temp_zgl_spr_data		= $newArray['zgl_sprawdzone_data'];
		$temp_zgl_spr_osoba		= $newArray['zgl_sprawdzone_osoba'];
			
			if ($KolorujWgStatusow==1) {
				
				switch ($temp_kategoria) {
					case 6: $kolorgrupy='#F73B3B'; tbl_tr_color_dblClick($i, $kolorgrupy); break;
					case 2:	$kolorgrupy='#FF7F2A'; tbl_tr_color_dblClick($i, $kolorgrupy); break; 
					default: if ($temp_status==9) { 
								$kolorgrupy='#FFFFFF'; 
								tbl_tr_highlight_dblClick($i);	
								//tbl_tr_color_dblClick($i, $kolorgrupy); 
								break; 
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
		
		echo "<td class=righttop>";
		
			echo "<span style='float:left; margin-top:2px;'>";

			echo "<input class='border0 hideme' type=checkbox name=markzgl$i id=markzgl$i value=$temp_id onClick=\"SelectTRById($i);\" />";
			echo "<input type=hidden name=K$temp_id id=K$temp_id value='$temp_kategoria' />";
			echo "<input type=hidden name=ID_$i id=ID_$i value='$temp_id' />";
			echo "</span>";
				
			echo "<span style='float:left; margin-top:2px;'>";
					
			echo "<a class=normalfont href=# ";
			echo " onclick=\"newWindow_r($_COOKIE[max_x],$_COOKIE[max_y],'hd_p_zgloszenie_kroki.php?newwindow=1&nr=$temp_nr&id=$temp_nr&searchtext=".urlencode($_REQUEST[st])."&kg=".substr($kolorgrupy,1,strlen($kolorgrupy))."&ss=".urlencode($_REQUEST[ss])."&st_wc=".urlencode($_REQUEST[st_wc])."&searcheserwisnr=".urlencode($_REQUEST[search_eserwis_nr])."&kz=$temp_kategoria'); return false; \">";
			if (($czy_wyroznic_zgloszenia_seryjne==1) && ($temp_zgl_seryjne!='')) echo "";
			echo "&nbsp;$temp_nr&nbsp;";
			if (($czy_wyroznic_zgloszenia_seryjne==1) && ($temp_zgl_seryjne!='')) echo "[s]";
			echo "</a>";				
			
			echo "<span style='display:none' id='save$temp_nr'>";
			echo "<font color=red>&nbsp|&nbsp;<i>zmodyfikowano</i></font>";
			echo "</span>";

			
			echo "</span>";
			
			echo "<span>";
			
			// jeżeli zgłoszenie utworzono na bazie innego zgłoszenia pokaż ! obok numeru zgłoszenia	
			if ($temp_parent_zgl!=0) {
				echo "<input title=' Zgłoszenie utworzono na bazie zgłoszenia numer $temp_parent_zgl ' class=imgoption type=image src=img/have_parent.gif onclick=\"newWindow_r(800,600,'hd_o_zgloszenia.php?action=obsluga&id=$temp_parent_zgl&nr=$temp_parent_zgl&zgl_s='); return false;\">";
				
				//echo "<a class=normalfont title=' Zgłoszenie utworzono na bazie innego zgłoszenia ' href=# onClick=\"return false;\">!</a>";
			}

			/*	
	list($child_zgl)=mysql_fetch_array(mysql_query("SELECT zgl_nr FROM hd_zgloszenie WHERE (zgl_kontynuacja_zgloszenia_numer=$temp_nr) LIMIT 1"));
	if ($child_zgl!=0) {
		echo "<input title=' Na bazie zgłoszenia nr $temp_nr utworzono zgłoszenie numer $child_zgl ' class=imgoption type=image src=img/have_child.gif>";
	}
*/
			
			if ($temp_naprawa_id>0) {
				echo "<a title=' Przejdź do modułu NAPRAWY ' href=# onClick=\"self.location.href='przekieruj_do_napraw.php?hd_zgl_nr=$temp_nr'\"><img class=imgoption src=img/naprawa_unknown.gif border=0 width=16 height=16></a>";
				
				if ($temp_ss_id<=0) {
					$sql88="SELECT naprawa_sprzet_zastepczy_id  FROM $dbname.serwis_naprawa WHERE (naprawa_id=$temp_naprawa_id) LIMIT 1";
					$result88 = mysql_query($sql88, $conn) or die($k_b);
					list($temp_ss_id)=mysql_fetch_array($result88);
				}
			}
			
			if ($temp_ss_id>0) {
				echo "<a title=' Przejdź do modułu SPRZĘT SERWISOWY ' href=# onClick=\"self.location.href='przekieruj_do_sprzetu_serwisowego.php?id=$temp_ss_id&hd_zgl_nr=$temp_nr'\"><img class=imgoption src=img/service.gif border=0 width=16 height=16></a>";		
			}
			
			if (($temp_nrawarii!='') && ($temp_status!=9)) 
			echo "<a title=' Przejdź do listy otwartych awarii WAN ' href=# onClick=self.location.href='przekieruj_do_awarii_o.php?nr=$temp_nrawarii&up=".urlencode($temp_komorka)."'><img class=imgoption src=img/wan_disconnect.gif border=0></a>";

			if (($temp_nrawarii!='') && ($temp_status==9)) 
			echo "<a title=' Przejdź do listy zamkniętych awarii WAN ' href=# onClick=self.location.href='przekieruj_do_awarii_z.php?nr=$temp_nrawarii&up=".urlencode($temp_komorka)."'><img class=imgoption src=img/wan_connect.gif border=0></a>";	

			if (($temp_parent_zgl!=0)) {
				$sql88="SELECT zgl_czy_powiazane_z_wymiana_podzespolow FROM $dbname_hd.hd_zgloszenie WHERE (zgl_nr=$temp_parent_zgl) LIMIT 1";
				$result88 = mysql_query($sql88, $conn_hd) or die($k_b);
				list($temp_czy_pow_z_wp_parent)=mysql_fetch_array($result88);
				
				if ($temp_czy_pow_z_wp_parent==1) {
					$sql_wp="SELECT wp_sprzet_opis, wp_sprzet_sn, wp_sprzet_ni FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_zgl_id=$temp_parent_zgl) and (belongs_to=$es_filia) LIMIT 1";	
					$result_wp = mysql_query($sql_wp, $conn_hd) or die($k_b);

					list($wp_o,$wp_sn,$wp_ni)=mysql_fetch_array($result_wp);
					if ($wp_sn=='') $wp_sn='-';
					if ($wp_ni=='') $wp_ni='-';

					if ($enableHDszczPreviewDIV==1) {
						$rand = rand_str(10);			
						$sciezka_do_cookie = "hd_p_zgloszenie_kroki.php?nr=".$temp_nr."&id=".$temp_id."&searchtext=".urlencode($_REQUEST[st])."&kg=".substr($kolorgrupy,1,strlen($kolorgrupy))."&randval=".$rand."";
						
						echo "<a href=# title='Zgłoszenie powiązane z wymianą podzespołów w sprzęcie: $wp_o (SN: $wp_sn, NI: $wp_ni) w zgłoszeniu $temp_parent_zgl' onclick=\"$('#ZawartoscDIV').load('hd_p_zgloszenie_kroki.php?nr=".$temp_nr."&id=".$temp_id."&searchtext=".urlencode($_REQUEST[st])."&kg=".substr($kolorgrupy,1,strlen($kolorgrupy))."&poz=under&randval=".$rand."&ss=".urlencode($_REQUEST[ss])."&st_wc=".urlencode($_REQUEST[st_wc])."&searcheserwisnr=".urlencode($_REQUEST[search_eserwis_nr])."&kz=$temp_kategoria'); createCookie('hd_p_zgloszenia_div_nr','$sciezka_do_cookie',365); $('#info').hide(); self.location.href='#_SZ'; return false;\" /><img class=imgoption style='border:0px' type=image src=img/wp.gif width=16 height=16></a>";
						
					} else {
						echo "<a title='Zgłoszenie powiązane z wymianą podzespołów w sprzęcie: $wp_o (SN: $wp_sn, NI: $wp_ni)' href=#><img class=imgoption src=img/wp.gif border=0 width=16 height=16></a>";
					}
					
				}
				
			}

			if ($temp_czy_pow_z_wp==1) {
				$sql_wp="SELECT wp_sprzet_opis, wp_sprzet_sn, wp_sprzet_ni FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_zgl_id=$temp_nr) and (belongs_to=$es_filia) LIMIT 1";	
				$result_wp = mysql_query($sql_wp, $conn_hd) or die($k_b);
				list($wp_o,$wp_sn,$wp_ni)=mysql_fetch_array($result_wp);
				if ($wp_sn=='') $wp_sn='-';
				if ($wp_ni=='') $wp_ni='-';
				
				if ($enableHDszczPreviewDIV==1) {
					$rand = rand_str(10);			
					$sciezka_do_cookie = "hd_p_zgloszenie_kroki.php?nr=".$temp_nr."&id=".$temp_id."&searchtext=".urlencode($_REQUEST[st])."&kg=".substr($kolorgrupy,1,strlen($kolorgrupy))."&randval=".$rand."";
					
					echo "<a href=# title='Zgłoszenie powiązane z wymianą podzespołów w sprzęcie: $wp_o (SN: $wp_sn, NI: $wp_ni)'  onclick=\"$('#ZawartoscDIV').load('hd_p_zgloszenie_kroki.php?nr=".$temp_nr."&id=".$temp_id."&searchtext=".urlencode($_REQUEST[st])."&kg=".substr($kolorgrupy,1,strlen($kolorgrupy))."&poz=under&randval=".$rand."&ss=".urlencode($_REQUEST[ss])."&st_wc=".urlencode($_REQUEST[st_wc])."&searcheserwisnr=".urlencode($_REQUEST[search_eserwis_nr])."'); createCookie('hd_p_zgloszenia_div_nr','$sciezka_do_cookie',365); $('#info').hide(); self.location.href='#_SZ'; return false;\" /><img class=imgoption style='border:0px' type=image src=img/wp.gif  width=16 height=16></a>";
					
				} else {
					echo "<a title='Zgłoszenie powiązane z wymianą podzespołów w sprzęcie: $wp_o (SN: $wp_sn, NI: $wp_ni)' href=#><img class=imgoption src=img/wp.gif width=16 height=16 border=0></a>";
				}
			}
			
			
			if ($temp_rekl_czy_ma==1) {
				list($rekl_nr)=mysql_fetch_array(mysql_query("SELECT zgl_nr FROM hd_zgloszenie WHERE (zgl_nr_zgloszenia_reklamowanego=$temp_nr) LIMIT 1"));
				
				echo "<a href=# title='To zgłoszenie było reklamowane przez klienta. Utworzono z niego zgłoszenie reklamacyjne o numerze $rekl_nr' onclick=\"self.location.href='hd_p_zgloszenia.php?sr=search-wyniki&search_zgl_nr=$rekl_nr';\"><input class=imgoption type=image src=img/is_reklamacyjne.gif></a>";
			}
			
			if ($temp_rekl_czy_jest==1) {
				echo "<a href=# title='To jest zgłoszenie reklamacyjne do zgłoszenia nr $temp_rekl_nr' onclick=\"self.location.href='hd_p_zgloszenia.php?sr=search-wyniki&search_zgl_nr=$temp_rekl_nr';\"><input class=imgoption type=image src=img/have_reklamacyjne.gif></a>";
			}
			/*
			if ($temp_zgl_r_km>0) {
				echo "<input class=imgoption type=image src=img/car_ww.gif>";
			} else {
				if ($temp_kategoria>1) {
					$r33 = mysql_query("SELECT SUM(zgl_szcz_byl_wyjazd) FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$temp_nr') and (zgl_szcz_widoczne=1) and (belongs_to=$es_filia)", $conn_hd) or die($k_b);
					list($tmp_bw)=mysql_fetch_array($r33);
					if ($tmp_bw>0) echo "<input class=imgoption type=image src=img/car_ww.gif>";
				}
			}
			*/
			if ((($temp_kategoria=='2') || ($temp_kategoria=='6')) && (($temp_status=='2') || ($temp_status=='1'))) {
			
				// sprawdz czy było przesunięcie terminu rozpoczęcia realizacji zgłoszenia
				$r33 = mysql_query("SELECT zgl_szcz_przesuniety_termin_rozpoczecia,zgl_szcz_przesuniecie_data,zgl_szcz_przesuniecie_osoba FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$temp_nr') and (zgl_szcz_widoczne=1) and (belongs_to=$es_filia) and (zgl_szcz_status=1) ORDER BY zgl_szcz_nr_kroku DESC LIMIT 1", $conn_hd) or die($k_b);
				list($przesuniety,$przesuniety_data,$przesuniety_osoba)=mysql_fetch_array($r33);
			
				if (($przesuniety==1) && ($temp_kategoria=='2')) {
					echo "<a href=# onClick=\"alert('Dla zgłoszenie nr $temp_nr ustalono termin rozpoczęcia na ".substr($przesuniety_data,0,16).".  Osoba z którą ustalono przesunięcie: $przesuniety_osoba.');\"title='Zgłoszenie z przesuniętym terminem rozpoczęcia. Kliknij, aby zobaczyć szczegóły przesunięcia.'><input class=imgoption type=image src=img/przesuniety_termin.gif></a>";
				}
				if (($przesuniety==1) && ($temp_kategoria=='6')) {
						$r44 = mysql_query("SELECT up_working_time, up_working_time_alternative, up_working_time_alternative_start_date,up_working_time_alternative_stop_date FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ((serwis_komorki.belongs_to=$es_filia) and (serwis_piony.pion_id=serwis_komorki.up_pion_id) and (CONCAT(serwis_piony.pion_nazwa,' ',serwis_komorki.up_nazwa)='$temp_komorka'))", $conn) or die($k_b);
						list($temp_k_wh,$temp_k_wha,$temp_k_wha_start,$temp_k_wha_stop)=mysql_fetch_array($r44);
						
						if (($temp_k_wha!='') && ($temp_k_wha_start>=date('Y-m-d')) && ($temp_k_wha_stop<=date('Y-m-d'))) {
							$daysUP = explode(";",$temp_k_wha);
						} else $daysUP = explode(";",$temp_k_wh);
						
						$oneday1b = explode("@",$daysUP[0]); 
						$oneday2b = explode("@",$daysUP[1]); 
						$oneday3b = explode("@",$daysUP[2]); 
						$oneday4b = explode("@",$daysUP[3]); 
						$oneday5b = explode("@",$daysUP[4]); 
						$oneday6b = explode("@",$daysUP[5]); 
						$oneday7b = explode("@",$daysUP[6]); 
						
						$days = explode(";",$temp_zgl_komorka_working_hours);
						
						$opis_stanow = '';
						
						$oneday1 = explode("@",$days[0]); 
						$oneday2 = explode("@",$days[1]); 
						$oneday3 = explode("@",$days[2]); 
						$oneday4 = explode("@",$days[3]); 
						$oneday5 = explode("@",$days[4]); 
						$oneday6 = explode("@",$days[5]); 
						$oneday7 = explode("@",$days[6]); 

						$gp_sa = 1;
						if (($oneday1[1]=='') && ($oneday2[1]=='') && ($oneday3[1]=='') && ($oneday4[1]=='') && ($oneday5[1]=='') && ($oneday6[1]=='') && ($oneday7[1]=='')) $gp_sa = 0;
						
						if ($oneday1[1]=='') $oneday1[1] = '-';
						if ($oneday2[1]=='') $oneday2[1] = '-';
						if ($oneday3[1]=='') $oneday3[1] = '-';
						if ($oneday4[1]=='') $oneday4[1] = '-';
						if ($oneday5[1]=='') $oneday5[1] = '-';
						if ($oneday6[1]=='') $oneday6[1] = '-';
						if ($oneday7[1]=='') $oneday7[1] = '-';
						
						// menu z godzinami pracy
						$opis_stanow = '\r\n\r\n';
						
						if ($oneday1[1]!=$oneday1b[1]) { $opis_stanow .= '->\t'; } else { $opis_stanow .= '\t'; }
						$opis_stanow .= 'Poniedziałek:\t'.$oneday1[1].'\r\n';
						if ($oneday2[1]!=$oneday2b[1]) { $opis_stanow .= '->\t'; } else { $opis_stanow .= '\t'; }
						$opis_stanow .= 'Wtorek:\t\t\t'.$oneday2[1].'\r\n';
						if ($oneday3[1]!=$oneday3b[1]) { $opis_stanow .= '->\t'; } else { $opis_stanow .= '\t'; }
						$opis_stanow .= 'Środek:\t\t\t'.$oneday3[1].'\r\n';
						if ($oneday4[1]!=$oneday4b[1]) { $opis_stanow .= '->\t'; } else { $opis_stanow .= '\t'; }
						$opis_stanow .= 'Czwartek:\t\t'.$oneday4[1].'\r\n';
						if ($oneday5[1]!=$oneday5b[1]) { $opis_stanow .= '->\t'; } else { $opis_stanow .= '\t'; }
						$opis_stanow .= 'Piątek:\t\t\t'.$oneday5[1].'\r\n';
						if ($oneday6[1]!=$oneday6b[1]) { $opis_stanow .= '->\t'; } else { $opis_stanow .= '\t'; }
						$opis_stanow .= 'Sobota:\t\t\t\t'.$oneday6[1].'\r\n';
						if ($oneday7[1]!=$oneday7b[1]) { $opis_stanow .= '->\t'; } else { $opis_stanow .= '\t'; }
						$opis_stanow .= 'Niedziela:\t\t\t'.$oneday7[1].'\r\n';
						
					echo "<a href=# onClick=\"alert('Dla zgłoszenie nr $temp_nr zmodyfikowano godziny pracy (ustalono z: $przesuniety_osoba): $opis_stanow');\"title='Zgłoszenie ze zmodyfikowanymi godzinami pracy. Kliknij, aby zobaczyć szczegóły.'><input class=imgoption type=image src=img/mod_wh.gif></a>";
				}
			}
			
			if ($temp_czy_ww==1) {
				echo "<a href=# title='Wymaga wyjazdu. Ustawione przez $temp_czy_ww_osoba w dniu $temp_czy_ww_data'><input class=imgoption type=image src=img/car_ww.gif></a>";
			}

			
			if ($temp_status==9) {
				if (($temp_zgl_spr_data!='0000-00-00 00:00:00') && ($temp_zgl_spr_data!='')) {
					echo "<a href=# onClick=\"alert('Zgłoszenie nr $temp_nr zostało sprawdzone przez $temp_zgl_spr_osoba w dniu ".substr($temp_zgl_spr_data,0,16)."');\"title='Zgłoszenie nr $temp_nr zostało sprawdzone przez $temp_zgl_spr_osoba w dniu ".substr($temp_zgl_spr_data,0,16)."'><input class=imgoption type=image src=img/zgl_checked.gif></a>&nbsp;";
				}
			}			
			if ($PokazIkonyWHPrzegladanie==1) {
				echo "<a href=# class=anchorclass rel='submenu".$i."[click]'>";
				echo "<input class=imgoption type=image src=img/hd_zgl_opcje.gif>";
			} else {
				echo "<a href=# class='anchorclass normalfont' rel='submenu".$i."[click]'>";
				echo "Opcje";
			}
			echo "</a>";
			echo "</span>";
			
			echo "<div id=submenu".$i." class=anylinkcss>";
			echo "<p style='background-color:#2F3047; color:white; padding:3px'>&nbsp;Zgłoszenie nr <b>$temp_nr</b></p>";
			echo "<ul>";
			echo "<li>";
			if ($enableHDszczPreviewDIV==1) {
				echo "<a class=normalfont href=# ";
				echo " onclick=\"newWindow_r($_COOKIE[max_x],$_COOKIE[max_y],'hd_p_zgloszenie_kroki.php?newwindow=1&nr=$temp_nr&id=$temp_nr&searchtext=".urlencode($_REQUEST[st])."&kg=".substr($kolorgrupy,1,strlen($kolorgrupy))."&ss=".urlencode($_REQUEST[ss])."&st_wc=".urlencode($_REQUEST[st_wc])."&searcheserwisnr=".urlencode($_REQUEST[search_eserwis_nr])."&kz=$temp_kategoria'); return false; \">";
				echo "Kroki realizacji (nowe okno)</a>";
			}
			echo "</li>";
			echo "<li><hr /></hr></li>";
			
			if ($czy_wyroznic_zgloszenia_seryjne==1) {
				if ($temp_zgl_seryjne!='') { $zgl_seryjne_mark = '1'; } else { $zgl_seryjne_mark = ''; }
			} else { $zgl_seryjne_mark = ''; }
			
			if ($temp_status!=9) {
				echo "<li><a href=# onclick=\"newWindow_r(800,600,'hd_o_zgloszenia.php?action=obsluga&id=$temp_id&nr=$temp_nr&zgl_s=$zgl_seryjne_mark'); return false;\"><b>Obsługa zgłoszenia</b></a></li>";
				echo "<li><hr /></li>";
				
				if ($przypisanedo!=$currentuser) {
				
				//echo "&nbsp;<input id=pds_button type=button class=buttons value='Przypisz do siebie' onClick=\"if (confirm('Czy na pewno chcesz przypisać to zgłoszenie do siebie ?')) { $('#OsobaPrzypisana').load('hd_o_zgloszenia_pds.php?id=".$temp_id."&nr=".$temp_nr."&osoba=".urlencode($currentuser)."&randval='+ Math.random()); } \" />";
				
					echo "<li><a href=# onClick=\"if (confirm('Czy napewno chcesz przypisać zgłoszenie nr $temp_nr z $temp_komorka do siebie ?')) { newWindow(500,250,'hd_o_zgloszenia_pds.php?id=".$temp_id."&nr=".$temp_nr."&osoba=".urlencode($currentuser)."&refresh=1');return false; }\">Przypisz do siebie</a></li>";
				}
				
				echo "<li><a href=# onClick=\"newWindow(700,400,'hd_o_zgloszenia_pdo.php?id=$temp_id&nr=$temp_nr&osoba=".urlencode($przypisanedo)."'); return false; \"><font color=blue>Przypisz do osoby</font></a></li>";
			}
			
			if ($temp_status==9) {
				
				// jeżeli zgłoszenie nie jest konsultacją ani pracą na potrzeby Postdata -> pozwól utworzyć zgłoszenie reklamacyjne
				if (($temp_kategoria!=1) && ($temp_kategoria!=5)) {
					
					// jeżeli nie utworzono jeszcze zgłoszenia reklamacyjnego do tego zgłoszenia
					if ($temp_rekl_czy_ma==0) {
						//echo "<li><a href=# onclick=\"newWindow_r(800,600,'hd_d_zgloszenie.php?zgl_reklamacyjne=1&zgl_reklamacyjne_do_zgl_nr=$temp_nr&komorka=".urlencode($temp_komorka)."&osobazgl=".urlencode($temp_osoba)."&osobazgltel=".$temp_telefon."&nrzglpoczty=$temp_poczta_nr&zglkat=$temp_kategoria&zglpodkat=$temp_podkategoria&uniquenr_zgl_reklamowanego=$temp_unique&zgl_temat=".urlencode($temp_temat)."&zgl_tresc=".urlencode($temp_tresc)."'); return false; \"><font color=blue>Utwórz zgł. reklamacyjne</font></a></li>";
						//echo "<li><hr /></li>";
					}
				}
				
				echo "<li><a href=# onclick=\"newWindow_r(800,600,'hd_o_zgloszenia.php?action=podglad&id=$temp_id&nr=$temp_nr&zgl_s=$zgl_seryjne_mark'); return false; \">Podgląd zgłoszenia</a></li>";
			}
			$KierownikId = $kierownik_nr;
			if (($es_nr==$KierownikId) || ($is_dyrektor==1)) {
				echo "<li><a href=# onclick=\"newWindow_r(800,600,'hd_e_zgloszenie_new.php?id=$temp_id&nr=$temp_nr&tk=$temp_kategoria&tpk=$temp_podkategoria&tp=$temp_priorytet&unr=$temp_unique');return false;\"><font color=green>Edytuj zgłoszenie</font></a></li>";
				
				echo "<li><a href=# onclick=\"newWindow_r(800,600,'hd_e_zgloszenie_new.php?range=kat&id=$temp_id&nr=$temp_nr&tk=$temp_kategoria&tpk=$temp_podkategoria&tp=$temp_priorytet&unr=$temp_unique'); return false;\"><font color=green>Edytuj kategorie zgłoszenia</font></a></li>";
				
				if ((($temp_czy_pow_z_wp==0) && ($temp_naprawa_id<=0)) || ($allow_hide_for_all==true)) {	
					echo "<li><a href=# onclick=\"newWindow_r($dialog_window_x,$dialog_window_y,'hd_u_zgloszenie.php?id=$temp_id&nr=$temp_nr&data=$temp_data'); return false;\"><font color=red>Ukryj zgłoszenie</font></a></li>";
				}
				
			} else {
				if ($temp_status!=9) {
					echo "<li><a href=# onclick=\"newWindow_r(800,300,'hd_e_zgloszenie_new.php?id=$temp_id&nr=$temp_nr&tk=$temp_kategoria&tpk=$temp_podkategoria&tp=$temp_priorytet&unr=$temp_unique');return false;\"><font color=green>Edytuj zgłoszenie</font></a></li>";
				} else {
					echo "<li><a href=# onclick=\"newWindow_r(600,200,'hd_e_zgloszenie_hadim.php?id=$temp_id&nr=$temp_nr&tk=$temp_kategoria&tpk=$temp_podkategoria&tp=$temp_priorytet&unr=$temp_unique');return false;\"><font color=green>Edytuj nr zgł. HADIM</font></a></li>";
				}
			}
			
			if (($es_nr==$KierownikId) || ($is_dyrektor==1)) {				
				
			}	else {			
					if (substr($temp_data,0,7)==$edycja_dla_wszystkich_dla_okresu) {
						echo "<li><a href=# onclick=\"newWindow_r(800,600,'hd_e_zgloszenie_new.php?range=kat&id=$temp_id&nr=$temp_nr&tk=$temp_kategoria&tpk=$temp_podkategoria&tp=$temp_priorytet&unr=$temp_unique'); return false;\"><font color=green>Edytuj kategorie zgł.</font></a></li>";
				}
			}
			
			if (($temp_status==9) && ((($kierownik_nr==$es_nr) || ($is_dyrektor==1)) && ($funkcja_weryfikacji_zgloszen_przez_kierownikow==true)) && ($temp_zgl_spr_data=='0000-00-00 00:00:00')) {
				echo "<li><a href=# onclick=\"newWindow_r($dialog_window_x,$dialog_window_y,'hd_zgloszenie_potwierdz.php?frommenu=1&potwierdz=1&id=$temp_id&nr=$temp_nr&sprdata=".Date("Y-m-d H:i:s")."&spruser=".urlencode($currentuser)."&donotreloadopener=1');return false;\"><font color=green>*Potwierdź sprawdzenie*</font></a></li>";
			}
			if (($temp_status==9) && ((($kierownik_nr==$es_nr) || ($is_dyrektor==1)) && ($funkcja_weryfikacji_zgloszen_przez_kierownikow==true)) && ($temp_zgl_spr_data!='0000-00-00 00:00:00')) {
				echo "<li><a href=# onclick=\"newWindow_r($dialog_window_x,$dialog_window_y,'hd_zgloszenie_potwierdz.php?potwierdz=0&id=$temp_id&nr=$temp_nr&sprdata=".Date("Y-m-d H:i:s")."&spruser=".urlencode($currentuser)."&donotreloadopener=1');return false;\"><font color=red>*Anuluj sprawdzenie*</font></a></li>";
			}
			
			if (($temp_kategoria!=1) && ($temp_kategoria!=5) && ($temp_kategoria!=4)) {
			
				list($czy_sa_stany_posrednie)=mysql_fetch_array(mysql_query("SELECT COUNT(hdnp_id) FROM $dbname_hd.hd_naprawy_powiazane WHERE (hdnp_zgl_id='$temp_nr') and ((hdnp_zdiagnozowany<>9) or (hdnp_oferta_wyslana<>9) or (hdnp_akceptacja_kosztow<>9) or (hdnp_zamowienie_wyslane<>9) or (hdnp_zamowienie_zrealizowane<>9) or (hdnp_gotowe_do_oddania<>9))", $conn_hd));
			
				if ($czy_sa_stany_posrednie>0) {
					echo "<li><a href=# onclick=\"newWindow_r(800,600,'hd_e_zgloszenie_sp.php?id=$temp_id&nr=$temp_nr');return false;\"><font color=#336749>Edytuj stany pośrednie</font></a></li>";
				}
			
			}
			
			if (($temp_kategoria!=1) && ($temp_status!=9)) {

				if ($temp_czy_ww==1) {
					echo "<li><a href=# onClick=\"newWindow(500,50,'hd_o_zgloszenia_ww.php?zgl_nr=".$temp_nr."&set=0&donotreloadopener=1');\"><font color=blue>Anuluj wymagany wyjazd</font></a></li>";
				} else {
					echo "<li><a href=# onClick=\"newWindow(500,50,'hd_o_zgloszenia_ww.php?zgl_nr=".$temp_nr."&set=1&ww_data=".urlencode(Date('Y-m-d H:i:s'))."&ww_osoba=".urlencode($currentuser)."&donotreloadopener=1');\"><font color=blue>Ustaw wymagany wyjazd</font></a></li>";
					//echo "<span style=''><input type=button class=buttons value='Ustaw wymagany wyjazd'  onClick=\"newWindow(500,50,'hd_o_zgloszenia_ww.php?zgl_nr=".$_REQUEST[nr]."&set=1&ww_data=".urlencode(Date('Y-m-d H:i:s'))."&ww_osoba=".urlencode($currentuser)."'); \"></span>";		
				}
				
			}
			
			
			// jeżeli nie było wymian sprzętu w zgłoszeniu -> daj możliwość powiązania zgłoszenia z naprawą
			/*
			if ((($temp_kategoria=='2') || ($temp_kategoria=='6') || ($temp_kategoria=='3')) && 
				(($temp_podkategoria=='3') || ($temp_podkategoria=='4') || ($temp_podkategoria=='9') || ($temp_podkategoria=='0') || ($temp_podkategoria=='E'))) { 
				if (($temp_naprawa_id<=0) && ($temp_czy_pow_z_wp==0) && (($temp_status=='3A') || ($temp_status=='3B') || ($temp_status==7))) {
					//echo "<li><a href=# onclick=\"newWindow_r(800,$dialog_window_y,'hd_powiaz_zgloszenie_z_naprawa.php?id=".$temp_id."&nr=".$temp_nr."&data=".$temp_data."&tk=".$temp_kategoria."&tpk=".$temp_podkategoria."&ts=".$temp_status."&komorkanazwa=".urlencode($temp_komorka)."&komorka=".urlencode(substr($temp_komorka,strpos($temp_komorka,' ')+1,strlen($temp_komorka)))."'); return false;\">Powiąż z naprawą</a></li>";
				}
			}
*/

			//echo "<li><hr /></li>";
			//echo "<li><a href=# onclick=\"if (confirm('Czy napewno chcesz wydrukować potwierdzenie dla zgłoszenia nr $temp_nr ?')) { newWindow_r(800,600,'hd_potwierdzenie.php?id=$temp_id&nr=$temp_nr&zgl_s=$zgl_seryjne_mark'); return false; } else { return false; } \"><b>Drukuj potwierdzenie</b></a></li>";
			echo "<li><hr /></li>";
			
			echo "<li><a href=# onclick=\"self.location.href='z_protokol.php?okresm=".Date('m')."&okresr=".Date('Y')."&ss=$temp_nr';\">Protokoły powiązane</a></li>";
			echo "<li><hr /></li>";

			if ((($temp_kategoria=='2')) && ((($currentuser==$przypisanedo) && (($temp_status=='2'))) || ($temp_status=='1'))) {
				if ($temp_data_zak!='0000-00-00 00:00:00') {
					echo "<li><a href=# onclick=\"newWindow_r(700,450,'hd_e_zgloszenie.php?element=czas_zak&id=$temp_id&nr=$temp_nr');return false;\">Zmień czas rozpoczęcia</a></li>";
					echo "<li><hr /></li>";
				}
			}
			
			echo "<li><a href=# onclick=\"newWindow(400,200,'hd_p_pokaz_ip_tel.php?komorkanazwa=".urlencode($temp_komorka)."');return false;\">Telefon i IP komórki</a></li>";
			//echo "<li><a href=# onclick=\"newWindow(400,150,'hd_p_pokaz_ip.php?komorkanazwa=".urlencode($temp_komorka)."');return false;\">Adres IP placówki</a></li>";
			//echo "<li><a href=# onclick=\"newWindow(400,150,'hd_p_pokaz_tel.php?komorkanazwa=".urlencode($temp_komorka)."');return false;\">Telefon do placówki</a></li>";
			echo "<li><a href=# onclick=\"newWindow(500,300,'hd_p_pokaz_godziny.php?nr=$temp_nr&komorkanazwa=".urlencode($temp_komorka)."&wa=".urlencode($temp_zgl_komorka_working_hours)."'); return false;\">Zapisane godziny pracy</a></li>";			
			echo "<li><hr /></hr></li>";
			echo "<li><a href=# onclick=\"newWindow_r(800,600,'hd_p_zgloszenie_historia_zmian.php?id=$temp_id&nr=$temp_nr');return false;\">Historia zmian</a></li>";
			echo "</ul>";
			echo "</div>";
			// koniec popup'a z opcjami 

			
		echo "</td>";
		echo "<td class=center width=36>";
			//list($status)=mysql_fetch_array(mysql_query("SELECT hd_status_opis FROM $dbname_hd.hd_status WHERE hd_status_nr='$temp_status' LIMIT 1", $conn_hd));
			echo "<a class='normalfont ";
			
			echo "' title='Status zgłoszenia: ";
					switch ($temp_status) {
						case "1"	: echo "nowe"; break;
						case "2"	: echo "przypisane"; break;
						case "3"	: echo "rozpoczęte"; break;
						case "3A"	: echo "w serwisie zewnętrznym"; break;
						case "3B"	: echo "w firmie"; break;
						case "4"	: echo "oczekiwanie na odpowiedź klienta"; break;
						case "5"	: echo "oczekiwanie na sprzęt"; break;
						case "6"	: echo "do oddania"; break;
						case "7"	: echo "rozpoczęte, nie zakończone"; break;
						case "9"	: echo "zamknięte"; break;
					}			
				echo "' ";
			echo " href=# onclick=\"newWindow_r(800,600,'hd_o_zgloszenia.php?action=obsluga&id=$temp_id&nr=$temp_nr&zgl_s=$zgl_seryjne_mark&donotreloadopener=1'); return false;\">";
			switch ($temp_status) {
			case "1"	: echo "<input class=imgoption type=image src=img/zgl_nowe.gif>"; break;
			case "2"	: echo "<input class=imgoption type=image src=img/zgl_przypisane.gif>"; break;
			case "3"	: echo "<input class=imgoption type=image src=img/zgl_rozpoczete.gif>"; break;
			case "3A"	: echo "<input class=imgoption type=image src=img/zgl_w_serwisie_zewnetrznym.gif>"; break;
			case "3B"	: echo "<input class=imgoption type=image src=img/zgl_w_firmie.gif>"; break;
			case "4"	: echo "<input class=imgoption type=image src=img/zgl_oczekiwanie_na_odpowiedz.gif>"; break;
			case "5"	: echo "<input class=imgoption type=image src=img/zgl_oczekiwanie_na_sprzet.gif>"; break;
			case "6"	: echo "<input class=imgoption type=image src=img/zgl_do_oddania.gif>"; break;
			case "7"	: echo "<input class=imgoption type=image src=img/zgl_rozpoczete_nie_zakonczone.gif>"; break;
				//case "8"	: echo "<input class=imgoption type=image src=img/zgl_nowe.gif>"; break;
			case "9"	: echo "<input class=imgoption type=image src=img/zgl_zamkniete.gif>"; break;
			}
				
			echo "</a>";
			
			
		echo "</td>";	
		echo "<td class=center width=120>$temp_data";
				
			/*
			
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
			*/	
		echo "</td>";
			
		echo "<td>$temp_komorka</td>";

		$r1 = mysql_query("SELECT hd_kategoria_opis FROM $dbname_hd.hd_kategoria WHERE (hd_kategoria_nr='$temp_kategoria') LIMIT 1", $conn_hd) or die($k_b);
		list($kat_opis)=mysql_fetch_array($r1);		
		$r1 = mysql_query("SELECT hd_podkategoria_opis FROM $dbname_hd.hd_podkategoria WHERE (hd_podkategoria_nr='$temp_podkategoria') LIMIT 1", $conn_hd) or die($k_b);
		list($podkat_opis)=mysql_fetch_array($r1);			

		if ($temp_podkategoria2=='') $temp_podkategoria2 = '-';
		
		echo "<td>$kat_opis -> $podkat_opis -> $temp_podkategoria2</td>";

		echo "<td>";
		echo nl2br(wordwrap($temp_temat, 78, "<br />"));
		
		echo "<input id=sm$temp_nr title='Pokaż pełną treść zgłoszenia nr $temp_nr' type=image class=imgoption src=img/show_more.gif onClick=\"$('#tresc$i').load('hd_zgl_detail.php?id=$temp_nr'); $('#tresc$i').show();\">";
		echo "<input id=hm$temp_nr style='display:none;' title='Ukryj pełną treść zgłoszenia nr $temp_nr' type=image class=imgoption src=img/hide_more.gif onClick=\"$('#tresc$i').hide(); $('#sm$temp_nr').show(); $('#hm$temp_nr').hide();\">";
		
		echo "<input id=sm_kroki_$temp_nr title='Podgląd kroków zgłoszenia nr $temp_nr' type=image class=imgoption src=img/expand.gif onClick=\"$('#kroki$i').load('hd_zgl_detail2.php?id=$temp_nr'); $('#kroki$i').show();\">";
		echo "<input id=hm_kroki_$temp_nr style='display:none;' title='Ukryj kroki zgłoszenia nr $temp_nr' type=image class=imgoption src=img/collapse.gif onClick=\"$('#kroki$i').hide(); $('#sm_kroki_$temp_nr').show(); $('#hm_kroki_$temp_nr').hide();\">";
		
		echo "<div id=tresc$i></div>";		
		echo "</td>";	
		
		_tr();
		
		echo "<tr class=hideme>";
		echo "<td colspan=6>";
			echo "<div id=kroki$i></div>";	
		echo "</td>";
		echo "</tr>";
		
		$i++;
	}	

				echo "<tr class=hideme><td colspan=6>";
					echo "<span>";
					echo "<input class=imgoption type=image src=img/obsluga_seryjna.png>";
				
					echo "Zaznaczono: ";
				
					echo "<span id=IloscZaznaczonych style='font-weight:bold; '>0</span>";
						echo "<span style='display:none;' id=NapisPrzed>";
							echo "&nbsp;|&nbsp;Oznacz jako:&nbsp;<input type=button id=OznaczJakoSprawdzone1 class=buttons style='color:blue' value='Sprawdzone' title='Oznacz wybrane zgłoszenia jako sprawdzone' onClick=\"if (confirm('Czy napewno chcesz oznaczyć wybrane zgłoszenia jako `sprawdzone` ?')) OznaczJakoSprawdzone(1);\" />";
							echo "<input type=button id=OznaczJakoSprawdzone2 class=buttons style='color:blue' value='Niesprawdzone' title='Oznacz wybrane zgłoszenia jako niesprawdzone' onClick=\"if (confirm('Czy napewno chcesz oznaczyć wybrane zgłoszenia jako `niesprawdzone` ?')) OznaczJakoSprawdzone(0);\" />";			
						echo "</span>";
					echo "</span>";
					
				startbuttonsarea("left");
				echo "<br />Wszystkie: ";
				echo "<input class=buttons type=button onClick=\"MarkCheckboxes('zaznacz'); UpdateIloscZaznaczen(); \" value='Zaznacz'>";
				echo "<input class=buttons type=button onClick=\"MarkCheckboxes('odznacz'); UpdateIloscZaznaczen(); \" value='Odznacz'>";
				echo "<input class=buttons type=button onClick=\"MarkCheckboxes('odwroc'); UpdateIloscZaznaczen(); \"value='Odwróć zaznaczenie'>";
				
				echo "<span style='float:right;'>";
				echo "Szczegóły wszystkich zgłoszeń: ";
				echo "<input class=buttons type=button onClick=\"ShowAllDetails(true);\" value='Pokaż'>";
				echo "<input class=buttons type=button onClick=\"ShowAllDetails(false);\" value='Ukryj'>";
				echo "</span>";
				
				endbuttonsarea();
			
				echo "</td></tr>";
				
	echo "<tr class=hideme><td colspan=6>";
		if ($count_rows>0) include_once('paging_end_hd.php');
	echo "</td></tr>";

echo "</form>";	
	echo "</table>";
	echo "<br />";
	
	echo "<form action=do_xls_htmlexcel_hd_g_raport_okresowy.php METHOD=POST target=_blank>";	
	startbuttonsarea("right");
	
	if ($_REQUEST[readonly]!=1) {
		echo "<span style='float:left;'>";
		addlinkbutton("'Zmień kryteria'","main.php?action=hdwz&okres_od=$okres_od&okres_do=$okres_do&tuser=".urlencode($tuser)."&tstatus=$tstatus&kategoria=$kategoria&podkategoria=$podkategoria&priorytet=$priorytet&potw_spr=$_REQUEST[potw_spr]");
		echo "</span>";
	}
	
		//addownlinkbutton("'Nowe zgłoszenie'","Button1","button","newWindow_r(800,600,'hd_d_zgloszenie.php?stage=1')");
		//addownlinkbutton("'Nowe zgłoszenie seryjne'","Button1","button","newWindow_r(800,600,'hd_d_zgloszenie_s.php?stage=1&filtr=X-X-X-X&p6=true')");
		//echo " | ";
		echo "<input type=button class=buttons value='Odśwież stronę' onClick=\"self.location.reload(true); return false;\" />";
		addlinkbutton("'Przeglądaj zgłoszenia'","hd_p_zgloszenia.php?showall=0&p5=BZ&sort=AD&page=1");
		echo " | ";
		
//		echo "<input class=buttons type=submit style='color:blue;' value='Export do XLS'>";
			
		echo "<input type=hidden name=g_okres_od value='$_REQUEST[okres_od]'>";
		echo "<input type=hidden name=g_okres_do value='$_REQUEST[okres_do]'>";
		echo "<input type=hidden name=g_tzgldata value='$_REQUEST[tzgldata]'>";
		
		echo "<input type=hidden name=zapytanie value=\"$sql\" >";
		
	//addlinkbutton("'Przeglądaj towary'","p_towary_dostepne.php?view=normal&wybor=".urlencode($wybor)."");

	
	if ($_REQUEST[readonly]!=1) addbuttons("start");
	if ($_REQUEST[readonly]==1) addbuttons("zamknij");

	endbuttonsarea();	
	echo "</form>";

	?>
	
	<script>
	anylinkcssmenu.init("anchorclass");
	</script>

	<script>HideWaitingMessage();</script>
	<?php 
} else {

		errorheader("Nie znaleziono zgłoszeń spełniających podane przez Ciebie kryteria");
		startbuttonsarea("right");

		echo "<span style='float:left;'>";
		addlinkbutton("'Zmień kryteria'","main.php?action=hdwz&okres_od=$okres_od&okres_do=$okres_do&tuser=".urlencode($tuser)."&tstatus=$tstatus&kategoria=$kategoria&podkategoria=$podkategoria&priorytet=$priorytet&potw_spr=$_REQUEST[potw_spr]");
		echo "</span>";

		//		addlinkbutton("'Zmień kryteria'","main.php?action=hdwz&okres_od=$okres_od&okres_do=$okres_do&tuser=".urlencode($tuser)."&tstatus=$tstatus&kategoria=$kategoria&podkategoria=$podkategoria&priorytet=$priorytet");
		addbuttons("start");
		endbuttonsarea();	

}

	include('body_stop.php');
	//echo "</body></html>";
	
} else {
br();
pageheader("Generowanie listy zgłoszeń z bazy Helpdesk do weryfikacji");
starttable("650px");
echo "<form name=ruch action=hd_g_raport_weryfikacja_zgloszen.php method=GET>";
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
/*	tr_();
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
*/
	echo "<input type=hidden name=tzgldata value='data_utworzenia'>";
	tbl_empty_row();
	tr_();
		td_colspan(1,'r');
			echo "<b>Wybierz pracownika</b>";
		_td();
		td_colspan(1,'c'); echo "&nbsp;"; _td();
		td_colspan(2,'l');
			$sql111 = "SELECT user_first_name, user_last_name FROM $dbname.serwis_uzytkownicy ";
			if ($is_dyrektor==0) $sql111.= " WHERE belongs_to=$es_filia ";
			$sql111 .= " ORDER BY user_last_name";

			$result6 = mysql_query($sql111, $conn) or die($k_b);
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
			echo "<option value='0'"; if ($_REQUEST[tstatus]=='0') echo " SELECTED ";echo ">Wszystkie</option>\n"; 
			echo "<option value='9' "; if ($_REQUEST[tstatus]=='9') echo " SELECTED ";echo ">Tylko zakończone</option>\n"; 
			echo "</select>\n";
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
			$sql_f1="SELECT DISTINCT(hd_podkategoria.hd_podkategoria_opis), hd_podkategoria.hd_podkategoria_nr, hd_zgloszenie.zgl_podkategoria FROM $dbname_hd.hd_podkategoria, $dbname_hd.hd_zgloszenie WHERE (hd_podkategoria.hd_podkategoria_wlaczona=1) and (hd_zgloszenie.zgl_podkategoria=hd_podkategoria.hd_podkategoria_nr) ORDER BY hd_podkategoria_order ASC";
			$result_f1=mysql_query($sql_f1,$conn) or die($k_b);
			
			while ($dane_f1=mysql_fetch_array($result_f1)) {
				$temp_nr = $dane_f1['hd_podkategoria_nr'];
				$temp_opis = $dane_f1['hd_podkategoria_opis'];
				echo "<option value='$temp_nr'"; if ($_GET[podkategoria]==$temp_nr) echo " SELECTED ";echo ">$temp_opis</option>\n";
			}
			echo "</select>";
	
		_td();
	_tr();	
	tbl_empty_row();
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
	
	tr_();
		td_colspan(1,'r');
			echo "<b>Ilość zgłoszeń na stronie</b>";
		_td();
		td_colspan(1,'c'); echo "&nbsp;"; _td();
		td_colspan(2,'l');
		if ($_REQUEST[rps111]=='') $_REQUEST[rps111] = '50';
			echo "<select name=rps111>";
				echo "<option value='5' ";
				if ($_REQUEST[rps111]=='5') echo " SELECTED ";
				echo " >5</option>\n";
				
				echo "<option value='15'"; 
				if ($_REQUEST[rps111]=='15') echo " SELECTED ";
				echo ">15</option>\n";
				
				echo "<option value='50'"; 
				if ($_REQUEST[rps111]=='50') echo " SELECTED ";
				echo ">50</option>\n";

				echo "<option value='100'"; 
				if ($_REQUEST[rps111]=='100') echo " SELECTED ";
				echo ">100</option>\n";

				echo "<option value='200'"; 
				if ($_REQUEST[rps111]=='200') echo " SELECTED ";
				echo ">200</option>\n";
				
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