<?php include_once('header.php'); ?>
<?php //include_once('header.php'); ?>
<body onLoad="document.forms[0].elements[0].focus();">
<?php 
include('inc_encrypt.php');
if ($submit) { 

?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();

//$width = "<script>document.write((screen.width-300)/2); </script>"; 
//$height = "<script>document.write((screen.height-20)/2); </script>"; 

$wersjaprosta=0;
//if ($submit=='Generuj raport (alternatywnie)') $wersjaprosta=1;

$unique=rand(0,999999);
$sql99="TRUNCATE TABLE serwis_temp_m1$es_filia";
$result99=mysql_query($sql99,$conn);

$sql99="TRUNCATE TABLE serwis_temp_m2$es_filia";
$result99=mysql_query($sql99,$conn);

	// sprawdzenie czy zatwierdzono raport na za wybrany okres 

	$m_1 = intval($_POST[m_i_e_s_i_a_c]);
	$r_1 = intval($_POST[r_o_k]);
	
	$testsql = "SELECT * FROM $dbname.serwis_sprzedaz_raport WHERE ((belongs_to=$es_filia) and (sr_miesiac=$m_1) and (sr_rok=$r_1))";
	$testresult = mysql_query($testsql, $conn);
	$pokazrapzesprz=mysql_num_rows($testresult);
	
	// koniec sprawdzania
	

if ($_POST['nadzien']!='') {

//$sql_a = "SELECT * FROM $dbname.serwis_faktura_szcz WHERE belongs_to=$es_filia and pozycja_status='0' and pozycja_datasprzedazy='0000-00-00'";
$sql_a = "SELECT * FROM $dbname.serwis_faktura_szcz";

$result_a = mysql_query($sql_a, $conn) or die($k_b);
//$dane3 = mysql_fetch_array($result_a);

if (mysql_num_rows($result_a)>0) { 

$sqlf="SELECT * FROM $dbname.serwis_filie WHERE filia_id=$es_filia";
$resultf = mysql_query($sqlf, $conn) or die($k_b);
$dane1f = mysql_fetch_array($resultf);
$filian = $dane1f['filia_nazwa'];

$i = 0;

// sprawdzenie czy są pozycje do wyświetlenia
$sql1="SELECT * FROM $dbname.serwis_faktury WHERE ((faktura_data<='$_POST[nadzien]')) ORDER BY faktura_data ASC, faktura_numer ASC";
$result1 = mysql_query($sql1, $conn) or die($k_b);
$czy_sa = 0;

while ($newArray4 = mysql_fetch_array($result1)) {
	$temp_id4	= $newArray4['faktura_id'];
	
	$sql_poz="SELECT COUNT(*) FROM $dbname.serwis_faktura_szcz WHERE ((belongs_to=$es_filia) and (pozycja_nr_faktury=$temp_id4) and ((pozycja_datasprzedazy='0000-00-00') or (pozycja_datasprzedazy>'$_POST[nadzien]')) ) ORDER BY pozycja_nazwa ASC";
	$result_poz = mysql_query($sql_poz, $conn) or die($k_b);
	$count_rows = mysql_num_rows($result_poz);
	
	if ($count_rows>0) {
		$czy_sa = 1;
		break;
	}
//	while ($newArrayp = mysql_fetch_array($result_poz)) {
//		$czy_sa++;
//	}
}

$sql1="SELECT * FROM $dbname.serwis_faktury WHERE ((faktura_data<='$_POST[nadzien]')) ORDER BY faktura_data ASC, faktura_numer ASC";
//$sql1="SELECT * FROM $dbname.serwis_faktury ORDER BY faktura_data ASC, faktura_numer ASC";
$result1 = mysql_query($sql1, $conn) or die($k_b);

if ($czy_sa>0) {
	if ($wersjaprosta==0) pageheader("Stan magazynu na dzień <b>".$_POST[nadzien]."</b>",1,1);
	ob_flush();	
	flush();

	echo "<table cellspacing=1 class=maxwidth>";
	echo "<tr_n>";
	echo "<th width=30 class=center>LP</th><th>Data zakupu</th><th>Nr faktury</th><th>Dostawca</th><th>Nazwa towaru / usługi<br />Numer seryjny</th><th class=center>Ilość</th><th class=right>Cena zakupu</th><th class=right>Cena odsprzedaży</th><th class=center>Zlecenie fakturowania</th><th>Nr zlecenia</th><th class=center>Filia</th><th class=center>Realizacja zakupu</th><th class=center>Rodzaj sprzedaży</th>";
	echo "</tr>";
}
	while ($newArray4 = mysql_fetch_array($result1)) 
	{ // while 1
		$temp_id4  			= $newArray4['faktura_id'];
		$temp_numer4		= $newArray4['faktura_numer'];
		$temp_data4			= $newArray4['faktura_data'];
		$temp_dostawca4		= $newArray4['faktura_dostawca'];	
		$temp_realizacja4	= $newArray4['faktura_realizacjazakupu'];
	
			//$sql_poz="SELECT * FROM $dbname.serwis_faktura_szcz WHERE ((belongs_to=$es_filia) and ((pozycja_status=0) or (pozycja_status=5)) and (pozycja_nr_faktury=$temp_id4)) ORDER BY pozycja_nazwa ASC";
			
			$sql_poz="SELECT * FROM $dbname.serwis_faktura_szcz WHERE ((belongs_to=$es_filia) and (pozycja_nr_faktury=$temp_id4) and ((pozycja_datasprzedazy='0000-00-00') or (pozycja_datasprzedazy>'$_POST[nadzien]')) and (pozycja_status!='-2')) ORDER BY pozycja_nazwa ASC";
			$result_poz = mysql_query($sql_poz, $conn) or die($k_b);
	
			while ($newArrayp = mysql_fetch_array($result_poz)) {	
		
				$temp_id  			= $newArrayp['pozycja_id'];
				$temp_nrfaktury		= $newArrayp['pozycja_nr_faktury'];
				$temp_numer			= $newArrayp['pozycja_numer'];
				$temp_nazwa			= $newArrayp['pozycja_nazwa'];
				$temp_ilosc			= $newArrayp['pozycja_ilosc'];
				$temp_sn			= $newArrayp['pozycja_sn'];
				$temp_cenanetto_cr	= $newArrayp['pozycja_cena_netto'];
				$temp_status		= $newArrayp['pozycja_status'];
				$temp_dolicz_koszty	= $newArrayp['pozycja_dolicz_koszty'];
				$temp_cenanettoodsp_cr	= $newArrayp['pozycja_cena_netto_odsprzedazy'];
	
				$temp_cenanetto 	= decrypt_md5($temp_cenanetto_cr,$key);
				$temp_cenanettoodsp = decrypt_md5($temp_cenanettoodsp_cr,$key);
				
				$i++;
				
				tbl_tr_highlight($i);
					if ($temp_sn=='') $temp_sn='-';
					
					//echo "<td class=center width=30>$i</td>";
					td("30;c;<a class=normalfont href=# title=' $temp_id '>".$i."</a>");
					echo "<td>$temp_data4</td>";
					echo "<td>$temp_numer4</td>";
					echo "<td>$temp_dostawca4</td>";
					echo "<td>$temp_nazwa<br /><font color=grey>$temp_sn</font></td>";
					echo "<td class=center>1</td>";
					echo "<td class=right>".correct_currency($temp_cenanetto)." zł</td>";
					echo "<td class=right></td>";
//					echo "<td class=right>".correct_currency($temp_cenanettoodsp)." zł</td>";
					echo "<td class=center>na magazynie</td>";
					echo "<td>&nbsp</td>";
					echo "<td class=center>$filian</td>";	
					echo "<td class=center>$temp_realizacja4</td>";									
					echo "<td>&nbsp</td>";
	
	
					$datedate1=strtotime($temp_data4.' 00:00:00');	
					$datedate=date('Y-m-d', $datedate1);
					
					$cena = str_replace('.',',',$temp_cenanetto);
					$cena2 = str_replace('.',',',$temp_cenanettoodsp);					

					$sql99="INSERT INTO $dbname.serwis_temp_m1$es_filia VALUES ('$datedate','$temp_numer4','$temp_dostawca4','$temp_nazwa','1','$cena ','','na magazynie','','$filian','$temp_realizacja4','','$unique')";	
					$result99=mysql_query($sql99,$conn) or die($k_b);					

					echo "</tr>";

			} // end while 2
		
	} // end while 1
	if ($i==0) {
		errorheader("Brak towarów / usług na stanie");
	}

if ($wersjaprosta==0) {	
	endtable();
	//echo "<br />";
}

} else { 
	errorheader("Brak towarów / usług na stanie");
/*	startbuttonsarea("right");
	startbuttonsarea("right");
	addownlinkbutton("'Zmień kryteria'","button","button","window.history.go(-1);");	
	addbuttons("start");
	endbuttonsarea();
*/	
	//exit;
}
if ($_POST[addsell]!="on") { 
	startbuttonsarea("right");
	
	echo "<span style='float:left'>";
	addbackbutton("Zmień kryteria");
	echo "</span>";
	
	addbuttons("start");
	endbuttonsarea();
}
}

if ($_POST[addsell]=="on") { 

	$m_1 = intval($_POST[m_i_e_s_i_a_c]);
	$r_1 = intval($_POST[r_o_k]);
	
	$testsql = "SELECT * FROM $dbname.serwis_sprzedaz_raport WHERE ((belongs_to=$es_filia) and (sr_miesiac=$m_1) and (sr_rok=$r_1))";
	$testresult = mysql_query($testsql, $conn);
	$pokazrapzesprz=mysql_num_rows($testresult);
	
	//echo "$pokazrapzesprz | $_POST[m_i_e_s_i_a_c] | $_POST[r_o_k] | $_POST[tumowa]";
	
	if (($_POST[m_i_e_s_i_a_c]!='') && ($_POST[r_o_k]!='') && ($_POST[tumowa]!='') && ($pokazrapzesprz>0)) {
	
		if ($wersjaprosta==0) {
			pageheader("Raport ze sprzedaży za ".$_POST[m_i_e_s_i_a_c]."-".$_POST[r_o_k]."");
		}

		$obc=''.$_POST[m_i_e_s_i_a_c].'/'.$_POST[r_o_k];
		
		$obc1=''.$_POST[m_i_e_s_i_a_c].'_'.$_POST[r_o_k];
		
if ($wersjaprosta==0) {	
		echo "<table cellspacing=1 class=maxwidth>";
		echo "<tr>";
		echo "<th width=30 class=center>LP</th><th>Data zakupu</th><th>Nr faktury</th><th>Dostawca</th><th>Nazwa towaru / usługi</th><th class=center>Ilość</th><th class=right>Cena zakupu</th><th class=right>Cena odsprzedaży</th><th class=center>Zlecenie fakturowania</th><th class=center>Nr zlecenia</th><th class=center>Filia</th><th class=center>Realizacja zakupu</th><th class=center>Rodzaj sprzedaży</th>";
		echo "</tr>";
}
		$mmm=$_POST[m_i_e_s_i_a_c];
		$rrr=$_POST[r_o_k];

		if ($mmm<12) { $mm1=$mmm+1; } else $mm1='01';
		
		//$mm1=$mmm+1;		
		
	if ($mmm==1) $d2=31;
	if ($mmm==2) $d2=29;
	if ($mmm==3) $d2=31;
	if ($mmm==4) $d2=30;
	if ($mmm==5) $d2=31;
	if ($mmm==6) $d2=30;
	if ($mmm==7) $d2=31;
	if ($mmm==8) $d2=31;
	if ($mmm==9) $d2=30;
	if ($mmm==10) $d2=31;
	if ($mmm==11) $d2=30;
	if ($mmm==12) $d2=31;
	
	if (strlen($mmm)==1) $mmm='0'.$mmm;
	
	$dataod=$rrr.'-'.$mmm.'-01';
	$datado=$rrr.'-'.$mmm.'-'.$d2;

		if ($_POST[tumowa]='all') {
			$sql = "SELECT * FROM $dbname.serwis_sprzedaz,serwis_faktury WHERE (serwis_faktury.faktura_id=serwis_sprzedaz.sprzedaz_faktura_id) and  (serwis_sprzedaz.belongs_to=$es_filia) and (sprzedaz_data BETWEEN '$dataod' AND '$datado') ORDER BY serwis_faktury.faktura_data ASC, serwis_faktury.faktura_numer ASC, serwis_sprzedaz.sprzedaz_pozycja_nazwa ASC";	
		} else { 
			$sql = "SELECT * FROM $dbname.serwis_sprzedaz,serwis_faktury WHERE (serwis_faktury.faktura_id=serwis_sprzedaz.sprzedaz_faktura_id) and (serwis_sprzedaz.belongs_to=$es_filia) and (sprzedaz_umowa_nazwa='$_POST[tumowa]') and (sprzedaz_data BETWEEN '$dataod' AND '$datado') ORDER BY serwis_faktury.faktura_data ASC, serwis_faktury.faktura_numer ASC, serwis_sprzedaz.sprzedaz_pozycja_nazwa ASC";
		}	
		
		//echo $sql;
		$result = mysql_query($sql, $conn) or die($k_b);

	
		
	if ($wersjaprosta==0) {		
		$i = 0;
	}
		$j = 1000;
			
		while ($newArray = mysql_fetch_array($result)) {
			$temp_id  			= $newArray['sprzedaz_id'];
			$temp_poz_nazwa		= $newArray['sprzedaz_pozycja_nazwa'];
			$temp_poz_sn		= $newArray['sprzedaz_pozycja_sn'];
			$temp_poz_cena_cr	= $newArray['sprzedaz_pozycja_cenanetto'];
			$temp_poz_cena_twoja_cr= $newArray['sprzedaz_pozycja_cenatwoja']; 

			$temp_poz_cenaodsp_cr	= $newArray['sprzedaz_pozycja_cenaodsp'];
			$temp_poz_cenatwoja_cr	= $newArray['sprzedaz_pozycja_cenatwoja'];

			
			
			$temp_data			= $newArray['sprzedaz_data'];
			$temp_umowa			= $newArray['sprzedaz_umowa_nazwa'];
			$temp_pion			= $newArray['sprzedaz_pion_nazwa'];
			$temp_up			= $newArray['sprzedaz_up_nazwa'];
			$temp_uwagi			= $newArray['sprzedaz_uwagi'];
			$temp_fakt_id		= $newArray['sprzedaz_faktura_id'];
			$temp_sprzrodzaj	= $newArray['sprzedaz_rodzaj'];
		
		$sql2 = "SELECT * FROM $dbname.serwis_umowy WHERE (umowa_nr='$temp_umowa')";
		$result2 = mysql_query($sql2, $conn) or die($k_b);	
		$newArray2 = mysql_fetch_array($result2);
		$temp_nrzlecenia = $newArray2['umowa_nr_zlecenia'];

		$temp_poz_cena			= decrypt_md5($temp_poz_cena_cr,$key);
		$temp_poz_cena_twoja	= decrypt_md5($temp_poz_cena_twoja_cr,$key);
		
		$temp_poz_cenaodsp	= decrypt_md5($temp_poz_cenaodsp_cr,$key);	
		$temp_poz_cenatwoja	= decrypt_md5($temp_poz_cenatwoja_cr,$key);	
	
		tbl_tr_highlight($j);
				
			$j++;
			$i++;
			
				$sql1="SELECT * FROM $dbname.serwis_faktury WHERE (faktura_id=$temp_fakt_id)";
// 				$sql1="SELECT * FROM $dbname.serwis_faktury WHERE ((belongs_to=$es_filia) and (faktura_id=$temp_fakt_id))";
				$result1 = mysql_query($sql1, $conn) or die($k_b);

					
					while ($newArray4 = mysql_fetch_array($result1)) 
					{ // while 1
						$temp_id4  			= $newArray4['faktura_id'];
						$temp_numer4		= $newArray4['faktura_numer'];
						$temp_data4			= $newArray4['faktura_data'];
						$temp_dostawca4		= $newArray4['faktura_dostawca'];	
						$temp_realizacja4	= $newArray4['faktura_realizacjazakupu'];			
					}
					
					//echo "<td class=center width=30>$i</td>";
					td("30;c;<a class=normalfont href=# title=' sprzedaż id: $temp_id, faktura id: $temp_id4 '>".$i."</a>");
					
					echo "<td>$temp_data4</td>";
					echo "<td>$temp_numer4</td>";
					echo "<td>$temp_dostawca4</td>";
					echo "<td>$temp_poz_nazwa";
					//echo "<br />$temp_data";
					echo "</td>";
					echo "<td class=center>1</td>";
					echo "<td class=right>".correct_currency($temp_poz_cena)." zł</td>";
					
					//$temp_cena_odsprzedazy=(float) ($temp_poz_cena*1.10);
		
					$temp_cena_odsprzedazy = (float) $temp_poz_cena * $wskaznik_marzy;
					//$temp_cena_odsprzedazy = round_up($temp_cena_odsprzedazy, 3);
		
					
					$temp_cena_odsprzedazy = $temp_poz_cenaodsp;
					
					$temp_cena1=''.$temp_cena_odsprzedazy;
					$temp_cena2=str_replace(".", ",", $temp_cena1); 
					
					echo "<td class=right>".correct_currency($temp_cena_odsprzedazy)." zł</td>";
					echo "<td class=center>$obc</td>";
					echo "<td class=center>$temp_nrzlecenia</td>";
					echo "<td class=center>$filian</td>";	
					echo "<td class=center>$temp_realizacja4</td>";									
					echo "<td class=center>$temp_sprzrodzaj</td>";					
					
					$datedate1=strtotime($temp_data4.' 00:00:00');	
					$datedate=date('Y-m-d', $datedate1);

					$cena = str_replace('.',',',$temp_poz_cena);
					$cena2 = str_replace('.',',',$temp_cena_odsprzedazy);

					$sql99="INSERT INTO $dbname.serwis_temp_m2$es_filia VALUES ('$datedate','$temp_numer4','$temp_dostawca4','$temp_poz_nazwa','1','$cena ','$cena2','$obc','$temp_nrzlecenia','$filian','$temp_realizacja4','$temp_sprzrodzaj','$unique')";
					$result99=mysql_query($sql99,$conn) or die($k_b);		
	
			echo "</tr>";
		}
		
		endtable();
		

	} else {
		nowalinia();
		errorheader("Nie można wygenerować raportu ze sprzedaży gdyż nie został zatwierdzony raport za okres ".$m_1."/".$r_1."");
	}


	echo "<form action=do_xls2_htmlexcel.php METHOD=POST target=_blank>";
	
	echo "<input type=hidden name=addsell value=$_POST[addsell]>";
	echo "<input type=hidden name=obc value=$obc>";
	echo "<input type=hidden name=obc1 value=$obc1>";
	echo "<input type=hidden name=unique value=$unique>";
	echo "<input type=hidden name=filian value='$filian'>";
	echo "<input type=hidden name=nadzien value='$_POST[nadzien]'>";

	echo "<input type=hidden name=umowanr value='$_POST[tumowa]'>";
	
	if ($pokazrapzesprz==0) {
		echo "<input type=hidden name=wykrap value=0>";
	} else echo "<input type=hidden name=wykrap value=1>";

if ($wersjaprosta==0) {		
	startbuttonsarea("right");
	oddziel();

	echo "<span style='float:left'>";
	addbackbutton("Zmień kryteria");
	echo "</span>";
	
	if ($i>0) addownsubmitbutton("'Generuj plik XLS'","refresh_");
	addbuttons("start");
	endbuttonsarea();
}
	_form();

}
?>

<script>HideWaitingMessage();</script>

<?php 
} else { ?>

<?php 
	br();
	pageheader("Stany magazynowe");

// przygotowanie tabel
	$sql_f = "SELECT filia_leader FROM $dbname.serwis_filie WHERE filia_leader=$es_nr LIMIT 1";
	$result_f = mysql_query($sql_f, $conn) or die($k_b);
	$countf = mysql_num_rows($result_f);
	
	$czyistnieje = mysql_query("SELECT * FROM $dbname.serwis_temp_m1$es_filia LIMIT 1", $conn);
	if ($czyistnieje) {	
		if ($countf>0) { 
			$sql_report = "TRUNCATE TABLE $dbname.serwis_temp_m1$es_filia";
			$result_report = mysql_query($sql_report, $conn) or die($k_b);			
		}
	} else { 
		if ($countf>0) { 
			$sql_temp1 = "CREATE TABLE `$dbname.serwis_temp_m1$es_filia` (`pole2` varchar(100) collate utf8_polish_ci NOT NULL,`pole3` varchar(100) collate utf8_polish_ci NOT NULL,`pole4` varchar(100) collate utf8_polish_ci NOT NULL,`pole5` varchar(100) collate utf8_polish_ci NOT NULL,`pole6` varchar(100) collate utf8_polish_ci NOT NULL,`pole7` varchar(100) collate utf8_polish_ci NOT NULL,`pole8` varchar(100) collate utf8_polish_ci NOT NULL,`pole9` varchar(100) collate utf8_polish_ci NOT NULL,`pole10` varchar(100) collate utf8_polish_ci NOT NULL,`pole11` varchar(100) collate utf8_polish_ci NOT NULL,`pole12` varchar(100) collate utf8_polish_ci NOT NULL,`pole13` varchar(100) collate utf8_polish_ci NOT NULL, `pole14` varchar(100) collate utf8_polish_ci NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci";
			$result_report = mysql_query($sql_temp1, $conn) or die($k_b);	
		}
	}

	$czyistnieje = mysql_query("SELECT * FROM $dbname.serwis_temp_m2$es_filia LIMIT 1", $conn);
	if ($czyistnieje) {	
		if ($countf>0) { 
			$sql_report = "TRUNCATE TABLE $dbname.serwis_temp_m2$es_filia";
			$result_report = mysql_query($sql_report, $conn) or die($k_b);			
		}
	} else { 
		if ($countf>0) { 
			$sql_temp2 = "CREATE TABLE `$dbname.serwis_temp_m2$es_filia` (`pole2` varchar(100) collate utf8_polish_ci NOT NULL,`pole3` varchar(100) collate utf8_polish_ci NOT NULL,`pole4` varchar(100) collate utf8_polish_ci NOT NULL,`pole5` varchar(100) collate utf8_polish_ci NOT NULL,`pole6` varchar(100) collate utf8_polish_ci NOT NULL,`pole7` varchar(100) collate utf8_polish_ci NOT NULL,`pole8` varchar(100) collate utf8_polish_ci NOT NULL,`pole9` varchar(100) collate utf8_polish_ci NOT NULL,`pole10` varchar(100) collate utf8_polish_ci NOT NULL,`pole11` varchar(100) collate utf8_polish_ci NOT NULL,`pole12` varchar(100) collate utf8_polish_ci NOT NULL,`pole13` varchar(100) collate utf8_polish_ci NOT NULL, `pole14` varchar(100) collate utf8_polish_ci NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci";
			$result_report = mysql_query($sql_temp2, $conn) or die($k_b);
		}
	}

// koniec przygotowania 
	
	echo "<table cellspacing=1 align=center style=width:40%>";
	echo "<form name=ruch action=p_towary_stan.php method=POST onsubmit='return validateForm();'>";	
	tbl_empty_row(1);
	echo "<tr>";
	echo "<td class=center colspan=2>";
	echo "<b>Pokaż stan magazynu na dzień<br /><br /></b>";
	echo "</td>";
	echo "</tr>";
		echo "<tr colspan=2>";
		echo "</tr>";
		$curr = Date('Y-m-d');
		echo "<tr>";
			echo "<td class=center><input class=wymagane size=10 maxlength=10 type=text id=nadzien name=nadzien value=$curr onkeypress='return handleEnter(this, event);' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\">";	
			echo "<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif width=16 height=16 border=0></a>";
			if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('nadzien').value='".Date('Y-m-d')."'; return false;\">";			
			echo "</td>";
		echo "</tr>";

	echo "<tr>";
	echo "<td class=center colspan=2>";
	echo "<br /><input class=border0 type=checkbox id=wlacz name=addsell onclick=\"pokazrap();\">";
	echo "<a class=normalfont href=# style='cursor:hand' onclick=\"if (document.getElementById('wlacz').checked) { document.getElementById('wlacz').checked=false; pokazrap();} else { document.getElementById('wlacz').checked=true; pokazrap();} \">Dołącz raport ze sprzedaży za miesiąc</a>";
	echo "</td>";	
	echo "</tr>";
	
	echo "<tr><td class=center>";
	echo "<div id=rap style=display:none>";
	$r1 = Date('Y');
	$m1 = Date('m');
	$d1 = '01';
	
	$r2 = Date('Y');
	$m2 = Date('m');
	
	if ($m1==1) { $m1=12; $r1--; } else $m1--;
	br();

	// wyliczenie liczby lat wstecz (wg najstarszej daty sprzedaży)	
	$r40 = mysql_query("SELECT sprzedaz_data FROM $dbname.serwis_sprzedaz WHERE (belongs_to='$es_filia') and (sprzedaz_data<>'0000-00-00') ORDER BY sprzedaz_data ASC LIMIT 1", $conn) or die($k_b);	
	list($Rok_Min)=mysql_fetch_array($r40);
	$Rok_Curr = date('Y');	
	$Lat_Wstecz = $Rok_Curr - $Rok_Min;

	echo "<select name=r_o_k onkeypress='return handleEnter(this, event);' onChange=\"document.ruch.m_i_e_s_i_a_c.value=0\">";	
	for ($r=$r1-$Lat_Wstecz; $r<=$r1+1; $r++) 
	{ 
		echo "<option value=$r "; if ($r1==$r) echo "SELECTED"; echo ">$r</option>\n";
	
	}
	echo "</select>";
	
	echo "<select name=m_i_e_s_i_a_c onkeypress='return handleEnter(this, event);' OnChange=\"
	if (this.value==1) { document.ruch.okres_od.value=document.ruch.r_o_k.value+'-0'+this.value+'-01';
						 document.ruch.okres_do.value=document.ruch.r_o_k.value+'-0'+this.value+'-31';
						}
	if (this.value==2) { document.ruch.okres_od.value=document.ruch.r_o_k.value+'-0'+this.value+'-01';
						 document.ruch.okres_do.value=document.ruch.r_o_k.value+'-0'+this.value+'-29';
						}
	if (this.value==3) { document.ruch.okres_od.value=document.ruch.r_o_k.value+'-0'+this.value+'-01';
						 document.ruch.okres_do.value=document.ruch.r_o_k.value+'-0'+this.value+'-31';
						}
	if (this.value==4) { document.ruch.okres_od.value=document.ruch.r_o_k.value+'-0'+this.value+'-01';
						 document.ruch.okres_do.value=document.ruch.r_o_k.value+'-0'+this.value+'-30';
						}
	if (this.value==5) { document.ruch.okres_od.value=document.ruch.r_o_k.value+'-0'+this.value+'-01';
						 document.ruch.okres_do.value=document.ruch.r_o_k.value+'-0'+this.value+'-31';
						}
	if (this.value==6) { document.ruch.okres_od.value=document.ruch.r_o_k.value+'-0'+this.value+'-01';
						 document.ruch.okres_do.value=document.ruch.r_o_k.value+'-0'+this.value+'-30';
						}
	if (this.value==7) { document.ruch.okres_od.value=document.ruch.r_o_k.value+'-0'+this.value+'-01';
						 document.ruch.okres_do.value=document.ruch.r_o_k.value+'-0'+this.value+'-31';
						}
	if (this.value==8) { document.ruch.okres_od.value=document.ruch.r_o_k.value+'-0'+this.value+'-01';
						 document.ruch.okres_do.value=document.ruch.r_o_k.value+'-0'+this.value+'-31';
						}
	if (this.value==9) { document.ruch.okres_od.value=document.ruch.r_o_k.value+'-0'+this.value+'-01';
						 document.ruch.okres_do.value=document.ruch.r_o_k.value+'-0'+this.value+'-30';
						}
	if (this.value==10) { document.ruch.okres_od.value=document.ruch.r_o_k.value+'-'+this.value+'-01';
						 document.ruch.okres_do.value=document.ruch.r_o_k.value+'-'+this.value+'-31';
						}
	if (this.value==11) { document.ruch.okres_od.value=document.ruch.r_o_k.value+'-'+this.value+'-01';
						 document.ruch.okres_do.value=document.ruch.r_o_k.value+'-'+this.value+'-30';
						}
	if (this.value==12) { document.ruch.okres_od.value=document.ruch.r_o_k.value+'-'+this.value+'-01';
						 document.ruch.okres_do.value=document.ruch.r_o_k.value+'-'+this.value+'-31';
						}
	\">";
	echo "<option value=0>Wybierz miesiąc</option>\n";
	echo "<option value=1 "; if ($m1==1) echo "SELECTED"; echo ">Styczeń</option>\n";
	echo "<option value=2 "; if ($m1==2) echo "SELECTED"; echo ">Luty</option>\n";
	echo "<option value=3 "; if ($m1==3) echo "SELECTED"; echo ">Marzec</option>\n";
	echo "<option value=4 "; if ($m1==4) echo "SELECTED"; echo ">Kwiecień</option>\n";
	echo "<option value=5 "; if ($m1==5) echo "SELECTED"; echo ">Maj</option>\n";
	echo "<option value=6 "; if ($m1==6) echo "SELECTED"; echo ">Czerwiec</option>\n";
	echo "<option value=7 "; if ($m1==7) echo "SELECTED"; echo ">Lipiec</option>\n";
	echo "<option value=8 "; if ($m1==8) echo "SELECTED"; echo ">Sierpień</option>\n";
	echo "<option value=9 "; if ($m1==9) echo "SELECTED"; echo ">Wrzesień</option>\n";
	echo "<option value=10 "; if ($m1==10) echo "SELECTED"; echo ">Październik</option>\n";
	echo "<option value=11 "; if ($m1==11) echo "SELECTED"; echo ">Listopad</option>\n";
	echo "<option value=12 "; if ($m1==12) echo "SELECTED"; echo ">Grudzień</option>\n";
	echo "</select>";

	
	echo "<br /><br /><b>Do umowy nr</b><br /><br />";
	
		$sql6="SELECT * FROM $dbname.serwis_umowy WHERE belongs_to=$es_filia";
		$result6 = mysql_query($sql6, $conn) or die($k_b);
		$i = 0;
		
		echo "<select class=wymagane name=tumowa onkeypress='return handleEnter(this, event);'>\n";
		echo "<option value='all'>Wszystkie przypisane dla aktualnej filii</option>\n"; 
				
		while ($newArray = mysql_fetch_array($result6)) 
		 {
			$temp_id  				= $newArray['umowa_id'];
			$temp_nr				= $newArray['umowa_nr'];			
			$temp_nazwa				= $newArray['umowa_opis'];
			
			echo "<option value='$temp_nr'>$temp_nazwa ($temp_nr)</option>\n"; 
		
		}
		
		echo "</select>\n"; 		

		echo "</div>";

	echo "</td>";	
	echo "</tr>";
	tbl_empty_row(1);
	endtable();

	startbuttonsarea("center");
	addownsubmitbutton("'Generuj raport'","submit");
	
 //   echo "<a><input class=buttons type=image name=submit value='Generuj raport (alternatywnie)' onclick=\"newWindow_r(800,600,'p_towary_stan.php)\"></a>");
	
	//addownsubmitbutton("'Generuj raport (alternatywnie)'","submit");
	endbuttonsarea();

	_form();	
?>

<script language="JavaScript">
var cal1 = new calendar1(document.forms['ruch'].elements['nadzien']);
	cal1.year_scroll = true;
	cal1.time_comp = false;
</script>

<script language="JavaScript" type="text/javascript">
	var frmvalidator  = new Validator("ruch");
	 
	frmvalidator.addValidation("nadzien","req","Nie podałeś daty"); 
	frmvalidator.addValidation("nadzien","numerichyphen","Użyłeś niedozwolonych znaków w polu daty");
	
</script>

<?php } ?>

</body>
</html>