<?php include_once('header.php'); ?>
<body>
<?php
if ($es_m==1) {
	$result = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni,magazyn_uwagi,magazyn_uwagi_sa,magazyn_status,belongs_to FROM $dbname.serwis_magazyn WHERE magazyn_status='0' ORDER BY magazyn_nazwa", $conn) or die($k_b);
} else {

	if ($wybierz_typ=='') {
		$result = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni,magazyn_uwagi,magazyn_uwagi_sa,magazyn_status,belongs_to FROM $dbname.serwis_magazyn WHERE magazyn_status='0' and belongs_to=$es_filia ORDER BY magazyn_nazwa ASC", $conn) or die($k_b);
	} else {
		$result = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni,magazyn_uwagi,magazyn_uwagi_sa,magazyn_status,belongs_to FROM $dbname.serwis_magazyn WHERE magazyn_status='0' and belongs_to=$es_filia and (magazyn_nazwa='$wybierz_typ') ORDER BY magazyn_nazwa ASC", $conn) or die($k_b);
	}

}
if (mysql_num_rows($result)!=0) {
	br();
	pageheader("Przeglądanie dostępnego sprzętu serwisowego",1);
	?><script>ShowWaitingMessage('','a');</script><?php ob_flush(); flush();	
	startbuttonsarea("center");
	echo "<span style='float:left'>";
	//echo "&nbsp;&nbsp;";
	if ($kolor!=1) {
		echo "<input id=with_color type=button class=buttons value='Koloruj wg typu' onClick=\"self.location.href='main.php?action=asm&wybierz_typ=$wybierz_typ&kolor=1'\" />";
	} else {
		echo "<input id=without_color type=button class=buttons value='Bez kolorowania wg typu' onClick=\"self.location.href='main.php?action=asm&wybierz_typ=$wybierz_typ&kolor=0'\" />";
	}
	echo "</span>";
	
	echo "<form name=magazyn action=main.php method=GET>";
	echo "Pokaż: ";
	echo "<select name=wybierz_typ onChange='document.location.href=document.magazyn.wybierz_typ.options[document.magazyn.wybierz_typ.selectedIndex].value'>";
		$sql_lista_p = "SELECT DISTINCT magazyn_nazwa FROM $dbname.serwis_magazyn WHERE (belongs_to=$es_filia) and (magazyn_status='0') ORDER BY magazyn_nazwa";
		$wynik_lista_typ = mysql_query($sql_lista_p,$conn) or die($k_b);
		echo "<option ";
		if ($wybierz_typ=='') echo "SELECTED ";
		echo "value='main.php?action=asm&wybierz_typ=&kolor=$kolor'>Cały sprzęt</option>\n";	
		while (list($temp_magazyn_nazwa)=mysql_fetch_array($wynik_lista_typ)) {
			echo "<option "; 
			if ($wybierz_typ==$temp_magazyn_nazwa) echo "SELECTED ";
			echo "value='main.php?action=asm&wybierz_typ=".urlencode($temp_magazyn_nazwa)."&kolor=$kolor'>$temp_magazyn_nazwa</option>\n";	
		}
	echo "</select>";
	echo "<input type=hidden name=action value=asm>";
	echo "<input type=hidden name=kolor value=$kolor>";
	
	echo "</form>";
	endbuttonsarea();
	
	starttable();
	if ($es_m==1) {
		th("30;c;LP|;;Typ sprzętu|;;Model|;;Numer seryjny|;;Numer inwentarzowy|;c;Uwagi<br /><sub>Filia</sub>|;c;Opcje",$es_prawa);
	} else {
		th("30;c;LP|;;Typ sprzętu|;;Model|;;Numer seryjny|;;Numer inwentarzowy|;c;Uwagi|;c;Opcje",$es_prawa);
	}
	$i = 0;
	while (list($mid, $mnazwa, $mmodel, $msn, $mni, $muwagi, $muwagisa,$mstatus,$bt) = mysql_fetch_array($result)) {
	
		if ($_GET[kolor]!=1) {
			tbl_tr_highlight_dblClick_p_magazyn_aktualne($i);
		} else {
			list($kolorgrupy)=mysql_fetch_array(mysql_query("SELECT rola_kolor FROM $dbname.serwis_slownik_rola WHERE rola_nazwa='$mnazwa' LIMIT 1",$conn));
			tbl_tr_highlight_dblClick_p_magazyn_aktualne_kolor($i,$kolorgrupy);
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
				if ($muwagisa=='1') { echo "<a title=' Czytaj uwagi '><input class=imgoption type=image src=img/comment.gif onclick=\"newWindow(480,265,'p_magazyn_uwagi.php?id=$mid'); return false;\"></a>";}
				echo "<a title=' Edytuj uwagi '><input class=imgoption type=image src=img/edit_comment.gif onclick=\"newWindow(480,245,'e_magazyn_uwagi.php?id=$mid')\"></a>";
				if ($es_m==1) include("p_filia_nazwa.php");
			_td();
			td_(";c");
				if ($mstatus=='0') echo "<a title=' Pobierz $mnazwa $mmodel o numerze seryjnym $msn magazynu '><input class=imgoption type=image src=img/pobierz.gif onclick=\"newWindow_r(700,595,'z_magazyn_pobierz.php?id=$mid&part=".urlencode($mnazwa)."')\"></a>";			
			_td();
		_tr();
	}
	endtable();
} else
    {
		br();
		errorheader("Brak sprzętu serwisowego na stanie");
		?>
		<meta http-equiv="REFRESH" content="<?php echo $http_refresh; ?>;url=<?php echo "$linkdostrony";?>main.php">
		<?php
	}
	
startbuttonsarea("right");
oddziel();
echo "<span style='float:left'>";
echo "Pokaż: ";
addlinkbutton("'Cały sprzęt'","main.php?action=cm");
//addlinkbutton("'Dostępny sprzęt'","main.php?action=asm");
addlinkbutton("'Pobrany sprzęt'","main.php?action=sw");
addlinkbutton("'Zarezerwowany sprzęt'","main.php?action=prs");
addlinkbutton("'Ukryty sprzęt'","main.php?action=pus");
echo "</span>";

addlinkbutton("'Przeglądaj zgłoszenia'","hd_p_zgloszenia.php?showall=0&p5=BZ&sort=AD&page=1");
addbuttons("start");
endbuttonsarea();
include('body_stop.php');
	
?>
<script>HideWaitingMessage('a');</script>
</body>
</html>