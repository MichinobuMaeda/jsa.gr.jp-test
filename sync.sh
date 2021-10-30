#!/bin/bash
rsync -av --delete jsa.gr.jp:/home/jsazenkoku/www/zenkoku/* ./www/zenkoku/
rsync -av --delete jsa.gr.jp:/home/jsazenkoku/OLD/* ./OLD/
