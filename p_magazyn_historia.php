<?php include_once('header.php'); ?>
<body>
<?php include('body_start.php'); ?>
<?php 
if ($es_m==1) {
	$result44 = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni,magazyn_uwagi_sa,magazyn_uwagi,magazyn_status,belongs_to FROM $dbname.serwis_magazyn ORDER BY magazyn_nazwa", $conn) or die($k_b);
} else {
	$result44 = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni,magazyn_uwagi_sa,magazyn_uwagi,magazyn_status,belongs_to FROM $dbname.serwis_magazyn WHERE belongs_to=$es_filia ORDER BY magazyn_nazwa", $conn) or die($k_b);
}
if (mysql_num_rows($result44)!=0) {
	pageheader("Historia ruchów wybranego sprzętu",0,1);
	?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
	starttable();
	if ($es_m==1) {
		th("30;c;LP|;;Nazwa|;;Model|;;Numer seryjny|;;Numer inwentarzowy|;;Uwagi<br /><sub>Filia</sub>|40;c;Opcje",$es_prawa);
	} else {
		th("30;c;LP|;;Nazwa|;;Model|;;Numer seryjny|;;Numer inwentarzowy|;;Uwagi|40;c;Opcje",$es_prawa);
	}
	$i = 0;
	while (list($mid,$mnazwa,$mmodel,$msn,$mni,$muwagisa,$muwagi,$mstatus,$bt)=mysql_fetch_array($result44)) {
		tbl_tr_highlight($i);
	 	$i++;
			td("30;c;".$i."");
			td_(";;".$mnazwa."");
				if ($pokazpoczatekuwag==1) pokaz_uwagi($muwagi,$iloscznakowuwag,"newWindow(480,265,'p_magazyn_uwagi.php?id=$mid'); return false;");
			_td();
			td(";;".$mmodel."|;;".$msn."|;;".$mni."");
			td_img(";c");
				if ($muwagisa=='1') { echo "<a title='Czytaj uwagi '><input type=image class=imgoption src=img/comment.gif onclick=\"newWindow(480,265,'p_magazyn_uwagi.php?id=$mid'); return false;\"></a>";}
				echo "<a title=' Edytuj uwagi '><input type=image class=imgoption src=img/edit_comment.gif onclick=\"newWindow(480,245,'e_magazyn_uwagi.php?id=$mid')\"></a>";
				if ($es_m==1) include("p_filia_nazwa.php");
			_td();
			td_img(";c");
				echo "<a title=' Historia ruchów sprzętu $mnazwa $mmodel o numerze seryjnym $msn '><input class=imgoption type=image src=img/history.gif onclick=\"window.location.href='r_magazyn_historia.php?id=$mid'\"></a>";	
			_td();
		_tr();
	}
	endtable();
} else
errorheader("Brak historii ruchów sprzętu");
startbuttonsarea("right");
oddziel();
echo "<span style='float:left'>";
echo "Pokaż historię ruchów: ";
addlinkbutton("'Całego sprzętu'","r_magazyn_historia_wszystko.php");
addlinkbutton("'Sprzętu w okresie'","main.php?action=rso");
//addlinkbutton("'Wybranego sprzętu'","p_magazyn_historia.php");
addlinkbutton("'Sptzętu wg komórki / daty'","main.php?action=rswgup");
//addlinkbutton("'Ukryty sprzęt'","main.php?action=pus");
echo "</span>";

addbuttons("start");
endbuttonsarea();	
include('body_stop.php'); 
?>
<script>HideWaitingMessage();</script>
</body>
</html>