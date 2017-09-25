<table class="menu hideme" width="100%">
<tr class="menu">
<td class="menu">
<div id="floatmenu">
<ul class="menulist" id="listMenuRoot">

<?php $accessLevels = array("0","1","9"); if(array_search($es_prawa, $accessLevels)>-1) { ?>

		<?php if (($currentuser==$adminname) && ($es_block==1)) {	?>
			<li><a class="menu_font" href="#" onClick="newWindow(500,105,'e_adminblock.php?a=0')"><img src="img/zwolnij.gif" border="0" width="16" height="16"></a></li>
			<?php } ?>
		<li><a class="menu_font" href="main.php">&nbsp;Start&nbsp;</a>
		<ul>
		<li><a class="menu_font" href="main.php"><?php if ($pokaz_ikony==1) echo "<img src='img/house.gif' border=0 align=absmiddle width=16 height=16>"; ?>&nbsp;Przejdź do strony startowej</a></li>

		<li><a class="menu_font" href="#" onclick="newWindow_chat(800,603,'chat/index.php?name=<?php echo "$es_login";?>')"><?php if ($pokaz_ikony==1) echo "<img src='img/chat.gif' border=0 align=absmiddle width=16 height=16>"; ?>&nbsp;Uruchom Chat</a></li>

		<?php $accessLevels = array("9"); if(array_search($es_prawa, $accessLevels)>-1){ 
			if ($currentuser==$adminname) {	
				?>
				<li><a class="menu_font" href="#">&nbsp;Administracja&nbsp;</a>
				<ul>
				<li><a class="menu_font" href="z_czarna_lista.php?showall=0"><?php if ($pokaz_ikony==1) echo "<img src='img/blacklist.gif' border=0 align=absmiddle width=16 height=16>"; ?>&nbsp;Czarna lista IP</a></li>
				<li><a class="menu_font" target="_blank" href="index_fm.php"><?php if ($pokaz_ikony==1) echo "<img src='img/folder_explore.gif' border=0 align=absmiddle width=16 height=16>"; ?>&nbsp;Eksplorator plików</a></li>
				<li><a class="menu_font" href="#" onclick="newWindow(250,10,'mybackup/backup.php')"><?php if ($pokaz_ikony==1) echo "<img src='img/icon_backup.gif' border=0 align=absmiddle width=16 height=16>"; ?>&nbsp;Zrób backup bazy</a></li>
				<li><a class="menu_font" href="#" onClick="newWindow_r(420,600,'mybackup//restore.php')"><?php if ($pokaz_ikony==1) echo "<img src='img/icon_restore.gif' border=0 align=absmiddle width=16 height=16>"; ?>&nbsp;Przywróć bazę z kopii</a></li>		
				<li><a class="menu_font" href="#" onClick="newWindow(600,200,'e_admininfo.php')"><?php if ($pokaz_ikony==1) echo "<img src='img/information.gif' border=0 align=absmiddle width=16 height=16>"; ?>&nbsp;Pokaż informację od admina</a></li>
				<li><a class="menu_font" href="#" onClick="newWindow(500,105,'e_adminblock.php?a=1')"><?php if ($pokaz_ikony==1) echo "<img src='img/zarezerwuj.gif' border=0 align=absmiddle width=16 height=16>"; ?>&nbsp;Zablokuj dostęp do bazy</a></li>
				<li><a class="menu_font" href="#" onClick="newWindow(500,105,'e_adminblock.php?a=0')"><?php if ($pokaz_ikony==1) echo "<img src='img/zwolnij.gif' border=0 align=absmiddle width=16 height=16>"; ?>&nbsp;Odblokuj dostęp do bazy</a></li>
				<li><a class="menu_font" href="#" onClick="newWindow_r(1024,760,'sqlc/wizmysqladmin.php')"><?php if ($pokaz_ikony==1) echo "<img src='img/edita.gif' border=0 align=absmiddle width=16 height=16>"; ?>&nbsp;MySQLAdmin</a></li>
				<li><a class="menu_font" href="#" onClick="newWindow_r(1024,760,'replicate/replicate.php')"><?php if ($pokaz_ikony==1) echo "<img src='img/edita.gif' border=0 align=absmiddle width=16 height=16>"; ?>&nbsp;Replikuj bazę dla Poczty</a></li>
				<li><a class="menu_font" href="#" onClick="newWindow_r(400,400,'hd_statystyka_uaktualnij.php')"><?php if ($pokaz_ikony==1) echo "<img src='img/edita.gif' border=0 align=absmiddle width=16 height=16>"; ?>&nbsp;Uaktualnij tabelę ze statystykami</a></li>
				<li><a class="menu_font" href="#" onClick="newWindow_r(800,600,'hd_spojnosc_zgloszen_ze_statystyka.php')"><?php if ($pokaz_ikony==1) echo "<img src='img/edita.gif' border=0 align=absmiddle width=16 height=16>"; ?>&nbsp;Sprawdź spójność</a></li>
				<?php $accessLevels = array("9"); if((array_search($es_prawa, $accessLevels)>-1) && ($es_m=='1')) { ?>
					<li><a class="menu_font" href="1test.php"><?php if ($pokaz_ikony==1) echo "<img src='img/database_up.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Wyczyść tabele ze zgłoszeniami</a></li>
					<?php } ?>				
				<?php $accessLevels = array("9"); if(array_search($es_prawa, $accessLevels)>-1){ 
					if ($currentuser==$adminname) {	
						?>
						<li><a class="menu_font" href="#" onclick="newWindow_r(500,100,'test.php')"><?php if ($pokaz_ikony==1) echo "<img src='img/database_up.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;<font color="red">Wyczyść wszystkie zgłoszenia</font></a></li>
						<?php 
					} 
				}	
				?>
				<li><a class="menu_font" href="#" onClick="newWindow_r(800,600,'hd_zgloszenia_przelicz.php')"><?php if ($pokaz_ikony==1) echo "<img src='img/edita.gif' border=0 align=absmiddle width=16 height=16>"; ?>&nbsp;<font color="blue">Przelicz czasy etapów dla zgłoszeń</font></a></li>
				</ul>
				</li>
				<?php } 
		} ?>
		<li><a class="menu_font" href="#" onclick="newWindow(600,600,'e_uzytkownika.php?select_id=<?php echo $es_nr; ?>&all=1')"><?php if ($pokaz_ikony==1) echo "<img src='img/vcard.gif' border=0 align=absmiddle width=16 height=16>"; ?>&nbsp;Edytuj swój profil</a></li>
		<li><a class="menu_font" href="#" onclick="newWindow(440,185,'e_haslo.php')"><?php if ($pokaz_ikony==1) echo "<img src='img/textfield_key.png' border=0 align=absmiddle width=16 height=16>"; ?>&nbsp;Zmień hasło</a></li>
		<li><a class="menu_font" href="#" onClick="newWindow_r(800,600,'wersja_info.php?w=<?php echo $wnr; ?>&nouserupdate=1');"><?php if ($pokaz_ikony==1) echo "<img src='img/information.gif' align=absmiddle width=16 height=16 border=0>";?>&nbsp;Zmiany w wersji <?php echo $wnr; ?></a></li>
		
		<li><a class="menu_font" href="#" onClick="newWindow_r(800,600,'wersja_info.php?w=<?php echo $wnr; ?>&old=1');"><?php if ($pokaz_ikony==1) echo "<img src='img/information.gif' align=absmiddle width=16 height=16 border=0>";?>&nbsp;Zmiany w wersjach poprzednich</a></li>

		<?php if ($is_dyrektor!=1) { ?>
		<li><a class="menu_font" href="#"><?php if ($pokaz_ikony==1) echo "<img src='img/1osoba.gif' align=absmiddle width=16 height=16 border=0>";?>&nbsp;Inne&nbsp;</a>		
			<ul>
			<li><a class="menu_font" href="#" onclick="newWindow_r(<?php echo $_COOKIE[max_x]; ?>,<?php echo $_COOKIE[max_y]; ?>,'other/tetris.swf');"><?php if ($pokaz_ikony==1) echo "<img src='img/check_ping_up.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Tetris&nbsp;</a>	</li>
			<li><a class="menu_font" href="#" onclick="newWindow_r(<?php echo $_COOKIE[max_x]; ?>,<?php echo $_COOKIE[max_y]; ?>,'other/CastleDefender.swf');"><?php if ($pokaz_ikony==1) echo "<img src='img/check_ping_up.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Castle Defender&nbsp;</a>	</li>			
			<li><a class="menu_font" href="#" onclick="newWindow_r(<?php echo $_COOKIE[max_x]; ?>,<?php echo $_COOKIE[max_y]; ?>,'other/pasjans.swf');"><?php if ($pokaz_ikony==1) echo "<img src='img/check_ping_up.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Pasjans&nbsp;</a>	</li>			
			<li><a class="menu_font" href="#" onclick="newWindow_r(<?php echo $_COOKIE[max_x]; ?>,<?php echo $_COOKIE[max_y]; ?>,'other/pilkarzyki.swf');"><?php if ($pokaz_ikony==1) echo "<img src='img/check_ping_up.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Piłkarzyki&nbsp;</a>	</li>			
			<li><a class="menu_font" href="#" onclick="newWindow_r(<?php echo $_COOKIE[max_x]; ?>,<?php echo $_COOKIE[max_y]; ?>,'other/kulki.swf');"><?php if ($pokaz_ikony==1) echo "<img src='img/check_ping_up.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Kulki&nbsp;</a>	</li>			

			</ul>
		</li>
		<?php } ?>
		
		<li><a class="menu_font" href="#" onclick="alert('Informacje o bazie eSerwis - wersja <?php echo $wersja;?>\n\nDostępne moduły:\n- sprzęt serwisowy\n- naprawy\n- towary/sprzedaż\n- ewidencja sprzętu\n- ewidencja oprogramowania\n- zadania i czynności\n- awarie\n- helpdesk\n\nAutor bazy: Maciej Adrjanowicz\n\nChciałbym podziękować wszystkim za prawie 13 lat współpracy.\nPozostawia tą bazę dla Waszego użytku, mając nadzieję, że ułatwi Wam to pracę.\n')"><?php if ($pokaz_ikony==1) echo "<img src='img/information.png' align=absmiddle width=16 height=16 border=0>"; ?>&nbsp;O bazie</a></li>
		<li><a class="menu_font" href="#" onClick="javascript:if(confirm('Czy napewno chcesz się wylogować ?')) document.location.href='logout.php'"><?php if ($pokaz_ikony==1) echo "<img src='img/logout1.gif' align=absmiddle width=16 height=16 border=0>";?>&nbsp;Wyloguj się</a></li>		
		<li><a class="menu_font" href="#" onClick="javascript:if(confirm('Czy napewno chcesz zakończyć pracę z bazą eSerwis ?')) document.location.href='exit.php'; return false;"><?php if ($pokaz_ikony==1) echo "<img src='img/logout.gif' align=absmiddle width=16 height=16 border=0>";?>&nbsp;Koniec</a></li>
		</ul>
		</li>
<?php } ?>

<?php $accessLevels = array("0","1","2","9"); if(array_search($es_prawa, $accessLevels)>-1) { ?>
		<li><a class="menu_font" href="#" onclick="newWindow_r(800,600,'hd_d_zgloszenie_simple.php?stage=1')">&nbsp;HelpDesk&nbsp;</a>
		<ul>
		<li><a class="menu_font" href="#" onclick="newWindow_r(800,600,'hd_d_zgloszenie_simple.php?stage=1')"><?php if ($pokaz_ikony==1) echo "<img src='img//helpdesk.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Nowe zgłoszenie</a></li>
		<li><a class="menu_font" href="#" onclick="newWindow_r(800,600,'hd_d_zgloszenie.php?stage=1')"><?php if ($pokaz_ikony==1) echo "<img src='img//helpdesk.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Nowe zgłoszenie (rozszerzone)</a></li>
		<li><a class="menu_font" href="#" onclick="newWindow_r(800,600,'hd_d_zgloszenie_s.php?stage=1&filtr=X-X-X-X&p6=true')"><?php if ($pokaz_ikony==1) echo "<img src='img//helpdesk_s.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Nowe zgłoszenie seryjne</a></li>
		<li><a class="menu_font" href="hd_p_zgloszenia.php?sa=0&p5=BZ&s=AD" onClick="createCookie('hd_p_zgloszenia_div_nr','',365);"><?php if ($pokaz_ikony==1) echo "<img src='img//helpdesk_przegladaj.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Przeglądaj zgłoszenia</a>
		<ul>
			<li><a class="menu_font" href="hd_p_zgloszenia.php?sa=0&p5=BZ&s=AD" onClick="createCookie('hd_p_zgloszenia_div_nr','',365);"><?php if ($pokaz_ikony==1) echo "<img src='img//helpdesk_przegladaj.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Nie zamknięte</a></li>
			<li><a class="menu_font" href="hd_p_zgloszenia.php?sa=0&p5=9&s=AD" onClick="createCookie('hd_p_zgloszenia_div_nr','',365);"><?php if ($pokaz_ikony==1) echo "<img src='img//helpdesk_przegladaj.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Zamknięte</a></li>
			<li><a class="menu_font" href="hd_p_zgloszenia.php?sa=0&p5=&s=AD" onClick="createCookie('hd_p_zgloszenia_div_nr','',365);"><?php if ($pokaz_ikony==1) echo "<img src='img//helpdesk_przegladaj.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Wszystkie</a></li>
			<li><hr /></li>
			<li><a class="menu_font" href="#" onClick="createCookie('hd_p_zgloszenia_div_nr','',365);"><?php if ($pokaz_ikony==1) echo "";?><p align="right"><b>Wg statusu&nbsp;</b></p></a>
				<ul>
					<li><a class="menu_font" href="hd_p_zgloszenia.php?sa=0&p5=BZ&p6=&s=&hd_rps=&page=1&p0=R&p2=&p3=&p6=X&p7=&add=&sk=&st=&ss=" onClick="createCookie('hd_p_zgloszenia_div_nr','',365);"><?php if ($pokaz_ikony==1) echo "<img src='img//helpdesk_przegladaj.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;<font color="red">Pr. rozpoczęcia</font></a></li>
					<li><a class="menu_font" href="hd_p_zgloszenia.php?sa=0&p5=BZ&s=&hd_rps=&page=1&p0=Z&p2=&p3=&p6=X&p7=&add=&sk=&st=&ss=" onClick="createCookie('hd_p_zgloszenia_div_nr','',365);"><?php if ($pokaz_ikony==1) echo "<img src='img//helpdesk_przegladaj.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;<font color="red">Pr. zakończenia</font></a></li>
					<li><a class="menu_font" href="hd_p_zgloszenia.php?sa=0&p5=1&p6=&s=&hd_rps=&page=1&p0=&p2=&p3=&p6=X&p7=&add=&sk=&st=&ss=" onClick="createCookie('hd_p_zgloszenia_div_nr','',365);"><?php if ($pokaz_ikony==1) echo "<img src='img//helpdesk_przegladaj.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;<font color="green">Nowe</font></a></li>
					<li><a class="menu_font" href="hd_p_zgloszenia.php?sa=0&p5=7&s=&hd_rps=&page=1&p2=&p3=&p6=X&p7=&add=&sk=&st=&ss=" onClick="createCookie('hd_p_zgloszenia_div_nr','',365);"><?php if ($pokaz_ikony==1) echo "<img src='img//helpdesk_przegladaj.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Rozp. nie zakończone</a></li>
					<li><a class="menu_font" href="hd_p_zgloszenia.php?sa=0&p5=2&s=&hd_rps=&page=1&p2=&p3=&p6=X&p7=&add=&sk=&st=&ss=" onClick="createCookie('hd_p_zgloszenia_div_nr','',365);"><?php if ($pokaz_ikony==1) echo "<img src='img//helpdesk_przegladaj.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Przypisane</a></li>
					<li><a class="menu_font" href="hd_p_zgloszenia.php?sa=0&p5=3&s=&hd_rps=&page=1&p2=&p3=&p6=X&p7=&add=&sk=&st=&ss=" onClick="createCookie('hd_p_zgloszenia_div_nr','',365);"><?php if ($pokaz_ikony==1) echo "<img src='img//helpdesk_przegladaj.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Rozpoczęte</a></li>
					<li><a class="menu_font" href="hd_p_zgloszenia.php?sa=0&p5=3B&s=&hd_rps=&page=1&p2=&p3=&p6=X&p7=&add=&sk=&st=&ss=" onClick="createCookie('hd_p_zgloszenia_div_nr','',365);"><?php if ($pokaz_ikony==1) echo "<img src='img//helpdesk_przegladaj.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;W firmie</a></li>
					<li><a class="menu_font" href="hd_p_zgloszenia.php?sa=0&p5=3A&s=&hd_rps=&page=1&p2=&p3=&p6=X&p7=&add=&sk=&st=&ss=#_SZ" onClick="createCookie('hd_p_zgloszenia_div_nr','',365);"><?php if ($pokaz_ikony==1) echo "<img src='img//helpdesk_przegladaj.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;W serwisie zewn.</a></li>
					<li><a class="menu_font" href="hd_p_zgloszenia.php?sa=0&p5=6&s=&hd_rps=&page=1&p2=&p3=&p6=X&p7=&add=&sk=&st=&ss=" onClick="createCookie('hd_p_zgloszenia_div_nr','',365);"><?php if ($pokaz_ikony==1) echo "<img src='img//helpdesk_przegladaj.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Do oddania</a></li>
					<li><a class="menu_font" href="hd_p_zgloszenia.php?sa=0&p5=9&s=&hd_rps=&page=1&p2=&p3=&p6=X&p7=&add=&sk=&st=&ss=" onClick="createCookie('hd_p_zgloszenia_div_nr','',365);"><?php if ($pokaz_ikony==1) echo "<img src='img//helpdesk_przegladaj.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Zamknięte</a></li>

					<li><a class="menu_font" href="hd_p_zgloszenia.php?sa=0&p2=6&p5=BZ&s=&hd_rps=&page=1&p3=&p6=X&p7=&add=&sk=&st=&ss=" onClick="createCookie('hd_p_zgloszenia_div_nr','',365);"><?php if ($pokaz_ikony==1) echo "<img src='img//helpdesk_przegladaj.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;<font color="red">Awarie krytyczne</font></a></li>
					<li><a class="menu_font" href="hd_p_zgloszenia.php?sa=0&p2=2&p5=BZ&s=&hd_rps=&page=1&p3=&p6=X&p7=&add=&sk=&st=&ss=" onClick="createCookie('hd_p_zgloszenia_div_nr','',365);"><?php if ($pokaz_ikony==1) echo "<img src='img//helpdesk_przegladaj.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;<font color="red">Awarie zwykłe</font></a></li>
					
					
				</ul>
			</li>		
			<li><hr /></li>
			<li><a class="menu_font" href="hd_p_zgloszenia.php?sa=0&sr=0&p5=BZ&add=ptr" onClick="createCookie('hd_p_zgloszenia_div_nr','',365);"><?php if ($pokaz_ikony==1) echo "<img src='img//przesuniety_termin.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Z przesuniętym terminem</a></li>
			<li><a class="menu_font" href="hd_p_zgloszenia.php?sa=0&sr=0&p5=BZ&add=pzw" onClick="createCookie('hd_p_zgloszenia_div_nr','',365);"><?php if ($pokaz_ikony==1) echo "<img src='img//car_s.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Powiązane z wyjazdem</a></li>
			<li><a class="menu_font" href="hd_p_zgloszenia.php?sa=0&sr=0&p5=BZ&add=pzn" onClick="createCookie('hd_p_zgloszenia_div_nr','',365);"><?php if ($pokaz_ikony==1) echo "<img src='img//naprawa_unknown.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Powiązane z naprawami</a></li>
			<li><a class="menu_font" href="hd_p_zgloszenia.php?sa=0&sr=0&p5=BZ&add=pzpss" onClick="createCookie('hd_p_zgloszenia_div_nr','',365);"><?php if ($pokaz_ikony==1) echo "<img src='img//service.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Powiązane z przek. sprzętu serw.</a></li>
			<li><a class="menu_font" href="hd_p_zgloszenia.php?sa=0&sr=0&p5=BZ&add=sn14" onClick="createCookie('hd_p_zgloszenia_div_nr','',365);"><?php if ($pokaz_ikony==1) echo "<img src='img//sn14.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Starsze niż 14 dni</a></li>
			<li><a class="menu_font" href="hd_p_zgloszenia.php?sa=0&sr=0&p5=BZ&add=rekl" onClick="createCookie('hd_p_zgloszenia_div_nr','',365);"><?php if ($pokaz_ikony==1) echo "<img src='img//have_reklamacyjne.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Reklamacyjne</a></li>				
			<li><a class="menu_font" href="hd_p_zgloszenia.php?sa=0&sr=0&p5=BZ&add=wp" onClick="createCookie('hd_p_zgloszenia_div_nr','',365);"><?php if ($pokaz_ikony==1) echo "<img src='img//wp.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Pow. z wymianą podzespołów</a></li>
			<li><a class="menu_font" href="hd_p_zgloszenia.php?sa=0&sr=0&p5=BZ&add=nr&p2=2" onClick="createCookie('hd_p_zgloszenia_div_nr','',365);"><?php if ($pokaz_ikony==1) echo "<img src='img//az.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Nie rozwiązane - Awarie</a></li>
			<li><a class="menu_font" href="hd_p_zgloszenia.php?sa=0&sr=0&p5=BZ&add=nr&p2=6" onClick="createCookie('hd_p_zgloszenia_div_nr','',365);"><?php if ($pokaz_ikony==1) echo "<img src='img//ak.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Nie rozwiązane - Awarie krytyczne</a></li>
			<li><a class="menu_font" href="hd_p_zgloszenia.php?sa=0&sr=0&p5=BZ&add=ww&p5=BZ" onClick="createCookie('hd_p_zgloszenia_div_nr','',365);"><?php if ($pokaz_ikony==1) echo "<img src='img//car_ww.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Wymagane wyjazdy</a></li>
		
		</ul>
		</li>

		<?php if ($testowa==6) { ?>
		<li><a class="menu_font" href="hd_p_zgloszenia_nowe.php?sa=0&p5=BZ&s=AD" onClick="createCookie('hd_p_zgloszenia_div_nr','',365);"><?php if ($pokaz_ikony==1) echo "<img src='img//helpdesk_przegladaj.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;<font color="red">Przeglądaj zgłoszenia (NOWE)</font></a></li>		
		<?php } ?>
		
		<?php if (($kierownik_nr==$es_nr) || ($is_dyrektor==1)) { ?>
			<li><a class="menu_font" href="main.php?action=hdwz&tzgldata=9&potw_spr=0&tstatus=9"><?php if ($pokaz_ikony==1) echo "<img src='img//helpdesk.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Weryfikuj zgłoszenia</a></li>		
		<?php } ?>
		
		<li><a class="menu_font" href="z_kb_kategorie.php?showall=0&action=view"><?php if ($pokaz_ikony==1) echo "<img src='img/application_go.gif' border=0 align=absmiddle width=16 height=16>";?><p align="right">&nbsp;<b>Baza wiedzy</b>&nbsp;</p></a>
		<ul>
			<li><a class="menu_font" href="#" onclick="newWindow_r(700,500,'d_kb_pytanie.php?poziom=1');"><?php if ($pokaz_ikony==1) echo "<img src='img/information.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Dodaj nowy wątek</a></li>
		<?php $accessLevels = array("9"); if(array_search($es_prawa, $accessLevels)>-1){ ?>
			<li><a class="menu_font" href="z_kb_kategorie.php?showall=0&action=manage"><?php if ($pokaz_ikony==1) echo "<img src='img/application_edit.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Zadządzaj kategoriami</a></li>
			<?php } ?>
			<li><a class="menu_font" href="z_kb_kategorie.php?showall=0&action=view"><?php if ($pokaz_ikony==1) echo "<img src='img/application_go.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Przeglądaj wątki</a></li>
			
			<li><a class="menu_font" href="#" onclick="newWindow_r(800,600,'p_kb_active_users.php');"><?php if ($pokaz_ikony==1) echo "<img src='img/information.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Aktywni użytkownicy</a></li>
			
			<li><a class="menu_font" href="main.php?action=szukaj&typs=B"><center><?php if ($pokaz_ikony==1) echo "<img src='img/search.gif' align=absmiddle border=0 width=16 height=16>"; ?>&nbsp;<b>Szukaj</b></center></a></li>
		</ul>
		</li>
		
		<li><a class="menu_font" href="#" onclick="newWindow_r(600,400,'hd_d_note.php?user_id=<?php echo $es_nr; ?>&norefresh=1');"><?php if ($pokaz_ikony==1) echo "<img src='img//hd_note_add.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Nowa notatka</a></li>
		
		<li><a class="menu_font" href="#" onclick="newWindow_r(800,600,'hd_potwierdzenie.php');"><?php if ($pokaz_ikony==1) echo "<img src='img//helpdesk_delegacja_pieczatki.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Drukuj puste potwierdzenie</a></li>
		
		<li><a class="menu_font" href="#" onclick="newWindow_r(<?php echo $_COOKIE[max_x]; ?>,<?php echo $_COOKIE[max_y]; ?>,'hd_g_delegacje.php?osoba=<?php echo urlencode($currentuser); ?>&okres=<?php echo Date("Y-m"); ?>');"><?php if ($pokaz_ikony==1) echo "<img src='img/helpdesk_delegacja.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Delegacje&nbsp;</a>		
		<ul>
		<li><a class="menu_font" href="#" onclick="newWindow_r(800,600,'hd_g_delegacje_pieczatki.php?param=1')"><?php if ($pokaz_ikony==1) echo "<img src='img/helpdesk_delegacja_pieczatki.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Nowa karta dla pieczątek</a></li>
		<li><a class="menu_font" href="#" onclick="newWindow_r(<?php echo $_COOKIE[max_x]; ?>,<?php echo $_COOKIE[max_y]; ?>,'hd_g_delegacje.php?osoba=<?php echo urlencode($currentuser); ?>&okres=<?php echo Date("Y-m"); ?>');"><?php if ($pokaz_ikony==1) echo "<img src='img/helpdesk_delegacja.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Załącznik do delegacji</a></li>
		</ul>
		</li>
		
		<li><a class="menu_font" href="#" onclick="newWindow_r(800,600,'hd_p_komorki.php');"><?php if ($pokaz_ikony==1) echo "<img src='img/database_users.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Słowniki&nbsp;</a>		
			<ul>
				<li><a class="menu_font" href="#" onclick="newWindow_r(800,600,'hd_p_komorki.php');"><?php if ($pokaz_ikony==1) echo "<img src='img/database_users.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Wykaz telefonów</a>
				</li>
				<li><a class="menu_font" href="#" onclick="newWindow_r(<?php echo $_COOKIE[max_x]; ?>,<?php echo $_COOKIE[max_y]; ?>,'hd_z_osoby_zglaszajace.php?view=all');"><?php if ($pokaz_ikony==1) echo "<img src='img/database_users.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Osoby zgłaszające</a>
				</li>
				<li><a class="menu_font" href="#" onclick="newWindow_r(700,600,'hd_z_slownik_tresci.php?p6=<?php echo urlencode($currentuser); ?>&akcja=manage');"><?php if ($pokaz_ikony==1) echo "<img src='img/database_users.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Słownik treści zgłoszeń</a>
				</li>
			</ul>
		</li>
		
		<li><a class="menu_font" href="main.php?action=hdgro&tzgldata=9"><?php if ($pokaz_ikony==1) echo "<img src='img/calendar.png' align=absmiddle width=16 height=16 border=0>";?>&nbsp;Raporty&nbsp;</a>		
		<ul>
		<li><a class="menu_font" href="main.php?action=hdgro&tzgldata=9"><?php if ($pokaz_ikony==1) echo "<img src='img/calendar.png' align=absmiddle width=16 height=16 border=0>";?>&nbsp;Okresowy ze zgłoszeń</a></li>
		<li><a class="menu_font" href="main.php?action=hdddp&tstatus=9&tzgldata=9"><?php if ($pokaz_ikony==1) echo "<img src='img/calendar.png' align=absmiddle width=16 height=16 border=0>";?>&nbsp;Dzienny dla pracownika</a></li>
		<li><a class="menu_font" href="hd_g_raport_z_wykonania_zgl_s.php"><?php if ($pokaz_ikony==1) echo "<img src='img/calendar.png' align=absmiddle width=16 height=16 border=0>";?>&nbsp;Wykonania zgłoszenia seryjnego</a></li>
		<?php//$accessLevels = array("9"); if ((array_search($es_prawa, $accessLevels)>-1) || ($is_dyrektor==1)) { ?>
		
		<li><a class="menu_font" href="main.php?action=hdzzptr"><?php if ($pokaz_ikony==1) echo "<img src='img/calendar.png' align=absmiddle width=16 height=16 border=0>";?>&nbsp;Przesunięte terminy rozpoczęcia</a></li>
		
		<?php if (($kierownik_nr==$es_nr) || ($is_dyrektor==1)) { ?>
		<?php /* 
			<li><a class="menu_font" href="#" onclick="newWindow_r(<?php echo $_COOKIE[max_x]; ?>,<?php echo $_COOKIE[max_y]; ?>,'hd_g_raport_szczegolowy_ze_zgloszen.php?okres=D&rok=<?php echo date('Y'); ?>')"><?php if ($pokaz_ikony==1) echo "<img src='img/helpdesk_delegacja.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Szczegółowy ze zgłoszeń</a></li>
		*/ ?>
			<li><a class="menu_font" href="#" onclick="newWindow_r(800,600,'hd_g_statystyka_zgloszen.php?okres=T&rok=<?php echo date('Y'); ?>')"><?php if ($pokaz_ikony==1) echo "<img src='img/helpdesk_delegacja.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Okresowy ze zgłoszeń (dla Poczty)</a></li>
			
			<li><a class="menu_font" href="#" onclick="newWindow_r(800,600,'hd_g_statystyka_zgloszen_rozbudowany.php?okres=T&rok=<?php echo date('Y'); ?>')"><?php if ($pokaz_ikony==1) echo "<img src='img/helpdesk_delegacja.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Okresowy ze zgłoszeń-rozbudowany</a></li>

			<?php
				if (($is_dyrektor==1) || ($es_m==1)) { $set_zakres = 'all'; } else { $set_zakres = $es_filia; }
			?>
		
			<li><a class="menu_font" href="#" onclick="newWindow_r(<?php echo $_COOKIE[max_x]; ?>,<?php echo $_COOKIE[max_y]; ?>,'hd_g_raport_zbiorczy.php?okres=T&rok=<?php echo date('Y'); ?>&zakres=<?php echo $set_zakres; ?>&kat=all'); "><?php if ($pokaz_ikony==1) echo "<img src='img/helpdesk_delegacja.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Zbiorczy ze zgłoszeń dla oddziału</a></li>
			
			<?php } ?>
			
			<?php if (($kierownik_nr==$es_nr) || ($is_dyrektor==1)) { ?>
					<li><a class="menu_font" href="#" onclick="newWindow_r(800,600,'hd_g_raport_sumaryczny_ze_zgloszen.php?okres=T&rok=<?php echo date('Y'); ?>')"><?php if ($pokaz_ikony==1) echo "<img src='img/helpdesk_delegacja.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Okresowy sumaryczny <sup><font color=red>NOWY</font></sup></a></li>
			<?php } ?>
			
		<?php //} ?>
		</ul>
		</li>
		
		<li><a class="menu_font" href="main.php?action=hdgro&tzgldata=9"><?php if ($pokaz_ikony==1) echo "<img src='img/1osoba.gif' align=absmiddle width=16 height=16 border=0>";?>&nbsp;Prace administracyjne&nbsp;</a>		
			<ul>
<?php /*			<li><a class="menu_font" href="#" onclick="newWindow_r(<?php echo $_COOKIE[max_x]; ?>,<?php echo $_COOKIE[max_y]; ?>,'hd_check_ping.php');"><?php if ($pokaz_ikony==1) echo "<img src='img/check_ping_up.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Komunikacja z placówkami&nbsp;</a>	</li>
*/ ?>
			<li><a class="menu_font" href="hd_check_backups_new.php">
			<?php if ($pokaz_ikony==1) echo "<img src='img/check_backups.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Sprawdzenie wykonania kopii&nbsp;</a>
			</li>
			<li><a class="menu_font" href="hd_check_backups_new.php?view=error">
			<?php if ($pokaz_ikony==1) echo "<img src='img/check_backups.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Sprawdzenie wykonania kopii-problemy&nbsp;</a>
			</li>
			</ul>
		</li>
		
		<li><a class="menu_font" href="#" onclick="newWindow_r(800,600,'prepare_data_for_chart.php?ran=notclosed&who=<?php echo urlencode($currentuser); ?>');"><?php if ($pokaz_ikony==1) echo "<img src='img/wykres.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Wykresy&nbsp;</a>		
		<ul>
		<li><a class="menu_font" href="#" onclick="newWindow_r(800,600,'prepare_data_for_chart.php?ran=notclosed&who=<?php echo urlencode($currentuser); ?>');"><?php if ($pokaz_ikony==1) echo "<img src='img/wykres.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Zgłoszenia niezamknięte (moje)</a></li>
		<li><a class="menu_font" href="#" onclick="newWindow_r(800,600,'prepare_data_for_chart.php?ran=all&who=<?php echo urlencode($currentuser); ?>');"><?php if ($pokaz_ikony==1) echo "<img src='img/wykres.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Wszystkie moje zgłoszenia</a></li>
		
		<li><a class="menu_font" href="#" onclick="newWindow_r(800,600,'prepare_data_for_chart.php?ran=notclosed&who=0');"><?php if ($pokaz_ikony==1) echo "<img src='img/wykres.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Zgłoszenia niezamknięte (wszyscy)</a></li>
		<li><a class="menu_font" href="#" onclick="newWindow_r(800,600,'prepare_data_for_chart.php?ran=all&who=0');"><?php if ($pokaz_ikony==1) echo "<img src='img/wykres.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Zgłoszenia wszystkie</a></li>
		</ul>
		</li>
		<li><a class="menu_font" href="hd_p_zgloszenia.php?sa=0&sr=1"><b><center><?php if ($pokaz_ikony==1) echo "<img src='img//search.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Szukaj zgłoszenia</center></b></a></li>
		</ul>
		</li>
	<?php } ?>

	<?php $accessLevels = array("0","1","9"); if(array_search($es_prawa, $accessLevels)>-1) { ?>
		<li><a class="menu_font" href="#">&nbsp;Sprzęt serwisowy&nbsp;</a>
		<ul>
		<?php if ($es_m!=1) { ?>
			<?php $accessLevels = array("9"); if(array_search($es_prawa, $accessLevels)>-1){ ?>
				<li><a class="menu_font" href="#" onclick="newWindow(500,340,'d_magazyn.php')"><?php if ($pokaz_ikony==1) echo "<img src='img/computer_add.png' align=absmiddle width=16 height=16 border=0>";?>&nbsp;Dodaj nowy sprzęt</a></li>
				<?php } ?> 
			<li><a class="menu_font" href="e_magazyn_pobierz.php?kolor=<?php echo $default_kolorowanie; ?>"><?php if ($pokaz_ikony==1) echo "<img src='img/pobierz.gif' align=absmiddle width=16 height=16 border=0>"; ?>&nbsp;Pobierz sprzęt</a></li>
			<li><a class="menu_font" href="z_magazyn_zwroty.php?kolor=<?php echo $default_kolorowanie; ?>"><?php if ($pokaz_ikony==1) echo "<img src='img//return.gif' align=absmiddle width=16 height=16 border=0>";?>&nbsp;Zwróć sprzęt</a></li>
			<li><a class="menu_font" href="main.php?action=rs&kolor=<?php echo $default_kolorowanie; ?>"><?php if ($pokaz_ikony==1) echo "<img src='img/zarezerwuj.gif' align=absmiddle width=16 height=16 border=0>";?>&nbsp;Zarezerwuj sprzęt</a></li>
			<li><a class="menu_font" href="main.php?action=us&kolor=<?php echo $default_kolorowanie; ?>"><?php if ($pokaz_ikony==1) echo "<img src='img/ukryj.gif' align=absmiddle width=16 height=16 border=0>";?>&nbsp;Ukryj sprzęt</a></li>
			<?php } ?>
		<li><a class="menu_font" href="main.php?action=cm"><?php if ($pokaz_ikony==1) echo "<img src='img/pokaz_caly.png' align=absmiddle width=16 height=16 border=0>";?>&nbsp;Pokaż</a>
		<ul>
		<li><a class="menu_font" href="main.php?action=cm&kolor=<?php echo $default_kolorowanie; ?>"><?php if ($pokaz_ikony==1) echo "<img src='img/pokaz_caly.png' align=absmiddle width=16 height=16 border=0>";?>&nbsp;Cały sprzęt</a></li>
		<li><a class="menu_font" href="main.php?action=asm&kolor=<?php echo $default_kolorowanie; ?>"><?php if ($pokaz_ikony==1) echo "<img src='img/pokaz_dostepny.png' align=absmiddle width=16 height=16 border=0>";?>&nbsp;Dostępny sprzęt</a></li>
		<li><a class="menu_font" href="main.php?action=sw&kolor=<?php echo $default_kolorowanie; ?>"><?php if ($pokaz_ikony==1) echo "<img src='img/pokaz_pobrany.gif' align=absmiddle width=16 height=16 border=0>";?>&nbsp;Pobrany sprzęt</a></li>
		<li><a class="menu_font" href="main.php?action=prs&kolor=<?php echo $default_kolorowanie; ?>"><?php if ($pokaz_ikony==1) echo "<img src='img/pokaz_zarez.png' align=absmiddle width=16 height=16 border=0>";?>&nbsp;Zarezerwowany sprzęt</a></li>
		<?php $accessLevels = array("1","9"); if(array_search($es_prawa, $accessLevels)>-1){ ?>
			<li><a class="menu_font" href="main.php?action=pus&kolor=<?php echo $default_kolorowanie; ?>"><?php if ($pokaz_ikony==1) echo "<img src='img/pokaz_ukryty.png' align=absmiddle width=16 height=16 border=0>";?>&nbsp;Ukryty sprzęt</a></li>
			<?php } ?>				
		</ul>
		</li>
		<?php $accessLevels = array("1","9"); if(array_search($es_prawa, $accessLevels)>-1){ ?>		
			<li><a class="menu_font" href="r_magazyn_historia_wszystko.php"><?php if ($pokaz_ikony==1) echo "<img src='img/historia_cala.png' align=absmiddle width=16 height=16 border=0>";?>&nbsp;Raporty</a>
			<ul>
			<li><a class="menu_font" href="r_magazyn_historia_wszystko.php"><?php if ($pokaz_ikony==1) echo "<img src='img/historia_cala.png' align=absmiddle width=16 height=16 border=0>";?>&nbsp;Historia ruchów całego sprzętu</a></li>
			<li><a class="menu_font" href="main.php?action=rso"><?php if ($pokaz_ikony==1) echo "<img src='img/calendar.png' align=absmiddle width=16 height=16 border=0>";?>&nbsp;Historia ruchu sprzętu w okresie</a></li>
			<li><a class="menu_font" href="p_magazyn_historia.php"><?php if ($pokaz_ikony==1) echo "<img src='img/historia_wybr.png' align=absmiddle width=16 height=16 border=0>";?>&nbsp;Historia ruchu wybranego sprzętu</a></li>
			<li><a class="menu_font" href="main.php?action=rswgup"><?php if ($pokaz_ikony==1) echo "<img src='img/historia_wgup.png' align=absmiddle width=16 height=16 border=0>";?>&nbsp;Historia ruchu wg komórki/UP i dat</a></li>
			</ul>
			</li>
			<?php } ?>
		<li><a class="menu_font" href="main.php?action=szukaj&typs=M"><center><?php if ($pokaz_ikony==1) echo "<img src='img/search.gif' align=absmiddle width=16 height=16 border=0>";?>&nbsp;<b>Szukaj</b></center></a></li>
		</ul>
		</li>
		<li><a class="menu_font" href="#">&nbsp;Naprawy&nbsp;</a>
		<ul>
		<?php if ($es_m!=1) { ?>
			<li><a class="menu_font" href="#" onclick="newWindow_r(800,600,'z_naprawy_przyjmij.php?tresc_zgl=')"><?php if ($pokaz_ikony==1) echo "<img src='img//naprawa_prz.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Przyjmij uszkodzony sprzęt</a></li>
			<?php } ?>
		<li><a class="menu_font" href="main.php?action=npus"><?php if ($pokaz_ikony==1) echo "<img src='img/snapraw_p.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Sprzęt na stanie</a>
		<ul>
		<li><a class="menu_font" href="main.php?action=npus"><?php if ($pokaz_ikony==1) echo "<img src='img/snapraw_p.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Uszkodzony (pobrany)</a></li>
		<li><a class="menu_font" href="main.php?action=nsw"><?php if ($pokaz_ikony==1) echo "<img src='img/naprawy_w.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Wycofany z serwisu</a></li>
		</ul>
		</li>
		<li><a class="menu_font" href="main.php?action=npswsz"><?php if ($pokaz_ikony==1) echo "<img src='img/snapraw_w.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Sprzęt naprawiany</a>
		<ul>
		<li><a class="menu_font" href="main.php?action=nwwz"><?php if ($pokaz_ikony==1) echo "<img src='img/snapraw_p.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;We własnym zakresie</a></li>		
		<li><a class="menu_font" href="main.php?action=npswsz"><?php if ($pokaz_ikony==1) echo "<img src='img/snapraw_w.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;W serwisach zewnętrznych</a></li>
		<li><a class="menu_font" href="main.php?action=nsnrl"><?php if ($pokaz_ikony==1) echo "<img src='img/snapraw_w.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;W serwisach zewn. - rynek lokalny</a></li>
		</ul>
		</li>
		<li><a class="menu_font" href="p_naprawy_zakonczone.php"><?php if ($pokaz_ikony==1) echo "<img src='img/snapraw_ok.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Naprawiony sprzęt na stanie</a></li>
		<?php $accessLevels = array("0","1","9"); if(array_search($es_prawa, $accessLevels)>-1){ ?>
			<li><a class="menu_font" href="p_naprawy_wszystko.php"><?php if ($pokaz_ikony==1) echo "<img src='img//naprawa_all.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Raporty</a>
			<ul>
			<li><a class="menu_font" href="p_naprawy_wszystko.php"><?php if ($pokaz_ikony==1) echo "<img src='img//naprawa_all.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Pokaż wszystko</a></li>
			<li><a class="menu_font" href="p_naprawy_historia_wycofane.php"><?php if ($pokaz_ikony==1) echo "<img src='img//naprawa_all.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Naprawy wycofane z serwisów</a></li>
			<li><a class="menu_font" href="p_naprawy_historia_cala.php"><?php if ($pokaz_ikony==1) echo "<img src='img//naprawa_zak.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Historia zakończonych napraw</a></li>
			<li><a class="menu_font" href="main.php?action=nwo"><?php if ($pokaz_ikony==1) echo "<img src='img//naprawa_wgup.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Historia napraw wg UP/komórki</a></li>
			<li><a class="menu_font" href="main.php?action=snd"><?php if ($pokaz_ikony==1) echo "<img src='img//naprawa_wgup.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Stan sprzętu na dzień</a></li>
			</ul>
			</li>
			<?php } ?>
		<li><a class="menu_font" href="main.php?action=szukaj&typs=N"><center><?php if ($pokaz_ikony==1) echo "<img src='img/search.gif' align=absmiddle width=16 height=16 border=0>";?>&nbsp;<b>Szukaj</b></center></a></li>		
		</ul>
		</li>
		<li><a class="menu_font" href="#">&nbsp;Sprzedaż&nbsp;</a>
		<ul>
		<?php $accessLevels = array("1","9"); if(array_search($es_prawa, $accessLevels)>-1){ ?>	
			<li><a class="menu_font" href="z_faktury.php?showall=0"><?php if ($pokaz_ikony==1) echo "<img src='img/faktura_add.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Faktury</a>
			<ul>
			<li><a class="menu_font" href="#" onclick="newWindow_r(800,600,'d_faktura.php')"><?php if ($pokaz_ikony==1) echo "<img src='img/faktura_add.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Dodaj nową fakturę</a></li>
			<li><a class="menu_font" href="z_faktury.php?showall=0"><?php if ($pokaz_ikony==1) echo "<img src='img/faktury_nz.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Faktury niezatwierdzone</a></li>
			<li><a class="menu_font" href="z_faktury_zatwierdzone.php?showall=0"><?php if ($pokaz_ikony==1) echo "<img src='img/accept.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Faktury zatwierdzone</a></li>
			<li><a class="menu_font" href="main.php?action=fsfo"><?php if ($pokaz_ikony==1) echo "<img src='img/calendar.png' align=absmiddle width=16 height=16 border=0>";?>&nbsp;Pokaż faktury z okresu</a></li>
			<li><a class="menu_font" href="main.php?action=szukaj&typs=F"><center><?php if ($pokaz_ikony==1) echo "<img src='img/search.gif' align=absmiddle width=16 height=16 border=0>";?>&nbsp;<b>Szukaj</b></center></a></li>
			</ul>
			</li>
			<?php } ?>
		<li><a class="menu_font" href="p_towary_dostepne.php?view=normal"><?php if ($pokaz_ikony==1) echo "<img src='img/package.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Towary na stanie</a>
		<ul>
		<li><a class="menu_font" href="p_towary_dostepne.php?view=normal"><?php if ($pokaz_ikony==1) echo "<img src='img/package.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Pokaż wszystko</a></li>
		<li><a class="menu_font" href="p_towary_wybor.php"><?php if ($pokaz_ikony==1) echo "<img src='img/package_green.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Pokaż wybraną grupę</a>
		</ul>
		</li>
		<?php $accessLevels = array("9"); if(array_search($es_prawa, $accessLevels)>-1){ ?>		
			<li><a class="menu_font" href="p_towary_przesuniecia.php"><?php if ($pokaz_ikony==1) echo "<img src='img/package_go.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Przesunięcia towarów</a></li>
			<?php } ?>
		<li><a class="menu_font" href="z_towary_zestawy.php"><?php if ($pokaz_ikony==1) echo "<img src='img/basket_go.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Zestawy</a>
		<ul>
		<li><a class="menu_font" href="p_towary_dostepne.php?view=dozestawu&addto=0"><?php if ($pokaz_ikony==1) echo "<img src='img/basket_add.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Utwórz nowy zestaw</a></li>
		<li><a class="menu_font" href="z_towary_zestawy.php"><?php if ($pokaz_ikony==1) echo "<img src='img/basket_go.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Pokaż zestawy</a></li>
		</ul>
		</li>
		<?php $accessLevels = array("9"); if(array_search($es_prawa, $accessLevels)>-1){ ?>		
			<li><a class="menu_font" href="main.php?action=pswo"><?php if ($pokaz_ikony==1) echo "<img src='img/calendar.png' align=absmiddle width=16 height=16 border=0>";?>&nbsp;Raporty</a>
			<ul>
			<li><a class="menu_font" href="main.php?action=pswo"><?php if ($pokaz_ikony==1) echo "<img src='img/calendar.png' align=absmiddle width=16 height=16 border=0>";?>&nbsp;Pokaż sprzedaż w okresie</a></li>
			<li><a class="menu_font" href="main.php?action=grzs"><?php if ($pokaz_ikony==1) echo "<img src='img/coins.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Generuj raport ze sprzedaży</a></li>
			<li><a class="menu_font" href="main.php?action=aktsm"><?php if ($pokaz_ikony==1) echo "<img src='img/sum.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Aktualny stan magazynu</a></li>
			<?php /*
				<li><a class="menu_font" href="main.php?action=onkm">&nbsp;Sprzedaż w okresie</a></li>
				<li><a class="menu_font" href="main.php?action=aktsm">&nbsp;Stan magazynu na wybrany dzień</a></li>
				<li><a class="menu_font" href="r_towary_sprzedaz.php?showall=0">&nbsp;Pełna historia sprzedaży</a></li>				
*/ ?>
			</ul>
			</li>
			<?php } ?>
			
		<li><a class="menu_font" href="main.php?action=zsdwk"><?php if ($pokaz_ikony==1) echo "<img src='img/sum_up.png' align=absmiddle width=16 height=16 border=0>";?>&nbsp;Zestawienie sprzedaży</a>
			<ul>			
				<li><a class="menu_font" href="main.php?action=zsdwk"><?php if ($pokaz_ikony==1) echo "<img src='img/sum_up.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Dla wybranej komórki</a></li>
				<li><a class="menu_font" href="main.php?action=zsds"><?php if ($pokaz_ikony==1) echo "<img src='img/sum_sprzet.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Dla sprzętu</a></li>		
			</ul>
		</li>
			
		<li><a class="menu_font" href="main.php?action=szukaj&typs=S"><center><?php if ($pokaz_ikony==1) echo "<img src='img/search.gif' align=absmiddle width=16 height=16 border=0>";?>&nbsp;<b>Szukaj</b></center></a></li>
		</ul>
		</li>	
		<li><a class="menu_font" href="#">&nbsp;Ewidencja sprzętu&nbsp;</a>
		<ul>
		<?php $accessLevels = array("0","1","9"); if(array_search($es_prawa, $accessLevels)>-1){ ?>
			<li><a class="menu_font" href="#" onclick="newWindow_r(820,600,'d_ewidencja.php')"><?php if ($pokaz_ikony==1) echo "<img src='img/computer_add.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Dodaj sprzęt</a></li>
			<?php } ?>
		<li><a class="menu_font" href="p_ewidencja.php?view=simple&sel_up=all&printpreview=0"><?php if ($pokaz_ikony==1) echo "<img src='img/ew_prosty.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Pokaż sprzęt</a>
		<ul>
		<li><a class="menu_font" href="p_ewidencja.php?view=all&sel_up=all&printpreview=0"><?php if ($pokaz_ikony==1) echo "<img src='img/ew_szczegolowy.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Widok szczegółowy </a></li>
		<li><a class="menu_font" href="p_ewidencja.php?view=simple&sel_up=all&printpreview=0"><?php if ($pokaz_ikony==1) echo "<img src='img/ew_prosty.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Widok prosty</a></li>
		<li><a class="menu_font" href="p_ewidencja_uzytkownika.php"><?php if ($pokaz_ikony==1) echo "<img src='img/ew_user.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Widok użytkownika</a></li>
		<li><a class="menu_font" href="main.php?action=ewid_choose"><?php if ($pokaz_ikony==1) echo "<img src='img/building.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Z wybranej lokalizacji</a></li>
		<li><a class="menu_font" href="p_ewidencja.php?action=ewid_all&view=simple&sel_up=all&printpreview=0&g=1"><?php if ($pokaz_ikony==1) echo "<img src='img/gwarancja.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Na gwarancji</a></li>
		<li><a class="menu_font" href="p_ewidencja.php?action=ewid_all&view=simple&sel_up=all&printpreview=0&o=none"><?php if ($pokaz_ikony==1) echo "<img src='img/software_none.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Bez oprogramowania</a></li>
		<li><a class="menu_font" href="p_ewidencja.php?action=ewid_all&view=simple&sel_up=all&printpreview=0&uwagi=1"><?php if ($pokaz_ikony==1) echo "<img src='img/comment.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Z wpisanymi uwagami</a></li>
		</ul>
		</li>
		<li><a class="menu_font" href="#">&nbsp;Operacje specjalne</a>
		<ul>
		<li><a class="menu_font" href="p_ewidencja.php?view=simple&sel_up=all&printpreview=0&ew_action=move"><?php if ($pokaz_ikony==1) echo "<img src='img/przesuniecie.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Przesunięcie sprzętu</a></li>
		<li><a class="menu_font" href="r_ewidencja_przesuniecia.php?showall=0"><?php if ($pokaz_ikony==1) echo "<img src='img/historia_przes.png' align=absmiddle width=16 height=16 border=0>";?>&nbsp;Historia przesunięć sprzętu</a></li>
		<li><a class="menu_font" href="p_ewidencja.php?view=simple&sel_up=all&printpreview=0&ew_action=change"><?php if ($pokaz_ikony==1) echo "<img src='img//remont.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Remont sprzętu</a></li>
		<li><a class="menu_font" href="r_ewidencja_remonty.php?showall=0"><?php if ($pokaz_ikony==1) echo "<img src='img/historia_rem.png' align=absmiddle width=16 height=16 border=0>";?>&nbsp;Historia remontów sprzętu</a></li>
		<?php $accessLevels = array("1","9"); if(array_search($es_prawa, $accessLevels)>-1){ ?>
			<li><a class="menu_font" href="p_ewidencja.php?view=simple&sel_up=all&printpreview=0&ew_action=delete"><?php if ($pokaz_ikony==1) echo "<img src='img//likwidacja.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Usunięcie sprzętu z ewidencji</a></li>		
			<li><a class="menu_font" href="r_ewidencja_usuniecia.php?showall=0"><?php if ($pokaz_ikony==1) echo "<img src='img/historia_likw.png' align=absmiddle width=16 height=16 border=0>";?>&nbsp;Historia usunięć sprzętu</a></li>
			<?php } ?>
		</ul>
		</li>
		<?php $accessLevels = array("1","9"); if(array_search($es_prawa, $accessLevels)>-1){ ?>
			<li><a class="menu_font" href="#" onClick="newWindow_r(800,600,'r_typ_sprzetu.php')"><?php if ($pokaz_ikony==1) echo "<img src='img//raport_sprz.png' align=absmiddle width=16 height=16 border=0>";?>&nbsp;Raporty zbiorcze</a>
			<ul>
			<li><a class="menu_font" href="#" onClick="newWindow_r(800,600,'r_typ_sprzetu.php')"><?php if ($pokaz_ikony==1) echo "<img src='img//raport_sprz.png' align=absmiddle width=16 height=16 border=0>";?>&nbsp;Typy sprzętu wg rodzaju</a></li>
			<li><a class="menu_font" href="#" onClick="newWindow_r(800,600,'r_typ_oprogramowania.php')"><?php if ($pokaz_ikony==1) echo "<img src='img//raport_opr.png' align=absmiddle width=16 height=16 border=0>";?>&nbsp;Typy oprogramowania wg rodzaju</a></li>
			<li><a class="menu_font" href="#" onClick="newWindow_r(800,600,'r_typ_drukarki.php')"><?php if ($pokaz_ikony==1) echo "<img src='img//raport_druk.png' align=absmiddle width=16 height=16 border=0>";?>&nbsp;Typy drukarek wg rodzaju</a></li>
			<?php /*
				<li><a class="menu_font" href="main.php?action=es_plot1"><?php if ($pokaz_ikony==1) echo "<img src='img/chart_bar.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Wykresy</a></li>
	*/
			?>
			</ul>
			</li>
			<?php } ?>
		
		<li><a class="menu_font" href="main.php?action=szukaj&typs=E"><center><?php if ($pokaz_ikony==1) echo "<img src='img/search.gif' align=absmiddle width=16 height=16 border=0>";?>&nbsp;<b>Szukaj</b></center></a></li>
		</ul>
		</li>
		<li><a class="menu_font" href="main.php">&nbsp;Zadania / Projekty&nbsp;</a>
		<ul>
		<li><a class="menu_font" href="#" onClick="newWindow_r(800,400,'p_komorka_wybierz.php?filtruj=all')"><?php if ($pokaz_ikony==1) echo "<img src='img/clock_add.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Dodaj <b>czynność</b> do wykonania</a></li>
		<?php $accessLevels = array("9"); if(array_search($es_prawa, $accessLevels)>-1){ ?>
			<li><a class="menu_font" href="z_komorka_czynnosc.php?s=nowe"><?php if ($pokaz_ikony==1) echo "<img src='img/clock_play.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Pokaż czynności</a>
			<ul>
			<li><a class="menu_font" href="z_komorka_czynnosc.php?s=nowe"><?php if ($pokaz_ikony==1) echo "<img src='img/clock_play.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Nowe</a></li>
			<li><a class="menu_font" href="z_komorka_czynnosc.php?s=zakonczone"><?php if ($pokaz_ikony==1) echo "<img src='img/clock_stop.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Zakończone</a></li>
			</ul>
			</li>
			
			<li><a class="menu_font" href="#" onclick="newWindow_r(800,600,'d_zadanie.php')"><?php if ($pokaz_ikony==1) echo "<img src='img/cog_add.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Dodaj <b>zadanie</b> do wykonania</a>
			<ul>
				<li><a class="menu_font" href="#" onclick="newWindow_r(800,600,'d_zadanie.php')"><?php if ($pokaz_ikony==1) echo "<img src='img/cog_add.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Nowe</a></li>
				<li><a class="menu_font" href="#" onclick="newWindow_r(800,600,'d_zadanie.php?from=closed&wybierz=')"><?php if ($pokaz_ikony==1) echo "<img src='img/cog_add_special.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Na bazie zadania istniejącego</a></li>
			</ul>
			</li>
			
			<?php } ?>
		<li><a class="menu_font" href="z_zadania.php?s=otwarte"><?php if ($pokaz_ikony==1) echo "<img src='img/cog_play.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Pokaż zadania</a>
		<ul>
		<?php $accessLevels = array("9"); if(array_search($es_prawa, $accessLevels)>-1){ ?>
			<li><a class="menu_font" href="z_zadania.php?s=nowe"><?php if ($pokaz_ikony==1) echo "<img src='img/cog_edit.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Nowe</a></li>
			<?php } ?>
		<li><a class="menu_font" href="z_zadania.php?s=otwarte"><?php if ($pokaz_ikony==1) echo "<img src='img/cog_play.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Otwarte</a></li>	
		<?php $accessLevels = array("9"); if(array_search($es_prawa, $accessLevels)>-1){ ?>
			<li><a class="menu_font" href="z_zadania.php?s=zakonczone"><?php if ($pokaz_ikony==1) echo "<img src='img/cog_stop.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Zakończone</a></li>
			<?php } ?>
			</ul>
		</li>
		<?php if (($kierownik_nr==$es_nr) || ($is_dyrektor==1)) { ?>
		<li><a class="menu_font" href="#" onclick="newWindow_r(800,600,'d_projekt.php')"><?php if ($pokaz_ikony==1) echo "<img src='img/cog_add.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Dodaj <b>projekt</b> do realizacji</a></li>
		<?php } ?>
		
		<li><a class="menu_font" href="z_projekty.php?s=otwarte"><?php if ($pokaz_ikony==1) echo "<img src='img/cog_play.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Pokaż projekty</a>
		<ul>
		<?php $accessLevels = array("9"); if(array_search($es_prawa, $accessLevels)>-1){ ?>
			<li><a class="menu_font" href="z_projekty.php?s=nowe"><?php if ($pokaz_ikony==1) echo "<img src='img/cog_edit.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Nowe</a></li>
			<?php } ?>
		<li><a class="menu_font" href="z_projekty.php?s=otwarte"><?php if ($pokaz_ikony==1) echo "<img src='img/cog_play.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Otwarte</a></li>	
		<?php $accessLevels = array("9"); if(array_search($es_prawa, $accessLevels)>-1){ ?>
			<li><a class="menu_font" href="z_projekty.php?s=zakonczone"><?php if ($pokaz_ikony==1) echo "<img src='img/cog_stop.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Zakończone</a></li>
			<?php } ?>
			
			
		</ul>
		</li>
		</ul>
		</li>
		<li><a class="menu_font" href="#">&nbsp;Awarie&nbsp;</a>
		<ul>
		<li><a class="menu_font" href="#" onclick="newWindow(700,235,'d_awaria.php')"><?php if ($pokaz_ikony==1) echo "<img src='img/transmit_add.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Zarejestruj awarię łącza WAN</a></li>
		<li><a class="menu_font" href="z_awarie.php"><?php if ($pokaz_ikony==1) echo "<img src='img/wan_error.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Pokaż otwarte zgłoszenia</a></li>
		<li><a class="menu_font" href="z_awarie_zamkniete.php"><?php if ($pokaz_ikony==1) echo "<img src='img/transmit.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Pokaż zamknięte zgłoszenia</a></li>
		<?php $accessLevels = array("1","9"); if(array_search($es_prawa, $accessLevels)>-1){ ?>
			<li><a class="menu_font" href="main.php?action=hawo"><?php if ($pokaz_ikony==1) echo "<img src='img/calendar.png' align=absmiddle width=16 height=16 border=0>";?>&nbsp;Historia awarii WAN'u w okresie</a></li>
			<?php } ?>
			<li><a class="menu_font" href="main.php?action=szukaj&typs=A"><center><?php if ($pokaz_ikony==1) echo "<img src='img/search.gif' align=absmiddle width=16 height=16 border=0>";?>&nbsp;<b>Szukaj</b></center></a></li>		
		</ul>		
	</li>
	<li><a class="menu_font" href="#">&nbsp;Protokoły&nbsp;</a>
		<ul>
		<?php if ($kierownik_nr==$es_nr) { $state=''; } else { $state='empty'; } ?>
			<li><a class="menu_font" href="#" onclick="newWindow_r(700,595,'utworz_protokol.php?blank=1&state=<?php echo $state; ?>&nowy=1&source=')"><?php if ($pokaz_ikony==1) echo "<img src='img/print.gif' border=0 align=absmiddle width=16 height=16'>";?>&nbsp;Utwórz protokół</a></li>
			<li><a class="menu_font" href="#" onclick="newWindow_r(700,595,'utworz_protokol_generuj.php?state=empty&nowy=1&wersjap=2&submit=pusty&clear=1')"><?php if ($pokaz_ikony==1) echo "<img src='img/print.gif' border=0 align=absmiddle width=16 height=16'>";?>&nbsp;Utwórz protokół pusty</a></li>			
			<li><a class="menu_font" href="z_protokol.php?okresm=<?php echo Date('m');?>&okresr=<?php echo Date('Y'); ?>"><?php if ($pokaz_ikony==1) echo "<img  src='img/protokoly.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Przeglądaj protokoły</a></li>
			<li><a class="menu_font" href="main.php?action=info"><?php if ($pokaz_ikony==1) echo "<img src='img/information.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Zalecane ustawienia strony</a></li>
		</ul>
	</li>
	<li><a class="menu_font" href="#">&nbsp;Baza&nbsp;</a>
		<ul>
			<?php $accessLevels = array("9"); if(array_search($es_prawa, $accessLevels)>-1){ ?>
			<li><a class="menu_font" href="z_uzytkownicy.php?showall=0"><?php if ($pokaz_ikony==1) echo "<img src='img/database_users.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Pracowników</a></li>
			<li><a class="menu_font" href="z_pion.php"><?php if ($pokaz_ikony==1) echo "<img src='img/database_piony.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Pionów</a></li>
			<li><a class="menu_font" href="z_umowa.php"><?php if ($pokaz_ikony==1) echo "<img src='img/database_umowy.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Umów</a></li>
			<li><a class="menu_font" href="z_filie.php"><?php if ($pokaz_ikony==1) echo "<img src='img/database_filie.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Filii</a></li>
			<?php } ?>
			<li><a class="menu_font" href="z_komorka.php?showall=0&aktywne=1&ko="><?php if ($pokaz_ikony==1) echo "<img src='img/database_up.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Urzędów/komórek</a></li>
			<li><a class="menu_font" href="z_firmy_zewnetrzne.php"><?php if ($pokaz_ikony==1) echo "<img src='img/database_fz.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Firm zewnętrznych</a></li>			
			<li><a class="menu_font" href="z_oprogramowanie.php"><?php if ($pokaz_ikony==1) echo "<img src='img/database_opr.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Oprogramowania</a></li>
			<li><a class="menu_font" href="z_typ_sprzetu.php"><?php if ($pokaz_ikony==1) echo "<img src='img/database_typy.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Typów sprzętu komputerowego</a></li>
			<li><a class="menu_font" href="z_konfiguracja.php"><?php if ($pokaz_ikony==1) echo "<img src='img/database_konf.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Typów komputerów z konfiguracją</a></li>
			<li><a class="menu_font" href="z_monitorami.php"><?php if ($pokaz_ikony==1) echo "<img src='img/database_monitor.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Modeli monitorów</a></li>
			<li><a class="menu_font" href="z_drukarka.php"><?php if ($pokaz_ikony==1) echo "<img src='img/database_print.png' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Modeli drukarek</a></li>
			<li><a class="menu_font" href="z_czytnik.php"><?php if ($pokaz_ikony==1) echo "<img src='img/barcode.gif' border=0 align=absmiddle width=16 height=16>";?>&nbsp;Modeli czytników</a></li>
		</ul>
	</li>	
	
	<li><a class="menu_font" title=" Wróć do poprzedniej strony " href="javascript:history.go(-1);"><?php if ($pokaz_ikony==1) { echo "<img src=img/back.gif border=0 width=16 height=16>"; } else { echo "<"; }?></a></li> 
	<li><a class="menu_font" title=" Odśwież zawartość strony " href="javascript:window.location.reload( true );"><?php if ($pokaz_ikony==1) { echo "<img src=img//refresh.gif border=0 width=16 height=16>"; } else { echo "*"; }?></a></li>  
	<li><a class="menu_font" title=" Przejdź do następnej strony " href="javascript:history.go(1);"><?php if ($pokaz_ikony==1) { echo "<img src=img/forward.gif border=0 width=16 height=16>"; } else { echo ">"; }?></a></li>
	<li><a class="menu_font" title=" Szukaj " href="main.php?action=szukaj&typs=all"><?php if ($pokaz_ikony==1) { echo "<img src=img/search.gif border=0 width=16 height=16>"; } else { echo "S"; }?></a></li>
<?php } ?>
	<li><a class="menu_font" href="#" title=" Wyloguj się " onClick="javascript:if(confirm('Czy napewno chcesz się wylogować ?')) document.location.href='logout.php'"><?php if ($pokaz_ikony==1) { echo "<img src=img/logout1.gif border=0 width=16 height=16>"; } else { echo "&nbsp;W&nbsp;"; }?></a></li>
	<li><a class="menu_font" href="#" title=" Zakończ pracę z bazą eSerwis " onClick="javascript:if(confirm('Czy napewno chcesz zakończyć pracę z bazą eSerwis ?')) document.location.href='exit.php'"><?php if ($pokaz_ikony==1) { echo "<img src=img/logout.gif border=0 width=16 height=16>"; } else { echo "&nbsp;Koniec&nbsp;"; }?></a></li>
</ul>
</div>
</td>
</tr>
</table>
