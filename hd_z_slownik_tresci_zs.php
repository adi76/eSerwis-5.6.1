<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body>
<?php include('body_start.php');

//echo "---> ".$_GET[p2]." | ".$_GET[p3]." ".$_GET[p6];

$sql="SELECT * FROM $dbname_hd.hd_slownik_tresci ";
if ($es_m!=1) { $sql = $sql." WHERE belongs_to=$es_filia"; }
$sql.=" and (1=1) ";

// wg kategorii
if (($_REQUEST[p2]!='X') && ($_REQUEST[p2]!='0') && ($_REQUEST[p2]!='')) $sql.="AND (tresc_kategoria='$_REQUEST[p2]') ";
// wg podkategorii
if (($_REQUEST[p3]!='X') && ($_REQUEST[p3]!='0') && ($_REQUEST[p3]!='')) $sql.="AND (tresc_podkategoria='$_REQUEST[p3]') ";
// wg podkategorii (poziom 2)
if (($_REQUEST[p4]!='X') && ($_REQUEST[p4]!='0') && ($_REQUEST[p4]!='')) $sql.="AND (tresc_podkategoria_poziom_2='$_REQUEST[p4]') ";	
// wg przypisania
if (($_REQUEST[p6]!='X') && ($_REQUEST[p6]!='0') && ($_REQUEST[p6]!='')) {
	$p_6='';
	if ($_REQUEST[p6]!='9') $p_6=$_REQUEST[p6];
	$sql.="AND (tresc_osoba='$p_6') ";
}
$sql = $sql." ORDER BY tresc_id ASC";

$result = mysql_query($sql, $conn_hd) or die($k_b);
$totalrows = mysql_num_rows($result);
if ($showall==0) { $rps=$rowpersite; } else $rps=10000;
if(empty($page)){ $page = 1; }
$limitvalue = $page * $rps - ($rps);
$sql="SELECT * FROM $dbname_hd.hd_slownik_tresci ";
if ($es_m!=1) { $sql = $sql." WHERE belongs_to=$es_filia"; }
$sql.=" and (1=1) ";

	// wg kategorii
	if (($_REQUEST[p2]!='X') && ($_REQUEST[p2]!='0') && ($_REQUEST[p2]!='')) $sql.="AND (tresc_kategoria='$_REQUEST[p2]') ";
	// wg podkategorii
	if (($_REQUEST[p3]!='X') && ($_REQUEST[p3]!='0') && ($_REQUEST[p3]!='')) $sql.="AND (tresc_podkategoria='$_REQUEST[p3]') ";
	// wg podkategorii (poziom 2)
	if (($_REQUEST[p4]!='X') && ($_REQUEST[p4]!='0') && ($_REQUEST[p4]!='')) $sql.="AND (tresc_podkategoria_poziom_2='$_REQUEST[p4]') ";	
	// wg przypisania
	if (($_REQUEST[p6]!='X') && ($_REQUEST[p6]!='0') && ($_REQUEST[p6]!='')) {

			$p_6='';
			if ($_REQUEST[p6]!='9') $p_6=$_REQUEST[p6];
			$sql.="AND (tresc_osoba='$p_6') ";
	}
$sql = $sql." ORDER BY tresc_id ASC LIMIT $limitvalue, $rps";

//echo $sql;

$result = mysql_query($sql, $conn_hd) or die($k_b);

if ($totalrows!=0) {

echo "<input type=hidden name=showall1 id=showall1 value=$_REQUEST[showall]>";
echo "<input type=hidden name=page11 id=page11 value=$_REQUEST[page]>";
echo "<input type=hidden name=page12 id=page12 value=$_REQUEST[paget]>";
echo "<input type=hidden name=rpsite id=rpsite value=$rps>";
echo "<input type=hidden name=akcja id=akcja value=$_REQUEST[akcja]>";

if ($_GET[akcja]!='wybierz') {
	pageheader("Przeglądanie słownika treści zgłoszeń dla osoby: <b>$currentuser</b>",1,0);
} else {
	pageheader("Wybór treści ze słownika",0,0);
}

	startbuttonsarea("center");
	
	if ($showall==0) {
		echo "<a class=paging href=$PHP_SELF?showall=1&paget=$page&hd_rps=$_REQUEST[hd_rps]&p2=$_REQUEST[p2]&p3=$_REQUEST[p3]&p4=$_REQUEST[p4]&p6=".urlencode($_REQUEST[p6])."&akcja=$_REQUEST[akcja]&refreshed=$_REQUEST[refreshed]>Pokaż wszystko na jednej stronie</a>";
	} else {
		echo "<a class=paging href=$PHP_SELF?showall=1&paget=$page&hd_rps=$_REQUEST[hd_rps]&p2=$_REQUEST[p2]&p3=$_REQUEST[p3]&p4=$_REQUEST[p4]&p6=".urlencode($_REQUEST[p6])."&akcja=$_REQUEST[akcja]&refreshed=$_REQUEST[refreshed]>Dziel na strony</a>";	
	}

	nowalinia();
	nowalinia();
	
	echo "Filtrowanie: ";
	// wg kategorii
	echo "<select class=select_hd_p_zgloszenia style='display:none;' id=filtr2 name=filtr2 onChange=\"ApplyFiltrHD_sl_1a(true);\" />";
	echo "<option value='X'"; 	if ($_REQUEST[p2]=='X') echo " SELECTED";	echo ">wg kategorii</option>\n"; 
	echo "<option value=0";		if ($_REQUEST[p2]=='0') echo " SELECTED";	echo ">-wszystkie-</option>\n";
	echo "<option>-</options>\n";
	
	$sql_f1="SELECT DISTINCT(hd_slownik_tresci.tresc_kategoria), hd_slownik_tresci.tresc_kategoria,hd_kategoria.hd_kategoria_nr, hd_kategoria.hd_kategoria_opis FROM $dbname_hd.hd_slownik_tresci, $dbname_hd.hd_kategoria WHERE (hd_slownik_tresci.tresc_kategoria=hd_kategoria.hd_kategoria_nr) ORDER BY hd_kategoria_display_order ASC";
	$result_f1=mysql_query($sql_f1,$conn) or die($k_b);
	
	while ($dane_f1=mysql_fetch_array($result_f1)) {
		$temp_nr = $dane_f1['hd_kategoria_nr'];
		$temp_opis = $dane_f1['hd_kategoria_opis'];
		echo "<option value='$temp_nr'";	if ($_REQUEST[p2]==$temp_nr) echo " SELECTED";	echo ">$temp_opis</option>\n";
	}
	echo "</select>";
	echo "&nbsp;";
	
	// wg podkategorii
	echo "<select class=select_hd_p_zgloszenia id=filtr3 name=filtr3 onChange=\"ApplyFiltrHD_sl_1a(true);\" />";
	echo "<option value='X'"; 	if ($_REQUEST[p3]=='X') echo " SELECTED";	echo ">wg podkategorii</option>\n"; 
	echo "<option value=0";		if ($_REQUEST[p3]=='0') echo " SELECTED";	echo ">-wszystkie-</option>\n";
	echo "<option>-</options>\n";
	
	//$sql_f1="SELECT DISTINCT(hd_slownik_tresci.tresc_podkategoria), hd_slownik_tresci.tresc_podkategoria,hd_podkategoria.hd_podkategoria_nr, hd_podkategoria.hd_podkategoria_opis FROM $dbname_hd.hd_slownik_tresci, $dbname_hd.hd_podkategoria WHERE (hd_slownik_tresci.tresc_podkategoria=hd_podkategoria.hd_podkategoria_nr) ORDER BY hd_podkategoria_opis ASC";
	if ($_REQUEST[p2]=='1') $sql_f1="SELECT DISTINCT(hd_slownik_tresci.tresc_podkategoria), hd_slownik_tresci.tresc_podkategoria,hd_podkategoria.hd_podkategoria_nr, hd_podkategoria.hd_podkategoria_opis FROM $dbname_hd.hd_slownik_tresci, $dbname_hd.hd_podkategoria WHERE (hd_slownik_tresci.tresc_podkategoria=hd_podkategoria.hd_podkategoria_nr) and (hd_podkategoria.hd_podkategoria_nr=1) ORDER BY hd_podkategoria_order ASC";
	
	if ($_REQUEST[p2]=='7') $sql_f1="SELECT DISTINCT(hd_slownik_tresci.tresc_podkategoria), hd_slownik_tresci.tresc_podkategoria,hd_podkategoria.hd_podkategoria_nr, hd_podkategoria.hd_podkategoria_opis FROM $dbname_hd.hd_slownik_tresci, $dbname_hd.hd_podkategoria WHERE (hd_slownik_tresci.tresc_podkategoria=hd_podkategoria.hd_podkategoria_nr) and (hd_podkategoria.hd_podkategoria_nr=1) ORDER BY hd_podkategoria_order ASC";

	if (($_REQUEST[p2]=='2') || ($_REQUEST[p2]=='6')) $sql_f1="SELECT DISTINCT(hd_slownik_tresci.tresc_podkategoria), hd_slownik_tresci.tresc_podkategoria,hd_podkategoria.hd_podkategoria_nr, hd_podkategoria.hd_podkategoria_opis FROM $dbname_hd.hd_slownik_tresci, $dbname_hd.hd_podkategoria WHERE (hd_slownik_tresci.tresc_podkategoria=hd_podkategoria.hd_podkategoria_nr) and (hd_podkategoria.hd_podkategoria_nr IN ('5','2','4','3','0','9')) ORDER BY hd_podkategoria_order ASC";

	if ($_REQUEST[p2]=='3') $sql_f1="SELECT DISTINCT(hd_slownik_tresci.tresc_podkategoria), hd_slownik_tresci.tresc_podkategoria,hd_podkategoria.hd_podkategoria_nr, hd_podkategoria.hd_podkategoria_opis FROM $dbname_hd.hd_slownik_tresci, $dbname_hd.hd_podkategoria WHERE (hd_slownik_tresci.tresc_podkategoria=hd_podkategoria.hd_podkategoria_nr) and (hd_podkategoria.hd_podkategoria_nr IN ('4','3','D','9')) ORDER BY hd_podkategoria_order ASC";
	
	if ($_REQUEST[p2]=='4') $sql_f1="SELECT DISTINCT(hd_slownik_tresci.tresc_podkategoria), hd_slownik_tresci.tresc_podkategoria,hd_podkategoria.hd_podkategoria_nr, hd_podkategoria.hd_podkategoria_opis FROM $dbname_hd.hd_slownik_tresci, $dbname_hd.hd_podkategoria WHERE (hd_slownik_tresci.tresc_podkategoria=hd_podkategoria.hd_podkategoria_nr) and (hd_podkategoria.hd_podkategoria_nr IN ('D','A')) ORDER BY hd_podkategoria_order ASC";
	
	if ($_REQUEST[p2]=='5') $sql_f1="SELECT DISTINCT(hd_slownik_tresci.tresc_podkategoria), hd_slownik_tresci.tresc_podkategoria,hd_podkategoria.hd_podkategoria_nr, hd_podkategoria.hd_podkategoria_opis FROM $dbname_hd.hd_slownik_tresci, $dbname_hd.hd_podkategoria WHERE (hd_slownik_tresci.tresc_podkategoria=hd_podkategoria.hd_podkategoria_nr) and (hd_podkategoria.hd_podkategoria_nr IN ('1')) ORDER BY hd_podkategoria_order ASC";	
	$result_f1=mysql_query($sql_f1,$conn) or die($k_b);
	
	while ($dane_f1=mysql_fetch_array($result_f1)) {
		$temp_nr = $dane_f1['hd_podkategoria_nr'];
		$temp_opis = $dane_f1['hd_podkategoria_opis'];
		if ($temp_opis!='') { echo "<option value='$temp_nr'";	if ($_REQUEST[p3]==$temp_nr) echo " SELECTED";	echo ">$temp_opis</option>\n"; }
	}
	echo "</select>";
	echo "&nbsp;";

	// wg podkategorii poziom 2

	echo "<select class=select_hd_p_zgloszenia style='display:none;' id=filtr4 name=filtr4 onChange=\"ApplyFiltrHD_sl_1a(true);\" />";
	echo "<option value='X'"; 	if (($_REQUEST[p4]=='X') || ($_REQUEST[p4]=='')) echo " SELECTED";	echo ">wg podk. (poziom 2)</option>\n"; 
	echo "<option value='0'";	if ($_REQUEST[p4]=='0') echo " SELECTED";	echo ">-wszystkie-</option>\n";
	echo "<option>-</options>\n";

	if (($_REQUEST[p3]!='X') && ($_REQUEST[p3]!='0')) {
		$sql_f1="SELECT DISTINCT(tresc_podkategoria_poziom_2) FROM $dbname_hd.hd_slownik_tresci WHERE tresc_podkategoria='$_REQUEST[p3]' ORDER BY tresc_podkategoria_poziom_2 ASC";
		$result_f1=mysql_query($sql_f1,$conn) or die($k_b);
	
		while ($dane_f1=mysql_fetch_array($result_f1)) {
			$temp_opis = $dane_f1['tresc_podkategoria_poziom_2'];
			echo "<option value='$temp_opis'";	if (($_REQUEST[p4]==$temp_opis) && ($_REQUEST[p4]!='')) echo " SELECTED";	echo ">$temp_opis</option>\n";
		}
	}
	
	echo "</select>";
	echo "&nbsp;";	
	
	$KierownikId = $kierownik_nr;	
	// wyciągnij imie i nazwisko kierownika danej filii
	//$r40 = mysql_query("SELECT filia_leader FROM $dbname.serwis_filie WHERE (filia_id='$es_filia') LIMIT 1", $conn) or die($k_b);
	//list($KierownikId)=mysql_fetch_array($r40);
	// koniec wyciągania danych o kierowniku
				
	// wg przypisania
	echo "<select class=select_hd_p_zgloszenia id=filtr6 name=filtr6 "; 

	if (($es_nr!=$KierownikId) && ($es_m!=1)) {
		//echo " DISABLED ";
	}
	echo "onChange=\"ApplyFiltrHD_sl_1a(true);\" />";
	echo "<option value='X'"; 	if ($_REQUEST[p6]=='X') echo " SELECTED";	echo ">wg przypisania</option>\n"; 
	echo "<option value=0";		if ($_REQUEST[p6]=='0') echo " SELECTED";	echo ">-wszystkie-</option>\n";
//	echo "<option value=9";		if ($_REQUEST[p6]=='9') echo " SELECTED";	echo ">nie przypisane</option>\n";
	echo "<option>-</options>\n";	
	
	$sql_f1="SELECT DISTINCT(hd_slownik_tresci.tresc_osoba) FROM $dbname_hd.hd_slownik_tresci WHERE (belongs_to=$es_filia) ORDER BY tresc_osoba ASC";
	$result_f1=mysql_query($sql_f1,$conn) or die($k_b);
	
	while ($dane_f1=mysql_fetch_array($result_f1)) {
		$temp_osoba = $dane_f1['tresc_osoba'];
		if ($temp_osoba=='') {
			echo "<option value='9'";	if ($_REQUEST[p6]=='9') echo " SELECTED";	echo ">nie przypisane</option>\n";
		} else {
			echo "<option value='$temp_osoba'";	if ($_REQUEST[p6]==$temp_osoba) echo " SELECTED";	echo ">$temp_osoba</option>\n";
		}
	}
	echo "</select>";
	echo "&nbsp;";

	if ($_GET[akcja]!='manage') {
		echo "<input type=button class=buttons style='padding:1px; margin:0px;height:21px;' value=' Zastostuj filtr ' onClick=\"ApplyFiltrHD_sl_2(true); \" />";
	} else {
		echo "<input type=button class=buttons style='padding:1px; margin:0px;height:21px;' value=' Zastostuj filtr ' onClick=\"ApplyFiltrHD_sl_3(true); \" />";
	}
	
endbuttonsarea();

starttable();
if ($_REQUEST[p6]!='') {
	th("40;c;LP|;l;Treść<br />Kategoria->Podkategoria->Podkategoria (poziom 2)|60;c;Opcje",$es_prawa);
} else {
	th("40;c;LP|;l;Treść<br />Kategoria->Podkategoria->Podkategoria (poziom 2)|;l;Twórca|60;c;Opcje",$es_prawa);
}
/*if ($_GET[akcja]=='manage') {
	
} else {
	th("40;c;LP|;l;Treść",$es_prawa);
}
*/
$i = 0;
$j = $page*$rowpersite-$rowpersite+1;
while ($newArray = mysql_fetch_array($result)) {
	$temp_id  				= $newArray['tresc_id'];
	$temp_tresc				= $newArray['tresc_tresc'];
	$temp_osoba				= $newArray['tresc_osoba'];
	$temp_kat				= $newArray['tresc_kategoria'];
	$temp_podkat			= $newArray['tresc_podkategoria'];
	$temp_podkat2			= $newArray['tresc_podkategoria_poziom_2'];
	//tbl_tr_highlight($i);
	//tbl_tr_highlight_dblClick($i);
	if ($i % 2 != 0 ) echo "<tr id=$i class=nieparzyste onmouseover=rowOver('$i',1); this.style.cursor=arrow onmouseout=rowOver('$i',0) onclick=\"selectRow('$i'); \" onDblClick=\"WybierzTrescZS('".nl2br2($temp_tresc)."'); \">";
	if ($i % 2 == 0 ) echo "<tr id=$i class=parzyste onmouseover=rowOver('$i',1); this.style.cursor=arrow onmouseout=rowOver('$i',0) onclick=\"selectRow('$i'); \" onDblClick=\"WybierzTrescZS('".nl2br2($temp_tresc)."'); \">";
	
	$r1 = mysql_query("SELECT hd_kategoria_opis FROM $dbname_hd.hd_kategoria WHERE (hd_kategoria_nr='$temp_kat') LIMIT 1", $conn_hd) or die($k_b);list($kat_opis)=mysql_fetch_array($r1);
	$r2 = mysql_query("SELECT hd_podkategoria_opis FROM $dbname_hd.hd_podkategoria WHERE (hd_podkategoria_nr='$temp_podkat') LIMIT 1", $conn_hd) or die($k_b);list($podkat_opis)=mysql_fetch_array($r2);
		
		td(";c;".$j."");
		td_(";l");
			echo "<a title='";
			if ($kat_opis!='') echo "Kategoria: $kat_opis";
			if (($kat_opis!='') || ($podkat_opis!='')) echo ", ";
			if ($podkat_opis!='') echo "Podkategoria: $podkat_opis";
			echo "'>";
			echo nl2br(wordwrap($temp_tresc, 100, "<br />"));
			echo "</a>";
			echo "<br />";
			echo "<font color=grey>";
			if ($kat_opis!='') echo "$kat_opis";
			if (($kat_opis!='') || ($podkat_opis!='')) echo " -> ";
			if ($podkat_opis!='') { echo "$podkat_opis"; } else { echo "-"; }
			echo " -> ";
			if ($temp_podkat2!='') { echo "$temp_podkat2"; } else { echo "-"; }
			echo "</font>";
		_td();
		
	if ($_REQUEST[p6]=='') {
		td_(";l");
			echo $temp_osoba;
		_td();
	}
	
		td_(";c");
			if ($_GET[akcja]=='manage') {
				if ($currentuser==$temp_osoba) {
					echo "<a title=' Edytuj treść $temp_tresc '><input class=imgoption type=image src=img/edit.gif  onclick=\"newWindow(600,400,'hd_e_slownik_tresc.php?id=$temp_id')\"></a>";
				
				echo "<a title=' Usuń treść $temp_tresc ze słownika '><input class=imgoption type=image src=img/delete.gif onclick=\"if (confirm('Czy napewno chcesz usunąć treść $temp_tresc ze słownika ?')) newWindow(10,10,'hd_u_slownik_tresc.php?select_id=$temp_id')\"></a>";
				}
			} else {
				echo "<a title=' Wybierz treść '><input class=imgoption type=image src=img/ok.gif  onclick=\"WybierzTrescZS('".nl2br2($temp_tresc)."'); \"></a>";
			
			}
		_td();
	
	_tr();
	$i++;
	$j++;
}
endtable();
include_once('paging_end.php');
	startbuttonsarea("right");
	echo "<span style=float:left>";
	if ($_GET[akcja]!='manage') {
		echo "<input type=button class=buttons value=' Pokaż wszystkie moje' onClick=\" self.location.href='hd_z_slownik_tresci_zs.php?akcja=1&p2=X&p3=X&refreshed=0&p6=".urlencode($currentuser)."'; \">";
		//if (($es_nr==$KierownikId) || ($es_m==1)) {
		echo "<input type=button class=buttons value=' Pokaż wszystkie z filii / oddziału' onClick=\" self.location.href='hd_z_slownik_tresci_zs.php?akcja=$_GET[akcja]'; \">";
		//}
	} else {
		echo "<input type=button class=buttons value=' Pokaż wszystkie moje' onClick=\" self.location.href='hd_z_slownik_tresci_zs.php?akcja=$_GET[akcja]&p2=X&p3=X&refreshed=0&p6=".urlencode($currentuser)."'; \">";
		//if (($es_nr==$KierownikId) || ($es_m==1)) {
		echo "<input type=button class=buttons value=' Pokaż wszystkie z filii / oddziału' onClick=\" self.location.href='hd_z_slownik_tresci_zs.php?akcja=$_GET[akcja]&p2=X&p3=X'; \">";
	
		//}
	}
	echo "</span>";
	
	if (($_GET[akcja]!='wybierz') && ($_GET[akcja]!='pokazwszystkie')) {
		addownlinkbutton("'Dodaj nową treść'","Button1","button","newWindow(600,400,'hd_d_slownik_tresc.php')");
	}
	
	echo "<input class=buttons type=button onClick=\"eraseCookie('current_window_name'); self.close();\" value=Zamknij>";
	endbuttonsarea();
} else {

	$r1 = mysql_query("SELECT hd_kategoria_opis FROM $dbname_hd.hd_kategoria WHERE (hd_kategoria_nr='$_REQUEST[p2]') LIMIT 1", $conn_hd) or die($k_b);
	list($kat_opis)=mysql_fetch_array($r1);
	$r2 = mysql_query("SELECT hd_podkategoria_opis FROM $dbname_hd.hd_podkategoria WHERE (hd_podkategoria_nr='$_REQUEST[p3]') LIMIT 1", $conn_hd) or die($k_b);
	list($podkat_opis)=mysql_fetch_array($r2);
	if (($_REQUEST[p4]=='X') || ($_REQUEST[p4]=='')) $_REQUEST[p4] = '-';
	
	errorheader("Brak treści spełniających podane kryteria lub słownik treści jest pusty<br /><br />Wybrane kryteria:<br /><font color=white><b>$kat_opis -> $podkat_opis -> $_REQUEST[p4]</b></font>");
	startbuttonsarea("right");
	
	echo "<span style=float:left>";
	if ($_GET[akcja]!='manage') {
		echo "<input type=button class=buttons value=' Pokaż wszystkie moje' onClick=\" self.location.href='hd_z_slownik_tresci_zs.php?akcja=$_$REQUEST[akcja]&p2=X&p3=X&refreshed=0&p6=".urlencode($currentuser)."'; \">";
		//if (($es_nr==$KierownikId) || ($es_m==1)) {
		echo "<input type=button class=buttons value=' Pokaż wszystkie z filii / oddziału' onClick=\" self.location.href='hd_z_slownik_tresci_zs.php?akcja=$_GET[akcja]'; \">";
		//}
	} else {
		echo "<input type=button class=buttons value=' Pokaż wszystkie moje' onClick=\" self.location.href='hd_z_slownik_tresci_zs.php?akcja=$_GET[akcja]&p2=X&p3=X&refreshed=0&p6=".urlencode($currentuser)."'; \">";
		//if (($es_nr==$KierownikId) || ($es_m==1)) {
		echo "<input type=button class=buttons value=' Pokaż wszystkie z filii / oddziału' onClick=\" self.location.href='hd_z_slownik_tresci_zs.php?akcja=$_GET[akcja]&p2=X&p3=X'; \">";
	
		//}
	}
	echo "</span>";
	
	//addownlinkbutton("'Wróć do poprzedniego widoku'","Button1","button","newWindow(600,400,'hd_d_slownik_tresc.php')");
	//echo "<input class=buttons type=button onClick=\"self.location.href='hd_z_slownik_tresci.php?akcja=$akcja&p2=X&p3=X&p6=".urlencode($currentuser)."'; \" value='Wróć do poprzedniego widoku'>";
	if ($_GET[akcja]=='manage') {
			//addbuttons("wstecz");
			addownlinkbutton("'Dodaj nową treść'","Button1","button","newWindow(600,400,'hd_d_slownik_tresc.php')");
	}
///	addbuttons("wstecz");
	echo "<input class=buttons type=button onClick=\"eraseCookie('current_window_name'); self.close();\" value=Zamknij>";
	endbuttonsarea();
}

?>
</body>
</html>