@echo off
setlocal
set ROOT=%~dp0
rmdir /s /q %ROOT%bin %ROOT%node_modules %ROOT%vendor
endlocal