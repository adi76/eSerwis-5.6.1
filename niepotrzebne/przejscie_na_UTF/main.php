<?php include_once('header.php'); ?>
<body onLoad="document.forms[0].elements[0].focus();">
<?php include('body_start.php'); ?>
<div id="tiplayer" style="position:absolute; visibility:hidden; z-index:10000;"></div>
<?php if ($printpreview==0) { ?>
<table class="mainpageheader">
<tr>
	<td style="vertical-align:middle">
	<?php
		$remoteIP = $_SERVER['REMOTE_ADDR']; 
		if (strstr($remoteIP, ', ')) { 
		   $ips = explode(', ', $remoteIP); 
		   $remoteIP = $ips[0]; 
		} 
		$fullhost = $remoteIP; 	
		echo "Twój adres IP 	: <b>$fullhost</b>";	
	?>
	</td>
	<td align="center" width="100%">
	<?php
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
	</td>
	<td class="right">
		<?php 
		echo "Aktualnie zalogowany : <b>".$currentuser."</b> (<b>"; 
		$sql="SELECT filia_nazwa FROM $dbname.serwis_filie WHERE filia_id=$es_filia";
		$result = mysql_query($sql, $conn) or die($k_b);
		$dane1 = mysql_fetch_array($result);
		$filian = $dane1['filia_nazwa'];
		echo "$filian</b>)";
		echo "<br />Poziom uprawnień : ";
			if ($es_prawa=='0') { echo "<b>Użytkownik zwykły</b>"; }
			if ($es_prawa=='1') { echo "<b>Użytkownik zaawansowany</b>"; }
			if ($es_prawa=='9') { echo "<b>Administrator</b>"; }
		?>			
	</td>
</tr>
</table>
<?php } ?>
<?php if ($printpreview==0) { ?>
<table class="menu" width="100%">
<tr class="menu">
<td class="menu">
<div id="floatmenu">
<ul class="menulist" id="listMenuRoot">
	<?php if (($currentuser==$adminname) && ($es_block==1)) {	?>
	<li><a class="menu_font" href="#" onClick="newWindow(500,105,'e_adminblock.php?a=0')"><img src="img/zwolnij.gif" border=0></a></li>
	<?php } ?>
	<li><a class="menu_font" href="main.php">&nbsp;Start&nbsp;</a>
		<ul>
		<li><a class="menu_font" href="main.php"><?php if ($pokaz_ikony==1) echo "<img src='img/house.png' border=0 align=absmiddle>"; ?>&nbsp;Przejdź do strony startowej</a></li>
		<li><a class="menu_font" href="#" onclick="newWindow_chat(800,603,'chat/index.php?name=<?php echo "$es_login";?>')"><?php if ($pokaz_ikony==1) echo "<img src='img/chat.png' border=0 align=absmiddle>"; ?>&nbsp;Uruchom Chat</a></li>
		<li><a class="menu_font" href="z_kb_kategorie.php?showall=0&action=view">&nbsp;Baza wiedzy&nbsp;</a>
			<ul>
				<?php $accessLevels = array("9"); if(array_search($es_prawa, $accessLevels)>-1){ ?>
				<li><a class="menu_font" href="z_kb_kategorie.php?showall=0&action=manage"><?php if ($pokaz_ikony==1) echo "<img src='img/application_edit.png' border=0 align=absmiddle>";?>&nbsp;Zadządzaj kategoriami</a></li>
				<?php } ?>
				<li><a class="menu_font" href="z_kb_kategorie.php?showall=0&action=view"><?php if ($pokaz_ikony==1) echo "<img src='img/application_go.png' border=0 align=absmiddle>";?>&nbsp;Przeglądaj pytania</a></li>
				<li><a class="menu_font" href="main.php?action=szukaj&typs=B"><center><?php if ($pokaz_ikony==1) echo "<img src='img/search.gif' align=absmiddle border=0>"; ?>&nbsp;<b>Szukaj</b></center></a></li>
				</ul>
		</li>
		<?php $accessLevels = array("9"); if(array_search($es_prawa, $accessLevels)>-1){ 
			if ($currentuser==$adminname) {	
		?>
		<li><a class="menu_font" href="#">&nbsp;Administracja&nbsp;</a>
			<ul>
				<li><a class="menu_font" href="z_czarna_lista.php?showall=0"><?php if ($pokaz_ikony==1) echo "<img src='img/blacklist.gif' border=0 align=absmiddle>"; ?>&nbsp;Czarna lista IP</a></li>
				<li><a class="menu_font" target="_blank" href="index_fm.php"><?php if ($pokaz_ikony==1) echo "<img src='img/folder_explore.png' border=0 align=absmiddle>"; ?>&nbsp;Eksplorator plików</a></li>
				<li><center><img src="img/menubreak.gif" border=0 align=absmiddle></center></li>
				<li><a class="menu_font" href="#" onclick="newWindow(250,10,'mybackup/backup.php')"><?php if ($pokaz_ikony==1) echo "<img src='img/icon_backup.gif' border=0 align=absmiddle>"; ?>&nbsp;Zrób backup bazy</a></li>
				<li><a class="menu_font" href="#" onClick="newWindow_r(420,600,'mybackup//restore.php')"><?php if ($pokaz_ikony==1) echo "<img src='img/icon_restore.gif' border=0 align=absmiddle>"; ?>&nbsp;Przywróć bazę z kopii</a></li>
				<li><center><img src="img/menubreak.gif" border=0 align=absmiddle></center></li>		
				<li><a class="menu_font" href="#" onClick="newWindow(500,160,'e_admininfo.php')"><?php if ($pokaz_ikony==1) echo "<img src='img/information.png' border=0 align=absmiddle>"; ?>&nbsp;Pokaż informację od admina</a></li>
				<li><a class="menu_font" href="#" onClick="newWindow(500,105,'e_adminblock.php?a=1')"><?php if ($pokaz_ikony==1) echo "<img src='img/zarezerwuj.gif' border=0 align=absmiddle>"; ?>&nbsp;Zablokuj dostęp do bazy</a></li>
				<li><a class="menu_font" href="#" onClick="newWindow(500,105,'e_adminblock.php?a=0')"><?php if ($pokaz_ikony==1) echo "<img src='img/zwolnij.gif' border=0 align=absmiddle>"; ?>&nbsp;Odblokuj dostęp do bazy</a></li>
				<li><a class="menu_font" href="#" onClick="newWindow_r(1024,760,'sqlc/wizmysqladmin.php')"><?php if ($pokaz_ikony==1) echo "<img src='img/edita.gif' border=0 align=absmiddle>"; ?>&nbsp;MySQLAdmin</a></li>
				<li><center><img src="img/menubreak.gif" border=0 align=absmiddle></center></li>
				<li><center>na bazie w starym kodowaniu</center></li>
				<li><a class="menu_font" href="#" onClick="newWindow_r(800,600,'serwis_sprzedaz_convert_ceny.php?kierunek=cr_to_normal')">serwis sprzedaż -> normalne ceny</a></li>
				<li><a class="menu_font" href="#" onClick="newWindow_r(800,600,'serwis_fszcz_convert_ceny.php?kierunek=cr_to_normal')">serwis f.szczeg -> normalne ceny</a></li>
				<li><a class="menu_font" href="#" onClick="newWindow_r(800,600,'serwis_pf_convert_ceny.php?kierunek=cr_to_normal')">serwis podfaktury -> normalne ceny</a></li>
				<li><center><img src="img/menubreak.gif" border=0 align=absmiddle></center></li>
				<li><a class="menu_font" href="#" onClick="newWindow_r(800,600,'serwis_fz_convert.php')"><b>konwersja danych w tbl. do UTF8</b></a></li>
				<li><center><img src="img/menubreak.gif" border=0 align=absmiddle></center></li>
				<li><center>na bazie w kodowaniu UTF-8</center></li>
			<li><a class="menu_font" href="#" onClick="newWindow_r(800,600,'serwis_sprzedaz_convert_ceny.php?kierunek=normal_to_cr')">serwis sprzedaż -> zaszyfr. ceny</a></li>
				<li><a class="menu_font" href="#" onClick="newWindow_r(800,600,'serwis_fszcz_convert_ceny.php?kierunek=normal_to_cr')">serwis f.szczeg -> zaszyfr. ceny</a></li>
				<li><a class="menu_font" href="#" onClick="newWindow_r(800,600,'serwis_pf_convert_ceny.php?kierunek=normal_to_cr')">serwis podfaktury -> zaszyfr. ceny</a></li>
			</ul>
		</li>
		<?php } 
		} ?>
			<li><a class="menu_font" href="#" onclick="newWindow(430,330,'e_uzytkownika.php?select_id=<?php echo $es_nr; ?>&all=1')"><?php if ($pokaz_ikony==1) echo "<img src='img/vcard.png' border=0 align=absmiddle>"; ?>&nbsp;Edytuj swój profil</a></li>
			<li><a class="menu_font" href="#" onclick="newWindow(440,185,'e_haslo.php')"><?php if ($pokaz_ikony==1) echo "<img src='img/textfield_key.png' border=0 align=absmiddle>"; ?>&nbsp;Zmień hasło</a></li>
			<li><a class="menu_font" href="#" onclick="alert('Informacje o bazie eSerwis - wersja <?php echo $wersja;?>\n\nDostępne moduły:\n- sprzęt serwisowy\n- naprawy\n- towary/sprzedaż\n- ewidencja sprzętu\n- ewidencja oprogramowania\n- zadania i czynności\n- awarie\n\nAutor bazy: Maciej Adrjanowicz')"><?php if ($pokaz_ikony==1) echo "<img src='img/information.png' align=absmiddle border=0>"; ?>&nbsp;O bazie</a></li>
			<li><a class="menu_font" href="#" onClick="javascript:if(confirm('Czy napewno chcesz się wylogować ?')) document.location.href='logout.php'"><?php if ($pokaz_ikony==1) echo "<img src='img/logout1.gif' align=absmiddle border=0>";?>&nbsp;Wyloguj się</a></li>
			<li><a class="menu_font" href="#" onClick="javascript:if(confirm('Czy napewno chcesz zakończyć pracę z bazą eSerwis ?')) document.location.href='exit.php'; return false;"><?php if ($pokaz_ikony==1) echo "<img src='img/logout.gif' align=absmiddle border=0>";?>&nbsp;Zakończ pracę z bazą</a></li>
		</ul>
	</li>
	<li><a class="menu_font" href="#">&nbsp;Sprzęt serwisowy&nbsp;</a>
	<ul>
	<?php if ($es_m!=1) { ?>
		<?php $accessLevels = array("9"); if(array_search($es_prawa, $accessLevels)>-1){ ?>
		<li><a class="menu_font" href="#" onclick="newWindow(500,325,'d_magazyn.php')"><?php if ($pokaz_ikony==1) echo "<img src='img/computer_add.png' align=absmiddle border=0>";?>&nbsp;Dodaj nowy sprzęt</a></li>
		<?php } ?>	
		<li><a class="menu_font" href="main.php?action=asm_p"><?php if ($pokaz_ikony==1) echo "<img src='img/pobierz.gif' align=absmiddle border=0>"; ?>&nbsp;Pobierz sprzęt</a></li>
		<li><a class="menu_font" href="main.php?action=sw_z"><?php if ($pokaz_ikony==1) echo "<img src='img//return.gif' align=absmiddle border=0>";?>&nbsp;Zwróć sprzęt</a></li>
		<li><a class="menu_font" href="main.php?action=rs"><?php if ($pokaz_ikony==1) echo "<img src='img/zarezerwuj.gif' align=absmiddle border=0>";?>&nbsp;Zarezerwuj sprzęt</a></li>
		<li><a class="menu_font" href="main.php?action=us"><?php if ($pokaz_ikony==1) echo "<img src='img/ukryj.png' align=absmiddle border=0>";?>&nbsp;Ukryj sprzęt</a></li>
	<?php } ?>
		<li><a class="menu_font" href="main.php?action=cm">&nbsp;Pokaż</a>
			<ul>
				<li><a class="menu_font" href="main.php?action=cm"><?php if ($pokaz_ikony==1) echo "<img src='img/pokaz_caly.png' align=absmiddle border=0>";?>&nbsp;Cały sprzęt</a></li>
				<li><a class="menu_font" href="main.php?action=asm"><?php if ($pokaz_ikony==1) echo "<img src='img/pokaz_dostepny.png' align=absmiddle border=0>";?>&nbsp;Dostępny sprzęt</a></li>
				<li><a class="menu_font" href="main.php?action=sw"><?php if ($pokaz_ikony==1) echo "<img src='img/pokaz_pobrany.gif' align=absmiddle border=0>";?>&nbsp;Pobrany sprzęt</a></li>
				<li><a class="menu_font" href="main.php?action=prs"><?php if ($pokaz_ikony==1) echo "<img src='img/pokaz_zarez.png' align=absmiddle border=0>";?>&nbsp;Zarezerwowany sprzęt</a></li>
				<?php $accessLevels = array("1","9"); if(array_search($es_prawa, $accessLevels)>-1){ ?>
				<li><a class="menu_font" href="main.php?action=pus"><?php if ($pokaz_ikony==1) echo "<img src='img/pokaz_ukryty.png' align=absmiddle border=0>";?>&nbsp;Ukryty sprzęt</a></li>
				<?php } ?>				
			</ul>
		</li>
		<?php $accessLevels = array("1","9"); if(array_search($es_prawa, $accessLevels)>-1){ ?>		
		<li><a class="menu_font" href="r_magazyn_historia_wszystko.php">&nbsp;Raporty</a>
			<ul>
				<li><a class="menu_font" href="r_magazyn_historia_wszystko.php"><?php if ($pokaz_ikony==1) echo "<img src='img/historia_cala.png' align=absmiddle border=0>";?>&nbsp;Historia ruchów całego sprzętu</a></li>
				<li><a class="menu_font" href="main.php?action=rso"><?php if ($pokaz_ikony==1) echo "<img src='img/calendar.png' align=absmiddle border=0>";?>&nbsp;Historia ruchu sprzętu w okresie</a></li>
				<li><a class="menu_font" href="p_magazyn_historia.php"><?php if ($pokaz_ikony==1) echo "<img src='img/historia_wybr.png' align=absmiddle border=0>";?>&nbsp;Historia ruchu wybranego sprzętu</a></li>
				<li><a class="menu_font" href="main.php?action=rswgup"><?php if ($pokaz_ikony==1) echo "<img src='img/historia_wgup.png' align=absmiddle border=0>";?>&nbsp;Historia ruchu wg komórki/UP i dat</a></li>
			</ul>
		</li>
		<?php } ?>
		<li><a class="menu_font" href="main.php?action=szukaj&typs=M"><center><?php if ($pokaz_ikony==1) echo "<img src='img/search.gif' align=absmiddle border=0>";?>&nbsp;<b>Szukaj</b></center></a></li>
	</ul>
	</li>
	<li><a class="menu_font" href="#">&nbsp;Naprawy&nbsp;</a>
	<ul>
	<?php if ($es_m!=1) { ?>
		<li><a class="menu_font" href="#" onclick="newWindow_r(700,380,'z_naprawy_przyjmij.php')"><?php if ($pokaz_ikony==1) echo "<img src='img//naprawa_prz.gif' border=0 align=absmiddle>";?>&nbsp;Przyjmij uszkodzony sprzęt</a></li>
	<?php } ?>
		<li><a class="menu_font" href="main.php?action=npus"><?php if ($pokaz_ikony==1) echo "<img src='img/snapraw_p.gif' border=0 align=absmiddle>";?>&nbsp;Uszkodzony sprzęt na stanie</a></li>
		<li><a class="menu_font" href="main.php?action=npswsz"><?php if ($pokaz_ikony==1) echo "<img src='img/snapraw_w.gif' border=0 align=absmiddle>";?>&nbsp;Uszkodzony sprzęt w serwisach</a></li>
		<li><a class="menu_font" href="main.php?action=npns"><?php if ($pokaz_ikony==1) echo "<img src='img/snapraw_ok.gif' border=0 align=absmiddle>";?>&nbsp;Naprawiony sprzęt na stanie</a></li>
		<?php $accessLevels = array("0","1","9"); if(array_search($es_prawa, $accessLevels)>-1){ ?>
		<li><a class="menu_font" href="p_naprawy_wszystko.php">&nbsp;Raporty</a>
			<ul>
				<li><a class="menu_font" href="p_naprawy_wszystko.php"><?php if ($pokaz_ikony==1) echo "<img src='img//naprawa_all.gif' border=0 align=absmiddle>";?>&nbsp;Pokaż wszystko</a></li>
				<li><a class="menu_font" href="p_naprawy_historia_cala.php"><?php if ($pokaz_ikony==1) echo "<img src='img//naprawa_zak.gif' border=0 align=absmiddle>";?>&nbsp;Historia zakończonych napraw</a></li>
				<li><a class="menu_font" href="main.php?action=nwo"><?php if ($pokaz_ikony==1) echo "<img src='img//naprawa_wgup.gif' border=0 align=absmiddle>";?>&nbsp;Historia napraw wg UP/komórki</a></li>
			</ul>
		</li>
		<?php } ?>
		<li><a class="menu_font" href="main.php?action=szukaj&typs=N"><center><?php if ($pokaz_ikony==1) echo "<img src='img/search.gif' align=absmiddle border=0>";?>&nbsp;<b>Szukaj</b></center></a></li>		
	</ul>
	</li>
	<li><a class="menu_font" href="#">&nbsp;Sprzedaż towarów&nbsp;</a>
	<ul>
		<?php $accessLevels = array("1","9"); if(array_search($es_prawa, $accessLevels)>-1){ ?>	
		<li><a class="menu_font" href="z_faktury.php?showall=0">&nbsp;Faktury</a>
			<ul>
				<li><a class="menu_font" href="#" onclick="newWindow_r(800,322,'d_faktura.php')"><?php if ($pokaz_ikony==1) echo "<img src='img/faktura_add.png' border=0 align=absmiddle>";?>&nbsp;Dodaj nową fakturę</a></li>
				<li><a class="menu_font" href="z_faktury.php?showall=0"><?php if ($pokaz_ikony==1) echo "<img src='img/faktury_nz.gif' border=0 align=absmiddle>";?>&nbsp;Faktury niezatwierdzone</a></li>
				<li><a class="menu_font" href="z_faktury_zatwierdzone.php?showall=0"><?php if ($pokaz_ikony==1) echo "<img src='img/accept.gif' border=0 align=absmiddle>";?>&nbsp;Faktury zatwierdzone</a></li>
				<li><a class="menu_font" href="main.php?action=fsfo"><?php if ($pokaz_ikony==1) echo "<img src='img/calendar.png' align=absmiddle border=0>";?>&nbsp;Pokaż faktury z okresu</a></li>
				<li><a class="menu_font" href="main.php?action=szukaj&typs=F"><center><?php if ($pokaz_ikony==1) echo "<img src='img/search.gif' align=absmiddle border=0>";?>&nbsp;<b>Szukaj</b></center></a></li>
			</ul>
		</li>
		<?php } ?>
		<li><a class="menu_font" href="p_towary_dostepne.php?view=normal">&nbsp;Towary na stanie</a>
			<ul>
				<li><a class="menu_font" href="p_towary_dostepne.php?view=normal"><?php if ($pokaz_ikony==1) echo "<img src='img/package.png' border=0 align=absmiddle>";?>&nbsp;Pokaż wszystko</a></li>
				<li><a class="menu_font" href="p_towary_wybor.php"><?php if ($pokaz_ikony==1) echo "<img src='img/package_green.png' border=0 align=absmiddle>";?>&nbsp;Pokaż wybraną grupę</a>
			</ul>
		</li>
		<?php $accessLevels = array("9"); if(array_search($es_prawa, $accessLevels)>-1){ ?>		
		<li><a class="menu_font" href="p_towary_przesuniecia.php"><?php if ($pokaz_ikony==1) echo "<img src='img/package_go.png' border=0 align=absmiddle>";?>&nbsp;Przesunięcia towarów</a></li>
		<?php } ?>
		<li><a class="menu_font" href="z_towary_zestawy.php">&nbsp;Zestawy</a>
			<ul>
				<li><a class="menu_font" href="p_towary_dostepne.php?view=dozestawu&addto=0"><?php if ($pokaz_ikony==1) echo "<img src='img/basket_add.png' border=0 align=absmiddle>";?>&nbsp;Utwórz nowy zestaw</a></li>
				<li><a class="menu_font" href="z_towary_zestawy.php"><?php if ($pokaz_ikony==1) echo "<img src='img/basket_go.png' border=0 align=absmiddle>";?>&nbsp;Pokaż zestawy</a></li>
			</ul>
		</li>
		<?php $accessLevels = array("9"); if(array_search($es_prawa, $accessLevels)>-1){ ?>		
		<li><a class="menu_font" href="#">&nbsp;Raporty</a>
			<ul>
				<li><a class="menu_font" href="main.php?action=pswo"><?php if ($pokaz_ikony==1) echo "<img src='img/calendar.png' align=absmiddle border=0>";?>&nbsp;Pokaż sprzedaż w okresie</a></li>
				<li><a class="menu_font" href="main.php?action=grzs"><?php if ($pokaz_ikony==1) echo "<img src='img/coins.png' border=0 align=absmiddle>";?>&nbsp;Generuj raport ze sprzedaży</a></li>
				<li><a class="menu_font" href="main.php?action=aktsm"><?php if ($pokaz_ikony==1) echo "<img src='img/sum.png' border=0 align=absmiddle>";?>&nbsp;Aktualny stan magazynu</a></li>
				<?php /*
				<li><a class="menu_font" href="main.php?action=onkm">&nbsp;Sprzedaż w okresie</a></li>
				<li><a class="menu_font" href="main.php?action=aktsm">&nbsp;Stan magazynu na wybrany dzień</a></li>
				<li><a class="menu_font" href="r_towary_sprzedaz.php?showall=0">&nbsp;Pełna historia sprzedaży</a></li>				
*/ ?>
			</ul>
		</li>
		<?php } ?>
		<li><a class="menu_font" href="main.php?action=szukaj&typs=S"><center><?php if ($pokaz_ikony==1) echo "<img src='img/search.gif' align=absmiddle border=0>";?>&nbsp;<b>Szukaj</b></center></a></li>
	</ul>
	</li>
	<li><a class="menu_font" href="#">&nbsp;Ewidencja sprzętu&nbsp;</a>
		<ul>
			<?php $accessLevels = array("0","1","9"); if(array_search($es_prawa, $accessLevels)>-1){ ?>
			<li><a class="menu_font" href="#" onclick="newWindow_r(820,600,'d_ewidencja.php')"><?php if ($pokaz_ikony==1) echo "<img src='img/computer_add.png' border=0 align=absmiddle>";?>&nbsp;Dodaj sprzęt</a></li>
			<?php } ?>
		<li><a class="menu_font" href="p_ewidencja.php?view=simple&sel_up=all&printpreview=0">&nbsp;Pokaż sprzęt</a>
			<ul>
				<li><a class="menu_font" href="p_ewidencja.php?view=all&sel_up=all&printpreview=0"><?php if ($pokaz_ikony==1) echo "<img src='img/ew_szczegolowy.png' border=0 align=absmiddle>";?>&nbsp;Widok szczegółowy </a></li>
				<li><a class="menu_font" href="p_ewidencja.php?view=simple&sel_up=all&printpreview=0"><?php if ($pokaz_ikony==1) echo "<img src='img/ew_prosty.png' border=0 align=absmiddle>";?>&nbsp;Widok prosty</a></li>
				<li><a class="menu_font" href="p_ewidencja_uzytkownika.php"><?php if ($pokaz_ikony==1) echo "<img src='img/ew_user.png' border=0 align=absmiddle>";?>&nbsp;Widok użytkownika</a></li>
				<li><a class="menu_font" href="main.php?action=ewid_choose"><?php if ($pokaz_ikony==1) echo "<img src='img/building.png' border=0 align=absmiddle>";?>&nbsp;Z wybranej lokalizacji</a></li>
				<li><a class="menu_font" href="p_ewidencja.php?action=ewid_all&view=simple&sel_up=all&printpreview=0&g=1"><?php if ($pokaz_ikony==1) echo "<img src='img/gwarancja.gif' border=0 align=absmiddle>";?>&nbsp;Na gwarancji</a></li>
				<li><a class="menu_font" href="p_ewidencja.php?action=ewid_all&view=simple&sel_up=all&printpreview=0&o=none"><?php if ($pokaz_ikony==1) echo "<img src='img/software_none.gif' border=0 align=absmiddle>";?>&nbsp;Bez oprogramowania</a></li>
			</ul>
		</li>
		<li><a class="menu_font" href="#">&nbsp;Operacje specjalne</a>
			<ul>
				<li><a class="menu_font" href="p_ewidencja.php?view=simple&sel_up=all&printpreview=0&ew_action=move"><?php if ($pokaz_ikony==1) echo "<img src='img/przesuniecie.gif' border=0 align=absmiddle>";?>&nbsp;Przesunięcie sprzętu</a></li>
				<li><a class="menu_font" href="r_ewidencja_przesuniecia.php?showall=0"><?php if ($pokaz_ikony==1) echo "<img src='img/historia_przes.png' align=absmiddle border=0>";?>&nbsp;Historia przesunięć sprzętu</a></li>
				<li><a class="menu_font" href="p_ewidencja.php?view=simple&sel_up=all&printpreview=0&ew_action=change"><?php if ($pokaz_ikony==1) echo "<img src='img//remont.gif' border=0 align=absmiddle>";?>&nbsp;Remont sprzętu</a></li>
				<li><a class="menu_font" href="r_ewidencja_remonty.php?showall=0"><?php if ($pokaz_ikony==1) echo "<img src='img/historia_rem.png' align=absmiddle border=0>";?>&nbsp;Historia remontów sprzętu</a></li>
		<?php $accessLevels = array("1","9"); if(array_search($es_prawa, $accessLevels)>-1){ ?>
				<li><a class="menu_font" href="p_ewidencja.php?view=simple&sel_up=all&printpreview=0&ew_action=delete"><?php if ($pokaz_ikony==1) echo "<img src='img//likwidacja.gif' border=0 align=absmiddle>";?>&nbsp;Usunięcie sprzętu z ewidencji</a></li>		
				<li><a class="menu_font" href="r_ewidencja_usuniecia.php?showall=0"><?php if ($pokaz_ikony==1) echo "<img src='img/historia_likw.png' align=absmiddle border=0>";?>&nbsp;Historia usunięć sprzętu</a></li>
		<?php } ?>
			</ul>
		</li>
		<?php $accessLevels = array("1","9"); if(array_search($es_prawa, $accessLevels)>-1){ ?>
		<li><a class="menu_font" href="#">&nbsp;Raporty zbiorcze</a>
			<ul>
				<li><a class="menu_font" href="#" onClick="newWindow_r(800,600,'r_typ_sprzetu.php')"><?php if ($pokaz_ikony==1) echo "<img src='img//raport_sprz.png' align=absmiddle border=0>";?>&nbsp;Typy sprzętu wg rodzaju</a></li>
				<li><a class="menu_font" href="#" onClick="newWindow_r(800,600,'r_typ_oprogramowania.php')"><?php if ($pokaz_ikony==1) echo "<img src='img//raport_opr.png' align=absmiddle border=0>";?>&nbsp;Typy oprogramowania wg rodzaju</a></li>
				<li><a class="menu_font" href="#" onClick="newWindow_r(800,600,'r_typ_drukarki.php')"><?php if ($pokaz_ikony==1) echo "<img src='img//raport_druk.png' align=absmiddle border=0>";?>&nbsp;Typy drukarek wg rodzaju</a></li>
				<li><a class="menu_font" href="main.php?action=es_plot1"><?php if ($pokaz_ikony==1) echo "<img src='img/chart_bar.png' border=0 align=absmiddle>";?>&nbsp;Wykresy</a></li>			
			</ul>
		</li>
		<?php } ?>
		
		<li><a class="menu_font" href="main.php?action=szukaj&typs=E"><center><?php if ($pokaz_ikony==1) echo "<img src='img/search.gif' align=absmiddle border=0>";?>&nbsp;<b>Szukaj</b></center></a></li>
		</ul>
	</li>
	<li><a class="menu_font" href="main.php">&nbsp;Czynności/Zadania&nbsp;</a>
		<ul>
			<li><a class="menu_font" href="#" onClick="newWindow_r(800,400,'p_komorka_wybierz.php?filtruj=all')"><?php if ($pokaz_ikony==1) echo "<img src='img/clock_add.png' border=0 align=absmiddle>";?>&nbsp;Dodaj czynność do wykonania</a></li>
			<?php $accessLevels = array("9"); if(array_search($es_prawa, $accessLevels)>-1){ ?>
			<li><a class="menu_font" href="z_komorka_czynnosc.php?s=nowe">&nbsp;Pokaż czynności</a>
				<ul>
					<li><a class="menu_font" href="z_komorka_czynnosc.php?s=nowe"><?php if ($pokaz_ikony==1) echo "<img src='img/clock_play.png' border=0 align=absmiddle>";?>&nbsp;Nowe</a></li>
					<li><a class="menu_font" href="z_komorka_czynnosc.php?s=zakonczone"><?php if ($pokaz_ikony==1) echo "<img src='img/clock_stop.png' border=0 align=absmiddle>";?>&nbsp;Zakończone</a></li>
				</ul>
			</li>
			
			<li><a class="menu_font" href="#" onclick="newWindow(600,255,'d_zadanie.php')"><?php if ($pokaz_ikony==1) echo "<img src='img/cog_add.png' border=0 align=absmiddle>";?>&nbsp;Dodaj zadanie  do wykonania</a></li>
			<?php } ?>
			<li><a class="menu_font" href="z_zadania.php?s=otwarte">&nbsp;Pokaż zadania</a>
				<ul>
				<?php $accessLevels = array("9"); if(array_search($es_prawa, $accessLevels)>-1){ ?>
					<li><a class="menu_font" href="z_zadania.php?s=nowe"><?php if ($pokaz_ikony==1) echo "<img src='img/cog_edit.png' border=0 align=absmiddle>";?>&nbsp;Przygotowywane</a></li>
				<?php } ?>
					<li><a class="menu_font" href="z_zadania.php?s=otwarte"><?php if ($pokaz_ikony==1) echo "<img src='img/cog_play.png' border=0 align=absmiddle>";?>&nbsp;Otwarte</a></li>	
				<?php $accessLevels = array("9"); if(array_search($es_prawa, $accessLevels)>-1){ ?>
					<li><a class="menu_font" href="z_zadania.php?s=zakonczone"><?php if ($pokaz_ikony==1) echo "<img src='img/cog_stop.png' border=0 align=absmiddle>";?>&nbsp;Zakończone</a></li>
				<?php } ?>
				</ul>
			</li>
		</ul>
	</li>
	<li><a class="menu_font" href="#">&nbsp;Awarie WAN&nbsp;</a>
		<ul>
			<li><a class="menu_font" href="#" onclick="newWindow(600,220,'d_awaria.php')"><?php if ($pokaz_ikony==1) echo "<img src='img/transmit_add.png' border=0 align=absmiddle>";?>&nbsp;Zarejestruj awarię łącza WAN</a></li>
			<li><a class="menu_font" href="z_awarie.php"><?php if ($pokaz_ikony==1) echo "<img src='img/wan_error.gif' border=0 align=absmiddle>";?>&nbsp;Pokaż otwarte zgłoszenia</a></li>
			<li><a class="menu_font" href="z_awarie_zamkniete.php"><?php if ($pokaz_ikony==1) echo "<img src='img/transmit.png' border=0 align=absmiddle>";?>&nbsp;Pokaż zamknięte zgłoszenia</a></li>
			<?php $accessLevels = array("1","9"); if(array_search($es_prawa, $accessLevels)>-1){ ?>
			<li><a class="menu_font" href="main.php?action=hawo"><?php if ($pokaz_ikony==1) echo "<img src='img/calendar.png' align=absmiddle border=0>";?>&nbsp;Historia awarii WAN'u w okresie</a></li>
			<?php } ?>
			<li><a class="menu_font" href="main.php?action=szukaj&typs=A"><center><?php if ($pokaz_ikony==1) echo "<img src='img/search.gif' align=absmiddle border=0>";?>&nbsp;<b>Szukaj</b></center></a></li>		
		</ul>		
	</li>
	<li><a class="menu_font" href="#">&nbsp;Protokoły&nbsp;</a>
		<ul>
			<li><a class="menu_font" href="#" onclick="newWindow_r(700,595,'utworz_protokol.php')"><?php if ($pokaz_ikony==1) echo "<img src='img/print.gif' border=0 align=absmiddle'>";?>&nbsp;Utwórz protokół</a></li>
			<li><a class="menu_font" href="z_protokol.php?okresm=<?php echo Date('m');?>&okresr=<?php echo Date('Y'); ?>"><?php if ($pokaz_ikony==1) echo "<img src='img/protokoly.gif' border=0 align=absmiddle>";?>&nbsp;Przeglądaj protokoły</a></li>
			<li><a class="menu_font" href="main.php?action=info"><?php if ($pokaz_ikony==1) echo "<img src='img/information.png' border=0 align=absmiddle>";?>&nbsp;Informacje o ustawieniach strony</a></li>
		</ul>
	</li>
	<li><a class="menu_font" href="#">&nbsp;Baza&nbsp;</a>
		<ul>
			<?php $accessLevels = array("9"); if(array_search($es_prawa, $accessLevels)>-1){ ?>
			<li><a class="menu_font" href="z_uzytkownicy.php?showall=0"><?php if ($pokaz_ikony==1) echo "<img src='img/database_users.png' border=0 align=absmiddle>";?>&nbsp;Pracowników</a></li>
			<li><a class="menu_font" href="z_pion.php"><?php if ($pokaz_ikony==1) echo "<img src='img/database_piony.png' border=0 align=absmiddle>";?>&nbsp;Pionów</a></li>
			<li><a class="menu_font" href="z_umowa.php"><?php if ($pokaz_ikony==1) echo "<img src='img/database_umowy.png' border=0 align=absmiddle>";?>&nbsp;Umów</a></li>
			<li><a class="menu_font" href="z_filie.php"><?php if ($pokaz_ikony==1) echo "<img src='img/database_filie.png' border=0 align=absmiddle>";?>&nbsp;Filii</a></li>
			<?php } ?>
			<li><a class="menu_font" href="z_komorka.php?showall=0"><?php if ($pokaz_ikony==1) echo "<img src='img/database_up.png' border=0 align=absmiddle>";?>&nbsp;Urzędów/komórek</a></li>
			<li><a class="menu_font" href="z_firmy_zewnetrzne.php"><?php if ($pokaz_ikony==1) echo "<img src='img/database_fz.png' border=0 align=absmiddle>";?>&nbsp;Firm zewnętrznych</a></li>			
			<li><a class="menu_font" href="z_oprogramowanie.php"><?php if ($pokaz_ikony==1) echo "<img src='img/database_opr.png' border=0 align=absmiddle>";?>&nbsp;Oprogramowania</a></li>
			<li><a class="menu_font" href="z_typ_sprzetu.php"><?php if ($pokaz_ikony==1) echo "<img src='img/database_typy.png' border=0 align=absmiddle>";?>&nbsp;Typów sprzętu komputerowego</a></li>
			<li><a class="menu_font" href="z_konfiguracja.php"><?php if ($pokaz_ikony==1) echo "<img src='img/database_konf.png' border=0 align=absmiddle>";?>&nbsp;Typów komputerów z konfiguracją</a></li>
			<li><a class="menu_font" href="z_monitorami.php"><?php if ($pokaz_ikony==1) echo "<img src='img/database_monitor.png' border=0 align=absmiddle>";?>&nbsp;Modeli monitorów</a></li>
			<li><a class="menu_font" href="z_drukarka.php"><?php if ($pokaz_ikony==1) echo "<img src='img/database_print.png' border=0 align=absmiddle>";?>&nbsp;Modeli drukarek</a></li>
		</ul>
	</li>

	<li><a class="menu_font" title=" Wróć do poprzedniej strony " href="javascript:history.go(-1);"><img src="img/back.png" border="0" height="16"></a></li> 
	<li><a class="menu_font" title=" Odśwież zawartość strony " href="javascript:window.location.reload( true );"><img src="img//refresh.gif" border="0" height="16"></a></li>  
	<li><a class="menu_font" title=" Przejdź do następnej strony " href="javascript:history.go(1);"><img src="img/forward.png" border="0" height="16"></a></li>
	<li><a class="menu_font" title=" Szukaj " href="main.php?action=szukaj&typs=all"><img src="img/search.gif" border="0" height="16"></a></li>
	<li><a class="menu_font" href="#" title=" Wyloguj się " onClick="javascript:if(confirm('Czy napewno chcesz się wylogować ?')) document.location.href='logout.php'"><img src="img/logout1.gif" border="0"></a></li>
	<li><a class="menu_font" href="#" title=" Zakończ pracę z bazą eSerwis " onClick="javascript:if(confirm('Czy napewno chcesz zakończyć pracę z bazą eSerwis ?')) document.location.href='exit.php'"><img src="img/logout.gif" border="0"></a></li>	
</ul>
</div>
</td>
</tr>
</table>

<?php
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
}

addEvent(window, 'load', new Function('listMenu.activateMenu("listMenuRoot", arrow)'));

//]]>
</script>

<?php
switch ($action) {
	case "asm"				: include_once('p_magazyn_aktualne.php');	 		break;	
	case "cm"				: include_once('p_magazyn_wszystko.php');	  		break;	
	case "sw"				: include_once('p_magazyn_pobrany.php');			break;	
	case "asm_p"			: include_once('e_magazyn_pobierz.php');			break;
	case "sw_z"				: include_once('z_magazyn_zwroty.php');				break;	
	case "rso"				: include_once('p_magazyn_historia_okres.php');		break;
	case "rswgup"			: include_once('p_magazyn_historia_wg_up.php');		break;
	case "us"				: include_once('z_magazyn_ukryj.php');				break;	
	case "pus"				: include_once('z_magazyn_ukryty.php');				break;
	case "rs"				: include_once('p_magazyn_do_rezerwacji.php');		break;
	case "prs"				: include_once('z_magazyn_rezerwacje.php');			break;	
	case "npswsz"			: include_once('p_naprawy_w_naprawie.php');			break;
	case "npus"				: include_once('p_naprawy_pobrane.php');			break;	
	case "npns"				: include_once('p_naprawy_zakonczone.php');			break;	
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
	case "pmf"				: include_once('p_towary_przesuniecia.php');		break; 
	default 				: include_once('p_naprawy_po_terminie.php');  
							if ($pokazraport==1) { include_once('p_raport.php'); }
							break;			
}
include('body_stop.php');
?>
</body>
</html>