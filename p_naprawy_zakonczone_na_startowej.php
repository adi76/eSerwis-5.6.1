<?php //include_once('header.php'); ?>
<body>
<?php
if ($es_m==1) {
	$result = mysql_query("SELECT * FROM $dbname.serwis_naprawa WHERE (naprawa_status='3')", $conn) or die($k_b);
} else {
	$result = mysql_query("SELECT * FROM $dbname.serwis_naprawa WHERE ((naprawa_status='3')) and ((belongs_to=$es_filia) or ((naprawa_przekazanie_naprawy_do=$es_filia) and (naprawa_przekazanie_zakonczone=0)))", $conn) or die($k_b);
}
$wynik = mysql_num_rows($result);
if ($wynik!=0) {
	echo "<h3 style='font-size:13px;font-weight:normal;padding-top:4px;padding-bottom:4px;margin-top:0px;margin-bottom:5px;text-align:left;background:#B5DDAF;color:#313131;display: block; border: 1px solid #98D090;'>&nbsp;";

	echo "<a href=# class=normalfont id=pokaz_sprzet_do_oddania style='display:none' type=button onClick=\"document.getElementById('div_pokaz_informacje_o_sprzecie_do_oddania').style.display=''; document.getElementById('pokaz_sprzet_do_oddania').style.display='none'; document.getElementById('ukryj_sprzet_do_oddania').style.display=''; createCookie('p_naprawy_zakonczone_na_startowej','TAK',365); location.reload(true); \"><img class=imgoption style='border:0px' type=image src=img/expand.gif  width=11 height=11>&nbsp;Przeglądanie naprawionego sprzętu na stanie - gotowe do oddania do klienta (<b>$wynik</b>) </a>";
	
	echo "<a href=# class=normalfont id=ukryj_sprzet_do_oddania style='display:none' type=button onClick=\"document.getElementById('div_pokaz_informacje_o_sprzecie_do_oddania').style.display='none'; document.getElementById('pokaz_sprzet_do_oddania').style.display=''; document.getElementById('ukryj_sprzet_do_oddania').style.display='none'; createCookie('p_naprawy_zakonczone_na_startowej','NIE',365);\"><img class=imgoption style='border:0px' type=image src=img/collapse.gif width=11 height=11>&nbsp;Przeglądanie naprawionego sprzętu na stanie - gotowe do oddania do klienta (<b>$wynik</b>) </a>";
	
	echo "</h3>";
	
	echo "<div id=div_pokaz_informacje_o_sprzecie_do_oddania>";
	
	?>
	<script>
	//alert(readCookie('p_naprawy_zakonczone_na_startowej'));
	if (readCookie('p_naprawy_zakonczone_na_startowej')=='TAK') {
		document.getElementById('pokaz_sprzet_do_oddania').style.display='none';
		document.getElementById('ukryj_sprzet_do_oddania').style.display='';
		document.getElementById('div_pokaz_informacje_o_sprzecie_do_oddania').style.display='';
	} else {
		document.getElementById('pokaz_sprzet_do_oddania').style.display='';
		document.getElementById('ukryj_sprzet_do_oddania').style.display='none';
		document.getElementById('div_pokaz_informacje_o_sprzecie_do_oddania').style.display='none';
	}

	if (readCookie('p_naprawy_zakonczone_na_startowej')==null) {
		document.getElementById('pokaz_sprzet_do_oddania').style.display='none';
		document.getElementById('ukryj_sprzet_do_oddania').style.display='';
		document.getElementById('div_pokaz_informacje_o_sprzecie_do_oddania').style.display='';
		createCookie('p_naprawy_zakonczone_na_startowej','TAK',365);
	}

	</script>
	<?php
	
//if (($_COOKIE['p_naprawy_zakonczone_na_startowej']=='TAK') || ($_COOKIE['p_naprawy_zakonczone_na_startowej']=='null')) {
	
	starttable();
	if ($es_m==1) {
		th("30;c;LP|;;Nazwa Model<br />Numer seryjny, inwentarzowy|;;Pobrano z|;;Rejestracja przez<br />Data rejestracji|;;Przyjęcie z naprawy<br />Data przyjęcia|;c;Wykonane naprawy<hr />Sprzęt zastępczy|;c;Uwagi<br /><sub>Filia</sub>|;c;Opcje",$es_prawa);
	} else {
		th("30;c;LP|;;Nazwa Model<br />Numer seryjny, inwentarzowy|;;Pobrano z|;;Rejestracja przez<br />Data rejestracji|;;Przyjęcie z naprawy<br />Data przyjęcia|;c;Wykonane naprawy<hr />Sprzęt zastępczy|;c;Uwagi|;c;Opcje",$es_prawa);
	}
	$y = 100;
	$yy = 0;
	$f = 1;
	while ($dane = mysql_fetch_array($result)) {
		$mid = $dane['naprawa_id'];						
		$mnazwa = $dane['naprawa_nazwa'];
		$mmodel= $dane['naprawa_model'];				
		$msn = $dane['naprawa_sn'];
		$mni= $dane['naprawa_ni'];						
		$muwagisa= $dane['naprawa_uwagi_sa'];
		$muwagi= $dane['naprawa_uwagi'];				
		$mup= $dane['naprawa_pobrano_z'];
		$moo= $dane['naprawa_osoba_pobierajaca'];		
		$mdp= $dane['naprawa_data_pobrania'];
		$mstatus= $dane['naprawa_status'];				
		$msz= $dane['naprawa_sprzet_zastepczy_id'];		
		$mopzs= $dane['naprawa_osoba_przyjmujaca_sprzet_z_serwisu']; 
		$mdpzs= $dane['naprawa_data_odbioru_z_serwisu'];
		$bt = $dane['belongs_to'];
		$mewid_id = $dane['naprawa_ew_id'];
		$mnw = $dane['naprawa_wykonane_naprawy'];
		$mewid_id = $dane['naprawa_ew_id'];
		
		$n_przekaz_do = $dane['naprawa_przekazanie_naprawy_do'];
		$n_przekaz_data = $dane['naprawa_przekazanie_naprawy_data'];
		$n_przekaz_osoba = $dane['naprawa_przekazanie_naprawy_osoba'];
		$n_odbior_data = $dane['naprawa_odbior_z_filii_data'];
		$n_odbior_osoba = $dane['naprawa_odbior_z_filii_osoba'];	
		$n_przekazanie_zakonczone = $dane['naprawa_przekazanie_zakonczone'];
		$n_przekazanie_zakonczone = $dane['naprawa_przekazanie_zakonczone'];
		$n_zgl_id = $dane['naprawa_hd_zgl_id'];
		
		$naprawa_przekazana_do_innej_filii = 0;
		if (($n_przekaz_do!=$bt) && ($n_przekaz_do!=0)) {
			$change_color_start = '<font color=blue>';
			$change_color_stop = '</font>';
			$naprawa_przekazana_do_innej_filii = 1;
		} else {
			$change_color_start = '';
			$change_color_stop = '';
			$naprawa_przekazana_do_innej_filii = 0;
		}
		if ($n_przekazanie_zakonczone==1) {
			$change_color_start = '';
			$change_color_stop = '';
			$naprawa_przekazana_do_innej_filii = 0;
		}

		$result54 = mysql_query("SELECT up_id FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (up_active=1) and (belongs_to=$bt) and (serwis_komorki.up_nazwa = '$mup') LIMIT 1", $conn) or die($k_b);
		list($temp_upid)=mysql_fetch_array($result54);
		
		tbl_tr_highlight($y);
		$y++;
		$yy++;
			//td("30;c;".$yy."");
//			td("30;c;<a class=normalfont href=# title=' $mid '>".$yy."</a>");
			td("30;c;<a class=normalfont href=# title=' $mid '>".$change_color_start."".$f."".$change_color_stop."</a>");
			$f++;
			
			//td_("50%;nw;<b>".$mnazwa." ".$mmodel."</b><br />".$msn.", ".$mni."");
			
			echo "<td>";
				echo "<b>".$change_color_start."".$mnazwa." ".$mmodel."</b>";		
				if ($naprawa_przekazana_do_innej_filii==1) {
					// informacje o tym że sprzęt jest w innej filii / oddziale
					$r40 = mysql_query("SELECT filia_nazwa FROM $dbname.serwis_filie WHERE (filia_id='$n_przekaz_do') LIMIT 1", $conn) or die($k_b);
					list($NazwaFilii)=mysql_fetch_array($r40);
					
					$r41 = mysql_query("SELECT filia_nazwa FROM $dbname.serwis_filie WHERE (filia_id='$bt') LIMIT 1", $conn) or die($k_b);
					list($NazwaFiliiZrodlowej)=mysql_fetch_array($r41);
					
					if ($n_przekaz_do==$es_filia) {
						echo " -> sprzęt przekazany z filii: <b>$NazwaFiliiZrodlowej&nbsp;</b>";
						echo "<a title=' Informacje dodatkowe '><input class=imgoption type=image src=img//information.gif onclick=\"alert('Sprzęt przekazany z serwisu filii $NazwaFiliiZrodlowej w dniu $n_przekaz_data przez $n_przekaz_osoba');\"></a>";
					} else {
						echo " -> sprzęt przekazany do filii: <b>$NazwaFilii&nbsp;</b>";
						echo "<a title=' Informacje dodatkowe '><input class=imgoption type=image src=img//information.gif onclick=\"alert('Sprzęt przekazany do serwisu filii $NazwaFilii w dniu $n_przekaz_data przez $n_przekaz_osoba');\"></a>";
					}
				}
				echo "<br />".$msn." / ".$mni."".$change_color_stop."";				
				if ($pokazpoczatekuwag==1) pokaz_uwagi($muwagi,$iloscznakowuwag,"newWindow(480,300,'p_naprawy_uwagi.php?id=$mid')");
			_td();
			td_(";;");
				//$wynik = mysql_query("SELECT up_id FROM $dbname.serwis_komorki WHERE (up_nazwa='$mup') and (belongs_to=$es_filia) LIMIT 1", $conn) or die($k_b);
				//list($temp_up_id)= mysql_fetch_array($wynik);

				$sql_up = "SELECT up_id, up_pion_id FROM $dbname.serwis_komorki WHERE (up_nazwa='$mup') and (belongs_to=$bt) LIMIT 1";
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
					
				echo "<a class=normalfont title=' Szczegółowe informacje o $temp_pion_nazwa $mup ' onclick=\"newWindow_r(700,600,'p_komorka_szczegoly.php?id=$temp_up_id'); return false;\" href=#><b>".$change_color_start."$temp_pion_nazwa $mup".$change_color_stop."</b></a><br />";
				//echo "".$msn."<br />".$mni."";
			_td();
//			td_("115;;".$moo."<br />".$mdp."");
//			td_("115;;".$mopzs."<br />".$mdpzs."");
			
			td_(";;".$change_color_start."".$moo."".$change_color_stop."<br />".$change_color_start."".substr($mdp,0,16)."".$change_color_stop."");
			td_(";;".$change_color_start."".$mopzs."".$change_color_stop."<br />".$change_color_start."".substr($mdpzs,0,16)."".$change_color_stop."");
			
			td_(";c");
			/*	if ($mstatus=='-1') echo "<a>pobrany od klienta</a>";
				if ($mstatus=='0') echo "<a>naprawa we własnym zakresie</a>";
				if ($mstatus=='1') echo "<a>naprawa w serwisie zewnętrznym</a>";
				if ($mstatus=='2') echo "<a>naprawa na rynku lokalnym</a>";
				if ($mstatus=='3') echo "<a>naprawiony</a>";
				if ($mstatus=='5') echo "<a>zwrócony do klienta</a>";
			*/	$c4='';
				if ($mstatus=='3') {
					//echo "<br /><hr />";
					if ($mnw!='') {
						echo "&nbsp;<a title=' Pokaż wykonane naprawy '><input type=image class=imgoption src=img/wykonane_naprawy.gif onclick=\"newWindow(480,300,'p_naprawy_wykonane.php?id=$mid')\"></a>";
						echo "&nbsp;<a title=' Edytuj wykonane naprawy '><input type=image class=imgoption src=img/wykonane_naprawy_edit.gif onclick=\"newWindow(480,300,'p_naprawy_wykonane_edit.php?id=$mid')\"></a>";
					} else {
						echo "&nbsp;<a title=' Dopisz wykonane naprawy '><input type=image class=imgoption src=img/wykonane_naprawy_add.gif onclick=\"newWindow(480,300,'p_naprawy_wykonane_edit.php?id=$mid')\"></a>";
					}
				}
				
				if ($msz>0) {
					hr();
					$result8 = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn FROM $dbname.serwis_magazyn WHERE ((belongs_to=$es_filia) and (magazyn_id=$msz)) LIMIT 1", $conn) or die($k_b);
					list($temp_id,$temp_nazwa,$temp_model,$temp_sn)=mysql_fetch_array($result8);
					echo $change_color_start."<b>$temp_nazwa $temp_model</b><br />SN: $temp_sn".$change_color_stop;
					$wykonane_cz='Pobranie sprzętu zastępczego : '.$temp_nazwa.' '.$temp_model.', o nr seryjnym '.$temp_sn;
					$c4='on';
					$pelnanazwakomorki = $temp_pion_nazwa." ".$mup;
					if ($naprawa_przekazana_do_innej_filii==1) { 
						if ($n_przekaz_do!=$es_filia)
							if ($n_zgl_id>0) {
								echo "<a title=' Pobierz sprzęt serwisowy $temp_nazwa $temp_model (SN: $temp_sn) z $mup '><input class=imgoption type=image src=img//wycofaj_z_komorki.gif onclick=\"newWindow_r(700,595,'z_naprawy_wycofaj_sprzet_serwisowy.php?id=$mid&cs=$mstatus&tup=".urlencode($mup)."&szid=$msz&sz=$msz&hd_zgl_nr=$n_zgl_id&from=hd&up=".urlencode($pelnanazwakomorki)."&ewid_id=$mewid')\"></a>";
							} else {
								echo "<a title=' Pobierz sprzęt serwisowy $temp_nazwa $temp_model (SN: $temp_sn) z $mup '><input class=imgoption type=image src=img//wycofaj_z_komorki.gif onclick=\"newWindow_r(700,595,'z_naprawy_wycofaj_sprzet_serwisowy.php?id=$mid&cs=$mstatus&tup=".urlencode($mup)."&szid=$msz&sz=$msz&ewid_id=$mewid')\"></a>";
							}
					} else {
						if ($n_zgl_id>0) {
							echo "<a title=' Pobierz sprzęt serwisowy $temp_nazwa $temp_model (SN: $temp_sn) z $mup '><input class=imgoption type=image src=img//wycofaj_z_komorki.gif onclick=\"newWindow_r(700,595,'z_naprawy_wycofaj_sprzet_serwisowy.php?id=$mid&cs=$mstatus&tup=".urlencode($mup)."&szid=$msz&sz=$msz&hd_zgl_nr=$n_zgl_id&from=hd&up=".urlencode($pelnanazwakomorki)."&ewid_id=$mewid')\"></a>";
						} else {
							echo "<a title=' Pobierz sprzęt serwisowy $temp_nazwa $temp_model (SN: $temp_sn) z $mup '><input class=imgoption type=image src=img//wycofaj_z_komorki.gif onclick=\"newWindow_r(700,595,'z_naprawy_wycofaj_sprzet_serwisowy.php?id=$mid&cs=$mstatus&tup=".urlencode($mup)."&szid=$msz&sz=$msz&ewid_id=$mewid')\"></a>";
						}
					}					
				} else $wykonane_cz='';
			_td();
			td_img(";c");
				if ($muwagisa=='1') {
					echo "<a title=' Czytaj uwagi '><input type=image class=imgoption src=img/comment.gif onclick=\"newWindow(480,300,'p_naprawy_uwagi.php?id=$mid')\"></a>";
				} 
				echo "<a title=' Edytuj uwagi '><input type=image class=imgoption src=img/edit_comment.gif onclick=\"newWindow(480,300,'e_naprawy_uwagi.php?id=$mid')\"></a>";
				if ($es_m==1) include("p_filia_nazwa.php");
			_td();			
			td_(";c");
				$d= Date('d');
				$m= Date('m');
				$r= Date('Y');
				if ($es_m==1) {
					$sql5 = "SELECT up_id FROM $dbname.serwis_komorki WHERE (up_nazwa='$mup') LIMIT 1";
				} else {
					$sql5 = "SELECT up_id FROM $dbname.serwis_komorki WHERE up_nazwa='$mup' AND (belongs_to=$bt) LIMIT 1";
				}
				$wynik = mysql_query($sql5,$conn) or die($k_b);
				list($upid)=mysql_fetch_array($wynik);
				
				if ($mstatus=='3') { 
				
					// **************************************************
					if ($naprawa_przekazana_do_innej_filii==1) {
						// informacje o tym że sprzęt jest w innej filii / oddziale
							if ($n_przekaz_do==$es_filia) {
								//jeżeli sprzęt jest w serwisie zewnętrznym - daj możliwość zmiany serwisu
								echo "<a title=' Zwróć naprawiony sprzęt do filli/oddziału źródłowego'><input class=imgoption type=image src=img//naprawa_przekaz_zwrot.gif onclick=\"newWindow(600,250,'z_naprawa_przekaz_zwrot.php?id=$mid&cs=$mstatus&tup=$mup')\"></a>"; 
							}
					
					} else {
						//jeżeli sprzęt jest w serwisie zewnętrznym - daj możliwość zmiany serwisu
						if ($mewid_id==0) {
								echo "<a title=' Edytuj informacje o naprawie '><input type=image class=imgoption src=img/edit.gif onclick=\"newWindow(600,350,'e_naprawy.php?id=$mid&up=".urlencode($mup)."&typ=".urlencode($mnazwa)."&from_ewid=0')\"></a>";
						//	}			
						} else { 
							echo "<a title=' Edytuj informacje o naprawie (możliwość aktualizacji w Ewidencji sprzętu) '><input type=image class=imgoption src=img/edit_ewid.gif onclick=\"newWindow(600,350,'e_naprawy.php?id=$mid&up=".urlencode($mup)."&typ=".urlencode($mnazwa)."&from_ewid=1&ewid_id=$mewid_id')\"></a>";
						}
						
						if ($n_zgl_id>0) {
							echo "<a title=' Zwróć towar do klienta '><input class=imgoption type=image src=img//return.gif  onclick=\"newWindow_r(700,595,'z_naprawy_napraw5.php?id=$mid&cs=$mstatus&sz=$msz&tup=".urlencode($mup)."&mni=$mni&msn=$msn&upid=$upid&new_upid=".$temp_upid."&ewid_id=$mewid_id&hd_zgl_nr=$n_zgl_id')\"></a>";
						} else {
							echo "<a title=' Zwróć towar do klienta '><input class=imgoption type=image src=img//return.gif  onclick=\"newWindow_r(700,595,'z_naprawy_napraw5.php?id=$mid&cs=$mstatus&sz=$msz&tup=".urlencode($mup)."&mni=$mni&msn=$msn&upid=$upid&new_upid=".$temp_upid."&ewid_id=$mewid_id')\"></a>";
						}
						
						//
					}
					// **************************************************
				}	

				echo "<a title=' Pokaż szczegóły '><input class=imgoption type=image src=img/detail.gif  onclick=\"newWindow(800,600,'p_naprawy_szczegoly.php?id=$mid')\"></a>";

				$naprawy_zmiany_result = mysql_query("SELECT naprawa_hz_id FROM $dbname.serwis_naprawa_historia_zmian WHERE (naprawa_hz_naprawa_id=$mid) and (belongs_to=$es_filia)", $conn) or die($k_b);
				if (mysql_num_rows($naprawy_zmiany_result)>0)
				echo "<a title=' Pokaż historię zmian w naprawie '><input type=image class=imgoption src=img//faktury_nz.gif onclick=\"newWindow(800,500,'p_naprawy_historia_zmian.php?id=$mid&up=".urlencode($mup)."&sn=".$msn."&model=".urlencode($mmodel)."&typ=".urlencode($mnazwa)."')\"></a>";

				if ($n_zgl_id>0) {
					$LinkHDZglNr=$n_zgl_id; include('linktohelpdesk.php');
				}				
				
			_td();
		_tr();
    }
endtable();

echo "</div>";
}
//}
?>
</body>
</html>