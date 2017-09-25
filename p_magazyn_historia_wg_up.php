<?php include_once('header.php'); ?>
<body>
<?php 

if ($submit) { 
	include('cfg_helpdesk.php');
	include('body_start.php');
	$okres_od=$_GET[okres_od];
	$okres_do=$_GET[okres_do];
	$tup=$_GET[tup];

	if (($okres_od!="") && ($okres_do!="")) {
		if ($tup!="") {
			if ($es_m==1) {
				$sql_a = "SELECT * FROM $dbname.serwis_historia WHERE historia_up='$tup' and historia_data BETWEEN '$okres_od 00:00:00' AND '$okres_do 23:59:59'";
			} else {
				$sql_a = "SELECT * FROM $dbname.serwis_historia WHERE belongs_to=$es_filia and historia_up='$tup' and historia_data BETWEEN '$okres_od 00:00:00' AND '$okres_do 23:59:59'";
			}
		} else { 
			if ($es_m==1) {
				$sql_a = "SELECT * FROM $dbname.serwis_historia WHERE historia_data BETWEEN '$okres_od 00:00:00' AND '$okres_do 23:59:59'"; 
			} else {
				$sql_a = "SELECT * FROM $dbname.serwis_historia WHERE belongs_to=$es_filia and historia_data BETWEEN '$okres_od 00:00:00' AND '$okres_do 23:59:59'"; 
			}
		}
	} else { 
		if ($es_m==1) {
			$sql_a = "SELECT * FROM $dbname.serwis_historia WHERE historia_up='$tup'"; 
		} else {
			$sql_a = "SELECT * FROM $dbname.serwis_historia WHERE belongs_to=$es_filia and historia_up='$tup'"; 
		}
	}
	$result_a = mysql_query($sql_a, $conn) or die($k_b);
	$count_rows = mysql_num_rows($result_a);
	
	if ($count_rows>0) {
		pageheader("Historia ruchu sprzętu w",1,1);
		infoheader("<b>".$tup."</b><br />w okresie<br /><b>".$okres_od." - ".$okres_do."</b>");

		?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
		
		starttable();
		if ($es_m==1) {
			th("60;c;Czynność|;;Skąd / Dokąd|;;Typ sprzętu, model<br />Numer seryjny, inwentarzowy|115;nw;Osoba odpowiedzialna<br />Data operacji|;;Uwagi<br /><sub>Filia</sub>|50;c;Opcje",$es_prawa);
		} else {
			th("60;c;Czynność|;;Skąd / Dokąd|;;Typ sprzętu, model<br />Numer seryjny, inwentarzowy|115;nw;Osoba odpowiedzialna<br />Data operacji|;c;Uwagi|50;c;Opcje",$es_prawa);
		}
		$result = mysql_query("SELECT filia_nazwa FROM $dbname.serwis_filie WHERE filia_id=$es_filia LIMIT 1", $conn) or die($k_b);
		list($filian) = mysql_fetch_array($result);
		$wlasny='Magazyn ' .$filian;
		if (($okres_od!="") && ($okres_do!="")) {
			if ($tup!="") {
				if ($es_m==1) {
					$sql_a = "SELECT historia_id,historia_magid,historia_up,historia_user,historia_data,historia_ruchsprzetu,historia_komentarz,belongs_to,historia_naprawa_id FROM $dbname.serwis_historia WHERE historia_up='$tup' and historia_data BETWEEN '$okres_od 00:00:00' AND '$okres_do 23:59:59'"; 
				} else {
					$sql_a = "SELECT historia_id,historia_magid,historia_up,historia_user,historia_data,historia_ruchsprzetu,historia_komentarz,belongs_to,historia_naprawa_id FROM $dbname.serwis_historia WHERE belongs_to=$es_filia and historia_up='$tup' and historia_data BETWEEN '$okres_od 00:00:00' AND '$okres_do 23:59:59'"; 
				}
			} else {
				if ($es_m==1) {
					$sql_a = "SELECT historia_id,historia_magid,historia_up,historia_user,historia_data,historia_ruchsprzetu,historia_komentarz,belongs_to,historia_naprawa_id FROM $dbname.serwis_historia WHERE historia_data BETWEEN '$okres_od 00:00:00' AND '$okres_do 23:59:59'";
				} else {
					$sql_a = "SELECT historia_id,historia_magid,historia_up,historia_user,historia_data,historia_ruchsprzetu,historia_komentarz,belongs_to,historia_naprawa_id FROM $dbname.serwis_historia WHERE belongs_to=$es_filia and historia_data BETWEEN '$okres_od 00:00:00' AND '$okres_do 23:59:59'";
				}
			}
		} else { 
			if ($es_m==1) {
				$sql_a = "SELECT historia_id,historia_magid,historia_up,historia_user,historia_data,historia_ruchsprzetu,historia_komentarz,belongs_to,historia_naprawa_id FROM $dbname.serwis_historia WHERE belongs_to=$es_filia and historia_up='$tup'"; 
			} else {
				$sql_a = "SELECT historia_id,historia_magid,historia_up,historia_user,historia_data,historia_ruchsprzetu,historia_komentarz,belongs_to,historia_naprawa_id FROM $dbname.serwis_historia WHERE belongs_to=$es_filia and historia_up='$tup'"; 
			}
		}
		$result_a = mysql_query($sql_a, $conn) or die($k_b);
		$i=0;
		
		while (list($hid,$hmagin,$hup,$huser,$hdata,$hrs,$hkomentarz,$bt,$hnid) = mysql_fetch_array($result_a)) {
			$result_a1 = mysql_query("SELECT magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni FROM $dbname.serwis_magazyn WHERE magazyn_id=$hmagin LIMIT 1", $conn) or die($k_b);
			list($mag_nazwa,$mag_model,$mag_sn,$mag_ni)= mysql_fetch_array($result_a1);
		
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
				td(";;<b>".$mag_nazwa." ".$mag_model."</b><br />".$mag_sn.", ".$mag_ni."");
				td("110;;".$huser."<br />".substr($hdata,0,16)."");
				td_(";w;");
					if ($hkomentarz!='') { echo "".urldecode($hkomentarz).""; } else echo "-";
					if ($es_m==1) include("p_filia_nazwa.php");
				_td();
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
	} else errorheader("Nie znaleziono pozycji spełniających podane przez Ciebie kryteria");

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

addlinkbutton("'Zmień kryteria'","main.php?action=rswgup");
addbuttons("start");
endbuttonsarea();			
include('body_stop.php');
?>

<script>HideWaitingMessage();</script>

<?php 
} else { 
br();
pageheader("Historia ruchu sprzętu wg wybranego UP");
starttable("500px");
echo "<form name=ruch action=p_magazyn_historia_wg_up.php method=GET onsubmit='return validateForm();'>";
tbl_empty_row();
tr_();
	td_colspan(2,'c');
		echo "<b>Podaj zakres dat<br /><br /></b>";
	_td();
_tr();
tr_();
	td("150;c;od dnia");
	td("150;c;do dnia");
_tr();

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

tr_();
	td_img(";c");
		echo "<input class=wymagane size=10 maxlength=10 type=text id=okres_od name=okres_od value=$data1 onkeypress='return handleEnter(this, event);' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\">";	
		echo "<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif width=16 height=16 border=0></a>";
		if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('okres_od').value='".Date('Y-m-d')."'; return false;\">";
	_td();
	td_img(";c");
		echo "<input class=wymagane size=10 maxlength=10 type=text id=okres_do name=okres_do value=$data2 onkeypress='return handleEnter(this, event);' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\">";
		echo "<a tabindex=-1 href=javascript:cal11.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif width=16 height=16 border=0></a>";
		if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('okres_do').value='".Date('Y-m-d')."'; return false;\">";
	_td();	
_tr();
tbl_empty_row();
tr_();
	td_colspan(2,'c');
		echo "<b>Wybierz komórkę/UP<br /><br /></b>";
	_td();
_tr();
tr_();
	td_colspan(2,'c');
	if ($es_m==1) {
		$result6 = mysql_query("SELECT serwis_komorki.up_id, serwis_komorki.up_nazwa, serwis_piony.pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) ORDER BY serwis_piony.pion_nazwa,up_nazwa", $conn) or die($k_b);
	} else {
		$result6 = mysql_query("SELECT serwis_komorki.up_id, serwis_komorki.up_nazwa, serwis_piony.pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) ORDER BY serwis_piony.pion_nazwa,up_nazwa", $conn) or die($k_b);
	}
		echo "<select name=tup onkeypress='return handleEnter(this, event);'>\n"; 					 				
		echo "<option value=''>Wybierz z listy...";
		while (list($temp_id,$temp_nazwa,$pion) = mysql_fetch_array($result6)) {
			echo "<option value='$temp_nazwa'>$pion $temp_nazwa</option>\n"; 
		}
		echo "</select>\n";
	_td();
_tr();
tbl_empty_row();
endtable();
startbuttonsarea("center");
addownsubmitbutton("'Pokaż'","submit");
endbuttonsarea();
oddziel();
echo "<span style='float:left'>";
echo "&nbsp;Pokaż historię ruchów: ";
addlinkbutton("'Całego sprzętu'","r_magazyn_historia_wszystko.php");
addlinkbutton("'Sprzętu w okresie'","main.php?action=rso");
//addlinkbutton("'Wybranego sprzętu'","p_magazyn_historia.php");
addlinkbutton("'Sptzętu wg komórki / daty'","main.php?action=rswgup");
//addlinkbutton("'Ukryty sprzęt'","main.php?action=pus");
echo "</span>";

_form();	
?>

<script language="JavaScript">
var cal1 = new calendar1(document.forms['ruch'].elements['okres_od']);
	cal1.year_scroll = true;
	cal1.time_comp = false;
var cal11 = new calendar1(document.forms['ruch'].elements['okres_do']);
	cal11.year_scroll = true;
	cal11.time_comp = false;
</script>

<script language="JavaScript" type="text/javascript">
	var frmvalidator  = new Validator("ruch");
	frmvalidator.addValidation("okres_od","req","Nie podałeś daty początkowej");  
	frmvalidator.addValidation("okres_do","req","Nie podałeś daty końcowej");  
	frmvalidator.addValidation("okres_od","numerichyphen","Użyłeś niedozwolonych znaków w polu \"od dnia\"");
	frmvalidator.addValidation("okres_do","numerichyphen","Użyłeś niedozwolonych znaków w polu \"do dnia\"");
</script>
<?php } ?>
</body>
</html>