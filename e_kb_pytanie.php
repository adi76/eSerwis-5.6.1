<?php include_once('header.php'); ?>
<body onLoad="document.getElementById('dnp').select(); document.getElementById('dnp').focus();">
<?php 
if (($submit) && ($co=='status')) {
$nowystatus = $value;

$sql_a = "UPDATE $dbname.serwis_kb_pytania SET kb_pytanie_status=$nowystatus WHERE kb_pytanie_id=$id LIMIT 1";

	if (mysql_query($sql_a, $conn)) 
		{ 
			?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else 
		{
		  ?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
		}
exit;
}

if (($submit) && ($co=='dane')) { 
	$_POST=sanitize($_POST);
	$sql_a = "UPDATE $dbname.serwis_kb_pytania SET kb_kategoria_id=$_POST[dnpk], kb_pytanie_temat='$_POST[dnp]', kb_pytanie_tresc='".nl2br($_POST[dnpo])."', kb_pytanie_status=$_POST[dnps] WHERE kb_pytanie_id=$id LIMIT 1";

	if (mysql_query($sql_a, $conn)) 
		{ 
			?><script>if (opener) opener.location.reload(true); self.close(); </script><?php
	} else 
		{
		  ?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); self.close(); </script><?php
		}
	
} else { ?>
		
<?php

$sql1="SELECT * FROM $dbname.serwis_kb_pytania WHERE kb_pytanie_id=$id LIMIT 1";
$result1 = mysql_query($sql1, $conn) or die($k_b);

$newArray1 = mysql_fetch_array($result1);
$temp_pyt_id 		= $newArray1['kb_pytanie_id'];
$temp_kat_id1		= $newArray1['kb_kategoria_id'];
$temp_temat			= $newArray1['kb_pytanie_temat'];
$temp_tresc			= $newArray1['kb_pytanie_tresc'];
$temp_keywords		= $newArray1['kb_pytanie_keywords'];
$temp_status		= $newArray1['kb_pytanie_status'];	
$temp_file			= $newArray1['kb_pytanie_file_attachement'];	

pageheader("Edycja pytania");

	starttable();
	echo "<form name=add action=$PHP_SELF method=POST onSubmit=\"return pytanie_dodaj_do_kb('Czy zapisać zmiany w wątku ?'); \">";
	echo "<input type=hidden name=co value=$co>";
	echo "<input type=hidden name=id value=$temp_pyt_id>";	

	tbl_empty_row();
	echo "<tr>";
		echo "<td width=120 class=right>Temat wątku</td>";
		echo "<td><input class=wymagane size=70 type=text name=dnp id=dnp value='$temp_temat'>";
		echo "</td>";
	echo "</tr>";

	echo "<tr>";
		echo "<td width=100 class=right>Kategoria wątku</td>";
		echo "<td>";

		echo "<select name=dnpk id=dnpk>";

//if ($poziom==0)	
	$sql="SELECT kb_kategoria_id,kb_kategoria_nazwa FROM $dbname.serwis_kb_kategorie WHERE kb_parent_id=0 ORDER BY kb_kategoria_nazwa ASC";
//if ($poziom==1)	$sql="SELECT kb_kategoria_id,kb_kategoria_nazwa FROM $dbname.serwis_kb_kategorie WHERE kb_parent_id<>0 ORDER BY kb_kategoria_id";

		$result=mysql_query($sql,$conn) or die($k_b);
		while ($dane=mysql_fetch_array($result)) 
		{
			$temp_kat_id 	= $dane['kb_kategoria_id'];
			$temp_kat_nazwa = $dane['kb_kategoria_nazwa'];

			echo "<option ";
			if ($temp_kat_id==$temp_kat_id1) echo "SELECTED ";
			echo "value=$temp_kat_id>$temp_kat_nazwa</option>\n";

			$sql_pk="SELECT kb_kategoria_id,kb_kategoria_nazwa FROM $dbname.serwis_kb_kategorie WHERE kb_parent_id=$temp_kat_id ORDER BY kb_kategoria_nazwa ASC";

			$result_pk=mysql_query($sql_pk,$conn) or die($k_b);
			
			while ($dane_pk=mysql_fetch_array($result_pk)) 
			{
				$temp_pkat_id 	= $dane_pk['kb_kategoria_id'];
				$temp_pkat_nazwa = $dane_pk['kb_kategoria_nazwa'];

				echo "<option ";
				if ($temp_pkat_id==$temp_kat_id1) echo "SELECTED ";
				echo "value=$temp_pkat_id>&nbsp;&nbsp;&nbsp;&nbsp;$temp_pkat_nazwa</option>\n";			}
		}
	
		echo "</select>";
		
		echo "</td>";
	echo "</tr>";	
	echo "<tr>";
		echo "<td width=100 class=righttop>Opis wątku</td>";
		echo "<td><textarea rows=8 cols=50 name=dnpo>".br2nl($temp_tresc)."</textarea></td>";
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
			echo "<option value=1 "; if ($temp_status==1) echo "SELECTED "; echo ">Aktywne</option>\n";
			echo "<option value=0 "; if ($temp_status==0) echo "SELECTED "; echo ">Nieaktywne</option>\n";
			echo "</select>";
		} else {
 		// access control koniec
		// -
		echo "<b>Aktywne</b>";
		echo "<input type=hidden name=dnps value=$temp_status>";
		}
	echo "</td>";
	echo "</tr>";	
	tbl_empty_row();
	endtable();	
	
	startbuttonsarea("right");
	addbuttons("zapisz","anuluj");
	endbuttonsarea();
	
	_form();

?>

<?php } ?>

</body>
</html>