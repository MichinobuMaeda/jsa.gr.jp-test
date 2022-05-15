<?php
function upload($src_path) {
    $trg_path = preg_replace('/\\\/', '/', $src_path);
    exec('scp ' . $src_path . ' jsazenkoku@jsazenkoku.sakura.ne.jp:~/' . $trg_path);
}

if (count($argv) > 1) {
    upload($argv[1]);
} else {
    echo "\nUsage: php upload.php www/zenkoku/path_to/filename.html\n\n";
}
