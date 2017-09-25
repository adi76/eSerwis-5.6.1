<?php include_once('header.php'); ?>
<body>
<?php
if ($es_m==1) {
	$result = mysql_query("SELECT * FROM $dbname.serwis_naprawa WHERE (naprawa_status='3')", $conn) or die($k_b);
} else {
	$result = mysql_query("SELECT * FROM $dbname.serwis_naprawa WHERE ((naprawa_status='3')) and belongs_to=$es_filia", $conn) or die($k_b);
}
if (mysql_num_rows($result)!=0) {
	br();
	pageheader("Przeglądanie naprawionego sprzętu na stanie",1);
	starttable();
	if ($es_m==1) {
		th("30;c;LP|;;Nazwa Model<br /><sub>Numer seryjny, inwentarzowy</sub>|;;Pobrano z|115;;Rejestracja przez<br />Data rejestracji|;;Przyjęcie z naprawy<br />Data przyjęcia|;c;Status<br /><sub>Sprzęt zastępczy</sub>|;c;Uwagi<br /><sub>Filia</sub>|;c;Opcje",$es_prawa);
	} else {
		th("30;c;LP|;;Nazwa Model<br /><sub>Numer seryjny, inwentarzowy</sub>|;;Pobrano z|115;;Rejestracja przez<br />Data rejestracji|;;Przyjęcie z naprawy<br />Data przyjęcia|;c;Status<br /><sub>Sprzęt zastępczy</sub>|40;c;Uwagi|;c;Opcje",$es_prawa);
	}
	$i = 0;
	while ($dane = mysql_fetch_array($result)) {
		$mid = $dane['naprawa_id'];						$mnazwa = $dane['naprawa_nazwa'];
		$mmodel= $dane['naprawa_model'];				$msn = $dane['naprawa_sn'];
		$mni= $dane['naprawa_ni'];						$muwagisa= $dane['naprawa_uwagi_sa'];
		$muwagi= $dane['naprawa_uwagi'];				$mup= $dane['naprawa_pobrano_z'];
		$moo= $dane['naprawa_osoba_pobierajaca'];		$mdp= $dane['naprawa_data_pobrania'];
		$mstatus= $dane['naprawa_status'];				$msz= $dane['naprawa_sprzet_zastepczy_id'];		
		$mopzs= $dane['naprawa_osoba_przyjmujaca_sprzet_z_serwisu']; 
		$mdpzs= $dane['naprawa_data_odbioru_z_serwisu'];$bt = $dane['belongs_to'];

		tbl_tr_highlight($i);
		$i++;

			td("30;c;".$i."");
			td_(";nw;<b>".$mnazwa." ".$mmodel."</b><br /><sub>".$msn.", ".$mni."</sub>");
				if ($pokazpoczatekuwag==1) pokaz_uwagi($muwagi,$iloscznakowuwag,"newWindow(480,300,'p_naprawy_uwagi.php?id=$mid')");
			_td();
			td_(";nw;");
				$wynik = mysql_query("SELECT up_id FROM $dbname.serwis_komorki WHERE (up_nazwa='$mup') and (belongs_to=$es_filia) LIMIT 1", $conn) or die($k_b);
				list($temp_up_id)= mysql_fetch_array($wynik);
				echo "<a class=normalfont title=' Szczegółowe informacje o $mup ' onclick=\"newWindow_r(700,600,'p_komorka_szczegoly.php?id=$temp_up_id'); return false;\" href=#><b>$mup</b></a><br />";
				//echo "".$msn."<br />".$mni."";
			_td();
			td_("115;;".$moo."<br />".$mdp."");
			td_("115;;".$mopzs."<br />".$mdpzs."");
			td_(";c");
				if ($mstatus=='-1') echo "<a>pobrany od klienta</a>";
				if ($mstatus=='0') echo "<a>naprawa we własnym zakresie</a>";
				if ($mstatus=='1') echo "<a>naprawa w serwisie zewnętrznym</a>";
				if ($mstatus=='2') echo "<a>naprawa na rynku lokalnym</a>";
				if ($mstatus=='3') echo "<a>naprawiony</a>";
				if ($mstatus=='5') echo "<a>zwrócony do klienta</a>";
				$c4='';
				if ($msz>0) {
					br();
					$result8 = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn FROM $dbname.serwis_magazyn WHERE ((belongs_to=$es_filia) and (magazyn_id=$msz)) LIMIT 1", $conn) or die($k_b);
					list($temp_id,$temp_nazwa,$temp_model,$temp_sn)=mysql_fetch_array($result8);
					echo "<sub>$temp_nazwa $temp_model<br />($temp_sn)</sub>";
					$wykonane_cz='Pobranie sprzętu zastępczego : '.$temp_nazwa.' '.$temp_model.', o nr seryjnym '.$temp_sn;
					$c4='on';
				} else $wykonane_cz='';
			_td();
			td_img("40;c");
				if ($muwagisa=='1') {
					echo "<a title=' Czytaj uwagi '><input type=image class=imgoption src=img/comment.gif onclick=\"newWindow(480,300,'p_naprawy_uwagi.php?id=$mid')\"></a>";
				} 
				echo "<a title=' Edytuj uwagi '><input type=image class=imgoption src=img/edit_comment.gif onclick=\"newWindow(480,300,'e_naprawy_uwagi.php?id=$mid')\"></a>";
				if ($es_m==1) include("p_filia_nazwa.php");
			_td();			
			td_(";c");
				echo "<a title=' Zwróć towar do klienta '><input class=imgoption type=image src=img//return.gif  onclick=\"newWindow(550,300,'z_naprawy_napraw5.php?id=$mid&cs=$mstatus&szid=$msz&tup=$mup')\"></a>";
				$d= Date('d');
				$m= Date('m');
				$r= Date('Y');
				if ($es_m==1) {
					$sql5 = "SELECT up_id FROM $dbname.serwis_komorki WHERE up_nazwa='$mup' LIMIT 1";
				} else {
					$sql5 = "SELECT up_id FROM $dbname.serwis_komorki WHERE up_nazwa='$mup' AND belongs_to=$es_filia LIMIT 1";
				}
				$wynik = mysql_query($sql5,$conn) or die($k_b);
				list($upid)=mysql_fetch_array($wynik);
				
				echo "<a title=' Generuj protokół '><input class=imgoption type=image align=top src=img/print.gif onclick=\"newWindow_r(700,595,'utworz_protokol.php?dzien=$d&miesiac=$m&rok=$r&c_1=&c_2=&c_3=&c_4=$c4&c_5=&c_6=on&c_7=&c_8=&up=".urlencode($mup)."&nazwa_urzadzenia=".urlencode($mnazwa)."%20".urlencode($mmodel)."&sn_urzadzenia=".urlencode($msn)."&ni_urzadzenia=".urlencode($mni)."&opis_uszkodzenia=&wykonane_czynnosci=".urlencode($wykonane_cz)."&uwagi=&imieinazwisko=&readonly=0&findpion=1&upid=$upid')\"></a>";	
				echo "<a title=' Pokaż szczegóły '><input class=imgoption type=image src=img/detail.gif  onclick=\"newWindow(800,600,'p_naprawy_szczegoly.php?id=$mid')\"></a>";
			_td();
		_tr();
    }
endtable();
startbuttonsarea("right");
addlinkbutton("'Uszkodzony sprzęt na stanie'","main.php?action=npus");
addlinkbutton("'Uszkodzony sprzęt w serwisach'","main.php?action=npswsz");
addbuttons("start");
endbuttonsarea();
} else {
		br();
		errorheader("Brak naprawionego sprzętu na stanie");
		?>
		<meta http-equiv="REFRESH" content="<?php echo $http_refresh; ?>;url=<?php echo "$linkdostrony";?>main.php">
		<?php
	}
?>
</body>
</html>