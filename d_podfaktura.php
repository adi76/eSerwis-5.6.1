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

$sql_t = "INSERT INTO $dbname.serwis_podfaktury values ('', '$_POST[fid]','$_POST[tpfnr]','$_POST[tpfdw]','$_POST[firmapf]','$crypted_cena','TAK','".nl2br($_POST[pfuwagi])."','$currentuser','$dddd1',$es_filia)";

if (mysql_query($sql_t, $conn)) { 

		// zebranie sumy kosztów z wszystkich podfaktury
		$sql_get_sum = "SELECT pf_kwota_netto FROM $dbname.serwis_podfaktury WHERE ((pf_nr_faktury_id=$_POST[fid]) and (belongs_to=$es_filia))";
		$lacznie_kwota = 0;
		$result_get_suma = mysql_query($sql_get_sum, $conn) or die($k_b);
		while ($newArray1 = mysql_fetch_array($result_get_suma)) {
			$temp_koszty  			= $newArray1['pf_kwota_netto'];

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
} else { 

pageheader("Dodawanie nowej podfaktury");

$sql5 = "SELECT * FROM $dbname.serwis_faktury WHERE faktura_id=$id";
$result5 = mysql_query($sql5, $conn) or die($k_b);
$newArray = mysql_fetch_array($result5);

	$temp_id  			= $newArray['faktura_id'];
	$temp_numer			= $newArray['faktura_numer'];
	$temp_data			= $newArray['faktura_data'];
	$temp_dostawca		= $newArray['faktura_dostawca'];
	$temp_koszty		= $newArray['faktura_koszty_dodatkowe'];
	$temp_osoba			= $newArray['faktura_osoba'];
	$temp_datawpisu		= $newArray['faktura_datawpisu'];
	$temp_status		= $newArray['faktura_status'];
	$temp_realizacja	= $newArray['faktura_realizacjazakupu'];
	$temp_fnrz			= $newArray['faktura_nr_zamowienia'];
	
	startbuttonsarea("center");
	
	$nw=0;
	echo "Numer faktury : <b>$temp_numer</b>";
	if ($temp_numer=='') { echo "<b><i><font color=grey>nie wpisana</font></i></b>"; $nw = 1; }	
	
	if ($nw==1) {
		echo "&nbsp;<a title=' Popraw fakturę '><input class=imgoption type=image src=img/edit.gif onclick=\"newWindow(800,600,'e_faktura.php?id=$temp_id&fz=1'); return false;\"></a>";
	}
	
	echo "<br />Data wystawienia : <b>$temp_data</b><br />Firma wystawiająca fakturę : <b>$temp_dostawca</b><br />Numer zamówienia : <b>$temp_fnrz</b>";
	endbuttonsarea();
	
	starttable();
	echo "<form name=addpf action=$PHP_SELF method=POST>";
	echo "<input type=hidden name=fid value='$temp_id'>";	
	tbl_empty_row();

		echo "<tr>";
		echo "<td width=200 class=right>Numer podfaktury</td>";
		echo "<td><input id=pfaktura_add size=35 maxlength=50 type=text name=tpfnr>";
		echo "</td>";
		echo "</td>";
	echo "</tr>";
	
	echo "<tr>";
		echo "<td width=200 class=right>Data wystawienia podfaktury</td>";
		
		$dddd = Date('Y-m-d');
		
		echo "<td><input id=tpfdw size=10 type=text name=tpfdw maxlength=10 value=$dddd  onKeyDown=\"javascript:return dFilter(event.keyCode, this, '####-##-##');\" onkeypress='return handleEnter(this, event);'>"; 
		echo "<a tabindex=-1 href=javascript:cal1.popup(); title=' Kliknij, aby wybrać datę '><img class=imgoption src=img/cal.gif width=16 height=16 border=0></a>";
		if ($_today==1) echo "<input title=' Ustaw datę na dzień bieżący ' type=image class=imgoption src=img/hd_note_today.gif width=16 height=16 border=0 onClick=\"document.getElementById('tpfdw').value='".Date('Y-m-d')."'; return false;\">";
	    echo "</td>";
	echo "</tr>";

	echo "<tr>";
		echo "<td width=200 class=right>Firma wystawiająca podfakturę</td>";
		echo "<td>";
		//<input class=wymagane size=35 maxlength=100 type=text name=firmapf>&nbsp;
		echo "<select class=wymagane name=firmapf>\n";
		echo "<option value=''>Wybierz z listy...</option>\n";

		$sql_e = "SELECT * FROM $dbname.serwis_fz WHERE ((fz_is_ds='on') or (fz_is_fs='on') or (fz_is_fk='on')) ORDER BY fz_nazwa ASC";
		$result = mysql_query($sql_e, $conn) or die($k_b);

		while ($newArray = mysql_fetch_array($result)) 
		 {
			$temp_id1  				= $newArray['fz_id'];
			$temp_nazwa1			= $newArray['fz_nazwa'];
			
			echo "<option value='$temp_nazwa1' ";
			echo ">$temp_nazwa1</option>\n"; 
		
		}		
		echo "</select>\n"; 
		echo "</td>";
	echo "</tr>";

	echo "<tr>";
		echo "<td width=200 class=right>Kwota netto podfaktury</td>";
		echo "<td><input style='text-align:right;' class=wymagane size=5 maxlength=5 type=text name=kwotapf onkeypress=\"return filterInput(1, event, true);\">&nbsp;zł</td>";
	echo "</tr>";

	echo "<tr>";
		echo "<td width=200 class=righttop>Uwagi</td>";
		echo "<td><textarea name=pfuwagi cols=30 rows=4></textarea></td>";
	echo "</tr>";
	
// -
// access control 
/* $accessLevels = array("9");
if(array_search($es_prawa, $accessLevels)>-1){	
	
	echo "<tr>";
		echo "<td width=200 class=right>Koszt przesyłki</td>";
		echo "<td><input size=4 maxlength=5 type=text name=tfkp>&nbsp;zł</td>";
	echo "</tr>";

} 
else $tfkp=0;
*/

	echo "<input type=hidden name=tfkp value=0>";

// access control koniec


	echo "<tr>";
	tbl_empty_row();	
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
 
  frmvalidator.addValidation("firmapf","dontselect=0","Nie wybrałeś firmy wystawiającej podfakturę");
  frmvalidator.addValidation("kwotapf","req","Nie wpisano kwoty netto podfaktury");
  frmvalidator.addValidation("tpfdw","numerichyphen","Użyłeś niedozwolonych znaków w polu \"Data wystawienia podfaktury\"");
  frmvalidator.addValidation("kwotapf","numericmoney","Użyłeś niedozwolonych znaków w polu \"Kwota netto podfaktury\"\nZnakiem oddzielającym musi być kropka (nie przecinek)");
 
</script>

<?php } ?>

</body>
</html>