<?php include_once('header.php'); ?>
<body>
<?php 
include('inc_encrypt.php');
if ($submit) { 
$unique=rand(0,999999);

$sql99="TRUNCATE TABLE serwis_temp0";
$result99=mysql_query($sql99,$conn) or die($k_b);

$tumowa=$_GET[tumowa];
$okres_od=$_GET[okres_od];
$okres_do=$_GET[okres_do];

if (($tumowa!='') && ($okres_od!='') && ($okres_do!='')) {

$sql_a = "SELECT * FROM $dbname.serwis_sprzedaz WHERE belongs_to=$es_filia and sprzedaz_data BETWEEN '$okres_od' AND '$okres_do'";

$result_a = mysql_query($sql_a, $conn) or die($k_b);
$count_rows=0;

while ($dane3 = mysql_fetch_array($result_a)) {
  $count_rows+=1;				
}

if ($count_rows==0) {
	errorheader("Nie znaleziono pozycji spełniających podane przez Ciebie kryteria");
	startbuttonsarea("right");
	addlinkbutton("'Zmień kryteria'","main.php?action=onkm");
	addbuttons("start");
	endbuttonsarea();
	echo "</body></html>";
  exit;
  
} else {

	if ($tumowa!='all') {
		pageheader("Sprzedaż towarów w okresie");
		infoheader("".$okres_od." - ".$okres_do."<br /><br />dla umowy: ".$tumowa."");
	} else {
		pageheader("Sprzedaż towarów w okresie");
		infoheader("".$okres_od." - ".$okres_do."<br /><br />dla wszystkich umów  bieżącej filii");
	}

starttable();
echo "<tr>";
echo "<th width=30 class=center>LP</th><th>&nbsp;Nazwa towaru, SN<br />Dostawca, nr faktury, data wystawienia</th><th width=60>Cena<br />netto</th><th>&nbsp;Data sprzedaży<br />Sprzedano dla</th><th>&nbsp;Numer zlecenia</th><th class=center>Opcje</th>";

echo "</tr>";
}

if ($tumowa=='all') { 
	$sql = "SELECT * FROM $dbname.serwis_sprzedaz WHERE (belongs_to=$es_filia) and (sprzedaz_data BETWEEN '$okres_od' AND '$okres_do')";

} else {
$sql = "SELECT * FROM $dbname.serwis_sprzedaz WHERE belongs_to=$es_filia and sprzedaz_umowa_nazwa='$tumowa' and sprzedaz_data BETWEEN '$okres_od' AND '$okres_do'";
}

//echo "$sql";

$result = mysql_query($sql, $conn) or die($k_b);

$i = 0;
$j = 1;
	
while ($newArray = mysql_fetch_array($result)) {
	$temp_id  			= $newArray['sprzedaz_id'];
	$temp_poz_nazwa		= $newArray['sprzedaz_pozycja_nazwa'];
	$temp_poz_sn		= $newArray['sprzedaz_pozycja_sn'];
	$temp_poz_cena_cr	= $newArray['sprzedaz_pozycja_cenanetto'];
	
	$temp_data			= $newArray['sprzedaz_data'];
	$temp_umowa			= $newArray['sprzedaz_umowa_nazwa'];
	$temp_pion			= $newArray['sprzedaz_pion_nazwa'];
	$temp_up			= $newArray['sprzedaz_up_nazwa'];
	$temp_uwagi			= $newArray['sprzedaz_uwagi'];
	$temp_fakt_id		= $newArray['sprzedaz_faktura_id'];
	$temp_rodzaj		= $newArray['sprzedaz_rodzaj'];

	$sql2 = "SELECT * FROM $dbname.serwis_umowy WHERE (umowa_nr='$temp_umowa')";
	$result2 = mysql_query($sql2, $conn) or die($k_b);	
	$newArray2 = mysql_fetch_array($result2);
	$temp_nrzlecenia = $newArray2['umowa_nr_zlecenia'];


	$temp_poz_cena	= decrypt_md5($temp_poz_cena_cr,$key);
	
	tbl_tr_highlight($i);
	
	echo "<td width=30 class=center>$j</td>";

	$sql1="SELECT * FROM $dbname.serwis_faktury WHERE ((belongs_to=$es_filia) and (faktura_id='$temp_fakt_id'))";
	//echo "$sql1";

	$result1 = mysql_query($sql1, $conn) or die($k_b);
	
	while ($newArray4 = mysql_fetch_array($result1)) {
		$temp_id4  			= $newArray4['faktura_id'];
		$temp_numer4		= $newArray4['faktura_numer'];
		$temp_data4			= $newArray4['faktura_data'];
		$temp_dostawca4		= $newArray4['faktura_dostawca'];	
	}
	
	echo "<td>$temp_poz_nazwa, $temp_poz_sn<br /><a class=opt3 onclick=\"newWindow_r(800,600,'p_faktura_pozycje.php?id=$temp_id4')\">&nbsp;$temp_dostawca4, $temp_numer4, $temp_data4&nbsp;</a>";
	
	echo "</td>";
	echo "<td>"; 
	
	if ($temp_poz_cena!=0) { echo "$temp_poz_cena zł"; }
	
	echo "</td>";

	echo "<td>$temp_data<br />";
	
	if ($temp_pion!="") { echo "$temp_pion&nbsp;"; } 
	
	echo "$temp_up</td>";


	echo "<td>$temp_nrzlecenia</td>";
	$j+=1;
	
	$r=substr($temp_data,0,4);
	$m=substr($temp_data,5,2);
	$d=substr($temp_data,8,2);
	
	
	$s1=strpos($temp_up,' ');
	$upup=substr($temp_up,$s1+1,strlen($temp_up)-$s1);
	
	//echo ':'.$upup.':';
	
	
	echo "<td width=40 class=center>";
	
	echo "<input type=image class=imgoption src=img//1print.gif title=' Generuj protokół ' onclick=\"newWindow_r(700,595,'utworz_protokol.php?c_6=on&dzien=$d&miesiac=$m&rok=$r&up=".urlencode($upup)."&nazwa_urzadzenia=".urlencode($temp_poz_nazwa)."&sn_urzadzenia=".urlencode($temp_poz_sn)."')\">";
	
	$cena = str_replace('.',',',$temp_poz_cena);
	
	$cenan1 = $temp_poz_cena*1.1;
	$cenan = str_replace('.',',',$cenan1);
	
	$sql99="INSERT INTO $dbname.serwis_temp0 VALUES ('$temp_poz_nazwa','$temp_up','1','szt.','$cena zł','$cenan zł','$temp_numer4','$temp_data4','$temp_dostawca4','$temp_rodzaj','$unique','$temp_nrzlecenia')";
	//echo "$sql99";
	$result99=mysql_query($sql99,$conn) or die($k_b);		
	
	echo "</td>";


	$i+=1;
	echo "</tr>";
}

endtable();
echo "</div>";

$sqlf="SELECT * FROM $dbname.serwis_filie WHERE filia_id=$es_filia";
$resultf = mysql_query($sqlf, $conn) or die($k_b);
$dane1f = mysql_fetch_array($resultf);
$filian = $dane1f['filia_nazwa'];

	echo "<form action=do_xls.php METHOD=post target=_blank>";
	echo "<input type=hidden name=poczatek value='$okres_od'>";
	echo "<input type=hidden name=koniec value='$okres_do'>";
	echo "<input type=hidden name=umowanr value='$tumowa'>";
	echo "<input type=hidden name=unique value=$unique>";
	echo "<input type=hidden name=filian value=$filian>";

	startbuttonsarea("right");
	addownsubmitbutton("'Generuj plik XLS'","submit");
	addlinkbutton("'Zmień kryteria'","main.php?action=onkm");
	addbuttons("start");
	endbuttonsarea();

	_form();	
	
} 

} else { ?>

<?php
	br();
	pageheader("Sprzedaż w okresie");
	echo "<form name=ruch action=p_sprzedaz_obciazenie.php method=GET>";
	echo "<table cellspacing=1 align=center style=width:400px>";
	tbl_empty_row();

	echo "<tr>";
	echo "<td class=center colspan=2>";
	echo "<b>Podaj zakres dat<br /><br /></b>";
echo "</td>";
	echo "</tr>";
	
		echo "<tr>";
		echo "<td width=150 class=center>&nbsp;od dnia</td>";
		echo "<td width=150 class=center>&nbsp;do dnia</td>";
		echo "</tr>";

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

		echo "<tr>";
			echo "<td class=center><input class=wymagane size=10 type=text id=okres_od name=okres_od value=$data1 onkeypress='return handleEnter(this, event);' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\">";			
			echo "<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif width=16 height=16 border=0></a>";
			if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('okres_od').value='".Date('Y-m-d')."'; return false;\">";
			echo "</td>";
			echo "<td class=center><input class=wymagane size=10 type=text id=okres_do name=okres_do value=$data2 onkeypress='return handleEnter(this, event);' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\">";
			echo "<a tabindex=-1 href=javascript:cal11.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif width=16 height=16 border=0></a>";
			if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('okres_do').value='".Date('Y-m-d')."'; return false;\">";
			echo "</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td class=center colspan=2>";
		echo "<b><br />dla umowy<br /><br /></b>";
		echo "</td></tr>";
		echo "<tr>";	
		echo "<td class=center colspan=2>";
		
		$sql6="SELECT * FROM $dbname.serwis_umowy WHERE belongs_to=$es_filia";
		$result6 = mysql_query($sql6, $conn) or die($k_b);
		$i = 0;
		
		echo "<select class=wymagane name=tumowa onkeypress='return handleEnter(this, event);'>\n"; 					 				
		echo "<option value='all'>Wszystkie przypisane dla aktualnej filii</option>\n"; 
				
		while ($newArray = mysql_fetch_array($result6)) 
		 {
			$temp_id  				= $newArray['umowa_id'];
			$temp_nr				= $newArray['umowa_nr'];			
			$temp_nazwa				= $newArray['umowa_opis'];
			echo "<option value='$temp_nr'>$temp_nazwa ($temp_nr)</option>\n"; 
		}

	echo "</select>\n"; 		
	echo "</td>";
	echo "</tr>";
	echo "<tr>";
	tbl_empty_row(2);	
	endtable();
	
	startbuttonsarea("right");
	addownsubmitbutton("'Generuj raport'","submit");
	endbuttonsarea();
	
	_form();	
	
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
  frmvalidator.addValidation("okres_do","req","Nie podano daty końcowej");
  frmvalidator.addValidation("tumowa","dontselect","Nie wybrano umowy");  
  frmvalidator.addValidation("okres_od","numerichyphen","Użyłeś niedozwolonych znaków w polu \"od dnia\"");
  frmvalidator.addValidation("okres_do","numerichyphen","Użyłeś niedozwolonych znaków w polu \"do dnia\"");

  
</script>

<?php } ?>
</body>
</html>