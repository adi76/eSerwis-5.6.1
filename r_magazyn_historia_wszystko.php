<?php include_once('header.php'); ?>
<body>
<?php
include('body_start.php');
include('cfg_helpdesk.php');

if ($es_m==1) {
	$result_a = mysql_query("SELECT * FROM $dbname.serwis_historia ORDER BY historia_data DESC", $conn) or die($k_b);
} else {
	$result_a = mysql_query("SELECT * FROM $dbname.serwis_historia WHERE (belongs_to=$es_filia) ORDER BY historia_data DESC", $conn) or die($k_b);
}
$totalrows = mysql_num_rows($result_a);
// paging
if ($showall==0) { $rps=$rowpersite; } else $rps=10000;
if(empty($page)){ $page = 1;}
$limitvalue = $page * $rps - ($rps);
if ($es_m==1) {
	$sql_a="SELECT historia_id,historia_magid,historia_up,historia_user,historia_data,historia_ruchsprzetu,historia_komentarz,belongs_to,historia_naprawa_id FROM $dbname.serwis_historia ORDER BY historia_data DESC LIMIT $limitvalue, $rps";
} else {
	$sql_a="SELECT historia_id,historia_magid,historia_up,historia_user,historia_data,historia_ruchsprzetu,historia_komentarz,belongs_to,historia_naprawa_id FROM $dbname.serwis_historia WHERE belongs_to=$es_filia ORDER BY historia_data DESC LIMIT $limitvalue, $rps";
}
$result_a = mysql_query($sql_a, $conn) or die($k_b);
// koniec - paging
$count_rows = mysql_num_rows($result_a);

if ($count_rows!=0) {
	pageheader("Historia ruchów całego sprzętu",1,1);

	?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();

	startbuttonsarea("center");
	if ($showall==0) {
		echo "<a class=paging href=r_magazyn_historia_wszystko.php?showall=1&paget=$page>Pokaż wszystko na jednej stronie</a>";
	} else {
		echo "<a class=paging href=r_magazyn_historia_wszystko.php?showall=0&page=$paget>Dziel na strony</a>";
	}

	echo "<a href=# class=paging1>";
	echo "| Łącznie: <b>$totalrows pozycji</b>";
	echo "</a>";
	
	endbuttonsarea();
	starttable();
	if ($es_m==1) {
		th(";c;Czynność|;;Skąd/Dokąd|;;Typ sprzętu, model<br />Numer seryjny|;nw;Osoba odpowiedzialna<br />Data wykonania czynności|;;Uwagi<br /><sub>Filia</sub>|;c;Opcje",$es_prawa);
	} else {
		th(";c;Czynność|;;Skąd/Dokąd|;;Typ sprzętu, model<br />Numer seryjny|;nw;Osoba odpowiedzialna<br />Data wykonania czynności|;;Uwagi|;c;Opcje",$es_prawa);
	}
	$i=$page*$rowpersite-$rowpersite;

	while (list($hid,$hmagin,$hup,$huser,$hdata,$hrs,$hkomentarz,$bt,$hnid) = mysql_fetch_array($result_a)) {
		tbl_tr_highlight($i);
	  	$i++;
			td(";c;".$hrs."");
			td_img(";");
				list($temp_up_id) = mysql_fetch_array(mysql_query("SELECT up_id FROM $dbname.serwis_komorki WHERE (up_nazwa='$hup') and (belongs_to=$es_filia) LIMIT 1", $conn));
				
				$sql_up = "SELECT up_id, up_pion_id FROM $dbname.serwis_komorki WHERE (up_nazwa='$hup') and (belongs_to=$es_filia) LIMIT 1";
				$wynik = mysql_query($sql_up, $conn) or die($k_b);
				$dane_up = mysql_fetch_array($wynik);
				$temp_up_id1 = $dane_up['up_id'];
				$temp_pion_id = $dane_up['up_pion_id'];
	
				// nazwa pionu z id pionu
				$sql_get_pion = "SELECT pion_nazwa FROM $dbname.serwis_piony WHERE (pion_id='$temp_pion_id') LIMIT 1";
				$wynik_get_pion = mysql_query($sql_get_pion, $conn) or die($k_b);
				$dane_get_pion = mysql_fetch_array($wynik_get_pion);
				$temp_pion_nazwa = $dane_get_pion['pion_nazwa'];
				// koniec ustalania nazwy pionu
	
				echo "<a class=normalfont title=' Szczegółowe informacje o $temp_pion_nazwa $hup ' onclick=\"newWindow_r(700,600,'p_komorka_szczegoly.php?id=$temp_up_id')\" href=#><b>$temp_pion_nazwa $hup</b></a>";		
			_td();
			$result_a9 = mysql_query("SELECT magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni FROM $dbname.serwis_magazyn WHERE magazyn_id=$hmagin LIMIT 1", $conn) or die($k_b);
			list($sprzet,$sprzet1,$sprzet2,$sprzet3) = mysql_fetch_array($result_a9);
			td(";;<a title=' NI : ".$sprzet3." '>".$sprzet." ".$sprzet1."<br />".$sprzet2."</a>");
			td(";;".$huser."<br />".substr($hdata,0,16)."");
			td_(";;");
				if ($hkomentarz!='') { echo "".urldecode($hkomentarz).""; } else echo "-";
				if ($es_m==1) include("p_filia_nazwa.php");
			_td();
			td_(";c;");
			
				$result_a4 = mysql_query("SELECT zgl_nr FROM $dbname_hd.hd_zgloszenie WHERE (zgl_sprzet_serwisowy_id=$hmagin) LIMIT 1", $conn) or die($k_b);
				list($n_zgl_id) = mysql_fetch_array($result_a4);
				
				if ($n_zgl_id>0) {
					$LinkHDZglNr=$n_zgl_id; include('linktohelpdesk.php');
				}
						
				if ($hnid!=0) echo "<a title=' Pokaż szczegóły naprawy powiązanej z pobraniem tego sprzętu '><input class=imgoption type=image src=img/detail.gif  onclick=\"newWindow(800,600,'p_naprawy_szczegoly.php?id=$hnid')\"></a>";
			_td();
		_tr();
	}
	endtable();
	include_once('paging_end.php');
} else {
	errorheader("Historia ruchu sprzętu jest pusta");
	?><meta http-equiv="REFRESH" content="<?php echo $http_refresh; ?>;url=<?php echo "$linkdostrony";?>main.php"><?php	
}
startbuttonsarea("right");
oddziel();
echo "<span style='float:left'>";
echo "&nbsp;Pokaż historię ruchów: ";
//addlinkbutton("'Całego sprzętu'","r_magazyn_historia_wszystko.php");
addlinkbutton("'Sprzętu w okresie'","main.php?action=rso");
addlinkbutton("'Wybranego sprzętu'","p_magazyn_historia.php");
addlinkbutton("'Sprzętu wg komórki / daty'","main.php?action=rswgup");
echo "</span>";

addbuttons("start");
endbuttonsarea();
include('body_stop.php');
?>

<script>HideWaitingMessage();</script>

</body>
</html>