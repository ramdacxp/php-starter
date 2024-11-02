@echo off

rem ============================================================================
echo Setting up path environment ...

rem Add bin to path; if missing
echo %PATH% | findstr "%~dp0bin" >nul
if errorlevel 1 set Path=%Path%;%~dp0bin

rem Add verndor\bin to path; if missing
echo %PATH% | findstr "%~dp0vendor\bin" >nul
if errorlevel 1 set Path=%Path%;%~dp0vendor\bin

rem ============================================================================
echo Tools available in this shell:
call :LIST %~dp0bin
call :LIST %~dp0vendor\bin
goto :EOF

:LIST
rem ============================================================================
rem echo Available Tools in %1:
rem dir /b %1\*.cmd %1\*.bat
for /f "tokens=1,2 delims=." %%i in ('dir /b %1\*.cmd %1\*.bat') do echo - %%i
goto :EOF