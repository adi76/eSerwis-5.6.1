<?php 
include_once('header.php'); 
include('cfg_helpdesk.php'); 

if (($_REQUEST[up_id]=='') && ($_REQUEST[typ_id]=='')) $nr=0;
if (($_REQUEST[up_id]!='') && ($_REQUEST[typ_id]=='')) $nr=1;
if (($_REQUEST[up_id]=='') && ($_REQUEST[typ_id]!='')) $nr=0;
if (($_REQUEST[up_id]!='') && ($_REQUEST[typ_id]!='')) $nr=2;

if ($_REQUEST[auto]=='1') $nr=5;

echo "<body OnLoad=document.forms[0].elements[".$nr."].focus();>";	

function HexToNormal($string) {
	$string = urlencode($string);
	// 					ą		ć		ę			ł		ń		ó		ś		ź			ż
	$_hex = array ('%25u0105', '%25u0107','%25u0119','%25u0142','%25u0144','%25uFFFD','%25u015B','%25u017A','%25u017C', '%25u0104', '%25u0106', '%25u0118', '%25u0141', '%25u0143', '%25u00D3', '%25u015A', '%25u0179', '%25u017B','%F3','%D3' );
	$_normal = array ('ą','ć','ę','ł','ń','ó','ś','ź','ż','Ą','Ć','Ę','Ł','Ń','Ó','Ś','Ź','Ż','ó','Ó');
	
	$string = str_replace($_hex, $_normal, $string);
	return urldecode($string); 
}; 


$_SESSION['naprawa_id_for_zgloszenie_nr_'.$_REQUEST[hd_nr].'']=0;

$_SESSION[protokol_dodany_do_bazy]=0;

$_SESSION[numer_id_dopisanego_do_tabeli_serwis_historia]=0;
$_SESSION[numer_id_dopisanego_do_tabeli_serwis_naprawa]=0;

$_SESSION[wykonaj_naprawy_przyjecie]=0;

if ($submit) { 
/*	$_POST=sanitize($_POST);
	if ($_POST[tuwagi]!='') { $tuwagisa='1'; } else $tuwagisa='0';
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
if ($_REQUEST[clear]=='1') {
	?>
		<script>
		ClearCookie('tuwagi_<?php echo $_REQUEST[ewid_id]; ?>');
		</script>
		<?php
}

$cat=$_POST['cat'];
if (($_REQUEST[from]=='hd') && ($_REQUEST[hd_nr]!='')) {
	pageheader("Przyjęcie uszkodzonego sprzętu do serwisu <font color=red>| powiązane ze zgłoszeniem nr <b>".$_REQUEST[hd_nr]."</b></font>");
} else { 
	pageheader("Przyjęcie uszkodzonego sprzętu do serwisu");
}

?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();

if (($Podst_Inf_o_zgl) && ($_REQUEST[hd_nr]!='')) include_once('hd_inf_podstawowe.php');

starttable();
echo "<form name=addt action=utworz_protokol.php method=POST>";
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
				$result44 = mysql_query("SELECT serwis_komorki.up_id, serwis_komorki.up_nazwa, serwis_piony.pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) and (serwis_komorki.up_id=$_REQUEST[new_upid]) ORDER BY serwis_piony.pion_nazwa,up_nazwa", $conn) or die($k_b);
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

				if ($_REQUEST[hd_podkategoria_nr]=='') {
					$result = mysql_query("SELECT rola_id,rola_nazwa FROM $dbname.serwis_slownik_rola WHERE (rola_do_ewidencji=1) ORDER BY rola_nazwa", $conn) or die($k_b);
				} else {

					if ($_REQUEST[hd_podkategoria_nr]=='3') $result = mysql_query("SELECT rola_id,rola_nazwa FROM $dbname.serwis_slownik_rola WHERE ((rola_id=1) or (rola_id=18) or (rola_id=7) or (rola_id=2)) and (rola_do_ewidencji=1) ORDER BY rola_nazwa", $conn) or die($k_b);
					
					if ($_REQUEST[hd_podkategoria_nr]=='4') $result = mysql_query("SELECT rola_id,rola_nazwa FROM $dbname.serwis_slownik_rola WHERE ((rola_id=1) or (rola_id=18) or (rola_id=7) or (rola_id=2)) and (rola_do_ewidencji=1) ORDER BY rola_nazwa", $conn) or die($k_b);
					
					if (($_REQUEST[hd_podkategoria_nr]=='2') || ($_REQUEST[hd_podkategoria_nr]=='5')) {
					
						$result = mysql_query("SELECT rola_id,rola_nazwa FROM $dbname.serwis_slownik_rola WHERE (rola_do_ewidencji=1) ORDER BY rola_nazwa", $conn) or die($k_b);
						
					}	
					if ($_REQUEST[hd_podkategoria_nr]=='7') $result = mysql_query("SELECT rola_id,rola_nazwa FROM $dbname.serwis_slownik_rola WHERE ((rola_id=1) or (rola_id=18) or (rola_id=2)) and (rola_do_ewidencji=1) ORDER BY rola_nazwa", $conn) or die($k_b);
					
					if ($_REQUEST[hd_podkategoria_nr]=='9') $result = mysql_query("SELECT rola_id,rola_nazwa FROM $dbname.serwis_slownik_rola WHERE (rola_do_ewidencji=1) and (rola_id>2) and (rola_id<>18) ORDER BY rola_nazwa", $conn) or die($k_b);
					
					if ($_REQUEST[hd_podkategoria_nr]=='0') $result = mysql_query("SELECT rola_id,rola_nazwa FROM $dbname.serwis_slownik_rola WHERE (((rola_id=10) or (rola_id=11) or (rola_id=17) or (rola_id=28)) and (rola_do_ewidencji=1)) ORDER BY rola_nazwa", $conn) or die($k_b);
					
				}
				
					td_(";;");
						echo "<select class=wymagane name=tnazwa id=tnazwa onkeypress='return handleEnter(this, event);' onchange=\"reload_z_naprawy_uszkodzony(this.form)\">\n";
						echo "<option value=''>Wybierz z listy...";
						while (list($temp_id,$temp_nazwa)=mysql_fetch_array($result)) {
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
						echo "<input type=hidden name=tnazwa id=tnazwa value='$tnazwa'>";
						echo "<b>$_REQUEST[tnazwa]</b>";
					_td();
				}
			
	_tr();
	tr_();
		td("150;r;Model");

		if ($auto==1) {
				if ($_REQUEST[tmodel]=='') {
					td_(";;");
						echo "<input class=wymagane size=35 maxlength=30 type=text name=tmodel>";
					_td();
				} else
				{
					td_(";;");
						echo "<input tabindex=-1 type=hidden name=tmodel value='$_REQUEST[tmodel]'>";
						echo "<b>$_REQUEST[tmodel]</b>";
					_td();
				}
			} else {	
				td_(";;");
					
					echo "<input class=wymagane size=35 maxlength=30 type=text name=tmodel id=tmodel onkeypress='return handleEnter(this, event);' onBlur=\"SprawdzSlownik(this.value);\" value='$_REQUEST[tmodel]'>";
					
					if ($_REQUEST[dodaj_do_ewidencji]==1) 
						echo "&nbsp;<font color=red><a id=gw3 title=' Można dodać nowy sprzęt do bazy ewidencji '>*</a></font>";
						
					if (($_REQUEST[ewid_id]!='') && ($_REQUEST[popraw_dane]==1)) 
						echo "&nbsp;<font color=red><a id=infomodel title=' Można dokonać uaktualnienia tej wartości w bazie ewidencji sprzętu '>*</a></font>";
						
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
						echo "<input class=wymagane size=35 maxlength=30 type=text name=tsn onBlur=\"ClearCookie('tsn_".$_REQUEST[ewid_id]."'); SetCookie('tsn_".$_REQUEST[ewid_id]."',this.value);\" value='";
						
						if ($_REQUEST[tsn]!='') {
							echo cleanup(cleanup(($_REQUEST[tsn])));
						} else {
							if ($_COOKIE['tsn_'.$_REQUEST[ewid_id].'']!=null) {
								echo HexToNormal($_COOKIE['tsn_'.$_REQUEST[ewid_id].'']);
							}	
						}
						
						echo "' >";
					_td();
				} else {
					td_(";;");
						echo "<input tabindex=-1 type=hidden name=tsn value='$_REQUEST[tsn]'>";
						echo "<b>$_REQUEST[tsn]</b>";
					_td();
				}
		} else {			
				td_(";;");
					echo "<input class=wymagane size=35 maxlength=30 type=text name=tsn onkeypress='return handleEnter(this, event);' value='";

					if ($_REQUEST[tsn]!='') {
						echo cleanup(cleanup(($_REQUEST[tsn])));
					} else {
						if (($_COOKIE['tsn_'.$_REQUEST[ewid_id].'']!=null) && ($_REQUEST[ewid_id]!='')) {
							echo HexToNormal($_COOKIE['tsn_'.$_REQUEST[ewid_id].'']);
						}	
					}					
					//echo "value='$_REQUEST[tsn]' ";
					echo "' onBlur=\"ClearCookie('tsn_".$_REQUEST[ewid_id]."'); SetCookie('tsn_".$_REQUEST[ewid_id]."',this.value);\">";
					
					if ($_REQUEST[dodaj_do_ewidencji]==1) 
						echo "&nbsp;<font color=red><a id=gw4 title=' Można dodać nowy sprzęt do bazy ewidencji '>*</a></font>";
						
					if (($_REQUEST[ewid_id]!='') && ($_REQUEST[popraw_dane]==1)) 	
						echo "&nbsp;<font color=red><a title=' Można dokonać uaktualnienia tej wartości w bazie ewidencji sprzętu '>*</a></font>";
				_td();
				}
	_tr();
	tr_();
		td("150;r;Numer inwentarzowy");
		if ($_REQUEST[tni]=='') $_REQUEST[tni]='-';
		
		if ($auto==1) {
			td_(";;");
				echo "<input tabindex=-1 type=hidden name=tni value='$_REQUEST[tni]'>";
				echo "<b>$_REQUEST[tni]</b>";
			_td();
		} else
			{			
				td_(";;");
					echo "<input size=23 maxlength=20 type=text name=tni onkeypress='return handleEnter(this, event);'  value='$_REQUEST[tni]'>";
					if ($_REQUEST[dodaj_do_ewidencji]==1) 
						echo "&nbsp;<font color=red><a id=gw5 title=' Można dodać nowy sprzęt do bazy ewidencji '>*</a></font>";
						
					if (($_REQUEST[ewid_id]!='') && ($_REQUEST[popraw_dane]==1))  
						echo "&nbsp;<font color=red><a title=' Można dokonać uaktualnienia tej wartości w bazie ewidencji sprzętu '>*</a></font>";
				_td();
			}
	_tr();
	tr_();
		td("150;rt;Uwagi / Opis uszkodzenia");
		td_(";;");
//			echo "<textarea name=tuwagi id=tuwagi cols=50 rows=6 onBlur=\"createCookie('przyjecie_drukarki_$ewid_id',''+nl2br(this.value)+'',100);\">".cleanup(cleanup(($_REQUEST[tuwagi])))."</textarea>";
			echo "<textarea name=tuwagi id=tuwagi cols=50 rows=6 onBlur=\"ClearCookie('tuwagi_".$_REQUEST[ewid_id]."'); SetCookie('tuwagi_".$_REQUEST[ewid_id]."',this.value);\">";
			
			if ($_REQUEST[tuwagi]!='') {
				echo cleanup(cleanup(($_REQUEST[tuwagi])));
			} else {
				if ($_COOKIE['tuwagi_'.$_REQUEST[ewid_id].'']!=null) {
					echo HexToNormal($_COOKIE['tuwagi_'.$_REQUEST[ewid_id].'']);
				}	
			}
			
			echo "</textarea>";
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
	tr_();
		td("150;r;Dostępny sprzęt serwisowy");
		td_(";;");		
		$cat = $_REQUEST[cat];
			if ($auto==1) {
				//echo "SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni FROM $dbname.serwis_magazyn WHERE (belongs_to=$es_filia) and ((magazyn_status=0) or ((magazyn_status=2) and (magazyn_osoba_rezerwujaca='$currentuser'))) and (magazyn_nazwa='$_REQUEST[tnazwa]') ORDER BY magazyn_model";
				
				$result = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni FROM $dbname.serwis_magazyn WHERE (belongs_to=$es_filia) and ((magazyn_status=0) or ((magazyn_status=2) and (magazyn_osoba_rezerwujaca='$currentuser'))) and (magazyn_nazwa='$_REQUEST[tnazwa]') ORDER BY magazyn_model", $conn) or die($k_b);
			} else {
				$result = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni FROM $dbname.serwis_magazyn WHERE (belongs_to=$es_filia) and ((magazyn_status=0) or ((magazyn_status=2) and (magazyn_osoba_rezerwujaca='$currentuser'))) and (magazyn_nazwa='$cat') ORDER BY magazyn_model", $conn) or die($k_b);
			}
			
			if ($_REQUEST[hd_nr]!='') {
				$result44 = mysql_query("SELECT zgl_sprzet_serwisowy_id FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id=$_REQUEST[hd_nr]) and (zgl_widoczne=1)", $conn) or die($k_b);
				list($temp_ss_id) = mysql_fetch_array($result44);	
			}
			
			if ((mysql_num_rows($result)>0) || ($temp_ss_id>0)) { 
			
			echo "<select name=sz onkeypress='return handleEnter(this, event);'>\n";			 							
			
			// jeżeli już jest powiązane zgłoszenie z przekazaniem sprzętu serwisowego
				if ($_REQUEST[hd_nr]>0) {				
					$result44 = mysql_query("SELECT zgl_sprzet_serwisowy_id FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id=$_REQUEST[hd_nr]) and (zgl_widoczne=1)", $conn) or die($k_b);
					list($temp_ss_id) = mysql_fetch_array($result44);
					//echo "SELECT magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni FROM $dbname.serwis_magazyn WHERE (belongs_to=$es_filia) and ((magazyn_status=1) or ((magazyn_status=2) and (magazyn_osoba_rezerwujaca='$currentuser'))) and (magazyn_id='$temp_ss_id')";
					
					if ($temp_ss_id>0) {
	//echo "<option value=''>SELECT magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni FROM $dbname.serwis_magazyn WHERE (belongs_to=$es_filia) and ((magazyn_status=1) or ((magazyn_status=2) and (magazyn_osoba_rezerwujaca='$currentuser'))) and (magazyn_id='$temp_ss_id')</option>\n";				
						$result44 = mysql_query("SELECT magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni FROM $dbname.serwis_magazyn WHERE (belongs_to=$es_filia) and ((magazyn_status=1) or ((magazyn_status=2) and (magazyn_osoba_rezerwujaca='$currentuser'))) and (magazyn_id='$temp_ss_id')", $conn) or die($k_b);
						list($temp_mnazwa, $temp_mmodel, $temp_msn, $temp_mni) = mysql_fetch_array($result44);
						echo "<option value='$temp_ss_id' SELECTED>$temp_mnazwa $temp_mmodel | SN:$temp_msn | NI:$temp_mni (już powiązany ze zgłoszeniem)</option>\n";
					} else {
						echo "<option value='0'>Nic nie przekazuj na UP</option>\n";
						while (list($temp_id,$temp_nazwa,$temp_model,$temp_sn,$temp_ni)=mysql_fetch_array($result)) {
							echo "<option value='$temp_id'";
							if ($_REQUEST[sz]==$temp_id) echo " SELECTED";
							if ($temp_ni=='') $temp_ni='-';
							echo ">$temp_nazwa $temp_model | SN:$temp_sn | NI:$temp_ni</option>\n"; 
						}
						echo "</select>\n"; 				
					}
					
					if ($temp_ss_id>0) {
						echo "<input type=hidden name=PNZSS value=1>";
					} else { 
						echo "<input type=hidden name=PNZSS value=0>";
					}
					
				} else {
					echo "<option value='0'>Nic nie przekazuj na UP</option>\n";
						while (list($temp_id,$temp_nazwa,$temp_model,$temp_sn,$temp_ni)=mysql_fetch_array($result)) {
							echo "<option value='$temp_id'";
							if ($_REQUEST[sz]==$temp_id) echo " SELECTED";
							if ($temp_ni=='') $temp_ni='-';
							echo ">$temp_nazwa $temp_model | SN:$temp_sn | NI:$temp_ni</option>\n"; 
						}
					echo "</select>\n";
				
				}
			

			} else {
				if ($_REQUEST[clear_typ]==1) { 
					echo "<b>wybierz typ sprzętu z listy</b>"; 
				} else {
			
					echo "<b>brak dostępnego sprzętu serwisowego tego typu na stanie</b>";
					echo "<input type=hidden name=sz value=0>";
					echo "<input type=hidden name=ni value=0>";
				}
			}
			
		_td();
	_tr();
	
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

	echo "<input type=hidden name=source value='naprawy-przyjecie'>";
	echo "<input type=hidden name=findpion value=1>";
	echo "<input type=hidden name=state value='empty'>";
	echo "<input type=hidden name=c_1 value='on'>";
	if ($temp_ss_id>0) {
		echo "<input type=hidden name=c_2 value=''>";
	} else {
		echo "<input type=hidden name=c_2 value='on'>";
	}
	echo "<input type=hidden name=wstecz value='1'>";

	echo "<input type=hidden name=ewid_id value='$_REQUEST[ewid_id]'>";
		
	echo "<input type=hidden id=tresc_zgl name=tresc_zgl value='".urlencode($_REQUEST[tresc_zgl])."'>";
	echo "<input type=hidden name=up_id value='".urlencode($_REQUEST[up_id])."'>";
	
	
	echo "<input type=hidden name=from value='$_REQUEST[from]'>";
	echo "<input type=hidden name=hd_nr value='$_REQUEST[hd_nr]'>";
	echo "<input type=hidden name=hd_podkategoria_nr value='$_REQUEST[hd_podkategoria_nr]'>";
	
	
	echo "<input type=hidden name=hd_zgl_nr value='$_REQUEST[hd_nr]'>";
	
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

	if ($_REQUEST[from]=='hd') {
		if ($_REQUEST[noback]!=1)
			echo "<input type=button class=buttons value='Wróć do poprzedniego widoku' onClick='history.go(-1);'>";
	}
	
	//echo "$_REQUEST[up]";
	
	if ($_REQUEST[auto]==1) {
		echo "<input class=buttons type=button onClick=window.location.href='z_naprawy_uszkodzony.php?id=$id&tup=".urlencode($_REQUEST[tup])."&up=".urlencode($_REQUEST[up])."&tnazwa=".urlencode($_REQUEST[tnazwa])."&tmodel=".urlencode($_REQUEST[tmodel])."&tsn=".urlencode($_REQUEST[tsn])."&tni=".urlencode($_REQUEST[tni])."&tstatus1=".$_REQUEST[tstatus1]."&sz=".$_REQUEST[sz]."&unr=".$_REQUEST[unr]."&cat=".urlencode($_REQUEST[tnazwa])."&tuwagi=".urlencode($uwagi)."&auto=0&tresc_zgl=".urlencode($_REQUEST[tresc_zgl])."&upid=".urlencode($_REQUEST[upid])."&tup1=".urlencode($_REQUEST[tup1])."&new_upid=".$_REQUEST[new_upid]."&popraw_dane=1&ewid_id=".$_REQUEST[ewid_id]."&from=$_REQUEST[from]&hd_nr=$_REQUEST[hd_nr]&hd_zgl_nr=$_REQUEST[hd_zgl_nr]' value='Popraw dane o sprzęcie'>";
	}
	echo "</span>";

if (($_REQUEST[ewid_id]!='') && ($_REQUEST[popraw_dane]==1)) {	
	echo "<input class=border0 type=checkbox checked=checked name=popraw_w_ewidencji id=popraw_w_ewidencji ";
	if ($_REQUEST[popraw_w_ewidencji]=='on') echo " checked ";
	echo ">";
	
	echo "<a href=# class=normalfont onClick=\"if (document.getElementById('popraw_w_ewidencji').checked) { document.getElementById('popraw_w_ewidencji').checked=false; } else { document.getElementById('popraw_w_ewidencji').checked=true; } return false; \">";
	
	echo "<font color=red>&nbsp;Uaktualnij ewidencję sprzętu *</font></a>&nbsp;&nbsp;&nbsp;&nbsp;";
}

if ($_REQUEST[dodaj_do_ewidencji]==1) {
	echo "<input class=border0 type=checkbox name=dodaj_sprzet_do_ewidencji id=dodaj_sprzet_do_ewidencji ";
	if ($_REQUEST[dodaj_sprzet_do_ewidencji]=='on') echo " checked ";
	echo ">";
	
	echo "<a id=a_dodaj_sprzet_do_ewidencji href=# class=normalfont onClick=\"if (document.getElementById('dodaj_sprzet_do_ewidencji').checked) { document.getElementById('dodaj_sprzet_do_ewidencji').checked=false; } else { document.getElementById('dodaj_sprzet_do_ewidencji').checked=true; } return false;\">";
	
	echo "<font color=red>&nbsp;Dodaj wpisany sprzęt do ewidencji *</font></a>&nbsp;&nbsp;&nbsp;&nbsp;";
}

	addbuttons("dalej","anuluj");
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

/*
echo "UP : $_REQUEST[up]<br />";
echo "TUP : $_REQUEST[tup]<br />";
echo "TUP1 : $_REQUEST[tup1]<br />";
*/

if ($auto==0) { ?>
		
	<script language="JavaScript" type="text/javascript">
		var frmvalidator  = new Validator("addt");
		<?php if ($_REQUEST[tresc_zgl]=='') { 
			if ($_REQUEST[popraw_dane]!=1) {	
			?> frmvalidator.addValidation("new_upid","dontselect=0","Nie wybrałeś komórki z której pobrano sprzęt"); 
		<?php } 
			} ?>
		<?php 
			if ($_REQUEST[popraw_dane]!=1) {
		?>
			frmvalidator.addValidation("tnazwa","dontselect=0","Nie wybrałeś typu sprzętu");  
		<?php } ?>
		
		<?php if (($_REQUEST[cat]=='Czytnik') || ($_REQUEST[cat]=='Drukarka') || ($_REQUEST[cat]=='Monitor') || ($_REQUEST[cat]=='Serwer') || ($_REQUEST[cat]=='Komputer') || ($_REQUEST[cat]=='Serwer')) { ?>
			
		<?php } else { ?>
			frmvalidator.addValidation("tmodel","req","Nie podałeś modelu sprzętu");	  
		<?php } ?>
		frmvalidator.addValidation("tsn","req","Nie podałeś numeru seryjnego sprzętu");
	</script>
	<script>SprawdzSlownik(document.getElementById('tmodel').value);</script>
<?php } ?>
<?php if ($auto==1) { ?>
	<script language="JavaScript" type="text/javascript">
		var frmvalidator  = new Validator("addt");
		<?php if ($tnazwa=='') { ?>frmvalidator.addValidation("tnazwa","dontselect=0","Nie wybrałeś typu sprzętu2");  <?php } ?>
		<?php if ($tmodel=='') { ?>frmvalidator.addValidation("tmodel","req","Nie podałeś modelu sprzętu"); <?php } ?>
		<?php if ($tsn=='') { ?>frmvalidator.addValidation("tsn","req","Nie podałeś numeru seryjnego sprzętu"); <?php } ?>
	</script>
<?php } ?>
<?php } ?>
<script>HideWaitingMessage();</script>
</body>
</html>