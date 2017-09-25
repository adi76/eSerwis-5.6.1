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
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$start_row.'', 'Nr zgł.');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$start_row.'', 'Nr zgł. poczty');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$start_row.'', 'Data zgłoszenia');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$start_row.'', 'Godzina zgłoszenia');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$start_row.'', 'Komórka zgłaszająca');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$start_row.'', 'Osoba zgłaszająca');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$start_row.'', 'Temat');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$start_row.'', 'Treść');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$start_row.'', 'Kategoria');
//$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$start_row.'', 'Podkategoria');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$start_row.'', 'Priorytet');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$start_row.'', 'Czas poświęcony (w min.)');

if ($pokaz_kolumne_ze_statusem==1) 
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$start_row.'', 'Status');

$ii++;
$jj = 1;

if ($_POST[do_punktu]=='1') $sql_query = "SELECT * FROM $dbname_hd.hd_temp_statystyka_1 ORDER BY pole1 ASC";
if ($_POST[do_punktu]=='2') $sql_query = "SELECT * FROM $dbname_hd.hd_temp_statystyka_2 ORDER BY pole1 ASC";
if ($_POST[do_punktu]=='3') $sql_query = "SELECT * FROM $dbname_hd.hd_temp_statystyka_3 ORDER BY pole1 ASC";
if ($_POST[do_punktu]=='4') $sql_query = "SELECT * FROM $dbname_hd.hd_temp_statystyka_4 ORDER BY pole1 ASC";

$result = mysql_query($sql_query, $conn_hd) or die($k_b);

while ($row = mysql_fetch_row($result)) {

if ($status_slownie==1) { $col_status = 12; } else { $col_status = 11; }
$row[9] = '';

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
		->setCellValue('K'.$ii.'', $row[10]);
		//->setCellValue('K'.$ii.'', $row[10]);
	
	if ($pokaz_kolumne_ze_statusem==1) 
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$ii.'', $row[$col_status]);

	$objPHPExcel->getActiveSheet()->getStyle('A'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('B'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('C'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('D'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('E'.$ii.'')->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('F'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('G'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('H'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('I'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('J'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('K'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 

	if ($pokaz_kolumne_ze_statusem==1) 
		$objPHPExcel->getActiveSheet()->getStyle('K'.$ii.'')->applyFromArray($styleThinBlackBorderOutline);
		
	$objPHPExcel->getActiveSheet()->getStyle('F'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle('G'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle('H'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$ii++;	
	$jj++;

}

	$objPHPExcel->getActiveSheet()->getStyle('A'.$start_row.'')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('B'.$start_row.'')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('C'.$start_row.'')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('D'.$start_row.'')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('E'.$start_row.'')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('F'.$start_row.'')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('G'.$start_row.'')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('H'.$start_row.'')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('I'.$start_row.'')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('J'.$start_row.'')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('K'.$start_row.'')->getFont()->setBold(true);
	if ($pokaz_kolumne_ze_statusem==1) 
		$objPHPExcel->getActiveSheet()->getStyle('K'.$start_row.'')->getFont()->setBold(true);
	
	$ii = $start_row;
	
	$objPHPExcel->getActiveSheet()->getStyle('A'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('B'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('C'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('D'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('E'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('F'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('G'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('H'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('I'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('J'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('K'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	if ($pokaz_kolumne_ze_statusem==1) 
		$objPHPExcel->getActiveSheet()->getStyle('K'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	
	if ($pokaz_kolumne_ze_statusem==1) {
		$objPHPExcel->getActiveSheet()->getStyle('A'.$ii.':K'.$ii.'')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$ii.':K'.$ii.'')->getFill()->getStartColor()->setARGB('FF808080');
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
		$objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		
		$objPHPExcel->getActiveSheet()->getStyle('A1:K'.($start_row+$jj-1).'')->getFont()->setName('Verdana');
		$objPHPExcel->getActiveSheet()->getStyle('A1:K'.($start_row+$jj-1).'')->getFont()->setSize(8);
		$objPHPExcel->getActiveSheet()->getStyle('A1:K'.($start_row+$jj-1).'')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
	
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
		//$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
	} else {
		$objPHPExcel->getActiveSheet()->getStyle('A'.$ii.':K'.$ii.'')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$ii.':K'.$ii.'')->getFill()->getStartColor()->setARGB('FF808080');
		$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
		$objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

		$objPHPExcel->getActiveSheet()->getStyle('A1:K'.($start_row+$jj-1).'')->getFont()->setName('Verdana');
		$objPHPExcel->getActiveSheet()->getStyle('A1:K'.($start_row+$jj-1).'')->getFont()->setSize(8);
		$objPHPExcel->getActiveSheet()->getStyle('A1:K'.($start_row+$jj-1).'')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
	}

$objPHPExcel->getActiveSheet()->setTitle('Raport_z_bazy_zgloszen');
$objPHPExcel->createSheet();

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename='.$export_filename.'');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output'); 
?>