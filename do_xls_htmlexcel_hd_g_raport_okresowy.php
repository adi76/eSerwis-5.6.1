<?php 
// require_once 'cfg_eserwis.php';

require_once 'Classes/PHPExcel.php';
require_once 'Classes/PHPExcel/IOFactory.php';

include('cfg_db_helpdesk.php');

function ConnectToDatabase_HelpDesk() {
	global $link_hd, $dbtype_hd, $dbhost_hd, $dbusername_hd, $dbpassword_hd, $dbname_hd;
	$link_hd = mysql_connect( "$dbhost_hd", "$dbusername_hd" , "$dbpassword_hd" ) or die("<h2 style='font-size:13px;font-weight:bold;padding-top:10px;padding-bottom:10px;margin-top:0px;margin-bottom:5px;text-align:center;background: #FF9999;color:#313131;display: block; border: 1px solid #FF6C6C;'>Wyst¹pi³ b³¹d podczas ³¹czenia siê z baz¹ danych</h2><div align=right><input type=button class=buttons value='Spróbuj ponownie' onclick=\"window.location.reload(true);\"></div>");
	mysql_select_db("$dbname_hd", $link_hd) or die($k_b);
	return $link_hd;
}
$conn_hd = ConnectToDatabase_HelpDesk();

session_start(); 
if (!session_is_registered('es_autoryzacja')) { header("Location: ".$linkdostrony.""); }

//get contents
//define date for title
$now_date = date('d-m-Y H:i');
$okres_od = $_POST[g_okres_od];
$okres_do = $_POST[g_okres_do];
$file_ending = "xls";

if ($_REQUEST[g_tzgldata]=='data_utworzenia') $export_filename = "raport_okresowy_".$okres_od."_".$okres_do.".".$file_ending."";
if ($_REQUEST[g_tzgldata]=='data_modyfikacji') $export_filename = "raport_dzienny_dla_pracownika_".$okres_od."_".$okres_do.".".$file_ending."";

//$_POST[zapytanie] = str_replace('\','-',$_POST[zapytanie]);

$zap = $_POST[zapytanie];
$zap = str_replace('\\','',$zap);

//echo $zap;
$result = mysql_query($zap, $conn_hd) or die($k_b);
$count = mysql_num_fields($result);

//echo ">>> ".$count;

$objPHPExcel = new PHPExcel();

$styleThinBlackBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => 'FF000000'),
		),
	),
);

$naglowej_start_row = 1;
$start_row = 1;

if ($_REQUEST[g_tzgldata]=='data_utworzenia') $v = 'Data utworzenia';
if ($_REQUEST[g_tzgldata]=='data_modyfikacji') $v = 'Data modyfikacji';

$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$start_row.'', 'Numer zgÅ‚oszenia')
				->setCellValue('B'.$start_row.'', 'Nr zgÅ‚. poczty')
				->setCellValue('C'.$start_row.'', $v)
				->setCellValue('D'.$start_row.'', 'PlacÃ³wka zgÅ‚aszajÄ…ca')
				->setCellValue('E'.$start_row.'', 'Temat')
				->setCellValue('F'.$start_row.'', 'Czas realizacji')
				->setCellValue('G'.$start_row.'', 'Status')
				->setCellValue('H'.$start_row.'', 'Osoba przypisana')
				->setCellValue('I'.$start_row.'', 'Wyjazdowe ?');

$objPHPExcel->getActiveSheet()->getStyle('A'.$start_row.'')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B'.$start_row.'')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('C'.$start_row.'')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('D'.$start_row.'')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('E'.$start_row.'')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('F'.$start_row.'')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('G'.$start_row.'')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('H'.$start_row.'')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('I'.$start_row.'')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->getStyle('A'.$start_row.'')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->getActiveSheet()->getStyle('B'.$start_row.'')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->getActiveSheet()->getStyle('C'.$start_row.'')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->getActiveSheet()->getStyle('D'.$start_row.'')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->getActiveSheet()->getStyle('E'.$start_row.'')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->getActiveSheet()->getStyle('F'.$start_row.'')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->getActiveSheet()->getStyle('G'.$start_row.'')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->getActiveSheet()->getStyle('H'.$start_row.'')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->getActiveSheet()->getStyle('I'.$start_row.'')->applyFromArray($styleThinBlackBorderOutline); 

$objPHPExcel->getActiveSheet()->getStyle('A'.$start_row.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('B'.$start_row.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('C'.$start_row.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('H'.$start_row.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('F'.$start_row.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('G'.$start_row.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
$objPHPExcel->getActiveSheet()->getStyle('H'.$start_row.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);	
$objPHPExcel->getActiveSheet()->getStyle('I'.$start_row.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	

$ii = $start_row+1;
// fetch data each row, store on tabular row data
while ($row = mysql_fetch_row($result)) {

	list($status)=mysql_fetch_array(mysql_query("SELECT hd_status_opis FROM $dbname_hd.hd_status WHERE hd_status_nr='$row[0]' LIMIT 1", $conn_hd));
	
	$wyjazdowe = 'NIE';
	list($countf)=mysql_fetch_array(mysql_query("SELECT COUNT(zgl_szcz_byl_wyjazd) FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$temp_nr') AND (zgl_szcz_byl_wyjazd=1)", $conn_hd));
	if ($countf>=1) $wyjazdowe='TAK';
		
//	$numery = $row[0];
//	if ($row[7]!='') $numery .= " / ".$row[7];
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$ii.'', $row[0])
		->setCellValue('B'.$ii.'', $row[2])
		->setCellValue('C'.$ii.'', $row[4])
		->setCellValue('D'.$ii.'', $row[6])
		->setCellValue('E'.$ii.'', $row[9])
		->setCellValue('F'.$ii.'', $row[21].' minut')
		->setCellValue('G'.$ii.'', $status)
		->setCellValue('H'.$ii.'', $row[7])
		->setCellValue('I'.$ii.'', $wyjazdowe);
	
	$objPHPExcel->getActiveSheet()->getStyle('A'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('B'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

	$objPHPExcel->getActiveSheet()->getStyle('C'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
	$objPHPExcel->getActiveSheet()->getStyle('E'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle('F'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('G'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('H'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle('I'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$ii++;	

	$objPHPExcel->getActiveSheet()->getStyle('A'.($ii-1).'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('B'.($ii-1).'')->applyFromArray($styleThinBlackBorderOutline); 

	$objPHPExcel->getActiveSheet()->getStyle('C'.($ii-1).'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('D'.($ii-1).'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('E'.($ii-1).'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('F'.($ii-1).'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('G'.($ii-1).'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('H'.($ii-1).'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('I'.($ii-1).'')->applyFromArray($styleThinBlackBorderOutline); 
}

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(220);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(220);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(150);

$ii++;
$jj=$ii+1;

$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$ii.'', 'ÅÄ…czna iloÅ›Ä‡ zgÅ‚oszeÅ„ w wybranym okresie')
				->setCellValue('A'.$jj.'', 'ÅÄ…czny czas poÅ›wiÄ™cony w wybranym okresie');
				
$objPHPExcel->getActiveSheet()->getStyle('A'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);$objPHPExcel->getActiveSheet()->getStyle('A'.$jj)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	

$objPHPExcel->getActiveSheet()->getStyle('B'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);$objPHPExcel->getActiveSheet()->getStyle('B'.$jj)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('B'.$ii.'', $_REQUEST[g_zgl_razem])
				->setCellValue('B'.$jj.'', $_REQUEST[g_czas_razem]);		

$objPHPExcel->getActiveSheet()->getStyle('B'.$ii.'')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B'.$jj.'')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->setTitle('Wyjazdy');
$objPHPExcel->createSheet();

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename='.$export_filename.'');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output'); 
?>