<?php
	echo "Rodzaj prac do wpisania:&nbsp;";

	if ($_REQUEST[k]=='1') echo "<b>konsultacje telefoniczne (max. 15 minut)</b>"; 
	if ($_REQUEST[k]=='7') echo "<b>okresowa konserwacja sprzętu</b>"; 
	if (($_REQUEST[k]=='2') || ($_REQUEST[k]=='3') || ($_REQUEST[k]=='6')) {
		switch ($_REQUEST[pk]) {
			case "2" : echo "<b>zgłaszane problemy eksploatacyjne w SP2000, MRU, ŁP14, Listy, Przekazy, Depesza + Outlook Express, SEDI, SNOSP</b>"; break;
			case "3" : echo "<b>awarie sprzętowe, awarie systemowe, reinstalacja systemu operacyjnego + instalacja niezbędnego oprogramowania pocztowego, wymiana klawiatury, myszki</b>"; break;
			case "4" : echo "<b>dotyczy serwera lub komputera pełniącego rolę serwera. Awarie sprzętowe, awarie systemowe, reinstalacja systemu operacyjnego + MS SQL _ instalacja niezbędnego oprogramowania pocztowego, wymiana klawiatury,myszki</b>"; break;
			case "5" : echo "<b>wszystkie prace przy oprogramowaniu Lotus Notes, MS Office i Acrobat Reader</b>"; break;
			case "6" : echo "<b>aktualizacje oprogramowania, wgrywanie poprawek, uaktualnień, fix'ów itd.</b>"; break;
			case "7" : echo "<b>reinstalacja i eksploatacja OCSI, Symantec, poprawki windows, Nessus, zgłoszenia związane z AD, kopie zapasowe, archiwizery (7-Zip, ZIP-Genius, RAR)</b>"; break;
			case "9" : echo "<b>awarie oraz instalacje drukarek, skanerów, monitorów, UPS'ów, czytników kart chipowych, sterowników (do tych urządzeń)</b>"; break;
			case "0" : echo "<b>awarie WAN, switch'y, okablowanie, konfiguracja routera</b>"; break;
			case "A" : echo "<b>otwarcie nowej agencji pocztowej, filii, urzędu pocztowego</b>"; break;
			case "B" : echo "<b>przeniesienie filii, urzędu pocztowego</b>"; break;
			case "C" : echo "<b>zamknięcie agencji pocztowej, filii, urzędu pocztowego</b>"; break;
			case "F" : echo "<b>uzgodnienia z koordynatorem CIT, raportowanie prac, ekspertyzy</b>"; break;
			case "D" : echo "<b>inne prace np. przenoszenie sprzętu</b>"; break;
			case "E" : echo "<b>prace związane z obsługą instalacji alarmowych</b>"; break;
			case "8" : echo "<b>prace przy konserwacji sprzętu</b>"; break;
			case "H" : echo "<b>prace administracyjne związane z ustawianień oraz weryfikowaniem kopii bezpieczeństwa</b>"; break;
			case "I" : echo "<b>prace związane z tematyką domeny net.pp (np. resetowanie haseł, zakładanie nowych użytkowniów itp.)</b>"; break;
			case "G" : echo "<b>prace przy projektach</b>"; break;
			default  : echo "<b>brak opisu</b>"; break;		
		}
	}
	
	if ($_REQUEST[k]=='4') {
		switch ($_REQUEST[pk]) {
			case "A" : echo "<b>otwarcie nowej agencji pocztowej, filii, urzędu pocztowego</b>"; break;
			case "D" : echo "<b>inne prace</b>"; break;
			case "2" : echo "<b>inne prace</b>"; break;
			default  : echo "-"; break;
		}
	}
	
	if ($_REQUEST[k]=='5') {
		switch ($_REQUEST[pk]) {
			case "1" : echo "<b>prace dla potrzeb Postdaty (niewidoczne dla Poczty)</b>"; break;
			default  : echo "-"; break;
		}
	}	
	echo "<br />";
	
?>