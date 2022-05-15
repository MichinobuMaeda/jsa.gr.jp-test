Push-Location $PSScriptRoot
& .\env.ps1
php -r "phpinfo();"
php -v
"続行するには何かキーを押してください . . ."
Read-Host
