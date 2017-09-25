<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php 
include('inc_encrypt.php');

if ($submit) { 
$_POST=sanitize($_POST);
$dddd = Date('Y-m-d');
$dddd1 = Date('Y-m-d H:i:s');

$cena = str_replace(',','.',$_POST[kwotapf]);
$crypted_cena = crypt_md5($cena, $key);

$sql_t = "UPDATE $dbname.serwis_podfaktury SET pf_numer='$_POST[tpfnr]', pf_data='$_POST[tpfdw]', pf_dostawca_nazwa='$_POST[firmapf]', pf_kwota_netto='$crypted_cena', pf_uwagi='".nl2br($_POST[pfuwagi])."', pf_osoba='$currentuser', pf_datawpisu='$dddd1', belongs_to=$es_filia WHERE (pf_id=$_POST[pfid]) LIMIT 1";


if (mysql_query($sql_t, $conn)) { 

		// zebranie sumy kosztów z wszystkich podfaktury
		$sql_get_sum = "SELECT pf_kwota_netto FROM $dbname.serwis_podfaktury WHERE ((pf_nr_faktury_id=$_POST[fid]) and (belongs_to=$es_filia))";
		$lacznie_kwota = 0;
		$result_get_suma = mysql_query($sql_get_sum, $conn) or die($k_b);
		while ($newArray1 = mysql_fetch_array($result_get_suma)) {
			$temp_koszty	= $newArray1['pf_kwota_netto'];

			$lacznie_kwota += decrypt_md5($temp_koszty,$key);
		}
	
		$lacznie_kwota_crypted = crypt_md5($lacznie_kwota,$key);

		$sql_update_faktura = "UPDATE $dbname.serwis_faktury SET faktura_koszty_dodatkowe='$lacznie_kwota_crypted' WHERE faktura_id=$_POST[fid] LIMIT 1";
		
		$doit = mysql_query($sql_update_faktura,$conn) or die($k_b);
		
		?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
} else 
	{
	  ?><script>info('Wystąpił błąd podczas dodawania podfaktury do bazy'); self.close(); </script><?php		
		}

} else { ?>
		
<?php
	
pageheader("Edycja podfaktury");

$sql5 = "SELECT * FROM $dbname.serwis_podfaktury WHERE pf_id=$id";
$result5 = mysql_query($sql5, $conn) or die($k_b);
$newArray = mysql_fetch_array($result5);

	$temp_id  			= $newArray['pf_id'];
	$temp_nrfaktury		= $newArray['pf_nr_faktury_id'];
	$temp_numer			= $newArray['pf_numer'];
	$temp_data			= $newArray['pf_data'];
	$temp_dostawca		= $newArray['pf_dostawca_nazwa'];
	$temp_kwota_netto	= $newArray['pf_kwota_netto'];
	$temp_uwagi			= $newArray['pf_uwagi'];
	
	starttable();
	echo "<form name=addpf action=$PHP_SELF method=POST>";

	tbl_empty_row();

		echo "<tr>";
		echo "<td class=right>Numer podfaktury</td>";
		echo "<td><input id=pfake size=35 maxlength=50 type=text name=tpfnr value='$temp_numer' onkeypress='return handleEnter(this, event);'>";
		echo "</td>";
		echo "</td>";
	echo "</tr>";

	echo "<tr>";
		echo "<td class=right>Data wystawienia podfaktury</td>";
		
		//$dddd = Date('Y-m-d');
		
		echo "<td><input id=tpfdw size=10 type=text name=tpfdw maxlength=10 value='$temp_data' onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\" onkeypress='return handleEnter(this, event);'>"; 
		echo "<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif width=16 height=16 border=0></a>";
		if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('tpfdw').value='".Date('Y-m-d')."'; return false;\">";
	    echo "</td>";
	echo "</tr>";

	echo "<tr>";
		echo "<td class=right>Firma wystawiająca podfakturę</td>";
		echo "<td>";
		echo "<select class=wymagane name=firmapf onkeypress='return handleEnter(this, event);'>\n";
		echo "<option value=''>Wybierz z listy...</option>\n";
		$sql_e = "SELECT * FROM $dbname.serwis_fz WHERE ((fz_is_ds='on') or (fz_is_fk='on') or (fz_is_fs='on')) ORDER BY fz_nazwa ASC";		
		$result = mysql_query($sql_e, $conn) or die($k_b);

		while ($newArray = mysql_fetch_array($result)) 
		 {
			$temp_id1  				= $newArray['fz_id'];
			$temp_nazwa1			= $newArray['fz_nazwa'];
			
			echo "<option value='$temp_nazwa1' ";
			if ($temp_nazwa1==$temp_dostawca) echo "selected";
			echo ">$temp_nazwa1</option>\n"; 
		}
		echo "</select>\n"; 
		echo "</td>";
	echo "</tr>";

	$kwotan = decrypt_md5($temp_kwota_netto,$key);
	
	echo "<tr>";
	echo "<td class=right>Kwota netto podfaktury</td>";
	if ($blockprice==0) {	
		echo "<td><input style='text-align:right;' class=wymagane size=5 maxlength=5 type=text name=kwotapf value='".correct_currency($kwotan)."' onkeypress='return handleEnter(this, event);'>&nbsp;zł</td>";
	} else {
		echo "<td><input type=hidden name=kwotapf value='".correct_currency($kwotan)."'>".correct_currency($kwotan)."&nbsp;zł</td>";
	}
	echo "</tr>";

	echo "<tr>";
		echo "<td class=righttop>Uwagi</td>";
		echo "<td><textarea name=pfuwagi cols=30 rows=4>".br2nl($temp_uwagi)."</textarea></td>";
	echo "</tr>";
	
// -
// access control 
/* $accessLevels = array("9");
if(array_search($es_prawa, $accessLevels)>-1){	
	
	echo "<tr>";
		echo "<td class=right>Koszt przesyłki</td>";
		echo "<td><input size=4 maxlength=5 type=text name=tfkp>&nbsp;zł</td>";
	echo "</tr>";

} 
else $tfkp=0;
*/

	tbl_empty_row();
	echo "<input type=hidden name=tfkp value=0>";
	echo "<input type=hidden name=pfid value='$temp_id'>";
	echo "<input type=hidden name=fid value='$temp_nrfaktury'>";	
// access control koniec
	
	endtable();
	
	startbuttonsarea("right");
	addbuttons("zapisz","anuluj");
	endbuttonsarea();

	_form();
?>

<script language="JavaScript">
var cal1 = new calendar1(document.forms['addpf'].elements['tpfdw']);
	cal1.year_scroll = true;
	cal1.time_comp = false;

</script>

<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("addpf");
 
  frmvalidator.addValidation("firmapf","req","Nie wpisałeś nazwy firmy wystawiającej podfakturę");
  frmvalidator.addValidation("kwotapf","req","Nie wpisano kwoty na podfakturze");
 
</script>

<?php } ?>

</body>
</html>