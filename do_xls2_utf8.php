<?php 
require_once 'cfg_eserwis.php';
require_once "Classes/Writer.php";
session_start(); 
if (!session_is_registered('es_autoryzacja')) { header("Location: ".$linkdostrony.""); }

$now_date = date('d-m-Y H:i');
$rok = date('Y');
$miesiac = date('m');

$filianazwa=$_POST['filian'];
$nazwapliku=$_POST['obc1']."_";

if ($_POST[addsell]!='on') { $nazwapliku=''; }

switch ($miesiac) {
  case "01" : $miesiac_slownie="styczeń"; break;
  case "02" : $miesiac_slownie="luty"; break;
  case "03" : $miesiac_slownie="marzec"; break;
  case "04" : $miesiac_slownie="kwiecień"; break;
  case "05" : $miesiac_slownie="maj"; break;
  case "06" : $miesiac_slownie="czerwiec"; break;
  case "07" : $miesiac_slownie="lipiec"; break;
  case "08" : $miesiac_slownie="sierpień"; break;
  case "09" : $miesiac_slownie="wrzesień"; break;
  case "10" : $miesiac_slownie="październik"; break;
  case "11" : $miesiac_slownie="listopad"; break;
  case "12" : $miesiac_slownie="grudzień"; break;
}
$xls =& new Spreadsheet_Excel_Writer();
$nazwa_pliku = "Zestawienie_zakupow_sprzedazy_".$nazwapliku."Filia_$filianazwa.xls";
$xls->send($nazwa_pliku);
$xls->setVersion(8);
$sheet =& $xls->addWorksheet('Magazyn');
$sheet->setInputEncoding('utf-8');
//$title = "Dane do fakturowania za miesiÄ…c ".$miesiac_slownie." ".$rok."";
//$sheet->write(0,0,$title);

$sheet->write(0,0,'Data zakupu');
$sheet->write(0,1,'Numer faktury');
$sheet->write(0,2,'Dostawca');
$sheet->write(0,3,'Nazwa towaru lub usługi');
$sheet->write(0,4,'Ilość');
$sheet->write(0,5,'Cena zakupu');
$sheet->write(0,6,'Cena odsprzedaży');
$sheet->write(0,7,'Zlecenie fakturowania');
$sheet->write(0,8,'Numer zlecenia');
$sheet->write(0,9,'Filia');
$sheet->write(0,10,'Realizacja zakupu');
$sheet->write(0,11,'Rodzaj sprzedaży');

$sql = "SELECT pole2,pole3,pole4,pole5,pole6,pole7,pole8,pole9,pole10,pole11,pole12,pole13 FROM $dbname.serwis_temp_m1$es_filia"; //WHERE (pole14=$_POST[unique])";  

//echo "$sql\n";

$result = mysql_query($sql, $conn) or die($k_b);

$curr_format =& $xls->addFormat();
$curr_format->setNumFormat('##,## zł');

$kol = mysql_num_fields($result);
$i = 1;
while($row = mysql_fetch_row($result)) {
	for($j=0; $j<$kol;$j++) {
		if (($j==5) || ($j==6)) {
//			$sheet->write($i,$j,"".htmlspecialchars($row[$j])."",$curr_format);
			$sheet->write($i,$j,"".htmlspecialchars($row[$j])."");
		} else $sheet->write($i,$j,"".htmlspecialchars($row[$j])."");
	}
    $i++;
}

if (($_POST[addsell]=='on') && ($_POST[wykrap]>0)) {
	$sql1 = "SELECT pole2,pole3,pole4,pole5,pole6,pole7,pole8,pole9,pole10,pole11,pole12,pole13 FROM $dbname.serwis_temp_m2$es_filia";  
	$result1 = mysql_query($sql1, $conn) or die($k_b);
	$kol = mysql_num_fields($result1);
	while($row = mysql_fetch_row($result1)) {
	    for($j=0; $j<$kol;$j++) {
			if (($j==5) || ($j==6)) {
//				$sheet->write($i,$j,"".htmlspecialchars($row[$j])."",$curr_format);
				$sheet->write($i,$j,"".htmlspecialchars($row[$j])."");
			} else $sheet->write($i,$j,"".htmlspecialchars($row[$j])."");
		}
	    $i++;
	}
}
$xls->close();
?>