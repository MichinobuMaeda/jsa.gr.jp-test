@ECHO OFF
CD %~dp0
CALL env.bat
php -S localhost:8000 -t www/zenkoku router.php
