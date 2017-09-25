<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) { 
	$sql_t = "UPDATE $dbname.serwis_ewidencja SET ewidencja_drukarka_powiaz_z = '$_POST[drukpowiaz1]' WHERE (ewidencja_id='$_POST[id]')";
	if (mysql_query($sql_t, $conn)) { 
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	}
} else {
	$sql = "SELECT * FROM $dbname.serwis_ewidencja WHERE (belongs_to=$es_filia) and (ewidencja_id=$id) ";
	$result = mysql_query($sql, $conn) or die($k_b);
	pageheader("Szczegółowe informacje o drukarce");
	starttable();
	echo "<form name=powiazd action=$PHP_SELF method=POST>";
	while ($dane = mysql_fetch_array($result)) {
		$eid 		= $dane['ewidencja_id'];
		$etyp_id	= $dane['ewidencja_typ'];
		$eup_id		= $dane['ewidencja_up_id'];
		$euser		= $dane['ewidencja_uzytkownik'];	  
		$enrpok		= $dane['ewidencja_nr_pokoju'];
		$enizest	= $dane['ewidencja_zestaw_ni'];
		$eknazwa	= $dane['ewidencja_komputer_nazwa'];
		$ekopis		= $dane['ewidencja_komputer_opis'];
		$eksn		= $dane['ewidencja_komputer_sn'];
		$ekip		= $dane['ewidencja_komputer_ip'];
		$eke		= $dane['ewidencja_komputer_endpoint'];
		$emo		= $dane['ewidencja_monitor_opis'];
		$emsn		= $dane['ewidencja_monitor_sn'];
		$edo		= $dane['ewidencja_drukarka_opis'];
		$edsn		= $dane['ewidencja_drukarka_sn'];
		$edni		= $dane['ewidencja_drukarka_ni'];
		$eu			= $dane['ewidencja_uwagi'];
		$es			= $dane['ewidencja_status'];
		$eo_id		= $dane['ewidencja_oprogramowanie_id'];
		$emoduser	= $dane['ewidencja_modyfikacja_user'];
		$emoddata	= $dane['ewidencja_modyfikacja_date'];
		$ekonf		= $dane['ewidencja_konfiguracja'];
		$egwarancja	= $dane['ewidencja_gwarancja_do'];	
		$egwarancjakto	= $dane['ewidencja_gwarancja_kto'];		
		$drukarkapow	= $dane['ewidencja_drukarka_powiaz_z'];
		$edo1=$edo;
		if ($enrpok=='') $enrpok='-';
		if ($enizest=='') $enizest='-';
		if ($eknazwa=='') $eknazwa='-';
		if ($ekopis=='') $ekopis='-';
		if ($eksn=='') $eksn='-';
		if ($ekip=='') $ekip='-';
		if ($eke=='') $eke='-';
		if ($emo=='') $emo='-';
		if ($emsn=='') $emsn='-';
		if ($edo=='') $edo='-';
		if ($edsn=='') $edsn='-';
		if ($edni=='') $edni='-';
		if ($ekonf=='') $ekonf='-';
		if ($euser=='') $euser='-';
		if ($eu=='') $eu='-';		

		$sql77="SELECT * FROM $dbname.serwis_slownik_rola WHERE rola_id='$etyp_id'";
		$result77 = mysql_query($sql77, $conn) or die($k_b);
	
		while ($newArray77 = mysql_fetch_array($result77)) {
			$rolanazwa		= $newArray77['rola_nazwa'];
		}

	echo "<tr height=18 id=1 class=nieparzyste onmouseover=rowOver('1',1); this.style.cursor=arrow onmouseout=rowOver('1',0) onclick=selectRow('1') ondblclick=deSelectRow('1')><td class=nowrap>&nbsp;Rodzaj sprzętu</td><td><b>&nbsp;$rolanazwa</b></td></tr>";

	$sql7a="SELECT * FROM $dbname.serwis_komorki WHERE up_id='$eup_id'";
	$result7a = mysql_query($sql7a, $conn) or die($k_b);
			
	while ($newArray7a = mysql_fetch_array($result7a))
		{
			  $temp_up_nazwa		= $newArray7a['up_nazwa'];
		}	
		
	echo "<tr height=18 id=3 class=nieparzyste onmouseover=rowOver('3',1); this.style.cursor=arrow onmouseout=rowOver('3',0) onclick=selectRow('3') ondblclick=deSelectRow('3')><td class=nowrap>&nbsp;Lokalizacja sprzętu</td><td><b>&nbsp;$temp_up_nazwa</b></td></tr>";
if (($rolanazwa=='Komputer') || ($rolanazwa=='Serwer') || ($rolanazwa=='Notebook')) {
	echo "<tr height=18 id=5 class=nieparzyste onmouseover=rowOver('5',1); this.style.cursor=arrow onmouseout=rowOver('5',0) onclick=selectRow('5') ondblclick=deSelectRow('3')><td class=nowrap>&nbsp;Numer pokoju</td><td><b>&nbsp;$enrpok</b></b></td></tr>";
}
if (($rolanazwa=='Komputer') || ($rolanazwa=='Serwer') || ($rolanazwa=='Notebook')) {
	echo "<tr height=18 id=7 class=nieparzyste onmouseover=rowOver('7',1); this.style.cursor=arrow onmouseout=rowOver('7',0) onclick=selectRow('7') ondblclick=deSelectRow('7')><td class=nowrap>&nbsp;Użytkownik sprzętu</td><td><b>&nbsp;$euser</b></b></td></tr>";
}
if ($rolanazwa!='Drukarka') {
	echo "<tr height=18 id=9 class=nieparzyste onmouseover=rowOver('9',1); this.style.cursor=arrow onmouseout=rowOver('9',0) onclick=selectRow('9') ondblclick=deSelectRow('9')><td class=nowrap>&nbsp;Nr inwentarzowy zestawu</td><td><b>&nbsp;$enizest</b></b></td></tr>";
}	
if (($rolanazwa=='Komputer') || ($rolanazwa=='Serwer') || ($rolanazwa=='Notebook')) {
	echo "<tr height=18 id=11 class=nieparzyste onmouseover=rowOver('11',1); this.style.cursor=arrow onmouseout=rowOver('11',0) onclick=selectRow('11') ondblclick=deSelectRow('11')><td class=nowrap>&nbsp;Model komputera</td><td><b>&nbsp;$ekopis</b></td></tr>";
	echo "<tr height=18 id=13 class=nieparzyste onmouseover=rowOver('13',1); this.style.cursor=arrow onmouseout=rowOver('13',0) onclick=selectRow('13') ondblclick=deSelectRow('13')><td class=nowrap>&nbsp;Numer seryjny komputera</td><td><b>&nbsp;$eksn</b></td></tr>";
	echo "<tr height=18 id=15 class=nieparzyste onmouseover=rowOver('15',1); this.style.cursor=arrow onmouseout=rowOver('15',0) onclick=selectRow('15') ondblclick=deSelectRow('15')><td class=nowrap>&nbsp;Nazwa komputera</td><td><b>&nbsp;$eknazwa</b></td></tr>";
	echo "<tr height=18 id=17 class=nieparzyste onmouseover=rowOver('17',1); this.style.cursor=arrow onmouseout=rowOver('17',0) onclick=selectRow('17') ondblclick=deSelectRow('17')><td class=nowrap>&nbsp;Adres IP komputera</td><td><b>&nbsp;$ekip</b></td></tr>";
	echo "<tr height=18 id=19 class=nieparzyste onmouseover=rowOver('19',1); this.style.cursor=arrow onmouseout=rowOver('19',0) onclick=selectRow('19') ondblclick=deSelectRow('19')><td class=nowrap>&nbsp;Konfiguracja komputera</td><td><b>&nbsp;$ekonf</b></td></tr>";
	echo "<tr height=18 id=21 class=nieparzyste onmouseover=rowOver('21',1); this.style.cursor=arrow onmouseout=rowOver('21',0) onclick=selectRow('21') ondblclick=deSelectRow('21')><td class=nowrap>&nbsp;Numer endpointa</td><td><b>&nbsp;$eke</b></td></tr>";
}

if (($rolanazwa=='Komputer') || ($rolanazwa=='Serwer')) {
	echo "<tr height=18 id=23 class=nieparzyste onmouseover=rowOver('23',1); this.style.cursor=arrow onmouseout=rowOver('23',0) onclick=selectRow('23') ondblclick=deSelectRow('23')><td class=nowrap>&nbsp;Model monitora</td><td><b>&nbsp;$emo</b></td></tr>";
	echo "<tr height=18 id=25 class=nieparzyste onmouseover=rowOver('25',1); this.style.cursor=arrow onmouseout=rowOver('25',0) onclick=selectRow('25') ondblclick=deSelectRow('25')><td class=nowrap>&nbsp;Numer seryjny monitora</td><td><b>&nbsp;$emsn</b></td></tr>";
}
	
if (($rolanazwa=='Drukarka') || ($edo1!='')) {
	echo "<tr height=18 id=27 class=nieparzyste onmouseover=rowOver('27',1); this.style.cursor=arrow onmouseout=rowOver('27',0) onclick=selectRow('27') ondblclick=deSelectRow('27')><td class=nowrap>&nbsp;Model drukarki</td><td><b>&nbsp;$edo</b></td></tr>";
	echo "<tr height=18 id=29 class=nieparzyste onmouseover=rowOver('29',1); this.style.cursor=arrow onmouseout=rowOver('29',0) onclick=selectRow('29') ondblclick=deSelectRow('29')><td class=nowrap>&nbsp;Numer seryjny drukarki</td><td><b>&nbsp;$edsn</b></td></tr>";
	echo "<tr height=18 id=31 class=nieparzyste onmouseover=rowOver('31',1); this.style.cursor=arrow onmouseout=rowOver('31',0) onclick=selectRow('31') ondblclick=deSelectRow('31')><td class=nowrap>&nbsp;Numer inwentarzowy drukarki&nbsp;</td><td><b>&nbsp;$edni</b></td></tr>";
}	
	
	echo "<tr id=33 class=nieparzyste onmouseover=rowOver('33',1); this.style.cursor=arrow onmouseout=rowOver('33',0) onclick=selectRow('33') ondblclick=deSelectRow('33')><td class=nowrap>&nbsp;Uwagi</td><td><b>&nbsp;$eu</b></td></tr>";
	$dddd = Date('Y-m-d');
	if ($egwarancja!='0000-00-00') {
		echo "<tr id=35 class=nieparzyste onmouseover=rowOver('35',1); this.style.cursor=arrow onmouseout=rowOver('35',0) onclick=selectRow('35') ondblclick=deSelectRow('35')><td class=nowrap>&nbsp;Gwarancja do</td><td><b>&nbsp;$egwarancja</b>";
		echo "&nbsp;<b>";
		if ($dddd>$egwarancja) echo "- gwarancja wygasła";
		echo "</b>";
		echo "</td></tr>";
	}

    echo "<tr><td></td></tr>";
}
tbl_empty_row();
echo "<tr><td class=nowrap>Powiąż z komputerem</td>";
		td_(";;");
			$sql5="SELECT ewidencja_id,ewidencja_drukarka_powiaz_z,ewidencja_komputer_nazwa,ewidencja_komputer_opis,ewidencja_komputer_sn,ewidencja_komputer_ip  FROM $dbname.serwis_ewidencja WHERE ((ewidencja_up_id=$upid) and ((ewidencja_typ_nazwa='Komputer') or (ewidencja_typ_nazwa='Serwer') or (ewidencja_typ_nazwa='Notebook'))) ORDER BY ewidencja_komputer_nazwa";
			$result5 = mysql_query($sql5, $conn); // or die($k_b);
			
			//$count_rows = mysql_num_rows($result5);
			echo "<select ";
			echo "name=drukpowiaz1>\n"; 
			echo "<option ";
			if ($drukarkapow==0) echo "SELECTED ";
			echo "value=0>brak powiązania</option>\n";			
			while ($newArray5 = mysql_fetch_array($result5)) {
				$temp_id1			= $newArray5['ewidencja_id'];
				$temp_1				= $newArray5['ewidencja_komputer_nazwa'];
				$temp_2				= $newArray5['ewidencja_komputer_opis'];
				$temp_3				= $newArray5['ewidencja_komputer_sn'];
				$temp_4				= $newArray5['ewidencja_komputer_ip'];
				$temp_pow			= $newArray5['ewidencja_drukarka_powiaz_z'];
				
				echo "<option ";
				if ($drukarkapow!=0) { 
					if ($temp_id1==$drukarkapow) { 
						echo "SELECTED "; 
					}
				}
				echo "value=$temp_id1>$temp_2, $temp_3, $temp_1, $temp_4</option>\n"; 
			}
			echo "</select>\n"; 
		_td();
	_tr();
tbl_empty_row();
endtable();
echo "<input type=hidden name=id value=$id>";
startbuttonsarea("right");
addbuttons("zapisz","anuluj");
endbuttonsarea();
_form();
}

?>
</body>
</html>