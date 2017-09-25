<?php 
include_once('header.php');
include_once('cfg_eserwis.php');
include_once('cfg_helpdesk.php');

echo "<body OnLoad=\"document.forms[0].elements[0].focus();\" />";


if ($submit) {
	$_REQUEST=sanitize($_REQUEST);
	
	$sql = "UPDATE $dbname_hd.hd_zgloszenie_oferty SET oferta_data = '$_REQUEST[hd_oferta_data]', oferta_numer = '$_REQUEST[hd_oferta_numer]', oferta_uwagi = '$_REQUEST[hd_oferta_uwagi]' WHERE ((oferta_zgl_szcz_id = '$_REQUEST[hd_oferta_zgl_szcz_id]') and (belongs_to=$es_filia))";

	if (mysql_query($sql, $conn_hd)) {			
		?><script>if (opener) opener.location.reload(true); self.close();</script><?php
			//echo " - Oferta została wysłana do klienta";
		} else {
	?><?php
	}		
} else {

pageheader("Edytuj informacje o wysłanej ofercie do klienta");
starttable();
echo "<form id=hd_oferta name=hd_oferta action=$PHP_SELF method=POST />";

$result = mysql_query("SELECT oferta_data,oferta_numer, oferta_uwagi FROM $dbname_hd.hd_zgloszenie_oferty WHERE (oferta_zgl_szcz_id='$_GET[id]') and (belongs_to=$es_filia)", $conn_hd) or die($k_b);
list($odata,$onumer,$ouwagi)=mysql_fetch_array($result);

tbl_empty_row(2);
	tr_();
		td("150;r;<b>Data wysłania oferty</b>");
		td_(";;;");
			$dddd = Date('Y-m-d');
			echo "<select name=hd_oferta_data id=hd_oferta_data>";
			echo "<option value='$odata' SELECTED>$odata</option>\n";
		
			if ((date("w",strtotime($dddd))!=1) || ($idw_dla_zbh_testowa))  {
				for ($cd=1; $cd<($idw_dla_zbh+1); $cd++) {
					echo "<option value='".SubstractDays($cd,$dddd)."'>".SubstractDays($cd,$dddd)."&nbsp;";
					if ($idw_dla_zbh_testowa) echo "[dla testów]";
					echo "</option>\n";
				}
			}
			
			//echo "<option value='".SubstractWorkingDays(1,$dddd)."'>".SubstractWorkingDays(1,$dddd)."&nbsp;</option>\n";
			//echo "<option value='".SubstractWorkingDays(2,$dddd)."'>".SubstractWorkingDays(2,$dddd)."&nbsp;</option>\n";
			echo "</select>\n";
		_td();
	_tr();
	tr_();
		td("150;rt;<b>Numer oferty</b>");
		td_(";;;");
			echo "<input type=text size=10 maxlength=20 name=hd_oferta_numer value='$onumer'/>";
		_td();
	_tr();	
	tr_();
		td("150;rt;<b>Uwagi<br />do oferty</b>");
		td_(";;;");
			echo "<textarea name=hd_oferta_uwagi cols=35 rows=5 />$ouwagi</textarea>";
		_td();
	_tr();		
	tbl_empty_row(2);
endtable();
echo "<input type=hidden name=znr value='$_GET[znr]'>";

echo "<input type=hidden name=hd_oferta_zgl_szcz_id value='$_GET[id]'>";
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
  
EnterToTab.init(document.forms.hd_oferta, false);

</script>
</body>
</html>