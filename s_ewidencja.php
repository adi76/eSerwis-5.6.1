<?php

?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();	
starttable();
echo "<tr><th colspan=3></th>";
if ($view=='simple') {
echo "<th colspan=1>Komputer</th>";
} else echo "<th colspan=3>Komputer</th>";

echo "<th>Monitor</th>";

echo "<th></th>";

if ($view=='simple') { 
echo "<th colspan=1>Drukarka</th>";
} else echo "<th colspan=2>Drukarka</th>";

if ($view!='simple') {
echo "<th></th>";
}	

	if ($printpreview==0) {

echo "<th colspan=3></th>";

	}
echo "</tr>";

	
echo "<th>LP</th>";
echo "<th align=center>Rodzaj";

echo "</th>";

if ($view=='simple') {
echo "<th>Lokalizacja</th>";
} else echo "<th>Lokalizacja / nr pokoju<br />Użytkownik</th>";
 // komputer	
if ($view=='simple') { 		
echo "<th>Model</th>";
} else echo "<th>Model<br />SN</th>";
			
if ($view!='simple') { 	
echo "<th>Nazwa<br />Adres IP</th>";
echo "<th>Endpoint</th>";
}		
 // monitor
echo "<th>Model";
if ($view!='simple') { 
echo "<br />SN";
}
echo "</th>";

echo "<th>NI zestawu</th>";
	
 // drukarka
echo "<th>Model<br /><sub>Adres IP drukarki</sub></th>";

if ($view!='simple') { 
echo "<th>SN<br />NI</th>";
}
// konfiguracja
if ($view!='simple') { 	
echo "<th>Konfiguracja sprzętu</th>";
}		

if ($printpreview==0) {
	
echo "<th>Uwagi</th>";
			//	echo "<th aling=center>Status</th>";

// -
// access control 
$accessLevels = array("0","1","9");
if(array_search($es_prawa, $accessLevels)>-1){
echo "<th>Opcje</th>";
}
// access control koniec
// -




}

echo "</tr>";

$i = 0;

while ($dane = mysql_fetch_array($result)) {



$eid 		= $dane['ewidencja_id'];
$etyp_id	= $dane['ewidencja_typ'];
$eup_id		= $dane['ewidencja_up_id'];
$euser		= $dane['ewidencja_uzytkownik'];	
$enrpok		= $dane['ewidencja_nr_pokoju'];
$enizest	= $dane['ewidencja_zestaw_ni'];
$eknazwa	= $dane['ewidencja_komputer_nazwa'];
$ekopis		= $dane['ewidencja_komputer_opis'];
$eksn		= $dane['ewidencja_komputer_sn'];
$ekip		= $dane['ewidencja_komputer_ip'];
$eke		= $dane['ewidencja_komputer_endpoint'];
$emo		= $dane['ewidencja_monitor_opis'];
$emsn		= $dane['ewidencja_monitor_sn'];
$edo		= $dane['ewidencja_drukarka_opis'];
$edsn		= $dane['ewidencja_drukarka_sn'];
$edni		= $dane['ewidencja_drukarka_ni'];
$eu			= $dane['ewidencja_uwagi'];
$es			= $dane['ewidencja_status'];
$eo_id		= $dane['ewidencja_oprogramowanie'];
$emoduser	= $dane['ewidencja_modyfikacja_user'];
$emoddata	= $dane['ewidencja_modyfikacja_date'];
$ekonf		= $dane['ewidencja_konfiguracja'];
$egwarancja	= $dane['ewidencja_gwarancja_do'];
$egwarancjakto	= $dane['ewidencja_gwarancja_kto'];		
$drukarkapow	= $dane['ewidencja_drukarka_powiaz_z'];

tbl_tr_highlight($i);

$i+=1;

echo "<td width=30 class=center>$i</td>";

$sql77="SELECT rola_nazwa FROM $dbname.serwis_slownik_rola WHERE rola_id='$etyp_id'";
$result77 = mysql_query($sql77, $conn) or die($k_b);

while ($newArray77 = mysql_fetch_array($result77))
{
$rolanazwa	= $newArray77['rola_nazwa'];
}
echo "<td nowrap>";

$ok=1;
$display='gt';

if ($printpreview==0) { 

if (($display=='g') || ($display=='gt')) 
{
if ($rolanazwa=="Komputer") { echo "<img class=imgoption src=img/komputer.gif border=0 align=absmiddle title=' Komputer ' width=16 width=16>"; $ok=1; }
if ($rolanazwa=="Serwer") { echo "<img class=imgoption src=img/serwer.gif border=0 align=absmiddle title=' Serwer ' width=16 width=16>"; $ok=1; }
if ($rolanazwa=="Drukarka") { echo "<img class=imgoption src=img/drukarka.gif border=0 align=absmiddle title=' Drukarka ' width=16 width=16>"; $ok=1; }
if ($rolanazwa=="Router") { echo "<img class=imgoption src=img//router.gif border=0 align=absmiddle title=' Router ' width=16 width=16>"; $ok=1; }
if ($rolanazwa=="Switch") { echo "<img class=imgoption src=img/switch.gif border=0 align=absmiddle title=' Switch ' width=16 width=16>"; $ok=1; }
if ($rolanazwa=="Notebook") { echo "<img class=imgoption src=img//notebook.gif border=0 align=absmiddle title=' Notebook ' width=16 width=16>"; $ok=1; }

}
}
if (($ok==0) || ($display=='gt')) echo "&nbsp;".highlight($rolanazwa, $search)."";
if ($display=='t') echo "&nbsp;".highlight($rolanazawa, $search)."";
echo "</td>";

$sql7="SELECT * FROM $dbname.serwis_komorki WHERE up_id='$eup_id'";
$result7 = mysql_query($sql7, $conn) or die($k_b);

while ($newArray7 = mysql_fetch_array($result7))
{
$upid_		= $newArray7['up_id'];
$upnazwa		= $newArray7['up_nazwa'];
$uptel		= $newArray7['up_telefon'];
$upsiec		= $newArray7['up_ip'];
$uppionid	= $newArray7['up_pion_id'];
}

	// nazwa pionu z id pionu
	$sql_get_pion = "SELECT pion_nazwa FROM $dbname.serwis_piony WHERE (pion_id='$uppionid') LIMIT 1";
	$wynik_get_pion = mysql_query($sql_get_pion, $conn) or die($k_b);
	$dane_get_pion = mysql_fetch_array($wynik_get_pion);
	$temp_pion_nazwa = $dane_get_pion['pion_nazwa'];
	// koniec ustalania nazwy pionu
	
echo "<td>";

echo "".highlight($temp_pion_nazwa." ".$upnazwa, $search)."";
if ($view!='simple') { 

if ($enrpok!='') {
echo "&nbsp;/&nbsp;$enrpok";
}

if ($euser!='') {
echo "<br />".highlight($euser, $search)."";
}
}

echo "</td>";
echo "<td>".highlight($ekopis, $search)."";

if ($view!='simple') { 
echo "<br />".highlight($eksn, $search)."";
}	
echo "</td>";
echo "</a>";	

if ($view!='simple') { 
if ($rolanazwa!='Drukarka') {
	echo "<td>".highlight($eknazwa, $search)."<br />".highlight($ekip, $search)."</td>";
} else {
	echo "<td></td>";
}
echo "<td class=center>";

if ($ekip!='') {
echo "<a title=' Sprawdź czy działa endpoint na komputerze o adresie IP : $ekip ' class=endpoint_s title=' Sprawdź czy działa ten endpoint ' onclick=\"newWindow_r(800,600,'p_endpoint.php?ip=$ekip')\">".highlight($eke, $search)."</a>";
} else echo "&nbsp;";

echo "</td>";
}
	
echo "<td>".highlight($emo, $search)."";
if ($view!='simple') { 
echo "<br />".highlight($emsn, $search)."";
}
echo "</td>";

echo "<td>".highlight($enizest, $search)."</td>";

$dd = Date('d');
$mm = Date('m');
$rr = Date('Y');
$nazwa_urzadzenia_=$edo;
$sn_urzadzenia_=$edsn;
$ni_urzadzenia_=$edni;

			// sprawdzenie czy drukarka jest podpiętą do jakiegoś komputera
			if ($rolanazwa=='Drukarka') 
			{
				$sql_d = "SELECT ewidencja_komputer_opis, ewidencja_komputer_ip, ewidencja_drukarka_opis, ewidencja_drukarka_sn FROM $dbname.serwis_ewidencja WHERE ewidencja_id=$drukarkapow LIMIT 1";

				$result_d = mysql_query($sql_d, $conn) or die($k_b);
				$count_rows = mysql_num_rows($result_d);

				if ($count_rows>0)
					{
						$info_1 = mysql_fetch_array($result_d);	
						$nazwa_k	= $info_1['ewidencja_komputer_opis'];
						$ip_k		= $info_1['ewidencja_komputer_ip'];
					}
			// ====
			} 
			else
			{
			// sprawdzenie czy komputer ma podpiętą drukarkę

			$sql_d = "SELECT ewidencja_drukarka_opis, ewidencja_drukarka_sn FROM $dbname.serwis_ewidencja WHERE ewidencja_drukarka_powiaz_z=$eid LIMIT 1";

			$result_d = mysql_query($sql_d, $conn) or die($k_b);
			$count_rows_k = mysql_num_rows($result_d);

			if ($count_rows_k>0)
				{
					$info_1 = mysql_fetch_array($result_d);	
					$nazwa_d	= $info_1['ewidencja_drukarka_opis'];
					$sn_d		= $info_1['ewidencja_drukarka_sn'];
				}
			// ====
			}
				

echo "<td>"; //$edo</td>";

				if ($drukarkapow>0) { echo "<a title=' Drukarka $edo jest powiązana z komputerem. Pokaż szczegóły '>"; }				
				//echo "$edo";
				$nazwa_urzadzenia_=$edo;
				$sn_urzadzenia_=$edsn;
				$ni_urzadzenia_=$edni;
				$dd = Date('d');
				$mm = Date('m');
				$rr = Date('Y');

				if (($drukarkapow>0) and ($rolanazwa=='Drukarka')) 
				{ 
					echo "<a title=' Drukarka jest podłączona do komputera $nazwa_k ($ip_k) '>"; 
					echo "".highlight($edo, $search)."";
					if (($ekip!='') && ($rolanazwa=='Drukarka')) echo "<br /><sub>".highlight($ekip,$search)."</sub>";
				} else
					{
						echo "".highlight($edo, $search)."";
						
						if ($count_rows_k>0)
						{
							echo "<a title=' Do tego komputera podłączona jest drukarka $nazwa_d ($sn_d) '>";
							echo "<input class=imgoption type=image align=absmiddle src=img/link.gif onclick=\"newWindow_r(620,600,'p_ewidencja_szczegoly.php?id=$eid&ew_action=$ew_action')\"></a>";
							if (($ekip!='') && ($rolanazwa=='Drukarka')) echo "<br /><sub>".highlight($ekip,$search)."</sub>";
						}
					}

				if ($drukarkapow>0) 
				{
					echo "<input class=imgoption type=image align=absmiddle src=img/link.gif onclick=\"newWindow_r(620,600,'p_ewidencja_szczegoly.php?id=$eid&ew_action=$ew_action')\">";
				}

				echo "</a>";

echo "</td>";

if ($view!='simple') { 
echo "<td>".highlight($edsn, $search)."<br />".highlight($edni, $search)."</td>";
}

if ($view!='simple') { 

echo "<td class=nowrap>"; 
if ($ekonf=='0') { echo "&nbsp;"; } else echo "$ekonf";
echo "</td>";
	
//	echo "<td>$ekonf</td>";
}

if ($printpreview==0) {

$uwagisa = ($eu!='');
echo "<td class=center>";
if ($uwagisa=='1') {
	echo "<a title=' Czytaj uwagi '><input class=imgoption type=image src=img/comment.gif  onclick=\"newWindow(480,265,'p_ewidencja_uwagi.php?id=$eid')\"></a>";
}
echo "<a title=' Edytuj uwagi '><input class=imgoption type=image src=img/edit_comment.gif onclick=\"newWindow(480,260,'e_ewidencja_uwagi.php?id=$eid')\"></a>";

$dddd = Date('Y-m-d H:i:s');

if (($printpreview==0) && ($egwarancja!='0000-00-00 00:00:00') && ($egwarancja>=$dddd)) { 
echo "<a title=' Gwarancja do $egwarancja '><img class=imgoption src=img/gwarancja.gif border=0 width=16 width=16></a>";
}	

echo "</td>";
/*
if ($es=='-1') echo "<td align=center></td>";
if ($es=='0') echo "<td align=center></td>";
if ($es=='1') echo "<td align=center><a>OK</a></td>";
if ($es=='2') echo "<td align=center><a>S</a></td>";
if ($es=='3') echo "<td align=center><a>L</a></td>";
*/}
echo "<td class=center>";


if ($ew_action=='move') {
echo "<a title=' Przesunięcie sprzętu '><input class=imgoption type=image src=img/przesuniecie.gif  onclick=\"newWindow_r(700,586,'ew_przesuniecie.php?id=$eid')\"></a>";
}

if ($ew_action=='change') {
echo "<a title=' Remont sprzętu '><input class=imgoption type=image src=img//remont.gif  onclick=\"newWindow_r(700,586,'ew_remont.php?id=$eid')\"></a>";
}

if ($ew_action=='delete') {
echo "<a title=' Usunięcie sprzętu z ewidencji '><input class=imgoption type=image src=img/likwidacja.gif  onclick=\"newWindow_r(700,586,'ew_usuniecie.php?id=$eid')\"></a>";
}

if (($ew_action!='move') && ($ew_action!='delete') && ($ew_action!='change')) {
// -
// access control 
$accessLevels = array("0","1","9");
if(array_search($es_prawa, $accessLevels)>-1){
echo "<a title=' Popraw dane o wybranym sprzęcie '><input class=imgoption type=image src=img/edit.gif  onclick=\"newWindow_r(820,600,'e_ewidencja.php?id=$eid')\"></a>";
}
// access control koniec
// -
// -
// access control 
$accessLevels = array("0","1","9");
if(array_search($es_prawa, $accessLevels)>-1){
echo "<a title='Popraw wszystkie dane o wybranym sprzęcie (f-cja tylko dla Administratorów)'><input class=imgoption type=image src=img/edita.gif  onclick=\"newWindow_r(820,600,'e_ewidencja.php?id=$eid&edittype=admin')\"></a>";
}

// access control koniec
// -

if ($es==-1) { echo "<a title=' Sprzęt pobrany od klienta ' href=main.php?action=npus&id=$mid&cs=$es><img class=imgoption src=img/snapraw_p.gif border=0 width=16 width=16></a>"; }
if ($es==0) { echo "<a title=' Sprzęt jest naprawiany we własnym zakresie ' href=main.php?action=npus&id=$mid&cs=$es><img class=imgoption src=img/w_naprawie_wz.gif border=0 width=16 width=16></a>"; }
if ($es==1) { echo "<a title=' Sprzęt jest w naprawie w serwisie zewnętrznym ' href=main.php?action=npswsz&id=$mid&cs=$es><img class=imgoption src=img/w_naprawie_sz.gif border=0 width=16 width=16></a>"; }
if ($es==2) { echo "<a title=' Sprzęt jest w naprawie w serwisie lokalnym ' href=main.php?action=npswsz&id=$mid&cs=$es><img class=imgoption src=img/w_naprawie_sl.gif border=0 width=16 width=16></a>"; }
if ($es==3) { echo "<a title=' Sprzęt wrócił z naprawy ' href=p_naprawy_zakonczone.php?id=$eid&cs=$es><img class=imgoption src=img/snapraw_ok.gif border=0 width=16 width=16></a>"; }

// -
// access control 
$accessLevels = array("0","1","9");
if(array_search($es_prawa, $accessLevels)>-1){
 if (($es==3) || ($es==1)) { echo "<a title=' Usuń sprzęt z ewidencji (nierejestrowane) '><input class=imgoption type=image src=img/delete.gif onclick=\"newWindow(600,200,'u_ewidencja.php?id=$eid')\"></a>"; }

if (($rolanazwa=="Komputer") || ($rolanazwa=="Serwer") || ($rolanazwa=="Notebook")) {
$wynik_czyjest = mysql_num_rows(mysql_query("SELECT oprogramowanie_id FROM $dbname.serwis_oprogramowanie WHERE oprogramowanie_ewidencja_id=$eid",$conn));
if ($wynik_czyjest>0) { echo "<a title=' Pokaż oprogramowanie zainstalowane na tym sprzęcie '><input class=imgoption type=image src=img/software.gif onclick=\"newWindow_r(600,600,'p_oprogramowanie.php?id=$eid')\"></a>";
} else {
echo "<a title=' Pokaż oprogramowanie zainstalowane na tym sprzęcie '><input class=imgoption type=image src=img/software_none.gif onclick=\"newWindow_r(600,600,'p_oprogramowanie.php?id=$eid')\"></a>";
}
}

echo "<a title=' Szczegółowe informacje o sprzęcie i oprogramowaniu '><input class=imgoption type=image src=img/detail.gif onclick=\"newWindow_r(620,600,'p_ewidencja_szczegoly.php?id=$eid')\"></a>";

$dd = Date('d');
$mm = Date('m');
$rr = Date('Y');

if (($rolanazwa=="Komputer") || ($rolanazwa=="Serwer") || ($rolanazwa=="Notebook")) {

	$nazwa_urzadzenia_=$ekopis;
	$sn_urzadzenia_=$eksn;
	$ni_urzadzenia_=$enizest;

}

//if ($rolanazwa!="Drukarka") echo "<a title=' Generuj protokół dla wybranego sprzętu '><input class=imgoption type=image src=img/print.gif onclick=\"newWindow_r(700,595,'utworz_protokol.php?dzien=$dd&miesiac=$mm&rok=$rr&c_1=&c_2=&c_3=&c_4=&c_5=&c_6=&c_7=&c_8=&up=".urlencode($upnazwa)."&nazwa_urzadzenia=".urlencode($nazwa_urzadzenia_)."&sn_urzadzenia=".urlencode($sn_urzadzenia_)."&ni_urzadzenia=".urlencode($ni_urzadzenia_)."&opis_urzadzenia=&wykonane_czynnosci=&uwagi=&imieinazwisko=&readonly=0&upid=$eup_id&findpion=1')\"></a>"; 

//if (($edo!='') && ($rolanazwa=="Drukarka")) { echo "<a title=' Generuj protokół dla drukarki $nazwa_urzadzenia_ o numerze seryjnym $sn_urzadzenia_ '><input class=imgoption type=image src=img/print.gif onclick=\"newWindow_r(700,595,'utworz_protokol.php?dzien=$dd&miesiac=$mm&rok=$rr&c_1=&c_2=&c_3=&c_4=&c_5=&c_6=&c_7=&c_8=&up=".urlencode($upnazwa)."&nazwa_urzadzenia=".urlencode($nazwa_urzadzenia_)."&sn_urzadzenia=".urlencode($sn_urzadzenia_)."&ni_urzadzenia=".urlencode($ni_urzadzenia_)."&opis_urzadzenia=&wykonane_czynnosci=&uwagi=&imieinazwisko=&readonly=0&upid=$eup_id&findpion=1')\"></a>"; 
}
}

echo "</td>";

	}
echo "</tr>";

endtable();
?>

<script>HideWaitingMessage();</script>

<?php 
?>