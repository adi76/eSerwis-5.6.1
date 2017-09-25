<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php'); 
?>
<body>
<?php 
if ($submit) { 

	// utworzenie nowego zestawu
	
	$zestawname = $_POST[opiszestawu];
	$dddd = Date('Y-m-d H:i:s');
	
	if ($zestawname=='') $zestawname = 'Zestaw utworzony '.$dddd.'';
	
	$sql_utworz_zestaw = "INSERT INTO $dbname.serwis_zestawy VALUES ('','$_POST[opiszestawu]','$currentuser','$dddd','',0,1,'$_REQUEST[hd_zgl_nr]',$es_filia)";	
	//echo $sql_utworz_zestaw;
	
	$utworz_zestaw = mysql_query($sql_utworz_zestaw, $conn) or die($k_b);
	$zestawid = mysql_insert_id();

	
	$ile=$_POST[pozcnt];
	$i=0;

	$countpozycje = 0;
	$jeden_towar = explode("|#|", $_REQUEST[pozfsz]);
	
	while ($i < $ile) {
		$pozycja_id = $jeden_towar[$i];
		
		$sql_zmienstatuspozycji = "UPDATE $dbname.serwis_faktura_szcz SET pozycja_status=5 WHERE pozycja_id=$pozycja_id LIMIT 1";
		echo $sql_zmienstatuspozycji;
		$zmienstatus = mysql_query($sql_zmienstatuspozycji,$conn) or die($k_b);
		
		$sql_dodajpozycje = "INSERT INTO $dbname.serwis_zestaw_szcz values ('',$zestawid,$pozycja_id)";
		echo $sql_dodajpozycje;
		
		$zapisz_pozycje = mysql_query($sql_dodajpozycje, $conn) or die($k_b);
		$countpozycje++;
		$i++;
	}
	
	?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	
} else {

pageheader('Tworzenie nowego zestawu',0,0);
//list($ilosc_podzespolow_wybranych)=mysql_fetch_array(mysql_query("SELECT COUNT(wp_id) FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_sprzet_active=1) and (belongs_to=$es_filia) and (wp_zgl_szcz_unique_nr='$_REQUEST[unique]')",$conn_hd));

$ilosc_podzespolow_wybranych = $_REQUEST['pozcnt'];
$jeden_towar = explode("|#|", $_REQUEST[pozfsz]);

$powiazanych = 0;
for ($i=0; $i<$ilosc_podzespolow_wybranych; $i++) {
	if ($jeden_towar[$i]!=0) $powiazanych++;
}

// zlicz ile jest podzespołów określonych wg typu
$sql_count_typy = "SELECT wp_typ_podzespolu,wp_sprzet_pocztowy FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_zgl_id=$_REQUEST[hd_zgl_nr]) and (wp_typ_podzespolu<>'') and (wp_wskazanie_sprzetu_z_magazynu='0') and (wp_sprzet_active=1)";

//list($typ_podzespolu,$czy_pocztowy)=mysql_fetch_array(mysql_query($sql_count_typy, $conn_hd));

$lista = '';
$lista_pocztowe = '';
$count_typ_podzespolu = 0;
$count_pocztowe = 0;

$result_count_typy = mysql_query($sql_count_typy, $conn_hd);
while (list($typ_podzespolu,$czy_pocztowy)=mysql_fetch_array($result_count_typy)) {
	if ($czy_pocztowy==0) {
		$lista.=$typ_podzespolu."<br />";
	} else {
		$lista_pocztowe.=$typ_podzespolu."<br />";
	}
	
	$count_typ_podzespolu++;
	if ($czy_pocztowy==1) $count_pocztowe++;
}
//echo $powiazanych." | ".$ilosc_podzespolow_wybranych." | ".$count_typ_podzespolu;

	if (($powiazanych==$ilosc_podzespolow_wybranych) && (($count_typ_podzespolu-$count_pocztowe)==0)) {

		echo "<form action=$PHP_SELF method=POST>";

		echo "<input type=hidden name=pozfsz value='$_REQUEST[pozfsz]'>";
		echo "<input type=hidden name=pozcnt value='$_REQUEST[pozcnt]'>";

		$jeden_towar = explode("|#|", $_REQUEST[pozfsz]);
		$cnt = $ilosc_podzespolow_wybranych;

		$list_pozycje = '';

		for ($i=0; $i<$cnt; $i++) { 
			$result1 = mysql_query("SELECT pozycja_id, pozycja_nazwa, pozycja_sn FROM $dbname.serwis_faktura_szcz WHERE (pozycja_id=$jeden_towar[$i]) LIMIT 1", $conn) or die($k_b);
			
			list($temp_pozid, $temp_poznazwa, $temp_pozsn)=mysql_fetch_array($result1);
			
			$list_pozycje .= $temp_poznazwa;
			if ($temp_pozsn!='') $list_pozycje .= " (SN: ".$temp_pozsn.")";	
			$list_pozycje .= " <br />";
		}

			infoheader("<b>Pozycje do dodania do zestawu:</b><br /><br />$list_pozycje");
			
			
			startbuttonsarea("center");	

				$dddd = Date('Y-m-d H:i');
				echo "<br />&nbsp;Opis zestawu: ";
				echo "<input type=text name=opiszestawu size=36 value='Zestaw utworzony $dddd'>&nbsp;&nbsp;";
				
			
			endbuttonsarea();
			
			if ($count_pocztowe>0) {
				echo "<br />";
				okheader("<b>Podzespoły pocztowe użyte do realizacji zgłoszenia</b><br /><br /><font style='font-weight:normal'>$lista_pocztowe</font>");
			}
			echo "<br />";
			startbuttonsarea("right");	
			addownsubmitbutton("'Dodaj zaznaczone pozycje do nowego zestawu'");
			addbuttons("zamknij");
			endbuttonsarea();

			echo "<input type=hidden name=hd_zgl_nr value='$_REQUEST[hd_zgl_nr]'>";
		_form();
	} else {
		$all = $ilosc_podzespolow_wybranych + $count_typ_podzespolu;
		$bez_powiazania = $count_typ_podzespolu - $count_pocztowe;
		
		errorheader("Aby móc utworzyć nowy zestaw<br />wszystkie wymienione podzespoły muszą być powiązane z konkretnymi towarami w magazynie");
		
		$i=0;
		starttable();
			tbl_tr_highlight($i);	echo "<td width=400 class=right>Ilość wszystkich podzespołów</td><td><b>$all</b></td>";	_tr(); $i++;
			tbl_empty_row(1);
			tbl_tr_highlight($i);	echo "<td class=right>Ilość przypisanych do towarów</td><td><b>$powiazanych</b></td>";	_tr(); $i++;
			if ($bez_powiazania>0) {
				tbl_tr_highlight($i);	echo "<td class=righttop><font color=red>Ilość typów podzespołów bez powiązania z towarem</font></td><td><font color=red><b>$bez_powiazania</b><hr />$lista</font></td>";	_tr(); $i++;
			}
			if ($count_pocztowe>0) {
				tbl_tr_highlight($i);	echo "<td class=righttop>Ilość  podzespołów 'pocztowych'</td><td><b>$count_pocztowe</b><hr />$lista_pocztowe</td>";	_tr(); $i++;
			}
		endtable();
		
		if ($allow_sell==1) {
			startbuttonsarea("right");	
			echo "<span style='float:left'>";
			echo "<input type=button class=buttons value='Towary na stanie' onClick=\"self.close(); if (opener) opener.location.href='p_towary_dostepne.php?view=normal'; \" />";
			echo "</span>";
		}

		addbuttons("zamknij");
		endbuttonsarea();		
	}
} 
?>
</body>
</html>