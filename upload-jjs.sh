#!/bin/sh
if [ "$2" = "" ]
then
    echo Usage: $0 yyyy mm
    exit 1
fi

sh upload.sh www/zenkoku/04pub/$1/$1$2JJStokusyu.pdf
sh upload.sh www/zenkoku/04pub/0401jjs/$1contents.html
sh upload.sh www/zenkoku/04pub/index.html
sh upload.sh www/zenkoku/jjs-cover-s.jpg
sh upload.sh www/zenkoku/index.html
