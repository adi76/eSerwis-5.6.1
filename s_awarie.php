<?php

?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
	
starttable();
th("30;c;LP|;;Miejsce awarii|100;;Nr zgłoszenia|115;;Osoba zgłaszająca<br />Data zgłoszenia|115;;Osoba zamykająca<br />Data zamknięcia|120;c;Czas trwania awarii|80;c;Status|40;c;Opcje",$es_prawa);
$i = 0;
$j = 0;

while ($newArray = mysql_fetch_array($result)) {
	$temp_id  			= $newArray['awaria_id'];
	$temp_gdzie			= $newArray['awaria_gdzie'];
	$temp_nrwanportu	= $newArray['awaria_nrwanportu'];
	$temp_datao			= $newArray['awaria_datazgloszenia'];
	$temp_dataz			= $newArray['awaria_datazamkniecia'];
	$temp_nrzgl			= $newArray['awaria_nrzgloszenia'];
	$temp_ip			= $newArray['awaria_ip'];
	$temp_osobar		= $newArray['awaria_osobarejestrujaca'];
	$temp_osobaz		= $newArray['awaria_osobazamykajaca'];	
	$temp_status		= $newArray['awaria_status'];
	$temp_belongs_to	= $newArray['belongs_to'];

	tbl_tr_highlight($i);
	$j++;
		td("30;c;".$j."");
		td_(";nw;");
			//$wynik = mysql_query("SELECT up_id FROM $dbname.serwis_komorki WHERE (up_nazwa='$temp_gdzie') and (belongs_to=$es_filia) LIMIT 1", $conn) or die($k_b);
			//list($temp_up_id)=mysql_fetch_array($wynik);
			
			$sql_up = "SELECT up_id, up_pion_id FROM $dbname.serwis_komorki WHERE (up_nazwa='$temp_gdzie') and (belongs_to=$es_filia) LIMIT 1";
			$wynik = mysql_query($sql_up, $conn) or die($k_b);
			$dane_up = mysql_fetch_array($wynik);
			$temp_up_id = $dane_up['up_id'];
			$temp_pion_id = $dane_up['up_pion_id'];
			
			// nazwa pionu z id pionu
			$sql_get_pion = "SELECT pion_nazwa FROM $dbname.serwis_piony WHERE (pion_id='$temp_pion_id') LIMIT 1";
			$wynik_get_pion = mysql_query($sql_get_pion, $conn) or die($k_b);
			$dane_get_pion = mysql_fetch_array($wynik_get_pion);
			$temp_pion_nazwa = $dane_get_pion['pion_nazwa'];
			// koniec ustalania nazwy pionu
	
			echo "<a class=normalfont title=' Szczegółowe informacje o $temp_pion_nazwa $temp_gdzie ' onclick=\"newWindow_r(700,600,'p_komorka_szczegoly.php?id=$temp_up_id')\" href=#>".highlight($temp_pion_nazwa." ".$temp_gdzie, $search)."</a>";
		_td();	
		td(";;".highlight($temp_nrzgl,$search)."");
		td_("115;;".$temp_osobar."<br />");
			if ($temp_datao!='0000-00-00 00:00:00') echo substr($temp_datao,0,16); 
			if ($temp_datao=='0000-00-00 00:00:00') echo "&nbsp;"; 
		_td();
		td_("115;;".$temp_osobaz."<br />");
			if ($temp_dataz!='0000-00-00 00:00:00') echo substr($temp_dataz,0,16);
			if ($temp_dataz=='0000-00-00 00:00:00') echo ""; 
		td(";c;".calculate_datediff($temp_datao,$temp_dataz,"dgm")."");	
		if ($temp_status==0) td("70;c;<b>otwarte</b>");
		if ($temp_status==1) td("70;c;<b>zamknięte</b>");
		if ($temp_status==2) td("70;c;<b>anulowane</b>");			
		td_("40;c;");
		if ($temp_status=='0') {
			echo "<a title= ' Zamknij zgłoszenie nr $temp_nrzgl '><input class=imgoption type=image src=img/accept.gif  onclick=\"newWindow(550,200,'z_awaria_zamknij.php?id=$temp_id')\"></a>";
		}
			echo "<a title=' Anuluj zgłoszenie awarii w $temp_gdzie '><input class=imgoption type=image src=img/delete.gif  onclick=\"newWindow(550,120,'e_awaria_anuluj.php?id=$temp_id')\"></a>";		
			echo "<a title=' Szczegółowe informacje o $temp_gdzie '><input class=imgoption type=image src=img/detail.gif onclick=\"newWindow_r(700,600,'p_komorka_szczegoly.php?id=$temp_up_id')\"></a>";	
		_td();
	$i++;
}
endtable();
?>

<script>HideWaitingMessage();</script>

<?php 
?>
