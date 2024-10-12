@echo off
setlocal
set ROOT=%~dp0
set BIN=%ROOT%bin\
set PHP=%BIN%php\php.exe

%PHP% %BIN%composer\composer.phar %*

endlocal
