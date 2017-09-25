<?php include_once('header.php'); ?>
<body>
<?php
include('body_start.php'); 
if ($wybierz_typ=='') {
	$result = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni,magazyn_uwagi,magazyn_uwagi_sa FROM $dbname.serwis_magazyn WHERE magazyn_status=1 and belongs_to=$es_filia", $conn) or die($k_b);
} else {
	$result = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni,magazyn_uwagi,magazyn_uwagi_sa FROM $dbname.serwis_magazyn WHERE magazyn_status=1 and belongs_to=$es_filia and (magazyn_nazwa='$wybierz_typ')", $conn) or die($k_b);
}

if (mysql_num_rows($result)!=0) {
pageheader("Zwrot pobranego sprzętu serwisowego",1,1);

startbuttonsarea("center");
echo "<form name=magazyn action=$PHP_SELF method=GET>";
hr();
echo "Pokaż: ";
echo "<select name=wybierz_typ  onChange='document.location.href=document.magazyn.wybierz_typ.options[document.magazyn.wybierz_typ.selectedIndex].value'>";
	$sql_lista_p = "SELECT DISTINCT magazyn_nazwa FROM $dbname.serwis_magazyn WHERE (belongs_to=$es_filia) and (magazyn_status='1') ORDER BY magazyn_nazwa";
	$wynik_lista_typ = mysql_query($sql_lista_p,$conn) or die($k_b);
	echo "<option ";
	if ($wybierz_typ=='') echo "SELECTED ";
	echo "value='z_magazyn_zwroty.php?wybierz_typ='>Cały sprzęt</option>\n";	
	while (list($temp_magazyn_nazwa)=mysql_fetch_array($wynik_lista_typ)) {
		echo "<option "; 
		if ($wybierz_typ==$temp_magazyn_nazwa) echo "SELECTED ";
		echo "value='z_magazyn_zwroty_szczecin.php?wybierz_typ=$temp_magazyn_nazwa'>$temp_magazyn_nazwa</option>\n";	
	}
echo "</select>";
echo "</form>";
endbuttonsarea();	

starttable();
th("30;c;LP|;;Typ sprzętu|;;Model|;;Numer seryjny|;;Numer inwentarzowy|;;Aktualna lokalizacja<br/><sub>Data pobrania</sub>|;c;Uwagi|;c;Opcje",$es_prawa);
$i = 0;

while (list($mid, $mnazwa, $mmodel, $msn, $mni, $muwagi, $muwagisa)=mysql_fetch_array($result)) {
	
tbl_tr_highlight($i);

	$i++;
	td("30;c;".$i."");					
	td_(";;".$mnazwa."");
		if ($pokazpoczatekuwag==1) pokaz_uwagi($muwagi,$iloscznakowuwag,"newWindow(480,265,'p_magazyn_uwagi.php?id=$mid'); return false;");
	_td();
	td(";;".$mmodel."|;;".$msn."|;;".$mni."");

	$result_a = mysql_query("SELECT historia_id,historia_up, historia_komentarz,historia_data FROM $dbname.serwis_historia WHERE historia_magid=$mid ORDER BY historia_data DESC LIMIT 1", $conn) or die($k_b);
	list($hid,$hup,$hkomentarz,$hdata) = mysql_fetch_array($result_a);
 
	td_(";nw;;");
 
	$sql_up = "SELECT up_id FROM $dbname.serwis_komorki WHERE (up_nazwa='$hup') and (belongs_to=$es_filia) LIMIT 1";
	$wynik = mysql_query($sql_up, $conn) or die($k_b);
	$dane_up = mysql_fetch_array($wynik);
	$temp_up_id = $dane_up['up_id'];
 	
	echo "<a class=normalfont title=' Szczegółowe informacje o $hup ' onclick=\"newWindow_r(700,600,'p_komorka_szczegoly.php?id=$temp_up_id')\" href=#><b>$hup</b></a>";	
	echo "<br /><sub>$hdata</sub>";
	if ($pokazpoczatekuwag==1) pokaz_uwagi($hkomentarz,$iloscznakowuwag,"newWindow(480,265,'p_historia_uwagi.php?id=$hid')");
	_td();

	td_("40;c;;");
		if ($hkomentarz!='') { echo "<a title=' Czytaj uwagi o sprzęcie '><input class=imgoption type=image src=img/comment.gif  onclick=\"newWindow(480,265,'p_historia_uwagi.php?id=$hid')\"></a></td>";	} else echo "-";
	_td();
  
	$sql_a1 = "SELECT naprawa_status FROM $dbname.serwis_naprawa WHERE ((naprawa_sprzet_zastepczy_id=$mid) and (naprawa_status<5))";
	$result_a1 = mysql_query($sql_a1, $conn) or die($k_b);
	$num_rows = mysql_num_rows($result_a1); 

	$id=0;
	list($id)= mysql_fetch_array($result_a1);

	td_("70;c;;");
		if ($num_rows==0) {
			echo "<a title=' Zwróć $mnazwa $mmodel o numerze seryjnym $msn na magazyn '><input class=imgoption type=image src=img//return.gif  onclick=\"newWindow(535,340,'z_magazyn_zwrot_szczecin.php?id=$mid&part=$mnazwa')\"></a>";
		} else {
			if ($id==3) echo "<a href=main.php?action=npns title=' Sprzęt powiązany z naprawą '><img class=imgoption src=img/snapraw_w.gif border=0></a>";
			if ($id==-1) echo "<a href=main.php?action=npus title=' Sprzęt powiązany z naprawą '><img class=imgoption src=img/snapraw_w.gif border=0></a>";
			if ($id==1) echo "<a href=main.php?action=npswsz title=' Sprzęt powiązany z naprawą '><img class=imgoption src=img/snapraw_w.gif border=0></a>";
			if ($id==2) echo "<a href=main.php?action=npswsz title=' Sprzęt powiązany z naprawą '><img class=imgoption src=img/snapraw_w.gif border=0></a>";
		}
	_td();

_tr();
}
endtable();
} else
    {
		br();
		errorheader("Cały sprzęt jest w magazynie");
		?>
		<meta http-equiv="REFRESH" content="<?php echo $http_refresh; ?>;url=<?php echo "$linkdostrony";?>main.php">
		<?php
	}

startbuttonsarea("right");
addbuttons("start");
endbuttonsarea();
include('body_stop.php');
include('js/menu.js');

?>
</body>
</html>