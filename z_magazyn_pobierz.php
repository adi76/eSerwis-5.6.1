<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php'); 
?>
<body OnLoad="document.forms[0].elements[3].focus();">
<?php 

$_SESSION[protokol_dodany_do_bazy]=0;
$_SESSION[wykonaj_magazyn_pobierz]=0;

if ($submit) { 

/*	$_POST=sanitize($_POST);
	$result55 = mysql_query("SELECT filia_nazwa FROM $dbname.serwis_filie WHERE filia_id=$es_filia", $conn) or die($k_b);
	list($filian)=mysql_fetch_array($result55);
	$dddd = Date('Y-m-d H:i:s');
	$sql_t = "INSERT INTO $dbname.serwis_historia VALUES ('','$_POST[tid]','$_POST[tup]','$_POST[tuser]','$dddd','pobrano na','".nl2br($_POST[tkomentarz])."',$es_filia)";
	if (mysql_query($sql_t, $conn)) { 
		$sql_e1b="UPDATE $dbname.serwis_magazyn SET magazyn_status = '1' WHERE magazyn_id = '$id' LIMIT 1";
		$wykonaj = mysql_query($sql_e1b, $conn) or die($k_b);
		?><script> opener.location.reload(true); self.close();  </script><?php
	} else {
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
	}
*/
?>

<?php
} else { 

function toUpper($string) {
	$string = strtr($string,'qwertyuiopasdfghjklzxcvbnm','QWERTYUIOPASDFGHJKLZXCVBNM');
	return (strtr($string, 'ęóąśłżźćń','ĘÓĄŚŁŻŹĆŃ' )); 
}; 

if ($_REQUEST[fromhd]==1) {
	$id = $_POST[WybranySS];
}

pageheader("Pobranie sprzętu serwisowego");
starttable();
echo "<form name=addt action=utworz_protokol.php method=POST>";
tbl_empty_row();
	tr_();
		td("150;r;Nazwa");
		$result3 = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni,magazyn_uwagi_sa,magazyn_uwagi FROM $dbname.serwis_magazyn WHERE magazyn_id=$id LIMIT 1", $conn) or die($k_b);
		list($mid,$mnazwa,$mmodel,$msn,$mni,$muwagi,$muwagi1)=mysql_fetch_array($result3);
		td_(";;;");
			if ($msn=='') $msn = '-';
			if ($mni=='') $mni = '-';
				
			if ($_REQUEST[submit2]=='Dalej') {
				$part=$mnazwa;
			}
		
			echo "<b>$part</b>";
			//if ($muwagi1!='') echo "<a title='Czytaj uwagi '><input class=imgoption align=absmiddle type=image src=img/comment.gif onclick=\"newWindow(480,265,'p_magazyn_uwagi.php?id=$mid'); return false; \"></a>";
		_td();
	_tr();

	tr_();
		td("150;r;Model");
		td_(";;;");
			echo "<b>$mmodel</b>";
			echo "<input type=hidden name=mmodel value='$mmodel'>";
		_td();
	_tr();
	
	tr_();
		td("150;r;Numer seryjny");
		td_(";;;");
			echo "<b>$msn</b>";
			echo "<input type=hidden name=msn value='$msn'>";
		_td();
	_tr();

	tr_();
		td("150;r;Numer inwentarzowy");
		td_(";;;");
			echo "<b>$mni</b>";
			echo "<input type=hidden name=mni value='$mni'>";
		_td();
	_tr();

	tr_();
		td("150;r;Osoba pobierająca");
		td_(";;;");
			echo "<b>$currentuser</b>";
		_td();
	_tr();

	tr_();
		td("150;rt;Miejsce docelowe");
		td_(";;;");
		
		if (($_REQUEST[new_upid]>0) && ($_REQUEST[from]=='hd')) {
			echo "<input type=hidden id=new_upid name=new_upid value='$_REQUEST[new_upid]'>";
			echo "<b>$_REQUEST[tup]</b>";

		} else {
			$sql6="SELECT * FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) ORDER BY serwis_piony.pion_nazwa,up_nazwa";
			$result6 = mysql_query($sql6, $conn) or die($k_b);
			
			echo "<select class=wymagane name=new_upid onChange=\"reload1_obrot(this.form); \">\n"; 					 				
			echo "<option value=''>Wybierz z listy...";
			while ($newArray6 = mysql_fetch_array($result6)) { 
				$temp_id = $newArray6[up_id];
				$temp_nazwa = $newArray6[up_nazwa];
				$temp_pion = $newArray6[pion_nazwa];
				
				echo "<option value='$temp_id'";
				if ($temp_id==$_REQUEST[new_upid]) { echo " SELECTED"; $nazwawybranejkomorki = $temp_pion." ".$temp_nazwa; }
				echo ">$temp_pion $temp_nazwa</option>\n"; 
			}
			echo "</select>\n";
		}
		
		if ($_REQUEST[new_upid]>0) {			
			echo "<br /><br /><input type=button class=buttons style='color:blue' value='Pokaż zgłoszenia z wybranej komórki' onClick=\"newWindow_r(800,600,'hd_p_zgloszenia_dla_komorki.php?randval=".date('Ymdhim')."".rand()."&komorka=".urlencode($nazwawybranejkomorki)."&showall=0&id=".$_REQUEST[id]."&part=".urlencode($_REQUEST[part])."&new_upid=".$_REQUEST[new_upid]."&hd_zgl_nr=".$_REQUEST[hd_zgl_nr]."&powiazzhd=".$_REQUEST[powiazzhd]."&tkomentarz=".urlencode($_REQUEST[tkomentarz])."&file=".urlencode(basename($PHP_SELF))."'); return false; \" />";
		}
		
		_td();
	_tr();
		
	tr_();
		td("150;rt;Uwagi");
		td_(";;;");
			echo "<textarea name=tkomentarz cols=43 rows=6>";
			if ($tkomentarz!='') echo cleanup(cleanup($tkomentarz));
			echo "</textarea>";
		_td();
	_tr();

	echo "<tr ";
//	if (($_REQUEST[hd_zgl_nr]!='') && ($_REQUEST[hd_zgl_nr]!='0')) {
//		echo " style='display:' ";
//	} else {
//		echo " style='display:none' ";
//	}
	echo ">";
		$nowe_pola = 0;
		td("150;r;Nr zgłoszenia Helpdesk");
		td_(";;");	
			
		//	if (($_REQUEST[hd_zgl_nr]=='') || ($_REQUEST[hd_zgl_nr]=='0')) echo "<input class=wymagane type=text id=hd_zgl_nr name=hd_zgl_nr value='' size=10 maxlength=10>";
			
		//	if ($_REQUEST[hd_zgl_nr]>0) {
				//echo "<input type=hidden name=hd_zgl_nr value='$_REQUEST[hd_zgl_nr]'>";
				//echo "<b>$_REQUEST[hd_zgl_nr]</b>";
				if ($_REQUEST[powiazzhd]=='1') {
					echo "<input class=wymagane type=text id=hd_zgl_nr name=hd_zgl_nr value='$_REQUEST[hd_zgl_nr]' size=10 maxlength=10 onchange=\"reload1_obrot(this.form); this.focus(); return false;\" \" onBlur=\"reload1_obrot(this.form); this.focus(); return false;\" onKeyUp=\"if (event.keyCode==13) reload1_obrot(this.form); this.focus(); return false;\"/>";
				} else {
					echo "<b>$_REQUEST[hd_zgl_nr]</b>";
					echo "<input type=hidden id=hd_zgl_nr name=hd_zgl_nr value='$_REQUEST[hd_zgl_nr]'>";
				}
				
				$block = 1;
				if ($_REQUEST[hd_zgl_nr]>0) {
					//echo "SELECT zgl_id FROM $dbname_hd.hd_zgloszenie WHERE (zgl_nr='$_REQUEST[hd_zgl_nr]') and (belongs_to=$es_filia) and (zgl_widoczne=1) LIMIT 1";
					$r4 = mysql_query("SELECT zgl_id, zgl_komorka,zgl_kategoria,zgl_podkategoria,zgl_status FROM $dbname_hd.hd_zgloszenie WHERE (zgl_nr='$_REQUEST[hd_zgl_nr]') and (belongs_to=$es_filia) and (zgl_widoczne=1) LIMIT 1", $conn_hd) or die($k_b);
					list($z_id,$temp_komorka,$temp_kategoria,$temp_podkategoria,$temp_status)=mysql_fetch_array($r4);
					if ($z_id==$_REQUEST[hd_zgl_nr]) $block=0;
				}
				
				
		//	} else {
			//	echo "<input class=wymagane type=text id=hd_zgl_nr  name=hd_zgl_nr value='' size=10 maxlength=10>";
		//	}
			if ($block==0) echo "&nbsp;&nbsp;<input type=button class=buttons value='Szczegóły zgłoszenia' onClick=\"PokazZgloszenie(document.getElementById('hd_zgl_nr').value); return false; \">";
			
			if ($_REQUEST[hd_zgl_nr]=='') $block = 9;
			if ($_REQUEST[new_upid]=='') $block = 8;
			
			
		_td();
	_tr();

	if ($block==0) {
		echo "<tr>";
			echo "<td></td>";
			echo "<td>";
					$r1 = mysql_query("SELECT hd_kategoria_opis FROM $dbname_hd.hd_kategoria WHERE (hd_kategoria_nr='$temp_kategoria') LIMIT 1", $conn_hd) or die($k_b);
					list($kat_opis)=mysql_fetch_array($r1);
					$r2 = mysql_query("SELECT hd_podkategoria_opis FROM $dbname_hd.hd_podkategoria WHERE (hd_podkategoria_nr='$temp_podkategoria') LIMIT 1", $conn_hd) or die($k_b);
					list($podkat_opis)=mysql_fetch_array($r2);
					$r2 = mysql_query("SELECT hd_status_opis FROM $dbname_hd.hd_status WHERE (hd_status_nr='$temp_status') LIMIT 1", $conn_hd) or die($k_b);
					list($status_opis)=mysql_fetch_array($r2);
					
					okheader("<font style='font-weight:normal;'>".$temp_komorka."<br />".$kat_opis." -> ".$podkat_opis."<br />Status zgłoszenia: <b>".$status_opis."</b></font>");
					
					if ($_REQUEST[new_upid]!='') {
						
						$sql445="SELECT up_nazwa, up_pion_id FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) and (serwis_komorki.up_id = $_REQUEST[new_upid]) LIMIT 1";
						$result445 = mysql_query($sql445, $conn) or die($k_b);
						
						while ($newArray445 = mysql_fetch_array($result445)) {
							
							$temp_nazwa				= $newArray445['up_nazwa'];
							$temp_pion_id			= $newArray445['up_pion_id'];
							
							$sql444="SELECT pion_nazwa FROM $dbname.serwis_piony WHERE pion_id=$temp_pion_id LIMIT 1";
							$result444=mysql_query($sql444,$conn) or die($k_b);
							$wynik = mysql_fetch_array($result444);
							$pionnazwa = $wynik['pion_nazwa'];
							
							$komorka_wybrana = toUpper($pionnazwa." ".$temp_nazwa);
						}				
					} else {						
						$komorka_wybrana = '';
						$block = 8;
					}
					//echo "|".toUpper($temp_komorka)."|".trim($komorka_wybrana)."|".$temp_komorka."|";
					if (((toUpper($temp_komorka)!=trim($komorka_wybrana)) && (($temp_komorka!=trim($komorka_wybrana))))) { 
						errorheader("<font style='font-weight:normal;'>Komórka ze zgłoszenia nr $_REQUEST[hd_zgl_nr] jest niezgodna z tą dla której chcesz wykonać sprzedaż</font>"); 
						$block=2;
					} else {
						$block = 0;
					}
					
					// jeżeli kategoria = konsultacje - nie pozwól przekazać sprzętu
					if ($temp_kategoria==1) {
						$block = 6;
					}
					
					if (($mnazwa=='Drukarka') && (($temp_podkategoria=='2') || ($temp_podkategoria=='3') || ($temp_podkategoria=='8') || ($temp_podkategoria=='0') || ($temp_podkategoria=='A') || ($temp_podkategoria=='B') || ($temp_podkategoria=='C') || ($temp_podkategoria=='E'))) {
						$block = 7;
					}
					
					if (($mnazwa!='Drukarka') && (($temp_podkategoria=='9') || ($temp_podkategoria=='8') || ($temp_podkategoria=='0') || ($temp_podkategoria=='A') || ($temp_podkategoria=='B') || ($temp_podkategoria=='C') || ($temp_podkategoria=='E'))) {
						$block = 7;
					}
					//echo "SELECT naprawa_id_sprzetu_z_magazynu FROM $dbname.serwis_naprawa WHERE (naprawa_hd_zgl_id='$_REQUEST[hd_zgl_nr]') LIMIT 1";
					$r1 = mysql_query("SELECT naprawa_sprzet_zastepczy_id FROM $dbname.serwis_naprawa WHERE (naprawa_hd_zgl_id='$_REQUEST[hd_zgl_nr]') LIMIT 1", $conn_hd) or die($k_b);
					list($czy_naprawa_jest_pow_ze_sprzetem_serwisowym)=mysql_fetch_array($r1);					
					if ($czy_naprawa_jest_pow_ze_sprzetem_serwisowym>0) $block = 5;

			echo "</td>";
		_tr();
	}

	if (($block==7) && ($_REQUEST[hd_zgl_nr]!='')) {
		echo "<tr>";
			echo "<td></td>";
			echo "<td>";
				errorheader('Typ przekazywanego sprzętu <font color=white>'.$mnazwa.'</font> jest niezgodny z podkategorią zgłoszenia <font color=white>'.$podkat_opis.'</font>');
				$block=0;
			echo "</td>";
		_tr();
	}
	
	if (($block==6) && ($_REQUEST[hd_zgl_nr]!='')) {
		echo "<tr>";
			echo "<td></td>";
			echo "<td>";
				errorheader('Dla kategorii zgłoszenia nr '.$_REQUEST[hd_zgl_nr].': <font color=white>konsultacje</font>, nie można pobrać sprzętu serwisowego.');
			echo "</td>";
		_tr();
	}

	if ($block==8) {
		echo "<tr>";
			echo "<td></td>";
			echo "<td>";
				errorheader('Nie wybrałeś komórki z listy');
			echo "</td>";
		_tr();
	}

	if ($block==9) {
		echo "<tr>";
			echo "<td></td>";
			echo "<td>";
				errorheader('Nie podano numeru zgłoszenia');
			echo "</td>";
		_tr();
	}
	
	if (($block==1) && ($_REQUEST[hd_zgl_nr]!='')) {
		echo "<tr>";
			echo "<td></td>";
			echo "<td>";
				errorheader('Zgłoszenie o podanym numerze nie istnieje lub jest przypisane do innej filii/oddziału');
			echo "</td>";
		_tr();
	}
	
	if (($block==5) && ($_REQUEST[hd_zgl_nr]!='')) {
		echo "<tr>";
			echo "<td></td>";
			echo "<td>";
				errorheader('Zgłoszenie o podanym numerze jest powiązane z naprawą, która to powiązana jest z podstawieniem sprzętu serwisowego.');
			echo "</td>";
		_tr();
	}
	
	if ($_REQUEST[hd_zgl_nr]>0) {
		$r1 = mysql_query("SELECT zgl_naprawa_id, zgl_sprzet_serwisowy_id FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id='$_REQUEST[hd_zgl_nr]') LIMIT 1", $conn_hd) or die($k_b);
		list($temp_zgl_naprawaid, $temp_zgl_ssid)=mysql_fetch_array($r1);
		
		if ($temp_zgl_ssid>0) {
			echo "<tr>";
				echo "<td></td>";
				echo "<td>";
					infoheader('Ze zgłoszeniem nr '.$_REQUEST[hd_zgl_nr].' jest już związany inny sprzęt serwisowy');
					$block = 1;
				echo "</td>";
			_tr();			
		}
		
		if ($temp_zgl_naprawaid>0) $_REQUEST[naprawaid]=$temp_zgl_naprawaid;
	}
	
	if (($_REQUEST[naprawaid]>0) && ($block!=5)) {
		echo "<tr>";
			echo "<td></td>";
			echo "<td>";
			
				$result99 = mysql_query("SELECT * FROM $dbname.serwis_naprawa WHERE naprawa_id='$_REQUEST[naprawaid]' LIMIT 1");
				while ($dane99 = mysql_fetch_array($result99)) {
					
					$mnazwa5 	= $dane99['naprawa_nazwa'];
					$mmodel5	= $dane99['naprawa_model'];			
					$msn5 		= $dane99['naprawa_sn'];
					$mni5 		= $dane99['naprawa_ni'];					
					$msz5		= $dane99['naprawa_sprzet_zastepczy_id'];
				}				
				
				$ook = 1;
				if (($mnazwa=='Serwer') || ($mnazwa=='Komputer') || ($mnazwa=='Notebook')) $ook = 2;

				if (($mnazwa!=$mnazwa5) && ($ook==1)) {
					errorheader("Wybrany typ sprzętu serwisowego (<font color=white>$mnazwa</font>) jest niezgodny z typem sprzetu związanego z naprawą (<font color=white>$mnazwa5</font>)");
					$block = 1;					
				} else {
					infoheader("Wybrany sprzęt serwisowy zostanie powiązany z naprawą:<br /><br /><b>Sprzęt uszkodzony</b>: ".$mnazwa5." ".$mmodel5." | SN: ".$msn5." | NI: ".$mni5."");
				}
				
			echo "</td>";
		_tr();	
	}
	
	echo "<input type=hidden name=tid value=$id>";
	echo "<input type=hidden name=tstatus value='1'>";
	echo "<input type=hidden name=id value='$id'>";
	echo "<input type=hidden name=part value='$part'>";
	echo "<input type=hidden name=tuser value='$currentuser'>";
	echo "<input type=hidden name=source value='magazyn-pobierz'>";
	echo "<input type=hidden name=findpion value=1>";
	echo "<input type=hidden name=state value='empty'>";
	echo "<input type=hidden name=c_2 value='on'>";
	echo "<input type=hidden name=wstecz value='1'>";
	//echo "<input type=hidden name=hd_zgl_nr value='0'>";
	echo "<input type=hidden name=powiazzhd value='$_REQUEST[powiazzhd]'>";
	echo "<input type=hidden name=naprawaid value='$_REQUEST[naprawaid]'>";
	echo "<input type=hidden name=tup value='$_REQUEST[tup]'>";
	echo "<input type=hidden name=from value='$_REQUEST[from]'>";

	
tbl_empty_row();
endtable();
startbuttonsarea("right");

if ($_REQUEST[from]!='hd') {
	infoheader("<font color=red>Aby przekazanie sprzętu serwisowego było widoczne w krokach zgłoszenia, wykonaj je z poziomu obsługi zgłoszenia</font>");
}

if ($block==0) {
	addbuttons("dalej");
}
addbuttons("anuluj");
endbuttonsarea();
_form();
?>
<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("addt");
  frmvalidator.addValidation("new_upid","req","Nie wybrałeś miejsca docelowego");
</script>
<?php } ?>
</body>
</html>