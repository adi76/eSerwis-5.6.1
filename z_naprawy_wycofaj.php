<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php'); 
?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php
if ($submit) {
/*
//echo "$_REQUEST[szid]";
	if ($_POST[szid]=='0') {
		if ($_POST[status]!='7') {
//			$sql_usun_z_serwisu = "DELETE FROM $dbname.serwis_naprawa WHERE naprawa_id=$_POST[uid] LIMIT 1";
			$sql_usun_z_serwisu = "UPDATE $dbname.serwis_naprawa SET naprawa_status=8 WHERE naprawa_id=$_POST[uid] LIMIT 1";
			//	echo "$sql_usun_z_serwisu";
				if (mysql_query($sql_usun_z_serwisu,$conn)) {
				} else {
					?><script>info('Wystąpił błąd podczas usuwania z napraw'); //self.close(); </script><?php
				}
		} else {
			$sql_usun_z_serwisu = "UPDATE $dbname.serwis_naprawa SET naprawa_status=8 WHERE naprawa_id=$_POST[uid] LIMIT 1";	

			if (mysql_query($sql_usun_z_serwisu,$conn)) {
			} else {
					?><script>info('Wystąpił błąd podczas usuwania z napraw'); //self.close(); </script><?php
				}		
		}
		if ($_POST[ewid]!=0) {
			$sql_zmien_status_w_ewid = "UPDATE $dbname.serwis_ewidencja SET ewidencja_status=9 WHERE ewidencja_id=$_POST[ewid] LIMIT 1";
			if (mysql_query($sql_zmien_status_w_ewid,$conn)) {
			} else {
				?><script>info('Wystąpił błąd podczas zmiany statusu'); //self.close(); </script><?php
			}
		}
		?><script>opener.location.reload(true); self.close(); </script><?php
	} else {
		
		if ($_POST[status]!='7') {
//			$sql_usun_z_serwisu = "DELETE FROM $dbname.serwis_naprawa WHERE naprawa_id=$_POST[uid] LIMIT 1";
//			//	echo "$sql_usun_z_serwisu";
//			if (mysql_query($sql_usun_z_serwisu,$conn)) {
//			} else {
//				?><script>info('Wystąpił błąd podczas usuwania z napraw'); self.close(); </script><?php
//			}

			$sql_usun_z_serwisu = "UPDATE $dbname.serwis_naprawa SET naprawa_status=8 WHERE naprawa_id=$_POST[uid] LIMIT 1";	

			if (mysql_query($sql_usun_z_serwisu,$conn)) {
			} else {
					?><script>info('Wystąpił błąd podczas usuwania z napraw'); //self.close(); </script><?php
				}

		} else {
		
			$sql_usun_z_serwisu = "UPDATE $dbname.serwis_naprawa SET naprawa_status=8 WHERE naprawa_id=$_POST[uid] LIMIT 1";	

			if (mysql_query($sql_usun_z_serwisu,$conn)) {
			} else {
					?><script>info('Wystąpił błąd podczas usuwania z napraw'); //self.close(); </script><?php
				}

		}
		
		if ($_POST[serwisowy]=='NIE') {
			$sql_wroc_sprzet_na_magazyn = "UPDATE $dbname.serwis_magazyn SET magazyn_status=0 WHERE magazyn_id=$_POST[szid] LIMIT 1";
			
			if (mysql_query($sql_wroc_sprzet_na_magazyn,$conn)) {
					
					$dddd = Date('Y-m-d H:i:s');
					$sql_t = "INSERT INTO $dbname.serwis_historia VALUES ('','$_POST[szid]','$_POST[tup]','$currentuser','$dddd','zwrócono z','',$es_filia)";

					if (mysql_query($sql_t, $conn)) { 
						//$sql_t1 = "UPDATE $dbname.serwis_magazyn SET magazyn_status='0' WHERE magazyn_id='$_POST[szid]' LIMIT 1";
						//$wykonaj = mysql_query($sql_t1, $conn) or die($k_b);
					} else {
						?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); </script><?php	
					}
				} else {
			?><script>info('Wystąpił błąd podczas aktualizowania magazynu');  </script><?php
			}
		} else {
		
			$sql_usun_z_serwisu = "UPDATE $dbname.serwis_naprawa SET naprawa_sprzet_zastepczy_id=0, naprawa_status=8 WHERE naprawa_id=$_POST[uid] LIMIT 1";	
			if (mysql_query($sql_usun_z_serwisu,$conn)) {
			} else {
					?><script>info('Wystąpił błąd podczas usuwania z napraw'); //self.close(); </script><?php
				}
				
		}
		
		if ($_POST[status]!='7') {

			$sql_znajdz_w_historii = "SELECT historia_id FROM $dbname.serwis_historia WHERE belongs_to=$es_filia and historia_magid=$_POST[szid] ORDER BY historia_data DESC LIMIT 1";
			list($hid)=mysql_fetch_array(mysql_query($sql_znajdz_w_historii,$conn));
		
			$sql_usun_z_historii = "DELETE FROM $dbname.serwis_historia WHERE historia_id=$hid LIMIT 1";
			//	echo "$sql_usun_z_historii<br />";
			if (mysql_query($sql_usun_z_historii,$conn)) {
			} else {
				?><script>info('Wystąpił błąd podczas usuwania historii magazynowej'); self.close(); </script><?php
			}

//		} else {
			$dddd = Date('Y-m-d H:i:s');
			$sql_t = "INSERT INTO $dbname.serwis_historia VALUES ('','$_POST[szid]','$_POST[tup]','$currentuser','$dddd','zwrócono z','',$es_filia)";

			if (mysql_query($sql_t, $conn)) { 
				//$sql_t1 = "UPDATE $dbname.serwis_magazyn SET magazyn_status='0' WHERE magazyn_id='$_POST[szid]' LIMIT 1";
				//$wykonaj = mysql_query($sql_t1, $conn) or die($k_b);
			} else {
				?><script>info('Wystąpił błąd podczas zapisywania zmian do bazy'); </script><?php	
			}

//		}
		
		$sql_zmien_status_w_ewid = "UPDATE $dbname.serwis_ewidencja SET ewidencja_status=9 WHERE ewidencja_id=$_POST[ewid] LIMIT 1";
		if (mysql_query($sql_zmien_status_w_ewid,$conn)) {
		} else {
			?><script>info('Wystąpił błąd podczas aktualizowania ewidencji'); //self.close(); </script><?php
		}
	}
	?><script>opener.location.reload(true); self.close(); </script><?php
*/

} else {
//echo "$_REQUEST[status]";

function toUpper($string) {
	$string = strtr($string,'qwertyuiopasdfghjklzxcvbnm','QWERTYUIOPASDFGHJKLZXCVBNM');
	return (strtr($string, 'ęóąśłżźćń','ĘÓĄŚŁŻŹĆŃ' )); 
}; 

$result = mysql_query("SELECT naprawa_id,naprawa_nazwa,naprawa_model,naprawa_sn,naprawa_status,naprawa_sprzet_zastepczy_id,naprawa_ew_id,naprawa_uwagi,naprawa_ni,naprawa_pobrano_z,naprawa_powod_wycofania_z_serwisu FROM $dbname.serwis_naprawa WHERE (naprawa_id=$id) LIMIT 1", $conn) or die($k_b);
list($temp_id,$temp_nazwa,$temp_model,$temp_sn,$temp_status,$temp_szid,$temp_ewid,$temp_u,$temp_ni,$temp_pobrano_z,$temp_powod)=mysql_fetch_array($result);
pageheader("Wycofanie sprzętu klienta ze swojego serwisu ?");

if ($temp_powod!='') infoheader("<b>Sprzęt klienta:</b><br /><br />Sprzęt pobrano z: <b>$temp_pobrano_z</b><br/>Typ sprzętu: <b>".$temp_nazwa." ".$temp_model."</b><br />SN: <b>".$temp_sn."</b><br />NI: <b>$temp_ni</b><br /><br />Powód wycofania z serwisu:<br /><b>$temp_powod</b>");

if ($temp_powod=='') infoheader("<b>Sprzęt klienta:</b><br /><br />Sprzęt pobrano z: <b>$temp_pobrano_z</b><br/>Typ sprzętu: <b>".$temp_nazwa." ".$temp_model."</b><br />SN: <b>".$temp_sn."</b>");

starttable();

echo "<form name=edu action=utworz_protokol.php method=POST>";

if ($szid!='0') {
	echo "<center>";
	echo "<font color=red><b>Podstawiony sprzęt serwisowy: </b></font>";
	echo "<input class=border0 type=radio name=serwisowy value='TAK' ";
	if ($_REQUEST[serwisowy]=='TAK') echo "CHECKED ";
	echo "onClick='pokaz_tak(this.value); ukryj_szcz(this.value);'>Pozostawiam na UP";
	
	echo "&nbsp;";
	
	echo "<input class=border0 type=radio name=serwisowy value='NIE' ";
	if ($_REQUEST[serwisowy]=='NIE') echo "CHECKED ";
	echo "onClick='pokaz_tak(this.value); pokaz_szcz(this.value);'>Zabieram<br />";
	echo "</center>";
	nowalinia();
} else {
	echo "<input type=hidden name=serwisowy value=''>";
}

echo "<input type=hidden name=id value='$_REQUEST[id]'>";
echo "<input type=hidden name=up value='$_REQUEST[up]'>";
echo "<input type=hidden name=tstatus1 value='$_REQUEST[tstatus1]'>";
echo "<input type=hidden name=unr value='$_REQUEST[unr]'>";
//echo "<input type=hidden name=serwisowy value='$_REQUEST[serwisowy]'>";
//echo "<input type=hidden name=hd_zgl_nr value='$_REQUEST[hd_zgl_nr]'>";

echo "<input type=hidden name=uid value='$temp_id'>";
echo "<input type=hidden name=szid value=$temp_szid>";
echo "<input type=hidden name=tup value='$tup'>";	
echo "<input type=hidden name=ewid value=$temp_ewid>";	
echo "<input type=hidden name=status value=$status>";		

echo "<input type=hidden name=part value='$temp_nazwa'>";
echo "<input type=hidden name=mmodel value='$temp_model'>";
echo "<input type=hidden name=msn value='$temp_sn'>";
echo "<input type=hidden name=mni value='$temp_ni'>";
echo "<input type=hidden name=muwagi value='$temp_u'>";

echo "<input type=hidden name=source value='naprawy-wycofaj'>";
echo "<input type=hidden name=findpion value=1>";
echo "<input type=hidden name=state value='empty'>";

echo "<input type=hidden name=new_upid value='$_REQUEST[new_upid]'>";
echo "<input type=hidden name=ewid_id value=$_REQUEST[ewid_id]>";	

echo "<input type=hidden name=powod_wycofania value='$temp_powod'>";	

if ($temp_szid>0) {
	echo "<input type=hidden name=c_4 value='on'>";
} else {
	echo "<input type=hidden name=c_4 value='off'>";
}
echo "<input type=hidden name=c_3 value='on'>";
echo "<input type=hidden name=wstecz value='1'>";

echo "<span id=szcz name=szcz style='display:";

if ($_REQUEST[serwisowy]=='NIE') { echo ""; } 
if ($_REQUEST[serwisowy]!='NIE') { echo "none"; } 

echo "'>";
$result = mysql_query("SELECT magazyn_id,magazyn_nazwa,magazyn_model,magazyn_sn, magazyn_ni,magazyn_uwagi FROM $dbname.serwis_magazyn WHERE (magazyn_id=$temp_szid) LIMIT 1", $conn) or die($k_b);
list($temp_mid,$temp_nazwa1,$temp_model1,$temp_sn1,$temp_ni1,$temp_u1)=mysql_fetch_array($result);

infoheader("<b>Sprzęt serwisowy:</b><br/><br/>Typ sprzętu: ".$temp_nazwa1." ".$temp_model1."<br />SN: ".$temp_sn1.", NI: ".$temp_ni1."");

echo "</span>";
echo "<input type=hidden name=sz value=$temp_mid>";

starttable();
	tr_();
		td("150;r;Nr zgłoszenia Helpdesk");
		td_(";;");	
			if ($_REQUEST[hd_zgl_nr]>0) {
				echo "<input type=hidden name=hd_zgl_nr id=hd_zgl_nr value='$_REQUEST[hd_zgl_nr]'>";
				echo "<b>$_REQUEST[hd_zgl_nr]</b>";
				//echo "<input class=wymagane type=text id=hd_zgl_nr name=hd_zgl_nr value='$_REQUEST[hd_zgl_nr]' size=10 maxlength=10 onKeyPress=\"return filterInput(1, event, false); \" onchange=\"reload1(this.form); this.focus();\">";
			} else {
				echo "<input class=wymagane type=text id=hd_zgl_nr name=hd_zgl_nr value='' size=10 maxlength=10 onKeyPress=\"return filterInput(1, event, false); \" onchange=\"reload1(this.form); this.focus();\">";
			}
			//echo "&nbsp;&nbsp;<input type=button class=buttons value='Szczegóły zgłoszenia' onClick=\"PokazZgloszenie(document.getElementById('hd_zgl_nr').value); \" >";
			
			$block = 1;
			if ($_REQUEST[hd_zgl_nr]>0) {
				//echo "SELECT zgl_id FROM $dbname_hd.hd_zgloszenie WHERE (zgl_nr='$_REQUEST[hd_zgl_nr]') and (belongs_to=$es_filia) and (zgl_widoczne=1) LIMIT 1";
				$r4 = mysql_query("SELECT zgl_id, zgl_komorka,zgl_kategoria,zgl_podkategoria,zgl_status FROM $dbname_hd.hd_zgloszenie WHERE (zgl_nr='$_REQUEST[hd_zgl_nr]') and (belongs_to=$es_filia) and (zgl_widoczne=1) LIMIT 1", $conn_hd) or die($k_b);
				list($z_id,$temp_komorka,$temp_kategoria,$temp_podkategoria,$temp_status)=mysql_fetch_array($r4);
				if ($z_id==$_REQUEST[hd_zgl_nr]) $block=0;
			}
			
			if ($block==0) echo "&nbsp;&nbsp;<input type=button class=buttons value='Szczegóły zgłoszenia' onClick=\"PokazZgloszenie(document.getElementById('hd_zgl_nr').value); \">";
			
		_td();
	_tr();
	
	if ($block==0) {
		echo "<tr>";
			echo "<td></td>";
			echo "<td>";
					$r1 = mysql_query("SELECT hd_kategoria_opis FROM $dbname_hd.hd_kategoria WHERE (hd_kategoria_nr='$temp_kategoria') LIMIT 1", $conn_hd) or die($k_b);
					list($kat_opis)=mysql_fetch_array($r1);
					$r2 = mysql_query("SELECT hd_podkategoria_opis FROM $dbname_hd.hd_podkategoria WHERE (hd_podkategoria_nr='$temp_podkategoria') LIMIT 1", $conn_hd) or die($k_b);
					list($podkat_opis)=mysql_fetch_array($r2);
					$r2 = mysql_query("SELECT hd_status_opis FROM $dbname_hd.hd_status WHERE (hd_status_nr='$temp_status') LIMIT 1", $conn_hd) or die($k_b);
					list($status_opis)=mysql_fetch_array($r2);
					
					okheader("<font style='font-weight:normal;'>".$temp_komorka."<br />".$kat_opis." -> ".$podkat_opis."<br />Status zgłoszenia: <b>".$status_opis."</b></font>");
					
					if ($_REQUEST[new_upid]!='') {
						
						$sql445="SELECT up_nazwa, up_pion_id FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) and (serwis_komorki.up_id = $_REQUEST[new_upid]) LIMIT 1";
						$result445 = mysql_query($sql445, $conn) or die($k_b);
						
						while ($newArray445 = mysql_fetch_array($result445)) {
							
							$temp_nazwa				= $newArray445['up_nazwa'];
							$temp_pion_id			= $newArray445['up_pion_id'];
							
							$sql444="SELECT pion_nazwa FROM $dbname.serwis_piony WHERE pion_id=$temp_pion_id LIMIT 1";
							$result444=mysql_query($sql444,$conn) or die($k_b);
							$wynik = mysql_fetch_array($result444);
							$pionnazwa = $wynik['pion_nazwa'];
							
							$komorka_wybrana = toUpper($pionnazwa." ".$temp_nazwa);
						}				
					} else {
						$komorka_wybrana = '';
					}					
					
					if ((trim(toUpper($temp_komorka))!=trim(toUpper($komorka_wybrana))) && ($komorka_wybrana!='')) { 
						errorheader("<font style='font-weight:normal;'>Komórka ze zgłoszenia nr $_REQUEST[hd_zgl_nr] jest niezgodna z tą dla której chcesz wykonać wycofanie sprzętu</font>"); 
						$block=2;
					}
					
					
			echo "</td>";
		_tr();
	}
	
	if (($block==1) && ($_REQUEST[hd_zgl_nr]!='')) {
		echo "<tr>";
			echo "<td></td>";
			echo "<td>";
				errorheader('Zgłoszenie o podanym numerze nie istnieje lub jest przypisane do innej filii/oddziału');
			echo "</td>";
		_tr();
	}
	
	tbl_empty_row();
	
endtable();
nowalinia();

startbuttonsarea("right");
if ($szid!='0') { 
	echo "<span id=pokaztak name=pokaztak style='display:";
	if ($_REQUEST[serwisowy]=='NIE') { echo ""; } 
	if ($_REQUEST[serwisowy]!='NIE') { echo "none"; } 
	echo "'>";
}

//	if ($block==0) {
		echo "<input class=buttons type=submit name=submit id=dalej value=Dalej "; 
		if (($block>1) && ($_REQUEST[hd_zgl_nr]!='')) { echo " style='display:none' "; }
		echo ">";
	//}
	
if ($szid!='0') echo "</span>";
addbuttons("anuluj");
endbuttonsarea();
_form();
}
?>
<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("edu");
  frmvalidator.addValidation("hd_zgl_nr","req","Nie podano numeru zgłoszenia Helpdesk");  
</script>
</body>
</html>