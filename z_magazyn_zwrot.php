<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php'); 
?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 

$_SESSION[protokol_dodany_do_bazy]=0;
$_SESSION[wykonaj_magazyn_zwrot]=0;

if ($submit) { 
/*
$_POST=sanitize($_POST);
$sql55="SELECT * FROM $dbname.serwis_filie WHERE filia_id=$es_filia";
$result55 = mysql_query($sql55, $conn) or die($k_b);
$dane55 = mysql_fetch_array($result55);
$filian = $dane55['filia_nazwa'];

if (2==2) {

$dddd = Date('Y-m-d H:i:s');

$sql_t = "INSERT INTO $dbname.serwis_historia VALUES ('','$_POST[tid]','$_POST[tup]','$_POST[tuser]','$dddd','zwrócono z','".nl2br($_POST[tkomentarz])."',$es_filia)";

if (mysql_query($sql_t, $conn)) { 

	$sql_t1 = "UPDATE $dbname.serwis_magazyn SET magazyn_status='0' WHERE magazyn_id='$_POST[tid]' LIMIT 1";
	$wykonaj = mysql_query($sql_t1, $conn) or die($k_b);
		?><script>opener.location.reload(true); self.close();  </script><?php
} else 
	{
	  ?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php	
		}
} else
     {
	?><script>info('Nie wypełniłeś wymaganych pól'); window.history.go(-1); </script><?php	 
	 }
*/
} else { ?>
	
<?php

function toUpper($string) {
	$string = strtr($string,'qwertyuiopasdfghjklzxcvbnm','QWERTYUIOPASDFGHJKLZXCVBNM');
	return (strtr($string, 'ęóąśłżźćń','ĘÓĄŚŁŻŹĆŃ' )); 
}; 

pageheader("Zwrot sprzętu do magazynu");
starttable();
echo "<form name=addt action=utworz_protokol.php method=POST>";	
	tbl_empty_row();

		echo "<tr>";
		echo "<td width=150 class=right>Nazwa</td>";

		$sql3 = "SELECT * FROM $dbname.serwis_magazyn WHERE magazyn_id=$id";
		$result3 = mysql_query($sql3, $conn) or die($k_b);

		while ($dane3 = mysql_fetch_array($result3)) {	
		  $mid 		= $dane3['magazyn_id'];
		  $mnazwa 	= $dane3['magazyn_nazwa'];
		  $mmodel	= $dane3['magazyn_model'];
		  $msn	 	= $dane3['magazyn_sn'];
		  $mni		= $dane3['magazyn_ni'];
		  $muwagi	= $dane3['magazyn_uwagi_sa'];
		}

		if ($_REQUEST[from]=='hd') {
			$part=$mnazwa;
			if ($msn=='') $msn = '-';
			if ($mni=='') $mni = '-';
		}
			
		echo "<td><b>$part</b></td>";

		
	echo "</tr>";
	
	echo "<tr>";
		echo "<td width=150 class=right>Model</td>";
		echo "<td><b>$mmodel</b></td>";
	echo "</tr>";

	echo "<tr>";
		echo "<td width=150 class=right>Numer seryjny</td>";
		echo "<td><b>$msn</b></td>";
	echo "</tr>";

	echo "<tr>";
		echo "<td width=150 class=right>Numer inwentarzowy</td>";
		echo "<td><b>$mni</b></td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td width=150 class=right>Osoba dokonująca zwrotu</td>";
		echo "<td width=200 class=left><b>$currentuser</b></td>";

	echo "</tr>";

	echo "<tr>";
		echo "<td width=150 class=righttop>Sprzęt powrócił z</td>";
		echo "<td><b>";

		$sql_a = "SELECT * FROM $dbname.serwis_historia WHERE (historia_magid=$id) and (belongs_to=$es_filia) ORDER BY historia_id DESC LIMIT 1";
		$result_a = mysql_query($sql_a, $conn) or die($k_b);
		
		while ($dane3 = mysql_fetch_array($result_a)) {
		
			$hid 		= $dane3['historia_id'];
			$hmagin 	= $dane3['historia_magid'];
			$hup		= $dane3['historia_up'];
			$huser		= $dane3['historia_user'];
			$hdata		= $dane3['historia_data'];
			$hrs		= $dane3['historia_ruchsprzetu'];
			$hkomentarz	= $dane3['historia_komentarz'];
			
			$sql21 = "SELECT up_id, up_nazwa, pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.up_nazwa = '$hup') and (serwis_piony.pion_id=serwis_komorki.up_pion_id) and (belongs_to=$es_filia) LIMIT 1";
					
			$wynik21 = mysql_query($sql21,$conn);
			list($upid,$temp_nazwa, $temp_pion) = mysql_fetch_array($wynik21);
			
			$nazwawybranejkomorki = $temp_pion." ".$temp_nazwa;
			
			echo "<input type=hidden name=new_upid value=$upid>";
		
		}
		
		echo "$temp_pion $hup";

		echo "</b>";
		
		if ($_REQUEST[powiazzhd]==1) {
			if ($upid>0) {			
				echo "<br /><br /><input type=button class=buttons style='color:blue' value='Pokaż zgłoszenia z wybranej komórki' onClick=\"newWindow_r(800,600,'hd_p_zgloszenia_dla_komorki.php?randval=".date('Ymdhim')."".rand()."&komorka=".urlencode($nazwawybranejkomorki)."&showall=0&id=".$_REQUEST[id]."&part=".urlencode($_REQUEST[part])."&new_upid=".$_REQUEST[new_upid]."&hd_zgl_nr=".$_REQUEST[hd_zgl_nr]."&powiazzhd=".$_REQUEST[powiazzhd]."&tkomentarz=".urlencode($_REQUEST[tkomentarz])."&file=".urlencode(basename($PHP_SELF))."'); return false; \" />";
			}		
		}
		
		echo "</td>";
	echo "</tr>";

	echo "<tr>";
		echo "<td width=150 class=right>Sprzęt pobrano</td>";
		echo "<td><b>".substr($hdata,0,16)."</b></td>";
	echo "</tr>";

//  dopisać wybór UP docelowego

	echo "<tr>";
		echo "<td width=150 class=righttop>Komentarz</td>";
		echo "<td><textarea name=tkomentarz cols=37 rows=6>";
		if ($tkomentarz!='') echo cleanup(cleanup($tkomentarz));
		echo "</textarea></td>";
	echo "</tr>";
	
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
					echo "<input class=wymagane type=text id=hd_zgl_nr name=hd_zgl_nr value='$_REQUEST[hd_zgl_nr]' size=10 maxlength=10 onchange=\"reload1_obrot(this.form); this.focus();\" onKeyPress=\"return filterInput(1, event, false); \">";
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
				
		//	} else {
			//	echo "<input class=wymagane type=text id=hd_zgl_nr  name=hd_zgl_nr value='' size=10 maxlength=10>";
		//	}
			if ($block==0) echo "&nbsp;&nbsp;<input type=button class=buttons value='Szczegóły zgłoszenia' onClick=\"PokazZgloszenie(document.getElementById('hd_zgl_nr').value); \">";
		
			if ($_REQUEST[hd_zgl_nr]=='') $block = 9;
			
			
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
					
					$komorka_wybrana = toUpper($temp_pion." ".$hup);
					
					if (($temp_komorka!=$komorka_wybrana) && ($komorka_wybrana!='')) { 
						errorheader("<font style='font-weight:normal;'>Komórka ze zgłoszenia nr $_REQUEST[hd_zgl_nr] jest niezgodna z tą dla której chcesz wykonać sprzedaż</font>"); 
						$block=2;
					} else {
						$block = 0;
					}
					
			echo "</td>";
		_tr();
	}
	
	if (($block==7) && ($_REQUEST[hd_zgl_nr]!='')) {
		echo "<tr>";
			echo "<td></td>";
			echo "<td>";
				errorheader('Typ przekazywanego sprzętu <font color=white>'.$mnazwa.'</font> jest niezgodny z podkategorią zgłoszenia <font color=white>'.$podkat_opis.'</font>');
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
	
	
	tbl_empty_row();
	endtable();

	echo "<input type=hidden name=tstatus value='1'>";
	echo "<input type=hidden name=tup value='$hup'>";
	echo "<input type=hidden name=tid value=$id>";		
	echo "<input type=hidden name=tuser value='$currentuser'>";

	echo "<input type=hidden name=tstatus value='1'>";
	echo "<input type=hidden name=id value='$id'>";
	echo "<input type=hidden name=part value='$part'>";
	echo "<input type=hidden name=tuser value='$currentuser'>";
	echo "<input type=hidden name=source value='magazyn-zwrot'>";
	echo "<input type=hidden name=findpion value=1>";
	echo "<input type=hidden name=state value='empty'>";
	echo "<input type=hidden name=c_4 value='on'>";
	echo "<input type=hidden name=wstecz value='1'>";

	echo "<input type=hidden name=mmodel value='$mmodel'>";	
	echo "<input type=hidden name=msn value='$msn'>";
	echo "<input type=hidden name=mni value='$mni'>";
	echo "<input type=hidden name=hdata value='$hdata'>";
	echo "<input type=hidden name=from value='$_REQUEST[from]'>";
	
	echo "<input type=hidden name=powiazzhd value='$_REQUEST[powiazzhd]'>";
	
	startbuttonsarea("right");
	

	if ($_REQUEST[info]!='0') {
		infoheader("<font color=red>Aby zwrot sprzętu serwisowego był widoczny w krokach zgłoszenia, wykonaj je z poziomu obsługi zgłoszenia</font>");
	}	
	
	if ($block==0) {
		addbuttons("dalej");
	}

	addbuttons("anuluj");
	endbuttonsarea();
	
	_form();
}
?>
</body>
</html>