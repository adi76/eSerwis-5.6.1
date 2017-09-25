<?php 
include_once('header_simple.php');
include_once('cfg_helpdesk.php');

if ($_REQUEST[f]=='hide') exit;

$licznik_m = mysql_query("SELECT licznik_id, licznik_opis, licznik_wartosc, licznik_last_update, licznik_osoba FROM $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]."_".$_REQUEST[moj_nr]." WHERE (licznik_wartosc>0) LIMIT ".$liczba_wierszy_w_licznikach_m."", $conn_hd);

	while ($lw=mysql_fetch_array($licznik_m)) {
	
		$temp_i	= $lw['licznik_id'];
		$temp_o	= $lw['licznik_opis'];
		$temp_w = $lw['licznik_wartosc'];
		$temp_d	= $lw['licznik_last_update'];
		$temp_os= $lw['licznik_osoba'];
		
		/*
		
			1 - pr. rozpoczecia
			2 - pr. zakończenia
			3 - rozp. nie zak.
			4 - przypisane
			5 - rozpoczęte
			6 - w firmie
			7 - w serwisie zewn.
			8 - nie zamkniete
			9 - do oddania
			10 - zamknięte
			
			*11 - wszystkie
			*12 - awarie krytyczne
			*13 - awarie zwykł
		
			15 - oczek. na odp. klienta
			16 - oczek. na odp. klienta
	
		*/
		
		if ($temp_i == 1) $_link = 'hd_p_zgloszenia.php?sa=0&p5=BZ&p6=&s='.$_REQUEST[s].'&hd_rps='.$_REQUEST[hd_rps].'&page=1&p0=R&p2='.$_REQUEST[p2].'&p3='.$_REQUEST[p3].'&p6='.urlencode($temp_os).'&p7='.$_REQUEST[p7].'&add=&sk='.urlencode($_REQUEST[sk]).'&st='.urlencode($_REQUEST[st]).'&ss='.$_REQUEST[ss].'';
		
		if ($temp_i == 2) $_link = 'hd_p_zgloszenia.php?sa=0&p5=BZ&s='.$_REQUEST[s].'&hd_rps='.$_REQUEST[hd_rps].'&page=1&p0=Z&p2='.$_REQUEST[p2].'&p3='.$_REQUEST[p3].'&p6='.urlencode($temp_os).'&p7='.$_REQUEST[p7].'&add=&sk='.urlencode($_REQUEST[sk]).'&st='.urlencode($_REQUEST[st]).'&ss='.$_REQUEST[ss].'';
		
		if ($temp_i == 3) $_link = 'hd_p_zgloszenia.php?sa=0&p5=7&s='.$_REQUEST[s].'&hd_rps='.$_REQUEST[hd_rps].'&page=1&p2='.$_REQUEST[p2].'&p3='.$_REQUEST[p3].'&p6='.urlencode($temp_os).'&p7='.$_REQUEST[p7].'&add=&sk='.urlencode($_REQUEST[sk]).'&st='.urlencode($_REQUEST[st]).'&ss='.$_REQUEST[ss].'';
		
		if ($temp_i == 4) $_link = 'hd_p_zgloszenia.php?sa=0&p5=2&s='.$_REQUEST[s].'&hd_rps='.$_REQUEST[hd_rps].'&page=1&p2='.$_REQUEST[p2].'&p3='.$_REQUEST[p3].'&p6='.urlencode($temp_os).'&p7='.$_REQUEST[p7].'&add=&sk='.urlencode($_REQUEST[sk]).'&st='.urlencode($_REQUEST[st]).'&ss='.$_REQUEST[ss].'';
		
		if ($temp_i == 5) $_link = 'hd_p_zgloszenia.php?sa=0&p5=3&s='.$_REQUEST[s].'&hd_rps='.$_REQUEST[hd_rps].'&page=1&p2='.$_REQUEST[p2].'&p3='.$_REQUEST[p3].'&p6='.urlencode($temp_os).'&p7='.$_REQUEST[p7].'&add=&sk='.urlencode($_REQUEST[sk]).'&st='.urlencode($_REQUEST[st]).'&ss='.$_REQUEST[ss].'';
		
		if ($temp_i == 6) $_link = 'hd_p_zgloszenia.php?sa=0&p5=3B&s='.$_REQUEST[s].'&hd_rps='.$_REQUEST[hd_rps].'&page=1&p2='.$_REQUEST[p2].'&p3='.$_REQUEST[p3].'&p6='.urlencode($temp_os).'&p7='.$_REQUEST[p7].'&add=&sk='.urlencode($_REQUEST[sk]).'&st='.urlencode($_REQUEST[st]).'&ss='.$_REQUEST[ss].'';
		
		if ($temp_i == 7) $_link = 'hd_p_zgloszenia.php?sa=0&p5=3A&s='.$_REQUEST[s].'&hd_rps='.$_REQUEST[hd_rps].'&page=1&p2='.$_REQUEST[p2].'&p3='.$_REQUEST[p3].'&p6='.urlencode($temp_os).'&p7='.$_REQUEST[p7].'&add=&sk='.urlencode($_REQUEST[sk]).'&st='.urlencode($_REQUEST[st]).'&ss='.$_REQUEST[ss].'';
		
		if ($temp_i == 8) $_link = 'hd_p_zgloszenia.php?sa=0&p5=4&s='.$_REQUEST[s].'&hd_rps='.$_REQUEST[hd_rps].'&page=1&p3='.$_REQUEST[p3].'&p6='.urlencode($temp_os).'&p7='.$_REQUEST[p7].'&add=&sk='.urlencode($_REQUEST[sk]).'&st='.urlencode($_REQUEST[st]).'&ss='.$_REQUEST[ss].'';
		
		if ($temp_i == 9) $_link = 'hd_p_zgloszenia.php?sa=0&p5=5&s='.$_REQUEST[s].'&hd_rps='.$_REQUEST[hd_rps].'&page=1&p3='.$_REQUEST[p3].'&p6='.urlencode($temp_os).'&p7='.$_REQUEST[p7].'&add=&sk='.urlencode($_REQUEST[sk]).'&st='.urlencode($_REQUEST[st]).'&ss='.$_REQUEST[ss].'';		
		
		if ($temp_i == 10) $_link = 'hd_p_zgloszenia.php?sa=0&p5=BZ&s='.$_REQUEST[s].'&hd_rps='.$_REQUEST[hd_rps].'&page=1&p2='.$_REQUEST[p2].'&p3='.$_REQUEST[p3].'&p6='.urlencode($temp_os).'&p7='.$_REQUEST[p7].'&add=&sk='.urlencode($_REQUEST[sk]).'&st='.urlencode($_REQUEST[st]).'&ss='.$_REQUEST[ss].'';
		
		if ($temp_i == 11) $_link = 'hd_p_zgloszenia.php?sa=0&p5=6&s='.$_REQUEST[s].'&hd_rps='.$_REQUEST[hd_rps].'&page=1&p2='.$_REQUEST[p2].'&p3='.$_REQUEST[p3].'&p6='.urlencode($temp_os).'&p7='.$_REQUEST[p7].'&add=&sk='.urlencode($_REQUEST[sk]).'&st='.urlencode($_REQUEST[st]).'&ss='.$_REQUEST[ss].'';
		
		if ($temp_i == 12) $_link = 'hd_p_zgloszenia.php?sa=0&p5=9&s='.$_REQUEST[s].'&hd_rps='.$_REQUEST[hd_rps].'&page=1&p2='.$_REQUEST[p2].'&p3='.$_REQUEST[p3].'&p6='.urlencode($temp_os).'&p7='.$_REQUEST[p7].'&add=&sk='.urlencode($_REQUEST[sk]).'&st='.urlencode($_REQUEST[st]).'&ss='.$_REQUEST[ss].'';
		
		if ($temp_i == 13) $_link = 'hd_p_zgloszenia.php?sa=0&p5=&s='.$_REQUEST[s].'&hd_rps='.$_REQUEST[hd_rps].'&page=1&p2='.$_REQUEST[p2].'&p3='.$_REQUEST[p3].'&p6='.urlencode($temp_os).'&p7='.$_REQUEST[p7].'&add=&sk='.urlencode($_REQUEST[sk]).'&st='.urlencode($_REQUEST[st]).'&ss='.$_REQUEST[ss].'';
		
		if ($temp_i == 14) $_link = 'hd_p_zgloszenia.php?sa=0&p5=BZ&s='.$_REQUEST[s].'&hd_rps='.$_REQUEST[hd_rps].'&page=1&p2=6&p3='.$_REQUEST[p3].'&p6='.urlencode($temp_os).'&p7='.$_REQUEST[p7].'&add=&sk='.urlencode($_REQUEST[sk]).'&st='.urlencode($_REQUEST[st]).'&ss='.$_REQUEST[ss].'';
		
		if ($temp_i == 15) $_link = 'hd_p_zgloszenia.php?sa=0&p2=2&p5=BZ&s='.$_REQUEST[s].'&hd_rps='.$_REQUEST[hd_rps].'&page=1&p3='.$_REQUEST[p3].'&p6='.urlencode($temp_os).'&p7='.$_REQUEST[p7].'&add=&sk='.urlencode($_REQUEST[sk]).'&st='.urlencode($_REQUEST[st]).'&ss='.$_REQUEST[ss].'';
		
		echo "<tr style='width=100%; margin:0px; padding:0px;font-weight:normal'>";
		echo "<td>";
		
		echo "<a class=normalfont href='$_link'>";
			if (($temp_i==1) || ($temp_i==2) || ($temp_i==14) || ($temp_i==15)) echo "<font color=red>";
			//if ($temp_i==3) echo "<font color=green>";
			echo "$temp_o";
			if (($temp_i==1) || ($temp_i==2) || ($temp_i==14) || ($temp_i==15)) echo "</font>";
		echo "</a>";
		
		echo "</td>";
		echo "<td class=right>";

		echo "<a class=normalfont href='$_link'>";
			if (($temp_i==1) || ($temp_i==2) || ($temp_i==14) || ($temp_i==15)) echo "<font color=red>";
			//if ($temp_i==3) echo "<font color=green>";			
			echo "<b>$temp_w</b>";
			if (($temp_i==1) || ($temp_i==2) || ($temp_i==14) || ($temp_i==15)) echo "</font>";
		echo "</a>";
				
		echo "</td>";
		
		
		echo "</tr>";
		
	}
	
	if (($temp_d!='0000-00-00 00:00:00') && ($temp_d!='')) {
	//		echo "<tr><td colspan=2 class=center style='font-weight:normal; '><hr /><a href=# title='Kliknij, aby odświeżyć liczniki moich zgłoszeń' class=normalfont style='color:grey;'  onClick=\"newWindow_r(800,200,'hd_uaktualnij_liczniki.php?f=$_REQUEST[f]&range=M&moj_nr=$_REQUEST[moj_nr]&cu=".urlencode($temp_os)."&randval='+Math.random()+'&refresh_parent=1');\">Stan na: <font color=#696969>".SubMinutesFromDate2($noofminutes_w,$temp_d)."</font></a></td></tr>";	
			echo "<tr><td colspan=2 class=center style='font-weight:normal'><hr />Stan na: <font color=#696969>".SubMinutesFromDate2($noofminutes_m,$temp_d)."</font></td></tr>";
	}
	
?>