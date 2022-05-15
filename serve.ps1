Push-Location $PSScriptRoot
& .\env.ps1
php -S localhost:8000 -t www/zenkoku router.php
