<?php include_once('header.php'); ?>
<body>
<?php 
if ($submit) { 

$okres_od=$_GET[okres_od];
$okres_do=$_GET[okres_do];

$sql_a = "SELECT * FROM $dbname.serwis_awarie WHERE ";

if ($es_m==1) { } else { $sql_a=$sql_a."belongs_to='$es_filia' and "; }

$sql_a=$sql_a."((awaria_datazgloszenia BETWEEN '$okres_od 00:00:00' AND '$okres_do 23:59:59') or (awaria_datazamkniecia BETWEEN '$okres_od 00:00:00' AND '$okres_do 23:59:59'))";

$result_a = mysql_query($sql_a, $conn) or die(mysql_error());
$count_rows=mysql_num_rows($result_a);

// paging
// ============================================================================================================
$totalrows = mysql_num_rows($result_a);
if ($showall==0) {
  $rps=$rowpersite;
} else $rps=10000;

if(empty($page)){ $page = 1; }
$limitvalue = $page * $rps - ($rps);
$sql_a="SELECT * FROM $dbname.serwis_awarie WHERE belongs_to='$es_filia' and ((awaria_datazgloszenia BETWEEN '$okres_od 00:00:00' AND '$okres_do 23:59:59') or (awaria_datazamkniecia BETWEEN '$okres_od 00:00:00' AND '$okres_do 23:59:59')) ORDER BY awaria_datazgloszenia DESC LIMIT $limitvalue, $rps";
$result_a = mysql_query($sql_a, $conn) or die(mysql_error());
// ============================================================================================================
// koniec - paging

if ($count_rows!=0) {
echo "<h4>Historia awarii w okresie</h4><h5>$okres_od - $okres_do</h5>";
echo "<div class=show>";
if ($showall==0) {
echo "<a class=paging href=r_awarie.php?showall=1&paget=$page&okres_od=$okres_od&okres_do=$okres_do&submit=$submit>Poka¿ wszystko na jednej stronie</a>";
} else {
echo "<a class=paging href=r_awarie.php?showall=0&page=$paget&okres_od=$okres_od&okres_do=$okres_do&submit=$submit>Dziel na strony</a>";	
}
echo "</div>";

echo "<table cellspacing=1 align=center>";
echo "<tr>";
echo "<th width=30 class=center>LP</th><th>Miejsce awarii</th><th>Nr WAN-portu</th><th>Nr z³oszenia</th><th width=100>Adres IP routera</th>";

echo "<th>Osoba zg³aszaj¹ca<br />Data zg³oszenia</th>";
echo "<th>Osoba zamykaj¹ca<br />Data zakoñczenia</th>";

echo "<th class=center>Status</th>";

echo "</tr>";

$i = 0;
$j = $page*$rowpersite-$rowpersite+1;

while ($newArray = mysql_fetch_array($result_a)) {
	$temp_id  			= $newArray['awaria_id'];
	$temp_gdzie			= $newArray['awaria_gdzie'];
	$temp_nrwanportu	= $newArray['awaria_nrwanportu'];
	$temp_datao			= $newArray['awaria_datazgloszenia'];
	$temp_dataz			= $newArray['awaria_datazamkniecia'];
	$temp_nrzgl			= $newArray['awaria_nrzgloszenia'];
	$temp_ip			= $newArray['awaria_ip'];
	$temp_osobar		= $newArray['awaria_osobarejestrujaca'];
	$temp_osobaz		= $newArray['awaria_osobazamykajaca'];	
	$temp_status		= $newArray['awaria_status'];
	$temp_belongs_to	= $newArray['belongs_to'];
	
	tbl_tr_highlight($i);
	
	$i+=1;
	
	echo "<td class=center width=30>$j</td>";
	echo "<td>$temp_gdzie</td>";
	echo "<td>$temp_nrwanportu</td>";
	echo "<td>$temp_nrzgl</td>";
	echo "<td>$temp_ip</td>";


	echo "<td>$temp_osobar<br />";
	if ($temp_datao!='0000-00-00 00:00:00') echo "$temp_datao"; 
	if ($temp_datao=='0000-00-00 00:00:00') echo "&nbsp;"; 
	echo "</td>";


	echo "<td>$temp_osobaz<br />";
	if ($temp_dataz!='0000-00-00 00:00:00') echo "$temp_dataz"; 
	if ($temp_dataz=='0000-00-00 00:00:00') echo "&nbsp;"; 
	echo "</td>";
	
	if ($temp_status==0) echo "<td class=center><b>otwarte</b></td>";
	if ($temp_status==1) echo "<td class=center><b>zamkniête</b></td>";	
	
	$j=$j+1;
	echo "</tr>";
}

echo "</table>";

// paging_end
include_once('paging_end.php');
// paging_end

} else echo "<h2>Nie znaleziono pozycji spe³niaj¹cych podane przez Ciebie kryteria</h2>";

startbuttonsarea("right");
addlinkbutton("'Zmieñ kryteria'","main.php?action=hawo");
addbuttons("start");
endbuttonsarea();

} else { ?>

<?php

	echo "<br /><h4>Historia awarii w okresie</h4><br />";

	echo "<table cellspacing=1 align=center style=width:40%>";
	echo "<form name=ruch action=r_awarie.php method=GET>";	
	tbl_empty_row();

	echo "<tr>";
	echo "<td class=center colspan=2>";
	echo "<b>Podaj zakres dat<br /><br /></b>";
	echo "</td>";
	echo "</tr>";
	
		echo "<tr>";
		echo "<td width=150 class=center>od dnia</td>";
		echo "<td width=150 class=center>do dnia</td>";
		echo "</tr>";

		echo "<tr>";
			
	$r1 = Date('Y');
	$m1 = Date('m');
	$d1 = '01';
	
	$r2 = Date('Y');
	$m2 = Date('m');
	
	if ($m2==1) $d2=31;
	if ($m2==2) $d2=29;
	if ($m2==3) $d2=31;
	if ($m2==4) $d2=30;
	if ($m2==5) $d2=31;
	if ($m2==6) $d2=30;
	if ($m2==7) $d2=31;
	if ($m2==8) $d2=31;
	if ($m2==9) $d2=30;
	if ($m2==10) $d2=31;
	if ($m2==11) $d2=30;
	if ($m2==12) $d2=31;
				
	$data1=$r1.'-'.$m1.'-'.$d1;
	$data2=$r1.'-'.$m1.'-'.$d2;
			
	echo "<td class=center><input class=wymagane size=10 maxlength=10 type=text name=okres_od value=$data1 onkeypress='return handleEnter(this, event)'>&nbsp;";			
	echo "<a href=javascript:cal1.popup(); title=' Kliknij, aby wybraæ datê '><img class=imgoption src=img/cal.gif width=16 height=16 border=0></a>";
			
	echo "</td>";
	echo "<td class=center><input class=wymagane size=10 maxlength=10 type=text name=okres_do value=$data2 onkeypress='return handleEnter(this, event)'>&nbsp;";
	echo "<a href=javascript:cal11.popup(); title=' Kliknij, aby wybraæ datê '><img class=imgoption src=img/cal.gif width=16 height=16 border=0 alt='Kliknij, aby wybraæ datê'></a>";
	
	echo "</td>";
	echo "</tr>";		
	echo "<tr>";
	echo "<td width=150 class=center>RRRR-MM-DD</td>";
	echo "<td width=150 class=center>RRRR-MM-DD</td>";
	echo "</tr>";
	tbl_empty_row();
	echo "</table>";

	startbuttonsarea("center");
	addownsubmitbutton("'Poka¿'","submit");
	endbuttonsarea();
	
	echo "</form>";	
?>
<script language="JavaScript">
var cal1 = new calendar1(document.forms['ruch'].elements['okres_od']);
	cal1.year_scroll = true;
	cal1.time_comp = false;
var cal11 = new calendar1(document.forms['ruch'].elements['okres_do']);
	cal11.year_scroll = true;
	cal11.time_comp = false;
</script>

<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("ruch");
  
  frmvalidator.addValidation("okres_od","req","Nie podano daty poczatkowej");
  frmvalidator.addValidation("okres_do","req","Nie podano daty koñcowej");
  frmvalidator.addValidation("okres_od","numerichyphen","U¿y³eœ niedozwolonych znaków w polu \"od dnia\"");
  frmvalidator.addValidation("okres_do","numerichyphen","U¿y³eœ niedozwolonych znaków w polu \"do dnia\"");
	
</script>

<?php } ?>
</body>
</html>