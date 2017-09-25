<?php include_once('header.php'); ?>
<body>
<?php
pageheader("Komórki z błędnie wprowadzonymi godzinami pracy",1);
?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
$sql="SELECT * FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (belongs_to=$es_filia) AND (up_active=1) AND  (serwis_komorki.up_pion_id=serwis_piony.pion_id) ORDER BY up_nazwa ASC";

$result = mysql_query($sql, $conn) or die($k_b);
$count_rows = mysql_num_rows($result);

if ($count_rows!=0) {

	echo "<table id=tabela cellspacing=1 class=maxwidth align=center style=width:100%>";
	th("30;c;LP|;;Nazwa|;;Godziny pracy|;c;Opcje",$es_prawa);
	
	$i = 1;	
	while ($newArray = mysql_fetch_array($result)) {
		$temp_id  						= $newArray['up_id'];
		$temp_pion						= $newArray['pion_nazwa'];
		$temp_nazwa						= $newArray['up_nazwa'];
		$temp_working_time				= $newArray['up_working_time'];	
		$temp_working_time_alt			= $newArray['up_working_time_alternative'];	
		$temp_working_time_start_date	= $newArray['up_working_time_alternative_start_date'];		
		$temp_working_time_stop_date	= $newArray['up_working_time_alternative_stop_date'];			
		
		$days = explode(";",$temp_working_time);	
		$days_alt = explode(";",$temp_working_time_alt);
		
		$oneday1 = explode("@",$days[0]); 
		$oneday2 = explode("@",$days[1]); 
		$oneday3 = explode("@",$days[2]); 
		$oneday4 = explode("@",$days[3]); 
		$oneday5 = explode("@",$days[4]); 
		$oneday6 = explode("@",$days[5]); 
		$oneday7 = explode("@",$days[6]); 

		$oneday1a = explode("@",$days_alt[0]); 
		$oneday2a = explode("@",$days_alt[1]); 
		$oneday3a = explode("@",$days_alt[2]); 
		$oneday4a = explode("@",$days_alt[3]); 
		$oneday5a = explode("@",$days_alt[4]); 
		$oneday6a = explode("@",$days_alt[5]); 
		$oneday7a = explode("@",$days_alt[6]); 

		$gp_sa = 1;
		if (($oneday1[1]=='') && ($oneday2[1]=='') && ($oneday3[1]=='') && ($oneday4[1]=='') && ($oneday5[1]=='') && ($oneday6[1]=='') && ($oneday7[1]=='')) $gp_sa = 0;

		$gp_alt_sa = 1;
		if (($oneday1a[1]=='') && ($oneday2a[1]=='') && ($oneday3a[1]=='') && ($oneday4a[1]=='') && ($oneday5a[1]=='') && ($oneday6a[1]=='') && ($oneday7a[1]=='')) $gp_alt_sa = 0;
		
		if ($gp_sa==1) {
			$poprawnosc = 0;
			
			// sprawdź czy długość wpisanej godziny jest równa 11 (czyli odpowiada masce gg:mm-gg:mm) lub jest to dzień pusty (czyli = '-')
			if ((strlen($oneday1[1])==11) || ($oneday1[1]=='-')) $poprawnosc++;
			if ((strlen($oneday2[1])==11) || ($oneday2[1]=='-')) $poprawnosc++;
			if ((strlen($oneday3[1])==11) || ($oneday3[1]=='-')) $poprawnosc++;
			if ((strlen($oneday4[1])==11) || ($oneday4[1]=='-')) $poprawnosc++;
			if ((strlen($oneday5[1])==11) || ($oneday5[1]=='-')) $poprawnosc++;
			if ((strlen($oneday6[1])==11) || ($oneday6[1]=='-')) $poprawnosc++;
			if ((strlen($oneday7[1])==11) || ($oneday7[1]=='-')) $poprawnosc++; 
			
			// sprawdź czy są poprawnie wpisane godziny
			if ($poprawnosc==7) {
				
				// godziny ok
				if (((strlen($oneday1[1])==11) && (intval(substr($oneday1[1],0,2))>=0) && (intval(substr($oneday1[1],0,2))<24)) || ($oneday1[1]=='-')) $poprawnosc++;
				if (((strlen($oneday2[1])==11) && (intval(substr($oneday2[1],0,2))>=0) && (intval(substr($oneday2[1],0,2))<24)) || ($oneday2[1]=='-')) $poprawnosc++; 
				if (((strlen($oneday3[1])==11) && (intval(substr($oneday3[1],0,2))>=0) && (intval(substr($oneday3[1],0,2))<24)) || ($oneday3[1]=='-')) $poprawnosc++;
				if (((strlen($oneday4[1])==11) && (intval(substr($oneday4[1],0,2))>=0) && (intval(substr($oneday4[1],0,2))<24)) || ($oneday4[1]=='-')) $poprawnosc++;
				if (((strlen($oneday5[1])==11) && (intval(substr($oneday5[1],0,2))>=0) && (intval(substr($oneday5[1],0,2))<24)) || ($oneday5[1]=='-')) $poprawnosc++;
				if (((strlen($oneday6[1])==11) && (intval(substr($oneday6[1],0,2))>=0) && (intval(substr($oneday6[1],0,2))<24)) || ($oneday6[1]=='-')) $poprawnosc++;
				if (((strlen($oneday7[1])==11) && (intval(substr($oneday7[1],0,2))>=0) && (intval(substr($oneday7[1],0,2))<24)) || ($oneday7[1]=='-')) $poprawnosc++;
				
				// dwukropek ok
				if ($poprawnosc==14) {
					if ((substr($oneday1[1],2,1)==':') || ($oneday1[1]=='-')) $poprawnosc++;
					if ((substr($oneday2[1],2,1)==':') || ($oneday2[1]=='-')) $poprawnosc++;
					if ((substr($oneday3[1],2,1)==':') || ($oneday3[1]=='-')) $poprawnosc++;
					if ((substr($oneday4[1],2,1)==':') || ($oneday4[1]=='-')) $poprawnosc++;
					if ((substr($oneday5[1],2,1)==':') || ($oneday5[1]=='-')) $poprawnosc++;
					if ((substr($oneday6[1],2,1)==':') || ($oneday6[1]=='-')) $poprawnosc++;
					if ((substr($oneday7[1],2,1)==':') || ($oneday7[1]=='-')) $poprawnosc++;
				}

				// minuty ok
				if ($poprawnosc==21) {
					if (((strlen($oneday1[1])==11) && (intval(substr($oneday1[1],3,2))>=0) && (intval(substr($oneday1[1],3,2))<=59)) || ($oneday1[1]=='-')) $poprawnosc++;
					if (((strlen($oneday2[1])==11) && (intval(substr($oneday2[1],3,2))>=0) && (intval(substr($oneday2[1],3,2))<=59)) || ($oneday2[1]=='-')) $poprawnosc++;
					if (((strlen($oneday3[1])==11) && (intval(substr($oneday3[1],3,2))>=0) && (intval(substr($oneday3[1],3,2))<=59)) || ($oneday3[1]=='-')) $poprawnosc++;
					if (((strlen($oneday4[1])==11) && (intval(substr($oneday4[1],3,2))>=0) && (intval(substr($oneday4[1],3,2))<=59)) || ($oneday4[1]=='-')) $poprawnosc++;
					if (((strlen($oneday5[1])==11) && (intval(substr($oneday5[1],3,2))>=0) && (intval(substr($oneday5[1],3,2))<=59)) || ($oneday5[1]=='-')) $poprawnosc++;
					if (((strlen($oneday6[1])==11) && (intval(substr($oneday6[1],3,2))>=0) && (intval(substr($oneday6[1],3,2))<=59)) || ($oneday6[1]=='-')) $poprawnosc++;
					if (((strlen($oneday7[1])==11) && (intval(substr($oneday7[1],3,2))>=0) && (intval(substr($oneday7[1],3,2))<=59)) || ($oneday7[1]=='-')) $poprawnosc++;
				}
				
			}
		}

		// jeżeli $poprawnosc == 28 ==> godziny pracy są OK
		
		// ************************
		// * GODZINY ALTERNATYWNE *
		// ************************
		
		if ($gp_alt_sa==1) {
			$poprawnosc2 = 0;

			// sprawdź daty obowiązywania godzin alternatywnych
			//echo $temp_working_time_start_date." ".$temp_working_time_stop_date."<br />";
			//echo "|".(substr($temp_working_time_start_date,5,2))."";
			//echo "".(substr($temp_working_time_start_date,8,2))."|";
			//echo "|".(substr($temp_working_time_stop_date,5,2))."";
			//echo "".(substr($temp_working_time_stop_date,8,2))."|";
			
			// data start
			if ((strlen($temp_working_time_start_date)==10) && (intval(substr($temp_working_time_start_date,5,2))>=1) && (intval(substr($temp_working_time_start_date,5,2))<=12) && (intval(substr($temp_working_time_start_date,8,2))>=1) && (intval(substr($temp_working_time_start_date,8,2))<=31)) $poprawnosc2++;
			
			if ($poprawnosc2!=1) $error1 = 'Błędnie wpisana data rozpoczęcia obowiązywania godzin alternatywnych';
			
			// data stop
			if ($poprawnosc2==1) {
				if (((strlen($temp_working_time_stop_date)==10) && (intval(substr($temp_working_time_stop_date,5,2))>=1) && (intval(substr($temp_working_time_stop_date,5,2))<=12) && (intval(substr($temp_working_time_stop_date,8,2))>=1) && (intval(substr($temp_working_time_stop_date,8,2))<=31))) $poprawnosc2++;
			}

			if ($poprawnosc2!=2) $error2 = 'Błędnie wpisana data zakończenia obowiązywania godzin alternatywnych';
			
			// sprawdź czy długość wpisanej godziny jest równa 11 (czyli odpowiada masce gg:mm-gg:mm) lub jest to dzień pusty (czyli = '-')
			if ((strlen($oneday1a[1])==11) || ($oneday1a[1]=='-')) $poprawnosc2++;
			if ((strlen($oneday2a[1])==11) || ($oneday2a[1]=='-')) $poprawnosc2++;
			if ((strlen($oneday3a[1])==11) || ($oneday3a[1]=='-')) $poprawnosc2++;
			if ((strlen($oneday4a[1])==11) || ($oneday4a[1]=='-')) $poprawnosc2++;
			if ((strlen($oneday5a[1])==11) || ($oneday5a[1]=='-')) $poprawnosc2++;
			if ((strlen($oneday6a[1])==11) || ($oneday6a[1]=='-')) $poprawnosc2++;
			if ((strlen($oneday7a[1])==11) || ($oneday7a[1]=='-')) $poprawnosc2++;
			
			// sprawdź czy są poprawnie wpisane godziny
			if ($poprawnosc2==9) {
				
				// godziny ok
				if (((strlen($oneday1a[1])==11) && (intval(substr($oneday1a[1],0,2))>=0) && (intval(substr($oneday1a[1],0,2))<24)) || ($oneday1a[1]=='-')) $poprawnosc2++;
				if (((strlen($oneday2a[1])==11) && (intval(substr($oneday2a[1],0,2))>=0) && (intval(substr($oneday2a[1],0,2))<24)) || ($oneday2a[1]=='-')) $poprawnosc2++;
				if (((strlen($oneday3a[1])==11) && (intval(substr($oneday3a[1],0,2))>=0) && (intval(substr($oneday3a[1],0,2))<24)) || ($oneday3a[1]=='-')) $poprawnosc2++;
				if (((strlen($oneday4a[1])==11) && (intval(substr($oneday4a[1],0,2))>=0) && (intval(substr($oneday4a[1],0,2))<24)) || ($oneday4a[1]=='-')) $poprawnosc2++;
				if (((strlen($oneday5a[1])==11) && (intval(substr($oneday5a[1],0,2))>=0) && (intval(substr($oneday5a[1],0,2))<24)) || ($oneday5a[1]=='-')) $poprawnosc2++;
				if (((strlen($oneday6a[1])==11) && (intval(substr($oneday6a[1],0,2))>=0) && (intval(substr($oneday6a[1],0,2))<24)) || ($oneday6a[1]=='-')) $poprawnosc2++;
				if (((strlen($oneday7a[1])==11) && (intval(substr($oneday7a[1],0,2))>=0) && (intval(substr($oneday7a[1],0,2))<24)) || ($oneday7a[1]=='-')) $poprawnosc2++;
				
				// dwukropek ok
				if ($poprawnosc2==16) {
					if ((substr($oneday1[1],2,1)==':') || ($oneday1a[1]=='-')) $poprawnosc2++;
					if ((substr($oneday1[1],2,1)==':') || ($oneday2a[1]=='-')) $poprawnosc2++;
					if ((substr($oneday1[1],2,1)==':') || ($oneday3a[1]=='-')) $poprawnosc2++;
					if ((substr($oneday1[1],2,1)==':') || ($oneday4a[1]=='-')) $poprawnosc2++;
					if ((substr($oneday1[1],2,1)==':') || ($oneday5a[1]=='-')) $poprawnosc2++;
					if ((substr($oneday1[1],2,1)==':') || ($oneday6a[1]=='-')) $poprawnosc2++;
					if ((substr($oneday1[1],2,1)==':') || ($oneday7a[1]=='-')) $poprawnosc2++;
				}
				
				// minuty ok
				if ($poprawnosc2==23) {
					if (((strlen($oneday1a[1])==11) && (intval(substr($oneday1a[1],3,2))>=0) && (intval(substr($oneday1a[1],3,2))<=59)) || ($oneday1a[1]=='-')) $poprawnosc2++;
					if (((strlen($oneday2a[1])==11) && (intval(substr($oneday2a[1],3,2))>=0) && (intval(substr($oneday2a[1],3,2))<=59)) || ($oneday2a[1]=='-'))  $poprawnosc2++;
					if (((strlen($oneday3a[1])==11) && (intval(substr($oneday3a[1],3,2))>=0) && (intval(substr($oneday3a[1],3,2))<=59)) || ($oneday3a[1]=='-'))  $poprawnosc2++;
					if (((strlen($oneday4a[1])==11) && (intval(substr($oneday4a[1],3,2))>=0) && (intval(substr($oneday4a[1],3,2))<=59)) || ($oneday4a[1]=='-'))  $poprawnosc2++;
					if (((strlen($oneday5a[1])==11) && (intval(substr($oneday5a[1],3,2))>=0) && (intval(substr($oneday5a[1],3,2))<=59)) || ($oneday5a[1]=='-'))  $poprawnosc2++;
					if (((strlen($oneday6a[1])==11) && (intval(substr($oneday6a[1],3,2))>=0) && (intval(substr($oneday6a[1],3,2))<=59)) || ($oneday6a[1]=='-'))  $poprawnosc2++;
					if (((strlen($oneday7a[1])==11) && (intval(substr($oneday7a[1],3,2))>=0) && (intval(substr($oneday7a[1],3,2))<=59)) || ($oneday7a[1]=='-'))  $poprawnosc2++;
				}
			}
		}	
		
		// jeżeli $poprawnosc2 == 30 ==> godziny pracy alternatywne są OK
		
		if (($poprawnosc<28) && ($temp_working_time!='')) {
			tbl_tr_highlight($i);
			echo "<td>$i</td>";
			echo "<td>$temp_pion $temp_nazwa</td>";
			echo "<td>";
				echo "<table>";
				echo "<tr style='background-color:#B4B4B4'>";
					echo "<td width=80 class=center>PN</td><td width=80 class=center>WT</td><td width=80 class=center>ŚR</td><td width=80 class=center>CZ</td><td width=80 class=center>PT</td><td width=80 class=center>SO</td><td width=80 class=center>NI</td>";
				echo "</tr>";
				echo "<tr style='background-color:#FFFF7F'>";
					echo "<td class=center><b>$oneday1a[1]</b></td>";
					echo "<td class=center><b>$oneday2a[1]</b></td>";
					echo "<td class=center><b>$oneday3a[1]</b></td>";
					echo "<td class=center><b>$oneday4a[1]</b></td>";
					echo "<td class=center><b>$oneday5a[1]</b></td>";
					echo "<td class=center><b>$oneday6a[1]</b></td>";
					echo "<td class=center><b>$oneday7a[1]</b></td>";
				echo "</tr>";
				echo "</table>";			
			echo "</td>";
			echo "<td class=center>";
			echo "<a title=' Edycja danych o $temp_nazwa '><input class=imgoption type=image src=img/edit.gif onclick=\"newWindow_r(800,600,'e_komorka.php?select_id=$temp_id'); return false;\"></a>";
			echo "</td>";
			echo "</tr>";
			$i++;
		}
		
		if (($poprawnosc==28) && ($poprawnosc2<30) && ($temp_working_time!='') && ($temp_working_time_alt!='')) {
			tbl_tr_highlight($i);
			echo "<td>$i</td>";
			echo "<td>$temp_pion $temp_nazwa</td>";
			echo "<td>";			
				echo "Okres obowiązywania godzin alternatywnych: <b>".date('Y')."-".substr($temp_working_time_start_date,5,5)." - ".date('Y')."-".substr($temp_working_time_stop_date,5,5)."</b>";
				echo "<table>";
				echo "<tr style='background-color:#B4B4B4'>";
					echo "<td width=80 class=center>PN</td><td width=80 class=center>WT</td><td width=80 class=center>ŚR</td><td width=80 class=center>CZ</td><td width=80 class=center>PT</td><td width=80 class=center>SO</td><td width=80 class=center>NI</td>";
				echo "</tr>";
				echo "<tr style='background-color:#FFAA7F'>";
					echo "<td class=center><b>$oneday1a[1]</b></td>";
					echo "<td class=center><b>$oneday2a[1]</b></td>";
					echo "<td class=center><b>$oneday3a[1]</b></td>";
					echo "<td class=center><b>$oneday4a[1]</b></td>";
					echo "<td class=center><b>$oneday5a[1]</b></td>";
					echo "<td class=center><b>$oneday6a[1]</b></td>";
					echo "<td class=center><b>$oneday7a[1]</b></td>";
				echo "</tr>";
				echo "</table>";
			echo "</td>";
			echo "<td class=center>";
			echo "<a title=' Edycja danych o $temp_nazwa '><input class=imgoption type=image src=img/edit.gif onclick=\"newWindow_r(800,600,'e_komorka.php?select_id=$temp_id'); return false;\"></a>";
			echo "</td>";
			echo "</tr>";
			$i++;				
		}
	}
	
	endtable();
}

echo "<h2 id=info style='display:none'>Brak komórek z błędnie wpisanymi godzinami pracy</h2>";

startbuttonsarea("right");
addbuttons("zamknij");
endbuttonsarea();

if ($i==1) {
?>
<script>
	document.getElementById('tabela').style.display='none';
	document.getElementById('info').style.display='';
</script>
<?php } ?>
<script>HideWaitingMessage();</script>
</body>
</html>