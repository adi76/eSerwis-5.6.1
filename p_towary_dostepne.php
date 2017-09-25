<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php'); 
?>
<body onload="RecoverScroll.init('p_towary_dostepne')">
<?php
include('body_start.php');
include('inc_encrypt.php');

if ($submit) {
	$_POST=sanitize($_POST);
	$ile=$_POST[ilosc];
	$i=0;
	$countpozycje = 0;
	while ($i <= $ile) {
		$pozycja_id = $_POST['dozestawu'.$i.''];
		if ($_POST['dozestawu'.$i.'']!='') $countpozycje++;
		$i++;
	}
	if ($countpozycje==0) { 
		?>
		<script>
			alert("Nie wybrałeś żadnych towarów / usług do dodania do zestawu");
		</script>
		<meta http-equiv="REFRESH" content="0;url=p_towary_dostepne.php?view=dozestawu&addto=<?php echo $_POST[nrzestawu];?>">
		<?php		
	} else {
	
	if ($_POST[nowy]==1) {
		// utworzenie nowego zestawu
		$dddd = Date('Y-m-d H:i:s');
		$sql_utworz_zestaw = "INSERT INTO $dbname.serwis_zestawy VALUES ('','$_POST[opiszestawu]','$currentuser','$dddd','',0,0,0,$es_filia)";
		//echo "$sql_utworz_zestaw<br /><br />";
		$utworz_zestaw = mysql_query($sql_utworz_zestaw, $conn) or die($k_b);
		$zestawid = mysql_insert_id();
	} else $zestawid=$_POST[nrzestawu];
	
	$ile=$_POST[ilosc];
	$i=0;
//	echo "Ilość : $ile<br />";
	$countpozycje = 0;
	while ($i <= $ile) {
		$pozycja_id = $_POST['dozestawu'.$i.''];
		//echo "ID=$pozycja_id ";
		if ($_POST['dozestawu'.$i.'']!='') {
			$sql_zmienstatuspozycji = "UPDATE $dbname.serwis_faktura_szcz SET pozycja_status=5 WHERE pozycja_id=$pozycja_id LIMIT 1";
			$zmienstatus = mysql_query($sql_zmienstatuspozycji,$conn) or die($k_b);
			//echo "$sql_zmienstatuspozycji<br />";
			$sql_dodajpozycje = "INSERT INTO $dbname.serwis_zestaw_szcz values ('',$zestawid,$pozycja_id)";
			$zapisz_pozycje = mysql_query($sql_dodajpozycje, $conn) or die($k_b);
			$countpozycje++;
			//echo "$sql_t<br />";
		}
		$i++;
	}
	if ($countpozycje>0) {
		if ($_POST[nowy]==1) {
			okheader("Pomyślnie utworzono zestaw i dodano do niego ".$countpozycje." towary / usługi"); 
		} else okheader("Pomyślnie zaktualizowano zestaw. Dodano ".$countpozycje." towarów / usług"); 
	} else errorheader("Nie wybrałeś żadnych towarów / usług do dodania do zestawu");
	
	startbuttonsarea("right");	
	addlinkbutton("'Utworzone zestawy'","z_towary_zestawy.php");
	addlinkbutton("'Nowy zestaw'","p_towary_dostepne.php?view=dozestawu&addto=0");
	addlinkbutton("'Towary na stanie'","p_towary_dostepne.php?view=normal&wybor=".urlencode($wybor)."");
	addbuttons("start");
	endbuttonsarea();
}
} else {

if ($_GET[magazyn_id]!='') { $wybierz_magazyn = $_GET[magazyn_id]; } else { $wybierz_magazyn = $es_filia; }

$sql="SELECT * FROM $dbname.serwis_faktura_szcz WHERE (belongs_to=$wybierz_magazyn) and ((pozycja_status='0') || (pozycja_status='5'))";
if ($wybor!='') $sql.=" and (pozycja_typ='$wybor') ";
$result = mysql_query($sql, $conn) or die($k_b);
$count_rows = mysql_num_rows($result);

if ($count_rows!=0) {

$dopisek='';
if ($_GET[magazyn_id]!='') 
	if ($_GET[magazyn_id]!=$es_filia) $dopisek = " magazynu <b>".$_GET[magazyn_nazwa]."</b>";

if ($_REQUEST[readonly]!='tak') {
	list($fn11)=mysql_fetch_array(mysql_query("SELECT filia_nazwa FROM $dbname.serwis_filie WHERE filia_id=$es_filia LIMIT 1",$conn));
	$dopisek = " filii/oddziału: <font color=red>".$fn11."</font>";
}

if ($view=='') $view='normal';
$e = 1;
if ($_REQUEST[magazyn_nazwa]!='') $e = 0;
if ($view=='normal') pageheader("Przeglądanie towarów dostępnych na stanie".$dopisek,1,$e);
if ($view=='dozestawu') pageheader("Wybierz towar(y) do umieszczenia w zestawie",1,$e);
		
?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
		
//if ($_GET[magazyn_id]=='') {
	startbuttonsarea("center");
	echo "<form name=towary>";
	echo "Wybierz grupę towarową: ";
	$sql2="SELECT * FROM $dbname.serwis_slownik_rola ORDER BY rola_nazwa";
	$result2 = mysql_query($sql2, $conn) or die($k_b);
	echo "<select name=trodzaj onChange='document.location.href=document.towary.trodzaj.options[document.towary.trodzaj.selectedIndex].value'>\n";
	
	$iloscnastanie = mysql_num_rows(mysql_query("SELECT pozycja_id FROM $dbname.serwis_faktura_szcz WHERE ((belongs_to=$wybierz_magazyn) and (pozycja_status=0))",$conn));	
	
	echo "<option value='$PHP_SELF?readonly=$_REQUEST[readonly]&view=$view&wybor=$temp_nazwa5&magazyn_id=$_GET[magazyn_id]&magazyn_nazwa=$_GET[magazyn_nazwa]&pokaz=$_GET[pokaz]&sortby=$_REQUEST[sortby]'>Wszystko"; if ($_REQUEST[pokaz]=='all') echo " | $iloscnastanie sztuk";
	
	while ($newArray2 = mysql_fetch_array($result2)) {
		$temp_id5  	= $newArray2['rola_id'];
		$temp_nazwa5	= $newArray2['rola_nazwa'];
		$iloscnastanie = mysql_num_rows(mysql_query("SELECT pozycja_id FROM $dbname.serwis_faktura_szcz WHERE ((belongs_to=$wybierz_magazyn) and (pozycja_typ='$temp_nazwa5') and (pozycja_status=0))",$conn));
		if ($iloscnastanie>0) {
			echo "<option ";
			if ($wybor==$temp_nazwa5) echo "SELECTED ";
			echo "VALUE='$PHP_SELF?readonly=$_REQUEST[readonly]&view=$view&wybor=".urlencode($temp_nazwa5)."&magazyn_id=$_GET[magazyn_id]&magazyn_nazwa=$_GET[magazyn_nazwa]&pokaz=$_GET[pokaz]&sortby=$_REQUEST[sortby]'>$temp_nazwa5"; if ($_REQUEST[pokaz]=='all') echo " | $iloscnastanie sztuk"; echo "</option>\n";
		}
	}	
	echo "</select>";

	if ($_REQUEST[magazyn_id]=='') {
		echo "&nbsp;|&nbsp;Pokaż towary: ";
		echo "<select name=tpokaz onChange='document.location.href=document.towary.tpokaz.options[document.towary.tpokaz.selectedIndex].value'>\n";
		echo "<option value='$PHP_SELF?view=$view&wybor=".urlencode($_REQUEST[wybor])."&magazyn_id=$_GET[magazyn_id]&magazyn_nazwa=$_GET[magazyn_nazwa]&pokaz=all&sortby=$_REQUEST[sortby]'";
		if (($_REQUEST[pokaz]=='') || ($_REQUEST[pokaz]=='all')) echo "SELECTED "; 
		echo ">wszystkie";
		
		echo "<option "; if ($_REQUEST[pokaz]=='0') echo "SELECTED "; echo "VALUE='$PHP_SELF?view=$view&wybor=".urlencode($_REQUEST[wybor])."&magazyn_id=$_GET[magazyn_id]&magazyn_nazwa=$_GET[magazyn_nazwa]&pokaz=0&sortby=$_REQUEST[sortby]'>niepowiązane ze zgłoszeniem</option>\n";
		echo "<option "; if ($_REQUEST[pokaz]=='1') echo "SELECTED "; echo "VALUE='$PHP_SELF?view=$view&wybor=".urlencode($_REQUEST[wybor])."&magazyn_id=$_GET[magazyn_id]&magazyn_nazwa=$_GET[magazyn_nazwa]&pokaz=1&sortby=$_REQUEST[sortby]'>powiązane ze zgłoszeniem</option>\n";
		
		echo "</select>";
	}
	
	$accessLevels = array("1", "9");
	if(array_search($es_prawa, $accessLevels)>-1){
		//echo "<span style='float:left'>";
		if ($_GET[magazyn_id]=='') {
			echo "&nbsp;|&nbsp;Pokaż wybraną grupę towarową w lokalizacji: ";
			$query444 = "SELECT filia_skrot,filia_nazwa,filia_id FROM $dbname.serwis_filie WHERE (filia_id<>$es_filia)";
			if ($result444 = mysql_query($query444,$conn)) while (list($fskrot,$fnazwa,$fid)=mysql_fetch_array($result444)) addownlinkbutton("' ".$fskrot." '","button","button","newWindow_r($_COOKIE[max_x],$_COOKIE[max_y],'p_towary_dostepne.php?readonly=tak&view=".$_REQUEST[view]."&wybor=".$_REQUEST[wybor]."&magazyn_id=$fid&magazyn_nazwa=".urlencode($fnazwa)."')");
		}
			//echo "</span>";
	}

_form();

endbuttonsarea();
//}

if (($PTPNPZPM==1) && (($_REQUEST[magazyn_id]==$es_filia) || ($_REQUEST[magazyn_id]==''))) {
	
	echo "<h3 id=h3_typy  style='font-size:13px;font-weight:normal;padding-top:4px;padding-bottom:4px;margin-top:0px;margin-bottom:5px;text-align:left;color:#313131;display:block;'>&nbsp;";
	
	echo "<a href=# class=normalfont id=pokaz_zbiorcze style='display:none' type=button onClick=\"document.getElementById('typy_niepowiazane').style.display=''; document.getElementById('pokaz_zbiorcze').style.display='none'; document.getElementById('ukryj_zbiorcze').style.display=''; createCookie('p_typy_podzespolow','TAK',365); \"><img class=imgoption style='border:0px' type=image src=img/expand.gif width=11 width=11>&nbsp;Pokaż typy podzespołów powiązanych ze zgłoszeniami Helpdesk - niepowiązanymi z konkretymi towarami z magazynu</a>";
	echo "<a href=# class=normalfont id=ukryj_zbiorcze style='display:none' type=button onClick=\"document.getElementById('typy_niepowiazane').style.display='none'; document.getElementById('pokaz_zbiorcze').style.display=''; document.getElementById('ukryj_zbiorcze').style.display='none'; createCookie('p_typy_podzespolow','NIE',365); \"><img class=imgoption style='border:0px' type=image src=img/collapse.gif width=11 width=11>&nbsp;Pokaż typy podzespołów powiązanych ze zgłoszeniami Helpdesk - niepowiązanymi z konkretymi towarami z magazynu</a>";
	
	//Pokaż typy podzespołów powiązanych ze zgłoszeniami Helpdesk - niepowiązanymi z konkretymi towarami z magazynu
	echo "</h3>";
		
	echo "<span id=typy_niepowiazane style='display:none'>";
		// pokaz_typy_podzespolow_nie_powiazane_z_pozycja_magazynowa
		$result_wp = mysql_query("SELECT wp_id,wp_zgl_id,wp_sprzet_opis,wp_sprzet_sn,wp_sprzet_ni,wp_typ_podzespolu,wp_sprzet_pocztowy,wp_sprzet_pocztowy_uwagi,wp_uwagi FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_sprzet_active=1) and (belongs_to=$es_filia) and (wp_typ_podzespolu<>'') and (wp_wskazanie_sprzetu_z_magazynu=0) and (wp_sprzedaz_fakt_szcz_id=0)",$conn);
		
		$count_result_wp = mysql_num_rows($result_wp);
		
		// spradź czy pozycje niepowiązane nie są pocztowe i nie jest sprzedany zestaw powiązany z jego zgłoszeniem
		$zlicz = 0;
		while (list($wp_id,$wp_zgl_id,$wp_sprzet_o, $wp_sprzet_sn, $wp_sprzet_ni,$wp_typ,$wp_sp,$wp_sp_uwagi,$wp_uwagi)=mysql_fetch_array($result_wp)) {
			//echo "SELECT zestaw_status FROM $dbname.serwis_zestawy WHERE (zestaw_from_hd='1') and (zestaw_hd_zgl_nr=$wp_zgl_id) LIMIT 1";
			
			list($zestaw_sprzedany)=mysql_fetch_array(mysql_query("SELECT zestaw_status FROM $dbname.serwis_zestawy WHERE (zestaw_from_hd='1') and (zestaw_hd_zgl_nr=$wp_zgl_id) LIMIT 1",$conn));
			
			if ($zestaw_sprzedany>0) $zlicz++;
		}

		$i = 2000;
		$t = 1;
		$result_wp = mysql_query("SELECT wp_id,wp_zgl_id,wp_sprzet_opis,wp_sprzet_sn,wp_sprzet_ni,wp_typ_podzespolu,wp_sprzet_pocztowy,wp_sprzet_pocztowy_uwagi,wp_uwagi FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_sprzet_active=1) and (belongs_to=$es_filia) and (wp_typ_podzespolu<>'') and (wp_wskazanie_sprzetu_z_magazynu=0) and (wp_sprzedaz_fakt_szcz_id=0)",$conn_hd);
		
		//echo "$count_result_wp  |  $zlicz";
		
		if (($count_result_wp-$zlicz)>0) {
			starttable();
			echo "<th class=center>LP</th><th class=center>Nr zgłoszenia</th><th>Komórka powiązana</th><th>Typ podzespołu</th><th>Informacje o sprzęcie</th><th class=center>Uwagi</th><th class=center>Opcje</th>";
			while (list($wp_id,$wp_zgl_id,$wp_sprzet_o, $wp_sprzet_sn, $wp_sprzet_ni,$wp_typ,$wp_sp,$wp_sp_uwagi,$wp_uwagi)=mysql_fetch_array($result_wp)) {

				$result_wp22 = mysql_query("SELECT wp_wskazanie_sprzetu_z_magazynu, wp_typ_podzespolu, wp_sprzedaz_fakt_szcz_id, wp_sprzet_pocztowy FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_sprzet_active=1) and (belongs_to=$es_filia) and (wp_zgl_id='$wp_zgl_id')",$conn_hd);
				
				$cnt_all22 = 0;
				$pokaz = 0;
				while (list($wp_z_mag22,$wp_typ22,$wp_fszcz22,$wp_sp22)=mysql_fetch_array($result_wp22)) {
					//echo "$wp_z_mag22,$wp_typ22,$wp_fszcz22,$wp_sp22<br />";
					
					if (($wp_z_mag22=='0') && ($wp_sp22=='0')) $pokaz++;
					if (($wp_z_mag22=='1') && ($wp_fszcz22>0)) {
						list($pobierz_status)=mysql_fetch_array(mysql_query("SELECT pozycja_status FROM $dbname.serwis_faktura_szcz WHERE (pozycja_id=$wp_fszcz22) LIMIT 1",$conn));
						if (($pobierz_status=='0') || ($pobierz_status=='5')) $pokaz++;
					}
					$cnt_all22++;
				}
				
				if ($pokaz>0) { $czy_pokazac=true; } else { $czy_pokazac=false; }

			
			if ($czy_pokazac==true) {

				list($kolorgrupy)=mysql_fetch_array(mysql_query("SELECT rola_kolor FROM $dbname.serwis_slownik_rola WHERE rola_nazwa='$wp_typ' LIMIT 1",$conn));
				//tbl_tr_color($i, $kolorgrupy);
				
				tbl_tr_color_dblClick_towary_dostepne($i,$kolorgrupy);
	
				//echo "<tr>";
				echo "<td class=center>";
					echo $t;
				echo "</td>";
				echo "<td class=center>";
					echo "<a class=normalfont href=# title=' Przejdź do zgłoszenia nr ".$wp_zgl_id." w bazie Helpdesk' onclick=\"self.location.href='hd_p_zgloszenia.php?sr=search-wyniki&search_zgl_nr=".$wp_zgl_id."'; return false; \">$wp_zgl_id</a>";	
				echo "</td>";
				echo "<td class=left>";
					list($komorka)=mysql_fetch_array(mysql_query("SELECT zgl_komorka FROM $dbname_hd.hd_zgloszenie WHERE (zgl_widoczne=1) and (zgl_nr=$wp_zgl_id) LIMIT 1",$conn));
					echo $komorka;
				echo "</td>";
				echo "<td>";
					echo $wp_typ;
					if ($wp_sp==1) echo "&nbsp;<font color=red>| pocztowy</font>";
					if ($wp_sp_uwagi!='') pokaz_uwagi($wp_sp_uwagi,30,"newWindow(480,265,'p_sp_uwagi.php?wpid=$wp_id');return false;");
				echo "</td>";
				echo "<td>";
					echo $wp_sprzet_o;
					if ($wp_sprzet_sn!='') echo " (SN: ".$wp_sprzet_sn.") ";
					if ($wp_sprzet_ni!='') echo " (NI: ".$wp_sprzet_ni.") ";
				echo "</td>";
				echo "<td class=center>";
					if ($wp_sp_uwagi!='') {
					  echo "<a title='Uwagi o podzespole'><input class=imgoption type=image src=img/comment.gif onclick=\"newWindow(480,265,'p_sp_uwagi.php?wpid=$wp_id'); return false;\"></a>";
					  echo "<a title=' Edytuj uwagi o podzespole '><input class=imgoption type=image src=img/edit_comment.gif onclick=\"newWindow(480,245,'e_sp_uwagi.php?wpid=$wp_id'); return false;\"></a>";
					}
					if ($wp_sp_uwagi=='') {
						echo "<a title=' Edytuj uwagi o podzespole '><input class=imgoption type=image src=img/edit_comment.gif onclick=\"newWindow(480,245,'e_sp_uwagi.php?wpid=$wp_id'); return false;\"></a>";
					}
				echo "</td>";				
			echo "<td class=center>";
				if ($_REQUEST[magazyn_nazwa]!='') {
				} else {
					echo "<a title='Usuń wybrany typ podzespołu z wymiany z tego zgłoszenia ($wp_typ)'><input class=imgoption type=image src='img/delete.gif' onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_hd_wp.php?id=$wp_id&wp_opis=".urlencode($wp_typ)."&wp_sn=&nr=".$wp_zgl_id."&typ=1'); return false;\"></a>";
					
					// sprzet pocztowy
					if ($wp_sp=='1') {
						echo "<a title='Ustaw jako podzespół nowy'><input class=imgoption type=image src=img/none_poczta.gif onclick=\"newWindow_r($dialog_window_x,100,'hd_g_wp_ustaw_typ.php?wpid=$wp_id&pocztowy=0&typ=".urlencode($wp_typ)."'); return false;\"></a>";	
					} else {
						echo "<a title='Ustaw jako podzespół \"pocztowy\"''><input class=imgoption type=image src=img/poczta.gif onclick=\"newWindow_r($dialog_window_x,200,'hd_g_wp_ustaw_typ.php?wpid=$wp_id&pocztowy=1&typ=".urlencode($wp_typ)."'); return false;\"></a>";	
					}
					
					if ($wp_zgl_id>0) {
						$LinkHDZglNr=$wp_zgl_id; include('linktohelpdesk.php');
					}
					
					// spradź czy są na stanie towary z tego typu

					$sql55 = "SELECT pozycja_id FROM $dbname.serwis_faktura_szcz WHERE (pozycja_status='0') and (belongs_to=$es_filia) and (pozycja_typ='$wp_typ')";
					$result55 = mysql_query($sql55, $conn) or die($k_b);
					$count_rows56 = mysql_num_rows($result55);
					
					$sql55 = "SELECT * FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (belongs_to=$es_filia) and (wp_typ_podzespolu='$wp_typ') and (wp_sprzedaz_fakt_szcz_id NOT IN (SELECT pozycja_id FROM $dbname.serwis_faktura_szcz WHERE (pozycja_status='0') and (belongs_to=$es_filia) and (pozycja_typ='$wp_typ')))";
					
				//	$sql55="SELECT pozycja_id FROM $dbname.serwis_faktura_szcz WHERE (serwis_faktura_szcz.pozycja_status='0')";
				//	$sql55=$sql55." and (belongs_to=$es_filia) ";
				//	$sql55=$sql55." and (pozycja_typ='$wp_typ')";

					$result55 = mysql_query($sql55, $conn) or die($k_b);
					$count_rows55 = mysql_num_rows($result55);
	
					if ($count_rows56==0) $count_rows55 = 0;
					
					if ($count_rows55>0) {
						if ($allow_sell==1) {
							if ($wp_sp=='0') {
								echo "<a title='Powiąż typ podzespołu\usługi: $wp_typ z pozycją w magazynie'><input class=imgoption type=image src=img/powiaz.gif onclick=\"newWindow_r(800,600,'z_powiaz_typ_z_towarem.php?wpid=$wp_id&typ=".urlencode($wp_typ)."&zglnr=$wp_zgl_id&sopis=".urlencode($wp_sprzet_o)."&ssn=".urlencode($wp_sprzet_sn)."&sni=".urlencode($wp_sprzet_ni)."')\"></a>";
							}
						} else echo "-";
					} else {
						if ($wp_sp=='0') {
						
							echo "<a title='Powiąż typ podzespołu\usługi: $wp_typ z dowolną pozycją w magazynie'><input class=imgoption type=image src=img/powiaz_dowolny.gif onclick=\"newWindow_r(800,600,'z_powiaz_typ_z_towarem.php?wpid=$wp_id&typ=".urlencode($wp_typ)."&zglnr=$wp_zgl_id&sopis=".urlencode($wp_sprzet_o)."&ssn=".urlencode($wp_sprzet_sn)."&sni=".urlencode($wp_sprzet_ni)."&dowolny=1')\"></a>";
						
							echo "<br /><b>brak na magazynie</b>";
							$accessLevels = array("1","9"); 
							if(array_search($es_prawa, $accessLevels)>-1){
								echo "<br />";
								echo "<input type=button class=buttons value='Dodaj fakturę' onClick=\"newWindow_r(800,600,'d_faktura.php'); return false; \" />";
								echo "<input type=button class=buttons value='Faktury niezatwierdzone' onClick=\"self.location.href='z_faktury.php?showall=0';\" />";
							}
						}
					}
				}
				echo "</td>";
				$i++;
				$t++;
				echo "</tr>";
			}
			}
			endtable();
			echo "<hr />";
		}
	echo "</span>";
?>

<?php if (($count_result_wp-$zlicz)>0) { ?>
<script>
document.getElementById('typy_niepowiazane').style.display='';
</script>
<?php } else { ?>
<script>
document.getElementById('typy_niepowiazane').style.display='none';
</script>
<?php } ?>

<script>
<?php if ($_COOKIE['p_typy_podzespolow']==null) { ?>
	document.getElementById('pokaz_zbiorcze').style.display='none';
	document.getElementById('ukryj_zbiorcze').style.display='';
	//document.getElementById('typy_niepowiazane').style.display='none';
	createCookie('p_typy_podzespolow','TAK',365);
<?php } ?>
if (readCookie('p_typy_podzespolow')=='TAK') {
	document.getElementById('pokaz_zbiorcze').style.display='none';
	document.getElementById('ukryj_zbiorcze').style.display='';
	//document.getElementById('typy_niepowiazane').style.display='';
} else {
	document.getElementById('pokaz_zbiorcze').style.display='';
	document.getElementById('ukryj_zbiorcze').style.display='none';
	//document.getElementById('typy_niepowiazane').style.display='none';
}
if (readCookie('p_typy_podzespolow')==null) {
	document.getElementById('pokaz_zbiorcze').style.display='none';
	document.getElementById('ukryj_zbiorcze').style.display='';
	//document.getElementById('typy_niepowiazane').style.display='none';
	createCookie('p_typy_podzespolow','TAK',365);
}
</script>

<?php
	
// SELECT wp_typ_podzespolu FROM hd_zgl_wymiany_podzespolow WHERE (wp_sprzet_active=1) and (belongs_to=1) and (wp_typ_podzespolu<>'') and (wp_wskazanie_sprzetu_z_magazynu=0) and (wp_sprzedaz_fakt_szcz_id=0)
}

?>
<script>HideWaitingMessage();</script>
<?php 

echo "<table id=tabela_z_towarami style='margin-bottom:2px' cellspacing=1 class=maxwidth>";
if ($view=='dozestawu') {
	echo "<form name=tdz action=$PHP_SELF method=POST>";	
}
	
echo "<tr>";
echo "<th class=center>LP</th>";
if ($view=='dozestawu') { echo "<th class=center><sub>do<br/>zestawu</sub></th>"; }
echo "<th>Nazwa towaru&nbsp;";
			
			echo "<a href=# onClick='self.location.href=\"$phpfile?readonly=$_REQUEST[readonly]&view=$view&wybor=$_REQUEST[wybor]&magazyn_id=$_GET[magazyn_id]&magazyn_nazwa=$_GET[magazyn_nazwa]&pokaz=$_GET[pokaz]&sortby=AD\"'>";
			echo "<img class=imgoption style='border:0px' type=image src=img/sortuj_malejaco"; if ($_REQUEST[sortby]!='AD') echo "_inactive"; echo ".gif title=' Sortuj malejąco po nazwie towaru ' width=16 height=16></a>";
			
			echo "<a href=# onClick='self.location.href=\"$phpfile?readonly=$_REQUEST[readonly]&view=$view&wybor=$_REQUEST[wybor]&magazyn_id=$_GET[magazyn_id]&magazyn_nazwa=$_GET[magazyn_nazwa]&pokaz=$_GET[pokaz]&sortby=AA\"'>";
			echo "<img class=imgoption style='border:0px' type=image src=img/sortuj_rosnaco"; if ($_REQUEST[sortby]!='AA') echo "_inactive"; echo ".gif title=' Sortuj rosnąco po nazwie towaru ' width=16 height=16></a>";
			
			if ($_REQUEST[sortby]!='') {
				echo "<span style='float:right;'>";
				if ($_REQUEST[magazyn_id]=='') {
					addlinkbutton("'Domyślne sortowanie'","p_towary_dostepne.php?view=normal&wybor=".urlencode($_REQUEST[wybor])."&pokaz=".urlencode($_REQUEST[pokaz])."");
				} else {
					addlinkbutton("'Domyślne sortowanie'","p_towary_dostepne.php?readonly=$_REQUEST[readonly]&view=$_REQUEST[view]&wybor=".urlencode($_REQUEST[wybor])."&magazyn_id=$_REQUEST[magazyn_id]&magazyn_nazwa=".urlencode($_REQUEST[magazyn_nazwa])."&pokaz=".urlencode($_REQUEST[pokaz])."&sortby=");
				}
				echo "</span>";
			}
			
echo "<br />Określony rodzaj sprzedaży</th><th>Numer seryjny<br />Gwarancja</th>";

// -
// access control 

$accessLevels = array("1", "9");
if(array_search($es_prawa, $accessLevels)>-1){
echo "<th class=right>Cena netto<br />z faktury</th>";
}
// access control koniec
// -

// -
// access control 
$accessLevels = array("9");
if(array_search($es_prawa, $accessLevels)>-1){
	echo "<th class=right>Cena netto<br />odsprzedaży</th>";
}
// access control koniec
// -

// -
// access control 
$accessLevels = array("1", "9");
if(array_search($es_prawa, $accessLevels)>-1){

	echo "<th class=center>Nr zamówienia</th><th>Dostawca<br />Nr faktury, data wystawienia</th>";
}
// access control koniec
// -

echo "<th class=center>Uwagi</th><th class=center>Opcje</th>";

echo "</tr>";

if ($_GET[magazyn_id]!='') { $wybierz_magazyn = $_GET[magazyn_id]; } else { $wybierz_magazyn = $es_filia; }

$sql="SELECT * FROM $dbname.serwis_faktura_szcz,$dbname.serwis_faktury WHERE ((serwis_faktura_szcz.belongs_to=$wybierz_magazyn) and ((serwis_faktura_szcz.pozycja_status='0')";
if ($view!='dozestawu') $sql .= " or (serwis_faktura_szcz.pozycja_status='5')"; 
$sql.=") and (serwis_faktury.faktura_id=serwis_faktura_szcz.pozycja_nr_faktury))";
if ($wybor!='') $sql.=" and (pozycja_typ='$wybor') ";

if ($_REQUEST[sortby]=='AA') $sql.=" ORDER BY serwis_faktura_szcz.pozycja_typ ASC, serwis_faktura_szcz.pozycja_nazwa ASC, serwis_faktury.faktura_data ASC";
if ($_REQUEST[sortby]=='AD') $sql.=" ORDER BY serwis_faktura_szcz.pozycja_typ ASC, serwis_faktura_szcz.pozycja_nazwa DESC, serwis_faktury.faktura_data ASC";
if ($_REQUEST[sortby]=='') $sql.=" ORDER BY serwis_faktura_szcz.pozycja_typ ASC, serwis_faktury.faktura_data ASC";

//echo $sql;

$result = mysql_query($sql, $conn) or die($k_b);
$ilepozycji = mysql_num_rows($result);
$i = 0;
$j = 1;
$kwotarazem = 0;
$kwotarazempodfaktury = 0;

$count_pokazane = 0;

while ($newArray = mysql_fetch_array($result)) {
	$temp_id  			= $newArray['pozycja_id'];
	$temp_nrfaktury		= $newArray['pozycja_nr_faktury'];
	$temp_numer			= $newArray['pozycja_numer'];
	$temp_nazwa			= $newArray['pozycja_nazwa'];
	$temp_ilosc			= $newArray['pozycja_ilosc'];
	$temp_sn			= $newArray['pozycja_sn'];
	$temp_cenanetto_cr	= $newArray['pozycja_cena_netto'];
	$temp_status		= $newArray['pozycja_status'];
	$temp_cenanettoodsp_cr	= $newArray['pozycja_cena_netto_odsprzedazy'];
	$temp_typ			= $newArray['pozycja_typ'];
	$temp_uwagi			= $newArray['pozycja_uwagi'];
	$temp_rs			= $newArray['pozycja_rodzaj_sprzedazy'];
	$temp_gw			= $newArray['pozycja_gwarancja'];
	
	$temp_cenanetto = decrypt_md5($temp_cenanetto_cr,$key);
	$temp_cenanettoodsp = decrypt_md5($temp_cenanettoodsp_cr,$key);

	if ($_REQUEST[magazyn_id]=='')	{
		$pokaz_wiersz = false;
		if (($_REQUEST[pokaz]=='all') || ($_REQUEST[pokaz]=='')) $pokaz_wiersz = true;
		
		if ($_REQUEST[pokaz]=='1') {
			$r1a = mysql_query("SELECT wp_zgl_id FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_sprzedaz_fakt_szcz_id='$temp_id') and (belongs_to='$wybierz_magazyn') and (wp_sprzet_active=1) and (wp_typ_podzespolu='')", $conn_hd) or die($k_b);			
			list($wp_zgl_id)=mysql_fetch_array($r1a);
			if ($wp_zgl_id>0) {	
				$pokaz_wiersz = true;
			} 
		}
		
		if ($_REQUEST[pokaz]=='0') {
			$r1a = mysql_query("SELECT wp_zgl_id FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_sprzedaz_fakt_szcz_id='$temp_id') and (belongs_to='$wybierz_magazyn') and (wp_sprzet_active=1) and (wp_typ_podzespolu='')", $conn_hd) or die($k_b);			
			list($wp_zgl_id)=mysql_fetch_array($r1a);
			if ($wp_zgl_id<=0) {	
				$pokaz_wiersz = true;
			}
		}
	} else {
		$pokaz_wiersz = true;
	}

if ($pokaz_wiersz==true) {	
	
	$count_pokazane ++;
	
	list($kolorgrupy)=mysql_fetch_array(mysql_query("SELECT rola_kolor FROM $dbname.serwis_slownik_rola WHERE rola_nazwa='$temp_typ' LIMIT 1",$conn));
	//tbl_tr_color($i, $kolorgrupy);
	
	if ($_GET[id]==$temp_id) {
		tbl_tr_color_with_border($i,'#FFFF55');
	} else tbl_tr_color_dblClick_towary_dostepne($i,$kolorgrupy);
	
	//tbl_tr_color_dblClick_towary_dostepne($i,$kolorgrupy);
	
	$sql1="SELECT * FROM $dbname.serwis_faktury WHERE (faktura_id='$temp_nrfaktury')";

	$result1 = mysql_query($sql1, $conn) or die($k_b);
	
	while ($newArray4 = mysql_fetch_array($result1)) {
		$temp_id4  			= $newArray4['faktura_id'];
		$temp_numer4		= $newArray4['faktura_numer'];
		$temp_data4			= $newArray4['faktura_data'];
		$temp_dostawca4		= $newArray4['faktura_dostawca'];	
		$temp_nz			= $newArray4['faktura_nr_zamowienia'];	
		$temp_btf			= $newArray4['belongs_to'];	
	}
		
	echo "<input type=hidden id=pozid$i value=$temp_id>";
	echo "<input type=hidden id=pozf$i value=$temp_id4>";
	
	
	echo "<td width=30 class=center><a class=normalfont href=# title='$temp_id'>$j</a></td>";
	if ($view=='dozestawu') {
	
		$r1a = mysql_query("SELECT wp_zgl_id FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_sprzedaz_fakt_szcz_id='$temp_id') and (belongs_to='$wybierz_magazyn') and (wp_sprzet_active=1) and (wp_typ_podzespolu='')", $conn_hd) or die($k_b);			
		list($wp_zgl_id)=mysql_fetch_array($r1a);
		if ($wp_zgl_id>0) {
			echo "<td class=center></td>";
		} else {
			echo "<td class=center>";
			echo "<input class=border0 type=checkbox name=dozestawu$j value=$temp_id>";
			echo "</td>";		
		}
	}
	
	echo "<td class=wrap>$temp_nazwa";
	if ($pokazpoczatekuwag==1) pokaz_uwagi($temp_uwagi,$iloscznakowuwag,"newWindow(480,265,'p_towar_uwagi.php?id=$temp_id');return false;");
	if ($temp_rs!='') {
		echo "<br />";
		echo "<font color=grey>$temp_rs</font>";
	} else { 
		echo "<br /><font color=grey><a class=normalfont title=' Nie określono rodzaju sprzedaży ' href=#>-</a></font>";
	}
	echo "</td>";
	echo "<td>";
	
	if (strlen($temp_sn)>0) {
		echo "$temp_sn";
	} else echo "-";
	
	if ($temp_gw!=0) {
		echo "<br /><font color=grey>".$temp_gw." m-ce/cy</font>";
	} else {
		echo "<br />-";
	}
	echo "</td>";
// -
// access control 


$accessLevels = array("1", "9");
if(array_search($es_prawa, $accessLevels)>-1){
	echo "<td class=right>".correct_currency($temp_cenanetto)." zł</td>";
	$kwotarazem+=$temp_cenanetto;
}
// access control koniec
// -
	
// -
// access control 
$accessLevels = array("9");
if(array_search($es_prawa, $accessLevels)>-1){
	echo "<td class=right>".correct_currency($temp_cenanettoodsp)." zł</td>";
	$kwotarazempodfaktury+=$temp_cenanettoodsp;
}
// access control koniec
// -
	
// -
// access control 

$accessLevels = array("1", "9");
if(array_search($es_prawa, $accessLevels)>-1){
	echo "<td class=center>";
	if ($temp_nz!='') { echo "$temp_nz"; } else { echo "-"; }
	echo "</td>";

	if ($temp_numer4=='') { $temp_numer4a = '<font color=grey>brak numeru</font>,'; } else { $temp_numer4a = ',';} 
	echo "<td>$temp_dostawca4<br /><font color=grey>$temp_numer4$temp_numer4a $temp_data4</font>&nbsp;";
	echo "</td>";
}
// access control koniec
// -
echo "<td class=center>";
if ($temp_uwagi!='') {
  echo "<a title=' Czytaj uwagi o towarze '><input class=imgoption type=image src=img/comment.gif onclick=\"newWindow(480,265,'p_towar_uwagi.php?id=$temp_id'); return false;\"></a>";
}
if ($_GET[magazyn_id]=='') {
	echo "<a title=' Edytuj uwagi o towarze '><input class=imgoption type=image src=img/edit_comment.gif onclick=\"newWindow(480,245,'e_towar_uwagi.php?id=$temp_id'); return false;\"></a>";
}
echo "</td>";

$j+=1;
	
$d= Date('d');
$m= Date('m');
$r= Date('Y');

echo "<td class=center>";
	
$jest_zestawem = 0;

if ($temp_status==5) {
	$sql_nr_zestawu = "SELECT zestawpozycja_zestaw_id FROM $dbname.serwis_zestaw_szcz WHERE zestawpozycja_fszcz_id=$temp_id LIMIT 1";
	list($nr_zestawu)=mysql_fetch_array(mysql_query($sql_nr_zestawu,$conn));
	$sql_zestaw_name = "SELECT zestaw_opis,zestaw_hd_zgl_nr FROM $dbname.serwis_zestawy WHERE (zestaw_id=$nr_zestawu) LIMIT 1";
	list($zestaw_name,$zestaw_hd_zgl_nr)=mysql_fetch_array(mysql_query($sql_zestaw_name, $conn));
}

if ($view=='normal') {
if ($temp_rs=='') { $acrs = 1; } else { $acrs = 0; }

	if (($temp_status==0) && ($_GET[magazyn_id]=='')) {
		
		if ($allow_sell==0) {
			$r1a = mysql_query("SELECT wp_zgl_id,wp_sprzet_opis, wp_sprzet_sn, wp_sprzet_ni, wp_sprzet_wykonane_czynnosci, wp_sprzedaz_fakt_szcz_id,wp_wskazanie_sprzetu_z_magazynu, wp_typ_podzespolu FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_sprzedaz_fakt_szcz_id='$temp_id') and (belongs_to='$wybierz_magazyn') and (wp_sprzet_active=1) and (wp_typ_podzespolu='')", $conn_hd) or die($k_b);		

			if (mysql_num_rows($r1a)>0) {
				list($wp_zgl_id,$wp_opis, $wp_sn, $wp_ni, $wp_wc, $wp_fszczid, $wp_z_mag, $wp_typ_pod)=mysql_fetch_array($r1a);
				$nazwa_urzadzenia = $wp_opis;
				$sn_urzadzenia = $wp_sn;
				$ni_urzadzenia = $wp_ni;
				
				$sql_komorka_name = "SELECT zgl_komorka,zgl_nr FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id=$wp_zgl_id) LIMIT 1";
				list($komorka_name,$hd_zgl_nr)=mysql_fetch_array(mysql_query($sql_komorka_name, $conn));
				
				echo "Powiązane ze zgłoszeniem nr: <b>$hd_zgl_nr</b><br />";
				
				$LinkHDZglNr=$hd_zgl_nr; include('linktohelpdesk.php');

			}
		}
		if ($allow_sell==1) {
		
			$r1a = mysql_query("SELECT wp_zgl_id,wp_sprzet_opis, wp_sprzet_sn, wp_sprzet_ni, wp_sprzet_wykonane_czynnosci, wp_sprzedaz_fakt_szcz_id,wp_wskazanie_sprzetu_z_magazynu, wp_typ_podzespolu FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_sprzedaz_fakt_szcz_id='$temp_id') and (belongs_to='$wybierz_magazyn') and (wp_sprzet_active=1) and (wp_typ_podzespolu='')", $conn_hd) or die($k_b);
			
			//echo "SELECT wp_zgl_id,wp_sprzet_opis, wp_sprzet_sn, wp_sprzet_ni, wp_sprzet_wykonane_czynnosci, wp_sprzedaz_fakt_szcz_id,wp_wskazanie_sprzetu_z_magazynu, wp_typ_podzespolu FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_sprzedaz_fakt_szcz_id='$temp_id') and (belongs_to='$wybierz_magazyn') and (wp_sprzet_active=1)";
			
			if (mysql_num_rows($r1a)>0) {
			
				list($wp_zgl_id,$wp_opis, $wp_sn, $wp_ni, $wp_wc, $wp_fszczid, $wp_z_mag, $wp_typ_pod)=mysql_fetch_array($r1a);
				$nazwa_urzadzenia = $wp_opis;
				$sn_urzadzenia = $wp_sn;
				$ni_urzadzenia = $wp_ni;

				$sql_komorka_name = "SELECT zgl_komorka,zgl_nr FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id=$wp_zgl_id) LIMIT 1";
				list($komorka_name,$hd_zgl_nr)=mysql_fetch_array(mysql_query($sql_komorka_name, $conn));
				
				$result_upid = mysql_query("SELECT up_id,up_nazwa,up_pion_id,up_umowa_id,pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (up_active=1) and (belongs_to=$wybierz_magazyn) and (CONCAT(serwis_piony.pion_nazwa,' ',serwis_komorki.up_nazwa)='$komorka_name') LIMIT 1", $conn) or die($k_b);
				
				list($temp_upid,$temp_nazwa,$temp_pion_id,$temp_umowa_id,$temp_pionnazwa)=mysql_fetch_array($result_upid);

				if ($wp_z_mag=='1') {
				
					$r1aa = mysql_query("SELECT wp_sprzedaz_fakt_szcz_id FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_zgl_id='$hd_zgl_nr') and (belongs_to='$wybierz_magazyn') and (wp_sprzedaz_fakt_szcz_id<>0) and (wp_sprzet_active=1)", $conn_hd) or die($k_b);		
					//echo "SELECT wp_sprzedaz_fakt_szcz_id FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_zgl_id='$hd_zgl_nr') and (belongs_to='$wybierz_magazyn')";
						
					$dolinku = '';
					$cnt = 0;
					while (list($addfszcz_id)=mysql_fetch_array($r1aa)) {
					
						$czy_sprzedany = "SELECT pozycja_status FROM $dbname.serwis_faktura_szcz WHERE (pozycja_id=$addfszcz_id) LIMIT 1";
						list($pstatus)=mysql_fetch_array(mysql_query($czy_sprzedany, $conn));
						
						if ($pstatus<=0) {
							$dolinku .= $addfszcz_id."|#|";
							$cnt++;
						}
					}		


					$r1aa = mysql_query("SELECT count(wp_id) FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_zgl_id='$hd_zgl_nr') and (belongs_to='$wybierz_magazyn') and (wp_sprzedaz_fakt_szcz_id=0) and (wp_typ_podzespolu!='') and (wp_sprzet_pocztowy=0) and (wp_sprzet_active=1)", $conn_hd) or die($k_b);
					list($cnt_nie_pocztowe)=mysql_fetch_array($r1aa);					
					
				}

				$cnt_do_linku = $cnt;
				if ($cnt_nie_pocztowe>0) $cnt+=$cnt_nie_pocztowe;
				
				//echo "<".$cnt.">";
				
				echo "Powiązane ze zgłoszeniem nr: <b>$hd_zgl_nr</b> ";
				$pozwol_sprzedac_pojedynczo = 1;
				
				if (($allow_sell==1) && ($dolinku!='') && ($cnt>1)) {
//echo "SELECT wp_zgl_szcz_unique_nr FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_typ_podzespolu='$temp_typ') and (wp_sprzet_active=1) and (belongs_to=$es_filia) and (wp_zgl_id=$hd_zgl_nr) ORDER BY wp_id DESC LIMIT 1";

//(wp_typ_podzespolu='$temp_typ') and 

					list($unique_nr)=mysql_fetch_array(mysql_query("SELECT wp_zgl_szcz_unique_nr FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_sprzet_active=1) and (belongs_to=$es_filia) and (wp_zgl_id=$hd_zgl_nr) ORDER BY wp_id DESC LIMIT 1",$conn_hd));
					
					//list($unique_nr)=mysql_fetch_array(mysql_query("SELECT wp_zgl_szcz_unique_nr FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_typ_podzespolu='$temp_typ') and (wp_sprzet_active=1) and (belongs_to=$es_filia) and (wp_zgl_id=$hd_zgl_nr) ORDER BY wp_id DESC LIMIT 1",$conn_hd));				
				
					echo "<br /><input title='Utwórz zestaw z wybranych podzespołów użytych do tego zgłoszenia' type=button class=buttons value='Utwórz zestaw dla zgl. nr $hd_zgl_nr' onClick=\"newWindow(800,600,'hd_g_zestaw_z_wymiany_podzespolow.php?pozcnt=".$cnt_do_linku."&pozfsz=".urlencode($dolinku)."&unique=$unique_nr&hd_zgl_nr=$hd_zgl_nr'); return false;\">";
					
					$pozwol_sprzedac_pojedynczo = 0;
				}
				
				echo "<br />";
				
				if ($pozwol_sprzedac_pojedynczo==1) 
					echo "<a title='Towar użyty do wymiany podzespołów w zgłoszeniu nr $hd_zgl_nr. Sprzedaj towar : $temp_nazwa o numerze seryjnym : $temp_sn '><input class=imgoption type=image src=img/sell_hd.gif  onclick=\"newWindow_r(700,595,'z_towary_obrot.php?new_upid=$temp_upid&id=$temp_id&f=$temp_id4&obzp=1&trodzaj=".urlencode($temp_rs)."&allow_change_rs=$acrs&nazwa_urzadzenia=".urlencode($nazwa_urzadzenia)."&sn_urzadzenia=".urlencode($sn_urzadzenia)."&ni_urzadzenia=".urlencode($ni_urzadzenia)."&quiet=1&ewid_id=1&readonly=0&wykonane_czynnosci=".urlencode($doprotokolu)."&hd_zgl_nr=$hd_zgl_nr')\"></a>";
				
				
				if ($hd_zgl_nr>0) {
					$LinkHDZglNr=$hd_zgl_nr; include('linktohelpdesk.php');
				}	

			
			} else {

				$result_typ_wp = mysql_query("SELECT wp_sprzedaz_fakt_szcz_id,wp_zgl_id FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_typ_podzespolu='$temp_typ') and (wp_wskazanie_sprzetu_z_magazynu=0) and (wp_sprzedaz_fakt_szcz_id=0) and (wp_sprzet_active=1)", $conn) or die($k_b);
				//echo "SELECT wp_sprzedaz_fakt_szcz_id,wp_zgl_id FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_typ_podzespolu='$temp_typ') and (wp_wskazanie_sprzetu_z_magazynu=0) and (wp_sprzedaz_fakt_szcz_id=0) ";
				
				list($a1,$a2)=mysql_fetch_array($result_typ_wp);
				
				//$count_1 = mysql_num_rows($result_typ_wp);
				//echo $count_1;
				if ($a1!='') {
					//echo "<a title='Powiąż towar: $temp_nazwa (SN: $temp_sn) ze zgłoszeniem w bazie Helpdesk '><input class=imgoption type=image src=img/powiaz.gif onclick=\"newWindow_r(800,600,'z_towary_powiaz_z_hd.php?id=$temp_id&typ=".urlencode($temp_typ)."&nazwa=".urlencode($temp_nazwa)."&sn=".urlencode($temp_sn)."')\"></a>";
				}
				
				echo "<a title='Sprzedaj towar: $temp_nazwa o numerze seryjnym : $temp_sn '><input class=imgoption type=image src=img/sell.gif  onclick=\"newWindow_r(700,595,'z_towary_obrot.php?id=$temp_id&f=$temp_id4&obzp=1&trodzaj=".urlencode($temp_rs)."&allow_change_rs=$acrs&dodajwymianepodzespolow=1')\"></a>";
				
			}
			// obzp - opcja bez zapisu protokołu
		$wc = $temp_nazwa.",".$temp_sn;
		
		$accessLevels = array("9");
		if(array_search($es_prawa, $accessLevels)>-1){
//			echo "<a title=' Generuj protokół '><input class=imgoption type=image src=img//print.gif  onclick=\"newWindow_r(700,595,'utworz_protokol.php?dzien=$d&miesiac=$m&rok=$r&wykonane_czynnosci=".urlencode($wc)."&choosefromewid=1')\"></a>";
		}
	} else {
		//echo "brak uprawnień do sprzedaży";
		if ($temp_typ!='Usługa') {
			//echo "<a title=' Generuj protokół '><input class=imgoption type=image src=img//print.gif  onclick=\"newWindow_r(700,595,'utworz_protokol.php?dzien=$d&miesiac=$m&rok=$r&wykonane_czynnosci=".urlencode($wc)."&choosefromewid=1&source=only-protokol&state=empty&nowy=1&nazwa_urzadzenia=$temp_nazwa&sn_urzadzenia=$temp_sn&ni_urzadzenia=-&c_8=on')\"></a>";
	} else {
		$wc=$temp_nazwa." (SN: ".$temp_sn.") w serwisie";
		
		//echo "<a title=' Generuj protokół '><input class=imgoption type=image src=img//print.gif  onclick=\"newWindow_r(700,595,'utworz_protokol.php?dzien=$d&miesiac=$m&rok=$r&wykonane_czynnosci=".urlencode($wc)."&choosefromewid=1&source=only-protokol&state=empty&nowy=1&c_6=on&nazwa_urzadzenia=$temp_nazwa&sn_urzadzenia=$temp_sn&ni_urzadzenia=-')\"></a>";
	}
	}
	} else {
	
		if ($_GET[magazyn_id]=='') {
				
			if ($allow_sell==1) {
				
				$r1a = mysql_query("SELECT wp_zgl_id,wp_sprzet_opis, wp_sprzet_sn, wp_sprzet_ni, wp_sprzet_wykonane_czynnosci, wp_sprzedaz_fakt_szcz_id,wp_wskazanie_sprzetu_z_magazynu, wp_typ_podzespolu FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_sprzedaz_fakt_szcz_id='$temp_id') and (belongs_to='$wybierz_magazyn') and (wp_sprzet_active=1)", $conn_hd) or die($k_b);		
				
				if (mysql_num_rows($r1a)>0) {
					list($wp_zgl_id,$wp_opis, $wp_sn, $wp_ni, $wp_wc, $wp_fszczid, $wp_z_mag, $wp_typ_pod)=mysql_fetch_array($r1a);
					$nazwa_urzadzenia = $wp_opis;
					$sn_urzadzenia = $wp_sn;
					$ni_urzadzenia = $wp_ni;
					
					$sql_komorka_name = "SELECT zgl_komorka,zgl_nr FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id=$wp_zgl_id) LIMIT 1";
					list($komorka_name,$hd_zgl_nr)=mysql_fetch_array(mysql_query($sql_komorka_name, $conn));
					
					echo "Powiązane ze zgłoszeniem nr: <b>$hd_zgl_nr</b><br />";
					
					$result_upid = mysql_query("SELECT up_id,up_nazwa,up_pion_id,up_umowa_id,pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (up_active=1) and (belongs_to=$wybierz_magazyn) and (CONCAT(serwis_piony.pion_nazwa,' ',serwis_komorki.up_nazwa)='$komorka_name') LIMIT 1", $conn) or die($k_b);
					
					list($temp_upid,$temp_nazwa,$temp_pion_id,$temp_umowa_id,$temp_pionnazwa)=mysql_fetch_array($result_upid);
					
					echo "<a title=' Pokaż elementy zestawu: $zestaw_name '><input class=imgoption type=image src=img/basket.gif onclick=\"newWindow_r(800,600,'p_zestaw_pozycje.php?id=$nr_zestawu&sold=0&showall=1&paget=1&hdzglnr=$hd_zgl_nr'); \"></a>";
				
					//echo "<a title='Towar użyty do wymiany podzespołów w zgłoszeniu nr $hd_zgl_nr. Sprzedaj towar : $temp_nazwa o numerze seryjnym : $temp_sn '><input class=imgoption type=image src=img/sell_hd.gif  onclick=\"newWindow_r(700,595,'z_towary_obrot.php?new_upid=$temp_upid&id=$temp_id&f=$temp_id4&obzp=1&trodzaj=".urlencode($temp_rs)."&allow_change_rs=$acrs&nazwa_urzadzenia=".urlencode($nazwa_urzadzenia)."&sn_urzadzenia=".urlencode($sn_urzadzenia)."&ni_urzadzenia=".urlencode($ni_urzadzenia)."&quiet=1&ewid_id=1&readonly=1&wykonane_czynnosci=".urlencode($doprotokolu)."')\">-</a>";
					echo "<a title=' Sprzedaj zestaw: $zestaw_name '><input class=imgoption type=image src=img/sell_zestaw_hd.gif  onclick=\"newWindow_r(700,595,'g_zestaw_obrot.php?new_upid=$temp_upid&id=$nr_zestawu&allow_change_rs=0&nazwa_urzadzenia=".urlencode($nazwa_urzadzenia)."&sn_urzadzenia=".urlencode($sn_urzadzenia)."&ni_urzadzenia=".urlencode($ni_urzadzenia)."&quiet=1&ewid_id=1&readonly=0&wykonane_czynnosci=".urlencode($doprotokolu)."&hd_zgl_nr=$hd_zgl_nr')\"></a>";
					
					if ($hd_zgl_nr>0) {
						$LinkHDZglNr=$hd_zgl_nr; include('linktohelpdesk.php');
					}
					
					echo "<a title='Wyrzuć podzespoły z zestawu i usuń zestaw: $zestaw_name '><input class=imgoption type=image src=img/basket_delete.png onclick=\"newWindow_r(800,600,'u_zestaw_pozycje.php?id=$nr_zestawu&hdzglnr=$hd_zgl_nr'); \"></a>";

			} else {
				echo "<a title='Sprzedaj zestaw: $zestaw_name '><input class=imgoption type=image src=img/sell_zestaw.gif  onclick=\"newWindow_r(700,595,'g_zestaw_obrot.php?id=$nr_zestawu&allow_change_rs=0&dodajwymianepodzespolow=1')\"></a>";
				}
			}
		
			$jest_zestawem = 1;
		}
		//echo "<a title=' Pokaż pozycje zestawu : $temp_opis '><input class=imgoption type=image src=img/search.gif  onclick=\"newWindow_r(800,600,'p_zestaw_pozycje.php?id=$temp_id&sold=$temp_status&showall=1&paget=1')\"></a>";
	}
	
}

$accessLevels = array("9");
if(array_search($es_prawa, $accessLevels)>-1){

	if ($view=='dozestawu') {
		$r1a = mysql_query("SELECT wp_zgl_id,wp_sprzet_opis, wp_sprzet_sn, wp_sprzet_ni, wp_sprzet_wykonane_czynnosci, wp_sprzedaz_fakt_szcz_id,wp_wskazanie_sprzetu_z_magazynu, wp_typ_podzespolu FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_sprzedaz_fakt_szcz_id='$temp_id') and (belongs_to='$wybierz_magazyn') and (wp_sprzet_active=1)", $conn_hd) or die($k_b);		
		if (mysql_num_rows($r1a)>0) {
			list($wp_zgl_id,$wp_opis, $wp_sn, $wp_ni, $wp_wc, $wp_fszczid, $wp_z_mag, $wp_typ_pod)=mysql_fetch_array($r1a);
			$nazwa_urzadzenia = $wp_opis;
			$sn_urzadzenia = $wp_sn;
			$ni_urzadzenia = $wp_ni;
					
			$sql_komorka_name = "SELECT zgl_komorka,zgl_nr FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id=$wp_zgl_id) LIMIT 1";
			list($komorka_name,$hd_zgl_nr)=mysql_fetch_array(mysql_query($sql_komorka_name, $conn));
					
			echo "Powiązane ze zgłoszeniem nr: <b>$hd_zgl_nr</b><br />";
			
			$LinkHDZglNr=$hd_zgl_nr; include('linktohelpdesk.php');

		}
	}
	
	$sql_c = "SELECT faktura_id FROM $dbname.serwis_faktury WHERE ((faktura_id=$temp_nrfaktury) and (belongs_to=$wybierz_magazyn))";
	$result_c = mysql_query($sql_c, $conn) or die($k_b);
	$count_rows = mysql_num_rows($result_c);
	
	if ($temp_numer4=='') $temp_numer4 = '<brak numeru>';
	if ($count_rows>0) {
		if ($_GET[magazyn_id]=='') 
			echo "<a title=' Pokaż pozycje na fakturze nr $temp_numer4 '><input class=imgoption type=image src=img/search.gif  onclick=\"newWindow_r(800,600,'p_faktura_pozycje.php?id=$temp_id4&pozid=$temp_id&readonly=$_REQUEST[readonly]'); return false; \"></a>";
	} else {
			list($fn11)=mysql_fetch_array(mysql_query("SELECT filia_nazwa FROM $dbname.serwis_filie WHERE filia_id=$temp_btf LIMIT 1",$conn));
			echo "<a title=' Towar został przesunięty z magazynu: $fn11'><input class=imgoption type=image src=img/towar_p.gif></a>";
			echo "<a title=' Pokaż pozycje na fakturze nr $temp_numer4 '><input class=imgoption type=image src=img/search.gif  onclick=\"newWindow_r(800,600,'p_faktura_pozycje.php?id=$temp_id4&pozid=$temp_id&readonly=$_REQUEST[readonly]'); return false; \"></a>";
		}
}

	if (($jest_zestawem==1) && ($allow_sell==1)) {
		echo "<br /><a title=' Nazwa zestawu: $zestaw_name '>[<b>$zestaw_name</b>]</a>";
		
	}
	
	echo "</td>";

	$i+=1;
	echo "</tr>";
	
}
}
endtable();

if ($count_pokazane==0) {
	if ($_REQUEST[pokaz]==1) errorheader("Brak tego typu towaru do odsprzedaży (<b>".$wybor."</b>) dostępnych na stanie magazynu".$dopisek."");
	if ($_REQUEST[pokaz]==0) errorheader("Brak tego typu towaru do odsprzedaży (<b>".$wybor."</b>) w niepowiązanych towarach na stanie magazynu".$dopisek."");
	
	if ($_GET[magazyn_id]=='') { ?>
	<script>
		document.getElementById('tabela_z_towarami').style.display='none';
	</script>
	<meta http-equiv="REFRESH" content="<?php echo $http_refresh; ?>;url=p_towary_dostepne.php?view=<?php echo $view;?>&wybor=&pokaz=<?php echo $_REQUEST[pokaz];?>">
	<?php	
	}
}

if ($view=='dozestawu') {
	echo "<input type=hidden name=ilosc value=$ilepozycji>";
	startbuttonsarea("left");
	if ($addto!=0) {
		$zo_sql = "SELECT zestaw_opis,zestaw_kto,zestaw_kiedy FROM $dbname.serwis_zestawy WHERE zestaw_id=$addto LIMIT 1";
		list($zo,$kto,$kiedy)=mysql_fetch_array(mysql_query($zo_sql,$conn));
		echo "<input type=hidden name=nowy value=0>";
		echo "<input type=hidden name=nrzestawu value=$addto>";
		addownsubmitbutton("'Dodaj zaznaczone elementy do istniejącego zestawu'");
		echo "<br />Opis: <b><a class=normalfont href=# title='Zestaw utworzony przez $kto | $kiedy '>$zo</a></b>";
	} else {	
		
		$dddd=date("Y-m-d H:i");
		echo "<input type=hidden name=nowy value=1>";
		echo "<input type=hidden name=nrzestawu value=0>";
		echo "&nbsp;&nbsp;";
		echo "Opis zestawu: ";
		echo "<input type=text name=opiszestawu size=35 value='Zestaw utworzony ".substr($dddd,0,16)."'>&nbsp;&nbsp;";
		addownsubmitbutton("'Dodaj zaznaczone pozycje do nowego zestawu'");
	}

	endbuttonsarea();
	_form();
}

if (($view=='normal') && ($_GET[magazyn_id]=='')) {
	if (($wybor=='') && ($es_prawa==9)) { 
		startbuttonsarea("right");
		echo "Wartość towarów / usług na magazynie : <b>".correct_currency($kwotarazem)." zł</b><br />";
		echo "Wartość odsprzedaży towarów / usług na magazynie : <b>".correct_currency($kwotarazempodfaktury)." zł</b>";
		endbuttonsarea();
	} elseif (($wybor!='') && ($es_prawa==9)) {
		startbuttonsarea("right");
		echo "Wartość wyświetlonych towarów / usług (<b>$wybor</b>) na magazynie: <b>".correct_currency($kwotarazem)." zł</b><br />";
		echo "Wartość odsprzedaży wyświetlonych towarów / usług (<b>$wybor</b>) na magazynie: <b>".correct_currency($kwotarazempodfaktury)." zł</b>";
		endbuttonsarea();
	}
} 
} else {
	
	$dopisek = '';
	if ($_GET[magazyn_id]!='') $dopisek = " ".$_GET[magazyn_nazwa];
		
	if ($wybor!='wszystko') {
		errorheader("Brak tego typu towaru do odsprzedaży (".$wybor.") na stanie magazynu".$dopisek."");
			if ($_GET[magazyn_id]=='') { ?>
			<meta http-equiv="REFRESH" content="<?php echo $http_refresh; ?>;url=p_towary_dostepne.php?view=<?php echo $view;?>&wybor=wszystko">
			<?php	
			}
	}
	else {
			errorheader("Brak towarów do odsprzedaży na stanie magazynu".$dopisek."");
			if ($_GET[magazyn_id]=='') {
			?>
			<meta http-equiv="REFRESH" content="<?php echo $http_refresh; ?>;url=main.php">
			<?php
			}
		}
	}
startbuttonsarea("right");
oddziel();

if ($_GET[magazyn_id]=='') {
	$accessLevels = array("1", "9");
	if(array_search($es_prawa, $accessLevels)>-1){
		echo "<span style='float:left'>";
		echo "&nbsp;Pokaż aktualny stan magazynu w lokalizacji: ";
		$query444 = "SELECT filia_skrot,filia_nazwa,filia_id FROM $dbname.serwis_filie WHERE (filia_id<>$es_filia)";
		if ($result444 = mysql_query($query444,$conn)) while (list($fskrot,$fnazwa,$fid)=mysql_fetch_array($result444)) 
		
		echo "<input title=' $fnazwa' class=buttons name=button type=button onClick=\"newWindow_r(800,600,'p_towary_dostepne.php?readonly=tak&view=".$_REQUEST[view]."&magazyn_id=".$fid."&magazyn_nazwa=".urlencode($fnazwa)."'); \" value=' ".$fskrot." '>";
		
		//addownlinkbutton("' ".$fskrot." '","button","button","newWindow_r(800,600,'p_towary_dostepne.php?readonly=tak&view=".$_REQUEST[view]."&magazyn_id=$fid&magazyn_nazwa=".urlencode($fnazwa)."')");
		echo "</span>";

	}
}

if ($_GET[magazyn_id]=='') {
	if ($view=='dozestawu') {
		//if ($_REQUEST[sortby]!='') addlinkbutton("'Domyślny widok'","p_towary_dostepne.php?view=normal");	
		addlinkbutton("'Utworzone zestawy'","z_towary_zestawy.php");
		addlinkbutton("'Towary na stanie'","p_towary_dostepne.php?view=normal&wybor=".urlencode($wybor)."");
		addlinkbutton("'Faktury niezatwierdzone'","z_faktury.php?showall=0");
		addlinkbutton("'Przeglądaj zgłoszenia'","hd_p_zgloszenia.php?showall=0&p5=BZ&sort=AD&page=1");
		addlinkbutton("'Sprzedaż w okresie'","main.php?action=pswo");
		if (($kierownik_nr==$es_nr) || ($is_dyrektor==1)) {
			addlinkbutton("'Sprzedaż w okresie'","main.php?action=pswo");
		}
		
	} else {
		addlinkbutton("'Utworzone zestawy'","z_towary_zestawy.php");	
		addlinkbutton("'Nowy zestaw'","p_towary_dostepne.php?view=dozestawu&wybor=".urlencode($wybor)."&addto=0");
		addlinkbutton("'Faktury niezatwierdzone'","z_faktury.php?showall=0");
		addlinkbutton("'Przeglądaj zgłoszenia'","hd_p_zgloszenia.php?showall=0&p5=BZ&sort=AD&page=1");
		if (($kierownik_nr==$es_nr) || ($is_dyrektor==1)) {
			addlinkbutton("'Sprzedaż w okresie'","main.php?action=pswo");
		}
	}
	echo "<br />";
	addbuttons("start");
} else addbuttons("zamknij");

endbuttonsarea();
}
include('body_stop.php');
?>
<?php if ((($count_result_wp-$zlicz)==0) || ($czy_pokazac==false) ){ ?>
<script>
document.getElementById('h3_typy').style.display='none';
//document.getElementById('typy_niepowiazane').style.display='none';
</script>
<?php } else { ?>
<script>
document.getElementById('h3_typy').style.display='';
//document.getElementById('typy_niepowiazane').style.display='';
</script>
<?php } ?>

<?php 
	
$result_wp = mysql_query("SELECT wp_id FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_sprzet_active=1) and (belongs_to=$es_filia) and (wp_typ_podzespolu<>'') and (wp_wskazanie_sprzetu_z_magazynu=0) and (wp_sprzedaz_fakt_szcz_id=0) and (wp_sprzet_pocztowy=0)",$conn);
$count_result_wp1 = mysql_num_rows($result_wp);

if ($count_result_wp1 == 0) { ?>

<script>
document.getElementById('typy_niepowiazane').style.display = document.getElementById('h3_typy').style.display;
</script>

<?php } ?>

</body>
</html>	