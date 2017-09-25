@ECHO OFF

SET LOAD_OK=NIE
SET SCRIPT_NAME=SP2000_BACKUP.BAT

REM wczytanie konfiguracji
CALL SP2000_BACKUP_KONFIG.BAT
IF "%LOAD_OK%"=="TAK" GOTO CONF_LOADED

CLS
ECHO Nie odnaleziono pliku konfiguracyjnego SP2000_BACKUP_KONFIG.BAT. Wykonywanie skryptu zostaje przerwane.
ECHO Brak pliku konifguracyjnego (SP2000_BACKUP_KONFIG.BAT) dla skryptu SP2000_BACKUP.BAT. Wykonywanie aktualizacji jest niemozliwe.
GOTO END

:CONF_LOADED
SET LOAD_OK=TAK

ECHO open %FTP_IP% >update.cmd
ECHO user %FTP_USER% %FTP_PASS% >>update.cmd
ECHO binary >>update.cmd
ECHO cd / >>update.cmd
ECHO cd Skrypt >>update.cmd
ECHO prompt 0 >>update.cmd
ECHO get %SCRIPT_NAME% __%SCRIPT_NAME% >>update.cmd
ECHO bye >>update.cmd

FTP -n -s:update.cmd >nul
DEL update.cmd /Q >nul

FC /b %SCRIPT_NAME% __%SCRIPT_NAME% > nul
IF %ERRORLEVEL% EQU 0 GOTO KONIEC

:AKTUALIZUJ
CLS
ECHO ************************************************************************
ECHO * Znaleziono zmodyfikowana wersje skryptu. Rozpoczynam aktualizacje... *
ECHO ************************************************************************

COPY __%SCRIPT_NAME% %SCRIPT_NAME% /Y >nul
IF %ERRORLEVEL% EQU 0 GOTO OK
GOTO NOT_OK

:OK
ECHO.
ECHO.
DEL __%SCRIPT_NAME% /Q >nul
ECHO Plik %SCRIPT_NAME% zostal zaktualizowany
GOTO END

:NOT_OK
ECHO.
ECHO.
DEL __%SCRIPT_NAME% /Q >nul
ECHO Wystapil blad podczas aktualizacji skryptu (sprawdz czy plik %SCRIPT_NAME% nie jest uzywany przez inny proces

:KONIEC
CLS
DEL __%SCRIPT_NAME% /Q >nul
ECHO Wersja skryptu %SCRIPT_NAME% jest aktualna

:END
ECHO.
ECHO.
