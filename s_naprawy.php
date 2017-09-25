<?php

?><script>ShowWaitingMessage('','a');</script><?php ob_flush(); flush();
	
starttable();
if ($es_m==1) {
	th("30;c;LP|;;Nazwa Model<br />Numer seryjny, inwentarzowy|;;Pobrano z|;;Przyjęcie sprzętu<br />Data przyjęcia|;c;Status|;c;Uwagi<br /><sub>Filia</sub>|;c;Opcje",$es_prawa);
} else {
	th("30;c;LP|;;Nazwa Model<br />Numer seryjny, inwentarzowy|;;Pobrano z|;;Przyjęcie sprzętu<br />Data przyjęcia|;c;Status|;c;Uwagi|;c;Opcje",$es_prawa);
}
$i = 0;
while ($dane = mysql_fetch_array($result)) {
	$mid 		= $dane['naprawa_id'];					$mnazwa 	= $dane['naprawa_nazwa'];
	$mmodel		= $dane['naprawa_model'];				$msn	 	= $dane['naprawa_sn'];
	$mni		= $dane['naprawa_ni'];					$muwagisa	= $dane['naprawa_uwagi_sa'];
	$muwagi		= $dane['naprawa_uwagi'];				$mup		= $dane['naprawa_pobrano_z'];
	$moo		= $dane['naprawa_osoba_pobierajaca'];	$mdp		= $dane['naprawa_data_pobrania'];
	$mstatus	= $dane['naprawa_status'];				$mdos		= $dane['naprawa_data_oddania_sprzetu'];
	$moos		= $dane['naprawa_osoba_oddajaca_sprzet'];$bt		= $dane['belongs_to'];
	$n_zgl_id = $dane['naprawa_hd_zgl_id'];
	
	tbl_tr_highlight($i);
	$i++;
			td("30;c;".$i."");
			td_(";;<b>".highlight($mnazwa, $search)." ".highlight($mmodel, $search)."</b><br />".highlight($msn, $search).", ".highlight($mni, $search)."");
				if ($pokazpoczatekuwag==1) pokaz_uwagi($muwagi,$iloscznakowuwag,"newWindow(480,300,'p_naprawy_uwagi.php?id=$mid')");
			_td();
			//td(";;".highlight($msn, $search)."<br />".highlight($mni, $search)."");
			td_(";");
			//	$wynik = mysql_query("SELECT up_id FROM $dbname.serwis_komorki WHERE (up_nazwa='$mup') and (belongs_to=$es_filia) LIMIT 1", $conn) or die($k_b);
			//	list($temp_up_id)= mysql_fetch_array($wynik);
				
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
	
				echo "<a class=normalfont title=' Szczegółowe informacje o $temp_pion_nazwa $mup ' onclick=\"newWindow_r(700,600,'p_komorka_szczegoly.php?id=$temp_up_id')\" href=#><b>".highlight($temp_pion_nazwa.' '.$mup, $search)."</b></a>";

			_td();
			td_(";;".highlight($moo, $search)."<br />".substr($mdp,0,16)."");
			td_img(";c");
				if ($mstatus=='-1') echo "<a title=' Sprzęt pobrany od klienta ' href=main.php?action=npus&id=$mid&cs=$es><img class=imgoption src=img/snapraw_p.gif border=0 width=16 width=16></a>";
				if ($mstatus=='0') echo "<a title=' Sprzęt jest naprawiany we własnym zakresie ' href=main.php?action=npus&id=$mid&cs=$es><img class=imgoption src=img/w_naprawie_wz.gif border=0 width=16 width=16></a>";
				if ($mstatus=='1') echo "<a title=' Sprzęt jest w naprawie w serwisie zewnętrznym ' href=main.php?action=npswsz&id=$mid&cs=$es><img class=imgoption src=img/w_naprawie_sz.gif border=0 width=16 width=16></a>";
				if ($mstatus=='2') echo "<a title=' Sprzęt jest w naprawie w serwisie lokalnym ' href=main.php?action=npswsz&id=$mid&cs=$es><img class=imgoption src=img/w_naprawie_sl.gif border=0 width=16 width=16></a>";
				if ($mstatus=='3') echo "<a title=' Sprzęt wrócił z naprawy ' href=p_naprawy_zakonczone.php?id=$eid&cs=$es><img class=imgoption src=img/snapraw_ok.gif border=0 width=16 width=16></a>";
				if ($mstatus=='5') echo "<a title='Zwrócony do klienta $mdos przez $moos '><input class=imgoption type=image src=img/ok.gif></a>";
			_td();
			td_img(";c");
				if ($muwagisa=='1') {
					echo "<a title=' Czytaj uwagi '><input type=image class=imgoption src=img/comment.gif onclick=\"newWindow(480,300,'p_naprawy_uwagi.php?id=$mid'); return false;\"></a>";
				} 
				if ($mstatus!='5') echo "<a title=' Edytuj uwagi '><input type=image class=imgoption src=img/edit_comment.gif onclick=\"newWindow(480,300,'e_naprawy_uwagi.php?id=$mid'); return false;\"></a>";
				if ($es_m==1) include("p_filia_nazwa.php");
			_td();			
			td_img(";c");
				if ($mstatus==-1) { echo "<a title=' Naprawiaj '><input class=imgoption type=image src=img//naprawiaj.gif onclick=\"newWindow_r(800,600,'z_naprawy_status.php?id=$mid&cs=$mstatus&tup=$mup')\"></a>"; }
				if ($mstatus==0) { echo "<a title=' Zmień status na naprawiony '><input class=imgoption type=image src=img//naprawiaj.gif onclick=\"newWindow_r(800,600,'z_naprawy_napraw3.php?id=$mid&cs=$mstatus&tup=$mup')\"></a>"; }
				if ($mstatus==1) { echo "<a title=' Zmień status na naprawiony '><input class=imgoption type=image src=img//naprawiaj.gif onclick=\"newWindow_r(800,600,'z_naprawy_napraw3.php?id=$mid&cs=$mstatus&tup=$mup')\"></a>";  }
				if ($mstatus==2) { echo "<a title=' Zmień status na naprawiony '><input class=imgoption type=image src=img//naprawiaj.gif onclick=\"newWindow_r(800,600,'z_naprawy_napraw3.php?id=$mid&cs=$mstatus&tup=$mup')\"></a>"; }
				if ($mstatus==3) { echo "<a title=' Oddaj do klienta '><input class=imgoption type=image src=img//return.gif onclick=\"newWindow(490,190,'z_naprawy_napraw5.php?id=$mid&cs=$mstatus&tup=$mup')\"></a>"; }
				
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
?>

<script>HideWaitingMessage('a');</script>

<?php 
?>