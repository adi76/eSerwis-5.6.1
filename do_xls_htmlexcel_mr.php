<?php 
// require_once 'cfg_eserwis.php';
require_once 'Classes/PHPExcel.php';
require_once 'Classes/PHPExcel/IOFactory.php';
include('cfg_db.php');

function ConnectToDatabase() {
	global $link, $dbtype, $dbhost, $dbusername, $dbpassword, $dbname;
	$link = mysql_connect( "$dbhost", "$dbusername" , "$dbpassword" ) or die("<h2 style='font-size:13px;font-weight:bold;padding-top:10px;padding-bottom:10px;margin-top:0px;margin-bottom:5px;text-align:center;background: #FF9999;color:#313131;display: block; border: 1px solid #FF6C6C;'>Wystąpił błąd podczas łączenia się z bazą danych</h2><div align=right><input type=button class=buttons value='Spróbuj ponownie' onclick=\"window.location.reload(true);\"></div>");
	mysql_select_db("$dbname", $link) or die($k_b);
	return $link;
}

function correct_currency($kwota_wejciowa=null) {
	if (($kwota_wejciowa) || ($kwota_wejciowa!="0.00")) {
		$cena9 = str_replace(',','.',$kwota_wejciowa);
		$kropka = strpos($cena9,'.');
		if (($kropka==0) && ($cena9[0]=='.')) { 
			$cena9='0'.$cena9;
			$kropka = strpos($cena9,'.');
		}
		if ($kropka>0) {
			$pelnakwota = intval(substr($cena9,0,$kropka));
			$pokropce = substr($cena9,$kropka+1,2);
			if (strlen($pokropce)==1) $pokropce.='0';
		} else {
				$pelnakwota=intval($cena9);
				if ($kropka!=0) $pokropce = intval(substr($cena9,$kropka+1,2)); else $pokropce.='00';
				}
		$kwotakoncowa = $pelnakwota.'.'.$pokropce;
		return $kwotakoncowa;
	} else return "0.00";
}

function round_up ($value, $places=0) {
  if ($places < 0) { $places = 0; }
  $mult = pow(10, $places);
  return ceil($value * $mult) / $mult;
}

function round_to_penny($amount){
// print( round_to_penny(79.99999) ); //result: 79.99  
    $string = (string)($amount * 10000);
    $string_array = split("\.", $string);
    $int = (int)$string_array[0];
    $return = $int / 10000;
    return $return;
}

function ceiling($number, $significance = 0.01) {
	return ( is_numeric($number) && is_numeric($significance) ) ? (ceil($number/$significance)*$significance) : false;
}

$conn = ConnectToDatabase();

session_start(); 
if (!session_is_registered('es_autoryzacja')) { header("Location: ".$linkdostrony.""); }

//get contents
//define date for title
$now_date = date('d-m-Y H:i');
$rok = $_POST[r_rok];
$miesiac = $_POST[r_miesiac];
$file_ending = "xls";

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
  case "10" : $miesiac_slownie="paxdziernik"; break;
  case "11" : $miesiac_slownie="listopad"; break;
  case "12" : $miesiac_slownie="grudzień"; break;
}

$export_filename = "miesieczny_protokol_odbioru_".$miesiac_slownie."'".$rok."(".$filianazwa.").".$file_ending."";

$sql = "SELECT pole2,pole3,pole4,pole5,pole6,pole7,pole8,pole9,pole10,pole11,pole13 FROM $dbname.serwis_temp_raport$es_filia ORDER BY pole13 ASC"; 
$result = mysql_query($sql, $conn) or die($k_b);

$count = mysql_num_fields($result);
$objPHPExcel = new PHPExcel();

$styleThinBlackBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => 'FF000000'),
		),
	),
);

$start_row = 19;

$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$start_row, 'LP')
				->setCellValue('B'.$start_row, 'Przedmiot')
				->setCellValue('C'.$start_row, 'Ilość')
				->setCellValue('D'.$start_row, 'j.m.')
				->setCellValue('E'.$start_row, 'Cena Netto')
				->setCellValue('F'.$start_row, 'Wartość bez VAT')
				->setCellValue('G'.$start_row, 'Stawka VAT')
				->setCellValue('H'.$start_row, 'Kwota VAT')
				->setCellValue('I'.$start_row, 'Wartość z VAT');				
				
$objPHPExcel->getActiveSheet()->getStyle('A'.$start_row)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B'.$start_row)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('C'.$start_row)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('D'.$start_row)->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->getStyle('E'.$start_row)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('F'.$start_row)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('G'.$start_row)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('H'.$start_row)->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('I'.$start_row)->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->getStyle('A'.$start_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('B'.$start_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('C'.$start_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('D'.$start_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

$objPHPExcel->getActiveSheet()->getStyle('E'.$start_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('F'.$start_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('G'.$start_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('H'.$start_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('i'.$start_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

$objPHPExcel->getActiveSheet()->getStyle('A'.$start_row.'')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->getActiveSheet()->getStyle('B'.$start_row.'')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->getActiveSheet()->getStyle('C'.$start_row.'')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->getActiveSheet()->getStyle('D'.$start_row.'')->applyFromArray($styleThinBlackBorderOutline); 

$objPHPExcel->getActiveSheet()->getStyle('E'.$start_row.'')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->getActiveSheet()->getStyle('F'.$start_row.'')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->getActiveSheet()->getStyle('G'.$start_row.'')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->getActiveSheet()->getStyle('H'.$start_row.'')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->getActiveSheet()->getStyle('I'.$start_row.'')->applyFromArray($styleThinBlackBorderOutline); 
	
$ii = $start_row + 1;
$jj = 1;
// fetch data each row, store on tabular row data
while($row = mysql_fetch_row($result)){

	$opis = $row[0].' - '.$row[1];
	
	include_once('cfg_vat.php');

	$cena_netto = correct_currency($row[5]);
	$cena_brutto = correct_currency(ceiling($cena_netto * $VAT_value));
	$kwota_vat = correct_currency(ceiling($cena_brutto-$cena_netto));
	$VAT_display = (($VAT_value*100) - 100) . "%";
	
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$ii.'', $jj)
		->setCellValue('B'.$ii.'', $opis)
		->setCellValue('C'.$ii.'', $row[2])
		->setCellValue('D'.$ii.'', $row[3])
		->setCellValue('E'.$ii.'', $row[5].' zł')
		->setCellValue('F'.$ii.'', $row[5].' zł')
		->setCellValue('G'.$ii.'', $VAT_display)
		->setCellValue('H'.$ii.'', $kwota_vat.' zł')
		->setCellValue('I'.$ii.'', $cena_brutto.' zł');
		
	$objPHPExcel->getActiveSheet()->getStyle('D'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
	$objPHPExcel->getActiveSheet()->getStyle('E'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
	$objPHPExcel->getActiveSheet()->getStyle('F'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);		
	$objPHPExcel->getActiveSheet()->getStyle('G'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
	$objPHPExcel->getActiveSheet()->getStyle('H'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
	$objPHPExcel->getActiveSheet()->getStyle('I'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	

	$objPHPExcel->getActiveSheet()->getStyle('A'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('B'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('C'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('D'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 

	$objPHPExcel->getActiveSheet()->getStyle('E'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('F'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('G'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('H'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('I'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	
	$ii++;	
	$jj++;
}

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
//$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);

// nagłówek dokumentu

$objPHPExcel->getActiveSheet()->getStyle('A1:I7')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->getActiveSheet()->getStyle('A8:I8')->applyFromArray($styleThinBlackBorderOutline); 

$objPHPExcel->getActiveSheet()->mergeCells('B2:G6');
$objPHPExcel->setActiveSheetIndex(0) ->setCellValue('B2', 'MIESIĘCZNY PROTOKÓŁ ODBIORU ');
$objPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setSize(26);

$objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);

$komorka_c8 = 'Data 01-'.Date('m').'-'.Date('Y');
$komorka_a8 = 'Rok i miesiąc '.$rok.'-'.$miesiac;
$objPHPExcel->getActiveSheet()->mergeCells('A8:G8');
$objPHPExcel->setActiveSheetIndex(0) ->setCellValue('A8', $komorka_a8);
$objPHPExcel->getActiveSheet()->getStyle('A8')->getFont()->setSize(13)->setItalic(true);
$objPHPExcel->getActiveSheet()->getStyle('A8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

$objPHPExcel->getActiveSheet()->mergeCells('H8:I8');
$objPHPExcel->setActiveSheetIndex(0) ->setCellValue('H8', $komorka_c8);
$objPHPExcel->getActiveSheet()->getStyle('H8')->getFont()->setSize(13)->setItalic(true);
$objPHPExcel->getActiveSheet()->getStyle('H8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('H8')->applyFromArray($styleThinBlackBorderOutline); 

$objPHPExcel->getActiveSheet()->mergeCells('H1:I3');
$objPHPExcel->setActiveSheetIndex(0) ->setCellValue('H1', 'NR ');
$objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setSize(20)->setItalic(true);
$objPHPExcel->getActiveSheet()->getStyle('H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('H1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);

$komorka_c4 = '('.$miesiac.'/'.$rok.')';
$objPHPExcel->getActiveSheet()->mergeCells('H5:I7');
$objPHPExcel->setActiveSheetIndex(0) ->setCellValue('H5', $komorka_c4);
$objPHPExcel->getActiveSheet()->getStyle('H5')->getFont()->setSize(20)->setItalic(true);
$objPHPExcel->getActiveSheet()->getStyle('H5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('H5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->mergeCells('B10:G10');
$objPHPExcel->setActiveSheetIndex(0) ->setCellValue('B10', 'DLA CIT DWI');
$objPHPExcel->getActiveSheet()->getStyle('B10')->getFont()->setSize(20);

$objPHPExcel->setActiveSheetIndex(0) ->setCellValue('B12', 'Dotyczy:');
$objPHPExcel->getActiveSheet()->getStyle('B12')->getFont()->setSize(18)->setItalic(true);

$objPHPExcel->getActiveSheet()->getStyle('H1:I7')->applyFromArray($styleThinBlackBorderOutline);

// grafika z logo Postdata
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('Postdata Logo');
$objDrawing->setDescription('Postdata Logo');
$objDrawing->setPath('img\logopostdata.gif');
$objDrawing->setHeight(120);
$objDrawing->setCoordinates('A1');
$objDrawing->setOffsetX(5);
$objDrawing->setOffsetY(2);
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

// checkbox - off
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('Checkbox off');
$objDrawing->setDescription('Checkbox off');
$objDrawing->setPath('img\checkbox.gif');
$objDrawing->setHeight(18);
$objDrawing->setWidth(18);
$objDrawing->setCoordinates('B13');
$objDrawing->setOffsetX(2);
$objDrawing->setOffsetY(2);
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

$objPHPExcel->getActiveSheet()->mergeCells('B13:G13');
$objPHPExcel->setActiveSheetIndex(0) ->setCellValue('B13', '    kompleksowej obsługi informatycznej - §2 ust 1.1');
$objPHPExcel->getActiveSheet()->getStyle('B13')->getFont()->setSize(14);

$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('Checkbox off');
$objDrawing->setDescription('Checkbox off');
$objDrawing->setPath('img\checkbox.gif');
$objDrawing->setHeight(18);
$objDrawing->setWidth(18);
$objDrawing->setCoordinates('B14');
$objDrawing->setOffsetX(2);
$objDrawing->setOffsetY(2);
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

$objPHPExcel->getActiveSheet()->mergeCells('B14:G14');
$objPHPExcel->setActiveSheetIndex(0) ->setCellValue('B14', '    okresowej konserwacji i stałego nadzoru technicznego - §2 ust 1.2');
$objPHPExcel->getActiveSheet()->getStyle('B14')->getFont()->setSize(14);

$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('Checkbox off');
$objDrawing->setDescription('Checkbox off');
$objDrawing->setPath('img\checkbox.gif');
$objDrawing->setHeight(18);
$objDrawing->setWidth(18);
$objDrawing->setCoordinates('B15');
$objDrawing->setOffsetX(2);
$objDrawing->setOffsetY(2);
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

$objPHPExcel->getActiveSheet()->mergeCells('B15:G15');
$objPHPExcel->setActiveSheetIndex(0) ->setCellValue('B15', '    usług na żądanie - §2 ust 1.3');
$objPHPExcel->getActiveSheet()->getStyle('B15')->getFont()->setSize(14);

// checkbox - on
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('Checkbox on');
$objDrawing->setDescription('Checkbox on');
$objDrawing->setPath('img\checkbox_on.gif');
$objDrawing->setHeight(18);
$objDrawing->setWidth(18);
$objDrawing->setCoordinates('B16');
$objDrawing->setOffsetX(2);
$objDrawing->setOffsetY(2);
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

$objPHPExcel->getActiveSheet()->mergeCells('B16:G16');
$objPHPExcel->setActiveSheetIndex(0) ->setCellValue('B16', '    usług naprawy sprzętu w serwisach specjalistycznych - §2 ust 1.5');
$objPHPExcel->getActiveSheet()->getStyle('B16')->getFont()->setSize(14);

$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setName('Checkbox on');
$objDrawing->setDescription('Checkbox on');
$objDrawing->setPath('img\checkbox_on.gif');
$objDrawing->setHeight(18);
$objDrawing->setWidth(18);
$objDrawing->setCoordinates('B17');
$objDrawing->setOffsetX(2);
$objDrawing->setOffsetY(2);
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

$objPHPExcel->getActiveSheet()->mergeCells('B17:G17');
$objPHPExcel->setActiveSheetIndex(0) ->setCellValue('B17', '    dostawy podzespołów - §2 ust 1.6');
$objPHPExcel->getActiveSheet()->getStyle('B17')->getFont()->setSize(14);

// szerokość kolumn
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(54);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(75);

$objPHPExcel->getActiveSheet()->getRowDimension('4')->setRowHeight(1);

// Set page orientation, size, Print Area and Fit To Pages

$rownr = $ii - 1;

$margin = 1;
$objPHPExcel->getActiveSheet()->getPageMargins()->setTop($margin);
$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom($margin);
$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft($margin);
$objPHPExcel->getActiveSheet()->getPageMargins()->setRight($margin);

$rownr = $ii+5;
$objPHPExcel->getActiveSheet()->mergeCells('A'.$rownr.':I'.$rownr.'');
$objPHPExcel->getActiveSheet()->getStyle('A'.$rownr.'')->getFont()->setSize(16);
$objPHPExcel->getActiveSheet()->getStyle('A'.$rownr.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->setActiveSheetIndex(0) ->setCellValue('A'.$rownr.'', '.....................................                                                                                  ......................................');

$rownr++;
$objPHPExcel->getActiveSheet()->mergeCells('A'.$rownr.':I'.$rownr.'');
$objPHPExcel->getActiveSheet()->getStyle('A'.$rownr.'')->getFont()->setSize(16);
$objPHPExcel->getActiveSheet()->getStyle('A'.$rownr.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->setActiveSheetIndex(0) ->setCellValue('A'.$rownr.'', '             Zleceniodawca                                                                                                   Zleceniobiorca           ');

$rownr += 3;
$rownr1 += $rownr + 3;

$objPHPExcel->getActiveSheet()->mergeCells('A'.$rownr.':I'.$rownr1.'');

$objPHPExcel->getActiveSheet()->getStyle('A'.$rownr.':I'.$rownr1.'')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->setActiveSheetIndex(0) ->setCellValue('A'.$rownr.'','MIESIĘCZNY PROTOKÓŁ ODBIORU DO UMOWY NR: CIT/197/2010');
$objPHPExcel->getActiveSheet()->getStyle('A'.$rownr.'')->getFont()->setSize(22);
$objPHPExcel->getActiveSheet()->getStyle('A'.$rownr.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A'.$rownr.'')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);


$objPageSetup = new PHPExcel_Worksheet_PageSetup();
$objPageSetup->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER);
$objPageSetup->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
$objPageSetup->setPrintArea("A1:D".$rownr1);
$objPageSetup->setFitToWidth(1);
$objPHPExcel->getActiveSheet()->setPageSetup($objPageSetup);


$objPHPExcel->getActiveSheet()->setTitle('Miesieczny_protokol_odbioru');
$objPHPExcel->createSheet();

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename='.$export_filename.'');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output'); 
?>