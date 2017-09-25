<?php include_once('header.php'); ?>
<body>
<?php 
pageheader('Lista zgłoszeń w których użyto typ podzespołu: '.$_REQUEST[typ].'');
infoheader('Podzespół który chcesz powiązać ze zgłoszeniem: <br /><b>'.$_REQUEST[nazwa].' (SN: '.$_REQUEST[sn].')</b>');
include('cfg_helpdesk.php');
include('body_start.php');

	$sql44="SELECT wp_zgl_id,wp_zgl_szcz_unique_nr FROM $dbname_hd.hd_zgl_wymiany_podzespolow WHERE ";
	if ($es_m==1) { } else $sql44=$sql44."(belongs_to=$es_filia) and (wp_sprzet_active=1) ";
	
	$sql44=$sql44." and (wp_typ_podzespolu='$_REQUEST[typ]') and (wp_wskazanie_sprzetu_z_magazynu = '0') and (wp_sprzet_active=1) ORDER BY wp_zgl_id ASC";
//	echo $sql;

	$result44 = mysql_query($sql44, $conn_hd) or die($k_b);
	$count_rows44 = mysql_num_rows($result44);
	
if ($count_rows44!=0) {	
	
	echo "<table class=maxwidth cellspacing=1 align=center>";
	echo "<tr>";
	echo "<th><center>LP</center></th>";	
	echo "<th><center>Nr zgłoszenia</center></th>";
	echo "<th><center>Nr<br />kroku</center></th>";	
	
	echo "<th><center>Data wykonania</center></th>";
	echo "<th>Placówka zgłaszająca</th>";
	echo "<th>Wykonane czynności</th>";
	//echo "<th><center>Osoba wykonująca<br /><sub>dodatkowe osoby</sub></center></th>";
	//echo "<th><center>Status</center></th>";	
	echo "<th><center>Opcje</center></th>";
	echo "</tr>";
	
	$i = 0;
	$j = $page*$rowpersite-$rowpersite+1;
	$k = 1;
	
	while ($newArray = mysql_fetch_array($result44)) {
		$temp_id			= $newArray['wp_zgl_id'];		
		$temp_unique		= $newArray['wp_zgl_szcz_unique_nr'];		
		
		list($krok_nr,$krok_ow,$krok_status,$krok_data,$wc,$krok_unique)=mysql_fetch_array(mysql_query("SELECT zgl_szcz_nr_kroku, zgl_szcz_osoba_wykonujaca_krok,zgl_szcz_status,zgl_szcz_czas_rozpoczecia_kroku,zgl_szcz_wykonane_czynnosci,zgl_szcz_unikalny_numer  FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_unikalny_numer='$temp_unique') LIMIT 1", $conn_hd));
				
		list($temp_kategoria,$temp_priorytet,$temp_komorka)=mysql_fetch_array(mysql_query("SELECT zgl_kategoria,zgl_priorytet, zgl_komorka FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id='$temp_id') LIMIT 1", $conn_hd));
		
		if ($KolorujWgStatusow==1) {
			switch ($temp_kategoria) {
				case 6: $kolorgrupy='#F73B3B'; tbl_tr_color_dblClick($i, $kolorgrupy); break;
				case 2:	if ($temp_priorytet==2) { $kolorgrupy='#FF7F2A'; tbl_tr_color_dblClick($i, $kolorgrupy); break; 
							}
						if ($temp_priorytet==4) { $kolorgrupy='#F73B3B'; tbl_tr_color_dblClick($i, $kolorgrupy); break; 
							}				
				case 3:	if ($temp_priorytet==3) { $kolorgrupy='#FFAA7F'; tbl_tr_color_dblClick($i, $kolorgrupy); break; 
							}
				default: if ($temp_status==9) { $kolorgrupy='#FFFFFF'; tbl_tr_color_dblClick($i, $kolorgrupy); break; 
							} else {
							tbl_tr_highlight_dblClick($i);	
							$kolorgrupy='';
						}
			}
		} else {
			tbl_tr_highlight_dblClick($i);	
			$kolorgrupy='';
		}
		
		$j++;
		//list($status)=mysql_fetch_array(mysql_query("SELECT hd_status_opis FROM $dbname_hd.hd_status WHERE hd_status_nr='$temp_status' LIMIT 1", $conn_hd));
		echo "<td class=center>$k</td>"; $k++;
		echo "<td class=center>$temp_id</td>";
		echo "<td class=center>$krok_nr";
		echo "</td>";
		echo "<td class=center>$krok_data";
		echo "</td>";
		echo "<td>$temp_komorka</td>";
		echo "<td>$wc";
		echo "</td>";
		echo "<td class=center>";
			echo "<a title=' Powiąż towar: $_REQUEST[nazwa] (SN: $_REQUEST[sn]) ze zgłoszeniem nr $temp_id w bazie Helpdesk '><input class=imgoption type=image src=img/powiaz.gif onclick=\"newWindow_r(800,600,'z_towary_powiaz_z_hd_wykonaj.php?id=$temp_id&fszcz_id=$_REQUEST[id]&unique=$krok_unique&typ=".urlencode($_REQUEST[typ])."')\"></a>";		
		echo "</td>";		
		
		$i++;
		_tr();
	}	
	
	echo "</table>";
}

startbuttonsarea();	
echo "<span style='float:left'>";
//addbuttons("start");
echo "</span>";
echo "<input type=button class=buttons value='Zamknij' onClick=\"if (opener) opener.location.reload(true); self.close();\" />";
endbuttonsarea();	

if ($i==0) {
?>
<script>
if (opener) opener.location.reload(true); self.close();
</script>
<?php
}
?>
</body>
</html>