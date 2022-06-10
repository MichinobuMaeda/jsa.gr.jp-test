if [%~2]==[] (
    echo Usage: %~0 yyyy mm
    exit 1
)

%~dp0upload.sh www\zenkoku\04pub\%~1\%~1%~2JJStokusyu.pdf
%~dp0upload.sh www\zenkoku\04pub\0401jjs\%~1contents.html
%~dp0upload.sh www\zenkoku\04pub\index.html
%~dp0upload.sh www\zenkoku\jjs-cover-s.jpg
%~dp0upload.sh www\zenkoku\index.html
