<?php 
require_once 'Classes/PHPExcel.php';
require_once 'Classes/PHPExcel/IOFactory.php';
include('cfg_db.php');

function ConnectToDatabase() {
	global $link, $dbtype, $dbhost, $dbusername, $dbpassword, $dbname;
	$link = mysql_connect( "$dbhost", "$dbusername" , "$dbpassword" ) or die("<h2 style='font-size:13px;font-weight:bold;padding-top:10px;padding-bottom:10px;margin-top:0px;margin-bottom:5px;text-align:center;background: #FF9999;color:#313131;display: block; border: 1px solid #FF6C6C;'>Wystąpił błąd podczas łączenia się z bazą danych</h2><div align=right><input type=button class=buttons value='Spróbuj ponownie' onclick=\"window.location.reload(true);\"></div>");
	mysql_select_db("$dbname", $link) or die($k_b);
	return $link;

}

$wskaznik_marzy	= 1.08; // 8%

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

function round_up($value, $places=0) {
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
  case "10" : $miesiac_slownie="październik"; break;
  case "11" : $miesiac_slownie="listopad"; break;
  case "12" : $miesiac_slownie="grudzień"; break;
}

$export_filename = "obciazenie_".$miesiac_slownie."'".$rok."(".$filianazwa.").".$file_ending."";

$sql = "SELECT pole2,pole3,pole4,pole5,pole6,pole7,pole8,pole9,pole10,pole11,pole13 FROM $dbname.serwis_temp_raport$es_filia ORDER BY pole13 ASC"; 
$result = mysql_query($sql, $conn) or die($k_b);

$count = mysql_num_fields($result);
$objPHPExcel = new PHPExcel();

$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A1', 'LP')
				->setCellValue('B1', 'Nazwa towaru lub usługi')
				->setCellValue('C1', 'Miejsce instalacji')
				->setCellValue('D1', 'Ilość')
				->setCellValue('E1', 'Jednostka masy')
				->setCellValue('F1', 'Cena netto')
				->setCellValue('G1', 'Cena jedn. netto z marżą')
				->setCellValue('H1', 'Sugerowana cena odsprzedaży')
				
				->setCellValue('I1', 'Numer faktury')
				->setCellValue('J1', 'Data wystawienia')
				->setCellValue('K1', 'Dostawca')
				->setCellValue('L1', 'Rodzaj sprzedaży')
				->setCellValue('M1', 'Numer zlecenia');
				
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('I1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('J1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('K1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('L1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('M1')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('K1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('L1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('M1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

$ii = 2;
// fetch data each row, store on tabular row data
while($row = mysql_fetch_row($result)){

	$string1=str_replace(',','.',$row[4]); $float1=floatval($string1);
	$string2=str_replace(',','.',$row[5]); $float2=floatval($string2);
	
	$cena_z_marza = (float) $float1 * $wskaznik_marzy;
	$float3 = round_up($cena_z_marza, 2);
	$float3=floatval($float3);
	
	$float3 = $float2;
	//$float3 = str_replace('.',',',correct_currency($float3));

	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$ii.'', $ii-1)
		->setCellValue('B'.$ii.'', $row[0])
		->setCellValue('C'.$ii.'', $row[1])
		->setCellValue('D'.$ii.'', $row[2])
		->setCellValue('E'.$ii.'', $row[3])
		->setCellValue('F'.$ii.'', $float1)
		->setCellValue('G'.$ii.'', $float2)
		->setCellValue('H'.$ii.'', $float3)		
		->setCellValue('I'.$ii.'', $row[6])
		->setCellValue('J'.$ii.'', $row[7])
		->setCellValue('K'.$ii.'', $row[8])
		->setCellValue('L'.$ii.'', $row[9])
		->setCellValue('m'.$ii.'', $row[10]);
		
	$objPHPExcel->getActiveSheet()->getStyle('E'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('F'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('G'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('H'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('I'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('K'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('L'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('M'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	
	$objPHPExcel->getActiveSheet()->getStyle('F'.$ii)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_ZL_SIMPLE);
	$objPHPExcel->getActiveSheet()->getStyle('G'.$ii)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_ZL_SIMPLE);
	$objPHPExcel->getActiveSheet()->getStyle('H'.$ii)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_ZL_SIMPLE);

	$ii++;	
}

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);

//$objPHPExcel->getActiveSheet()->getStyle('F2:G10')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE);

$objPHPExcel->getActiveSheet()->setTitle('Raport');
$objPHPExcel->createSheet();

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename='.$export_filename.'');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output'); 
?>