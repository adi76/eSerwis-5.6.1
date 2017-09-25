<?php 
include_once('cfg_helpdesk.php');
include_once('header.php');

echo "<body OnLoad=\"document.forms[0].elements[0].focus();\" />";
//echo "SELECT * FROM $dbname_hd.hd_zgloszenie WHERE (belongs_to=$es_filia) and (zgl_poledodatkowe2='$_GET[unique]')";

pageheader("Trasa wyjazdu dla zgłoszenia seryjnego",0);
 
startbuttonsarea("right");
//echo "<input class=buttons type=button onClick=\"self.location.reload(); \" value='Odśwież widok' />";
echo "<input class=buttons id=anuluj type=button onClick=\"self.close();\" value=Zamknij>";
endbuttonsarea();


?>
</body>
</html>