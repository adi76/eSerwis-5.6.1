<?php 
$page_half = ($page_max / 2);
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
if (($showall==0) && ($printpreview!=1)) 
{
	$phpfile = $PHP_SELF;
	if($page != 1)
		{  
			$pageprev = $page - 1;

			echo("<a><input class=pagingimage type=image src=img/button_first.gif align=absmiddle onclick=\"self.location.href='$phpfile?showall=$showall&page=1&okres_od=$okres_od&okres_do=$okres_do&submit=$submit&sel=$sel&view=$view&ew_action=$ew_action&g=$g&allowback=$allowback&s=$s&sel_up=$sel_up&id=$id&sold=$sold&esk_rola_check=$esk_rola_check&esk_rola=$esk_rola&stat=$stat&esk_lok_check=$esk_lok_check&esk_lok=$esk_lok&esk_user_check=$esk_user_check&esk_user_checkw=$esk_user_checkw&esk_user=$esk_user&esk_nrpok_check=$esk_nrpok_check&esk_nrpok_checkw=$esk_nrpok_checkw&esk_nrpok=$esk_nrpok&esk_nrinwz_check=$esk_nrinwz_check&esk_nrinwz_checkw=$esk_nrinwz_checkw&esk_nrinwz=$esk_nrinwz&esk_nazwak_check=$esk_nazwak_check&esk_nazwak_checkw=$esk_nazwak_checkw&esk_nazwak=$esk_nazwak&esk_opisk_check=$esk_opisk_check&esk_opisk_checkw=$esk_opisk_checkw&esk_opisk=$esk_opisk&esk_nrsk_check=$esk_nrsk_check&esk_nrsk_checkw=$esk_nrsk_checkw&esk_nrsk=$esk_nrsk&esk_nrip_check=$esk_nrip_check&esk_nrip_checkw=$esk_nrip_checkw&esk_nrip=$esk_nrip&esk_endpoint_check=$esk_endpoint_check&esk_endpoint_checkw=$esk_endpoint_checkw&esk_endpoint=$esk_endpoint&esk_nazwam_check=$esk_nazwam_check&esk_nazwam_checkw=$esk_nazwam_checkw&esk_nazwam=$esk_nazwam&esk_nrsm_check=$esk_nrsm_check&esk_nrsm_checkw=$esk_nrsm_checkw&esk_nrsm=$esk_nrsm&esk_nazwad_check=$esk_nazwad_check&esk_nazwad_checkw=$esk_nazwad_checkw&esk_nazwad=$esk_nazwad&esk_nrsd_check=$esk_nrsd_check&esk_nrsd_checkw=$esk_nrsd_checkw&esk_nrsd=$esk_nrsd&esk_nrinwd_check=$esk_nrinwd_check&esk_nrinwd_checkw=$esk_nrinwd_checkw&esk_nrinwd=$esk_nrinwd&esk_konf_check=$esk_konf_check&esk_konf_checkw=$esk_konf_checkw&esk_konf=$esk_konf&esk_uwagi_check=$esk_uwagi_check&esk_status_check=$esk_status_check&esk_opcje_check=$esk_opcje_check&action=$action&logid=$logid&pokaz=$pokaz'\"></a>");  

			echo("<a><input class=pagingimage type=image src=img/button_prev.gif align=absmiddle onclick=\"self.location.href='$phpfile?showall=$showall&page=$pageprev&okres_od=$okres_od&okres_do=$okres_do&submit=$submit&sel=$sel&view=$view&ew_action=$ew_action&g=$g&allowback=$allowback&s=$s&sel_up=$sel_up&id=$id&sold=$sold&esk_rola_check=$esk_rola_check&esk_rola=$esk_rola&stat=$stat&esk_lok_check=$esk_lok_check&esk_lok=$esk_lok&esk_user_check=$esk_user_check&esk_user_checkw=$esk_user_checkw&esk_user=$esk_user&esk_nrpok_check=$esk_nrpok_check&esk_nrpok_checkw=$esk_nrpok_checkw&esk_nrpok=$esk_nrpok&esk_nrinwz_check=$esk_nrinwz_check&esk_nrinwz_checkw=$esk_nrinwz_checkw&esk_nrinwz=$esk_nrinwz&esk_nazwak_check=$esk_nazwak_check&esk_nazwak_checkw=$esk_nazwak_checkw&esk_nazwak=$esk_nazwak&esk_opisk_check=$esk_opisk_check&esk_opisk_checkw=$esk_opisk_checkw&esk_opisk=$esk_opisk&esk_nrsk_check=$esk_nrsk_check&esk_nrsk_checkw=$esk_nrsk_checkw&esk_nrsk=$esk_nrsk&esk_nrip_check=$esk_nrip_check&esk_nrip_checkw=$esk_nrip_checkw&esk_nrip=$esk_nrip&esk_endpoint_check=$esk_endpoint_check&esk_endpoint_checkw=$esk_endpoint_checkw&esk_endpoint=$esk_endpoint&esk_nazwam_check=$esk_nazwam_check&esk_nazwam_checkw=$esk_nazwam_checkw&esk_nazwam=$esk_nazwam&esk_nrsm_check=$esk_nrsm_check&esk_nrsm_checkw=$esk_nrsm_checkw&esk_nrsm=$esk_nrsm&esk_nazwad_check=$esk_nazwad_check&esk_nazwad_checkw=$esk_nazwad_checkw&esk_nazwad=$esk_nazwad&esk_nrsd_check=$esk_nrsd_check&esk_nrsd_checkw=$esk_nrsd_checkw&esk_nrsd=$esk_nrsd&esk_nrinwd_check=$esk_nrinwd_check&esk_nrinwd_checkw=$esk_nrinwd_checkw&esk_nrinwd=$esk_nrinwd&esk_konf_check=$esk_konf_check&esk_konf_checkw=$esk_konf_checkw&esk_konf=$esk_konf&esk_uwagi_check=$esk_uwagi_check&esk_status_check=$esk_status_check&esk_opcje_check=$esk_opcje_check&action=$action&logid=$logid&pokaz=$pokaz'\"></a>");  
	    } else 
		{ 
			echo("<a class=nav_current_first><input class=pagingimage type=image src=img/button_first_inactive.gif align=absmiddle></a>"); 
			echo("<a class=nav_current_first><input class=pagingimage type=image src=img/button_prev_inactive.gif align=absmiddle></a>"); 
	    } 
	
	    for($i = $page_start; $i <= $page_end; $i++)
		{ 
		
			if($i == $page)
			{ 
				echo("<a class=nav_current>$i</a>"); 
	        } else 
			{ 
				echo("<a class=nav_normal href=\"$phpfile?showall=$showall&page=$i&okres_od=$okres_od&okres_do=$okres_do&submit=$submit&sel=$sel&view=$view&ew_action=$ew_action&g=$g&allowback=$allowback&s=$s&sel_up=$sel_up&id=$id&sold=$sold&esk_rola_check=$esk_rola_check&esk_rola=$esk_rola&stat=$stat&esk_lok_check=$esk_lok_check&esk_lok=$esk_lok&esk_user_check=$esk_user_check&esk_user_checkw=$esk_user_checkw&esk_user=$esk_user&esk_nrpok_check=$esk_nrpok_check&esk_nrpok_checkw=$esk_nrpok_checkw&esk_nrpok=$esk_nrpok&esk_nrinwz_check=$esk_nrinwz_check&esk_nrinwz_checkw=$esk_nrinwz_checkw&esk_nrinwz=$esk_nrinwz&esk_nazwak_check=$esk_nazwak_check&esk_nazwak_checkw=$esk_nazwak_checkw&esk_nazwak=$esk_nazwak&esk_opisk_check=$esk_opisk_check&esk_opisk_checkw=$esk_opisk_checkw&esk_opisk=$esk_opisk&esk_nrsk_check=$esk_nrsk_check&esk_nrsk_checkw=$esk_nrsk_checkw&esk_nrsk=$esk_nrsk&esk_nrip_check=$esk_nrip_check&esk_nrip_checkw=$esk_nrip_checkw&esk_nrip=$esk_nrip&esk_endpoint_check=$esk_endpoint_check&esk_endpoint_checkw=$esk_endpoint_checkw&esk_endpoint=$esk_endpoint&esk_nazwam_check=$esk_nazwam_check&esk_nazwam_checkw=$esk_nazwam_checkw&esk_nazwam=$esk_nazwam&esk_nrsm_check=$esk_nrsm_check&esk_nrsm_checkw=$esk_nrsm_checkw&esk_nrsm=$esk_nrsm&esk_nazwad_check=$esk_nazwad_check&esk_nazwad_checkw=$esk_nazwad_checkw&esk_nazwad=$esk_nazwad&esk_nrsd_check=$esk_nrsd_check&esk_nrsd_checkw=$esk_nrsd_checkw&esk_nrsd=$esk_nrsd&esk_nrinwd_check=$esk_nrinwd_check&esk_nrinwd_checkw=$esk_nrinwd_checkw&esk_nrinwd=$esk_nrinwd&esk_konf_check=$esk_konf_check&esk_konf_checkw=$esk_konf_checkw&esk_konf=$esk_konf&esk_uwagi_check=$esk_uwagi_check&esk_status_check=$esk_status_check&esk_opcje_check=$esk_opcje_check&action=$action&logid=$logid&pokaz=$pokaz\">$i</a> "); 
	        } 
	    } 

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
		
		echo " z $numofpages ";
	    if (($totalrows - ($rowpersite * $page)) > 0)
		{ 
			$pagenext = $page + 1;  
	        echo("<a><input class=pagingimage type=image src=img/button_next.gif align=absmiddle onClick=\"self.location.href='$phpfile?showall=$showall&page=$pagenext&okres_od=$okres_od&okres_do=$okres_do&submit=$submit&sel=$sel&view=$view&ew_action=$ew_action&g=$g&allowback=$allowback&s=$s&sel_up=$sel_up&id=$id&sold=$sold&esk_rola_check=$esk_rola_check&esk_rola=$esk_rola&stat=$stat&esk_lok_check=$esk_lok_check&esk_lok=$esk_lok&esk_user_check=$esk_user_check&esk_user_checkw=$esk_user_checkw&esk_user=$esk_user&esk_nrpok_check=$esk_nrpok_check&esk_nrpok_checkw=$esk_nrpok_checkw&esk_nrpok=$esk_nrpok&esk_nrinwz_check=$esk_nrinwz_check&esk_nrinwz_checkw=$esk_nrinwz_checkw&esk_nrinwz=$esk_nrinwz&esk_nazwak_check=$esk_nazwak_check&esk_nazwak_checkw=$esk_nazwak_checkw&esk_nazwak=$esk_nazwak&esk_opisk_check=$esk_opisk_check&esk_opisk_checkw=$esk_opisk_checkw&esk_opisk=$esk_opisk&esk_nrsk_check=$esk_nrsk_check&esk_nrsk_checkw=$esk_nrsk_checkw&esk_nrsk=$esk_nrsk&esk_nrip_check=$esk_nrip_check&esk_nrip_checkw=$esk_nrip_checkw&esk_nrip=$esk_nrip&esk_endpoint_check=$esk_endpoint_check&esk_endpoint_checkw=$esk_endpoint_checkw&esk_endpoint=$esk_endpoint&esk_nazwam_check=$esk_nazwam_check&esk_nazwam_checkw=$esk_nazwam_checkw&esk_nazwam=$esk_nazwam&esk_nrsm_check=$esk_nrsm_check&esk_nrsm_checkw=$esk_nrsm_checkw&esk_nrsm=$esk_nrsm&esk_nazwad_check=$esk_nazwad_check&esk_nazwad_checkw=$esk_nazwad_checkw&esk_nazwad=$esk_nazwad&esk_nrsd_check=$esk_nrsd_check&esk_nrsd_checkw=$esk_nrsd_checkw&esk_nrsd=$esk_nrsd&esk_nrinwd_check=$esk_nrinwd_check&esk_nrinwd_checkw=$esk_nrinwd_checkw&esk_nrinwd=$esk_nrinwd&esk_konf_check=$esk_konf_check&esk_konf_checkw=$esk_konf_checkw&esk_konf=$esk_konf&esk_uwagi_check=$esk_uwagi_check&esk_status_check=$esk_status_check&esk_opcje_check=$esk_opcje_check&action=$action&logid=$logid&pokaz=$pokaz'\"></a>"); 
			
echo("<a><input class=pagingimage type=image src=img/button_last.gif align=absmiddle onClick=\"self.location.href='$phpfile?showall=$showall&page=$numofpages&okres_od=$okres_od&okres_do=$okres_do&submit=$submit&sel=$sel&view=$view&ew_action=$ew_action&g=$g&allowback=$allowback&s=$s&sel_up=$sel_up&id=$id&sold=$sold&esk_rola_check=$esk_rola_check&esk_rola=$esk_rola&stat=$stat&esk_lok_check=$esk_lok_check&esk_lok=$esk_lok&esk_user_check=$esk_user_check&esk_user_checkw=$esk_user_checkw&esk_user=$esk_user&esk_nrpok_check=$esk_nrpok_check&esk_nrpok_checkw=$esk_nrpok_checkw&esk_nrpok=$esk_nrpok&esk_nrinwz_check=$esk_nrinwz_check&esk_nrinwz_checkw=$esk_nrinwz_checkw&esk_nrinwz=$esk_nrinwz&esk_nazwak_check=$esk_nazwak_check&esk_nazwak_checkw=$esk_nazwak_checkw&esk_nazwak=$esk_nazwak&esk_opisk_check=$esk_opisk_check&esk_opisk_checkw=$esk_opisk_checkw&esk_opisk=$esk_opisk&esk_nrsk_check=$esk_nrsk_check&esk_nrsk_checkw=$esk_nrsk_checkw&esk_nrsk=$esk_nrsk&esk_nrip_check=$esk_nrip_check&esk_nrip_checkw=$esk_nrip_checkw&esk_nrip=$esk_nrip&esk_endpoint_check=$esk_endpoint_check&esk_endpoint_checkw=$esk_endpoint_checkw&esk_endpoint=$esk_endpoint&esk_nazwam_check=$esk_nazwam_check&esk_nazwam_checkw=$esk_nazwam_checkw&esk_nazwam=$esk_nazwam&esk_nrsm_check=$esk_nrsm_check&esk_nrsm_checkw=$esk_nrsm_checkw&esk_nrsm=$esk_nrsm&esk_nazwad_check=$esk_nazwad_check&esk_nazwad_checkw=$esk_nazwad_checkw&esk_nazwad=$esk_nazwad&esk_nrsd_check=$esk_nrsd_check&esk_nrsd_checkw=$esk_nrsd_checkw&esk_nrsd=$esk_nrsd&esk_nrinwd_check=$esk_nrinwd_check&esk_nrinwd_checkw=$esk_nrinwd_checkw&esk_nrinwd=$esk_nrinwd&esk_konf_check=$esk_konf_check&esk_konf_checkw=$esk_konf_checkw&esk_konf=$esk_konf&esk_uwagi_check=$esk_uwagi_check&esk_status_check=$esk_status_check&esk_opcje_check=$esk_opcje_check&action=$action&logid=$logid&pokaz=$pokaz'\"></a>"); 
	    } else
		{ 
			echo("<a class=nav_current_last><input class=pagingimage type=image src=img/button_next_inactive.gif align=absmiddle></a>");  
			echo("<a class=nav_current_last><input class=pagingimage type=image src=img/button_last_inactive.gif align=absmiddle></a>");  
	    } 
	
if ($numofpages>1) 
{
startbuttonsarea("center");
echo "<FORM NAME=nav>";
echo "Idź do strony <SELECT NAME=SelectPage onChange='document.location.href=
document.nav.SelectPage.options[document.nav.SelectPage.selectedIndex].value'>";
for($i = 1; $i <= $numofpages; $i++)
{
	echo "<OPTION ";
	if ($i==$page) echo "SELECTED ";
	echo "VALUE=\"$phpfile?showall=$showall&page=$i&okres_od=$okres_od&okres_do=$okres_do&submit=$submit&sel=$sel&view=$view&ew_action=$ew_action&g=$g&allowback=$allowback&s=$s&sel_up=$sel_up&esk_rola_check=$esk_rola_check&id=$id&sold=$sold&esk_rola=$esk_rola&stat=$stat&esk_lok_check=$esk_lok_check&esk_lok=$esk_lok&esk_user_check=$esk_user_check&esk_user_checkw=$esk_user_checkw&esk_user=$esk_user&esk_nrpok_check=$esk_nrpok_check&esk_nrpok_checkw=$esk_nrpok_checkw&esk_nrpok=$esk_nrpok&esk_nrinwz_check=$esk_nrinwz_check&esk_nrinwz_checkw=$esk_nrinwz_checkw&esk_nrinwz=$esk_nrinwz&esk_nazwak_check=$esk_nazwak_check&esk_nazwak_checkw=$esk_nazwak_checkw&esk_nazwak=$esk_nazwak&esk_opisk_check=$esk_opisk_check&esk_opisk_checkw=$esk_opisk_checkw&esk_opisk=$esk_opisk&esk_nrsk_check=$esk_nrsk_check&esk_nrsk_checkw=$esk_nrsk_checkw&esk_nrsk=$esk_nrsk&esk_nrip_check=$esk_nrip_check&esk_nrip_checkw=$esk_nrip_checkw&esk_nrip=$esk_nrip&esk_endpoint_check=$esk_endpoint_check&esk_endpoint_checkw=$esk_endpoint_checkw&esk_endpoint=$esk_endpoint&esk_nazwam_check=$esk_nazwam_check&esk_nazwam_checkw=$esk_nazwam_checkw&esk_nazwam=$esk_nazwam&esk_nrsm_check=$esk_nrsm_check&esk_nrsm_checkw=$esk_nrsm_checkw&esk_nrsm=$esk_nrsm&esk_nazwad_check=$esk_nazwad_check&esk_nazwad_checkw=$esk_nazwad_checkw&esk_nazwad=$esk_nazwad&esk_nrsd_check=$esk_nrsd_check&esk_nrsd_checkw=$esk_nrsd_checkw&esk_nrsd=$esk_nrsd&esk_nrinwd_check=$esk_nrinwd_check&esk_nrinwd_checkw=$esk_nrinwd_checkw&esk_nrinwd=$esk_nrinwd&esk_konf_check=$esk_konf_check&esk_konf_checkw=$esk_konf_checkw&esk_konf=$esk_konf&esk_uwagi_check=$esk_uwagi_check&esk_status_check=$esk_status_check&esk_opcje_check=$esk_opcje_check&action=$action&logid=$logid&pokaz=$pokaz\">$i</option>\n";
}

echo "</SELECT>";
_form();
endbuttonsarea();
}
}
endbuttonsarea();
?>