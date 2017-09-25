<?php 
$page_half = ($page_max / 2);

if ($_GET[hd_rps]!='') $rowpersite = $_GET[hd_rps];
$numofpages = ceil($totalrows / $rowpersite);  
if ($page_half>=$numofpages) $page_half=$totalrows;
$page_start = $page-$page_half;
$page_end = $page+$page_half;
if ($page_start<1) $page_start=1;
if ($page_end>$numofpages) $page_end=$numofpages;
if (($page_end-$page_start)<$page_max) 
{
	if ($page<$page_max) $page_end=($page_start+$page_max);
	if ($page>$page_max) $page_start=($page_end-$page_max);	
}

if ($page_end>$numofpages) $page_end=$numofpages;

startbuttonsarea("center");
$pw = array($_f."/hd_p_zgloszenia.php"); 
if (array_search($PHP_SELF, $pw)>-1) {} else {	
	if ($_REQUEST[showall]!=1) nowalinia();
}

if (($showall==0) && ($printpreview!=1) && ($numofpages>1)) {
	$phpfile = $PHP_SELF;
	if($page != 1)
		{  
			$pageprev = $page - 1;

			echo "<a class=pagingimage href=\"$phpfile?showall=$showall&page=1&okres_od=$okres_od&okres_do=$okres_do&submit=$submit&sel=$sel&view=$view&ew_action=$ew_action&g=$g&allowback=$allowback&s=$s&sel_up=$sel_up&id=$id&sold=$sold&action=$action&logid=$logid&pokaz=$pokaz&o=$o&uwagi=$uwagi&wybierz_p=$wybierz_p&okresm=$okresm&okresr=$okresr&wybierz=&p1=$p1&p2=$p2&p3=$p3&p4=$p4&p5=$p5&p6=".urlencode($p6)."&p7=$p7&search=$search&sort=$sort&p0=$p0&akcja=$akcja&wybierz_typ=$wybierz_typ&wybierz_kat=$wybierz_kat&osoba=$osoba&aktywne=$aktywne&ko=$ko&nr=$_REQUEST[nr]&up=".urlencode($_REQUEST[up])."\"><img class=border0 src=img/button_first.gif></a> " ; 
			echo "<a class=pagingimage href=\"$phpfile?showall=$showall&page=$pageprev&okres_od=$okres_od&okres_do=$okres_do&submit=$submit&sel=$sel&view=$view&ew_action=$ew_action&g=$g&allowback=$allowback&s=$s&sel_up=$sel_up&id=$id&sold=$sold&action=$action&logid=$logid&pokaz=$pokaz&o=$o&uwagi=$uwagi&wybierz_p=$wybierz_p&okresm=$okresm&okresr=$okresr&wybierz=&p1=$p1&p2=$p2&p3=$p3&p4=$p4&p5=$p5&p6=".urlencode($p6)."&p7=$p7&search=$search&sort=$sort&p0=$p0&akcja=$akcja&wybierz_typ=$wybierz_typ&wybierz_kat=$wybierz_kat&osoba=$osoba&aktywne=$aktywne&ko=$ko&nr=$_REQUEST[nr]&up=".urlencode($_REQUEST[up])."\"><img class=border0 src=img/button_prev.gif></a> " ; 
			
	    } else 
		{ 
			echo("<a class=nav_current_first><input class=pagingimage type=image src=img/button_first_inactive.gif align=absmiddle></a>"); 
			echo("<a class=nav_current_first><input class=pagingimage type=image src=img/button_prev_inactive.gif align=absmiddle></a>"); 
	    } 
	
		if ($_GET[page]>10) $page_start = $_GET[page]-10;
		if (($_GET[page]+10)<$page_end) $page_end=$_GET[page]+10;
		
	    for($i = $page_start; $i <= $page_end; $i++)
		{ 
		
			if($i == $page)
			{ 
				echo "<a class=nav_current>$i</a>"; 
	        } else 
			{ 
				echo "<a class=nav_normal href=\"$phpfile?showall=$showall&page=$i&okres_od=$okres_od&okres_do=$okres_do&submit=$submit&sel=$sel&view=$view&ew_action=$ew_action&g=$g&allowback=$allowback&s=$s&sel_up=$sel_up&id=$id&sold=$sold&action=$action&logid=$logid&pokaz=$pokaz&o=$o&uwagi=$uwagi&wybierz_p=$wybierz_p&okresm=$okresm&okresr=$okresr&wybierz=&p1=$p1&p2=$p2&p3=$p3&p4=$p4&p5=$p5&p6=".urlencode($p6)."&p7=$p7&search=$search&sort=$sort&p0=$p0&akcja=$akcja&wybierz_typ=$wybierz_typ&wybierz_kat=$wybierz_kat&osoba=$osoba&aktywne=$aktywne&ko=$ko&nr=$_REQUEST[nr]&up=".urlencode($_REQUEST[up])."\">$i</a> " ; 
	        } 
	    } 

		if ($i<=$numofpages) echo "...";
		
	    if (($totalrows % $rowpersite) != 0)
		{ 
	        if($i == $page)
			{
				echo("<a class=nav_current>$i</a>"); 
	        } else 
			{
				if ($page<$numofpages) {
				//echo("<a class=nav_normal href=\"$phpfile?showall=$showall&page=$i&okres_od=$okres_od&okres_do=$okres_do&submit=$submit&sel=$sel&view=$view&ew_action=$ew_action&g=$g&allowback=$allowback&s=$s\">$i</a> "); 
				}
	        }
	    }
		
		echo "<a class='pagingimage hideme'> z $numofpages </a>";
	    if (($totalrows - ($rowpersite * $page)) > 0)
		{ 
			$pagenext = $page + 1;  

			echo "<a class=pagingimage href=\"$phpfile?showall=$showall&page=$pagenext&okres_od=$okres_od&okres_do=$okres_do&submit=$submit&sel=$sel&view=$view&ew_action=$ew_action&g=$g&allowback=$allowback&s=$s&sel_up=$sel_up&id=$id&sold=$sold&action=$action&logid=$logid&pokaz=$pokaz&o=$o&uwagi=$uwagi&wybierz_p=$wybierz_p&okresm=$okresm&okresr=$okresr&wybierz=&p1=$p1&p2=$p2&p3=$p3&p4=$p4&p5=$p5&p6=".urlencode($p6)."&p7=$p7&search=$search&sort=$sort&p0=$p0&akcja=$akcja&wybierz_typ=$wybierz_typ&wybierz_kat=$wybierz_kat&osoba=$osoba&aktywne=$aktywne&ko=$ko&nr=$_REQUEST[nr]&up=".urlencode($_REQUEST[up])."\"><img class=border0 src=img/button_next.gif></a> " ; 			
			
			echo "<a class=pagingimage href=\"$phpfile?showall=$showall&page=$numofpages&okres_od=$okres_od&okres_do=$okres_do&submit=$submit&sel=$sel&view=$view&ew_action=$ew_action&g=$g&allowback=$allowback&s=$s&sel_up=$sel_up&id=$id&sold=$sold&action=$action&logid=$logid&pokaz=$pokaz&o=$o&uwagi=$uwagi&wybierz_p=$wybierz_p&okresm=$okresm&okresr=$okresr&wybierz=&p1=$p1&p2=$p2&p3=$p3&p4=$p4&p5=$p5&p6=".urlencode($p6)."&p7=$p7&search=$search&sort=$sort&p0=$p0&akcja=$akcja&wybierz_typ=$wybierz_typ&wybierz_kat=$wybierz_kat&osoba=$osoba&aktywne=$aktywne&ko=$ko&nr=$_REQUEST[nr]&up=".urlencode($_REQUEST[up])."\"><img class=border0 src=img/button_last.gif></a> " ; 		
			
	    } else
		{ 
			echo "<a class=nav_current_last><input class=pagingimage type=image src=img/button_next_inactive.gif align=absmiddle></a>";  
			echo "<a class=nav_current_last><input class=pagingimage type=image src=img/button_last_inactive.gif align=absmiddle></a>";  
	    } 

endbuttonsarea();		
if ($numofpages>1) {
startbuttonsarea("center");

$pliki_wyroznione = $folder."/hd_p_zgloszenia.php";
if ($PHP_SELF!=$pliki_wyroznione) {
echo "<FORM NAME=nav>";
echo "<span class=hideme>";
echo "Idź do strony <SELECT NAME=SelectPage onChange='document.location.href=
document.nav.SelectPage.options[document.nav.SelectPage.selectedIndex].value'>";
for($i = 1; $i <= $numofpages; $i++)
{
	echo "<OPTION ";
	if ($i==$page) echo "SELECTED ";
	echo "VALUE=\"$phpfile?showall=$showall&page=$i&okres_od=$okres_od&okres_do=$okres_do&submit=$submit&sel=$sel&view=$view&ew_action=$ew_action&g=$g&allowback=$allowback&s=$s&sel_up=$sel_up&esk_rola_check=$esk_rola_check&id=$id&sold=$sold&action=$action&logid=$logid&pokaz=$pokaz&o=$o&uwagi=$uwagi&wybierz_p=$wybierz_p&okresm=$okresm&okresr=$okresr&wybierz=&p1=$p1&p2=$p2&p3=$p3&p4=$p4&p5=$p5&p6=".urlencode($p6)."&p7=$p7&search=$search&sort=$sort&p0=$p0&akcja=$akcja&wybierz_typ=$wybierz_typ&wybierz_kat=$wybierz_kat&osoba=$osoba&aktywne=$aktywne&ko=$ko&nr=$_REQUEST[nr]&up=".urlencode($_REQUEST[up])."\">$i</option>\n";
}

echo "</SELECT>";
echo "</span>";
_form();
} else {
	$pliki_wyroznione = array($folder."/hd_p_zgloszenia.php");
	if (array_search($PHP_SELF, $pliki_wyroznione)<0) {

		echo "<br />Wierszy na stronie&nbsp;<SELECT name=hd_rps onChange=\"self.location.href=this.value\">";
		echo "<option value=\"$phpfile?showall=$showall&page=1&okres_od=$okres_od&okres_do=$okres_do&submit=$submit&sel=$sel&view=$view&ew_action=$ew_action&g=$g&allowback=$allowback&s=$s&sel_up=$sel_up&esk_rola_check=$esk_rola_check&id=$id&sold=$sold&action=$action&logid=$logid&pokaz=$pokaz&o=$o&uwagi=$uwagi&wybierz_p=$wybierz_p&okresm=$okresm&okresr=$okresr&wybierz=&p1=$p1&p2=$p2&p3=$p3&p4=$p4&p5=$p5&p6=".urlencode($p6)."&p7=$p7&search=$search&hd_rps=5&sort=$sort&p0=$p0&akcja=$akcja&wybierz_typ=$wybierz_typ&wybierz_kat=$wybierz_kat&osoba=$osoba&aktywne=$aktywne&ko=$ko&nr=$_REQUEST[nr]&up=".urlencode($_REQUEST[up])."\""; if ($_GET[hd_rps]==5) echo " SELECTED "; echo ">5</option>\n";
		
		echo "<option value=\"$phpfile?showall=$showall&page=1&okres_od=$okres_od&okres_do=$okres_do&submit=$submit&sel=$sel&view=$view&ew_action=$ew_action&g=$g&allowback=$allowback&s=$s&sel_up=$sel_up&esk_rola_check=$esk_rola_check&id=$id&sold=$sold&action=$action&logid=$logid&pokaz=$pokaz&o=$o&uwagi=$uwagi&wybierz_p=$wybierz_p&okresm=$okresm&okresr=$okresr&wybierz=&p1=$p1&p2=$p2&p3=$p3&p4=$p4&p5=$p5&p6=".urlencode($p6)."&p7=$p7&search=$search&hd_rps=15&sort=$sort&p0=$p0&akcja=$akcja&wybierz_typ=$wybierz_typ&wybierz_kat=$wybierz_kat&osoba=$osoba&aktywne=$aktywne&ko=$ko&nr=$_REQUEST[nr]&up=".urlencode($_REQUEST[up])."\""; if ($_GET[hd_rps]==15) echo " SELECTED "; echo ">15</option>\n";

		echo "<option value=\"$phpfile?showall=$showall&page=1&okres_od=$okres_od&okres_do=$okres_do&submit=$submit&sel=$sel&view=$view&ew_action=$ew_action&g=$g&allowback=$allowback&s=$s&sel_up=$sel_up&esk_rola_check=$esk_rola_check&id=$id&sold=$sold&action=$action&logid=$logid&pokaz=$pokaz&o=$o&uwagi=$uwagi&wybierz_p=$wybierz_p&okresm=$okresm&okresr=$okresr&wybierz=&p1=$p1&p2=$p2&p3=$p3&p4=$p4&p5=$p5&p6=".urlencode($p6)."&p7=$p7&search=$search&hd_rps=50&sort=$sort&p0=$p0&akcja=$akcja&wybierz_typ=$wybierz_typ&wybierz_kat=$wybierz_kat&osoba=$osoba&aktywne=$aktywne&ko=$ko&nr=$_REQUEST[nr]&up=".urlencode($_REQUEST[up])."\""; if ($_GET[hd_rps]==50) echo " SELECTED "; echo ">50</option>\n";

		echo "<option value=\"$phpfile?showall=$showall&page=1&okres_od=$okres_od&okres_do=$okres_do&submit=$submit&sel=$sel&view=$view&ew_action=$ew_action&g=$g&allowback=$allowback&s=$s&sel_up=$sel_up&esk_rola_check=$esk_rola_check&id=$id&sold=$sold&action=$action&logid=$logid&pokaz=$pokaz&o=$o&uwagi=$uwagi&wybierz_p=$wybierz_p&okresm=$okresm&okresr=$okresr&wybierz=&p1=$p1&p2=$p2&p3=$p3&p4=$p4&p5=$p5&p6=".urlencode($p6)."&p7=$p7&search=$search&hd_rps=100&sort=$sort&p0=$p0&akcja=$akcja&wybierz_typ=$wybierz_typ&wybierz_kat=$wybierz_kat&osoba=$osoba&aktywne=$aktywne&ko=$ko&nr=$_REQUEST[nr]&up=".urlencode($_REQUEST[up])."\""; if ($_GET[hd_rps]==100) echo " SELECTED "; echo ">100</option>\n";

		echo "</select>";
	} 
}
endbuttonsarea();
}
}
$pliki_wyroznione = array($folder."/hd_p_zgloszenia.php");
if (array_search($PHP_SELF, $pliki_wyroznione)<0) echo "<hr style='color:#5F6676;border-style:dotted;height:1px;border-bottom:none;border-right:none;border-left:none;background-color:transparent;margin:5px 0 5px 0;padding:0px;'>";
//endbuttonsarea();
?>