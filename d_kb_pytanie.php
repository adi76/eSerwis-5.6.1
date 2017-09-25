<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php'); 
?>
<body onLoad="document.forms[0].elements[0].focus();">
<?php 
if ($submit) { 
	?><script>ShowWaitingMessage('Trwa zapisywanie danych...', 'Saving1');</script>
	<?php
	$_POST=sanitize($_POST);
	$remoteIP = $_SERVER['REMOTE_ADDR']; 
	if (strstr($remoteIP, ', ')) { 
	   $ips = explode(', ', $remoteIP); 
	   $remoteIP = $ips[0]; 
	} 

	$dddd = date("Y-m-d H:i:s");
	$sql_a = "INSERT INTO $dbname.serwis_kb_pytania VALUES ('',$_POST[dnpk],$es_nr,'$remoteIP','$dddd','$_POST[dnp]','".nl2br($_POST[dnpo])."', '',$_POST[dnps],0,'','$_REQUEST[zgl_nr]','$_REQUEST[zgl_szcz_id]')";
	
	if (mysql_query($sql_a, $conn)) { 
		if ($_REQUEST[DodajZalacznik]=='on') {
			//echo "SELECT kb_pytanie_id, kb_pytanie_temat FROM $dbname.serwis_kb_pytania WHERE (kb_pytanie_data='$dddd') and (kb_user_id=$es_nr) and (kb_user_ip='$remoteIP') LIMIT 1";
			
			list($last_pyt, $last_pyt_tresc)=mysql_fetch_array(mysql_query("SELECT kb_pytanie_id, kb_pytanie_temat FROM $dbname.serwis_kb_pytania WHERE (kb_pytanie_data='$dddd') and (kb_user_id=$es_nr) and (kb_user_ip='$remoteIP') LIMIT 1", $conn));
			?>
			
			<script>
				self.close();
				newWindow_r(800,600,'z_kb_d_zalacznik.php?pytanie_id=<?php echo $last_pyt; ?>&edit=1&pytanie=<?php echo urlencode($last_pyt_tresc); ?>');
				HideWaitingMessage('Saving1');
				<?php if ($_REQUEST[norefresh]!=1) { ?>
					if (opener) opener.location.reload(true); 
				<?php } ?>
				self.close(); 
			</script>
			<?php
		} else {
		?>
			<script>HideWaitingMessage('Saving1');</script>
			<script>
			<?php if ($_REQUEST[norefresh]!=1) { ?>
				if (opener) opener.location.reload(true); 
			<?php } ?>
			self.close(); 
			</script>
		<?php
		}
	} else 
		{
		  ?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
		}
	
} else { ?>
		
<?php
pageheader("Dodawanie nowego wątku do bazy wiedzy");
starttable();
echo "<form name=add action=$PHP_SELF method=POST onSubmit=\"return pytanie_dodaj_do_kb('Czy dodać nowy wątek do bazy wiedzy ?'); \" />";
tbl_empty_row();
	echo "<tr>";
		echo "<td width=120 class=right>Temat wątku</td>";
		echo "<td><input id=dnp class=wymagane size=80 type=text name=dnp "; 
		if ($_REQUEST[temat]!='') echo " value='".urldecode($_REQUEST[temat])."' ";
		echo ">";
		echo "</td>";
	echo "</tr>";

	echo "<tr>";
		echo "<td width=100 class=right>Kategoria wątku</td>";
		echo "<td>";
		
	if ($poziom==1) 
	{

		echo "<select class=wymagane id=dnpk name=dnpk>";
		//if ($_REQUEST[id]=='') 
		echo "<option value=''></option>";
//if ($poziom==0)	
	$sql="SELECT kb_kategoria_id,kb_kategoria_nazwa FROM $dbname.serwis_kb_kategorie WHERE (kb_parent_id=0) and (kb_kategoria_status=1) ORDER BY kb_kategoria_nazwa ASC";
//if ($poziom==1)	$sql="SELECT kb_kategoria_id,kb_kategoria_nazwa FROM $dbname.serwis_kb_kategorie WHERE kb_parent_id<>0 ORDER BY kb_kategoria_id";

		$result=mysql_query($sql,$conn) or die($k_b);
		while ($dane=mysql_fetch_array($result)) {
			$temp_kat_id 	= $dane['kb_kategoria_id'];
			$temp_kat_nazwa = $dane['kb_kategoria_nazwa'];

			echo "<option ";
			if ($id==$temp_kat_id) echo "SELECTED ";
			echo "value=$temp_kat_id>$temp_kat_nazwa</option>\n";
			
			$sql_pk="SELECT kb_kategoria_id,kb_kategoria_nazwa FROM $dbname.serwis_kb_kategorie WHERE (kb_parent_id=$temp_kat_id) and (kb_kategoria_status=1) ORDER BY kb_kategoria_nazwa ASC";
			
			$result_pk=mysql_query($sql_pk,$conn) or die($k_b);
			
			while ($dane_pk=mysql_fetch_array($result_pk)) 
			{
				$temp_pkat_id 	= $dane_pk['kb_kategoria_id'];
				$temp_pkat_nazwa = $dane_pk['kb_kategoria_nazwa'];
				
				echo "<option ";
				if ($id==$temp_pkat_id) echo "SELECTED ";
				echo "value=$temp_pkat_id>&nbsp;&nbsp;&nbsp;&nbsp;$temp_pkat_nazwa</option>\n";			}
		}
	
		echo "</select>";		
	} else 
	{
		echo "-"; echo "<input type=hidden name=dnpk id=dnpk value=0>";
	}
		
		echo "</td>";
	echo "</tr>";	
	

	echo "<tr>";
		echo "<td width=100 class=righttop>Opis wątku</td>";
		echo "<td><textarea rows=8 cols=50 name=dnpo id=dnpo>";
		if ($_REQUEST[opis]!='') echo urldecode($_REQUEST[opis]);
		echo "</textarea>";
		
		if ($_REQUEST[zgl_szcz_id]!='') {
			echo "<br />";
			$wszystkie_kroki = '';
			$wynik = mysql_query("SELECT zgl_szcz_wykonane_czynnosci FROM $dbname_hd.hd_zgloszenie_szcz WHERE (zgl_szcz_zgl_id='$_REQUEST[zgl_nr]') and (zgl_szcz_widoczne=1)", $conn_hd);
			
			while ($dane_f1=mysql_fetch_array($wynik)) {
				$temp_wc = $dane_f1['zgl_szcz_wykonane_czynnosci'];
				$wszystkie_kroki .= "".(nl2br2($temp_wc))."<br /><br />";
			}
			
			echo "<input type=button class=buttons value='Wrzuć do opisu treść wszystkich kroków zgłoszenia nr $_REQUEST[zgl_nr]' onClick=\"document.getElementById('dnpo').value='".(br2nl3($wszystkie_kroki))."'; document.getElementById('but1').style.display=''; return false;\">";
		
			echo "<br /><input id=but1 style='display:none;' type=button class=buttons value='Wrzuć do opisu wartość domyślną' onClick=\"document.getElementById('dnpo').value='".br2nl3($_REQUEST[opis])."'; return false;\">";
			echo "<input type=hidden name=zgl_nr value='$_REQUEST[zgl_nr]'>";
			echo "<input type=hidden name=zgl_szcz_id value='$_REQUEST[zgl_szcz_id]'>";
			
		} else {
			echo "<input type=hidden name=zgl_nr value=''>";
			echo "<input type=hidden name=zgl_szcz_id value=''>";
		}
		
		
		echo "</td>";
	echo "</tr>";	

	echo "<tr>";
		echo "<td width=100 class=right>Status</td>";
		echo "<td>";

		// -
		// access control 
		$accessLevels = array("9");
		if(array_search($es_prawa, $accessLevels)>-1)
		{
			echo "<select name=dnps onkeypress='return handleEnter(this, event);'>";
			echo "<option value=1>Aktywne</option>\n";
			echo "<option value=0>Nieaktywne</option>\n";
			echo "</select>";
		} else {
 		// access control koniec
		// -
			echo "<b>Aktywne</b>";
			echo "<input type=hidden name=dnps value=1>";
		}
	echo "</td>";
	echo "</tr>";	

tbl_empty_row();
		echo "<tr style='display:'>";
			td(";;");
			td_(";;;");
				echo "<input class=border0 type=checkbox name=DodajZalacznik id=DodajZalacznik onClick=\"if (this.checked) { document.getElementById('DodajDoBazyWiedzySelect').style.display=''; } else { document.getElementById('DodajDoBazyWiedzySelect').style.display='none'; } \" />";
				
				echo "<a href=# class=normalfont onClick=\"if (document.getElementById('DodajZalacznik').checked) { document.getElementById('DodajZalacznik').checked=false; } else { document.getElementById('DodajZalacznik').checked=true; } return false;\"> Po zapisaniu, chcę dodać załącznik do wątku</a>";
				echo "<br />";
			_td();
		echo "</tr>";
		
	tbl_empty_row();
	endtable();	
	echo "<input type=hidden name=norefresh value='$_REQUEST[norefresh]'>";
	
	startbuttonsarea("right");
	addbuttons("zapisz","anuluj");
	endbuttonsarea();

	_form();

} 
?>

</body>
</html>