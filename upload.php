<?php
function upload($src_path) {
    $trg_path = preg_replace('/\\\/', '/', $src_path);
    exec('scp ' . $src_path . ' jsazenkoku@jsazenkoku.sakura.ne.jp:~/' . $trg_path);
}

if (count($argv) > 1) {
    upload($argv[1]);
}
