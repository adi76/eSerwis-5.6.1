<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body>
<?php 
$sql88="SELECT zgl_szcz_dodatkowe_osoby_wykonujace_krok FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_id=$_REQUEST[zgl_krok_id]) LIMIT 1";
$result88 = mysql_query($sql88, $conn_hd) or die($k_b);
list($osobypowiazane)=mysql_fetch_array($result88);
$osobypowiazane = $osobypowiazane . '';
$odu = $_REQUEST[osobadousuniecia].', ';

//echo "Przed: |".$osobypowiazane."|";
$osobypowiazane = str_replace($odu, '', $osobypowiazane);

//echo "<br />Po: |".$osobypowiazane."|";

$oldjuzsa = substr($_REQUEST[juzsa],0,-2);
$dddd = Date('Y-m-d H:i:s');
if ($osobypowiazane=='') $osobypowiazane1 = 'brak osób dodatkowych, ';
$lista_zmian='<u>Zmiana dodatkowych osób wykonujących krok (usunięcie):</u> <b>'.$oldjuzsa.'</b> -> <b>'.substr($osobypowiazane1,0,-2).'</b><br />';
//echo $lista_zmian;

$sql_d1="UPDATE $dbname_hd.hd_zgloszenie_szcz SET zgl_szcz_dodatkowe_osoby_wykonujace_krok='$osobypowiazane' WHERE (zgl_szcz_id = '$_REQUEST[zgl_krok_id]') LIMIT 1";
	if (mysql_query($sql_d1, $conn_hd)) { 
	
		if ($lista_zmian!='') {
			$sql_insert = "INSERT INTO $dbname_hd.hd_zgloszenie_kroki_historia_zmian values ('', '$_REQUEST[zgl_krok_id]','$dddd','$currentuser','$lista_zmian',1,'',$es_filia)";
			$wynik = mysql_query($sql_insert, $conn_hd);		
		}
		
		?><script>
		self.close(); 
		if (opener) opener.location.reload(true);
		</script><?php
	} else { 
		?><script>info('Wystąpił błąd podczas operacji usuwania osoby z kroku'); self.close(); </script><?php
	}

?>
</body>
</html>