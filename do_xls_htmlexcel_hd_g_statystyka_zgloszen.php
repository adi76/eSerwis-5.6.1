<?php 
// require_once 'cfg_eserwis.php';

require_once 'Classes/PHPExcel.php';
require_once 'Classes/PHPExcel/IOFactory.php';

session_start(); 
if (!session_is_registered('es_autoryzacja')) { header("Location: ".$linkdostrony.""); }

//get contents
//define date for title
$okres = $_POST[okres];
$file_ending = "xls";

$export_filename = "raport_okresowy_ze_zgloszen_".$obszar."_za_okres_".$okres.".".$file_ending."";

$objPHPExcel = new PHPExcel();
$naglowej_start_row = 1;

$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$naglowej_start_row.'', 'Zarządzanie Jakością Usług IT - '.$_POST[obszar]);
$objPHPExcel->getActiveSheet()->mergeCells('A'.$naglowej_start_row.':B'.$naglowej_start_row.'');

$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('C'.$naglowej_start_row.'', 'Okres pomiaru: '.$_POST[okres]);

$naglowej_start_row++;
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$naglowej_start_row.'', '1');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$naglowej_start_row.'', 'Liczba zgłoszeń które wpłynęły w bieżącym okresie [szt.]');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$naglowej_start_row.'', $_POST[pkt_1]);

$naglowej_start_row++;
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$naglowej_start_row.'', '2');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$naglowej_start_row.'', 'Liczba zgłoszeń rozwiązanych spośród tych które wpłynęły w bieżacym okresie [szt.]');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$naglowej_start_row.'', $_POST[pkt_2]);

$naglowej_start_row++;
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$naglowej_start_row.'', '3');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$naglowej_start_row.'', 'Liczba zgłoszeń w trakcie realizacji z bieżacego okresu [szt.]');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$naglowej_start_row.'', $_POST[pkt_3]);

$naglowej_start_row++;
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$naglowej_start_row.'', '4');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$naglowej_start_row.'', 'Liczba zgłoszeń w trakcie realizacji z poprzednich okresów [szt.]');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$naglowej_start_row.'', $_POST[pkt_4]);

$naglowej_start_row++;
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$naglowej_start_row.'', '5');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$naglowej_start_row.'', 'Procent zgłoszeń rozwiązanych w bieżącym okresie [%]');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$naglowej_start_row.'', $_POST[pkt_5]);

$naglowej_start_row++;
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$naglowej_start_row.'', '6');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$naglowej_start_row.'', 'Procent zgłoszeń w trakcie realizacji w bieżącym okresie [%]');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$naglowej_start_row.'', $_POST[pkt_6]);

$naglowej_start_row++;
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$naglowej_start_row.'', '7');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$naglowej_start_row.'', 'Procent zgłoszeń reklamacyjnych [%]');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$naglowej_start_row.'', $_POST[pkt_7]);

$naglowej_start_row++;
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$naglowej_start_row.'', '8');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$naglowej_start_row.'', 'Średni czas rozwiązania zgłoszenia [godz.]');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$naglowej_start_row.'', $_POST[pkt_8]);

$objPHPExcel->getActiveSheet()->getStyle('A'.$start_row.'')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B'.$start_row.'')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('C'.$start_row.'')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->getStyle('A'.$start_row.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('C'.$start_row.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFill()->getStartColor()->setARGB('FF000080');

$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFont()->setName('Verdana');
$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFont()->setSize(9);
$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);

$styleThinBlackBorderOutline = array(
	'borders' => array(
		'allborders' => array(
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

$objPHPExcel->getActiveSheet()->getStyle('A1:C9')->applyFromArray($styleThinBlackBorderOutline); 

$objPHPExcel->getActiveSheet()->getStyle('C2:C9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('C2:C9')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A2:C9')->getFont()->setName('Verdana');
$objPHPExcel->getActiveSheet()->getStyle('A2:C9')->getFont()->setSize(8);
$objPHPExcel->getActiveSheet()->getStyle('A2:C9')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);

$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(45);

$ii = $start_row;

$objPHPExcel->getActiveSheet()->setTitle('Raport_z_bazy_zgloszen');
$objPHPExcel->createSheet();

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename='.$export_filename.'');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output'); 
?>