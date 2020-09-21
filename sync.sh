#!/bin/bash
rsync -av jsa.gr.jp:/home/chasu/htdocs/* ./htdocs/
cp robots.txt htdocs/
