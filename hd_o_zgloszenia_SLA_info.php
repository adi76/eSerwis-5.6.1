<?php

	if (($temp_kategoria=='2') || ($temp_kategoria=='6')) {
		
		tbl_empty_row(1);

		tr_();
			echo "<td colspan=2 class=left>";
				echo "<b>Wyliczone czasy wynikające z umowy:</b>";
			echo "</td>";
		_tr();
		
		tr_();
			echo "<td colspan=2 class=center>";
				echo "<hr />";
			echo "</td>";
		_tr();

		tr_();	
			td_("160;r;Maksymalna data rozpoczęcia"); 		_td(); 	
			td_(";;;");		
				if ($__color) echo "<font color=red>";
				echo "<b>".substr($__zgl_data_r,0,16)."</b>";
				if ($__color) echo "</font>";
				
				if ($__zgl_data_r=='0000-00-00 00:00:00') echo " <- błędnie wyliczona max. data rozpoczęcia (skontaktuj się z administratorem)";
			
				if ((($temp_kategoria==2) || ($temp_kategoria==6))) {
					echo "<span style='float:right'>";
					//if ($__tunon) echo "<input type=button class=buttons value='Przelicz czasy umowne' onClick=\"newWindow_r(800,600,'przelicz_czasy.php'); return false; \" />";
					echo "</span>";
				}
			_td(); 	
		_tr();
		
		tr_();	
			td_("160;r;Maksymalna data zakończenia"); 		_td(); 	
			td_(";;;");		
				if ($__color) echo "<font color=red>";					
				echo "<b>".substr(AddMinutesToDate($__temp_zgl_E2P,$__zgl_data_z),0,16)."</b>";
				if ($__color) echo "</font>";
				if ($__zgl_data_z=='0000-00-00 00:00:00') echo " <- błędnie wyliczona max. data zakończenia (skontaktuj się z administratorem)";
				if ($__temp_zgl_E2P>0) {
					echo " | ".substr($__zgl_data_z,0,16)." + $__temp_zgl_E2P minut (przestoje na etapie rozwiązania)";
				}
			_td(); 	
		_tr();
		
		tr_();
			echo "<td colspan=2 class=center>";
				echo "<hr />";
			echo "</td>";
		_tr();
		//tbl_empty_row(1);
	}
	
?>