#!/bin/sh
if [ "$1" = "" ]
then
    echo Usage: $0 path
    exit 1
fi

scp $1 jsazenkoku@jsazenkoku.sakura.ne.jp:~/$1
