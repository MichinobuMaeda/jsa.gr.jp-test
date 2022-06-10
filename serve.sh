#!/bin/sh
source env.sh
php -S localhost:8000 -t www/zenkoku router.php
