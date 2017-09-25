<?php include_once('header.php'); ?>
<body>
<?php include('body_start.php'); ?>
<?php 
include('inc_encrypt.php');

$o = 0;
$docelowo = $_POST[filia_docelowa];

if ($submit) {

	while ($o<=$_POST[ilosc]-1)
	{
		$pozycja_do_zmiany = $_POST['pid'.$o];
		$sql5="UPDATE $dbname.serwis_faktura_szcz SET belongs_to=$docelowo WHERE (pozycja_id=$pozycja_do_zmiany) LIMIT 1";
		$result5 = mysql_query($sql5, $conn) or die($k_b);
		$o++;
	}
	
	okheader("Pomyślnie przeniesiono wybrane towary");
	
	startbuttonsarea("right");
	addownlinkbutton("'Wybierz inne towary do przesunięcia'","button","button","window.location.href='p_towary_przesuniecia.php'");
	addbuttons("start");
	endbuttonsarea();
	
} else {

$oo = 0;
$licz = 0;

while ($oo<=$_POST[ilosc])
{	
	if ($_POST['pozycja'.$oo]=='on') { $licz++; }
	$oo++;
}

if (($_POST[ilosc]>0) && ($licz>0)) {
	
	pageheader("Potwierdzenie przesuniecia towarów",1,1);

	starttable();
	
	echo "<tr><th class=center>LP</th><th>Nazwa towaru</th><th>SN</th><th class=right>Cena netto<br />z faktury</th>";
	echo "<th class=right>Cena netto<br />+ koszty</th>";
	echo "<th class=center>Opcje</th>";
	echo "</tr>";

	$j = 1;
	$i = 0;
	$kwotarazem = 0;
	$o = 0;	
	$k = 0;

	echo "<form name=przesuniecie action=$PHP_SELF method=POST>";
	
	while ($o<=$_POST[ilosc])
	{
		if ($_POST['pozycja'.$o]=='on') 
		{ 
			$pozycja_id = $_POST['pozid'.$o];
			$sql = "SELECT * FROM $dbname.serwis_faktura_szcz WHERE (pozycja_id=$pozycja_id)";
			$result = mysql_query($sql, $conn) or die($k_b);
			$newArray = mysql_fetch_array($result);
	
			$temp_id  			= $newArray['pozycja_id'];
			$temp_nrfaktury		= $newArray['pozycja_nr_faktury'];
			$temp_numer			= $newArray['pozycja_numer'];
			$temp_nazwa			= $newArray['pozycja_nazwa'];
			$temp_ilosc			= $newArray['pozycja_ilosc'];
			$temp_sn			= $newArray['pozycja_sn'];
			$temp_cenanetto_cr	= $newArray['pozycja_cena_netto'];
			$temp_status		= $newArray['pozycja_status'];
			$temp_cenanettoodsp_cr	= $newArray['pozycja_cena_netto_odsprzedazy'];
	
			$temp_cenanetto 	= decrypt_md5($temp_cenanetto_cr,$key);
			$temp_cenanettoodsp = decrypt_md5($temp_cenanettoodsp_cr,$key);
			
			$kwotarazem=$kwotarazem+$temp_cenanetto;

			echo "<input type=hidden name=pid$k value=$temp_id>";
			echo "<tr class=parzyste>";	
			echo "<td width=30 class=center>$j</td>";
			echo "<td>$temp_nazwa</td>";
			echo "<td>$temp_sn</td>";
			echo "<td class=right width=120>".correct_currency($temp_cenanetto)." zł</td>";
			echo "<td class=right width=120>".correct_currency($temp_cenanettoodsp)." zł</td>";
			echo "<td></td>";
			echo "</tr>";
			$j++;
			$k++;
		}

		$o++;

	}

	endtable();

	echo "<input type=hidden name=ilosc value=$k>";
	startbuttonsarea("right");
	echo "Łączna wartość przesuwanych towarów : <b>".correct_currency($kwotarazem)." zł</b>";
	endbuttonsarea();
	startbuttonsarea("right");
	echo "<span style='float:left'>";
	addownlinkbutton("'Wybierz inne towary do przesunięcia'","button","button","window.location.href='p_towary_przesuniecia.php'");
	echo "</span>";
	
		$sql1="SELECT * FROM $dbname.serwis_filie WHERE (filia_id<>$es_filia) ORDER BY filia_nazwa";
		$result1 = mysql_query($sql1, $conn) or die($k_b);
		echo "<select class=wymagane name=filia_docelowa onkeypress='return handleEnter(this, event);'>\n"; 					 				
		echo "<option value=''>Wybierz docelową lokalizację...</option>\n";

		while ($newArray44 = mysql_fetch_array($result1)) 
		 {
			$f_id		= $newArray44['filia_id'];
			$f_nazwa	= $newArray44['filia_nazwa'];
			echo "<option value='$f_id'>$f_nazwa</option>\n"; 
		}
		
		echo "</select>\n"; 

	//addownsubmitbutton("'Przesuń towar'","submit");
	echo "<input class=buttons type=submit id=submit name=submit style='font-weight:bold' value='Przesuń towary / usługi'>";
	addbuttons("start");
	endbuttonsarea();

	_form();
?>

<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("przesuniecie"); 
  frmvalidator.addValidation("filia_docelowa","dontselect=0","Nie wybrano docelowego miejsca przesunięcia");  

</script>
<?php
	
} else {
	errorheader("Nie wybrałeś żadnego towaru do przesunięcia");
	startbuttonsarea("right");
	addownlinkbutton("'Wybierz inne towary do przesunięcia'","button","button","window.location.href='p_towary_przesuniecia.php'");
	addbuttons("start");
	endbuttonsarea();	
	
	?>
	<script>
	self.close;
	</script>
	<?php
}


}
?>

<?php include('body_stop.php'); ?>
</body>
</html>