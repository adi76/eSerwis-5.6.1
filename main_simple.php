<?php include_once('header.php');
//if ($action=='szukaj') { echo "<body onLoad=\"RecoverScroll.init('main_view'); document.forms[0].elements[0].focus();\">";} else { echo "<body onLoad=\"RecoverScroll.init('main_view');\">"; }
?>
<script>
function DisplayText(t) { 
	document.getElementById('opis').innerHTML=''; 
//	document.getElementById('opis').innerHTML=t; 
}
function HideText() { document.getElementById('opis').innerHTML=''; }

function eraseCookie(name) {
    createCookie(name,"",-1);
}

function createCookie(name,value,days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
}

var xx=window.screen.availWidth-10;
var yy=window.screen.availHeight-30;

eraseCookie('max_x');
eraseCookie('max_y');

createCookie('max_x',xx,30);
createCookie('max_y',yy,30);

</script>
<?php
if ($action=='szukaj') { echo "<body document.forms[0].elements[0].focus();\">";} else { echo "<body>"; }

include('body_start.php'); 
$odstep = 20;

if ($_GET[new_location]!='') {
	$_SESSION[es_filia]=$_GET[new_location];
}

if (($_POST['search_zgl_nr']!='')) {
	?>
	<script>
		//alert('<?php echo $_POST['search_nr']; ?>');
		newWindow_r(<?php echo $_COOKIE[max_x]; ?>,<?php echo $_COOKIE[max_y]; ?>,'hd_p_zgloszenia.php?sr=search-wyniki&search_zgl_nr=<?php echo $_POST['search_zgl_nr']; ?>');	
	</script>
	<?php
	$_POST['szukaj_nr']=='';
} 
?>

<?php //echo $_COOKIE[max_y]; ?>

<div id="header_main">
	<?php
		$remoteIP = $_SERVER['REMOTE_ADDR']; 
		if (strstr($remoteIP, ', ')) { 
		   $ips = explode(', ', $remoteIP); 
		   $remoteIP = $ips[0]; 
		} 
		$fullhost = $remoteIP; 	
		echo "&nbsp;Twój adres IP : <b>$fullhost</b>";	
	?>
	<?php
	
	echo "<span style='float:right'>";
		$sql_ = "SELECT admin_opis,admin_value FROM $dbname.serwis_admin WHERE ((admin_id=1) and (admin_value=1)) LIMIT 1";
		$odpowiedz = mysql_query($sql_,$conn) or die($k_b);
		if (mysql_num_rows($odpowiedz)!=0) {
			$dane				= mysql_fetch_array($odpowiedz);
			$es_admin_info 		= $dane['admin_opis'];
			$es_admin_info_a 	= $dane['admin_value'];
		} else { $es_admin_info=''; $es_admin_info_a=0; }

		if ($es_admin_info_a!=0) {
			echo "<div style='position:relative; display:block; top:0; border:1px solid red; height:16px; background: #FF9999;color:#313131; padding: 4px 5px 2px 5px' align=center>";
			echo "<b>$es_admin_info</b>";
			echo "</div>";
		}
		?>
		<?php 
		$sql="SELECT user_dyrektor FROM $dbname.serwis_uzytkownicy WHERE user_id=$es_nr";
		$result = mysql_query($sql, $conn) or die($k_b);
		$dane1 = mysql_fetch_array($result);
		session_register('is_dyrektor');
		$is_dyrektor = $dane1['user_dyrektor'];
		
		echo "Zalogowany: <b>".$currentuser."</b>"; 
		$sql="SELECT filia_nazwa FROM $dbname.serwis_filie WHERE filia_id=$es_filia";
		$result = mysql_query($sql, $conn) or die($k_b);
		$dane1 = mysql_fetch_array($result);
		$filian = $dane1['filia_nazwa'];
	
		echo " ( ";
		if ($is_dyrektor==0) {
			if ($es_prawa=='0') { echo "<b>Użytkownik zwykły</b>"; }
			if ($es_prawa=='2') { echo "<b>Helpdesk</b>"; }
			if ($es_prawa=='1') { echo "<b>Użytkownik zaawansowany</b>"; }
			if ($es_prawa=='9') { 
				echo "<b>";
				if ($es_m=='1') { echo "<font color=red>Super "; }
				echo "Administrator";
				if ($es_m=='1') { echo "</font>"; }
				echo "</b>"; 
			}
		} else {
			if ($is_dyrektor==1) echo " <b>Dyrektor</b>";
		}
		
		echo " )";
		
	if ($is_dyrektor==1) {	
		$sql44="SELECT filia_id,filia_nazwa FROM $dbname.serwis_filie";
		$result44 = mysql_query($sql44, $conn) or die($k_b);
		echo " | Widok obszaru: ";
		echo "<form name=switch_filia style='display:inline'>";
		echo "<select name=view_filia onChange='document.location.href=document.switch_filia.view_filia.options[document.switch_filia.view_filia.selectedIndex].value'>";
		while ($newArray44 = mysql_fetch_array($result44)) {
			$temp_f	= $newArray44['filia_id'];
			$temp_fn = $newArray44['filia_nazwa'];
			
			echo "<option value='main.php?new_location=$temp_f'"; 
			if (($es_filia==$temp_f) || ($temp_f==$_GET[new_location])) echo " SELECTED"; 
			
			echo ">$temp_fn</option>\n";
		}
		echo "</select>";
		echo "</form>&nbsp;";
	} else echo " | Lokalizacja: <b>$filian</b>&nbsp;";
echo "</span>";		
?>			
	
</div>
<div id="header-hr_main"><hr class="linia"></div>
<div id="horizon">
	<div id="content_main">
		<div class="bodytext">
			<p align="center">
			</p>
			<p align="center">
				<a href="#" title="Podwójne kliknięcie w ikonę otworzy formularz nowego zgłoszenia" onClick="TurnOn('helpdesk');" onDblClick="newWindow_r(800,600,'hd_d_zgloszenie.php?stage=0');" onMouseOver="TurnOn('helpdesk');" onMouseOut="HideText();"><img src="img//main_simple_helpdesk<?php echo $icon_size; ?>.png" style="border:0px; margin-left:<?php echo $odstep; ?>px; margin-right:<?php echo $odstep; ?>px;" /></a>
				
<!--				<a href="#" title="Podwójne kliknięcie w ikonę otworzy formularz przyjęcia uszkodzonego sprzętu" onClick="TurnOn('naprawy');" onDblClick="newWindow_r(800,600,'z_naprawy_przyjmij.php?tresc_zgl='); return false;" onMouseOver="TurnOn('naprawy');" onMouseOut="HideText();"><img src="img//main_simple_naprawy<?php echo $icon_size; ?>.png" style="border:0px; margin-left:<?php echo $odstep; ?>px; margin-right:<?php echo $odstep; ?>px;" /></a>
				
				<a href="#" title="Podwójne kliknięcie w ikonę otworzy listę towarów do odsprzedaży" onClick="TurnOn('na_skroty');" onDblClick="newWindow_r(<?php echo $_COOKIE[max_x]; ?>,<?php echo $_COOKIE[max_y]; ?>,'p_towary_dostepne.php?view=normal'); return false;" onMouseOver="TurnOn('na_skroty');" onMouseOut="HideText();"><img src="img//main_simple_na_skroty<?php echo $icon_size; ?>.png" style="border:0px; margin-left:<?php echo $odstep; ?>px; margin-right:<?php echo $odstep; ?>px;" /></a>
				
				<a href="#" title="Podwójne kliknięcie w ikonę otworzy w nowym oknie pełną wersję bazy eSerwis" onClick="TurnOn('pelna_wersja');" onDblClick="newWindow_r(<?php echo $_COOKIE[max_x]; ?>,<?php echo $_COOKIE[max_y]; ?>,'main.php'); return false;" onMouseOver="TurnOn('pelna_wersja');" onMouseOut="HideText();"><img src="img//main_simple_full_system<?php echo $icon_size; ?>.png" style="border:0px; margin-left:<?php echo $odstep; ?>px; margin-right:<?php echo $odstep; ?>px;" /></a>
-->
				<a href="#" onClick="TurnOn('koniec'); if(confirm('Czy napewno chcesz zamknąć aplikację ?')) self.close();" onMouseOver="TurnOn('koniec');" onMouseOut="HideText();"><img src="img//main_simple_koniec<?php echo $icon_size; ?>.png" style="border:0px; margin-left:<?php echo $odstep*2; ?>px; " /></a>

			</p>
			<div id="DIV_Helpdesk" style="display:;">
				<p align="center">
					<br />
					<font color="#000000"><b>Baza Helpdesk</b></font><br /><br />
					<input type="button" id="hd_nz" name="hd_nz" class="buttons" value="Nowe zgłoszenie" onClick="newWindow_r(800,600,'hd_d_zgloszenie.php?stage=0'); return false;" />
<!--					<input type="button" id="hd_pz" name="hd_pz" class="buttons" value="Przeglądaj zgłoszenia" onClick="newWindow_r(<?php echo $_COOKIE[max_x]; ?>,<?php echo $_COOKIE[max_y]; ?>,'hd_p_zgloszenia.php?showall=0&p5=BZ&sort=AD'); return false;" />
					<input type="button" id="hd_pzmn" name="hd_pzmn" class="buttons" value="Moje zgłoszenia (nie zakończone)" onClick="newWindow_r(<?php echo $_COOKIE[max_x]; ?>,<?php echo $_COOKIE[max_y]; ?>,'hd_p_zgloszenia.php?showall=0&p5=BZ&sort=AD&p6=<?php echo urlencode($currentuser); ?>'); return false;" />
					<input type="button" id="hd_rddp" name="hd_rddp" class="buttons" value="Raport dzienny dla mnie" onClick="newWindow_r(<?php echo $_COOKIE[max_x]; ?>,<?php echo $_COOKIE[max_y]; ?>,'hd_g_raport_dzienny_dla_pracownika.php?okres_od=<?php echo date("Y-m-d"); ?>&okres_do=<?php echo date("Y-m-d");?>&tuser=<?php echo urlencode($currentuser); ?>&submit=Generuj&readonly=1'); return false;" />
-->					
					<input type="button" id="hd_znddm" name="hd_znddm" class="buttons" value="Zadania na dzisiaj dla mnie" onClick="newWindow_r(<?php echo $_COOKIE[max_x]; ?>,<?php echo $_COOKIE[max_y]; ?>,'hd_refresh_zadania.php?osoba=<?php echo urlencode($currentuser); ?>&userid=<?php echo $es_nr; ?>&nastartowej=0&today=<?php echo date('Y-m-d');?>&from=main_simple'); return false;" />
					
					<div id="DIV_Search_nr" style="display:">
						<p align="center">
							<br />
							<font color="#000000"><b>Szukaj po numerze zgłoszenia</b></font><br /><br />
							<span style="align:center">
								<form action="main_simple.php" style="text-align:center" name="szukanie1" method="POST">
									<input style="width:100px; margin:0 0 0.3em 0;border:1px solid silver;padding:3 3 3 3;font-size:18px;font-family:'Courier New' Tahoma Verdana Arial; font-weight:bold; height:25px; color:#000000; text-align:center;" id="search_zgl_nr" type="text" name="search_zgl_nr" size="12" maxlength="8" onFocus="this.style.border='1px solid grey'" onBlur="this.style.border='1px solid silver'; if (this.value=='') document.getElementById('szukaj_nr').style.display='none'" />
									<br />
									<input type="hidden" name="location" value="<?php echo $es_filia; ?>">
									<input type="submit" id="submit_nr" name="szukaj_nr" class="buttons" value="Szukaj" />
									
								</form>
							</span>
						</p>
					</div>

					
				</p>
			</div>
			
			<div id="DIV_Naprawy" style="display:none">
				<p align="center">
					<br />
					<font color="#000000"><b>Naprawy sprzętu</b></font><br />
					<br />
					<!---
					<input type="button" id="n_pu" name="n_pu" class="buttons" value="Przyjmij uszkodzony sprzęt" onClick="newWindow_r(800,600,'z_naprawy_przyjmij.php?tresc_zgl='); return false;" />
					--->
					<input type="button" id="n_pn" name="n_pn" class="buttons" value="Naprawiony sprzęt do oddania" onClick="newWindow_r(<?php echo $_COOKIE[max_x]; ?>,<?php echo $_COOKIE[max_y]; ?>,'p_naprawy_zakonczone.php'); return false;" />
				</p>
			</div>		

			<div id="DIV_Skroty" style="display:none">
				<p align="center">
					<br />
					<font color="#000000"><b>Skróty do często używanych opcji</b></font><br />
					<br />
					<input type="button" id="tns" name="tns" class="buttons" value="Towary na stanie" onClick="newWindow_r(<?php echo $_COOKIE[max_x]; ?>,<?php echo $_COOKIE[max_y]; ?>,'p_towary_dostepne.php?view=normal'); return false;" /><br />
					
					<input type="button" name="n_pu" class="buttons" value="Pokaż sprzęt z wybranej lokalizacji" onClick="newWindow(<?php echo $_COOKIE[max_x]; ?>,<?php echo $_COOKIE[max_y]; ?>,'p_ewidencja_lokalizacja.php?from=simple');" /><br />
					<!---
					<input type="button" name="n_pu" class="buttons" value="Rejestruj awarię WAN" onClick="newWindow(700,235,'d_awaria.php'); return false;" />
					<input type="button" name="n_pu" class="buttons" value="Zamknij awarię WAN" onClick="newWindow_r(<?php echo $_COOKIE[max_x]; ?>,<?php echo $_COOKIE[max_y]; ?>,'z_awarie.php'); return false;" /><br />
					--->
					<input type="button" name="n_pu" class="buttons" value="Pobierz sprzęt serwisowy" onClick="newWindow_r(<?php echo $_COOKIE[max_x]; ?>,<?php echo $_COOKIE[max_y]; ?>,'e_magazyn_pobierz.php?kolor='); return false;" />
					<input type="button" name="n_pu" class="buttons" value="Dodaj sprzęt do ewidencji" onClick="newWindow(820,600,'d_ewidencja.php'); return false;" /><br />
					<input type="button" name="n_pu" class="buttons" value="Wykaz telefonów" onClick="newWindow_r(800,600,'hd_p_komorki.php'); return false;" />
					<input type="button" name="n_pu" class="buttons" value="Baza wiedzy" onClick="newWindow_r(<?php echo $_COOKIE[max_x]; ?>,<?php echo $_COOKIE[max_y]; ?>,'z_kb_kategorie.php?showall=0&action=view'); return false;" />
					<input type="button" name="n_pu" class="buttons" value="Baza komórek" onClick="newWindow_r(<?php echo $_COOKIE[max_x]; ?>,<?php echo $_COOKIE[max_y]; ?>,'z_komorka.php?showall=0&aktywne=1&ko='); return false;" />
				</p>
			</div>

			<div id="DIV_Full" style="display:none">
				<p align="center">
					<br />
					<font color="#000000"><b>Przejście do pełnej wersji bazy eSerwis</b></font><br />
					<br />
					<input type="button" id="pdpw" name="pdpw" class="buttons" value="Otwórz pełną wersję bazy eSerwis w nowym oknie" onClick="newWindow_r(<?php echo $_COOKIE[max_x]; ?>,<?php echo $_COOKIE[max_y]; ?>,'main.php'); return false;" />
				</p>
			</div>
			
			<div id="DIV_Exit" style="display:none">
				<p align="center">
					<br />
					<font color="#000000"><b>Zakończenie pracy</b></font><br /><br />
					<input type="button" id="logout" name="logout" class="buttons" value="Wyloguj się" onClick="if(confirm('Czy napewno chcesz się wylogować ?')) self.location.href='index.php';"/>
					<input type="button" id="exit_app" name="exit_app" class="buttons" value="Zakończ pracę" onClick="if(confirm('Czy napewno chcesz zamknąć aplikację ?')) self.close();"/>
				</p>
			</div>
		</div>
	</div>
</div>
<div id="footer-hr_main"><hr class="linia"></div>
<div id="footer_main">
	<span id="opis" style="font-size:16px; color:#000000; font-style:arial; "></span>
</div>

</body>

<script>

function TurnOn(what) {

document.getElementById('DIV_Helpdesk').style.display = 'none';
document.getElementById('DIV_Naprawy').style.display = 'none';
document.getElementById('DIV_Skroty').style.display = 'none';
document.getElementById('DIV_Exit').style.display = 'none';
document.getElementById('DIV_Full').style.display = 'none';							

	if (what=='helpdesk')	{ 
		document.getElementById('DIV_Helpdesk').style.display = ''; 
		document.getElementById('hd_nz').focus(); 
		return true; 
	}

	if (what=='naprawy')	{ 
		document.getElementById('DIV_Naprawy').style.display = ''; 
		document.getElementById('n_pu').focus(); 
		return true; 
	}
	
	if (what=='na_skroty') 	{
		document.getElementById('DIV_Skroty').style.display = '';
		document.getElementById('tns').focus();
		return true; 
	}

	if (what=='pelna_wersja') 	{ 	
		document.getElementById('DIV_Full').style.display = '';
		document.getElementById('pdpw').focus();
		return true; 
	}

	if (what=='koniec') 	{ 	
		document.getElementById('DIV_Exit').style.display = '';
		document.getElementById('exit_app').focus();
		return true; 
	}
}
</script>
</html>