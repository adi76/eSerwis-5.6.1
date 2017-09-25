<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php'); 
?>
<body>
<?php 
if ($_REQUEST[dowolny]!=1) {
	pageheader('Lista dostępnych towarów w magazynie o typie: <font color=red>'.$_REQUEST[typ].'</font>');
	list($ilosc_typow_do_powiazania)=mysql_fetch_array(mysql_query("SELECT wp_id FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_typ_podzespolu='$_REQUEST[typ]') and (wp_sprzet_active=1) and (belongs_to=$es_filia) and (wp_wskazanie_sprzetu_z_magazynu=0) and (wp_sprzedaz_fakt_szcz_id=0)",$conn_hd));
} else {
	if ($_REQUEST[sa]!=1) errorheader('Brak towarów o typie <font color=red>'.$_REQUEST[typ].'</font> w magazynie');
	pageheader('Lista dostępnych towarów w magazynie');
	
	list($ilosc_typow_do_powiazania)=mysql_fetch_array(mysql_query("SELECT wp_id FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_sprzet_active=1) and (belongs_to=$es_filia) and (wp_wskazanie_sprzetu_z_magazynu=0) and (wp_sprzedaz_fakt_szcz_id=0)",$conn_hd));	
}

infoheader("Powiązanie dotyczy numeru zgłoszenia <b>".$_REQUEST[zglnr]."</b><br /><br /><b>Informacje o sprzęcie:</b><br />".$_REQUEST[sopis]."<br />".$_REQUEST[ssn]."<br />".$_REQUEST[sni]."");

include('body_start.php');

if ($ilosc_typow_do_powiazania!=null) {

	$sql44="SELECT * FROM $dbname.serwis_faktura_szcz WHERE (serwis_faktura_szcz.pozycja_status='0')";
	if ($es_m==1) { } else $sql44=$sql44." and (belongs_to=$es_filia) ";
	if ($_REQUEST[dowolny]!=1) $sql44=$sql44." and (pozycja_typ='$_REQUEST[typ]') 	";
	$sql44=$sql44." ORDER BY pozycja_id ASC";
	$result44 = mysql_query($sql44, $conn) or die($k_b);
	$count_rows44 = mysql_num_rows($result44);
//echo $sql44;
//echo $count_rows44;

	$sql441="SELECT * FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE (wp_sprzedaz_fakt_szcz_id=0)";
	if ($es_m==1) { } else $sql441=$sql441." and (belongs_to=$es_filia) and (wp_sprzet_active=1) ";
	
	if ($_REQUEST[dowolny]!=1) $sql441=$sql441." and (wp_typ_podzespolu='$_REQUEST[typ]') ";
//	echo "<br />".$sql441;

	$result441 = mysql_query($sql441, $conn) or die($k_b);
	$count_rows441 = mysql_num_rows($result441);

//echo ", ".$count_rows441;	

	if (($count_rows44!=0) && ((($count_rows44!=$count_rows441)) )) {	
		
		echo "<table class=maxwidth cellspacing=1 align=center>";
		echo "<tr>";
		echo "<th><center>LP</center></th>";	
		echo "<th>Nazwa towaru</th>";
		echo "<th>Numer seryjny</th>";	
		echo "<th>Numer zamówienia</th>";
		echo "<th>Dostawca<br />Nr faktury, data wystawienia</th>";
		echo "<th><center>Opcje</center></th>";
		echo "</tr>";
		
		$i = 0;
		$j = 1;
		$k = 1;
		
		while ($newArray = mysql_fetch_array($result44)) {
			$temp_id  			= $newArray['pozycja_id'];
			$temp_nrfaktury		= $newArray['pozycja_nr_faktury'];
			$temp_numer			= $newArray['pozycja_numer'];
			$temp_nazwa			= $newArray['pozycja_nazwa'];
			$temp_ilosc			= $newArray['pozycja_ilosc'];
			$temp_sn			= $newArray['pozycja_sn'];
			$temp_cenanetto_cr	= $newArray['pozycja_cena_netto'];
			$temp_status		= $newArray['pozycja_status'];
			$temp_cenanettoodsp_cr	= $newArray['pozycja_cena_netto_odsprzedazy'];
			$temp_typ			= $newArray['pozycja_typ'];
			$temp_uwagi			= $newArray['pozycja_uwagi'];
			$temp_rs			= $newArray['pozycja_rodzaj_sprzedazy'];	

			list($czy_powiazane)=mysql_fetch_array(mysql_query("SELECT wp_id FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE wp_sprzedaz_fakt_szcz_id='$temp_id' LIMIT 1",$conn));
			
		if ($czy_powiazane==0) {
		
			list($kolorgrupy)=mysql_fetch_array(mysql_query("SELECT rola_kolor FROM $dbname.serwis_slownik_rola WHERE rola_nazwa='$temp_typ' LIMIT 1",$conn));
			
			tbl_tr_color_dblClick_towary_dostepne($i,$kolorgrupy);

			$sql1="SELECT * FROM $dbname.serwis_faktury WHERE (faktura_id='$temp_nrfaktury')";
			$result1 = mysql_query($sql1, $conn) or die($k_b);
			while ($newArray4 = mysql_fetch_array($result1)) {
				$temp_id4  			= $newArray4['faktura_id'];
				$temp_numer4		= $newArray4['faktura_numer'];
				$temp_data4			= $newArray4['faktura_data'];
				$temp_dostawca4		= $newArray4['faktura_dostawca'];	
				$temp_nz			= $newArray4['faktura_nr_zamowienia'];			
			}	

			echo "<td width=30 class=center><a class=normalfont href=# title='$temp_id'>$j</a></td>";
			
			echo "<td class=wrap>$temp_nazwa";
			if ($pokazpoczatekuwag==1) pokaz_uwagi($temp_uwagi,$iloscznakowuwag,"newWindow(480,265,'p_towar_uwagi.php?id=$temp_id');return false;");
			if ($temp_rs!='') {
				echo "<br />";
				echo "<font color=grey>$temp_rs</font>";
			} else { 
				echo "<br /><font color=grey><a class=normalfont title=' Nie określono rodzaju sprzedaży ' href=#>-</a></font>";
			}
			echo "</td>";
			echo "<td>$temp_sn</td>";
			
			$accessLevels = array("1", "9");
			if(array_search($es_prawa, $accessLevels)>-1){
				echo "<td class=center>$temp_nz</td>";

				if ($temp_numer4=='') { $temp_numer4a = '<font color=grey>brak numeru</font>,'; } else { $temp_numer4a = ',';} 
				echo "<td>$temp_dostawca4<br />$temp_numer4$temp_numer4a $temp_data4&nbsp;";
				echo "</td>";
			}
			
			echo "<td class=center>";
				if ($_REQUEST[dowolny]!=1) {
					echo "<a title='Powiąż towar: $temp_nazwa (SN: $temp_sn) ze zgłoszeniem nr $_REQUEST[zglnr] w bazie Helpdesk '><input class=imgoption type=image src=img/powiaz.gif onclick=\"newWindow_r(800,600,'z_powiaz_typ_z_towarem_wykonaj.php?wpid=$_REQUEST[wpid]&wyb_fszcz_id=$temp_id'); return false; \"></a>";
				} else {
					echo "<a title='Powiąż towar: $temp_nazwa (SN: $temp_sn) ze zgłoszeniem nr $_REQUEST[zglnr] w bazie Helpdesk '><input class=imgoption type=image src=img/powiaz_dowolny.gif onclick=\"newWindow_r(800,600,'z_powiaz_typ_z_towarem_wykonaj.php?wpid=$_REQUEST[wpid]&wyb_fszcz_id=$temp_id'); return false; \"></a>";
				}
			echo "</td>";
			
			$j++;
			$i++;
			
			echo "</tr>";
			}
		}
		
		echo "</table>";
	} else {
		errorheader('Brak wolnych towarów o wybranym typie w magazynie');
	}
	startbuttonsarea();	
	echo "<span style='float:left'>";
	//addbuttons("start");
	if ($_REQUEST[dowolny]!=1) {
		echo "<input type=button class=buttons value='Powiąż typ z dowolną pozycją z magazynu' onClick=\"self.location.href='z_powiaz_typ_z_towarem.php?wpid=$_REQUEST[wpid]&typ=".urlencode($_REQUEST[typ])."&zglnr=$_REQUEST[zglnr]&sopis=".urlencode($_REQUEST[sopis])."&ssn=".urlencode($_REQUEST[ssn])."&sni=".urlencode($_REQUEST[sni])."&dowolny=1&sa=1';\" />";
	} else {
		echo "<input type=button class=buttons value='Powiąż typ z konkretnym typem z magazynu' onClick=\"self.location.href='z_powiaz_typ_z_towarem.php?wpid=$_REQUEST[wpid]&typ=".urlencode($_REQUEST[typ])."&zglnr=$_REQUEST[zglnr]&sopis=".urlencode($_REQUEST[sopis])."&ssn=".urlencode($_REQUEST[ssn])."&sni=".urlencode($_REQUEST[sni])."&dowolny=0&sa=0';\" />";
	}
	
	echo "</span>";
	echo "<input type=button class=buttons value='Zamknij' onClick=\"self.close();\" />";
	endbuttonsarea();	
	
} else {
	okheader('Już powiązałeś towar z magazynu z typem w zgłoszeniu');
	startbuttonsarea();	
	echo "<span style='float:left'>";
	//addbuttons("start");
	echo "</span>";
	echo "<input type=button class=buttons value='Zamknij' onClick=\"if (opener) opener.location.reload(true); self.close();\" />";
	endbuttonsarea();	
	
}

if ($i==0) {
?>
<script>
//opener.location.reload(true); self.close();
</script>
<?php
}
?>
</body>
</html>