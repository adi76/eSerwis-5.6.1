<?php 
include_once('header_simple.php');
include_once('cfg_helpdesk.php');

if ($_REQUEST[f]=='hide') exit;

$licznik_w = mysql_query("SELECT licznik_id, licznik_opis, licznik_wartosc, licznik_last_update FROM $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]." WHERE (licznik_wartosc>0) LIMIT ".$liczba_wierszy_w_licznikach_w."", $conn_hd);

	while ($lw=mysql_fetch_array($licznik_w)) {
		$temp_i	= $lw['licznik_id'];
		$temp_o	= $lw['licznik_opis'];
		$temp_w = $lw['licznik_wartosc'];
		$temp_d	= $lw['licznik_last_update'];
		
		/*
			1 - pr. rozpoczecia
			2 - pr. zakończenia
			3 - nowe
			4 - rozp. nie zak.
			5 - przypisane
			6 - rozpoczęte
			7 - w firmie
			8 - w serwisie zewn.
			9 - nie zamkniete
			10 - do oddania
			11 - zamknięte
			12 - wszystkie
			13 - awarie krytyczne
			14 - awarie zwykłe
		
		*/
		
		if ($temp_i == 1) $_link = 'hd_p_zgloszenia.php?sa=0&p5=BZ&p6=&s='.$_REQUEST[s].'&hd_rps='.$_REQUEST[hd_rps].'&page=1&p0=R&p2='.$_REQUEST[p2].'&p3='.$_REQUEST[p3].'&p6=X&p7='.$_REQUEST[p7].'&add=&sk='.urlencode($_REQUEST[sk]).'&st='.urlencode($_REQUEST[st]).'&ss='.$_REQUEST[ss].'';
		
		if ($temp_i == 2) $_link = 'hd_p_zgloszenia.php?sa=0&p5=BZ&s='.$_REQUEST[s].'&hd_rps='.$_REQUEST[hd_rps].'&page=1&p0=Z&p2='.$_REQUEST[p2].'&p3='.$_REQUEST[p3].'&p6=X&p7='.$_REQUEST[p7].'&add=&sk='.urlencode($_REQUEST[sk]).'&st='.urlencode($_REQUEST[st]).'&ss='.$_REQUEST[ss].'';
		
		if ($temp_i == 3) $_link = 'hd_p_zgloszenia.php?sa=0&p5=1&s='.$_REQUEST[s].'&hd_rps='.$_REQUEST[hd_rps].'&page=1&p2='.$_REQUEST[p2].'&p3='.$_REQUEST[p3].'&p6=X&p7='.$_REQUEST[p7].'&add=&sk='.urlencode($_REQUEST[sk]).'&st='.urlencode($_REQUEST[st]).'&ss='.$_REQUEST[ss].'';
		
		if ($temp_i == 4) $_link = 'hd_p_zgloszenia.php?sa=0&p5=7&s='.$_REQUEST[s].'&hd_rps='.$_REQUEST[hd_rps].'&page=1&p2='.$_REQUEST[p2].'&p3='.$_REQUEST[p3].'&p6=X&p7='.$_REQUEST[p7].'&add=&sk='.urlencode($_REQUEST[sk]).'&st='.urlencode($_REQUEST[st]).'&ss='.$_REQUEST[ss].'';
		
		if ($temp_i == 5) $_link = 'hd_p_zgloszenia.php?sa=0&p5=2&s='.$_REQUEST[s].'&hd_rps='.$_REQUEST[hd_rps].'&page=1&p2='.$_REQUEST[p2].'&p3='.$_REQUEST[p3].'&p6=X&p7='.$_REQUEST[p7].'&add=&sk='.urlencode($_REQUEST[sk]).'&st='.urlencode($_REQUEST[st]).'&ss='.$_REQUEST[ss].'';
		
		if ($temp_i == 6) $_link = 'hd_p_zgloszenia.php?sa=0&p5=3&s='.$_REQUEST[s].'&hd_rps='.$_REQUEST[hd_rps].'&page=1&p2='.$_REQUEST[p2].'&p3='.$_REQUEST[p3].'&p6=X&p7='.$_REQUEST[p7].'&add=&sk='.urlencode($_REQUEST[sk]).'&st='.urlencode($_REQUEST[st]).'&ss='.$_REQUEST[ss].'';
		
		if ($temp_i == 7) $_link = 'hd_p_zgloszenia.php?sa=0&p5=3B&s='.$_REQUEST[s].'&hd_rps='.$_REQUEST[hd_rps].'&page=1&p2='.$_REQUEST[p2].'&p3='.$_REQUEST[p3].'&p6=X&p7='.$_REQUEST[p7].'&add=&sk='.urlencode($_REQUEST[sk]).'&st='.urlencode($_REQUEST[st]).'&ss='.$_REQUEST[ss].'';
		
		if ($temp_i == 8) $_link = 'hd_p_zgloszenia.php?sa=0&p5=3A&s='.$_REQUEST[s].'&hd_rps='.$_REQUEST[hd_rps].'&page=1&p2='.$_REQUEST[p2].'&p3='.$_REQUEST[p3].'&p6=X&p7='.$_REQUEST[p7].'&add=&sk='.urlencode($_REQUEST[sk]).'&st='.urlencode($_REQUEST[st]).'&ss='.$_REQUEST[ss].'';
		
		if ($temp_i == 9) $_link = 'hd_p_zgloszenia.php?sa=0&p5=4&s='.$_REQUEST[s].'&hd_rps='.$_REQUEST[hd_rps].'&page=1&p2='.$_REQUEST[p2].'&p3='.$_REQUEST[p3].'&p6=X&p7='.$_REQUEST[p7].'&add=&sk='.urlencode($_REQUEST[sk]).'&st='.urlencode($_REQUEST[st]).'&ss='.$_REQUEST[ss].'';

		if ($temp_i == 10) $_link = 'hd_p_zgloszenia.php?sa=0&p5=5&s='.$_REQUEST[s].'&hd_rps='.$_REQUEST[hd_rps].'&page=1&p2='.$_REQUEST[p2].'&p3='.$_REQUEST[p3].'&p6=X&p7='.$_REQUEST[p7].'&add=&sk='.urlencode($_REQUEST[sk]).'&st='.urlencode($_REQUEST[st]).'&ss='.$_REQUEST[ss].'';
		
		if ($temp_i == 11) $_link = 'hd_p_zgloszenia.php?sa=0&p5=BZ&s='.$_REQUEST[s].'&hd_rps='.$_REQUEST[hd_rps].'&page=1&p2='.$_REQUEST[p2].'&p3='.$_REQUEST[p3].'&p6=X&p7='.$_REQUEST[p7].'&add=&sk='.urlencode($_REQUEST[sk]).'&st='.urlencode($_REQUEST[st]).'&ss='.$_REQUEST[ss].'';
		
		if ($temp_i == 12) $_link = 'hd_p_zgloszenia.php?sa=0&p5=6&s='.$_REQUEST[s].'&hd_rps='.$_REQUEST[hd_rps].'&page=1&p2='.$_REQUEST[p2].'&p3='.$_REQUEST[p3].'&p6=X&p7='.$_REQUEST[p7].'&add=&sk='.urlencode($_REQUEST[sk]).'&st='.urlencode($_REQUEST[st]).'&ss='.$_REQUEST[ss].'';
		
		if ($temp_i == 13) $_link = 'hd_p_zgloszenia.php?sa=0&p5=9&s='.$_REQUEST[s].'&hd_rps='.$_REQUEST[hd_rps].'&page=1&p2='.$_REQUEST[p2].'&p3='.$_REQUEST[p3].'&p6=X&p7='.$_REQUEST[p7].'&add=&sk='.urlencode($_REQUEST[sk]).'&st='.urlencode($_REQUEST[st]).'&ss='.$_REQUEST[ss].'';
		
		if ($temp_i == 14) $_link = 'hd_p_zgloszenia.php?sa=0&p5=X&s='.$_REQUEST[s].'&hd_rps='.$_REQUEST[hd_rps].'&page=1&p2='.$_REQUEST[p2].'&p3='.$_REQUEST[p3].'&p6=X&p7='.$_REQUEST[p7].'&add=&sk='.urlencode($_REQUEST[sk]).'&st='.urlencode($_REQUEST[st]).'&ss='.$_REQUEST[ss].'';
		
		if ($temp_i == 15) $_link = 'hd_p_zgloszenia.php?sa=0&p2=6&p5=BZ&s='.$_REQUEST[s].'&hd_rps='.$_REQUEST[hd_rps].'&page=1&p3='.$_REQUEST[p3].'&p6=X&p7='.$_REQUEST[p7].'&add=&sk='.urlencode($_REQUEST[sk]).'&st='.urlencode($_REQUEST[st]).'&ss='.$_REQUEST[ss].'';
		
		if ($temp_i == 16) $_link = 'hd_p_zgloszenia.php?sa=0&p2=2&p5=BZ&s='.$_REQUEST[s].'&hd_rps='.$_REQUEST[hd_rps].'&page=1&p3='.$_REQUEST[p3].'&p6=X&p7='.$_REQUEST[p7].'&add=&sk='.urlencode($_REQUEST[sk]).'&st='.urlencode($_REQUEST[st]).'&ss='.$_REQUEST[ss].'';

		
		echo "<tr style='width=100%; margin:0px; padding:0px;font-weight:normal;'>";
		
		//$_font = "normal";
		//if (strlen($_REQUEST[p6])<=1) {
	//		if ($_REQUEST[p0]=='R') $_font = "bold";
	//		if ($_REQUEST[p0]=='Z') $_font = "bold";
	//		if ($_REQUEST[p5]=='1') $_font = "bold";
		//}
		
		//echo $_font;
		//echo "'>";
		
		echo "<td>";
		
		echo "<a class=normalfont href='$_link'>";
			if (($temp_i==1) || ($temp_i==2) || ($temp_i==15) || ($temp_i==16)) echo "<font color=red>";
			if ($temp_i==3) echo "<font color=green>";
			echo "$temp_o";
			if (($temp_i==1) || ($temp_i==2) || ($temp_i==15) || ($temp_i==16)) echo "</font>";
		echo "</a>";
		
		echo "</td>";
		echo "<td class=right>";

		echo "<a class=normalfont href='$_link'>";
			if (($temp_i==1) || ($temp_i==2) || ($temp_i==15) || ($temp_i==16)) echo "<font color=red>";
			if ($temp_i==3) echo "<font color=green>";			
			echo "<b>$temp_w</b>";
			if (($temp_i==1) || ($temp_i==2) || ($temp_i==15) || ($temp_i==16)) echo "</font>";
		echo "</a>";
		
		echo "</td>";
		
		
		echo "</tr>";
		
	}

	if (($temp_d!='0000-00-00 00:00:00') && ($temp_d!='')) {
		//echo "<tr><td colspan=2 class=center style='font-weight:normal; '><hr /><a id=wz_hd href=# title='Kliknij, aby odświeżyć liczniki wszystkich zgłoszeń' class=normalfont style='color:grey;'  onClick=\"newWindow_r(800,200,'hd_uaktualnij_liczniki.php?f=$_REQUEST[f]&range=W&moj_nr=$_REQUEST[moj_nr]&randval='+Math.random()+'&refresh_parent=0&todiv=1&sourcepage=start');\">Stan na: <font color=#696969>".SubMinutesFromDate2($noofminutes_w,$temp_d)."</font></a></td></tr>";
		echo "<tr><td colspan=2 class=center style='font-weight:normal'><hr />Stan na: <font color=#696969>".SubMinutesFromDate2($noofminutes_w,$temp_d)."</font></td></tr>";
	}
	
?>