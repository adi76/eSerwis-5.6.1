<?php 
$page_half = ($page_max / 2);

//if ($_GET[hd_rps]!='') $rowpersite = $_GET[hd_rps];
//if ($_GET[hd_rps]!='') {
//	$rowpersite = $_GET[hd_rps];
//} else $_GET[hd_rps] = $rowpersite;

$pw = array($_f."/hd_g_raport_weryfikacja_zgloszen.php"); 
if (array_search($PHP_SELF, $pw)>-1) {
	$rowpersite = $_REQUEST[rps111];
} else {

	if ($_GET[hd_rps]!='') {
		$rowpersite = $_GET[hd_rps];
		$_SESSION['es_rps']=$_GET[hd_rps];
	} else {
		
		if ($_SESSION['es_rps']!='') {
			$_GET[hd_rps] = $_SESSION['es_rps'];
			$rowpersite = $_SESSION['es_rps'];
		} else {	
			$_GET[hd_rps] = $rowpersite;
		}		
	}
}

$hd_rps = $rowpersite;

$numofpages = ceil($totalrows / $rowpersite);  
if ($page_half>=$numofpages) $page_half=$totalrows;
$page_start = $page-$page_half;
$page_end = $page+$page_half;
if ($page_start<1) $page_start=1;
if ($page_end>$numofpages) $page_end=$numofpages;
if (($page_end-$page_start)<$page_max) {
	if ($page<$page_max) $page_end=($page_start+$page_max);
	if ($page>$page_max) $page_start=($page_end-$page_max);	
}

if ($page_end>$numofpages) $page_end=$numofpages;

$phpfile = $PHP_SELF;
$pliki_wyroznione = array($folder."/hd_p_zgloszenia.php",$_f."/hd_g_raport_weryfikacja_zgloszen.php");

echo "<hr style='color:#5F6676;border-style:dotted;height:1px;border-bottom:none;border-right:none;border-left:none;background-color:transparent;margin:5px 0 5px 0;padding:0px;'>";
echo "<span style='float:right'>";

if ($numofpages>1) {
	echo "<span class=hideme>";
	echo "<FORM style='float:right;' NAME=nav>";
	echo "Idź do strony <SELECT NAME=SelectPage onChange=\"self.location.href=this.value\">";
	for($i = 1; $i <= $numofpages; $i++) {
		echo "<OPTION ";
		if ($i==$page) echo "SELECTED ";
		echo "VALUE=\"$phpfile?sa=$sa&page=$i&sel=$sel&id=$id&p1=$p1&p2=$p2&p3=$p3&p4=$p4&p5=$p5&p6=$p6&p7=$p7&p8=$p8&sr=$sr&hd_rps=$hd_rps&s=$s&p0=$p0&ss=".urlencode($ss)."&st=".urlencode($st)."&sd=".urlencode($sd)."&sk=".urlencode($sk)."&add=$_REQUEST[add]&submit=$_REQUEST[submit]&okres_od=$_REQUEST[okres_od]&okres_do=$_REQUEST[okres_do]&tzgldata=$_REQUEST[tzgldata]&tuser=$_REQUEST[tuser]&tstatus=$_REQUEST[tstatus]&kategoria=$_REQUEST[kategoria]&podkategoria=$_REQUEST[podkategoria]&potw_spr=$_REQUEST[potw_spr]&rps111=$_REQUEST[rps111]\">$i</option>\n";
	}
	echo "</SELECT>";
}

$pw = array($_f."/hd_p_zgloszenia.php"); 
if (array_search($PHP_SELF, $pw)>-1) {

	echo "&nbsp;&nbsp;&nbsp;&nbsp;Wierszy na stronie&nbsp;<SELECT name=hd_rps onChange=\"self.location.href=this.value;\">";

	echo "<option value=\"$phpfile?sa=$sa&page=1&sel=$sel&id=$id&p1=$p1&p2=$p2&p3=$p3&p4=$p4&p5=$p5&p6=$p6&p7=$p7&p8=$p8&sr=$sr&hd_rps=5&s=$s&p0=$p0&ss=".urlencode($ss)."&st=".urlencode($st)."&sd=".urlencode($sd)."&sk=".urlencode($sk)."&add=$_REQUEST[add]&submit=$_REQUEST[submit]&okres_od=$_REQUEST[okres_od]&okres_do=$_REQUEST[okres_do]&tzgldata=$_REQUEST[tzgldata]&tuser=$_REQUEST[tuser]&tstatus=$_REQUEST[tstatus]&kategoria=$_REQUEST[kategoria]&podkategoria=$_REQUEST[podkategoria]&potw_spr=$_REQUEST[potw_spr]&rps111=$_REQUEST[rps111]\""; if ($_GET[hd_rps]==5) echo " SELECTED "; echo ">5</option>\n";
	
	echo "<option value=\"$phpfile?sa=$sa&page=1&sel=$sel&id=$id&p1=$p1&p2=$p2&p3=$p3&p4=$p4&p5=$p5&p6=$p6&p7=$p7&p8=$p8&sr=$sr&hd_rps=15&s=$s&p0=$p0&ss=".urlencode($ss)."&st=".urlencode($st)."&sd=".urlencode($sd)."&sk=".urlencode($sk)."&add=$_REQUEST[add]&submit=$_REQUEST[submit]&okres_od=$_REQUEST[okres_od]&okres_do=$_REQUEST[okres_do]&tzgldata=$_REQUEST[tzgldata]&tuser=$_REQUEST[tuser]&tstatus=$_REQUEST[tstatus]&kategoria=$_REQUEST[kategoria]&podkategoria=$_REQUEST[podkategoria]&potw_spr=$_REQUEST[potw_spr]&rps111=$_REQUEST[rps111]\""; if ($_GET[hd_rps]==15) echo " SELECTED "; echo ">15</option>\n";

	echo "<option value=\"$phpfile?sa=$sa&page=1&sel=$sel&id=$id&p1=$p1&p2=$p2&p3=$p3&p4=$p4&p5=$p5&p6=$p6&p7=$p7&p8=$p8&sr=$sr&hd_rps=50&s=$s&p0=$p0&ss=".urlencode($ss)."&st=".urlencode($st)."&sd=".urlencode($sd)."&sk=".urlencode($sk)."&add=$_REQUEST[add]&submit=$_REQUEST[submit]&okres_od=$_REQUEST[okres_od]&okres_do=$_REQUEST[okres_do]&tzgldata=$_REQUEST[tzgldata]&tuser=$_REQUEST[tuser]&tstatus=$_REQUEST[tstatus]&kategoria=$_REQUEST[kategoria]&podkategoria=$_REQUEST[podkategoria]&potw_spr=$_REQUEST[potw_spr]&rps111=$_REQUEST[rps111]\""; if ($_GET[hd_rps]==50) echo " SELECTED "; echo ">50</option>\n";
	
	echo "<option value=\"$phpfile?sa=$sa&page=1&sel=$sel&id=$id&p1=$p1&p2=$p2&p3=$p3&p4=$p4&p5=$p5&p6=$p6&p7=$p7&p8=$p8&sr=$sr&hd_rps=100&s=$s&p0=$p0&ss=".urlencode($ss)."&st=".urlencode($st)."&sd=".urlencode($sd)."&sk=".urlencode($sk)."&add=$_REQUEST[add]&submit=$_REQUEST[submit]&okres_od=$_REQUEST[okres_od]&okres_do=$_REQUEST[okres_do]&tzgldata=$_REQUEST[tzgldata]&tuser=$_REQUEST[tuser]&tstatus=$_REQUEST[tstatus]&kategoria=$_REQUEST[kategoria]&podkategoria=$_REQUEST[podkategoria]&potw_spr=$_REQUEST[potw_spr]&rps111=$_REQUEST[rps111]\""; if ($_GET[hd_rps]==100) echo " SELECTED "; echo ">100</option>\n";
	
	echo "<option value=\"$phpfile?sa=$sa&page=1&sel=$sel&id=$id&p1=$p1&p2=$p2&p3=$p3&p4=$p4&p5=$p5&p6=$p6&p7=$p7&p8=$p8&sr=$sr&hd_rps=200&s=$s&p0=$p0&ss=".urlencode($ss)."&st=".urlencode($st)."&sd=".urlencode($sd)."&sk=".urlencode($sk)."&add=$_REQUEST[add]&submit=$_REQUEST[submit]&okres_od=$_REQUEST[okres_od]&okres_do=$_REQUEST[okres_do]&tzgldata=$_REQUEST[tzgldata]&tuser=$_REQUEST[tuser]&tstatus=$_REQUEST[tstatus]&kategoria=$_REQUEST[kategoria]&podkategoria=$_REQUEST[podkategoria]&potw_spr=$_REQUEST[potw_spr]&rps111=$_REQUEST[rps111]\""; if ($_GET[hd_rps]==200) echo " SELECTED "; echo ">200</option>\n";	
	echo "</select>";

}

	_form();
	echo "</span>";
	
	//echo "<span id=SaveToCookie style=''><br />";
	//echo "asdsad";
	//echo "</span>";

//}

echo "</span>";
?>