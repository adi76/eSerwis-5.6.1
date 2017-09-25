<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 
	include('body_start.php');
	$tup=$_GET[tup];
	$okres_od=$_GET[okres_od];
	$okres_do=$_GET[okres_do];

	if (($okres_od!="") && ($okres_do!="")) {
		if ($tup!="") {
			if ($es_m==1) {
				$sql_a = "SELECT * FROM $dbname.serwis_naprawa WHERE naprawa_pobrano_z='$tup' and naprawa_data_odbioru_z_serwisu BETWEEN '$okres_od 00:00:00' AND '$okres_do 23:59:59'"; 
			} else {
				$sql_a = "SELECT * FROM $dbname.serwis_naprawa WHERE belongs_to=$es_filia and naprawa_pobrano_z='$tup' and naprawa_data_odbioru_z_serwisu BETWEEN '$okres_od 00:00:00' AND '$okres_do 23:59:59'"; 
			}
		} else { 
			if ($es_m==1) {
				$sql_a = "SELECT * FROM $dbname.serwis_naprawa WHERE naprawa_data_odbioru_z_serwisu BETWEEN '$okres_od 00:00:00' AND '$okres_do 23:59:59'"; 
			} else {
				$sql_a = "SELECT * FROM $dbname.serwis_naprawa WHERE belongs_to=$es_filia and naprawa_data_odbioru_z_serwisu BETWEEN '$okres_od 00:00:00' AND '$okres_do 23:59:59'"; 
			}
		}
	} else { 
		if ($es_m==1) {
			$sql_a = "SELECT * FROM $dbname.serwis_naprawa WHERE naprawa_pobrano_z='$tup'";
		} else {
			$sql_a = "SELECT * FROM $dbname.serwis_naprawa WHERE belongs_to=$es_filia and naprawa_pobrano_z='$tup'";
		}
	}
	$result_a = mysql_query($sql_a, $conn) or die($k_b);
	$count_rows = mysql_num_rows($result_a);
	if ($count_rows>0) {
		pageheader("Historia napraw sprzętu z",1,1);
		
		?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
	
		infoheader("<b>".$tup."</b><br />w okresie<br /><b>".$okres_od." - ".$okres_do."</b>");
		
		starttable();
		if ($es_m==1) {
			th("30;c;LP|;;Nazwa Model<br />Numer seryjny, inwentarzowy|;;Pobrano z|;;Przyjęcie sprzętu<br />Data przyjęcia|;;Zwrot do klienta<br />Data zwrotu|;c;Status<hr />Wykonane naprawy|;c;Uwagi<br /><sub>Filia</sub>|;c;Opcje",$es_prawa);	
		} else {
			th("30;c;LP|;;Nazwa Model<br />Numer seryjny, inwentarzowy|;;Pobrano z|;;Przyjęcie sprzętu<br />Data przyjęcia|;;Zwrot do klienta<br />Data zwrotu|;c;Status<hr />Wykonane naprawy|;c;Uwagi|;c;Opcje",$es_prawa);	
		}
		
		$result_a = mysql_query($sql_a, $conn) or die($k_b);
		$i=0;
		
		while ($dane3 = mysql_fetch_array($result_a)) {		
			$mid 		= $dane3['naprawa_id'];				
			$mnazwa 	= $dane3['naprawa_nazwa'];
			$mmodel		= $dane3['naprawa_model'];			
			$msn	 	= $dane3['naprawa_sn'];
			$mni		= $dane3['naprawa_ni'];				
			$muwagisa	= $dane3['naprawa_uwagi_sa'];
			$muwagi		= $dane3['naprawa_uwagi'];			
			$mup		= $dane3['naprawa_pobrano_z'];
			$moo		= $dane3['naprawa_osoba_pobierajaca'];
			$mdp		= $dane3['naprawa_data_pobrania'];	
			$mstatus	= $dane3['naprawa_status'];
			$mdos		= $dane3['naprawa_data_oddania_sprzetu'];
			$bt			= $dane3['belongs_to'];
			$moos		= $dane3['naprawa_osoba_oddajaca_sprzet'];
			$mnw 		= $dane3['naprawa_wykonane_naprawy'];
			
			$n_przekaz_do 		= $dane3['naprawa_przekazanie_naprawy_do'];
			$n_przekaz_data 	= $dane3['naprawa_przekazanie_naprawy_data'];
			$n_przekaz_osoba 	= $dane3['naprawa_przekazanie_naprawy_osoba'];
			$n_odbior_data		= $dane3['naprawa_odbior_z_filii_data'];
			$n_odbior_osoba 	= $dane3['naprawa_odbior_z_filii_osoba'];	
			
			$n_przekazanie_zakonczone 		= $dane3['naprawa_przekazanie_zakonczone'];
			$n_przekazanie_naprawa_wykonana = $dane3['naprawa_przekazanie_naprawa_wykonana'];
			
			$n_zgl_id = $dane3['naprawa_hd_zgl_id'];
				
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
		
			//echo $naprawa_przekazana_do_innej_filii;
			
			tbl_tr_highlight($i);
			$i++;
			td("30;c;<a class=normalfont href=# title=' $mid '>".$change_color_start."".$i."".$change_color_stop."</a>");

//			td_(";nw;<b>".$mnazwa." ".$mmodel."</b><br />".$msn.", ".$mni."");
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
						echo "<a title=' Informacje dodatkowe '><input class=imgoption type=image src=img//information.gif onclick=\"alert('Sprzęt przekazany z serwisu filii $NazwaFiliiZrodlowej w dniu ".substr($n_przekaz_data,0,16)." przez $n_przekaz_osoba');\"></a>";
					} else {
						echo " -> sprzęt przekazany do filii: <b>$NazwaFilii&nbsp;</b>";
						echo "<a title='Informacje dodatkowe '><input class=imgoption type=image src=img//information.gif onclick=\"alert('Sprzęt przekazany do serwisu filii $NazwaFilii w dniu ".substr($n_przekaz_data,0,16)." przez $n_przekaz_osoba');\"></a>";
					}
				}
				
				echo "<br />".$msn." / ".$mni."".$change_color_stop."";		
				
				if ($pokazpoczatekuwag==1) pokaz_uwagi($muwagi,$iloscznakowuwag,"newWindow(480,300,'p_naprawy_uwagi.php?id=$mid')");
			_td();
			
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
	
			td(";;<b>".$change_color_start."$temp_pion_nazwa $mup".$change_color_stop."</b>");
//			td(";;".$msn."<br />".$mni."");
//			td_("115;;".$moo."<br />".$mdp."");
//			td_("115;;".$moos."<br />".$mdos."");	
			
			if ($moos=='') $moos='-';
			if ($mdos=='0000-00-00 00:00:00') $mdos='-';
			
			td_(";;".$change_color_start."".$moo."".$change_color_stop."<br />".$change_color_start."".substr($mdp,0,16)."".$change_color_stop."");	
			td_(";;".$change_color_start."".$moos."".$change_color_stop."<br />".$change_color_start."".substr($mdos,0,16)."".$change_color_stop."");	
			
			td_img(";c");

				if ($mstatus=='-1') echo "<a title=' Sprzęt pobrany od klienta ' href=main.php?action=npus&id=$mid&cs=$es><img class=imgoption src=img/snapraw_p.gif border=0 width=16 width=16></a>";
				if ($mstatus=='0') echo "<a title=' Sprzęt jest naprawiany we własnym zakresie ' href=main.php?action=npus&id=$mid&cs=$es><img class=imgoption src=img/w_naprawie_wz.gif border=0 width=16 width=16></a>";
				if ($mstatus=='1') echo "<a title=' Sprzęt jest w naprawie w serwisie zewnętrznym ' href=main.php?action=npswsz&id=$mid&cs=$es><img class=imgoption src=img/w_naprawie_sz.gif border=0 width=16 width=16></a>";
				if ($mstatus=='2') echo "<a title=' Sprzęt jest w naprawie w serwisie lokalnym ' href=main.php?action=npswsz&id=$mid&cs=$es><img class=imgoption src=img/w_naprawie_sl.gif border=0 width=16 width=16></a>";
				if ($mstatus=='3') {
					echo "<a title=' Sprzęt wrócił z naprawy ' href=p_naprawy_zakonczone.php?id=$eid&cs=$es><img class=imgoption
 src=img/snapraw_ok.gif border=0></a>";
					echo "<br /><hr />";
					if ($mnw!='') {
						echo "&nbsp;<a title=' Pokaż wykonane naprawy '><input type=image class=imgoption src=img/wykonane_naprawy.gif onclick=\"newWindow(480,300,'p_naprawy_wykonane.php?id=$mid')\"></a>";
						echo "&nbsp;<a title=' Edytuj wykonane naprawy '><input type=image class=imgoption src=img/wykonane_naprawy_edit.gif onclick=\"newWindow(480,300,'p_naprawy_wykonane_edit.php?id=$mid')\"></a>";
					} else {
						echo "&nbsp;<a title=' Dopisz wykonane naprawy '><input type=image class=imgoption src=img/wykonane_naprawy_add.gif onclick=\"newWindow(480,300,'p_naprawy_wykonane_edit.php?id=$mid')\"></a>";
					}
					
				}
				if ($mstatus=='5') {
					echo "<a title='Zwrócony do klienta $mdos przez $moos '><input class=imgoption type=image src=img/ok.gif></a>";
					echo "<br /><hr />";
					if ($mnw!='') {
						echo "&nbsp;<a title=' Pokaż wykonane naprawy '><input type=image class=imgoption src=img/wykonane_naprawy.gif onclick=\"newWindow(480,300,'p_naprawy_wykonane.php?id=$mid')\"></a>";
						echo "&nbsp;<a title=' Edytuj wykonane naprawy '><input type=image class=imgoption src=img/wykonane_naprawy_edit.gif onclick=\"newWindow(480,300,'p_naprawy_wykonane_edit.php?id=$mid')\"></a>";
					} else {
						echo "&nbsp;<a title=' Dopisz wykonane naprawy '><input type=image class=imgoption src=img/wykonane_naprawy_add.gif onclick=\"newWindow(480,300,'p_naprawy_wykonane_edit.php?id=$mid')\"></a>";
					}
				}
			_td();			
			td_img(";c");
				if ($muwagisa=='1') {
					echo "<a title=' Czytaj uwagi '><input type=image class=imgoption src=img/comment.gif onclick=\"newWindow(480,300,'p_naprawy_uwagi.php?id=$mid')\"></a>";
				} else { echo ""; } 
				if ($mstatus!='5') echo "<a title=' Edytuj uwagi '><input type=image class=imgoption src=img/edit_comment.gif onclick=\"newWindow(480,300,'e_naprawy_uwagi.php?id=$mid')\"></a>";
				if ($es_m==1) include("p_filia_nazwa.php");
			_td();
			td_img(";c");
				if ($naprawa_przekazana_do_innej_filii==0) {
					if ($mstatus==-1) { echo "<a title=' Naprawiaj '><input class=imgoption type=image src=img//naprawiaj.gif onclick=\"newWindow_r(800,600,'z_naprawy_status.php?id=$mid&cs=$mstatus&tup=$mup')\"></a>"; }
					if ($mstatus==0) { echo "<a title=' Zmień status na naprawiony '><input class=imgoption type=image src=img//naprawiaj.gif onclick=\"newWindow_r(800,600,'z_naprawy_napraw3.php?id=$mid&cs=$mstatus&tup=$mup')\"></a>"; }
					if ($mstatus==1) { echo "<a title=' Zmień status na naprawiony '><input class=imgoption type=image src=img//naprawiaj.gif onclick=\"newWindow_r(800,600,'z_naprawy_napraw3.php?id=$mid&cs=$mstatus&tup=$mup')\"></a>";  }
					if ($mstatus==2) { echo "<a title=' Zmień status na naprawiony '><input class=imgoption type=image src=img//naprawiaj.gif onclick=\"newWindow_r(800,600,'z_naprawy_napraw3.php?id=$mid&cs=$mstatus&tup=$mup')\"></a>"; }
					if ($mstatus==3) { echo "<a title=' Oddaj do klienta '><input class=imgoption type=image src=img//return.gif onclick=\"newWindow(490,190,'z_naprawy_napraw5.php?id=$mid&cs=$mstatus&tup=$mup')\"></a>"; }
				} else {
				
				}
							
				$naprawy_zmiany_result = mysql_query("SELECT naprawa_hz_id FROM $dbname.serwis_naprawa_historia_zmian WHERE (naprawa_hz_naprawa_id=$mid) and (belongs_to=$es_filia)", $conn) or die($k_b);
				if (mysql_num_rows($naprawy_zmiany_result)>0)
				echo "<a title=' Pokaż historię zmian w naprawie '><input type=image class=imgoption src=img//faktury_nz.gif onclick=\"newWindow(800,500,'p_naprawy_historia_zmian.php?id=$mid&up=".urlencode($mup)."&sn=".$msn."&model=".urlencode($mmodel)."&typ=".urlencode($mnazwa)."')\"></a>";
			
				echo "<a title=' Pokaż szczegóły naprawy'><input class=imgoption type=image src=img/detail.gif  onclick=\"newWindow(800,600,'p_naprawy_szczegoly.php?id=$mid')\"></a>";

				if ($n_zgl_id>0) {
					$LinkHDZglNr=$n_zgl_id; include('linktohelpdesk.php');
				}
			
			_td();
		_tr();
		}
		endtable();
	} else { 
		errorheader("Nie znaleziono pozycji spełniających podane przez Ciebie kryteria");
		?><meta http-equiv="REFRESH" content="<?php echo $http_refresh; ?>;url=<?php echo "$linkdostrony";?>main.php"><?php	
		}
startbuttonsarea("right");
oddziel();
addlinkbutton("'Zmień kryteria'","main.php?action=nwo");
addbuttons("start");
endbuttonsarea();
include('body_stop.php');
?>

<script>HideWaitingMessage();</script>

<?php 
} else {
br();
pageheader("Historia zakończonych napraw sprzętu wg wybranego UP");
starttable("55%");
echo "<form name=ruch action=p_naprawy_w_okresie.php method=GET onsubmit='return validateForm();'>";
tbl_empty_row();
	tr_();
		td_colspan(2,'c');
			echo "<b>Podaj zakres dat<br /><br /></b>";
		_td();
	_tr();
	tr_();
		td("150;c;od dnia");
		td("150;c;do dnia");
	_tr();
	
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
	
	tr_();
		td_img(";c");
			echo "<input class=wymagane size=10 maxlength=10 type=text id=okres_od name=okres_od value=$data1 onkeypress='return handleEnter(this, event);' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\">";	
			echo "<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif width=16 height=16 border=0></a>";
			if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('okres_od').value='".Date('Y-m-d')."'; return false;\">";
		_td();
		td_img(";c");
			echo "<input class=wymagane size=10 maxlength=10 type=text id=okres_do name=okres_do value=$data2 onkeypress='return handleEnter(this, event);' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\">";
			echo "<a tabindex=-1 href=javascript:cal11.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif width=16 height=16 border=0></a>";
			if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('okres_do').value='".Date('Y-m-d')."'; return false;\">";
		_td();	
	_tr();	
	tr_();
		td_colspan(2,'c');
			echo "<b>Wybierz komórkę / UP<br /><br /></b>";
		_td();
	_tr();
	tr_();
		td_colspan(2,'c');
		if ($es_m==1) {
			$result6 = mysql_query("SELECT serwis_komorki.up_id, serwis_komorki.up_nazwa, serwis_piony.pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) ORDER BY serwis_piony.pion_nazwa,up_nazwa", $conn) or die($k_b);
		} else {
			$result6 = mysql_query("SELECT serwis_komorki.up_id, serwis_komorki.up_nazwa, serwis_piony.pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) ORDER BY serwis_piony.pion_nazwa,up_nazwa", $conn) or die($k_b);
		}
			echo "<select class=wymagane name=tup onkeypress='return handleEnter(this, event);'>\n";
			echo "<option value=''>Wybierz z listy...";
			while (list($temp_id,$temp_nazwa,$pion) = mysql_fetch_array($result6)) {
				echo "<option value='$temp_nazwa'>$pion $temp_nazwa</option>\n"; 
			}
			echo "</select>\n";
		_td();
	_tr();
tbl_empty_row();
endtable();
startbuttonsarea("center");
addownsubmitbutton("'Pokaż'","submit");
endbuttonsarea();
oddziel();
echo "<span style='float:left'>";
echo "&nbsp;Historia: ";
addlinkbutton("'Pokaż wszystko'","p_naprawy_wszystko.php");		
addlinkbutton("'Naprawy wycofane z serwisu'","p_naprawy_historia_wycofane.php");		
addlinkbutton("'Zakończone naprawy '","p_naprawy_historia_cala.php");
//addlinkbutton("'Wg komórki'","main.php?action=nwo");
echo "</span>";

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
	frmvalidator.addValidation("tup","dontselect=0","Nie wybrałeś komórki/UP");  
	frmvalidator.addValidation("okres_od","numerichyphen","Użyłeś niedozwolonych znaków w polu \"od dnia\"");
	frmvalidator.addValidation("okres_do","numerichyphen","Użyłeś niedozwolonych znaków w polu \"do dnia\"");
</script>
<?php }
?>
</body>
</html>