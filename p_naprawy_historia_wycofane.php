<?php include_once('header.php'); ?>
<body>
<?php include('body_start.php'); ?>
<?php 
if ($es_m==1) {
	$result = mysql_query("SELECT * FROM $dbname.serwis_naprawa WHERE (naprawa_status='7') or (naprawa_status='8')) ORDER BY naprawa_data_pobrania DESC", $conn) or die($k_b);
} else {
	$result = mysql_query("SELECT * FROM $dbname.serwis_naprawa WHERE ((naprawa_status='7') or (naprawa_status='8')) and (belongs_to=$es_filia) ORDER BY naprawa_data_pobrania DESC", $conn) or die($k_b);
}
$count_rows = mysql_num_rows($result);	
// paging
$totalrows = $count_rows;
if ($showall==0) {  $rps=$rowpersite;} else $rps=10000;
if(empty($page)){ $page = 1; }
$limitvalue = $page * $rps - ($rps);

if ($es_m==1) {
	$sql="SELECT * FROM $dbname.serwis_naprawa WHERE ((naprawa_status='7') or (naprawa_status='8')) ORDER BY naprawa_data_pobrania DESC LIMIT $limitvalue, $rps";
} else {
	$sql="SELECT * FROM $dbname.serwis_naprawa WHERE ((naprawa_status='7') or (naprawa_status='8')) and (belongs_to=$es_filia) ORDER BY naprawa_data_pobrania DESC LIMIT $limitvalue, $rps";
}
$result = mysql_query($sql, $conn) or die($k_b);

// koniec - paging
if (mysql_num_rows($result)!=0) {
	pageheader("Naprawy wycofane z serwisów",1,1);

	?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
		
	startbuttonsarea("center");
	if ($showall==0) {
		echo "<a class=paging href=p_naprawy_historia_wycofane.php?showall=1&paget=$page>Pokaż wszystko na jednej stronie</a>";
	} else {
		echo "<a class=paging href=p_naprawy_historia_wycofane.php?showall=0&page=$paget>Dziel na strony</a>";
	}
	
	echo "<a href=# class=paging1>";
	echo "| Łącznie: <b>$totalrows pozycji</b>";
	echo "</a>";
	
	endbuttonsarea();
	starttable();
	if ($es_m==1) {
		th("30;c;LP|;;Nazwa Model<br />Numer seryjny, inwentarzowy|;;Pobrano z|;;Wycofanie z serwisu<br />Data wycofania|;c;Powód wycofania|;c;Uwagi<br /><sub>Filia</sub>|;c;Opcje",$es_prawa);
	} else {
		th("30;c;LP|;;Nazwa Model<br />Numer seryjny, inwentarzowy|;;Pobrano z|;;Wycofanie z serwisu<br />Data wycofania|;c;Powód wycofania|;c;Uwagi|;c;Opcje",$es_prawa);
	}

	$i = 0;
	$j = $page*$rowpersite-$rowpersite+1;
	while ($dane = mysql_fetch_array($result)) {	
		$mid 		= $dane['naprawa_id'];					$mnazwa 	= $dane['naprawa_nazwa'];
		$mmodel	= $dane['naprawa_model'];					$msn	 	= $dane['naprawa_sn'];
		$mni		= $dane['naprawa_ni'];					$muwagisa	= $dane['naprawa_uwagi_sa'];
		$muwagi	= $dane['naprawa_uwagi'];					$mup		= $dane['naprawa_pobrano_z'];
		$moo		= $dane['naprawa_osoba_pobierajaca'];	$mdp		= $dane['naprawa_data_pobrania'];
		$mstatus	= $dane['naprawa_status'];				$mdos		= $dane['naprawa_data_oddania_sprzetu'];
		$moos		= $dane['naprawa_osoba_oddajaca_sprzet'];$bt		= $dane['belongs_to'];
		$mpwzs = $dane['naprawa_powod_wycofania_z_serwisu'];
		$mdwzs = $dane['naprawa_data_wycofania'];
		$mowzs = $dane['naprawa_osoba_wycofujaca_sprzet_z_serwisu'];
		$n_zgl_id = $dane['naprawa_hd_zgl_id'];		
		
		if ($mowzs=='') $mowzs = '-';
		if ($mdwzs=='0000-00-00') $mdwzs = '-';

		if ($_GET[id]==$mid) {
			tbl_tr_color_with_border($i,'#FFFF55');
		} else tbl_tr_highlight($i);
		
		$i++;
			//td("30;c;".$j."");
			td("30;c;<a class=normalfont href=# title=' $mid '>".$j."</a>");
			td_(";;<b>".$mnazwa." ".$mmodel."</b><br />".$msn.", ".$mni."");
				if ($pokazpoczatekuwag==1) pokaz_uwagi($muwagi,$iloscznakowuwag,"newWindow(480,300,'p_naprawy_uwagi.php?id=$mid')");
		//td(";;".$msn."<br />".$mni."");
			td_(";");
				//$wynik = mysql_query("SELECT up_id FROM $dbname.serwis_komorki WHERE (up_nazwa='$mup') and (belongs_to=$es_filia) LIMIT 1", $conn) or die($k_b);
				//list($temp_up_id)= mysql_fetch_array($wynik);
				
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
	
				echo "<a class=normalfont title=' Szczegółowe informacje o $temp_pion_nazwa $mup ' onclick=\"newWindow_r(700,600,'p_komorka_szczegoly.php?id=$temp_up_id'); return false;\" href=#><b>$temp_pion_nazwa $mup</b></a>";	
			_td();
			td_(";;".$mowzs."<br />".$mdwzs."");			
			td_img(";c");
				if ($mstatus=='-1') echo "<a title=' Sprzęt pobrany od klienta ' href=main.php?action=npus&id=$mid&cs=$es><img class=imgoption src=img/snapraw_p.gif border=0 width=16 width=16></a>";
				if ($mstatus=='0') echo "<a title=' Sprzęt jest naprawiany we własnym zakresie ' href=main.php?action=npus&id=$mid&cs=$es><img class=imgoption src=img/w_naprawie_wz.gif border=0 width=16 width=16></a>";
				if ($mstatus=='1') echo "<a title=' Sprzęt jest w naprawie w serwisie zewnętrznym ' href=main.php?action=npswsz&id=$mid&cs=$es><img class=imgoption src=img/w_naprawie_sz.gif border=0 width=16 width=16></a>";
				if ($mstatus=='2') echo "<a title=' Sprzęt jest w naprawie w serwisie lokalnym ' href=main.php?action=npswsz&id=$mid&cs=$es><img class=imgoption src=img/w_naprawie_sl.gif border=0 width=16 width=16></a>";
				if ($mstatus=='3') echo "<a title=' Sprzęt wrócił z naprawy ' href=p_naprawy_zakonczone.php?id=$eid&cs=$es><img class=imgoption src=img/snapraw_ok.gif border=0 width=16 width=16></a>";
				if ($mstatus=='5') echo "<a title='Zwrócony do klienta $mdos przez $moos '><input class=imgoption type=image src=img/ok.gif></a>";
				//if ($mstatus=='7') echo "<a><i><font color=red>wycofany z serwisu</font></i></a>";
				//if ($mstatus=='8') echo "<a><i><font color=red>wycofany z serwisu i zwrócony do klienta</font></i></a>";

				if ($mstatus=='7') {
					
				//	echo "<br /><hr />";
					if ($mpwzs!='') {
						echo "<a title=' Pokaż powód wycofania z serwisu '><input type=image class=imgoption src=img/powod_wycofania.gif onclick=\"newWindow(480,300,'p_naprawy_powod_wycofania.php?id=$mid')\"></a>";
						echo "<a title=' Edytuj powód wycofania z serwisu '><input type=image class=imgoption src=img/powod_wycofania_edit.gif onclick=\"newWindow(480,300,'e_naprawy_powod_wycofania.php?id=$mid')\"></a>";
					} else {
						echo "<a title=' Dodaj powód wycofania z serwisu '><input type=image class=imgoption src=img/powod_wycofania_add.gif onclick=\"newWindow(480,300,'e_naprawy_powod_wycofania.php?id=$mid')\"></a>";
					}
				
				}
				
			_td();			
			td_img(";c");
				if ($muwagisa=='1') {
					echo "<a title=' Czytaj uwagi '><input type=image class=imgoption src=img/comment.gif onclick=\"newWindow(480,300,'p_naprawy_uwagi.php?id=$mid')\"></a>";
				} else echo "";
				if (($mstatus!='5') && ($mstatus!='7') && ($mstatus!='8')) echo "<a title=' Edytuj uwagi '><input type=image class=imgoption src=img/edit_comment.gif onclick=\"newWindow(480,300,'e_naprawy_uwagi.php?id=$mid')\"></a>";
				if ($es_m==1) include("p_filia_nazwa.php");
			_td();
			td_img(";c");
				if ($mstatus==-1) { echo "<a title=' Naprawiaj '><input class=imgoption type=image src=img//naprawiaj.gif onclick=\"newWindow_r(800,600,'z_naprawy_status.php?id=$mid&cs=$mstatus&tup=$mup')\"></a>"; }
				if ($mstatus==0) { echo "<a title=' Zmień status na naprawiony '><input class=imgoption type=image src=img//naprawiaj.gif onclick=\"newWindow_r(800,600,'z_naprawy_napraw3.php?id=$mid&cs=$mstatus&tup=$mup')\"></a>"; }
				if ($mstatus==1) { echo "<a title=' Zmień status na naprawiony '><input class=imgoption type=image src=img//naprawiaj.gif onclick=\"newWindow_r(800,600,'z_naprawy_napraw3.php?id=$mid&cs=$mstatus&tup=$mup')\"></a>";  }
				if ($mstatus==2) { echo "<a title=' Zmień status na naprawiony '><input class=imgoption type=image src=img//naprawiaj.gif onclick=\"newWindow_r(800,600,'z_naprawy_napraw3.php?id=$mid&cs=$mstatus&tup=$mup')\"></a>"; }
				if ($mstatus==3) { echo "<a title=' Oddaj do klienta '><input class=imgoption type=image src=img//return.gif onclick=\"newWindow(490,190,'z_naprawy_napraw5.php?id=$mid&cs=$mstatus&tup=$mup')\"></a>"; }
				
				if ($mstatus==7) { echo "<a title=' Przekaż ponownie do naprawy '><input class=imgoption type=image src=img//naprawiaj.gif onclick=\"newWindow_r(800,600,'z_naprawy_status.php?id=$mid&cs=-1&tup=$mup')\"></a>"; }
				
				//echo "<img src='img/snapraw_p.gif' border=0 align=absmiddle>";
							
				$naprawy_zmiany_result = mysql_query("SELECT naprawa_hz_id FROM $dbname.serwis_naprawa_historia_zmian WHERE (naprawa_hz_naprawa_id=$mid) and (belongs_to=$es_filia)", $conn) or die($k_b);
				if (mysql_num_rows($naprawy_zmiany_result)>0)
				echo "<a title=' Pokaż historię zmian w naprawie '><input type=image class=imgoption src=img//faktury_nz.gif onclick=\"newWindow(800,500,'p_naprawy_historia_zmian.php?id=$mid&up=".urlencode($mup)."&sn=".$msn."&model=".urlencode($mmodel)."&typ=".urlencode($mnazwa)."')\"></a>";

				echo "<a title=' Pokaż szczegóły naprawy'><input class=imgoption type=image src=img/detail.gif  onclick=\"newWindow(800,600,'p_naprawy_szczegoly.php?id=$mid')\"></a>";
				
				if ($n_zgl_id>0) {
					$LinkHDZglNr=$n_zgl_id; include('linktohelpdesk.php');
				}
			_td();
		_tr();
		$j++;
	}
endtable();
include_once('paging_end.php');
} else {
	errorheader("Historia zakończonych napraw jest pusta");
	?><meta http-equiv="REFRESH" content="<?php echo $http_refresh; ?>;url=<?php echo "$linkdostrony";?>main.php"><?php
}
echo "<span style='float:left'>";
if ($_REQUEST[id]>0) { addbackbutton('Wróć do poprzedniego widoku'); echo "<br />";}
echo "</span>";

startbuttonsarea("right");
echo "<span style='float:left'>";
echo "Historia: ";
addlinkbutton("'Pokaż wszystko'","p_naprawy_wszystko.php");		
//addlinkbutton("'Naprawy wycofane z serwisu'","p_naprawy_historia_wycofane.php");		
addlinkbutton("'Zakończone naprawy '","p_naprawy_historia_cala.php");
addlinkbutton("'Wg komórki'","main.php?action=nwo");
echo "</span>";

addbuttons("start");
endbuttonsarea();
include('body_stop.php'); 
?>

<script>HideWaitingMessage();</script>

</body>
</html>
