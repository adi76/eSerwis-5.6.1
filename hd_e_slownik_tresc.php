<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body onLoad="document.forms[0].elements[0].focus(); ">
<?php 
if ($submit) { 
	$_POST=sanitize($_POST);
	$sql_a = "UPDATE $dbname_hd.hd_slownik_tresci SET tresc_tresc='$_POST[sl_tresc]', tresc_kategoria='$_POST[sl_kat_id]', tresc_podkategoria='$_POST[sl_podkat_id]', tresc_podkategoria_poziom_2='$_POST[sl_sub_podkat_id]' WHERE tresc_id=$_POST[id] LIMIT 1";
	//echo $sql_a;
	
	if (mysql_query($sql_a, $conn)) { 
			?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else {
		?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
	}
} else {
pageheader("Edycja treści w słowniku");
$result = mysql_query("SELECT tresc_id, tresc_tresc, tresc_kategoria, tresc_podkategoria, tresc_podkategoria_poziom_2 FROM $dbname_hd.hd_slownik_tresci WHERE (tresc_id=$id) LIMIT 1", $conn_hd) or die($k_b);

list($temp_id,$temp_tresc,$temp_kat,$temp_podkat,$temp_podkat2)=mysql_fetch_array($result);
starttable();
echo "<form name=ed action=$PHP_SELF method=POST onSubmit=\"return pytanie_zatwierdz_e_slownik('Czy napewno chcesz zapisać zmiany w treści do bazy ?');\">";
tbl_empty_row();
tr_();
	td("100;rt;Treść");
	td_(";;");
		echo "<textarea class=wymagane name=sl_tresc id=sl_tresc cols=60 rows=3>$temp_tresc</textarea>";
	_td();
_tr();
	tr_();
		td("150;rt;Kategoria");
		td_(";;;");	
			echo "<select class=wymagane id=sl_kat_id name=sl_kat_id onChange=\"MakePodkategoriaList_sl(this.options[this.options.selectedIndex].value);\" onKeyUp=\"if (event.keyCode==13) document.getElementById('sl_podkat_id').focus(); \" />\n";
				echo "<option value=''";  if ($temp_kat=='')  echo " SELECTED"; echo "></option>\n";	
				echo "<option value='1'"; if ($temp_kat=='1') echo " SELECTED"; echo ">Konsultacje</option>\n";
				echo "<option value='2'"; if ($temp_kat=='2') echo " SELECTED"; echo ">Awarie</option>\n";
				echo "<option value='6'"; if ($temp_kat=='6') echo " SELECTED"; echo ">Awarie krytyczne</option>\n";
				echo "<option value='7'"; if ($temp_kat=='7') echo " SELECTED"; echo ">Konserwacja</option>\n";
				echo "<option value='3'"; if ($temp_kat=='3') echo " SELECTED"; echo ">Prace zlecone w ramach umowy</option>\n";
				echo "<option value='4'"; if ($temp_kat=='4') echo " SELECTED"; echo ">Prace zlecone poza umową</option>\n";
				echo "<option value='5'"; if ($temp_kat=='5') echo " SELECTED"; echo ">Prace na potrzeby Postdata</option>\n";
			echo "</select>\n";
		_td();
	_tr();

	tr_();
		td("150;rt;Podkategoria");
		td_(";;;");	
			echo "<select class=wymagane id=sl_podkat_id name=sl_podkat_id disabled=true onChange=\"GenerateSubPodkategoriaList(document.getElementById('sl_kat_id').value,document.getElementById('sl_podkat_id').value);\"/>\n";
			echo "<option value=''></option>\n";		
			echo "</select>\n";			
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
	
echo "<input type=hidden name=id value=$temp_id>";
tbl_empty_row();	
endtable();
startbuttonsarea("right");
addbuttons("zapisz","anuluj");
endbuttonsarea();
_form();

?>
<script>
document.getElementById('sl_kat_id').value='<?php echo $temp_kat; ?>';
MakePodkategoriaList_sl(document.getElementById('sl_kat_id').options[document.getElementById('sl_kat_id').options.selectedIndex].value);
document.getElementById('sl_podkat_id').value='<?php echo $temp_podkat; ?>';
GenerateSubPodkategoriaList(document.getElementById('sl_kat_id').value,document.getElementById('sl_podkat_id').value);
document.getElementById('sl_sub_podkat_id').value='<?php echo $temp_podkat2; ?>';
</script>
<?php 
}

?>
</body>
</html>