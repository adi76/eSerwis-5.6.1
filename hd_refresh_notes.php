<?php 

include_once('header_simple.php');
include_once('cfg_eserwis.php');
include_once('cfg_helpdesk.php');

	//$r40 = mysql_query("SELECT filia_leader FROM $dbname.serwis_filie WHERE (filia_id='$es_filia') LIMIT 1", $conn) or die($k_b);
	//list($KierownikId)=mysql_fetch_array($r40);
	$KierownikId = $kierownik_nr;
	
	if ($today=='') {
		$result88 = mysql_query("SELECT * FROM $dbname_hd.hd_notes WHERE (belongs_to=$es_filia) and (note_status=1) and ((note_user_id=$es_nr) or (note_creator=$es_nr)) ORDER BY note_alertdate DESC, note_id ASC", $conn_hd);
	} else {
		$result88 = mysql_query("SELECT * FROM $dbname_hd.hd_notes WHERE (belongs_to=$es_filia) and (note_status=1) and ((note_user_id=$es_nr) or (note_creator=$es_nr)) and (note_alertdate='$today')  ORDER BY note_alertdate DESC, note_id ASC", $conn_hd);	
	}
	
	$t = 0;
	$dddd=Date('Y-m-d');
	
	$span_notatki_moje = '';
	//$span_notatki_moje .= '<table align=center class=maxwidth><tr><th>Treść notatki</th><th class=center>Opcje</th></tr>';
	$o = 0;
	
	while ($dane88 = mysql_fetch_array($result88)) {
		$temp_id88			= $dane88['note_id'];	
		$temp_name88		= $dane88['note_name'];	
		$temp_tresc88		= $dane88['note_tresc'];
		$temp_alertdate88	= $dane88['note_alertdate'];
		$temp_creator88		= $dane88['note_creator'];
		$temp_user88		= $dane88['note_user_id'];	
		$temp_creation_date	= $dane88['note_creation_date'];

		$r40 = mysql_query("SELECT user_first_name, user_last_name FROM $dbname.serwis_uzytkownicy WHERE (user_id='$temp_creator88') LIMIT 1", $conn) or die($k_b);
		list($creator_fname,$creator_lname)=mysql_fetch_array($r40);
		$creator_name = $creator_fname.'+'.$creator_lname;
	
		$r40 = mysql_query("SELECT user_first_name, user_last_name FROM $dbname.serwis_uzytkownicy WHERE (user_id='$temp_user88') LIMIT 1", $conn) or die($k_b);
		list($to_fname,$to_lname)=mysql_fetch_array($r40);
		$to_person = $to_fname.'+'.$to_lname;
		
		$name_display = $temp_name88;
		if (strlen($temp_name88)>12) $name_display = substr($temp_name88,0,12).'...';	
		
		$span_notatki_moje.= "<tr "; 
		
		//if ($o % 2 != 0 ) $span_notatki_moje.=" class=nieparzyste ";
		//$span_notatki_moje.=" class=parzyste ";
		
		$span_notatki_moje.=" id=tr_notatki_$t style='margin=0px; padding:0px;'><td style='width:100%;margin=0px; padding:0px; ";
		if ($temp_alertdate88==$dddd) $span_notatki_moje.= " background-color: transparent; ";
		
		$span_notatki_moje.= "'>";
		$span_notatki_moje.= "<a title='";
		$span_notatki_moje.= $temp_name88.". ";
		if ($temp_creator88!=$temp_user88) { 
			if ($es_nr==$temp_creator88) { $span_notatki_moje.= "Notatka dla osoby: ".urldecode($to_person).""; } else { $span_notatki_moje.= "Notatka od osoby: ".urldecode($creator_name)."";  }
		} else { $span_notatki_moje.= "Notatka własna"; }
		$span_notatki_moje.= "";
		
		if ($temp_alertdate88==$dddd) $span_notatki_moje.= ". Notatka ma ustawione przypomnienie na dzisiaj ";
		if (($temp_alertdate88<$dddd) && ($temp_alertdate88!='0000-00-00')) $span_notatki_moje.= ". Data przypomnienia o notatce upłynęła $temp_alertdate88 ";
		if ($temp_alertdate88>$dddd) $span_notatki_moje.= ". Notatka ma ustawione przypomnienie na $temp_alertdate88 ";
		if ($temp_alertdate88=='0000-00-00') $span_notatki_moje.= ". Notatka nie ma ustawionej daty przypomnienia ";
		
		$span_notatki_moje.= "' class='normalfont' style='font-weight:normal; ";
		
		if ($temp_alertdate88==$dddd) $span_notatki_moje.= " color: red; ";
		if (($temp_alertdate88<$dddd) && ($temp_alertdate88!='0000-00-00')) $span_notatki_moje.= " color: grey; ";
		if ($temp_alertdate88>$dddd) $span_notatki_moje.= " color: green; ";

		$note_edit = 1;
		
		if ($temp_creator88!=$temp_user88) { 
			if ($es_nr==$temp_creator88) { } else { $note_edit=0; }
		} else { }
		

		if ($temp_creator88!=$temp_user88) { 
			if ($es_nr==$temp_creator88) { $note_edit=1; } else {  }
		} else {  }
		
	
		if ($today=='') {
			$span_notatki_moje.= "' href=# onclick=\"newWindow_r(600,400,'hd_e_note.php?noteid=$temp_id88&edit=$note_edit&creator=$temp_creator88&cr_date=".unescape($temp_creation_date)."&cr_name=".$creator_name."&refresh=0'); $('#count_notatki_moje').load('hd_count_notes.php?user_id=$es_nr&randval='+ Math.random()); $('#notatki').load('hd_refresh_notes.php?userid=$es_nr&randval='+ Math.random()); return false; \">";
		} else {
			$span_notatki_moje.= "' href=# onclick=\"newWindow_r(600,400,'hd_e_note.php?noteid=$temp_id88&edit=$note_edit&creator=$temp_creator88&cr_date=".unescape($temp_creation_date)."&cr_name=".$creator_name."&today=$today&refresh=0'); $('#count_notatki_moje').load('hd_count_notes.php?user_id=$es_nr&randval='+ Math.random()); $('#notatki').load('hd_refresh_notes.php?userid=$es_nr&randval='+ Math.random()+'&today=$today&nastartowej=1'); return false; \">";
		
		}
		
		if ($temp_creator88!=$temp_user88) { 
//			if ($es_nr==$temp_creator88) { $span_notatki_moje.= "dla"; } else { $span_notatki_moje.= "od"; }
			if ($es_nr==$temp_creator88) { $span_notatki_moje.= ""; } else { $span_notatki_moje.= ">";  $note_edit=0; }
		} else { $span_notatki_moje.= ""; }
		
		$span_notatki_moje.= "$name_display";

		if ($temp_creator88!=$temp_user88) { 
			if ($es_nr==$temp_creator88) { $span_notatki_moje.= ">"; $note_edit=1; } else { $span_notatki_moje.= ""; }
		} else { $span_notatki_moje.= ""; }
		
		$span_notatki_moje.= "</a></td>";
		$span_notatki_moje.= "<td class=center style='padding:0px; margin:0px; align:right; '>";
			$span_notatki_moje.= "<a class=normalfont href=# title=' Usuń notatkę: $temp_name88 ' onclick=\"if (confirm('Czy na pewno chcesz usunąć notatkę: $temp_name88 ?')) { document.getElementById('tr_notatki_$t').style.display='none'; newWindow_r(1,1,'hd_u_note.php?noteid=$temp_id88'); $('#count_notatki_moje').load('hd_count_notes.php?user_id=$es_nr&randval='+ Math.random()).show();
 return false;} \">";
			$span_notatki_moje.= "<input class=imgoption style='padding:0px; margin:0px;' type=image src=img/hd_note_delete.gif>"; 
			$span_notatki_moje.= "</a>";
		$span_notatki_moje.= "</td>";
		$span_notatki_moje.= "</tr>";
		$t+=1;
		$o++;
	}
//$span_notatki_moje.= "</table><br />";
echo $span_notatki_moje;

//if ($today!='') echo "<br /><br />";
if ($nastartowej=='2') {
	echo "<tr><td colspan=1><span style='margin-left:-10px;'><input type=button class=buttons value=\"Pokaż wszystkie notatki\"  onClick=\"$('#notatki').load('hd_refresh_notes.php?userid=$es_nr&randval='+ Math.random()+'&nastartowej=1&today=');\"/></span>";
	
	echo "<span style=''><input type=button class=buttons value='Pokaż notatki na dziś' onClick=\"refresh_notatki1();\"/></span></td></tr>";	
}
?>
