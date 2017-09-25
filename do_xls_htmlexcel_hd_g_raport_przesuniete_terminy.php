<?php 
// require_once 'cfg_eserwis.php';

$pokaz_info_o_osobie_i_statusie = 0;

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
$okres = $_POST[okres];
$file_ending = "xls";

if ($_REQUEST[typ]=='day') $export_filename = "przesuniete_terminy_rozpoczecia_z_dnia_".$okres.".".$file_ending."";
if ($_REQUEST[typ]=='all') $export_filename = "przesuniete_terminy_rozpoczecia.".$file_ending."";

$zap="SELECT zgl_nr,zgl_data,zgl_godzina,zgl_komorka,zgl_temat, zgl_status, zgl_osoba_przypisana,zgl_szcz_przesuniecie_data,zgl_szcz_przesuniecie_osoba FROM $dbname_hd.hd_zgloszenie, $dbname_hd.hd_zgloszenie_szcz WHERE (hd_zgloszenie.belongs_to=$es_filia) and (hd_zgloszenie.zgl_widoczne=1) AND (hd_zgloszenie.zgl_id=hd_zgloszenie_szcz.zgl_szcz_zgl_id) and (hd_zgloszenie_szcz.zgl_szcz_przesuniety_termin_rozpoczecia=1) and ((hd_zgloszenie.zgl_status=1) or (hd_zgloszenie.zgl_status=2)) ";

if ($_REQUEST[typ]=='day') $zap .= " and (SUBSTRING(hd_zgloszenie_szcz.zgl_szcz_czas_rozpoczecia_kroku,1,10)='".$_REQUEST[okres]."') ";
if ($_REQUEST[typ]=='all') $sql .= " and (hd_zgloszenie.zgl_status<>9) ";
	
$zap .= "ORDER BY zgl_data ASC";

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

	$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$start_row.'', 'Numer zgÅ‚oszenia')
					->setCellValue('B'.$start_row.'', 'Data rejestracji')
					->setCellValue('C'.$start_row.'', 'PlacÃ³wka zgÅ‚aszajÄ…ca')
					->setCellValue('D'.$start_row.'', 'Temat')
					->setCellValue('E'.$start_row.'', 'Ustalona data rozpoczÄ™cia');
					
	if ($pokaz_info_o_osobie_i_statusie==1) {				
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$start_row.'', 'Osoba przypisana')
											->setCellValue('G'.$start_row.'', 'Status');
	}

	$objPHPExcel->getActiveSheet()->getStyle('A'.$start_row.'')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('B'.$start_row.'')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('C'.$start_row.'')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('D'.$start_row.'')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('E'.$start_row.'')->getFont()->setBold(true);
	if ($pokaz_info_o_osobie_i_statusie==1) $objPHPExcel->getActiveSheet()->getStyle('F'.$start_row.'')->getFont()->setBold(true);
	if ($pokaz_info_o_osobie_i_statusie==1) $objPHPExcel->getActiveSheet()->getStyle('G'.$start_row.'')->getFont()->setBold(true);

	$objPHPExcel->getActiveSheet()->getStyle('A'.$start_row.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('B'.$start_row.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('C'.$start_row.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('D'.$start_row.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('E'.$start_row.'')->applyFromArray($styleThinBlackBorderOutline); 
	if ($pokaz_info_o_osobie_i_statusie==1) $objPHPExcel->getActiveSheet()->getStyle('F'.$start_row.'')->applyFromArray($styleThinBlackBorderOutline); 
	if ($pokaz_info_o_osobie_i_statusie==1) $objPHPExcel->getActiveSheet()->getStyle('G'.$start_row.'')->applyFromArray($styleThinBlackBorderOutline); 

	$objPHPExcel->getActiveSheet()->getStyle('A'.$start_row.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('B'.$start_row.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('C'.$start_row.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	if ($pokaz_info_o_osobie_i_statusie==1) $objPHPExcel->getActiveSheet()->getStyle('F'.$start_row.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	if ($pokaz_info_o_osobie_i_statusie==1) $objPHPExcel->getActiveSheet()->getStyle('G'.$start_row.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	

	$ii = $start_row+1;
	// fetch data each row, store on tabular row data
	while ($row = mysql_fetch_row($result)) {

		list($status)=mysql_fetch_array(mysql_query("SELECT hd_status_opis FROM $dbname_hd.hd_status WHERE hd_status_nr='$row[5]' LIMIT 1", $conn_hd));

	//	$numery = $row[0];
	//	if ($row[7]!='') $numery .= " / ".$row[7];
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$ii.'', $row[0])
			->setCellValue('B'.$ii.'', $row[1]." ".$row[2])
			->setCellValue('C'.$ii.'', $row[3])
			->setCellValue('D'.$ii.'', $row[4])
			->setCellValue('E'.$ii.'', $row[7]." / ".$row[8]);
			
		if ($pokaz_info_o_osobie_i_statusie==1) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ii.'', $row[6])
												->setCellValue('G'.$ii.'', $status);
		}
		
		$objPHPExcel->getActiveSheet()->getStyle('A'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getStyle('B'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()->getStyle('C'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);	
		$objPHPExcel->getActiveSheet()->getStyle('D'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$objPHPExcel->getActiveSheet()->getStyle('E'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		if ($pokaz_info_o_osobie_i_statusie==1)$objPHPExcel->getActiveSheet()->getStyle('F'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		if ($pokaz_info_o_osobie_i_statusie==1)$objPHPExcel->getActiveSheet()->getStyle('G'.$ii)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$ii++;	

		$objPHPExcel->getActiveSheet()->getStyle('A'.($ii-1).'')->applyFromArray($styleThinBlackBorderOutline); 
		$objPHPExcel->getActiveSheet()->getStyle('B'.($ii-1).'')->applyFromArray($styleThinBlackBorderOutline); 

		$objPHPExcel->getActiveSheet()->getStyle('C'.($ii-1).'')->applyFromArray($styleThinBlackBorderOutline); 
		$objPHPExcel->getActiveSheet()->getStyle('D'.($ii-1).'')->applyFromArray($styleThinBlackBorderOutline); 
		$objPHPExcel->getActiveSheet()->getStyle('E'.($ii-1).'')->applyFromArray($styleThinBlackBorderOutline); 
		if ($pokaz_info_o_osobie_i_statusie==1)$objPHPExcel->getActiveSheet()->getStyle('F'.($ii-1).'')->applyFromArray($styleThinBlackBorderOutline); 
		if ($pokaz_info_o_osobie_i_statusie==1)$objPHPExcel->getActiveSheet()->getStyle('G'.($ii-1).'')->applyFromArray($styleThinBlackBorderOutline); 
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		if ($pokaz_info_o_osobie_i_statusie==1)$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		if ($pokaz_info_o_osobie_i_statusie==1)$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);	
	}

	$ii++;
	$jj=$ii+1;

	$objPHPExcel->getActiveSheet()->setTitle('przesuniete terminy');
	$objPHPExcel->createSheet();

	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename='.$export_filename.'');
	header('Cache-Control: max-age=0');

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output'); 

?>