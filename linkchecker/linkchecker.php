<?php
// リンクチェッカー
// --
// 2021-05-28 Michinobu Maeda
//   新規作成。
// --

print('Start: '.(new DateTime('NOW'))->format('c').PHP_EOL);

// Webサイトのコンテンツのパス
define('DOC_ROOT', dirname(__DIR__) . '/www/zenkoku');

// WikiとCGIを除くすべてのファイルのパスを取得する。
function getAllFiles(array &$file_list, $dir) {
    foreach (scandir(DOC_ROOT.'/'.$dir) as $path) {
        if (preg_match('/^\./', $path) || preg_match('/^\/(pukiwiki|d|cgi-bin)/', $dir.'/'.$path)) {
            // Skip
        } else if (is_dir(DOC_ROOT.'/'.$dir.'/'.$path)) {
            getAllFiles($file_list, $dir.'/'.$path);
        } else {
            $file_list[$dir.'/'.$path] = 0;
        }
    }
}

$file_list = [];
getAllFiles($file_list, '');

// すべてのHTMLファイルについて、
foreach (array_keys($file_list) as $path) {
    // if ($path === '/04pub/0401jjs/2021contents.html') {
    if (preg_match('/\.(html|html|php)$/i', $path)) {
        $html = file_get_contents(DOC_ROOT.'/'.$path);
        $matches = [];
        preg_match_all('/[^A-Za-z](src|href)="([^#][^"#?]*)"/i', $html, $matches);
        foreach ($matches[2] as $link) {
            if (preg_match('/^https?:\/\/(www\.)?jsa\.gr\.jp/', $link)) {
                $key = preg_replace('/^https?:\/\/(www\.)?jsa\.gr\.jp/', '', $link);
            } else if (preg_match('/^https?:\/\//', $link)) {
                continue;
            } else if (preg_match('/^\.\//', $link)) {
                $key = dirname($path).'/'.preg_replace('/^\.\//', '', $link);
            } else if (preg_match('/^[^\/]/', $link)) {
                $key = dirname($path).'/'.$link;
            } else {
                $key = $link;
            }
            while (preg_match('/[^\/]+\/\.\.\//', $key)) {
                $key = preg_replace('/[^\/]+\/\.\.\//', '', $key);
            }
            if (preg_match('/^\/(pukiwiki|d|cgi-bin)/', $key)) {
                continue;
            } else if (preg_match('/\/$/', $key) && array_key_exists($key.'index.html', $file_list)) {
                ++$file_list[$key.'index.html'];
            } else if (preg_match('/\/$/', $key) && array_key_exists($key.'index.htm', $file_list)) {
                ++$file_list[$key.'index.htm'];
            } else if (array_key_exists($key, $file_list)) {
                ++$file_list[$key];
            } else {
                print('Link error in '.$path.': '.$link.PHP_EOL);
            }
        }
    }
}

print('----------------------------------------------------------------'.PHP_EOL);
print('The following files are not referenced.'.PHP_EOL);
print('----------------------------------------------------------------'.PHP_EOL);

foreach($file_list as $key => $value) {
    if (0 < $value) { continue; }
    print($key.PHP_EOL);
}

print('----------------------------------------------------------------'.PHP_EOL);
print('End: '.(new DateTime('NOW'))->format('c').PHP_EOL);
