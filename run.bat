@echo off

if [%1]==[] goto usage

php %~dp0\%1.php
echo.
pause
exit

:usage
@echo Usage: %0 ^<filename^>