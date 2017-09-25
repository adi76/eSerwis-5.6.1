<?php
/*
if ($noauto=='Sprzęt poza ewidencją') {	header("Location: ".$linkdostrony."z_naprawy_uszkodzony.php?id=0&auto=0&up_id=$upid&typ_id=$typid");}
if ($noauto=='Inny typ sprzętu z tej lokalizacji') { header("Location: ".$linkdostrony."z_naprawy_przyjmij.php?upid=$_POST[up_id]&new_upid=$new_upid");}
if ($noauto=='Wybierz od nowa') { header("Location: ".$linkdostrony."z_naprawy_przyjmij.php"); }
*/
include_once('header.php');
include_once('cfg_helpdesk.php'); 
?>
<body>
<?php include('body_start.php'); ?>
<div id="tiplayer" style="position:absolute; visibility:hidden; z-index:10000;"></div>
<?php
	$result77 = mysql_query("SELECT rola_nazwa FROM $dbname.serwis_slownik_rola WHERE (rola_id='$_POST[typid]')", $conn) or die($k_b);
	list($rolanazwa)=mysql_fetch_array($result77);
	
	if ($rolanazwa!='Monitor') {
		$sql = "SELECT * FROM $dbname.serwis_ewidencja WHERE (ewidencja_up_id=$_POST[new_upid]) and (ewidencja_typ=$_POST[typid])";
	} else 
		{
			$sql = "SELECT * FROM $dbname.serwis_ewidencja WHERE (ewidencja_up_id=$_POST[new_upid]) and (ewidencja_monitor_opis<>'') and (ewidencja_status=9)";		
		}

	//echo "$sql";
	
	$result = mysql_query($sql, $conn) or die($k_b);
	$count = mysql_num_rows($result);

//$_SESSION['naprawa_id_for_zgloszenie_nr_'.$_REQUEST[hd_nr].'']=0;
	
if ($count!=0) {
	
	$result7 = mysql_query("SELECT serwis_komorki.up_id, serwis_komorki.up_nazwa, serwis_piony.pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (up_id=$_POST[new_upid]) and (serwis_komorki.up_pion_id=serwis_piony.pion_id)", $conn) or die($k_b);
	
	list($upid1,$upnazwa,$temp_pion)=mysql_fetch_array($result7);
	pageheader("Sprzęt dostępny w wybranej lokalizacji<br /><br />Wybrana lokalizacja : <b>$temp_pion $upnazwa</b><br />Wybrany typ sprzętu : <b>$rolanazwa</b><br />");
	//startbuttonsarea("center");
	//echo "Wybrana lokalizacja : <b>$temp_pion $upnazwa</b><br />";
	//echo "Wybrany typ sprzętu : <b>$rolanazwa</b><br />";
	$nazwaup = $temp_pion." ".$upnazwa;
	//endbuttonsarea();
	
	if ($Podst_Inf_o_zgl) include_once('hd_inf_podstawowe.php');
	
	starttable();
	th(";;Nazwa sprzętu<br /><sub>Adres IP urządzenia</sub>|;;SN<br />NI|;c;Uwagi|;c;Opcje",$es_prawa);
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
		$eknazwa		= $dane['ewidencja_komputer_nazwa'];		
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
		
		//echo "SELECT naprawa_id FROM $dbname.serwis_naprawa WHERE (naprawa_ew_id=$eeid) and (naprawa_status<>'5')";
	//	$result111 = mysql_query("SELECT naprawa_id,naprawa_status FROM $dbname.serwis_naprawa WHERE (naprawa_ew_id=$eeid) and (naprawa_status<>'5')", $conn) or die($k_b);
	//	$czy_w_naprawie = mysql_num_rows($result111);
		
		$r40 = mysql_query("SELECT naprawa_id,naprawa_status FROM $dbname.serwis_naprawa WHERE (naprawa_ew_id=$eeid) and (naprawa_status<>'5')", $conn) or die($k_b);
		list($czy_w_naprawie,$jakistatus)=mysql_fetch_array($r40);
		
		$jakistatus = $es;
		
		if (($rolanazwa=='Komputer') || ($rolanazwa=='Serwer') || ($rolanazwa=='Notebook')) {

			td_(";;".$ekopis."<br /><sub>$ekip</sub>");
				if ($pokazpoczatekuwag==1) pokaz_uwagi($eu,$iloscznakowuwag,"newWindow(480,330,'p_ewidencja_uwagi.php?id=$eeid')");
			_td();
			td(";;".$eksn."<br />".$ekni."");
			td_img(";c");
				if ($eu!='') {
					echo "<a title=' Czytaj uwagi '><input class=imgoption type=image src=img/comment.gif  onclick=\"newWindow(480,330,'p_ewidencja_uwagi.php?id=$eeid')\">";
				}
				echo "<a title=' Edytuj uwagi '><input class=imgoption type=image src=img/edit_comment.gif onclick=\"newWindow(480,260,'e_ewidencja_uwagi.php?id=$eeid')\"></a>";
			_td();
			td_img(";c");

				if (($czy_w_naprawie>0) && ($jakistatus!=9)) { 
				//echo $jakistatus;
							
					if ($jakistatus=='-1') {
						echo "<a title=' Sprzęt pobrany od klienta ' href=# "; 
						//if ($_REQUEST[from]=='hd') echo " onClick=\"opener.location.href='main.php?action=npus'\">"; 
						echo "><img class=imgoption src=img/snapraw_p.gif border=0 width=16 width=16></a>";
					}
					
					if ($jakistatus==0) {
						echo "<a title=' Sprzęt jest naprawiany we własnym zakresie ' href=# "; 
						//if ($_REQUEST[from]=='hd') echo " onClick=\"opener.location.href='main.php?action=nwwz'\">";
						echo "><img class=imgoption src=img/w_naprawie_wz.gif border=0 width=16 width=16></a>";
					}
					
					if ($jakistatus==1) {
						echo "<a title=' Sprzęt jest w naprawie w serwisie zewnętrznym ' href=# ";
						// echo "onClick=\"opener.location.href='main.php?action=npswsz'\">";
						echo "><img class=imgoption src=img/w_naprawie_sz.gif border=0 width=16 width=16></a>";
					}
					
					if ($jakistatus==2) {
						echo "<a title=' Sprzęt jest w naprawie w serwisie lokalnym ' href=# ";
						//echo "onClick=\"opener.location.href='main.php?action=nsnrl'\">";
						echo "><img class=imgoption src=img/w_naprawie_sl.gif border=0 width=16 width=16></a>";
					}
					
					if ($jakistatus==3) {
						echo "<a title=' Sprzęt wrócił z naprawy ' href=# ";
						//echo "onClick=\"opener.location.href='p_naprawy_zakonczone.php'\">";
						echo "><img class=imgoption src=img/snapraw_ok.gif border=0 width=16 width=16></a>";
					}
				//  if ($jakistatus==5) echo "sprzęt wrócił z naprawy";
					if ($jakistatus==7) {
						echo "<a title=' Sprzęt wycofany z serwisu ' href=# ";
						//echo "onClick=\"opener.location.href='main.php?action=nsw'\">";
						echo "><img class=imgoption src=img/wycofaj_z_serwisu.gif border=0 width=16 width=16></a>";
					}
					if ($jakistatus==8) {
						echo "<a title=' Sprzęt wycofany z serwisu ' href=# ";
						//if ($_REQUEST[from]=='hd') { echo " onClick=\"opener.location.href='main.php?action=nsw'; \""; }
						echo "><img class=imgoption src=img/wycofaj_z_serwisu.gif border=0 width=16 width=16></a>";
					}
					
				} else {
					echo "<input class=imgoption type=image src=img//napraw.gif title=' Wybierz ten sprzęt do wymiany podzespołów ' onclick='window.location=\"z_wymiana_wybor_z_ewidencji.php?ewid_id=$eeid&id=$eeid&ts=k&auto=1&pionnazwa=$temp_pion&tup=".urlencode($upnazwa)."&up=".urlencode($upnazwa)."&tnazwa=".urlencode($rolanazwa)."&tmodel=".urlencode($ekopis)."&tsn=".urlencode($eksn)."&tni=".urlencode($ekni)."&tresc_zgl=".urlencode($_REQUEST[tresc_zgl])."&new_upid=".urlencode($_REQUEST[new_upid])."&from=$_REQUEST[from]&hd_nr=$_REQUEST[hd_nr]\"'>";
					
//					echo "$_REQUEST[from] | $_REQUEST[hd_nr]";
				}
			
				echo "<input class=imgoption type=image src=img/detail.gif title=' Szczegółowe informacje o sprzęcie i oprogramowaniu ' onclick=\"newWindow_r(620,600,'p_ewidencja_szczegoly.php?id=$eeid')\">";
				if (($egwarancja!='0000-00-00') && ($egwarancja>=$dddd)) { 
					echo "<input class=imgoption type=image src=img/gwarancja.gif title=' Gwarancja do $egwarancja ' ";
					if ($egwarancjakto>-1) echo "onclick=\"newWindow(600,250,'p_firma_serwisowa_szczegoly.php?id=$egwarancjakto')\">";
				}
				$nazwa_urzadzenia_=$ekopis;
				$sn_urzadzenia_=$eksn;
				$ni_urzadzenia_=$ekni;
				//echo "<input class=imgoption type=image src=img/print.gif title=' Generuj protokół dla wybranego sprzętu ' onclick=\"newWindow_r(700,595,'utworz_protokol.php?dzien=$dd&miesiac=$mm&rok=$rr&c_1=&c_2=&c_3=&c_4=&c_5=&c_6=&c_7=&c_8=&up=".urlencode($upnazwa)."&nazwa_urzadzenia=".urlencode($nazwa_urzadzenia_)."&sn_urzadzenia=".urlencode($sn_urzadzenia_)."&ni_urzadzenia=".urlencode($ni_urzadzenia_)."&opis_urzadzenia=&wykonane_czynnosci=&uwagi=&imieinazwisko=&readonly=0&upid=$eup_id&findpion=1')\">"; 
			_td();
		} else if ($rolanazwa=='Drukarka') 
				{
					td_(";;".$edo."<br /><sub>$ekip</sub>");
						if ($pokazpoczatekuwag==1) pokaz_uwagi($eu,$iloscznakowuwag,"newWindow(480,330,'p_ewidencja_uwagi.php?id=$eeid')");
					_td();
					td(";;".$edsn."<br />".$edni."");
					td_img(";c");
						if ($eu!='') {
							echo "<a title=' Czytaj uwagi '><input class=imgoption type=image src=img/comment.gif  onclick=\"newWindow(480,330,'p_ewidencja_uwagi.php?id=$eeid')\">";
						}
						echo "<a title=' Edytuj uwagi '><input class=imgoption type=image src=img/edit_comment.gif onclick=\"newWindow(480,260,'e_ewidencja_uwagi.php?id=$eeid')\"></a>";
					_td();
					td_img(";c");
						$nazwa_urzadzenia_=$edo;
						$sn_urzadzenia_=$edsn;
						$ni_urzadzenia_=$edni;
						
				if (($czy_w_naprawie>0) && ($jakistatus!=9)) { 
				//echo $jakistatus;
					if ($jakistatus=='-1') {
						echo "<a title=' Sprzęt pobrany od klienta ' href=# "; 
						//if ($_REQUEST[from]=='hd') echo " onClick=\"opener.location.href='main.php?action=npus'\">"; 
						echo "><img class=imgoption src=img/snapraw_p.gif border=0 width=16 width=16></a>";
					}
					
					if ($jakistatus==0) {
						echo "<a title=' Sprzęt jest naprawiany we własnym zakresie ' href=# "; 
						//if ($_REQUEST[from]=='hd') echo " onClick=\"opener.location.href='main.php?action=nwwz'\">";
						echo "><img class=imgoption src=img/w_naprawie_wz.gif border=0 width=16 width=16></a>";
					}
					
					if ($jakistatus==1) {
						echo "<a title=' Sprzęt jest w naprawie w serwisie zewnętrznym ' href=# ";
						// echo "onClick=\"opener.location.href='main.php?action=npswsz'\">";
						echo "><img class=imgoption src=img/w_naprawie_sz.gif border=0 width=16 width=16></a>";
					}
					
					if ($jakistatus==2) {
						echo "<a title=' Sprzęt jest w naprawie w serwisie lokalnym ' href=# ";
						//echo "onClick=\"opener.location.href='main.php?action=nsnrl'\">";
						echo "><img class=imgoption src=img/w_naprawie_sl.gif border=0 width=16 width=16></a>";
					}
					
					if ($jakistatus==3) {
						echo "<a title=' Sprzęt wrócił z naprawy ' href=# ";
						//echo "onClick=\"opener.location.href='p_naprawy_zakonczone.php'\">";
						echo "><img class=imgoption src=img/snapraw_ok.gif border=0 width=16 width=16></a>";
					}
				//  if ($jakistatus==5) echo "sprzęt wrócił z naprawy";
					if ($jakistatus==7) {
						echo "<a title=' Sprzęt wycofany z serwisu ' href=# ";
						//echo "onClick=\"opener.location.href='main.php?action=nsw'\">";
						echo "><img class=imgoption src=img/wycofaj_z_serwisu.gif border=0 width=16 width=16></a>";
					}
					if ($jakistatus==8) {
						echo "<a title=' Sprzęt wycofany z serwisu ' href=# ";
						//if ($_REQUEST[from]=='hd') { echo " onClick=\"opener.location.href='main.php?action=nsw'; \""; }
						echo "><img class=imgoption src=img/wycofaj_z_serwisu.gif border=0 width=16 width=16></a>";
					}				} else {

							echo "<input class=imgoption type=image src=img//napraw.gif title=' Wybierz ten sprzęt do wymiany podzespołów ' onclick='window.location=\"z_wymiana_wybor_z_ewidencji.php?ewid_id=$eeid&id=$eeid&ts=k&auto=1&pionnazwa=$temp_pion&tup=".urlencode($upnazwa)."&up=".urlencode($upnazwa)."&tnazwa=".urlencode($rolanazwa)."&tmodel=".urlencode($nazwa_urzadzenia_)."&tsn=".urlencode($sn_urzadzenia_)."&tni=".urlencode($ni_urzadzenia_)."&tresc_zgl=".urlencode($_REQUEST[tresc_zgl])."&new_upid=".urlencode($_REQUEST[new_upid])."&from=$_REQUEST[from]&hd_nr=$_REQUEST[hd_nr]\"'>";
					
							//echo "<input class=imgoption type=image src=img//napraw.gif title=' Wybierz ten sprzęt do wymiany podzespołów ' onclick='window.location=\"z_naprawy_uszkodzony.php?ewid_id=$eeid&id=$eeid&ts=k&auto=1&pionnazwa=$temp_pion&tup=".urlencode($upnazwa)."&up=".urlencode($upnazwa)."&tnazwa=".urlencode($rolanazwa)."&tmodel=".urlencode($nazwa_urzadzenia_)."&tsn=".urlencode($sn_urzadzenia_)."&tni=".urlencode($ni_urzadzenia_)."&tresc_zgl=".urlencode($_REQUEST[tresc_zgl])."&new_upid=".urlencode($_REQUEST[new_upid])."&from=$_REQUEST[from]&hd_nr=$_REQUEST[hd_nr]\"'>";
							
//							echo "$_REQUEST[from] | $_REQUEST[hd_nr]";
						}
			
						//echo "<input class=imgoption type=image src=img//napraw.gif title=' Wybierz ten sprzęt do wymiany podzespołów ' onclick='window.location=\"z_naprawy_uszkodzony.php?id=$eeid&ts=d&auto=1&pionnazwa=$temp_pion&tup=".urlencode($upnazwa)."&up=".urlencode($nazwaup)."&tnazwa=".urlencode($rolanazwa)."&tmodel=".urlencode($edo)."&tsn=".urlencode($edsn)."&tni=".urlencode($edni)."\"'>";			
						echo "<input class=imgoption type=image src=img/detail.gif title=' Szczegółowe informacje o sprzęcie ' onclick=\"newWindow_r(620,600,'p_ewidencja_szczegoly.php?id=$eeid')\">";
						if (($egwarancja!='0000-00-00') && ($egwarancja>=$dddd)) { 
							echo "<input class=imgoption type=image src=img/gwarancja.gif title=' Gwarancja do $egwarancja ' ";
							if ($egwarancjakto>-1) echo "onclick=\"newWindow(600,250,'p_firma_serwisowa_szczegoly.php?id=$egwarancjakto')\">";
						}

						//echo "<input class=imgoption type=image src=img/print.gif title=' Generuj protokół dla wybranego sprzętu ' onclick=\"newWindow_r(700,595,'utworz_protokol.php?dzien=$dd&miesiac=$mm&rok=$rr&c_1=&c_2=&c_3=&c_4=&c_5=&c_6=&c_7=&c_8=&up=".urlencode($upnazwa)."&nazwa_urzadzenia=".urlencode($nazwa_urzadzenia_)."&sn_urzadzenia=".urlencode($sn_urzadzenia_)."&ni_urzadzenia=".urlencode($ni_urzadzenia_)."&opis_urzadzenia=&wykonane_czynnosci=&uwagi=&imieinazwisko=&readonly=0&upid=$eup_id&findpion=1')\">"; 
					_td();				
				} else if ($rolanazwa=='Monitor')
					{
						td_(";;".$emo."");
							if ($pokazpoczatekuwag==1) pokaz_uwagi($eu,$iloscznakowuwag,"newWindow(480,330,'p_ewidencja_uwagi.php?id=$eeid')");
						_td();
						td(";;".$emsn."<br />".$emni."");
						td_img(";c");
							$nazwa_urzadzenia_=$emo;
							$sn_urzadzenia_=$emsn;
							$ni_urzadzenia_=$emni;
							
							if ($eu!='') {
								echo "<a title=' Czytaj uwagi '><input class=imgoption type=image src=img/comment.gif  onclick=\"newWindow(480,330,'p_ewidencja_uwagi.php?id=$eeid')\">";
							}
							echo "<a title=' Edytuj uwagi '><input class=imgoption type=image src=img/edit_comment.gif onclick=\"newWindow(480,260,'e_ewidencja_uwagi.php?id=$eeid')\"></a>";
						_td();
						td_img(";c");
						
						if (($czy_w_naprawie>0) && ($jakistatus!=9)) { 
						//echo $jakistatus;
							if ($jakistatus=='-1') {
								echo "<a title=' Sprzęt pobrany od klienta ' href=# "; 
								//if ($_REQUEST[from]=='hd') echo " onClick=\"opener.location.href='main.php?action=npus'\">"; 
								echo "><img class=imgoption src=img/snapraw_p.gif border=0 width=16 width=16></a>";
							}
							
							if ($jakistatus==0) {
								echo "<a title=' Sprzęt jest naprawiany we własnym zakresie ' href=# "; 
								//if ($_REQUEST[from]=='hd') echo " onClick=\"opener.location.href='main.php?action=nwwz'\">";
								echo "><img class=imgoption src=img/w_naprawie_wz.gif border=0 width=16 width=16></a>";
							}
							
							if ($jakistatus==1) {
								echo "<a title=' Sprzęt jest w naprawie w serwisie zewnętrznym ' href=# ";
								// echo "onClick=\"opener.location.href='main.php?action=npswsz'\">";
								echo "><img class=imgoption src=img/w_naprawie_sz.gif border=0 width=16 width=16></a>";
							}
							
							if ($jakistatus==2) {
								echo "<a title=' Sprzęt jest w naprawie w serwisie lokalnym ' href=# ";
								//echo "onClick=\"opener.location.href='main.php?action=nsnrl'\">";
								echo "><img class=imgoption src=img/w_naprawie_sl.gif border=0 width=16 width=16></a>";
							}
							
							if ($jakistatus==3) {
								echo "<a title=' Sprzęt wrócił z naprawy ' href=# ";
								//echo "onClick=\"opener.location.href='p_naprawy_zakonczone.php'\">";
								echo "><img class=imgoption src=img/snapraw_ok.gif border=0 width=16 width=16></a>";
							}
						//  if ($jakistatus==5) echo "sprzęt wrócił z naprawy";
							if ($jakistatus==7) {
								echo "<a title=' Sprzęt wycofany z serwisu ' href=# ";
								//echo "onClick=\"opener.location.href='main.php?action=nsw'\">";
								echo "><img class=imgoption src=img/wycofaj_z_serwisu.gif border=0 width=16 width=16></a>";
							}
							if ($jakistatus==8) {
								echo "<a title=' Sprzęt wycofany z serwisu ' href=# ";
								//if ($_REQUEST[from]=='hd') { echo " onClick=\"opener.location.href='main.php?action=nsw'; \""; }
								echo "><img class=imgoption src=img/wycofaj_z_serwisu.gif border=0 width=16 width=16></a>";
							}
						} else {

							echo "<input class=imgoption type=image src=img//napraw.gif title=' Wybierz ten sprzęt do wymiany podzespołów ' onclick='window.location=\"z_wymiana_wybor_z_ewidencji.php?ewid_id=$eeid&id=$eeid&ts=k&auto=1&pionnazwa=$temp_pion&tup=".urlencode($upnazwa)."&up=".urlencode($upnazwa)."&tnazwa=".urlencode($rolanazwa)."&tmodel=".urlencode($nazwa_urzadzenia_)."&tsn=".urlencode($sn_urzadzenia_)."&tni=".urlencode($ni_urzadzenia_)."&tresc_zgl=".urlencode($_REQUEST[tresc_zgl])."&new_upid=".urlencode($_REQUEST[new_upid])."&from=$_REQUEST[from]&hd_nr=$_REQUEST[hd_nr]\"'>";
							
							//echo "<input class=imgoption type=image src=img//napraw.gif title=' Wybierz ten sprzęt do wymiany podzespołów  ' onclick='window.location=\"z_naprawy_uszkodzony.php?ewid_id=$eeid&id=$eeid&ts=m&auto=1&pionnazwa=$temp_pion&tup=".urlencode($upnazwa)."&up=".urlencode($upnazwa)."&tnazwa=".urlencode($rolanazwa)."&tmodel=".urlencode($emo)."&tsn=".urlencode($emsn)."&tni=".urlencode($ekni)."&tresc_zgl=".urlencode($_REQUEST[tresc_zgl])."&new_upid=".urlencode($_REQUEST[new_upid])."&from=$_REQUEST[from]&hd_nr=$_REQUEST[hd_nr]\"'>";
						}
						
						echo "<input class=imgoption type=image src=img/detail.gif title=' Szczegółowe informacje o sprzęcie ' onclick=\"newWindow_r(620,600,'p_ewidencja_szczegoly.php?id=$eeid')\">";

						if (($egwarancja!='0000-00-00') && ($egwarancja>=$dddd)) { 
							echo "<input class=imgoption type=image src=img/gwarancja.gif title=' Gwarancja do $egwarancja ' ";
							if ($egwarancjakto>-1) echo "onclick=\"newWindow(600,250,'p_firma_serwisowa_szczegoly.php?id=$egwarancjakto')\">";
						}

						$nazwa_urzadzenia_=$emo;
						$sn_urzadzenia_=$emsn;
						$ni_urzadzenia_=$ekni;
						//echo "<input class=imgoption type=image src=img/print.gif title=' Generuj protokół dla wybranego sprzętu ' onclick=\"newWindow_r(700,595,'utworz_protokol.php?dzien=$dd&miesiac=$mm&rok=$rr&c_1=&c_2=&c_3=&c_4=&c_5=&c_6=&c_7=&c_8=&up=".urlencode($upnazwa)."&nazwa_urzadzenia=".urlencode($nazwa_urzadzenia_)."&sn_urzadzenia=".urlencode($sn_urzadzenia_)."&ni_urzadzenia=".urlencode($ni_urzadzenia_)."&opis_urzadzenia=&wykonane_czynnosci=&uwagi=&imieinazwisko=&readonly=0&upid=$eup_id&findpion=1')\">"; 
						_td();										
					} else 
					{
						$ekopis = $eknazwa;
						td_(";;".$ekopis."");
						_td();
						td(";;".$eksn."<br />".$ekni."");

						td_img(";c");
							if ($eu!='') {
								echo "<a title=' Czytaj uwagi '><input class=imgoption type=image src=img/comment.gif  onclick=\"newWindow(480,330,'p_ewidencja_uwagi.php?id=$eeid')\">";
							}
							echo "<a title=' Edytuj uwagi '><input class=imgoption type=image src=img/edit_comment.gif onclick=\"newWindow(480,260,'e_ewidencja_uwagi.php?id=$eeid')\"></a>";
						_td();
						td_img(";c");
						if (($czy_w_naprawie>0) && ($jakistatus!=8)) { 
						//echo $jakistatus;
							echo "<b><font color=red>";
							if ($jakistatus=='-1') echo "uszkodzony - na stanie";					
							if ($jakistatus==0) echo "naprawiany we własnym zakresie";
							if ($jakistatus==1) echo "naprawie w serwisie";
							if ($jakistatus==2) echo "naprawie w serwisie lokalnym";
							if ($jakistatus==3) echo "sprzęt wrócił z naprawy";
						//  if ($jakistatus==5) echo "sprzęt wrócił z naprawy";
							if ($jakistatus==7) echo "wycofany z serwisu";
							if ($jakistatus==8) echo "wycofany z serwisu";
							echo "</font></b><br />"; 
						} else {
						
							$nazwa_urzadzenia_=$ekopis;
							$sn_urzadzenia_=$eksn;
							$ni_urzadzenia_=$ekni;
							if ($ni_urzadzenia=='') $ni_urzadzenia='-';
							
							echo "<input class=imgoption type=image src=img//napraw.gif title=' Wybierz ten sprzęt do wymiany podzespołów ' onclick='window.location=\"z_wymiana_wybor_z_ewidencji.php?ewid_id=$eeid&id=$eeid&ts=k&auto=1&pionnazwa=$temp_pion&tup=".urlencode($upnazwa)."&up=".urlencode($upnazwa)."&tnazwa=".urlencode($rolanazwa)."&tmodel=".urlencode($nazwa_urzadzenia_)."&tsn=".urlencode($sn_urzadzenia_)."&tni=".urlencode($ni_urzadzenia_)."&tresc_zgl=".urlencode($_REQUEST[tresc_zgl])."&new_upid=".urlencode($_REQUEST[new_upid])."&from=$_REQUEST[from]&hd_nr=$_REQUEST[hd_nr]\"'>";
							
							//echo "<input class=imgoption type=image src=img//napraw.gif title=' Wybierz ten sprzęt do wymiany podzespołów ' onclick='window.location=\"z_naprawy_uszkodzony.php?ewid_id=$eeid&id=$eeid&ts=k&auto=1&pionnazwa=$temp_pion&tresc_zgl=".urlencode($_REQUEST[tresc_zgl])."&new_upid=".urlencode($_REQUEST[new_upid])."&from=$_REQUEST[from]&hd_nr=$_REQUEST[hd_nr]&tnazwa=".urlencode($rolanazwa)."&tmodel=".urlencode($nazwa_urzadzenia_)."&tsn=".urlencode($sn_urzadzenia_)."&tni=".urlencode($ni_urzadzenia_)."\"'>";			
						}
						echo "<input class=imgoption type=image src=img/detail.gif title=' Szczegółowe informacje o sprzęcie ' onclick=\"newWindow_r(620,600,'p_ewidencja_szczegoly.php?id=$eeid')\">";
						
						if (($egwarancja!='0000-00-00') && ($egwarancja>=$dddd)) { 
							echo "<input class=imgoption type=image src=img/gwarancja.gif title=' Gwarancja do $egwarancja ' ";
							if ($egwarancjakto>-1) echo "onclick=\"newWindow(600,250,'p_firma_serwisowa_szczegoly.php?id=$egwarancjakto')\">";
						}
						//echo "<input class=imgoption type=image src=img/print.gif title=' Generuj protokół dla wybranego sprzętu ' onclick=\"newWindow_r(700,595,'utworz_protokol.php?dzien=$dd&miesiac=$mm&rok=$rr&c_1=&c_2=&c_3=&c_4=&c_5=&c_6=&c_7=&c_8=&up=".urlencode($upnazwa)."&nazwa_urzadzenia=".urlencode($nazwa_urzadzenia_)."&sn_urzadzenia=".urlencode($sn_urzadzenia_)."&ni_urzadzenia=".urlencode($ni_urzadzenia_)."&opis_urzadzenia=&wykonane_czynnosci=&uwagi=&imieinazwisko=&readonly=0&upid=$eup_id&findpion=1')\">"; 
					_td();
					}
		_tr();
	}
endtable();
} else {
		errorheader("Brak takiego sprzętu w wybranej lokalizacji");
		startbuttonsarea("right");
		
//		addbuttons("wstecz","anuluj");
		endbuttonsarea();
	}

if ($_REQUEST[tresc_zgl]=='') {
	echo "<form name=addt action=$PHP_SELF method=POST>";
	echo "<input type=hidden name=tresc_zgl value='".urlencode($_REQUEST[tresc_zgl])."'>";	
	echo "<input type=hidden name=up_id value=$_POST[upid]>";	
	echo "<input type=hidden name=new_upid value=$_POST[new_upid]>";	

	startbuttonsarea("right");
	addownlinkbutton2("'Wybierz od nowa'","noauto","submit","z_naprawy_uszkodzony.php?id=0&auto=0");
	addownlinkbutton2("'Inny typ sprzętu z tej lokalizacji'","noauto","submit","z_naprawy_uszkodzony.php?id=0&auto=0&new_upid=".$_POST[new_upid]."");
	
	//echo "z_naprawy_uszkodzony.php?id=0&auto=0&new_upid=".$_POST[new_upid]."";
	
	addbuttons("anuluj");
	endbuttonsarea();
	_form();
}

if ($_REQUEST[from]=='hd') {
	startbuttonsarea("right");
	echo "<span style='float:left'>";
	echo "<input type=button class=buttons value='Wróć do poprzedniego widoku' onClick='history.go(-1);'>";
	echo "</span>";
	addbuttons("anuluj");
	endbuttonsarea();
}

include('body_stop.php'); 
?>
</body>
</html>