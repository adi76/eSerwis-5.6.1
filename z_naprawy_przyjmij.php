<?php 
include_once('header.php'); 
include_once('cfg_helpdesk.php');
?>
<body OnLoad="document.forms[0].elements[0].focus();">
<?php
//print_r">>>".$_SESSION['wc_sprzetu_dla_zgloszenia_nr_42446'];

$kierownik = ($kierownik_nr==$es_nr);
if (($wylacz_przyjmowanie_uszk_sprzetu_z_menu_glownego==0) || ($_REQUEST[przyjmij]==1) || ($kierownik==true)) {

$_SESSION[protokol_dodany_do_bazy]=0;

$_SESSION[numer_id_dopisanego_do_tabeli_serwis_historia]=0;
$_SESSION[numer_id_dopisanego_do_tabeli_serwis_naprawa]=0;

$_SESSION[wykonaj_naprawy_przyjecie]=0;

if (($kierownik==true) && ($_REQUEST[hd_zgl_nr]=='')) errorheader('Dostęp do tej części systemu dla kierowników jest włączony');

$cat=$_POST['cat'];
pageheader("Przyjęcie uszkodzonego sprzętu do serwisu");

if (($_REQUEST[new_upid]=='') && ($_REQUEST[hd_zgl_nr]!='')) {
	$result44 = mysql_query("SELECT zgl_komorka FROM $dbname_hd.hd_zgloszenie WHERE zgl_nr=$_REQUEST[hd_zgl_nr] LIMIT 1", $conn) or die($k_b);
	list($komorkanazwa) = mysql_fetch_array($result44);
	errorheader("Brak bazie komórek brakuje komórki o nazwie <b>".$komorkanazwa."</b><br />Po dokonaniu odpowiednich poprawek w bazie komórek, będzie można wykonać tą czynność");
	startbuttonsarea("right");
	addbuttons("zamknij");
	endbuttonsarea();
	exit;
}

?><script>ShowWaitingMessage();</script><?php ob_flush(); flush();
if (($_REQUEST[new_upid]=='') && ($_REQUEST[hd_zgl_nr]!='')) {
	if ($Podst_Inf_o_zgl) include_once('hd_inf_podstawowe.php');
}

starttable();
echo "<form name=addt action=z_naprawy_wybierz.php method=POST>";
tbl_empty_row(2);
	tr_();
		td(";r;Sprzęt pobrano z");
		td_(";;;");
			if ($_REQUEST[tresc_zgl]=='') {	
				$result44 = mysql_query("SELECT serwis_komorki.up_id, serwis_komorki.up_nazwa, serwis_piony.pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) ORDER BY serwis_piony.pion_nazwa,up_nazwa", $conn) or die($k_b);
				$count_rows = mysql_num_rows($result44);				
				echo "<select ";
				if ($_REQUEST[tresc_zgl]!='') echo " DISABLED ";
				echo " class=wymagane name=new_upid onkeypress='return handleEnter(this, event);' onchange=\"reload_z_naprawy_przyjmij(this.form)\">\n"; 					 				
				echo "<option value=''>Wybierz z listy...</option>\n";	
				while (list($temp_id,$temp_nazwa,$temp_pion) = mysql_fetch_array($result44)) {
					echo "<option value=$temp_id ";
					if ($new_upid==$temp_id) echo "SELECTED";
					if ($_REQUEST[new_upid1]==$temp_id) echo "SELECTED";
					echo ">$temp_pion $temp_nazwa</option>\n"; 
				}
				echo "</select>\n"; 
			} else {
				echo "<input type=hidden name=new_upid value=$_REQUEST[new_upid]>";
				$result44 = mysql_query("SELECT serwis_komorki.up_id, serwis_komorki.up_nazwa, serwis_piony.pion_nazwa FROM $dbname.serwis_komorki, $dbname.serwis_piony WHERE (belongs_to=$es_filia) and (serwis_komorki.up_pion_id=serwis_piony.pion_id) and (serwis_komorki.up_active=1) and (serwis_komorki.up_id=$_REQUEST[new_upid]) ORDER BY serwis_piony.pion_nazwa,up_nazwa", $conn) or die($k_b);
				list($temp_id,$temp_nazwa,$temp_pion) = mysql_fetch_array($result44);
				echo "<b>$temp_pion $temp_nazwa</b>";
				$pelna_nazwa_up = $temp_pion ." ".$temp_nazwa;
			}
	
		_td();
	_tr();
	tr_();
		td(";r;Typ sprzętu");
		td_(";;;");
			if ($_REQUEST[from]!='hd') {
				$result = mysql_query("SELECT rola_id,rola_nazwa FROM $dbname.serwis_slownik_rola WHERE rola_do_ewidencji=1 ORDER BY rola_nazwa", $conn) or die($k_b);
				$count_rows = mysql_num_rows($result);
				echo "<select class=wymagane id=typid name=typid onkeypress='return handleEnter(this, event);' onchange=\"reload_z_naprawy_przyjmij(this.form)\">\n";
				echo "<option value=''>Wybierz z listy...";
				while (list($temp_id,$temp_nazwa) = mysql_fetch_array($result)) {
					echo "<option value=$temp_id";
					if ($temp_id==$typid) echo " SELECTED";
					if (($_REQUEST[hd_podkategoria_nr]=='3') && ($temp_id==1)) { echo " SELECTED"; }
					if (($_REQUEST[hd_podkategoria_nr]=='4') && ($temp_id==2)) { echo " SELECTED"; }				
					echo ">$temp_nazwa</option>\n"; 
				}
				echo "</select>\n"; 
			} else {
			
				if ($_REQUEST[hd_podkategoria_nr]=='7') {
					$result = mysql_query("SELECT rola_id,rola_nazwa FROM $dbname.serwis_slownik_rola WHERE (rola_do_ewidencji=1) and ((rola_id=1) or (rola_id=18) or (rola_id=2)) ORDER BY rola_nazwa", $conn) or die($k_b);
					$count_rows = mysql_num_rows($result);
					echo "<select class=wymagane name=typid onkeypress='return handleEnter(this, event);' onchange=\"reload_z_naprawy_przyjmij(this.form)\">\n";
					echo "<option value=''>Wybierz z listy...";
					while (list($temp_id,$temp_nazwa) = mysql_fetch_array($result)) {
						echo "<option value=$temp_id";
						if ($temp_id==$typid) echo " SELECTED";
						echo ">$temp_nazwa</option>\n"; 
					}
					echo "</select>\n"; 
				}
				
				if (($_REQUEST[hd_podkategoria_nr]=='3') || ($_REQUEST[hd_podkategoria_nr]=='4')) {
					$result = mysql_query("SELECT rola_id,rola_nazwa FROM $dbname.serwis_slownik_rola WHERE (rola_do_ewidencji=1) and ((rola_id=1) or (rola_id=18) or (rola_id=7) or (rola_id=2)) ORDER BY rola_nazwa", $conn) or die($k_b);
					$count_rows = mysql_num_rows($result);
					echo "<select class=wymagane name=typid onkeypress='return handleEnter(this, event);' onchange=\"reload_z_naprawy_przyjmij(this.form)\">\n";
					echo "<option value=''>Wybierz z listy...";
					while (list($temp_id,$temp_nazwa) = mysql_fetch_array($result)) {
						echo "<option value=$temp_id";
						if ($temp_id==$typid) echo " SELECTED";
						echo ">$temp_nazwa</option>\n"; 
					}
					echo "</select>\n"; 
				}

				if (($_REQUEST[hd_podkategoria_nr]=='2') || ($_REQUEST[hd_podkategoria_nr]=='5') || ($_REQUEST[hd_podkategoria_nr]=='0')) {
					$result = mysql_query("SELECT rola_id,rola_nazwa FROM $dbname.serwis_slownik_rola WHERE (rola_do_ewidencji=1) ORDER BY rola_nazwa", $conn) or die($k_b);
					$count_rows = mysql_num_rows($result);
					echo "<select class=wymagane id=typid name=typid onkeypress='return handleEnter(this, event);' onchange=\"reload_z_naprawy_przyjmij(this.form)\">\n";
					echo "<option value=''>Wybierz z listy...";
					while (list($temp_id,$temp_nazwa) = mysql_fetch_array($result)) {
						echo "<option value=$temp_id";
						if ($temp_id==$typid) echo " SELECTED";
						echo ">$temp_nazwa</option>\n"; 
					}
					echo "</select>\n"; 
				}				
				
				if ($_REQUEST[hd_podkategoria_nr]=='9') {
					$result = mysql_query("SELECT rola_id,rola_nazwa FROM $dbname.serwis_slownik_rola WHERE (rola_do_ewidencji=1) and (rola_id>2) and (rola_id<>18) ORDER BY rola_nazwa", $conn) or die($k_b);
					$count_rows = mysql_num_rows($result);
					echo "<select class=wymagane name=typid onkeypress='return handleEnter(this, event);' onchange=\"reload_z_naprawy_przyjmij(this.form)\">\n";
					echo "<option value=''>Wybierz z listy...";
					while (list($temp_id,$temp_nazwa) = mysql_fetch_array($result)) {
						echo "<option value=$temp_id";
						if ($temp_id==$typid) echo " SELECTED";
						echo ">$temp_nazwa</option>\n"; 
					}
					echo "</select>\n"; 
				}
			}
		_td();
	_tr();
tbl_empty_row(2);
endtable();
startbuttonsarea("right");

//addownlinkbutton2("'Sprzęt poza ewidencją'","noauto","submit","z_naprawy_uszkodzony.php?id=0&auto=0&cat=&typ_id=0&clear_typ=1");
echo "<span style='float:left'>";
//if (($_REQUEST[cat]!='Wybierz z listy...') && ($_REQUEST[cat]!='')) 
addownlinkbutton2("'Sprzęt poza ewidencją'","noauto","button","z_naprawy_uszkodzony.php?id=0&auto=0&cat=".$_REQUEST[cat]."&typ_id=".$_REQUEST[typid]."&new_upid=".$_REQUEST[new_upid]."&clear_typ=1&tresc_zgl=".urlencode($_REQUEST[tresc_zgl])."&tup=".urlencode($_REQUEST[tup])."&up=".urlencode($pelna_nazwa_up)."&dodaj_do_ewidencji=1&from=".$_REQUEST[from]."&hd_nr=".$_REQUEST[hd_nr]."&hd_podkategoria_nr=".$_REQUEST[hd_podkategoria_nr]."");
echo "</span>";

echo "<input type=hidden name=from value='$_REQUEST[from]'>";
echo "<input type=hidden name=hd_nr value='$_REQUEST[hd_nr]'>";
echo "<input type=hidden name=hd_podkategoria_nr value='$_REQUEST[hd_podkategoria_nr]'>";

echo "<input type=hidden name=hd_zgl_nr value='$_REQUEST[hd_zgl_nr]'>";

//echo "<input class=buttons name=pozaewid type=button onClick='window.location=\"z_naprawy_uszkodzony.php\"' value='Sprzęt poza'>";
if (($_REQUEST[cat]!='Wybierz z listy...') && ($_REQUEST[cat]!='')) addownsubmitbutton("'Wybierz z ewidencji'","submit");
addbuttons("anuluj");
endbuttonsarea();
echo "<input type=hidden name=tuser value='$currentuser'>";
echo "<input type=hidden name=tresc_zgl value='".urlencode($_REQUEST[tresc_zgl])."'>";
echo "<input type=hidden name=tup value='".urlencode($_REQUEST[tup])."'>";
echo "<input type=hidden name=przyjmij value='$_REQUEST[przyjmij]'>";

if ($_REQUEST[tresc_zgl]!='') echo "<input type=hidden name=upid value='".urlencode($_REQUEST[upid])."'>";
echo "<input type=hidden name=new_upid1 value='".urlencode($_REQUEST[new_upid])."'>";

//echo ">>>>$_REQUEST[upid]";
_form();

?>
<?php if ($noauto!='Sprzęt poza ewidencją') { ?>
<script language="JavaScript" type="text/javascript">
  var frmvalidator  = new Validator("addt");
  <?php if ($_REQUEST[tresc_zgl]=='') { ?>frmvalidator.addValidation("new_upid","dontselect=0","Nie wybrałeś komórki z której pobrano sprzęt"); <?php } ?>
  <?php if ($_REQUEST[hd_podkategoria_nr]=='') { ?> frmvalidator.addValidation("typid","dontselect=0","Nie wybrałeś typu sprzętu");   <?php } ?>
</script>
<?php } 

} else 
{
	errorheader('Przyjmowanie sprzętu możliwe tylko przez bazę Helpdesk');
	
	startbuttonsarea("right");
	echo "<span style='float:left'>";
	addownlinkbutton("'Nowe zgłoszenie helpdesk'","button","button","self.location.href='hd_d_zgloszenie.php';");
	addownlinkbutton("'Przeglądaj zgłoszenia'","button","button","self.close(); if (opener) opener.location.href='hd_p_zgloszenia.php';");
	echo "</span>";
	
	addbuttons("zamknij");
	endbuttonsarea();

}
?>
<script>HideWaitingMessage();</script>
</body>
</html>