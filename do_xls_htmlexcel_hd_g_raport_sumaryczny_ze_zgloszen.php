<?php 
// require_once 'cfg_eserwis.php';

require_once 'Classes/PHPExcel.php';
require_once 'Classes/PHPExcel/IOFactory.php';

include('cfg_db_helpdesk.php');

$pokaz_kolumne_ze_statusem = 0;
$status_slownie = 1;

function ConnectToDatabase_HelpDesk() {
	global $link_hd, $dbtype_hd, $dbhost_hd, $dbusername_hd, $dbpassword_hd, $dbname_hd;
	$link_hd = mysql_connect( "$dbhost_hd", "$dbusername_hd" , "$dbpassword_hd" ) or die("<h2 style='font-size:13px;font-weight:bold;padding-top:10px;padding-bottom:10px;margin-top:0px;margin-bottom:5px;text-align:center;background: #FF9999;color:#313131;display: block; border: 1px solid #FF6C6C;'>Wystąpił błąd podczas łączenia się z bazą danych</h2><div align=right><input type=button class=buttons value='Spróbuj ponownie' onclick=\"window.location.reload(true);\"></div>");
	mysql_select_db("$dbname_hd", $link_hd) or die($k_b);
	return $link_hd;
}
$conn_hd = ConnectToDatabase_HelpDesk();

session_start(); 
if (!session_is_registered('es_autoryzacja')) { header("Location: ".$linkdostrony.""); }

//get contents
//define date for title
$okres = $_POST[okres];
$file_ending = "xls";

$export_filename = $_POST[opis_raportu]."_za_okres_".$okres."_z_obszaru_".$obszar.".".$file_ending."";

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
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$start_row.'', 'LP');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$start_row.'', 'Nr zgłoszenia');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$start_row.'', 'Data zgłoszenia');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$start_row.'', 'Komórka zgłaszająca');

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$start_row.'', 'Typ komórki');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$start_row.'', 'Kategoria komórki');

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$start_row.'', 'Temat zgłoszenia');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$start_row.'', 'Treść zgłoszenia');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$start_row.'', 'Kategoria zgłoszenia');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$start_row.'', 'Podkategoria zgłoszenia');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$start_row.'', 'Status zgłoszenia');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$start_row.'', 'Zasadność zgłoszenia');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$start_row.'', 'Filia / Oddział');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$start_row.'', 'Łączny czas kroków (min)');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$start_row.'', 'Sposób realizacji');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$start_row.'', 'Łączny czas dojazdu (min)');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$start_row.'', 'Łączna ilość km');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$start_row.'', 'Data ostatniej zmiany statusu');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$start_row.'', '');

$ii++;
$jj = 1;

$sql_query = "SELECT * FROM $dbname_hd.hd_temp_raport_sumaryczny_".$_REQUEST[filia_leader_nr]."";
$result = mysql_query($sql_query, $conn_hd) or die($k_b);
$kk = 1;

while ($row = mysql_fetch_row($result)) {

	// kategoria jednostki
	if ($row[6]>2) $row[6]='pozostałe';
	// inf. o sprzęcie
	if ($row[11]==' / ') $row[11]='';
	
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$ii.'', $kk)
		->setCellValue('B'.$ii.'', $row[0])
		->setCellValue('C'.$ii.'', $row[1])
		->setCellValue('D'.$ii.'', $row[2])
		
		->setCellValue('E'.$ii.'', $row[14])
		->setCellValue('F'.$ii.'', $row[15])
		
		->setCellValue('G'.$ii.'', $row[3])
		->setCellValue('H'.$ii.'', $row[4])
		->setCellValue('I'.$ii.'', $row[5])
		->setCellValue('J'.$ii.'', $row[6])
		->setCellValue('K'.$ii.'', $row[7])
		->setCellValue('L'.$ii.'', $row[8])
		->setCellValue('M'.$ii.'', $row[9])
		->setCellValue('N'.$ii.'', $row[10])
		->setCellValue('O'.$ii.'', $row[11])
		->setCellValue('P'.$ii.'', $row[12])
		->setCellValue('Q'.$ii.'', $row[13])
		->setCellValue('R'.$ii.'', $row[16])
		->setCellValue('S'.$ii.'', $row[17]);	
	
	$ii++;	
	$jj++;
	$kk++;
	
}

$ii = $start_row;

$objPHPExcel->getActiveSheet()->setTitle('Raport_sumaryczny');
$objPHPExcel->createSheet();

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename='.$export_filename.'');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output'); 
?>