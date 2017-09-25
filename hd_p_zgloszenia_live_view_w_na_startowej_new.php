<?php 
include_once('header_simple.php');
include_once('cfg_helpdesk.php');

if ($_REQUEST[f]=='hide') exit;

$licznik_w = mysql_query("SELECT licznik_id, licznik_opis, licznik_wartosc, licznik_last_update FROM $dbname_hd.hd_licznik_zgloszen_".$_REQUEST[f]." WHERE (licznik_wartosc>0) and (licznik_id<>13)", $conn_hd);

	echo "<tr style='width=100%; margin:0px; padding:0px;font-weight:normal'>";
	
	$ik = 0;
	$j = 1;
	
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
			11 - zamkniÄ™te
			12 - wszystkie
			13 - awarie krytyczne
			14 - awarie zwykĹ‚e
		
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
		
		//$_font = "normal";
		//if (strlen($_REQUEST[p6])<=1) {
//			if ($_REQUEST[p0]=='R') $_font = "bold";
//			if ($_REQUEST[p0]=='Z') $_font = "bold";
//			if ($_REQUEST[p5]=='1') $_font = "bold";
		//}
		
		//echo $_font;
		//echo "'>";
		
		echo "<td id=$j class=center style='width:auto; height:".$box_z_licznikiem_height."px; border:1px solid black; background-color:";
		if (($temp_i==1) || ($temp_i==2) || ($temp_i==15) || ($temp_i==16)) {
			echo "#FEE2E2";
		} elseif ($temp_i==3) {
			echo "#E7F9E8";
		} else {
			echo "#E1E1E1";
		}	
		echo "; font-size:".$box_z_licznikiem_fontsize_napis."px; cursor:pointer;' onmouseover=\"document.getElementById('$j').style.backgroundColor = '#FFF000';\" onmouseout=\"document.getElementById('$j').style.backgroundColor = '";
		if (($temp_i==1) || ($temp_i==2) || ($temp_i==15) || ($temp_i==16)) {
			echo "#FEE2E2";
		} elseif ($temp_i==3) {
			echo "#E7F9E8";
		} else {
			echo "#E1E1E1";
		}		
		echo "';\" onClick=\"self.location.href='".$_link."'; return false;\">";
		
			echo "<a class=normalfont href='$_link' title='$temp_o' >";
			
			if (($temp_i==1) || ($temp_i==2) || ($temp_i==15) || ($temp_i==16)) echo "<font color=red>";
			if ($temp_i==3) echo "<font color=green>";
			
			echo "$temp_o";
			echo "<br />";

					switch ($temp_i4) {
						case "1"	: echo "Priorytet rozpoczęcia"; break;
						case "2"	: echo "Priorytet zakończenia"; break;
						case "13"	: echo "Awarie krytyczne"; break;
						case "14"	: echo "Awarie zwykłe"; break;
						
						case "3"	: echo "<input class=imgoption type=image src=img/zgl_nowe.gif>"; break;
						case "5"	: echo "<input class=imgoption type=image src=img/zgl_przypisane.gif>"; break;
						case "6"	: echo "<input class=imgoption type=image src=img/zgl_rozpoczete.gif>"; break;
						case "8"	: echo "<input class=imgoption type=image src=img/zgl_w_serwisie_zewnetrznym.gif>"; break;
						case "7"	: echo "<input class=imgoption type=image src=img/zgl_w_firmie.gif>"; break;
						//case "4"	: echo "<input class=imgoption type=image src=img/zgl_oczekiwanie_na_odpowiedz.gif>"; break;
						//case "5"	: echo "<input class=imgoption type=image src=img/zgl_oczekiwanie_na_sprzet.gif>"; break;
						case "10"	: echo "<input class=imgoption type=image src=img/zgl_do_oddania.gif>"; break;
						case "9"	: echo "NZ"; break;
						case "4"	: echo "<input class=imgoption type=image src=img/zgl_rozpoczete_nie_zakonczone.gif>"; break;
						//case "8"	: echo "<input class=imgoption type=image src=img/zgl_nowe.gif>"; break;
						case "11"	: echo "<input class=imgoption type=image src=img/zgl_zamkniete.gif>"; break;
					}
			if (($temp_i==1) || ($temp_i==2) || ($temp_i==15) || ($temp_i==16)) echo "</font>";
			
			//echo "<br />";
			echo "<div style='height:".$box_z_licznikiem_odstep."px'>&nbsp;</div>";
			if (($temp_i==1) || ($temp_i==2) || ($temp_i==15) || ($temp_i==16)) echo "<font color=red>";
			if ($temp_i==3) echo "<font color=green>";	
			echo "<font size=".$box_z_licznikiem_fontsize.">";
			echo "<b>$temp_w</b>";
			echo "</font>";
			if (($temp_i==1) || ($temp_i==2) || ($temp_i==15) || ($temp_i==16)) echo "</font>";
			echo "</a>";
		echo "</td>";
		$ik++;
		$j++;
	}
	echo "</tr>";
		
	if (($temp_d!='0000-00-00 00:00:00') && ($temp_d!='')) {
		echo "<tr><td colspan=$ik class=left style='font-weight:normal; color:grey; '>";
		echo "<hr />Stan na: <font color=#696969>".SubMinutesFromDate2($noofminutes_w,$temp_d)."</font>";
		
		echo "&nbsp;&nbsp;<input type=button class=buttons value='Odśwież wszystkie liczniki' onClick=\"newWindow_r(800,200,'hd_uaktualnij_liczniki.php?f=$_REQUEST[f]&range=MW&moj_nr=$_REQUEST[moj_nr]&cu=".urlencode($currentuser)."&randval='+Math.random()+'&refresh_parent=1&todiv=0&sourcepage=start'); $('#liczniki_wszystkie_na_startowej').load('hd_p_zgloszenia_live_view_w_na_startowej_new.php?f=$_REQUEST[f]&randval='+ Math.random()+'&range=W&moj_nr=$_REQUEST[moj_nr]&cu=".urlencode($currentuser)."'); $('#liczniki_moje_na_startowej').load('hd_p_zgloszenia_live_view_m_na_startowej_new.php?f=$_REQUEST[f]&randval='+ Math.random()+'&range=M&moj_nr=$_REQUEST[moj_nr]&cu=".urlencode($currentuser)."'); return false;\" />";
		
	/*
		echo "&nbsp;&nbsp;<input type=button class=buttons value='OdĹ›wieĹĽ wszystkie liczniki' onClick=\"	
		
newWindow_r(800,200,'hd_uaktualnij_liczniki.php?f=$es_filia&range=MW&moj_nr=$es_nr&cu=".urlencode($currentuser)."&randval='+Math.random()+'&refresh_parent=1&todiv=0&sourcepage=start');
		
		
		newWindow_r(800,200,'hd_uaktualnij_liczniki.php?f=$_REQUEST[f]&range=W&moj_nr=$es_nr&randval='+Math.random()+'&refresh_parent=1'); return false;\" />";
	*/
	
		echo "</td></tr>";
	}
	
?>