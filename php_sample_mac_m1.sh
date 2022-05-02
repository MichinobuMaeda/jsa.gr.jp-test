#!/bin/sh
export PHP_HOME=/opt/homebrew/opt/php@7.4/
export PATH=$PHP_HOME/bin:$PATH
php $@
