<?php //include_once('header.php');
include_once('cfg_helpdesk.php');

// ilosc notatek na bieżący dzień
$dddd = date("Y-m-d");
nowalinia();
//infoheader("Informacje zbiorcze z bazy zgłoszeń (Helpdesk)",0);

if ((($kierownik_nr==$es_nr) || ($is_dyrektor==1)) && ($funkcja_kontroli_pracownikow==1)) {	
	
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
	
	$sql_wc="SELECT COUNT(zgl_id) FROM $dbname_hd.hd_zgloszenie WHERE ";
	$sql_wc.=" (hd_zgloszenie.belongs_to=$es_filia) and (hd_zgloszenie.zgl_widoczne=1) AND ";
	$sql_wc.=" (hd_zgloszenie.zgl_status=9) AND ";
	$sql_wc.=" (zgl_data>='$data1') AND (zgl_data<='$data2') ";
	$sql_wc.="AND (zgl_sprawdzone_osoba='') ";
	
	$result_wc = mysql_query($sql_wc, $conn_hd) or die($k_b);
	list($count_rows_wc)=mysql_fetch_array($result_wc);
	//$count_rows_wc = mysql_num_rows($result_wc);
	
}
	
echo "<span id=ZbiorczeTable>";
echo "<h5 id=ZbiorczeHD style='font-size:13px;font-weight:normal;padding-top:4px;padding-bottom:4px;margin-top:0px;margin-bottom:5px;text-align:left;background:#FDFDBD;color:#313131;display: block; border: 1px solid #F6F721;'>&nbsp;";
echo "<a href=# class=normalfont id=pokaz_hd style='display:none' type=button onClick=\"document.getElementById('helpdesk_szcz').style.display=''; document.getElementById('pokaz_hd').style.display='none'; document.getElementById('ukryj_hd').style.display=''; createCookie('p_raport_hd','TAK',365); refresh_hd();\"><img class=imgoption style='border:0px' type=image src=img/expand.gif width=11 height=11>&nbsp;Informacje zbiorcze z bazy Helpdesk</a>";
echo "<a href=# class=normalfont id=ukryj_hd style='display:none' type=button onClick=\"document.getElementById('helpdesk_szcz').style.display='none'; document.getElementById('pokaz_hd').style.display=''; document.getElementById('ukryj_hd').style.display='none'; createCookie('p_raport_hd','NIE',365);\"><img class=imgoption style='border:0px' type=image src=img/collapse.gif width=11 height=11>&nbsp;Informacje zbiorcze z bazy Helpdesk</a>";

if ($count_rows_wc>0) {
	echo "&nbsp;|&nbsp;Ilość zgłoszeń z bieżącego okresu do weryfikacji: <b>$count_rows_wc</b>";
	echo "&nbsp;";
	echo "<input type=button class=buttons style='padding:0px; margin:0px;' onClick=\"self.location.href='hd_g_raport_weryfikacja_zgloszen.php?okres_od=$data1&okres_do=$data2&tzgldata=data_utworzenia&tuser=all&tstatus=9&kategoria=&podkategoria=&priorytet=&potw_spr=0&rps111=50&submit=Generuj+raport';\" value=\"Weryfikuj zgłoszenia\" />";	
}

echo "<span style='float:right; margin-top:-6px;'>";
	echo "Szukaj po nr zgłoszenia&nbsp;";
	echo "<form name=szukaj_szybko action=hd_p_zgloszenia.php method=POST style='display:inline'>";
	echo "<input name=search_zgl_nr id=search_zgl_nr title=' Wyszukiwanie po numerze zgłoszenia ' maxlength=7 size=9 style='font-size:12px;color:#686868;' value='' onKeyPress=\"return filterInputEnter(1, event, false); \" onFocus=\"this.value=''; \" />";
	echo "<input type=hidden name=sa id=sa value='0'>";
	echo "<input type=hidden name=filtr value='true'>";
	echo "<input type=hidden name=sr id=sr value='search-wyniki'>";
	$sciezka_do_cookie = "hd_p_zgloszenie_kroki.php?nr=0&id=0";
	echo "&nbsp;<a href=# class='normalfont' style='font-size:10px; margin-bottom:15px;' onClick=\"self.location.href='hd_p_zgloszenia.php?sa=0&sr=1'\" >zaawansowane</a>";
	echo "&nbsp;<input type=submit class=buttons style='margin-top:4px; padding:2px 3px 1px 3px;' value='Szukaj'>&nbsp;";
	
	echo "</form>";
echo "</span>";
echo "</h5>";

echo "<div id=helpdesk_szcz>";

?>
<script>
if ((readCookie('p_raport_hd')=='TAK') || (readCookie('p_raport_hd')==null)) {
	$("#pokaz_hd").hide();
	$("#ukryj_hd").show();
	$("#helpdesk_szcz").show();
	createCookie('p_raport_hd','TAK',365);
	refresh_hd();
} else {
	$("#pokaz_hd").show();
	$("#ukryj_hd").hide();
	$("#helpdesk_szcz").hide();
}
</script>
<?php
echo "<div id=mz>";
startbuttonsarea("left");
	echo "<fieldset><legend><b>&nbsp;Moje zgłoszenia&nbsp;</b></legend>";
	echo "<span id=licznik_refresh_na_startowej></span>";
	echo "<table cellspacing=2 cellpadding=0 style='background-color:transparent; padding:0; margin:0; border:0px solid;' id=liczniki_moje_na_startowej></table>";
	echo "</fieldset>";
endbuttonsarea();
echo "</div>";
startbuttonsarea("left");
	echo "<fieldset><legend><b>&nbsp;Wszystkie zgłoszenia&nbsp;</b></legend>";
	echo "<table cellspacing=2 cellpadding=0 style='background-color:transparent; padding:0; margin:0; border:0px solid;' id=liczniki_wszystkie_na_startowej></table>";
	echo "</fieldset>";
endbuttonsarea();
echo "</span>";
echo "</div>";
?>

<?php 

$result = mysql_query("SELECT note_id,note_name FROM $dbname_hd.hd_notes WHERE note_alertdate='$dddd' and note_user_id=$es_nr and note_status='1'", $conn) or die($k_b);
$ilosc_notatek_na_dzisiaj = mysql_num_rows($result);

$result = mysql_query("SELECT note_id,note_name FROM $dbname_hd.hd_notes WHERE note_user_id=$es_nr and note_status='1'", $conn) or die($k_b);
$ilosc_notatek = mysql_num_rows($result);

if ($ilosc_notatek>0) {
	echo "<h5 id=Notatki style='font-size:13px;font-weight:normal;padding-top:4px;padding-bottom:4px;margin-top:0px;margin-bottom:5px;text-align:left;background:#FCBD9E;color:#000000;display: block; border: 1px solid #FBAB83;'>&nbsp;";
	echo "<a href=# class=normalfont id=pokaz_notatki style='display:none' type=button onClick=\"document.getElementById('notes_buttons').style.display=''; document.getElementById('notatki').style.display='';document.getElementById('pokaz_notatki').style.display='none'; document.getElementById('ukryj_notatki').style.display=''; createCookie('p_notatki','TAK',365); refresh_notatki1();\"><img class=imgoption style='border:0px' type=image src=img/expand.gif width=11 height=11><font color=black>&nbsp;Notatki dla mnie</font></a>";
	echo "<a href=# class=normalfont id=ukryj_notatki style='display:none' type=button onClick=\"document.getElementById('notes_buttons').style.display='none'; document.getElementById('notatki').style.display='none';document.getElementById('pokaz_notatki').style.display=''; document.getElementById('ukryj_notatki').style.display='none'; createCookie('p_notatki','NIE',365);\"><img class=imgoption style='border:0px' type=image src=img/collapse.gif width=11 height=11><font color=black>&nbsp;Notatki dla mnie</font></a>";
	
	//echo "&nbsp;Notatki dla mnie na dziś (<b>$ilosc_notatek_na_dzisiaj</b>)";
	echo "<span id=notatki_suffix></span>";
	
	echo "</h5>";
	echo "<span id=notatki style='margin-left:20px;'>";
	echo "</span>";
	
	?>
	<script>
		$('#notatki').load('hd_refresh_notes_na_startowej.php?userid=<?php echo $es_nr; ?>&randval='+ Math.random()+'&today=&nastartowej=1');
	</script>

	<?php
	
	echo "<div id=notes_buttons style='margin-left:20px;'>";
	echo "<span style='margin-left:-10px;'><input type=button class=buttons value=\"Pokaż wszystkie notatki ($ilosc_notatek)\"  onClick=\"createCookie('show_notatki','all',365); $('#notatki').load('hd_refresh_notes_na_startowej.php?userid=$es_nr&randval='+ Math.random()+'&nastartowej=1&today='); $('#notatki_suffix').text(' | wszystkie');\"/></span>";
	
	if ($ilosc_notatek_na_dzisiaj>0) echo "<span style=''><input type=button class=buttons value='Pokaż notatki na dziś ($ilosc_notatek_na_dzisiaj)' onClick=\"createCookie('show_notatki','today',365); refresh_notatki1(); $('#notatki_suffix').text(' | na dzisiaj');\"/></span>";	
	echo "</div>";
	//nowalinia();
	//nowalinia();
}

?>
<script>
if ((readCookie('p_notatki')=='TAK') || (readCookie('p_notatki')==null)) {
	$("#pokaz_notatki").hide();
	$("#ukryj_notatki").show();
	$("#notatki").show();
	$("#notes_buttons").show();
	refresh_notatki1();
	createCookie('p_notatki','TAK',365);
} else {
	$("#pokaz_notatki").show();
	$("#ukryj_notatki").hide();
	$("#notatki").hide();
	$("#notes_buttons").hide();
	refresh_notatki1();
}

if ((readCookie('show_notatki')=='all') || (readCookie('show_notatki')==null)) {
	$('#notatki').load('hd_refresh_notes_na_startowej.php?userid=<?php echo $es_nr;?>&randval='+ Math.random()+'&nastartowej=1&today=');
	$('#notatki_suffix').text(' | wszystkie');
}

if ((readCookie('show_notatki')=='today')) {
	refresh_notatki1();
	$('#notatki_suffix').text(' | na dzisiaj');
}

</script>
<?php
$result88 = mysql_query("SELECT * FROM $dbname.serwis_zadania_pozycje, $dbname.serwis_zadania WHERE (serwis_zadania.belongs_to=$es_filia) and (serwis_zadania_pozycje.pozycja_status<>9) and (pozycja_przypisane_osobie='$currentuser') and (serwis_zadania_pozycje.pozycja_zadanie_id=serwis_zadania.zadanie_id) ORDER BY zadanie_opis, pozycja_id DESC", $conn);
$ilosc_zadan_wszystkich = mysql_num_rows($result88);

$result88a = mysql_query("SELECT up_nazwa, pion_nazwa, todo_opis, todo_termin_koncowy FROM $dbname.serwis_komorka_todo, $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorka_todo.todo_up_id=serwis_komorki.up_id) and (serwis_piony.pion_id=serwis_komorki.up_pion_id) and (serwis_komorka_todo.todo_status<>9) and (serwis_komorki.belongs_to=$es_filia) and (todo_przypisane_osobie='$currentuser') ORDER BY todo_termin_koncowy ASC, up_nazwa ASC", $conn);
$ilosc_czynnosci_wszystkich = mysql_num_rows($result88a);

$ilosc_zadan_wszystkich+=$ilosc_czynnosci_wszystkich;

$result88 = mysql_query("SELECT * FROM $dbname.serwis_zadania_pozycje, $dbname.serwis_zadania WHERE (serwis_zadania.belongs_to=$es_filia) and (serwis_zadania_pozycje.pozycja_status<>9) and (pozycja_przypisane_osobie='$currentuser') and (serwis_zadania_pozycje.pozycja_zadanie_id=serwis_zadania.zadanie_id) and (serwis_zadania_pozycje.pozycja_zaplanowana_data_wykonania='".Date('Y-m-d')."') ORDER BY zadanie_opis, pozycja_id DESC", $conn);
$ilosc_zadan_na_dzisiaj = mysql_num_rows($result88);

$result88a = mysql_query("SELECT up_nazwa, pion_nazwa, todo_opis, todo_termin_koncowy FROM $dbname.serwis_komorka_todo, $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorka_todo.todo_up_id=serwis_komorki.up_id) and (serwis_piony.pion_id=serwis_komorki.up_pion_id) and (serwis_komorka_todo.todo_status<>9) and (todo_przypisane_osobie='$currentuser') and (serwis_komorka_todo.todo_termin_koncowy='".Date('Y-m-d')." 00:00:00') and (serwis_komorki.belongs_to=$es_filia) ORDER BY todo_termin_koncowy ASC, up_nazwa ASC", $conn);	

$ilosc_czynnosci_na_dzisiaj = mysql_num_rows($result88a);
//echo ">>>".$ilosc_czynnosci_na_dzisiaj;

$ilosc_zadan_na_dzisiaj+=$ilosc_czynnosci_na_dzisiaj;

if ($ilosc_zadan_wszystkich>0) {
	echo "<h5 id=Zadania style='font-size:13px; font-weight:normal;padding-top:4px;padding-bottom:4px; margin-top:0px;margin-bottom:5px;text-align:left;background:#DCBBAC;color:#000000;display: block; border: 1px solid #D0A794;'>&nbsp;";
	echo "<a href=# class=normalfont id=pokaz_zadania style='display:none' type=button onClick=\"document.getElementById('zadania_buttons').style.display=''; document.getElementById('zadania1').style.display='';document.getElementById('pokaz_zadania').style.display='none'; document.getElementById('ukryj_zadania').style.display=''; createCookie('p_zadania','TAK',365); \"><img class=imgoption style='border:0px' type=image src=img/expand.gif width=11 height=11><font color=black>&nbsp;Zadania dla mnie</font></a>";
	
	echo "<a href=# class=normalfont id=ukryj_zadania style='display:none' type=button onClick=\"document.getElementById('zadania_buttons').style.display='none'; document.getElementById('zadania1').style.display='none';document.getElementById('pokaz_zadania').style.display=''; document.getElementById('ukryj_zadania').style.display='none'; createCookie('p_zadania','NIE',365);\"><img class=imgoption style='border:0px' type=image src=img/collapse.gif width=11 height=11><font color=black>&nbsp;Zadania i czynności dla mnie</font></a>";
	
	echo "<span id=zadanie_suffix></span>";
	
	echo "</h5>";
	echo "<span id=zadania1 style='margin-left:20px;'>";
	echo "</span>";
	
	?>
	<script>
		//$('#zadania1').load('hd_refresh_zadania.php?osoba=<?php echo urlencode($currentuser); ?>&userid=<?php echo $es_nr; ?>&randval='+ Math.random()+'&today=<?php echo $dddd; ?>&nastartowej=1');
	</script>
	<?php
	
	echo "<div id=zadania_buttons style='margin-left:20px;'>";
	echo "<span style='margin-left:-10px;'><input type=button class=buttons value=\"Pokaż wszystkie zadania i czynności przypisane do mnie ($ilosc_zadan_wszystkich)\"  onClick=\"$('#zadania1').load('hd_refresh_zadania.php?osoba=".urlencode($currentuser)."&userid=$es_nr&randval=".rand(10000,10)."&nastartowej=1&today=');  createCookie('show_zadania','all',1); $('#zadanie_suffix').text(' | wszystkie'); \"/>";
	
	//echo "<input type=button class=buttons value=\"Pokaż wszystkie zadania\" onClick=\"$('#zadania1').load('hd_refresh_zadania.php?osoba=&userid=0&randval=".rand(10000,10)."&nastartowej=1&today=');  createCookie('show_zadania','all',1); $('#zadanie_suffix').text(' | wszystkie'); \"/>";
	
	echo "</span>";
	//echo "--->".$ilosc_zadan_na_dzisiaj;
	if ($ilosc_zadan_na_dzisiaj>0) {
		echo "<span style=''><input type=button class=buttons value='Pokaż zadania i czynności przypisane do mnie, zaplanowane do wykonania na dzisiaj ($ilosc_zadan_na_dzisiaj)' onClick=\"$('#zadania1').load('hd_refresh_zadania.php?test=2&osoba=".urlencode($currentuser)."&userid=$es_nr&randval=".rand(10000,10)."&nastartowej=1&today=".Date('Y-m-d')."'); createCookie('show_zadania','today',1); $('#zadanie_suffix').text(' | na dzisiaj');\"/></span>";
	} else {
		?>
		<script>
			$('#zadania1').load('hd_refresh_zadania.php?test=1&osoba=<?php echo urlencode($currentuser); ?>&userid=<?php echo $es_nr; ?>&randval='+ Math.random()+'&today=&nastartowej=1');
			$('#zadanie_suffix').text(' | wszystkie');
		</script>
		<?php	
	}
	echo "</div>";
}

?>
<script>
 
if ((readCookie('p_zadania')=='TAK') || (readCookie('p_zadania')==null)) {
	$("#pokaz_zadania").hide();
	$("#ukryj_zadania").show();
	$("#zadania1").show();
	$("#zadania_buttons").show();
<?php if ($ilosc_zadan_na_dzisiaj>0) { ?>
	createCookie('show_zadania','today',1);
	refresh_zadania1a();
	$('#zadanie_suffix').text(' | na dzisiaj');
<?php } else { ?>
	createCookie('show_zadania','today',0);
	$('#zadania1').load('hd_refresh_zadania.php?osoba=<?php echo urlencode($currentuser); ?>&userid=<?php echo $es_nr; ?>&randval='+ Math.random()+'&today=&nastartowej=1');
	$('#zadanie_suffix').text(' | wszystkie');
<?php }	?>	
	createCookie('p_zadania','TAK',365);
} else {
	$("#pokaz_zadania").show();
	$("#ukryj_zadania").hide();
	$("#zadania1").hide();
	$("#zadania_buttons").hide();	
}

if ((readCookie('show_zadania')=='all')) {
	$('#zadania1').load('hd_refresh_zadania.php?osoba=<?php echo urlencode($currentuser); ?>&userid=<?php echo $es_nr; ?>&randval='+ Math.random()+'&today=&nastartowej=1');
	$('#zadanie_suffix').text(' | wszystkie');
}

if ((readCookie('show_zadania')=='today') || (readCookie('show_zadania')==null)) {
	<?php if ($ilosc_zadan_na_dzisiaj>0) { ?>
		refresh_zadania1a();
		$('#zadanie_suffix').text(' | na dzisiaj');
	<?php } else { ?>
		$('#zadania1').load('hd_refresh_zadania.php?osoba=<?php echo urlencode($currentuser); ?>&userid=<?php echo $es_nr; ?>&randval='+ Math.random()+'&today=&nastartowej=1');
		$('#zadanie_suffix').text(' | wszystkie');
	<?php }	?>	
}

</script>