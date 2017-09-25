<?php 
// require_once 'cfg_eserwis.php';

require_once 'Classes/PHPExcel.php';
require_once 'Classes/PHPExcel/IOFactory.php';

include('cfg_db.php');

$pokaz_kolumne_ze_statusem = 0;
$status_slownie = 1;

function ConnectToDatabase() {
	global $link, $dbtype, $dbhost, $dbusername, $dbpassword, $dbname;
	$link = mysql_connect( "$dbhost", "$dbusername" , "$dbpassword" ) or die("<h2 style='font-size:13px;font-weight:normal;padding-top:10px;padding-bottom:10px;margin-top:0px;margin-bottom:5px;text-align:center;background: #FF9999;color:#313131;display: block; border: 1px solid #FF6C6C;'>Wystąpił błąd podczas łączenia się z bazą danych</h2><div align=right><input type=button class=buttons value='Spróbuj ponownie' onclick=\"window.location.reload(true);\"></div>");
	mysql_select_db("$dbname", $link) or die($k_b);
	return $link;
}

$conn = ConnectToDatabase();

session_start(); 
if (!session_is_registered('es_autoryzacja')) { header("Location: ".$linkdostrony.""); }

$file_ending = "xls";

$export_filename = "lista_komorek".$file_ending."";

$objPHPExcel = new PHPExcel();

$styleThinBlackBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => 'FF000000'),
		),
	),
);

$styleThickBlackBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
			'color' => array('argb' => 'FF000000'),
		),
	),
);

$naglowej_start_row = 1;
$start_row = 1;
$ii = $start_row;
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$start_row.'', 'Nazwa komórki');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$start_row.'', 'Typ komórki');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$start_row.'', 'Kategoria komórki');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$start_row.'', 'Typ usługi');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$start_row.'', 'KOI');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$start_row.'', 'Stempel');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$start_row.'', 'Podsieć');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$start_row.'', 'Wanport');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$start_row.'', 'Telefon');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$start_row.'', 'Umowa');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$start_row.'', 'Załącznik');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$start_row.'', 'Data otwarcia');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$start_row.'', 'Data zamknięcia');

$ii++;
$jj = 1;

$sql_query = "SELECT * FROM $dbname.temp_serwis_komorki_".$_REQUEST[nr]."";
$result = mysql_query($sql_query, $conn) or die($k_b);

while ($row = mysql_fetch_row($result)) {

	// kategoria jednostki
	if ($row[4]==1) { $row[4]='TAK'; } else { $row[4]='NIE'; }
	// inf. o sprzęcie
	
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$ii.'', $row[0])
		->setCellValue('B'.$ii.'', $row[1])
		->setCellValue('C'.$ii.'', $row[2])
		->setCellValue('D'.$ii.'', $row[3])
		->setCellValue('E'.$ii.'', $row[4])
		->setCellValue('F'.$ii.'', $row[5])
		->setCellValue('G'.$ii.'', $row[6])
		->setCellValue('H'.$ii.'', $row[7])
		->setCellValue('I'.$ii.'', $row[8])
		->setCellValue('J'.$ii.'', $row[9])
		->setCellValue('K'.$ii.'', $row[10])
		->setCellValue('L'.$ii.'', $row[11])
		->setCellValue('M'.$ii.'', $row[12]);	
		$ii++;	
	}

	$ii++;	
	$jj++;

	$ii = $start_row;


$objPHPExcel->getActiveSheet()->setTitle('Baza komórek');
$objPHPExcel->createSheet();

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename='.$export_filename.'');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output'); 
?>