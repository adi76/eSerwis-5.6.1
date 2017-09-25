<?php

?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
	
starttable();
	
echo "<tr>";
echo "<th class=center width=40>LP</th>";
echo "<th>Temat <i>(ilość wyświetleń / ilość odpowiedzi)</i>";
echo "</th>";
echo "<th width=140>Autor pytania<br />Data pytania";
echo "</th>";

echo "</th>";

if ($action=='manage') 
{
	
	// -
	// access control 
	$accessLevels = array("9");
	if(array_search($es_prawa, $accessLevels)>-1)
	{
		echo "<th class=center width=55>Aktywne</th>";
	}
	// access control koniec
	// -
}

echo "<th class=center width=120>Opcje</th>";

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
	
	tbl_tr_highlight($j);

	$sql_t = "SELECT kb_pytanie_temat, kb_pytanie_data FROM $dbname.serwis_kb_pytania WHERE (kb_pytanie_id=$temp_pyt_id) LIMIT 1";
	$result_t = mysql_query($sql_t, $conn) or die($k_b);
	$newArray_t = mysql_fetch_array($result_t);
	$temp_pyt_temat = $newArray_t['kb_pytanie_temat'];
	$temp_pyt_data = $newArray_t['kb_pytanie_data'];
	
	$sql_u = "SELECT user_first_name, user_last_name FROM $dbname.serwis_uzytkownicy WHERE (user_id=$temp_user_id) LIMIT 1";
	$result_u = mysql_query($sql_u, $conn) or die($k_b);
	$newArray = mysql_fetch_array($result_u);

	$user_name = $newArray['user_first_name']." ".$newArray['user_last_name'];
	
	echo "<td class=center>$j</td>";

	$j++;
	
	if (strlen($temp_pyt_temat)>80) { $temat=substr($temp_pyt_temat,0,80).'...'; } else { $temat=$temp_pyt_temat; }
	
	echo "<td class=left><a onclick=\"newWindow_r(800,600,'p_kb_watek.php?id=$temp_pyt_id&poziom=0&action=$action')\">".highlight($temat,$search)."</a>";
	
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
	echo "<i>($p_ilosc / <b>$o_ilosc</b>)</i>";
		
	echo "</td>";

	echo "<td>$user_name";
	
	echo "<br />$temp_pyt_data</td>";	
	

	if ($action=='manage') 
	{
		echo "<td class=center>";
		if ($temp_pyt_status==0) echo "NIE<a title=' Aktywuj pytanie : $temat '><input class=imgoption type=image src=img/off.gif onclick=\"newWindow(540,295,'e_kb_pytanie.php?id=$temp_pyt_id&poziom=0&co=status&value=1&submit=1')\"></a>";
		if ($temp_pyt_status==1) echo "TAK<a title=' Deaktywuj pytanie : $temat '><input class=imgoption type=image src=img/on.gif onclick=\"newWindow(540,295,'e_kb_pytanie.php?id=$temp_pyt_id&poziom=0&co=status&value=0&submit=1')\"></a>";
		
		echo "</td>"; 
		
	}
	
		echo "<td class=center>";
		echo "<a valign=absmiddle title=' Pokaż cały wątek : $temat '><input class=imgoption type=image src=img/search.gif  onclick=\"newWindow_r(800,600,'p_kb_watek.php?id=$temp_pyt_id&poziom=0&action=$action')\"></a>";
		echo "<a title=' Dodaj odpowiedź do pytania : $temat '><input class=imgoption type=image src=img/add.gif onclick=\"newWindow_r(640,335,'d_kb_odpowiedz.php?id=$temp_pyt_id&poziom=1')\"></a>";	
	
		$zalogowany = $currentuser;
	
					if ($upload_plikow==1) {
							// zlicz ilość załączników do tego kroku
							$filecount=0;
							$directory = "attachements_kb/";
							if (glob("$directory$temp_pyt_id*.*") != false)	{
								$filecount = count(glob("$directory$temp_pyt_id*.*"));
								//echo $filecount;
							} else { //	echo 0; 
							}
							
							
							if ($filecount>0) 
							{ 
								$ikona_attach='attach.gif'; 
								
								if (($currentuser==$user_name) || ($es_nr==$KierownikId)) {
									$title_add = ' Zarządzaj załącznikami (jest już '.$filecount.' załączników do tego pytania) ';
									echo "<a href=# onclick=\"newWindow_r(800,600,'z_kb_d_zalacznik.php?pytanie_id=$temp_pyt_id&edit=1&pytanie=".urlencode($temat)."'); return false;\" title='$title_add'><input class=imgoption type=image src=img/$ikona_attach></a>";
								} else {
									$title_add = ' Przeglądaj załącznikami (jest już '.$filecount.' załączników do tego pytania) ';	
									echo "<a href=# onclick=\"newWindow_r(800,600,'z_kb_d_zalacznik.php?pytanie_id=$temp_pyt_id&edit=1&pytanie=".urlencode($temat)."'); return false;\" title='$title_add'><input class=imgoption type=image src=img/$ikona_attach></a>";
								}
							
							} else { 
								$ikona_attach='attach_new.gif'; 
								if ($currentuser==$user_name) {
									$title_add = ' Dodaj załączniki do tego pytania ';
									echo "<a href=# onclick=\"newWindow_r(800,600,'z_kb_d_zalacznik.php?pytanie_id=$temp_pyt_id&edit=1&pytanie=".urlencode($temat)."'); return false;\" title='$title_add'><input class=imgoption type=image src=img/$ikona_attach></a>";
								} else {
									
								echo "<a href=# onclick=\"newWindow_r(800,600,'z_kb_d_zalacznik.php?pytanie_id=$temp_pyt_id&edit=1&pytanie=".urlencode($temat)."'); return false;\" title='$title_add'><input class=imgoption type=image src=img/$ikona_attach></a>";
								}
							}
							
							
					//	}
					}
	
	if (($currentuser==$user_name) || ($es_prawa==9))
	{
		echo "<a title=' Edytuj pytanie : $temat '><input class=imgoption type=image src=img/edit.gif onclick=\"newWindow(640,336,'e_kb_pytanie.php?id=$temp_pyt_id&poziom=0&co=dane')\"></a>";		
		echo "<a title=' Usuń pytanie $temp_nazwa z listy '><input class=imgoption type=image src=img/delete.gif  onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_kb_pytanie.php?id=$temp_pyt_id&poziom=0')\"></a>";	
	}
		echo "</td>";
	
		
	echo "</tr>";	
	
	$i++;

}

endtable();		
?>

<script>HideWaitingMessage();</script>

<?php 
?>