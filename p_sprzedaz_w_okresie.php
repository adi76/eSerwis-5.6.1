<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php'); 
?>
<body>
<?php 
if ($submit) { 
	$sql_a = "SELECT sprzedaz_id FROM $dbname.serwis_sprzedaz WHERE belongs_to=$es_filia and (sprzedaz_umowa_nazwa='') ORDER BY sprzedaz_id ASC, sprzedaz_unique ASC"; 
	$result_a = mysql_query($sql_a, $conn) or die($k_b);
	$count_rows_bledne = mysql_num_rows($result_a);

	include('body_start.php');
	$tuser=$_GET[tuser];
	$okres_od1=$_GET[okres_od];
	$okres_do1=$_GET[okres_do];
	
	$okres_od=substr($okres_od1,0,10);
	$okres_do=substr($okres_do1,0,10);	
	
if ($count_rows_bledne > 0 ) { 
	errorheader("Lista sprzedanych towarów / usług błędnie zarejestrowanych w systemie");
	$sql_a = "SELECT * FROM $dbname.serwis_sprzedaz WHERE belongs_to=$es_filia and (sprzedaz_umowa_nazwa='') ORDER BY sprzedaz_id ASC, sprzedaz_unique ASC"; 
	$result_a = mysql_query($sql_a, $conn) or die($k_b);

		starttable();
		th("30;c;LP|;;Nazwa towaru<br />Numer seryjny|;;Sprzedano dla<br />Rodzaj sprzedaży - Numer zlecenia|;c;Nr zgłoszenia Helpdesk<br /><font color=red>Nr Landek</font>|;;Osoba sprzedająca<br />Data sprzedaży|;c;Opcje",$es_prawa);
		
		$result_a = mysql_query($sql_a, $conn) or die($k_b);
		$i=0;
		
		while ($newArray = mysql_fetch_array($result_a)) {		
			$temp_id  			= $newArray['sprzedaz_id'];
			$temp_poz_pid		= $newArray['sprzedaz_pozycja_id'];			
			$temp_poz_nazwa		= $newArray['sprzedaz_pozycja_nazwa'];			
			$temp_poz_sn		= $newArray['sprzedaz_pozycja_sn'];
			$temp_osoba			= $newArray['sprzedaz_osoba'];
			$temp_data			= $newArray['sprzedaz_data'];
			$temp_pion			= $newArray['sprzedaz_pion_nazwa'];
			$temp_up			= $newArray['sprzedaz_up_nazwa'];			
			$temp_umowa			= $newArray['sprzedaz_umowa_nazwa'];
			$temp_uwagi			= $newArray['sprzedaz_uwagi'];
			$temp_status		= $newArray['sprzedaz_status'];
			$temp_fakt_id		= $newArray['sprzedaz_faktura_id'];
			$temp_rodzaj		= $newArray['sprzedaz_rodzaj'];
			$temp_poz_cena_cr	= $newArray['sprzedaz_pozycja_cenanetto'];
			$temp_sprzedaz_unique = $newArray['sprzedaz_unique'];

			tbl_tr_highlight($i);
			$i++;
			
			//$hd_zgl_id = 0;
			if ($temp_poz_pid!=0) {
				list($hd_zgl_id)=mysql_fetch_array(mysql_query("SELECT wp_zgl_id FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_sprzedaz_fakt_szcz_id='$temp_poz_pid') and (wp_sprzet_active=1) LIMIT 1",$conn_hd));	
			} else $hd_zgl_id = 0;
			
			if ($temp_poz_nazwa=='') $temp_poz_nazwa = '-';
			if ($temp_poz_sn=='') $temp_poz_sn = '-';
			
			td("30;c;<a title='$temp_id' href=# class=normalfont>".$i."</a>");
			td_(";;".$temp_poz_nazwa."");
			
				$sprawdz_nr_pozycji_sql = "SELECT sprzedaz_pozycja_id, sprzedaz_unique FROM $dbname.serwis_sprzedaz WHERE ((sprzedaz_pozycja_id=$temp_poz_pid) and (sprzedaz_unique='$temp_sprzedaz_unique'))";
				
				$sprawdz_nr_pozycji_wynik = mysql_query($sprawdz_nr_pozycji_sql, $conn);
				$count_rows = mysql_num_rows($sprawdz_nr_pozycji_wynik);			
			
				if ($count_rows>1) echo "<br><font color=red>Przy tej pozycji wystąpiła niespójność bazy. Proszę o kontakt z administratorem </font>";
			
				if ($temp_poz_sn!='') echo "<br /><font color=grey>".$temp_poz_sn."</font>";
				list($temp_towar_uwagi)=mysql_fetch_array(mysql_query("SELECT pozycja_uwagi FROM $dbname.serwis_faktura_szcz WHERE pozycja_id=$temp_poz_pid LIMIT 1",$conn));
				if ($pokazpoczatekuwag==1) pokaz_uwagi($temp_towar_uwagi,$iloscznakowuwag,"newWindow(480,265,'p_towar_uwagi.php?id=$temp_poz_pid')");
			_td();
			list($temp_nr_zl)=mysql_fetch_array(mysql_query("SELECT umowa_nr_zlecenia FROM $dbname.serwis_umowy WHERE umowa_nr='$temp_umowa' LIMIT 1",$conn));
			td_(";;".$temp_pion." ".$temp_up."<br /><font color=grey>".$temp_rodzaj."</font> - ".$temp_nr_zl."");
				if ($pokazpoczatekuwag==1) pokaz_uwagi($temp_uwagi,$iloscznakowuwag,"newWindow(480,265,'p_sprzedaz_uwagi.php?id=$temp_id')");
			_td();
			
			echo "<td class=center>";
				
				if ($hd_zgl_id>0) {
					list($hd_zgl_hadim)=mysql_fetch_array(mysql_query("SELECT zgl_poczta_nr FROM $dbname_hd.hd_zgloszenie WHERE (zgl_nr='$hd_zgl_id') LIMIT 1",$conn_hd));
					echo "<a href=# class=normalfont title=' Sprzedaż powiązana jest ze zgłoszeniem o numerze ".$hd_zgl_id." w bazie Helpdesk ' onclick=\"self.location.href='hd_p_zgloszenia.php?sr=search-wyniki&search_zgl_nr=".$hd_zgl_id."'; return false; \"><b>$hd_zgl_id</b></a>";
					if ($hd_zgl_hadim>0) {
						//echo "<br /><a class=normalfont style='color:red' href=# title='Powiązanie ze zgłoszeniem numer ".$hd_zgl_hadim." w bazie HDIM' onclick=\"newWindow_r(800,600,'https://sd.cit.net.pp/helpdesk/issue.php?id=$hd_zgl_hadim'); return false;\"><b>$hd_zgl_hadim</b></a>";
						echo "<br /><b>$hd_zgl_hadim</b>";
					}
					
				} else echo "<b>-</b>";
				
			echo "</td>";
			
			td(";;".$temp_osoba."<br /><font color=grey>".$temp_data."</font>");
	/*		td_img("40;c");
				if ($temp_uwagi!='') {
				  echo "<a title=' Pokaż uwagi do sprzedaży '><input class=imgoption type=image src=img/comment.gif onclick=\"newWindow(480,265,'p_sprzedaz_uwagi.php?id=$temp_id')\"></a>";
				}
				echo "<a title=' Edytuj uwagi do sprzedaży '><input class=imgoption type=image src=img/edit_comment.gif onclick=\"newWindow(480,270,'e_sprzedaz_uwagi.php?id=$temp_id')\"></a>";
			_td();
	*/
			td_img(";c");
			
			// sprawdzenie czy sprzedaż jest z okresu zraportowanego
					$rok = substr($temp_data,0,4);
					$miesiac = substr($temp_data,5,2);					
					$sql66 = "SELECT sr_miesiac FROM $dbname.serwis_sprzedaz_raport WHERE ((belongs_to=$es_filia) and (sr_rok=$rok) and (sr_miesiac=$miesiac))";				
					$result = mysql_query($sql66, $conn) or die($k_b);
					$count_rows11 = mysql_num_rows($result);
					
					if ($count_rows11>0) {
							echo "<a href=# onclick=\"newWindow_r(500,100,'u_sprzedaz.php?id=$temp_id');return false; \">"; 
							echo "<input class=imgoption type=image src=img/delete.gif title=' Ukryj sprzedaż '>";
							echo "</a>";
					} else {
						$sql_czy_w_zestawie = "SELECT zestawpozycja_zestaw_id FROM $dbname.serwis_zestaw_szcz WHERE (zestawpozycja_fszcz_id=$temp_poz_pid) LIMIT 1";
						$wynik=mysql_num_rows(mysql_query($sql_czy_w_zestawie,$conn));
				
						if ($temp_status==1) {
							if ($wynik==0) {
							
								$accessLevels = array("9"); 
								if(array_search($es_prawa, $accessLevels)>-1){
									$result = mysql_query("SELECT up_id,up_nazwa,up_pion_id,up_umowa_id,pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (up_active=1) and (belongs_to=$es_filia) and (CONCAT(serwis_piony.pion_nazwa,' ',serwis_komorki.up_nazwa)='$temp_pion $temp_up') LIMIT 1", $conn) or die($k_b);
				
						
									list($temp_upid,$temp_nazwa,$temp_pion_id,$temp_umowa_id,$temp_pionnazwa)=mysql_fetch_array($result);
				
									echo "<a title=' Edycja daty sprzedaży i komórki '><input class=imgoption type=image src=img/edit.gif onclick=\"newWindow_r(800,600,'e_towary_obrot.php?tid=$temp_id&f=$temp_fakt_id&pozid=$temp_poz_pid&new_upid=$temp_upid&sdata=$temp_data&srodzaj=".urlencode($temp_rodzaj)."&hd_zgl_nr=$hd_zgl_id&hd_zgl_nr=$hd_zgl_id')\"></a>";
								}
								
								if ($hd_zgl_id>0) {
									echo "<a title='Anuluj sprzedaż podzespołu powiązanego ze zgłoszeniem nr $hd_zgl_id'><input class=imgoption type=image src='img/money_delete_hd.gif' onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_towary_sprzedaz.php?id=$temp_poz_pid'); return false;\"></a>";
								} else {
									echo "<a title='Anuluj sprzedaż: $temp_poz_nazwa '><input class=imgoption type=image src='img/money_delete.gif' onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_towary_sprzedaz.php?id=$temp_poz_pid'); return false;\"></a>";
								}
								
								echo "<a title=' Pokaż fakturę dla tego towaru / usługi '><input class=imgoption type=image src=img/search.gif  onclick=\"newWindow_r(800,600,'p_faktura_pozycje.php?id=$temp_fakt_id&pozid=$temp_poz_pid');\"></a>";

								
								if ($hd_zgl_id>0) {
									$LinkHDZglNr=$hd_zgl_id; include('linktohelpdesk.php');
								}	
								
							} else {
							
								$sql_zestaw_id = "SELECT zestawpozycja_zestaw_id FROM $dbname.serwis_zestaw_szcz WHERE (zestawpozycja_fszcz_id=$temp_poz_pid) LIMIT 1";
								list($nr_zestawu)=mysql_fetch_array(mysql_query($sql_zestaw_id,$conn));
										
								$sql_zestaw_nazwa = "SELECT zestaw_opis FROM $dbname.serwis_zestawy WHERE (zestaw_id=$nr_zestawu) LIMIT 1";
								list($nazwa_zestawu)=mysql_fetch_array(mysql_query($sql_zestaw_nazwa,$conn));
								
								$result = mysql_query("SELECT up_id,up_nazwa,up_pion_id,up_umowa_id,pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (up_active=1) and (belongs_to=$es_filia) and (CONCAT(serwis_piony.pion_nazwa,' ',serwis_komorki.up_nazwa)='$temp_pion $temp_up') LIMIT 1", $conn) or die($k_b);

								list($temp_upid,$temp_nazwa,$temp_pion_id,$temp_umowa_id,$temp_pionnazwa)=mysql_fetch_array($result);
				
								list($hd_zgl_id)=mysql_fetch_array(mysql_query("SELECT wp_zgl_id FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE wp_sprzedaz_fakt_szcz_id='$temp_poz_pid' LIMIT 1",$conn_hd));			
				
								echo "<a title=' Edycja daty sprzedaży i komórki (dla zestawu) '><input class=imgoption type=image src=img/edit_zestaw.gif onclick=\"newWindow_r(800,600,'e_zestaw_obrot.php?zid=$nr_zestawu&tid=$temp_id&f=$temp_fakt_id&pozid=$temp_poz_pid&new_upid=$temp_upid&sdata=$temp_data&srodzaj=".urlencode($temp_rodzaj)."&hd_zgl_nr=$hd_zgl_id')\"></a>";
									
								if ($allow_sell==1) {
									
									if ($hd_zgl_id>0) {
										echo "<a title='Anuluj sprzedaż zestawu: $nazwa_zestawu powiązanego z wymianą podzespołów w zgłoszeniu nr $hd_zgl_id'><input class=imgoption type=image src='img/money_delete_hd.gif' onclick=\"newWindow_r(800,600,'u_zestaw_sprzedaz.php?id=$nr_zestawu'); return false;\"></a>";
									} else {
										echo "<a title='Anuluj sprzedaż zestawu: $nazwa_zestawu '><input class=imgoption type=image src='img/money_delete.gif' onclick=\"newWindow_r(800,600,'u_zestaw_sprzedaz.php?id=$nr_zestawu'); return false;\"></a>";
									}
									
								}
								
								echo "<a title=' Element zestawu: $nazwa_zestawu '><input class=imgoption type=image src=img/basket.gif onclick=\"newWindow(800,600,'p_zestaw_pozycje.php?id=$nr_zestawu&sold=1&showall=1&paget=1')\"></a>";
								
								echo "<a title=' Pokaż fakturę dla tego towaru / usługi '><input class=imgoption type=image src=img/search.gif  onclick=\"newWindow_r(800,600,'p_faktura_pozycje.php?id=$temp_fakt_id&pozid=$temp_poz_pid');\"></a>";
								
								
								if ($hd_zgl_id>0) {
									$LinkHDZglNr=$hd_zgl_id; include('linktohelpdesk.php');
								}	
								
								echo "<br /><a title=' Nazwa zestawu: $nazwa_zestawu '>[<b>$nazwa_zestawu</b>]</a>";
							}
						}
					}
			_td();
		_tr();
		}
		endtable();	
}
	
	$bez_hd = false;
	if (($_REQUEST[okres_od]<'2011-09-20') || ($_REQUEST[okres_do]<'2011-09-20')) $bez_hd = true;
	
	if (($okres_od!="") && ($okres_do!="")) {
		if ($tuser!="all") {
			if (!$bez_hd) {
				$sql_a = "SELECT * FROM $dbname.serwis_sprzedaz,$dbname_hd.hd_zgl_wymiany_podzespolow WHERE (serwis_sprzedaz.belongs_to=$es_filia) and (serwis_sprzedaz.sprzedaz_pozycja_id<>0) and (serwis_sprzedaz.sprzedaz_osoba='$tuser') and (serwis_sprzedaz.sprzedaz_data BETWEEN '$okres_od' AND '$okres_do') ";
				if ($_REQUEST[dlaup]!='') $sql_a .= " AND (CONCAT(serwis_sprzedaz.sprzedaz_pion_nazwa,' ',serwis_sprzedaz.sprzedaz_up_nazwa)='$_REQUEST[dlaup]') ";
				if ($_REQUEST[dlazgl]!='') $sql_a .= " AND (hd_zgl_wymiany_podzespolow.wp_zgl_id='$_REQUEST[dlazgl]') ";
				$sql_a .= " and (serwis_sprzedaz.sprzedaz_pozycja_id=hd_zgl_wymiany_podzespolow.wp_sprzedaz_fakt_szcz_id) and (hd_zgl_wymiany_podzespolow.wp_sprzet_active=1) ";
				$sql_a .= " ORDER BY serwis_sprzedaz.sprzedaz_id ASC, sprzedaz_unique ASC"; 
			} else {
				$sql_a = "SELECT * FROM $dbname.serwis_sprzedaz WHERE (serwis_sprzedaz.belongs_to=$es_filia) and (serwis_sprzedaz.sprzedaz_pozycja_id<>0) and (serwis_sprzedaz.sprzedaz_osoba='$tuser') and (serwis_sprzedaz.sprzedaz_data BETWEEN '$okres_od' AND '$okres_do') ";
				if ($_REQUEST[dlaup]!='') $sql_a .= " AND (CONCAT(serwis_sprzedaz.sprzedaz_pion_nazwa,' ',serwis_sprzedaz.sprzedaz_up_nazwa)='$_REQUEST[dlaup]') ";
				//if ($_REQUEST[dlazgl]!='') $sql_a .= " AND (hd_zgl_wymiany_podzespolow.wp_zgl_id='$_REQUEST[dlazgl]') ";
				//$sql_a .= " and (serwis_sprzedaz.sprzedaz_pozycja_id=hd_zgl_wymiany_podzespolow.wp_sprzedaz_fakt_szcz_id) ";
				$sql_a .= " ORDER BY serwis_sprzedaz.sprzedaz_id ASC, sprzedaz_unique ASC"; 			
			}
		} else { 
			if (!$bez_hd) {
				$sql_a = "SELECT * FROM $dbname.serwis_sprzedaz,$dbname_hd.hd_zgl_wymiany_podzespolow WHERE (serwis_sprzedaz.belongs_to=$es_filia) and (serwis_sprzedaz.sprzedaz_pozycja_id<>0) and (serwis_sprzedaz.sprzedaz_data BETWEEN '$okres_od' AND '$okres_do') ";
				if ($_REQUEST[dlaup]!='') $sql_a .= " AND (CONCAT(serwis_sprzedaz.sprzedaz_pion_nazwa,' ',serwis_sprzedaz.sprzedaz_up_nazwa)='$_REQUEST[dlaup]') ";
				if ($_REQUEST[dlazgl]!='') $sql_a .= " AND (hd_zgl_wymiany_podzespolow.wp_zgl_id='$_REQUEST[dlazgl]') ";
				$sql_a .= " and (serwis_sprzedaz.sprzedaz_pozycja_id=hd_zgl_wymiany_podzespolow.wp_sprzedaz_fakt_szcz_id) and (hd_zgl_wymiany_podzespolow.wp_sprzet_active=1) ";
				$sql_a .= " ORDER BY serwis_sprzedaz.sprzedaz_id ASC, sprzedaz_unique ASC"; 
			} else {
				$sql_a = "SELECT * FROM $dbname.serwis_sprzedaz WHERE (serwis_sprzedaz.belongs_to=$es_filia) and (serwis_sprzedaz.sprzedaz_pozycja_id<>0) and (serwis_sprzedaz.sprzedaz_data BETWEEN '$okres_od' AND '$okres_do') ";
				if ($_REQUEST[dlaup]!='') $sql_a .= " AND (CONCAT(serwis_sprzedaz.sprzedaz_pion_nazwa,' ',serwis_sprzedaz.sprzedaz_up_nazwa)='$_REQUEST[dlaup]') ";
				//if ($_REQUEST[dlazgl]!='') $sql_a .= " AND (hd_zgl_wymiany_podzespolow.wp_zgl_id='$_REQUEST[dlazgl]') ";
				//$sql_a .= " and (serwis_sprzedaz.sprzedaz_pozycja_id=hd_zgl_wymiany_podzespolow.wp_sprzedaz_fakt_szcz_id) ";
				$sql_a .= " ORDER BY serwis_sprzedaz.sprzedaz_id ASC, sprzedaz_unique ASC"; 
			}
		}
	} else { 
		if (!$bez_hd) {
			$sql_a = "SELECT * FROM $dbname.serwis_sprzedaz,$dbname_hd.hd_zgl_wymiany_podzespolow WHERE (serwis_sprzedaz.belongs_to=$es_filia) and (serwis_sprzedaz.sprzedaz_pozycja_id<>0)and (serwis_sprzedaz.sprzedaz_pozycja_id<>0) ";
			if ($_REQUEST[dlaup]!='') $sql_a .= " AND (CONCAT(serwis_sprzedaz.sprzedaz_pion_nazwa,' ',serwis_sprzedaz.sprzedaz_up_nazwa)='$_REQUEST[dlaup]') ";
			if ($_REQUEST[dlazgl]!='') $sql_a .= " AND (hd_zgl_wymiany_podzespolow.wp_zgl_id='$_REQUEST[dlazgl]') ";	
			$sql_a .= " and (serwis_sprzedaz.sprzedaz_pozycja_id=hd_zgl_wymiany_podzespolow.wp_sprzedaz_fakt_szcz_id) and (hd_zgl_wymiany_podzespolow.wp_sprzet_active=1) ";
			$sql_a .= " ORDER BY serwis_sprzedaz.sprzedaz_id ASC, serwis_sprzedaz.sprzedaz_unique ASC"; 
		} else {
			$sql_a = "SELECT * FROM $dbname.serwis_sprzedaz WHERE (serwis_sprzedaz.belongs_to=$es_filia) and (serwis_sprzedaz.sprzedaz_pozycja_id<>0)and (serwis_sprzedaz.sprzedaz_pozycja_id<>0) ";
			if ($_REQUEST[dlaup]!='') $sql_a .= " AND (CONCAT(serwis_sprzedaz.sprzedaz_pion_nazwa,' ',serwis_sprzedaz.sprzedaz_up_nazwa)='$_REQUEST[dlaup]') ";
			//if ($_REQUEST[dlazgl]!='') $sql_a .= " AND (hd_zgl_wymiany_podzespolow.wp_zgl_id='$_REQUEST[dlazgl]') ";	
			//$sql_a .= " and (serwis_sprzedaz.sprzedaz_pozycja_id=hd_zgl_wymiany_podzespolow.wp_sprzedaz_fakt_szcz_id) ";
			$sql_a .= " ORDER BY serwis_sprzedaz.sprzedaz_id ASC, serwis_sprzedaz.sprzedaz_unique ASC"; 		
		}
	}
	
	//echo $sql_a;
	
	$result_a = mysql_query($sql_a, $conn) or die($k_b);
	$count_rows = mysql_num_rows($result_a);

	if ($count_rows>0) {
		pageheader("Raport ze sprzedaży towarów / usług ",1,1);

		?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
		
		if ($tuser!="all") infoheader("dla pracownika:<br /><b>".$tuser."</b><br />w okresie<br /><b>".$okres_od." - ".$okres_do."</b>");
		if ($tuser=="all") infoheader("w okresie<br /><b>".$okres_od." - ".$okres_do."</b>");

		if ($bez_hd) {
			errorheader('Podany okres zawiera w sobie sprzedaż nie powiązaną ze zgłoszeniami Helpdesk. Dla sprzedaży w tym okresie numery zgłoszeń nie będą podane.<br />Fitrowanie po numerze zgłoszenia zostało wyłączone autoamtycznie');
		}
		
		echo "<form name=swo style='display:inline'>";
		startbuttonsarea("center");
		
		$sql5="SELECT CONCAT(sprzedaz_pion_nazwa,' ',sprzedaz_up_nazwa) as pnk FROM $dbname.serwis_sprzedaz,$dbname_hd.hd_zgl_wymiany_podzespolow WHERE (serwis_sprzedaz.belongs_to=$es_filia) and (serwis_sprzedaz.sprzedaz_pozycja_id<>0) and (serwis_sprzedaz.sprzedaz_pozycja_id=hd_zgl_wymiany_podzespolow.wp_sprzedaz_fakt_szcz_id)";
		if (($_REQUEST[tuser]!='') && ($_REQUEST[tuser]!='all')) $sql5 .= " and (serwis_sprzedaz.sprzedaz_osoba='$_REQUEST[tuser]') "; 
		if ($_REQUEST[dlazgl]!='') $sql5 .= " AND (hd_zgl_wymiany_podzespolow.wp_zgl_id='$_REQUEST[dlazgl]') ";

		$sql5 .= " and (serwis_sprzedaz.sprzedaz_data BETWEEN '$okres_od' AND '$okres_do') ";
		$sql5 .= " GROUP BY pnk ORDER BY pnk ASC";

		$result5=mysql_query($sql5,$conn) or die($k_b);
		$count_rows5 = mysql_num_rows($result5);

		if ($count_rows5>0) {
			echo "Filtruj: ";	
			echo "<select name=dlaup ";
			if ($_REQUEST[dlaup]!='') echo " style='background-color:yellow' ";
			echo " onkeypress='return handleEnter(this, event);' onChange=\"document.location.href=document.swo.dlaup.options[document.swo.dlaup.selectedIndex].value;\" >\n"; 
			echo "<option ";
			if ($_REQUEST[dlaup]=='') echo "SELECTED";
			echo " value='$PHP_SELF?okres_od=$_REQUEST[okres_od]&okres_do=$_REQUEST[okres_do]&tuser=$_REQUEST[tuser]&submit=$_REQUEST[submit]&dlaup=&dlazgl=$_REQUEST[dlazgl]'>wg komórki</option>\n";
			while ($newArray44 = mysql_fetch_array($result5)) {
				$temp_iin = $newArray44['pnk'];			
				echo "<option";
				if ($temp_iin==$_REQUEST[dlaup]) echo " SELECTED ";
				echo " value='$PHP_SELF?okres_od=$_REQUEST[okres_od]&okres_do=$_REQUEST[okres_do]&tuser=$_REQUEST[tuser]&submit=$_REQUEST[submit]&dlaup=".urlencode($temp_iin)."&dlazgl=$_REQUEST[dlazgl]'>$temp_iin</option>\n"; 
			}
			echo "</select>";
		}
		
		// SELECT wp_zgl_id FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_sprzedaz_fakt_szcz_id='$temp_poz_pid') and (wp_sprzet_active=1) LIMIT 1
		
	if (($_REQUEST[okres_od]>'2011-09-20') && ($_REQUEST[okres_do]>'2011-09-21')) {
		$sql5="SELECT DISTINCT(wp_zgl_id) FROM $dbname.serwis_sprzedaz,$dbname_hd.hd_zgl_wymiany_podzespolow WHERE (serwis_sprzedaz.belongs_to=$es_filia) and (serwis_sprzedaz.sprzedaz_pozycja_id<>0) ";
		if (($_REQUEST[hdzglnr]!='')) $sql5 .= " and (serwis_sprzedaz.sprzedaz_osoba='$_REQUEST[hdzglnr]') "; 
		if ($_REQUEST[dlaup]!='') $sql5 .= " AND (CONCAT(serwis_sprzedaz.sprzedaz_pion_nazwa,' ',serwis_sprzedaz.sprzedaz_up_nazwa)='$_REQUEST[dlaup]') ";
		//	if ($_REQUEST[dlazgl]!='') $sql5 .= " AND (hd_zgl_wymiany_podzespolow.wp_zgl_id='$_REQUEST[dlazgl]') ";
		if (($_REQUEST[tuser]!='') && ($_REQUEST[tuser]!='all')) $sql5 .= " and (serwis_sprzedaz.sprzedaz_osoba='$_REQUEST[tuser]') "; 
		
		$sql5 .= " and (serwis_sprzedaz.sprzedaz_data BETWEEN '$okres_od' AND '$okres_do') and (serwis_sprzedaz.sprzedaz_pozycja_id=hd_zgl_wymiany_podzespolow.wp_sprzedaz_fakt_szcz_id) ";
		$sql5 .= " ORDER BY wp_zgl_id ASC";
		
		//echo $sql5;
		$result5=mysql_query($sql5,$conn) or die($k_b);
		$count_rows5 = mysql_num_rows($result5);

		if ($count_rows5>0) {
			echo "&nbsp;";	
			echo "<select name=dlazgl ";
			if ($_REQUEST[dlazgl]!='') echo " style='background-color:yellow' ";
			echo " onkeypress='return handleEnter(this, event);' onChange=\"document.location.href=document.swo.dlazgl.options[document.swo.dlazgl.selectedIndex].value;\" >\n"; 
			echo "<option ";
			if ($_REQUEST[dlazgl]=='') echo "SELECTED";
			echo " value='$PHP_SELF?okres_od=$_REQUEST[okres_od]&okres_do=$_REQUEST[okres_do]&tuser=$_REQUEST[tuser]&submit=$_REQUEST[submit]&dlaup=".urlencode($_REQUEST[dlaup])."&dlazgl='>wg zgłoszenia</option>\n";
			while ($newArray44 = mysql_fetch_array($result5)) {
				$temp_iin = $newArray44['wp_zgl_id'];		
				if ($temp_iin!='0') {
					echo "<option";
					if ($temp_iin==$_REQUEST[dlazgl]) echo " SELECTED ";
					echo " value='$PHP_SELF?okres_od=$_REQUEST[okres_od]&okres_do=$_REQUEST[okres_do]&tuser=$_REQUEST[tuser]&submit=$_REQUEST[submit]&dlaup=".urlencode($_REQUEST[dlaup])."&dlazgl=".urlencode($temp_iin)."'>$temp_iin</option>\n"; 
				}
			}
			echo "</select>";
		}
	}
			$result6 = mysql_query("SELECT user_first_name, user_last_name FROM $dbname.serwis_uzytkownicy WHERE (belongs_to=$es_filia) and (user_locked=0) ORDER BY user_last_name", $conn) or die($k_b);
			echo "&nbsp;<select ";
			if ($_REQUEST[tuser]!='') echo " style='background-color:yellow' ";			
			echo " name=tuser onkeypress='return handleEnter(this, event);' onChange=\"document.location.href=document.swo.tuser.options[document.swo.tuser.selectedIndex].value;\">\n"; 					 				
			echo "<option value='$PHP_SELF?okres_od=$_REQUEST[okres_od]&okres_do=$_REQUEST[okres_do]&tuser=all&submit=$_REQUEST[submit]&dlaup=".urlencode($_REQUEST[dlaup])."&dlazgl=$_REQUEST[dlazgl]' "; 
			if ($_REQUEST[tuser]=='all') echo " SELECTED ";
			
			echo " >Wszyscy z bieżącej filii";
			while (list($temp_imie,$temp_nazwisko) = mysql_fetch_array($result6)) {
				$iin = $temp_imie." ".$temp_nazwisko;
				echo "<option value='$PHP_SELF?okres_od=$_REQUEST[okres_od]&okres_do=$_REQUEST[okres_do]&tuser=".urlencode($iin)."&submit=$_REQUEST[submit]&dlaup=".urlencode($_REQUEST[dlaup])."&dlazgl=$_REQUEST[dlazgl]' ";
				if ($_REQUEST[tuser]==$iin) echo " SELECTED ";
				echo ">$temp_imie $temp_nazwisko</option>\n"; 
			}
			echo "</select>\n";
		
			echo "<input type=button class=buttons value='Domyślny widok dla okresu i osoby' style='padding:2px; margin:2px;' onClick=\"document.location.href='$PHP_SELF?okres_od=$_REQUEST[okres_od]&okres_do=$_REQUEST[okres_do]&tuser=".$_REQUEST[tuser]."&submit=$_REQUEST[submit]'; \" />";
		endbuttonsarea();
		echo "</form>";
		
		starttable();
		th("30;c;LP|;;Nazwa towaru<br />Numer seryjny|;;Sprzedano dla<br />Rodzaj sprzedaży - Numer zlecenia|;c;Nr zgłoszenia<br /><font color=red>Nr Landek</font>|;;Osoba sprzedająca<br />Data sprzedaży|;c;Opcje",$es_prawa);
		
		$result_a = mysql_query($sql_a, $conn) or die($k_b);
		$i=0;
		
		while ($newArray = mysql_fetch_array($result_a)) {		
			$temp_id  			= $newArray['sprzedaz_id'];
			$temp_poz_pid		= $newArray['sprzedaz_pozycja_id'];			
			$temp_poz_nazwa		= $newArray['sprzedaz_pozycja_nazwa'];			
			$temp_poz_sn		= $newArray['sprzedaz_pozycja_sn'];
			$temp_osoba			= $newArray['sprzedaz_osoba'];
			$temp_data			= $newArray['sprzedaz_data'];
			$temp_pion			= $newArray['sprzedaz_pion_nazwa'];
			$temp_up			= $newArray['sprzedaz_up_nazwa'];			
			$temp_umowa			= $newArray['sprzedaz_umowa_nazwa'];
			$temp_uwagi			= $newArray['sprzedaz_uwagi'];
			$temp_status		= $newArray['sprzedaz_status'];
			$temp_fakt_id		= $newArray['sprzedaz_faktura_id'];
			$temp_rodzaj		= $newArray['sprzedaz_rodzaj'];
			$temp_poz_cena_cr	= $newArray['sprzedaz_pozycja_cenanetto'];
			$temp_sprzedaz_unique = $newArray['sprzedaz_unique'];

			tbl_tr_highlight($i);
			$i++;
			
			list($hd_zgl_id)=mysql_fetch_array(mysql_query("SELECT wp_zgl_id FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_sprzedaz_fakt_szcz_id='$temp_poz_pid') and (wp_sprzet_active=1) LIMIT 1",$conn_hd));	
			
			td("30;c;<a title='$temp_id' href=# class=normalfont>".$i."</a>");
			td_(";;".$temp_poz_nazwa."");
			
				$sprawdz_nr_pozycji_sql = "SELECT sprzedaz_pozycja_id, sprzedaz_unique FROM $dbname.serwis_sprzedaz WHERE ((sprzedaz_pozycja_id=$temp_poz_pid) and (sprzedaz_unique='$temp_sprzedaz_unique'))";
				
				$sprawdz_nr_pozycji_wynik = mysql_query($sprawdz_nr_pozycji_sql, $conn);
				$count_rows = mysql_num_rows($sprawdz_nr_pozycji_wynik);			
			
				if ($count_rows>1) echo "<br><font color=red>Przy tej pozycji wystąpiła niespójność bazy. Proszę o kontakt z administratorem </font>";
			
				if ($temp_poz_sn!='') echo "<br /><font color=grey>".$temp_poz_sn."</font>";
				list($temp_towar_uwagi)=mysql_fetch_array(mysql_query("SELECT pozycja_uwagi FROM $dbname.serwis_faktura_szcz WHERE pozycja_id=$temp_poz_pid LIMIT 1",$conn));
				if ($pokazpoczatekuwag==1) pokaz_uwagi($temp_towar_uwagi,$iloscznakowuwag,"newWindow(480,265,'p_towar_uwagi.php?id=$temp_poz_pid')");
			_td();
			list($temp_nr_zl)=mysql_fetch_array(mysql_query("SELECT umowa_nr_zlecenia FROM $dbname.serwis_umowy WHERE umowa_nr='$temp_umowa' LIMIT 1",$conn));
			td_(";;".$temp_pion." ".$temp_up."<br /><font color=grey>".$temp_rodzaj."</font> - ".$temp_nr_zl."");
				if ($pokazpoczatekuwag==1) pokaz_uwagi($temp_uwagi,$iloscznakowuwag,"newWindow(480,265,'p_sprzedaz_uwagi.php?id=$temp_id')");
			_td();
			
			echo "<td class=center>";
				
				if ($hd_zgl_id>0) {
					list($hd_zgl_hadim)=mysql_fetch_array(mysql_query("SELECT zgl_poczta_nr FROM $dbname_hd.hd_zgloszenie WHERE (zgl_nr='$hd_zgl_id') LIMIT 1",$conn_hd));
					echo "<a href=# class=normalfont title=' Sprzedaż powiązana jest ze zgłoszeniem o numerze ".$hd_zgl_id." w bazie Helpdesk ' onclick=\"self.location.href='hd_p_zgloszenia.php?sr=search-wyniki&search_zgl_nr=".$hd_zgl_id."'; return false; \"><b>$hd_zgl_id</b></a>";
					if ($hd_zgl_hadim>0) {
						//echo "<br /><a class=normalfont style='color:red' href=# title='Powiązanie ze zgłoszeniem numer ".$hd_zgl_hadim." w bazie HDIM' onclick=\"newWindow_r(800,600,'https://sd.cit.net.pp/helpdesk/issue.php?id=$hd_zgl_hadim'); return false;\"><b>$hd_zgl_hadim</b></a>";
						echo "<br /><b>$hd_zgl_hadim</b>";
					}
					
				} else echo "<b>-</b>";
				
			echo "</td>";
			
			td(";;".$temp_osoba."<br /><font color=grey>".$temp_data."</font>");
	/*		td_img("40;c");
				if ($temp_uwagi!='') {
				  echo "<a title=' Pokaż uwagi do sprzedaży '><input class=imgoption type=image src=img/comment.gif onclick=\"newWindow(480,265,'p_sprzedaz_uwagi.php?id=$temp_id')\"></a>";
				}
				echo "<a title=' Edytuj uwagi do sprzedaży '><input class=imgoption type=image src=img/edit_comment.gif onclick=\"newWindow(480,270,'e_sprzedaz_uwagi.php?id=$temp_id')\"></a>";
			_td();
	*/
			td_img(";c");
			
				$sql_czy_w_zestawie = "SELECT zestawpozycja_zestaw_id FROM $dbname.serwis_zestaw_szcz WHERE (zestawpozycja_fszcz_id=$temp_poz_pid) LIMIT 1";
				//echo $sql_czy_w_zestawie;
				$wynik=mysql_num_rows(mysql_query($sql_czy_w_zestawie,$conn));
				
				if ($temp_status==1) {
					if ($wynik==0) {
					
						$accessLevels = array("9"); 
						if(array_search($es_prawa, $accessLevels)>-1){
							$result = mysql_query("SELECT up_id,up_nazwa,up_pion_id,up_umowa_id,pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (up_active=1) and (belongs_to=$es_filia) and (CONCAT(serwis_piony.pion_nazwa,' ',serwis_komorki.up_nazwa)='$temp_pion $temp_up') LIMIT 1", $conn) or die($k_b);
		
				
							list($temp_upid,$temp_nazwa,$temp_pion_id,$temp_umowa_id,$temp_pionnazwa)=mysql_fetch_array($result);
		
							echo "<a title=' Edycja daty sprzedaży i komórki '><input class=imgoption type=image src=img/edit.gif onclick=\"newWindow_r(800,600,'e_towary_obrot.php?tid=$temp_id&f=$temp_fakt_id&pozid=$temp_poz_pid&new_upid=$temp_upid&sdata=$temp_data&srodzaj=".urlencode($temp_rodzaj)."&hd_zgl_nr=$hd_zgl_id&hd_zgl_nr=$hd_zgl_id')\"></a>";
						}
						
						if ($hd_zgl_id>0) {
							echo "<a title='Anuluj sprzedaż podzespołu powiązanego ze zgłoszeniem nr $hd_zgl_id'><input class=imgoption type=image src='img/money_delete_hd.gif' onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_towary_sprzedaz.php?id=$temp_poz_pid'); return false;\"></a>";
						} else {
							echo "<a title='Anuluj sprzedaż: $temp_poz_nazwa '><input class=imgoption type=image src='img/money_delete.gif' onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_towary_sprzedaz.php?id=$temp_poz_pid'); return false;\"></a>";
						}
						
						echo "<a title=' Pokaż fakturę dla tego towaru / usługi '><input class=imgoption type=image src=img/search.gif  onclick=\"newWindow_r(800,600,'p_faktura_pozycje.php?id=$temp_fakt_id&pozid=$temp_poz_pid');\"></a>";

						
						if ($hd_zgl_id>0) {
							$LinkHDZglNr=$hd_zgl_id; include('linktohelpdesk.php');
						}	
						
					} else {
					
						$sql_zestaw_id = "SELECT zestawpozycja_zestaw_id FROM $dbname.serwis_zestaw_szcz WHERE (zestawpozycja_fszcz_id=$temp_poz_pid) LIMIT 1";
						list($nr_zestawu)=mysql_fetch_array(mysql_query($sql_zestaw_id,$conn));
								
						$sql_zestaw_nazwa = "SELECT zestaw_opis FROM $dbname.serwis_zestawy WHERE (zestaw_id=$nr_zestawu) LIMIT 1";
						list($nazwa_zestawu)=mysql_fetch_array(mysql_query($sql_zestaw_nazwa,$conn));
						
						$result = mysql_query("SELECT up_id,up_nazwa,up_pion_id,up_umowa_id,pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (up_active=1) and (belongs_to=$es_filia) and (CONCAT(serwis_piony.pion_nazwa,' ',serwis_komorki.up_nazwa)='$temp_pion $temp_up') LIMIT 1", $conn) or die($k_b);

						list($temp_upid,$temp_nazwa,$temp_pion_id,$temp_umowa_id,$temp_pionnazwa)=mysql_fetch_array($result);
		
						list($hd_zgl_id)=mysql_fetch_array(mysql_query("SELECT wp_zgl_id FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE wp_sprzedaz_fakt_szcz_id='$temp_poz_pid' LIMIT 1",$conn_hd));			
		
						echo "<a title=' Edycja daty sprzedaży i komórki (dla zestawu) '><input class=imgoption type=image src=img/edit_zestaw.gif onclick=\"newWindow_r(800,600,'e_zestaw_obrot.php?zid=$nr_zestawu&tid=$temp_id&f=$temp_fakt_id&pozid=$temp_poz_pid&new_upid=$temp_upid&sdata=$temp_data&srodzaj=".urlencode($temp_rodzaj)."&hd_zgl_nr=$hd_zgl_id')\"></a>";
							
						if ($allow_sell==1) {
							
							if ($hd_zgl_id>0) {
								echo "<a title='Anuluj sprzedaż zestawu: $nazwa_zestawu powiązanego z wymianą podzespołów w zgłoszeniu nr $hd_zgl_id'><input class=imgoption type=image src='img/money_delete_hd.gif' onclick=\"newWindow_r(800,600,'u_zestaw_sprzedaz.php?id=$nr_zestawu'); return false;\"></a>";
							} else {
								echo "<a title='Anuluj sprzedaż zestawu: $nazwa_zestawu '><input class=imgoption type=image src='img/money_delete.gif' onclick=\"newWindow_r(800,600,'u_zestaw_sprzedaz.php?id=$nr_zestawu'); return false;\"></a>";
							}
							
						}
						
						echo "<a title=' Element zestawu: $nazwa_zestawu '><input class=imgoption type=image src=img/basket.gif onclick=\"newWindow(800,600,'p_zestaw_pozycje.php?id=$nr_zestawu&sold=1&showall=1&paget=1')\"></a>";
						
						echo "<a title=' Pokaż fakturę dla tego towaru / usługi '><input class=imgoption type=image src=img/search.gif  onclick=\"newWindow_r(800,600,'p_faktura_pozycje.php?id=$temp_fakt_id&pozid=$temp_poz_pid');\"></a>";
						
						
						if ($hd_zgl_id>0) {
							$LinkHDZglNr=$hd_zgl_id; include('linktohelpdesk.php');
						}	
						
						echo "<br /><a title=' Nazwa zestawu: $nazwa_zestawu '>[<b>$nazwa_zestawu</b>]</a>";
					}
				}
		
			_td();
		_tr();
		}
		endtable();
	} else {
		errorheader("Nie znaleziono pozycji spełniających podane przez Ciebie kryteria");
		if ($count_rows_bledne==0) {
			?><meta http-equiv="REFRESH" content="<?php echo $http_refresh; ?>;url=<?php echo "$linkdostrony";?>main.php"><?php	
		}
	}
startbuttonsarea("right");

echo "<span style='float:left'>";
addlinkbutton("'Zmień kryteria'","main.php?action=pswo&okres_od=$_REQUEST[okres_od]&okres_do=$_REQUEST[okres_do]&tuser=".urlencode($_REQUEST[tuser])."");
echo "</span>";

oddziel();
addlinkbutton("'Towary na stanie'","p_towary_dostepne.php?view=normal&wybor=".urlencode($wybor)."");
addlinkbutton("'Utworzone zestawy'","z_towary_zestawy.php");

addlinkbutton("'Faktury niezatwierdzone'","z_faktury.php?showall=0");
addlinkbutton("'Faktury zatwierdzone'","z_faktury_zatwierdzone.php?showall=0");

addlinkbutton("'Przeglądaj zgłoszenia'","hd_p_zgloszenia.php?showall=0&p5=BZ&sort=AD&page=1");
echo "<br />";
addbuttons("start");
endbuttonsarea();

include('body_stop.php');
?>

<script>HideWaitingMessage();</script>

<?php 

} else {

$sql_a = "SELECT sprzedaz_id FROM $dbname.serwis_sprzedaz WHERE belongs_to=$es_filia and (sprzedaz_umowa_nazwa='') ORDER BY sprzedaz_id ASC, sprzedaz_unique ASC"; 
$result_a = mysql_query($sql_a, $conn) or die($k_b);
$count_rows_bledne = mysql_num_rows($result_a);
	
if ($count_rows_bledne > 0 ) { 
	echo "<hr />";
	errorheader("Lista sprzedanych towarów / usług błędnie zarejestrowanych w systemie");
	$sql_a = "SELECT * FROM $dbname.serwis_sprzedaz WHERE belongs_to=$es_filia and (sprzedaz_umowa_nazwa='') ORDER BY sprzedaz_id ASC, sprzedaz_unique ASC"; 
	$result_a = mysql_query($sql_a, $conn) or die($k_b);

		starttable();
		th("30;c;LP|;;Nazwa towaru<br />Numer seryjny|;;Sprzedano dla<br />Rodzaj sprzedaży - Numer zlecenia|40;c;Nr<br />zgłoszenia|;;Osoba sprzedająca<br />Data sprzedaży|;c;Opcje",$es_prawa);
		
		$result_a = mysql_query($sql_a, $conn) or die($k_b);
		$i=0;
		
		while ($newArray = mysql_fetch_array($result_a)) {		
			$temp_id  			= $newArray['sprzedaz_id'];
			$temp_poz_pid		= $newArray['sprzedaz_pozycja_id'];			
			$temp_poz_nazwa		= $newArray['sprzedaz_pozycja_nazwa'];			
			$temp_poz_sn		= $newArray['sprzedaz_pozycja_sn'];
			$temp_osoba			= $newArray['sprzedaz_osoba'];
			$temp_data			= $newArray['sprzedaz_data'];
			$temp_pion			= $newArray['sprzedaz_pion_nazwa'];
			$temp_up			= $newArray['sprzedaz_up_nazwa'];			
			$temp_umowa			= $newArray['sprzedaz_umowa_nazwa'];
			$temp_uwagi			= $newArray['sprzedaz_uwagi'];
			$temp_status		= $newArray['sprzedaz_status'];
			$temp_fakt_id		= $newArray['sprzedaz_faktura_id'];
			$temp_rodzaj		= $newArray['sprzedaz_rodzaj'];
			$temp_poz_cena_cr	= $newArray['sprzedaz_pozycja_cenanetto'];
			$temp_sprzedaz_unique = $newArray['sprzedaz_unique'];

			tbl_tr_highlight($i);
			$i++;
			
			//$hd_zgl_id = 0;
			
			if ($temp_poz_pid!=0) {
				list($hd_zgl_id)=mysql_fetch_array(mysql_query("SELECT wp_zgl_id FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_sprzedaz_fakt_szcz_id='$temp_poz_pid') and (wp_sprzet_active=1) LIMIT 1",$conn_hd));	
			} else $hd_zgl_id = 0;
			
			if ($temp_poz_nazwa=='') $temp_poz_nazwa = '-';
			if ($temp_poz_sn=='') $temp_poz_sn = '-';
			
			td("30;c;<a title='$temp_id' href=# class=normalfont>".$i."</a>");
			td_(";;".$temp_poz_nazwa."");
			
				$sprawdz_nr_pozycji_sql = "SELECT sprzedaz_pozycja_id, sprzedaz_unique FROM $dbname.serwis_sprzedaz WHERE ((sprzedaz_pozycja_id=$temp_poz_pid) and (sprzedaz_unique='$temp_sprzedaz_unique'))";
				
				$sprawdz_nr_pozycji_wynik = mysql_query($sprawdz_nr_pozycji_sql, $conn);
				$count_rows = mysql_num_rows($sprawdz_nr_pozycji_wynik);			
			
				if ($count_rows>1) echo "<br><font color=red>Przy tej pozycji wystąpiła niespójność bazy. Proszę o kontakt z administratorem </font>";
			
				if ($temp_poz_sn!='') echo "<br /><font color=grey>".$temp_poz_sn."</font>";
				list($temp_towar_uwagi)=mysql_fetch_array(mysql_query("SELECT pozycja_uwagi FROM $dbname.serwis_faktura_szcz WHERE pozycja_id=$temp_poz_pid LIMIT 1",$conn));
				if ($pokazpoczatekuwag==1) pokaz_uwagi($temp_towar_uwagi,$iloscznakowuwag,"newWindow(480,265,'p_towar_uwagi.php?id=$temp_poz_pid')");
			_td();
			list($temp_nr_zl)=mysql_fetch_array(mysql_query("SELECT umowa_nr_zlecenia FROM $dbname.serwis_umowy WHERE umowa_nr='$temp_umowa' LIMIT 1",$conn));
			td_(";;".$temp_pion." ".$temp_up."<br /><font color=grey>".$temp_rodzaj."</font> - ".$temp_nr_zl."");
				if ($pokazpoczatekuwag==1) pokaz_uwagi($temp_uwagi,$iloscznakowuwag,"newWindow(480,265,'p_sprzedaz_uwagi.php?id=$temp_id')");
			_td();
			
			echo "<td class=center>";
				
				if ($hd_zgl_id>0) {
					list($hd_zgl_hadim)=mysql_fetch_array(mysql_query("SELECT zgl_poczta_nr FROM $dbname_hd.hd_zgloszenie WHERE (zgl_nr='$hd_zgl_id') LIMIT 1",$conn_hd));
					echo "<a href=# class=normalfont title=' Sprzedaż powiązana jest ze zgłoszeniem o numerze ".$hd_zgl_id." w bazie Helpdesk ' onclick=\"self.location.href='hd_p_zgloszenia.php?sr=search-wyniki&search_zgl_nr=".$hd_zgl_id."'; return false; \"><b>$hd_zgl_id</b></a>";
					if ($hd_zgl_hadim>0) {
						//echo "<br /><a class=normalfont style='color:red' href=# title='Powiązanie ze zgłoszeniem numer ".$hd_zgl_hadim." w bazie HDIM' onclick=\"newWindow_r(800,600,'https://sd.cit.net.pp/helpdesk/issue.php?id=$hd_zgl_hadim'); return false;\"><b>$hd_zgl_hadim</b></a>";
						echo "<br /><b>$hd_zgl_hadim</b>";
					}
			
				} else echo "<b>-</b>";
				
			echo "</td>";
			
			td(";;".$temp_osoba."<br /><font color=grey>".$temp_data."</font>");
	/*		td_img("40;c");
				if ($temp_uwagi!='') {
				  echo "<a title=' Pokaż uwagi do sprzedaży '><input class=imgoption type=image src=img/comment.gif onclick=\"newWindow(480,265,'p_sprzedaz_uwagi.php?id=$temp_id')\"></a>";
				}
				echo "<a title=' Edytuj uwagi do sprzedaży '><input class=imgoption type=image src=img/edit_comment.gif onclick=\"newWindow(480,270,'e_sprzedaz_uwagi.php?id=$temp_id')\"></a>";
			_td();
	*/
			td_img(";c");
			
			// sprawdzenie czy sprzedaż jest z okresu zraportowanego
					$rok = substr($temp_data,0,4);
					$miesiac = substr($temp_data,5,2);					
					$sql66 = "SELECT sr_miesiac FROM $dbname.serwis_sprzedaz_raport WHERE ((belongs_to=$es_filia) and (sr_rok=$rok) and (sr_miesiac=$miesiac))";				
					$result = mysql_query($sql66, $conn) or die($k_b);
					$count_rows11 = mysql_num_rows($result);
					
					if ($count_rows11>0) {
							echo "<a href=# onclick=\"newWindow_r(500,100,'u_sprzedaz.php?id=$temp_id');return false; \">"; 
							echo "<input class=imgoption type=image src=img/delete.gif title=' Ukryj sprzedaż '>";
							echo "</a>";
					} else {
						$sql_czy_w_zestawie = "SELECT zestawpozycja_zestaw_id FROM $dbname.serwis_zestaw_szcz WHERE (zestawpozycja_fszcz_id=$temp_poz_pid) LIMIT 1";
						$wynik=mysql_num_rows(mysql_query($sql_czy_w_zestawie,$conn));
				
						if ($temp_status==1) {
							if ($wynik==0) {
							
								$accessLevels = array("9"); 
								if(array_search($es_prawa, $accessLevels)>-1){
									$result = mysql_query("SELECT up_id,up_nazwa,up_pion_id,up_umowa_id,pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (up_active=1) and (belongs_to=$es_filia) and (CONCAT(serwis_piony.pion_nazwa,' ',serwis_komorki.up_nazwa)='$temp_pion $temp_up') LIMIT 1", $conn) or die($k_b);
				
						
									list($temp_upid,$temp_nazwa,$temp_pion_id,$temp_umowa_id,$temp_pionnazwa)=mysql_fetch_array($result);
				
									echo "<a title=' Edycja daty sprzedaży i komórki '><input class=imgoption type=image src=img/edit.gif onclick=\"newWindow_r(800,600,'e_towary_obrot.php?tid=$temp_id&f=$temp_fakt_id&pozid=$temp_poz_pid&new_upid=$temp_upid&sdata=$temp_data&srodzaj=".urlencode($temp_rodzaj)."&hd_zgl_nr=$hd_zgl_id&hd_zgl_nr=$hd_zgl_id')\"></a>";
								}
								
								if ($hd_zgl_id>0) {
									echo "<a title='Anuluj sprzedaż podzespołu powiązanego ze zgłoszeniem nr $hd_zgl_id'><input class=imgoption type=image src='img/money_delete_hd.gif' onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_towary_sprzedaz.php?id=$temp_poz_pid'); return false;\"></a>";
								} else {
									echo "<a title='Anuluj sprzedaż: $temp_poz_nazwa '><input class=imgoption type=image src='img/money_delete.gif' onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_towary_sprzedaz.php?id=$temp_poz_pid'); return false;\"></a>";
								}
								
								echo "<a title=' Pokaż fakturę dla tego towaru / usługi '><input class=imgoption type=image src=img/search.gif  onclick=\"newWindow_r(800,600,'p_faktura_pozycje.php?id=$temp_fakt_id&pozid=$temp_poz_pid');\"></a>";

								
								if ($hd_zgl_id>0) {
									$LinkHDZglNr=$hd_zgl_id; include('linktohelpdesk.php');
								}	
								
							} else {
							
								$sql_zestaw_id = "SELECT zestawpozycja_zestaw_id FROM $dbname.serwis_zestaw_szcz WHERE (zestawpozycja_fszcz_id=$temp_poz_pid) LIMIT 1";
								list($nr_zestawu)=mysql_fetch_array(mysql_query($sql_zestaw_id,$conn));
										
								$sql_zestaw_nazwa = "SELECT zestaw_opis FROM $dbname.serwis_zestawy WHERE (zestaw_id=$nr_zestawu) LIMIT 1";
								list($nazwa_zestawu)=mysql_fetch_array(mysql_query($sql_zestaw_nazwa,$conn));
								
								$result = mysql_query("SELECT up_id,up_nazwa,up_pion_id,up_umowa_id,pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (up_active=1) and (belongs_to=$es_filia) and (CONCAT(serwis_piony.pion_nazwa,' ',serwis_komorki.up_nazwa)='$temp_pion $temp_up') LIMIT 1", $conn) or die($k_b);

								list($temp_upid,$temp_nazwa,$temp_pion_id,$temp_umowa_id,$temp_pionnazwa)=mysql_fetch_array($result);
				
								list($hd_zgl_id)=mysql_fetch_array(mysql_query("SELECT wp_zgl_id FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE wp_sprzedaz_fakt_szcz_id='$temp_poz_pid' LIMIT 1",$conn_hd));			
				
								echo "<a title=' Edycja daty sprzedaży i komórki (dla zestawu) '><input class=imgoption type=image src=img/edit_zestaw.gif onclick=\"newWindow_r(800,600,'e_zestaw_obrot.php?zid=$nr_zestawu&tid=$temp_id&f=$temp_fakt_id&pozid=$temp_poz_pid&new_upid=$temp_upid&sdata=$temp_data&srodzaj=".urlencode($temp_rodzaj)."&hd_zgl_nr=$hd_zgl_id')\"></a>";
									
								if ($allow_sell==1) {
									
									if ($hd_zgl_id>0) {
										echo "<a title='Anuluj sprzedaż zestawu: $nazwa_zestawu powiązanego z wymianą podzespołów w zgłoszeniu nr $hd_zgl_id'><input class=imgoption type=image src='img/money_delete_hd.gif' onclick=\"newWindow_r(800,600,'u_zestaw_sprzedaz.php?id=$nr_zestawu'); return false;\"></a>";
									} else {
										echo "<a title='Anuluj sprzedaż zestawu: $nazwa_zestawu '><input class=imgoption type=image src='img/money_delete.gif' onclick=\"newWindow_r(800,600,'u_zestaw_sprzedaz.php?id=$nr_zestawu'); return false;\"></a>";
									}
									
								}
								
								echo "<a title=' Element zestawu: $nazwa_zestawu '><input class=imgoption type=image src=img/basket.gif onclick=\"newWindow(800,600,'p_zestaw_pozycje.php?id=$nr_zestawu&sold=1&showall=1&paget=1')\"></a>";
								
								echo "<a title=' Pokaż fakturę dla tego towaru / usługi '><input class=imgoption type=image src=img/search.gif  onclick=\"newWindow_r(800,600,'p_faktura_pozycje.php?id=$temp_fakt_id&pozid=$temp_poz_pid');\"></a>";
								
								
								if ($hd_zgl_id>0) {
									$LinkHDZglNr=$hd_zgl_id; include('linktohelpdesk.php');
								}	
								
								echo "<br /><a title=' Nazwa zestawu: $nazwa_zestawu '>[<b>$nazwa_zestawu</b>]</a>";
							}
						}
					}
			_td();
		_tr();
		}
		endtable();	
}

br();
pageheader("Pokaż sprzedaż w okresie wg pracownika/ów");
starttable("350px");
echo "<form name=ruch action=p_sprzedaz_w_okresie.php method=GET onsubmit='return validateForm();'>";
tbl_empty_row();
	tr_();
		td_colspan(2,'c');
			echo "<b>Podaj zakres dat<br /><br /></b>";
		_td();
	_tr();
	tr_();
		td("150;c;od dnia");
		td("150;c;do dnia");
	_tr();
	
	$r1 = Date('Y');
	$m1 = Date('m');
	$d1 = '01';
	
	$r2 = Date('Y');
	$m2 = Date('m');
	
	if ($m2==1) $d2=31;
	if ($m2==2) $d2=29;
	if ($m2==3) $d2=31;
	if ($m2==4) $d2=30;
	if ($m2==5) $d2=31;
	if ($m2==6) $d2=30;
	if ($m2==7) $d2=31;
	if ($m2==8) $d2=31;
	if ($m2==9) $d2=30;
	if ($m2==10) $d2=31;
	if ($m2==11) $d2=30;
	if ($m2==12) $d2=31;

	$data1=$r1.'-'.$m1.'-'.$d1;
	$data2=$r1.'-'.$m1.'-'.$d2;

	if ($_REQUEST[okres_od]!='') $data1 = $_REQUEST[okres_od];
	if ($_REQUEST[okres_do]!='') $data2 = $_REQUEST[okres_do];
	if ($_REQUEST[tuser]!='') $user1 = $_REQUEST[tuser];
		
	tr_();
		td_img(";c");
			echo "<input class=wymagane size=10 maxlength=10 type=text id=okres_od name=okres_od value=$data1 onkeypress='return handleEnter(this, event);' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\">";
			echo "<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif width=16 height=16 border=0></a>";
			if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('okres_od').value='".Date('Y-m-d')."'; return false;\">";
		_td();
		td_img(";c");
			echo "<input class=wymagane size=10 maxlength=10 type=text id=okres_do name=okres_do value=$data2 onkeypress='return handleEnter(this, event);' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\">";
			echo "<a tabindex=-1 href=javascript:cal11.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif width=16 height=16 border=0></a>";
			if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('okres_do').value='".Date('Y-m-d')."'; return false;\">";
		_td();	
	_tr();
	tbl_empty_row();
	tr_();
		td_colspan(2,'c');
			echo "<b>Wybierz pracownika<br /><br /></b>";
		_td();
	_tr();
	tr_();
		td_colspan(2,'c');
			$result6 = mysql_query("SELECT user_first_name, user_last_name FROM $dbname.serwis_uzytkownicy WHERE (belongs_to=$es_filia) and (user_locked=0) ORDER BY user_last_name", $conn) or die($k_b);
			echo "<select name=tuser onkeypress='return handleEnter(this, event);'>\n"; 					 				
			echo "<option value='all' "; 
			if ($_REQUEST[tuser]=='all') echo " SELECTED ";
			
			echo " >Wszyscy z bieżącej filii";
			while (list($temp_imie,$temp_nazwisko) = mysql_fetch_array($result6)) {
				$iin = $temp_imie." ".$temp_nazwisko;
				echo "<option value='$temp_imie $temp_nazwisko' ";
				if ($_REQUEST[tuser]==$iin) echo " SELECTED ";
				echo ">$temp_imie $temp_nazwisko</option>\n"; 
			}
			echo "</select>\n";
		_td();
	_tr();
tbl_empty_row();
endtable();
startbuttonsarea("center");
addownsubmitbutton("'Pokaż'","submit");
endbuttonsarea();
_form();

?>
<script language="JavaScript">
var cal1 = new calendar1(document.forms['ruch'].elements['okres_od']);
	cal1.year_scroll = true;
	cal1.time_comp = false;
var cal11 = new calendar1(document.forms['ruch'].elements['okres_do']);
	cal11.year_scroll = true;
	cal11.time_comp = false;
</script>
<script language="JavaScript" type="text/javascript">
	var frmvalidator  = new Validator("ruch");
	frmvalidator.addValidation("okres_od","req","Nie podałeś daty początkowej");  
	frmvalidator.addValidation("okres_do","req","Nie podałeś daty końcowej");  
	frmvalidator.addValidation("okres_od","numerichyphen","Użyłeś niedozwolonych znaków w polu \"od dnia\"");
	frmvalidator.addValidation("okres_do","numerichyphen","Użyłeś niedozwolonych znaków w polu \"do dnia\"");
</script>
<?php }
?>
</body>
</html>