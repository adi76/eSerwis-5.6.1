<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) { 
$_POST=sanitize($_POST);
$sql55="SELECT * FROM $dbname.serwis_filie WHERE filia_id=$es_filia";
$result55 = mysql_query($sql55, $conn) or die($k_b);
$dane55 = mysql_fetch_array($result55);
$filian = $dane55['filia_nazwa'];

if (2==2) {

$dddd = Date('Y-m-d H:i:s');

$sql_t = "INSERT INTO $dbname.serwis_historia VALUES ('','$_POST[tid]','$_POST[tup]','$_POST[tuser]','$dddd','zwrócono z','".nl2br($_POST[tkomentarz])."',$es_filia)";

if (mysql_query($sql_t, $conn)) { 

	$sql_t1 = "UPDATE $dbname.serwis_magazyn SET magazyn_status='0' WHERE magazyn_id='$_POST[tid]' LIMIT 1";
	$wykonaj = mysql_query($sql_t1, $conn) or die($k_b);
		?><script>if (opener) opener.location.reload(true); self.close();  </script><?php
} else 
	{
	  ?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php	
		}
} else
     {
	?><script>info('Nie wypełniłeś wymaganych pól'); window.history.go(-1); </script><?php	 
	 }

} else { ?>
	
<?php
	pageheader("Zwrot sprzętu do magazynu");
	starttable();
	echo "<form name=addt action=$PHP_SELF method=POST>";	
	tbl_empty_row();

		echo "<tr>";
		echo "<td width=150 class=right>Nazwa</td>";

		$sql3 = "SELECT * FROM $dbname.serwis_magazyn WHERE magazyn_id=$id";
		$result3 = mysql_query($sql3, $conn) or die($k_b);

		while ($dane3 = mysql_fetch_array($result3)) {	
		  $mid 		= $dane3['magazyn_id'];
		  $mnazwa 	= $dane3['magazyn_nazwa'];
		  $mmodel	= $dane3['magazyn_model'];
		  $msn	 	= $dane3['magazyn_sn'];
		  $mni		= $dane3['magazyn_ni'];
		  $muwagi	= $dane3['magazyn_uwagi_sa'];
		}

		echo "<td><b>$part</b></td>";

		
	echo "</tr>";
	
	echo "<tr>";
		echo "<td width=150 class=right>Model</td>";
		echo "<td><b>$mmodel</b></td>";
	echo "</tr>";

	echo "<tr>";
		echo "<td width=150 class=right>Numer seryjny</td>";
		echo "<td><b>$msn</b></td>";
	echo "</tr>";

	echo "<tr>";
		echo "<td width=150 class=right>Numer inwentarzowy</td>";
		echo "<td><b>$mni</b></td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td width=150 class=right>Osoba dokonująca zwrotu</td>";
		echo "<td width=200 class=left><b>$currentuser</b></td>";

	echo "</tr>";

	echo "<tr>";
		echo "<td width=150 class=right>Sprzęt powrócił z</td>";
		echo "<td><b>";

		$sql_a = "SELECT * FROM $dbname.serwis_historia WHERE historia_magid=$id";
		$result_a = mysql_query($sql_a, $conn) or die($k_b);
		
		while ($dane3 = mysql_fetch_array($result_a)) {
		
			$hid 		= $dane3['historia_id'];
			$hmagin 	= $dane3['historia_magid'];
			$hup		= $dane3['historia_up'];
			$huser		= $dane3['historia_user'];
			$hdata		= $dane3['historia_data'];
			$hrs		= $dane3['historia_ruchsprzetu'];
			$hkomentarz	= $dane3['historia_komentarz'];
		}
		
		echo "$hup";

		echo "</b></td>";
	echo "</tr>";

	echo "<tr>";
		echo "<td width=150 class=right>Sprzęt pobrano</td>";
		echo "<td><b>$hdata</b></td>";
	echo "</tr>";

//  dopisać wybór UP docelowego


	echo "<tr>";
		echo "<td width=150 class=righttop>Komentarz</td>";
		echo "<td><textarea name=tkomentarz cols=37 rows=6></textarea></td>";
	echo "</tr>";
	tbl_empty_row();
	endtable();

	echo "<input type=hidden name=tstatus value='1'>";
	echo "<input type=hidden name=tup value='$hup'>";
	echo "<input type=hidden name=tid value=$id>";		
	echo "<input type=hidden name=tuser value='$currentuser'>";
	startbuttonsarea("right");
	addbuttons("zapisz","anuluj");
	endbuttonsarea();
	
	_form();
}
?>
</body>
</html>