@ECHO OFF

REM | ----------------------------------------------------------------
REM | SP2000 Backup Script
REM | Autor: Maciej Adrjanowicz
REM | 
REM | skrypt potrzebuje do prawidłowej pracy jednego parametru:
REM |  - rodzaj kopii: FULL lub DIFF
REM | ----------------------------------------------------------------

SET LOAD_OK=NIE
SET DRIVE_MAPPED=NIE
SET DESYNCHRONIZED=NIE
SET OBUDZONY=NIE

SET WERSJA_SKRYPTU=1.9.1
SET DATA_SKRYPTU=05.12.2014

:SCRIPT_START
REM wczytanie konfiguracji
CALL SP2000_BACKUP_KONFIG.BAT
IF "%LOAD_OK%"=="TAK" GOTO CONF_LOADED
GOTO GENERAL_ERROR

:CONF_LOADED
REM pobranie nowego pliku do automatycznej zmiany pliku konfiguracyjnego
ECHO open %FTP_IP% >update_cfg.cmd
ECHO user %FTP_USER% %FTP_PASS% >>update_cfg.cmd
ECHO binary >>update_cfg.cmd
ECHO cd / >>update_cfg.cmd
ECHO cd Skrypt >>update_cfg.cmd
ECHO prompt 0 >>update_cfg.cmd
ECHO get SP2000_BACKUP_KONFIG_AKTUALIZACJA.BAT >>update_cfg.cmd
ECHO get SP2000_AKTUALIZACJA.BAT >>update_cfg.cmd
ECHO bye >>update_cfg.cmd
FTP -n -s:update_cfg.cmd >nul
DEL update_cfg.cmd /Q >nul

REM sprawdzenie ilosci parametrow
SET /A ARGS_COUNT=0    
FOR %%A in (%*) DO SET /A ARGS_COUNT+=1    
IF %ARGS_COUNT%==0 GOTO NO_PARAMETERS_0

IF "%1"=="FULL" GOTO DALEJ
IF "%1"=="full" GOTO DALEJ
IF "%1"=="DIFF" GOTO DALEJ
IF "%1"=="diff" GOTO DALEJ
ECHO Pierwszy parametr blednie ustawiony. Dozwolone wartosci: FULL lub DIFF
GOTO KONIEC_NO_BACKUP

:DALEJ
SET BACKUP_TYPE=%1

REM UTWORZENIE CIAGU DOLACZANEGO DO PREFIXU, ODPOWIADAJACEGO DACIE. WYNIKOWO POWSTANIE CIAG np. 2014_05_14__09_49
SET DD=%DATE:~-2,2%
SET MM=%DATE:~-5,2%
SET RR=%DATE:~-10,4%
SET HOUR=%TIME:~0,2%
IF "%HOUR:~0,1%" == " " SET HOUR=0%HOUR:~1,1%
SET MIN=%TIME:~3,2%

SET ZNACZNIK_CZASU=%RR%_%MM%_%DD%__%HOUR%_%MIN%
SET ZNACZNIK_CZASU2=%RR%-%MM%-%DD% %HOUR%:%MIN%

GOTO START

:NO_PARAMETERS_0
CLS
ECHO Blad podczas wykonywania skryptu. Nie podano parametrow.
ECHO Blad podczas wykonywania skryptu. Nie podano parametrow. Uruchomione przez: %username%, %DATE% %TIME:~0,-3% >>ERROR.LOG
ECHO.
ECHO.
ECHO.
ECHO SP2000 Backup Script wersja 1.0 (Maciej Adrjanowicz)
ECHO.
ECHO Skrypt do wykonywania kopii pelnej lub przyrostowej bazy MS SQL.
ECHO.
ECHO SP2000_BACKUP.BAT [rodzaj kopii]
ECHO.
ECHO     rodzaj kopii - dopuszczalne sa 2 parametry: 
ECHO         FULL (dla kopii pełnej) 
ECHO         DIFF (dla kopii przyrostowej)
ECHO.
ECHO.
GOTO :POMIN_WYSYLKE_LOGOW_NA_FTP_END

:NO_PARAMETERS_1
ECHO Za malo parametrow niezbednych do prawidlowego dzialania skryptu.
ECHO Za malo parametrow niezbednych do prawidlowego dzialania skryptu. Uruchomione przez: %username%, %DATE% %TIME:~0,-3% >>ERROR.LOG
ECHO.
ECHO.
ECHO.
ECHO SP2000 Backup Script wersja 1.0 (Maciej Adrjanowicz)
ECHO.
ECHO Skrypt do wykonywania kopii pelnej lub przyrostowej bazy MS SQL.
ECHO.
ECHO SP2000_BACKUP.BAT [rodzaj kopii]
ECHO.
ECHO     rodzaj kopii - dopuszczalne sa 2 parametry: 
ECHO         FULL (dla kopii pełnej) 
ECHO         DIFF (dla kopii przyrostowej)
ECHO.
ECHO.
GOTO :POMIN_WYSYLKE_LOGOW_NA_FTP_END

:START

rem na komputerze o tym adresie musi byc udzial KOPIA a w nim podkatalogi: PN WT SR CZ PT SO ND

SET ARCHNAME=%PREFIX_NAZWY_PLIKU_Z_KOPIA%_%NAZWA_BAZY_DANYCH_DO_BACKUPU%_%BACKUP_TYPE%_%ZNACZNIK_CZASU%
SET LOGNAME_FTP_DELETE_FULL=%PREFIX_NAZWY_PLIKU_Z_KOPIA%_%NAZWA_BAZY_DANYCH_DO_BACKUPU%_FULL*.LOG
SET LOGNAME_FTP_DELETE_DIFF=%PREFIX_NAZWY_PLIKU_Z_KOPIA%_%NAZWA_BAZY_DANYCH_DO_BACKUPU%_DIFF*.LOG
IF NOT EXIST %KATALOG_Z_KOPIAMI%\LOGS (MKDIR %KATALOG_Z_KOPIAMI%\LOGS >nul)

SET LOGNAME=%KATALOG_Z_KOPIAMI%\LOGS\%ARCHNAME%.LOG
SET LOGNAME_MASK=LOGS\*.LOG

IF "%ILOSC_DNI_DO_PRZECHOWYWANIA_KOPII_STACJA_ROBOCZA%"=="" SET ILOSC_DNI_DO_PRZECHOWYWANIA_KOPII_STACJA_ROBOCZA=%ILOSC_DNI_DO_PRZECHOWYWANIA_KOPII%

ECHO #STEP_0#Konfiguracja skryptu#%DATE% %TIME:~0,-3%#>%LOGNAME%
ECHO #STEP_0#INFO_WERSJA_SKR#%WERSJA_SKRYPTU%#>>%LOGNAME%
ECHO #STEP_0#INFO_DATA_SKR#%DATA_SKRYPTU%#>>%LOGNAME%
ECHO #STEP_0#ILOSCKOPII#%ILOSC_DNI_DO_PRZECHOWYWANIA_KOPII%#>>%LOGNAME%
ECHO #STEP_0a#ILOSCKOPII_NA_STACJI_ROBOCZEJ#%ILOSC_DNI_DO_PRZECHOWYWANIA_KOPII_STACJA_ROBOCZA%#>>%LOGNAME%
ECHO #STEP_0#PREFIX_NAZWY_PLIKU_Z_KOPIA#%PREFIX_NAZWY_PLIKU_Z_KOPIA%#>>%LOGNAME%
ECHO #STEP_0#NAZWA_KOMPUTERA_LOKALNA#%NAZWA_KOMPUTERA%#>>%LOGNAME%
ECHO #STEP_0#KATALOG_Z_KOPIAMI#%KATALOG_Z_KOPIAMI%#>>%LOGNAME%
ECHO #STEP_0#NAZWA_BAZY_DANYCH_DO_BACKUPU#%NAZWA_BAZY_DANYCH_DO_BACKUPU%#>>%LOGNAME%
ECHO #STEP_0#NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII#%NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII%#>>%LOGNAME%
ECHO #STEP_0#NAZWA_UDZIALU_NA_KOMPUTERZE_DOCELOWYM#%NAZWA_UDZIALU_NA_KOMPUTERZE_DOCELOWYM%#>>%LOGNAME%
ECHO #STEP_0#MAPUJ_UDZIAL_NA_LITERZE#%MAPUJ_UDZIAL_NA_LITERZE%#>>%LOGNAME%
ECHO #STEP_0#WYLACZ_KOMPUTER_PO_WYKONANIU_KOPII#%WYLACZ_KOMPUTER_PO_WYKONANIU_KOPII%#>>%LOGNAME%
ECHO #STEP_0#UZYJ_WON#%UZYJ_WON%#>>%LOGNAME%
ECHO #STEP_0#MAC_ADDRESS#%MAC_ADDRESS%#>>%LOGNAME%
ECHO #STEP_0#ILOSC_SEKUND_POTRZEBNYCH_NA_URUCHOMIENIE_KOMPUTERA#%ILOSC_SEKUND_POTRZEBNYCH_NA_URUCHOMIENIE_KOMPUTERA%#>>%LOGNAME%
ECHO #STEP_0#WYSYLAJ_LOGI_NA_FTP#%WYSYLAJ_LOGI_NA_FTP%#>>%LOGNAME%
ECHO #STEP_0#FTP_FILIA#%FTP_FILIA%#>>%LOGNAME%
ECHO #STEP_0#FTP_IP#%FTP_IP%#>>%LOGNAME%
ECHO.>>%LOGNAME%
ECHO #STEP_0#INFO#SPRAWDZENIE CZY KOMPUTER DOCELOWY %NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII% JEST DOSTEPNY W SIECI#%DATE% %TIME:~0,-3%#>>%LOGNAME%
PING -n 1 %NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII% >nul
IF %errorlevel%==0 GOTO START_BACKUP 
REM JEZELI KOMPUTER JEST NIEDOSTEPNY, PODEJMOWANA JEST PROBA JEGO WZBUDZENIA PRZEZ FUNKCJE WakeOnLan
ECHO #STEP_0#ERROR#Komputer %NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII% nie jest dostepny w sieci #%DATE% %TIME:~0,-3%#>>%LOGNAME%
ECHO #STEP_0#INFO#Podjecie proby wzbudzenia komputera %NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII%#%DATE% %TIME:~0,-3%#>>%LOGNAME%

IF "%UZYJ_WON%" == "NIE" GOTO START_BACKUP_1
mc-wol %MAC_ADDRESS% >>%LOGNAME%
SET OBUDZONY=TAK
GOTO START_BACKUP_1

:START_BACKUP
ECHO #STEP_0#INFO#KOMPUTER DOCELOWY %NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII% JEST DOSTEPNY W SIECI#%DATE% %TIME:~0,-3%#>>%LOGNAME%
:START_BACKUP_1

ECHO #STEP_0#CHECKSQL#Sprawdzenie czy uruchomiona jest usluga MSSQLSERVER#%DATE% %TIME:~0,-3%# >>%LOGNAME%
net start |find "MSSQLSERVER" >nul
IF %errorlevel%==0 GOTO SQL_RUNNING
ECHO #STEP_0#ERROR#Usluga MSSQLSERVER jest zatrzymana. Podejmuje probe jej uruchomienia#%DATE% %TIME:~0,-3%# >>%LOGNAME%
net start MSSQLSERVER >nul
IF %errorlevel%==0 GOTO SQL_RUNNING

ECHO #STEP_0#ERROR#Usluga MSSQL jest zatrzymana i nie mozna jej uruchomic. Proces wykonywania kopii zostaje przerwany.#%DATE% %TIME:~0,-3%# >>%LOGNAME%
GOTO FINISH

:SQL_RUNNING
ECHO #STEP_0#INFO#Usluga MSSQLSERVER jest uruchomiona#%DATE% %TIME:~0,-3%# >>%LOGNAME%

IF "%1"=="FULL" GOTO MAKE_FULL_BACKUP
IF "%1"=="full" GOTO MAKE_FULL_BACKUP

ECHO.>>%LOGNAME%
ECHO #BACKUPTYPE#DIFF#ROZPOCZECIE PROCESU WYKONYWANIA KOPII PRZYROSTOWEJ >>%LOGNAME%
ECHO.>>%LOGNAME%

ECHO #STEP_1#INFO#Sprawdzenie czy jest mozliwosc wlaczenia opcji kompresji dla backup'u#%DATE% %TIME:~0,-3%# >>%LOGNAME%
OSQL -H %NAZWA_KOMPUTERA% -S -E -Q "SELECT @@VERSION" |FIND "Express Edition" >nul
IF %errorlevel%==0 GOTO EXPRESS_EDITION_DIFF
GOTO OTHER_EDITION1

:EXPRESS_EDITION_DIFF
ECHO #STEP_1#SQLEDITION#Express Edition#%DATE% %TIME:~0,-3%# >>%LOGNAME%
OSQL -H %NAZWA_KOMPUTERA% -S -E -Q "SELECT @@VERSION" |FIND "Edition" >>%LOGNAME%
ECHO #STEP_1#COMPRESSION#NIE#%DATE% %TIME:~0,-3%# >>%LOGNAME%
ECHO #STEP_1#START#Rozpoczecie wykonywania backupu#%DATE% %TIME:~0,-3%# >>%LOGNAME%
OSQL -H %NAZWA_KOMPUTERA% -S -E -Q "BACKUP DATABASE [%NAZWA_BAZY_DANYCH_DO_BACKUPU%] TO DISK=N'%KATALOG_Z_KOPIAMI%\%ARCHNAME%.BAK' WITH DIFFERENTIAL, FORMAT, INIT, NAME=N'%NAZWA_BAZY_DANYCH_DO_BACKUPU%-Differential Database Backup | Data uruchomienia: %DATE% %TIME:~0,-3%', SKIP, NOREWIND, NOUNLOAD, STATS=10" >>%LOGNAME%
GOTO SKIP_EDITION_CHECK1
:OTHER_EDITION1
ECHO #STEP_1#SQLEDITION#Standard Edition#%DATE% %TIME:~0,-3%# >>%LOGNAME%
OSQL -H %NAZWA_KOMPUTERA% -S -E -Q "SELECT @@VERSION" |FIND "Edition" >>%LOGNAME%
ECHO #STEP_1#COMPRESSION#TAK#%DATE% %TIME:~0,-3%# >>%LOGNAME%
ECHO #STEP_1#START#Rozpoczecie wykonywania backupu#%DATE% %TIME:~0,-3%# >>%LOGNAME%
OSQL -H %NAZWA_KOMPUTERA% -S -E -Q "BACKUP DATABASE [%NAZWA_BAZY_DANYCH_DO_BACKUPU%] TO DISK=N'%KATALOG_Z_KOPIAMI%\%ARCHNAME%.BAK' WITH DIFFERENTIAL, COMPRESSION, FORMAT, INIT, NAME=N'%NAZWA_BAZY_DANYCH_DO_BACKUPU%-Differential Database Backup | Data uruchomienia: %DATE% %TIME:~0,-3%', SKIP, NOREWIND, NOUNLOAD, STATS=10" >>%LOGNAME%
:SKIP_EDITION_CHECK1

IF %errorlevel%==0 GOTO END_STEP_1
ECHO #STEP_1#ERROR#Wystapi blad podczas wykonywania backupu. Wykonywanie skryptu zostaje przerwane.#%DATE% %TIME:~0,-3%# >>%LOGNAME%
GOTO FINISH

:MAKE_FULL_BACKUP
ECHO #BACKUPTYPE#FULL#ROZPOCZECIE PROCESU WYKONYWANIA PELNEJ KOPII >>%LOGNAME%
REM *******************************************************************************************************************************************************************************************************************************

ECHO #STEP_1#INFO#Sprawdzenie czy jest mozliwosc wlaczenia opcji kompresji dla backup'u#%DATE% %TIME:~0,-3%# >>%LOGNAME%
OSQL -H %NAZWA_KOMPUTERA% -S -E -Q "SELECT @@VERSION" |FIND "Express Edition" >nul
IF %errorlevel%==0 GOTO EXPRESS_EDITION_FULL
GOTO OTHER_EDITION_FULL

:EXPRESS_EDITION_FULL
ECHO #STEP_1#SQLEDITION#Express Edition#%DATE% %TIME:~0,-3%# >>%LOGNAME%
OSQL -H %NAZWA_KOMPUTERA% -S -E -Q "SELECT @@VERSION" |FIND "Edition" >>%LOGNAME%
ECHO #STEP_1#COMPRESSION#NIE#%DATE% %TIME:~0,-3%# >>%LOGNAME%
ECHO #STEP_1#START#Rozpoczecie wykonywania backupu#%DATE% %TIME:~0,-3%# >>%LOGNAME%
OSQL -H %NAZWA_KOMPUTERA% -S -E -Q "BACKUP DATABASE [%NAZWA_BAZY_DANYCH_DO_BACKUPU%] TO DISK=N'%KATALOG_Z_KOPIAMI%\%ARCHNAME%.BAK' WITH FORMAT, INIT, NAME=N'%NAZWA_BAZY_DANYCH_DO_BACKUPU%-Full Database Backup | Data uruchomienia: %DATE% %TIME:~0,-3%', SKIP, NOREWIND, NOUNLOAD, STATS=10" >>%LOGNAME%
GOTO SKIP_EDITION_CHECK2

:OTHER_EDITION_FULL
ECHO #STEP_1#SQLEDITION#Standard Edition#%DATE% %TIME:~0,-3%# >>%LOGNAME%
OSQL -H %NAZWA_KOMPUTERA% -S -E -Q "SELECT @@VERSION" |FIND "Edition" >>%LOGNAME%
ECHO #STEP_1#COMPRESSION#TAK#%DATE% %TIME:~0,-3%# >>%LOGNAME%
ECHO #STEP_1#START#Rozpoczecie wykonywania backupu#%DATE% %TIME:~0,-3%# >>%LOGNAME%
OSQL -H %NAZWA_KOMPUTERA% -S -E -Q "BACKUP DATABASE [%NAZWA_BAZY_DANYCH_DO_BACKUPU%] TO DISK=N'%KATALOG_Z_KOPIAMI%\%ARCHNAME%.BAK' WITH FORMAT, COMPRESSION, INIT, NAME=N'%NAZWA_BAZY_DANYCH_DO_BACKUPU%-Full Database Backup | Data uruchomienia: %DATE% %TIME:~0,-3%', SKIP, NOREWIND, NOUNLOAD, STATS=10" >>%LOGNAME%

:SKIP_EDITION_CHECK2
IF %errorlevel%==0 GOTO END_STEP_1
ECHO #STEP_1#ERROR#Wystapil blad podczas wykonywania backupu. Wykonywanie skryptu zostaje przerwane.#%DATE% %TIME:~0,-3%# >>%LOGNAME%
GOTO FINISH

:END_STEP_1

ECHO #STEP_1#STOP#Zakonczenie wykonywania backupu#%DATE% %TIME:~0,-3%# >>%LOGNAME%

REM pobranie rozmiaru utworzonego archiwum
FOR %%F IN (%KATALOG_Z_KOPIAMI%\%ARCHNAME%.BAK) DO ECHO #STEP_1#INFO#Rozmiar pliku#%%~zF >>%LOGNAME%

REM **********************************************************************************************************************************************************************************************************************************
ECHO.>>%LOGNAME%
ECHO #STEP_2#START#Rozpoczecie procesu weryfikacji backupu#%DATE% %TIME:~0,-3%# >>%LOGNAME%
OSQL -H %NAZWA_KOMPUTERA% -S -E -Q "RESTORE VERIFYONLY FROM DISK = N'%KATALOG_Z_KOPIAMI%\%ARCHNAME%.BAK' WITH NOUNLOAD, NOREWIND" >>%LOGNAME%

IF %errorlevel%==0 GOTO END_STEP_2
ECHO #STEP_2#ERROR#Wystapi blad podczas weryfikacji backupu.#%DATE% %TIME:~0,-3%# >>%LOGNAME%

:END_STEP_2

ECHO #STEP_2#STOP#Zakonczenie procesu weryfikacji backupu#%DATE% %TIME:~0,-3%# >>%LOGNAME%
REM ***********************************************************************************************************************************************************************
ECHO.>>%LOGNAME%
ECHO #STEP_3#START#Rozpoczecie generowania sumy kontrolnej backupu#%DATE% %TIME:~0,-3%# >>%LOGNAME%
DEL %KATALOG_Z_KOPIAMI%\LOGS\%ARCHNAME%.XML >NUL
FCIV %KATALOG_Z_KOPIAMI%\%ARCHNAME%.BAK -xml %KATALOG_Z_KOPIAMI%\LOGS\%ARCHNAME%.XML >>%LOGNAME%
IF %errorlevel%==0 GOTO STEP3_INFO_OK
ECHO #STEP_3#INFO_GEN_SUMY_KONTR#NIE >>%LOGNAME%
GOTO STEP3_INFO_NOT_OK

:STEP3_INFO_OK
ECHO #STEP_3#INFO_GEN_SUMY_KONTR#TAK >>%LOGNAME%
:STEP3_INFO_NOT_OK

ECHO #STEP_3#STOP#Zakonczenie procesu generowania sumy kontrolnej#%DATE% %TIME:~0,-3%# >>%LOGNAME%
REM ***********************************************************************************************************************************************************************

IF "%UZYJ_WON%" == "NIE" GOTO ROZPOCZNIJ_KOPIOWANIE

REM SPRAWDZENIE CZY KOMPUTER DOCELOWY JEST DOSTEPNY
SET /A w=0
SET WOL=0
ECHO.>>%LOGNAME%
ECHO #STEP_4#START#Rozpoczecie sprawdzania czy komputer docelowy jest dostepny w sieci#%DATE% %TIME:~0,-3%# >>%LOGNAME%
:LOOP
PING -n 1 %NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII% >nul
IF %errorlevel%==0 GOTO KOMPUTER_DOSTEPNY 

REM JEZELI KOMPUTER JEST NIEDOSTEPNY, PODEJMOWANA JEST PROBA JEGO WZBUDZENIA PRZEZ FUNKCJE WakeOnLan
IF %WOL%==1 GOTO POMIN_WOL
ECHO #STEP_4#ERROR#Komputer %NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII% nie jest dostepny w sieci#%DATE% %TIME:~0,-3%#>>%LOGNAME%
ECHO #STEP_4#INFO#Podjecie proby wzbudzenia komputera %NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII%#%DATE% %TIME:~0,-3%#>>%LOGNAME%
mc-wol %MAC_ADDRESS% >>%LOGNAME%
SET WOL=1
PING -n %ILOSC_SEKUND_POTRZEBNYCH_NA_URUCHOMIENIE_KOMPUTERA% 127.0.0.1 > nul 

:POMIN_WOL
SET /A w=w+1
IF %w% LEQ %ILOSC_SEKUND_POTRZEBNYCH_NA_URUCHOMIENIE_KOMPUTERA% GOTO LOOP

PING -n 1 %NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII% >nul
IF %errorlevel%==0 GOTO KOMPUTER_DOSTEPNY 

ECHO #STEP_4#ERRORWOL#Komputer %NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII% nie reaguje na sygnal wzbudzenia. Proces kopii na komputer docelowy zostaje pominiety#%DATE% %TIME:~0,-3%#>>%LOGNAME%
SET DESYNCHRONIZED=TAK
GOTO WYSYLKA_LOGOW_NA_FTP

:ROZPOCZNIJ_KOPIOWANIE
PING -n 1 %NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII% >nul
IF %errorlevel%==0 GOTO KOMPUTER_DOSTEPNY 
ECHO #STEP_4#ERROR#Komputer %NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII% nie jest dostepny w sieci. Proces kopii na komputer docelowy zostaje pominiety (funkcja WakeOnLan jest wylaczona)#%DATE% %TIME:~0,-3%#>>%LOGNAME%
GOTO WYSYLKA_LOGOW_NA_FTP

:KOMPUTER_DOSTEPNY

REM Automatyczne pobranie adresu MAC komputera docelowego do zgrania kopii. Zmienna bedzie wykorzystywana do weryfikacji poprawnosci konfiguracji
PING -n 1 %NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII% >nul
IF NOT %errorlevel%==0 GOTO SKIP_AUTO_MAC
FOR /F "tokens=*" %%G IN ('mcgetmac %NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII%') DO SET MAC_AUTO=%%G
FOR /F "tokens=4 delims= " %%b IN ("%MAC_AUTO%") DO ECHO #STEP_0#AUTO_MAC_ADDRESS#%%b#%DATE% %TIME:~0,-3%#>>%LOGNAME%
GOTO SKIP_SKIP_AUTO_MAC
:SKIP_AUTO_MAC
ECHO ECHO #STEP_0#AUTO_MAC_ADDRESS##%DATE% %TIME:~0,-3%#>>%LOGNAME%
:SKIP_SKIP_AUTO_MAC

ECHO #STEP_4#INFO#Komputer %NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII% jest dostepny w sieci #%DATE% %TIME:~0,-3%#>>%LOGNAME%
ECHO #STEP_4#STOP#Zakonczenie sprawdzania czy komputer docelowy jest dostepny w sieci#%DATE% %TIME:~0,-3%# >>%LOGNAME%
REM ***********************************************************************************************************************************************************************
ECHO.>>%LOGNAME%
ECHO #STEP_5#START#Rozpoczecie kopiowania backupu na komputer %NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII%#%DATE% %TIME:~0,-3%# >>%LOGNAME%
REM ZAMAPOWANIE UDZIAŁU. Zalozenie jest takie, ze hasla lokalnych administratorow beda identyczne na komputerze z baza SQL i na komputerze gdzie beda kopie przechowywane

ECHO #STEP_5#INFO#Proba zamapowania udzialu z komputera %NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII% na literze %MAPUJ_UDZIAL_NA_LITERZE%#%DATE% %TIME:~0,-3%# >>%LOGNAME%
NET USE %MAPUJ_UDZIAL_NA_LITERZE%: /DELETE /YES >>%LOGNAME%
NET USE %MAPUJ_UDZIAL_NA_LITERZE%: \\%NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII%\%NAZWA_UDZIALU_NA_KOMPUTERZE_DOCELOWYM% /USER:%NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII%_S >>%LOGNAME%
IF %errorlevel%==0 GOTO STEP_5_CONTINUE
REM IF %errorlevel%==2 GOTO STEP_5_CONTINUE
SET DESYNCHRONIZED=TAK
ECHO Zawartosc katalogu z kopiami na komputerze %NAZWA_KOMPUTERA% (%KATALOG_Z_KOPIAMI%), jest niezsynchronizowana z zawartoscia udzialu \\%NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII%\%NAZWA_UDZIALU_NA_KOMPUTERZE_DOCELOWYM%    #%DATE% %TIME:~0,-3%# >%KATALOG_Z_KOPIAMI%\DESYNCHRONIZED

ECHO #STEP_5#ERROR#Nie udalo sie zamapowac udzialu z komputera %NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII% na literze %MAPUJ_UDZIAL_NA_LITERZE%#%DATE% %TIME:~0,-3%# >>%LOGNAME%
ECHO #STEP_5#ERROR#Kopia na komputerze %NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII% nie zostala wykonana. Konieczna interwencja informatyka. Prawdopodobnie problem z uprawnieniami#%DATE% %TIME:~0,-3%# >>%LOGNAME%
ECHO #STEP_5#INFO_O_WYK_KOPII#NIE >>%LOGNAME%
ECHO #STEP_6#ERROR#Weryfikacja kopii na komputerze %NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII% nie moze zostac wykonana#%DATE% %TIME:~0,-3%# >>%LOGNAME%
ECHO #STEP_6#INFO_WER_OK#NIE >>%LOGNAME%
GOTO STEP_5_SKIP_COPY_AND_VERYFICATION

:STEP_5_CONTINUE
SET DRIVE_MAPPED=TAK

IF NOT EXIST %KATALOG_Z_KOPIAMI%\DESYNCHRONIZED GOTO NORMAL_COPY

IF NOT EXIST %MAPUJ_UDZIAL_NA_LITERZE%:\LOGS (MKDIR %MAPUJ_UDZIAL_NA_LITERZE%:\LOGS >nul)
COPY %KATALOG_Z_KOPIAMI%\LOGS\*.XML %MAPUJ_UDZIAL_NA_LITERZE%:\LOGS /y >nul
COPY %KATALOG_Z_KOPIAMI%\LOGS\*.LOG %MAPUJ_UDZIAL_NA_LITERZE%:\LOGS /y >nul
COPY %KATALOG_Z_KOPIAMI%\*.BAK %MAPUJ_UDZIAL_NA_LITERZE%: /y >>%LOGNAME%
IF %errorlevel%==0 GOTO DEL_DESYNC_FILE
GOTO DONT_DELETE_DESYNC_FILE

:DEL_DESYNC_FILE
DEL %KATALOG_Z_KOPIAMI%\DESYNCHRONIZED /Q >NUL
ECHO #STEP_4#SYN_COPY#Synchronizacja katalogow z kopiami oraz logami zostala zakonczona#%DATE% %TIME:~0,-3%# >>%LOGNAME%
SET DESYNCHRONIZED=NIE
GOTO STEP_5_CONTINUE2

:DONT_DELETE_DESYNC_FILE
SET DESYNCHRONIZED=TAK
ECHO #STEP_4#ERROR_COPY#Nieudana synchronizacja katalogow z kopiami#%DATE% %TIME:~0,-3%# >>%LOGNAME%
ECHO Zawartosc katalogu z kopiami na komputerze %NAZWA_KOMPUTERA% (%KATALOG_Z_KOPIAMI%), jest niezsynchronizowana z zawartoscia udzialu \\%NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII%\%NAZWA_UDZIALU_NA_KOMPUTERZE_DOCELOWYM%    #%DATE% %TIME:~0,-3%# >%KATALOG_Z_KOPIAMI%\DESYNCHRONIZED
GOTO STEP_5_CONTINUE3

:NORMAL_COPY
COPY %KATALOG_Z_KOPIAMI%\%ARCHNAME%.BAK %MAPUJ_UDZIAL_NA_LITERZE%: /y >>%LOGNAME%
IF %errorlevel%==0 GOTO STEP_5_CONTINUE2
ECHO #STEP_5#ERROR#Kopia na komputerze %NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII% nie zostala wykonana. Konieczna interwencja informatyka. Prawdopodobnie problem z uprawnieniami#%DATE% %TIME:~0,-3%# >>%LOGNAME%
ECHO #STEP_6#ERROR#Weryfikacja kopii na komputerze %NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII% nie moze zostac wykonana#%DATE% %TIME:~0,-3%# >>%LOGNAME%
ECHO #STEP_5#INFO_O_WYK_KOPII#NIE >>%LOGNAME%
SET DESYNCHRONIZED=TAK
ECHO Zawartosc katalogu z kopiami na komputerze %NAZWA_KOMPUTERA% (%KATALOG_Z_KOPIAMI%), jest niezsynchronizowana z zawartoscia udzialu \\%NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII%\%NAZWA_UDZIALU_NA_KOMPUTERZE_DOCELOWYM%    #%DATE% %TIME:~0,-3%# >%KATALOG_Z_KOPIAMI%\DESYNCHRONIZED
GOTO STEP_5_SKIP_COPY_AND_VERYFICATION

:STEP_5_CONTINUE2
ECHO #STEP_5#INFO_O_WYK_KOPII#TAK >>%LOGNAME%
ECHO #STEP_5#STOP#Zakonczenie kopiowania backupu na komputer %NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII%#%DATE% %TIME:~0,-3%# >>%LOGNAME%
REM ***********************************************************************************************************************************************************************
ECHO.>>%LOGNAME%
:STEP_5_CONTINUE3
ECHO #STEP_6#START#Rozpoczecie weryfikacji sumy kontrolnej backupu#%DATE% %TIME:~0,-3%# >>%LOGNAME%
FCIV -v %MAPUJ_UDZIAL_NA_LITERZE%:\%ARCHNAME%.BAK -xml %KATALOG_Z_KOPIAMI%\LOGS\%ARCHNAME%.XML >>%LOGNAME%
IF %errorlevel%==0 GOTO STEP6_VERIFY_OK
ECHO #STEP_6#ERROR#Weryfikacja sumy kontrolnej backupu na komputerze %NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII% zakonczyla sie niepowodzeniem#%DATE% %TIME:~0,-3%# >>%LOGNAME%
ECHO #STEP_6#INFO_WER_OK#NIE >>%LOGNAME%
GOTO STEP6_VERIFY_ERROR
:STEP6_VERIFY_OK
ECHO #STEP_6#INFO_WER_OK#TAK >>%LOGNAME%
ECHO #STEP_6#INFO#Weryfikacja sumy kontrolnej backupu na komputerze %NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII% zakonczyla sie poprawnie.#%DATE% %TIME:~0,-3%# >>%LOGNAME%
:STEP6_VERIFY_ERROR
ECHO #STEP_6#STOP#Zakonczenie weryfikacji sumy kontrolnej backupu#%DATE% %TIME:~0,-3%# >>%LOGNAME%
REM ***********************************************************************************************************************************************************************
ECHO.>>%LOGNAME%
ECHO #STEP_7#START#Rozpoczecie kopiowania logow na %NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII%#%DATE% %TIME:~0,-3%# >>%LOGNAME%
IF NOT EXIST %MAPUJ_UDZIAL_NA_LITERZE%:\LOGS (MKDIR %MAPUJ_UDZIAL_NA_LITERZE%:\LOGS >nul)
COPY %KATALOG_Z_KOPIAMI%\LOGS\*.XML %MAPUJ_UDZIAL_NA_LITERZE%:\LOGS /y >nul
COPY %KATALOG_Z_KOPIAMI%\LOGS\*.LOG %MAPUJ_UDZIAL_NA_LITERZE%:\LOGS /y >nul
IF %errorlevel%==0 GOTO STEP_7_OK
ECHO #STEP_7#ERROR#Wystapil blad podczas kopiowania logow na %NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII%#%DATE% %TIME:~0,-3% >>%LOGNAME%
GOTO STEP_7_ERROR
:STEP_7_OK
ECHO #STEP_7#STOP#Zakonczenie kopiowania logow na %NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII%#%DATE% %TIME:~0,-3% >>%LOGNAME%
:STEP_7_ERROR

REM kasowanie plikow kopii i logow starszych niz n dni z komputera na ktorym sa wysylane kopie
:CZYSZCZENIE_STARYCH_BACKUPOW_REMOTE
REM CZYSZCZENIE PLIKOW BACKUPu, LOGow i XML'i starszych niz okreslona ilosc dni z katalogu gdzie wykonywane sa kopie oraz katalogu z logami
ECHO.>>%LOGNAME%
ECHO #STEP_8#START#Rozpoczecie procedury kasowania plikow kopii starszych niz %ILOSC_DNI_DO_PRZECHOWYWANIA_KOPII_STACJA_ROBOCZA% dni z komputera %NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII% (katalog: %MAPUJ_UDZIAL_NA_LITERZE%:) oraz logow z katalogu %MAPUJ_UDZIAL_NA_LITERZE%:\LOGS:#%DATE% %TIME:~0,-3%# >>%LOGNAME%
ECHO #STEP_8#INFO#Lista plikow kopii, starszych niz %ILOSC_DNI_DO_PRZECHOWYWANIA_KOPII_STACJA_ROBOCZA% dni z komputera %NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII% (katalog: %MAPUJ_UDZIAL_NA_LITERZE%:)#%DATE% %TIME:~0,-3%# >>%LOGNAME%
ff.exe -p%MAPUJ_UDZIAL_NA_LITERZE%: -d-%ILOSC_DNI_DO_PRZECHOWYWANIA_KOPII_STACJA_ROBOCZA% -m*.BAK -c"CMD /C ECHO @PATH@FILE" >>%LOGNAME%
ff.exe -p%MAPUJ_UDZIAL_NA_LITERZE%: -d-%ILOSC_DNI_DO_PRZECHOWYWANIA_KOPII_STACJA_ROBOCZA% -m*.BAK -c"CMD /C DEL @PATH@FILE /Q" >nul

IF %errorlevel%==0 GOTO STEP_11_DEL_LOGS
ECHO #STEP_8#ERROR#Wystapil blad podczas kasowania plikow kopii starszych niz %ILOSC_DNI_DO_PRZECHOWYWANIA_KOPII_STACJA_ROBOCZA% dni z komputera %NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII% (katalog: %MAPUJ_UDZIAL_NA_LITERZE%:)#%DATE% %TIME:~0,-3%# >>%LOGNAME%
GOTO STEP_8_DEL_BACKUPS_ERROR
:STEP_11_DEL_LOGS
ECHO #STEP_8#INFO#Kasowanie plikow kopii starszych niz %ILOSC_DNI_DO_PRZECHOWYWANIA_KOPII_STACJA_ROBOCZA% dni z komputera %NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII% (katalog: %MAPUJ_UDZIAL_NA_LITERZE%:) zostalo zakonczone pomyslnie#%DATE% %TIME:~0,-3%# >>%LOGNAME%

:STEP_8_DEL_BACKUPS_ERROR

ECHO #STEP_8#INFO#Lista plikow logow, starszych niz %ILOSC_DNI_DO_PRZECHOWYWANIA_KOPII_STACJA_ROBOCZA% dni z komputera %NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII% (katalog: %MAPUJ_UDZIAL_NA_LITERZE%:\LOGS)#%DATE% %TIME:~0,-3%# >>%LOGNAME%
ff.exe -p%MAPUJ_UDZIAL_NA_LITERZE%:\LOGS -d-%ILOSC_DNI_DO_PRZECHOWYWANIA_KOPII_STACJA_ROBOCZA% -m*.LOG -c"cmd /c ECHO @PATH\@FILE" >>%LOGNAME%
ff.exe -p%MAPUJ_UDZIAL_NA_LITERZE%:\LOGS -d-%ILOSC_DNI_DO_PRZECHOWYWANIA_KOPII_STACJA_ROBOCZA% -m*.* -c"cmd /c DEL @PATH\@FILE /Q" >nul

IF %errorlevel%==0 GOTO STEP_8_KONIEC
ECHO #STEP_8#ERROR#Wystapil blad podczas kasowania plikow logow starszych niz %ILOSC_DNI_DO_PRZECHOWYWANIA_KOPII_STACJA_ROBOCZA% dni z komputera %NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII% (katalog: %MAPUJ_UDZIAL_NA_LITERZE%:)#%DATE% %TIME:~0,-3%# >>%LOGNAME%
GOTO STEP_8_DEL_LOGS_ERROR
:STEP_8_KONIEC
ECHO #STEP_8#INFO#Kasowanie plikow logow starszych niz %ILOSC_DNI_DO_PRZECHOWYWANIA_KOPII_STACJA_ROBOCZA% dni z komputera %NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII% (katalog: %MAPUJ_UDZIAL_NA_LITERZE%:\LOGS) zostalo zakonczone pomyslnie#%DATE% %TIME:~0,-3%# >>%LOGNAME%

:STEP_8_DEL_LOGS_ERROR
ECHO #STEP_8#STOP#Zakonczenie procedury kasowania plikow kopii i logow starszych niz %ILOSC_DNI_DO_PRZECHOWYWANIA_KOPII_STACJA_ROBOCZA% dni z komputera %NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII%#%DATE% %TIME:~0,-3%# >>%LOGNAME%

ECHO.>>%LOGNAME%
REM Pobranie informacji o ilosci wolnego miejsca na dysku sieciowym, na ktorym wysylane sa kopie
ECHO #STEP_9#START#Rozpoczecie pobierania informacji o ilosci wolnego miejsca na komputerze %NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII% na dysku %MAPUJ_UDZIAL_NA_LITERZE%:#%DATE% %TIME:~0,-3%>>%LOGNAME%
rem wmic logicaldisk where "DeviceID='%MAPUJ_UDZIAL_NA_LITERZE%:'" get FreeSpace /format:value |find "FreeSpace" >>%LOGNAME%

FOR /F "tokens=*" %%G IN ('dir /W /-C ^"%MAPUJ_UDZIAL_NA_LITERZE%:^"') DO SET GFS=%%G
FOR /F "tokens=3 delims= " %%b IN ("%GFS%") DO ECHO FreeSpace=%%b>>%LOGNAME%

ECHO #STEP_9#STOP#Zakonczenie pobierania informacji o ilosci wolnego miejsca na komputerze %NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII%#%DATE% %TIME:~0,-3%>>%LOGNAME%

REM *********************************************************************************************************************************************************************

REM INFORMACJE DODATKOWE WYCIAGANE PODCZAS WYKONYWANIA KOPII

:STEP_5_SKIP_COPY_AND_VERYFICATION

:WYSYLKA_LOGOW_NA_FTP

REM POBRANIE INFORMACJI O WOLNEJ PRZESTRZENI Z DYSKU NA KTORYM WYKONYWANE SA KOPIE
SET DRIVE_FREE_SPACE=%KATALOG_Z_KOPIAMI:~0,2%
ECHO.>>%LOGNAME%
ECHO #STEP_10#START#Rozpoczecie pobierania informacji o ilosci wolnego miejsca na dysku %DRIVE_FREE_SPACE%, na ktorym wykonywane sa kopie>>%LOGNAME%
wmic logicaldisk where "DeviceID='%DRIVE_FREE_SPACE%'" get FreeSpace /format:value |find "FreeSpace" >nul

SETLOCAL ENABLEDELAYEDEXPANSION
SET "volume=%DRIVE_FREE_SPACE%"
FOR /f "tokens=1*delims=:" %%i IN ('fsutil volume diskfree %volume%') DO (
    SET "diskfree=!disktotal!"
    SET "disktotal=!diskavail!"
    SET "diskavail=%%j"
)
FOR /f "tokens=1,2" %%i IN ("%disktotal% %diskavail%") DO SET "disktotal=%%i"& SET "diskavail=%%j"
(ECHO(Information for volume %volume%
ECHO(total  %disktotal:~0,-9% GB
ECHO(avail. %diskavail:~0,-9% GB)>>%LOGNAME%
ECHO #STEP_10#STOP#Zakonczenie pobierania informacji o ilosci wolnego miejsca na dysku %DRIVE_FREE_SPACE%, na ktorym wykonywane sa kopie>>%LOGNAME%

REM ***************************************************************************************************************************************************

IF "%DESYNCHRONIZED%"=="NIE" GOTO SKIP_DESYNCH_CREATE

ECHO #STEP_4#ERROR_COPY#Nieudana synchronizacja katalogow z kopiami#%DATE% %TIME:~0,-3%# >>%LOGNAME%
ECHO Zawartosc katalogu z kopiami na komputerze %NAZWA_KOMPUTERA% (%KATALOG_Z_KOPIAMI%), jest niezsynchronizowana z zawartoscia udzialu \\%NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII%\%NAZWA_UDZIALU_NA_KOMPUTERZE_DOCELOWYM%    #%DATE% %TIME:~0,-3%# >%KATALOG_Z_KOPIAMI%\DESYNCHRONIZED
ECHO #STEP_5#INFO_O_WYK_KOPII#NIE >>%LOGNAME%
ECHO #STEP_6#INFO_WER_OK#NIE >>%LOGNAME%

:SKIP_DESYNCH_CREATE
REM PRZYGOTOWANIE PLIKU Z KOMENDAMI DO WYSYLKI NA FTPa
ECHO.>>%LOGNAME%
ECHO #STEP_11#START#Rozpoczecie wysylki plikow LOG na FTPa#%DATE% %TIME:~0,-3%# >>%LOGNAME%
ECHO open %FTP_IP% >%KATALOG_Z_KOPIAMI%\send_to_ftp
ECHO user %FTP_USER% %FTP_PASS% >>%KATALOG_Z_KOPIAMI%\send_to_ftp
ECHO binary >>%KATALOG_Z_KOPIAMI%\send_to_ftp
ECHO cd / >>%KATALOG_Z_KOPIAMI%\send_to_ftp
ECHO cd %FTP_FILIA% >>%KATALOG_Z_KOPIAMI%\send_to_ftp

REM czyszczenie wszystkich logow z danej placowki z FTP'a
ECHO prompt 0 >>%KATALOG_Z_KOPIAMI%\send_to_ftp
ECHO mdelete %LOGNAME_FTP_DELETE_FULL%>>%KATALOG_Z_KOPIAMI%\send_to_ftp
ECHO mdelete %LOGNAME_FTP_DELETE_DIFF%>>%KATALOG_Z_KOPIAMI%\send_to_ftp
REM wysylaj wszystkie logi na FTP'a
ECHO mput %KATALOG_Z_KOPIAMI%\LOGS\%LOGNAME_FTP_DELETE_FULL% >>%KATALOG_Z_KOPIAMI%\send_to_ftp
ECHO mput %KATALOG_Z_KOPIAMI%\LOGS\%LOGNAME_FTP_DELETE_DIFF% >>%KATALOG_Z_KOPIAMI%\send_to_ftp

REM ECHO prompt 1 >>%KATALOG_Z_KOPIAMI%\send_to_ftp
ECHO bye >>%KATALOG_Z_KOPIAMI%\send_to_ftp

IF "%WYSYLAJ_LOGI_NA_FTP%" == "NIE" GOTO POMIN_WYSYLKE_LOGOW_NA_FTP 
REM WYSYLKA NA FTPa
FTP -n -s:%KATALOG_Z_KOPIAMI%\send_to_ftp >nul

IF %errorlevel%==0 GOTO STEP_11_OK
ECHO #STEP_11#ERROR#Wystapil problem z wysylka logow na FTPa %FTP_IP%#%DATE% %TIME:~0,-3%# >>%LOGNAME%
GOTO STEP_11_STOP
:STEP_11_OK

:STEP_11_STOP
ECHO #STEP_11#STOP#ZAKONCZENIE wysylki plikow LOG na FTPa#%DATE% %TIME:~0,-3%# >>%LOGNAME%
GOTO CZYSZCZENIE_STARYCH_BACKUPOW_SOURCE

:POMIN_WYSYLKE_LOGOW_NA_FTP
ECHO #STEP_11#INFO#Wysylka LOGow na FTP jest wylaczona w konfiguracji kopii#%DATE% %TIME:~0,-3%# >>%LOGNAME%

:CZYSZCZENIE_STARYCH_BACKUPOW_SOURCE
REM CZYSZCZENIE PLIKOW BACKUPu, LOGow i XML'i starszych niz okreslona ilosc dni z katalogu gdzie wykonywane sa kopie oraz katalogu z logami
ECHO.>>%LOGNAME%
ECHO #STEP_12#START#Rozpoczecie procedury kasowania plikow kopii starszych niz %ILOSC_DNI_DO_PRZECHOWYWANIA_KOPII% dni z katalogu %KATALOG_Z_KOPIAMI% oraz logow z katalogu %KATALOG_Z_KOPIAMI%\LOGS#%DATE% %TIME:~0,-3%# >>%LOGNAME%
ECHO #STEP_12#INFO#Lista plikow kopii, starszych niz %ILOSC_DNI_DO_PRZECHOWYWANIA_KOPII% dni w katalogu %KATALOG_Z_KOPIAMI%#%DATE% %TIME:~0,-3%# >>%LOGNAME%
ff.exe -p%KATALOG_Z_KOPIAMI% -d-%ILOSC_DNI_DO_PRZECHOWYWANIA_KOPII% -m*.BAK -c"cmd /c ECHO @PATH\@FILE" >>%LOGNAME%
ff.exe -p%KATALOG_Z_KOPIAMI% -d-%ILOSC_DNI_DO_PRZECHOWYWANIA_KOPII% -m*.BAK -c"cmd /c DEL @PATH\@FILE /Q" >nul

IF %errorlevel%==0 GOTO STEP_12_DEL_BACKUPS
ECHO #STEP_12#ERROR#Wystapil blad podczas kasowania plikow kopii starszych niz %ILOSC_DNI_DO_PRZECHOWYWANIA_KOPII% dni z katalogu %KATALOG_Z_KOPIAMI%#%DATE% %TIME:~0,-3%# >>%LOGNAME%
GOTO STEP_12_DEL_BACKUPS_ERROR
:STEP_12_DEL_BACKUPS
ECHO #STEP_12#INFO#Kasowanie plikow kopii starszych niz %ILOSC_DNI_DO_PRZECHOWYWANIA_KOPII% dni z katalogu %KATALOG_Z_KOPIAMI% zostalo zakonczone pomyslnie#%DATE% %TIME:~0,-3%# >>%LOGNAME%
:STEP_12_DEL_BACKUPS_ERROR

ECHO #STEP_12#INFO#Lista plikow logow, starszych niz %ILOSC_DNI_DO_PRZECHOWYWANIA_KOPII% dni w katalogu %KATALOG_Z_KOPIAMI%\LOGS#%DATE% %TIME:~0,-3%# >>%LOGNAME%
ff.exe -p%KATALOG_Z_KOPIAMI%\LOGS -d-%ILOSC_DNI_DO_PRZECHOWYWANIA_KOPII% -m*.LOG -c"cmd /c ECHO @PATH\@FILE" >>%LOGNAME%
ff.exe -p%KATALOG_Z_KOPIAMI%\LOGS -d-%ILOSC_DNI_DO_PRZECHOWYWANIA_KOPII% -m*.* -c"cmd /c DEL @PATH\@FILE /Q" >nul

IF %errorlevel%==0 GOTO STEP_12_KONIEC
ECHO #STEP_12#ERROR#Wystapil blad podczas kasowania plikow logow starszych niz %ILOSC_DNI_DO_PRZECHOWYWANIA_KOPII% dni z katalogu %KATALOG_Z_KOPIAMI%\LOGS#%DATE% %TIME:~0,-3%# >>%LOGNAME%
GOTO STEP_12_DEL_LOGS_ERROR
:STEP_12_KONIEC
ECHO #STEP_12#INFO#Kasowanie plikow logow starszych niz %ILOSC_DNI_DO_PRZECHOWYWANIA_KOPII% dni z katalogu %KATALOG_Z_KOPIAMI%\LOGS zostalo zakonczone pomyslnie#%DATE% %TIME:~0,-3%# >>%LOGNAME%
:STEP_12_DEL_LOGS_ERROR
ECHO #STEP_12#STOP#Zakonczenie procedury kasowania plikow kopii i logow starszych niz %ILOSC_DNI_DO_PRZECHOWYWANIA_KOPII% dni#%DATE% %TIME:~0,-3%# >>%LOGNAME%

REM *********************************************************************************************************************************************************************

:KONIEC
IF "%1"=="FULL" GOTO INFO_FULL
IF "%1"=="full" GOTO INFO_FULL
ECHO ZAKONCZENIE PROCESU WYKONYWANIA KOPII PRZYROSTOWEJ#%DATE% %TIME:~0,-3% >>%LOGNAME%
GOTO KONIEC_NO_BACKUP

:INFO_FULL
ECHO ZAKONCZENIE PROCESU WYKONYWANIA PELNEJ KOPII#%DATE% %TIME:~0,-3% >>%LOGNAME%
:KONIEC_NO_BACKUP
IF "%OBUDZONY%"=="TAK" GOTO WYLACZ_GO
IF "%WYLACZ_KOMPUTER_PO_WYKONANIU_KOPII%" == "NIE" GOTO FINISH

:WYLACZ_GO
ECHO #STEP_13#START#Rozpoczecie procedury wylaczenia komputera %NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII%#%DATE% %TIME:~0,-3% >>%LOGNAME%
SHUTDOWN -m \\%NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII% -s -f
ECHO #STEP_13#STOP#Procedura wylaczenia komputera %NAZWA_KOMPUTERA_DO_PRZECHOWYWANIA_KOPII% zostala wykonana#%DATE% %TIME:~0,-3% >>%LOGNAME%
GOTO FINISH

:GENERAL_ERROR
CLS
ECHO Nie odnaleziono pliku konfiguracyjnego SP2000_BACKUP_KONFIG.BAT. Wykonywanie skryptu zostaje przerwane.
ECHO #STEP_0#ERROR#Brak pliku konifguracyjnego (SP2000_BACKUP_KONFIG.BAT) dla skryptu SP2000_BACKUP.BAT. Wykonywanie skryptu jest niemozliwe.#%DATE% %TIME:~0,-3% >%LOGNAME%
GOTO POMIN_WYSYLKE_LOGOW_NA_FTP_END

:FINISH
IF %DRIVE_MAPPED%==NIE GOTO WYSYLKA_LOGOW_NA_FTP_END
REM COPY %KATALOG_Z_KOPIAMI%\%LOGNAME% %MAPUJ_UDZIAL_NA_LITERZE%:\LOGS /y >nul

COPY %KATALOG_Z_KOPIAMI%\LOGS\*.XML %MAPUJ_UDZIAL_NA_LITERZE%:\LOGS /y >nul
COPY %KATALOG_Z_KOPIAMI%\LOGS\*.LOG %MAPUJ_UDZIAL_NA_LITERZE%:\LOGS /y >nul

NET USE %MAPUJ_UDZIAL_NA_LITERZE%: /DELETE /YES >nul

:WYSYLKA_LOGOW_NA_FTP_END
IF "%WYSYLAJ_LOGI_NA_FTP%" == "NIE" GOTO POMIN_WYSYLKE_LOGOW_NA_FTP_END 
FTP -n -s:%KATALOG_Z_KOPIAMI%\send_to_ftp >nul
DEL %KATALOG_Z_KOPIAMI%\send_to_ftp
:POMIN_WYSYLKE_LOGOW_NA_FTP_END