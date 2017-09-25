<?php 
require_once 'cfg_eserwis.php';
require_once "Classes/Writer.php";

session_start(); 
if (!session_is_registered('es_autoryzacja')) { header("Location: ".$linkdostrony.""); }

//get contents
//define date for title
$now_date = date('d-m-Y H:i');
$rok = $_POST[r_rok];
$miesiac = $_POST[r_miesiac];

//$filianazwa=$_POST['filian'];
$filianazwa=$_POST['umowanr'];
if ($filianazwa=='all') $filianazwa='zbiorcze';

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
$nazwa_pliku = "obciazenie_$miesiac_slownie'$rok(".$filianazwa.").xls";
$xls->send($nazwa_pliku);
$xls->setVersion(8);
$sheet =& $xls->addWorksheet('Raport miesieczny');
$sheet->setInputEncoding('utf-8');
//$title = "Dane do fakturowania za miesiąc ".$miesiac_slownie." ".$rok."";
//$sheet->write(0,0,$title);

$sheet->write(0,0,'LP');
$sheet->write(0,1,'Nazwa towaru lub usługi');
$sheet->write(0,2,'Miejsce instalacji');
$sheet->write(0,3,'Ilość');
$sheet->write(0,4,'jedn. masy');
$sheet->write(0,5,'cena netto');
$sheet->write(0,6,'cena jedn. netto z marżą');
$sheet->write(0,7,'Numer faktury');
$sheet->write(0,8,'Data wystawienia');
$sheet->write(0,9,'Dostawca');
$sheet->write(0,10,'Rodzaj sprzedaży');
$sheet->write(0,11,'Numer zlecenia');

$sql = "SELECT pole2,pole3,pole4,pole5,pole6,pole7,pole8,pole9,pole10,pole11,pole13 FROM $dbname.serwis_temp_raport$es_filia ORDER BY pole13 ASC";
$result = mysql_query($sql, $conn) or die($k_b);

$curr_format =& $xls->addFormat();
$curr_format->setNumFormat('##,##');

$kol = mysql_num_fields($result);
    $i = 1;
	$y = 1;
    while($row = mysql_fetch_row($result))
    {
		$sheet->write($i,0,$y);
		set_time_limit(0);
        for($j=0; $j<$kol;$j++) {
			if (!isset($row[$j]) || is_null($row[$j])) {
                    $sheet->write($i, $j+1, 'NULL'); 
			} elseif (($j==4) || ($j==5)) {
//				$sheet->write($i,$j+1,"".htmlspecialchars($row[$j])."",$curr_format);
				$sheet->write($i,$j+1,"".htmlspecialchars($row[$j])."");
			} else
			$sheet->write($i,$j+1,"".htmlspecialchars($row[$j])."");
		}
        $i++;
		$y++;
	}

$xls->close();

?>