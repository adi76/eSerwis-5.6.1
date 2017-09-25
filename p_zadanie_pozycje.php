<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body>
<?php include('body_start.php'); ?>
<?php
pageheader("Przeglądanie UP / komórek przypisanych do zadania",1);
?>
<script>ShowWaitingMessage();</script><?php ob_flush(); flush();

$result = mysql_query("SELECT * FROM $dbname.serwis_zadania WHERE (zadanie_id=$id) LIMIT 1", $conn) or die($k_b);
while ($newArray = mysql_fetch_array($result)) {
	$temp_zid  			= $newArray['zadanie_id'];
	$temp_opis			= $newArray['zadanie_opis'];
	$temp_termin		= $newArray['zadanie_termin_zakonczenia'];
	$temp_uwagi			= $newArray['zadanie_uwagi'];
	$temp_priorytet		= $newArray['zadanie_priorytet'];
	$temp_status_z		= $newArray['zadanie_status'];
	$temp_data_zam		= $newArray['zadanie_data_zakonczenia'];
	$temp_osoba_zam		= $newArray['zadanie_zakonczone_przez'];
	
	$temp_wc			= $newArray['zadanie_hd_wc'];
	$temp_osoba			= $newArray['zadanie_hd_osoba'];
}

$enableHD = 1;
if (($temp_wc == '') || ($temp_osoba == '')) $enableHD = 0;

$termin = substr($temp_termin,0,10); 
if ($termin!='0000-00-00') {
	$pozostalo_dni = round( abs(strtotime(date('Y-m-d'))-strtotime($termin)) / 86400, 0 );
} else $pozostalo_dni='-';
if ($termin=='0000-00-00') { $termin='nieokreślony'; }
if ($temp_priorytet==0) $priorytet='NISKI';
if ($temp_priorytet==1) $priorytet='NORMALNY';
if ($temp_priorytet==2) $priorytet='WYSOKI';
startbuttonsarea("center");
if ($enableHD==0) infoheader("Z tego zadania nie ma możliwości utworzenia zgłoszenia Helpdesk");
echo "Zadanie : <b>$temp_opis</b>&nbsp;";
echo "<a title=' Czytaj uwagi do zadania'><input class=imgoption type=image align=absmiddle src=img/comment.gif onclick=\"newWindow(480,265,'p_zadania_uwagi.php?id=$temp_zid')\"></a>";
echo "<br />Termin zakończenia : <b>$termin</b> | Priorytet : <b>$priorytet</b>";	
if ($temp_status_z==9) {
	echo "<br /><br />Data zamknięcia zadania : <b>$temp_data_zam</b><br />";
	echo "Zadanie zamknięte przez : <b>$temp_osoba_zam</b><br />";
}
endbuttonsarea();
$result1 = mysql_query("SELECT filia_leader FROM $dbname.serwis_filie WHERE filia_id=$es_filia LIMIT 1", $conn) or die($k_b);
list($kierownik1)=mysql_fetch_array($result1);
$kierownik = ($kierownik1==$es_nr);

$sql="SELECT * FROM $dbname.serwis_zadania_pozycje WHERE (belongs_to=$es_filia) and (pozycja_zadanie_id=$id) ";
$result = mysql_query($sql, $conn) or die($k_b);
$count_all_rows = mysql_num_rows($result);

$sql="SELECT * FROM $dbname.serwis_zadania_pozycje WHERE (serwis_zadania_pozycje.belongs_to=$es_filia) and (serwis_zadania_pozycje.pozycja_zadanie_id=$id) ";
//$sql="SELECT * FROM $dbname.serwis_zadania_pozycje, $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_zadania_pozycje.belongs_to=$es_filia) and (pozycja_zadanie_id=$id) and (serwis_zadania_pozycje.pozycja_komorka=serwis_komorki.up_nazwa) and (up_pion_id=pion_id) and (serwis_komorki.belongs_to=$es_filia) ";
if ($all==0) $sql.=" and (pozycja_status=0)";
if ($all==9) $sql.=" and (pozycja_status=9)";
if ($all==99) $sql.=" and (pozycja_przypisane_osobie='$currentuser')";
if (($_REQUEST[dlaosoby]!='') && ($_REQUEST[dlaosoby]!='BP')) $sql.=" and (pozycja_przypisane_osobie='$_REQUEST[dlaosoby]') ";
if ($_REQUEST[dlaosoby]=='BP') $sql .= " and (pozycja_przypisane_osobie = '') ";

if ($_REQUEST[nadzien]!='') $sql.=" and (pozycja_zaplanowana_data_wykonania='$_REQUEST[nadzien]') ";
$sql.=" ORDER BY pozycja_komorka";

//echo $sql;

$result = mysql_query($sql, $conn) or die($k_b);
$count_rows = mysql_num_rows($result);

// ===
	$sql33="SELECT * FROM $dbname.serwis_zadania_pozycje WHERE (belongs_to=$es_filia) and (pozycja_zadanie_id=$id) and (pozycja_zaplanowana_data_wykonania<>'0000-00-00')";
	$result33 = mysql_query($sql33, $conn) or die($k_b);
	$count_all_rows33 = mysql_num_rows($result33);
// ===

$accessLevels = array("9");
if(array_search($es_prawa, $accessLevels)>-1) {
	$update_value = 0;
} else $update_value = 1;
	
echo "<form name=addzp action=p_zadanie_pozycje.php method=POST>";
if ($count_rows>0) {	
	starttable();	
	if ($temp_status_z!=-1)	{
		echo "<tr>";
			echo "<th width=40 class=center>LP</th>";
			echo "<th class=left>UP/Komórka</th>";
			echo "<th class=center>Przypisane osobie</th>";
			if ($count_all_rows33>0) echo "<th class=center>Zaplanowana<br />data wykonania</th>";
			echo "<th class=center>Status</th>";
			echo "<th class='center hideme'>Uwagi</th>";
			echo "<th class=center>Data zakończenia<br />Zakończone przez</th>";
			echo "<th class='center hideme'>Opcje</th>";
		echo "</tr>";
		//th("40;c;LP|;;UP / Komórka|;;Przypisane osobie|;c;Status|;c;Uwagi|;;Data zakończenia<br />Zakończone przez|;c;Opcje",$es_prawa); 
	} else {
		echo "<tr>";
			echo "<th width=40 class=center>LP</th>";
			echo "<th class=left>UP/Komórka</th>";
			echo "<th class=center>Przypisane osobie</th>";
			if ($count_all_rows33>0) echo "<th class=center>Zaplanowana<br />data wykonania</th>";
			echo "<th class=center>Status</th>";
			echo "<th class='center hideme'>Uwagi</th>";
			echo "<th class='center hideme'>Opcje</th>";
		echo "</tr>";
		//th("40;c;LP|;;UP / Komórka|;;Przypisane osobie|;c;Status|;c;Uwagi|;c;Opcje",$es_prawa);
	}
	$i = 0;
	$j = 1;
	$countall = 0;
	$countwyk = 0;
	
	while ($newArray = mysql_fetch_array($result)) {
		$temp_id			= $newArray['pozycja_id'];
		$temp_komorka		= $newArray['pozycja_komorka'];
		$temp_data_mod		= $newArray['pozycja_data_modyfikacji'];
		$temp_mod_przez 	= $newArray['pozycja_modyfikowane_przez'];
		$temp_status		= $newArray['pozycja_status'];		
		$temp_uwagi			= $newArray['pozycja_uwagi'];
		$temp_przyp_osobie 	= $newArray['pozycja_przypisane_osobie'];
		$n_zgl_id			= $newArray['pozycja_hd_zgloszenie'];
		$temp_z_id 			= $newArray['pozycja_zadanie_id'];
		$temp_data_zapl		= $newArray['pozycja_zaplanowana_data_wykonania'];
		
		tbl_tr_highlight($i);
			echo "<td width=40 class=center>";
				if ($_REQUEST[all]!=9) {
					echo "<span title='$temp_id'"; 
					if ($temp_status!=9) { echo "style='float:left; margin-top:5px;'"; }					
					echo ">";
					echo "<a class=normalfont onClick=\"return false;\" title=' $temp_id ' >$j</a>";
					echo "</span>";
					echo "<span style='float:right; margin-top:1px;'>";					
						if (($temp_status!=9)) {
						
						//	if ($kierownik_nr==$es_nr) {
						//		echo "<input class='border0 hideme' type=checkbox name=markzgl$i id=markzgl$i value=$temp_id onClick=\"UpdateIloscZaznaczen($update_value);\" />";
						//	}
						//	if (($kierownik_nr!=$es_nr) && ($temp_przyp_osobie==$currentuser)) {
							echo "<input class='border0 hideme' type=checkbox name=markzgl$i id=markzgl$i value=$temp_id onClick=\"UpdateIloscZaznaczen($update_value); \" />";
						// }
						}
					echo "</span>";	
				} else {
					//echo "<span style='float:left; margin-top:5px;'>";
					echo "<a href=# class=normalfont title=' $temp_id ' >$j</a>";
					
					//echo "</span>";
				}
			echo "</td>";
			td_(";nw");
				$result9 = mysql_query("SELECT up_id, up_pion_id, up_komorka_macierzysta_id FROM $dbname.serwis_komorki WHERE (up_nazwa='$temp_komorka') and (belongs_to=$es_filia) LIMIT 1", $conn) or die($k_b);
				list($up_id,$temp_pion_id,$temp_komorka_macierzysta)=mysql_fetch_array($result9);
				
				$result9 = mysql_query("SELECT up_id FROM $dbname.serwis_komorki WHERE (up_komorka_macierzysta_id='$up_id') and (belongs_to=$es_filia) and (up_active=1) LIMIT 1", $conn) or die($k_b);
				list($czy_ma_podlegla_placowke)=mysql_fetch_array($result9);
				
				// nazwa pionu z id pionu
				$sql_get_pion = "SELECT pion_nazwa FROM $dbname.serwis_piony WHERE (pion_id='$temp_pion_id') LIMIT 1";
				$wynik_get_pion = mysql_query($sql_get_pion, $conn) or die($k_b);
				$dane_get_pion = mysql_fetch_array($wynik_get_pion);
				$temp_pion_nazwa = $dane_get_pion['pion_nazwa'];
				// koniec ustalania nazwy pionu
	
				echo "<a class=normalfont title=' Szczegółowe informacje o $temp_pion_nazwa $temp_komorka ' onclick=\"newWindow_r(700,600,'p_komorka_szczegoly.php?id=$up_id'); return false;\" href=#><b>";

				if ($czy_ma_podlegla_placowke<=0) echo "<font color=green>";
				if ($temp_komorka_macierzysta>0) echo "<font color=grey>";
				if (($temp_komorka_macierzysta>0) && ($wciecie_dla_podleglych_komorek==1)) echo "&nbsp;&nbsp;&nbsp;&nbsp;";
					echo "$temp_pion_nazwa $temp_komorka";
				if ($temp_komorka_macierzysta>0) echo "</font>";
				if ($czy_ma_podlegla_placowke>0) echo "</font>";
				echo "</b></a>";
				if ($pokazpoczatekuwag==1) pokaz_uwagi($temp_uwagi,$iloscznakowuwag,"newWindow(480,265,'p_zadania_pozycje_uwagi.php?id=$temp_id&nr=$temp_numer'); return false;");
			_td();
			
			if ($temp_status==9) $temp_przyp_osobie = '';
			td(";;".$temp_przyp_osobie."");
			//td(";;".$temp_data_zapl."");
		
		if ($count_all_rows33>0) {
			td_(";c");
				if ($temp_data_zapl!='0000-00-00') {
//					echo "$temp_data_zapl";

					if (($temp_data_zapl<Date('Y-m-d')) && ($temp_data_zapl!='0000-00-00') && ($temp_status!=9)) echo "<font color=red><b>";		
					echo "$temp_data_zapl";
					if (($temp_data_zapl<Date('Y-m-d')) && ($temp_data_zapl!='0000-00-00') && ($temp_status!=9)) echo "</b></font>";				
						
					if ($temp_data_zapl<Date('Y-m-d') && ($temp_status!=9)) echo "<br /><font color=red><b>minął termin wykonania</b></font>";
					if (($temp_data_zapl==Date('Y-m-d')) && ($today=='') && ($temp_status!=9)) echo "<br /><font color=#FF5500><b>termin upływa dzisiaj</b></font>";
						
				} else {
					echo "-";
				}
			_td();
		}
		
			td_(";c");
				if ($temp_status==0) echo "<b>nie wykonane</b>";
				if ($temp_status==1) echo "<b>w trakcie wykonywania</b>";
				if ($temp_status==9) { 
					echo "<a title=' Zadanie zostało wykonane dla $temp_komorka '><input class=imgoption type=image src=img/ok.gif></a>";
					$countwyk++; 
				}
			_td();
			echo "<td class='center hideme'>";
				if ($temp_uwagi!='') {
					echo "<a title=' Czytaj uwagi '><input class=imgoption type=image src=img/comment.gif onclick=\"newWindow(520,300,'p_zadania_pozycje_uwagi.php?id=$temp_id&nr=$temp_numer'); return false;\"></a>";
				}
				$_up = urlencode($temp_pion_nazwa." ".$temp_komorka);
				if ($temp_status!=9) echo "<a title=' Edytuj uwagi '><input class=imgoption type=image src=img/edit_comment.gif onclick=\"newWindow(520,300,'e_zadania_pozycje_uwagi.php?id=$temp_id&komorka=$_up'); return false;\"></a>";		
			_td();	
			if ($temp_status_z!=-1)	{	
				$data_mod=$temp_data_mod;
				if ($temp_data_mod=='0000-00-00 00:00:00') $data_mod=''; 
				td(";;".substr($data_mod,0,16)."<br />".$temp_mod_przez."");
			}
			echo "<td class='center hideme'>";
				$accessLevels = array("9");
				if(array_search($es_prawa, $accessLevels)>-1) {
					if ($temp_status!=9) {
						echo "<a title=' Przypisz wykonanie zadania w $temp_komorka innej osobie '><input class=imgoption type=image src=img/user_edit.gif onclick=\"newWindow(600,400,'e_zadanie_osoba.php?id=$temp_id&osoba=".urlencode($temp_przyp_osobie)."&dosiebie=0'); return false;\"></a>";
					}
				} else {
					// przypisanie do siebie
					if (($temp_status!=9) && ($temp_przyp_osobie!=$currentuser)) {
					
						echo "<a title=' Przypisz sobie wykonanie zadania w $temp_komorka '><input class=imgoption type=image src=img/user_edit.gif onclick=\"newWindow(600,400,'e_zadanie_osoba.php?id=$temp_id&osoba=".urlencode($temp_przyp_osobie)."&dosiebie=".urlencode($currentuser)."'); return false;\"></a>";
						
						//echo "<a title='Przypisz wykonanie zadania w $temp_komorka do siebie'><input class=imgoption type=image src=img/user_edit.gif onclick=\"newWindow(600,200,'e_zadanie_osoba.php?id=$temp_id&osoba=".urlencode($temp_przyp_osobie)."&dosiebie=1&cu=".urlencode($currentuser)."&dosiebie=1'); return false;\"></a>";
					}
				}
				if ($temp_status==0) {
					if (($currentuser==$temp_przyp_osobie) || ($temp_przyp_osobie=='') || ($kierownik==true) || ($es_m==1)) {
						if ($temp_status_z!=-1)	{
						
							$r2 = mysql_query("SELECT zadanie_hd_podkat,zadanie_hd_wc,zadanie_hd_osoba,zadanie_hd_kat,zadanie_hd_podkat_poziom_2 FROM $dbname.serwis_zadania WHERE (zadanie_id='$_REQUEST[id]') LIMIT 1", $conn_hd) or die($k_b);
							list($_podkat, $_wc, $_osoba, $_kat, $_podkat2)=mysql_fetch_array($r2);
							
							$r2 = mysql_query("SELECT hd_podkategoria_opis FROM $dbname_hd.hd_podkategoria WHERE (hd_podkategoria_nr='$_podkat') LIMIT 1", $conn_hd) or die($k_b);
							list($_podkat_opis)=mysql_fetch_array($r2);

							$r2 = mysql_query("SELECT hd_kategoria_opis FROM $dbname_hd.hd_kategoria WHERE (hd_kategoria_nr='$_kat') LIMIT 1", $conn_hd) or die($k_b);
							list($_kat_opis)=mysql_fetch_array($r2);
							
							echo "<a title=' Potwierdź wykonanie zadania w $temp_komorka '><input class=imgoption type=image src=img/snapraw_ok.gif onclick=\"newWindow_r(600,600,'e_zadanie_pozycja.php?id=$temp_id&k=".urlencode($temp_komorka)."&komorka=".urlencode($temp_pion_nazwa." ".$temp_komorka)."&zadanie=".urlencode($temp_opis)."&zid=$temp_z_id&zpodkatnr=$_podkat&zpodkatopis=".urlencode($_podkat_opis)."&osoba=".urlencode($_osoba)."&enablehd=$enableHD&zkatnr=$_kat&zkatopis=".urlencode($_kat_opis)."&zpodkat2=".urlencode($_podkat2)."'); return false;\"></a>";
							
						}
					}
				}
				
				if (($temp_status==9) && ($temp_status_z!=9)) {
					if (($temp_mod_przez==$currentuser) || ($kierownik==true) || ($es_m==1)) {
						echo "<a title=' Anuluj wykonanie pozycji zadania w $temp_komorka '><input class=imgoption type=image src=img/cofnij_wykonanie.gif onclick=\"newWindow_r(600,150,'e_zadanie_pozycja_anuluj.php?id=$temp_id&k=".urlencode($temp_komorka)."&komorka=".urlencode($temp_pion_nazwa." ".$temp_komorka)."&zadanie=".urlencode($temp_opis)."&zid=$temp_z_id&zpodkatnr=$_podkat&zpodkatopis=".urlencode($_podkat_opis)."&osoba=".urlencode($_osoba)."'); return false;\"></a>";
					}
				}

				if(array_search($es_prawa, $accessLevels)>-1) { 
					if (($temp_status_z!=9)) {
						echo "<a title=' Usuń $temp_komorka z listy UP/komórek do wykonania tego zadania '><input class=imgoption type=image src=img/delete.gif  onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_zadanie_pozycja.php?id=$temp_id&k=".urlencode($temp_komorka)."'); return false;\"></a>";	
					}
				}

				// sprawdzenie czy zgłoszenie nie jest ukryte
				$sql331="SELECT * FROM $dbname_hd.hd_zgloszenie WHERE (zgl_nr=$n_zgl_id) and (zgl_widoczne=1)";
				$result331 = mysql_query($sql331, $conn_hd) or die($k_b);
				$n_zgl_id_widoczne = (mysql_num_rows($result331) > 0);
	
				if (($n_zgl_id_widoczne) && ($n_zgl_id>0) && ($temp_status==0)) {
					echo "<hr />";
					echo "Wykonanie tej pozycji zadania<br />jest już powiązane<br />ze zgłoszeniem Helpdesk<br />";
				}

				if (($n_zgl_id_widoczne==false) && ($n_zgl_id>0) && ($temp_status==0)) {
					echo "<hr />";
					echo "Do tej pozycji zadania<br />utworzono zgłoszenie,<br />które zostało ukryte";
				}
				
				if (($n_zgl_id_widoczne) && ($n_zgl_id>0)) {
				
					$LinkHDZglNr=$n_zgl_id; 
					if ($_REQUEST[noback]!='1') include('linktohelpdesk.php');

					$r2 = mysql_query("SELECT zgl_status FROM $dbname_hd.hd_zgloszenie WHERE (zgl_nr='$n_zgl_id') LIMIT 1", $conn_hd) or die($k_b);
					list($status_id)=mysql_fetch_array($r2);
					
					echo "<a class='normalfont' href=# onclick=\"newWindow_r(800,600,'hd_o_zgloszenia.php?action=obsluga&id=$n_zgl_id&nr=$n_zgl_id&zgl_s='); return false;\">";
					
						switch ($status_id) {
						case "1"	: echo "<input title='Nowe' class=imgoption type=image src=img/zgl_nowe.gif>"; break;
						case "2"	: echo "<input title='Przypisane' class=imgoption type=image src=img/zgl_przypisane.gif>"; break;
						case "3"	: echo "<input title='Rozpoczęte' class=imgoption type=image src=img/zgl_rozpoczete.gif>"; break;
						case "3A"	: echo "<input title='W serwisie zewnętrznym' class=imgoption type=image src=img/zgl_w_serwisie_zewnetrznym.gif>"; break;
						case "3B"	: echo "<input title='W firmie' class=imgoption type=image src=img/zgl_w_firmie.gif>"; break;
						case "4"	: echo "<input title='Oczekiwanie na odp. klienta' class=imgoption type=image src=img/zgl_oczekiwanie_na_odpowiedz.gif>"; break;
						case "5"	: echo "<input title='Oczekiwanie na sprzęt' class=imgoption type=image src=img/zgl_oczekiwanie_na_sprzet.gif>"; break;
						case "6"	: echo "<input title='Do Oddania' class=imgoption type=image src=img/zgl_do_oddania.gif>"; break;
						case "7"	: echo "<input title='Rozpoczęte - nie zakończone' class=imgoption type=image src=img/zgl_rozpoczete_nie_zakonczone.gif>"; break;
							//case "8"	: echo "<input class=imgoption type=image src=img/zgl_nowe.gif>"; break;
						case "9"	: echo "<input title='Zamknięte' class=imgoption type=image src=img/zgl_zamkniete.gif>"; break;
						}
						
					echo "</a>";
				
				}
			
			_td();
			$j++;
			$i++;
			$countall++;
			//ob_flush(); flush();
		_tr();
	}
	
if ($temp_status_z!=-1) {	
	$procent_ = ($countwyk/$countall)*100;
	$procent = round_up($procent_, 2);
	startbuttonsarea("center");
	hr();
	if ($all==1) echo "Procent wykonania zadania : <b>$procent%</b> | <b>$countwyk</b> z <b>$countall</b>";
	if ($all==0) echo "Ilość niewykonanych pozycji zadania : <b>$countall</b>";
	if ($all==9) echo "Ilość wykonanych pozycji zadania : <b>$countall</b>";
	if ($all!=99) br();
	echo "Pozostało dni : <b>$pozostalo_dni</b>";
	br();
	hr();
	echo "<span class=hideme>";
	echo "Pokaż :";
	
	//echo "<a class=paging href=$PHP_SELF?id=$id&all=1 title=' Pokaż wszystkie pozycje zadania '>wszystko</a> <a class=paging href=$PHP_SELF?id=$id&all=0 title=' Pokaż tylko niezakończone pozycje  '>niezakończone</a> <a class=paging href=$PHP_SELF?id=$id&all=9 title=' Pokaż tylko pozycje zakończone  '>zakończone</a> <a class=paging href=$PHP_SELF?id=$id&all=99 title=' Pokaż pozycje przypisane do: $currentuser  '>przypisane do mnie</a>";
	
	echo "<select name=pokazywanie ";
	if ($_REQUEST[all]!=1) echo " style='background-color:yellow;' ";
	
	//if ($_REQUEST[all]!='') echo " style='background-color:yellow' ";
	echo " onChange=\"document.location.href=document.addzp.pokazywanie.options[document.addzp.pokazywanie.selectedIndex].value;\" >\n";
	
	echo "<option value='$PHP_SELF?id=$_REQUEST[id]&all=1&dlaosoby=".urlencode($_REQUEST[dlaosoby])."&nadzien=".$_REQUEST[nadzien]."&noback=$_REQUEST[noback]'"; 
	//if ($_REQUEST[all]==1) echo " SELECTED ";
	echo ">domyślny widok</option>\n";
	
	echo "<option value='$PHP_SELF?id=$_REQUEST[id]&all=1&dlaosoby=".urlencode($_REQUEST[dlaosoby])."&nadzien=".$_REQUEST[nadzien]."&noback=$_REQUEST[noback]'"; 
	if ($_REQUEST[all]==1) echo " SELECTED ";
	echo ">wszystko</option>\n";
	echo "<option value='$PHP_SELF?id=$_REQUEST[id]&all=0&dlaosoby=".urlencode($_REQUEST[dlaosoby])."&nadzien=".$_REQUEST[nadzien]."&noback=$_REQUEST[noback]'"; 
	if ($_REQUEST[all]==0) echo " SELECTED ";
	echo ">niezakończone</option>\n";
	echo "<option value='$PHP_SELF?id=$_REQUEST[id]&all=9&dlaosoby=".urlencode($_REQUEST[dlaosoby])."&nadzien=".$_REQUEST[nadzien]."&noback=$_REQUEST[noback]'";
	if ($_REQUEST[all]==9) echo " SELECTED ";
	echo ">zakończone</option>\n";
	echo "<option value='$PHP_SELF?id=$_REQUEST[id]&all=99&dlaosoby=".urlencode($_REQUEST[dlaosoby])."&nadzien=".$_REQUEST[nadzien]."&noback=$_REQUEST[noback]'";
	if ($_REQUEST[all]==99) echo " SELECTED ";
	echo ">przypisane do mnie</option>\n";

	//echo "<option value='$PHP_SELF?id=$_REQUEST[id]&all=0&dlaosoby=BP&nadzien=".$_REQUEST[nadzien]."'";
	//if (($_REQUEST[all]==0) && ($_REQUEST[dlaosoby]=='BP')) echo " SELECTED ";
	//echo ">niezakończone i nieprzypisane</option>\n";
	
	echo "</select>";
	
	$sql5="SELECT DISTINCT(pozycja_przypisane_osobie) FROM $dbname.serwis_zadania_pozycje WHERE (pozycja_zadanie_id=$_REQUEST[id]) and (pozycja_przypisane_osobie<>'') ";
	
	if (($_REQUEST[dlaosoby]!='')) $sql.=" and (pozycja_przypisane_osobie='$_REQUEST[dlaosoby]') ";
	if ($_REQUEST[nadzien]!='') $sql5 .= " and (pozycja_zaplanowana_data_wykonania = '$_REQUEST[nadzien]') ";
	
	$sql5 .= " ORDER BY pozycja_przypisane_osobie ASC";
	 
	$result5=mysql_query($sql5,$conn) or die($k_b);
	$count_rows5 = mysql_num_rows($result5);
	
	if ($count_rows5>0) {
		echo "&nbsp;|&nbsp;Przypisane do: ";	
		echo "<select name=dlaosoby ";
		if ($_REQUEST[dlaosoby]!='') echo " style='background-color:yellow;' ";
		echo " onkeypress='return handleEnter(this, event);' onChange=\"document.location.href=document.addzp.dlaosoby.options[document.addzp.dlaosoby.selectedIndex].value;\" >\n"; 
		echo "<option ";
		if ($_REQUEST[dlaosoby]=='') echo "SELECTED";
		echo " value='$PHP_SELF?id=$_REQUEST[id]&all=$_REQUEST[all]&dlaosoby=&nadzien=$_REQUEST[nadzien]&noback=$_REQUEST[noback]'>pokaż wszystkie pozycje</option>\n";
		
		echo "<option ";
		if ($_REQUEST[dlaosoby]=='BP') echo "SELECTED";
		echo " value='$PHP_SELF?id=$_REQUEST[id]&all=$_REQUEST[all]&dlaosoby=BP&nadzien=$_REQUEST[nadzien]&noback=$_REQUEST[noback]'>- bez przypisania -</option>\n";
		
		while ($newArray44 = mysql_fetch_array($result5)) {
			$temp_iin = $newArray44['pozycja_przypisane_osobie'];			
			echo "<option";
			if ($temp_iin==$_REQUEST[dlaosoby]) echo " SELECTED ";
			echo " value='$PHP_SELF?id=$_REQUEST[id]&all=$_REQUEST[all]&dlaosoby=".urlencode($temp_iin)."&nadzien=$_REQUEST[nadzien]&noback=$_REQUEST[noback]'>$temp_iin</option>\n"; 
		}
		echo "</select>";
	}

	$sql5="SELECT DISTINCT(pozycja_zaplanowana_data_wykonania) FROM $dbname.serwis_zadania_pozycje WHERE (pozycja_zadanie_id=$_REQUEST[id]) ";

	if ($_REQUEST[dlaosoby]=='BP') $_REQUEST[dlaosoby] = '';
	if ($_REQUEST[dlaosoby]!='') $sql5 .= " and (pozycja_przypisane_osobie = '$_REQUEST[dlaosoby]') ";
	//if ($_REQUEST[nadzien]!='') $sql5 .= " and (pozycja_zaplanowana_data_wykonania = '$_REQUEST[nadzien]') ";
	
	$sql5 .= " ORDER BY pozycja_zaplanowana_data_wykonania ASC";
	
	$result5=mysql_query($sql5,$conn) or die($k_b);
	$count_rows5 = mysql_num_rows($result5);
	//echo $sql5;
	
	if ($count_rows5>0) {
		echo "&nbsp;|&nbsp;Zaplanowane na dzień: ";	
		echo "<select name=nadzien ";
		if ($_REQUEST[nadzien]!='') echo " style='background-color:yellow' ";
		echo " onkeypress='return handleEnter(this, event);' onChange=\"document.location.href=document.addzp.nadzien.options[document.addzp.nadzien.selectedIndex].value;\" >\n"; 	
		echo "<option ";
		if ($_REQUEST[nadzien]=='') echo "SELECTED";
		echo " value='$PHP_SELF?id=$_REQUEST[id]&all=$_REQUEST[all]&nadzien=&dlaosoby=".urlencode($_REQUEST[dlaosoby])."&noback=$_REQUEST[noback]'>pokaż wszystkie</option>\n";
		
		echo "<option value='$PHP_SELF?id=$_REQUEST[id]&all=$_REQUEST[all]&nadzien=0000-00-00&dlaosoby=".urlencode($_REQUEST[dlaosoby])."&noback=$_REQUEST[noback]'"; 
		if ($_REQUEST[nadzien]=='0000-00-00') echo " SELECTED ";
		echo ">nie zaplanowane</option>\n";		
		while ($newArray44 = mysql_fetch_array($result5)) {
			$temp_iin = $newArray44['pozycja_zaplanowana_data_wykonania'];
			if ($temp_iin!='0000-00-00') {
				echo "<option";
				if ($temp_iin==$_REQUEST[nadzien]) echo " SELECTED ";
				echo " value='$PHP_SELF?id=$_REQUEST[id]&all=$_REQUEST[all]&dlaosoby=".urlencode($_REQUEST[dlaosoby])."&nadzien=".urlencode($temp_iin)."&noback=$_REQUEST[noback]'>$temp_iin</option>\n"; 
			}
		}
		echo "</select>";
	}
	
	echo "</span>";
	
	endbuttonsarea();
	
	startbuttonsarea("right");
	addclosewithreloadbutton("Zamknij");
	endbuttonsarea();
}

//if ($temp_status_z!=-1) {
	if (($_REQUEST[all]!=9) && ($procent<100)) {
		echo "<tr class=hideme><td colspan=7>";			
			if ($_REQUEST[all]!=1) {
				echo "<span>";
				echo "&nbsp;&nbsp;&nbsp;&nbsp;<input class=imgoption type=image src=img/obsluga_seryjna.png>&nbsp;Zaznaczonych: ";
				echo "<span id=IloscZaznaczonych style='font-weight:bold;'>0</span>";
			
			echo "<span id=przerwa style='display:none'> | Zaznaczone: </span>";
			$accessLevels = array("9");
			if(array_search($es_prawa, $accessLevels)>-1) {
				echo "&nbsp;<input type=button id=UsunZZadania class=buttons style='display:none;font-weight:bold;color:red;' value='Usuń' onClick=\"UsunPozycjeZZadania(0); return false;\" />";
				echo "&nbsp;<input type=button id=PrzypiszDoOsoby class=buttons style='display:none;font-weight:bold;' value='Przypisz do osoby / Ustal datę wykonania' onClick=\"PrzypiszDoSiebie1(0); return false;\" />";
				
				if ($enableHD==1) echo "&nbsp;<input type=button id=PrzypiszWHD class=buttons style='display:none;font-weight:normal;color:green;' value='Utwórz zgłoszenia o statusie przypisane' onClick=\"UtworzPrzypisaneZgloszeniaWHD(); return false;\" />";
				
				//echo "&nbsp;|&nbsp;";
				//if ($enableHD==1) echo "&nbsp;<input type=button id=ZamknijBezHD class=buttons style='display:none;font-weight:normal;color:blue;' value='Zamknij wybrane ( bez generowania zgłoszeń )' onClick=\"ZamknijBezZgloszeniaHD(); return false;\" />";
				echo "&nbsp;<input type=button id=ZamknijBezHD class=buttons style='display:none;font-weight:normal;color:blue;' value='Zamknij wybrane ( bez generowania zgłoszeń )' onClick=\"ZamknijBezZgloszeniaHD(); return false;\" />";
			} else { 
				// przypisanie do siebie
				echo "&nbsp;<input type=button id=PrzypiszDoOsoby class=buttons style='display:none;font-weight:bold;' value='Przypisz do siebie' onClick=\"PrzypiszDoSiebie1(0); return false;\" />";
				
				if ($enableHD==1) echo "&nbsp;<input type=button id=PrzypiszWHD class=buttons style='display:none;font-weight:normal;color:green;' value='Utwórz zgłoszenia o statusie przypisane' onClick=\"UtworzPrzypisaneZgloszeniaWHD(); return false;\" />";
				
				if ($enableHD==1) echo "&nbsp;<input type=button id=ZamknijBezHD class=buttons style='display:none;font-weight:normal;color:blue;' value='Zamknij wybrane ( bez generowania zgłoszeń )' onClick=\"ZamknijBezZgloszeniaHD(); return false;\" />";
			}
			echo "&nbsp;<br />";
			echo "<span id=l style='display:'><br /></span>";
			echo "</span>";
			}
			if ($_REQUEST[all]!=1) {
				echo "<span style='float:left'>";
					echo "Wszystkie: <input class=buttons type=button onClick=\"MarkCheckboxes2('zaznacz'); UpdateIloscZaznaczen($update_value); \" value='Zaznacz'>";
					echo "<input class=buttons type=button onClick=\"MarkCheckboxes2('odznacz'); UpdateIloscZaznaczen($update_value); \" value='Odznacz'>";
					echo "<input class=buttons type=button onClick=\"MarkCheckboxes2('odwroc'); UpdateIloscZaznaczen($update_value); \"value='Odwróć zaznaczenie'>";
					echo "<input type=hidden name=user_prawa id=user_prawa value=$es_prawa>";
				echo "</span>";
			}
		echo "</td></tr>";
	}
//} else echo "<tr><td colspan=6></td></tr>";

	echo "<input type=hidden name=noback id=noback value='$_REQUEST[noback]'>";
	echo "<input type=hidden name=enablehd id=enablehd value='$enableHD'>";
	
	
endtable();
echo "</form>";
$fff = 0;
} else {
	br();
	if ($all!=99) {
		if ($all==0) errorheader("Brak niezakończonych pozycji zadania");
		if ($all==1) errorheader("Brak UP / komórek przypisanych do zadania");
	}
	if ($all==99) errorheader("Brak UP / komórek przypisanych bezpośrednio do osoby: ".$currentuser."");
	$fff = 1;
}
startbuttonsarea("right");
echo "<span style='float:left'>";
if ($fff==1) addbuttons("wstecz");
if ($all!=99) echo "<input type=button class=buttons value='Pokaż wszystkie pozycje zadania' onClick=\"self.location.href='p_zadanie_pozycje.php?id=$_REQUEST[id]&all=1&dlaosoby=".$_REQUEST[dlaosoby]."&nadzien=".$_REQUEST[nadzien]."'; \" />";
//echo "<a title=' Dodaj komórki/UP do zadania $temp_opis '><input class=imgoption type=image src=img/add_pos.gif onclick=\"newWindow_r(800,600,'d_zadanie_pozycje.php?id=$temp_id')\"></a>";
addownlinkbutton("'Dodaj komórki/UP do tego zadania'","button","button","newWindow_r(800,600,'d_zadanie_pozycje.php?id=$_GET[id]')");
echo "</span>";

$r1 = mysql_query("SELECT count(pozycja_id) FROM $dbname.serwis_zadania_pozycje WHERE pozycja_zadanie_id=$_REQUEST[id]", $conn) or die($k_b);
list($countall_)=mysql_fetch_array($r1);
$r1 = mysql_query("SELECT count(pozycja_id) FROM $dbname.serwis_zadania_pozycje WHERE pozycja_zadanie_id=$_REQUEST[id] and (pozycja_status=9)", $conn) or die($k_b);
list($countwyk_)=mysql_fetch_array($r1);
	
	$procent__1 = ($countwyk_/$countall_)*100;
	$procent_ = round_up($procent__1, 2);
	

if ($procent_==100) {
	addownlinkbutton("'Zakończ zadanie'","button","button","newWindow($dialog_window_x,$dialog_window_y,'z_zadanie_status.php?id=$temp_zid&s=9')");
}

if ($fff==0) {
	echo "<form style='display:inline' action=do_xls_htmlexcel_p_zadanie_pozycje.php METHOD=POST target=_blank>";
		echo "<input class=buttons type=submit value='Export do XLS'>";
		echo "<input type=hidden name=id value='$_REQUEST[id]'>";
		echo "<input type=hidden name=all value='$_REQUEST[all]'>";
		
		echo "<input type=hidden name=proc value='$procent'>";
	//	echo "<input type=hidden name=all value='$_REQUEST[all]'>";
	//	echo "<input type=hidden name=all value='$_REQUEST[all]'>";
		
		echo "<input type=hidden name=zname value='$temp_opis'>";
	echo "</form>";
}
	
addclosewithreloadbutton("Zamknij");
endbuttonsarea();
include('body_stop.php'); 
?>

<script>HideWaitingMessage();</script>

</body>
</html>