#!/bin/sh
rsync -av --delete jsa.gr.jp:/home/jsazenkoku/www/zenkoku/* ./www/zenkoku/
rsync -av --delete jsa.gr.jp:/home/jsazenkoku/OLD/* ./OLD/
rsync -av --delete jsa.gr.jp:/home/jsazenkoku/data/* ./data/
rsync -av --delete jsa.gr.jp:/home/jsazenkoku/twitter/* ./twitter/
rsync -av          jsa.gr.jp:/home/jsazenkoku/log/* ./log/
rsync -av          jsa.gr.jp:/home/jsazenkoku/linkchecker/* ./linkchecker/
