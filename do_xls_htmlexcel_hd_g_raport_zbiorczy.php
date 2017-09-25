<?php 
// require_once 'cfg_eserwis.php';

require_once 'Classes/PHPExcel.php';
require_once 'Classes/PHPExcel/IOFactory.php';

include('cfg_db_helpdesk.php');

function ConnectToDatabase_HelpDesk() {
	global $link_hd, $dbtype_hd, $dbhost_hd, $dbusername_hd, $dbpassword_hd, $dbname_hd;
	$link_hd = mysql_connect( "$dbhost_hd", "$dbusername_hd" , "$dbpassword_hd" ) or die("<h2 style='font-size:13px;font-weight:bold;padding-top:10px;padding-bottom:10px;margin-top:0px;margin-bottom:5px;text-align:center;background: #FF9999;color:#313131;display: block; border: 1px solid #FF6C6C;'>Wyst�pi� b��d podczas ��czenia si� z baz� danych</h2><div align=right><input type=button class=buttons value='Spr�buj ponownie' onclick=\"window.location.reload(true);\"></div>");
	mysql_select_db("$dbname_hd", $link_hd) or die($k_b);
	return $link_hd;
}
$conn_hd = ConnectToDatabase_HelpDesk();

// formats money to a whole number or with 2 decimals; includes a dollar sign in front
function formatMoney2($number, $cents = 1) { // cents: 0=never, 1=if needed, 2=always
  if (is_numeric($number)) { // a number
    if (!$number) { // zero
      $money = ($cents == 2 ? '0.00' : '0'); // output zero
    } else { // value
      if (floor($number) == $number) { // whole number
        $money = number_format($number, ($cents == 2 ? 2 : 0)); // format
      } else { // cents
        $money = number_format(round($number, 2), ($cents == 0 ? 0 : 2)); // format
      } // integer or decimal
    } // value
    return $money.' zł';
  } // numeric
} // formatMoney

function formatMoney4($number, $cents = 1) { // cents: 0=never, 1=if needed, 2=always
  if (is_numeric($number)) { // a number
    if (!$number) { // zero
      $money = ($cents == 4 ? '0.00' : '0'); // output zero
    } else { // value
      if (floor($number) == $number) { // whole number
        $money = number_format($number, ($cents == 4 ? 4 : 0)); // format
      } else { // cents
        $money = number_format(round($number, 4), ($cents == 0 ? 0 : 4)); // format
      } // integer or decimal
    } // value
    return $money.' zł';
  } // numeric
} // formatMoney

session_start(); 
if (!session_is_registered('es_autoryzacja')) { header("Location: ".$linkdostrony.""); }

//get contents
//define date for title
$now_date = date('d-m-Y H:i');
$okres = $_POST[g_okres];
$pojazd = $_POST[g_pojazd];
$osoba = $_POST[g_osoba];
$osoba1 = str_replace(' ','_',$osoba);
$file_ending = "xls";

//$filianazwa=$_POST['filian'];
$o1 = str_replace('-','\'',$_REQUEST[g_okres_od]);
$o2 = str_replace('-','\'',$_REQUEST[g_okres_do]);

$export_filename = "".$o1."-".$o2."_".$_REQUEST[filia_skrot].".".$file_ending."";

$sql = "SELECT * FROM $dbname_hd.hd_temp_raport_zbiorczy_".$_REQUEST[tbl_suffix]."";
	
$result = mysql_query($sql, $conn_hd) or die($k_b);
$count = mysql_num_fields($result);

$objPHPExcel = new PHPExcel();

$naglowej_start_row = 1;

$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$naglowej_start_row.'', 'Raport zbiorczy ze zgłoszeń za okres: '.$_REQUEST[g_okres_od].' - '.$_REQUEST[g_okres_do].'');
//$objPHPExcel->getActiveSheet()->mergeCells('A'.$naglowej_start_row.':C'.$naglowej_start_row.'');

$naglowej_start_row++;
$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$naglowej_start_row.'', 'Raport dla obszaru: '.$_REQUEST[g_zakres].'');
//$objPHPExcel->getActiveSheet()->mergeCells('A'.$naglowej_start_row.':C'.$naglowej_start_row.'');

$naglowej_start_row++;
$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$naglowej_start_row.'', 'Raport dla kategorii: '.$_REQUEST[g_kat].'');
//$objPHPExcel->getActiveSheet()->mergeCells('A'.$naglowej_start_row.':C'.$naglowej_start_row.'');

$start_row = 5;

if ($_REQUEST[g_addcategories]=='on') {
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$start_row.'', 'LP')
		->setCellValue('B'.$start_row.'', 'Imię i nazwisko')
		->setCellValue('C'.$start_row.'', 'Ilość zgłoszen zarejestrowanych (IZZ)')
		->setCellValue('D'.$start_row.'', 'Ilość wykonanych kroków (IWK)')
		->setCellValue('E'.$start_row.'', 'Łączny czas poświęcony na wykonanie (CZAS)')
		->setCellValue('F'.$start_row.'', 'Łączny czas poświęcony na przejazdy (CZAS)')
		->setCellValue('G'.$start_row.'', 'Konsultacje (IZZ)') 
		->setCellValue('H'.$start_row.'', 'Konsultacje (IWK)')
		->setCellValue('I'.$start_row.'', 'Konsultacjee (CZAS)')
		->setCellValue('J'.$start_row.'', 'Awarie (IZZ)')
		->setCellValue('K'.$start_row.'', 'Awarie (IWK)')
		->setCellValue('L'.$start_row.'', 'Awarie (CZAS)')
		->setCellValue('M'.$start_row.'', 'Awarie krytyczne (IZZ)')
		->setCellValue('N'.$start_row.'', 'Awarie krytyczne (IWK)')
		->setCellValue('O'.$start_row.'', 'Awarie krytyczne (CZAS)')				
		->setCellValue('P'.$start_row.'', 'Prace zlecone w ramach umowy (IZZ)')
		->setCellValue('Q'.$start_row.'', 'Prace zlecone w ramach umowy (IWK)')
		->setCellValue('R'.$start_row.'', 'Prace zlecone w ramach umowy (CZAS)')
		->setCellValue('S'.$start_row.'', 'Prace zleconee poza umową (IZZ)')
		->setCellValue('T'.$start_row.'', 'Prace zleconee poza umową (IWK)')
		->setCellValue('U'.$start_row.'', 'Prace zleconee poza umową (CZAS)')
		->setCellValue('V'.$start_row.'', 'Prace na potrzeby Postdata (IZZ)')
		->setCellValue('W'.$start_row.'', 'Prace na potrzeby Postdata (IWK)')
		->setCellValue('X'.$start_row.'', 'Prace na potrzeby Postdata (CZAS)')
		->setCellValue('Y'.$start_row.'', 'Konserwacje (IZZ)')
		->setCellValue('Z'.$start_row.'', 'Konserwacje (IWK)')
		->setCellValue('AA'.$start_row.'', 'Konserwacje (CZAS)')		
		->setCellValue('AB'.$start_row.'', 'Filia/Oddział');
} else {
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$start_row.'', 'LP')
			->setCellValue('B'.$start_row.'', 'Imię i nazwisko')
			->setCellValue('C'.$start_row.'', 'Ilość zgłoszen zarejestrowanych (IZZ)')
			->setCellValue('D'.$start_row.'', 'Ilość wykonanych kroków (IWK)')
			->setCellValue('E'.$start_row.'', 'Łączny czas poświęcony na wykonanie (CZAS)')
			->setCellValue('F'.$start_row.'', 'Łączny czas poświęcony na przejazdy (CZAS)')
			->setCellValue('G'.$start_row.'', 'Filia/Oddział');
		}

//$objPHPExcel->getActiveSheet()->getStyle('A'.$start_row.'')->getFont()->setBold(true);
//$objPHPExcel->getActiveSheet()->getStyle('B'.$start_row.'')->getFont()->setBold(true);
//$objPHPExcel->getActiveSheet()->getStyle('C'.$start_row.'')->getFont()->setBold(true);

//$objPHPExcel->getActiveSheet()->getStyle('D'.$start_row.'')->getFont()->setBold(true);
//$objPHPExcel->getActiveSheet()->getStyle('E'.$start_row.'')->getFont()->setBold(true);
//$objPHPExcel->getActiveSheet()->getStyle('F'.$start_row.'')->getFont()->setBold(true);

/*
$objPHPExcel->getActiveSheet()->getStyle('A'.$start_row.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('B'.$start_row.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('D'.$start_row.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('E'.$start_row.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('F'.$start_row.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('G'.$start_row.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('H'.$start_row.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
*/

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

$ii = $start_row+1;
/*
$objPHPExcel->getActiveSheet()->getStyle('A'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->getActiveSheet()->getStyle('B'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->getActiveSheet()->getStyle('C'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->getActiveSheet()->getStyle('D'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->getActiveSheet()->getStyle('E'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
//$objPHPExcel->getActiveSheet()->getStyle('F'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->getActiveSheet()->getStyle('F'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->getActiveSheet()->getStyle('G'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->getActiveSheet()->getStyle('H'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	
$objPHPExcel->getActiveSheet()->getStyle('A'.$ii.':H'.$ii.'')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A'.$ii.':H'.$ii.'')->getFill()->getStartColor()->setARGB('FF808080');
*/

$jj = 1;
// fetch data each row, store on tabular row data
while ($row = mysql_fetch_row($result)) {	
		
	if ($_REQUEST[g_addcategories]=='on') {
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$ii.'', $jj)
			->setCellValue('B'.$ii.'', $row[1])
			->setCellValue('C'.$ii.'', $row[2])
			->setCellValue('D'.$ii.'', $row[3])		
			->setCellValue('E'.$ii.'', $row[4])
			->setCellValue('F'.$ii.'', $row[6])
	
			->setCellValue('G'.$ii.'', $row[11])		// KONSULTACJE
			->setCellValue('H'.$ii.'', $row[12])
			->setCellValue('I'.$ii.'', $row[13])
			
			->setCellValue('J'.$ii.'', $row[14])		// AWARIE
			->setCellValue('K'.$ii.'', $row[15])
			->setCellValue('L'.$ii.'', $row[16])
			
			->setCellValue('M'.$ii.'', $row[17])		// AWARIE KRYTYCZNE
			->setCellValue('N'.$ii.'', $row[18])
			->setCellValue('O'.$ii.'', $row[19])
			
			->setCellValue('P'.$ii.'', $row[20])		// PRACE ZLECONE W RAMACH UMOWY
			->setCellValue('Q'.$ii.'', $row[21])
			->setCellValue('R'.$ii.'', $row[22])
			
			->setCellValue('S'.$ii.'', $row[23])		// PRACE ZLECONE POZA UMOW�
			->setCellValue('T'.$ii.'', $row[24])
			->setCellValue('U'.$ii.'', $row[25])
			
			->setCellValue('V'.$ii.'', $row[26])		// PRACE NA POTRZEBY POSTDATA
			->setCellValue('W'.$ii.'', $row[27])
			->setCellValue('X'.$ii.'', $row[28])

			->setCellValue('Y'.$ii.'', $row[29])		// KONSERWACJE
			->setCellValue('Z'.$ii.'', $row[30])
			->setCellValue('AA'.$ii.'', $row[31])
			
			->setCellValue('AB'.$ii.'', $row[33]);
	} else {
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$ii.'', $jj)
			->setCellValue('B'.$ii.'', $row[1])
			->setCellValue('C'.$ii.'', $row[2])
			->setCellValue('D'.$ii.'', $row[3])		
			->setCellValue('E'.$ii.'', $row[4])
			->setCellValue('F'.$ii.'', $row[6])
			->setCellValue('G'.$ii.'', $row[30]);
	}
	
/*	
	
	$objPHPExcel->getActiveSheet()->getStyle('A'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('B'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('C'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('D'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('E'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	//$objPHPExcel->getActiveSheet()->getStyle('F'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('F'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('G'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('H'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 

	
	$objPHPExcel->getActiveSheet()->getStyle('B'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
	$objPHPExcel->getActiveSheet()->getStyle('D'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('E'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle('F'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('G'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('H'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
*/
	$ii++;	
	$jj++;
	

}

$end_row = $start_row+$jj;

/*

if ($_REQUEST[g_addcategories]=='on') {
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
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);

} else {
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
}

*/

/*
//$objPHPExcel->getActiveSheet()->getStyle('F'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->getActiveSheet()->getStyle('F'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->getActiveSheet()->getStyle('G'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->getActiveSheet()->getStyle('H'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	
$objPHPExcel->getActiveSheet()->getStyle('F'.$ii.':H'.$ii.'')->applyFromArray($styleThickBlackBorderOutline); 

//$objPHPExcel->getActiveSheet()->getStyle('F'.$ii.'')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
//$objPHPExcel->getActiveSheet()->getStyle('F'.$ii.'')->getFill()->getStartColor()->setARGB('FFFFFF00');
$objPHPExcel->getActiveSheet()->getStyle('F'.$ii.'')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('F'.$ii.'')->getFill()->getStartColor()->setARGB('FFFFFF00');
$objPHPExcel->getActiveSheet()->getStyle('G'.$ii.'')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('G'.$ii.'')->getFill()->getStartColor()->setARGB('FFFFFF00');
$objPHPExcel->getActiveSheet()->getStyle('H'.$ii.'')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('H'.$ii.'')->getFill()->getStartColor()->setARGB('FFFFFF00');

$objPHPExcel->getActiveSheet()->getStyle('A'.$ii)->getFont()->setBold(true);
//$objPHPExcel->getActiveSheet()->getStyle('F'.$ii)->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->getStyle('F'.$ii)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('H'.$ii)->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->getStyle('A'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
//$objPHPExcel->getActiveSheet()->getStyle('F'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	

$objPHPExcel->getActiveSheet()->getStyle('F'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
$objPHPExcel->getActiveSheet()->getStyle('H'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
	
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
//$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
*/

$objPHPExcel->getActiveSheet()->setTitle('raport');
$objPHPExcel->createSheet();

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename='.$export_filename.'');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output'); 
?>