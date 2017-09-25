<?php include_once('header.php'); ?>
<body>
<?php

include('body_start.php'); 
if ($wybierz_typ=='') {
$result = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni,magazyn_uwagi,magazyn_uwagi_sa FROM $dbname.serwis_magazyn WHERE magazyn_status='0' and belongs_to=$es_filia ORDER BY magazyn_nazwa ASC", $conn) or die($k_b);
} else 
$result = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni,magazyn_uwagi,magazyn_uwagi_sa FROM $dbname.serwis_magazyn WHERE (magazyn_status='0') and (belongs_to=$es_filia) and (magazyn_nazwa='$wybierz_typ') ORDER BY magazyn_nazwa ASC", $conn) or die($k_b);

if (mysql_num_rows($result)!=0) {
	pageheader("Pobranie sprzętu serwisowego",1,1);
	?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
	
	startbuttonsarea("center");
	
	echo "<span style='float:left'>";
	//echo "&nbsp;";
	if ($kolor!=1) {
		echo "<input id=with_color type=button class=buttons value='Koloruj wg typu' onClick=\"self.location.href='e_magazyn_pobierz.php?wybierz_typ=$wybierz_typ&kolor=1'\" />";
	} else {
		echo "<input id=without_color type=button class=buttons value='Bez kolorowania wg typu' onClick=\"self.location.href='e_magazyn_pobierz.php?wybierz_typ=$wybierz_typ&kolor=0'\" />";
	}
	echo "</span>";
	
	echo "<form name=magazyn action=$PHP_SELF method=GET>";
	echo "Pokaż: ";
	echo "<select name=wybierz_typ  onChange='document.location.href=document.magazyn.wybierz_typ.options[document.magazyn.wybierz_typ.selectedIndex].value'>";
			$sql_lista_p = "SELECT DISTINCT magazyn_nazwa FROM $dbname.serwis_magazyn WHERE (belongs_to=$es_filia) and (magazyn_status='0') ORDER BY magazyn_nazwa";
			$wynik_lista_typ = mysql_query($sql_lista_p,$conn) or die($k_b);
			echo "<option ";
			if ($wybierz_typ=='') echo "SELECTED ";
			echo "value='e_magazyn_pobierz.php?wybierz_typ=&kolor=$kolor'>Cały sprzęt</option>\n";	
			while (list($temp_magazyn_nazwa)=mysql_fetch_array($wynik_lista_typ)) {
				echo "<option "; 
				if ($wybierz_typ==$temp_magazyn_nazwa) echo "SELECTED ";
				echo "value='e_magazyn_pobierz.php?wybierz_typ=$temp_magazyn_nazwa&kolor=$kolor'>$temp_magazyn_nazwa</option>\n";	
			
			}
	echo "</select>";
	echo "</form>";
	endbuttonsarea();	
	
	starttable();
	th("30;c;LP|;;Typ sprzętu|;;Model|;;Numer seryjny|;;Numer inwentarzowy|;c;Uwagi|;c;Opcje",$es_prawa);

	$i = 0;
	while (list($mid, $mnazwa, $mmodel, $msn, $mni, $muwagi, $muwagisa) = mysql_fetch_array($result)) {
		
		if ($_GET[kolor]!=1) {
			tbl_tr_highlight_dblClick_e_magazyn_pobierz($i);
		} else {
			list($kolorgrupy)=mysql_fetch_array(mysql_query("SELECT rola_kolor FROM $dbname.serwis_slownik_rola WHERE rola_nazwa='$mnazwa' LIMIT 1",$conn));
			tbl_tr_highlight_dblClick_e_magazyn_pobierz_kolor($i,$kolorgrupy);
		}
		
		echo "<input type=hidden id=pozid$i value=$mid>";
		echo "<input type=hidden id=pozname$i value='$mnazwa'>";
		
		$i++;
		//td("30;c;".$i."");
		td("30;c;<a class=normalfont href=# title=' $mid '>".$i."</a>");
		td_(";;".$mnazwa."");
		if ($pokazpoczatekuwag==1) pokaz_uwagi($muwagi,$iloscznakowuwag,"newWindow(480,265,'p_magazyn_uwagi.php?id=$mid'); return false;");
		_td();
		td(";;".$mmodel."|;;".$msn."|;;".$mni."");
		td_img(";c");
			if ($muwagisa=='1') { echo "<a title='Czytaj uwagi '><input class=imgoption type=image src=img/comment.gif onclick=\"newWindow(480,265,'p_magazyn_uwagi.php?id=$mid'); return false;\"></a>";}
			echo "<a title=' Edytuj uwagi '><input type=image class=imgoption src=img/edit_comment.gif onclick=\"newWindow(480,245,'e_magazyn_uwagi.php?id=$mid')\"></a>";
		_td();
		td_img(";c");
			echo "<a title=' Pobierz $mnazwa $mmodel o numerze seryjnym $msn z magazynu '><input type=image class=imgoption src=img/pobierz.gif  onclick=\"newWindow_r(700,595,'z_magazyn_pobierz.php?id=$mid&part=".urlencode($mnazwa)."&powiazzhd=1')\"></a>";
		_td();
		_tr();
	}
	endtable();
} else { 
		br();
		errorheader('Brak sprzętu na magazynie');
		?>
		<meta http-equiv="REFRESH" content="<?php echo $http_refresh; ?>;url=<?php echo "$linkdostrony";?>main.php">
		<?php
	}

	

startbuttonsarea("right");
oddziel();
echo "<span style='float:left'>";
//echo "&nbsp;";
//addlinkbutton("'Pobierz sprzęt'","e_magazyn_pobierz.php?kolor=$kolor");		
addlinkbutton("'Zwróć sprzęt'","z_magazyn_zwroty.php?kolor=$kolor");		
addlinkbutton("'Zarezerwuj sprzęt'","main.php?action=rs&kolor=$kolor");
addlinkbutton("'Ukryj sprzęt'","main.php?action=us&kolor=$kolor");
echo "</span>";

addlinkbutton("'Przeglądaj zgłoszenia'","hd_p_zgloszenia.php?showall=0&p5=BZ&sort=AD&page=1");
addbuttons("start");
endbuttonsarea();



include('body_stop.php');
//include('js/menu.js');

?>
<script>HideWaitingMessage();</script>
</body>
</html>