<?php 
include_once('header.php');
include_once('cfg_eserwis.php');
include_once('cfg_helpdesk.php');

echo "<body OnLoad=\"document.forms[0].elements[0].focus();\" />";


if ($submit) {
	$_REQUEST=sanitize($_REQUEST);
	
	$sql = "UPDATE $dbname_hd.hd_zgloszenie_szcz SET zgl_szcz_zamowienie_wyslane=1 WHERE (zgl_szcz_id='$_REQUEST[hd_zam_zgl_szcz_id]') LIMIT 1";
	$result = mysql_query($sql, $conn_hd) or die($k_b);
	
	$sql = "INSERT INTO $dbname_hd.hd_zgloszenie_zamowienia VALUES ('',$_REQUEST[hd_zam_zgl_szcz_id],'$_REQUEST[hd_zam_data]','$_REQUEST[hd_zam_numer]','$_REQUEST[hd_zam_uwagi]',1,'$currentuser',$es_filia)";
	
	if (mysql_query($sql, $conn_hd)) {

			$r3 = mysql_query("SELECT hdnp_id FROM $dbname_hd.hd_naprawy_powiazane WHERE ((hdnp_zgl_id='$_REQUEST[znr]') and (hdnp_naprawa_id='$_REQUEST[nid]') and (belongs_to='$es_filia') and (hdnp_widoczne=1)) LIMIT 1", $conn_hd) or die($k_b);
			list($jest_powiazana_naprawa)=mysql_fetch_array($r3);
				
			if ($jest_powiazana_naprawa>0) {
				$sql1 = "UPDATE $dbname_hd.hd_naprawy_powiazane SET hdnp_zamowienie_wyslane=1 WHERE (hdnp_id='$jest_powiazana_naprawa') LIMIT 1";
				$result1 = mysql_query($sql1, $conn_hd) or die($k_b);
			}
			
		?><script>if (opener) opener.location.reload(true); self.close(); 
			//document.getElementById("ZamowienieWyslaneButton").style.display='none'; </script><?php
			//echo " - Oferta została wysłana do klienta";
		} else {
	?><?php
	}		
} else {

pageheader("Potwierdzenie wysłania zamówienia do realizacji");
starttable();
echo "<form id=hd_zamowienie name=hd_zamowienie action=$PHP_SELF method=POST />";

tbl_empty_row(2);
	tr_();
		td("150;r;<b>Data wysłania zamówienia</b>");
		td_(";;;");
			$dddd = Date('Y-m-d');
			echo "<select name=hd_zam_data id=hd_oferta_data>";
			echo "<option value='$dddd' SELECTED>$dddd</option>\n";

			if ((date("w",strtotime($dddd))!=1) || ($idw_dla_zbh_testowa))  {
				for ($cd=1; $cd<($idw_dla_zbh+1); $cd++) {
					echo "<option value='".SubstractDays($cd,$dddd)."'>".SubstractDays($cd,$dddd)."&nbsp;";
					if ($idw_dla_zbh_testowa) echo "[dla testów]";
					echo "</option>\n";
				}
			}

			
		//	echo "<option value='".SubstractWorkingDays(1,$dddd)."'>".SubstractWorkingDays(1,$dddd)."&nbsp;</option>\n";
		//	echo "<option value='".SubstractWorkingDays(2,$dddd)."'>".SubstractWorkingDays(2,$dddd)."&nbsp;</option>\n";
			echo "</select>\n";
		_td();
	_tr();
	tr_();
		td("150;rt;<b>Numer zamówienia</b>");
		td_(";;;");
			echo "<input type=text size=10 maxlength=20 name=hd_zam_numer />";
		_td();
	_tr();	
	tr_();
		td("150;rt;<b>Uwagi<br />do zamówienia</b>");
		td_(";;;");
			echo "<textarea name=hd_zam_uwagi cols=35 rows=5 /></textarea>";
		_td();
	_tr();		
	tbl_empty_row(2);
endtable();

echo "<input type=hidden name=znr value='$_GET[znr]'>";
echo "<input type=hidden name=nid value='$_GET[nid]'>";

echo "<input type=hidden name=hd_zam_zgl_szcz_id value='$_GET[id]'>";
startbuttonsarea("right");
echo "<input id=submit type=submit class=buttons name=submit value='Zapisz'>";
//echo "<input type=button class=buttons name=reset value=\"Wyczyść zgłoszenie\" onClick=\"pytanie_wyczysc('Wyczyścić formularz ?'); \" />";
echo "<input class=buttons id=anuluj type=button onClick=\"self.close();\" value=Anuluj>";
endbuttonsarea();

echo "</form>";
}
?>

<script type="text/javascript" src="js/jquery/entertotab.js"></script>
<script type='text/javascript'>
  
EnterToTab.init(document.forms.hd_zamowienie, false);

</script>
</body>
</html>