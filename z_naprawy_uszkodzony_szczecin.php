<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">	
<?php 
if ($submit) { 
	$_POST=sanitize($_POST);
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
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else {	
			?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php	
			}
} else {
$cat=$_POST['cat'];
pageheader("Przyjęcie uszkodzonego sprzętu do serwisu");
starttable();
echo "<form name=addt action=$PHP_SELF method=POST>";
tbl_empty_row(2);
	tr_();
		td("200;r;Sprzęt pobrano z");
		if ($id!=0) {
			$result = mysql_query("SELECT * FROM $dbname.serwis_ewidencja WHERE (ewidencja_id=$id) LIMIT 1", $conn) or die($k_b);
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
				$tnazwa			= $etypnazwa;

				if ($ts=='d') {
					$tmodel		= $edo;
					$tsn		= $edsn;
					$tni		= $edni;			
				}
				if ($ts=='k') {
					$tmodel		= $ekopis;
					$tsn		= $eksn;
					$tni		= $enizest;
				}
				if ($ts=='m') {
					$tmodel		= $emo;
					$tsn		= $emsn;
					$tni		= $enizest;
					$tnazwa		= 'Monitor';
				}
				if ($ts=='i') {
					$tmodel		= $ekopis;
					$tsn		= $eksn;
					$tni		= $enizest;			
				}
				
			}
		}
		
		if ($auto==0)
		{
			//$result44 = mysql_query("SELECT serwis_komorki.up_id, serwis_komorki.up_nazwa, serwis_piony.pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE belongs_to=$es_filia ORDER BY up_nazwa", $conn) or die($k_b);
			$result44 = mysql_query("SELECT serwis_komorki.up_id, serwis_komorki.up_nazwa, serwis_piony.pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) ORDER BY serwis_piony.pion_nazwa,up_nazwa", $conn) or die($k_b);
			td_(";;");		
				echo "<select class=wymagane name=tup onkeypress='return handleEnter(this, event);'>\n"; 					 				
				echo "<option value=''>Wybierz z listy...";
				while (list($temp_id,$temp_nazwa,$temp_pion)=mysql_fetch_array($result44)) {				
					echo "<option value='$temp_nazwa' ";
					if ($auto==1) { if ($tup==$temp_nazwa) echo "SELECTED"; }
					if ($up_id==$temp_id) echo "SELECTED";
					if (($tup!='') && ($tup==cleanup(cleanup($temp_nazwa)))) echo "SELECTED";
					if ($_REQUEST[upid]==$temp_nazwa) echo "SELECTED";
					echo ">$temp_pion $temp_nazwa</option>\n"; 
				}
				echo "</select>\n"; 
			_td();

		} else
		{
			td_(";;");
				echo "<input tabindex=-1 type=hidden name=tup value='$tup'>";
				echo "<b>$pionnazwa $tup</b>";
			_td();
		}
	_tr();
	tr_();
		td("200;r;Typ sprzętu");
			if ($auto==0) {
				$result = mysql_query("SELECT rola_id,rola_nazwa FROM $dbname.serwis_slownik_rola ORDER BY rola_nazwa", $conn) or die($k_b);
				td_(";;");
					echo "<select class=wymagane name=tnazwa onkeypress='return handleEnter(this, event);' onchange=\"reload_z_naprawy_przyjmij_szczecin(this.form)\">\n";
					echo "<option value=''>Wybierz z listy...";
					while (list($temp_id,$temp_nazwa)=mysql_fetch_array($result)) {
						echo "<option value='$temp_nazwa' ";
						if ($auto==1) { if ($tnazwa==$temp_nazwa) echo "SELECTED"; }
						if (($_REQUEST[clear_typ]!=0) && ($typ_id==$temp_id)) echo "SELECTED";
						if (($temp_nazwa==$_REQUEST[cat]) && ($_REQUEST[clear_typ]==0)) echo "SELECTED";
						echo ">$temp_nazwa</option>\n"; 
					}
					echo "</select>\n"; 
				_td();
			} else {
					td_(";;");
						echo "<input tabindex=-1 type=hidden name=tnazwa value='$tnazwa'>";
						echo "<b>$tnazwa</b>";
					_td();
				}
	_tr();
	tr_();
		td("200;r;Model");
		if ($auto==1) 
			{
				if ($tmodel=='') {
					td_(";;");
						echo "<input class=wymagane size=35 maxlength=30 type=text name=tmodel>";
					_td();
				} else
				{
					td_(";;");
						echo "<input tabindex=-1 type=hidden name=tmodel value='$tmodel'>";
						echo "<b>$tmodel</b>";
					_td();
				}
			} else
			{	
				td_(";;");
					echo "<input class=wymagane size=35 maxlength=30 type=text name=tmodel onkeypress='return handleEnter(this, event);' value='$_REQUEST[tmodel]'>";
				_td();
			}
	_tr();
	tr_();
		td("200;r;Numer seryjny");
		if ($auto==1) {
				if ($tsn=='') {
					td_(";;");
						echo "<input class=wymagane size=35 maxlength=30 type=text name=tsn>";
					_td();
				} else {
					td_(";;");
						echo "<input tabindex=-1 type=hidden name=tsn value='$tsn'>";
						echo "<b>$tsn</b>";
					_td();
				}
		} else {			
				td_(";;");
					echo "<input class=wymagane size=35 maxlength=30 type=text name=tsn onkeypress='return handleEnter(this, event);'  value='$_REQUEST[tsn]'>";
				_td();
				}
	_tr();
	tr_();
		td("200;r;Numer inwentarzowy");
		if ($auto==1) {
			td_(";;");
				echo "<input tabindex=-1 type=hidden name=tni value='$tni'>";
				echo "<b>$tni</b>";
			_td();
		} else
			{			
				td_(";;");
					echo "<input size=23 maxlength=20 type=text name=tni onkeypress='return handleEnter(this, event);'  value='$_REQUEST[tni]'>";
				_td();
			}
	_tr();
	tr_();
		td("200;rt;Uwagi");
		td_(";;");
			echo "<textarea name=tuwagi cols=41 rows=6>".urlencode($_REQUEST[tuwagi])."</textarea>";
		_td();
	_tr();
	tr_();
		td("200;r;Dostępny sprzęt serwisowy");
		td_(";;");		
		$cat = $_REQUEST[cat];
			if ($auto==1) {
				$result = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn FROM $dbname.serwis_magazyn WHERE (belongs_to=$es_filia) and ((magazyn_status=0) or ((magazyn_status=2) and (magazyn_osoba_rezerwujaca='$currentuser'))) and (magazyn_nazwa='$tnazwa') ORDER BY magazyn_model", $conn) or die($k_b);
			} else {
				$result = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn FROM $dbname.serwis_magazyn WHERE (belongs_to=$es_filia) and ((magazyn_status=0) or ((magazyn_status=2) and (magazyn_osoba_rezerwujaca='$currentuser'))) and (magazyn_nazwa='$cat') ORDER BY magazyn_model", $conn) or die($k_b);
				//echo "SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn FROM $dbname.serwis_magazyn WHERE (belongs_to=$es_filia) and ((magazyn_status=0) or ((magazyn_status=2) and (magazyn_osoba_rezerwujaca='$currentuser'))) and (magazyn_nazwa='$cat') ORDER BY magazyn_model";
			}
			if (mysql_num_rows($result)>0) { 
			echo "<select name=sz onkeypress='return handleEnter(this, event);'>\n";			 				
			echo "<option value='0'>Wybierz z listy...";
			while (list($temp_id,$temp_nazwa,$temp_model,$temp_sn)=mysql_fetch_array($result)) {
				echo "<option value='$temp_id'>$temp_nazwa ($temp_model, $temp_sn)</option>\n"; 
			}
			echo "</select>\n"; 
			} else if ($clear_typ==1) { echo "<b>wybierz typ sprzętu z listy</b>"; } else echo "<b>brak dostępnego sprzętu serwisowego tego typu na stanie</b>";
			
		_td();
	_tr();
	echo "<input type=hidden name=tstatus value='-1'>";
	echo "<input type=hidden name=id value=$id>";
	echo "<input type=hidden name=auto value='$auto'>";
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

	startbuttonsarea("left");
//	addownlinkbutton2("'Wybierz nowy sprzęt'","button","button","z_naprawy_przyjmij.php");
	endbuttonsarea();
	startbuttonsarea("right");
	addbuttons("zapisz","anuluj");
	endbuttonsarea();
	echo "<input type=hidden name=tuser value='$currentuser'>";
_form();

if ($auto==0) { ?>
	<script language="JavaScript" type="text/javascript">
		var frmvalidator  = new Validator("addt");
		frmvalidator.addValidation("tup","dontselect=0","Nie wybrałeś komórki z której pobrano sprzęt");  
		frmvalidator.addValidation("tnazwa","dontselect=0","Nie wybrałeś typu sprzętu");  
		frmvalidator.addValidation("tmodel","req","Nie podałeś modelu sprzętu");	  
		frmvalidator.addValidation("tsn","req","Nie podałeś numeru seryjnego sprzętu");
	</script>
<?php } ?>
<?php if ($auto==1) { ?>
	<script language="JavaScript" type="text/javascript">
		var frmvalidator  = new Validator("addt");
		<?php if ($tnazwa=='') { ?>frmvalidator.addValidation("tnazwa","dontselect=0","Nie wybrałeś typu sprzętu");  <?php } ?>
		<?php if ($tmodel=='') { ?>frmvalidator.addValidation("tmodel","req","Nie podałeś modelu sprzętu"); <?php } ?>
		<?php if ($tsn=='') { ?>frmvalidator.addValidation("tsn","req","Nie podałeś numeru seryjnego sprzętu"); <?php } ?>
	</script>
<?php } ?>
<?php } ?>
</body>
</html>