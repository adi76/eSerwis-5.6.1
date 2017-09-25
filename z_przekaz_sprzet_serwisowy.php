<?php include_once('header.php'); ?>
<body>
<?php if ($_REQUEST[dostepne]=='') { ?>
	<script>
		alert('Dla tego typu zgłoszenia nie zdefiniowano dostępnych typów sprzętu serwisowego. Skontaktuj się administratorem i podaj mu numer tego zgłoszenia');
		self.close();
	</script>
<?php }

include('body_start.php'); 
$zakres = strtr($_REQUEST[dostepne],'#','\'');
if ($wybierz_typ=='') $wybierz_typ = $_REQUEST[typ];

if ($_REQUEST[preselect]!='') {
	//echo "SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni,magazyn_uwagi,magazyn_uwagi_sa FROM $dbname.serwis_magazyn WHERE (magazyn_status='0') and (belongs_to=$es_filia) and (magazyn_nazwa = '$wybierz_typ') ORDER BY magazyn_nazwa ASC";
	
	if (($wybierz_typ=='Drukarka') || ($wybierz_typ=='Monitor')) {
		$result = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni,magazyn_uwagi,magazyn_uwagi_sa FROM $dbname.serwis_magazyn WHERE (magazyn_status='0') and (belongs_to=$es_filia) and (magazyn_nazwa = '$wybierz_typ') ORDER BY magazyn_nazwa ASC", $conn) or die($k_b);
	} else {
		if ($wybierz_typ=='Inne') {
			$result = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni,magazyn_uwagi,magazyn_uwagi_sa FROM $dbname.serwis_magazyn WHERE (magazyn_status='0') and (belongs_to=$es_filia) and (magazyn_nazwa <> 'Drukarka') and (magazyn_nazwa <> 'Monitor') and (magazyn_nazwa <> 'Switch') ORDER BY magazyn_nazwa ASC", $conn) or die($k_b);
		} else {
			$result = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni,magazyn_uwagi,magazyn_uwagi_sa FROM $dbname.serwis_magazyn WHERE (magazyn_status='0') and (belongs_to=$es_filia) ORDER BY magazyn_nazwa ASC", $conn) or die($k_b);
		}
	}
} else {
	$result = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn,magazyn_ni,magazyn_uwagi,magazyn_uwagi_sa FROM $dbname.serwis_magazyn WHERE (magazyn_status='0') and (belongs_to=$es_filia) and (magazyn_nazwa = '$wybierz_typ') ORDER BY magazyn_nazwa ASC", $conn) or die($k_b);
}

if (mysql_num_rows($result)!=0) {
	pageheader("Przekazanie sprzętu serwisowego dla zgłoszenia nr <b>".$_REQUEST[hd_zgl_nr]."</b>",0,0);
	startbuttonsarea("center");

	//if ($_REQUEST[pkp2]==$_REQUEST[preselect]) $_REQUEST[preselect] = $_REQUEST[pkp2];
	
	echo "<form name=magazyn action=$PHP_SELF method=GET>";
	echo "Pokaż: ";
	echo "<select name=wybierz_typ onChange=\"document.location.href=document.magazyn.wybierz_typ.options[document.magazyn.wybierz_typ.selectedIndex].value; \">";
		if ($_REQUEST[typ]=='') {
			$sql_lista_p = "SELECT DISTINCT magazyn_nazwa FROM $dbname.serwis_magazyn WHERE (belongs_to=$es_filia) and (magazyn_status='0') ORDER BY magazyn_nazwa";
		} else {
			if ($_REQUEST[preselect]!='') {
				if ($_REQUEST[preselect]=='Inne') {
					$sql_lista_p = "SELECT DISTINCT magazyn_nazwa FROM $dbname.serwis_magazyn WHERE (belongs_to=$es_filia) and (magazyn_status='0') and (magazyn_nazwa NOT IN (".$zakres.")) ORDER BY magazyn_nazwa";
				} else {
					$sql_lista_p = "SELECT DISTINCT magazyn_nazwa FROM $dbname.serwis_magazyn WHERE (belongs_to=$es_filia) and (magazyn_status='0') and (magazyn_nazwa IN (".$zakres.")) ORDER BY magazyn_nazwa";
				}
			} else {
				$sql_lista_p = "SELECT DISTINCT magazyn_nazwa FROM $dbname.serwis_magazyn WHERE (belongs_to=$es_filia) and (magazyn_status='0') and (magazyn_nazwa IN (".$zakres.")) ORDER BY magazyn_nazwa";
			}
		}
		
		$wynik_lista_typ = mysql_query($sql_lista_p,$conn) or die($k_b);
		
		if ($_REQUEST[typ]=='') {
			echo "<option ";
			if ($wybierz_typ=='') echo "SELECTED ";
			echo "value='z_przekaz_sprzet_serwisowy.php?wybierz_typ=&tup=".urlencode($_REQUEST[tup])."&new_upid=$_REQUEST[new_upid]&hd_zgl_nr=$_REQUEST[hd_zgl_nr]&fromhd=1&naprawaid=".$_REQUEST[naprawaid]."&typ=".urlencode($_REQUEST[typ])."&preselect=".urlencode($_REQUEST[preselect])."&dostepne=".urlencode($_REQUEST[dostepne])."'>Cały sprzęt</option>\n";	
		}
		
		while (list($temp_magazyn_nazwa)=mysql_fetch_array($wynik_lista_typ)) {
					
			echo "<option "; 
			if ($wybierz_typ==$temp_magazyn_nazwa) echo " SELECTED ";
			//if ($_REQUEST[typ]==$temp_magazyn_nazwa) echo "SELECTED ";
			
			echo "value='z_przekaz_sprzet_serwisowy.php?wybierz_typ=$temp_magazyn_nazwa&tup=".urlencode($_REQUEST[tup])."&new_upid=$_REQUEST[new_upid]&hd_zgl_nr=$_REQUEST[hd_zgl_nr]&from=hd&naprawaid=".$_REQUEST[naprawaid]."&typ=".urlencode($_REQUEST[typ])."&preselect=".urlencode($_REQUEST[preselect])."&dostepne=".urlencode($_REQUEST[dostepne])."'>$temp_magazyn_nazwa</option>\n";	
			
		}
	echo "</select>";
	
	//echo ">>".$sql_lista_p;
	echo "</form>";
	endbuttonsarea();	
	
	startbuttonsarea("center");

	echo "<form name=magazyn2 action=z_magazyn_pobierz.php method=GET onSubmit=\"if (document.getElementById('id').value=='') { alert('Nie wybrałeś sprzętu z listy'); document.getElementById('id').focus(); return false; } \" />";
	
	echo "<select id=id name=id>";
	echo "<option value=''>Wybierz z listy...</option><br />";
	
	while (list($mid, $mnazwa, $mmodel, $msn, $mni, $muwagi, $muwagisa) = mysql_fetch_array($result)) {
		echo "<option value=$mid>$mnazwa $mmodel | SN: $msn | NI: $mni</option><br />";
	}
	echo "</select>";
	
	endbuttonsarea();

	startbuttonsarea("right");
	echo "<span style='float:left'>";
	
	echo "</span>";
	
	//addbuttons("dalej");
	echo "<input type=hidden name=powiazzhd value=0>";
	echo "<input type=hidden name=hd_zgl_nr value=$_REQUEST[hd_zgl_nr] />";
	
	echo "<input type=hidden name=new_upid value=$_REQUEST[new_upid] />";
	echo "<input type=hidden name=tup value='$_REQUEST[tup]' />";
	echo "<input type=hidden name=from value='hd' />";
	echo "<input type=hidden name=naprawaid value='$_REQUEST[naprawaid]'>";
	
	echo "<input class=buttons type=submit name=submit2 id=submit2 value=Dalej />";
	addbuttons("anuluj");
	endbuttonsarea();

	echo "</form>";
} else { 
		errorheader('Brak sprzętu na magazynie');	

		startbuttonsarea("right");
		addbuttons("zamknij");
		endbuttonsarea();
		
	}

include('body_stop.php');
//include('js/menu.js');

?>
</body>
</html>