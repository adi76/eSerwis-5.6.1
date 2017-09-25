<?php include_once('header.php');
include_once('cfg_helpdesk.php');
?>

<body OnLoad="document.forms[0].elements[1].focus();" />
<?php

	//echo $_REQUEST[action];
	//nowalinia();
	pageheader("Zapisane w zgłoszeniu nr <b>$_REQUEST[nr]</b> godziny pracy komórki");
	infoheader("<b>".$_REQUEST[komorkanazwa]."</b>");
	
			$days = explode(";",$_REQUEST[wa]);
			
			$oneday1 = explode("@",$days[0]); 
			$oneday2 = explode("@",$days[1]); 
			$oneday3 = explode("@",$days[2]); 
			$oneday4 = explode("@",$days[3]); 
			$oneday5 = explode("@",$days[4]); 
			$oneday6 = explode("@",$days[5]); 
			$oneday7 = explode("@",$days[6]); 

			$gp_sa = 1;
			if (($oneday1[1]=='') && ($oneday2[1]=='') && ($oneday3[1]=='') && ($oneday4[1]=='') && ($oneday5[1]=='') && ($oneday6[1]=='') && ($oneday7[1]=='')) $gp_sa = 0;
			
			if ($oneday1[1]=='') $oneday1[1] = '-';
			if ($oneday2[1]=='') $oneday2[1] = '-';
			if ($oneday3[1]=='') $oneday3[1] = '-';
			if ($oneday4[1]=='') $oneday4[1] = '-';
			if ($oneday5[1]=='') $oneday5[1] = '-';
			if ($oneday6[1]=='') $oneday6[1] = '-';
			if ($oneday7[1]=='') $oneday7[1] = '-';
			
			// menu z godzinami pracy
			$opis_stanow = '<table>';
			//$opis_stanow.= '<tr height=24><td colspan=2 class=center style=background-color:#FFFF7F><font color=black>Godziny pracy</b></font></td></tr>';
			$opis_stanow .= '<tr><td class=right colspan=2></td></tr>';
			$opis_stanow .= '<tr><td class=right>Poniedziałek:</td><td><b>'.$oneday1[1].'</b></td></tr>';
			$opis_stanow .= '<tr><td class=right>Wtorek</td><td><b>'.$oneday2[1].'</b></td></tr>';
			$opis_stanow .= '<tr><td class=right>Środa:</td><td><b>'.$oneday3[1].'</b></td></tr>';
			$opis_stanow .= '<tr><td class=right>Czwartek:</td><td><b>'.$oneday4[1].'</b></td></tr>';
			$opis_stanow .= '<tr><td class=right>Piątek:</td><td><b>'.$oneday5[1].'</b></td></tr>';
			$opis_stanow .= '<tr><td class=right>Sobota:</td><td><b>'.$oneday6[1].'</b></td></tr>';
			$opis_stanow .= '<tr><td class=right>Niedziela:</td><td><b>'.$oneday7[1].'</b></td></tr>';
			$opis_stanow .= '<tr><td class=right colspan=2></td></tr>';
			$opis_stanow.= '</table>';
			
	echo $opis_stanow;
	startbuttonsarea("right");
	echo "<input class=buttons type=button onClick=\"self.close();\" value=Zamknij />";
	endbuttonsarea();
	
?>
</body>
</html>