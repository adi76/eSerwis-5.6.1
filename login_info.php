	<table class="mainpageheader hideme">
	<tr>
	<td style="vertical-align:middle;" nowrap>
	<?php
	$remoteIP = $_SERVER['REMOTE_ADDR']; 
	if (strstr($remoteIP, ', ')) { 
		$ips = explode(', ', $remoteIP); 
		$remoteIP = $ips[0]; 
	} 
	$fullhost = $remoteIP; 	
	echo "Twój adres IP : <b>$fullhost</b>";	
	?>
	</td>
	<td align="center" width="100%">
	<?php
	$sql_ = "SELECT admin_opis,admin_value FROM $dbname.serwis_admin WHERE ((admin_id=1) and (admin_value=1)) LIMIT 1";
	$odpowiedz = mysql_query($sql_,$conn) or die($k_b);
	if (mysql_num_rows($odpowiedz)!=0) {
		$dane				= mysql_fetch_array($odpowiedz);
		$es_admin_info 		= $dane['admin_opis'];
		$es_admin_info_a 	= $dane['admin_value'];
	} else { $es_admin_info=''; $es_admin_info_a=0; }

	if ($es_admin_info_a!=0) {
		echo "<div style='position:relative; display:block; top:0; border:1px solid red; height:16px; background: #FF9999;color:#313131; padding: 4px 5px 2px 5px' align=center>";
		echo "<b>$es_admin_info</b>";
		echo "</div>";
	}
	?>
	</td>
	<td class="right" width="auto" nowrap>
	<?php 
	$sql="SELECT user_dyrektor FROM $dbname.serwis_uzytkownicy WHERE user_id=$es_nr";
	$result = mysql_query($sql, $conn) or die($k_b);
	$dane1 = mysql_fetch_array($result);
	session_register('is_dyrektor');
	$is_dyrektor = $dane1['user_dyrektor'];
	
	echo "Zalogowany: <b>".$currentuser."</b>"; 
	$sql="SELECT filia_nazwa FROM $dbname.serwis_filie WHERE filia_id=$es_filia";
	$result = mysql_query($sql, $conn) or die($k_b);
	$dane1 = mysql_fetch_array($result);
	$filian = $dane1['filia_nazwa'];
	
	echo " ( ";
	if ($is_dyrektor==0) {
		if ($es_prawa=='0') { echo "<b>Użytkownik zwykły</b>"; }
		if ($es_prawa=='2') { echo "<b>Helpdesk</b>"; }
		if ($es_prawa=='1') { echo "<b>Użytkownik zaawansowany</b>"; }
		if ($es_prawa=='9') { 
			echo "<b>";
			if ($es_m=='1') { echo "<font color=red>Super "; }
			echo "Administrator";
			if ($es_m=='1') { echo "</font>"; }
			echo "</b>"; 
		}
	} else {
		if ($is_dyrektor==1) echo " <b>Dyrektor</b>";
	}
	
	echo " )";
	
	if ($is_dyrektor==1) {	
		$sql44="SELECT filia_id,filia_nazwa FROM $dbname.serwis_filie";
		$result44 = mysql_query($sql44, $conn) or die($k_b);
		echo " | Widok obszaru: ";
		echo "<form name=switch_filia style='display:inline'>";
		echo "<select name=view_filia onChange='document.location.href=document.switch_filia.view_filia.options[document.switch_filia.view_filia.selectedIndex].value'>";
		while ($newArray44 = mysql_fetch_array($result44)) {
			$temp_f	= $newArray44['filia_id'];
			$temp_fn = $newArray44['filia_nazwa'];
			
			echo "<option value='main.php?new_location=$temp_f'"; 
			if (($es_filia==$temp_f) || ($temp_f==$_GET[new_location])) echo " SELECTED"; 
			
			echo ">$temp_fn</option>\n";
		}
		echo "</select>";
		echo "</form>";
	} else echo " | Lokalizacja: <b>$filian</b>";
	
	//	if (isset($_SESSION['is_dyrektor'])) {
	
	//	} else {

	//	}			
	?>			
	</td>
	</tr>
	</table>