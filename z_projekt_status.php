<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php'); 
?>
<body>
<?php 
if ($submit) { 
	$dddd = Date('Y-m-d H:i:s');
	$dz='';
	$oz='';
	if ($_POST[status]==1) {
		$pa=1;
	}
	if ($_POST[status]==9) {
		$dz=$dddd;
		$oz=$currentuser;
		$pa=1;
	}
	if ($_POST[status]==0) {
		$dz='';
		$oz='';	
		$pa=0;
	}
	
	$sql = "UPDATE $dbname_hd.hd_projekty SET projekt_status=$_POST[status], projekt_active=$pa, projekt_termin_zakonczenia='$dz', projekt_osoba_zakanczajaca='$oz' WHERE projekt_id = '$_POST[zid]' LIMIT 1";
	if (mysql_query($sql, $conn)) { 
		?><script>if (opener) opener.location.reload(true); self.close();  </script><?php
	} else 	{
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php	
	}
} else { ?>
<?php
$result444 = mysql_query("SELECT projekt_id, projekt_opis, projekt_uwagi FROM $dbname_hd.hd_projekty WHERE (projekt_id='$id') LIMIT 1", $conn_hd) or die($k_b);
list($temp_id,$temp_opis,$temp_uwagi)=mysql_fetch_array($result444);

if ($s==1) okheader("Czy napewno chcesz zmienić status projektu na OTWARTY ?");
if ($s==9) okheader("Czy napewno chcesz zakończyć projekt ?");
if ($s=='cofnij') okheader("Czy napewno chcesz zmienić status projektu na NOWY ?");

infoheader("<b>".skroc_tekst($temp_opis,70)."</b>");

startbuttonsarea("center");
echo "<form action=$PHP_SELF method=POST><br />";
echo "<input type=hidden name=zid value=$temp_id>";	

if ($s=='cofnij') {
	echo "<input type=hidden name=status value=0>";
} else echo "<input type=hidden name=status value=$s>";

addbuttons("tak","nie");
endbuttonsarea();

_form();
}
?>
</body>
</html>