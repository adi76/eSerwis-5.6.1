<?php include_once('header.php'); ?>
<body>
<?php 
include('body_start.php');
if ($opis=='1') { $od = ''; } else { $od = 'none'; }
$sql="SELECT * FROM $dbname.serwis_kb_pytania WHERE kb_kategoria_id=$id ORDER BY kb_pytanie_data DESC";
$result = mysql_query($sql, $conn) or die($k_b);
$totalrows = mysql_num_rows($result);

if ($showall==0) {
  $rps=$rowpersite;
} else $rps=10000;

if(empty($page)){ $page = 1; }
$limitvalue = $page * $rps - ($rps);

$sql=$sql." LIMIT $limitvalue, $rps";

// -
// koniec - paging

// nazwa kategorii
$sql_name = "SELECT kb_kategoria_nazwa, kb_kategoria_id FROM $dbname.serwis_kb_kategorie WHERE (kb_kategoria_id=$id)";
$result_name 		= mysql_query($sql_name, $conn) or die($k_b);
$newArray_name		= mysql_fetch_array($result_name);
$kategoria_nazwa	= $newArray_name['kb_kategoria_nazwa'];
$kategoria_id		= $newArray_name['kb_kategoria_id'];
	
if ($totalrows!=0) {

if ($poziom==0) $kat = 'kategorii';
if ($poziom==1) $kat = 'podkategorii';

if ($action=='manage') 
{
	pageheader("Zarządzanie wątkami z ".$kat." <b>".$kategoria_nazwa."</b>");
}

if ($action=='view') 
{
	pageheader("Wątek z <b>".$kat." ".$kategoria_nazwa."</b>");
}

?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
startbuttonsarea("center");
if ($showall==0) {
	echo "<a class=paging href=$PHP_SELF?showall=1&paget=$page&poziom=$poziom&action=$action&id=$id>Pokaż wszystko na jednej stronie</a>";
} else {
	echo "<a class=paging href=$PHP_SELF?showall=0&page=$paget&poziom=$poziom&action=$action&id=$id>Dziel na strony</a>";	
}
endbuttonsarea();

starttable();
	
echo "<tr>";
echo "<th class=center width=30>LP</th>";
echo "<th>Temat wątku<br /><sub><font color=red>ilość wyświetleń</font> / <font color=blue>ilość odpowiedzi</font> / <font color=green>ilość załączników</font></sub></th>";
echo "<th>Autor wątku<br />Data dodania wątku</th>";

if ($action=='manage') {
	$accessLevels = array("9");	if(array_search($es_prawa, $accessLevels)>-1) {
		echo "<th width=55>Aktywne</th>";
	}
}

echo "<th class=center width=100>Opcje</th>";

echo "</tr>";
$i = 0;
$j = 1;

while ($newArray = mysql_fetch_array($result)) {

	$temp_pyt_id		= $newArray['kb_pytanie_id'];
	$temp_user_id		= $newArray['kb_user_id'];
	$temp_user_ip		= $newArray['kb_user_ip'];
	$temp_pyt_data		= $newArray['kb_pytanie_data'];
	$temp_pyt_temat		= $newArray['kb_pytanie_temat'];
	$temp_pyt_tresc		= $newArray['kb_pytanie_tresc'];
	$temp_pyt_key		= $newArray['kb_pytanie_keywords'];
	$temp_pyt_status	= $newArray['kb_pytanie_status'];
	$temp_pyt_views		= $newArray['kb_pytanie_views'];
	$temp_pyt_file		= $newArray['kb_pytanie_file_attachement'];
	$temp_zgl_nr		= $newArray['kb_pytanie_zgl_nr'];

	if ($upload_plikow==1) {
		// zlicz ilość załączników do tego kroku
		$filecount=0;
		$directory = "attachements_kb/";
		if (glob("$directory$temp_pyt_id*.*") != false)	{
			$filecount = count(glob("$directory$temp_pyt_id*.*"));
			//echo $filecount;
		} 
	}
					
 	if ($j % 2 != 0 ) echo "<tr id=$j class=nieparzyste onmouseover=rowOver('$j',1); this.style.cursor=arrow onmouseout=rowOver('$j',0) onclick=selectRow('$j') ondblclick=deSelectRow('$j')>";
	if ($j % 2 == 0 ) echo "<tr id=$j class=parzyste onmouseover=rowOver('$j',1); this.style.cursor=arrow onmouseout=rowOver('$j',0) onclick=selectRow('$j') ondblclick=deSelectRow('$j')>";

	$sql_u = "SELECT user_first_name, user_last_name FROM $dbname.serwis_uzytkownicy WHERE (user_id=$temp_user_id) LIMIT 1";
	$result_u = mysql_query($sql_u, $conn) or die($k_b);
	$newArray = mysql_fetch_array($result_u);

	$user_name = $newArray['user_first_name']." ".$newArray['user_last_name'];
	
	echo "<td class=center>$j</td>";

	$j++;
	
	if (strlen($temp_pyt_temat)>80) { $temat=substr($temp_pyt_temat,0,80).'...'; } else { $temat=$temp_pyt_temat; }
	
	echo "<td><a href=# class=normalfont onclick=\"newWindow_r(800,600,'p_kb_watek.php?id=$temp_pyt_id&poziom=0&action=$action')\">$temat</a>";
	
// wpisanie ilości wyświetleń pytania 
   	$sql_p_ilosc = "SELECT kb_pytanie_views FROM $dbname.serwis_kb_pytania WHERE (kb_pytanie_id=$temp_pyt_id) LIMIT 1";
	$result1 = mysql_query($sql_p_ilosc, $conn) or die($k_b);
	$ilosc = mysql_fetch_array($result1);
	$p_ilosc = $ilosc['kb_pytanie_views'];
// --------
// ilość odpowiedzi 
   	$sql_o_ilosc = "SELECT kb_odpowiedz_id FROM $dbname.serwis_kb_odpowiedzi WHERE (kb_pytanie_id=$temp_pyt_id)";
	$result1 = mysql_query($sql_o_ilosc, $conn) or die($k_b);
	$o_ilosc = mysql_num_rows($result1);
// --------
	echo "<br /><sub><b><font color=red>$p_ilosc</font> / <font color=blue>$o_ilosc</font> / <font color=green>$filecount</font></sub></b>";
	echo "</td>";

	echo "<td>$user_name";
	
	echo "<br /><font color=grey>".substr($temp_pyt_data,0,16)."</font></td>";	
	

	if ($action=='manage') 
	{
		echo "<td class=center>";
		if ($temp_pyt_status==0) echo "NIE<a title=' Aktywuj pytanie : $temat '><input class=imgoption type=image src=img/off.gif onclick=\"newWindow(540,295,'e_kb_pytanie.php?id=$temp_pyt_id&poziom=0&co=status&value=1&submit=1')\"></a>";
		if ($temp_pyt_status==1) echo "TAK<a title=' Deaktywuj pytanie : $temat '><input class=imgoption type=image src=img/on.gif onclick=\"newWindow(540,295,'e_kb_pytanie.php?id=$temp_pyt_id&poziom=0&co=status&value=0&submit=1')\"></a>";
		
		echo "</td>"; 
		
	}
	
		echo "<td class=center>";
		echo "<a valign=absmiddle title='Pokaż cały wątek: $temat '><input class=imgoption type=image src=img/search.gif  onclick=\"newWindow_r(800,600,'p_kb_watek.php?id=$temp_pyt_id&poziom=0&action=$action')\"></a>";
		echo "<a title='Dodaj odpowiedź do wątku: $temat '><input class=imgoption type=image src=img/add.gif onclick=\"newWindow_r(800,500,'d_kb_odpowiedz.php?id=$temp_pyt_id&poziom=1')\"></a>";	
	
		$zalogowany = $currentuser;
	
					if ($upload_plikow==1) {		
							if ($filecount>0) 
							{ 
								$ikona_attach='attach.gif'; 
								
								if (($currentuser==$user_name) || ($es_nr==$KierownikId)) {
									$title_add = 'Zarządzaj załącznikami (jest już '.$filecount.' załączników do tego wątku) ';
									echo "<a href=# onclick=\"newWindow_r(800,600,'z_kb_d_zalacznik.php?pytanie_id=$temp_pyt_id&edit=1&pytanie=".urlencode($temat)."'); return false;\" title='$title_add'><input class=imgoption type=image src=img/$ikona_attach></a>";
								} else {
									$title_add = 'Przeglądaj załącznikami (jest już '.$filecount.' załączników do tego wątku) ';	
									echo "<a href=# onclick=\"newWindow_r(800,600,'z_kb_d_zalacznik.php?pytanie_id=$temp_pyt_id&edit=1&pytanie=".urlencode($temat)."'); return false;\" title='$title_add'><input class=imgoption type=image src=img/$ikona_attach></a>";
								}
							
							} else { 
								$ikona_attach='attach_new.gif'; 
								if ($currentuser==$user_name) {
									$title_add = 'Dodaj załączniki do tego wątku ';
									echo "<a href=# onclick=\"newWindow_r(800,600,'z_kb_d_zalacznik.php?pytanie_id=$temp_pyt_id&edit=1&pytanie=".urlencode($temat)."'); return false;\" title='$title_add'><input class=imgoption type=image src=img/$ikona_attach></a>";
								} else {
									
								echo "<a href=# onclick=\"newWindow_r(800,600,'z_kb_d_zalacznik.php?pytanie_id=$temp_pyt_id&edit=1&pytanie=".urlencode($temat)."'); return false;\" title='$title_add'><input class=imgoption type=image src=img/$ikona_attach></a>";
								}
							}
							
							
					//	}
					}
					
	//	echo "<a href=# onclick=\"newWindow_r(800,600,'z_kb_d_zalacznik.php?edit=1'); return false;\" title='Dodaj załącznik do wątku'><input class=imgoption type=image src=img/$ikona_attach></a>";
	
	if (($action=='manage') || ($zalogowany==$user_name) || ($es_prawa==9))
	{
		echo "<a title=' Edytuj wątek: $temat '><input class=imgoption type=image src=img/edit.gif onclick=\"newWindow(700,400,'e_kb_pytanie.php?id=$temp_pyt_id&poziom=0&co=dane')\"></a>";		
		echo "<a title=' Usuń pytanie $temp_nazwa z listy '><input class=imgoption type=image src=img/delete.gif  onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_kb_pytanie.php?id=$temp_pyt_id&poziom=0')\"></a>";	
	}
	
			if ($temp_zgl_nr>0) {
				$LinkHDZglNr=$temp_zgl_nr; include('linktohelpdeskparent.php');
			}
			
		echo "</td>";
	
		
	echo "</tr>";	
	
	$i++;

}

endtable();	


// paging_end
include_once('paging_end.php');
// paging_end

} else 
{
	if ($poziom==0) errorheader("Brak pytań w kategorii \"<font color=white>".$kategoria_nazwa."</font>\"");
	if ($poziom==1) errorheader("Brak pytań w podkategorii \"<font color=white>".$kategoria_nazwa."</font>\"");
}	

startbuttonsarea("right");

//echo "<span style='float:left'>";
//addownlinkbutton("'Wstecz'","button","button","document.history.go(-1);");
//echo "</span>";

addownlinkbutton("'Dodaj nowy wątek'","button", "button","newWindow(700,500,'d_kb_pytanie.php?poziom=1&id=$kategoria_id')");
addclosewithreloadbutton("Zamknij");
endbuttonsarea();

include('body_stop.php'); 
?>
<script>HideWaitingMessage();</script>
</body>
</html>