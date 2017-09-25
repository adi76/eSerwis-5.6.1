<?php
if ($noauto=='Sprzęt poza ewidencją') {	header("Location: ".$linkdostrony."z_naprawy_uszkodzony.php?id=0&auto=0&up_id=$upid&typ_id=$typid");}
if ($noauto=='Inny typ sprzętu z tej lokalizacji') { header("Location: ".$linkdostrony."z_naprawy_przyjmij.php?upid=$_POST[up_id]");}
if ($noauto=='Wybierz od nowa') { header("Location: ".$linkdostrony."z_naprawy_przyjmij.php"); }

include_once('header.php');
?>
<body>
<?php include('body_start.php'); ?>
<div id="tiplayer" style="position:absolute; visibility:hidden; z-index:10000;"></div>
<?php

	$result77 = mysql_query("SELECT rola_nazwa FROM $dbname.serwis_slownik_rola WHERE (rola_id='$_POST[typid]')", $conn) or die($k_b);
	list($rolanazwa)=mysql_fetch_array($result77);

	if ($rolanazwa!='Monitor') {
		$sql = "SELECT * FROM $dbname.serwis_ewidencja WHERE (ewidencja_up_id=$_POST[upid]) and (ewidencja_typ=$_POST[typid]) and (ewidencja_status=9)";
	} else 
		{
			$sql = "SELECT * FROM $dbname.serwis_ewidencja WHERE (ewidencja_up_id=$_POST[upid]) and (ewidencja_monitor_opis<>'') and (ewidencja_status=9)";		
		}
		
	$result = mysql_query($sql, $conn) or die($k_b);
	$count = mysql_num_rows($result);
	
if ($count!=0) {
	pageheader("Sprawny sprzęt dostępny w wybranej lokalizacji");
	$result7 = mysql_query("SELECT serwis_komorki.up_id, serwis_komorki.up_nazwa, serwis_piony.pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE up_id=$_POST[upid]", $conn) or die($k_b);
	list($upid1,$upnazwa,$temp_pion)=mysql_fetch_array($result7);
	startbuttonsarea("center");
	echo "Wybrana lokalizacja : <b>$temp_pion $upnazwa</b><br />";
	echo "Wybrany typ sprzętu : <b>$rolanazwa</b><br />";
	endbuttonsarea();
	starttable();
	th(";;Nazwa sprzętu<br /><sub>Adres IP urządzenia</sub>|;;SN<br />NI|40;c;Uwagi|;c;Opcje",$es_prawa);
	$dddd = Date('Y-m-d');
	$dd = Date('d');
	$mm = Date('m');
	$rr = Date('Y');
	$i = 0;
	
	while ($dane = mysql_fetch_array($result)) {
		$eeid 			= $dane['ewidencja_id'];
		$etyp_id		= $dane['ewidencja_typ'];
		$eup_id			= $dane['ewidencja_up_id'];
		$ekopis			= $dane['ewidencja_komputer_opis'];
		$eksn			= $dane['ewidencja_komputer_sn'];
		$ekni			= $dane['ewidencja_zestaw_ni'];
		$emo			= $dane['ewidencja_monitor_opis'];
		$emsn			= $dane['ewidencja_monitor_sn'];
		$edo			= $dane['ewidencja_drukarka_opis'];
		$edsn			= $dane['ewidencja_drukarka_sn'];
		$edni			= $dane['ewidencja_drukarka_ni'];
		$eu				= $dane['ewidencja_uwagi'];
		$es				= $dane['ewidencja_status'];
		$egwarancja		= $dane['ewidencja_gwarancja_do'];
		$egwarancjakto	= $dane['ewidencja_gwarancja_kto'];		
		$eo_id			= $dane['ewidencja_oprogramowanie'];
		$ekip			= $dane['ewidencja_komputer_ip'];

		tbl_tr_highlight($i);
	  	$i++;
		if (($rolanazwa=='Komputer') || ($rolanazwa=='Serwer') || ($rolanazwa=='Notebook')) 
		{
			td_(";;".$ekopis."<br /><sub>$ekip</sub>");
				if ($pokazpoczatekuwag==1) pokaz_uwagi($eu,$iloscznakowuwag,"newWindow(480,330,'p_ewidencja_uwagi.php?id=$eeid')");
			_td();
			td(";;".$eksn."<br />".$ekni."");
			td_img("40;c");
				if ($eu!='') {
					echo "<a title=' Czytaj uwagi '><input class=imgoption type=image src=img/comment.gif  onclick=\"newWindow(480,330,'p_ewidencja_uwagi.php?id=$eeid')\">";
				}
				echo "<a title=' Edytuj uwagi '><input class=imgoption type=image src=img/edit_comment.gif onclick=\"newWindow(480,260,'e_ewidencja_uwagi.php?id=$eeid')\"></a>";
			_td();
			td_img("100;c");
				echo "<input class=imgoption type=image src=img//napraw.gif title=' Przyjmij wybrany sprzęt do naprawy ' onclick='window.location=\"z_naprawy_uszkodzony_szczecin.php?id=$eeid&ts=k&auto=1&pionnazwa=$temp_pion\"'>";			
				echo "<input class=imgoption type=image src=img/detail.gif title=' Szczegółowe informacje o sprzęcie i oprogramowaniu ' onclick=\"newWindow_r(620,600,'p_ewidencja_szczegoly.php?id=$eeid')\">";
				if (($egwarancja!='0000-00-00') && ($egwarancja>=$dddd)) { 
					echo "<input class=imgoption type=image src=img/gwarancja.gif title=' Gwarancja do $egwarancja ' ";
					if ($egwarancjakto>-1) echo "onclick=\"newWindow(600,250,'p_firma_serwisowa_szczegoly.php?id=$egwarancjakto')\">";
				}
				$nazwa_urzadzenia_=$ekopis;
				$sn_urzadzenia_=$eksn;
				$ni_urzadzenia_=$ekni;
				echo "<input class=imgoption type=image src=img/print.gif title=' Generuj protokół dla wybranego sprzętu ' onclick=\"newWindow_r(700,595,'utworz_protokol.php?dzien=$dd&miesiac=$mm&rok=$rr&c_1=&c_2=&c_3=&c_4=&c_5=&c_6=&c_7=&c_8=&up=".urlencode($upnazwa)."&nazwa_urzadzenia=".urlencode($nazwa_urzadzenia_)."&sn_urzadzenia=".urlencode($sn_urzadzenia_)."&ni_urzadzenia=".urlencode($ni_urzadzenia_)."&opis_urzadzenia=&wykonane_czynnosci=&uwagi=&imieinazwisko=&readonly=0&upid=$eup_id&findpion=1')\">"; 
			_td();
		} else if ($rolanazwa=='Drukarka') 
				{
					td_(";;".$edo."<br /><sub>$ekip</sub>");
						if ($pokazpoczatekuwag==1) pokaz_uwagi($eu,$iloscznakowuwag,"newWindow(480,330,'p_ewidencja_uwagi.php?id=$eeid')");
					_td();
					td(";;".$edsn."<br />".$edni."");
					td_img("40;c");
						if ($eu!='') {
							echo "<a title=' Czytaj uwagi '><input class=imgoption type=image src=img/comment.gif  onclick=\"newWindow(480,330,'p_ewidencja_uwagi.php?id=$eeid')\">";
						}
						echo "<a title=' Edytuj uwagi '><input class=imgoption type=image src=img/edit_comment.gif onclick=\"newWindow(480,260,'e_ewidencja_uwagi.php?id=$eeid')\"></a>";
					_td();
					td_img("100;c");
						echo "<input class=imgoption type=image src=img//napraw.gif title=' Przyjmij wybrany sprzęt do naprawy ' onclick='window.location=\"z_naprawy_uszkodzony_szczecin.php?id=$eeid&ts=d&auto=1&pionnazwa=$temp_pion\"'>";			
						echo "<input class=imgoption type=image src=img/detail.gif title=' Szczegółowe informacje o sprzęcie ' onclick=\"newWindow_r(620,600,'p_ewidencja_szczegoly.php?id=$eeid')\">";
						if (($egwarancja!='0000-00-00') && ($egwarancja>=$dddd)) { 
							echo "<input class=imgoption type=image src=img/gwarancja.gif title=' Gwarancja do $egwarancja ' ";
							if ($egwarancjakto>-1) echo "onclick=\"newWindow(600,250,'p_firma_serwisowa_szczegoly.php?id=$egwarancjakto')\">";
						}
						$nazwa_urzadzenia_=$edo;
						$sn_urzadzenia_=$edsn;
						$ni_urzadzenia_=$edni;
						echo "<input class=imgoption type=image src=img/print.gif title=' Generuj protokół dla wybranego sprzętu ' onclick=\"newWindow_r(700,595,'utworz_protokol.php?dzien=$dd&miesiac=$mm&rok=$rr&c_1=&c_2=&c_3=&c_4=&c_5=&c_6=&c_7=&c_8=&up=".urlencode($upnazwa)."&nazwa_urzadzenia=".urlencode($nazwa_urzadzenia_)."&sn_urzadzenia=".urlencode($sn_urzadzenia_)."&ni_urzadzenia=".urlencode($ni_urzadzenia_)."&opis_urzadzenia=&wykonane_czynnosci=&uwagi=&imieinazwisko=&readonly=0&upid=$eup_id&findpion=1')\">"; 
					_td();				
				} else if ($rolanazwa=='Monitor')
					{
						td_(";;".$emo."");
							if ($pokazpoczatekuwag==1) pokaz_uwagi($eu,$iloscznakowuwag,"newWindow(480,330,'p_ewidencja_uwagi.php?id=$eeid')");
						_td();
						td(";;".$emsn."<br />".$emni."");
						td_img("40;c");
							if ($eu!='') {
								echo "<a title=' Czytaj uwagi '><input class=imgoption type=image src=img/comment.gif  onclick=\"newWindow(480,330,'p_ewidencja_uwagi.php?id=$eeid')\">";
							}
							echo "<a title=' Edytuj uwagi '><input class=imgoption type=image src=img/edit_comment.gif onclick=\"newWindow(480,260,'e_ewidencja_uwagi.php?id=$eeid')\"></a>";
						_td();
						td_img("100;c");
							echo "<input class=imgoption type=image src=img//napraw.gif title=' Przyjmij wybrany sprzęt do naprawy ' onclick='window.location=\"z_naprawy_uszkodzony_szczecin.php?id=$eeid&ts=m&auto=1&pionnazwa=$temp_pion\"'>";			
							echo "<input class=imgoption type=image src=img/detail.gif title=' Szczegółowe informacje o sprzęcie ' onclick=\"newWindow_r(620,600,'p_ewidencja_szczegoly.php?id=$eeid')\">";
							if (($egwarancja!='0000-00-00') && ($egwarancja>=$dddd)) { 
								echo "<input class=imgoption type=image src=img/gwarancja.gif title=' Gwarancja do $egwarancja ' ";
								if ($egwarancjakto>-1) echo "onclick=\"newWindow(600,250,'p_firma_serwisowa_szczegoly.php?id=$egwarancjakto')\">";
							}
							$nazwa_urzadzenia_=$emo;
							$sn_urzadzenia_=$emsn;
							$ni_urzadzenia_=$ekni;
							echo "<input class=imgoption type=image src=img/print.gif title=' Generuj protokół dla wybranego sprzętu ' onclick=\"newWindow_r(700,595,'utworz_protokol.php?dzien=$dd&miesiac=$mm&rok=$rr&c_1=&c_2=&c_3=&c_4=&c_5=&c_6=&c_7=&c_8=&up=".urlencode($upnazwa)."&nazwa_urzadzenia=".urlencode($nazwa_urzadzenia_)."&sn_urzadzenia=".urlencode($sn_urzadzenia_)."&ni_urzadzenia=".urlencode($ni_urzadzenia_)."&opis_urzadzenia=&wykonane_czynnosci=&uwagi=&imieinazwisko=&readonly=0&upid=$eup_id&findpion=1')\">"; 
						_td();										
					} else 
					{
						td(";;;|;;;");
						td_img("40;c");
							if ($eu!='') {
								echo "<a title=' Czytaj uwagi '><input class=imgoption type=image src=img/comment.gif  onclick=\"newWindow(480,330,'p_ewidencja_uwagi.php?id=$eeid')\">";
							}
							echo "<a title=' Edytuj uwagi '><input class=imgoption type=image src=img/edit_comment.gif onclick=\"newWindow(480,260,'e_ewidencja_uwagi.php?id=$eeid')\"></a>";
						_td();
						td_img("100;c");
							echo "<input class=imgoption type=image src=img//napraw.gif title=' Przyjmij wybrany sprzęt do naprawy ' onclick='window.location=\"z_naprawy_uszkodzony_szczecin.php?id=$eeid&ts=k&auto=1&pionnazwa=$temp_pion\"'>";			
							echo "<input class=imgoption type=image src=img/detail.gif title=' Szczegółowe informacje o sprzęcie ' onclick=\"newWindow_r(620,600,'p_ewidencja_szczegoly.php?id=$eeid')\">";
							if (($egwarancja!='0000-00-00') && ($egwarancja>=$dddd)) { 
								echo "<input class=imgoption type=image src=img/gwarancja.gif title=' Gwarancja do $egwarancja ' ";
								if ($egwarancjakto>-1) echo "onclick=\"newWindow(600,250,'p_firma_serwisowa_szczegoly.php?id=$egwarancjakto')\">";
							}
							echo "<input class=imgoption type=image src=img/print.gif title=' Generuj protokół dla wybranego sprzętu ' onclick=\"newWindow_r(700,595,'utworz_protokol.php?dzien=$dd&miesiac=$mm&rok=$rr&c_1=&c_2=&c_3=&c_4=&c_5=&c_6=&c_7=&c_8=&up=".urlencode($upnazwa)."&nazwa_urzadzenia=".urlencode($nazwa_urzadzenia_)."&sn_urzadzenia=".urlencode($sn_urzadzenia_)."&ni_urzadzenia=".urlencode($ni_urzadzenia_)."&opis_urzadzenia=&wykonane_czynnosci=&uwagi=&imieinazwisko=&readonly=0&upid=$eup_id&findpion=1')\">"; 
						_td();
					}
		_tr();
	}
endtable();
} else errorheader("Brak takiego sprzętu w wybranej lokalizacji");

echo "<form name=addt action=$PHP_SELF method=POST>";
echo "<input type=hidden name=up_id value=$_POST[upid]>";	
	startbuttonsarea("right");
	addownlinkbutton2("'Wybierz od nowa'","noauto","submit","z_naprawy_uszkodzony_szczecin.php?id=0&auto=0");
	addownlinkbutton2("'Inny typ sprzętu z tej lokalizacji'","noauto","submit","z_naprawy_uszkodzony_szczecin.php?id=0&auto=0");
	addbuttons("anuluj");
	endbuttonsarea();
_form();
include('body_stop.php'); 
?>
</body>
</html>