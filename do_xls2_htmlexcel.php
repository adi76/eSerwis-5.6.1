<?php 
//require_once 'cfg_eserwis.php';

include('cfg_db.php');

function ConnectToDatabase() {
	global $link, $dbtype, $dbhost, $dbusername, $dbpassword, $dbname;
	$link = mysql_connect( "$dbhost", "$dbusername" , "$dbpassword" ) or die("<h2 style='font-size:13px;font-weight:bold;padding-top:10px;padding-bottom:10px;margin-top:0px;margin-bottom:5px;text-align:center;background: #FF9999;color:#313131;display: block; border: 1px solid #FF6C6C;'>WystÄ…piĹ‚ bĹ‚Ä…d podczas Ĺ‚Ä…czenia siÄ™ z bazÄ… danych</h2><div align=right><input type=button class=buttons value='SprĂłbuj ponownie' onclick=\"window.location.reload(true);\"></div>");
	mysql_select_db("$dbname", $link) or die($k_b);
	return $link;
}

$conn = ConnectToDatabase();

require_once 'Classes/PHPExcel.php';
require_once 'Classes/PHPExcel/IOFactory.php';

//require_once "Classes/Writer.php";
session_start(); 
if (!session_is_registered('es_autoryzacja')) { header("Location: ".$linkdostrony.""); }

$now_date = date('d-m-Y H:i');
$rok = date('Y');
$miesiac = date('m');

$filianazwa=$_POST['filian'];
$nazwapliku=$_POST['obc1']."_";

if ($_POST[addsell]!='on') { $nazwapliku=''; }

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

$nazwa_pliku = "Zestawienie_zakupow_sprzedazy_".$nazwapliku."Filia_$filianazwa.xls";

$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A1', 'Data zakupu')
				->setCellValue('B1', 'Numer faktury')
				->setCellValue('C1', 'Dostawca')
				->setCellValue('D1', 'Nazwa towaru lub usługi')
				->setCellValue('E1', 'Ilość')
				->setCellValue('F1', 'Cena zakupu')
				->setCellValue('G1', 'Cena odsprzedaży')
				->setCellValue('H1',' Zlecenie fakturowania')
				->setCellValue('I1', 'Numer zlecenia')
				->setCellValue('J1', 'Filia')
				->setCellValue('K1', 'Realizacja zakupu')
				->setCellValue('L1', 'Rodzaj sprzedaży');

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

$objPHPExcel->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('K1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('L1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

$ii = 2;

$sql = "SELECT pole2,pole3,pole4,pole5,pole6,pole7,pole8,pole9,pole10,pole11,pole12,pole13 FROM $dbname.serwis_temp_m1$es_filia";
$result = mysql_query($sql, $conn) or die($k_b);

while($row = mysql_fetch_row($result)){
	
	$string1=str_replace(',','.',$row[5]); 
	$float1=floatval($string1);
	
	//$string2=str_replace(',',',',$row[6]); $float2=floatval($string2);
	
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$ii.'', $row[0])
		->setCellValue('B'.$ii.'', $row[1])
		->setCellValue('C'.$ii.'', $row[2])
		->setCellValue('D'.$ii.'', $row[3])
		->setCellValue('E'.$ii.'', $row[4])
		->setCellValue('F'.$ii.'', $float1)
		->setCellValue('G'.$ii.'', $row[6])
		->setCellValue('H'.$ii.'', $row[7])
		->setCellValue('I'.$ii.'', $row[8])
		->setCellValue('J'.$ii.'', $row[9])
		->setCellValue('K'.$ii.'', $row[10])
		->setCellValue('L'.$ii.'', $row[11]);

	$objPHPExcel->getActiveSheet()->getStyle('E'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('F'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('G'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('G'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('H'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('I'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('J'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('K'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
	$objPHPExcel->getActiveSheet()->getStyle('L'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	
	$objPHPExcel->getActiveSheet()->getStyle('F'.$ii)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_ZL_SIMPLE);
	$objPHPExcel->getActiveSheet()->getStyle('G'.$ii)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_ZL_SIMPLE);
	
	$ii++;	

}

if (($_POST[addsell]=='on') && ($_POST[wykrap]>0)) {
	$sql1 = "SELECT pole2,pole3,pole4,pole5,pole6,pole7,pole8,pole9,pole10,pole11,pole12,pole13 FROM $dbname.serwis_temp_m2$es_filia";  
	$result1 = mysql_query($sql1, $conn) or die($k_b);
	//$kol = mysql_num_fields($result1);
	
	$jj = $ii;
	
	while($row = mysql_fetch_row($result1)){

		$string1=str_replace(',','.',$row[5]); $float1=floatval($string1);
		$string2=str_replace(',','.',$row[6]); $float2=floatval($string2);
	
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$jj.'', $row[0])
			->setCellValue('B'.$jj.'', $row[1])
			->setCellValue('C'.$jj.'', $row[2])
			->setCellValue('D'.$jj.'', $row[3])
			->setCellValue('E'.$jj.'', $row[4])
			->setCellValue('F'.$jj.'', $float1)
			->setCellValue('G'.$jj.'', $float2)
			->setCellValue('H'.$jj.'', $row[7])
			->setCellValue('I'.$jj.'', $row[8])
			->setCellValue('J'.$jj.'', $row[9])
			->setCellValue('K'.$jj.'', $row[10])
			->setCellValue('L'.$jj.'', $row[11]);

		$objPHPExcel->getActiveSheet()->getStyle('E'.$jj)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getStyle('F'.$jj)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getStyle('G'.$jj)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getStyle('G'.$jj)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getStyle('H'.$jj)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getStyle('I'.$jj)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getStyle('J'.$jj)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getStyle('K'.$jj)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
		$objPHPExcel->getActiveSheet()->getStyle('L'.$jj)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	
		$objPHPExcel->getActiveSheet()->getStyle('F'.$jj)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_ZL_SIMPLE);
		$objPHPExcel->getActiveSheet()->getStyle('G'.$jj)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_ZL_SIMPLE);

		$jj++;	
	}
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

//$objPHPExcel->getActiveSheet()->getStyle('F2:G10')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_EUR_SIMPLE);

$objPHPExcel->getActiveSheet()->setTitle('Magazyn');
$objPHPExcel->createSheet();

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename='.$nazwa_pliku.'');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

exit;
?>