<?php
require_once('cfg_eserwis.php');


function MinutesBetween1($data1_start, $data1_stop, $_wt_, $_serwis_wt_) {
//	echo "<br />$data1_start | $data1_stop | $_wt_ | $_serwis_wt_";
	
	if ($data1_start==$data1_stop) return 0;
	
	$sama_data1 = substr($data1_start,0,10);				//echo "<br />Sama data 1: <b>[$sama_data1]</b><br />";
	$sama_data2 = substr($data1_stop,0,10);				//echo "Sama data 2: <b>[$sama_data2]</b><br />";

	$sama_godzina1 = substr($data1_start,11,8);			//echo "<br />Sama godzina 1: <b>[$sama_godzina1]</b><br />";
	$sama_godzina2 = substr($data1_stop,11,8);			//echo "Sama godzina 2: <b>[$sama_godzina2]</b><br />";

	$sama_godzina1short = substr($data1_start,11,5);		//echo "<br />Sama godzina 1 short: <b>[$sama_godzina1short]</b><br />";
	$sama_godzina2short = substr($data1_stop,11,5);		//echo "Sama godzina 2 short: <b>[$sama_godzina2short]</b><br />";

	$temp_data1_str1 = strtotime($sama_data1);		// data pocz¹tkowa
	$temp_data2_str = strtotime($sama_data2);		// data koñcowa

	$sama_godzina2 = substr($data1_stop,11,8);			//echo "Sama godzina 2: <b>[$sama_godzina2]</b><br />";

	$kkkk = 0;
	$ile_dni_pracujacych = 0;
	$minut_w_pelnych_dniach_pracujacych = 0;

	Do {
		$ilosc_minut_pracujacych_w_dniu_do_dodania = 0;				// wyzeruj licznik minut do dodania danego dnia
		$temp_data1_str = strtotime($sama_data1)+($kkkk*86400);		// $temp_data1_str-> data w milisekundach
		$temp_data1_str_normal = date("Y-m-d",$temp_data1_str);		// $temp_data1_str_normal 	-> data w formacie YYYY-MM-DD
		
		// sprawdŸ czy aktualny dzieñ jest pracuj¹cy
		if (czy_pracuje($temp_data1_str_normal,$_wt_)) {
			$ile_dni_pracujacych++;	
			//echo "<b><font color=green>PRACUJACY</font></b> | <b><font color=green>".$temp_data1_str_normal."</font></b> | ";
			
			// je¿eli badany dzieñ nie jest dniem pocz¹tkowym i nie jest dniem koñcowym => dodaj iloœæ minut roboczych w danym dniu
			if (($temp_data1_str != $temp_data2_str) &&	($temp_data1_str != $temp_data1_str1)) {
				$ilosc_minut_pracujacych_w_dniu_do_dodania = ilosc_minut_pracujacych_w_dniu($temp_data1_str_normal,$_wt_,$_serwis_wt_);	
				$minut_w_pelnych_dniach_pracujacych += $ilosc_minut_pracujacych_w_dniu_do_dodania;
			}
			
			//echo "(k=$kkkk) | ";		
			// je¿eli badany dzieñ jest równy dniowi pocz¹tkowemu wtedy wylicz ile pozosta³o minut do koñca pracy dnia
			if ($kkkk == 0) {
				$sama_godzina_graniczna_start = godzina_start1s($temp_data1_str_normal,$_wt_,$_serwis_wt_);

				// je¿eli godzina pocz¹tkowa jest po rozpoczêciu dnia
				if ((strtotime($sama_godzina1)) < strtotime($sama_godzina_graniczna_start)) {
					$sama_godzina1 = godzina_start1s($temp_data1_str_normal,$_wt_,$_serwis_wt_);	
				}

			// je¿eli daty d¹ te same
			if (($temp_data1_str == $temp_data1_str1) && ($temp_data1_str == $temp_data2_str)) {

				// ustaw graniczn¹ godzinê pracy w danym dniu 
				$sama_godzina_graniczna_stop = godzina_stop1s($temp_data1_str_normal,$_wt_,$_serwis_wt_);
				
				// je¿eli ten sam dzieñ i godzina zakoñczenia jest < od granicznej godziny pracy
				if ($sama_godzina_graniczna_stop>=$sama_godzina2short) {
					$minut_pracujacych_w_dniu_startowym = ile_minut_pomiedzy_godzinami($sama_godzina1, $sama_godzina2,$_serwis_wt_,$kkkk);
					//echo "<font color=blue>Ilosc min. w dniu startowym: ".$minut_pracujacych_w_dniu_startowym."</font> | ";	
					$ilosc_minut_pracujacych_w_dniu_do_dodania = $minut_pracujacych_w_dniu_startowym;
				} else {
					//echo "Dostepnosc do komorki do: <font color=purple><b>".$sama_godzina_graniczna_stop."</b> | </font>";
					$minut_pracujacych_w_dniu_startowym = ile_minut_pomiedzy_godzinami($sama_godzina1, $sama_godzina_graniczna_stop,$_serwis_wt_,$kkkk);
					//echo "<font color=blue>Ilosc min. w dniu startowym: ".$minut_pracujacych_w_dniu_startowym."</font> | ";	
					$ilosc_minut_pracujacych_w_dniu_do_dodania = $minut_pracujacych_w_dniu_startowym;
				}
				
			} else {
				
				$sama_godzina_graniczna_stop = godzina_stop1s($temp_data1_str_normal,$_wt_,$_serwis_wt_);
				if ($sama_godzina1short>$sama_godzina_graniczna_stop) {
					//echo "Krok rozpoczêty po godzinach pracy UP: <font color=purple><b>".$sama_godzina_graniczna_stop."</b> | </font>";
					$minut_pracujacych_w_dniu_startowym = 0;
					$ilosc_minut_pracujacych_w_dniu_do_dodania = $minut_pracujacych_w_dniu_startowym;
				} else {
					//echo "Dostepnosc do komorki do: <font color=purple><b>".$sama_godzina_graniczna_stop."</b> | </font>";
					$minut_pracujacych_w_dniu_startowym = ile_minut_pomiedzy_godzinami($sama_godzina1, $sama_godzina_graniczna_stop,$_serwis_wt_,$kkkk);
					//echo "<font color=blue>Ilosc min. w dniu startowym: ".$minut_pracujacych_w_dniu_startowym."</font> | ";
					$ilosc_minut_pracujacych_w_dniu_do_dodania = $minut_pracujacych_w_dniu_startowym;
				}
			}

			}
			
			// je¿eli badany dzieñ jest równy dniowi koñcowemu wtedy wylicz ile up³yne³o minut od rozpoczêcia pracy tego dnia do godziny podanej
			if ($kkkk != 0) {		
				if ($temp_data1_str >= $temp_data2_str) {
					$sama_godzina_graniczna_stop = godzina_start1s($sama_data2,$_wt_,$_serwis_wt_);
					
					//echo "-$sama_godzina_graniczna_stop,$sama_godzina2-";
					// je¿eli godzina koñcowa jest póŸniejsza ni¿ godzina zakoñczenia pracy komórki
					if ((strtotime($sama_godzina2short)) > strtotime($sama_godzina_graniczna_stop)) {
						//$sama_godzina2 = godzina_stop1($temp_data1_str_normal,$_wt_);
					}
					//$sama_godzina_graniczna_start = godzina_start1s($sama_data2,$_wt_);
					//echo "$sama_godzina_graniczna_start";
					if ((strtotime($sama_godzina2short)) > strtotime($sama_godzina_graniczna_stop)) {
					
					}
					
					//echo "+$sama_godzina_graniczna_stop+ $sama_godzina2";
					
					if ((strtotime($sama_godzina_graniczna_stop))>(strtotime($sama_godzina2short))) { 
						$minut_pracujacych_w_dniu_koñcowym = 0;
						//	echo "<font color=blue>Ilosc min. w dniu koñcowym: ".$minut_pracujacych_w_dniu_koñcowym."</font> | ";
						$minut_w_pelnych_dniach_pracujacych -= $ilosc_minut_pracujacych_w_dniu_do_dodania;
						$ilosc_minut_pracujacych_w_dniu_do_dodania = $minut_pracujacych_w_dniu_koñcowym;
					} 
					
					if ((strtotime($sama_godzina_graniczna_stop))<(strtotime($sama_godzina2short))) { 
						//else {
						//	echo "Dostepnosc do komorki od: <font color=purple><b>".$sama_godzina_graniczna_stop."</b> | </font>";
						$minut_pracujacych_w_dniu_koñcowym = ile_minut_pomiedzy_godzinami($sama_godzina_graniczna_stop,$sama_godzina2,$_serwis_wt_,$kkkk);
						//	echo "<font color=blue>Ilosc min. w dniu koñcowym: ".$minut_pracujacych_w_dniu_koñcowym."</font> | ";
						$minut_w_pelnych_dniach_pracujacych -= $ilosc_minut_pracujacych_w_dniu_do_dodania;
						$ilosc_minut_pracujacych_w_dniu_do_dodania = $minut_pracujacych_w_dniu_koñcowym;
					}
				}
			}

			//	echo "ilosc minut w danym dniu: <b>".$ilosc_minut_pracujacych_w_dniu_do_dodania."</b>";	
			//	echo " | suma z pe³nych dni: ".$minut_w_pelnych_dniach_pracujacych."";
		
		} else {
			// echo "<b><font color=red>NIEPRACUJACY</font></b> | <b><font color=red>".$temp_data1_str_normal."</font></b>";
		}
		
		if ($temp_data1_str >= $temp_data2_str) {
			//if (($czas_nowy!=$czas) && ($czas_nowy==1)) { $czas = $czas_nowy; $temp_data1_str-=3600; $ilosc_minut_pracujacych_w_dniu_do_dodania-=60; }
			//if (($czas_nowy!=$czas) && ($czas_nowy==0)) { $czas = $czas_nowy; $temp_data1_str+=3600; $ilosc_minut_pracujacych_w_dniu_do_dodania+=60; }
			break;
		}	
		
		//	if (czy_pracuje($temp_data1_str_normal,$_wt_)) { echo "PRACUJACY | "; } else { echo "NIEPRACUJACY | "; }	
			
		$kkkk++;
	} While ($temp_data1_str != $temp_data2_str);

	// faktyczna iloœæ minut pracujacych miêdzy datami = suma minut pozosta³ych w dniu startowym + iloœæ minut w pe³nych dniach pracuj¹cych + iloœæ minut jakie up³ynê³o w dniu koñcowym
	$minut_razem = $minut_pracujacych_w_dniu_startowym + $minut_w_pelnych_dniach_pracujacych + $minut_pracujacych_w_dniu_koñcowym;
	return $minut_razem;
}
/*

echo "<font color=grey>".Date("H:i:s")."</font><br />";

$data1 = '2011-08-22 01:00:00';		echo "Data rozpoczecia kroku 1: <b>$data1</b><br />";
$data2 = '2011-08-24 22:30:00';		echo "Data rozpoczecia kroku 2: <b>$data2</b><br />";

$wt = 'PN@06:00-22:00;WT@06:00-22:00;SR@06:00-22:00;CZ@06:00-22:00;PT@06:00-22:00;SO@07:00-15:00;NI@-;';	
$serwis_wt = 'PN@07:00-22:00;WT@07:00-21:00;SR@07:00-21:00;CZ@07:00-21:00;PT@07:00-21:00;SO@07:00-14:00;NI@-;';

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

$opis_stanow .= '<tr><td class=right>Poniedzia³ek:</td><td><b>'.$oneday1[1].'</b></td></tr>';
$opis_stanow .= '<tr><td class=right>Wtorek</td><td><b>'.$oneday2[1].'</b></td></tr>';
$opis_stanow .= '<tr><td class=right>Sroda:</td><td><b>'.$oneday3[1].'</b></td></tr>';
$opis_stanow .= '<tr><td class=right>Czwartek:</td><td><b>'.$oneday4[1].'</b></td></tr>';
$opis_stanow .= '<tr><td class=right>Piatek:</td><td><b>'.$oneday5[1].'</b></td></tr>';
$opis_stanow .= '<tr><td class=right>Sobota:</td><td><b>'.$oneday6[1].'</b></td></tr>';
$opis_stanow .= '<tr><td class=right>Niedziela:</td><td><b>'.$oneday7[1].'</b></td></tr>';
$opis_stanow.= '</table>';
echo $opis_stanow;

//echo "Godziny pracy serwisu: <b>[$serwis_wt]</b><br />";

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

$opis_stanow .= '<tr><td class=right>Poniedzia³ek:</td><td><b>'.$oneday1[1].'</b></td></tr>';
$opis_stanow .= '<tr><td class=right>Wtorek</td><td><b>'.$oneday2[1].'</b></td></tr>';
$opis_stanow .= '<tr><td class=right>Sroda:</td><td><b>'.$oneday3[1].'</b></td></tr>';
$opis_stanow .= '<tr><td class=right>Czwartek:</td><td><b>'.$oneday4[1].'</b></td></tr>';
$opis_stanow .= '<tr><td class=right>Piatek:</td><td><b>'.$oneday5[1].'</b></td></tr>';
$opis_stanow .= '<tr><td class=right>Sobota:</td><td><b>'.$oneday6[1].'</b></td></tr>';
$opis_stanow .= '<tr><td class=right>Niedziela:</td><td><b>'.$oneday7[1].'</b></td></tr>';
$opis_stanow.= '</table>';
echo $opis_stanow."<br />";

// ============================

$sama_data1 = substr($data1,0,10);	//echo "<br />Sama data 1: <b>[$sama_data1]</b><br />";
$sama_data2 = substr($data2,0,10);	//echo "Sama data 2: <b>[$sama_data2]</b><br />";

$sama_godzina1 = substr($data1,11,8);	//echo "<br />Sama godzina 1: <b>[$sama_godzina1]</b><br />";
$sama_godzina2 = substr($data2,11,8);	//echo "Sama godzina 2: <b>[$sama_godzina2]</b><br />";

$sama_godzina1short = substr($data1,11,5);	//echo "<br />Sama godzina 1 short: <b>[$sama_godzina1short]</b><br />";
$sama_godzina2short = substr($data2,11,5);	//echo "Sama godzina 2 short: <b>[$sama_godzina2short]</b><br />";

echo "<hr />";

$temp_data1_str1 = strtotime($sama_data1);		// data pocz¹tkowa
$temp_data2_str = strtotime($sama_data2);		// data koñcowa

$sama_godzina2 = substr($data2,11,8);	//echo "Sama godzina 2: <b>[$sama_godzina2]</b><br />";

$k = 0;
$ile_dni_pracujacych = 0;
$minut_w_pelnych_dniach_pracujacych = 0;

Do {
	$ilosc_minut_pracujacych_w_dniu_do_dodania = 0;// wyzeruj licznik minut do dodania danego dnia
	$temp_data1_str = strtotime($sama_data1)+($k*86400);	// $temp_data1_str-> data w milisekundach
	$temp_data1_str_normal = date("Y-m-d",$temp_data1_str);		// $temp_data1_str_normal 	-> data w formacie YYYY-MM-DD
	
	// sprawdŸ czy aktualny dzieñ jest pracuj¹cy
	if (czy_pracuje($temp_data1_str_normal,$wt)) {
		$ile_dni_pracujacych++;	
		echo "<b><font color=green>PRACUJACY</font></b> | <b><font color=green>".$temp_data1_str_normal."</font></b> | ";
		
		// je¿eli badany dzieñ nie jest dniem pocz¹tkowym i nie jest dniem koñcowym => dodaj iloœæ minut roboczych w danym dniu
		if (($temp_data1_str != $temp_data2_str) &&	($temp_data1_str != $temp_data1_str1)) {
$ilosc_minut_pracujacych_w_dniu_do_dodania = ilosc_minut_pracujacych_w_dniu($temp_data1_str_normal,$wt,$serwis_wt);	
$minut_w_pelnych_dniach_pracujacych += $ilosc_minut_pracujacych_w_dniu_do_dodania;
//echo "+";
		}
		
		echo "(k=$k) | ";		
		// je¿eli badany dzieñ jest równy dniowi pocz¹tkowemu wtedy wylicz ile pozosta³o minut do koñca pracy dnia
		if ($k == 0) {
$sama_godzina_graniczna_start = godzina_start1s($temp_data1_str_normal,$wt,$serwis_wt);

// je¿eli godzina pocz¹tkowa jest po rozpoczêciu dnia
if ((strtotime($sama_godzina1)) < strtotime($sama_godzina_graniczna_start)) {
	$sama_godzina1 = godzina_start1s($temp_data1_str_normal,$wt,$serwis_wt);	
}

// je¿eli daty d¹ te same
if (($temp_data1_str == $temp_data1_str1) && ($temp_data1_str == $temp_data2_str)) {

	// ustaw graniczn¹ godzinê pracy w danym dniu 
	$sama_godzina_graniczna_stop = godzina_stop1s($temp_data1_str_normal,$wt,$serwis_wt);
	
	// je¿eli ten sam dzieñ i godzina zakoñczenia jest < od granicznej godziny pracy
	if ($sama_godzina_graniczna_stop>=$sama_godzina2short) {
		$minut_pracujacych_w_dniu_startowym = ile_minut_pomiedzy_godzinami($sama_godzina1, $sama_godzina2,$serwis_wt,$k);
		echo "<font color=blue>Ilosc min. w dniu startowym: ".$minut_pracujacych_w_dniu_startowym."</font> | ";	
		$ilosc_minut_pracujacych_w_dniu_do_dodania = $minut_pracujacych_w_dniu_startowym;
	} else {
		echo "Dostepnosc do komorki do: <font color=purple><b>".$sama_godzina_graniczna_stop."</b> | </font>";
		$minut_pracujacych_w_dniu_startowym = ile_minut_pomiedzy_godzinami($sama_godzina1, $sama_godzina_graniczna_stop,$serwis_wt,$k);
		echo "<font color=blue>Ilosc min. w dniu startowym: ".$minut_pracujacych_w_dniu_startowym."</font> | ";	
		$ilosc_minut_pracujacych_w_dniu_do_dodania = $minut_pracujacych_w_dniu_startowym;
	}
	
} else {
	
	$sama_godzina_graniczna_stop = godzina_stop1s($temp_data1_str_normal,$wt,$serwis_wt);
	if ($sama_godzina1short>$sama_godzina_graniczna_stop) {
		echo "Krok rozpoczêty po godzinach pracy UP: <font color=purple><b>".$sama_godzina_graniczna_stop."</b> | </font>";
		$minut_pracujacych_w_dniu_startowym = 0;
		$ilosc_minut_pracujacych_w_dniu_do_dodania = $minut_pracujacych_w_dniu_startowym;
	} else {
		echo "Dostepnosc do komorki do: <font color=purple><b>".$sama_godzina_graniczna_stop."</b> | </font>";
		$minut_pracujacych_w_dniu_startowym = ile_minut_pomiedzy_godzinami($sama_godzina1, $sama_godzina_graniczna_stop,$serwis_wt,$k);
		echo "<font color=blue>Ilosc min. w dniu startowym: ".$minut_pracujacych_w_dniu_startowym."</font> | ";
		$ilosc_minut_pracujacych_w_dniu_do_dodania = $minut_pracujacych_w_dniu_startowym;
	}
}

		}
		
		// je¿eli badany dzieñ jest równy dniowi koñcowemu wtedy wylicz ile up³yne³o minut od rozpoczêcia pracy tego dnia do godziny podanej
		if ($k != 0) {
		
if ($temp_data1_str == $temp_data2_str) {
	$sama_godzina_graniczna_stop = godzina_start1s($sama_data2,$wt,$serwis_wt);
	
	//echo "-$sama_godzina_graniczna_stop,$sama_godzina2-";
	// je¿eli godzina koñcowa jest póŸniejsza ni¿ godzina zakoñczenia pracy komórki
	if ((strtotime($sama_godzina2short)) > strtotime($sama_godzina_graniczna_stop)) {
		//$sama_godzina2 = godzina_stop1($temp_data1_str_normal,$wt);
	}
	//$sama_godzina_graniczna_start = godzina_start1s($sama_data2,$wt);
	//echo "$sama_godzina_graniczna_start";
	if ((strtotime($sama_godzina2short)) > strtotime($sama_godzina_graniczna_stop)) {
	
	}
	
	//echo "+$sama_godzina_graniczna_stop+ $sama_godzina2";
	
	if ((strtotime($sama_godzina_graniczna_stop))>(strtotime($sama_godzina2short))) { 
		$minut_pracujacych_w_dniu_koñcowym = 0;
		echo "<font color=blue>Ilosc min. w dniu koñcowym: ".$minut_pracujacych_w_dniu_koñcowym."</font> | ";
		$ilosc_minut_pracujacych_w_dniu_do_dodania = $minut_pracujacych_w_dniu_koñcowym;
	} 
	
	if ((strtotime($sama_godzina_graniczna_stop))<(strtotime($sama_godzina2short))) { 
	//else {
		echo "Dostepnosc do komorki od: <font color=purple><b>".$sama_godzina_graniczna_stop."</b> | </font>";
		$minut_pracujacych_w_dniu_koñcowym = ile_minut_pomiedzy_godzinami($sama_godzina_graniczna_stop,$sama_godzina2,$serwis_wt,$k);
		echo "<font color=blue>Ilosc min. w dniu koñcowym: ".$minut_pracujacych_w_dniu_koñcowym."</font> | ";
		$ilosc_minut_pracujacych_w_dniu_do_dodania = $minut_pracujacych_w_dniu_koñcowym;
	}
}
		}

		echo "ilosc minut w danym dniu: <b>".$ilosc_minut_pracujacych_w_dniu_do_dodania."</b>";	
	//	echo " | suma z pe³nych dni: ".$minut_w_pelnych_dniach_pracujacych."";
	
	} else echo "<b><font color=red>NIEPRACUJACY</font></b> | <b><font color=red>".$temp_data1_str_normal."</font></b>";
	
//	if (czy_pracuje($temp_data1_str_normal,$wt)) { echo "PRACUJACY | "; } else { echo "NIEPRACUJACY | "; }
	
	//echo "temp_data1_str1 (str) = ".$temp_data1_str1." | temp_data1_str (str) = <font color=red>".$temp_data1_str."</font> (<b>".date("Y-m-d",$temp_data1_str)."</b>) | temp_data2_str (str) = <font color=red>".$temp_data2_str."</font> | ";
	
	
	echo "<br />";

	
	$k++;
} While ($temp_data1_str != $temp_data2_str);

// faktyczna iloœæ minut pracujacych miêdzy datami = suma minut pozosta³ych w dniu startowym + iloœæ minut w pe³nych dniach pracuj¹cych + iloœæ minut jakie up³ynê³o w dniu koñcowym
$minut_razem = $minut_pracujacych_w_dniu_startowym + $minut_w_pelnych_dniach_pracujacych + $minut_pracujacych_w_dniu_koñcowym;

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
echo "Ilosc dni pracujacych miêdzy podanymi datami: <b>$ile_dni_pracujacych</b><br />";
echo "Ilosc minut pracujacych miedzy podanymi datami (pe³nych dni): <b>$minut_razem</b>";

if ($blad==1) {
	echo "<font color=red> | ";
	
	switch ($blad_nr) {
		case "1" : echo "blad w datach (data koncowa wczesniejsza niz data poczatkowa)"; break;
		
		default : echo "blad niezindentyfikowany"; break;
	}
	
	echo "</font>";
}

echo "<br />";
echo "<font color=grey>".Date("H:i:s")."</font><br />";
*/

//$data1 = '2011-08-22 01:00:00';		echo "Data rozpoczecia kroku 1: <b>$data1</b><br />";
//$data2 = '2011-08-24 22:30:00';		echo "Data rozpoczecia kroku 2: <b>$data2</b><br />";

//$wt = 'PN@06:00-22:00;WT@06:00-22:00;SR@06:00-22:00;CZ@06:00-22:00;PT@06:00-22:00;SO@07:00-15:00;NI@-;';	
//$serwis_wt = 'PN@07:00-22:00;WT@07:00-21:00;SR@07:00-21:00;CZ@07:00-21:00;PT@07:00-21:00;SO@07:00-14:00;NI@-;';

echo MinutesBetween('2011-09-10 13:00:40','2011-09-10 15:06:00','PN@06:00-22:00;WT@06:00-22:00;SR@06:00-22:00;CZ@06:00-22:00;PT@06:00-22:00;SO@07:00-15:00;NI@-;','PN@07:00-22:00;WT@07:00-21:00;SR@07:00-21:00;CZ@07:00-21:00;PT@07:00-21:00;SO@07:00-14:00;NI@-;');

?>