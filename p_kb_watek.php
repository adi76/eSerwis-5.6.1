<?php include_once('header.php'); ?>
<body onLoad="document.forms[0].elements[0].focus();">
<?php

$sql1="SELECT * FROM $dbname.serwis_kb_pytania WHERE kb_pytanie_id=$id LIMIT 1";
$result1 = mysql_query($sql1, $conn) or die($k_b);

$newArray1 = mysql_fetch_array($result1);
$temp_pyt_id 		= $newArray1['kb_pytanie_id'];
$temp_pyt_user		= $newArray1['kb_user_id'];
$temp_pyt_data		= $newArray1['kb_pytanie_data'];
$temp_kat_id1		= $newArray1['kb_kategoria_id'];
$temp_temat			= $newArray1['kb_pytanie_temat'];
$temp_tresc			= $newArray1['kb_pytanie_tresc'];
$temp_keywords		= $newArray1['kb_pytanie_keywords'];
$temp_status		= $newArray1['kb_pytanie_status'];	
$temp_pyt_views		= $newArray1['kb_pytanie_views'];
$temp_file			= $newArray1['kb_pytanie_file_attachement'];	

$view_nowa = $temp_pyt_views+1;

$sql_incview = "UPDATE $dbname.serwis_kb_pytania SET kb_pytanie_views=$view_nowa WHERE kb_pytanie_id=$temp_pyt_id LIMIT 1";
$wynik = mysql_query($sql_incview, $conn) or die($k_b);

$sql_e = "SELECT * FROM $dbname.serwis_kb_kategorie WHERE (kb_kategoria_id=$temp_kat_id1) LIMIT 1";
$result = mysql_query($sql_e, $conn) or die($k_b);
$newArray = mysql_fetch_array($result);
$temp_par_id	= $newArray['kb_parent_id'];
$temp_pk_nazwa  = $newArray['kb_kategoria_nazwa'];

if ($temp_par_id!=0) 
{
	$sql_e = "SELECT * FROM $dbname.serwis_kb_kategorie WHERE (kb_kategoria_id=$temp_par_id) LIMIT 1";
	$result = mysql_query($sql_e, $conn) or die($k_b);
	$newArray = mysql_fetch_array($result);
	$temp_id	  	= $newArray['kb_kategoria_id'];
	$temp_par_id	= $newArray['kb_parent_id'];
	$temp_knazwa  	= $newArray['kb_kategoria_nazwa'];	
}

pageheader("Przeglądanie wątku");
	
	starttable();
	tbl_empty_row();

	echo "<tr>";
		echo "<td width=100 class=righttop>Temat wątku</td>";
		echo "<td><b>$temp_temat</b>";
		echo "</td>";
	echo "</tr>";	
	tbl_empty_row();
	echo "<tr>";
		echo "<td width=100 class=right>Kategoria wątku</td>";
		echo "<td>";
		echo "<b>";
		if ($temp_knazwa!='') echo "$temp_knazwa-> ";
		echo "$temp_pk_nazwa";
		echo "</b>";
		echo "</td>";
	echo "</tr>";
	tbl_empty_row();
	$sql1="SELECT user_first_name, user_last_name FROM $dbname.serwis_uzytkownicy WHERE user_id=$temp_pyt_user LIMIT 1";
	$result1 = mysql_query($sql1, $conn) or die($k_b);
	$newArray1 = mysql_fetch_array($result1);
	$user 	= $newArray1['user_first_name']." ".$newArray1['user_last_name'];
	

	echo "<tr>";
		echo "<td width=100 class=righttop>Opis wątku</td>";
		echo "<td>".($temp_tresc)."";
		echo "</td>";
	echo "</tr>";
	tbl_empty_row();
	echo "<tr>";
		echo "<td width=100 class=righttop>Data dodania</td>";
		echo "<td><b>".substr($temp_pyt_data,0,16)."</b> przez <b>$user</b>";
		echo "</td>";
	echo "</tr>";	
	tbl_empty_row();
	echo "<tr><td colspan=2>"; 
	hr(); 
	echo "</td></tr>";
	$sql_o = "SELECT * FROM $dbname.serwis_kb_odpowiedzi WHERE kb_pytanie_id=$temp_pyt_id ORDER BY kb_odpowiedz_id ASC";
	$result_o = mysql_query($sql_o, $conn) or die($k_b);
	
	while ($newArray_o = mysql_fetch_array($result_o)) 
	{
		$temp_odp_id	= $newArray_o['kb_odpowiedz_id'];
		$temp_odp_data	= $newArray_o['kb_odpowiedz_data'];
		$temp_user_id	= $newArray_o['kb_user_id'];
		$temp_user_ip	= $newArray_o['kb_user_ip'];
		$temp_odp_tresc	= $newArray_o['kb_odpowiedz_tresc'];

		$sql1="SELECT user_first_name, user_last_name FROM $dbname.serwis_uzytkownicy WHERE user_id=$temp_user_id LIMIT 1";
		$result1 = mysql_query($sql1, $conn) or die($k_b);

		$newArray1 = mysql_fetch_array($result1);
		$user 	= $newArray1['user_first_name']." ".$newArray1['user_last_name'];
		
		echo "<tr>";
			
			echo "<td width=100 class=right>Odpowiedź</td>";
			echo "<td valign=top><i>udzielona <b>$temp_odp_data</b> przez <b>$user</b> :</i>";
			
			echo "<span class=right>";
			$zalogowany = $currentuser;
			if (($zalogowany==$user) || ($es_m==1) || ($es_prawa==9))
			{
				
				echo "&nbsp;<a title=' Edytuj odpowiedź '><input align=top class=border0 type=image src=img/edit_o.gif onclick=\"newWindow(640,355,'e_kb_odpowiedz.php?id=$temp_odp_id&poziom=0&co=dane')\"></a>&nbsp;";
			}
			
			if ($es_prawa==9)
			{
				echo "<a title=' Usuń odpowiedź '><input type=image class=imgoption src=img/delete.gif  onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_kb_odpowiedz.php?id=$temp_odp_id&poziom=0')\"></a>";	
			}
			echo "</span>";
			echo "</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td width=100 class=righttop></td>";
			echo "<td>".$temp_odp_tresc."</td>";
		echo "</tr>";		
		echo "<tr><td colspan=2>";
		hr();
		echo "</td></tr>";		
	}
	tbl_empty_row();
	endtable();

$thelist = '';
$iii = 1;
$thelist .= '<center><table width=100%><tr><th width=20 style=\'text-align:center\'>LP</th><th>Nazwa</th>';
if ($_GET[edit]==1) $thelist .= '<th width=30 style=\'text-align:center\'>Opcje</th>';
$thelist .= '</tr>';

if ($handle = opendir('attachements_kb')) {
	while (false !== ($file = readdir($handle))) {
		if ($file != "." && $file != "..") {
		
			$l = strlen($_REQUEST[id])+1;
			
			$porow = $_REQUEST[id].'_';
			
			$newname = substr($file,$l,strlen($file));
			
			$ext = strtoupper(substr($file,strlen($file)-3,3));
		
			if (substr($file,0,$l)==$porow) {
				
				if ($iii % 2 != 0 ) $thelist .= "<tr style='background-color:#E3E3E3'>";
				if ($iii % 2 == 0 ) $thelist .= "<tr style='background-color:#DEDEDE'>";
				
				$thelist .= "<td style='text-align:center'>".$iii."</td><td>";
				switch ($ext) {
					case "JPG" : $thelist .= "<a class=highslide onclick=\"return hs.expand(this, { contentId: 'podglad_img', align: 'center', allowSizeReduction: true, width:580 } )\" href=\"attachements_kb/".$file."\"><img border=0 align=absmiddle src=\"img/attachements_image.gif\" style='border:0px solid;' />";
									$thelist .= '&nbsp;'; break;
									
					case "GIF" : $thelist .= "<a class=highslide onclick=\"return hs.expand(this, { contentId: 'podglad_img', align: 'center', allowSizeReduction: true, width:580 } )\" href=\"attachements_kb/".$file."\"><img border=0 align=absmiddle src=\"img/attachements_image.gif\" style='border:0px solid;' />";
									$thelist .= '&nbsp;'; break;
									
					case "PNG" : $thelist .= "<a class=highslide onclick=\"return hs.expand(this, { contentId: 'podglad_img', align: 'center', allowSizeReduction: true, width:580 } )\" href=\"attachements_kb/".$file."\"><img border=0 align=absmiddle src=\"img/attachements_image.gif\" style='border:0px solid;' />";
									$thelist .= '<img src=img/attachements_image.gif border=0>&nbsp;'; break;
					
					case "TXT" : $thelist .= '<a target=_blank class=normalfont href="attachements_kb/'.$file.'">';
								$thelist .= '<img src=img/attachements_text.gif border=0>&nbsp;'; break;
					
					case "DOC" :$thelist .= '<a target=_blank class=normalfont href="attachements_kb/'.$file.'">'; 
								$thelist .= '<img src=img/attachements_word.gif border=0>&nbsp;'; break;

					case "PDF" :$thelist .= '<a target=_blank class=normalfont href="attachements_kb/'.$file.'">'; 
								$thelist .= '<img src=img/attachements_pdf.gif border=0>&nbsp;'; break;
					
					case "XLS" : $thelist .= '<a target=_blank class=normalfont href="attachements_kb/'.$file.'">';
									$thelist .= '<img src=img/attachements_excel.gif border=0>&nbsp;'; break;
					
					case "ZIP" : $thelist .= '<a target=_blank class=normalfont href="attachements_kb/'.$file.'">';
								$thelist .= '<img src=img/attachements_zip.gif border=0>&nbsp;'; break;
					
					case "RAR" : $thelist .= '<a target=_blank class=normalfont href="attachements_kb/'.$file.'">';
								$thelist .= '<img src=img/attachements_rar.gif border=0>&nbsp;'; break;
					
					case ".7Z" : $thelist .= '<a target=_blank class=normalfont href="attachements_kb/'.$file.'">';
								$thelist .= '<img src=img/attachements_7z.gif border=0>&nbsp;'; break;				
				}
				
				

				$thelist .= $newname.'</a></td>';

				if ($_GET[edit]==1) {		
					$thelist .= "<td style='text-align:center'>";
					$thelist .= "<a title=' Usuń załącznik o nazwie ".$file." z pytania ".$_GET[pytanie]."'><input class=imgoption type=image src=img/attachements_delete.gif onclick=\"newWindow_r(600,150,'hd_u_zalacznik.php?path=".urlencode('attachements_kb/'.$file.'')."&nazwapliku=".urlencode($newname)."');\"></a>";
					$thelist .= "</td>";
				}

				$thelist .= "</tr>";
				$iii++;			
				
			}
		}
		
	}
	closedir($handle);
}
$thelist.='</table></center>';
if (strlen($thelist)>161) {
	okheader('Lista już dołączonych plików do tego pytania '.$_GET[pytanie].'',0,0);
	echo $thelist;
} else errorheader("Do tego pytania brak jest załączników");

	
	startbuttonsarea("right");
	addownlinkbutton("'Dodaj odpowiedź'","submit","submit","newWindow_r(800,500,'d_kb_odpowiedz.php?id=$temp_pyt_id&poziom=1')");

	if ($_REQUEST[norefresh]=='1') {
		addbuttons("zamknij");
	} else {
		addclosewithreloadbutton("Zamknij");
	}
		
//	addclosewithreloadbutton("Zamknij");
	endbuttonsarea();
	
?>
<link rel="stylesheet" href="js/highslide/highslide/highslide.css" type="text/css" />
<script type="text/javascript" src="js/highslide/highslide/highslide-full.packed.js"></script>

<script type="text/javascript">
//<![CDATA[
hs.registerOverlay({
	html: '<div class="closebutton" onclick="return hs.close(this)" title="Close"></div>',
	position: 'top right',
	fade: 2 // fading the semi-transparent overlay looks bad in IE
});

hs.graphicsDir = 'js/highslide/highslide/graphics/';
hs.wrapperClassName = 'borderless';
//]]>
</script>

</body>
</html>