<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 
	include('body_start.php');
	$data_raportu=$_GET[okres_od];

	$sql_a = "SELECT * FROM $dbname.serwis_naprawa WHERE (SUBSTRING(naprawa_data_pobrania,1,10)<='$data_raportu') AND ((((SUBSTRING(naprawa_data_oddania_sprzetu,1,10)>'$data_raportu'))) OR (naprawa_data_wycofania>'$data_raportu'))"; 
	
	if ($es_m==1) {
		
	} else {
		$sql_a .= " AND (belongs_to=$es_filia)";
	}

	//echo $sql_a;
	$result_a = mysql_query($sql_a, $conn) or die($k_b);
	$count_rows = mysql_num_rows($result_a);
	if ($count_rows>0) {
		pageheader("Stan sprzętu w naprawie na dzień <b>$okres_od</b>",1,1);
		
		?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
	
		//infoheader("<b>".$tup."</b><br />w okresie<br /><b>".$okres_od." - ".$okres_do."</b>");
		
		starttable();
		if ($es_m==1) {
			th("30;c;LP|;;Nazwa Model<br />Numer seryjny, inwentarzowy|;;Pobrano z|;;Data przyjęcia sprzętu|;;Data oddania sprzętu<hr />Data wycofania|;c;Status",$es_prawa);	
		} else {
			th("30;c;LP|;;Nazwa Model<br />Numer seryjny, inwentarzowy|;;Pobrano z|;;Data przyjęcia sprzętu|;;Data oddania sprzętu<hr />Data wycofania|;c;Status",$es_prawa);	
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
			$mdwyc		= $dane3['naprawa_data_wycofania'];
			
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
			if ($mdwyc=='0000-00-00') $mdwyc='-';
			
			td_(";;".$change_color_start."".substr($mdp,0,10)."");
			td_(";;".$change_color_start."".substr($mdos,0,10)."<hr />".$mdwyc);	
			
		//	td_(";;".$change_color_start."".$moos."".$change_color_stop."<br />".$change_color_start."".substr($mdos,0,16)."".$change_color_stop."");	
			
			td_img(";c");
				if ($mstatus=='-1') echo "Sprzęt pobrany od klienta";
				if ($mstatus=='0') echo "Sprzęt jest naprawiany we własnym zakresie";
				if ($mstatus=='1') echo "Sprzęt jest w naprawie w serwisie zewnętrznym";
				if ($mstatus=='2') echo "Sprzęt jest w naprawie w serwisie lokalnym";
				if ($mstatus=='3') echo "Sprzęt wrócił z naprawy";
				if ($mstatus=='5') echo "Zwrócony do klienta";
				if ($mstatus=='7') echo "Sprzęt wycofany z serwisu";
				if ($mstatus=='8') echo "Sprzęt wycofany z serwisu-oddany do klienta";
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
echo "<span style='float:left'>";
addlinkbutton("'Zmień kryteria'","main.php?action=snd&okres_od=$data_raportu");
echo "</span>";
addbuttons("start");
endbuttonsarea();
include('body_stop.php');
?>

<script>HideWaitingMessage();</script>

<?php 
} else {
br();
pageheader("Stan sprzętu w naprawie na dzień");
starttable("55%");
echo "<form name=ruch action=p_naprawy_stan_na_dzien.php method=GET onsubmit='return validateForm();'>";
tbl_empty_row();
	tr_();
		td_colspan(2,'c');
			echo "<b>Podaj datę<br /><br /></b>";
		_td();
	_tr();
	
	$data1 = Date("Y-m-d");
	if ($_REQUEST[okres_od]!='') $data1 = $_REQUEST[okres_od];
	tr_();
		td_img(";c");
			echo "<input class=wymagane size=10 maxlength=10 type=text id=okres_od name=okres_od value=$data1 onkeypress='return handleEnter(this, event);' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\">";	
			echo "<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif width=16 height=16 border=0></a>";
			if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('okres_od').value='".Date('Y-m-d')."'; return false;\">";
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
</script>
<script language="JavaScript" type="text/javascript">
	var frmvalidator  = new Validator("ruch");
	frmvalidator.addValidation("okres_od","req","Nie podałeś daty dla raportu");  
	frmvalidator.addValidation("okres_od","numerichyphen","Użyłeś niedozwolonych znaków w polu daty");
</script>
<?php }
?>
</body>
</html>