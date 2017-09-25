<?php //include_once('header.php'); ?>

<?php

$result = mysql_query("SELECT * FROM $dbname.serwis_naprawa WHERE ((naprawa_status='1') || (naprawa_status='2')) and ((belongs_to=$es_filia) or ((naprawa_przekazanie_naprawy_do=$es_filia) and (naprawa_przekazanie_zakonczone=0)))", $conn) or die($k_b);

$wynik1 = mysql_num_rows($result);
if ($wynik1!=0) {
	$saprzeterminowane=0;
	
	while ($dane = mysql_fetch_array($result)) {
		$mndw	= $dane['naprawa_data_wysylki'];
		$mnptn	= $dane['naprawa_przewidywany_termin_naprawy'];
		
		if ($mnptn!="0000-00-00 00:00:00") {
			$timestamp1 = strtotime($mnptn);
			$timestamp2 = strtotime($mndw);
			$dni = abs(strtotime(date('Y-m-d'))-strtotime($mnptn)) / 86400;
			$wynik = idate('d',$dni);
			$dzisiaj = strtotime(date('Y-m-d'));
			$eee = $dzisiaj-$timestamp1;
			$eee = abs($eee/86400);
			if (($eee!=0) && ($dzisiaj>$timestamp1)) $saprzeterminowane+=1;
		} 
	}
}

$result = mysql_query("SELECT * FROM $dbname.serwis_naprawa WHERE ((naprawa_status='1') || (naprawa_status='2')) and ((belongs_to=$es_filia) or ((naprawa_przekazanie_naprawy_do=$es_filia) and (naprawa_przekazanie_zakonczone=0)))", $conn) or die($k_b);

if ((mysql_num_rows($result)!=0) && ($saprzeterminowane>0)) {
	//br();
	echo "<h2 style='font-size:13px;font-weight:normal;padding-top:4px;padding-bottom:4px;margin-top:0px;margin-bottom:5px;text-align:left;background: #FF9999;color:#313131;display: block; border: 1px solid #FC8C8C;'>&nbsp;";
	
	echo "<a href=# class=normalfont id=pokaz_po_terminie style='display:none' type=button onClick=\"document.getElementById('pokaz_inf_po_terminie').style.display=''; document.getElementById('pokaz_po_terminie').style.display='none'; document.getElementById('ukryj_po_terminie').style.display=''; createCookie('p_naprawy_po_terminie','TAK',365);  \"><img class=imgoption style='border:0px' type=image src=img/expand.gif width=11 height=11>&nbsp;Przeglądanie naprawianego sprzętu - przekroczone terminy naprawy (<b>$wynik1</b>) </a>";
	
	echo "<a href=# class=normalfont id=ukryj_po_terminie style='display:none' type=button onClick=\"document.getElementById('pokaz_inf_po_terminie').style.display='none'; document.getElementById('pokaz_po_terminie').style.display=''; document.getElementById('ukryj_po_terminie').style.display='none'; createCookie('p_naprawy_po_terminie','NIE',365);\"><img class=imgoption style='border:0px' type=image src=img/collapse.gif width=11 height=11>&nbsp;Przeglądanie naprawianego sprzętu - przekroczone terminy naprawy (<b>$wynik1</b>) </a>";
	
	echo "</h2>";
	
	echo "<div id=pokaz_inf_po_terminie>";
	
	?>
	<script>
	if (readCookie('p_naprawy_po_terminie')=='TAK') {
		document.getElementById('pokaz_po_terminie').style.display='none';
		document.getElementById('ukryj_po_terminie').style.display='';
		document.getElementById('pokaz_inf_po_terminie').style.display='';
	} else {
		document.getElementById('pokaz_po_terminie').style.display='';
		document.getElementById('ukryj_po_terminie').style.display='none';
		document.getElementById('pokaz_inf_po_terminie').style.display='none';
	}

	if (readCookie('p_naprawy_po_terminie')==null) {
		document.getElementById('pokaz_po_terminie').style.display='none';
		document.getElementById('ukryj_po_terminie').style.display='';
		document.getElementById('pokaz_inf_po_terminie').style.display='';
		createCookie('p_naprawy_po_terminie','TAK',365);
	}

	</script>
	<?php
	
	starttable();
	th("30;c;LP|;;Nazwa Model<br />Numer seryjny, inwentarzowy|;;Pobrano z|;;Przyjęte przez<br />Data przyjęcia|;;Wysłane przez<br />Data wysyłki|;c;Status<hr />Po terminie|;c;Uwagi|;c;Opcje",$es_prawa);
	$i = 150;
	$y = 1;
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
		$mnfs= $dane['naprawa_fs_nazwa'];				
		$mnfk= $dane['naprawa_fk_nazwa'];
		$mnow= $dane['naprawa_osoba_wysylajaca'];		
		$mndw= $dane['naprawa_data_wysylki'];
		$mnnlp= $dane['naprawa_nr_listu_przewozowego'];	
		$mnptn= $dane['naprawa_przewidywany_termin_naprawy'];
		$mstatus= $dane['naprawa_status'];
		$mewid_id = $dane['naprawa_ew_id'];

		$n_przekaz_do = $dane['naprawa_przekazanie_naprawy_do'];
		$n_przekaz_data = $dane['naprawa_przekazanie_naprawy_data'];
		$n_przekaz_osoba = $dane['naprawa_przekazanie_naprawy_osoba'];
		$n_odbior_data = $dane['naprawa_odbior_z_filii_data'];
		$n_odbior_osoba = $dane['naprawa_odbior_z_filii_osoba'];	
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

		$wiersz=0;
		
		if ($mnptn!="0000-00-00 00:00:00") {
			$timestamp1 = strtotime($mnptn);
			$timestamp2 = strtotime($mndw);
			if ($timestamp2>$timestamp1) {
			  $dni= $timestamp2-$timestamp1;
			} else $dni = $timestamp1-$timestamp2;
			$dni = abs(strtotime(date('Y-m-d'))-strtotime($mnptn)) / 86400;
			$wynik = idate('d',$dni);
			$dzisiaj = strtotime(date('Y-m-d'));
			$eee = $dzisiaj-$timestamp1;
			$eee = abs($eee/86400);
			if (($eee!=0) && ($dzisiaj>$timestamp1)) { $wiersz=1; }
		}

		if ($wiersz==1) {  
			tbl_tr_highlight($i);
				$i++;
//				td("30;c;<a class=normalfont href=# title=' $mid '>".$i."</a>");
				td("30;c;<a class=normalfont href=# title=' $mid '>".$change_color_start."".$y."".$change_color_stop."</a>");
				$y++;
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
					$sql_up = "SELECT up_id, up_pion_id FROM $dbname.serwis_komorki WHERE (up_nazwa='$mup') and (belongs_to=$es_filia) LIMIT 1";
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

					echo "<a class=normalfont title=' Szczegółowe informacje o $temp_pion_nazwa $mup ' onclick=\"newWindow_r(700,600,'p_komorka_szczegoly.php?id=$temp_up_id')\" href=#><b>".$change_color_start."$temp_pion_nazwa $mup".$change_color_stop."</b></a><br />";				//echo "".$msn."<br />".$mni."";

				td_(";;".$change_color_start."".$moo."".$change_color_stop."<br />".$change_color_start."".$mdp."".$change_color_stop."");
				td_(";;".$change_color_start."".$mnow."".$change_color_stop."<br />".$change_color_start."".$mndw."".$change_color_stop."");
				td_(";c");
					if (strlen($mnfs)>20){ $fs=substr($mnfs,0,20)."..."; } else $fs=$mnfs;
					if ($mstatus=='-1') echo "<a>pobrany od klienta</a>";
					if ($mstatus=='0') echo "<a title=' Sprzęt jest naprawiany we własnym zakresie ' href=main.php?action=nwwz&id=$mid&cs=$es><img class=imgoption src=img/w_naprawie_wz.gif border=0 width=16 width=16></a><br />";
					if ($mstatus=='1') { 
						echo "<a title=' Sprzęt jest w naprawie w serwisie zewnętrznym ' href=main.php?action=npswsz&id=$mid&cs=$es><img class=imgoption src=img/w_naprawie_sz.gif border=0 width=16 width=16></a><br />";
						$result77 = mysql_query("SELECT fz_id,fz_nazwa,fz_adres,fz_telefon,fz_fax,fz_email,fz_www FROM $dbname.serwis_fz WHERE ((fz_is_fs='on') and (fz_nazwa='$mnfs')) LIMIT 1", $conn) or die($k_b);
						list($temp_id77,$temp_nazwa77,$temp_adres77,$temp_telefon77,$temp_fax77,$temp_email77,$temp_www77)=mysql_fetch_array($result77);
						if (strlen($mnfs)>20){ $fs=substr($mnfs,0,20)."..."; } else $fs=$mnfs;
						$result9 = mysql_query("SELECT fz_id FROM $dbname.serwis_fz WHERE (fz_nazwa='$mnfs') LIMIT 1", $conn) or die($k_b);
						list($fs_id)=mysql_fetch_array($result9);
						$result9 = mysql_query("SELECT fz_id FROM $dbname.serwis_fz WHERE (fz_nazwa='$mnfk') LIMIT 1", $conn) or die($k_b);
						list($fk_id)=mysql_fetch_array($result9);
						$tel=mysql_query("SELECT fz_telefon FROM $dbname.serwis_fz WHERE (fz_nazwa='$mnfs') LIMIT 1",$conn) or die($k_b);
						list($fztel)=mysql_fetch_array($tel);
						echo "<a class=normalfont title=' Telefon do firmy : $fztel ' onclick=\"newWindow(600,280,'p_fz_szczegoly.php?id=$fs_id')\" href=#><b>".$change_color_start."".$fs."".$change_color_stop."</b></a>";
						if ($mnfk!='-1') {
							echo "<br />Wysyłka: <a class=normalfont title=' Numer listu przewozowego : $mnnlp ' href=# onclick=\"newWindow(600,280,'p_fz_szczegoly.php?id=$fk_id')\"><b>".$change_color_start."".$mnfk."".$change_color_stop."</a></b>";
						}
					}
					
					if ($mstatus=='2') echo "<a title=' Sprzęt jest w naprawie w serwisie lokalnym ' href=main.php?action=nsnrl&id=$mid&cs=$es><img class=imgoption src=img/w_naprawie_sl.gif border=0 width=16 height=16></a>";
				
					if ($mstatus=='3') echo "<a>naprawiony</a>";
					if ($mstatus=='5') echo "<a>zwrócony do klienta</a>";
					
					$dddd = Date('Y-m-d H:i:s');
					if ($mnptn!="0000-00-00 00:00:00") {
						$timestamp1 = strtotime($mnptn);
						$timestamp2 = strtotime($dddd);
						if ($timestamp2>$timestamp1) {
							$dni= $timestamp2-$timestamp1;
						} else $dni = $timestamp1-$timestamp2;
						$wynik = idate('d',$dni);
						if ($timestamp2>$timestamp1) { echo "<b></b>"; }
						
						$cal_dni = calculate_datediff($mnptn,$dddd,"d");
						if ($cal_dni[0]=='-') {
							echo "<hr /><font color=green><b><sub><a title=' Termin naprawy upływa za ".br2nl(substr($cal_dni,1,strlen($cal_dni)))." '>".substr($cal_dni,1,strlen($cal_dni))."</a></sub></b></font>";
						} else {
							echo "<hr /><font color=red><b><a title=' Minęło ".br2nl(calculate_datediff($mnptn,$dddd,"d"))." od przewidywanego czasu wykonania naprawy '>Po terminie ".calculate_datediff($mnptn,$dddd,"d")."</a></b></font>";
						}
					}
				_td();
				td_img(";c");
					if ($muwagisa=='1') {
						echo "<a title=' Czytaj uwagi '><input type=image class=imgoption src=img/comment.gif onclick=\"newWindow(480,300,'p_naprawy_uwagi.php?id=$mid')\"></a>";
					} 
					echo "<a title=' Edytuj uwagi '><input type=image class=imgoption src=img/edit_comment.gif onclick=\"newWindow(480,300,'e_naprawy_uwagi.php?id=$mid')\"></a>";
				_td();				
				td_(";c");					
					if ($mstatus==0) {	
						// **************************************************
						if ($naprawa_przekazana_do_innej_filii==1) {
							// informacje o tym że sprzęt jest w innej filii / oddziale
								if ($n_przekaz_do==$es_filia) {
									//echo "<a title=' Wycofaj sprzęt z serwisu '><input class=imgoption type=image src=img//wycofaj_z_serwisu.gif onclick=\"newWindow(550,400,'z_naprawy_wycofaj_z_serwisu.php?id=$mid&cs=$mstatus&tup=$mup&szid=$msz&sz=$msz&ewid_id=$mewid_id')\"></a>";		
									echo "<a title=' Zwróć nienaprawiony sprzęt do filli/oddziału źródłowego'><input class=imgoption type=image src=img//naprawa_przekaz_zwrot_bez_naprawy.gif onclick=\"newWindow(600,350,'z_naprawa_przekaz_zwrot.php?id=$mid&cs=$mstatus&tup=$mup&hdzgl=$n_zgl_id')\"></a>"; 
									
									echo "<a title=' Zmień status na naprawiony '><input class=imgoption type=image src=img//naprawiaj.gif onclick=\"newWindow_r(800,600,'z_naprawy_napraw3.php?id=$mid&cs=$mstatus&tup=$mup')\"></a>";
									//	echo "<a title=' Przekaż sprzęt do naprawy do innej filli/oddziału '><input class=imgoption type=image src=img//naprawa_przekaz.gif onclick=\"newWindow(600,250,'z_naprawa_przekaz.php?id=$mid&cs=$mstatus&tup=$mup')\"></a>"; 
								}
						
						} else {
							echo "<a title=' Wycofaj sprzęt z serwisu '><input class=imgoption type=image src=img//wycofaj_z_serwisu.gif onclick=\"newWindow(550,400,'z_naprawy_wycofaj_z_serwisu.php?id=$mid&cs=$mstatus&tup=$mup&szid=$msz&sz=$msz&ewid_id=$mewid_id')\"></a>";
							if ($mewid_id==0) {
								echo "<a title=' Edytuj informacje o naprawie '><input type=image class=imgoption src=img/edit.gif onclick=\"newWindow(600,350,'e_naprawy.php?id=$mid&up=".urlencode($mup)."&typ=".urlencode($mnazwa)."&from_ewid=0')\"></a>";
							//	}			
							} else { 
								echo "<a title=' Edytuj informacje o naprawie (możliwość aktualizacji w Ewidencji sprzętu) '><input type=image class=imgoption src=img/edit_ewid.gif onclick=\"newWindow(600,350,'e_naprawy.php?id=$mid&up=".urlencode($mup)."&typ=".urlencode($mnazwa)."&from_ewid=1&ewid_id=$mewid_id')\"></a>";
							}				
							echo "<a title=' Przekaż sprzęt do naprawy do innej filli/oddziału '><input class=imgoption type=image src=img//naprawa_przekaz.gif onclick=\"newWindow(600,350,'z_naprawa_przekaz.php?id=$mid&cs=$mstatus&tup=".urlencode($mup)."&cs=0&hdzgl=$n_zgl_id')\"></a>"; 
							
							echo "<a title=' Zmień status na naprawiony '><input class=imgoption type=image src=img//naprawiaj.gif onclick=\"newWindow_r(800,600,'z_naprawy_napraw3.php?id=$mid&cs=$mstatus&tup=$mup')\"></a>";				
							
						}
						// **************************************************
					}
					
					if ($mstatus=='1') { 
					
						// **************************************************
						if ($naprawa_przekazana_do_innej_filii==1) {
							// informacje o tym że sprzęt jest w innej filii / oddziale
								if ($n_przekaz_do==$es_filia) {
									//jeżeli sprzęt jest w serwisie zewnętrznym - daj możliwość zmiany serwisu
									echo "<a title=' Zmień serwis '><input class=imgoption type=image src=img//zmienserwis.gif onclick=\"newWindow(590,270,'z_naprawy_zmien_serwis.php?nid=$mid&cs=$mstatus&tup=$mup&staryserwis=$fs_id&fk=$mnfk&fs=$mnfs&fknlp=$mnnlp')\"></a>";
									
									echo "<a title=' Wycofaj sprzęt z serwisu '><input class=imgoption type=image src=img//wycofaj_z_serwisu.gif onclick=\"newWindow(550,400,'z_naprawy_wycofaj_z_serwisu.php?id=$mid&cs=$mstatus&tup=".urlencode($mup)."&szid=$msz&sz=$msz&ewid_id=$mewid_id')\"></a>";
									
									echo "<a title=' Zwróć nienaprawiony sprzęt do filli/oddziału źródłowego'><input class=imgoption type=image src=img//naprawa_przekaz_zwrot_bez_naprawy.gif onclick=\"newWindow(600,250,'z_naprawa_przekaz_zwrot.php?id=$mid&cs=$mstatus&tup=$mup')\"></a>"; 
									
									//echo "<a title=' Wycofaj sprzęt z serwisu '><input class=imgoption type=image src=img//wycofaj_z_serwisu.gif onclick=\"newWindow(550,400,'z_naprawy_wycofaj_z_serwisu.php?id=$mid&cs=$mstatus&tup=$mup&szid=$msz&sz=$msz&ewid_id=$mewid_id')\"></a>";
									echo "<a title=' Zmień status na naprawiony '><input class=imgoption type=image src=img//naprawiaj.gif onclick=\"newWindow_r(800,600,'z_naprawy_napraw3.php?id=$mid&cs=$mstatus&tup=$mup')\"></a>";

									//	echo "<a title=' Przekaż sprzęt do naprawy do innej filli/oddziału '><input class=imgoption type=image src=img//naprawa_przekaz.gif onclick=\"newWindow(600,250,'z_naprawa_przekaz.php?id=$mid&cs=$mstatus&tup=$mup')\"></a>"; 
								}
						
						} else {
							//jeżeli sprzęt jest w serwisie zewnętrznym - daj możliwość zmiany serwisu
							echo "<a title=' Zmień serwis '><input class=imgoption type=image src=img//zmienserwis.gif onclick=\"newWindow(590,270,'z_naprawy_zmien_serwis.php?nid=$mid&cs=$mstatus&tup=$mup&staryserwis=$fs_id&fk=$mnfk&fs=$mnfs&fknlp=$mnnlp')\"></a>";					
						
							echo "<a title=' Wycofaj sprzęt z serwisu '><input class=imgoption type=image src=img//wycofaj_z_serwisu.gif onclick=\"newWindow(550,400,'z_naprawy_wycofaj_z_serwisu.php?id=$mid&cs=$mstatus&tup=$mup&szid=$msz&sz=$msz&ewid_id=$mewid_id')\"></a>";
							if ($mewid_id==0) {
									echo "<a title=' Edytuj informacje o naprawie '><input type=image class=imgoption src=img/edit.gif onclick=\"newWindow(600,350,'e_naprawy.php?id=$mid&up=".urlencode($mup)."&typ=".urlencode($mnazwa)."&from_ewid=0')\"></a>";
							//	}			
							} else { 
								echo "<a title=' Edytuj informacje o naprawie (możliwość aktualizacji w Ewidencji sprzętu) '><input type=image class=imgoption src=img/edit_ewid.gif onclick=\"newWindow(600,350,'e_naprawy.php?id=$mid&up=".urlencode($mup)."&typ=".urlencode($mnazwa)."&from_ewid=1&ewid_id=$mewid_id')\"></a>";
							}				
							echo "<a title=' Przekaż sprzęt do naprawy do innej filli/oddziału '><input class=imgoption type=image src=img//naprawa_przekaz.gif onclick=\"newWindow(600,300,'z_naprawa_przekaz.php?id=$mid&cs=$mstatus&tup=".urlencode($mup)."&cs=1')\"></a>"; 
							
							echo "<a title=' Zmień status na naprawiony '><input class=imgoption type=image src=img//naprawiaj.gif onclick=\"newWindow_r(800,600,'z_naprawy_napraw3.php?id=$mid&cs=$mstatus&tup=$mup')\"></a>";				
							
						}
						// **************************************************				
					
					}

					if ($mstatus=='2') { 
					
						// **************************************************
						if ($naprawa_przekazana_do_innej_filii==1) {
							// informacje o tym że sprzęt jest w innej filii / oddziale
								if ($n_przekaz_do==$es_filia) {
									//jeżeli sprzęt jest w serwisie zewnętrznym - daj możliwość zmiany serwisu
									echo "<a title=' Zmień serwis '><input class=imgoption type=image src=img//zmienserwis.gif onclick=\"newWindow(590,270,'z_naprawy_zmien_serwis.php?nid=$mid&cs=$mstatus&tup=$mup&staryserwis=$fs_id&fk=$mnfk&fs=$mnfs&fknlp=$mnnlp')\"></a>";
									
									//echo "<a title=' Wycofaj sprzęt z serwisu '><input class=imgoption type=image src=img//wycofaj_z_serwisu.gif onclick=\"newWindow(550,400,'z_naprawy_wycofaj_z_serwisu.php?id=$mid&cs=$mstatus&tup=$mup&szid=$msz&sz=$msz&ewid_id=$mewid_id')\"></a>";

									echo "<a title=' Wycofaj sprzęt z serwisu '><input class=imgoption type=image src=img//wycofaj_z_serwisu.gif onclick=\"newWindow(550,400,'z_naprawy_wycofaj_z_serwisu.php?id=$mid&cs=$mstatus&tup=".urlencode($mup)."&szid=$msz&sz=$msz&ewid_id=$mewid_id')\"></a>";
									
									echo "<a title=' Zwróć nienaprawiony sprzęt do filli/oddziału źródłowego'><input class=imgoption type=image src=img//naprawa_przekaz_zwrot_bez_naprawy.gif onclick=\"newWindow(600,250,'z_naprawa_przekaz_zwrot.php?id=$mid&cs=$mstatus&tup=$mup')\"></a>"; 								
									
									echo "<a title=' Zmień status na naprawiony '><input class=imgoption type=image src=img//naprawiaj.gif onclick=\"newWindow_r(800,600,'z_naprawy_napraw3.php?id=$mid&cs=$mstatus&tup=$mup')\"></a>";

									//	echo "<a title=' Przekaż sprzęt do naprawy do innej filli/oddziału '><input class=imgoption type=image src=img//naprawa_przekaz.gif onclick=\"newWindow(600,250,'z_naprawa_przekaz.php?id=$mid&cs=$mstatus&tup=$mup')\"></a>"; 
								}
						
						} else {
							//jeżeli sprzęt jest w serwisie zewnętrznym - daj możliwość zmiany serwisu
							echo "<a title=' Zmień serwis '><input class=imgoption type=image src=img//zmienserwis.gif onclick=\"newWindow(590,270,'z_naprawy_zmien_serwis.php?nid=$mid&cs=$mstatus&tup=$mup&staryserwis=$fs_id&fk=$mnfk&fs=$mnfs&fknlp=$mnnlp')\"></a>";					
						
							echo "<a title=' Wycofaj sprzęt z serwisu '><input class=imgoption type=image src=img//wycofaj_z_serwisu.gif onclick=\"newWindow(550,400,'z_naprawy_wycofaj_z_serwisu.php?id=$mid&cs=$mstatus&tup=$mup&szid=$msz&sz=$msz&ewid_id=$mewid_id')\"></a>";
							if ($mewid_id==0) {
									echo "<a title=' Edytuj informacje o naprawie '><input type=image class=imgoption src=img/edit.gif onclick=\"newWindow(600,350,'e_naprawy.php?id=$mid&up=".urlencode($mup)."&typ=".urlencode($mnazwa)."&from_ewid=0')\"></a>";
							//	}			
							} else { 
								echo "<a title=' Edytuj informacje o naprawie (możliwość aktualizacji w Ewidencji sprzętu) '><input type=image class=imgoption src=img/edit_ewid.gif onclick=\"newWindow(600,350,'e_naprawy.php?id=$mid&up=".urlencode($mup)."&typ=".urlencode($mnazwa)."&from_ewid=1&ewid_id=$mewid_id')\"></a>";
							}				
							echo "<a title=' Przekaż sprzęt do naprawy do innej filli/oddziału '><input class=imgoption type=image src=img//naprawa_przekaz.gif onclick=\"newWindow(600,300,'z_naprawa_przekaz.php?id=$mid&cs=$mstatus&tup=".urlencode($mup)."&cs=2')\"></a>"; 
							
							echo "<a title=' Zmień status na naprawiony '><input class=imgoption type=image src=img//naprawiaj.gif onclick=\"newWindow_r(800,600,'z_naprawy_napraw3.php?id=$mid&cs=$mstatus&tup=$mup')\"></a>";				
							
						}
						// **************************************************				
					
					}
				
/*				if ($mewid_id==0) {
						echo "<a title=' Edytuj informacje o naprawie '><input type=image class=imgoption src=img/edit.gif onclick=\"newWindow(600,350,'e_naprawy.php?id=$mid&up=".urlencode($mup)."&typ=".urlencode($mnazwa)."&from_ewid=0')\"></a>";
				//	}			
				} else { 
					echo "<a title=' Edytuj informacje o naprawie (możliwość aktualizacji w Ewidencji sprzętu) '><input type=image class=imgoption src=img/edit_ewid.gif onclick=\"newWindow(600,350,'e_naprawy.php?id=$mid&up=".urlencode($mup)."&typ=".urlencode($mnazwa)."&from_ewid=1')\"></a>";
				}
				
				if ($mstatus=='1') { 
					//jeżeli sprzęt jest w serwisie zewnętrznym - daj możliwość zmiany serwisu
					echo "<a title=' Zmień serwis '><input class=imgoption type=image src=img//zmienserwis.gif onclick=\"newWindow(590,270,'z_naprawy_zmien_serwis.php?nid=$mid&cs=$mstatus&tup=$mup&staryserwis=$fs_id&fk=$mnfk&fs=$mnfs&fknlp=$mnnlp')\"></a>";
				}
				if ($mstatus=='2') { 
					//jeżeli sprzęt jest w serwisie zewnętrznym - daj możliwość zmiany serwisu
					echo "<a title=' Zmień serwis '><input class=imgoption type=image src=img//zmienserwis.gif onclick=\"newWindow(590,270,'z_naprawy_zmien_serwis.php?nid=$mid&cs=$mstatus&tup=$mup&staryserwis=$fs_id&lokalny=1&fk=$mnfk&fs=$mnfs&fknlp=$mnnlp')\"></a>";
				}
			
				echo "<a title=' Wycofaj sprzęt z serwisu '><input class=imgoption type=image src=img//wycofaj_z_serwisu.gif onclick=\"newWindow(550,400,'z_naprawy_wycofaj_z_serwisu.php?id=$mid&cs=$mstatus&tup=$mup&szid=$msz&sz=$msz&ewid_id=$mewid_id')\"></a>";
		
				echo "<a title=' Zmień status na naprawiony '><input class=imgoption type=image src=img//naprawiaj.gif onclick=\"newWindow_r(800,600,'z_naprawy_napraw3.php?id=$mid&cs=$mstatus&tup=$mup')\"></a>";
*/				
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
	}
endtable();
	echo "</div>";
}
?>