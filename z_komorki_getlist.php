<?php
include_once('header.php');

//echo "otherkat: ".$_REQUEST[otherkat]." | othertyp: ".$_REQUEST[othertyp]." | kategoria: ".$_REQUEST[kategoria]." | typ: ".$_REQUEST[typ]."<br />";

$sql="SELECT * FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ";
$sql=$sql."(serwis_komorki.belongs_to=$_REQUEST[filia]) and ";
//if (($_REQUEST[othertyp]==0) && ($_REQUEST[otherkat]==1)) 
$sql.=" (serwis_komorki.up_pion_id=serwis_piony.pion_id) ";
//if ($_REQUEST[otherkat]!=1) $sql.=" (serwis_komorki.up_pion_id=serwis_piony.pion_id) ";
//$sql.=" and (serwis_komorki.up_typ=$_REQUEST[typ]) ";

if ($_REQUEST[othertyp]!=1) {
	if ($_REQUEST[typ]!='all') {
		$sql.=" and (serwis_komorki.up_typ=$_REQUEST[typ]) ";
	} else {
		$sql.=" and (serwis_komorki.up_typ>0) ";
	}
} else {
	$sql.=" and (serwis_komorki.up_typ=0) ";
}
	
if ($_REQUEST[otherkat]!=1) {
	if ($_REQUEST[kategoria]!='all') {
		$sql.=" and (serwis_komorki.up_kategoria=$_REQUEST[kategoria]) ";
	} else {
		$sql.=" and (serwis_komorki.up_kategoria>=0) ";
	}
} else $sql.=" and (serwis_komorki.up_kategoria=0) ";

$sql.=" and (serwis_komorki.up_active=1) ";
$sql=$sql." ORDER BY serwis_piony.pion_nazwa,serwis_komorki.up_nazwa ASC";

//echo $sql;

$result = mysql_query($sql, $conn) or die($k_b);
$count_rows = mysql_num_rows($result);
$totalrows = mysql_num_rows($result);

if ($count_rows!=0) {
	pageheader("Lista komórek z wybranego zestawienia",0,0);
	
	starttable('99%');
	th("30;c;LP|;;Nazwa|;c;Typ<br/><sub>Kategoria</sub>|;c;Opcje",$es_prawa);
	$i = 0;
	$j = 1;
	
	while ($newArray = mysql_fetch_array($result)) {
		$temp_id  				= $newArray['up_id'];
		$temp_nazwa				= $newArray['up_nazwa'];
		$temp_up_typ			= $newArray['up_typ'];
		$temp_up_kategoria		= $newArray['up_kategoria'];
		$temp_pion_id			= $newArray['up_pion_id'];
	
		tbl_tr_highlight($i);
		$result1 = mysql_query("SELECT filia_nazwa FROM $dbname.serwis_filie WHERE filia_id=$_REQUEST[filia]", $conn) or die($k_b);
		list($temp_filia_nazwa)=mysql_fetch_array($result1);
			echo "<td class=center>";
			if ($temp_active_status=='2') echo "<font color=red>";
			if ($temp_active_status=='0') echo "<strike>";
			echo $j;
			if ($temp_active_status=='0') echo "</strike>";
			if ($temp_active_status=='2') echo "</font>";
			echo "</td>";
			$j++;
			
			list($typkomorki)=mysql_fetch_array(mysql_query("SELECT slownik_typ_komorki_opis FROM $dbname.serwis_slownik_typ_komorki WHERE slownik_typ_komorki_id=$temp_up_typ LIMIT 1", $conn));
			list($kategoriakomorki)=mysql_fetch_array(mysql_query("SELECT slownik_kategoria_komorki_opis FROM $dbname.serwis_slownik_kategoria_komorki WHERE slownik_kategoria_komorki_id=$temp_up_kategoria LIMIT 1", $conn));			
			list($pionnazwa)=mysql_fetch_array(mysql_query("SELECT pion_nazwa FROM $dbname.serwis_piony WHERE pion_id=$temp_pion_id LIMIT 1", $conn));

		td_(";w");
			echo "".$pionnazwa." ".$temp_nazwa."";			
		_td();

		echo "<td class=center>";
			echo $typkomorki."<br /><sub>".$kategoriakomorki."</sub>";
		echo "</td>";
			
		td_(";c");
		
		
			echo "<a title=' Edycja danych o $temp_nazwa '><input class=imgoption type=image src=img/edit.gif onclick=\"newWindow_r(800,600,'e_komorka.php?select_id=$temp_id'); return false;\"></a>";
				
			$accessLevels = array("9");
			if (array_search($es_prawa, $accessLevels)>-1) {
				echo "<a title=' Usuń $temp_nazwa z bazy '><input class=imgoption type=image src=img/delete.gif  onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_komorka.php?select_id=$temp_id')\"></a>";
			}
			
			echo "<a title=' Szczegółowe informacje o $temp_nazwa '><input class=imgoption type=image src=img/detail.gif onclick=\"newWindow_r(700,600,'p_komorka_szczegoly.php?id=$temp_id')\"></a>";
			echo "<a title=' Pokaż sprzęt będący na stanie $temp_nazwa ' href=p_ewidencja.php?action=ewid_all&view=all&sel_up=$temp_id&printpreview=0&allowback=2 title=' Pokaż sprzęt będący na stanie $temp_nazwa '><img class=imgoption src=img/software.gif border=0 width=16 width=16></a>";
			
		_td();
		$i++;
	_tr();
}
endtable();
} else { 
	errorheader("Baza komórek jest pusta lub brak pozycji spełniających wybrane kryteria");
}
?>
