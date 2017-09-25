<?php 
require_once 'cfg_eserwis.php';
session_start(); 
if (!session_is_registered('es_autoryzacja')) { header("Location: ".$linkdostrony.""); }

//get contents
//define date for title
$now_date = date('d-m-Y H:i');
$rok = $_POST[r_rok];
$miesiac = $_POST[r_miesiac];

//$filianazwa=$_POST['filian'];
$filianazwa=$_POST['umowanr'];
if ($filianazwa=='all') $filianazwa='zbiorcze';

switch ($miesiac) {
  case "01" : $miesiac_slownie="styczeñ"; break;
  case "02" : $miesiac_slownie="luty"; break;
  case "03" : $miesiac_slownie="marzec"; break;
  case "04" : $miesiac_slownie="kwiecieñ"; break;
  case "05" : $miesiac_slownie="maj"; break;
  case "06" : $miesiac_slownie="czerwiec"; break;
  case "07" : $miesiac_slownie="lipiec"; break;
  case "08" : $miesiac_slownie="sierpieñ"; break;
  case "09" : $miesiac_slownie="wrzesieñ"; break;
  case "10" : $miesiac_slownie="paŸdziernik"; break;
  case "11" : $miesiac_slownie="listopad"; break;
  case "12" : $miesiac_slownie="grudzieñ"; break;
}

$file_type = "vnd.ms-excel";
$file_ending = "xls";

header("Content-Type: application/$file_type");
header("Content-Disposition: attachment; filename=obciazenie_$miesiac_slownie'$rok(".$filianazwa.").$file_ending");
header("Pragma: no-cache");
header("Expires: 0");

$title = "Dane do fakturowania za miesi¹c ".$miesiac_slownie." ".$rok."";
echo("$title\n");

echo "\n";

//echo "LP\tNazwa towaru lub usÅ‚ugi\tMiejsce instalacji\tIlosÄ‡\tjedn. masy\tcena netto\tcena jedn. netto z marÅ¼a\tDostawca\tNumer faktury\tData wystawienia\n";

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


//$sql = "SELECT * FROM $dbname.serwis_sprzedaz, $dbname.serwis_faktury WHERE sprzedaz_faktura_id=faktura_numer and serwis_sprzedaz.belongs_to=$es_filia and sprzedaz_umowa_nazwa='$_POST[umowanr]' and sprzedaz_data BETWEEN '$_POST[poczatek]' AND '$_POST[koniec]'";  


//$sql = "SELECT sprzedaz_pozycja_nazwa, sprzedaz_up_nazwa,'1','szt',sprzedaz_pozycja_cenanetto, sprzedaz_pozycja_cenanetto * 1.10, faktura_numer, faktura_data, faktura_dostawca, sprzedaz_rodzaj FROM $dbname.serwis_sprzedaz, $dbname.serwis_faktury WHERE sprzedaz_faktura_id=faktura_numer and serwis_sprzedaz.belongs_to=$es_filia and sprzedaz_umowa_nazwa='$_POST[umowanr]' and sprzedaz_data BETWEEN '$_POST[poczatek]' AND '$_POST[koniec]'";  

$sql = "SELECT pole2,pole3,pole4,pole5,pole6,pole7,pole8,pole9,pole10,pole11,pole13 FROM $dbname.serwis_temp_raport$es_filia ORDER BY pole13 ASC"; // WHERE (pole12=$_POST[unique])";  
//echo "$sql\n";

$result = mysql_query($sql, $conn) or die($k_b);
/*
@mysql_query("SET character_set_client=latin1", $conn);
@mysql_query("SET character_set_connection=latin1", $conn);
@mysql_query("SET collation_connection=latin1", $conn);
@mysql_query("SET character_set_results=latin1", $conn);
*/

echo "LP\tNazwa towaru lub us³ugi\tMiejsce instalacji\tIloœæ\tjedn. masy\tcena netto\tcena jedn. netto z mar¿¹\tNumer faktury\tData wystawienia\tDostawca\tRodzaj sprzeda¿y\tNumer zlecenia";
echo "\n";

$kol = mysql_num_fields($result);

    $i = 0;
	$y = 1;
    while($row = mysql_fetch_row($result))
    {
        //set_time_limit(60); // HaRa
        $schema_insert = "";
        $schema_insert.="$y".$sep;
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
		
//		if ($_POST['raportuj'.$i]=='on')
//		{
			print(trim($schema_insert));
			print "\n";
//		}
		
        $i++;
    }
    return (true);

?>