<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body onLoad="document.forms[0].elements[0].focus();">
<?php 

$dddd=Date("Y-m-d H:i:s");
$note_name1 = "Znacznik (".$_REQUEST[nr].")";
$note_opis = "W kroku nr ".$_REQUEST[nrkroku]." zgłoszenia nr ".$_REQUEST[nr]." ustawiony jest błędnie znacznik START/STOP. Jest ".$_REQUEST[jest].", powinno być ".$_REQUEST[powinno].".";
// <input type=button class=buttons value=\'Przejdź do zgłoszenia\' onClick=\"self.close(); opener.location.href=\'hd_p_zgloszenia.php?sr=search-wyniki&search_zgl_nr=".$_REQUEST[nr]."#_SZ\'; \" >";
$note_alert1 = "0000-00-00";

	$sql_a = "INSERT INTO $dbname_hd.hd_notes VALUES ('',$kierownik_nr,'$note_name1','".$note_opis."','$note_alert1',1,$es_nr,'$dddd',$es_filia)";
	//echo $sql_a;
	
	if (mysql_query($sql_a, $conn)) { 
		?>
		<script>
		self.close();
//		if (opener) opener.document.getElementById('notes_refresh').click();	
		</script><?php
	} else {
	  ?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
	}

?>
</body>
</html>