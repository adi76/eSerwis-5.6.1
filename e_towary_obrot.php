<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
$_SESSION[edytuj_sprzedaz]=0;
include('inc_encrypt.php');

if ($submit) {

/*
$_POST=sanitize($_POST);
$rok = substr($_POST[tdata],0,4);
$miesiac = substr($_POST[tdata],5,2);

$sql = "SELECT sr_miesiac FROM $dbname.serwis_sprzedaz_raport WHERE ((belongs_to=$es_filia) and (sr_rok=$rok) and (sr_miesiac=$miesiac))";
$result = mysql_query($sql, $conn) or die($k_b);
$count_rows = mysql_num_rows($result);

if ($count_rows==0) {
$dddd=date("Y-m-d H:i");
$nrup = $_POST['new_upid'];

$sql_get_info = "SELECT * FROM $dbname.serwis_komorki WHERE up_id=$nrup LIMIT 1";
$wynik_get_info = mysql_query($sql_get_info, $conn) or die($k_b);
$gi = mysql_fetch_array($wynik_get_info);
$nazwaup = $gi['up_nazwa'];
$pionid = $gi['up_pion_id'];
$umowaid = $gi['up_umowa_id'];

$sql_get_pion = "SELECT pion_nazwa FROM $dbname.serwis_piony WHERE pion_id=$pionid LIMIT 1";
$wynik_get_pion = mysql_query($sql_get_pion, $conn) or die($k_b);
$pi = mysql_fetch_array($wynik_get_pion);
$pionnazwa = $pi['pion_nazwa'];

$sql_get_umowanr = "SELECT umowa_nr FROM $dbname.serwis_umowy WHERE umowa_id=$umowaid LIMIT 1";
$wynik_get_umowanr = mysql_query($sql_get_umowanr, $conn) or die($k_b);
$ui = mysql_fetch_array($wynik_get_umowanr);
$umowanr = $ui['umowa_nr'];

$sql_t = "UPDATE $dbname.serwis_sprzedaz SET sprzedaz_data='$_POST[tdata]', sprzedaz_data_operacji='$dddd', sprzedaz_umowa_nazwa='$umowanr', sprzedaz_pion_nazwa = '$pionnazwa',sprzedaz_up_nazwa='$nazwaup',sprzedaz_uwagi ='".nl2br($_POST[tuwagi])."', sprzedaz_rodzaj='$_POST[trodzaj]' WHERE sprzedaz_id=$_REQUEST[sid]";

if (mysql_query($sql_t, $conn)) {
		
		$sql1a="UPDATE $dbname.serwis_faktura_szcz SET pozycja_datasprzedazy = '$_POST[tdata]' WHERE pozycja_id = '$_REQUEST[tid]'";

		if (mysql_query($sql1a, $conn)) {
			?><script> opener.location.reload(true); self.close();  </script><?php
		}
} else 
	{
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php	
	}

} else
	{
	errorheader("Sprzedaż towaru na dzień ".$_POST[tdata]." jest niemożliwa - wygenerowano już raport ze sprzedaży");	
	startbuttonsarea("right");
	addbuttons("wstecz","zamknij");
	endbuttonsarea();
	}

	*/
} else { ?>
	
<?php

pageheader("Edycja daty sprzedażu i komórki");

$sql9r="SELECT * FROM $dbname.serwis_faktury WHERE ((belongs_to=$es_filia) and (faktura_id='$f')) LIMIT 1";
$result9r = mysql_query($sql9r, $conn) or die($k_b);
while ($newArray9r = mysql_fetch_array($result9r)) 
{
	$temp_id9r  			= $newArray9r['faktura_id'];
	$temp_numer9r			= $newArray9r['faktura_numer'];
	$temp_data9r			= $newArray9r['faktura_data'];
	$temp_dostawca9r		= $newArray9r['faktura_dostawca'];
	$temp_koszty9r			= $newArray9r['faktura_koszty_dodatkowe'];	
	$temp_ilpoz				= $newArray9r['faktura_ilosc_pozycji'];	
	
}		

$il_podfaktur="SELECT * FROM $dbname.serwis_podfaktury WHERE pf_nr_faktury_id=$f and belongs_to=$es_filia";
$wykonaj = mysql_query($il_podfaktur,$conn) or die($k_b);

$sumapf = 0;
while ($wynik_ilpod = mysql_fetch_array($wykonaj)) {
	$pfkwota_cr = $wynik_ilpod['pf_kwota_netto'];
	$pfkwota = decrypt_md5($pfkwota_cr,$key);
	$sumapf+=$pfkwota;
}

$sql9="SELECT * FROM $dbname.serwis_faktura_szcz WHERE ((belongs_to=$es_filia) and (pozycja_id='$pozid')) LIMIT 1";
$result9 = mysql_query($sql9, $conn) or die($k_b);
while ($newArray9 = mysql_fetch_array($result9)) {
	$temp_id9  			= $newArray9['pozycja_id'];
	$temp_nazwa9		= $newArray9['pozycja_nazwa'];
	$temp_sn9			= $newArray9['pozycja_sn'];
	$temp_ttyp			= $newArray9['pozycja_typ'];
	$temp_uwagi1		= $newArray9['pozycja_uwagi'];
	
	$temp_cenanetto9_cr		= $newArray9['pozycja_cena_netto'];
	$temp_cenaodsp_cr		= $newArray9['pozycja_cena_netto_odsprzedazy'];
	
	$temp_cenanetto9 = decrypt_md5($temp_cenanetto9_cr,$key);
	$temp_cenaodsp = decrypt_md5($temp_cenaodsp_cr,$key);
	//"$temp_cenanetto9<br/>$temp_cenaodsp";
}
	//echo "Suma pf : $sumapf | $temp_cenanetto9 | $temp_cenaodsp";
/*	if (($temp_cenanetto9==$temp_cenaodsp) && ($sumapf!=0)) {
	?>
	<script>
		alert('Cena zakupu towaru/usługi nie ma doliczonych kosztów podfaktur(y). \n\nJeżeli potwierdzasz ten stan - kontynuuj sprzedaż.\nJeżeli nie - skontaktuj się z osobą odpowiedzialną za ustalanie cen');
	</script>
	<?php
	
	}
*/	
	startbuttonsarea("center");
	echo "Nazwa towaru : <b>$temp_nazwa9</b><br />Numer seryjny : <b>$temp_sn9</b>";
	echo "<br /><br />";
	
	
	echo "<a id=PokazUwagi class=normalfont style='display:'; href=# onClick=\"document.getElementById('Uwagi').style.display=''; document.getElementById('PokazUwagi').style.display='none'; document.getElementById('UkryjUwagi').style.display='';\">";	
	echo "Uwagi (+) ";
	echo substr($temp_uwagi1,0,20).'...';
	echo "</a>";
	
	echo "<a id=UkryjUwagi class=normalfont style='display:none'; href=# onClick=\"document.getElementById('Uwagi').style.display='none'; document.getElementById('PokazUwagi').style.display=''; document.getElementById('UkryjUwagi').style.display='none';\"> Uwagi (-) </a>";
	
	
	echo "<div id=Uwagi style='display:none'>";
	oddziel();
	echo cleanup($temp_uwagi1);
	oddziel();
	echo "</div>";
	
	endbuttonsarea();
	starttable();
	echo "<form name=addt action=utworz_protokol.php method=POST>";


	tbl_empty_row(2);

/*		echo "<tr>";
		echo "<td width=220 class=right>Pion</td>";

		$sql7="SELECT * FROM $dbname.serwis_piony";
		$result7 = mysql_query($sql7, $conn) or die($k_b);
		$count_rows = mysql_num_rows($result7);
		$i = 0;
		
		echo "<td>";		
		echo "<select class=wymagane name=tpion onkeypress='return handleEnter(this, event);'>\n"; 					 				
		echo "<option value=''>Wybierz pion...";
				
		while ($newArray7 = mysql_fetch_array($result7)) 
		 {
			$temp_id7  				= $newArray7['pion_id'];
			$temp_nazwa7				= $newArray7['pion_nazwa'];
			
		//	echo "<option value='$temp_id'>$temp_nazwa</option>\n"; 
			echo "<option value='$temp_nazwa7'>$temp_nazwa7</option>\n"; 
		
		}
		
		echo "</select>\n"; 
		echo "</td>";
	echo "</tr>";
*/
	echo "<tr>";
		
		echo "<td width=100 class=righttop>Sprzedaż sprzętu dla</td>";		
		echo "<td>";		
		
				$sql44="SELECT * FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) ORDER BY serwis_piony.pion_nazwa,up_nazwa";
				$result44 = mysql_query($sql44, $conn) or die($k_b);
				$count_rows = mysql_num_rows($result44);
				$i = 0;		
				echo "<select class=wymagane name=new_upid onkeypress='return handleEnter(this, event);' onchange=\"reload1_obrot(this.form); this.focus();\" ";
				if ($_REQUEST[hd_zgl_nr]!='') { echo " disabled "; }
				echo ">\n"; 					 				
				echo "<option value=''>Wybierz UP / komórkę z listy...";

				while ($newArray44 = mysql_fetch_array($result44)) 
				{
					$temp_id  				= $newArray44['up_id'];
					$temp_nazwa				= $newArray44['up_nazwa'];
					$temp_pion_id			= $newArray44['up_pion_id'];
					$temp_umowa_id			= $newArray44['up_umowa_id'];

					$sql444="SELECT pion_nazwa FROM $dbname.serwis_piony WHERE pion_id=$temp_pion_id LIMIT 1";
					$result444=mysql_query($sql444,$conn) or die($k_b);
					$wynik = mysql_fetch_array($result444);
					$pionnazwa = $wynik['pion_nazwa'];
					
					echo "<option value=$temp_id";
					if ($_REQUEST[new_upid]==$temp_id) { 
						echo " SELECTED";
						$temp_nazwa_1 = $temp_nazwa;
						$nazwawybranejkomorki = $pionnazwa." ".$temp_nazwa;
					}
					//if ($temp_id==$_REQUEST[new_upid]) { echo " SELECTED"; $nazwawybranejkomorki = $pionnazwa." ".$temp_nazwa; }
					
					echo ">$pionnazwa $temp_nazwa</option>\n"; 
				}
				
				echo "</select>\n"; 
				
				if ($_REQUEST[hd_zgl_nr]!='') { echo "<input type=hidden name=new_upid id=new_upid value='$_REQUEST[new_upid]'>"; }
				
				if ($_REQUEST[new_upid]>0) {			
					echo "<br /><br /><input type=button class=buttons style='color:blue' value='Pokaż zgłoszenia z wybranej komórki' onClick=\"newWindow_r(800,600,'hd_p_zgloszenia_dla_komorki.php?randval=".date('Ymdhim')."".rand()."&komorka=".urlencode($nazwawybranejkomorki)."&showall=0&id=".$_REQUEST[id]."&f=".$_REQUEST[f]."&new_upid=".$_REQUEST[new_upid]."&hd_zgl_nr=".$_REQUEST[hd_zgl_nr]."&trodzaj=".$_REQUEST[trodzaj]."&tdata=".$_REQUEST[tdata]."&nazwa_sprzetu=".urlencode($_REQUEST[nazwa_sprzetu])."&sn_sprzetu=".urlencode($_REQUEST[sn_sprzetu])."&tuwagi=".urlencode($_REQUEST[tuwagi])."&obzp=".$_REQUEST[obzp]."&tumowa=".urlencode($_REQUEST[tumowa])."&allow_change_rs=".$_REQUEST[allow_change_rs]."&ewid_id=".$_REQUEST[ewid_id]."&quiet=".$_REQUEST[quiet]."&nazwa_urzadzenia=".urlencode($_REQUEST[nazwa_urzadzenia])."&sn_urzadzenia=".urlencode($_REQUEST[sn_urzadzenia])."&ni_urzadzenia=".urlencode($_REQUEST[ni_urzadzenia])."&readonly=".$_REQUEST[readonly]."&dodajwymianepodzespolow=".$_REQUEST[dodajwymianepodzespolow]."&_wp_opis=".urlencode($_REQUEST[_wp_opis])."&_wp_sn=".urlencode($_REQUEST[_wp_sn])."&_wp_ni=".urlencode($_REQUEST[_wp_ni])."&file=".urlencode(basename($PHP_SELF))."&sprzedaz=1e'); return false; \" />";
				}
				
		echo "</td>";
	echo "</tr>";
		
	$dddd = Date('Y-m-d');
	if ($_REQUEST[tdata]!='') $dddd = $_REQUEST[tdata];
	if ($_REQUEST[sdata]!='') $dddd = $_REQUEST[sdata];
	echo "<tr>";
		echo "<td width=150 class=right>Data sprzedaży&nbsp;";
		echo "</td>";

		echo "<td><input class=wymagane size=10 type=text maxlength=10 id=tdata name=tdata value='$dddd' onkeypress='return handleEnter(this, event);' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\">";
		echo "<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img src=img/cal.gif width=16 height=16 border=0></a>";
		if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('tdata').value='".Date('Y-m-d')."'; return false;\">";
		echo "</td>";
	echo "</tr>";

	if ($_GET[new_upid]!='') {
		echo "<tr>";
		
			$r40 = mysql_query("SELECT up_umowa_id FROM $dbname.serwis_komorki WHERE (up_id='$_GET[new_upid]') LIMIT 1", $conn) or die($k_b);
			list($umowy_ids)=mysql_fetch_array($r40);
		
			echo "<td width=100 class=right>Realizacja umowy nr</td>";
			
			//$umowy_ids = "'1','5'";
			
		if (strpos($umowy_ids,',')>0) {
			
			$sql7a="SELECT * FROM $dbname.serwis_umowy WHERE (belongs_to=$es_filia) and (umowa_id IN (".$umowy_ids."))";
			//echo $sql7a;
			
			$result7a = mysql_query($sql7a, $conn) or die($k_b);
			$count_rows = mysql_num_rows($result7a);
			$i = 0;
			
			echo "<td>";		
			echo "<select class=wymagane id=tumowa name=tumowa onkeypress='return handleEnter(this, event);' onchange=\"reload1_obrot(this.form); this.focus();\" >\n"; 					 				
			//echo "<option value=''>Wybierz umowę...";

			while ($newArray7a = mysql_fetch_array($result7a)) 
			{
				$temp_id7a  			= $newArray7a['umowa_id'];
				$temp_nr7a				= $newArray7a['umowa_nr'];
				$temp_nrzlecenia7a		= $newArray7a['umowa_nr_zlecenia'];
				$temp_opis7a			= $newArray7a['umowa_opis'];
				
			//	echo "<option value='$temp_id'>$temp_nazwa</option>\n"; 
				echo "<option value='$temp_nr7a'";
				if ($_REQUEST[tumowa]==$temp_nr7a) { 
					echo " SELECTED";
				}
				echo ">$temp_opis7a (Nr : $temp_nr7a, Nr zlecenia : $temp_nrzlecenia7a)</option>\n"; 
			}
			
			echo "</select>\n"; 
			echo "<input type=hidden name=jedna_umowa id=jedna_umowa value=0>";
			echo "</td>";
		} 
		
		if ($umowy_ids=='') {
			echo "<input type=hidden name=jedna_umowa id=jedna_umowa value='-'>";
			echo "<input type=hidden name=tumowa value=''>";
			echo "<td><b><font color=red>Wybrana komórka nie ma podpiętej umowy</font></b></td>";
			
		} else if (strpos($umowy_ids,',')==0) {
			$r41 = mysql_query("SELECT umowa_nr, umowa_nr_zlecenia,umowa_opis FROM $dbname.serwis_umowy WHERE (umowa_id=$umowy_ids) LIMIT 1", $conn) or die($k_b);
			list($umowa_numer,$umowa_zlecenia,$umowa_o)=mysql_fetch_array($r41);
			echo "<input type=hidden name=jedna_umowa id=jedna_umowa value=1>";
			echo "<input type=hidden name=tumowa value='$umowa_numer'>";
			echo "<td><b>$umowa_numer ($umowa_o)</b>, nr zlecenia: <b>$umowa_zlecenia</b></td>";
		}
		
		echo "</tr>";
	}
	
		echo "<tr>";
		echo "<td width=100 class=right>Rodzaj sprzedaży</td>";
			
		echo "<td>";		
		echo "<select class=wymagane name=trodzaj onkeypress='return handleEnter(this, event);' onchange=\"reload1_obrot(this.form); this.focus();\">\n"; 					 				
		echo "<option value=''>Wybierz rodzaj sprzedaży...";

		echo "<option value='Towar'";
		if ($_REQUEST[trodzaj]=='Towar') echo " SELECTED";
		if ($_REQUEST[srodzaj]=='Towar') echo " SELECTED";
		echo ">sprzedaż towaru</option>\n"; 
		
		echo "<option value='Materiał'";
		if ($_REQUEST[trodzaj]=='Materiał') echo " SELECTED";
		if ($_REQUEST[srodzaj]=='Materiał') echo " SELECTED";		
		echo ">sprzedaż materiału do wykonania usługi</option>\n"; 
		
		echo "<option value='Usługa'";
		if ($_REQUEST[trodzaj]=='Usługa') echo " SELECTED";
		if ($_REQUEST[srodzaj]=='Usługa') echo " SELECTED";
		echo ">sprzedaż usługi</option>\n"; 
		
		echo "</select>\n"; 
		echo "</td>";
	echo "</tr>";

	echo "<tr>";
		echo "<td width=100 class=righttop>Uwagi</td>";
		echo "<td><textarea name=tuwagi cols=55 rows=6>";
			if ($_REQUEST[sz]==0) $_REQUEST[tuwagi]='';
			if ($_REQUEST[tuwagi]!='') echo cleanup(cleanup($_REQUEST[tuwagi]));
			if ($tuwagi!='') echo cleanup(cleanup($tuwagi));
			
		echo "</textarea></td>";
	echo "</tr>";
	
	if ($_REQUEST[trodzaj]=='Usługa') {
	
			
			$result = mysql_query("SELECT naprawa_id, naprawa_nazwa, naprawa_model, naprawa_sn, naprawa_uwagi, naprawa_sprzet_zastepczy_id, naprawa_ew_id FROM $dbname.serwis_naprawa WHERE (belongs_to=$es_filia) and (naprawa_status=3) and (naprawa_pobrano_z='$temp_nazwa_1') ORDER BY naprawa_data_odbioru_z_serwisu DESC", $conn) or die($k_b);
			
			if (mysql_num_rows($result)>0) { 
			echo "<tr>";
			echo "<td width=150 class=righttop>Sprzedaż powiązana z naprawą sprzętu</td>";
			echo "<td>";
			
			echo "<select name=pn onkeypress='return handleEnter(this, event);'  onchange=\"reload2_obrot(this.form)\">\n";			 				
			echo "<option value=''>Sprzedaż nie powiązana jest z naprawą</option>\n";
			while (list($temp_id,$temp_nazwa,$temp_model,$temp_sn,$temp_uwagi,$temp_sprzet_zast,$temp_ew_id)=mysql_fetch_array($result)) {
				echo "<option value='$temp_id'";
				if ($_REQUEST[pn]==$temp_id) {
					echo " SELECTED";
					$sprzet_zast_id = $temp_sprzet_zast;
					$naprawa_id5 = $temp_id;
				}
				echo ">$temp_nazwa $temp_model (SN: $temp_sn)</option>\n"; 
			}
			echo "</select>\n"; 
			
			echo "</td>";
		echo "</tr>";
		echo "<tr><td></td><td><font color=red>Wybranie powiązania spowoduje zmianę statusu naprawy na <b>\"oddany do klienta\"</b></font></td></tr>";
		
		}

		
	if ($sprzet_zast_id!=0) {
		
		echo "<tr>";
			echo "<td width=150 class=righttop>Pobierz sprzęt serwisowy z<br /><b>$temp_nazwa_1</b></td>";
			echo "<td>";
			
			$result1 = mysql_query("SELECT naprawa_nazwa,naprawa_sprzet_zastepczy_id FROM $dbname.serwis_naprawa WHERE (naprawa_id=$naprawa_id5) LIMIT 1", $conn) or die($k_b);
			list($temp_typ,$temp_sprzet_zastepczy_id)=mysql_fetch_array($result1);

			//echo "SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn FROM $dbname.serwis_magazyn WHERE (belongs_to=$es_filia) and (magazyn_status=1) and (magazyn_nazwa='$temp_typ') ORDER BY magazyn_model";
			
			//echo "SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn FROM $dbname.serwis_magazyn,serwis_naprawa WHERE (serwis_magazyn.belongs_to=$es_filia) and (serwis_magazyn.magazyn_status=1) and (serwis_magazyn.magazyn_nazwa='$temp_typ') and (naprawa_pobrano_z='$temp_nazwa_1') and (serwis_magazyn.magazyn_id=serwis_naprawa.naprawa_sprzet_zastepczy_id) ORDER BY serwis_magazyn.magazyn_model";
			
			$result = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn FROM $dbname.serwis_magazyn,serwis_naprawa WHERE (serwis_magazyn.belongs_to=$es_filia) and (serwis_magazyn.magazyn_status=1) and (serwis_magazyn.magazyn_nazwa='$temp_typ') and (naprawa_pobrano_z='$temp_nazwa_1') and (serwis_magazyn.magazyn_id=serwis_naprawa.naprawa_sprzet_zastepczy_id) ORDER BY serwis_magazyn.magazyn_model", $conn) or die($k_b);

			if (mysql_num_rows($result)>0) { 

			echo "<select name=sz onkeypress='return handleEnter(this, event);'  onchange=\"reload3_obrot(this.form)\">\n";			 				
			echo "<option value='0'";
			if ($_REQUEST[sz]=='0') echo " SELECTED";
			echo ">Nic nie pobieraj z UP</option>\n";
			while (list($temp_id,$temp_nazwa,$temp_model,$temp_sn)=mysql_fetch_array($result)) {
				echo "<option value='$temp_id'";
				
				if (($temp_sprzet_zastepczy_id==$temp_id) && ($_REQUEST[sz]!=0)) { 
					echo " SELECTED";
					$nazwa = $temp_nazwa." ".$temp_model." (SN:".$temp_sn.")";
				}

				if ($_REQUEST[sz]==$temp_id) { 
					echo " SELECTED";
					$nazwa = $temp_nazwa." ".$temp_model." (SN:".$temp_sn.")";
				}
				echo ">$temp_nazwa ($temp_model, $temp_sn)</option>\n"; 
			}
			
			echo "<input type=hidden name=zastepczy value='$nazwa'>";
			
			echo "</select>\n"; 
			} else if ($clear_typ==1) { echo "<b>wybierz typ sprzętu z listy</b>"; } else echo "<b>brak dostępnego sprzętu serwisowego tego typu na stanie</b>";
			
			echo "</td>";
		echo "</tr>";
	}
}

	tr_();
		td("150;r;Nr zgłoszenia Helpdesk");
		td_(";;");	
			if ($_REQUEST[hd_zgl_nr]>0) {
				echo "<input type=hidden id=hd_zgl_nr name=hd_zgl_nr value='$_REQUEST[hd_zgl_nr]'>";
				echo "<b>$_REQUEST[hd_zgl_nr]</b>";
				//echo "<input class=wymagane type=text name=hd_zgl_nr value='$_REQUEST[hd_zgl_nr]' size=10 maxlength=10>";
			} else {
				echo "<input class=wymagane type=text id=hd_zgl_nr name=hd_zgl_nr value='' size=10 maxlength=10 onKeyPress=\"return filterInput(1, event, false); \">";
			}
			echo "&nbsp;&nbsp;<input type=button class=buttons value='Szczegóły zgłoszenia' onClick=\"PokazZgloszenie(document.getElementById('hd_zgl_nr').value); \">";
		_td();
	_tr();
	
	echo "<input type=hidden name=tprzesylka value=0>"; 	
	echo "<input type=hidden name=tstatus value='1'>";
	
	/*
	   -1 - faktura nie zatwierdzona
		0 - towar dostępny
		1 - towar sprzedany
	*/
	
//	echo "<input size=10 type=hidden name=tutworzoneprzez value=''>";

	echo "<tr><td class=right colspan=2>&nbsp;</td></td>";
	endtable();

	echo "<input type=hidden name=ttyp value='$temp_ttyp'>";
	echo "<input type=hidden name=tidf value='$f'>";
	echo "<input type=hidden name=tid value='$_REQUEST[tid]'>";
	echo "<input type=hidden name=pozid value='$pozid'>";
	echo "<input type=hidden name=tcenaodsp value='$temp_cenaodsp'>";
	echo "<input type=hidden name=tcena value='$temp_cenanetto9'>";
	echo "<input type=hidden name=tnazwa value='$temp_nazwa9'>";
	echo "<input type=hidden name=tsn value='$temp_sn9'>";	

	echo "<input type=hidden name=sid value='$_REQUEST[tid]'>";	
	
	echo "<input type=hidden name=source value='towary-sprzedaz'>";
	echo "<input type=hidden name=findpion value=1>";
	echo "<input type=hidden name=state value='empty'>";
//	echo "<input type=hidden name=c_7 value='on'>";

	if ($_REQUEST[trodzaj]=='Usługa') echo "<input type=hidden name=c_6 value='on'>";
	if ($_REQUEST[trodzaj]=='Towar') echo "<input type=hidden name=c_8 value='on'>";
	
	
	echo "<input type=hidden name=wstecz value='1'>";
	echo "<input type=hidden name=nowy value='0'>";
	
	echo "<input type=hidden name=nazwa_sprzetu value='$temp_nazwa9'>";
	echo "<input type=hidden name=sn_sprzetu value='$temp_sn9'>";

	echo "<input type=hidden name=obzp value='1'>";
	
	echo "<input type=hidden name=edit_towar value=1>";
	echo "<input type=hidden name=wstecz value=''>";
	echo "<input type=hidden id=readonly name=readonly value='$_REQUEST[readonly]'>";
	startbuttonsarea("right");
	if ($umowy_ids!='') {
		echo "<input class=buttons type=submit name=submit id=dalej value=Dalej>";
	}
	addbuttons("anuluj");
	endbuttonsarea();

	_form();

?>
<script language="JavaScript">
var cal1 = new calendar1(document.forms['addt'].elements['tdata']);
	cal1.year_scroll = true;
	cal1.time_comp = false;
</script>

<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("addt");
  
//  frmvalidator.addValidation("tpion","dontselect=0","Nie wybrano pionu");  
  frmvalidator.addValidation("new_upid","dontselect=0","Nie wybrano komórki");  
  frmvalidator.addValidation("tdata","req","Nie podano daty sprzedaży");
//  frmvalidator.addValidation("tumowa","req","Nie wybrano umowy");  
  frmvalidator.addValidation("trodzaj","dontselect=0","Nie wybrano rodzaju sprzedaży");  
frmvalidator.addValidation("hd_zgl_nr","req","Nie podano numeru zgłoszenia Helpdesk");  
</script>

<?php } ?>

</body>
</html>