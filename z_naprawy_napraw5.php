<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');

?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php
$_SESSION[protokol_dodany_do_bazy]=0;
$_SESSION[unr_session]='';
$_SESSION[wykonaj_naprawy_zwrot]=0;
$_SESSION[wykonaj_sprzedaz]=0;

$result = mysql_query("SELECT naprawa_id,naprawa_nazwa,naprawa_model,naprawa_sn,naprawa_status,naprawa_sprzet_zastepczy_id,naprawa_uwagi,naprawa_ni,naprawa_pobrano_z FROM $dbname.serwis_naprawa WHERE (naprawa_id=$id) LIMIT 1", $conn) or die($k_b);

list($temp_id,$temp_nazwa,$temp_model,$temp_sn,$temp_status,$temp_szid,$temp_uwagi,$temp_ni,$temp_pobrano_z)=mysql_fetch_array($result);

$sql_up = "SELECT up_id, up_pion_id FROM $dbname.serwis_komorki WHERE (up_nazwa='$temp_pobrano_z') and (belongs_to=$es_filia) LIMIT 1";
$wynik = mysql_query($sql_up, $conn) or die($k_b);
$dane_up = mysql_fetch_array($wynik);
$temp_up_id = $dane_up['up_id'];
$temp_pion_id = $dane_up['up_pion_id'];

// nazwa pionu z id pionu
$sql_get_pion = "SELECT pion_nazwa FROM $dbname.serwis_piony WHERE (pion_id='$temp_pion_id') LIMIT 1";
$wynik_get_pion = mysql_query($sql_get_pion, $conn) or die($k_b);
$dane_get_pion = mysql_fetch_array($wynik_get_pion);
$temp_pion_nazwa = $dane_get_pion['pion_nazwa'];
			
pageheader("Zwrot sprzętu z naprawy do klienta");
infoheader("<b>Sprzęt klienta: </b><br /><br />Sprzęt pobrano z: <b>".$temp_pion_nazwa." ".$temp_pobrano_z."</b><br />Typ sprzętu: <b>".$temp_nazwa." ".$temp_model."</b><br />SN: <b>".$temp_sn."</b>, NI: <b>".$temp_ni."</b>");
?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();

starttable();

//echo "$_REQUEST[new_upid]";

$result = mysql_query("SELECT up_id FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (up_active=1) and (belongs_to=$es_filia) and (serwis_komorki.up_nazwa='$temp_pobrano_z') LIMIT 1", $conn) or die($k_b);
list($temp_new_up_id)=mysql_fetch_array($result);

echo "<form name=edu action=utworz_protokol.php method=POST>";

echo "<input type=hidden name=part value='$temp_nazwa'>";
echo "<input type=hidden name=mmodel value='$temp_model'>";
echo "<input type=hidden name=msn value='$temp_sn'>";
echo "<input type=hidden name=mni value='$temp_ni'>";
echo "<input type=hidden name=muwagi value='$temp_uwagi'>";

echo "<input type=hidden name=new_upid value=$temp_new_up_id>";

$dddd = Date('Y-m-d');
tbl_empty_row();
	tr_();
		td("120;r;Nowy status sprzętu");
		td_(";l");
			echo "<input type=hidden name=ew_id value=$temp_ew_id>";
			
			$result = mysql_query("SELECT sn_id,sn_nazwa FROM $dbname.serwis_sposob_naprawy WHERE (sn_id='5') LIMIT 1", $conn) or die($k_b);
			list($temp_id1,$temp_nazwa1)=mysql_fetch_array($result);
			echo "<b>".$temp_nazwa1."</b>";			
			echo "<input type=hidden name=tstatus1 value=5>";
			
		_td();
		td(";;;");
	_tr();
	
	//if ($_REQUEST[trodzaj]=='Usługa') {
				
				$sql="SELECT pozycja_id,pozycja_nazwa,pozycja_sn,pozycja_uwagi FROM $dbname.serwis_faktura_szcz,$dbname.serwis_faktury WHERE ((serwis_faktura_szcz.belongs_to=$es_filia) and (serwis_faktura_szcz.pozycja_status='0') and (serwis_faktury.faktura_id=serwis_faktura_szcz.pozycja_nr_faktury) and (serwis_faktura_szcz.pozycja_typ='Usługa'))";
				$sql.=" ORDER BY pozycja_nazwa ASC";

				$result = mysql_query($sql,$conn) or die($k_b);
				
				//$result = mysql_query("SELECT naprawa_id, naprawa_nazwa, naprawa_model, naprawa_sn, naprawa_uwagi, naprawa_sprzet_zastepczy_id, naprawa_ew_id FROM $dbname.serwis_naprawa WHERE (belongs_to=$es_filia) and (naprawa_status=3) and (naprawa_pobrano_z='$_REQUEST[tup]') ORDER BY naprawa_data_odbioru_z_serwisu DESC", $conn) or die($k_b);
				
		if (mysql_num_rows($result)>0) { 
				tbl_empty_row();
				echo "<tr>";
				echo "<td width=150 class=righttop>Naprawa powiązana<br />ze sprzedażą usługi</td>";
				echo "<td>";
			if ($allow_sell==1) {
				echo "<select name=ps onkeypress='return handleEnter(this, event);' onchange=\"reload_naprawy(this.form);\">\n";
				echo "<option value=''>Naprawa nie jest powiązana ze sprzedażą usługi</option>\n";
				while (list($temp_id2,$temp_nazwa2,$temp_sn2,$temp_uwagi2)=mysql_fetch_array($result)) {
					echo "<option value='$temp_id2'";
					if ($_REQUEST[ps]==$temp_id2) {
						echo " SELECTED";
						$sprzet_zast_id = $temp_sprzet_zast;
						$naprawa_id5 = $temp_id2;
					}
					
					$uuu = substr($temp_uwagi2,0,20).'...';
					
					echo ">$temp_nazwa2 (SN: $temp_sn2) | $uuu</option>\n"; 
				}
				echo "</select>\n";
			} else {
				echo "<input type=hidden name=ps value=''>";
				echo "<b>Naprawa nie jest powiązana ze sprzedażą usługi (<font color=red>brak uprawnień</font>)</b>";
			}
				echo "</td>";
				echo "</tr>";
				
		} else {
		
		}
	//}		
	
	if ($_REQUEST[ps]!='') {
		echo "<tr><td></td><td><font color=red>Wybranie powiązania spowoduje sprzedaż usługi na konto wybranej komórki</b></font></td></tr>";
		tbl_empty_row();
	
		echo "<tr>";
		echo "<td class=right>Sprzedaż sprzętu dla</td>";
		
		if ($_REQUEST[sz]=='0') {
			$temp1_up = $_REQUEST[tup];
		} else {
			$histsql = "SELECT historia_up FROM $dbname.serwis_historia WHERE historia_magid='$_REQUEST[sz]' ORDER BY historia_data DESC LIMIT 1";
			$histwynik = mysql_query($histsql,$conn) or die($k_b);
			list($temp1_up)=mysql_fetch_array($histwynik);
		}
			
		echo "<td><b>$temp1_up</b></td>";

		echo "</tr>";
		
		echo "<tr>";
		echo "<td width=150 class=right>Data sprzedaży&nbsp;";
		echo "</td>";

		echo "<td><input class=wymagane size=10 type=text maxlength=10 id=tdata name=tdata value='$dddd' onkeypress='return handleEnter(this, event);' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\">";
		echo "<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img src=img/cal.gif width=16 height=16 border=0></a>";
		if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('tdata').value='".Date('Y-m-d')."'; return false;\">";
		echo "</td>";
		echo "</tr>";
	
		echo "<tr>";
		echo "<td width=150 class=right>Rodzaj sprzedaży</td>";
			
		echo "<td>";	
		echo "<b>sprzedaż usługi</b>";
/*		
		echo "<select class=wymagane name=trodzaj onkeypress='return handleEnter(this, event);' onchange=\"reload1_obrot(this.form)\">\n"; 					 				
		echo "<option value=''>Wybierz rodzaj sprzedaży...";
				
		echo "<option value='Towar'";
		if ($_REQUEST[trodzaj]=='Towar') echo " SELECTED";
		echo ">sprzedaż towaru</option>\n"; 
		
		echo "<option value='Materiał'";
		if ($_REQUEST[trodzaj]=='Materiał') echo " SELECTED";
		echo ">sprzedaż materiału do wykonania usługi</option>\n"; 
		
		echo "<option value='Usługa'";
		if ($_REQUEST[trodzaj]=='Usługa') echo " SELECTED";
		echo ">sprzedaż usługi</option>\n"; 
		
		echo "</select>\n"; 
*/
		echo "<input type=hidden name=trodzaj value='Usługa'>";
		echo "</td>";
	echo "</tr>";
	
	}
	
// if ($_REQUEST[sz]!='0') {
	
	tbl_empty_row();	
	tr_();
		td("200;r;Sprzęt serwisowy podstawiony");
		td_(";;");	
		
			$result1 = mysql_query("SELECT naprawa_nazwa,naprawa_sprzet_zastepczy_id FROM $dbname.serwis_naprawa WHERE (naprawa_id=$id) LIMIT 1", $conn) or die($k_b);
			list($temp_typ,$temp_sprzet_zastepczy_id)=mysql_fetch_array($result1);

			$result = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn FROM $dbname.serwis_magazyn WHERE (belongs_to=$es_filia) and (magazyn_status=1) and (magazyn_nazwa='$temp_typ') ORDER BY magazyn_model", $conn) or die($k_b);
			
			if (mysql_num_rows($result)>0) { 

				while (list($temp_id3,$temp_nazwa3,$temp_model3,$temp_sn3)=mysql_fetch_array($result)) {
					if ($temp_sprzet_zastepczy_id==$temp_id3) { 
						$nazwa = $temp_nazwa3." ".$temp_model3." (SN:".$temp_sn3.")";
					} else $nazwa = '-';
				}
				
			} 
			
			echo "<b>$nazwa</b>";
			echo "<input type=hidden name=nazwasz value='$nazwa'>";			
		_td();
	_tr();
		tbl_empty_row();
	tr_();
		if ($_REQUEST[sz]=='0') {
			$temp1_up = $_REQUEST[tup];
		} else {
			$histsql = "SELECT historia_up FROM $dbname.serwis_historia WHERE historia_magid='$_REQUEST[sz]' ORDER BY historia_data DESC LIMIT 1";
			$histwynik = mysql_query($histsql,$conn) or die($k_b);
			list($temp1_up)=mysql_fetch_array($histwynik);
		}
			//echo "$histsql";
			
			
		td("200;r;Pobierz sprzęt serwisowy<br />z <b>$temp1_up</b>");
		td_(";;");
		

			
			$result1 = mysql_query("SELECT naprawa_nazwa,naprawa_sprzet_zastepczy_id FROM $dbname.serwis_naprawa WHERE (naprawa_id=$id) LIMIT 1", $conn) or die($k_b);
			list($temp_typ,$temp_sprzet_zastepczy_id)=mysql_fetch_array($result1);

			//echo "<br />SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn FROM $dbname.serwis_magazyn,serwis_naprawa WHERE (serwis_magazyn.belongs_to=$es_filia) and (serwis_magazyn.magazyn_status=1) and (serwis_magazyn.magazyn_nazwa='$temp_typ') and (naprawa_pobrano_z='$temp1_up') and (serwis_magazyn.magazyn_id=serwis_naprawa.naprawa_sprzet_zastepczy_id) ORDER BY serwis_magazyn.magazyn_model";
			//echo "SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn FROM $dbname.serwis_magazyn,$dbname.serwis_naprawa WHERE (((serwis_magazyn.belongs_to=$es_filia) and (serwis_magazyn.magazyn_status=1) and (serwis_magazyn.magazyn_nazwa='$temp_typ') and (naprawa_pobrano_z='$temp1_up') and (serwis_magazyn.magazyn_id=serwis_naprawa.naprawa_sprzet_zastepczy_id)) or ((serwis_naprawa.naprawa_hd_zgl_id='$_REQUEST[hd_zgl_nr]') and (serwis_magazyn.belongs_to=$es_filia) and (serwis_magazyn.magazyn_status=1) and (naprawa_pobrano_z='$temp1_up'))) ORDER BY serwis_magazyn.magazyn_model";
			
			$result = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn FROM $dbname.serwis_magazyn,$dbname.serwis_naprawa WHERE (((serwis_magazyn.belongs_to=$es_filia) and (serwis_magazyn.magazyn_status=1) and (serwis_magazyn.magazyn_nazwa='$temp_typ') and (naprawa_pobrano_z='$temp1_up') and (serwis_magazyn.magazyn_id=serwis_naprawa.naprawa_sprzet_zastepczy_id)) or ((serwis_naprawa.naprawa_hd_zgl_id='$_REQUEST[hd_zgl_nr]') and (serwis_magazyn.belongs_to=$es_filia) and (serwis_magazyn.magazyn_status=1) and (naprawa_pobrano_z='$temp1_up') and (serwis_magazyn.magazyn_id=serwis_naprawa.naprawa_sprzet_zastepczy_id))) ORDER BY serwis_magazyn.magazyn_model", $conn) or die($k_b);

			if (mysql_num_rows($result)>0) { 
				echo "<select name=sz onkeypress='return handleEnter(this, event);'>\n";			 				
				echo "<option value='0'";
				if ($_REQUEST[sz]=='0') echo " SELECTED";
				echo ">Nic nie pobieraj z UP</option>\n";
				while (list($temp_id4,$temp_nazwa4,$temp_model4,$temp_sn4)=mysql_fetch_array($result)) {
					echo "<option value='$temp_id4'";
					
					if (($temp_sprzet_zastepczy_id==$temp_id4) && ($_REQUEST[sz]!=0)) { 
						echo " SELECTED";
						$nazwa = $temp_nazwa4." ".$temp_model4." (SN:".$temp_sn4.")";
					}

					if ($_REQUEST[sz]==$temp_id4) { 
						echo " SELECTED";
						$nazwa = $temp_nazwa4." ".$temp_model4." (SN:".$temp_sn4.")";
					}
					
					echo ">$temp_nazwa4 ($temp_model4, $temp_sn4)</option>\n"; 
				}
				
				echo "<input type=hidden name=zastepczy value='$nazwa'>";
				
				echo "</select>\n"; 
			} else 
				if ($clear_typ==1) { 
					echo "<b>wybierz typ sprzętu z listy</b>"; 
				} else echo "<b>brak dostępnego sprzętu serwisowego tego typu na stanie</b>";
			
		_td();
	_tr();
//} else {
//	echo "<input type=hidden name=nazwasz value=''>";	
//	echo "<input type=hidden name=zastepczy value=''>";
//	echo "<input type=hidden name=sz value='-1'>";
//}
tbl_empty_row();	
endtable();

if ($_REQUEST[hd_zgl_nr]!='') {

	$result_k = mysql_query("SELECT zgl_czy_powiazane_z_wymiana_podzespolow FROM $dbname_hd.hd_zgloszenie WHERE zgl_id=$_REQUEST[hd_zgl_nr] LIMIT 1", $conn) or die($k_b);
	list($czy_powiazane)=mysql_fetch_array($result_k);

	if ($czy_powiazane==1) {
	
		okheader('Podzespoły wymienione w ramach tego zgłoszenia');

		starttable();
		$i = 0;
		echo "<tr><th></th><th>Nazwa towaru</th><th>Numer seryjny</th><th>Rodzaj</th><th class=center>Status</th></tr>";
		//tbl_empty_row();

			$r1a = mysql_query("SELECT wp_sprzedaz_fakt_szcz_id,pozycja_nazwa,pozycja_sn,pozycja_status,pozycja_typ,pozycja_rodzaj_sprzedazy FROM $dbname_hd.hd_zgl_wymiany_podzespolow, $dbname.serwis_faktura_szcz WHERE (hd_zgl_wymiany_podzespolow.wp_zgl_id=$_REQUEST[hd_zgl_nr]) and (hd_zgl_wymiany_podzespolow.belongs_to='$es_filia') and (hd_zgl_wymiany_podzespolow.wp_sprzet_active=1) and (hd_zgl_wymiany_podzespolow.wp_sprzedaz_fakt_szcz_id=serwis_faktura_szcz.pozycja_id)", $conn_hd) or die($k_b);

			$wybranych = 0;
			while (list($wp_fszczid,$poz_nazwa,$poz_sn, $poz_status, $poz_typ, $poz_rodz)=mysql_fetch_array($r1a)) {
			
				tbl_tr_highlight($i);
				
					td_("20;;");
						$opis_pozycji = $poz_nazwa;
						if ($poz_sn!='') $opis_pozycji.= " (SN: ".$poz_sn.")";	
						$jest = strpos($_REQUEST['lista'],'|'.$i.'|');
						echo "<input class=border0 type=checkbox ";

						if (strlen($jest)>0) {
							echo " checked=checked";
							$wybranych++;
						}
						
						echo " name=markpoz$i id=markpoz$i value='$opis_pozycji'/>";
					_td();
					td_(";;");
						echo $poz_nazwa;
					_td();
					td_(";;");
						echo $poz_sn;
					_td();
					td_(";;");
						echo $poz_rodz;
					_td();
								
					td_(";c;");
						if ($poz_status=='0') echo "na stanie";
						if ($poz_status=='1') echo "sprzedany";
						if ($poz_status=='9') echo "zraportowany";
					_td();

				_tr();
				//echo "<b>$wp_fszczid -  (SN: $poz_sn) - $poz_status - $poz_typ - $poz_rodz</b><br />";
				$i++;
			}
			
			$r1a = mysql_query("SELECT wp_typ_podzespolu FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (hd_zgl_wymiany_podzespolow.wp_zgl_id=$_REQUEST[hd_zgl_nr]) and (hd_zgl_wymiany_podzespolow.belongs_to='$es_filia') and (hd_zgl_wymiany_podzespolow.wp_sprzet_active=1) and (wp_typ_podzespolu<>'')", $conn_hd) or die($k_b);

			while (list($wp_typ)=mysql_fetch_array($r1a)) {
			
				tbl_tr_highlight($i);
								
					td_("20;;");
						$opis_pozycji = $wp_typ;
						
						echo "<input class=border0 type=checkbox ";					
						echo " name=markpoz$i id=markpoz$i value='$opis_pozycji'/>";
					_td();
					td_(";;");
						echo "$opis_pozycji";
					_td();
					td_(";;");
						echo "-";
					_td();
					td_(";;");
						echo "-";
					_td();
					td_(";;");
						echo "Towar nie powiązany z pozycją w magazynie";
					_td();					
				_tr();
				$i++;
				
			}			
			
			
			
		echo "<tr><td colspan=4 class=left>";

		echo "<input class=imgoption type=image src=img/obsluga_seryjna.png>";
		echo "<input class=buttons type=button onClick=\"MarkCheckboxes('zaznacz'); \" value='Zaznacz'>";
		echo "<input class=buttons type=button onClick=\"MarkCheckboxes('odznacz'); \" value='Odznacz'>";
		echo "<input class=buttons type=button onClick=\"MarkCheckboxes('odwroc'); \"value='Odwróć zaznaczenie'>";
		
		echo "<br /><font color=red>&nbsp;Zaznacz pozycje, które chcesz ująć w protokole</font></td></tr>";
		//tbl_empty_row();	
		endtable();	
		echo "<br />";
		echo "<input type=hidden name=wp value='1'>";
		echo "<input type=hidden name=wp_count value=$i>";		
		echo "<input type=hidden name=wp_count_sel value=$wybranych>";
	} else {
		echo "<input type=hidden name=wp value='0'>";
		echo "<input type=hidden name=wp_count value=0>";
		echo "<input type=hidden name=wp_count_sel value=0>";
	}
	
	if ($_REQUEST[dodajwymianepodzespolow]=='1') {

		echo "<input type=hidden name=_wp_opis value='".$temp_nazwa." ".$temp_model."'>";
		echo "<input type=hidden name=_wp_sn value='".$temp_sn."'>";
		echo "<input type=hidden name=_wp_ni value='".$temp_ni."'>";
			
		$r4 = mysql_query("SELECT zgl_szcz_unikalny_numer FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$_REQUEST[hd_zgl_nr]') and (belongs_to=$es_filia) and (zgl_szcz_widoczne=1) ORDER BY zgl_szcz_nr_kroku DESC LIMIT 1", $conn_hd) or die($k_b);
		list($temp_wp_unique)=mysql_fetch_array($r4);

		echo "<input type=hidden name=_wp_unique value='".$temp_wp_unique."'>";
		
	}
	
}



echo "<input type=hidden name=id value='$id'>";
echo "<input type=hidden name=uid value='$upid'>";
echo "<input type=hidden name=szid value=$temp_szid>";
echo "<input type=hidden name=tup1 value='$tup'>";	
echo "<input type=hidden name=tup value='$tup'>";

echo "<input type=hidden name=from value='$_REQUEST[from]'>";

echo "<input type=hidden name=source value='naprawy-zwrot'>";
echo "<input type=hidden name=findpion value=1>";
echo "<input type=hidden name=state value='empty'>";
echo "<input type=hidden name=c_4 value='on'>";
echo "<input type=hidden name=c_3 value='on'>";
echo "<input type=hidden name=wstecz value='1'>";

//echo "<input type=hidden name=part value='$temp_nazwa'>";
//echo "<input type=hidden name=mmodel value='$temp_model'>";
//echo "<input type=hidden name=msn value='$temp_sn'>";
echo "<input type=hidden name=ewid_id value='$_REQUEST[ewid_id]'>";

echo "<input type=hidden name=hd_zgl_nr id=hd_zgl_nr value='$_REQUEST[hd_zgl_nr]'>";	
echo "<input type=hidden name=cs id=cs value='$_REQUEST[cs]'>";	
echo "<input type=hidden name=upid id=upid value='$_REQUEST[upid]'>";	
//echo "<input type=hidden name=new_upid id=new_upid value='$_REQUEST[new_upid]'>";	

echo "<input type=hidden name=dodajwymianepodzespolow id=dodajwymianepodzespolow value='$_REQUEST[dodajwymianepodzespolow]'>";

startbuttonsarea("right");
addbuttons("dalej","anuluj");
endbuttonsarea();
_form();
?>

<?php 

if ($_REQUEST[ps]!='') { 

?>

<script language="JavaScript">
var cal1 = new calendar1(document.forms['edu'].elements['tdata']);
	cal1.year_scroll = true;
	cal1.time_comp = false;
</script>

<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("edu");
  
//  frmvalidator.addValidation("tpion","dontselect=0","Nie wybrano pionu");  
//  frmvalidator.addValidation("tup","dontselect=0","Nie wybrano komórki");  
  frmvalidator.addValidation("tdata","req","Nie podano daty sprzedaży");
//  frmvalidator.addValidation("tumowa","dontselect=0","Nie wybrano umowy");  
//  frmvalidator.addValidation("trodzaj","dontselect=0","Nie wybrano rodzaju sprzedaży");  

</script>

<?php 

} 

?>
<script>HideWaitingMessage();</script>
</body>
</html>