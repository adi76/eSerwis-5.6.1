<?php 
// require_once 'cfg_eserwis.php';

require_once 'Classes/PHPExcel.php';
require_once 'Classes/PHPExcel/IOFactory.php';

include('cfg_db_helpdesk.php');

function AddMinutesToDate($noofminutes, $startdate) {
	return date("Y-m-d H:i:s",strtotime($startdate)+$noofminutes*60);
}

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

$export_filename = "raport_szczegolowy_ze_zgloszen_".$obszar."_za_okres_".$_POST[data_od]."-".$_POST[data_do].".".$file_ending."";

$objPHPExcel = new PHPExcel();
$naglowej_start_row = 1;

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

$zgloszenie = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => 'FF7F9ECF'),
		),
	),
);

$kroki = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => 'FFCCD8EC'),
		),
	),
);

$naglowej_start_row = 1;
$start_row = 1;
$ii = $start_row;

if ($_POST[pole_1]=='on') $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$start_row.'', 'LP');
if ($_POST[pole_2]=='on') $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$start_row.'', 'Nr zgł.');
if ($_POST[pole_3]=='on') $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$start_row.'', 'Nr zgł. poczty');
if ($_POST[pole_4]=='on') $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$start_row.'', 'Data zgłoszenia');
if ($_POST[pole_5]=='on') $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$start_row.'', 'Godzina zgłoszenia');
if ($_POST[pole_6]=='on') $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$start_row.'', 'Komórka zgłaszająca');
if ($_POST[pole_7]=='on') $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$start_row.'', 'Osoba zgłaszająca');
if ($_POST[pole_8]=='on') $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$start_row.'', 'Telefon');
if ($_POST[pole_9]=='on') $objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$start_row.'', 'Temat');
if ($_POST[pole_10]=='on') $objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$start_row.'', 'Treść');
if ($_POST[pole_11]=='on') $objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$start_row.'', 'Kategoria');
if ($_POST[pole_12]=='on') $objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$start_row.'', 'Podkategoria');
if ($_POST[pole_13]=='on') $objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$start_row.'', 'Priorytet');
if ($_POST[pole_14]=='on') $objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$start_row.'', 'Status');
if ($_POST[pole_15]=='on') $objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$start_row.'', 'Osoba przypisana');
if ($_POST[pole_16]=='on') $objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$start_row.'', 'Um. data rozp.');
if ($_POST[pole_17]=='on') $objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$start_row.'', 'Um. data zak.');
if ($_POST[pole_18]=='on') $objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$start_row.'', 'Osoba rejestrująca');
if ($_POST[pole_19]=='on') $objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$start_row.'', 'Zgłoszenie zasadne');
if ($_POST[pole_20]=='on') $objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$start_row.'', 'Osoba potw. zamk. zgł.');
//if ($_POST[pole_21]=='on') $objPHPExcel->setActiveSheetIndex(0)->setCellValue('U'.$start_row.'', 'Data zamknięcia zgł.');
if ($_POST[pole_22]=='on') $objPHPExcel->setActiveSheetIndex(0)->setCellValue('V'.$start_row.'', 'Filia');

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
$objPHPExcel->getActiveSheet()->getStyle('L'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->getActiveSheet()->getStyle('M'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->getActiveSheet()->getStyle('N'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->getActiveSheet()->getStyle('O'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->getActiveSheet()->getStyle('P'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->getActiveSheet()->getStyle('Q'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->getActiveSheet()->getStyle('R'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->getActiveSheet()->getStyle('S'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->getActiveSheet()->getStyle('T'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->getActiveSheet()->getStyle('U'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
$objPHPExcel->getActiveSheet()->getStyle('V'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
*/
$objPHPExcel->getActiveSheet()->getStyle('A'.$ii.':V'.$ii.'')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A'.$ii.':V'.$ii.'')->getFill()->getStartColor()->setARGB('FF395D97');	

$ii++;
$jj = 1;

$sql4 = "SELECT  zgl_id,zgl_nr,zgl_poczta_nr,zgl_data,zgl_godzina,zgl_komorka,zgl_osoba,zgl_telefon,zgl_temat,zgl_tresc,zgl_osoba_przypisana,zgl_data_rozpoczecia,zgl_data_zakonczenia,zgl_osoba_rejestrujaca,zgl_zasadne,zgl_osoba_potwierdzajaca_zamkniecie_zgloszenia,hd_kategoria_opis,hd_podkategoria_opis,hd_priorytet_opis,hd_status_opis,location_name,zgl_status FROM $dbname_hd.hd_zgloszenie, $dbname_hd.hd_kategoria, $dbname_hd.hd_podkategoria, $dbname_hd.hd_priorytet, $dbname_hd.hd_status, zgloszenia.locations WHERE (zgl_data BETWEEN '".$_POST[data_od]."' and '".$_POST[data_do]."') and (zgl_kategoria=hd_kategoria_nr) and (zgl_podkategoria=hd_podkategoria_nr) and (zgl_priorytet=hd_priorytet_nr) and (zgl_status=hd_status_nr) and (belongs_to=location_id) and (zgl_widoczne=1) and (hd_kategoria_nr<>5) ORDER BY zgl_id ASC";

$result4 = mysql_query($sql4, $conn_hd) or die($k_b);
$count_rows1 = mysql_num_rows($result4);

if ($count_rows1>0) {
	
	while ($row = mysql_fetch_array($result4)) {
		$temp_id  			= $row['zgl_id'];
		$temp_nr			= $row['zgl_nr'];
		$temp_poczta_nr		= $row['zgl_poczta_nr'];
		$temp_data			= $row['zgl_data'];
		$temp_godzina		= $row['zgl_godzina'];
		$temp_komorka		= $row['zgl_komorka'];
		$temp_osoba			= $row['zgl_osoba'];
		$temp_telefon		= $row['zgl_telefon'];
		$temp_temat			= $row['zgl_temat'];	
		$temp_tresc			= $row['zgl_tresc'];
		$temp_kategoria		= $row['zgl_kategoria'];
		$temp_podkategoria	= $row['zgl_podkategoria'];
		$temp_priorytet		= $row['zgl_priorytet'];
		$temp_status 		= $row['zgl_status'];
		$temp_data_roz		= $row['zgl_data_rozpoczecia'];
		$temp_data_zak		= $row['zgl_data_zakonczenia'];
		$temp_opz			= $row['zgl_osoba_potwierdzajaca_zamkniecie_zgloszenia'];
		$temp_km			= $row['zgl_razem_km'];
		$temp_or			= $row['zgl_osoba_rejestrujaca'];
		$temp_op			= $row['zgl_osoba_przypisana'];
		$temp_zz			= $row['zgl_zasadne'];

		$temp_kategoria_opis 	= $row['hd_kategoria_opis'];
		$temp_podkategoria_opis = $row['hd_podkategoria_opis'];
		$temp_priorytet_opis 	= $row['hd_priorytet_opis'];
		$temp_status_opis 		= $row['hd_status_opis'];
		
		$temp_belongs_to_opis = $row['location_name'];
	/*	
	if ($temp_status==9) {
		$r3 = mysql_query("SELECT zgl_szcz_czas_rozpoczecia_kroku, zgl_szcz_czas_wykonywania FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$temp_id') and (zgl_szcz_widoczne=1) ORDER BY zgl_szcz_nr_kroku DESC LIMIT 1", $conn_hd) or die($k_b);
		list($w_zgl_szcz_czas_rozp, $w_zgl_szcz_czas_wyk)=mysql_fetch_array($r3);
		// wyznacz faktyczny czas zakończenia wykonywania kroku
		$newTime = AddMinutesToDate($w_zgl_szcz_czas_wyk,$w_zgl_szcz_czas_rozp);
		$temp_data_zamkniecia_zgloszenia = substr($newTime,0,16);
	} else $temp_data_zamkniecia_zgloszenia = '';
	*/
	
	if ($row[11]=='0000-00-00 00:00:00') $row[11]='';
	if ($row[12]=='0000-00-00 00:00:00') $row[12]='';
	if ($row[14]=='1') { $row[14]='TAK'; } else { $row[14]='NIE'; }
	
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ii.'', $jj);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ii.'', $row[1]);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ii.'', $row[2]);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ii.'', $row[3]);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ii.'', substr($row[4],0,5));
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ii.'', $row[5]);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$ii.'', $row[6]);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ii.'', $row[7]);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$ii.'', $row[8]);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J'.$ii.'', $row[9]);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$ii.'', $row[16]);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$ii.'', $row[17]);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$ii.'', $row[18]);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$ii.'', $row[19]);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$ii.'', $row[10]);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P'.$ii.'', $row[11]);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q'.$ii.'', $row[12]);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$ii.'', $row[13]);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('S'.$ii.'', $row[14]);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('T'.$ii.'', $row[15]);
	//$objPHPExcel->setActiveSheetIndex(0)->setCellValue('U'.$ii.'', $temp_data_zamkniecia_zgloszenia);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('V'.$ii.'', $row[20]);

		// jeżeli status=9 => pobierz czas zakończenia zgłoszenia
	/*	if ($temp_status==9) {
			$r3 = mysql_query("SELECT zgl_szcz_czas_rozpoczecia_kroku, zgl_szcz_czas_wykonywania FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$temp_id') and (zgl_szcz_widoczne=1) ORDER BY zgl_szcz_nr_kroku DESC LIMIT 1", $conn_hd) or die($k_b);
			list($w_zgl_szcz_czas_rozp, $w_zgl_szcz_czas_wyk)=mysql_fetch_array($r3);
			// wyznacz faktyczny czas zakończenia wykonywania kroku
			$newTime = AddMinutesToDate($w_zgl_szcz_czas_wyk,$w_zgl_szcz_czas_rozp);
			$temp_data_zamkniecia_zgloszenia = $newTime;
		} else $temp_data_zamkniecia_zgloszenia = '-';
	*/
	/*
		echo "<td>$temp_data_zamkniecia_zgloszenia</td>";
		echo "<td>$temp_belongs_to_opis</td>";
	*/
	
	/*	$objPHPExcel->getActiveSheet()->getStyle('G'.$ii.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$objPHPExcel->getActiveSheet()->getStyle('H'.$ii.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$objPHPExcel->getActiveSheet()->getStyle('I'.$ii.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
		$objPHPExcel->getActiveSheet()->getStyle('J'.$ii.'')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	
		$objPHPExcel->getActiveSheet()->getStyle('A'.$ii.':V'.$ii.'')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$ii.':V'.$ii.'')->getFill()->getStartColor()->setARGB('FF7F9ECF');
	*/
	/*
		$objPHPExcel->getActiveSheet()->getStyle('A'.$ii.'')->applyFromArray($zgloszenie); 
		$objPHPExcel->getActiveSheet()->getStyle('B'.$ii.'')->applyFromArray($zgloszenie); 
		$objPHPExcel->getActiveSheet()->getStyle('C'.$ii.'')->applyFromArray($zgloszenie); 
		$objPHPExcel->getActiveSheet()->getStyle('D'.$ii.'')->applyFromArray($zgloszenie); 
		$objPHPExcel->getActiveSheet()->getStyle('E'.$ii.'')->applyFromArray($zgloszenie);
		$objPHPExcel->getActiveSheet()->getStyle('F'.$ii.'')->applyFromArray($zgloszenie); 
		$objPHPExcel->getActiveSheet()->getStyle('G'.$ii.'')->applyFromArray($zgloszenie); 
		$objPHPExcel->getActiveSheet()->getStyle('H'.$ii.'')->applyFromArray($zgloszenie); 
		$objPHPExcel->getActiveSheet()->getStyle('I'.$ii.'')->applyFromArray($zgloszenie); 
		$objPHPExcel->getActiveSheet()->getStyle('J'.$ii.'')->applyFromArray($zgloszenie); 
		$objPHPExcel->getActiveSheet()->getStyle('K'.$ii.'')->applyFromArray($zgloszenie); 
		$objPHPExcel->getActiveSheet()->getStyle('L'.$ii.'')->applyFromArray($zgloszenie); 
		$objPHPExcel->getActiveSheet()->getStyle('M'.$ii.'')->applyFromArray($zgloszenie); 
		$objPHPExcel->getActiveSheet()->getStyle('N'.$ii.'')->applyFromArray($zgloszenie); 
		$objPHPExcel->getActiveSheet()->getStyle('O'.$ii.'')->applyFromArray($zgloszenie); 
		$objPHPExcel->getActiveSheet()->getStyle('P'.$ii.'')->applyFromArray($zgloszenie); 
		$objPHPExcel->getActiveSheet()->getStyle('Q'.$ii.'')->applyFromArray($zgloszenie); 
		$objPHPExcel->getActiveSheet()->getStyle('R'.$ii.'')->applyFromArray($zgloszenie); 
		$objPHPExcel->getActiveSheet()->getStyle('S'.$ii.'')->applyFromArray($zgloszenie); 
		$objPHPExcel->getActiveSheet()->getStyle('T'.$ii.'')->applyFromArray($zgloszenie); 
		$objPHPExcel->getActiveSheet()->getStyle('U'.$ii.'')->applyFromArray($zgloszenie); 
		$objPHPExcel->getActiveSheet()->getStyle('V'.$ii.'')->applyFromArray($zgloszenie); 	
	*/
	
	$ii++;
	
		// wylistuj kroki
		
		$sql_kroki = "SELECT zgl_szcz_nr_kroku, zgl_szcz_czas_rozpoczecia_kroku, hd_status_opis, zgl_szcz_wykonane_czynnosci, zgl_szcz_czas_wykonywania FROM $dbname_hd.hd_zgloszenie_szcz, $dbname_hd.hd_status WHERE (zgl_szcz_status=hd_status_nr) and (zgl_szcz_zgl_id='$temp_id') and (zgl_szcz_widoczne=1)";
		$result_kroki= mysql_query($sql_kroki, $conn_hd) or die($k_b);
		
		while ($newArray_kroki = mysql_fetch_array($result_kroki)) {
			$temp_krok_nr  			= $newArray_kroki['zgl_szcz_nr_kroku'];
			$temp_krok_czas_rozp	= $newArray_kroki['zgl_szcz_czas_rozpoczecia_kroku'];
			$temp_krok_status		= $newArray_kroki['hd_status_opis'];
			$temp_krok_wc  			= $newArray_kroki['zgl_szcz_wykonane_czynnosci'];
			$temp_krok_cw  			= $newArray_kroki['zgl_szcz_czas_wykonywania'];
			
			$newTime = AddMinutesToDate($temp_krok_cw,$temp_krok_czas_rozp);
			
			$c1 = explode(" ",$temp_krok_czas_rozp);
			$c2 = explode(" ",$newTime);
			
		//	tbl_tr_highlight($i);
			
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$ii.'', '');
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$ii.'', $row[1]);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$ii.'', $newArray_kroki[0]);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$ii.'', $c1[0]);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$ii.'', substr($c1[1],0,5));
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$ii.'', $newArray_kroki[4]);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$ii.'', $c2[0]);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$ii.'', substr($c2[1],0,5));
			$ww = $newArray_kroki[3];
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('I'.$ii.'', $ww);

			
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K'.$ii.'', $row[16]);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L'.$ii.'', $row[17]);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M'.$ii.'', $row[18]);
			
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N'.$ii.'', $newArray_kroki[2]);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O'.$ii.'', $row[10]);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('R'.$ii.'', $row[13]);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('V'.$ii.'', $row[20]);		
		
/*			$objPHPExcel->getActiveSheet()->mergeCells('I'.$ii.':M'.$ii.'');
			$objPHPExcel->getActiveSheet()->mergeCells('O'.$ii.':V'.$ii.'');
		
			$objPHPExcel->getActiveSheet()->getStyle('A'.$ii.':V'.$ii.'')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$ii.':V'.$ii.'')->getFill()->getStartColor()->setARGB('FFCCD8EC');						
	*/	
			$ii++;
		}

	
		$i++;
		
		$jj++;
		
	}
}

$start_row = 1;

/*	$objPHPExcel->getActiveSheet()->getStyle('A'.$start_row.'')->getFont()->setBold(true);
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
	$objPHPExcel->getActiveSheet()->getStyle('L'.$start_row.'')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('M'.$start_row.'')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('N'.$start_row.'')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('O'.$start_row.'')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('P'.$start_row.'')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('Q'.$start_row.'')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('R'.$start_row.'')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('S'.$start_row.'')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('T'.$start_row.'')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('U'.$start_row.'')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('V'.$start_row.'')->getFont()->setBold(true);
*/
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
	$objPHPExcel->getActiveSheet()->getStyle('L'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('M'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('N'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('O'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('P'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('Q'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('R'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('S'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('T'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('U'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
	$objPHPExcel->getActiveSheet()->getStyle('V'.$ii.'')->applyFromArray($styleThinBlackBorderOutline); 
*/
	//$objPHPExcel->getActiveSheet()->getStyle('A'.$start_row.':V'.$start_row.'')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
	//$objPHPExcel->getActiveSheet()->getStyle('A'.$start_row.':V'.$start_row.'')->getFill()->getStartColor()->setARGB('FF808080');
/*	$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
	$objPHPExcel->getActiveSheet()->getStyle('A1:V1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

	$objPHPExcel->getActiveSheet()->getStyle('A1:V'.($ii-1).'')->getFont()->setName('Verdana');
	$objPHPExcel->getActiveSheet()->getStyle('A1:V'.($ii-1).'')->getFont()->setSize(8);
	$objPHPExcel->getActiveSheet()->getStyle('A1:V'.($ii-1).'')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLACK);

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
*/
/*
if ($_POST[pole_1]!='on') $objPHPExcel->getActiveSheet()->removeColumn('A',1);
if ($_POST[pole_2]!='on') $objPHPExcel->getActiveSheet()->removeColumn('B',1);
if ($_POST[pole_3]!='on') $objPHPExcel->getActiveSheet()->removeColumn('C',1);
if ($_POST[pole_4]!='on') $objPHPExcel->getActiveSheet()->removeColumn('D',1);
if ($_POST[pole_5]!='on') $objPHPExcel->getActiveSheet()->removeColumn('E',1);
if ($_POST[pole_6]!='on') $objPHPExcel->getActiveSheet()->removeColumn('F',1);
if ($_POST[pole_7]!='on') $objPHPExcel->getActiveSheet()->removeColumn('G',1);
if ($_POST[pole_8]!='on') $objPHPExcel->getActiveSheet()->removeColumn('H',1);
if ($_POST[pole_9]!='on') $objPHPExcel->getActiveSheet()->removeColumn('I',1);
if ($_POST[pole_10]!='on') $objPHPExcel->getActiveSheet()->removeColumn('J',1);
if ($_POST[pole_11]!='on') $objPHPExcel->getActiveSheet()->removeColumn('K',1);
if ($_POST[pole_12]!='on') $objPHPExcel->getActiveSheet()->removeColumn('L',1);
if ($_POST[pole_13]!='on') $objPHPExcel->getActiveSheet()->removeColumn('M',1);
if ($_POST[pole_14]!='on') $objPHPExcel->getActiveSheet()->removeColumn('N',1);
if ($_POST[pole_15]!='on') $objPHPExcel->getActiveSheet()->removeColumn('O',1);
if ($_POST[pole_16]!='on') $objPHPExcel->getActiveSheet()->removeColumn('P',1);
if ($_POST[pole_17]!='on') $objPHPExcel->getActiveSheet()->removeColumn('Q',1);
if ($_POST[pole_18]!='on') $objPHPExcel->getActiveSheet()->removeColumn('R',1);
if ($_POST[pole_19]!='on') $objPHPExcel->getActiveSheet()->removeColumn('S',1);
if ($_POST[pole_20]!='on') $objPHPExcel->getActiveSheet()->removeColumn('T',1);
if ($_POST[pole_21]!='on') $objPHPExcel->getActiveSheet()->removeColumn('U',1);
if ($_POST[pole_22]!='on') $objPHPExcel->getActiveSheet()->removeColumn('V',1);

*/

$objPHPExcel->getActiveSheet()->setTitle('Raport_szczegolowy');
$objPHPExcel->createSheet();

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename='.$export_filename.'');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output'); 
?>