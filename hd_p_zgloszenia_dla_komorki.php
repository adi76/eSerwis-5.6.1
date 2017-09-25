<?php 
include_once('header.php');
include_once('cfg_eserwis.php');
include_once('cfg_helpdesk.php');

function toUpper($string) {
	$string = strtr($string,'qwertyuiopasdfghjklzxcvbnm','QWERTYUIOPASDFGHJKLZXCVBNM');
	return (strtr($string, 'ęóąśłżźćń','ĘÓĄŚŁŻŹĆŃ' )); 
}; 
echo "<body>";
//$span_pracownicy = '<b>'.$_GET[komorka].'</b>';

$sql2 = "SELECT * FROM $dbname_hd.hd_zgloszenie WHERE ";
if ($_REQUEST[showall]==0) $sql2.= "(zgl_status<>9) and ";

if ($_REQUEST[sprzedaz]=='1') $sql2 .= " (zgl_kategoria!=1) and ";
if ($_REQUEST[sprzedaz]=='1z') $sql2 .= " (zgl_kategoria!=1) and ";
if ($_REQUEST[sprzedaz]=='1e') $sql2 .= " (zgl_kategoria!=1) and ";

$sql2.= " (UPPER(zgl_komorka)='".toUpper($_REQUEST[komorka])."') and (zgl_widoczne=1) and (belongs_to=$es_filia) ORDER BY zgl_id DESC";

//echo $sql2;

$i = 1;
$result2 = mysql_query($sql2, $conn_hd) or die($k_b);
$count_rows1 = mysql_num_rows($result2);

if ($count_rows1>0) {

	if ($_REQUEST[showall]=='1') {
		pageheader("Wszystkie zgłoszenia dla komórki <b><font color=red>".toUpper($_GET[komorka])."</font></b>",0,0);
	} else {
		pageheader("Otwarte zgłoszenia dla komórki <b><font color=red>".toUpper($_GET[komorka])."</font></b>",0,0);
	}

	?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
		
	echo "<table width=100% cellspacing=1 cellpadding=1><tr><th class=center>Nr zgłoszenia</th><th class=center width=40>Status</th><th width=100>Data zgłoszenia</th><th>Temat zgłoszenia</th><th class=center>Opcje</th></tr>";


	while ($newArray2 = mysql_fetch_array($result2)) {
		$temp_id			= $newArray2['zgl_id'];
		$temp_nr			= $newArray2['zgl_nr'];
		$temp_data			= $newArray2['zgl_data'];
		$temp_godzina		= $newArray2['zgl_godzina'];
		$temp_temat			= $newArray2['zgl_temat'];	
		$temp_status		= $newArray2['zgl_status'];	
		$temp_kategoria		= $newArray2['zgl_kategoria'];
		$temp_priorytet		= $newArray2['zgl_priorytet'];
		$temp_or			= $newArray2['zgl_osoba_rejestrujaca'];
		$temp_zgl_seryjne	= $newArray2['zgl_poledodatkowe2'];
		$temp_czy_pow_z_wp	= $newArray2['zgl_czy_powiazane_z_wymiana_podzespolow'];
		$temp_naprawa_id	= $newArray2['zgl_naprawa_id'];
		
		$temp_nrawarii		= $newArray2['zgl_poledodatkowe1'];
		$temp_parent_zgl	= $newArray2['zgl_kontynuacja_zgloszenia_numer'];
		$temp_rekl_czy_jest = $newArray2['zgl_czy_to_jest_reklamacyjne'];
		$temp_rekl_nr	 	= $newArray2['zgl_nr_zgloszenia_reklamowanego'];
		$temp_rekl_czy_ma	= $newArray2['zgl_czy_ma_zgl_reklamacyjne'];		
		$temp_naprawa_id	= $newArray2['zgl_naprawa_id'];
		$temp_czy_pow_z_wp	= $newArray2['zgl_czy_powiazane_z_wymiana_podzespolow'];
		$temp_czy_ww		= $newArray2['zgl_wymagany_wyjazd'];
		$temp_czy_ww_data	= $newArray2['zgl_wymagany_wyjazd_data_ustawienia'];		
		$temp_czy_ww_data 	= substr($temp_czy_ww_data,0,16);
		$temp_czy_ww_osoba	= $newArray2['zgl_wymagany_wyjazd_osoba_wlaczajaca'];
		$temp_ss_id			= $newArray2['zgl_sprzet_serwisowy_id'];
				
		if ($KolorujWgStatusow==1) {
				switch ($temp_kategoria) {
				case 6:	$kolor='#F73B3B';
						echo "<tr id=$i style=background:'.$kolor.'>";
						break;
						
				case 2:	if ($temp_priorytet==2) { 
							$kolor='#FF7F2A';
							echo "<tr id=$i style='background:".$kolor."' >";
							break;
						}
						if ($temp_priorytet==4) { 
							$kolor='#F73B3B';
							echo "<tr id=$i style='background:".$kolor."' >";
							break;
						}				
				case 3:	if ($temp_priorytet==3) { 
							$kolor='#FFAA7F';
							echo "<tr id=$i style='background:".$kolor."' >";
							break;						
							}
				default: if ($temp_status==9) { 
							$kolor='#FFFFFF';
							echo "<tr id=$i style='background:".$kolor."' >";
							break;								
							} else {
								$kolor='';
							}
			}
		} else {
			echo "<tr id=$i class=nieparzyste>";
		}
		
		$temp_d = "Data rejestracji zgłoszenia: ";
		$temp_d .= $temp_data;
		$temp_d .= ' ';
		$temp_d .= $temp_godzina;
				
		echo "<td class=center>$temp_nr <a title='Pokaż kroki zgłoszenia $temp_nr w nowym oknie' href=# onclick=\"newWindow_r($_COOKIE[max_x],$_COOKIE[max_y],'hd_p_zgloszenie_kroki.php?newwindow=1&id=$temp_id&nr=$temp_nr&readonly=1'); return false;\"><img class=imgoption style=border:0px type=image src=img/expand.gif width=11 height=11></a>";
		
		if ($temp_parent_zgl!=0) {
			echo "<input title=' Zgłoszenie utworzono na bazie zgłoszenia numer $temp_parent_zgl ' class=imgoption type=image src=img/have_parent.gif>";
		}
		
		if ($temp_naprawa_id>0) {
			echo "&nbsp;<a title=' Zgłoszenie powiązane z naprawą ' href=#><img class=imgoption src=img/naprawa_unknown.gif border=0 width=16 height=16></a>";		
		}
				
		if ($temp_czy_pow_z_wp==1) {
			echo "&nbsp;<a title='Zgłoszenie powiązane z wymianą podzespołów w sprzęcie: $wp_o (SN: $wp_sn, NI: $wp_ni)' href=#><img class=imgoption src=img/wp.gif border=0 width=16 height=16></a>";
		}
		
		if ($temp_ss_id>0) {
			echo "&nbsp;<a title=' Zgłoszenie powiązane z przekazaniem sprzętu serwisowego ' href=#><img class=imgoption src=img/service.gif border=0 width=16 height=16></a>";		
		}

		if (($temp_nrawarii!='') && ($temp_status!=9)) 
			echo "&nbsp;<a title=' Zgłoszenie powiązane z otwartą awarią WAN ' href=#><img class=imgoption src=img/wan_disconnect.gif border=0></a>";

		if (($temp_nrawarii!='') && ($temp_status==9)) 
			echo "&nbsp;<a title=' Zgłoszenie powiązane z zamkniętą awarią WAN ' href=# ><img class=imgoption src=img/wan_connect.gif border=0></a>";	

		if ($temp_rekl_czy_ma==1) {
			list($rekl_nr)=mysql_fetch_array(mysql_query("SELECT zgl_nr FROM hd_zgloszenie WHERE (zgl_nr_zgloszenia_reklamowanego=$temp_nr) LIMIT 1"));
					
			echo "<a href=# title='To zgłoszenie było reklamowane przez klienta. Utworzono z niego zgłoszenie reklamacyjne o numerze $rekl_nr'><input class=imgoption type=image src=img/is_reklamacyjne.gif></a>";
		}
				
		if ($temp_rekl_czy_jest==1) {
			echo "<a href=# title='To jest zgłoszenie reklamacyjne do zgłoszenia nr $temp_rekl_nr'><input class=imgoption type=image src=img/have_reklamacyjne.gif></a>";
		}

		if ($temp_czy_ww==1) {
			echo "<a href=# title='Wymaga wyjazdu. Ustawione przez $temp_czy_ww_osoba w dniu $temp_czy_ww_data'><input class=imgoption type=image src=img/car_ww.gif></a>";
		}
				
		echo "</td>";
		
		echo '<td class=center>';		
			//echo '<a href=# onclick=\'self.close(); newWindow_r(800,600,"hd_o_zgloszenia.php?action=obsluga&id='.$temp_id.'&nr='.$temp_nr.'&zgl_s='.$zgl_seryjne_mark.'"); return false;\'>';
			
			switch ($temp_status) {
				case "1"	: echo '<input title=\'Status: nowe.\' class=imgoption type=image src=img/zgl_nowe.gif>'; break;
				case "2"	: echo '<input title=\'Status: przypisane.\' class=imgoption type=image src=img/zgl_przypisane.gif>'; break;
				case "3"	: echo '<input title=\'Status: rozpoczęte.\' class=imgoption type=image src=img/zgl_rozpoczete.gif>'; break;
				case "3A"	: echo '<input title=\'Status: w serwisie zewnętrznym.\' class=imgoption type=image src=img/zgl_w_serwisie_zewnetrznym.gif>'; break;
				case "3B"	: echo '<input title=\'Status: w firmie.\' class=imgoption type=image src=img/zgl_w_firmie.gif>'; break;
				case "4"	: echo '<input title=\'Status: oczekiwanie na odpowiedź klienta.\' class=imgoption type=image src=img/zgl_oczekiwanie_na_odpowiedz.gif>'; break;
				case "5"	: echo '<input title=\'Status: oczekiwanie na sprzęt.\' class=imgoption type=image src=img/zgl_oczekiwanie_na_sprzet.gif>'; break;
				case "6"	: echo '<input title=\'Status: do oddania.\' class=imgoption type=image src=img/zgl_do_oddania.gif>'; break;
				case "7"	: echo '<input title=\'Status: rozpoczęte - nie zakończone.\' class=imgoption type=image src=img/zgl_rozpoczete_nie_zakonczone.gif>'; break;		
				case "9"	: echo "<input class=imgoption type=image src=img/zgl_zamkniete.gif>"; break;
			}
			//echo '</a>';		
		echo '</td>';
				
		echo "<td><a class=normalfont href=#>$temp_data ".substr($temp_godzina,0,5)."</a></td>";
		echo "<td><a class=normalfont href=#>$temp_temat</a></td>";
		
		if ($czy_wyroznic_zgloszenia_seryjne==1) {
			if ($temp_zgl_seryjne!='') { $zgl_seryjne_mark = '1'; } else { $zgl_seryjne_mark = ''; }
		} else { $zgl_seryjne_mark = ''; }

		echo '<td class=center>';
		
		if ($_REQUEST[sprzedaz]=='1') {
		//http://10.216.39.150/serwis/z_towary_obrot.php?id=8920&f=1631&new_upid=21&trodzaj=Towar&tdata=2012-01-03&nazwa_sprzetu=Klawiatura+A4-Tech+Stilo+X%60C+CAB%2C+standard%2C+PS%2F2&sn_sprzetu=110900233&tuwagi=&obzp=1&tumowa=0&allow_change_rs=0&ewid_id=&quiet=&nazwa_urzadzenia=&sn_urzadzenia=&ni_urzadzenia=&readonly=&hd_zgl_nr=&dodajwymianepodzespolow=1&_wp_opis=&_wp_sn=&_wp_ni=
		
			echo "<a title=' Pobierz numer tego zgłoszenia '><input class=imgoption type=image src=img/ok.gif onclick=\"if (opener) opener.location.href='".$_REQUEST[file]."?id=".$_REQUEST[id]."&f=".$_REQUEST[f]."&new_upid=".$_REQUEST[new_upid]."&hd_zgl_nr=".$temp_nr."&trodzaj=".$_REQUEST[trodzaj]."&tdata=".$_REQUEST[tdata]."&nazwa_sprzetu=".urlencode($_REQUEST[nazwa_sprzetu])."&sn_sprzetu=".urlencode($_REQUEST[sn_sprzetu])."&tuwagi=".urlencode($_REQUEST[tuwagi])."&obzp=".$_REQUEST[obzp]."&tumowa=".urlencode($_REQUEST[tumowa])."&allow_change_rs=".$_REQUEST[allow_change_rs]."&ewid_id=".$_REQUEST[ewid_id]."&quiet=".$_REQUEST[quiet]."&nazwa_urzadzenia=".urlencode($_REQUEST[nazwa_urzadzenia])."&sn_urzadzenia=".urlencode($_REQUEST[sn_urzadzenia])."&ni_urzadzenia=".urlencode($_REQUEST[ni_urzadzenia])."&readonly=".$_REQUEST[readonly]."&dodajwymianepodzespolow=".$_REQUEST[dodajwymianepodzespolow]."&_wp_opis=".urlencode($_REQUEST[_wp_opis])."&_wp_sn=".urlencode($_REQUEST[_wp_sn])."&_wp_ni=".urlencode($_REQUEST[_wp_ni])."'; self.close(); return false;\"></a>";
		} elseif ($_REQUEST[sprzedaz]=='1z') {
			echo "<a title=' Pobierz numer tego zgłoszenia '><input class=imgoption type=image src=img/ok.gif onclick=\"if (opener) opener.location.href='".$_REQUEST[file]."?id=".$_REQUEST[id]."&f=".$_REQUEST[f]."&new_upid=".$_REQUEST[new_upid]."&hd_zgl_nr=".$temp_nr."&trodzaj=".$_REQUEST[trodzaj]."&tdata=".$_REQUEST[tdata]."&nazwa_sprzetu=".urlencode($_REQUEST[nazwa_sprzetu])."&sn_sprzetu=".urlencode($_REQUEST[sn_sprzetu])."&tuwagi=".urlencode($_REQUEST[tuwagi])."&obzp=".$_REQUEST[obzp]."&tumowa=".urlencode($_REQUEST[tumowa])."&allow_change_rs=".$_REQUEST[allow_change_rs]."&ewid_id=".$_REQUEST[ewid_id]."&quiet=".$_REQUEST[quiet]."&nazwa_urzadzenia=".urlencode($_REQUEST[nazwa_urzadzenia])."&sn_urzadzenia=".urlencode($_REQUEST[sn_urzadzenia])."&ni_urzadzenia=".urlencode($_REQUEST[ni_urzadzenia])."&readonly=".$_REQUEST[readonly]."&dodajwymianepodzespolow=".$_REQUEST[dodajwymianepodzespolow]."&_wp_opis=".urlencode($_REQUEST[_wp_opis])."&_wp_sn=".urlencode($_REQUEST[_wp_sn])."&_wp_ni=".urlencode($_REQUEST[_wp_ni])."'; self.close(); return false;\"></a>";
		} elseif ($_REQUEST[sprzedaz]=='1e') {
			echo "<a title=' Pobierz numer tego zgłoszenia '><input class=imgoption type=image src=img/ok.gif onclick=\"if (opener) opener.location.href='".$_REQUEST[file]."?id=".$_REQUEST[id]."&f=".$_REQUEST[f]."&new_upid=".$_REQUEST[new_upid]."&hd_zgl_nr=".$temp_nr."&trodzaj=".$_REQUEST[trodzaj]."&tdata=".$_REQUEST[tdata]."&nazwa_sprzetu=".urlencode($_REQUEST[nazwa_sprzetu])."&sn_sprzetu=".urlencode($_REQUEST[sn_sprzetu])."&tuwagi=".urlencode($_REQUEST[tuwagi])."&obzp=".$_REQUEST[obzp]."&tumowa=".urlencode($_REQUEST[tumowa])."&allow_change_rs=".$_REQUEST[allow_change_rs]."&ewid_id=".$_REQUEST[ewid_id]."&quiet=".$_REQUEST[quiet]."&nazwa_urzadzenia=".urlencode($_REQUEST[nazwa_urzadzenia])."&sn_urzadzenia=".urlencode($_REQUEST[sn_urzadzenia])."&ni_urzadzenia=".urlencode($_REQUEST[ni_urzadzenia])."&readonly=".$_REQUEST[readonly]."&dodajwymianepodzespolow=".$_REQUEST[dodajwymianepodzespolow]."&_wp_opis=".urlencode($_REQUEST[_wp_opis])."&_wp_sn=".urlencode($_REQUEST[_wp_sn])."&_wp_ni=".urlencode($_REQUEST[_wp_ni])."'; self.close(); return false;\"></a>";
		} else {
			echo "<a title=' Pobierz numer tego zgłoszenia '><input class=imgoption type=image src=img/ok.gif onclick=\"if (opener) opener.location.href='".$_REQUEST[file]."?id=$_REQUEST[id]&part=".urlencode($_REQUEST[part])."&new_upid=$_REQUEST[new_upid]&hd_zgl_nr=$temp_nr&powiazzhd=$_REQUEST[powiazzhd]&tkomentarz=".urlencode($_REQUEST[tkomentarz])."'; self.close(); return false;\"></a>";
		}
		
		echo '</td>';
		echo '</tr>';
		
		$i++;
	}
	echo '</table>';
	
	startbuttonsarea("right");
	
	echo "<span style='float:left'>";
	if (($_REQUEST[showall]=='0')) {
		echo "<input type=button class=buttons style='color:blue' value='Pokaż wszystkie zgłoszenia z wybranej komórki' onClick=\"self.location.href='hd_p_zgloszenia_dla_komorki.php?randval=".date('Ymdhim')."".rand()."&komorka=".urlencode($_REQUEST[komorka])."&showall=1&id=".$_REQUEST[id]."&f=".$_REQUEST[f]."&new_upid=".$_REQUEST[new_upid]."&hd_zgl_nr=".$_REQUEST[hd_zgl_nr]."&trodzaj=".$_REQUEST[trodzaj]."&tdata=".$_REQUEST[tdata]."&nazwa_sprzetu=".urlencode($_REQUEST[nazwa_sprzetu])."&sn_sprzetu=".urlencode($_REQUEST[sn_sprzetu])."&tuwagi=".urlencode($_REQUEST[tuwagi])."&obzp=".$_REQUEST[obzp]."&tumowa=".urlencode($_REQUEST[tumowa])."&allow_change_rs=".$_REQUEST[allow_change_rs]."&ewid_id=".$_REQUEST[ewid_id]."&quiet=".$_REQUEST[quiet]."&nazwa_urzadzenia=".urlencode($_REQUEST[nazwa_urzadzenia])."&sn_urzadzenia=".urlencode($_REQUEST[sn_urzadzenia])."&ni_urzadzenia=".urlencode($_REQUEST[ni_urzadzenia])."&readonly=".$_REQUEST[readonly]."&dodajwymianepodzespolow=".$_REQUEST[dodajwymianepodzespolow]."&_wp_opis=".urlencode($_REQUEST[_wp_opis])."&_wp_sn=".urlencode($_REQUEST[_wp_sn])."&_wp_ni=".urlencode($_REQUEST[_wp_ni])."&file=".$_REQUEST[file]."&sprzedaz=1'; return false;\" />";
	}
	if (($_REQUEST[showall]=='1')) {
		echo "<input type=button class=buttons style='color:green' value='Pokaż otwarte zgłoszenia z wybranej komórki' onClick=\"self.location.href='hd_p_zgloszenia_dla_komorki.php?randval=".date('Ymdhim')."".rand()."&komorka=".urlencode($_REQUEST[komorka])."&showall=0&id=".$_REQUEST[id]."&f=".$_REQUEST[f]."&new_upid=".$_REQUEST[new_upid]."&hd_zgl_nr=".$_REQUEST[hd_zgl_nr]."&trodzaj=".$_REQUEST[trodzaj]."&tdata=".$_REQUEST[tdata]."&nazwa_sprzetu=".urlencode($_REQUEST[nazwa_sprzetu])."&sn_sprzetu=".urlencode($_REQUEST[sn_sprzetu])."&tuwagi=".urlencode($_REQUEST[tuwagi])."&obzp=".$_REQUEST[obzp]."&tumowa=".urlencode($_REQUEST[tumowa])."&allow_change_rs=".$_REQUEST[allow_change_rs]."&ewid_id=".$_REQUEST[ewid_id]."&quiet=".$_REQUEST[quiet]."&nazwa_urzadzenia=".urlencode($_REQUEST[nazwa_urzadzenia])."&sn_urzadzenia=".urlencode($_REQUEST[sn_urzadzenia])."&ni_urzadzenia=".urlencode($_REQUEST[ni_urzadzenia])."&readonly=".$_REQUEST[readonly]."&dodajwymianepodzespolow=".$_REQUEST[dodajwymianepodzespolow]."&_wp_opis=".urlencode($_REQUEST[_wp_opis])."&_wp_sn=".urlencode($_REQUEST[_wp_sn])."&_wp_ni=".urlencode($_REQUEST[_wp_ni])."&file=".$_REQUEST[file]."&sprzedaz=1'; return false;\" />";	
	}
	
	echo "</span>";
	
	addbuttons("zamknij");
	endbuttonsarea();
} else {
	if ($_REQUEST[showall]==0) errorheader("Brak otwartych zgłoszeń dla komórki <font color=red>".toUpper($_REQUEST[komorka])."</font>");
	if ($_REQUEST[showall]==1) errorheader("Brak zgłoszeń dla komórki <font color=red>".toUpper($_REQUEST[komorka])."</font>");
	
	startbuttonsarea("right");
	
	echo "<span style='float:left'>";
	if (($_REQUEST[showall]=='0')) {
		echo "<input type=button class=buttons style='color:blue' value='Pokaż wszystkie zgłoszenia z wybranej komórki' onClick=\"self.location.href='hd_p_zgloszenia_dla_komorki.php?randval=".date('Ymdhim')."".rand()."&komorka=".urlencode($_REQUEST[komorka])."&showall=1&id=".$_REQUEST[id]."&f=".$_REQUEST[f]."&new_upid=".$_REQUEST[new_upid]."&hd_zgl_nr=".$_REQUEST[hd_zgl_nr]."&trodzaj=".$_REQUEST[trodzaj]."&tdata=".$_REQUEST[tdata]."&nazwa_sprzetu=".urlencode($_REQUEST[nazwa_sprzetu])."&sn_sprzetu=".urlencode($_REQUEST[sn_sprzetu])."&tuwagi=".urlencode($_REQUEST[tuwagi])."&obzp=".$_REQUEST[obzp]."&tumowa=".urlencode($_REQUEST[tumowa])."&allow_change_rs=".$_REQUEST[allow_change_rs]."&ewid_id=".$_REQUEST[ewid_id]."&quiet=".$_REQUEST[quiet]."&nazwa_urzadzenia=".urlencode($_REQUEST[nazwa_urzadzenia])."&sn_urzadzenia=".urlencode($_REQUEST[sn_urzadzenia])."&ni_urzadzenia=".urlencode($_REQUEST[ni_urzadzenia])."&readonly=".$_REQUEST[readonly]."&dodajwymianepodzespolow=".$_REQUEST[dodajwymianepodzespolow]."&_wp_opis=".urlencode($_REQUEST[_wp_opis])."&_wp_sn=".urlencode($_REQUEST[_wp_sn])."&_wp_ni=".urlencode($_REQUEST[_wp_ni])."&file=".$_REQUEST[file]."&sprzedaz=1'; return false;\" />";
	}
	if (($_REQUEST[showall]=='1')) {
		echo "<input type=button class=buttons style='color:green' value='Pokaż otwarte zgłoszenia z wybranej komórki' onClick=\"self.location.href='hd_p_zgloszenia_dla_komorki.php?randval=".date('Ymdhim')."".rand()."&komorka=".urlencode($_REQUEST[komorka])."&showall=0&id=".$_REQUEST[id]."&f=".$_REQUEST[f]."&new_upid=".$_REQUEST[new_upid]."&hd_zgl_nr=".$_REQUEST[hd_zgl_nr]."&trodzaj=".$_REQUEST[trodzaj]."&tdata=".$_REQUEST[tdata]."&nazwa_sprzetu=".urlencode($_REQUEST[nazwa_sprzetu])."&sn_sprzetu=".urlencode($_REQUEST[sn_sprzetu])."&tuwagi=".urlencode($_REQUEST[tuwagi])."&obzp=".$_REQUEST[obzp]."&tumowa=".urlencode($_REQUEST[tumowa])."&allow_change_rs=".$_REQUEST[allow_change_rs]."&ewid_id=".$_REQUEST[ewid_id]."&quiet=".$_REQUEST[quiet]."&nazwa_urzadzenia=".urlencode($_REQUEST[nazwa_urzadzenia])."&sn_urzadzenia=".urlencode($_REQUEST[sn_urzadzenia])."&ni_urzadzenia=".urlencode($_REQUEST[ni_urzadzenia])."&readonly=".$_REQUEST[readonly]."&dodajwymianepodzespolow=".$_REQUEST[dodajwymianepodzespolow]."&_wp_opis=".urlencode($_REQUEST[_wp_opis])."&_wp_sn=".urlencode($_REQUEST[_wp_sn])."&_wp_ni=".urlencode($_REQUEST[_wp_ni])."&file=".$_REQUEST[file]."&sprzedaz=1'; return false;\" />";	
	}
	
	echo "</span>";
	addbuttons("zamknij");
	endbuttonsarea();
}
?>

<script>HideWaitingMessage();</script><?php 

echo "</body>";
echo "</html>";