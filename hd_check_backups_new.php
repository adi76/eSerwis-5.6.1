<?php include_once('header.php'); ?>
<body>
<script>
createCookie('break_loop','');
</script>
<?php 
include('body_start.php'); 

if ($es_mminhd==1) { 
	echo "<div id=mainmenu style='display:;'>";
		include_once('login_info.php');
		include_once('mainmenu.php');
		echo "<br />";

	echo "</div>";
}

include('cfg_sp2000_backup_script_ver.php');

function remote_filesize($url) {
    static $regex = '/^Content-Length: *+\K\d++$/im';
    if (!$fp = @fopen($url, 'rb')) {
        return false;
    }
    if (
        isset($http_response_header) &&
        preg_match($regex, implode("\n", $http_response_header), $matches)
    ) {
        return (int)$matches[0];
    }
    return strlen(stream_get_contents($fp));
}

function formatBytes_new($bytes, $precision = 0) {
    $unit = array(" B", " KB", " MB", " GB");
    $exp = floor(log($bytes, 1024)) | 0;
    return round($bytes / (pow(1024, $exp)), $precision).$unit[$exp];
}

function formatBytes_new2($bytes, $precision = 0) {
    $unit = array("", "", "", "");
    $exp = floor(log($bytes, 1024)) | 0;
    return round($bytes / (pow(1024, $exp)), $precision).$unit[$exp];
}

function sort_by($field, &$arr, $sorting=SORT_ASC, $case_insensitive=true){
    if(is_array($arr) && (count($arr)>0) && ( ( is_array($arr[0]) && isset($arr[0][$field]) ) || ( is_object($arr[0]) && isset($arr[0]->$field) ) ) ){
        if($case_insensitive==true) $strcmp_fn = "strnatcasecmp";
        else $strcmp_fn = "strnatcmp";

        if($sorting==SORT_ASC){
            $fn = create_function('$a,$b', '
                if(is_object($a) && is_object($b)){
                    return '.$strcmp_fn.'($a->'.$field.', $b->'.$field.');
                }else if(is_array($a) && is_array($b)){
                    return '.$strcmp_fn.'($a["'.$field.'"], $b["'.$field.'"]);
                }else return 0;
            ');
        }else{
            $fn = create_function('$a,$b', '
                if(is_object($a) && is_object($b)){
                    return '.$strcmp_fn.'($b->'.$field.', $a->'.$field.');
                }else if(is_array($a) && is_array($b)){
                    return '.$strcmp_fn.'($b["'.$field.'"], $a["'.$field.'"]);
                }else return 0;
            ');
        }
        usort($arr, $fn);
        return true;
    }else{
        return false;
    }
}

function columnSort($unsorted, $column) { 
    $sorted = $unsorted; 
    for ($i=0; $i < sizeof($sorted)-1; $i++) { 
      for ($j=0; $j<sizeof($sorted)-1-$i; $j++) 
        if ($sorted[$j][$column] > $sorted[$j+1][$column]) { 
          $tmp = $sorted[$j]; 
          $sorted[$j] = $sorted[$j+1]; 
          $sorted[$j+1] = $tmp; 
      } 
    } 
    return $sorted; 
} 

function toUpper($string) {
	$string = strtr($string,'qwertyuiopasdfghjklzxcvbnm','QWERTYUIOPASDFGHJKLZXCVBNM');
	return (strtr($string, 'ęóąśłżźćń','ĘÓĄŚŁŻŹĆŃ' )); 
}; 

function formatBytes($size, $precision = 2)
{
    $base = log($size) / log(1024);
    $suffixes = array('', 'k', 'MB', 'GB', 'TB');   

    return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
}

$sql="SELECT * FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ";
if (($es_m==1) || ($_REQUEST[allfilie]=='TAK')) { } else $sql=$sql."(serwis_komorki.belongs_to=$es_filia) and  ";
$sql.=" (serwis_komorki.up_pion_id=serwis_piony.pion_id) ";
$sql.="AND (serwis_komorki.up_backupname<>'') and (serwis_komorki.up_active=1) ";
$sql.="ORDER BY serwis_piony.pion_nazwa,serwis_komorki.up_nazwa ASC";

$result = mysql_query($sql, $conn) or die($k_b);
$count_rows = mysql_num_rows($result);
// paging
$totalrows = mysql_num_rows($result);
if ($showall==0) { $rps=$rowpersite; } else $rps=10000;
if(empty($page)){ $page = 1; }
$limitvalue = $page * $rps - ($rps);
$sql="SELECT * FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE ";
//if ($es_m==1) { } else $sql=$sql."(serwis_komorki.belongs_to=$es_filia) and  ";
if (($es_m==1) || ($_REQUEST[allfilie]=='TAK')) { } else $sql=$sql."(serwis_komorki.belongs_to=$es_filia) and  ";
$sql.=" (serwis_komorki.up_pion_id=serwis_piony.pion_id) ";
$sql.="AND (serwis_komorki.up_backupname<>'') and (serwis_komorki.up_active=1) ";
$sql.=" ORDER BY ";

if ($_REQUEST[allfilie]=='TAK') $sql.=" serwis_komorki.belongs_to ASC, ";

$sql.=" serwis_piony.pion_nazwa,serwis_komorki.up_nazwa ASC "; //LIMIT $limitvalue, $rps";

//echo $sql;

$result = mysql_query($sql, $conn) or die($k_b);
$count_rows = mysql_num_rows($result);

if ($count_rows!=0) {
	pageheader("Sprawdzenie wykonania kopii z poszczególnych lokalizacji",1,0);
	if ($_REQUEST[view]=='error') infoheader('Lista komórek w których występują problemy z wykonaniem kopii');
	
	ob_flush(); flush();
	
	?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
	
	starttable();
	//th("30;c;LP|;;Nazwa komórki<br /><sub>Adres<br />Opis</sub>|;c;Podsieć|;c;Nr WAN-portu|;;Telefon|;c;Test łączności|;c;Opcje",$es_prawa);
	echo "<tr id=h0><th width=30 class=center>LP</th>";
	if ($_REQUEST[allfilie]=='TAK') echo "<th class=center>Filia/Oddział</th>";
	echo "<th class=left>Nazwa komórki</th><th class=left>Telefon</th><th class=left>Informacje o wykonanych kopiach</th><th class=right width=150><div style='float:left;'>Wolne miejsce</div><br /><div style='float:right;'>Serwer<br />Stacja robocza</div></th><th width=150 class=right><div style='float:left;'>Ilość kopii z dni</div><br /><div style='float:right;'>Serwer<br />Stacja robocza</div></th><th>Lokalizacja ostatniej kopii lokalnej<br />Lokalizacja ostatniej kopii zdalnej</th><th class=center width=50>Wersja<br />skryptu</th><th class=center width=40>WOL</th><th class=center>Opcje</th></tr>";
	//echo "<tr id=h0a><th class=center>Informacja </th><th class=center>Data</th><th class=center>Status</th><th class=center>Rozmiar</th>";
	
	$i = 0;
	$j = $page*$rowpersite-$rowpersite+1;
	
	$h = 0;
	
	$count_bez_godzin_pracy = 0;
	$x = 1;
	ob_flush(); flush();
	while ($newArray = mysql_fetch_array($result)) {
		
		$temp_id  				= $newArray['up_id'];
		$temp_nazwa				= $newArray['up_nazwa'];
		$temp_opis				= $newArray['up_opis'];
		$temp_adres				= $newArray['up_adres'];
		$temp_telefon			= $newArray['up_telefon'];
		$temp_ip				= $newArray['up_ip'];
		$temp_nrwanportu		= $newArray['up_nrwanportu'];
		$temp_stempel			= $newArray['up_stempel'];	
		$temp_belongs_to		= $newArray['belongs_to'];
		$temp_pion_id			= $newArray['up_pion_id'];
		$temp_umowa_id			= $newArray['up_umowa_id'];
		$temp_active_status 	= $newArray['up_active'];
		$temp_up_typ			= $newArray['up_typ'];
		$temp_up_kategoria		= $newArray['up_kategoria'];
		$temp_up_ko				= $newArray['up_kompleksowa_obsluga'];
		$temp_working_time		= $newArray['up_working_time'];	
		$temp_working_time_alt	= $newArray['up_working_time_alternative'];	
		$temp_working_time_start_date	= $newArray['up_working_time_alternative_start_date'];		
		$temp_working_time_stop_date	= $newArray['up_working_time_alternative_stop_date'];		
		$temp_typ_uslugi				= $newArray['up_typ_uslugi'];
		$temp_przypisanie_jedn			= $newArray['up_przypisanie_jednostki'];
		$temp_komorka_macierzysta 		= $newArray['up_komorka_macierzysta_id'];
		$temp_backupname				= $newArray['up_backupname'];
		$temp_ipserwera					= $newArray['up_ipserwera'];
		
		$count_problems=0;
		tbl_tr_highlight($i);
		$result1 = mysql_query("SELECT filia_nazwa,filia_skrot FROM $dbname.serwis_filie WHERE filia_id=$temp_belongs_to", $conn) or die($k_b);
		list($temp_filia_nazwa, $es_skrot1)=mysql_fetch_array($result1);
			echo "<td class=center>";
			if ($temp_active_status=='2') echo "<font color=red>";
			if ($temp_active_status=='0') echo "<strike>";
			echo $j;
			//echo "-".$_COOKIE['break_loop'];
			if ($temp_active_status=='0') echo "</strike>";
			if ($temp_active_status=='2') echo "</font>";
			echo "</td>";
			$j++;
			
			list($pionnazwa)=mysql_fetch_array(mysql_query("SELECT pion_nazwa FROM $dbname.serwis_piony WHERE pion_id=$temp_pion_id LIMIT 1", $conn));

		if ($_REQUEST[allfilie]=='TAK') {
			echo "<td class=center>";
			echo $temp_filia_nazwa;
			echo "</td>";
		}
		
		echo "<td rowspan=1>";
			echo "<a href=# class=normalfont title='Adres komórki: ".$temp_adres."' onclick=\"newWindow_r(700,600,'p_komorka_szczegoly.php?id=$temp_id'); return false; \"><b>";
			if ($temp_active_status=='0') echo "<strike>";
			if ($temp_active_status=='2') echo "<font color=red>";
			
			if ($temp_komorka_macierzysta>0) echo "<font color=grey>";			
			if (($temp_komorka_macierzysta>0) && ($wciecie_dla_podleglych_komorek==1)) echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			
			echo "".$pionnazwa." ".$temp_nazwa."";
			
			if ($temp_active_status=='2') echo " - komórka zamknięta";
			
			if ($temp_active_status=='2') echo "</font>";
			if ($temp_active_status=='0') echo "</strike>";
			if ($temp_komorka_macierzysta>0) echo "</font>";
			echo "</b></a>";
		_td();

		echo "<td>";
			echo $temp_telefon;
		_td();

		$cnt_backups = 0;
		
		//ob_flush(); flush();
		
		$steps = array();
		$steps2 = array();
		

if ($handle = opendir(realpath($sciezka_do_logow_z_kopiami."/".$es_skrot1.""))) {
	$steps[10]='pomin';
	
	$file_list = array(); 
	// [0] - nazwa pliku
	// [1] - data utworzenia
	
	$search_dir = realpath($sciezka_do_logow_z_kopiami."/".$es_skrot1.""); 
	$dp = opendir($search_dir); 

	while ($item = readdir($dp)) { 
		
		if (substr($item, 0, 1) != '.') { 
		
			$backup_pattern = toUpper($temp_backupname."_SP2000_");
			$backup_pattern_lenght = strlen($backup_pattern);	
			$file_pattern = substr(toUpper($item),0,$backup_pattern_lenght);
				
			if ($backup_pattern==$file_pattern) {
				
				$_type = substr($item,-26,-22);
				$_skrot = toUpper(substr($_type,0,1));
				$_r = substr($item,-21,-17);
				$_m = substr($item,-16,-14);
				$_d = substr($item,-13,-11);
				
				$_h = substr($item,-9,-7);
				$_mi = substr($item,-6,-4);
				
				$data_pliku = strtotime($_r."-".$_m."-".$_d." ".$_h.":".$_mi.":00");
				
				$checkdate = $_r."".$_m."".$_d."".$_h."".$_mi;
				
				if (is_numeric($checkdate)) {
					$file_list[] = array('name' => $item, 'mtime' => $data_pliku, 'data' => ($_r."-".$_m."-".$_d." ".$_h.":".$_mi.""), 'type' => $_type, 'skrot' => $_skrot); 
				}
				//echo $_r."-".$_m."-".$_d." ".$_h.":".$_mi.":00<br />";
				
			}
		}
	} 

	// Sort $file_list 
	sort_by('data',$file_list,SORT_ASC);
	
//	var_dump($file_list);
	//var_dump($tablica_pomocnicza);
	
	$_day = '';
	$t = 0;
	
	$_free_space_on_remote_computer = 0;
	
	$not_available_count=0;
	
	$WOL_start_check = false;
	$WOL_ready_check = false;
	$WON_count_ok = 0;
	
	$WOL_working = false;
	
	$FULL_size = 0;
	
	foreach ($file_list as $plik) {
	
//		echo $plik['name']."<br />";
			
		if ($t==0) {
			$first_date = substr($plik['data'],0,10);		
			if (Date('Y-m-d')!=$first_date) { $_day.="<font color=grey>";} else { $_day.="<font color=black>";}
			$_day .= $first_date.": ";
			if (Date('Y-m-d')!=$first_date) $_day.="</font>";
			$t=1;
		}
		
		if ($first_date != substr($plik['data'],0,10)) {
			$_day .= "<br />";
			$first_date = substr($plik['data'],0,10);
			if (Date('Y-m-d')!=$first_date) { $_day.="<font color=grey>";} else { $_day.="<font color=black>";}
			$_day .= $first_date.": ";
			if (Date('Y-m-d')!=$first_date) $_day.="</font>";
		}
	
	
		$fs = fopen(realpath($sciezka_do_logow_z_kopiami."/".$es_skrot1."")."\\".$plik['name']."","r");
		$lines = array();
		while (!feof($fs)) {
			//	echo fgets($fs)."<br />";
			$x = trim(fgets($fs));
			if ((strlen($x))>0) $lines[]=$x;
		}
		
		$steps = array();
		/*
			0 - start skryptu
			1 - ustawiona ilosc kopii
			2 - prefiks nazwy pliku
			3 - nazwa komputera na ktorym wykonywana jest kopia (lokalnie)
			4 - lokalizacja kopii (lokalnie)
			5 - nazwa komputera (w ramach tej samej podsieci) na ktorym przechowywana jest kopia
			6 - nazwa udzialu na komputerze docelowym, w ktorej przechowywane sa kopie
			7 - czy komputer docelowy ma zostac wylaczony po wykonaniu kopii
			8 - czy uzywac funkcji WOL dla komputera docelowego
			9 - mac address komputera docelowego
			10 - ilosc sekund potrzebnych na start komputera docelowego po uzyciu funkcji WOL
			11 - czy logi maja byc wysylane na FTPa
			12 - skrot filii
			13 - adres FTPa
			14 - czy baza jest kompresowana
			
			15 - rozpoczecie wykonywania backupu
			16 - zakonczenie wykonywania backupu
			17 - czy backup wykonal sie poprawnie
			
			18 - rozpoczecie weryfikacji backupu
			19 - zakonczenie weryfikacji backupu
			20 - czy weryfikacja przebiegla poprawnie
			
			21 - rozpoczecie generowania sumy kontrolnej dla backupu
			22 - zakonczenie generowania sumy kontrolnej dla backupu
			23 - czy wygenerowano sume kontrolna poprawnie
			
			24 - rozpoczecie kopiowania backupu na komputer docelowy
			25 - zakonczenie kopiowania backupu na komputer docelowy
			26 - czy skopiowano plik na komputer docelowy
			
			27 - rozpoczecie weryfikacji sumy kontrolnej backupu na komputerze docelowym
			28 - zakonczenie weryfikacji sumy kontrolnej backupu na komputerze docelowym
			29 - czy suma kontrolna na komputerze docelowym jest zgodna z suma kontrolna kopii lokalnej
			
			30 - ilosc wolnego miejsca na komputerze docelowym
			31 - ilosc wolnego miejsca na komputerze lokalnym
			
			32 - rozpoczecie wysylki logow na FTPa
			33 - zakonczenie wysylki logow na FTPa
			
			34 - czy kopia jest wykonana poprawnie
			35 - czy komputer docelowy jest dostepny
			36 - czy komputer docelowy reaguje na sygnal WOL
			37 - rozmiar archiwum 
			
			38 - czy na rozpoczeciu wykonywania skryptu komputer docelowy jest dostepny
			39 - czy przed kopiowaniem pliku na komputer docelowy komputer jest dostępny
			40 - czy została uruchomiona procedura wyłączania komputera
			
			41 - wersja skryptu
			42 - data skryptu

			43 - czas zakonczenia wykonywania skryptu
			
			44 - informacja o desynchronizacji folderow z kopiami
			45 - informacja o udanej synchronizacji folderow
			
			46 - ilosc kopii na stacji roboczej
			47 - auto mac address
			
			48 - rodzaj backupu (FULL, DIFF)
			*/
		
		
		$steps_ok_count = 0;
		$xxx = 0;
		
		$ftp_off = false;
		foreach ($lines as $line) {
		//	echo $line."<br />";	
			
			if (strpos(trim($line),"STEP_0#Konfiguracja skryptu")>=1) {	$_t = explode("#",$line); $steps[0] = $_t[3]; }			
			if (strpos(trim($line),"STEP_0#ILOSCKOPII")>=1) { $_t = explode("#",$line);	$steps[1] = $_t[3];	}
			if (strpos(trim($line),"STEP_0#PREFIX_NAZWY_PLIKU_Z_KOPI")>=1) { $_t = explode("#",$line); $steps[2] = $_t[3]; }
			if (strpos(trim($line),"STEP_0#NAZWA_KOMPUTERA_LOKALNA")>=1) { $_t = explode("#",$line); $steps[3] = $_t[3]; }
			if (strpos(trim($line),"STEP_0#KATALOG_Z_KOPIAMI")>=1) { $_t = explode("#",$line); $steps[4] = $_t[3]; }
			if (strpos(trim($line),"STEP_0#NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII")>=1) { $_t = explode("#",$line); $steps[5] = $_t[3]; }
			if (strpos(trim($line),"STEP_0#NAZWA_UDZIALU_NA_KOMPUTERZE_DOCELOWYM")>=1) { $_t = explode("#",$line); $steps[6] = $_t[3]; }
			if (strpos(trim($line),"STEP_0#WYLACZ_KOMPUTER_PO_WYKONANIU_KOPII")>=1) { $_t = explode("#",$line); $steps[7] = $_t[3]; }
			if (strpos(trim($line),"STEP_0#UZYJ_WON")>=1) { $_t = explode("#",$line); $steps[8] = $_t[3]; }
			if (strpos(trim($line),"STEP_0#MAC_ADDRESS")>=1) { $_t = explode("#",$line); $steps[9] = $_t[3]; }
			if (strpos(trim($line),"STEP_0#ILOSC_SEKUND_POTRZEBNYCH_NA_URUCHOMIENIE_KOMPUTERA")>=1) { $_t = explode("#",$line); $steps[10] = $_t[3]; }
			if (strpos(trim($line),"STEP_0#WYSYLAJ_LOGI_NA_FTP")>=1) { $_t = explode("#",$line); $steps[11] = $_t[3]; }			
			if (strpos(trim($line),"STEP_0#FTP_FILIA")>=1) { $_t = explode("#",$line); $steps[12] = $_t[3]; }
			if (strpos(trim($line),"STEP_0#FTP_IP")>=1) { $_t = explode("#",$line); $steps[13] = $_t[3]; }
			
			if (strpos(trim($line),"STEP_1#COMPRESSION#")>=1) { $_t = explode("#",$line); $steps[14] = $_t[3]; }
			
			if (strpos(trim($line),"STEP_1#START#Rozpoczecie wykonywania backupu")>=1) { $_t = explode("#",$line); $steps[15] = $_t[4]; }
			if (strpos(trim($line),"STEP_1#STOP#Zakonczenie wykonywania backupu")>=1) { $_t = explode("#",$line); $steps[16] = $_t[4]; }
			if (strpos(trim($line),"successfully processed")>=1) { $steps[17] = 'TAK'; $steps_ok_count++; }
			
			if (strpos(trim($line),"STEP_2#START#Rozpoczecie procesu weryfikacji backupu")>=1) { $_t = explode("#",$line); $steps[18] = $_t[4]; }
			if (strpos(trim($line),"STEP_2#STOP#Zakonczenie procesu weryfikacji backupu")>=1) { $_t = explode("#",$line); $steps[19] = $_t[4]; }
			if (strpos(trim($line),"he backup set on file 1 is valid.")>=1) { $steps[20] = 'TAK'; $steps_ok_count++; }

			if (strpos(trim($line),"STEP_3#START#Rozpoczecie generowania sumy kontrolnej backupu")>=1) { $_t = explode("#",$line); $steps[21] = $_t[4]; }
			if (strpos(trim($line),"STEP_3#STOP#Zakonczenie procesu generowania sumy kontrolnej")>=1) { $_t = explode("#",$line); $steps[22] = $_t[4]; }
			if (strpos(trim($line),"STEP_3#INFO_GEN_SUMY_KONTR")>=1) { 
				$_t = explode("#",$line); $steps[23] = $_t[3]; 
				if ($steps[23]=='TAK') $steps_ok_count++;
				if ($steps[23]=='NIE') { $xxx = 3; }
			}
		
			if (strpos(trim($line),"STEP_5#START#Rozpoczecie kopiowania backupu na komputer")>=1) { $_t = explode("#",$line); $steps[24] = $_t[4]; }
			if (strpos(trim($line),"STEP_5#STOP#Zakonczenie kopiowania backupu na komputer")>=1) { $_t = explode("#",$line); $steps[25] = $_t[4]; }
			if (strpos(trim($line),"STEP_5#INFO_O_WYK_KOPII")>=1) { 
				$_t = explode("#",$line); $steps[26] = $_t[3]; 
				if ($steps[26]=='TAK') $steps_ok_count++;
				if ($steps[26]=='NIE') { $xxx = 4; }
			}
			
			if (strpos(trim($line),"STEP_6#START#Rozpoczecie weryfikacji sumy kontrolnej backupu")>=1) { $_t = explode("#",$line); $steps[27] = $_t[4]; }
			if (strpos(trim($line),"STEP_6#STOP#Zakonczenie weryfikacji sumy kontrolnej backupu")>=1) { $_t = explode("#",$line); $steps[28] = $_t[4]; }
			if (strpos(trim($line),"STEP_6#INFO_WER_OK")>=1) { 
				$_t = explode("#",$line); $steps[29] = $_t[3]; 
				if ($steps[29]=='TAK') $steps_ok_count++;
				if ($steps[29]=='NIE') { $xxx = 5; }
			}
			
			if (strpos(trim($line),"STEP_6#ERROR#Weryfikacja kopii na komputerze")>=1) { $steps[29] = 'NIE'; }
					
			if (strpos(trim($line),"reeSpace=")>=1) { 
				$_t = explode("=",$line); $steps[30] = $_t[1]; 
				
				$a_f2 = explode(" ",$steps[30]);
				//echo $a_f2[0]."<br />";
				
				if ($a_f2[0] > 0 ) { $_free_space_on_remote_computer = $a_f2[0]; }				
			}
			if (strpos(trim($line),"vail. ")>=1) { $_t = explode(". ",$line); $steps[31] = $_t[1]; }

			if (strpos(trim($line),"STEP_11#START#Rozpoczecie wysylki plikow LOG na FTPa")>=1) { $_t = explode("#",$line); $steps[32] = $_t[4]; }
			if (strpos(trim($line),"STEP_11#STOP#ZAKONCZENIE wysylki plikow LOG na FTPa")>=1) { $_t = explode("#",$line); $steps[33] = $_t[4]; $steps_ok_count++;}
			if ($steps[11]=='NIE') {
				if ($ftp_off==false) { $steps_ok_count++; $ftp_off = true; }
			}
			
			
			if (strpos(trim($line),"verified successfully")>1) {
				//$_t = explode("#",$line);
				//$steps[0] = substr($_t[3],0,16);
				$steps[34] = "TAK";		
			} else {
				$steps[34] = "NIE";
				//$is_backup_ok = false;
				//$xxx = 6;
			}
			
			$steps[35]="TAK";
			if (strpos(trim($line),"STEP_4#ERROR#Komputer")>=1) { $steps[35] = 'NIE'; }		
		
			if ($steps[8]=="TAK") {
				$steps[36]="TAK";
				if (strpos(trim($line),"STEP_4#ERRORWOL#Komputer")>=1) { $steps[36] = 'NIE'; }
				
				if (strpos(trim($line),"STEP_0#ERROR#Komputer")>=1) { $_t = explode("#",$line); $steps[38] = "NIE"; }
				if (strpos(trim($line),"STEP_0#INFO#KOMPUTER DOCELOWY")>=1) { $_t = explode("#",$line); $steps[38] = "TAK"; }
				
				if (strpos(trim($line),"STEP_4#INFO#Komputer")>=1) { $_t = explode("#",$line); $steps[39] = "TAK"; }
			
			}
				
			if (strpos(trim($line),"STEP_1#INFO#Rozmiar pliku#")>=1) { $_t = explode("#",$line); $steps[37] = $_t[4]; }
		
			if ($steps[7]=="TAK") 
				if (strpos(trim($line),"STEP_13#STOP#Procedura wylaczenia komputera")>=1) { $_t = explode("#",$line); $steps[40] = $_t[4]; }

			if (strpos(trim($line),"STEP_0#INFO_WERSJA_SKR")>=1) {	$_t = explode("#",$line); $steps[41] = $_t[3]; }
			if (strpos(trim($line),"STEP_0#INFO_DATA_SKR")>=1) {	$_t = explode("#",$line); $steps[42] = $_t[3]; }

			
			if (strpos(trim($line),"AKONCZENIE PROCESU WYKONYWANIA")>=1) { $_t = explode("#",$line); $steps[43] = $_t[1]; }
			
			
			if (strpos(trim($line),"STEP_4#ERROR_COPY")>=1) { $_t = explode("#",$line); $steps[44] = "TAK"; }
			if (strpos(trim($line),"STEP_4#SYN_COPY")>=1) { $_t = explode("#",$line); $steps[45] = "TAK"; }

			if (strpos(trim($line),"STEP_0a#ILOSCKOPII_NA_STACJI_ROBOCZEJ")>=1) { $_t = explode("#",$line);	$steps[46] = trim($_t[3]);	}
			if (strpos(trim($line),"STEP_0#AUTO_MAC_ADDRESS")>=1) { $_t = explode("#",$line); $steps[47] = $_t[3]; }
			
			if (strpos(trim($line),"BACKUPTYPE#FULL#")>=1) { $steps[48] = 'F'; }
			if (strpos(trim($line),"BACKUPTYPE#DIFF#")>=1) { $steps[48] = 'D'; }
			
		}
		
		if ($steps[41]=='') $steps[41]='-';
		
		$_day .= "<a class='normalfont title' title='Rozmiar archiwum: ".formatBytes_new($steps[37])."|";
		
		if ($steps[8]=="TAK") { 
			//$_day .= "<br />";
			if ($steps[38]=="NIE") {
				$_day .= "Komputer docelowy ".$steps[5]." nie jest dostępny w sieci<br />Podjęcie próby wzbudzenia komputera ".$steps[5]."<br /> ";
				$WOL_start_check = false;
			} else {
				$_day .= "Komputer docelowy ".$steps[5]." jest dostępny w sieci<br />";
				$WOL_start_check = true;
			}
			$_day .= "<br />";
		}
				
		$_day .= "1.Rozpoczęcie backupu: ".$steps[15]."<br />";
		$_day .= "2.Zakończenie backupu: ";
		if ($steps[17]=="TAK") { $_day .= "<font color=green>"; } else { $_day .= "<font color=red>"; }
		$_day .= $steps[16];
		$_day .= "</font><br />";
		
		$_day .= "3.Rozpoczęcie weryfikacji backupu: ".$steps[18]."<br />";
		$_day .= "4.Zakończenie weryfikacji backupu: ";
		if ($steps[20]=="TAK") { $_day .= "<font color=green>"; } else { $_day .= "<font color=red>"; }
		$_day .= $steps[19];
		$_day .= "</font><br />";		
		
		$_day .= "5.Rozpoczęcie generowania sumy kontrolnej backupu: ".$steps[21]."<br />";
		$_day .= "6.Zakończenie generowania sumy kontrolnej backupu: ";
		if ($steps[23]=="TAK") { $_day .= "<font color=green>"; } else { $_day .= "<font color=red>"; }
		$_day .= $steps[22];
		$_day .= "</font><br />";
		
		if ($steps[8]=="TAK") { 
			$_day .= "<br />";
			if ($steps[39]!="TAK") {
				$_day .= "Komputer docelowy ".$steps[5]." nie jest dostępny w sieci<br />Podjęcie próby wzbudzenia komputera ".$steps[5]."<br /> ";
				$not_available_count++;
				$WOL_ready_check = false;
			} else {
				$_day .= "Komputer docelowy ".$steps[5]." jest dostępny w sieci<br />";
				$WOL_ready_check = true;
			}
			$_day .= "<br />";
		}
		
		$_day .= "7.Rozpoczęcie kopiowania na komputer ".$steps[5].": ";
		if ($steps[24]=="") {
			$_day .= "<font color=red>problem z kopiowaniem</font>";
		} else {
			if ($steps[26]=="TAK") { $_day .= "<font>"; } else { $_day .= "<font color=red>"; }
			$_day .= $steps[24];
		}
		$_day .= "</font><br />";
		$_day .= "8.Zakończenie kopiowania backupu na komputer ".$steps[5].": ";
		if ($steps[26]=="TAK") { $_day .= "<font color=green>"; } else { $_day .= "<font color=red>"; }
		$_day .= $steps[25];
		if ($steps[25]=="") $_day .= "problem z kopiowaniem";
		$_day .= "</font><br />";	
		
		$_day .= "9.Rozpoczęcie weryfikacji sumy kontrolnej na komputerze: ".$steps[5].": ".$steps[27]."";
		if ($steps[29]=="NIE") $_day .= "<font color=red>problem z weryfikacją</font>";
		$_day .= "<br />";
		$_day .= "10.Zakończenie weryfikacji sumy kontrolnej na komputerze: ".$steps[5].": ";
		if ($steps[29]=="TAK") { $_day .= "<font color=green>"; } else { $_day .= "<font color=red>"; }
		$_day .= $steps[28];
		if ($steps[29]=="NIE") $_day .= "problem z weyfikacją";
		$_day .= "</font><br />";	
		
		if ($steps[11]=='TAK') {
			$_day .= "11.Rozpoczęcie wysyłki logów na FTPa: ".$steps[32]."";
			if ($steps[32]=="") $_day .= "<font color=red>problem z wysyłką na FTPa</font>";
			$_day .= "<br />";
			$_day .= "12.Zakończenie wysyłki logów na FTPa: ";
			if ($steps[33]!="") { $_day .= "<font color=green>"; } else { $_day .= "<font color=red>"; }
			$_day .= $steps[33];
			if ($steps[33]=="") $_day .= "problem z wysyłką na FTPa";
			$_day .= "</font><br />";	
		} else {
			$_day .= "<font color=red>11.Wysyłka logów na FTPa jest wyłączona w konfiguracji skryptu</font>";
		}
		
		
		if ($steps[7]=="TAK") {
			$_day .= "<br />";
			$_day .= "Podjęcie próby wyłączenia komputera ".$steps[5]."";
			//$_day .= "<br />";
		}
		
		$_day .= "<br />Na serwerze są przechowywane kopie z <b>".$steps[1]."</b> dni.";
		if ($steps[46]=='') $steps[46]=$steps[1];
		$_day .= "<br />Na stacji roboczej są przechowywane kopie z <b>".$steps[46]."</b> dni.<br />";
		
		if ($steps[43]=='') $steps[43]=date("Y-m-d H:i:s");
		$_day .= "<br />Łączny czas działania skryptu: <b>".CalcDiff(strtotime(date($steps[43])),strtotime(date($steps[0])))."</b><br />";
		
		if ($steps[41]=='') $steps[41]='-';
		if ($steps[42]=='') $steps[42]='-';
		
		if (($steps[41]!='-') && ($steps[42]!='-')) $_day .= "<br /><i>Wersja skryptu: ".$steps[41]." z ".$steps[42]."</i>";
		
		$_day .= "' onClick=\"newWindow_r(800,600,'$path_to_kopie/".$es_skrot1."/".$plik['name']."'); return false;\"";
		$_day .= ">";
		if ($steps_ok_count==6) { $_day .= "<font color=green>"; } else { $_day .= "<font color=red>"; $count_problems++; }
		
		if ($plik['skrot']=='F') $_day .= "<b>";
		
				if ($steps[44]=="TAK") $_day .= "~";
				if ($steps[45]=="TAK") $_day .= "=";
				$_day .= $plik['skrot'];
		
		if ($plik['skrot']=='F') {
			$_day .= "</b>";
			$FULL_size = $steps[37];
		}
		
		$_day .= "</font>";
		
		$_day .= "</a>&nbsp;";
		
		if (($WOL_start_check == 0) && ($WOL_ready_check == 1)) $WON_count_ok++;
		
	}
	//echo $_day;
	
	
	//echo "<br /><br />";
	//var_dump($steps);
	
	
	closedir($handle);
} else {
	echo "Na serwerze WWW nie ma katalogu $sciezka_do_logow_z_kopiami";
}
		
//if ($temp_id==51) { 
/*		?>
		<script>
			var ref<?php echo $temp_id; ?> = setInterval(function() { 
				$('#r_<?php echo $temp_id; ?>').load('check_ping.php?typ=R&router=<?php echo $temp_ip.".1"; ?>&randval='+Math.random()+'');
				$('#s_<?php echo $temp_id; ?>').load('check_ping.php?typ=S&serwer=<?php echo $temp_ip.".".$temp_ipserwera; ?>&randval='+Math.random()+'');
			}, 5000);	
		</script>
		<?php
*/
//	}
		echo "<td>$_day";
		
		if ((Date('Y-m-d')!=$first_date) && ($t!=0)) {
			echo "<br /><font color=red>".Date('Y-m-d').": <b>brak kopii z dnia dzisiejszego</b></font>";
			$count_problems++;
		} 
		
		if ($t==0) echo "<font color=red>skrypt do wykonywania kopii nie skonfigurowany lub skonfigurowany błędnie</font>";
		
		echo "</td>";
		
		td_(";r");
			$_f1 = explode(" ",$steps[31]);
			$_f2 = explode(" ",formatBytes_new($steps[30]));
			
			$_f1_prefix = '';
			$_f1_suffix = '';
			$_f2_prefix = '';
			$_f2_suffix = '';
			
			if ((int)($_f1[0]) < 5 ) { $_f1_prefix = "<font color=red>"; $_f1_suffix = "</font>"; }
			if (($_free_space_on_remote_computer) < 5368709120 ) { $_f2_prefix = "<font color=red>"; $_f2_suffix = "</font>"; $count_problems++; }
		
			echo "".$_f1_prefix."<b>".$steps[31]."</b>".$_f1_suffix."";
			echo "<br />";
			if ($_free_space_on_remote_computer!=0) echo "".$_f2_prefix."<b>".formatBytes_new($_free_space_on_remote_computer)."</b>".$_f2_suffix."";			
			
		_td();
		
		td_(";r");
			$needed_space = 0;
			
			if ($steps[1]==$steps[46]) echo "<font color=grey>";
			echo $steps[1];
			if ($steps[1]==$steps[46]) echo "</font>";
			//echo "#".($FULL_size)."#";
			// 1073741824

			if ((substr(formatBytes_new($FULL_size*($steps[1]+1)),-2))=='MB') { $needed_space = formatBytes_new(1073741824*($steps[1]+2)); } else { $needed_space = formatBytes_new($FULL_size*($steps[1]+2)); }
			
			$_free_space = explode(" ",$steps[31]);
			$_remote_free_space = explode(" ",formatBytes_new($_free_space_on_remote_computer));
			
			$_needed_space = explode(" ",$needed_space);
			if ($steps[46]=='') $steps[46]=$steps[1];
			
			//echo "|".$_free_space[0]."|".$_needed_space[0]."";
			if (($_needed_space[0]<$_free_space[0]) && ($steps[1]<8)) {
				echo "&nbsp;<a class='normalfont title' title='Ilość wolnego miejsca na serwerze umożliwia pozostawianie kopii z większej ilości dni wstecz'><b><font color=green>!</font></b></a>";
			}
			echo "<br />";
			
			if ($steps[1]==$steps[46]) echo "<font color=grey>";
			echo $steps[46];
			if ($steps[1]==$steps[46]) echo "</font>";
			if (($_needed_space[0]<$_remote_free_space[0]) && ($steps[46]<4)) {
				echo "&nbsp;<a class='normalfont title' title='Ilość wolnego miejsca na stacji roboczej umożliwia pozostawianie kopii z większej ilości dni wstecz'><b><font color=green>!</font></b></a>";
			}			
			$_free_space_on_remote_computer=0;
		_td();
		
		echo "<td>";		
		//.", ".$_f2[0]."";
		
		if (($steps[3]!='') && ($steps[4]!='')) {
			//echo "\\\\".$steps[3]."\\".str_replace(":","$",$steps[4])." (Free: ".$_f1_prefix."<b>".$steps[31]."</b>".$_f1_suffix.")";
			echo "\\\\".$steps[3]."\\".str_replace(":","$",$steps[4])."";
		} else echo "-";
		
		echo "<br />";
		
		if (($steps[5]!='') && ($steps[6]!='')) {
			//echo "\\\\".$steps[5]."\\".$steps[6]." (Free: ".$_f2_prefix."<b>".formatBytes_new($_free_space_on_remote_computer)."</b>".$_f2_suffix.")";
			echo "\\\\".$steps[5]."\\".$steps[6]."";
		}
		
		echo "</td>";				
		


		td_(";c");
			if ($t!=0) {
				if ($sp2000_backup_script_version<>$steps[41]) { echo "<font color=red><a title='Wersja skryptu jest nieaktualna. Sprawdź czy zadanie aktualizacji skryptu jest poprawnie skonfigurowane.'>"; $count_problems++; } else { echo "<font color=green>"; }
				echo "<b>".$steps[41]."</b>";
				if ($sp2000_backup_script_version<>$steps[41]) { echo "</a>"; }
				echo "</font>";
			} else echo "-";
		_td();
				
		td_(";c");
			if ($t!=0) {

				if ($steps[8]=='TAK') {
					if ($WON_count_ok>0) echo "<b><font color=green>";
					if ($WON_count_ok==0) echo "<font color=black>";
				} else  echo "<font color=red>";
				echo $steps[8];
				
				if (toUpper($steps[47])!=toUpper($steps[9])) {
					if (toUpper($steps[47])!='') {
						echo "<br /><a class='normalfont title' title='Błędy w skrypcie konfiguracyjnym|Niezgodność wpisanego w konfiguracji MAC adresu, z automatycznie wykrytym przez skrypt<br />Wpisany: <b><font color=red>".toUpper($steps[9])."</font></b><br />Wykryty: <b><font color=green>".toUpper($steps[47])."</font></b>'><b><font color=red>!</font></b></a>";
					}
				}

			
				if ($steps[8]=='TAK') echo "</font></b>";
				if ($WON_count_ok>0) {
					echo "<br />(działa)";
				}
			} else echo "-";
			//echo "(".$not_available_count.")";
		_td();
		
		td_(";c");
		
			echo "<a title=' Edycja danych o $temp_nazwa '><input class=imgoption type=image src=img/edit.gif onclick=\"newWindow_r(800,600,'e_komorka.php?select_id=$temp_id'); return false;\"></a>";

		//	echo $count_problems;
		_td();
		?>
		
		<?php 
			if ($_REQUEST[view]=='error') {
				if ($count_problems==0) { 
			?>
			<script>
				document.getElementById('<?php echo $i; ?>').style.display='none';
			</script>
		<?php 
				$h++;
				$j--;
				}
			}
		?>
		
		<?php
		ob_flush();	flush();
		$i++;
		$x++;
		
	_tr();
	
}
endtable();
echo "<h3 id=h_3 style='display:none; padding:10px; font-weight:normal;'>Kopie z wszystkich zdefiniowanych w bazie komórek są aktualne</h3>";
if ($h==$i) { ?>
	<script>
		document.getElementById('h0').style.display='none'; 
		document.getElementById('h0a').style.display='none'; 
		document.getElementById('h_3').style.display='';
	</script>
<?php 
	}
//echo "i=".$i.", h=".$h."";

//include_once('paging_end.php');
} else { 
	errorheader("W bazie komórek nie ma pozycji ze zdefiniowanym plikiem backupu");
}

//listabaz($es_prawa,"".$pokaz_ikony."");
/*echo "<span style='float:left; margin-left:5px;'>";addbuttons("wstecz");echo "</span>";

if ($count_rows>0) {
	startbuttonsarea("right");
	echo "<form action=do_xls_htmlexcel_slownik_komorki.php METHOD=POST target=_blank>";		
		echo "<input type=hidden name=nr value='$es_nr'>";
		addownsubmitbutton("'Export do XLS'","refresh_");
	echo "</form>";
	endbuttonsarea();
}
*/
ob_flush(); flush();

startbuttonsarea("left");
echo "Oznaczenie symboli:<br />&nbsp;&nbsp;&nbsp;<b>F</b>/<b>D</b> - kopia pełna/różnicowa<br />&nbsp;&nbsp;&nbsp;<b><font color=green>F</font></b>/<b><font color=green>D</font></b> - kopia pełna/różnicowa, która wykonała się poprawnie<br />&nbsp;&nbsp;&nbsp;<b><font color=red>F</font></b>/<b><font color=red>D</font></b> - kopia pełna/różnicowa, podczas wykonania której wystąpiły problemy<br />&nbsp;&nbsp;&nbsp;<b><font color=red>~F</b></font>/<b><font color=red>~D</font></b> - kopia pełna/różnicowa, której nie udało się skopiować na komputer zapasowy<br />&nbsp;&nbsp;&nbsp;<b><font color=green>=F</font></b>/<b><font color=green>=D</font></b> - kopia pełna/różnicowa, podczas wykonywania której nastąpiła synchronizacja kopii/logów z komputerem zapasowym";

endbuttonsarea();

startbuttonsarea("right");
echo "<span style='float:left;'>";
//if ($_REQUEST[view]=='') addlinkbutton("'Pokaż komórki z nieaktualnymi kopiami na D-1'","hd_check_backups.php?view=error");
//if ($_REQUEST[view]=='error') addlinkbutton("'Pokaż wykonanie kopii we wszystkich komórkach'","hd_check_backups.php?view=");
echo "</span>";
echo "<br /><i>Ostrzeżenia o kończącym się wolnym miejscu na dyskach ustawione jest dla <b>5GB</b>&nbsp;</i><br /><br />";
if ($currentuser==$adminname) {
	
	echo "<div style='float:left'>";
	if ($_REQUEST[allfilie]=='') {
		echo "<input type=button class=buttons onClick=\"location.href='hd_check_backups_new.php?view=$_REQUEST[view]&allfilie=TAK';\" value='Pokaż informacje ze wszystkich filii'>";
	} else 
		echo "<input type=button class=buttons onClick=\"location.href='hd_check_backups_new.php?view=$_REQUEST[view]&allfilie=';\" value='Pokaż informacje z mojej filii'>";
	echo "</div>";
}

echo "<input type=button class=buttons onClick=\"location.href='hd_check_backups_new.php?view=error&allfilie=$_REQUEST[allfilie]';\" value='Pokaż tylko komórki z problemami'>";
echo "<input type=button class=buttons onClick=\"location.href='hd_check_backups_new.php?view=&allfilie=$_REQUEST[allfilie]';\" value='Pokaż wszystkie komórki'>";
addbuttons("start");
endbuttonsarea();
echo "<br />";
echo "<br />";
//echo "<input type=button class=buttons onClick=\"document.getElementById('1').style.display='none';\" value='123'>";
include('body_stop.php');
//include('js/menu.js');
?>
<script type="text/javascript" src="js/jquery/jquery-1.6.min.js"></script>
<script type="text/javascript" src="js/jquery/jquery.cluetip_min_backup.js"></script>
<script>
$(document).ready(function() { $('a.title').cluetip({splitTitle: '|', closePosition: 'title', width: '500'}); });

document.getElementById('_h3').style.display='';
$(document).ready(function() { $('a.title').cluetip({splitTitle: '|'}); });
</script>
<?php if ($es_mminhd==1) {  ?>
<script type="text/javascript">
//<![CDATA[

var listMenu = new FSMenu('listMenu', true, 'display', 'block', 'none');
listMenu.showDelay = 100;
listMenu.switchDelay = 125;
listMenu.hideDelay = 300;
listMenu.cssLitClass = 'highlighted';
//listMenu.showOnClick = 1;
listMenu.animInSpeed = 0.5;
listMenu.animOutSpeed = 0.5;

function animClipDown(ref, counter)
{
var cP = Math.pow(Math.sin(Math.PI*counter/200),0.75);
ref.style.clip = (counter==100 ? (window.opera ? '': 'rect(auto, auto, auto, auto)') :
'rect(0, ' + ref.offsetWidth + 'px, '+(ref.offsetHeight*cP)+'px, 0)');
};

function animFade(ref, counter)
{
var f = ref.filters, done = (counter==100);
if (f)
{
if (!done && ref.style.filter.indexOf("alpha") == -1)
ref.style.filter += ' alpha(opacity=' + counter + ')';
else if (f.length && f.alpha) with (f.alpha)
{
if (done) enabled = false;
else { opacity = counter; enabled=true }
}
}
else ref.style.opacity = ref.style.MozOpacity = counter/100.1;
};

// I'm applying them both to this menu and setting the speed to 20%. Delete this to disable.
			//listMenu.animations[listMenu.animations.length] = animFade;
			//listMenu.animations[listMenu.animations.length] = animClipDown;
			//listMenu.animations[listMenu.animations.length] = FSMenu.animFade;
			//listMenu.animations[listMenu.animations.length] = FSMenu.animSwipeDown;

			//listMenu.animSpeed = 500;

			var arrow = null;
			if (document.createElement && document.documentElement)
			{
				arrow = document.createElement('img');
				arrow.src = 'img/menu.gif';
				arrow.style.borderWidth = '0';
				arrow.className = 'subind';
				arrow.width = '16';
				arrow.height = '16';
			}

			addEvent(window, 'load', new Function('listMenu.activateMenu("listMenuRoot", arrow)'));

			//]]>
</script>
<?php } ?>
<script>HideWaitingMessage();</script>
</body>
</html>