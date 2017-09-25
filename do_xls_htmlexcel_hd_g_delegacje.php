<?php 
// require_once 'cfg_eserwis.php';

require_once 'Classes/PHPExcel.php';
require_once 'Classes/PHPExcel/IOFactory.php';

include('cfg_db_helpdesk.php');

function ConnectToDatabase_HelpDesk() {
	global $link_hd, $dbtype_hd, $dbhost_hd, $dbusername_hd, $dbpassword_hd, $dbname_hd;
	$link_hd = mysql_connect( "$dbhost_hd", "$dbusername_hd" , "$dbpassword_hd" ) or die("<h2 style='font-size:13px;font-weight:bold;padding-top:10px;padding-bottom:10px;margin-top:0px;margin-bottom:5px;text-align:center;background: #FF9999;color:#313131;display: block; border: 1px solid #FF6C6C;'>Wystπpi≥ b≥πd podczas ≥πczenia siÍ z bazπ danych</h2><div align=right><input type=button class=buttons value='SprÛbuj ponownie' onclick=\"window.location.reload(true);\"></div>");
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
    return $money.' z≈Ç';
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
    return $money.' z≈Ç';
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

$export_filename = "wyjazdy_".$okres."_".$osoba1.".".$file_ending."";


	$result1 = mysql_query("SELECT hd_pojazd_kategoria FROM $dbname_hd.hd_pojazdy WHERE (hd_pojazd_id='$pojazd')", $conn_hd) or die($k_b);
	list($poj_kat)=mysql_fetch_array($result1);
	
	$dddd = Date('Y-m-d');
	switch ($poj_kat) {
		case "1" : $result1 = mysql_query("SELECT hd_stawka_motorower FROM helpdesk.hd_stawki_za_km WHERE hd_stawka_od<'".$dddd."' and hd_stawka_active=1", $conn_hd) or die($k_b); break;
		case "2" : $result1 = mysql_query("SELECT hd_stawka_motocykl FROM helpdesk.hd_stawki_za_km WHERE hd_stawka_od<'".$dddd."' and hd_stawka_active=1", $conn_hd) or die($k_b); break;
		case "3" : $result1 = mysql_query("SELECT hd_stawka_samochod_do_900 FROM helpdesk.hd_stawki_za_km WHERE hd_stawka_od<'".$dddd."' and hd_stawka_active=1", $conn_hd) or die($k_b); break;
		case "4" : $result1 = mysql_query("SELECT hd_stawka_samochod_od_900 FROM helpdesk.hd_stawki_za_km WHERE hd_stawka_od<'".$dddd."' and hd_stawka_active=1", $conn_hd) or die($k_b); break;
	}
	list($stawka)=mysql_fetch_array($result1);	
	
//	$sql = "SELECT wyjazd_data,wyjazd_trasa,wyjazd_km,wyjazd_zgl_szcz_id, wyjazd_rodzaj_pojazdu FROM $dbname_hed.hd_zgloszenie_wyjazd WHERE (wyjazd_osoba='$osoba') and (wyjazd_widoczny=1) and (wyjazd_data LIKE '%$okres%') ";
//	if ($_REQUEST[pokaz_zerowe]=='on') $sql .= " and (wyjazd_km<>'0') ";
//	$sql .= " ORDER BY wyjazd_data ASC"; 

	$sql = "SELECT wyjazd_zgl_szcz_id,wyjazd_trasa,wyjazd_km,wyjazd_data,wyjazd_rodzaj_pojazdu FROM $dbname_hd.hd_zgloszenie_wyjazd WHERE (wyjazd_osoba='$osoba') and (wyjazd_widoczny=1) and (wyjazd_data LIKE '%$okres%') ";
	
	if (($_REQUEST[pokaz_zerowe]=='on') && ($_REQUEST[pomijaj_wyjazdy_sluzbowe]!='on')) {
		$sql .= " and (((wyjazd_rodzaj_pojazdu='P') and (wyjazd_km<>'0')) or (wyjazd_rodzaj_pojazdu='S'))";
	}

	if (($_REQUEST[pokaz_zerowe]=='on') && ($_REQUEST[pomijaj_wyjazdy_sluzbowe]=='on')) {	
		$sql .= " and (wyjazd_rodzaj_pojazdu='P') and (wyjazd_km<>'0')";
	}

	if (($_REQUEST[pokaz_zerowe]!='on') && ($_REQUEST[pomijaj_wyjazdy_sluzbowe]=='on')) {	
		$sql .= " and (wyjazd_rodzaj_pojazdu='P')";
	}
	
	$sql .= " ORDER BY wyjazd_data ASC";
	

$result = mysql_query($sql, $conn_hd) or die($k_b);
$count = mysql_num_fields($result);

$objPHPExcel = new PHPExcel();

$naglowej_start_row = 1;

$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$naglowej_start_row.'', 'Ewidencja przebiegu pojazdu dla informatyka: '.$osoba);
$objPHPExcel->getActiveSheet()->mergeCells('A'.$naglowej_start_row.':C'.$naglowej_start_row.'');

$naglowej_start_row++;
$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$naglowej_start_row.'', 'Adres zamieszkania pracownika: '.$_POST[g_ulica].', '.$_POST[g_kod].' '.$_POST[g_miejscowosc].'');
$objPHPExcel->getActiveSheet()->mergeCells('A'.$naglowej_start_row.':C'.$naglowej_start_row.'');

if ($_POST[g_marka]=='') {
	$naglowej_start_row++;
	$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$naglowej_start_row.'', 'Marka pojazdu: '.$_POST[g_marka]);
	$objPHPExcel->getActiveSheet()->mergeCells('A'.$naglowej_start_row.':C'.$naglowej_start_row.'');
}

$naglowej_start_row++;
$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$naglowej_start_row.'', 'Numer rejestracyjny pojazdu: '.$_POST[g_nrrej]);
$objPHPExcel->getActiveSheet()->mergeCells('A'.$naglowej_start_row.':C'.$naglowej_start_row.'');

$naglowej_start_row++;
$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$naglowej_start_row.'', 'Pojemno≈õƒá silnika pojazdu: '.$_POST[g_poj]);
$objPHPExcel->getActiveSheet()->mergeCells('A'.$naglowej_start_row.':C'.$naglowej_start_row.'');

$naglowej_start_row++;
$objPHPExcel->getActiveSheet()->mergeCells('A'.$naglowej_start_row.':C'.$naglowej_start_row.'');
$naglowej_start_row++;

$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$naglowej_start_row.'', 'Koszt ca≈Çkowity przejazd√≥w z danego okresu: '.formatMoney2($_POST[g_kwota_razem]).'');
$objPHPExcel->getActiveSheet()->mergeCells('A'.$naglowej_start_row.':C'.$naglowej_start_row.'');

$naglowej_start_row++;
$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$naglowej_start_row.'', 'Ilo≈õƒá przejechanych kilometr√≥w w danym okresie: '.$_POST[g_km_razem].' km');
$objPHPExcel->getActiveSheet()->mergeCells('A'.$naglowej_start_row.':C'.$naglowej_start_row.'');
$start_row = 9;

$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$start_row.'', 'LP')
				->setCellValue('B'.$start_row.'', 'Data')
				->setCellValue('C'.$start_row.'', 'Trasa')
				
				->setCellValue('D'.$start_row.'', 'Nr zg≈Çoszenia')
				->setCellValue('E'.$start_row.'', 'Cel wyjazdu')
			//	->setCellValue('F'.$start_row.'', 'Tre≈õƒá')
				
				->setCellValue('F'.$start_row.'', 'Ilo≈õƒá km')
				->setCellValue('G'.$start_row.'', 'Stawka za km')
				->setCellValue('H'.$start_row.'', 'Kwota');

$objPHPExcel->getActiveSheet()->getStyle('A'.$start_row.'')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B'.$start_row.'')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('C'.$start_row.'')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->getStyle('D'.$start_row.'')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('E'.$start_row.'')->getFont()->setBold(true);
//$objPHPExcel->getActiveSheet()->getStyle('F'.$start_row.'')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->getStyle('F'.$start_row.'')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('G'.$start_row.'')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('H'.$start_row.'')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->getStyle('A'.$start_row.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('B'.$start_row.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('D'.$start_row.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('E'.$start_row.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('F'.$start_row.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('G'.$start_row.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('H'.$start_row.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

$km_razem = 0;
$zl_razem = 0;
	

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
//$objPHPExcel->getActiveSheet()->getStyle('F'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->getActiveSheet()->getStyle('F'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->getActiveSheet()->getStyle('G'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->getActiveSheet()->getStyle('H'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	
$objPHPExcel->getActiveSheet()->getStyle('A'.$ii.':H'.$ii.'')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A'.$ii.':H'.$ii.'')->getFill()->getStartColor()->setARGB('FF808080');

$ii++;
$jj = 1;
// fetch data each row, store on tabular row data
while ($row = mysql_fetch_row($result)) {

	// wyjazd_zgl_szcz_id,wyjazd_trasa,wyjazd_km,wyjazd_data,wyjazd_rodzaj_pojazdu
	
	//$string1=str_replace(',','.',$row[4]); $float1=floatval($string1);
	//$string2=str_replace(',','.',$row[5]); $float2=floatval($string2);

		$r3 = mysql_query("SELECT zgl_szcz_zgl_id FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_unikalny_numer='$row[0]')", $conn_hd) or die($k_b);
		list($zgloszenie_id)=mysql_fetch_array($r3);
		
		//echo "SELECT zgl_nr, zgl_temat, zgl_tresc FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id='$zgloszenie_id')";
		$r3 = mysql_query("SELECT zgl_nr, zgl_temat, zgl_tresc FROM $dbname_hd.hd_zgloszenie WHERE (zgl_id='$zgloszenie_id')", $conn_hd) or die($k_b);
		list($zgloszenie_numer,$zgloszenie_temat,$zgloszenie_tresc)=mysql_fetch_array($r3);
		
	$kwota = $stawka*$row[2];
	
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$ii.'', $jj)
		->setCellValue('B'.$ii.'', $row[3])
		->setCellValue('C'.$ii.'', $row[1])

		->setCellValue('D'.$ii.'', $zgloszenie_numer)
		->setCellValue('E'.$ii.'', $zgloszenie_temat)
	//	->setCellValue('F'.$ii.'', wordwrap($zgloszenie_tresc, 100, "\n"))		
		
		->setCellValue('F'.$ii.'', $row[2].' km')
		->setCellValue('G'.$ii.'', $stawka.' z≈Ç')
		->setCellValue('H'.$ii.'', formatMoney4($kwota));
	
	
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

	$ii++;	
	$jj++;
	
	$kwota_razem+=($kwota);
	$km_razem+=$row[2];

}

	$end_row = $start_row+$jj;

	$objPHPExcel->getActiveSheet()->getStyle('A'.$start_row.':H'.($ii-1).'')->applyFromArray($styleThickBlackBorderOutline); 
	
	$styleArray = array(
		'borders' => array(
			'allborders' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN
			)
		)
	);


//	$objPHPExcel->getActiveSheet()->getStyle()->getAlignment('A'.$start_row.':I'.$end_row.'')->applyFromArray($styleArray);
//	$objPHPExcel->getActiveSheet()->Range('A8:I12')->BORDERS('7')->Weight=2;

	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A'.$ii.'', '')
		->setCellValue('B'.$ii.'', '')
		->setCellValue('F'.$ii.'', 'Razem:')
		
		->setCellValue('D'.$ii.'', '')

		->setCellValue('F'.$ii.'', $km_razem.' km')
		->setCellValue('G'.$ii.'', '')
		->setCellValue('H'.$ii.'', formatMoney2($kwota_razem));

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

$objPHPExcel->getActiveSheet()->setTitle('Wyjazdy');
$objPHPExcel->createSheet();

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename='.$export_filename.'');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output'); 
?>