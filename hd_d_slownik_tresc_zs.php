<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body onLoad="document.forms[0].elements[0].focus(); 
document.getElementById('sl_tresc').value=opener.document.getElementById('zs_wcz').value; document.getElementById('sl_kat_id').value=<?php echo $_GET[p2];?>;  MakePodkategoriaList_sl2('<?php echo $_GET[p2];?>'); GenerateSubPodkategoriaList(opener.document.getElementById('kat_id').value,opener.document.getElementById('podkat_id').value); document.getElementById('sl_sub_podkat_id').value=opener.document.getElementById('sub_podkat_id').value;">
<?php 
if ($submit) { 
	$_POST=sanitize($_POST);
	$seryjne=0;
	if ($_POST[up_id]==0) $seryjne=1;
	$sql_a = "INSERT INTO $dbname_hd.hd_slownik_tresci values ('', '$_POST[sl_tresc]','$currentuser','$_POST[sl_kat_id]','$_POST[sl_podkat_id]','$_POST[sl_sub_podkat_id]',$es_filia)";
	if (mysql_query($sql_a, $conn_hd)) { 
	
	if ($_GET[akcja]=='manage') {
	?><script>if (opener) opener.location.reload(true);</script><?php 
	}
			?><script>self.close();</script><?php
	} else {
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
	}
} else {
pageheader("Dodawanie nowej treści do słownika");
//$result = mysql_query("SELECT hd_komorka_pracownicy_id,hd_serwis_komorka_id,hd_komorka_pracownicy_nazwa,hd_komorka_pracownicy_telefon FROM $dbname_hd.hd_komorka_pracownicy WHERE (hd_komorka_pracownicy_id=$id) LIMIT 1", $conn_hd) or die($k_b);

//list($temp_id,$temp_komorka_id,$temp_osoba,$temp_telefon)=mysql_fetch_array($result);
starttable();
echo "<form name=add action=$PHP_SELF method=POST>";
tbl_empty_row();
tr_();
	td("100;rt;Treść");
	td_(";;");
		echo "<textarea class=wymagane name=sl_tresc id=sl_tresc cols=60 rows=3>";
		echo "</textarea>";
	_td();
_tr();
	tr_();
		td("150;rt;Kategoria");
		td_(";;;");	
			echo "<select class=wymagane id=sl_kat_id name=sl_kat_id onChange=\"MakePodkategoriaList_sl2(this.options[this.options.selectedIndex].value,'$_REQUEST[p3]'); GenerateSubPodkategoriaList(document.getElementById('sl_kat_id').value,document.getElementById('sl_podkat_id').value); \" onKeyUp=\"if (event.keyCode==13) document.getElementById('sl_podkat_id').focus(); \" />\n";
				//echo "<option value=''></option>\n";	
				echo "<option value='1'>Konsultacje</option>\n";
				echo "<option value='2'>Awarie</option>\n";
				echo "<option value='6'>Awarie krytyczne</option>\n";
				echo "<option value='3'>Prace zlecone w ramach umowy</option>\n";
				echo "<option value='7'>Konserwacja</option>\n";
				echo "<option value='4'>Prace zlecone poza umową</option>\n";
				echo "<option value='5'>Prace na potrzeby Postdata</option>\n";
			echo "</select>\n";
		_td();
	_tr();

	tr_();
		td("150;rt;Podkategoria");
		td_(";;;");	

			echo "<select class=wymagane id=sl_podkat_id name=sl_podkat_id disabled=true onChange=\"GenerateSubPodkategoriaList(document.getElementById('sl_kat_id').value,document.getElementById('sl_podkat_id').value);\" />\n";
			echo "<option value=''></option>\n";		
			echo "</select>\n";		

/*			
		$result44 = mysql_query("SELECT hd_podkategoria_nr,hd_podkategoria_opis FROM $dbname_hd.hd_podkategoria WHERE (hd_podkategoria_wlaczona=1)", $conn_hd) or die($k_b);
			$count_rows = mysql_num_rows($result44);
			echo "<select class=wymagane id=sl_podkat_id name=sl_podkat_id />\n";
			echo "<option value=''></option>\n";	
			while (list($temp_nr,$temp_opis) = mysql_fetch_array($result44)) {
				echo "<option value='$temp_nr' ";
				echo ">$temp_opis</option>\n"; 
			}
			echo "</select>\n";
*/

/*
			echo "<select class=wymagane id=sl_podkat_id name=sl_podkat_id/>\n";
			echo "<option value=''></option>\n";		
			echo "</select>\n";	
			*/
		_td();
	_tr();

	tr_();
		td("140;rt;Podkategoria (poziom 2)");
		td_(";;;");	
			echo "<select class=wymagane id=sl_sub_podkat_id name=sl_sub_podkat_id />\n";
			echo "<option value=''></option>\n";
			echo "</select>\n";
		_td();
	_tr();		
	
tbl_empty_row();	
endtable();
startbuttonsarea("right");
addbuttons("zapisz","anuluj");
endbuttonsarea();
_form();
?>
<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("add");
  frmvalidator.addValidation("sl_tresc","req","Nie podano treści");
</script>	
<?php } ?>
</body>
</html>