rsync -av --delete -e "ssh -F %USERPROFILE%\.ssh\config" jsazenkoku@jsa.gr.jp:/home/jsazenkoku/www/zenkoku/* ./www/zenkoku
rsync -av --delete -e "ssh -F %USERPROFILE%\.ssh\config" jsazenkoku@jsa.gr.jp:/home/jsazenkoku/OLD/* ./OLD/
rsync -av --delete -e "ssh -F %USERPROFILE%\.ssh\config" jsazenkoku@jsa.gr.jp:/home/jsazenkoku/data/* ./data/
rsync -av          -e "ssh -F %USERPROFILE%\.ssh\config" jsazenkoku@jsa.gr.jp:/home/jsazenkoku/log/* ./log/
rsync -av          -e "ssh -F %USERPROFILE%\.ssh\config" jsazenkoku@jsa.gr.jp:/home/jsazenkoku/linkchecker/* ./linkchecker/
