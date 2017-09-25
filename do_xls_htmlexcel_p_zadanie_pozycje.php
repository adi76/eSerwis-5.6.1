<?php 
// require_once 'cfg_eserwis.php';

require_once 'Classes/PHPExcel.php';
require_once 'Classes/PHPExcel/IOFactory.php';

include('cfg_db.php');

function ConnectToDatabase() {
	global $link, $dbtype, $dbhost, $dbusername, $dbpassword, $dbname;
	$link = mysql_connect( "$dbhost", "$dbusername" , "$dbpassword" ) or die("<h2 style='font-size:13px;font-weight:bold;padding-top:10px;padding-bottom:10px;margin-top:0px;margin-bottom:5px;text-align:center;background: #FF9999;color:#313131;display: block; border: 1px solid #FF6C6C;'>Wyst¹pi³ b³¹d podczas ³¹czenia siê z baz¹ danych</h2><div align=right><input type=button class=buttons value='Spróbuj ponownie' onclick=\"window.location.reload(true);\"></div>");
	mysql_select_db("$dbname", $link) or die($k_b);
	return $link;
}
$conn = ConnectToDatabase();

session_start(); 
if (!session_is_registered('es_autoryzacja')) { header("Location: ".$linkdostrony.""); }

//get contents
//define date for title
$now_date = date('d-m-Y H:i');
$zid = $_POST[id];
$all = $_POST[all];
$zname = $_REQUEST[zname];
$file_ending = "xls";

$export_filename = "zadanie_".$zid."_".$file_ending."";

$sql="SELECT * FROM $dbname.serwis_zadania_pozycje WHERE (belongs_to=$es_filia) and (pozycja_zadanie_id=$zid) ";
if ($all==0) $sql.=" and (pozycja_status=0)";
if ($all==9) $sql.=" and (pozycja_status=9)";
if ($all==99) $sql.=" and (pozycja_przypisane_osobie='$currentuser')";
$sql.=" ORDER BY pozycja_komorka";

//$sql = "SELECT wyjazd_data,wyjazd_trasa,wyjazd_km,wyjazd_zgl_szcz_id FROM $dbname_hed.hd_zgloszenie_wyjazd WHERE (wyjazd_osoba='$osoba') and (wyjazd_widoczny=1) and (wyjazd_data LIKE '%$okres%') ORDER BY wyjazd_data ASC"; 
$result = mysql_query($sql, $conn);
$count = mysql_num_fields($result);

$objPHPExcel = new PHPExcel();

$naglowej_start_row = 1;

$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$naglowej_start_row.'', 'Realizcja zadania: '.$zname);
$objPHPExcel->getActiveSheet()->mergeCells('A'.$naglowej_start_row.':F'.$naglowej_start_row.'');

if ($all!=0) {
	$naglowej_start_row++;
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$naglowej_start_row.'', 'Procent wykonania: '.$_POST[proc].'%');
	$objPHPExcel->getActiveSheet()->mergeCells('A'.$naglowej_start_row.':F'.$naglowej_start_row.'');
}

if ($all==1) {
	$naglowej_start_row++;
	$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$naglowej_start_row.'', 'Status pozycji zadania: wszystkie');
	$objPHPExcel->getActiveSheet()->mergeCells('A'.$naglowej_start_row.':F'.$naglowej_start_row.'');
}

if ($all==9) {
	$naglowej_start_row++;
	$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$naglowej_start_row.'', 'Status pozycji zadania: zakonczone');
	$objPHPExcel->getActiveSheet()->mergeCells('A'.$naglowej_start_row.':F'.$naglowej_start_row.'');
}

if ($all==0) {
	$naglowej_start_row++;
	$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$naglowej_start_row.'', 'Status pozycji zadania: niezakonczone');
	$objPHPExcel->getActiveSheet()->mergeCells('A'.$naglowej_start_row.':F'.$naglowej_start_row.'');
}

if ($all==99) {
	$naglowej_start_row++;
	$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$naglowej_start_row.'', 'Status pozycji zadania: wszystkie, przypisane do '.$currentuser.'');
	$objPHPExcel->getActiveSheet()->mergeCells('A'.$naglowej_start_row.':F'.$naglowej_start_row.'');
}

$start_row = 5;

$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$start_row.'', 'LP')
				->setCellValue('B'.$start_row.'', 'KomÃ³rka')
				->setCellValue('C'.$start_row.'', 'Przypisane osobie')
				->setCellValue('D'.$start_row.'', 'Zaplanowana data wykonania')
				->setCellValue('E'.$start_row.'', 'Status')
				->setCellValue('F'.$start_row.'', 'Data zakoÅ„czenia / ZakoÅ„czone przez');

$objPHPExcel->getActiveSheet()->getStyle('A'.$start_row.'')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B'.$start_row.'')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('C'.$start_row.'')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->getStyle('D'.$start_row.'')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('E'.$start_row.'')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('F'.$start_row.'')->getFont()->setBold(true);

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

$ii = $start_row;
$objPHPExcel->getActiveSheet()->getStyle('A'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->getActiveSheet()->getStyle('B'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->getActiveSheet()->getStyle('C'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->getActiveSheet()->getStyle('D'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->getActiveSheet()->getStyle('E'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->getActiveSheet()->getStyle('F'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	
$objPHPExcel->getActiveSheet()->getStyle('A'.$ii.':F'.$ii.'')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A'.$ii.':F'.$ii.'')->getFill()->getStartColor()->setARGB('FF808080');

$ii++;
$jj = 1;
// fetch data each row, store on tabular row data
while ($row = mysql_fetch_row($result)) {
	
	if ($row[5]==0) $status = 'nie wykonane';
	if ($row[5]==1) $status = 'w trakcie';
	if ($row[5]==9) $status = 'wykonane';
	
	if ($row[5]==9) {
		$dz = $row[3];
		$zp = $row[4];
		$separator = ' \ ';
	} else {
		$dz = '';
		$zp = '';
		$separator = '';
	}
	
	$ztw = $row[10];
	if ($row[10]=='0000-00-00') $ztw = '';
	
	$po = $row[7];
	if ($row[5]==9) $po = '';
	
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$ii.'', $jj)
		->setCellValue('B'.$ii.'', $row[2])
		->setCellValue('C'.$ii.'', $po)
		->setCellValue('D'.$ii.'', $ztw)
		->setCellValue('E'.$ii.'', $status)
		->setCellValue('F'.$ii.'', substr($dz,0,16).''.$separator.''.$zp);
		
		
	$objPHPExcel->getActiveSheet()->getStyle('A'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('B'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('C'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('D'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('E'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('F'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	
	$ii++;	
	$jj++;

}

	$end_row = $start_row+$jj;
	$objPHPExcel->getActiveSheet()->getStyle('A'.$start_row.':F'.($ii-1).'')->applyFromArray($styleThickBlackBorderOutline); 	
	$styleArray = array(
		'borders' => array(
			'allborders' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN
			)
		)
	);

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);

$objPHPExcel->getActiveSheet()->setTitle('Zadanie');
$objPHPExcel->createSheet();

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename='.$export_filename.'');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output'); 
?>