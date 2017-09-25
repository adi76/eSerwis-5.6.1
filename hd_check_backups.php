<?php include_once('header.php'); ?>
<body>
<script>
createCookie('break_loop','');
</script>
<?php include('body_start.php'); 

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
if ($es_m==1) { } else $sql=$sql."(serwis_komorki.belongs_to=$es_filia) and  ";
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
if ($es_m==1) { } else $sql=$sql."(serwis_komorki.belongs_to=$es_filia) and  ";
$sql.=" (serwis_komorki.up_pion_id=serwis_piony.pion_id) ";
$sql.="AND (serwis_komorki.up_backupname<>'') and (serwis_komorki.up_active=1) ";
$sql.=" ORDER BY serwis_piony.pion_nazwa,serwis_komorki.up_nazwa ASC "; //LIMIT $limitvalue, $rps";

//echo $sql;

$result = mysql_query($sql, $conn) or die($k_b);
$count_rows = mysql_num_rows($result);

if ($count_rows!=0) {
	pageheader("Sprawdzenie wykonania kopii z poszczególnych lokalizacji",1,0);
	if ($_REQUEST[view]=='error') infoheader('Lista komórek z nieaktualnymi kopiami na D-1');
	
	ob_flush(); flush();
	
	?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
	
	starttable();
	//th("30;c;LP|;;Nazwa komórki<br /><sub>Adres<br />Opis</sub>|;c;Podsieć|;c;Nr WAN-portu|;;Telefon|;c;Test łączności|;c;Opcje",$es_prawa);
	echo "<tr id=h0><th width=30 class=center rowspan=2>LP</th><th class=left rowspan=2>Nazwa komórki</th><th class=left rowspan=2>Telefon</th><th class=center colspan=4>Informacje o kopiach</th><th class=center rowspan=2>Opcje</th></tr>";
	echo "<tr id=h0a><th class=center>Nazwa pliku</th><th class=center>Data</th><th class=center>Status</th><th class=center>Rozmiar</th></tr>";
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
		
		tbl_tr_highlight($i);
		$result1 = mysql_query("SELECT filia_nazwa FROM $dbname.serwis_filie WHERE filia_id=$temp_belongs_to", $conn) or die($k_b);
		list($temp_filia_nazwa)=mysql_fetch_array($result1);
			echo "<td class=center rowspan=2>";
			if ($temp_active_status=='2') echo "<font color=red>";
			if ($temp_active_status=='0') echo "<strike>";
			echo $j;
			//echo "-".$_COOKIE['break_loop'];
			if ($temp_active_status=='0') echo "</strike>";
			if ($temp_active_status=='2') echo "</font>";
			echo "</td>";
			$j++;
			
			list($pionnazwa)=mysql_fetch_array(mysql_query("SELECT pion_nazwa FROM $dbname.serwis_piony WHERE pion_id=$temp_pion_id LIMIT 1", $conn));

		echo "<td rowspan=2>";
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

		echo "<td rowspan=2>";
			echo $temp_telefon;
		_td();

		$cnt_backups = 0;
		
		//ob_flush(); flush();
		
		$steps = array();
		$steps2 = array();
			
if ($handle = opendir(realpath($sciezka_do_logow_z_kopiami."/".$es_skrot.""))) {
	$steps[10]='pomin';
	while (false !== ($file = readdir($handle))) {
	//	echo "==>".toUpper("".$temp_backupname).".LOG<br />$file";
		if (($file != "." && $file != "..") && (((toUpper($temp_backupname).".LOG")==toUpper($file)))) {		
		//echo ">>>>>>>".$file;
			
			if ((toUpper($temp_backupname.".LOG")==toUpper($file))) {
				//echo $file.":<br />";
				$fs = fopen(realpath($sciezka_do_logow_z_kopiami."/".$es_skrot."")."\\".$file."","r");
				//var_dump($fs);
				
				$lines = array();
				while (!feof($fs)) {
				//	echo fgets($fs)."<br />";
					$x = trim(fgets($fs));
					
					if ((strlen($x))>0) $lines[]=$x;
				}
				
				
				// Ssteps:
				//		0 - SQL stop
				//		1 - start backup
				// 		2 - is ok
				//		3 - compressed file size
				//		4 - stop backup
				// 		5 - SQL start
				// 		6 - nazwa hosta gdzie wykonywana jest kopia
				//		7 - lokalizacja kopii na hoście
				//		8 - Kopia z dnia
				
				$steps[0]='';
				$steps[1]='';
				$steps[2] = "ERROR";
				$steps[3]='';
				$steps[4]='';
				$steps[5]='';
				$steps[6]='';
				$steps[7]='';
				$steps[8]='';
				$steps[9]=$file;
				$steps[10]='ok';
			//	echo ">".$steps[9]."<<<";
				
				foreach ($lines as $line) {
					
					if (strpos($line,"1#")==1) {
						$_t = explode("#",$line);
						$steps[0] = substr($_t[3],0,16);
					}
					if (strpos($line,"2#")==1) {
						$_t = explode("#",$line);
						$steps[1] = substr($_t[3],0,16);
					}
					
					if (strpos($line,"ynik wykonania")==1) {
						$_t = explode(":",$line);
						$steps[2] = $_t[2];
						$steps[8] = $_t[1];
					}
					
					if (strpos($line,"ompressed")==1) {
						$_t = explode(":",$line);
						$steps[3] = $_t[1];
					}
					
					if (strpos($line,"3#")==1) {
						$_t = explode("#",$line);
						$steps[4] = substr($_t[3],0,16);
					}
					if (strpos($line,"4#")==1) {
						$_t = explode("#",$line);
						$steps[5] = substr($_t[3],0,16);
					}
					
					if (strpos(trim($line),"azwa hosta")==1) {
						$_t = explode(": ",$line);
						$steps[6] = $_t[1];
					}
					
					if (strpos($line,"rocessing archive")==1) {
						$_t = explode(": ",$line);
						$steps[7] = $_t[1];
					}
					 
					//echo $line."<br />";
				}
				$cnt_backups++;
			}
		}
	}
	closedir($handle);
} else {
	echo "Na serwerze WWW nie ma katalogu $sciezka_do_logow_z_kopiami";
}

if ($handle = opendir(realpath($sciezka_do_logow_z_kopiami."/".$es_skrot.""))) {
	$steps2[10]='pomin';
	while (false !== ($file2 = readdir($handle))) {
	
		if (($file2 != "." && $file2 != "..") && (((toUpper("OLD_".$temp_backupname.".LOG")==toUpper($file2))))) {		
			//echo "($file2)";
					
			$fs = fopen(realpath($sciezka_do_logow_z_kopiami."/".$es_skrot."")."\\".$file2."","r");
				//var_dump($fs);
				
				$lines = array();
				while (!feof($fs)) {
				//	echo fgets($fs)."<br />";
					$x = trim(fgets($fs));
					
					if ((strlen($x))>0) $lines[]=$x;
				}
				
				//$steps2 = array();
				// Ssteps:
				//		0 - SQL stop
				//		1 - start backup
				// 		2 - is ok
				//		3 - compressed file2 size
				//		4 - stop backup
				// 		5 - SQL start
				// 		6 - nazwa hosta gdzie wykonywana jest kopia
				//		7 - lokalizacja kopii na hoście
				//		8 - Kopia z dnia
				
				$steps2[0]='';
				$steps2[1]='';
				$steps2[2] = "ERROR";
				$steps2[3]='';
				$steps2[4]='';
				$steps2[5]='';
				$steps2[6]='';
				$steps2[7]='';
				$steps2[8]='';
				$steps2[9]=$file2;
				$steps2[10]='ok';
				
			//	echo ">".$steps2[9]."<<<";
				
				foreach ($lines as $line) {
					
					if (strpos($line,"1#")==1) {
						$_t = explode("#",$line);
						$steps2[0] = substr($_t[3],0,16);
					}
					if (strpos($line,"2#")==1) {
						$_t = explode("#",$line);
						$steps2[1] = substr($_t[3],0,16);
					}
					
					if (strpos($line,"ynik wykonania")==1) {
						$_t = explode(":",$line);
						$steps2[2] = $_t[2];
						$steps2[8] = $_t[1];
					}
					
					if (strpos($line,"ompressed")==1) {
						$_t = explode(":",$line);
						$steps2[3] = $_t[1];
					}
					
					if (strpos($line,"3#")==1) {
						$_t = explode("#",$line);
						$steps2[4] = substr($_t[3],0,16);
					}
					if (strpos($line,"4#")==1) {
						$_t = explode("#",$line);
						$steps2[5] = substr($_t[3],0,16);
					}
					
					if (strpos(trim($line),"azwa hosta")==1) {
						$_t = explode(": ",$line);
						$steps2[6] = $_t[1];
					}
					
					if (strpos($line,"rocessing archive")==1) {
						$_t = explode(": ",$line);
						$steps2[7] = $_t[1];
					}
					 
					//echo $line."<br />";
				}			
				$cnt_backups++;
			} 

		//	echo "----->".$steps[9];
		//	echo "----->".$steps2[9];

		}
		closedir($handle);
} else {
	echo "Na serwerze WWW nie ma katalogu $sciezka_do_logow_z_kopiami";
}

				td_(";c;");
					//echo "($steps[9]) ($steps[10])<br />";
					//echo "($steps2[9]) ($steps2[10])<br />";
					
					//$old_name = str_replace($steps2[7],"old_",strpos($steps2[7],"\Kopia"),strlen($steps2[7]));
					//$old_name = str_replace($steps2[7],"old_",10,0);
					$old_name1 = substr($steps[7],0,strrpos($steps[7],'\\'));
					$old_name2 = substr($steps2[7],0,strrpos($steps2[7],'\\'));
					
					if ($cnt_backups>0) {
						if (($steps[9]!='') && ($steps[10]=='ok')) { echo "<a title='&nbsp;Kopia za dzień: ".substr($steps[0],0,10)." - status: ".$steps[2]."|&nbsp;1. Zatrzymanie SQLa: <b>".$steps[0]."</b><br />&nbsp;2. Rozpoczęcie wykonywania kopii: <b>".$steps[1]."</b><br />&nbsp;3. Zakończenie wykonywania kopii: <b>".$steps[4]."</b><br />&nbsp;4. Uruchomienie SQLa: <b>".$steps[5]."</b><br />&nbsp;5. Nazwa komputera gdzie zlokalizowana jest kopia: <b>".$steps[6]."</b><br />&nbsp;6. Lokalizacja pliku kopii: <b>".$old_name1."</b>' class='normalfont title' href=# onClick=\"newWindow_r(800,600,'$path_to_kopie/".$es_skrot."/".substr($steps[9],0,-4).".log'); return false;\">".substr($steps[9],0,-4).".exe"."</a><br />"; } else { echo "<br />"; }
						if (($steps2[9]!='') && ($steps2[10]=='ok')) { echo "<a title='&nbsp;Kopia za dzień: ".substr($steps2[0],0,10)." - status: ".$steps2[2]."|&nbsp;1. Zatrzymanie SQLa: <b>".$steps2[0]."</b><br />&nbsp;2. Rozpoczęcie wykonywania kopii: <b>".$steps2[1]."</b><br />&nbsp;3. Zakończenie wykonywania kopii: <b>".$steps2[4]."</b><br />&nbsp;4. Uruchomienie SQLa: <b>".$steps2[5]."</b><br />&nbsp;5. Nazwa komputera gdzie zlokalizowana jest kopia: <b>".$steps2[6]."</b><br />&nbsp;6. Lokalizacja pliku kopii: <b>".$old_name2."</b>' class='normalfont title' href=# onClick=\"newWindow_r(800,600,'$path_to_kopie/".$es_skrot."/".substr($steps2[9],0,-4).".log'); return false;\">".substr($steps2[9],0,-4).".exe"."</a>"; } else { echo "<br />"; }
					} else {
						echo "<font color=red>Brak pliku log: <b>$temp_backupname</b></font>";
					}
				_td();		
				
				$r2 = mysql_query("SELECT up_working_time,up_working_time_alternative,up_working_time_alternative_start_date,up_working_time_alternative_stop_date FROM $dbname.serwis_komorki WHERE (up_id='$temp_id') LIMIT 1", $conn) or die($k_b);
				list($wt1,$wta1,$wtastart,$wtastop)=mysql_fetch_array($r2);
				if ((substr($wtastart,5,5)!='00-00') && (substr($wtastop,5,5)!='00-00')) {
					if ((date("Y-m-d")>=$wtastart) && (date("Y-m-d")<=$wtastop)) {
						$wt1 = $wta1;
					}
				}
				
				td_(";c;");
					$ok_d_1 = 0;
					$ok_d_2 = 0;
					//echo "|".$ok_1." - ".$ok_2."|";
					if ($steps[9]=='') $ok_d_1 = 1;
					if ($steps2[9]=='') $ok_d_2 = 1;
					//echo ">$steps[0]< |".$ok_d_1." - ".$ok_d_2."|";
					if ($cnt_backups>0) {
						if (($steps[9]!='') && ($steps[10]=='ok')) { 
							echo substr($steps[0],0,10);
							echo " | <b>";
							$d = z_jakiego_dnia(substr($steps[0],0,10), $wt1);
							if ($d=='') $d=0;
							if (($d<=2) && ($d>=0)) { echo "<font color=green>"; $ok_d_1=1; }
							if (($d>2) && ($d<=6)) { echo "<font color=#BC4306>"; $ok_d_1=0; }
							if (($d>6) || ($d<0)) echo "<font color=red>";
							if ($d<0) $d='?'; 
							echo "&nbsp;D-$d";
							echo "</font></b>";
							
							echo "<br />"; 
						} else { 
							echo "<br />"; 
						}
						
						if (($steps2[9]!='') && ($steps2[10]=='ok')) { 
							echo substr($steps2[0],0,10); 
							echo " | <b>";
							$d = z_jakiego_dnia(substr($steps2[0],0,10), $wt1);
							if ($d=='') $d=0;
							if (($d<=2) && ($d>=0)) { echo "<font color=green>"; $ok_d_2=1;}
							if (($d>2) && ($d<=6)) { echo "<font color=#BC4306>"; $ok_d_2=0; }
							if (($d>6) || ($d<0)) echo "<font color=red>";
							if ($d<0) $d='?'; 
							echo "&nbsp;D-$d";
							echo "</font></b>";

							} else { 
							echo "<br />"; 
						}
						
					} else {
						echo "-";
					}
				_td();
				
				td_(";c;");
					if ($cnt_backups>0) {
						$steps[2]=trim($steps[2]);
						$steps2[2]=trim($steps2[2]);
						if (($steps[9]!='') && ($steps[10]=='ok')) { 						
							if ($steps[2]=='OK') { echo "<b><font color=green>"; } else { echo "<b><font color=red>"; }
							echo $steps[2];
							echo "<br />"; 
						} else { echo "<br />"; }
						if (($steps2[9]!='') && ($steps2[10]=='ok')) { 
							if ($steps2[2]=='OK') { echo "<b><font color=green>"; } else { echo "<b><font color=red>"; }
							echo $steps2[2]; 
						} else { echo "<br />"; }
					} else {
						echo "-";
					}						
				_td();			
				td_(";c;");
					if ($cnt_backups>0) {				
						if (($steps[9]!='') && ($steps[10]=='ok')) { 
							if ($steps[3]<10000000) echo "<b><a href=# class=normalfont title='Mały rozmiar kopii może wskazywać na problem z wykonywaniem kopii'><font color=red>!!!&nbsp;";
							echo formatBytes($steps[3]);
							if ($steps[3]<10000000) echo "&nbsp;!!!</font></a></b>";
							echo "<br />"; 
						} else { 
							echo "<br />"; 
						}
						if (($steps2[9]!='') && ($steps2[10]=='ok')) { 
							if ($steps2[3]<10000000) echo "<b><a href=# class=normalfont title='Mały rozmiar kopii może wskazywać na problem z wykonywaniem kopii'><font color=red>!!!&nbsp;";
							echo formatBytes($steps2[3]);
							if ($steps2[3]<10000000) echo "&nbsp;!!!</font></a></b>";
						} else { 
							echo "<br />"; 
						}
					} else {
						echo "-";
					}						
				_td();			
			
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
		td_(";c");
		
			if ($temp_active_status=='2') 		
				if ($gp_sa==1) echo "<a class='normalfont title' href=# title='$opis_stanow'><input class=imgoption type=image src=img/godziny_pracy.gif></a>";

			if ($temp_active_status!='2') 		
				if ($gp_sa==1) echo "<a class='normalfont title' href=# title='$opis_stanow' onclick=\"newWindow_r(800,600,'e_komorka.php?select_id=$temp_id'); return false;\"><input class=imgoption type=image src=img/godziny_pracy.gif></a>";

			echo "<a title=' Edycja danych o $temp_nazwa '><input class=imgoption type=image src=img/edit.gif onclick=\"newWindow_r(800,600,'e_komorka.php?select_id=$temp_id'); return false;\"></a>";

			
		_td();
		?>
		
		<?php 
			if ($_REQUEST[view]=='error') {
				if (($ok_d_1==1) && (($steps[0]!='') || ($steps2[0]!=''))) { 
			?>
			<script>
				document.getElementById('<?php echo $i; ?>').style.display='none'; 
			</script>
		<?php 
				$h++;
				}
			}
		?>
		
		<?php
		ob_flush();	flush();
		$i++;
		$x++;
	_tr();
	tr_();
		
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

startbuttonsarea("right");
echo "<span style='float:left;'>";
if ($_REQUEST[view]=='') addlinkbutton("'Pokaż komórki z nieaktualnymi kopiami na D-1'","hd_check_backups.php?view=error");
if ($_REQUEST[view]=='error') addlinkbutton("'Pokaż wykonanie kopii we wszystkich komórkach'","hd_check_backups.php?view=");
echo "</span>";
addbuttons("zamknij");
endbuttonsarea();
echo "<br />";
echo "<br />";

include('body_stop.php');
//include('js/menu.js');
?>
<script type="text/javascript" src="js/jquery/jquery-1.6.min.js"></script>
<script type="text/javascript" src="js/jquery/jquery.cluetip_min_backup.js"></script>
<script>
$(document).ready(function() { $('a.title').cluetip({splitTitle: '|', closePosition: 'title', width: '450'}); });

document.getElementById('_h3').style.display='';
$(document).ready(function() { $('a.title').cluetip({splitTitle: '|'}); });
</script>
<script>HideWaitingMessage();</script>
</body>
</html>