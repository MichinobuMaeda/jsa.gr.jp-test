@ECHO OFF
CD %~dp0
CALL env.bat
php -r "phpinfo();"
php -v
PAUSE
