<?php include_once('header.php'); ?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php
include('inc_encrypt.php');
$accessLevels = array("1", "9");
if(array_search($es_prawa, $accessLevels)>-1){
} else { 
	echo "</body>"; 
	?>
	<script>alert("Brak uprawnień do tej funkcji"); self.close(); </script>
	<?php
	exit; 
}

if ($submit) { 

?>
<script>ShowWaitingMessage('Trwa zapisywanie danych...', 'Saving1');</script>
<?php 

if ($_SESSION[pozycje_dodane_do_faktury]!=1) {

	$_POST=sanitize($_POST);
	$dddd = Date('Y-m-d');
	$dddd1 = Date('Y-m-d H:i:s');
	$pocz=$_POST[tpoczatek]+1;
	$ile=$_POST[tilosc1];
	$granica = $pocz + $ile;

	
	$r3 = mysql_query("SELECT rola_nazwa FROM $dbname.serwis_slownik_rola WHERE rola_id=$_POST[trodzaj]", $conn) or die($k_b);
	list($rodzaj)=mysql_fetch_array($r3);
			
	
	while ($pocz <= $granica-1) {
		$pozycja = $_POST['poz'.$pocz.''];
		$nazwa = $_POST['tnazwa'.$pocz.''];
		$ile = $_POST['tile'.$pocz.''];
		$sn = $_POST['tsn'.$pocz.''];
		$uwaga = $_POST['tuwaga'.$pocz.''];
		
		$cena1 = $_POST['tcena'.$pocz.''];
		$cena1odsp = $_POST['tcenaodsp'.$pocz.''];
		
		$cenaodsp = str_replace(',','.',$cena1odsp);
		$cena = str_replace(',','.',$cena1);
		
//		echo "$cena				$cenaodsp<br />";
		
		$cena_cr = crypt_md5($cena,$key);
		$cenaodsp_cr = crypt_md5($cenaodsp,$key);
		
		$sql_t = "INSERT INTO $dbname.serwis_faktura_szcz values ('', '$_POST[tidf]','$pozycja','$nazwa','1','$sn','$cena_cr','-1',$es_filia,'','off','$cenaodsp_cr','$rodzaj','$uwaga','$_POST[rodzaj_sprzedazy]',$_POST[gwarancja])";
		$result = mysql_query($sql_t, $conn) or die($k_b);
		$pocz++;
	}	

	$_SESSION[pozycje_dodane_do_faktury]=1;
	
	okheader("Pomyślnie dodano nowe pozycje do faktury");

	} // $_SESSION
	
	$sql = "SELECT * FROM $dbname.serwis_faktura_szcz WHERE pozycja_nr_faktury=$_POST[tidf] and (pozycja_status!='-2')";
	$result = mysql_query($sql, $conn) or die($k_b);
	$il_pozycji = mysql_num_rows($result);

	if ($il_pozycji>0) {
		$sql1="SELECT * FROM $dbname.serwis_faktury WHERE ((belongs_to=$es_filia) and (faktura_id=$_POST[tidf]))";
		$result1 = mysql_query($sql1, $conn) or die($k_b);
		while ($newArray1 = mysql_fetch_array($result1)) {
			$temp_id1  			= $newArray1['faktura_id'];
			$temp_numer1		= $newArray1['faktura_numer'];
			$temp_data1			= $newArray1['faktura_data'];
			$temp_dostawca1		= $newArray1['faktura_dostawca'];
			$temp_koszty1_cr	= $newArray1['faktura_koszty_dodatkowe'];	
			$temp_fnrz			= $newArray1['faktura_nr_zamowienia'];	
			$temp_statusf		= $newArray1['faktura_status'];
			$temp_knf_cr		= $newArray1['faktura_kwota_netto'];
		}
		
//		startbuttonsarea("center");

		$temp_knf = decrypt_md5($temp_knf_cr,$key);

		
//		endbuttonsarea();
		
		pageheader("Pozycje na fakturze nr ".$temp_numer1."");

		

		starttable();
		th("30;c;LP|;;Nazwa towaru / usługi<br />Rodzaj sprzedaży|;c;Ilość|;;Numer seryjny<br />Gwarancja|;r;Cena netto<br />z faktury;|;c;Opcje",$es_prawa);
		$j = 1;
		$kwotarazem=0;
		while ($newArray = mysql_fetch_array($result)) {
			$temp_id  			= $newArray['pozycja_id'];
			$temp_nrfaktury		= $newArray['pozycja_nr_faktury'];
			$temp_numer			= $newArray['pozycja_numer'];
			$temp_nazwa			= $newArray['pozycja_nazwa'];
			$temp_ilosc			= $newArray['pozycja_ilosc'];
			$temp_sn			= $newArray['pozycja_sn'];
			$temp_cenanetto_cr	= $newArray['pozycja_cena_netto'];
			$temp_status		= $newArray['pozycja_status'];
			$temp_datasprzedazy	= $newArray['pozycja_datasprzedazy'];
			$temp_uwagi			= $newArray['pozycja_uwagi'];
			$temp_rs			= $newArray['pozycja_rodzaj_sprzedazy'];
			$temp_belongs_to	= $newArray['belongs_to'];
			$temp_gw			= $newArray['pozycja_gwarancja'];
			
			$temp_cenanetto 	= decrypt_md5($temp_cenanetto_cr,$key);
			$kwotarazem=$kwotarazem+$temp_cenanetto;
			tbl_tr_highlight($j);
				td("30;c;".$j."");
				td_(";;");
					echo "$temp_nazwa";
					if ($pokazpoczatekuwag==1) pokaz_uwagi($temp_uwagi,$iloscznakowuwag,"newWindow(480,265,'p_towar_uwagi.php?id=$temp_id')");
					echo "<br />";
					echo "<font color=grey>$temp_rs</font>";
				_td();
				td(";c;".$temp_ilosc."");
				
				echo "<td>";
					if ($temp_sn!='') { echo "$temp_sn"; } else { echo "-"; }				
					if ($temp_gw!=0) echo "<br /><font color=grey>$temp_gw m-ce/cy</font>";
				echo "</td>";
				
				td(";r;".$temp_cenanetto." zł");
				
				echo "<td class=center>";
				
				if (($temp_status==0) || ($temp_status==-1) || ($temp_status==5)) {
				echo "<a title=' Popraw pozycję $temp_nazwa na fakturze'><input class=imgoption type=image src=img/edit.gif  onclick=\"newWindow(800,600,'e_faktura_pozycja.php?id=$temp_id&trodzaj=".urlencode($temp_rs)."'); return false;\"></a>";
				}
				if (($temp_status==0) || ($temp_status==-1)) {
				echo "<a title=' Edytuj uwagę dla tego towaru / usługi '><input class=imgoption type=image src=img/edit_comment.gif onclick=\"newWindow(480,245,'e_towar_uwagi.php?id=$temp_id'); return false;\"></a>";
				} else echo "<a title=' Czytaj uwagi o towarze '><input class=imgoption type=image src=img/comment.gif onclick=\"newWindow(480,265,'p_towar_uwagi.php?id=$temp_id'); return false;\"></a>";
			// -
			// access control 
		
			$accessLevels = array("9");
			if(array_search($es_prawa, $accessLevels)>-1){
				if (($temp_status==-1) && ($temp_belongs_to==$es_filia)) {
					echo "<a title=' Usuń pozycję $temp_nazwa o numerze seryjnym $temp_sn z faktury $temp_numer1 '><input class=imgoption type=image src=img/delete.gif  onclick=\"newWindow($dialog_window_x,$dialog_window_y,'u_faktura_pozycja.php?id=$temp_id')\"></a>";
					} else echo "&nbsp;";
			} else echo "&nbsp;";
			// koniec accesky 
				echo "</td>";
	
			_tr();
			$j++;
		}
		endtable();
		startbuttonsarea("right");
		echo "<span style='float:left'>";
		addlinkbutton("'Dodaj nowe pozycje do faktury'","d_faktura_pozycja.php?id=$tidf");
		echo "</span>";
	
		echo "Wpisana kwota netto faktury: <b>".correct_currency($temp_knf)." zł</b>&nbsp;<br /> ";
		echo "Dotychczasowa wartość faktury: <b>".correct_currency($kwotarazem)." zł</b>&nbsp;<br />";
		
		$roznica = correct_currency($temp_knf) - correct_currency($kwotarazem);
			
		$roznica1 = abs($roznica*100);
		
			$o_grosz = 0;
			if ((strlen($roznica1)==1) && (substr($roznica1,0,1)=='1')){
				$o_grosz = 1;
				$o_wiecej = 0;
			} else { 
				if (substr($roznica1,0,1)!='0') {
					$o_grosz = 0; 
					$o_wiecej = 1; 
				} else {
					$o_grosz = 0; 
					if ($roznica1==0) {
						$o_wiecej = 0; 
					} else {
						$o_wiecej = 1; 
					}
					}
			}
		
		if ($o_grosz==1) { $set_color = '#FF5500'; } else { $set_color = 'red'; }

		$r1 = abs($roznica*100);
		if (($r1>0) && ($r1<=10)) { $set_color = '#FF5500'; }

		if ($roznica>0) echo "<br /><font color=$set_color>Wartość wpisanych pozycji na fakturze jest mniejsza niż wpisanej kwoty faktury netto o: <b>".correct_currency($roznica)." zł</b>&nbsp;</font><br /><br />";
		if ($roznica==0) echo "<br /><b><font color=green>Wpisana kwota netto faktury jest zgodna z wartością wpisanych pozycji</b></font>&nbsp;<br /><br />";
		if ($roznica<0) echo "<br /><font color=$set_color>Wartość wpisanych pozycji przekracza wpisaną kwotę faktury netto o: <b>".correct_currency(abs($roznica))." zł</b>&nbsp;</font><br /><br />";
	
		//addclosewithreloadbutton("'Zakończ dodawanie pozycji'");
		echo "<input class=buttons type=button onClick=\"if (confirm('Czy przejść do listy niezatwierdzonych faktur ?')) { if (opener)  opener.location.href='z_faktury.php?showall=0'; } else { if (opener) opener.location.reload(true); } self.close();\" value='Zakończ dodawanie pozycji'>";
		
		endbuttonsarea();
	}
	?>
	<script>HideWaitingMessage('Saving1');</script>
	<?php 
} else {
$_POST=sanitize($_POST);
$pocz=$_POST[tcurr];
$pocz++;
$ile=$_POST[tilosc];
$sql="SELECT * FROM $dbname.serwis_faktury WHERE ((belongs_to=$es_filia) and (faktura_id=$_POST[tid])) LIMIT 1";
$result = mysql_query($sql, $conn) or die($k_b);
while ($newArray = mysql_fetch_array($result)) {
	$temp_id  			= $newArray['faktura_id'];
	$temp_numer			= $newArray['faktura_numer'];
	$temp_data			= $newArray['faktura_data'];
	$temp_dostawca		= $newArray['faktura_dostawca'];
	$temp_fnrz			= $newArray['faktura_nr_zamowienia'];	
	$temp_koszy			= $newArray['faktura_koszty_dodatkowe'];

	$temp_knf_cr			= $newArray['faktura_kwota_netto'];
}
pageheader("Dodawanie nowej pozycji do faktury");
?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
startbuttonsarea("center");

$temp_knf = decrypt_md5($temp_knf_cr,$key);

if ($temp_numer=='') $temp_numer='<i><font color=grey>nie wpisana</font></i>';
echo "Numer faktury: <b>$temp_numer</b><br />Data wystawienia: <b>$temp_data</b><br />Firma wystawiająca fakturę: <b>$temp_dostawca</b><br />Numer zamówienia: <b>$temp_fnrz</b><br />Wpisana kwota netto faktury: <b>".correct_currency($temp_knf)." zł</b><br /> ";
endbuttonsarea();
starttable();
echo "<form name=addf action=$PHP_SELF method=POST>";
echo "<input type=hidden name=tpoczatek value=$_POST[tcurr]>";
echo "<input type=hidden name=tilosc1 value=$_POST[tilosc]>";
echo "<input type=hidden name=tidf value=$_POST[tid]>";
echo "<input type=hidden name=trodzaj value='$_POST[trodzaj]'>";
echo "<input type=hidden name=rodzaj_sprzedazy value='$_POST[rodzaj_sprzedazy]'>";

th("30;c;LP|;;Nazwa towaru / usługi<br />Rodzaj sprzedaży|;;Numer seryjny<br />Gwarancja|;r;Cena netto<br />z faktury;19|;;Uwagi",$es_prawa);
$i = 0;	
$ilosc = $ile+1+$_POST[tcurr];
while ($pocz < $ile+1+$_POST[tcurr]) {
	tbl_tr_highlight($i);
		td(";c;".$pocz."");
		echo "<input type=hidden name=poz$pocz value=$pocz>";
		td_(";;");
			echo "<input type=text name=tnazwa$pocz value='".cleanup($_POST[tnazwa6])."' size=40 maxlength=100 />";
			echo "<br />";
			echo "&nbsp;<font color=grey>$_POST[rodzaj_sprzedazy]</font>";
			
		_td();
		td_(";;");
			echo "<input style='text-align:left; font-size: 9px;' size=30 maxlength=30 type=text name=tsn$pocz id=tsn$pocz onBlur=\"UsunMalpy(this);\" />"; 
			if ($_REQUEST[tgwarancja6]!=0) echo "<br /><font color=grey>".$_REQUEST[tgwarancja6]." m-ce/cy</font>";
		_td();
		$accessLevels = array("1","9");
		if (array_search($es_prawa, $accessLevels)>-1) {
			$post_cena=$_POST['tcena6'];
			$kwotakoncowa = correct_currency($post_cena);

			$post_cenaodsp=$_POST['uco'];
			$cenaodsp_correct = correct_currency($post_cenaodsp);
			
			if ($cenaodsp_correct=='0.00') $cenaodsp_correct = $kwotakoncowa;
			
			td_(";r;");
				echo "<input type=hidden name=tcena$pocz value='$kwotakoncowa'>";
				echo "<input type=hidden name=tcenaodsp$pocz value='$cenaodsp_correct'>";
				echo "$kwotakoncowa zł";
			_td();
		} else td(";;");
		td_(";;");
			echo "<textarea name=tuwaga$pocz style='font-size:11px;' cols=32 rows=1></textarea>";
		_td();
	_tr();	
	
	$pocz++;
	$i++;
}
endtable();
startbuttonsarea("right");

session_register('pozycje_dodane_do_faktury');
$_SESSION[pozycje_dodane_do_faktury]=0;

echo "<span style='float:left'>";
echo "&nbsp;";
addbackbutton("Wpisz towar / usługę od nowa");
echo "</span>";

echo "<input type=hidden name=gwarancja value=$_REQUEST[tgwarancja6]>";

addbuttons("zapisz","anuluj");
endbuttonsarea();
_form();
}
?>
<script>HideWaitingMessage();</script>
</body>
</html>