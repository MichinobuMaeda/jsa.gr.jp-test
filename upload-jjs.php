<?php
require_once('./upload.php');

function upload_all($yyy, $mm) {
    upload('www/zenkoku/04pub/' . $yyyy . '/' . $yyyy . $mm . 'JJStokusyu.pdf');
    upload('www/zenkoku/04pub/0401jjs/' . $yyyy . 'contents.html');
    upload('www/zenkoku/04pub/index.html');
    upload('www/zenkoku/jjs-cover-s.jpg');
    upload('www/zenkoku/index.html');
}

if (count($argv) > 2) {
    upload_all($argv[1], $argv[2]);
} else {
    echo "\nUsage: php upload-jjs.php yyyy mm\n\n";
}
