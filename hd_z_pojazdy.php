<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body>
<?php include('body_start.php');
$sql="SELECT * FROM $dbname_hd.hd_pojazdy WHERE hd_pojazd_user_id='$_GET[userid]' ";
if ($es_m!=1) { $sql = $sql." AND belongs_to=$es_filia"; }
$sql = $sql." ORDER BY hd_pojazd_id ASC";
//echo "$sql";
$result = mysql_query($sql, $conn_hd) or die($k_b);

$totalrows = mysql_num_rows($result);
if ($showall==0) { $rps=$rowpersite; } else $rps=10000;
if(empty($page)){ $page = 1; }
$limitvalue = $page * $rps - ($rps);
$sql="SELECT * FROM $dbname_hd.hd_pojazdy WHERE hd_pojazd_user_id='$_GET[userid]' ";
if ($es_m!=1) { $sql = $sql." AND belongs_to=$es_filia"; }
$sql = $sql." ORDER BY hd_pojazd_id ASC LIMIT $limitvalue, $rps";
$result = mysql_query($sql, $conn_hd) or die($k_b);
if ($totalrows!=0) {

pageheader("Przeglądanie pojazdów pracownika",1,0);

starttable();
th(";;LP|;c;Użytkowany<br />od|;c;Użytkowany<br />do|;;Marka|;c;Numer rejestracyjny|;;Kategoria|;c;Pojemność silnika|;c;Opcje",$es_prawa);
$i = 0;
$j = 1;
while ($newArray = mysql_fetch_array($result)) {
	$temp_id  						= $newArray['hd_pojazd_id'];
	$temp_user_id					= $newArray['hd_pojazd_user_id'];
	$temp_pojazd_od					= $newArray['hd_pojazd_od'];
	$temp_pojazd_do					= $newArray['hd_pojazd_do'];
	$temp_pojazd_marka				= $newArray['hd_pojazd_marka'];
	$temp_pojazd_nr_rejestracyjny	= $newArray['hd_pojazd_nr_rejestracyjny'];
	$temp_pojazd_pojemnosc			= $newArray['hd_pojazd_pojemnosc'];
	$temp_kategoria					= $newArray['hd_pojazd_kategoria'];
	$temp_pojazd_active				= $newArray['hd_pojazd_active'];
	
	tbl_tr_highlight($i);
	
	switch ($temp_kategoria) {
		case "1"	: $kategoria = 'Motorower'; break;
		case "2"	: $kategoria = 'Motocykl'; break;
		case "3"	: $kategoria = 'Samochód (poniżej 900cm)'; break;
		case "4"	: $kategoria = 'Samochód (powyżej 900cm)'; break;
	}
	
	$pojazd_od = $temp_pojazd_od;
	$pojazd_do = $temp_pojazd_do;
	
	if ($temp_pojazd_od=='0000-00-00') $pojazd_oo='';
	if ($temp_pojazd_do=='0000-00-00') $pojazd_do='';
	
	if ($temp_pojazd_active==0) { echo "<font color=grey>"; }
	
		if ($temp_pojazd_marka=='') $temp_pojazd_marka='-';
		
		td(";c;".$j."|80;r;".$pojazd_od."|80;r;".$pojazd_do."|;;".$temp_pojazd_marka."|;;".$temp_pojazd_nr_rejestracyjny."|;;".$kategoria."|;c;".$temp_pojazd_pojemnosc."");
		echo "</font>";
		
			td_(";c");
			if ($temp_pojazd_active==1) {
				echo "<a title=' Edytuj dane o pojeździe $temp_pojazd_marka ($temp_pojazd_nr_rejestracyjny) '><input class=imgoption type=image src=img/edit.gif onclick=\"newWindow(550,400,'e_pojazd.php?id=$temp_id')\">";
			}
			if (($currentuser!="$temp_first_name $temp_last_name") && (($es_prawa==1) || ($es_prawa==9))) {
					echo "<a title=' Usuń pojazd $temp_pojazd_marka ($temp_pojazd_nr_rejestracyjny) z bazy '><input class=imgoption type=image src=img/delete.gif onclick=\"if (confirm('Czy napewno chcesz usunąć wybrany pojazd z bazy ?')) newWindow(10,10,'hd_u_pojazd.php?select_id=$temp_id')\"></a>";
					
			}
		_td();
	_tr();
	$i++;
	$j++;
}
endtable();
} else errorheader("Baza pojazdów dla użytkownika jest pusta");
//listabaz($es_prawa,"".$pokaz_ikony."");
startbuttonsarea("right");

addownlinkbutton("'Dodaj pojazd'","Button1","button","newWindow(550,250,'d_pojazd.php?userid=$_GET[userid]')");

addbuttons("zamknij");
endbuttonsarea();	 
//include('body_stop.php');
//include('js/menu.js');
?>
</body>
</html>