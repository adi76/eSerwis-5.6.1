<?php include_once('header.php'); ?>
<?php 
if ($_REQUEST[tup]!='') { $nr=1; } else { $nr=0; }
echo "<body OnLoad=document.forms[0].elements[".$nr."].focus();>"; ?>
<?php
if ($submit) {
	$sql77="SELECT rola_nazwa FROM $dbname.serwis_slownik_rola WHERE (rola_id='$_POST[typid]')";
	$result77 = mysql_query($sql77, $conn) or die($k_b);
	$newArray77 = mysql_fetch_array($result77);
	$rolanazwa	= $newArray77['rola_nazwa'];

	if ($rolanazwa!='Monitor') {	
		$sql = "SELECT * FROM $dbname.serwis_ewidencja WHERE (ewidencja_up_id=$_POST[new_upid]) and (ewidencja_typ=$_POST[typid])";
	} else 
		{
			$sql = "SELECT * FROM $dbname.serwis_ewidencja WHERE (ewidencja_up_id=$_POST[new_upid]) and (ewidencja_monitor_opis<>'')";
		}

	$result = mysql_query($sql, $conn) or die($k_b);
	$count = mysql_num_rows($result);
	
if ($count!=0) {

	pageheader("Sprzęt dostępny w wybranej lokalizacji");

	$sql7="SELECT up_id,up_nazwa FROM $dbname.serwis_komorki WHERE up_id=$_REQUEST[new_upid]";
	$result7 = mysql_query($sql7, $conn) or die($k_b);

	while ($newArray7 = mysql_fetch_array($result7))
	{
		$upid1		= $newArray7['up_id'];
		$upnazwa	= $newArray7['up_nazwa'];
	}

	startbuttonsarea("center");
	echo "Wybrana lokalizacja : <b>$upnazwa</b><br />";
	endbuttonsarea();
	
	starttable();
	echo "<tr><th>Nazwa sprzętu</th><th>SN<br />NI</th><th width=40 class=center>Uwagi</th><th class=center>Opcje</th></tr>";
	$dddd = Date('Y-m-d');
	$dd = Date('d');
	$mm = Date('m');
	$rr = Date('Y');
	$i = 0;
	while ($dane = mysql_fetch_array($result)) 
	{
		$eeid 		= $dane['ewidencja_id'];
		$etyp_id	= $dane['ewidencja_typ'];
		$eup_id		= $dane['ewidencja_up_id'];
		$ekopis		= $dane['ewidencja_komputer_opis'];
		$eknazwa	= $dane['ewidencja_komputer_nazwa'];
		$eksn		= $dane['ewidencja_komputer_sn'];
		$ekni		= $dane['ewidencja_zestaw_ni'];
		$emo		= $dane['ewidencja_monitor_opis'];
		$emsn		= $dane['ewidencja_monitor_sn'];
		$edo		= $dane['ewidencja_drukarka_opis'];
		$edsn		= $dane['ewidencja_drukarka_sn'];
		$edni		= $dane['ewidencja_drukarka_ni'];
		$eu			= $dane['ewidencja_uwagi'];
		$es			= $dane['ewidencja_status'];
		$egwarancja	= $dane['ewidencja_gwarancja_do'];
		$egwarancjakto= $dane['ewidencja_gwarancja_kto'];		
		$eo_id		= $dane['ewidencja_oprogramowanie'];

		tbl_tr_highlight($i);
	  	$i+=1;

		if (($rolanazwa=='Komputer') || ($rolanazwa=='Serwer') || ($rolanazwa=='Notebook')) 
		{
			echo "<td>$ekopis";
			if ($pokazpoczatekuwag==1) pokaz_uwagi($eu,$iloscznakowuwag,"newWindow(480,330,'p_ewidencja_uwagi.php?id=$eeid')");
			echo "</td>";
			echo "<td>$eksn<br />$ekni</td>";
			$uwagisa = ($eu!='');
			echo "<td width=40 class=center>";
			
			if ($uwagisa=='1') {
				echo "<input class=imgoption type=image src=img/comment.gif title=' Czytaj uwagi ' onclick=\"newWindow(480,330,'p_ewidencja_uwagi.php?id=$eeid')\">";
			} 
			
			echo "<a title=' Edytuj uwagi '><input class=imgoption type=image src=img/edit_comment.gif onclick=\"newWindow(480,260,'e_ewidencja_uwagi.php?id=$eeid')\"></a>";
			echo "</td>";

			echo "<td class=center width=100>";
		
			$accessLevels = array("0","1","9");
			if (array_search($es_prawa, $accessLevels)>-1) {
				echo "<a title=' Popraw dane o wybranym sprzęcie '><input class=imgoption type=image src=img/edit.gif  onclick=\"newWindow_r(820,600,'e_ewidencja.php?id=$eeid')\"></a>";
			}
			if (array_search($es_prawa, $accessLevels)>-1) {
				echo "<a title='Popraw wszystkie dane o wybranym sprzęcie (f-cja tylko dla Administratorów)'><input class=imgoption type=image src=img/edita.gif onclick=\"newWindow_r(820,600,'e_ewidencja.php?id=$eeid&edittype=admin')\"></a>";
			}
	
			echo "<input class=imgoption type=image src=img/detail.gif title=' Szczegółowe informacje o sprzęcie i oprogramowaniu ' onclick=\"newWindow_r(620,600,'p_ewidencja_szczegoly.php?id=$eeid')\">";

			if (($egwarancja!='0000-00-00') && ($egwarancja>=$dddd)) { 
				echo "<input class=imgoption type=image src=img/gwarancja.gif title=' Gwarancja do $egwarancja ' ";
				if ($egwarancjakto>-1) echo "onclick=\"newWindow(600,250,'p_firma_serwisowa_szczegoly.php?id=$egwarancjakto')\">";
			}

			$nazwa_urzadzenia_=$ekopis;
			
			$nazwa_urzadzenia_ = $rolanazwa." ".$nazwa_urzadzenia_;
			
			$sn_urzadzenia_=$eksn;
			$ni_urzadzenia_=$ekni;
			$wykonane_czynnosci=$_REQUEST[wykonane_czynnosci];
			if ($wykonane_czynnosci=='') $wykonane_czynnosci=$wc;
			
			echo "<input class=imgoption type=image src=img/print.gif title=' Generuj protokół dla wybranego sprzętu ' onclick='window.location=\"utworz_protokol.php?blank=".$_REQUEST[blank]."&dzien=$dd&miesiac=$mm&rok=$rr&c_1=$c_1&c_2=$c_2&c_3=$c_3&c_4=$c_4&c_5=$c_5&c_6=$c_6&c_7=$c_7&c_8=$c_8&up=".urlencode($upnazwa)."&nazwa_urzadzenia=".urlencode($nazwa_urzadzenia_)."&sn_urzadzenia=".urlencode($sn_urzadzenia_)."&ni_urzadzenia=".urlencode($ni_urzadzenia_)."&opis_uszkodzenia=".urlencode($ou)."&wykonane_czynnosci=".urlencode($wc)."&uwagi=".urlencode($uwagi)."&imieinazwisko=&readonly=0&choosefromewid=1&findpion=1&upid=$eup_id&source=".$_REQUEST[source]."&state=".$_REQUEST[state]."&nowy=1&findpion=1&wstecz=1&tup=$eup_id&new_upid=$_REQUEST[new_upid]&jedna_umowa=".urlencode($_REQUEST[jedna_umowa])."&tumowa=".urlencode($_REQUEST[tumowa])."&trodzaj=".urlencode($_REQUEST[trodzaj])."&uwagi=".urlencode($_REQUEST[uwagi])."&tstatus=".urlencode($_REQUEST[tstatus])."&ttyp=".urlencode($_REQUEST[ttyp])."&tidf=".urlencode($_REQUEST[tidf])."&tid=".urlencode($_REQUEST[tid])."&tcenaodsp=".urlencode($_REQUEST[tcenaodsp])."&tcena=".urlencode($_REQUEST[tcena])."&tnazwa=".urlencode($_REQUEST[tnazwa])."&zestaw=".$_REQUEST[zestaw]."&niedodawaj=1&zid=".$_REQUEST[zid]."&obzp=".$_REQUEST[obzp]."&edit_towar=".$_REQUEST[edit_towar]."&edit_zestaw=".$_REQUEST[edit_zestaw]."&wstecz=".$_REQUEST[wstecz]."\";'>"; 

			echo "";	
			echo "</td>";
				
		} else if ($rolanazwa=='Drukarka') {
					echo "<td>$edo";
					if ($pokazpoczatekuwag==1) pokaz_uwagi($eu,$iloscznakowuwag,"newWindow(480,330,'p_ewidencja_uwagi.php?id=$eeid')");
					echo "</td>";
					echo "<td>$edsn<br />$edni</td>";
					$uwagisa = ($eu!='');
					echo "<td width=40 class=center>";
					if ($uwagisa=='1') {
						echo "<input class=imgoption type=image src=img/comment.gif title=' Czytaj uwagi ' onclick=\"newWindow(480,330,'p_ewidencja_uwagi.php?id=$eeid')\">";
					}
					echo "<a title=' Edytuj uwagi '><input class=imgoption type=image src=img/edit_comment.gif onclick=\"newWindow(480,260,'e_ewidencja_uwagi.php?id=$eeid')\"></a>";
					echo "</td>";
					echo "<td class=center width=100>";					
					if (($egwarancja!='0000-00-00') && ($egwarancja>=$dddd)) { 
						echo "<input class=imgoption type=image src=img/gwarancja.gif align=absmiddle title=' Gwarancja do $egwarancja ' ";
						if ($egwarancjakto>-1) echo "onclick=\"newWindow(600,250,'p_firma_serwisowa_szczegoly.php?id=$egwarancjakto')\">";
					}
					echo "<input class=imgoption type=image src=img/detail.gif title=' Szczegółowe informacje o sprzęcie i oprogramowaniu ' onclick=\"newWindow_r(620,600,'p_ewidencja_szczegoly.php?id=$eeid')\">";					

					$nazwa_urzadzenia_=$edo;
					$nazwa_urzadzenia_ = $rolanazwa." ".$nazwa_urzadzenia_;
					
					$sn_urzadzenia_=$edsn;
					$ni_urzadzenia_=$edni;
					$wykonane_czynnosci=$_REQUEST[wykonane_czynnosci];
					if ($wykonane_czynnosci=='') $wykonane_czynnosci=$wc;
					
					echo "<input class=imgoption type=image src=img/print.gif title=' Generuj protokół dla wybranego sprzętu ' onclick='window.location=\"utworz_protokol.php?blank=".$_REQUEST[blank]."&dzien=$dd&miesiac=$mm&rok=$rr&c_1=$c_1&c_2=$c_2&c_3=$c_3&c_4=$c_4&c_5=$c_5&c_6=$c_6&c_7=$c_7&c_8=$c_8&up=".urlencode($upnazwa)."&nazwa_urzadzenia=".urlencode($nazwa_urzadzenia_)."&sn_urzadzenia=".urlencode($sn_urzadzenia_)."&ni_urzadzenia=".urlencode($ni_urzadzenia_)."&opis_uszkodzenia=".urlencode($ou)."&wykonane_czynnosci=".urlencode($wc)."&uwagi=".urlencode($uwagi)."&imieinazwisko=&readonly=0&choosefromewid=1&findpion=1&upid=$eup_id&new_upid=$_REQUEST[new_upid]&source=".$_REQUEST[source]."&state=".$_REQUEST[state]."&jedna_umowa=".urlencode($_REQUEST[jedna_umowa])."&tumowa=".urlencode($_REQUEST[tumowa])."&trodzaj=".urlencode($_REQUEST[trodzaj])."&uwagi=".urlencode($_REQUEST[uwagi])."&tstatus=".urlencode($_REQUEST[tstatus])."&ttyp=".urlencode($_REQUEST[ttyp])."&tidf=".urlencode($_REQUEST[tidf])."&tid=".urlencode($_REQUEST[tid])."&tcenaodsp=".urlencode($_REQUEST[tcenaodsp])."&tcena=".urlencode($_REQUEST[tcena])."&tnazwa=".urlencode($_REQUEST[tnazwa])."&zestaw=".$_REQUEST[zestaw]."&niedodawaj=1&zid=".$_REQUEST[zid]."&obzp=".$_REQUEST[obzp]."&edit_towar=".$_REQUEST[edit_towar]."&edit_zestaw=".$_REQUEST[edit_zestaw]."&wstecz=".$_REQUEST[wstecz]."\";'>";
					echo "";	
					echo "</td>";					
				} else if ($rolanazwa=='Monitor')
					{
						echo "<td>$emo";
						if ($pokazpoczatekuwag==1) pokaz_uwagi($eu,$iloscznakowuwag,"newWindow(480,330,'p_ewidencja_uwagi.php?id=$eeid')");
						echo "</td>";
						echo "<td>$emsn<br />$ekni</td>";
						$uwagisa = ($eu!='');
						echo "<td width=40 class=center>";
						if ($uwagisa=='1') {
							echo "<input class=imgoption type=image src=img/comment.gif title=' Czytaj uwagi ' onclick=\"newWindow(480,330,'p_ewidencja_uwagi.php?id=$eeid')\">";
						}
						echo "<a title=' Edytuj uwagi '><input class=imgoption type=image src=img/edit_comment.gif onclick=\"newWindow(480,260,'e_ewidencja_uwagi.php?id=$eeid')\"></a>";
						echo "</td>";
						echo "<td class=center width=100>";
						//echo "<input class=imgoption type=image src=img//napraw.gif title=' Przyjmij wybrany sprzęt do naprawy ' onclick='window.location=\"z_naprawy_uszkodzony.php?id=$eeid&ts=m&auto=1\"'>";	

						if (($egwarancja!='0000-00-00') && ($egwarancja>=$dddd)) { 
							echo "<input class=imgoption type=image src=img/gwarancja.gif align=absmiddle title=' Gwarancja do $egwarancja ' ";
							if ($egwarancjakto>-1) echo "onclick=\"newWindow(600,250,'p_firma_serwisowa_szczegoly.php?id=$egwarancjakto')\">";
						}
						echo "<input class=imgoption type=image src=img/detail.gif title=' Szczegółowe informacje o sprzęcie i oprogramowaniu ' onclick=\"newWindow_r(620,600,'p_ewidencja_szczegoly.php?id=$eeid')\">";					

						$nazwa_urzadzenia_=$emo;
						$nazwa_urzadzenia_ = $rolanazwa." ".$nazwa_urzadzenia_;
						
						$sn_urzadzenia_=$emsn;
						$ni_urzadzenia_=$ekni;
						$wykonane_czynnosci=$_REQUEST[wykonane_czynnosci];
						if ($wykonane_czynnosci=='') $wykonane_czynnosci=$wc;

						echo "<input class=imgoption type=image src=img/print.gif title=' Generuj protokół dla wybranego sprzętu ' onclick='window.location=\"utworz_protokol.php?blank=".$_REQUEST[blank]."&dzien=$dd&miesiac=$mm&rok=$rr&c_1=$c_1&c_2=$c_2&c_3=$c_3&c_4=$c_4&c_5=$c_5&c_6=$c_6&c_7=$c_7&c_8=$c_8&up=".urlencode($upnazwa)."&nazwa_urzadzenia=".urlencode($nazwa_urzadzenia_)."&sn_urzadzenia=".urlencode($sn_urzadzenia_)."&ni_urzadzenia=".urlencode($ni_urzadzenia_)."&opis_uszkodzenia=".urlencode($ou)."&wykonane_czynnosci=".urlencode($wc)."&uwagi=".urlencode($uwagi)."&imieinazwisko=&readonly=0&choosefromewid=1&findpion=1&upid=$eup_id&new_upid=".$_REQUEST[new_upid]."&source=".$_REQUEST[source]."&state=".$_REQUEST[state]."&jedna_umowa=".urlencode($_REQUEST[jedna_umowa])."&tumowa=".urlencode($_REQUEST[tumowa])."&trodzaj=".urlencode($_REQUEST[trodzaj])."&uwagi=".urlencode($_REQUEST[uwagi])."&tstatus=".urlencode($_REQUEST[tstatus])."&ttyp=".urlencode($_REQUEST[ttyp])."&tidf=".urlencode($_REQUEST[tidf])."&tid=".urlencode($_REQUEST[tid])."&tcenaodsp=".urlencode($_REQUEST[tcenaodsp])."&tcena=".urlencode($_REQUEST[tcena])."&tnazwa=".urlencode($_REQUEST[tnazwa])."&zestaw=".$_REQUEST[zestaw]."&niedodawaj=1&zid=".$_REQUEST[zid]."&obzp=".$_REQUEST[obzp]."&edit_towar=".$_REQUEST[edit_towar]."&edit_zestaw=".$_REQUEST[edit_zestaw]."&wstecz=".$_REQUEST[wstecz]."\";'>";
						echo "";	
						echo "</td>";										
					
					} else 
					{
						echo "<td>$eknazwa</td>";
						echo "<td>$eksn<br />$ekni</td>";
						
						//echo "<td></td><td></td>";
						$uwagisa = ($eu!='');
						if ($uwagisa=='1') {
						echo "<td width=100 class=center><input class=imgoption type=image src=img/comment.gif title=' Czytaj uwagi ' onclick=\"newWindow(480,330,'p_ewidencja_uwagi.php?id=$eid')\">";
						} else echo "<td class=center>-</td>";
						
						echo "<td class=center width=100>";						
						//echo "<input class=imgoption type=image src=img//napraw.gif title=' Przyjmij wybrany sprzęt do naprawy ' onclick='window.location=\"z_naprawy_uszkodzony.php?id=$eeid&ts=i&auto=1\"'>";	
						
						if (($egwarancja!='0000-00-00') && ($egwarancja>=$dddd)) { 
							echo "<input class=imgoption type=image src=img/gwarancja.gif align=absmiddle title=' Gwarancja do $egwarancja ' ";
							if ($egwarancjakto>-1) echo "onclick=\"newWindow(600,250,'p_firma_serwisowa_szczegoly.php?id=$egwarancjakto')\"";
						}
						echo "<input class=imgoption type=image src=img/detail.gif title=' Szczegółowe informacje o sprzęcie i oprogramowaniu ' onclick=\"newWindow_r(620,600,'p_ewidencja_szczegoly.php?id=$eeid')\">";			

						$nazwa_urzadzenia_=$rolanazwa." ".$eknazwa;
						$sn_urzadzenia_ = $eksn;
						$ni_urzadzenia_ = $ekni;
						
						$c_3 = 'on';
						
						$wykonane_czynnosci=$_REQUEST[wykonane_czynnosci];
						if ($wykonane_czynnosci=='') $wykonane_czynnosci=$wc;

						echo "<input class=imgoption type=image src=img/print.gif title=' Generuj protokół dla wybranego sprzętu ' onclick='window.location=\"utworz_protokol.php?blank=".$_REQUEST[blank]."&dzien=$dd&miesiac=$mm&rok=$rr&c_1=$c_1&c_2=$c_2&c_3=$c_3&c_4=$c_4&c_5=$c_5&c_6=$c_6&c_7=$c_7&c_8=$c_8&up=".urlencode($upnazwa)."&nazwa_urzadzenia=".urlencode($nazwa_urzadzenia_)."&sn_urzadzenia=".urlencode($sn_urzadzenia_)."&ni_urzadzenia=".urlencode($ni_urzadzenia_)."&opis_uszkodzenia=".urlencode($ou)."&wykonane_czynnosci=".urlencode($wc)."&uwagi=".urlencode($uwagi)."&imieinazwisko=&readonly=0&choosefromewid=1&odswiez_openera=2&findpion=1&upid=$eup_id&source=".$_REQUEST[source]."&state=".$_REQUEST[state]."&jedna_umowa=".urlencode($_REQUEST[jedna_umowa])."&tumowa=".urlencode($_REQUEST[tumowa])."&trodzaj=".urlencode($_REQUEST[trodzaj])."&uwagi=".urlencode($_REQUEST[uwagi])."&tstatus=".urlencode($_REQUEST[tstatus])."&ttyp=".urlencode($_REQUEST[ttyp])."&tidf=".urlencode($_REQUEST[tidf])."&tid=".urlencode($_REQUEST[tid])."&tcenaodsp=".urlencode($_REQUEST[tcenaodsp])."&tcena=".urlencode($_REQUEST[tcena])."&tnazwa=".urlencode($_REQUEST[tnazwa])."&zestaw=".$_REQUEST[zestaw]."&niedodawaj=1&zid=".$_REQUEST[zid]."&obzp=".$_REQUEST[obzp]."&new_upid=$_REQUEST[new_upid]&edit_towar=".$_REQUEST[edit_towar]."&edit_zestaw=".$_REQUEST[edit_zestaw]."&wstecz=".$_REQUEST[wstecz]."\";'>";
						
						echo "";
						echo "</td>";
					}
		
		echo "</tr>";
	}
	
	endtable();
} else errorheader("Brak takiego sprzętu w wybranej lokalizacji");

echo "<form name=addt action=$PHP_SELF method=POST>";
echo "<input type=hidden name=up_id value=$_POST[upid]>";
echo "<input type=hidden name=opis_uszkodzenia value='$ou'>";
echo "<input type=hidden name=wykonane_czynnosci value='$wc'>";
//echo "<input type=hidden name=uwagi value='$u'>";

echo "<input type=hidden name=c_1 value='$c_1'>";

echo "<input type=hidden name=source value='$_REQUEST[source]'>";

echo "<input type=hidden name=wykonane_czynnosci value='".urlencode($_REQUEST[wykonane_czynnosci])."'>";
echo "<input type=hidden name=uwagi value='".urlencode($_REQUEST[uwagi])."'>";
echo "<input type=hidden name=blank value='$_REQUEST[blank])'>";

startbuttonsarea("right");
//addownlinkbutton("'Wybierz od nowa'","button","button","history.go(-1);");
//addownlinkbutton2("'Wybierz od nowa'","button","submit","e_protokol_z_ewidencji.php?upid=0&up_id=0&opis_uszkodzenia=".urlencode($ou)."&wykonane_czynnosci=".urlencode($wc)."&uwagi=".urlencode($u)."");

addownlinkbutton("'Inny typ sprzętu z tej lokalizacji'","button","button","history.go(-1);");

//addownlinkbutton2("'Inny typ sprzętu z tej lokalizacji'","button","submit","e_protokol_z_ewidencji.php?new_upid=$new_upid&opis_uszkodzenia=".urlencode($ou)."&wykonane_czynnosci=".urlencode($wc)."&uwagi=".urlencode($u)."");

//addownlinkbutton2("'Anuluj wybieranie z ewidencji'","button","button","utworz_protokol.php?opis_uszkodzenia=".urlencode($ou)."&wykonane_czynnosci=".urlencode($wc)."&uwagi=".urlencode($u)."&choosefromewid=1");

addownlinkbutton2("'Anuluj wybieranie z ewidencji'","button","button","utworz_protokol.php?blank=".$_REQUEST[blank]."&dzien=$dd&miesiac=$mm&rok=$rr&c_1=$c_1&c_2=$c_2&c_3=$c_3&c_4=$c_4&c_5=$c_5&c_6=$c_6&c_7=$c_7&c_8=$c_8&up=".urlencode($upnazwa)."&nazwa_urzadzenia=".urlencode($nazwa_urzadzenia)."&sn_urzadzenia=".urlencode($sn_urzadzenia)."&ni_urzadzenia=".urlencode($ni_urzadzenia)."&opis_uszkodzenia=".urlencode($ou)."&wykonane_czynnosci=".urlencode($wc)."&uwagi=".urlencode($uwagi)."&imieinazwisko=&readonly=0&choosefromewid=1&findpion=1&upid=$eup_id&source=".$_REQUEST[source]."&state=".$_REQUEST[state]."&nowy=1&findpion=1&wstecz=1&tup=$eup_id&new_upid=$_REQUEST[new_upid]&jedna_umowa=".urlencode($_REQUEST[jedna_umowa])."&tumowa=".urlencode($_REQUEST[tumowa])."&trodzaj=".urlencode($_REQUEST[trodzaj])."&uwagi=".urlencode($_REQUEST[uwagi])."&tstatus=".urlencode($_REQUEST[tstatus])."&ttyp=".urlencode($_REQUEST[ttyp])."&tidf=".urlencode($_REQUEST[tidf])."&tid=".urlencode($_REQUEST[tid])."&tcenaodsp=".urlencode($_REQUEST[tcenaodsp])."&tcena=".urlencode($_REQUEST[tcena])."&tnazwa=".urlencode($_REQUEST[tnazwa])."&zestaw=".$_REQUEST[zestaw]."&niedodawaj=1&zid=".$_REQUEST[zid]."&obzp=".$_REQUEST[obzp]."&edit_towar=".$_REQUEST[edit_towar]."&edit_zestaw=".$_REQUEST[edit_zestaw]."&wstecz=".$_REQUEST[wstecz]."&tdata=".$_REQUEST[tdata]."&tuwagi=".urlencode($_REQUEST[tuwagi])."&hd_zgl_nr=".$_REQUEST[hd_zgl_nr]."&tprzesylka=".$_REQUEST[tprzesylka]."&tsn=".urlencode($_REQUEST[tsn])."&ewid_id=".$_REQUEST[ewid_id]."&quiet=".$_REQUEST[quiet]."");

//tdata=".$_REQUEST[tdata]."&tuwagi=".urlencode($_REQUEST[tuwagi])."&hd_zgl_nr=".$_REQUEST[hd_zgl_nr]."&tprzesylka=".$_REQUEST[tprzesylka]."&tsn=".urlencode($_REQUEST[tsn])."&ewid_id=".$_REQUEST[ewid_id]."&quiet=".$_REQUEST[quiet]."

endbuttonsarea();
_form();


} else {
	pageheader("Wybór sprzętu do wypełnienia protokołu");
	starttable();
	echo "<form name=addt action=$PHP_SELF method=POST>";
	tbl_empty_row(2);
	
	echo "<tr>";
		echo "<td width=200 class=right>Lokalizacja sprzętu</td>";
		$sql44="SELECT up_id,up_nazwa, pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_piony.pion_id=serwis_komorki.up_pion_id) and (belongs_to=$es_filia) ORDER BY pion_nazwa, up_nazwa ASC";

		$result44 = mysql_query($sql44, $conn) or die($k_b);
		$count_rows = mysql_num_rows($result44);
		$i = 0;

		echo "<td>";

	if ($_REQUEST[block_komorka]!=1) {
	
		echo "<select class=wymagane name=new_upid onkeypress=\"return handleEnter(this, event);\">";				 
		echo "<option value=''>Wybierz z listy...</option>\n";

		while ($newArray44 = mysql_fetch_array($result44)) 
		{
			$temp_id  				= $newArray44['up_id'];
			$temp_nazwa				= $newArray44['up_nazwa'];
			$temp_pion				= $newArray44['pion_nazwa'];
		
			echo "<option value=$temp_id ";
			if ($new_upid==$temp_id) echo "SELECTED";
			if ($up_id==$temp_id) echo "SELECTED";
			if ($temp_id==$_REQUEST[tup]) echo "SELECTED";
			
			echo ">$temp_pion $temp_nazwa</option>\n"; 
		}
		echo "</select>\n"; 
	} else {	
		//if ($_COOKIE['pustyprotokol']!='0') $_REQUEST['new_upid']=$_COOKIE['pustyprotokol'];
		
			//if ($block_komorka==1)
			
			$sql21 = "SELECT up_nazwa, pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.up_id=$_REQUEST[new_upid]) and (serwis_piony.pion_id=serwis_komorki.up_pion_id) and (belongs_to=$es_filia) LIMIT 1";
			//echo $sql21;
			$wynik21 = mysql_query($sql21,$conn);
			list($nazwa,$pion) = mysql_fetch_array($wynik21);
			echo "<b>$pion $nazwa</b>";
			
			echo "<input type=hidden name=new_upid value=$_REQUEST[new_upid]>";
		}
		
	/*	if (($_REQUEST[tup]!='') || ($_REQUEST[tup1]!='')) { 
			$nowe_tup = $_REQUEST[tup];
			if ($_REQUEST[tup1]!='') $nowe_tup = $_REQUEST[tup1];
			echo "<input type=hidden name=new_upid value='$nowe_tup'>";
		}
		*/
		echo "</td>";
	echo "</tr>";
		echo "<tr>";
		echo "<td width=200 class=right>Typ sprzętu</td>";

		$sql="SELECT * FROM $dbname.serwis_slownik_rola WHERE rola_do_ewidencji='1' ORDER BY rola_nazwa";
		$result = mysql_query($sql, $conn) or die($k_b);
		$count_rows = mysql_num_rows($result);
		$i = 0;
		
		echo "<td>";		
		echo "<select class=wymagane name=typid onkeypress='return handleEnter(this, event);'>\n";
		echo "<option value=''>Wybierz z listy...";
		while ($newArray = mysql_fetch_array($result)) 
		 {
			$temp_id  				= $newArray['rola_id'];
			$temp_nazwa				= $newArray['rola_nazwa'];
			echo "<option value=$temp_id>$temp_nazwa</option>\n"; 
		}
		echo "</select>\n"; 
		echo "</td>";
	echo "</tr>";
	tbl_empty_row(2);
	endtable();
	
	echo "<input type=hidden name=block_komorka value='".$_REQUEST[block_komorka]."'>";
	echo "<input type=hidden name=tup1 value='".$_REQUEST[tup1]."'>";
	echo "<input type=hidden name=niedodawaj value='".$_REQUEST[niedodawaj]."'>";
	echo "<input type=hidden name=tdata value='".$_REQUEST[tdata]."'>";
	echo "<input type=hidden name=tuwagi value='".$_REQUEST[tuwagi]."'>";
	echo "<input type=hidden name=hd_zgl_nr value='".$_REQUEST[hd_zgl_nr]."'>";
	echo "<input type=hidden name=tprzesylka value='".$_REQUEST[tprzesylka]."'>";
	echo "<input type=hidden name=ewid_id value='".$_REQUEST[ewid_id]."'>";
	echo "<input type=hidden name=tsn value='".$_REQUEST[tsn]."'>";
	echo "<input type=hidden name=quiet value='".$_REQUEST[quiet]."'>";
	
	echo "<input type=hidden name=ou value='$opis_uszkodzenia'>";
	echo "<input type=hidden name=wc value='$wykonane_czynnosci'>";
	echo "<input type=hidden name=u value='$uwagi'>";
	
	echo "<input type=hidden name=c_1 value='$c_1'>";
	echo "<input type=hidden name=c_2 value='$c_2'>";
	echo "<input type=hidden name=c_3 value='$c_3'>";
	echo "<input type=hidden name=c_4 value='$c_4'>";
	echo "<input type=hidden name=c_5 value='$c_5'>";
	echo "<input type=hidden name=c_6 value='$c_6'>";
	echo "<input type=hidden name=c_7 value='$c_7'>";
	echo "<input type=hidden name=c_8 value='$c_8'>";
	
	//echo "<input type=hidden id=newupid2 name=newupid2 value=$temp_id>";

	echo "<input type=hidden name=source value='".$_REQUEST[source]."'>";
	echo "<input type=hidden name=wykonane_czynnosci value='".urlencode($_REQUEST[wykonane_czynnosci])."'>";
	echo "<input type=hidden name=uwagi value='".urlencode($_REQUEST[uwagi])."'>";
	echo "<input type=hidden name=blank value='".$_REQUEST[blank]."'>";

echo "<input type=hidden name=jedna_umowa value='".$_REQUEST[jedna_umowa]."'>";
echo "<input type=hidden name=tumowa value='".$_REQUEST[tumowa]."'>";
echo "<input type=hidden name=trodzaj value='".$_REQUEST[trodzaj]."'>";
echo "<input type=hidden name=uwagi value='".$_REQUEST[uwagi]."'>";
echo "<input type=hidden name=tstatus value='".$_REQUEST[tstatus]."'>";
echo "<input type=hidden name=ttyp value='".$_REQUEST[ttyp]."'>";
echo "<input type=hidden name=tidf value='".$_REQUEST[tidf]."'>";
echo "<input type=hidden name=tid value='".$_REQUEST[tid]."'>";
echo "<input type=hidden name=tcenaodsp value='".$_REQUEST[tcenaodsp]."'>";
echo "<input type=hidden name=tcena value='".$_REQUEST[tcena]."'>";
echo "<input type=hidden name=tnazwa value='".$_REQUEST[tnazwa]."'>";
echo "<input type=hidden name=zid value='".$_REQUEST[zid]."'>";
echo "<input type=hidden name=zestaw value='".$_REQUEST[zestaw]."'>";
echo "<input type=hidden name=obzp value='".$_REQUEST[obzp]."'>";
echo "<input type=hidden name=edit_towar value='".$_REQUEST[edit_towar]."'>";
echo "<input type=hidden name=edit_zestaw value='".$_REQUEST[edit_zestaw]."'>";
echo "<input type=hidden name=wstecz value='".$_REQUEST[wstecz]."'>";

	startbuttonsarea("right");
	//echo "<input class=buttons type=button onClick='history.go(-1);'; value='Wstecz'>";
	echo "<span style='float:left'>";
	addownlinkbutton2("'Wróć do edycji protokołu'","button","button","utworz_protokol.php?blank=".$_REQUEST[blank]."&dzien=$dd&miesiac=$mm&rok=$rr&c_1=$c_1&c_2=$c_2&c_3=$c_3&c_4=$c_4&c_5=$c_5&c_6=$c_6&c_7=$c_7&c_8=$c_8&up=".urlencode($upnazwa)."&nazwa_urzadzenia=".urlencode($nazwa_urzadzenia)."&sn_urzadzenia=".urlencode($sn_urzadzenia)."&ni_urzadzenia=".urlencode($ni_urzadzenia)."&opis_uszkodzenia=".urlencode($ou)."&wykonane_czynnosci=".urlencode($wc)."&uwagi=".urlencode($uwagi)."&imieinazwisko=&readonly=0&choosefromewid=1&findpion=1&upid=$eup_id&source=".$_REQUEST[source]."&state=".$_REQUEST[state]."&nowy=1&findpion=1&wstecz=1&tup=$eup_id&new_upid=$_REQUEST[new_upid]&jedna_umowa=".urlencode($_REQUEST[jedna_umowa])."&tumowa=".urlencode($_REQUEST[tumowa])."&trodzaj=".urlencode($_REQUEST[trodzaj])."&uwagi=".urlencode($_REQUEST[uwagi])."&tstatus=".urlencode($_REQUEST[tstatus])."&ttyp=".urlencode($_REQUEST[ttyp])."&tidf=".urlencode($_REQUEST[tidf])."&tid=".urlencode($_REQUEST[tid])."&tcenaodsp=".urlencode($_REQUEST[tcenaodsp])."&tcena=".urlencode($_REQUEST[tcena])."&tnazwa=".urlencode($_REQUEST[tnazwa])."&zestaw=".$_REQUEST[zestaw]."&niedodawaj=1&zid=".$_REQUEST[zid]."&obzp=".$_REQUEST[obzp]."&edit_towar=".$_REQUEST[edit_towar]."&edit_zestaw=".$_REQUEST[edit_zestaw]."&wstecz=".$_REQUEST[wstecz]."&tdata=".$_REQUEST[tdata]."&tuwagi=".urlencode($_REQUEST[tuwagi])."&hd_zgl_nr=".$_REQUEST[hd_zgl_nr]."&tprzesylka=".$_REQUEST[tprzesylka]."&tsn=".urlencode($_REQUEST[tsn])."&ewid_id=".$_REQUEST[ewid_id]."&quiet=".$_REQUEST[quiet]."");
	echo "</span>";
	
	//addbuttons("wstecz");
	addownsubmitbutton("'Wybierz z ewidencji'","submit");
	//addownlinkbutton2("'Anuluj wybieranie z ewidencji'","button","button","utworz_protokol.php?c_1=$c_1&c_2=$c_2&c_3=$c_3&c_4=$c_4&c_5=$c_5&c_6=$c_6&c_7=$c_7&c_8=$c_8&opis_uszkodzenia=".urlencode($opis_uszkodzenia)."&wykonane_czynnosci=".urlencode($wykonane_czynnosci)."&uwagi=".urlencode($uwagi)."&choosefromewid=1");
	//addbuttons("anuluj");
	endbuttonsarea();
	_form();	

?>	
<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("addt");
	
	<?php if ($_REQUEST[block_komorka]!=1) { ?>
	frmvalidator.addValidation("new_upid","dontselect=0","Nie wybrałeś komórki / UP");  
	<?php } ?>
  
  frmvalidator.addValidation("typid","dontselect=0","Nie wybrałeś typu sprzętu");  

 // document.getElementById('upid').value=readCookie('utworz_protokol_user_nr<?php echo $es_nr; ?>');
  //alert(readCookie('utworz_protokol_user_nr<?php echo $es_nr; ?>'));
//  document.getElementById('upid').options[1];
  
</script>
<?php 
}
?>
</body>
</html>	