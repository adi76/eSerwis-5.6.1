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
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$start_row.'', 'Typ usługi');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$start_row.'', 'Nr incydentu');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$start_row.'', 'Wewn. nr poczty');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$start_row.'', 'Moment zgłoszenia');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$start_row.'', 'Jednostka zgłaszająca');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$start_row.'', 'Przypisanie jednostki');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$start_row.'', 'Kategoria jednostki');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$start_row.'', 'Osoba zgłaszająca');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$start_row.'', 'Kategoria incydentu');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$start_row.'', 'Treść incydentu');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$start_row.'', 'Typ incydentu');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$start_row.'', 'Numer inwentarzowy lub seryjny uszkodzonego urządzenia');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$start_row.'', 'Marka/model sprzętu');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$start_row.'', 'Gwarancja na sprzęt');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$start_row.'', 'Moment reakcji');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$start_row.'', 'Przewidywany czas reakcji [min]');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$start_row.'', 'Czas reakcji [min]');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$start_row.'', 'Czy został dotrzymany czas reakcji');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$start_row.'', 'Przekroczenie określonego w Umowie czasu reakcji [min]');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$start_row.'', 'Przyczyna przesunięcia czasu reakcji');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('U'.$start_row.'', 'Status incydentu');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('V'.$start_row.'', 'Moment rozwiązania');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('W'.$start_row.'', 'Deklarowany moment rozwiązania incydentu');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('X'.$start_row.'', 'Przewidywany czas rozwiązania incydentu [min]');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Y'.$start_row.'', 'Czas rozwiązania incydentu [min]');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Z'.$start_row.'', 'Czy został przekroczony czas rozwiązania incydentu');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AA'.$start_row.'', 'Przekroczenie określonego w Umowie czasu rozwiązania incydentu [min]');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AB'.$start_row.'', 'Opis rozwiązania incydentu');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AC'.$start_row.'', 'Moment zamknięcia incydentu');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AD'.$start_row.'', 'Czas zamknięcia incydentu [min]');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AE'.$start_row.'', 'Sumaryczny czas realizacji incydentu [min]');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AF'.$start_row.'', 'Czas przestoju etapu reakcji [min]');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AG'.$start_row.'', 'Czas przestoju etapu rozwiązania incydentu [min]');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AH'.$start_row.'', 'Czas przestoju etapu zamknięcia incydentu [min]');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AI'.$start_row.'', 'Okres gwarancji');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AJ'.$start_row.'', 'Sposób załatwienia incydentu');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AK'.$start_row.'', 'Uwagi');
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AL'.$start_row.'', 'Podkategoria (poziom 2)');

if ($_REQUEST[addfilia]=='on') {
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AM'.$start_row.'', 'Filia');
}

if ($_REQUEST[addfilia]=='on') {
	if ($_REQUEST[add_time_and_kategoria]=='on') {
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AN'.$start_row.'', 'Kategoria komórki');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AO'.$start_row.'', 'Łączny czas wykonywania dla zgłoszenia (min.)');
	}
} else {
	if ($_REQUEST[add_time_and_kategoria]=='on') {
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AM'.$start_row.'', 'Kategoria komórki');
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AN'.$start_row.'', 'Łączny czas wykonywania dla zgłoszenia (min.)');
	}
}

if ($_REQUEST[addfilia]=='on') {
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AP'.$start_row.'', 'Czy sprawdzono zgłoszenie?');
}

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AQ'.$start_row.'', 'Łączny czas przejazdów (min.)');

$ii++;
$jj = 1;

$sql_query = "SELECT * FROM $dbname_hd.hd_temp_raport_rozbudowany_".$_REQUEST[filia_leader_nr]."";
$result = mysql_query($sql_query, $conn_hd) or die($k_b);

while ($row = mysql_fetch_row($result)) {

	// kategoria jednostki
	if ($row[6]>2) $row[6]='pozostałe';
	// inf. o sprzęcie
	if ($row[11]==' / ') $row[11]='';
	
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
		->setCellValue('M'.$ii.'', $row[12])
		->setCellValue('N'.$ii.'', $row[13])
		->setCellValue('O'.$ii.'', $row[14])
		->setCellValue('P'.$ii.'', $row[15])
		->setCellValue('Q'.$ii.'', $row[16])
		->setCellValue('R'.$ii.'', $row[17])
		->setCellValue('S'.$ii.'', $row[18])
		->setCellValue('T'.$ii.'', $row[19])
		->setCellValue('U'.$ii.'', $row[20])
		->setCellValue('V'.$ii.'', $row[21])
		->setCellValue('W'.$ii.'', $row[22])
		->setCellValue('X'.$ii.'', $row[23])
		->setCellValue('Y'.$ii.'', $row[24])
		->setCellValue('Z'.$ii.'', $row[25])
		->setCellValue('AA'.$ii.'', $row[26])
		->setCellValue('AB'.$ii.'', $row[27])
		->setCellValue('AC'.$ii.'', $row[28])
		->setCellValue('AD'.$ii.'', $row[29])
		->setCellValue('AE'.$ii.'', $row[30])
		->setCellValue('AF'.$ii.'', $row[31])
		->setCellValue('AG'.$ii.'', $row[32])
		->setCellValue('AH'.$ii.'', $row[33])
		->setCellValue('AI'.$ii.'', $row[34])
		->setCellValue('AJ'.$ii.'', $row[35])
		->setCellValue('AK'.$ii.'', $row[36])
		->setCellValue('AL'.$ii.'', $row[49]);	
	
	if ($_REQUEST[addfilia]=='on') 
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AM'.$ii.'', $row[46]);

/*	if ($_REQUEST[add_time_and_kategoria]=='on') {
		if ($row[47]=='9') {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AM'.$ii.'', 'Administracja');	// kategoria komórki
		} else {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AM'.$ii.'', $row[47]);	// kategoria komórki
		}
		
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AN'.$ii.'', $row[48]);	// łączny czas wykonywania
	}
*/

	if ($_REQUEST[addfilia]=='on') {
		if ($_REQUEST[add_time_and_kategoria]=='on') {
			if ($row[47]=='9') {
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AN'.$ii.'', 'Administracja');	// kategoria komórki
			} else {
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AN'.$ii.'', $row[47]);	// kategoria komórki
			}
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AO'.$ii.'', $row[48]);	// łączny czas wykonywania
		}
	} else {
		if ($_REQUEST[add_time_and_kategoria]=='on') {
			if ($row[47]=='9') {
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AM'.$ii.'', 'Administracja');	// kategoria komórki
			} else {
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AM'.$ii.'', $row[47]);	// kategoria komórki
			}
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AN'.$ii.'', $row[48]);	// łączny czas wykonywania
		}
	}
	
	if ($_REQUEST[addfilia]=='on') $objPHPExcel->setActiveSheetIndex(0)->setCellValue('AP'.$ii.'', $row[50]);
	
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('AQ'.$ii.'', $row[51]);
	
/*	$objPHPExcel->getActiveSheet()->getStyle('A'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
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
*/	
	$ii++;	
	$jj++;
}
/*
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
	
*/
	$ii = $start_row;

/*
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
*/

$objPHPExcel->getActiveSheet()->setTitle('Raport_z_bazy_zgloszen');
$objPHPExcel->createSheet();

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename='.$export_filename.'');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output'); 
?>