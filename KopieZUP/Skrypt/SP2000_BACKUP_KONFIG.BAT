REM USTAWIENIA OGOLNE
SET ILOSC_DNI_DO_PRZECHOWYWANIA_KOPII=1
SET ILOSC_DNI_DO_PRZECHOWYWANIA_KOPII_STACJA_ROBOCZA=1
SET ZMIENNA1=
SET ZMIENNA2=
SET ZMIENNA3=
SET ZMIENNA4=
SET ZMIENNA5=
SET ZMIENNA6=
SET ZMIENNA7=
SET ZMIENNA8=
SET ZMIENNA9=
SET ZMIENNA10=
SET ZMIENNA11=
SET ZMIENNA12=
SET ZMIENNA13=
SET ZMIENNA14=
SET ZMIENNA15=

REM do PREFIX_NAZWY_PLIKU_Z_KOPIA będzie dodawany ciag znakow okreslajacy date i godzine wykonania kopii np. KOPIA_FUP_ZDWOLA2_2014_05_14__09_31.BAK. Nazwa musi byc zgodna ze zdefiniowanym w bazie komorek (eSerwis) polem: "Nazwa pliku z backup'em"
SET PREFIX_NAZWY_PLIKU_Z_KOPIA=Kopia_UP_Biala

REM USTAWIENIA ODNOSNIE KOMPUTERA NA KTORYM JEST URUCHAMIANY SKRYPT
SET NAZWA_KOMPUTERA=P216047VCP050
SET KATALOG_Z_KOPIAMI=E:\KOPIA\SP2000
SET NAZWA_BAZY_DANYCH_DO_BACKUPU=SP2000

REM USTAWIENIA ODNOSNIE KOMPUTERA NA KTORYM BEDZIE PRZECHOWYWANA KOPIA
SET NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII=P216047rcp100
SET NAZWA_UDZIALU_NA_KOMPUTERZE_DOCELOWYM=KOPIA
SET MAPUJ_UDZIAL_NA_LITERZE=Z
REM WYLACZ_KOMPUTER_PO_WYKONANIU_KOPII, dostepne opcje TAK, NIE
SET WYLACZ_KOMPUTER_PO_WYKONANIU_KOPII=NIE

REM USTAWIENIA ODNOSNIE FUNKCJI WakeOnLan DLA KOMPUTERA NA KTORYM BEDA PRZECHOWYWANE KOPIE
REM UZYJ_WON:  czy ma uzywac funkcji WakeOnLan do wzbudzania komputera (przy jego niedostepnosci)
SET UZYJ_WON=NIE
REM MAC ADDRESS: zapis w formacie XX:XX:XX:XX:XX:XX
SET MAC_ADDRESS=00:0C:6E:14:6E:C8
REM ILOSC_SEKUND_POTRZEBNYCH_NA_URUCHOMIENIE_KOMPUTERA- parametr potrzeby przy wywolaniu funkcji WakeOnLan
SET ILOSC_SEKUND_POTRZEBNYCH_NA_URUCHOMIENIE_KOMPUTERA=120

REM USTAWIENIA DOTYCZACE WYSYLKI LOGOW NA FTP
REM WYSYLAJ_LOGI_NA_FTP: dopuszczalne opcje TAK, NIE
SET WYSYLAJ_LOGI_NA_FTP=TAK
REM FTP_FILIA: dopuszczalne opcje SI, LD, SK, PT
SET FTP_FILIA=test
SET FTP_IP=10.214.78.14
SET FTP_USER=backupuser
SET FTP_PASS=backupuser

REM Przydatne linki na temat WakeOnLine
REM http://spece.it/windows-serwer-artykuly/wake-on-lan-czyli-komputerowa-pobudka-przygotowanie-urzadzen
REM LOAD_OK - znacznik dla skryptu glownego informujacy o poprawnym zaladowaniu pliku konfiguracyjnego
SET LOAD_OK=TAK