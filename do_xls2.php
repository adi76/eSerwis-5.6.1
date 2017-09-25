<?php 
require_once 'cfg_eserwis.php';
session_start(); 
if (!session_is_registered('es_autoryzacja')) { header("Location: ".$linkdostrony.""); }

$now_date = date('d-m-Y H:i');
$rok = date('Y');
$miesiac = date('m');

$filianazwa=$_POST['filian'];
$nazwapliku=$_POST['obc1']."_";

if ($_POST[addsell]!='on') { $nazwapliku=''; }

switch ($miesiac) {
  case "01" : $miesiac_slownie="styczeń"; 		break;
  case "02" : $miesiac_slownie="luty"; 			break;
  case "03" : $miesiac_slownie="marzec"; 		break;
  case "04" : $miesiac_slownie="kwiecień"; 		break;
  case "05" : $miesiac_slownie="maj"; 			break;
  case "06" : $miesiac_slownie="czerwiec"; 		break;
  case "07" : $miesiac_slownie="lipiec"; 		break;
  case "08" : $miesiac_slownie="sierpień"; 		break;
  case "09" : $miesiac_slownie="wrzesień"; 		break;
  case "10" : $miesiac_slownie="październik"; 	break;
  case "11" : $miesiac_slownie="listopad"; 		break;
  case "12" : $miesiac_slownie="grudzień"; 		break;
}

$file_type = "vnd.ms-excel";
$file_ending = "xls";

header("Content-Type: application/$file_type");
header("Content-Disposition: attachment; filename=Zestawienie_zakupow_sprzedazy_".$nazwapliku."Filia_$filianazwa.$file_ending");
header("Pragma: no-cache");
header("Expires: 0");

//define separator (defines columns in excel)
$sep = "\t";

//print excel header with timestamp:

//start of printing column names
$j = 1;

//start while loop to get data
/*      
note: the following while-loop was taken from phpMyAdmin 2.1.0.
--from the file "lib.inc.php".
*/








/*
$sql = "SELECT pole2,pole3,pole4,pole5,pole6,pole7,pole8,pole9,pole10,pole11,pole12,pole13 FROM $dbname.serwis_temp_m1$es_filia WHERE (pole14=$_POST[unique])";  

$result = mysql_query($sql, $conn) or die($k_b);
while($newArray1 = mysql_fetch_array($result))
{
	$temp_cena	=	$newArray1['pole7'];  
	$cena = str_replace('.',',',$temp_cena);
	
	$sql_up = "UPDATE $dbname.serwis_temp1 SET pole7='$cena'";
	$result_up = mysql_query($sql_up, $conn) or die($k_b);
		
}

*/








//$sql = "SELECT * FROM $dbname.serwis_sprzedaz, serwis_faktury WHERE sprzedaz_faktura_id=faktura_numer and serwis_sprzedaz.belongs_to=$es_filia and sprzedaz_umowa_nazwa='$_POST[umowanr]' and sprzedaz_data BETWEEN '$_POST[poczatek]' AND '$_POST[koniec]'";  

$sql = "SELECT pole2,pole3,pole4,pole5,pole6,pole7,pole8,pole9,pole10,pole11,pole12,pole13 FROM $dbname.serwis_temp_m1$es_filia WHERE (pole14=$_POST[unique])";  

//echo "$sql\n";

$result = mysql_query($sql, $conn) or die($k_b);

print "Data zakupu\tNr faktury\tDostawca\tNazwa części\tIlość\tCena zakupu\tCena odsprzedaży\tZlecenie fakturowania\tNumer zlecenia\tFilia\tRealizacja zakupu\tRodzaj sprzedaży";
print "\n";

$kol = mysql_num_fields($result);

    $i = 0;
	$y = 1;
    while($row = mysql_fetch_row($result))
    {

	
        //set_time_limit(60); // HaRa
        $schema_insert = "";
        $schema_insert.=$sep;
		$y++;

        for($j=0; $j<$kol;$j++)
        {

			if(!isset($row[$j]))
                $schema_insert .= "NULL".$sep;
            elseif ($row[$j] != "")
                $schema_insert .= "$row[$j]".$sep;
            else
                $schema_insert .= "".$sep;
        }
        $schema_insert = str_replace($sep."$", "", $schema_insert);
		//$schema_insert = preg_replace("/rn|nr|n|r/", "_", $schema_insert);
		$schema_insert .= "\t";
		
        print(trim($schema_insert));
		print "\n";
        $i++;
    }
    //return (true);


if (($_POST[addsell]=='on') && ($_POST[wykrap]>0))
{
		
		$sql1 = "SELECT pole2,pole3,pole4,pole5,pole6,pole7,pole8,pole9,pole10,pole11,pole12,pole13 FROM $dbname.serwis_temp_m2$es_filia WHERE (pole14=$_POST[unique])";  
//		print "\nSprzedaż towarów w miesiącu $_POST[obc]\n\n";
		$result1 = mysql_query($sql1, $conn) or die($k_b);
	//	print "Data zakupu\tNr faktury\tDostawca\tNazwa części\tIlość\tCena zakupu\tCena odsprzedaży\tZlecenie fakturowania\tNumer zlecenia\tFilia\tRealizacja zakupu\tRodzaj sprzedaży\n";

//				echo "$sql";
		
		$kol = mysql_num_fields($result1);
		
			$i = 0;
			$y = 1;
			while($row = mysql_fetch_row($result1))
			{
		
				
				//set_time_limit(60); // HaRa
				$schema_insert = "";
				$schema_insert.=$sep;
				$y++;
		
				for($j=0; $j<$kol;$j++)
				{
		
					if(!isset($row[$j]))
						$schema_insert .= "NULL".$sep;
					elseif ($row[$j] != "")
						$schema_insert .= "$row[$j]".$sep;
					else
						$schema_insert .= "".$sep;
				}
				$schema_insert = str_replace($sep."$", "", $schema_insert);
				//$schema_insert = preg_replace("/rn|nr|n|r/", "_", $schema_insert);
				$schema_insert .= "\t";
				
				print(trim($schema_insert));
				print "\n";
				$i++;
			}
} 


    return (true);

?>