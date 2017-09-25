<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
$_SESSION[wykonaj_edycje_zestawu]=0;
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
	$unr='';
	$litery=array('a','b','c','d','e','f','g','h','i','j');
	for ($q=1;$q<26;$q++) { $unr.= rand(0,9); $unr.=$litery[rand(0,9)]; }

//	$lock_table = mysql_query("LOCK TABLES serwis_sprzedaz READ");
	
	//if (mysql_query("GET_LOCK('".$es_filia."',10)",$conn)) {
//	$start_transaction = mysql_query("START TRANSACTION",$conn);
	
	$dddd = Date('Y-m-d H:i:s');
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

	$umowanr = $_REQUEST[tumowa];

	
	$zestaw_sql = "SELECT zestawpozycja_fszcz_id FROM $dbname.serwis_zestaw_szcz WHERE zestawpozycja_zestaw_id=$_REQUEST[zid]";
	$wynik = mysql_query($zestaw_sql,$conn) or die($k_b);

	while ($newarray = mysql_fetch_array($wynik)) {
		$temp_zestaw_fszcz_id = $newarray['zestawpozycja_fszcz_id'];
		list($temp1_nrf, $temp1_nazwa, $temp1_sn, $temp1_cn, $temp1_cno,$temp1_typ)=mysql_fetch_array(mysql_query("SELECT pozycja_nr_faktury,pozycja_nazwa, pozycja_sn, pozycja_cena_netto, pozycja_cena_netto_odsprzedazy, pozycja_typ FROM $dbname.serwis_faktura_szcz WHERE pozycja_id=$temp_zestaw_fszcz_id LIMIT 1",$conn));
		$cena_cr = $temp1_cn;
		$cenaodsp_cr = $temp1_cno;
		
		list($temp1_sid)=mysql_fetch_array(mysql_query("SELECT sprzedaz_id FROM $dbname.serwis_sprzedaz WHERE sprzedaz_pozycja_id=$temp_zestaw_fszcz_id LIMIT 1",$conn));
		
		$sql_t1 = "UPDATE $dbname.serwis_sprzedaz SET sprzedaz_data='$_POST[tdata]', sprzedaz_data_operacji='$dddd', sprzedaz_umowa_nazwa='$umowanr', sprzedaz_pion_nazwa = '$pionnazwa',sprzedaz_up_nazwa='$nazwaup', sprzedaz_rodzaj='$_POST[trodzaj]' WHERE sprzedaz_id=$temp1_sid";
		
		//$sql_t1 = "UPDATE $dbname.serwis_sprzedaz SET ('',$temp_zestaw_fszcz_id,'$temp1_nazwa','$temp1_sn','$cena_cr','$cenaodsp_cr','','$_POST[tdata]','$dddd','$currentuser','$umowanr','$pionnazwa','$nazwaup','".nl2br($_POST[tuwagi])."',$es_filia,$temp1_nrf,'$_POST[trodzaj]','1','$temp1_typ','','$unr')";
		
		$wstaw_sql = mysql_query($sql_t1, $conn) or die($k_b);
		$sql1a="UPDATE $dbname.serwis_faktura_szcz SET pozycja_status = 1, pozycja_datasprzedazy = '$_POST[tdata]' WHERE pozycja_id = $temp_zestaw_fszcz_id";
		$aktualizuj_fszcz = mysql_query($sql1a,$conn) or die($k_b);
	}
	//$zmien_status_zestawu = "UPDATE $dbname.serwis_zestawy SET zestaw_status=1 WHERE zestaw_id=$_REQUEST[zid] LIMIT 1";
	//$aktualizuj_zestaw = mysql_query($zmien_status_zestawu,$conn) or die($k_b);
	
	//$commit_transaction = mysql_query("COMMIT");
	//$unlock_table = mysql_query("UNLOCK TABLES");
	
	?><script> opener.location.reload(true); self.close();
	//self.location.href='utworz_protokol.php?source=towary-sprzedaz&tdata=<?php echo $_POST[tdata]; ?>&new_upid=<?php echo $_POST[new_upid]; ?>&c_7=on&wykonane_czynnosci=<?php echo urlencode($_POST[tuwagi]); ?>&edit_zestaw=<?php echo $_REQUEST[zid]; ?>&obzp=1'; 
	</script><?php
} else {
	errorheader("Sprzedaż towarów/zestawów na dzień ".$_POST[tdata]." jest niemożliwa - wygenerowano już raport ze sprzedaży");	
	startbuttonsarea("right");
	addbuttons("wstecz","zamknij");
	endbuttonsarea();
	
	}
*/

} else {
pageheader("Edycja daty sprzedaży i komórki obrotu magazynowego towarów z zestawu");
$sql9r="SELECT zestaw_opis, zestaw_kto, zestaw_kiedy FROM $dbname.serwis_zestawy WHERE (zestaw_id=$zid) LIMIT 1";
$result9r = mysql_query($sql9r, $conn) or die($k_b);
while ($newArray9r = mysql_fetch_array($result9r)) {
  $temp_opis  	= $newArray9r['zestaw_opis'];
  $temp_kto  	= $newArray9r['zestaw_kto'];
  $temp_kiedy  	= $newArray9r['zestaw_kiedy'];
}
startbuttonsarea("center");
echo "Nazwa zestawu : <b>$temp_opis</b><br />Utworzony przez : <b>$temp_kto, $temp_kiedy</b>";
endbuttonsarea();
errorheader("Wprowadzone zmiany zostaną zastosowane do wszystkich elementów wybranego zestawu");

starttable();
echo "<form name=addt action=utworz_protokol.php method=POST>";
tbl_empty_row();
	tr_();
		td("150;r;Sprzedaż towarów / usług dla");
		td_(";;");
			$sql44="SELECT * FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE belongs_to=$es_filia and (serwis_komorki.up_pion_id=serwis_piony.pion_id)  and (serwis_komorki.up_active=1) ORDER BY serwis_piony.pion_nazwa,up_nazwa";
			$result44 = mysql_query($sql44, $conn) or die($k_b);
			$count_rows = mysql_num_rows($result44);
			$i = 0;
			echo "<select class=wymagane name=new_upid onkeypress='return handleEnter(this, event);' onchange=\"reload1_obrot(this.form);\" ";
			if ($_REQUEST[hd_zgl_nr]!='') { echo " disabled "; }
			echo " >\n"; 					 				
			echo "<option value=''>Wybierz UP / komórkę z listy...";
			while ($newArray44 = mysql_fetch_array($result44)) {
				$temp_id  		= $newArray44['up_id'];
				$temp_nazwa		= $newArray44['up_nazwa'];
				$temp_pion_id	= $newArray44['up_pion_id'];
				$temp_umowa_id	= $newArray44['up_umowa_id'];
				$sql444="SELECT pion_nazwa FROM $dbname.serwis_piony WHERE pion_id=$temp_pion_id LIMIT 1";
				$result444=mysql_query($sql444,$conn) or die($k_b);
				$wynik = mysql_fetch_array($result444);
				$pionnazwa = $wynik['pion_nazwa'];
				echo "<option value=$temp_id";
				if ($_REQUEST[new_upid]==$temp_id) { 
				echo " SELECTED ";
				}
				echo ">$pionnazwa $temp_nazwa</option>\n"; 
			}
			echo "</select>\n"; 
			
			if ($_REQUEST[hd_zgl_nr]!='') { echo "<input type=hidden name=new_upid id=new_upid value='$_REQUEST[new_upid]'>"; }
			echo "<input type=hidden name=hd_zgl_nr value='$_REQUEST[hd_zgl_nr]'>";
		_td();
	_tr();
	$dddd = Date('Y-m-d');
	
	tr_();
		td("150;rt;Data sprzedaży");
		td_(";;");
		//f ($_REQUEST[tdata]!='') { $data_sprzedazy = $_REQUEST[tdata]; } else { $data_sprzedazy = $dddd; }
		
			$data_sprzedazy = Date('Y-m-d');
			if ($_REQUEST[tdata]!='') $data_sprzedazy = $_REQUEST[tdata];
			if ($_REQUEST[sdata]!='') $data_sprzedazy = $_REQUEST[sdata];
	
			echo "<input class=wymagane size=10 type=text maxlength=10 id=tdata name=tdata value='$data_sprzedazy' onkeypress=\"return handleEnter(this, event);\" onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\" onBlur=\"reload1_obrot(this.form); this.focus();\" />";
			echo "&nbsp;<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img src=img/cal.gif width=16 height=16 border=0></a>";
			if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('tdata').value='".Date('Y-m-d')."'; reload1_obrot(this.form); return false;\">";
			// sprawdzenie czy nie zamknięto sprzedaży dla danej daty
			
			if ($_REQUEST[tdata]!='') {
				if (check_date($_REQUEST[tdata])) {
				
					$rok = substr($_REQUEST[tdata],0,4);
					$miesiac = substr($_REQUEST[tdata],5,2);
					
					$sql66 = "SELECT sr_miesiac FROM $dbname.serwis_sprzedaz_raport WHERE ((belongs_to=$es_filia) and (sr_rok=$rok) and (sr_miesiac=$miesiac))";
				
					$result = mysql_query($sql66, $conn) or die($k_b);
					$count_rows11 = mysql_num_rows($result);
					
					if ($count_rows11>0) {
						echo "<br /><br />";
						errorheader("<font style='font-weight:normal'>Sprzedaż z wybraną datą jest niemożliwa. Zatwierdzono już raport za okres <b>$rok-$miesiac</b></font>");
						
					}
				} else {
					echo "<br /><font color=red><b>Niepoprawnie wpisana data sprzedaży</b></font>";
					$count_rows11=11;
				}
			}			
		_td();
	_tr();
	tbl_empty_row();
	if ($_GET[new_upid]!='') {
		echo "<tr>";
		
			$r40 = mysql_query("SELECT up_umowa_id FROM $dbname.serwis_komorki WHERE (up_id='$_REQUEST[new_upid]') LIMIT 1", $conn) or die($k_b);
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
			echo "<select class=wymagane id=tumowa name=tumowa onkeypress='return handleEnter(this, event);' onchange=\"reload1_obrot(this.form);\" >\n"; 					 				
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
	
	tr_();
		td("150;rt;Rodzaj sprzedaży");
		td_(";;");

		//echo "<b>sprzedaż towaru</b>";

		$sql="SELECT zestawpozycja_id,zestawpozycja_fszcz_id,zestawpozycja_zestaw_id FROM $dbname.serwis_zestaw_szcz WHERE zestawpozycja_zestaw_id=$zid";
		$result=mysql_query($sql, $conn) or die($k_b);	
		$o = 1;
		
		$bez_rs = 0;
		
		echo "<table>";
		echo "<tr><th class=center width=20>LP</th><th>Element zestawu (SN)<br />Rodzaj sprzedaży</th>";
		
		$accessLevels = array("9");
		if(array_search($es_prawa, $accessLevels)>-1){
			echo "<th width=30 class=center>Opcje</th>";
		}
		
		echo "</tr>";
		while (list($temp_id,$temp_fszcz_id,$temp_zestawid)=mysql_fetch_array($result)) {
			list($temp_towarid, $temp_nazwatowaru,$temp_sntowaru,$temp_uwagitowar,$temp_rs,$temp_status)=mysql_fetch_array(mysql_query("SELECT pozycja_id, pozycja_nazwa, pozycja_sn, pozycja_uwagi, pozycja_rodzaj_sprzedazy,pozycja_status FROM $dbname.serwis_faktura_szcz WHERE pozycja_id=$temp_fszcz_id LIMIT 1",$conn));
		
			if ($temp_rs=='') { $rs_opis = '-'; $bez_rs++; } else { $rs_opis = $temp_rs; }
			if ($temp_sntowaru=='') { $sn_opis = '-'; } else { $sn_opis = $temp_sntowaru; }
			
			tbl_tr_highlight($o);
			echo "<td class=center>$o</td><td>$temp_nazwatowaru (SN:$sn_opis)<br /><font color=grey>$rs_opis</font>";
			
			if ($temp_rs=='') echo "&nbsp;&nbsp;<a title='Nie określono rodzaju sprzedaży na poziomie wprowadzania faktury. Jeżeli chcesz go zmienić - skontaktuj się z osobą, która ma uprawnienia do zmian w pozycjach na fakturach. Domyślny rodzaj sprzedaży: Towar' class=normalfont style='border:1px solid red; color:red' href=#>&nbsp;?&nbsp;</a>";
			echo "</td>";
			
			$accessLevels = array("9");
			if(array_search($es_prawa, $accessLevels)>-1){
				if (($temp_status==1) || ($temp_status==-1) || ($temp_status==5)) {	
					echo "<td class=center><a title=' Popraw pozycję $temp_nazwatowaru na fakturze'><input class=imgoption type=image src=img/edit.gif  onclick=\"newWindow(800,600,'e_faktura_pozycja.php?id=$temp_towarid&trodzaj=".urlencode($temp_rs)."&allow_change_rs=1'); return false;\"></a></td>";
				}
			}
				
			echo "</tr>";
			$o++;
		}
		echo "</table>";	
		
		if ($bez_rs>0) {
			if ($bez_rs==1) $prefix = 'Jeden element zestawu ma ';
			if ($bez_rs==2) $prefix = 'Dwa elementy zestawu mają ';
			if ($bez_rs==3) $prefix = 'Trzy elementy zestawu mają ';
			if ($bez_rs==4) $prefix = 'Cztery elementy zestawu mają ';
			if ($bez_rs==5) $prefix = 'Pięć elementów zestawu ma ';
			if ($bez_rs>5) $prefix = 'Więcej niż 5 elementów zestawu ma ';

			errorheader("<font style='font-weight:normal'>$prefix nieokreślony rodzaj sprzedaży.<br />Te elementy zestawu zostaną sprzedane jako <b>Towar</b>.</font>");
		}
		
		echo "<input type=hidden name=trodzaj value=''>";
	/*
			echo "<select class=wymagane name=trodzaj onkeypress='return handleEnter(this, event);' onchange=\"reload1_obrot(this.form);\">\n"; 			
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
*/

/*			echo "<option value='Towar'>sprzedaż towaru</option>\n"; 
			echo "<option value='Materiał'>sprzedaż materiału do wykonania usługi</option>\n"; 
			echo "<option value='Usługa'>sprzedaż usługi</option>\n"; 			
*/
			echo "</select>\n"; 
		_td();
	_tr();

	tbl_empty_row();
	echo "<tr style=display:none>";
		td("150;rt;Elementy zestawu");
		td_(";;");
				
			$sql="SELECT zestawpozycja_id,zestawpozycja_fszcz_id,zestawpozycja_zestaw_id FROM $dbname.serwis_zestaw_szcz WHERE zestawpozycja_zestaw_id=$zid";
			$result=mysql_query($sql, $conn) or die($k_b);	

			$wc = '';
			while (list($temp_id,$temp_fszcz_id,$temp_zestawid)=mysql_fetch_array($result)) {
				list($temp_towarid, $temp_nazwatowaru,$temp_sntowaru,$temp_uwagitowar)=mysql_fetch_array(mysql_query("SELECT pozycja_id, pozycja_nazwa, pozycja_sn FROM $dbname.serwis_faktura_szcz WHERE pozycja_id=$temp_fszcz_id LIMIT 1",$conn));
				$wc.=" ".$temp_nazwatowaru."";
				if ($temp_sntowaru!='') $wc.=" (SN : ".$temp_sntowaru.")";
				$wc.=",";
			}
			$wc=substr($wc,1,strlen($wc)-2);
			$d= Date('d');
			$m= Date('m');
			$r= Date('Y');

			if ($_GET[tuwagi]!='') { $uwagi = $_GET[tuwagi]; } else { $uwagi = $wc; }
			echo "<textarea style='background-color:transparent' name=tuwagi cols=55 rows=6 readonly>".$uwagi."</textarea>";
		_td();
	_tr();

	/*
	   -1 - faktura nie zatwierdzona
		0 - towar dostępny
		1 - towar sprzedany
	*/
	
	tr_(); echo "<td class=right colspan=2>&nbsp;</td>"; _tr();
	endtable();
	echo "<input type=hidden name=tprzesylka value=0>"; 	
	echo "<input type=hidden name=tstatus value='1'>";
	echo "<input type=hidden name=ttyp value='$temp_ttyp'>";
	echo "<input type=hidden name=zid value=$_REQUEST[zid]>";
	
	//echo "<input type=hidden name=c_7 value='on'>";

/*	
	echo "<input type=hidden name=tidf value='$f'>";
	echo "<input type=hidden name=tcenaodsp value='$temp_cenaodsp'>";
	echo "<input type=hidden name=tcena value='$temp_cenanetto9'>";
	echo "<input type=hidden name=tnazwa value='$temp_nazwa9'>";
	echo "<input type=hidden name=tsn value='$temp_sn9'>";	
*/	

	echo "<input type=hidden name=source value='towary-sprzedaz'>";
	echo "<input type=hidden name=findpion value=1>";
	echo "<input type=hidden name=state value='empty'>";
	echo "<input type=hidden name=obzp value='1'>";
	echo "<input type=hidden name=zestaw value=1>";

	echo "<input type=hidden name=edit_zestaw value=1>";
	echo "<input type=hidden name=wstecz value=''>";

	echo "<input type=hidden name=c_3 value='on'>";
	echo "<input type=hidden id=readonly name=readonly value='$_REQUEST[readonly]'>";
	
	//	echo "<input type=hidden name=sid value='$_REQUEST[tid]'>";	

	startbuttonsarea("right");
	if ($umowy_ids!='') {
		echo "<input class=buttons type=submit name=submit id=dalej value=Dalej "; 
		if ($count_rows11>0) { echo " style='display:none' "; }
		echo ">";
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
  frmvalidator.addValidation("new_upid","dontselect=0","Nie wybrano komórki");  
  frmvalidator.addValidation("tdata","req","Nie podano daty sprzedaży");
 // frmvalidator.addValidation("trodzaj","dontselect=0","Nie wybrano rodzaju sprzedaży");  
</script>
<?php } ?>
</body>
</html>