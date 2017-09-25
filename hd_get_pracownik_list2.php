<?php
//require_once "cfg_eserwis.php";
include_once('header.php');
require_once "cfg_helpdesk.php"; 

$sql = "SELECT hd_komorka_pracownicy_nazwa, hd_komorka_pracownicy_telefon FROM $dbname_hd.hd_komorka_pracownicy WHERE (hd_serwis_komorka_id=$_GET[komorka])";
$rsd = mysql_query($sql,$conn_hd);
$ile = mysql_num_rows($rsd);

if ($ile==0) {
	echo "&nbsp;Brak pracowników przypisanych do tej komórki";
	exit;
}

?>
&nbsp;<select id="pracownik" name="pracownik" onChange="showhide('pokaz_pracownikow','pokaz_id_pracownikow'); document.getElementById('hdoztelefon').value=this.value; document.getElementById('hd_oz').value=this.options[this.options.selectedIndex].text.toUpperCase(); document.getElementById('hd_tresc').focus();"  />\n
<?php
//echo "<option value=''></option>\n";
while($rs = mysql_fetch_array($rsd)) {
	$cnazwa = $rs['hd_komorka_pracownicy_nazwa'];
	$ctel = $rs['hd_komorka_pracownicy_telefon'];
	?><option value="<?php echo $ctel; ?>"><?php echo $cnazwa; ?></option>\n
<?php
}
?>
</select>
<input class="buttons" type="button" value="Wybierz" onClick="showhide('pokaz_pracownikow','pokaz_id_pracownikow'); document.getElementById('hdoztelefon').value=this.value; document.getElementById('hd_oz').value=document.getElementById('pracownik').options[document.getElementById('pracownik').options.selectedIndex].text.toUpperCase();  document.getElementById('hd_tresc').focus();" />
<input class="buttons" type="button" value="Anuluj" onClick="showhide('pokaz_pracownikow','pokaz_id_pracownikow'); document.getElementById('hd_tresc').focus();" />	
