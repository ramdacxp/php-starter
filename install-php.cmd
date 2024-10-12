@echo off
setlocal
set ROOT=%~dp0
set BIN=%ROOT%bin\
set PHPVERSION=8.3.11
set PHPINI=%BIN%\php\php.ini
set URL=https://windows.php.net/downloads/releases/archives/php-%PHPVERSION%-Win32-vs16-x64.zip
set ZIP=%BIN%\php.zip

echo Root: %ROOT%
IF NOT EXIST %BIN% mkdir %BIN%

if "%1" == "fast" goto :fast

REM Download ===================================================================
echo ### DOWNLOAD PHP v%PHPVERSION%
del /f /q %ZIP% 2>NUL
curl -o %ZIP% %URL%

:fast

REM Cleanup php folder =========================================================
echo ### CLEANUP PHP BIN
rmdir /s /q %BIN%\php 2>NUL

REM Download & unzip ===========================================================
echo ### UNZIP
call npx qzip unzip %ZIP% %BIN%\php

REM Set PHP.INI ================================================================
echo ### SET PHP.INI
type %BIN%php\php.ini-development > %PHPINI%
echo. >> %PHPINI%
echo ; =============== >> %PHPINI%
echo ; Custom Settings >> %PHPINI%
echo ; =============== >> %PHPINI%
echo extension_dir=%BIN%php\ext >> %PHPINI%
echo extension=pdo_mysql >> %PHPINI%
echo extension=pdo_sqlite >> %PHPINI%

rem required by composer
echo extension=curl >> %PHPINI%
echo extension=openssl >> %PHPINI%
echo extension=zip >> %PHPINI%

endlocal
