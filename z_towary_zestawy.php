<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php'); 
?>
<body>
<?php include('body_start.php');
$result = mysql_query("SELECT zestaw_id FROM $dbname.serwis_zestawy WHERE belongs_to=$es_filia", $conn) or die($k_b);
$count_rows = mysql_num_rows($result);
if ($count_rows!=0) {
	pageheader("Przeglądanie utworzonych zestawów",1,1);
	
	?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
	
	startbuttonsarea("center");
		if ($showall==0) {
			echo "<a class=paging href=$phpfile?showall=1&paget=$page>Pokaż wszystko na jednej stronie</a>";
		} else {
			echo "<a class=paging href=$phpfile?showall=0&page=$paget>Dziel na strony</a>";	
		}
	echo "| Łącznie: <b>$count_rows pozycji</b>";	
	endbuttonsarea();
	// paging
	$totalrows = mysql_num_rows($result);
	if ($showall==0) { $rps=$rowpersite; } else $rps=10000;
	if(empty($page)) { $page = 1; }
	$limitvalue = $page * $rps - ($rps);
	$sql="SELECT zestaw_id,zestaw_opis,zestaw_kto,zestaw_kiedy,zestaw_uwagi,zestaw_status,zestaw_from_hd,zestaw_hd_zgl_nr FROM $dbname.serwis_zestawy WHERE belongs_to=$es_filia ORDER BY zestaw_kiedy DESC LIMIT $limitvalue, $rps";
	$result=mysql_query($sql, $conn) or die($k_b);
	// koniec - paging
	starttable();
	th("30;c;LP|;;Opis zestawu|;c;Ilość<br />pozycji|;;Utworzony przez<br />Data utworzenia|;c;Uwagi|;c;Opcje",$es_prawa);
	$i = 0;
	$j = $page*$rowpersite-$rowpersite+1;
	while (list($temp_id,$temp_opis,$temp_kto,$temp_kiedy,$temp_uwagi,$temp_status,$temp_zestaw_from_hd,$temp_zestaw_zgl_nr)=mysql_fetch_array($result)) {
		tbl_tr_highlight($i);
			$ilepozycji=0;
			list($ilepozycji)=mysql_fetch_array(mysql_query("SELECT count(*) FROM $dbname.serwis_zestaw_szcz WHERE zestawpozycja_zestaw_id=$temp_id",$conn));
			list($pozycja)=mysql_fetch_array(mysql_query("SELECT zestawpozycja_fszcz_id FROM $dbname.serwis_zestaw_szcz WHERE zestawpozycja_zestaw_id=$temp_id LIMIT 1",$conn));
			
			list($hd_zgl_nr)=mysql_fetch_array(mysql_query("SELECT wp_zgl_id FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE wp_sprzedaz_fakt_szcz_id='$pozycja'",$conn));
			
			//td("30;c;".$j."");
			td("30;c;<a class=normalfont href=# title=' $temp_id '>".$j."</a>");
			td_(";;<a class=normalfont href=# title=' Pokaż pozycje zestawu : $temp_opis ' onClick=\"newWindow_r(800,600,'p_zestaw_pozycje.php?id=$temp_id&sold=$temp_status')\">".$temp_opis."</a>");
			
			//<a title=' Pokaż pozycje zestawu : $temp_opis '><input class=imgoption type=image src=img/search.gif  onclick=\"newWindow_r(800,600,'p_zestaw_pozycje.php?id=$temp_id&sold=$temp_status')\"></a>
				if ($pokazpoczatekuwag==1) pokaz_uwagi($temp_uwagi,$iloscznakowuwag,"newWindow(480,265,'p_zestaw_uwagi.php?id=$temp_id')");
				//if ($temp_zestaw_from_hd==1) echo "<br /><font color=red>Zestaw powiązany ze zgłoszeniem Helpdesk</font>";
			_td();
			td(";c;".$ilepozycji."|;;".$temp_kto."<br />".substr($temp_kiedy,0,16)."");
			$j++;	
			td_img(";c");
				if ($temp_uwagi!='') {
					echo "<a title=' Czytaj uwagi '><input type=image class=imgoption src=img/comment.gif onclick=\"newWindow(480,265,'p_zestaw_uwagi.php?id=$temp_id')\"></a>";
				} 
				echo "<a title=' Edytuj uwagi '><input type=image class=imgoption src=img/edit_comment.gif onclick=\"newWindow(480,245,'e_zestaw_uwagi.php?id=$temp_id')\"></a>";
			_td();
//			$accessLevels = array("9");
//			if (array_search($es_prawa, $accessLevels)>-1) {
			td_(";c");
				echo "<a title=' Pokaż pozycje zestawu : $temp_opis '><input class=imgoption type=image src=img/search.gif  onclick=\"newWindow_r(800,600,'p_zestaw_pozycje.php?id=$temp_id&sold=$temp_status&showall=1&paget=1')\"></a>";
				if ($temp_status==1) {
					$sql_pozycja_nr = "SELECT zestawpozycja_fszcz_id FROM $dbname.serwis_zestaw_szcz WHERE zestawpozycja_zestaw_id=$temp_id LIMIT 1";
					list($wynik_pozycja_nr) = mysql_fetch_array(mysql_query($sql_pozycja_nr,$conn));
					$sql_czy_zraportowane = "SELECT pozycja_status FROM $dbname.serwis_faktura_szcz WHERE ((pozycja_id=$wynik_pozycja_nr) and (pozycja_status=9)) LIMIT 1";
					list($zraportowane) = mysql_fetch_array(mysql_query($sql_czy_zraportowane,$conn));
					if ($zraportowane==0) {
						if ($allow_sell==1) {
							echo "<a title=' Anuluj sprzedaż zestawu: $temp_opis '><input class=imgoption type=image src='img/sell_zestaw_delete.gif' onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_zestaw_sprzedaz.php?id=$temp_id'); return false;\"></a>";
						}
					}
				}
				if (($ilepozycji==0) && ($temp_status==0)) {
					echo "<a title=' Usuń zestaw: $temp_opis z bazy '><input class=imgoption type=image src=img/delete.gif onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_zestaw.php?id=$temp_id')\"></a>";
				}
				if (($ilepozycji>0) && ($temp_status==0)) {
					if ($allow_sell==1) {
					
						if ($temp_zestaw_from_hd==1) {
							if ($temp_zestaw_zgl_nr>0) {
							
								$r1a = mysql_query("SELECT wp_zgl_id,wp_sprzet_opis, wp_sprzet_sn, wp_sprzet_ni, wp_sprzet_wykonane_czynnosci, wp_sprzedaz_fakt_szcz_id,wp_wskazanie_sprzetu_z_magazynu, wp_typ_podzespolu FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_zgl_id='$temp_zestaw_zgl_nr') and (belongs_to='$es_filia') and (wp_sprzet_active=1)", $conn_hd) or die($k_b);								
								
								list($wp_zgl_id,$wp_opis, $wp_sn, $wp_ni, $wp_wc, $wp_fszczid, $wp_z_mag, $wp_typ_pod)=mysql_fetch_array($r1a);
								$nazwa_urzadzenia = $wp_opis;
								$sn_urzadzenia = $wp_sn;
								$ni_urzadzenia = $wp_ni;
					
								$sql_komorka_name = "SELECT zgl_komorka,zgl_nr FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id=$temp_zestaw_zgl_nr) LIMIT 1";
								list($komorka_name,$hd_zgl_nr)=mysql_fetch_array(mysql_query($sql_komorka_name, $conn));
					
								$result_upid = mysql_query("SELECT up_id,up_nazwa,up_pion_id,up_umowa_id,pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (up_active=1) and (belongs_to=$es_filia) and (CONCAT(serwis_piony.pion_nazwa,' ',serwis_komorki.up_nazwa)='$komorka_name') LIMIT 1", $conn) or die($k_b);
								
								list($temp_upid,$temp_nazwa,$temp_pion_id,$temp_umowa_id,$temp_pionnazwa)=mysql_fetch_array($result_upid);

					
								echo "<a title='Sprzedaj zestaw: $temp_opis powiązany ze zgłoszeniem nr $temp_zestaw_zgl_nr'><input class=imgoption type=image src=img/sell_zestaw_hd.gif onclick=\"newWindow_r(700,595,'g_zestaw_obrot.php?id=$temp_id&new_upid=$temp_upid&allow_change_rs=0&quiet=1&ewid_id=1&readonly=0&nazwa_urzadzenia=".urlencode($nazwa_urzadzenia)."&sn_urzadzenia=".urlencode($sn_urzadzenia)."&ni_urzadzenia=".urlencode($ni_urzadzenia)."&hd_zgl_nr=$temp_zestaw_zgl_nr')\"></a>";
							}
						} else {
							echo "<a title='Sprzedaj zestaw: $temp_opis '><input class=imgoption type=image src=img/sell_zestaw.gif  onclick=\"newWindow_r(700,595,'g_zestaw_obrot.php?id=$temp_id&dodajwymianepodzespolow=1')\"></a>";
						}
						//echo "<a title=' Generuj protokół dla zestawu : $temp_opis'><input class=imgoption type=image src=img//print.gif  onclick=\"newWindow_r(700,595,'g_zestaw_protokol.php?id=$temp_id&source=towary-sprzedaz&state=empty')\"></a>";
					}
				}
				if ($temp_zestaw_from_hd==1) {
					if ($temp_zestaw_zgl_nr>0) {
						$LinkHDZglNr=$temp_zestaw_zgl_nr; include('linktohelpdesk.php');
					}
				}
			_td();
//			} 
			$i++;
		_tr();
	}
endtable();
include_once('paging_end.php');
} else { 
	errorheader("Baza zestawów jest pusta");
	?><meta http-equiv="REFRESH" content="<?php echo $http_refresh; ?>;url=<?php echo "$linkdostrony";?>main.php"><?php	

}

startbuttonsarea("right");
oddziel();
addlinkbutton("'Towary na stanie'","p_towary_dostepne.php?view=normal");
addlinkbutton("'Utwórz nowy zestaw'","p_towary_dostepne.php?view=dozestawu");
addbuttons("start");
endbuttonsarea();
include('body_stop.php');
?>

<script>HideWaitingMessage();</script>

</body>
</html>