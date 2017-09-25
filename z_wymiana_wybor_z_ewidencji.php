<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');

if (($_REQUEST[up_id]=='') && ($_REQUEST[typ_id]=='')) $nr=0;
if (($_REQUEST[up_id]!='') && ($_REQUEST[typ_id]=='')) $nr=1;
if (($_REQUEST[up_id]=='') && ($_REQUEST[typ_id]!='')) $nr=0;
if (($_REQUEST[up_id]!='') && ($_REQUEST[typ_id]!='')) $nr=2;

if ($_REQUEST[auto]=='1') $nr=5;

echo "<body OnLoad=document.forms[0].elements[".$nr."].focus();>";	

if ($submit) { 
?><script>ShowWaitingMessage('Trwa zapisywanie danych... proszę czekać', 'Saving');</script><?php 

	$_POST=sanitize($_POST);
	//print_r($_REQUEST);

	$count_towary = 0;
	$count_typy = 0;
	
	// wyczyść zmienne $_SESSION
	unset($_SESSION['faktura_szcz_id_for_zgloszenie_nr_'.$_REQUEST[hd_nr].'']);
	unset($_SESSION['typ_podzespolu_for_zgloszenie_nr_'.$_REQUEST[hd_nr].'']);
	unset($_SESSION['podzespoly_do_zgl_nr_'.$_REQUEST[hd_nr].'']);
	unset($_SESSION['podzespoly_do_zgl_nr_'.$_REQUEST[hd_nr].'_count']);
	unset($_SESSION['wybrany_sprzet_do_wymiany_dla_zgl_nr_'.$_REQUEST[hd_nr].'']);
	unset($_SESSION['typ_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[hd_nr].'']);
	unset($_SESSION['model_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[hd_nr].'']);
	unset($_SESSION['sn_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[hd_nr].'']);
	unset($_SESSION['ni_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[hd_nr].'']);
	unset($_SESSION['wc_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[hd_nr].'']);
	
	// jeżeli wybieramy konkretny zestaw
	if ($_REQUEST[wp_pw]==2) {
		$sql_sell8="SELECT zestawpozycja_fszcz_id, pozycja_status FROM $dbname.serwis_zestaw_szcz,$dbname.serwis_faktura_szcz WHERE (serwis_zestaw_szcz.zestawpozycja_zestaw_id = $_REQUEST[sell_zestaw]) and (serwis_faktura_szcz.pozycja_id=serwis_zestaw_szcz.zestawpozycja_fszcz_id)";
		$result_sell8 = mysql_query($sql_sell8, $conn) or die($k_b);
		$ile_all = mysql_num_rows($result_sell8);
		$count_towary = $ile_all;
		
		$towary = '';
		while ($newArrays8 = mysql_fetch_array($result_sell8)) {
			$temp_id8  			= $newArrays8['zestawpozycja_fszcz_id'];
			$towary .= $temp_id8."|#|";
		}
		$towary = substr($towary,0,-3); 
		
		//echo $towary;
		$_SESSION['podzespoly_do_zgl_nr_'.$_REQUEST[hd_nr].'']='zestaw';
		$_SESSION['podzespoly_do_zgl_nr_'.$_REQUEST[hd_nr].'_count']=''.$count_towary.'';
		
//		$i=1;
		unset($_SESSION['faktura_szcz_id_for_zgloszenie_nr_'.$_REQUEST[hd_nr].'']);
//		foreach ($towary as $towar) {
		$_SESSION['faktura_szcz_id_for_zgloszenie_nr_'.$_REQUEST[hd_nr].''].=$towary;
//			$i++;
//		}

		//$jeden_towar = explode("|#|", $_SESSION['faktura_szcz_id_for_zgloszenie_nr_'.$_REQUEST[hd_nr].'']);
		//for ($i=0; $i<$count_towary; $i++) { echo $jeden_towar[$i]."<br />"; }		
	}
	
	// jeżeli wybieramy konkretne podzespoły
	if ($_REQUEST[wp_pw]==1) {
		$towary = $_REQUEST['selectedP'];
		$towary = substr($towary,3,-3); 
		//print_r($towary);
//		foreach ($towary as $towar) $count_towary++;
		$count_towary = $_REQUEST['selectedPcount'];
		//echo $towary;
		$_SESSION['podzespoly_do_zgl_nr_'.$_REQUEST[hd_nr].'']='magazyn';
		$_SESSION['podzespoly_do_zgl_nr_'.$_REQUEST[hd_nr].'_count']=''.$count_towary.'';
		
//		$i=1;
		unset($_SESSION['faktura_szcz_id_for_zgloszenie_nr_'.$_REQUEST[hd_nr].'']);
//		foreach ($towary as $towar) {
		$_SESSION['faktura_szcz_id_for_zgloszenie_nr_'.$_REQUEST[hd_nr].''].=$towary;
//			$i++;
//		}
		//$jeden_towar = explode("|#|", $_SESSION['faktura_szcz_id_for_zgloszenie_nr_'.$_REQUEST[hd_nr].'']);
		//for ($i=0; $i<$count_towary; $i++) { echo $jeden_towar[$i]."<br />"; }	
		//exit;
	}
	
	if ($_REQUEST[wp_pw]==0) {
		// jeżeli wybieramy typy podzespołów
		$typy = $_REQUEST['selectedT'];
		$typy = substr($typy,3,-3); 
//		print_r($typy);
//		foreach ($typy as $typ) $count_typy++;
		$count_typy = $_REQUEST['selectedTcount'];
	//	echo $count_typy;
		
		$_SESSION['podzespoly_do_zgl_nr_'.$_REQUEST[hd_nr].'']='typ';
		$_SESSION['podzespoly_do_zgl_nr_'.$_REQUEST[hd_nr].'_count'].=''.$count_typy.'';		
		
//		$i=1;
		unset($_SESSION['typ_podzespolu_for_zgloszenie_nr_'.$_REQUEST[hd_nr].'']);
//		foreach ($typy as $typ) {
		$_SESSION['typ_podzespolu_for_zgloszenie_nr_'.$_REQUEST[hd_nr].''].=$typy;
//			$i++;
//		}

		//$jeden_typ = explode("|#|", $_SESSION['typ_podzespolu_for_zgloszenie_nr_'.$_REQUEST[hd_nr].'']);
		//for ($i=0; $i<$count_typy; $i++) { echo $jeden_typ[$i]."<br />"; }

	}

	$_SESSION['wybrany_sprzet_do_wymiany_dla_zgl_nr_'.$_REQUEST[hd_nr].'']=1;

	$_SESSION['typ_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[hd_nr].'']=$_REQUEST[tnazwa];
	$_SESSION['model_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[hd_nr].'']=$_REQUEST[tmodel];
	$_SESSION['sn_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[hd_nr].'']=$_REQUEST[tsn];
	$_SESSION['ni_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[hd_nr].'']=$_REQUEST[tni];
	$_SESSION['wc_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[hd_nr].'']=$_REQUEST[tuwagi];

	
//	print_r($_SESSION);
	
	$put_ni_urzadzenia = $_SESSION['ni_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[hd_nr].''];
	$put_sn_urzadzenia = $_SESSION['sn_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[hd_nr].''];
	$put_model_urzadzenia = $_SESSION['model_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[hd_nr].''];	
	
			if ($_REQUEST[popraw_w_ewidencji]=='on') {
			
				$sql22 = "SELECT up_nazwa FROM $dbname.serwis_komorki WHERE ((up_id=$_REQUEST[new_upid]) and (belongs_to=$es_filia)) LIMIT 1";
				$wynik22 = mysql_query($sql22,$conn);
				list($nazwa_up) = mysql_fetch_array($wynik22);
				
				$wynik3 = mysql_query("UPDATE $dbname.serwis_ewidencja SET ewidencja_zestaw_ni='$put_ni_urzadzenia', ewidencja_komputer_sn='$put_sn_urzadzenia', ewidencja_komputer_opis='$put_model_urzadzenia' WHERE ewidencja_id=$_REQUEST[ewid_id] LIMIT 1", $conn) or die($k_b);
			}
			
			// dodanie nowego sprzętu do bazy ewidencji
			if ($_REQUEST[dodaj_sprzet_do_ewidencji]=='on') {
				$sql22 = "SELECT up_nazwa FROM $dbname.serwis_komorki WHERE ((up_id=$_REQUEST[new_upid]) and (belongs_to=$es_filia)) LIMIT 1";
				$wynik22 = mysql_query($sql22,$conn);
				list($nazwa_up) = mysql_fetch_array($wynik22);
			
				$sql22 = "SELECT rola_id FROM $dbname.serwis_slownik_rola WHERE (rola_nazwa='$_REQUEST[tnazwa]') LIMIT 1";
				//echo $sql22;
				
				$wynik22 = mysql_query($sql22,$conn);
				list($typ_id) = mysql_fetch_array($wynik22);
				
				//echo "INSERT INTO $dbname.serwis_ewidencja VALUES ('', '$typ_id','$_REQUEST[tnazwa]','$_REQUEST[new_upid]','$nazwa_up','','','$put_ni_urzadzenia','','$_REQUEST[model_urzadzenia]','$put_sn_urzadzenia','','','','','','','','',$es_filia,'-1',0,'$dddd','$currentuser','','','','','','',0)";
				
				$d1 = date("Y-m-d H:i:s");
				
				// aktualizuj słownik
				if (($_REQUEST[tnazwa]=='Komputer') || ($_REQUEST[tnazwa]=='Serwer') || ($_REQUEST[tnazwa]=='Notebook')) {
					$wynik3 = mysql_query("INSERT INTO $dbname.serwis_slownik_konfiguracja VALUES ('', 'pozycja dodana automatycznie w dniu $d1 przez $currentuser','$es_filia','$put_model_urzadzenia','','','')", $conn) or die($k_b);
				}
				
				if ($_REQUEST[tnazwa]=='Monitor') {
					$wynik3 = mysql_query("INSERT INTO $dbname.serwis_slownik_monitor VALUES ('', '$put_model_urzadzenia','pozycja dodana automatycznie w dniu $d1 przez $currentuser','','$es_filia','')", $conn) or die($k_b);
				}
				
				if ($_REQUEST[tnazwa]=='Drukarka') {
					$wynik3 = mysql_query("INSERT INTO $dbname.serwis_slownik_drukarka VALUES ('', '$put_model_urzadzenia','pozycja dodana automatycznie w dniu $d1 przez $currentuser','$es_filia')", $conn) or die($k_b);
				}
				
				if ($_REQUEST[tnazwa]=='Czytnik') {
					$wynik3 = mysql_query("INSERT INTO $dbname.serwis_slownik_czytnik VALUES ('', '$put_model_urzadzenia','pozycja dodana automatycznie w dniu $d1 przez $currentuser','$es_filia')", $conn) or die($k_b);
				}
				
				// dodanie do ewidencji
				$wynik3 = mysql_query("INSERT INTO $dbname.serwis_ewidencja VALUES ('', '$typ_id','$_REQUEST[tnazwa]','$_REQUEST[new_upid]','$nazwa_up','','','$put_ni_urzadzenia','','$put_model_urzadzenia','$put_sn_urzadzenia','','','','','','','','',$es_filia,'-1',0,'$dddd','$currentuser','','','','','','',0)", $conn) or die($k_b);
			}

	?>
	<script>
	self.close();
	if (opener) opener.location.reload(true);
	</script>
	<?php 

/*	if ($_POST[tuwagi]!='') { $tuwagisa='1'; } else $tuwagisa='0';
	$dddd = Date('Y-m-d H:i:s');
	if ($_POST[sz]!='0') {
		$wynik=mysql_query("INSERT INTO $dbname.serwis_historia VALUES ('','$_POST[sz]','$_POST[tup]','$_POST[tuser]','$dddd','pobrano na','".nl2br($_POST[tuwagi])."',$es_filia)", $conn) or die($k_b);
		$wynik2=mysql_query("UPDATE $dbname.serwis_magazyn SET magazyn_status = '1' WHERE magazyn_id = '$_POST[sz]' LIMIT 1", $conn) or die($k_b);
	}
	if ($_REQUEST[id]=='') $id='0';
	$sql_t = "INSERT INTO $dbname.serwis_naprawa VALUES ('', '$_POST[tnazwa]','$_POST[tmodel]','$_POST[tsn]','$_POST[tni]',$tuwagisa,'".nl2br($_POST[tuwagi])."','$_POST[tup]','$currentuser','$dddd','','','','','','','','','','','','',-1,$es_filia,'','','$_POST[sz]',$id)";
	if (mysql_query($sql_t, $conn)) { 
		if ($auto==1) {
			$wynik3 = mysql_query("UPDATE $dbname.serwis_ewidencja SET ewidencja_status = -1 WHERE ewidencja_id=$id LIMIT 1", $conn) or die($k_b);
		}
		?><script> opener.location.reload(true); self.close(); </script><?php
	} else {	
			?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php	
			}
	*/
} else {
$cat=$_POST['cat'];

//$_SESSION['faktura_szcz_id_for_zgloszenie_nr_'.$_REQUEST[hd_nr].'']=0;
//$_SESSION['wykonaj_sprzedaz'] = 0;

// wyczyść zmienne $_SESSION
unset($_SESSION['faktura_szcz_id_for_zgloszenie_nr_'.$_REQUEST[id].'']);
unset($_SESSION['typ_podzespolu_for_zgloszenie_nr_'.$_REQUEST[id].'']);

unset($_SESSION['podzespoly_do_zgl_nr_'.$_REQUEST[id].'']);
unset($_SESSION['podzespoly_do_zgl_nr_'.$_REQUEST[id].'_count']);
unset($_SESSION['wybrany_sprzet_do_wymiany_dla_zgl_nr_'.$_REQUEST[id].'']);
unset($_SESSION['typ_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].'']);
unset($_SESSION['model_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].'']);
unset($_SESSION['sn_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].'']);
unset($_SESSION['ni_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].'']);
unset($_SESSION['wc_sprzetu_dla_zgloszenia_nr_'.$_REQUEST[id].'']);
	
pageheader("Informacje o sprzęcie, którego dotyczy wymiana podzespołów");

?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();

if ($Podst_Inf_o_zgl) include_once('hd_inf_podstawowe.php');
	
starttable();
echo "<form name=addt action=z_wymiana_wybor_z_ewidencji.php method=POST onSubmit=\"return pytanie_zatwierdz_wp('Potwierdzasz poprawność wprowadzonych danych ?');\"/>";
tbl_empty_row(2);
	tr_();
		td("150;r;Sprzęt pobrano z");
		if ($id!=0) {
			
			$result = mysql_query("SELECT * FROM $dbname.serwis_ewidencja WHERE (belongs_to=$es_filia) and (ewidencja_id=$id) LIMIT 1", $conn) or die($k_b);
			
			while ($dane = mysql_fetch_array($result)) {
				$eid 			= $dane['ewidencja_id'];					$etyp_id		= $dane['ewidencja_typ'];
				$etypnazwa		= $dane['ewidencja_typ_nazwa'];				$eup_id			= $dane['ewidencja_up_id'];
				$eupnazwa		= $dane['ewidencja_up_nazwa'];				$euser			= $dane['ewidencja_uzytkownik'];									  
				$enrpok			= $dane['ewidencja_nr_pokoju'];				$enizest		= $dane['ewidencja_zestaw_ni'];
				$eknazwa		= $dane['ewidencja_komputer_nazwa'];		$ekopis			= $dane['ewidencja_komputer_opis'];
				$eksn			= $dane['ewidencja_komputer_sn'];			$ekip			= $dane['ewidencja_komputer_ip'];
				$eke			= $dane['ewidencja_komputer_endpoint'];		$emo			= $dane['ewidencja_monitor_opis'];
				$emsn			= $dane['ewidencja_monitor_sn'];			$edo			= $dane['ewidencja_drukarka_opis'];
				$edsn			= $dane['ewidencja_drukarka_sn'];			$edni			= $dane['ewidencja_drukarka_ni'];
				$eu				= $dane['ewidencja_uwagi'];					$es				= $dane['ewidencja_status'];
				$eo_id			= $dane['ewidencja_oprogramowanie'];		$emoduser 		= $dane['ewidencja_modyfikacja_user'];
				$emoddata		= $dane['ewidencja_modyfikacja_date'];		$ekonf			= $dane['ewidencja_konfiguracja'];
				$egwarancja		= $dane['ewidencja_gwarancja_do'];			$egwarancjakto	= $dane['ewidencja_gwarancja_kto'];		
				$drukarkapow	= $dane['ewidencja_drukarka_powiaz_z'];		$tup			= $eupnazwa;
				//$tnazwa			= $etypnazwa;

				if ($etypnazwa=='Drukarka') {
					$tmodel		= $edo;
					$tsn		= $edsn;
					$tni		= $edni;			
				}
				if (($etypnazwa=='Komputer') || ($etypnazwa=='Serwer') || ($etypnazwa=='Notebook')) {
					$tmodel		= $ekopis;
					$tsn		= $eksn;
					$tni		= $enizest;
				}
				if ($etypnazwa=='Monitor') {
					$tmodel		= $emo;
					$tsn		= $emsn;
					$tni		= $enizest;
					//$tnazwa		= 'Monitor';
				}
				if ($etypnazwa=='i') {
					$tmodel		= $ekopis;
					$tsn		= $eksn;
					$tni		= $enizest;			
				}
				
			}
		}
		
		if (($auto==0) && ($_REQUEST[popraw_dane]!=1)) {
		
			$result44 = mysql_query("SELECT serwis_komorki.up_id, serwis_komorki.up_nazwa, serwis_piony.pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) ORDER BY serwis_piony.pion_nazwa,up_nazwa", $conn) or die($k_b);
			td_(";;");
				if ($_REQUEST[tresc_zgl]!='') { 
					//if ($_REQUEST[tup]!='') {
				//		$tup=$_REQUEST[tup1];
				//	} else 
					
					$tup=$_REQUEST[up];
					//echo "$_REQUEST[tup]";
					
					echo "<input type=hidden name=tup value='$_REQUEST[tup]'>";
					echo "<input type=hidden name=up value='$_REQUEST[up]'>";
					echo "<b>$tup</b>";
					
					$sama_nazwa_up = substr($tup,strpos($tup," ")+1,strlen($tup));
				
					//echo "<input type=hidden name=up value='$sama_nazwa_up'>";
					echo "<input type=hidden name=new_upid value='$_REQUEST[new_upid]'>";
					
				} else {
				
					echo "<select "; 
					
					//if ($_REQUEST[tresc_zgl]!='') echo " DISABLED ";
					echo " class=wymagane name=new_upid onkeypress='return handleEnter(this, event);'>\n"; 					 				
					echo "<option value='0'>Wybierz z listy...</option>\n";
					while (list($temp_id,$temp_nazwa,$temp_pion)=mysql_fetch_array($result44)) {				
						echo "<option value='$temp_id' ";
						if ($auto==1) { if ($new_upid==$temp_id) echo "SELECTED"; }
						if ($new_upid==$temp_id) echo "SELECTED";
						//if (($tup!='') && ($tup==cleanup(cleanup($temp_nazwa)))) echo "SELECTED";
						echo ">$temp_pion $temp_nazwa</option>\n"; 
					}
					echo "</select>\n"; 
					
					echo "<input type=hidden name=tup value='$_REQUEST[tup]'>";
					echo "<input type=hidden name=up value='$_REQUEST[up]'>";
					
					if ($_REQUEST[dodaj_do_ewidencji]==1) 
						echo "&nbsp;<font color=red><a id=gw1 title=' Można dodać nowy sprzęt do bazy ewidencji '>*</a></font>";
				}
				
					
			_td();

		} else
		{
			td_(";;");	
				//echo "SELECT serwis_komorki.up_id, serwis_komorki.up_nazwa, serwis_piony.pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) and (serwis_komorki.up_id=$_REQUEST[new_upid]) ORDER BY serwis_piony.pion_nazwa,up_nazwa";
				
				$result44 = mysql_query("SELECT serwis_komorki.up_id, serwis_komorki.up_nazwa, serwis_piony.pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) and (serwis_komorki.up_id='$_REQUEST[new_upid]') ORDER BY serwis_piony.pion_nazwa,up_nazwa", $conn) or die($k_b);
				list($temp_id,$temp_nazwa,$temp_pion) = mysql_fetch_array($result44);
			/*	$tup=$temp_pion. " ".$_REQUEST[up];
				
				$sama_nazwa_up = substr($tup,strpos($tup," ")+1,strlen($tup));
				
				echo "<input type=hidden name=tup value='$sama_nazwa_up'>";
				echo "<input type=hidden name=up value='$tup'>";		
			*/	
				echo "<input type=hidden name=new_upid value='$_REQUEST[new_upid]'>";
				
		//		echo "<input type=hidden name=tup value='$temp_pion $temp_nazwa'>";
		//		echo "<input type=hidden name=up value='$temp_nazwa'>";
				
				echo "<b>$temp_pion $temp_nazwa</b>";
			_td();
		}
	_tr();
	tr_();
		td("150;r;Typ sprzętu");
			
			//echo ">>>> $auto		-	$_REQUEST[popraw_dane]";
			echo "<input type=hidden name=auto id=auto value='$auto'>";
			echo "<input type=hidden name=auto1 id=auto1 value='$auto'>";
			
			if (($auto==0) && ($_REQUEST[popraw_dane]!=1)) {	
				//$result = mysql_query("SELECT rola_id,rola_nazwa FROM $dbname.serwis_slownik_rola WHERE rola_do_ewidencji=1 ORDER BY rola_nazwa", $conn) or die($k_b);
				if (($_REQUEST[hd_podkategoria_nr]=='3') || ($_REQUEST[hd_podkategoria_nr]=='4')) { 
					$result7 = mysql_query("SELECT rola_id,rola_nazwa FROM $dbname.serwis_slownik_rola WHERE (rola_do_ewidencji=1) and ((rola_id=1) or (rola_id=2) or (rola_id=7) or (rola_id=18)) ORDER BY rola_nazwa", $conn) or die($k_b);
				} else {					
					$result7 = mysql_query("SELECT rola_id,rola_nazwa FROM $dbname.serwis_slownik_rola WHERE (rola_do_ewidencji=1) and ((rola_id=1) or (rola_id=4) or (rola_id=2) or (rola_id=3) or (rola_id=7) or (rola_id=10) or (rola_id=11) or (rola_id=18) or (rola_id=29)) ORDER BY rola_nazwa", $conn) or die($k_b);
				}
				/*
					if ($_REQUEST[hd_podkategoria_nr]=='3') $result7 = mysql_query("SELECT rola_id,rola_nazwa FROM $dbname.serwis_slownik_rola WHERE ((rola_id=1) or (rola_id=18) or (rola_id=7) or (rola_id=2)) and (rola_do_ewidencji=1) ORDER BY rola_nazwa", $conn) or die($k_b);

					if ($_REQUEST[hd_podkategoria_nr]=='2') $result7 = mysql_query("SELECT rola_id,rola_nazwa FROM $dbname.serwis_slownik_rola WHERE ((rola_id=1) or (rola_id=18) or (rola_id=7) or (rola_id=2)) and (rola_do_ewidencji=1) ORDER BY rola_nazwa", $conn) or die($k_b);
					
					if ($_REQUEST[hd_podkategoria_nr]=='5') $result7 = mysql_query("SELECT rola_id,rola_nazwa FROM $dbname.serwis_slownik_rola WHERE ((rola_id=1) or (rola_id=18) or (rola_id=7) or (rola_id=2)) and (rola_do_ewidencji=1) ORDER BY rola_nazwa", $conn) or die($k_b);
					
					if ($_REQUEST[hd_podkategoria_nr]=='4') $result7 = mysql_query("SELECT rola_id,rola_nazwa FROM $dbname.serwis_slownik_rola WHERE ((rola_id=2) and (rola_do_ewidencji=1)) ORDER BY rola_nazwa", $conn) or die($k_b);
					
					if ($_REQUEST[hd_podkategoria_nr]=='0') $result7 = mysql_query("SELECT rola_id,rola_nazwa FROM $dbname.serwis_slownik_rola WHERE (((rola_id=17) or (rola_id=11) or (rola_id=10) or (rola_id=29) or (rola_id=3) or (rola_id=4) or (rola_id=30)) and (rola_do_ewidencji=1)) ORDER BY rola_nazwa", $conn) or die($k_b);
					
					if ($_REQUEST[hd_podkategoria_nr]=='9') $result7 = mysql_query("SELECT rola_id,rola_nazwa FROM $dbname.serwis_slownik_rola WHERE (((rola_id=17) or (rola_id=11) or (rola_id=10) or (rola_id=29) or (rola_id=3) or (rola_id=4) or (rola_id=30)) and (rola_do_ewidencji=1)) ORDER BY rola_nazwa", $conn) or die($k_b);
				*/
				
					td_(";;");
					
						echo "<select class=wymagane name=tnazwa id=tnazwa onkeypress='return handleEnter(this, event);' onchange=\"reload_wymiana_podzespolow(this.form)\">\n";
						echo "<option value=''>Wybierz z listy...";
						while (list($temp_id,$temp_nazwa)=mysql_fetch_array($result7)) {
							echo "<option value='$temp_nazwa' ";
							if ($auto==1) { if ($tnazwa==$temp_nazwa) echo "SELECTED "; }
							if (($_REQUEST[clear_typ]!=0) && ($typ_id==$temp_id)) echo "SELECTED ";
							if (($temp_nazwa==$_REQUEST[cat]) && ($_REQUEST[clear_typ]==0)) echo "SELECTED ";
							if (($temp_nazwa==$_REQUEST[cat]) && ($_REQUEST[clear_typ]==1)) echo "SELECTED ";
							if ($temp_nazwa==$_REQUEST[tnazwa]) echo " SELECTED";
							echo ">$temp_nazwa</option>\n"; 
						}
						echo "</select>\n"; 
						if ($_REQUEST[dodaj_do_ewidencji]==1) 
							echo "&nbsp;<font color=red><a id=gw2 title=' Można dodać nowy sprzęt do bazy ewidencji '>*</a></font>";
							
					_td();
				
			} else {
					td_(";;");
						echo "<input type=hidden name=tnazwa value='$tnazwa'>";
						echo "<b>$_REQUEST[tnazwa]</b>";
					_td();
				}
	_tr();
	tr_();
		td("150;r;Model");

		if ($auto==1) 
			{
				if ($_REQUEST[tmodel]=='') {
					td_(";;");
						echo "<input class=wymagane size=35 maxlength=30 type=text id=tmodel name=tmodel>";
					_td();
				} else
				{
					td_(";;");
						echo "<input tabindex=-1 type=hidden id=tmodel name=tmodel value='$_REQUEST[tmodel]'>";
						echo "<b>$_REQUEST[tmodel]</b>";
					_td();
				}
			} else
			{	
				td_(";;");
					echo "<input class=wymagane size=35 maxlength=30 type=text id=tmodel name=tmodel onkeypress='return handleEnter(this, event);' value='$_REQUEST[tmodel]' onBlur=\"SprawdzSlownik(this.value);\">";
					
					if ($_REQUEST[dodaj_do_ewidencji]==1) 
						echo "&nbsp;<font color=red><a id=gw3 title=' Można dodać nowy sprzęt do bazy ewidencji '>*</a></font>";
						
					if (($_REQUEST[ewid_id]!='') && ($_REQUEST[popraw_dane]==1)) 
						echo "&nbsp;<font color=red><a title=' Można dokonać uaktualnienia tej wartości w bazie ewidencji sprzętu '>*</a></font>";
						
					echo "<div id=info1 style='display:none'><i><font color=red>Brak wpisanego modelu w słowniku. Wprowadzony model zostanie automatycznie dodany do odpowiedniego słownika.</font></i></div>";
				_td();
			}
			echo "<input type=hidden name=tmodelid id=tmodelid>";
			echo "<input type=hidden name=tmodelid1 id=tmodelid1>";
	_tr();
	tr_();
		td("150;r;Numer seryjny");
		if ($auto==1) {
				if ($_REQUEST[tsn]=='') {
					td_(";;");
						echo "<input class=wymagane size=35 maxlength=30 type=text id=tsn name=tsn>";
					_td();
				} else {
					td_(";;");
						echo "<input tabindex=-1 type=hidden id=tsn name=tsn value='$_REQUEST[tsn]'>";
						echo "<b>$_REQUEST[tsn]</b>";
					_td();
				}
		} else {			
				td_(";;");
					echo "<input class=wymagane size=35 maxlength=30 type=text id=tsn name=tsn onkeypress='return handleEnter(this, event);'  value='$_REQUEST[tsn]'>";
					
					if ($_REQUEST[dodaj_do_ewidencji]==1) 
						echo "&nbsp;<font color=red><a id=gw4 title=' Można dodać nowy sprzęt do bazy ewidencji '>*</a></font>";
						
					if (($_REQUEST[ewid_id]!='') && ($_REQUEST[popraw_dane]==1)) 	
						echo "&nbsp;<font color=red><a title=' Można dokonać uaktualnienia tej wartości w bazie ewidencji sprzętu '>*</a></font>";
				_td();
				}
	_tr();
	tr_();
		td("150;r;Numer inwentarzowy");
		//if ($_REQUEST[tni]=='') $_REQUEST[tni]='-';
		
		if ($auto==1) {
			td_(";;");
				if ($_REQUEST[tni]=='') {
					//echo "<input class=wymagane size=23 maxlength=20 type=text id=tni name=tni onkeypress='return handleEnter(this, event);'  value='$_REQUEST[tni]'>";
					echo "<b>-</b>";
					echo "<input tabindex=-1 type=hidden id=tni name=tni value='-'>";
				} else {
					echo "<input tabindex=-1 type=hidden id=tni name=tni value='$_REQUEST[tni]'>";
					echo "<b>$_REQUEST[tni]</b>";
				}
			_td();
		} else
			{			
				td_(";;");
					echo "<input class=wymagane size=23 maxlength=20 type=text id=tni name=tni onkeypress='return handleEnter(this, event);'  value='$_REQUEST[tni]'>";
					if ($_REQUEST[dodaj_do_ewidencji]==1) 
						echo "&nbsp;<font color=red><a id=gw5 title=' Można dodać nowy sprzęt do bazy ewidencji '>*</a></font>";
						
					if (($_REQUEST[ewid_id]!='') && ($_REQUEST[popraw_dane]==1))  
						echo "&nbsp;<font color=red><a title=' Można dokonać uaktualnienia tej wartości w bazie ewidencji sprzętu '>*</a></font>";
				_td();
			}
	_tr();
	tr_();
		td("150;rt;Wykonane czynności / wymieniane podzespoły");
		td_(";;");
//			echo "<textarea name=tuwagi id=tuwagi cols=50 rows=6 onBlur=\"createCookie('przyjecie_drukarki_$ewid_id',''+nl2br(this.value)+'',100);\">".cleanup(cleanup(($_REQUEST[tuwagi])))."</textarea>";
		
			echo "<textarea name=tuwagi id=tuwagi class=wymagane cols=70 rows=3>".cleanup(cleanup(($_REQUEST[tuwagi])))."</textarea>";

			echo "<a href=# class=normalfont onclick=\"document.getElementById('tuwagi').value=''; \" title=' Wyczyść wykonane czynności'> <img src=img/czysc.gif border=0></a>";
			/*			
			?>
			<script>
				var x = readCookie('przyjecie_drukarki_<?php echo $ewid_id; ?>');
				document.getElementById('tuwagi').value = br2nl(x);
			</script>
			<?php 
*/

		_td();
	_tr();
	
	$sql_filtruj = "SELECT pozycja_id, pozycja_nr_faktury,pozycja_nazwa, pozycja_sn, pozycja_typ, pozycja_uwagi FROM $dbname.serwis_faktura_szcz WHERE (belongs_to=$es_filia) and (pozycja_status='0') and (pozycja_rodzaj_sprzedazy<>'Usługa') ORDER BY pozycja_typ ASC";
	$result44 = mysql_query($sql_filtruj, $conn) or die($k_b);
	$count_rows = mysql_num_rows($result44);

	$sql_filtruj2 = "SELECT zestaw_id, zestaw_opis,zestaw_kto, zestaw_kiedy, zestaw_hd_zgl_nr FROM $dbname.serwis_zestawy WHERE (belongs_to=$es_filia) and (zestaw_status=0) and (zestaw_hd_zgl_nr=0) ORDER BY zestaw_opis ASC";
	$result44b = mysql_query($sql_filtruj2, $conn) or die($k_b);
	$count_rows_zestaw = mysql_num_rows($result44b);
	
	echo "<tr>";
	td("140;r;Powiązanie wymiany z");
		td_(";;;");
			if ($_COOKIE['wybrane_powiazanie_'.$_REQUEST[hd_nr].'']=='') $_COOKIE['wybrane_powiazanie_'.$_REQUEST[hd_nr].''] = 1;
			
			echo "<select name=wp_pw id=wp_pw onChange=\"if (this.value=='0') { $('#wp_typ_podzespolu').show(); $('#wp_magazyn').hide(); $('#wp_magazyn_zestaw').hide(); document.getElementById('sell_towar').selectedIndex=0; } if (this.value=='1') { $('#wp_typ_podzespolu').hide(); $('#wp_magazyn').show(); $('#wp_magazyn_zestaw').hide(); } if (this.value=='2') { $('#wp_typ_podzespolu').hide(); $('#wp_magazyn').hide(); $('#wp_magazyn_zestaw').show(); } ClearCookie('wybrane_powiazanie_".$_REQUEST[hd_nr]."'); SetCookie('wybrane_powiazanie_".$_REQUEST[hd_nr]."',this.value); \">";
						
			echo "<option value='0' "; 
				if ($_COOKIE['wybrane_powiazanie_'.$_REQUEST[hd_nr].'']==0) echo "SELECTED";
			echo ">typem podzespołu</option>";
			
			if ($count_rows>0) {
				echo "<option value='1' ";
				if ($_COOKIE['wybrane_powiazanie_'.$_REQUEST[hd_nr].'']==1) echo "SELECTED";
				if (($_COOKIE['wybrane_powiazanie_'.$_REQUEST[hd_nr].'']!=0) && ($_COOKIE['wybrane_powiazanie_'.$_REQUEST[hd_nr].'']!=1) && ($_COOKIE['wybrane_powiazanie_'.$_REQUEST[hd_nr].'']!=2)) echo "SELECTED";
				//echo "SELECTED";
				echo ">pozycją z magazynu</option>";
			}
			
			if ($count_rows_zestaw>0) {
				echo "<option value='2'";
				if ($_COOKIE['wybrane_powiazanie_'.$_REQUEST[hd_nr].'']==2) echo "SELECTED";
				echo " >z utworzonym zestawem</option>";
			}
			
			echo "</select>";
		_td();
	echo "</tr>";

	echo "<tr id=wp_magazyn_zestaw style='display:'>";
		td("150;rt;");
		td_(";;");	

		$cat = $_REQUEST[cat];

		$sql_sell="SELECT * FROM $dbname.serwis_zestawy WHERE (belongs_to=$es_filia) and (zestaw_status=0) and (zestaw_hd_zgl_nr=0) ORDER BY zestaw_opis ASC";
		// and (pozycja_rodzaj_sprzedazy<>'Usługa') 
		
		//echo $sql_sell;
		$result_sell = mysql_query($sql_sell, $conn) or die($k_b);
		$count_sell = mysql_num_rows($result_sell);
		
			//if (mysql_num_rows($result_sell)>0) { 
				
				//echo "<select id=c multiple=multiple name=c><option>1</option><option>2</option></select>";
				echo "<table width='auto'>";
				echo "<th class=center>Zestawy dostępne do sprzedaży</th>";
				echo "<tr>";
					echo "<td class=center>";			
					
					echo "<select name=sell_zestaw id=sell_zestaw onkeypress='return handleEnter(this, event);' >\n";
					echo "<option value=''>wybierz zestaw w listy</option>";
					$opt_typ = '';
					$opt_end = '';
					$notfirst = 0;
					$cnt = 0;
					
					while ($newArrays8 = mysql_fetch_array($result_sell)) {
						$temp_id8  			= $newArrays8['zestaw_id'];
						$temp_opis8			= $newArrays8['zestaw_opis'];
						$temp_kto8			= $newArrays8['zestaw_kto'];
						$temp_kiedy8		= $newArrays8['zestaw_kiedy'];

						// ile jest pozycji w zestawie
						$sql_sell8="SELECT zestawpozycja_fszcz_id, pozycja_status FROM $dbname.serwis_zestaw_szcz,$dbname.serwis_faktura_szcz WHERE (serwis_zestaw_szcz.zestawpozycja_zestaw_id = $temp_id8) and (serwis_faktura_szcz.pozycja_id=serwis_zestaw_szcz.zestawpozycja_fszcz_id)";
						$result_sell8 = mysql_query($sql_sell8, $conn) or die($k_b);
						$ile_all = mysql_num_rows($result_sell8);
						
						// ile pozycji z zestawu jest w tabeli hd_zgl_wymiany_podzespolow
						$sql_sell8="SELECT zestawpozycja_fszcz_id, wp_sprzedaz_fakt_szcz_id FROM $dbname.serwis_zestaw_szcz,$dbname_hd.hd_zgl_wymiany_podzespolow WHERE (serwis_zestaw_szcz.zestawpozycja_zestaw_id = $temp_id8) and (hd_zgl_wymiany_podzespolow.wp_sprzedaz_fakt_szcz_id=serwis_zestaw_szcz.zestawpozycja_fszcz_id)";
						$result_sell8 = mysql_query($sql_sell8, $conn) or die($k_b);
						$ile_wp = mysql_num_rows($result_sell8);
						
						// ile jest pozycji jest w zestawie (status = 5)
						$sql_sell8="SELECT zestawpozycja_fszcz_id, pozycja_status FROM $dbname.serwis_zestaw_szcz,$dbname.serwis_faktura_szcz WHERE (serwis_zestaw_szcz.zestawpozycja_zestaw_id = $temp_id8) and (serwis_faktura_szcz.pozycja_id=serwis_zestaw_szcz.zestawpozycja_fszcz_id) and (pozycja_status=5)";
						$result_sell8 = mysql_query($sql_sell8, $conn) or die($k_b);
						$ile_z_5 = mysql_num_rows($result_sell8);
						
						if (($ile_z_5 == $ile_all) && ($ile_all>0) && ($ile_wp==0)) {
							echo "<option value='$temp_id8'";
								echo ">$temp_opis8";
								echo " ($temp_kto8)";
								//	if ($temp_sn!='') { echo "(SN: $temp_sn)"; } else { echo "(SN:-)"; }
								//	if ($temp_uwagi!='') echo " | Uwagi: $temp_uwagi";
							echo "</option>\n";
						}
					}
					
					echo "</select>\n"; 
					echo "</td>";
				echo "</tr>";

				echo "</table>";				
				
			//} else { errorheader('Brak towarów na stanie'); }
			
	//	echo "Wybrane towary<br />";
	//	echo "<textarea style='background-color:transparent;font-size:9px;font-family:tahoma;' name=towary id=sell_towar_selectedItems  readonly cols=130 rows=2></textarea>";
			
		_td();
	_tr();
	
	echo "<tr id=wp_magazyn style='display:none'>";
		td("150;rt;");
		td_(";;");	

		$cat = $_REQUEST[cat];

		$sql_sell="SELECT pozycja_id, pozycja_nr_faktury,pozycja_nazwa, pozycja_sn, pozycja_typ, pozycja_uwagi FROM $dbname.serwis_faktura_szcz WHERE (belongs_to=$es_filia) and (pozycja_status='0') ORDER BY pozycja_typ ASC";
		// and (pozycja_rodzaj_sprzedazy<>'Usługa') 
		
		//echo $sql_sell;
		$result_sell = mysql_query($sql_sell, $conn) or die($k_b);
		$count_sell = mysql_num_rows($result_sell);
		
			//if (mysql_num_rows($result_sell)>0) { 
				
				//echo "<select id=c multiple=multiple name=c><option>1</option><option>2</option></select>";
				echo "<table width='auto'>";
				echo "<th class=center>Dostępne towary</th>";
				echo "<tr>";
					echo "<td class=center>";			
					
					echo "<select name=sell_towar_select id=sell_towar_select multiple=multiple size=8 onkeypress='return handleEnter(this, event);' onchange=\"addOptionP('sell_towar','sell_towar_select','selectedP','selectedPcount'); \">\n";
					//echo "<select name=sell_towar_select id=sell_towar_select multiple=multiple size=10 onkeypress='return handleEnter(this, event);' onClick=\"one2two('sell_towar_select,'sell_towar');\">\n";
					echo "<option value=''></option>";
					$opt_typ = '';
					$opt_end = '';
					$notfirst = 0;
					$cnt = 0;
					
					while ($newArrays = mysql_fetch_array($result_sell)) {
						$temp_id  			= $newArrays['pozycja_id'];
						$temp_nrfaktury		= $newArrays['pozycja_nr_faktury'];
						$temp_numer			= $newArrays['pozycja_numer'];
						$temp_nazwa			= $newArrays['pozycja_nazwa'];
						$temp_sn			= $newArrays['pozycja_sn'];
						$temp_typ			= $newArrays['pozycja_typ'];
						$temp_uwagi			= $newArrays['pozycja_uwagi'];
						$temp_rs			= $newArrays['pozycja_rodzaj_sprzedazy'];

						
						$r1a = mysql_query("SELECT wp_id FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_sprzedaz_fakt_szcz_id='$temp_id') and (belongs_to='$es_filia') and (wp_sprzet_active=1)", $conn_hd) or die($k_b);			
						list($jest_wp_id)=mysql_fetch_array($r1a);
		
						if ($jest_wp_id>0) { 
						
						} else {
							echo "<option value='$temp_id'";
							echo ">$temp_nazwa ";
							if ($temp_sn!='') { echo "(SN: $temp_sn)"; } else { echo "(SN:-)"; }
							if ($temp_uwagi!='') echo " | Uwagi: $temp_uwagi";
							echo "</option>\n";
						}
						
					}
					
					echo "</select>\n"; 
					echo "</td>";
				echo "</tr>";
				echo "<tr><td class=center><font color=green>Kliknij na pozycję, aby wybrać podzespół</font></td></tr>";
				
				if ($count_sell==0) { 
					echo "<tr><td class=center>";
					echo "<h2 style='font-weight:normal'>Brak towarów na stanie. Dodaj towary do magazynu lub wybierz podzespoły wg typu.</h2>";
					echo "</td></tr>";
				}
				
				echo "<tr><td class=center>&nbsp;</td></tr>";
				echo "<th class=center>Wybrane towary</th>";
				echo "<tr>";
					echo "<td class=center>";
					
						echo "<select name=sell_towar[] id=sell_towar size=8 multiple=multiple onchange=\"removeOptionP('sell_towar','selectedP','selectedPcount');\">";
						//echo "<select name=sell_towar[] id=sell_towar size=5 multiple=multiple onchange=\"two2one('sell_towar_select,'sell_towar');\">";
						echo "<option value=0 selected=selected>* wybranych towarów: 0 *</option>";
						echo "</select>";
					
						echo "<input type=hidden name=selectedP value='|#|' id=selectedP />";
						echo "<input type=hidden name=selectedPcount value='0' id=selectedPcount />";
					echo "</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<tr><td class=center><font color=red>Kliknij na pozycję, aby usunąć podzespół</font></td></tr>";
				echo "</table>";				
				
			//} else { errorheader('Brak towarów na stanie'); }
			
	//	echo "Wybrane towary<br />";
	//	echo "<textarea style='background-color:transparent;font-size:9px;font-family:tahoma;' name=towary id=sell_towar_selectedItems  readonly cols=130 rows=2></textarea>";
				
		_td();
	_tr();

	echo "<tr id=wp_typ_podzespolu style='display:'>";
		td("150;rt;");
		td_(";;");		
		$cat = $_REQUEST[cat];

		$sql_typy="SELECT rola_nazwa, rola_do_ewidencji FROM $dbname.serwis_slownik_rola ORDER BY rola_nazwa ASC";
	
		$result_typy = mysql_query($sql_typy, $conn) or die($k_b);

			if (mysql_num_rows($result_typy)>0) { 
				echo "<table width='auto'>";
				echo "<th class=center>Dostępne typy</th><th class=center>Wybrane typy</th>";
				echo "<tr>";
					echo "<td class=center>";
						echo "<select name=typ_podzespolu_source id=typ_podzespolu_source multiple=multiple size=8 onkeypress='return handleEnter(this, event);' onchange=\"addOptionT('typ_podzespolu','typ_podzespolu_source','selectedT','selectedTcount'); this.selectedIndex=0;\">\n";
						echo "<option value=''></option>";
					
						while ($newArrays = mysql_fetch_array($result_typy)) {
							$temp_nazwa  	= $newArrays['rola_nazwa'];
							$temp_rde 		= $newArray['rola_do_ewidencji'];
						
							echo "<option value='$temp_nazwa'";
							echo ">$temp_nazwa";
							echo "</option>\n";
						}
						echo "</select>\n"; 
					echo "</td>";
					
					echo "<td class=center>";
						echo "<select name=typ_podzespolu[] id=typ_podzespolu size=8 multiple=multiple onchange=\"removeOptionT('typ_podzespolu','selectedT','selectedTcount');\">";
						echo "<option value=0 selected=selected>* wybranych typów: 0 *</option>";
						echo "</select>";
					
						echo "<input type=hidden name=selectedT value='|#|' id=selectedT />";
						echo "<input type=hidden name=selectedTcount value='0' id=selectedTcount />";
					echo "</td>";
				echo "</tr>";
				echo "<tr>";
					echo "<td class=center><font color=green>Kliknij na pozycję, aby wybrać typ</font></td>";
					
					echo "<td class=center><font color=red>Kliknij na pozycję, aby usunąć typ</font></td>";
				echo "</table>";
			}
			
		//echo "	<br />";
		//echo "<textarea style='background-color:transparent;font-size:9px;font-family:tahoma;' name=typy id=typ_podzespolu_selectedItems readonly cols=130 rows=1></textarea>";
		
		_td();
	_tr();
	
/*	
if (($powiaz_wymiane_podzespolow_ze_sprzedaza==1) && ($allow_sell==1)) {	

	tr_();
		td("150;r;Powiąż ze sprzedażą towaru");
		td_(";;");		
		$cat = $_REQUEST[cat];

		$sql_sell="SELECT pozycja_id, pozycja_nr_faktury,pozycja_nazwa, pozycja_sn, pozycja_typ, pozycja_uwagi FROM $dbname.serwis_faktura_szcz WHERE (belongs_to=$es_filia) and  (pozycja_status='0') and (pozycja_rodzaj_sprzedazy<>'Usługa') ORDER BY pozycja_typ ASC";
		
		//echo $sql_sell;
		$result_sell = mysql_query($sql_sell, $conn) or die($k_b);

			if (mysql_num_rows($result)>0) { 
				
				echo "<select name=sell_towar onkeypress='return handleEnter(this, event);' onChange=\"document.getElementById('tuwagi').value = document.getElementById('tuwagi').value + 'Wymieniono: '+this.options[this.options.selectedIndex].text; \">\n";
				echo "<option value='0'>Nie wiąż ze sprzedażą</option>\n";
				
				while ($newArrays = mysql_fetch_array($result_sell)) {
					$temp_id  			= $newArrays['pozycja_id'];
					$temp_nrfaktury		= $newArrays['pozycja_nr_faktury'];
					$temp_numer			= $newArrays['pozycja_numer'];
					$temp_nazwa			= $newArrays['pozycja_nazwa'];
					$temp_sn			= $newArrays['pozycja_sn'];
					$temp_typ			= $newArrays['pozycja_typ'];
					$temp_uwagi			= $newArrays['pozycja_uwagi'];
					$temp_rs			= $newArrays['pozycja_rodzaj_sprzedazy'];
		
					echo "<option value='$temp_id'";
					if ($_REQUEST[sell_towar]==$temp_id) echo " SELECTED ";
					echo ">$temp_nazwa ";
					if ($temp_sn!='') { echo "(SN: $temp_sn)"; } else { echo "(SN:-)"; }
					if ($temp_uwagi!='') echo " | Uwagi: $temp_uwagi";
					
					echo "</option>\n";
					
				}
				
				echo "</select>\n"; 
				
				
			}
		_td();
	_tr();
} else {
	//echo "<input type=hidden name=sell_towar value=0>";
}
*/

	echo "<input type=hidden name=dodaj_do_ewidencji value='".$_REQUEST[dodaj_do_ewidencji]."'>";
	echo "<input type=hidden name=tstatus value='-1'>";
	echo "<input type=hidden name=id value=$id>";
	echo "<input type=hidden name=auto value='$auto'>";
	echo "<input type=hidden name=auto1 id=auto1 value='$auto'>";
	
	echo "<input type=hidden name=part value='$temp_nazwa'>";
	echo "<input type=hidden name=mmodel value='$temp_model'>";
	echo "<input type=hidden name=msn value='$temp_sn'>";
	echo "<input type=hidden name=mni value='$temp_ni'>";

/*
	echo "<input type=hidden name=uid value='$temp_id'>";
	echo "<input type=hidden name=szid value=$temp_szid>";
	echo "<input type=hidden name=tup1 value='$tup'>";	
	echo "<input type=hidden name=tup value='$tup'>";	
*/

	echo "<input type=hidden name=source value='wymiana-podzespolow'>";
	echo "<input type=hidden name=findpion value=1>";
	echo "<input type=hidden name=state value='empty'>";
	echo "<input type=hidden name=c_7 value='on'>";
	//echo "<input type=hidden name=c_2 value='on'>";
	echo "<input type=hidden name=wstecz value='1'>";

	echo "<input type=hidden name=ewid_id value='$_REQUEST[ewid_id]'>";
		
	echo "<input type=hidden id=tresc_zgl name=tresc_zgl value='".urlencode($_REQUEST[tresc_zgl])."'>";
	echo "<input type=hidden name=up_id value='".urlencode($_REQUEST[up_id])."'>";
	
	
	echo "<input type=hidden name=from value='$_REQUEST[from]'>";
	echo "<input type=hidden name=hd_nr value='$_REQUEST[hd_nr]'>";
	echo "<input type=hidden name=hd_podkategoria_nr value='$_REQUEST[hd_podkategoria_nr]'>";
	
	//echo ">>>>>$_REQUEST[upid]";
	/*
	   -1 - przyjęcie sprzętu uszkodzonego na serwis
		0 - naprawa we własnym zakresie
		1 - wysyłka do serwisu zewnetrznego
		2 - naprawa w serwisie na rynku lokalnym 
		3 - naprawiony
		5 - oddany
	*/
	tbl_empty_row(2);
	endtable();
	
	startbuttonsarea("right");
	
	//echo "$_REQUEST[tresc_zgl]";
	
	echo "<span style='float:left'>";
	if ($_REQUEST[tresc_zgl]=='') {
		addownlinkbutton2("'Wybierz nowy sprzęt'","button","button","z_naprawy_przyjmij.php");
		echo "<input type=hidden name=tresc_zgl value=''>";
	}

	if ($_REQUEST[noback]!='1') 
		if ($_REQUEST[from]=='hd') {
			echo "<input type=button class=buttons value='Wróć do poprzedniego widoku' onClick='history.go(-1);'>";
		}
	
	//echo "$_REQUEST[up]";
	
	if ($_REQUEST[auto]==1) {
		echo "<input class=buttons type=button onClick=window.location.href='z_wymiana_wybor_z_ewidencji.php?id=$id&tup=".urlencode($_REQUEST[tup])."&up=".urlencode($_REQUEST[up])."&tnazwa=".urlencode($_REQUEST[tnazwa])."&tmodel=".urlencode($_REQUEST[tmodel])."&tsn=".urlencode($_REQUEST[tsn])."&tni=".urlencode($_REQUEST[tni])."&tstatus1=".$_REQUEST[tstatus1]."&sz=".$_REQUEST[sz]."&unr=".$_REQUEST[unr]."&cat=".urlencode($_REQUEST[tnazwa])."&tuwagi=".urlencode($uwagi)."&auto=0&tresc_zgl=".urlencode($_REQUEST[tresc_zgl])."&upid=".urlencode($_REQUEST[upid])."&tup1=".urlencode($_REQUEST[tup1])."&new_upid=".$_REQUEST[new_upid]."&popraw_dane=1&ewid_id=".$_REQUEST[ewid_id]."&from=$_REQUEST[from]&hd_nr=$_REQUEST[hd_nr]&tuwagi=".urlencode($_REQUEST[tuwagi])."&sell_towar=".$_REQUEST[sell_towar]."' value='Popraw dane o sprzęcie'>";
	}
	echo "</span>";

if (($_REQUEST[ewid_id]!='') && ($_REQUEST[popraw_dane]==1)) {	
	echo "<input class=border0 type=checkbox name=popraw_w_ewidencji id=popraw_w_ewidencji ";
	//if ($_REQUEST[popraw_w_ewidencji]=='on') echo " checked ";
	if ($_REQUEST[popraw_dane]=='1') echo " checked ";
	echo ">";
	
	echo "<a href=# class=normalfont onClick=\"if (document.getElementById('popraw_w_ewidencji').checked) { document.getElementById('popraw_w_ewidencji').checked=false; } else { document.getElementById('popraw_w_ewidencji').checked=true; } return false; \">";
	
	echo "<font color=red>&nbsp;Uaktualnij ewidencję sprzętu *</font></a>&nbsp;&nbsp;&nbsp;&nbsp;";
}

if ($_REQUEST[dodaj_do_ewidencji]==1) {
	echo "<input class=border0 type=checkbox name=dodaj_sprzet_do_ewidencji id=dodaj_sprzet_do_ewidencji ";
	if ($_REQUEST[dodaj_sprzet_do_ewidencji]=='on') echo " checked ";
	echo ">";
	
	echo "<a id=a_dodaj_sprzet_do_ewidencji href=# class=normalfont onClick=\"if (document.getElementById('dodaj_sprzet_do_ewidencji').checked) { document.getElementById('dodaj_sprzet_do_ewidencji').checked=false; } else { document.getElementById('dodaj_sprzet_do_ewidencji').checked=true; } return false; \">";
	
	echo "<font color=red>&nbsp;Dodaj wpisany sprzęt do ewidencji *</font></a>&nbsp;&nbsp;&nbsp;&nbsp;";
}

	addbuttons("zapisz","anuluj");
	endbuttonsarea();
	echo "<input type=hidden name=tuser value='$currentuser'>";
	echo "<input type=hidden name=upid value='".urlencode($_REQUEST[upid])."'>";
	echo "<input type=hidden name=popraw_dane value='".$_REQUEST[popraw_dane]."'>";
	
_form();

if ($_GET[dodaj_do_ewidencji]=='1') {
	if (($_GET[cat]!='Czytnik') && ($_GET[cat]!='Drukarka') && ($_GET[cat]!='Hub') && ($_GET[cat]!='Komputer') && ($_GET[cat]!='Monitor') && ($_GET[cat]!='Notebook') && ($_GET[cat]!='Router') && ($_GET[cat]!='Serwer') && ($_GET[cat]!='Switch') && ($_GET[cat]!='UPS')) {
		?><script>
			document.getElementById('dodaj_sprzet_do_ewidencji').style.display='none';
			document.getElementById('a_dodaj_sprzet_do_ewidencji').style.display='none';
			
			document.getElementById('gw1').style.display='none';
			document.getElementById('gw2').style.display='none';
			document.getElementById('gw3').style.display='none';
			document.getElementById('gw4').style.display='none';
			document.getElementById('gw5').style.display='none';
			
		</script><?php
	} else {
		?><script>
			document.getElementById('dodaj_sprzet_do_ewidencji').style.display='';
			document.getElementById('a_dodaj_sprzet_do_ewidencji').style.display='';
	
			document.getElementById('gw1').style.display='';
			document.getElementById('gw2').style.display='';
			document.getElementById('gw3').style.display='';
			document.getElementById('gw4').style.display='';
			document.getElementById('gw5').style.display='';
			
		</script><?php
	}
	
}
?>
<script>HideWaitingMessage();</script>
<?php
}

?>

<script>

if (document.getElementById('wp_pw').value=='0') { 
	$('#wp_typ_podzespolu').show(); 
	$('#wp_magazyn').hide(); 
	$('#wp_magazyn_zestaw').hide(); 
	document.getElementById('sell_towar').selectedIndex=0; 
}

if (document.getElementById('wp_pw').value=='1') { 
	$('#wp_typ_podzespolu').hide(); 
	$('#wp_magazyn').show(); 
	$('#wp_magazyn_zestaw').hide(); 
} 

if (document.getElementById('wp_pw').value=='2') { 
	$('#wp_typ_podzespolu').hide(); 
	$('#wp_magazyn').hide(); 
	$('#wp_magazyn_zestaw').show(); 
}

/*
$(function() {
	$("#sell_towar").toChecklist({
		addSearchBox : true,
		searchBoxText : 'wyszukaj towar',
		showSelectedItems : true,
		submitDataAsArray : true,
		showCheckboxes:true,
		preferIdOverName : false
	});	
	
	$("#typ_podzespolu").toChecklist({
		addSearchBox : true,
		searchBoxText : 'wyszukaj typ podzespołu',
		showSelectedItems : true,
		submitDataAsArray : true,
		showCheckboxes:true,
		preferIdOverName : false
	});	
	
	$("#wp_typ_podzespolu").hide();
});
*/
</script>

</body>
</html>