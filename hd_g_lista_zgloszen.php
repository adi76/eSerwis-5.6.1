<?php 
include_once('header_simple.php');
include_once('cfg_eserwis.php');
include_once('cfg_helpdesk.php');

function toUpper($string) {
	$string = strtr($string,'qwertyuiopasdfghjklzxcvbnm','QWERTYUIOPASDFGHJKLZXCVBNM');
	return (strtr($string, 'ęóąśłżźćń','ĘÓĄŚŁŻŹĆŃ' )); 
}; 

//$span_pracownicy = '<b>'.$_GET[komorka].'</b>';
$span_pracownicy = '';
$span_pracownicy = '<br />Zgłoszenia dla komórki <b>'.toUpper($_GET[komorka]).':</b>';
$span_pracownicy .= '<table cellspacing=1 cellpadding=1 width=100%><tr style=background-color:#B4B4B4><td width=auto>Temat zgłoszenia<span style=\'float:right;\'>Nr HADIM</span></td><td class=center width=110>Osoba<br />rejestrująca</td><td class=center width=40>Status</td><td class=center>Opcje</td></tr>';

$sql = "SELECT zgl_id, zgl_nr, zgl_poczta_nr, zgl_data, zgl_godzina, zgl_temat, zgl_kategoria, zgl_priorytet, zgl_osoba_rejestrujaca, zgl_status, zgl_poledodatkowe2,zgl_unikalny_nr,zgl_komorka,zgl_osoba,zgl_wymagany_wyjazd,zgl_czy_rozwiazany_problem,zgl_podkategoria FROM $dbname_hd.hd_zgloszenie WHERE (zgl_status<>9) and (zgl_komorka='".$_GET[komorka]."') and (zgl_widoczne=1) and (belongs_to=$es_filia) ORDER BY zgl_nr DESC";

$i = 1;
$result = mysql_query($sql, $conn_hd) or die($k_b);
while ($newArray = mysql_fetch_array($result)) {
	$temp_id			= $newArray['zgl_id'];
	$temp_nr			= $newArray['zgl_nr'];
	$temp_pnr			= $newArray['zgl_poczta_nr'];
	$temp_data			= $newArray['zgl_data'];
	$temp_godzina		= $newArray['zgl_godzina'];
	$temp_temat			= $newArray['zgl_temat'];
	$temp_status		= $newArray['zgl_status'];
	$temp_kategoria		= $newArray['zgl_kategoria'];
	$temp_podkategoria	= $newArray['zgl_podkategoria'];
	$temp_priorytet		= $newArray['zgl_priorytet'];
	$temp_or			= $newArray['zgl_osoba_rejestrujaca'];
	$temp_zgl_seryjne	= $newArray['zgl_poledodatkowe2'];
	$temp_unikalny_nr	= $newArray['zgl_unikalny_nr'];
	$temp_komorka		= $newArray['zgl_komorka'];
	$temp_osoba			= $newArray['zgl_osoba'];
	$temp_czy_ww		= $newArray['zgl_wymagany_wyjazd'];
	$temp_czy_rozwiazany	= $newArray['zgl_czy_rozwiazany_problem'];
	
	if ($KolorujWgStatusow==1) {
		switch ($temp_kategoria) {
			case 6:	$kolor='#F73B3B';
					$span_pracownicy .= '<tr id='.$i.' style=background:'.$kolor.'>';
					break;
					
			case 2:	if ($temp_priorytet==2) { 
						$kolor='#FF7F2A';
						$span_pracownicy .= "<tr id=".$i." style='background:".$kolor."' >";
						break;
					}
					if ($temp_priorytet==4) { 
						$kolor='#F73B3B';
						$span_pracownicy .= "<tr id=".$i." style='background:".$kolor."' >";
						break;
					}				
			case 3:	if ($temp_priorytet==3) { 
						$kolor='#FFAA7F';
						$span_pracownicy .= "<tr id=".$i." style='background:".$kolor."' >";
						break;						
						}
			default: if ($temp_status==9) { 
						$kolor='#FFFFFF';
						$span_pracownicy .= "<tr id=".$i." style='background:".$kolor."' >";
						break;								
						} else {
							$kolor='';
						}
		}
	} else {
		$span_pracownicy .= "<tr id=".$i." class=nieparzyste>";
	}
	
	$temp_d = "Data rejestracji zgłoszenia: ";
	$temp_d .= $temp_data;
	$temp_d .= ' ';
	$temp_d .= substr($temp_godzina,0,5);
	
	$span_pracownicy .= '<td><a class=normalfont title=\'Zmień status zgłoszenia nr '.$temp_nr.'. '.$temp_d.'\' href=# onclick=\'self.close(); newWindow_r(800,600,"hd_o_zgloszenia_zs.php?id='.$temp_id.'&unr='.$temp_unikalny_nr.'&nr='.$temp_nr.'&ts='.$temp_status.'&tk='.$temp_kategoria.'&tpk='.$temp_podkategoria.'&zgoda=9&komorka='.urlencode($temp_komorka).'&osoba='.urlencode($temp_osoba).'&zgl_s='.$zgl_seryjne_mark.'&rozwiazany='.$temp_czy_rozwiazany.'&ww='.$temp_ww.'&clearcookies=1"); return false;\'>'.$temp_temat.'</a>';
	
	if ($temp_pnr!='') $span_pracownicy .= '<span style=\'float:right;\'>'.$temp_pnr.'</span>';
	
	$span_pracownicy .= '</td>';
	
	//$span_pracownicy .= '<td><a title=\''.$temp_d.'\'>'.$temp_temat.'</a></td>';
	$span_pracownicy .= '<td class=center>'.$temp_or.'</td>';
	
	if ($czy_wyroznic_zgloszenia_seryjne==1) {
		if ($temp_zgl_seryjne!='') { $zgl_seryjne_mark = '1'; } else { $zgl_seryjne_mark = ''; }
	} else { $zgl_seryjne_mark = ''; }
			
	$span_pracownicy .= '<td class=center>';
	
	//newWindow_r(800,600,"hd_o_zgloszenia_zs.php?id='.$temp_id.'&nr='.$temp_nr.'&zgl_s='.$zgl_seryjne_mark.'");
	$span_pracownicy .= '<a href=# onclick=\'self.close(); newWindow_r(800,600,"hd_o_zgloszenia_zs.php?id='.$temp_id.'&unr='.$temp_unikalny_nr.'&nr='.$temp_nr.'&ts='.$temp_status.'&tk='.$temp_kategoria.'&tpk='.$temp_podkategoria.'&zgoda=9&komorka='.urlencode($temp_komorka).'&osoba='.urlencode($temp_osoba).'&zgl_s='.$zgl_seryjne_mark.'&rozwiazany='.$temp_czy_rozwiazany.'&ww='.$temp_ww.'&clearcookies=1"); return false;\'>';
	
	switch ($temp_status) {
		case "1"	: $span_pracownicy .= '<input title=\'Status: nowe. Zmień status zgłoszenia nr '.$temp_nr.'. Okno z nowo rejestrowanym zgłoszeniem zostanie zamknięte.\' class=imgoption type=image src=img/zgl_nowe.gif>'; break;
		case "2"	: $span_pracownicy .= '<input title=\'Status: przypisane. Zmień status zgłoszenia nr '.$temp_nr.'. Okno z nowo rejestrowanym zgłoszeniem zostanie zamknięte.\' class=imgoption type=image src=img/zgl_przypisane.gif>'; break;
		case "3"	: $span_pracownicy .= '<input title=\'Status: rozpoczęte. Zmień status zgłoszenia nr '.$temp_nr.'. Okno z nowo rejestrowanym zgłoszeniem zostanie zamknięte.\' class=imgoption type=image src=img/zgl_rozpoczete.gif>'; break;
		case "3A"	: $span_pracownicy .= '<input title=\'Status: w serwisie zewnętrznym. Zmień status zgłoszenia nr '.$temp_nr.'. Okno z nowo rejestrowanym zgłoszeniem zostanie zamknięte.\' class=imgoption type=image src=img/zgl_w_serwisie_zewnetrznym.gif>'; break;
		case "3B"	: $span_pracownicy .= '<input title=\'Status: w firmie. Zmień status zgłoszenia nr '.$temp_nr.'. Okno z nowo rejestrowanym zgłoszeniem zostanie zamknięte.\' class=imgoption type=image src=img/zgl_w_firmie.gif>'; break;
		case "4"	: $span_pracownicy .= '<input title=\'Status: oczekiwanie na odpowiedź klienta. Zmień status zgłoszenia nr '.$temp_nr.'. Okno z nowo rejestrowanym zgłoszeniem zostanie zamknięte.\' class=imgoption type=image src=img/zgl_oczekiwanie_na_odpowiedz.gif>'; break;
		case "5"	: $span_pracownicy .= '<input title=\'Status: oczekiwanie na sprzęt. Zmień status zgłoszenia nr '.$temp_nr.'. Okno z nowo rejestrowanym zgłoszeniem zostanie zamknięte.\' class=imgoption type=image src=img/zgl_oczekiwanie_na_sprzet.gif>'; break;
		case "6"	: $span_pracownicy .= '<input title=\'Status: do oddania. Zmień status zgłoszenia nr '.$temp_nr.'. Okno z nowo rejestrowanym zgłoszeniem zostanie zamknięte.\' class=imgoption type=image src=img/zgl_do_oddania.gif>'; break;
		case "7"	: $span_pracownicy .= '<input title=\'Status: rozpoczęte - nie zakończone. Zmień status zgłoszenia nr '.$temp_nr.'. Okno z nowo rejestrowanym zgłoszeniem zostanie zamknięte.\' class=imgoption type=image src=img/zgl_rozpoczete_nie_zakonczone.gif>'; break;		
	}
	$span_pracownicy .= '</a>';
	
	$span_pracownicy .= '</td>';
	$span_pracownicy .= '<td class=center>';
	
	
	$span_pracownicy .= '<a title=\'Pokaż kroki zgłoszenia '.$temp_nr.'\' href=# onclick=\'if (confirm("Czy zamknąć okno nowo rejestrowanego zgłoszenia przed pokazaniem kroków zgłoszenia nr '.$temp_nr.' ?")) { self.close(); } newWindow_r('.$_COOKIE[max_x].','.$_COOKIE[max_y].',"hd_p_zgloszenie_kroki.php?newwindow=1&id='.$temp_id.'&nr='.$temp_nr.'&readonly=0"); return false;\'><img class=imgoption style=border:0px type=image src=img/expand.gif width=11 height=11></a>';
	
	$span_pracownicy .= '</td>';
	$span_pracownicy .= '</tr>';
	
	$i++;
}
$span_pracownicy .= '</table>';

if ($i>1) { 
	echo $span_pracownicy;
?>
<script>$('#lista_zgloszen').show();</script>
<?php } else { ?>
<script>$('#lista_zgloszen').hide();</script>
<?php } ?>