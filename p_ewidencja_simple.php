<?php include_once('header.php'); ?>
<body>
<?php
$komorka='';
$result7a = mysql_query("SELECT up_nazwa FROM $dbname.serwis_komorki WHERE up_id='$_REQUEST[upid]' LIMIT 1", $conn) or die($k_b);
list($komorka)=mysql_fetch_array($result7a);

$result7a = mysql_query("SELECT * FROM $dbname.serwis_ewidencja WHERE (ewidencja_up_nazwa='$komorka') and (belongs_to=$es_filia) ORDER BY ewidencja_typ", $conn) or die($k_b);
if (mysql_num_rows($result7a)!=0) {
	$i = 0;
	pageheader("Wykaz sprzętu wg ewidencji z",0,0);
	infoheader("<b>$_REQUEST[komorka]</b>");
	starttable();
	echo "<tr><th>Informacje o sprzęcie (SN, NI)</th><th class=center>Opcje</th></tr>";
	while ($dane = mysql_fetch_array($result7a)) {
		$eid 		= $dane['ewidencja_id'];					$etyp_id	= $dane['ewidencja_typ'];
		$etyp_nazwa	= $dane['ewidencja_typ_nazwa'];				$eup_id		= $dane['ewidencja_up_id'];
		$euser		= $dane['ewidencja_uzytkownik'];			$enrpok		= $dane['ewidencja_nr_pokoju'];
		$enizest	= $dane['ewidencja_zestaw_ni'];				$eknazwa	= $dane['ewidencja_komputer_nazwa'];
		$ekopis		= $dane['ewidencja_komputer_opis'];			$eksn		= $dane['ewidencja_komputer_sn'];
		$ekip		= $dane['ewidencja_komputer_ip'];			$eke		= $dane['ewidencja_komputer_endpoint'];
		$emo		= $dane['ewidencja_monitor_opis'];			$emsn		= $dane['ewidencja_monitor_sn'];
		$edo		= $dane['ewidencja_drukarka_opis'];			$edsn		= $dane['ewidencja_drukarka_sn'];
		$edni		= $dane['ewidencja_drukarka_ni'];			$eu			= $dane['ewidencja_uwagi'];
		$es			= $dane['ewidencja_status'];				$eo_id		= $dane['ewidencja_oprogramowanie'];
		$emoduser 	= $dane['ewidencja_modyfikacja_user'];		$emoddata	= $dane['ewidencja_modyfikacja_date'];
		$ekonf		= $dane['ewidencja_konfiguracja'];			$egwarancja	= $dane['ewidencja_gwarancja_do'];
		$egwarancjakto	= $dane['ewidencja_gwarancja_kto'];		$drukarkapow = $dane['ewidencja_drukarka_powiaz_z'];
		
		tbl_tr_highlight($i);
	
			$temp_tresc1 = '';
			$temp_tresc2 = '';
			
			td_(";l");
				echo "$etyp_nazwa ";
				$temp_tresc1 .= $etyp_nazwa." "; 
				
				if (($etyp_id=='1') || ($etyp_id=='2') || ($etyp_id=='18')) {
					// komputer lub serwer
					echo "$ekopis ";
					$temp_tresc1 .= $ekopis." ";
					
					echo " (SN: $eksn";
					if ($enizest!='') {
						echo ", NI: ".$enizest."";
					}
					echo ")";					
					
					$temp_tresc1 .= " (SN: ".$eksn.""; 					
					if ($enizest!='') $temp_tresc1 .= ", NI: ".$enizest."";
					$temp_tresc1 .= ") ";
					
					if ($emo!='') {
						echo "<font color=grey><br />Monitor ";
						echo "$emo";
						$temp_tresc2 .= "Monitor ".$emo; 
						
						echo " (SN: $emsn";
						if ($enizest!='') {
							echo ", NI: ".$enizest."";
						}
						echo ")";						
						echo "</font>";
						
						$temp_tresc2 .= " (SN: ".$emsn.""; 
					
						if ($enizest!='') $temp_tresc2 .= ", NI: ".$enizest."";
						$temp_tresc2 .= ") ";
						
						echo "</font>";
					}
				}
				
				if ($etyp_id=='3') {
					echo "$edo";
					$temp_tresc1 .= $edo.""; 
					
					echo " (SN: $edsn";
					if ($edni!='') {
						echo ", NI: ".$edni."";
					}
					echo ")";
					
					$temp_tresc1 .= " (SN: ".$edsn.""; 
					
					if ($edni!='') $temp_tresc1 .= ", NI: ".$edni."";
					$temp_tresc1 .= ") ";
					
				}
				
			_td();
					
			td_(";c");			
				
				if ($_REQUEST[alternative]!=1) {

					if ($temp_tresc1!='') {
						echo "<a title=' Dodaj do treści zgłoszenia'><input class=imgoption type=image src=img/ok.gif  onclick=\"if (confirm('Dodać wybrane informacje do treści zgłoszenia ?')) { DodajTresc('".nl2br2($temp_tresc1)."'); }\"></a>";
						
						echo "<a title=' Nadpisz treść zgłoszenia'><input class=imgoption type=image src=img/ok1.gif  onclick=\"if (confirm('Nadpisać treść zgłoszenia wybranymi informacjami ?')) { NadpiszTresc('".nl2br2($temp_tresc1)."'); }\"></a>";
					}
					
					if ($temp_tresc2!='') {
						echo "<br /><a title=' Dodaj do treści zgłoszenia dane o monitorze'><input class=imgoption type=image src=img/ok.gif  onclick=\"if (confirm('Dodać wybrane informacje o monitorze do treści zgłoszenia ?')) { DodajTresc('".nl2br2($temp_tresc2)."'); } \"></a>";
						
						echo "<a title=' Nadpisz treść zgłoszenia informacjami o monitorze'><input class=imgoption type=image src=img/ok1.gif  onclick=\"if (confirm('Nadpisać treść zgłoszenia wybranymi informacjami o monitorze ?')) { NadpiszTresc('".nl2br2($temp_tresc2)."'); }\"></a>";
					}
					
				} else {
					if ($temp_tresc1!='') {
						echo "<a title=' Dodaj do treści zgłoszenia'><input class=imgoption type=image src=img/ok.gif  onclick=\"if (confirm('Dodać wybrane informacje do treści zgłoszenia ?')) { DodajTrescA('".nl2br2($temp_tresc1)."'); }\"></a>";
						
						echo "<a title=' Nadpisz treść zgłoszenia'><input class=imgoption type=image src=img/ok1.gif  onclick=\"if (confirm('Nadpisać treść zgłoszenia wybranymi informacjami ?')) { NadpiszTrescA('".nl2br2($temp_tresc1)."'); }\"></a>";
					}
					
					if ($temp_tresc2!='') {
						echo "<br /><a title=' Dodaj do treści zgłoszenia dane o monitorze'><input class=imgoption type=image src=img/ok.gif  onclick=\"if (confirm('Dodać wybrane informacje o monitorze do treści zgłoszenia ?')) { DodajTrescA('".nl2br2($temp_tresc2)."'); } \"></a>";
						
						echo "<a title=' Nadpisz treść zgłoszenia informacjami o monitorze'><input class=imgoption type=image src=img/ok1.gif  onclick=\"if (confirm('Nadpisać treść zgłoszenia wybranymi informacjami o monitorze ?')) { NadpiszTrescA('".nl2br2($temp_tresc2)."'); }\"></a>";
					}				
				}
			_td();
			
		_tr();	
		$i++;
	}
	endtable();
	
} else {
	errorheader("W ewidencji sprzętu brak jest pozycji z tej lokalizacji");
}
startbuttonsarea("right");
addbuttons("zamknij");
endbuttonsarea();
?>
</body>
</html>