<?php include_once('header.php'); ?>
<body>
<?php
include('body_start.php'); 
if ($wybierz_typ=='') {
$result = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni,magazyn_uwagi,magazyn_uwagi_sa FROM $dbname.serwis_magazyn WHERE magazyn_status='0' and belongs_to=$es_filia", $conn) or die($k_b);
} else 
$result = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni,magazyn_uwagi,magazyn_uwagi_sa FROM $dbname.serwis_magazyn WHERE magazyn_status='0' and belongs_to=$es_filia and (magazyn_nazwa='$wybierz_typ')", $conn) or die($k_b);

//$result = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni,magazyn_uwagi,magazyn_uwagi_sa FROM $dbname.serwis_magazyn WHERE magazyn_status='0' and belongs_to=$es_filia", $conn) or die($k_b);

if (mysql_num_rows($result)!=0) {
	pageheader("Pobranie sprzętu serwisowego",1,1);
	
	startbuttonsarea("center");
	echo "<form name=magazyn action=$PHP_SELF method=GET>";
	hr();
	echo "Pokaż: ";
	echo "<select name=wybierz_typ  onChange='document.location.href=document.magazyn.wybierz_typ.options[document.magazyn.wybierz_typ.selectedIndex].value'>";
			$sql_lista_p = "SELECT DISTINCT magazyn_nazwa FROM $dbname.serwis_magazyn WHERE (belongs_to=$es_filia) and (magazyn_status='0') ORDER BY magazyn_nazwa";
			$wynik_lista_typ = mysql_query($sql_lista_p,$conn) or die($k_b);
			echo "<option ";
			if ($wybierz_typ=='') echo "SELECTED ";
			echo "value='e_magazyn_pobierz.php?wybierz_typ='>Cały sprzęt</option>\n";	
			while (list($temp_magazyn_nazwa)=mysql_fetch_array($wynik_lista_typ)) {
				echo "<option "; 
				if ($wybierz_typ==$temp_magazyn_nazwa) echo "SELECTED ";
				echo "value='e_magazyn_pobierz_szczecin.php?wybierz_typ=$temp_magazyn_nazwa'>$temp_magazyn_nazwa</option>\n";	
			
			}
	echo "</select>";
	echo "</form>";
	endbuttonsarea();	
	
	starttable();
	th("30;c;LP|;;Typ sprzętu|;;Model|;;Numer seryjny|;;Numer inwentarzowy|;c;Uwagi|;c;Opcje",$es_prawa);

	$i = 0;
	while (list($mid, $mnazwa, $mmodel, $msn, $mni, $muwagi, $muwagisa) = mysql_fetch_array($result)) {
		tbl_tr_highlight($i);
		$i++;
		td("30;c;".$i."");
		td_(";;".$mnazwa."");
		if ($pokazpoczatekuwag==1) pokaz_uwagi($muwagi,$iloscznakowuwag,"newWindow(480,265,'p_magazyn_uwagi.php?id=$mid'); return false;");
		_td();
		td(";;".$mmodel."|;;".$msn."|;;".$mni."");
		td_img("40;c");
			if ($muwagisa=='1') { echo "<a title='Czytaj uwagi '><input class=imgoption type=image src=img/comment.gif onclick=\"newWindow(480,265,'p_magazyn_uwagi.php?id=$mid'); return false;\"></a>";}
			echo "<a title=' Edytuj uwagi '><input type=image class=imgoption src=img/edit_comment.gif onclick=\"newWindow(480,245,'e_magazyn_uwagi.php?id=$mid')\"></a>";
		_td();
		td_img("60;c");
			echo "<a title=' Pobierz $mnazwa $mmodel o numerze seryjnym $msn z magazynu '><input type=image class=imgoption src=img/pobierz.gif  onclick=\"newWindow(595,335,'z_magazyn_pobierz_szczecin.php?id=$mid&part=$mnazwa')\"></a>";
		_td();
		_tr();
	}
	endtable();
} else { 
		br();
		errorheader('Brak sprzętu na magazynie');
		?>
		<meta http-equiv="REFRESH" content="2;url=<?php echo "$linkdostrony";?>main.php">
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