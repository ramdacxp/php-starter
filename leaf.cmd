@ECHO OFF
SETLOCAL
SET PATH=%PATH%;%~dp0bin\php
CALL %~dp0vendor\bin\leaf.bat %*
ENDLOCAL
