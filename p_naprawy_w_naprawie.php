<?php include_once('header.php'); ?>
<body>
<?php
if ($es_m==1) {
	$result = mysql_query("SELECT * FROM $dbname.serwis_naprawa WHERE (naprawa_status='1')", $conn) or die($k_b);
} else {
	$result = mysql_query("SELECT * FROM $dbname.serwis_naprawa WHERE (naprawa_status='1') and ((belongs_to=$es_filia) or ((naprawa_przekazanie_naprawy_do=$es_filia) and (naprawa_przekazanie_zakonczone=0))) ", $conn) or die($k_b);
}
if (mysql_num_rows($result)!=0) {
	br();
	pageheader("Przeglądanie uszkodzonego sprzętu - naprawy w serwisach zewnętrznych",1);

	?><script>ShowWaitingMessage('','a');</script><?php ob_flush(); flush();
	
	if ($pokaz_filtrowanie_w_naprawach==1) {
		startbuttonsarea("center");
		$moja_akcja = 'npswsz';
		$moj_status = '1';
		
		echo "<form name=naprawy action=main.php method=GET>";
		echo "Pokaż sprzęt z komórki: ";
		
		$sql_lista_p = "SELECT DISTINCT(serwis_naprawa.naprawa_pobrano_z),serwis_piony.pion_nazwa,serwis_naprawa.belongs_to,serwis_filie.filia_nazwa FROM $dbname.serwis_naprawa, $dbname.serwis_piony, $dbname.serwis_komorki, $dbname.serwis_filie WHERE (serwis_naprawa.belongs_to=serwis_filie.filia_id) and ((serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_nazwa=serwis_naprawa.naprawa_pobrano_z)) and (serwis_naprawa.naprawa_status='$moj_status') and ((serwis_naprawa.belongs_to=$es_filia) or ((serwis_naprawa.naprawa_przekazanie_naprawy_do=$es_filia) and (serwis_naprawa.naprawa_przekazanie_zakonczone=0))) "; 
		
		if ($_REQUEST[wybierz_komorke]!='') $sql_lista_p .= " and (serwis_naprawa.naprawa_pobrano_z='".$_REQUEST[wybierz_komorke]."') ";
		if ($_REQUEST[wybierz_typ]!='') $sql_lista_p .= " and (serwis_naprawa.naprawa_nazwa='".$_REQUEST[wybierz_typ]."') ";
		
		$sql_lista_p .= " ORDER BY serwis_piony.pion_nazwa, serwis_naprawa.naprawa_pobrano_z";
			
		$wynik_lista_typ = mysql_query($sql_lista_p,$conn) or die($k_b);
			
		echo "<select name=wybierz_komorke onChange='document.location.href=document.naprawy.wybierz_komorke.options[document.naprawy.wybierz_komorke.selectedIndex].value'>";

			echo "<option ";
			//if ($wybierz_komorke=='') echo "SELECTED ";
			echo "value='main.php?action=$moja_akcja&wybierz_komorke=&p=$_REQUEST[p]&b=$_REQUEST[b]&bn=$_REQUEST[bn]&wybierz_typ=".urlencode($_REQUEST[wybierz_typ])."'>wybierz komórkę</option>\n";	
			while (list($temp_nazwa_komorki,$temp_pion_komorki,$temp_bt,$temp_bt_nazwa)=mysql_fetch_array($wynik_lista_typ)) {
				echo "<option "; 
				if (($wybierz_komorke==$temp_nazwa_komorki) && ($p==$temp_pion_komorki) && ($b=$temp_bt)) echo "SELECTED ";
				echo "value='main.php?action=$moja_akcja&wybierz_komorke=".urlencode($temp_nazwa_komorki)."&p=$temp_pion_komorki&b=$temp_bt&bn=".urlencode($temp_bt_nazwa)."&wybierz_typ=".$_REQUEST[wybierz_typ]."'>";
				
				if ($es_filia!=$temp_bt) {
					echo "$temp_pion_komorki $temp_nazwa_komorki | $temp_bt_nazwa";
				} else {
					echo "$temp_pion_komorki $temp_nazwa_komorki";
				}
				echo "</option>\n";	
			}
		echo "</select>";
		echo "<input type=hidden name=action value='$moja_akcja'>";


		$sql_lista_p = "SELECT DISTINCT(serwis_naprawa.naprawa_nazwa) FROM $dbname.serwis_naprawa WHERE (serwis_naprawa.naprawa_status='$moj_status') and ((serwis_naprawa.belongs_to=$es_filia) or ((serwis_naprawa.naprawa_przekazanie_naprawy_do=$es_filia) and (serwis_naprawa.naprawa_przekazanie_zakonczone=0))) ";

		if ($_REQUEST[wybierz_komorke]!='') $sql_lista_p .= " and (serwis_naprawa.naprawa_pobrano_z='".$_REQUEST[wybierz_komorke]."') ";
		if ($_REQUEST[wybierz_typ]!='') $sql_lista_p .= " and (serwis_naprawa.naprawa_nazwa='".$_REQUEST[wybierz_typ]."') ";
		
		$sql_lista_p .= " ORDER BY naprawa_nazwa";

		$wynik_lista_typ = mysql_query($sql_lista_p,$conn) or die($k_b);
			
		echo "&nbsp;<select name=wybierz_typ onChange='document.location.href=document.naprawy.wybierz_typ.options[document.naprawy.wybierz_typ.selectedIndex].value'>";

			echo "<option ";
			//if ($wybierz_typ=='') echo "SELECTED ";
			echo "value='main.php?action=$moja_akcja&wybierz_komorke=".urlencode($_REQUEST[wybierz_komorke])."&p=$_REQUEST[p]&b=$_REQUEST[b]&bn=$_REQUEST[bn]&wybierz_typ='>wybierz typ</option>\n";	
			while (list($temp_typ)=mysql_fetch_array($wynik_lista_typ)) {
				echo "<option "; 
				if ($wybierz_typ==$temp_typ) echo "SELECTED ";
				echo "value='main.php?action=$moja_akcja&wybierz_komorke=".$_REQUEST[wybierz_komorke]."&p=".$_REQUEST[p]."&b=".$_REQUEST[b]."&bn=".$_REQUEST[bn]."&wybierz_typ=".$temp_typ."'>";
				echo "$temp_typ";

				echo "</option>\n";	
			}
		echo "</select>";
		
		if (($_REQUEST[wybierz_komorke]!='') || ($_REQUEST[wybierz_typ]!='')) echo "&nbsp;<input type=button class=buttons value='Pokaż wszystko' onClick=\"document.location.href='main.php?action=$moja_akcja&wybierz_komorke=&wybierz_typ='\">";

		
		if (($_REQUEST[wybierz_komorke]!='') && ($_REQUEST[b]!='') && ($_REQUEST[wybierz_typ]=='')) {
			$sql22 = "SELECT * FROM $dbname.serwis_naprawa WHERE (naprawa_status='$moj_status') and ((belongs_to=".$es_filia.") or ((naprawa_przekazanie_naprawy_do=".$es_filia.") and (naprawa_przekazanie_zakonczone=0))) and (naprawa_pobrano_z='".$_REQUEST[wybierz_komorke]."')";
			$result = mysql_query($sql22, $conn) or die($k_b);
		}
		
		if (($_REQUEST[wybierz_typ]!='') && ($_REQUEST[wybierz_komorke]!='')) {
			$sql22 = "SELECT * FROM $dbname.serwis_naprawa WHERE (naprawa_status='$moj_status') and ((belongs_to=".$es_filia.") or ((naprawa_przekazanie_naprawy_do=".$es_filia.") and (naprawa_przekazanie_zakonczone=0))) and (naprawa_nazwa='".$_REQUEST[wybierz_typ]."') and (naprawa_pobrano_z='".$_REQUEST[wybierz_komorke]."')";
			$result = mysql_query($sql22, $conn) or die($k_b);	
		}
		
		if (($_REQUEST[wybierz_typ]!='') && ($_REQUEST[wybierz_komorke]=='')) {
			$sql22 = "SELECT * FROM $dbname.serwis_naprawa WHERE (naprawa_status='$moj_status') and ((belongs_to=".$es_filia.") or ((naprawa_przekazanie_naprawy_do=".$es_filia.") and (naprawa_przekazanie_zakonczone=0))) and (naprawa_nazwa='".$_REQUEST[wybierz_typ]."')";
			$result = mysql_query($sql22, $conn) or die($k_b);	
		}	
		

		$count_rows = mysql_num_rows($result);
		echo "&nbsp;| Łącznie: <b>$count_rows pozycji</b>";
		
		echo "</form>";
		
		endbuttonsarea();
	}
	
	starttable();
	if ($es_m==1) {
		th("30;c;LP|;;Nazwa Model<br />Numer seryjny, inwentarzowy|;;Pobrano z|;;Przyjęte przez<br />Data przyjęcia|;;Wysłane przez<br />Data wysyłki|;c;Nazwa serwisuSprzęt zastępczy<br/>Termin upływa za|;c;Uwagi<br /><sub>Filia</sub>|;c;Opcje",$es_prawa);
	} else {
		th("30;c;LP|;;Nazwa Model<br />Numer seryjny, inwentarzowy|;;Pobrano z|;;Przyjęte przez<br />Data przyjęcia|;;Wysłane przez<br />Data wysyłki|;c;Nazwa serwisu<br />Sprzęt zastępczy<br/>Termin upływa za|;c;Uwagi|;c;Opcje",$es_prawa);
	}
	$i = 0;
	
	$KierownikId = $kierownik_nr;
	
	//$r40 = mysql_query("SELECT filia_leader FROM $dbname.serwis_filie WHERE (filia_id='$es_filia') LIMIT 1", $conn) or die($k_b);
	//list($KierownikId)=mysql_fetch_array($r40);	
	
	while ($dane = mysql_fetch_array($result)) {
		$mid = $dane['naprawa_id'];						$mnazwa = $dane['naprawa_nazwa'];
		$mmodel= $dane['naprawa_model'];				$msn = $dane['naprawa_sn'];
		$mni= $dane['naprawa_ni'];						$muwagisa= $dane['naprawa_uwagi_sa'];
		$muwagi= $dane['naprawa_uwagi'];				$mup= $dane['naprawa_pobrano_z'];
		$moo= $dane['naprawa_osoba_pobierajaca'];		$mdp= $dane['naprawa_data_pobrania'];
		$mnfs= $dane['naprawa_fs_nazwa'];				$mnfk= $dane['naprawa_fk_nazwa'];
		$mnow= $dane['naprawa_osoba_wysylajaca'];		$mndw= $dane['naprawa_data_wysylki'];
		$mnnlp= $dane['naprawa_nr_listu_przewozowego'];	$mnptn= $dane['naprawa_przewidywany_termin_naprawy'];
		$mstatus= $dane['naprawa_status'];				$bt = $dane['belongs_to'];
		$msz= $dane['naprawa_sprzet_zastepczy_id'];		$mewid= $dane['naprawa_ew_id'];	
		$mewid_id = $dane['naprawa_ew_id'];

		$n_przekaz_do = $dane['naprawa_przekazanie_naprawy_do'];
		$n_przekaz_data = $dane['naprawa_przekazanie_naprawy_data'];
		$n_przekaz_osoba = $dane['naprawa_przekazanie_naprawy_osoba'];
		$n_odbior_data = $dane['naprawa_odbior_z_filii_data'];
		$n_odbior_osoba = $dane['naprawa_odbior_z_filii_osoba'];	
		
		$n_przekazanie_zakonczone = $dane['naprawa_przekazanie_zakonczone'];
		$n_przekazanie_naprawa_wykonana = $dane['naprawa_przekazanie_naprawa_wykonana'];
		$n_zgl_id = $dane['naprawa_hd_zgl_id'];

		if ($n_zgl_id=='0') $n_zgl_id = '';
		
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
		
		if ($_GET[id]==$mid) {
			tbl_tr_color_with_border($i,'#FFFF55');
		} else tbl_tr_highlight($i);
		
		$i++;
		td("30;c;<a class=normalfont href=# title=' $mid '>".$change_color_start."".$i."".$change_color_stop."</a>");
		//td_("50%;nw;<b>".$mnazwa." ".$mmodel."</b><br />".$msn.", ".$mni."");
		echo "<td>";
		//td_("50%;nw;<b>".$change_color_start."".$mnazwa." ".$mmodel."</b><br />".$msn." / ".$mni."".$change_color_stop."");
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
			
				echo "<a class=normalfont title=' Szczegółowe informacje o $temp_pion_nazwa $mup ' onclick=\"newWindow_r(700,600,'p_komorka_szczegoly.php?id=$temp_up_id')\" href=#><b>".$change_color_start."$temp_pion_nazwa $mup".$change_color_stop."</b></a>";	
				//echo "".$msn."<br />".$mni."";
			_td();
			td_(";;".$change_color_start."".$moo."".$change_color_stop."<br />".$change_color_start."".substr($mdp,0,16)."".$change_color_stop."");
			td_(";;".$change_color_start."".$mnow."".$change_color_stop."<br />".$change_color_start."".substr($mndw,0,16)."".$change_color_stop."");
			//td(";;".$mnow."<br />".$mndw."");
			td_(";c");
				if (strlen($mnfs)>20){ $fs=substr($mnfs,0,20)."..."; } else $fs=$mnfs;

				if ($mstatus=='1') { 
					//echo "<a class=normalfont title=' Pokaż szczegóły naprawy ' href=# onclick=\"newWindow_r(800,600,'p_naprawy_szczegoly.php?id=$mid')\">w serwisie zewnętrznym</a><br />";
					//echo "<a title=' Sprzęt jest w naprawie w serwisie zewnętrznym' href=#><img class=imgoption src=img/snapraw_w.gif border=0></a><br />";
					
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
						echo "<hr />Wysyłka: <a class=normalfont title=' Numer listu przewozowego : $mnnlp ' href=# onclick=\"newWindow(600,280,'p_fz_szczegoly.php?id=$fk_id')\"><b>".$change_color_start."".$mnfk."".$change_color_stop."</a></b>";
					}
				}
				
				if ($msz>0) {
					hr();
					$result8 = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn FROM $dbname.serwis_magazyn WHERE ((belongs_to=$bt) and (magazyn_id=$msz)) LIMIT 1", $conn) or die($k_b);
					list($temp_id,$temp_nazwa,$temp_model,$temp_sn)=mysql_fetch_array($result8);
					echo $change_color_start."<b>$temp_nazwa $temp_model</b><br />SN: $temp_sn".$change_color_stop;
					//echo "<b>$temp_nazwa $temp_model</b><br />SN: $temp_sn</b>";
					$wykonane_cz='Pobranie sprzętu zastępczego : '.$temp_nazwa.' '.$temp_model.', o nr seryjnym '.$temp_sn;
									
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
				
				}

				$dddd = Date('Y-m-d H:i:s');
				if ($mnptn!="0000-00-00 00:00:00") {
					$timestamp1 = strtotime($mnptn);
					$timestamp2 = strtotime($dddd);
					if ($timestamp2>$timestamp1) {
						$dni= $timestamp2-$timestamp1;
					} else $dni = $timestamp1-$timestamp2;
					$wynik = idate('d',$dni);
					if ($timestamp2>$timestamp1) { echo "<b>-</b>"; }
					
					$cal_dni = calculate_datediff($mnptn,$dddd,"d");
					if ($cal_dni[0]=='-') {
						echo "<hr /><font color=green><b><a title=' Termin naprawy upływa za ".br2nl(substr($cal_dni,1,strlen($cal_dni)))." '>".substr($cal_dni,1,strlen($cal_dni))."</a></b></font>";
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
				if ($es_m==1) include("p_filia_nazwa.php");
			_td();			
			td_(";c");
			
				if ($mstatus=='1') { 
				
					// **************************************************
					if ($naprawa_przekazana_do_innej_filii==1) {
						// informacje o tym że sprzęt jest w innej filii / oddziale
							if ($n_przekaz_do==$es_filia) {
								//jeżeli sprzęt jest w serwisie zewnętrznym - daj możliwość zmiany serwisu
								echo "<a title=' Zmień serwis '><input class=imgoption type=image src=img//zmienserwis.gif onclick=\"newWindow(590,270,'z_naprawy_zmien_serwis.php?nid=$mid&cs=$mstatus&tup=".urlencode($mup)."&staryserwis=$fs_id&fk=$mnfk&fs=$mnfs&fknlp=$mnnlp')\"></a>";
								
								echo "<a title=' Wycofaj sprzęt z serwisu '><input class=imgoption type=image src=img//wycofaj_z_serwisu.gif onclick=\"newWindow(550,400,'z_naprawy_wycofaj_z_serwisu.php?id=$mid&cs=$mstatus&tup=".urlencode($mup)."&szid=$msz&sz=$msz&ewid_id=$mewid_id')\"></a>";
								
								echo "<a title=' Zwróć nienaprawiony sprzęt do filli/oddziału źródłowego'><input class=imgoption type=image src=img//naprawa_przekaz_zwrot_bez_naprawy.gif onclick=\"newWindow(600,350,'z_naprawa_przekaz_zwrot.php?id=$mid&cs=$mstatus&tup=".urlencode($mup)."&hdzgl=$n_zgl_id')\"></a>"; 
								
								//echo "<a title=' Wycofaj sprzęt z serwisu '><input class=imgoption type=image src=img//wycofaj_z_serwisu.gif onclick=\"newWindow(550,400,'z_naprawy_wycofaj_z_serwisu.php?id=$mid&cs=$mstatus&tup=".urlencode($mup)."&szid=$msz&sz=$msz&ewid_id=$mewid_id')\"></a>";
								echo "<a title=' Zmień status na naprawiony '><input class=imgoption type=image src=img//naprawiaj.gif onclick=\"newWindow_r(800,600,'z_naprawy_napraw3.php?id=$mid&cs=$mstatus&tup=".urlencode($mup)."&hdzglid=$n_zgl_id&obcezgloszenie=1')\"></a>";

								//	echo "<a title=' Przekaż sprzęt do naprawy do innej filli/oddziału '><input class=imgoption type=image src=img//naprawa_przekaz.gif onclick=\"newWindow(600,250,'z_naprawa_przekaz.php?id=$mid&cs=$mstatus&tup=".urlencode($mup)."')\"></a>"; 
							}
					
					} else {
						//jeżeli sprzęt jest w serwisie zewnętrznym - daj możliwość zmiany serwisu
						echo "<a title=' Zmień serwis '><input class=imgoption type=image src=img//zmienserwis.gif onclick=\"newWindow(590,270,'z_naprawy_zmien_serwis.php?nid=$mid&cs=$mstatus&tup=".urlencode($mup)."&staryserwis=$fs_id&fk=$mnfk&fs=$mnfs&fknlp=$mnnlp')\"></a>";					
					
						echo "<a title=' Wycofaj sprzęt z serwisu '><input class=imgoption type=image src=img//wycofaj_z_serwisu.gif onclick=\"newWindow(550,400,'z_naprawy_wycofaj_z_serwisu.php?id=$mid&cs=$mstatus&tup=".urlencode($mup)."&szid=$msz&sz=$msz&ewid_id=$mewid_id')\"></a>";
						
						if ($mewid_id==0) {
								echo "<a title=' Edytuj informacje o naprawie '><input type=image class=imgoption src=img/edit.gif onclick=\"newWindow(600,350,'e_naprawy.php?id=$mid&up=".urlencode($mup)."&typ=".urlencode($mnazwa)."&from_ewid=0')\"></a>";
						//	}
						} else { 
							echo "<a title=' Edytuj informacje o naprawie (możliwość aktualizacji w Ewidencji sprzętu) '><input type=image class=imgoption src=img/edit_ewid.gif onclick=\"newWindow(600,350,'e_naprawy.php?id=$mid&up=".urlencode($mup)."&typ=".urlencode($mnazwa)."&from_ewid=1&ewid_id=$mewid_id')\"></a>";
						}
						
						echo "<a title=' Przekaż sprzęt do naprawy do innej filli/oddziału '><input class=imgoption type=image src=img//naprawa_przekaz.gif onclick=\"newWindow(600,350,'z_naprawa_przekaz.php?id=$mid&cs=$mstatus&tup=".urlencode($mup)."&cs=1&hdzgl=$n_zgl_id')\"></a>"; 

						echo "<a title=' Zmień status na naprawiony '><input class=imgoption type=image src=img//naprawiaj.gif onclick=\"newWindow_r(800,600,'z_naprawy_napraw3.php?id=$mid&cs=$mstatus&tup=".urlencode($mup)."&hdzglid=$n_zgl_id')\"></a>";
						
					}
					// **************************************************				
				
				}

	
				echo "<a title=' Pokaż szczegóły '><input class=imgoption type=image src=img/detail.gif  onclick=\"newWindow_r(800,600,'p_naprawy_szczegoly.php?id=$mid')\"></a>";
				
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
} else {
		br();
		errorheader("Brak uszkodzonego sprzętu w serwisach zewnętrznych");

		startbuttonsarea("right");
		if ($_REQUEST[id]>0) addbackbutton('Wróć do poprzedniego widoku');
		endbuttonsarea();

		?>
		<meta http-equiv="REFRESH" content="<?php echo $http_refresh; ?>;url=<?php echo "$linkdostrony";?>main.php">
		<?php
	}

startbuttonsarea("left");
oddziel();
echo "<span style='float:left'>";
//addownlinkbutton("'Przyjmij uszkodzony sprzęt'","button","button","newWindow_r(800,600,'z_naprawy_przyjmij.php')");
//echo " <br /> ";
addlinkbutton("'Wycofany sprzęt na stanie'","main.php?action=nsw");
addlinkbutton("'Uszkodzony sprzęt na stanie'","main.php?action=npus");
echo " <br />&nbsp;Naprawiany: ";
addlinkbutton("'We własnym zakresie'","main.php?action=nwwz");
//addlinkbutton("'W serwisach zewnętrznych'","main.php?action=npswsz");
addlinkbutton("'W serwisach lokalnych'","main.php?action=nsnrl");
echo " <br /> ";
addlinkbutton("'Naprawiony sprzęt na stanie'","p_naprawy_zakonczone.php");
echo "</span>";

if ($_REQUEST[id]>0) addbackbutton('Wróć do poprzedniego widoku');
endbuttonsarea();

startbuttonsarea("right");
addlinkbutton("'Przeglądaj zgłoszenia'","hd_p_zgloszenia.php?showall=0&p5=BZ&sort=AD&page=1");
addbuttons("start");
endbuttonsarea();
	
?>
<script>HideWaitingMessage('a');</script>

</body>
</html>