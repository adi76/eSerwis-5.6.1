<?php include_once('header.php'); ?>
<body>
<?php
include('body_start.php');
include('cfg_helpdesk.php');

$result_a = mysql_query("SELECT * FROM $dbname.serwis_historia WHERE historia_magid=$id", $conn) or die($k_b);
$count_rows=mysql_num_rows($result_a);

if ($count_rows!=0) {
	pageheader("Szczegóły sprzętu",1,1);
	$result = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni,magazyn_uwagi_sa,magazyn_uwagi FROM $dbname.serwis_magazyn WHERE magazyn_id=$id and belongs_to=$es_filia", $conn) or die($k_b);
	starttable();
	th(";;Nazwa|;;Model|;;Numer seryjny|;;Numer inwentarzowy",$es_prawa);
	while (list($mid,$mnazwa,$mmodel,$msn,$mni,$muwagi,$muwagi1) = mysql_fetch_array($result)) {	
		tbl_tr_highlight(1);
			td_(";;".$mnazwa."");
				if ($pokazpoczatekuwag==1) pokaz_uwagi($muwagi,$iloscznakowuwag,"newWindow(480,265,'p_magazyn_uwagi.php?id=$mid'); return false;");
			_td();
			td(";;".$mmodel."|;;".$msn."|;;".$mni."");
		_tr();
	}
	endtable();						
	br();
	pageheader("Historia ruchu sprzętu");
	
	?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
		
	starttable();
	th("60;c;Czynność|;;Skąd/Dokąd|;nw;Osoba odpowiedzialna<br />Data wykonania czynności|;w;Uwagi|50;c;Opcje",$es_prawa);
	$result_a = mysql_query("SELECT historia_id,historia_magid,historia_up,historia_user,historia_data,historia_ruchsprzetu,historia_komentarz,historia_naprawa_id FROM $dbname.serwis_historia WHERE historia_magid=$id ORDER BY historia_data DESC", $conn) or die($k_b);
	$i=100;	
	while (list($hid,$hmagin,$hup,$huser,$hdata,$hrs,$hkomentarz,$hnid) = mysql_fetch_array($result_a)) {
		tbl_tr_highlight($i);
		$i++;
			td(";c;".$hrs."");
			td_img("250;nw");
				list($temp_up_id) = mysql_fetch_array(mysql_query("SELECT up_id FROM $dbname.serwis_komorki WHERE (up_nazwa='$hup') and (belongs_to=$es_filia) LIMIT 1", $conn));
				
				$sql_up = "SELECT up_id, up_pion_id FROM $dbname.serwis_komorki WHERE (up_nazwa='$hup') and (belongs_to=$es_filia) LIMIT 1";
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
	
				echo "<a class=normalfont title=' Szczegółowe informacje o $temp_pion_nazwa $hup ' onclick=\"newWindow_r(700,600,'p_komorka_szczegoly.php?id=$temp_up_id')\" href=#><b>$temp_pion_nazwa $hup</b></a>";		
			_td();
			td("115;;".$huser."<br />".substr($hdata,0,16)."|;w;".urldecode($hkomentarz)."");
			td_("50;c;");
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
} else errorheader("Historia ruchu tego sprzętu jest pusta");
startbuttonsarea("right");
oddziel();
echo "<span style='float:left'>";
echo "&nbsp;Pokaż historię ruchów: ";
addlinkbutton("'Całego sprzętu'","r_magazyn_historia_wszystko.php");
addlinkbutton("'Sprzętu w okresie'","main.php?action=rso");
//addlinkbutton("'Wybranego sprzętu'","p_magazyn_historia.php");
addlinkbutton("'Sptzętu wg komórki / daty'","main.php?action=rswgup");
//addlinkbutton("'Ukryty sprzęt'","main.php?action=pus");
echo "</span>";

addbackbutton("Wybierz inny sprzęt");
addbuttons("start");
endbuttonsarea();
include('body_stop.php');
?>

<script>HideWaitingMessage();</script>

</body>
</html>