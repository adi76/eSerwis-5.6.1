<?php
require_once('cfg_eserwis.php');

if ($submit) {

/*

	$data1 = '2011-08-22 01:00:00';		
	$data2 = '2011-08-24 22:30:00';		
*/

	$data1 = $_POST['d1'];
	$data2 = $_POST['d2'];
	$wt = $_POST['w'];
	$serwis_wt = $_POST['sw'];

	echo "Data rozpoczecia kroku 1: <b>$data1</b><br />"; ob_flush();
	echo "Data rozpoczecia kroku 2: <b>$data2</b><br />";ob_flush();
	
	$days = explode(";",$wt);

	$oneday1 = explode("@",$days[0]); 
	$oneday2 = explode("@",$days[1]); 
	$oneday3 = explode("@",$days[2]); 
	$oneday4 = explode("@",$days[3]); 
	$oneday5 = explode("@",$days[4]); 
	$oneday6 = explode("@",$days[5]); 
	$oneday7 = explode("@",$days[6]); 

	$gp_sa = 1;
	if (($oneday1[1]=='') && ($oneday2[1]=='') && ($oneday3[1]=='') && ($oneday4[1]=='') && ($oneday5[1]=='') && ($oneday6[1]=='') && ($oneday7[1]=='')) $gp_sa = 0;

	if ($oneday1[1]=='') $oneday1[1] = '-';
	if ($oneday2[1]=='') $oneday2[1] = '-';
	if ($oneday3[1]=='') $oneday3[1] = '-';
	if ($oneday4[1]=='') $oneday4[1] = '-';
	if ($oneday5[1]=='') $oneday5[1] = '-';
	if ($oneday6[1]=='') $oneday6[1] = '-';
	if ($oneday7[1]=='') $oneday7[1] = '-';

	// menu z godzinami pracy
	$opis_stanow = '<br /><table>';
	$opis_stanow.= '<tr height=24><td colspan=2 class=center style=background-color:#FFFF7F><font color=black>Godziny pracy komorki</b></font></td></tr>';

	$opis_stanow .= '<tr><td class=right>Poniedzia�ek:</td><td><b>'.$oneday1[1].'</b></td></tr>';
	$opis_stanow .= '<tr><td class=right>Wtorek</td><td><b>'.$oneday2[1].'</b></td></tr>';
	$opis_stanow .= '<tr><td class=right>Sroda:</td><td><b>'.$oneday3[1].'</b></td></tr>';
	$opis_stanow .= '<tr><td class=right>Czwartek:</td><td><b>'.$oneday4[1].'</b></td></tr>';
	$opis_stanow .= '<tr><td class=right>Piatek:</td><td><b>'.$oneday5[1].'</b></td></tr>';
	$opis_stanow .= '<tr><td class=right>Sobota:</td><td><b>'.$oneday6[1].'</b></td></tr>';
	$opis_stanow .= '<tr><td class=right>Niedziela:</td><td><b>'.$oneday7[1].'</b></td></tr>';
	$opis_stanow.= '</table>';
	echo $opis_stanow;

	echo "Godziny pracy serwisu: <b>[$serwis_wt]</b><br />";ob_flush();

	$days = explode(";",$serwis_wt);

	$oneday1 = explode("@",$days[0]); 
	$oneday2 = explode("@",$days[1]); 
	$oneday3 = explode("@",$days[2]); 
	$oneday4 = explode("@",$days[3]); 
	$oneday5 = explode("@",$days[4]); 
	$oneday6 = explode("@",$days[5]); 
	$oneday7 = explode("@",$days[6]); 

	$gp_sa = 1;
	if (($oneday1[1]=='') && ($oneday2[1]=='') && ($oneday3[1]=='') && ($oneday4[1]=='') && ($oneday5[1]=='') && ($oneday6[1]=='') && ($oneday7[1]=='')) $gp_sa = 0;

	if ($oneday1[1]=='') $oneday1[1] = '-';
	if ($oneday2[1]=='') $oneday2[1] = '-';
	if ($oneday3[1]=='') $oneday3[1] = '-';
	if ($oneday4[1]=='') $oneday4[1] = '-';
	if ($oneday5[1]=='') $oneday5[1] = '-';
	if ($oneday6[1]=='') $oneday6[1] = '-';
	if ($oneday7[1]=='') $oneday7[1] = '-';

	// menu z godzinami pracy
	$opis_stanow = '<br /><table>';
	$opis_stanow.= '<tr height=24><td colspan=2 class=center style=background-color:#ABF1FF><font color=black>Godziny pracy serwisu</b></font></td></tr>';

	$opis_stanow .= '<tr><td class=right>Poniedzia�ek:</td><td><b>'.$oneday1[1].'</b></td></tr>';
	$opis_stanow .= '<tr><td class=right>Wtorek</td><td><b>'.$oneday2[1].'</b></td></tr>';
	$opis_stanow .= '<tr><td class=right>Sroda:</td><td><b>'.$oneday3[1].'</b></td></tr>';
	$opis_stanow .= '<tr><td class=right>Czwartek:</td><td><b>'.$oneday4[1].'</b></td></tr>';
	$opis_stanow .= '<tr><td class=right>Piatek:</td><td><b>'.$oneday5[1].'</b></td></tr>';
	$opis_stanow .= '<tr><td class=right>Sobota:</td><td><b>'.$oneday6[1].'</b></td></tr>';
	$opis_stanow .= '<tr><td class=right>Niedziela:</td><td><b>'.$oneday7[1].'</b></td></tr>';
	$opis_stanow.= '</table>';
	echo $opis_stanow."<br />";ob_flush();

	// ============================

	$sama_data1 = substr($data1,0,10);	echo "<br />Sama data 1: <b>[$sama_data1]</b> => ".strtotime($sama_data1)."<br />";ob_flush();
	$sama_data2 = substr($data2,0,10);	echo "Sama data 2: <b>[$sama_data2]</b> => ".strtotime($sama_data2)."<br />";ob_flush();

	$sama_godzina1 = substr($data1,11,8);	echo "<br />Sama godzina 1: <b>[$sama_godzina1]</b><br />";ob_flush();
	$sama_godzina2 = substr($data2,11,8);	echo "Sama godzina 2: <b>[$sama_godzina2]</b><br />";ob_flush();

	$sama_godzina1short = substr($data1,11,5);	echo "<br />Sama godzina 1 short: <b>[$sama_godzina1short]</b><br />";ob_flush();
	$sama_godzina2short = substr($data2,11,5);	echo "Sama godzina 2 short: <b>[$sama_godzina2short]</b><br />";ob_flush();

	echo "<hr />";ob_flush();

	$temp_data1_str1 = strtotime($sama_data1);		// data pocz�tkowa
	$temp_data2_str = strtotime($sama_data2);		// data ko�cowa
	//echo ">>>>>>>$temp_data2_str<<<<<<<br />";
	
	$sama_godzina2 = substr($data2,11,8);	echo "Sama godzina 2: <b>[$sama_godzina2]</b><br />";ob_flush();

	$k = 0;
	$ile_dni_pracujacych = 0;
	$minut_w_pelnych_dniach_pracujacych = 0;

	$czas = date("I",$sama_data1);
	
	echo "[$czas]<br />";
	
	Do {
		$ilosc_minut_pracujacych_w_dniu_do_dodania = 0;// wyzeruj licznik minut do dodania danego dnia
		$temp_data1_str = strtotime($sama_data1)+($k*86400);	// $temp_data1_str-> data w milisekundach
		
		
		// sprawdzenie czy nie ma przej�cia z czasu letniego na zimowy i odwrotnie
		$czas_nowy = date("I",$temp_data1_str);
		//echo "[$czas_nowy]";
		//if (($czas_nowy!=$czas) && ($czas_nowy==1)) { $temp_data1_str-=3600; $czas = $czas_nowy; }
		
		//echo ">>>>>>>$temp_data1_str<<<<<<<<<br />";
		
		$temp_data1_str_normal = date("Y-m-d",$temp_data1_str);		// $temp_data1_str_normal 	-> data w formacie YYYY-MM-DD
		
		// sprawd� czy aktualny dzie� jest pracuj�cy
		if (czy_pracuje($temp_data1_str_normal,$wt)) {
			$ile_dni_pracujacych++;	
			echo "<b><font color=green>PRACUJACY</font></b> | <b><font color=green>".$temp_data1_str_normal."</font></b> | ";ob_flush();
			
			// je�eli badany dzie� nie jest dniem pocz�tkowym i nie jest dniem ko�cowym => dodaj ilo�� minut roboczych w danym dniu
			if (($temp_data1_str != $temp_data2_str) &&	($temp_data1_str != $temp_data1_str1)) {
				$ilosc_minut_pracujacych_w_dniu_do_dodania = ilosc_minut_pracujacych_w_dniu($temp_data1_str_normal,$wt,$serwis_wt);	
				$minut_w_pelnych_dniach_pracujacych += $ilosc_minut_pracujacych_w_dniu_do_dodania;
				echo "(+ ".$ilosc_minut_pracujacych_w_dniu_do_dodania.")";
			}
			
			echo "(k=$k) | ";		ob_flush();
			// je�eli badany dzie� jest r�wny dniowi pocz�tkowemu wtedy wylicz ile pozosta�o minut do ko�ca pracy dnia
			if ($k == 0) {
	$sama_godzina_graniczna_start = godzina_start1s($temp_data1_str_normal,$wt,$serwis_wt);

	// je�eli godzina pocz�tkowa jest po rozpocz�ciu dnia
	if ((strtotime($sama_godzina1)) < strtotime($sama_godzina_graniczna_start)) {
		$sama_godzina1 = godzina_start1s($temp_data1_str_normal,$wt,$serwis_wt);	
	}

	// je�eli daty d� te same
	if (($temp_data1_str == $temp_data1_str1) && ($temp_data1_str == $temp_data2_str)) {

		// ustaw graniczn� godzin� pracy w danym dniu 
		$sama_godzina_graniczna_stop = godzina_stop1s($temp_data1_str_normal,$wt,$serwis_wt);
		// je�eli ten sam dzie� i godzina zako�czenia jest < od granicznej godziny pracy
		if ($sama_godzina_graniczna_stop>=$sama_godzina2short) {
			$minut_pracujacych_w_dniu_startowym = ile_minut_pomiedzy_godzinami($sama_godzina1, $sama_godzina2,$serwis_wt,$k);
			echo "<font color=blue>Ilosc min. w dniu startowym: ".$minut_pracujacych_w_dniu_startowym."</font> | ";	ob_flush();
			$ilosc_minut_pracujacych_w_dniu_do_dodania = $minut_pracujacych_w_dniu_startowym;
		} else {
			echo "Dostepnosc do komorki do: <font color=purple><b>".$sama_godzina_graniczna_stop."</b> | </font>";ob_flush();
			$minut_pracujacych_w_dniu_startowym = ile_minut_pomiedzy_godzinami($sama_godzina1, $sama_godzina_graniczna_stop,$serwis_wt,$k);
			echo "<font color=blue>Ilosc min. w dniu startowym: ".$minut_pracujacych_w_dniu_startowym."</font> | ";	ob_flush();
			$ilosc_minut_pracujacych_w_dniu_do_dodania = $minut_pracujacych_w_dniu_startowym;
		}
		
	} else {
		
		$sama_godzina_graniczna_stop = godzina_stop1s($temp_data1_str_normal,$wt,$serwis_wt);
		if ($sama_godzina1short>$sama_godzina_graniczna_stop) {
			echo "Krok rozpocz�ty po godzinach pracy UP: <font color=purple><b>".$sama_godzina_graniczna_stop."</b> | </font>";ob_flush();
			$minut_pracujacych_w_dniu_startowym = 0;
			$ilosc_minut_pracujacych_w_dniu_do_dodania = $minut_pracujacych_w_dniu_startowym;
		} else {
			echo "Dostepnosc do komorki do: <font color=purple><b>".$sama_godzina_graniczna_stop."</b> | </font>";ob_flush();
			$minut_pracujacych_w_dniu_startowym = ile_minut_pomiedzy_godzinami($sama_godzina1, $sama_godzina_graniczna_stop,$serwis_wt,$k);
			echo "<font color=blue>Ilosc min. w dniu startowym: ".$minut_pracujacych_w_dniu_startowym."</font> | ";ob_flush();
			$ilosc_minut_pracujacych_w_dniu_do_dodania = $minut_pracujacych_w_dniu_startowym;
		}
	}

			}
			
			// je�eli badany dzie� jest r�wny dniowi ko�cowemu wtedy wylicz ile up�yne�o minut od rozpocz�cia pracy tego dnia do godziny podanej
			if ($k != 0) {
			
	if ($temp_data1_str >= $temp_data2_str) {
		$sama_godzina_graniczna_stop = godzina_start1s($sama_data2,$wt,$serwis_wt);
		
		//echo "-$sama_godzina_graniczna_stop,$sama_godzina2-";ob_flush();
		// je�eli godzina ko�cowa jest p�niejsza ni� godzina zako�czenia pracy kom�rki
		if ((strtotime($sama_godzina2short)) > strtotime($sama_godzina_graniczna_stop)) {
			//$sama_godzina2 = godzina_stop1($temp_data1_str_normal,$wt);
		}
		//$sama_godzina_graniczna_start = godzina_start1s($sama_data2,$wt);
		//echo "$sama_godzina_graniczna_start";ob_flush();
		if ((strtotime($sama_godzina2short)) > strtotime($sama_godzina_graniczna_stop)) {
		
		}
		
		//echo "+$sama_godzina_graniczna_stop+ $sama_godzina2short $sama_godzina2";ob_flush();
		
		if ((strtotime($sama_godzina_graniczna_stop))>(strtotime($sama_godzina2short))) { 
			$minut_pracujacych_w_dniu_ko�cowym = 0;
			echo "<font color=blue>Ilosc min. w dniu ko�cowym: ".$minut_pracujacych_w_dniu_ko�cowym."</font> | ";ob_flush();
			$minut_w_pelnych_dniach_pracujacych -= $ilosc_minut_pracujacych_w_dniu_do_dodania;
			
			$ilosc_minut_pracujacych_w_dniu_do_dodania = $minut_pracujacych_w_dniu_ko�cowym;
			
		} 
		
		if ((strtotime($sama_godzina_graniczna_stop))<(strtotime($sama_godzina2short))) { 
		//else {
			echo "Dostepnosc do komorki od: <font color=purple><b>".$sama_godzina_graniczna_stop."</b> | </font>";ob_flush();
			$minut_pracujacych_w_dniu_ko�cowym = ile_minut_pomiedzy_godzinami($sama_godzina_graniczna_stop,$sama_godzina2,$serwis_wt,$k);
			echo "<font color=blue>Ilosc min. w dniu ko�cowym: ".$minut_pracujacych_w_dniu_ko�cowym."</font> | ";ob_flush();
			$minut_w_pelnych_dniach_pracujacych -= $ilosc_minut_pracujacych_w_dniu_do_dodania;

			$ilosc_minut_pracujacych_w_dniu_do_dodania = $minut_pracujacych_w_dniu_ko�cowym;
		}
	}
			}
			
			echo "ilosc minut w danym dniu: <b>".$ilosc_minut_pracujacych_w_dniu_do_dodania."</b> | ";	ob_flush();
		//	echo " | suma z pe�nych dni: ".$minut_w_pelnych_dniach_pracujacych."";
		
		} else {
		
			echo "<b><font color=red>NIEPRACUJACY</font></b>| k=".$k." | <b><font color=red>".$temp_data1_str_normal."</font></b> | ";ob_flush();
		//	echo "|||||>$temp_data1_str -> ".date("Y-m-d H:i:s",$temp_data1_str)." | ".date("I",$temp_data1_str)."<||||||";
			
		}
		
	//	if (czy_pracuje($temp_data1_str_normal,$wt)) { echo "PRACUJACY | "; } else { echo "NIEPRACUJACY | "; }
		
		echo "temp_data1_str1 (str) = ".$temp_data1_str1." | temp_data1_str (str) = <font color=red>".$temp_data1_str."</font> (<b>".date("Y-m-d H:i:s",$temp_data1_str)."</b>) | temp_data2_str (str) = <font color=red>".$temp_data2_str."</font> | ";ob_flush();

		if ($temp_data1_str >= $temp_data2_str) {
			//if (($czas_nowy!=$czas) && ($czas_nowy==1)) { $czas = $czas_nowy; $temp_data1_str-=3600; $ilosc_minut_pracujacych_w_dniu_do_dodania-=60; }
			//if (($czas_nowy!=$czas) && ($czas_nowy==0)) { $czas = $czas_nowy; $temp_data1_str+=3600; $ilosc_minut_pracujacych_w_dniu_do_dodania+=60; }
			break;
		}			
		
	//	echo "$minut_pracujacych_w_dniu_startowym + $minut_w_pelnych_dniach_pracujacych + $minut_pracujacych_w_dniu_ko�cowym";
		
		echo "<br />";ob_flush();

		
		$k++;
	} While ($temp_data1_str != $temp_data2_str);

	// faktyczna ilo�� minut pracujacych mi�dzy datami = suma minut pozosta�ych w dniu startowym + ilo�� minut w pe�nych dniach pracuj�cych + ilo�� minut jakie up�yn�o w dniu ko�cowym
	$minut_razem = $minut_pracujacych_w_dniu_startowym + $minut_w_pelnych_dniach_pracujacych + $minut_pracujacych_w_dniu_ko�cowym;

	$blad = 0;
	$blad_nr = 0;

	if ($minut_razem<0) {
		$minut_razem = 0;
		
		if (strtotime($data2) < strtotime($data1)) {
			$blad = 1;
			$blad_nr = 1;
		} 
		
	}

	if ($blad==1) {
		echo "<font color=red> | ";
		
		switch ($blad_nr) {
			case "1" : echo "blad w datach (data koncowa wczesniejsza niz data poczatkowa)"; break;
			
			default : echo "blad niezindentyfikowany"; break;
		}
		
		echo "</font>";
	}

	echo "<hr />";
	echo "Ilosc dni pracujacych mi�dzy podanymi datami: <b>$ile_dni_pracujacych</b><br />";ob_flush();
	echo "Ilosc minut pracujacych miedzy podanymi datami (pe�nych dni): <b>$minut_razem</b>";ob_flush();

	if ($blad==1) {
		echo "<font color=red> | ";
		
		switch ($blad_nr) {
			case "1" : echo "blad w datach (data koncowa wczesniejsza niz data poczatkowa)"; break;
			
			default : echo "blad niezindentyfikowany"; break;
		}
		
		echo "</font>";
	}
	echo "<br /><hr /><input type=button name=wstecz value='Od nowa' onClick=\"self.location.href='test_czas.php?d1=".urlencode($_POST[d1])."&d2=".urlencode($_POST[d2])."&w=".urlencode($_POST[w])."&sw=".urlencode($_POST[sw])."';\">";
	
} else {

	if ($_REQUEST[w]!='') {
		$wt = $_REQUEST[w];
	} else {
		$wt = 'PN@06:00-22:00;WT@06:00-22:00;SR@06:00-22:00;CZ@06:00-22:00;PT@06:00-22:00;SO@07:00-15:00;NI@-;';
	}

	if ($_REQUEST[sw]!='') {
		$serwis_wt = $_REQUEST[sw];
	} else {
		$serwis_wt = 'PN@07:00-22:00;WT@07:00-21:00;SR@07:00-21:00;CZ@07:00-21:00;PT@07:00-21:00;SO@07:00-14:00;NI@-;';
	}
	
	if ($_REQUEST[d1]!='') { $d1=$_REQUEST[d1]; } else { $d1 = Date('Y-m-d H:i'); }
	if ($_REQUEST[d2]!='') { $d2=$_REQUEST[d2]; } else { $d2 = Date('Y-m-d H:i'); }
	
	echo "<form name=test action=test_czas.php method=POST>";
	echo "Data poczatkowa: "; echo "<input type=text name=d1 value='".$d1."'><br />";
	echo "Data koncowa: "; echo "<input type=text name=d2 value='".$d2."'><br />";
	echo "<br />Godziny pracy komorki: "; echo "<input type=text name=w size=120 value='".$wt."'><br />";
	echo "Godziny pracy serwisu: "; echo "<input type=text name=sw size=120 value='".$serwis_wt."'><br />";
	
	echo "<br />";
	echo "<input type=submit name=submit value='Wylicz'>";
	echo "</form>";
	
	echo "2011-12-04 => ".strtotime('2011-12-04');
}
?>