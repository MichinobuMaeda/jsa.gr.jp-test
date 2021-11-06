#!/bin/bash
rsync -av --delete jsa.gr.jp:/home/jsazenkoku/www/zenkoku/* ./www/zenkoku/
rsync -av --delete jsa.gr.jp:/home/jsazenkoku/OLD/* ./OLD/
rsync -av --delete jsa.gr.jp:/home/jsazenkoku/data/* ./data/
rsync -av jsa.gr.jp:/home/jsazenkoku/log/* ./log/
