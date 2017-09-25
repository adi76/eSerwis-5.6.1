<?php 
include_once('header.php');
include_once('cfg_helpdesk.php');
if ($action=='szukaj') { echo "<body onLoad=\"document.forms[0].elements[0].focus();\">";} else { echo "<body onClose=\"if (confirm('Czy zamknąć główne okno aplikacji?')==true) { return true; } else { return false; }\">"; }

include('body_start.php'); 
if ($_GET[new_location]!='') $_SESSION[es_filia]=$_GET[new_location];
?>

<div id="tiplayer" style="position:absolute; visibility:hidden; z-index:10000;"></div>
<?php if ($printpreview==0) { 
	include_once('login_info.php');
	include_once('mainmenu.php');
}
?>
<script type="text/javascript">
//<![CDATA[

var listMenu = new FSMenu('listMenu', true, 'display', 'block', 'none');
listMenu.showDelay = 100;
listMenu.switchDelay = 125;
listMenu.hideDelay = 300;
listMenu.cssLitClass = 'highlighted';
//listMenu.showOnClick = 1;
listMenu.animInSpeed = 0.5;
listMenu.animOutSpeed = 0.5;

function animClipDown(ref, counter)
{
var cP = Math.pow(Math.sin(Math.PI*counter/200),0.75);
ref.style.clip = (counter==100 ? (window.opera ? '': 'rect(auto, auto, auto, auto)') :
'rect(0, ' + ref.offsetWidth + 'px, '+(ref.offsetHeight*cP)+'px, 0)');
};

function animFade(ref, counter)
{
var f = ref.filters, done = (counter==100);
if (f)
{
if (!done && ref.style.filter.indexOf("alpha") == -1)
ref.style.filter += ' alpha(opacity=' + counter + ')';
else if (f.length && f.alpha) with (f.alpha)
{
if (done) enabled = false;
else { opacity = counter; enabled=true }
}
}
else ref.style.opacity = ref.style.MozOpacity = counter/100.1;
};

// I'm applying them both to this menu and setting the speed to 20%. Delete this to disable.
			//listMenu.animations[listMenu.animations.length] = animFade;
			//listMenu.animations[listMenu.animations.length] = animClipDown;
			//listMenu.animations[listMenu.animations.length] = FSMenu.animFade;
			//listMenu.animations[listMenu.animations.length] = FSMenu.animSwipeDown;

			//listMenu.animSpeed = 500;

			var arrow = null;
			if (document.createElement && document.documentElement)
			{
				arrow = document.createElement('img');
				arrow.src = 'img/menu.gif';
				arrow.style.borderWidth = '0';
				arrow.className = 'subind';
				arrow.width = '16';
				arrow.height = '16';
			}

			addEvent(window, 'load', new Function('listMenu.activateMenu("listMenuRoot", arrow)'));

			//]]>
</script>

<script>ShowWaitingMessage('Trwa ładowanie strony startowej...','main');</script><?php ob_flush(); flush();

		switch ($action) {
			case "asm"				: include_once('p_magazyn_aktualne.php');	 		break;	
			case "cm"				: include_once('p_magazyn_wszystko.php');	  		break;	
			case "sw"				: include_once('p_magazyn_pobrany.php');			break;	
			case "asm_p"			: include_once('e_magazyn_pobierz_szczecin.php');	break;
			case "sw_z"				: include_once('z_magazyn_zwroty_szczecin.php');	break;	
			case "rso"				: include_once('p_magazyn_historia_okres.php');		break;
			case "rswgup"			: include_once('p_magazyn_historia_wg_up.php');		break;
			case "us"				: include_once('z_magazyn_ukryj.php');				break;	
			case "pus"				: include_once('z_magazyn_ukryty.php');				break;
			case "rs"				: include_once('p_magazyn_do_rezerwacji.php');		break;
			case "prs"				: include_once('z_magazyn_rezerwacje.php');			break;	
			case "npswsz"			: include_once('p_naprawy_w_naprawie.php');			break;
			case "snd"				: include_once('p_naprawy_stan_na_dzien.php');		break;
			case "nsnrl"			: include_once('p_naprawy_wnnrl.php'); 				break;
			case "npus"				: include_once('p_naprawy_pobrane.php');			break;	
			case "nwwz"				: include_once('p_naprawy_wwz.php');				break;	
			case "nsw"				: include_once('p_naprawy_wycofane.php');			break;	
			case "npns"				: include_once('p_naprawy_zakonczone_szczecin.php');break;	
			case "nwo"				: include_once('p_naprawy_w_okresie.php');			break;
			case "info"				: include_once('info.php');							break;
			case "hawo"				: include_once('r_awarie.php');						break;
			case "fwn"				: include_once('fwn.php');							break;
			case "bum"				: include_once('manage_umowy.php');					break;
			case "bd"				: include_once('manage_dostawcy.php');				break;
		//	case "zsns"				: include_once('p_towary_dostepne.php');			break;		
			case "onkm"				: include_once('p_sprzedaz_obciazenie.php');		break;
			case "grzs"				: include_once('p_towary_raport_sprzedaz.php');  	break;
			case "aktsm"			: include_once('p_towary_stan.php');				break;							
			case "fsfo"				: include_once('r_faktury_okres.php');				break;
			case "bopr"				: include_once('manage_opr.php');					break;
			case "besk"				: include_once('z_typ_sprzetu.php');				break;	
			case "pods_sprz"		: include_once('p_ewidencja_wg_rodzaju.php');		break;
			case "pods_opr"			: include_once('p_oprogramowanie_rodzaj.php');		break;
			case "szukaj"			: include_once('szukaj.php');						break;
			case "ewid_choose"		: include_once('p_ewidencja_lokalizacja.php');		break;
			case "es_plot1"			: include_once('z_plot.php');						break;
			case "pswo"				: include_once('p_sprzedaz_w_okresie.php');			break;
			
			case "zsdwk"			: include_once('p_sprzedaz_dla_komorki_w_okresie.php');			break;
			case "zsds"				: include_once('p_sprzedaz_dla_sprzetu_w_okresie.php');			break;
			
			case "pmf"				: include_once('p_towary_przesuniecia.php');		break; 
			case "hdgro"			: include_once('hd_g_raport_okresowy.php');			break;
			case "hdwz"				: include_once('hd_g_raport_weryfikacja_zgloszen.php');		break;
			case "hdddp"			: include_once('hd_g_raport_dzienny_dla_pracownika.php');	break;
		//	case "rzwzs"			: include_once('hd_g_raport_z_wykonania_zgl_s.php');	break;
			case "grozz"			: include_once('hd_g_statystyka_zgloszen.php'); 	break;
			case "hdzzptr"			: include_once('hd_g_raport_przesuniete_terminy.php'); break;				
			default 				: 
					echo "<span id=licznik_refresh_na_startowej></span>";
					if (($PokazHDnaStronieGlownej==1) && ($wersja_light!="on")){
					include_once('p_raport_hd.php');
					echo "<div id=responsecontainer></div>";
				} else { nowalinia(); }
				
				include_once('p_naprawy_wycofane_z_serwisu.php');
				include_once('p_naprawy_zakonczone_na_startowej.php');
				
				if (($pokazraport==1) && ($wersja_light!="on")) { 
					
					include_once('p_naprawy_po_terminie.php');	
					$accessLevels = array("0","1","9"); if(array_search($es_prawa, $accessLevels)>-1) { 
						include_once('p_raport.php');
					}
				}

				break;
			}

			if ($action=='') {
				// liczniki w filii dla wszystkich
				$czy_jest_tabela_ze_statystykami_dla_filii = mysql_query("SELECT licznik_last_update FROM $dbname_hd.hd_licznik_zgloszen_".$es_filia." LIMIT 1", $conn_hd);
				if ($czy_jest_tabela_ze_statystykami_dla_filii) {
					list($last_update)=mysql_fetch_array($czy_jest_tabela_ze_statystykami_dla_filii);	
					$ddddd = date("Y-m-d H:i:s");
					if ($last_update!='') {
						if ($last_update<$ddddd) {
							?><script>		
								//newWindow_r(800,150,'hd_uaktualnij_liczniki.php?f=<?php echo $es_filia; ?>&range=W&moj_nr=<?php echo $es_nr; ?>&randval='+ Math.random()+'');
								
								$('#licznik_refresh_na_startowej').load('hd_uaktualnij_liczniki.php?f=<?php echo $es_filia; ?>&range=W&moj_nr=<?php echo $es_nr; ?>&randval='+Math.random()+'&refresh_parent=0&todiv=1&sourcepage=start').load('hd_uaktualnij_liczniki.php?f=<?php echo $es_filia; ?>&range=W&moj_nr=<?php echo $es_nr; ?>&randval='+Math.random()+'&refresh_parent=0&todiv=1&sourcepage=start');
								
							</script><?php				
						}
					} else {		
						?>
						<script>
							//newWindow_r(800,150,'hd_uaktualnij_liczniki.php?f=<?php echo $es_filia; ?>&range=W&moj_nr=<?php echo $es_nr; ?>&randval='+ Math.random()+'');
							$('#licznik_refresh_na_startowej').load('hd_uaktualnij_liczniki.php?f=<?php echo $es_filia; ?>&range=W&moj_nr=<?php echo $es_nr; ?>&randval='+Math.random()+'&refresh_parent=0&todiv=1&sourcepage=start');
						</script><?php 
					}			
				} else {
					$sql_report = "CREATE TABLE `$dbname_hd`.`hd_licznik_zgloszen_".$es_filia."` (`licznik_id` INT( 10 ) NOT NULL AUTO_INCREMENT ,`licznik_opis` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL ,`licznik_wartosc` INT( 10 ) NOT NULL ,`licznik_kogo` VARCHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL COMMENT 'M - moje, W - wszystkie',`licznik_osoba` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL COMMENT 'jeżeli licznik_kogo = M',`licznik_last_update` DATETIME NOT NULL ,`belongs_to` TINYINT( 2 ) NOT NULL ,PRIMARY KEY ( `licznik_id` )) ENGINE = MYISAM ;";	
					$result_report = mysql_query($sql_report, $conn_hd) or die($k_b);	
					?><script>
						//newWindow_r(800,150,'hd_uaktualnij_liczniki.php?f=<?php echo $es_filia; ?>&range=W&moj_nr=<?php echo $es_nr; ?>&randval='+ Math.random()+'');
						$('#licznik_refresh_na_startowej').load('hd_uaktualnij_liczniki.php?f=<?php echo $es_filia; ?>&range=W&moj_nr=<?php echo $es_nr; ?>&randval='+Math.random()+'&refresh_parent=0&todiv=1&sourcepage=start');
					</script><?php		
				}

				// liczniki moich zgłoszeń
				$czy_jest_tabela_ze_statystykami_dla_mnie= mysql_query("SELECT licznik_last_update FROM $dbname_hd.hd_licznik_zgloszen_".$es_filia."_".$es_nr." LIMIT 1", $conn_hd);
				if ($czy_jest_tabela_ze_statystykami_dla_mnie) {	
					list($last_update)=mysql_fetch_array($czy_jest_tabela_ze_statystykami_dla_mnie);	
					$ddddd = date("Y-m-d H:i:s");
					if ($last_update!='') {			
						if ($last_update<$ddddd) {
							?><script>		
								//newWindow_r(800,150,'hd_uaktualnij_liczniki.php?f=<?php echo $es_filia; ?>&range=M&moj_nr=<?php echo $es_nr; ?>&cu=<?php echo urlencode($currentuser); ?>&randval='+ Math.random()+'');
								$('#licznik_refresh_na_startowej').load('hd_uaktualnij_liczniki.php?f=<?php echo $es_filia; ?>&range=M&moj_nr=<?php echo $es_nr; ?>&cu=<?php echo urlencode($currentuser); ?>&randval='+Math.random()+'&refresh_parent=0&todiv=1&sourcepage=start');
							</script><?php					
						}
					} else {		
						?>
						<script>
							//newWindow_r(800,150,'hd_uaktualnij_liczniki.php?f=<?php echo $es_filia; ?>&range=M&moj_nr=<?php echo $es_nr; ?>&cu=<?php echo urlencode($currentuser); ?>&randval='+ Math.random()+'');
							$('#licznik_refresh_na_startowej').load('hd_uaktualnij_liczniki.php?f=<?php echo $es_filia; ?>&range=M&moj_nr=<?php echo $es_nr; ?>&cu=<?php echo urlencode($currentuser); ?>&randval='+Math.random()+'&refresh_parent=0&todiv=1&sourcepage=start');
						</script><?php 
					}
				} else {
					$sql_report = "CREATE TABLE `$dbname_hd`.`hd_licznik_zgloszen_".$es_filia."_".$es_nr."` (`licznik_id` INT( 10 ) NOT NULL AUTO_INCREMENT ,`licznik_opis` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL ,`licznik_wartosc` INT( 10 ) NOT NULL ,`licznik_kogo` VARCHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL COMMENT 'M - moje, W - wszystkie',`licznik_osoba` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_polish_ci NOT NULL COMMENT 'jeżeli licznik_kogo = M',`licznik_last_update` DATETIME NOT NULL ,`belongs_to` TINYINT( 2 ) NOT NULL ,PRIMARY KEY ( `licznik_id` )) ENGINE = MYISAM ;";	
					$result_report = mysql_query($sql_report, $conn_hd) or die($k_b);	
					// uaktualnik liczniki
					?><script>
						//newWindow_r(800,150,'hd_uaktualnij_liczniki.php?f=<?php echo $es_filia; ?>&range=M&moj_nr=<?php echo $es_nr; ?>&cu=<?php echo urlencode($currentuser); ?>&randval='+ Math.random()+'');
						$('#licznik_refresh_na_startowej').load('hd_uaktualnij_liczniki.php?f=<?php echo $es_filia; ?>&range=M&moj_nr=<?php echo $es_nr; ?>&cu=<?php echo urlencode($currentuser); ?>&randval='+Math.random()+'&refresh_parent=0&todiv=1&sourcepage=start');
					</script><?php
				}
				
			}
			
			if ($action=='hdgro') {
				?>
				<script language="JavaScript">
				var cal1 = new calendar1(document.forms['ruch'].elements['okres_od']);
				cal1.year_scroll = true;
				cal1.time_comp = false;
				var cal11 = new calendar1(document.forms['ruch'].elements['okres_do']);
				cal11.year_scroll = true;
				cal11.time_comp = false;
				</script>
				<?php 
			}

			if ($czas==1) include('body_stop.php');
			?>

			<script type="text/javascript">
			$().ready(function() {
				var refreshPage_co_5_minut = setInterval(function() {
					if ($('#rp').attr('checked')) { 
						self.location.reload(true); 
						$('input[name=rp]').attr('checked',true);
					}
				}, 300000);
			});
			</script>
			
		<script>
		HideWaitingMessage('main');
		</script>
		
		<?php
				
		if (($es_auth_hdim==1) && ($es_hdim==0)) {
			?>
				<script>
					newWindow(800,600,'https://sd.cit.net.pp/');
				</script>
				
			<?php
			$es_hdim=1;
		}
		
		?>
</body>
	
</html>